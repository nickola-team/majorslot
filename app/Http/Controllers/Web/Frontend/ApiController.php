<?php 

namespace VanguardLTE\Http\Controllers\Web\Frontend
{
    use VanguardLTE\Http\Controllers\Web\GameProviders\CQ9Controller;
    use VanguardLTE\Http\Controllers\Web\GameProviders\PPController;

    class ApiController extends \VanguardLTE\Http\Controllers\Controller
    {
        public function login(\VanguardLTE\Http\Requests\Auth\LoginRequest $request, \VanguardLTE\Repositories\Session\SessionRepository $sessionRepository)
        {
            $throttles = settings('throttle_enabled');
            if( $throttles && $this->hasTooManyLoginAttempts($request) ) 
            {
                return response()->json(['error' => true, 'msg' => trans('auth.throttle')]);
            }

            $credentials = $request->getCredentials();
            if( !\Auth::validate($credentials) ) 
            {
                if( $throttles ) 
                {
                    $this->incrementLoginAttempts($request);
                }
                return response()->json(['error' => true, 'msg' => trans('auth.failed')]);
            }
            $user = \Auth::getProvider()->retrieveByCredentials($credentials);
            if( $request->lang ) 
            {
                $user->update(['language' => $request->lang]);
            }
            if( !$user->hasRole('admin') && setting('siteisclosed') ) 
            {
                \Auth::logout();
                return response()->json(['error' => true, 'msg' => trans('app.site_is_turned_off')]);
            }
            if( $user->hasRole([
                1, 
                2, 
                3
            ]) && (!$user->shop || $user->shop->is_blocked) ) 
            {
                return response()->json(['error' => true, 'msg' => trans('app.your_shop_is_blocked')]);
            }
            if( $user->isBanned() ) 
            {
                return response()->json(['error' => true, 'msg' => trans('app.your_account_is_banned')]);
            }
            
            if(count($sessionRepository->getUserSessions($user->id)) ) 
            {
                return response()->json(['error' => true, 'msg' => '회원님은 이미 다른 기기에서 로그인되었습니다']);
            }

            \Auth::login($user, settings('remember_me') && $request->get('remember'));
            
            $user->update(['api_token' => $user->generateCode(36)]);

            return response()->json(['error' => false, 'msg' => '성공']);
        }
        public function getgamelink(\Illuminate\Http\Request $request)
        {
            $provider = $request->provider;
            $gamecode = $request->gamecode;
            $res = call_user_func('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($provider) . 'Controller::getgamelink', $gamecode);
            return response()->json($res);

        }
        public function gamelistbyProvider($provider, $href)
        {
            $games = call_user_func('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($provider) . 'Controller::getgamelist', $href);
            return $games;
        }

        public function gamelist($categoryIDs, $wherenot=false)
        {
            $shop_id = (\Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::user()->shop_id : 0);
            $shop = \VanguardLTE\Shop::find($shop_id);

            $games = \VanguardLTE\Game::where([
                'view' => 1, 
                'shop_id' => $shop_id
            ]);
            if ($wherenot) {
                $game_ids = \VanguardLTE\GameCategory::whereNotIn('category_id', $categoryIDs)->groupBy('game_id')->pluck('game_id')->toArray();
            }
            else
            {
                $game_ids = \VanguardLTE\GameCategory::whereIn('category_id', $categoryIDs)->groupBy('game_id')->pluck('game_id')->toArray();
            }
            if( count($game_ids) > 0 ) 
            {
                $games = $games->whereIn('id', $game_ids);
            }
            else
            {
                $games = $games->where('id', 0);
            }

            $detect = new \Detection\MobileDetect();
            $devices = [];
            if( $detect->isMobile() || $detect->isTablet() ) 
            {
                $games = $games->whereIn('device', [
                    0, 
                    2
                ]);
                $devices = [
                    0, 
                    2
                ];
            }
            else
            {
                $games = $games->whereIn('device', [
                    1, 
                    2
                ]);
                $devices = [
                    1, 
                    2
                ];
            }

            if( $shop ) 
            {
                switch( $shop->orderby ) 
                {
                    case 'AZ':
                        $games = $games->orderBy('name', 'ASC');
                        break;
                    case 'Rand':
                        $games = $games->inRandomOrder();
                        break;
                    case 'RTP':
                        $games = $games->orderBy(\DB::raw('CASE WHEN(stat_in > 0) THEN(stat_out*100)/stat_in ELSE 0 END '), 'DESC');
                        break;
                    case 'Count':
                        $games = $games->orderBy('bids', 'DESC');
                        break;
                    case 'Date':
                        $games = $games->orderBy('created_at', 'DESC');
                        break;
                }
            }
            $games = $games->get();
            $data = [];
            foreach ($games as $game)
            {
                $data[] = [
                    'name' => $game->name,
                    'title' => __('gamename.' . $game->title)
                ];
            }
            return $data;
        }
        public function inoutList_json(\Illuminate\Http\Request $request)
        {
            $res['now'] = \Carbon\Carbon::now();
            $transactions = \VanguardLTE\WithdrawDeposit::where([
                'status' => 0,
                'payeer_id' => $request->id])->get();
            $res['count'] = $transactions->count();
            return json_encode($res);
        }

        public function getgamelist(\Illuminate\Http\Request $request){
            if( !\Illuminate\Support\Facades\Auth::check() ) {
                return response()->json(['error' => true, 'msg' => trans('app.site_is_turned_off'), 'code' => '001']);
            }

            $shop_id = (\Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::user()->shop_id : 0);
            $category = $request->category;
            if( $category == '' ) 
            {
                return response()->json(['error' => true, 'msg' => '카테고리ID 에러', 'code' => '002']);
            }

            $cat1 = \VanguardLTE\Category::where([
                'href' => $category, 
                'shop_id' => $shop_id
            ])->first();
            if( !$cat1) 
            {
                return response()->json(['error' => true, 'msg' => '존재하지 않는 카테고리입니다.', 'code' => '002']);
            }

            $categories = [$cat1->id];
            if ($cat1->provider != null)
            {
                $selectedGames = $this->gamelistbyProvider($cat1->provider, $cat1->href);
            }
            else{
                if (str_contains(\Illuminate\Support\Facades\Auth::user()->username, 'testfor')) // test account for game providers
                {
                    $selectedGames = [];
                }
                else
                {
                    $selectedGames = $this->gamelist($categories, false);
                }
            }

            return response()->json(['error' => false, 'games' => $selectedGames, 'others' => []]);
        }

        public function changeBankAccount(\Illuminate\Http\Request $request){
            if( !\Illuminate\Support\Facades\Auth::check() ) {
                return response()->json(['error' => true, 'msg' => trans('app.site_is_turned_off'), 'code' => '001']);
            }

            $user = \VanguardLTE\User::find(\Auth::id());
            
            if( !$request->bank_name ) 
            {
                return response()->json([
                    'error' => true, 
                    'msg' => '은행을 선택해주세요',
                    'code' => '001'
                ], 200);
            }
            if( !$request->account_no ) 
            {
                return response()->json([
                    'error' => true, 
                    'msg' => '계좌번호를 입력해주세요',
                    'code' => '002'
                ], 200);
            }
            if( !$request->recommender ) 
            {
                return response()->json([
                    'error' => true, 
                    'msg' => '예금주명을 입력해주세요',
                    'code' => '003'
                ], 200);
            }

            $user->update([
                'bank_name' => $request->bank_name,
                'account_no' => $request->account_no,
                'recommender' => $request->recommender
            ]);
            
            return response()->json(['error' => false]);
        }

        public function convertDealBalance(\Illuminate\Http\Request $request){
            if( !\Illuminate\Support\Facades\Auth::check() ) {
                return response()->json(['error' => true, 'msg' => trans('app.site_is_turned_off'), 'code' => '001']);
            }

            $user = \VanguardLTE\User::find(\Auth::id());
            if(!$user->hasRole('manager') && !$user->hasRole('distributor') && !$user->hasRole('agent')){
                return response()->json([
                    'error' => true, 
                    'msg' => '딜비전환권한이 없습니다.',
                    'code' => '001'
                ], 200);
            }
           
            if($user->hasRole('manager')){
                $shop = $user->shop;
                $real_deal_balance = $shop->deal_balance - $shop->mileage;
                if ($real_deal_balance > 0) {
                    $shop->balance = $shop->balance + $real_deal_balance;
                    $open_shift = \VanguardLTE\OpenShift::where([
                        'shop_id' => $shop->id, 
                        'type' => 'shop',
                        'end_date' => null
                    ])->first();
                    if( $open_shift ) 
                    {
                        $open_shift->increment('convert_deal', $real_deal_balance);
                    }
                    \VanguardLTE\ShopStat::create([
                        'user_id' => $user->parent_id,
                        'type' => 'add',
                        'sum' => $real_deal_balance,
                        'shop_id' => $shop->id,
                        'date_time' => \Carbon\Carbon::now()
                    ]);

                    $shop->deal_balance = 0;
                    $shop->mileage = 0;
                    $shop->save();
                }
                else{
                    return response()->json([
                        'error' => true, 
                        'msg' => '수익금이 없습니다.',
                        'code' => '001'
                    ], 200);
                }
            }
            else {
                $real_deal_balance = $user->deal_balance - $user->mileage;
                if ($real_deal_balance > 0) {
                    $user->balance = $user->balance + $real_deal_balance;
                    $open_shift = \VanguardLTE\OpenShift::where([
                        'user_id' => $user->id, 
                        'end_date' => null,
                        'type' => 'partner'
                    ])->first();
                    if( $open_shift ) 
                    {
                        $open_shift->increment('convert_deal', $real_deal_balance);
                    }

                    \VanguardLTE\Transaction::create([
                        'user_id' => $user->id,
                        'payeer_id' => $user->id,
                        'system' => $user->username,
                        'type' => 'add',
                        'summ' => $real_deal_balance,
                        'shop_id' => $user->shop_id
                    ]);

                    $user->deal_balance = 0;
                    $user->mileage = 0;
                    $user->save();
                }
                else{
                    return response()->json([
                        'error' => true, 
                        'msg' => '수익금이 없습니다.',
                        'code' => '001'
                    ], 200);
                }
            }
            
            return response()->json(['error' => false]);
        }

        public function withdrawDealMoney(\Illuminate\Http\Request $request){
            return response()->json(['error' => true, 'msg' => 'not implemented']);
        }

        public function deposit(\Illuminate\Http\Request $request){
            if( !\Illuminate\Support\Facades\Auth::check() ) {
                return response()->json(['error' => true, 'msg' => trans('app.site_is_turned_off'), 'code' => '001']);
            }

            $user = auth()->user();
            if($user->bank_name == null || $user->bank_name == ''){
                return response()->json([
                    'error' => true, 
                    'msg' => '계좌정보를 입력해주세요',
                    'code' => '004'
                ], 200);
            }
            if( !$request->money ) 
            {
                return response()->json([
                    'error' => true, 
                    'msg' => '충전금액을 입력해주세요'
                ], 200);
            }
            if($user->hasRole('manager')){
                //send it to agent.
                $distr = $user->referral;
                if ($distr) {
                    \VanguardLTE\WithdrawDeposit::create([
                        'user_id' => $user->id,
                        'payeer_id' => $distr->parent_id,
                        'type' => 'add',
                        'sum' => $request->money,
                        'status' => 0,
                        'shop_id' => $user->shop_id,
                        'created_at' => \Carbon\Carbon::now(),
                        'updated_at' => \Carbon\Carbon::now(),
                        'bank_name' => $user->bank_name,
                        'account_no' => $user->account_no,
                        'recommender' => $user->recommender,
                        'partner_type' => 'shop'
                    ]);
                }
            }
            else {
                \VanguardLTE\WithdrawDeposit::create([
                    'user_id' => $user->id,
                    'payeer_id' => $user->parent_id,
                    'type' => 'add',
                    'sum' => $request->money,
                    'status' => 0,
                    'shop_id' => 0,
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                    'bank_name' => $user->bank_name,
                    'account_no' => $user->account_no,
                    'recommender' => $user->recommender,
                    'partner_type' => 'partner'
                ]);
            }

            return response()->json(['error' => false]);
        }

        public function withdraw(\Illuminate\Http\Request $request)
        {
            if( !\Illuminate\Support\Facades\Auth::check() ) {
                return response()->json(['error' => true, 'msg' => trans('app.site_is_turned_off'), 'code' => '001']);
            }

            $user = auth()->user();
            if($user->bank_name == null || $user->bank_name == ''){
                return response()->json([
                    'error' => true, 
                    'msg' => '계좌정보를 입력해주세요',
                    'code' => '004'
                ], 200);
            }
            if( !$request->money ) 
            {
                return response()->json([
                    'error' => true, 
                    'msg' => '환전금액을 입력해주세요',
                    'code' => '001'
                ], 200);
            }

            if($user->hasRole('manager')){
                if($request->money > $user->shop->balance) {
                    return response()->json([
                        'error' => true, 
                        'msg' => '환전금액은 보유금액을 초과할수 없습니다',
                        'code' => '002'
                    ], 200);
                }
            }
            else {
                if($request->money > $user->balance) {
                    return response()->json([
                        'error' => true, 
                        'msg' => '환전금액은 보유금액을 초과할수 없습니다',
                        'code' => '002'
                    ], 200);
                }
            }
            
            

            if($user->hasRole('manager')){
                //send it to agent.
                $distr = $user->referral;
                if ($distr) {
                    \VanguardLTE\WithdrawDeposit::create([
                        'user_id' => $user->id,
                        'payeer_id' => $distr->parent_id,
                        'type' => 'out',
                        'sum' => $request->money,
                        'status' => 0,
                        'shop_id' => $user->shop_id,
                        'created_at' => \Carbon\Carbon::now(),
                        'updated_at' => \Carbon\Carbon::now(),
                        'bank_name' => $user->bank_name,
                        'account_no' => $user->account_no,
                        'recommender' => $user->recommender,
                        'partner_type' => 'shop'
                    ]);

                    $shop = \VanguardLTE\Shop::where('id', $user->shop_id)->get()->first();
                    $shop->update([
                        'balance' => $shop->balance - $request->money,
                    ]);

                    $open_shift = \VanguardLTE\OpenShift::where([
                        'shop_id' => $shop->id, 
                        'end_date' => null,
                        'type' => 'shop'
                    ])->first();
                    if( $open_shift ) 
                    {
                        $open_shift->increment('balance_out', $request->money);
                    }
                }
            }
            else {
                $user->update(
                    ['balance' => $user->balance - $request->money]
                );
                \VanguardLTE\WithdrawDeposit::create([
                    'user_id' => $user->id,
                    'payeer_id' => $user->parent_id,
                    'type' => 'out',
                    'sum' => $request->money,
                    'status' => 0,
                    'shop_id' => 0,
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                    'bank_name' => $user->bank_name,
                    'account_no' => $user->account_no,
                    'recommender' => $user->recommender,
                    'partner_type' => 'partner'
                ]);

                $open_shift = \VanguardLTE\OpenShift::where([
                    'user_id' => $user->id, 
                    'end_date' => null,
                    'type' => 'partner'
                ])->first();
                if( $open_shift ) 
                {
                    $open_shift->increment('balance_out', $request->money);
                }

            }
            return response()->json(['error' => false]);
        }


        public function allowInOut(\Illuminate\Http\Request $request){
            $in_out_id = $request->in_out_id;
            $transaction = \VanguardLTE\WithdrawDeposit::where('id', $in_out_id)->get()->first();
            $amount = $transaction->sum;
            $type = $transaction->type;
            $requestuser = \VanguardLTE\User::where('id', $transaction->user_id)->get()->first();

            if ($requestuser->hasRole('manager')) // for shops
            {
                $shop = \VanguardLTE\Shop::where('id', $transaction->shop_id)->get()->first();
                if($type == 'add'){
                    if(auth()->user()->balance < $amount) {
                        return redirect()->route('backend.in_out_manage')->withSuccess(['보유금액이 충분하지 않습니다.']);
                    }

                    auth()->user()->update(
                        ['balance' => auth()->user()->balance - $amount]
                    );

                    $shop->update([
                        'balance' => $shop->balance + $amount
                    ]);

                    $open_shift = \VanguardLTE\OpenShift::where([
                        'user_id' => auth()->user()->id, 
                        'end_date' => null,
                        'type' => 'partner'
                    ])->first();
                    if( $open_shift ) 
                    {
                        $open_shift->increment('balance_out', $amount);
                    }
                    $open_shift = \VanguardLTE\OpenShift::where([
                        'shop_id' => $shop->id, 
                        'end_date' => null,
                        'type' => 'shop'
                    ])->first();
                    if( $open_shift ) 
                    {
                        $open_shift->increment('balance_in', $amount);
                    }

                }
                else if($type == 'out'){
                    auth()->user()->update(
                        ['balance' => auth()->user()->balance + $amount]
                    );
                    $open_shift = \VanguardLTE\OpenShift::where([
                        'user_id' => auth()->user()->id, 
                        'end_date' => null,
                        'type' => 'partner'
                    ])->first();
                    if( $open_shift ) 
                    {
                        $open_shift->increment('balance_in', $amount);
                    }

                }
                $transaction->update([
                    'status' => 1
                ]);

                \VanguardLTE\ShopStat::create([
                    'user_id' => auth()->user()->id,
                    'type' => $type,
                    'sum' => $amount,
                    'request_id' => $transaction->id,
                    'shop_id' => $transaction->shop_id,
                    'date_time' => \Carbon\Carbon::now()
                ]);
            }
            else // for partners
            {

                if($type == 'add'){

                    $result = $requestuser->addBalance('add', $amount, false, 0, $transaction->id);
                    $result = json_decode($result, true);
                    if ($result['status'] == 'error')
                    {
                        return redirect()->route('backend.in_out_manage')->withErrors($result['message']);
                    }
                }
                else if($type == 'out'){
                    auth()->user()->update(
                        ['balance' => auth()->user()->balance + $amount]
                    );
                    $open_shift = \VanguardLTE\OpenShift::where([
                        'user_id' => auth()->user()->id, 
                        'end_date' => null,
                        'type' => 'partner'
                    ])->first();
                    if( $open_shift ) 
                    {
                        $open_shift->increment('balance_in', $amount);
                    }
                    \VanguardLTE\Transaction::create([
                        'user_id' => $transaction->user_id,
                        'payeer_id' => auth()->user()->id,
                        'system' => auth()->user()->username,
                        'type' => $type,
                        'summ' => $amount,
                        'request_id' => $transaction->id,
                        'shop_id' => $transaction->shop_id,
                        'created_at' => \Carbon\Carbon::now(),
                        'updated_at' => \Carbon\Carbon::now()
                    ]);
                }

                $transaction->update([
                    'status' => 1
                ]);
            }
            return redirect()->route('backend.in_out_manage')->withSuccess(['조작이 성공적으로 진행되었습니다.']);
        }

        public function rejectInOut(\Illuminate\Http\Request $request){
            $in_out_id = $request->out_id;
            if(auth()->user()->hasRole('distributor')){
                $shop_stat = \VanguardLTE\WithdrawDeposit::where('id', $in_out_id)->get()->first();
                $amount = $shop_stat->sum;
                $type = $shop_stat->type;
                $shop = \VanguardLTE\Shop::where('id', $shop_stat->shop_id)->get()->first();
                if($type == 'add'){
                   

                }
                else if($type == 'out'){
                    $shop->update([
                        'balance' => $shop->balance + $amount
                    ]);
                    $open_shift = \VanguardLTE\OpenShift::where([
                        'shop_id' => $shop->id, 
                        'end_date' => null,
                        'type' => 'shop'
                    ])->first();
                    if( $open_shift ) 
                    {
                        $open_shift->decrement('balance_out', $amount);
                    }
                }
                else if($type == 'deal_out'){
                    $shop->update([
                        'deal_balance' => $shop->deal_balance + $amount
                    ]);
                }

                $shop_stat->update([
                   'status' => 2
                ]);
           }
           else {
               $transaction = \VanguardLTE\WithdrawDeposit::where('id', $in_out_id)->get()->first();
               if($transaction == null){
                return redirect()->route('backend.in_out_manage')->withErrors(['유효하지 않은 조작입니다.']);
               }
               $amount = $transaction->sum;
               $type = $transaction->type;
               $requestuser = \VanguardLTE\User::where('id', $transaction->user_id)->get()->first();

               if ($requestuser->hasRole('manager')) // for shops
                {
                    $shop = \VanguardLTE\Shop::where('id', $transaction->shop_id)->get()->first();
                    if($type == 'out'){
                        $shop->update([
                            'balance' => $shop->balance + $amount
                        ]);
                        $open_shift = \VanguardLTE\OpenShift::where([
                            'shop_id' => $shop->id, 
                            'end_date' => null,
                            'type' => 'shop'
                        ])->first();
                        if( $open_shift ) 
                        {
                            $open_shift->decrement('balance_out', $amount);
                        }
                    }

                    $transaction->update([
                    'status' => 2
                    ]);
                }
                else
                {
                    if($type == 'out'){
                        $requestuser->update([
                            'balance' => $requestuser->balance + $amount
                        ]);
                        $open_shift = \VanguardLTE\OpenShift::where([
                            'user_id' => $requestuser->id, 
                            'end_date' => null,
                            'type' => 'partner'
                        ])->first();
                        if( $open_shift ) 
                        {
                            $open_shift->decrement('balance_out', $amount);
                        }
                    }
    
                    $transaction->update([
                       'status' => 2
                    ]);
                }
           }
           return redirect()->route('backend.in_out_manage')->withSuccess(['조작이 성공적으로 진행되었습니다.']);
        }
    }
}
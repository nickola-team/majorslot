<?php 

namespace VanguardLTE\Http\Controllers\Web\Frontend
{
    use Illuminate\Support\Facades\Http;
    class ApiController extends \VanguardLTE\Http\Controllers\Controller
    {
        public function login(\VanguardLTE\Http\Requests\Auth\LoginRequest $request, \VanguardLTE\Repositories\Session\SessionRepository $sessionRepository)
        {
            $siteMaintence = env('MAINTENANCE', 0);

            if( $siteMaintence==1 ) 
            {
                \Auth::logout();
                return response()->json(['error' => true, 'msg' => '사이트 점검중입니다']);
            }
            
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

            //check admin id per site
            $site = \VanguardLTE\WebSite::where('domain', $request->root())->first();
            $adminid = 1; //default admin id
            if ($site)
            {
                $adminid = $site->adminid;
            }

            $admin = $user;
            while ($admin !=null && !$admin ->hasRole('admin'))
            {
                if (!$admin->isActive())
                {
                    return response()->json(['error' => true, 'msg' => '계정이 임시 차단되었습니다.']);
                }
                $admin = $admin->referral;
            }

            if (!$admin || $admin->id != $adminid)
            {
                return response()->json(['error' => true, 'msg' => trans('auth.failed')]);
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
            
            $sessions = $sessionRepository->getUserSessions($user->id);
            $expiretime = env('EXPIRE_TIME_CLOSE'. 30);
            $count = count($sessions);
            if(count($sessions) > 0 ) 
            {
                foreach ($sessions as $s)
                {
                    if ($s->last_activity->diffInSeconds(\Carbon\Carbon::now()) >  $expiretime)
                    {
                        $count--;
                    }
                }
                if ($count > 0){
                    return response()->json(['error' => true, 'msg' => '회원님은 이미 다른 기기에서 로그인되었습니다']);
                }
            }

            $sessionRepository->invalidateAllSessionsForUser($user->id);

            \Auth::login($user, settings('remember_me') && $request->get('remember'));
            
            $user->update(['api_token' => $user->generateCode(36)]);

            event(new \VanguardLTE\Events\User\LoggedIn());


            return response()->json(['error' => false, 'msg' => '성공']);
        }
        public function getbalance(\Illuminate\Http\Request $request)
        {
            if( !\Illuminate\Support\Facades\Auth::check() ) {
                return response()->json(['error' => true, 'msg' => trans('app.site_is_turned_off'), 'code' => '001']);
            }

            $balance = number_format(\Illuminate\Support\Facades\Auth::user()->balance,2);

            return response()->json(['error' => false, 'balance' => $balance]);
        }
        public function getgamelink(\Illuminate\Http\Request $request)
        {
            if( !\Illuminate\Support\Facades\Auth::check() ) {
                return response()->json(['error' => true, 'msg' => trans('app.site_is_turned_off'), 'code' => '001']);
            }
            $provider = $request->provider;
            $gamecode = $request->gamecode;
            $major_domain = env('MAJOR_DOMAIN', 'http://major999.com/');
            $brand = env('MAJOR_BRAND');
            if ($brand == null || $brand == 'major') //this is main server
            {
                $res = call_user_func('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($provider) . 'Controller::getgamelink', $gamecode);
                return response()->json($res);
            }
            else
            {
                $user = auth()->user();
                $response = Http::withHeaders([
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ])->get($major_domain . 'api/website/getgamelink', [
                    'token' => $user->api_token . '_' . $brand,
                    'provider' => $provider,
                    'gamecode' => $gamecode,
                ]);
                if (!$response->ok())
                {
                    return null;
                }
                $data = $response->json();
                return response()->json($data);
            }
        }

        public function gamelistbyProvider($provider, $href)
        {
            $games = null;
            $major_domain = env('MAJOR_DOMAIN', 'http://major999.com/');
            $brand = env('MAJOR_BRAND');
            if ($brand == null || $brand == 'major') //this is main server
            {
                $games = call_user_func('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($provider) . 'Controller::getgamelist', $href);
            }
            else
            {
                $gameList = \Illuminate\Support\Facades\Redis::get($href.'list');
                if ($gameList)
                {
                    $games = json_decode($gameList, true);
                    return $games;
                }

                $response = Http::withHeaders([
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ])->get($major_domain . 'api/website/getgamelist', [
                    'provider' => $provider,
                    'href' => $href,
                    'brand' => $brand
                ]);
                if (!$response->ok())
                {
                    return null;
                }
                
                $games = $response->json();
                \Illuminate\Support\Facades\Redis::set($href.'list', json_encode($games));
            }
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
            if( !\Illuminate\Support\Facades\Auth::check() ) {
                return response()->json(['error' => true, 'add' => 0, 'out'=>0,'now' => \Carbon\Carbon::now()]);
            }

            if (isset($request->rating))
            {
                auth()->user()->rating = $request->rating;
                auth()->user()->save();
            }

            $res['now'] = \Carbon\Carbon::now();
            if (\Session::get('isCashier'))
            {
                $payeer_ids = auth()->user()->childPartners();
                $transactions1 = \VanguardLTE\WithdrawDeposit::where(['status'=>\VanguardLTE\WithdrawDeposit::REQUEST,'type'=>'add'])->whereIn('payeer_id',  $payeer_ids);
                $transactions2 = \VanguardLTE\WithdrawDeposit::where(['status'=>\VanguardLTE\WithdrawDeposit::REQUEST,'type'=>'out'])->whereIn('payeer_id',  $payeer_ids);
            }
            else
            {
                $transactions1 = \VanguardLTE\WithdrawDeposit::where([
                    'type' => 'add',
                    'status' => \VanguardLTE\WithdrawDeposit::REQUEST,
                    'payeer_id' => $request->id]);
                $transactions2 = \VanguardLTE\WithdrawDeposit::where([
                    'type' => 'out',
                    'status' => \VanguardLTE\WithdrawDeposit::REQUEST,
                    'payeer_id' => $request->id]);
            }
            $res['add'] = $transactions1->count();
            $res['out'] = $transactions2->count();
            $res['rating'] = auth()->user()->rating;
            return response()->json($res);
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

            $summ = str_replace(',','',$request->summ);
           
            if($user->hasRole('manager')){
                $shop = $user->shop;
                $real_deal_balance = $shop->deal_balance - $shop->mileage;
                if ($summ )
                {
                    $summ = abs($summ);
                    if ($real_deal_balance < $summ)
                    {
                        return response()->json([
                            'error' => true, 
                            'msg' => '수익금이 부족합니다.',
                            'code' => '000'
                        ], 200);
                    }
                }
                else
                {
                    $summ = $real_deal_balance;
                }
                if ($summ > 0) {
                    //out balance from master
                    $distr = $user->referral;
                    $agent = $distr->referral;
                    $master = $agent->referral;
                    if ($master->balance < $summ * 2)
                    {
                        return response()->json([
                            'error' => true, 
                            'msg' => '본사보유금이 부족합니다',
                            'code' => '001'
                        ], 200);
                    }
                    $master->update(
                        ['balance' => $master->balance - $summ * 2]
                    );
                    $master = $master->fresh();
                    $open_shift = \VanguardLTE\OpenShift::where([
                        'user_id' => $master->id, 
                        'type' => 'partner',
                        'end_date' => null
                    ])->first();
                    if( $open_shift ) 
                    {
                        $open_shift->increment('money_in', $summ * 2);
                    }

                    $old = $shop->balance;
                    $shop->balance = $shop->balance + $summ;
                    $open_shift = \VanguardLTE\OpenShift::where([
                        'shop_id' => $shop->id, 
                        'type' => 'shop',
                        'end_date' => null
                    ])->first();
                    if( $open_shift ) 
                    {
                        $open_shift->increment('convert_deal', $summ);
                    }

                    $shop->deal_balance = $real_deal_balance - $summ;
                    $shop->mileage = 0;
                    $shop->save();
                    $shop = $shop->fresh();
                    \VanguardLTE\ShopStat::create([
                        'user_id' => $master->id,
                        'type' => 'deal_out',
                        'sum' => $summ,
                        'old' => $old,
                        'new' => $shop->balance,
                        'balance' => $master->balance,
                        'shop_id' => $shop->id,
                        'date_time' => \Carbon\Carbon::now()
                    ]);
                   
                }
                else{
                    return response()->json([
                        'error' => true, 
                        'msg' => '수익금이 없습니다.',
                        'code' => '000'
                    ], 200);
                }
            }
            else {
                $real_deal_balance = $user->deal_balance - $user->mileage;
                if ($summ )
                {
                    $summ = abs($summ);
                    if ($real_deal_balance < $summ)
                    {
                        return response()->json([
                            'error' => true, 
                            'msg' => '수익금이 부족합니다.',
                            'code' => '000'
                        ], 200);
                    }
                }
                else
                {
                    $summ = $real_deal_balance;
                }
                if ($summ > 0) {
                    //out balance from master
                    $master = $user->referral;
                    while ($master!=null && !$master->hasRole('master'))
                    {
                        $master = $master->referral;
                    }

                    if ($master == null)
                    {
                        return response()->json([
                            'error' => true, 
                            'msg' => '본사를 찾을수 없습니다.',
                            'code' => '001'
                        ], 200);
                    }
                    
                    if ($master->balance < $summ *2)
                    {
                        return response()->json([
                            'error' => true, 
                            'msg' => '본사보유금이 부족합니다',
                            'code' => '001'
                        ], 200);
                    }
                    $master->update(
                        ['balance' => $master->balance - $summ*2]
                    );
                    $open_shift = \VanguardLTE\OpenShift::where([
                        'user_id' => $master->id, 
                        'type' => 'partner',
                        'end_date' => null
                    ])->first();
                    if( $open_shift ) 
                    {
                        $open_shift->increment('money_in', $summ);
                    }
                    $old = $user->balance;

                    $user->balance = $user->balance + $summ;
                    $open_shift = \VanguardLTE\OpenShift::where([
                        'user_id' => $user->id, 
                        'end_date' => null,
                        'type' => 'partner'
                    ])->first();
                    if( $open_shift ) 
                    {
                        $open_shift->increment('convert_deal', $summ);
                    }

                    $user->deal_balance = $real_deal_balance - $summ;
                    $user->mileage = 0;
                    $user->save();
                    $user = $user->fresh();

                    $master = $master->fresh();

                    \VanguardLTE\Transaction::create([
                        'user_id' => $user->id,
                        'payeer_id' => $master->id,
                        'system' => $user->username,
                        'type' => 'deal_out',
                        'summ' => $summ,
                        'old' => $old,
                        'new' => $user->balance,
                        'balance' => $master->balance,
                        'shop_id' => 0
                    ]);

                }
                else{
                    return response()->json([
                        'error' => true, 
                        'msg' => '수익금이 없습니다.',
                        'code' => '000'
                    ], 200);
                }
            }
            
            return response()->json(['error' => false]);
        }

        public function withdrawDealMoney(\Illuminate\Http\Request $request){
            if( !\Illuminate\Support\Facades\Auth::check() ) {
                return response()->json(['error' => true, 'msg' => trans('app.site_is_turned_off'), 'code' => '001']);
            }

            $user = auth()->user();
            if (!$user->hasRole('manager'))
            {
                return response()->json([
                    'error' => true, 
                    'msg' => '매장에서만 가능한 기능입니다',
                    'code' => '004'
                ], 200);
            }
            if($user->bank_name == null || $user->bank_name == ''){
                return response()->json([
                    'error' => true, 
                    'msg' => '계좌정보를 입력해주세요',
                    'code' => '004'
                ], 200);
            }

            if( !$request->summ ) 
            {
                return response()->json([
                    'error' => true, 
                    'msg' => '환전금액을 입력해주세요',
                    'code' => '001'
                ], 200);
            }

            $summ = abs($request->summ);
            $shop = \VanguardLTE\Shop::where('id', $user->shop_id)->first();

            if (!$shop)
            {
                return response()->json([
                    'error' => true, 
                    'msg' => '유효하지 않은 매장입니다',
                    'code' => '001'
                ], 200);
            }

            if($summ > $shop->deal_balance) {
                return response()->json([
                    'error' => true, 
                    'msg' => '환전금액은 수익금액을 초과할수 없습니다',
                    'code' => '002'
                ], 200);
            }

            //send it to master.
            $distr = $user->referral;
            if ($distr) {
                $agent = $distr->referral;
                \VanguardLTE\WithdrawDeposit::create([
                    'user_id' => $user->id,
                    'payeer_id' => $agent->parent_id,
                    'type' => 'deal_out',
                    'sum' => $summ,
                    'status' => \VanguardLTE\WithdrawDeposit::REQUEST,
                    'shop_id' => $user->shop_id,
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                    'bank_name' => $user->bank_name,
                    'account_no' => $user->account_no,
                    'recommender' => $user->recommender,
                    'partner_type' => 'shop'
                ]);

                $shop->update([
                    'deal_balance' => $shop->deal_balance - $summ,
                ]);

                $open_shift = \VanguardLTE\OpenShift::where([
                    'shop_id' => $shop->id, 
                    'end_date' => null,
                    'type' => 'shop'
                ])->first();
                if( $open_shift ) 
                {
                    $open_shift->increment('convert_deal', $summ);
                }
            }
            return response()->json(['error' => false]);
        }

        public function deposit(\Illuminate\Http\Request $request){
            if( !\Illuminate\Support\Facades\Auth::check() ) {
                return response()->json(['error' => true, 'msg' => trans('app.site_is_turned_off'), 'code' => '001']);
            }

            $user = auth()->user();
            if ($user->hasRole('user'))
            {
                return response()->json([
                    'error' => true, 
                    'msg' => '개인충전은 지원하지 않습니다',
                    'code' => '004'
                ], 200);
            }
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
            $money = abs(str_replace(',','', $request->money));
            if($user->hasRole('manager')){
                //send it to master.
                $distr = $user->referral;
                if ($distr) {
                    $agent = $distr->referral;
                    \VanguardLTE\WithdrawDeposit::create([
                        'user_id' => $user->id,
                        'payeer_id' => $agent->parent_id,
                        'type' => 'add',
                        'sum' => $money,
                        'status' => \VanguardLTE\WithdrawDeposit::REQUEST,
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
                if ($user->hasRole('distributor'))
                {
                    $agent = $user->referral;
                }
                else 
                {
                    $agent = $user;
                }
                \VanguardLTE\WithdrawDeposit::create([
                    'user_id' => $user->id,
                    'payeer_id' => $agent->parent_id,
                    'type' => 'add',
                    'sum' => $money,
                    'status' => \VanguardLTE\WithdrawDeposit::REQUEST,
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
            if ($user->hasRole('user'))
            {
                return response()->json([
                    'error' => true, 
                    'msg' => '개인환전은 지원하지 않습니다',
                    'code' => '004'
                ], 200);
            }
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

            $money = abs(str_replace(',','', $request->money));

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
                //send it to master.
                $distr = $user->referral;
                if ($distr) {
                    $agent = $distr->referral;
                    \VanguardLTE\WithdrawDeposit::create([
                        'user_id' => $user->id,
                        'payeer_id' => $agent->parent_id,
                        'type' => 'out',
                        'sum' => $money,
                        'status' => \VanguardLTE\WithdrawDeposit::REQUEST,
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
                        'balance' => $shop->balance - $money,
                    ]);

                    $open_shift = \VanguardLTE\OpenShift::where([
                        'shop_id' => $shop->id, 
                        'end_date' => null,
                        'type' => 'shop'
                    ])->first();
                    if( $open_shift ) 
                    {
                        $open_shift->increment('balance_out', $money);
                    }
                }
            }
            else {
                $user->update(
                    ['balance' => $user->balance - $money]
                );
                if ($user->hasRole('distributor'))
                {
                    $agent = $user->referral;
                }
                else 
                {
                    $agent = $user;
                }
                \VanguardLTE\WithdrawDeposit::create([
                    'user_id' => $user->id,
                    'payeer_id' => $agent->parent_id,
                    'type' => 'out',
                    'sum' => $money,
                    'status' => \VanguardLTE\WithdrawDeposit::REQUEST,
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
                    $open_shift->increment('balance_out', $money);
                }

            }
            return response()->json(['error' => false]);
        }

        public function waitInOut($id, \Illuminate\Http\Request $request){
            if( !\Illuminate\Support\Facades\Auth::check() ) {
                return redirect()->back()->withErrors(['로그인하세요']);
            }
            $transaction = \VanguardLTE\WithdrawDeposit::where('id', $id)->get()->first();
            if (!$transaction)
            {
                return redirect()->back()->withErrors(['유효하지 않은 신청입니다']);
            }
            if ($transaction->status!=\VanguardLTE\WithdrawDeposit::REQUEST)
            {
                return redirect()->back()->withErrors(['이미 처리된 신청내역입니다.']);
            }
            $transaction->update(['status' => \VanguardLTE\WithdrawDeposit::WAIT]);
            return redirect()->back()->withSuccess(['대기처리하였습니다.']);
        }
        public function allowInOut(\Illuminate\Http\Request $request){
            if( !\Illuminate\Support\Facades\Auth::check() ) {
                return redirect()->back()->withErrors(['로그인하세요']);
            }
            $in_out_id = $request->in_out_id;
            $transaction = \VanguardLTE\WithdrawDeposit::where('id', $in_out_id)->get()->first();
            $amount = $transaction->sum;
            $type = $transaction->type;
            $requestuser = \VanguardLTE\User::where('id', $transaction->user_id)->get()->first();
            if (\Session::get('isCashier'))
            {
                $user = \VanguardLTE\User::where('id', $transaction->payeer_id)->first();
            }
            else
            {
                $user = auth()->user();
            }
            if (!$user)
            {
                return redirect()->route('backend.in_out_manage',$type)->withErrors(['본사를 찾을수 없습니다.']);
            }
            if ($transaction->status!=\VanguardLTE\WithdrawDeposit::REQUEST && $transaction->status!=\VanguardLTE\WithdrawDeposit::WAIT )
            {
                return redirect()->back()->withErrors(['이미 처리된 신청내역입니다.']);
            }
            if ($requestuser->hasRole('manager')) // for shops
            {
                $shop = \VanguardLTE\Shop::where('id', $transaction->shop_id)->get()->first();
                if($type == 'add'){
                    if($user->balance < $amount) {
                        return redirect()->route('backend.in_out_manage',$type)->withErrors(['보유금액이 충분하지 않습니다.']);
                    }

                    $user->update(
                        ['balance' => $user->balance - $amount]
                    );
                    $old = $shop->balance;
                    $shop->update([
                        'balance' => $shop->balance + $amount
                    ]);

                    $open_shift = \VanguardLTE\OpenShift::where([
                        'user_id' => $user->id, 
                        'end_date' => null,
                        'type' => 'partner'
                    ])->first();
                    if( $open_shift ) 
                    {
                        $open_shift->increment('money_in', $amount);
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
                    $user->update(
                        ['balance' => $user->balance + $amount]
                    );
                    $open_shift = \VanguardLTE\OpenShift::where([
                        'user_id' => $user->id, 
                        'end_date' => null,
                        'type' => 'partner'
                    ])->first();
                    if( $open_shift ) 
                    {
                        $open_shift->increment('money_out', $amount);
                    }
                    $old = $shop->balance + $amount;

                }
                else if($type == 'deal_out'){
                    //out balance from master
                    $user->update(
                        ['balance' => $user->balance - $amount]
                    ); 
                    $open_shift = \VanguardLTE\OpenShift::where([
                        'user_id' => $user->id, 
                        'end_date' => null,
                        'type' => 'partner'
                    ])->first();
                    if( $open_shift ) 
                    {
                        $open_shift->increment('convert_deal', $amount);
                    }
                    $old = $shop->balance;
                }

                $transaction->update([
                    'status' => 1
                ]);
                $shop = $shop->fresh();
                $user = $user->fresh();

                \VanguardLTE\ShopStat::create([
                    'user_id' => $user->id,
                    'type' => $type,
                    'sum' => $amount,
                    'old' => $old,
                    'new' => $shop->balance,
                    'balance' => $user->balance,
                    'request_id' => $transaction->id,
                    'shop_id' => $transaction->shop_id,
                    'date_time' => \Carbon\Carbon::now()
                ]);
            }
            else // for partners
            {
                if($type == 'add'){
                    $result = $requestuser->addBalance('add', $amount, $user, 0, $transaction->id);
                    $result = json_decode($result, true);
                    if ($result['status'] == 'error')
                    {
                        return redirect()->route('backend.in_out_manage', $type)->withErrors($result['message']);
                    }
                }
                else if($type == 'out'){
                    $user->update(
                        ['balance' => $user->balance + $amount]
                    );
                    $open_shift = \VanguardLTE\OpenShift::where([
                        'user_id' => $user->id, 
                        'end_date' => null,
                        'type' => 'partner'
                    ])->first();
                    if( $open_shift ) 
                    {
                        $open_shift->increment('money_out', $amount);
                    }
                    $old = $requestuser->balance + $amount;
                    $user = $user->fresh();
                    \VanguardLTE\Transaction::create([
                        'user_id' => $transaction->user_id,
                        'payeer_id' => $user->id,
                        'system' => $user->username,
                        'type' => $type,
                        'summ' => $amount,
                        'old' => $old,
                        'new' => $requestuser->balance,
                        'balance' => $user->balance,
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
            return redirect()->route('backend.in_out_manage', $type)->withSuccess(['조작이 성공적으로 진행되었습니다.']);
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
                    $open_shift = \VanguardLTE\OpenShift::where([
                        'shop_id' => $shop->id, 
                        'end_date' => null,
                        'type' => 'shop'
                    ])->first();
                    if( $open_shift ) 
                    {
                        $open_shift->decrement('convert_deal', $amount);
                    }
                }

                $shop_stat->update([
                   'status' => 2
                ]);
           }
           else {
               $transaction = \VanguardLTE\WithdrawDeposit::where('id', $in_out_id)->get()->first();
               if($transaction == null){
                return redirect()->back()->withErrors(['유효하지 않은 조작입니다.']);
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
                    else if($type == 'deal_out'){
                        $shop->update([
                            'deal_balance' => $shop->deal_balance + $amount
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
           return redirect()->back()->withSuccess(['조작이 성공적으로 진행되었습니다.']);
        }

        public function sitegamelink(\Illuminate\Http\Request $request)
        {
            $provider = $request->provider;
            $gamecode = $request->gamecode;
            $token = $request->token;
            $brand = $request->brand;
            $res = call_user_func('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($provider) . 'Controller::getgamelink', $gamecode, $token);
            return response()->json($res);
        }

        public function sitegamelist(\Illuminate\Http\Request $request)
        {
            $provider = $request->provider;
            $href = $request->href;
            $brand = $request->brand;
            $games = call_user_func('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($provider) . 'Controller::getgamelist', $href);
            return response()->json($games);
        }
    }
}
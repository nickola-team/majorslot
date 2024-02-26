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
            // if( $request->lang ) 
            // {
            //     $user->update(['language' => $request->lang]);
            // }

            //check admin id per site
            $site = \VanguardLTE\WebSite::where('domain', $request->root())->get();
            $adminid = [1]; //default admin id
            if (count($site) > 0)
            {
                $adminid = $site->pluck('adminid')->toArray();
            }

            $admin = $user;
            while ($admin !=null && !$admin->isInoutPartner())
            {
                if ($admin->status == \VanguardLTE\Support\Enum\UserStatus::DELETED)
                {
                    return response()->json(['error' => true, 'msg' => '삭제된 계정입니다.']);
                }
                if ($admin->status == \VanguardLTE\Support\Enum\UserStatus::BANNED)
                {
                    return response()->json(['error' => true, 'msg' => '계정이 임시 차단되었습니다.']);
                }
                if ($admin->status == \VanguardLTE\Support\Enum\UserStatus::JOIN || $admin->status == \VanguardLTE\Support\Enum\UserStatus::UNCONFIRMED)
                {
                    return response()->json(['error' => true, 'msg' => '가입신청을 처리중입니다.']);
                }
                if ($admin->status == \VanguardLTE\Support\Enum\UserStatus::REJECTED)
                {
                    return response()->json(['error' => true, 'msg' => '가입신청이 거부되었습니다.']);
                }
                $admin = $admin->referral;
            }

            if (!$admin || !in_array($admin->id, $adminid))
            {
                return response()->json(['error' => true, 'msg' => trans('auth.failed')]);
            }
            if (!$admin->isActive())
            {
                return response()->json(['error' => true, 'msg' => '계정이 임시 차단되었습니다.']);
            }
            if (!$user->isInoutPartner())
            {
                foreach ($site as $web)
                {
                    if ($web->adminid == $admin->id && $web->status == 0)
                    {
                        return response()->json(['error' => true, 'msg' => '현재 점검중입니다']);
                    }
                }
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
            ]) && (!$user->shop || $user->shop->is_blocked || $user->shop->pending != 0) ) 
            {
                return response()->json(['error' => true, 'msg' => trans('app.your_shop_is_blocked')]);
            }
            if( $user->isBanned() ) 
            {
                return response()->json(['error' => true, 'msg' => trans('app.your_account_is_banned')]);
            }
            
            $sessions = $sessionRepository->getUserSessions($user->id);
            $expiretime = env('EXPIRE_TIME_CLOSE', 600);
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
            
            $api_token = $user->generateCode(36);
            $tryCount = 0;
            $bToken = false;
            do{
                $alreadyUser = \VanguardLTE\User::where('api_token', $api_token)->first();
                if (!$alreadyUser)
                {
                    $bToken = true;
                    break;
                }
                $api_token = $user->generateCode(36);
                $tryCount = $tryCount + 1;
            }
            while ($tryCount < 20);
            if ($bToken){
                $user->update(['api_token' => $api_token]);
                // if ($user->playing_game != null)
                // {
                //     \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::terminate($user->id);
                //     $user->update(['playing_game' => null]);
                // }
                $user = $user->fresh();
                if ($user->api_token != $api_token)
                {
                    return response()->json(['error' => true, 'msg' => '잠시후 다시 시도해주세요.']);
                }
            }
            else
            {
                return response()->json(['error' => true, 'msg' => '잠시후 다시 시도해주세요.']);
            }

            

            event(new \VanguardLTE\Events\User\LoggedIn());


            return response()->json(['error' => false, 'msg' => '성공']);
        }
        public function notices(\Illuminate\Http\Request $request)
        {
            $user = auth()->user();
            if (!$user)
            {
                return response()->json(['error' => true, 'msg' => '로그인하세요']);
            }
            $parent = $user->referral;
                
            while($parent!=null && !$parent->isInOutPartner())
            {
                $parent = $parent->referral;

            }
            $adminid = $parent->id;
            $noticelist = \VanguardLTE\Notice::where(['user_id' => $adminid, 'active' => 1])->whereIn('type' , ['user', 'all'])->get();
            return response()->json(['error' => false, 'data' => $noticelist]);
        }
        public function inoutHistory(\Illuminate\Http\Request $request)
        {
            $user = auth()->user();
            if (!$user)
            {
                return response()->json(['error' => true, 'msg' => '로그인하세요']);
            }
            $type = $request->type;
            $statistics = \VanguardLTE\WithdrawDeposit::select('sum','status','created_at')->where('user_id', $user->id)->where('type', $type)->orderBy('created_at', 'DESC')->take(10)->get();
            return response()->json(['error' => false, 'balance'=>$user->balance, 'data' => $statistics]);

        }
        public function stat_game_balance(\Illuminate\Http\Request $request)
        {
            if( !\Illuminate\Support\Facades\Auth::check() ) {
                return response()->json(['error' => true, 'msg' => trans('app.site_is_turned_off'), 'code' => '001']);
            }
            $statid = $request->id;
            $gamestat = \VanguardLTE\StatGame::where('id', $statid)->first();
            $balance = 0;
            if ($gamestat)
            {
                if ($gamestat->balance > 0)
                {
                    $balance = $gamestat->balance;
                }
                else
                {
                    $category = $gamestat->category;
                    if ($category && $gamestat->roundid > 0)
                    {
                        if ($category->provider == 'pp')
                        {
                            $balance = \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::getRoundBalance($gamestat);
                            if ($balance != null)
                            {
                                $gamestat->update(['balance' => $balance]);
                            }
                        }
                    }
                }
            }
            return response()->json(['error' => false, 'balance' => number_format($balance,0)]);
        }
        public function checkId(\Illuminate\Http\Request $request)
        {
            if ($request->id){
                $user = \VanguardLTE\User::where('username',$request->id)->get();
                if (count($user) > 0)
                {
                    return response()->json(['error' => false, 'ok' => 0]);
                }
                else{
                    return response()->json(['error' => false, 'ok' => 1]);
                }
            }
            return response()->json(['error' => true]);
        }
        public function postJoin(\Illuminate\Http\Request $request)
        {
            $comasters = \VanguardLTE\WebSite::where('domain', $request->root())->pluck('adminid')->toArray();
            // $site = \VanguardLTE\WebSite::where('domain', $request->root())->first();
            // $admin = null;
            // if ($site)
            // {
            //     $admin = \VanguardLTE\User::where([
            //         'id' => $site->adminid, 
            //     ])->first();
            // }

            $friend = $request->friend;
            $parent = \VanguardLTE\User::where([
                'username' => $friend, 
                'role_id' => 3
            ])->first();
            if (!$friend || !$parent)
            {
                return response()->json(['error' => true, 'msg' => '추천인아이디가 정확하지 않습니다.']);
            }
            $checkp = $parent->referral;
            while ($checkp && !$checkp->isInoutPartner())
            {
                $checkp = $checkp->referral;
            }

            if (!$checkp || !in_array($checkp->id, $comasters))
            {
                return response()->json(['error' => true, 'msg' => '추천인아이디가 정확하지 않습니다']);
            }

            $count = \VanguardLTE\User::where([
                'shop_id' => $parent->shop_id, 
                'role_id' => 1
            ])->count();
            $max_users = 500;

            // if( $max_users <= $count) 
            // {
            //     return response()->json(['error' => true, 'msg' => '최대 유저수를 초과했습니다.']);
            // }
            $user = \VanguardLTE\User::where([
                'username' => $request->username, 
            ])->first();
            if ($user)
            {
                return response()->json(['error' => true, 'msg' => '아이디가 이미 존재합니다.']);
            }

            $data = $request->only([
                    'username',
                    'password',
                    'bank_name',
                    'recommender',
                    'account_no'
                ]);
            $data['status'] = \VanguardLTE\Support\Enum\UserStatus::JOIN;
            $data['role_id'] = 1;
            $data['shop_id'] = $parent->shop_id;
            $data['parent_id'] = $parent->id;
            $data['phone'] = $request->tel1;
            $data['email'] = $data['username'] . '@major.com';
            if ($request->tel2 != '') {
                $data['phone'] = $data['phone'] . $request->tel2 ;
            }
            if ($request->tel3 != '') {
                $data['phone'] = $data['phone'] . $request->tel3 ;
            }

            $onlineShop = \VanguardLTE\OnlineShop::where('shop_id', $parent->shop_id)->first();

            if ($onlineShop) //it is online users
            {
                $data['deal_percent'] = $parent->shop->deal_percent;
                $data['table_deal_percent'] = $parent->shop->table_deal_percent;
            }
            
            $user = \VanguardLTE\User::create($data);
            $user->detachAllRoles();
            $user->attachRole(1);

            \VanguardLTE\ShopUser::create([
                'shop_id' => $user->shop_id, 
                'user_id' => $user->id
            ]);

            return response()->json(['error' => false, 'msg' => '가입신청이 접수되었습니다.']);
        }
        public function getbalance(\Illuminate\Http\Request $request)
        {
            if( !\Illuminate\Support\Facades\Auth::check() ) {
                return response()->json(['error' => true, 'msg' => trans('app.site_is_turned_off'), 'code' => '001']);
            }

            $balance = number_format(auth()->user()->balance);
            $deal_balance = number_format(auth()->user()->deal_balance);
            $unreadmsg = \VanguardLTE\Message::where('user_id', auth()->user()->id)->whereNull('read_at')->count();

            return response()->json(['error' => false, 'balance' => $balance, 'deal' => $deal_balance, 'msgCount' => $unreadmsg]);
        }
        public function getgamelink(\Illuminate\Http\Request $request)
        {
            if( !\Illuminate\Support\Facades\Auth::check() ) {
                return response()->json(['error' => true, 'msg' => trans('app.site_is_turned_off'), 'code' => '001']);
            }
            $user = auth()->user();
            $provider = $request->provider;
            $gamecode = $request->gamecode;

            //withdraw all balance from old game provider.
            $b = $user->withdrawAll('getgamelink');

            if (!$b)
            {
                return response()->json(['error' => true, 'msg' => '잠시후 다시 시도하세요', 'code' => '002']);
            }
            $api_token = $user->generateCode(36);
            $user->update([
                'remember_token' => $api_token,
                'api_token' => $api_token
            ]);
            // $user->update([
            //     'remember_token' => $user->api_token
            // ]);
            if ($provider == 'null')
            {
                $fakeparams = [
                    'jackpotid' => 0,
                    'exitGame' => 1,
                    'extra' => 0,
                    'mjckey' => uniqid('AUTH@') . uniqid('~style@'),
                    'game' => $gamecode, //this is real param
                    'lobbyUrl' => 'js://window.close();',
                ];
                return response()->json(['error'=>false,'data' => ['url' => route('frontend.game.startgame',$fakeparams)]]);
            }
            if ($provider == '')
            {
                return response()->json(['error' => true, 'msg' => '게임사를 찾을수 없습니다', 'code' => '002']);
            }
            if (!class_exists('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($provider) . 'Controller')) 
            {
                return response()->json(['error' => true, 'msg' => '게임사를 찾을수 없습니다', 'code' => '002']);
            }
            //check if this game is available for this user
            if (method_exists('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($provider) . 'Controller','getGameObj'))
            {
                $gameobj = call_user_func('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($provider) . 'Controller::getGameObj', $gamecode);
                if ($gameobj && isset($gameobj['href']))
                {
                    $category = \VanguardLTE\Category::where(['provider' => $provider, 'href' => $gameobj['href'], 'shop_id' => $user->shop_id])->first();
                    if ($category->view == 0 )
                    {
                        return response()->json(['error' => true, 'msg' => '게임사를 찾을수 없습니다', 'code' => '002']);
                    }
                    if ($category->status == 0 )
                    {
                        return response()->json(['error' => true, 'msg' => '점검중입니다', 'code' => '003']);
                    }
                }
            }
            
            $res = call_user_func('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($provider) . 'Controller::getgamelink', $gamecode);
            
           
            return response()->json($res);
        }

        public function gamelistbyProvider($provider, $href)
        {
            $games = null;
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

            // if( $shop ) 
            // {
            //     switch( $shop->orderby ) 
            //     {
            //         case 'AZ':
            //             $games = $games->orderBy('name', 'ASC');
            //             break;
            //         case 'Rand':
            //             $games = $games->inRandomOrder();
            //             break;
            //         case 'RTP':
            //             $games = $games->orderBy(\DB::raw('CASE WHEN(stat_in > 0) THEN(stat_out*100)/stat_in ELSE 0 END '), 'DESC');
            //             break;
            //         case 'Count':
            //             $games = $games->orderBy('bids', 'DESC');
            //             break;
            //         case 'Date':
            //             $games = $games->orderBy('created_at', 'DESC');
            //             break;
            //     }
            // }
            // $games = $games->get();
            $games = $games->orderBy('original_id', 'DESC');
            $org_ids = $games->pluck('original_id')->toArray();
            $game_ids = $games->pluck('id')->toArray();
            $startDate = date('Y-m-d', strtotime('-30 days'));
            $query = 'SELECT A.*, B.bet from w_games A  left JOIN (SELECT SUM(totalbet) AS bet,SUM(totalwin) AS win, game_id AS game_id FROM w_game_summary WHERE date>="'.$startDate.'" and game_id in ('.implode(',',$org_ids).') GROUP BY game_id) B ON A.original_id=B.game_id WHERE A.id in ('.implode(',',$game_ids).')  ORDER BY B.bet desc limit 20;';
            $gamerst = \DB::select($query);
            $data = [];
            $hotgameids = [];
            foreach ($gamerst as $game)
            {
                $data[] = [
                    'name' => $game->name,
                    'title' => \Illuminate\Support\Facades\Lang::has('gamename.'.$game->title)? __('gamename.'.$game->title):$game->title,
                    'enname' => $game->title,
                    'label' => $game->label,
                    'date_time' => $game->created_at
                ];
                $hotgameids[] = $game->id;
            }
            $games = $games->get();
            foreach ($games as $game)
            {
                if (!in_array($game->id, $hotgameids))
                {
                    $data[] = [
                        'name' => $game->name,
                        'title' => \Illuminate\Support\Facades\Lang::has('gamename.'.$game->title)? __('gamename.'.$game->title):$game->title,
                        'enname' => $game->title,
                        'label' => $game->label,
                        'date_time' => $game->created_at
                    ];
                }
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
            $transactions1 = \VanguardLTE\WithdrawDeposit::where([
                'type' => 'add',
                'status' => \VanguardLTE\WithdrawDeposit::REQUEST,
                'payeer_id' => auth()->user()->id]);
            $transactions2 = \VanguardLTE\WithdrawDeposit::where([
                'type' => 'out',
                'status' => \VanguardLTE\WithdrawDeposit::REQUEST,
                'payeer_id' => auth()->user()->id]);
            $unreadmsgs = \VanguardLTE\Message::where('user_id', auth()->user()->id)->whereNull('read_at')->get();
            $res['join'] = 0;
            $res['msg'] = count($unreadmsgs);
            if (auth()->user()->isInOutPartner()) {
                $availableUsers = auth()->user()->availableUsers();
                $joinUsers = \VanguardLTE\User::where('status', \VanguardLTE\Support\Enum\UserStatus::JOIN)->whereIn('id', $availableUsers);
                $res['join'] = $joinUsers->count();
            }
            $res['add'] = $transactions1->count();
            $res['out'] = $transactions2->count();
            
            $res['rating'] = auth()->user()->rating;
            return response()->json($res);
        }

        public function bo_getgamelist(\Illuminate\Http\Request $request){

            $category = $request->href;
            if( $category == '' ) 
            {
                return response()->json(['error' => true, 'msg' => '카테고리ID 에러', 'code' => '002']);
            }

            $cat1 = \VanguardLTE\Category::where([
                'href' => $category, 
                'shop_id' => 0
            ])->first();
            if( !$cat1) 
            {
                return response()->json(['error' => true, 'msg' => '존재하지 않는 카테고리입니다.', 'code' => '002']);
            }

            $selectedGames = [];
            if ($cat1->provider != null)
            {
                $selectedGames = $this->gamelistbyProvider($cat1->provider, $cat1->href);
            }

            return response()->json(['error' => false, 'games' => $selectedGames]);
        }

        public function bo_getgamedetail(\Illuminate\Http\Request $request)
        {
            $statid = $request->statid;
            $statgame = \VanguardLTE\StatGame::where('id', $statid)->first();
            if (!$statgame)
            {
                return response()->json(['error' => true, 'res' => null]);
            }
            $ct = $statgame->category;
            $res = null;
            
            if ($ct->provider != null)
            {
                if (method_exists('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($ct->provider) . 'Controller','getgamedetail'))
                {
                    $res = call_user_func('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($ct->provider) . 'Controller::getgamedetail', $statgame);
                    if($ct->provider == 'sc4'){
                        return response()->json(['error' => false, 'res' => [
                            'href_sc4' => $res
                        ]]);
                    }
                }
                else
                {
                    
                }
            }
            else //local game
            {
                $game = $statgame->game_item;
                $object = '\VanguardLTE\Games\\' . $game->name . '\Server';
                $isMini = false;
                if ($game)
                {
                    //check game category
                    $gameCats = $game->categories;
                    foreach ($gameCats as $gcat)
                    {
                        if ($gcat->category->href == 'pragmatic') //pragmatic game history
                        {
                            if ($statgame->user)
                            {
                                return response()->json(['error' => false, 'res' => [
                                    'href' => '/gs2c/lastGameHistory.do?symbol='.$game->label.'&token='.$statgame->user->id.'-'.$statgame->id
                                ]]);
                            }
                        }
                        else if ($gcat->category->href == 'bngplay') // booongo game history
                        {
                            if ($statgame->user)
                            {
                                return response()->json(['error' => false, 'res' => [
                                    'href' => "/op/major/history.html?session_id=68939e9a5d134e78bfd9993d4a2cc34e#player_id=".$statgame->user->id."&brand=*&show=transactions&game_id=".$statgame->game_id."&tz=0&start_date=&end_date=&per_page=100&round_id=".$statgame->roundid."&currency=KRW&mode=REAL&report_type=GGR&header=0&totals=1&info=0&exceeds=0&lang=ko"
                                ]]);
                            }
                        }else if ($gcat->category->href == 'minigame')
                        {
                            $object = '\VanguardLTE\Http\Controllers\Web\GameParsers\PowerBall\\' . $game->name;
                            $isMini = true;
                            break;        
                        }
                    }


                    
                    if (!class_exists($object))
                    {
                        return response()->json(['error' => true, 'res' => null]);
                    }
                    if ($isMini)
                    {
                        $gameObject = new $object($game->id);
                    }else
                    {
                        $gameObject = new $object();
                    }
                    if (method_exists($gameObject, 'gameDetail'))
                    {
                        $res = $gameObject->gameDetail($statgame);
                    }
                    else
                    {
                    }
                }
                else
                {
                    return response()->json(['error' => true, 'res' => null]);
                }
            }
            return response()->json(['error' => false, 'res' => $res]);
        }

        public function getgamelist(\Illuminate\Http\Request $request){
            if( !\Illuminate\Support\Facades\Auth::check() ) {
                return response()->json(['error' => true, 'msg' => trans('app.site_is_turned_off'), 'code' => '001']);
            }else if( \Illuminate\Support\Facades\Auth::check() && !\Illuminate\Support\Facades\Auth::user()->hasRole('user') ){
                return response()->json(['error' => true, 'msg' => trans('app.site_is_turned_off'), 'code' => '001']);
            }

            $shop_id = (\Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::user()->shop_id : 0);
            $category = $request->category;
            $showAll = 0;
            // if ($request->showAll != '')
            // {
            //     $showAll = $request->showAll;
            // }
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
            if( $cat1->status == 0) 
            {
                return response()->json(['error' => true, 'msg' => '점검중입니다', 'code' => '002']);
            }

            if ($cat1->view == 0)
            {
                $selectedGames = [];
            }
            else
            {
                $categories = [$cat1->id];
                if ($cat1->provider != null)
                {
                    $selectedGames = $this->gamelistbyProvider($cat1->provider, $cat1->href);
                    $childcat = \VanguardLTE\Category::where([
                        'parent' => $cat1->id, 
                        'shop_id' => $shop_id
                    ])->first();
                    if ($childcat)
                    {
                        $childgames = $this->gamelist([$childcat->id],false);
                        $sortedgames = [];
                        foreach ($childgames as $cg)
                        {
                            foreach ($selectedGames as $idx=>$sg)
                            {
                                if (isset($sg['symbol']) && ($sg['symbol']==$cg['label']))
                                {
                                    $sortedgames[] = $sg;
                                    unset($selectedGames[$idx]);
                                    break;
                                }
                            }
                        }
                        //now add remained games
                        $selectedGames = array_merge($sortedgames, $selectedGames);
                    }
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
            }

            //exclude if view=0
            if ($showAll != "1")
            {
                $filtergames = [];
                foreach ($selectedGames as $game)
                {
                    if (!isset($game['view']) || $game['view'] == 1)
                    {
                        $filtergames[] = $game;
                    }
                }
                $selectedGames = $filtergames;
            }
            

            return response()->json(['error' => false, 'games' => $selectedGames, 'others' => []]);
        }

        

        public function getgamelist_vi(\Illuminate\Http\Request $request){
            if( !\Illuminate\Support\Facades\Auth::check() ) {
                return response()->json(['error' => true, 'msg' => trans('app.site_is_turned_off'), 'code' => '001']);
            }
            $shop_id = (\Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::user()->shop_id : 0);
            $categories = ['pragmatic'];
            $selectedGames = [];
            foreach ($categories as $category){

                $cat1 = \VanguardLTE\Category::where([
                    'href' => $category, 
                    'shop_id' => $shop_id
                ])->first();
                if( !$cat1) 
                {
                    continue;
                }

                if ($cat1->view == 1)
                {
                    $categories = [$cat1->id];
                    $selectedGames = array_merge($selectedGames, $this->gamelist($categories, false));
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
            \DB::beginTransaction();
            if ($request->user_id)
            {
                $users = auth()->user()->availableUsers();
                if( count($users) && !in_array($request->user_id, $users) ) 
                {
                    \DB::commit();
                    return response()->json([
                        'error' => true, 
                        'msg' => '비정상적인 접근입니다.',
                        'code' => '005'
                    ], 200);
                }
                $user = \VanguardLTE\User::lockForUpdate()->where('id',$request->user_id)->first();
            }
            else
            {
                $user = \VanguardLTE\User::lockForUpdate()->where('id',\Auth::id())->first();
            }
            if (!$user)
            {
                \DB::commit();
                return response()->json([
                    'error' => true, 
                    'msg' => '다시 시도해주세요.',
                    'code' => '001'
                ], 200);
            }
            if ($user->hasRole('user'))
            {
                $b = $user->withdrawAll('convertDealBalance');
                if (!$b)
                {
                    \DB::commit();
                    return response()->json([
                        'error' => true, 
                        'msg' => '게임사 머니 회수중 오류가 발생했습니다.',
                        'code' => '002'
                    ], 200);
                }
            }


            // if ($user->hasRole('user') && $user->playing_game != null)
            // {
            //     \DB::commit();
            //     return response()->json([
            //         'error' => true, 
            //         'msg' => '게임중에 딜비전환을 할수 없습니다.',
            //         'code' => '002'
            //     ], 200);
            // }

            $summ = str_replace(',','',$request->summ);
            $shop = \VanguardLTE\Shop::lockForUpdate()->where('id',$user->shop_id)->first();           
            if($user->hasRole('manager')){
                if (!$shop)
                {
                    \DB::commit();
                    return response()->json([
                        'error' => true, 
                        'msg' => '다시 시도해주세요.',
                        'code' => '001'
                    ], 200);
                }
                $real_deal_balance = $shop->deal_balance - $shop->mileage;
            }
            else {
                $real_deal_balance = $user->deal_balance - $user->mileage;
            }

            if ($summ )
            {
                $summ = abs($summ);
                if ($real_deal_balance < $summ)
                {
                    \DB::commit();
                    return response()->json([
                        'error' => true, 
                        'msg' => '딜비수익이 부족합니다.',
                        'code' => '000'
                    ], 200);
                }
            }
            else
            {
                $summ = $real_deal_balance;
            }

            // \VanguardLTE\Jobs\OutDeal::dispatch(['user_id' => $user->id, 'sum' => abs($summ)])->onQueue('deal');

            if ($summ > 0) {
                //out balance from master
                $master = $user->referral;
                while ($master!=null && !$master->isInoutPartner())
                {
                    $master = $master->referral;
                }
                if ($master == null)
                {
                    \DB::commit();
                    return response()->json([
                        'error' => true, 
                        'msg' => '상위파트너를 찾을수 없습니다.',
                        'code' => '000'
                    ], 200);
                }
                
                if ($master->balance < $summ)
                {
                    \DB::commit();
                    return response()->json([
                        'error' => true, 
                        'msg' => '상위파트너보유금이 부족합니다',
                        'code' => '000'
                    ], 200);
                }
                $master->update(
                    ['balance' => $master->balance - $summ ]
                );
                $master = $master->fresh();

                if ($user->hasRole('manager'))
                {
                    // $shop = \VanguardLTE\Shop::lockForUpdate()->where('id',$user->shop_id)->first();
                    // $real_deal_balance = $shop->deal_balance - $shop->mileage;
                    // if ($real_deal_balance < $summ)
                    // {
                    //     return -4;
                    // }
                    $old = $shop->balance;
                    $shop->balance = $shop->balance + $summ;
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
                    //create another transaction for mananger account
                    \VanguardLTE\Transaction::create([
                        'user_id' => $user->id, 
                        'payeer_id' => $master->id, 
                        'type' => 'deal_out', 
                        'summ' => abs($summ), 
                        'old' => $old,
                        'new' => $shop->balance,
                        'balance' => $master->balance,
                        'shop_id' => $shop->id,
                    ]);
                }
                else
                {
                    // $real_deal_balance = $user->deal_balance - $user->mileage;
                    // if ($real_deal_balance < $summ)
                    // {
                    //     return -4;
                    // }
                    $old = $user->balance;
                    $user->balance = $user->balance + $summ;
                    $user->deal_balance = $real_deal_balance - $summ;
                    $user->mileage = 0;
                    $user->save();
                    $user = $user->fresh();
        
                    \VanguardLTE\Transaction::create([
                        'user_id' => $user->id,
                        'payeer_id' => $master->id,
                        'system' => $user->username,
                        'type' => 'deal_out',
                        'summ' => $summ,
                        'old' => $old,
                        'new' => $user->balance,
                        'balance' => $master->balance,
                        'shop_id' => $user->shop_id
                    ]);
                }
            }

            \DB::commit();
            
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
                    'msg' => '환전금액은 딜비수익을 초과할수 없습니다',
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
        public function olddeposit(\Illuminate\Http\Request $request){
            return response()->json([
                'error' => true, 
                'msg' => '개인충전은 지원하지 않습니다.',
                'code' => '004'
            ], 200);
        }
        public function oldwithdraw(\Illuminate\Http\Request $request){
            return response()->json([
                'error' => true, 
                'msg' => '개인환전은 지원하지 않습니다.',
                'code' => '004'
            ], 200);
        }

        public function depositAccount(\Illuminate\Http\Request $request){
            $amount = $request->money;
            $account = $request->account;
            $force = $request->force;
            $user = auth()->user();
            if (!$user)
            {
                return response()->json([
                    'error' => true, 
                    'msg' => '로그인이 필요합니다.',
                    'code' => '001'
                ], 200);
            }
            

            // if ($amount > 5000000)
            // {
            //     return response()->json([
            //         'error' => true, 
            //         'msg' => '충전금액은 최대 500만원까지 가능합니다.',
            //         'code' => '002'
            //     ], 200);
            // }

            // if ($account == '')
            // {
            //     return response()->json([
            //         'error' => true, 
            //         'msg' => '입금자명을 입력하세요',
            //         'code' => '003'
            //     ], 200);
            // }
            $master = $user->referral;
            while ($master!=null && !$master->isInoutPartner())
            {
                $master = $master->referral;
            }
            if (!$master)
            {
                return response()->json([
                    'error' => true, 
                    'msg' => '본사를 찾을수 없습니다.',
                    'code' => '001'
                ], 200);
            }
            if ($master->bank_name == 'PAYWIN') // 페이윈 가상계좌
            {
                if ($amount == 0)
                {
                    return response()->json([
                        'error' => true, 
                        'msg' => '충전금액을 입력하세요',
                        'code' => '002'
                    ], 200);
                }
                //상품조회
                try {
                    $data = [
                        'sellerId' => "mir",
                    ];
                    $response = Http::withBody(json_encode($data), 'application/json')->post('http://api.paywin.co.kr/api/markerSellerInfo');
                    if ($response->ok())
                    {
                        $data = $response->json();
                        $product = null;
                        if ($data['resultCode'] == '0000')
                        {
                            foreach ($data['productList'] as $p)
                            {
                                if ($p['p_price'] == $amount)
                                {
                                    $product = $p;
                                    break;
                                }
                            }
                            if ($product != null)
                            {
                                //계좌발급 요청
                                $data = [
                                    'm_id' => $user->id,
                                    'm_name' => $account,
                                    'p_seq' => $product['p_seq'],
                                    'mid' => $master->account_no
                                ];
                                $response = Http::withBody(json_encode($data), 'application/json')->post('http://api.paywin.co.kr/api/markerBuyProduct');
                                if ($response->ok())
                                {
                                    $data = $response->json();
                                    if ($data['resultCode'] == '0000')
                                    {
                                        return response()->json([
                                            'error' => false, 
                                            'msg' => $data['orderVO']['o_sender_bank_name'] . ' [ ' .$data['orderVO']['o_virtual_account']. ' ]',
                                        ], 200);
                                    }
                                    else //가상계좌 발급 실패
                                    {
                                        return response()->json([
                                            'error' => true, 
                                            'msg' => '계좌요청이 실패하였습니다. 다시 시도해주세요',
                                            'code' => 001
                                        ], 200);
                                    }
                                }
                                else //가상계좌 발급 실패
                                {
                                    return response()->json([
                                        'error' => true, 
                                        'msg' => '계좌요청이 실패하였습니다. 다시 시도해주세요',
                                        'code' => 001
                                    ], 200);
                                }

                            }
                            else //매칭되는 금액 상품 없음
                            {
                                return response()->json([
                                    'error' => true, 
                                    'msg' => '입금금액은 1만원단위로 최대 199만원까지 가능합니다.',
                                    'code' => 002
                                ], 200);
                            }
                        }
                        else //상품조회 실패
                        {
                            return response()->json([
                                'error' => true, 
                                'msg' => '계좌요청이 실패하였습니다. 다시 시도해주세요',
                                'code' => 001
                            ], 200);
                        }
                    }
                    else //상품조회 실패
                    {
                        return response()->json([
                            'error' => true, 
                            'msg' => '계좌요청이 실패하였습니다. 다시 시도해주세요',
                            'code' => 001
                        ], 200);
                    }
                }
                catch (Exception $ex)
                {
                    return response()->json([
                        'error' => true, 
                        'msg' => '계좌요청이 실패하였습니다. 다시 시도해주세요',
                        'code' => 001
                    ], 200);
                }
            }
            else if ($master->bank_name == 'JUNCOIN') //준코인 가상계좌
            {
                if ($amount == 0)
                {
                    return response()->json([
                        'error' => true, 
                        'msg' => '충전금액을 입력하세요',
                        'code' => '002'
                    ], 200);
                }

                if ($amount % 10000 != 0)
                {
                    return response()->json([
                        'error' => true, 
                        'msg' => '1만원 단위로 입금하세요',
                        'code' => '001'
                    ], 200);
                }
                $url = 'https://jun-200.com/sign-up-process-api?uid='.$master->account_no. '@'.$user->id.'&site='.$master->recommender.'&p='.($amount/10000).'&rec_name=' . $account;
                return response()->json([
                    'error' => false, 
                    'msg' => '팝업창에서 입금계좌신청을 하세요',
                    'url' => $url
                ], 200);
            }
            else if ($master->bank_name == 'WORLDPAY') //월드페이 가상계좌
            {
                $paramete=$master->account_no.'||'.$user->username.'||'.$user->recommender.'||'.$user->bank_name.'||'.$user->account_no;
                
                $url = 'http://1one-pay.com/api_mega/?token='.rawurlencode($paramete);
                return response()->json([
                    'error' => false, 
                    'msg' => '팝업창에서 입금계좌신청을 하세요',
                    'url' => $url
                ], 200);
            }
            else if ($master->bank_name == 'OSSCOIN') //OSS코인 가상계좌
            {
                $url = 'https://testapi.osscoin.net';
                $publicKey = $master->account_no; //WGJzUHM1UEZRMmRCb3JuRjJxLzM1UT09
                $agent = $master->recommender; // tposeidon
                $ossbanks = ['국민은행' => 0,'우리은행' => 1,'신한은행' => 2,'하나은행' => 3,'SC 제일은행' => 4,'씨티은행' => 5,'기업은행' => 6,'농협중앙회' => 7,'단위농협' => 8,'새마을금고' => 9,'케이뱅크' => 10,'카카오뱅크' => 11,'수협' => 12,'신협' => 13,'산림조합' => 14,'상호저축은행' => 15,'부산은행' => 16,'경남은행' => 17,'광주은행' => 18,'대구은행' => 19,'전북은행' => 20,'제주은행' => 21,'우체국' => 22,'한국투자증권' => 23,'토스뱅크' => 24,'뱅크오브아메리카' => 25,'bnp 파리바' => 26,'jp 모간' => 27,'동양증권' => 28,'현대증권' => 29,'미래에셋증권' => 30,'대우증권' => 31,'삼성증권' => 32,'우리투자증권' => 33,'교보증권' => 34,'하이투자증권' => 35,'HMC 투자증권' => 36,'키움증권' => 37,'이트레이드증권' => 38,'대신증권' => 39,'아이엠투자증권' => 40,'한화투자증권' => 41,'하나대투증권' => 42,'신한금융투자' => 43,'동부증권' => 44,'유진투자증권' => 45,'메리츠증권' => 46,'부국증권' => 47,'신영증권' => 48,'LIG 투자증권' => 49,'유안타증권' => 50,'이베스트투자증권' => 51,'NH 농협투자증권' => 52,'KB 증권' => 53,'SK 증권' => 54,'SBI 저축은행' => 55,'카카오페이증권' => 56,'IBK 투자증권' => 57,'산업은행' => 58,'모건스탠리' => 59,'중국공상은행' => 60,'케이티비투자증권' => 61,'BNK 투자증권' => 63,'BOA 은행' => 64,'HSBC 은행' => 65,'도이치은행' => 66,'미쓰비시도쿄 UFJ 은행' => 67,'미즈호은행' => 68,'피엔피파리팡느행' => 69,'중국은행' => 70,'한국포스증권' => 71,'현대차증권' => 72];
                // 회원조회
                $data = [
                    'agent' => $agent,
                    'userAccount' => $user->username
                ];
                $response = Http::withHeaders([
                    'PublicKey' => $publicKey
                    ])->post(config('app.osscoin_url') . '/api_user', $data);
                if (!$response->ok())
                {
                    return response()->json([
                        'error' => true, 
                        'msg' => '계좌요청이 실패하였습니다. 다시 시도해주세요',
                        'code' => 001
                    ], 200);
                }
                $data = $response->json();
                if ($data['code'] != 0)
                {
                    $bank_id = 99;
                    foreach($ossbanks as $bankname => $id){
                        if($bankname == $user->bank_name){
                            $bank_id = $id;
                        }
                    }
                    // 회원가입
                    $data = [
                        'agent' => $agent,
                        'userAccount' => $user->username,
                        'userName' => $user->username,
                        'userBank' => $bank_id,
                        'userBankaccount' => $user->account_no
                    ];
                    $response = Http::withHeaders([
                        'PublicKey' => $publicKey
                        ])->post(config('app.osscoin_url') . '/api_join', $data);
                    if (!$response->ok())
                    {
                        return response()->json([
                            'error' => true, 
                            'msg' => '계좌요청이 실패하였습니다. 다시 시도해주세요',
                            'code' => 001
                        ], 200);
                    }
                    $data = $response->json();
                }
                if(($data['code'] == 0 || $data['code'] == 5) && isset($data['token'])){
                    $url = config('app.osscoin_user_url') . '/?token='.$data['token'];
                    return response()->json([
                        'error' => false, 
                        'msg' => '팝업창에서 입금계좌신청을 하세요',
                        'url' => $url
                    ], 200);
                }else{
                    return response()->json([
                        'error' => true, 
                        'msg' => '계좌요청이 실패하였습니다. 다시 시도해주세요',
                        'code' => 001
                    ], 200);
                }
            }
            else if ($master->bank_name == '경남가상계좌') //경남 가상계좌
            {
                // $url = 'https://jun-200.com/sign-up-process-api?uid='.$master->account_no. '@'.$user->id.'&site='.$master->recommender.'&p='.($amount/10000).'&rec_name=' . $account;
                $url = 'https://bnka.jtrader.net';
                return response()->json([
                    'error' => false, 
                    'msg' => '팝업창에서 입금계좌신청을 하세요',
                    'url' => $url
                ], 200);
            }
            else if ($master->bank_name == 'VirtualAcc') //버철가상계좌
            {
                // $url = 'https://jun-200.com/sign-up-process-api?uid='.$master->account_no. '@'.$user->id.'&site='.$master->recommender.'&p='.($amount/10000).'&rec_name=' . $account;
                $url = 'https://virtualacc.co.kr/mobileAuth';
                return response()->json([
                    'error' => false, 
                    'msg' => '팝업창에서 입금계좌신청을 하세요',
                    'url' => $url
                ], 200);
            }
            else if ($master->bank_name == '효원라이프') //효원라이프
            {
                // $url = 'https://jun-200.com/sign-up-process-api?uid='.$master->account_no. '@'.$user->id.'&site='.$master->recommender.'&p='.($amount/10000).'&rec_name=' . $account;
                $url = 'http://shop.hwlife.kr/direct/payInfo.do?key=cml2ZXI=';
                return response()->json([
                    'error' => false, 
                    'msg' => '팝업창에서 입금계좌신청을 하세요',
                    'url' => $url
                ], 200);
            }
            else if ($master->bank_name == 'MESSAGE') //계좌를 쪽지방식으로 알려주는 총본사
            {
                $date_time = date('Y-m-d H:i:s', strtotime("-2 minutes"));
                $lastMsgs = \VanguardLTE\Message::where([
                    'writer_id' => $user->id,
                ])->where('created_at', '>', $date_time)->get();
                if (count($lastMsgs) > 0)
                {
                    return response()->json([
                        'error' => true, 
                        'msg' => '이미 쪽지를 발송하였습니다. 잠시후 다시 시도하세요',
                    ], 200);
                }
                $data = [
                    'user_id' => $master->id,
                    'writer_id' => $user->id,
                    'type' => 1,
                    'title' => '계좌문의드립니다',
                    'content' => '입금계좌 문의드립니다'
                ];
                \VanguardLTE\Message::create($data);
                return response()->json([
                    'error' => false, 
                    'msg' => '계좌문의 쪽지가 발송되었습니다',
                ], 200);
            }
            else
            {
                $telegramId = $master->address;
                if ($force==0 && $user->hasRole('user'))
                {
                    return response()->json([
                        'error' => false, 
                        'msg' => '텔레그램 문의, 아이디 ' . $telegramId,
                    ], 200);
                }
                else
                {
                    return response()->json([
                        'error' => false, 
                        'msg' => $master->bank_name . ' [ ' .$master->account_no. ' ] , ' . $master->recommender . (empty($telegramId)?'':(', 텔레그램아이디 ' . $telegramId)),
                    ], 200);
                }
            }
        }

        public function deposit(\Illuminate\Http\Request $request){
            if( !\Illuminate\Support\Facades\Auth::check() ) {
                return response()->json(['error' => true, 'msg' => trans('app.site_is_turned_off'), 'code' => '001']);
            }

            $user = auth()->user();

            if($user->role_id > 1 && ($user->bank_name == null || $user->bank_name == '')){
                return response()->json([
                    'error' => true, 
                    'msg' => '계좌정보를 입력해주세요',
                    'code' => '004'
                ], 200);
            }
            if ($user->hasRole('user') && ($user->recommender == '' || $user->bank_name == ''  || $user->account_no == '') && ($request->accountName == '' || $request->bank == ''  || $request->no == ''))
            {
                return response()->json([
                    'error' => true, 
                    'msg' => '입금정보를 입력해주세요.',
                ], 200);
            }
            $oldrequest = \VanguardLTE\WithdrawDeposit::where(['user_id' => $user->id])->whereIn('status', [\VanguardLTE\WithdrawDeposit::REQUEST,\VanguardLTE\WithdrawDeposit::WAIT])->get();
            if (count($oldrequest) > 0)
            {
                return response()->json([
                    'error' => true, 
                    'msg' => '이미 신청중이니 기다려주세요',
                    'code' => '001'
                ], 200);
            }
            if( !$request->money ) 
            {
                return response()->json([
                    'error' => true, 
                    'msg' => '충전금액을 입력해주세요'
                ], 200);
            }
            if ($user->hasRole('user')  && ($request->accountName != '' || $request->bank != ''  || $request->no != ''))
            {
                $user->update([
                    'recommender' => $request->accountName,
                    'bank_name' => $request->bank,
                    'account_no' => $request->no
                ]);
                $user =  $user->fresh();
            }
            $money = abs(str_replace(',','', $request->money));
            if($user->hasRole('manager')){
                //send it to master.
                $master = $user->referral;
                while ($master!=null && !$master->isInoutPartner())
                {
                    $master = $master->referral;
                }
                if ($master == null)
                {
                    return response()->json([
                        'error' => true, 
                        'msg' => '본사를 찾을수 없습니다.',
                        'code' => '002'
                    ], 200);
                }
                \VanguardLTE\WithdrawDeposit::create([
                    'user_id' => $user->id,
                    'payeer_id' => $master->id,
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
            else {
                $master = $user->referral;
                while ($master!=null && !$master->isInoutPartner())
                {
                    $master = $master->referral;
                }
                if ($master == null)
                {
                    return response()->json([
                        'error' => true, 
                        'msg' => '본사를 찾을수 없습니다.',
                        'code' => '002'
                    ], 200);
                }
                \VanguardLTE\WithdrawDeposit::create([
                    'user_id' => $user->id,
                    'payeer_id' => $master->id,
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

            if(($user->bank_name == null || $user->bank_name == '')){
                return response()->json([
                    'error' => true, 
                    'msg' => '계좌정보를 입력해주세요',
                    'code' => '004'
                ], 200);
            }
            if ($user->role_id > 1 && empty($user->confirmation_token))
            {
                return response()->json([
                    'error' => true, 
                    'msg' => '환전비밀번호를 설정하세요',
                    'code' => '010'
                ], 200);
            }
            if ($user->role_id > 1 && empty($request->confirmation_token))
            {
                return response()->json([
                    'error' => true, 
                    'msg' => '환전비밀번호를 입력하세요',
                    'code' => '011'
                ], 200);
            }
            if ($user->role_id > 1 && !\Illuminate\Support\Facades\Hash::check($request->confirmation_token, $user->confirmation_token))
            {
                return response()->json([
                    'error' => true, 
                    'msg' => '환전비밀번호가 틀립니다',
                    'code' => '012'
                ], 200);
            }

            if ($user->hasRole('user') && ($user->recommender == '' || $user->bank_name == ''  || $user->account_no == '') && ($request->accountName == '' || $request->bank == ''  || $request->no == ''))
            {
                return response()->json([
                    'error' => true, 
                    'msg' => '출금정보를 입력해주세요.',
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
            $oldrequest = \VanguardLTE\WithdrawDeposit::where(['user_id' => $user->id])->whereIn('status', [\VanguardLTE\WithdrawDeposit::REQUEST,\VanguardLTE\WithdrawDeposit::WAIT])->get();
            if (count($oldrequest) > 0)
            {
                return response()->json([
                    'error' => true, 
                    'msg' => '이미 신청중이니 기다려주세요',
                    'code' => '001'
                ], 200);
            }

            $money = abs(str_replace(',','', $request->money));

            if($user->hasRole('manager')){
                if($money > $user->shop->balance) {
                    return response()->json([
                        'error' => true, 
                        'msg' => '환전금액은 보유금액을 초과할수 없습니다',
                        'code' => '002'
                    ], 200);
                }
            }
            else {
                if($money > $user->balance) {
                    return response()->json([
                        'error' => true, 
                        'msg' => '환전금액은 보유금액을 초과할수 없습니다',
                        'code' => '002'
                    ], 200);
                }
            }

            if ($user->hasRole('user'))
            {
                $b = $user->withdrawAll('withdrawRequest');
                if (!$b)
                {
                    \DB::commit();
                    return response()->json([
                        'error' => true, 
                        'msg' => '게임사 머니 회수중 오류가 발생했습니다.',
                        'code' => '002'
                    ], 200);
                }

                // if ($user->playing_game != null)
                // {
                //     return response()->json([
                //         'error' => true, 
                //         'msg' => '게임중에 환전신청을 할수 없습니다.',
                //         'code' => '002'
                //     ], 200);
                // }
                // $user->update([
                //     'recommender' => $request->accountName,
                //     'bank_name' => $request->bank,
                //     'account_no' => $request->no
                // ]);
                // $user =  $user->fresh();
            }


            if($user->hasRole('manager')){
                //send it to master.
                $master = $user->referral;
                while ($master!=null && !$master->isInoutPartner())
                {
                    $master = $master->referral;
                }
                if ($master == null)
                {
                    return response()->json([
                        'error' => true, 
                        'msg' => '본사를 찾을수 없습니다.',
                        'code' => '002'
                    ], 200);
                }
                \VanguardLTE\WithdrawDeposit::create([
                    'user_id' => $user->id,
                    'payeer_id' => $master->id,
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

                $shop = \VanguardLTE\Shop::lockforUpdate()->where('id', $user->shop_id)->get()->first();
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
            else {
                $user->update(
                    [
                        'balance' => $user->balance - $money,
                        'total_out' => $user->total_out + $money,
                    ]
                );
                $master = $user->referral;
                while ($master!=null && !$master->isInoutPartner())
                {
                    $master = $master->referral;
                }
                if ($master == null)
                {
                    return response()->json([
                        'error' => true, 
                        'msg' => '본사를 찾을수 없습니다.',
                        'code' => '002'
                    ], 200);
                }
                \VanguardLTE\WithdrawDeposit::create([
                    'user_id' => $user->id,
                    'payeer_id' => $master->id,
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
            if (!$transaction)
            {
                return redirect()->back()->withErrors(['비정상적인 접근입니다.']);
            }
            $amount = $transaction->sum;
            $type = $transaction->type;
            $requestuser = \VanguardLTE\User::where('id', $transaction->user_id)->get()->first();
            $user = auth()->user();

            if (!$user)
            {
                return redirect()->back()->withErrors(['본사를 찾을수 없습니다.']);
            }
            if (!$user->isInoutPartner())
            {
                return redirect()->back()->withErrors(['관리권한이 없습니다.']);
            }
            if ($transaction->status!=\VanguardLTE\WithdrawDeposit::REQUEST && $transaction->status!=\VanguardLTE\WithdrawDeposit::WAIT )
            {
                return redirect()->back()->withErrors(['이미 처리된 신청내역입니다.']);
            }
            if ($requestuser->hasRole('user') )
            {
                $b = $requestuser->withdrawAll('allowInOut');
                if (!$b)
                {
                    return redirect()->back()->withErrors(['게임사 머니 회수중 오류가 발생했습니다.']);
                }
            }
            // if ($requestuser->hasRole('user') && $requestuser->playing_game != null)
            // {
            //     return redirect()->back()->withErrors(['해당 유저가 게임중이므로 충환전처리를 할수 없습니다.']);
            // }
            
            if ($requestuser->hasRole('manager')) // for shops
            {
                $shop = \VanguardLTE\Shop::lockforUpdate()->where('id', $transaction->shop_id)->get()->first();
                if($type == 'add'){
                    if($user->balance < $amount) {
                        if (auth()->user()->hasRole('comaster'))
                        {
                            return redirect()->back()->withErrors([$user->username. '본사의 보유금액이 충분하지 않습니다.']);
                        }
                        else
                        {
                            return redirect()->back()->withErrors(['보유금액이 충분하지 않습니다.']);
                        }
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

                //create another transaction for mananger account
                \VanguardLTE\Transaction::create([
                    'user_id' => $requestuser->id, 
                    'payeer_id' => $user->id, 
                    'type' => $type, 
                    'summ' => abs($amount), 
                    'old' => $old,
                    'new' => $shop->balance,
                    'balance' => $user->balance,
                    'request_id' => $transaction->id,
                    'shop_id' => $transaction->shop_id,
                ]);
            }
            else // for partners
            {
                if($type == 'add'){
                    $result = $requestuser->addBalance('add', $amount, $user, 0, $transaction->id);
                    $result = json_decode($result, true);
                    if ($result['status'] == 'error')
                    {
                        return redirect()->route(config('app.admurl').'.in_out_manage', $type)->withErrors($result['message']);
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

            return redirect()->route(config('app.admurl').'.in_out_manage', $type)->withSuccess(['조작이 성공적으로 진행되었습니다.']);
        }

        public function rejectInOut(\Illuminate\Http\Request $request){
            $in_out_id = $request->out_id;
            
            $transaction = \VanguardLTE\WithdrawDeposit::where('id', $in_out_id)->get()->first();
            if($transaction == null){
                return redirect()->back()->withErrors(['유효하지 않은 조작입니다.']);
            }
            if ($transaction->status!=\VanguardLTE\WithdrawDeposit::REQUEST && $transaction->status!=\VanguardLTE\WithdrawDeposit::WAIT )
            {
                return redirect()->back()->withErrors(['이미 처리된 신청내역입니다.']);
            }
            $user = auth()->user();
            if (!$user)
            {
                return redirect()->back()->withErrors(['본사를 찾을수 없습니다.']);
            }
            if (!$user->isInoutPartner())
            {
                return redirect()->back()->withErrors(['관리권한이 없습니다.']);
            }
            
            $amount = $transaction->sum;
            $type = $transaction->type;
            $requestuser = \VanguardLTE\User::where('id', $transaction->user_id)->get()->first();
            if ($requestuser){
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

                }
                else
                {
                    if($type == 'out'){
                        $requestuser->update([
                            'balance' => $requestuser->balance + $amount,
                            'total_out' => $requestuser->total_out - $amount,

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

                }
            }
            $transaction->update([
                'status' => 2
            ]);
           return redirect()->back()->withSuccess(['조작이 성공적으로 진행되었습니다.']);
        }
        public function msglist(\Illuminate\Http\Request $request)
        {
            if( !\Illuminate\Support\Facades\Auth::check() ) 
            {
                return response()->json(['error' => true, 'msg'=>'로그인하세요']);
            }
            $parent = auth()->user()->referral;
                
            while($parent!=null && !$parent->isInOutPartner())
            {
                $parent = $parent->referral;
            }
            $adminid = $parent->id;
            
            $personmsgs = \VanguardLTE\Message::where(function ($query) {
                $query->where('writer_id','=', auth()->user()->id)->orWhere('user_id','=', auth()->user()->id);
            });
            $grpmsgs = \VanguardLTE\Message::where(['user_id' => \VanguardLTE\Message::GROUP_MSG_ID, 'writer_id' => $adminid]);
            $msgs = $grpmsgs->union($personmsgs)->orderby('created_at', 'desc')->take(10)->get();
            
            return response()->json(['error' => false, 'user_id'=>auth()->user()->id,'data' => $msgs]);
        }
        public function writeMessage(\Illuminate\Http\Request $request)
        {
            if( !\Illuminate\Support\Facades\Auth::check() ) 
            {
                return response()->json(['error' => true, 'msg' => '로그인하세요']);
            }
            if ($request->title == '' || $request->content == '')
            {
                return response()->json(['error' => true, 'msg' => '제목과 내용을 모두 입력하세요']);
            }
            $title = $request->title;
            $content = $request->content;
            $type = 0;
            if ($request->type != null)
            {
                $type = $request->type;
            }
            $parent =auth()->user()->referral;
            while($parent!=null && !$parent->isInOutPartner())
            {
                $parent = $parent->referral;

            }
            $adminid = $parent->id;
            $msg = \VanguardLTE\Message::create(
                [
                    'user_id' => $adminid,
                    'writer_id' => auth()->user()->id,
                    'type' => $type,
                    'title' => $title,
                    'content' => $content,
                ]
                );
            return response()->json(['error' => false, 'id' => $msg->id]);
        }
        public function readMessage(\Illuminate\Http\Request $request)
        {
            if( !\Illuminate\Support\Facades\Auth::check() ) 
            {
                return response()->json(['error' => 0]);
            }
            $msg = \VanguardLTE\Message::where('id', $request->id)->first();
            if ($msg )
            {
                if ($msg->user_id == auth()->user()->id)
                {
                    $msg->update([
                            'read_at' => \Carbon\Carbon::now(), 
                            'count' => 1
                                ]);
                }
                else if ($msg->user_id == \VanguardLTE\Message::GROUP_MSG_ID)
                {
                    $msg->update([
                        'read_at' => \Carbon\Carbon::now(), 
                        'count' => $msg->count + 1
                            ]);
                }
            }
            return response()->json(['error' => 0]);
        }
        public function deleteMessage(\Illuminate\Http\Request $request)
        {
            $msg = \VanguardLTE\Message::where('id', $request->id)->first();
            if ($msg && $msg->read_at != null)
            {
                $msg->delete();
                return response()->json(['error' => 0]);
            }
            return response()->json(['error' => 1]);
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
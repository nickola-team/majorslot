<?php 
namespace VanguardLTE\Http\Controllers\Web\Frontend
{
    class GamesController extends \VanguardLTE\Http\Controllers\Controller
    {
        public function index(\Illuminate\Http\Request $request, $category1 = '', $category2 = '')
        {
            if( \Illuminate\Support\Facades\Auth::check() && !\Illuminate\Support\Facades\Auth::user()->hasRole('user') ) 
            {
                $backendtheme = ['slot', 'backend'];
                if (!in_array(config('app.admurl'), $backendtheme))
                {
                    return redirect()->to(argon_route('argon.dashboard'));
                }
                else
                {
                    return redirect()->route(config('app.admurl').'.dashboard');
                }
            }

            $title = trans('app.games');
            $shop_id = (\Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::user()->shop_id : 0);
            $frontend = 'Default';
            $excat = ['hot', 'new', 'card','bingo','roulette', 'keno', 'novomatic','wazdan', 'habaneroplay'];
            $sites = \VanguardLTE\WebSite::where('domain', $request->root())->get();
            $adminid = 1;
            if (count($sites) > 0)
            {
                $site = $sites->first();

                $frontend = $site->frontend;
                $title = $site->title;
                $adminid = $site->adminid;
                if (count($sites) > 1)
                {
                    $adminid = -1;
                }
            }
            else
            {
                if (str_contains($request->root(),env('SESSION_SECURE_DOMAIN')))
                {
                    return response()->view('system.pages.unlogged', [], 200)->header('Content-Type', 'text/html');
                }
                return response()->view('system.pages.siteisclosed', [], 200)->header('Content-Type', 'text/html');
            }

            if ($shop_id != 0)
            {
                $parent = auth()->user()->referral;
                
                while($parent!=null && !$parent->isInOutPartner())
                {
                    $parent = $parent->referral;

                }
                $adminid = $parent->id;
            }

            if ($shop_id == 0)
            {
                $categories = \VanguardLTE\Category::where([
                    'shop_id' => $shop_id,
                    'site_id' => $site->id,
                    'view' => 1,
                    'parent' => 0,
                ])->whereNotIn('href',$excat)->orderby('position', 'desc')->get();

                if (count($categories) == 0) // use default category
                {
                    $categories = \VanguardLTE\Category::where([
                        'shop_id' => $shop_id,
                        'site_id' => 0,
                        'view' => 1,
                        'parent' => 0,
                    ])->whereNotIn('href',$excat)->orderby('position', 'desc')->get();
                }
            }
            else
            {
                $categories = \VanguardLTE\Category::where(['shop_id' => $shop_id, 'view' => 1,'parent' => 0,])->whereNotIn('href',$excat)->orderby('position', 'desc')->get();
            }

            $hotgames = [];
            $pbgames = [];
            $detect = new \Detection\MobileDetect();
            $devices = [];
            if( $detect->isMobile() || $detect->isTablet() ) 
            {
                $devices = [
                    0, 
                    2
                ];
            }
            else
            {
                $devices = [
                    1, 
                    2
                ];
            }
            //add pbgames
            $pbgameLst = \VanguardLTE\Category::where(['href'=> 'minigame', 'shop_id'=>0, 'site_id'=>0])->first();
            if ($pbgameLst)
            {
                $gamecats = $pbgameLst->games()->orderby('game_id', 'asc')->get();
                foreach ($gamecats as $gc)
                {
                    if ($gc->game->view == 1 && in_array($gc->game->device, $devices))
                    {
                        $pbgames[] = ['name' => $gc->game->name, 'title' => \Illuminate\Support\Facades\Lang::has('gamename.'.$gc->game->title)? __('gamename.'.$gc->game->title):$gc->game->title];
                    }
                }
            }
            //add virtualtech games
            $virtualtech = \VanguardLTE\Category::where(['href'=> 'virtualtech', 'shop_id'=>0, 'site_id'=>0])->first();
            if ($virtualtech)
            {
                $gamecats = $virtualtech->games()->orderby('game_id', 'desc')->get();
                foreach ($gamecats as $gc)
                {
                    if ($gc->game->view == 1 && in_array($gc->game->device, $devices))
                    {
                        $hotgames[] = ['name' => $gc->game->name, 'title' => \Illuminate\Support\Facades\Lang::has('gamename.'.$gc->game->title)? __('gamename.'.$gc->game->title):$gc->game->title];
                    }
                }
            }

            //add aristocrat games
            $aristocrat = \VanguardLTE\Category::where(['href'=> 'aristocrat', 'shop_id'=>0, 'site_id'=>0])->first();
            if ($aristocrat)
            {
                $gamecats = $aristocrat->games;
                foreach ($gamecats as $gc)
                {
                    if ($gc->game->view == 1 && in_array($gc->game->device, $devices))
                    {
                        $hotgames[] = ['name' => $gc->game->name, 'title' => \Illuminate\Support\Facades\Lang::has('gamename.'.$gc->game->title)? __('gamename.'.$gc->game->title):$gc->game->title];
                    }
                }
            }

            $ppgames = [];
            $livegames = [];
            

            $superadminId = \VanguardLTE\User::where('role_id',9)->first()->id;
            $notice = \VanguardLTE\Notice::where(['user_id' => $superadminId, 'active' => 1])->whereIn('type' , ['user', 'all'])->first(); //for admin's popup
            $noticelist = \VanguardLTE\Notice::where(['user_id' => $superadminId, 'active' => 1])->whereIn('type' , ['user', 'all'])->get();
            $msgs = [];
            $unreadmsg = 0;
            if ($notice==null || $shop_id != 0) { //it is logged in
                $notice = \VanguardLTE\Notice::where(['user_id' => $adminid, 'active' => 1])->whereIn('type' , ['user', 'all'])->first(); //for comaster's popup
                $noticelist = \VanguardLTE\Notice::where(['user_id' => $adminid, 'active' => 1])->whereIn('type' , ['user', 'all'])->get(); //for comaster's popup
            }
            $trhistory = [];
            if ($shop_id != 0)
            {
                // $msgs = \VanguardLTE\Message::where('user_id', auth()->user()->id)->get(); //messages
                $personmsgs = \VanguardLTE\Message::where(function ($query) {
                    $query->where('writer_id','=', auth()->user()->id)->orWhere('user_id','=', auth()->user()->id);
                });
                $grpmsgs = \VanguardLTE\Message::where(['user_id' => \VanguardLTE\Message::GROUP_MSG_ID, 'writer_id' => $adminid]);
                $msgs = $grpmsgs->union($personmsgs)->orderby('created_at', 'desc')->take(10)->get();

                $unreadmsg = \VanguardLTE\Message::where('user_id', auth()->user()->id)->whereNull('read_at')->count();
                //transaction history

                $trhistory = \VanguardLTE\WithdrawDeposit::leftJoin('transactions', function($join){
                    $join->on('withdraw_deposit.id', '=', 'transactions.request_id');
                })->where('withdraw_deposit.user_id', auth()->user()->id)->orderby('withdraw_deposit.created_at','desc')->take(20)->get(
                    [
                        'withdraw_deposit.type',
                        'withdraw_deposit.status',
                        'withdraw_deposit.sum',
                        'withdraw_deposit.created_at',
                        'transactions.updated_at',
                    ]
                );
            }
            if (!\View::exists('frontend.' . $frontend . '.games.list')) { 
                abort(404);
             }

             //we must sync balance
             if (\Illuminate\Support\Facades\Auth::check()){
                //게임사 연동 위해 대기중이면 머니동기화 하지 않기
                $launchRequests = \VanguardLTE\GameLaunch::where('finished', 0)->where('user_id', auth()->user()->id)->get();
                if (count($launchRequests) == 0)
                {
                    $balance = \VanguardLTE\User::syncbalance(auth()->user(), 'gameindex');
                    if ($balance >= 0)
                    {
                        auth()->user()->balance = $balance;
                    }
                }
             }

            return view('frontend.' . $frontend . '.games.list', compact('categories', 'hotgames', 'livegames', 'title', 'notice', 'noticelist','msgs','unreadmsg', 'ppgames', 'trhistory','pbgames'));
        }
        public function setpage(\Illuminate\Http\Request $request)
        {
            $cookie = cookie('currentPage', $request->page, 2678400);
            return response()->json([
                'success' => true, 
                'page' => $request->page
            ])->cookie($cookie);
        }
        public function search(\Illuminate\Http\Request $request)
        {
            if( \Illuminate\Support\Facades\Auth::check() && !\Illuminate\Support\Facades\Auth::user()->hasRole('user') ) 
            {
                return redirect()->route(config('app.admurl').'.dashboard');
            }
            if( !\Illuminate\Support\Facades\Auth::check() ) 
            {
                return redirect()->route('frontend.auth.login');
            }
            $shop_id = (\Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::user()->shop_id : 0);
            $frontend = 'Default';
            if( $shop_id ) 
            {
                $shop = \VanguardLTE\Shop::find($shop_id);
                if( $shop ) 
                {
                    $frontend = $shop->frontend;
                }
            }
            $query = (isset($request->q) ? $request->q : '');
            $games = \VanguardLTE\Game::where('view', 1);
            $games = $games->where('shop_id', $shop_id);
            $games = $games->where('name', 'like', '%' . $query . '%');
            $detect = new \Detection\MobileDetect();
            if( $detect->isMobile() || $detect->isTablet() ) 
            {
                $games = $games->whereIn('device', [
                    0, 
                    2
                ]);
            }
            else
            {
                $games = $games->whereIn('device', [
                    1, 
                    2
                ]);
            }
            $games = $games->orderBy('name', 'ASC');
            $games = $games->get();
            return view('frontend.' . $frontend . '.games.search', compact('games'));
        }
        public function go(\Illuminate\Http\Request $request, $game)
        {
            if( \Illuminate\Support\Facades\Auth::check() && !\Illuminate\Support\Facades\Auth::user()->hasRole('user') ) 
            {
                return redirect('/');
            }
            if( !\Illuminate\Support\Facades\Auth::check() ) 
            {
                return redirect()->route('frontend.auth.login');
            }

            $detect = new \Detection\MobileDetect();
            $userId = \Illuminate\Support\Facades\Auth::id();
            //reset playing_game field to null for standalone games.
            $user = auth()->user();
            if ($user)
            {
                // \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::terminate($user->id);
                $user->update(['playing_game' => null]);
            }
            // if (str_contains($user->username, 'testfor'))
            // {
            //     abort(404);
            // }
            if (!isset($game))
            {
                return redirect()->route('frontend.auth.login');
            }
            $slot = null;
            $object = '\VanguardLTE\Games\\' . $game . '\SlotSettings';
            if (class_exists($object))
            {
                $slot = new $object($game, $userId);
                // return redirect()->route('frontend.auth.login');
            }
            $game = \VanguardLTE\Game::where([
                'name' => $game, 
                'shop_id' => \Illuminate\Support\Facades\Auth::user()->shop_id
            ]);
            
            if( $detect->isMobile() || $detect->isTablet() ) 
            {
                $game = $game->whereIn('device', [
                    0, 
                    2
                ]);
            }
            else
            {
                $game = $game->whereIn('device', [
                    1, 
                    2
                ]);
            }
            $game = $game->first();
            if( !$game ) 
            {
                return redirect()->route('frontend.game.list');
            }
            if( !$game->view ) 
            {
                return redirect()->route('frontend.game.list');
            }

            
            $envID = $game->original_id;
            $styleName = config('app.stylename');
            $replayUrl = config('app.replayurl');
            $is_api = false;
            $cq_loadimg = '';
            if(strpos($game->name, 'CQ9') !== false){
                $cq_promo = \VanguardLTE\CQPromo::first();
                if(isset($cq_promo)){
                    $cq_loadimg = $cq_promo->promoid;
                }
            }
            $pagelang = 'ko';
            $parent = auth()->user()->referral;
            while ($parent && !$parent->isInOutPartner())
            {
                $parent = $parent->referral;
            }
            $pagelang = $parent->language;

            $cats = $game->categories;
            if ($cats){
                foreach ($cats as $ct)
                {
                    if ($ct->category->type == 'pball')
                    {
                        //here you must go to mini game blade file
                        $object =  'VanguardLTE\Http\Controllers\Web\GameParsers\PowerBall\\'.$game->name;
                        if(!class_exists($object))
                        {
                            abort(404);
                        }
                        $gameInfo = new $object($game->original_id);
                        $pbGameResults = \VanguardLTE\PowerBallModel\PBGameResult::where('game_id',$game->original_id)->get();
                        $token = $user->api_token;
                        $gameName = $game->title;
                        $betMax = $game->rezerv;
                        return view('frontend.games.list.PowerBall', compact('pbGameResults', 'gameInfo', 'token','gameName','betMax'));
                    }

                }
            }

            return view('frontend.games.list.' . $game->name, compact('slot', 'game', 'is_api','envID', 'userId', 'styleName', 'replayUrl', 'cq_loadimg','pagelang'));
        }
        public function pball_go(\Illuminate\Http\Request $request)
        {
            $game = $request->game;
            $object =  'VanguardLTE\Http\Controllers\Web\GameParsers\PowerBall\\'.$game;
            if(!class_exists($object))
            {
                abort(404);
            }
            $shop_id=0;
            $token = '';
            if(\Illuminate\Support\Facades\Auth::check()){
                $shop_id = \Illuminate\Support\Facades\Auth::user()->shop_id;
                $token = \Illuminate\Support\Facades\Auth::user()->api_token;
            }
            $game = \VanguardLTE\Game::where([
                'name' => $game, 
                'shop_id' => $shop_id,
                'view' => 1
            ])->first();
            $gameInfo = new $object($game->original_id);
            $pbGameResults = \VanguardLTE\PowerBallModel\PBGameResult::where('game_id',$game->original_id)->get();
            
            $gameName = $game->title;
            $betMax = $game->rezerv;
            return view('frontend.games.list.PowerBall', compact('pbGameResults', 'gameInfo', 'token','gameName','betMax'));
        }
        public function startGameWithiFrame(\Illuminate\Http\Request $request, \VanguardLTE\Repositories\Session\SessionRepository $sessionRepository)
        {
            $game = $request->game;
            $url = '/game/' . $game;
            return view('frontend.Default.games.apigame',compact('url'));
        }

        public function startGameWithToken(\Illuminate\Http\Request $request, \VanguardLTE\Repositories\Session\SessionRepository $sessionRepository)
        {
            $token = $request->token;
            if ($token == '')
            {
                abort(404);
            }
            $user = \VanguardLTE\User::where(['api_token' => $token, 'role_id' => 1])->first();
            if (!$user)
            {
                abort(404); //player not found
            }
            if (!\Auth::check())
            {
                \Auth::login($user);
                event(new \VanguardLTE\Events\User\LoggedIn());
            }
            else if ($user->id != auth()->user()->id)
            {
                event(new \VanguardLTE\Events\User\LoggedOut());
                // \Auth::logout();
                // \DB::table('sessions')
                // ->where('user_id', $user->id)
                // ->delete();
                \Auth::login($user);
                event(new \VanguardLTE\Events\User\LoggedIn());
            }
            
            //regenerate api token so that other user can not enter game with old token.
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
                $user->update([
                    'playing_game' => null,
                    'api_token' => $api_token,
                    'remember_token' => $api_token

                ]);
            }
            else
            {
                abort(404);
            }

      
            // $sessionRepository->invalidateAllSessionsForUser($user->id);


            $gamecode = $request->gamecode;
            $available_provider_cats = \VanguardLTE\Category::where('shop_id', $user->shop_id)->whereNotNull('provider')->where('view',1)->get();
            foreach ($available_provider_cats as $ct)
            {
                $gameobj = call_user_func('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($ct->provider) . 'Controller::getGameObj', $gamecode);
                if ($gameobj && $gameobj['href'] == $ct->href)
                {
                    //check if game is visible
                    if (isset($gameobj['view']) && $gameobj['view'] == 1)
                    {
                        $res = call_user_func('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($ct->provider) . 'Controller::getgamelink', $gamecode);
                        if ($res['error'] == false)
                        {
                            if ($ct->status == 0)
                            {
                                return response()->view('errors.maintenance', [], 200)->header('Content-Type', 'text/html');
                            }
                            return redirect($res['data']['url']);
                        }
                    }
                }
            }

            //for owner games
            

            $game = \VanguardLTE\Game::where(['shop_id' => $user->shop_id, 'original_id' => $gamecode])->first();
            if (!$game)
            {
                abort(404);
            }
            if ($game->view == 0)
            {
                return response()->view('system.pages.gameisclosed', [], 200)->header('Content-Type', 'text/html');
            }
            $fakeparams = [
                'jackpotid' => 0,
                'exitGame' => 1,
                'extra' => 0,
                'mjckey' => uniqid('AUTH@') . uniqid('~style@'),
                'game' => $game->name, //this is real param
                'lobbyUrl' => 'js://window.close();',
            ];
            return redirect(route('frontend.game.startgame',$fakeparams));
        }

        public function server(\Illuminate\Http\Request $request, $game)
        {
            /*if( \Illuminate\Support\Facades\Auth::check() && !\Illuminate\Support\Facades\Auth::user()->hasRole('user') ) 
            {
                echo '{"responseEvent":"error","responseType":"start","serverResponse":"Wrong User"}';
                exit();
            }
            if( !\Illuminate\Support\Facades\Auth::check() ) 
            {
            }*/
            $GLOBALS['rgrc'] = config('app.salt');
            $userId = \Illuminate\Support\Facades\Auth::id();
            $user = \VanguardLTE\User::find($userId);
            if (!$user || $user->remember_token != $user->api_token)
            {
                exit('unlogged-1'); // it must be different per every game. but...
            }
            if (!$user || $user->playing_game != null)
            {
                exit('double game detected'); // it must be different per every game. but...
            }
            $object = '\VanguardLTE\Games\\' . $game . '\Server';
            $server = new $object();
            echo $server->get($request, $game, $userId);
        }

        public function game_result(\Illuminate\Http\Request $request)
        {
            $username = $request->username;
            $user = \VanguardLTE\User::where('username', $username)->first();
            if (!$user)
            {
                abort(404);
            }
            $user_id = $user->id;
            $statistics = \VanguardLTE\StatGame::select('stat_game.*')->orderBy('stat_game.date_time', 'DESC');
            $statistics = $statistics->where('stat_game.user_id', $user_id);
            $game_name = $request->gameType;
            $statistics = $statistics->where('stat_game.game', $game_name);
            $records = $statistics->paginate(20);

            //$sumst = \VanguardLTE\StatGame::select('SUM(bet) as totalbet, SUM(win) as totalwin')->orderBy('stat_game.date_time', 'DESC');
            $sumst = \VanguardLTE\StatGame::where('stat_game.user_id', $user_id)->where('stat_game.game', $game_name);
            $totalbet = $sumst->sum('bet');
            $totalwin = $sumst->sum('win');
            $totalincome = $totalwin - $totalbet;


            return view('frontend.help.'. $game_name.'.game_result', compact('records', 'totalbet', 'totalwin', 'totalincome'));
        }

        public function pay_table(\Illuminate\Http\Request $request)
        {
            //$user_id = auth()->user()->id;
            $bet_rate = $request->bet_rate;
            $game_name = $request->gameType;
            $gamepaytable = \VanguardLTE\GamePaytableVT::select('*')->where('game_name', $game_name)->get();
            if ($gamepaytable->count() > 0){
                $Paytable = json_decode($gamepaytable[0]->pay_table, true);
                for($i = 0; $i < count($Paytable); $i++) {
                    for($j =0; $j < 5; $j++){
                        $Paytable[$i][$j] = $Paytable[$i][$j] * $bet_rate / 100;
                    }
                }
                return view('frontend.help.'. $game_name.'.pay_table', compact('Paytable'));
            }
            else 
            {
                abort(404);
            }
        }


/*        public function security()
        {
            if( config('LicenseDK.APL_INCLUDE_KEY_CONFIG') != 'wi9qydosuimsnls5zoe5q298evkhim0ughx1w16qybs2fhlcpn' ) 
            {
                return false;
            }
            if( md5_file(base_path() . '/app/Lib/LicenseDK.php') != '3c5aece202a4218a19ec8c209817a74e' ) 
            {
                return false;
            }
            if( md5_file(base_path() . '/config/LicenseDK.php') != '951a0e23768db0531ff539d246cb99cd' ) 
            {
                return false;
            }
            return true;
        }*/

        
    }

}
namespace 
{
    function onkXppk3PRSZPackRnkDOJaZ9()
    {
        return 'OkBM2iHjbd6FHZjtvLpNHOc3lslbxTJP6cqXsMdE4evvckFTgS';
    }

}

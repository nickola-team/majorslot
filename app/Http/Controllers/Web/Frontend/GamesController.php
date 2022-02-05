<?php 
namespace VanguardLTE\Http\Controllers\Web\Frontend
{
    class GamesController extends \VanguardLTE\Http\Controllers\Controller
    {
        public function index(\Illuminate\Http\Request $request, $category1 = '', $category2 = '')
        {
            if( \Illuminate\Support\Facades\Auth::check() && !\Illuminate\Support\Facades\Auth::user()->hasRole('user') ) 
            {
                return redirect()->route(config('app.admurl').'.dashboard');
            }

            $title = trans('app.games');
            $shop_id = (\Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::user()->shop_id : 0);
            $frontend = 'Default';
            $excat = ['pragmatic','hot', 'new', 'card','bingo','roulette', 'keno', 'novomatic','wazdan', 'habaneroplay', 'cq9play'];
            $site = \VanguardLTE\WebSite::where('domain', $request->root())->first();
            $adminid = 1;
            if ($site)
            {
                $frontend = $site->frontend;
                $title = $site->title;
                $adminid = $site->adminid;
                if ($frontend != 'Default') {
                    $excat[] = 'virtualtech';
                    $excat[] = 'skywind';
                }
            }
            else
            {
                return response()->view('system.pages.siteisclosed', [], 200)->header('Content-Type', 'text/html');
            }

            if ($shop_id == 0)
            {
                $categories = \VanguardLTE\Category::where([
                    'shop_id' => $shop_id,
                    'site_id' => $site->id,
                ])->whereNotIn('href',$excat)->orderby('position')->get();

                if (count($categories) == 0) // use default category
                {
                    $categories = \VanguardLTE\Category::where([
                        'shop_id' => $shop_id,
                        'site_id' => 0,
                    ])->whereNotIn('href',$excat)->orderby('position')->get();
                }
            }
            else
            {
                $categories = \VanguardLTE\Category::where('shop_id' , $shop_id)->whereNotIn('href',$excat)->orderby('position')->get();
            }
            $hotgames = [];

            $ppgames = \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::getgamelist('pp');
            $livegames = [];
            if (\VanguardLTE\Category::where('shop_id' , $shop_id)->where('href','live')->first())
            {
                $pplivegames = \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::getgamelist('live');
                $gameid = [413,201,101,104,518,513,512,224];
                foreach ($pplivegames as $l)
                {
                    if (in_array($l['gamecode'], $gameid))
                    {
                        $livegames[] = $l;
                    }
                }
            }

            if ($shop_id == 0 || str_contains(\Illuminate\Support\Facades\Auth::user()->username, 'testfor')) // not logged in or test account for game providers
            {
                if (count($ppgames) > 0){
                    $newgames = \VanguardLTE\NewGame::where('provider', 'pp')->get()->pluck('gameid')->toArray();
                    foreach ($ppgames as $game)
                    {
                        if (in_array($game['gamecode'] , $newgames))
                        {
                            $hotgames[] = $game;
                        }
                    }
                }
            }
            else
            {
                $pmId = \VanguardLTE\Category::where([
                    'href' => 'pragmatic', 
                    'shop_id' => 0
                ])->first();
                $games = \VanguardLTE\Game::select('games.*')->where('shop_id', 0)->orderBy('name', 'ASC');
                $games = $games->join('game_categories', 'game_categories.game_id', '=', 'games.id');
                $games = $games->where('game_categories.category_id', $pmId->id);
                $ppgamenames = $games->get()->pluck('name')->toArray();
                if (count($ppgames) > 0){
                    foreach ($ppgames as $pg)
                    {
                        $gamename = preg_replace('/[^a-zA-Z0-9 -]+/', '', $pg['name']) . 'PM';
                        if (in_array($gamename, $ppgamenames))
                        {
                            $hotgames[] = $pg;
                        }
                    }
                }
                $hotgames[] = ['name' => 'DuoFuDuoCai5Treasures', 'title' => '5트레저 다복이'];
                $hotgames[] = ['name' => 'DuoFuDuoCai88Fortune', 'title' => '88포츈 다복이'];
                $hotgames[] = ['name' => 'DuoFuDuoCaiDancingDrum', 'title' => '댄싱드럼 다복이'];
                //$hotgames[] = ['name' => 'BlackjackSurrenderPT', 'title' => '블랙 잭 써랜더'];
                //$hotgames[] = ['name' => 'BlackJackAM', 'title' => '블랙 잭'];
            }

            //add bng hot games
            $bnggames = \VanguardLTE\Http\Controllers\Web\GameProviders\BNGController::getgamelist('booongo');
            if (count($bnggames) > 0){
                $newgames = \VanguardLTE\NewGame::where('provider', 'bng')->get()->pluck('gameid')->toArray();
                foreach ($bnggames as $game)
                {
                    if (in_array($game['gamecode'] , $newgames))
                    {
                        $hotgames[] = $game;
                    }
                }
            }
            if (count($hotgames) % 4 > 0)
            {
                $len = 4 - count($hotgames) % 4;
                if (count($ppgames) > 0){
                    for ($i=0;$i<$len;$i++)
                    {
                        $exist = false;
                        do {
                            $idx = mt_rand(0, count($ppgames)-1);
                            $exist = false;
                            foreach ($hotgames as $game)
                            {
                                if (isset($game['gamecode']) && $game['gamecode'] == $ppgames[$idx]['gamecode'])
                                {
                                    $exist = true;
                                }
                            }
                        } while ($exist);
                        $hotgames[] = $ppgames[$idx];
                    }
                }
            }
            shuffle($hotgames);

            $superadminId = \VanguardLTE\User::where('role_id',8)->first()->id;
            $notice = \VanguardLTE\Notice::where(['user_id' => $superadminId, 'active' => 1, 'type' => 'user'])->first(); //for admin's popup
            $msgs = [];
            $unreadmsg = 0;
            if ($notice==null || $shop_id != 0) { //it is logged in
                $notice = \VanguardLTE\Notice::where(['user_id' => $adminid, 'active' => 1, 'type' => 'user'])->first(); //for admin's popup
            }
            $trhistory = [];
            if ($shop_id != 0)
            {
                $msgs = \VanguardLTE\Message::whereIn('user_id', [0, auth()->user()->id])->get(); //messages
                $unreadmsg = \VanguardLTE\Message::whereIn('user_id', [0, auth()->user()->id])->whereNull('read_at')->count();
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
            return view('frontend.' . $frontend . '.games.list', compact('categories', 'hotgames', 'livegames', 'title', 'notice', 'msgs','unreadmsg', 'ppgames', 'trhistory'));
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
                return redirect()->route(config('app.admurl').'.dashboard');
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
                $user->update(['playing_game' => null]);
            }
            if (!isset($game))
            {
                return redirect()->route('frontend.auth.login');
            }
            $object = '\VanguardLTE\Games\\' . $game . '\SlotSettings';
            if (!class_exists($object))
            {
                return redirect()->route('frontend.auth.login');
            }
            $slot = new $object($game, $userId);
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
            return view('frontend.games.list.' . $game->name, compact('slot', 'game', 'is_api','envID', 'userId', 'styleName', 'replayUrl'));
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
            $object = '\VanguardLTE\Games\\' . $game . '\Server';
            $server = new $object();
            echo $server->get($request, $game, $userId);
        }

        public function game_result(\Illuminate\Http\Request $request)
        {
            $user_id = auth()->user()->id;
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

            $Paytable = json_decode($gamepaytable[0]->pay_table, true);
            for($i = 0; $i < count($Paytable); $i++) {
                for($j =0; $j < 5; $j++){
                    $Paytable[$i][$j] = $Paytable[$i][$j] * $bet_rate / 100;
                }
            }
            return view('frontend.help.'. $game_name.'.pay_table', compact('Paytable'));
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

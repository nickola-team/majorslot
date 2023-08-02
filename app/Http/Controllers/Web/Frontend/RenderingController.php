<?php 
namespace VanguardLTE\Http\Controllers\Web\Frontend
{
    use Log;
    class RenderingController extends \VanguardLTE\Http\Controllers\Controller
    {
        public function __construct()
        {
            $this->middleware('auth');
        }

        public function waiting($provider, $gamecode, \Illuminate\Http\Request $request)
        {
            $alreadyRequest = \VanguardLTE\GameLaunch::where([
                'user_id' => auth()->user()->id,
                'provider' => $provider,
                'finished' => '0'
            ])->first();

            $args = $request->all();
            $requestId = 0;
            if ($alreadyRequest)
            {
                $requestId = $alreadyRequest->id;
            }
            else{
                $query_string = null;
                if (count($args) > 0)
                {
                    $query_string = http_build_query($args);
                }
                $launchRequest = \VanguardLTE\GameLaunch::create(
                    [
                        'user_id' => auth()->user()->id,
                        'provider' => $provider,
                        'gamecode' => $gamecode,
                        'finished' => '0',
                        'argument' => $query_string
                    ]
                );
                $requestId = $launchRequest->id;
            }
            Log::channel('monitor_game')->info('Create gamelaunch | ' . strtoupper($provider) . ':' . $gamecode . ' : ' . auth()->user()->username . '('.auth()->user()->id . ') : ' . $requestId);
            $prompt = true;
            if (count($args) > 0)
            {
                $prompt = false;
            }
            $site = \VanguardLTE\WebSite::where('domain', $request->root())->first();
            $frontend = 'Default';
            $view_name = 'frontend.'.$frontend.'.games.waiting';
            if ($site)
            {
                $frontend = $site->frontend;

                if (!\View::exists('frontend.'.$frontend.'.games.waiting'))
                {
                    $frontend = 'Default';
                }
                $view_name = 'frontend.'.$frontend.'.games.waiting';

                if (\View::exists('frontend.Default.games.waiting_' . $provider))
                {
                    $view_name = 'frontend.Default.games.waiting_' . $provider;
                }

            }
            return view($view_name, compact('requestId', 'prompt'));
        }

        public function launch($requestid, \Illuminate\Http\Request $request)
        {
            $launchRequest = \VanguardLTE\GameLaunch::where('id', $requestid)->first();
            if ($launchRequest && $requestid)
            {
                if ($launchRequest->user_id != auth()->user()->id)
                {
                    return response()->json(['error' => true, 'url' => '']);
                }
                if ($launchRequest->finished == 0)
                {
                    return response()->json(['error' => true, 'url' => '']);
                }
                if ($launchRequest->argument)
                {
                    return response()->json(['error' => false, 'url' => $launchRequest->launchUrl. '?' . $launchRequest->argument . '&t=' . $launchRequest->id]);
                }
                else
                {
                    return response()->json(['error' => false, 'url' => $launchRequest->launchUrl . '?t=' . $launchRequest->id]);
                }
            }
            else
            {
                return response()->json(['error' => true, 'url' => '']);
            }

        }

        public function theplusrender($gamecode, \Illuminate\Http\Request $request)
        {
            $lobby = $request->lobby;
            $t = $request->t; //check timestamp if it is normal request
            $launchRequest = \VanguardLTE\GameLaunch::where('id', $t)->first();
            if (!$launchRequest)
            {
                //this is irlegal request.
                abort(404);
            }
            $user = auth()->user();
            if (!$user)
            {
                abort(404);
            }
            if ($user->id != $launchRequest->user_id)
            {
                abort(404);
            }

            

            $gameObj = \VanguardLTE\Http\Controllers\Web\GameProviders\TPController::getGameObj($gamecode);
            if (!$gameObj)
            {
                $gameObj = \VanguardLTE\Http\Controllers\Web\GameProviders\TPController::getGameObjBySymbol(8, $gamecode);
                if (!$gameObj)
                {
                    abort(404);
                }
            }
            $cat = null;
            $embed_games = null;
            if ($gameObj['href'] == \VanguardLTE\Http\Controllers\Web\GameProviders\TPController::TP_PP_HREF) {
                $shop_id = \Auth::user()->shop_id;
                $cat = \VanguardLTE\Category::where([
                    'shop_id' => $shop_id,
                    'href' => 'pragmatic',
                    'view' => 1
                ])->first();

                $embed_games = \VanguardLTE\Game::where([
                    'shop_id' => $shop_id,
                    'label' => $gameObj['symbol'],
                    'view' => 1,
                    ]
                )->first();
            }

            if ($gameObj['href'] == 'tp_hbn') {
                $gamename = $gameObj['name'];
                $gamename = preg_replace('/[^a-zA-Z0-9 -]+/', '', $gamename) . 'HBN';
                $gamename = preg_replace('/^(\d)([a-zA-Z0-9 -]+)/', '_$1$2', $gamename);
                $shop_id = \Auth::user()->shop_id;
                $cat = \VanguardLTE\Category::where([
                    'shop_id' => $shop_id,
                    'href' => 'habaneroplay',
                    'view' => 1
                ])->first();
                $embed_games = \VanguardLTE\Game::where([
                    'shop_id' => $shop_id,
                    'name' => 'SGThe' . $gamename,
                    'view' => 1,
                    ]
                )->first();
            }
            
            $alonegame = 0;
            $url = null;
            $data = [];

            if (str_contains(\Illuminate\Support\Facades\Auth::user()->username, 'testfor'))
            {
                abort(404);
            }
           
            if (!str_contains(\Illuminate\Support\Facades\Auth::user()->username, 'testfor') && $embed_games && $cat) {
                //you must withdraw user balance
                $data = \VanguardLTE\Http\Controllers\Web\GameProviders\TPController::withdrawAll(auth()->user()->id);
                // if ($data['error'] == true)
                // {
                //     $data['msg'] = 'Withdraw Error';
                //     return view('frontend.Default.games.theplus', compact('data'));
                // }
                $url = url('/game/' . $embed_games->name);
                $alonegame = 1;
            }
            else {
                if ($launchRequest->finished > 1) //only use this token once
                {
                    abort(404);
                }

                //게임런칭
                $data = \VanguardLTE\Http\Controllers\Web\GameProviders\TPController::getgamelink_tp($gameObj['gamecode'], $user);
                if ($data['error'] == true)
                {
                    $data['msg'] = 'GameLinkError';
                    return view('frontend.Default.games.theplus', compact('data'));
                }
                $url = $data['data']['url'];
            }

            $launchRequest->update([
                'finished' => $launchRequest->finished + 1
            ]);
            //delete all user's request
            // \VanguardLTE\GameLaunch::where('user_id', $user->id)->delete();
            

            if ($alonegame == 0)
            {
                $user->update([
                    'playing_game' => \VanguardLTE\Http\Controllers\Web\GameProviders\TPController::TP_PROVIDER,
                    'played_at' => time(),
                ]);
            }
            if ($lobby == 'mini')
            {
                return redirect($url);
            }
            else
            {
                return view('frontend.Default.games.theplus', compact('url', 'alonegame', 'data'));
            }
        }

        public function pragmaticrender($gamecode, \Illuminate\Http\Request $request)
        {
            $lobby = $request->lobby;
            $t = $request->t; //check timestamp if it is normal request
            $launchRequest = \VanguardLTE\GameLaunch::where('id', $t)->first();
            if (!$launchRequest)
            {
                //this is irlegal request.
                return redirect('/');
            }
            $user = auth()->user();
            if (!$user)
            {
                return redirect('/');
            }
            if ($user->id != $launchRequest->user_id)
            {
                return redirect('/');
            }

            $launchRequest->delete();

            $gamename = \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::gamecodetoname($gamecode)[0];
            $gamename = preg_replace('/[^a-zA-Z0-9 ]+/', '', $gamename) . 'PM';
            $gamename = preg_replace('/^(\d)([a-zA-Z0-9 ]+)/', '_$1$2', $gamename);
            $shop_id = \Auth::user()->shop_id;
            $cat = \VanguardLTE\Category::where([
                'shop_id' => $shop_id,
                'href' => 'pragmatic',
                'view' => 1
            ])->first();
            $pm_games = \VanguardLTE\Game::where([
                'shop_id' => $shop_id,
                'name' => $gamename,
                'view' => 1,
                ]
            )->get()->first();
            
            $enhancedgames = env('PP_GAMES', '1');
            $alonegame = 0;
            $url = null;
            $data = [];
            $ppiplist = ['195.69.223.', '176.112.120.', '185.8.155.', '5.2.130.103', '84.1.113.218', '81.196.86.120','185.54.229.'];
            $ip = $request->server('HTTP_CF_CONNECTING_IP')??($request->server('X_FORWARDED_FOR')??$request->server('REMOTE_ADDR'));
            $blocked = false;
            foreach ($ppiplist as $net)
            {
                if (str_contains($ip, $net))
                {
                    $blocked = true;
                    break;
                }
            }
            
            if ($enhancedgames==1 && !str_contains(\Illuminate\Support\Facades\Auth::user()->username, 'testfor') && $pm_games && $cat && !$blocked) {
                $url = url('/game/' . $gamename);
                $alonegame = 1;
            }
            else {
                    //게임런칭
                    $data = \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::getgamelink_pp($gamecode, $user);
                    if ($data['error'] == true)
                    {
                        $data['msg'] = 'getgamelink';
                        return view('frontend.Default.games.pragmatic', compact('data'));
                    }
                    $url = $data['data']['url'];
            }

            if ($alonegame == 0)
            {
                $user->update([
                    'playing_game' => 'pp',
                    'played_at' => time(),
                ]);
            }
            if ($lobby == 'mini')
            {
                return redirect($url);
            }
            else
            {
                return view('frontend.Default.games.pragmatic', compact('url', 'alonegame', 'data'));
            }
        }

        public function habanerorender($gamecode, \Illuminate\Http\Request $request)
        {
            $user = auth()->user();
            if (!$user)
            {
                return redirect('/');
            }

            $gameObj = \VanguardLTE\Http\Controllers\Web\GameProviders\HBNController::getGameObj($gamecode);
            if (!$gameObj)
            {
                return redirect('/');
            }
            $gamename = $gameObj['name'];
            $gamename = preg_replace('/[^a-zA-Z0-9 -]+/', '', $gamename) . 'HBN';
            $gamename = preg_replace('/^(\d)([a-zA-Z0-9 -]+)/', '_$1$2', $gamename);
            $shop_id = \Auth::user()->shop_id;
            $cat = \VanguardLTE\Category::where([
                'shop_id' => $shop_id,
                'href' => 'habaneroplay',
                'view' => 1
            ])->first();
            $hbn_games = \VanguardLTE\Game::where([
                'shop_id' => $shop_id,
                'name' => $gamename,
                'view' => 1,
                ]
            )->get()->first();
            
            $alonegame = 0;
            $url = null;
            $data = [];
            if (!str_contains(\Illuminate\Support\Facades\Auth::user()->username, 'testfor') && $hbn_games && $cat) {
                $url = url('/game/' . $gamename);
                $alonegame = 1;
            }
            else {
                    //게임런칭
                    $url = \VanguardLTE\Http\Controllers\Web\GameProviders\HBNController::makegamelink($gamecode, 'real');
            }
            return view('frontend.Default.games.habanero', compact('url', 'alonegame', 'data'));
            
        }

        public function cq9render($gamecode, \Illuminate\Http\Request $request)
        {
            $user = auth()->user();
            if (!$user)
            {
                return redirect('/');
            }

            $gamename = \VanguardLTE\Http\Controllers\Web\GameProviders\CQ9Controller::gamecodetoname($gamecode);
            $gamename = preg_replace('/[^a-zA-Z0-9 -]+/', '', $gamename) . 'CQ9';
            $gamename = preg_replace('/^(\d)([a-zA-Z0-9 -]+)/', '_$1$2', $gamename);
            $shop_id = \Auth::user()->shop_id;
            $cat = \VanguardLTE\Category::where([
                'shop_id' => $shop_id,
                'href' => 'cq9play',
                'view' => 1
            ])->first();
            $cq9_games = \VanguardLTE\Game::where([
                'shop_id' => $shop_id,
                'name' => $gamename,
                'view' => 1,
                ]
            )->get()->first();
            
            $alonegame = 0;
            $url = null;
            $data = [];
            if (!str_contains(\Illuminate\Support\Facades\Auth::user()->username, 'testfor') && $cq9_games && $cat) {
                $url = url('/game/' . $gamename);
                $alonegame = 1;
            }
            else {
                //게임런칭
                $url = \VanguardLTE\Http\Controllers\Web\GameProviders\CQ9Controller::makegamelink($gamecode);
                if ($url == null)
                {
                    $data['msg'] = '게임링크 오류';
                }
            }
            return view('frontend.Default.games.cq9', compact('url', 'alonegame', 'data'));
            
        }

        public function booongorender($gamecode, \Illuminate\Http\Request $request)
        {
            $user = auth()->user();
            if (!$user)
            {
                return redirect('/');
            }

            $gamename = \VanguardLTE\Http\Controllers\Web\GameProviders\BNGController::gamecodetoname($gamecode);
            $gamename = preg_replace('/[^a-zA-Z0-9 -]+/', '', $gamename);
            // $gamename = preg_replace('/^(\d)([a-zA-Z0-9 -]+)/', '_$1$2', $gamename);
            $shop_id = \Auth::user()->shop_id;
            $cat = \VanguardLTE\Category::where([
                'shop_id' => $shop_id,
                'href' => 'bngplay',
                'view' => 1
            ])->first();
            $bng_games = \VanguardLTE\Game::where([
                'shop_id' => $shop_id,
                'name' => $gamename,
                'view' => 1,
                ]
            )->get()->first();
            
            $alonegame = 0;
            $url = null;
            $data = [];
            if (!str_contains(\Illuminate\Support\Facades\Auth::user()->username, 'testfor') && $bng_games && $cat) {
                $url = url('/game/' . $gamename);
                $alonegame = 1;
            }
            else {
                    //게임런칭
                $url = \VanguardLTE\Http\Controllers\Web\GameProviders\BNGController::makegamelink($gamecode, 'real');
            }
            return view('frontend.Default.games.booongo', compact('url', 'alonegame', 'data'));
            
        }
        public function gamerenderv2($provider, $gamecode, \Illuminate\Http\Request $request)
        {
            $user = auth()->user();
            if (!$user)
            {
                return redirect('/');
            }
            $t = $request->t; //check timestamp if it is normal request
            $launchRequest = \VanguardLTE\GameLaunch::where('id', $t)->first();
            if (!$launchRequest)
            {
                //this is irlegal request.
                abort(404);
            }
            if ($user->id != $launchRequest->user_id)
            {
                abort(404);
            }

            Log::channel('monitor_game')->info('Delete gamelaunch | ' . strtoupper($launchRequest->provider) . ':' . $launchRequest->gamecode . ' : ' . $user->username . '('. $user->id . ') : ' . $launchRequest->id);

            $launchRequest->delete();
            $object = '\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($provider)  . 'Controller';
            if (!class_exists($object))
            {
                abort(404);
            }

            // $game = call_user_func('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($provider) . 'Controller::getGameObj', $gamecode);
            // if (!$game)
            // {
            //     abort(404);
            // }

            // $user->update([
            //     'playing_game' => $game['href'],
            //     'played_at' => time(),
            // ]);
            

            if ($provider == 'bnn')
            {

                $user->update([
                    'playing_game' => $gamecode,
                    'played_at' => time(),
                ]);
            }
            
            else if ($provider == 'kuza')
            {
                $user->update([
                    'playing_game' => strtolower($provider),
                    'played_at' => time(),
                ]);
            }
            else if ($provider == 'xmx')
            {
                $game = \VanguardLTE\Http\Controllers\Web\GameProviders\XMXController::getGameObj($gamecode);
                if (!$game)
                {
                    abort(404);
                }
                $user->update([
                    'playing_game' => $game['href'],
                    'played_at' => time(),
                ]);
            }
            else if ($provider == 'kten')
            {
                $game = \VanguardLTE\Http\Controllers\Web\GameProviders\KTENController::getGameObj($gamecode);
                if (!$game)
                {
                    abort(404);
                }
                if ($game['href'] == 'kten-hbn') {
                    $gamename = $game['name'];
                    $gamename = preg_replace('/[^a-zA-Z0-9 -]+/', '', $gamename) . 'HBN';
                    $gamename = preg_replace('/^(\d)([a-zA-Z0-9 -]+)/', '_$1$2', $gamename);
                    $shop_id = \Auth::user()->shop_id;
                    $cat = \VanguardLTE\Category::where([
                        'shop_id' => $shop_id,
                        'href' => 'habaneroplay',
                        'view' => 1
                    ])->first();
                    $embed_games = \VanguardLTE\Game::where([
                        'shop_id' => $shop_id,
                        'name' => 'SGThe' . $gamename,
                        'view' => 1,
                        ]
                    )->first();
                    if ($embed_games && $cat) {
                        $url = url('/game/' . $embed_games->name);
                        $alonegame = 1;
                        $data = null;
                        return view('frontend.Default.games.theplus', compact('url', 'alonegame', 'data'));
                    }
                }
                $user->update([
                    'playing_game' => $game['href'],
                    'played_at' => time(),
                ]);
            }
            else if ($provider == 'honor')
            {
                $game = \VanguardLTE\Http\Controllers\Web\GameProviders\HONORController::getGameObj($gamecode);
                if (!$game)
                {
                    abort(404);
                }
                if ($game['href'] == 'honor-cq9') {
                    $gamename = $game['name'];
                    $gamename = preg_replace('/[^a-zA-Z0-9 -]+/', '', $gamename) . 'CQ9';
                    $gamename = preg_replace('/^(\d)([a-zA-Z0-9 -]+)/', '_$1$2', $gamename);
                    $shop_id = \Auth::user()->shop_id;
                    $cat = \VanguardLTE\Category::where([
                        'shop_id' => $shop_id,
                        'href' => 'cq9play',
                        'view' => 1
                    ])->first();
                    $embed_games = \VanguardLTE\Game::where([
                        'shop_id' => $shop_id,
                        'name' => $gamename,
                        'view' => 1,
                        ]
                    )->first();
                    if ($embed_games && $cat) {
                        $fakeparams = [
                            'token' => uniqid(''),
                            'language' => 'ko',
                            'dollarsign' => 'Y',
                            'app' => 'N',
                            'detect' => 'N',
                            'game' => $gamename, //this is real param
                        ];
                        return redirect(route('frontend.game.startgame',$fakeparams));
                    }
                }
                $user->update([
                    'playing_game' => $game['href'],
                    'played_at' => time(),
                ]);
            }
            else //if ($provider == 'honor')
            {
                if (method_exists('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($provider) . 'Controller','getGameObj'))
                {
                    $game = call_user_func('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($provider) . 'Controller::getGameObj', $gamecode);
                    if (!$game)
                    {
                        abort(404);
                    }
                    $user->update([
                        'playing_game' => $game['href'],
                        'played_at' => time(),
                    ]);
                }
                else
                {
                    abort(404);
                }
            }
            // else if ($provider == 'gold')
            // {
            //     $game = \VanguardLTE\Http\Controllers\Web\GameProviders\GOLDController::getGameObj($gamecode);
            //     if (!$game)
            //     {
            //         abort(404);
            //     }
            //     $user->update([
            //         'playing_game' => $game['href'],
            //         'played_at' => time(),
            //     ]);
            // }
            // else
            // {
            //     $user->update([
            //         'playing_game' => $gamecode,
            //         'played_at' => time(),
            //     ]);
            // }
            $url = call_user_func('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($provider) . 'Controller::makegamelink', $gamecode, $user);
            if ($url == null)
            {
                abort(404);
            }
            return redirect($url);
            
        }

        public function gamerender($provider, $gamecode, \Illuminate\Http\Request $request)
        {
            $user = auth()->user();
            if (!$user)
            {
                return redirect('/');
            }
            $t = $request->t; //check timestamp if it is normal request
            $launchRequest = \VanguardLTE\GameLaunch::where('id', $t)->first();
            if (!$launchRequest)
            {
                //this is irlegal request.
                abort(404);
            }
            if ($user->id != $launchRequest->user_id)
            {
                abort(404);
            }

            $launchRequest->delete();
            $object = '\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($provider)  . 'Controller';
            if (!class_exists($object))
            {
                abort(404);
            }

            $rqtime = 5; //default 5s

            if ($provider == 'bnn')
            {
                $user->update([
                    'playing_game' => strtolower($provider) . '_' . $gamecode,
                    'played_at' => time(),
                ]);
                $rqtime = 30; //default 5s
            }
            else if ($provider == 'xmx')
            {
                $game = \VanguardLTE\Http\Controllers\Web\GameProviders\XMXController::getGameObj($gamecode);
                if ($game==null)
                {
                    abort(404);
                }
                if ($game['href'] == 'xmx-cq9') {
                    $gamename = $game['name'];
                    $gamename = preg_replace('/[^a-zA-Z0-9 -]+/', '', $gamename) . 'CQ9';
                    $gamename = preg_replace('/^(\d)([a-zA-Z0-9 -]+)/', '_$1$2', $gamename);
                    $shop_id = \Auth::user()->shop_id;
                    $cat = \VanguardLTE\Category::where([
                        'shop_id' => $shop_id,
                        'href' => 'cq9play',
                        'view' => 1
                    ])->first();
                    $embed_games = \VanguardLTE\Game::where([
                        'shop_id' => $shop_id,
                        'name' => $gamename,
                        'view' => 1,
                        ]
                    )->first();
                    if ($embed_games && $cat) {
                        $url = url('/game/' . $embed_games->name);
                        $alonegame = 1;
                        $data = null;
                        return view('frontend.Default.games.theplus', compact('url', 'alonegame', 'data'));
                    }
                }

                if ($game['href'] == 'xmx-hbn') {
                    $gamename = $game['name'];
                    $gamename = preg_replace('/[^a-zA-Z0-9 -]+/', '', $gamename) . 'HBN';
                    $gamename = preg_replace('/^(\d)([a-zA-Z0-9 -]+)/', '_$1$2', $gamename);
                    $shop_id = \Auth::user()->shop_id;
                    $cat = \VanguardLTE\Category::where([
                        'shop_id' => $shop_id,
                        'href' => 'habaneroplay',
                        'view' => 1
                    ])->first();
                    $embed_games = \VanguardLTE\Game::where([
                        'shop_id' => $shop_id,
                        'name' => 'SGThe' . $gamename,
                        'view' => 1,
                        ]
                    )->first();
                    if ($embed_games && $cat) {
                        $url = url('/game/' . $embed_games->name);
                        $alonegame = 1;
                        $data = null;
                        return view('frontend.Default.games.theplus', compact('url', 'alonegame', 'data'));
                    }
                }

                $user->update([
                    'playing_game' => strtolower($provider) . '_' . $game['href'],
                    'played_at' => time(),
                ]);
                $rqtime = 30; //default 5s
            }
            else
            {
                $user->update([
                    'playing_game' => strtolower($provider),
                    'played_at' => time(),
                ]);
            }
            $url = call_user_func('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($provider) . 'Controller::makegamelink', $gamecode, $user);
            return view('frontend.Default.games.render', compact('provider','url','rqtime'));
            
        }
    }

}

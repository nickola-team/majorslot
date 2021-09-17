<?php 
namespace VanguardLTE\Http\Controllers\Web\Frontend
{
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
            $prompt = true;
            if (count($args) > 0)
            {
                $prompt = false;
            }
            return view('frontend.Default.games.waiting', compact('requestId', 'prompt'));
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
                if ($launchRequest->finished != 1)
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
            $gamename = preg_replace('/[^a-zA-Z0-9 -]+/', '', $gamename) . 'PM';
            $gamename = preg_replace('/^(\d)([a-zA-Z0-9 -]+)/', '_$1$2', $gamename);
            $shop_id = \Auth::user()->shop_id;
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
            if ($enhancedgames==1 && !str_contains(\Illuminate\Support\Facades\Auth::user()->username, 'testfor') && $pm_games) {
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

            $gamename = \VanguardLTE\Http\Controllers\Web\GameProviders\HBNController::gamecodetoname($gamecode);
            $gamename = preg_replace('/[^a-zA-Z0-9 -]+/', '', $gamename) . 'HBN';
            $gamename = preg_replace('/^(\d)([a-zA-Z0-9 -]+)/', '_$1$2', $gamename);
            $shop_id = \Auth::user()->shop_id;
            $hbn_games = \VanguardLTE\Game::where([
                'shop_id' => $shop_id,
                'name' => $gamename,
                'view' => 1,
                ]
            )->get()->first();
            
            $alonegame = 0;
            $url = null;
            $data = [];
            if (!str_contains(\Illuminate\Support\Facades\Auth::user()->username, 'testfor') && $hbn_games) {
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
            $cq9_games = \VanguardLTE\Game::where([
                'shop_id' => $shop_id,
                'name' => $gamename,
                'view' => 1,
                ]
            )->get()->first();
            
            $alonegame = 0;
            $url = null;
            $data = [];
            if (!str_contains(\Illuminate\Support\Facades\Auth::user()->username, 'testfor') && $cq9_games) {
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
            return view('frontend.Default.games.habanero', compact('url', 'alonegame', 'data'));
            
        }
    }

}

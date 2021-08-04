<?php 
namespace VanguardLTE\Http\Controllers\Web\Frontend
{
    class RenderingController extends \VanguardLTE\Http\Controllers\Controller
    {
        public function __construct()
        {
            $this->middleware('auth');
        }

        public function pragmaticrender($gamecode, \Illuminate\Http\Request $request)
        {
            $lobby = $request->lobby;
            $gamename = \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::gamecodetoname($gamecode)[0];
            $gamename = preg_replace('/[^a-zA-Z0-9 -]+/', '', $gamename) . 'PM';
            $shop_id = \Auth::user()->shop_id;
            $pm_games = \VanguardLTE\Game::where([
                'shop_id' => $shop_id,
                'name' => $gamename,
                'view' => 1,
                ]
            )->get()->first();
            $enhancedgames = env('PP_GAMES', '1');
            $alonegame = 0;
            if ($enhancedgames==1 && !str_contains(\Illuminate\Support\Facades\Auth::user()->username, 'testfor') && $pm_games) {
                $url = url('/game/' . $gamename);
                $alonegame = 1;
            }
            else {
                $user = auth()->user();
                if (!$user)
                {
                    $url = url('/');
                }
                else
                {
                    $data = \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::getBalance($user->id);
                    if ($data['error'] == -1) {
                        //연동오류
                        $data['msg'] = 'balance';
                        return view('frontend.Default.games.pragmatic', compact('data'));
                    }
                    else if ($data['error'] == 17) //Player not found
                    {
                        $data = \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::createPlayer($user->id);
                        if ($data['error'] != 0) //create player failed
                        {
                            //오류
                            $data['msg'] = 'createplayer';
                            return view('frontend.Default.games.pragmatic', compact('data'));
                        }
                    }
                    else if ($data['error'] == 0)
                    {
                        if ($data['balance'] > 0) //이미 밸런스가 있다면
                        {
                            $data = \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::transfer($user->id, -$data['balance']);
                        }
                    }
                    else //알수 없는 오류
                    {
                        //오류
                        $data['msg'] = 'balance';
                        return view('frontend.Default.games.pragmatic', compact('data'));
                    }
                    //밸런스 넘기기
                    $data = \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::transfer($user->id, $user->balance);
                    if ($data['error'] != 0)
                    {
                        //밸런스 넘기기 오류
                        $data['msg'] = 'transfer';
                        return view('frontend.Default.games.pragmatic', compact('data'));
                    }

                    //게임런칭

                    $data = \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::getgamelink_pp($gamecode, $user);
                    $url = $data['data']['url'];
                }
            }
            if ($lobby == 'mini')
            {
                return redirect($url);
            }
            else
            {
                return view('frontend.Default.games.pragmatic', compact('url', 'alonegame'));
            }
        }
    }

}

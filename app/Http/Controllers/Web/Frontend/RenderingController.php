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
            $gamename = \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::gamecodetoname($gamecode);
            $gamename = preg_replace('/[^a-zA-Z0-9 -]+/', '', $gamename) . 'PM';
            $shop_id = \Auth::user()->shop_id;
            $pm_games = \VanguardLTE\Game::where([
                'shop_id' => $shop_id,
                'name' => $gamename
                ]
            )->get()->first();
            if (!str_contains(\Illuminate\Support\Facades\Auth::user()->username, 'testfor') && $pm_games) {
                $url = url('/game/' . $gamename);
            }
            else {
                $data = \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::getgamelink_pp($gamecode);
                $url = $data['data']['url'];
            }
            if ($lobby == 'mini')
            {
                return redirect($url);
            }
            else
            {
                return view('frontend.Default.games.pragmatic', compact('url'));
            }
        }
    }

}

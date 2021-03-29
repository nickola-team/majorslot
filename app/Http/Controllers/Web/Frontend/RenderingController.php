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
            $data = \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::getgamelink_pp($gamecode);
            $url = $data['data']['url'];
            return view('frontend.Default.games.pragmatic', compact('url'));
        }
    }

}

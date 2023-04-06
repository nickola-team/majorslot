<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend\Argon
{
    class ShareBetController extends \VanguardLTE\Http\Controllers\Controller
    {
        public function __construct(\VanguardLTE\Repositories\User\UserRepository $users)
        {
            $this->middleware('auth');
            $this->middleware('permission:access.admin.panel');
        }

        public function index(\Illuminate\Http\Request $request)
        {
            
        }

        public function gamestat(\Illuminate\Http\Request $request)
        {

        }

        public function report_daily(\Illuminate\Http\Request $request)
        {

        }

        public function report_game(\Illuminate\Http\Request $request)
        {

        }


    }

}

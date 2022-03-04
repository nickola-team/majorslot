<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend\Argon
{
    class DWController extends \VanguardLTE\Http\Controllers\Controller
    {
        public function __construct(\VanguardLTE\Repositories\User\UserRepository $users)
        {
            $this->middleware('auth');
            $this->middleware('permission:access.admin.panel');
        }

        public function addrequest(\Illuminate\Http\Request $request)
        {
            return view('backend.argon.dw.addrequest');
        }

        public function outrequest(\Illuminate\Http\Request $request)
        {
            return view('backend.argon.dw.outrequest');
        }

        public function dealconvert(\Illuminate\Http\Request $request)
        {
            return view('backend.argon.dw.dealconvert');
        }

        public function addmanagement(\Illuminate\Http\Request $request)
        {
            return view('backend.argon.common.balance',compact('type', 'user', 'url'));
        }
        public function outmanagement(\Illuminate\Http\Request $request)
        {
            return view('backend.argon.common.balance',compact('type', 'user', 'url'));
        }

    }

}

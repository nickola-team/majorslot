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
            $comasterIds = auth()->user()->availableUsersByRole('comaster');
            $users = \VanguardLTE\User::whereIn('id', $comasterIds)->whereIn('status', [\VanguardLTE\Support\Enum\UserStatus::ACTIVE, \VanguardLTE\Support\Enum\UserStatus::BANNED]);
            if ($request->user != '')
            {
                $users = $users->where('username', 'like', '%' . $request->user . '%');
            }
            $usersum = (clone $users)->get();
            $sum = 0;
            $count = 0;
            foreach ($usersum as $u)
            {
                $sum = $sum + $u->deal_balance;
                
            }

            $total = [
                'count' => $users->count(),
                'deal' => $sum
            ];

            $users = $users->paginate(20);
            return view('backend.argon.share.index', compact('users','total'));
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

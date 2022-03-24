<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend\Argon
{
    class DashboardController extends \VanguardLTE\Http\Controllers\Controller
    {
        private $users = null;
        private $activities = null;
        public function __construct(\VanguardLTE\Repositories\User\UserRepository $users, \VanguardLTE\Repositories\Activity\ActivityRepository $activities)
        {
            $this->middleware('auth');
            $this->middleware('permission:access.admin.panel');
            $this->users = $users;
            $this->activities = $activities;
        }
        public function index()
        {
            $ids = auth()->user()->hierarchyUsersOnly();
            $availableShops = auth()->user()->availableShops();
            $start_date = date("Y-m-d", strtotime("-30 days"));
            $end_date = date("Y-m-d");
            $todayprofit = 0;
            $todaybetwin = 0;
            $online = 0;
            $monthsummary = null;
            if (count($availableShops) > 0){
                $monthsummary = \VanguardLTE\DailySummary::where('user_id', auth()->user()->id)->where('date', '>=', $start_date)->where('date', '<=', $end_date)->get();
                $todaysummary = \VanguardLTE\DailySummary::where('user_id', auth()->user()->id)->where('date', $end_date)->first();
                if ($todaysummary){
                    $todaybetwin = $todaysummary->totalbet - $todaysummary->totalwin;
                    $todayprofit = $todaysummary->totalin - $todaysummary->totalout;
                }
            }
            $stats = [
                'todaydw' => $todayprofit,
                'todaybetwin' => $todaybetwin,
                
            ];
            return view('backend.argon.dashboard.admin', compact('monthsummary', 'stats'));
        }
    }

}

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
            $this_date = date("Y-m-1");
            $todayprofit = 0;
            $todaybetwin = 0;
            $mtbet = 0;
            $mtwin = 0;
            $mtin = 0;
            $mtout = 0;
            $monthrtp = 0;
            $monthpayout = 0;
            $monthsummary = null;
            $thismonthsummary = null;
            $monthcategory = null;
            if (count($availableShops) > 0){
                $monthsummary = \VanguardLTE\DailySummary::where('user_id', auth()->user()->id)->where('date', '>=', $start_date)->where('date', '<=', $end_date)->get();
                $thismonthsummary = \VanguardLTE\DailySummary::where('user_id', auth()->user()->id)->where('date', '>=', $this_date)->get();
                $monthcategory = \VanguardLTE\CategorySummary::where('user_id', auth()->user()->id)->where('date', '>=', $start_date)->where('date', '<=', $end_date)->groupby('category_id')->selectRaw('category_id, sum(totalbet) as bet, sum(totalwin) as win')->orderby('bet','desc')->limit(5)->get();
                $todaysummary = \VanguardLTE\DailySummary::where('user_id', auth()->user()->id)->where('date', $end_date)->first();
                if ($todaysummary){
                    $todaybetwin = $todaysummary->totalbet - $todaysummary->totalwin;
                    $todayprofit = $todaysummary->totalin - $todaysummary->totalout;
                }
                if ($thismonthsummary->count() > 0)
                {
                    $mtbet = $thismonthsummary->sum('totalbet');
                    $mtwin = $thismonthsummary->sum('totalwin');
                    $mtin = $thismonthsummary->sum('totalin');
                    $mtout = $thismonthsummary->sum('totalout');
                    $monthprofit = $mtbet - $mtwin ;
                    if ($mtbet > 0){
                        $monthrtp = $mtwin / $mtbet  ; 
                    }
                    $monthbetwin = $mtin - $mtout;
                    if ($mtin > 0){
                        $monthpayout = $mtout / $mtin;
                    }
                }
            }
            $stats = [
                'todaydw' => $todayprofit,
                'todaybetwin' => $todaybetwin,

                'monthbet' => $mtbet,
                'monthwin' => $mtwin,
                'monthrtp' => $monthrtp * 100,
                'monthin' => $mtin,
                'monthout' => $mtout,
                'monthpayout' => $monthpayout * 100,
            ];
            return view('backend.argon.dashboard.admin', compact('monthsummary', 'monthcategory', 'todaysummary','stats'));
        }
    }

}

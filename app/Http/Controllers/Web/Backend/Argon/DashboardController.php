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
            $user = auth()->user();
            $ids = auth()->user()->hierarchyUsersOnly();
            $availableShops = auth()->user()->availableShops();
            $start_date = date("Y-m-d", strtotime("-31 days"));
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
            $monthInout = null;
            $thismonthsummary = null;
            $monthcategory = null;
            $todayInOut = null;

            if (count($availableShops) > 0){
                if (auth()->user()->isInOutPartner())
                {
                    $monthsummary = \VanguardLTE\CategorySummary::selectRaw('sum(totalbet) as totalbet, sum(totalwin) as totalwin, date')->where('user_id', auth()->user()->id)->where('date', '>=', $start_date)->where('date', '<=', $end_date)->groupby('user_id','date')->get();
                }
                else
                {
                    $monthsummary = \VanguardLTE\CategorySummary::selectRaw('sum(totaldealbet) as totalbet, sum(totaldealwin) as totalwin, date')->where('user_id', auth()->user()->id)->where('date', '>=', $start_date)->where('date', '<=', $end_date)->groupby('user_id','date')->get();
                }
                $monthInout = \VanguardLTE\DailySummary::where('daily_summary.user_id', auth()->user()->id)->where('daily_summary.date', '>=', $start_date)->where('daily_summary.date', '<=', $end_date)->get();
                $thismonthsummary = \VanguardLTE\DailySummary::where('user_id', auth()->user()->id)->where('date', '>=', $this_date)->get();

                $totalQuery = 'SELECT SUM(totalbet) AS totalbet, SUM(totalwin) AS totalwin, category_id, if (w_categories.parent>0, w_categories.parent, w_categories.id) AS parent, w_categories.title as title FROM w_category_summary JOIN w_categories ON w_categories.id=w_category_summary.category_id WHERE ';
                $totalQuery = $totalQuery . "w_category_summary.date>=\"$start_date\" AND w_category_summary.date<=\"$end_date\" ";
                $totalQuery = $totalQuery . "AND w_category_summary.user_id=$user->id ";
                $totalQuery = $totalQuery . "GROUP BY w_category_summary.category_id ORDER BY totalbet desc limit 5";
                if (!auth()->user()->hasRole('admin'))
                {
                    $totalQuery = "SELECT SUM(a.totalbet) AS totalbet, SUM(a.totalwin) AS totalwin,  a.parent AS category_id, b.trans_title as title FROM ($totalQuery) a JOIN w_categories_trans_kr as b on b.category_id=a.parent  GROUP BY a.parent ORDER BY totalbet desc";
                }
                else
                {
                    $totalQuery = "SELECT a.totalbet AS totalbet, a.totalwin AS totalwin,  a.parent AS category_id, b.trans_title as title FROM ($totalQuery) a JOIN w_categories_trans_kr as b on b.category_id=a.parent  ORDER BY totalbet desc";
                }
                $monthcategory = \DB::select($totalQuery);

                // $monthcategory = \VanguardLTE\CategorySummary::where('user_id', auth()->user()->id)->where('date', '>=', $start_date)->where('date', '<=', $end_date)->groupby('category_id')->selectRaw('category_id, sum(totalbet) as bet, sum(totalwin) as win')->orderby('bet','desc')->limit(5)->get();
                $todaysummary = \VanguardLTE\DailySummary::where('user_id', auth()->user()->id)->where('date', $end_date)->first();
                if ($todaysummary){
                    $todayInOut = $todaysummary->calcInOut();
                    $todayprofit = $todayInOut['totalin'] - $todayInOut['totalout'];
                    $betwin = $todaysummary->betwin();
                    if (auth()->user()->isInOutPartner())
                    {
                        $todaysummary->totalbet = $betwin['total']['totalbet'];
                        $todaysummary->totalwin = $betwin['total']['totalwin'];
                    }
                    else
                    {
                        $todaysummary->totalbet = $betwin['total']['totaldealbet'];
                        $todaysummary->totalwin = $betwin['total']['totaldealwin'];
                    }
                    $todaybetwin = $todaysummary->totalbet - $todaysummary->totalwin;

                }
                if ($thismonthsummary->count() > 0)
                {
                    foreach ($thismonthsummary as $m)
                    {
                        $betwin = $m->betwin();
                        $mtbet = $mtbet + $betwin['total']['totalbet'];
                        $mtwin = $mtwin + $betwin['total']['totalwin'];
                    }
                    $mtin = $thismonthsummary->sum('totalin');
                    $mtout = $thismonthsummary->sum('totalout');
                    if ($todaysummary)
                    {
                        $mtin = $mtin - $todaysummary->totalin + $todayInOut['totalin'];
                        $mtout = $mtout - $todaysummary->totalout + $todayInOut['totalout'];
                    }
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
                'todayin' => $todayInOut?$todayInOut['totalin']:0,
                'todayout' => $todayInOut?$todayInOut['totalout']:0,

                'monthbet' => $mtbet,
                'monthwin' => $mtwin,
                'monthrtp' => $monthrtp * 100,
                'monthin' => $mtin,
                'monthout' => $mtout,
                'monthpayout' => $monthpayout * 100,
            ];
            return view('backend.argon.dashboard.admin', compact('monthsummary', 'monthInout', 'monthcategory', 'todaysummary','stats'));
        }
    }

}

<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend\Argon
{
    class ReportController extends \VanguardLTE\Http\Controllers\Controller
    {
        private $users = null;
        private $max_users = 10000;
        public function __construct(\VanguardLTE\Repositories\User\UserRepository $users)
        {
            $this->middleware('auth');
            $this->middleware('permission:access.admin.panel');
            $this->users = $users;
        }
        public function report_childdaily(\Illuminate\Http\Request $request, $argon, $daily_type='')
        {
            $parent_id = $request->id;
            $param = explode('~', $parent_id);
            if(!is_array($param) || count($param) < 2){
                return redirect()->back()->withErrors('찾을수 없습니다.');
            }
            $user_id = $param[0];
            $date = $param[1];
            $user = \VanguardLTE\User::where('id', $user_id)->get()->first();
            $users = $user->childPartners();

            $summary = \VanguardLTE\DailySummary::where('date', '=', $date)->whereIn('user_id', $users);
            
            $summary = $summary->orderBy('user_id', 'ASC')->orderBy('date', 'ASC');
            $summary = $summary->get();
            if(isset($daily_type) && $daily_type == 'dw'){
                return view('backend.argon.report.partials.childs_dailydw', compact('summary', 'parent_id'));
            }else{
                return view('backend.argon.report.partials.childs_daily', compact('summary', 'parent_id'));
            }
        }
        public function report_daily(\Illuminate\Http\Request $request)
        {
            $users = [auth()->user()->id];
            $user = null;
            if ($request->partner != '')
            {
                $availablePartners = auth()->user()->hierarchyPartners();
                $partners = \VanguardLTE\User::where('username', 'like', '%' . $request->partner . '%')->whereIn('id', $availablePartners)->pluck('id')->toArray();
                if (count($partners) == 0)
                {
                    $partners = [-1];
                }
                $users = $partners;
            }

            if ($request->role != '')
            {
                if ($request->partner != '')
                {
                    $partners = \VanguardLTE\User::where('role_id',   $request->role)->whereIn('id', $users)->pluck('id')->toArray();
                    if (count($partners) == 0)
                    {
                        return redirect()->back()->withErrors('에이전트를 찾을수 없습니다.');
                    }
                    $users = $partners;
                }
                else
                {
                    $availablePartners = auth()->user()->hierarchyPartners();
                    $partners = \VanguardLTE\User::where('role_id',   $request->role)->whereIn('id', $availablePartners)->pluck('id')->toArray();
                    if (count($partners) == 0)
                    {
                        return redirect()->back()->withErrors('에이전트를 찾을수 없습니다.');
                    }
                    $users = $partners;
                }
            }
            
            $start_date = date("Y-m-d");
            $end_date = date("Y-m-d");
            if ($request->dates != '')
            {
                // $dates = explode(' - ', $request->dates);
                $start_date = preg_replace('/T/',' ', $request->dates[0]);
                $end_date = preg_replace('/T/',' ', $request->dates[1]);            
            }

            if (!auth()->user()->hasRole('admin'))
            {
                $d = strtotime($start_date);
                if ($d < strtotime("-30 days"))
                {
                    $start_date = date("Y-m-d",strtotime("-30 days"));
                }
            }

            $summary = \VanguardLTE\DailySummary::where('date', '>=', $start_date)->where('date', '<=', $end_date)->whereIn('user_id', $users);
            $total = [
                'totalbet' => $summary->sum('totalbet'),
                'totalwin' => $summary->sum('totalwin'),
                'ggr' => $summary->sum('totalbet') - $summary->sum('totalwin'),
            ];
            $summary = $summary->orderBy('user_id', 'ASC')->orderBy('date', 'desc');
            $summary = $summary->paginate(31);
            $type = 'daily';
            return view('backend.argon.report.daily', compact('summary','total','type'));
        }
        public function report_dailydw(\Illuminate\Http\Request $request)
        {
            $users = [auth()->user()->id];
            $user = null;
            if ($request->partner != '')
            {
                $availablePartners = auth()->user()->hierarchyPartners();
                $partners = \VanguardLTE\User::where('username', 'like', '%' . $request->partner . '%')->whereIn('id', $availablePartners)->pluck('id')->toArray();
                if (count($partners) == 0)
                {
                    return redirect()->back()->withErrors('에이전트를 찾을수 없습니다.');
                }
                $users = $partners;
            }

            if ($request->role != '')
            {
                if ($request->partner != '')
                {
                    $partners = \VanguardLTE\User::where('role_id',   $request->role)->whereIn('id', $users)->pluck('id')->toArray();
                    if (count($partners) == 0)
                    {
                        return redirect()->back()->withErrors('에이전트를 찾을수 없습니다.');
                    }
                    $users = $partners;
                }
                else
                {
                    $availablePartners = auth()->user()->hierarchyPartners();
                    $partners = \VanguardLTE\User::where('role_id',   $request->role)->whereIn('id', $availablePartners)->pluck('id')->toArray();
                    if (count($partners) == 0)
                    {
                        return redirect()->back()->withErrors('에이전트를 찾을수 없습니다.');
                    }
                    $users = $partners;
                }
            }
            
            $start_date = date("Y-m-d");
            $end_date = date("Y-m-d");
            if ($request->dates != '')
            {
                // $dates = explode(' - ', $request->dates);
                $start_date = preg_replace('/T/',' ', $request->dates[0]);
                $end_date = preg_replace('/T/',' ', $request->dates[1]);            
            }

            if (!auth()->user()->hasRole('admin'))
            {
                $d = strtotime($start_date);
                if ($d < strtotime("-30 days"))
                {
                    $start_date = date("Y-m-d",strtotime("-30 days"));
                }
            }

            $summary = \VanguardLTE\DailySummary::where('date', '>=', $start_date)->where('date', '<=', $end_date)->whereIn('user_id', $users);
            $total = [
                'totalin' => $summary->sum('totalin'),
                'totalout' => $summary->sum('totalout'),
                'moneyin' => $summary->sum('moneyin'),
                'moneyout' => $summary->sum('moneyout'),
            ];
            $summary = $summary->orderBy('user_id', 'ASC')->orderBy('date', 'desc');
            $summary = $summary->paginate(31);
            return view('backend.argon.report.dailydw', compact('summary','total'));
        }
        public function report_childmonthly(\Illuminate\Http\Request $request)
        {
            $parent_id = $request->id;
            $param = explode('~', $parent_id);
            if(!is_array($param) || count($param) < 2){
                return redirect()->back()->withErrors('찾을수 없습니다.');
            }
            $user_id = $param[0];
            $date = $param[1];
            $user = \VanguardLTE\User::where('id', $user_id)->get()->first();
            $users = $user->childPartners();

            $summary = \VanguardLTE\DailySummary::where('date', '=', $date)->where('type','monthly')->whereIn('user_id', $users);
            $summary = $summary->orderBy('user_id', 'ASC')->orderBy('date', 'ASC');
            $summary = $summary->get();
            return view('backend.argon.report.partials.childs_daily', compact('summary', 'parent_id'));
        }
        public function report_monthly(\Illuminate\Http\Request $request)
        {
            $user_id = $request->input('parent');
            $users = [];
            $shops = [];
            $user = null;
            if ($request->search != '')
            {
                $availablePartners = auth()->user()->hierarchyPartners();
                $partner = \VanguardLTE\User::where('username', 'like', '%' . $request->search . '%')->whereIn('id', $availablePartners)->first();
                if (!$partner)
                {
                    $partners = [-1];
                }
                $user_id = $partner->id;
                $dates = $request->dates;
                $users = [$user_id];
            }
            else
            {
                if($user_id == null || $user_id == 0)
                {
                    $user_id = auth()->user()->id;
                    $users = [$user_id];
                    $request->session()->put('dates', null);
                    $dates = $request->dates;
                }
                else {
                    if (auth()->user()->id!=$user_id && !in_array($user_id, auth()->user()->hierarchyPartners()))
                    {
                        return redirect()->back()->withErrors(['비정상적인 접근입니다.']);
                    }
                    $user = \VanguardLTE\User::where('id', $user_id)->get()->first();
                    $users = $user->childPartners();
                    $dates = ($request->session()->exists('dates') ? $request->session()->get('dates') : '');
                }
            }
            
            $start_date = date("Y-m-01",strtotime("-1 months"));
            $end_date = date("Y-m-01");
            if($dates != null && $dates != ''){
                $start_date = $dates[0] . '-01';
                $end_date = $dates[1] . '-01';
                $request->session()->put('dates', $dates);
            }

            $summary = \VanguardLTE\DailySummary::where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('type','monthly')->whereIn('user_id', $users);
            $summary = $summary->orderBy('user_id', 'ASC')->orderBy('date', 'ASC');
            $summary = $summary->paginate(31);
            $type = 'monthly';
        
            return view('backend.argon.report.daily', compact('start_date', 'end_date', 'user', 'summary','type'));
        }
        public function report_game(\Illuminate\Http\Request $request)
        {
            $statistics = \VanguardLTE\CategorySummary::orderBy('category_summary.date', 'DESC');
        
            $totalQuery = 'SELECT SUM(totalbet) AS totalbet, SUM(totalwin) AS totalwin, SUM(total_deal-total_mileage) as totaldeal, category_id, if (w_categories.parent>0, w_categories.parent, w_categories.id) AS parent, w_categories.title as title FROM w_category_summary JOIN w_categories ON w_categories.id=w_category_summary.category_id WHERE ';

            $dateQuery = 'SELECT totalbet, totalwin, (total_deal-total_mileage) as totaldeal, category_id, date, if (w_categories.parent>0, w_categories.parent, w_categories.id) AS parent, w_categories.title AS title FROM w_category_summary JOIN w_categories ON w_categories.id=w_category_summary.category_id WHERE ';

            $start_date = date("Y-m-1");
            $end_date = date("Y-m-d");

            if ($request->dates != '')
            {
                $start_date = preg_replace('/T/',' ', $request->dates[0]);
                $end_date = preg_replace('/T/',' ', $request->dates[1]);            
            }
            $statistics = $statistics->where('category_summary.date', '>=', $start_date);
            $statistics = $statistics->where('category_summary.date', '<=', $end_date);
            $totalQuery = $totalQuery . "w_category_summary.date>=\"$start_date\" AND w_category_summary.date<=\"$end_date\" ";
            $dateQuery = $dateQuery . "w_category_summary.date>=\"$start_date\" AND w_category_summary.date<=\"$end_date\" ";

            if ($request->partner != '')
            {
                $availablePartners = auth()->user()->hierarchyPartners();
                if ($request->role != '')
                {
                    $user = \VanguardLTE\User::where('username', 'like', '%'. $request->partner . '%')->whereIn('id', $availablePartners)->where('role_id',$request->role)->first();
                }
                else
                {
                    $user = \VanguardLTE\User::where('username', 'like', '%'. $request->partner . '%')->whereIn('id', $availablePartners)->first();
                }
                if (!$user || !in_array($user->id, $availablePartners))
                {
                    return redirect()->back()->withErrors(['에이전트를 찾을수 없습니다']);
                }
            }
            else
            {
                $user = auth()->user();
            }

            $statistics = $statistics->where('user_id', $user->id);
            $totalQuery = $totalQuery . "AND w_category_summary.user_id=$user->id ";
            $dateQuery = $dateQuery . "AND w_category_summary.user_id=$user->id ";

            if ($request->game != '')
            {
                $category = \VanguardLTE\Category::where('title', $request->game);
                if (!auth()->user()->hasRole('admin'))
                {
                    $category = $category->where('parent', 0);
                }
                $category = $category->first();
                if (!$category)
                {
                    return redirect()->back()->withErrors(['게임사를 찾을수 없습니다']);
                }
                $statistics = $statistics->where('category_id', $category->id);
                $totalQuery = $totalQuery . "AND w_category_summary.category_id=$category->id ";
                $dateQuery = $dateQuery . "AND w_category_summary.category_id=$category->id ";
            }
            
            $totalQuery = $totalQuery . "GROUP BY w_category_summary.category_id ORDER BY totalbet desc";
            $dateQuery = $dateQuery . "ORDER BY w_category_summary.date desc";

            if (!auth()->user()->hasRole('admin'))
            {
                $totalQuery = "SELECT SUM(a.totalbet) AS totalbet, SUM(a.totalwin) AS totalwin, SUM(a.totaldeal) as totaldeal, a.parent AS category_id, b.title FROM ($totalQuery) a JOIN w_categories as b on b.id=a.parent GROUP BY a.parent ORDER BY totalbet desc";
                $dateQuery = "SELECT SUM(a.totalbet) AS totalbet, SUM(a.totalwin) AS totalwin, SUM(a.totaldeal) as totaldeal, date, a.parent AS category_id, b.title FROM ($dateQuery) a JOIN w_categories as b on b.id=a.parent GROUP BY a.parent, a.date ORDER BY a.date desc";
            }
        
            $sumQuery = "SELECT SUM(c.totalbet) AS totalbet, SUM(c.totalwin) AS totalwin, SUM(c.totaldeal) as totaldeal FROM ($totalQuery) c";

            $totalstatics = \DB::select($totalQuery);
            $totalsummary = \DB::select($sumQuery);
            $statistics = \DB::select($dateQuery);

            $categories = [];
            if ($statistics)
            {
                $last_date = null;
                $date_cat = null;
                foreach ($statistics as $cat)
                {
                    if ($cat->date != $last_date)
                    {
                        if ($date_cat)
                        {
                            //sort games per totalbet
                            usort($date_cat['cat'], function($element1, $element2)
                            {
                                return $element2['totalbet'] - $element1['totalbet'];
                            });
                            $categories[] = $date_cat;
                        }
                        $last_date = $cat->date;
                        $date_cat = [
                            'date' => $cat->date,
                        ];
                    }
                    $info['totalbet'] = $cat->totalbet;
                    $info['totalwin'] = $cat->totalwin;
                    $info['totaldeal'] = $cat->totaldeal;
                    $info['title'] = $cat->title;
                    $date_cat['cat'][] = $info;
                }
                if ($date_cat)
                {
                    //sort games per totalbet
                    usort($date_cat['cat'], function($element1, $element2)
                    {
                        return $element2['totalbet'] - $element1['totalbet'];
                    });
                    $categories[] = $date_cat;
                }
            }

            //merge evolution&gac per config
            $master = auth()->user();
            while ($master !=null && !$master->isInoutPartner())
            {
                $master = $master->referral;
            }
            $gacmerge = \VanguardLTE\Http\Controllers\Web\GameProviders\GACController::mergeGAC_EVO($master->id);
            
            return view('backend.argon.report.game', compact('totalsummary', 'categories', 'totalstatics','user','gacmerge'));


        }
    }
}

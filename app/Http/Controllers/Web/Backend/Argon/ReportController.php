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

            $summary = \VanguardLTE\DailySummary::where('date', '=', $date)->where('type','daily')->whereIn('user_id', $users);
            
            $summary = $summary->orderBy('user_id', 'ASC')->orderBy('date', 'ASC');
            $summary = $summary->get();
            if(isset($daily_type) && $daily_type == 'dw'){
                return view('backend.argon.report.partials.childs_dailydw', compact('summary', 'parent_id'));
            }else{
                return view('backend.argon.report.partials.childs_daily', compact('summary', 'parent_id'));
            }
        }
        public function report_daily(\Illuminate\Http\Request $request, $argon, $daily_type = '')
        {
            $user_id = $request->input('parent');
            $users = [];
            $shops = [];
            $user = null;
            if ($request->partner != '')
            {
                $availablePartners = auth()->user()->hierarchyPartners();
                $partner = \VanguardLTE\User::where('username', 'like', '%' . $request->partner . '%')->whereIn('id', $availablePartners)->first();
                if (!$partner)
                {
                    return redirect()->back()->withErrors('파트너를 찾을수 없습니다.');
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
            
            $start_date = date("Y-m-d",strtotime("-1 days"));
            $end_date = date("Y-m-d");
            if($dates != null && count($dates) == 2){
                $start_date = $dates[0];
                $end_date = $dates[1];
                $request->session()->put('dates', $dates);
            }

            if (!auth()->user()->hasRole('admin'))
            {
                $d = strtotime($start_date);
                if ($d < strtotime("-30 days"))
                {
                    $start_date = date("Y-m-d",strtotime("-30 days"));
                }
            }

            $summary = \VanguardLTE\DailySummary::where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('type','daily')->whereIn('user_id', $users);
            
            $summary = $summary->orderBy('user_id', 'ASC')->orderBy('date', 'ASC');
            $summary = $summary->paginate(31);
            if(isset($daily_type) && $daily_type == 'dw'){
                return view('backend.argon.report.dailydw', compact('start_date', 'end_date', 'user', 'summary'));
            }else{
                $type = 'daily';
                return view('backend.argon.report.daily', compact('start_date', 'end_date', 'user', 'summary', 'type'));
            }
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
                    return redirect()->back()->withErrors('파트너를 찾을수 없습니다.');
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
            $dates = $request->dates;
            $start_date = date("Y-m-d 0:0:0");
            $end_date = date("Y-m-d H:i:s");
            if($dates != null && $dates != ''){
                $start_date = $dates[0] . " 00:00:00";
                $end_date = $dates[1] . " 23:59:59";
            }
            $user_id = auth()->user()->id;
            if( $request->partner != '' ) 
            {
                if ($request->type == 'shop')
                {
                    $availableShops = auth()->user()->availableShops();
                    $shop = \VanguardLTE\Shop::where('shops.name', 'like', '%' . $request->partner . '%')->whereIn('id', $availableShops)->first();
                    if (!$shop)
                    {
                        return redirect()->back()->withErrors('매장을 찾을수 없습니다.');
                    }
                    $user_id = $shop->getUsersByRole('manager')->first()->id;
                }
                else if ($request->type == 'partner')
                {
                    $availablePartners = auth()->user()->hierarchyPartners();
                    $partner = \VanguardLTE\User::where('username', 'like', '%' . $request->partner . '%')->whereIn('id', $availablePartners)->first();
                    if (!$partner)
                    {
                        return redirect()->back()->withErrors('파트너를 찾을수 없습니다.');
                    }
                    $user_id = $partner->id;
                }
            }
            $bshowGame = false;
            if ($request->cat != '' && $request->date != '' && $request->type != '')
            {
                $bshowGame = true;
                $adj_games = \VanguardLTE\GameSummary::where(['date' => $request->date, 'type' => $request->type, 'category_id' => $request->cat, 'user_id'=> $user_id])->orderby('totalbet')->get();
            }
            else
            {
                $adj_games = \VanguardLTE\CategorySummary::where('date', '>=', $start_date)->where('date', '<=', $end_date)->whereIn('type',['daily','today'])->where('user_id', $user_id)->orderby('date')->get();
            }
            $categories = null;
            $totalcategory = [
                'date' => '',
                'title' => '합계',
                'totalbet' => $adj_games->sum('totalbet'),
                'totalwin' => $adj_games->sum('totalwin'),
                'totalcount' => $adj_games->sum('totalcount'),
                'total_deal' => $adj_games->sum('total_deal'),
                'total_mileage' => $adj_games->sum('total_mileage'),
            ];
            if ($adj_games)
            {
                $last_date = null;
                $date_cat = null;
                foreach ($adj_games as $cat)
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
                    $info = $cat->toArray();
                    if ($bshowGame)
                    {
                        $info['title'] = $cat->name;
                        $info['name'] = $cat->name;
                    }
                    else
                    {
                        $info['title'] = $cat->category->trans->trans_title;
                        $info['name'] = $cat->category->title;
                    }
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

            if (!auth()->user()->hasRole('admin'))
            {
                //merge pragmatic and pragmatic play games if not admin
                //merge habanero and habanero play games if not admin
                //merge cq9 and cq9 play games if not admin
                if ($categories){
                    foreach ($categories as $i => $cat)
                    {
                        $pp_adj = null; //provider's game
                        $pragmatic_adj = null; // owner's game
                        $pp_index = 0;
                        $pragmatic_index = 0;

                        $hbn_adj = null; // provider's game
                        $hbn_play_adj = null; // owner's game
                        $hbn_index = 0;
                        $hbn_play_index = 0;

                        $cq9_adj = null; // provider's game
                        $cq9_play_adj = null; // owner's game
                        $cq9_index = 0;
                        $cq9_play_index = 0;
                        foreach ($cat['cat'] as $index => $game)
                        {
                            if ($game['name'] == 'Pragmatic Play')
                            {
                                $pp_adj = $game;
                                $pp_index = $index;
                            }
                            if ($game['name'] == 'Pragmatic')
                            {
                                $pragmatic_adj = $game;
                                $pragmatic_index = $index;
                            }
                            if ($game['name'] == 'Habanero Play')
                            {
                                $hbn_play_adj = $game;
                                $hbn_play_index = $index;
                            }
                            if ($game['name'] == 'Habanero')
                            {
                                $hbn_adj = $game;
                                $hbn_index = $index;
                            }

                            if ($game['name'] == 'CQ9 Play')
                            {
                                $cq9_play_adj = $game;
                                $cq9_play_index = $index;
                            }
                            if ($game['name'] == 'CQ9')
                            {
                                $cq9_adj = $game;
                                $cq9_index = $index;
                            }
                        }
                        if ($pragmatic_adj)
                        {
                            if ($pp_adj)
                            {
                                $pp_adj['totalwin'] = $pp_adj['totalwin'] + $pragmatic_adj['totalwin'];
                                $pp_adj['totalbet'] = $pp_adj['totalbet'] + $pragmatic_adj['totalbet'];
                                $pp_adj['totalcount'] = $pp_adj['totalcount'] + $pragmatic_adj['totalcount'];
                                $pp_adj['total_deal'] = $pp_adj['total_deal'] + $pragmatic_adj['total_deal'];
                                $pp_adj['total_mileage'] = $pp_adj['total_mileage'] + $pragmatic_adj['total_mileage'];
                                $cat['cat'][$pp_index] = $pp_adj;
                                unset($cat['cat'][$pragmatic_index]);
                            }
                            else
                            {
                                $pragmatic_adj['title'] = '프라그메틱 플레이';
                                $cat['cat'][$pragmatic_index] = $pragmatic_adj;
                            }
                            $categories[$i] = $cat;
                        }
                        if ($hbn_play_adj)
                        {
                            if ($hbn_adj)
                            {
                                $hbn_adj['totalwin'] = $hbn_adj['totalwin'] + $hbn_play_adj['totalwin'];
                                $hbn_adj['totalbet'] = $hbn_adj['totalbet'] + $hbn_play_adj['totalbet'];
                                $hbn_adj['totalcount'] = $hbn_adj['totalcount'] + $hbn_play_adj['totalcount'];
                                $hbn_adj['total_deal'] = $hbn_adj['total_deal'] + $hbn_play_adj['total_deal'];
                                $hbn_adj['total_mileage'] = $hbn_adj['total_mileage'] + $hbn_play_adj['total_mileage'];
                                $cat['cat'][$hbn_index] = $hbn_adj;
                                unset($cat['cat'][$hbn_play_index]);
                            }
                            else
                            {
                                $hbn_play_adj['title'] = '하바네로';
                                $cat['cat'][$hbn_play_index] = $hbn_play_adj;
                            }
                            $categories[$i] = $cat;
                        }

                        if ($cq9_play_adj)
                        {
                            if ($cq9_adj)
                            {
                                $cq9_adj['totalwin'] = $cq9_adj['totalwin'] + $cq9_play_adj['totalwin'];
                                $cq9_adj['totalbet'] = $cq9_adj['totalbet'] + $cq9_play_adj['totalbet'];
                                $cq9_adj['totalcount'] = $cq9_adj['totalcount'] + $cq9_play_adj['totalcount'];
                                $cq9_adj['total_deal'] = $cq9_adj['total_deal'] + $cq9_play_adj['total_deal'];
                                $cq9_adj['total_mileage'] = $cq9_adj['total_mileage'] + $cq9_play_adj['total_mileage'];
                                $cat['cat'][$cq9_index] = $cq9_adj;
                                unset($cat['cat'][$cq9_play_index]);
                            }
                            else
                            {
                                $cq9_play_adj['title'] = '씨큐9';
                                $cat['cat'][$cq9_play_index] = $cq9_play_adj;
                            }
                            $categories[$i] = $cat;
                        }
                    }
                }

            }

            $updated_at = '00:00:00';
            if (count($adj_games) > 0)
            {
                $updated_at = $adj_games->last()->updated_at;
            }

            return view('backend.argon.report.game', compact('categories', 'totalcategory', 'updated_at','start_date', 'end_date'));


        }
    }
}

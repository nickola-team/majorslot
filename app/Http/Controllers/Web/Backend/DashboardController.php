<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend
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
/*            $checked = new \VanguardLTE\Lib\LicenseDK();
            $license_notifications_array = $checked->aplVerifyLicenseDK(null, 0);
            if( $license_notifications_array['notification_case'] != 'notification_license_ok' ) 
            {
                return redirect()->route('frontend.page.error_license');
            }
            if( !$this->security() ) 
            {
                return redirect()->route('frontend.page.error_license');
            }*/
            if( config('app.admurl') != 'slot' && !\Auth::user()->hasRole('admin')) 
            {
                return redirect()->route(config('app.admurl').'.user.list');
            }
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

                $validTimestamp = \Carbon\Carbon::now()->subMinutes(config('session.lifetime'))->timestamp;
                if (count($ids) > 0)
                {
                    $query = 'SELECT count(*) as online FROM w_sessions WHERE user_id in (' . implode(',', $ids) . ') AND last_activity>=' . $validTimestamp;
                    $qresult = \DB::select($query);
                    $online = $qresult[0]->online;
                }
            }
            $stats = [
                'total' => $this->users->count($ids), 
                'online' => $online, 
                'todayprofit' => $todayprofit,
                'todaybetwin' => $todaybetwin,
                
            ];
            $user = \Auth::user();
            $latestRegistrations = $this->users->latest(5, $ids);
            $shops = \VanguardLTE\Shop::orderBy('id', 'desc')->whereIn('id', $availableShops)->take(5)->get();
            $statistics = \VanguardLTE\Transaction::whereIn('user_id', $ids)->orderBy('id', 'desc')->take(5)->get();
            $gamestat = \VanguardLTE\StatGame::whereIn('user_id', $ids)->orderBy('date_time', 'DESC')->take(5)->get();
            $bank_stat = \VanguardLTE\BankStat::whereIn('user_id', $ids)->orderBy('created_at', 'DESC')->take(5)->get();
            $shops_stat = \VanguardLTE\ShopStat::whereIn('user_id', $ids)->orderBy('date_time', 'DESC')->take(5)->get();
            return view('backend.Default.dashboard.admin', compact('stats', 'latestRegistrations', 'user', 'statistics', 'gamestat', 'shops',  'bank_stat', 'shops_stat', 'monthsummary'));
        }
        public function live_stat(\Illuminate\Http\Request $request)
        {
            $filter = ($request->session()->exists('type') ? $request->session()->get('type') : '');
            if( isset($request->type) && $request->session()->get('type') != $request->type ) 
            {
                $filter = $request->type;
                $request->session()->put('type', $request->type);
            }
            $statistics = [];
            $availableShops = auth()->user()->availableShops();
            $users = auth()->user()->hierarchyUsers();
            $transactions = \VanguardLTE\Transaction::select('transactions.*')->orderBy('transactions.created_at', 'DESC');
            $transactions = $transactions->whereIn('transactions.user_id', $users)->get();
            if( \Auth::user()->hasPermission('stats.pay') && (!$request->type || $request->type && $request->type == 'PayStat') ) 
            {
                foreach( $transactions as $transaction ) 
                {
                    $system = ($transaction->admin ? $transaction->admin->username : $transaction->system);
                    $sysdata = '<a href="' . route(config('app.admurl').'.statistics', ['system_str' => $system]) . '">' . $system . '</a>';
                    if( $transaction->value ) 
                    {
                        $sysdata .= $transaction->value;
                    }
                    if( $transaction->type == 'add' ) 
                    {
                        $sum = '<span class="text-green">' . number_format(abs($transaction->summ), 2) . '</span>';
                    }
                    else
                    {
                        $sum = '<span class="text-red">' . number_format(abs($transaction->summ), 2) . '</span>';
                    }
                    $usdata = '<a href="' . route(config('app.admurl').'.statistics', ['user' => $transaction->user->username]) . '">' . $transaction->user->username . '</a>';
                    $statistics[] = [
                        'type' => 'PayStat', 
                        'Name' => '', 
                        'Old' => '', 
                        'New' => '', 
                        'Game' => '', 
                        'User' => $usdata, 
                        'System' => $sysdata, 
                        'Sum' => $sum, 
                        'In' => ($transaction->type == 'add' ? number_format($transaction->summ,2) : ''), 
                        'Out' => ($transaction->type != 'add' ? number_format($transaction->summ,2) : ''), 
                        'Balance' => '', 
                        'Bet' => '', 
                        'Win' => '', 
                        'IN_GAME' => '', 
                        'IN_JPS' => '', 
                        'IN_JPG' => '', 
                        'Profit' => '', 
                        'Date' => strtotime($transaction->created_at)
                    ];
                }
            }
            $statgames = \VanguardLTE\StatGame::select('stat_game.*')->whereIn('stat_game.shop_id', $availableShops)->take(100)->orderBy('id', 'DESC');
            $statgames = $statgames->whereIn('stat_game.user_id', $users)->get();
            if( \Auth::user()->hasPermission('stats.game') && (!$request->type || $request->type && $request->type == 'StatGame') ) 
            {
                foreach( $statgames as $stat ) 
                {
                    $statistics[] = [
                        'type' => 'StatGame', 
                        'Name' => '', 
                        'Old' => '', 
                        'New' => '', 
                        'Game' => $stat->game, 
                        'User' => $stat->user->username, 
                        'System' => '', 
                        'Sum' => '', 
                        'In' => '', 
                        'Out' => '', 
                        'Balance' => number_format($stat->balance, 2), 
                        'Bet' => number_format($stat->bet, 2), 
                        'Win' => number_format($stat->win, 2), 
                        'IN_GAME' => number_format($stat->percent, 2), 
                        'IN_JPS' => number_format($stat->percent_jps, 2), 
                        'IN_JPG' => number_format($stat->percent_jpg, 2), 
                        'Profit' => number_format($stat->profit, 2), 
                        'Date' => strtotime($stat->date_time)
                    ];
                }
            }
            $bankstat = \VanguardLTE\BankStat::select('bank_stat.*')->whereIn('bank_stat.shop_id', $availableShops)->take(100)->orderBy('id', 'DESC');
            $bankstat = $bankstat->whereIn('bank_stat.user_id', $users)->get();
            if( \Auth::user()->hasPermission('stats.bank') && (!$request->type || $request->type && $request->type == 'BankStat') ) 
            {
                foreach( $bankstat as $stat ) 
                {
                    $statistics[] = [
                        'type' => 'BankStat', 
                        'Name' => $stat->name, 
                        'Old' => number_format($stat->old,2), 
                        'New' => number_format($stat->new,2), 
                        'Game' => '', 
                        'User' => $stat->user->username, 
                        'System' => '', 
                        'Sum' => number_format($stat->sum, 2), 
                        'In' => ($stat->type == 'add' ? number_format($stat->sum,2) : ''), 
                        'Out' => ($stat->type != 'add' ? number_format($stat->sum,2) : ''), 
                        'Balance' => '', 
                        'Bet' => '', 
                        'Win' => '', 
                        'IN_GAME' => '', 
                        'IN_JPS' => '', 
                        'IN_JPG' => '', 
                        'Profit' => '', 
                        'Date' => strtotime($stat->created_at)
                    ];
                }
            }
            $ShopStat = \VanguardLTE\ShopStat::select('shops_stat.*')->whereIn('shops_stat.shop_id', $availableShops)->take(100)->orderBy('id', 'DESC');
            $ShopStat = $ShopStat->get();
            if( \Auth::user()->hasPermission('stats.shop') && (!$request->type || $request->type && $request->type == 'ShopStat') ) 
            {
                foreach( $ShopStat as $stat ) 
                {
                    $statistics[] = [
                        'type' => 'ShopStat', 
                        'Name' => $stat->shop->name, 
                        'Old' => '', 
                        'New' => '', 
                        'Game' => '', 
                        'User' => $stat->shop->name, 
                        'System' => '', 
                        'Sum' => number_format($stat->sum, 2), 
                        'In' => ($stat->type == 'add' ? number_format($stat->sum,2) : ''), 
                        'Out' => ($stat->type == 'add' ? '' : number_format($stat->sum,2)), 
                        'Balance' => '', 
                        'Bet' => '', 
                        'Win' => '', 
                        'IN_GAME' => '', 
                        'IN_JPS' => '', 
                        'IN_JPG' => '', 
                        'Profit' => '', 
                        'Date' => strtotime($stat->date_time)
                    ];
                }
            }
            usort($statistics, function($element1, $element2)
            {
                return $element2['Date'] - $element1['Date'];
            });
            $statistics = array_slice($statistics, 0, 50);
            return view('backend.Default.stat.live_stat', compact( 'statistics', 'filter'));
        }
        public function start_shift(\Illuminate\Http\Request $request)
        {
            $users = \VanguardLTE\User::where([
                'shop_id' => \Auth::user()->shop_id, 
                'role_id' => 1
            ])->where('balance', '>', 0)->count();
            if( $users ) 
            {
                return redirect()->route(config('app.admurl').'.shift_stat')->withErrors([trans('users_with_balance', ['count' => $users])]);
            }
            $count = \VanguardLTE\OpenShift::where([
                'shop_id' => \Auth::user()->shop_id, 
                'end_date' => null
            ])->first();
            if( $count ) 
            {
                $summ = \VanguardLTE\User::where([
                    'shop_id' => \Auth::user()->shop_id, 
                    'role_id' => 1
                ])->sum('balance');
                $count->update([
                    'end_date' => \Carbon\Carbon::now(), 
                    'last_banks' => $count->banks(), 
                    'last_returns' => $count->returns(), 
                    'users' => $summ
                ]);
            }
            $shop = \VanguardLTE\Shop::find(\Auth::user()->shop_id);
            if( !$shop ) 
            {
                return redirect()->route(config('app.admurl').'.shift_stat')->withErrors([trans('app.wrong_shop')]);
            }
            if( $count ) 
            {
                \VanguardLTE\OpenShift::create([
                    'start_date' => \Carbon\Carbon::now(), 
                    'balance' => $shop->balance, 
                    'old_banks' => $count->banks(), 
                    'user_id' => \Auth::id(), 
                    'shop_id' => \Auth::user()->shop_id
                ]);
            }
            else
            {
                \VanguardLTE\OpenShift::create([
                    'start_date' => \Carbon\Carbon::now(), 
                    'balance' => $shop->balance, 
                    'user_id' => \Auth::id(), 
                    'shop_id' => \Auth::user()->shop_id
                ]);
            }
            return redirect()->route(config('app.admurl').'.shift_stat')->withSuccess(trans('app.open_shift_started'));
        }
        public function statistics(\Illuminate\Http\Request $request)
        {
            $users = auth()->user()->hierarchyUsersOnly();
            $statistics = \VanguardLTE\Transaction::select('transactions.*')->orderBy('transactions.created_at', 'DESC');
            $statistics = $statistics->whereIn('transactions.user_id', $users);
            //not show admin payer
            if (!auth()->user()->hasRole('admin'))
            {
                $admin = \VanguardLTE\User::where('role_id', 8)->pluck('id')->toArray();
                $statistics = $statistics->whereNotIn('transactions.payeer_id', $admin);
            }
            if( $request->system_str != '' ) 
            {
                $system = $request->system_str;
                $statistics = $statistics->where(function($q) use ($system)
                {
                    $q->where('transactions.system', 'like', '%' . $system . '%')->orWhere('transactions.value', 'like', '%' . str_replace('-', '', $system) . '%')->orWhereHas('admin', function($query) use ($system)
                    {
                        $query->where('username', 'like', '%' . $system . '%');
                    });
                });
            }
            if( $request->type != '' ) 
            {
                $statistics = $statistics->where('transactions.type', $request->type);
            }
            if( $request->sum_from != '' ) 
            {
                $statistics = $statistics->where('transactions.summ', '>=', $request->sum_from);
            }
            if( $request->sum_to != '' ) 
            {
                $statistics = $statistics->where('transactions.summ', '<=', $request->sum_to);
            }
            if( $request->user != '' ) 
            {
                $statistics = $statistics->join('users', 'users.id', '=', 'transactions.user_id');
                $statistics = $statistics->where('users.username', 'like', '%' . $request->user . '%');
            }
            if( $request->shopname != '' ) 
            {
                $statistics = $statistics->join('shops', 'shops.id', '=', 'transactions.shop_id');
                $statistics = $statistics->where('shops.name', 'like', '%' . $request->shopname . '%');
            }
            if( $request->payeername != '' ) 
            {
                if( !$request->user) 
                {
                    $statistics = $statistics->join('users', 'users.id', '=', 'transactions.payeer_id');
                }
                $statistics = $statistics->where('users.username', 'like', '%' . $request->payeername . '%');
            }
            $start_date = date("Y-m-d") . " 00:00:00";
            $end_date = date("Y-m-d H:i:s");
            if( $request->dates != '' ) 
            {
                $dates = explode(' - ', $request->dates);
                $start_date = $dates[0];
                $end_date = $dates[1];
            }

            $statistics = $statistics->where('transactions.created_at', '>=', $start_date);
            $statistics = $statistics->where('transactions.created_at', '<=', $end_date);

            
            if( $request->shifts != '' ) 
            {
                $shift = \VanguardLTE\OpenShift::find($request->shifts);
                if( $shift ) 
                {
                    $statistics = $statistics->where('transactions.created_at', '>=', $shift->start_date);
                    if( $shift->end_date ) 
                    {
                        $statistics = $statistics->where('transactions.created_at', '<=', $shift->end_date);
                    }
                }
            }
            $stats = $statistics->get();
            $statistics = $stats->sortByDesc('created_at')->paginate(20);
            $partner = 0;
            return view('backend.Default.stat.pay_stat', compact('stats', 'statistics','partner'));
        }

        public function statistics_partner(\Illuminate\Http\Request $request)
        {
            $users = auth()->user()->hierarchyPartners();
            array_push($users,auth()->user()->id);
            $statistics = \VanguardLTE\Transaction::select('transactions.*')->orderBy('transactions.created_at', 'DESC')->orderBy('transactions.balance', 'ASC');
            $statistics = $statistics->whereIn('transactions.user_id', $users);
            //not show admin payer
            if (!auth()->user()->hasRole('admin'))
            {
                $admin = \VanguardLTE\User::where('role_id', 8)->pluck('id')->toArray();
                $statistics = $statistics->whereNotIn('transactions.payeer_id', $admin);
            }
            if( $request->system_str != '' ) 
            {
                $system = $request->system_str;
                $statistics = $statistics->where(function($q) use ($system)
                {
                    $q->where('transactions.system', 'like', '%' . $system . '%')->orWhere('transactions.value', 'like', '%' . str_replace('-', '', $system) . '%')->orWhereHas('admin', function($query) use ($system)
                    {
                        $query->where('username', 'like', '%' . $system . '%');
                    });
                });
            }
            if( $request->type != '' ) 
            {
                $statistics = $statistics->where('transactions.type', $request->type);
            }
            if( $request->in_out != '' ) 
            {
                if ($request->in_out == 1)
                {
                    $statistics = $statistics->whereNotNull('transactions.request_id');
                }
                else
                {
                    $statistics = $statistics->whereNull('transactions.request_id');
                }
            }
            if( $request->sum_from != '' ) 
            {
                $statistics = $statistics->where('transactions.summ', '>=', $request->sum_from);
            }
            if( $request->sum_to != '' ) 
            {
                $statistics = $statistics->where('transactions.summ', '<=', $request->sum_to);
            }
            if( $request->user != '' ) 
            {
                $statistics = $statistics->join('users', 'users.id', '=', 'transactions.user_id');
                $statistics = $statistics->where('users.username', 'like', '%' . $request->user . '%');
            }
            else if( $request->payeer != '' ) 
            {
                $statistics = $statistics->join('users', 'users.id', '=', 'transactions.payeer_id');
                $statistics = $statistics->where('users.username', 'like', '%' . $request->payeer . '%');
            }
            if( $request->dates != '' ) 
            {
                $dates = explode(' - ', $request->dates);
                $statistics = $statistics->where('transactions.created_at', '>=', $dates[0]);
                $statistics = $statistics->where('transactions.created_at', '<=', $dates[1]);
            }
            if( $request->shifts != '' ) 
            {
                $shift = \VanguardLTE\OpenShift::find($request->shifts);
                if( $shift ) 
                {
                    $statistics = $statistics->where('transactions.created_at', '>=', $shift->start_date);
                    if( $shift->end_date ) 
                    {
                        $statistics = $statistics->where('transactions.created_at', '<=', $shift->end_date);
                    }
                }
            }
            $stats = $statistics->get();            
            $statistics = $stats->sortByDesc('created_at')->paginate(20);
            $partner = 1;
            return view('backend.Default.stat.pay_stat_partner', compact('stats', 'statistics','partner'));
        }
        public function game_stat(\Illuminate\Http\Request $request)
        {
            //$users = auth()->user()->hierarchyUsersOnly();
            $statistics = \VanguardLTE\StatGame::select('stat_game.*')->whereIn('stat_game.shop_id', auth()->user()->availableShops())->orderBy('stat_game.date_time', 'DESC');
            //$statistics = \VanguardLTE\StatGame::select('stat_game.*')->orderBy('stat_game.date_time', 'DESC');
            //$statistics = $statistics->whereIn('stat_game.user_id', $users);
            if( $request->game != '' ) 
            {
                $statistics = $statistics->where('stat_game.game', 'like', '%' . $request->game . '%');
            }
            if( $request->balance_from != '' ) 
            {
                $statistics = $statistics->where('stat_game.balance', '>=', $request->balance_from);
            }
            if( $request->balance_to != '' ) 
            {
                $statistics = $statistics->where('stat_game.balance', '<=', $request->balance_to);
            }
            if( $request->bet_from != '' ) 
            {
                $statistics = $statistics->where('stat_game.bet', '>=', $request->bet_from);
            }
            if( $request->bet_to != '' ) 
            {
                $statistics = $statistics->where('stat_game.bet', '<=', $request->bet_to);
            }
            if( $request->win_from != '' ) 
            {
                $statistics = $statistics->where('stat_game.win', '>=', $request->win_from);
            }
            if( $request->win_to != '' ) 
            {
                $statistics = $statistics->where('stat_game.win', '<=', $request->win_to);
            }
            if( $request->user != '' ) 
            {
                $statistics = $statistics->join('users', 'users.id', '=', 'stat_game.user_id');
                $statistics = $statistics->where('users.username', 'like', '%' . $request->user . '%');
            }
            $start_date = date("Y-m-d H:i:s", strtotime("-1 hours"));
            $end_date = date("Y-m-d H:i:s");
            if( $request->dates != '' ) 
            {
                $dates = explode(' - ', $request->dates);
                $start_date = $dates[0];
                $end_date = $dates[1];
            }
            if (!auth()->user()->hasRole('admin'))
            {
                $d = strtotime($start_date);
                if ($d < strtotime("-30 days"))
                {
                    $start_date = date("Y-m-d H:i:s",strtotime("-30 days"));
                }
            }

            $statistics = $statistics->where('stat_game.date_time', '>=', $start_date);
            $statistics = $statistics->where('stat_game.date_time', '<=', $end_date);

            if( $request->shifts != '' ) 
            {
                $shift = \VanguardLTE\OpenShift::find($request->shifts);
                if( $shift ) 
                {
                    $statistics = $statistics->where('stat_game.date_time', '>=', $shift->start_date);
                    if( $shift->end_date ) 
                    {
                        $statistics = $statistics->where('stat_game.date_time', '<=', $shift->end_date);
                    }
                }
            }
            $totalbet= $statistics->sum('bet');
            $totalwin= $statistics->sum('win');
            $game_stat = $statistics->paginate(20);
            return view('backend.Default.stat.game_stat', compact('game_stat','totalbet', 'totalwin'));
        }

        public function deal_stat(\Illuminate\Http\Request $request)
        {
            $statistics = \VanguardLTE\DealLog::orderBy('deal_log.date_time', 'DESC');
            if (auth()->user()->isInoutPartner())
            {
                $users = auth()->user()->hierarchyUsersOnly();
                $shops = auth()->user()->availableShops();
                if( $request->user != '' ) 
                {
                    $user_ids = \VanguardLTE\User::where('username', 'like', '%' . $request->user . '%')->pluck('id')->toArray();
                    if (count($user_ids) > 0)
                    {
                        $statistics = $statistics->whereIn('deal_log.user_id', $user_ids);
                    }
                }
                else
                {
                    $statistics = $statistics->whereIn('deal_log.user_id', $users);
                }
            }
            else  if (auth()->user()->hasRole('manager'))
            {
                $statistics = $statistics->where(['shop_id' => auth()->user()->shop_id, 'type'=>'shop']);
            }
            else
            {
                $statistics = $statistics->where('partner_id', auth()->user()->id);
            }

            if( $request->game != '' ) 
            {
                $statistics = $statistics->where('deal_log.game', 'like', '%' . $request->game . '%');
            }
            if( $request->bet_from != '' ) 
            {
                $statistics = $statistics->where('deal_log.bet', '>=', $request->bet_from);
            }
            if( $request->bet_to != '' ) 
            {
                $statistics = $statistics->where('deal_log.bet', '<=', $request->bet_to);
            }
            if( $request->user != '' ) 
            {
                $user_ids = \VanguardLTE\User::where('username', 'like', '%' . $request->user . '%')->pluck('id')->toArray();
                if (count($user_ids) > 0)
                {
                    $statistics = $statistics->whereIn('deal_log.user_id', $user_ids);
                }
            }
            if( $request->partner != '' ) 
            {
                if ($request->type == 'shop')
                {
                    $shop_id = \VanguardLTE\Shop::where('shops.name', 'like', '%' . $request->partner . '%')->pluck('id')->toArray();
                    if (count($shop_id) > 0)
                    {
                        $statistics = $statistics->whereIn('shop_id', $shop_id);
                    }
                    
                }
                else if ($request->type == 'partner')
                {
                    $partner_ids = \VanguardLTE\User::where('username', 'like', '%' . $request->partner . '%')->pluck('id')->toArray();
                    if (count($partner_ids) > 0)
                    {
                        $statistics = $statistics->whereIn('deal_log.partner_id', $partner_ids);
                    }
                }
                $statistics = $statistics->where('deal_log.type',$request->type);
            }
            $start_date = date("Y-m-d H:i:s", strtotime("-1 hours"));
            $end_date = date("Y-m-d H:i:s");

            if( $request->dates != '' ) 
            {
                $dates = explode(' - ', $request->dates);
                $start_date = $dates[0];
                $end_date = $dates[1];
            }

            $statistics = $statistics->where('deal_log.date_time', '>=', $start_date);
            $statistics = $statistics->where('deal_log.date_time', '<=', $end_date);
            
            $game_stat = $statistics->paginate(20);
            return view('backend.Default.stat.deal_stat', compact('game_stat'));
        }

        public function game_stat_clear()
        {
            \VanguardLTE\StatGame::where('shop_id', \Auth::user()->shop_id)->delete();
            \VanguardLTE\GameLog::where('shop_id', \Auth::user()->shop_id)->delete();
            \VanguardLTE\Game::where('shop_id', \Auth::user()->shop_id)->update([
                'stat_in' => 0, 
                'stat_out' => 0
            ]);
            return redirect()->back()->withSuccess(trans('app.logs_removed'));
        }

        public function deal_stat_clear()
        {
            \VanguardLTE\DealLog::where('shop_id', \Auth::user()->shop_id)->delete();
            // \VanguardLTE\GameLog::where('shop_id', \Auth::user()->shop_id)->delete();
            // \VanguardLTE\Game::where('shop_id', \Auth::user()->shop_id)->update([
            //     'stat_in' => 0, 
            //     'stat_out' => 0
            // ]);
            return redirect()->back()->withSuccess(trans('app.logs_removed'));
        }

        public function bank_stat(\Illuminate\Http\Request $request)
        {
            $users = auth()->user()->hierarchyUsers();
            $statistics = \VanguardLTE\BankStat::select('bank_stat.*')->whereIn('bank_stat.shop_id', auth()->user()->availableShops())->orderBy('bank_stat.created_at', 'DESC');
            $statistics = $statistics->whereIn('bank_stat.user_id', $users);
            if( $request->name != '' ) 
            {
                $statistics = $statistics->where('bank_stat.name', 'like', '%' . $request->name . '%');
            }
            if( $request->sum_from != '' ) 
            {
                $statistics = $statistics->where('bank_stat.sum', '>=', $request->sum_from);
            }
            if( $request->sum_to != '' ) 
            {
                $statistics = $statistics->where('bank_stat.sum', '<=', $request->sum_to);
            }
            if( $request->type != '' ) 
            {
                $statistics = $statistics->where('bank_stat.type', $request->type);
            }
            if( $request->user != '' ) 
            {
                $statistics = $statistics->join('users', 'users.id', '=', 'bank_stat.user_id');
                $statistics = $statistics->where('users.username', 'like', '%' . $request->user . '%');
            }
            if( $request->dates != '' ) 
            {
                $dates = explode(' - ', $request->dates);
                $statistics = $statistics->where('bank_stat.created_at', '>=', $dates[0]);
                $statistics = $statistics->where('bank_stat.created_at', '<=', $dates[1]);
            }
            if( $request->shifts != '' ) 
            {
                $shift = \VanguardLTE\OpenShift::find($request->shifts);
                if( $shift ) 
                {
                    $statistics = $statistics->where('bank_stat.created_at', '>=', $shift->start_date);
                    if( $shift->end_date ) 
                    {
                        $statistics = $statistics->where('bank_stat.created_at', '<=', $shift->end_date);
                    }
                }
            }
            $bank_stat = $statistics->paginate(20);
            return view('backend.Default.stat.bank_stat', compact('bank_stat'));
        }
        public function shop_stat(\Illuminate\Http\Request $request)
        {
            $users = auth()->user()->hierarchyUsers();
            $shops = auth()->user()->shops();
            if( \Auth::user()->hasRole('distributor') ) 
            {
                $ids = \VanguardLTE\ShopUser::where('user_id', \Auth::id())->pluck('shop_id');
                if( count($ids) ) 
                {
                    $shops = \VanguardLTE\Shop::whereIn('id', $ids)->pluck('name', 'id');
                }
            }
            $statistics = \VanguardLTE\ShopStat::select('shops_stat.*')->whereIn('shops_stat.shop_id', auth()->user()->availableShops())->orderBy('shops_stat.date_time', 'DESC')->orderBy('shops_stat.balance', 'ASC');
            //not show admin payer
            if (!auth()->user()->hasRole('admin'))
            {
                $admin = \VanguardLTE\User::where('role_id', 8)->pluck('id')->toArray();
                $statistics = $statistics->whereNotIn('shops_stat.user_id', $admin);
            }
            
            if( $request->name != '' ) 
            {
                $statistics = $statistics->join('shops', 'shops.id', '=', 'shops_stat.shop_id');
                $statistics = $statistics->where('shops.name', $request->name);
            }
            if( $request->sum_from != '' ) 
            {
                $statistics = $statistics->where('shops_stat.sum', '>=', $request->sum_from);
            }
            if( $request->sum_to != '' ) 
            {
                $statistics = $statistics->where('shops_stat.sum', '<=', $request->sum_to);
            }
            if( $request->type != '' ) 
            {
                $statistics = $statistics->where('shops_stat.type', $request->type);
            }
            if( $request->in_out != '' ) 
            {
                if ($request->in_out == 1)
                {
                    $statistics = $statistics->whereNotNull('shops_stat.request_id');
                }
                else
                {
                    $statistics = $statistics->whereNull('shops_stat.request_id');
                }
            }
            if( $request->user != '' ) 
            {
                $statistics = $statistics->join('users', 'users.id', '=', 'shops_stat.user_id');
                $statistics = $statistics->where('users.username', 'like', '%' . $request->user . '%');
            }
            if( $request->dates != '' ) 
            {
                $dates = explode(' - ', $request->dates);
                if (!auth()->user()->hasRole('admin'))
                {
                    $d = strtotime($dates[0]);
                    if ($d < strtotime("-30 days"))
                    {
                        $dates[0] = date("Y-m-d H:i",strtotime("-30 days"));
                    }
                }
            }
            else
            {
                $dates = [
                    date('Y-m-d 00:00'),
                    date('Y-m-d H:i')
                ];
            }
            $statistics = $statistics->where('shops_stat.date_time', '>=', $dates[0]);
            $statistics = $statistics->where('shops_stat.date_time', '<=', $dates[1]);


            if( $request->shifts != '' ) 
            {
                $shift = \VanguardLTE\OpenShift::find($request->shifts);
                if( $shift ) 
                {
                    $statistics = $statistics->where('shops_stat.date_time', '>=', $shift->start_date);
                    if( $shift->end_date ) 
                    {
                        $statistics = $statistics->where('shops_stat.date_time', '<=', $shift->end_date);
                    }
                }
            }
            $shops_stat = $statistics->paginate(20);
            return view('backend.Default.stat.shop_stat', compact('shops_stat', 'shops'));
        }
        public function search(\Illuminate\Http\Request $request)
        {
            if( !$request->q ) 
            {
                return redirect()->back()->withErrors(['Empty query']);
            }
            $query = $request->q;
            $hierarchyUsers = auth()->user()->hierarchyUsers();
            $availableShops = auth()->user()->availableShops();
            $happyhour = \VanguardLTE\HappyHour::where([
                'shop_id' => auth()->user()->shop_id, 
                'time' => date('G')
            ])->first();
            $users = \VanguardLTE\User::whereIn('id', $hierarchyUsers)->where(function($q) use ($query)
            {
                $q->where('email', 'like', '%' . $query . '%')->orWhere('username', 'like', '%' . $query . '%');
            })->orderBy('created_at', 'DESC')->take(25)->get();
            $pay_stats = \VanguardLTE\Transaction::select('*')->whereIn('shop_id', $availableShops)->orderBy('created_at', 'DESC')->whereIn('user_id', $hierarchyUsers)->where(function($q) use ($query)
            {
                $q->where('system', 'like', '%' . $query . '%')->orWhereHas('user', function($q2) use ($query)
                {
                    $q2->where('username', 'like', '%' . $query . '%')->orWhere('email', 'like', '%' . $query . '%');
                })->orWhereHas('admin', function($q2) use ($query)
                {
                    $q2->where('username', 'like', '%' . $query . '%')->orWhere('email', 'like', '%' . $query . '%');
                });
            })->take(25)->get();
            $game_stats = \VanguardLTE\StatGame::select('stat_game.*')->whereIn('stat_game.shop_id', $availableShops)->orderBy('stat_game.date_time', 'DESC')->whereIn('stat_game.user_id', $hierarchyUsers)->where(function($q) use ($query)
            {
                $q->where('stat_game.game', 'like', '%' . $query . '%')->orWhereHas('user', function($q2) use ($query)
                {
                    $q2->where('username', 'like', '%' . $query . '%')->orWhere('email', 'like', '%' . $query . '%');
                });
            })->take(25)->get();
            $bank_stats = \VanguardLTE\BankStat::select('*')->whereIn('shop_id', $availableShops)->orderBy('created_at', 'DESC')->whereIn('user_id', $hierarchyUsers)->where(function($q) use ($query)
            {
                $q->where('name', 'like', '%' . $query . '%')->orWhereHas('user', function($q2) use ($query)
                {
                    $q2->where('username', 'like', '%' . $query . '%')->orWhere('email', 'like', '%' . $query . '%');
                });
            })->take(25)->get();
            $shop_stats = \VanguardLTE\ShopStat::select('*')->whereIn('shop_id', $availableShops)->orderBy('date_time', 'DESC')->whereIn('user_id', $hierarchyUsers)->where(function($q) use ($query)
            {
                $q->whereHas('user', function($q2) use ($query)
                {
                    $q2->where('username', 'like', '%' . $query . '%')->orWhere('email', 'like', '%' . $query . '%');
                })->orWhereHas('shop', function($q2) use ($query)
                {
                    $q2->where('name', 'like', '%' . $query . '%');
                });
            })->take(25)->get();
            $summ = \VanguardLTE\User::where([
                'shop_id' => \Auth::user()->shop_id, 
                'role_id' => 1
            ])->sum('balance');
            $shift_stats = \VanguardLTE\OpenShift::select('*')->whereIn('shop_id', $availableShops)->orderBy('start_date', 'DESC')->whereIn('user_id', $hierarchyUsers)->where(function($q) use ($query)
            {
                $q->whereHas('user', function($q2) use ($query)
                {
                    $q2->where('username', 'like', '%' . $query . '%')->orWhere('email', 'like', '%' . $query . '%');
                })->orWhereHas('shop', function($q2) use ($query)
                {
                    $q2->where('name', 'like', '%' . $query . '%');
                });
            })->take(25)->get();
            return view('backend.Default.dashboard.search', compact('query', 'happyhour', 'summ', 'users', 'pay_stats', 'game_stats', 'bank_stats', 'shop_stats', 'shift_stats'));
        }
        public function shift_stat(\Illuminate\Http\Request $request)
        {
            $users = auth()->user()->hierarchyUsers();
            $summ = \VanguardLTE\User::where([
                'shop_id' => \Auth::user()->shop_id, 
                'role_id' => 1
            ])->sum('balance');
            $statistics = \VanguardLTE\OpenShift::select('open_shift.*')->whereIn('open_shift.shop_id', auth()->user()->availableShops())->orderBy('open_shift.start_date', 'DESC');
            if( $request->shifts != '' ) 
            {
                $statistics = $statistics->where('open_shift.id', $request->shifts);
            }
            if( $request->user != '' ) 
            {
                $statistics = $statistics->join('users', 'users.id', '=', 'open_shift.user_id');
                $statistics = $statistics->where('users.username', 'like', '%' . $request->user . '%');
            }
            if( $request->dates != '' ) 
            {
                $dates = explode(' - ', $request->dates);
                $statistics = $statistics->where('open_shift.start_date', '>=', $dates[0]);
                $statistics = $statistics->where('open_shift.start_date', '<=', $dates[1]);
            }
            $open_shift = $statistics->paginate(20);
            return view('backend.Default.stat.shift_stat', compact('open_shift', 'summ'));
        }
        public function ggr_partner($parent)
        {
            $adjustments = [];
            $ggr_childs = \VanguardLTE\User::where('parent_id', $parent->id)->get();
            
            foreach ($ggr_childs as $c)
            {
                if (!$c->hasRole('manager'))
                {
                    if ($c->ggr_percent > 0 && $c->reset_days > 0){
                        $next_reset_at = date('Y-m-d', strtotime("$c->last_reset_at +$c->reset_days days"));
                        $summary = \VanguardLTE\DailySummary::where('date', '>=', $c->last_reset_at)->where('date', '<', $next_reset_at)->where('user_id', $c->id);
                        $total_ggr = $summary->sum('total_ggr') - $summary->sum('total_ggr_mileage');
                        $total_deal = $summary->sum('total_deal') - $summary->sum('total_mileage');
                        $total_sum = $total_ggr-$total_deal;
                        if ($total_sum < 0) {$total_sum = 0;}
                        $adjustments[] = [
                            'user_id' => $c->id,
                            'username' => $c->username ,
                            'role_id' =>$c->role_id,
                            'last_reset_at' => $c->last_reset_at,
                            'next_reset_at' => $next_reset_at,
                            'reset_days' => $c->reset_days,
                            'total_bet' => $summary->sum('totalbet'),
                            'total_win' => $summary->sum('totalwin'),
                            'ggr_percent' => $c->ggr_percent,
                            'deal_percent' => $c->deal_percent. '%, ' . $c->table_deal_percent . '%',
                            'total_ggr' => $total_ggr,
                            'total_deal' => $total_deal,
                            'total_sum' => $total_sum,
                        ];
                        // $adj_child = $this->ggr_partner($c);
                        // $adjustments = array_merge($adjustments, $adj_child);
                    }
                }
                else
                {
                    $shop = $c->shop;
                    if ($shop->ggr_percent > 0 && $shop->reset_days > 0){
                        $next_reset_at = date('Y-m-d', strtotime("$shop->last_reset_at +$shop->reset_days days"));
                        $summary = \VanguardLTE\DailySummary::where('date', '>=', $shop->last_reset_at)->where('date', '<', $next_reset_at)->where('user_id', $c->id);
                        $total_ggr = $summary->sum('total_ggr');// - $summary->sum('total_ggr_mileage');
                        $total_deal = $summary->sum('total_deal');// - $summary->sum('total_mileage');
                        $total_sum = $total_ggr-$total_deal;
                        if ($total_sum < 0) {$total_sum = 0;}
                        $adjustments[] = [
                            'user_id' => $c->id,
                            'username' => $c->username,
                            'role_id' =>$c->role_id,
                            'last_reset_at' => $shop->last_reset_at,
                            'next_reset_at' => $next_reset_at,
                            'reset_days' => $shop->reset_days,
                            'total_bet' => $summary->sum('totalbet'),
                            'total_win' => $summary->sum('totalwin'),
                            'ggr_percent' => $shop->ggr_percent,
                            'deal_percent' => $shop->deal_percent. '%, ' . $shop->table_deal_percent . '%',
                            'total_ggr' => $total_ggr,
                            'total_deal' => $total_deal,
                            'total_sum' => $total_sum,
                        ];
                    }
                }
            }
            return $adjustments;
        }
        public function adjustment_ggr(\Illuminate\Http\Request $request)
        {
            set_time_limit(0);
            $user_id = $request->input('parent');
            if (!auth()->user()->isInoutPartner())
            {
                return redirect()->back()->withErrors(['비정상적인 접근입니다.']);

            }
            $user = null;
            $adjustments = [];
            if($user_id == null || $user_id == 0)
            {
                if (auth()->user()->hasRole('admin'))
                {
                    $comasters = \VanguardLTE\User::where('parent_id', auth()->user()->id)->get();
                    foreach ($comasters as $comaster)
                    {
                        $adj = $this->ggr_partner($comaster);
                        $adjustments = array_merge($adjustments, $adj);
                    }
                }
                else if (auth()->user()->hasRole('comaster'))
                {
                    $adjustments = $this->ggr_partner(auth()->user());
                }
            }
            else {
                if (auth()->user()->id!=$user_id && !in_array($user_id, auth()->user()->hierarchyPartners()))
                {
                    return redirect()->back()->withErrors(['비정상적인 접근입니다.']);
                }
                $user = \VanguardLTE\User::where('id', $user_id)->first();
                $adjustments = $this->ggr_partner($user);
            }
            
            return view('backend.Default.adjustment.adjustment_ggr', compact('adjustments','user'));
        }
        public function process_ggr(\Illuminate\Http\Request $request)
        {
            if (!auth()->user()->isInOutPartner())
            {
                return redirect()->back()->withErrors(['비정상적인 접근입니다.']);
            }
            $partner_id = $request->id;
            if (!in_array($partner_id, auth()->user()->hierarchyPartners()))
            {
                return redirect()->back()->withErrors(['비정상적인 접근입니다.']);
            }
            $partner = \VanguardLTE\User::where('id', $partner_id)->first();
            if (!$partner)
            {
                return redirect()->back()->withErrors(['파트너를 찾을수 없습니다']);
            }
            if ($partner->hasRole('manager'))
            {
                $shop = $partner->shop;
                $next_reset_at = date('Y-m-d', strtotime("$shop->last_reset_at +$shop->reset_days days"));
                $summary = \VanguardLTE\DailySummary::where('date', '>=', $shop->last_reset_at)->where('date', '<', $next_reset_at)->where('user_id', $partner->id);
                $total_ggr = $summary->sum('total_ggr');// - $summary->sum('total_ggr_mileage');
                $total_deal = $summary->sum('total_deal');// - $summary->sum('total_mileage');
                $summ = $total_ggr-$total_deal;
                if ($summ > 0)
                {
                    //out balance from master
                    $master = $partner->referral;
                    while ($master!=null && !$master->isInoutPartner())
                    {
                        $master = $master->referral;
                    }

                    if ($master == null)
                    {
                        return redirect()->back()->withErrors(['상위파트너를 찾을수 없습니다']);
                    }
                    
                    if ($master->balance < $summ )
                    {
                        return redirect()->back()->withErrors(['상위파트너의 보유금이 부족합니다']);
                    }
                    $master->update(
                        ['balance' => $master->balance - $summ]
                    );
                    
                    $old = $shop->balance;

                    $shop->balance = $shop->balance + $summ;

                    $shop->save();
                    $shop = $shop->fresh();

                    $master = $master->fresh();

                    \VanguardLTE\ShopStat::create([
                        'user_id' => $master->id,
                        'type' => 'ggr_out',
                        'sum' => $summ,
                        'old' => $old,
                        'new' => $shop->balance,
                        'balance' => $master->balance,
                        'shop_id' => $shop->id,
                        'date_time' => \Carbon\Carbon::now()
                    ]);

                    //create another transaction for mananger account
                    $manager = $shop->getUsersByRole('manager')->first();
                    \VanguardLTE\Transaction::create([
                        'user_id' => $manager->id, 
                        'payeer_id' => $master->id, 
                        'type' => 'ggr_out', 
                        'summ' => abs($summ), 
                        'old' => $old,
                        'new' => $shop->balance,
                        'balance' => $master->balance,
                        'shop_id' => $shop->id,
                    ]);
                }

                $shop->ggr_balance = 0;
                $shop->ggr_mileage = 0;
                $shop->last_reset_at = date('Y-m-d');
                $shop->save();
            }
            else
            {
                $next_reset_at = date('Y-m-d', strtotime("$partner->last_reset_at +$partner->reset_days days"));
                $summary = \VanguardLTE\DailySummary::where('date', '>=', $partner->last_reset_at)->where('date', '<', $next_reset_at)->where('user_id', $partner->id);
                $total_ggr = $summary->sum('total_ggr') - $summary->sum('total_ggr_mileage');
                $total_deal = $summary->sum('total_deal') - $summary->sum('total_mileage');
                $summ = $total_ggr-$total_deal;
                if ($summ > 0) {
                    //out balance from master
                    $comaster = $partner->referral;
                    while ($comaster!=null && !$comaster->isInoutPartner())
                    {
                        $comaster = $comaster->referral;
                    }

                    if ($comaster == null)
                    {
                        return redirect()->back()->withErrors(['상위파트너를 찾을수 없습니다']);
                    }
                    
                    if ($comaster->balance < $summ )
                    {
                        return redirect()->back()->withErrors(['상위파트너의 보유금이 부족합니다']);
                    }
                    $comaster->update(
                        ['balance' => $comaster->balance - $summ]
                    );
                    
                    $old = $partner->balance;

                    $partner->balance = $partner->balance + $summ;
                    $partner->save();
                    $partner = $partner->fresh();

                    $comaster = $comaster->fresh();

                    \VanguardLTE\Transaction::create([
                        'user_id' => $partner->id,
                        'payeer_id' => $comaster->id,
                        'system' => $partner->username,
                        'type' => 'ggr_out',
                        'summ' => $summ,
                        'old' => $old,
                        'new' => $partner->balance,
                        'balance' => $comaster->balance,
                        'shop_id' => 0
                    ]);
                }
                $partner->ggr_balance = 0;
                $partner->ggr_mileage = 0;
                
                $partner->last_reset_at = date('Y-m-d');
                $partner->save();
            }

            return redirect()->back()->withSuccess(['죽장금이 정산되었습니다']);
            

        }
        public function reset_ggr(\Illuminate\Http\Request $request)
        {
            if (!auth()->user()->isInOutPartner())
            {
                return redirect()->back()->withErrors(['비정상적인 접근입니다.']);
            }
            $partner_id = $request->id;
            if (!in_array($partner_id, auth()->user()->hierarchyPartners()))
            {
                return redirect()->back()->withErrors(['비정상적인 접근입니다.']);
            }
            $partner = \VanguardLTE\User::where('id', $partner_id)->first();
            if (!$partner)
            {
                return redirect()->back()->withErrors(['파트너를 찾을수 없습니다']);
            }
            if ($partner->hasRole('manager'))
            {
                $shop = $partner->shop;
                $shop->ggr_balance = 0;
                $shop->ggr_mileage = 0;
                
                $shop->last_reset_at = date('Y-m-d');
                $shop->save();
            }
            else
            {
                $partner->ggr_balance = 0;
                $partner->ggr_mileage = 0;

                $partner->last_reset_at = date('Y-m-d');
                $partner->save();
            }
            return redirect()->back()->withSuccess(['죽장기간이 리셋되었습니다']);
            
        }
        public function adjustment_daily(\Illuminate\Http\Request $request)
        {
            $user_id = $request->input('parent');
            $users = [];
            $shops = [];
            $user = null;
            if ($request->search != '')
            {
                if ($request->type == 'shop')
                {
                    $availableShops = auth()->user()->availableShops();
                    $shop = \VanguardLTE\Shop::where('shops.name', 'like', '%' . $request->search . '%')->whereIn('id', $availableShops)->first();
                    if (!$shop)
                    {
                        return redirect()->back()->withErrors('매장을 찾을수 없습니다.');
                    }
                    $user_id = $shop->getUsersByRole('manager')->first()->id;
                }
                else
                {
                    $availablePartners = auth()->user()->hierarchyPartners();
                    $partner = \VanguardLTE\User::where('username', 'like', '%' . $request->search . '%')->whereIn('id', $availablePartners)->first();
                    if (!$partner)
                    {
                        return redirect()->back()->withErrors('파트너를 찾을수 없습니다.');
                    }
                    $user_id = $partner->id;
                }
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
            if($dates != null && $dates != ''){
                $dates_tmp = explode(' - ', $dates);
                $start_date = $dates_tmp[0];
                $end_date = $dates_tmp[1];
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
            $type = 'daily';
        
            return view('backend.Default.adjustment.adjustment_daily', compact('start_date', 'end_date', 'user', 'summary', 'type'));
        }
        public function adjustment_monthly(\Illuminate\Http\Request $request)
        {
            $user_id = $request->input('parent');
            $users = [];
            $shops = [];
            $user = null;
            if ($request->search != '')
            {
                if ($request->type == 'shop')
                {
                    $availableShops = auth()->user()->availableShops();
                    $shop = \VanguardLTE\Shop::where('shops.name', 'like', '%' . $request->search . '%')->whereIn('id', $availableShops)->first();
                    if (!$shop)
                    {
                        return redirect()->back()->withErrors('매장을 찾을수 없습니다.');
                    }
                    $user_id = $shop->getUsersByRole('manager')->first()->id;
                }
                else
                {
                    $availablePartners = auth()->user()->hierarchyPartners();
                    $partner = \VanguardLTE\User::where('username', 'like', '%' . $request->search . '%')->whereIn('id', $availablePartners)->first();
                    if (!$partner)
                    {
                        return redirect()->back()->withErrors('파트너를 찾을수 없습니다.');
                    }
                    $user_id = $partner->id;
                }
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
                $dates_tmp = explode(' - ', $dates);
                $start_date = $dates_tmp[0] . '-01';
                $end_date = $dates_tmp[1] . '-01';
                $request->session()->put('dates', $dates);
            }

            $summary = \VanguardLTE\DailySummary::where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('type','monthly')->whereIn('user_id', $users);
            $summary = $summary->orderBy('user_id', 'ASC')->orderBy('date', 'ASC');
            $summary = $summary->paginate(31);
            $type = 'monthly';
        
            return view('backend.Default.adjustment.adjustment_daily', compact('start_date', 'end_date', 'user', 'summary','type'));
        }
        public function adjustment_partner(\Illuminate\Http\Request $request)
        {
            set_time_limit(0);
            $user_id = $request->input('parent');
            $users = [];
            $user = null;
            if ($request->search != '')
            {
                if ($request->type == 'shop')
                {
                    $availableShops = auth()->user()->availableShops();
                    $shop = \VanguardLTE\Shop::where('shops.name', 'like', '%' . $request->search . '%')->whereIn('id', $availableShops)->first();
                    if (!$shop)
                    {
                        return redirect()->back()->withErrors('매장을 찾을수 없습니다.');
                    }
                    $user_id = $shop->getUsersByRole('manager')->first()->id;
                }
                else
                {
                    $availablePartners = auth()->user()->hierarchyPartners();
                    $partner = \VanguardLTE\User::where('username', 'like', '%' . $request->search . '%')->whereIn('id', $availablePartners)->first();
                    if (!$partner)
                    {
                        return redirect()->back()->withErrors('파트너를 찾을수 없습니다.');
                    }
                    $user_id = $partner->id;
                }
                $dates = $request->dates;
                $users = \VanguardLTE\User::where('id', $user_id);
            }
            else
            {
                if($user_id == null || $user_id == 0)
                {
                    $user_id = auth()->user()->id;
                    $users = \VanguardLTE\User::where('id', auth()->user()->id);
                    $request->session()->put('dates', null);
                    $dates = $request->dates;
                }
                else {
                    if (auth()->user()->id!=$user_id && !in_array($user_id, auth()->user()->hierarchyPartners()))
                    {
                        return redirect()->back()->withErrors(['비정상적인 접근입니다.']);
                    }
                    $users = \VanguardLTE\User::where('parent_id', $user_id);
                    $user =  \VanguardLTE\User::where('id', $user_id)->first();
                    $dates = ($request->session()->exists('dates') ? $request->session()->get('dates') : '');
                }
            }
            $childs = $users->paginate(20);
            $start_date = date("Y-m-d");
            $end_date = date("Y-m-d");
            if($dates != null && $dates != ''){
                $dates_tmp = explode(' - ', $dates);
                $start_date = $dates_tmp[0];
                $end_date = $dates_tmp[1];
                $request->session()->put('dates', $dates);
            }

            $adjustments = [];

            $adj_days = \VanguardLTE\DailySummary::groupBy('user_id')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->whereIn('type',['daily','today'])->whereIn('user_id', $users->pluck('id')->toArray())->selectRaw('user_id, shop_id, sum(totalin) as totalin,sum(totalout) as totalout,sum(moneyin) as moneyin,sum(moneyout) as moneyout,sum(dealout) as dealout,sum(ggrout) as ggrout,sum(totalbet) as totalbet,sum(totalwin) as totalwin,sum(total_deal) as total_deal,sum(total_mileage) as total_mileage,sum(total_ggr) as total_ggr,sum(total_ggr_mileage) as total_ggr_mileage, updated_at')->get();

            foreach ($adj_days as $adj_user)
            {
                $adj = $adj_user->toArray();
                $adj['role_id']=$adj_user->user->role_id;
                $adj['name']=$adj_user->user->username;
                $adj['type']='today';
                $comaster = $adj_user->user;
                while ($comaster && !$comaster->hasRole('comaster'))
                {
                    $comaster = $comaster->referral;
                }
                if ($comaster)
                {
                    $adj['ggr'] = ($adj['totalbet'] - $adj['totalwin']) * $comaster->money_percent / 100;
                }
                else
                {
                    $adj['ggr'] = 0;
                }
                $adjustments[] = $adj;
            }
            $updated_at = '00:00:00';
            if (count($adj_days) > 0)
            {
                $updated_at = $adj_days->last()->updated_at;
            }
            return view('backend.Default.adjustment.adjustment_partner', compact('adjustments', 'start_date', 'end_date', 'user', 'updated_at', 'childs'));
        } 
        public function adjustment_game(\Illuminate\Http\Request $request)
        {
            $dates = $request->dates;
            $start_date = date("Y-m-d 0:0:0");
            $end_date = date("Y-m-d H:i:s");
            if($dates != null && $dates != ''){
                $dates_tmp = explode(' - ', $request->dates);
                $start_date = $dates_tmp[0] . " 00:00:00";
                $end_date = $dates_tmp[1] . " 23:59:59";
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
            $catid = -1;
            if ($request->category != '')
            {
                $category = \VanguardLTE\CategoryTrans::where('trans_title', 'like', '%'. $request->category .'%');
                $category = $category->first();
                if (!$category)
                {
                    return redirect()->back()->withErrors(['게임사를 찾을수 없습니다']);
                }
                $catid = $category->category_id;
            }

            $bshowGame = false;
            if ($request->cat != '' && $request->date != '' && $request->type != '')
            {
                $bshowGame = true;
                $adj_games = \VanguardLTE\GameSummary::where(['date' => $request->date, 'type' => $request->type, 'category_id' => $request->cat, 'user_id'=> $user_id])->orderby('totalbet')->get();
            }
            else
            {
                if ($catid > 0)
                {
                    $adj_games = \VanguardLTE\CategorySummary::where('date', '>=', $start_date)->where('date', '<=', $end_date)->whereIn('type',['daily','today'])->where(['category_id'=>$catid, 'user_id'=> $user_id])->orderby('date')->get();
                }
                else
                {
                    $adj_games = \VanguardLTE\CategorySummary::where('date', '>=', $start_date)->where('date', '<=', $end_date)->whereIn('type',['daily','today'])->where('user_id', $user_id)->orderby('date')->get();
                }
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

                        $bng_adj = null; // provider's game
                        $bng_play_adj = null; // owner's game
                        $bng_index = 0;
                        $bng_play_index = 0;
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

                            if ($game['name'] == 'Booongo Play')
                            {
                                $bng_play_adj = $game;
                                $bng_play_index = $index;
                            }
                            if ($game['name'] == 'Booongo')
                            {
                                $bng_adj = $game;
                                $bng_index = $index;
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

                        if ($bng_play_adj)
                        {
                            if ($bng_adj)
                            {
                                $bng_adj['totalwin'] = $bng_adj['totalwin'] + $bng_play_adj['totalwin'];
                                $bng_adj['totalbet'] = $bng_adj['totalbet'] + $bng_play_adj['totalbet'];
                                $bng_adj['totalcount'] = $bng_adj['totalcount'] + $bng_play_adj['totalcount'];
                                $bng_adj['total_deal'] = $bng_adj['total_deal'] + $bng_play_adj['total_deal'];
                                $bng_adj['total_mileage'] = $bng_adj['total_mileage'] + $bng_play_adj['total_mileage'];
                                $cat['cat'][$bng_index] = $bng_adj;
                                unset($cat['cat'][$bng_play_index]);
                            }
                            else
                            {
                                $bng_play_adj['title'] = '부웅고';
                                $cat['cat'][$bng_play_index] = $bng_play_adj;
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

            return view('backend.Default.adjustment.adjustment_game', compact('categories', 'totalcategory', 'updated_at','start_date', 'end_date'));


        }


        public function adjustment_shift(\Illuminate\Http\Request $request)
        {
            $user_id = $request->input('parent');
            $users = [];
            $shops = [];
            $user = null;
            $b_distributor = false;
            if($user_id == null || $user_id == 0)
            {
                $user_id = auth()->user()->id;
                $users = [$user_id];
                if (auth()->user()->hasRole('manager'))
                {
                    $b_distributor = true;
                }
            }
            else {
                if (auth()->user()->id!=$user_id && !in_array($user_id, auth()->user()->hierarchyPartners()))
                {
                    return redirect()->back()->withErrors(['비정상적인 접근입니다.']);
                }
                $user = \VanguardLTE\User::where('id', $user_id)->get()->first();
                if($user->hasRole('distributor')){
                    $b_distributor = true;
                }
                $users = $user->childPartners();
            }
            
            $adjustments = [];
            $adjustment_logs = [];
            if($b_distributor){
                $shop_users = \VanguardLTE\ShopUser::whereIn('user_id', $users)->get()->pluck('shop_id')->toArray();
                $partners = \VanguardLTE\Shop::whereIn('id', $shop_users)->get();

                foreach($partners as $shop){

                    $summ = \VanguardLTE\User::where([
                        'shop_id' => $shop->id, 
                        'role_id' => 1
                    ])->sum('balance');
    
                    $adjustment = new \VanguardLTE\Adjustment();
                    $adjustment->total_in = $summ;
                    $adjustment->partner = $shop;
                    
                    $open_shift = \VanguardLTE\OpenShift::where('shop_id', $shop->id);
                    $open_shift = $open_shift->where('type', 'shop');
                    $open_shift = $open_shift->where('end_date', null);
                    $open_shift = $open_shift->get()->first();
                    $adjustment->open_shift = $open_shift;
                    
                    $adjustments[] = $adjustment;
                }
                $shift_logs = [];
                if (!empty($shop_users) && count($shop_users) > 0){
                    $shift_logs = \VanguardLTE\OpenShift::where('shop_id', $shop_users);
                    $shift_logs = $shift_logs->where('type', 'shop');
                    $shift_logs = $shift_logs->where('end_date', '<>', null);
                    $shift_logs = $shift_logs->get();
                }
            }
            else {
                $partners = \VanguardLTE\User::whereIn('id', $users)->get();
                foreach($partners as $partner){
                    
                    $adjustment = new \VanguardLTE\Adjustment();
                    $adjustment->partner = $partner;
                    
                    $open_shift = \VanguardLTE\OpenShift::where('user_id', $partner->id);
                    $open_shift = $open_shift->where('end_date', null);
                    $open_shift = $open_shift->get()->first();
                    $adjustment->open_shift = $open_shift;

                    $adjustments[] = $adjustment;
                }
                $shift_logs = [];
                if (!empty($users) && count($users) > 0){
                    $shift_logs = \VanguardLTE\OpenShift::where('user_id', $users);
                    $shift_logs = $shift_logs->where('type', '<>', 'shop');
                    $shift_logs = $shift_logs->where('end_date', '<>', null);
                    $shift_logs = $shift_logs->get();
                }
            }

            return view('backend.Default.adjustment.adjustment_shift', compact('adjustments', 'shift_logs', 'user'));
        }

        public function adjustment_create_shift(\Illuminate\Http\Request $request)
        {
            $balance = 0;
            if(auth()->user()->hasRole('manager')){
                $type = 'shop';
                $balance = auth()->user()->shop->balance;
            }
            else
            {
                $type = 'partner';
                $balance = auth()->user()->balance;
            }

            \VanguardLTE\OpenShift::create([
                'start_date' => \Carbon\Carbon::now(), 
                'user_id' => \Auth::id(), 
                'shop_id' => \Auth::user()->shop_id,
                'old_total' => $balance,
                'deal_profit' => 0,
                'mileage' => 0,
                'type' => $type
            ]);
            return redirect()->route(config('app.admurl').'.adjustment_shift')->withSuccess(trans('app.open_shift_started'));
        }

        public function adjustment_shift_stat(\Illuminate\Http\Request $request)
        {
            if(auth()->user()->hasRole('manager')){
                $shop_users = \VanguardLTE\User::where([
                    'shop_id' => auth()->user()->shop_id,
                    'role_id' => 1,
                ]);
                $shop_users->update(
                    [
                        'total_in' => 0,
                        'total_out' => 0
                    ]
                );
                $shop_games = \VanguardLTE\Game::where([
                    'shop_id' => auth()->user()->shop_id,
                ]);
                $shop_games->update(
                    [
                        'stat_in' => 0,
                        'stat_out' => 0,
                        'bids' => 0
                    ]
                );
            }
            $summ = $request->summ;
            $remain_balance = 0;

            if(auth()->user()->hasRole('manager')){
                $partner_id = auth()->user()->shop_id;
                $balance = auth()->user()->shop->balance;
                $partner = auth()->user()->shop;
                $type = 'shop';
                $shift = \VanguardLTE\OpenShift::where([
                    'shop_id' => $partner->id, 
                    'type' => 'shop',
                    'end_date' => null
                ])->first();
                $remain_balance = $partner->balance;
            }
            else
            {
                $partner_id = auth()->user()->id;
                $balance = auth()->user()->balance;
                $partner = auth()->user();
                $type = 'partner';
                $shift = \VanguardLTE\OpenShift::where([
                    'user_id' => $partner_id, 
                    'end_date' => null,
                    'type' => 'partner'
                ])->first();
                $remain_balance = $partner->balance;
            }
            if ($shift) {
                $shift->update([
                    'end_date' => \Carbon\Carbon::now(), 
                    'last_banks' => $shift->banks(), 
                    'last_returns' => $summ, 
                    'balance' => $partner->balance,
                    'users' => 0,
                ]);
            }

            \VanguardLTE\OpenShift::create([
                'start_date' => \Carbon\Carbon::now(), 
                'user_id' => \Auth::id(), 
                'shop_id' => \Auth::user()->shop_id,
                'old_total' => $remain_balance,
                'type' => $type
            ]);
            return redirect()->route(config('app.admurl').'.adjustment_shift')->withSuccess(trans('app.open_shift_started'));
        }

        public function in_out_request(\Illuminate\Http\Request $request) 
        {
            $user = auth()->user();
            $in_out_logs = \VanguardLTE\WithdrawDeposit::where('user_id', $user->id);
            $master = $user;
            while ($master!=null && !$master->isInoutPartner())
            {
                $master = $master->referral;
            }

            $bankinfo = '없음';

            if ($master != null) {
                if ($master->bank_name !== '') {
                    $bankinfo = "[$master->bank_name] $master->account_no, $master->recommender";
                }
                else
                {
                    $bankinfo = "입금계좌가 설정되어있지 않습니다.";
                }
            }

            $in_out_logs = $in_out_logs->get()->sortByDesc('created_at')->paginate(10);
            return view('backend.Default.adjustment.in_out_request', compact('in_out_logs', 'bankinfo'));
        }
        public function in_out_history(\Illuminate\Http\Request $request) 
        {
            if (auth()->user()->hasRole('admin'))
            {
                $in_out_logs = \VanguardLTE\WithdrawDeposit::whereIn('status', [\VanguardLTE\WithdrawDeposit::DONE, \VanguardLTE\WithdrawDeposit::CANCEL])->orderBy('created_at','desc');
            }
            else if (auth()->user()->isInoutPartner())
            {
                $in_out_logs = \VanguardLTE\WithdrawDeposit::where('payeer_id', auth()->user()->id)->whereIn('status', [\VanguardLTE\WithdrawDeposit::DONE, \VanguardLTE\WithdrawDeposit::CANCEL])->orderBy('created_at','desc');
            }
            else
            {
                if (auth()->user()->hasRole('manager'))
                {
                    $in_out_logs = \VanguardLTE\WithdrawDeposit::where(['shop_id' => auth()->user()->shop_id, 'partner_type' => 'shop'])->whereIn('status', [\VanguardLTE\WithdrawDeposit::DONE, \VanguardLTE\WithdrawDeposit::CANCEL])->orderBy('created_at','desc');

                }
                else
                {
                    $in_out_logs = \VanguardLTE\WithdrawDeposit::where(['user_id' => auth()->user()->id, 'partner_type' => 'partner'])->whereIn('status', [\VanguardLTE\WithdrawDeposit::DONE, \VanguardLTE\WithdrawDeposit::CANCEL])->orderBy('created_at','desc');
                }
            }

            if( $request->type != '' ) 
            {
                $in_out_logs = $in_out_logs->where('type', $request->type);
            }
            if( $request->sum_from != '' ) 
            {
                $in_out_logs = $in_out_logs->where('sum', '>=', $request->sum_from);
            }
            if( $request->sum_to != '' ) 
            {
                $in_out_logs = $in_out_logs->where('sum', '<=', $request->sum_to);
            }
            if( $request->dates != '' ) 
            {
                $dates = explode(' - ', $request->dates);
                if (!auth()->user()->hasRole('admin'))
                {
                    $d = strtotime($dates[0]);
                    if ($d < strtotime("-30 days"))
                    {
                        $dates[0] = date("Y-m-d H:i",strtotime("-30 days"));
                    }
                }
            }
            else
            {
                $dates = [
                    date('Y-m-d 00:00'),
                    date('Y-m-d H:i')
                ];
            }
            $in_out_logs = $in_out_logs->where('created_at', '>=', $dates[0]);
            $in_out_logs = $in_out_logs->where('created_at', '<=', $dates[1]);
            if( $request->recommender != '' ) 
            {
                $in_out_logs = $in_out_logs->where('recommender', 'like', '%'.$request->recommender.'%');
            }
            if( $request->search != '' ) 
            {
                if ($request->partner_type == 'shop')
                {
                    $in_out_logs = $in_out_logs->join('shops', 'shops.id', '=', 'withdraw_deposit.shop_id');
                    $in_out_logs = $in_out_logs->where('shops.name', 'like', '%' . $request->search . '%');
                }
                else if ($request->partner_type == 'partner')
                {
                    $user_ids = \VanguardLTE\User::where('username','like', '%' . $request->search . '%')->pluck('id')->toArray();
                    $in_out_logs = $in_out_logs->whereIn('user_id', $user_ids);
                }
                $in_out_logs = $in_out_logs->where('withdraw_deposit.partner_type',$request->partner_type);
            }


            $stat = [
                'add' => (clone $in_out_logs)->where(['type'=>'add', 'status'=>\VanguardLTE\WithdrawDeposit::DONE])->sum('sum'),
                'out' => (clone $in_out_logs)->where(['type'=>'out', 'status'=>\VanguardLTE\WithdrawDeposit::DONE])->sum('sum'),
            ];

            $in_out_logs = $in_out_logs->paginate(20);

            return view('backend.Default.adjustment.in_out_history', compact('in_out_logs','stat'));
        }

        public function in_out_manage($type, \Illuminate\Http\Request $request) 
        {
            if (auth()->user()->isInoutPartner())
            {
                $in_out_request = \VanguardLTE\WithdrawDeposit::where([
                    'payeer_id'=> auth()->user()->id,
                    'status'=> \VanguardLTE\WithdrawDeposit::REQUEST,
                ])->where('type', $type);
                $in_out_wait = \VanguardLTE\WithdrawDeposit::where([
                    'payeer_id'=> auth()->user()->id,
                    'status'=> \VanguardLTE\WithdrawDeposit::WAIT,
                ])->where('type', $type);
                $in_out_logs = \VanguardLTE\WithdrawDeposit::where([
                    'payeer_id'=> auth()->user()->id,
                    'status'=> \VanguardLTE\WithdrawDeposit::DONE,
                ])->where('type', $type)->orderBy('created_at','desc')->take(20);
            }
            else
            {
                return redirect()->back()->withErrors('접근권한이 없습니다.');
            }
            $in_out_request = $in_out_request->orderBy('created_at', 'desc')->get();
            $in_out_request = $in_out_request->paginate(20);
            $in_out_wait = $in_out_wait->orderBy('created_at', 'desc')->get();
            $in_out_wait = $in_out_wait->paginate(20);
            $in_out_logs = $in_out_logs->get();

            return view('backend.Default.adjustment.in_out_manage', compact('in_out_request','in_out_wait','in_out_logs','type'));
        }


/*        public function security()
        {
            if( config('LicenseDK.APL_INCLUDE_KEY_CONFIG') != 'wi9qydosuimsnls5zoe5q298evkhim0ughx1w16qybs2fhlcpn' ) 
            {
                return false;
            }
            if( md5_file(base_path() . '/app/Lib/LicenseDK.php') != '3c5aece202a4218a19ec8c209817a74e' ) 
            {
                return false;
            }
            if( md5_file(base_path() . '/config/LicenseDK.php') != '951a0e23768db0531ff539d246cb99cd' ) 
            {
                return false;
            }
            return true;
        }*/
    }

}
namespace 
{
    function onkXppk3PRSZPackRnkDOJaZ9()
    {
        return 'OkBM2iHjbd6FHZjtvLpNHOc3lslbxTJP6cqXsMdE4evvckFTgS';
    }

}

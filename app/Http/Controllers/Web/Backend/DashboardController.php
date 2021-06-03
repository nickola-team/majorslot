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
            if( !\Auth::user()->hasRole('admin') ||  \Session::get('isCashier')) 
            {
                return redirect()->route('backend.user.list');
            }
            $ids = auth()->user()->availableUsers();
            $availableShops = auth()->user()->availableShops();

            
            $start_date = date("Y-m-01");
            $end_date = date("Y-m-d");
            $todayprofit = 0;
            if (count($availableShops) > 0){
                $monthsummary = \VanguardLTE\DailySummary::where('user_id', auth()->user()->id)->where('date', '>=', $start_date)->where('date', '<=', $end_date)->get();
                $todayprofit = $monthsummary->sum('totalbet') - $monthsummary->sum('totalwin');
            } 


            $usersPerMonth = $this->users->countOfNewUsersPerMonth(\Carbon\Carbon::now()->subYear()->startOfMonth(), \Carbon\Carbon::now()->endOfMonth(), $ids);
            $stats = [
                'total' => $this->users->count($ids), 
                'new' => $this->users->newUsersCount($ids), 
                'banned' => 0,//$this->users->countByStatus(\VanguardLTE\Support\Enum\UserStatus::BANNED, $ids), 
                'todayprofit' => $todayprofit,
                'games' => 0,/*\VanguardLTE\Game::where([
                    'shop_id' => \Auth::user()->shop_id, 
                    'view' => 1
                ])->count()*/
            ];
            $latestRegistrations = $this->users->latest(5, $ids);
            $user = \Auth::user();
            $shops = \VanguardLTE\Shop::orderBy('id', 'desc')->whereIn('id', $availableShops)->take(5)->get();
            $summ = 0;/*\VanguardLTE\User::where([
                'shop_id' => \Auth::user()->shop_id, 
                'role_id' => 1
            ])->sum('balance');*/
            $open_shift = null;//\VanguardLTE\OpenShift::select('open_shift.*')->whereIn('open_shift.shop_id', $availableShops)->orderBy('open_shift.start_date', 'DESC')->take(5)->get();
            $statistics = \VanguardLTE\Transaction::whereIn('user_id', $ids)->orderBy('id', 'desc')->take(5)->get();
            $gamestat = \VanguardLTE\StatGame::whereIn('user_id', $ids)->orderBy('date_time', 'DESC')->take(5)->get();
            $bank_stat = \VanguardLTE\BankStat::whereIn('user_id', $ids)->orderBy('created_at', 'DESC')->take(5)->get();
            $shops_stat = \VanguardLTE\ShopStat::whereIn('user_id', $ids)->orderBy('date_time', 'DESC')->take(5)->get();




            return view('backend.Default.dashboard.admin', compact('stats', 'latestRegistrations', 'usersPerMonth', 'user', 'statistics', 'gamestat', 'shops', 'open_shift', 'summ', 'bank_stat', 'shops_stat'));
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
                    $sysdata = '<a href="' . route('backend.statistics', ['system_str' => $system]) . '">' . $system . '</a>';
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
                    $usdata = '<a href="' . route('backend.statistics', ['user' => $transaction->user->username]) . '">' . $transaction->user->username . '</a>';
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
                return redirect()->route('backend.shift_stat')->withErrors([trans('users_with_balance', ['count' => $users])]);
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
                return redirect()->route('backend.shift_stat')->withErrors([trans('app.wrong_shop')]);
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
            return redirect()->route('backend.shift_stat')->withSuccess(trans('app.open_shift_started'));
        }
        public function statistics(\Illuminate\Http\Request $request)
        {
            $users = auth()->user()->hierarchyUsersOnly();
            $statistics = \VanguardLTE\Transaction::select('transactions.*')->orderBy('transactions.created_at', 'DESC');
            $statistics = $statistics->whereIn('transactions.user_id', $users);
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
            $partner = 0;
            return view('backend.Default.stat.pay_stat', compact('stats', 'statistics','partner'));
        }

        public function statistics_partner(\Illuminate\Http\Request $request)
        {
            $users = auth()->user()->hierarchyPartners();
            array_push($users,auth()->user()->id);
            $statistics = \VanguardLTE\Transaction::select('transactions.*')->orderBy('transactions.created_at', 'DESC');
            $statistics = $statistics->whereIn('transactions.user_id', $users);
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
            $start_date = date("Y-m-d") . " 00:00:00";
            $end_date = date("Y-m-d H:i:s");
            if( $request->dates != '' ) 
            {
                $dates = explode(' - ', $request->dates);
                $start_date = $dates[0];
                $end_date = $dates[1];
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
            $game_stat = $statistics->paginate(20);
            return view('backend.Default.stat.game_stat', compact('game_stat'));
        }

        public function deal_stat(\Illuminate\Http\Request $request)
        {
            $users = auth()->user()->hierarchyUsersOnly();
            if(!auth()->user()->hasRole('admin')) {
                $users = [auth()->user()->id];
            }
            $statistics = \VanguardLTE\DealLog::select('deal_log.*')->orderBy('deal_log.date_time', 'DESC');
            if(auth()->user()->hasRole('admin')){
                $statistics = $statistics->whereIn('deal_log.user_id', $users);
            }
            else if(auth()->user()->hasRole('manager')){
                $shop = auth()->user()->shop;
                $statistics = $statistics->whereIn('deal_log.user_id', $shop->users()->pluck('user_id')->toArray());
                $statistics = $statistics->where('type','=', 'shop');
            }
            else if(auth()->user()->hasRole(['master','agent','distributor'])){
                $statistics = $statistics->whereIn('deal_log.partner_id', $users);
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
                $statistics = $statistics->join('users', 'users.id', '=', 'deal_log.user_id');
                $statistics = $statistics->where('users.username', 'like', '%' . $request->user . '%');
            }
            if( $request->partner != '' ) 
            {
                if ($request->type == 'shop')
                {
                    $statistics = $statistics->join('shops', 'shops.id', '=', 'deal_log.shop_id');
                    $statistics = $statistics->where('shops.name', 'like', '%' . $request->partner . '%');
                }
                else if ($request->type == 'partner')
                {
                    $statistics = $statistics->join('users', 'users.id', '=', 'deal_log.partner_id');
                    $statistics = $statistics->where('users.username', 'like', '%' . $request->partner . '%');
                }
                $statistics = $statistics->where('deal_log.type',$request->type);
            }

            if( $request->dates != '' ) 
            {
                $dates = explode(' - ', $request->dates);
                $statistics = $statistics->where('deal_log.date_time', '>=', $dates[0]);
                $statistics = $statistics->where('deal_log.date_time', '<=', $dates[1]);
            }
            
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
            $statistics = \VanguardLTE\ShopStat::select('shops_stat.*')->whereIn('shops_stat.shop_id', auth()->user()->availableShops())->orderBy('shops_stat.date_time', 'DESC');
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
                $statistics = $statistics->where('shops_stat.date_time', '>=', $dates[0]);
                $statistics = $statistics->where('shops_stat.date_time', '<=', $dates[1]);
            }
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
        public function adjustment_daily(\Illuminate\Http\Request $request)
        {
            $user_id = $request->input('parent');
            $users = [];
            $shops = [];
            $user = null;
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
            
            $start_date = date("Y-m-d",strtotime("-1 days"));
            $end_date = date("Y-m-d");
            if($dates != null && $dates != ''){
                $dates_tmp = explode(' - ', $dates);
                $start_date = $dates_tmp[0];
                $end_date = $dates_tmp[1];
                $request->session()->put('dates', $dates);
            }

            $summary = \VanguardLTE\DailySummary::where('date', '>=', $start_date)->where('date', '<=', $end_date)->whereIn('user_id', $users);
            $summary = $summary->orderBy('user_id', 'ASC')->orderBy('date', 'ASC');
            $summary = $summary->paginate(31);
        
            return view('backend.Default.adjustment.adjustment_daily', compact('start_date', 'end_date', 'user', 'summary'));
        }
        public function adjustment_monthly(\Illuminate\Http\Request $request)
        {
            $user_id = $request->input('parent');
            $users = [];
            $shops = [];
            $user = null;
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
            
            $start_date = date("Y-m-d",strtotime("-1 days"));
            $end_date = date("Y-m-d");
            if($dates != null && $dates != ''){
                $dates_tmp = explode(' - ', $dates);
                $start_date = $dates_tmp[0];
                $end_date = $dates_tmp[1];
                $request->session()->put('dates', $dates);
            }

            $summary = \VanguardLTE\DailySummary::where('date', '>=', $start_date)->where('date', '<=', $end_date)->whereIn('user_id', $users);
            $summary = $summary->orderBy('user_id', 'ASC')->orderBy('date', 'ASC');
            $summary = $summary->paginate(31);
        
            return view('backend.Default.adjustment.adjustment_daily', compact('start_date', 'end_date', 'user', 'summary'));
        }
        public function adjustment_partner(\Illuminate\Http\Request $request)
        {
            set_time_limit(0);
            $user_id = $request->input('parent');
            $users = [];
            $user = null;
            if ($request->search != '')
            {
                $users = \VanguardLTE\User::orderBy('username', 'ASC');
                $users = $users->whereIn('id', auth()->user()->hierarchyPartners());
                $users = $users->where('username', 'like', '%' . $request->search . '%');
                $dates = $request->dates;
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
                    $dates = ($request->session()->exists('dates') ? $request->session()->get('dates') : '');
                }
            }
            $childs = $users->paginate(20);
            $start_date = date("Y-m-d H:i:s",strtotime("-1 days"));
            $end_date = date("Y-m-d H:i:s");
            if($dates != null && $dates != ''){
                $dates_tmp = explode(' - ', $dates);
                $start_date = $dates_tmp[0] . " 00:00:00";
                $end_date = $dates_tmp[1] . " 23:59:59";
                $request->session()->put('dates', $dates);
            }

            $adjustments = [];
            foreach ($childs as $adj_user)
            {
                $adj = \VanguardLTE\DailySummary::adjustment($adj_user->id, $start_date, $end_date);
                $adjustments[] = $adj;
            }
            return view('backend.Default.adjustment.adjustment_partner', compact('adjustments', 'start_date', 'end_date', 'user', 'childs'));
        }
        public function adjustment_game(\Illuminate\Http\Request $request)
        {
            $categories = \VanguardLTE\Category::where('shop_id', 0);
            $saved_category = $request->category;
            if($saved_category != null && $saved_category != "0"){
                $category = $categories->where('id', $saved_category);
            }
            $categories = $categories->get();
            $adjustments = [];
            $game_name = $request->search;

            foreach ($categories as $cat)
            {
                $adj = [
                    'category' => $cat->title,
                    'total_win' => 0,
                    'total_bet' => 0,
                    'total_bet_count' => 0,
                    'total_deal' => 0,
                    'total_mileage' => 0,
                    'games' => []
                ];

                if ($cat->provider != null)
                {
                    $games = call_user_func('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($cat->provider) . 'Controller::getgamelist', $cat->href);
                    if ($games){
                        foreach ($games as $game)
                        {
                            if($game_name != null && $game_name != '' &&  strpos($game['name'], $game_name) === false){
                                continue;
                            }
                            $adj_game = [
                                'name' => $game['name'],
                                'total_win' => 0,
                                'total_bet' => 0,
                                'total_bet_count' => 0,
                                'total_deal' => 0,
                                'total_mileage' => 0,
                            ];
                            $adj['games'][] = $adj_game;
                        }
                    }
                }
                else
                {
                    $gameIds = \VanguardLTE\GameCategory::where('category_id', $cat->id)->pluck('game_id')->toArray();
                    $games = \VanguardLTE\Game::whereIn('id', $gameIds);
                    if($game_name != null && $game_name != ''){
                        $games = $games->where('name', 'LIKE', '%'.$game_name.'%');
                    }
                    $games = $games->get();
                    foreach ($games as $game)
                    {
                        $adj_game = [
                            'name' => $game->name,
                            'total_win' => 0,
                            'total_bet' => 0,
                            'total_bet_count' => 0,
                            'total_deal' => 0,
                            'total_mileage' => 0,
                        ];
                        $adj['games'][] = $adj_game;
                    }

                }
                $adjustments[] = $adj;
            }
            $totaladj = [
                'name' => '합계',
                'total_win' => 0,
                'total_bet' => 0,
                'total_bet_count' => 0,
                'total_deal' => 0,
                'total_mileage' => 0,
            ];

            $dates = $request->dates;
            $start_date = date("Y-m-d H:i:s",strtotime("-1 days"));
            $end_date = date("Y-m-d H:i:s");
            if($dates != null && $dates != ''){
                $dates_tmp = explode(' - ', $request->dates);
                $start_date = $dates_tmp[0] . " 00:00:00";
                $end_date = $dates_tmp[1] . " 23:59:59";
            }

            $shop_ids = auth()->user()->availableShops();
            if (count($shop_ids) == 0) {
                return redirect()->back()->withError('정산할 매장이 없습니다');
            }


            $query = 'SELECT game, SUM(bet) as totalbet, SUM(win) as totalwin, COUNT(*) as betcount FROM w_stat_game WHERE shop_id in ('. implode(',',$shop_ids) .') AND date_time <="'.$end_date .'" AND date_time>="'. $start_date. '" GROUP BY game';
            $stat_games = \DB::select($query);


            if(auth()->user()->hasRole(['admin','master'])){
                $partners = auth()->user()->childPartners();
                if (count($partners) == 0) {
                    return redirect()->back()->withError('정산할 부본사가 없습니다');
                }
                $query = 'SELECT game, 0 as total_deal, SUM(deal_profit) as total_mileage FROM w_deal_log WHERE type="partner" AND partner_id in (' . implode(',',$partners) . ') AND date_time <="'.$end_date .'" AND date_time>="'. $start_date. '" GROUP BY game';
                $deal_logs = \DB::select($query);
            }
            else if(auth()->user()->hasRole(['agent','distributor'])){
                $query = 'SELECT game, SUM(deal_profit) as total_deal, SUM(mileage) as total_mileage FROM w_deal_log WHERE type="partner" AND partner_id =' . auth()->user()->id . ' AND date_time <="'.$end_date .'" AND date_time>="'. $start_date. '" GROUP BY game';
                $deal_logs = \DB::select($query);

            }
            else if(auth()->user()->hasRole('manager')) {
                $query = 'SELECT game, SUM(deal_profit) as total_deal, SUM(mileage) as total_mileage FROM w_deal_log WHERE type="shop" AND shop_id =' . auth()->user()->shop_id . ' AND date_time <="'.$end_date .'" AND date_time>="'. $start_date. '" GROUP BY game';
                $deal_logs = \DB::select($query);
            }


            foreach($stat_games as $stat_game) {
                $bfound_game = false;
                foreach ($adjustments as $i => $adj)
                {
                    foreach ($adj['games'] as $j => $game)
                    {
                        $real_gamename = explode(' ', $stat_game->game)[0];
                        if (strpos($real_gamename, '_') !== false)
                        {
                            $real_gamename = explode('_', $real_gamename)[0];
                        }
                        if($real_gamename == $game['name'])
                        {
                            $game['total_bet'] = $game['total_bet'] + $stat_game->totalbet;
                            $game['total_win'] = $game['total_win'] + $stat_game->totalwin;
                            $game['total_bet_count'] = $game['total_bet_count'] + $stat_game->betcount;

                            $adj['total_bet'] = $adj['total_bet'] + $stat_game->totalbet;
                            $adj['total_win'] = $adj['total_win'] + $stat_game->totalwin;
                            $adj['total_bet_count'] = $adj['total_bet_count'] + $stat_game->betcount;

                            $totaladj['total_bet'] = $totaladj['total_bet'] + $stat_game->totalbet;
                            $totaladj['total_win'] = $totaladj['total_win'] + $stat_game->totalwin;
                            $totaladj['total_bet_count'] = $totaladj['total_bet_count'] + $stat_game->betcount;

                            $adj['games'][$j] = $game;
                            $bfound_game = true;
                            break;
                        }
                    }
                    if ($bfound_game){ 
                        $adjustments[$i] = $adj;
                        break; 
                    }
                }
            }

            foreach($deal_logs as $deal_log){
                $bfound_game = false;
                foreach ($adjustments as $i => $adj)
                {
                    foreach ($adj['games'] as $j => $game)
                    {
                        $real_gamename = explode(' ', $deal_log->game)[0];
                        if (strpos($real_gamename, '_') !== false)
                        {
                            $real_gamename = explode('_', $real_gamename)[0];
                        }
                        if($real_gamename == $game['name'])
                        {
                            $game['total_deal'] = $game['total_deal'] + $deal_log->total_deal;
                            $game['total_mileage'] = $game['total_mileage'] + $deal_log->total_mileage;

                            $adj['total_deal'] = $adj['total_deal'] + $deal_log->total_deal;
                            $adj['total_mileage'] = $adj['total_mileage'] + $deal_log->total_mileage;

                            $totaladj['total_deal'] = $totaladj['total_deal'] + $deal_log->total_deal;
                            $totaladj['total_mileage'] = $totaladj['total_mileage'] + $deal_log->total_mileage;
                            $bfound_game = true;
                            $adj['games'][$j] = $game;
                            break;
                        }
                    }
                    if ($bfound_game){ 
                        $adjustments[$i] = $adj;
                        break; 
                    }
                }
            }

            //remove total bet is zero
            $filtered_adjustment = [];

            foreach ($adjustments as $index => $adj)
            {
                if ($adj['total_bet'] > 0)
                {
                    $filtered_games = [];
                    foreach ($adj['games'] as $game)
                    {
                        if ($game['total_bet'] > 0)
                        {
                            $filtered_games[] = $game;
                        }
                    }
                    usort($filtered_games, function($element1, $element2)
                    {
                        return $element2['total_bet'] - $element1['total_bet'];
                    });

                    $adj['games'] = $filtered_games;
                    $filtered_adjustment[] = $adj;
                }
            }

            usort($filtered_adjustment, function($element1, $element2)
            {
                return $element2['total_bet'] - $element1['total_bet'];
            });

            if (!auth()->user()->hasRole('admin'))
            {
                //merge pragmatic and pragmatic play games if not admin
                $pp_adj = null;
                $pragmatic_adj = null;
                $pp_index = 0;
                $pragmatic_index = 0;
                foreach ($filtered_adjustment as $index => $adj)
                {
                    if ($adj['category'] == 'Pragmatic Play')
                    {
                        $pp_adj = $adj;
                        $pp_index = $index;
                    }
                    if ($adj['category'] == 'Pragmatic')
                    {
                        $pragmatic_adj = $adj;
                        $pragmatic_index = $index;
                    }
                }
                if ($pp_adj && $pragmatic_adj)
                {
                    $pp_adj['games'] = array_merge($pp_adj['games'] , $pragmatic_adj['games']);
                    $pp_adj['total_win'] = $pp_adj['total_win'] + $pragmatic_adj['total_win'];
                    $pp_adj['total_bet'] = $pp_adj['total_bet'] + $pragmatic_adj['total_bet'];
                    $pp_adj['total_bet_count'] = $pp_adj['total_bet_count'] + $pragmatic_adj['total_bet_count'];
                    $pp_adj['total_deal'] = $pp_adj['total_deal'] + $pragmatic_adj['total_deal'];
                    $pp_adj['total_mileage'] = $pp_adj['total_mileage'] + $pragmatic_adj['total_mileage'];
                    $filtered_adjustment[$pp_index] = $pp_adj;
                    unset($filtered_adjustment[$pragmatic_index]);
                }
            }

            $filtered_adjustment[] = [
                'category' => null,
                'total_win' => 0,
                'total_bet' => 0,
                'total_bet_count' => 0,
                'total_deal' => 0,
                'total_mileage' => 0,
                'games' => [
                    $totaladj
                ]
            ];

            $categories = \VanguardLTE\Category::where('shop_id', 0)->get();

            return view('backend.Default.adjustment.adjustment_game', compact('filtered_adjustment', 'categories', 'saved_category', 'start_date', 'end_date'));

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
            return redirect()->route('backend.adjustment_shift')->withSuccess(trans('app.open_shift_started'));
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
            return redirect()->route('backend.adjustment_shift')->withSuccess(trans('app.open_shift_started'));
        }

        public function in_out_request(\Illuminate\Http\Request $request) 
        {
            $user = auth()->user();

            $in_out_logs = \VanguardLTE\WithdrawDeposit::where('user_id', $user->id);
            
            if ($user->hasRole('master'))
            {
                $master = $user->referral; // it is admin
            }
            else
            {
                $master = $user->referral;
                while ($master!=null && !$master->hasRole('master'))
                {
                    $master = $master->referral;
                }
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

        public function in_out_manage(\Illuminate\Http\Request $request) 
        {
            if (\Session::get('isCashier'))
            {
                $childs = auth()->user()->childPartners();
                $in_out_logs = \VanguardLTE\WithdrawDeposit::where('status', 0)->whereIn('payeer_id', $childs);
            }
            else
            {
                $in_out_logs = \VanguardLTE\WithdrawDeposit::where([
                    'payeer_id'=> auth()->user()->id,
                    'status'=> 0,
                ]);
            }
            $in_out_logs = $in_out_logs->orderBy('created_at', 'desc')->get();

            $in_out_logs = $in_out_logs->paginate(20);

            return view('backend.Default.adjustment.in_out_manage', compact('in_out_logs'));
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

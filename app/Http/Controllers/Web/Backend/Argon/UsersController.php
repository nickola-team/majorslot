<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend\Argon
{
    class UsersController extends \VanguardLTE\Http\Controllers\Controller
    {
        private $users = null;
        private $max_users = 10000;
        public function __construct(\VanguardLTE\Repositories\User\UserRepository $users)
        {
            $this->middleware('auth');
            $this->middleware('permission:access.admin.panel');
            $this->users = $users;
        }

        public function agent_child(\Illuminate\Http\Request $request)
        {
            $user = auth()->user();
            $availablePartners = $user->hierarchyPartners();
            $child_id = $request->id;
            $users = [];
            if (in_array($child_id, $availablePartners))
            {
                $users = \VanguardLTE\User::where('parent_id', $child_id)->get();
            }
            return view('backend.argon.agent.partials.childs', compact('users', 'child_id'));
        }

        public function agent_create(\Illuminate\Http\Request $request)
        {
            return view('backend.argon.agent.create');
        }

        public function agent_store(\VanguardLTE\Http\Requests\User\CreateUserRequest $request)
        {
            $data = $request->all() + ['status' => \VanguardLTE\Support\Enum\UserStatus::ACTIVE];

            if( !$request->parent_id ) 
            {
                $data['parent_id'] = \Illuminate\Support\Facades\Auth::user()->id;
            }
            $role_id = (isset($data['role_id']) && $data['role_id'] < auth()->user()->role_id ? $data['role_id'] : auth()->user()->role_id - 1);
            $data['role_id'] = $role_id;
            $role = \jeremykenedy\LaravelRoles\Models\Role::find($role_id);
            if( (auth()->user()->hasRole('distributor') && $role->slug == 'manager' || auth()->user()->hasRole('manager') && $role->slug == 'cashier') && \VanguardLTE\User::where([
                'role_id' => $role->id, 
                'shop_id' => $request->shop_id
            ])->count() ) 
            {
                return redirect()->route(config('app.admurl').'.user.list')->withErrors([trans('app.only_1', ['type' => $role->slug])]);
            }

            if( $data['role_id'] == 1 || $data['role_id'] == 4 || $data['role_id'] == 5 || $data['role_id'] == 6) //user, distributor, agent, master
            {
                $parent = auth()->user();
                if ($data['role_id'] == 1)
                {
                    $parent = auth()->user()->shop;
                }
                if (isset($data['deal_percent']) && $parent!=null &&  $parent->deal_percent < $data['deal_percent'])
                {
                    return redirect()->back()->withErrors(['딜비는 상위파트너보다 클수 없습니다']);
                }
                if (isset($data['table_deal_percent']) && $parent!=null && $parent->table_deal_percent < $data['table_deal_percent'])
                {
                    return redirect()->back()->withErrors(['라이브딜비는 상위파트너보다 클수 없습니다']);
                }
                if ($data['role_id'] > 1 && $parent!=null && !$parent->isInoutPartner() && $parent->ggr_percent < $data['ggr_percent'])
                {
                    return redirect()->back()->withErrors(['죽장퍼센트는 상위파트너보다 클수 없습니다']);
                }
            }

            $user = $this->users->create($data);
            $user->detachAllRoles();
            $user->attachRole($role);
            if( $request->shop_id && $request->shop_id > 0 && !empty($request->shop_id) ) 
            {
                \VanguardLTE\ShopUser::create([
                    'shop_id' => $request->shop_id, 
                    'user_id' => $user->id
                ]);
            }
            if( !$user->shop_id && $user->hasRole([
                'cashier', 
                'user'
            ]) ) 
            {
                $shops = $user->shops(true);
                if( count($shops) ) 
                {
                    $shop_id = $shops->first();
                    $user->update(['shop_id' => $shop_id]);
                }
            }
            if ($role_id<3) {
                return redirect()->route(config('app.admurl').'.user.list')->withSuccess(trans('app.user_created'));
            }

            //create shift for partners
            $type = 'partner';

            \VanguardLTE\OpenShift::create([
                'start_date' => \Carbon\Carbon::now(), 
                'user_id' => $user->id, 
                'shop_id' => 0,
                'old_total' => 0,
                'deal_profit' => 0,
                'mileage' => 0,
                'type' => $type
            ]);
            return view('backend.argon.agent.create');
        }

        public function agent_list(\Illuminate\Http\Request $request)
        {
            $user = auth()->user();
            if ($request->user != '' || $request->role != '')
            {
                $childPartners = $user->hierarchyPartners();
            }
            else
            {
                $childPartners = $user->childPartners();
            }

            $users = \VanguardLTE\User::whereIn('id', $childPartners);

            if ($request->user != '')
            {
                $users = $users->where('username', 'like', '%' . $request->user . '%');
            }

            if ($request->role != '')
            {
                $users = $users->where('role_id', $request->role);
            }
            
            $usersum = (clone $users)->get();
            $sum = 0;
            $count = 0;
            foreach ($usersum as $u)
            {
                $sum = $sum + $u->childBalanceSum();
                $count = $count + count($u->hierarchyPartners());
            }

            $total = [
                'count' => $users->count() + $count,
                'balance' => $users->sum('balance'),
                'childbalance' => $sum
            ];
            

            $users = $users->paginate(20);
            return view('backend.argon.agent.list', compact('users','total'));
        }

        public function agent_deal_stat(\Illuminate\Http\Request $request)
        {
            $statistics = \VanguardLTE\DealLog::select('deal_log.*')->orderBy('deal_log.date_time', 'DESC');
            $user = auth()->user();
            $availablePartners = $user->availableUsers();
            

            $start_date = date("Y-m-d H:i:s", strtotime("-1 hours"));
            $end_date = date("Y-m-d H:i:s");

            if ($request->dates != '')
            {
                // $dates = explode(' - ', $request->dates);
                $start_date = preg_replace('/T/',' ', $request->dates[0]);
                $end_date = preg_replace('/T/',' ', $request->dates[1]);            
            }
            $statistics = $statistics->where('deal_log.date_time', '>=', $start_date);
            $statistics = $statistics->where('deal_log.date_time', '<=', $end_date );

            $statistics = $statistics->join('users', 'users.id', '=', 'deal_log.partner_id');

            if ($request->user != '')
            {
                $statistics = $statistics->whereIn('deal_log.partner_id', $availablePartners);
                $statistics = $statistics->where('users.username', 'like', '%' . $request->user . '%');
            }
            else
            {
                $statistics = $statistics->where('deal_log.partner_id', $user->id);
            }

            if ($request->player != '')
            {
                $playerIds = \VanguardLTE\User::where('username', 'like', '%' . $request->player . '%')->pluck('id')->toArray();
                $statistics = $statistics->whereIn('deal_log.user_id', $playerIds);
            }

            if ($request->game != '')
            {
                $statistics = $statistics->where('deal_log.game', 'like', '%'. $request->game . '%');
            }

            $total = [
                'bet' => (clone $statistics)->sum('bet'),
                'win' => (clone $statistics)->sum('win'),
                'deal' => (clone $statistics)->sum('deal_log.deal_profit') - (clone $statistics)->sum('deal_log.mileage'),
                'ggr' => (clone $statistics)->sum('deal_log.ggr_profit') - (clone $statistics)->sum('deal_log.ggr_mileage'),
            ];

            $statistics = $statistics->paginate(20);
            return view('backend.argon.agent.deal', compact('statistics', 'total'));
        }

        public function agent_transaction(\Illuminate\Http\Request $request)
        {
            $statistics = \VanguardLTE\Transaction::select('transactions.*')->orderBy('transactions.created_at', 'DESC');
            $user = auth()->user();
            $availablePartners = $user->hierarchyPartners();
            $availablePartners[] = $user->id;
            $statistics = $statistics->whereIn('user_id', $availablePartners);

            $start_date = date("Y-m-1 0:0:0");
            $end_date = date("Y-m-d 23:59:59");

            if ($request->dates != '')
            {
                // $dates = explode(' - ', $request->dates);
                $start_date = preg_replace('/T/',' ', $request->dates[0]);
                $end_date = preg_replace('/T/',' ', $request->dates[1]);            
            }
            $statistics = $statistics->where('transactions.created_at', '>=', $start_date);
            $statistics = $statistics->where('transactions.created_at', '<=', $end_date );

            $statistics = $statistics->join('users', 'users.id', '=', 'transactions.user_id');

            if ($request->role != '')
            {
                $statistics = $statistics->where('users.role_id', $request->role);
            }
            if ($request->user != '')
            {
                $statistics = $statistics->where('users.username', 'like', '%' . $request->user . '%');
            }

            if ($request->admin != '')
            {
                $payeerIds = \VanguardLTE\User::where('username', 'like', '%' . $request->admin . '%')->pluck('id')->toArray();
                $statistics = $statistics->whereIn('transactions.payeer_id', $payeerIds);
            }

            if ($request->type != '')
            {
                $statistics = $statistics->where('transactions.type',  $request->type);
            }

            if ($request->mode != '')
            {
                if ($request->mode == 'manual')
                {
                    $statistics = $statistics->whereIsNull('transactions.request_id');
                }
                else
                {
                    $statistics = $statistics->whereNotNull('transactions.request_id');
                }
            }

            $total = [
                'add' => (clone $statistics)->where(['type'=>'add'])->sum('summ'),
                'out' => (clone $statistics)->where(['type'=>'out'])->sum('summ'),
                'deal_out' => (clone $statistics)->where(['type'=>'deal_out'])->sum('summ'),
                'ggr_out' => (clone $statistics)->where(['type'=>'ggr_out'])->sum('summ'),
            ];

            $statistics = $statistics->paginate(20);
            return view('backend.argon.agent.transaction', compact('statistics', 'total'));
        }

        public function player_list(\Illuminate\Http\Request $request)
        {
            $user = auth()->user();
            $availableUsers = $user->hierarchyUsersOnly();

            $users = \VanguardLTE\User::whereIn('id', $availableUsers);

            if ($request->user != '')
            {
                $users = $users->where('username', 'like', '%' . $request->user . '%');
            }

            if ($request->shop != '')
            {
                $shops = \VanguardLTE\Shop::where('name', 'like', '%'.$request->shop.'%')->whereIn('id', auth()->user()->availableShops())->pluck('id')->toArray();
                if (count($shops) > 0){
                    $users = $users->whereIn('shop_id',$shops);
                }
                else
                {
                    $users = $users->where('shop_id',-1); //show nothing
                }
            }

            $total = [
                'count' => $users->count(),
                'balance' => $users->sum('balance'),
            ];
            
            $users = $users->paginate(20);
            return view('backend.argon.player.list', compact('users','total'));
        }

        public function player_transaction(\Illuminate\Http\Request $request)
        {
            $statistics = \VanguardLTE\Transaction::select('transactions.*')->orderBy('transactions.created_at', 'DESC');
            $user = auth()->user();
            $availableUsers = $user->hierarchyUsersOnly();
            $statistics = $statistics->whereIn('user_id', $availableUsers);

            $start_date = date("Y-m-1 0:0:0");
            $end_date = date("Y-m-d 23:59:59");

            if ($request->dates != '')
            {
                // $dates = explode(' - ', $request->dates);
                $start_date = preg_replace('/T/',' ', $request->dates[0]);
                $end_date = preg_replace('/T/',' ', $request->dates[1]);            
            }
            $statistics = $statistics->where('transactions.created_at', '>=', $start_date);
            $statistics = $statistics->where('transactions.created_at', '<=', $end_date );

            $statistics = $statistics->join('users', 'users.id', '=', 'transactions.user_id');

            if ($request->role != '')
            {
                $statistics = $statistics->where('users.role_id', $request->role);
            }
            if ($request->user != '')
            {
                $statistics = $statistics->where('users.username', 'like', '%' . $request->user . '%');
            }

            if ($request->admin != '')
            {
                $payeerIds = \VanguardLTE\User::where('username', 'like', '%' . $request->admin . '%')->pluck('id')->toArray();
                $statistics = $statistics->whereIn('transactions.payeer_id', $payeerIds);
            }

            if ($request->type != '')
            {
                $statistics = $statistics->where('transactions.type',  $request->type);
            }

            $total = [
                'add' => (clone $statistics)->where(['type'=>'add'])->sum('summ'),
                'out' => (clone $statistics)->where(['type'=>'out'])->sum('summ'),
            ];

            $statistics = $statistics->paginate(20);
            return view('backend.argon.player.transaction', compact('statistics', 'total'));
        }

        public function player_game_stat(\Illuminate\Http\Request $request)
        {
            $statistics = \VanguardLTE\StatGame::select('stat_game.*')->orderBy('stat_game.date_time', 'DESC');
            $user = auth()->user();
            $availableUsers = $user->hierarchyUsersOnly();

            $start_date = date("Y-m-d H:i:s", strtotime("-1 hours"));
            $end_date = date("Y-m-d H:i:s");

            if ($request->dates != '')
            {
                // $dates = explode(' - ', $request->dates);
                $start_date = preg_replace('/T/',' ', $request->dates[0]);
                $end_date = preg_replace('/T/',' ', $request->dates[1]);            
            }
            $statistics = $statistics->where('stat_game.date_time', '>=', $start_date);
            $statistics = $statistics->where('stat_game.date_time', '<=', $end_date );

            $statistics = $statistics->join('users', 'users.id', '=', 'stat_game.user_id');

            if ($request->player != '')
            {
                $statistics = $statistics->where('users.username', 'like', '%' . $request->player . '%');
            }

            if ($request->game != '')
            {
                $statistics = $statistics->where('stat_game.game', 'like', '%'. $request->game . '%');
            }

            $total = [
                'bet' => (clone $statistics)->sum('bet'),
                'win' => (clone $statistics)->sum('win'),
            ];

            $statistics = $statistics->paginate(20);
            return view('backend.argon.player.game', compact('statistics', 'total'));
        }

    }

}

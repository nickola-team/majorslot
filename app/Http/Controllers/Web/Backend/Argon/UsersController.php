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

        public function balance(\Illuminate\Http\Request $request)
        {
            $type = 'add';
            return view('backend.argon.common.balance',compact('type'));
        }
    }

}

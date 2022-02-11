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

        public function agent_list()
        {

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
    }

}

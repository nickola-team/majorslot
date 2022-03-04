<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend\Argon
{
    class DWController extends \VanguardLTE\Http\Controllers\Controller
    {
        public function __construct(\VanguardLTE\Repositories\User\UserRepository $users)
        {
            $this->middleware('auth');
            $this->middleware('permission:access.admin.panel');
        }

        public function addrequest(\Illuminate\Http\Request $request)
        {
            return view('backend.argon.dw.addrequest');
        }

        public function outrequest(\Illuminate\Http\Request $request)
        {
            return view('backend.argon.dw.outrequest');
        }

        public function dealconvert(\Illuminate\Http\Request $request)
        {
            return view('backend.argon.dw.dealconvert');
        }
        public function history(\Illuminate\Http\Request $request)
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
                $in_out_logs = \VanguardLTE\WithdrawDeposit::where(['user_id' => auth()->user()->id])->whereIn('status', [\VanguardLTE\WithdrawDeposit::DONE, \VanguardLTE\WithdrawDeposit::CANCEL])->orderBy('created_at','desc');
            }

            if( $request->type != '' ) 
            {
                $in_out_logs = $in_out_logs->where('type', $request->type);
            }

            $start_date = date("Y-m-1 0:0:0");
            $end_date = date("Y-m-d 23:59:59");
            
            if( $request->dates != '' ) 
            {
                $start_date = preg_replace('/T/',' ', $request->dates[0]);
                $end_date = preg_replace('/T/',' ', $request->dates[1]);        
                if (!auth()->user()->hasRole('admin'))
                {
                    $d = strtotime($start_date);
                    if ($d < strtotime("-30 days"))
                    {
                        $start_date = date("Y-m-d H:i",strtotime("-30 days"));
                    }
                }
            }
            
            $in_out_logs = $in_out_logs->where('created_at', '>=', $start_date);
            $in_out_logs = $in_out_logs->where('created_at', '<=', $end_date);
            
            if( $request->user != '' ) 
            {
                $availableUsers = auth()->user()->availableUsers();
                $user_ids = \VanguardLTE\User::whereIn('id', $availableUsers)->where('username','like', '%' . $request->user . '%')->pluck('id')->toArray();
                if (count($user_ids) > 0){
                    $in_out_logs = $in_out_logs->whereIn('user_id', $user_ids);
                }
                else
                {
                    $in_out_logs = $in_out_logs->where('user_id', -1);
                }
            }


            $total = [
                'add' => (clone $in_out_logs)->where(['type'=>'add', 'status'=>\VanguardLTE\WithdrawDeposit::DONE])->sum('sum'),
                'out' => (clone $in_out_logs)->where(['type'=>'out', 'status'=>\VanguardLTE\WithdrawDeposit::DONE])->sum('sum'),
            ];

            $in_out_logs = $in_out_logs->paginate(20);

            return view('backend.argon.dw.history', compact('in_out_logs','total'));
        }

        public function addmanage(\Illuminate\Http\Request $request)
        {
            return view('backend.argon.common.balance',compact('type', 'user', 'url'));
        }
        public function outmanage(\Illuminate\Http\Request $request)
        {
            return view('backend.argon.common.balance',compact('type', 'user', 'url'));
        }

    }

}

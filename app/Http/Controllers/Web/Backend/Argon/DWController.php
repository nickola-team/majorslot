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
            $user = auth()->user();
            $in_out_logs = \VanguardLTE\WithdrawDeposit::where('user_id', $user->id)->orderby('created_at', 'desc')->take(10)->get();
            $master = $user->referral;
            while ($master!=null && !$master->isInoutPartner())
            {
                $master = $master->referral;
            }
            $needRequestAccount = true;
            if (!$master && $master->bank_name == 'MESSAGE')
            {
                $needRequestAccount = false;
            }
            return view('backend.argon.dw.addrequest', compact('in_out_logs', 'needRequestAccount'));
        }

        public function outrequest(\Illuminate\Http\Request $request)
        {
            $user = auth()->user();
            $in_out_logs = \VanguardLTE\WithdrawDeposit::where('user_id', $user->id)->orderby('created_at', 'desc')->take(10)->get();
            return view('backend.argon.dw.outrequest', compact('in_out_logs'));
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
            else if (auth()->user()->hasRole('group'))
            {
                $comasters = auth()->user()->childPartners();
                $in_out_logs = \VanguardLTE\WithdrawDeposit::whereIn('payeer_id', $comasters)->whereIn('status', [\VanguardLTE\WithdrawDeposit::DONE, \VanguardLTE\WithdrawDeposit::CANCEL])->orderBy('created_at','desc');
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

            $start_date = date("Y-m-d 0:0:0");
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
            
            $in_out_logs = $in_out_logs->where('updated_at', '>=', $start_date);
            $in_out_logs = $in_out_logs->where('updated_at', '<=', $end_date);
            
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

            if ($request->partner != '')
            {
                $availablePartners = auth()->user()->hierarchyPartners();
                $partners = \VanguardLTE\User::whereIn('id', $availablePartners)->orderBy('role_id','desc')->where('username','like', '%' . $request->partner . '%');
                if ($request->role != '')
                {
                    $partners = $partners->where('role_id', $request->role);
                }
                $partners = $partners->first();
                if ($partners) {
                    $childsPartners = $partners->hierarchyPartners();
                    $childsPartners[] = $partners->id;
                    $in_out_logs = $in_out_logs->whereIn('user_id', $childsPartners);
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
        public function process(\Illuminate\Http\Request $request)
        {
            $requestid = $request->id;
            $in_out = \VanguardLTE\WithdrawDeposit::where('id', $requestid)->where('payeer_id', auth()->user()->id)->whereIn('status', [\VanguardLTE\WithdrawDeposit::REQUEST, \VanguardLTE\WithdrawDeposit::WAIT ])->first();
            if (!$in_out)
            {
                return redirect()->back()->withErrors(['비정상적인 접근입니다.']);
            }
            return view('backend.argon.dw.dw', compact('in_out'));
        }

        public function rejectDW(\Illuminate\Http\Request $request){
            
            $in_out_id = $request->id;
            $transaction = \VanguardLTE\WithdrawDeposit::where('id', $in_out_id)->first();
            if($transaction == null){
                return redirect()->back()->withErrors(['유효하지 않은 조작입니다.']);
            }
            if ($transaction->status!=\VanguardLTE\WithdrawDeposit::REQUEST && $transaction->status!=\VanguardLTE\WithdrawDeposit::WAIT )
            {
                return redirect()->back()->withErrors(['이미 처리된 신청내역입니다.']);
            }
            
            $amount = $transaction->sum;
            $type = $transaction->type;
            $requestuser = \VanguardLTE\User::where('id', $transaction->user_id)->first();
            if ($requestuser){
                if ($requestuser->hasRole('manager')) // for shops
                {
                    $shop = \VanguardLTE\Shop::where('id', $transaction->shop_id)->first();
                    if($type == 'out'){
                        $shop->update([
                            'balance' => $shop->balance + $amount
                        ]);
                        $open_shift = \VanguardLTE\OpenShift::where([
                            'shop_id' => $shop->id, 
                            'end_date' => null,
                            'type' => 'shop'
                        ])->first();
                        if( $open_shift ) 
                        {
                            $open_shift->decrement('balance_out', $amount);
                        }
                    }
                    else if($type == 'deal_out'){
                        $shop->update([
                            'deal_balance' => $shop->deal_balance + $amount
                        ]);
                        $open_shift = \VanguardLTE\OpenShift::where([
                            'shop_id' => $shop->id, 
                            'end_date' => null,
                            'type' => 'shop'
                        ])->first();
                        if( $open_shift ) 
                        {
                            $open_shift->decrement('balance_out', $amount);
                        }
                    }

                }
                else
                {
                    if($type == 'out'){
                        $requestuser->update([
                            'balance' => $requestuser->balance + $amount,
                            'total_out' => $requestuser->total_out - $amount,
                        ]);
                        $open_shift = \VanguardLTE\OpenShift::where([
                            'user_id' => $requestuser->id, 
                            'end_date' => null,
                            'type' => 'partner'
                        ])->first();
                        if( $open_shift ) 
                        {
                            $open_shift->decrement('balance_out', $amount);
                        }
                    }

                }
            }
            $transaction->update([
                'status' => 2
            ]);
           return redirect()->back()->withSuccess(['조작이 성공적으로 진행되었습니다.']);
        }
        public function processDW(\Illuminate\Http\Request $request){
            $in_out_id = $request->id;
            $transaction = \VanguardLTE\WithdrawDeposit::where('id', $in_out_id)->where('payeer_id', auth()->user()->id)->first();
            if (!$transaction)
            {
                return redirect()->back()->withErrors(['비정상적인 접근입니다.']);
            }
            $amount = $transaction->sum;
            $type = $transaction->type;
            $requestuser = \VanguardLTE\User::where('id', $transaction->user_id)->first();
            $user = auth()->user();

            if (!$user)
            {
                return redirect()->back()->withErrors(['본사를 찾을수 없습니다.']);
            }
            if ($transaction->status!=\VanguardLTE\WithdrawDeposit::REQUEST && $transaction->status!=\VanguardLTE\WithdrawDeposit::WAIT )
            {
                return redirect()->back()->withErrors(['이미 처리된 신청내역입니다.']);
            }
            if ($requestuser->hasRole('user') && $requestuser->playing_game != null)
            {
                return redirect()->back()->withErrors(['해당 유저가 게임중이므로 충환전처리를 할수 없습니다.']);
            }
            if ($requestuser->hasRole('manager')) // for shops
            {
                $shop = \VanguardLTE\Shop::lockforUpdate()->where('id', $transaction->shop_id)->get()->first();
                if($type == 'add'){
                    if($user->balance < $amount) {
                        if (auth()->user()->hasRole('comaster'))
                        {
                            return redirect()->back()->withErrors([$user->username. '본사의 보유금액이 충분하지 않습니다.']);
                        }
                        else
                        {
                            return redirect()->back()->withErrors(['보유금액이 충분하지 않습니다.']);
                        }
                    }

                    $user->update(
                        ['balance' => $user->balance - $amount]
                    );
                    $old = $shop->balance;
                    $shop->update([
                        'balance' => $shop->balance + $amount
                    ]);

                    $open_shift = \VanguardLTE\OpenShift::where([
                        'user_id' => $user->id, 
                        'end_date' => null,
                        'type' => 'partner'
                    ])->first();
                    if( $open_shift ) 
                    {
                        $open_shift->increment('money_in', $amount);
                    }
                    $open_shift = \VanguardLTE\OpenShift::where([
                        'shop_id' => $shop->id, 
                        'end_date' => null,
                        'type' => 'shop'
                    ])->first();
                    if( $open_shift ) 
                    {
                        $open_shift->increment('balance_in', $amount);
                    }

                }
                else if($type == 'out'){
                    $user->update(
                        ['balance' => $user->balance + $amount]
                    );
                    $open_shift = \VanguardLTE\OpenShift::where([
                        'user_id' => $user->id, 
                        'end_date' => null,
                        'type' => 'partner'
                    ])->first();
                    if( $open_shift ) 
                    {
                        $open_shift->increment('money_out', $amount);
                    }
                    $old = $shop->balance + $amount;

                }
                else if($type == 'deal_out'){
                    //out balance from master
                    $user->update(
                        ['balance' => $user->balance - $amount]
                    ); 
                    $open_shift = \VanguardLTE\OpenShift::where([
                        'user_id' => $user->id, 
                        'end_date' => null,
                        'type' => 'partner'
                    ])->first();
                    if( $open_shift ) 
                    {
                        $open_shift->increment('convert_deal', $amount);
                    }
                    $old = $shop->balance;
                }

                $transaction->update([
                    'status' => 1
                ]);
                $shop = $shop->fresh();
                $user = $user->fresh();

                \VanguardLTE\ShopStat::create([
                    'user_id' => $user->id,
                    'type' => $type,
                    'sum' => $amount,
                    'old' => $old,
                    'new' => $shop->balance,
                    'balance' => $user->balance,
                    'request_id' => $transaction->id,
                    'shop_id' => $transaction->shop_id,
                    'date_time' => \Carbon\Carbon::now()
                ]);

                //create another transaction for mananger account
                \VanguardLTE\Transaction::create([
                    'user_id' => $requestuser->id, 
                    'payeer_id' => $user->id, 
                    'type' => $type, 
                    'summ' => abs($amount), 
                    'old' => $old,
                    'new' => $shop->balance,
                    'balance' => $user->balance,
                    'request_id' => $transaction->id,
                    'shop_id' => $transaction->shop_id,
                ]);
            }
            else // for partners
            {
                if($type == 'add'){
                    $result = $requestuser->addBalance('add', $amount, $user, 0, $transaction->id);
                    $result = json_decode($result, true);
                    if ($result['status'] == 'error')
                    {
                        return redirect()->back()->withErrors($result['message']);
                    }
                }
                else if($type == 'out'){
                    $user->update(
                        ['balance' => $user->balance + $amount]
                    );
                    $open_shift = \VanguardLTE\OpenShift::where([
                        'user_id' => $user->id, 
                        'end_date' => null,
                        'type' => 'partner'
                    ])->first();
                    if( $open_shift ) 
                    {
                        $open_shift->increment('money_out', $amount);
                    }
                    $old = $requestuser->balance + $amount;
                    $user = $user->fresh();
                    \VanguardLTE\Transaction::create([
                        'user_id' => $transaction->user_id,
                        'payeer_id' => $user->id,
                        'system' => $user->username,
                        'type' => $type,
                        'summ' => $amount,
                        'old' => $old,
                        'new' => $requestuser->balance,
                        'balance' => $user->balance,
                        'request_id' => $transaction->id,
                        'shop_id' => $transaction->shop_id,
                        'created_at' => \Carbon\Carbon::now(),
                        'updated_at' => \Carbon\Carbon::now()
                    ]);
                }

                $transaction->update([
                    'status' => 1
                ]);
            }
            if ($type == 'add'){
                return redirect()->to(argon_route('argon.dw.addmanage'))->withSuccess(['조작이 성공적으로 진행되었습니다.']);
            }
            else
            {
                return redirect()->to(argon_route('argon.dw.outmanage'))->withSuccess(['조작이 성공적으로 진행되었습니다.']);
            }
        }

        public function addmanage(\Illuminate\Http\Request $request)
        {
            $type = 'add';
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
                ])->where('type', $type)->whereIn('status', [\VanguardLTE\WithdrawDeposit::DONE, \VanguardLTE\WithdrawDeposit::CANCEL])->orderBy('created_at','desc')->take(20);
            }
            else
            {
                return redirect()->back()->withErrors('접근권한이 없습니다.');
            }
            $total = [
                'add' => $in_out_request->sum('sum'),
                'out' => $in_out_wait->sum('sum'),
            ];
            $in_out_request = $in_out_request->orderBy('created_at', 'desc');
            $in_out_request = $in_out_request->paginate(20);
            $in_out_wait = $in_out_wait->orderBy('created_at', 'desc');
            $in_out_wait = $in_out_wait->paginate(20);
            $in_out_logs = $in_out_logs->get();
            return view('backend.argon.dw.addmanage', compact('in_out_request','in_out_wait','in_out_logs','total','type'));
        }
        public function outmanage(\Illuminate\Http\Request $request)
        {
            $type = 'out';
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
                ])->where('type', $type)->whereIn('status', [\VanguardLTE\WithdrawDeposit::DONE, \VanguardLTE\WithdrawDeposit::CANCEL])->orderBy('created_at','desc')->take(20);
            }
            else
            {
                return redirect()->back()->withErrors('접근권한이 없습니다.');
            }
            $total = [
                'add' => $in_out_request->sum('sum'),
                'out' => $in_out_wait->sum('sum'),
            ];
            $in_out_request = $in_out_request->orderBy('created_at', 'desc');
            $in_out_request = $in_out_request->paginate(20);
            $in_out_wait = $in_out_wait->orderBy('created_at', 'desc');
            $in_out_wait = $in_out_wait->paginate(20);
            $in_out_logs = $in_out_logs->get();
            return view('backend.argon.dw.outmanage', compact('in_out_request','in_out_wait','in_out_logs','total','type'));
        }

    }

}

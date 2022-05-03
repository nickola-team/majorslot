<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend\Argon
{
    class CommonController extends \VanguardLTE\Http\Controllers\Controller
    {
        public function __construct(\VanguardLTE\Repositories\User\UserRepository $users)
        {
            $this->middleware('auth');
            $this->middleware('permission:access.admin.panel');
        }

        public function balance(\Illuminate\Http\Request $request)
        {
            $type = 'add';
            if ($request->type != '')
            {
                $type = $request->type;
            }
            $userid = -1;
            if ($request->id != '')
            {
                $userid = $request->id;
            }
            
            $availableUsers = auth()->user()->availableUsers();
            if (!in_array($userid, $availableUsers))
            {
                return redirect()->back()->withErrors(['유저를 찾을수 없습니다.']);
            }
            $user = \VanguardLTE\User::find($userid);
            if (!$user)
            {
                return redirect()->back()->withErrors(['유저를 찾을수 없습니다.']);
            }
            $url = $request->url;
            
            return view('backend.argon.common.balance',compact('type', 'user', 'url'));
        }

        public function updateBalance(\Illuminate\Http\Request $request)
        {
            $data = $request->all();
            if( !array_get($data, 'type') ) 
            {
                $data['type'] = 'add';
            }
            $user = \VanguardLTE\User::lockForUpdate()->find($request->user_id);
            if (!$user)
            {
                return redirect()->back()->withErrors(['유저를 찾을수 없습니다.']);
            }

            if (!in_array($user->id, auth()->user()->availableUsers()))
            {
                return redirect()->back()->withErrors(['유저를 찾을수 없습니다.']);
            }

            if ($user->playing_game != null )
            {
                return redirect()->back()->withErrors(['게임중에는 충환전을 할수 없습니다.']);
            }

            $summ = abs(str_replace(',','',$request->amount));
            if ($summ == 0 && $request->all != '1' )
            {
                return redirect()->back()->withErrors(['유효하지 않은 금액입니다']);
            }

            if ($user->hasRole('manager')) // add shop balance
            {

                if( \Auth::user()->hasRole([
                    'master', 
                    'agent', 
                    'distributor', 
                    'manager'
                ]) && $data['type'] == 'out') 
                {
                    return redirect()->back()->withErrors(['허용되지 않은 조작입니다.']);
                }

                $shop = $user->shop;
                if( $request->all && $request->all == '1' ) 
                {
                    $summ = $shop->balance;
                }
                $payer = auth()->user();
                if( $data['type'] == 'add' && (!$payer->hasRole('admin') && $payer->balance < $summ  ) )
                {
                    return redirect()->back()->withErrors([trans('app.not_enough_money_in_the_user_balance', [
                        'name' => $user->username, 
                        'balance' => $user->balance
                    ])]);
                }
                if( $data['type'] == 'out' && $shop->balance < $summ  ) 
                {
                    return redirect()->back()->withErrors([trans('app.not_enough_money_in_the_shop', [
                        'name' => $shop->name, 
                        'balance' => $shop->balance
                    ])]);
                }

                $sum = ($request->type == 'out' ? -1 * $summ  : $summ );

                if (!$payer->hasRole('admin')) {
                    $payer->update([
                        'balance' => $payer->balance - $sum, 
                        'count_balance' => $payer->count_balance - $sum
                    ]);
                    $payer = $payer->fresh();
                }

                $old = $shop->balance;
                $shop->update(['balance' => $shop->balance + $sum]);
                $shop = $shop->fresh();
                \VanguardLTE\ShopStat::create([
                    'user_id' => \Auth::id(), 
                    'shop_id' => $shop->id, 
                    'type' => $request->type, 
                    'sum' => abs($summ),
                    'old' => $old,
                    'new' => $shop->balance,
                    'balance' => $payer->balance,
                    'reason' => $request->reason,
                ]);


                //create another transaction for mananger account
                \VanguardLTE\Transaction::create([
                    'user_id' => $user->id, 
                    'payeer_id' => $payer->id, 
                    'type' => $request->type, 
                    'summ' => abs($summ), 
                    'old' => $old,
                    'new' => $shop->balance,
                    'balance' => $payer->balance,
                    'shop_id' => $shop->id,
                    'reason' => $request->reason
                ]);

                if( $payer->balance == 0 ) 
                {
                    $payer->update([
                        'wager' => 0, 
                        'bonus' => 0
                    ]);
                }
                if( $payer->count_balance < 0 ) 
                {
                    $payer->update(['count_balance' => 0]);
                }
            }
            else
            {
                if( $request->all && $request->all == '1' ) 
                {
                    $summ = $user->balance;
                }
                $result = $user->addBalance($data['type'], abs($summ), false, 0, null, isset($data['reason'])?$data['reason']:null);
            
                $result = json_decode($result, true);
                if( $result['status'] == 'error' ) 
                {
                    return redirect()->back()->withErrors([$result['message']]);
                }
            }
            return redirect($request->url)->withSuccess(['조작이 성공했습니다.']);
        }

        public function profile(\Illuminate\Http\Request $request, \VanguardLTE\Repositories\Activity\ActivityRepository $activities)
        {
            $userid = auth()->user()->id;
            if ($request->id != '')
            {
                $userid = $request->id;
            }
            
            $availableUsers = auth()->user()->availableUsers();
            if (!in_array($userid, $availableUsers))
            {
                return redirect()->back()->withErrors(['유저를 찾을수 없습니다.']);
            }
            $user = \VanguardLTE\User::where('id', $userid)->first();
            if (!$user)
            {
                return redirect()->back()->withErrors(['유저를 찾을수 없습니다.']);
            }
            if ($user->status == \VanguardLTE\Support\Enum\UserStatus::DELETED)
            {
                return redirect()->to(argon_route('argon.dashboard'))->withErrors(['삭제된 유저입니다.']);
            }
            $userActivities = $activities->getLatestActivitiesForUser($user->id, 10);
            return view('backend.argon.common.profile', compact('user', 'userActivities'));
        }
        public function updatePassword(\VanguardLTE\Http\Requests\User\UpdateDetailsRequest $request)
        {
            $user_id = $request->id;
            $user = \VanguardLTE\User::find($user_id);
            $users = auth()->user()->availableUsers();
            if( !$user || (count($users) && !in_array($user_id, $users) )) 
            {
                return redirect()->back()->withErrors(['유저를 찾을수 없습니다']);
            }

            $data = $request->all();
            if( trim($data['password']) != '' ) 
            {
                unset($data['id']);
                $user->update($data);
            }
            event(new \VanguardLTE\Events\User\UpdatedByAdmin($user));
            return redirect()->back()->withSuccess(trans('app.login_updated'));
        }

        public function updateDWPass(\VanguardLTE\Http\Requests\User\UpdateDetailsRequest $request)
        {
            $user_id = $request->id;
            $user = \VanguardLTE\User::find($user_id);
            $users = auth()->user()->availableUsers();
            if( !$user || (count($users) && !in_array($user_id, $users) )) 
            {
                return redirect()->back()->withErrors(['유저를 찾을수 없습니다']);
            }

            $data = $request->all();
            $old_confirm_token = $data['old_confirmation_token'];
            if(!empty($user->confirmation_token) && !\Illuminate\Support\Facades\Hash::check($old_confirm_token, $user->confirmation_token) ) 
            {
                return redirect()->back()->withErrors(['이전 환전비밀번호가 틀립니다']);
            }

            if( trim($data['confirmation_token']) != '' ) 
            {
                unset($data['id']);
                $data['confirmation_token'] = \Illuminate\Support\Facades\Hash::make($data['confirmation_token']);
                $user->update($data);
            }
            event(new \VanguardLTE\Events\User\UpdatedByAdmin($user));
            return redirect()->back()->withSuccess(['환전비번을 업데이트했습니다']);
        }

        public function resetDWPass(\Illuminate\Http\Request $request)
        {
            $user_id = $request->id;
            $user = \VanguardLTE\User::find($user_id);
            $users = auth()->user()->availableUsers();
            if( !$user && count($users) && !in_array($user->id, $users) ) 
            {
                return redirect()->back()->withErrors(['유저를 찾을수 없습니다']);
            }
            $user->update(['confirmation_token' => null]);
            return redirect()->back()->withSuccess(['환전비번을 리셋했습니다']);
        }

        public function updateProfile(\Illuminate\Http\Request $request)
        {
            $user_id = $request->id;
            $user = \VanguardLTE\User::find($user_id);
            $users = auth()->user()->availableUsers();
            if( !$user || (count($users) && !in_array($user_id, $users) )) 
            {
                return redirect()->back()->withErrors(['유저를 찾을수 없습니다']);
            }
            
            $data = $request->all();
            
            if( !$user->isInOutPartner())
            {
                if ($user->hasRole('user'))
                {
                    $parent = $user->shop;
                }
                else{
                    $parent = $user->referral;
                }

                if ($parent!=null &&  isset($data['deal_percent']) && $parent->deal_percent < $data['deal_percent'])
                {
                    return redirect()->back()->withErrors(['딜비는 상위파트너보다 클수 없습니다']);
                }

                if ($parent!=null &&  isset($data['table_deal_percent']) && $parent->table_deal_percent < $data['table_deal_percent'])
                {
                    return redirect()->back()->withErrors(['라이브딜비는 상위파트너보다 클수 없습니다']);
                }
            }
            if ($user->hasRole('manager'))
            {
                $user->shop->update($data);   
            }
            $user->update($data);

            if (empty($data['memo']))
            {
                if ($user->memo)
                {
                    $user->memo->delete();
                }
            }
            else
            {
                if ($user->memo)
                {
                    $user->memo->update(['memo' => $data['memo']]);
                }
                else
                {
                    \VanguardLTE\UserMemo::create([
                        'user_id' => $user->id,
                        'writer_id' => auth()->user()->id,
                        'memo' => $data['memo']
                    ]);
                }
            }
            event(new \VanguardLTE\Events\User\UpdatedByAdmin($user));

            if( $this->userIsBanned($user, $request) ) 
            {
                event(new \VanguardLTE\Events\User\Banned($user));
            }

            return redirect()->back()->withSuccess(['설정정보가 업데이트되었습니다']);
        }
        public function deleteUser(\Illuminate\Http\Request $request)
        {
            $id = $request->id;
            $hard = $request->hard;
            $user = \VanguardLTE\User::where('id', $id)->first();

            $availableUsers = auth()->user()->availableUsers();
            if (!$user || !in_array($id, $availableUsers))
            {
                return redirect()->back()->withErrors(['유저를 찾을수 없습니다.']);
            }

            if( $user->id == \Illuminate\Support\Facades\Auth::id() ) 
            {
                return redirect()->back()->withErrors(trans('app.you_cannot_delete_yourself'));
            }

            if ($hard != '1')
            {
                if( $user->balance > 0 || ($user->hasRole('manager') && $user->shop->balance > 0)) 
                {
                    return redirect()->back()->withErrors(['유저의 보유금이 0이 아닙니다']);
                }

                if ($user->childBalanceSum() > 0)
                {
                    return redirect()->back()->withErrors(['하부 보유금이 0이 아닙니다']);
                }
            }
            if ($user->hasRole('user')) 
            {
                \VanguardLTE\Task::create([
                    'user_id' => auth()->user()->id,
                    'category' => 'user', 
                    'action' => 'delete', 
                    'item_id' => $user->id
                ]);
                $user->update(['status' => \VanguardLTE\Support\Enum\UserStatus::DELETED]);
            }
            else
            {

                $childUsers = $user->availableUsers();
                \VanguardLTE\User::whereIn('id', $childUsers)->update(['status' => \VanguardLTE\Support\Enum\UserStatus::DELETED]);

                foreach ($childUsers as $cid){
                    if ($cid != 0){
                        \VanguardLTE\Task::create([
                            'user_id' => auth()->user()->id,
                            'category' => 'user', 
                            'action' => 'delete', 
                            'item_id' => $cid
                        ]);
                    }
                }
                $shop_ids = $user->availableShops();
                \VanguardLTE\Shop::whereIn('id', $shop_ids)->update(['pending'=>2]);

                foreach ($shop_ids as $shop){
                    if ($shop != 0){
                        \VanguardLTE\Task::create([
                            'user_id' => auth()->user()->id,
                            'category' => 'shop', 
                            'action' => 'delete', 
                            'item_id' => $shop
                        ]);
                        $shopInfo = \VanguardLTE\Shop::where('id', $shop)->first();
                        event(new \VanguardLTE\Events\Shop\ShopDeleted($shopInfo));
                    }
                }
            }

            event(new \VanguardLTE\Events\User\Deleted($user));
            return redirect()->back()->withSuccess(['유저가 삭제되었습니다']);
        }
        private function userIsBanned(\VanguardLTE\User $user, \Illuminate\Http\Request $request)
        {
            return $user->status != $request->status && $request->status == \VanguardLTE\Support\Enum\UserStatus::BANNED;
        }

    }

}

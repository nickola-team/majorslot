<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend\Argon
{
    class CommonController extends \VanguardLTE\Http\Controllers\Controller
    {
        public function __construct(\VanguardLTE\Repositories\User\UserRepository $users)
        {
            $this->middleware('auth');
            $this->middleware('permission:access.admin.panel');
            $this->users = $users;
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

            $summ = str_replace(',','',$request->amount);

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
            
            return redirect($request->url)->withSuccess($result['message']);
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
            $userActivities = $activities->getLatestActivitiesForUser($user->id, 10);
            return view('backend.argon.common.profile', compact('user', 'userActivities'));
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

    }

}

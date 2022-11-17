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
        
        public function agent_move(\Illuminate\Http\Request $request)
        {
            $availablePartners = auth()->user()->hierarchyPartners();
            $id = $request->id;
            $user = \VanguardLTE\User::where('id', $id)->first();
            if (!$user || !in_array($id, $availablePartners))
            {
                return redirect()->back()->withErrors(['에이전트를 찾을수 없습니다']);
            }
            return view('backend.argon.agent.move', compact('user'));
        }

        public function agent_update(\Illuminate\Http\Request $request)
        {
            $availablePartners = auth()->user()->hierarchyPartners();
            $id = $request->id;
            $user = \VanguardLTE\User::where('id', $id)->first();
            if (!$user || !in_array($id, $availablePartners))
            {
                return redirect()->back()->withErrors(['에이전트를 찾을수 없습니다']);
            }

            $parentname = $request->parent;
            $parent = \VanguardLTE\User::where('username', $parentname)->where('role_id', $user->role_id+1)->first();

            if (!$parent || !in_array($parent->id, $availablePartners))
            {
                return redirect()->back()->withErrors(['상위에이전트를 찾을수 없습니다']);
            }
            $deal_percent = $user->deal_percent;
            $table_deal_percent = $user->tabe_deal_percent;            
            if ($user->hasRole('manager'))
            {
                $deal_percent = $user->shop->deal_percent;
                $table_deal_percent = $user->shop->tabe_deal_percent;            
            }

            if ($deal_percent > $parent->deal_percent || $table_deal_percent > $parent->table_deal_percent)
            {
                return redirect()->back()->withErrors(['롤링은 상위에이전트보다 클수 없습니다.']);
            }

            if ($user->hasRole('manager'))
            {
                $shopUser = \VanguardLTE\ShopUser::where(['user_id' => $user->referral->id, 'shop_id' => $user->shop->id])->first();
                if ($shopUser)
                {
                    $shopUser->update(['user_id' => $parent->id]);
                }
                $user->shop->update(['user_id' => $parent->id]);
            }
            else 
            {
                \VanguardLTE\ShopUser::where('user_id' , $user->referral->id)->whereIn('shop_id', $user->availableShops())->update(['user_id' => $parent->id]);
            }

            $user->update(['parent_id' => $parent->id]);

            return redirect()->to(argon_route('argon.agent.list'))->withSuccess(['에이전트를 이동하였습니다']);
           
        }

        public function agent_child(\Illuminate\Http\Request $request)
        {
            $user = auth()->user();
            $availablePartners = $user->hierarchyPartners();
            $child_id = $request->id;
            $users = [];
            if (in_array($child_id, $availablePartners))
            {
                $users = \VanguardLTE\User::where('parent_id', $child_id)->where('status', \VanguardLTE\Support\Enum\UserStatus::ACTIVE)->get();
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

            $availablePartners = auth()->user()->hierarchyPartners();
            $availablePartners[] = auth()->user()->id;
            $parent = \VanguardLTE\User::where(['username' => $request->parent, 'role_id' => $request->role_id+1])->whereIn('id', $availablePartners)->first();
            if (!$parent)
            {
                $role = \jeremykenedy\LaravelRoles\Models\Role::find($request->role_id+1);
                return redirect()->back()->withErrors([$request->parent . ' ' . $role->description . '을(를) 찾을수 없습니다']);
            }

            $role = \jeremykenedy\LaravelRoles\Models\Role::find($request->role_id);
            $data['parent_id'] = $parent->id;


            if (isset($data['deal_percent']) && $parent!=null &&  $parent->deal_percent < $data['deal_percent'])
            {
                return redirect()->back()->withErrors(['딜비는 상위에이전트보다 클수 없습니다']);
            }
            if (isset($data['table_deal_percent']) && $parent!=null && $parent->table_deal_percent < $data['table_deal_percent'])
            {
                return redirect()->back()->withErrors(['라이브딜비는 상위에이전트보다 클수 없습니다']);
            }
            // if ($data['role_id'] > 1 && $parent!=null && !$parent->isInoutPartner() && $parent->ggr_percent < $data['ggr_percent'])
            // {
            //     return redirect()->back()->withErrors(['죽장퍼센트는 상위에이전트보다 클수 없습니다']);
            // }

            $user = \VanguardLTE\User::create($data);
            $user->detachAllRoles();
            $user->attachRole($role);
            event(new \VanguardLTE\Events\User\Created($user));

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

            if ($data['role_id'] == 3)  //create shop
            {
                $data['name'] = $data['username'];
                $shop = \VanguardLTE\Shop::create($data + ['user_id' => auth()->user()->id]);
                
                //create shopuser table for all agents
                $parent = $user;
                while ($parent && !$parent->isInOutPartner())
                {
                    \VanguardLTE\ShopUser::create([
                        'shop_id' => $shop->id, 
                        'user_id' => $parent->id
                    ]);
                    $parent = $parent->referral;
                }
                
                $user->update(['shop_id' => $shop->id]);
                $site = null;
                if ($parent == null){
                    $site = \VanguardLTE\WebSite::where('domain', \Request::root())->first();
                }
                else
                {
                    $site = \VanguardLTE\WebSite::where('adminid', $parent->id)->first();
                }
                \VanguardLTE\Task::create([
                    'category' => 'shop', 
                    'action' => 'create', 
                    'item_id' => $shop->id,
                    'details' => $site->id,
                ]);
                $open_shift = \VanguardLTE\OpenShift::create([
                    'start_date' => \Carbon\Carbon::now(), 
                    'balance' => 0, 
                    'user_id' => auth()->user()->id, 
                    'shop_id' => $shop->id
                ]); 
                event(new \VanguardLTE\Events\Shop\ShopCreated($shop));

            }
            return redirect()->to(argon_route('argon.common.profile', ['id' => $user->id]));
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

            $users = \VanguardLTE\User::whereIn('id', $childPartners)->whereIn('status', [\VanguardLTE\Support\Enum\UserStatus::ACTIVE, \VanguardLTE\Support\Enum\UserStatus::BANNED]);

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
            //check if evolution+gameartcasino
            $master = $user;
            while ($master !=null && !$master->isInoutPartner())
            {
                $master = $master->referral;
            }
            $gacmerge = \VanguardLTE\Http\Controllers\Web\GameProviders\GACController::mergeGAC_EVO($master->id);
            return view('backend.argon.agent.deal', compact('statistics', 'total','gacmerge'));
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
                    $statistics = $statistics->whereNull('transactions.request_id');
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

        public function player_create(\Illuminate\Http\Request $request)
        {
            return view('backend.argon.player.create');
        }

        public function player_store(\VanguardLTE\Http\Requests\User\CreateUserRequest $request)
        {
            $data = $request->all() + ['status' => \VanguardLTE\Support\Enum\UserStatus::ACTIVE];

            $availablePartners = auth()->user()->hierarchyPartners();
            $availablePartners[] = auth()->user()->id;
            $parent = \VanguardLTE\User::where(['username' => $request->parent, 'role_id' => 3])->whereIn('id', $availablePartners)->first();
            if (!$parent)
            {
                return redirect()->back()->withErrors([$request->parent . '매장을 찾을수 없습니다']);
            }

            $role = \jeremykenedy\LaravelRoles\Models\Role::find(1);
            $data['parent_id'] = $parent->id;

            $shop = $parent->shop;
            if (isset($data['deal_percent']) && $shop!=null &&  $shop->deal_percent < $data['deal_percent'])
            {
                return redirect()->back()->withErrors(['딜비는 매장보다 클수 없습니다']);
            }
            if (isset($data['table_deal_percent']) && $shop!=null && $shop->table_deal_percent < $data['table_deal_percent'])
            {
                return redirect()->back()->withErrors(['라이브딜비는 매장보다 클수 없습니다']);
            }
            // if ($data['role_id'] > 1 && $parent!=null && !$parent->isInoutPartner() && $parent->ggr_percent < $data['ggr_percent'])
            // {
            //     return redirect()->back()->withErrors(['죽장퍼센트는 매장보다 클수 없습니다']);
            // }
            $data['shop_id'] = $shop->id;
            $data['role_id'] = $role->id;
            $user = \VanguardLTE\User::create($data);
            $user->detachAllRoles();
            $user->attachRole($role);
            event(new \VanguardLTE\Events\User\Created($user));

            \VanguardLTE\ShopUser::create([
                'shop_id' => $shop->id, 
                'user_id' => $user->id
            ]);
           return redirect()->to(argon_route('argon.common.profile', ['id' => $user->id]))->withSuccess(['플레이어가 생성되었습니다']);
        }

        public function vplayer_list(\Illuminate\Http\Request $request)
        {
            $partner_users = auth()->user()->availableUsers();
            $users = [];
            $joinusers = [];
            $confirmusers = [];
            if (count($partner_users) > 0){
                $joinusers = \VanguardLTE\User::orderBy('username', 'ASC')->where('status', \VanguardLTE\Support\Enum\UserStatus::JOIN)->whereIn('users.id', $partner_users)->get();

                $confirmusers = \VanguardLTE\User::orderBy('username', 'ASC')->where('status', \VanguardLTE\Support\Enum\UserStatus::UNCONFIRMED)->whereIn('users.id', $partner_users)->get();

                $users = \VanguardLTE\User::orderBy('username', 'ASC')->where('status', \VanguardLTE\Support\Enum\UserStatus::ACTIVE)->where('role_id',1)->where('email','<>', '')->whereIn('id', $partner_users);
                $total = [
                    'count' => $users->count(),
                    'balance' => $users->sum('balance'),
                ];
                $users = $users->paginate(20);

            }

            return view('backend.argon.player.vlist',compact('users', 'joinusers','confirmusers', 'total'));
        }

        public function player_join(\Illuminate\Http\Request $request)
        {
            if (!auth()->user()->isInoutPartner())
            {
                return redirect()->back()->withErrors(['권한이 없습니다.']);
            }

            $user = \VanguardLTE\User::whereIn('status', [\VanguardLTE\Support\Enum\UserStatus::JOIN, \VanguardLTE\Support\Enum\UserStatus::UNCONFIRMED])->where('id', $request->id)->first();
            if (!$user)
            {
                return redirect()->back()->withErrors(['잘못된 요청입니다.']);
            }
            if ($request->type == 'allow')
            {
                $user->update(['status' => \VanguardLTE\Support\Enum\UserStatus::ACTIVE]);
                $onlineShop = \VanguardLTE\OnlineShop::where('shop_id', $user->shop_id)->first();

                if ($onlineShop) //it is online users
                {
                    $admin = \VanguardLTE\User::where('role_id', 8)->first();
                    $user->addBalance('add',$onlineShop->join_bonus, $admin);
                }
            }
            else if ($request->type == 'stand')
            {
                $user->update(['status' => \VanguardLTE\Support\Enum\UserStatus::UNCONFIRMED]);
            }
            else
            {
                $user->update(['status' => \VanguardLTE\Support\Enum\UserStatus::REJECTED]);
            }
            return redirect()->back()->withSuccess(['가입신청을 처리했습니다']);
        }


        public function player_list(\Illuminate\Http\Request $request)
        {
            $user = auth()->user();
            $availableUsers = $user->hierarchyUsersOnly();

            $users = \VanguardLTE\User::whereIn('id', $availableUsers)->whereIn('status', [\VanguardLTE\Support\Enum\UserStatus::ACTIVE, \VanguardLTE\Support\Enum\UserStatus::BANNED]);

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
        public function player_terminate(\Illuminate\Http\Request $request)
        {
            $userid = $request->id;
            $availableUsers = auth()->user()->hierarchyUsersOnly();
            if (!in_array($userid, $availableUsers))
            {
                return redirect()->back()->withErrors(['허용되지 않은 조작입니다.']);
            }
            $user = \VanguardLTE\User::where('id', $userid)->first();
            if (!$user)
            {
                return redirect()->back()->withErrors(['플레이어를 찾을수 없습니다.']);
            }
            if ($user->playing_game != null)
            {
                $user->update(['playing_game' => $user->playing_game . '_exit']);
            }

            return redirect()->back()->withSuccess(['플레이어의 게임을 종료하였습니다']);
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
            $user = auth()->user();
            // $availableUsers = $user->hierarchyUsersOnly();
            $availableShops = $user->availableShops();
            // $statistics = \VanguardLTE\StatGame::select('stat_game.*')->orderBy('stat_game.date_time', 'DESC')->whereIn('user_id', $availableUsers);

            $statistics = \VanguardLTE\StatGame::select('stat_game.*')->orderBy('stat_game.date_time', 'DESC')->orderBy('stat_game.id', 'DESC');

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

            if ($request->shop != '')
            {
                $shop_ids = \VanguardLTE\Shop::where('name', 'like', '%' . $request->shop . '%')->whereIn('id', $availableShops)->pluck('id')->toArray();
                if (count($shop_ids) > 0) 
                {
                    $availableShops = $shop_ids;
                }
                else
                {
                    $availableShops = [-1];
                }
            }
            $statistics = $statistics->whereIn('stat_game.shop_id', $availableShops);

            if ($request->game != '')
            {
                $statistics = $statistics->where('stat_game.game', 'like', '%'. $request->game . '%');
            }

            if( $request->win_from != '' ) 
            {
                $statistics = $statistics->where('stat_game.win', '>=', $request->win_from);
            }
            if( $request->win_to != '' ) 
            {
                $statistics = $statistics->where('stat_game.win', '<=', $request->win_to);
            }
            if( $request->gametype != '' ) 
            {
                $statistics = $statistics->where('stat_game.type', $request->gametype);
            }

            $total = [
                'bet' => (clone $statistics)->sum('bet'),
                'win' => (clone $statistics)->sum('win'),
            ];

            $statistics = $statistics->paginate(20);

            //check if evolution+gameartcasino
            $master = $user;
            while ($master !=null && !$master->isInoutPartner())
            {
                $master = $master->referral;
            }
            $gacmerge = \VanguardLTE\Http\Controllers\Web\GameProviders\GACController::mergeGAC_EVO($master->id);
            return view('backend.argon.player.game', compact('statistics', 'total', 'gacmerge'));
        }

        public function player_game_pending(\Illuminate\Http\Request $request)
        {
            $user = auth()->user();
            $availableUsers = $user->hierarchyUsersOnly();
            if (count($availableUsers) == 0)
            {
                return redirect()->back()->withErrors(['유저가 없습니다']);
            }


            $statistics = \VanguardLTE\GACTransaction::whereIn('user_id', $availableUsers)->where(['gactransaction.type'=>1,'gactransaction.status'=>0])->orderBy('date_time', 'DESC');


            $statistics = $statistics->paginate(20);


            return view('backend.argon.player.pending', compact('statistics'));
        }

        public function player_game_cancel(\Illuminate\Http\Request $request)
        {
            $gacid = $request->id;
            $user = auth()->user();
            $availableUsers = $user->hierarchyUsersOnly();
            if (count($availableUsers) == 0)
            {
                return redirect()->back()->withErrors(['유저가 없습니다']);
            }


            \VanguardLTE\Http\Controllers\Web\GameProviders\GACController::cancelResult($gacid);

            return redirect()->back()->withSuccess(['취소처리 되었습니다']);

        }

        public function player_game_detail(\Illuminate\Http\Request $request)
        {
            $statid = $request->statid;
            $statgame = \VanguardLTE\StatGame::where('id', $statid)->first();
            if (!$statgame)
            {
                abort(404);
            }
            $ct = $statgame->category;
            $res = null;
            
            if ($ct->provider != null)
            {
                if (method_exists('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($ct->provider) . 'Controller','getgamedetail'))
                {
                    $res = call_user_func('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($ct->provider) . 'Controller::getgamedetail', $statgame);
                }
                else
                {
                    
                }
            }
            else //local game
            {
                $game = $statgame->game_item;
                if ($game)
                {
                    $object = '\VanguardLTE\Games\\' . $game->name . '\Server';
                    if (!class_exists($object))
                    {
                        abort(404);
                    }
                    $gameObject = new $object();
                    if (method_exists($gameObject, 'gameDetail'))
                    {
                        $res = $gameObject->gameDetail($statgame);
                    }
                    else
                    {
                        
                    }
                }
                else
                {
                    abort(404);
                }

            }
            return view('backend.argon.player.gamedetail', compact('res'));

            
        }

    }

}

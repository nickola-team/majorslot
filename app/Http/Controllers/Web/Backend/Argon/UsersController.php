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
        public function joiners_list(\Illuminate\Http\Request $request)
        {
            $partner_users = auth()->user()->availableUsers();

            $joinusers = \VanguardLTE\User::where(['status' => \VanguardLTE\Support\Enum\UserStatus::JOIN])->whereIn('users.id', $partner_users)->orderby('role_id', 'desc')->get();

            $goto = argon_route('argon.player.vlist');
            foreach ($joinusers as $joiner)
            {
                if ($joiner->role_id > 1)
                {
                    $goto = argon_route('argon.agent.joinlist');
                    break;
                }
                else if ($joiner->email == '')
                {
                    $goto = argon_route('argon.player.joinlist');
                    break;
                }
            }
            return redirect()->to($goto);
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
            // $deal_percent = $user->deal_percent;
            // $table_deal_percent = $user->tabe_deal_percent;            
            // if ($user->hasRole('manager'))
            // {
            //     $deal_percent = $user->shop->deal_percent;
            //     $table_deal_percent = $user->shop->tabe_deal_percent;            
            // }
            $check_deals = [
                'deal_percent',
                'table_deal_percent',
                'pball_single_percent',
                'pball_comb_percent',
                'sports_deal_percent'
            ];
            foreach ($check_deals as $dealtype)
            {
                if ($parent->{$dealtype} < $user->{$dealtype})
                {
                    return redirect()->back()->withErrors(['딜비는 상위에이전트보다 클수 없습니다']);
                }
            }
            

            // if ($deal_percent > $parent->deal_percent || $table_deal_percent > $parent->table_deal_percent)
            // {
            //     return redirect()->back()->withErrors(['롤링은 상위에이전트보다 클수 없습니다.']);
            // }

            if ($user->hasRole('manager'))
            {
                $referral = $user->referral;
                $parent_referral = $parent;
                while ($referral && !$referral->isInOutPartner())
                {
                    $shopUser = \VanguardLTE\ShopUser::where(['user_id' => $referral->id, 'shop_id' => $user->shop->id])->first();
                    if ($shopUser)
                    {
                        $shopUser->update(['user_id' => $parent_referral->id]);
                    }
                    $referral = $referral->referral;
                    $parent_referral = $parent_referral->referral;
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

            $parent = $user;
            while ($parent && !$parent->isInOutPartner())
            {
                $parent = $parent->referral;
            }
            $moneyperm = 0;
            if ($user->isInOutPartner())
            {
                $moneyperm = 1;
            }
            else if (isset($parent->sessiondata()['moneyperm']))
            {
                $moneyperm = $parent->sessiondata()['moneyperm'];
            }
            return view('backend.argon.agent.partials.childs', compact('users', 'child_id','moneyperm'));
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

            $check_deals = [
                'deal_percent',
                'table_deal_percent',
                'ggr_percent',
                'table_ggr_percent',
                'pball_single_percent',
                'pball_comb_percent',
                'sports_deal_percent'
            ];
            foreach ($check_deals as $dealtype)
            {
                if (isset($data[$dealtype]) && $parent!=null &&  $parent->{$dealtype} < $data[$dealtype])
                {
                    return redirect()->back()->withErrors(['롤링이나 죽장은 상위에이전트보다 클수 없습니다']);
                }
            }

            $comaster = auth()->user();
            while ($comaster && !$comaster->isInOutPartner())
            {
                $comaster = $comaster->referral;
            }
            $manualjoin = 1; //default 1
            if (auth()->user()->isInOutPartner())
            {
                $manualjoin = 0;
            }
            else if (isset($comaster->sessiondata()['manualjoin']))
            {
                $manualjoin = $comaster->sessiondata()['manualjoin'];
            }

            if ($manualjoin == 1)
            {
                $data['status'] = \VanguardLTE\Support\Enum\UserStatus::JOIN;
            }

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
                    if (!$site)
                    {
                        $site = \VanguardLTE\WebSite::where('domain', \Request::root())->first();
                    }
                }
                if ($site == null)
                {
                    return redirect()->back()->withErrors(['파트너생성이 실패하였습니다']);
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
            if ($manualjoin == 1)
            {
                return redirect()->to(argon_route('argon.common.profile', ['id' => $user->id]))->withSuccess(['파트너생성이 신청되었습니다. 승인될때까지 기다려주세요']);
            }

            return redirect()->to(argon_route('argon.common.profile', ['id' => $user->id]))->withSuccess(['파트너가 생성되었습니다']);
        }
        public function agent_joinlist(\Illuminate\Http\Request $request)
        {
            $partner_users = auth()->user()->availableUsers();
            $users = null;
            $total = null;
            $joinusers = [];
            $confirmusers = [];
            $title = '신규가입 리스트';
            if (count($partner_users) > 0){
                $joinusers = \VanguardLTE\User::orderBy('username', 'ASC')->where(['status' => \VanguardLTE\Support\Enum\UserStatus::JOIN])->where('email', '')->whereIn('users.id', $partner_users)->get();

                $confirmusers = \VanguardLTE\User::orderBy('username', 'ASC')->where(['status' => \VanguardLTE\Support\Enum\UserStatus::UNCONFIRMED])->where('email','')->whereIn('users.id', $partner_users)->get();
            }

            $moneyperm = 0;
            return view('backend.argon.player.vlist',compact('users', 'joinusers','confirmusers', 'total','moneyperm','title'));
        }

        public function agent_list(\Illuminate\Http\Request $request)
        {
            $user = auth()->user();
            if ($request->user != '' || $request->role != '' || $request->account_no != '' || $request->recommender != '' || $request->phone != '')
            {
                 $childPartners = $user->hierarchyPartners();
            }
            else if ($request->status == \VanguardLTE\Support\Enum\UserStatus::BANNED && $request->role == '')
            {
                 $childPartners = $user->hierarchyPartners();
            }
            else
            {
                $childPartners = $user->childPartners();
            }
            $parent = $user;
            while ($parent && !$parent->isInOutPartner())
            {
                $parent = $parent->referral;
            }
            $moneyperm = 0;
            if ($user->isInOutPartner())
            {
                $moneyperm = 1;
            }
            else if (isset($parent->sessiondata()['moneyperm']))
            {
                $moneyperm = $parent->sessiondata()['moneyperm'];
            }
            
            $status = [];
            if ($request->status == '')
            {
                $status = [\VanguardLTE\Support\Enum\UserStatus::ACTIVE, \VanguardLTE\Support\Enum\UserStatus::BANNED];
            }
            else
            {
                $status = [$request->status];
            }
            
            $users = \VanguardLTE\User::select('users.*')->whereIn('users.id', $childPartners)->whereIn('users.status', $status);

            if ($request->user != '')
            {
                if ($request->includename == 'on')
                {
                    $users = $users->where('users.username', 'like', '%' . $request->user . '%');
                }
                else
                {
                    $users = $users->where('users.username',  $request->user );
                }
            }

            if ($request->role != '')
            {
                $users = $users->where('users.role_id', $request->role);
            }

            if ($request->phone != '')
            {
                $users = $users->where('users.phone', 'like', '%'. $request->phone .'%');
            }

            if ($request->account_no != '')
            {
                $users = $users->where('users.account_no', 'like', '%'. $request->account_no .'%');
            }

            if ($request->recommender != '')
            {
                $users = $users->where('users.recommender', 'like', '%'. $request->recommender .'%');
            }

            if ($request->balance == 1)
            {
                
                if ($request->role == 3) //shop
                {
                    $users = $users->join('shops', 'shops.id', '=', 'users.shop_id');
                    $users = $users->orderby('shops.balance', 'desc');
                }
                else
                {
                    $users = $users->orderby('users.balance', 'desc');
                }
            }
            else if ($request->balance == 2)
            {
                
                if ($request->role == 3) //shop
                {
                    $users = $users->join('shops', 'shops.id', '=', 'users.shop_id');
                    $users = $users->orderby('shops.balance', 'asc');
                }
                else
                {
                    $users = $users->orderby('users.balance', 'asc');
                }
            }
            
            $usersum = (clone $users)->get();
            $childsum = 0;
            $balancesum = 0;
            $count = 0;
            foreach ($usersum as $u)
            {
                $childsum = $childsum + $u->childBalanceSum();
                if ($u->role_id > 3)
                {
                    $count = $count + count($u->hierarchyPartners());
                    $balancesum = $balancesum + $u->balance;
                }
                else if ($u->role_id==3)
                {
                    $balancesum = $balancesum + $u->shop->balance;
                }
            }

            $total = [
                'count' => $users->count() + $count,
                'balance' => $balancesum,
                'childbalance' => $childsum
            ];

            

            $users = $users->paginate(20);
            return view('backend.argon.agent.list', compact('users','total', 'moneyperm'));
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
            if (strtotime($end_date) - strtotime($start_date) >= 90000)
            {
                return redirect()->back()->withErrors(['검색시간을 24시간 이내로 설정해주세요.']);
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
            $availablePartners = $user->availableUsers();
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
            $check_deals = [
                'deal_percent',
                'table_deal_percent',
                'pball_single_percent',
                'pball_comb_percent',
                'sports_deal_percent'
            ];
            foreach ($check_deals as $dealtype)
            {
                if (isset($data[$dealtype]) && $shop!=null &&  $shop->{$dealtype} < $data[$dealtype])
                {
                    return redirect()->back()->withErrors(['딜비는 매장보다 클수 없습니다']);
                }
            }

            $data['shop_id'] = $shop->id;
            $data['role_id'] = $role->id;

            $comaster = auth()->user();
            while ($comaster && !$comaster->isInOutPartner())
            {
                $comaster = $comaster->referral;
            }
            $manualjoin = 1; //default 0
            if (auth()->user()->isInOutPartner())
            {
                $manualjoin = 0;
            }
            else if (isset($comaster->sessiondata()['manualjoin']))
            {
                $manualjoin = $comaster->sessiondata()['manualjoin'];
            }

            if ($manualjoin == 1)
            {
                $data['status'] = \VanguardLTE\Support\Enum\UserStatus::JOIN;
            }

            $user = \VanguardLTE\User::create($data);
            $user->detachAllRoles();
            $user->attachRole($role);
            event(new \VanguardLTE\Events\User\Created($user));

            \VanguardLTE\ShopUser::create([
                'shop_id' => $shop->id, 
                'user_id' => $user->id
            ]);
            if ($manualjoin == 1)
            {
                return redirect()->to(argon_route('argon.common.profile', ['id' => $user->id]))->withSuccess(['플레이어생성이 신청되었습니다. 승인될때까지 기다려주세요']);
            }
            return redirect()->to(argon_route('argon.common.profile', ['id' => $user->id]))->withSuccess(['플레이어가 생성되었습니다']);
        }
        public function player_joinlist(\Illuminate\Http\Request $request)
        {
            $partner_users = auth()->user()->availableUsers();
            $users = null;
            $total = null;
            $joinusers = [];
            $confirmusers = [];
            $title = '신규가입유저';
            if (count($partner_users) > 0){
                $joinusers = \VanguardLTE\User::orderBy('username', 'ASC')->where(['status' => \VanguardLTE\Support\Enum\UserStatus::JOIN, 'role_id' => 1])->where('email', '')->whereIn('users.id', $partner_users)->get();

                $confirmusers = \VanguardLTE\User::orderBy('username', 'ASC')->where(['status' => \VanguardLTE\Support\Enum\UserStatus::UNCONFIRMED, 'role_id' => 1])->where('email','')->whereIn('users.id', $partner_users)->get();
            }

            $moneyperm = 0;
            return view('backend.argon.player.vlist',compact('users', 'joinusers','confirmusers', 'total','moneyperm','title'));
        }

        public function vplayer_list(\Illuminate\Http\Request $request)
        {
            $partner_users = auth()->user()->availableUsers();
            $parent = auth()->user();
            while ($parent && !$parent->isInOutPartner())
            {
                $parent = $parent->referral;
            }
            $moneyperm = 0;
            if (auth()->user()->isInOutPartner() || auth()->user()->hasRole('manager'))
            {
                $moneyperm = 1;
            }
            else if (isset($parent->sessiondata()['moneyperm']))
            {
                $moneyperm = $parent->sessiondata()['moneyperm'];
            }

            $users = [];
            $joinusers = [];
            $confirmusers = [];
            if (count($partner_users) > 0){
                $joinusers = \VanguardLTE\User::orderBy('username', 'ASC')->where(['status' => \VanguardLTE\Support\Enum\UserStatus::JOIN, 'role_id' => 1])->where('email','<>', '')->whereIn('users.id', $partner_users)->get();

                $confirmusers = \VanguardLTE\User::orderBy('username', 'ASC')->where(['status' => \VanguardLTE\Support\Enum\UserStatus::UNCONFIRMED, 'role_id' => 1])->where('email','<>', '')->whereIn('users.id', $partner_users)->get();

                $users = \VanguardLTE\User::orderBy('username', 'ASC')->where('status', \VanguardLTE\Support\Enum\UserStatus::ACTIVE)->where('role_id',1)->where('email','<>', '')->whereIn('id', $partner_users);

                
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

            }


            return view('backend.argon.player.vlist',compact('users', 'joinusers','confirmusers', 'total','moneyperm'));
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
                    $admin = \VanguardLTE\User::where('role_id', 9)->first();
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
            set_time_limit(0);
            $user = auth()->user();
            $availableUsers = $user->hierarchyUsersOnly();
            $parent = $user;
            while ($parent && !$parent->isInOutPartner())
            {
                $parent = $parent->referral;
            }
            $moneyperm = 0;
            if ($user->isInOutPartner() || $user->hasRole('manager'))
            {
                $moneyperm = 1;
            }
            else if (isset($parent->sessiondata()['moneyperm']))
            {
                $moneyperm = $parent->sessiondata()['moneyperm'];
            }


            $users = \VanguardLTE\User::whereIn('id', $availableUsers)->whereIn('status', [\VanguardLTE\Support\Enum\UserStatus::ACTIVE, \VanguardLTE\Support\Enum\UserStatus::BANNED]);

            if ($request->user != '')
            {
                if ($request->includename == 'on')
                {
                    $users = $users->where('username', 'like', '%' . $request->user . '%');
                }
                else
                {
                    $users = $users->where('username',  $request->user );
                }
            }

            if ($request->phone != '')
            {
                $users = $users->where('phone', 'like', '%'. $request->phone .'%');
            }

            if ($request->account_no != '')
            {
                $users = $users->where('account_no', 'like', '%'. $request->account_no .'%');
            }

            if ($request->recommender != '')
            {
                $users = $users->where('recommender', 'like', '%'. $request->recommender .'%');
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
            if ($request->balance == 1)
            {
                $users = $users->orderby('balance', 'desc');
            }
            else if ($request->balance == 2)
            {
                $users = $users->orderby('balance', 'asc');
            }

            if ($request->ordername == 1)
            {
                $users = $users->orderby('username', 'asc');
            }
            else if ($request->ordername == 2)
            {
                $users = $users->orderby('username', 'desc');
            }

            $usersId = (clone $users)->pluck('id')->toArray();

            $validTimestamp = \Carbon\Carbon::now()->subMinutes(config('session.lifetime'))->timestamp;
            $validTime = date('Y-m-d H:i:s', strtotime("-5 minutes"));
            $onlineUsers = \VanguardLTE\Session::whereIn('user_id', $usersId)->where('last_activity', '>=', $validTimestamp)->pluck('user_id')->toArray();
            $onlineUserByGame = \VanguardLTE\StatGame::whereIn('user_id', $usersId)->where('date_time', '>=', $validTime)->pluck('user_id')->toArray();
            
            $onlineUsers = array_unique($onlineUsers);
            $onlineUserByGame = array_unique($onlineUserByGame);
            
            $onlineUsers = array_merge_recursive($onlineUsers, $onlineUserByGame);

            if ($request->online == 1)
            {
                $users = $users->whereIn('id',$onlineUsers);
            }

            if ($request->join != '')
            {
                // $dates = explode(' - ', $request->dates);
                $start_date = preg_replace('/T/',' ', $request->join[0]);
                $end_date = preg_replace('/T/',' ', $request->join[1]);
                $users = $users->where('created_at', '>', $start_date)->where('created_at', '<', $end_date);
            }

            $today = date('Y-m-d 0:0:0');
            $newusers = (clone $users)->where('created_at', '>', $today)->get();

            $totalusercount = $users->count();
            $onlinecount = count($onlineUsers);
            if ($onlinecount > $totalusercount)
            {
                $onlinecount = $totalusercount;
            }

            $total = [
                'count' => $totalusercount,
                'balance' => $users->sum('balance'),
                'online' => $onlinecount,
                'new' => count($newusers)
            ];
            
            $users = $users->paginate(20);
            return view('backend.argon.player.list', compact('users','total','moneyperm'));
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
            //대기중의 게임입장큐 삭제
            \VanguardLTE\GameLaunch::where('user_id', $user->id)->delete(); // where('finished', 0)->
            $b = $user->withdrawAll('playerterminate');
            if (!$b)
            {
                return redirect()->back()->withSuccess(['게임종료시 오류가 발생했습니다.']);
            }
            $user->update(['api_token' => 'playerterminate']);
            event(new \VanguardLTE\Events\User\TerminatedByAdmin($user));

            return redirect()->back()->withSuccess(['플레이어의 게임을 종료하였습니다']);
        }

        public function player_logout(\Illuminate\Http\Request $request,  \VanguardLTE\Repositories\Session\SessionRepository $sessionRepository)
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
            \VanguardLTE\GameLaunch::where('finished', 0)->where('user_id', $user->id)->delete();
            $b = $user->withdrawAll('playerlogout');

            $user->update(['api_token' => null]);

            $sessionRepository->invalidateAllSessionsForUser($user->id);

            event(new \VanguardLTE\Events\User\TerminatedByAdmin($user));

            return redirect()->back()->withSuccess(['플레이어를 로그아웃시켰습니다.']);
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
            if (strtotime($end_date) - strtotime($start_date) >= 90000)
            {
                return redirect()->back()->withErrors(['검색시간을 24시간 이내로 설정해주세요.']);
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

            if( $request->has('categories') && count($request->categories) > 0 ) 
            {

                $statistics = $statistics->whereIn('stat_game.category_id', $request->categories);
            }

            $total = [
                'bet' => (clone $statistics)->sum('bet'),
                'win' => (clone $statistics)->sum('win'),
            ];

            $statistics = $statistics->paginate(50);

            //check if evolution+gameartcasino
            $master = $user;
            while ($master !=null && !$master->isInoutPartner())
            {
                $master = $master->referral;
            }
            $gacmerge = \VanguardLTE\Http\Controllers\Web\GameProviders\GACController::mergeGAC_EVO($master->id);
            $categories = null;
            $website = \VanguardLTE\WebSite::where('adminid', $master->id)->first();
            if ($website)
            {
                $categories = $website->categories->where('parent', 0)->where('view', 1);
            }
            else
            {
                $categories = \VanguardLTE\Category::where(['site_id' => 0, 'shop_id' => 0,'view' => 1])->orderby('position', 'desc')->get();
            }
            return view('backend.argon.player.game', compact('statistics', 'total', 'gacmerge', 'categories'));
        }

        public function player_game_pending(\Illuminate\Http\Request $request)
        {
            $user = auth()->user();
            $availableUsers = $user->hierarchyUsersOnly();
            if (count($availableUsers) == 0)
            {
                return redirect()->back()->withErrors(['유저가 없습니다']);
            }


            $statistics = \VanguardLTE\GACTransaction::whereIn('user_id', $availableUsers)->where(['gactransaction.type'=>1,'gactransaction.status'=>0])->orderBy('date_time', 'ASC');


            $statistics = $statistics->paginate(20);


            return view('backend.argon.player.pending', compact('statistics'));
        }

        public function player_game_cancel(\Illuminate\Http\Request $request)
        {
            $gacid = $request->id;
            if (!auth()->user()->hasRole('admin'))
            {
                return redirect()->back()->withErrors(['허용되지 않은 접근입니다.']);
            }


            \VanguardLTE\Http\Controllers\Web\GameProviders\GACController::cancelResult($gacid);

            return redirect()->back()->withSuccess(['취소처리 되었습니다']);

        }

        public function player_game_process(\Illuminate\Http\Request $request)
        {
            $gacid = $request->id;
            if (!auth()->user()->hasRole('admin'))
            {
                return redirect()->back()->withErrors(['허용되지 않은 접근입니다.']);
            }

            $result = \VanguardLTE\Http\Controllers\Web\GameProviders\GACController::processResult($gacid);
            if ($result['error'] == false)
            {
                return redirect()->back()->withSuccess([$result['win'] . '원의 당첨금 결과처리되었습니다']);
            }
            else
            {
                \VanguardLTE\Http\Controllers\Web\GameProviders\GACController::cancelResult($gacid);

                return redirect()->back()->withSuccess(['취소처리 되었습니다']);
            }
        }

        public function player_refresh(\Illuminate\Http\Request $request)
        {
            $userid = $request->id;
            $availableUsers = auth()->user()->hierarchyUsersOnly();
            if (!in_array($userid, $availableUsers))
            {
                return response()->json(['error'=>true, 'msg'=> '유저를 찾을수 없습니다']);
            }
            $user = \VanguardLTE\User::where('id', $userid)->first();
            if (!$user)
            {
                return response()->json(['error'=>true, 'msg'=> '유저를 찾을수 없습니다']);
            }
            //게임사 연동 위해 대기중이면 머니동기화 하지 않기
            $launchRequests = \VanguardLTE\GameLaunch::where('finished', 0)->where('user_id', $user->id)->get();
            if (count($launchRequests) > 0)
            {
                return response()->json(['error'=>false, 'balance'=> number_format($user->balance)]);
            }
            $balance = \VanguardLTE\User::syncBalance($user, 'playerrefresh');
            if ($balance < 0)
            {
                return response()->json(['error'=>true, 'msg'=> '게임사머니 연동오류']);
            }
            else
            {
                return response()->json(['error'=>false, 'balance'=> number_format($balance)]);
            }        

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
                    if(($ct->provider == 'nexus' || $ct->provider == 'rg') && $res != null){
                        return redirect($res);
                    }
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
                    //check game category
                    $gameCats = $game->categories;
                    $object = '\VanguardLTE\Games\\' . $game->name . '\Server';
                    $isMini = false;
                    foreach ($gameCats as $gcat)
                    {
                        if ($gcat->category->href == 'pragmatic') //pragmatic game history
                        {
                            if ($statgame->user)
                            {
                                return redirect('/gs2c/lastGameHistory.do?symbol='.$game->label.'&token='.$statgame->user->id.'-'.$statgame->id);
                            }
                        }
                        else if ($gcat->category->href == 'bngplay') // booongo game history
                        {
                            if ($statgame->user)
                            {
                                return redirect("op/major/history.html?session_id=68939e9a5d134e78bfd9993d4a2cc34e#player_id=".$statgame->user->id."&brand=*&show=transactions&game_id=".$statgame->game_id."&tz=0&start_date=&end_date=&per_page=100&round_id=".$statgame->roundid."&currency=KRW&mode=REAL&report_type=GGR&header=0&totals=1&info=0&exceeds=0&lang=ko");
                            }
                        }
                        else if ($gcat->category->href == 'minigame')
                        {
                            $object = '\VanguardLTE\Http\Controllers\Web\GameParsers\PowerBall\\' . $game->name;
                            $isMini = true;
                            break;        
                        }
                    }

                    if (!class_exists($object))
                    {
                        abort(404);
                    }
                    if ($isMini)
                    {
                        $gameObject = new $object($game->id);
                    }else{
                        $gameObject = new $object();
                    }
                    
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
        public function exportCSV(\Illuminate\Http\Request $request)
        {
            set_time_limit(0);
            $fileName = '유저목록.csv';
            $headers = array(
                "Content-type"        => "text/csv;charset=UTF-8",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
                "Content-Transfer-Encoding" => "binary"
            );

            $columns = array('번호', '이름', '상위유저', '등급', '폰번호', '은행이름', '예금주', '계좌번호', '보유금');
            $currentUser = auth()->user();
            if($currentUser == null){
                return redirect()->back()->withErrors(trans('app.logout'));
            }else if($currentUser->isInOutPartner() == false){
                return redirect()->back()->withErrors('허용되지 않은 조작입니다.');
            }
            $currentUser->export_csvUserList = [];
            $userlist = $currentUser->getCSVUserList($currentUser->id);
            $callback = function() use($userlist, $columns) {
                echo "\xEF\xBB\xBF"; 
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ($userlist as $sub_user) {
                    fputcsv($file, array($sub_user['id'], $sub_user['username'], $sub_user['parent_id'], $sub_user['role_id'], $sub_user['phone'], $sub_user['bank_name'], $sub_user['recommender'], $sub_user['account_no'], $sub_user['balance']));
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }
    }

}

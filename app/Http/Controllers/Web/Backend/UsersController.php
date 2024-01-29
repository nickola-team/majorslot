<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend
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
        public function index(\Illuminate\Http\Request $request)
        {
            $statuses = ['' => trans('app.all')] + \VanguardLTE\Support\Enum\UserStatus::lists();
            $roles = \jeremykenedy\LaravelRoles\Models\Role::where('level', '<', \Illuminate\Support\Facades\Auth::user()->level())->pluck('name', 'id');
            $roles->prepend(trans('app.all'), '0');
            $users = \VanguardLTE\User::orderBy('username', 'ASC')->where('status', \VanguardLTE\Support\Enum\UserStatus::ACTIVE);
            if($request->orderby)
            {
                if ($request->orderby == 1)
                {
                    $users = \VanguardLTE\User::orderBy('balance', 'DESC');
                }
            }
            $users = $users->whereIn('id', auth()->user()->hierarchyUsersOnly());
            if($request->shopname != '')
            {
                if (!$users)
                {
                    return redirect()->back()->withErrors('알수 없는 오류');
                }
                $shops = \VanguardLTE\Shop::where('name', 'like', '%'.$request->shopname.'%')->whereIn('id', auth()->user()->availableShops())->get();
                if (count($shops) > 0) {
                    $shopUsers = [];
                    foreach ($shops as $shop)
                    {
                        $shopUsers = array_merge_recursive($shopUsers, $shop->users->pluck('user_id')->toArray());
                    }
                    $users = $users->whereIn('id', $shopUsers);
                }
            }
            if (!$users)
            {
                return redirect()->back()->withErrors('알수 없는 오류');
            }
            $users = $users->where('id', '!=', \Illuminate\Support\Facades\Auth::user()->id);
            $users = $users->where('role_id', '=', 1);
            if( $request->search != '' ) 
            {
                $users = $users->where('username', 'like', '%' . $request->search . '%');
            }
            if( $request->status != '' ) 
            {
                $users = $users->where('status', $request->status);
            }
            
            $role_id = $request->role;
            if( $role_id ) 
            {
                $users = $users->where('role_id', $request->role);
            }
            $userIds = auth()->user()->hierarchyUsersOnly();
            $shopIds = auth()->user()->availableShops();

            $stat['totaluser'] = count($userIds);
            $stat['onlineuser'] = 0;
            if (count($userIds) > 0){
                $validTimestamp = \Carbon\Carbon::now()->subMinutes(config('session.lifetime'))->timestamp;
                $query = 'SELECT count(*) as online FROM w_sessions WHERE user_id in (' . implode(',', $userIds) . ') AND last_activity>=' . $validTimestamp;
                $qresult = \DB::select($query);
                $stat['onlineuser'] = $qresult[0]->online;
            }
            $stat['totalbalance'] = \VanguardLTE\User::where('role_id' , 1)->whereIn('shop_id', $shopIds)->sum('balance');

            $users = $users->paginate(20);
            $happyhour = \VanguardLTE\HappyHour::where([
                'shop_id' => auth()->user()->shop_id, 
                'time' => date('G')
            ])->first();

            return view('backend.Default.user.list', compact('users', 'statuses', 'roles', 'role_id', 'happyhour', 'stat'));
        }
        public function join(\Illuminate\Http\Request $request)
        {
            $partner_users = auth()->user()->availableUsers();
            $users = [];
            $joinusers = [];
            if (count($partner_users) > 0){
                $joinusers = \VanguardLTE\User::orderBy('username', 'ASC')->where('status', \VanguardLTE\Support\Enum\UserStatus::JOIN)->whereIn('users.id', $partner_users)->get();

                $users = \VanguardLTE\User::orderBy('username', 'ASC')->where('status', \VanguardLTE\Support\Enum\UserStatus::ACTIVE)->where('role_id',1)->whereNotNull('phone')->whereIn('id', $partner_users);
                $users = $users->paginate(20);
            }

            return view('backend.Default.user.join',compact('users', 'joinusers'));
        }
        public function processJoin(\Illuminate\Http\Request $request)
        {
            if (!auth()->user()->isInoutPartner())
            {
                return redirect()->back()->withErrors(['권한이 없습니다.']);
            }

            $user = \VanguardLTE\User::where('status', \VanguardLTE\Support\Enum\UserStatus::JOIN)->where('id', $request->user_id)->first();
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
            else
            {
                $user->update(['status' => \VanguardLTE\Support\Enum\UserStatus::REJECTED]);
            }
            return redirect()->back()->withSuccess(['가입신청을 처리했습니다']);
        }
        public function createuserfromcsv(\Illuminate\Http\Request $request)
        {
            return view('backend.Default.user.createfromcsv', ['ispartner' => 0]);
        }
        public function createpartnerfromcsv(\Illuminate\Http\Request $request)
        {
            return view('backend.Default.user.createfromcsv',  ['ispartner' => 1]);
        }
        public function storepartnerfromcsv(\Illuminate\Http\Request $request)
        {
            set_time_limit(0);
            $shopcount = 0;
            $partnercount = 0;
            $fuser = [];
            if ($request->hasFile('csvfile'))
            {
                $csvfile = $request->file('csvfile')->getRealPath();
                $data = array_map('str_getcsv', file($csvfile));
                $master = null;
                $agent = null;
                $distr = null;
                $manager = null;
                $parent = auth()->user()->id;
                $roles = \jeremykenedy\LaravelRoles\Models\Role::get();
                foreach ($data as $partner)
                {
                    $username = $partner[1];
                    $role = 3;
                    switch ($partner[3])
                    {
                        case '매장': //manager
                            $parent = $distr->id;
                            $username = $username . 'co';
                            $role = 3;
                            break;
                        case '총판'://distributor
                            $parent = $agent->id;
                            $role = 4;
                            break;
                        case '부본사': //agent
                            $parent = $master->id;
                            $role = 5;
                            break;
                        case '본사'://master
                            $parent = auth()->user()->id;
                            $role = 6;
                            break;
                    }
                    try
                    {
                        $user = \VanguardLTE\User::where('username', $username)->first();
                        if ($user)
                        {
                            $partner[] = '파트너있음';
                            $fuser[] = $partner;
                            break;
                        }
                        $user = \VanguardLTE\User::create([
                            'first_name' => $partner[0],
                            'username' => $username,
                            'password' => $partner[2],
                            'role_id' => $role,
                            'deal_percent' => $partner[6],
                            'bank_name' => $partner[7],
                            'account_no' => $partner[8],
                            'recommender' => $partner[9],
                            'parent_id' => $parent,
                            'status' => \VanguardLTE\Support\Enum\UserStatus::ACTIVE, 
                        ]);
                        $user->attachRole($roles->find($role));
                        $partnercount++;
                    }
                    catch (Exception $e)
                    {
                        $fuser[] = $partner;
                    }

                    switch ($role)
                    {
                        case 3: //manager
                            $manager = $user;
                            break;
                        case 4://distributor
                            $distr = $user;
                            break;
                        case 5: //agent
                            $agent = $user;
                            break;
                        case 6://master
                            $master = $user;
                            break;
                    }

                    if ($role == 3) //if shop?, create a shop
                    {
                        try
                        {
                            $shop = \VanguardLTE\Shop::where('name', $partner[1])->first();
                            if ($shop)
                            {
                                $partner[] = '매장있음';
                                $manager->delete(); //delete manager
                                $partnercount--;
                                $fuser[] = $partner;
                                continue;
                            }
                            $shop = \VanguardLTE\Shop::create([
                                'user_id' => $distr->id,
                                'name' => $partner[1],
                                'alias' => $partner[0],
                                'percent' => 97,
                                'frontend' => 'Default',
                                'currency' => 'KRW',
                                'orderby' => 'AZ',
                                'balance' => 0,
                                'deal_percent' => $partner[6],
                                ]);
                            $open_shift = \VanguardLTE\OpenShift::create([
                                'start_date' => \Carbon\Carbon::now(), 
                                'balance' => $shop->balance, 
                                'user_id' => $distr->id, 
                                'shop_id' => $shop->id
                            ]);

                            foreach( [
                                $master, 
                                $agent, 
                                $distr, 
                                $manager
                            ] as $user ) 
                            {
                                \VanguardLTE\ShopUser::create([
                                    'shop_id' => $shop->id, 
                                    'user_id' => $user->id
                                ]);
                                $user->update(['shop_id' => $shop->id]);
                            }

                            \VanguardLTE\Task::create([
                                'category' => 'shop', 
                                'action' => 'create', 
                                'item_id' => $shop->id
                            ]);
                        }
                        catch (Illuminate\Database\QueryException $e)
                        {
                            $fuser[] = $partner;
                        }
                        $shopcount++;
                    }
                }
            }
            $ispartner = 1;
            $msg = $partnercount . '명의 파트너와 ' . $shopcount . '개의 매장을 생성하였습니다.';
            return view('backend.Default.user.createfromcsv',  compact('ispartner', 'fuser', 'msg'));
        }

        public function storeuserfromcsv(\Illuminate\Http\Request $request)
        {
            set_time_limit(0);
            $succeed = 0;
            $failed = 0;
            $fuser = [];
            if ($request->hasFile('csvfile'))
            {
                $csvfile = $request->file('csvfile')->getRealPath();
                $data = array_map('str_getcsv', file($csvfile));
                $manager = null;
                $roles = \jeremykenedy\LaravelRoles\Models\Role::get();
                foreach ($data as $user)
                {
                    $shop = \VanguardLTE\Shop::where('name', $user[7])->first();
                    $shopmanager = \VanguardLTE\User::where('username', $user[7].'co')->first();
                    if ($shop && $shopmanager) {
                        try
                        {
                            $checkuser = \VanguardLTE\User::where('username', $user[0])->first();
                            if ($checkuser)
                            {
                                $user[] = '회원있음';
                                $fuser[] = $user;
                                $failed++;
                                continue;
                            }
                            $newuser = \VanguardLTE\User::create([
                                'first_name' => $user[2],
                                'username' => $user[0],
                                'password' => $user[1],
                                'role_id' => 1,
                                'phone' => $user[3],
                                'bank_name' => $user[4],
                                'account_no' => $user[5],
                                'recommender' => $user[6],
                                'shop_id' => $shop->id,
                                'parent_id' => $shopmanager->id,
                                'status' => \VanguardLTE\Support\Enum\UserStatus::ACTIVE, 
                            ]);
                            $newuser->attachRole($roles->find(1));
                            \VanguardLTE\ShopUser::create([
                                'shop_id' => $shop->id, 
                                'user_id' => $newuser->id
                            ]);
                            $succeed++;
                        }
                        catch (Illuminate\Database\QueryException $e)
                        {
                            $error_code = $e->errorInfo[1];
                            return redirect()->back()->withErrors($e->getMessage());
                        }
                    }
                    else
                    {
                        $user[] = '매장없음';
                        $fuser[] = $user;
                        $failed++;
                    }
                }
            }
            $ispartner = 0;
            $msg = $succeed . '명의 회원을 생성하였습니다. 실패 ' . $failed . '명';
            return view('backend.Default.user.createfromcsv',  compact('ispartner', 'fuser', 'msg'));
        }
        public function blacklist(\Illuminate\Http\Request $request)
        {
            $user = auth()->user();
            if (!$user->hasRole('admin'))
            {
                return redirect()->back()->withErrors('비정상적인 접근입니다.');
            }
            $blacklist = \VanguardLTE\BlackList::orderby('id');

            if( $request->search != '' ) 
            {
                $blacklist = $blacklist->where('name', 'like', '%'.$request->search.'%');
            }

            if( $request->phone != '' ) 
            {
                $blacklist = $blacklist->where('phone', 'like', '%'.$request->phone.'%');
            }

            if( $request->account != '' ) 
            {
                $blacklist = $blacklist->where('account_number', 'like', '%'.$request->account.'%');
            }

            $blacklist = $blacklist->paginate(20);

            return view('backend.Default.user.blacklist',  compact('blacklist'));
        }

        public function partner($role_id, \Illuminate\Http\Request $request)
        {
            $user = auth()->user();
            $users = $user->availableUsers();
            $level = $user->level();
            if ($level <= $role_id)
            {
                return redirect()->back()->withErrors('비정상적인 접근입니다.');
            }
            

            $partners = \VanguardLTE\User::where('status', '<>',\VanguardLTE\Support\Enum\UserStatus::DELETED)->where('role_id', $role_id)->whereIn('id', $users);

            if( $request->credit_from != '' ) 
            {
                $partners = $partners->where('balance', '>=', $request->credit_from);
            }
            if( $request->credit_to != '' ) 
            {
                $partners = $partners->where('balance', '<=', $request->credit_to);
            }
            if( $request->search != '' ) 
            {
                $partners = $partners->where('username', 'like', '%'.$request->search.'%');
            }

            $stat = [
                'count' => $partners->count(),
                'sum' => $partners->sum('balance')
            ];

            $partners = $partners->paginate(20);
            return view('backend.Default.user.partners',  compact('partners', 'stat', 'role_id'));

        }
        public function tree(\Illuminate\Http\Request $request)
        {
            $user_id = $request->input('parent');
            $users = [];
            $user = null;
            if ($request->search != '')
            {
                $users = \VanguardLTE\User::orderBy('username', 'ASC');
                $users = $users->whereIn('id', auth()->user()->hierarchyPartners());
                $users = $users->where('username', 'like', '%' . $request->search . '%');
                $users = $users->get()->pluck('id')->toArray();
            }
            else
            {
                if($user_id == null || $user_id == 0)
                {
                    $user_id = auth()->user()->id;
                    $users = [$user_id];
                    if (auth()->user()->hasRole('admin'))
                    {
                        $users = auth()->user()->childPartners();
                    }
                }
                else 
                {
                    if (auth()->user()->id!=$user_id && !in_array($user_id, auth()->user()->hierarchyPartners()))
                    {
                        return redirect()->back()->withErrors(['비정상적인 접근입니다.']);
                    }
                    $user = \VanguardLTE\User::where('id', $user_id)->get()->first();
                    if ($user) {
                        $users = $user->childPartners();
                    }
                    else
                    {
                        $users = [$user_id];
                    }
                }
            }
            $partners = [];
            $childs = \VanguardLTE\User::where('status', '<>',\VanguardLTE\Support\Enum\UserStatus::DELETED)->whereIn('id', $users)->get();
            foreach($childs as $partner){
                if ($partner->hasRole('manager'))
                {
                    $shop_users = \VanguardLTE\ShopUser::whereIn('user_id', [$partner->id])->get()->pluck('shop_id')->toArray();
                    $shop = \VanguardLTE\Shop::whereIn('id', $shop_users)->get()->first();
                    $partners[] = [
                        'id' => $partner->id,
                        'name' => $partner->username,
                        'balance' => $shop->balance,
                        'profit' => $shop->deal_balance - $shop->mileage,
                        'deal_percent' => $shop->deal_percent,
                        'table_deal_percent' => $shop->table_deal_percent,
                        'ggr_percent' => $shop->ggr_percent,
                        'reset_days' => $shop->reset_days,
                        'bonus' => 0,
                        'role_id' => $partner->role_id,
                        'shop' => $shop->name,
                        'shop_id' => $shop->id,
                    ];
                }
                else
                {
                    /*$bonus_value = 0;
                    if ($partner->hasRole('master'))
                    {
                        $bonus_bank = \VanguardLTE\BonusBank::where('master_id', $partner->id)->first();
                        if ($bonus_bank)
                        {
                            $bonus_value = $bonus_bank->bank;
                        }

                    } */
                    $partners[] = [
                        'id' => $partner->id,
                        'name' => $partner->username,
                        'balance' => $partner->balance,
                        'profit' => $partner->deal_balance - $partner->mileage,
                        'deal_percent' => $partner->deal_percent,
                        'table_deal_percent' => $partner->table_deal_percent,
                        'ggr_percent' => $partner->ggr_percent,
                        'reset_days' => $partner->reset_days,
                        'bonus' => 0,
                        'role_id' => $partner->role_id
                    ];
                }
            }
            return view('backend.Default.user.tree', compact('partners','user'));
        }
        public function view(\VanguardLTE\User $user, \VanguardLTE\Repositories\Activity\ActivityRepository $activities)
        {
            $userActivities = $activities->getLatestActivitiesForUser($user->id, 10);
            if( \Illuminate\Support\Facades\Auth::user()->role_id < $user->role_id ) 
            {
                return redirect()->route(config('app.admurl').'.user.list');
            }
            return view('backend.Default.user.view', compact('user', 'userActivities'));
        }
        public function blackcreate()
        {
            return view('backend.Default.user.blackcreate');
        }
        public function blackedit($blackid)
        {
            $user = \VanguardLTE\BlackList::where('id', $blackid)->first();
            if (!$user)
            {
                return redirect()->route(config('app.admurl').'.black.list');
            }
            return view('backend.Default.user.blackedit', compact('user'));
        }
        public function blackupdate($blackid, \Illuminate\Http\Request $request)
        {
            $data = $request->only(
                [
                    'name',
                    'phone',
                    'account_bank',
                    'account_name',
                    'account_number',
                    'memo',
                ]
            );
            $user = \VanguardLTE\BlackList::where('id', $blackid);
            if ($user){
                $user->update($data);
            }
            return redirect()->route(config('app.admurl').'.black.list')->withSuccess('블랙정보가 수정되었습니다');
        }
        public function blackremove($blackid, \Illuminate\Http\Request $request)
        {
            \VanguardLTE\BlackList::where('id', $blackid)->delete();
            return redirect()->route(config('app.admurl').'.black.list')->withSuccess('블랙정보가 삭제되었습니다');
        }
        public function blackstore(\Illuminate\Http\Request $request)
        {
            $data = $request->all();
            \VanguardLTE\BlackList::create($data);
            return redirect()->route(config('app.admurl').'.black.list')->withSuccess('블랙리스트에 추가되었습니다');
        }
        public function create()
        {
            $happyhour = \VanguardLTE\HappyHour::where([
                'shop_id' => auth()->user()->shop_id, 
                'time' => date('G')
            ])->first();
            $roles = \jeremykenedy\LaravelRoles\Models\Role::where('level', '<', \Illuminate\Support\Facades\Auth::user()->level())->pluck('name', 'id');
            $statuses = \VanguardLTE\Support\Enum\UserStatus::lists();
            $shops = auth()->user()->shops();
            $availibleUsers = [];
            if( auth()->user()->hasRole('admin') ) 
            {
                $availibleUsers = \VanguardLTE\User::get();
            }
            if( auth()->user()->hasRole('agent') ) 
            {
                $me = \VanguardLTE\User::where('id', \Illuminate\Support\Facades\Auth::id())->get();
                $distributors = \VanguardLTE\User::where([
                    'parent_id' => auth()->user()->id, 
                    'role_id' => 4
                ])->get();
                if( $shopsIds = \Illuminate\Support\Facades\Auth::user()->shops(true) ) 
                {
                    $users = \VanguardLTE\ShopUser::whereIn('shop_id', $shopsIds)->pluck('user_id');
                    if( $users ) 
                    {
                        $availibleUsers = \VanguardLTE\User::whereIn('id', $users)->whereIn('role_id', [
                            2, 
                            3
                        ])->get();
                    }
                }
                $me = $me->merge($distributors);
                $availibleUsers = $me->merge($availibleUsers);
            }
            if( auth()->user()->hasRole([
                'distributor', 
                'manager', 
                'cashier'
            ]) ) 
            {
                $me = \VanguardLTE\User::where('id', \Illuminate\Support\Facades\Auth::id())->get();
                if( $shopsIds = \Illuminate\Support\Facades\Auth::user()->shops(true) ) 
                {
                    $users = \VanguardLTE\ShopUser::whereIn('shop_id', $shopsIds)->pluck('user_id');
                    if( $users ) 
                    {
                        $availibleUsers = \VanguardLTE\User::whereIn('id', $users)->whereIn('role_id', [
                            2, 
                            3
                        ])->get();
                    }
                }
                $availibleUsers = $me->merge($availibleUsers);
            }
            return view('backend.Default.user.add', compact('roles', 'statuses', 'shops', 'availibleUsers', 'happyhour'));
        }
        public function reset_ggr(\Illuminate\Http\Request $request)
        {
            \Artisan::call('daily:reset_ggr', ['masterid' => $request->masterid]);
            return redirect()->back()->withSuccess('리셋되었습니다.');
        }

        public function store(\VanguardLTE\Http\Requests\User\CreateUserRequest $request)
        {
            $count = \VanguardLTE\User::where([
                'shop_id' => \Illuminate\Support\Facades\Auth::user()->shop_id, 
                'role_id' => 1
            ])->count();
            if( $request->role_id <= 3 && !$request->shop_id ) 
            {
                return redirect()->route(config('app.admurl').'.user.list')->withErrors([trans('app.choose_shop')]);
            }
            $data = $request->all() + ['status' => \VanguardLTE\Support\Enum\UserStatus::ACTIVE];
            if( trim($data['username']) == '' ) 
            {
                $data['username'] = null;
            }
            if( $this->max_users <= $count && $data['role_id'] == 1 ) 
            {
                return redirect()->route(config('app.admurl').'.user.list')->withErrors([trans('app.max_users', ['max' => $this->max_users])]);
            }
            if( !$request->parent_id ) 
            {
                $data['parent_id'] = \Illuminate\Support\Facades\Auth::user()->id;
            }
            $sum = 0;
            if( $request->balance && $request->balance > 0 ) 
            {
                $shop = \VanguardLTE\Shop::find(\Illuminate\Support\Facades\Auth::user()->shop_id);
                $sum = abs(str_replace(',','', $request->balance));
                if( $shop->balance < $sum ) 
                {
                    return redirect()->back()->withErrors([trans('app.not_enough_money_in_the_shop', [
                        'name' => $shop->name, 
                        'balance' => $shop->balance
                    ])]);
                }
                $open_shift = \VanguardLTE\OpenShift::where([
                    'shop_id' => \Illuminate\Support\Facades\Auth::user()->shop_id, 
                    'type' => 'shop',
                    'end_date' => null
                ])->first();
                if( !$open_shift ) 
                {
                    return redirect()->back()->withErrors([trans('app.shift_not_opened')]);
                }
            }
            $role_id = (isset($data['role_id']) && $data['role_id'] < auth()->user()->role_id ? $data['role_id'] : auth()->user()->role_id - 1);
            $data['role_id'] = $role_id;
            if (empty($data['reset_days']))
            {
                $data['reset_days'] = auth()->user()->reset_days;
            }
            $data['last_reset_at'] = date('Y-m-d');
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
            if( $request->balance && $request->balance > 0 ) 
            {
                $happyhour = \VanguardLTE\HappyHour::where([
                    'shop_id' => auth()->user()->shop_id, 
                    'time' => date('G')
                ])->first();
                $balance = $sum;
                if( $happyhour ) 
                {
                    $transactionSum = $sum * intval(str_replace('x', '', $happyhour->multiplier));
                    $bonus = $transactionSum - $sum;
                    $wager = $bonus * intval(str_replace('x', '', $happyhour->wager));
                    \VanguardLTE\Transaction::create([
                        'user_id' => $user->id, 
                        'system' => 'HH ' . $happyhour->multiplier, 
                        'summ' => $transactionSum, 
                        'shop_id' => ($user->hasRole('user') ? $user->shop_id : 0)
                    ]);
                    $user->increment('wager', $wager);
                    $user->increment('bonus', $bonus);
                    $user->increment('count_bonus', $bonus);
                    $balance = $transactionSum;
                }
                else
                {
                    \VanguardLTE\Transaction::create([
                        'user_id' => $user->id, 
                        'payeer_id' => \Illuminate\Support\Facades\Auth::id(), 
                        'summ' => $sum, 
                        'old' => 0,
                        'new' => $sum, 
                        'balance' => auth()->user()->balance,
                        'shop_id' => auth()->user()->shop_id
                    ]);
                }
                $user->update([
                    'balance' => $balance, 
                    'count_balance' => $sum, 
                    'total_in' => $sum, 
                    'count_return' => \VanguardLTE\Lib\Functions::count_return($sum, $user->shop_id)
                ]);
                $shop->update(['balance' => $shop->balance - $sum]);
                //$open_shift->increment('balance_out', abs($sum));
                $open_shift->increment('money_in', abs($sum));
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

            return redirect()->route(config('app.admurl').'.user.tree')->withSuccess(trans('app.user_created'));
        }
        public function massadd(\Illuminate\Http\Request $request)
        {
            $shop = \VanguardLTE\Shop::find(\Illuminate\Support\Facades\Auth::user()->shop_id);
            $count = \VanguardLTE\User::where([
                'shop_id' => \Illuminate\Support\Facades\Auth::user()->shop_id, 
                'role_id' => 1
            ])->count();
            if( isset($request->count) && is_numeric($request->count) && isset($request->balance) && is_numeric($request->balance) ) 
            {
                if( $this->max_users < ($count + $request->count) ) 
                {
                    return redirect()->route(config('app.admurl').'.user.list')->withErrors([trans('max_users', ['max' => $this->max_users])]);
                }
                if( $request->balance > 0 ) 
                {
                    if( $shop->balance < ($request->count * $request->balance) ) 
                    {
                        return redirect()->back()->withErrors([trans('app.not_enough_money_in_the_shop', [
                            'name' => $shop->name, 
                            'balance' => $shop->balance
                        ])]);
                    }
                    $open_shift = \VanguardLTE\OpenShift::where([
                        'shop_id' => \Illuminate\Support\Facades\Auth::user()->shop_id, 
                        'end_date' => null
                    ])->first();
                    if( !$open_shift ) 
                    {
                        return redirect()->back()->withErrors([trans('app.shift_not_opened')]);
                    }
                }
                if( \Illuminate\Support\Facades\Auth::user()->hasRole('cashier') ) 
                {
                    $role = \jeremykenedy\LaravelRoles\Models\Role::find(1);
                    for( $i = 0; $i < $request->count; $i++ ) 
                    {
                        $number = rand(111111111, 999999999);
                        $data = [
                            'username' => $number, 
                            'password' => $number, 
                            'role_id' => $role->id, 
                            'status' => \VanguardLTE\Support\Enum\UserStatus::ACTIVE, 
                            'parent_id' => \Illuminate\Support\Facades\Auth::user()->id, 
                            'shop_id' => \Illuminate\Support\Facades\Auth::user()->shop_id
                        ];
                        $newUser = $this->users->create($data);
                        $newUser->attachRole($role);
                        \VanguardLTE\ShopUser::create([
                            'shop_id' => \Illuminate\Support\Facades\Auth::user()->shop_id, 
                            'user_id' => $newUser->id
                        ]);
                        if( $request->balance > 0 ) 
                        {
                            $happyhour = \VanguardLTE\HappyHour::where([
                                'shop_id' => auth()->user()->shop_id, 
                                'time' => date('G')
                            ])->first();
                            $balance = $sum = $request->balance;
                            if( $happyhour ) 
                            {
                                $transactionSum = $sum * intval(str_replace('x', '', $happyhour->multiplier));
                                $bonus = $transactionSum - $sum;
                                $wager = $bonus * intval(str_replace('x', '', $happyhour->wager));
                                \VanguardLTE\Transaction::create([
                                    'user_id' => $newUser->id, 
                                    'system' => 'HH ' . $happyhour->multiplier, 
                                    'summ' => $transactionSum, 
                                    'shop_id' => $newUser->shop_id
                                ]);
                                $newUser->increment('wager', $wager);
                                $newUser->increment('bonus', $bonus);
                                $newUser->increment('count_bonus', $bonus);
                                $balance = $transactionSum;
                            }
                            else
                            {
                                \VanguardLTE\Transaction::create([
                                    'user_id' => $newUser->id, 
                                    'payeer_id' => \Illuminate\Support\Facades\Auth::id(), 
                                    'summ' => $request->balance, 
                                    'shop_id' => $newUser->shop_id
                                ]);
                            }
                            $newUser->update([
                                'balance' => $balance, 
                                'count_balance' => $sum, 
                                'total_in' => $sum, 
                                'count_return' => \VanguardLTE\Lib\Functions::count_return($sum, $newUser->shop_id)
                            ]);
                            $shop->decrement('balance', $request->balance);
                            $open_shift->increment('balance_out', $request->balance);
                            $open_shift->increment('money_in', $request->balance);
                            $newUser->refresh();
                        }
                    }
                }
            }
            return redirect()->route(config('app.admurl').'.user.list')->withSuccess(trans('app.user_created'));
        }
        public function edit(\Illuminate\Http\Request $request, \VanguardLTE\Repositories\Activity\ActivityRepository $activitiesRepo, \VanguardLTE\User $user)
        {
            $edit = true;
            $roles = \jeremykenedy\LaravelRoles\Models\Role::where('level', '<=', \Illuminate\Support\Facades\Auth::user()->level())->pluck('name', 'id');
            $statuses = \VanguardLTE\Support\Enum\UserStatus::lists();
            $shops = $user->shops();
            $userActivities = $activitiesRepo->getLatestActivitiesForUser($user->id, 50);
            $users = auth()->user()->availableUsers();
            if( count($users) && !in_array($user->id, $users) ) 
            {
                return redirect()->route(config('app.admurl').'.user.list')->withErrors([trans('app.wrong_shop')]);
            }
            if( \Illuminate\Support\Facades\Auth::user()->role_id < $user->role_id ) 
            {
                return redirect()->route(config('app.admurl').'.user.list');
            }
            if( $shopIds = $user->shops(true) ) 
            {
                $allShops = \VanguardLTE\Shop::whereIn('id', $shopIds)->get();
            }
            else
            {
                $allShops = \VanguardLTE\Shop::where('id', 0)->get();
            }
            $free_shops = [];
            foreach( $allShops as $shop ) 
            {
                if( !$shop->distributors_count() ) 
                {
                    $free_shops[$shop->id] = $shop->name;
                }
            }
            $hasActivities = $this->hasActivities($user);
            $langs = [];
            foreach( glob(resource_path() . '/lang/*', GLOB_ONLYDIR) as $fileinfo ) 
            {
                $dirname = basename($fileinfo);
                $langs[$dirname] = $dirname;
            }
            $date = ($request->date ?: \Carbon\Carbon::now()->format('Y-m-d'));
            $numbers = \DB::select("SELECT \r\n                                        `game`, SUM(number) AS summ \r\n                                    FROM \r\n                                        `w_games_activity` \r\n                                    WHERE \r\n                                        `user_id` =:userID AND \r\n                                        `number` != \"\" AND \r\n                                        `created_at` LIKE :mydate \r\n                                    GROUP BY game \r\n                                    ORDER BY SUM(number) DESC \r\n                                    LIMIT 5", [
                'userID' => $user->id, 
                'mydate' => $date . '%'
            ]);
            $max_wins = \VanguardLTE\GameActivity::where('user_id', $user->id)->where('created_at', 'LIKE', $date . '%')->where('max_win', '!=', '')->groupBy('game')->orderBy('max_win', 'DESC')->take(5)->get();
            $max_bets = \VanguardLTE\GameActivity::where('user_id', $user->id)->where('created_at', 'LIKE', $date . '%')->where('max_bet', '!=', '')->groupBy('game')->orderBy('max_bet', 'DESC')->take(5)->get();
            $master = null;
            $agent = null;
            $distributor = null;
            if ($user->hasRole('manager'))
            {
                $distributor = $user->referral;
                $agent = $distributor->referral;
                $master = $agent->referral;
            }
            else if ($user->hasRole('distributor'))
            {
                $agent = $user->referral;
                $master = $agent->referral;
            }
            else if ($user->hasRole('agent'))
            {
                $master = $user->referral;
            }
            return view('backend.Default.user.edit', compact('edit', 'user', 'roles', 'statuses', 'shops', 'free_shops', 'userActivities', 'hasActivities', 'langs', 'max_wins', 'max_bets', 'numbers', 'master', 'agent', 'distributor'));
        }
        public function move(\VanguardLTE\User $user, \Illuminate\Http\Request $request)
        {
            $master = null;
            $agent = null;
            $distributor = null;
            if ($request->mastername)
            {
                $master = \VanguardLTE\User::where([
                    'username' => $request->mastername,
                    'role_id' => 6
                ])->first();
            }
            if (!$master)
            {
                return redirect()->back()->withErrors(['이동하려는 본사를 찾을수 없습니다.']);
            }
            if ($request->agentname)
            {
                $agent = \VanguardLTE\User::where([
                    'username' => $request->agentname,
                    'role_id' => 5,
                    'parent_id' => $master->id,
                ])->first();
            }
            if ($user->hasRole(['distributor', 'manager']) && $agent==null)
            {
                return redirect()->back()->withErrors(['이동하려는 부본사를 찾을수 없습니다.']);
            }

            if ($agent && $request->distributorname)
            {
                $distributor = \VanguardLTE\User::where([
                    'username' => $request->distributorname,
                    'role_id' => 4,
                    'parent_id' => $agent->id,
                ])->first();
            }
            if ($user->hasRole('manager') && $distributor==null)
            {
                return redirect()->back()->withErrors(['이동하려는 총판을 찾을수 없습니다.']);
            }
            //check deal percent

            if ($user->hasRole('manager'))
            {
                $deal_percent = $user->shop->deal_percent;
                $table_deal_percent = $user->shop->table_deal_percent;
                $ggr_percent = $user->shop->ggr_percent;
            }
            else
            {
                $deal_percent = $user->deal_percent;
                $table_deal_percent = $user->table_deal_percent;
                $ggr_percent = $user->ggr_percent;
            }

            if (   ($user->hasRole('agent') && $master->deal_percent < $deal_percent) 
                || ($user->hasRole('distributor') && $agent->deal_percent < $deal_percent) 
                || ($user->hasRole('manager') && $distributor->deal_percent < $deal_percent) )
            {
                return redirect()->back()->withErrors(['딜비는 상위파트너보다 클수 없습니다.']);
            }
            if (   ($user->hasRole('agent') && $master->table_deal_percent < $table_deal_percent) 
                || ($user->hasRole('distributor') && $agent->table_deal_percent < $table_deal_percent) 
                || ($user->hasRole('manager') && $distributor->table_deal_percent < $table_deal_percent) )
            {
                return redirect()->back()->withErrors(['라이브딜비는 상위파트너보다 클수 없습니다.']);
            }
            if (   ($user->hasRole('agent') && $master->ggr_percent < $ggr_percent) 
                || ($user->hasRole('distributor') && $agent->ggr_percent < $ggr_percent) 
                || ($user->hasRole('manager') && $distributor->ggr_percent < $ggr_percent) )
            {
                return redirect()->back()->withErrors(['죽장퍼센트는 상위파트너보다 클수 없습니다.']);
            }
            if ($user->hasRole('manager'))
            {
                $shopUser = \VanguardLTE\ShopUser::where(['user_id' => $user->referral->id, 'shop_id' => $user->shop->id])->first();
                if ($shopUser)
                {
                    $shopUser->update(['user_id' => $distributor->id]);
                }
                $user->shop->update(['user_id' => $distributor->id]);
                $user->update(['parent_id' => $distributor->id]);
            }
            else if ($user->hasRole('distributor'))
            {
                \VanguardLTE\ShopUser::where('user_id' , $user->referral->id)->whereIn('shop_id' ,$user->availableShops())->update(['user_id' => $agent->id]);
                $user->update(['parent_id' => $agent->id]);
            }
            else if ($user->hasRole('agent'))
            {
                \VanguardLTE\ShopUser::where('user_id' , $user->referral->id)->whereIn('shop_id' ,$user->availableShops())->update(['user_id' => $master->id]);
                $user->update(['parent_id' => $master->id]);
            }

            return redirect()->back()->withSuccess(['파트너를 이동하였습니다.']);
        }
        public function resetConfirmPwd(\VanguardLTE\User $user, \VanguardLTE\Http\Requests\User\UpdateDetailsRequest $request)
        {
            $users = auth()->user()->availableUsers();
            if( count($users) && !in_array($user->id, $users) ) 
            {
                return redirect()->route(config('app.admurl').'.user.list')->withErrors([trans('app.wrong_shop')]);
            }
            $user->update(['confirmation_token' => null]);
            return redirect()->back()->withSuccess(['환전비번을 리셋했습니다']);
        }
        public function updateDetails(\VanguardLTE\User $user, \VanguardLTE\Http\Requests\User\UpdateDetailsRequest $request)
        {
            $users = auth()->user()->availableUsers();
            if( count($users) && !in_array($user->id, $users) ) 
            {
                return redirect()->route(config('app.admurl').'.user.list')->withErrors([trans('app.wrong_shop')]);
            }
            if( \Illuminate\Support\Facades\Auth::user()->role_id < $user->role_id ) 
            {
                return redirect()->route(config('app.admurl').'.user.list');
            }
            $request->validate([
                'username' => 'required|unique:users,username,' . $user->id, 
                'email' => 'nullable|unique:users,email,' . $user->id
            ]);
            $count = \VanguardLTE\User::where([
                'shop_id' => \Illuminate\Support\Facades\Auth::user()->shop_id, 
                'role_id' => 1
            ])->count();
            $data = $request->all();
            
            if( empty($data['password_confirmation']) ) 
            {
                unset($data['password_confirmation']);
            }
            if( empty($data['password']) ) 
            {
                unset($data['password']);
            }
            else
            {
                if (empty($data['password_confirmation']))
                {
                    return redirect()->back()->withErrors(['확인비밀번호를 입력해주세요']);
                }
                if ($data['password_confirmation'] != $data['password'])
                {
                    return redirect()->back()->withErrors(['확인비밀번호와 맞지 않습니다']);
                }
            }

            if( empty($data['confirmation_token_confirmation']) ) 
            {
                unset($data['confirmation_token_confirmation']);
            }

            if( empty($data['confirmation_token']) ) 
            {
                unset($data['confirmation_token']);
            }
            else
            {
                if (empty($data['confirmation_token_confirmation']))
                {
                    return redirect()->back()->withErrors(['확인 환전비밀번호를 입력해주세요']);
                }
                if ($data['confirmation_token_confirmation'] != $data['confirmation_token'])
                {
                    return redirect()->back()->withErrors(['확인 환전비밀번호와 맞지 않습니다']);
                }

                $old_confirm_token = $data['old_confirmation_token'];
                if(!empty($user->confirmation_token) && !\Illuminate\Support\Facades\Hash::check($old_confirm_token, $user->confirmation_token) ) 
                {
                    return redirect()->back()->withErrors(['이전 환전비밀번호가 틀립니다']);
                }
                $data['confirmation_token'] = \Illuminate\Support\Facades\Hash::make($data['confirmation_token']);
            }
            


            if( isset($data['role_id']) && $user->role_id != $data['role_id'] && $data['role_id'] == 1 && $this->max_users <= ($count + 1) ) 
            {
                return redirect()->route(config('app.admurl').'.user.list')->withErrors([trans('max_users', ['max' => $this->max_users])]);
            }
            unset($data['role_id']);

            if( $user->hasRole([
                'distributor', 'agent', 'master', 'user'
            ]))
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
                if (!$user->hasRole('user') && $parent!=null && !$parent->isInoutPartner() && isset($data['ggr_percent']) && $parent->ggr_percent < $data['ggr_percent'])
                {
                    return redirect()->back()->withErrors(['죽장퍼센트는 상위파트너보다 클수 없습니다']);
                }
            }
            if ($user->last_reset_at == null )
            {
                $data['last_reset_at'] = date('Y-m-d');
            }
            $this->users->update($user->id, $data);
            if (isset($data['reset_days']))
            {
                //update all child partners reset days
                $child_partners = $user->hierarchyPartners();
                $shops = $user->availableShops();
                \VanguardLTE\User::whereIn('id', $child_partners)->update(['reset_days' => $data['reset_days']]);
                \VanguardLTE\Shop::whereIn('id', $shops)->update(['reset_days' => $data['reset_days']]);
            }
            if( $user->hasRole([
                'distributor', 
                'cashier', 
                'user'
            ]) && $request->shops && count($request->shops) ) 
            {
                foreach( $request->shops as $shop ) 
                {
                    \VanguardLTE\ShopUser::create([
                        'shop_id' => $shop, 
                        'user_id' => $user->id
                    ]);
                }
            }
            if( $user->hasRole([
                'agent', 
                'distributor'
            ]) && $request->free_shops && count($request->free_shops) ) 
            {
                foreach( $request->free_shops as $shop ) 
                {
                    \VanguardLTE\ShopUser::create([
                        'shop_id' => $shop, 
                        'user_id' => $user->id
                    ]);
                }
            }
            if (auth()->user()->isInOutPartner()){
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
            }
            event(new \VanguardLTE\Events\User\UpdatedByAdmin($user));
            if( $this->userIsBanned($user, $request) ) 
            {
                event(new \VanguardLTE\Events\User\Banned($user));
            }
            return redirect()->back()->withSuccess(trans('app.user_updated'));
        }
        public function setBonusSetting($userid) {
            $user = \VanguardLTE\User::find($userid);
            if($user->rating == 1) {
                $user->rating = 2;
                $bank = \VanguardLTE\GameBank::where('shop_id', $user->shop_id)->first();
                $bank->update(['bonus' => 50000000]);
            }
            else {
                $user->rating = 1;
            }
            $user->save();
            
            return redirect()->route(config('app.admurl').'.user.list');
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
                return redirect()->back()->withErrors(['회원/파트너를 찾을수 없습니다.']);
            }

            if (!in_array($user->id, auth()->user()->availableUsers()))
            {
                return redirect()->back()->withErrors(['회원/파트너를 찾을수 없습니다.']);
            }

            if ($user->playing_game != null )
            {
                return redirect()->back()->withErrors(['게임중에는 충환전을 할수 없습니다.']);
            }

            $summ = str_replace(',','',$request->summ);

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
            //currently user is playing games with balance transfer mode.
            
            return redirect()->back()->withSuccess($result['message']);
        }
        public function statistics(\VanguardLTE\User $user, \Illuminate\Http\Request $request)
        {
            $statistics = \VanguardLTE\Transaction::where('user_id', $user->id)->orderBy('created_at', 'DESC')->paginate(20);
            return view('backend.Default.stat.pay_stat', compact('user', 'statistics'));
        }
        private function userIsBanned(\VanguardLTE\User $user, \Illuminate\Http\Request $request)
        {
            return $user->status != $request->status && $request->status == \VanguardLTE\Support\Enum\UserStatus::BANNED;
        }
        public function updateAvatar(\VanguardLTE\User $user, \VanguardLTE\Services\Upload\UserAvatarManager $avatarManager, \Illuminate\Http\Request $request)
        {
            $this->validate($request, ['avatar' => 'image']);
            $name = $avatarManager->uploadAndCropAvatar($user, $request->file('avatar'), $request->get('points'));
            if( $name ) 
            {
                $this->users->update($user->id, ['avatar' => $name]);
                event(new \VanguardLTE\Events\User\UpdatedByAdmin($user));
                return redirect()->route(config('app.admurl').'.user.edit', $user->id)->withSuccess(trans('app.avatar_changed'));
            }
            return redirect()->route(config('app.admurl').'.user.edit', $user->id)->withErrors(trans('app.avatar_not_changed'));
        }
        public function updateAddress(\VanguardLTE\User $user, \VanguardLTE\Services\Upload\UserAvatarManager $avatarManager, \Illuminate\Http\Request $request)
        {
            $user->update(['address' => $request->address]);
            return redirect()->back()->withSuccess('연락처가 변경되었습니다');
        }
        public function updateAvatarExternal(\VanguardLTE\User $user, \Illuminate\Http\Request $request, \VanguardLTE\Services\Upload\UserAvatarManager $avatarManager)
        {
            $avatarManager->deleteAvatarIfUploaded($user);
            $this->users->update($user->id, ['avatar' => $request->get('url')]);
            event(new \VanguardLTE\Events\User\UpdatedByAdmin($user));
            return redirect()->route(config('app.admurl').'.user.edit', $user->id)->withSuccess(trans('app.avatar_changed'));
        }
        public function updateLoginDetails(\VanguardLTE\User $user, \VanguardLTE\Http\Requests\User\UpdateLoginDetailsRequest $request)
        {
            // $data = $request->all();
            // if( trim($data['password']) == '' ) 
            // {
            //     unset($data['password']);
            //     unset($data['password_confirmation']);
            // }
            // $this->users->update($user->id, $data);
            // event(new \VanguardLTE\Events\User\UpdatedByAdmin($user));
            return redirect()->route(config('app.admurl').'.user.edit', $user->id)->withSuccess(trans('app.login_updated'));
        }
        public function delete(\VanguardLTE\User $user)
        {
            if( $user->id == \Illuminate\Support\Facades\Auth::id() ) 
            {
                return redirect()->back()->withErrors(trans('app.you_cannot_delete_yourself'));
            }
            if( $user->balance > 0 ) 
            {
                return redirect()->back()->withErrors([trans('app.balance_not_zero')]);
            }
            if(!auth()->user()->hasRole(['manager']) && ($count = \VanguardLTE\User::where('status', '<>', \VanguardLTE\Support\Enum\UserStatus::DELETED)->where('parent_id', $user->id)->count()) ) 
            {
                return redirect()->back()->withErrors([trans('app.has_users', ['name' => $user->username])]);
            }

            $user->update(['status' => \VanguardLTE\Support\Enum\UserStatus::DELETED]);
            \VanguardLTE\Task::create([
                'user_id' => auth()->user()->id,
                'category' => 'user', 
                'action' => 'delete', 
                'item_id' => $user->id
            ]);
            
            event(new \VanguardLTE\Events\User\Deleted($user));
            return redirect()->route(config('app.admurl').'.user.list')->withSuccess(trans('app.user_deleted'));
        }
        public function hard_delete(\VanguardLTE\User $user)
        {
            if( $user->id == \Illuminate\Support\Facades\Auth::id() ) 
            {
                return redirect()->route(config('app.admurl').'.user.list')->withErrors(trans('app.you_cannot_delete_yourself'));
            }
            if( auth()->user()->role_id <= $user->role_id ) 
            {
                return redirect()->route(config('app.admurl').'.user.list')->withErrors([trans('app.no_permission')]);
            }
            $agents = null;
            $distributors = null;
            if( $user->hasRole('comaster') ) 
            {
                $masters = $user->childPartners();
                $agents = \VanguardLTE\User::where('role_id' , 5)->whereIn('parent_id' , $masters )->get();
                $distributors = \VanguardLTE\User::where('role_id' , 4)->whereIn('parent_id', $agents->pluck('id')->toArray())->get();
            }
            if( $user->hasRole('master') ) 
            {
                $agents = \VanguardLTE\User::where([
                    'parent_id' => $user->id, 
                    'role_id' => 5
                ])->get();
                $distributors = \VanguardLTE\User::where('role_id' , 4)->whereIn('parent_id', $agents->pluck('id')->toArray())->get();
            }
            if( $user->hasRole('agent') ) 
            {
                $distributors = \VanguardLTE\User::where([
                    'parent_id' => $user->id, 
                    'role_id' => 4
                ])->get();
            }
            if( $user->hasRole('distributor') ) 
            {
                $distributors = \VanguardLTE\User::where(['id' => $user->id])->get();
            }
            if( $distributors ) 
            {
                foreach( $distributors as $distributor ) 
                {
                    if( $distributor->rel_shops ) 
                    {
                        foreach( $distributor->rel_shops as $shop ) 
                        {
                            \VanguardLTE\ShopUser::where('shop_id', $shop->shop_id)->delete();
                            \VanguardLTE\Shop::where('id', $shop->shop_id)->delete();
                            \VanguardLTE\Task::create([
                                'category' => 'shop', 
                                'action' => 'delete', 
                                'item_id' => $shop->shop_id, 
                                'user_id' => auth()->user()->id
                            ]);
                            \VanguardLTE\User::whereIn('role_id', [
                                1, 
                                2, 
                                3
                            ])->where('shop_id', $shop->shop_id)->delete();
                        }
                    }
                    event(new \VanguardLTE\Events\User\Deleted($distributor));
                    $distributor->delete();
                }
            }
            $bUser = false;
            if($user->hasRole('user')){
                $bUser = true;
            }
            if ($agents)
            {
                foreach( $agents as $agent ) 
                {
                    event(new \VanguardLTE\Events\User\Deleted($agent));
                    \VanguardLTE\ShopUser::where('user_id', $agent->id)->delete();
                    $agent->delete();
                }
            }
            if($user->hasRole(['comaster','master','agent'])) 
            {
                event(new \VanguardLTE\Events\User\Deleted($user));
                \VanguardLTE\ShopUser::where('user_id', $user->id)->delete();
                $user->delete();
            }

            if($bUser){
                return redirect()->route(config('app.admurl').'.user.tree')->withSuccess(trans('app.user_deleted'));
            }
            return redirect()->route(config('app.admurl').'.user.list')->withSuccess('파트너가 성공적으로 삭제되었습니다.');
            
        }
        public function hasActivities($user)
        {
            if( $user->hasRole([
                'distributor', 
                'manager', 
                'cashier'
            ]) ) 
            {
                $transactions = \VanguardLTE\Transaction::where('payeer_id', $user->id)->count();
                if( $transactions ) 
                {
                    return true;
                }
                $stats = \VanguardLTE\BankStat::where('user_id', $user->id)->count();
                if( $stats ) 
                {
                    return true;
                }
                $stats = \VanguardLTE\StatGame::where('user_id', $user->id)->count();
                if( $stats ) 
                {
                    return true;
                }
                $stats = \VanguardLTE\ShopStat::where('user_id', $user->id)->count();
                if( $stats ) 
                {
                    return true;
                }
                $open_shifts = \VanguardLTE\OpenShift::where('user_id', $user->id)->count();
                if( $open_shifts ) 
                {
                    return true;
                }
            }
            return false;
        }
        public function sessions(\VanguardLTE\User $user, \VanguardLTE\Repositories\Session\SessionRepository $sessionRepository)
        {
            $adminView = true;
            $sessions = $sessionRepository->getUserSessions($user->id);
            return view('backend.Default.user.sessions', compact('sessions', 'user', 'adminView'));
        }
        public function invalidateSession(\VanguardLTE\User $user, $session, \VanguardLTE\Repositories\Session\SessionRepository $sessionRepository)
        {
            $sessionRepository->invalidateSession($session->id);
            return redirect()->route(config('app.admurl').'.user.sessions', $user->id)->withSuccess(trans('app.session_invalidated'));
        }
        public function action($action)
        {
            $open_shift = \VanguardLTE\OpenShift::where([
                'shop_id' => \Illuminate\Support\Facades\Auth::user()->shop_id, 
                'end_date' => null
            ])->first();
            if( !$open_shift ) 
            {
                return redirect()->back()->withErrors([trans('app.shift_not_opened')]);
            }
            $shop = \VanguardLTE\Shop::find(\Illuminate\Support\Facades\Auth::user()->shop_id);
            if( $action && in_array($action, [
                'users_out', 
                'pin_out'
            ]) ) 
            {
                switch( $action ) 
                {
                    case 'users_out':
                        $users = \VanguardLTE\User::where('shop_id', $shop->id)->get();
                        foreach( $users as $user ) 
                        {
                            $sum = $user->balance;
                            if( $sum <= 0 ) 
                            {
                                continue;
                            }
                            $transaction = new \VanguardLTE\Transaction();
                            $transaction->user_id = $user->id;
                            $transaction->payeer_id = \Illuminate\Support\Facades\Auth::id();
                            $transaction->type = 'out';
                            $transaction->summ = abs($sum);
                            $transaction->shop_id = $user->shop_id;
                            $transaction->save();
                            $user->update([
                                'balance' => 0, 
                                'count_balance' => 0, 
                                'count_return' => 0
                            ]);
                            if( \Illuminate\Support\Facades\Auth::user()->hasRole('cashier') && $user->hasRole('user') ) 
                            {
                                $shop->update(['balance' => $shop->balance + $sum]);
                                $open_shift->increment('balance_in', abs($sum));
                                $open_shift->increment('money_out', abs($sum));
                                $user->increment('total_out', abs($sum));
                            }
                            $user->fresh();
                            if( $user->balance == 0 ) 
                            {
                                $user->update([
                                    'wager' => 0, 
                                    'bonus' => 0
                                ]);
                            }
                            if( $user->count_balance < 0 ) 
                            {
                                $user->update([
                                    'count_balance' => 0, 
                                    'count_return' => 0
                                ]);
                            }
                            if( $user->count_return < 0 ) 
                            {
                                $user->update(['count_return' => 0]);
                            }
                        }
                        return redirect()->back()->withSuccess(trans('app.balance_updated'));
                        break;
                    case 'pin_out':
                        $pincodes = \VanguardLTE\Pincode::where('shop_id', $shop->id)->get();
                        foreach( $pincodes as $pincode ) 
                        {
                            $sum = $pincode->nominal;
                            if( $sum <= 0 ) 
                            {
                                continue;
                            }
                            if( \Illuminate\Support\Facades\Auth::user()->hasRole('cashier') ) 
                            {
                                $shop->update(['balance' => $shop->balance + $pincode->nominal]);
                                $open_shift->increment('balance_in', $pincode->nominal);
                                $open_shift->increment('money_out', abs($pincode->nominal));
                            }
                            \VanguardLTE\Pincode::where('id', $pincode->id)->delete();
                        }
                        return redirect()->back()->withSuccess(trans('app.balance_updated'));
                        break;
                }
            }
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

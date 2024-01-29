<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend
{
    class ShopsController extends \VanguardLTE\Http\Controllers\Controller
    {
        private $max_shops = 1000;
        public function __construct()
        {
            $this->middleware('auth');
            $this->middleware('permission:access.admin.panel');
            $this->middleware('permission:shops.manage');
        }
        public function index(\Illuminate\Http\Request $request)
        {
/*            $checked = new \VanguardLTE\Lib\LicenseDK();
            $license_notifications_array = $checked->aplVerifyLicenseDK(null, 0);
            if( $license_notifications_array['notification_case'] != 'notification_license_ok' ) 
            {
                return redirect()->route('frontend.page.error_license');
            }
            if( !$this->security() ) 
            {
                return redirect()->route('frontend.page.error_license');
            }*/
            $shops = \VanguardLTE\Shop::select('shops.*', 'shops.id AS shop_id')->where('pending','<>', 2);
            if( $shopIds = auth()->user()->shops(true) ) 
            {
                $shops = $shops->whereIn('shops.id', $shopIds);
            }
            else
            {
                $shops = $shops->where('shops.id', 0);
            }
            if( $request->name != '' ) 
            {
                $shops = $shops->where('shops.name', 'like', '%'.$request->name.'%');
            }
            if( $request->credit_from != '' ) 
            {
                $shops = $shops->where('shops.balance', '>=', $request->credit_from);
            }
            if( $request->credit_to != '' ) 
            {
                $shops = $shops->where('shops.balance', '<=', $request->credit_to);
            }
            if( $request->frontend != '' ) 
            {
                $shops = $shops->where('shops.frontend', $request->frontend);
            }
            if( $request->percent_from != '' ) 
            {
                $shops = $shops->where('shops.percent', '>=', $request->percent_from);
            }
            if( $request->percent_to != '' ) 
            {
                $shops = $shops->where('shops.percent', '<=', $request->percent_to);
            }
            if( $request->order != '' ) 
            {
                $shops = $shops->where('shops.orderby', $request->order);
            }
            if( $request->currency != '' ) 
            {
                $shops = $shops->where('shops.currency', $request->currency);
            }
            if( $request->status != '' ) 
            {
                $shops = $shops->where('shops.pending', '<>', $request->status);
            }
            if( $request->categories ) 
            {
                $shops = $shops->join('shop_categories', 'shop_categories.shop_id', '=', 'shops.id');
                $shops = $shops->whereIn('shop_categories.category_id', $request->categories);
            }
            if( $request->users != '' ) 
            {
                $shops = $shops->join('shops_user', 'shops_user.shop_id', '=', 'shops.id');
                $shops = $shops->where('shops_user.user_id', $request->users);
            }
            $categories = \VanguardLTE\Category::where([
                'parent' => 0, 
                'shop_id' => auth()->user()->shop_id
            ])->get();
            $stats = [
                'shops' => $shops->count(), 
                'masters' => 0, 
                'agents' => 0, 
                'distributors' => 0, 
                'managers' => 0, 
                'cashiers' => 0, 
                'users' => 0, 
                'credit' => $shops->sum('balance')
            ];
            $countAgents = [];
            $countDistributors = [];
            if( $shops ) 
            {
                foreach( $shops as $shop ) 
                {
                    if( $shop->users ) 
                    {
                        foreach( $shop->users as $user ) 
                        {
                            if ($user->user){
                                if( $user->user->hasRole('agent') ) 
                                {
                                    $countAgents[$user->user->username] = 1;
                                }
                                if( $user->user->hasRole('distributor') ) 
                                {
                                    $countDistributors[$user->user->username] = 1;
                                }
                                if( $user->user->hasRole('manager') ) 
                                {
                                    $stats['managers']++;
                                }
                                if( $user->user->hasRole('cashier') ) 
                                {
                                    $stats['cashiers']++;
                                }
                                if( $user->user->hasRole('user') ) 
                                {
                                    $stats['users']++;
                                }
                            }
                        }
                    }
                }
            }
            $distributors = [];
            $agents = [];
            if( auth()->user()->hasRole(['comaster']) ) 
            {
                $masters = auth()->user()->childPartners();
                $stats['masters'] = count($masters);
                if (count($masters) > 0){
                    $agents = \VanguardLTE\User::where('role_id', 5)->whereIn('parent_id', $masters);
                    $stats['agents'] = $agents->count();
                    $distributors = \VanguardLTE\User::where('role_id', 4)->whereIn('parent_id', $agents->pluck('id')->toArray());
                    $stats['distributors'] = $distributors->count();
                    $distributors = $distributors->pluck('username', 'id')->toArray();
                    $agents =$agents->pluck('username', 'id')->toArray();
                }
            }
            if( auth()->user()->hasRole(['master']) ) 
            {
                $agents = \VanguardLTE\User::where(['role_id' => 5,'parent_id' => auth()->user()->id]);
                $stats['agents'] = $agents->count();
                $distributors = \VanguardLTE\User::where('role_id', 4)->whereIn('parent_id', $agents->pluck('id')->toArray());
                $stats['distributors'] = $distributors->count();
                $distributors = $distributors->pluck('username', 'id')->toArray();
                $agents =$agents->pluck('username', 'id')->toArray();
            }
            if( auth()->user()->hasRole('agent') ) 
            {
                $distributors = \VanguardLTE\User::where([
                    'role_id' => 4, 
                    'parent_id' => auth()->user()->id
                ]);
                $stats['distributors'] = $distributors->count();
                $distributors = $distributors->pluck('username', 'id')->toArray();
            }
            if( auth()->user()->hasRole('distributor') ) 
            {
                $stats['distributors'] = 1;
            }
            if( auth()->user()->hasRole('manager') ) 
            {
                $stats['distributors'] = 1;
            }
            /*if( count($request->all()) ) 
            {
                $stats['agents'] = count($countAgents);
                $stats['distributors'] = count($countDistributors);
            } */
          
            $shops = $shops->paginate(25);
            return view('backend.Default.shops.list', compact('shops', 'categories', 'stats', 'agents', 'distributors'));
        }
        public function create()
        {
            if( !auth()->user()->hasRole('distributor') ) 
            {
                return redirect()->route(config('app.admurl').'.shop.list')->withErrors([trans('app.only_for_distributors')]);
            }
            $directories = [];
            foreach( glob(public_path() . '/frontend/*', GLOB_ONLYDIR) as $fileinfo ) 
            {
                $dirname = basename($fileinfo);
                $directories[$dirname] = $dirname;
            }
            $categories = \VanguardLTE\Category::where([
                'parent' => 0, 
                'shop_id' => 0
            ])->get();
            $shop = new \VanguardLTE\Shop();
            $availibleUsers = [];
            if( auth()->user()->hasRole('admin') ) 
            {
                $me = \VanguardLTE\User::where('id', \Auth::id())->get();
                $availibleUsers = \VanguardLTE\User::whereIn('role_id', [
                    4, 
                    5
                ])->has('rel_shops')->get();
                $availibleUsers = $me->merge($availibleUsers);
            }
            if( auth()->user()->hasRole('agent') ) 
            {
                $me = \VanguardLTE\User::where('id', \Auth::id())->get();
                $distributors = \VanguardLTE\User::where([
                    'parent_id' => auth()->user()->id, 
                    'role_id' => 4
                ])->has('rel_shops')->get();
                $availibleUsers = $me->merge($distributors);
            }
            if( auth()->user()->hasRole('distributor') ) 
            {
                $availibleUsers = \VanguardLTE\User::where('id', \Auth::id())->has('rel_shops')->get();
            }
            $blocks = [];
            if( auth()->user()->hasPermission('shops.unblock') ) 
            {
                $blocks[0] = __('app.unblock');
            }
            if( auth()->user()->hasPermission('shops.block') ) 
            {
                $blocks[1] = __('app.block');
            }
            return view('backend.Default.shops.add', compact('directories', 'categories', 'shop', 'availibleUsers', 'blocks'));
        }
        public function store(\Illuminate\Http\Request $request)
        {
            if( $this->max_shops <= \VanguardLTE\Shop::count() ) 
            {
                return redirect()->route(config('app.admurl').'.shop.list')->withErrors([trans('max_shops', ['max' => config('limits.max_shops')])]);
            }
            if( !auth()->user()->hasRole('distributor') ) 
            {
                return redirect()->route(config('app.admurl').'.shop.list')->withErrors([trans('app.only_for_distributors')]);
            }

            $validatedData = $request->validate([
                'name' => 'required|unique:shops|max:255', 
                'currency' => 'present|in:' . implode(',', \VanguardLTE\Shop::$values['currency']), 
                //'percent' => 'required|in:' . implode(',', \VanguardLTE\Shop::$values['percent']), 
                'orderby' => 'required|in:' . implode(',', \VanguardLTE\Shop::$values['orderby'])
            ]);
            $manager = $request->only([
                'username', 
                'password', 
            ]);
            if (empty($request->input('username'))) {
                $manager['username'] = $request->input('name');
            }
            $validator = \Illuminate\Support\Facades\Validator::make($manager, [
                'username' => 'required|regex:/^[A-Za-z0-9가-힣]+$/|unique:users,username', 
                'password' => 'required|min:6'
            ]);
            if( $validator->fails() ) 
            {
                return redirect()->back()->withErrors($validator)->with('blockError', implode(',',$manager))->withInput();
            }

            $data = $request->all();
            $parent = auth()->user();
            if ($parent!=null && $parent->deal_percent < $data['deal_percent'])
            {
                return redirect()->route(config('app.admurl').'.shop.list')->withErrors(['딜비는 상위파트너보다 클수 없습니다']);
            }
            if ($parent!=null && $parent->table_deal_percent < $data['table_deal_percent'])
            {
                return redirect()->route(config('app.admurl').'.shop.list')->withErrors(['라이브딜비는 상위파트너보다 클수 없습니다']);
            } 
            if ($parent!=null && $parent->ggr_percent < $data['ggr_percent'])
            {
                return redirect()->route(config('app.admurl').'.shop.list')->withErrors(['죽장퍼센트는 상위파트너보다 클수 없습니다']);
            } 
            $data['reset_days'] = auth()->user()->reset_days;
            $data['last_reset_at'] = date('Y-m-d');
            $shop = \VanguardLTE\Shop::create($data + ['user_id' => auth()->user()->id]);
            $user = \VanguardLTE\User::find(\Auth::id());
            if( isset($request->categories) && count($request->categories) ) 
            {
                foreach( $request->categories as $category ) 
                {
                    \VanguardLTE\ShopCategory::create([
                        'shop_id' => $shop->id, 
                        'category_id' => $category
                    ]);
                }
            }
            \VanguardLTE\ShopUser::create([
                'shop_id' => $shop->id, 
                'user_id' => auth()->user()->id
            ]);
            if( auth()->user()->hasRole('distributor') ) 
            {
                \VanguardLTE\ShopUser::create([
                    'shop_id' => $shop->id, 
                    'user_id' => auth()->user()->parent_id
                ]); //for agent
                \VanguardLTE\ShopUser::create([
                    'shop_id' => $shop->id, 
                    'user_id' => auth()->user()->referral->parent_id
                ]); //for master
            }
            $user->update(['shop_id' => $shop->id]);
            //get comaster id
            $comaster = $parent;
            while ($comaster!=null && !$comaster->isInoutPartner())
            {
                $comaster = $comaster->referral;
            }
            $site = null;
            if ($comaster == null){
                $site = \VanguardLTE\WebSite::where('domain', \Request::root())->first();
            }
            else
            {
                $site = \VanguardLTE\WebSite::where('adminid', $comaster->id)->first();
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

            $manager = \VanguardLTE\User::create($manager + [
                'parent_id' => auth()->user()->id, 
                'role_id' => 3,
                'status' => \VanguardLTE\Support\Enum\UserStatus::ACTIVE, 
            ]);
            $roles = \jeremykenedy\LaravelRoles\Models\Role::get();
            $manager->attachRole($roles->find(3));

            $manager->update(['shop_id' => $shop->id]);

            \VanguardLTE\ShopUser::create([
                'shop_id' => $shop->id, 
                'user_id' => $manager->id
            ]);
            
            event(new \VanguardLTE\Events\Shop\ShopCreated($shop));

            return redirect()->route(config('app.admurl').'.shop.list')->withSuccess(trans('app.shop_created'));
        }
        public function admin_create()
        {
            if( !auth()->user()->hasRole('admin') ) 
            {
                return redirect()->route(config('app.admurl').'.shop.list')->withErrors([trans('app.only_for_distributors')]);
            }
            $directories = [];
            foreach( glob(public_path() . '/frontend/*', GLOB_ONLYDIR) as $fileinfo ) 
            {
                $dirname = basename($fileinfo);
                $directories[$dirname] = $dirname;
            }
            $categories = \VanguardLTE\Category::where([
                'parent' => 0, 
                'shop_id' => 0
            ])->get();
            $shop = new \VanguardLTE\Shop();
            $availibleUsers = [];
            if( auth()->user()->hasRole('admin') ) 
            {
                $me = \VanguardLTE\User::where('id', \Auth::id())->get();
                $availibleUsers = \VanguardLTE\User::whereIn('role_id', [
                    4, 
                    5
                ])->has('rel_shops')->get();
                $availibleUsers = $me->merge($availibleUsers);
            }
            $blocks = [];
            if( auth()->user()->hasPermission('shops.unblock') ) 
            {
                $blocks[0] = __('app.unblock');
            }
            if( auth()->user()->hasPermission('shops.block') ) 
            {
                $blocks[1] = __('app.block');
            }
            $statuses = \VanguardLTE\Support\Enum\UserStatus::lists();
            return view('backend.Default.shops.admin', compact('directories', 'categories', 'shop', 'availibleUsers', 'blocks', 'statuses'));
        }
        public function admin_store(\Illuminate\Http\Request $request)
        {
            return redirect()->route(config('app.admurl').'.shop.list')->withErrors(['Not implemented yet']);
            $shop = $request->only([
                'name', 
                'percent', 
                'frontend', 
                'orderby', 
                'currency', 
                'categories', 
                'balance'
            ]);
            $agent = $request->input('agent');
            $distributor = $request->input('distributor');
            $manager = $request->input('manager');
            // $cashier = $request->input('cashier');
            $users = $request->input('users');
            if( $this->max_shops <= \VanguardLTE\Shop::count() ) 
            {
                return redirect()->back()->with('blockError', 'SHOP')->withErrors([trans('max_shops', ['max' => config('limits.max_shops')])])->withInput();
            }
            $request->validate([
                'name' => 'required|unique:shops|max:255', 
                'currency' => 'present|in:' . implode(',', \VanguardLTE\Shop::$values['currency']), 
                'percent' => 'required|in:' . implode(',', \VanguardLTE\Shop::$values['percent']), 
                'orderby' => 'required|in:' . implode(',', \VanguardLTE\Shop::$values['orderby'])
            ]);
            foreach( [
                'agent', 
                'distributor', 
                'manager' 
                //'cashier'
            ] as $role_name ) 
            {
                $validator = \Illuminate\Support\Facades\Validator::make($request->input($role_name), [
                    'username' => 'required|regex:/^[A-Za-z0-9가-힣]+$/|unique:users,username', 
                    'password' => 'required|min:6'
                ]);
                if( $validator->fails() ) 
                {
                    return redirect()->back()->withErrors($validator)->with('blockError', $role_name)->withInput();
                }
            }
            $usersBalance = floatval($users['balance'] * $users['count']);
//            $manager['balance'] = $shop['balance'];			
//            $managerBalance = floatval($manager['balance']);
            $distributorBalance = floatval($distributor['balance']);
            $agentBalance = floatval($agent['balance']);
            $shopBalance = floatval($shop['balance']);
            $shop['balance'] = 0;
            $manager['balance'] = $shop['balance'];
            $distributor['balance'] = $manager['balance'];
            $agent['balance'] = $distributor['balance'];
            if( $usersBalance < 0 || $distributorBalance < 0 || $agentBalance < 0 || $shopBalance < 0 ) 
            {
                return redirect()->back()->withErrors(['Error balance < 0'])->withInput();
            }
            if( $usersBalance > 0 && ($shopBalance <= 0 || $shopBalance < $usersBalance) ) 
            {
                return redirect()->back()->withErrors(['Error balance: Users > Shop'])->withInput();
            }
            if( $shopBalance > 0 && ($distributorBalance <= 0 || $distributorBalance < $shopBalance) ) 
            {
                return redirect()->back()->withErrors(['Error balance: Manager+shop > Distributor'])->withInput();
            }
            if( $distributorBalance > 0 && ($agentBalance <= 0 || $agentBalance < $distributorBalance) ) 
            {
                return redirect()->back()->withErrors(['Error balance: Distributor > Agent'])->withInput();
            }
            $roles = \jeremykenedy\LaravelRoles\Models\Role::get();
            $agent = \VanguardLTE\User::create($agent + [
                'parent_id' => auth()->user()->id, 
                'role_id' => 5
            ]);
            $agent->attachRole($roles->find(5));
            $distributor = \VanguardLTE\User::create($distributor + [
                'parent_id' => $agent->id, 
                'role_id' => 4
            ]);
            $distributor->attachRole($roles->find(4));
            $manager = \VanguardLTE\User::create($manager + [
                'parent_id' => $distributor->id, 
                'role_id' => 3
            ]);
            $manager->attachRole($roles->find(3));
            // $cashier = \VanguardLTE\User::create($cashier + [
            //     'parent_id' => $manager->id, 
            //     'role_id' => 2
            // ]);
            // $cashier->attachRole($roles->find(2));
            $shop = \VanguardLTE\Shop::create($shop + ['user_id' => $distributor->id]);
            $open_shift = \VanguardLTE\OpenShift::create([
                'start_date' => \Carbon\Carbon::now(), 
                'balance' => $shop->balance, 
                'user_id' => $distributor->id, 
                'shop_id' => $shop->id
            ]);
            if( $agentBalance > 0 ) 
            {
                $agent->addBalance('add', $agentBalance);
            }
            if( $distributorBalance > 0 ) 
            {
                $distributor->addBalance('add', $distributorBalance, $agent);
            }
            if( $shopBalance > 0 ) 
            {
                $open_shift->increment('balance_in', $shopBalance);
                $distributor->decrement('balance', $shopBalance);
                $shop->increment('balance', $shopBalance);
                \VanguardLTE\ShopStat::create([
                    'user_id' => $distributor->id, 
                    'shop_id' => $shop->id, 
                    'type' => 'add', 
                    'sum' => $shopBalance
                ]);
            }
            foreach( [
                $agent, 
                $distributor, 
                $manager/*, 
                $cashier*/
            ] as $user ) 
            {
                \VanguardLTE\ShopUser::create([
                    'shop_id' => $shop->id, 
                    'user_id' => $user->id
                ]);
                $user->update(['shop_id' => $shop->id]);
            }
            $role = \jeremykenedy\LaravelRoles\Models\Role::find(1);
            for( $i = 0; $i < $users['count']; $i++ ) 
            {
                $number = rand(111111111, 999999999);
                $data = [
                    'username' => $number, 
                    'password' => $number, 
                    'role_id' => $role->id, 
                    'status' => \VanguardLTE\Support\Enum\UserStatus::ACTIVE, 
                    'shop_id' => $shop->id, 
                    'parent_id' => $manager->id
                    //'parent_id' => $cashier->id
                ];
                $newUser = \VanguardLTE\User::create($data);
                $newUser->attachRole($role);
                if( $users['balance'] > 0 ) 
                {
                    //$newUser->addBalance('add', $users['balance'], $cashier, $users['return']);
                    $newUser->addBalance('add', $users['balance'], $manager, 0);
                }
                \VanguardLTE\ShopUser::create([
                    'shop_id' => $shop->id, 
                    'user_id' => $newUser->id
                ]);
                $newUser->update(['shop_id' => $shop->id]);
            }
            if( $request->input('categories') && count($request->input('categories')) ) 
            {
                foreach( $request->input('categories') as $category ) 
                {
                    \VanguardLTE\ShopCategory::create([
                        'shop_id' => $shop->id, 
                        'category_id' => $category
                    ]);
                }
            }
            \VanguardLTE\Task::create([
                'category' => 'shop', 
                'action' => 'create', 
                'item_id' => $shop->id
            ]);
            return redirect()->route(config('app.admurl').'.shop.list')->withSuccess(trans('app.shop_created'));
        }
        public function edit($shop)
        {
            $shop = \VanguardLTE\Shop::where('id', $shop)->first();
            if( !$shop ) 
            {
                return redirect()->route(config('app.admurl').'.shop.list')->withErrors([trans('app.wrong_shop')]);
            }
            $categories = \VanguardLTE\Category::where([
                'parent' => 0, 
                'shop_id' => 0
            ])->get();
            if( \Auth::user()->hasRole([
                'master', 
                'agent', 
                'distributor', 
                'manager', 
                'cashier'
            ]) ) 
            {
                $ids = \VanguardLTE\ShopUser::where('user_id', \Auth::id())->pluck('shop_id')->toArray();
                if( !(count($ids) && in_array($shop->id, $ids)) ) 
                {
                    return redirect()->route(config('app.admurl').'.shop.list')->withErrors([trans('app.wrong_shop')]);
                }
            }
            $directories = [];
            foreach( glob(public_path() . '/frontend/*', GLOB_ONLYDIR) as $fileinfo ) 
            {
                $dirname = basename($fileinfo);
                $directories[$dirname] = $dirname;
            }
            $cats = \VanguardLTE\ShopCategory::where('shop_id', $shop->id)->pluck('category_id')->toArray();
            $blocks = [];
            if( auth()->user()->hasPermission('shops.unblock') ) 
            {
                $blocks[0] = __('app.unblock');
            }
            if( auth()->user()->hasPermission('shops.block') ) 
            {
                $blocks[1] = __('app.block');
            }
            return view('backend.Default.shops.edit', compact('shop', 'directories', 'categories', 'cats', 'blocks'));
        }
        public function update(\Illuminate\Http\Request $request, \VanguardLTE\Repositories\Session\SessionRepository $sessionRepository, \VanguardLTE\Shop $shop)
        {
            if( \Auth::user()->hasRole([
                'master', 
                'agent', 
                'distributor', 
                'manager', 
                'cashier'
            ]) ) 
            {
                $ids = \VanguardLTE\ShopUser::where('user_id', \Auth::id())->pluck('shop_id')->toArray();
                if( !(count($ids) && in_array($shop->id, $ids)) ) 
                {
                    return redirect()->route(config('app.admurl').'.shop.list')->withErrors([trans('app.wrong_shop')]);
                }
            }
            $data = $request->only([
                'name', 
                'frontend', 
                'currency', 
                'percent', 
                'deal_percent', 
                'table_deal_percent', 
                'ggr_percent', 
                'orderby', 
                'is_blocked'
            ]);
            $validatedData = $request->validate([
                'name' => 'required|unique:shops,name,' . $shop->id, 
                'currency' => 'present|in:' . implode(',', \VanguardLTE\Shop::$values['currency']), 
                //'percent' => 'required|in:' . implode(',', \VanguardLTE\Shop::$values['percent']), 
                'orderby' => 'required|in:' . implode(',', \VanguardLTE\Shop::$values['orderby'])
            ]);
            //$parent = auth()->user();
            $managers = $shop->getUsersByRole('manager');
            if (count($managers) == 0)
            {
                return redirect()->route(config('app.admurl').'.shop.list')->withErrors(['매장관리자를 찾을수 없습니다.']);
            }
            $parent = $managers->first()->referral;
            if ($parent!=null && $parent->deal_percent < $data['deal_percent'])
            {
                return redirect()->route(config('app.admurl').'.shop.list')->withErrors(['딜비는 상위파트너보다 클수 없습니다']);
            }
            if ($parent!=null && $parent->table_deal_percent < $data['table_deal_percent'])
            {
                return redirect()->route(config('app.admurl').'.shop.list')->withErrors(['라이브딜비는 상위파트너보다 클수 없습니다']);
            }
            if ($parent!=null && $parent->ggr_percent < $data['ggr_percent'])
            {
                return redirect()->route(config('app.admurl').'.shop.list')->withErrors(['죽장퍼센트는 상위파트너보다 클수 없습니다']);
            }
            $shop->update($data);
            //added by shev for random win
            $percent = $request['percent'];
            /*
            $bank = \VanguardLTE\GameBank::where('shop_id', $shop->id)->first();
            if($percent == 99) {
                $bank->update(['slots' => 500000]);
            }
            else if($percent > 50) {
                $bank->update(['slots' => 500000]);
            }
            else {
                $bank->update(['slots' => 500000]);
            }*/
            if( isset($request->categories) && count($request->categories) ) 
            {
                \VanguardLTE\ShopCategory::where('shop_id', $shop->id)->delete();
                foreach( $request->categories as $category ) 
                {
                    \VanguardLTE\ShopCategory::create([
                        'shop_id' => $shop->id, 
                        'category_id' => $category
                    ]);
                }
            }
            if( $request->is_blocked ) 
            {
                $users = \VanguardLTE\User::where('shop_id', $shop->id)->whereIn('role_id', [
                    1, 
                    2, 
                    3
                ])->get();
                if( $users ) 
                {
                    foreach( $users as $user ) 
                    {
                        $sessions = $sessionRepository->getUserSessions($user->id);
                        if( count($sessions) ) 
                        {
                            foreach( $sessions as $session ) 
                            {
                                $sessionRepository->invalidateSession($session->id);
                            }
                        }
                    }
                }
            }
            event(new \VanguardLTE\Events\Shop\ShopEdited($shop));
            return redirect()->route(config('app.admurl').'.shop.list')->withSuccess(trans('app.shop_updated'));
        }
        public function delete($shop)
        {
            $shop_ids = auth()->user()->availableShops();
            if (!in_array($shop, $shop_ids))
            {
                return redirect()->route(config('app.admurl').'.shop.list')->withErrors(['비정상적인 접근입니다.']);
            }
            $shopInfo = \VanguardLTE\Shop::find($shop);
            if( $shopInfo && $shopInfo->balance > 0 ) 
            {
                return redirect()->route(config('app.admurl').'.shop.list')->withErrors(['매장보유금이 0이 아닙니다.']);
            }
            $usersWithBalance = \VanguardLTE\User::where('shop_id', $shop)->where('role_id', 1)->where('balance', '>', 0)->count();
            if( $usersWithBalance ) 
            {
                return redirect()->route(config('app.admurl').'.shop.list')->withErrors([$usersWithBalance . '명의 회원보유금이 0이 아닙니다.']);
            }
           
            $shopInfo->update(['pending'=>2]);

            \VanguardLTE\Task::create([
                'user_id' => auth()->user()->id,
                'category' => 'shop', 
                'action' => 'delete', 
                'item_id' => $shop
            ]);
            $toDelete = \VanguardLTE\User::whereIn('role_id', [
                1, 
                2, 
                3
            ])->where('shop_id', $shop)->update(['status' => \VanguardLTE\Support\Enum\UserStatus::DELETED]);
            event(new \VanguardLTE\Events\Shop\ShopDeleted($shopInfo));
            return redirect()->route(config('app.admurl').'.shop.list')->withSuccess(['매장을 삭제하였습니다.']);
        }
        public function hard_delete($shop)
        {
            \VanguardLTE\Shop::where('id', $shop)->delete();
            \VanguardLTE\ShopUser::where('shop_id', $shop)->delete();
            \VanguardLTE\Task::create([
                'category' => 'shop', 
                'action' => 'delete', 
                'item_id' => $shop
            ]);
            $usersToDelete = \VanguardLTE\User::whereIn('role_id', [
                1, 
                2, 
                3
            ])->where('shop_id', $shop)->get();
            if( $usersToDelete ) 
            {
                foreach( $usersToDelete as $userDelete ) 
                {
                    $userDelete->delete();
                }
            }
            return redirect()->route(config('app.admurl').'.shop.list')->withSuccess(trans('app.shop_deleted'));
        }
        public function balance(\Illuminate\Http\Request $request)
        {
            $data = $request->all();
            if( !array_get($data, 'type') ) 
            {
                $data['type'] = 'add';
            }
            $shop = \VanguardLTE\Shop::find($request->shop_id);
            $user = \VanguardLTE\User::find(\Auth::id());
            if( $request->all && $request->all == '1' ) 
            {
                $request->summ = $shop->balance;
            }
            if( !$shop ) 
            {
                return redirect()->back()->withErrors([trans('app.wrong_shop')]);
            }
            if( !$user ) 
            {
                return redirect()->back()->withErrors([trans('app.wrong_user')]);
            }
            if( !\Auth::user()->hasRole([
                'admin', 
                'comaster', 
                'master', 
                'agent', 
                'distributor', 
                'manager'
            ]) ) 
            {
                return redirect()->back()->withErrors([trans('app.only_for_distributors')]);
            }

            if( \Auth::user()->hasRole([
                'master', 
                'agent', 
                'distributor', 
                'manager'
            ]) && $data['type'] == 'out') 
            {
                return redirect()->back()->withErrors(['허용되지 않은 조작입니다.']);
            }

            if (!in_array($shop->id, $user->availableShops()))
            {
                return redirect()->back()->withErrors(['하위 매장이 아닙니다.']);
            }

            if( !$request->summ || $request->summ==0 ) 
            {
                return redirect()->back()->withErrors([trans('app.wrong_sum')]);
            }
            $summ = abs(str_replace(',','',$request->summ));
            if( $data['type'] == 'add' && (!$user->hasRole('admin') && $user->balance < $summ  ) )
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
            $open_shift = \VanguardLTE\OpenShift::where([
                'shop_id' => $shop->id, 
                'type' => 'shop',
                'end_date' => null
            ])->first();
            if( !$open_shift ) 
            {
                return redirect()->back()->withErrors([trans('app.shift_not_opened')]);
            }
            $sum = ($request->type == 'out' ? -1 * $summ  : $summ );

            if( $request->type == 'out' ) 
            {
                $open_shift->increment('balance_out', abs($sum));
            }
            else
            {
                $open_shift->increment('balance_in', abs($sum));
            }
            //update shift for partners

            $user_shift = \VanguardLTE\OpenShift::where([
                'user_id' => $user->id, 
                'type' => 'partner',
                'end_date' => null
            ])->first();
            if( $user_shift ) 
            {
                if( $request->type == 'out' ) 
                {
                    $user_shift->increment('money_out', abs($sum));
                }
                else
                {
                    $user_shift->increment('money_in', abs($sum));
                }
            }
            if (!$user->hasRole('admin')) {
                $user->update([
                    'balance' => $user->balance - $sum, 
                    'count_balance' => $user->count_balance - $sum
                ]);
                $user = $user->fresh();
            }

            $old = $shop->balance;
            $shop->update(['balance' => $shop->balance + $sum]);
            $shop = $shop->fresh();
            \VanguardLTE\ShopStat::create([
                'user_id' => \Auth::id(), 
                'shop_id' => $shop->id, 
                'type' => $request->type, 
                'sum' => abs($sum),
                'old' => $old,
                'new' => $shop->balance,
                'balance' => $user->balance,
                'reason' => $request->reason,
            ]);

            //create another transaction for mananger account
            $manager = $shop->getUsersByRole('manager')->first();
            \VanguardLTE\Transaction::create([
                'user_id' => $manager->id, 
                'payeer_id' => $user->id, 
                'type' => $request->type, 
                'summ' => abs($summ), 
                'old' => $old,
                'new' => $shop->balance,
                'balance' => $user->balance,
                'shop_id' => $shop->id,
                'reason' => $request->reason
            ]);
            if( $user->balance == 0 ) 
            {
                $user->update([
                    'wager' => 0, 
                    'bonus' => 0
                ]);
            }
            if( $user->count_balance < 0 ) 
            {
                $user->update(['count_balance' => 0]);
            }
            return redirect()->back()->withSuccess(trans('app.balance_updated'));
        }
        public function action(\VanguardLTE\Shop $shop, $action)
        {
            $open_shift = \VanguardLTE\OpenShift::where([
                'shop_id' => $shop->id, 
                'end_date' => null
            ])->first();
            if( !$open_shift ) 
            {
                return redirect()->back()->withErrors([trans('app.shift_not_opened')]);
            }
            if( $action && in_array($action, [
                'jpg_out', 
                'games_out', 
                'return_out'
            ]) ) 
            {
                switch( $action ) 
                {
                    case 'jpg_out':
                        $jackpots = \VanguardLTE\JPG::where('shop_id', $shop->id)->get();
                        foreach( $jackpots as $jackpot ) 
                        {
                            $sum = $jackpot->balance;
                            if( $sum <= 0 ) 
                            {
                                continue;
                            }
                            $jackpot->decrement('balance', abs($sum));
                            $shop->increment('balance', abs($sum));
                            $open_shift->increment('balance_in', abs($sum));
                            \VanguardLTE\BankStat::create([
                                'name' => $jackpot->name, 
                                'user_id' => \Auth::id(), 
                                'type' => 'out', 
                                'sum' => abs($sum), 
                                'old' => $sum, 
                                'new' => 0, 
                                'shop_id' => $shop->id
                            ]);
                        }
                        return redirect()->back()->withSuccess(trans('app.balance_updated'));
                        break;
                    case 'games_out':
                        $arr = ['gamebank'];
                        if( $action == 'jpg_out' ) 
                        {
                            $arr = [
                                'jp_1', 
                                'jp_2', 
                                'jp_3', 
                                'jp_4', 
                                'jp_5', 
                                'jp_6', 
                                'jp_7', 
                                'jp_8', 
                                'jp_9', 
                                'jp_10'
                            ];
                        }
                        $games = \VanguardLTE\Game::where('shop_id', $shop->id)->get();
                        foreach( $games as $game ) 
                        {
                            foreach( $arr as $element ) 
                            {
                                $sum = $game->$element;
                                if( $sum <= 0 ) 
                                {
                                    continue;
                                }
                                $name = $game->name;
                                if( $element != 'gamebank' ) 
                                {
                                    $name .= (' JP ' . str_replace('jp_', '', $element));
                                }
                                $shop->increment('balance', $sum);
                                $open_shift->increment('balance_in', abs($sum));
                                if( $action == 'jpg_out' ) 
                                {
                                    $game->update([$element => 0]);
                                }
                                else
                                {
                                    $game->update([$element => 0]);
                                }
                                \VanguardLTE\BankStat::create([
                                    'name' => $name, 
                                    'user_id' => \Auth::id(), 
                                    'type' => 'out', 
                                    'sum' => $sum, 
                                    'old' => $sum, 
                                    'new' => 0, 
                                    'shop_id' => $shop->id
                                ]);
                            }
                        }
                        return redirect()->back()->withSuccess(trans('app.balance_updated'));
                        break;
                    case 'return_out':
                        \VanguardLTE\User::where('shop_id', $shop->id)->update(['count_return' => 0]);
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

<?php 
namespace VanguardLTE
{
    class User extends \Illuminate\Foundation\Auth\User implements \Tymon\JWTAuth\Contracts\JWTSubject
    {
        use \Laracasts\Presenter\PresentableTrait, 
            \Illuminate\Notifications\Notifiable, 
            \jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
        protected $presenter = 'VanguardLTE\Presenters\UserPresenter';
        protected $table = 'users';
        protected $dates = [
            'last_login', 
            'birthday'
        ];
        protected $fillable = [
            'password', 
            'email', 
            'username', 
            'avatar', 
            'balance', 
            'last_login', 
            'confirmation_token', 
            'status', 
            'wager', 
            'rating', 
            'points', 
            'total_balance', 
            'bonus', 
            'count_bonus', 
            'total_in', 
            'total_out', 
            'language', 
            'remember_token', 
            'role_id', 
            'count_balance', 
            'count_return', 
            'parent_id', 
            'shop_id', 
            'session',
            'deal_balance', 
            'deal_percent',
            'mileage',
            'bank_name',
            'recommender',
            'account_no',
            'api_token'
        ];


        public static $values = [
            'banks' => [
                '', 
                '국민', 
                '기업', 
                '농협 / 단위농협', 
                '신한', 
                '우체국', 
                'SC(스탠다드차타드) / 제일', 
                '하나', 
                '씨티', 
                '우리', 
                '경남', 
                '광주', 
                '대구', 
                '도이치', 
                '부산', 
                '산업', 
                '수협', 
                '전북', 
                '제주', 
                '새마을금고', 
                '신용협동조합', 
                '홍콩상하이(HSBC)', 
                '상호저축은행중앙회', 
                '뱅크오브아메리카', 
                '케이뱅크', 
                '카카오', 
                '제이피모간체이스', 
                '비엔피파리바', 
                'NH투자증권', 
                '유안타증권', 
                'KB증권', 
                '미래에셋대우', 
                '삼성증권', 
                '한국투자증권', 
                '교보증권', 
                '하이투자증권', 
                '현대차증권', 
                'SK증권', 
                '한화투자증권', 
                '하나금융투자', 
                '신한금융투자', 
                '유진투자증권', 
                '메리츠종합금융증권', 
                '신영증권', 
                '이베스트투자증권', 
                '케이프증권', 
                '산림조합', 
                '부국증권', 
                '키움증권', 
                '대신증권', 
                'DB금융투자', 
                '중국공상', 
                '펀드온라인코리아', 
                '케이티비투자증권'
            ]
        ];



        protected $hidden = [
            'password', 
            'remember_token'
        ];
        public function generateCode($limit){
            $code = 0;
            for($i = 0; $i < $limit; $i++) { $code .= mt_rand(0, 9); }
            return $code;
        }
        public static function boot()
        {
            parent::boot();
            self::created(function($model)
            {
            });
            self::deleting(function($model)
            {
                $model->detachAllRoles();
                Transaction::where('user_id', $model->id)->delete();
                ShopUser::where('user_id', $model->id)->delete();
                StatGame::where('user_id', $model->id)->delete();
                GameLog::where('user_id', $model->id)->delete();
                UserActivity::where('user_id', $model->id)->delete();
                Session::where('user_id', $model->id)->delete();
                Info::where('user_id', $model->id)->delete();
                OpenShift::where('user_id', $model->id)->delete();
                GameActivity::where('user_id', $model->id)->delete();
            });
        }
        public function setPasswordAttribute($value)
        {
            $this->attributes['password'] = bcrypt($value);
        }
        public function setBirthdayAttribute($value)
        {
            $this->attributes['birthday'] = (trim($value) ?: null);
        }
        public function gravatar()
        {
            $hash = hash('md5', strtolower(trim($this->attributes['username'])));
            return sprintf('https://www.gravatar.com/avatar/%s?size=150', $hash);
        }
        public function isActive()
        {
            return $this->status == Support\Enum\UserStatus::ACTIVE;
        }
        public function availableUsers()
        {
            $users = User::where(['id' => $this->id])->get();
            if( $this->hasRole(['admin']) ) 
            {
                $users = User::get();
            }
            if( $this->hasRole(['master']) ) 
            {
                $agents = User::where([
                    'role_id' => 5, 
                    'parent_id' => $this->id
                ])->get();
                $distributors = User::where('role_id', 4)->whereIn('parent_id' , $agents->pluck('id')->toArray())->get();
                $other = User::where('role_id', '<=', 3)->whereIn('shop_id', $this->availableShops())->get();
                $users = $users->merge($agents);
                $users = $users->merge($distributors);
                $users = $users->merge($other);
            }
            if( $this->hasRole(['agent']) ) 
            {
                $distributors = User::where([
                    'role_id' => 4, 
                    'parent_id' => $this->id
                ])->get();
                $other = User::where('role_id', '<=', 3)->whereIn('shop_id', $this->availableShops())->get();
                $users = $users->merge($distributors);
                $users = $users->merge($other);
            }
            if( $this->hasRole(['distributor']) ) 
            {
                $other = User::where('role_id', '<=', 3)->whereIn('shop_id', $this->shops_array(true))->get();
                $users = $users->merge($other);
            }
            if( $this->hasRole(['manager']) ) 
            {
                $other = User::where('role_id', '<=', 2)->where('shop_id', $this->shop_id)->get();
                $users = $users->merge($other);
            }
            if( $this->hasRole(['cashier']) ) 
            {
                $other = User::where('role_id', 1)->where('shop_id', $this->shop_id)->get();
                $users = $users->merge($other);
            }
            $users = $users->pluck('id');
            if( !count($users) ) 
            {
                $users = [0];
            }
            else
            {
                $users = $users->toArray();
            }
            return $users;
        }
        public function hierarchyUsers()
        {
            $level = $this->level();
            $users = User::where('id', $this->id)->get();
            for( $i = $level; $i >= 1; $i-- ) 
            {
                foreach( $users as $user ) 
                {
                    if( $user->level() == $i ) 
                    {
                        /*if( auth()->user()->shop_id > 0 ) 
                        {
                            $users = $users->merge(User::where('parent_id', $user->id)->whereHas('rel_shops', function($query)
                            {
                                $query->where('shop_id', $this->shop_id);
                            })->get());
                        }
                        else
                        {*/
                            $users = $users->merge(User::where('parent_id', $user->id)->get());
                        //}
                    }
                }
            }
            return $users->pluck('id')->toArray();
        }

        public function hierarchyPartners()
        {
            $level = $this->level();
            $users = User::where('id', $this->id)->get();
            for( $i = $level; $i >= 4; $i-- ) 
            {
                foreach( $users as $user ) 
                {
                    if( $user->level() == $i ) 
                    {
                        /*if( auth()->user()->shop_id > 0 ) 
                        {
                            $users = $users->merge(User::where('parent_id', $user->id)->whereHas('rel_shops', function($query)
                            {
                                $query->where('shop_id', $this->shop_id);
                            })->get());
                        }
                        else
                        { */
                            $users = $users->merge(User::where('parent_id', $user->id)->get());
                        //}
                    }
                }
            }
            return $users->pluck('id')->toArray();
        }

        public function childPartners()
        {
            $level = $this->level();
            $users = User::where('parent_id', $this->id)->get();
            return $users->pluck('id')->toArray();
        }

        public function hierarchyUsersOnly()
        {
            $level = $this->level();
            $users = User::where('id', $this->id)->get();
            for( $i = $level; $i >= 1; $i-- ) 
            {
                foreach( $users as $user ) 
                {
                    if( $user->level() == $i) 
                    {
                        // if( auth()->user()->shop_id > 0 ) 
                        // {
                        //     $users = $users->merge(User::where('parent_id', $user->id)->whereHas('rel_shops', function($query)
                        //     {
                        //         $query->where('shop_id', $this->shop_id);
                        //     })->get());
                        // }
                        // else
                        // {
                            $users = $users->merge(User::where('parent_id', $user->id)->get());
                        // }
                    }
                }
            }

            for($i = count($users) - 1; $i >= 0; $i--) {
                $user = $users[$i];
                if($user->role_id != 1) {
                    $users->forget($i);
                }
            }
            return $users->pluck('id')->toArray();
        }
        public function hierarchyUserNamesOnly()
        {
            $level = $this->level();
            $users = User::where('id', $this->id)->get();
            for( $i = $level; $i >= 1; $i-- ) 
            {
                foreach( $users as $user ) 
                {
                    if( $user->level() == $i) 
                    {
                        // if( auth()->user()->shop_id > 0 ) 
                        // {
                        //     $users = $users->merge(User::where('parent_id', $user->id)->whereHas('rel_shops', function($query)
                        //     {
                        //         $query->where('shop_id', $this->shop_id);
                        //     })->get());
                        // }
                        // else
                        // {
                            $users = $users->merge(User::where('parent_id', $user->id)->get());
                        // }
                    }
                }
            }

            for($i = count($users) - 1; $i >= 0; $i--) {
                $user = $users[$i];
                if($user->role_id != 1) {
                    $users->forget($i);
                }
            }
            return $users->pluck('username','id')->toArray();
        }

        public function isAvailable($user)
        {
            if( !$user ) 
            {
                return false;
            }
            if( in_array($user->id, $this->availableUsers()) ) 
            {
                return true;
            }
            return false;
        }
        public function emptyShops()
        {
            $count = 0;
            if( $shops = $this->rel_shops ) 
            {
                foreach( $shops as $shop ) 
                {
                    if( $shop->shop && count($shop->shop->getUsersByRole('user')) == 0 ) 
                    {
                        $count++;
                    }
                }
            }
            return $count;
        }
        public function availableUsersByRole($roleName)
        {
            $users = $this->availableUsers();
            if( !count($users) ) 
            {
                return [];
            }
            $role = \jeremykenedy\LaravelRoles\Models\Role::where('slug', $roleName)->first();
            return User::where('role_id', $role->id)->whereIn('id', $users)->pluck('id')->toArray();
        }
        public function availableShops()
        {
            $shops = [$this->shop_id];
            if( $this->hasRole([
                'admin', 
                'master',
                'agent', 
                'distributor'
            ]) ) 
            {
                //if( !$this->shop_id ) 
                //{
                    $shops = array_merge([0], $this->shops_array(true));
                /*}
                else
                {
                    $shops = [$this->shop_id];
                }*/
            }
            return $shops;
        }
        public function getInnerUsers()
        {
            $role = \jeremykenedy\LaravelRoles\Models\Role::where('id', $this->role_id - 1)->first();
            $ids = $this->availableUsersByRole($role->slug);
            if( count($ids) ) 
            {
                return User::whereIn('id', $ids)->get();
            }
            return false;
        }
        public function getRowspan()
        {
            $rowspan = 0;
            if( $this->hasRole(['master','agent']) ) 
            {
                $rowspan = 0;
                $distributors = User::where('parent_id', $this->id)->get();
                if( $distributors ) 
                {
                    foreach( $distributors as $distributor ) 
                    {
                        $shops = $distributor->shops_array();
                        $rowspan += (count($shops) ?: 1);
                    }
                }
            }
            if( $this->hasRole('distributor') ) 
            {
                $rowspan = 0;
                if( $shops = $this->rel_shops ) 
                {
                    foreach( $shops as $shop ) 
                    {
                        if( $shop = $shop->shop ) 
                        {
                            $managers = $shop->getUsersByRole('manager');
                            $rowspan += (count($managers) ?: 1);
                        }
                    }
                }
            }
            return ($rowspan > 0 ? $rowspan : 1);
        }
        public function isBanned()
        {
            return $this->status == Support\Enum\UserStatus::BANNED;
        }
        public function role()
        {
            return $this->belongsTo('jeremykenedy\LaravelRoles\Models\Role', 'role_id');
        }
        public function activities()
        {
            return $this->hasMany('VanguardLTE\Services\Logging\UserActivity\Activity', 'user_id');
        }
        public function referral()
        {
            return $this->belongsTo('VanguardLTE\User', 'parent_id');
        }
        public function rel_shops()
        {
            return $this->hasMany('VanguardLTE\ShopUser', 'user_id');
        }
        public function shops($onlyId = false)
        {
            if( $this->hasRole('admin') ) 
            {
                $partners = $this->childPartners();
                $shops = ShopUser::whereIn('user_id', $partners)->pluck('shop_id');
            }
            else{
                $shops = ShopUser::where('user_id', $this->id)->pluck('shop_id');
            }
            if( count($shops) ) 
            {
                if( $onlyId ) 
                {
                    return Shop::whereIn('id', $shops)->pluck('id');
                }
                else
                {
                    return Shop::whereIn('id', $shops)->pluck('name', 'id');
                }
            }
            else
            {
                return [];
            }
        }
        public function shops_array($onlyId = false)
        {
            $data = $this->shops($onlyId);
            if( !is_array($data) ) 
            {
                return $data->toArray();
            }
            return $data;
        }
        public function available_roles($withMe = false)
        {
            $roles = [
                '1' => [], 
                '2' => [1], 
                '3' => [1], 
                '4' => [3], 
                '5' => [4], 
                '6' => [5],
                '7' => [6]
            ];
            if( $withMe ) 
            {
                $roles = [
                    '1' => [], 
                    '2' => [
                        1, 
                        2
                    ], 
                    '3' => [
                        1, 
                        2, 
                        3
                    ], 
                    '4' => [
                        1, 
                        2, 
                        3, 
                        4
                    ], 
                    '5' => [
                        1, 
                        2, 
                        3, 
                        4, 
                        5
                    ], 
                    '6' => [
                        1, 
                        2, 
                        3, 
                        4, 
                        5, 
                        6
                    ],
                    '7' => [
                        1, 
                        2, 
                        3, 
                        4, 
                        5, 
                        6,
                        7
                    ]
                ];
            }
            if( count($roles[$this->level()]) ) 
            {
                return \jeremykenedy\LaravelRoles\Models\Role::whereIn('id', $roles[$this->level()])->pluck('name', 'id');
            }
            return [];
        }
        public function shop()
        {
            return $this->belongsTo('VanguardLTE\Shop', 'shop_id');
        }
        public function getJWTIdentifier()
        {
            return $this->id;
        }
        public function getJWTCustomClaims()
        {
            $token = app('VanguardLTE\Services\Auth\Api\TokenFactory')->forUser($this);
            return ['jti' => $token->id];
        }
        public function addBalance($type, $summ, $payeer = false, $return = 0, $request_id = null)
        {
            if( !in_array($type, [
                'add', 
                'out'
            ]) ) 
            {
                $type = 'add';
            }
            $shop = $this->shop;
            if( !$payeer ) 
            {
                $payeer = User::where('id', auth()->user()->id)->first();
            }
            if( $payeer->hasRole('admin') && !$this->hasRole('master') ) 
            {
                return response()->json([
                    'status' => 'error', 
                    'message' => trans('app.wrong_user')
                ]);
            }
            /*if( $payeer->hasRole('master') && !$this->hasRole('agent') ) 
            {
                return response()->json([
                    'status' => 'error', 
                    'message' => trans('app.wrong_user')
                ]);
            } */
            if( $payeer->hasRole('agent') && (!$this->hasRole('distributor') && !$this->hasRole('user')) ) 
            {
                return response()->json([
                    'status' => 'error', 
                    'message' => trans('app.wrong_user')
                ]);
            }
            if( $payeer->hasRole('distributor') && !$this->hasRole('manager') ) 
            {
                return response()->json([
                    'status' => 'error', 
                    'message' => trans('app.wrong_user')
                ]);
            }
            if( ($payeer->hasRole('cashier') || $payeer->hasRole('manager')) && !$this->hasRole('user')) 
            {
                return response()->json([
                    'status' => 'error', 
                    'message' => trans('app.wrong_user')
                ]);
            }
            if( !$summ ) 
            {
                return response()->json([
                    'status' => 'error', 
                    'message' => trans('app.wrong_sum')
                ]);
            }
            if( ($payeer->hasRole('cashier') || $payeer->hasRole('manager'))&& $this->hasRole('user') ) 
            {
                if( !$shop ) 
                {
                    return response()->json([
                        'status' => 'error', 
                        'message' => trans('app.wrong_shop')
                    ]);
                }
                if( $type == 'add' && $shop->balance < $summ ) 
                {
                    return response()->json([
                        'status' => 'error', 
                        'message' => trans('app.not_enough_money_in_the_shop', [
                            'name' => $shop->name, 
                            'balance' => $shop->balance
                        ])
                    ]);
                }
            }
            if(/* ($payeer->hasRole('agent') && ($this->hasRole('distributor') || $this->hasRole('user'))|| $payeer->hasRole('distributor') && $this->hasRole('manager')) && */$payeer->hasRole(['master','agent','distributor']) && $type == 'add' && $payeer->balance < $summ ) 
            {
                return response()->json([
                    'status' => 'error', 
                    'message' => trans('app.not_enough_money_in_the_user_balance', [
                        'name' => $payeer->name, 
                        'balance' => $payeer->balance
                    ])
                ]);
            }
            if( $type == 'out' && $this->balance < $summ ) 
            {
                return response()->json([
                    'status' => 'error', 
                    'message' => trans('app.not_enough_money_in_the_user_balance', [
                        'name' => $this->username, 
                        'balance' => $this->balance
                    ])
                ]);
            }
            if( ($payeer->hasRole('cashier') || $payeer->hasRole('manager'))&& $this->hasRole('user')) 
            {
                $open_shift = OpenShift::where([
                    'shop_id' => $payeer->shop_id, 
                    'type' => 'shop',
                    'end_date' => null
                ])->first();
                if( !$open_shift ) 
                {
                    return response()->json([
                        'status' => 'error', 
                        'message' => trans('app.shift_not_opened')
                    ]);
                }
            }
            if ($this->hasRole(['master','agent','distributor'])) 
            {
                $open_shift = OpenShift::where([
                    'user_id' => $this->id, 
                    'type' => 'partner',
                    'end_date' => null
                ])->first();
            }
            if ($payeer->hasRole(['admin','master','agent'])) 
            {
                $payeer_open_shift = OpenShift::where([
                    'user_id' => $payeer->id, 
                    'type' => 'partner',
                    'end_date' => null
                ])->first();
            }
            
            
            $happyhour = HappyHour::where([
                'shop_id' => $payeer->shop_id, 
                'time' => date('G')
            ])->first();
            $summ = ($type == 'out' ? -1 * $summ : $summ);
            $balance = $summ;
            if( ($payeer->hasRole('cashier') || $payeer->hasRole('manager')) && $this->hasRole('user') && $type == 'add' && $happyhour ) 
            {
                $transactionSum = $summ * intval(str_replace('x', '', $happyhour->multiplier));
                $bonus = $transactionSum - $summ;
                $wager = $bonus * intval(str_replace('x', '', $happyhour->wager));
                Transaction::create([
                    'user_id' => $this->id, 
                    'system' => 'HH ' . $happyhour->multiplier, 
                    'type' => $type, 
                    'summ' => $transactionSum, 
                    'request_id' => $request_id,
                    'shop_id' => ($this->hasRole('user') ? $this->shop_id : 0)
                ]);
                $this->increment('wager', $wager);
                $this->increment('bonus', $bonus);
                $this->increment('count_bonus', $bonus);
                $balance = $transactionSum;
            }
            else
            {
                Transaction::create([
                    'user_id' => $this->id, 
                    'payeer_id' => $payeer->id, 
                    'type' => $type, 
                    'summ' => abs($summ), 
                    'request_id' => $request_id,
                    'shop_id' => ($this->hasRole('user') ? $this->shop_id : 0)
                ]);
            }
            if( !$this->hasRole('admin') ) 
            {
                $this->increment('balance', $balance);
                $this->increment('count_balance', $summ);
            }
            if( $type == 'out' ) 
            {
                $this->increment('total_out', abs($summ));
            }
            else
            {
                $this->increment('total_in', abs($summ));
            }
            if( $this->hasRole('user') ) 
            {
                if( $type == 'out' ) 
                {
                    $this->update(['count_return' => 0]);
                }
                else if( $return > 0 ) 
                {
                    $this->update(['count_return' => $this->count_return + (($summ * $return) / 100)]);
                }
                else
                {
                    $this->update(['count_return' => $this->count_return + Lib\Functions::count_return($summ, $this->shop_id)]);
                }
            }
            if(/* $payeer->hasRole('agent') && ($this->hasRole('distributor') || $this->hasRole('user'))|| $payeer->hasRole('distributor') && $this->hasRole('manager') */
                $payeer->hasRole(['master','agent','distributor'])) 
            {
                $payeer->update(['balance' => $payeer->balance - $summ]);
            }
            if( ($payeer->hasRole('cashier') || $payeer->hasRole('manager')) && $this->hasRole('user') ) 
            {
                $shop->update(['balance' => $shop->balance - $summ]);
                /*if( $type == 'out' ) 
                {
                    $open_shift->increment('balance_in', abs($summ));
                }
                else
                {
                    $open_shift->increment('balance_out', abs($summ));
                } */
                if( $type == 'out' ) 
                {
                    $open_shift->increment('money_out', abs($summ));
                }
                else
                {
                    $open_shift->increment('money_in', abs($summ));
                }
            }
            if ($payeer->hasRole(['admin','master','agent'])) 
            {
                if( $type == 'out' ) 
                {
                    if ($open_shift) $open_shift->increment('balance_out', abs($summ));
                    if ($payeer_open_shift) $payeer_open_shift->increment('money_out', abs($summ));
                }
                else
                {
                    if ($open_shift) $open_shift->increment('balance_in', abs($summ));
                    if ($payeer_open_shift) $payeer_open_shift->increment('money_in', abs($summ));
                }
            }
            if( $this->balance == 0 ) 
            {
                $this->update([
                    'wager' => 0, 
                    'bonus' => 0
                ]);
            }
            if( $this->wager <= 0 ) 
            {
                $this->update([
                    'wager' => 0, 
                    'bonus' => 0, 
                    'count_bonus' => 0
                ]);
            }
            if( $this->count_return <= 0 ) 
            {
                $this->update(['count_return' => 0]);
            }
            if( $this->count_balance < 0 ) 
            {
                $this->update(['count_balance' => 0]);
            }
            return response()->json([
                'status' => 'success', 
                'message' => trans('app.balance_updated')
            ]);
        }


        public function processBetDealerMoney($betMoney, $game) 
        {
            if(!$this->hasRole('user')) {
                return;
            }

            $shop = $this->shop;
            $deal_shop = 0;
            $deal_distributor = 0;
            $deal_agent = 0;
            if($shop->deal_percent > 0) {
                $deal_shop = $betMoney * $shop->deal_percent  / 100;
                $balance_before = $shop->deal_balance;
                $shop->update(['deal_balance' => $shop->deal_balance + $deal_shop]);
                $open_shift = OpenShift::where([
                    'shop_id' => $shop->id, 
                    'type' => 'shop',
                    'end_date' => null
                ])->first();
                if ($open_shift)
                {
                    $open_shift->increment('deal_profit', $deal_shop);
                }

                $balance_after = $shop->deal_balance;

                DealLog::create([
                    'user_id' => $this->id, 
                    'partner_id' => 0,
                    'balance_before' => $balance_before, 
                    'balance_after' => $balance_after, 
                    'bet' => abs($betMoney), 
                    'deal_profit' => $deal_shop,
                    'game' => $game,
                    'shop_id' => $shop->id,
                    'type' => 'shop',
                    'deal_percent' => $shop->deal_percent,
                    'mileage' => 0
                ]);
            }

            $manager = $this->referral;
            if($manager != null) {
                $distributor = $manager->referral;
                if($distributor != null && $distributor->hasRole('distributor') && $distributor->deal_percent > 0){
                    $deal_distributor = $this->addDealerMoney($betMoney, $distributor, $deal_shop, $game);
                }

                if($distributor != null && $distributor->referral != null){
                    $agent = $distributor->referral;
                    if($agent !=  null && $agent->deal_percent > 0) {
                        $agent_distributor = $this->addDealerMoney($betMoney, $agent, $deal_distributor, $game);
                        $open_shift = OpenShift::where([
                            'user_id' => $agent->parent_id,  //will be admin
                            'type' => 'partner',
                            'end_date' => null
                        ])->first();
            
                        if ($open_shift)
                        {
                            $open_shift->increment('mileage', $agent_distributor);
                        }
                    }
                }
            }   
        }

        public function addDealerMoney($betMoney, $parentUser, $childDealMoney, $game)
        {
            $total_deal_money = $betMoney * $parentUser->deal_percent  / 100;
            $deal_money = $total_deal_money  - $childDealMoney;
            if($deal_money < 0){
                return $total_deal_money;
            }
            $balance_before = $parentUser->deal_balance;
            $parentUser->update(['deal_balance' => $parentUser->deal_balance + $total_deal_money, 'mileage' => $parentUser->mileage + $childDealMoney]);
            $balance_after = $parentUser->deal_balance;

            $open_shift = OpenShift::where([
                'user_id' => $parentUser->id, 
                'type' => 'partner',
                'end_date' => null
            ])->first();

            if ($open_shift)
            {
                $open_shift->increment('deal_profit', $total_deal_money);
                $open_shift->increment('mileage', $childDealMoney);
            }

            DealLog::create([
                'user_id' => $this->id, 
                'partner_id' => $parentUser->id,
                'balance_before' => $balance_before, 
                'balance_after' => $balance_after, 
                'bet' => abs($betMoney), 
                'deal_profit' => $total_deal_money,
                'game' => $game,
                'shop_id' => $this->shop->id,
                'type' => 'partner',
                'deal_percent' => $parentUser->deal_percent,
                'mileage' => $childDealMoney
            ]);
            return $total_deal_money;
        }


    }

}

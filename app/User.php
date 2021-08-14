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
            'first_name',
            'phone',
            'password', 
            'email', 
            'username', 
            'avatar', 
            'address',
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
            'count_deal_balance', 
            'deal_balance', 
            'deal_percent',
            'table_deal_percent',
            'money_percent',
            'mileage',
            'count_mileage',
            'bank_name',
            'recommender',
            'account_no',
            'api_token',
            'ggr_percent',
            'ggr_balance',
            'ggr_mileage',
            'reset_days',
            'last_reset_at',
            'playing_game',
            'played_at'
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
            ],
            'reset_days' => [
                '',
                '1일', 
                '2일',  
                '3일',  
                '4일',  
                '5일',  
                '6일',  
                '7일',  
                '8일',  
                '9일',  
                '10일',  
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
                $comasters = User::where([
                    'role_id' => 7, 
                ])->get();
                $masters = User::where('role_id' , 6)->whereIn('parent_id',$comasters->pluck('id')->toArray())->get(); 
                $agents = User::where('role_id', 5)->whereIn('parent_id' , $masters->pluck('id')->toArray())->get();
                $distributors = User::where('role_id', 4)->whereIn('parent_id' , $agents->pluck('id')->toArray())->get();
                $other = User::where('role_id', '<=', 3)->whereIn('shop_id', $this->availableShops())->get();
                $users = $users->merge($comasters);
                $users = $users->merge($masters);
                $users = $users->merge($agents);
                $users = $users->merge($distributors);
                $users = $users->merge($other);
            }
            if( $this->hasRole(['comaster']) ) 
            {
                $masters = User::where([
                    'role_id' => 6, 
                    'parent_id' => $this->id
                ])->get(); 
                $agents = User::where('role_id', 5)->whereIn('parent_id' , $masters->pluck('id')->toArray())->get();
                $distributors = User::where('role_id', 4)->whereIn('parent_id' , $agents->pluck('id')->toArray())->get();
                $other = User::where('role_id', '<=', 3)->whereIn('shop_id', $this->availableShops())->get();
                $users = $users->merge($masters);
                $users = $users->merge($agents);
                $users = $users->merge($distributors);
                $users = $users->merge($other);
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
            return $this->availableUsers();
        }

        public function hierarchyPartners()
        {
            $users = $this->availableUsers();
            if( !count($users) ) 
            {
                return [];
            }
            $level = $this->level();
            if ($level < 3)
            {
                return [];
            }
            return User::whereIn('role_id', range(3,$level-1))->whereIn('id', $users)->pluck('id')->toArray();
        }

        public function childPartners()
        {
            $level = $this->level();
            $users = User::where(['parent_id'=> $this->id,'role_id' => $this->role_id-1])->get();
            return $users->pluck('id')->toArray();
        }

        public function hierarchyUsersOnly()
        {
            return $this->availableUsersByRole('user');
        }
        public function hierarchyUserNamesOnly()
        {
            $users = $this->availableUsers();
            if( !count($users) ) 
            {
                return [];
            }
            return User::where('role_id', 1)->whereIn('id', $users)->pluck('username','id')->toArray();
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
                'comaster',
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
            if( $this->hasRole(['comaster','master','agent']) )  //don't use this function
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
                $shops = Shop::all()->pluck('id');
            }
            else if( $this->hasRole('comaster') ) 
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
                '7' => [6],
                '8' => [7]
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
                    ],
                    '8' => [
                        1, 
                        2, 
                        3, 
                        4, 
                        5, 
                        6,
                        7,
                        8
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
            /*if( $payeer->hasRole('admin') && !$this->hasRole('master') ) 
            {
                return response()->json([
                    'status' => 'error', 
                    'message' => trans('app.wrong_user')
                ]);
            }
            if( $payeer->hasRole('master') && !$this->hasRole('agent') ) 
            {
                return response()->json([
                    'status' => 'error', 
                    'message' => trans('app.wrong_user')
                ]);
            } */
            if( $payeer->hasRole('agent') && (!$this->hasRole('distributor') && !$this->hasRole('user')) ) 
            {
                return json_encode([
                    'status' => 'error', 
                    'message' => trans('app.wrong_user')
                ]);
            }
            if( $payeer->hasRole('distributor') && !$this->hasRole('manager') ) 
            {
                return json_encode([
                    'status' => 'error', 
                    'message' => trans('app.wrong_user')
                ]);
            }
            if( ($payeer->hasRole('cashier') || $payeer->hasRole('manager')) && !$this->hasRole('user')) 
            {
                return json_encode([
                    'status' => 'error', 
                    'message' => trans('app.wrong_user')
                ]);
            }
            if( !$summ ) 
            {
                return json_encode([
                    'status' => 'error', 
                    'message' => trans('app.wrong_sum')
                ]);
            }
            $summ = abs($summ);
            if( ($payeer->hasRole('cashier') || $payeer->hasRole('manager'))&& $this->hasRole('user') ) 
            {
                if( !$shop ) 
                {
                    return json_encode([
                        'status' => 'error', 
                        'message' => trans('app.wrong_shop')
                    ]);
                }
                if( $type == 'add' && $shop->balance < $summ ) 
                {
                    return json_encode([
                        'status' => 'error', 
                        'message' => trans('app.not_enough_money_in_the_shop', [
                            'name' => $shop->name, 
                            'balance' => $shop->balance
                        ])
                    ]);
                }
            }
            if(/* ($payeer->hasRole('agent') && ($this->hasRole('distributor') || $this->hasRole('user'))|| $payeer->hasRole('distributor') && $this->hasRole('manager')) && */$payeer->hasRole(['comaster','master','agent','distributor']) && $type == 'add' && $payeer->balance < $summ ) 
            {
                return json_encode([
                    'status' => 'error', 
                    'message' => trans('app.not_enough_money_in_the_user_balance', [
                        'name' => $payeer->name, 
                        'balance' => $payeer->balance
                    ])
                ]);
            }
            if( $type == 'out' && $this->balance < $summ ) 
            {
                return json_encode([
                    'status' => 'error', 
                    'message' => trans('app.not_enough_money_in_the_user_balance', [
                        'name' => $this->username, 
                        'balance' => $this->balance
                    ])
                ]);
            }
            $open_shift = null;
            if( ($payeer->hasRole('cashier') || $payeer->hasRole('manager'))&& $this->hasRole('user')) 
            {
                $open_shift = OpenShift::where([
                    'shop_id' => $payeer->shop_id, 
                    'type' => 'shop',
                    'end_date' => null
                ])->first();
                if( !$open_shift ) 
                {
                    return json_encode([
                        'status' => 'error', 
                        'message' => trans('app.shift_not_opened')
                    ]);
                }
            }
            if ($this->hasRole(['comaster','master','agent','distributor'])) 
            {
                $open_shift = OpenShift::where([
                    'user_id' => $this->id, 
                    'type' => 'partner',
                    'end_date' => null
                ])->first();
            }
            if ($payeer->hasRole(['admin','comaster','master','agent'])) 
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
            $summ = ($type == 'out' ? -1 * abs($summ) : abs($summ));
            $balance = $summ;
            $old = $this->balance;
            /*if( ($payeer->hasRole('cashier') || $payeer->hasRole('manager')) && $this->hasRole('user') && $type == 'add' && $happyhour ) 
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
            {*/
                
            //}
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
            $payer_balance = 0;
            if(/* $payeer->hasRole('agent') && ($this->hasRole('distributor') || $this->hasRole('user'))|| $payeer->hasRole('distributor') && $this->hasRole('manager') */
                $payeer->hasRole(['comaster','master','agent','distributor'])) 
            {
                $payeer->update(['balance' => $payeer->balance - $summ]);
                $payeer = $payeer->fresh();
                $payer_balance = $payeer->balance;
            }
            if( ($payeer->hasRole('cashier') || $payeer->hasRole('manager')) && $this->hasRole('user') ) 
            {
                $shop->update(['balance' => $shop->balance - $summ]);
                $shop = $shop->fresh();
                $payer_balance = $shop->balance;
                if( $type == 'out' ) 
                {
                    $open_shift->increment('money_out', abs($summ));
                }
                else
                {
                    $open_shift->increment('money_in', abs($summ));
                }
            }
            if ($payeer->hasRole(['admin','comaster','master','agent'])) 
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
            Transaction::create([
                'user_id' => $this->id, 
                'payeer_id' => $payeer->id, 
                'type' => $type, 
                'summ' => abs($summ), 
                'old' => $old,
                'new' => $this->balance,
                'balance' => $payer_balance,
                'request_id' => $request_id,
                'shop_id' => ($this->hasRole('user') ? $this->shop_id : 0)
            ]);
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
            return json_encode([
                'status' => 'success', 
                'message' => trans('app.balance_updated')
            ]);
        }

        public function processBetDealerMoney_Queue($stat_game) 
        {

            $betMoney = $stat_game->bet;
            $winMoney = $stat_game->win;
            $game = $stat_game->game;
            $date_time = $stat_game->date_time;
            if ($date_time == null)
            {
                $date_time = date('Y-m-d H:i:s');
            }
            $type=$stat_game->type;
            $category_id = $stat_game->category_id;
            $game_id = $stat_game->game_id;

            if(!$this->hasRole('user')) {
                return;
            }
            $shop = $this->shop;
            $deal_balance = 0;
            $deal_mileage = 0;
            $deal_percent = 0;
            $ggr_profit = 0;
            $ggr_mileage = 0;
            $ggr_percent = 0;

            $deal_data = [];
            $deal_percent = ($type==null || $type=='slot')?$shop->deal_percent:$shop->table_deal_percent;
            $ggr_percent = $shop->ggr_percent;
            $manager = $this->referral;
            if ($manager != null){
                // if($deal_percent > 0 || $ggr_percent > 0) {
                    $deal_balance = $betMoney * $deal_percent  / 100;
                    $ggr_profit = ($betMoney - $winMoney) * $ggr_percent / 100;
                    $deal_data[] = [
                        'user_id' => $this->id, 
                        'partner_id' => $manager->id, //manager's id
                        'balance_before' => 0, 
                        'balance_after' => 0, 
                        'bet' => abs($betMoney), 
                        'win' => abs($winMoney), 
                        'deal_profit' => $deal_balance,
                        'game' => $game,
                        'shop_id' => $shop->id,
                        'type' => 'shop',
                        'deal_percent' => $deal_percent,
                        'mileage' => $deal_mileage,
                        'ggr_profit' => $ggr_profit,
                        'ggr_mileage' => $ggr_mileage,
                        'ggr_percent' => $ggr_percent,
                        'date_time' => $date_time, 
                        'category_id' => $category_id,
                        'game_id' => $game_id,
                    ];
                // }
                $partner = $manager->referral;
                while ($partner != null && !$partner->isInoutPartner())
                {
                    $deal_mileage = $deal_balance;
                    $ggr_mileage = $ggr_profit;
                    $deal_percent = ($type==null || $type=='slot')?$partner->deal_percent:$partner->table_deal_percent;
                    $ggr_percent = $partner->ggr_percent;
                    // if($deal_percent > 0 || $ggr_percent > 0) {
                        $deal_balance = $betMoney * $deal_percent  / 100;
                        $ggr_profit = ($betMoney - $winMoney) * $ggr_percent / 100;
                        $deal_data[] = [
                            'user_id' => $this->id, 
                            'partner_id' => $partner->id,
                            'balance_before' => 0, 
                            'balance_after' => 0, 
                            'bet' => abs($betMoney),
                            'win' => abs($winMoney),
                            'deal_profit' => $deal_balance,
                            'game' => $game,
                            'shop_id' => $this->shop_id,
                            'type' => 'partner',
                            'deal_percent' => $deal_percent,
                            'mileage' => $deal_mileage,
                            'ggr_profit' => $ggr_profit,
                            'ggr_mileage' => $ggr_mileage,
                            'ggr_percent' => $ggr_percent,
                            'date_time' => $date_time, 
                            'category_id' => $category_id,
                            'game_id' => $game_id,
                        ];
                    // }
                    $partner = $partner->referral;
                }
            }

            if (count($deal_data) > 0)
            {
                \VanguardLTE\Jobs\UpdateDeal::dispatch($deal_data)->onQueue('deal');
                \VanguardLTE\Jobs\UpdateSummary::dispatch($deal_data)->onQueue('summary');
            }
        }
        public function processBetDealerMoney($betMoney, $game, $type='slot') 
        {
            if(!$this->hasRole('user')) {
                return;
            }

            //$shop = $this->shop;
            $shop = \VanguardLTE\Shop::lockForUpdate()->where('id',$this->shop_id)->first();
            $deal_shop = 0;
            $deal_distributor = 0;
            $deal_agent = 0;
            $deal_percent = ($type==null || $type=='slot')?$shop->deal_percent:$shop->table_deal_percent;
            if($deal_percent > 0) {
                $deal_shop = $betMoney * $deal_percent  / 100;
                $balance_before = $shop->deal_balance;
                $shop->update(['deal_balance' => $shop->deal_balance + $deal_shop]);
                /*$open_shift = OpenShift::where([
                    'shop_id' => $shop->id, 
                    'type' => 'shop',
                    'end_date' => null
                ])->first();
                if ($open_shift)
                {
                    $open_shift->increment('deal_profit', $deal_shop);
                } */

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
                    'deal_percent' => $deal_percent,
                    'mileage' => 0
                ]);
            }

            $manager = $this->referral;
            if($manager != null) {
                $distributor = \VanguardLTE\User::lockForUpdate()->where('id',$manager->parent_id)->first();
                $deal_percent = ($type==null || $type=='slot')?$distributor->deal_percent:$distributor->table_deal_percent;
                if($distributor != null && $distributor->hasRole('distributor') && $deal_percent > 0){
                    $deal_distributor = $this->addDealerMoney($betMoney, $distributor, $deal_shop, $game, $type);
                }

                if($distributor != null && $distributor->referral != null){
                    $agent = \VanguardLTE\User::lockForUpdate()->where('id',$distributor->parent_id)->first();
                    $deal_percent = ($type==null || $type=='slot')?$agent->deal_percent:$agent->table_deal_percent;
                    if($agent !=  null && $deal_percent > 0) {
                        $agent_distributor = $this->addDealerMoney($betMoney, $agent, $deal_distributor, $game, $type);
                        
                        if (settings('enable_master_deal'))
                        {
                            $master = \VanguardLTE\User::lockForUpdate()->where('id',$agent->parent_id)->first();
                            $deal_percent = ($type==null || $type=='slot')?$master->deal_percent:$master->table_deal_percent;
                            if($master !=  null && $deal_percent > 0) {
                                $this->addDealerMoney($betMoney, $master, $agent_distributor, $game, $type);
                            }
                        }
                    }
                }
            }   
        }

        public function addDealerMoney($betMoney, $parentUser, $childDealMoney, $game, $type)
        {
            $deal_percent = ($type==null || $type=='slot')?$parentUser->deal_percent:$parentUser->table_deal_percent;
            $total_deal_money = $betMoney * $deal_percent  / 100;
            $deal_money = $total_deal_money  - $childDealMoney;
            if($deal_money < 0){
                return $total_deal_money;
            }
            $balance_before = $parentUser->deal_balance;
            $parentUser->update(['deal_balance' => $parentUser->deal_balance + $total_deal_money, 'mileage' => $parentUser->mileage + $childDealMoney]);
            $balance_after = $parentUser->deal_balance;

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
                'deal_percent' => $deal_percent,
                'mileage' => $childDealMoney
            ]);
            return $total_deal_money;
        }

        public function isInoutPartner()
        {
            if ($this->hasRole(['admin', 'comaster']))
            {
                return true;
            }
            if ($this->hasRole('master') && !settings('enable_master_deal'))
            {
                return true;
            }
            return false;
        }


    }

}

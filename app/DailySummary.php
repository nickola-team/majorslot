<?php 
namespace VanguardLTE
{
    class DailySummary extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'daily_summary';
        protected $fillable = [
            'user_id', 
            'shop_id',
            'date',
            'totalin', //deposit via request
            'totalout', //withdraw via request
            'moneyin', // manual deposit
            'moneyout', //manual withdraw
            'dealout',
            'ggrout',
            'totalbet',
            'totalwin',
            'totaldealbet',
            'totaldealwin',
            'total_deal',
            'total_mileage',
            'total_ggr',
            'total_ggr_mileage',
            'balance',
            'deal_balance',
            'deal_mileage',
            'childsum',
            'user_sum',
            'partner_sum',
            'user_dealsum',
            'partner_dealsum',
            'type',
            'updated_at'
        ];
        public $timestamps = false;
        public static function boot()
        {
            parent::boot();
        }

        public function user()
        {
            return $this->belongsTo('VanguardLTE\User', 'user_id');
        }

        public function prevDay()
        {
            return $this->hasOne('VanguardLTE\DailySummary', 'user_id', 'user_id')->where('date', date('Y-m-d', strtotime("$this->date -1 days")));        
        }

        public function calcInOut()
        {
            $adj = [
                'totalin' => 0,
                'totalout' => 0,
                'moneyin' => 0,
                'moneyout' => 0,
            ];
            $childPartners = $this->user->hierarchyPartners();
            $childPartners[] = $this->user->id;
            $availableUsers = $this->user->availableUsers();
            $availableUsers[] = $this->user->id; //include self in/out

            $from = $this->date . ' 0:0:0';
            $to = $this->date . ' 23:59:59';
            $query = 'SELECT SUM(summ) as totalin FROM w_transactions WHERE user_id in ('.implode(',', $availableUsers).') AND created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="add" AND request_id IS NOT NULL';
            $user_in_out = \DB::select($query);
            $adj['totalin'] = $adj['totalin'] + $user_in_out[0]->totalin??0;

            $query = 'SELECT SUM(summ) as totalout FROM w_transactions WHERE user_id in ('.implode(',', $availableUsers).') AND created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="out" AND request_id IS NOT NULL';
            $user_in_out = \DB::select($query);
            $adj['totalout'] = $adj['totalout'] + $user_in_out[0]->totalout??0;

            if (!$this->user->hasRole('admin'))
            {

                $query = 'SELECT SUM(summ) as moneyin FROM w_transactions WHERE user_id in ('.implode(',', $availableUsers).') AND created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="add" AND request_id IS NULL AND payeer_id NOT IN ('.implode(',', $childPartners).')';
                $user_in_out = \DB::select($query);
                $adj['moneyin'] = $adj['moneyin'] + $user_in_out[0]->moneyin??0;

                $query = 'SELECT SUM(summ) as moneyout FROM w_transactions WHERE user_id in ('.implode(',', $availableUsers).') AND created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="out" AND request_id IS NULL AND payeer_id NOT IN ('.implode(',', $childPartners).')';
                $user_in_out = \DB::select($query);
                $adj['moneyout'] = $adj['moneyout'] + $user_in_out[0]->moneyout??0;
            }
            else
            {
                $query = 'SELECT SUM(summ) as moneyin FROM w_transactions WHERE user_id in ('.implode(',', $availableUsers).') AND created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="add" AND request_id IS NULL AND payeer_id = ' . $this->user->id;
                $user_in_out = \DB::select($query);
                $adj['moneyin'] = $adj['moneyin'] + $user_in_out[0]->moneyin??0;

                $query = 'SELECT SUM(summ) as moneyout FROM w_transactions WHERE user_id in ('.implode(',', $availableUsers).') AND created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="out" AND request_id IS NULL AND payeer_id = ' . $this->user->id;
                $user_in_out = \DB::select($query);
                $adj['moneyout'] = $adj['moneyout'] + $user_in_out[0]->moneyout??0;
            }

            return $adj;
        }

        public static function adjustment($user_id, $from, $to)
        {
            set_time_limit(0);
            $user = \VanguardLTE\User::where('id', $user_id)->first();
            if (!$user)
            {
                return null;
            }
            if($user->hasRole('manager')){
                $shop = \VanguardLTE\Shop::where('id', $user->shop_id)->first();
                $query = 'SELECT SUM(sum) as totalin FROM w_shops_stat WHERE shop_id='.$shop->id.' AND date_time <="'.$to .'" AND date_time>="'. $from. '" AND type="add" AND request_id IS NOT NULL';
                $in_out = \DB::select($query);
                $adj['totalin'] = $in_out[0]->totalin??0;

                $query = 'SELECT SUM(sum) as totalout FROM w_shops_stat WHERE shop_id='.$shop->id.' AND date_time <="'.$to .'" AND date_time>="'. $from. '" AND type="out" AND request_id IS NOT NULL';
                $in_out = \DB::select($query);
                $adj['totalout'] = $in_out[0]->totalout??0;

                $query = 'SELECT SUM(sum) as dealout FROM w_shops_stat WHERE shop_id='.$shop->id.' AND date_time <="'.$to .'" AND date_time>="'. $from. '" AND type="deal_out"';
                $in_out = \DB::select($query);
                $adj['dealout'] = $in_out[0]->dealout??0;

                $query = 'SELECT SUM(sum) as ggrout FROM w_shops_stat WHERE shop_id='.$shop->id.' AND date_time <="'.$to .'" AND date_time>="'. $from. '" AND type="ggr_out"';
                $in_out = \DB::select($query);
                $adj['ggrout'] = $in_out[0]->ggrout??0;

                $shop_users = $shop->getUsersByRole('user')->pluck('id')->toArray();
                
                $query = 'SELECT SUM(sum) as moneyin FROM w_shops_stat WHERE shop_id='.$shop->id.' AND date_time <="'.$to .'" AND date_time>="'. $from. '" AND type="add" AND request_id IS NULL';
                $in_out = \DB::select($query);
                $adj['moneyin'] = $in_out[0]->moneyin??0;

                $query = 'SELECT SUM(sum) as moneyout FROM w_shops_stat WHERE shop_id='.$shop->id.' AND date_time <="'.$to .'" AND date_time>="'. $from. '" AND type="out" AND request_id IS NULL';
                $in_out = \DB::select($query);
                $adj['moneyout'] = $in_out[0]->moneyout??0;
                $adj['childsum'] = 0;
                $adj['user_sum'] = 0;
                $adj['partner_sum'] = 0;
                $adj['user_dealsum'] = 0;
                $adj['partner_dealsum'] = 0;
                
                if (count($shop_users)>0)
                {

                    $query = 'SELECT SUM(summ) as totalin FROM w_transactions WHERE user_id in ('.implode(',', $shop_users).') AND created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="add" AND request_id IS NOT NULL';
                    $user_in_out = \DB::select($query);
                    $adj['totalin'] = $adj['totalin'] + $user_in_out[0]->totalin??0;

                    $query = 'SELECT SUM(summ) as totalout FROM w_transactions WHERE user_id in ('.implode(',', $shop_users).') AND created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="out" AND request_id IS NOT NULL';
                    $user_in_out = \DB::select($query);
                    $adj['totalout'] = $adj['totalout'] + $user_in_out[0]->totalout??0;

                    $query = 'SELECT SUM(summ) as moneyin FROM w_transactions WHERE user_id in ('.implode(',', $shop_users).') AND created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="add" AND request_id IS NULL AND payeer_id <> ' . $user->id;
                    $user_in_out = \DB::select($query);
                    $adj['moneyin'] = $adj['moneyin'] + $user_in_out[0]->moneyin??0;

                    $query = 'SELECT SUM(summ) as moneyout FROM w_transactions WHERE user_id in ('.implode(',', $shop_users).') AND created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="out" AND request_id IS NULL AND payeer_id <> ' . $user->id;
                    $user_in_out = \DB::select($query);
                    $adj['moneyout'] = $adj['moneyout'] + $user_in_out[0]->moneyout??0;


                    $query = 'SELECT SUM(summ) as dealout FROM w_transactions WHERE user_id in ('.implode(',', $shop_users).') AND created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="deal_out"';
                    $user_in_out = \DB::select($query);
                    $adj['dealout'] = $adj['dealout'] + $user_in_out[0]->dealout??0;

                    $query = 'SELECT SUM(summ) as ggrout FROM w_transactions WHERE user_id in ('.implode(',', $shop_users).') AND created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="ggr_out"';
                    $user_in_out = \DB::select($query);
                    $adj['ggrout'] = $adj['ggrout'] + $user_in_out[0]->ggrout??0;

                    $query = 'SELECT SUM(balance) as sumbalance, SUM(deal_balance) as dealsum FROM w_users WHERE id in ('.implode(',', $shop_users) .')';
                    $in_out = \DB::select($query);
                    $adj['childsum'] =  $in_out[0]->sumbalance??0;
                    $adj['user_sum'] = $in_out[0]->sumbalance??0;
                    $adj['partner_sum'] = 0;
                    $adj['user_dealsum'] = $in_out[0]->dealsum??0;
                    $adj['partner_dealsum'] = 0;
                }

                $query = 'SELECT SUM(bet) as totalbet, SUM(win) as totalwin FROM w_stat_game WHERE shop_id='.$shop->id.' AND date_time <="'.$to .'" AND date_time>="'. $from. '"';
                $game_bet = \DB::select($query);
                $adj['totalbet'] = $game_bet[0]->totalbet??0;
                $adj['totalwin'] = $game_bet[0]->totalwin??0;

                $query = 'SELECT SUM(bet) as totaldealbet, SUM(win) as totaldealwin, SUM(deal_profit) as total_deal, SUM(mileage) as total_mileage, SUM(ggr_profit) as total_ggr, SUM(ggr_mileage) as total_ggr_mileage FROM w_deal_log WHERE type="shop" AND shop_id =' . $shop->id . ' AND date_time <="'.$to .'" AND date_time>="'. $from. '"';
                $deal_logs = \DB::select($query);
                $adj['totaldealbet'] = $deal_logs[0]->totaldealbet??0;
                $adj['totaldealwin'] = $deal_logs[0]->totaldealwin??0;
                $adj['total_deal'] = $deal_logs[0]->total_deal??0;
                $adj['total_mileage'] = $deal_logs[0]->total_mileage??0;
                $adj['total_ggr'] = $deal_logs[0]->total_ggr??0;
                $adj['total_ggr_mileage'] = $deal_logs[0]->total_ggr_mileage??0;

                // $query = 'SELECT balance FROM w_shops WHERE id=' . $shop->id;
                // $in_out = \DB::select($query);
                // $adj['balance'] =  $in_out[0]->balance??0;
                $adj['balance'] = $shop->balance;
                $adj['deal_balance'] = $shop->deal_balance;
                $adj['deal_mileage'] = $shop->deal_mileage;
                $adj['shop_id'] = $shop->id;
                $adj['name'] = $shop->name;
                $adj['role_id'] = $user->role_id;
                //manager's id
                $adj['user_id'] = $user_id;
                return $adj;
            }
            return null;
        }
        public static function summary_month($user_id, $month=null)
        {
            set_time_limit(0);
            $user = \VanguardLTE\User::where('id', $user_id)->first();
            if (!$user)
            {
                return;
            }
            if (!$month)
            {
                $month = date("Y-m", strtotime("-1 days"));
            }
            
            $from =  $month . "-01";
            $to = $month . "-31";

            $childusers = $user->childPartners(); //하위 파트너들 먼저 정산하고, 그다음 해당 파트너들의 정산을 이용해서 계산
            foreach ($childusers as $c)
            {
                DailySummary::summary_month($c, $month);
            }

            $d_summary = \VanguardLTE\DailySummary::where('date', '>=', $from)->where('date', '<=', $to)->where(['type' => 'daily', 'user_id' => $user_id]);
            
            $adj['user_id']=$user_id;
            $adj['shop_id']=$user->shop_id;
            $adj['date']=$from;
            $adj['totalin']=$d_summary->sum('totalin');
            $adj['totalout']=$d_summary->sum('totalout');
            $adj['moneyin']=$d_summary->sum('moneyin');
            $adj['moneyout']=$d_summary->sum('moneyout');
            $adj['dealout']=$d_summary->sum('dealout');
            $adj['ggrout']=$d_summary->sum('ggrout');
            $adj['totalbet']=$d_summary->sum('totalbet');
            $adj['totalwin']=$d_summary->sum('totalwin');
            $adj['total_deal']=$d_summary->sum('total_deal');
            $adj['total_mileage']=$d_summary->sum('total_mileage');
            $adj['total_ggr']=$d_summary->sum('total_ggr');
            $adj['total_ggr_mileage']=$d_summary->sum('total_ggr_mileage');
            $adj['balance']=0;
            $adj['deal_balance']=0;
            $adj['deal_mileage']=0;
            $adj['childsum']=0;
            $adj['user_sum'] = 0;
            $adj['partner_sum'] = 0;
            $adj['user_dealsum'] = 0;
            $adj['partner_dealsum'] = 0;
            $adj['type']='monthly';
            $monthlysumm = \VanguardLTE\DailySummary::where(['user_id'=> $user_id, 'date' => $from, 'type'=>'monthly'])->first();
            if ($monthlysumm)
            {
                $monthlysumm->update($adj);
            }
            else
            {
                \VanguardLTE\DailySummary::create($adj);
            }

        }

        public static function summary($user_id, $day=null)
        {
            set_time_limit(0);
            $b_shop = false;
            $user = \VanguardLTE\User::where('id', $user_id)->first();
            if (!$user)
            {
                return;
            }
            if($user->hasRole('manager')){
                $b_shop = true;
            }

            if (!$day)
            {
                $day = date("Y-m-d", strtotime("-1 days"));
            }
            
            $from =  $day . " 0:0:0";
            $to = $day . " 23:59:59";
            if($b_shop){
                $adj = DailySummary::adjustment($user_id, $from, $to);
                $adj['date'] = $day;

                //get balance and childsum from snapshot table
                $shop_users = $user->availableUsers();
                if (count($shop_users) > 0) {
                    $query = 'SELECT SUM(balance) as sumbalance, SUM(deal_balance) as dealsum FROM w_users_snapshot WHERE id in ('.implode(',', $shop_users) .')';
                    $in_out = \DB::select($query);
                    $adj['childsum'] =  $in_out[0]->sumbalance??0;
                    $adj['user_sum'] = $in_out[0]->sumbalance??0;
                    $adj['partner_sum'] = 0;
                    $adj['user_dealsum'] = $in_out[0]->dealsum??0;
                    $adj['partner_dealsum'] = 0;

                    $query = 'SELECT balance, deal_balance, deal_mileage FROM w_shops_snapshot WHERE id=' . $user->shop_id;
                    $in_out = \DB::select($query);
                    $adj['balance'] =  $in_out[0]->balance??0;
                    $adj['deal_balance'] =  $in_out[0]->deal_balance??0;
                    $adj['deal_mileage'] =  $in_out[0]->deal_mileage??0;
                }
                $dailysumm = \VanguardLTE\DailySummary::where(['user_id'=> $user->id, 'date' => $day, 'type'=>'daily'])->first();
                if ($dailysumm)
                {
                    $dailysumm->update($adj);
                }
                else
                {
                    \VanguardLTE\DailySummary::create($adj);
                }
                return $adj;
            }
            else
            {
                //repeat child partners
                $adj = [
                    'user_id' => $user->id, 
                    'shop_id' => 0,
                    'date' => $day,
                    'totalin' => 0, //deposit via request
                    'totalout' => 0, //withdraw via request
                    'moneyin' => 0, // manual deposit
                    'moneyout' => 0, //manual withdraw
                    'dealout' => 0,
                    'ggrout' => 0,
                    'totalbet' => 0,
                    'totalwin' => 0,
                    'totaldealbet' => 0,
                    'totaldealwin' => 0,
                    'total_deal' => 0,
                    'total_mileage' => 0,
                    'total_ggr' => 0,
                    'total_ggr_mileage' => 0,
                    'balance' => 0,
                    'deal_balance' => 0,
                    'deal_mileage' => 0,
                    'childsum' => 0,
                    'user_sum' => 0,
                    'partner_sum' => 0,
                    'user_dealsum' => 0,
                    'partner_dealsum' => 0,
                    'type' => 'daily',
                ];
                $childusers = $user->childPartners(); //하위 파트너들 먼저 정산하고, 그다음 해당 파트너들의 정산을 이용해서 계산
                foreach ($childusers as $c)
                {
                    $childAdj = DailySummary::summary($c, $day);
                    $adj['totalin'] = $adj['totalin'] + $childAdj['totalin'];
                    $adj['totalout'] = $adj['totalout'] + $childAdj['totalout'];
                    // $adj['moneyin'] = $adj['moneyin'] + $childAdj['moneyin'];
                    // $adj['moneyout'] = $adj['moneyout'] + $childAdj['moneyout'];
                    $adj['dealout'] = $adj['dealout'] + $childAdj['dealout'];
                    $adj['ggrout'] = $adj['ggrout'] + $childAdj['ggrout'];
                    $adj['totalbet'] = $adj['totalbet'] + $childAdj['totalbet'];
                    $adj['totalwin'] = $adj['totalwin'] + $childAdj['totalwin'];
                    $adj['totaldealbet'] = $adj['totaldealbet'] + $childAdj['totaldealbet'];
                    $adj['totaldealwin'] = $adj['totaldealwin'] + $childAdj['totaldealwin'];
                    $adj['childsum'] = $adj['childsum'] + $childAdj['balance'] + $childAdj['childsum'];
                    $adj['user_sum'] = $adj['user_sum'] +$childAdj['user_sum'];
                    $adj['partner_sum'] = $adj['partner_sum'] + $childAdj['balance'];
                    $adj['user_dealsum'] = $adj['user_dealsum'] +$childAdj['user_dealsum'];
                    $adj['partner_dealsum'] = $adj['partner_dealsum'] + $childAdj['partner_dealsum'] + $childAdj['deal_balance'] - $childAdj['deal_mileage'];
                }

                if (!$user->hasRole('admin'))
                {

                    $query = 'SELECT SUM(summ) as totalin FROM w_transactions WHERE user_id='.$user->id.' AND created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="add" AND request_id IS NOT NULL';
                    $in_out = \DB::select($query);
                    $adj['totalin'] = $adj['totalin'] + $in_out[0]->totalin;

                    $query = 'SELECT SUM(summ) as totalout FROM w_transactions WHERE user_id='.$user->id.' AND created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="out" AND request_id IS NOT NULL';
                    $in_out = \DB::select($query);
                    $adj['totalout'] = $adj['totalout'] + $in_out[0]->totalout;

                    $childPartners = $user->hierarchyPartners();
                    $childPartners[] = $user->id;
                    $availableUsers = $user->availableUsers();
                    $availableUsers[] = $user->id; //include self in/out
                    //exclude manager users
                    $managerUsers = $user->availableUsersByRole('manager');
                    $availableUsers = array_diff($availableUsers, $managerUsers);

                    $availableShops = $user->availableShops();
                    

                    if (count($availableUsers) > 0 && count($childPartners) > 0 && count($availableShops) > 0){

                        $query = 'SELECT SUM(summ) as moneyin FROM w_transactions WHERE user_id IN ('.implode(',', $availableUsers).') AND created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="add" AND request_id IS NULL AND payeer_id NOT IN ('.implode(',', $childPartners).')';
                        $in_out = \DB::select($query);
                        $adj['moneyin'] = $adj['moneyin'] + $in_out[0]->moneyin;

                        $query = 'SELECT SUM(sum) as moneyin FROM w_shops_stat WHERE shop_id IN ('.implode(',', $availableShops).') AND date_time <="'.$to .'" AND date_time>="'. $from. '" AND type="add" AND request_id IS NULL AND user_id NOT IN ('.implode(',', $childPartners).')';
                        $in_out = \DB::select($query);
                        $adj['moneyin'] = $adj['moneyin'] + $in_out[0]->moneyin;

                        $query = 'SELECT SUM(summ) as moneyout FROM w_transactions WHERE user_id IN ('.implode(',', $availableUsers).') AND created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="out" AND request_id IS NULL AND payeer_id NOT IN ('.implode(',', $childPartners).')';
                        $in_out = \DB::select($query);
                        $adj['moneyout'] = $adj['moneyout'] + $in_out[0]->moneyout;

                        $query = 'SELECT SUM(sum) as moneyout FROM w_shops_stat WHERE shop_id IN ('.implode(',', $availableShops).') AND date_time <="'.$to .'" AND date_time>="'. $from. '" AND type="out" AND request_id IS NULL AND user_id NOT IN ('.implode(',', $childPartners).')';
                        $in_out = \DB::select($query);
                        $adj['moneyout'] = $adj['moneyout'] + $in_out[0]->moneyout;
                    }

                    $query = 'SELECT SUM(summ) as dealout FROM w_transactions WHERE user_id='.$user->id.' AND created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="deal_out"';
                    $in_out = \DB::select($query);
                    $adj['dealout'] = $adj['dealout'] + $in_out[0]->dealout;

                    $query = 'SELECT SUM(summ) as ggrout FROM w_transactions WHERE user_id='.$user->id.' AND created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="ggr_out"';
                    $in_out = \DB::select($query);
                    $adj['ggrout'] = $adj['ggrout'] + $in_out[0]->ggrout;

                    if ($user->isInoutPartner() && count($childusers) > 0)
                    {
                        $query = 'SELECT 0 as total_deal, SUM(deal_profit) as total_mileage, 0 as total_ggr, SUM(ggr_profit) as total_ggr_mileage  FROM w_deal_log WHERE type="partner" AND partner_id in ('. implode(',', $childusers) .') AND date_time <="'.$to .'" AND date_time>="'. $from. '"';
                    }
                    else
                    {
                        $query = 'SELECT SUM(deal_profit) as total_deal, SUM(mileage) as total_mileage, SUM(ggr_profit) as total_ggr, SUM(ggr_mileage) as total_ggr_mileage  FROM w_deal_log WHERE type="partner" AND partner_id='. $user->id .' AND date_time <="'.$to .'" AND date_time>="'. $from. '"';
                    }

                    $deal_logs = \DB::select($query);
                    $adj['total_deal'] = $deal_logs[0]->total_deal??0;
                    $adj['total_mileage'] = $deal_logs[0]->total_mileage??0;
                    $adj['total_ggr'] = $deal_logs[0]->total_ggr??0;
                    $adj['total_ggr_mileage'] = $deal_logs[0]->total_ggr_mileage??0;
                }
                else //admin
                {
                    $managerUsers = $user->availableUsersByRole('manager');
                    // $managerUsers[] = 0; //avoid if count(manager)=0
                    $query = 'SELECT SUM(summ) as moneyin FROM w_transactions WHERE created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="add" AND request_id IS NULL AND payeer_id =' . $user->id . ' AND user_id NOT IN (' .implode(',', $managerUsers). ')';
                    $in_out = \DB::select($query);
                    $adj['moneyin'] = $adj['moneyin'] + $in_out[0]->moneyin;

                    $query = 'SELECT SUM(sum) as moneyin FROM w_shops_stat WHERE date_time <="'.$to .'" AND date_time>="'. $from. '" AND type="add" AND request_id IS NULL AND user_id =' . $user->id;
                    $in_out = \DB::select($query);
                    $adj['moneyin'] = $adj['moneyin'] + $in_out[0]->moneyin;

                    $query = 'SELECT SUM(summ) as moneyout FROM w_transactions WHERE created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="out" AND request_id IS NULL AND payeer_id =' . $user->id . ' AND user_id NOT IN (' .implode(',', $managerUsers). ')';;
                    $in_out = \DB::select($query);
                    $adj['moneyout'] = $adj['moneyout'] + $in_out[0]->moneyout;

                    $query = 'SELECT SUM(sum) as moneyout FROM w_shops_stat WHERE date_time <="'.$to .'" AND date_time>="'. $from. '" AND type="out" AND request_id IS NULL AND user_id =' . $user->id;
                    $in_out = \DB::select($query);
                    $adj['moneyout'] = $adj['moneyout'] + $in_out[0]->moneyout;
                }

                $query = 'SELECT balance,deal_balance,deal_mileage FROM w_users_snapshot WHERE id=' . $user->id;
                $in_out = \DB::select($query);
                $adj['balance'] =  $in_out[0]->balance??0;
                $adj['deal_balance'] =  $in_out[0]->deal_balance??0;
                $adj['deal_mileage'] =  $in_out[0]->deal_mileage??0;

                $dailysumm = \VanguardLTE\DailySummary::where(['user_id'=> $user->id, 'date' => $day, 'type'=>'daily'])->first();
                if ($dailysumm)
                {
                    $dailysumm->update($adj);
                }
                else
                {
                    \VanguardLTE\DailySummary::create($adj);
                }
                return $adj;

            }
        }

        public static function summary_today($user_id)
        {
            set_time_limit(0);
            $user = \VanguardLTE\User::where('id', $user_id)->first();
            if (!$user)
            {
                return;
            }
            $day = date("Y-m-d");

            $todaysumm = \VanguardLTE\DailySummary::where(['user_id'=> $user->id, 'date' => $day, 'type'=>'today'])->first();
            if ($todaysumm)
            {
                $from = $todaysumm->updated_at;
            }
            else
            {
                $from =  $day . " 0:0:0";
            }
            $to =  date("Y-m-d H:i:s", strtotime("now"));

            $adj = [
                'user_id' => $user->id, 
                'shop_id' => 0,
                'date' => $day,
                'totalin' => 0, //deposit via request
                'totalout' => 0, //withdraw via request
                'moneyin' => 0, // manual deposit
                'moneyout' => 0, //manual withdraw
                'dealout' => 0,
                'ggrout' => 0,
                'totalbet' => 0,
                'totalwin' => 0,
                'totaldealbet' => 0,
                'totaldealwin' => 0,
                'total_deal' => 0,
                'total_mileage' => 0,
                'total_ggr' => 0,
                'total_ggr_mileage' => 0,
                'balance' => 0,
                'deal_balance' => 0,
                'deal_mileage' => 0,
                'childsum' => 0,
                'user_sum' => 0,
                'partner_sum' => 0,
                'user_dealsum' => 0,
                'partner_dealsum' => 0,
                'type' => 'today',
                'updated_at' => $to,
            ];

            if($user->hasRole('manager')){
                $adj = DailySummary::adjustment($user_id, $from, $to);
            }
            else
            {
                $childusers = $user->childPartners(); //하위 파트너들 먼저 정산하고, 그다음 해당 파트너들의 정산을 이용해서 계산
                foreach ($childusers as $c)
                {
                    $childAdj = DailySummary::summary_today($c);
                    if ($childAdj){
                        $adj['totalin'] = $adj['totalin'] + $childAdj['totalin'];
                        $adj['totalout'] = $adj['totalout'] + $childAdj['totalout'];
                        // $adj['moneyin'] = $adj['moneyin'] + $childAdj['moneyin'];
                        // $adj['moneyout'] = $adj['moneyout'] + $childAdj['moneyout'];
                        $adj['dealout'] = $adj['dealout'] + $childAdj['dealout'];
                        $adj['ggrout'] = $adj['ggrout'] + $childAdj['ggrout'];
                        $adj['totalbet'] = $adj['totalbet'] + $childAdj['totalbet'];
                        $adj['totalwin'] = $adj['totalwin'] + $childAdj['totalwin'];
                        $adj['totaldealbet'] = $adj['totaldealbet'] + $childAdj['totaldealbet'];
                        $adj['totaldealwin'] = $adj['totaldealwin'] + $childAdj['totaldealwin'];
                        $adj['childsum'] = $adj['childsum'] +$childAdj['balance'] + $childAdj['childsum'];
                        $adj['user_sum'] = $adj['user_sum'] +$childAdj['user_sum'];
                        $adj['partner_sum'] = $adj['partner_sum'] + $childAdj['balance'];
                        $adj['user_dealsum'] = $adj['user_dealsum'] +$childAdj['user_dealsum'];
                        $adj['partner_dealsum'] = $adj['partner_dealsum'] + $childAdj['partner_dealsum'] + $childAdj['deal_balance'] - $childAdj['deal_mileage'];
                    }
                }

                if (!$user->hasRole('admin'))
                {
                    $query = 'SELECT SUM(summ) as totalin FROM w_transactions WHERE user_id='.$user->id.' AND created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="add" AND request_id IS NOT NULL';
                    $in_out = \DB::select($query);
                    $adj['totalin'] = $adj['totalin'] + $in_out[0]->totalin;

                    $query = 'SELECT SUM(summ) as totalout FROM w_transactions WHERE user_id='.$user->id.' AND created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="out" AND request_id IS NOT NULL';
                    $in_out = \DB::select($query);
                    $adj['totalout'] = $adj['totalout'] + $in_out[0]->totalout;

                    $childPartners = $user->hierarchyPartners();
                    $childPartners[] = $user->id;
                    $availableUsers = $user->availableUsers();
                    $availableUsers[] = $user->id; //include self in/out
                    //exclude manager users
                    $managerUsers = $user->availableUsersByRole('manager');
                    $availableUsers = array_diff($availableUsers, $managerUsers);

                    $availableShops = $user->availableShops();

                    if (count($availableUsers) > 0 && count($childPartners) > 0 && count($availableShops) > 0){

                        $query = 'SELECT SUM(summ) as moneyin FROM w_transactions WHERE user_id IN ('.implode(',', $availableUsers).') AND created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="add" AND request_id IS NULL AND payeer_id NOT IN ('.implode(',', $childPartners).')';
                        $in_out = \DB::select($query);
                        $adj['moneyin'] = $adj['moneyin'] + $in_out[0]->moneyin;

                        $query = 'SELECT SUM(sum) as moneyin FROM w_shops_stat WHERE shop_id IN ('.implode(',', $availableShops).') AND date_time <="'.$to .'" AND date_time>="'. $from. '" AND type="add" AND request_id IS NULL AND user_id NOT IN ('.implode(',', $childPartners).')';
                        $in_out = \DB::select($query);
                        $adj['moneyin'] = $adj['moneyin'] + $in_out[0]->moneyin;

                        $query = 'SELECT SUM(summ) as moneyout FROM w_transactions WHERE user_id IN ('.implode(',', $availableUsers).') AND created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="out" AND request_id IS NULL AND payeer_id NOT IN ('.implode(',', $childPartners).')';
                        $in_out = \DB::select($query);
                        $adj['moneyout'] = $adj['moneyout'] + $in_out[0]->moneyout;

                        $query = 'SELECT SUM(sum) as moneyout FROM w_shops_stat WHERE shop_id IN ('.implode(',', $availableShops).') AND date_time <="'.$to .'" AND date_time>="'. $from. '" AND type="out" AND request_id IS NULL AND user_id NOT IN ('.implode(',', $childPartners).')';
                        $in_out = \DB::select($query);
                        $adj['moneyout'] = $adj['moneyout'] + $in_out[0]->moneyout;
                    }

                    $query = 'SELECT SUM(summ) as dealout FROM w_transactions WHERE user_id='.$user->id.' AND created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="deal_out"';
                    $in_out = \DB::select($query);
                    $adj['dealout'] = $adj['dealout'] + $in_out[0]->dealout;

                    $query = 'SELECT SUM(summ) as ggrout FROM w_transactions WHERE user_id='.$user->id.' AND created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="ggr_out"';
                    $in_out = \DB::select($query);
                    $adj['ggrout'] = $adj['ggrout'] + $in_out[0]->ggrout;

                    $query = 'SELECT SUM(deal_profit) as total_deal, SUM(mileage) as total_mileage, SUM(ggr_profit) as total_ggr, SUM(ggr_mileage) as total_ggr_mileage  FROM w_deal_log WHERE type="partner" AND partner_id='. $user->id .' AND date_time <="'.$to .'" AND date_time>="'. $from. '"';

                    $deal_logs = \DB::select($query);
                    $adj['total_deal'] = $deal_logs[0]->total_deal??0;
                    $adj['total_mileage'] = $deal_logs[0]->total_mileage??0;
                    $adj['total_ggr'] = $deal_logs[0]->total_ggr??0;
                    $adj['total_ggr_mileage'] = $deal_logs[0]->total_ggr_mileage??0;
                }
                else //admin
                {
                    $managerUsers = $user->availableUsersByRole('manager');
                    // $managerUsers[] = 0; //avoid if count(manager)=0
                    $query = 'SELECT SUM(summ) as moneyin FROM w_transactions WHERE created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="add" AND request_id IS NULL AND payeer_id =' . $user->id . ' AND user_id NOT IN (' .implode(',', $managerUsers). ')';
                    $in_out = \DB::select($query);
                    $adj['moneyin'] = $adj['moneyin'] + $in_out[0]->moneyin;

                    $query = 'SELECT SUM(sum) as moneyin FROM w_shops_stat WHERE date_time <="'.$to .'" AND date_time>="'. $from. '" AND type="add" AND request_id IS NULL AND user_id =' . $user->id;
                    $in_out = \DB::select($query);
                    $adj['moneyin'] = $adj['moneyin'] + $in_out[0]->moneyin;

                    $query = 'SELECT SUM(summ) as moneyout FROM w_transactions WHERE created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="out" AND request_id IS NULL AND payeer_id =' . $user->id . ' AND user_id NOT IN (' .implode(',', $managerUsers). ')';;
                    $in_out = \DB::select($query);
                    $adj['moneyout'] = $adj['moneyout'] + $in_out[0]->moneyout;

                    $query = 'SELECT SUM(sum) as moneyout FROM w_shops_stat WHERE date_time <="'.$to .'" AND date_time>="'. $from. '" AND type="out" AND request_id IS NULL AND user_id =' . $user->id;
                    $in_out = \DB::select($query);
                    $adj['moneyout'] = $adj['moneyout'] + $in_out[0]->moneyout;
                }

                $adj['balance'] =  $user->balance;
                $adj['deal_balance'] =  $user->deal_balance;
                $adj['deal_mileage'] =  $user->deal_mileage;
            }

            $adj['date'] = $day;
            $adj['type'] ='today';
            $adj['updated_at'] = $to;
            $aa = $adj;
            if ($todaysumm)
            {
                $adj['user_id']=$user_id;
                $adj['shop_id']=$user->shop_id;
                $adj['totalin']+=$todaysumm->totalin;
                $adj['totalout']+=$todaysumm->totalout;
                $adj['moneyin']+=$todaysumm->moneyin;
                $adj['moneyout']+=$todaysumm->moneyout;
                $adj['dealout']+=$todaysumm->dealout;
                $adj['ggrout']+=$todaysumm->ggrout;
                $adj['totalbet']+=$todaysumm->totalbet;
                $adj['totalwin']+=$todaysumm->totalwin;
                $adj['totaldealbet']+=$todaysumm->totaldealbet;
                $adj['totaldealwin']+=$todaysumm->totaldealwin;
                $adj['total_deal']+=$todaysumm->total_deal;
                $adj['total_mileage']+=$todaysumm->total_mileage;
                $adj['total_ggr']+=$todaysumm->total_ggr;
                $adj['total_ggr_mileage']+=$todaysumm->total_ggr_mileage;
                $todaysumm->update($adj);
            }
            else
            {
                \VanguardLTE\DailySummary::create($adj);
            }
            return $aa;
        }
    }

}

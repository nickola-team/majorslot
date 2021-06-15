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
            'totalin',
            'totalout',
            'moneyin',
            'moneyout',
            'dealout',
            'totalbet',
            'totalwin',
            'total_deal',
            'total_mileage',
            'balance',
            'type'
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

        public static function adjustment($user_id, $from, $to)
        {
            set_time_limit(0);
            $b_shop = false;
            $user = \VanguardLTE\User::where('id', $user_id)->first();
            if (!$user)
            {
                return null;
            }
            if($user->hasRole('manager')){
                $b_shop = true;
            }

            if($b_shop){
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
                $adj['totalout'] = $adj['totalout'] + $adj['dealout'];

                $query = 'SELECT SUM(summ) as moneyin FROM w_transactions WHERE shop_id='.$shop->id.' AND created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="add"';
                $user_in_out = \DB::select($query);
                $adj['moneyin'] = $user_in_out[0]->moneyin??0;

                $query = 'SELECT SUM(summ) as moneyout FROM w_transactions WHERE shop_id='.$shop->id.' AND created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="out"';
                $user_in_out = \DB::select($query);
                $adj['moneyout'] = $user_in_out[0]->moneyout??0;


                $query = 'SELECT SUM(bet) as totalbet, SUM(win) as totalwin FROM w_stat_game WHERE shop_id='.$shop->id.' AND date_time <="'.$to .'" AND date_time>="'. $from. '"';
                $game_bet = \DB::select($query);
                $adj['totalbet'] = $game_bet[0]->totalbet??0;
                $adj['totalwin'] = $game_bet[0]->totalwin??0;

                $query = 'SELECT SUM(deal_profit) as total_deal, SUM(mileage) as total_mileage FROM w_deal_log WHERE type="shop" AND shop_id =' . $shop->id . ' AND date_time <="'.$to .'" AND date_time>="'. $from. '"';
                $deal_logs = \DB::select($query);
                $adj['total_deal'] = $deal_logs[0]->total_deal??0;
                $adj['total_mileage'] = $deal_logs[0]->total_mileage??0;
                $adj['balance'] = $shop->balance;
                $adj['shop_id'] = $shop->id;
                $adj['name'] = $shop->name;
                $adj['role_id'] = $user->role_id;
                //manager's id
                $adj['user_id'] = $user_id;
                return $adj;
            }
            else
            {
                //repeat child partners
                $partner = $user;
                $shops = $partner->availableShops();
                /*if( $partner->hasRole('admin') ) 
                {
                    $partners = $partner->childPartners();
                    $shops = \VanguardLTE\ShopUser::whereIn('user_id', $partners)->pluck('shop_id')->toArray();
                }
                else
                {
                    $shops = \VanguardLTE\ShopUser::where('user_id', $partner->id)->pluck('shop_id')->toArray();
                }*/
                $adj['totalin'] = 0;
                $adj['totalout'] = 0;
                $adj['dealout'] = 0;
                if (count($shops) > 0 ){
                    $query = 'SELECT SUM(sum) as totalin FROM w_shops_stat WHERE shop_id in ('.implode(',', $shops).') AND date_time <="'.$to .'" AND date_time>="'. $from. '" AND type="add" AND request_id IS NOT NULL';
                    $user_in_out = \DB::select($query);
                    $adj['totalin'] = $user_in_out[0]->totalin??0;

                    $query = 'SELECT SUM(sum) as totalout FROM w_shops_stat WHERE shop_id in ('.implode(',', $shops).') AND date_time <="'.$to .'" AND date_time>="'. $from. '" AND type="out" AND request_id IS NOT NULL';
                    $user_in_out = \DB::select($query);
                    $adj['totalout'] = $user_in_out[0]->totalout??0;
                    

                    $query = 'SELECT SUM(sum) as dealout FROM w_shops_stat WHERE shop_id in ('.implode(',', $shops).') AND date_time <="'.$to .'" AND date_time>="'. $from. '" AND type="deal_out"';
                    $in_out = \DB::select($query);
                    $adj['dealout'] = $in_out[0]->dealout??0;
                    $adj['totalout'] = $adj['totalout'] + $in_out[0]->dealout;
                    
                    //agent, distributor's deal out
                    $partner_users = $partner->availableUsers();
                    if( count($partner_users) > 0) 
                    {
                        $level = $partner->level();
                        $childpartners = \VanguardLTE\User::whereIn('role_id', range(4,$level))->whereIn('id', $partner_users)->pluck('id')->toArray();

                        if (count($childpartners) > 0){
                            $query = 'SELECT SUM(summ) as dealout FROM w_transactions WHERE user_id in ('.implode(',', $childpartners).') AND created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="deal_out"';
                            $in_out = \DB::select($query);
                            $adj['dealout'] = $adj['dealout'] + $in_out[0]->dealout;
                            $adj['totalout'] = $adj['totalout'] + $in_out[0]->dealout;

                            $query = 'SELECT SUM(summ) as totalout FROM w_transactions WHERE user_id in ('.implode(',', $childpartners).') AND created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="out" AND request_id IS NOT NULL';
                            $in_out = \DB::select($query);
                            $adj['totalout'] = $adj['totalout'] + $in_out[0]->totalout;
                        }
                    }
                    
                }

                $adj['moneyin'] = 0;
                $adj['moneyout'] = 0;

                if (count($shops) > 0){
                    $query = 'SELECT SUM(summ) as moneyin FROM w_transactions WHERE shop_id in ('.implode(',', $shops).') AND created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="add"';
                    $user_in_out = \DB::select($query);
                    $adj['moneyin'] = $user_in_out[0]->moneyin??0;

                    $query = 'SELECT SUM(summ) as moneyout FROM w_transactions WHERE shop_id in ('.implode(',', $shops).') AND created_at <="'.$to .'" AND created_at>="'. $from. '" AND type="out"';
                    $user_in_out = \DB::select($query);
                    $adj['moneyout'] = $user_in_out[0]->moneyout??0;
                }
                if (count($shops) > 0 )
                {
                    $query = 'SELECT SUM(bet) as totalbet, SUM(win) as totalwin FROM w_stat_game WHERE shop_id in ('. implode(',',$shops) .') AND date_time <="'.$to .'" AND date_time>="'. $from. '"';
                }
                else
                {
                    $query = 'SELECT 0 as totalbet, 0 as totalwin';
                }
                $game_bet = \DB::select($query);
                $adj['totalbet'] = $game_bet[0]->totalbet??0;
                $adj['totalwin'] = $game_bet[0]->totalwin??0;
                if ($partner->hasRole(['admin','comaster']))
                {
                    $query = 'SELECT 0 as total_deal, 0 as total_mileage';
                }
                else if ($partner->hasRole('master'))
                {
                    $agents = $partner->childPartners();
                    if (count($agents) > 0){
                        $query = 'SELECT 0 as total_deal, SUM(deal_profit) as total_mileage FROM w_deal_log WHERE type="partner" AND partner_id in ('. implode(',',$agents) .') AND date_time <="'.$to .'" AND date_time>="'. $from. '"';
                    }
                    else
                    {
                        $query = 'SELECT 0 as total_deal, 0 as total_mileage';
                    }
                }
                else
                {
                    $query = 'SELECT SUM(deal_profit) as total_deal, SUM(mileage) as total_mileage FROM w_deal_log WHERE type="partner" AND partner_id='. $partner->id .' AND date_time <="'.$to .'" AND date_time>="'. $from. '"';
                }
                $deal_logs = \DB::select($query);
                $adj['total_deal'] = $deal_logs[0]->total_deal??0;
                $adj['total_mileage'] = $deal_logs[0]->total_mileage??0;
                $adj['balance'] = $partner->balance;
                $adj['name'] = $partner->username;
                $adj['user_id'] = $partner->id;
                $adj['role_id'] = $partner->role_id;
                $adj['profit'] = 0;
                return $adj;
            }
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
            $adj['totalbet']=$d_summary->sum('totalbet');
            $adj['totalwin']=$d_summary->sum('totalwin');
            $adj['total_deal']=$d_summary->sum('total_deal');
            $adj['total_mileage']=$d_summary->sum('total_mileage');
            $adj['balance']=0;
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
                $dailysumm = \VanguardLTE\DailySummary::where(['shop_id'=> $user->shop_id, 'date' => $day, 'type'=>'daily'])->first();
                if ($dailysumm)
                {
                    $dailysumm->update($adj);
                }
                else
                {
                    \VanguardLTE\DailySummary::create($adj);
                }
            }
            else
            {
                //repeat child partners
                $childusers = $user->childPartners(); //하위 파트너들 먼저 정산하고, 그다음 해당 파트너들의 정산을 이용해서 계산
                foreach ($childusers as $c)
                {
                    DailySummary::summary($c, $day);
                }
                $adj = DailySummary::adjustment($user_id, $from, $to);
                $adj['date'] = $day;             

                $dailysumm = \VanguardLTE\DailySummary::where(['user_id'=> $user->id, 'date' => $day, 'type'=>'daily'])->first();
                if ($dailysumm)
                {
                    $dailysumm->update($adj);
                }
                else
                {
                    \VanguardLTE\DailySummary::create($adj);
                }

            }
        }
    }

}

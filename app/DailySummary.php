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
            'balance'
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
                $shop = \VanguardLTE\Shop::where('id', $user->shop_id)->first();
                $query = 'SELECT SUM(sum) as totalin FROM w_shops_stat WHERE shop_id='.$shop->id.' AND date_time <="'.$to .'" AND date_time>="'. $from. '" AND type="add" AND request_id IS NOT NULL';
                $in_out = \DB::select($query);
                $adj['totalin'] = $in_out[0]->totalin??0;

                $query = 'SELECT SUM(sum) as totalout FROM w_shops_stat WHERE shop_id='.$shop->id.' AND date_time <="'.$to .'" AND date_time>="'. $from. '" AND type="out" AND request_id IS NOT NULL';
                $in_out = \DB::select($query);
                $adj['totalout'] = $in_out[0]->totalout??0;

                $query = 'SELECT SUM(sum) as dealout FROM w_shops_stat WHERE shop_id='.$shop->id.' AND date_time <="'.$to .'" AND date_time>="'. $from. '" AND type="deal_out" AND request_id IS NOT NULL';
                $in_out = \DB::select($query);
                $adj['dealout'] = $in_out[0]->dealout??0;

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
                $adj['date'] = $day;
                $adj['shop_id'] = $shop->id;
                //manager's id
                $adj['user_id'] = $user_id;
                $dailysumm = \VanguardLTE\DailySummary::where(['shop_id'=> $shop->id, 'date' => $day])->first();
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

                $adj['totalin'] = 0;
                $adj['totalout'] = 0;
                $adj['dealout'] = 0;
                $adj['moneyin'] = 0;
                $adj['moneyout'] = 0;
                $adj['totalbet'] = 0;
                $adj['totalwin'] = 0;
                $adj['total_deal'] = 0;
                $adj['total_mileage'] = 0;
                $adj['balance'] = $user->balance;
                $adj['date'] = $day;
                $adj['shop_id'] = 0; //shop is 0
                //partner's id
                $adj['user_id'] = $user->id;

                if (count($childusers) > 0 ){
                    $childSummary = DailySummary::where('date', $day)->whereIn('user_id', $childusers)->get();
                    $adj['totalin'] = $childSummary->sum('totalin');
                    $adj['totalout'] = $childSummary->sum('totalout');
                    $adj['dealout'] = $childSummary->sum('dealout');
                    $adj['moneyin'] = $childSummary->sum('moneyin');
                    $adj['moneyout'] = $childSummary->sum('moneyout');
                    $adj['totalbet'] = $childSummary->sum('totalbet');
                    $adj['totalwin'] = $childSummary->sum('totalwin');
                }
                $query = null;
                if ($user->hasRole('master'))
                {
                    $agents = $user->childPartners();
                    if (count($agents) > 0){
                        $query = 'SELECT 0 as total_deal, SUM(deal_profit) as total_mileage FROM w_deal_log WHERE type="partner" AND partner_id in ('. implode(',',$agents) .') AND date_time <="'.$to .'" AND date_time>="'. $from. '"';
                    }
                }
                else
                {
                    $query = 'SELECT SUM(deal_profit) as total_deal, SUM(mileage) as total_mileage FROM w_deal_log WHERE type="partner" AND partner_id='. $user->id .' AND date_time <="'.$to .'" AND date_time>="'. $from. '"';
                }
                if ($query){
                    $deal_logs = \DB::select($query);
                    $adj['total_deal'] = $deal_logs[0]->total_deal ?? 0;
                    $adj['total_mileage'] = $deal_logs[0]->total_mileage ?? 0;
                }

                $dailysumm = \VanguardLTE\DailySummary::where(['user_id'=> $user->id, 'date' => $day])->first();
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

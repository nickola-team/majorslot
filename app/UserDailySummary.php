<?php 
namespace VanguardLTE
{
    class UserDailySummary extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'userdaily_summary';
        protected $fillable = [
            'user_id', 
            'date',
            'totalbet',
            'totalwin',
            'type',
            'gametype',
            'created_at',
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
            return $this->hasOne('VanguardLTE\UserDailySummary', 'user_id', 'user_id')->where('date', date('Y-m-d', strtotime("$this->date -1 days")));        
        }
        public static function adjustment($from, $to)
        {
            set_time_limit(0);
            $adjs = [];
            $query = 'SELECT user_id, SUM(bet) as totalbet, SUM(win) as totalwin, type as gametype FROM w_stat_game WHERE date_time <="'.$to .'" AND date_time>="'. $from. '" group by user_id, type';
            $adjs = \DB::select($query);
            
            return $adjs;
        }
        public static function summary($user_id, $day=null)
        {
            set_time_limit(0);
            $user = \VanguardLTE\User::where('id', $user_id)->first();
            if (!$user)
            {
                return;
            }
            if (!$day)
            {
                $day = date("Y-m-d", strtotime("-1 days"));
            }
            
            $from =  $day . " 0:0:0";
            $to = $day . " 23:59:59";
            $adjs = UserDailySummary::adjustment($from, $to);
            foreach($adjs as $adj){
                $adj->date = $day;
                $adj->type = 'daily';
                $dailysumm = \VanguardLTE\UserDailySummary::where(['user_id'=> $adj->user_id, 'date' => $day, 'gametype'=>$adj->type, 'type'=>'daily'])->first();
                $adj = json_decode(json_encode($adj), true);
                if ($dailysumm)
                {
                    $dailysumm->update($adj);
                }
                else
                {
                    \VanguardLTE\UserDailySummary::create($adj);
                }
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
            $from =  $day . " 0:0:0";
            $to = $day . " 23:59:59";
            $adjs = UserDailySummary::adjustment($from, $to);
            foreach($adjs as $adj){
                $adj->date = $day;
                $adj->type = 'today';
                $dailysumm = \VanguardLTE\UserDailySummary::where(['user_id'=> $adj->user_id, 'date' => $day, 'gametype'=>$adj->type, 'type'=>'today'])->first();
                $adj = json_decode(json_encode($adj), true);
                if ($dailysumm)
                {
                    $dailysumm->update($adj);
                }
                else
                {
                    \VanguardLTE\UserDailySummary::create($adj);
                }
            }
        }
    }

}

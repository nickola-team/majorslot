<?php 
namespace VanguardLTE
{
    class ShareBetlog extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'sharebet_log';
        protected $fillable = [
            'user_id',
            'date_time',
            'game',
            'partner_id',
            'share_id', 
            'bet',
            'win',
            'betlimit', 
            'winlimit',
            'deal_percent',
            'deal_limit',
            'deal_share',
            'shop_id',
            'category_id', 
            'game_id',
            'stat_id'
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
        public function partner()
        {
            return $this->belongsTo('VanguardLTE\User', 'partner_id');
        }
        public function shareuser()
        {
            return $this->belongsTo('VanguardLTE\User', 'share_id');
        }
        public function category()
        {
            return $this->belongsTo('VanguardLTE\Category', 'category_id');
        }

    }

}

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
            'minlimit', 
            'sharebet',
            'sharewin',
            'deal_percent',
            'deal_profit',
            'deal_share',
            'shop_id',
            'category_id', 
            'game_id'
        ];
        public $timestamps = false;
        public static function boot()
        {
            parent::boot();
        }

    }

}

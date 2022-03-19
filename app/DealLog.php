<?php 
namespace VanguardLTE
{
    class DealLog extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'deal_log';
        protected $fillable = [
            'date_time', 
            'user_id', 
            'partner_id',
            'balance_before',
            'balance_after',
            'bet', 
            'win', 
            'deal_profit',
            'mileage',
            'game', 
            'shop_id',
            'type',
            'deal_percent',
            'ggr_percent',
            'ggr_profit',
            'ggr_mileage',
            'category_id',
            'game_id'
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
        public function shop()
        {
            return $this->belongsTo('VanguardLTE\Shop');
        }
        public function partner()
        {
            return $this->belongsTo('VanguardLTE\User', 'partner_id');
        }
        public function game_item()
        {
            return $this->hasOne('VanguardLTE\Game', 'name', 'game');
        }
        public function name_ico()
        {
            return explode(' ', $this->game)[0];
        }

        public function category()
        {
            return $this->belongsTo('VanguardLTE\Category', 'category_id');
        }
    }

}

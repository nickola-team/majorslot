<?php 
namespace VanguardLTE
{
    class OnlineShop extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'onlineshop';
        protected $fillable = [
            'shop_id', 
            'join_bonus', 
            'extra', 
        ];
        public static function boot()
        {
            parent::boot();
        }
        public function shop()
        {
            return $this->belongsTo('VanguardLTE\Shop', 'shop_id');
        }
    }

}

<?php 
namespace VanguardLTE
{
    class PPPromoChoice extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'pppromo_choice';
        protected $fillable = [
            'user_id', 
            'promo_id'
        ];
        public $timestamps = false;
        public static function boot()
        {
            parent::boot();
        }
    }
}

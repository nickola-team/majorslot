<?php 
namespace VanguardLTE
{
    class ShareBetInfo extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'sharebet_info';
        protected $fillable = [
            'partner_id', 
            'share_id', 
            'minlimit', 
            'category_id', 
        ];
        public $timestamps = false;
        public static function boot()
        {
            parent::boot();
        }

        public function partner()
        {
            return $this->hasOne('VanguardLTE\User', 'id', 'partner_id');
        }
        public function category()
        {
            return $this->hasOne('VanguardLTE\Category', 'id', 'category_id');
        }
    }

}

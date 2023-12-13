<?php 
namespace VanguardLTE
{
    class SC4User extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'sc4_users';
        protected $fillable = [
            'sc4user_id', 
            'user_id'
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
    }

}

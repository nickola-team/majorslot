<?php 
namespace VanguardLTE
{
    class Notice extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'notice';
        protected $fillable = [
            'user_id', 
            'title', 
            'type', 
            'content', 
            'image', 
            'active',
            'date_time', 
        ];
        public $timestamps = false;
        public static function boot()
        {
            parent::boot();
        }

        public function writer()
        {
            return $this->hasOne('VanguardLTE\User', 'id', 'user_id');
        }
    }

}

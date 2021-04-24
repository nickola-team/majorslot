<?php 
namespace VanguardLTE
{
    class Notice extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'notice';
        protected $fillable = [
            'user_id', 
            'type', 
            'content', 
            'image', 
            'date_time', 
        ];
        public $timestamps = false;
        public static function boot()
        {
            parent::boot();
        }
    }

}

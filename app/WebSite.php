<?php 
namespace VanguardLTE
{
    class WebSite extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'website';
        protected $fillable = [
            'domain', 
            'title', 
            'frontend', 
            'backend', 
            'adminid',
            'created_at'
        ];
        public $timestamps = false;
        public static function boot()
        {
            parent::boot();
        }
    }

}

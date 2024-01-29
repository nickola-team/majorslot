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
            'status',
            'created_at'
        ];
        public $timestamps = false;
        public static function boot()
        {
            parent::boot();
        }

        public function admin()
        {
            return $this->hasOne('VanguardLTE\User', 'id', 'adminid');
        }
        public function categories()
        {
            $excat = ['hot', 'new', 'card','bingo','roulette', 'keno', 'novomatic','wazdan','skywind'];
            return $this->hasMany('VanguardLTE\Category', 'site_id')->whereNotIn('href', $excat)->where('shop_id', 0)->orderby('position', 'desc');
        }
    }

}

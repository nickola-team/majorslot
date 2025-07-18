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
            'popup', 
            'active',
            'date_time', 
        ];
        public $timestamps = false;
        public static function lists()
        {
            return [
                'user' => '회원',
                'partner' => '파트너',
                'all' => '전체'
            ];
        }
        public static function popups()
        {
            return [
                'all' => '팝업/공지',
                'popup' => '팝업',
                'general' => '일반',
            ];
        }
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

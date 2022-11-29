<?php 
namespace VanguardLTE
{
    class Message extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'message';
        protected $fillable = [
            'user_id',
            'writer_id',
            'ref_id',
            'title', 
            'content', 
            'created_at', 
            'read_at',
        ];
        public $timestamps = false;
        public static function boot()
        {
            parent::boot();
        }

        public function writer()
        {
            return $this->hasOne('VanguardLTE\User', 'id', 'writer_id');
        }
        public function user()
        {
            return $this->hasOne('VanguardLTE\User', 'id', 'user_id');
        }
        public function refs()
        {
            return $this->hasMany('VanguardLTE\Message', 'ref_id', 'id');
        }
    }

}

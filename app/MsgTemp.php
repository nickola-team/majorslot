<?php 
namespace VanguardLTE
{
    class MsgTemp extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'msg_temp';
        protected $fillable = [
            'order',
            'writer_id',
            'title', 
            'content', 
            'created_at', 
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
    }

}

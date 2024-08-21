<?php 
namespace VanguardLTE
{
    class PPGameVerifyLog extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'ppgame_verify_log';
        protected $hidden = [
            'created_at', 
            'updated_at'
        ];
        protected $fillable = [
            'id', 
            'user_id', 
            'game_id', 
            'label', 
            'rid', 
            'crid', 
            'bet'
        ];        
        public $timestamps = false;
        public static function boot()
        {
            parent::boot();
        }
    }

}

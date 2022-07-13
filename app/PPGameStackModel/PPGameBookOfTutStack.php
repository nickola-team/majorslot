<?php 
namespace VanguardLTE\PPGameStackModel;
{
    class PPGameBookOfTutStack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'ppgame_bookoftut_stack';
        protected $fillable = [
            'id', 
            'game_id', 
            'odd', 
            'spin_stack', 
            'spin_type', 
            'pur_level'
        ];
        
        public static function boot()
        {
            parent::boot();
        }
    }

}

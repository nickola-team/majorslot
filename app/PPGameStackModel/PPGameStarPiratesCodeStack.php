<?php 
namespace VanguardLTE\PPGameStackModel;
{
    class PPGameStarPiratesCodeStack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'ppgame_starpiratescode_stack';
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

<?php 
namespace VanguardLTE\PPGameStackModel;
{
    class PPGameDayofDeadStack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'ppgame_dayofdead_stack';
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

<?php 
namespace VanguardLTE\PPGameStackModel;
{
    class PPGameDrillThatGoldStack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'ppgame_drillthatgold_stack';
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

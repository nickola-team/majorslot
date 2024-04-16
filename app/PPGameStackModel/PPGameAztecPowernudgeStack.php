<?php 
namespace VanguardLTE\PPGameStackModel;
{
    class PPGameAztecPowernudgeStack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'ppgame_aztecpowernudge_stack';
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

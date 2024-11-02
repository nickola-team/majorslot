<?php 
namespace VanguardLTE\PPGameStackModel;
{
    class PPGameFangtasticFreespinsStack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'ppgame_fangtasticfreespins_stack';
        protected $fillable = [
            'id', 
            'game_id', 
            'odd', 
            'spin_stack', 
            'spin_type', 
            'pur_level', 
            'fsmax'
        ];
        
        public static function boot()
        {
            parent::boot();
        }
    }

}

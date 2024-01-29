<?php 
namespace VanguardLTE\PPGameStackModel;
{
    class PPGameTheDogHouseMegawaysStack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'ppgame_thedoghousemegaways_stack';
        protected $fillable = [
            'id', 
            'game_id', 
            'odd', 
            'spin_stack', 
            'spin_type', 
            'pur_level', 
            'scatter_count'
        ];
        
        public static function boot()
        {
            parent::boot();
        }
    }

}

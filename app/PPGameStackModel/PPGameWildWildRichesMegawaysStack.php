<?php 
namespace VanguardLTE\PPGameStackModel;
{
    class PPGameWildWildRichesMegawaysStack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'ppgame_wildwildrichesmegaways_stack';
        protected $fillable = [
            'id', 
            'game_id', 
            'odd', 
            'spin_stack', 
            'spin_type', 
            'fsmax', 
            'pur_level'
        ];
        
        public static function boot()
        {
            parent::boot();
        }
    }

}

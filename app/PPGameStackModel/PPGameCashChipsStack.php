<?php 
namespace VanguardLTE\PPGameStackModel;
{
    class PPGameCashChipsStack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'ppgame_cashchips_stack';
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

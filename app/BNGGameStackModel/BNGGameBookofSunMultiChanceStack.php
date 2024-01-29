<?php 
namespace VanguardLTE\BNGGameStackModel;
{
    class BNGGameBookofSunMultiChanceStack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'bnggame_bookofsunmultichance_stack';
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

<?php 
namespace VanguardLTE\BNGGameStackModel;
{
    class BNGGameSunofEgypt2Stack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'bnggame_sunofegypt2_stack';
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

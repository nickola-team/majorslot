<?php 
namespace VanguardLTE\BNGGameStackModel;
{
    class BNGGameBookofSunStack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'bnggame_bookofsun_stack';
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

<?php 
namespace VanguardLTE\BNGGameStackModel;
{
    class BNGGameMagicBallStack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'bnggame_magicball_stack';
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

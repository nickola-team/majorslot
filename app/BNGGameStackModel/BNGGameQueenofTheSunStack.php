<?php 
namespace VanguardLTE\BNGGameStackModel;
{
    class BNGGameQueenofTheSunStack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'bnggame_queenofthesun_stack';
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

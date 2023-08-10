<?php 
namespace VanguardLTE\CQ9GameStackModel;
{
    class CQ9GameDragonBallStack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'cqgame_dragonball_stack';
        protected $fillable = [
            'id', 
            'game_id', 
            'odd', 
            'spin_stack', 
            'spin_type',         
            'pur_level',
            'free_count',
            'symbol_count'
        ];
        
        public static function boot()
        {
            parent::boot();
        }
    }

}

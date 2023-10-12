<?php 
namespace VanguardLTE\CQ9GameStackModel;
{
    class CQ9GameSixCandyStack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'cqgame_sixcandy_stack';
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

<?php 
namespace VanguardLTE\CQ9GameStackModel;
{
    class CQ9GameMagicWorldStack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'cqgame_magicworld_stack';
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

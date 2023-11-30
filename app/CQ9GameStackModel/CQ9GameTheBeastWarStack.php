<?php 
namespace VanguardLTE\CQ9GameStackModel;
{
    class CQ9GameTheBeastWarStack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'cqgame_thebeastwar_stack';
        protected $fillable = [
            'id', 
            'game_id', 
            'odd', 
            'spin_stack', 
            'spin_type',         
            'pur_level',
            'free_count',
            're_spin'
        ];
        
        public static function boot()
        {
            parent::boot();
        }
    }

}

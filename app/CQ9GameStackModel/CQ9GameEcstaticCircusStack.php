<?php 
namespace VanguardLTE\CQ9GameStackModel;
{
    class CQ9GameEcstaticCircusStack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'cqgame_estaticcircus_stack';
        protected $fillable = [
            'id', 
            'game_id', 
            'odd', 
            'spin_stack', 
            'spin_type',         
            'pur_level',
            'free_count'
        ];
        
        public static function boot()
        {
            parent::boot();
        }
    }

}

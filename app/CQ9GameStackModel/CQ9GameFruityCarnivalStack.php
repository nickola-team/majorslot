<?php 
namespace VanguardLTE\CQ9GameStackModel;
{
    class CQ9GameFruityCarnivalStack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'cqgame_fruitycarnival_stack';
        protected $fillable = [
            'id', 
            'game_id', 
            'odd', 
            'spin_stack', 
            'spin_type', 
            'pur_level',
            'symbol_count'
        ];
        
        public static function boot()
        {
            parent::boot();
        }
    }

}

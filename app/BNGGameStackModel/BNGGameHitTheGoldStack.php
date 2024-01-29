<?php 
namespace VanguardLTE\BNGGameStackModel;
{
    class BNGGameHitTheGoldStack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'bnggame_hitthegold_stack';
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

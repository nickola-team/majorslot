<?php 
namespace VanguardLTE\BNGGameStackModel;
{
    class BNGGameGrabMoreGoldStack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'bnggame_grabmoregold_stack';
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

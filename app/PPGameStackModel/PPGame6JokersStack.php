<?php 
namespace VanguardLTE\PPGameStackModel;
{
    class PPGame6JokersStack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'ppgame_6jokers_stack';
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

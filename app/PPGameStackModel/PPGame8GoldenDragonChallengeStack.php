<?php 
namespace VanguardLTE\PPGameStackModel;
{
    class PPGame8GoldenDragonChallengeStack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'ppgame_8goldendragonchallenge_stack';
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

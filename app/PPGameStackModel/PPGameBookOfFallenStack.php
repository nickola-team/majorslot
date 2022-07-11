<?php 
namespace VanguardLTE\PPGameStackModel;
{
    class PPGameBookOfFallenStack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'ppgame_bookoffallen_stack';
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

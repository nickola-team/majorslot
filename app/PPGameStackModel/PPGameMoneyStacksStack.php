<?php 
namespace VanguardLTE\PPGameStackModel;
{
    class PPGameMoneyStacksStack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'ppgame_moneystacks_stack';
        protected $fillable = [
            'id', 
            'game_id', 
            'odd', 
            'spin_stack', 
            'spin_type', 
            'pur_level', 
            'fsmax'
        ];
        
        public static function boot()
        {
            parent::boot();
        }
    }

}

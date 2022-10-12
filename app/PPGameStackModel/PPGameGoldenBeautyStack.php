<?php 
namespace VanguardLTE\PPGameStackModel;
{
    class PPGameGoldenBeautyStack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'ppgame_goldenbeauty_stack';
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

<?php 
namespace VanguardLTE\CQ9GameStackModel;
{
    class CQ9GameOrientalBeautyStack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'cqgame_orientalbeauty_stack';
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

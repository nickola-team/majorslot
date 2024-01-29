<?php 
namespace VanguardLTE\BNGGameStackModel;
{
    class BNGGameSuperRichGodStack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'bnggame_superrichgod_stack';
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

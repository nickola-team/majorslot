<?php 
namespace VanguardLTE\CQ9GameStackModel;
{
    class CQ9GameHeroofthe3KingdomsCaocaoStack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'cqgame_heroofthe3kingdomscaocao_stack';
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

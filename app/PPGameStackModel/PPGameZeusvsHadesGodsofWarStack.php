<?php 
namespace VanguardLTE\PPGameStackModel;
{
    class PPGameZeusvsHadesGodsofWarStack extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'ppgame_zeusvshadesgodsofwar_stack';
        protected $fillable = [
            'id', 
            'game_id', 
            'odd', 
            'spin_stack', 
            'spin_type', 
            'ind', 
            'pur_level'
        ];
        
        public static function boot()
        {
            parent::boot();
        }
    }

}

<?php 
namespace VanguardLTE
{
    class BonusBank extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'bonus_bank';
        protected $fillable = [
            'master_id', 
            'bank', 
            'game_id',
            'max_bank'
        ];
        public static function boot()
        {
            parent::boot();
        }

        public function master()
        {
            return $this->belongsTo('VanguardLTE\User', 'master_id');
        }
        public function game()
        {
            return $this->belongsTo('VanguardLTE\Game', 'game_id');
        }
    }

}

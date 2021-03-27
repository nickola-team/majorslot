<?php 
namespace VanguardLTE
{
    class PPBonus extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'ppbonus';
        protected $fillable = [
            'playerId', 
            'rounds', 
            'bonusCode',
            'gameIDList',
            'startDate',
            'expirationDate'
        ];
        public $timestamps = false;
        public static function boot()
        {
            parent::boot();
        }

        public function user()
        {
            return $this->belongsTo('VanguardLTE\User', 'playerId');
        }
    }
}

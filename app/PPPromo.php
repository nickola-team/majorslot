<?php 
namespace VanguardLTE
{
    class PPPromo extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'pppromo';
        protected $fillable = [
            'active', 
            'racedetails', 
            'raceprizes',
            'racewinners',
            'tournamentdetails',
            'tournamentleaderboard',
            'games',
            'multiminilobby'
        ];
        public $timestamps = false;
        public static function boot()
        {
            parent::boot();
        }
    }
}

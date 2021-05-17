<?php 
namespace VanguardLTE
{
    class BonusBank extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'bonus_bank';
        protected $fillable = [
            'master_id', 
            'bank', 
            
        ];
        public static function boot()
        {
            parent::boot();
        }
    }

}

<?php 
namespace VanguardLTE
{
    class PNGTransaction extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'pngtransaction';
        protected $fillable = [
            'transactionId', 
            'timestamp', 
            'data',
        ];
        public $timestamps = false;
        public static function boot()
        {
            parent::boot();
        }
    }
}

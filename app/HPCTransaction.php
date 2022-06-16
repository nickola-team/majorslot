<?php 
namespace VanguardLTE
{
    class HPCTransaction extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'hpctransaction';
        protected $fillable = [
            'reference', 
            'timestamp', 
            'data',
            'refund'
        ];
        public $timestamps = false;
        public static function boot()
        {
            parent::boot();
        }
    }
}

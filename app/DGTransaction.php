<?php 
namespace VanguardLTE
{
    class DGTransaction extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'dgtransaction';
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

<?php 
namespace VanguardLTE
{
    class HoldemTransaction extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'holdemtransaction';
        protected $fillable = [
            'id',
            'user_id', 
            'data', 
            'gamecode',            
            'eventtype',
            'eventmoney',
            'token',   
            'bet',         
            'win', //win amount
            'balance', //user balance  
            'safebalance',
            'error_code',            
            'stats'  //1이면 10일후에 삭제,0이면 삭제하지 않음
        ];
        public $timestamps = false;
        public static function boot()
        {
            parent::boot();
        }

        public function user()
        {
            return $this->belongsTo('VanguardLTE\User', 'user_id');
        }
    }
}

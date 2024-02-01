<?php 
namespace VanguardLTE
{
    class BTiTransaction extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'btitransaction';
        protected $fillable = [
            'id',
            'user_id', 
            'reserve_id', 
            'amount', //bet amount
            'balance', //user balance  
            'data',   
            'req_id',    
            'error_code',
            'error_message',
            'status',
            'bet_type_id',
            'bet_type_name',
            'purchase_id',
            'stats'
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

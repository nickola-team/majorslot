<?php 
namespace VanguardLTE
{
    class ShopStat extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'shops_stat';
        protected $fillable = [
            'shop_id', 
            'user_id', 
            'type', 
            'sum', 
            'old',
            'new',
            'balance',
            'date_time',
            'request_id',
            'reason'
        ];
        public $timestamps = false;
        public function shop()
        {
            return $this->belongsTo('VanguardLTE\Shop', 'shop_id');
        }
        public function user()
        {
            return $this->belongsTo('VanguardLTE\User', 'user_id');
        }
        public static function boot()
        {
            parent::boot();
            /*self::created(function($model)
            {
                try
                {
                    \Illuminate\Support\Facades\Redis::publish('Lives', json_encode([
                        'event' => 'NewLive', 
                        'data' => [
                            'type' => 'ShopStat', 
                            'Name' => $model->shop->name, 
                            'Old' => $model->old, 
                            'New' => $model->new, 
                            'Game' => '', 
                            'User' => $model->user->username, 
                            'System' => '', 
                            'Sum' => number_format($model->sum, 0, '.', ''), 
                            'In' => ($model->type == 'add' ?number_format($model->sum,0) : ''), 
                            'Out' => ($model->type != 'add' ? number_format($model->sum,0) : ''), 
                            'Balance' => '', 
                            'Bet' => '', 
                            'Win' => '', 
                            'IN_GAME' => '', 
                            'IN_JPS' => '', 
                            'IN_JPG' => '', 
                            'Profit' => '', 
                            'user_id' => \Auth::id(), 
                            'shop_id' => $model->shop_id, 
                            'Date' => date(config('app.date_time_format')), 
                            'domain' => request()->getHost()
                        ]
                    ]));
                }
                catch( \Predis\Connection\ConnectionException $e ) 
                {
                }
            });*/
        }

        public function requestInfo()
        {
            return $this->hasOne('VanguardLTE\WithdrawDeposit', 'id', 'request_id');
        }
    }
    

}

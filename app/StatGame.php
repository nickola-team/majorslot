<?php 
namespace VanguardLTE
{
    class StatGame extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'stat_game';
        protected $fillable = [
            'date_time', 
            'user_id', 
            'balance', 
            'bet', 
            'win', 
            'game', 
            'type', 
            'denomination', 
            'percent', 
            'percent_jps', 
            'percent_jpg', 
            'profit', 
            'game_bank', 
            'jack_balance', 
            'shop_id'
        ];
        public $timestamps = false;
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
                            'type' => 'StatGame', 
                            'Name' => '', 
                            'Old' => '', 
                            'New' => '', 
                            'Game' => $model->game, 
                            'User' => $model->user->username, 
                            'System' => '', 
                            'Sum' => '', 
                            'In' => '', 
                            'Out' => '', 
                            'Balance' => number_format($model->balance, 0, '.', ''), 
                            'Bet' => number_format($model->bet, 0, '.', ''), 
                            'Win' => number_format($model->win, 0, '.', ''), 
                            'IN_GAME' => number_format($model->percent, 0, '.', ''), 
                            'IN_JPS' => number_format($model->percent_jps, 0, '.', ''), 
                            'IN_JPG' => number_format($model->percent_jpg, 0, '.', ''), 
                            'Profit' => number_format($model->profit, 0, '.', ''), 
                            'user_id' => $model->user->id, 
                            'shop_id' => $model->user->shop_id, 
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
        public function user()
        {
            return $this->belongsTo('VanguardLTE\User', 'user_id');
        }
        public function shop()
        {
            return $this->belongsTo('VanguardLTE\Shop');
        }
        public function game_item()
        {
            return $this->hasOne('VanguardLTE\Game', 'name', 'game');
        }
        public function name_ico()
        {
            return explode(' ', $this->game)[0];
        }

        public static function create(array $attributes = [])
        {
            $filterGames = [' FG', ' FG1', ' respin', ' RS', ' doBonus'];
            foreach($filterGames as $ignoreGame) 
            {
                if (substr_compare($attributes['game'], $ignoreGame, -strlen($ignoreGame)) === 0)
                {
                    $attributes['bet'] = 0;
                }
            }

            $model = static::query()->create($attributes);
            $filterGames = [' FG', ' FG1', ' respin', ' RS', ' JP', ' debit', ' credit', ' refund', ' payoff', ' RB', ' recredit'];
            /*foreach($filterGames as $ignoreGame) 
            {
                if (substr_compare($model->game, $ignoreGame, -strlen($ignoreGame)) === 0)
                {
                    return $model;
                }
            } */
            if ($model->bet > 0 || $model->win > 0) {
                $user = \VanguardLTE\User::where('id',$model->user_id)->first();
                $deal_method = env('DEAL_PROCESS', 'direct');
                /*if ($deal_method == 'direct')
                {
                    // we did not implement ggr profit part.
                    //please use queue mode for ggr profit.
                    $user->processBetDealerMoney($model->bet, $model->game, $model->type);
                }
                else if ($deal_method == 'queue')
                {*/
                    $user->processBetDealerMoney_Queue($model->bet, $model->win, $model->game, $model->date_time, $model->type);
                //}
            }
            return $model;
        }
    }

}

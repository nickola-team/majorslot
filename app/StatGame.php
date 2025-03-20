<?php
namespace VanguardLTE
{
    use Log;
    use VanguardLTE\Http\Controllers\Web\Frontend\CallbackController;

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
            'bet_type',
            'denomination',
            'percent',
            'percent_jps',
            'percent_jpg',
            'profit',
            'shop_id',
            'category_id',
            'game_id',
            'roundid',
            'status'
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
        public function category()
        {
            return $this->belongsTo('VanguardLTE\Category', 'category_id');
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
            return $this->hasOne('VanguardLTE\Game', 'id', 'game_id');
        }
        public function name_ico()
        {
            return explode(' ', $this->game)[0];
        }

        public static function create(array $attributes = [])
        {
            $gamecode = '';
            if (empty($attributes['category_id']) || empty($attributes['game_id']))
            {
                //search manually category_id and game_id
                $real_game = explode(' ', $attributes['game']);
                $game = \VanguardLTE\Game::where(['name' => $real_game[0], 'shop_id' => 0])->first();
                if ($game)
                {
                    $attributes['game_id'] = $game->id;
                    $category = $game->categories->first();
                    if ($category)
                    {
                        $attributes['category_id'] = $category->category_id;
                    }
                    $gamecode = $game->label;
                }
            }

            $model = static::query()->create($attributes);

            $user = \VanguardLTE\User::where('id',$model->user_id)->first();

            if (($model->type != 'table' || $model->bet != $model->win) && $user) // if live game and tie, then don't process deal
            {
                $user->processBetDealerMoney_Queue($model);
            }

            $parent = $user->findAgent();

            if($parent->callback) {
                $username = explode("#P#", $user->username)[1];

                $transaction = [
                    'roundid' => $model->id,
                    'datetime' => $model->date_time,
                    'username' => $username,
                    'bet' => $attributes['bet'],
                    'win' => $attributes['win'],
                    'type' => $attributes['bet_type'],
                    'balance' => $attributes['balance'],
                    'gamecode' => $attributes['game_id'],
                    'gamehall' => $attributes['category_id'],
                ];
    
                $response = CallbackController::setTransaction($parent->callback, $transaction);

                if($response['status'] == 1) {
                    $user->balance = $response['balance'];
                    $user->save();

                    $model->balance = $response['balance'];
                    $model->save();

                    if (!$parent) {
                        $newParentBalance = $parent->balance - $attributes['bet'] + $attributes['win'];

                        if($newParentBalance < 0) {
                            return [
                                'status' => 0,
                                'msg' => "operator's balance is not enough",
                            ];
                        }

                        $parent->balance = $newParentBalance;
                        $parent->save();
                    }

                    return [
                        'status' => 1,
                        'balance' => $response['balance'],
                        'beforeBalance' => $response['beforeBalance'],
                    ];
                }

                return [
                    'status' => 0,
                    'msg' => $response['msg'],
                ];
            }

            if($model->category_id == 14) {
                \VanguardLTE\PPGameVerifyLog::updateOrCreate([
                    'user_id'=>$model->user_id,
                    'game_id'=>$model->game_id
                ],[
                    'game_id' => $model->game_id,
                    'user_id' => $model->user_id,
                    'bet' => $model->bet,
                    'label' => $gamecode,
                    'crid' => '',
                    'rid' => $model->roundid
                ]);
            }

            return [
                'status' => 1,
                'balance' => $user->balance,
                'beforeBalance' => $user->balance - $attributes['win'] + $attributes['bet'],
            ];
        }
    }

}

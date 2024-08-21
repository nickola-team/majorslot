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
            // $filterGames = [' FG', ' FG1', ' respin', ' RS', ' doBonus'];
            // foreach($filterGames as $ignoreGame) 
            // {
            //     if (strlen($attributes['game']) >= strlen($ignoreGame) && substr_compare($attributes['game'], $ignoreGame, -strlen($ignoreGame)) === 0)
            //     {
            //         $attributes['bet'] = 0;
            //     }
            // }
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
                else
                {
                    
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
            if (($model->type == 'table') && ($model->bet==$model->win)) // if live game and tie, then don't process deal
            {
                return $model;
            }
            // if ($model->bet > 0) {
                $user = \VanguardLTE\User::where('id',$model->user_id)->first();
                if ($user){
                    $user->processBetDealerMoney_Queue($model);
                }
                if($model->category_id == 14)
                {
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
                    // $ppverify = \VanguardLTE\PPGameVerifyLog::where(['user_id'=>$model->user_id, 'game_id'=>$model->game_id])->first();
                    // if(isset($ppverify))
                    // {
                    //     // $ppverify->update(['bet'=>$model->bet, 'rid'=>$model->roundid, 'crid'=>'']);
                    //     $ppverify->bet = $model->bet;
                    //     $ppverify->rid = $model->roundid;
                    //     $ppverify->crid = '1';
                    //     $ppverify->save();
                    // }
                    // else
                    // {
                    //     \VanguardLTE\PPGameVerifyLog::create([
                    //         'game_id' => $model->game_id, 
                    //         'user_id' => $model->user_id, 
                    //         'bet' => $model->bet, 
                    //         'label' => $gamecode,
                    //         'rid' => $model->roundid
                    //     ]);
                    // }
                }
            // }
            return $model;
        }
    }

}

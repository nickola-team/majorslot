<?php 
namespace VanguardLTE\Console
{
    use GuzzleHttp\Client;
    use GuzzleHttp\Exception\RequestException;
    use GuzzleHttp\Pool;
    use GuzzleHttp\Psr7\Request;
    use GuzzleHttp\Psr7\Response;
    use \VanguardLTE\Http\Controllers\Web\GameProviders\PPController;
    class Kernel extends \Illuminate\Foundation\Console\Kernel
    {
        protected $commands = [];
        protected function schedule(\Illuminate\Console\Scheduling\Schedule $schedule)
        {
            //$schedule->command('queue:work --daemon')->everyMinute()->withoutOverlapping();
            $schedule->call(function () {
                \Illuminate\Support\Facades\Redis::del('pplist');
                \Illuminate\Support\Facades\Redis::del('livelist');
                \Illuminate\Support\Facades\Redis::del('hbnlist');
                \Illuminate\Support\Facades\Redis::del('booongolist');
                \Illuminate\Support\Facades\Redis::del('playsonlist');
                \Illuminate\Support\Facades\Redis::del('cq9list');
                \Illuminate\Support\Facades\Redis::del('playngolist');

                set_time_limit(0);
                $_daytime = strtotime("-1 days") * 10000;
                
                \VanguardLTE\PPTransaction::where('timestamp', '<', $_daytime)->delete();
                \VanguardLTE\HBNTransaction::where('timestamp', '<', $_daytime)->delete();
                \VanguardLTE\BNGTransaction::where('timestamp', '<', $_daytime)->delete();
                \VanguardLTE\CQ9Transaction::where('timestamp', '<', $_daytime)->delete();
                \VanguardLTE\PNGTransaction::where('timestamp', '<', $_daytime)->delete();

                $start_date = date("Y-m-d H:i:s",strtotime("-7 days"));
                \VanguardLTE\GameLog::where('time', '<', $start_date)->delete();

                $start_date = date("Y-m-d H:i:s",strtotime("-10 days"));

                \VanguardLTE\StatGame::where('date_time', '<', $start_date)->delete();
                
                $start_date = date("Y-m-d H:i:s",strtotime("-3 days"));
                \VanguardLTE\DealLog::where('date_time', '<', $start_date)->delete();

                $start_date = date("Y-m-d H:i:s",strtotime("-90 days"));
                \VanguardLTE\ShopStat::where('date_time', '<', $start_date)->delete();
                \VanguardLTE\Transaction::where('created_at', '<', $start_date)->delete();
                \VanguardLTE\WithdrawDeposit::where('created_at', '<', $start_date)->delete();

                \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::syncpromo();

            })->dailyAt('08:00');

            $schedule->command('daily:reset_ggr')->dailyAt('00:00')->runInBackground();
            $schedule->command('daily:summary')->dailyAt('08:10')->runInBackground();
            $schedule->command('daily:gamesummary')->dailyAt('08:30')->runInBackground();

            $schedule->command('monthly:summary')->monthlyOn(1, '9:00')->runInBackground();

            $schedule->command('today:summary')->everyTenMinutes()->withoutOverlapping()->runInBackground();
            $schedule->command('today:gamesummary')->everyTenMinutes()->withoutOverlapping()->runInBackground();
            
            if (env('SWITCH_PP', false) == true){
                $schedule->command('daily:ppgames')->cron('15 */2 * * *');
            }

            $schedule->call(function()
            {
                $start_date = date("Y-m-d H:i:s",strtotime("-12 hours"));
                \VanguardLTE\GameLaunch::where('created_at', '<', $start_date)->where('finished', 1)->delete();
            })->hourly();

            $schedule->call(function()
            {
                \VanguardLTE\Session::where('user_id', 'NULL')->delete();
                \VanguardLTE\Session::where('user_id', '')->delete();
                \VanguardLTE\Task::where('finished', 1)->delete();
            })->everyMinute();
            $schedule->call(function()
            {
                $task = \VanguardLTE\Task::where([
                    'finished' => 0, 
                    'category' => 'shop', 
                    'action' => 'create'
                ])->first();
                if( $task ) 
                {
                    $task->update(['finished' => 1]);
                    $shop = \VanguardLTE\Shop::find($task->item_id);
                    if (!$shop)
                    {
                        return;
                    }
                    $shopCategories = \VanguardLTE\ShopCategory::where('shop_id', $shop->id)->get();
                    if( count($shopCategories) ) 
                    {
                        $shopCategories = $shopCategories->pluck('category_id')->toArray();
                    }
                    $superCategories = [];
                    $categories = \VanguardLTE\Category::where([
                        'shop_id' => 0, 
                        'parent' => 0
                    ])->get();
                    if( count($categories) ) 
                    {
                        foreach( $categories as $category ) 
                        {
                            $newCategory = $category->replicate();
                            $newCategory->shop_id = $shop->id;
                            $newCategory->save();
                            $superCategories[$category->id] = $newCategory->id;
                            $categories_2 = \VanguardLTE\Category::where([
                                'shop_id' => 0, 
                                'parent' => $category->id
                            ])->get();
                            if( count($categories_2) ) 
                            {
                                foreach( $categories_2 as $category_2 ) 
                                {
                                    $newCategory_2 = $category_2->replicate();
                                    $newCategory_2->shop_id = $shop->id;
                                    $newCategory_2->parent = $newCategory->id;
                                    $newCategory_2->save();
                                    $superCategories[$category_2->id] = $newCategory_2->id;
                                }
                            }
                        }
                    }
                    $returns = \VanguardLTE\Returns::where('shop_id', 0)->get();
                    if( count($returns) ) 
                    {
                        foreach( $returns as $return ) 
                        {
                            $newReturn = $return->replicate();
                            $newReturn->shop_id = $shop->id;
                            $newReturn->save();
                        }
                    }
                    $jackpots = \VanguardLTE\JPG::where('shop_id', 0)->get();
                    if( count($jackpots) ) 
                    {
                        foreach( $jackpots as $jackpot ) 
                        {
                            $newJackpot = $jackpot->replicate();
                            $newJackpot->shop_id = $shop->id;
                            $newJackpot->save();
                        }
                    }

                    $bank = \VanguardLTE\GameBank::where('shop_id', 0)->first();
                    if( $bank ) 
                    {
                        $newBank = $bank->replicate();
                        $newBank->shop_id = $shop->id;
                        $newBank->save();
                    }
                    $game_ids = [];
                    if( count($shopCategories) ) 
                    {
                        $categories = \VanguardLTE\Category::whereIn('parent', $shopCategories)->where('shop_id', 0)->pluck('id')->toArray();
                        $categories = array_merge($categories, $shopCategories);
                        $game_ids = \VanguardLTE\GameCategory::whereIn('category_id', $categories)->groupBy('game_id')->pluck('game_id')->toArray();
                    }
                    if( count($game_ids) ) 
                    {
                        $games = \VanguardLTE\Game::where('shop_id', 0)->whereIn('id', $game_ids)->get();
                    }
                    else
                    {
                        $games = \VanguardLTE\Game::where('shop_id', 0)->get();
                    }
                    if( count($games) ) 
                    {
                        foreach( $games as $game ) 
                        {
                            $newGame = $game->replicate();
                            $newGame->original_id = $game->id;
                            $newGame->shop_id = $shop->id;
                            $newGame->save();
                            $categories = \VanguardLTE\GameCategory::where('game_id', $game->id)->get();
                            if( count($categories) && count($superCategories) ) 
                            {
                                foreach( $categories as $category ) 
                                {
                                    $newCategory = $category->replicate();
                                    $newCategory->game_id = $newGame->id;
                                    $newCategory->category_id = $superCategories[$category->category_id];
                                    $newCategory->save();
                                }
                            }
                        }
                    }
                    $shop->update(['pending' => 0]);
                }
            })->everyMinute();
            $schedule->call(function()
            {
                $date_time = date('Y-m-d H:i:s', strtotime("-1 days"));
                $task = \VanguardLTE\Task::where([
                    'finished' => 0, 
                    'category' => 'user', 
                    'action' => 'delete'
                ])->where('created_at', '<=', $date_time)->first();
                if( $task ) 
                {
                    $task->update(['finished' => 1]);
                    $user = \VanguardLTE\User::find($task->item_id);
                    if ($user){
                        $user->detachAllRoles();
                        //\VanguardLTE\Transaction::where('user_id', $user->id)->delete();
                        \VanguardLTE\ShopUser::where('user_id', $user->id)->delete();
                        //\VanguardLTE\StatGame::where('user_id', $user->id)->delete();
                        \VanguardLTE\GameLog::where('user_id', $user->id)->delete();
                        \VanguardLTE\UserActivity::where('user_id', $user->id)->delete();
                        \VanguardLTE\Session::where('user_id', $user->id)->delete();
                        \VanguardLTE\Info::where('user_id', $user->id)->delete();
                        $user->delete();
                    }

                }
            })->everyMinute();
            $schedule->call(function()
            {
                $date_time = date('Y-m-d H:i:s', strtotime("-1 days"));
                $task = \VanguardLTE\Task::where([
                    'finished' => 0, 
                    'category' => 'shop', 
                    'action' => 'delete'
                ])->where('created_at', '<=', $date_time)->first();
                if( $task ) 
                {
                    $task->update(['finished' => 1]);
                    $shopId = $task->item_id;
                    $shopInfo = \VanguardLTE\Shop::find($shopId);
                    if ($shopInfo)
                    {
                        $shopInfo->delete();
                    }
                    $rel_users = \VanguardLTE\User::whereHas('rel_shops', function($query) use ($shopId)
                    {
                        $query->where('shop_id', $shopId);
                    })->get();
                    $toDelete = \VanguardLTE\User::whereIn('role_id', [
                        1, 
                        2, 
                        3
                    ])->where('shop_id', $shopId)->get();
                    if( $toDelete ) 
                    {
                        foreach( $toDelete as $userDelete ) 
                        {
                            $userDelete->detachAllRoles();
                            $userDelete->delete();
                        }
                    }
                    $games = \VanguardLTE\Game::where('shop_id', $shopId)->get();
                    foreach( $games as $game ) 
                    {
                        \VanguardLTE\Game::where('id', $game->id)->delete();
                        \VanguardLTE\GameCategory::where('game_id', $game->id)->delete();
                        \VanguardLTE\GameLog::where('game_id', $game->id)->delete();
                    }
                    //\VanguardLTE\Transaction::where('shop_id', $shopId)->delete();
                    //\VanguardLTE\StatGame::where('shop_id', $shopId)->delete();
                    \VanguardLTE\Category::where('shop_id', $shopId)->delete();
                    \VanguardLTE\Returns::where('shop_id', $shopId)->delete();
                    \VanguardLTE\OpenShift::where('shop_id', $shopId)->delete();
                    //\VanguardLTE\ShopStat::where('shop_id', $shopId)->delete();
                    \VanguardLTE\ShopUser::where('shop_id', $shopId)->delete();
                    \VanguardLTE\BankStat::where('shop_id', $shopId)->delete();
                    \VanguardLTE\Api::where('shop_id', $shopId)->delete();
                    \VanguardLTE\ShopCategory::where('shop_id', $shopId)->delete();
                    \VanguardLTE\JPG::where('shop_id', $shopId)->delete();
                    \VanguardLTE\InfoShop::where('shop_id', $shopId)->delete();
                    \VanguardLTE\Pincode::where('shop_id', $shopId)->delete();
                    \VanguardLTE\Returns::where('shop_id', $shopId)->delete();
                    \VanguardLTE\HappyHour::where('shop_id', $shopId)->delete();
                    \VanguardLTE\GameBank::where('shop_id', $shopId)->delete();
                    if( $rel_users ) 
                    {
                        foreach( $rel_users as $user ) 
                        {
                            if( $user->hasRole([
                                'comaster',
                                'master',
                                'agent', 
                                'distributor'
                            ]) ) 
                            {
                                $user->update(['shop_id' => 0]);
                            }
                        }
                    }
                }
            })->everyMinute();
            $schedule->call(function()
            {
                $tasks = \VanguardLTE\Task::where([
                    'finished' => 0, 
                    'category' => 'game', 
                    'action' => 'delete'
                ])->take(10)->get();
                if( count($tasks) ) 
                {
                    foreach( $tasks as $task ) 
                    {
                        $task->update(['finished' => 1]);
                        \VanguardLTE\GameCategory::where('game_id', $task->item_id)->delete();
                        \VanguardLTE\GameLog::where('game_id', $task->item_id)->delete();
                        \VanguardLTE\Game::where('id', $task->item_id)->delete();
                    }
                }
            })->everyMinute();
            $schedule->call(function()
            {
                $tasks = \VanguardLTE\Task::where([
                    'finished' => 0, 
                    'category' => 'event', 
                    'action' => 'GameEdited'
                ])->take(1)->get();
                if( count($tasks) ) 
                {
                    foreach( $tasks as $task ) 
                    {
                        $task->update(['finished' => 1]);
                        $games = explode(',', $task->item_id);
                        if( count($games) ) 
                        {
                            $games = \VanguardLTE\Game::whereIn('id', $games)->get();
                            foreach( $games as $game ) 
                            {
                                \VanguardLTE\Services\Logging\UserActivity\Activity::create([
                                    'description' => 'Update Game / ' . $game->name . ' / ' . $task->details, 
                                    'user_id' => $task->user_id, 
                                    'ip_address' => $task->ip_address, 
                                    'user_agent' => $task->user_agent
                                ]);
                            }
                        }
                    }
                }
            })->everyMinute();

            
        }
        protected function commands()
        {
            require(base_path('routes/console.php'));

            \Artisan::command('daily:gamesummary {date=today}', function ($date) {
                $this->info("Begin daily game summary adjustment.");

                $admins = \VanguardLTE\User::where('role_id',8)->get();
                foreach ($admins as $admin)
                {
                    if ($date == 'today') {
                        \VanguardLTE\CategorySummary::summary($admin->id);
                    }
                    else{
                        \VanguardLTE\CategorySummary::summary($admin->id, $date);
                    }
                }
                if ($date == 'today') {
                    $day = date("Y-m-d", strtotime("-1 days"));
                    \VanguardLTE\CategorySummary::where(['type' => 'today', 'date' => $day])->delete();
                    \VanguardLTE\GameSummary::where(['type' => 'today', 'date' => $day])->delete();
                }
                $this->info("End daily game summary adjustment.");
            });
            \Artisan::command('today:gamesummary', function () {
                set_time_limit(0);
                $this->info("Begin today's game adjustment.");

                $admins = \VanguardLTE\User::where('role_id',8)->get();
                foreach ($admins as $admin)
                {
                    \VanguardLTE\CategorySummary::summary_today($admin->id);
                }
                $this->info("End today's game adjustment.");
            });

            \Artisan::command('daily:summary {date=today}', function ($date) {
                set_time_limit(0);
                $this->info("Begin summary daily adjustment.");

                $admins = \VanguardLTE\User::where('role_id',8)->get();
                foreach ($admins as $admin)
                {
                    if ($date == 'today') {
                        \VanguardLTE\DailySummary::summary($admin->id);
                    }
                    else{
                        \VanguardLTE\DailySummary::summary($admin->id, $date);
                    }
                }
                if ($date == 'today') {
                    $day = date("Y-m-d", strtotime("-1 days"));
                    \VanguardLTE\DailySummary::where(['type' => 'today', 'date' => $day])->delete();
                }
                $this->info("End summary daily adjustment.");
            });

            \Artisan::command('today:summary', function () {
                set_time_limit(0);
                $this->info("Begin today's adjustment.");

                $admins = \VanguardLTE\User::where('role_id',8)->get();
                foreach ($admins as $admin)
                {
                    \VanguardLTE\DailySummary::summary_today($admin->id);
                }
                $this->info("End today's adjustment.");
            });

            \Artisan::command('monthly:summary {month=today}', function ($month) {
                set_time_limit(0);
                $this->info("Begin summary monthly adjustment.");

                $admins = \VanguardLTE\User::where('role_id',8)->get();
                foreach ($admins as $admin)
                {
                    if ($month == 'today') {
                        \VanguardLTE\DailySummary::summary_month($admin->id);
                    }
                    else{
                        \VanguardLTE\DailySummary::summary_month($admin->id, $month);
                    }
                }
                $this->info("End summary monthly adjustment.");
            });

            \Artisan::command('daily:promo', function () {
                set_time_limit(0);
                $this->info("Begin pp game promotions");
                $res = \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::syncpromo();
                $this->info($res['msg']);
            });

            \Artisan::command('daily:newgame {originalid}', function ($originalid) {
                set_time_limit(0);
                $this->info("Begin adding new game to all shop");
                
                $buffgame = \VanguardLTE\Game::where('id', $originalid)->first();
                if (!$buffgame)
                {
                    $this->error('Can not find original game of new game');
                    return;
                }
                $shop_ids = \VanguardLTE\Shop::all()->pluck('id')->toArray();
                $data = $buffgame->toArray();
                foreach ($shop_ids as $id)
                {
                    if (\VanguardLTE\Game::where(['shop_id'=> $id, 'original_id' => $originalid])->first())
                    {
                        $this->info("Game already exist in " . $id . " shop");
                    }
                    else{
                        $data['shop_id'] = $id;
                        $game = \VanguardLTE\Game::create($data);
                        $ppcat = \VanguardLTE\Category::where(['shop_id' => $id, 'href' => 'pragmatic'])->first();
                        if ($ppcat){
                            \VanguardLTE\GameCategory::create(['game_id'=>$game->id, 'category_id'=>$ppcat->id]);
                        }
                    }
                }
                $this->info('End');
            });

            \Artisan::command('launch:makeurl', function () {
                $launchRequests = \VanguardLTE\GameLaunch::where('finished', 0)->orderby('created_at', 'asc')->get();
                $processed_users = [];
                foreach ($launchRequests as $request)
                {
                    if (in_array($request->user_id, $processed_users)) //process 1 request per one user
                    {
                        $this->info('skipping userid=' . $request->user_id . ', id=' . $request->id);
                        continue;
                    }
                    if ($request->provider == 'pp')
                    {
                        $url = PPController::makelink($request->gamecode, $request->user_id);
                        if ($url != null)
                        {
                            $request->update([
                                'launchUrl' => $url,
                                'finished' => 1,
                            ]);
                            $processed_users[] = $request->user_id;
                        }
                    }
                }

            });


            \Artisan::command('pp:gameround {debug=0}', function ($debug) {
                $data = PPController::processGameRound('RNG');
                $this->info('saved ' . $data[0] . ' RNG bet/win record.');
                $this->info('new RNG timepoint = ' . $data[1]);
                $data = PPController::processGameRound('LC');
                $this->info('saved ' . $data[0] . ' LC bet/win record.');
                $this->info('new LC timepoint = ' . $data[1]);
            });

            \Artisan::command('pp:syncbalance {debug=1}', function ($debug) {
                $pp_users = \VanguardLTE\User::where('playing_game','pp')->get()->toArray();
                $pp_playing_users = [];
                foreach ($pp_users as $user) {
                    if ( time() - $user['played_at'] > 300) //5min
                    {
                        PPController::terminate($user['id']);
                        \VanguardLTE\User::lockforUpdate()->where('id',$user['id'])->update(['playing_game' => null]);
                    }
                    else
                    {
                        $pp_playing_users[] = $user;
                    }
                }

                $client = new Client();

                $requests = function () use ($pp_playing_users) {
                    foreach ($pp_playing_users as $user) {
                        $data = [
                            'secureLogin' => config('app.ppsecurelogin'),
                            'externalPlayerId' => $user['id'],
                        ];
                        $data['hash'] = PPController::calcHash($data);
                        yield new Request('POST', config('app.ppapi') . '/http/CasinoGameAPI/balance/current/' , 
                                                ['Content-Type' => 'application/x-www-form-urlencoded'],
                                                http_build_query($data, null, '&'));
                    }
                };

                $synccount = 0;
                $failedcount = 0;

                $pool = new Pool($client, $requests(), [
                    'fulfilled' => function (Response $response, $index) use ($pp_playing_users, &$synccount, &$failedcount, $debug) {
                        // this is delivered each successful response
                        $data = json_decode($response->getBody(), true);
                        $user = $pp_playing_users[$index];
                        if ($data['error'] == 0){
                            if ($debug == 1) {
                                $this->info('sync id = ' . $user['id'] . ', old balance = ' . $user['balance'] .  ', pp balance = ' . $data['balance']);
                            }
                            if (floatval($user['balance']) !=  floatval($data['balance']) )
                            {
                                \VanguardLTE\User::lockforUpdate()->where('id',$user['id'])->update(['balance' => $data['balance'], 'played_at' => time()]);
                            }
                            $synccount = $synccount + 1;
                        }
                        else
                        {
                            if ($debug == 1) {
                                $this->info('failed id = ' . $user['id'] . ', old balance = ' . $user['balance'] .  ', pp return code = ' . $data['error']);
                            }
                            $failedcount = $failedcount + 1;
                        }                       
                    },
                    'rejected' => function ($reason, $index) use ($pp_playing_users, &$failedcount, $debug){
                        $user = $pp_playing_users[$index];
                        if ($debug == 1) {
                            $this->info('failed id = ' . $user['id'] . ', old balance = ' . $user['balance'] );
                        }
                        $failedcount = $failedcount + 1;
                    },
                ]);

                // Initiate the transfers and create a promise
                $promise = $pool->promise();

                // Force the pool of requests to complete.
                $promise->wait();

                $this->info('Synchronized ' . $synccount .  ' users, failed ' . $failedcount . ' users.');

            });

            \Artisan::command('daily:newcategory {originalid}', function ($originalid) {
                set_time_limit(0);
                $this->info("Begin adding new category to all shop");
                
                $cat = \VanguardLTE\Category::where('id', $originalid)->first();
                if (!$cat)
                {
                    $this->error('Can not find original id of new category');
                    return;
                }
                $shop_ids = \VanguardLTE\Shop::all()->pluck('id')->toArray();
                $data = $cat->toArray();
                foreach ($shop_ids as $id)
                {
                    if (\VanguardLTE\Category::where(['shop_id'=> $id, 'href' => $cat->href])->first())
                    {
                        $this->info("Category already exist in " . $id . " shop");
                    }
                    else{
                        $data['shop_id'] = $id;
                        $shop_cat = \VanguardLTE\Category::create($data);
                    }
                }
                $this->info('End');
            });

            \Artisan::command('daily:ppgames', function () {
                $this->info("Begin pp game config");
                $path=base_path('.env');
                $oldValue=env('PP_GAMES');
                $newValue = $oldValue==1?0:1;
                if (file_exists($path))
                {
                    file_put_contents($path, str_replace('PP_GAMES='.$oldValue, 'PP_GAMES='.$newValue, file_get_contents($path)));
                }
                $this->info('End');
            });

            \Artisan::command('daily:dealsum {from} {to} {game=all}', function ($from, $to, $game) {
                set_time_limit(0);                
                $this->info("Begin deal calculation");
                if ($game=='all'){
                    $stat_games = \VanguardLTE\StatGame::where('date_time','>=',$from)->where('date_time','<=',$to)->where('bet','>', 0)->get();
                }
                else
                {
                    $stat_games = \VanguardLTE\StatGame::groupby('user_id')->where('date_time','>=',$from)->where('date_time','<=',$to)->where('bet','>', 0)->where('game', $game)->selectRaw('SUM(bet) as bet, game, type, user_id')->get();
                }
                $this->info("total bet = " . $stat_games->sum('bet') . ', count=' . $stat_games->count());
                foreach ($stat_games as $stat)
                {
                    usleep(10);
                    $user = \VanguardLTE\User::where('id',$stat->user_id)->first();
                    if ($game=='all')
                    {
                        $user->processBetDealerMoney_Queue($stat->bet, $stat->game, $stat->type);
                    }
                    else
                    {
                        $betMoney = $stat->bet;
                        $shop = $user->shop;
                        $deal_balance = 0;
                        $deal_mileage = 0;
                        $deal_percent = 0;
                        $deal_data = [];
                        $deal_percent = $shop->deal_percent - $shop->table_deal_percent;
                        $manager = $user->referral;
                        if ($manager != null){
                            if($deal_percent > 0) {
                                $deal_balance = $betMoney * $deal_percent  / 100;
                                $deal_data[] = [
                                    'user_id' => $user->id, 
                                    'partner_id' => $manager->id, //manager's id
                                    'balance_before' => 0, 
                                    'balance_after' => 0, 
                                    'bet' => abs($betMoney), 
                                    'deal_profit' => $deal_balance,
                                    'game' => $game,
                                    'shop_id' => $shop->id,
                                    'type' => 'shop',
                                    'deal_percent' => $deal_percent,
                                    'mileage' => $deal_mileage
                                ];
                            }
                            $partner = $manager->referral;
                            while ($partner != null && !$partner->isInoutPartner())
                            {
                                $deal_mileage = $deal_balance;
                                $deal_percent = $partner->deal_percent-$partner->table_deal_percent;
                                if($deal_percent > 0) {
                                    $deal_balance = $betMoney * $deal_percent  / 100;
                                    $deal_data[] = [
                                        'user_id' => $user->id, 
                                        'partner_id' => $partner->id,
                                        'balance_before' => 0, 
                                        'balance_after' => 0, 
                                        'bet' => abs($betMoney), 
                                        'deal_profit' => $deal_balance,
                                        'game' => $game,
                                        'shop_id' => $user->shop_id,
                                        'type' => 'partner',
                                        'deal_percent' => $deal_percent,
                                        'mileage' => $deal_mileage
                                    ];
                                }
                                $partner = $partner->referral;
                            }
                        }
            
                        if (count($deal_data) > 0)
                        {
                            \VanguardLTE\Jobs\UpdateDeal::dispatch($deal_data);
                        }
                    }
                    
                }
                $this->info('End deal calculation');
            });
            \Artisan::command('daily:reset_ggr {masterid=0}', function ($masterid) {
                $this->info('Begin reset calculation');
                if ($masterid>0)
                {
                    $reset_masters = \VanguardLTE\User::where('id', $masterid)->get();
                }
                else
                {
                    $reset_masters = \VanguardLTE\User::whereRaw('`last_reset_at` < NOW()  - INTERVAL `reset_days` DAY')->where('role_id', 6)->where('ggr_percent', '>', 0)->get();
                }
                if (count($reset_masters) > 0)
                {
                    foreach ($reset_masters as $master)
                    {
                        $partner_users = $master->availableUsers() + [$master->id];
                        $level = $master->level();
                        $childpartners = \VanguardLTE\User::lockForUpdate()->whereIn('role_id', range(4,$level))->whereIn('id', $partner_users)->get();
                        foreach ($childpartners as $user){
                            $ggr = $user->ggr_balance - $user->ggr_mileage - ($user->count_deal_balance - $user->count_mileage);
                            if ($ggr > 0)
                            {
                                //add ggr
                                $summ = $ggr;
                                if ($summ > 0) {
                                    //out balance from master
                                    $master = $user->referral;
                                    while ($master!=null && !$master->isInoutPartner())
                                    {
                                        $master = $master->referral;
                                    }

                                    if ($master == null)
                                    {
                                        $this->warn('Can not find master');
                                        return ;
                                    }
                                    
                                    if ($master->balance < $summ )
                                    {
                                        $this->warn('Masters balance is not enough');
                                        return ;
                                    }
                                    $master->update(
                                        ['balance' => $master->balance - $summ]
                                    );
                                    
                                    $old = $user->balance;

                                    $user->balance = $user->balance + $summ;
                                    $user->save();
                                    $user = $user->fresh();

                                    $master = $master->fresh();

                                    \VanguardLTE\Transaction::create([
                                        'user_id' => $user->id,
                                        'payeer_id' => $master->id,
                                        'system' => $user->username,
                                        'type' => 'ggr_out',
                                        'summ' => $summ,
                                        'old' => $old,
                                        'new' => $user->balance,
                                        'balance' => $master->balance,
                                        'shop_id' => 0
                                    ]);
                                }
                            }

                            //convert all deal balances
                            $real_deal_balance = $user->deal_balance - $user->mileage;
                            $summ = $real_deal_balance;
                            if ($summ > 0) {
                                //out balance from master
                                $master = $user->referral;
                                while ($master!=null && !$master->isInoutPartner())
                                {
                                    $master = $master->referral;
                                }

                                if ($master == null)
                                {
                                    $this->warn('Can not find master');
                                    return ;
                                }
                                
                                if ($master->balance < $summ )
                                {
                                    $this->warn('Masters balance is not enough');
                                    return ;
                                }
                                $master->update(
                                    ['balance' => $master->balance - $summ]
                                );
                                
                                $old = $user->balance;

                                $user->balance = $user->balance + $summ;
                                $user->deal_balance = 0;
                                $user->mileage = 0;
                                
                                $user->save();
                                $user = $user->fresh();

                                $master = $master->fresh();

                                \VanguardLTE\Transaction::create([
                                    'user_id' => $user->id,
                                    'payeer_id' => $master->id,
                                    'system' => $user->username,
                                    'type' => 'deal_out',
                                    'summ' => $summ,
                                    'old' => $old,
                                    'new' => $user->balance,
                                    'balance' => $master->balance,
                                    'shop_id' => 0
                                ]);
                            }

                            $user->ggr_balance = 0;
                            $user->ggr_mileage = 0;
                            //reset count deal balances
                            $user->count_deal_balance = 0;
                            $user->count_mileage = 0;
                            $user->last_reset_at = date('Y-m-d');
                            $user->save();
                        }
                    }
                    
                    $partner_shops = $master->availableShops();
                    $reset_shops = \VanguardLTE\Shop::lockForUpdate()->whereIn('id', $partner_shops)->get();
                    if (count($reset_shops) > 0)
                    {
                        foreach ($reset_shops as $shop)
                        {
                            $ggr = $shop->ggr_balance - $shop->ggr_mileage - ($shop->count_deal_balance - $shop->count_mileage);
                            if ($ggr > 0)
                            {
                                //add ggr
                                $summ = $ggr;
                                if ($summ > 0) {
                                    //out balance from master
                                    $master = $shop->creator;
                                    while ($master!=null && !$master->isInoutPartner())
                                    {
                                        $master = $master->referral;
                                    }

                                    if ($master == null)
                                    {
                                        $this->warn('Can not find master');
                                        return ;
                                    }
                                    
                                    if ($master->balance < $summ )
                                    {
                                        $this->warn('Masters balance is not enough');
                                        return ;
                                    }
                                    $master->update(
                                        ['balance' => $master->balance - $summ]
                                    );
                                    
                                    $old = $shop->balance;

                                    $shop->balance = $shop->balance + $summ;

                                    $shop->save();
                                    $shop = $shop->fresh();

                                    $master = $master->fresh();

                                    \VanguardLTE\ShopStat::create([
                                        'user_id' => $master->id,
                                        'type' => 'ggr_out',
                                        'sum' => $summ,
                                        'old' => $old,
                                        'new' => $shop->balance,
                                        'balance' => $master->balance,
                                        'shop_id' => $shop->id,
                                        'date_time' => \Carbon\Carbon::now()
                                    ]);
                                }
                            }

                            //convert all deal balances
                            $real_deal_balance = $shop->deal_balance - $shop->mileage;
                            $summ = $real_deal_balance;
                            if ($summ > 0) {
                                //out balance from master
                                $master = $shop->creator;
                                while ($master!=null && !$master->isInoutPartner())
                                {
                                    $master = $master->referral;
                                }

                                if ($master == null)
                                {
                                    $this->warn('Can not find master');
                                    return ;
                                }
                                
                                if ($master->balance < $summ )
                                {
                                    $this->warn('Masters balance is not enough');
                                    return ;
                                }
                                $master->update(
                                    ['balance' => $master->balance - $summ]
                                );
                                
                                $old = $shop->balance;

                                $shop->balance = $shop->balance + $summ;
                                $shop->deal_balance = $real_deal_balance - $summ;
                                $shop->mileage = 0;
                                
                                
                                $shop->save();
                                $shop = $shop->fresh();

                                $master = $master->fresh();

                                \VanguardLTE\ShopStat::create([
                                    'user_id' => $master->id,
                                    'type' => 'deal_out',
                                    'sum' => $summ,
                                    'old' => $old,
                                    'new' => $shop->balance,
                                    'balance' => $master->balance,
                                    'shop_id' => $shop->id,
                                    'date_time' => \Carbon\Carbon::now()
                                ]);
                            }
                            $shop->ggr_balance = 0;
                            $shop->ggr_mileage = 0;
                            //reset count deal balances
                            $shop->count_deal_balance = 0;
                            $shop->count_mileage = 0;
                            $shop->last_reset_at = date('Y-m-d');
                            $shop->save();

                        }
                    }
                }

                
                $this->info('End reset calculation');
            });

            
        }
    }

}

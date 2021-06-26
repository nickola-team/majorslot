<?php 
namespace VanguardLTE\Console
{
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

                $start_date = date("Y-m-d H:i:s",strtotime("-30 days"));
                \VanguardLTE\ShopStat::where('date_time', '<', $start_date)->delete();
                \VanguardLTE\Transaction::where('created_at', '<', $start_date)->delete();
                \VanguardLTE\WithdrawDeposit::where('created_at', '<', $start_date)->delete();

                \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::syncpromo();

            })->dailyAt('08:00');

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
                $task = \VanguardLTE\Task::where([
                    'finished' => 0, 
                    'category' => 'shop', 
                    'action' => 'delete'
                ])->first();
                if( $task ) 
                {
                    $task->update(['finished' => 1]);
                    $shopId = $task->item_id;
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
                    \VanguardLTE\Transaction::where('shop_id', $shopId)->delete();
                    \VanguardLTE\StatGame::where('shop_id', $shopId)->delete();
                    \VanguardLTE\Category::where('shop_id', $shopId)->delete();
                    \VanguardLTE\Returns::where('shop_id', $shopId)->delete();
                    \VanguardLTE\OpenShift::where('shop_id', $shopId)->delete();
                    \VanguardLTE\ShopStat::where('shop_id', $shopId)->delete();
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
                                $shops = $user->shops(true);
                                if( count($shops) ) 
                                {
                                    if( !is_array($shops) ) 
                                    {
                                        $shops = $shops->toArray();
                                    }
                                    $user->update(['shop_id' => array_shift($shops)]);
                                }
                                else
                                {
                                    $user->update(['shop_id' => 0]);
                                }
                            }
                        }
                    }
                    \VanguardLTE\User::doesntHave('rel_shops')->where('shop_id', '!=', 0)->whereIn('role_id', [
                        4, 
                        5
                    ])->update(['shop_id' => 0]);
                    $admin = \VanguardLTE\User::where('role_id', 6)->first();
                    if( $admin->shop_id == $shopId ) 
                    {
                        $admin->update(['shop_id' => 0]);
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
                    \VanguardLTE\DailySummary::where(['type' => 'today', 'date' => $date])->delete();
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

            
        }
    }

}

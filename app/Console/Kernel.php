<?php 
namespace VanguardLTE\Console
{
    use GuzzleHttp\Client;
    use GuzzleHttp\Exception\RequestException;
    use GuzzleHttp\Pool;
    use GuzzleHttp\Psr7\Request;
    use GuzzleHttp\Psr7\Response;
    use \VanguardLTE\Http\Controllers\Web\GameProviders\PPController;
    use Illuminate\Support\Facades\Crypt;


    class Kernel extends \Illuminate\Foundation\Console\Kernel
    {
        protected $commands = [

            \VanguardLTE\Console\Commands\GameLaunchCommand::class,
            \VanguardLTE\Console\Commands\GameSyncCommand::class,
            \VanguardLTE\Console\Commands\GameTerminateCommand::class,
            \VanguardLTE\Console\Commands\GameWithdrawAll::class,

            \VanguardLTE\Console\Commands\MonitorBetWin::class,
            \VanguardLTE\Console\Commands\GameRTPMonitor::class,

            \VanguardLTE\Console\Commands\XMXWithdrawAll::class,
            \VanguardLTE\Console\Commands\KDiorUsers::class,

            \VanguardLTE\Console\Commands\PowerBall\GameRoundGen::class,
            \VanguardLTE\Console\Commands\PowerBall\GameRoundProcess::class,
            \VanguardLTE\Console\Commands\PowerBall\GameList::class,
            
        ];
        protected function schedule(\Illuminate\Console\Scheduling\Schedule $schedule)
        {
            //$schedule->command('queue:work --daemon')->everyMinute()->withoutOverlapping();
            $schedule->call(function () {
                // \Illuminate\Support\Facades\Redis::del('pplist');
                // \Illuminate\Support\Facades\Redis::del('livelist');
                // \Illuminate\Support\Facades\Redis::del('hbnlist');
                // \Illuminate\Support\Facades\Redis::del('booongolist');
                // \Illuminate\Support\Facades\Redis::del('playsonlist');
                // \Illuminate\Support\Facades\Redis::del('cq9list');
                // \Illuminate\Support\Facades\Redis::del('playngolist');
                $hrefcats = \VanguardLTE\Category::where(['shop_id'=>0, 'site_id'=>0])->whereNotNull('categories.provider')->get();
                if (count($hrefcats) > 0)
                {
                    foreach ($hrefcats as $cat)
                    {
                        \Illuminate\Support\Facades\Redis::del($cat->href . 'list');
                    }
                }

                set_time_limit(0);
                $_daytime = strtotime("-1 days") * 10000;
                
                \VanguardLTE\PPTransaction::where('timestamp', '<', $_daytime)->delete();
                \VanguardLTE\HBNTransaction::where('timestamp', '<', $_daytime)->delete();
                \VanguardLTE\BNGTransaction::where('timestamp', '<', $_daytime)->delete();
                \VanguardLTE\CQ9Transaction::where('timestamp', '<', $_daytime)->delete();
                \VanguardLTE\ATATransaction::where('timestamp', '<', $_daytime)->delete();
                \VanguardLTE\EVOTransaction::where('timestamp', '<', $_daytime)->delete();
                \VanguardLTE\DGTransaction::where('timestamp', '<', $_daytime)->delete();
                \VanguardLTE\PNGTransaction::where('timestamp', '<', $_daytime)->delete();

                $start_date = date("Y-m-d H:i:s",strtotime("-7 days"));
                \VanguardLTE\GameLog::where('time', '<', $start_date)->delete();
                
                

                $start_date = date("Y-m-d H:i:s",strtotime("-10 days"));

                \VanguardLTE\StatGame::where('date_time', '<', $start_date)->delete();

                
                $start_date = date("Y-m-d H:i:s",strtotime("-3 days"));
                \VanguardLTE\DealLog::where('date_time', '<', $start_date)->delete();
                \VanguardLTE\GACTransaction::where('date_time', '<', $start_date)->delete();
                \VanguardLTE\PPGameLog::where('time', '<', $start_date)->delete();
                \VanguardLTE\BNGGameLog::where('c_at', '<', $start_date)->delete();


                $start_date = date("Y-m-d H:i:s",strtotime("-90 days"));
                \VanguardLTE\ShopStat::where('date_time', '<', $start_date)->delete();
                \VanguardLTE\Transaction::where('created_at', '<', $start_date)->delete();
                \VanguardLTE\WithdrawDeposit::where('created_at', '<', $start_date)->delete();


                $start_date = date("Y-m-d H:i:s",strtotime("-1 hours"));
                \VanguardLTE\GameLaunch::where('created_at', '<', $start_date)->delete();

                $start_date = date("Y-m-d",strtotime("-7 days")) . 'T00:00:00';
                \VanguardLTE\GPGameTrend::where('sDate','<', $start_date)->delete();

                $start_date = date("Y-m-d",strtotime("-90 days"));
                \VanguardLTE\DailySummary::where('date', '<', $start_date)->delete();
                \VanguardLTE\CategorySummary::where('date', '<', $start_date)->delete();
                \VanguardLTE\GameSummary::where('date', '<', $start_date)->delete();
                

                // \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::syncpromo();

            })->dailyAt('08:00');

            $schedule->command('daily:snapshot')->dailyAt('00:00')->runInBackground();
            $schedule->command('daily:sharesummary')->dailyAt('02:00')->runInBackground();
            $schedule->command('daily:summary')->dailyAt('01:00')->runInBackground();
            $schedule->command('daily:gamesummary')->dailyAt('01:30')->runInBackground();

            // $schedule->command('kten:omitted')->dailyAt('02:00')->runInBackground();

            $schedule->command('gp:genTrend')->dailyAt('08:00')->runInBackground();
            $schedule->command('pbgame:genround')->dailyAt('22:00')->runInBackground();

            // $schedule->command('monthly:summary')->monthlyOn(1, '9:00')->runInBackground();

            $schedule->command('today:summary')->everyTenMinutes()->withoutOverlapping()->runInBackground();
            $schedule->command('daily:promo')->everyTenMinutes()->withoutOverlapping()->runInBackground();
            $schedule->command('today:gamesummary')->everyTenMinutes()->withoutOverlapping()->runInBackground();

            $schedule->command('gac:processpending')->everyMinute()->withoutOverlapping()->runInBackground();

            $schedule->command('monitor:betwin')->cron('0 * * * *'); //every hour
            // $schedule->command('monitor:rtp')->dailyAt('12:00')->runInBackground();

            // $schedule->command('dg:sync')->everyMinute()->withoutOverlapping()->runInBackground();
            
            if (env('SWITCH_PP', false) == true){
                $schedule->command('daily:ppgames')->cron('15 */2 * * *');
            }

            $reset_bank = \VanguardLTE\Settings::where('key', 'reset_bank')->first();
            if ($reset_bank){
                switch ($reset_bank->value)
                {
                    case 0:
                        $schedule->command('hourly:reset_bank')->cron('0 * * * *'); //every hour
                        break;
                    case 1:
                        $schedule->command('hourly:reset_bank')->cron('0 */2 * * *'); //every 2 hour
                        break;
                    case 2:
                        $schedule->command('hourly:reset_bank')->cron('0 */6 * * *'); //every 6 hour
                        break;
                    case 3:
                        $schedule->command('hourly:reset_bank')->cron('0 */12 * * *'); //every 12 hour
                        break;
                    case 4:
                        $schedule->command('hourly:reset_bank')->cron('0 0 * * *'); //every day, 0hour
                        break;
                }
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

                $start_date = date("Y-m-d H:i:s", strtotime("-2 hours")) ;
                \VanguardLTE\PPGameFreeStackLog::where('created_at' , '<=', $start_date)->delete();
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
                        'parent' => 0,
                        'site_id' => $task->details,
                    ])->get();
                    if( count($categories) == 0) 
                    {
                        $categories = \VanguardLTE\Category::where([
                            'shop_id' => 0, 
                            'parent' => 0,
                            'site_id' => 0,
                        ])->get();
                    }
                    if( count($categories) ) 
                    {
                        foreach( $categories as $category ) 
                        {
                            $newCategory = $category->replicate();
                            $newCategory->shop_id = $shop->id;
                            $newCategory->save();
                            $superCategories[$category->original_id] = $newCategory->id;
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
                                    $superCategories[$category_2->original_id] = $newCategory_2->id;
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
                            //random generate win10, bonus10
                            if ($game->game_win)
                            {
                                $win = explode(',', $game->game_win->winline10);
                                $number = rand(0, count($win) - 1);
                                $newGame->winline10 = $win[$number];

                                $bonus = explode(',', $game->game_win->winbonus10);
                                $number = rand(0, count($bonus) - 1);

                                $newGame->winbonus10 = $bonus[$number];
                            }
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
                    //miss role
                    //direct find master
                    $masterid = $shop->getUsersByRole('master');
                    $master = \VanguardLTE\User::whereIn('id',$masterid)->first();
                    if ($master)
                    {
                        $comaster = $master->referral;
                        if ($comaster)
                        {
                            $info = $comaster->info;
                            $slotinf = null;
                            $tableinf = null;

                            foreach ($info as $inf)
                            {
                                if ($inf->roles =='slot')
                                {
                                    $slotinf = $inf;
                                }
                                if ($inf->roles =='table')
                                {
                                    $tableinf = $inf;
                                }
                            }
                            if ($slotinf)
                            {
                                $shopslotinf = \VanguardLTE\Info::create(
                                    [
                                        'link' => $slotinf->link,
                                        'title' => $slotinf->title,
                                        'roles' => 'slot',
                                        'text' => implode(',', rand_region_numbers($slotinf->title,$slotinf->link)),
                                        'user_id' => $comaster->id
                                    ]
                                    );
                                \VanguardLTE\InfoShop::create(
                                    [
                                        'shop_id' => $shop->id,
                                        'info_id' => $shopslotinf->id
                                    ]
                                    );
                                if ($slotinf->link>0)
                                {
                                    $shop->update(['slot_miss_deal' => 1]);
                                }
                            }
                            if ($tableinf)
                            {
                                $shoptableinf = \VanguardLTE\Info::create(
                                    [
                                        'link' => $tableinf->link,
                                        'title' => $tableinf->title,
                                        'roles' => 'table',
                                        'text' => implode(',', rand_region_numbers($tableinf->title,$tableinf->link)),
                                        'user_id' => $comaster->id
                                    ]
                                    );
                                \VanguardLTE\InfoShop::create(
                                    [
                                        'shop_id' => $shop->id,
                                        'info_id' => $shoptableinf->id
                                    ]
                                    );
                                if ($tableinf->link>0)
                                {
                                    $shop->update(['table_miss_deal' => 1]);
                                }
                            }
                        }

                    }
                    $shop->update(['pending' => 0]);
                }
            })->everyMinute();
            $schedule->call(function()
            {
                $date_time = date('Y-m-d 0:0:0', strtotime("-1 days"));
                $task = \VanguardLTE\Task::where([
                    'finished' => 0, 
                    'category' => 'user', 
                    'action' => 'delete'
                ])->where('created_at', '<=', $date_time)->first();
                if( $task != null ) 
                {
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
                    $task->update(['finished' => 1]);
                }
            })->everyMinute();
            $schedule->call(function()
            {
                $date_time = date('Y-m-d 0:0:0', strtotime("-1 days"));
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
            \Artisan::command('daily:sharesummary {date=today}', function ($date) {
                $this->info("Begin daily share summary adjustment.");
                $groups = \VanguardLTE\User::where('role_id',8)->get();
                foreach ($groups as $g)
                {
                    if ($date == 'today') {
                        \VanguardLTE\ShareBetSummary::summary($g->id);
                    }
                    else{
                        \VanguardLTE\ShareBetSummary::summary($g->id, $date);
                    }
                }
                $this->info("End daily share summary adjustment.");
            });

            \Artisan::command('daily:gamesummary {date=today} {partnerid=0}', function ($date, $partnerid) {
                $this->info("Begin daily game summary adjustment.");
                if ($partnerid == 0)
                {
                    $admins = \VanguardLTE\User::where('role_id',9)->get();
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
                }
                else
                {
                    $partner = \VanguardLTE\User::find($partnerid);
                    if (!$partner)
                    {
                        $this->error('Not found partnerid');
                        return;
                    }
                    if ($date == 'today') {
                        \VanguardLTE\CategorySummary::summary($partnerid);
                    }
                    else{
                        \VanguardLTE\CategorySummary::summary($partnerid, $date);
                    }
                    if ($date == 'today') {
                        $availablePartners = $partner->hierarchyPartners();
                        $availablePartners[] = $partnerid;
                        $day = date("Y-m-d", strtotime("-1 days"));
                        \VanguardLTE\CategorySummary::where(['type' => 'today', 'date' => $day])->whereIn('user_id', $availablePartners)->delete();
                        \VanguardLTE\GameSummary::where(['type' => 'today', 'date' => $day])->whereIn('user_id', $availablePartners)->delete();
                    }
                }
                
                $this->info("End daily game summary adjustment.");
            });
            \Artisan::command('today:gamesummary {user_id=0}', function ($user_id) {
                set_time_limit(0);
                $this->info("Begin today's game adjustment.");
                if ($user_id == 0)
                {
                    $admins = \VanguardLTE\User::where('role_id',9)->get();
                }
                else
                {
                    $admins = \VanguardLTE\User::where('id',$user_id)->get();
                }
                
                foreach ($admins as $admin)
                {
                    \VanguardLTE\CategorySummary::summary_today($admin->id);
                }
                $this->info("End today's game adjustment.");
            });

            \Artisan::command('daily:summary {date=today}  {partnerid=0}', function ($date, $partnerid) {
                set_time_limit(0);
                $this->info("Begin summary daily adjustment.");
                if ($partnerid == 0)
                {
                    $admins = \VanguardLTE\User::where('role_id',9)->get();
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
                }
                else
                {
                    $partner = \VanguardLTE\User::find($partnerid);
                    if (!$partner)
                    {
                        $this->error('Not found partnerid');
                        return;
                    }
                    if ($date == 'today') {
                        \VanguardLTE\DailySummary::summary($partnerid);
                    }
                    else{
                        \VanguardLTE\DailySummary::summary($partnerid, $date);
                    }
                    if ($date == 'today') {
                        $day = date("Y-m-d", strtotime("-1 days"));
                        \VanguardLTE\DailySummary::where(['type' => 'today', 'date' => $day])->delete();
                    }
                }

                $this->info("End summary daily adjustment.");
            });

            \Artisan::command('today:summary', function () {
                set_time_limit(0);
                $this->info("Begin today's adjustment.");

                $admins = \VanguardLTE\User::where('role_id',9)->get();
                foreach ($admins as $admin)
                {
                    \VanguardLTE\DailySummary::summary_today($admin->id);
                }
                $this->info("End today's adjustment.");
            });

            \Artisan::command('monthly:summary {month=today}', function ($month) {
                set_time_limit(0);
                $this->info("Begin summary monthly adjustment.");

                $admins = \VanguardLTE\User::where('role_id',9)->get();
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
                $res = \VanguardLTE\Http\Controllers\Web\GameProviders\HONORController::syncpromo();
                $this->info($res['msg']);
            });

            \Artisan::command('daily:newgame {categoryid} {originalid}', function ($categoryid, $originalid) {
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
                        $cat = \VanguardLTE\Category::where(['shop_id' => $id, 'original_id' => $categoryid])->first();
                        if ($cat){
                            \VanguardLTE\GameCategory::create(['game_id'=>$game->id, 'category_id'=>$cat->id]);
                        }
                    }
                }
                $this->info('End');
            });

            \Artisan::command('daily:bulknewgame {categoryid} {startid} {endid}', function ($categoryid, $startid, $endid) {
                set_time_limit(0);
                $this->info("Begin adding bulk new game to all shop");
                
                for ($originalid = $startid; $originalid<$endid; $originalid++){
                    $this->info("creating  " . $originalid . " game..");
                    $buffgame = \VanguardLTE\Game::where('id', $originalid)->first();
                    if (!$buffgame)
                    {
                        $this->error('Can not find original game of new game');
                        continue;
                    }
                    $shop_ids = \VanguardLTE\Shop::all()->pluck('id')->toArray();
                    $data = $buffgame->toArray();
                    foreach ($shop_ids as $id)
                    {
                        $game = \VanguardLTE\Game::where(['shop_id'=> $id, 'original_id' => $originalid])->first();
                        if ($game)
                        {
                            $this->info("Game already exist in " . $id . " shop");
                        }
                        else{
                            $this->info("=== creating  game at " . $id . " shop");
                            $data['shop_id'] = $id;
                            if ($buffgame->game_win)
                            {
                                $win = explode(',', $buffgame->game_win->winline10);
                                $number = rand(0, count($win) - 1);
                                $data['winline10'] = $win[$number];

                                $bonus = explode(',', $buffgame->game_win->winbonus10);
                                $number = rand(0, count($bonus) - 1);

                                $data['winbonus10'] = $bonus[$number];
                            }
                            $game = \VanguardLTE\Game::create($data);
                            
                            
                        }
                        $this->info("=== creating  gamecategory at " . $id . " shop");
                        $cat = \VanguardLTE\Category::where(['shop_id' => $id, 'original_id' => $categoryid])->first();
                        if ($cat){
                            $gcat = \VanguardLTE\GameCategory::where(['game_id'=>$game->id, 'category_id'=>$cat->id])->first();
                            if (!$gcat)
                            {
                                $gcat = \VanguardLTE\GameCategory::create(['game_id'=>$game->id, 'category_id'=>$cat->id]);
                            }
                        }
                        $this->info("=== done");
                    }
                }
                $this->info('End');
            });

            \Artisan::command('launch:makeurl', function () {
                $launchRequests = \VanguardLTE\GameLaunch::where(['finished'=> 0, 'provider' => 'pp'])->orderby('created_at', 'asc')->get();
                $processed_users = [];
                foreach ($launchRequests as $request)
                {
                    if (in_array($request->user_id, $processed_users)) //process 1 request per one user
                    {
                        $this->info('skipping userid=' . $request->user_id . ', id=' . $request->id);
                        continue;
                    }
                    $processed_users[] = $request->user_id;
                    if ($request->provider == 'pp')
                    {
                        $url = PPController::makelink($request->gamecode, $request->user_id);
                        if ($url != null)
                        {
                            $request->update([
                                'launchUrl' => $url,
                                'finished' => 1,
                            ]);
                        }
                    }
                }

            });

            \Artisan::command('pp:getround {timepoint} {lastpoint} {dataType}', function ($timepoint, $lastpoint, $dataType) {
                while ($timepoint < $lastpoint) {
                    $data = PPController::gamerounds($timepoint, $dataType);
                    // $this->info($data);
                    $count = 0;
                    if ($data)
                    {
                        $parts = explode("\n", $data);
                        $updatetime = explode("=",$parts[0]);
                        if (count($updatetime) > 1) {
                            $timepoint = $updatetime[1];
                        }
                        //ignore $parts[2]
                        for ($i=2;$i<count($parts);$i++)
                        {
                            $round = explode(",", $parts[$i]);
                            if (count($round) < 8)
                            {
                                continue;
                            }
                            if ($round[7] == "C") {
                                $time = strtotime($round[6].' UTC');
                                $dateInLocal = date("Y-m-d H:i:s", $time);
                                $shop = \VanguardLTE\ShopUser::where('user_id', $round[1])->first();
                                $gameObj =  PPController::gamecodetoname($round[2]);
                                $stat = \VanguardLTE\StatGame::where(['roundid'=> $round[3], 'game_id'=>$round[2], 'user_id'=>$round[1]])->where('date_time', '>=', $dateInLocal)->get();
                                if (count($stat) == 0){
                                    \VanguardLTE\StatGame::create([
                                        'user_id' => $round[1], 
                                        'balance' => floatval(-1), 
                                        'bet' => $round[9], 
                                        'win' => $round[10], 
                                        'game' =>$gameObj[0] . '_pp', 
                                        'type' => $gameObj[1],//($dataType=='RNG')?'slot':'table',
                                        'percent' => 0, 
                                        'percent_jps' => 0, 
                                        'percent_jpg' => 0, 
                                        'profit' => 0, 
                                        'denomination' => 0, 
                                        'date_time' => $dateInLocal,
                                        'shop_id' => $shop->shop_id,
                                        'category_id' => isset($category)?$category->id:0,
                                        'game_id' => $round[2],
                                        'roundid' => $round[3],
                                    ]);
                                    $count = $count + 1;
                                }
                            }
                        }
                    }
                    $this->info('new timepoint = ' . $timepoint . ', Added ' . $count . ' records');
                    usleep(1000);
                }

            });
            
            \Artisan::command('pp:gameround {debug=0}', function ($debug) {
                $data = PPController::processGameRound('RNG');
                $this->info('saved ' . $data[0] . ' RNG bet/win record.');
                $this->info('new RNG timepoint = ' . $data[1]);
                $data = PPController::processGameRound('LC');
                $this->info('saved ' . $data[0] . ' LC bet/win record.');
                $this->info('new LC timepoint = ' . $data[1]);
                $data = PPController::processGameRound('R2');
                $this->info('saved ' . $data[0] . ' R2 bet/win record.');
                $this->info('new R2 timepoint = ' . $data[1]);
            });

            \Artisan::command('pp:syncbalance {debug=1}', function ($debug) {
                $pp_users = \VanguardLTE\User::where('playing_game','pp')->get()->toArray();
                $pp_playing_users = [];
                foreach ($pp_users as $user) {
                    // if ( time() - $user['played_at'] > 300) //5min
                    // {
                    //     $this->info('terminate human user id = ' . $user['id']);
                    //     PPController::terminate($user['id']);
                    //     \VanguardLTE\User::lockforUpdate()->where('id',$user['id'])->update(['playing_game' => null]);
                    // }
                    // else
                    // {
                        $pp_playing_users[] = $user;
                    // }
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
                            // if ($data['balance']==null)
                            // {
                            //     $this->info('null balance id = ' . $user['id'] . ', old balance = ' . $user['balance'] .  ', pp balance = ' . $data['balance']);
                            // }
                            // else 
                            if (floatval($user['balance']) !=  floatval($data['balance']) )
                            {
                                $userdata = \VanguardLTE\User::lockforUpdate()->where('id',$user['id'])->first();
                                if ($userdata && $userdata->playing_game == 'pp')
                                {
                                    $userdata->update(['balance' => $data['balance'], 'played_at' => time()]);
                                }
                                else
                                {
                                    $this->info('ID ' . $user['id'] . ' alreay exist game');
                                }
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

                foreach ($pp_users as $user) {
                    if ( time() - $user['played_at'] > 600) //10min
                    {
                        $this->info('terminate human user id = ' . $user['id']);
                        PPController::terminate($user['id']);
                        \VanguardLTE\User::lockforUpdate()->where('id',$user['id'])->update(['playing_game' => null]);
                    }
                }

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
                $data = $cat->toArray();

                $sites = \VanguardLTE\WebSite::all()->pluck('id')->toArray();
                foreach ($sites as $site)
                {
                    $sitecats = \VanguardLTE\Category::where('site_id', $site)->get();
                    if (count($sitecats) > 0)
                    {
                        if (\VanguardLTE\Category::where(['shop_id'=>0,'original_id'=>$originalid,'site_id'=>$site])->first())
                        {
                            $this->info("Category already exist in " . $site . " site");
                        }
                        else
                        {
                            $data['site_id'] = $site;
                            if ($cat->parent > 0)
                            {
                                $supercategory = \VanguardLTE\Category::where(['site_id' => $site, 'original_id' => $cat->parent])->first();
                                if ($supercategory){
                                    $data['parent'] = $supercategory->id;
                                }
                            }
                            $site_cat = \VanguardLTE\Category::create($data);
                        }
                        
                        
                    }
                }
                $data['site_id'] = 0;
                $shop_ids = \VanguardLTE\Shop::all()->pluck('id')->toArray();
                foreach ($shop_ids as $id)
                {
                    $default_cat = \VanguardLTE\Category::where(['shop_id'=> $id])->first();
                    $data['site_id'] = $default_cat->site_id;
                    if (\VanguardLTE\Category::where(['shop_id'=> $id, 'href' => $cat->href, 'provider' => $cat->provider])->first())
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

            \Artisan::command('daily:newwebsite', function () {
                set_time_limit(0);
                $this->info("Begin adding new category to sites");
                
                $sites = \VanguardLTE\WebSite::all()->pluck('id')->toArray();
                foreach ($sites as $site)
                {
                    $sitecats = \VanguardLTE\Category::where('site_id', $site)->get();
                    if (count($sitecats) == 0){
                        // create categories
                        $categories = \VanguardLTE\Category::where([
                            'shop_id' => 0, 
                            'parent' => 0,
                            'site_id' => 0,
                        ])->get();
                        if( count($categories) ) 
                        {
                            foreach( $categories as $category ) 
                            {
                                $newCategory = $category->replicate();
                                $newCategory->site_id = $site;
                                $newCategory->save();
                                $categories_2 = \VanguardLTE\Category::where([
                                    'shop_id' => 0, 
                                    'parent' => $category->id
                                ])->get();
                                if( count($categories_2) ) 
                                {
                                    foreach( $categories_2 as $category_2 ) 
                                    {
                                        $newCategory_2 = $category_2->replicate();
                                        $newCategory_2->site_id = $site;
                                        $newCategory_2->parent = $newCategory->id;
                                        $newCategory_2->save();
                                    }
                                }
                            }
                        }
                    }
                    else
                    {
                        $this->info('Categoryes already exist at ' . $site . ' site.');
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

            \Artisan::command('daily:dealsum {from} {to} {comastername} {game=all}', function ($from, $to, $comastername, $game) {
                set_time_limit(0);
                $this->info("Begin deal calculation");
                $comaster = \VanguardLTE\User::where('username', $comastername)->first();
                if (!$comaster || !$comaster->hasRole('comaster'))
                {
                    $this->info("Can not find comaster");
                    return;
                }
                $availableUsers = $comaster->availableUsers();
                if ($game=='all'){
                    $stat_games = \VanguardLTE\StatGame::groupby('user_id','game')->where('date_time','>=',$from)->where('date_time','<=',$to)->where('bet','>', 0)->whereIn('user_id', $availableUsers)->selectRaw('SUM(bet) as bet, win, game, type, user_id, date_time, category_id, game_id')->get();
                }
                else
                {
                    $stat_games = \VanguardLTE\StatGame::groupby('user_id')->where('date_time','>=',$from)->where('date_time','<=',$to)->where('bet','>', 0)->where('game', 'like', '%' . $game . '%')->whereIn('user_id', $availableUsers)->selectRaw('SUM(bet) as bet, win, game, type, user_id')->get();
                }
                $this->info("total bet = " . $stat_games->sum('bet') . ', count=' . $stat_games->count());
                foreach ($stat_games as $stat)
                {
                    usleep(10);
                    $user = \VanguardLTE\User::where('id',$stat->user_id)->first();
                    if ($game=='all')
                    {
                        $user->processBetDealerMoney_Queue($stat);
                    }
                }
                $this->info('End deal calculation');
            });
            \Artisan::command('hourly:reset_bank {masterid=0}', function ($masterid) {
                $this->info('Begin reset game bank');
                $minslot1 = \VanguardLTE\Settings::where('key', 'minslot1')->first();
                $maxslot1 = \VanguardLTE\Settings::where('key', 'maxslot1')->first();
                $minslot2 = \VanguardLTE\Settings::where('key', 'minslot2')->first();
                $maxslot2 = \VanguardLTE\Settings::where('key', 'maxslot2')->first();
                $minslot3 = \VanguardLTE\Settings::where('key', 'minslot3')->first();
                $maxslot3 = \VanguardLTE\Settings::where('key', 'maxslot3')->first();
                $minslot4 = \VanguardLTE\Settings::where('key', 'minslot4')->first();
                $maxslot4 = \VanguardLTE\Settings::where('key', 'maxslot4')->first();
                $minslot5 = \VanguardLTE\Settings::where('key', 'minslot5')->first();
                $maxslot5 = \VanguardLTE\Settings::where('key', 'maxslot5')->first();

                $minbonus1 = \VanguardLTE\Settings::where('key', 'minbonus1')->first();
                $maxbonus1 = \VanguardLTE\Settings::where('key', 'maxbonus1')->first();
                $minbonus2 = \VanguardLTE\Settings::where('key', 'minbonus2')->first();
                $maxbonus2 = \VanguardLTE\Settings::where('key', 'maxbonus2')->first();
                $minbonus3 = \VanguardLTE\Settings::where('key', 'minbonus3')->first();
                $maxbonus3 = \VanguardLTE\Settings::where('key', 'maxbonus3')->first();
                $minbonus4 = \VanguardLTE\Settings::where('key', 'minbonus4')->first();
                $maxbonus4 = \VanguardLTE\Settings::where('key', 'maxbonus4')->first();
                $minbonus5 = \VanguardLTE\Settings::where('key', 'minbonus5')->first();
                $maxbonus5 = \VanguardLTE\Settings::where('key', 'maxbonus5')->first();
                // $reset_bank = \VanguardLTE\Settings::where('key', 'reset_bank')->first();
                $gamebanks = \VanguardLTE\GameBank::all();
                foreach ($gamebanks as $bank)
                {
                    $admin = \VanguardLTE\User::where('role_id', 9)->first();
                    try {
                        $shop = \VanguardLTE\Shop::where('id', $bank->shop_id)->first();
                        $game = $bank->game;
                        $old1 = $bank->slots_01;
                        if ($minslot1 && $bank->slots_01 < $minslot1->value)
                        {
                            $bank->slots_01 = $minslot1->value;
                        }
                        if ($maxslot1 && $bank->slots_01 > $maxslot1->value)
                        {
                            $bank->slots_01 = $maxslot1->value;
                        }
                        if ($shop && $old1 != $bank->slots_01){
                            $name = $shop->name;
                            if ($game)
                            {
                                $name = $name . '-' . $game->name;
                            }
                            \VanguardLTE\BankStat::create([
                                'name' => 'Slot1' . "[$name]", 
                                'user_id' => $admin->id, 
                                'type' => ($old1<$bank->slots_01)?'add':'out', 
                                'sum' => abs($old1 - $bank->slots_01), 
                                'old' => $old1, 
                                'new' => $bank->slots_01, 
                                'shop_id' => $bank->shop_id
                            ]);
                        }

                        $old2 = $bank->slots_02;

                        if ($minslot2 && $bank->slots_02 < $minslot2->value)
                        {
                            $bank->slots_02 = $minslot2->value;
                        }
                        if ($maxslot2 && $bank->slots_02 > $maxslot2->value)
                        {
                            $bank->slots_02 = $maxslot2->value;
                        }

                        if ($shop && $old2 != $bank->slots_02){
                            $name = $shop->name;
                            if ($game)
                            {
                                $name = $name . '-' . $game->name;
                            }
                            \VanguardLTE\BankStat::create([
                                'name' => 'Slot2' . "[$name]", 
                                'user_id' => $admin->id, 
                                'type' => ($old2<$bank->slots_02)?'add':'out', 
                                'sum' => abs($old2 - $bank->slots_02), 
                                'old' => $old2, 
                                'new' => $bank->slots_02, 
                                'shop_id' => $bank->shop_id
                            ]);
                        }

                        $old3 = $bank->slots_03;

                        if ($minslot3 && $bank->slots_03 < $minslot3->value)
                        {
                            $bank->slots_03 = $minslot3->value;
                        }
                        if ($maxslot3 && $bank->slots_03 > $maxslot3->value)
                        {
                            $bank->slots_03 = $maxslot3->value;
                        }

                        if ($shop && $old3 != $bank->slots_03){
                            $name = $shop->name;
                            if ($game)
                            {
                                $name = $name . '-' . $game->name;
                            }
                            \VanguardLTE\BankStat::create([
                                'name' => 'Slot3' . "[$name]", 
                                'user_id' => $admin->id, 
                                'type' => ($old3<$bank->slots_03)?'add':'out', 
                                'sum' => abs($old3 - $bank->slots_03), 
                                'old' => $old3, 
                                'new' => $bank->slots_03, 
                                'shop_id' => $bank->shop_id
                            ]);
                        }

                        $old4 = $bank->slots_04;

                        if ($minslot4 && $bank->slots_04 < $minslot4->value)
                        {
                            $bank->slots_04 = $minslot4->value;
                        }
                        if ($maxslot4 && $bank->slots_04 > $maxslot4->value)
                        {
                            $bank->slots_04 = $maxslot4->value;
                        }

                        if ($shop && $old4 != $bank->slots_04){
                            $name = $shop->name;
                            if ($game)
                            {
                                $name = $name . '-' . $game->name;
                            }
                            \VanguardLTE\BankStat::create([
                                'name' => 'Slot4' . "[$name]", 
                                'user_id' => $admin->id, 
                                'type' => ($old4<$bank->slots_04)?'add':'out', 
                                'sum' => abs($old4 - $bank->slots_04), 
                                'old' => $old4, 
                                'new' => $bank->slots_04, 
                                'shop_id' => $bank->shop_id
                            ]);
                        }

                        $old5 = $bank->slots_05;

                        if ($minslot5 && $bank->slots_05 < $minslot5->value)
                        {
                            $bank->slots_05 = $minslot5->value;
                        }
                        if ($maxslot5 && $bank->slots_05 > $maxslot5->value)
                        {
                            $bank->slots_05 = $maxslot5->value;
                        }

                        if ($shop && $old5 != $bank->slots_05){
                            $name = $shop->name;
                            if ($game)
                            {
                                $name = $name . '-' . $game->name;
                            }
                            \VanguardLTE\BankStat::create([
                                'name' => 'Slot5' . "[$name]", 
                                'user_id' => $admin->id, 
                                'type' => ($old5<$bank->slots_05)?'add':'out', 
                                'sum' => abs($old5 - $bank->slots_05), 
                                'old' => $old5, 
                                'new' => $bank->slots_05, 
                                'shop_id' => $bank->shop_id
                            ]);
                        }
                        
                        $bank->save();

                    }
                    catch (Exception $ex)
                    {

                    }
                }

                $bonusbanks = \VanguardLTE\BonusBank::all();
                foreach ($bonusbanks as $bank)
                {
                    try {
                        if ($bank->game && $bank->game->advanced == 'modern'){
                            $master = \VanguardLTE\User::where('id', $bank->master_id)->first();
                            $old1 = $bank->bank_01;
                            if ($minbonus1 && $bank->bank_01 < $minbonus1->value)
                            {
                                $bank->bank_01 = $minbonus1->value;
                            }
                            if ($maxbonus1 && $bank->bank_01 > $maxbonus1->value)
                            {
                                $bank->bank_01 = $maxbonus1->value;
                            }
                            if ($old1 != $bank->bank_01){
                                if ($master)
                                {
                                    $name = $master->username;
                                    $game = 'General';
                                    if ($bank->game_id!=0)
                                    {
                                        $game = $bank->game->title;
                                    }
                                    \VanguardLTE\BankStat::create([
                                        'name' => 'Bonus1' . "[$name]-$game", 
                                        'user_id' => $admin->id, 
                                        'type' => ($old1<$bank->bank_01)?'add':'out', 
                                        'sum' => abs($old1 - $bank->bank_01), 
                                        'old' => $old1, 
                                        'new' => $bank->bank_01, 
                                        'shop_id' => 0
                                    ]);
                                }
                            }

                            //2
                            $old2 = $bank->bank_02;
                            if ($minbonus2 && $bank->bank_02 < $minbonus2->value)
                            {
                                $bank->bank_02 = $minbonus2->value;
                            }
                            if ($maxbonus2 && $bank->bank_02 > $maxbonus2->value)
                            {
                                $bank->bank_02 = $maxbonus2->value;
                            }
                            if ($old2 != $bank->bank_02){
                                if ($master)
                                {
                                    $name = $master->username;
                                    $game = 'General';
                                    if ($bank->game_id!=0)
                                    {
                                        $game = $bank->game->title;
                                    }
                                    \VanguardLTE\BankStat::create([
                                        'name' => 'Bonus2' . "[$name]-$game", 
                                        'user_id' => $admin->id, 
                                        'type' => ($old2<$bank->bank_02)?'add':'out', 
                                        'sum' => abs($old2 - $bank->bank_02), 
                                        'old' => $old2, 
                                        'new' => $bank->bank_02, 
                                        'shop_id' => 0
                                    ]);
                                }
                            }

                            //3
                            $old3 = $bank->bank_03;
                            if ($minbonus3 && $bank->bank_03 < $minbonus3->value)
                            {
                                $bank->bank_03 = $minbonus3->value;
                            }
                            if ($maxbonus3 && $bank->bank_03 > $maxbonus3->value)
                            {
                                $bank->bank_03 = $maxbonus3->value;
                            }
                            if ($old3 != $bank->bank_03){
                                if ($master)
                                {
                                    $name = $master->username;
                                    $game = 'General';
                                    if ($bank->game_id!=0)
                                    {
                                        $game = $bank->game->title;
                                    }
                                    \VanguardLTE\BankStat::create([
                                        'name' => 'Bonus3' . "[$name]-$game", 
                                        'user_id' => $admin->id, 
                                        'type' => ($old3<$bank->bank_03)?'add':'out', 
                                        'sum' => abs($old3 - $bank->bank_03), 
                                        'old' => $old3, 
                                        'new' => $bank->bank_03, 
                                        'shop_id' => 0
                                    ]);
                                }
                            }

                            //4
                            $old4 = $bank->bank_04;
                            if ($minbonus4 && $bank->bank_04 < $minbonus4->value)
                            {
                                $bank->bank_04 = $minbonus4->value;
                            }
                            if ($maxbonus4 && $bank->bank_04 > $maxbonus4->value)
                            {
                                $bank->bank_04 = $maxbonus4->value;
                            }
                            if ($old4 != $bank->bank_04){
                                if ($master)
                                {
                                    $name = $master->username;
                                    $game = 'General';
                                    if ($bank->game_id!=0)
                                    {
                                        $game = $bank->game->title;
                                    }
                                    \VanguardLTE\BankStat::create([
                                        'name' => 'Bonus4' . "[$name]-$game", 
                                        'user_id' => $admin->id, 
                                        'type' => ($old4<$bank->bank_04)?'add':'out', 
                                        'sum' => abs($old4 - $bank->bank_04), 
                                        'old' => $old4, 
                                        'new' => $bank->bank_04, 
                                        'shop_id' => 0
                                    ]);
                                }
                            }

                            //5
                            $old5 = $bank->bank_05;
                            if ($minbonus5 && $bank->bank_05 < $minbonus5->value)
                            {
                                $bank->bank_05 = $minbonus5->value;
                            }
                            if ($maxbonus5 && $bank->bank_05 > $maxbonus5->value)
                            {
                                $bank->bank_05 = $maxbonus5->value;
                            }
                            if ($old5 != $bank->bank_05){
                                if ($master)
                                {
                                    $name = $master->username;
                                    $game = 'General';
                                    if ($bank->game_id!=0)
                                    {
                                        $game = $bank->game->title;
                                    }
                                    \VanguardLTE\BankStat::create([
                                        'name' => 'Bonus5' . "[$name]-$game", 
                                        'user_id' => $admin->id, 
                                        'type' => ($old5<$bank->bank_05)?'add':'out', 
                                        'sum' => abs($old5 - $bank->bank_05), 
                                        'old' => $old5, 
                                        'new' => $bank->bank_05, 
                                        'shop_id' => 0
                                    ]);
                                }
                            }

                            $bank->save();

                        }
                    }
                    catch (Exception $ex)
                    {

                    }
                }
                $this->info('End reset game bank');
            });
            \Artisan::command('daily:snapshot', function () {
                \DB::statement('DROP TABLE IF EXISTS w_users_snapshot');
                \DB::statement('CREATE TABLE w_users_snapshot AS SELECT * FROM w_users');
                \DB::statement('DROP TABLE IF EXISTS w_shops_snapshot');
                \DB::statement('CREATE TABLE w_shops_snapshot AS SELECT * FROM w_shops');
                \DB::statement('DROP TABLE IF EXISTS w_happyhour_users_snap');
                \DB::statement('CREATE TABLE w_happyhour_users_snap AS SELECT * FROM w_happyhour_users WHERE status=1');
            });
            
            \Artisan::command('game:genfreestack {gameid}', function ($gameid) {
                $this->info('Gen freestack');
                $game = \VanguardLTE\Game::where('id', $gameid)->first();
                $slotsetting = '\VanguardLTE\Games\\' . $game->name . '\SlotSettings';
                $user = \VanguardLTE\User::where('role_id',1)->first();
                $slot = new $slotsetting($game->name, $user->id);
                $slot->genfree();
                $this->info('End freestack');
            });
            \Artisan::command('convert:session', function () {
                $this->info('Convert Session');
                $users = \VanguardLTE\User::get();
                foreach ($users as $user)
                {
                    if( !isset($user->session) || strlen($user->session) <= 0 ) 
                    {
                        $user->session = serialize([]);
                    }
                    $gameData = unserialize($user->session);
                    $user->session_json = json_encode($gameData);
                    $user->save();
                }
                $this->info('End convert session');
            });
            \Artisan::command('add:gamesession', function () {
                $this->info('Add Game Session');
                $users = \VanguardLTE\User::get();
                foreach ($users as $user)
                {
                    if( !isset($user->session_json) || strlen($user->session_json) <= 0 ) 
                    {
                        $user->session_json = json_encode([]);
                    }
                    $session_jsons = json_decode($user->session_json, true);
                    $games = \VanguardLTE\Game::where('shop_id', $user->shop_id)->get();
                    foreach($games as $game){
                        $session = [];
                        foreach($session_jsons as $key => $value){
                            if(strpos($key, $game->name) !== false){
                                $session[$key] = $value;
                            }
                        }
                        if(is_array($session) && count($session) > 0){
                            \VanguardLTE\GameSession::create([
                                'user_id' => $user->id, 
                                'game_id' => $game->id, 
                                'session' => json_encode($session)
                            ]);
                        }
                    }
                }
                $this->info('End add game session');
            });

            \Artisan::command('add:fixshop {shopid}', function($shopid){
                $this->info('Start fixing games at shop');
                if( $shopid ) 
                {
                    $shop = \VanguardLTE\Shop::find($shopid);
                    if (!$shop)
                    {
                        $this->info('Could not find shop');
                    }
                    
                    $shopCategories = \VanguardLTE\ShopCategory::where('shop_id', $shop->id)->get();
                    if( count($shopCategories) ) 
                    {
                        $shopCategories = $shopCategories->pluck('category_id')->toArray();
                    }
                    $superCategories = [];

                    $categories = \VanguardLTE\Category::where([
                        'shop_id' => 0, 
                        'parent' => 0,
                        'site_id' => 0,
                    ])->get();

                    if( count($categories) ) 
                    {
                        foreach( $categories as $category ) 
                        {
                            $oldCategory = \VanguardLTE\Category::where([
                                'shop_id' => $shop->id, 
                                'original_id' => $category->original_id
                            ])->first();
                            if (!$oldCategory){
                                $newCategory = $category->replicate();
                                $newCategory->shop_id = $shop->id;
                                $newCategory->save();
                                $oldCategory = $newCategory;
                                $this->info('Add category ' . $category->original_id);
                            }
                            $superCategories[$category->original_id] = $oldCategory->id;
                            $categories_2 = \VanguardLTE\Category::where([
                                'shop_id' => 0, 
                                'parent' => $category->id
                            ])->get();
                            if( count($categories_2) ) 
                            {
                                foreach( $categories_2 as $category_2 ) 
                                {
                                    $oldCategory_2 = \VanguardLTE\Category::where([
                                        'shop_id' => $shop->id, 
                                        'original_id' => $category_2->original_id
                                    ])->first();
                                    if (!$oldCategory_2) {
                                        $newCategory_2 = $category_2->replicate();
                                        $newCategory_2->shop_id = $shop->id;
                                        $newCategory_2->parent = $oldCategory->id;
                                        $newCategory_2->save();
                                        $oldCategory_2 = $newCategory_2;
                                        $this->info('Add category ' . $category_2->original_id);
                                    }
                                    $superCategories[$category_2->original_id] = $oldCategory_2->id;
                                }
                            }
                        }
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
                            $oldGame = \VanguardLTE\Game::where(['shop_id' => $shop->id, 'original_id' => $game->original_id])->first();
                            if (!$oldGame) {
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
                                $this->info('Add game ' . $game->original_id);
                            }
                            else
                            {
                                $oldCategory = \VanguardLTE\GameCategory::where(['game_id' => $oldGame->id])->first();
                                if (!$oldCategory)
                                {
                                    $categories = \VanguardLTE\GameCategory::where('game_id', $game->id)->get();
                                    if( count($categories) && count($superCategories) ) 
                                    {
                                        foreach( $categories as $category ) 
                                        {
                                            $newCategory = $category->replicate();
                                            $newCategory->game_id = $oldGame->id;
                                            $newCategory->category_id = $superCategories[$category->category_id];
                                            $newCategory->save();
                                        }
                                        $this->info('Add game category ' . $game->original_id);

                                    }
                                }

                            }
                        }
                    }
                    $shop->update(['pending' => 0]);
                }
            });

            \Artisan::command('veryfy:bet', function () {
                set_time_limit(0);
                $this->info("Begin pp game verify bet");
                $res = \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::verify_bet();
                $this->info($res['msg']);
            });

            \Artisan::command('gp:genTrend {date=next} {p=0}', function ($date, $p) {
                set_time_limit(0);
                $today = $date;
                if ($date == 'next')
                {
                    $today = date('Y-m-d', strtotime("+1 days"));
                }
                $Oldtoday = date('Y-m-d', strtotime("-7 days"));
                $this->info("Begin genTrend of " . $today);
                $res = \VanguardLTE\Http\Controllers\Web\GameProviders\GamePlayController::generateGameTrend($today, $p);
                $res = \VanguardLTE\Http\Controllers\Web\GameProviders\GamePlayController::processOldTrends($p);
                $this->info("End genTrend");
            });

            \Artisan::command('gp:processTrend {p=0}', function ($p) {
                set_time_limit(0);
                $this->info("Begin processTrend");
                $res = \VanguardLTE\Http\Controllers\Web\GameProviders\GamePlayController::processOldTrends($p);
                $this->info("End processTrend");
            });
            \Artisan::command('xmx:omitted {from} {to}', function ($from, $to) {

                $this->info("Begin xmx rounds : $from ~ $to");

                $this->info("Getting omitted history from " . $from);
                $res = \VanguardLTE\Http\Controllers\Web\GameProviders\XMXController::processGameRound($from, $to,true);
                $this->info("Proceed omitted records count = " . $res[0]);
            });  

            \Artisan::command('kten:omitted {from} {to}', function ($from, $to) {

                $this->info("Begin kten rounds : $from ~ $to");

                while ($from <= $to)
                {
                    $this->info("Getting omitted history from " . $from);
                    $res = \VanguardLTE\Http\Controllers\Web\GameProviders\KTENController::processGameRound($from, true);
                    $this->info("Proceed omitted records count = " . $res[0]);
                    if ($from == $res[1])
                    {
                        $this->info("No more history after " . $from);
                        break;
                    }
                    $from = $res[1];
                }
                $this->info("End kten rounds. last id = " . $from);
            });            
            \Artisan::command('honor:omitted {from} {to}', function ($from, $to) {

                $this->info("Begin kten rounds : $from ~ $to");

                while ($from <= $to)
                {
                    $this->info("Getting omitted history from " . $from);
                    $res = \VanguardLTE\Http\Controllers\Web\GameProviders\HONORController::processGameRound($from, $to);
                    $this->info("Proceed omitted records count = " . $res[0]);
                    if ($from == $res[1])
                    {
                        $this->info("No more history after " . $from);
                        break;
                    }
                    $from = $res[1];
                }
                $this->info("End kten rounds. last id = " . $from);
            }); 
            \Artisan::command('session:cookie {session}', function ($session) {

                $this->info("Begin");
                
                $base64_key = env('APP_KEY');
                $payload = json_decode(base64_decode(urldecode($session)), true);
                $iv = base64_decode($payload['iv']);
                $key = base64_decode(substr($base64_key, 7));
                $sessionId = openssl_decrypt($payload['value'],  'AES-256-CBC', $key, 0, $iv);
                $this->info($sessionId);
                $this->info("End");
            });            

            \Artisan::command('gac:processpending', function () {

                $this->info("Begin");
                $warningtime = date('Y-m-d H:i:s', strtotime("-5 minutes"));
                $pendings = \VanguardLTE\GACTransaction::where(['gactransaction.type'=>1,'gactransaction.status'=>0])->where('date_time', '<', $warningtime)->get();
                $this->info('pending bet count = '. count($pendings));
                $prcCount = 0;
                $cancelCount = 0;
                foreach ($pendings as $bet)
                {
                    $result = \VanguardLTE\Http\Controllers\Web\GameProviders\GACController::processResult($bet->id);
                    if ($result['error'] == false)
                    {
                        $prcCount = $prcCount + 1;
                    }
                    else
                    {
                        \VanguardLTE\Http\Controllers\Web\GameProviders\GACController::cancelResult($bet->id);
                        $cancelCount = $cancelCount + 1;
                    }
                }
                $this->info("Proceed $prcCount, Cancel $cancelCount");
                $this->info("End");
            });         
            \Artisan::command('add:userlist {manager} {userlist} {password}', function ($manager, $userlist, $password) {

                $this->info("Begin");
                $parent = \VanguardLTE\User::where(['username' => $manager, 'role_id' => 3])->first();
                if (!$parent)
                {
                    $this->info("매장을 찾을수 없습니다");
                }
                $role = \jeremykenedy\LaravelRoles\Models\Role::find(1);
                $arr_users = explode(',', $userlist);                
                
                for($k = 0; $k < count($arr_users); $k++){
                    $alreadyuser = \VanguardLTE\User::where(['username' => $arr_users[$k]])->first();
                    if($alreadyuser == null){
                        $data = [];
                        $data['username'] = $arr_users[$k];
                        $data['password'] = $password;
                        $data['status'] = "Active";
                        $data['parent_id'] = $parent->id;
                        $shop = $parent->shop;
                        $check_deals = [
                            'deal_percent',
                            'table_deal_percent',
                            'pball_single_percent',
                            'pball_comb_percent'
                        ];
                        $data['shop_id'] = $shop->id;
                        $data['role_id'] = $role->id;
        
                        $user = \VanguardLTE\User::create($data);
                        $user->detachAllRoles();
                        $user->attachRole($role);
                        // event(new \VanguardLTE\Events\User\Created($user));
        
                        \VanguardLTE\ShopUser::create([
                            'shop_id' => $shop->id, 
                            'user_id' => $user->id
                        ]);
                    }else{
                        $this->info($arr_users[$k] . "유저가 이미 존재합니다.");
                    }
                }
                
                $this->info("End");
            }); 
        }
    }

}

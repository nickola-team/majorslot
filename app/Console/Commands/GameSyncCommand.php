<?php

namespace VanguardLTE\Console\Commands;

use Illuminate\Console\Command;
use VanguardLTE\Http\Controllers\Web\GameProviders\HPCController;
use VanguardLTE\GameLaunch;
use VanguardLTE\User;
use Carbon\Carbon;
use VanguardLTE\StatGame;
use VanguardLTE\Category;
use Cache;
use Exception;
use Log;

class GameSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        foreach (GameLaunchCommand::GAME_PROVIDERS as $provider)
        {
            $object = '\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($provider)  . 'Controller';
            if (!class_exists($object))
            {
                continue;
            }
            $data = call_user_func('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($provider) . 'Controller::processGameRound');
            $this->info('saved ' . $data[0] . ' bet/win records');
            $this->info('new timepoint = ' . $data[1] );

            // $query = 'SELECT * FROM w_provider_info WHERE provider="hpc"';
            // $hpc_info = \DB::select($query);
            // foreach ($hpc_info as $info)
            // {
            //     $master = User::where('id', $info->user_id)->first();
            //     if ($master){
            //         $data = HPCController::processGameRound($master);
            //         $this->info('saved ' . $data[0] . ' bet/win record for ' . $master->username);
            //         $this->info('new timepoint = ' . $data[1] . '  for ' . $master->username);
            //     }

            // }
        }
    }


}

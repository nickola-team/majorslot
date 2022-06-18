<?php

namespace VanguardLTE\Console\Commands;

use Illuminate\Console\Command;
use VanguardLTE\Http\Controllers\Web\GameProviders\HPCController;
use VanguardLTE\GameLaunch;
use VanguardLTE\User;
use VanguardLTE\Game;
use Carbon\Carbon;
use Exception;
use Log;

class HPCLaunch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hpc:launch';

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
        $launchRequests = GameLaunch::where('finished', 0)->orderby('created_at', 'desc')->get();
        $launchRequests = \VanguardLTE\GameLaunch::where(['finished'=> 0, 'provider' => HPCController::HPC_PROVIDER])->orderby('created_at', 'asc')->get();
        $processed_users = [];
        foreach ($launchRequests as $request)
        {
            if (in_array($request->user_id, $processed_users)) //process 1 request per one user
            {
                $this->info('skipping userid=' . $request->user_id . ', id=' . $request->id);
                continue;
            }
            $processed_users[] = $request->user_id;
            $url = HPCController::makelink($request->gamecode, $request->user_id);
            if ($url != null)
            {
                $request->update([
                    'launchUrl' => $url,
                    'finished' => 1,
                ]);
            }
        }

    }
}

<?php

namespace VanguardLTE\Console\Commands;

use Illuminate\Console\Command;
use VanguardLTE\Http\Controllers\Web\GameProviders\TPController;
use VanguardLTE\GameLaunch;
use VanguardLTE\User;
use Carbon\Carbon;
use VanguardLTE\StatGame;
use VanguardLTE\Category;
use Cache;
use Exception;
use Log;

class TheplusSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theplus:sync';

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
        $data = TPController::processGameRound();
        $this->info('saved ' . $data[0] . ' bet/win record.');
        $this->info('new timepoint = ' . $data[1]);
    }


}

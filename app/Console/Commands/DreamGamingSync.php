<?php

namespace VanguardLTE\Console\Commands;

use Illuminate\Console\Command;
use VanguardLTE\Http\Controllers\Web\GameProviders\DGController;
use VanguardLTE\User;
use Carbon\Carbon;
use VanguardLTE\StatGame;
use VanguardLTE\Category;
use Cache;
use Exception;
use Log;

class DreamGamingSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dg:sync';

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
        $data = DGController::processGameRound();
    }


}

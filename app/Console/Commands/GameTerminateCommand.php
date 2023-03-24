<?php

namespace VanguardLTE\Console\Commands;

use Illuminate\Console\Command;
use VanguardLTE\User;
use VanguardLTE\Http\Controllers\Web\GameProviders\HPCController;
use Exception;
use Log;

class GameTerminateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:terminate';

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
        // return;
        $validTimestamp = strtotime("-12 hours");
        $gameUsers = User::whereNotNull('playing_game')->where('played_at', '<', $validTimestamp)->get();
        foreach ($gameUsers as $user)
        {
               //human user
            $b = $user->withdrawAll('gameterminate');
        }
    }
}

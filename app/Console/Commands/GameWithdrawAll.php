<?php

namespace VanguardLTE\Console\Commands;

use Illuminate\Console\Command;
use VanguardLTE\User;
use Exception;
use Log;

class GameWithdrawAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:withdrawAll';

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
        $tpUsers = User::whereNull('playing_game')->where('role_id',1)->get();
        foreach (GameLaunchCommand::GAME_PROVIDERS as $provider)
        {
            if ($provider != 'XMX')
            {
                $total = 0;
                foreach ($tpUsers as $user) {
                    try {
                            $data = call_user_func('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($provider) . 'Controller::withdrawAll', '', $user);
                            if ($data['error'] == false){
                                $total = $total + $data['amount'];       
                                $this->info('Withdraw from ' . $user->username . ' amount = ' . $data['amount'] . ' at ' . $provider);
                            }
                            else
                            {
                                $this->info('Withdraw failed ' . $user->username  . ' at ' . $provider);
                            }
                    }
                    catch (Exception $exception) {
                        $this->info('Exception while withdraw  : ' . $user->id);
                    }
                }
                $this->info('Total withdraw amount = ' . $total . ' at ' . $provider);
            }

        }


    }
}

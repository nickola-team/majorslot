<?php

namespace VanguardLTE\Console\Commands;

use Illuminate\Console\Command;
use VanguardLTE\User;
use VanguardLTE\Http\Controllers\Web\GameProviders\XMXController;
use Exception;
use Log;

class XMXWithdrawAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xmx:withdrawAll';

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
        $total = 0;
        foreach ($tpUsers as $user) {
            try {

                    $data = XMXController::subtractMemberPointAll($user);
                    if ($data['error'] == false){
                        $total = $total + $data['amount'];       
                        $this->info('Withdraw from ' . $user->username . ' amount = ' . $data['amount']);
                    }
                    else
                    {
                        $this->info('Withdraw failed ' . $user->username );
                    }
            }
            catch (Exception $exception) {
                $this->info('Exception while withdraw xmx : ' . $user->id);
            }
        }
        $this->info('Total withdraw amount = ' . $total);

    }
}

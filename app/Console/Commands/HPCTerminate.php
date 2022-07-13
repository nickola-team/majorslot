<?php

namespace VanguardLTE\Console\Commands;

use Illuminate\Console\Command;
use VanguardLTE\User;
use VanguardLTE\Http\Controllers\Web\GameProviders\HPCController;
use Exception;
use Log;

class HPCTerminate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hpc:terminate';

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
        $hpcUsers = User::where('playing_game', 'like', HPCController::HPC_PROVIDER . '%')->get();
        
        foreach ($hpcUsers as $user) {
            try {
                if ($user->playing_game == HPCController::HPC_PROVIDER . 'exit')
                {
                    $data = HPCController::withdrawAll($user);
                    Log::channel('monitor_game')->info(self::HPC_PROVIDER . ' : ' . $user->id . ' close game. balance = ' . $data['amount']);
                    if ($data['error'] == false){
                        User::lockforUpdate()
                            ->where('id', $user->id)
                            ->update([
                                'balance' => $data['amount'], 
                                'playing_game' => null
                            ]);
                    }
                    else
                    {
                        User::lockforUpdate()
                        ->where('id', $user->id)
                        ->update([
                            'playing_game' => null
                        ]);
                    }
                }
                else
                {
                    if ( time() - $user->played_at > 600)
                    {
                        //?????
                    }
                }
            }
            catch (Exception $exception) {
                Log::info('Exception while terminating hpc : ' . $user->id);
            }
        }

    }
}

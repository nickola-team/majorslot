<?php

namespace VanguardLTE\Console\Commands;

use Illuminate\Console\Command;
use VanguardLTE\User;
use VanguardLTE\Http\Controllers\Web\GameProviders\TPController;
use Exception;
use Log;

class TheplusTerminate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theplus:terminate';

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
        $tpUsers = User::where('playing_game', 'like', TPController::TP_PROVIDER . '%')->get();
        
        foreach ($tpUsers as $user) {
            try {
                if ($user->playing_game == TPController::TP_PROVIDER . 'exit')
                {
                    $data = TPController::withdrawAll($user->id);
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
                Log::info('Exception while terminating theplus : ' . $user->id);
            }
        }

    }
}

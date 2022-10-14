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
        foreach (GameLaunchCommand::GAME_PROVIDERS as $provider)
        {    
            $object = '\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($provider)  . 'Controller';
            if (!class_exists($object))
            {
                continue;
            }
            $gameUsers = User::where('playing_game', 'like', strtolower($provider) . '%')->get();
            
            foreach ($gameUsers as $user) {
                try {
                    if (str_contains($user->playing_game ,'exit'))
                    {
                        $extra = explode('_', $user->playing_game);
                        if (count($extra) >= 2){
                            $data = call_user_func('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($provider) . 'Controller::withdrawAll', $extra[1], $user);
                            Log::channel('monitor_game')->info($provider .'-' .$extra[1]. ' : ' . $user->id . ' close game. balance = ' . $data['amount']);
                        }
                        else
                        {
                            $data = call_user_func('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($provider) . 'Controller::withdrawAll', $user);
                            Log::channel('monitor_game')->info($provider . ' : ' . $user->id . ' close game. balance = ' . $data['amount']);
                        }
                        
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
                            Log::channel('monitor_game')->info($provider . ' : ' . $user->id . ' close game error. msg = ' . (isset($data['msg'])?$data['msg']:''));
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
                    Log::info('Exception while terminating game : ' . $user->id . $exception->getMessage());
                }
            }
        }

    }
}

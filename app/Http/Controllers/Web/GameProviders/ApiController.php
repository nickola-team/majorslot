<?php 
namespace VanguardLTE\Http\Controllers\Web\GameProviders
{
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Log;
    class ApiController extends \VanguardLTE\Http\Controllers\Controller
    {    
        public function userSignal($provider, \Illuminate\Http\Request $request)
        {
            $user = \VanguardLTE\User::lockForUpdate()->where('id',auth()->id())->first();
            if (!$user)
            {
                return response()->json([
                    'error' => '1',
                    'description' => 'unlogged']);
            }
            if ($user->playing_game != strtolower($provider))
            {
                return response()->json([
                    'error' => '1',
                    'description' => 'Idle TimeOut']);
            }

            if ($request->name == 'exitGame')
            {
                Log::channel('monitor_game')->info(strtoupper($provider) . ' : ' . $user->id . ' is exiting game');
                $user->update([
                    'playing_game' => strtolower($provider) . 'exit',
                    'played_at' => time()
                ]);
            }
            else
            {
                $balance = call_user_func('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($provider) . 'Controller::getUserBalance', $user);
                // $balance = HPCController::getUserBalance($user);
                if ($balance >= 0)
                {
                    $user->update([
                        'balance' => $balance,
                        'played_at' => time()
                    ]);
                }
            }
            return response()->json([
                'error' => '0',
                'description' => 'OK']);
        }
    }

}

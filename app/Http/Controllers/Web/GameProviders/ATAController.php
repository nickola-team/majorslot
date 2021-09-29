<?php 
namespace VanguardLTE\Http\Controllers\Web\GameProviders
{
    use Illuminate\Support\Facades\Http;
    class ATAController extends \VanguardLTE\Http\Controllers\Controller
    {
        /*
        * UTILITY FUNCTION
        */

        public function checkuid($uid)
        {
            $record = \VanguardLTE\ATATransaction::where('uid',$uid)->get()->first();
            return $record;
        }
        public function generateCode($limit){
            $code = 0;
            for($i = 0; $i < $limit; $i++) { $code .= mt_rand(0, 9); }
            return $code;
        }


        public function microtime_string()
        {
            $microstr = sprintf('%.4f', microtime(TRUE));
            $microstr = str_replace('.', '', $microstr);
            return $microstr;
        }
        /*
        * FROM ATA, BACK API
        */

        public function endpoint($service, \Illuminate\Http\Request $request)
        {
            $response = [];
            switch ($service)
            {
                case 'playerinfo.json':
                    $response = $this->playerinfo($request);
                    break;
                case 'wager.json':
                    $response = $this->wager($request);
                    break;
                default:
                    $response = ['uid' => $uid, 'error' => ['code' => "FATAL_ERROR"]];
            }

            return response()->json($response, 200);
        }

        public function playerinfo($request)
        {
            $org = $request->org;
            $sessiontoken = $request->sessiontoken;

            $user = \VanguardLTE\User::lockForUpdate()->where('api_token',$sessiontoken)->first();
            if (!$user || !$user->hasRole('user')){
                return [
                    'code' => 1000,
                    'msg' => 'Not found user',
                ];
            }

            return [
                'code' => 0,
                'data' => [
                    'gender' => 'M',
                    'playerId' => $user->id,
                    'organization' => config('app.ata_org'),
                    'balance' => $user->balance,
                    'currency' => 'KRW',
                    'applicableBonus' => 0,
                    'homeCurrency' => 'KRW',
                    'country' => 'ko',
                ],
                'msg' => 'OK'
            ];
        }

        public function wager($request)
        {
           
        }

        /*
        * FROM CONTROLLER, API
        */
        
        public static function getgamelist($href)
        {
            $gameList = \Illuminate\Support\Facades\Redis::get($href.'list');
            if ($gameList)
            {
                $games = json_decode($gameList, true);
                if ($games!=null && count($games) > 0){
                    return $games;
                }
            }

            $data = [
                'topOrg' => config('app.ata_toporg'),
                'org' => config('app.ata_org'),
                'sign' => config('app.ata_sign'),
                'type' => $href
            ];
            $resultdata = null;
            try {
                $response = Http::asForm()->post(config('app.ata_api') . '/getGameList', $data);
                if (!$response->ok())
                {
                    return [];
                }
                $resultdata = $response->json();
            }
            catch (Exception $ex)
            {
                return [];
            }

            $gameList = [];

            foreach ($resultdata as $game)
            {
                if ($game['GameType'] == 'Slot game' && $game['GameStatus'] == 1){ // need to check the string
                    $gameList[] = [
                        'provider' => 'ata',
                        'gamecode' => $game['GameId'],
                        'name' => preg_replace('/[_\s]+/', '', $game['GameName']),
                        'title' => __('gameprovider.'.$game['GameName']),
                        'icon' => $game['GameIcon'],
                    ];
                }
            }
            \Illuminate\Support\Facades\Redis::set($href.'list', json_encode($gameList));
            return $gameList;
            
        }

        public static function makegamelink($gamecode)
        {
            $detect = new \Detection\MobileDetect();
            $user = auth()->user();
            if ($user == null)
            {
                return null;
            }
            $key = [
                'loginname' => $user->id,
                'key' => $user->api_token,
                'currency' => 'KRW',
                'lang' => 'ko',
                'gameid' => $gamecode,
                'org' => config('app.ata_org'),
                'home' => url('/'),
                'fullscreen' => 'no',
                'channel' => ($detect->isMobile() || $detect->isTablet())?'mobile':'pc',
            ];

            $resultdata = null;
            try {
                $response = Http::asForm()->post(config('app.ata_api') . '/launchClient.html', $data);
                if (!$response->ok())
                {
                    return null;
                }
                $resultdata = $response->json();
            }
            catch (Exception $ex)
            {
                return null;
            }
            if ($resultdata)
            {
                if ($resultdata['code'] == 0)
                {
                    return $result['data']['launchurl'];
                }
            }
            return null;
        }

        public static function getgamelink($gamecode)
        {
            $url = ATAController::makegamelink($gamecode);
            if ($url)
            {
                return ['error' => false, 'data' => ['url' => $url]];
            }
            return ['error' => true, 'msg' => '로그인하세요'];            
        }

    }

}

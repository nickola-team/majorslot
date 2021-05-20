<?php 
namespace VanguardLTE\Http\Controllers\Web\GameProviders
{
    use Illuminate\Support\Facades\Http;
    class PNGController extends \VanguardLTE\Http\Controllers\Controller
    {
        /*
        * UTILITY FUNCTION
        */

        public function microtime_string()
        {
            $microstr = sprintf('%.4f', microtime(TRUE));
            $microstr = str_replace('.', '', $microstr);
            return $microstr;
        }

        public static function gamecodetoname($code)
        {
            $gamelist = PNGController::getgamelist('png');
            $gamename = $code;
            if ($gamelist)
            {
                foreach($gamelist as $game)
                {
                    if ($game['gamecode'] == $code)
                    {
                        $gamename = $game['name'];
                        break;
                    }
                }
            }
            return $gamename;
        }

        /*
        * FROM Play'n GO, BACK API
        */

        public function endpoint($service, \Illuminate\Http\Request $request)
        {
            if (!$request->isXml()) {
                return response()->xml(['status' => 4], 200, [], $service);

            }
            // do something
            $data = $request->xml();
            $accessToken = $data['accessToken'];
            if ($accessToken != config('app.png_access_token'))
            {
                //token is not valid
                return response()->xml(['status' => 4], 200, [], $service);
            }

            \DB::beginTransaction();

            switch ($service)
            {
                case 'authenticate':
                    $response = $this->auth($data);
                    break;
                case 'reserve':
                    $response = $this->reserve($data);
                    break;
                case 'release':
                    $response = $this->release($data);
                    break;
                case 'balance':
                    $response = $this->balance($data);
                    break;
                case 'cancelReserve':
                    $response = $this->cancelReserve($data);
                    break;
                default:
                    $response = ['status' => 4];
            }
            if (isset($data['transactionId']))
            {
                $data['service'] = $service;
                \VanguardLTE\PNGTransaction::create([
                    'transactionId' => $data['transactionId'], 
                    'timestamp' => $this->microtime_string(),
                    'data' => json_encode($data)
                ]);
            }
            \DB::commit();
            return response()->xml($response, 200, [], $service);
        }

        public function auth($data)
        {
            $token = $data['username'];
            $response = [
                'externalId' => -1,
                'statusCode' => 0,
                'statusMessage' => 'OK',
                'userCurrency' => 'KRW',
                'nickname' => '',
                'country' => 'KR',
                'birthdate' => '1970-01-01',
                'registration' => '2010-05-05',
                'language' => 'ko_KR',
                'affiliateId' => '',
                'real' => 0,
                'externalGameSessionId' => '',
            ];

            $user = \VanguardLTE\User::Where('api_token',$token)->get()->first();
            if (!$user || !$user->hasRole('user')){
                $response['statusCode'] = 1;
                $response['statusMessage'] = 'NOUSER';
                return $response;
            }

            $response['externalId'] = $user->id;
            $response['nickname'] = $user->username;
            $response['real'] = $user->balance;
            return $response;
        }

        public function reserve($data)
        {
            $userId = $data['externalId'];
            $transactionId = $data['transactionId'];
            $bet = $data['real'];
            $gameId = $data['gameId'];

            $response = [
                'externalTransactionId' => $transactionId,
                'real' => 0,
                'statusCode' => 0,
                'statusMessage' => 'OK',
            ];

            $user = \VanguardLTE\User::find($userId);
            if (!$user || !$user->hasRole('user')){
                $response['statusCode'] = 1;
                $response['statusMessage'] = 'NOUSER';
                return $response;
            }

            if ($user->balance < $bet)
            {
                $response['statusCode'] = 7;
                $response['statusMessage'] = 'NOTENOUGHMONEY';
                return $response;
            }
            
            $user->balance = floatval(sprintf('%.4f', $user->balance - floatval($bet)));
            $user->save();

            $response['real'] = $user->balance;

            \VanguardLTE\StatGame::create([
                'user_id' => $user->id, 
                'balance' => floatval($user->balance), 
                'bet' => floatval($bet), 
                'win' => 0, 
                'game' => $this->gamecodetoname($gameId) . '_png', 
                'percent' => 0, 
                'percent_jps' => 0, 
                'percent_jpg' => 0, 
                'profit' => 0, 
                'denomination' => 0, 
                'shop_id' => $user->shop_id
            ]);

            return $response;
        }

        public function release($data)
        {
            $userId = $data['externalId'];
            $transactionId = $data['transactionId'];
            $win = $data['real'];
            $type = $data['type'];
            $gameId = $data['gameId'];

            $response = [
                'externalTransactionId' => $transactionId,
                'real' => 0,
                'statusCode' => 0,
            ];

            $user = \VanguardLTE\User::find($userId);
            if (!$user || !$user->hasRole('user')){
                $response['statusCode'] = 1;
                return $response;
            }

            $user->balance = floatval(sprintf('%.4f', $user->balance + floatval($win)));
            $user->save();
            $response['real'] = $user->balance;

            \VanguardLTE\StatGame::create([
                'user_id' => $user->id, 
                'balance' => floatval($user->balance), 
                'bet' => 0, 
                'win' => floatval($win), 
                'game' => $this->gamecodetoname($gameId) . '_png' . $type==0?'':' FG', 
                'percent' => 0, 
                'percent_jps' => 0, 
                'percent_jpg' => 0, 
                'profit' => 0, 
                'denomination' => 0, 
                'shop_id' => $user->shop_id
            ]);

            return $response;

        }

        public function balance($data)
        {
            $userId = $data['externalId'];
            $response = [
                'real' => 0,
                'statusCode' => 0,
            ];

            $user = \VanguardLTE\User::find($userId);
            if (!$user || !$user->hasRole('user')){
                $response['statusCode'] = 1;
                return $response;
            }

            $response['real'] = $user->balance;
            return $response;

        }

        public function cancelReserve($data)
        {
            $userId = $data['externalId'];
            $transactionId = $data['transactionId'];
            $bet = $data['real'];
            $gameId = $data['gameId'];

            $response = [
                'externalTransactionId' => $transactionId,
                'statusCode' => 0,
            ];

            $user = \VanguardLTE\User::find($userId);
            if (!$user || !$user->hasRole('user')){
                $response['statusCode'] = 1;
                return $response;
            }

            $transaction = \VanguardLTE\PNGTransaction::where('transactionId',  $transactionId)->first();
            if (!$transaction)
            {
                $response['statusCode'] = 2; //internal server error
                return $response;
            }

            $user->balance = floatval(sprintf('%.4f', $user->balance + floatval($bet)));
            $user->save();

            \VanguardLTE\StatGame::create([
                'user_id' => $user->id, 
                'balance' => floatval($user->balance), 
                'bet' => 0, 
                'win' => floatval($bet), 
                'game' => $this->gamecodetoname($gameId) . '_png refund', 
                'percent' => 0, 
                'percent_jps' => 0, 
                'percent_jpg' => 0, 
                'profit' => 0, 
                'denomination' => 0, 
                'shop_id' => $user->shop_id
            ]);

            return $response;
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
                return $games;
            }
            return null;

            $data = [
                'api_token' => config('app.bng_api_token'),
            ];
            $reqbody = json_encode($data);
            $response = Http::withHeaders([
                'Security-Hash' => PNGController::calcSecurityHash($reqbody)
                ])->withBody($reqbody, 'text/plain')->post(config('app.bng_game_server') . config('app.bng_project_name') . '/api/v1/provider/list/');
            if (!$response->ok())
            {
                return [];
            }
            $providerId = 1;
            $data = $response->json();
            $gameList = [];

            if (isset($data['items'])){
                foreach ($data['items'] as $item)
                {
                    if ($item['provider_name'] == $href)
                    {
                        $providerId = $item['provider_id'];
                        break;
                    }
                }
            }

            $data1 = [
                'api_token' => config('app.bng_api_token'),
                'provider_id' => $providerId
            ];
            $reqbody = json_encode($data1);
            $response = Http::withHeaders([
                'Security-Hash' => PNGController::calcSecurityHash($reqbody)
                ])->withBody($reqbody, 'text/plain')->post(config('app.bng_game_server') . config('app.bng_project_name') . '/api/v1/game/list/');
            
            if (!$response->ok())
            {
                return [];
            }
            $data1 = $response->json();
            foreach ($data1['items'] as $game)
            {
                if ($game['type'] == "SLOT")
                {
                    $gameList[] = [
                        'provider' => 'bng',
                        'gamecode' => $game['game_id'],
                        'name' => preg_replace('/[_\s]+/', '', $game['game_name']),
                        'title' => __('gameprovider.'.$game['i18n']['en']['title']),
                        'icon' => $game['i18n']['en']['banner_path'],
                        'demo' => PNGController::makegamelink($game['game_id'], "fun")
                    ];
                }
            }
            \Illuminate\Support\Facades\Redis::set($href.'list', json_encode($gameList));
            return $gameList;
            
        }

        public static function makegamelink($gamecode, $mode)
        {
            $detect = new \Detection\MobileDetect();
            $user = auth()->user();
            if ($user == null)
            {
                return null;
            }
            $key = [
                'token' => $user->api_token,
                'game' => $gamecode,
                'ts' => time(),
                'lang' => 'ko',
                'platform' => ($detect->isMobile() || $detect->isTablet())?'mobile':'desktop',
            ];
            if ($mode == "fun")
            {
                $key['wl'] = 'demo';
            }
            else
            {
                $key['wl'] = config('app.bng_wl');
            }
            $str_params = implode('&', array_map(
                function ($v, $k) {
                    return $k.'='.$v;
                }, 
                $key,
                array_keys($key)
            ));
            return $url = config('app.bng_game_server') . config('app.bng_project_name') . '/game.html?'.$str_params;
        }

        public static function getgamelink($gamecode)
        {
            $url = PNGController::makegamelink($gamecode, "real");
            if ($url)
            {
                return ['error' => false, 'data' => ['url' => $url]];
            }
            return ['error' => true, 'msg' => '로그인하세요'];
            
        }

    }

}

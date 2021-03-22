<?php 
namespace VanguardLTE\Http\Controllers\Web\GameProviders
{
    use Illuminate\Support\Facades\Http;
    class BNGController extends \VanguardLTE\Http\Controllers\Controller
    {
        /*
        * UTILITY FUNCTION
        */
        public static function calcSecurityHash($body)
        {
            $key = config('app.bng_wallet_sign_key');
            return hash_hmac('sha256', utf8_encode($body), utf8_encode($key));
        }

        public function checkuid($uid)
        {
            $record = \VanguardLTE\BNGTransaction::Where('uid',$uid)->get()->first();
            return $record;
        }
        public function generateCode($limit){
            $code = 0;
            for($i = 0; $i < $limit; $i++) { $code .= mt_rand(0, 9); }
            return $code;
        }

        public function gamecodetoname($code)
        {
            $gameList = \Illuminate\Support\Facades\Redis::get('bnglist');
            if (!$gameList)
            {
                $gameList = \BNGController::getgamelist();
            }
            $gamename = $code;
            if ($gameList)
            {
                $games = json_decode($gameList, true);
                foreach($games as $game)
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

        public function microtime_string()
        {
            list($usec, $sec) = explode(" ", microtime());
            $microstr =  strval(((float)$usec + (float)$sec));
            $microstr = str_replace('.', '', $microstr);
            return $microstr;
        }
        /*
        * FROM Booongo, BACK API
        */

        public function betman(\Illuminate\Http\Request $request)
        {
            $data = json_decode($request->getContent(), true);
            $name = $data['name'];
            $uid = $data['uid'];
            $c_at = $data['c_at'];
            $sent_at = $data['sent_at'];
            $session = $data['session'];
            $args = $data['args'];

            $record = $this->checkuid($uid);
            if ($record)
            {
                $securityhash = BNGController::calcSecurityHash($record->response);
                return response($record->response, 200)->withHeaders([
                    'Content-Type' => 'application/json',
                    'Security-Hash' => $securityhash
                ]);
            }

            $response = [];
            switch ($name)
            {
                case 'login':
                    $response = $this->login($data);
                    break;
                case 'transaction':
                    $response = $this->transaction($data);
                    break;
                case 'rollback':
                    $response = $this->rollback($data);
                    break;
                case 'getbalance':
                    $response = $this->getbalance($data);
                    break;
                case 'logout':
                    $response = $this->logout($data);
                    break;
                default:
                    $response = ['uid' => $uid, 'error' => ['code' => "FATAL_ERROR"]];
            }

            $transaction = \VanguardLTE\BNGTransaction::create([
                'uid' => $uid, 
                'timestamp' => $this->microtime_string(),
                'data' => $request->getContent(),
                'response' => json_encode($response)
            ]);
            $securityhash = BNGController::calcSecurityHash(json_encode($response));
            if (isset($response['error']) && !isset($response['balance']))
            {
                return response()->json($response, 503)->header('Security-Hash', $securityhash);
            }
            return response()->json($response, 200)->header('Security-Hash', $securityhash);
        }

        public function login($data)
        {
            $uid = $data['uid'];
            $token = $data['token'];
            $user = \VanguardLTE\User::Where('username',$token)->get()->first();
            if (!$user || !$user->hasRole('user')){
                return [
                    'uid' => $uid,
                    'error' => [ 'code' => 'INVALID_TOKEN']];
            }

            return [
                'uid' => $uid,
                'player' => [
                    'id' => strval($user->id),
                    'brand' => 'major',
                    'currency' => 'KRW',
                    'mode' => 'REAL',
                    'is_test' => false,
                ],
                'balance' => [
                    'value' => strval($user->balance),
                    'version' => time()
                ],
                'tag' => ''
            ];
        }

        public function transaction($data)
        {
            $uid = $data['uid'];
            $args = $data['args'];
            $userId = $args['player']['id'];
            $user = \VanguardLTE\User::find($userId);
            if (!$user || !$user->hasRole('user')){
                return [
                    'uid' => $uid,
                    'error' => [ 'code' => 'FATAL_ERROR']];
            }
            
            if (!isset($args['bonus']) && $args['bet'])
            {
                if ($user->balance < $args['bet'])
                {
                    return [
                        'uid' => $uid,
                        'balance' => [
                            'value' => strval($user->balance),
                            'version' => time()
                        ],
                        'error' => [ 'code' => 'FUNDS_EXCEED']];
                }

                $user->balance = floatval(sprintf('%.4f', $user->balance - floatval($args['bet'])));
            }
            if ($args['win'])
            {
                $user->balance = floatval(sprintf('%.4f', $user->balance + floatval($args['win'])));
            }

            $user->save();

            return [
                'uid' => $uid,
                'balance' => [
                    'value' => strval($user->balance),
                    'version' => time()
                ],
            ];
        }

        public function rollback($data)
        {
            $uid = $data['uid'];
            $args = $data['args'];
            $transaction_uid = $args['transaction_uid'];
            $userId = $args['player']['id'];
            $user = \VanguardLTE\User::find($userId);
            if (!$user || !$user->hasRole('user')){
                return [
                    'uid' => $uid,
                    'error' => [ 'code' => 'FATAL_ERROR']];
            }

            $record = $this->checkuid($transaction_uid);
            if (!$record)
            {
                return [
                    'uid' => $uid,
                    'balance' => [
                        'value' => strval($user->balance),
                        'version' => time()
                    ],
                ];
            }
            if ($record->refund > 0)
            {
                return [
                    'uid' => $uid,
                    'balance' => [
                        'value' => strval($user->balance),
                        'version' => time()
                    ],
                ];
            }

            if ($args['bet'])
            {
                $user->balance = floatval(sprintf('%.4f', $user->balance + floatval($args['bet'])));
            }

            if ($args['win'])
            {
                if ($user->balance < $args['win'])
                {
                    return [
                        'uid' => $uid,
                        'balance' => [
                            'value' => strval($user->balance),
                            'version' => time()
                        ],
                        'error' => [ 'code' => 'FUNDS_EXCEED']];
                }
                $user->balance = floatval(sprintf('%.4f', $user->balance - floatval($args['win'])));
            }
            $user->save();
            $record->refund = 1;
            $record->save();
            return [
                'uid' => $uid,
                'balance' => [
                    'value' => strval($user->balance),
                    'version' => time()
                ],
            ];
            

        }

        public function getbalance($data)
        {
            $uid = $data['uid'];
            $args = $data['args'];
            $userId = $args['player']['id'];
            $user = \VanguardLTE\User::find($userId);
            if (!$user || !$user->hasRole('user')){
                return [
                    'uid' => $uid,
                    'error' => [ 'code' => 'FATAL_ERROR']];
            }

            return [
                'uid' => $uid,
                'balance' => [
                    'value' => strval($user->balance),
                    'version' => time()
                ],
            ];

        }

        public function logout($data)
        {
            $uid = $data['uid'];
            return [
                'uid' => $uid,
            ];
        }
        /*
        * FROM CONTROLLER, API
        */
        
        public static function getgamelist()
        {
            $gameList = \Illuminate\Support\Facades\Redis::get('bnglist');
            if ($gameList)
            {
                $games = json_decode($gameList, true);
                return $games;
            }

            $data = [
                'api_token' => config('app.bng_api_token'),
            ];
            $reqbody = json_encode($data);
            $response = Http::withHeaders([
                'Security-Hash' => BNGController::calcSecurityHash($reqbody)
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
                    $data1 = [
                        'api_token' => config('app.bng_api_token'),
                        'provider_id' => $item['provider_id']
                    ];
                    $reqbody = json_encode($data1);
                    $response = Http::withHeaders([
                        'Security-Hash' => BNGController::calcSecurityHash($reqbody)
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
                                'name' => preg_replace('/\s+/', '', $game['game_name']),
                                'title' => $game['i18n']['en']['title'],
                                'icon' => $game['i18n']['en']['banner_path'],
                            ];
                        }
                    }
                    \Illuminate\Support\Facades\Redis::set('bnglist', json_encode($gameList));
                }
            }
            return $gameList;


            
        }

        public static function getgamelink($gamecode)
        {
            $detect = new \Detection\MobileDetect();
            $key = [
                'token' => auth()->user()->username,
                'game' => $gamecode,
                'ts' => time(),
                'lang' => 'ko',
                'platform' => ($detect->isMobile() || $detect->isTablet())?'mobile':'desktop',
            ];
            $str_params = implode('&', array_map(
                function ($v, $k) {
                    return $k.'='.$v;
                }, 
                $key,
                array_keys($key)
            ));
            $url = config('app.bng_game_server') . config('app.bng_project_name') . '/game.html?'.$str_params;
            return ['error' => false, 'data' => ['url' => $url]];
        }
    }

}

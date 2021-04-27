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
            return response()->json($response, 200)->header('Security-Hash', $securityhash);
        }

        public function login($data)
        {
            $uid = $data['uid'];
            $token = $data['token'];
            $user = \VanguardLTE\User::Where('api_token',$token)->get()->first();
            if (!$user || !$user->hasRole('user')){
                return [
                    'uid' => $uid,
                    'error' => [ 'code' => 'INVALID_TOKEN']];
            }

            $is_test = str_contains($user->username, 'testfor');

            return [
                'uid' => $uid,
                'player' => [
                    'id' => strval($user->id),
                    'brand' => 'major',
                    'currency' => 'KRW',
                    'mode' => 'REAL',
                    'is_test' => $is_test,
                ],
                'balance' => [
                    'value' => strval($user->balance),
                    'version' => time()
                ],
                'c_at' => date(DATE_ISO8601),
                'sent_at' => date(DATE_ISO8601),
                'tag' => ''
            ];
        }

        public function transaction($data)
        {
            $uid = $data['uid'];
            $args = $data['args'];
            $gamename = $data['game_name'];
            $userId = $args['player']['id'];
            $user = \VanguardLTE\User::find($userId);
            if (!$user || !$user->hasRole('user')){
                return [
                    'uid' => $uid,
                    'error' => [ 'code' => 'FATAL_ERROR']];
            }
            $bet = 0;
            $win = 0;
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
                $bet = floatval($args['bet']);
            }
            if ($args['win'])
            {
                $user->balance = floatval(sprintf('%.4f', $user->balance + floatval($args['win'])));
                $win = floatval($args['win']);
            }

            $user->save();

            \VanguardLTE\StatGame::create([
                'user_id' => $user->id, 
                'balance' => floatval($user->balance), 
                'bet' => $bet, 
                'win' => $win, 
                'game' => preg_replace('/[_\s]+/', '', $gamename) . '_bng',
                'percent' => 0, 
                'percent_jps' => 0, 
                'percent_jpg' => 0, 
                'profit' => 0, 
                'denomination' => 0, 
                'shop_id' => $user->shop_id
            ]);

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
            $gamename = $data['game_name'];
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
            $bet = 0;
            $win = 0;

            if ($args['bet'])
            {
                $user->balance = floatval(sprintf('%.4f', $user->balance + floatval($args['bet'])));
                $bet = floatval($args['bet']);
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
                $win = floatval($args['win']);
            }

            \VanguardLTE\StatGame::create([
                'user_id' => $user->id, 
                'balance' => floatval($user->balance), 
                'bet' => $win, 
                'win' => $bet, 
                'game' => preg_replace('/[_\s]+/', '', $gamename) . '_bng RB',
                'percent' => 0, 
                'percent_jps' => 0, 
                'percent_jpg' => 0, 
                'profit' => 0, 
                'denomination' => 0, 
                'shop_id' => $user->shop_id
            ]);
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
        
        public static function getgamelist($href)
        {
            $gameList = \Illuminate\Support\Facades\Redis::get($href.'list');
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
                        'name' => preg_replace('/[_\s]+/', '', $game['game_name']),
                        'title' => __('gameprovider.'.$game['i18n']['en']['title']),
                        'icon' => $game['i18n']['en']['banner_path'],
                        'demo' => BNGController::makegamelink($game['game_id'], "fun")
                    ];
                }
            }
            \Illuminate\Support\Facades\Redis::set($href.'list', json_encode($gameList));
            return $gameList;
            
        }

        public static function makegamelink($gamecode, $mode)
        {
            $detect = new \Detection\MobileDetect();
            $key = [
                'token' => auth()->user()->api_token,
                'game' => $gamecode,
                'ts' => time(),
                'lang' => 'ko',
                'platform' => ($detect->isMobile() || $detect->isTablet())?'mobile':'desktop',
            ];
            if ($mode == "fun")
            {
                $key['wl'] = 'demo';
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
            $url = BNGController::makegamelink($gamecode, "real");
            return ['error' => false, 'data' => ['url' => $url]];
        }

        public static function bonuscreate($data)
        {
            $requestdata = [
                'api_token' => config('app.bng_api_token'),
                'mode' => 'REAL',
                'campaign' => $data['campaign'],
                'game_id' => strval($data['game_id']),
                'bonus_type' => 'FLEXIBLE_FREEBET',
                'currency' => 'KRW',
                'total_bet' => strval($data['total_bet']),
                'start_date' => null,
                'end_date' => $data['end_date'],
                'bonuses' => [
                    [
                        'player_id' => $data['player_id'],
                        'ext_bonus_id' => '',
                        'brand' => 'major'
                    ]
                ]
            ];

            $reqbody = json_encode($requestdata);
            $response = Http::withHeaders([
                'Security-Hash' => BNGController::calcSecurityHash($reqbody)
                ])->withBody($reqbody, 'text/plain')->post(config('app.bng_game_server') . config('app.bng_project_name') . '/api/v1/bonus/create/');
            if (!$response->ok())
            {
                return null;
            }
            $res = $response->json();
            return $res['items'][0];
        }

        public static function bonuscancel($bonus_id)
        {
            $requestdata = [
                'api_token' => config('app.bng_api_token'),
                'bonus_id' => intval($bonus_id)
            ];

            $reqbody = json_encode($requestdata);
            $response = Http::withHeaders([
                'Security-Hash' => BNGController::calcSecurityHash($reqbody)
                ])->withBody($reqbody, 'text/plain')->post(config('app.bng_game_server') . config('app.bng_project_name') . '/api/v1/bonus/delete/');
            if (!$response->ok())
            {
                return null;
            }
            $res = $response->json();
            return $res;
        }

        public static function bonuslist()
        {
            
            $requestdata = [
                'api_token' => config('app.bng_api_token'),
                'mode' => 'REAL',
                'campaign' => null,
                'game_id' => null,
                'player_id' => null,
                'brand' => 'major',
                'include_expired' => true,
                'fetch_size' => 100,
                'fetch_state' => null
            ];

            $reslist = [];
            $bcontinue = true;
            while ($bcontinue) {
                $reqbody = json_encode($requestdata);
                $response = Http::withHeaders([
                    'Security-Hash' => BNGController::calcSecurityHash($reqbody)
                    ])->withBody($reqbody, 'text/plain')->post(config('app.bng_game_server') . config('app.bng_project_name') . '/api/v1/bonus/list/');
                if (!$response->ok())
                {
                    break;
                }
                $res = $response->json();
                $reslist = array_merge($reslist, $res['items']);
                if ($res['fetch_state'] === null)
                {
                    $bcontinue = false;
                }
                else
                {
                    $requestdata['fetch_state'] = $res['fetch_state'];
                }
            }
            return $reslist;
        }
    }

}

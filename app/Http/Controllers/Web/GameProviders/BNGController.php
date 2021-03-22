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
                return response($record->response, 200)->header('Content-Type', 'application/json');
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
                    $response = ['uid' => $uid, 'error' => ['code' => "invalid command name"]];
            }

            $transaction = \VanguardLTE\BNGTransaction::create([
                'uid' => $uid, 
                'timestamp' => $this->microtime_string(),
                'data' => $request->getContent(),
                'response' => json_encode($response)
            ]);
            $securityhash = BNGController::calcSecurityHash(json_encode($response));
            if (isset($response['error']))
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
                    'id' => $user->id,
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
                    'error' => [ 'code' => 'INVALID_USER']];
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
                    'error' => [ 'code' => 'INVALID_USER']];
            }

            $record = $this->checkuid($transaction_uid);
            if (!$record)
            {
                return [
                    'uid' => $uid,
                    'error' => [ 'code' => 'TRANSACTION NOT FOUND']];
            }
            if ($record->refund > 0)
            {
                return [
                    'uid' => $uid,
                    'error' => [ 'code' => 'ALREADY REFUNDED']];
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
                    'error' => [ 'code' => 'INVALID_USER']];
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
            $data = [
                'secureLogin' => config('app.ppsecurelogin'),
            ];
            $data['hash'] = PPController::calcHash($data);
            $response = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded'
                ])->get(config('app.ppapi') . '/getCasinoGames/', $data);
            if (!$response->ok())
            {
                return [];
            }
            $data = $response->json();
            if ($data['error'] == "0"){
                $gameList = [];
                foreach ($data['gameList'] as $game)
                {
                    if ($game['gameTypeID'] == "vs")
                    {
                        $gameList[] = [
                            'provider' => 'pp',
                            'gamecode' => $game['gameID'],
                            'name' => preg_replace('/\s+/', '', $game['gameName']),
                            'title' => $game['gameName'],
                            'icon' => config('app.ppgameserver') . '/game_pic/rec/325/'. $game['gameID'] . '.png',
                            'demo' => 'https://demogamesfree-asia.pragmaticplay.net/gs2c/openGame.do?gameSymbol='.$game['gameID'].'&lang=ko&cur=KRW&lobbyURL='. \URL::to('/')
                        ];
                    }
                }
                \Illuminate\Support\Facades\Redis::set('pplist', json_encode($gameList));
                return $gameList;
            }
            return [];
        }

        public static function getgamelink($gamecode)
        {
            $detect = new \Detection\MobileDetect();
            $key = [
                'token' => auth()->user()->username,
                'symbol' => $gamecode,
                'language' => 'ko',
                'technology' => 'H5',
                'platform' => ($detect->isMobile() || $detect->isTablet())?'MOBILE':'WEB',
                'cashierUrl' => \URL::to('/'),
                'lobbyUrl' => \URL::to('/'),
            ];
            $str_params = implode('&', array_map(
                function ($v, $k) {
                    return $k.'='.$v;
                }, 
                $key,
                array_keys($key)
            ));
            $url = config('app.ppgameserver') . '/gs2c/playGame.do?key='.urlencode($str_params) . 'stylename=rare_stake';
            return ['error' => false, 'data' => ['url' => $url]];
        }
    }

}

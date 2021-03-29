<?php 
namespace VanguardLTE\Http\Controllers\Web\GameProviders
{
    use Illuminate\Support\Facades\Http;
    class PPController extends \VanguardLTE\Http\Controllers\Controller
    {
        /*
        * UTILITY FUNCTION
        */
        public static function calcHash($params)
        {
            ksort($params);
            $str_params = implode('&', array_map(
                function ($v, $k) {
                    return $k.'='.$v;
                }, 
                $params, 
                array_keys($params)
            ));
            $calc_hash = md5($str_params . config('app.ppsecretkey'));
            return $calc_hash;
        }
        public function checkreference($reference)
        {
            $record = \VanguardLTE\PPTransaction::Where('reference',$reference)->get()->first();
            return $record;
        }
        public function generateCode($limit){
            $code = 0;
            for($i = 0; $i < $limit; $i++) { $code .= mt_rand(0, 9); }
            return $code;
        }

        public static function gamecodetoname($code)
        {
            $gameList = \Illuminate\Support\Facades\Redis::get('pplist');
            if (!$gameList)
            {
                $gameList = \PPController::getgamelist('pp');
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
        * FROM Pragmatic Play, BACK API
        */

        public function auth(\Illuminate\Http\Request $request)
        {
            $token = $request->token;
            $providerId = $request->providerId;
            if (!$token || !$providerId)
            {
                return response()->json([
                    'error' => 7,
                    'description' => 'paramenter incorrect']);
            }


            $user = \VanguardLTE\User::Where('api_token',$token)->get()->first();
            if (!$user || !$user->hasRole('user')){
                return response()->json([
                    'error' => 2,
                    'description' => 'player not found']);
            }

            return response()->json([
                'userId' => $user->id,
                'currency' => 'KRW',
                'cash' => floatval($user->balance),
                'bonus' => 0,
                'error' => 0,
                'description' => 'success']);
        }

        public function balance(\Illuminate\Http\Request $request)
        {
            $userId = $request->userId;
            $providerId = $request->providerId;
            if (!$userId || !$providerId)
            {
                return response()->json([
                    'error' => 7,
                    'description' => 'paramenter incorrect']);
            }

            $user = \VanguardLTE\User::find($userId);
            if (!$user || !$user->hasRole('user')){
                return response()->json([
                    'error' => 2,
                    'description' => 'player not found']);
            }

            return response()->json([
                'currency' => 'KRW',
                'cash' => floatval($user->balance),
                'bonus' => 0,
                'error' => 0,
                'description' => 'success']);
        }

        public function bet(\Illuminate\Http\Request $request)
        {
            $userId = $request->userId;
            $gameId = $request->gameId;
            $roundId = $request->roundId;
            $amount = $request->amount;
            $reference = $request->reference;
            $providerId = $request->providerId;
            $timestamp = $request->timestamp;
            if (!$userId || !$gameId || !$roundId || !$amount || !$reference || !$providerId || !$timestamp || $amount < 0)
            {
                return response()->json([
                    'error' => 7,
                    'description' => 'paramenter incorrect']);
            }

            $user = \VanguardLTE\User::find($userId);
            if (!$user || !$user->hasRole('user')){
                return response()->json([
                    'error' => 2,
                    'description' => 'player not found']);
            }

            $transaction = $this->checkreference($reference);
            if ($transaction)
            {
                return response()->json([
                    'transactionId' => strval($transaction->timestamp),
                    'currency' => 'KRW',
                    'cash' => floatval($user->balance),
                    'bonus' => 0,
                    'usedPromo' => 0,
                    'error' => 0,
                    'description' => 'success']);
            }

            if ($user->balance < $amount)
            {
                return response()->json([
                    'error' => 1,
                    'description' => 'insufficient balance']);
            }

            $user->balance = floatval(sprintf('%.4f', $user->balance - floatval($amount)));
            $user->save();

            $transaction = \VanguardLTE\PPTransaction::create([
                'reference' => $reference, 
                'timestamp' => $this->microtime_string(),
                'data' => json_encode($request->all())
            ]);
            \VanguardLTE\StatGame::create([
                'user_id' => $user->id, 
                'balance' => floatval($user->balance), 
                'bet' => floatval($amount), 
                'win' => 0, 
                'game' => $this->gamecodetoname($gameId) . '_pp', 
                'percent' => 0, 
                'percent_jps' => 0, 
                'percent_jpg' => 0, 
                'profit' => 0, 
                'denomination' => 0, 
                'shop_id' => $user->shop_id
            ]);

            return response()->json([
                'transactionId' => strval($transaction->timestamp),
                'currency' => 'KRW',
                'cash' => floatval($user->balance),
                'bonus' => 0,
                'usedPromo' => 0,
                'error' => 0,
                'description' => 'success']);
        }

        public function result(\Illuminate\Http\Request $request)
        {
            $userId = $request->userId;
            $gameId = $request->gameId;
            $roundId = $request->roundId;
            $amount = $request->amount;
            $reference = $request->reference;
            $providerId = $request->providerId;
            $timestamp = $request->timestamp;
            $roundDetails = $request->roundDetails;
            $promoWinAmount = $request->promoWinAmount;
            $bonusCode = $request->bonusCode;
            if (!$userId || !$gameId || !$roundId || !$amount || !$reference || !$providerId || !$timestamp || $amount < 0)
            {
                return response()->json([
                    'error' => 7,
                    'description' => 'paramenter incorrect']);
            }

            $user = \VanguardLTE\User::find($userId);
            if (!$user || !$user->hasRole('user')){
                return response()->json([
                    'error' => 2,
                    'description' => 'player not found']);
            }
            $transaction = $this->checkreference($reference);
            if ($transaction)
            {
                return response()->json([
                    'transactionId' => strval($transaction->timestamp),
                    'currency' => 'KRW',
                    'cash' => floatval($user->balance),
                    'bonus' => 0,
                    'error' => 0,
                    'description' => 'success']);
            }

            $user->balance = floatval(sprintf('%.4f', $user->balance + floatval($amount)));
            \VanguardLTE\StatGame::create([
                'user_id' => $user->id, 
                'balance' => floatval($user->balance), 
                'bet' => 0, 
                'win' => floatval($amount), 
                'game' => $this->gamecodetoname($gameId) . '_pp', 
                'percent' => 0, 
                'percent_jps' => 0, 
                'percent_jpg' => 0, 
                'profit' => 0, 
                'denomination' => 0, 
                'shop_id' => $user->shop_id
            ]);
            if ($promoWinAmount)
            {
                $user->balance = floatval(sprintf('%.4f', $user->balance + floatval($promoWinAmount)));
                \VanguardLTE\StatGame::create([
                    'user_id' => $user->id, 
                    'balance' => floatval($user->balance), 
                    'bet' => 0, 
                    'win' => floatval($promoWinAmount), 
                    'game' => $this->gamecodetoname($gameId) . '_pp promo', 
                    'percent' => 0, 
                    'percent_jps' => 0, 
                    'percent_jpg' => 0, 
                    'profit' => 0, 
                    'denomination' => 0, 
                    'shop_id' => $user->shop_id
                ]);
            }
            $user->save();

            $transaction = \VanguardLTE\PPTransaction::create([
                'reference' => $reference, 
                'timestamp' => $this->microtime_string(),
                'data' => json_encode($request->all())
            ]);
            

            return response()->json([
                'transactionId' => strval($transaction->timestamp),
                'currency' => 'KRW',
                'cash' => floatval($user->balance),
                'bonus' => 0,
                'error' => 0,
                'description' => 'success']);
        }
        public function bonuswin(\Illuminate\Http\Request $request)
        {
            $userId = $request->userId;
            $amount = $request->amount;
            $reference = $request->reference;
            $providerId = $request->providerId;
            $timestamp = $request->timestamp;
            if (!$userId ||  !$amount || !$reference || !$providerId || !$timestamp || $amount < 0)
            {
                return response()->json([
                    'error' => 7,
                    'description' => 'paramenter incorrect']);
            }

            $user = \VanguardLTE\User::find($userId);
            if (!$user || !$user->hasRole('user')){
                return response()->json([
                    'error' => 2,
                    'description' => 'player not found']);
            }

            $transaction = $this->checkreference($reference);
            if ($transaction)
            {
                return response()->json([
                    'transactionId' => strval($transaction->timestamp),
                    'currency' => 'KRW',
                    'cash' => floatval($user->balance),
                    'bonus' => 0,
                    'error' => 0,
                    'description' => 'success']);
            }


            //$user->balance = floatval(sprintf('%.4f', $user->balance + floatval($amount)));
            //$user->save();
            $transaction = \VanguardLTE\PPTransaction::create([
                'reference' => $reference, 
                'timestamp' => $this->microtime_string(),
                'data' => json_encode($request->all())
            ]);

            return response()->json([
                'transactionId' => strval($transaction->timestamp),
                'currency' => 'KRW',
                'cash' => floatval($user->balance),
                'bonus' => 0,
                'error' => 0,
                'description' => 'success']);
        }

        public function jackpotwin(\Illuminate\Http\Request $request)
        {
            $userId = $request->userId;
            $gameId = $request->gameId;
            $roundId = $request->roundId;
            $amount = $request->amount;
            $reference = $request->reference;
            $providerId = $request->providerId;
            $timestamp = $request->timestamp;
            $jackpotId = $request->jackpotId;
            if (!$userId || !$gameId || !$roundId || !$amount || !$reference || !$providerId || !$timestamp || !$jackpotId || $amount < 0)
            {
                return response()->json([
                    'error' => 7,
                    'description' => 'paramenter incorrect']);
            }

            $user = \VanguardLTE\User::find($userId);
            if (!$user || !$user->hasRole('user')){
                return response()->json([
                    'error' => 2,
                    'description' => 'player not found']);
            }

            $transaction = $this->checkreference($reference);
            if ($transaction)
            {
                return response()->json([
                    'transactionId' => strval($transaction->timestamp),
                    'currency' => 'KRW',
                    'cash' => floatval($user->balance),
                    'bonus' => 0,
                    'error' => 0,
                    'description' => 'success']);
            }

            $user->balance = floatval(sprintf('%.4f', $user->balance + floatval($amount)));
            $user->save();
            $transaction = \VanguardLTE\PPTransaction::create([
                'reference' => $reference, 
                'timestamp' => $this->microtime_string(),
                'data' => json_encode($request->all())
            ]);
            \VanguardLTE\StatGame::create([
                'user_id' => $user->id, 
                'balance' => floatval($user->balance), 
                'bet' => 0, 
                'win' => floatval($amount), 
                'game' => $this->gamecodetoname($gameId) . '_pp JP', 
                'percent' => 0, 
                'percent_jps' => 0, 
                'percent_jpg' => 0, 
                'profit' => 0, 
                'denomination' => 0, 
                'shop_id' => $user->shop_id
            ]);

            return response()->json([
                'transactionId' => strval($transaction->timestamp),
                'currency' => 'KRW',
                'cash' => floatval($user->balance),
                'bonus' => 0,
                'error' => 0,
                'description' => 'success']);
        }

        public function endround(\Illuminate\Http\Request $request)
        {
            $userId = $request->userId;
            $gameId = $request->gameId;
            $roundId = $request->roundId;
            $providerId = $request->providerId;
            if (!$userId || !$gameId || !$roundId || !$providerId)
            {
                return response()->json([
                    'error' => 7,
                    'description' => 'paramenter incorrect']);
            }

            return response()->json([
                'cash' => floatval($user->balance),
                'bonus' => 0,
                'error' => 0,
                'description' => 'success']);
        }

        public function refund(\Illuminate\Http\Request $request)
        {
            $userId = $request->userId;
            $reference = $request->reference;
            $providerId = $request->providerId;
            if (!$userId ||  !$reference || !$providerId )
            {
                return response()->json([
                    'error' => 7,
                    'description' => 'paramenter incorrect']);
            }

            $user = \VanguardLTE\User::find($userId);
            if (!$user || !$user->hasRole('user')){
                return response()->json([
                    'error' => 2,
                    'description' => 'player not found']);
            }

            $transaction = $this->checkreference($reference);
            if (!$transaction)
            {
                return response()->json([
                    'transactionId' => strval($this->generateCode(24)),
                    'error' => 0,
                    'description' => 'reference not found']);
            } 
            if ($transaction->refund > 0)
            {
                return response()->json([
                    'transactionId' => strval('refund-' . $transaction->timestamp),
                    'error' => 0,
                    'description' => 'success']);
            }

            $data = json_decode($transaction->data, true);
            if (!isset($data['amount']))
            {
                return response()->json([
                    'error' => 7,
                    'description' => 'bad reference to refund']);
            }


            $user->balance = floatval(sprintf('%.4f', $user->balance + floatval($data['amount'])));
            $user->save();
            $transaction->refund = 1;
            $transaction->save();

            \VanguardLTE\StatGame::create([
                'user_id' => $user->id, 
                'balance' => floatval($user->balance), 
                'bet' => 0, 
                'win' => floatval($data['amount']), 
                'game' => $this->gamecodetoname($data['gameId']) . '_pp refund', 
                'percent' => 0, 
                'percent_jps' => 0, 
                'percent_jpg' => 0, 
                'profit' => 0, 
                'denomination' => 0, 
                'shop_id' => $user->shop_id
            ]);



            return response()->json([
                'transactionId' => strval($transaction->timestamp),
                'error' => 0,
                'description' => 'success']);
        }

        public function promowin(\Illuminate\Http\Request $request)
        {
            $userId = $request->userId;
            $amount = $request->amount;
            $reference = $request->reference;
            $providerId = $request->providerId;
            $timestamp = $request->timestamp;
            $campaignId = $request->campaignId;
            $campaignType = $request->campaignType;
            $currency = $request->currencty;
            if (!$userId || !$campaignId || !$campaignType || !$amount || !$reference || !$providerId || !$timestamp || $amount < 0)
            {
                return response()->json([
                    'error' => 7,
                    'description' => 'paramenter incorrect']);
            }

            $user = \VanguardLTE\User::find($userId);
            if (!$user || !$user->hasRole('user')){
                return response()->json([
                    'error' => 2,
                    'description' => 'player not found']);
            }
            $transaction = $this->checkreference($reference);
            if ($transaction)
            {
                return response()->json([
                    'transactionId' => strval($transaction->timestamp),
                    'currency' => 'KRW',
                    'cash' => floatval($user->balance),
                    'bonus' => 0,
                    'error' => 0,
                    'description' => 'success']);
            }

            $user->balance = floatval(sprintf('%.4f', $user->balance + floatval($amount)));
            $user->save();
            $transaction = \VanguardLTE\PPTransaction::create([
                'reference' => $reference, 
                'timestamp' => $this->microtime_string(),
                'data' => json_encode($request->all())
            ]);

            \VanguardLTE\StatGame::create([
                'user_id' => $user->id, 
                'balance' => floatval($user->balance), 
                'bet' => 0, 
                'win' => floatval($amount), 
                'game' => 'PragmaticPlay_pp promo', 
                'percent' => 0, 
                'percent_jps' => 0, 
                'percent_jpg' => 0, 
                'profit' => 0, 
                'denomination' => 0, 
                'shop_id' => $user->shop_id
            ]);

            return response()->json([
                'transactionId' => strval($transaction->timestamp),
                'currency' => 'KRW',
                'cash' => floatval($user->balance),
                'bonus' => 0,
                'error' => 0,
                'description' => 'success']);
        }

        /*
        * FROM CONTROLLER, API
        */
        
        public static function getgamelist($href)
        {
            $gameList = \Illuminate\Support\Facades\Redis::get('pplist');
            if ($gameList)
            {
                $games = json_decode($gameList, true);
                return $games;
            }

            $data = [
                'secureLogin' => config('app.ppsecurelogin'),
            ];
            $data['hash'] = PPController::calcHash($data);
            $response = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded'
                ])->get(config('app.ppapi') . '/CasinoGameAPI/getCasinoGames/', $data);
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
                            'title' => __('gameprovider.'.$game['gameName']),
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

        public static function getgamelink_pp($gamecode)
        {
            $detect = new \Detection\MobileDetect();
            $key = [
                'token' => auth()->user()->api_token,
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
        public static function getgamelink($gamecode)
        {
            return ['error' => false, 'data' => ['url' => route('frontend.providers.pp.render', $gamecode)]];
        }

        public static function createfrb($frbdata)
        {
            $data = [
                'secureLogin' => config('app.ppsecurelogin'),
                'currency' => 'KRW',
            ];
            $data = $data + $frbdata;
            $data['hash'] = PPController::calcHash($data);
            $response = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded'
                ])->asForm()->post(config('app.ppapi') . '/FreeRoundsBonusAPI/createFRB', $data);
            if (!$response->ok())
            {
                return ['error' => '-1', 'description' => '제공사응답 오류'];
            }
            $data = $response->json();
            return $data;
        }
        public static function cancelfrb($bonusCode)
        {
            $data = [
                'secureLogin' => config('app.ppsecurelogin'),
                'bonusCode' => $bonusCode
            ];
            $data['hash'] = PPController::calcHash($data);
            $response = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded'
                ])->asForm()->post(config('app.ppapi') . '/FreeRoundsBonusAPI/cancelFRB', $data);
            if (!$response->ok())
            {
                return ['error' => '-1', 'description' => '제공사응답 오류'];
            }
            $data = $response->json();
            return $data;
        }

        public static function getplayersfrb($userid)
        {
            $data = [
                'secureLogin' => config('app.ppsecurelogin'),
                'playerId' => $userid
            ];
            $data['hash'] = PPController::calcHash($data);
            $response = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded'
                ])->asForm()->post(config('app.ppapi') . '/FreeRoundsBonusAPI/getPlayersFRB', $data);
            if (!$response->ok())
            {
                return ['error' => '-1', 'description' => '제공사응답 오류'];
            }
            $data = $response->json();
            return $data;
        }

        /**
         * FROM CLIENT
         */

        
        public function promoactive(\Illuminate\Http\Request $request)
        {
            /*$data = [];
            return response($data, 200)->header('Content-Type', 'application/json'); */
            return response()->json([
                'error' => 0,
                'description' => 'OK',
                'serverTime' =>  time(),
                "tournaments"=> [
                    [
                        "id" => 6488,
                        "status"=> "O",
                        "name"=> "Spring Fortune Tournament",
                        "clientMode"=> "V",
                        "startDate"=> 1615888800,
                        "endDate"=> 1616493540,
                        "optJurisdiction"=> [
                            "SE",
                            "UK"
                        ],
                        "optin"=> true
                    ]
                ],
                "races"=> [
                    [
                        "id"=> 4426,
                        "status"=> "O",
                        "name"=> "Spring Fortune Cash Drop",
                        "clientMode"=> "V",
                        "startDate"=> 1615888800,
                        "endDate"=> 1616493540,
                        "clientStyle"=> "AS",
                        "showWinnersList"=> true,
                        "optin"=> true
                    ]
                ]
                ]);

        }
        public function promoracedetails(\Illuminate\Http\Request $request)
        {
            $data = '{
                "error": 0,
                "description": "OK",
                "details": [
                    {
                        "id": 4426,
                        "htmlRules": "1. 데일리 캐쉬 드롭 멀티플라이어에서 베팅의 최대 1,000배를 획득하실 수 있습니다.\r\n2. 상금 멀티플라이어는 최종 당첨금(베팅 금액 x 획득한 멀티플라이어) 결정에 사용됩니다. 크게 베팅하시면 스핀 한 번에 최대 5,000 USD 현금을 획득할 기회를 드립니다\r\n예:\r\n베팅 금액 0.5 USD\r\n획득한 멀티플라이어: X 500\r\n당첨금: 250 USD\r\n3. 상금은 베팅 금액과 멀티플라이어를 곱하여 계산됩니다. 상금 계산에 사용할 수 있는 최대 단일 베팅 금액은 5 USD입니다. 베팅이 5 USD를 초과하면 5,000 USD를 초과하는 추가 금액은 상금 계산에 사용되지 않습니다.\r\n예:\r\n베팅 금액: 8 USD\r\n상금 계산에 해당되는 베팅 금액: 5 USD\r\n획득한 멀티플라이어: X 1000\r\n당첨금: 5 X 1000 = 5,000 USD\r\n4. 참여 최소 금액은 $0.35나 이에 상응하는 통화입니다.\r\n5. 프로모션 기간 동안 $8000 상당의 488개 상금이 매주 행운의 플레이어에게 지급됩니다.\r\n6. 프로모션은 매주 화요일부터 화요일까지 2021년 3월 9일 18:00 - 2021년 5월 18일 17:59 베이징 시간에 개최됩니다. 프로모션 기간 동안 10번의 토너먼트가 있습니다.​\r\n2021년 3월 9일 18:00 - 2021년 3월 16일 17:59 베이징 시간​\r\n2021년 3월 16일 18:00 - 2021년 3월 23일 17:59 베이징 시간​\r\n2021년 3월 23일 18:00 - 2021년 3월 30일 17:59 베이징 시간​\r\n2021년 3월 30일 18:00 - 2021년 4월 6일 17:59 베이징 시간​\r\n2021년 4월 6일 18:00 - 2021년 4월 13일 17:59 베이징 시간​\r\n2021년 4월 13일 18:00 - 2021년 4월 20일 17:59 베이징 시간​\r\n2021년 4월 20일 18:00 - 2021년 4월 27일 17:59 베이징 시간​\r\n2021년 4월 27일 18:00 - 2021년 5월 4일 17:59 베이징 시간​\r\n2021년 5월 4일 18:00 - 2021년 5월 11일 17:59 베이징 시간​\r\n2021년 5월 11일 18:00 - 2021년 5월 18일 17:59 베이징 시간\r\n7. 모든 테이블 게임, 모든 비디오 포커, 머니 롤, 아이리쉬 참, 888 골드, 다이아몬드는 영원히 3 라인, 키노, 퀸 오븝 골드, KTV, 하키 리그, 알라딘의 보물, 레이디 오브 더 문, 테일즈 오브 이집트, 슈가 러시, 슈가 러시 윈터, 슈가 러시 발렌타인 데이, 슈가 러시 서머 타임, 스푸키 포춘, 그레이트 리프, 영광의 로마, 프루티 블라스트, 드워프 골드, 데블스 13, 크레이지 7s, 비지 비를 제외한 모든 Pragmatic Play 게임이 프로모션에 해당됩니다.\r\n8. Pragmatic Play는 사전 통보 없이 언제든지 프로모션을 수정, 일시 중단 또는 취소할 수 있는 권리를 보유합니다.",
                        "shortHtmlRules": "Spring Fortune Cash Drop",
                        "prizePool": {
                            "currency": "KRW",
                            "currencyOriginal": "KRW",
                            "prizesList": [
                                {
                                    "prizeID": 1,
                                    "count": 1,
                                    "type": "BM",
                                    "betMultiplier": 1000
                                },
                                {
                                    "prizeID": 2,
                                    "count": 2,
                                    "type": "BM",
                                    "betMultiplier": 500
                                },
                                {
                                    "prizeID": 3,
                                    "count": 10,
                                    "type": "BM",
                                    "betMultiplier": 250
                                },
                                {
                                    "prizeID": 4,
                                    "count": 55,
                                    "type": "BM",
                                    "betMultiplier": 40
                                },
                                {
                                    "prizeID": 5,
                                    "count": 120,
                                    "type": "BM",
                                    "betMultiplier": 30
                                },
                                {
                                    "prizeID": 6,
                                    "count": 300,
                                    "type": "BM",
                                    "betMultiplier": 10
                                }
                            ],
                            "maxBetLimitByMultiplier": 625000,
                            "minBetLimit": 35000,
                            "totalPrizeAmount": 0
                        },
                        "currencyRateMap": {
                            "SYP": 0.00195020259474884,
                            "CDF": 0.000503737520327333,
                            "ETB": 0.0247240719399288,
                            "IDR": 0.0000697099473655042,
                            "STD": 0.0000485340196284955,
                            "AZN": 0.587956879242476,
                            "XPT": 1206.98603517157,
                            "EURO": 1.19502582450807,
                            "MMK2": 0.711391032163246,
                            "GTQ": 0.129846184210185,
                            "XXX": 1e-9,
                            "FJD": 0.49263025143848,
                            "JMD": 0.00682563241480821,
                            "BYR": 0.0000499344610199114,
                            "BIF": 0.000514409237788665,
                            "TZS": 0.000432274224926274,
                            "SCR": 0.0471555134131972,
                            "BSD": 1,
                            "BBD": 0.5,
                            "DASH": 232.875467561542,
                            "IDD": 0.0697099473655042,
                            "BND": 0.74502935043126,
                            "GNF": 0.0000991589370197054,
                            "PTH": 0.110055861879233,
                            "VUV": 0.0092281032175526,
                            "UGX": 0.000273688633161772,
                            "SEK": 0.117847816044273,
                            "PEN": 0.271158791673039,
                            "UAH": 0.0361788833047586,
                            "XAG": 25.9799372532555,
                            "NOK": 0.118718528460096,
                            "PS": 0.619064223070686,
                            "XAU": 1725.4468907447,
                            "MMK": 0.000711391032163246,
                            "WST": 0.395394601835659,
                            "ZMK": 0.00019040275866027,
                            "KWD": 3.30646281221275,
                            "MRO": 0.00280112179773935,
                            "VNDP": 0.0434908758613812,
                            "GOLD": 1,
                            "MKK": 0.711391032163246,
                            "FKP": 1.39298076990047,
                            "AED": 0.272257232989845,
                            "MOP": 0.125434174716509,
                            "KHR2": 0.247126156519244,
                            "SGD": 0.743955178188425,
                            "QAR": 0.274106652157288,
                            "DOG": 5.82188674160871e-8,
                            "ZAR": 0.0668842813645143,
                            "XPD": 2371.18535555924,
                            "LAK": 0.000106989712843568,
                            "IRR": 0.0000237501484384277,
                            "mLTC": 0.214979999873162,
                            "SVC": 0.11463747446307,
                            "HRK": 0.157541123353282,
                            "MZN": 0.0135637345236433,
                            "tEUR": 0.0119502582450807,
                            "BWP": 0.0906765138942715,
                            "VND": 0.0000434908758613812,
                            "RUB": 0.0136375866179381,
                            "CZK": 0.0456391100154479,
                            "CLP": 0.00138102490534376,
                            "DJF": 0.00563433892028183,
                            "XB0": 59.267059682344,
                            "CHF": 1.07591089305984,
                            "KHR": 0.000247126156519244,
                            "GGP": 1.39298076990047,
                            "PHP": 0.020615889812522,
                            "KES": 0.00913850424873736,
                            "SHP": 1.39298076990047,
                            "CUP": 0.0388349514563107,
                            "RSD": 0.0101651944737408,
                            "IDN": 0.0000697099473655042,
                            "BYN": 0.386536321465251,
                            "RM": 0.242836328314716,
                            "PYG": 0.000151883715969212,
                            "KGS": 0.0117924595051658,
                            "TOP": 0.441935625005248,
                            "Yen": 1,
                            "RWF": 0.00101168111702739,
                            "CUC": 1,
                            "XCD": 0.370020906181199,
                            "JEP": 1.39298076990047,
                            "MPT": 0.00242836328314716,
                            "ETH": 1853.1401274145,
                            "IDR1": 0.0697099473655042,
                            "SOS": 0.00172399505940254,
                            "GMD": 0.0194741966893866,
                            "SDG": 0.00263504611330698,
                            "PTU": 0.894203877768768,
                            "DOGE": 0.0582188674160871,
                            "SZL": 0.0667534238665519,
                            "TMT": 0.284900284900285,
                            "LIR": 0.132187706543291,
                            "KZT": 0.00239328063104415,
                            "IQX": 0.686748127804766,
                            "IQD": 0.000686748127804766,
                            "TND": 0.364298724954463,
                            "XAF": 0.00182180504060844,
                            "HNL": 0.0416238669255005,
                            "NAD": 0.0668449197860962,
                            "TUSD": 1,
                            "IRT": 0.000237501484384277,
                            "MBC": 59.267059682344,
                            "ZMW": 0.0456818500345263,
                            "SLL": 0.0000980771949261603,
                            "GBP": 1.39298076990047,
                            "BHD": 2.65161257818943,
                            "MYR": 0.242836328314716,
                            "AWG": 0.555555555555556,
                            "BTC": 59267.059682344,
                            "YER": 0.00399440677599023,
                            "MTL": 1.46283948847429,
                            "KRW2": 0.880062270319644,
                            "VEF": 5.38708579027318e-12,
                            "OMR": 2.59731489586066,
                            "TJS": 0.0880261698281852,
                            "MKD": 0.0193999013010621,
                            "mBTC": 59.267059682344,
                            "VNX": 0.0434908758613812,
                            "VES": 5.38708579027318e-7,
                            "LVL": 1.19502582450807,
                            "KYD": 1.20363014852796,
                            "ARS": 0.0110450701820327,
                            "PAB": 1,
                            "AUD": 0.776214309666042,
                            "HKK": 0.128818004634872,
                            "ZWL": 0.0031055900621118,
                            "UYU": 0.0225206102995309,
                            "CLF": 38.1068516119198,
                            "tCAD": 0.00801950343234747,
                            "LKR": 0.00510462461583425,
                            "BGN": 0.611119191464865,
                            "XOF": 0.00182180504060844,
                            "CVE": 0.0107933081489477,
                            "mXMR": 0.234288166948125,
                            "BMD": 1,
                            "DKK": 0.160694508811523,
                            "HK$": 0.128818004634872,
                            "RMB": 0.153645233156641,
                            "COP": 0.000282000683254379,
                            "GEL": 0.3003003003003,
                            "ALL": 0.00970393017905789,
                            "YUN": 0.153645233156641,
                            "TTD": 0.147496323654133,
                            "TB": 0.032544892098712,
                            "NTD": 1,
                            "HUF": 0.00325595162723834,
                            "GYD": 0.00479434025448128,
                            "UZS": 0.0000953359867831809,
                            "MNT": 0.000350593276642302,
                            "TRY": 0.132187706543291,
                            "KAY": 0.01,
                            "tGBP": 0.0139298076990047,
                            "LYD": 0.222847234432394,
                            "TWD": 0.0355029115227682,
                            "AFN": 0.012846044168605,
                            "BOB": 0.145264697700532,
                            "HTG": 0.0128917705949645,
                            "VND2": 0.0434908758613812,
                            "WDW": 0.0880062270319644,
                            "KMF": 0.00242791954223286,
                            "SBD": 0.125482070745286,
                            "XB1": 0.059267059682344,
                            "AOA": 0.0016131165734823,
                            "KPW": 0.00111111111111111,
                            "MGA": 0.000266144169310718,
                            "LBP": 0.000662077886930013,
                            "ILS": 0.300498286258273,
                            "XMR": 234.288166948125,
                            "DDD": 0.0697099473655042,
                            "LSL": 0.0667854646511882,
                            "INH": 0.0434908758613812,
                            "EEK": 0.0686468066878465,
                            "INR": 0.0137569827349041,
                            "EGP": 0.0636732715587987,
                            "GIP": 1.39298076990047,
                            "EUR": 1.19502582450807,
                            "ETH4": 0.18531401274145,
                            "SRD": 0.0706514059629787,
                            "BRL": 0.180396428367194,
                            "NGN": 0.00253244423842054,
                            "MWK": 0.00128240473745856,
                            "AMD": 0.00189886637677307,
                            "YNG": 0.0000434908758613812,
                            "ANG": 0.558798136967011,
                            "ETH7": 0.00018531401274145,
                            "MVR": 0.0646830530401035,
                            "ISK": 0.00777660592103154,
                            "HKD": 0.128818004634872,
                            "IDR2": 0.0697099473655042,
                            "Won": 0.000880062270319644,
                            "BTC3": 59.267059682344,
                            "IRX": 0.0697099473655042,
                            "BZD": 0.497616664983064,
                            "MXN": 0.0482783552298204,
                            "DZD": 0.00750109677286464,
                            "mETH": 1.8531401274145,
                            "PGK": 0.284034828350652,
                            "KRW": 0.000880062270319644,
                            "NT": 0.0355029115227682,
                            "BTN": 0.0137969972463539,
                            "THB": 0.032544892098712,
                            "XDR": 1.43024686060814,
                            "CNY": 0.153645233156641,
                            "US$": 1,
                            "BDT": 0.0118403861481454,
                            "BTC6": 0.059267059682344,
                            "XPF": 0.0100143159653883,
                            "PLN": 0.260888099615425,
                            "BAM": 0.611159902043291,
                            "ERN": 0.0666602095143717,
                            "UT": 1,
                            "USD": 1,
                            "XJC": 1.19502582450807e-7,
                            "LRD": 0.0057587101816,
                            "tUSD": 0.01,
                            "LTC8": 0.00000214979999873162,
                            "NPR": 0.00862309720904577,
                            "DOP": 0.0174679464056853,
                            "DOG6": 5.82188674160871e-8,
                            "LTC6": 0.000214979999873162,
                            "ETH9": 0.0000018531401274145,
                            "LTL": 0.311078178301302,
                            "IN": 0.0697099473655042,
                            "MAD": 0.111278040536142,
                            "HKD1": 0.0128818004634872,
                            "MDL": 0.0567193065316082,
                            "IQD2": 0.686748127804766,
                            "RON": 0.244549182141943,
                            "PKR": 0.00637988415380834,
                            "IMP": 1.39298076990047,
                            "SD$": 0.743955178188425,
                            "GHS": 0.174642929432555,
                            "MUR": 0.0249052678327446,
                            "CRC": 0.0016418625456845,
                            "CAD": 0.801950343234747,
                            "JPY": 0.00916455881813849,
                            "IND": 0.0697099473655042,
                            "NIO": 0.0286844378806801,
                            "SAR": 0.266624006825575,
                            "LTC": 214.979999873162,
                            "JOD": 1.41043723554302,
                            "$US": 1,
                            "NZD": 0.718674763735671,
                            "PE": 0.020615889812522
                        }
                    }
                ]
            }';
            return response($data, 200)->header('Content-Type', 'application/json');
        }
        public function promoraceprizes(\Illuminate\Http\Request $request)
        {
            $data = '{
                "error": 0,
                "description": "OK",
                "prizes": [
                    {
                        "id": 4426,
                        "prizeRemains": [
                            {
                                "prizeID": 2,
                                "count": 1,
                                "type": "BM",
                                "betMultiplier": 500
                            },
                            {
                                "prizeID": 3,
                                "count": 6,
                                "type": "BM",
                                "betMultiplier": 250
                            },
                            {
                                "prizeID": 4,
                                "count": 32,
                                "type": "BM",
                                "betMult plier": 40
                            },
                            {
                                "prizeID": 5,
                                "count": 71,
                                "type": "BM",
                                "betMultiplier": 30
                            },
                            {
                                "prizeID": 6,
                                "count": 187,
                                "type": "BM",
                                "betMultiplier": 10
                            }
                        ],
                        "currency": "KRW",
                        "maxBetLimitByMultiplier": 625000
                    }
                ]
            }';
            return response($data, 200)->header('Content-Type', 'application/json');
        }
        public function promoracewinners(\Illuminate\Http\Request $request)
        {
            $data = '{"error" : 0,"description" : "OK","tournaments" : [ {"tournamentID" : 10,"name" : "Super tournament","dateOpened" :2018-01-18 10:00:00,"dateClosed" :2018-01-20 11:30:00,"winners" : [ {"playerID" : "extid-0","tournamentPlayerID" : 20,"position" : 1,"score" : 100000,"prizeAmount" : 1000.00,"prizeCoins" : 20.0,"prizeCurrency" : "USD"}, {"playerID": "extid-3","tournamentPlayerID" : 24,"position" : 2,"score" : 99667,"prizeAmount" : 500.0,"prizeCoins" : 10.0,"prizeCurrency" : "USD"}, {"playerID" : "extid-6","tournamentPlayerID" : 28,"position" : 3,"score" : 99334,"prizeAmount" : 250.0,"prizeCoins" : 5.0,"prizeCurrency" : "USD"} ]} ]} ';
            return response($data, 200)->header('Content-Type', 'application/json');
        }
        public function promotournamentdetails(\Illuminate\Http\Request $request)
        {
            $data = '{
                "error": 0,
                "description": "OK",
                "details": [
                    {
                        "id": 6488,
                        "htmlRules": "1. 토너먼트 우승자는 최고 총 베팅 금액을 기준으로 합니다.\r\n2. 점수 = USD 통화로 변환된 총 베팅 금액 x1000\r\n3. 토너먼트는 매주 화요일부터 화요일까지 2021년 3월 9일 18:00 - 2021년 5월 18일 17:59 베이징 시간에 개최됩니다. 프로모션 기간 동안 10번의 토너먼트가 있습니다.​\r\n2021년 3월 9일 18:00 - 2021년 3월 16일 17:59 베이징 시간​\r\n2021년 3월 16일 18:00 - 2021년 3월 23일 17:59 베이징 시간​\r\n2021년 3월 23일 18:00 - 2021년 3월 30일 17:59 베이징 시간​\r\n2021년 3월 30일 18:00 - 2021년 4월 6일 17:59 베이징 시간​\r\n2021년 4월 6일 18:00 - 2021년 4월 13일 17:59 베이징 시간​\r\n2021년 4월 13일 18:00 - 2021년 4월 20일 17:59 베이징 시간​\r\n2021년 4월 20일 18:00 - 2021년 4월 27일 17:59 베이징 시간​\r\n2021년 4월 27일 18:00 - 2021년 5월 4일 17:59 베이징 시간​\r\n2021년 5월 4일 18:00 - 2021년 5월 11일 17:59 베이징 시간​\r\n2021년 5월 11일 18:00 - 2021년 5월 18일 17:59 베이징 시간\r\n4. 해당 게임: 머니 롤, 아이리쉬 매직, 888 골드, 다이아몬드는 영원히 3 라인, 퀸 오븝 골드, 테이블 게임, 비디오 포커를 제외한 모든 Pragmatic Play 게임.\r\n5. 참여 최소 금액은 $0.35나 이에 상응하는 통화입니다.\r\n6. 토너먼트 순위표에 점수가 동일한 플레이어가 두 명 이상 있으면 먼저 점수를 획득한 플레이어가 순위표에서 더높은 점수를 받습니다.\r\n7. 토너먼트 순위표는 해당 게임에 내장되어 있으며 실시간으로 업데이트됩니다.\r\n8. Pragmatic Play는 사전 통보 없이 언제든지 프로모션을수정, 일시 중단 또는 취소할 수 있는 권리를 보유합니다.",
                        "shortHtmlRules": "•총 상금 가치: ₩22,500,000\r\n•매주 토너먼트 승자 150명\r\n•매주 488개 캐쉬 드롭",
                        "prizePool": {
                            "currency": "KRW",
                            "currencyOriginal": "KRW",
                            "prizesList": [
                                {
                                    "placeFrom": 1,
                                    "placeTo": 1,
                                    "amount": 1250000,
                                    "type": "A"
                                },
                                {
                                    "placeFrom": 2,
                                    "placeTo": 2,
                                    "amount": 875000,
                                    "type": "A"
                                },
                                {
                                    "placeFrom": 3,
                                    "placeTo": 3,
                                    "amount": 625000,
                                    "type": "A"
                                },
                                {
                                    "placeFrom": 4,
                                    "placeTo": 5,
                                    "amount": 500000,
                                    "type": "A"
                                },
                                {
                                    "placeFrom": 6,
                                    "placeTo": 10,
                                    "amount": 250000,
                                    "type": "A"
                                },
                                {
                                    "placeFrom": 11,
                                    "placeTo": 50,
                                    "amount": 125000,
                                    "type": "A"
                                },
                                {
                                    "placeFrom": 51,
                                    "placeTo": 100,
                                    "amount": 37500,
                                    "type": "A"
                                },
                                {
                                    "placeFrom": 101,
                                    "placeTo": 150,
                                    "amount": 12500,
                                    "type": "A"
                                }
                            ],
                            "minBetLimit": 35000,
                            "totalPrizeAmount": 12500000
                        },
                        "currencyRateMap": {
                            "INR": 0.0137569827349041,
                            "VND": 0.0000434908758613812,
                            "RUB": 0.0136375866179381,
                            "CZK": 0.0456391100154479,
                            "CAD": 0.801950343234747,
                            "JPY": 0.00916455881813849,
                            "CLP": 0.00138102490534376,
                            "DJF": 0.00563433892028183,
                            "XB0": 59.267059682344,
                            "CHF": 1.07591089305984,
                            "KHR": 0.000247126156519244,
                            "GGP": 1.39298076990047,
                            "PHP": 0.020615889812522,
                            "KES": 0.00913850424873736,
                            "EGP": 0.0636732715587987,
                            "IND": 0.0697099473655042,
                            "SHP": 1.39298076990047,
                            "CUP": 0.0388349514563107,
                            "GIP": 1.39298076990047,
                            "RSD": 0.0101651944737408,
                            "EUR": 1.19502582450807,
                            "ETH4": 0.18531401274145,
                            "SRD": 0.0706514059629787,
                            "IDN": 0.0000697099473655042,
                            "NIO": 0.0286844378806801,
                            "BRL": 0.180396428367194,
                            "BYN": 0.386536321465251,
                            "RM": 0.242836328314716,
                            "SAR": 0.266624006825575,
                            "PYG": 0.000151883715969212,
                            "KGS": 0.0117924595051658,
                            "NGN": 0.00253244423842054,
                            "LTC": 214.979999873162,
                            "TOP": 0.441935625005248,
                            "Yen": 1,
                            "CUC": 1,
                            "RWF": 0.00101168111702739,
                            "XCD": 0.370020906181199,
                            "MWK": 0.00128240473745856,
                            "JEP": 1.39298076990047,
                            "JOD": 1.41043723554302,
                            "MPT": 0.00242836328314716,
                            "ETH": 1853.1401274145,
                            "IDR1": 0.0697099473655042,
                            "SOS": 0.00172399505940254,
                            "GMD": 0.0194741966893866,
                            "$US": 1,
                            "SDG": 0.00263504611330698,
                            "PTU": 0.894203877768768,
                            "DOGE": 0.0582188674160871,
                            "SZL": 0.0667534238665519,
                            "NZD": 0.718674763735671,
                            "TMT": 0.284900284900285,
                            "LIR": 0.132187706543291,
                            "AMD": 0.00189886637677307,
                            "KZT": 0.00239328063104415,
                            "IQX": 0.686748127804766,
                            "IQD": 0.000686748127804766,
                            "TND": 0.364298724954463,
                            "XAF": 0.00182180504060844,
                            "HNL": 0.0416238669255005,
                            "YNG": 0.0000434908758613812,
                            "NAD": 0.0668449197860962,
                            "IRT": 0.000237501484384277,
                            "TUSD": 1,
                            "MBC": 59.267059682344,
                            "ZMW": 0.0456818500345263,
                            "SLL": 0.0000980771949261603,
                            "GBP": 1.39298076990047,
                            "BHD": 2.65161257818943,
                            "MYR": 0.242836328314716,
                            "AWG": 0.555555555555556,
                            "BTC": 59267.059682344,
                            "YER": 0.00399440677599023,
                            "MTL": 1.46283948847429,
                            "KRW2": 0.880062270319644,
                            "VEF": 5.38708579027318e-12,
                            "OMR": 2.59731489586066,
                            "TJS": 0.0880261698281852,
                            "MKD": 0.0193999013010621,
                            "mBTC": 59.267059682344,
                            "PE": 0.020615889812522,
                            "VNX": 0.0434908758613812,
                            "VES": 5.38708579027318e-7,
                            "LVL": 1.19502582450807,
                            "KYD": 1.20363014852796,
                            "ANG": 0.558798136967011,
                            "ARS": 0.0110450701820327,
                            "AUD": 0.776214309666042,
                            "PAB": 1,
                            "HKK": 0.128818004634872,
                            "ZWL": 0.0031055900621118,
                            "UYU": 0.0225206102995309,
                            "CLF": 38.1068516119198,
                            "tCAD": 0.00801950343234747,
                            "LKR": 0.00510462461583425,
                            "BGN": 0.611119191464865,
                            "XOF": 0.00182180504060844,
                            "CVE": 0.0107933081489477,
                            "mXMR": 0.234288166948125,
                            "BMD": 1,
                            "DKK": 0.160694508811523,
                            "HK$": 0.128818004634872,
                            "RMB": 0.153645233156641,
                            "COP": 0.000282000683254379,
                            "GEL": 0.3003003003003,
                            "ALL": 0.00970393017905789,
                            "YUN": 0.153645233156641,
                            "TTD": 0.147496323654133,
                            "TB": 0.032544892098712,
                            "NTD": 1,
                            "HUF": 0.00325595162723834,
                            "GYD": 0.00479434025448128,
                            "UZS": 0.0000953359867831809,
                            "MNT": 0.000350593276642302,
                            "TRY": 0.132187706543291,
                            "KAY": 0.01,
                            "tGBP": 0.0139298076990047,
                            "LYD": 0.222847234432394,
                            "TWD": 0.0355029115227682,
                            "AFN": 0.012846044168605,
                            "BOB": 0.145264697700532,
                            "HTG": 0.0128917705949645,
                            "VND2": 0.0434908758613812,
                            "WDW": 0.0880062270319644,
                            "KMF": 0.00242791954223286,
                            "SBD": 0.125482070745286,
                            "XB1": 0.059267059682344,
                            "IDR": 0.0000697099473655042,
                            "STD": 0.0000485340196284955,
                            "AZN": 0.587956879242476,
                            "XPT": 1206.98603517157,
                            "MMK2": 0.711391032163246,
                            "FJD": 0.49263025143848,
                            "JMD": 0.00682563241480821,
                            "TZS": 0.000432274224926274,
                            "AOA": 0.0016131165734823,
                            "IDD": 0.0697099473655042,
                            "PTH": 0.110055861879233,
                            "KPW": 0.00111111111111111,
                            "MGA": 0.000266144169310718,
                            "UGX": 0.000273688633161772,
                            "SEK": 0.117847816044273,
                            "LBP": 0.000662077886930013,
                            "UAH": 0.0361788833047586,
                            "NOK": 0.118718528460096,
                            "PS": 0.619064223070686,
                            "ILS": 0.300498286258273,
                            "XMR": 234.288166948125,
                            "GOLD": 1,
                            "MKK": 0.711391032163246,
                            "DDD": 0.0697099473655042,
                            "AED": 0.272257232989845,
                            "LSL": 0.0667854646511882,
                            "MOP": 0.125434174716509,
                            "INH": 0.0434908758613812,
                            "SGD": 0.743955178188425,
                            "LAK": 0.000106989712843568,
                            "mLTC": 0.214979999873162,
                            "IRR": 0.0000237501484384277,
                            "SVC": 0.11463747446307,
                            "EEK": 0.0686468066878465,
                            "tEUR": 0.0119502582450807,
                            "ETH7": 0.00018531401274145,
                            "MVR": 0.0646830530401035,
                            "ISK": 0.00777660592103154,
                            "HKD": 0.128818004634872,
                            "IDR2": 0.0697099473655042,
                            "Won": 0.000880062270319644,
                            "IRX": 0.0697099473655042,
                            "BTC3": 59.267059682344,
                            "BZD": 0.497616664983064,
                            "MXN": 0.0482783552298204,
                            "DZD": 0.00750109677286464,
                            "mETH": 1.8531401274145,
                            "PGK": 0.284034828350652,
                            "KRW": 0.000880062270319644,
                            "NT": 0.0355029115227682,
                            "BTN": 0.0137969972463539,
                            "THB": 0.032544892098712,
                            "XDR": 1.43024686060814,
                            "CNY": 0.153645233156641,
                            "US$": 1,
                            "BDT": 0.0118403861481454,
                            "BTC6": 0.059267059682344,
                            "XPF": 0.0100143159653883,
                            "PLN": 0.260888099615425,
                            "BAM": 0.611159902043291,
                            "ERN": 0.0666602095143717,
                            "UT": 1,
                            "USD": 1,
                            "XJC": 1.19502582450807e-7,
                            "LRD": 0.0057587101816,
                            "tUSD": 0.01,
                            "LTC8": 0.00000214979999873162,
                            "NPR": 0.00862309720904577,
                            "DOP": 0.0174679464056853,
                            "DOG6": 5.82188674160871e-8,
                            "LTC6": 0.000214979999873162,
                            "ETH9": 0.0000018531401274145,
                            "LTL": 0.311078178301302,
                            "IN": 0.0697099473655042,
                            "MAD": 0.111278040536142,
                            "HKD1": 0.0128818004634872,
                            "SYP": 0.00195020259474884,
                            "CDF": 0.000503737520327333,
                            "ETB": 0.0247240719399288,
                            "MDL": 0.0567193065316082,
                            "IQD2": 0.686748127804766,
                            "EURO": 1.19502582450807,
                            "GTQ": 0.129846184210185,
                            "XXX": 1e-9,
                            "BYR": 0.0000499344610199114,
                            "BIF": 0.000514409237788665,
                            "SCR": 0.0471555134131972,
                            "BSD": 1,
                            "BBD": 0.5,
                            "RON": 0.244549182141943,
                            "DASH": 232.875467561542,
                            "BND": 0.74502935043126,
                            "GNF": 0.0000991589370197054,
                            "VUV": 0.0092281032175526,
                            "PEN": 0.271158791673039,
                            "PKR": 0.00637988415380834,
                            "XAG": 25.9799372532555,
                            "XAU": 1725.4468907447,
                            "MMK": 0.000711391032163246,
                            "IMP": 1.39298076990047,
                            "SD$": 0.743955178188425,
                            "WST": 0.395394601835659,
                            "ZMK": 0.00019040275866027,
                            "KWD": 3.30646281221275,
                            "MRO": 0.00280112179773935,
                            "VNDP": 0.0434908758613812,
                            "GHS": 0.174642929432555,
                            "FKP": 1.39298076990047,
                            "MUR": 0.0249052678327446,
                            "CRC": 0.0016418625456845,
                            "KHR2": 0.247126156519244,
                            "QAR": 0.274106652157288,
                            "ZAR": 0.0668842813645143,
                            "DOG": 5.82188674160871e-8,
                            "XPD": 2371.18535555924,
                            "HRK": 0.157541123353282,
                            "MZN": 0.0135637345236433,
                            "BWP": 0.0906765138942715
                        }
                    }
                ]
            }';
            return response($data, 200)->header('Content-Type', 'application/json');
        }
        public function promotournamentleaderboard(\Illuminate\Http\Request $request)
        {
            $data = '{
                "error": 0,
                "description": "OK",
                "leaderboards": [
                    {
                        "tournamentID": 6488,
                        "index": -1,
                        "items": [
                            {
                                "position": 1,
                                "playerID": "*****5190",
                                "score": 1546525908,
                                "scoreBet": 3400,
                                "effectiveBetForBetMultiplier": 3400,
                                "effectiveBetForFreeRounds": 3400,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 2,
                                "playerID": "*****2989",
                                "score": 1282637838,
                                "scoreBet": 60000,
                                "effectiveBetForBetMultiplier": 60000,
                                "effectiveBetForFreeRounds": 60000,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 3,
                                "playerID": "*****1674",
                                "score": 1281980712,
                                "scoreBet": 20,
                                "effectiveBetForBetMultiplier": 20,
                                "effectiveBetForFreeRounds": 20,
                                "memberCurrency": "CNY",
                                "countryID": "CN"
                            },
                            {
                                "position": 4,
                                "playerID": "*****9925",
                                "score": 988753887,
                                "scoreBet": 900,
                                "effectiveBetForBetMultiplier": 900,
                                "effectiveBetForFreeRounds": 900,
                                "memberCurrency": "CNY",
                                "countryID": "CN"
                            },
                            {
                                "position": 5,
                                "playerID": "*****3080",
                                "score": 942442980,
                                "scoreBet": 61900,
                                "effectiveBetForBetMultiplier": 61900,
                                "effectiveBetForFreeRounds": 61900,
                                "memberCurrency": "IDR",
                                "countryID": "ID"
                            },
                            {
                                "position": 6,
                                "playerID": "*****1734",
                                "score": 721693536,
                                "scoreBet": 216,
                                "effectiveBetForBetMultiplier": 216,
                                "effectiveBetForFreeRounds": 216,
                                "memberCurrency": "MYR",
                                "countryID": "MY"
                            },
                            {
                                "position": 7,
                                "playerID": "*****4394",
                                "score": 621574577,
                                "scoreBet": 50,
                                "effectiveBetForBetMultiplier": 50,
                                "effectiveBetForFreeRounds": 50,
                                "memberCurrency": "CNY",
                                "countryID": "CN"
                            },
                            {
                                "position": 8,
                                "playerID": "*****9661",
                                "score": 568631244,
                                "scoreBet": 7080,
                                "effectiveBetForBetMultiplier": 7080,
                                "effectiveBetForFreeRounds": 7080,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 9,
                                "playerID": "*****4338",
                                "score": 551114768,
                                "scoreBet": 618.75,
                                "effectiveBetForBetMultiplier": 618.75,
                                "effectiveBetForFreeRounds": 618.75,
                                "memberCurrency": "IDR2",
                                "countryID": "ID"
                            },
                            {
                                "position": 10,
                                "playerID": "*****0610",
                                "score": 535844450,
                                "scoreBet": 2180,
                                "effectiveBetForBetMultiplier": 2180,
                                "effectiveBetForFreeRounds": 2180,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 11,
                                "playerID": "*****5350",
                                "score": 520199280,
                                "scoreBet": 3000,
                                "effectiveBetForBetMultiplier": 3000,
                                "effectiveBetForFreeRounds": 3000,
                                "memberCurrency": "THB",
                                "countryID": "TH"
                            },
                            {
                                "position": 12,
                                "playerID": "*****2951",
                                "score": 492919146,
                                "scoreBet": 150000,
                                "effectiveBetForBetMultiplier": 150000,
                                "effectiveBetForFreeRounds": 150000,
                                "memberCurrency": "IDR",
                                "countryID": "ID"
                            },
                            {
                                "position": 13,
                                "playerID": "*****0312",
                                "score": 452029710,
                                "scoreBet": 125,
                                "effectiveBetForBetMultiplier": 125,
                                "effectiveBetForFreeRounds": 125,
                                "memberCurrency": "CNY",
                                "countryID": "CN"
                            },
                            {
                                "position": 14,
                                "playerID": "*****8971",
                                "score": 438722400,
                                "scoreBet": 20600,
                                "effectiveBetForBetMultiplier": 20600,
                                "effectiveBetForFreeRounds": 20600,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 15,
                                "playerID": "*****0010",
                                "score": 432046158,
                                "scoreBet": 7500,
                                "effectiveBetForBetMultiplier": 7500,
                                "effectiveBetForFreeRounds": 7500,
                                "memberCurrency": "CNY",
                                "countryID": "CN"
                            },
                            {
                                "position": 16,
                                "playerID": "*****1302",
                                "score": 423837587,
                                "scoreBet": 350,
                                "effectiveBetForBetMultiplier": 350,
                                "effectiveBetForFreeRounds": 350,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 17,
                                "playerID": "*****4319",
                                "score": 408478160,
                                "scoreBet": 90000,
                                "effectiveBetForBetMultiplier": 90000,
                                "effectiveBetForFreeRounds": 90000,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 18,
                                "playerID": "*****4940",
                                "score": 389748250,
                                "scoreBet": 50,
                                "effectiveBetForBetMultiplier": 50,
                                "effectiveBetForFreeRounds": 50,
                                "memberCurrency": "THB",
                                "countryID": "TH"
                            },
                            {
                                "position": 19,
                                "playerID": "*****8635",
                                "score": 374525568,
                                "scoreBet": 500,
                                "effectiveBetForBetMultiplier": 500,
                                "effectiveBetForFreeRounds": 500,
                                "memberCurrency": "CNY",
                                "countryID": "CN"
                            },
                            {
                                "position": 20,
                                "playerID": "*****2049",
                                "score": 354648163,
                                "scoreBet": 600,
                                "effectiveBetForBetMultiplier": 600,
                                "effectiveBetForFreeRounds": 600,
                                "memberCurrency": "THB",
                                "countryID": "TH"
                            },
                            {
                                "position": 21,
                                "playerID": "*****3642",
                                "score": 351205282,
                                "scoreBet": 10500,
                                "effectiveBetForBetMultiplier": 10500,
                                "effectiveBetForFreeRounds": 10500,
                                "memberCurrency": "THB",
                                "countryID": "TH"
                            },
                            {
                                "position": 22,
                                "playerID": "*****8962",
                                "score": 350833580,
                                "scoreBet": 75000,
                                "effectiveBetForBetMultiplier": 75000,
                                "effectiveBetForFreeRounds": 75000,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 23,
                                "playerID": "*****3614",
                                "score": 348697408,
                                "scoreBet": 100000,
                                "effectiveBetForBetMultiplier": 100000,
                                "effectiveBetForFreeRounds": 100000,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 24,
                                "playerID": "*****9674",
                                "score": 336655466,
                                "scoreBet": 2700,
                                "effectiveBetForBetMultiplier": 2700,
                                "effectiveBetForFreeRounds": 2700,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 25,
                                "playerID": "*****1598",
                                "score": 329113350,
                                "scoreBet": 1,
                                "effectiveBetForBetMultiplier": 1,
                                "effectiveBetForFreeRounds": 1,
                                "memberCurrency": "USD",
                                "countryID": "JP"
                            },
                            {
                                "position": 26,
                                "playerID": "*****2062",
                                "score": 316009638,
                                "scoreBet": 1000,
                                "effectiveBetForBetMultiplier": 1000,
                                "effectiveBetForFreeRounds": 1000,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 27,
                                "playerID": "*****1882",
                                "score": 313884525,
                                "scoreBet": 120,
                                "effectiveBetForBetMultiplier": 120,
                                "effectiveBetForFreeRounds": 120,
                                "memberCurrency": "VND2",
                                "countryID": "VN"
                            },
                            {
                                "position": 28,
                                "playerID": "*****8568",
                                "score": 291145627,
                                "scoreBet": 800000,
                                "effectiveBetForBetMultiplier": 800000,
                                "effectiveBetForFreeRounds": 800000,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 29,
                                "playerID": "*****8107",
                                "score": 285191961,
                                "scoreBet": 100000,
                                "effectiveBetForBetMultiplier": 100000,
                                "effectiveBetForFreeRounds": 100000,
                                "memberCurrency": "IDR",
                                "countryID": "ID"
                            },
                            {
                                "position": 30,
                                "playerID": "*****3020",
                                "score": 283465974,
                                "scoreBet": 3.75,
                                "effectiveBetForBetMultiplier": 3.75,
                                "effectiveBetForFreeRounds": 3.75,
                                "memberCurrency": "CNY",
                                "countryID": "CN"
                            },
                            {
                                "position": 31,
                                "playerID": "*****5153",
                                "score": 282605584,
                                "scoreBet": 75,
                                "effectiveBetForBetMultiplier": 75,
                                "effectiveBetForFreeRounds": 75,
                                "memberCurrency": "CNY",
                                "countryID": "CN"
                            },
                            {
                                "position": 32,
                                "playerID": "*****0452",
                                "score": 281149794,
                                "scoreBet": 8500,
                                "effectiveBetForBetMultiplier": 8500,
                                "effectiveBetForFreeRounds": 8500,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 33,
                                "playerID": "*****9458",
                                "score": 280220310,
                                "scoreBet": 180,
                                "effectiveBetForBetMultiplier": 180,
                                "effectiveBetForFreeRounds": 180,
                                "memberCurrency": "CNY",
                                "countryID": "CN"
                            },
                            {
                                "position": 34,
                                "playerID": "*****4208",
                                "score": 271886136,
                                "scoreBet": 180,
                                "effectiveBetForBetMultiplier": 180,
                                "effectiveBetForFreeRounds": 180,
                                "memberCurrency": "CNY",
                                "countryID": "CN"
                            },
                            {
                                "position": 35,
                                "playerID": "*****5651",
                                "score": 269507150,
                                "scoreBet": 30900,
                                "effectiveBetForBetMultiplier": 30900,
                                "effectiveBetForFreeRounds": 30900,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 36,
                                "playerID": "*****8025",
                                "score": 267172258,
                                "scoreBet": 2000,
                                "effectiveBetForBetMultiplier": 2000,
                                "effectiveBetForFreeRounds": 2000,
                                "memberCurrency": "IDR",
                                "countryID": "ID"
                            },
                            {
                                "position": 37,
                                "playerID": "*****4931",
                                "score": 266470697,
                                "scoreBet": 80,
                                "effectiveBetForBetMultiplier": 80,
                                "effectiveBetForFreeRounds": 80,
                                "memberCurrency": "CNY",
                                "countryID": "CN"
                            },
                            {
                                "position": 38,
                                "playerID": "*****1232",
                                "score": 251265907,
                                "scoreBet": 108000000,
                                "effectiveBetForBetMultiplier": 108000000,
                                "effectiveBetForFreeRounds": 108000000,
                                "memberCurrency": "IDR",
                                "countryID": "ID"
                            },
                            {
                                "position": 39,
                                "playerID": "*****6699",
                                "score": 238779742,
                                "scoreBet": 1000,
                                "effectiveBetForBetMultiplier": 1000,
                                "effectiveBetForFreeRounds": 1000,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 40,
                                "playerID": "*****3623",
                                "score": 236880879,
                                "scoreBet": 10300,
                                "effectiveBetForBetMultiplier": 10300,
                                "effectiveBetForFreeRounds": 10300,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 41,
                                "playerID": "*****3072",
                                "score": 233412900,
                                "scoreBet": 600,
                                "effectiveBetForBetMultiplier": 600,
                                "effectiveBetForFreeRounds": 600,
                                "memberCurrency": "USD",
                                "countryID": "JP"
                            },
                            {
                                "position": 42,
                                "playerID": "*****2901",
                                "score": 229770072,
                                "scoreBet": 32240,
                                "effectiveBetForBetMultiplier": 32240,
                                "effectiveBetForFreeRounds": 32240,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 43,
                                "playerID": "*****2640",
                                "score": 229529541,
                                "scoreBet": 10800,
                                "effectiveBetForBetMultiplier": 10800,
                                "effectiveBetForFreeRounds": 10800,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 44,
                                "playerID": "*****0771",
                                "score": 227439600,
                                "scoreBet": 10,
                                "effectiveBetForBetMultiplier": 10,
                                "effectiveBetForFreeRounds": 10,
                                "memberCurrency": "USD",
                                "countryID": "JP"
                            },
                            {
                                "position": 45,
                                "playerID": "*****6051",
                                "score": 226629092,
                                "scoreBet": 300,
                                "effectiveBetForBetMultiplier": 300,
                                "effectiveBetForFreeRounds": 300,
                                "memberCurrency": "THB",
                                "countryID": "TH"
                            },
                            {
                                "position": 46,
                                "playerID": "*****6296",
                                "score": 219304062,
                                "scoreBet": 13200,
                                "effectiveBetForBetMultiplier": 13200,
                                "effectiveBetForFreeRounds": 13200,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 47,
                                "playerID": "*****8189",
                                "score": 218395406,
                                "scoreBet": 47000,
                                "effectiveBetForBetMultiplier": 47000,
                                "effectiveBetForFreeRounds": 47000,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 48,
                                "playerID": "*****5896",
                                "score": 216675611,
                                "scoreBet": 800000,
                                "effectiveBetForBetMultiplier": 800000,
                                "effectiveBetForFreeRounds": 800000,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 49,
                                "playerID": "*****6631",
                                "score": 216124127,
                                "scoreBet": 11800,
                                "effectiveBetForBetMultiplier": 11800,
                                "effectiveBetForFreeRounds": 11800,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 50,
                                "playerID": "*****5798",
                                "score": 214709465,
                                "scoreBet": 500,
                                "effectiveBetForBetMultiplier": 500,
                                "effectiveBetForFreeRounds": 500,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 51,
                                "playerID": "*****7397",
                                "score": 211435387,
                                "scoreBet": 4.8,
                                "effectiveBetForBetMultiplier": 4.8,
                                "effectiveBetForFreeRounds": 4.8,
                                "memberCurrency": "CNY",
                                "countryID": "CN"
                            },
                            {
                                "position": 52,
                                "playerID": "*****3922",
                                "score": 210748272,
                                "scoreBet": 500,
                                "effectiveBetForBetMultiplier": 500,
                                "effectiveBetForFreeRounds": 500,
                                "memberCurrency": "CNY",
                                "countryID": "CN"
                            },
                            {
                                "position": 53,
                                "playerID": "*****0203",
                                "score": 210370951,
                                "scoreBet": 30900,
                                "effectiveBetForBetMultiplier": 30900,
                                "effectiveBetForFreeRounds": 30900,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 54,
                                "playerID": "*****3583",
                                "score": 205970752,
                                "scoreBet": 228,
                                "effectiveBetForBetMultiplier": 228,
                                "effectiveBetForFreeRounds": 228,
                                "memberCurrency": "CNY",
                                "countryID": "CN"
                            },
                            {
                                "position": 55,
                                "playerID": "*****1911",
                                "score": 204904185,
                                "scoreBet": 625,
                                "effectiveBetForBetMultiplier": 625,
                                "effectiveBetForFreeRounds": 625,
                                "memberCurrency": "CNY",
                                "countryID": "CN"
                            },
                            {
                                "position": 56,
                                "playerID": "*****7331",
                                "score": 203690232,
                                "scoreBet": 27000,
                                "effectiveBetForBetMultiplier": 27000,
                                "effectiveBetForFreeRounds": 27000,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 57,
                                "playerID": "*****9767",
                                "score": 201533523,
                                "scoreBet": 150,
                                "effectiveBetForBetMultiplier": 150,
                                "effectiveBetForFreeRounds": 150,
                                "memberCurrency": "CNY",
                                "countryID": "CN"
                            },
                            {
                                "position": 58,
                                "playerID": "*****1150",
                                "score": 196568182,
                                "scoreBet": 1000,
                                "effectiveBetForBetMultiplier": 1000,
                                "effectiveBetForFreeRounds": 1000,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 59,
                                "playerID": "*****7470",
                                "score": 194931858,
                                "scoreBet": 112240,
                                "effectiveBetForBetMultiplier": 112240,
                                "effectiveBetForFreeRounds": 112240,
                                "memberCurrency": "IDR",
                                "countryID": "KH"
                            },
                            {
                                "position": 60,
                                "playerID": "*****5521",
                                "score": 194012850,
                                "scoreBet": 175,
                                "effectiveBetForBetMultiplier": 175,
                                "effectiveBetForFreeRounds": 175,
                                "memberCurrency": "THB",
                                "countryID": "TH"
                            },
                            {
                                "position": 61,
                                "playerID": "*****4445",
                                "score": 189661679,
                                "scoreBet": 32960,
                                "effectiveBetForBetMultiplier": 32960,
                                "effectiveBetForFreeRounds": 32960,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 62,
                                "playerID": "*****0007",
                                "score": 188427627,
                                "scoreBet": 3,
                                "effectiveBetForBetMultiplier": 3,
                                "effectiveBetForFreeRounds": 3,
                                "memberCurrency": "CNY",
                                "countryID": "CN"
                            },
                            {
                                "position": 63,
                                "playerID": "*****1390",
                                "score": 186676434,
                                "scoreBet": 7.5,
                                "effectiveBetForBetMultiplier": 7.5,
                                "effectiveBetForFreeRounds": 7.5,
                                "memberCurrency": "IDR2",
                                "countryID": "ID"
                            },
                            {
                                "position": 64,
                                "playerID": "*****2504",
                                "score": 182314993,
                                "scoreBet": 100000,
                                "effectiveBetForBetMultiplier": 100000,
                                "effectiveBetForFreeRounds": 100000,
                                "memberCurrency": "IDR",
                                "countryID": "ID"
                            },
                            {
                                "position": 65,
                                "playerID": "*****0492",
                                "score": 181507497,
                                "scoreBet": 5280,
                                "effectiveBetForBetMultiplier": 5280,
                                "effectiveBetForFreeRounds": 5280,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 66,
                                "playerID": "*****6502",
                                "score": 180503015,
                                "scoreBet": 1600,
                                "effectiveBetForBetMultiplier": 1600,
                                "effectiveBetForFreeRounds": 1600,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 67,
                                "playerID": "*****8803",
                                "score": 178993817,
                                "scoreBet": 8100,
                                "effectiveBetForBetMultiplier": 8100,
                                "effectiveBetForFreeRounds": 8100,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 68,
                                "playerID": "*****7161",
                                "score": 177862457,
                                "scoreBet": 8120,
                                "effectiveBetForBetMultiplier": 8120,
                                "effectiveBetForFreeRounds": 8120,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 69,
                                "playerID": "*****9061",
                                "score": 176000000,
                                "scoreBet": 1000,
                                "effectiveBetForBetMultiplier": 1000,
                                "effectiveBetForFreeRounds": 1000,
                                "memberCurrency": "USD",
                                "countryID": "AT"
                            },
                            {
                                "position": 70,
                                "playerID": "*****0343",
                                "score": 169563702,
                                "scoreBet": 302000,
                                "effectiveBetForBetMultiplier": 302000,
                                "effectiveBetForFreeRounds": 302000,
                                "memberCurrency": "IDR",
                                "countryID": "ID"
                            },
                            {
                                "position": 71,
                                "playerID": "*****6896",
                                "score": 169536532,
                                "scoreBet": 11040,
                                "effectiveBetForBetMultiplier": 11040,
                                "effectiveBetForFreeRounds": 11040,
                                "memberCurrency": "IDR",
                                "countryID": "ID"
                            },
                            {
                                "position": 72,
                                "playerID": "*****3201",
                                "score": 166104501,
                                "scoreBet": 1180,
                                "effectiveBetForBetMultiplier": 1180,
                                "effectiveBetForFreeRounds": 1180,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 73,
                                "playerID": "*****8380",
                                "score": 165812601,
                                "scoreBet": 13050,
                                "effectiveBetForBetMultiplier": 13050,
                                "effectiveBetForFreeRounds": 13050,
                                "memberCurrency": "IDR",
                                "countryID": "ID"
                            },
                            {
                                "position": 74,
                                "playerID": "*****3843",
                                "score": 165003800,
                                "scoreBet": 2.8,
                                "effectiveBetForBetMultiplier": 2.8,
                                "effectiveBetForFreeRounds": 2.8,
                                "memberCurrency": "USD",
                                "countryID": "CN"
                            },
                            {
                                "position": 75,
                                "playerID": "*****0901",
                                "score": 164772600,
                                "scoreBet": 60,
                                "effectiveBetForBetMultiplier": 60,
                                "effectiveBetForFreeRounds": 60,
                                "memberCurrency": "USD",
                                "countryID": "JP"
                            },
                            {
                                "position": 76,
                                "playerID": "*****2626",
                                "score": 164337943,
                                "scoreBet": 1944000,
                                "effectiveBetForBetMultiplier": 1944000,
                                "effectiveBetForFreeRounds": 1944000,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 77,
                                "playerID": "*****8947",
                                "score": 162257608,
                                "scoreBet": 45,
                                "effectiveBetForBetMultiplier": 45,
                                "effectiveBetForFreeRounds": 45,
                                "memberCurrency": "IDR2",
                                "countryID": "ID"
                            },
                            {
                                "position": 78,
                                "playerID": "*****8160",
                                "score": 161584583,
                                "scoreBet": 204000,
                                "effectiveBetForBetMultiplier": 204000,
                                "effectiveBetForFreeRounds": 204000,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 79,
                                "playerID": "*****7535",
                                "score": 158716273,
                                "scoreBet": 300,
                                "effectiveBetForBetMultiplier": 300,
                                "effectiveBetForFreeRounds": 300,
                                "memberCurrency": "CNY",
                                "countryID": "CN"
                            },
                            {
                                "position": 80,
                                "playerID": "*****5596",
                                "score": 158676238,
                                "scoreBet": 10000,
                                "effectiveBetForBetMultiplier": 10000,
                                "effectiveBetForFreeRounds": 10000,
                                "memberCurrency": "IDR",
                                "countryID": "ID"
                            },
                            {
                                "position": 81,
                                "playerID": "*****9173",
                                "score": 158580128,
                                "scoreBet": 8850,
                                "effectiveBetForBetMultiplier": 8850,
                                "effectiveBetForFreeRounds": 8850,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 82,
                                "playerID": "*****5777",
                                "score": 156753624,
                                "scoreBet": 8388,
                                "effectiveBetForBetMultiplier": 8388,
                                "effectiveBetForFreeRounds": 8388,
                                "memberCurrency": "IDR",
                                "countryID": "ID"
                            },
                            {
                                "position": 83,
                                "playerID": "*****1558",
                                "score": 156608860,
                                "scoreBet": 75,
                                "effectiveBetForBetMultiplier": 75,
                                "effectiveBetForFreeRounds": 75,
                                "memberCurrency": "IDR2",
                                "countryID": "ID"
                            },
                            {
                                "position": 84,
                                "playerID": "*****8082",
                                "score": 156090634,
                                "scoreBet": 162,
                                "effectiveBetForBetMultiplier": 162,
                                "effectiveBetForFreeRounds": 162,
                                "memberCurrency": "CNY",
                                "countryID": "CN"
                            },
                            {
                                "position": 85,
                                "playerID": "*****6334",
                                "score": 155875958,
                                "scoreBet": 2000,
                                "effectiveBetForBetMultiplier": 2000,
                                "effectiveBetForFreeRounds": 2000,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 86,
                                "playerID": "*****9316",
                                "score": 154235002,
                                "scoreBet": 4500,
                                "effectiveBetForBetMultiplier": 4500,
                                "effectiveBetForFreeRounds": 4500,
                                "memberCurrency": "THB",
                                "countryID": "TH"
                            },
                            {
                                "position": 87,
                                "playerID": "*****7440",
                                "score": 153906363,
                                "scoreBet": 12000,
                                "effectiveBetForBetMultiplier": 12000,
                                "effectiveBetForFreeRounds": 12000,
                                "memberCurrency": "THB",
                                "countryID": "TH"
                            },
                            {
                                "position": 88,
                                "playerID": "*****0595",
                                "score": 153780328,
                                "scoreBet": 7690,
                                "effectiveBetForBetMultiplier": 7690,
                                "effectiveBetForFreeRounds": 7690,
                                "memberCurrency": "IDR",
                                "countryID": "ID"
                            },
                            {
                                "position": 89,
                                "playerID": "*****5914",
                                "score": 152297686,
                                "scoreBet": 50,
                                "effectiveBetForBetMultiplier": 50,
                                "effectiveBetForFreeRounds": 50,
                                "memberCurrency": "IDR2",
                                "countryID": "ID"
                            },
                            {
                                "position": 90,
                                "playerID": "*****1890",
                                "score": 152119333,
                                "scoreBet": 300000,
                                "effectiveBetForBetMultiplier": 300000,
                                "effectiveBetForFreeRounds": 300000,
                                "memberCurrency": "THB",
                                "countryID": "TH"
                            },
                            {
                                "position": 91,
                                "playerID": "*****6532",
                                "score": 151331736,
                                "scoreBet": 350,
                                "effectiveBetForBetMultiplier": 350,
                                "effectiveBetForFreeRounds": 350,
                                "memberCurrency": "CNY",
                                "countryID": "CN"
                            },
                            {
                                "position": 92,
                                "playerID": "*****1865",
                                "score": 149581479,
                                "scoreBet": 590,
                                "effectiveBetForBetMultiplier": 590,
                                "effectiveBetForFreeRounds": 590,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 93,
                                "playerID": "*****6037",
                                "score": 149225748,
                                "scoreBet": 125,
                                "effectiveBetForBetMultiplier": 125,
                                "effectiveBetForFreeRounds": 125,
                                "memberCurrency": "IDR2",
                                "countryID": "ID"
                            },
                            {
                                "position": 94,
                                "playerID": "*****9963",
                                "score": 148473715,
                                "scoreBet": 5440,
                                "effectiveBetForBetMultiplier": 5440,
                                "effectiveBetForFreeRounds": 5440,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 95,
                                "playerID": "*****4960",
                                "score": 148430493,
                                "scoreBet": 24000,
                                "effectiveBetForBetMultiplier": 24000,
                                "effectiveBetForFreeRounds": 24000,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 96,
                                "playerID": "*****7769",
                                "score": 147498187,
                                "scoreBet": 30,
                                "effectiveBetForBetMultiplier": 30,
                                "effectiveBetForFreeRounds": 30,
                                "memberCurrency": "IDR2",
                                "countryID": "ID"
                            },
                            {
                                "position": 97,
                                "playerID": "*****0109",
                                "score": 147065346,
                                "scoreBet": 3540,
                                "effectiveBetForBetMultiplier": 3540,
                                "effectiveBetForFreeRounds": 3540,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 98,
                                "playerID": "*****1539",
                                "score": 145301474,
                                "scoreBet": 240000,
                                "effectiveBetForBetMultiplier": 240000,
                                "effectiveBetForFreeRounds": 240000,
                                "memberCurrency": "IDR",
                                "countryID": "ID"
                            },
                            {
                                "position": 99,
                                "playerID": "*****1401",
                                "score": 145110018,
                                "scoreBet": 12.5,
                                "effectiveBetForBetMultiplier": 12.5,
                                "effectiveBetForFreeRounds": 12.5,
                                "memberCurrency": "CNY",
                                "countryID": "CN"
                            },
                            {
                                "position": 100,
                                "playerID": "*****1443",
                                "score": 142071556,
                                "scoreBet": 12,
                                "effectiveBetForBetMultiplier": 12,
                                "effectiveBetForFreeRounds": 12,
                                "memberCurrency": "IDR2",
                                "countryID": "ID"
                            },
                            {
                                "position": 101,
                                "playerID": "*****8982",
                                "score": 139796553,
                                "scoreBet": 3400,
                                "effectiveBetForBetMultiplier": 3400,
                                "effectiveBetForFreeRounds": 3400,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 102,
                                "playerID": "*****3777",
                                "score": 136452009,
                                "scoreBet": 150000,
                                "effectiveBetForBetMultiplier": 150000,
                                "effectiveBetForFreeRounds": 150000,
                                "memberCurrency": "IDR",
                                "countryID": "ID"
                            },
                            {
                                "position": 103,
                                "playerID": "*****5768",
                                "score": 136102801,
                                "scoreBet": 197120,
                                "effectiveBetForBetMultiplier": 197120,
                                "effectiveBetForFreeRounds": 197120,
                                "memberCurrency": "IDR",
                                "countryID": "ID"
                            },
                            {
                                "position": 104,
                                "playerID": "*****0624",
                                "score": 134883602,
                                "scoreBet": 20,
                                "effectiveBetForBetMultiplier": 20,
                                "effectiveBetForFreeRounds": 20,
                                "memberCurrency": "CNY",
                                "countryID": "CN"
                            },
                            {
                                "position": 105,
                                "playerID": "*****3655",
                                "score": 130179202,
                                "scoreBet": 5900,
                                "effectiveBetForBetMultiplier": 5900,
                                "effectiveBetForFreeRounds": 5900,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 106,
                                "playerID": "*****2416",
                                "score": 129887988,
                                "scoreBet": 68000,
                                "effectiveBetForBetMultiplier": 68000,
                                "effectiveBetForFreeRounds": 68000,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 107,
                                "playerID": "*****3601",
                                "score": 128725447,
                                "scoreBet": 13800,
                                "effectiveBetForBetMultiplier": 13800,
                                "effectiveBetForFreeRounds": 13800,
                                "memberCurrency": "IDR",
                                "countryID": "ID"
                            },
                            {
                                "position": 108,
                                "playerID": "*****2003",
                                "score": 128223486,
                                "scoreBet": 50000,
                                "effectiveBetForBetMultiplier": 50000,
                                "effectiveBetForFreeRounds": 50000,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 109,
                                "playerID": "*****2750",
                                "score": 128039748,
                                "scoreBet": 625,
                                "effectiveBetForBetMultiplier": 625,
                                "effectiveBetForFreeRounds": 625,
                                "memberCurrency": "CNY",
                                "countryID": "CN"
                            },
                            {
                                "position": 110,
                                "playerID": "*****4811",
                                "score": 127987681,
                                "scoreBet": 600,
                                "effectiveBetForBetMultiplier": 600,
                                "effectiveBetForFreeRounds": 600,
                                "memberCurrency": "THB",
                                "countryID": "TH"
                            },
                            {
                                "position": 111,
                                "playerID": "*****3135",
                                "score": 127963412,
                                "scoreBet": 18900,
                                "effectiveBetForBetMultiplier": 18900,
                                "effectiveBetForFreeRounds": 18900,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 112,
                                "playerID": "*****1403",
                                "score": 126802936,
                                "scoreBet": 30,
                                "effectiveBetForBetMultiplier": 30,
                                "effectiveBetForFreeRounds": 30,
                                "memberCurrency": "CNY",
                                "countryID": "CN"
                            },
                            {
                                "position": 113,
                                "playerID": "*****8897",
                                "score": 126593157,
                                "scoreBet": 10300,
                                "effectiveBetForBetMultiplier": 10300,
                                "effectiveBetForFreeRounds": 10300,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 114,
                                "playerID": "*****1714",
                                "score": 126078879,
                                "scoreBet": 320,
                                "effectiveBetForBetMultiplier": 320,
                                "effectiveBetForFreeRounds": 320,
                                "memberCurrency": "THB",
                                "countryID": "TH"
                            },
                            {
                                "position": 115,
                                "playerID": "*****8696",
                                "score": 125660294,
                                "scoreBet": 500,
                                "effectiveBetForBetMultiplier": 500,
                                "effectiveBetForFreeRounds": 500,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 116,
                                "playerID": "*****2756",
                                "score": 125650566,
                                "scoreBet": 5026000,
                                "effectiveBetForBetMultiplier": 5026000,
                                "effectiveBetForFreeRounds": 5026000,
                                "memberCurrency": "IDR",
                                "countryID": "KH"
                            },
                            {
                                "position": 117,
                                "playerID": "*****3855",
                                "score": 125384010,
                                "scoreBet": 180,
                                "effectiveBetForBetMultiplier": 180,
                                "effectiveBetForFreeRounds": 180,
                                "memberCurrency": "THB",
                                "countryID": "TH"
                            },
                            {
                                "position": 118,
                                "playerID": "*****0897",
                                "score": 125056752,
                                "scoreBet": 125000,
                                "effectiveBetForBetMultiplier": 125000,
                                "effectiveBetForFreeRounds": 125000,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 119,
                                "playerID": "*****7433",
                                "score": 120160837,
                                "scoreBet": 240,
                                "effectiveBetForBetMultiplier": 240,
                                "effectiveBetForFreeRounds": 240,
                                "memberCurrency": "THB",
                                "countryID": "TH"
                            },
                            {
                                "position": 120,
                                "playerID": "*****0070",
                                "score": 120151761,
                                "scoreBet": 1500,
                                "effectiveBetForBetMultiplier": 1500,
                                "effectiveBetForFreeRounds": 1500,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 121,
                                "playerID": "*****2288",
                                "score": 119712603,
                                "scoreBet": 9,
                                "effectiveBetForBetMultiplier": 9,
                                "effectiveBetForFreeRounds": 9,
                                "memberCurrency": "IDR2",
                                "countryID": "ID"
                            },
                            {
                                "position": 122,
                                "playerID": "*****0661",
                                "score": 118586980,
                                "scoreBet": 7.5,
                                "effectiveBetForBetMultiplier": 7.5,
                                "effectiveBetForFreeRounds": 7.5,
                                "memberCurrency": "CNY",
                                "countryID": "CN"
                            },
                            {
                                "position": 123,
                                "playerID": "*****9603",
                                "score": 118575759,
                                "scoreBet": 6120,
                                "effectiveBetForBetMultiplier": 6120,
                                "effectiveBetForFreeRounds": 6120,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 124,
                                "playerID": "*****7592",
                                "score": 118324055,
                                "scoreBet": 68000,
                                "effectiveBetForBetMultiplier": 68000,
                                "effectiveBetForFreeRounds": 68000,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 125,
                                "playerID": "*****6293",
                                "score": 117939468,
                                "scoreBet": 22500,
                                "effectiveBetForBetMultiplier": 22500,
                                "effectiveBetForFreeRounds": 22500,
                                "memberCurrency": "IDR",
                                "countryID": "ID"
                            },
                            {
                                "position": 126,
                                "playerID": "*****8279",
                                "score": 117233506,
                                "scoreBet": 4020,
                                "effectiveBetForBetMultiplier": 4020,
                                "effectiveBetForFreeRounds": 4020,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 127,
                                "playerID": "*****5392",
                                "score": 116563149,
                                "scoreBet": 13500,
                                "effectiveBetForBetMultiplier": 13500,
                                "effectiveBetForFreeRounds": 13500,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 128,
                                "playerID": "*****2862",
                                "score": 116534045,
                                "scoreBet": 3060,
                                "effectiveBetForBetMultiplier": 3060,
                                "effectiveBetForFreeRounds": 3060,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 129,
                                "playerID": "*****6205",
                                "score": 116490650,
                                "scoreBet": 10,
                                "effectiveBetForBetMultiplier": 10,
                                "effectiveBetForFreeRounds": 10,
                                "memberCurrency": "USD",
                                "countryID": "JP"
                            },
                            {
                                "position": 130,
                                "playerID": "*****4018",
                                "score": 115579900,
                                "scoreBet": 1000,
                                "effectiveBetForBetMultiplier": 1000,
                                "effectiveBetForFreeRounds": 1000,
                                "memberCurrency": "USD",
                                "countryID": "JP"
                            },
                            {
                                "position": 131,
                                "playerID": "*****3509",
                                "score": 115429300,
                                "scoreBet": 0.6,
                                "effectiveBetForBetMultiplier": 0.6,
                                "effectiveBetForFreeRounds": 0.6,
                                "memberCurrency": "USD",
                                "countryID": "JP"
                            },
                            {
                                "position": 132,
                                "playerID": "*****7768",
                                "score": 113839784,
                                "scoreBet": 16200,
                                "effectiveBetForBetMultiplier": 16200,
                                "effectiveBetForFreeRounds": 16200,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 133,
                                "playerID": "*****8927",
                                "score": 113787363,
                                "scoreBet": 1000,
                                "effectiveBetForBetMultiplier": 1000,
                                "effectiveBetForFreeRounds": 1000,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 134,
                                "playerID": "*****8939",
                                "score": 113614305,
                                "scoreBet": 240,
                                "effectiveBetForBetMultiplier": 240,
                                "effectiveBetForFreeRounds": 240,
                                "memberCurrency": "RMB",
                                "countryID": "CN"
                            },
                            {
                                "position": 135,
                                "playerID": "*****6243",
                                "score": 112993854,
                                "scoreBet": 20000,
                                "effectiveBetForBetMultiplier": 20000,
                                "effectiveBetForFreeRounds": 20000,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 136,
                                "playerID": "*****2774",
                                "score": 112308151,
                                "scoreBet": 8640,
                                "effectiveBetForBetMultiplier": 8640,
                                "effectiveBetForFreeRounds": 8640,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 137,
                                "playerID": "*****9675",
                                "score": 112028925,
                                "scoreBet": 1250,
                                "effectiveBetForBetMultiplier": 1250,
                                "effectiveBetForFreeRounds": 1250,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 138,
                                "playerID": "*****9256",
                                "score": 111140755,
                                "scoreBet": 62500,
                                "effectiveBetForBetMultiplier": 62500,
                                "effectiveBetForFreeRounds": 62500,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 139,
                                "playerID": "*****1009",
                                "score": 111140000,
                                "scoreBet": 60,
                                "effectiveBetForBetMultiplier": 60,
                                "effectiveBetForFreeRounds": 60,
                                "memberCurrency": "USD",
                                "countryID": "JP"
                            },
                            {
                                "position": 140,
                                "playerID": "*****2855",
                                "score": 111055989,
                                "scoreBet": 24,
                                "effectiveBetForBetMultiplier": 24,
                                "effectiveBetForFreeRounds": 24,
                                "memberCurrency": "CNY",
                                "countryID": "MY"
                            },
                            {
                                "position": 141,
                                "playerID": "*****9260",
                                "score": 110727305,
                                "scoreBet": 3480,
                                "effectiveBetForBetMultiplier": 3480,
                                "effectiveBetForFreeRounds": 3480,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 142,
                                "playerID": "*****1507",
                                "score": 110364265,
                                "scoreBet": 20,
                                "effectiveBetForBetMultiplier": 20,
                                "effectiveBetForFreeRounds": 20,
                                "memberCurrency": "IDR2",
                                "countryID": "ID"
                            },
                            {
                                "position": 143,
                                "playerID": "*****2645",
                                "score": 110084000,
                                "scoreBet": 100,
                                "effectiveBetForBetMultiplier": 100,
                                "effectiveBetForFreeRounds": 100,
                                "memberCurrency": "USD",
                                "countryID": "JP"
                            },
                            {
                                "position": 144,
                                "playerID": "*****5581",
                                "score": 109776625,
                                "scoreBet": 2500,
                                "effectiveBetForBetMultiplier": 2500,
                                "effectiveBetForFreeRounds": 2500,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 145,
                                "playerID": "*****1663",
                                "score": 109640000,
                                "scoreBet": 120,
                                "effectiveBetForBetMultiplier": 120,
                                "effectiveBetForFreeRounds": 120,
                                "memberCurrency": "USD",
                                "countryID": "JP"
                            },
                            {
                                "position": 146,
                                "playerID": "*****2912",
                                "score": 109312000,
                                "scoreBet": 300,
                                "effectiveBetForBetMultiplier": 300,
                                "effectiveBetForFreeRounds": 300,
                                "memberCurrency": "USD",
                                "countryID": "JP"
                            },
                            {
                                "position": 147,
                                "playerID": "*****5690",
                                "score": 108023497,
                                "scoreBet": 5000,
                                "effectiveBetForBetMultiplier": 5000,
                                "effectiveBetForFreeRounds": 5000,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 148,
                                "playerID": "*****9629",
                                "score": 107980000,
                                "scoreBet": 1600,
                                "effectiveBetForBetMultiplier": 1600,
                                "effectiveBetForFreeRounds": 1600,
                                "memberCurrency": "USD",
                                "countryID": "JP"
                            },
                            {
                                "position": 149,
                                "playerID": "*****4653",
                                "score": 107298595,
                                "scoreBet": 272000,
                                "effectiveBetForBetMultiplier": 272000,
                                "effectiveBetForFreeRounds": 272000,
                                "memberCurrency": "KRW",
                                "countryID": "KR"
                            },
                            {
                                "position": 150,
                                "playerID": "*****6879",
                                "score": 107032000,
                                "scoreBet": 180,
                                "effectiveBetForBetMultiplier": 180,
                                "effectiveBetForFreeRounds": 180,
                                "memberCurrency": "USD",
                                "countryID": "JP"
                            }
                        ]
                    }
                ]
            }';
            return response($data, 200)->header('Content-Type', 'application/json');
        }

    }

}

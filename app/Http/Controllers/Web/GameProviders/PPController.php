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

                    if ($game['gameTypeID'] == "vs" && str_contains($game['platform'], 'WEB'))
                    {
                        if ($game['gameID'] == 'vswayshammthor')
                        {
                            array_unshift($gameList, [
                                'provider' => 'pp',
                                'gamecode' => $game['gameID'],
                                'enname' => $game['gameName'],
                                'name' => preg_replace('/\s+/', '', $game['gameName']),
                                'title' => __('gameprovider.'.$game['gameName']),
                                'icon' => config('app.ppgameserver') . '/game_pic/rec/325/'. $game['gameID'] . '.png',
                                'demo' => 'https://demogamesfree-asia.pragmaticplay.net/gs2c/openGame.do?gameSymbol='.$game['gameID'].'&lang=ko&cur=KRW&lobbyURL='. \URL::to('/')
                            ]);
                        }
                        else
                        {
                            $gameList[] = [
                                'provider' => 'pp',
                                'gamecode' => $game['gameID'],
                                'enname' => $game['gameName'],
                                'name' => preg_replace('/\s+/', '', $game['gameName']),
                                'title' => __('gameprovider.'.$game['gameName']),
                                'icon' => config('app.ppgameserver') . '/game_pic/rec/325/'. $game['gameID'] . '.png',
                                'demo' => 'https://demogamesfree-asia.pragmaticplay.net/gs2c/openGame.do?gameSymbol='.$game['gameID'].'&lang=ko&cur=KRW&lobbyURL='. \URL::to('/')
                            ];
                        }
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
            $url = config('app.ppgameserver') . '/gs2c/playGame.do?key='.urlencode($str_params) . '&stylename=mjr_major';
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

        public function minilobby_games_json(\Illuminate\Http\Request $request)
        {
            $data = [
                "error" => 0,
                "description" => "OK",
                "gameLaunchURL" => "/gs2c/minilobby/start",
                "gameIconsURL" => "/frontend/Default/ico/pp",
            ];
            $allGames = [
                "categorySymbol" => "all",
                "categoryName" => "All games",
                "lobbyGames" => []
            ];
            $newGames = [
                "categorySymbol" => "new",
                "categoryName" => "New games",
                "lobbyGames" => []
            ];
            $hotGames = [
                "categorySymbol" => "hot",
                "categoryName" => "Hot games",
                "lobbyGames" => []
            ];
            $allGamesId = [
                "vs25mmouse",
                "vs1fortunetree",
                "vs25scarabqueen",
                "vs243lionsgold",
                "vs5spjoker",
                "vs243mwarrior",
                "vs18mashang",
                "vs40pirate",
                "vs25goldrush",
                "vs10egyptcls",
                "vs20doghouse",
                "vs25davinci",
                "vs7776secrets",
                "vs5aztecgems",
                "vs243caishien",
                "vs1dragon8",
                "vs25wolfgold",
                "vs9madmonkey",
                "vs243fortune",
                "vs9hotroll",
                "vs20rhino",
                "vs10firestrike",
                "vs10vampwolf",
                "vs20chicken",
                "bndt",
                "vs5trjokers",
                "vs20fruitsw",
                "vs20wildpix",
                "vs10fruity2",
                "vs25gladiator",
                "vs25goldpig",
                "vs50safariking",
                "vs25mustang",
                "vs20egypttrs",
                "vs20leprexmas",
                "vs5trdragons",
                "vs20vegasmagic",
                "vs9chen",
                "vs20leprechaun",
                "vs25peking",
                "vs1024butterfly",
                "vs10madame",
                "vs25asgard",
                "vs243lions",
                "vs25champ",
                "scwolfgoldai",
                "scsafariai",
                "scqogai",
                "scpandai",
                "scgoldrushai",
                "scdiamondai",
                "sc7piggiesai",
                "vs5joker",
                "vs7fire88",
                "vs15fairytale",
                "vs25chilli",
                "vs1tigers",
                "vs10egypt",
                "vs25newyear",
                "bjmb",
                "vs20santa",
                "cs5moneyroll",
                "vs25pandagold",
                "vs15diamond",
                "vs25wildspells",
                "vs7pigs",
                "vs25vegas",
                "vs50pixie",
                "vs4096jurassic",
                "vs20eightdragons",
                "vs3train",
                "vs25kingdoms",
                "cs3irishcharms",
                "vs25queenofgold",
                "cs5triple8gold",
                "vs25pantherqueen",
                "vs1024atlantis",
                "vs25dragonkingdom",
                "vs50aladdin",
                "vs50hercules",
                "vs30catz",
                "vs25journey",
                "vs50chinesecharms",
                "vs25dwarves_new",
                "vs40beowulf",
                "vs25romeoandjuliet",
                "vs25safari",
                "vs9hockey",
                "vs20godiva",
                "vs9catz",
                "vs50kingkong",
                "vs15ktv",
                "vs243crystalcave",
                "vs20hockey",
                "vs50amt",
                "vs13ladyofmoon",
                "vs7monkeys_jp",
                "vs7monkeys",
                "vs20cw",
                "vs20egypt",
                "vs25dwarves",
                "vs20rome",
                "vs20cms",
                "vs20cmv",
                "vs20cm",
                "vs25sea",
                "vs20bl",
                "vs20gg",
                "bca",
                "bjma",
                "cs3w",
                "rla",
                "vs25h",
                "vs13g",
                "vs15b",
            ];
            $newgameId = ["vs25pyramid",
                        "vs25mmouse",
                        "vs10firestrike",
                        "bjmb",
                        "vs5spjoker",
                        "vs1fortunetree",
                        "vs9hotroll",
                        "vs10vampwolf",
                        "vs20chicken",
                        "vs7776secrets",
                        "vs5trjokers",
                        "vs243lionsgold",
                        "vs20wildpix",
                        "vs20fruitsw",
                        "vs243mwarrior",
                        "vs243caishien",
                        "vs40pirate",
                        "vs20doghouse",
                        "vs20egypttrs",
                        "vs10fruity2",
                        "vs25gladiator",
                        "vs18mashang",
                        "vs25goldpig",
                        "vs50safariking",
                        "vs25scarabqueen",];
            $hotgameId = [
                "vs25pyramid",
                "vs25mmouse",
                "vs243caishien",
                "vs243fortune",
                "vs18mashang",
                "vs25wolfgold",
                "vs9madmonkey",
                "vs1dragon8",
                "vs25goldpig",
                "vs5aztecgems",
                "vs243lions",
                "vs5joker",
                "vs25journey",
                "vs1tigers",
                "vs20rhino",
                "vs25newyear",
                "cs5triple8gold",
                "vs7fire88",
                "vs25dragonkingdom",
                "vs20eightdragons",
                "vs25wildspells",
                "vs25chilli",
            ];
            $gamelist = PPController::getgamelist('pp');
            foreach ($gamelist as $game)
            {
                $gamedata = [
                    "name" => $game['enname'],
                    "symbol" => $game['gamecode'],
                    "promoKeys"=> [
                        [
                            "type" => "TM",
                            "id" => 6488
                        ],
                        [
                            "type" => "MR",
                            "id" => 4426
                        ]
                    ]
                ];
                if (in_array($game['gamecode'], $allGamesId))
                {
                    $allGames["lobbyGames"][] = $gamedata;
                }
                if (in_array($game['gamecode'], $newgameId))
                {
                    $newGames["lobbyGames"][] = $gamedata;
                }
                if (in_array($game['gamecode'], $hotgameId))
                {
                    $hotGames["lobbyGames"][] = $gamedata;
                }
            }
            $data["lobbyCategories"] = [
                $allGames,
                $newGames,
                $hotGames
            ];

            $data['activePromos'] = [
                "tournaments" => [
                    [
                        "id" => 6488,
                        "status"=> "O",
                        "name"=> "Spring Fortune Tournament",
                        "clientMode"=> "V",
                        "startDate"=> 1617973200,
                        "endDate" => 1620565200,
                        "optin"=> true
                    ]
                ],
                "races" => [
                    [
                        "id"=> 4426,
                        "status" => "O",
                        "name" => "Spring Fortune Cash Drop",
                        "clientMode" => "V",
                        "startDate"=> 1617973200,
                        "endDate" => 1620565200,
                        "clientStyle" => "AS",
                        "optin" => true
                    ]
                ]
            ];
            return response()->json($data);

        }

        public function minilobby_start(\Illuminate\Http\Request $request)
        {
            $gamecode = $request->gameSymbol;
            return redirect(route('frontend.providers.pp.render', $gamecode) . '?lobby=mini');
        }
        
        public function promoactive(\Illuminate\Http\Request $request)
        {
            $data = '{"error":0,"description":"OK","serverTime":1620053173,"tournaments":[{"id":7080,"status":"S","name":"Spring Fortune Tournament","clientMode":"V","startDate":1620122400,"endDate":1620727140,"optJurisdiction":["SE","UK"],"optin":false}],"races":[{"id":4819,"status":"S","name":"Spring Fortune Cash Drop","clientMode":"V","startDate":1620122400,"endDate":1620727140,"clientStyle":"EU","showWinnersList":true,"optin":true}]}';
            return response($data, 200)->header('Content-Type', 'application/json');

        }
        public function promoracedetails(\Illuminate\Http\Request $request)
        {
            $data = '{"error":0,"description":"OK","details":[{"id":4819,"htmlRules":"1. 데일리 캐쉬 드롭 멀티플라이어에서 베팅의 최대 1,000배를 획득하실 수 있습니다.\r\n2. 상금 멀티플라이어는 최종 당첨금(베팅 금액 x 획득한 멀티플라이어) 결정에 사용됩니다. 크게 베팅하시면 스핀 한 번에 최대 5,000 USD 현금을 획득할 기회를 드립니다\r\n예:\r\n베팅 금액 0.5 USD\r\n획득한 멀티플라이어: X 500\r\n당첨금: 250 USD\r\n3. 상금은 베팅 금액과 멀티플라이어를 곱하여 계산됩니다. 상금 계산에 사용할 수 있는 최대 단일 베팅 금액은 5 USD입니다. 베팅이 5 USD를 초과하면 5,000 USD를 초과하는 추가 금액은 상금 계산에 사용되지 않습니다.\r\n예:\r\n베팅 금액: 8 USD\r\n상금 계산에 해당되는 베팅 금액: 5 USD\r\n획득한 멀티플라이어: X 1000\r\n당첨금: 5 X 1000 = 5,000 USD\r\n4. 참여 최소 금액은 $0.35나 이에 상응하는 통화입니다.\r\n5. 프로모션 기간 동안 $8000 상당의 488개 상금이 매주 행운의 플레이어에게 지급됩니다.\r\n6. 프로모션은 매주 화요일부터 화요일까지 2021년 3월 9일 18:00 - 2021년 5월 18일 17:59 베이징 시간에 개최됩니다. 프로모션 기간 동안 10번의 토너먼트가 있습니다.​\r\n2021년 3월 9일 18:00 - 2021년 3월 16일 17:59 베이징 시간​\r\n2021년 3월 16일 18:00 - 2021년 3월 23일 17:59 베이징 시간​\r\n2021년 3월 23일 18:00 - 2021년 3월 30일 17:59 베이징 시간​\r\n2021년 3월 30일 18:00 - 2021년 4월 6일 17:59 베이징 시간​\r\n2021년 4월 6일 18:00 - 2021년 4월 13일 17:59 베이징 시간​\r\n2021년 4월 13일 18:00 - 2021년 4월 20일 17:59 베이징 시간​\r\n2021년 4월 20일 18:00 - 2021년 4월 27일 17:59 베이징 시간​\r\n2021년 4월 27일 18:00 - 2021년 5월 4일 17:59 베이징 시간​\r\n2021년 5월 4일 18:00 - 2021년 5월 11일 17:59 베이징 시간​\r\n2021년 5월 11일 18:00 - 2021년 5월 18일 17:59 베이징 시간\r\n7. 모든 테이블 게임, 모든 비디오 포커, 머니 롤, 아이리쉬 참, 888 골드, 다이아몬드는 영원히 3 라인, 키노, 퀸 오븝 골드, KTV, 하키 리그, 알라딘의 보물, 레이디 오브 더 문, 테일즈 오브 이집트, 슈가 러시, 슈가 러시 윈터, 슈가 러시 발렌타인 데이, 슈가 러시 서머 타임, 스푸키 포춘, 그레이트 리프, 영광의 로마, 프루티 블라스트, 드워프 골드, 데블스 13, 크레이지 7s, 비지 비를 제외한 모든 Pragmatic Play 게임이 프로모션에 해당됩니다.\r\n8. Pragmatic Play는 사전 통보 없이 언제든지 프로모션을 수정, 일시 중단 또는 취소할 수 있는 권리를 보유합니다.","shortHtmlRules":"Spring Fortune Cash Drop","prizePool":{"currency":"KRW","currencyOriginal":"KRW","prizesList":[{"prizeID":1,"count":1,"type":"BM","betMultiplier":1000.0},{"prizeID":2,"count":2,"type":"BM","betMultiplier":500.0},{"prizeID":3,"count":10,"type":"BM","betMultiplier":250.0},{"prizeID":4,"count":55,"type":"BM","betMultiplier":40.0},{"prizeID":5,"count":120,"type":"BM","betMultiplier":30.0},{"prizeID":6,"count":300,"type":"BM","betMultiplier":10.0}],"maxBetLimitByMultiplier":625000,"minBetLimit":35000,"totalPrizeAmount":0.0},"currencyRateMap":{"IDD":0.0692265444204977,"GNF":1.00557214343285E-4,"PTH":0.107969412643291,"UGX":2.78175034057686E-4,"SEK":0.118130281161882,"PEN":0.263079040256092,"UAH":0.035844390610949,"XAG":25.8644814973259,"NOK":0.120200687067127,"PS":0.607327946118512,"XAU":1769.12870411322,"MMK":6.38864082488669E-4,"WST":0.39496592234022,"KWD":3.3205492188408,"MRO":0.00280112179773935,"GOLD":1.0,"MKK":0.638864082488669,"FKP":1.38187014013545,"AED":0.272256269381243,"MOP":0.124414675606773,"KHR2":0.24602746658313,"SGD":0.751710140569796,"QAR":0.27437604828799,"DOG":3.77735916610909E-7,"ZAR":0.0690986632172614,"XPD":2947.85248946143,"IRR":2.37501484384277E-5,"mLTC":0.269330002834429,"SVC":0.11371759418148,"HRK":0.159283479197179,"MZN":0.0173771004570177,"tEUR":0.0120265015989234,"BWP":0.0915924371025575,"VND":4.31810997922873E-5,"RUB":0.0132921277373476,"CZK":0.0465075179402751,"CLP":0.00140706442273867,"DJF":0.00559507061745644,"XB0":56.6448838765719,"CHF":1.09520388315489,"KHR":2.4602746658313E-4,"KES":0.00923020121838656,"SHP":1.38187014013545,"CUP":0.0388349514563107,"RSD":0.0102210457526,"IDN":6.92265444204977E-5,"BYN":0.388655609155482,"RM":0.244170430960811,"KGS":0.0117926194287509,"TOP":0.442336171951994,"Yen":1.0,"RWF":9.98280820667496E-4,"CUC":1.0,"XCD":0.370020906181199,"JEP":1.38187014013545,"MPT":0.00244170430960811,"IDR1":0.0692265444204977,"GMD":0.0195503421309873,"SDG":0.00257731958762887,"PTU":0.87725147772674,"DOGE":0.377735916610909,"SZL":0.0691735435544991,"TMT":0.284900284900285,"LIR":0.120599432241993,"KZT":0.00231348464147529,"IQX":0.682349685389099,"IQD":6.82349685389099E-4,"TND":0.363901018922853,"XAF":0.00183342807603473,"HNL":0.0414194872474576,"NAD":0.0698324022346369,"TUSD":1.0,"IRT":2.37501484384277E-4,"SLL":9.78114667682651E-5,"GBP":1.38187014013545,"BHD":2.65232993923512,"MYR":0.244170430960811,"AWG":0.555401277422938,"BTC":56644.8838765719,"YER":0.00399321107795499,"KRW2":0.894647860429065,"OMR":2.59749705184085,"TJS":0.0872452351887254,"MKD":0.0195414554487743,"mBTC":56.6448838765719,"VNX":0.0431810997922873,"LVL":1.20265015989234,"KYD":1.19399563475196,"ARS":0.0106484252043965,"PAB":1.0,"AUD":0.77210001165871,"HKK":0.128749010241984,"ZWL":0.0031055900621118,"UYU":0.0226971690592736,"CLF":38.8259046435782,"tCAD":0.00814320974383905,"LKR":0.00503814152753197,"BGN":0.614784585629041,"XOF":0.00183342807603473,"CVE":0.0108754758020663,"mXMR":0.405617576285714,"BMD":1.0,"DKK":0.161732739882,"HK$":0.128749010241984,"RMB":0.154452081241795,"COP":2.6800175722749E-4,"GEL":0.289017341040462,"ALL":0.00974246742133508,"YUN":0.154452081241795,"TTD":0.146482930197661,"TB":0.0320821302534488,"NTD":1.0,"HUF":0.00334068283557159,"GYD":0.00475604314256197,"UZS":9.46175747639057E-5,"MNT":3.50775505994088E-4,"TRY":0.120599432241993,"KAY":0.01,"tGBP":0.0138187014013545,"LYD":0.222702765300237,"TWD":0.0358918233365692,"AFN":0.0128714164673324,"BOB":0.144103369381752,"HTG":0.0116514154687128,"VND2":0.0431810997922873,"WDW":0.0894647860429065,"KMF":0.0024428977416539,"SBD":0.125649497961148,"XB1":0.0566448838765719,"STD":4.82204972521054E-5,"IDR":6.92265444204977E-5,"AZN":0.587956879242476,"XPT":1202.99304670019,"MMK2":0.638864082488669,"FJD":0.492261646910566,"JMD":0.00648503484824266,"TZS":4.31354279897165E-4,"AOA":0.00152827850124784,"KPW":0.00111111111111111,"MGA":2.62037191595792E-4,"LBP":6.57355093719559E-4,"ILS":0.30834999451137,"XMR":405.617576285714,"DDD":0.0692265444204977,"LSL":0.0690649564894227,"INH":0.0431810997922873,"LAK":1.05872735134334E-4,"EEK":0.0686468066878465,"INR":0.0134961765804114,"PHP":0.020742184827013,"EGP":0.0638970939857244,"GIP":1.38187014013545,"EUR":1.20265015989234,"ETH4":0.295537006996248,"SRD":0.0706514059629787,"BRL":0.183782989046534,"NGN":0.00243433384454344,"MWK":0.00126197350682985,"ETH":2955.37006996248,"SOS":0.0017169743410856,"AMD":0.00191123583572849,"YNG":4.31810997922873E-5,"MBC":56.6448838765719,"ZMW":0.0445354868557073,"ANG":0.554323417780811,"ETH7":2.95537006996248E-4,"MVR":0.0648508430609598,"ISK":0.00798594473726242,"HKD":0.128749010241984,"IDR2":0.0692265444204977,"Won":8.94647860429065E-4,"BTC3":56.6448838765719,"IRX":0.0692265444204977,"BZD":0.493632388998119,"MXN":0.0494784966453579,"DZD":0.0075013618534911,"mETH":2.95537006996248,"PGK":0.280490746610269,"KRW":8.94647860429065E-4,"NT":0.0358918233365692,"BTN":0.0134319575713473,"THB":0.0320821302534488,"XDR":1.43320477486503,"CNY":0.154452081241795,"US$":1.0,"BDT":0.0117415020497316,"BTC6":0.0566448838765719,"XPF":0.0100782068854309,"PLN":0.263743474656758,"BAM":0.614570356936318,"ERN":0.0666579122608564,"UT":1.0,"USD":1.0,"XJC":1.20265015989234E-7,"LRD":0.0058122635051735,"tUSD":0.01,"LTC8":2.69330002834429E-6,"NPR":0.00839499435701072,"DOP":0.0175282588836897,"DOG6":3.77735916610909E-7,"LTC6":2.69330002834429E-4,"ETH9":2.95537006996248E-6,"LTL":0.311078178301302,"IN":0.0692265444204977,"MAD":0.111566376304016,"HKD1":0.0128749010241984,"CDF":5.00269871082225E-4,"SYP":7.95168048123647E-4,"ETB":0.0237435286715811,"MDL":0.0563758333264273,"IQD2":0.682349685389099,"EURO":1.20265015989234,"GTQ":0.128932073684164,"XXX":1.0E-9,"BYR":4.99344610199114E-5,"BIF":5.1015510752318E-4,"SCR":0.067528460376089,"BSD":1.0,"BBD":0.5,"RON":0.245821042281219,"DASH":315.455363286914,"BND":0.749139987294586,"VUV":0.00912871591684368,"PKR":0.0064976612415592,"IMP":1.38187014013545,"SD$":0.751710140569796,"ZMK":1.9040275866027E-4,"VNDP":0.0431810997922873,"GHS":0.172532156111926,"MUR":0.0248138963973672,"CRC":0.00161080139239412,"CAD":0.814320974383905,"JPY":0.00915013542200425,"GGP":1.38187014013545,"IND":0.0692265444204977,"NIO":0.028479223423979,"UBT":0.0566448838765719,"SAR":0.266652445202923,"PYG":1.52472588046242E-4,"LTC":269.330002834429,"JOD":1.40984068800226,"$US":1.0,"NZD":0.716896754680081,"MTL":1.46283948847429,"VEF":3.44729753091692E-12,"PE":0.020742184827013,"VES":3.44729753091692E-7}}]}';
            return response($data, 200)->header('Content-Type', 'application/json');
        }
        public function promoraceprizes(\Illuminate\Http\Request $request)
        {
            $data = '{"error":0,"description":"OK","prizes":[{"id":4819,"prizeRemains":[{"prizeID":1,"count":1,"type":"BM","betMultiplier":1000.0},{"prizeID":2,"count":2,"type":"BM","betMultiplier":500.0},{"prizeID":3,"count":10,"type":"BM","betMultiplier":250.0},{"prizeID":4,"count":55,"type":"BM","betMultiplier":40.0},{"prizeID":5,"count":120,"type":"BM","betMultiplier":30.0},{"prizeID":6,"count":300,"type":"BM","betMultiplier":10.0}],"currency":"KRW","maxBetLimitByMultiplier":625000}]}';
            return response($data, 200)->header('Content-Type', 'application/json');
        }
        public function promoracewinners(\Illuminate\Http\Request $request)
        {
            $data = '{"error":0,"description":"OK","winners":[{"raceID":4819,"action":"A","items":[]}]}';
            return response($data, 200)->header('Content-Type', 'application/json');
        }
        public function promotournamentdetails(\Illuminate\Http\Request $request)
        {
            $data = '{"error":0,"description":"OK","details":[{"id":7080,"htmlRules":"1. 토너먼트 우승자는 최고 총 베팅 금액을 기준으로 합니다.\r\n2. 점수 = USD 통화로 변환된 총 베팅 금액 x1000\r\n3. 토너먼트는 매주 화요일부터 화요일까지 2021년 3월 9일 18:00 - 2021년 5월 18일 17:59 베이징 시간에 개최됩니다. 프로모션 기간 동안 10번의 토너먼트가 있습니다.​\r\n2021년 3월 9일 18:00 - 2021년 3월 16일 17:59 베이징 시간​\r\n2021년 3월 16일 18:00 - 2021년 3월 23일 17:59 베이징 시간​\r\n2021년 3월 23일 18:00 - 2021년 3월 30일 17:59 베이징 시간​\r\n2021년 3월 30일 18:00 - 2021년 4월 6일 17:59 베이징 시간​\r\n2021년 4월 6일 18:00 - 2021년 4월 13일 17:59 베이징 시간​\r\n2021년 4월 13일 18:00 - 2021년 4월 20일 17:59 베이징 시간​\r\n2021년 4월 20일 18:00 - 2021년 4월 27일 17:59 베이징 시간​\r\n2021년 4월 27일 18:00 - 2021년 5월 4일 17:59 베이징 시간​\r\n2021년 5월 4일 18:00 - 2021년 5월 11일 17:59 베이징 시간​\r\n2021년 5월 11일 18:00 - 2021년 5월 18일 17:59 베이징 시간\r\n4. 해당 게임: 머니 롤, 아이리쉬 매직, 888 골드, 다이아몬드는 영원히 3 라인, 퀸 오븝 골드, 테이블 게임, 비디오 포커를 제외한 모든 Pragmatic Play 게임.\r\n5. 참여 최소 금액은 $0.35나 이에 상응하는 통화입니다.\r\n6. 토너먼트 순위표에 점수가 동일한 플레이어가 두 명 이상 있으면 먼저 점수를 획득한 플레이어가 순위표에서 더높은 점수를 받습니다.\r\n7. 토너먼트 순위표는 해당 게임에 내장되어 있으며 실시간으로 업데이트됩니다.\r\n8. Pragmatic Play는 사전 통보 없이 언제든지 프로모션을수정, 일시 중단 또는 취소할 수 있는 권리를 보유합니다.","shortHtmlRules":"•총 상금 가치: ₩22,500,000\r\n•매주 토너먼트 승자 150명\r\n•매주 488개 캐쉬 드롭","prizePool":{"currency":"KRW","currencyOriginal":"KRW","prizesList":[{"placeFrom":1,"placeTo":1,"amount":1250000.0,"type":"A"},{"placeFrom":2,"placeTo":2,"amount":875000.0,"type":"A"},{"placeFrom":3,"placeTo":3,"amount":625000.0,"type":"A"},{"placeFrom":4,"placeTo":5,"amount":500000.0,"type":"A"},{"placeFrom":6,"placeTo":10,"amount":250000.0,"type":"A"},{"placeFrom":11,"placeTo":50,"amount":125000.0,"type":"A"},{"placeFrom":51,"placeTo":100,"amount":37500.0,"type":"A"},{"placeFrom":101,"placeTo":150,"amount":12500.0,"type":"A"}],"minBetLimit":35000,"totalPrizeAmount":1.25E7},"currencyRateMap":{"ETH4":0.295537006996248,"SRD":0.0706514059629787,"IDN":6.92265444204977E-5,"NIO":0.028479223423979,"BRL":0.183782989046534,"BYN":0.388655609155482,"RM":0.244170430960811,"UBT":0.0566448838765719,"SAR":0.266652445202923,"PYG":1.52472588046242E-4,"KGS":0.0117926194287509,"NGN":0.00243433384454344,"LTC":269.330002834429,"TOP":0.442336171951994,"Yen":1.0,"CUC":1.0,"RWF":9.98280820667496E-4,"XCD":0.370020906181199,"MWK":0.00126197350682985,"JEP":1.38187014013545,"JOD":1.40984068800226,"MPT":0.00244170430960811,"ETH":2955.37006996248,"IDR1":0.0692265444204977,"SOS":0.0017169743410856,"GMD":0.0195503421309873,"$US":1.0,"SDG":0.00257731958762887,"PTU":0.87725147772674,"DOGE":0.377735916610909,"SZL":0.0691735435544991,"NZD":0.716896754680081,"TMT":0.284900284900285,"LIR":0.120599432241993,"AMD":0.00191123583572849,"KZT":0.00231348464147529,"IQX":0.682349685389099,"IQD":6.82349685389099E-4,"TND":0.363901018922853,"XAF":0.00183342807603473,"HNL":0.0414194872474576,"YNG":4.31810997922873E-5,"NAD":0.0698324022346369,"IRT":2.37501484384277E-4,"TUSD":1.0,"MBC":56.6448838765719,"ZMW":0.0445354868557073,"SLL":9.78114667682651E-5,"GBP":1.38187014013545,"BHD":2.65232993923512,"MYR":0.244170430960811,"AWG":0.555401277422938,"BTC":56644.8838765719,"YER":0.00399321107795499,"MTL":1.46283948847429,"KRW2":0.894647860429065,"VEF":3.44729753091692E-12,"OMR":2.59749705184085,"TJS":0.0872452351887254,"MKD":0.0195414554487743,"mBTC":56.6448838765719,"PE":0.020742184827013,"VNX":0.0431810997922873,"VES":3.44729753091692E-7,"LVL":1.20265015989234,"KYD":1.19399563475196,"ANG":0.554323417780811,"ARS":0.0106484252043965,"AUD":0.77210001165871,"PAB":1.0,"HKK":0.128749010241984,"ZWL":0.0031055900621118,"UYU":0.0226971690592736,"CLF":38.8259046435782,"tCAD":0.00814320974383905,"LKR":0.00503814152753197,"BGN":0.614784585629041,"XOF":0.00183342807603473,"CVE":0.0108754758020663,"mXMR":0.405617576285714,"BMD":1.0,"DKK":0.161732739882,"HK$":0.128749010241984,"RMB":0.154452081241795,"COP":2.6800175722749E-4,"GEL":0.289017341040462,"ALL":0.00974246742133508,"YUN":0.154452081241795,"TTD":0.146482930197661,"TB":0.0320821302534488,"NTD":1.0,"HUF":0.00334068283557159,"GYD":0.00475604314256197,"UZS":9.46175747639057E-5,"MNT":3.50775505994088E-4,"TRY":0.120599432241993,"KAY":0.01,"tGBP":0.0138187014013545,"LYD":0.222702765300237,"TWD":0.0358918233365692,"AFN":0.0128714164673324,"BOB":0.144103369381752,"HTG":0.0116514154687128,"VND2":0.0431810997922873,"WDW":0.0894647860429065,"KMF":0.0024428977416539,"SBD":0.125649497961148,"XB1":0.0566448838765719,"IDR":6.92265444204977E-5,"STD":4.82204972521054E-5,"AZN":0.587956879242476,"XPT":1202.99304670019,"MMK2":0.638864082488669,"FJD":0.492261646910566,"JMD":0.00648503484824266,"TZS":4.31354279897165E-4,"AOA":0.00152827850124784,"IDD":0.0692265444204977,"PTH":0.107969412643291,"KPW":0.00111111111111111,"MGA":2.62037191595792E-4,"UGX":2.78175034057686E-4,"SEK":0.118130281161882,"LBP":6.57355093719559E-4,"UAH":0.035844390610949,"NOK":0.120200687067127,"PS":0.607327946118512,"ILS":0.30834999451137,"XMR":405.617576285714,"GOLD":1.0,"MKK":0.638864082488669,"DDD":0.0692265444204977,"AED":0.272256269381243,"LSL":0.0690649564894227,"MOP":0.124414675606773,"INH":0.0431810997922873,"SGD":0.751710140569796,"LAK":1.05872735134334E-4,"mLTC":0.269330002834429,"IRR":2.37501484384277E-5,"SVC":0.11371759418148,"EEK":0.0686468066878465,"tEUR":0.0120265015989234,"VND":4.31810997922873E-5,"INR":0.0134961765804114,"DJF":0.00559507061745644,"CHF":1.09520388315489,"KHR":2.4602746658313E-4,"PHP":0.020742184827013,"KES":0.00923020121838656,"EGP":0.0638970939857244,"CUP":0.0388349514563107,"GIP":1.38187014013545,"EUR":1.20265015989234,"ETH7":2.95537006996248E-4,"MVR":0.0648508430609598,"ISK":0.00798594473726242,"HKD":0.128749010241984,"IDR2":0.0692265444204977,"Won":8.94647860429065E-4,"IRX":0.0692265444204977,"BTC3":56.6448838765719,"BZD":0.493632388998119,"MXN":0.0494784966453579,"DZD":0.0075013618534911,"mETH":2.95537006996248,"PGK":0.280490746610269,"KRW":8.94647860429065E-4,"NT":0.0358918233365692,"BTN":0.0134319575713473,"THB":0.0320821302534488,"XDR":1.43320477486503,"CNY":0.154452081241795,"US$":1.0,"BDT":0.0117415020497316,"BTC6":0.0566448838765719,"XPF":0.0100782068854309,"PLN":0.263743474656758,"BAM":0.614570356936318,"ERN":0.0666579122608564,"UT":1.0,"USD":1.0,"XJC":1.20265015989234E-7,"LRD":0.0058122635051735,"tUSD":0.01,"LTC8":2.69330002834429E-6,"NPR":0.00839499435701072,"DOP":0.0175282588836897,"DOG6":3.77735916610909E-7,"LTC6":2.69330002834429E-4,"ETH9":2.95537006996248E-6,"LTL":0.311078178301302,"IN":0.0692265444204977,"MAD":0.111566376304016,"HKD1":0.0128749010241984,"SYP":7.95168048123647E-4,"CDF":5.00269871082225E-4,"ETB":0.0237435286715811,"MDL":0.0563758333264273,"IQD2":0.682349685389099,"EURO":1.20265015989234,"GTQ":0.128932073684164,"XXX":1.0E-9,"BYR":4.99344610199114E-5,"BIF":5.1015510752318E-4,"SCR":0.067528460376089,"BSD":1.0,"BBD":0.5,"RON":0.245821042281219,"DASH":315.455363286914,"BND":0.749139987294586,"GNF":1.00557214343285E-4,"VUV":0.00912871591684368,"PEN":0.263079040256092,"PKR":0.0064976612415592,"XAG":25.8644814973259,"XAU":1769.12870411322,"MMK":6.38864082488669E-4,"IMP":1.38187014013545,"SD$":0.751710140569796,"WST":0.39496592234022,"ZMK":1.9040275866027E-4,"KWD":3.3205492188408,"MRO":0.00280112179773935,"VNDP":0.0431810997922873,"GHS":0.172532156111926,"FKP":1.38187014013545,"MUR":0.0248138963973672,"CRC":0.00161080139239412,"KHR2":0.24602746658313,"QAR":0.27437604828799,"ZAR":0.0690986632172614,"DOG":3.77735916610909E-7,"XPD":2947.85248946143,"HRK":0.159283479197179,"MZN":0.0173771004570177,"BWP":0.0915924371025575,"RUB":0.0132921277373476,"CZK":0.0465075179402751,"CAD":0.814320974383905,"JPY":0.00915013542200425,"CLP":0.00140706442273867,"XB0":56.6448838765719,"GGP":1.38187014013545,"IND":0.0692265444204977,"SHP":1.38187014013545,"RSD":0.0102210457526}}]}';
            return response($data, 200)->header('Content-Type', 'application/json');
        }
        public function promotournamentleaderboard(\Illuminate\Http\Request $request)
        {
            $data = '{"error":0,"description":"OK"}';
            return response($data, 200)->header('Content-Type', 'application/json');
        }

    }

}

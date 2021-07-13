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
            $gamelist = PPController::getgamelist('pp');
            $gamelist_live = PPController::getgamelist('live');
            $gamelist = array_merge_recursive($gamelist, $gamelist_live);
            $gamename = $code;
            $type = 'table';
            if ($gamelist)
            {
                foreach($gamelist as $game)
                {
                    if ($game['gamecode'] == $code)
                    {
                        $gamename = $game['name'];
                        $type = $game['type'];
                        break;
                    }
                }
            }
            return [$gamename,$type];
        }

        public function microtime_string()
        {
            $microstr = sprintf('%.4f', microtime(TRUE));
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


            $user = \VanguardLTE\User::lockForUpdate()->Where('api_token',$token)->get()->first();
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

            $user = \VanguardLTE\User::lockForUpdate()->find($userId);
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
            $herestamp = $this->microtime_string();
            if (!$userId || !$gameId || !$roundId || !$amount || !$reference || !$providerId || !$timestamp || $amount < 0)
            {
                return response()->json([
                    'error' => 7,
                    'description' => 'paramenter incorrect']);
            }

            \DB::beginTransaction();

            $user = \VanguardLTE\User::lockForUpdate()->find($userId);
            if (!$user || !$user->hasRole('user')){
                \DB::commit();
                return response()->json([
                    'error' => 2,
                    'description' => 'player not found']);
            }

            /*$transaction = $this->checkreference($reference);
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
            } */

            if ($user->balance < $amount)
            {
                \DB::commit();
                return response()->json([
                    'error' => 1,
                    'description' => 'insufficient balance']);
            }

            $user->balance = floatval(sprintf('%.4f', $user->balance - floatval($amount)));
            $user->save();

            $game = $this->gamecodetoname($gameId);

            \VanguardLTE\StatGame::create([
                'user_id' => $user->id, 
                'balance' => floatval($user->balance), 
                'bet' => floatval($amount), 
                'win' => 0, 
                'game' => $game[0] . '_pp', 
                'type' => $game[1],
                'percent' => 0, 
                'percent_jps' => 0, 
                'percent_jpg' => 0, 
                'profit' => 0, 
                'denomination' => 0, 
                'shop_id' => $user->shop_id
            ]);

            $req = $request->all();
            $req['herestamp'] = $herestamp;

            $transaction = \VanguardLTE\PPTransaction::create([
                'reference' => $reference, 
                'timestamp' => $this->microtime_string(),
                'data' => json_encode($req)
            ]);

            \DB::commit();

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

            \DB::beginTransaction();

            $user = \VanguardLTE\User::lockForUpdate()->find($userId);
            if (!$user || !$user->hasRole('user')){
                \DB::commit();
                return response()->json([
                    'error' => 2,
                    'description' => 'player not found']);
            }
            /*$transaction = $this->checkreference($reference);
            if ($transaction)
            {
                return response()->json([
                    'transactionId' => strval($transaction->timestamp),
                    'currency' => 'KRW',
                    'cash' => floatval($user->balance),
                    'bonus' => 0,
                    'error' => 0,
                    'description' => 'success']);
            }*/

            $user->balance = floatval(sprintf('%.4f', $user->balance + floatval($amount)));
            $game = $this->gamecodetoname($gameId);
            \VanguardLTE\StatGame::create([
                'user_id' => $user->id, 
                'balance' => floatval($user->balance), 
                'bet' => 0, 
                'win' => floatval($amount), 
                'game' => $game[0] . '_pp', 
                'type' => $game[1],
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
                $game = $this->gamecodetoname($gameId);
                \VanguardLTE\StatGame::create([
                    'user_id' => $user->id, 
                    'balance' => floatval($user->balance), 
                    'bet' => 0, 
                    'win' => floatval($promoWinAmount), 
                    'game' => $game[0] . '_pp promo', 
                    'type' => $game[1],
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

            \DB::commit();
            

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

            \DB::beginTransaction();

            $user = \VanguardLTE\User::lockForUpdate()->find($userId);
            if (!$user || !$user->hasRole('user')){
                \DB::commit();
                return response()->json([
                    'error' => 2,
                    'description' => 'player not found']);
            }

            /*$transaction = $this->checkreference($reference);
            if ($transaction)
            {
                return response()->json([
                    'transactionId' => strval($transaction->timestamp),
                    'currency' => 'KRW',
                    'cash' => floatval($user->balance),
                    'bonus' => 0,
                    'error' => 0,
                    'description' => 'success']);
            }*/


            //$user->balance = floatval(sprintf('%.4f', $user->balance + floatval($amount)));
            //$user->save();
            $transaction = \VanguardLTE\PPTransaction::create([
                'reference' => $reference, 
                'timestamp' => $this->microtime_string(),
                'data' => json_encode($request->all())
            ]);
            \DB::commit();

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

            \DB::beginTransaction();

            $user = \VanguardLTE\User::lockForUpdate()->find($userId);
            if (!$user || !$user->hasRole('user')){
                \DB::commit();
                return response()->json([
                    'error' => 2,
                    'description' => 'player not found']);
            }

            /*$transaction = $this->checkreference($reference);
            if ($transaction)
            {
                return response()->json([
                    'transactionId' => strval($transaction->timestamp),
                    'currency' => 'KRW',
                    'cash' => floatval($user->balance),
                    'bonus' => 0,
                    'error' => 0,
                    'description' => 'success']);
            }*/

            $user->balance = floatval(sprintf('%.4f', $user->balance + floatval($amount)));
            $user->save();
            $transaction = \VanguardLTE\PPTransaction::create([
                'reference' => $reference, 
                'timestamp' => $this->microtime_string(),
                'data' => json_encode($request->all())
            ]);
            $game = $this->gamecodetoname($gameId);
            \VanguardLTE\StatGame::create([
                'user_id' => $user->id, 
                'balance' => floatval($user->balance), 
                'bet' => 0, 
                'win' => floatval($amount), 
                'game' => $game[0] . '_pp JP', 
                'type' => $game[1],
                'percent' => 0, 
                'percent_jps' => 0, 
                'percent_jpg' => 0, 
                'profit' => 0, 
                'denomination' => 0, 
                'shop_id' => $user->shop_id
            ]);

            \DB::commit();

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

            \DB::beginTransaction();

            $user = \VanguardLTE\User::lockForUpdate()->find($userId);
            if (!$user || !$user->hasRole('user')){
                \DB::commit();
                return response()->json([
                    'error' => 2,
                    'description' => 'player not found']);
            }

            $transaction = $this->checkreference($reference);
            if (!$transaction)
            {
                \DB::commit();
                return response()->json([
                    'transactionId' => strval($this->generateCode(24)),
                    'error' => 0,
                    'description' => 'reference not found']);
            } 
            if ($transaction->refund > 0)
            {
                \DB::commit();
                return response()->json([
                    'transactionId' => strval('refund-' . $transaction->timestamp),
                    'error' => 0,
                    'description' => 'success']);
            }

            $data = json_decode($transaction->data, true);
            if (!isset($data['amount']))
            {
                \DB::commit();
                return response()->json([
                    'error' => 7,
                    'description' => 'bad reference to refund']);
            }


            $user->balance = floatval(sprintf('%.4f', $user->balance + floatval($data['amount'])));
            $user->save();
            $transaction->refund = 1;
            $transaction->save();
            $game = $this->gamecodetoname($data['gameId']);
            \VanguardLTE\StatGame::create([
                'user_id' => $user->id, 
                'balance' => floatval($user->balance), 
                'bet' => 0, 
                'win' => floatval($data['amount']), 
                'game' => $game[0] . '_pp refund', 
                'type' => $game[1],
                'percent' => 0, 
                'percent_jps' => 0, 
                'percent_jpg' => 0, 
                'profit' => 0, 
                'denomination' => 0, 
                'shop_id' => $user->shop_id
            ]);
            \DB::commit();



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
            \DB::beginTransaction();

            $user = \VanguardLTE\User::lockForUpdate()->find($userId);
            if (!$user || !$user->hasRole('user')){
                \DB::commit();
                return response()->json([
                    'error' => 2,
                    'description' => 'player not found']);
            }
            /*$transaction = $this->checkreference($reference);
            if ($transaction)
            {
                return response()->json([
                    'transactionId' => strval($transaction->timestamp),
                    'currency' => 'KRW',
                    'cash' => floatval($user->balance),
                    'bonus' => 0,
                    'error' => 0,
                    'description' => 'success']);
            }*/

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
            \DB::commit();

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
            $gameList = \Illuminate\Support\Facades\Redis::get($href.'list');
            if ($gameList)
            {
                $games = json_decode($gameList, true);
                if ($games!=null && count($games) > 0){
                    return $games;
                }
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
            $newgames = \VanguardLTE\NewGame::where('provider', 'pp')->get()->pluck('gameid')->toArray();
            if ($data['error'] == "0"){
                $gameList = [];
                foreach ($data['gameList'] as $game)
                {
                    if ($game['gameID'] == '106' || $game['gameID'] == '110' || $game['gameID'] == '111')
                    {

                    }
                    else if (str_contains($game['platform'], 'WEB'))
                    {
                        $bExclude = ($href == 'live')  ^ ($game['gameTypeID'] == 'lg');
                        if ($bExclude == false){
                            if (in_array($game['gameID'] , $newgames) )
                            {
                                array_unshift($gameList, [
                                    'provider' => 'pp',
                                    'gamecode' => $game['gameID'],
                                    'enname' => $game['gameName'],
                                    'name' => preg_replace('/\s+/', '', $game['gameName']),
                                    'title' => __('gameprovider.'.$game['gameName']),
                                    'icon' => config('app.ppgameserver') . '/game_pic/rec/325/'. $game['gameID'] . '.png',
                                    'type' => ($game['gameTypeID']=="vs" || $game['gameTypeID']=="cs" || $game['gameTypeID']=="sc")?'slot':'table',
                                    'gameType' => $game['gameTypeID'],
                                    'demo' => 'https://demogamesfree-asia.pragmaticplay.net/gs2c/openGame.do?gameSymbol='.$game['gameID'].'&lang=ko&cur=KRW&lobbyURL='. \URL::to('/')
                                ]);
                            }
                            else
                            {
                                array_push($gameList, [
                                    'provider' => 'pp',
                                    'gamecode' => $game['gameID'],
                                    'enname' => $game['gameName'],
                                    'name' => preg_replace('/\s+/', '', $game['gameName']),
                                    'title' => __('gameprovider.'.$game['gameName']),
                                    'icon' => config('app.ppgameserver') . '/game_pic/rec/325/'. $game['gameID'] . '.png',
                                    'type' => ($game['gameTypeID']=="vs" || $game['gameTypeID']=="cs" || $game['gameTypeID']=="sc")?'slot':'table',
                                    'gameType' => $game['gameTypeID'],
                                    'demo' => 'https://demogamesfree-asia.pragmaticplay.net/gs2c/openGame.do?gameSymbol='.$game['gameID'].'&lang=ko&cur=KRW&lobbyURL='. \URL::to('/')
                                ]);
                            }
                        }
                    }
                }
                \Illuminate\Support\Facades\Redis::set($href.'list', json_encode($gameList));
                return $gameList;
            }
            return [];
        }

        public static function getgamelink_pp($gamecode, $token)
        {
            $detect = new \Detection\MobileDetect();
            $key = [
                'token' => $token,
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
            $url = config('app.ppgameserver') . '/gs2c/playGame.do?key='.urlencode($str_params) . '&stylename=' . config('app.ppsecurelogin');
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
            $promo = \VanguardLTE\PPPromo::take(1)->first();
            if ($promo){
                $data = $promo->games;
            }
            else{
                $data = '';
            }
            return response($data, 200)->header('Content-Type', 'application/json');

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
            return response()->json($data);

        }

        public function minilobby_start(\Illuminate\Http\Request $request)
        {
            $gamecode = $request->gameSymbol;
            return redirect(route('frontend.providers.pp.render', $gamecode) . '?lobby=mini');
        }
        
        public function promoactive(\Illuminate\Http\Request $request)
        {
            $promo = \VanguardLTE\PPPromo::take(1)->first();
            if ($promo){
                $data = $promo->active;
                $json_data = json_decode($data, true);
                $json_data['serverTime'] = time();
                if (isset($json_data['tournaments'])){
                    $tour_count = count($json_data['tournaments']);
                    for ($i = 0; $i < $tour_count; $i++ )
                    {
                        $json_data['tournaments'][$i]['optin'] = true;
                    }
                }
                if (isset($json_data['races'])){
                    $race_count = count($json_data['races']);
                    for ($i = 0; $i < $race_count; $i++ )
                    {
                        $json_data['races'][$i]['optin'] = true;
                    }
                }
                $data = json_encode($json_data);
            }
            else{
                $data = '';
            }
            return response($data, 200)->header('Content-Type', 'application/json');

        }
        public function promoracedetails(\Illuminate\Http\Request $request)
        {
            $promo = \VanguardLTE\PPPromo::take(1)->first();
            if ($promo){
                $data = $promo->racedetails;
            }
            else{
                $data = '';
            }
            return response($data, 200)->header('Content-Type', 'application/json');
        }
        public function promoraceprizes(\Illuminate\Http\Request $request)
        {
            $promo = \VanguardLTE\PPPromo::take(1)->first();
            if ($promo){
                $data = $promo->raceprizes;
            }
            else{
                $data = '';
            }
            return response($data, 200)->header('Content-Type', 'application/json');
        }
        public function promoracewinners(\Illuminate\Http\Request $request)
        {
            $promo = \VanguardLTE\PPPromo::take(1)->first();
            if ($promo){
                $data = $promo->racewinners;
            }
            else{
                $data = '';
            }
            return response($data, 200)->header('Content-Type', 'application/json');
        }
        public function promotournamentdetails(\Illuminate\Http\Request $request)
        {
            $promo = \VanguardLTE\PPPromo::take(1)->first();
            if ($promo){
                $data = $promo->tournamentdetails;
            }
            else{
                $data = '';
            }
            return response($data, 200)->header('Content-Type', 'application/json');
        }
        public function promotournamentleaderboard(\Illuminate\Http\Request $request)
        {
            $promo = \VanguardLTE\PPPromo::take(1)->first();
            if ($promo){
                $data = $promo->tournamentleaderboard;
            }
            else{
                $data = '';
            }
            return response($data, 200)->header('Content-Type', 'application/json');
        }
        public function promochoice(\Illuminate\Http\Request $request)
        {
            $data = '{"error":0,"description":"OK"}';
            return response($data, 200)->header('Content-Type', 'application/json');
        }

        public static function syncpromo()
        {
            $anyuser = \VanguardLTE\User::where('role_id', 1)->whereNotNull('api_token')->first();
            if (!$anyuser)
            {
                return ['error' => true, 'msg' => 'not found any available user.'];
            }
            $url = PPController::getgamelink_pp('vs5aztecgems', $anyuser->api_token);
            $response = Http::withOptions(['allow_redirects' => false])->get($url['data']['url']);
            if ($response->status() == 302)
            {
                $location = $response->header('location');
                $keys = explode('&', $location);
                $mgckey = null;
                foreach ($keys as $key){
                    if (str_contains( $key, 'mgckey='))
                    {
                        $mgckey = $key;
                        break;
                    }
                }
                if (!$mgckey){
                    return ['error' => true, 'msg' => 'could not find mgckey value'];
                }
                $promo = \VanguardLTE\PPPromo::take(1)->first();
                if (!$promo)
                {
                    $promo = \VanguardLTE\PPPromo::create();
                }
                $raceIds = [];
                $response =  Http::get(config('app.ppgameserver') . '/gs2c/promo/active/?symbol=vs5aztecgems&' . $mgckey );
                if ($response->ok())
                {
                    $promo->active = $response->body();
                    $json_data = $response->json();
                    if (isset($json_data['races']))
                    {
                        foreach ($json_data['races'] as $race)
                        {
                            $raceIds[$race['id']] = null;
                        }
                    }
                }
                $response =  Http::get(config('app.ppgameserver') . '/gs2c/promo/tournament/details/?symbol=vs5aztecgems&' . $mgckey );
                if ($response->ok())
                {
                    $promo->tournamentdetails = $response->body();
                }
                $response =  Http::get(config('app.ppgameserver') . '/gs2c/promo/race/details/?symbol=vs5aztecgems&' . $mgckey );
                if ($response->ok())
                {
                    $promo->racedetails = $response->body();
                }
                $response =  Http::get(config('app.ppgameserver') . '/gs2c/promo/tournament/v2/leaderboard/?symbol=vs5aztecgems&' . $mgckey );
                if ($response->ok())
                {
                    $promo->tournamentleaderboard = $response->body();
                }
                $response =  Http::get(config('app.ppgameserver') . '/gs2c/promo/race/prizes/?symbol=vs5aztecgems&' . $mgckey );
                if ($response->ok())
                {
                    $promo->raceprizes = $response->body();
                }

                $response =  Http::post(config('app.ppgameserver') . '/gs2c/promo/race/winners/?symbol=vs5aztecgems&' . $mgckey , ['latestIdentity' => $raceIds]);
                if ($response->ok())
                {
                    $promo->racewinners = $response->body();
                }

                $response =  Http::get(config('app.ppgameserver') . '/gs2c/minilobby/games?' . $mgckey );
                if ($response->ok())
                {
                    $promo->games = $response->body();
                }

                $promo->save();
                return ['error' => false, 'msg' => 'synchronized successfully.'];
            }
            else
            {
                return ['error' => true, 'msg' => 'server response is not 302.'];
            }
            
        }

        public function replayList(\Illuminate\Http\Request $request)
        {
            $data = '{"error":0,"description":"OK","topList":[{"roundID":4245536090615,"bet":10000.0,"base_bet":10000.0,"win":1089500.0,"rtp":108.95,"playedDate":1613551775000}]}';
            return response($data, 200)->header('Content-Type', 'application/json');
        }

        public function replayLink(\Illuminate\Http\Request $request)
        {
            $data = '{"error":0,"description":"OK"}';
            return response($data, 200)->header('Content-Type', 'application/json');
        }

        public function replayGo(\Illuminate\Http\Request $request)
        {
            $userId = \Illuminate\Support\Facades\Auth::id();
            $object = '\VanguardLTE\Games\\' . 'TheDogHousePM' . '\SlotSettings';
            $slot = new $object('TheDogHousePM', $userId);

            $game = \VanguardLTE\Game::where([
                'name' => 'TheDogHousePM', 
                'shop_id' => \Illuminate\Support\Facades\Auth::user()->shop_id
            ]);
            $game = $game->first();
            if( !$game ) 
            {
                return redirect()->route('frontend.game.list');
            }
            if( !$game->view ) 
            {
                return redirect()->route('frontend.game.list');
            }
            $is_api = false;
            $replay = "true";
            return view('frontend.games.list.' . $game->name, compact('slot', 'game', 'is_api', 'replay'));
        }

        public function replayData(\Illuminate\Http\Request $request)
        {
            $data = '{"error":0,"description":"OK","init":"rt=d&def_s=9,3,2,3,9,10,4,1,4,10,9,3,2,3,9&sa=8,9,10,11,12&sb=8,10,8,11,7&sc=0.01&defc=0.01&sh=3&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&cfgs=2311&ver=2&c=0.01&n_reel_set=0&reel_set_size=2&def_sb=8,10,8,11,7&def_sa=8,9,10,11,12&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;750,150,50,0,0;500,100,35,0,0;300,60,25,0,0;200,40,20,0,0;150,25,12,0,0;100,20,8,0,0;50,10,5,0,0;50,10,5,0,0;25,5,2,0,0;25,5,2,0,0;25,5,2,0,0&l=20&rtp=95.51&reel_set0=9,8,12,8,10,7,5,11,4,1,3,7,10,13,1,6,9,13,6,11,12~3,6,8,13,7,10,9,11,10,9,6,5,12,2,4,8,11,12,13,7~4,9,13,12,13,7,8,12,6,1,2,10,11,7,5,11,3,10,8,9,6~2,6,10,7,11,13,12,5,9,3,6,7,12,9,13,8,10,11,4,8~8,11,7,6,13,9,10,5,1,12,6,3,8,4,7,10,13,12,11,9&na=s&s=9,3,2,3,9,10,4,1,4,10,9,3,2,3,9&reel_set1=12,5,11,9,13,8,13,3,3,3,10,12,11,10,13,11,8,8,9,6,9,10,12,6,3,7,4,7,5~13,11,7,9,4,12,7,3,10,9,8,13,11,10,13,5,6,9,2,7,6,10,12,8,11~6,12,10,13,7,12,5,10,8,7,2,13,3,6,9,8,11,8,5,12,9,4,11,10,9,13~13,9,5,7,13,6,12,11,6,10,13,12,9,7,8,10,4,2,8,7,5,9,11,3,12,8,6,10,11~13,12,11,7,10,11,7,13,4,9,12,6,10,3,3,3,8,6,11,8,9,13,7,9,5,8,12&scatters=1~0,0,5,0,0~0,0,0,0,0~1,1,1,1,1&mbr=1,1,1&gmb=0,0,0&mbri=1,2,3","log":[{"cr":"symbol=vs20doghouse&c=618&repeat=0&action=doSpin&index=22&counter=43&l=20","sr":"mbv=2&tw=24,720.00&balance=7,046.00&index=22&balance_cash=7,046.00&balance_bonus=0.00&na=c&mbri=1,2,3&l0=19~24,720.00~0~11~12&stime=1613552282847&sa=13,10,5,11,8&sb=4,8,12,7,5&sh=3&c=618.00&sver=5&n_reel_set=0&counter=44&l=20&s=6,7,9,8,12,8,3,10,12,9,10,6,2,10,7&w=24,720.00&mbp=12&mbr=2,2,3"},{"cr":"symbol=vs20doghouse&repeat=0&action=doCollect&index=23&counter=45","sr":"balance=31,766.00&index=23&balance_cash=31,766.00&balance_bonus=0.00&na=s&stime=1613552282995&sver=5&counter=46"}]}
            ';
            return response($data, 200)->header('Content-Type', 'application/json');
        }

    }

}

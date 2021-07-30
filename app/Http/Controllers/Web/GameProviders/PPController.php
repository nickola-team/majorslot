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
            }*/

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
                'here' => $herestamp,
                'time' => $this->microtime_string(),
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
                $json_data = json_decode($data, true);
                $json_data['gameLaunchURL'] = "/gs2c/minilobby/start";
                $data = json_encode($json_data);
            }
            else{
                $data = '';
            }
            return response($data, 200)->header('Content-Type', 'application/json');
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

    }

}

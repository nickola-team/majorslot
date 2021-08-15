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
        public static function microtime_string_st()
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

            // // \DB::beginTransaction();

            $user = \VanguardLTE\User::lockForUpdate()->find($userId);
            if (!$user || !$user->hasRole('user')){
                // // ::commit();
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
                // ::commit();
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

            // ::commit();

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

            // \DB::beginTransaction();

            $user = \VanguardLTE\User::lockForUpdate()->find($userId);
            if (!$user || !$user->hasRole('user')){
                // ::commit();
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

            // ::commit();
            

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

            // \DB::beginTransaction();

            $user = \VanguardLTE\User::lockForUpdate()->find($userId);
            if (!$user || !$user->hasRole('user')){
                // ::commit();
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
            // ::commit();

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

            // \DB::beginTransaction();

            $user = \VanguardLTE\User::lockForUpdate()->find($userId);
            if (!$user || !$user->hasRole('user')){
                // ::commit();
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

            // ::commit();

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

            // \DB::beginTransaction();

            $user = \VanguardLTE\User::lockForUpdate()->find($userId);
            if (!$user || !$user->hasRole('user')){
                // ::commit();
                return response()->json([
                    'error' => 2,
                    'description' => 'player not found']);
            }

            $transaction = $this->checkreference($reference);
            if (!$transaction)
            {
                // ::commit();
                return response()->json([
                    'transactionId' => strval($this->generateCode(24)),
                    'error' => 0,
                    'description' => 'reference not found']);
            } 
            if ($transaction->refund > 0)
            {
                // ::commit();
                return response()->json([
                    'transactionId' => strval('refund-' . $transaction->timestamp),
                    'error' => 0,
                    'description' => 'success']);
            }

            $data = json_decode($transaction->data, true);
            if (!isset($data['amount']))
            {
                // ::commit();
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
            // ::commit();



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
            // \DB::beginTransaction();

            $user = \VanguardLTE\User::lockForUpdate()->find($userId);
            if (!$user || !$user->hasRole('user')){
                // ::commit();
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
            // ::commit();

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
                ])->get(config('app.ppapi') . '/http/CasinoGameAPI/getCasinoGames/', $data);
            if (!$response->ok())
            {
                return [];
            }
            $data = $response->json();
            $newgames = \VanguardLTE\NewGame::where('provider', $href)->get()->toArray();
            if ($data['error'] == "0"){
                $gameList = [];
                foreach ($data['gameList'] as $game)
                {
                    if ($game['gameID'] == '106' || $game['gameID'] == '110' || $game['gameID'] == '111' || $game['gameID'] == '535' || $game['gameID'] == '536')
                    {

                    }
                    else if (str_contains($game['platform'], 'WEB'))
                    {
                        $bExclude = ($href == 'live')  ^ ($game['gameTypeID'] == 'lg');
                        if ($bExclude == false){
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
            usort($gameList, function ($a,$b) use($newgames)
                {
                    $a_id = 999;
                    $b_id = 999;
                    foreach ($newgames as $game)
                    {
                        if ($a['gamecode'] == $game['gameid'])
                        {
                            $a_id = $game['id'];
                        }
                        if ($b['gamecode'] == $game['gameid'])
                        {
                            $b_id = $game['id'];
                        }
                    }
                    return $a_id - $b_id;
                });
                \Illuminate\Support\Facades\Redis::set($href.'list', json_encode($gameList));
                return $gameList;
            }
            return [];
        }

        public static function makelink($gamecode, $userid)
        {
            $user = \VanguardLTE\User::where('id', $userid)->first();
            $data = PPController::getBalance($userid);
            if ($data['error'] == -1) {
                //연동오류
                $data['msg'] = 'balance';
                return null;
            }
            else if ($data['error'] == 17) //Player not found
            {
                $data = PPController::createPlayer($user->id);
                if ($data['error'] != 0) //create player failed
                {
                    //오류
                    $data['msg'] = 'createplayer';
                    return null;
                }
            }
            else if ($data['error'] == 0)
            {
                if ($data['balance'] > 0) //이미 밸런스가 있다면
                {
                    if ($user->playing_game == 'pp') //이전에 게임을 하고있었다면??
                    {
                        //유저의 밸런스를 업데이트
                        $user->update(['balance' => $data['balance']]);
                    }
                    else //밸런스 초기화
                    {
                        $data = PPController::transfer($user->id, -$data['balance']);
                        if ($data['error'] != 0)
                        {
                            return null;
                        }
                    }
                }
            }
            else //알수 없는 오류
            {
                //오류
                $data['msg'] = 'balance';
                return null;
            }
            //밸런스 넘기기
            if ($user->playing_game == 'pp') //이전에 게임을 하고있었다면??,
            {
                //유저의 밸런스가 게임사 밸런스로 업데이트되었으므로, ...
            }
            else
            {
                if ($user->balance > 0) {
                    $data = PPController::transfer($user->id, $user->balance);
                    if ($data['error'] != 0)
                    {
                        return null;
                    }
                }
            }
                
            return '/providers/pp/'.$gamecode;
        }

        public static function getgamelink_pp($gamecode, $user) //at BT integration, $token is userId
        {
            $detect = new \Detection\MobileDetect();
            if (config('app.ppmode') == 'bt') // BT integration mode
            {
                $data = [
                    'secureLogin' => config('app.ppsecurelogin'),
                    'externalPlayerId' => $user->id,
                    'gameId' => $gamecode,
                    'language' => 'ko',
                    'lobbyURL' => \URL::to('/'),
                ];
                $data['hash'] = PPController::calcHash($data);
                $response = Http::withHeaders([
                    'Content-Type' => 'application/x-www-form-urlencoded'
                    ])->asForm()->post(config('app.ppapi') . '/http/CasinoGameAPI/game/start/', $data);
                if (!$response->ok())
                {
                    return ['error' => true, 'data' => '제공사응답 오류', 'original' => $response->body()];
                }
                $data = $response->json();
                if ($data['error'] == 0)
                {
                    return ['error' => false, 'data' => ['url' => $data['gameURL']]];
                }
                return ['error' => true, 'data' => '제공사응답 오류', 'original' => $data];
            }
            else // seamless integration mode
                {
                
                $key = [
                    'token' => $user->api_token,
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
            }
            return ['error' => false, 'data' => ['url' => $url]];
        }

        /*
            FOR BALANCE TRANSFER INTEGRATION
        */

        public function userbet(\Illuminate\Http\Request $request)
        {
            $user = \VanguardLTE\User::lockForUpdate()->Where('id',auth()->id())->get()->first();
            if (!$user)
            {
                return response()->json([
                    'error' => '1',
                    'description' => 'unlogged']);
            }
            if ($request->name == 'notifyCloseContainer')
            {
                $data = PPController::getBalance($user->id);
                if ($user->playing_game=='pp' && $data['error'] == 0) {
                    $user->update([
                        'balance' => $data['balance'],
                        'playing_game' => null,
                        'played_at' => time()
                    ]);
                }
                else
                {
                    $user->update([
                        'playing_game' => null,
                        'played_at' => time()
                    ]);
                }
            }
            else
            {
                $user->update([
                    'playing_game' => 'pp',
                    'played_at' => time()
                ]);
            }
            return response()->json([
                'error' => '0',
                'description' => 'OK']);
        }
        public static function createPlayer($userId)
        {
            $data = [
                'secureLogin' => config('app.ppsecurelogin'),
                'externalPlayerId' => $userId,
                'currency' => 'KRW',
            ];
            $data['hash'] = PPController::calcHash($data);
            $response = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded'
                ])->asForm()->post(config('app.ppapi') . '/http/CasinoGameAPI/player/account/create/', $data);
            if (!$response->ok())
            {
                return ['error' => '-1', 'description' => '제공사응답 오류'];
            }
            $data = $response->json();
            return $data;
        }
        public static function transfer($userId, $amount)
        {
            $transaction = \VanguardLTE\PPTransaction::create([
                'reference' => $userId, 
                'timestamp' => PPController::microtime_string_st(),
                'data' => $amount
            ]);
            $data = [
                'secureLogin' => config('app.ppsecurelogin'),
                'externalPlayerId' => $userId,
                'externalTransactionId' => $transaction->id,
                'amount' => $amount,
            ];
            $data['hash'] = PPController::calcHash($data);
            $response = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded'
                ])->asForm()->post(config('app.ppapi') . '/http/CasinoGameAPI/balance/transfer/', $data);
            if (!$response->ok())
            {
                $transaction->update(['refund' => 1]);
                return ['error' => '-1', 'description' => '제공사응답 오류'];
            }
            $data = $response->json();
            if ($data['error'] != 0)
            {
                $transaction->update(['refund' => 1]);
                return ['error' => '-1', 'description' => '밸런스 전송 오류'];
            }
            return $data;

            //check transfer status
            // $tryCount = 0;
            // $resdata['status'] = 'Not found';
            // while ($tryCount < 5 && $resdata['status'] != 'success') {
            //     $data = [
            //         'secureLogin' => config('app.ppsecurelogin'),
            //         'externalTransactionId' => $transaction->id,
            //     ];
            //     $data['hash'] = PPController::calcHash($data);
            //     $response = Http::withHeaders([
            //         'Content-Type' => 'application/x-www-form-urlencoded'
            //         ])->asForm()->post(config('app.ppapi') . '/http/CasinoGameAPI/balance/transfer/status/', $data);

            //     if ($response->ok())
            //     {
            //         $resdata = $response->json();
            //         if ($resdata == null)
            //         {
            //             $resdata['status'] = 'Not found';
            //         }
            //     }
            //     $tryCount = $tryCount + 1;
            //     sleep(1);
            // }

            // return $resdata;
        }

        public static function terminate($userId)
        {
            $data = [
                'secureLogin' => config('app.ppsecurelogin'),
                'externalPlayerId' => $userId,
            ];
            $data['hash'] = PPController::calcHash($data);
            $response = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded'
                ])->asForm()->post(config('app.ppapi') . '/http/CasinoGameAPI/game/session/terminate/', $data);
            if (!$response->ok())
            {
                return ['error' => '-1', 'description' => '제공사응답 오류'];
            }
            $data = $response->json();
            return $data;
        }

        public static function gamerounds($timepoint, $dataType='RNG')
        {
            $data = [
                'login' => config('app.ppsecurelogin'),
                'password' => config('app.ppsecretkey'),
                'dataType' => $dataType,
                'timepoint' => $timepoint,
            ];
            $response = null;
            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/x-www-form-urlencoded'
                    ])->get(config('app.ppapi') . '/DataFeeds/gamerounds/finished/', $data);
            } catch (\Exception $e) {
                return null;
            }
            if (!$response->ok())
            {
                return null;
            }
            $data = $response->body();
            return $data;
        }
        public static function getRoundBalance($gamestat)
        {
            $datetime = explode(' ', $gamestat->date_time);
            $data = [
                'secureLogin' => config('app.ppsecurelogin'),
                'playerId' => $gamestat->user_id,
                'datePlayed' => $datetime[0],
                'timeZone' => 'GMT+9',
                'gameId' => $gamestat->game_id,
                'hour' => explode(':',  $datetime[1])[0],
            ];
            $data['hash'] = PPController::calcHash($data);
            $response = null;
            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/x-www-form-urlencoded'
                    ])->asForm()->post(config('app.ppapi') . '/http/HistoryAPI/GetGameRounds/', $data);
            } catch (\Exception $e) {
                return null;
            }
            if (!$response->ok())
            {
                return null;
            }
            $data = $response->json();
            $balance = 0;
            foreach ($data['rounds'] as $round)
            {
                if ($round['roundId'] == $gamestat->roundid)
                {
                    $balance = $round['balance'];
                }
            }
            return $balance;
        }

        public static function processGameRound($dataType)
        {
            $tpoint = \VanguardLTE\Settings::where('key', $dataType . 'timepoint')->first();
            if ($tpoint)
            {
                $timepoint = $tpoint->value;
            }
            else
            {
                $timepoint = null;
            }

            //get category id
            $category = null;
            if ($dataType == 'RNG'){
                $category = \VanguardLTE\Category::where(['provider' => 'pp', 'shop_id' => 0, 'href' => 'pp'])->first();
            }
            else
            {
                $category = \VanguardLTE\Category::where(['provider' => 'pp', 'shop_id' => 0, 'href' => 'live'])->first();
            }
            
            $data = PPController::gamerounds($timepoint, $dataType);
            $count = 0;
            if ($data)
            {
                $parts = explode("\n", $data);
                $updatetime = explode("=",$parts[0]);
                if (count($updatetime) > 1) {
                    $timepoint = $updatetime[1];
                    if ($tpoint)
                    {
                        $tpoint->update(['value' => $timepoint]);
                    }
                    else
                    {
                        \VanguardLTE\Settings::create(['key' => $dataType .'timepoint', 'value' => $timepoint]);
                    }
                }
                //ignore $parts[2]
                for ($i=2;$i<count($parts);$i++)
                {
                    $round = explode(",", $parts[$i]);
                    if (count($round) < 2)
                    {
                        continue;
                    }
                    if ($round[7] == "C") {
                        $time = strtotime($round[5].' UTC');
                        $dateInLocal = date("Y-m-d H:i:s", $time);
                        $shop = \VanguardLTE\ShopUser::where('user_id', $round[1])->first();
                        \VanguardLTE\StatGame::create([
                            'user_id' => $round[1], 
                            'balance' => floatval(-1), 
                            'bet' => $round[9], 
                            'win' => $round[10], 
                            'game' => PPController::gamecodetoname($round[2])[0] . '_pp', 
                            'percent' => 0, 
                            'percent_jps' => 0, 
                            'percent_jpg' => 0, 
                            'profit' => 0, 
                            'denomination' => 0, 
                            'date_time' => $dateInLocal,
                            'shop_id' => $shop->shop_id,
                            'category_id' => isset($category)?$category->id:0,
                            'game_id' => $round[2],
                            'roundid' => $round[3],
                        ]);
                        $count = $count + 1;
                    }
                }
            }
            return [$count, $timepoint];
        }

        public static function getBalance($userId)
        {
            $data = [
                'secureLogin' => config('app.ppsecurelogin'),
                'externalPlayerId' => $userId,
            ];
            $data['hash'] = PPController::calcHash($data);
            $response = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded'
                ])->asForm()->post(config('app.ppapi') . '/http/CasinoGameAPI/balance/current/', $data);
            if (!$response->ok())
            {
                return ['error' => '-1', 'description' => '제공사응답 오류'];
            }
            $data = $response->json();
            return $data;
        }

        public static function getgamelink($gamecode)
        {
            return ['error' => false, 'data' => ['url' => route('frontend.providers.waiting', ['pp', $gamecode])]];
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
                ])->asForm()->post(config('app.ppapi') . '/http/FreeRoundsBonusAPI/createFRB', $data);
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
                ])->asForm()->post(config('app.ppapi') . '/http/FreeRoundsBonusAPI/cancelFRB', $data);
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
                ])->asForm()->post(config('app.ppapi') . '/http/FreeRoundsBonusAPI/getPlayersFRB', $data);
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
            return redirect(route('frontend.providers.waiting', ['pp', $gamecode]). '?lobby=mini');
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
            if (config('app.ppmode') == 'bt') // BT integration mode
            {
                $anyuser = \VanguardLTE\User::where('role_id', 1)->where('playing_game', 'pp')->first();
            }
            else
            {
                $anyuser = \VanguardLTE\User::where('role_id', 1)->whereNotNull('api_token')->first();
            }
            
            if (!$anyuser)
            {
                return ['error' => true, 'msg' => 'not found any available user.'];
            }
            $url = PPController::getgamelink_pp('vs5aztecgems', $anyuser);
            if ($url['error'] == true)
            {
                return ['error' => true, 'msg' => 'game link error'];
            }
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

<?php
namespace VanguardLTE\Http\Controllers\Web\GameProviders {
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Log;
    use VanguardLTE\Http\Controllers\Web\Frontend\CallbackController;
    class RGController extends \VanguardLTE\Http\Controllers\Controller
    {
        /*
         * UTILITY FUNCTION
         */
        // RG => RealGates
        const RG_PROVIDER = 'rg';
        const RG_EVOCODE = 756;
        const EVO_CODE = 'evolution';

        const RG_GAME_IDENTITY = [
            //==== SLOT ====
            'rg-evol' => ['vendor' => 'evolution']
        ];

        public static function getGameObj($uuid, $isvendor = false)
        {
            foreach (RGController::RG_GAME_IDENTITY as $ref => $value) {
                $gamelist = RGController::getgamelist($ref);
                if ($gamelist) {
                    foreach ($gamelist as $game) {
                        if ($isvendor && $game['view'] == 1 && $game['vendor'] == $uuid) {
                            return $game;
                        }
                        if ($game['gamecode'] == $uuid) {
                            return $game;
                        }
                    }
                }
            }
            return null;
        }

        /*
         */


        /*
         * FROM CONTROLLER, API
         */
        public static function getUserBalance($href, $user, $prefix = self::RG_PROVIDER)
        {
            $url = config('app.rg_api') . '/user';
            $token = config('app.rg_key');

            $param = [
                'username' => $prefix . sprintf("%04d", $user->id)
            ];
            $balance = -1;

            try {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $token
                ])->withBody(json_encode($param), 'application/json')->post($url);
                if ($response->getStatusCode() == 200) {
                    $res = $response->json();

                    if (isset($res['success']) && $res['success'] == 0 && isset($res['data'])) {
                        $balance = $res['data']['user_money'];
                    } else {
                        Log::error('RGgetuserbalance : return failed. ' . $res['message']);
                    }
                } else {
                    Log::error('RGgetuserbalance : response is not okay. ' . $response->body());
                }
            } catch (\Exception $ex) {
                Log::error('RGgetuserbalance : getUserBalance Excpetion. exception= ' . $ex->getMessage());
                Log::error('RGgamerounds : getUserBalance Excpetion. PARAMS= ' . json_encode($param));
            }

            return intval($balance);
        }
        public static function getgamelist($href)
        {
            $gameList = \Illuminate\Support\Facades\Redis::get($href . 'list');
            if ($gameList) {
                $games = json_decode($gameList, true);
                if ($games != null && count($games) > 0) {
                    return $games;
                }
            }
            $category = RGController::RG_GAME_IDENTITY[$href];

            $url = config('app.rg_api') . '/game-list';
            $token = config('app.rg_key');

            $param = [
                'vendor' => $category['vendor']
            ];


            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ])->get($url, $param);
            if ($response->getStatusCode() != 200) {
                return [];
            }
            $data = $response->json();
            if (isset($data['success']) && $data['success'] != 0) {
                return [];
            }
            $gameList = [];
            foreach ($data['data'] as $game) {
                $view = 0;
                if ($game['game_code'] == self::RG_EVOCODE) {
                    $view = 1;
                }
                array_push($gameList, [
                    'provider' => self::RG_PROVIDER,
                    'href' => $href,
                    'gamecode' => $game['vendor'],
                    'symbol' => $game['game_code'],
                    'vendor' => $game['vendor'],
                    'enname' => $game['vendor'],
                    'name' => preg_replace('/\s+/', '', $game['game_name']),
                    'title' => $game['game_name'],
                    'icon' => $game['thumbnail'],
                    'type' => ($game['game_type'] == 'slot') ? 'slot' : 'table',
                    'view' => $view
                ]);
            }

            //add Unknown Game item
            array_push($gameList, [
                'provider' => self::RG_PROVIDER,
                'href' => $href,
                'symbol' => $href,
                'gamecode' => $href,
                'enname' => 'UnknownGame',
                'name' => 'UnknownGame',
                'title' => 'UnknownGame',
                'icon' => '',
                'type' => 'Unknown',
                'view' => 0
            ]);

            if ($href == 'rg-evol') {
                $gameList = [];
                array_push($gameList, [
                    'provider' => self::RG_PROVIDER,
                    'vendorKey' => "evolution_casino",
                    'href' => $href,
                    'gameid' => self::EVO_CODE,
                    'symbol' => 'Lobby',
                    'gamecode' => self::EVO_CODE,
                    'enname' => 'Evolution',
                    'name' => 'Evolution',
                    'title' => '에볼루션',
                    'icon' => '',
                    'type' => 'table',
                    'view' => 1
                ]);
            }

            \Illuminate\Support\Facades\Redis::set($href . 'list', json_encode($gameList));
            return $gameList;

        }

        public static function makegamelink($gamecode, $prefix = self::RG_PROVIDER)
        {

            $token = config('app.rg_key');

            $game = RGController::getGameObj($gamecode);
            if (!$game) {
                return null;
            }
            $user = auth()->user();
            if ($user == null) {
                return null;
            }
            $alreadyUser = 1;
            $username = self::RG_PROVIDER . sprintf("%04d", $user->id);
            $param = [
                'username' => $username
            ];
            try {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $token
                ])->withBody(json_encode($param), 'application/json')->post(config('app.rg_api') . '/user');
                if ($response->getStatusCode() != 200) {
                    // Log::error('RGmakelink : checkUser request failed. ' . $response->body());
                    $alreadyUser = 0;
                }
                $data = $response->json();
                if ($data == null || !isset($data['success']) || $data['success'] != 0) {
                    $alreadyUser = 0;
                }
            } catch (\Exception $ex) {
                Log::error('RGcheckuser : checkUser Exception. Exception=' . $ex->getMessage());
                Log::error('RGcheckuser : checkUser Exception. PARAMS=' . $username);
                return null;
            }
            if ($alreadyUser == 0) {
                //create honor account
                try {

                    $url = config('app.rg_api') . '/user/create';
                    $param = [
                        'username' => $username
                    ];
                    $response = Http::withHeaders([
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $token
                    ])->withBody(json_encode($param), 'application/json')->post($url);
                    if ($response->getStatusCode() != 200) {
                        Log::error('RGmakelink : createAccount request failed. ' . $response->body());
                        return null;
                    }
                    $data = $response->json();
                    if ($data == null || !isset($data['success']) || $data['success'] != 0) {
                        Log::error('RGmakelink : createAccount request failed. ' . $response->body());
                        return null;
                    }
                } catch (\Exception $ex) {
                    Log::error('RGcheckuser : createAccount Exception. Exception=' . $ex->getMessage());
                    Log::error('RGcheckuser : createAccount Exception. PARAMS=' . json_encode($param));
                    return null;
                }
            }
            //Create Game link
            $category = RGController::RG_GAME_IDENTITY[$game['href']];
            // $real_code = explode('_', $game['gamecode'])[1];
            $real_code = $game['symbol'];
            $skin = 'A';
            if ($game['href'] == 'rg-evol') {
                $parent = $user;
                while ($parent) {
                    $provider_config = \VanguardLTE\ProviderInfo::where('user_id', $parent->id)->where('provider', 'evo')->first();
                    if ($provider_config) {
                        $evoSkin = \VanguardLTE\EvoSkins::where('skin', $provider_config->config)->first();
                        if (isset($evoSkin)) {
                            $skin = $evoSkin->rg_skin;
                        }
                        break;
                    }
                    $parent = $parent->referral;
                }

                $real_code = self::RG_EVOCODE;
            }
            $param = [
                'username' => $username,
                'vendor' => $category['vendor'],
                'game_code' => '' . $real_code,
                'skin' => '' . $skin
            ];

            try {
                $url = config('app.rg_api') . '/game-url';
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $token
                ])->withBody(json_encode($param), 'application/json')->post($url);
                if ($response->getStatusCode() != 200) {
                    Log::error('RGGetLink : Game url request failed. status=' . $response->status());
                    Log::error('RGGetLink : Game url request failed. param=' . json_encode($param));

                    return null;
                }
                $data = $response->json();
                if ($data == null || !isset($data['success']) || $data['success'] != 0 || !isset($data['data'])) {
                    Log::error('RGGetLink : Game url result failed. ' . ($data == null ? 'null' : json_encode($data)));
                    return null;
                }
                $url = $data['data']['url'];
                return $url;
            } catch (\Exception $ex) {
                Log::error('RGcheckuser : createAccount Exception. Exception=' . $ex->getMessage());
                Log::error('RGcheckuser : createAccount Exception. PARAMS=' . json_encode($param));
                return null;
            }

        }
        public static function getgamelink($gamecode)
        {
            $gameObj = RGController::getGameObj($gamecode);
            if (!$gameObj) {
                return ['error' => true, 'msg' => '게임이 없습니다'];
            }
            $url = RGController::makegamelink($gamecode);
            if ($url) {
                return ['error' => false, 'data' => ['url' => $url]];
            }
            return ['error' => true, 'msg' => '게임실행 오류입니다'];
        }
        public static function getgamedetail(\VanguardLTE\StatGame $stat)
        {
            $betrounds = explode('#', $stat->roundid);
            if (count($betrounds) < 3) {
                return null;
            }
            $transactionKey = $betrounds[2];
            try {
                $url = config('app.rg_api') . '/details';
                $param = [
                    'id' => $transactionKey
                ];
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . config('app.rg_key')
                ])->get($url, $param);
                $data = $response->json();
                if ($data == null || $data['data'] == "") {
                    return null;
                } else {
                    return $data['data'];
                }
            } catch (\Exception $ex) {
                Log::error('RGcheckuser : createAccount Exception. Exception=' . $ex->getMessage());
                Log::error('RGcheckuser : createAccount Exception. PARAMS=' . json_encode($param));
                return null;
            }
        }
        // Callback
        public function balance(\Illuminate\Http\Request $request)
        {
            Log::info('---- RG Balance CallBack ' . ' : Request PARAMS= ' . $request->username);

            $userid = intval(preg_replace('/' . self::RG_PROVIDER . '(\d+)/', '$1', isset($request->username) ? $request->username : ""));
            $user = \VanguardLTE\User::where(['id' => $userid, 'role_id' => 1])->first();

            if (!$user) {
                Log::error('RGchangeBalance : Not found user. PARAMS= ' . $request->username);
                return response()->json([
                    'result' => false,
                    'message' => 'Not found user',
                    'balance' => 0,
                ]);
            }
            if ($user->api_token == 'playerterminate') {
                Log::error('RGchangeBalance CallBack Balance : terminated by admin. PARAMS= ' . $request->username);
                return response()->json([
                    'result' => false,
                    'message' => 'terminated by admin',
                    'balance' => 0,
                ]);
            }

            $parent = $user->findAgent();

            if ($parent->callback) {
                $username = explode("#P#", $user->username)[1];
                $response = CallbackController::userBalance($parent->callback, $username);

                if ($response['status'] == 1) {
                    $user->balance = $response['balance'];
                    $user->save();

                    return response()->json([
                        'result' => true,
                        'message' => 'OK',
                        'balance' => intval($response['balance']),
                    ]);
                }

                return response()->json([
                    'result' => false,
                    'message' => $response['msg'],
                ]);
            }

            return response()->json([
                'result' => true,
                'message' => 'OK',
                'balance' => intval($user->balance)
            ]);
        }
        public function changeBalance(\Illuminate\Http\Request $request)
        {
            $data = json_decode($request->getContent(), true);

            Log::info('---- RG ChangeBalance CallBack ' . ' : Request PARAMS= ' . json_encode($data));

            $username = isset($data['username']) ? $data['username'] : "";
            $transaction = isset($data['transaction']) ? $data['transaction'] : [];
            $type = isset($transaction['type']) ? $transaction['type'] : "";
            // 응답에 없는 필드 이용 $time = date('Y-m-d H:i:s',strtotime($transaction['processed_at']));
            $time = date('Y-m-d H:i:s', time());
            $transactionid = isset($transaction['id']) ? $transaction['id'] : '';
            if ($type != 'bet' && isset($transaction['referer_id'])) {
                $transactionid = $transaction['referer_id'];
            }
            $amount = isset($transaction['amount']) ? $transaction['amount'] : -1;
            $roundid = (isset($transaction['details']) && isset($transaction['details']['game'])) ? $transaction['details']['game']['round'] : "";
            $gametitle = (isset($transaction['details']) && isset($transaction['details']['game'])) ? $transaction['details']['game']['title'] : "";
            $tablekey = (isset($transaction['details']) && isset($transaction['details']['game'])) ? $transaction['details']['game']['id'] : "";
            $vendor = (isset($transaction['details']) && isset($transaction['details']['game'])) ? $transaction['details']['game']['vendor'] : "";
            if ($username == "" || empty($transaction) || $type == "" || $amount == -1 || $roundid == "" || $gametitle == "" || $vendor == "") {
                Log::error('RGchangeBalance : callback param Error. PARAMS= ' . json_encode($data));
                return response()->json([
                    'result' => false,
                    'message' => 'No Parameter',
                    'balance' => 0,
                ]);
            }

            if ($vendor == 'evolution') {
                $gameObj = RGController::getGameObj(self::EVO_CODE);
            } else {
                $gameObj = RGController::getGameObj($vendor, true);
            }
            if (!$gameObj) {
                Log::error('RGchangeBalance : Not found gameObj. PARAMS= ' . json_encode($data));
                return response()->json([
                    'result' => false,
                    'message' => 'Not found Vendor',
                    'balance' => 0,
                ]);
            }
            \DB::beginTransaction();

            $userId = intval(preg_replace('/' . self::RG_PROVIDER . '(\d+)/', '$1', $username));
            $user = \VanguardLTE\User::lockforUpdate()->where(['id' => $userId, 'role_id' => 1])->first();

            Log::info(json_encode($user));

            if (!$user) {
                Log::error('RGchangeBalance : Not found User. PARAMS= ' . json_encode($data));
                \DB::commit();
                return response()->json([
                    'result' => false,
                    'message' => 'Not found user',
                    'balance' => 0,
                ]);
            }
            $checkGameStat = \VanguardLTE\StatGame::where([
                'user_id' => $userId,
                'bet_type' => $type,
                // 'date_time' => $time,
                'roundid' => $vendor . '#' . $roundid . '#' . $transactionid,
            ])->first();

            Log::info($checkGameStat);

            if ($checkGameStat) {
                Log::error('RGchangeBalance : Not Game History. Roundid= ' . $roundid);
                \DB::commit();
                return response()->json([
                    'result' => true,
                    'message' => 'Exist Game History',
                    'balance' => intval($user->balance),
                ]);
            }

            $betAmount = 0;
            $winAmount = 0;
            if ($type == 'bet') {
                $betAmount = $amount;
                $user->balance = $user->balance - intval($amount);
            } else {
                $winAmount = $amount;
                $user->balance = $user->balance + intval($amount);
            }
            $user->save();

            $category = \VanguardLTE\Category::where(['provider' => RGController::RG_PROVIDER, 'shop_id' => 0, 'href' => $gameObj['href']])->first();
            $gamename = $gametitle . '_' . $gameObj['href'];
            if ($type == 'cancel')  // 취소처리된 경우
            {
                $gamename = $gametitle . '_rg[C]_' . $gameObj['href'];
            }

            $result = \VanguardLTE\StatGame::create([
                'user_id' => $userId,
                'balance' => intval($user->balance),
                'bet' => $betAmount,
                'win' => $winAmount,
                'game' => $gamename,
                'type' => 'table',
                'bet_type' => $type,
                'percent' => 0,
                'percent_jps' => 0,
                'percent_jpg' => 0,
                'profit' => 0,
                'denomination' => 0,
                'shop_id' => $user->shop_id,
                'date_time' => $time,
                'category_id' => isset($category) ? $category->original_id : 0,
                'game_id' => $gameObj['gamecode'],
                'roundid' => $vendor . '#' . $roundid . '#' . $transactionid,
                'transactionid' => $transactionid,
                'tablekey' => $gameObj['gamecode'] == 'evolution' ? $tablekey : '',
            ]);
            \DB::commit();

            Log::info(json_encode($result));

            if ($result['status'] == 1) {
                return response()->json([
                    'result' => true,
                    'message' => 'OK',
                    'balance' => intval($result['balance'])
                ]);
            }

            return response()->json([
                'result' => false,
                'message' => $result["msg"],
            ]);
        }

        public static function getAgentBalance()
        {
            $token = config('app.rg_key');
            try {
                $url = config('app.rg_api') . '/my-info';
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $token
                ])->get($url);
                if ($response->getStatusCode() != 200) {
                    Log::error('RGAgentBalance : agentbalance request failed. ' . $response->body());
                    return -1;
                }
                $data = $response->json();
                if (($data == null) || isset($data['message'])) {
                    Log::error('RGAgentBalance : agentbalance result failed. ' . ($data == null ? 'null' : $data['message']));
                    return -1;
                }
                return $data['balance'];
            } catch (\Exception $ex) {
                Log::error('RGAgentBalance : agentbalance Exception. Exception=' . $ex->getMessage());
                Log::error('RGAgentBalance : agentbalance Exception. PARAMS=');
                return -1;
            }

        }

        public static function transactionDetail()
        {
            $category = \VanguardLTE\Category::where([
                'code' => self::EVO_CODE,
                'href' => 'rg-evol',
                'shop_id' => 0,
                'site_id' => 0
            ])->first();

            $original_id = $category->original_id ?? 64;

            $result = \VanguardLTE\StatGame::where([
                'category_id' => $original_id,
                'bet_type' => 'win',
            ])
                ->whereNull('detail')->orderBy('date_time', 'asc')
                ->first();

            Log::info('In RG Controller Transaction Detail');
            Log::info(json_encode($result));

            if (!$result) {
                Log::info('No result found for transaction detail.');
                return;
            }

            try {
                $url = config('app.rg_api') . '/transactions';
                $token = config('app.rg_key');

                $dateTime = new \DateTime();
                $dateTime->setTime(23, 59, 59);
                $endOfDay = $dateTime->format('Y-m-d H:i:s');

                $param = [
                    'start' => $result->date_time,
                    'end' => $endOfDay,
                    'page' => 0,
                    'perPage' => 1000,
                ];

                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $token
                ])->get($url, $param);

                if (!$response->successful()) {
                    Log::error('RG Transacton Detail Exception. Status code=' . $response->status());
                    return;
                }

                $data = $response->json();

                $transactions = $data['data'];

                $chunkSize = 50; // 한 번에 처리할 $transactions의 크기
                $transactionsChunks = array_chunk($transactions, $chunkSize);

                foreach ($transactionsChunks as $chunk) {
                    $cases = [];
                    $transactionIds = [];

                    foreach ($chunk as $transaction) {
                        $transactionKey = $transaction['id'];

                        // $detail에 이스케이프 적용
                        $detail = addslashes(json_encode($transaction['external']));

                        $cases[] = "WHEN transactionid = '{$transactionKey}' THEN '{$detail}'";
                        $transactionIds[] = "'{$transactionKey}'";
                    }

                    if (!empty($cases)) {
                        $casesString = implode(' ', $cases);
                        $transactionIdsString = implode(',', $transactionIds);

                        $query = "UPDATE w_stat_game SET detail = CASE {$casesString} END WHERE transactionid IN ({$transactionIdsString}) AND bet_type = 'win'";
                        \DB::statement($query);
                    }
                }


            } catch (\Exception $ex) {
                Log::error('RG Transacton Detail Exception. Exception=' . $ex->getMessage());
            }
        }
    }

}

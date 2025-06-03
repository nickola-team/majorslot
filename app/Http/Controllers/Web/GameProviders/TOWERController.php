<?php
namespace VanguardLTE\Http\Controllers\Web\GameProviders {

    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Redis;
    use Illuminate\Http\Request;
    use VanguardLTE\Http\Controllers\Web\Frontend\CallbackController;

    class TOWERController extends \VanguardLTE\Http\Controllers\Controller
    {

        const TOWER_PROVIDER = 'tower';

        const TOWER_BLUEPREFIX = 'blue';

        const TOWER_GAME_IDENTITY = [
            //==== SLOT ====
            'tower-pp' => ['thirdname' => 'pp', 'type' => 'slot', 'symbol' => 'pp', 'skin' => 'SLOT'],                                  // 프라그마틱
            'tower-pgsoft' => ['thirdname' => 'h_PGSoft', 'type' => 'slot', 'symbol' => 'pgsoft', 'skin' => 'SLOT'],                    // 피지소프트
            'tower-skywind' => ['thirdname' => 'h_SkywindSlot', 'type' => 'slot', 'symbol' => 'skywind', 'skin' => 'SLOT'],             // 스카이윈드
            'tower-mg' => ['thirdname' => 'h_MicroGamingPlusSlo', 'type' => 'slot', 'symbol' => 'mg', 'skin' => 'SLOT'],                // 마이크로게이밍
            'tower-ag' => ['thirdname' => 'h_AsiaGamingSlot', 'type' => 'slot', 'symbol' => 'ag', 'skin' => 'SLOT'],                    // 아시안게이밍
            'tower-bp' => ['thirdname' => 'h_BlueprintGaming', 'type' => 'slot', 'symbol' => 'bp', 'skin' => 'SLOT'],                   // 블루프린트
            'tower-relax' => ['thirdname' => 'h_RelaxGaming', 'type' => 'slot', 'symbol' => 'relax', 'skin' => 'SLOT'],                 // 릴렉스
            'tower-netent' => ['thirdname' => 'v_netent', 'type' => 'slot', 'symbol' => 'netent', 'skin' => 'SLOT'],                    // 넷엔트
            'tower-ps' => ['thirdname' => 'h_PlayStar', 'type' => 'slot', 'symbol' => 'ps', 'skin' => 'SLOT'],                          // 플레이스타
            'tower-cq9' => ['thirdname' => 'v_cq9', 'type' => 'slot', 'symbol' => 'cq9, v_cq9', 'skin' => 'SLOT'],                      // 시큐9
            'tower-bng' => ['thirdname' => 'v_booongo', 'type' => 'slot', 'symbol' => 'bng, v_booongo', 'skin' => 'SLOT'],              // 부운고
            'tower-hbn' => ['thirdname' => 'v_habanero', 'type' => 'slot', 'symbol' => 'hbn, v_habanero', 'skin' => 'SLOT'],            // 하바네로
            'tower-playngo' => ['thirdname' => 'v_PLAYNGO', 'type' => 'slot', 'symbol' => 'playngo, v_PLAYNGO', 'skin' => 'SLOT'],      // 플레이앤고
            'tower-gmw' => ['thirdname' => 'v_gmw', 'type' => 'slot', 'symbol' => 'gmw', 'skin' => 'SLOT'],                             // GMW
            'tower-btg' => ['thirdname' => 'v_btg', 'type' => 'slot', 'symbol' => 'btg', 'skin' => 'SLOT'],                             // 빅타임게이밍
            'tower-netgaming' => ['thirdname' => 'h_netgame', 'type' => 'slot', 'symbol' => 'netgaming', 'skin' => 'SLOT'],             // 넷게이밍
            'tower-aux' => ['thirdname' => 'h_AvatarUX', 'type' => 'slot', 'symbol' => 'aux', 'skin' => 'SLOT'],                        // 아바타UX
            'tower-fant' => ['thirdname' => 'h_Fantasma', 'type' => 'slot', 'symbol' => 'fant', 'skin' => 'SLOT'],                      // 판타즈마
            'tower-nlc' => ['thirdname' => 'v_nlc', 'type' => 'slot', 'symbol' => 'nlc', 'skin' => 'SLOT'],                             // 노리밋시티
            'tower-hs' => ['thirdname' => 'h_Hacksaw', 'type' => 'slot', 'symbol' => 'hs', 'skin' => 'SLOT'],                           // 핵소우
            'tower-jili' => ['thirdname' => 'h_jili', 'type' => 'slot', 'symbol' => 'jili', 'skin' => 'SLOT'],                          // 질리
            'tower-bgaming' => ['thirdname' => 'h_bgaming', 'type' => 'slot', 'symbol' => 'bgaming', 'skin' => 'SLOT'],                 // 비게이밍
            'tower-booming' => ['thirdname' => 'h_booming', 'type' => 'slot', 'symbol' => 'booming', 'skin' => 'SLOT'],                 // 부밍게임즈
            'tower-expanse' => ['thirdname' => 'h_expanse', 'type' => 'slot', 'symbol' => 'expanse', 'skin' => 'SLOT'],                 // 익스팬스
            'tower-dragon' => ['thirdname' => 'h_dragoonsoft', 'type' => 'slot', 'symbol' => 'dragon', 'skin' => 'SLOT'],               // 드래곤소프트
            'tower-wazdan' => ['thirdname' => 'v_wazdan', 'type' => 'slot', 'symbol' => 'wazdan, v_wazdan', 'skin' => 'SLOT'],          // 와즈단
            'tower-octo' => ['thirdname' => 'h_Octoplay', 'type' => 'slot', 'symbol' => 'octo', 'skin' => 'SLOT'],                      // 옥토플레이
            'tower-ygg' => ['thirdname' => 'h_Yggdrasil', 'type' => 'slot', 'symbol' => 'ygg', 'skin' => 'SLOT'],                       // 이그드라실
            'tower-novo' => ['thirdname' => 'h_Novomatic', 'type' => 'slot', 'symbol' => 'novo', 'skin' => 'SLOT'],                     // 노보메틱

        ];

        public static function getgamelist($href)
        {
            $games = Redis::get($href . 'list');
            $games = $games ? json_decode($games, true) : [];

            if (count($games) > 0) {
                return $games;
            }

            $category = self::TOWER_GAME_IDENTITY[$href];

            $url = config('app.tower_api') . '/v2/games';
            $key = config('app.tower_key');
            $site_code = config('app.tower_site');

            $vendor_key = $category['thirdname'];
            $type = $category['type'];
            $skin = $category['skin'];
            $query = [
                'site_code' => $site_code,
                'vendorCode' => $vendor_key
            ];

            try {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => $key,
                ])->get($url, $query)->throw();

                $data = $response->json();

                foreach ($data as $game) {
                    array_push($games, [
                        'provider' => self::TOWER_PROVIDER,
                        'vendorKey' => $vendor_key,
                        'href' => $href,
                        'gameid' => $game['gameCode'],
                        'gamecode' => $vendor_key . '_' . $game['gameCode'],
                        'symbol' => $game['gameCode'],
                        'name' => $game['name'],
                        'title' => $game['name'],
                        'type' => $type,
                        'skin' => $skin,
                        'icon' => $game['img'],
                        'view' => 1
                    ]);
                }
            } catch (\Exception $ex) {
                Log::error('Tower Game List Request :  Excpetion. exception= ' . $ex->getMessage());
                Log::error('Tower Game List Request :  Excpetion. PARAMS= ' . json_encode($query));
            }

            Redis::set($href . 'list', json_encode($games));

            return $games;
        }

        public static function getGameObj($uuid)
        {
            foreach (self::TOWER_GAME_IDENTITY as $ref => $value) {
                $games = self::getgamelist($ref);

                if (empty($games)) {
                    continue;
                }

                foreach ($games as $game) {
                    if ($game['gamecode'] == $uuid) {
                        return $game;
                    }
                }
            }

            return null;
        }

        public static function getgamelink($gamecode)
        {
            if (isset(self::TOWER_GAME_IDENTITY[$gamecode])) {
                $gamelist = self::getgamelist($gamecode);

                if (count($gamelist) > 0) {
                    foreach ($gamelist as $g) {
                        if ($g['view'] == 1) {
                            $gamecode = $g['gamecode'];
                            break;
                        }
                    }

                }
            }

            $url = self::makegamelink($gamecode);

            if ($url) {
                return ['error' => false, 'data' => ['url' => $url]];
            }

            return ['error' => true, 'msg' => '게임실행 오류입니다'];
        }

        public static function makegamelink($gamecode, $prefix = self::TOWER_BLUEPREFIX, $user = null)
        {
            $game = self::getGameObj($gamecode);
            $user = $user ?? auth()->user();

            if (!$game || !$user) {
                return null;
            }

            $user_code = $prefix . sprintf("%04d", $user->id);

            $url = config('app.tower_api') . '/v2/play';
            $key = config('app.tower_key');
            $site_code = config('app.tower_site');

            $query = [
                'site_code' => $site_code,
                'user_id' => $user_code,
                'nickname' => $user_code,
                'user_ip' => '127.0.0.1',
                'vendorCode' => $game['vendorKey'],
                'gameCode' => $game['symbol'],
                'session_token' => $user_code,
            ];

            try {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => $key,
                ])->get($url, $query)->throw();

                $data = $response->json();

                if ($data['result'] != 'Error') {
                    return $data['result'];
                } else {
                    Log::error('Tower Game Launch Response Error, msg=  ' . $data['msg']);

                    return null;
                }
            } catch (\Exception $ex) {
                Log::error('Tower Game Launch Request :  Excpetion. exception= ' . $ex->getMessage());
                Log::error('Tower Game Launch Request :  Excpetion. PARAMS= ' . json_encode($query));
            }
        }

        public function balance(Request $request)
        {
            $data = json_decode($request->getContent(), true);

            Log::info('---- Tower Balance CallBack  : Request PARAMS= ' . json_encode($data));

            $user_id = $data['user_id'];
            $userid = intval(preg_replace('/' . self::TOWER_BLUEPREFIX . '(\d+)/', '$1', $user_id));
            $user = \VanguardLTE\User::where(['id' => $userid, 'role_id' => 1])->first();

            if (!$user) {
                Log::error('Tower Balance Callback : Not found user. PARAMS= ' . json_encode($data));
                return response('Not found user');
            }
            if ($user->api_token == 'playerterminate') {
                Log::error('Tower Balance Callback : terminated by admin. PARAMS= ' . json_encode($data));
                return response('Not found user');
            }

            $parent = $user->findAgent();

            if ($parent->callback) {
                $username = explode("#P#", $user->username)[1];
                $response = CallbackController::userBalance($parent->callback, $username);

                if ($response['status'] == 1) {
                    $user->balance = $response['balance'];
                    $user->save();

                    return response($user->balance);
                }

                return response($response['msg']);
            }

            return response($user->balance);
        }

        public function bet(Request $request)
        {
            $data = json_decode($request->getContent(), true);

            Log::info('---- Tower Bet CallBack  : Request PARAMS= ' . json_encode($data));

            $user_id = $data['user_id'];
            $transaction_id = $data['transaction_id'];
            $vendor_code = $data['vendorCode'];
            $game_code = $data['gameCode'];
            $amount = $data['amount'];

            $type = 'bet';
            $transaction_type = 'BET';
            $bet = $amount;
            $win = 0;
            $user_id = intval(preg_replace('/' . self::TOWER_BLUEPREFIX . '(\d+)/', '$1', $user_id));
            $game = self::getGameObj($game_code);
            $round_id = $vendor_code . '#' . $game_code . '#' . $transaction_id . '#' . $transaction_type;

            $checkGameStat = \VanguardLTE\StatGame::where([
                'user_id' => $user_id,
                'bet_type' => $type,
                'roundid' => $round_id,
            ])->first();

            if (!$checkGameStat) {
                return response()->json([
                    'status' => 'OK',
                    'balance' => $checkGameStat->balance,
                ]);
            }


            \DB::beginTransaction();
            $user = \VanguardLTE\User::lockforUpdate()->where(['id' => $user_id, 'role_id' => 1])->first();

            if (!$user) {
                Log::error('Tower Bet Callback : Not found User. PARAMS= ' . json_encode($data));
                \DB::commit();
                return response()->json([
                    'status' => 'Error',
                    'msg' => 'User Not Found',
                ]);
            }

            $user->balance = $user->balance - intval($bet);
            $user->save();

            $gamename = $game['gamecode'] . '_tower';
            $time = date('Y-m-d H:i:s', time());
            $shop = \VanguardLTE\ShopUser::where('user_id', $user_id)->first();
            $category = \VanguardLTE\Category::where('href', $game['href'])->first();

            $result = \VanguardLTE\StatGame::create([
                'user_id' => $user_id,
                'balance' => $user->balance,
                'bet' => $bet,
                'win' => $win,
                'bet_type' => $type,
                'game' => $gamename,
                'type' => $game['type'],
                'percent' => 0,
                'percent_jps' => 0,
                'percent_jpg' => 0,
                'profit' => 0,
                'denomination' => 0,
                'date_time' => $time,
                'shop_id' => $shop ? $shop->shop_id : -1,
                'category_id' => $category ? $category->original_id : 0,
                'game_id' => $game['gameid'],
                'roundid' => $round_id,
                'transactionid' => $transaction_id,
                'tablekey' => $game['gamecode'],
            ]);
            \DB::commit();

            if ($result['status'] == 1) {
                return response()->json([
                    'status' => 'OK',
                    'balance' => intval($result['balance']),
                ]);
            }

            return response()->json([
                'status' => 'Error',
                'msg' => $result['msg'],
            ]);
        }

        public function result(Request $request)
        {
            $data = json_decode($request->getContent(), true);

            Log::info('---- Tower Result CallBack  : Request PARAMS= ' . json_encode($data));

            $user_id = $data['user_id'];
            $transaction_type = $data['transaction_type'];
            $transaction_id = $data['transaction_id'];
            $round_id = $data['game_id'];
            $vendor_code = $data['vendorCode'];
            $game_code = $data['gameCode'];
            $amount = $data['amount'];
            $over_win = $data['overWin'];
            $reference = $data['reference'];
            $reference_for_cancel = $data['reference_for_cancel'];
            $detail = $data['betting_data'];

            $type = 'win';
            $bet = 0;
            $win = $amount - $over_win;
            $user_id = intval(preg_replace('/' . self::TOWER_BLUEPREFIX . '(\d+)/', '$1', $user_id));
            $game = self::getGameObj($game_code);
            $round_id = $vendor_code . '#' . $game_code . '#' . $transaction_id . '#' . $transaction_type;

            $checkGameStat = \VanguardLTE\StatGame::where([
                'user_id' => $user_id,
                'bet_type' => $type,
                'roundid' => $round_id,
            ])->first();

            if (!$checkGameStat) {
                return response()->json([
                    'status' => 'OK',
                    'balance' => $checkGameStat->balance,
                ]);
            }

            \DB::beginTransaction();
            $user = \VanguardLTE\User::lockforUpdate()->where(['id' => $user_id, 'role_id' => 1])->first();

            if (!$user) {
                Log::error('Tower Result Callback : Not found User. PARAMS= ' . json_encode($data));
                \DB::commit();
                return response()->json([
                    'status' => 'Error',
                    'msg' => 'User Not Found',
                ]);
            }

            $user->balance = $user->balance + intval($win);
            $user->save();

            $gamename = $game['gamecode'] . '_tower';
            $time = date('Y-m-d H:i:s', time());
            $shop = \VanguardLTE\ShopUser::where('user_id', $user_id)->first();
            $category = \VanguardLTE\Category::where('href', $game['href'])->first();

            $result = \VanguardLTE\StatGame::create([
                'user_id' => $user_id,
                'balance' => $user->balance,
                'bet' => $bet,
                'win' => $win,
                'bet_type' => $type,
                'game' => $gamename,
                'type' => $game['type'],
                'percent' => 0,
                'percent_jps' => 0,
                'percent_jpg' => 0,
                'profit' => 0,
                'denomination' => 0,
                'date_time' => $time,
                'shop_id' => $shop ? $shop->shop_id : -1,
                'category_id' => $category ? $category->original_id : 0,
                'game_id' => $game['gameid'],
                'roundid' => $round_id,
                'transactionid' => $transaction_id,
                'tablekey' => $game['gamecode'],
                'detail' => $detail,
            ]);
            \DB::commit();

            if ($result['status'] == 1) {
                return response()->json([
                    'status' => 'OK',
                    'balance' => intval($result['balance']),
                ]);
            }

            return response()->json([
                'status' => 'Error',
                'msg' => $result['msg'],
            ]);
        }
    }
}

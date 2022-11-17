<?php 
namespace VanguardLTE\Http\Controllers\Web\GameProviders
{
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Log;
    class GACController extends \VanguardLTE\Http\Controllers\Controller
    {
        /*
        * UTILITY FUNCTION
        */

        const GACGVO = 31;
        const GACGAC = 36;

        public function microtime_string()
        {
            $microstr = sprintf('%.4f', microtime(TRUE));
            $microstr = str_replace('.', '', $microstr);
            return $microstr;
        }

        public static function mergeGAC_EVO($masterid)
        {
            $query = 'SELECT * FROM w_provider_info WHERE provider="gacinfo" and user_id=' . $masterid;
            $gac_info = \DB::select($query);
            $merge = 0;
            foreach ($gac_info as $info)
            {
                $merge = $info->config;
            }
            return $merge;
        }

        public static function getGameObj($tableId)
        {
            $gamelist_gac = GACController::getgamelist('gac');
            $gamelist_evo = GACController::getgamelist('gvo');

            $gamelist = array_merge_recursive($gamelist_gac, $gamelist_evo);
            $tableName = preg_replace('/\s+/', '', $tableId);
            if ($gamelist)
            {
                foreach($gamelist as $game)
                {
                    if ($game['gamecode'] == $tableName)
                    {
                        return $game;
                        break;
                    }
                }
            }
            return null;
        }

        /*
        * FROM GAC, BACK API
        */

        public function checkplayer($userid, \Illuminate\Http\Request $request)
        {
            $user = \VanguardLTE\User::where(['id'=> $userid, 'role_id' => 1])->first();
            if (!$user)
            {
                return response()->json([
                    'result' => false,
                    'message' => 'Not found user',
                    'data' => []
                ]);
            }
            $betlimit = null;
            $userlimit = null;
            $default_config = \VanguardLTE\ProviderInfo::where('user_id', 0)->where('provider', 'gac')->first();
            if (!$default_config)
            {
                return response()->json([
                    'result' => false,
                    'message' => 'Not found default config',
                    'data' => []
                ]);
            }
            $betlimitD = json_decode($default_config->config, true);
            $betlimit = $betlimitD;
            $parent = $user;
            while ($parent)
            {
                $user_config = \VanguardLTE\ProviderInfo::where('user_id', $parent->id)->where('provider', 'gac')->first();
                if ($user_config)
                {
                    $userlimit = json_decode($user_config->config, true);
                    break;
                }
                $parent = $parent->referral;
            }
            if ($userlimit)
            {
                foreach ($betlimitD as $idx => $limit)
                {
                    foreach ($limit['BetLimit'] as $k => $v)
                    {
                        //bacmin, bacmax, scmin, scmax, dtmin, dtmax, rlmin, rlmax
                        if (isset($userlimit[$k])) 
                        {
                            $betlimit[$idx]['BetLimit'][$k] = $userlimit[$k];
                        }
                        else if ($k=='Baccarat_Min') 
                        {
                            if (isset($userlimit['bacmin']))
                            {
                                $betlimit[$idx]['BetLimit'][$k] = $userlimit['bacmin'];
                            }
                        }
                        else if (isset($userlimit['bacmax']) && str_contains($k,'Baccarat_'))
                        {
                            if (str_contains($k,'Pair'))
                            {
                                $betlimit[$idx]['BetLimit'][$k] = $userlimit['bacmax'] / 10;
                            }
                            else
                            {
                                $betlimit[$idx]['BetLimit'][$k] = $userlimit['bacmax'];
                            }
                        }

                        //Dragon&Tiger
                        else if ($k=='DragonTiger_Min')
                        {
                            if (isset($userlimit['dtmin']))
                            {
                                $betlimit[$idx]['BetLimit'][$k] = $userlimit['dtmin'];
                            }
                        }
                        else if (isset($userlimit['dtmax']) && str_contains($k,'DragonTiger_'))
                        {
                            $betlimit[$idx]['BetLimit'][$k] = $userlimit['dtmax'];
                        }

                        //SicBo
                        else if ($k=='SicBo_Min')
                        {
                            if (isset($userlimit['scmin']))
                            {
                                $betlimit[$idx]['BetLimit'][$k] = $userlimit['scmin'];
                            }
                        }
                        else if (isset($userlimit['scmax']) && str_contains($k,'SicBo_'))
                        {
                            $betlimit[$idx]['BetLimit'][$k] = $userlimit['scmax'];
                        }

                        //Roulette
                        else if ($k=='Roulette_Min')
                        {
                            if (isset($userlimit['rlmin']))
                            {
                                $betlimit[$idx]['BetLimit'][$k] = $userlimit['rlmin'];
                            }
                        }
                        else if (isset($userlimit['rlmax']) && str_contains($k,'Roulette_'))
                        {
                            $betlimit[$idx]['BetLimit'][$k] = $userlimit['rlmax'];
                        }
                        

                    }
                }
                
            }

            return response()->json([
                'result' => true,
                'message' => 'OK',
                'data' => json_encode([[
                    'GameType' => self::GACGVO,
                    "Limits" => $betlimit
                    ]])
            ]);
        }
        public function balance($userid, \Illuminate\Http\Request $request)
        {
            $user = \VanguardLTE\User::where(['id'=> $userid, 'role_id' => 1])->first();
            if (!$user)
            {
                return response()->json([
                    'result' => false,
                    'message' => 'Not found user',
                    'data' => [
                        'balance' => 0,
                    ]
                ]);
            }
            return response()->json([
                'result' => true,
                'message' => 'OK',
                'data' => [
                    'balance' => intval($user->balance)
                ]
            ]);
        }
        public function placebet(\Illuminate\Http\Request $request)
        {
            $data = json_decode($request->getContent(), true);
            $userId = isset($data['userId'])?$data['userId']:0;
            $tableName = isset($data['tableName'])?$data['tableName']:'';
            $betAmount = isset($data['betAmount'])?$data['betAmount']:0;
            $betInfo = isset($data['betInfo'])?$data['betInfo']:0;
            $gameId = isset($data['gameId'])?$data['gameId']:0;
            if (!$userId || !$tableName || !$betAmount || !$gameId)
            {
                return response()->json([
                    'result' => false,
                    'message' => 'No Parameter',
                    'data' => [
                        'balance' => 0,
                    ]
                ]);
            }
            // Dragon maintenance
            // 'puu47n7mic3rfd7y','DragonTiger00001',
            //'SuperSicBo000001'
            // if (in_array($tableName , ['peekbaccarat0001']))
            // {
            //     return response()->json([
            //         'result' => false,
            //         'message' => 'This table is in maintenance',
            //         'data' => [
            //             'balance' => 0,
            //         ]
            //     ]);
            // }
            $user = \VanguardLTE\User::lockforUpdate()->where(['id'=> $userId, 'role_id' => 1])->first();
            if (!$user || $user->playing_game != null || $user->remember_token != $user->api_token)
            {
                return response()->json([
                    'result' => false,
                    'message' => 'Not found user',
                    'data' => [
                        'balance' => 0,
                    ]
                ]);
            }
            $amount = abs($betAmount);
            
            if ($betInfo == 2) //additional betting
            {
                $record = \VanguardLTE\GACTransaction::where([
                    'user_id' => $userId,
                    'game_id' => $gameId,
                    'betInfo' => 1,
                    'type' => 1
                    ])->first();

                if ($record)
                {
                    $main_data = json_decode($record->data,true);
                    $main_amount = abs($main_data['betAmount']);
                    if ($amount < $main_amount)
                    {
                        return response()->json([
                            'result' => false,
                            'message' => 'Main amount must large than additional bet amount. main=' . $main_amount . ', addamount=' . $amount,
                            'data' => [
                                'balance' => $user->balance,
                            ]
                        ]);
                    }
                    $amount = $amount - $main_amount;
                }
            }

            if ($user->balance < $amount)
            {
                return response()->json([
                    'result' => false,
                    'message' => 'balance is not enough',
                    'data' => [
                        'balance' => $user->balance,
                    ]
                ]);
            }

            $user->balance = $user->balance - intval($amount);
            $user->save();
            $user = $user->fresh();

            \VanguardLTE\GACTransaction::create([
                'user_id' => $userId, 
                'game_id' => $gameId,
                'betInfo' => $betInfo,
                'type' => 1,
                'data' => json_encode($data),
                'response' => $user->balance,
                'status' => 0
            ]);
            return response()->json([
                'result' => true,
                'message' => 'OK',
                'data' => [
                    'balance' => intval($user->balance)
                ]
            ]);
        }

        public static function cancelResult($placeid)
        {

            $record = \VanguardLTE\GACTransaction::where('id', $placeid)->where(['gactransaction.type'=>1,'gactransaction.status'=>0])->first();
            if ($record == null)
            {
                return 0;
            }
            $json_data = json_decode($record->data, true);
            $betAmount = $json_data['betAmount'];
            $user = \VanguardLTE\User::lockforUpdate()->where(['id'=> $json_data['userId'], 'role_id' => 1])->first();
            if (!$user)
            {
                return 0;
            }
            $user->balance = $user->balance + abs($betAmount);
            $user->save();

            $gameObj = GACController::getGameObj($json_data['tableName']);
            if (!$gameObj)
            {
                $gameObj = GACController::getGameObj('unknowntable');
            }

            $category = \VanguardLTE\Category::where(['provider' => 'gac', 'shop_id' => 0, 'href' => $gameObj['href']])->first();


            \VanguardLTE\StatGame::create([
                'user_id' => $user->id, 
                'balance' => intval($user->balance), 
                'bet' => $betAmount, 
                'win' => $betAmount, 
                'game' =>  $gameObj['name'] . '_' . $gameObj['href'], 
                'type' => 'table',
                'percent' => 0, 
                'percent_jps' => 0, 
                'percent_jpg' => 0, 
                'profit' => 0, 
                'denomination' => 0, 
                'shop_id' => $user->shop_id,
                'category_id' => isset($category)?$category->id:0,
                'game_id' => $gameObj['gamecode'],
                'roundid' =>  '000000-' . $json_data['tableName'],
                'date_time' => $record->date_time
            ]);

            $record->update(['status' => 2]);


        }

        public function betresult(\Illuminate\Http\Request $request)
        {
            $data = json_decode($request->getContent(), true);
            $userId = isset($data['userId'])?$data['userId']:0;
            $tableName = isset($data['tableName'])?$data['tableName']:'';
            $betAmount = isset($data['betAmount'])?$data['betAmount']:0;
            $winAmount = isset($data['winAmount'])?$data['winAmount']:0;
            $betId = isset($data['betId'])?$data['betId']:0;
            $gameId = isset($data['gameId'])?$data['gameId']:0;
            if (!$userId || !$tableName || !$betAmount || !isset($data['winAmount']) || !$gameId)
            {
                return response()->json([
                    'result' => false,
                    'message' => 'No Parameter',
                    'data' => [
                        
                        'balance' => 0,
                    ]
                ]);
            }
            if ($betId == 0)
            {
                return response()->json([
                    'result' => false,
                    'message' => 'betid is 0',
                    'data' => [
                        'balance' => 0,
                    ]
                ]);
            }
            
            $user = \VanguardLTE\User::lockforUpdate()->where(['id'=> $userId, 'role_id' => 1])->first();
            if (!$user)
            {
                return response()->json([
                    'result' => false,
                    'message' => 'Not found user',
                    'data' => [
                        'balance' => 0,
                    ]
                ]);
            }

            $betrecords = \VanguardLTE\GACTransaction::where([
                'user_id' => $userId,
                'game_id' => $gameId,
                'type' => 1,
                'status' => 0
                ])->get();
            if (count($betrecords) == 0)
            {
                return response()->json([
                    'result' => false,
                    'message' => 'No placebet exist',
                    'data' => [
                        'balance' => 0,
                    ]
                ]);
            }

            $winrecord = \VanguardLTE\GACTransaction::where([
                'user_id' => $userId,
                'game_id' => $gameId,
                'type' => 2,
                'betInfo' => $betId
                ])->first();
            if ($winrecord)
            {
                return response()->json([
                    'result' => false,
                    'message' => 'duplicated betid - ' . $betId,
                    'data' => [
                        'balance' => 0,
                    ]
                ]);
            }

            $user->balance = $user->balance + intval(abs($winAmount));
            $user->save();
            
            $gameObj = GACController::getGameObj($tableName);
            if (!$gameObj)
            {
                $gameObj = GACController::getGameObj('unknowntable');
            }

            $category = \VanguardLTE\Category::where(['provider' => 'gac', 'shop_id' => 0, 'href' => $gameObj['href']])->first();

            \VanguardLTE\StatGame::create([
                'user_id' => $user->id, 
                'balance' => intval($user->balance), 
                'bet' => $betAmount, 
                'win' => $winAmount, 
                'game' =>  $gameObj['name'] . '_' . $gameObj['href'], 
                'type' => 'table',
                'percent' => 0, 
                'percent_jps' => 0, 
                'percent_jpg' => 0, 
                'profit' => 0, 
                'denomination' => 0, 
                'shop_id' => $user->shop_id,
                'category_id' => isset($category)?$category->id:0,
                'game_id' => $gameObj['gamecode'],
                'roundid' => $betId . '-' . $tableName,
            ]);

            \VanguardLTE\GACTransaction::create([
                'user_id' => $userId, 
                'game_id' => $gameId,
                'betInfo' => $betId,
                'type' => 2,
                'data' => json_encode($data),
                'response' => $user->balance,
                'status' => 1
            ]);

            \VanguardLTE\GACTransaction::whereIn('id', $betrecords->pluck('id')->toArray())->update(['status' => 1]);


            return response()->json([
                'result' => true,
                'message' => 'OK',
                'data' => [
                    'balance' => intval($user->balance)
                ]
            ]);
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
            $gameList = [];
            $query = 'SELECT * FROM w_provider_games WHERE provider="gac' . $href .'"';
            $gac_games = \DB::select($query);
            foreach ($gac_games as $game)
            {
                $icon_name = str_replace(' ', '_', $game->gameid);
                $icon_name = strtolower(preg_replace('/\s+/', '', $icon_name));
                array_push($gameList, [
                    'provider' => 'gac',
                    'gameid' => $game->gameid,
                    'gamecode' => $game->gamecode,
                    'enname' => $game->name,
                    'name' => preg_replace('/\s+/', '', $game->name),
                    'title' => $game->title,
                    'type' => $game->type,
                    'href' => $href,
                    'view' => $game->view,
                    'icon' => '/frontend/Default/ico/gac/'. $href . '/' . $icon_name . '.jpg',
                    ]);
            }
            \Illuminate\Support\Facades\Redis::set($href.'list', json_encode($gameList));
            return $gameList;
            
        }

        public static function makegamelink($gamecode)
        {
            $user = auth()->user();
            if ($user == null)
            {
                return null;
            }
            $is_test = str_contains($user->username, 'testfor');
            if ($is_test) //it must be hidden from all providers
            {
                return null;
            }
            $gameObj = GACController::getGameObj($gamecode);
            if (!$gameObj)
            {
                return null;
            }

            $recommend = config('app.gac_key');
            $data = [
                'userId' => strval($user->id),
                'userName' => $user->username,
                'recommend' => $recommend,
                'gameType' => ($gameObj['href'] == 'gac')?(self::GACGAC):(self::GACGVO),
            ];
            if (!str_contains(strtolower($gamecode),'lobby'))
            {
                $data['tableId'] = $gamecode;
            }
            $url = null;
            try {
                $response = Http::timeout(20)->post(config('app.gac_api') . '/wallet/api/getLobbyUrl', $data);
                if (!$response->ok())
                {
                    Log::error('GAC : getLobbyUrl response failed. ' . $response->body());
                    return null;
                }
                $data = $response->json();
                if (isset($data['lobbyUrl'])){
                    $url = $data['lobbyUrl'];
                }
            }
            catch (\Exception $ex)
            {
                Log::error('GAC : getLobbyUrl request failed. ' . $ex->getMessage());
                return null;
            }
            return $url;
        }

        public static function getgamelink($gamecode)
        {
            $gameObj = GACController::getGameObj($gamecode);
            if (!$gameObj)
            {
                return ['error' => true, 'msg' => '게임이 없습니다'];
            }
            $detect = new \Detection\MobileDetect();
            if ($detect->isiOS() || $detect->isiPadOS())
            {
                $url = GACController::makegamelink($gamecode);
            }
            else
            {
                $url = '/gac/golobby?code=' . $gamecode;
            }
            if ($url)
            {
                return ['error' => false, 'data' => ['url' => $url]];
            }
            return ['error' => true, 'msg' => '로그인하세요'];
        }

        public function embedGACgame(\Illuminate\Http\Request $request)
        {
            $gamecode = $request->code;
            $url = GACController::makegamelink($gamecode);
            if ($url)
            {
                return view('frontend.Default.games.apigame',compact('url'));
            }
            else
            {
                abort(404);
            }


        }

        public static function getgamedetail(\VanguardLTE\StatGame $stat)
        {
            $betId = explode('-',$stat->roundid)[0];
            if ($betId == '000000')
            {
                return null;
            }
            $recommend = config('app.gac_key');
            $param = [
                'betId' => intval($betId) - 1,
                'recommend' => $recommend,
                'pageSize' => 100,
                'pageNumber' => 1
            ];

            $data = null;
            try {
                $response = Http::timeout(10)->post(config('app.gac_api') . '/wallet/api/getBetHistoryByRecommend', $param);
                if (!$response->ok())
                {
                    Log::error('GAC : getgamedetail response failed. ' . $response->body());
                    return null;
                }
                $data = $response->json();
                if ($data==null || $data['returnCode']!=0)
                {
                    return null;
                }
            }
            catch (\Exception $ex)
            {
                Log::error('GAC : getLobbyUrl request failed. ' . $ex->getMessage());
                return null;
            }
            $gameId = '';
            $userId = '';
            foreach ($data['betHistories'] as $bet)
            {
                if ($bet['id'] == $betId)
                {
                    $gameId = $bet['gameId'];
                    $userId = $bet['userId'];
                    break;
                }
            }
            if ($gameId == '')
            {
                return null;
            }
            $userbets = array_values(array_filter($data['betHistories'], function($k) use ($gameId, $userId){
                return ($k['gameId'] == $gameId) && ($k['userId'] == $userId);
            }));
            
            $gametype = 'Baccarat';
            if ($userbets[0]['gameKind'] == 1)
            {
                $gametype = 'Baccarat';
                $result = [
                    'tableName' => $userbets[0]['tableName'],
                    'type' => $gametype,
                    'gameNumber' => $userbets[0]['gameNumber'],
                    'regdate' => $userbets[0]['regdate'],
                    'bankerScore' => $userbets[0]['bankerScore'],
                    'playerScore' => $userbets[0]['playerScore'],
                    'bankerHand' => $userbets[0]['bankerHand'],
                    'playerHand' => $userbets[0]['playerHand'],
                    'result' => $userbets[0]['result'],
                ];
            }
            else if ($userbets[0]['gameKind'] == 2)
            {
                $gametype = 'DragonTiger';
                $result = [
                    'tableName' => $userbets[0]['tableName'],
                    'type' => $gametype,
                    'gameNumber' => $userbets[0]['gameNumber'],
                    'regdate' => $userbets[0]['regdate'],
                    'dragonScore' => $userbets[0]['dragonScore'],
                    'tigerScore' => $userbets[0]['tigerScore'],
                    'dragonHand' => $userbets[0]['dragonHand'],
                    'tigerHand' => $userbets[0]['tigerHand'],
                    'result' => $userbets[0]['result'],
                ];
            }
            else if ($userbets[0]['gameKind'] == 4)
            {
                $gametype = 'SicBo';
                $result = [
                    'tableName' => $userbets[0]['tableName'],
                    'type' => $gametype,
                    'gameNumber' => $userbets[0]['gameNumber'],
                    'regdate' => $userbets[0]['regdate'],
                    'score' => [$userbets[0]['first'],$userbets[0]['second'],$userbets[0]['third']],
                ];
            }
            else
            {
                return null;
            }
            

            return [
                'type' => $gametype,
                'result' => $result,
                'bets' => $userbets,
                'stat' => $stat
            ];
        }

    }

}

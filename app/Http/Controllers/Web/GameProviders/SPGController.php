<?php 
namespace VanguardLTE\Http\Controllers\Web\GameProviders
{
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Log;
    class SPGController extends \VanguardLTE\Http\Controllers\Controller
    {
        /*
        * UTILITY FUNCTION
        */

        const SPG_PROVIDER = 'spg';

        public function microtime_string()
        {
            $microstr = sprintf('%.4f', microtime(TRUE));
            $microstr = str_replace('.', '', $microstr);
            return $microstr;
        }


        public static function getGameObj($tableId)
        {
            $gamelist_gac = SPGController::getgamelist('gac');
            $gamelist_evo = SPGController::getgamelist('gvo');

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
        * FROM SPG, BACK API
        */

        public function balance(\Illuminate\Http\Request $request)
        {
            
            $username = $request->username;
            $userid = intval(preg_replace('/'. self::SPG_PROVIDER .'(\d+)/', '$1', $username)) ;
            $user = \VanguardLTE\User::where(['id'=> $userid, 'role_id' => 1])->first();
            if (!$user)
            {
                return response()->json([
                    'balance' => 0,
                ]);
            }
            return response()->json([
                'balance' => intval($user->balance),
            ]);
        }

        public function changebalance(\Illuminate\Http\Request $request)
        {
            \DB::beginTransaction();
            $data = json_decode($request->getContent(), true);
            $username = isset($data['username'])?$data['username']:'';
            $amount = isset($data['amount'])?$data['amount']:0;
            $type = isset($data['type'])?$data['type']:'';
            $tid = isset($data['tid'])?$data['tid']:'';
            $iGameId = isset($data['iGameId'])?$data['iGameId']:'';
            $iActionId = isset($data['iActionId'])?$data['iActionId']:'';

            if (!$username || !$amount || !$type || !$tid || !$iGameId || !$iActionId)
            {
                return response()->json([
                    'error' => 'Parameter incorrect',
                    'balance' => 0,
                ]);
            }
            
            $userId = intval(preg_replace('/'. self::SPG_PROVIDER .'(\d+)/', '$1', $username)) ;
            $user = \VanguardLTE\User::lockforUpdate()->where(['id'=> $userId, 'role_id' => 1])->first();
            if (!$user)
            {
                return response()->json([
                    'error' => 'Not found user',
                    'balance' => 0,
                ]);
            }

            // $betrecords = \VanguardLTE\SPGTransaction::where([
            //     'user_id' => $userId,
            //     'game_id' => $gameId,
            //     'type' => 1,
            //     'status' => 0
            //     ])->get();
            // if (count($betrecords) == 0)
            // {
            //     return response()->json([
            //         'result' => false,
            //         'message' => 'No placebet exist',
            //         'data' => [
            //             'balance' => 0,
            //         ]
            //     ]);
            // }

            // $winrecord = \VanguardLTE\SPGTransaction::where([
            //     'user_id' => $userId,
            //     'game_id' => $gameId,
            //     'type' => 2,
            //     'betInfo' => $betId
            //     ])->first();
            // if ($winrecord)
            // {
            //     return response()->json([
            //         'result' => false,
            //         'message' => 'duplicated betid - ' . $betId,
            //         'data' => [
            //             'balance' => 0,
            //         ]
            //     ]);
            // }
            if ($type == 'credit')
            {
                $user->balance = $user->balance + intval(abs($amount));
            }
            else if ($type == 'debit')
            {
                if ($user->balance < intval(abs($amount)))
                {
                    return response()->json([
                        'error' => 'User balance is not enough than ' . abs($amount),
                        'balance' => 0,
                    ]);
                }
                $user->balance = $user->balance - intval(abs($amount));

            }
                
            $user->save();
            
            // $gameObj = SPGController::getGameObj($tableName);
            // if (!$gameObj)
            // {
            //     $gameObj = SPGController::getGameObj('unknowntable');
            // }

            // $category = \VanguardLTE\Category::where(['provider' => 'gac', 'shop_id' => 0, 'href' => $gameObj['href']])->first();

            // \VanguardLTE\StatGame::create([
            //     'user_id' => $user->id, 
            //     'balance' => intval($user->balance), 
            //     'bet' => $betAmount, 
            //     'win' => $winAmount, 
            //     'game' =>  $gameObj['name'] . '_' . $gameObj['href'], 
            //     'type' => 'table',
            //     'percent' => 0, 
            //     'percent_jps' => 0, 
            //     'percent_jpg' => 0, 
            //     'profit' => 0, 
            //     'denomination' => 0, 
            //     'shop_id' => $user->shop_id,
            //     'category_id' => isset($category)?$category->id:0,
            //     'game_id' => $gameObj['gamecode'],
            //     'roundid' => $betId . '-' . $tableName,
            // ]);

            // \VanguardLTE\SPGTransaction::create([
            //     'user_id' => $userId, 
            //     'game_id' => $gameId,
            //     'betInfo' => $betId,
            //     'type' => 2,
            //     'data' => json_encode($data),
            //     'response' => $user->balance,
            //     'status' => 1
            // ]);

            // \VanguardLTE\SPGTransaction::whereIn('id', $betrecords->pluck('id')->toArray())->update(['status' => 1]);
            \DB::commit();

            return response()->json([
                'balance' => intval($user->balance)
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
            $gameObj = SPGController::getGameObj($gamecode);
            if (!$gameObj)
            {
                return null;
            }
            $username = $user->username;
            $parse = explode('#', $username);
            if (count($parse) > 1)
            {
                $username = $parse[count($parse) - 1];
            }

            $username = $username . '#' . strval($user->id);
            $username = mb_substr($username, -10);

            $recommend = config('app.gac_key');
            $data = [
                'userId' => strval($user->id),
                'userName' => $username,
                'recommend' => $recommend,
                'gameType' => ($gameObj['href'] == 'gac')?(self::SPGSPG):(self::SPGGVO),
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
                    Log::error('SPG : getLobbyUrl response failed. ' . $response->body());
                    return null;
                }
                $data = $response->json();
                if (isset($data['lobbyUrl'])){
                    $url = $data['lobbyUrl'];
                }
            }
            catch (\Exception $ex)
            {
                Log::error('SPG : getLobbyUrl request failed. ' . $ex->getMessage());
                return null;
            }
            return $url;
        }

        public static function getgamelink($gamecode)
        {
            $gameObj = SPGController::getGameObj($gamecode);
            if (!$gameObj)
            {
                return ['error' => true, 'msg' => '게임이 없습니다'];
            }
            $detect = new \Detection\MobileDetect();
            if ($detect->isiOS() || $detect->isiPadOS())
            {
                $url = SPGController::makegamelink($gamecode);
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

        public function embedSPGgame(\Illuminate\Http\Request $request)
        {
            $gamecode = $request->code;
            $url = SPGController::makegamelink($gamecode);
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
                    Log::error('SPG : getgamedetail response failed. ' . $response->body());
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
                Log::error('SPG : getLobbyUrl request failed. ' . $ex->getMessage());
                return null;
            }
            $gameId = '';
            $userId = '';
            foreach ($data['betHistories'] as $bet)
            {
                if (isset($bet['id']) && ($bet['id'] == $betId))
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
                return (isset($k['gameId']) && ($k['gameId'] == $gameId)) && (isset($k['userId']) && $k['userId'] == $userId);
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

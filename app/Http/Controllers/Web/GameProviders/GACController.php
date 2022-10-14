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

        public function checktransaction($id)
        {
            $record = \VanguardLTE\GACTransaction::Where('transactionId',$id)->first();
            return $record;
        }

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
            return response()->json([
                'result' => true,
                'message' => 'OK',
                'data' => []
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
            $userId = $data['userId'];
            $tableName = $data['tableName'];
            $betAmount = $data['betAmount'];
            $type = $data['betInfo'];
            if (!$userId || !$tableName || !$betAmount)
            {
                return response()->json([
                    'result' => false,
                    'message' => 'No Parameter',
                    'data' => [
                        'balance' => 0,
                    ]
                ]);
            }
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
            $amount = ($type==1)?(abs($betAmount)) : (-1 * abs($betAmount));
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
            // $category = \VanguardLTE\Category::where(['provider' => 'gac', 'shop_id' => 0, 'href' => 'gac'])->first();

            // \VanguardLTE\StatGame::create([
            //     'user_id' => $user->id, 
            //     'balance' => intval($user->balance), 
            //     'bet' => abs($betAmount), 
            //     'win' => 0, 
            //     'game' =>  $tableName . (($type==1)?'_BET' :  '_CANCEL'), 
            //     'type' => 'table',
            //     'percent' => 0, 
            //     'percent_jps' => 0, 
            //     'percent_jpg' => 0, 
            //     'profit' => 0, 
            //     'denomination' => 0, 
            //     'shop_id' => $user->shop_id,
            //     'category_id' => isset($category)?$category->id:0,
            //     'game_id' => $tableName,
            //     'roundid' => 0,
            // ]);
            return response()->json([
                'result' => true,
                'message' => 'OK',
                'data' => [
                    'balance' => intval($user->balance)
                ]
            ]);
        }
        public function betresult(\Illuminate\Http\Request $request)
        {
            $data = json_decode($request->getContent(), true);
            $userId = $data['userId'];
            $tableName = $data['tableName'];
            $betAmount = $data['betAmount'];
            $winAmount = $data['winAmount'];
            $betId = $data['betId'];
            if (!$userId || !$tableName || !$betAmount || !isset($data['winAmount']))
            {
                return response()->json([
                    'result' => false,
                    'message' => 'No Parameter',
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
            $detect = new \Detection\MobileDetect();
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
            $master = $user->referral;
            while ($master!=null && !$master->isInoutPartner())
            {
                $master = $master->referral;
            }
            if ($master == null)
            {
                return null;
            }
            $recommend = config('app.gac_key');
            $query = 'SELECT * FROM w_provider_info WHERE provider="gac" and user_id=' . $master->id;
            $gac_info = \DB::select($query);
            foreach ($gac_info as $info)
            {
                $recommend = $info->config;
            }
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
                $response = Http::timeout(10)->post(config('app.gac_api') . '/wallet/api/getLobbyUrl', $data);
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
            $url = '/gac/golobby?code=' . $gamecode;
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

    }

}

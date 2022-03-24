<?php 
namespace VanguardLTE\Http\Controllers\Web\GameProviders
{
    use Illuminate\Support\Facades\Http;
    class GACController extends \VanguardLTE\Http\Controllers\Controller
    {
        /*
        * UTILITY FUNCTION
        */

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

        public function getGameObj($tableName)
        {
            $gamelist = GACController::getgamelist('gac');
            $tableName = preg_replace('/\s+/', '', $tableName);
            if ($gamelist)
            {
                foreach($gamelist as $game)
                {

                    if ($game['name'] == $tableName)
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

            $category = \VanguardLTE\Category::where(['provider' => 'gac', 'shop_id' => 0, 'href' => 'gac'])->first();

            $gameObj = GACController::getGameObj($tableName);
            if (!$gameObj)
            {
                $gameObj['name'] = 'Unknown';
                $gameObj['gameid'] = '0';
            }

            \VanguardLTE\StatGame::create([
                'user_id' => $user->id, 
                'balance' => intval($user->balance), 
                'bet' => $betAmount, 
                'win' => $winAmount, 
                'game' =>  $gameObj['name'] . '_gac', 
                'type' => 'table',
                'percent' => 0, 
                'percent_jps' => 0, 
                'percent_jpg' => 0, 
                'profit' => 0, 
                'denomination' => 0, 
                'shop_id' => $user->shop_id,
                'category_id' => isset($category)?$category->id:0,
                'game_id' => $gameObj['gamecode'],
                'roundid' => $betId,
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
            $data = [
                'userId' => strval($user->id),
                'userName' => $user->username,
                'recommend' => config('app.gac_key'),
                'gameType' => 36
            ];
            if ($gamecode != 'Lobby')
            {
                $data['tableId'] = $gamecode;
            }
            $url = null;
            try {
                $response = Http::timeout(10)->post(config('app.gac_api') . '/wallet/api/getLobbyUrl', $data);
                if (!$response->ok())
                {
                    return ['error' => true, 'data' => $response->body()];
                }
                $data = $response->json();
                if (isset($data['lobbyUrl'])){
                    $url = $data['lobbyUrl'];
                }
            }
            catch (\Exception $ex)
            {
                return ['error' => true, 'data' => 'request failed'];
            }
            return $url;
        }

        public static function getgamelink($gamecode)
        {
            $url = GACController::makegamelink($gamecode);
            if ($url)
            {
                return ['error' => false, 'data' => ['url' => $url]];
            }
            return ['error' => true, 'msg' => '로그인하세요'];
        }

    }

}

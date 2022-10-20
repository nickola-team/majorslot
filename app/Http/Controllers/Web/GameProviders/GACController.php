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
                'data' => json_encode([[
                    "GameType"=> 31,
                    "Limits"=> [[
                        "tableIds"=> ["default"],
                        "BetLimit"=> [
                            "Baccarat_Min"=> 1000,
                            "Baccarat_PlayerPair"=> 1000000,
                            "Baccarat_Player"=> 30000000,
                            "Baccarat_Tie"=> 1000000,
                            "Baccarat_Banker"=> 30000000,
                            "Baccarat_BankerPair"=> 1000000,
                            "Baccarat_PlayerBonus"=> 1000000,
                            "Baccarat_BankerBonus"=> 1000000,
                            "Baccarat_PerfectPair"=> 1000000,
                            "Baccarat_EitherPair"=> 1000000,
                            "Baccarat_SuperSix"=> 2500000,
                            "DragonTiger_Min"=> 1000,
                            "DragonTiger_Dragon"=> 5000000,
                            "DragonTiger_Tiger"=> 5000000,
                            "DragonTiger_Tie"=> 500000,
                            "DragonTiger_SuitedTie"=> 50000,
                            "Roulette_Min"=> 1000,
                            "Roulette_Straight"=> 100000,
                            "Roulette_Split"=> 200000,
                            "Roulette_Street"=> 300000,
                            "Roulette_Corner"=> 400000,
                            "Roulette_Line"=> 600000,
                            "Roulette_Column"=> 1200000,
                            "Roulette_Dozen"=> 1200000,
                            "Roulette_Red"=> 2000000,
                            "Roulette_Black"=> 2000000,
                            "Roulette_Even"=> 2000000,
                            "Roulette_Odd"=> 2000000,
                            "Roulette_Low"=> 2000000,
                            "Roulette_High"=> 2000000,
                            "SicBo_Min"=> 1000,
                            "SicBo_Big"=> 2500000,
                            "SicBo_Small"=> 2500000,
                            "SicBo_Even"=> 2500000,
                            "SicBo_Odd"=> 2500000,
                            "SicBo_Triple"=> 250000,
                            "SicBo_Total4"=> 50000,
                            "SicBo_Total5"=> 100000,
                            "SicBo_Total6"=> 250000,
                            "SicBo_Total7"=> 500000,
                            "SicBo_Total8"=> 500000,
                            "SicBo_Total9"=> 500000,
                            "SicBo_Total10"=> 500000,
                            "SicBo_Total11"=> 500000,
                            "SicBo_Total12"=> 500000,
                            "SicBo_Total13"=> 500000,
                            "SicBo_Total14"=> 500000,
                            "SicBo_Total15"=> 250000,
                            "SicBo_Total16"=> 100000,
                            "SicBo_Total17"=> 50000,
                            "SicBo_1"=> 250000,
                            "SicBo_2"=> 250000,
                            "SicBo_3"=> 250000,
                            "SicBo_4"=> 250000,
                            "SicBo_5"=> 250000,
                            "SicBo_6"=> 250000,
                            "SicBo_Double1"=> 250000,
                            "SicBo_Double2"=> 250000,
                            "SicBo_Double3"=> 250000,
                            "SicBo_Double4"=> 250000,
                            "SicBo_Double5"=> 250000,
                            "SicBo_Double6"=> 250000,
                            "SicBo_Triple1"=> 250000,
                            "SicBo_Triple2"=> 250000,
                            "SicBo_Triple3"=> 250000,
                            "SicBo_Triple5"=> 250000,
                            "SicBo_Triple4"=> 250000,
                            "SicBo_Triple6"=> 250000,
                            "SicBo_Combo1And2"=> 500000,
                            "SicBo_Combo1And3"=> 500000,
                            "SicBo_Combo1And4"=> 500000,
                            "SicBo_Combo1And5"=> 500000,
                            "SicBo_Combo1And6"=> 500000,
                            "SicBo_Combo2And3"=> 500000,
                            "SicBo_Combo2And4"=> 500000,
                            "SicBo_Combo2And5"=> 500000,
                            "SicBo_Combo2And6"=> 500000,
                            "SicBo_Combo3And4"=> 500000,
                            "SicBo_Combo3And5"=> 500000,
                            "SicBo_Combo3And6"=> 500000,
                            "SicBo_Combo4And5"=> 500000,
                            "SicBo_Combo4And6"=> 500000,
                            "SicBo_Combo5And6"=> 500000
                        ]
                    ], [
                        "tableIds"=> ["p63cmvmwagteemoy", "onokyd4wn7uekbjx", "qgdk6rtpw6hax4fe"],
                        "BetLimit"=> [
                            "Baccarat_Min"=> 1000,
                            "Baccarat_PlayerPair"=> 500000,
                            "Baccarat_Player"=> 30000000,
                            "Baccarat_Tie"=> 1000000,
                            "Baccarat_Banker"=> 30000000,
                            "Baccarat_BankerPair"=> 500000,
                            "Baccarat_PlayerBonus"=> 1000000,
                            "Baccarat_BankerBonus"=> 1000000,
                            "Baccarat_PerfectPair"=> 100000,
                            "Baccarat_EitherPair"=> 1000000,
                            "Baccarat_SuperSix"=> 2500000
                        ]
                    ], [
                        "tableIds"=> ["qgqrucipvltnvnvq", "oytmvb9m1zysmc44", "60i0lcfx5wkkv3sy", "ndgvs3tqhfuaadyg", "peekbaccarat0001", "ndgvz5mlhfuaad6e", "obj64qcnqfunjelj", "qgqrv4asvltnvuty", "nmwde3fd7hvqhq43", "ocye2ju2bsoyq6vv", "ovu5eja74ccmyoiq", "nmwdzhbg7hvqh6a7", "ndgv45bghfuaaebf", "ovu5h6b3ujb4y53w", "ndgvwvgthfuaad3q", "qgqrhfvsvltnueqf", "leqhceumaq6qfoug", "qgonc7t4ucdiel4o", "pwsaqk24fcz5qpcr", "o4kymodby2fa2c7g", "nxpkul2hgclallno", "o4kyj7tgpwqqy4m4", "o4kylkahpwqqy57w", "ndgv76kehfuaaeec", "ocye5hmxbsoyrcii", "lv2kzclunt2qnxo5", "puu43e6c5uvrfikr", "ovu5cwp54ccmymck", "ovu5dsly4ccmynil", "ovu5fzje4ccmyqnr", "qgqrrnuqvltnvejx", "ovu5fbxm4ccmypmb", "nxpj4wumgclak2lx", "puu4yfymic3reudn", "k2oswnib7jjaaznw", "zixzea8nrf1675oh"],
                        "BetLimit"=> [
                            "Baccarat_Min"=> 1000,
                            "Baccarat_PlayerPair"=> 500000,
                            "Baccarat_Player"=> 10000000,
                            "Baccarat_Tie"=> 1000000,
                            "Baccarat_Banker"=> 10000000,
                            "Baccarat_BankerPair"=> 500000,
                            "Baccarat_PlayerBonus"=> 1000000,
                            "Baccarat_BankerBonus"=> 1000000,
                            "Baccarat_PerfectPair"=> 100000,
                            "Baccarat_EitherPair"=> 1000000,
                            "Baccarat_SuperSix"=> 2500000
                        ]
                    ], [
                        "tableIds"=> ["rng-gwbaccarat00", "gwbaccarat000001"],
                        "BetLimit"=> [
                            "Baccarat_Min"=> 1000,
                            "Baccarat_PlayerPair"=> 1000000,
                            "Baccarat_Player"=> 5000000,
                            "Baccarat_Tie"=> 500000,
                            "Baccarat_Banker"=> 5000000,
                            "Baccarat_BankerPair"=> 1000000,
                            "Baccarat_PlayerBonus"=> 1000000,
                            "Baccarat_BankerBonus"=> 1000000,
                            "Baccarat_PerfectPair"=> 100000,
                            "Baccarat_EitherPair"=> 1000000,
                            "Baccarat_SuperSix"=> 2500000
                        ]
                    ], [
                        "tableIds"=> ["LightningBac0001"],
                        "BetLimit"=> [
                            "Baccarat_Min"=> 1000,
                            "Baccarat_PlayerPair"=> 500000,
                            "Baccarat_Player"=> 10000000,
                            "Baccarat_Tie"=> 500000,
                            "Baccarat_Banker"=> 10000000,
                            "Baccarat_BankerPair"=> 500000,
                            "Baccarat_PlayerBonus"=> 1000000,
                            "Baccarat_BankerBonus"=> 1000000,
                            "Baccarat_PerfectPair"=> 100000,
                            "Baccarat_EitherPair"=> 1000000,
                            "Baccarat_SuperSix"=> 2500000
                        ]
                    ]]
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
            $gameObj = GACController::getGameObj($gamecode);
            if (!$gameObj)
            {
                return ['error' => true, 'msg' => '게임이 없습니다'];
            }
            $url = GACController::makegamelink($gamecode);
            // $url = '/gac/golobby?code=' . $gamecode;
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

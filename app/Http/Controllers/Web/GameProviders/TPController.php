<?php 
namespace VanguardLTE\Http\Controllers\Web\GameProviders
{
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Log;
    class TPController extends \VanguardLTE\Http\Controllers\Controller
    {
        /*
        * UTILITY FUNCTION
        */

        const TP_PROVIDER = 'tp';
        const TP_PP_HREF = 'tp_pp';
        public static function getGameObjBySymbol($sym)
        {
            $gamelist = TPController::getgamelist(self::TP_PP_HREF);
            if ($gamelist)
            {
                foreach($gamelist as $game)
                {
                    if ($game['symbol'] == $sym)
                    {
                        return $game;
                        break;
                    }
                }
            }
            return null;
        }
        public static function getGameObj($uuid)
        {
            $gamelist = TPController::getgamelist(self::TP_PP_HREF);
            if ($gamelist)
            {
                foreach($gamelist as $game)
                {
                    if ($game['gamecode'] == $uuid)
                    {
                        return $game;
                        break;
                    }
                }
            }
            return null;
        }

        /*
        * FROM ThePlus, Utility API
        */

        
        /*
        * FROM CONTROLLER, API
        */

        public function userSignal(\Illuminate\Http\Request $request)
        {
            $user = \VanguardLTE\User::lockForUpdate()->where('id',auth()->id())->first();
            if (!$user)
            {
                return response()->json([
                    'error' => '1',
                    'description' => 'unlogged']);
            }
            if ($user->playing_game != self::TP_PROVIDER)
            {
                return response()->json([
                    'error' => '1',
                    'description' => 'Idle TimeOut']);
            }

            if ($request->name == 'exitGame')
            {
                $user->update([
                    'playing_game' => self::TP_PROVIDER . 'exit',
                    'played_at' => time()
                ]);
            }
            else
            {
                $balance = TPController::getuserbalance($user->id);
                if ($balance != null)
                {
                    $user->update([
                        'balance' => $balance,
                        'played_at' => time()
                    ]);
                }
            }
            return response()->json([
                'error' => '0',
                'description' => 'OK']);
        }

        public static function getuserbalance($userID) {
            $url = config('app.tp_api') . '/custom/api/user/GetBalance';
            $key = config('app.tp_api_key');
            $secret = config('app.tp_api_secret');
    
            $params = [
                'key' => $key,
                'secret' => $secret,
                'userID' => self::TP_PROVIDER . $userID,
                'isRenew' => true
            ];
    
            $response = Http::post($url, $params);
            
            $balance = null;
            if ($response->ok()) {
                $res = $response->json();
    
                if ($res['resultCode'] == 0) {
                    $balance = $res['balance'];
                }
            }
    
            return $balance;
        }
        
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

            $url = config('app.tp_api') . '/custom/api/game/List';
            $key = config('app.tp_api_key');
            $secret = config('app.tp_api_secret');

            $category = 8; //only use pragmatic
            if ($href == self::TP_PP_HREF) {
                $category = 8;
            }

            $params = [
                'key' => $key,
                'secret' => $secret,
                'thirdParty' => $category
            ];

            $response = Http::post($url, $params);

            if (!$response->ok())
            {
                return [];
            }
            
            $data = $response->json();
            $gameList = [];
            if ($data['resultCode'] == 0)
            {
                foreach ($data['data'] as $game)
                {
                    array_push($gameList, [
                        'provider' => self::TP_PROVIDER,
                        'symbol' => $game['symbol'],
                        'gamecode' => $game['uuid'],
                        'enname' => $game['name'],
                        'name' => preg_replace('/\s+/', '', $game['name']),
                        'title' => $game['nameKO'],
                        'icon' => $game['img'],
                        'type' => strtolower($game['type']),
                        'view' => 1
                    ]);
                }
            }
            \Illuminate\Support\Facades\Redis::set($href.'list', json_encode($gameList));
            return $gameList;
            
        }

        public static function getgamelink_tp($gamecode, $user) 
        {
            //Create Game Session
            $url = config('app.tp_api') . '/custom/api/game/CreateSession';
            $key = config('app.tp_api_key');
            $secret = config('app.tp_api_secret');
            $params = [
                'key' => $key,
                'secret' => $secret,
                'userID' => self::TP_PROVIDER . $user->id,
            ];

            $response = Http::post($url, $params);
            if (!$response->ok())
            {
                Log::error('TPGetLink : Game Session request failed. ' . $response->body());
                return ['error' => true, 'data' => 'Game Session request failed'];
            }
            $data = $response->json();
            if ($data==null || ($data['resultCode']!=0 )) //Deposit failed
            {
                Log::error('TPGetLink : Game Session result failed. ' . ($data==null?'null':$data['resultMessage']));
                return ['error' => true, 'data' => 'Game Session result failed'];
            }

            $session = $data['session'];

            //Create Game link
            $url = config('app.tp_api') . '/custom/api/game/Link';
            $params = [
                'key' => $key,
                'secret' => $secret,
                'session' => $session,
                'gameID' => $gamecode
            ];

            $response = Http::post($url, $params);
            if (!$response->ok())
            {
                Log::error('TPGetLink : Game link request failed. ' . $response->body());
                return ['error' => true, 'data' => 'Game link request failed'];
            }
            $data = $response->json();
            if ($data==null || ($data['resultCode']!=0 )) //Deposit failed
            {
                Log::error('TPGetLink : Game link result failed. ' . ($data==null?'null':$data['resultMessage']));
                return ['error' => true, 'data' => 'Game link result failed'];
            }

            $url = $data['gameUrl'];  
            return ['error' => false, 'data' => ['url' => $url]];
        }

        public static function makelink($gamecode, $userid)
        {
            $user = \VanguardLTE\User::where('id', $userid)->first();
            if (!$user)
            {
                Log::error('TPMakeLink : Does not find user ' . $userid);
                return null;
            }
            //create theplus account
            $url = config('app.tp_api') . '/custom/api/user/Create';
            $key = config('app.tp_api_key');
            $secret = config('app.tp_api_secret');
            $params = [
                'key' => $key,
                'secret' => $secret,
                'userID' => self::TP_PROVIDER . $user->id
            ];

            $response = Http::post($url, $params);
            if (!$response->ok())
            {
                Log::error('TPMakeLink : Create account request failed. ' . $response->body());
                return null;
            }
            $data = $response->json();
            if ($data==null || ($data['resultCode']!=0 && $data['resultCode']!=89)) //create success or already exist
            {
                Log::error('TPMakeLink : Create account result failed. ' . ($data==null?'null':$data['resultMessage']));
                return null;
            }

            //withdraw all balance

            $url = config('app.tp_api') . '/custom/api/user/WithdrawAll';
            $response = Http::post($url, $params);
            if (!$response->ok())
            {
                Log::error('TPMakeLink : WithdrawAll request failed. ' . $response->body());
                return null;
            }
            $data = $response->json();
            if ($data==null || ($data['resultCode']!=0 )) //WithdrawAll failed
            {
                Log::error('TPMakeLink : WithdrawAll result failed. ' . ($data==null?'null':$data['resultMessage']));
                return null;
            }

            //Add balance

            $url = config('app.tp_api') . '/custom/api/user/Deposit';
            $params = [
                'key' => $key,
                'secret' => $secret,
                'userID' => self::TP_PROVIDER . $user->id,
                'amount' => (int)$user->balance
            ];

            $response = Http::post($url, $params);
            if (!$response->ok())
            {
                Log::error('TPMakeLink : Deposit request failed. ' . $response->body());
                return null;
            }
            $data = $response->json();
            if ($data==null || ($data['resultCode']!=0 )) //Deposit failed
            {
                Log::error('TPMakeLink : Deposit result failed. ' . ($data==null?'null':$data['resultMessage']));
                return null;
            }
             
            return '/providers/tp/'.$gamecode;

        }

        public static function getgamelink($gamecode)
        {
            $user = auth()->user();
            if ($user->playing_game != null) //already playing game.
            {
                return ['error' => true, 'data' => '이미 실행중인 게임을 종료해주세요. 이미 종료했음에도 불구하고 이 메시지가 계속 나타난다면 매장에 문의해주세요.'];
            }
            return ['error' => false, 'data' => ['url' => route('frontend.providers.waiting', [self::TP_PROVIDER, $gamecode])]];
        }

        public static function gamerounds($timepoint)
        {
            $url = config('app.tp_api') . '/system/api/GetBetWinLogByIndex';
            $key = config('app.tp_api_key');
            $secret = config('app.tp_api_secret');
            $pageSize = 2000;
            $params = [
                'key' => $key,
                'secret' => $secret,
                'pageSize' => $pageSize,
                'objectID' => $timepoint
            ];
            $response = null;

            try {
                $response = Http::post($url, $params);
            } catch (\Exception $e) {
                Log::error('TPGameRoudns : GameRounds request failed. ' . $e->getMessage());
                return null;
            }

            if (!$response->ok())
            {
                Log::error('TPGameRoudns : GameRounds request failed. ' . $response->body());
                return null;
            }

            $data = $response->json();
            return $data;
        }

        public static function processGameRound()
        {
            $tpoint = \VanguardLTE\Settings::where('key', 'TPtimepoint')->first();
            if ($tpoint)
            {
                $timepoint = $tpoint->value;
            }
            else
            {
                $timepoint = 0;
            }

            //get category id
            $category = \VanguardLTE\Category::where(['provider' => self::TP_PROVIDER, 'shop_id' => 0, 'href' => self::TP_PP_HREF])->first();
            
            $data = TPController::gamerounds($timepoint);
            $count = 0;
            if ($data && $data['resultCode'] == 0 && $data['totalDataSize'] > 0)
            {
                $timepoint = $data['lastObjectID'];
                if ($tpoint)
                {
                    $tpoint->update(['value' => $timepoint]);
                    $tpoint->save();
                }
                else
                {
                    \VanguardLTE\Settings::create(['key' => 'TPtimepoint', 'value' => $timepoint]);
                }
                foreach ($data['data'] as $round)
                {
                    if ($round['Status'] == 'BET')
                    {
                        $bet = $round['Amount'];
                        $win = 0;
                    }
                    if ($round['Status'] == 'WIN') {
                        $bet = 0;
                        $win = $round['Amount'];
                    }

                    $balance = $round['Balance'];
                    $time = $round['Date'];
                    $userid = preg_replace('/'. self::TP_PROVIDER .'(\d+)/', '$1', $round['PlayerID']) ;
                    $shop = \VanguardLTE\ShopUser::where('user_id', $userid)->first();
                    $gameObj =  TPController::getGameObjBySymbol($round['GameID']);
                    \VanguardLTE\StatGame::create([
                        'user_id' => $userid, 
                        'balance' => $balance, 
                        'bet' => $bet, 
                        'win' => $win, 
                        'game' =>$gameObj['name'] . '_tp', 
                        'type' => 'slot',
                        'percent' => 0, 
                        'percent_jps' => 0, 
                        'percent_jpg' => 0, 
                        'profit' => 0, 
                        'denomination' => 0, 
                        'date_time' => $time,
                        'shop_id' => $shop->shop_id,
                        'category_id' => isset($category)?$category->id:0,
                        'game_id' => $round['GameID'],
                        'roundid' => $round['ObjectID'],
                    ]);
                    $count = $count + 1;
                }
            }
            return [$count, $timepoint];
        }

    }

}

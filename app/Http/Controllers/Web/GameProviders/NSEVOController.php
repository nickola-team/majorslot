<?php 
namespace VanguardLTE\Http\Controllers\Web\GameProviders
{
    use Illuminate\Support\Facades\Http;
    class NSEVOController extends \VanguardLTE\Http\Controllers\Controller
    {
        /*
        * UTILITY FUNCTION
        */

        const APIKEY='3F25E8248-F8091926E';
        const APIURL='https://evo0-gaming.com:8000';

        public static function microtime_string()
        {
            $microstr = sprintf('%.4f', microtime(TRUE));
            $microstr = str_replace('.', '', $microstr);
            return $microstr;
        }

        public static function getGameObj($code)
        {

            $gamelist = EVOController::getgamelist('evo');

            if ($gamelist)
            {
                foreach($gamelist as $game)
                {
                    if ($game['gamecode'] == $code)
                    {
                        return $game;
                        break;
                    }
                    if (isset($game['gamecode1']) && ($game['gamecode1'] == $code))
                    {
                        return $game;
                        break;
                    }
                }
            }
            return null;
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
            
            array_unshift($gameList, [
                'provider' => 'nsevo',
                'gameid' => 'lobby',
                'gamecode' => 'nslobby',
                'enname' => 'lobby',
                'name' => 'lobby',
                'title' => '뉴캐슬 에볼로비',
                'type' => 'table',
                'href' => 'nsevo',
                'view' => 1,
                'icon' => '/frontend/Default/ico/gac/gvo/117.jpg',
            ]);
                
            \Illuminate\Support\Facades\Redis::set($href.'list', json_encode($gameList));
            return $gameList;
        }

        public static function makegamelink($gamecode)
        {
            //create user

            $url = null;
            try {
                $param = [
                    'apiKey' => self::APIKEY,
                    'userId' => auth()->user()->username,
                    'password' => '111111',
                    'type' => 1
                ];
                $response = Http::timeout(10)->post(self::APIURL . '/api/user/login_new', $param);
                if (!$response->ok())
                {
                    return ['error' => true, 'data' => $response->body()];
                }
                $data = $response->json();
                if ($data['error'] != 0) {
                    return ['error' => true, 'data' => $response->body()];
                }
            }
            catch (\Exception $ex)
            {
                return ['error' => true, 'data' => 'request failed'];
            }

            //user balance
            try {
                $param = [
                    'apiKey' => self::APIKEY,
                    'user_id' => auth()->user()->username,
                ];
                $response = Http::timeout(10)->post(self::APIURL . '/api/user/balance_new', $param);
                if (!$response->ok())
                {
                    return ['error' => true, 'data' => $response->body()];
                }
                $data = $response->json();
                if ($data['error'] != 0) {
                    return ['error' => true, 'data' => $response->body()];
                }
                if ($data['data']['amount'] > 0)
                {
                    //withdraw
                    $param = [
                        'apiKey' => self::APIKEY,
                        'user_id' => auth()->user()->username,
                        'amount' => $data['data']['amount']
                    ];
                    $response = Http::timeout(10)->post(self::APIURL . '/api/user/transaction_new/withdraw', $param);
                    if (!$response->ok())
                    {
                        return ['error' => true, 'data' => $response->body()];
                    }
                    $data = $response->json();
                    if ($data['error'] != 0) {
                        return ['error' => true, 'data' => $response->body()];
                    }
                }
                //deposit
                $param = [
                    'apiKey' => self::APIKEY,
                    'user_id' => auth()->user()->username,
                    'amount' => auth()->user()->balance
                ];
                $response = Http::timeout(10)->post(self::APIURL . '/api/user/transaction_new/deposit', $param);
                if (!$response->ok())
                {
                    return ['error' => true, 'data' => $response->body()];
                }
                $data = $response->json();
                if ($data['error'] != 0) {
                    return ['error' => true, 'data' => $response->body()];
                }

            }
            catch (\Exception $ex)
            {
                return ['error' => true, 'data' => 'request failed'];
            }

            //run
            //create user

            $url = null;
            try {
                $param = [
                    'agentKey' => self::APIKEY,
                    'user_id' => auth()->user()->username,
                    'password' => '111111',
                ];
                $response = Http::timeout(10)->post(self::APIURL . '/api/game/launch_new', $param);
                if (!$response->ok())
                {
                    return ['error' => true, 'data' => $response->body()];
                }
                $data = $response->json();
                if ($data['error'] != 0) {
                    return ['error' => true, 'data' => $response->body()];
                }
                $url = $data['data']['url'];
            }
            catch (\Exception $ex)
            {
                return ['error' => true, 'data' => 'request failed'];
            }


            return ['error' => false, 'data' => ['url' => $url]];
        }

        public static function getgamelink($gamecode)
        {
            $data = NSEVOController::makegamelink($gamecode);
            if ($data['error'] == true)
            {
                $data['msg'] = '로그인하세요';
            }
            return $data;
        }

    }

}

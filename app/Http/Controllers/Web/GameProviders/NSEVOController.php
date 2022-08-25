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
        const tableName = [
            0=>"코리안 스피드 바카라 A",
            1=>"코리안 스피드 바카라 B",
            8=>"풍성한 골든 바카라",
            9=>"라이트닝 바카라",
            11=>"엠퍼러 스피드 바카라 A",
            12=>"엠퍼러 스피드 바카라 B",
            13=>"엠퍼러 스피드 바카라 C",
            14=>"스피드 바카라 A",
            15=>"스피드 바카라 B",
            16=>"스피드 바카라 C",
            17=>"스피드 바카라 D",
            18=>"스피드 바카라 E",
            19=>"스피드 바카라 F",
            20=>"스피드 바카라 G",
            21=>"스피드 바카라 H",
            22=>"스피드 바카라 I",
            23=>"스피드 바카라 J",
            24=>"스피드 바카라 K",
            25=>"스피드 바카라 L",
            26=>"스피드 바카라 M",
            27=>"스피드 바카라 N",
            28=>"스피드 바카라 O",
            29=>"스피드 바카라 P",
            30=>"스피드 바카라 Q",
            31=>"스피드 바카라 R",
            32=>"스피드 바카라 S",
            50=>"스피드 바카라 T",
            51=>"스피드 바카라 U",
            52=>"스피드 바카라 V",
            54=>"스피드 바카라 W", 
            55=>"스피드 바카라 X", 
            33=>"노 커미션 스피드 바카라 A",
            34=>"노 커미션 스피드 바카라 B",
            35=>"노 커미션 스피드 바카라 C",
            36=>"노 커미션 바카라",
            37=>"바카라 A",
            38=>"바카라 B",
            39=>"바카라 C",
            40=>"바카라 스퀴즈",
            41=>"바카라 컨트롤 스퀴즈"
        ];

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

        public static function getgameround()
        {
            $start_time = gmdate('Y-m-d H:i:s'); 
            $last_time = gmdate('Y-m-d H:i:s',strtotime('-2 day'));
            $limit = 1000;
            $last = \VanguardLTE\StatGame::where('category_id', 33)->orderby('date_time', 'desc')->first();
            if ($last)
            {
                $last_time = gmdate('Y-m-d H:i:s',strtotime($last->date_time));
            }
            try {
                $param = [
                    'apiKey' => self::APIKEY,
                    'start_date' => $last_time,
                    'end_date' => $start_time,
                    'limit' => $limit,
                ];
                $response = Http::timeout(10)->post(self::APIURL . '/api/user/history_new/bet', $param);
                if (!$response->ok())
                {
                    return 'Error : ' . $response->body();
                }
                $data = $response->json();
                if ($data['error'] != 0) {
                    return 'Error : ' . $data['error'];
                }
                foreach ($data['data'] as $round)
                {
                    $bet = $round['bet_player'] + $round['bet_banker'] + $round['bet_tie'] + $round['bet_player_pair'] + $round['bet_banker_pair'] + $round['bet_player_bonus'] + $round['bet_banker_bonus'] + $round['bet_either_pair'] + $round['bet_perfect_pair'];

                    $win = $round['win_player'] + $round['win_banker'] + $round['win_tie'] + $round['win_player_pair'] + $round['win_banker_pair'] + $round['win_player_bonus'] + $round['win_banker_bonus'] + $round['win_either_pair'] + $round['win_perfect_pair'];

                    $bet_time = date('Y-m-d H:i:s', strtotime($round['bet_time'] . " +9 hour"));
                    $user_balance = $round['after_bet_balance'];

                    $user = \VanguardLTE\User::where('username', $round['user_id'])->first();
                    if ($user){
                        \VanguardLTE\StatGame::create([
                            'user_id' => $user->id, 
                            'balance' => $user_balance, 
                            'bet' => $bet, 
                            'win' => $win, 
                            'game' =>self::tableName[$round['table_index']], 
                            'type' => 'table',
                            'percent' => 0, 
                            'percent_jps' => 0, 
                            'percent_jpg' => 0, 
                            'profit' => 0, 
                            'denomination' => 0, 
                            'date_time' => $bet_time,
                            'shop_id' => $user->shop_id,
                            'category_id' => 33,
                            'game_id' => $round['table_index'],
                            'roundid' => $round['game_id'],
                        ]);
                    }
                }

                return 'process : ' . count($data['data']);
            }
            catch (\Exception $ex)
            {
                return 'Error : ' . $ex->getMessage();
            }
        }

    }

}

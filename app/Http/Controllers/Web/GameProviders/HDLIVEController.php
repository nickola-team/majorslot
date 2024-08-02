<?php 
namespace VanguardLTE\Http\Controllers\Web\GameProviders
{
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Log;
    class HDLIVEController extends \VanguardLTE\Http\Controllers\Controller
    {
        /*
        * UTILITY FUNCTION
        */

        const HDLIVE_PROVIDER = 'hdlive';
        const HDLIVE_GAMEKEY = 'live';
        const HDLIVE_GAME_IDENTITY = [
            //==== CASINO ====
            'hdlive' => ['thirdname' =>'hdlive','type' => 'casino'],
        ];
        public static function getGameObj($uuid)
        {
            foreach (HDLIVEController::HDLIVE_GAME_IDENTITY as $ref => $value)
            {
                $gamelist = HDLIVEController::getgamelist($ref);
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
            }
            return null;
        }
        
        /*
        * CLEINT IP
        */
        public static function getIp(){
            foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
                if (array_key_exists($key, $_SERVER) === true){
                    foreach (explode(',', $_SERVER[$key]) as $ip){
                        $ip = trim($ip); // just to be safe
                        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                            return $ip;
                        }
                    }
                }
            }
        }

        /*
        * GENERATE HASH
        */
        public static function generateHash($data){
            //$opKey = 'f3fff4d3-cbf6-46ef-b710-eea686fad487';
            $secretKey = config('app.hdlive_secretkey');
            if($data == null || count($data) == 0)
            {
                $result = $secretKey;
            }
            else
            {
                $body = json_encode($data);
                // json String + secretKey
                $result = $body.$secretKey;
            }

            // SHA-256 make hash
            $hash_data = hash('sha256', $result, true);
            
            // Base64 encoding
            $hash = base64_encode($hash_data);

            return $hash;
        }
        /*
        * FROM CONTROLLER, API
        */
        public static function sendRequest($sub_url, $param) {

            $url = config('app.hdlive_api') ;
            $agent = config('app.hdlive_agent');
            try {       
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $agent
                    ])->withBody(json_encode($param),'application/json')->post($url . $sub_url, $param);
                if ($response->ok()) {
                    $res = $response->json();
                    return $res;
                }
                else
                {
                    Log::error('HDLive Request : response is not okay. api=>' . $sub_url . ', param =>' . json_encode($param) . ', body=>' . $response->body());
                }
            }
            catch (\Exception $ex)
            {
                Log::error('HDLive Request :  Excpetion. exception= ' . $ex->getMessage());
                Log::error('HDLive Request :  Excpetion. PARAMS= ' . json_encode($param));
            }
            return null;
        }
        public static function moneyInfo($user_code) {
            
            $params = [
                'username' => $user_code
            ];

            $data = HDLIVEController::sendRequest('/balance', $params);
            return $data;
        }
        
        public static function getUserBalance($href, $user) {   

            $balance = -1;
            $user_code = self::HDLIVE_PROVIDER  . sprintf("%04d",$user->id);
            $data = HDLIVEController::moneyInfo($user_code);

            if ($data && $data['code'] == 0)
            {
                $balance = $data['balance'];
            }
            return intval($balance);
        }
        
        public static function getgamelist($href)
        {
            // $gameList = \Illuminate\Support\Facades\Redis::get($href.'list');
            // if ($gameList)
            // {
            //     $games = json_decode($gameList, true);
            //     if ($games!=null && count($games) > 0){
            //         return $games;
            //     }
            // }
            // $category = HDLIVEController::HDLIVE_GAME_IDENTITY[$href];
            

            // $params = [
            //     'vendorKey' => $category['thirdname'],
            //     'skin' => HDLIVEController::HDLIVE_GAMEKEY,
            // ];

            // $gameList = [];
            // $data = HDLIVEController::sendRequest('/games', $params);

            // if ($data && $data['code'] == 0)
            // {
            //     foreach ($data['games'] as $game)
            //     {
            //         $gamename = 'Unknown';
            //         $gametitle = 'Unknown';
            //         if(isset($game['names']))
            //         {
            //             $gamename = isset($game['names']['ko'])?$game['names']['ko']:$game['names']['en'];
            //             $gametitle = $game['names']['en'];
            //         }
            //         $icon = '';
            //         if(isset($game['image']))
            //         {
            //             $icon = $game['image'];
            //         }
            //         array_push($gameList, [
            //             'provider' => self::HDLIVE_PROVIDER,
            //             'href' => $href,
            //             'gamecode' => $game['id'],
            //             'symbol' => $game['key'],
            //             'name' => preg_replace('/\s+/', '', $gamename),
            //             'title' => $gametitle,
            //             'type' => 'table',
            //             'icon' => $icon,
            //             'view' => 0
            //         ]);
            //     }
            //     //add casino lobby
            //     array_push($gameList, [
            //         'provider' => self::HDLIVE_PROVIDER,
            //         'href' => $href,
            //         'gamecode' => $category['thirdname'],
            //         'symbol' => 'gclobby',
            //         'name' => 'Lobby',
            //         'title' => 'Lobby',
            //         'type' => 'table',
            //         'icon' => '/frontend/Default/ico/gold/EVOLUTION_Lobby.jpg',
            //         'view' => 1
            //     ]);
            // }

            // //add Unknown Game item
            // array_push($gameList, [
            //     'provider' => self::HDLIVE_PROVIDER,
            //     'href' => $href,
            //     'symbol' => 'Unknown',
            //     'gamecode' => 'Unknown',
            //     'enname' => 'UnknownGame',
            //     'name' => 'UnknownGame',
            //     'title' => 'UnknownGame',
            //     'icon' => '',
            //     'type' => 'table',
            //     'view' => 0
            // ]);
            // \Illuminate\Support\Facades\Redis::set($href.'list', json_encode($gameList));
            // return $gameList;
            return [[
                'provider' => self::HDLIVE_PROVIDER,
                'href' => $href,
                'gamecode' => 'holdem',
                'symbol' => 'holdem',
                'name' => 'Holdem',
                'title' => '홀덤',
                'type' => 'table',
                'icon' => '/frontend/Default/ico/gold/Holdem.png',
                'view' => 1
            ]];
        }

        public static function makegamelink($gamecode, $user) 
        {
            $user_code = self::HDLIVE_PROVIDER  . sprintf("%04d",$user->id);
            $params = [
                'gameKey' => $gamecode,
                'lobbyKey' => HDLIVEController::HDLIVE_GAMEKEY,
                'username' => $user_code,
                'siteUsername' => $user_code,
                'nickname' => $user->username,
                'platform' => 'web',
                'amount' => $user->balance,
                'ipAddress' => '127.0.0.1',
                'requestKey' => $user->generateCode(24)
            ];

            $data = HDLIVEController::sendRequest('/play', $params);
            $url = null;

            if ($data && $data['code'] == 0)
            {
                $url = $data['url'];
            }
            else
            {
                Log::error('HDLIVEMakeLink : geturl, msg=  ' . $data['msg']);
            }

            return $url;
        }
        
        public static function withdrawAll($href, $user)
        {
            $balance = HDLIVEController::getuserbalance($href,$user);
            if ($balance < 0)
            {
                return ['error'=>true, 'amount'=>$balance, 'msg'=>'getuserbalance return -1'];
            }
            if ($balance > 0)
            {
                $params = [
                    'username' => self::HDLIVE_PROVIDER  . sprintf("%04d",$user->id),
                    'ipAddress' => '127.0.0.1',
                    'requestKey' => $user->generateCode(24)
                ];

                $data = HDLIVEController::sendRequest('/withdraw', $params);
                if ($data==null || $data['code'] != 0)
                {
                    return ['error'=>true, 'amount'=>0, 'msg'=>'data not ok'];
                }
            }
            return ['error'=>false, 'amount'=>$balance];
        }

        public static function makelink($gamecode, $userid)
        {
            $user = \VanguardLTE\User::where('id', $userid)->first();
            if (!$user)
            {
                Log::error('HDLIVEMakeLink : Does not find user ' . $userid);
                return null;
            }

            $game = HDLIVEController::getGameObj($gamecode);
            if ($game == null)
            {
                Log::error('HDLIVEMakeLink : Game not find  ' . $gamecode);
                return null;
            }
            $user_code = self::HDLIVE_PROVIDER  . sprintf("%04d",$user->id);
            $balance = 0;

            //유저정보 조회
            $data = HDLIVEController::moneyInfo($user_code);
            if($data == null || $data['code'] != 0)
            {
                //새유저 창조
                $params = [
                    'username' => $user_code,
                    'nickname' => $user_code,
                    'siteUsername' => $user_code,
                    'ipAddress' => '127.0.0.1',
                    'requestKey' => $user->generateCode(24)
                ];

                $data = HDLIVEController::sendRequest('/register', $params);
                if ($data==null || $data['code'] != 0)
                {
                    Log::error('HDLIVEMakeLink : Player Create, msg=  ' . $data['msg']);
                    return null;
                }
            }
            else
            {
                $balance = $data['balance'];
            }

            if ($balance != $user->balance)
            {
                //withdraw all balance
                $data = HDLIVEController::withdrawAll($game['href'], $user);
                if ($data['error'])
                {
                    return null;
                }
                //Add balance

                if ($user->balance > 0)
                {
                    $params = [
                        'username' => $user_code,
                        "amount" =>  intval($user->balance),
                        'ipAddress' => '127.0.0.1',
                        "requestKey" => $user->generateCode(24)
                    ];
    
                    $data = HDLIVEController::sendRequest('/deposit', $params);
                    if ($data==null || $data['code'] != 0)
                    {
                        Log::error('HDLIVEMakeLink : addMemberPoint result failed. ' . ($data==null?'null':json_encode($data)));
                        return null;
                    }
                    
                }
            }
            
            return '/followgame/hdlive/'.$gamecode;

        }

        public static function getgamelink($gamecode)
        {
            return ['error' => false, 'data' => ['url' => route('frontend.providers.waiting', [HDLIVEController::HDLIVE_PROVIDER, $gamecode])]];
        }

        public static function gamerounds($fromtimepoint, $totimepoint)
        {
            $sdate = date('Y-m-d H:i:s', $fromtimepoint);
            $edate = date('Y-m-d H:i:s', $totimepoint);
            $params = [
                'beginDate' => $sdate,
                'start_idx' => 0,
                'limit' => 2000
            ];

            $data = HDLIVEController::sendRequest('/transaction', $params);

            return $data;
        }

        public static function processGameRound($frompoint=-1, $checkduplicate=false)
        {
            $timepoint = strtotime(date('Y-m-d H:i:s',strtotime('-2 hours'))) - 9 * 60 * 60;
            $totimepoint = strtotime(date('Y-m-d H:i:s')) - 9 * 60 * 60;
            if ($frompoint == -1)
            {
                $tpoint = \VanguardLTE\Settings::where('key', self::HDLIVE_PROVIDER . 'timepoint')->first();
                if ($tpoint)
                {
                    $timepoint = $tpoint->value;
                }
            }
            else
            {
                $timepoint = $frompoint;
            }
            if($timepoint > $totimepoint)
            {
                $totimepoint = $timepoint + 2 * 60 * 60;
            }
            $count = 0;
            $data = null;
            $newtimepoint = $timepoint;
            $totalCount = 0;
            $data = HDLIVEController::gamerounds($timepoint, $totimepoint);
            if ($data == null || $data['code'] != 0 || $data['count'] == 0)
            {
                if ($frompoint == -1)
                {

                    if ($tpoint)
                    {
                        $tpoint->update(['value' => $newtimepoint]);
                    }
                    else
                    {
                        \VanguardLTE\Settings::create(['key' => self::HDLIVE_PROVIDER .'timepoint', 'value' => $newtimepoint]);
                    }
                }

                return [0,0];
            }
            $timepoint = strtotime($data['beginDate']);
            $invalidRounds = [];
            if (isset($data['data']))
            {
                foreach ($data['data'] as $round)
                {
                    // if ($round['txn_type'] != 'CREDIT')
                    // {
                    //     continue;
                    // }
                    $gameObj = self::getGameObj($round['pGame']);
                    
                    if (!$gameObj)
                    {
                        $gameObj = self::getGameObj($round['nGame']);
                        if (!$gameObj)
                        {
                            Log::error('HDLIVE Game could not found : '. $round['gameId']);
                            continue;
                        }
                    }
                    $bet = $round['WinMoney'];
                    $win = $round['EtcMoney'];
                    if($bet == 0 && $win == 0)
                    {
                        continue;
                    }
                    $balance = $round['STMoney'] - $bet + $win;
                    
                    $time = date('Y-m-d H:i:s',strtotime($round['createdAt']));

                    $userid = intval(preg_replace('/'. self::HDLIVE_PROVIDER  .'(\d+)/', '$1', $round['user_id'])) ;
                    if($userid == 0){
                        $userid = intval(preg_replace('/'. self::HDLIVE_PROVIDER . 'user' .'(\d+)/', '$1', $round['user_id'])) ;
                    }

                    $shop = \VanguardLTE\ShopUser::where('user_id', $userid)->first();
                    $category = \VanguardLTE\Category::where('href', $gameObj['href'])->first();

                    // if ($checkduplicate)
                    // {
                        $checkGameStat = \VanguardLTE\StatGame::where([
                            'user_id' => $userid, 
                            'bet' => $bet, 
                            'win' => $win, 
                            'date_time' => $time,
                            'roundid' => $round['pGame'] . '#' . $round['game_idx'] . '#' . $round['transactionKey'],
                        ])->first();
                        if ($checkGameStat)
                        {
                            continue;
                        }
                    // }
                    $gamename = $gameObj['name'] . '_hdlive';
                    \VanguardLTE\StatGame::create([
                        'user_id' => $userid, 
                        'balance' => $balance, 
                        'bet' => $bet, 
                        'win' => $win, 
                        'game' => $gamename, 
                        'type' => 'table',
                        'percent' => 0, 
                        'percent_jps' => 0, 
                        'percent_jpg' => 0, 
                        'profit' => 0, 
                        'denomination' => 0, 
                        'date_time' => $time,
                        'shop_id' => $shop?$shop->shop_id:-1,
                        'category_id' => $category?$category->original_id:0,
                        'game_id' =>  $gameObj['gamecode'],
                        // 'status' =>  $gameObj['isFinished']==true ? 1 : 0,
                        'roundid' => $round['pGame'] . '#' . $round['game_idx'] . '#' . $round['transactionKey'],
                    ]);
                    $count = $count + 1;
                }
            }
            if ($frompoint == -1)
            {

                if ($tpoint)
                {
                    $tpoint->update(['value' => $timepoint]);
                }
                else
                {
                    \VanguardLTE\Settings::create(['key' => self::HDLIVE_PROVIDER .'timepoint', 'value' => $timepoint]);
                }
            }
           
            return [$count, $timepoint];
        }

        public static function getgamedetail(\VanguardLTE\StatGame $stat)
        {
            $betrounds = explode('#',$stat->roundid);
            if (count($betrounds) < 3)
            {
                return null;
            }
            $transactionKey = $betrounds[2];
            
            $params = [
                'id' => $transactionKey
            ];

            $data = HDLIVEController::sendRequest('/transaction_one', $params);
            if ($data==null || $data['code'] != 0)
            {
                return null;
            }


            $betdetails = null;
            $gametype = 'hdlive';
            $result = null;
            if($data['transactions'])
            {
                foreach($data['transactions'] as $transaction)
                {
                    if($transaction['transactionKey'] == $transactionKey)
                    {
                        if($transaction['detail'])
                        {
                            $betdetails = $transaction['detail'];
                        }
                        else
                        {
                            $betdetails = [];
                        }
                        $betdetails['betType'] = '';
                        $bet = 0;
                        $win = 0;
                        if($transaction['type'] == 'turn_bet')
                        {
                            $bet = $transaction['cash'];
                        }
                        else
                        {
                            $win = $transaction['cash'];
                        }
                        $betdetails['bet_money'] = $bet;
                        $betdetails['win_money'] = $win;
                        $betdetails = json_encode($betdetails);
                        break;
                    }
                }
            }
            // foreach ($data['wager'] as $bet)
            // {
            //     if ($bet['txn_type'] == 'CREDIT' && $bet['txn_no'] == $txn_no)
            //     {
            //         $gametype = $bet['type'];
            //         $betdetails = json_decode($bet['detail'], true);
            //         $betdetails['betType'] = $bet['type'];
            //         $betdetails['bet_money'] = $bet['bet_money'];
            //         $betdetails['win_money'] = $bet['win_money'];
            //         $betdetails = json_encode($betdetails);
            //         break;
            //     }
            // }

            return [
                'type' => $gametype,
                'result' => $result,
                'bets' => $betdetails,
                'stat' => $stat
            ];
        }


        public static function getAgentBalance()
        {

            $balance = -1;

            $params = [];

            $data = HDLIVEController::sendRequest('/partner/balance', $params);
            if ($data==null || $data['code'] != 0)
            {
                return null;
            }
            if ($data && $data['code'] == 0)
            {
                $balance = $data['balance'];
            }
            return intval($balance);
        }


    }

}

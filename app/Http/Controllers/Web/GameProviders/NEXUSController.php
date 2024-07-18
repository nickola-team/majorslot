<?php 
namespace VanguardLTE\Http\Controllers\Web\GameProviders
{
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Log;
    class NEXUSController extends \VanguardLTE\Http\Controllers\Controller
    {
        /*
        * UTILITY FUNCTION
        */

        const NEXUS_PROVIDER = 'nexus';
        const NEXUS_GAMEKEY = 'B';
        const NEXUS_GAME_IDENTITY = [
            //==== CASINO ====
            'nexus-evo' => ['thirdname' =>'evolution_casino','type' => 'casino'],
        ];
        public static function getGameObj($uuid)
        {
            foreach (NEXUSController::NEXUS_GAME_IDENTITY as $ref => $value)
            {
                $gamelist = NEXUSController::getgamelist($ref);
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
            $secretKey = config('app.nexus_secretkey');
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

            $url = config('app.nexus_api') ;
            $agent = config('app.nexus_agent');
            $hash = NEXUSController::generateHash($param);
            try {       
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'hash' => $hash,
                    'agent' => $agent
                    ])->asForm()->post($url . $sub_url, $param);
                
                if ($response->ok()) {
                    $res = $response->json();
                    return $res;
                }
                else
                {
                    Log::error('Nexus Request : response is not okay. api=>' . $sub_url . ', param =>' . json_encode($param) . ', body=>' . $response->body());
                }
            }
            catch (\Exception $ex)
            {
                Log::error('Nexus Request :  Excpetion. exception= ' . $ex->getMessage());
                Log::error('Nexus Request :  Excpetion. PARAMS= ' . json_encode($param));
            }
            return null;
        }
        public static function moneyInfo($user_code) {
            
            $params = [
                'username' => $user_code,
                'siteUsername' => $user_code,
            ];

            $data = NEXUSController::sendRequest('/balance', $params);
            return $data;
        }
        
        public static function getUserBalance($href, $user) {   

            $balance = -1;
            $user_code = self::NEXUS_PROVIDER  . sprintf("%04d",$user->id);
            $data = NEXUSController::moneyInfo($user_code);

            if ($data && $data['code'] == 0)
            {
                $balance = $data['balance'];
            }
            return intval($balance);
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
            $category = NEXUSController::NEXUS_GAME_IDENTITY[$href];
            

            $params = [
                'vendorKey' => $category['thirdname'],
                'skin' => NEXUSController::NEXUS_GAMEKEY,
            ];

            $gameList = [];
            $data = NEXUSController::sendRequest('/games', $params);

            if ($data && $data['code'] == 0)
            {
                foreach ($data['games'] as $game)
                {
                    $gamename = 'Unknown';
                    $gametitle = 'Unknown';
                    if(isset($game['names']))
                    {
                        $gamename = isset($game['names']['ko'])?$game['names']['ko']:$game['names']['en'];
                        $gametitle = $game['names']['en'];
                    }
                    $icon = '';
                    if(isset($game['image']))
                    {
                        $icon = $game['image'];
                    }
                    array_push($gameList, [
                        'provider' => self::NEXUS_PROVIDER,
                        'href' => $href,
                        'gamecode' => $game['id'],
                        'symbol' => $game['key'],
                        'name' => preg_replace('/\s+/', '', $gamename),
                        'title' => $gametitle,
                        'type' => 'table',
                        'icon' => $icon,
                        'view' => 0
                    ]);
                }
                //add casino lobby
                array_push($gameList, [
                    'provider' => self::NEXUS_PROVIDER,
                    'href' => $href,
                    'gamecode' => $category['thirdname'],
                    'symbol' => 'gclobby',
                    'name' => 'Lobby',
                    'title' => 'Lobby',
                    'type' => 'table',
                    'icon' => '/frontend/Default/ico/gold/EVOLUTION_Lobby.jpg',
                    'view' => 1
                ]);
            }

            //add Unknown Game item
            array_push($gameList, [
                'provider' => self::NEXUS_PROVIDER,
                'href' => $href,
                'symbol' => 'Unknown',
                'gamecode' => 'Unknown',
                'enname' => 'UnknownGame',
                'name' => 'UnknownGame',
                'title' => 'UnknownGame',
                'icon' => '',
                'type' => 'table',
                'view' => 0
            ]);
            \Illuminate\Support\Facades\Redis::set($href.'list', json_encode($gameList));
            return $gameList;
            
        }

        public static function makegamelink($gamecode, $user) 
        {
            $user_code = self::NEXUS_PROVIDER  . sprintf("%04d",$user->id);
            $params = [
                'vendorKey' => $gamecode,
                'gameKey' => NEXUSController::NEXUS_GAMEKEY,
                'siteUsername' => $user_code,
                'ip' => '',
                'language' => 'ko',
                'requestKey' => $user->generateCode(24)
            ];

            $data = NEXUSController::sendRequest('/play', $params);
            $url = null;

            if ($data && $data['code'] == 0)
            {
                $url = $data['url'];
            }
            else
            {
                Log::error('NEXUSMakeLink : geturl, msg=  ' . $data['msg']);
            }

            return $url;
        }
        
        public static function withdrawAll($href, $user)
        {
            $balance = NEXUSController::getuserbalance($href,$user);
            if ($balance < 0)
            {
                return ['error'=>true, 'amount'=>$balance, 'msg'=>'getuserbalance return -1'];
            }
            if ($balance > 0)
            {
                $params = [
                    'username' => self::NEXUS_PROVIDER  . sprintf("%04d",$user->id),
                    'siteUsername' => self::NEXUS_PROVIDER  . sprintf("%04d",$user->id),
                    'requestKey' => $user->generateCode(24)
                ];

                $data = NEXUSController::sendRequest('/withdraw', $params);
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
                Log::error('NEXUSMakeLink : Does not find user ' . $userid);
                return null;
            }

            $game = NEXUSController::getGameObj($gamecode);
            if ($game == null)
            {
                Log::error('NEXUSMakeLink : Game not find  ' . $gamecode);
                return null;
            }
            $user_code = self::NEXUS_PROVIDER  . sprintf("%04d",$user->id);
            $balance = 0;

            //유저정보 조회
            $data = NEXUSController::moneyInfo($user_code);
            if($data == null || $data['code'] != 0)
            {
                //새유저 창조
                $params = [
                    'username' => $user_code,
                    'nickname' => $user_code,
                    'siteUsername' => $user_code,
                ];

                $data = NEXUSController::sendRequest('/register', $params);
                if ($data==null || $data['code'] != 0)
                {
                    Log::error('NEXUSMakeLink : Player Create, msg=  ' . $data['msg']);
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
                $data = NEXUSController::withdrawAll($game['href'], $user);
                if ($data['error'])
                {
                    return null;
                }
                //Add balance

                if ($user->balance > 0)
                {
                    $params = [
                        'username' => $user_code,
                        'siteUsername' => $user_code,
                        "amount" =>  intval($user->balance),
                        "requestKey" => $user->generateCode(24)
                    ];
    
                    $data = NEXUSController::sendRequest('/deposit', $params);
                    if ($data==null || $data['code'] != 0)
                    {
                        Log::error('NEXUSMakeLink : addMemberPoint result failed. ' . ($data==null?'null':json_encode($data)));
                        return null;
                    }
                    
                }
            }
            
            return '/followgame/nexus/'.$gamecode;

        }

        public static function getgamelink($gamecode)
        {
            if (isset(self::NEXUS_GAME_IDENTITY[$gamecode]))
            {
                $gamelist = NEXUSController::getgamelist($gamecode);
                if (count($gamelist) > 0)
                {
                    foreach ($gamelist as $g)
                    {
                        if ($g['view'] == 1)
                        {
                            $gamecode = $g['gamecode'];
                            break;
                        }
                    }
                    
                }
            }
            return ['error' => false, 'data' => ['url' => route('frontend.providers.waiting', [NEXUSController::NEXUS_PROVIDER, $gamecode])]];
        }

        public static function gamerounds($fromtimepoint, $totimepoint)
        {
            $sdate = date('Y-m-d H:i:s', $fromtimepoint);
            $edate = date('Y-m-d H:i:s', $totimepoint);
            $params = [
                'vendorKey' => 'evolution_casino',
                'sdate' => $sdate,
                'edate' => $edate,
                'limit' => 4000
            ];

            $data = NEXUSController::sendRequest('/transaction', $params);

            return $data;
        }

        public static function processGameRound($frompoint=-1, $checkduplicate=false)
        {
            $timepoint = strtotime(date('Y-m-d H:i:s',strtotime('-2 hours'))) - 9 * 60 * 60;
            $totimepoint = strtotime(date('Y-m-d H:i:s')) - 9 * 60 * 60;
            if ($frompoint == -1)
            {
                $tpoint = \VanguardLTE\Settings::where('key', self::NEXUS_PROVIDER . 'timepoint')->first();
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
            $data = NEXUSController::gamerounds($timepoint, $totimepoint);
            if ($data == null || $data['code'] != 0 || $data['lastObjectId'] == '')
            {
                if ($frompoint == -1)
                {

                    if ($tpoint)
                    {
                        $tpoint->update(['value' => $newtimepoint]);
                    }
                    else
                    {
                        \VanguardLTE\Settings::create(['key' => self::NEXUS_PROVIDER .'timepoint', 'value' => $newtimepoint]);
                    }
                }

                return [0,0];
            }
            $lastObjectId = $data['lastObjectId'];
            $invalidRounds = [];
            if (isset($data['transactions']))
            {
                foreach ($data['transactions'] as $round)
                {
                    // if ($round['txn_type'] != 'CREDIT')
                    // {
                    //     continue;
                    // }
                    if($round['_id'] == $lastObjectId)
                    {
                        $timepoint = strtotime($round['createdAt']) + 1 - 9 * 60 * 60;
                    }
                    $gameObj = self::getGameObj($round['gameId']);
                    
                    if (!$gameObj)
                    {
                        $gameObj = self::getGameObj($round['vendorKey']);
                        if (!$gameObj)
                        {
                            Log::error('NEXUS Game could not found : '. $round['gameId']);
                            continue;
                        }
                    }
                    $bet = 0;
                    $win = 0;
                    if($round['type'] == 'turn_bet')
                    {
                        $bet = $round['cash'];
                    }
                    else if($round['type'] == 'turn_win' || $round['type'] == 'turn_draw' || $round['type'] == 'turn_cancel' || $round['type'] == 'turn_adjust')
                    {
                        $win = $round['cash'];
                        if(isset($round['updepositCash']) && $round['updepositCash'] > 0)
                        {
                            $win += $round['updepositCash'];
                        }
                    }
                    if($bet == 0 && $win == 0)
                    {
                        continue;
                    }
                    $balance = $round['afterCash'];
                    
                    $time = date('Y-m-d H:i:s',strtotime($round['createdAt']));

                    $userid = intval(preg_replace('/'. self::NEXUS_PROVIDER  .'(\d+)/', '$1', $round['siteUsername'])) ;
                    if($userid == 0){
                        $userid = intval(preg_replace('/'. self::NEXUS_PROVIDER . 'user' .'(\d+)/', '$1', $round['siteUsername'])) ;
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
                            'roundid' => $round['vendorKey'] . '#' . $round['gameId'] . '#' . $round['transactionKey'],
                        ])->first();
                        if ($checkGameStat)
                        {
                            if($round['type'] == 'turn_adjust')
                            {
                                $checkGameStat->update(['win' => $win]);
                            }
                            continue;
                        }
                    // }
                    $gamename = $gameObj['name'] . '_nexus';
                    if($round['type'] == 'turn_cancel')  // 취소처리된 경우
                    {
                        $gamename = $gameObj['name'] . '_nexus[C'.$time.']_' . $gameObj['href'];
                    }
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
                        'roundid' => $round['vendorKey'] . '#' . $round['gameId'] . '#' . $round['transactionKey'],
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
                    \VanguardLTE\Settings::create(['key' => self::NEXUS_PROVIDER .'timepoint', 'value' => $timepoint]);
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

            $data = NEXUSController::sendRequest('/transaction_one', $params);
            if ($data==null || $data['code'] != 0)
            {
                return null;
            }


            $betdetails = null;
            $gametype = 'nexus';
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

            $data = NEXUSController::sendRequest('/partner/balance', $params);
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

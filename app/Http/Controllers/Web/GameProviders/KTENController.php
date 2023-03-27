<?php 
namespace VanguardLTE\Http\Controllers\Web\GameProviders
{
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Log;
    class KTENController extends \VanguardLTE\Http\Controllers\Controller
    {
        /*
        * UTILITY FUNCTION
        */

        const KTEN_PROVIDER = 'kten';
        const KTEN_PP_HREF = 'kten-pp';
        const KTEN_GAME_IDENTITY = [
            //==== SLOT ====
            'kten-pp' => ['thirdname' =>'Pragmatic','type' => 'slot'],
            'kten-cq9' => ['thirdname' =>'Cq9','type' => 'slot'],
            'kten-hbn' => ['thirdname' =>'Habanero','type' => 'slot'],
            'kten-playson' => ['thirdname' =>'Playson','type' => 'slot'],
            'kten-bng' => ['thirdname' =>'Booongo','type' => 'slot'],
            'kten-dragon' => ['thirdname' =>'Dragongaming','type' => 'slot'],
            'kten-playstar' => ['thirdname' =>'Playstar','type' => 'slot'],
            'kten-gameart' => ['thirdname' =>'Gameart','type' => 'slot'],
            'kten-dreamtech' => ['thirdname' =>'Dreamtech','type' => 'slot'],
            'kten-mg' => ['thirdname' =>'Microgaming','type' => 'slot'],
            'kten-rtg' => ['thirdname' =>'Rtg','type' => 'slot'],
            'kten-pgsoft' => ['thirdname' =>'Pgs','type' => 'slot'],
            'kten-playngo' => ['thirdname' =>'Playngo','type' => 'slot'],
            'kten-evoplay' => ['thirdname' =>'Evoplay','type' => 'slot'],

            //==== CASINO ====
            'kten-og' => ['thirdname' =>'Og','type' => 'casino'],
            'kten-ppl' => ['thirdname' =>'Pragmatic','type' => 'casino'],
            'kten-mgl' => ['thirdname' =>'Microgaming','type' => 'casino'],
            'kten-dg' => ['thirdname' =>'Dreamgame','type' => 'casino'],
            'kten-asia' => ['thirdname' =>'Ag','type' => 'casino'],
        ];

        public static function getGameObj($uuid)
        {
            foreach (KTENController::KTEN_GAME_IDENTITY as $ref => $value)
            {
                $gamelist = KTENController::getgamelist($ref);
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
        */

        
        /*
        * FROM CONTROLLER, API
        */

        
        public static function getUserBalance($href, $user) {
            $url = config('app.kten_api') . '/api/getAccountBalance';
            $op = config('app.kten_op');
            $token = config('app.kten_key');
    
            $params = [
                'agentId' => $op,
                'token' => $token,
                'time' => time(),
                'userId' => self::KTEN_PROVIDER . sprintf("%04d",$user->id),
            ];
            $balance = -1;

            try {       
                $response = Http::get($url, $params);
                
                if ($response->ok()) {
                    $res = $response->json();
        
                    if ($res['errorCode'] == 0) {
                        $balance = $res['balance'];
                    }
                    else
                    {
                        Log::error('KTENgetuserbalance : return failed. ' . $res['errorCode']);
                    }
                }
                else
                {
                    Log::error('KTENgetuserbalance : response is not okay. ' . $response->body());
                }
            }
            catch (\Exception $ex)
            {
                Log::error('KTENgetuserbalance : getAccountBalance Excpetion. exception= ' . $ex->getMessage());
                Log::error('KTENgamerounds : getAccountBalance Excpetion. PARAMS= ' . json_encode($params));
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
            $category = KTENController::KTEN_GAME_IDENTITY[$href];
            $type = $category['type'];
            
            if ($type=='slot')
            {
                $url = config('app.kten_api') . '/api/getGameList';
            }
            elseif ($type=='casino')
            {
                $url = config('app.kten_api') . '/api/getLobbyList';
            }
            $op = config('app.kten_op');
            $token = config('app.kten_key');


            $params = [
                'agentId' => $op,
                'token' => $token,
                'thirdname' => $category['thirdname'],
                'time' => time(),
            ];

    
            $response = Http::get($url, $params);
            if (!$response->ok())
            {
                return [];
            }
            $data = $response->json();
            $gameList = [];

            foreach ($data as $game)
            {
                if (strtolower($game['game_type']) == $type) 
                {
                    $view = 1;
                    if ($href == 'kten-og' && $game['game_id'] != 'ogplus')
                    {
                        $view = 0; // hide other tables
                    }

                    if ($href == 'kten-ppl' && $game['game_id'] != '101')
                    {
                        $view = 0; // hide other tables
                    }
                    $korname = $game['cp_game_name_kor'];
                    if ($href == 'kten-bng')
                    {
                        $korname = preg_replace('/bng/', '', $korname);
                        $korname = preg_replace('/_/', ' ', $korname);
                    }
                    if ($href == 'kten-playson')
                    {
                        $korname = preg_replace('/pls/', '', $korname);
                        $korname = preg_replace('/_/', ' ', $korname);
                    }

                    if ($korname == '')
                    {
                        $korname = $game['cp_game_name_en'];
                    }

                    array_push($gameList, [
                        'provider' => self::KTEN_PROVIDER,
                        'href' => $href,
                        'gamecode' => $game['game_id'],
                        'symbol' => $game['game_id'],
                        'enname' => $game['cp_game_name_en'],
                        'name' => preg_replace('/\s+/', '', $game['cp_game_name_en']),
                        'title' => $korname,
                        'icon' => $game['thumbnail'],
                        'type' => $type=='casino'?'table':'slot',
                        'view' => $view
                    ]);
                }
            }

            //add Unknown Game item
            array_push($gameList, [
                'provider' => self::KTEN_PROVIDER,
                'href' => $href,
                'symbol' => 'Unknown',
                'gamecode' => $href,
                'enname' => 'UnknownGame',
                'name' => 'UnknownGame',
                'title' => 'UnknownGame',
                'icon' => '',
                'type' => 'slot',
                'view' => 0
            ]);
            \Illuminate\Support\Facades\Redis::set($href.'list', json_encode($gameList));
            return $gameList;
            
        }

        public static function makegamelink($gamecode, $user) 
        {

            $op = config('app.kten_op');
            $token = config('app.kten_key');
            

            //Create Game link

            $params = [
                'gameId' => $gamecode,
                'agentId' => $op,
                'token' => $token,
                'time' => time(),
                'userId' => self::KTEN_PROVIDER . sprintf("%04d",$user->id),
            ];

            $url = config('app.kten_api') . '/api/getGameUrl';
            $response = Http::get($url, $params);
            if (!$response->ok())
            {
                Log::error('KTENGetLink : Game url request failed. status=' . $response->status());
                Log::error('KTENGetLink : Game url request failed. param=' . json_encode($params));

                return null;
            }
            $data = $response->json();
            if ($data==null || $data['errorCode'] != 0)
            {
                Log::error('KTENGetLink : Game url result failed. ' . ($data==null?'null':$data['errorCode']));
                return null;
            }
            $url = $data['gameUrl'];

            return $url;
        }
        
        public static function withdrawAll($href, $user)
        {
            $balance = KTENController::getuserbalance($href,$user);
            if ($balance < 0)
            {
                return ['error'=>true, 'amount'=>$balance, 'msg'=>'getuserbalance return -1'];
            }
            if ($balance > 0)
            {
                $op = config('app.kten_op');
                $token = config('app.kten_key');

                $params = [
                    'amount' => $balance,
                    'agentId' => $op,
                    'token' => $token,
                    'transactionID' => uniqid(self::KTEN_PROVIDER),
                    'userId' => self::KTEN_PROVIDER . sprintf("%04d",$user->id),
                    'time' => time(),
                ];

                try {
                    $url = config('app.kten_api') . '/api/subtractMemberPoint';
                    $response = Http::get($url, $params);
                    if (!$response->ok())
                    {
                        Log::error('KTENWithdraw : subtractMemberPoint request failed. ' . $response->body());

                        return ['error'=>true, 'amount'=>0, 'msg'=>'response not ok'];
                    }
                    $data = $response->json();
                    if ($data==null || $data['errorCode'] != 0)
                    {
                        Log::error('KTENWithdraw : subtractMemberPoint result failed. PARAMS=' . json_encode($params));
                        Log::error('KTENWithdraw : subtractMemberPoint result failed. ' . ($data==null?'null':$data['errorCode']));
                        return ['error'=>true, 'amount'=>0, 'msg'=>'data not ok'];
                    }
                }
                catch (\Exception $ex)
                {
                    Log::error('KTENWithdraw : subtractMemberPoint Exception. Exception=' . $ex->getMessage());
                    Log::error('KTENWithdraw : subtractMemberPoint Exception. PARAMS=' . json_encode($params));
                    return ['error'=>true, 'amount'=>0, 'msg'=>'exception'];
                }
            }
            return ['error'=>false, 'amount'=>$balance];
        }

        public static function makelink($gamecode, $userid)
        {
            $user = \VanguardLTE\User::where('id', $userid)->first();
            if (!$user)
            {
                Log::error('KTENMakeLink : Does not find user ' . $userid);
                return null;
            }

            $game = KTENController::getGameObj($gamecode);
            if ($game == null)
            {
                Log::error('KTENMakeLink : Game not find  ' . $game);
                return null;
            }

            $op = config('app.kten_op');
            $token = config('app.kten_key');

            //check kten account
            $params = [
                'agentId' => $op,
                'token' => $token,
                'userId' => self::KTEN_PROVIDER . sprintf("%04d",$user->id),
                'time' => time(),
            ];
            $alreadyUser = 1;
            try
            {

                $url = config('app.kten_api') . '/api/checkUser';
                $response = Http::get($url, $params);
                if (!$response->ok())
                {
                    Log::error('KTENmakelink : checkUser request failed. ' . $response->body());
                    return null;
                }
                $data = $response->json();
                if ($data==null || ($data['errorCode'] != 0 && $data['errorCode'] != 4))
                {
                    Log::error('KTENmakelink : checkUser result failed. ' . ($data==null?'null':$data['msg']));
                    return null;
                }
                if ($data['errorCode'] == 4)
                {
                    $alreadyUser = 0;
                }
            }
            catch (\Exception $ex)
            {
                Log::error('KTENcheckuser : checkUser Exception. Exception=' . $ex->getMessage());
                Log::error('KTENcheckuser : checkUser Exception. PARAMS=' . json_encode($params));
                return ['error'=>true, 'amount'=>0, 'msg'=>'exception'];
            }
            if ($alreadyUser == 0){
                //create kten account
                $params = [
                    'agentId' => $op,
                    'token' => $token,
                    'userId' => self::KTEN_PROVIDER . sprintf("%04d",$user->id),
                    'time' => time(),
                    'email' => self::KTEN_PROVIDER . sprintf("%04d",$user->id) . '@masu.com',
                    'password' => '111111'
                ];
                try
                {

                    $url = config('app.kten_api') . '/api/createAccount';
                    $response = Http::asForm()->post($url, $params);
                    if (!$response->ok())
                    {
                        Log::error('KTENmakelink : createAccount request failed. ' . $response->body());
                        return null;
                    }
                    $data = $response->json();
                    if ($data==null || $data['errorCode'] != 0)
                    {
                        Log::error('KTENmakelink : createAccount result failed. ' . ($data==null?'null':$data['msg']));
                        return null;
                    }
                }
                catch (\Exception $ex)
                {
                    Log::error('KTENcheckuser : createAccount Exception. Exception=' . $ex->getMessage());
                    Log::error('KTENcheckuser : createAccount Exception. PARAMS=' . json_encode($params));
                    return ['error'=>true, 'amount'=>0, 'msg'=>'exception'];
                }
            }
            
            $balance = KTENController::getuserbalance($gamecode, $user);
            if ($balance == -1)
            {
                return null;
            }

            if ($balance != $user->balance)
            {
                //withdraw all balance
                $data = KTENController::withdrawAll($gamecode, $user);
                if ($data['error'])
                {
                    return null;
                }
                //Add balance

                if ($user->balance > 0)
                {
                    //addMemberPoint
                    $params = [
                        'amount' => floatval($user->balance),
                        'agentId' => $op,
                        'token' => $token,
                        'transactionID' => uniqid(self::KTEN_PROVIDER),
                        'userId' => self::KTEN_PROVIDER . sprintf("%04d",$user->id),
                        'time' => time(),
                    ];
                    try {
                        $url = config('app.kten_api') . '/api/addMemberPoint';
                        $response = Http::get($url, $params);
                        if (!$response->ok())
                        {
                            Log::error('KTENmakelink : addMemberPoint request failed. ' . $response->body());

                            return null;
                        }
                        $data = $response->json();
                        if ($data==null || $data['errorCode'] != 0)
                        {
                            Log::error('KTENmakelink : addMemberPoint result failed. ' . ($data==null?'null':$data['errorCode']));
                            return null;
                        }
                    }
                    catch (\Exception $ex)
                    {
                        Log::error('KTENmakelink : addMemberPoint Exception. exception=' . $ex->getMessage());
                        Log::error('KTENmakelink : addMemberPoint PARAM. PARAM=' . json_encode($params));
                        return null;
                    }
                }
            }
            
            return '/followgame/kten/'.$gamecode;

        }

        public static function getgamelink($gamecode)
        {
            if (isset(self::KTEN_GAME_IDENTITY[$gamecode]) && self::KTEN_GAME_IDENTITY[$gamecode]['type']=='casino')
            {
                $gamelist = KTENController::getgamelist($gamecode);
                if (count($gamelist) > 0)
                {
                    $gamecode = $gamelist[0]['gamecode'];
                }
            }
            return ['error' => false, 'data' => ['url' => route('frontend.providers.waiting', [KTENController::KTEN_PROVIDER, $gamecode])]];
        }

        public static function gamerounds($lastid, $pageIdx)
        {
            
            $op = config('app.kten_op');
            $token = config('app.kten_key');


            $params = [
                'agentId' => $op,
                'token' => $token,
                'pageSize' => 1000,
                'pageStart' => $pageIdx,
                'time' => time(),
                'lastid' => $lastid
            ];
            try
            {
                $url = config('app.kten_api') . '/api/getBetWinHistoryAll';
                $response = Http::get($url, $params);
                if (!$response->ok())
                {
                    Log::error('KTENgamerounds : getBetWinHistoryAll request failed. PARAMS= ' . json_encode($params));
                    Log::error('KTENgamerounds : getBetWinHistoryAll request failed. ' . $response->body());

                    return null;
                }
                $data = $response->json();
                if ($data==null || $data['errorCode'] != 0)
                {
                    Log::error('KTENgamerounds : getBetWinHistoryAll result failed. PARAMS=' . json_encode($params));
                    Log::error('KTENgamerounds : getBetWinHistoryAll result failed. ' . ($data==null?'null':$data['errorCode']));
                    return null;
                }

                return $data;
            }
            catch (\Exception $ex)
            {
                Log::error('KTENgamerounds : getBetWinHistoryAll Excpetion. exception= ' . $ex->getMessage());
                Log::error('KTENgamerounds : getBetWinHistoryAll Excpetion. PARAMS= ' . json_encode($params));
            }
            return null;
        }

        public static function processGameRound($frompoint=-1, $checkduplicate=false)
        {
            $timepoint = 0;
            if ($frompoint == -1)
            {
                $tpoint = \VanguardLTE\Settings::where('key', self::KTEN_PROVIDER . 'timepoint')->first();
                if ($tpoint)
                {
                    $timepoint = $tpoint->value;
                }
            }
            else
            {
                $timepoint = $frompoint;
            }

            $count = 0;
            $curPage = 1;
            $data = null;
            $newtimepoint = $timepoint;
            do
            {
                $data = KTENController::gamerounds($timepoint, $curPage);
                if ($data == null)
                {
                    if ($frompoint == -1)
                    {

                        if ($tpoint)
                        {
                            $tpoint->update(['value' => $newtimepoint]);
                        }
                        else
                        {
                            \VanguardLTE\Settings::create(['key' => self::KTEN_PROVIDER .'timepoint', 'value' => $newtimepoint]);
                        }
                    }

                    return [0,0];
                }

                if (isset($data['totalPageSize']) && $data['totalPageSize'] > 0)
                {
                    foreach ($data['data'] as $round)
                    {
                        $bet = 0;
                        $win = 0;
                        $gameName = $round['gameId'];
                        $thirdname = $round['cp_name'];
                        if ($thirdname == 'Og')
                        {
                            $gameName = 'ogplus_' . $gameName;
                        }
                        $gameObj = self::getGameObj($gameName);
                        if (!$gameObj)
                        {
                            
                            
                            foreach (KTENController::KTEN_GAME_IDENTITY as $ref => $value)
                            {
                                if ($value['thirdname'] == $thirdname)
                                {
                                    $gameObj = self::getGameObj($ref);
                                    break;
                                }
                            }
                            if (!$gameObj)
                            {
                                Log::error('KTEN Game could not found : '. $thirdname . ' -' . $gameName);
                                continue;
                            }
                        }
                        //only check thirdname
                        if (self::KTEN_GAME_IDENTITY[$gameObj['href']]['thirdname'] != $thirdname)
                        {
                            Log::error('KTEN Game category is mismatched : '. self::KTEN_GAME_IDENTITY[$gameObj['href']]['thirdname'] . ' -' . $thirdname);
                        }


                        $type = $gameObj['type'];
                        $catname = $gameObj['href'];
                        if ($catname == 'kten-cq9')
                        {
                            if ($round['type'] == 'WIN')
                            {
                                continue;
                            }
                            $betdata = json_decode($round['details'],true);
                            $bet = $betdata['bet'];
                            $win = $betdata['win'];
                            $balance = $betdata['balance'];
                        }
                        else if ($catname == 'kten-bng' || $catname == 'kten-playson')
                        {
                            if ($round['type'] == 'WIN')
                            {
                                continue;
                            }
                            $betdata = json_decode($round['details'],true);
                            $bet = $betdata['bet']??0;
                            $win = $betdata['win'];
                            $balance = $betdata['balance_after'];
                        }
                        else if ($catname == 'kten-hbn')
                        {
                            if ($round['type'] == 'WIN')
                            {
                                continue;
                            }
                            if (!isset($round['details']))
                            {
                                Log::error('KTEN HBN round : '. json_encode($round));
                                continue;
                            }

                            $betdata = json_decode($round['details'],true);
                            $bet = $betdata['Stake'];
                            $win = $betdata['Payout'];
                            $balance = $betdata['BalanceAfter'];
                        }
                        // else if ($catname == 'kten-pp')
                        // {
                        //     if ($round['type'] == 'WIN')
                        //     {
                        //         continue;
                        //     }
                        //     if (!isset($round['details']))
                        //     {
                        //         Log::error('KTEN PP round : '. json_encode($round));
                        //         continue;
                        //     }

                        //     $betdata = json_decode($round['details'],true);
                        //     $bet = intval($betdata['bet']);
                        //     $win = intval($betdata['win']);
                        //     $balance = intval($betdata['balance']);
                        // }
                        else if ($catname == 'kten-og')
                        {
                            if ($round['type'] == 'WIN')
                            {
                                continue;
                            }

                            $betdata = json_decode($round['details'],true);
                            if (!$betdata)
                            {
                                Log::error('KTEN OG round : '. json_encode($round));
                                continue;
                            }
                            $bet = $betdata['bettingamount'];
                            $win = $bet + $betdata['winloseamount'];
                            $balance = $betdata['balance'];
                        }
                        else
                        {
                            if ($round['type'] == 'BET')
                            {
                                $bet = $round['amount'];
                            }
                            else
                            {
                                $win = $round['amount'];
                            }

                            $balance = -1;
                        }
                        if (is_null($win))
                        {
                            $win = 0;
                        }
                        if ($bet==0 && $win==0)
                        {
                            continue;
                        }
                        $time = $round['trans_time'];

                        $userid = intval(preg_replace('/'. self::KTEN_PROVIDER .'(\d+)/', '$1', $round['mem_id'])) ;
                        $shop = \VanguardLTE\ShopUser::where('user_id', $userid)->first();
                        $category = \VanguardLTE\Category::where('href', $catname)->first();

                        // if ($checkduplicate)
                        // {
                            $checkGameStat = \VanguardLTE\StatGame::where([
                                'user_id' => $userid, 
                                'bet' => $bet, 
                                'win' => $win, 
                                'date_time' => $time,
                                'roundid' => $round['gameId'] . '#' . $round['roundID'] . '#' . $round['id'],
                            ])->first();
                            if ($checkGameStat)
                            {
                                continue;
                            }
                        // }
                        
                        \VanguardLTE\StatGame::create([
                            'user_id' => $userid, 
                            'balance' => $balance, 
                            'bet' => $bet, 
                            'win' => $win, 
                            'game' =>$gameObj['name'] . '_kten', 
                            'type' => $type,
                            'percent' => 0, 
                            'percent_jps' => 0, 
                            'percent_jpg' => 0, 
                            'profit' => 0, 
                            'denomination' => 0, 
                            'date_time' => $time,
                            'shop_id' => $shop?$shop->shop_id:0,
                            'category_id' => $category?$category->original_id:0,
                            'game_id' =>  $gameName,
                            'roundid' => $round['gameId'] . '#' . $round['roundID'] . '#' . $round['id'],
                        ]);
                        $count = $count + 1;
                    }
                    $newtimepoint = $data['lastid'];

                }
                $curPage = $curPage + 1;
            }
            while ($curPage <= $data['totalPageSize']);

            $timepoint = $newtimepoint;

            if ($frompoint == -1)
            {

                if ($tpoint)
                {
                    $tpoint->update(['value' => $timepoint]);
                }
                else
                {
                    \VanguardLTE\Settings::create(['key' => self::KTEN_PROVIDER .'timepoint', 'value' => $timepoint]);
                }
            }
           
            return [$count, $timepoint];
        }


        public static function getAgentBalance()
        {

            $op = config('app.kten_op');
            $token = config('app.kten_key');

            //check kten account
            $params = [
                'agentId' => $op,
                'token' => $token,
                'time' => time(),
            ];

            try
            {

                $url = config('app.kten_api') . '/api/getAgentAccountBalance';
                $response = Http::get($url, $params);
                if (!$response->ok())
                {
                    Log::error('KTENAgentBalance : agentbalance request failed. ' . $response->body());
                    return -1;
                }
                $data = $response->json();
                if (($data==null) || ($data['errorCode'] != 0))
                {
                    Log::error('KTENAgentBalance : agentbalance result failed. ' . ($data==null?'null':$data['msg']));
                    return -1;
                }
                return $data['balance'];
            }
            catch (\Exception $ex)
            {
                Log::error('KTENAgentBalance : agentbalance Exception. Exception=' . $ex->getMessage());
                Log::error('KTENAgentBalance : agentbalance Exception. PARAMS=' . json_encode($params));
                return -1;
            }

        }

        public static function syncpromo()
        {
            $user = \VanguardLTE\User::where('role_id', 1)->whereNull('playing_game')->first();           
            if (!$user)
            {
                return ['error' => true, 'msg' => 'not found any available user.'];
            }
            $gamelist = KTENController::getgamelist(self::KTEN_PP_HREF);
            $len = count($gamelist);
            if ($len > 10) {$len = 10;}
            if ($len == 0)
            {
                return ['error' => true, 'msg' => 'not found any available game.'];
            }
            $rand = mt_rand(0,$len);
                
            $gamecode = $gamelist[$rand]['gamecode'];
            $op = config('app.kten_op');
            $token = config('app.kten_key');

            //check kten account
            $params = [
                'agentId' => $op,
                'token' => $token,
                'userId' => self::KTEN_PROVIDER . sprintf("%04d",$user->id),
                'time' => time(),
            ];
            $alreadyUser = 1;
            try
            {

                $url = config('app.kten_api') . '/api/checkUser';
                $response = Http::get($url, $params);
                if (!$response->ok())
                {
                    Log::error('KTENmakelink : checkUser request failed. ' . $response->body());
                    return ['error' => true, 'msg' => 'checkUser failed.'];
                }
                $data = $response->json();
                if ($data==null || ($data['errorCode'] != 0 && $data['errorCode'] != 4))
                {
                    Log::error('KTENmakelink : checkUser result failed. ' . ($data==null?'null':$data['msg']));
                    return ['error' => true, 'msg' => 'checkUser failed.'];
                }
                if ($data['errorCode'] == 4)
                {
                    $alreadyUser = 0;
                }
            }
            catch (\Exception $ex)
            {
                Log::error('KTENcheckuser : checkUser Exception. Exception=' . $ex->getMessage());
                Log::error('KTENcheckuser : checkUser Exception. PARAMS=' . json_encode($params));
                return ['error' => true, 'msg' => 'checkUser failed.'];
            }
            if ($alreadyUser == 0){
                //create kten account
                $params = [
                    'agentId' => $op,
                    'token' => $token,
                    'userId' => self::KTEN_PROVIDER . sprintf("%04d",$user->id),
                    'time' => time(),
                    'email' => self::KTEN_PROVIDER . sprintf("%04d",$user->id) . '@masu.com',
                    'password' => '111111'
                ];
                try
                {

                    $url = config('app.kten_api') . '/api/createAccount';
                    $response = Http::asForm()->post($url, $params);
                    if (!$response->ok())
                    {
                        Log::error('KTENmakelink : createAccount request failed. ' . $response->body());
                        return ['error' => true, 'msg' => 'createAccount failed.'];
                    }
                    $data = $response->json();
                    if ($data==null || $data['errorCode'] != 0)
                    {
                        Log::error('KTENmakelink : createAccount result failed. ' . ($data==null?'null':$data['msg']));
                        return ['error' => true, 'msg' => 'createAccount failed.'];
                    }
                }
                catch (\Exception $ex)
                {
                    Log::error('KTENcheckuser : createAccount Exception. Exception=' . $ex->getMessage());
                    Log::error('KTENcheckuser : createAccount Exception. PARAMS=' . json_encode($params));
                    return ['error' => true, 'msg' => 'createAccount failed.'];
                }
            }

            $url = KTENController::makegamelink($gamecode, $user);
            if ($url == null)
            {
                return ['error' => true, 'msg' => 'game link error '];
            }

            $parse = parse_url($url);
            $ppgameserver = $parse['scheme'] . '://' . $parse['host'];
        
            //emulate client
            $response = Http::withOptions(['allow_redirects' => false,'proxy' => config('app.ppproxy')])->get($url);
            if ($response->status() == 302)
            {
                $location = $response->header('location');
                $keys = explode('&', $location);
                $mgckey = null;
                foreach ($keys as $key){
                    if (str_contains( $key, 'mgckey='))
                    {
                        $mgckey = $key;
                    }
                    if (str_contains($key, 'symbol='))
                    {
                        $gamecode = $key;
                    }
                }
                if (!$mgckey){
                    return ['error' => true, 'msg' => 'could not find mgckey value'];
                }
                
                $promo = \VanguardLTE\PPPromo::take(1)->first();
                if (!$promo)
                {
                    $promo = \VanguardLTE\PPPromo::create();
                }
                $raceIds = [];
                $response =  Http::withOptions(['proxy' => config('app.ppproxy')])->get($ppgameserver . '/gs2c/promo/active/?'.$gamecode.'&' . $mgckey );
                if ($response->ok())
                {
                    $promo->active = $response->body();
                    $json_data = $response->json();
                    if (isset($json_data['races']))
                    {
                        foreach ($json_data['races'] as $race)
                        {
                            $raceIds[$race['id']] = null;
                        }
                    }
                }
                $response =  Http::withOptions(['proxy' => config('app.ppproxy')])->get($ppgameserver . '/gs2c/promo/tournament/details/?'.$gamecode.'&' . $mgckey );
                if ($response->ok())
                {
                    $promo->tournamentdetails = $response->body();
                }
                $response =  Http::withOptions(['proxy' => config('app.ppproxy')])->get($ppgameserver . '/gs2c/promo/race/details/?'.$gamecode.'&' . $mgckey );
                if ($response->ok())
                {
                    $promo->racedetails = $response->body();
                }
                $response =  Http::withOptions(['proxy' => config('app.ppproxy')])->get($ppgameserver . '/gs2c/promo/tournament/v3/leaderboard/?'.$gamecode.'&' . $mgckey );
                if ($response->ok())
                {
                    $promo->tournamentleaderboard = $response->body();
                }
                $response =  Http::withOptions(['proxy' => config('app.ppproxy')])->get($ppgameserver . '/gs2c/promo/race/prizes/?'.$gamecode.'&' . $mgckey );
                if ($response->ok())
                {
                    $promo->raceprizes = $response->body();
                }
                
                $response =  Http::withOptions(['proxy' => config('app.ppproxy')])->post($ppgameserver . '/gs2c/promo/race/v2/winners/?'.$gamecode.'&' . $mgckey, ['latestIdentity' => $raceIds]) ;
                if ($response->ok())
                {
                    $promo->racewinners = $response->body();
                }

                $response =  Http::withOptions(['proxy' => config('app.ppproxy')])->get($ppgameserver . '/gs2c/minilobby/games?' . $mgckey );
                if ($response->ok())
                {
                    $json_data = $response->json();
                    //disable not own games
                    $ownCats = \VanguardLTE\Category::where(['href'=> 'pragmatic', 'shop_id'=>0,'site_id'=>0])->first();
                    $gIds = $ownCats->games->pluck('game_id')->toArray();
                    $ownGames = \VanguardLTE\Game::whereIn('id', $gIds)->where('view',1)->get();

                    $lobbyCats = $json_data['lobbyCategories'];
                    $filteredCats = [];
                    foreach ($lobbyCats as $cat)
                    {
                        $lobbyGames = $cat['lobbyGames'];
                        $filteredGames = [];
                        foreach ($lobbyGames as $game)
                        {
                            foreach ($ownGames as $og)
                            {
                                if ($og->label == $game['symbol'])
                                {
                                    $filteredGames[] = $game;
                                    break;
                                }
                            }
                        }
                        $cat['lobbyGames'] = $filteredGames;
                        $filteredCats[] = $cat;
                    }
                    $json_data['lobbyCategories'] = $filteredCats;
                    $json_data['gameLaunchURL'] = "/gs2c/minilobby/start";
                    $promo->games = json_encode($json_data);
                }

                $promo->save();
                return ['error' => false, 'msg' => 'synchronized successfully.'];
            }
            else
            {
                return ['error' => true, 'msg' => 'server response is not 302.'];
            }          
            
        }

    }

}

<?php 
namespace VanguardLTE\Http\Controllers\Web\GameProviders
{
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Log;
    class GOLDController extends \VanguardLTE\Http\Controllers\Controller
    {
        /*
        * UTILITY FUNCTION
        */

        const GOLD_PROVIDER = 'gold';
        const GOLD_GAME_IDENTITY = [
            //==== CASINO ====
            'gold-evo' => ['thirdname' =>'EVOLUTION','type' => 'casino'],
        ];
        public static function getGameObj($uuid)
        {
            foreach (GOLDController::GOLD_GAME_IDENTITY as $ref => $value)
            {
                $gamelist = GOLDController::getgamelist($ref);
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
        public static function sendRequest($param) {

            $url = config('app.gold_api') ;
            $op = config('app.gold_op');
            $token = config('app.gold_key');
            $param['agent_code'] = $op;
            $param['agent_token'] = $token;
            try {       
                $response = Http::withBody(json_encode($param),'application/json')->post($url);
                
                if ($response->ok()) {
                    $res = $response->json();
                    return $res;
                }
                else
                {
                    Log::error('GOLD Request : response is not okay. ' . json_encode($params) . '===body==' . $response->body());
                }
            }
            catch (\Exception $ex)
            {
                Log::error('GOLD Request :  Excpetion. exception= ' . $ex->getMessage());
                Log::error('GOLD Request :  Excpetion. PARAMS= ' . json_encode($params));
            }
            return null;
        }
        public static function moneyInfo($user_code) {
            
            $params = [
                'method' => 'money_info',
                'user_code' => $user_code,
            ];

            $data = GOLDController::sendRequest($params);
            return $data;
        }
        
        public static function getUserBalance($href, $user) {   

            $balance = -1;

            $data = GOLDController::moneyInfo(self::GOLD_PROVIDER . sprintf("%04d",$user->id));

            if ($data && $data['status'] == 1)
            {
                $balance = $data['user']['balance'];
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
            $category = GOLDController::GOLD_GAME_IDENTITY[$href];
            

            $params = [
                'method' => 'game_list',
                'provider_code' => $category['thirdname'],
            ];

            $gameList = [];
            $data = GOLDController::sendRequest($params);

            if ($data && $data['status'] == 1)
            {
                foreach ($data['gameList'] as $game)
                {
                    array_push($gameList, [
                        'provider' => self::GOLD_PROVIDER,
                        'href' => $href,
                        'gamecode' => $game['table_code'],
                        'symbol' => $game['table_code'],
                        'name' => preg_replace('/\s+/', '', $game['table_name']),
                        'title' => $game['table_name'],
                        'type' => 'table',
                        'view' => 0
                    ]);
                }
                //add casino lobby
                array_push($gameList, [
                    'provider' => self::GOLD_PROVIDER,
                    'href' => $href,
                    'gamecode' => 'EVOLUTION',
                    'symbol' => 'gcevolobby',
                    'name' => 'Lobby',
                    'title' => 'Lobby',
                    'type' => 'table',
                    'view' => 1
                ]);
            }

            //add Unknown Game item
            array_push($gameList, [
                'provider' => self::GOLD_PROVIDER,
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

            $params = [
                'method' => 'game_launch',
                'user_code' => self::GOLD_PROVIDER . sprintf("%04d",$user->id),
                "provider_code" =>  $gamecode,
            ];

            $data = GOLDController::sendRequest($params);
            $url = null;

            if ($data && $data['status'] == 1)
            {
                $url = $data['launch_url'];
            }

            return $url;
        }
        
        public static function withdrawAll($href, $user)
        {
            $balance = GOLDController::getuserbalance($href,$user);
            if ($balance < 0)
            {
                return ['error'=>true, 'amount'=>$balance, 'msg'=>'getuserbalance return -1'];
            }
            if ($balance > 0)
            {
                $params = [
                    'method' => 'user_withdraw',
                    'user_code' => self::GOLD_PROVIDER . sprintf("%04d",$user->id),
                    "amount" =>  $balance,
                ];

                $data = GOLDController::sendRequest($params);
                if ($data==null || $data['status'] != 1)
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
                Log::error('GOLDMakeLink : Does not find user ' . $userid);
                return null;
            }

            $game = GOLDController::getGameObj($gamecode);
            if ($game == null)
            {
                Log::error('GOLDMakeLink : Game not find  ' . $gamecode);
                return null;
            }

            //create gold account
            $params = [
                'method' => 'user_create',
                'user_code' => self::GOLD_PROVIDER . sprintf("%04d",$user->id),
            ];

            $data = GOLDController::sendRequest($params);
            if ($data==null || ($data['msg'] != 'SUCCESS' && $data['msg'] != 'DUPLICATE_USER'))
            {
                return null;
            }
            
            
            $balance = GOLDController::getuserbalance($game['href'], $user);
            if ($balance == -1)
            {
                return null;
            }

            if ($balance != $user->balance)
            {
                //withdraw all balance
                $data = GOLDController::withdrawAll($game['href'], $user);
                if ($data['error'])
                {
                    return null;
                }
                //Add balance

                if ($user->balance > 0)
                {
                    $params = [
                        'method' => 'user_deposit',
                        'user_code' => self::GOLD_PROVIDER . sprintf("%04d",$user->id),
                        "amount" =>  intval($user->balance),
                    ];
    
                    $data = GOLDController::sendRequest($params);
                    if ($data==null || $data['status'] != 1)
                    {
                        Log::error('GOLDMakeLink : addMemberPoint result failed. ' . ($data==null?'null':json_encode($data)));
                        return null;
                    }
                    
                }
            }
            
            return '/followgame/gold/'.$gamecode;

        }

        public static function getgamelink($gamecode)
        {
            return ['error' => false, 'data' => ['url' => route('frontend.providers.waiting', [GOLDController::GOLD_PROVIDER, $gamecode])]];
        }

        public static function gamerounds($lastid, $pageIdx)
        {
            
            $params = [
                'method' => 'transaction_no',
                'txn_no' => $lastid,
                'page_number' => $pageIdx,
                'perPage_count' => 1000
            ];

            $data = GOLDController::sendRequest($params);

            return $data;
        }

        public static function processGameRound($frompoint=-1, $checkduplicate=false)
        {
            $timepoint = 0;
            if ($frompoint == -1)
            {
                $tpoint = \VanguardLTE\Settings::where('key', self::GOLD_PROVIDER . 'timepoint')->first();
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
            $curPage = 0;
            $data = null;
            $newtimepoint = $timepoint;
            $totalCount = 0;
            do
            {
                $data = GOLDController::gamerounds($timepoint, $curPage);
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
                            \VanguardLTE\Settings::create(['key' => self::GOLD_PROVIDER .'timepoint', 'value' => $newtimepoint]);
                        }
                    }

                    return [0,0];
                }

                if (isset($data['data']))
                {
                    foreach ($data['data'] as $round)
                    {
                        if ($round['txn_type'] != 'CREDIT')
                        {
                            continue;
                        }

                        $gameObj = self::getGameObj($round['table_code']);
                        
                        if (!$gameObj)
                        {
                            $gameObj = self::getGameObj('Unknown');
                            if (!$gameObj)
                            {
                                Log::error('GOLD Game could not found : '. $round['table_code']);
                                continue;
                            }
                        }
                        $bet = $round['bet_money'];
                        $win = $round['win_money'];
                        $balance = $round['user_end_balance'];
                        
                        $time = date('Y-m-d H:i:s',strtotime($round['CreatedAt'] . ' +9 hours'));

                        $userid = intval(preg_replace('/'. self::GOLD_PROVIDER .'(\d+)/', '$1', $round['user_code'])) ;

                        $shop = \VanguardLTE\ShopUser::where('user_id', $userid)->first();
                        $category = \VanguardLTE\Category::where('href', $gameObj['href'])->first();

                        // if ($checkduplicate)
                        // {
                            $checkGameStat = \VanguardLTE\StatGame::where([
                                'user_id' => $userid, 
                                'bet' => $bet, 
                                'win' => $win, 
                                'date_time' => $time,
                                'roundid' => $round['table_code'] . '#' . $round['game_no'] . '#' . $round['txn_no'],
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
                            'game' =>$gameObj['name'] . '_gold', 
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
                            'roundid' => $round['table_code'] . '#' . $round['game_no'] . '#' . $round['txn_no'],
                        ]);
                        $count = $count + 1;
                        if ($newtimepoint < $round['txn_no'])
                        {
                            $newtimepoint = $round['txn_no'] + 1;
                        }
                    }
                }
                $curPage = $curPage + 1;
                if (count($data['data']) == 0)
                {
                    break;
                }
                $totalPage = intval($data['total_count'] / $data['perPage_count']);
            }
            while ($curPage <= $totalPage);

            $timepoint = $newtimepoint;

            if ($frompoint == -1)
            {

                if ($tpoint)
                {
                    $tpoint->update(['value' => $timepoint]);
                }
                else
                {
                    \VanguardLTE\Settings::create(['key' => self::GOLD_PROVIDER .'timepoint', 'value' => $timepoint]);
                }
            }
           
            return [$count, $timepoint];
        }


        public static function getAgentBalance()
        {

            $balance = -1;

            $data = GOLDController::moneyInfo(null);

            if ($data && $data['status'] == 1)
            {
                $balance = $data['agent']['balance'];
            }
            return intval($balance);
        }


    }

}

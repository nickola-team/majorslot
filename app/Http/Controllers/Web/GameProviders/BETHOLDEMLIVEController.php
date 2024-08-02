<?php 
namespace VanguardLTE\Http\Controllers\Web\GameProviders
{
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Log;
    class BETHOLDEMLIVEController extends \VanguardLTE\Http\Controllers\Controller
    {
        /*
        * UTILITY FUNCTION
        */

        const HDLIVE_PROVIDER = 'hdlive';
        const HDLIVE_GAME_IDENTITY = [
            //==== CASINO ====
            'hdlive' => ['thirdname' =>'hdlive','type' => 'casino'],
        ];
        public static function getGameObj($uuid)
        {
            foreach (BETHOLDEMLIVEController::HDLIVE_GAME_IDENTITY as $ref => $value)
            {
                $gamelist = BETHOLDEMLIVEController::getgamelist($ref);
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
        * FROM CONTROLLER, API
        */
        public static function sendRequest($param) {

            $url = config('app.hdlive_api') ;
            $agent = config('app.hdlive_agent');
            $param['agent_id'] = $agent;
            try {       
                $response = Http::withBody(json_encode($param),'application/json')->post($url);
                
                if ($response->ok()) {
                    $res = $response->json();
                    return $res;
                }
                else
                {
                    Log::error('HDLIVE Request : response is not okay. ' . json_encode($param) . '===body==' . $response->body());
                }
            }
            catch (\Exception $ex)
            {
                Log::error('HDLIVERequest :  Excpetion. exception= ' . $ex->getMessage());
                Log::error('HDLIVERequest :  Excpetion. PARAMS= ' . json_encode($param));
            }
            return null;
        }
        public static function getUserBalance($href, $user) {   

            $balance = -1;
            $user_code = self::HDLIVE_PROVIDER  . sprintf("%04d",$user->id);
            $params = [
                'func' => 'GetBalanceAll',
                'mem_id' => $user_code,
                'username' => $user->username,
            ];

            $data = BETHOLDEMLIVEController::sendRequest($params);

            if ($data && $data['code'] == 0)
            {
                if($data['status'] == 0)
                {
                    $balance = $data['balance'];
                }
                else
                {
                    $balance = -2;
                }
            }
            return intval($balance);
        }
        
        public static function getgamelist($href)
        {
            return [[
                'provider' => self::HDLIVE_PROVIDER,
                'href' => $href,
                'gamecode' => 'hdlive',
                'symbol' => 'hdlive',
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
                'func' => 'GetAuthLaunch',
                'mem_id' => $user_code,
            ];

            $data = BETHOLDEMLIVEController::sendRequest($params);
            $url = null;

            if ($data && $data['code'] == 0)
            {
                $url = $data['launch_url'];
            }
            else
            {
                Log::error('HDLIVEMakeLink : geturl, msg=  ' . $data['msg']);
            }

            return $url;
        }
        
        public static function withdrawAll($href, $user)
        {
            $balance = BETHOLDEMLIVEController::getuserbalance($href,$user);
            if ($balance < 0)
            {
                if($balance == -2)
                {
                    return ['error'=>true, 'amount'=>$balance, 'msg'=>'2분30초 후 다시 시도해 주십시요'];
                }
                else
                {
                    return ['error'=>true, 'amount'=>$balance, 'msg'=>'getuserbalance return -1'];
                }
            }
            if ($balance > 0)
            {
                $params = [
                    'func' => 'PointBalanceDec',
                    'mem_id' => self::HDLIVE_PROVIDER  . sprintf("%04d",$user->id),
                    'point' => $balance
                ];

                $data = BETHOLDEMLIVEController::sendRequest($params);
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

            $game = BETHOLDEMLIVEController::getGameObj($gamecode);
            if ($game == null)
            {
                Log::error('HDLIVEMakeLink : Game not find  ' . $gamecode);
                return null;
            }
            $user_code = self::HDLIVE_PROVIDER  . sprintf("%04d",$user->id);
            $balance = 0;

            //유저정보 조회
            $params = [
                'func' => 'GetBalanceAll',
                'mem_id' => $user_code,
                'username' => $user->username,
            ];

            $data = BETHOLDEMLIVEController::sendRequest($params);
            if($data == null || $data['code'] != 0)
            {
                Log::error('HDLIVEMakeLink : Player Create, msg=  ' . $data['msg']);
                return null;
            }
            else
            {
                $balance = $data['balance'];
            }

            if ($balance != $user->balance)
            {
                //withdraw all balance
                $data = BETHOLDEMLIVEController::withdrawAll($game['href'], $user);
                if ($data['error'])
                {
                    return null;
                }
                //Add balance

                if ($user->balance > 0)
                {
                    $params = [
                        'func' => 'PointBalanceAdd',
                        'mem_id' => $user_code,
                        "point" =>  intval($user->balance)
                    ];
    
                    $data = BETHOLDEMLIVEController::sendRequest($params);
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
            if (isset(self::HDLIVE_GAME_IDENTITY[$gamecode]))
            {
                $gamelist = BETHOLDEMLIVEController::getgamelist($gamecode);
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
            return ['error' => false, 'data' => ['url' => route('frontend.providers.waiting', [BETHOLDEMLIVEController::HDLIVE_PROVIDER, $gamecode])]];
        }

        public static function gamerounds($pageIdx, $startDate, $endDate)
        {
            $params = [
                'func' => 'GetLogs',
                'start' => $startDate,
                'end' => $endDate,
                // 'perPage' => 10000
            ];

            $data = BETHOLDEMLIVEController::sendRequest($params);
            if ($data==null || $data['code'] != 0)
            {
                Log::error('HDLIVELogs : GetLogs result failed. ' . ($data==null?'null':json_encode($data)));
                return null;
            }
            return $data;
        }

        public static function processGameRound($from='', $to='')
        {
            $count = 0;


            $category_ids = \VanguardLTE\Category::where([
                'provider'=> BETHOLDEMLIVEController::HDLIVE_PROVIDER,
                'shop_id' => 0,
                'site_id' => 0,
                ])->pluck('original_id')->toArray();
            if (count($category_ids) == 0)
            {
                return [$count, 0];
            }
            $roundfrom = '';
            $roundto = '';

            if ($from == '')
            {
                $roundfrom = date('Y-m-d H:i:s',strtotime('-2 hours'));
                $lastround = \VanguardLTE\StatGame::whereIn('category_id', $category_ids)->orderby('date_time', 'desc')->first();
                if ($lastround)
                {
                    $d = strtotime($lastround->date_time);
                    if ($d > strtotime("-2 hours"))
                    {
                        $roundfrom = date('Y-m-d H:i:s',strtotime($lastround->date_time. ' +1 seconds'));
                    }
                }
            }
            else
            {
                $roundfrom = $from;
            }

            if ($to == '')
            {
                $roundto = date('Y-m-d H:i:s');
            }
            else
            {
                $roundto = $to;
            }

            $start_timeStamp = strtotime($roundfrom);
            $end_timeStamp = strtotime($roundto);
            if ($end_timeStamp < $start_timeStamp)
            {
                Log::error('HDLIVE processGameRound : '. $roundto . '>' . $roundfrom);
                return [0, 0];
            }

            do
            {
                $curend_timeStamp = $start_timeStamp + 3599; // 1hours
                if ($curend_timeStamp > $end_timeStamp)
                {
                    $curend_timeStamp = $end_timeStamp;
                }
                $curPage = 0;
                $data = BETHOLDEMLIVEController::gamerounds($curPage, date('Y-m-d H:i:s', $start_timeStamp), date('Y-m-d H:i:s', $curend_timeStamp));
                if ($data == null)
                {
                    Log::error('HDLIVE gamerounds failed : '. date('Y-m-d H:i:s', $start_timeStamp) . '~' . date('Y-m-d H:i:s', $curend_timeStamp));
                    return [0,0];
                }
                
                if (isset($data['logs']) && count($data['logs']) > 0)
                {
                    foreach ($data['logs'] as $round)
                    {
                        $bet = $round['betmoney'];
                        $win = 0;
                        if($round['resultmoney'] > 0)
                        {
                            $win = $round['resultmoney'];
                        }
                        $balance = -1;

                        $roundId = 'hdlive_' . $round['id'] . '_' . $round['gameid'];
                        $game = BETHOLDEMLIVEController::getGameObj('hdlive');
                        if (!$game)
                        {
                            Log::error('HDLIVE could not find game');
                            continue;
                        }
                        
                        
                        $time = date('Y-m-d H:i:s',strtotime($round['logtime']));
                        $type = 'table';
                        $category = \VanguardLTE\Category::where('provider', self::HDLIVE_PROVIDER)->where('href', $game['href'])->where(['shop_id'=>0, 'site_id'=>0])->first();

                        $userid = intval(preg_replace('/'. self::HDLIVE_PROVIDER .'(\d+)/', '$1', $round['user']['username'])) ;
                        $shop = \VanguardLTE\ShopUser::where('user_id', $userid)->first();

                        $checkGameStat = \VanguardLTE\StatGame::where([
                            'user_id' => $userid, 
                            'bet' => $bet, 
                            'win' => $win, 
                            'date_time' => $time,
                            'roundid' => $roundId,
                        ])->first();
                        if ($checkGameStat)
                        {
                            continue;
                        }

                        \VanguardLTE\StatGame::create([
                            'user_id' => $userid, 
                            'balance' => $balance, 
                            'bet' => $bet, 
                            'win' => $win, 
                            'game' =>$game['name'] . '_hdlive', 
                            'type' => $type,
                            'percent' => 0, 
                            'percent_jps' => 0, 
                            'percent_jpg' => 0, 
                            'profit' => 0, 
                            'denomination' => 0, 
                            'date_time' => $time,
                            'shop_id' => $shop?$shop->shop_id:0,
                            'category_id' => isset($category)?$category->id:0,
                            'game_id' => $game['gamecode'],
                            'roundid' => $roundId,
                        ]);
                        $count = $count + 1;
                    }
                }
                sleep(60);
                $start_timeStamp = $curend_timeStamp;
            }while ($start_timeStamp<$end_timeStamp);
            return [$count, 0];
        }

        public static function getAgentBalance()
        {

            $balance = -1;

            $params = [
                'func' => 'GetBalanceAll',
                'mem_id' => config('app.hdlive_agent'),
                'username' => config('app.hdlive_agent'),
            ];

            $data = BETHOLDEMLIVEController::sendRequest($params);
            if ($data==null || $data['code'] != 0)
            {
                return null;
            }
            if ($data && $data['code'] == 0)
            {
                $balance = $data['agent_balance'];
            }
            return intval($balance);
        }


    }

}

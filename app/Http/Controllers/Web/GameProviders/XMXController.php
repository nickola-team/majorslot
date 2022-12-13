<?php 
namespace VanguardLTE\Http\Controllers\Web\GameProviders
{
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Log;
    class XMXController extends \VanguardLTE\Http\Controllers\Controller
    {
        /*
        * UTILITY FUNCTION
        */

        const XMX_PROVIDER = 'tp';
        const XMX_PP_HREF = 'xmx_pp';
        const XMX_GAME_IDENTITY = [
            'xmx_bbtec' => 6,
            'xmx_pp' => 8,
            'xmx_isoft' => 10,
            'xmx_star' => 11,
            'xmx_pgsoft' => 14,
            'xmx_bbin' => 15,
            'xmx_rtg' => 19,
            'xmx_mg' => 21,
            'xmx_cq9' => 23,
            'xmx_hbn' => 24,
            'xmx_playstar' => 29,
            'xmx_gameart' => 30,
            'xmx_ttg' => 32,
            'xmx_genesis' => 33,
            'xmx_tpg' => 34,
            'xmx_playson' => 36,
            'xmx_bng' => 37,
            'xmx_evoplay' => 40,
            'xmx_dreamtech' => 41,
            'xmx_ag' => 44,
            'xmx_theshow' => 47,
            'xmx_png' => 51,
        ];
        const xmx_IDENTITY_GAME = [
            6  => 'xmx_bbtec'      ,
            8  => 'xmx_pp'         ,
            10 => 'xmx_isoft'      ,
            11 => 'xmx_star'       ,
            14 => 'xmx_pgsoft'     ,
            15 => 'xmx_bbin'       ,
            19 => 'xmx_rtg'        ,
            21 => 'xmx_mg'         ,
            23 => 'xmx_cq9'        ,
            24 => 'xmx_hbn'        ,
            29 => 'xmx_playstar'   ,
            30 => 'xmx_gameart'    ,
            32 => 'xmx_ttg'        ,
            33 => 'xmx_genesis'    ,
            34 => 'xmx_tpg'        ,
            36 => 'xmx_playson'    ,
            37 => 'xmx_bng'        ,
            40 => 'xmx_evoplay'    ,
            41 => 'xmx_dreamtech'  ,
            44 => 'xmx_ag'         ,
            47 => 'xmx_theshow'    ,
            51 => 'xmx_png'        ,
        ];

        public static function getGameObj($uuid)
        {
            $gamelist = XMXController::getgamelist($uuid);
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

        public static function hashParams($params) {
            if (!$params || count($params) == 0) {
                return null;
            }
    
            $privateKey = config('xmx_key', '');
    
            $strParams = '';
            ksort($params);
            foreach ($params as $key => $value) {
                if ($key == 'hash') {
                    continue;
                }
                
                if ($value !== null) {
                    $strParams = "{$strParams}&{$key}={$value}";
                }
            }
    
            $hash = md5($privateKey . trim($strParams, "&"));
    
            $params['hash'] = $hash;
            return $params;
        }

        /*
        */

        
        /*
        * FROM CONTROLLER, API
        */

        
        public static function getuserbalance($gid, $user) {
            $url = config('app.xmx_api') . '/getAccountBalance';
            $op = config('app.xmx_op');
    
            $params = [
                'isRenew' => true,
                'operatorID' => $op,
                'thirdPartyCode' => $gid,
                'time' => time(),
                'userID' => self::XMX_PROVIDER . sprintf("%04d",$user->id),
                'vendorID' => 0,
            ];

            $params['hash'] = XMXProvider::hashParam($params);
    
            $response = Http::asForm()->post($url, $params);
            
            $balance = -1;
            if ($response->ok()) {
                $res = $response->json();
    
                if ($res['returnCode'] == 1) {
                    $balance = $res['thirdPartyBalance'];
                }
                else
                {
                    Log::error('XMXgetuserbalance : return failed. ' . $res['description']);
                }
            }
            else
            {
                Log::error('XMXgetuserbalance : response is not okay. ' . $response->body());
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
            $url = config('app.xmx_api') . '/getGameList';
            $op = config('app.xmx_op');

            $category = self::XMX_GAME_IDENTITY[$href];

            $params = [
                'operatorID' => $op,
                'thirdPartyCode' => $category,
                'time' => time(),
                'vendorID' => 0,
            ];

            $params['hash'] = XMXProvider::hashParam($params);
    
            $response = Http::asForm()->post($url, $params);
            if (!$response->ok())
            {
                return [];
            }
            $data = $response->json();
            $gameList = [];
            if ($data['resultCode'] == 0)
            {
                foreach ($data['games'] as $game)
                {
                    if (in_array($href,['tp_bng','tp_playson']) && str_contains($game['uuid'],'_mob'))
                    {
                        continue;
                    }
                    array_push($gameList, [
                        'provider' => self::XMX_PROVIDER,
                        'href' => $href,
                        'gamecode' => $game['id'],
                        'enname' => $game['tEN'],
                        'name' => preg_replace('/\s+/', '', $game['tEN']),
                        'title' => $game['tKR'],
                        'icon' => $game['img'],
                        'type' => strtolower($game['gt']),
                        'view' => 1
                    ]);
                }
            }
            \Illuminate\Support\Facades\Redis::set($href.'list', json_encode($gameList));
            return $gameList;
            
        }

        public static function makegamelink($gamecode) 
        {

            $op = config('app.xmx_op');

            //generate session
            
            $params = [
                'operatorID' => $op,
                'userID' => self::XMX_PROVIDER . sprintf("%04d",auth()->user()->id),
                'time' => time(),
                'vendorID' => 0,
            ];
            $params['hash'] = XMXProvider::hashParam($params);

            $url = config('app.xmx_api') . '/generateSession';
            $response = Http::asForm()->post($url, $params);
            if (!$response->ok())
            {
                Log::error('XMXGetLink : Game Session request failed. ' . $response->body());

                return null;
            }
            $data = $response->json();
            if ($data==null || $data['returnCode'] != 0)
            {
                Log::error('XMXGetLink : Game Session result failed. ' . ($data==null?'null':$data['description']));
                return null;
            }
            $session = $data['session'];

            //Create Game link

            $params = [
                'gameID' => $gamecode,
                'lang' => 'kr',
                'operatorID' => $op,
                'session' => $session,
                'time' => time(),
                'vendorID' => 0,
            ];
            $params['hash'] = XMXProvider::hashParam($params);

            $url = config('app.xmx_api') . '/getGameUrl';
            $response = Http::asForm()->post($url, $params);
            if (!$response->ok())
            {
                Log::error('XMXGetLink : Game url request failed. ' . $response->body());

                return null;
            }
            $data = $response->json();
            if ($data==null || $data['returnCode'] != 0)
            {
                Log::error('XMXGetLink : Game url result failed. ' . ($data==null?'null':$data['description']));
                return null;
            }
            $url = $data['gameUrl'];

            return $url;
        }
        public static function withdrawAll($gid, $user)
        {
            $balance = XMXController::getuserbalance($gid, $user);
            if ($balance < 0)
            {
                return ['error'=>true, 'amount'=>$balance, 'msg'=>'getuserbalance return -1'];
            }
            if ($balance > 0)
            {
                $op = config('app.xmx_op');

                //transferPointG2M
                $params = [
                    'amount' => $balance,
                    'operatorID' => $op,
                    'thirdPartyCode' => $gid,
                    'transactionID' => uniqid(self::XMX_PROVIDER),
                    'userID' => self::XMX_PROVIDER . sprintf("%04d",auth()->user()->id),
                    'time' => time(),
                    'vendorID' => 0,
                ];
                $params['hash'] = XMXProvider::hashParam($params);

                $url = config('app.xmx_api') . '/transferPointG2M';
                $response = Http::asForm()->post($url, $params);
                if (!$response->ok())
                {
                    Log::error('XMXWithdraw : transferPointG2M request failed. ' . $response->body());

                    return ['error'=>true, 'amount'=>0, 'msg'=>'response not ok'];
                }
                $data = $response->json();
                if ($data==null || $data['returnCode'] != 0)
                {
                    Log::error('XMXWithdraw : transferPointG2M result failed. ' . ($data==null?'null':$data['description']));
                    return ['error'=>true, 'amount'=>0, 'msg'=>'data not ok'];
                }

                //subtractMemberPoint
                $params = [
                    'amount' => $balance,
                    'operatorID' => $op,
                    'transactionID' => uniqid(self::XMX_PROVIDER),
                    'userID' => self::XMX_PROVIDER . sprintf("%04d",auth()->user()->id),
                    'time' => time(),
                    'vendorID' => 0,
                ];
                $params['hash'] = XMXProvider::hashParam($params);

                $url = config('app.xmx_api') . '/subtractMemberPoint';
                $response = Http::asForm()->post($url, $params);
                if (!$response->ok())
                {
                    Log::error('XMXWithdraw : subtractMemberPoint request failed. ' . $response->body());

                    return ['error'=>true, 'amount'=>0, 'msg'=>'response not ok'];
                }
                $data = $response->json();
                if ($data==null || $data['returnCode'] != 0)
                {
                    Log::error('XMXWithdraw : subtractMemberPoint result failed. ' . ($data==null?'null':$data['description']));
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
                Log::error('XMXMakeLink : Does not find user ' . $userid);
                return null;
            }

            $key = config('app.bnn_key');

            $balance = XMXController::getuserbalance($gamecode, $user);
            if ($balance == -1)
            {
                return null;
            }

            if ($balance != $user->balance)
            {
                //withdraw all balance
                $data = XMXController::withdrawAll($gamecode, $user);
                if ($data['error'])
                {
                    return null;
                }
                //Add balance

                if ($user->balance > 0)
                {
                    $op = config('app.xmx_op');

                    //addMemberPoint
                    $params = [
                        'amount' => $balance,
                        'operatorID' => $op,
                        'transactionID' => uniqid(self::XMX_PROVIDER),
                        'userID' => self::XMX_PROVIDER . sprintf("%04d",auth()->user()->id),
                        'time' => time(),
                        'vendorID' => 0,
                    ];
                    $params['hash'] = XMXProvider::hashParam($params);

                    $url = config('app.xmx_api') . '/addMemberPoint';
                    $response = Http::asForm()->post($url, $params);
                    if (!$response->ok())
                    {
                        Log::error('XMXmakelink : addMemberPoint request failed. ' . $response->body());

                        return null;
                    }
                    $data = $response->json();
                    if ($data==null || $data['returnCode'] != 0)
                    {
                        Log::error('XMXmakelink : addMemberPoint result failed. ' . ($data==null?'null':$data['description']));
                        return null;
                    }
                }
            }
            
            return '/followgame/xmx/'.$gamecode;

        }

        public static function getgamelink($gamecode)
        {
            $user = auth()->user();
            if ($user->playing_game != null) //already playing game.
            {
                return ['error' => true, 'data' => '이미 실행중인 게임을 종료해주세요. 이미 종료했음에도 불구하고 이 메시지가 계속 나타난다면 매장에 문의해주세요.'];
            }
            return ['error' => false, 'data' => ['url' => route('frontend.providers.waiting', [XMXController::XMX_PROVIDER, $gamecode])]];
        }

        public static function gamerounds($timepoint)
        {
            $url = config('app.bnn_api') . '/v2/history/idx';
            $key = config('app.bnn_key');
            $pageSize = 1000;
            $params = [
                'key' => $key,
                'page_size' => $pageSize,
                'game_idx' => $timepoint
            ];
            $response = null;

            try {
                $response = Http::get($url, $params);
            } catch (\Exception $e) {
                Log::error('BNNGameRounds : GameRounds request failed. ' . $e->getMessage());
                return null;
            }

            if (!$response->ok())
            {
                Log::error('BNNGameRounds : GameRounds request failed. ' . $response->body());
                return null;
            }

            $data = $response->json();
            return $data;
        }

        public static function processGameRound()
        {
            $tpoint = \VanguardLTE\Settings::where('key', 'BNNtimepoint')->first();
            if ($tpoint)
            {
                $timepoint = $tpoint->value;
            }
            else
            {
                $timepoint = 0;
            }

            //get category id
            
            $data = XMXController::gamerounds($timepoint);
            $count = 0;
            if ($data && $data['ret'] == 1 && $data['total'] > 0)
            {
                
                foreach ($data['data'] as $round)
                {
                    $bet = $round['bet_money'];
                    $win = $round['result_money'];

                    $category = \VanguardLTE\Category::where(['provider' => self::XMX_PROVIDER, 'shop_id' => 0, 'href' =>$round['gid']])->first();
                    if ($round['extra'] != null)
                    {
                        $balance = $round['extra']['after_money'];
                    }
                    else
                    {
                        $balance = -1;
                    }
                    $time = $round['game_date'];

                    $userid = preg_replace('/'. self::XMX_PROVIDER .'(\d+)/', '$1', $round['uid']) ;
                    $shop = \VanguardLTE\ShopUser::where('user_id', $userid)->first();
                    $gameName = $round['gid'];
                    \VanguardLTE\StatGame::create([
                        'user_id' => $userid, 
                        'balance' => $balance, 
                        'bet' => $bet, 
                        'win' => $win, 
                        'game' =>$gameName . '_bnn', 
                        'type' => 'table',
                        'percent' => 0, 
                        'percent_jps' => 0, 
                        'percent_jpg' => 0, 
                        'profit' => 0, 
                        'denomination' => 0, 
                        'date_time' => $time,
                        'shop_id' => $shop?$shop->shop_id:0,
                        'category_id' => isset($category)?$category->id:0,
                        'game_id' => $gameName,
                        'roundid' => $round['gubun'] . '_' . $round['game_idx'],
                    ]);
                    $timepoint =  $round['game_idx'];
                    $count = $count + 1;
                }

                $timepoint = $timepoint + 1;
                if ($tpoint)
                {
                    $tpoint->update(['value' => $timepoint]);
                    $tpoint->save();
                }
                else
                {
                    \VanguardLTE\Settings::create(['key' => 'BNNtimepoint', 'value' => $timepoint]);
                }
            }
            return [$count, $timepoint];
        }

        public static function getAgentBalance()
        {
            $url = config('app.bnn_api') . '/v1/agent-money';
            $key = config('app.bnn_key');
            $params = [
                'key' => $key,
            ];
            $response = null;

            try {
                $response = Http::get($url, $params);
                $data = $response->json();
                return $data['money_ro'];
            } catch (\Exception $e) {
                Log::error('BNNAgentMoney : request failed. ' . $e->getMessage());
                return -1;
            }
        }
    }

}

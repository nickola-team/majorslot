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
        const NEXUS_PPVERIFY_PROVIDER = 'nexusv';
        const NEXUS_PP_HREF = 'nexus-pp';
        const NEXUS_GAMEKEY = 'B';
        const NEXUS_GAME_IDENTITY = [
            //==== CASINO ====
            'nexus-evo' => ['thirdname' =>'evolution-n','type' => 'casino', 'symbol'=>'evo', 'skin'=>'B'],              //에볼루션
            'nexus-ppl' => ['thirdname' =>'pragmatic_casino','type' => 'casino', 'symbol'=>'ppl', 'skin'=>'E'],         //프라그마틱 카지노
            'nexus-dg' => ['thirdname' =>'dreamgaming_casino','type' => 'casino', 'symbol'=>'dg', 'skin'=>'B'],         //드림게이밍
            'nexus-asia' => ['thirdname' =>'ag_casino','type' => 'casino', 'symbol'=>'asia', 'skin'=>'B'],              //아시안게이밍 카지노
            'nexus-mgl' => ['thirdname' =>'mggrand_casino','type' => 'casino', 'symbol'=>'mgl', 'skin'=>'A'],           //마이크로게이밍 그랜드 카지노
            'nexus-og' => ['thirdname' =>'orientalgame_casino','type' => 'casino', 'symbol'=>'og', 'skin'=>'B'],        //오리엔탈 게이밍
            'nexus-bota' => ['thirdname' =>'bota_casino','type' => 'casino', 'symbol'=>'bota', 'skin'=>'B'],            //보타 카지노
            //new
            'nexus-bgl' => ['thirdname' =>'bg_casino','type' => 'casino', 'symbol'=>'bgl', 'skin'=>'B'],                //빅게이밍
            'nexus-motil' => ['thirdname' =>'motivation_casino','type' => 'casino', 'symbol'=>'motil', 'skin'=>'A'],    //모티베이션 카지노
            'nexus-ezugil' => ['thirdname' =>'ezugi_casino','type' => 'casino', 'symbol'=>'ezugil', 'skin'=>'A'],       //이주기
            'nexus-taishanl' => ['thirdname' =>'taishan_casino','type' => 'casino', 'symbol'=>'taishanl', 'skin'=>'A'], //taishan 카지노
            'nexus-skyl' => ['thirdname' =>'skywind_casino','type' => 'casino', 'symbol'=>'skyl', 'skin'=>'A'],         //스카이윈드 카지노
            'nexus-betl' => ['thirdname' =>'betgames_casino','type' => 'casino', 'symbol'=>'betl', 'skin'=>'A'],        //벳게임즈 TV
            'nexus-sexyl' => ['thirdname' =>'sexy_casino','type' => 'casino', 'symbol'=>'sexyl', 'skin'=>'B'],          //섹시 바카라
            'nexus-dbl' => ['thirdname' =>'dblive_casino','type' => 'casino', 'symbol'=>'dbl', 'skin'=>'B'],            //DB Live 카지노

            //==== SLOT ====
            'nexus-pp' => ['thirdname' =>'pragmatic_slot','type' => 'slot', 'symbol'=>'pp', 'skin'=>'SLOT'],            //프라그마틱 슬롯
            'nexus-mg' => ['thirdname' =>'mggrand_slot','type' => 'slot', 'symbol'=>'mg', 'skin'=>'SLOT'],              //마이크로게이밍
            'nexus-bng' => ['thirdname' =>'booongo_slot','type' => 'slot', 'symbol'=>'bng', 'skin'=>'SLOT'],            //부운고 
            'nexus-playson' => ['thirdname' =>'playson_slot','type' => 'slot', 'symbol'=>'playson', 'skin'=>'SLOT'],    //플레이손
            'nexus-playngo' => ['thirdname' =>'playngo_slot','type' => 'slot', 'symbol'=>'playngo', 'skin'=>'SLOT'],    //플레이앤고
            'nexus-hbn' => ['thirdname' =>'habanero_slot','type' => 'slot', 'symbol'=>'hbn', 'skin'=>'SLOT'],           //하바네로
            'nexus-rtg' => ['thirdname' =>'evolution_redtiger','type' => 'slot', 'symbol'=>'rtg', 'skin'=>'SLOT'],      //레드타이거
            'nexus-pgsoft' => ['thirdname' =>'pgsoft_slot','type' => 'slot', 'symbol'=>'pgsoft', 'skin'=>'SLOT'],       //PG소프트
            //new
            'nexus-ag' => ['thirdname' =>'ag_slot','type' => 'slot', 'symbol'=>'ag', 'skin'=>'SLOT'],                   //아시안게이밍 슬롯
            'nexus-bp' => ['thirdname' =>'blueprint_slot','type' => 'slot', 'symbol'=>'bp', 'skin'=>'SLOT'],            //블루프린트 슬롯
            'nexus-cq9' => ['thirdname' =>'cq9_slot','type' => 'slot', 'symbol'=>'cq9', 'skin'=>'SLOT'],                //CQ9 슬롯
            'nexus-sh' => ['thirdname' =>'spearhead_slot','type' => 'slot', 'symbol'=>'sh', 'skin'=>'SLOT'],            //슬롯 매트릭스
            'nexus-gmw' => ['thirdname' =>'gmw_slot','type' => 'slot', 'symbol'=>'gmw', 'skin'=>'SLOT'],                //GMW
            'nexus-netent' => ['thirdname' =>'netent_slot','type' => 'slot', 'symbol'=>'netent', 'skin'=>'SLOT'],       //넷엔트
            'nexus-btg' => ['thirdname' =>'btg_slot','type' => 'slot', 'symbol'=>'btg', 'skin'=>'SLOT'],                //BTG
            'nexus-aspect' => ['thirdname' =>'aspect_slot','type' => 'slot', 'symbol'=>'aspect', 'skin'=>'SLOT'],       //Aspect
            'nexus-betsoft' => ['thirdname' =>'betsoft_slot','type' => 'slot', 'symbol'=>'betsoft', 'skin'=>'SLOT'],    //Betsoft 슬롯
            'nexus-skywind' => ['thirdname' =>'skywind_slot','type' => 'slot', 'symbol'=>'skywind', 'skin'=>'SLOT'],    //스카이윈드 슬롯
            'nexus-ftg' => ['thirdname' =>'ftg_slot','type' => 'slot', 'symbol'=>'ftg', 'skin'=>'SLOT'],                //FTG 슬롯
            'nexus-netgaming' => ['thirdname' =>'netgaming','type' => 'slot', 'symbol'=>'netgaming', 'skin'=>'SLOT'],   //넷게이밍
            'nexus-ps' => ['thirdname' =>'ps_slot','type' => 'slot', 'symbol'=>'ps', 'skin'=>'SLOT'],                   //플레이스타 슬롯
            'nexus-naga' => ['thirdname' =>'naga_slot','type' => 'slot', 'symbol'=>'naga', 'skin'=>'SLOT'],             //NAGA
            'nexus-nspin' => ['thirdname' =>'nspin_slot','type' => 'slot', 'symbol'=>'nspin', 'skin'=>'SLOT'],          //Nextspin 슬롯
            'nexus-evop' => ['thirdname' =>'evo_slot','type' => 'slot', 'symbol'=>'evop', 'skin'=>'SLOT'],              //에보플레이 슬롯
            'nexus-aux' => ['thirdname' =>'aux_slot','type' => 'slot', 'symbol'=>'aux', 'skin'=>'SLOT'],                //아바타UX 슬롯
            'nexus-relax' => ['thirdname' =>'relax_slot','type' => 'slot', 'symbol'=>'relax', 'skin'=>'SLOT'],          //릴렉스
            'nexus-reelp' => ['thirdname' =>'reelplay_slot','type' => 'slot', 'symbol'=>'reelp', 'skin'=>'SLOT'],       //릴플레이 슬롯
            'nexus-fant' => ['thirdname' =>'fantasma_slot','type' => 'slot', 'symbol'=>'fant', 'skin'=>'SLOT'],         //판타즈마 슬롯
            'nexus-boome' => ['thirdname' =>'boomerang_slot','type' => 'slot', 'symbol'=>'boome', 'skin'=>'SLOT'],      //부메랑 슬롯
            'nexus-4tp' => ['thirdname' =>'4tp_slot','type' => 'slot', 'symbol'=>'4tp', 'skin'=>'SLOT'],                //포더플레이어 슬롯
            'nexus-nlc' => ['thirdname' =>'nlc_slot','type' => 'slot', 'symbol'=>'nlc', 'skin'=>'SLOT'],                //노리밋시티 슬롯
            'nexus-hs' => ['thirdname' =>'hs_slot','type' => 'slot', 'symbol'=>'hs', 'skin'=>'SLOT'],                   //핵쏘우게이밍 슬롯
            'nexus-joker' => ['thirdname' =>'joker_slot','type' => 'slot', 'symbol'=>'joker', 'skin'=>'SLOT'],          //조커 슬롯
            'nexus-jili' => ['thirdname' =>'jili_slot','type' => 'slot', 'symbol'=>'jili', 'skin'=>'SLOT'],             //JILI 슬롯
            'nexus-funky' => ['thirdname' =>'funkygames_slot','type' => 'slot', 'symbol'=>'funky', 'skin'=>'SLOT'],     //펀키게임즈 슬롯
            'nexus-bgaming' => ['thirdname' =>'bgaming_slot','type' => 'slot', 'symbol'=>'bgaming', 'skin'=>'SLOT'],    //비게이밍 슬롯
            'nexus-booming' => ['thirdname' =>'booming_slot','type' => 'slot', 'symbol'=>'booming', 'skin'=>'SLOT'],    //부밍게임즈 슬롯
            'nexus-expanse' => ['thirdname' =>'expanse_slot','type' => 'slot', 'symbol'=>'expanse', 'skin'=>'SLOT'],    //익스팬스 슬롯
            'nexus-ygr' => ['thirdname' =>'ygr_slot','type' => 'slot', 'symbol'=>'ygr', 'skin'=>'SLOT'],                //YGR
            'nexus-dragon' => ['thirdname' =>'dragoonsoft_slot','type' => 'slot', 'symbol'=>'dragon', 'skin'=>'SLOT'],  //드래곤소프트
            'nexus-wazdan' => ['thirdname' =>'wazdan_slot','type' => 'slot', 'symbol'=>'wazdan', 'skin'=>'SLOT'],       //와즈단
            'nexus-octo' => ['thirdname' =>'octoplay_slot','type' => 'slot', 'symbol'=>'octo', 'skin'=>'SLOT'],         //옥토플레이
            'nexus-ygg' => ['thirdname' =>'ygg_slot','type' => 'slot', 'symbol'=>'ygg', 'skin'=>'SLOT'],                //이그드라실
            'nexus-novo' => ['thirdname' =>'novomatic_slot','type' => 'slot', 'symbol'=>'novo', 'skin'=>'SLOT'],        //노보메틱
            'nexus-bt1' => ['thirdname' =>'bt1_sports','type' => 'sports', 'symbol'=>'bt1', 'skin'=>'SPORTS'],          //BT1
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
                        if ($game['gameid'] == $uuid)
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
        
        public static function getUserBalance($href, $user, $prefix=self::NEXUS_PROVIDER) {   

            $balance = -1;
            $user_code = $prefix  . sprintf("%04d",$user->id);
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
            
            $skin = $category['skin'];
            $vendorKey = $category['thirdname'];
            $params = [
                'vendorKey' => $vendorKey,
                'skin' => $skin,
            ];

            $gameList = [];
            $data = NEXUSController::sendRequest('/games', $params);
            $view = 1;
            if($category['type'] == 'casino'){
                $view = 0;
            }
            if ($data && $data['code'] == 0)
            {
                foreach ($data['games'] as $game)
                {
                    $gamename = 'Unknown';
                    $gametitle = 'Unknown';
                    if(isset($game['names']))
                    {
                        $gametitle = isset($game['names']['ko'])?$game['names']['ko']:$game['names']['en'];
                        $gamename = $game['names']['en'];
                    }
                    $icon = '';
                    if(isset($game['image']))
                    {
                        $icon = $game['image'];
                    }
                    array_push($gameList, [
                        'provider' => self::NEXUS_PROVIDER,
                        'vendorKey' => $vendorKey, 
                        'href' => $href,
                        'gameid' => $game['id'],
                        'gamecode' => $vendorKey.'_'.$game['key'],
                        'symbol' => $game['key'],
                        'name' => preg_replace('/\s+/', '', $gamename),
                        'title' => $gametitle,
                        'type' => ($category['type']=='slot')?'slot':'table',
                        'skin' => $skin,
                        'icon' => $icon,
                        'view' => $view
                    ]);
                }
                //add casino lobby
                if($category['type'] == 'casino'){
                    array_push($gameList, [
                        'provider' => self::NEXUS_PROVIDER,
                        'vendorKey' => $vendorKey, 
                        'gameid' => $category['symbol'],
                        'href' => $href,
                        'gamecode' => $vendorKey,
                        'symbol' => $skin,
                        'name' => 'Lobby',
                        'title' => 'Lobby',
                        'skin' => $skin,
                        'type' => 'table',
                        'icon' => '/frontend/Default/ico/gold/EVOLUTION_Lobby.jpg',
                        'view' => 1
                    ]);
                }
            }

            //add Unknown Game item
            array_push($gameList, [
                'provider' => self::NEXUS_PROVIDER,
                'vendorKey' => $vendorKey, 
                'href' => $href,
                'gameid' => 'Unknown',
                'symbol' => 'Unknown',
                'gamecode' => 'Unknown',
                'enname' => 'UnknownGame',
                'name' => 'UnknownGame',
                'title' => 'UnknownGame',
                'skin' => $skin,
                'icon' => '',
                'type' => ($category['type']=='slot')?'slot':'table',
                'view' => 0
            ]);
            \Illuminate\Support\Facades\Redis::set($href.'list', json_encode($gameList));
            return $gameList;
            
        }

        public static function makegamelink($gamecode,  $prefix=self::NEXUS_PROVIDER) 
        {
            $game = NEXUSController::getGameObj($gamecode);
            if (!$game)
            {
                return null;
            }
            $user = auth()->user();
            if ($user == null)
            {
                return null;
            }
            $user_code = $prefix  . sprintf("%04d",$user->id);
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
            $params = [
                'vendorKey' => $game['vendorKey'],
                'gameKey' => $game['symbol'],
                'siteUsername' => $user_code,
                'ip' => $user->last_name ?? '',
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
        
        public static function withdrawAll($href, $user, $prefix=self::NEXUS_PROVIDER)
        {
            $balance = NEXUSController::getuserbalance($href,$user,$prefix);
            if ($balance < 0)
            {
                return ['error'=>true, 'amount'=>$balance, 'msg'=>'getuserbalance return -1'];
            }
            if ($balance > 0)
            {
                $params = [
                    'username' => $prefix  . sprintf("%04d",$user->id),
                    'siteUsername' => $prefix  . sprintf("%04d",$user->id),
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
            $url = NEXUSController::makegamelink($gamecode);
            if ($url)
            {
                return ['error' => false, 'data' => ['url' => $url]];
            }
            return ['error' => true, 'msg' => '게임실행 오류입니다'];
        }

        public static function gamerounds($fromtimepoint, $totimepoint)
        {
            $sdate = date('Y-m-d H:i:s', $fromtimepoint);
            $edate = date('Y-m-d H:i:s', $totimepoint);
            $params = [
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
                    if($round['vendorKey'] == 'dreamgaming_casino')
                    {
                        $gameObj = NEXUSController::getGameObj($round['vendorKey']);
                        if (!$gameObj)
                        {
                            Log::error('NEXUS Game could not found : '. $round['gameId']);
                            continue;
                        }
                    }
                    else
                    {
                        $gameObj = NEXUSController::getGameObj($round['gameId']);
                        
                        if (!$gameObj)
                        {
                            $gameObj = NEXUSController::getGameObj($round['vendorKey']);
                            if (!$gameObj)
                            {
                                Log::error('NEXUS Game could not found : '. $round['gameId']);
                                continue;
                            }
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
                        'type' => $gameObj['type'],
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
                'transactionKey' => $transactionKey,
                'lang' => 'ko'
            ];

            $data = NEXUSController::sendRequest('/getdetailurl', $params);
            if ($data==null || $data['code'] != 0)
            {
                return null;
            }
            else
            {
                return $data['url'];
            }
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
        public static function syncpromo()
        {
            $user = \VanguardLTE\User::where('role_id', 1)->whereNull('playing_game')->first();           
            if (!$user)
            {
                return ['error' => true, 'msg' => 'not found any available user.'];
            }
            $gamelist = NEXUSController::getgamelist(self::NEXUS_PP_HREF);
            $len = count($gamelist);
            if ($len > 10) {$len = 10;}
            if ($len == 0)
            {
                return ['error' => true, 'msg' => 'not found any available game.'];
            }
            $rand = mt_rand(0,$len);
                
            $symbol = $gamelist[$rand]['symbol'];
            $gamecode = $gamelist[$rand]['gamecode'];
            //유저정보 조회
            $user_code = self::NEXUS_PPVERIFY_PROVIDER  . sprintf("%04d",$user->id);
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

            $url = NEXUSController::makegamelink($gamecode, $user, self::NEXUS_PPVERIFY_PROVIDER);
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

                $response =  Http::withOptions(['proxy' => config('app.ppproxy')])->get($ppgameserver . '/ClientAPI/minilobby/common/games?' . $mgckey );
                if ($response->ok())
                {
                    $json_data = $response->json();
                    //disable not own games
                    $ownCats = \VanguardLTE\Category::where(['href'=> 'pragmatic', 'shop_id'=>0,'site_id'=>0])->first();
                    $gIds = $ownCats->games->pluck('game_id')->toArray();
                    $ownGames = \VanguardLTE\Game::whereIn('id', $gIds)->where('view',1)->get();
                    $multiLobby = 0;
                    if(isset($json_data['lobbyCategories']) || isset($json_data['gameLaunchURL']))
                    {
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
                    }
                    else if(isset($json_data['data']) && count($json_data['data']) > 0)
                    {
                        $multiLobby = 1;
                        $multi_json = $json_data['data'][0];
                        if(isset($multi_json['vendorConfig']) && isset($multi_json['vendorConfig']['gameLaunchURL']))
                        {
                            $multi_json['vendorConfig']['gameLaunchURL'] = "/gs2c/minilobby/start";
                            $json_data['data'][0] = $multi_json;
                        }
                    }   
                    $promo->games = json_encode($json_data);
                    $promo->multiminilobby = $multiLobby;
                }

                $promo->save();
                return ['error' => false, 'msg' => 'synchronized successfully.'];
            }
            else
            {
                return ['error' => true, 'msg' => 'server response is not 302.'];
            }          
            
        }
        public function ppverify($gamecode, \Illuminate\Http\Request $request){
            set_time_limit(0);
            $user = \Auth()->user();
            if($user == null){
                $this->ppverifyLog($gamecode, '', 'There is no user');
                return response()->json(['error'=>true, 'mgckey'=>'', 'rid'=>'', 'bet'=>'', 'verifyurl'=>'']); 
            }
            $user_code = self::NEXUS_PPVERIFY_PROVIDER  . sprintf("%04d",$user->id);
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
                    $this->ppverifyLog($gamecode, '', 'There is no user');
                    return response()->json(['error'=>true, 'mgckey'=>'', 'rid'=>'', 'bet'=>'', 'verifyurl'=>'']);
                }
            }
            else
            {
                $balance = $data['balance'];
            }
            
            //프라그마틱 인증부분 설정되어있는지 확인
            $is_ppverify = false;
            $parent = $user;
            while ($parent && !$parent->isInOutPartner())
            {
                $parent = $parent->referral;
            }
            if(isset($parent))
            {
                if(isset($parent->sessiondata()['ppverifyOn']) && $parent->sessiondata()['ppverifyOn']==1)
                {
                    $is_ppverify = true;
                }
            }
            
            // 유저마지막 베팅로그 얻기
            $verify_log = \VanguardLTE\PPGameVerifyLog::where(['user_id'=>$user->id, 'label'=>$gamecode])->first();
            $pm_category = \VanguardLTE\Category::where('href', 'pragmatic')->first();
            $cat_id = $pm_category->original_id;

            if ($balance != $user->balance)
            {
                //withdraw all balance
                if($balance > 0)
                {
                    $data = NEXUSController::withdrawAll(NEXUSController::NEXUS_PP_HREF, $user , NEXUSController::NEXUS_PPVERIFY_PROVIDER);
                    if ($data['error'])
                    {
                        $this->ppverifyLog($gamecode, $user->id, 'withdrawAll error');
                        return response()->json(['error'=>true, 'mgckey'=>'', 'rid'=>'', 'bet'=>'', 'verifyurl'=>'']);
                    }
                }
                if(isset($verify_log) && $verify_log->crid == '' && $is_ppverify == true)
                {
                    //Add balance
                    $adduserbalance = $verify_log->bet;   // 유저머니를 베트머니만큼 충전한다.
                    if ($adduserbalance > 1)
                    {
                        $params = [
                            'username' => $user_code,
                            'siteUsername' => $user_code,
                            "amount" =>  intval($adduserbalance),
                            "requestKey" => $user->generateCode(24)
                        ];
        
                        $data = NEXUSController::sendRequest('/deposit', $params);
                        if ($data==null || $data['code'] != 0)
                        {
                            $this->ppverifyLog($gamecode, $user->id, 'add-balance exception=' . $ex->getMessage() . ', PARAM=' . json_encode($params));
                            return response()->json(['error'=>true, 'mgckey'=>'', 'rid'=>'', 'bet'=>'', 'verifyurl'=>'']); 
                        }
                    }
                }
            }
            ///////////////////////<--- End --->/////////////////////////
            $category = NEXUSController::NEXUS_GAME_IDENTITY[NEXUSController::NEXUS_PP_HREF];
            $url = NEXUSController::makegamelink($category['thirdname'].'_'.$gamecode, $user, self::NEXUS_PPVERIFY_PROVIDER);
            if ($url == null)
            {
                $this->ppverifyLog($gamecode, $user->id, 'make game link error');
                return response()->json(['error'=>true, 'mgckey'=>'', 'rid'=>'', 'bet'=>'', 'verifyurl'=>'']); 
            }

            //emulate client
            $response = Http::withOptions(['allow_redirects' => false,'proxy' => config('app.ppproxy')])->get($url);
            // if($response->status() == 302){
            //     $url = $response->header('location');
            //     $response = Http::withOptions(['allow_redirects' => false,'proxy' => config('app.ppproxy')])->get($url);
            // }
        
            if ($response->status() == 302)
            {
                $location = $response->header('location');
                $response =  Http::withOptions(['proxy' => config('app.ppproxy')])->get($location);
                $parse = parse_url($url);
                $ppgameserver = $parse['scheme'] . '://' . $parse['host'];
                $datapath = $ppgameserver . '/gs2c/common/games-html5/games/vs/'. $gamecode . '/';
                $gameservice = $ppgameserver. '/gs2c/ge/v3/gameService';
                $verifyurl = $ppgameserver. '/gs2c/session/verify';
                if ($response->ok())
                {
                    $content = $response->body();
                    preg_match("/\"datapath\":\"([^\"]*)/", $content, $match);
                    if(!empty($match) && isset($match[1]) && !empty($match[1])){
                        $datapath = $match[1];
                    }
                    preg_match("/\"gameService\":\"([^\"]*)/", $content, $match);
                    if(!empty($match) && isset($match[1]) && !empty($match[1])){
                        $gameservice = $match[1];
                    }
                    preg_match("/\"gameVerificationURL\":\"([^\"]*)/", $content, $match);
                    if(!empty($match) && isset($match[1]) && !empty($match[1])){
                        $verifyurl = $match[1];
                    }
                }
                $keys = explode('&', $location);
                $mgckey = null;
                foreach ($keys as $key){
                    if (str_contains( $key, 'mgckey='))
                    {
                        $mgckey = $key;
                    }
                }
                if (!$mgckey){
                    $this->ppverifyLog($gamecode, $user->id, 'could not find mgckey value');
                    return response()->json(['error'=>true, 'mgckey'=>'', 'rid'=>'', 'bet'=>'', 'verifyurl'=>$verifyurl]); 
                }
                // if($verify_log != null)
                // {
                //     return response()->json(['error'=>false, 'mgckey'=>explode('=', $mgckey)[1], 'rid'=>$verify_log->rid, 'bet'=>$verify_log->bet, 'verifyurl'=>$verifyurl]); 
                // }
                $arr_b_ind_games = ['vs243lionsgold', 'vs10amm', 'vs10egypt', 'vs25asgard', 'vs9aztecgemsdx', 'vs10tut', 'vs243caishien', 'vs243ckemp', 'vs25davinci', 'vs15diamond', 'vs7fire88', 'vs20leprexmas', 'vs20leprechaun', 'vs25mustang', 'vs20santa', 'vs20pistols', 'vs25holiday', 'vs10bbextreme', 'vs243goldfor','vs25lagoon','vswayskrakenmw','vs10bbfmission'];
                $arr_b_no_ind_games = ['vs7776secrets', 'vs10txbigbass', 'vs20terrorv', 'vs20drgbless', 'vs5drhs', 'vs20ekingrr', 'vswaysxjuicy', 'vs10goldfish','vs10floatdrg', 'vswaysfltdrg', 'vs20hercpeg', 'vs20honey', 'vs20hburnhs', 'vs4096magician', 'vs9chen', 'vs243mwarrior', 'vs20muertos', 'vs20mammoth', 'vs25peking', 'vswayshammthor', 'vswayslofhero', 'vswaysfrywld', 'vswaysluckyfish', 'vs10egrich', 'vs25rlbank', 'vs40streetracer', 'vs5spjoker', 'vs20superx', 'vs1024temuj', 'vs20doghouse', 'vs20tweethouse', 'vs20amuleteg', 'vs40madwheel', 'vs5trdragons', 'vs10vampwolf', 'vs20vegasmagic', 'vswaysyumyum', 'vs10jnmntzma','vs10kingofdth', 'vswaysrhino', 'vs20xmascarol', 'vswaysaztecking','vswaysrockblst', 'vs20maskgame','vswayscfglory','vs10bhallbnza2', 'vs10bbsplxmas','vs20dhdice','vswaysfltdrgny','vs10bblotgl','vswaysloki','vs20bblitz','vs20jhunter','vs10dgold88', 'vs25xmasparty', 'vs10fdsnake'];
                $arr_b_gamble_games = ['vs20underground', 'vswayspowzeus', 'vswaysanime', 'vs40pirgold', 'vs40voodoo', 'vswayswwriches'];

                $cver = 99951;
                $response =  Http::withOptions(['proxy' => config('app.ppproxy')])->get($datapath .'desktop/bootstrap.js');
                if ($response->ok())
                {
                    $content = $response->body();
                    preg_match("/UHT_REVISION={common:'(\d+)',desktop:'\d+',mobile/", $content, $match);
                    if(!empty($match) && isset($match[1]) && !empty($match[1])){
                        $cver = $match[1];
                    }
                }
                $data = [
                    'action' => 'doInit',
                    'symbol' => $gamecode,
                    'cver' => $cver,
                    'index' => 1,
                    'counter' => 1,
                    'repeat' => 0,
                    'mgckey' => explode('=', $mgckey)[1]
                ];
                $v_type = 'v3';
                $response = Http::withOptions(['proxy' => config('app.ppproxy')])->withHeaders([
                    'Content-Type' => 'application/x-www-form-urlencoded'
                    ])->asForm()->post($gameservice, $data);
                if (!$response->ok())
                {
                    $this->ppverifyLog($gamecode, $user->id, 'doInit request error');
                    return response()->json(['error'=>true, 'mgckey'=>explode('=', $mgckey)[1], 'rid'=>'', 'bet'=>'', 'verifyurl'=>$verifyurl]); 
                }
                $result = PPController::toJson($response->body());
                $line = (int)$result['l'] ?? 20;
                $bet = (float)$result['c'] ?? 50;
                $arr_bets = isset( $result['sc']) ? explode(',', $result['sc']) : [];
                $bl = $result['bl'] ?? -1;
                $spinType = $result['na'] ?? 's';
                $rs_p = $result['rs_p'] ?? -1;
                $fsmax = $result['fsmax'] ?? -1;
                $index = $result['index'] ?? 1;
                $counter = $result['counter'] ?? 2;
                if(isset($verify_log) && $verify_log->crid != '')
                {
                    $rid = $verify_log->crid;
                }
                else
                {
                    $rid = '0';
                }
                $allbet = $line * $bet;
                if($is_ppverify == false)
                {
                    $allbet = $line * ($result['defc'] ?? $bet);
                }
                if(isset($verify_log) && $verify_log->crid == '' && $is_ppverify == true){
                    $bw = -1;
                    $end = -1;
                    $bgt = -1;
                    $ind = 0;
                    $isRespin = false;
                    $allbet = $verify_log->bet;
                    $pur_ind = -1;
                    $game = \VanguardLTE\Game::where('original_id', $verify_log->game_id)->where('shop_id', $user->shop_id)->first();
                    if(!isset($game)){
                        return response()->json(['error'=>false, 'mgckey'=>explode('=', $mgckey)[1], 'rid'=>$rid, 'bet'=>$allbet, 'verifyurl'=>$verifyurl]); 
                    }
                    $ppgamelog = \VanguardLTE\PPGameLog::where(['user_id' => $user->id, 'game_id'=>$game->id, 'roundid' => $verify_log->rid])->orderby('id', 'asc')->first();
                    if(!isset($ppgamelog)){
                        return response()->json(['error'=>false, 'mgckey'=>explode('=', $mgckey)[1], 'rid'=>$rid, 'bet'=>$allbet, 'verifyurl'=>$verifyurl]); 
                    }
                    $ppgamelog = json_decode($ppgamelog->str);
                    $pur = -1;
                    if(isset($ppgamelog->request) && isset($ppgamelog->request->pur) && $ppgamelog->request->pur >= 0){
                        $pur = $ppgamelog->request->pur;
                    }
                    if(isset($ppgamelog->request) && isset($ppgamelog->request->bl)){
                        $bl = $ppgamelog->request->bl;
                    }
                    if(isset($ppgamelog->request) && isset($ppgamelog->request->c)){
                        $bet = $ppgamelog->request->c;
                    }
                    if(isset($ppgamelog->request) && isset($ppgamelog->request->l)){
                        $line = $ppgamelog->request->l;
                    }
                    if(isset($ppgamelog->request) && isset($ppgamelog->request->ind)){
                        $pur_ind = $ppgamelog->request->ind;
                    }
                    while(true){
                        try
                        {
                            $data = [
                                'action' => 'doSpin',
                                'symbol' => $gamecode,
                                'index' => $index + 1,
                                'counter' => $counter + 1,
                                'repeat' => 0,
                                'mgckey' => explode('=', $mgckey)[1]
                            ];
                            if($spinType != 'b'){
                                $isRespin = false;
                            }
                            if($spinType == 's'){
                                $data['c'] = $bet;
                                $data['l'] = $line;
                                if($pur_ind >= 0){
                                    $data['ind'] = $pur_ind;
                                    if($gamecode != 'vs15godsofwar'){
                                        $pur_ind = -1;
                                    }
                                }
                            }else if($spinType == 'c'){
                                $data['action'] = 'doCollect';
                            }else if($spinType == 'cb'){
                                $data['action'] = 'doCollectBonus';
                            }else if($spinType == 'fso'){
                                $data['action'] = 'doFSOption';
                                $data['ind'] = 0;
                            }else if($spinType == 'm'){
                                $data['action'] = 'doMysteryScatter';
                                if($gamecode == 'vs10bookfallen'){
                                    $data['ind'] = 0;
                                }
                            }else if($spinType == 'go'){
                                $data['action'] = 'doGambleOption';
                                $data['g_a'] = 'stand';
                                $data['g_o_ind'] = -1;
                            }else if($spinType == 'b'){
                                $data['action'] = 'doBonus';
                                if($isRespin == false){
                                    $isRespin = true;
                                    $ind = 0;
                                }else{
                                    $ind++;
                                }
                                if($gamecode == 'vs7pigs'){
                                    $arr_i_pos = isset($result['i_pos']) ? explode(',', $result['i_pos']) : [1,1,1];
                                    $data['ind'] = $arr_i_pos[$ind];
                                }else if($gamecode == 'vs243queenie'){
                                    if($bw != 1){
                                        $data['ind'] = isset($result['wi']) ? ($result['wi'] + 1) : 1;
                                    }
                                }else if(in_array($gamecode, $arr_b_gamble_games)){
                                    $data['ind'] = 1;
                                }else if(in_array($gamecode, $arr_b_no_ind_games)){
                                    $data['ind'] = 0;
                                }else if(in_array($gamecode, $arr_b_ind_games)){
                                    if($bgt == 9 || $bgt == 23){
                                        $data['ind'] = 0;
                                    }else if($bgt == 11 || $bgt == 31){
                                        
                                    }else if($bgt == 21){
                                        if($end == 0){
                                            $data['ind'] = 0;
                                        }
                                    }else{
                                        $data['ind'] = $ind;
                                    }
                                }
                            }
                            if($bl >= 0){
                                $data['bl'] = $bl;
                            }
                            if($pur >= 0){
                                $data['pur'] = $pur;
                            }
                            $response = Http::withOptions(['proxy' => config('app.ppproxy')])->withHeaders([
                                'Accept' => '*/*',
                                'Content-Type' => 'application/x-www-form-urlencoded'
                                ])->asForm()->post($gameservice, $data);
                            if (!$response->ok())
                            {
                                $this->ppverifyLog($gamecode, $user->id, 'doSpin Error, Body => ' . $response->body());
                                if(in_array($bet, $arr_bets) == false){
                                    $this->ppverifyLog($gamecode, $user->id, 'doSpin BetMoney =' . $bet . ', BetList=' . json_encode($arr_bets));
                                }
                                break;
                            }
                            $result = PPController::toJson($response->body());
                            if(count($result) == 0){
                                $this->ppverifyLog($gamecode, $user->id, 'doSpin Error Data=' . json_encode($data));
                                break;
                            }
                            $spinType = $result['na'] ?? 's';
                            $rs_p = $result['rs_p'] ?? -1;
                            $fsmax = $result['fsmax'] ?? -1;
                            $index = $result['index'] ?? 1;
                            $counter = $result['counter'] ?? 2;
                            $bw = $result['bw'] ?? -1;
                            $end = $result['end'] ?? -1;
                            $bgt = $result['bgt'] ?? -1;
                            if(isset($result['rid']))
                            {
                                $rid = $result['rid'];
                            }
                            $pur = -1;
                            if($spinType == 's' && $rs_p == -1 && $fsmax == -1){
                                break;
                            }
                            
                        }
                        catch (\Exception $ex)
                        {
                            $this->ppverifyLog($gamecode, $user->id, 'doSpin Error Exception=' . $ex->getMessage() . ', Data=' . json_encode($data));
                            break;
                        }
                    }
                }
                if(isset($verify_log) && $rid != '0' && $verify_log->crid == ''){  
                    $verify_log->crid = $rid;
                    $verify_log->save();
                }
                return response()->json(['error'=>false, 'mgckey'=>explode('=', $mgckey)[1], 'rid'=>$rid, 'bet'=>$allbet, 'verifyurl'=>$verifyurl]); 
            }else{
                Log::error('server response is not 302.');
                return response()->json(['error'=>true, 'mgckey'=>'', 'rid'=>'', 'bet'=>'', 'verifyurl'=>'']); 
            }
        }
        public function ppverifyLog($gamecode, $user_id, $msg)
        {
            $strlog = '';
            $strlog .= "\n";
            $strlog .= date("Y-m-d H:i:s") . ' ';
            $strlog .= $user_id . '->' . $gamecode . ' : ' . $msg;
            $strlog .= "\n";
            $strlog .= ' ############################################### ';
            $strlog .= "\n";
            $strinternallog = '';
            if( file_exists(storage_path('logs/') . 'PPVerify.log') ) 
            {
                $strinternallog = file_get_contents(storage_path('logs/') . 'PPVerify.log');
            }
            file_put_contents(storage_path('logs/') . 'PPVerify.log', $strinternallog . $strlog);
        }

        // callback for seamless
        public function balance(\Illuminate\Http\Request $request)
        {
            $data = json_decode($request->getContent(), true);
            if(!isset($data['apiKey']) || $data['apiKey'] != config('app.nexus_secretkey'))
            {
                Log::error('Nexus CallBack Balance : Invalid ApiKey. PARAMS= ' . json_encode($data));
                return response()->json([
                    "code" => 1,               // 0: 정상, -1: 오류 메시지 확인
                    "msg" => 'Invalid ApiKey'  
                ]);
            }
            if(!isset($data['params']) || !isset($data['params']['siteUsername']))
            {
                Log::error('Nexus CallBack Balance : No params. PARAMS= ' . json_encode($data));
                return response()->json([
                    "code" => 1,               // 0: 정상, -1: 오류 메시지 확인
                    "msg" => 'No params'  
                ]);
            }
            $userid = intval(preg_replace('/'. self::NEXUS_PROVIDER .'(\d+)/', '$1', $data['params']['siteUsername'])) ;
            $user = \VanguardLTE\User::where(['id'=> $userid, 'role_id' => 1])->first();
            if (!$user)
            {
                Log::error('Nexus CallBack Balance : Not found user. PARAMS= ' . json_encode($data));
                return response()->json([
                    "code" => 1,               // 0: 정상, -1: 오류 메시지 확인
                    "msg" => 'Not found user'  
                ]);
            }
            if($user->api_token == 'playerterminate')
            {
                Log::error('Nexus CallBack Balance : terminated by admin. PARAMS= ' . json_encode($data));
                return response()->json([
                    "code" => 1,               // 0: 정상, -1: 오류 메시지 확인
                    "msg" => 'terminated by admin'  
                ]);
            }
            return response()->json([
                "code" => 0,               // 0: 정상, -1: 오류 메시지 확인
                "data" => [
                    "balance" => intval($user->balance),       // 보유 금액 (Number)
                    "currency" => 'KRW',    // 화폐 (String)
                ]
            ]);
        }
        public function betPlace(\Illuminate\Http\Request $request)
        {
            return NEXUSController::addGameRound('BetPlace', json_decode($request->getContent(), true));
        }
        public function betResult(\Illuminate\Http\Request $request)
        {
            return NEXUSController::addGameRound('Result', json_decode($request->getContent(), true));
        }
        public function betCancel(\Illuminate\Http\Request $request)
        {
            return NEXUSController::addGameRound('Cancel', json_decode($request->getContent(), true));
        }
        public function betAdjust(\Illuminate\Http\Request $request)
        {
            return NEXUSController::addGameRound('Adjust', json_decode($request->getContent(), true));
        }
        public static function addGameRound($callbackType, $data)
        {
            // Log::error('---- Nexus CallBack '. $callbackType .' : Request PARAMS= ' . json_encode($data));
            if(!isset($data['apiKey']) || $data['apiKey'] != config('app.nexus_secretkey'))
            {
                Log::error('Nexus CallBack '. $callbackType .' : Invalid ApiKey. PARAMS= ' . json_encode($data));
                return response()->json([
                    "code" => 1,               // 0: 정상, -1: 오류 메시지 확인
                    "msg" => 'Invalid ApiKey'  
                ]);
            }
            if(!isset($data['params']) || !isset($data['params']['siteUsername']))
            {
                Log::error('Nexus CallBack '. $callbackType .' : No params. PARAMS= ' . json_encode($data));
                return response()->json([
                    "code" => 1,               // 0: 정상, -1: 오류 메시지 확인
                    "msg" => 'No params'  
                ]);
            }
            $round = $data['params'];
            $userId = intval(preg_replace('/'. self::NEXUS_PROVIDER  .'(\d+)/', '$1', $round['siteUsername'])) ;
            if($userId == 0){
                $userId = intval(preg_replace('/'. self::NEXUS_PROVIDER . 'user' .'(\d+)/', '$1', $round['siteUsername'])) ;
            }
            if($round['vendorKey'] == 'dreamgaming_casino')
            {
                $gameObj = NEXUSController::getGameObj($round['vendorKey']);
                if (!$gameObj)
                {
                    Log::error('Nexus CallBack '. $callbackType .' : Game could not found. PARAMS= ' . json_encode($data));
                    return response()->json([
                        "code" => 1,               // 0: 정상, -1: 오류 메시지 확인
                        "msg" => 'Game could not found'  
                    ]);
                }
            }
            else
            {
                $gameObj = NEXUSController::getGameObj($round['gameId']);
                
                if (!$gameObj)
                {
                    $gameObj = NEXUSController::getGameObj($round['vendorKey']);
                    if (!$gameObj)
                    {
                        Log::error('Nexus CallBack '. $callbackType .' : Game could not found. PARAMS= ' . json_encode($data));
                        return response()->json([
                            "code" => 1,               // 0: 정상, -1: 오류 메시지 확인
                            "msg" => 'Game could not found'  
                        ]);
                    }
                }
            }
            $bet = 0;
            $win = 0;
            $type = 'bet';
            $transactionKey = $round['transactionKey'];
            if($round['type'] == 'turn_bet')
            {
                $bet = $round['amount'];
            }
            else if($round['type'] == 'turn_win' || $round['type'] == 'turn_draw' || $round['type'] == 'turn_lose' || $round['type'] == 'turn_out' || $round['type'] == 'turn_adjust')
            {
                $win = $round['amount'];
                if(isset($round['updepositCash']) && $round['updepositCash'] > 0)
                {
                    $win -= $round['updepositCash'];
                }
                $type = 'win';
                $transactionKey = $round['parentTransactionKey'];
            }
            else if($round['type'] == 'turn_cancel')
            {
                $win = $round['amount'];
                $type = 'cancel';
                $transactionKey = $round['parentTransactionKey'];
            }
            
            $time = date('Y-m-d H:i:s',strtotime($round['createdAt']));

            

            $shop = \VanguardLTE\ShopUser::where('user_id', $userId)->first();
            $category = \VanguardLTE\Category::where('href', $gameObj['href'])->first();

            // if ($checkduplicate)
            // {
            $checkGameStat = \VanguardLTE\StatGame::where([
                'user_id' => $userId, 
                'bet_type' => $type, 
                'date_time' => $time,
                'roundid' => $round['vendorKey'] . '#' . $round['gameId'] . '#' .  $transactionKey,
            ])->first();
            if ($checkGameStat)
            {
                if($round['type'] == 'turn_adjust')
                {
                    $checkGameStat->update(['win' => $win]);
                }
                Log::error('Nexus CallBack '. $callbackType .' : Exist Transaction. PARAMS= ' . json_encode($data));
                return response()->json([
                    "code" => 1,               // 0: 정상, -1: 오류 메시지 확인
                    "msg" => 'Exist Transaction'  
                ]);
            }
            \DB::beginTransaction();
            $user = \VanguardLTE\User::lockforUpdate()->where(['id'=> $userId, 'role_id' => 1])->first();
            if (!$user)
            {
                Log::error('Nexus CallBack '. $callbackType .' : Not found User. PARAMS= ' . json_encode($data));
                \DB::commit();
                return response()->json([
                    "code" => 0,               // 0: 정상, -1: 오류 메시지 확인
                    "msg" => 'Not found User'
                ]);
            }
            $beforeBalance = $user->balance;
            if($type == 'bet')
            {
                $user->balance = $user->balance - intval($bet);
            }
            else
            {
                $user->balance = $user->balance + intval($win);
            }
            $user->save();
            // }
            $gamename = $gameObj['name'] . '_nexus';
            if($round['type'] == 'turn_cancel')  // 취소처리된 경우
            {
                $gamename = $gameObj['name'] . '_nexus[C'.$time.']_' . $gameObj['href'];
            }
            \VanguardLTE\StatGame::create([
                'user_id' => $userId, 
                'balance' => $user->balance, 
                'bet' => $bet, 
                'win' => $win, 
                'bet_type' => $type,
                'game' => $gamename, 
                'type' => $gameObj['type'],
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
                'roundid' => $round['vendorKey'] . '#' . $round['gameId'] . '#' . $transactionKey,
            ]);
            \DB::commit();
            return response()->json([
                "code" => 0,               // 0: 정상, -1: 오류 메시지 확인
                "data" => [
                    "beforeBalance" => intval($beforeBalance),
                    "balance" => intval($user->balance),       // 보유 금액 (Number)
                    "currency" => 'KRW',    // 화폐 (String)
                ]
            ]);
        }
    }

}

<?php 
namespace VanguardLTE\Http\Controllers\Web\GameProviders
{

    use Carbon\Carbon;
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Log;
    class WILDGAMEController extends \VanguardLTE\Http\Controllers\Controller
    {
        const WILDGAME_GAME_IDENTITY = [
            //==== CASINO ====
            'wildgame-evo' => ['thirdname' =>'wildgame-evo', 'code' => '0301','type' => 'casino'],
            'wildgame-ppl' => ['thirdname' =>'wildgame-ppl', 'code' => '0201','type' => 'casino'],
            'wildgame-dg' => ['thirdname' =>'wildgame-dg', 'code' => '0201','type' => 'casino'],
            'wildgame-asia' => ['thirdname' =>'wildgame-asia', 'code' => '0302','type' => 'casino'],
            'wildgame-mgl' => ['thirdname' =>'wildgame-mgl', 'code' => '0202','type' => 'casino'],
            'wildgame-og' => ['thirdname' =>'wildgame-og', 'code' => '0201','type' => 'casino'],
            'wildgame-bota' => ['thirdname' =>'wildgame-bota', 'code' => '0201','type' => 'casino'],

            //==== slot ====
            'wildgame-pp' => ['thirdname' =>'wildgame-pp', 'code' => '0201','type' => 'slot'],
            'wildgame-mg' => ['thirdname' =>'wildgame-mg', 'code' => '0202','type' => 'slot'],
            'wildgame-bng' => ['thirdname' =>'wildgame-bng', 'code' => '0404','type' => 'slot'],
            'wildgame-playson' => ['thirdname' =>'wildgame-playson', 'code' => '0201','type' => 'slot'],
            'wildgame-playngo' => ['thirdname' =>'wildgame-playngo', 'code' => '0461','type' => 'slot'],
            'wildgame-hbn' => ['thirdname' =>'wildgame-hbn', 'code' => '0418','type' => 'slot'],
            'wildgame-rtg' => ['thirdname' =>'wildgame-rtg', 'code' => '0440','type' => 'slot'],
            'wildgame-pgsoft' => ['thirdname' =>'wildgame-pgsoft', 'code' => '0201','type' => 'slot'],
        ];
        const WILDGAME_PROVIDER = 'wildgame';

        public static function getGameObj($uuid='', $type='', $providerCode='')
        {
            foreach (WILDGAMEController::WILDGAME_GAME_IDENTITY as $ref => $value)
            {
                $gamelist = WILDGAMEController::getgamelist($ref);
                if ($gamelist)
                {
                    foreach($gamelist as $game)
                    {
                        if($type == 'live')
                        {
                            if ($game['providerCode'] == $providerCode)
                            {
                                return $game;
                                break;
                            }
                        }
                        else
                        {
                            if ($game['gamecode'] == $uuid)
                            {
                                return $game;
                                break;
                            }
                        }
                    }
                }
            }
            return null;
        }
        
        //게임 목록
        public static function getgamelist($href='')
        {
            $gameList = \Illuminate\Support\Facades\Redis::get($href.'list');
            if ($gameList)
            {
                $games = json_decode($gameList, true);
                if ($games!=null && count($games) > 0){
                    return $games;
                }
            }
            $category = WILDGAMEController::WILDGAME_GAME_IDENTITY[$href];
            $type = $category['type'];
            
            $url = config('app.wildgame_api') . '/getGames';
            $op = config('app.wildgame_opcode');

            $providerCode = $category['code'];
            $date = Carbon::now();            
            $data = [
                'providerCode' => $providerCode,
                'date' => $date,
                'gameSort' => $type
            ];
            $data = json_encode($data,true);
            $baseData = base64_encode($data);
            $gameHash = WILDGAMEController::generateHash($data);

            //test
            // $op = 'testopcode';
            $view = 1;
            if($category['type'] == 'casino'){
                $view = 0;
            }
            $params = [
                'opCode' => $op,
                'hash' => $gameHash,
                'data' => $baseData,
            ];
            $gameList = [];
            try {       
                $response = Http::get($url, $params);
                
                if ($response->ok()) {
                    $data = $response->json();
                    foreach ($data['gameList'] as $game)
                    {
                        $icon = '';
                        if(isset($game['gameIconUrl']))
                        {
                            $icon = $game['gameIconUrl'];
                        }
                        if (strtolower($game['gameSort']) == $type) 
                        {
                            $view = 1;
                            array_push($gameList, [
                                'provider' => WILDGAMEController::WILDGAME_PROVIDER,
                                'href' => $href,
                                'providercode' => $providerCode,
                                'gamecode' => $game['gameCode'],
                                'name' => $game['gameName'],
                                'title' => $game['gameKoreanName'],
                                'icon' => $icon,
                                'type' => ($type=='slot')?'slot':'live',
                                'view' => $view
                            ]);
                        }
                    }
                    \Illuminate\Support\Facades\Redis::set($href.'list', json_encode($gameList));
                }
                else
                {
                    Log::error('Holdem Gamelist Request : response is not okay. ' . json_encode($params) . '===body==' . $response->body());
                    return [];
                }
            }
            catch (\Exception $ex)
            {
                Log::error('Holdem Gamelist Request :  Excpetion. exception= ' . $ex->getMessage());
                Log::error('Holdem Gamelist Request :  Excpetion. PARAMS= ' . json_encode($params));
            }
            return $gameList;
        }       

        //유저 생성/확인
        public static function createPlayer($userId){
            $date = Carbon::now();
            $user = \VanguardLTE\User::where(['id'=> $userId])->first();
            $username = $user->username;
            $password = $user->password;
            $op = config('app.wildgame_opcode');

            $prefix = WILDGAMEController::WILDGAME_PROVIDER;
            $data = [
                'userId' => $prefix . sprintf("%04d",$user->id),
                'nickname' => $user->username,                
                'date' => $date
            ];
            $data = json_encode($data,true);
            $baseData = base64_encode($data);
            $createplayerHash = WILDGAMEController::generateHash($data);
            $url = config('app.wildgame_api') . '/player/account/create';
            
            $params = [
                'opCode' => $op,
                'hash' => $createplayerHash,
                'data' => $baseData,
            ];
            try{
                $response = Http::get($url, $params);
                if ($response->ok())
                {
                    $data = $response->json();
                    if($data['error'] == 0){
                        return $data['playerId'];
                    }else{
                        Log::error('Holdem: CreateUser result failed. ' . ($data==null?'null':json_encode($data)));
                        return -1;
                    }
                }else{
                    Log::error('Holdem: CreateUser result failed. ===body==' . $response->body());
                    return -1;
                }                
            }
            catch (\Exception $ex)
            {
                Log::error('Holdem CreateUser Request :  Excpetion. exception= ' . $ex->getMessage());
                Log::error('Holdem CreateUser Request :  Excpetion. PARAMS= ' . json_encode($params));
            }
            return -1;
        }

        public static function transferMoney($userId, $amount,$transferMethod){
            $date = Carbon::now();
            $user = \VanguardLTE\User::where(['id'=> $userId])->first();
            $safeAmount = 0;
            if(isset($user->sessiondata()['safeAmount']) && $user->sessiondata()['safeAmount'] != null){
                $safeAmount = $user->sessiondata()['safeAmount'];
            }
            $username = $user->username;
            $op = config('app.wildgame_opcode');  

            $prefix = WILDGAMEController::WILDGAME_PROVIDER;
            $data = [
                'userId' => strtoupper($prefix . sprintf("%04d",$user->id)),
                'referenceId' => $user->generateCode(24),  //랜덤 문자열
                'walletType' => 'casino',
                'amount' => $transferMethod . ($amount - $safeAmount), // 다시 확인
                'safeAmount' => $safeAmount,          
                'date' => $date
            ];
            
            $data = json_encode($data,JSON_UNESCAPED_UNICODE);
            $transfermoneyHash = WILDGAMEController::generateHash($data);
            $baseData = base64_encode($data);
            $url = config('app.wildgame_api') . '/player/balance/transfer';
            
            $params = [
                'opCode' => $op,
                'hash' => $transfermoneyHash,
                'data' => $baseData,
            ];
            try{
                $response = Http::get($url, $params);
                if (!$response->ok())
                {
                    Log::error('Holdem TransferMoney Request :  ===body==' . $response->body());
                    return null;
                }
                $data = $response->json();
                if($data['error'] == 0){
                    $responseValue = [
                        'transactionId' => $data['transactionId'],
                        'balance' => $data['balance'],
                        'safeBalance' => $data['safeBalance']
                    ];
                    $responseValue = json_encode($responseValue);
                    return $responseValue;
                }else{
                    Log::error('Holdem: TransferMoney result failed. ' . ($data==null?'null':json_encode($data)));
                    return null;
                }
            }catch(\Exception $ex){
                Log::error('Holdem TransferMoney Request :  Excpetion. exception= ' . $ex->getMessage());
                Log::error('Holdem TransferMoney Request :  Excpetion. PARAMS= ' . json_encode($params));
            }
            
            return null;
        }

        public static function getUserBalance($href, $user){
            $date = Carbon::now();
            $user = \VanguardLTE\User::where(['id'=> $user->id])->first();
            $username = $user->username;
            $op = config('app.wildgame_opcode');
            $prefix = WILDGAMEController::WILDGAME_PROVIDER;
            $data = [
                'userId' => $prefix . sprintf("%04d",$user->id),        
                'date' => $date
            ];
            $data = json_encode($data,true);
            $confirmmoneyHash = WILDGAMEController::generateHash($data);
            $baseData = base64_encode($data);
            $url = config('app.wildgame_api') . '/player/balance/current';
            
            $params = [
                'opCode' => $op,
                'hash' => $confirmmoneyHash,
                'data' => $baseData,
            ];
            try{
                $response = Http::get($url, $params);
                if (!$response->ok())
                {
                    Log::error('Holdem ConfirmMoney Request :  Failed  ===body==' . $response->body());
                    return -1;
                }
                $data = $response->json();
                if($data['error'] == 0){
                    $balance = $data['balanceList'][0]['balance'] + $data['balanceList'][0]['safeBalance'];
                    $user->sessiondata()['safeAmount'] = $data['balanceList'][0]['balance'];
                    $user->save();
                    return $balance;
                }else{
                    Log::error('Holdem: ConfirmMoney result failed. ' . ($data==null?'null':json_encode($data)));
                    return -1;
                }
            }catch(\Exception $ex){
                Log::error('Holdem ConfirmMoney Request :  Excpetion. exception= ' . $ex->getMessage());
                Log::error('Holdem ConfirmMoney Request :  Excpetion. PARAMS= ' . json_encode($params));
            }
            
            return -1;
        }


        public static function withdrawAll($href, $user)
        {
            $balance = WILDGAMEController::getUserBalance($href,$user);
            if ($balance < 0)
            {
                return ['error'=>true, 'amount'=>$balance, 'msg'=>'getuserbalance return -1'];
            }
            if ($balance > 0)
            {
                $moneyTrans = WILDGAMEController::transferMoney($user->id, $balance, '-');
                if ($moneyTrans==null)
                {
                    Log::error('Holdem withdrawAll Result :  Failed');
                    return ['error'=>true, 'amount'=>0, 'msg'=>'data not ok'];
                }
            }
            return ['error'=>false, 'amount'=>$balance];
        }
        public static function generateHash($data){
            //$opKey = 'f3fff4d3-cbf6-46ef-b710-eea686fad487';
            $opKey = config('app.wildgame_opkey');
            $baseData = base64_encode($data);
            $hashData = md5($baseData . $opKey);
            return $hashData;
        }
        public static function getgamelink($gamecode)
        {
            if (isset(WILDGAMEController::WILDGAME_GAME_IDENTITY[$gamecode]))
            {
                $gamelist = WILDGAMEController::getgamelist($gamecode);
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
            return ['error' => false, 'data' => ['url' => route('frontend.providers.waiting', [WILDGAMEController::WILDGAME_PROVIDER, $gamecode])]];
        }

        public static function makelink($gamecode,$userid){
            //Check User 
            $user = \VanguardLTE\User::where('id', $userid)->first();
            if (!$user)
            {
                Log::error('HoldemMakeLink : Does not find user ' . $userid);
                return null;
            }

            $game = WILDGAMEController::getGameObj($gamecode);
            if ($game == null)
            {
                Log::error('HoldemMakeLink : Game not find  ' . $gamecode);
                return null;
            }
            $balance = WILDGAMEController::getUserBalance($game['href'], $user);
            if($balance < 0){
                //Create User
                $alreadyUser = WILDGAMEController::createPlayer($userid);
                if($alreadyUser == -1){
                    Log::error('HoldemMakeLink : Does not find user ');
                    return null;
                }
    
            }
            if ($balance != $user->balance)
            {
                //withdraw all balance
                $data = WILDGAMEController::withdrawAll($game['href'], $user);
                if ($data['error'])
                {
                    Log::error('Holdem withdrawAll Result :  Failed msg=' . $data['msg']);
                    return null;
                }
                //Add balance

                if ($user->balance > 0)
                {
                    $moneyTrans = WILDGAMEController::transferMoney($userid, $user->balance, '+');    
                    if($moneyTrans == null){
                        Log::error('HoldemTransferMoney : Can not Transfer money ' . $userid);
                        return null;
                    }    
                }
            }
            return '/followgame/wildgame/'.$gamecode;
        }

        public static function makegamelink($gamecode, $user){
            
            $username = $user->username;
            $parse = explode('#', $user->username);
            if (count($parse) > 1)
            {
                $username = $parse[count($parse) - 1];
            }
            $game = WILDGAMEController::getGameObj($gamecode);
            if (!$game)
            {
                return null;
            }
            $date = Carbon::now();
            $providerCode = $game['providercode'];
            $op = config('app.wildgame_opcode');
            //////////////////////////////////
            $prefix = WILDGAMEController::WILDGAME_PROVIDER;
            $platform = 'WEB';
            $detect = new \Detection\MobileDetect();
            if( $detect->isMobile() || $detect->isTablet() ) 
            {
                $platform = 'Mobile';
            }
            $data = [
                'userId' => $prefix . sprintf("%04d",$user->id),
                'providerCode' => $providerCode,
                'gameCode' => $gamecode,   
                'platform' => $platform, 
                'date' => $date
            ];
            $data = json_encode($data,true);
            $baseData = base64_encode($data);
            $gameUrlHash = WILDGAMEController::generateHash($data);
            

            $params = [
                'opCode' => $op,
                'hash' => $gameUrlHash,
                'data' => $baseData
            ];

            $url = config('app.wildgame_api') . '/gameStart';
            try
            {
                $response = Http::get($url, $params);
                if (!$response->ok())
                {
                    Log::error('Holdem MakeGameLink Request Response Failed  ===body==' . $response->body());
                    return null;
                }
                $data = $response->json();
                $gameUrl = $data['gameUrl'];
                $token = $data['token'];
                $userBalance = $user->balance;
                // $record = \VanguardLTE\HoldemTransaction::create(['error_code' => $data['error'],'gamename' => $gamecode,'amount' => 0,'balance' => $userBalance,'gamecode' => $gamecode, 'user_id' => $username,'data' => json_encode($data),'token'=>$token,'stats'=>0]);
                
                return $gameUrl;
            }catch(\Exception $ex){
                Log::error('Holdem MakeGameLink Request :  Excpetion. exception= ' . $ex->getMessage());
                Log::error('Holdem MakeGameLink Request :  Excpetion. PARAMS= ' . json_encode($params));
            }
            return null;
        }

        //세션 종료 완료 API
        public static function sessionCloseFinish(){
            $user= auth()->user();
            $username = $user->username;
            $date = Carbon::now();
            $prefix = WILDGAMEController::WILDGAME_PROVIDER;
            $data= [
                'userId' => $prefix . sprintf("%04d",$user->id),
                'date' => $date
            ];
            $data = json_encode($data,true);
            $baseData = base64_encode($data);
            $gameUrlHash = WILDGAMEController::generateHash($data);
            $op = config('app.wildgame_opcode');

            $params = [
                'opCode' => $op,
                'data' => $baseData,
                'hash' => $gameUrlHash,
            ];
            $url = config('app.wildgame_api') . '/game/session/close/finish';
            try{
                $response = Http::get($url, $params);
                if (!$response->ok())
                {
                    Log::error('Holdem sessionCloseFinish Request Failed ');
                    return [];
                }
                $data = $response->json();
                $trId = '';
                $balance = 0;
                $safebalance = 0;
                if($data['error'] != 0){
                    $trId= 0;
                    $balance = 0;
                    $safebalance = 0;
                }else{
                    $trId = $data['transactionId'];
                    $balance = $data['balance'];
                    $safebalance = $data['safeBalance'];
                }
                // $preEventRecord = \VanguardLTE\HoldemTransaction::where(['user_id'=>$user->username,'transactionId'=>$trId,'stats'=>0])->first();
                $user = \VanguardLTE\User::where(['username'=> $username])->first();
                if(isset($user->sessiondata()['safeAmount'])){
                    if($user->sessiondata()['safeAmount'] == $safebalance){
                        $user->balance = $balance + $safebalance;
                    }else{
                        return response(WILDGAMEController::toText([
                            'error' => 21,
                            'description' => 'Insufficient safebox balance'
                        ]))->header('Content-Type', 'text/plain');
                    }
                }
                
                $user->save();
                // $data = $preEventRecord->data . ' /' . $data; 
                // $preEventRecord->update(['data'=>$data,'balance'=>$balance,'safebalance'=>$safebalance,'stats'=>1]);
            }catch(\Exception $ex){
                Log::error('Holdem sessionCloseFinish Request :  Excpetion. exception= ' . $ex->getMessage());
                Log::error('Holdem sessionCloseFinish Request :  Excpetion. PARAMS= ' . json_encode($params));
            }
            
        }

        public static function toText($obj) {
            $response = '';
            foreach ($obj as $key => $value) {
                if ($value !== null) {
                    $response = "{$response}\r\n{$key}={$value}";
                }
            }
            return trim($response, "\r\n");
        }

        public static function processGameRound($frompoint=-1, $checkduplicate=false)
        {
            $timepoint = 0;
            if ($frompoint == -1)
            {
                $tpoint = \VanguardLTE\Settings::where('key', self::WILDGAME_PROVIDER . 'timepoint')->first();
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
            $data = null;
            $newtimepoint = $timepoint;
            $totalCount = 0;      
            $data = WILDGAMEController::gamerounds($timepoint);
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
                        \VanguardLTE\Settings::create(['key' => self::WILDGAME_PROVIDER .'timepoint', 'value' => $newtimepoint]);
                    }
                }

                return [0,0];
            }
            $invalidRounds = [];
            if (isset($data) && isset($data['roundList']))
            {
                foreach ($data['roundList'] as $round)
                {
                    // if ($round['txn_type'] != 'CREDIT')
                    // {
                    //     continue;
                    // }

                    $gameObj = self::getGameObj($round['gameCode'],$round['type'], $round['providerCode']);
                    
                    if (!$gameObj)
                    {
                        Log::error('HOLDEM Game could not found : '. $round['gameCode']);
                            continue;
                    }
                    $bet = $round['bet'];
                    $win = $round['win'];
                    $balance = $round['balance'];
                    
                    $time = $round['date'];

                    $userid = intval(preg_replace('/'. self::WILDGAME_PROVIDER .'(\d+)/', '$1', $round['userId'])) ;
                    if ($userid == 0)
                    {
                        $userid = intval(preg_replace('/'. self::WILDGAME_PROVIDER .'(\d+)/', '$1', $round['userId'])) ;
                        continue;
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
                            'roundid' => $round['providerCode'] . '#' . $round['gameCode'] . '#' . $round['roundId'],
                        ])->first();
                        if ($checkGameStat)
                        {
                            continue;
                        }
                    // }
                    $gamename = $gameObj['name'] . '_wildgame';
                   
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
                        'roundid' => $round['providerCode'] . '#' . $round['gameCode'] . '#' . $round['roundId'],
                    ]);
                    $count = $count + 1;
                }
            }
            if(isset($data['listEndIndex']) && $data['listEndIndex'] > -1){
                $timepoint = $data['listEndIndex']; // 다시 확인 +1;
            }

            if ($frompoint == -1)
            {

                if ($tpoint)
                {
                    $tpoint->update(['value' => $timepoint]);
                }
                else
                {
                    \VanguardLTE\Settings::create(['key' => self::WILDGAME_PROVIDER .'timepoint', 'value' => $timepoint]);
                }
            }
           
            return [$count, $timepoint];
        }


        public static function gamerounds($lastid)
        {
            $date = Carbon::now();
            $op = config('app.wildgame_opcode');
            $data = [
                'walletType' => 'casino',
                'listIndex' => $lastid,
                'date' => $date
            ];
            $data = json_encode($data,true);
            $baseData = base64_encode($data);
            $gameRoundHash = WILDGAMEController::generateHash($data);
            

            $params = [
                'opCode' => $op,
                'hash' => $gameRoundHash,
                'data' => $baseData
            ];
            
            $url = config('app.wildgame_api') . '/history/DataFeeds/gamerounds';
            try
            {
                $response = Http::get($url, $params);
                if (!$response->ok())
                {
                    Log::error('Holdem GetGameRounds Request Response Failed  ===body==' . $response->body());
                    return null;
                }
                $data = $response->json();
                if($data['error'] == 0){
                    return $data;
                }else{
                    Log::error('Holdem GetGameRounds Request Response Error =>' . ($data==null?'null':json_encode($data)));
                    return null;
                }
            }catch(\Exception $ex){
                Log::error('Holdem GetGameRounds Request :  Excpetion. exception= ' . $ex->getMessage());
                Log::error('Holdem GetGameRounds Request :  Excpetion. PARAMS= ' . json_encode($params));
            }
            return null;
        }

        public static function getAgentBalance(){
            $date = Carbon::now();
            $op = config('app.wildgame_opcode');
            $data = [
                'date' => $date
            ];
            $data = json_encode($data,true);
            $baseData = base64_encode($data);
            $agentBalanceHash = WILDGAMEController::generateHash($data);

            $params = [
                'opCode' => $op,
                'hash' => $agentBalanceHash,
                'data' => $baseData
            ];

            $url = config('app.wildgame_api') . '/operator/balance/current';

            try{
                $response = Http::get($url, $params);
                if (!$response->ok())
                {
                    Log::error('Holdem GetAgentBalance Request Response Failed ===body==' . $response->body());
                    return -1;
                }
                $data = $response->json();
                if($data['error'] == 0){
                    if(isset($data['balanceList'])){
                        for($k = 0; $k < count($data['balanceList']); $k++){
                            if($data['balanceList'][$k]['walletType'] == 'casino'){
                                return intval($data['balanceList'][$k]['balance']);
                            }
                        }
                    }
                }else{
                    Log::error('Holdem GetAgentBalance Request Response Error =>' . ($data==null?'null':json_encode($data)));
                    return -1;
                }
            }catch(\Exception $ex){
                Log::error('Holdem GetAgentBalance Request :  Excpetion. exception= ' . $ex->getMessage());
                Log::error('Holdem GetAgentBalance Request :  Excpetion. PARAMS= ' . json_encode($params));
            }
            return -1;
        }


        public static function terminate($userId){
            $date = Carbon::now();
            $op = config('app.wildgame_opcode');
            $prefix = WILDGAMEController::WILDGAME_PROVIDER;
            $data= [
                'userId' => $prefix . sprintf("%04d",$userId),
                'date' => $date
            ];
            $data = json_encode($data,true);
            $baseData = base64_encode($data);
            $agentBalanceHash = WILDGAMEController::generateHash($data);

            $params = [
                'opCode' => $op,
                'hash' => $agentBalanceHash,
                'data' => $baseData
            ];
            $url = config('app.wildgame_api') . '/game/session/terminate';
            try{
                $response = Http::get($url, $params);
                if (!$response->ok())
                {
                    Log::error('Holdem Terminate Request Response Failed ===body==' . $response->body());
                    return ['error' => '-1', 'description' => '제공사응답 오류'];
                }
                $data = $response->json();
                if($data['error'] == 0){
                    $data = $response->json();
                    return $data;
                }else{
                    Log::error('Holdem Terminate Request Response Error =>' . ($data==null?'null':json_encode($data)));
                    return ['error' => '-1', 'description' => '제공사응답 오류'];
                }
            }catch(\Exception $ex){
                Log::error('Holdem Terminate Request :  Excpetion. exception= ' . $ex->getMessage());
                Log::error('Holdem Terminate Request :  Excpetion. PARAMS= ' . json_encode($params));
            }
            return ['error' => '-1', 'description' => '제공사응답 오류'];
        }
    }
}


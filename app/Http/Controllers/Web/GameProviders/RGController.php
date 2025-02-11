<?php 
namespace VanguardLTE\Http\Controllers\Web\GameProviders
{
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Log;
    class RGController extends \VanguardLTE\Http\Controllers\Controller
    {
        /*
        * UTILITY FUNCTION
        */
        // RG => RealGates
        const RG_PROVIDER = 'rg'; 
        const RG_EVOCODE = 756;

        const RG_GAME_IDENTITY = [
            //==== SLOT ====
            'rg-evol' => ['vendor' =>'evolution']
        ];

        public static function getGameObj($uuid, $isvendor=false)
        {
            foreach (RGController::RG_GAME_IDENTITY as $ref => $value)
            {
                $gamelist = RGController::getgamelist($ref);
                if ($gamelist)
                {
                    foreach($gamelist as $game)
                    {
                        if($isvendor == true)
                        {
                            if ($game['view'] == 1 && $game['vendor'] == $uuid)
                            {
                                return $game;
                            }
                        }
                        if ($game['gamecode'] == $uuid)
                        {
                            return $game;
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
        public static function getUserBalance($href, $user, $prefix=self::RG_PROVIDER) {
            $url = config('app.rg_api') . '/user';
            $token = config('app.rg_key');

            $param = [
                'username' => $prefix . sprintf("%04d",$user->id)
            ];
            $balance = -1;

            try {                   
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $token
                    ])->withBody(json_encode($param),'application/json')->post($url);
                if ($response->getStatusCode() == 200) {
                    $res = $response->json();
        
                    if (isset($res['success']) && $res['success'] == 0 && isset($res['data'])) {
                        $balance = $res['data']['user_money'];
                    }
                    else
                    {
                        Log::error('RGgetuserbalance : return failed. ' . $res['message']);
                    }
                }
                else
                {
                    Log::error('RGgetuserbalance : response is not okay. ' . $response->body());
                }
            }
            catch (\Exception $ex)
            {
                Log::error('RGgetuserbalance : getUserBalance Excpetion. exception= ' . $ex->getMessage());
                Log::error('RGgamerounds : getUserBalance Excpetion. PARAMS= ' . json_encode($params));
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
            $category = RGController::RG_GAME_IDENTITY[$href];

            $url = config('app.rg_api') . '/game-list';
            $token = config('app.rg_key');

            $param = [
                'vendor' => $category['vendor']
            ];

    
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token
                ])->get($url , $param);
            if ($response->getStatusCode() != 200)
            {
                return [];
            }
            $data = $response->json();
            if(isset($data['success']) && $data['success'] != 0)
            {
                return [];
            }
            $gameList = [];
            foreach ($data['data'] as $game)
            {
                $view = 0;
                if($game['game_code'] == self::RG_EVOCODE)
                {
                    $view = 1;
                }
                array_push($gameList, [
                    'provider' => self::RG_PROVIDER,
                    'href' => $href,
                    'gamecode' => $game['vendor'],
                    'symbol' => $game['game_code'],
                    'vendor' => $game['vendor'],
                    'enname' => $game['vendor'],
                    'name' => preg_replace('/\s+/', '', $game['game_name']),
                    'title' => $game['game_name'],
                    'icon' => $game['thumbnail'],
                    'type' => ($game['game_type']=='slot')?'slot':'table',
                    'view' => $view
                ]);
            }

            //add Unknown Game item
            array_push($gameList, [
                'provider' => self::RG_PROVIDER,
                'href' => $href,
                'symbol' => $href,
                'gamecode' => $href,
                'enname' => 'UnknownGame',
                'name' => 'UnknownGame',
                'title' => 'UnknownGame',
                'icon' => '',
                'type' => 'Unknown',
                'view' => 0
            ]);
            \Illuminate\Support\Facades\Redis::set($href.'list', json_encode($gameList));
            return $gameList;
            
        }

        public static function makegamelink($gamecode,  $prefix=self::RG_PROVIDER) 
        {

            $token = config('app.rg_key');
            
            $game = RGController::getGameObj($gamecode);
            if (!$game)
            {
                return null;
            }
            $user = auth()->user();
            if ($user == null)
            {
                return null;
            }
            $alreadyUser = 1;
            $username = self::RG_PROVIDER . sprintf("%04d",$user->id);
            $param = [
                'username' => $username
            ];
            try
            {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $token
                    ])->withBody(json_encode($param),'application/json')->post(config('app.rg_api') . '/user');
                if ($response->getStatusCode() != 200)
                {
                    // Log::error('RGmakelink : checkUser request failed. ' . $response->body());
                    $alreadyUser = 0;
                }
                $data = $response->json();
                if ($data==null || !isset($data['success']) || $data['success'] != 0)
                {
                    $alreadyUser = 0;
                }
            }
            catch (\Exception $ex)
            {
                Log::error('RGcheckuser : checkUser Exception. Exception=' . $ex->getMessage());
                Log::error('RGcheckuser : checkUser Exception. PARAMS=' . $username);
                return null;
            }
            if ($alreadyUser == 0){
                //create honor account
                try
                {

                    $url = config('app.rg_api') . '/user/create';
                    $param = [
                        'username' => $username
                    ];
                    $response = Http::withHeaders([
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $token
                        ])->withBody(json_encode($param), 'application/json')->post($url);
                    if ($response->getStatusCode() != 200)
                    {
                        Log::error('RGmakelink : createAccount request failed. ' . $response->body());
                        return null;
                    }
                    $data = $response->json();
                    if ($data==null || !isset($data['success']) || $data['success'] != 0)
                    {
                        Log::error('RGmakelink : createAccount request failed. ' . $response->body());
                        return null;
                    }
                }
                catch (\Exception $ex)
                {
                    Log::error('RGcheckuser : createAccount Exception. Exception=' . $ex->getMessage());
                    Log::error('RGcheckuser : createAccount Exception. PARAMS=' . json_encode($param));
                    return null;
                }
            }
            //Create Game link
            $category = RGController::RG_GAME_IDENTITY[$game['href']];
            // $real_code = explode('_', $game['gamecode'])[1];
            $real_code = $game['symbol'];
            $param = [
                'username' => $username,                
                'vendor' => $category['vendor'],
                'game_code' => '' . $real_code
            ];

            try
            {
                $url = config('app.rg_api') . '/game-url';
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $token
                    ])->withBody(json_encode($param),'application/json')->post($url);
                if ($response->getStatusCode() != 200)
                {
                    Log::error('RGGetLink : Game url request failed. status=' . $response->status());
                    Log::error('RGGetLink : Game url request failed. param=' . json_encode($param));
    
                    return null;
                }
                $data = $response->json();
                if ($data==null || !isset($data['success']) || $data['success'] != 0 || !isset($data['data']))
                {
                    Log::error('RGGetLink : Game url result failed. ' . ($data==null?'null':json_encode($data)));
                    return null;
                }
                $url = $data['data']['url'];
                return $url;
            }
            catch(\Exception $ex)
            {
                Log::error('RGcheckuser : createAccount Exception. Exception=' . $ex->getMessage());
                Log::error('RGcheckuser : createAccount Exception. PARAMS=' . json_encode($param));
                return null;
            }

        }
        /*
        public static function makelink($gamecode, $userid)
        {
            $token = config('app.rg_key');

            $user = \VanguardLTE\User::where('id', $userid)->first();
            if (!$user)
            {
                Log::error('RGMakeLink : Does not find user ' . $userid);
                return null;
            }

            $game = RGController::getGameObj($gamecode);
            if ($game == null)
            {
                Log::error('RGMakeLink : Game not find  ' . $gamecode);
                return null;
            }


            $alreadyUser = 1;
            $username = self::RG_PROVIDER . sprintf("%04d",$user->id);
            $param = [
                'username' => $username
            ];
            try
            {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $token
                    ])->withBody(json_encode($param),'application/json')->get(config('app.rg_api') . '/user');
                if ($response->getStatusCode() != 200)
                {
                    // Log::error('RGmakelink : checkUser request failed. ' . $response->body());
                    $alreadyUser = 0;
                }
                $data = $response->json();
                if ($data==null || !isset($data['success']) || $data['success'] != 0)
                {
                    $alreadyUser = 0;
                }
            }
            catch (\Exception $ex)
            {
                Log::error('RGcheckuser : checkUser Exception. Exception=' . $ex->getMessage());
                Log::error('RGcheckuser : checkUser Exception. PARAMS=' . $username);
                return null;
            }
            if ($alreadyUser == 0){
                //create honor account
                try
                {

                    $url = config('app.rg_api') . '/user/create';
                    $param = [
                        'username' => $username
                    ];
                    $response = Http::withHeaders([
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $token
                        ])->withBody(json_encode($param), 'application/json')->post($url);
                    if ($response->getStatusCode() != 200)
                    {
                        Log::error('RGmakelink : createAccount request failed. ' . $response->body());
                        return null;
                    }
                    $data = $response->json();
                    if ($data==null || !isset($data['success']) || $data['success'] != 0)
                    {
                        Log::error('RGmakelink : createAccount request failed. ' . $response->body());
                        return null;
                    }
                }
                catch (\Exception $ex)
                {
                    Log::error('RGcheckuser : createAccount Exception. Exception=' . $ex->getMessage());
                    Log::error('RGcheckuser : createAccount Exception. PARAMS=' . $str_param);
                    return null;
                }
            }
            return '/followgame/'.RGController::RG_PROVIDER.'/'.$gamecode;
            
        }
        */
        public static function getgamelink($gamecode)
        {
            $gameObj = RGController::getGameObj($gamecode);
            if (!$gameObj)
            {
                return ['error' => true, 'msg' => '게임이 없습니다'];
            }
            $url = RGController::makegamelink($gamecode);
            if ($url)
            {
                return ['error' => false, 'data' => ['url' => $url]];
            }
            return ['error' => true, 'msg' => '게임실행 오류입니다'];
            // return ['error' => false, 'data' => ['url' => route('frontend.providers.waiting', [RGController::RG_PROVIDER, $gamecode])]];
        }

        // Callback
        public function balance(\Illuminate\Http\Request $request)
        {
            $userid = intval(preg_replace('/'. self::RG_PROVIDER .'(\d+)/', '$1', isset($request->username)?$request->username:"")) ;
            $user = \VanguardLTE\User::where(['id'=> $userid, 'role_id' => 1])->first();
            if (!$user)
            {
                Log::error('RGchangeBalance : Not found user. PARAMS= ' . $request->username);
                return response()->json([
                    'result' => false,
                    'message' => 'Not found user',
                    'balance' => 0,
                ]);
            }
            return response()->json([
                'result' => true,
                'message' => 'OK',
                'balance' => intval($user->balance)
            ]);
        }
        public function changeBalance(\Illuminate\Http\Request $request)
        {
            $data = json_decode($request->getContent(), true);
            $username = isset($data['username'])?$data['username']:"";
            $transaction = isset($data['transaction'])?$data['transaction']:[];
            $type = isset($transaction['type'])?$transaction['type']:"";
            $amount = isset($transaction['amount'])?$transaction['amount']:-1;
            $roundid = (isset($transaction['details']) && isset($transaction['details']['game']))?$transaction['details']['game']['id'].'_'.$transaction['details']['game']['round']:"";
            $gametitle = (isset($transaction['details']) && isset($transaction['details']['game']))?$transaction['details']['game']['title']:"";
            $vendor = (isset($transaction['details']) && isset($transaction['details']['game']))?$transaction['details']['game']['vendor']:"";
            if ($username == "" || count($transaction) == 0 || $type == "" || $amount == -1 || $roundid == "" || $gametitle == "" || $vendor == "")
            {
                Log::error('RGchangeBalance : callback param Error. PARAMS= ' . json_encode($data));
                return response()->json([
                    'result' => false,
                    'message' => 'No Parameter',
                    'balance' => 0,
                ]);
            }
            $gameObj = RGController::getGameObj($vendor, true);
            if (!$gameObj)
            {
                Log::error('RGchangeBalance : Not found gameObj. PARAMS= ' . json_encode($data));
                return response()->json([
                    'result' => false,
                    'message' => 'Not found Vendor',
                    'balance' => 0,
                ]);
            }
            \DB::beginTransaction();

            $userId = intval(preg_replace('/'. self::RG_PROVIDER .'(\d+)/', '$1', $username)) ;
            $user = \VanguardLTE\User::lockforUpdate()->where(['id'=> $userId, 'role_id' => 1])->first();
            if (!$user)
            {
                Log::error('RGchangeBalance : Not found User. PARAMS= ' . json_encode($data));
                \DB::commit();
                return response()->json([
                    'result' => false,
                    'message' => 'Not found user',
                    'balance' => 0,
                ]);
            }
            $checkGameStat = \VanguardLTE\StatGame::where([
                'user_id' => $userId, 
                'bet_type' => $type, 
                'roundid' => $roundid,
            ])->first();
            if ($checkGameStat)
            {
                Log::error('RGchangeBalance : Not Game History. Roundid= ' . $roundid);
                \DB::commit();
                return response()->json([
                    'result' => true,
                    'message' => 'Exist Game History',
                    'balance' => intval($user->balance),
                ]);
            }

            $betAmount = 0;
            $winAmount = 0;
            if($type == 'bet')
            {
                $betAmount = $amount;
                $user->balance = $user->balance - intval($amount);
            }
            else
            {
                $winAmount = $amount;
                $user->balance = $user->balance + intval($amount);
            }
            $user->save();
            

            $category = \VanguardLTE\Category::where(['provider' => RGController::RG_PROVIDER, 'shop_id' => 0, 'href' => $gameObj['href']])->first();
            
            \VanguardLTE\StatGame::create([
                'user_id' => $userId, 
                'balance' => intval($user->balance), 
                'bet' => $betAmount, 
                'win' => $winAmount, 
                'game' =>  $gametitle . '_' . $gameObj['href'], 
                'type' => 'table',
                'bet_type' => $type,
                'percent' => 0, 
                'percent_jps' => 0, 
                'percent_jpg' => 0, 
                'profit' => 0, 
                'denomination' => 0, 
                'shop_id' => $user->shop_id,
                'category_id' => isset($category)?$category->id:0,
                'game_id' => $gameObj['gamecode'],
                'roundid' => $roundid,
            ]);
            \DB::commit();

            return response()->json([
                'result' => true,
                'message' => 'OK',
                'balance' => intval($user->balance)
            ]);
        }

        public static function getAgentBalance()
        {
            $token = config('app.rg_key');
            try
            {
                $url = config('app.rg_api') . '/my-info';
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $token
                    ])->get($url);
                if ($response->getStatusCode() != 200)
                {
                    Log::error('RGAgentBalance : agentbalance request failed. ' . $response->body());
                    return -1;
                }
                $data = $response->json();
                if (($data==null) || isset($data['message']))
                {
                    Log::error('RGAgentBalance : agentbalance result failed. ' . ($data==null?'null':$data['message']));
                    return -1;
                }
                return $data['balance'];
            }
            catch (\Exception $ex)
            {
                Log::error('RGAgentBalance : agentbalance Exception. Exception=' . $ex->getMessage());
                Log::error('RGAgentBalance : agentbalance Exception. PARAMS=');
                return -1;
            }

        }
    }

}

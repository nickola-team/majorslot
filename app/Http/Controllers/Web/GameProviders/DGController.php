<?php 
namespace VanguardLTE\Http\Controllers\Web\GameProviders
{
    use Illuminate\Support\Facades\Http;
    class DGController extends \VanguardLTE\Http\Controllers\Controller
    {
        /*
        * UTILITY FUNCTION
        */
        const DG_PROVIDER = 'dg';
        public function checkreference($reference)
        {
            $record = \VanguardLTE\DGTransaction::Where('reference',$reference)->get()->first();
            return $record;
        }

        public function generateCode($limit){
            $code = 0;
            for($i = 0; $i < $limit; $i++) { $code .= mt_rand(0, 9); }
            return $code;
        }

        public function getGameObj($cat4)
        {
            $categories = \VanguardLTE\Category::where(['provider' => 'ata', 'shop_id' => 0, 'site_id' => 0])->get();
            $gamelist = [];
            foreach ($categories as $category)
            {
                $gamelist = ATAController::getgamelist($category->href);
                if (count($gamelist) > 0 )
                {
                    foreach($gamelist as $game)
                    {
                        if ($game['name'] == $cat4)
                        {
                            if (isset($game['game']))
                            {
                                $game['name'] = $game['game'];
                            }
                            $game['cat_id'] = $category->original_id;
                            return $game;
                            break;
                        }
                    }
                }
            }
            return null;
        }


        public function microtime_string()
        {
            $microstr = sprintf('%.4f', microtime(TRUE));
            $microstr = str_replace('.', '', $microstr);
            return $microstr;
        }

        public static function sign()
        {
            return md5(config('app.ata_toporg') . config('app.ata_org') . config('app.ata_key'));
        }
        /*
        * FROM DG, BACK API
        */

        public function getBalance($agentName, \Illuminate\Http\Request $request)
        {
            $configAgent = config('app.dg_agent');
            $configToken = config('app.dg_token');
            if ($configAgent != $agentName)
            {
                return response()->json([
                    'codeId' => 118,
                    'token' => $configToken
                ], 200);
            }
            $data = json_decode($request->getContent());

            if ($configToken != $data->token)
            {
                return response()->json([
                    'codeId' => 2,
                    'token' => $configToken
                ], 200);
            }

            $username = $data->member->username;
            $userid = preg_replace('/'. self::DG_PROVIDER .'(\d+)/', '$1', $username) ;

            $user = \VanguardLTE\User::where('id',$userid)->first();
            if (!$user || !$user->hasRole('user')){
                return response()->json([
                    'codeId' => 102,
                    'token' => $configToken
                ], 200);
            }

            return response()->json([
                'codeId' => 0,
                'token' => $configToken,
                'member' => [
                    'username' => $username,
                    'balance' => floatval($user->balance)
                ]
            ], 200);
        }

        public function transfer($agentName, \Illuminate\Http\Request $request)
        {
            $configAgent = config('app.dg_agent');
            $configToken = config('app.dg_token');
            if ($configAgent != $agentName)
            {
                return response()->json([
                    'codeId' => 118,
                    'token' => $configToken
                ], 200);
            }
            $data = json_decode($request->getContent());

            if ($configToken != $data->token)
            {
                return response()->json([
                    'codeId' => 2,
                    'token' => $configToken
                ], 200);
            }

            $username = $data->member->username;
            $serial = $data->data;
            $amount = $data->member->amount;

            $userid = preg_replace('/'. self::DG_PROVIDER .'(\d+)/', '$1', $username);

            $user = \VanguardLTE\User::lockforUpdate()->where('id',$userid)->first();
            if (!$user || !$user->hasRole('user')){
                return response()->json([
                    'codeId' => 102,
                    'token' => $configToken
                ], 200);
            }


            $record = $this->checkreference($serial);
            if ($record)
            {
                return response()->json([
                    'codeId' => 0,
                    'token' => $configToken,
                    'member' => [
                        'username' => $username,
                        'amount' => $amount,
                        'balance' => floatval($user->balance)
                    ]
                ], 200);
            }

            $transaction = \VanguardLTE\DGTransaction::create([
                'reference' => $serial, 
                'timestamp' => $this->microtime_string(),
                'data' => json_encode($data),
                'refund' => 0
            ]);
            
            $oldBalance = $user->balance;
            $newBalance = $oldBalance + $amount;
            $user->update(['balance' => $newBalance]);

            return response()->json([
                'codeId' => 0,
                'token' => $configToken,
                'member' => [
                    'username' => $username,
                    'amount' => $amount,
                    'balance' => floatval($oldBalance)
                ]
            ], 200);
        }

        public function checkTransfer($agentName, \Illuminate\Http\Request $request)
        {
            $configAgent = config('app.dg_agent');
            $configToken = config('app.dg_token');
            if ($configAgent != $agentName)
            {
                return response()->json([
                    'codeId' => 118,
                    'token' => $configToken
                ], 200);
            }
            $data = json_decode($request->getContent());

            if ($configToken != $data->token)
            {
                return response()->json([
                    'codeId' => 2,
                    'token' => $configToken
                ], 200);
            }

            $serial = $data->data;

            $record = $this->checkreference($serial);
            if (!$record)
            {
                return response()->json([
                    'codeId' => 98,
                    'token' => $configToken,
                ], 200);
            }

            return response()->json([
                'codeId' => 0,
                'token' => $configToken,
            ], 200);
        }

        public function inform($agentName, \Illuminate\Http\Request $request)
        {
            $configAgent = config('app.dg_agent');
            $configToken = config('app.dg_token');
            if ($configAgent != $agentName)
            {
                return response()->json([
                    'codeId' => 118,
                    'token' => $configToken
                ], 200);
            }
            $data = json_decode($request->getContent());

            if ($configToken != $data->token)
            {
                return response()->json([
                    'codeId' => 2,
                    'token' => $configToken
                ], 200);
            }

            $serial = $data->data;
            $username = $data->member->username;
            $amount = $data->member->amount;
            $oldBalance = 0;
            $record = $this->checkreference($serial);
            if ($amount > 0) {
                if ($record)
                {
                    return response()->json([
                        'codeId' => 0,
                        'token' => $configToken,
                    ], 200);
                }
                $userid = preg_replace('/'. self::DG_PROVIDER .'(\d+)/', '$1', $username);
                $user = \VanguardLTE\User::lockforUpdate()->where('id',$userid)->first();
                if (!$user || !$user->hasRole('user')){
                    return response()->json([
                        'codeId' => 98,
                        'token' => $configToken
                    ], 200);
                }

                $transaction = \VanguardLTE\DGTransaction::create([
                    'reference' => $serial, 
                    'timestamp' => $this->microtime_string(),
                    'data' => json_encode($data),
                    'refund' => 0
                ]);
                
                $oldBalance = $user->balance;
                $newBalance = $oldBalance + $amount;
                $user->update(['balance' => $newBalance]);
            }
            else
            {
                if (!$record)
                {
                    return response()->json([
                        'codeId' => 0,
                        'token' => $configToken,
                    ], 200);
                }
                $record->delete();
                $userid = preg_replace('/'. self::DG_PROVIDER .'(\d+)/', '$1', $username);
                $user = \VanguardLTE\User::lockforUpdate()->where('id',$userid)->first();
                if (!$user || !$user->hasRole('user')){
                    return response()->json([
                        'codeId' => 98,
                        'token' => $configToken
                    ], 200);
                }
               
                $oldBalance = $user->balance;
                $newBalance = $oldBalance - $amount;
                $user->update(['balance' => $newBalance]);
            }
            return response()->json([
                'codeId' => 0,
                'token' => $configToken,
                'member' => [
                    'username' => $username,
                    'amount' => $amount,
                    'balance' => floatval($oldBalance)
                ]
            ], 200);
        }
        public function order($agentName, \Illuminate\Http\Request $request)
        {
            $configAgent = config('app.dg_agent');
            $configToken = config('app.dg_token');
            if ($configAgent != $agentName)
            {
                return response()->json([
                    'codeId' => 118,
                    'token' => $configToken
                ], 200);
            }
            $data = json_decode($request->getContent());

            if ($configToken != $data->token)
            {
                return response()->json([
                    'codeId' => 2,
                    'token' => $configToken
                ], 200);
            }
            $ticketId = $data->ticketId;
            return response()->json([
                'codeId' => 0,
                'token' => $configToken,
                'tiketId' => $ticketId,
                'list' => []
            ], 200);
        }

        public function unsettle($agentName, \Illuminate\Http\Request $request)
        {
            $configAgent = config('app.dg_agent');
            $configToken = config('app.dg_token');
            if ($configAgent != $agentName)
            {
                return response()->json([
                    'codeId' => 118,
                    'token' => $configToken
                ], 200);
            }
            $data = json_decode($request->getContent());

            if ($configToken != $data->token)
            {
                return response()->json([
                    'codeId' => 2,
                    'token' => $configToken
                ], 200);
            }
            return response()->json([
                'codeId' => 0,
                'token' => $configToken,
                'list' => []
            ], 200);
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

            $data = [
                'topOrg' => config('app.ata_toporg'),
                'org' => config('app.ata_org'),
                'sign' => ATAController::sign(),
                'type' => $href
            ];
            $resultdata = null;
            try {
                $response = Http::asForm()->post(config('app.ata_api') . '/getGameList', $data);
                if (!$response->ok())
                {
                    return [];
                }
                $resultdata = $response->json();
            }
            catch (Exception $ex)
            {
                return [];
            }

            $gameList = [];
            $slotgameString = ['Slot game', 'Video Slot','3-Reel Slot Machine', '5-Reel Slot Machine', 'SLOT','5x5 Grid Slot Machine','7x7 Grid Slot Machine','5x7 Grid Slot Machine','8x8 Grid Slot Machine','3x3 Grid Slot Machine','6x6 Grid Slot Machine'];
            $exceptGames = [ 150328,150331,150332,150325,150319,150320,150316,150312,150298,150295,150294,150268,150267,150266,150265,150264,150251,150250,150249,150244,150237,150236,150235,150232,150233,150212,150207,150196,150174,150209,150206,150200,150181,150202,150215,150211,150010,150205,150012];
            if ($resultdata['code'] == 0){

                foreach ($resultdata['data'] as $game)
                {
                    if (in_array($game['DCGameID'], $exceptGames))
                    {
                        continue;
                    }

                    if (in_array($game['GameType'] , $slotgameString) && $game['GameStatus'] == 1){ // need to check the string
                        if ($href=='nlc' && preg_match('/DX1$/',  $game['LogPara']))
                        {
                            continue;
                        }
                        if ($href=='png')
                        {
                            $icon_name = str_replace(' ', '_', $game['GameName']);
                            $icon_name = str_replace(':', '_', $icon_name);
                            // $icon_name = str_replace('\'', '_', $icon_name);
                            $icon_name = strtolower(preg_replace('/\s+/', '', $icon_name));
                            $gameList[] = [
                                'provider' => 'ata',
                                'href' => $href,
                                'gamecode' => $game['DCGameID'],
                                'name' => $game['LogPara'],
                                'game' => preg_replace('/\s+/', '', $game['GameName']),
                                'title' => \Illuminate\Support\Facades\Lang::has('gameprovider.'.$game['GameName'], 'ko')?__('gameprovider.'.$game['GameName']):$game['GameName'],
                                'icon' => '/frontend/Default/ico/png/'. $icon_name . '.jpg',
                            ];
                        }
                        else
                        {
                            $gameList[] = [
                                'provider' => 'ata',
                                'href' => $href,
                                'gamecode' => $game['DCGameID'],
                                'name' => $game['LogPara'],
                                'game' => preg_replace('/\s+/', '', $game['GameName']),
                                'title' => \Illuminate\Support\Facades\Lang::has('gameprovider.'.$game['GameName'], 'ko')?__('gameprovider.'.$game['GameName']):$game['GameName'],
                                'icon' => $href=='aux'?('/frontend/Default/ico/ata/avatarux/'. $game['DCGameID'] . '.png'):('/frontend/Default/ico/ata/'.$href.'/'. $game['DCGameID'] . '.png'),
                            ];
                        }
                    }
                }
                \Illuminate\Support\Facades\Redis::set($href.'list', json_encode($gameList));
            }
            return $gameList;
            
        }

        public static function makegamelink($gamecode)
        {
            $detect = new \Detection\MobileDetect();
            $user = auth()->user();
            if ($user == null)
            {
                return null;
            }
            $data = [
                'loginname' => $user->id,
                'key' => $user->api_token,
                'currency' => 'KRW',
                'lang' => 'ko',
                'gameid' => $gamecode,
                'org' => config('app.ata_org'),
                'home' => url('/'),
                'fullscreen' => 'no',
                'channel' => ($detect->isMobile() || $detect->isTablet())?'mobile':'pc',
            ];

            $resultdata = null;
            try {
                $response = Http::post(config('app.ata_api') . '/launchClient.html', $data);
                if (!$response->ok())
                {
                    return null;
                }
                $resultdata = $response->json();
            }
            catch (Exception $ex)
            {
                return null;
            }
            if ($resultdata)
            {
                if ($resultdata['code'] == 0)
                {
                    return $resultdata['data']['launchurl'];
                }
            }
            return null;
        }

        public static function getgamelink($gamecode)
        {
            $url = ATAController::makegamelink($gamecode);
            if ($url)
            {
                return ['error' => false, 'data' => ['url' => $url]];
            }
            return ['error' => true, 'msg' => '로그인하세요'];            
        }

    }

}

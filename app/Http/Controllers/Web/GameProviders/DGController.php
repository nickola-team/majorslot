<?php 
namespace VanguardLTE\Http\Controllers\Web\GameProviders
{
    use Illuminate\Support\Facades\Http;
    class DGController extends \VanguardLTE\Http\Controllers\Controller
    {
        /*
        * UTILITY FUNCTION
        */
        const DG_PROVIDER = 'DG';
        public function checkreference($reference)
        {
            $record = \VanguardLTE\DGTransaction::Where('reference',$reference)->get()->first();
            return $record;
        }

        public static function sign($random)
        {
            return md5(config('app.dg_agent') . config('app.dg_token') . $random);
        }


        public static function generateCode($limit){
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

        /*
        * FROM DG, BACK API
        */

        public function getBalance($agentName, \Illuminate\Http\Request $request)
        {
            $configAgent = config('app.dg_agent');
            $configToken = $this->sign('');
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
            $configToken = $this->sign('');
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
            $configToken = $this->sign('');
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
            $configToken = $this->sign('');
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
            $configToken = $this->sign('');
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
            $configToken = $this->sign('');
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

            $gameList = [];
            $query = 'SELECT * FROM w_provider_games WHERE provider="dg' . $href .'"';
            $gac_games = \DB::select($query);
            foreach ($gac_games as $game)
            {
                $icon_name = str_replace(' ', '_', $game->gameid);
                $icon_name = strtolower(preg_replace('/\s+/', '', $icon_name));
                array_push($gameList, [
                    'provider' => 'dg',
                    'gameid' => $game->gameid,
                    'gamecode' => $game->gamecode,
                    'enname' => $game->name,
                    'name' => preg_replace('/\s+/', '', $game->name),
                    'title' => $game->title,
                    'type' => $game->type,
                    'href' => $href,
                    'view' => $game->view,
                    'icon' => '/frontend/Default/ico/dg/'. $icon_name . '.jpg',
                    ]);
            }
            \Illuminate\Support\Facades\Redis::set($href.'list', json_encode($gameList));
            return $gameList;
            
        }

        public static function registerMember($userId)
        {
            $url = config('app.dg_api') . '/user/signup/' . config('app.dg_agent');
            $rand = DGController::generateCode(6);
            $key = DGController::sign($rand);
            $params = [
                'token' => $key,
                'random' => $rand,
                'data' => '',
                'member' => [
                    'username' => self::DG_PROVIDER . $userId,
                    'password' => DGController::sign($rand),
                    'currencyName' => 'KRW',
                    'winLimit' => 0
                ]
            ];
            $response = Http::post($url, $params);
            if (!$response->ok())
            {
                Log::error('DG : register request failed. ' . $response->body());
                return false;
            }
            $data = $response->json();
            if ($data['codeId'] == 0 || $data['codeId'] == 116)
            {
                return true;
            }
            Log::error('DG : register response failed. ' . $data['codeId']);
            return false;
        }

        public static function login($userId)
        {
            $url = config('app.dg_api') . '/user/login/' . config('app.dg_agent');
            $rand = DGController::generateCode(6);
            $key = DGController::sign($rand);
            $params = [
                'token' => $key,
                'random' => $rand,
                'lang' => 'kr',
                'member' => [
                    'username' => self::DG_PROVIDER . $userId,
                    'password' => DGController::sign($rand),
                ]
            ];
            $response = Http::post($url, $params);
            if (!$response->ok())
            {
                Log::error('DG : login request failed. ' . $response->body());
                return null;
            }
            $data = $response->json();
            if ($data['codeId'] != 0 )
            {
                Log::error('DG : login response failed. ' . $data['codeId']);
            }
            return $data;
        }

        public static function makegamelink($gamecode)
        {
            $detect = new \Detection\MobileDetect();
            $user = auth()->user();
            if ($user == null)
            {
                return null;
            }
            $res = DGController::registerMember($user->id);
            if (!$res)
            {
                return null;
            }
            $data = DGController::login($user->id);
            if (!$data || $data['codeId'] != 0)
            {
                return null;
            }
            $url = $data['list'][0] . $data['token'] . '&language=kr';
            if ($detect->isMobile() || $detect->isTablet())
            {
                $url = $data['list'][1] . $data['token'] . '&language=kr';
            }
            return $url;                 
        }

        public static function getgamelink($gamecode)
        {
            $url = DGController::makegamelink($gamecode);
            if ($url)
            {
                return ['error' => false, 'data' => ['url' => $url]];
            }
            return ['error' => true, 'msg' => '로그인하세요'];            
        }

    }

}

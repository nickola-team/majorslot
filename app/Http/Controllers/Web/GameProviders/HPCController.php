<?php 
namespace VanguardLTE\Http\Controllers\Web\GameProviders
{
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Log;
    class HPCController extends \VanguardLTE\Http\Controllers\Controller
    {
        /*
        * UTILITY FUNCTION
        */
        const HPC_PROVIDER = 'hpc';

        public function microtime_string()
        {
            $microstr = time();
            return $microstr;
        }

        public static function getGameObj($tableId)
        {
            $gamelist = HPCController::getgamelist(self::HPC_PROVIDER);
            $tableName = preg_replace('/\s+/', '', $tableId);
            if ($gamelist)
            {
                foreach($gamelist as $game)
                {
                    if ($game['gamecode'] == $tableName)
                    {
                        return $game;
                        break;
                    }
                }
            }
            return null;
        }

        /*
        * FROM CONTROLLER, API
        */

        public function userSignal(\Illuminate\Http\Request $request)
        {
            $user = \VanguardLTE\User::lockForUpdate()->where('id',auth()->id())->first();
            if (!$user)
            {
                return response()->json([
                    'error' => '1',
                    'description' => 'unlogged']);
            }
            if ($user->playing_game != self::HPC_PROVIDER)
            {
                return response()->json([
                    'error' => '1',
                    'description' => 'Idle TimeOut']);
            }

            if ($request->name == 'exitGame')
            {
                $user->update([
                    'playing_game' => self::HPC_PROVIDER . 'exit',
                    'played_at' => time()
                ]);
            }
            else
            {
                $balance = HPCController::getUserBalance($user);
                if ($balance != null)
                {
                    $user->update([
                        'balance' => $balance,
                        'played_at' => time()
                    ]);
                }
            }
            return response()->json([
                'error' => '0',
                'description' => 'OK']);
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
            $gameList = [];
            $headers = HPCController::getApiHeaderInfo(null);
            $url = config('app.hpc_api') . '/games/get';
            $params = [
                'language' => 'kr'
            ];
            $hpc_games = [];
            try {
                $response = Http::withHeaders($headers)->post($url, $params);
                if (!$response->ok())
                {
                    return [];
                }
                $data = $response->json();
                if ($data['status'] != 1)
                {
                    return [];
                }
                $hpc_games = $data['game_list'];
            } catch (\Exception $e) {
                Log::error('HPC : getgamelist request failed. ' . $e->getMessage());
                return [];
            }
            foreach ($hpc_games as $game)
            {
                array_push($gameList, [
                    'provider' => 'hpc',
                    'gamecode' => $game['tableid'],
                    'name' => preg_replace('/\s+/', '', $game['name']),
                    'title' => $game['name'],
                    'type' => 'live',
                    'href' => $href,
                    'view' => 0,
                    ]);
            }
            array_push($gameList, [
                'provider' => 'hpc',
                'gamecode' => 'hpclobby',
                'name' => 'HPCLobby',
                'title' => '에볼루션 로비',
                'type' => 'live',
                'icon' => '/frontend/Default/ico/hpc/hpclobby.jpg',
                'href' => $href,
                'view' => 1,
                ]);
            \Illuminate\Support\Facades\Redis::set($href.'list', json_encode($gameList));
            return $gameList;
            
        }

        public static function getApiHeaderInfo($user)
        {
            $recommend = config('app.hpc_key');
            if ($user != null) {
                $master = $user->referral;
                while ($master!=null && !$master->isInoutPartner())
                {
                    $master = $master->referral;
                }
                if ($master == null)
                {
                    return [];
                }
                $query = 'SELECT * FROM w_provider_info WHERE provider="hpc" and user_id=' . $master->id;
                $hpc_info = \DB::select($query);
                foreach ($hpc_info as $info)
                {
                    $recommend = $info->config;
                }
            }
            $hpc_params = explode(':', $recommend);

            $headers = [
                'ag-code' => $hpc_params[0],
                'ag-token' => $hpc_params[1]
            ];
            return $headers;
        }

        public static function getUserBalance($user)
        {
            //get user balance
            $params = [
                'user_id' => self::HPC_PROVIDER . $user->id,
            ];
            $headers = HPCController::getApiHeaderInfo($user);

            $url = config('app.hpc_api') . '/customer/get';
            $response = Http::withHeaders($headers)->post($url, $params);
            if (!$response->ok())
            {
                Log::error('HPC : get user info request failed. ' . $response->body());
                return 0;
            }
            $data = $response->json();
            if ($data==null && $data['status']!=0)
            {
                return 0;
            }
            return $data['balance'];
        }

        public static function withdrawAll($user)
        {
            $balance = HPCController::getUserBalance($user);

            if ($balance <= 0)
            {
                return ['error'=>false, 'amount'=>0];
            }
            //sub balance
            $params = [
                'user_id' => self::HPC_PROVIDER . $user->id,
                'balance' => $balance
            ];
            $headers = HPCController::getApiHeaderInfo($user);
            $url = config('app.hpc_api') . '/customer/sub_balance';
            $response = Http::withHeaders($headers)->post($url, $params);
            if (!$response->ok())
            {
                Log::error('HPC : sub balance request failed. ' . $response->body());
                return ['error'=>true, 'amount'=>0];
            }
            $data = $response->json();
            if ($data==null && $data['status']!=0)
            {
                return ['error'=>true, 'amount'=>0];
            }
            return ['error'=>false, 'amount'=>$data['amount']];
        }

        public static function makelink($gamecode, $userid)
        {
            $user = \VanguardLTE\User::where('id', $userid)->first();
            if (!$user)
            {
                Log::error('HPC : Does not find user ' . $userid);
                return null;
            }
            //create hpc account
            
            $params = [
                'id' => self::HPC_PROVIDER . $user->id,
                'name' => $user->username,
                'balance' => intval($user->balance),
                'language' => 'kr'
            ];
            $headers = HPCController::getApiHeaderInfo($user);

            $url = config('app.hpc_api') . '/customer/user_create';

            $response = Http::withHeaders($headers)->post($url, $params);
            if (!$response->ok())
            {
                Log::error('HPC : Create account request failed. ' . $response->body());
                return null;
            }
            $data = $response->json();
            if ($data==null || ($data['status']==0 && $data['error']!='DOUBLE_USER')) //create success or already exist
            {
                Log::error('HPC : Create account result failed. ' . ($data==null?'null':$data['error']));
                return null;
            }

            if ($data['status']==0) { //already user
                //withdraw all balance
                $data = HPCController::withdrawAll($user);
                if ($data['error'])
                {
                    return null;
                }
            }
            

            //Add balance

            $params = [
                'user_id' => self::HPC_PROVIDER . $user->id,
                'balance' => intval($user->balance)
            ];
            $url = config('app.hpc_api') . '/customer/add_balance';
            $response = Http::withHeaders($headers)->post($url, $params);
            if (!$response->ok())
            {
                Log::error('HPC : add balance request failed. ' . $response->body());
                return null;
            }
            $data = $response->json();
            if ($data==null || $data['status']==0)
            {
                return null;
            }

            return '/providers/hpc/'.$gamecode;

        }

        public static function makegamelink($gamecode)
        {
            $user = auth()->user();
            if ($user == null)
            {
                return null;
            }
            $is_test = str_contains($user->username, 'testfor');
            if ($is_test) //it must be hidden from all providers
            {
                return null;
            }
            $params = [
                'user' => [
                    'id' => self::HPC_PROVIDER . $user->id,
                    'name' => $user->username,
                    'balance' => intval($user->balance),
                    'language' => 'kr'
                ]
            ];
            $headers = HPCController::getApiHeaderInfo($user);
            
            $url = config('app.hpc_api') . '/account/login';
            $launchurl = null;
            try {
                $response = Http::withHeaders($headers)->post($url, $params);
                if (!$response->ok())
                {
                    return null;
                }
                $data = $response->json();
                if ($data && $data['status'] == 1){
                    $launchurl = $data['launch_url'];
                }
            }
            catch (\Exception $ex)
            {
                Log::error('HPC : Auth request failed. ' . $e->getMessage());
                return null;
            }
            return $launchurl;
        }

        public static function getgamelink($gamecode)
        {
            $user = auth()->user();
            if ($user->playing_game != null) //already playing game.
            {
                return ['error' => true, 'data' => '이미 실행중인 게임을 종료해주세요. 이미 종료했음에도 불구하고 이 메시지가 계속 나타난다면 매장에 문의해주세요.'];
            }
            return ['error' => false, 'data' => ['url' => route('frontend.providers.waiting', [self::HPC_PROVIDER, $gamecode])]];
        }

        public static function processRounds()
        {
            $old_time = date("Y-m-d H:i:s", strtotime("-5 minutes"));
            $records = \VanguardLTE\HPCTransaction::where('refund',0)->where('timestamp', '<', $old_time)->get();
            foreach ($records as $r)
            {
                $data = json_decode($record->data);
                $tx = $data->txn_id;
                $site_user = $data->site_user;
                $tableName = $data->table_id;
                $betAmount = $data->amount;
                $userid = preg_replace('/'. self::HPC_PROVIDER .'(\d+)/', '$1', $site_user) ;
                $user = \VanguardLTE\User::where(['id'=> $userid, 'role_id' => 1])->first();
                if (!$user)
                {
                    $r->update(['refund' => 1]);
                    continue;
                }
                $amount = abs($betAmount);
                $gameObj = $this->getGameObj($tableName);
                if (!$gameObj)
                {
                    $gameObj = 
                    ['name' => 'Unknown'];
                }
                $category = \VanguardLTE\Category::where(['provider' => 'hpc', 'shop_id' => 0, 'href' => 'hpc'])->first();

                \VanguardLTE\StatGame::create([
                    'user_id' => $user->id, 
                    'balance' => -1, 
                    'bet' => $amount, 
                    'win' => 0, 
                    'game' =>  $gameObj['name'], 
                    'type' => 'table',
                    'percent' => 0, 
                    'percent_jps' => 0, 
                    'percent_jpg' => 0, 
                    'profit' => 0, 
                    'denomination' => 0, 
                    'shop_id' => $user->shop_id,
                    'category_id' => isset($category)?$category->id:0,
                    'game_id' => $tableName,
                    'roundid' => $tx,
                ]);

            }
        }

    }

}

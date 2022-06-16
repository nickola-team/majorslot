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
        public function checktransaction($id)
        {
            $record = \VanguardLTE\HPCTransaction::Where('reference',$id)->first();
            return $record;
        }

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
        * FROM HPPlayCasino, BACK API
        */

        public function balance(\Illuminate\Http\Request $request)
        {
            $data = json_decode($request->getContent(), true);
            $site_user = $data['site_user'];
            $userid = preg_replace('/'. self::HPC_PROVIDER .'(\d+)/', '$1', $site_user) ;
            $user = \VanguardLTE\User::where(['id'=> $userid, 'role_id' => 1])->first();
            if (!$user)
            {
                return response()->json([
                    'status' => 0,
                    'balance' => 0,
                    'error' => 'INVALID_USER'
                ]);
            }
            return response()->json([
                'status' => 1,
                'balance' => intval($user->balance),
                'error' => null
            ]);
        }
        public function debit(\Illuminate\Http\Request $request)
        {
            $data = json_decode($request->getContent(), true);
            $site_user = $data['site_user'];
            $tableName = $data['table_id'];
            $betAmount = $data['amount'];
            $cause = $data['cause'];
            $txn_id = $data['txn_id'];
            if (!$site_user || !$tableName || !$betAmount)
            {
                return response()->json([
                    'status' => 0,
                    'error' => 'INVALID_USER'
                ]);
            }
            $userid = preg_replace('/'. self::HPC_PROVIDER .'(\d+)/', '$1', $site_user) ;
            $user = \VanguardLTE\User::lockforUpdate()->where(['id'=> $userid, 'role_id' => 1])->first();
            if (!$user)
            {
                return response()->json([
                    'status' => 0,
                    'balance' => 0,
                    'error' => 'INVALID_USER'
                ]);
            }
            $record = $this->checktransaction($txn_id);
            if ($record)
            {
                return response()->json([
                    'status' => 0,
                    'balance' => 0,
                    'error' => 'INTERNAL ERROR'
                ]);
            }

            $amount = abs($betAmount);
            if ($user->balance < $amount)
            {
                return response()->json([
                    'status' => 0,
                    'balance' => 0,
                    'error' => 'ACCESS_DENIED'
                ]);
            }
            

            $user->balance = $user->balance - intval($amount);
            $user->save();
            $user = $user->fresh();
            \VanguardLTE\HPCTransaction::create(
                [
                    'reference' => $txn_id, 
                    'data' => json_encode($data),
                    'refund' => 0
                ]
            );
            return response()->json([
                'status' => 1,
                'balance' => intval($user->balance),
                'error' => null
            ]);
        }
        public function credit(\Illuminate\Http\Request $request)
        {
            $data = json_decode($request->getContent(), true);
            $site_user = $data['site_user'];
            $tableName = $data['table_id'];
            $betAmount = $data['amount'];
            $cause = $data['cause'];
            $txn_id = $data['txn_id'];
            if (!$site_user || !$tableName || !$betAmount)
            {
                return response()->json([
                    'status' => 0,
                    'error' => 'INVALID_USER'
                ]);
            }
            $userid = preg_replace('/'. self::HPC_PROVIDER .'(\d+)/', '$1', $site_user) ;
            $user = \VanguardLTE\User::lockforUpdate()->where(['id'=> $userid, 'role_id' => 1])->first();
            if (!$user)
            {
                return response()->json([
                    'status' => 0,
                    'balance' => 0,
                    'error' => 'INVALID_USER'
                ]);
            }
            $record = $this->checktransaction($txn_id);
            $bet = 0;
            if ($record )
            {
                if ($record->refund == 1)
                {
                    return response()->json([
                        'status' => 0,
                        'balance' => 0,
                        'error' => 'INVALID_USER'
                    ]);
                }
                $data = json_decode($record->data);
                $bet = $data->amount;
                $record->update(['refund' => 1]);
            }

            $amount = abs($betAmount);

            $user->balance = $user->balance + intval($amount);
            $user->save();
            $user = $user->fresh();
            
            $gameObj = $this->getGameObj($tableName);
            if (!$gameObj)
            {
                $gameObj = 
                ['name' => 'Unknown'];
            }
            $category = \VanguardLTE\Category::where(['provider' => 'hpc', 'shop_id' => 0, 'href' => 'hpc'])->first();

            \VanguardLTE\StatGame::create([
                'user_id' => $user->id, 
                'balance' => $user->balance, 
                'bet' => abs($bet), 
                'win' => $amount, 
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
                'roundid' => $txn_id,
            ]);
            return response()->json([
                'status' => 1,
                'balance' => intval($user->balance),
                'error' => null
            ]);
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
            $recommend = config('app.hpc_key');
            $hpc_params = explode(':', $recommend);
            $params = [
                'language' => 'kr'
            ];

            $headers = [
                'ag-code' => $hpc_params[0],
                'ag-token' => $hpc_params[1]
            ];
            $url = config('app.hpc_api') . '/games/get';

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
                'title' => 'lobby',
                'type' => 'live',
                'href' => $href,
                'view' => 1,
                ]);
            \Illuminate\Support\Facades\Redis::set($href.'list', json_encode($gameList));
            return $gameList;
            
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
            $master = $user->referral;
            while ($master!=null && !$master->isInoutPartner())
            {
                $master = $master->referral;
            }
            if ($master == null)
            {
                return null;
            }
            $recommend = config('app.hpc_key');
            $query = 'SELECT * FROM w_provider_info WHERE provider="hpc" and user_id=' . $master->id;
            $hpc_info = \DB::select($query);
            foreach ($hpc_info as $info)
            {
                $recommend = $info->config;
            }
            $hpc_params = explode(':', $recommend);

            $params = [
                'user' => [
                    'id' => self::HPC_PROVIDER . $user->id,
                    'name' => $user->username,
                    'balance' => intval($user->balance),
                    'language' => 'kr'
                ]
            ];
            $headers = [
                'ag-code' => $hpc_params[0],
                'ag-token' => $hpc_params[1]
            ];
            
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
            $url = HPCController::makegamelink($gamecode);
            if ($url)
            {
                return ['error' => false, 'data' => ['url' => $url]];
            }
            return ['error' => true, 'msg' => '로그인하세요'];
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

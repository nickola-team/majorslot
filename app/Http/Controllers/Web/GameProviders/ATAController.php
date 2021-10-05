<?php 
namespace VanguardLTE\Http\Controllers\Web\GameProviders
{
    use Illuminate\Support\Facades\Http;
    class ATAController extends \VanguardLTE\Http\Controllers\Controller
    {
        /*
        * UTILITY FUNCTION
        */

        public function checkreference($reference)
        {
            $record = \VanguardLTE\ATATransaction::Where('reference',$reference)->get()->first();
            return $record;
        }

        public function generateCode($limit){
            $code = 0;
            for($i = 0; $i < $limit; $i++) { $code .= mt_rand(0, 9); }
            return $code;
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
        * FROM ATA, BACK API
        */

        public function endpoint($service, \Illuminate\Http\Request $request)
        {
            $response = [];
            switch ($service)
            {
                case 'playerinfo.json':
                    $response = $this->playerinfo($request);
                    break;
                case 'wager.json':
                    $response = $this->wager($request);
                    break;
                case 'cancelwager.json':
                    $response = $this->cancelwager($request);
                    break;
                case 'appendwagerresult.json':
                    $response = $this->appendwagerresult($request);
                    break;
                case 'endwager.json':
                    $response = $this->endwager($request);
                    break;
                case 'campaignpayout.json':
                    $response = $this->campaignpayout($request);
                    break;
                case 'getbalance.json':
                    $response = $this->getbalance($request);
                    break;
                default:
                    $response = ['code' => 1,];
            }

            return response()->json($response, 200);
        }

        public function playerinfo($request)
        {
            $org = $request->org;
            $sessiontoken = $request->sessiontoken;

            $user = \VanguardLTE\User::lockForUpdate()->where('api_token',$sessiontoken)->first();
            if (!$user || !$user->hasRole('user')){
                return [
                    'code' => 1000,
                    'msg' => 'Not found user',
                ];
            }

            return [
                'code' => 0,
                'data' => [
                    'gender' => 'M',
                    'playerId' => $user->id,
                    'organization' => config('app.ata_org'),
                    'balance' => $user->balance,
                    'currency' => 'KRW',
                    'applicableBonus' => 0,
                    'homeCurrency' => 'KRW',
                    'country' => 'ko',
                ],
            ];
        }

        public function getbalance($request)
        {
            $org = $request->org;
            $sessiontoken = $request->sessiontoken;
            $playerid = $request->playerid;

            $user = \VanguardLTE\User::lockForUpdate()->where('api_token',$sessiontoken)->first();
            if (!$user || !$user->hasRole('user')){
                return [
                    'code' => 1000,
                    'msg' => 'Not found user',
                ];
            }

            return [
                'code' => 0,
                'data' => [
                    'playerId' => $user->id,
                    'organization' => config('app.ata_org'),
                    'currency' => 'KRW',
                    'applicableBonus' => 0,
                    'homeCurrency' => 'KRW',
                    'country' => 'ko',
                    'balance' => $user->balance,
                    'bonus' => 0,
                ],
            ];
        }

        public function wager($request)
        {
            $org = $request->org;
            $sessiontoken = $request->sessiontoken;
            $playerid = $request->playerid;
            $amount = $request->amount;
            $reference = $request->reference;
            $cat1 = $request->cat1;
            $cat2 = $request->cat2;
            $cat3 = $request->cat3;
            $cat4 = $request->cat4;
            $cat5 = $request->cat5;
            $record = $this->checkreference($reference);
            if ($record)
            {
                return [
                    'code' => 1000,
                ];
            }
            $user = \VanguardLTE\User::lockForUpdate()->find($playerid);
            if (!$user || !$user->hasRole('user')){
                return [
                    'code' => 1000,
                ];
            }
            if ($user->balance < $amount)
            {
                return [
                    'code' => 1006,
                ];
            }

            $user->balance = floatval(sprintf('%.4f', $user->balance - floatval($amount)));
            $user->save();

            \VanguardLTE\StatGame::create([
                'user_id' => $user->id, 
                'balance' => floatval($user->balance), 
                'bet' => floatval($amount), 
                'win' => 0, 
                'game' => $cat4 . '_' . $cat1, 
                'type' => 'slot',
                'percent' => 0, 
                'percent_jps' => 0, 
                'percent_jpg' => 0, 
                'profit' => 0, 
                'denomination' => 0, 
                'shop_id' => $user->shop_id
            ]);

            $req = $request->all();

            $transaction = \VanguardLTE\ATATransaction::create([
                'reference' => $reference, 
                'timestamp' => $this->microtime_string(),
                'data' => json_encode($req),
                'refund' => 0
            ]);

            return [
                'code' => 0,
                'data' => [
                    'gender' => 'M',
                    'playerId' => $user->id,
                    'organization' => config('app.ata_org'),
                    'balance' => $user->balance,
                    'currency' => 'KRW',
                    'applicableBonus' => 0,
                    'homeCurrency' => 'KRW',
                    'country' => 'ko',
                ],
            ];
        }

        public function cancelwager($request)
        {
            $org = $request->org;
            $playerid = $request->playerid;
            $reference = $request->reference;
            $record = $this->checkreference($reference);
            if ($record == null || $record->refund == 1)
            {
                return [
                    'code' => 1000,
                ];
            }
            $data = json_decode($record->data);
            $amount = $data->amount;

            $user = \VanguardLTE\User::lockForUpdate()->find($playerid);
            if (!$user || !$user->hasRole('user')){
                return [
                    'code' => 1000,
                ];
            }

            $user->balance = floatval(sprintf('%.4f', $user->balance + floatval($amount)));
            $user->save();


            \VanguardLTE\StatGame::create([
                'user_id' => $user->id, 
                'balance' => floatval($user->balance), 
                'bet' => 0, 
                'win' => floatval($amount), 
                'game' => $data->cat4 . '_' . $data->cat1 . ' refund', 
                'type' => 'slot',
                'percent' => 0, 
                'percent_jps' => 0, 
                'percent_jpg' => 0, 
                'profit' => 0, 
                'denomination' => 0, 
                'shop_id' => $user->shop_id
            ]);

            $record->update(['refund' => 1]);

            return [
                'code' => 0,
                'data' => [
                    'playerId' => $user->id,
                    'organization' => config('app.ata_org'),
                    'balance' => $user->balance,
                    'currency' => 'KRW',
                    'bonus' => 0,
                ],
            ];
        }

        public function appendwagerresult($request)
        {
            $org = $request->org;
            $playerid = $request->playerid;
            $amount = $request->amount;
            $reference = $request->reference;
            $cat1 = $request->cat1;
            $cat2 = $request->cat2;
            $cat3 = $request->cat3;
            $cat4 = $request->cat4;
            $cat5 = $request->cat5;
            
            $user = \VanguardLTE\User::lockForUpdate()->find($playerid);
            if (!$user || !$user->hasRole('user')){
                return [
                    'code' => 1000,
                ];
            }
            

            $user->balance = floatval(sprintf('%.4f', $user->balance + floatval($amount)));
            $user->save();

            \VanguardLTE\StatGame::create([
                'user_id' => $user->id, 
                'balance' => floatval($user->balance), 
                'bet' => 0, 
                'win' => floatval($amount), 
                'game' => $cat4 . '_' . $cat1, 
                'type' => 'slot',
                'percent' => 0, 
                'percent_jps' => 0, 
                'percent_jpg' => 0, 
                'profit' => 0, 
                'denomination' => 0, 
                'shop_id' => $user->shop_id
            ]);

            return [
                'code' => 0,
                'data' => [
                    'organization' => config('app.ata_org'),
                    'playerId' => $user->id,
                    'currency' => 'KRW',
                    'applicableBonus' => 0,
                    'homeCurrency' => 'KRW',
                    'balance' => $user->balance,
                    'bonus' => 0,
                ],
            ];
        }

        public function endwager($request)
        {
            $org = $request->org;
            $playerid = $request->playerid;
            $amount = $request->amount;
            $reference = $request->reference;
            $cat1 = $request->cat1;
            $cat2 = $request->cat2;
            $cat3 = $request->cat3;
            $cat4 = $request->cat4;
            $cat5 = $request->cat5;
            
            $user = \VanguardLTE\User::lockForUpdate()->find($playerid);
            if (!$user || !$user->hasRole('user')){
                return [
                    'code' => 1000,
                ];
            }
            

            $user->balance = floatval(sprintf('%.4f', $user->balance + floatval($amount)));
            $user->save();

            \VanguardLTE\StatGame::create([
                'user_id' => $user->id, 
                'balance' => floatval($user->balance), 
                'bet' => 0, 
                'win' => floatval($amount), 
                'game' => $cat4 . '_' . $cat1, 
                'type' => 'slot',
                'percent' => 0, 
                'percent_jps' => 0, 
                'percent_jpg' => 0, 
                'profit' => 0, 
                'denomination' => 0, 
                'shop_id' => $user->shop_id
            ]);

            return [
                'code' => 0,
                'data' => [
                    'organization' => config('app.ata_org'),
                    'playerId' => $user->id,
                    'currency' => 'KRW',
                    'applicableBonus' => 0,
                    'homeCurrency' => 'KRW',
                    'balance' => $user->balance,
                    'bonus' => 0,
                ],
            ];
        }

        public function campaignpayout($request)
        {
            $org = $request->org;
            $playerid = $request->playerid;
            $amount = $request->cash;
            $reference = $request->reference;
            $cat1 = $request->cat1;
            $cat2 = $request->cat2;
            $cat3 = $request->cat3;
            $cat4 = $request->cat4;
            $cat5 = $request->cat5;
            
            $user = \VanguardLTE\User::lockForUpdate()->find($playerid);
            if (!$user || !$user->hasRole('user')){
                return [
                    'code' => 1000,
                ];
            }
            

            $user->balance = floatval(sprintf('%.4f', $user->balance + floatval($amount)));
            $user->save();

            \VanguardLTE\StatGame::create([
                'user_id' => $user->id, 
                'balance' => floatval($user->balance), 
                'bet' => 0, 
                'win' => floatval($amount), 
                'game' => $cat4 . '_' . $cat1 . ' bonus', 
                'type' => 'slot',
                'percent' => 0, 
                'percent_jps' => 0, 
                'percent_jpg' => 0, 
                'profit' => 0, 
                'denomination' => 0, 
                'shop_id' => $user->shop_id
            ]);

            return [
                'code' => 0,
                'data' => [
                    'organization' => config('app.ata_org'),
                    'playerId' => $user->id,
                    'currency' => 'KRW',
                    'applicableBonus' => 0,
                    'homeCurrency' => 'KRW',
                    'balance' => $user->balance,
                    'bonus' => 0,
                ],
            ];
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
            $slotgameString = ['Slot game', 'Video Slot'];
            if ($resultdata['code'] == 0){

                foreach ($resultdata['data'] as $game)
                {
                    if (in_array($game['GameType'] , $slotgameString) && $game['GameStatus'] == 1){ // need to check the string
                        $gameList[] = [
                            'provider' => 'ata',
                            'gamecode' => $game['GameId'],
                            'name' => $game['LogPara'],
                            'title' => __('gameprovider.'.$game['GameName']),
                            // 'icon' => $game['GameIcon'],
                            'icon' => '/frontend/Default/ico/ata/'.$href.'/'. $game['GameId'] . '.png',
                        ];
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
                $response = Http::asForm()->post(config('app.ata_api') . '/launchClient.html', $data);
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
                    return $result['data']['launchurl'];
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

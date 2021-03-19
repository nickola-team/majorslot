<?php 
namespace VanguardLTE\Http\Controllers\Web\GameProviders
{
    use Illuminate\Support\Facades\Http;
    class PPController extends \VanguardLTE\Http\Controllers\Controller
    {
        /*
        * UTILITY FUNCTION
        */
        public static function calcHash($params)
        {
            ksort($params);
            $str_params = implode('&', array_map(
                function ($v, $k) {
                    return $k.'='.$v;
                }, 
                $params, 
                array_keys($params)
            ));
            $calc_hash = md5($str_params . config('app.ppsecretkey'));
            return $calc_hash;
        }
        public function checkreference($reference)
        {
            $record = \VanguardLTE\PPTransaction::Where('reference',$reference)->get()->first();
            return $record;
        }

        /*
        * FROM Pragmatic Play, BACK API
        */

        public function auth(\Illuminate\Http\Request $request)
        {
            $token = $request->token;
            $providerId = $request->providerId;
            if (!$token || !$providerId)
            {
                return response()->json([
                    'error' => 7,
                    'description' => 'paramenter incorrect']);
            }


            $user = \VanguardLTE\User::Where('username',$token)->get()->first();
            if (!$user || !$user->hasRole('user')){
                return response()->json([
                    'error' => 2,
                    'description' => 'player not found']);
            }

            return response()->json([
                'userId' => $user->id,
                'currency' => 'KRW',
                'cash' => floatval($user->balance),
                'bonus' => 0,
                'error' => 0,
                'description' => 'success']);
        }

        public function balance(\Illuminate\Http\Request $request)
        {
            $userId = $request->userId;
            $providerId = $request->providerId;
            if (!$userId || !$providerId)
            {
                return response()->json([
                    'error' => 7,
                    'description' => 'paramenter incorrect']);
            }

            $user = \VanguardLTE\User::find($userId);
            if (!$user || !$user->hasRole('user')){
                return response()->json([
                    'error' => 2,
                    'description' => 'player not found']);
            }

            return response()->json([
                'currency' => 'KRW',
                'cash' => floatval($user->balance),
                'bonus' => 0,
                'error' => 0,
                'description' => 'success']);
        }

        public function bet(\Illuminate\Http\Request $request)
        {
            $userId = $request->userId;
            $gameId = $request->gameId;
            $roundId = $request->roundId;
            $amount = $request->amount;
            $reference = $request->reference;
            $providerId = $request->providerId;
            $timestamp = $request->timestamp;
            if (!$userId || !$gameId || !$roundId || !$amount || !$reference || !$providerId || !$timestamp || $amount < 0)
            {
                return response()->json([
                    'error' => 7,
                    'description' => 'paramenter incorrect']);
            }

            $user = \VanguardLTE\User::find($userId);
            if (!$user || !$user->hasRole('user')){
                return response()->json([
                    'error' => 2,
                    'description' => 'player not found']);
            }

            $transaction = $this->checkreference($reference);
            if ($transaction)
            {
                return response()->json([
                    'transactionId' => $transaction->timestamp,
                    'currency' => 'KRW',
                    'cash' => floatval($user->balance),
                    'bonus' => 0,
                    'usedPromo' => 0,
                    'error' => 0,
                    'description' => 'success']);
            }

            if ($user->balance < $amount)
            {
                return response()->json([
                    'error' => 1,
                    'description' => 'insufficient balance']);
            }

            $user->balance = floatval(sprintf('%.4f', $user->balance - floatval($amount)));
            $user->save();

            $transaction = \VanguardLTE\PPTransaction::create([
                'reference' => $reference, 
                'timestamp' => time(),
                'data' => json_encode($request->all())
            ]);

            return response()->json([
                'transactionId' => $transaction->timestamp,
                'currency' => 'KRW',
                'cash' => floatval($user->balance),
                'bonus' => 0,
                'usedPromo' => 0,
                'error' => 0,
                'description' => 'success']);
        }

        public function result(\Illuminate\Http\Request $request)
        {
            $userId = $request->userId;
            $gameId = $request->gameId;
            $roundId = $request->roundId;
            $amount = $request->amount;
            $reference = $request->reference;
            $providerId = $request->providerId;
            $timestamp = $request->timestamp;
            $roundDetails = $request->roundDetails;
            if (!$userId || !$gameId || !$roundId || !$amount || !$reference || !$providerId || !$timestamp || $amount < 0)
            {
                return response()->json([
                    'error' => 7,
                    'description' => 'paramenter incorrect']);
            }

            $user = \VanguardLTE\User::find($userId);
            if (!$user || !$user->hasRole('user')){
                return response()->json([
                    'error' => 2,
                    'description' => 'player not found']);
            }
            $transaction = $this->checkreference($reference);
            if ($transaction)
            {
                return response()->json([
                    'transactionId' => $transaction->timestamp,
                    'currency' => 'KRW',
                    'cash' => floatval($user->balance),
                    'bonus' => 0,
                    'error' => 0,
                    'description' => 'success']);
            }


            $user->balance = floatval(sprintf('%.4f', $user->balance + floatval($amount)));
            $user->save();

            $transaction = \VanguardLTE\PPTransaction::create([
                'reference' => $reference, 
                'timestamp' => time(),
                'data' => json_encode($request->all())
            ]);

            return response()->json([
                'transactionId' => $transaction->timestamp,
                'currency' => 'KRW',
                'cash' => floatval($user->balance),
                'bonus' => 0,
                'error' => 0,
                'description' => 'success']);
        }
        public function bonuswin(\Illuminate\Http\Request $request)
        {
            $userId = $request->userId;
            $amount = $request->amount;
            $reference = $request->reference;
            $providerId = $request->providerId;
            $timestamp = $request->timestamp;
            if (!$userId ||  !$amount || !$reference || !$providerId || !$timestamp || $amount < 0)
            {
                return response()->json([
                    'error' => 7,
                    'description' => 'paramenter incorrect']);
            }

            $user = \VanguardLTE\User::find($userId);
            if (!$user || !$user->hasRole('user')){
                return response()->json([
                    'error' => 2,
                    'description' => 'player not found']);
            }

            $transaction = $this->checkreference($reference);
            if ($transaction)
            {
                return response()->json([
                    'transactionId' => $transaction->timestamp,
                    'currency' => 'KRW',
                    'cash' => floatval($user->balance),
                    'bonus' => 0,
                    'error' => 0,
                    'description' => 'success']);
            }


            $user->balance = floatval(sprintf('%.4f', $user->balance + floatval($amount)));
            $user->save();
            $transaction = \VanguardLTE\PPTransaction::create([
                'reference' => $reference, 
                'timestamp' => time(),
                'data' => json_encode($request->all())
            ]);

            return response()->json([
                'transactionId' => $transaction->timestamp,
                'currency' => 'KRW',
                'cash' => floatval($user->balance),
                'bonus' => 0,
                'error' => 0,
                'description' => 'success']);
        }

        public function jackpotwin(\Illuminate\Http\Request $request)
        {
            $userId = $request->userId;
            $gameId = $request->gameId;
            $roundId = $request->roundId;
            $amount = $request->amount;
            $reference = $request->reference;
            $providerId = $request->providerId;
            $timestamp = $request->timestamp;
            $jackpotId = $request->jackpotId;
            if (!$userId || !$gameId || !$roundId || !$amount || !$reference || !$providerId || !$timestamp || !$jackpotId || $amount < 0)
            {
                return response()->json([
                    'error' => 7,
                    'description' => 'paramenter incorrect']);
            }

            $user = \VanguardLTE\User::find($userId);
            if (!$user || !$user->hasRole('user')){
                return response()->json([
                    'error' => 2,
                    'description' => 'player not found']);
            }

            $transaction = $this->checkreference($reference);
            if ($transaction)
            {
                return response()->json([
                    'transactionId' => $transaction->timestamp,
                    'currency' => 'KRW',
                    'cash' => floatval($user->balance),
                    'bonus' => 0,
                    'error' => 0,
                    'description' => 'success']);
            }

            $user->balance = floatval(sprintf('%.4f', $user->balance + floatval($amount)));
            $user->save();
            $transaction = \VanguardLTE\PPTransaction::create([
                'reference' => $reference, 
                'timestamp' => time(),
                'data' => json_encode($request->all())
            ]);

            return response()->json([
                'transactionId' => $transaction->timestamp,
                'currency' => 'KRW',
                'cash' => floatval($user->balance),
                'bonus' => 0,
                'error' => 0,
                'description' => 'success']);
        }

        public function endround(\Illuminate\Http\Request $request)
        {
            $userId = $request->userId;
            $gameId = $request->gameId;
            $roundId = $request->roundId;
            $providerId = $request->providerId;
            if (!$userId || !$gameId || !$roundId || !$providerId)
            {
                return response()->json([
                    'error' => 7,
                    'description' => 'paramenter incorrect']);
            }

            return response()->json([
                'cash' => floatval($user->balance),
                'bonus' => 0,
                'error' => 0,
                'description' => 'success']);
        }

        public function refund(\Illuminate\Http\Request $request)
        {
            $userId = $request->userId;
            $reference = $request->reference;
            $providerId = $request->providerId;
            if (!$userId ||  !$reference || !$providerId )
            {
                return response()->json([
                    'error' => 7,
                    'description' => 'paramenter incorrect']);
            }

            $user = \VanguardLTE\User::find($userId);
            if (!$user || !$user->hasRole('user')){
                return response()->json([
                    'error' => 2,
                    'description' => 'player not found']);
            }

            $transaction = $this->checkreference($reference);
            if (!$transaction)
            {
                return response()->json([
                    'error' => 7,
                    'description' => 'reference not found']);
            } 
            if ($transaction->refund > 0)
            {
                return response()->json([
                    'transactionId' => $transaction->timestamp,
                    'error' => 0,
                    'description' => 'success']);
            }

            $data = json_decode($transaction->data, true);
            if (!isset($data['amount']))
            {
                return response()->json([
                    'error' => 7,
                    'description' => 'bad reference to refund']);
            }


            $user->balance = floatval(sprintf('%.4f', $user->balance + floatval($data['amount'])));
            $user->save();
            $transaction->refund = 1;
            $transaction->save();



            return response()->json([
                'transactionId' => $transaction->timestamp,
                'error' => 0,
                'description' => 'success']);
        }

        public function promowin(\Illuminate\Http\Request $request)
        {
            $userId = $request->userId;
            $amount = $request->amount;
            $reference = $request->reference;
            $providerId = $request->providerId;
            $timestamp = $request->timestamp;
            $campaignId = $request->campaignId;
            $campaignType = $request->campaignType;
            $currency = $request->currencty;
            if (!$userId || !$campaignId || !$campaignType || !$amount || !$reference || !$providerId || !$timestamp || $amount < 0)
            {
                return response()->json([
                    'error' => 7,
                    'description' => 'paramenter incorrect']);
            }

            $user = \VanguardLTE\User::find($userId);
            if (!$user || !$user->hasRole('user')){
                return response()->json([
                    'error' => 2,
                    'description' => 'player not found']);
            }
            $transaction = $this->checkreference($reference);
            if ($transaction)
            {
                return response()->json([
                    'transactionId' => $transaction->timestamp,
                    'currency' => 'KRW',
                    'cash' => floatval($user->balance),
                    'bonus' => 0,
                    'error' => 0,
                    'description' => 'success']);
            }

            $user->balance = floatval(sprintf('%.4f', $user->balance + floatval($amount)));
            $user->save();
            $transaction = \VanguardLTE\PPTransaction::create([
                'reference' => $reference, 
                'timestamp' => time(),
                'data' => json_encode($request->all())
            ]);

            return response()->json([
                'transactionId' => $transaction->timestamp,
                'currency' => 'KRW',
                'cash' => floatval($user->balance),
                'bonus' => 0,
                'error' => 0,
                'description' => 'success']);
        }

        /*
        * FROM CONTROLLER, API
        */
        
        public static function getgamelist()
        {
            $data = [
                'secureLogin' => config('app.ppsecurelogin'),
            ];
            $data['hash'] = PPController::calcHash($data);
            $response = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded'
                ])->get(config('app.ppapi') . '/getCasinoGames/', $data);
            if (!$response->ok())
            {
                return $response->body();
            }
            $data = $response->json();
            if ($data['error'] == "0"){
                $gameList = [];
                foreach ($data['gameList'] as $game)
                {
                    if ($game['gameTypeID'] == "vs")
                    {
                        $gameList[] = [
                            'provider' => 'pp',
                            'gamecode' => $game['gameID'],
                            'name' => preg_replace('/\s+/', '', $game['gameName']),
                            'title' => $game['gameName'],
                            'icon' => config('app.ppgameserver') . '/game_pic/rec/325/'. $game['gameID'] . '.png'
                        ];
                    }
                }
                return $gameList;
            }
            return [];
        }

        public static function getgamelink($gamecode)
        {
            $detect = new \Detection\MobileDetect();
            $key = [
                'token' => auth()->user()->username,
                'symbol' => $gamecode,
                'language' => 'ko',
                'technology' => 'H5',
                'platform' => ($detect->isMobile() || $detect->isTablet())?'MOBILE':'WEB',
                'cashierUrl' => \URL::to('/'),
                'lobbyUrl' => \URL::to('/'),
            ];
            $str_params = implode('&', array_map(
                function ($v, $k) {
                    return $k.'='.$v;
                }, 
                $key,
                array_keys($key)
            ));
            $url = config('app.ppgameserver') . '/gs2c/playGame.do?key='.urlencode($str_params) . 'stylename=rare_stake';
            return ['error' => false, 'data' => ['url' => $url]];
        }
    }

}

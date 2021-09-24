<?php 
namespace VanguardLTE\Http\Controllers\Web\GameProviders
{
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Log;
    class CQ9Controller extends \VanguardLTE\Http\Controllers\Controller
    {
        /*
        * UTILITY FUNCTION
        */
        function validRFC3339Date($date) {
            if (preg_match('/^([0-9]+)-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])[Tt]([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9]|60)(\.[0-9]+)?(([Zz])|([\+|\-]([01][0-9]|2[0-3]):[0-5][0-9]))$/', $date)) {
                return true;
            } else {
                return false;
            }
        }

        public function checkmtcode($mtcode)
        {
            $record = \VanguardLTE\CQ9Transaction::where('mtcode',$mtcode)->get()->first();
            return $record;
        }

        public function generateCode($limit){
            $code = 0;
            for($i = 0; $i < $limit; $i++) { $code .= mt_rand(0, 9); }
            return $code;
        }

        public static function gamecodetoname($code)
        {
            $gamelist = CQ9Controller::getgamelist('cq9');
            $gamename = $code;
            if ($gamelist)
            {
                foreach($gamelist as $game)
                {
                    if ($game['gamecode'] == $code)
                    {
                        $gamename = $game['name'];
                        break;
                    }
                }
            }
            return $gamename;
        }

        public static function gamenametocode($name)
        {
            $gamelist = CQ9Controller::getgamelist('cq9');
            $gamecode = $name = preg_replace('/\s+/', '', $name);
            if ($gamelist)
            {
                foreach($gamelist as $game)
                {
                    if ($game['name'] == $name)
                    {
                        $gamecode = $game['gamecode'];
                        break;
                    }
                }
            }
            return $gamecode;
        }
        public function microtime_string()
        {
            $microstr = sprintf('%.4f', microtime(TRUE));
            $microstr = str_replace('.', '', $microstr);
            return $microstr;
        }

        /*
        * FROM CQ9, BACK API
        */
        public function bet(\Illuminate\Http\Request $request)
        {
            $account = $request->account;
            $eventTime = $request->eventTime;
            $gamehall = $request->gamehall;
            $gamecode = $request->gamecode;
            $roundid = $request->roundid;
            $amount = $request->amount;
            $mtcode = $request->mtcode;
            $session = $request->session;
            $platform = $request->platform;

            if (!$account || !$eventTime || !$gamehall || !$gamecode || !$roundid || !$amount || !$mtcode || $amount<0){
                return response()->json([
                    'data' => null,
                    'status' => [
                        'code' => '1003',
                        'message' => 'incorrect parameter',
                        'datetime' => date(DATE_RFC3339_EXTENDED)
                    ]
                ]);
            }

            if (!$this->validRFC3339Date($eventTime))
            {
                return response()->json([
                    'data' => null,
                    'status' => [
                        'code' => '1004',
                        'message' => 'wrong time format',
                        'datetime' => date(DATE_RFC3339_EXTENDED)
                    ]
                ]);
            }
            \DB::beginTransaction();

            if ($this->checkmtcode($mtcode))
            {
                return response()->json([
                    'data' => null,
                    'status' => [
                        'code' => '2009',
                        'message' => 'Duplicate MTCode.',
                        'datetime' => date(DATE_RFC3339_EXTENDED)
                    ]
                ]);
            }


            $transaction = [
                '_id' => $this->generateCode(24),
                'action' => 'bet',
                'target' => [
                    'account' => $account,
                ],
                'status' => [
                    'createtime' => date(DATE_RFC3339_EXTENDED),
                    'endtime' => date(DATE_RFC3339_EXTENDED),
                    'status' => 'success',
                    'message' => 'success'
                ],
                'before' => 0,
                'balance' => 0,
                'currency' => 'KRW',

                'event' => [
                    [
                        'mtcode' => $mtcode,
                        'amount' => floatval($amount),
                        'eventtime' => $eventTime,
                    ]
                ]
            ];


            $user = \VanguardLTE\User::lockForUpdate()->where('id',$account)->get()->first();
            if (!$user || !$user->hasRole('user')){
                $transaction['status']['endtime'] = date(DATE_RFC3339_EXTENDED);
                $transaction['status']['status'] = 'failed';
                $transaction['status']['message'] = 'failed';
                \VanguardLTE\CQ9Transaction::create([
                    'mtcode' => $mtcode, 
                    'timestamp' => $this->microtime_string(),
                    'data' => json_encode($transaction)
                ]);
                \DB::commit();

                return response()->json([
                    'data' => null,
                    'status' => [
                        'code' => '1006',
                        'message' => 'player not found',
                        'datetime' => date(DATE_RFC3339_EXTENDED)
                    ]
                ]);
            }
            if ($user->balance < $amount)
            {
                $transaction['status']['endtime'] = date(DATE_RFC3339_EXTENDED);
                $transaction['status']['status'] = 'failed';
                $transaction['status']['message'] = 'failed';
                \VanguardLTE\CQ9Transaction::create([
                    'mtcode' => $mtcode, 
                    'timestamp' => $this->microtime_string(),
                    'data' => json_encode($transaction)
                ]);
                \DB::commit();

                return response()->json([
                    'data' => null,
                    'status' => [
                        'code' => '1005',
                        'message' => 'insufficient balance',
                        'datetime' => date(DATE_RFC3339_EXTENDED)
                    ]
                ]);
            }
            $transaction['before'] = floatval($user->balance);
            
            $user->balance = floatval(sprintf('%.4f', $user->balance - floatval($amount)));
            $user->save();

            $transaction['balance'] = floatval($user->balance);
            $transaction['status']['endtime'] = date(DATE_RFC3339_EXTENDED);
            \VanguardLTE\CQ9Transaction::create([
                'mtcode' => $mtcode, 
                'timestamp' => $this->microtime_string(),
                'data' => json_encode($transaction)
            ]);
            $category = \VanguardLTE\Category::where(['provider' => 'cq9', 'shop_id' => 0, 'href' => 'cq9'])->first();

            \VanguardLTE\StatGame::create([
                'user_id' => $user->id, 
                'balance' => floatval($user->balance), 
                'bet' => floatval($amount), 
                'win' => 0, 
                'game' => CQ9Controller::gamecodetoname($gamecode) . '_' . $gamehall, 
                'percent' => 0, 
                'percent_jps' => 0, 
                'percent_jpg' => 0, 
                'profit' => 0, 
                'denomination' => 0, 
                'shop_id' => $user->shop_id,
                'category_id' => isset($category)?$category->id:0,
                'game_id' => $gamecode,
                'roundid' => 0,
            ]);

            \DB::commit();
            return response()->json([
                'data' => ['balance' => floatval($user->balance), 'currency' => 'KRW'],
                'status' => [
                    'code' => '0',
                    'message' => 'Success',
                    'datetime' => date(DATE_RFC3339_EXTENDED)
                ]
            ]);
        }
        public function endround(\Illuminate\Http\Request $request)
        {
            $account = $request->account;
            $gamehall = $request->gamehall;
            $gamecode = $request->gamecode;
            $roundid = $request->roundid;
            $data = $request->data;
            $createTime = $request->createTime;
            $freegame = $request->freegame;
            $jackpot = $request->jackpot;
            $jackpotcontribution = $request->jackpotcontribution;

            if (!$account ||  !$gamehall || !$gamecode || !$roundid || !$data || !$createTime){
                return response()->json([
                    'data' => null,
                    'status' => [
                        'code' => '1003',
                        'message' => 'incorrect parameter',
                        'datetime' => date(DATE_RFC3339_EXTENDED)
                    ]
                ]);
            }
            \DB::beginTransaction();


            if (!$this->validRFC3339Date($createTime))
            {
                return response()->json([
                    'data' => null,
                    'status' => [
                        'code' => '1004',
                        'message' => 'wrong time format',
                        'datetime' => date(DATE_RFC3339_EXTENDED)
                    ]
                ]);
            }
            $transaction = [
                '_id' => $this->generateCode(24),
                'action' => 'endround',
                'target' => [
                    'account' => $account,
                ],
                'status' => [
                    'createtime' => date(DATE_RFC3339_EXTENDED),
                    'endtime' => date(DATE_RFC3339_EXTENDED),
                    'status' => 'success',
                    'message' => 'success'
                ],
                'before' => 0,
                'balance' => 0,
                'currency' => 'KRW',
            ];


            $user = \VanguardLTE\User::lockForUpdate()->where('id',$account)->get()->first();
            if (!$user || !$user->hasRole('user')){
                \DB::commit();
                return response()->json([
                    'data' => null,
                    'status' => [
                        'code' => '1006',
                        'message' => 'player not found',
                        'datetime' => date(DATE_RFC3339_EXTENDED)
                    ]
                ]);
            }

            $endrounddata = json_decode($data, true);
            $totalamount = 0;
            $mtcodes_record = [];
            if ($endrounddata){
                foreach ($endrounddata as $round)
                {
                    if ($round['amount'] < 0)
                    {
                        \DB::commit();
                        return response()->json([
                            'data' => null,
                            'status' => [
                                'code' => '1003',
                                'message' => 'amount is negative',
                                'datetime' => date(DATE_RFC3339_EXTENDED)
                            ]
                        ]);
                    }
                    if (!$this->validRFC3339Date($round['eventtime']))
                    {
                        \DB::commit();
                        return response()->json([
                            'data' => null,
                            'status' => [
                                'code' => '1004',
                                'message' => 'wrong time format',
                                'datetime' => date(DATE_RFC3339_EXTENDED)
                            ]
                        ]);
                    }
                    $mtcodes_record[] = $round['mtcode'];
                    $totalamount += $round['amount'];
                }
            }
            $transaction['before'] = floatval($user->balance);

            $user->balance = floatval(sprintf('%.4f', $user->balance + floatval($totalamount)));
            $user->save();

            $transaction['balance'] = floatval($user->balance);
            $transaction['event'] = $endrounddata;
            $transaction['status']['endtime'] = date(DATE_RFC3339_EXTENDED);
            foreach ($mtcodes_record as $record)
            {
                if ($this->checkmtcode($record))
                {
                    $user->balance = floatval(sprintf('%.4f', $user->balance - floatval($totalamount)));
                    $user->save();
                    \DB::commit();
                    return response()->json([
                        'data' => null,
                        'status' => [
                            'code' => '2009',
                            'message' => 'Duplicate MTCode.',
                            'datetime' => date(DATE_RFC3339_EXTENDED)
                        ]
                    ]);
                }
                \VanguardLTE\CQ9Transaction::create([
                    'mtcode' => $record, 
                    'timestamp' => $this->microtime_string(),
                    'data' => json_encode($transaction)
                ]);
            }

            if (floatval($totalamount) > 0) {
                $category = \VanguardLTE\Category::where(['provider' => 'cq9', 'shop_id' => 0, 'href' => 'cq9'])->first();
                \VanguardLTE\StatGame::create([
                    'user_id' => $user->id, 
                    'balance' => floatval($user->balance), 
                    'bet' => 0, 
                    'win' => floatval($totalamount), 
                    'game' => CQ9Controller::gamecodetoname($gamecode) .'_' . $gamehall, 
                    'percent' => 0, 
                    'percent_jps' => 0, 
                    'percent_jpg' => 0, 
                    'profit' => 0, 
                    'denomination' => 0, 
                    'shop_id' => $user->shop_id,
                    'category_id' => isset($category)?$category->id:0,
                    'game_id' => $gamecode,
                    'roundid' => 0,
                ]);
            }

            \DB::commit();

            return response()->json([
                'data' => ['balance' => floatval($user->balance), 'currency' => 'KRW'],
                'status' => [
                    'code' => '0',
                    'message' => 'Success',
                    'datetime' => date(DATE_RFC3339_EXTENDED)
                ]
            ]);
        }

        public function debit(\Illuminate\Http\Request $request)
        {
            $account = $request->account;
            $eventTime = $request->eventTime;
            $gamehall = $request->gamehall;
            $gamecode = $request->gamecode;
            $roundid = $request->roundid;
            $amount = $request->amount;
            $mtcode = $request->mtcode;

            if (!$account || !$eventTime || !$gamehall || !$gamecode || !$roundid || !$amount || !$mtcode || $amount<0){
                return response()->json([
                    'data' => null,
                    'status' => [
                        'code' => '1003',
                        'message' => 'incorrect parameter',
                        'datetime' => date(DATE_RFC3339_EXTENDED)
                    ]
                ]);
            }
            if (!$this->validRFC3339Date($eventTime))
            {
                return response()->json([
                    'data' => null,
                    'status' => [
                        'code' => '1004',
                        'message' => 'wrong time format',
                        'datetime' => date(DATE_RFC3339_EXTENDED)
                    ]
                ]);
            }

            \DB::beginTransaction();


            if ($this->checkmtcode($mtcode))
            {
                return response()->json([
                    'data' => null,
                    'status' => [
                        'code' => '2009',
                        'message' => 'Duplicate MTCode.',
                        'datetime' => date(DATE_RFC3339_EXTENDED)
                    ]
                ]);
            }


            $transaction = [
                '_id' => $this->generateCode(24),
                'action' => 'debit',
                'target' => [
                    'account' => $account,
                ],
                'status' => [
                    'createtime' => date(DATE_RFC3339_EXTENDED),
                    'endtime' => date(DATE_RFC3339_EXTENDED),
                    'status' => 'success',
                    'message' => 'success'
                ],
                'before' => 0,
                'balance' => 0,
                'currency' => 'KRW',

                'event' => [
                    [
                        'mtcode' => $mtcode,
                        'amount' => floatval($amount),
                        'eventtime' => $eventTime,
                    ]
                ]
            ];


            $user = \VanguardLTE\User::lockForUpdate()->where('id',$account)->get()->first();
            if (!$user || !$user->hasRole('user')){
                $transaction['status']['endtime'] = date(DATE_RFC3339_EXTENDED);
                $transaction['status']['status'] = 'failed';
                $transaction['status']['message'] = 'failed';
                \VanguardLTE\CQ9Transaction::create([
                    'mtcode' => $mtcode, 
                    'timestamp' => $this->microtime_string(),
                    'data' => json_encode($transaction)
                ]);
                \DB::commit();

                return response()->json([
                    'data' => null,
                    'status' => [
                        'code' => '1006',
                        'message' => 'player not found',
                        'datetime' => date(DATE_RFC3339_EXTENDED)
                    ]
                ]);
            }
            if ($user->balance < $amount)
            {
                $transaction['status']['endtime'] = date(DATE_RFC3339_EXTENDED);
                $transaction['status']['status'] = 'failed';
                $transaction['status']['message'] = 'failed';
                \VanguardLTE\CQ9Transaction::create([
                    'mtcode' => $mtcode, 
                    'timestamp' => $this->microtime_string(),
                    'data' => json_encode($transaction)
                ]);
                \DB::commit();

                return response()->json([
                    'data' => null,
                    'status' => [
                        'code' => '1005',
                        'message' => 'insufficient balance',
                        'datetime' => date(DATE_RFC3339_EXTENDED)
                    ]
                ]);
            }
            $transaction['before'] = floatval($user->balance);
            
            $user->balance = floatval(sprintf('%.4f', $user->balance - floatval($amount)));
            $user->save();

            $transaction['balance'] = floatval($user->balance);
            $transaction['status']['endtime'] = date(DATE_RFC3339_EXTENDED);
            \VanguardLTE\CQ9Transaction::create([
                'mtcode' => $mtcode, 
                'timestamp' => $this->microtime_string(),
                'data' => json_encode($transaction)
            ]);
            $category = \VanguardLTE\Category::where(['provider' => 'cq9', 'shop_id' => 0, 'href' => 'cq9'])->first();
            \VanguardLTE\StatGame::create([
                'user_id' => $user->id, 
                'balance' => floatval($user->balance), 
                'bet' => floatval($amount), 
                'win' => 0, 
                'game' => CQ9Controller::gamecodetoname($gamecode) . '_' . $gamehall . ' debit', 
                'percent' => 0, 
                'percent_jps' => 0, 
                'percent_jpg' => 0, 
                'profit' => 0, 
                'denomination' => 0, 
                'shop_id' => $user->shop_id,
                'category_id' => isset($category)?$category->id:0,
                'game_id' => $gamecode,
                'roundid' => 0,
            ]);

            \DB::commit();

            
            return response()->json([
                'data' => ['balance' => floatval($user->balance), 'currency' => 'KRW'],
                'status' => [
                    'code' => '0',
                    'message' => 'Success',
                    'datetime' => date(DATE_RFC3339_EXTENDED)
                ]
            ]);

        }

        public function credit(\Illuminate\Http\Request $request)
        {
            $account = $request->account;
            $eventTime = $request->eventTime;
            $gamehall = $request->gamehall;
            $gamecode = $request->gamecode;
            $roundid = $request->roundid;
            $amount = $request->amount;
            $mtcode = $request->mtcode;

            if (!$account || !$eventTime || !$gamehall || !$gamecode || !$roundid || !$amount || !$mtcode || $amount<0){
                return response()->json([
                    'data' => null,
                    'status' => [
                        'code' => '1003',
                        'message' => 'incorrect parameter',
                        'datetime' => date(DATE_RFC3339_EXTENDED)
                    ]
                ]);
            }
            if (!$this->validRFC3339Date($eventTime))
            {
                return response()->json([
                    'data' => null,
                    'status' => [
                        'code' => '1004',
                        'message' => 'wrong time format',
                        'datetime' => date(DATE_RFC3339_EXTENDED)
                    ]
                ]);
            }

            \DB::beginTransaction();

            if ($this->checkmtcode($mtcode))
            {
                return response()->json([
                    'data' => null,
                    'status' => [
                        'code' => '2009',
                        'message' => 'Duplicate MTCode.',
                        'datetime' => date(DATE_RFC3339_EXTENDED)
                    ]
                ]);
            }


            $transaction = [
                '_id' => $this->generateCode(24),
                'action' => 'credit',
                'target' => [
                    'account' => $account,
                ],
                'status' => [
                    'createtime' => date(DATE_RFC3339_EXTENDED),
                    'endtime' => date(DATE_RFC3339_EXTENDED),
                    'status' => 'success',
                    'message' => 'success'
                ],
                'before' => 0,
                'balance' => 0,
                'currency' => 'KRW',

                'event' => [
                    [
                        'mtcode' => $mtcode,
                        'amount' => floatval($amount),
                        'eventtime' => $eventTime,
                    ]
                ]
            ];


            $user = \VanguardLTE\User::lockForUpdate()->where('id',$account)->get()->first();
            if (!$user || !$user->hasRole('user')){
                $transaction['status']['endtime'] = date(DATE_RFC3339_EXTENDED);
                $transaction['status']['status'] = 'failed';
                $transaction['status']['message'] = 'failed';
                \VanguardLTE\CQ9Transaction::create([
                    'mtcode' => $mtcode, 
                    'timestamp' => $this->microtime_string(),
                    'data' => json_encode($transaction)
                ]);
                \DB::commit();

                return response()->json([
                    'data' => null,
                    'status' => [
                        'code' => '1006',
                        'message' => 'player not found',
                        'datetime' => date(DATE_RFC3339_EXTENDED)
                    ]
                ]);
            }
            
            $transaction['before'] = floatval($user->balance);
            $user->balance = floatval(sprintf('%.4f', $user->balance + floatval($amount)));
            $user->save();

            $transaction['balance'] = floatval($user->balance);
            $transaction['status']['endtime'] = date(DATE_RFC3339_EXTENDED);
            \VanguardLTE\CQ9Transaction::create([
                'mtcode' => $mtcode, 
                'timestamp' => $this->microtime_string(),
                'data' => json_encode($transaction)
            ]);
            $category = \VanguardLTE\Category::where(['provider' => 'cq9', 'shop_id' => 0, 'href' => 'cq9'])->first();
            \VanguardLTE\StatGame::create([
                'user_id' => $user->id, 
                'balance' => floatval($user->balance), 
                'bet' => 0, 
                'win' => floatval($amount), 
                'game' => CQ9Controller::gamecodetoname($gamecode) . '_' . $gamehall . ' credit', 
                'percent' => 0, 
                'percent_jps' => 0, 
                'percent_jpg' => 0, 
                'profit' => 0, 
                'denomination' => 0, 
                'shop_id' => $user->shop_id,
                'category_id' => isset($category)?$category->id:0,
                'game_id' => $gamecode,
                'roundid' => 0,
            ]);

            \DB::commit();

            
            return response()->json([
                'data' => ['balance' => floatval($user->balance), 'currency' => 'KRW'],
                'status' => [
                    'code' => '0',
                    'message' => 'Success',
                    'datetime' => date(DATE_RFC3339_EXTENDED)
                ]
            ]);

        }

        public function refund(\Illuminate\Http\Request $request)
        {
            $mtcode = $request->mtcode;
            if (!$mtcode)
            {
                return response()->json([
                    'data' => null,
                    'status' => [
                        'code' => '1003',
                        'message' => 'incorrect parameter',
                        'datetime' => date(DATE_RFC3339_EXTENDED)
                    ]
                ]);
            }
            \DB::beginTransaction();

            $record = $this->checkmtcode($mtcode);
            
            if (!$record)
            {
                return response()->json([
                    'data' => null,
                    'status' => [
                        'code' => '1014',
                        'message' => 'record not found',
                        'datetime' => date(DATE_RFC3339_EXTENDED)
                    ]
                ]);
            }
            if ($record->refund > 0)
            {
                return response()->json([
                    'data' => null,
                    'status' => [
                        'code' => '1015',
                        'message' => 'already refunded',
                        'datetime' => date(DATE_RFC3339_EXTENDED)
                    ]
                ]);
            }

            $data = json_decode($record->data, true);

            
            $user = \VanguardLTE\User::lockForUpdate()->where('id',$data['target']['account'])->get()->first();
            if (!$user || !$user->hasRole('user')){
                \DB::commit();
                return response()->json([
                    'data' => null,
                    'status' => [
                        'code' => '1006',
                        'message' => 'player not found',
                        'datetime' => date(DATE_RFC3339_EXTENDED)
                    ]
                ]);
            }
            $totalamount = 0;
            foreach ($data['event'] as $event) {
                $totalamount += $event['amount'];
            }
            $data['status']['status'] ='refund';
            $data['before'] = floatval($user->balance);
            
            $user->balance = floatval(sprintf('%.4f', $user->balance + floatval($totalamount)));
            $user->save();

            $data['balance'] = floatval($user->balance);
            
            $record->refund = 1;
            $record->data = json_encode($data);
            $record->save();
            $category = \VanguardLTE\Category::where(['provider' => 'cq9', 'shop_id' => 0, 'href' => 'cq9'])->first();
            \VanguardLTE\StatGame::create([
                'user_id' => $user->id, 
                'balance' => floatval($user->balance), 
                'bet' => 0, 
                'win' => floatval($totalamount), 
                'game' => 'CQ9_cq9 refund', 
                'percent' => 0, 
                'percent_jps' => 0, 
                'percent_jpg' => 0, 
                'profit' => 0, 
                'denomination' => 0, 
                'shop_id' => $user->shop_id,
                'category_id' => isset($category)?$category->id:0,
                'game_id' => 'CQ9_cq9',
                'roundid' => 0,
            ]);

            \DB::commit();

            return response()->json([
                'data' => ['balance' => floatval($user->balance), 'currency' => 'KRW'],
                'status' => [
                    'code' => '0',
                    'message' => 'Success',
                    'datetime' => date(DATE_RFC3339_EXTENDED)
                ]
            ]);
        }

        public function payoff(\Illuminate\Http\Request $request)
        {
            $account = $request->account;
            $eventTime = $request->eventTime;
            $amount = $request->amount;
            $mtcode = $request->mtcode;
            $remark = $request->remark;

            if (!$account || !$eventTime || !$amount || !$mtcode || $amount<0){
                return response()->json([
                    'data' => null,
                    'status' => [
                        'code' => '1003',
                        'message' => 'incorrect parameter',
                        'datetime' => date(DATE_RFC3339_EXTENDED)
                    ]
                ]);
            }

            if (!$this->validRFC3339Date($eventTime))
            {
                return response()->json([
                    'data' => null,
                    'status' => [
                        'code' => '1004',
                        'message' => 'wrong time format',
                        'datetime' => date(DATE_RFC3339_EXTENDED)
                    ]
                ]);
            }

            \DB::beginTransaction();


            if ($this->checkmtcode($mtcode))
            {
                return response()->json([
                    'data' => null,
                    'status' => [
                        'code' => '2009',
                        'message' => 'Duplicate MTCode.',
                        'datetime' => date(DATE_RFC3339_EXTENDED)
                    ]
                ]);
            }


            $transaction = [
                '_id' => $this->generateCode(24),
                'action' => 'payoff',
                'target' => [
                    'account' => $account,
                ],
                'status' => [
                    'createtime' => date(DATE_RFC3339_EXTENDED),
                    'endtime' => date(DATE_RFC3339_EXTENDED),
                    'status' => 'success',
                    'message' => 'success'
                ],
                'before' => 0,
                'balance' => 0,
                'currency' => 'KRW',

                'event' => [
                    [
                        'mtcode' => $mtcode,
                        'amount' => floatval($amount),
                        'eventtime' => $eventTime,
                    ]
                ]
            ];


            $user = \VanguardLTE\User::lockForUpdate()->where('id',$account)->get()->first();
            if (!$user || !$user->hasRole('user')){
                $transaction['status']['endtime'] = date(DATE_RFC3339_EXTENDED);
                $transaction['status']['status'] = 'failed';
                $transaction['status']['message'] = 'failed';
                \VanguardLTE\CQ9Transaction::create([
                    'mtcode' => $mtcode, 
                    'timestamp' => $this->microtime_string(),
                    'data' => json_encode($transaction)
                ]);
                \DB::commit();

                return response()->json([
                    'data' => null,
                    'status' => [
                        'code' => '1006',
                        'message' => 'player not found',
                        'datetime' => date(DATE_RFC3339_EXTENDED)
                    ]
                ]);
            }
            
            $transaction['before'] = floatval($user->balance);
            
            $user->balance = floatval(sprintf('%.4f', $user->balance + floatval($amount)));
            $user->save();

            $transaction['balance'] = floatval($user->balance);
            $transaction['status']['endtime'] = date(DATE_RFC3339_EXTENDED);
            \VanguardLTE\CQ9Transaction::create([
                'mtcode' => $mtcode, 
                'timestamp' => $this->microtime_string(),
                'data' => json_encode($transaction)
            ]);
            $category = \VanguardLTE\Category::where(['provider' => 'cq9', 'shop_id' => 0, 'href' => 'cq9'])->first();
            \VanguardLTE\StatGame::create([
                'user_id' => $user->id, 
                'balance' => floatval($user->balance), 
                'bet' => 0, 
                'win' => floatval($amount), 
                'game' => 'CQ9_cq9 payoff', 
                'percent' => 0, 
                'percent_jps' => 0, 
                'percent_jpg' => 0, 
                'profit' => 0, 
                'denomination' => 0, 
                'shop_id' => $user->shop_id,
                'category_id' => isset($category)?$category->id:0,
                'game_id' => 'CQ9_cq9',
                'roundid' => 0,
            ]);

            \DB::commit();

            
            return response()->json([
                'data' => ['balance' => floatval($user->balance), 'currency' => 'KRW'],
                'status' => [
                    'code' => '0',
                    'message' => 'Success',
                    'datetime' => date(DATE_RFC3339_EXTENDED)
                ]
            ]);
        }

        public function record($mtcode, \Illuminate\Http\Request $request)
        {
            $record = $this->checkmtcode($mtcode);
            if (!$record)
            {
                return response()->json([
                    'data' => null,
                    'status' => [
                        'code' => '1014',
                        'message' => 'record not found',
                        'datetime' => date(DATE_RFC3339_EXTENDED)
                    ]
                ]);
            }
            $data = json_decode($record->data, true);

            return response()->json([
                'data' => $data,
                'status' => [
                    'code' => '0',
                    'message' => 'Success',
                    'datetime' => date(DATE_RFC3339_EXTENDED)
                ]
            ]);
        }
        public function balance($account, \Illuminate\Http\Request $request)
        {
            $user = \VanguardLTE\User::lockForUpdate()->where('id',$account)->get()->first();
            if ($user && $user->hasRole('user'))
            {
                $resjson = json_encode([
                    'data' => ['balance' => number_format ($user->balance,4,'.',''), 'currency' => 'KRW'],
                    'status' => [
                        'code' => '0',
                        'message' => 'Success',
                        'datetime' => date(DATE_RFC3339_EXTENDED)
                    ]
                ]);
                $pattern = '/("balance":)"([.\d]+)"/x';
                $replacement = '${1}${2}';
                
                return response(preg_replace($pattern, $replacement, $resjson), 200)->header('Content-Type', 'application/json');
            }
            else
            {
                return response()->json([
                    'data' => null,
                    'status' => [
                        'code' => '1006',
                        'message' => 'player not found',
                        'datetime' => date(DATE_RFC3339_EXTENDED)
                    ]
                ]);
            }
        }

        public function checkplayer($account, \Illuminate\Http\Request $request)
        {
            $user = \VanguardLTE\User::lockForUpdate()->where('id',$account)->get()->first();
            $data = false;
            if ($user && $user->hasRole('user')) {
                $data = true;
            }
            return response()->json([
                'data' => $data,
                'status' => [
                    'code' => '0',
                    'message' => 'Success',
                    'datetime' => date(DATE_RFC3339_EXTENDED)
                ]
            ]);

        }

        /*
        * FROM CONTROLLER, API
        */
        
        public static function getgamelist($href)
        {
            $gameList = \Illuminate\Support\Facades\Redis::get('cq9list');
            if ($gameList)
            {
                $games = json_decode($gameList, true);
                if ($games!=null && count($games) > 0){
                    return $games;
                }
            }
            $response = null;
            try {
                $response = Http::withHeaders([
                    'Authorization' => config('app.cq9token'),
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ])->get(config('app.cq9api') . '/gameboy/game/list/cq9');

            } catch (\Exception $e) {
                return null;
            }
           
            if ($response==null && !$response->ok())
            {
                return null;
            }
            $detect = new \Detection\MobileDetect();
            $plat = ($detect->isMobile() || $detect->isTablet())?'mobile':'web';
            $data = $response->json();
            if ($data!=null && $data['status']['code'] == 0){
                $gameList = [];
                foreach ($data['data'] as $game)
                {
                    if ($game['gametype'] == "slot" && $game['status'] && $game['gameplat'] == $plat)
                    {
                        $selLan = 'ko';
                        if (!in_array($selLan, $game['lang']))
                        {
                            $selLan = 'en';
                            if (!in_array($selLan, $game['lang']))
                            {
                                continue;
                            }
                        }
                        foreach ($game['nameset'] as $title)
                        {
                            if ($title['lang'] == $selLan)
                            {
                                $gameList[] = [
                                    'provider' => 'cq9',
                                    'gamecode' => $game['gamecode'],
                                    'name' => preg_replace('/\s+/', '', $game['gamename']),
                                    'title' => $selLan=='en'?__('gameprovider.'.$title['name']) : $title['name'],
                                ];
                            }
                        }
                    }
                }
                \Illuminate\Support\Facades\Redis::set('cq9list', json_encode($gameList));
                return $gameList;
            }
            return null;

        }
        public static function getgamelink($gamecode)
        {
            return ['error' => false, 'data' => ['url' => route('frontend.providers.cq9.render', $gamecode)]];
        }
        public static function makegamelink($gamecode)
        {
            $user = auth()->user();
            if ($user == null)
            {
                return null;
            }
            $detect = new \Detection\MobileDetect();
            try{
                $response = Http::withHeaders([
                    'Authorization' => config('app.cq9token'),
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ])->asForm()->post(config('app.cq9api') . '/gameboy/player/sw/gamelink', [
                    'account' => $user->id,
                    'gamehall' => 'cq9',
                    'gamecode' => $gamecode,
                    'gameplat' => ($detect->isMobile() || $detect->isTablet())?'MOBILE':'WEB',
                    'lang' => 'ko'
                ]);
                if (!$response->ok())
                {
                    return null;
                }
                $data = $response->json();
                if ($data['status']['code'] == 0){
                    return $data['data']['url'];
                }
                else{
                    return null;
                }
            }
            catch (\Exception $ex)
            {
                Log::error($ex->getMessage());
                return null;
            }
        }

        public function clientInfo(\Illuminate\Http\Request $request)
        {
            $ip = $request->server('HTTP_CF_CONNECTING_IP')??$request->server('REMOTE_ADDR');
            $data = [
                "data" => [
                    "ip" => $ip,
                    "code" =>"",
                    "datatime" => date(DATE_RFC3339_EXTENDED)
                ]
            ];
            return response()->json($data);

        }
        public function cq9History(\Illuminate\Http\Request $request)
        {
            return view('frontend.Default.games.cq9.history');
        }

        public function cq9PlayerOrder(\Illuminate\Http\Request $request)
        {
            return view('frontend.Default.games.cq9.playerorder');
        }

        public function searchTime(\Illuminate\Http\Request $request)
        {
            $token = $request->token;
            $begin = date('Y-m-d H:i:s', strtotime($request->begin));
            $end = date('Y-m-d H:i:s', strtotime($request->end));
            $offset = $request->offset;
            $count = $request->count;
            $user = \VanguardLTE\User::where('api_token', $token)->first();
            if (!$user)
            {
                $data = [
                    "error_code" => 4096,
                    "error_msg" => "GET CYPRESS AUTHORIZATION INVALID(4006)",
                    "log_id" => $this->microtime_string(),
                    "result" => [
                    ]
                ];
                return response()->json($data);
            }

            $categories = \VanguardLTE\Category::whereIn('href', ['cq9play'])->where(['shop_id' => 0,'site_id' => 0])->pluck('id')->toArray();
            $stat_game = \VanguardLTE\StatGame::where('user_id', $user->id)->where('date_time', '>=', $begin)->where('date_time', '<=', $end)->whereIn('category_id', $categories)->orderby('date_time', 'desc')->skip($offset * $count)->take($count)->get();
            $list = [];
            foreach ($stat_game as $game)
            {
                $date = new \DateTime($game->date_time);
                $timezoneName = timezone_name_from_abbr("", -4*3600, false);
                $date->setTimezone(new \DateTimeZone($timezoneName));
                $time= $date->format(DATE_RFC3339_EXTENDED);
                $code = $game->game_id;
                if ($game->category->href == 'cq9play')
                {
                    $code = CQ9Controller::gamenametocode($game->game_item->title);
                }
                $freegame = 0;
                if (strpos($game->game, ' FG') !== false)
                {
                    $freegame = 8;
                }
                $record = [
                    'bets' => $game->bet,
                    'createtime' => $time,
                    'detail' => [
                        ['freegame' => $freegame],
                        ['luckydraw' => 0],
                        ['bonus' => 0],
                    ],
                    'gamecode' => $code,
                    'gamename' => $game->game_item->title,
                    'nameset' => [
                        [
                            'lang' => 'en',
                            'name' => $game->game_item->title
                        ]
                    ],
                    'roundid' => $game->id,
                    'singlerowbet' => false,
                    'tabletype' => "",
                    'wins' => $game->win,
                ];
                $list[] = $record;
            }
            $data = [
                "error_code" => 1,
                "error_msg" => "SUCCESS",
                "log_id" => "",
                "result" => [
                    "data" => [ 
                        "count" => count($stat_game),
                        "list" => $list
                    ],
                    "status" => [
                        "code" => "0",
                        "message" => "Success",
                        "datetime" => date(DATE_RFC3339_EXTENDED),
                        "traceCode" => $this->microtime_string()
                    ]
                ]
            ];
            return response()->json($data);
        }

        public function detailLink(\Illuminate\Http\Request $request)
        {
            $token = $request->token;
            $statid = $request->id;
            $gameid = $request->game_id;
            $data = [
                "error_code" => 1,
                "error_msg" => "SUCCESS",
                "log_id" => "",
                "result" => [
                    "data" => [
                        "link" => url('/playerodh5/?token=' . $token),
                    ],
                    "status" => [
                        "code" => "0",
                        "message" => "Success",
                        "datetime" => date(DATE_RFC3339_EXTENDED),
                        "traceCode" => $this->microtime_string()
                    ]
                ]
            ];
            
            return response()->json($data);
        }

        public function wager(\Illuminate\Http\Request $request)
        {
            $data = [
                "error_code" => 1,
                "error_msg" => "SUCCESS",
                "log_id" => "",
                "result" => [
                    "data" => [
                        "link" => url('/playerodh5/?token=121212&gamecode=152'),
                    ],
                    "status" => [
                        "code" => "0",
                        "message" => "Success",
                        "datetime" => date(DATE_RFC3339_EXTENDED),
                        "traceCode" => $this->microtime_string()
                    ]
                ]
            ];

            $data = '{"data":{"account":"8533","parentacc":"major_prod","actionlist":[{"action":"bet","amount":750,"eventtime":"2021-09-20T09:32:11-04:00"},{"action":"win","amount":13200,"eventtime":"2021-09-20T09:33:16-04:00"}],"detail":{"wager":{"seq_no":"577883709373","order_time":"2021-09-20T09:33:16-04:00","end_time":"2021-09-20T09:33:16-04:00","user_id":"6126ecda414edc0001ae9aa7","game_id":"152","platform":"web","currency":"KRW","start_time":"2021-09-20T09:32:11-04:00","server_ip":"10.9.16.21","client_ip":"91.207.174.99","play_bet":"750","play_denom":"100","bet_multiple":"30","rng":[124,226,24,171,228],"multiple":"1","base_game_win":"0","win_over_limit_lock":0,"game_type":0,"win_type":2,"settle_type":0,"wager_type":0,"total_win":"13200","win_line_count":1,"bet_tid":"pro-bet-577883709373","win_tid":"pro-win-577883709373","proof":{"win_line_data":[{"line_extra_data":[0],"line_multiplier":1,"line_prize":0,"line_type":0,"symbol_id":"F","symbol_count":4,"num_of_kind":3,"win_line_no":999,"win_position":[[0,0,0,0,0],[0,1,0,0,0],[0,1,1,1,0]]}],"symbol_data":["14,15,2,5,2","1,F,12,14,12","15,F,F,F,4"],"symbol_data_after":[],"extra_data":[0],"lock_position":[],"reel_pos_chg":[0,0],"reel_len_change":[],"reel_pay":[],"respin_reels":[0,0,0,0,0],"bonus_type":0,"special_award":0,"special_symbol":0,"is_respin":false,"fg_times":8,"fg_rounds":0,"next_s_table":0,"extend_feature_by_game":[],"extend_feature_by_game2":[]},"sub":[{"sub_no":1,"game_type":50,"rng":[13,23,5,21,91],"multiple":"2","win":"7200","win_line_count":2,"win_type":1,"proof":{"win_line_data":[{"line_extra_data":[0],"line_multiplier":2,"line_prize":6000,"line_type":1,"symbol_id":"15","symbol_count":6,"num_of_kind":4,"win_line_no":0,"win_position":[[1,0,1,0,0],[0,2,1,0,0],[0,2,0,1,0]]},{"line_extra_data":[0],"line_multiplier":2,"line_prize":1200,"line_type":0,"symbol_id":"15","symbol_count":5,"num_of_kind":3,"win_line_no":1,"win_position":[[1,0,1,0,0],[0,2,1,0,0],[0,2,0,0,0]]}],"symbol_data":["15,11,15,12,3","2,W,15,5,3","2,W,12,13,15"],"symbol_data_after":[],"extra_data":[0],"lock_position":[[1,0,1,0,0],[0,1,1,0,0],[0,1,0,0,1]],"reel_pos_chg":[5,4],"reel_len_change":[],"reel_pay":[],"respin_reels":[0,0,0,0,0],"bonus_type":0,"special_award":0,"special_symbol":0,"is_respin":false,"fg_times":0,"fg_rounds":0,"next_s_table":0,"extend_feature_by_game":[],"extend_feature_by_game2":[]}},{"sub_no":2,"game_type":50,"rng":[23,97,76,3,86],"multiple":"2","win":"0","win_line_count":0,"win_type":0,"proof":{"win_line_data":[],"symbol_data":["14,F,3,14,3","13,11,12,3,3","5,11,5,11,3"],"symbol_data_after":[],"extra_data":[0],"lock_position":[[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0]],"reel_pos_chg":[0,0],"reel_len_change":[],"reel_pay":[],"respin_reels":[0,0,0,0,0],"bonus_type":0,"special_award":0,"special_symbol":0,"is_respin":false,"fg_times":0,"fg_rounds":0,"next_s_table":0,"extend_feature_by_game":[],"extend_feature_by_game2":[]}},{"sub_no":3,"game_type":50,"rng":[27,34,12,63,71],"multiple":"2","win":"0","win_line_count":0,"win_type":0,"proof":{"win_line_data":[],"symbol_data":["4,13,12,12,5","11,13,5,12,4","5,F,13,13,15"],"symbol_data_after":[],"extra_data":[0],"lock_position":[[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0]],"reel_pos_chg":[0,0],"reel_len_change":[],"reel_pay":[],"respin_reels":[0,0,0,0,0],"bonus_type":0,"special_award":0,"special_symbol":0,"is_respin":false,"fg_times":0,"fg_rounds":0,"next_s_table":0,"extend_feature_by_game":[],"extend_feature_by_game2":[]}},{"sub_no":4,"game_type":50,"rng":[85,28,142,92,33],"multiple":"2","win":"0","win_line_count":0,"win_type":0,"proof":{"win_line_data":[],"symbol_data":["14,5,15,3,3","1,14,1,15,12","13,3,1,3,4"],"symbol_data_after":[],"extra_data":[0],"lock_position":[[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0]],"reel_pos_chg":[0,0],"reel_len_change":[],"reel_pay":[],"respin_reels":[0,0,0,0,0],"bonus_type":0,"special_award":0,"special_symbol":0,"is_respin":false,"fg_times":0,"fg_rounds":0,"next_s_table":0,"extend_feature_by_game":[],"extend_feature_by_game2":[]}},{"sub_no":5,"game_type":50,"rng":[70,132,134,43,47],"multiple":"2","win":"0","win_line_count":0,"win_type":0,"proof":{"win_line_data":[],"symbol_data":["15,1,W,F,12","5,12,15,14,2","5,11,W,14,14"],"symbol_data_after":[],"extra_data":[0],"lock_position":[[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0]],"reel_pos_chg":[0,0],"reel_len_change":[],"reel_pay":[],"respin_reels":[0,0,0,0,0],"bonus_type":0,"special_award":0,"special_symbol":0,"is_respin":false,"fg_times":0,"fg_rounds":0,"next_s_table":0,"extend_feature_by_game":[],"extend_feature_by_game2":[]}},{"sub_no":6,"game_type":50,"rng":[17,151,31,79,55],"multiple":"2","win":"6000","win_line_count":1,"win_type":1,"proof":{"win_line_data":[{"line_extra_data":[0],"line_multiplier":2,"line_prize":6000,"line_type":1,"symbol_id":"4","symbol_count":4,"num_of_kind":4,"win_line_no":0,"win_position":[[0,1,2,0,0],[0,0,0,0,0],[1,0,0,1,0]]}],"symbol_data":["11,4,2,15,W","15,13,F,15,15","4,F,15,4,5"],"symbol_data_after":[],"extra_data":[0],"lock_position":[[0,1,0,0,1],[0,0,0,0,0],[1,0,0,1,0]],"reel_pos_chg":[5,3],"reel_len_change":[],"reel_pay":[],"respin_reels":[0,0,0,0,0],"bonus_type":0,"special_award":0,"special_symbol":0,"is_respin":false,"fg_times":0,"fg_rounds":0,"next_s_table":0,"extend_feature_by_game":[],"extend_feature_by_game2":[]}},{"sub_no":7,"game_type":50,"rng":[116,91,99,145,31],"multiple":"2","win":"0","win_line_count":0,"win_type":0,"proof":{"win_line_data":[],"symbol_data":["14,11,15,14,3","1,4,3,14,15","12,13,15,F,3"],"symbol_data_after":[],"extra_data":[0],"lock_position":[[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0]],"reel_pos_chg":[0,0],"reel_len_change":[],"reel_pay":[],"respin_reels":[0,0,0,0,0],"bonus_type":0,"special_award":0,"special_symbol":0,"is_respin":false,"fg_times":0,"fg_rounds":0,"next_s_table":0,"extend_feature_by_game":[],"extend_feature_by_game2":[]}},{"sub_no":8,"game_type":50,"rng":[8,100,147,33,132],"multiple":"2","win":"0","win_line_count":0,"win_type":0,"proof":{"win_line_data":[],"symbol_data":["15,1,5,15,11","4,15,13,12,1","13,12,13,5,4"],"symbol_data_after":[],"extra_data":[0],"lock_position":[[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0]],"reel_pos_chg":[0,0],"reel_len_change":[],"reel_pay":[],"respin_reels":[0,0,0,0,0],"bonus_type":0,"special_award":0,"special_symbol":0,"is_respin":false,"fg_times":0,"fg_rounds":0,"next_s_table":0,"extend_feature_by_game":[],"extend_feature_by_game2":[]}}],"pick":[]}}},"status":{"code":"0","message":"Success","datetime":"2021-09-20T09:35:48.898-04:00"}}';
            
            //return response()->json($data);
            return response($data, 200)->header('Content-Type', 'application/json');
        }
    }

}

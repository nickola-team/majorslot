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
    }

}

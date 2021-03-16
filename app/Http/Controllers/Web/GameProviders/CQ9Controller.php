<?php 
namespace VanguardLTE\Http\Controllers\Web\GameProviders
{
    class CQ9Controller extends \VanguardLTE\Http\Controllers\Controller
    {
        function validRFC3339Date($date) {
            if (preg_match('/^([0-9]+)-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])[Tt]([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9]|60)(\.[0-9]+)?(([Zz])|([\+|\-]([01][0-9]|2[0-3]):[0-5][0-9]))$/', $date)) {
                return true;
            } else {
                return false;
            }
        }

        public function checkmtcode($mtcode)
        {
            $record = \VanguardLTE\CQ9Transaction::Where('mtcode',$mtcode)->get()->first();
            return $record;
        }
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

            $user = \VanguardLTE\User::Where('username',$account)->get()->first();
            if (!$user || !$user->hasRole('user')){
                $transaction['status']['endtime'] = date(DATE_RFC3339_EXTENDED);
                $transaction['status']['status'] = 'failed';
                $transaction['status']['message'] = 'failed';
                \VanguardLTE\CQ9Transaction::create([
                    'mtcode' => $mtcode, 
                    'data' => json_encode($transaction)
                ]);

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
                    'data' => json_encode($transaction)
                ]);

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
            
            $user->balance = floatval(sprintf('%.2f', $user->balance - floatval($amount)));
            $user->save();

            $transaction['balance'] = floatval($user->balance);
            $transaction['status']['endtime'] = date(DATE_RFC3339_EXTENDED);
            \VanguardLTE\CQ9Transaction::create([
                'mtcode' => $mtcode, 
                'data' => json_encode($transaction)
            ]);

            
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

            $user = \VanguardLTE\User::Where('username',$account)->get()->first();
            if (!$user || !$user->hasRole('user')){
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

            $user->balance = floatval(sprintf('%.2f', $user->balance + floatval($totalamount)));
            $user->save();

            $transaction['balance'] = floatval($user->balance);
            $transaction['event'] = $endrounddata;
            $transaction['status']['endtime'] = date(DATE_RFC3339_EXTENDED);
            foreach ($mtcodes_record as $record)
            {
                if ($this->checkmtcode($record))
                {
                    $user->balance = floatval(sprintf('%.2f', $user->balance - floatval($totalamount)));
                    $user->save();
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
                    'data' => json_encode($transaction)
                ]);
            }

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

            $user = \VanguardLTE\User::Where('username',$account)->get()->first();
            if (!$user || !$user->hasRole('user')){
                $transaction['status']['endtime'] = date(DATE_RFC3339_EXTENDED);
                $transaction['status']['status'] = 'failed';
                $transaction['status']['message'] = 'failed';
                \VanguardLTE\CQ9Transaction::create([
                    'mtcode' => $mtcode, 
                    'data' => json_encode($transaction)
                ]);

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
                    'data' => json_encode($transaction)
                ]);

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
            
            $user->balance = floatval(sprintf('%.2f', $user->balance - floatval($amount)));
            $user->save();

            $transaction['balance'] = floatval($user->balance);
            $transaction['status']['endtime'] = date(DATE_RFC3339_EXTENDED);
            \VanguardLTE\CQ9Transaction::create([
                'mtcode' => $mtcode, 
                'data' => json_encode($transaction)
            ]);

            
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

            $user = \VanguardLTE\User::Where('username',$account)->get()->first();
            if (!$user || !$user->hasRole('user')){
                $transaction['status']['endtime'] = date(DATE_RFC3339_EXTENDED);
                $transaction['status']['status'] = 'failed';
                $transaction['status']['message'] = 'failed';
                \VanguardLTE\CQ9Transaction::create([
                    'mtcode' => $mtcode, 
                    'data' => json_encode($transaction)
                ]);

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
            $user->balance = floatval(sprintf('%.2f', $user->balance + floatval($amount)));
            $user->save();

            $transaction['balance'] = floatval($user->balance);
            $transaction['status']['endtime'] = date(DATE_RFC3339_EXTENDED);
            \VanguardLTE\CQ9Transaction::create([
                'mtcode' => $mtcode, 
                'data' => json_encode($transaction)
            ]);

            
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
            
            $user = \VanguardLTE\User::Where('username',$data['target']['account'])->get()->first();
            if (!$user || !$user->hasRole('user')){
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
            
            $user->balance = floatval(sprintf('%.2f', $user->balance + floatval($totalamount)));
            $user->save();

            $data['balance'] = floatval($user->balance);
            
            $record->refund = 1;
            $record->data = json_encode($data);
            $record->save();

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

            $user = \VanguardLTE\User::Where('username',$account)->get()->first();
            if (!$user || !$user->hasRole('user')){
                $transaction['status']['endtime'] = date(DATE_RFC3339_EXTENDED);
                $transaction['status']['status'] = 'failed';
                $transaction['status']['message'] = 'failed';
                \VanguardLTE\CQ9Transaction::create([
                    'mtcode' => $mtcode, 
                    'data' => json_encode($transaction)
                ]);

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
            
            $user->balance = floatval(sprintf('%.2f', $user->balance + floatval($amount)));
            $user->save();

            $transaction['balance'] = floatval($user->balance);
            $transaction['status']['endtime'] = date(DATE_RFC3339_EXTENDED);
            \VanguardLTE\CQ9Transaction::create([
                'mtcode' => $mtcode, 
                'data' => json_encode($transaction)
            ]);

            
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
            $data['_id'] = $record->id;

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
            $user = \VanguardLTE\User::Where('username',$account)->get()->first();
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
            $user = \VanguardLTE\User::Where('username',$account)->get()->first();
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

        
    }

}

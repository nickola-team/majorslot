<?php 
namespace VanguardLTE\Http\Controllers\Web\GameProviders
{
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Log;
    class BTIController extends \VanguardLTE\Http\Controllers\Controller
    {
        public function ValidateToken(\Illuminate\Http\Request $request){
            // $data = json_decode($request->getContent(), true);
            // $authToken = isset($data['auth_token'])?$data['auth_token']:0;
            $authToken = $request->auth_token;
            if($authToken == null){
                return response(BTIController::toText([
                    'error_code' => -1,
                    'error_message' => 'Generic Error'
                ]))->header('Content-Type', 'text/plain');
            }
            $user = \VanguardLTE\User::where(['api_token'=> $authToken])->first();
            if (!isset($user))
            {
                return response(BTIController::toText([
                    'error_code' => -1,
                    'error_message' => 'Generic Error'
                ]))->header('Content-Type', 'text/plain');
            }
            return response(BTIController::toText([
                'error_code' => 0,
                'error_message' => 'No Error',
                'cust_id' => $user->id,
                'balance' => $user->balance,
                'cust_login' => $user->username,
                'city' => 'City',
                'country' => 'KR',
                'currency_code' => 'KRW'
            ]))->header('Content-Type', 'text/plain');
        }

        public function reserve(\Illuminate\Http\Request $request){
            \DB::beginTransaction();
            $xmlObject = simplexml_load_string($request->getContent());            
            $json = json_encode($xmlObject);
            $array = json_decode($json,true); 
            $custId = $request->cust_id;// $array['@attributes']['cust_id'];
            $reserveId = $request->reserve_id;// $array['@attributes']['reserve_id'];
            $amount = $request->amount;// $array['@attributes']['amount'];
            if(count($array['Bet'])>1){  //system: off, riskfreebet: on
                if(isset($array['Bet'][0])){
                    $betTypeId = $array['Bet'][0]['@attributes']['BetTypeID'];
                    $betTypeName = $array['Bet'][0]['@attributes']['BetTypeName'];
                }else{
                    $betTypeId = $array['Bet']['@attributes']['BetTypeID'];
                    $betTypeName = $array['Bet']['@attributes']['BetTypeName'];
                }
                
            }else{
                $betTypeId = $array['Bet']['@attributes']['BetTypeID'];
                $betTypeName = $array['Bet']['@attributes']['BetTypeName'];
            }

            $user = \VanguardLTE\User::where(['id'=> $custId])->first();
            //No User
            if (!isset($user) || !is_numeric($custId))
            {
                $record = \VanguardLTE\BTiTransaction::create(['error_code' => -2,'error_message' => 'Invalid Customer','amount' => $amount,'balance' => 0.00,'user_id' => $custId,'reserve_id' => $reserveId,'data' => json_encode($array),'status' => -1,'bet_type_id'=>$betTypeId,'bet_type_name'=>$betTypeName]);

                return response(BTIController::toText([
                    'error_code' => -2,
                    'error_message' => 'Invalid Customer',
                    'trx_id' => $record->id,
                    'balance' => 0.00
                ]))->header('Content-Type', 'text/plain');
            }
            $userBalance = $user->balance;
            //bet amount is greater than user balance
            if($amount > $userBalance){
                $record = \VanguardLTE\BTiTransaction::create(['error_code' => -4,'error_message' => 'Insufficient Amount','amount' => $amount,'balance' => $userBalance,'user_id' => $custId,'reserve_id' => $reserveId,'data' => json_encode($array),'status' => -1,'bet_type_id'=>$betTypeId,'bet_type_name'=>$betTypeName]);

                return response(BTIController::toText([
                    'error_code' => -4,
                    'error_message' => 'Insufficient Amount',
                    'trx_id' => $record->id,
                    'balance' => $userBalance
                ]))->header('Content-Type', 'text/plain');
            }

            $sameReserve = false;
            $record = \VanguardLTE\BTiTransaction::where(['user_id' => $custId,'status'=>0,'reserve_id'=>$reserveId])->first();
            $balance = 0;
            $sendreserveId = 0;
            
            
            if(!isset($record)){    
                $balance = $userBalance - $amount;            
                $record_1 = \VanguardLTE\BTiTransaction::create(['error_code' => 0,'error_message' => 'No Error','amount' => $amount,'balance' => $balance,'user_id' => $custId,'reserve_id' => $reserveId,'data' => json_encode($array),'status' => 0,'bet_type_id'=>$betTypeId,'bet_type_name'=>$betTypeName]);

                $user->balance = $balance;
                $user->save();

                \DB::commit();
                
                return response(BTIController::toText([
                    'error_code' => 0,
                    'error_message' => 'No Error',
                    'trx_id' => $record_1->id,
                    'balance' => $balance
                ]))->header('Content-Type', 'text/plain');
            }else{
                $sameRequest = false;
                $records = \VanguardLTE\BTiTransaction::where(['user_id' => $custId,'reserve_id'=>$reserveId])->get();
                foreach($records as $recordx){
                    if($recordx->status == 2){
                        $sameRequest = true;
                    }
                }
                $balance = 0;
                if($sameRequest){
                    $user->balance = $user->balance - $amount;
                    $user->save();
                    $balance = $user->balance;
                }else{
                    $balance = $record->balance;
                }

                //$this->getgamedetail();   //test
                return response(BTIController::toText([
                    'error_code' => 0,
                    'error_message' => 'No Error',
                    'trx_id' => $record->id,
                    'balance' => $balance
                ]))->header('Content-Type', 'text/plain');
            }
        }



        public function cancelreserve(\Illuminate\Http\Request $request){
            \DB::beginTransaction();

            $agentId = $request->agent_id;// isset($data['agent_id'])?$data['agent_id']:0;
            $customerId = $request->cust_id;//isset($data['customer_id'])?$data['customer_id']:0;
            $custId = $request->customer_id;//isset($data['cust_id'])?$data['cust_id']:0;
            $reserveId = $request->reserve_id;//isset($data['reserve_id'])?$data['reserve_id']:0;

            $user = \VanguardLTE\User::where(['id'=> $customerId])->first();
            
            $record = \VanguardLTE\BTiTransaction::where(['user_id' => $customerId,'reserve_id'=>$reserveId])->first();  //status:-1 : error, status:0 : reserve, status:1 : debit, status:2 : cancel,status:4 : commited reserve, 5: debit customer, 6: credit customer

            if(!isset($record)){
                \VanguardLTE\BTiTransaction::create(['error_code' => 0,'error_message' => 'ReserveID not exists ','amount' => 0,'balance' => $user->balance,'user_id' => $customerId,'reserve_id' => $reserveId,'data' => '','status' => -1]);
                
                \DB::commit();
                return response(BTIController::toText([
                    'error_code' => 0,
                    'error_message' => 'ReserveID not exists ',
                    'balance' => $user->balance
                ]))->header('Content-Type', 'text/plain');
            }else{
                $sameRequest = false;
                $records = \VanguardLTE\BTiTransaction::where(['user_id' => $customerId,'reserve_id'=>$reserveId])->get();
                foreach($records as $recordx){
                    if($recordx->status == 2){
                        $sameRequest = true;
                    }
                }
                if($sameRequest){
                    \VanguardLTE\BTiTransaction::create(['error_code' => 0,'error_message' => 'No Error','amount' => 0,'balance' => $user->balance,'user_id' => $customerId,'reserve_id' => $reserveId,'data' => '','status' => 2]);
                
                    \DB::commit();
                    return response(BTIController::toText([
                        'error_code' => 0,
                        'error_message' => 'No Error',
                        'balance' => $user->balance
                    ]))->header('Content-Type', 'text/plain');
                }else{
                    if($record->status == 1){   //Debitted Reserve
                        \VanguardLTE\BTiTransaction::create(['error_code' => 0,'error_message' => 'Already Debitted Reserve','amount' => $record->amount,'balance' => $user->balance,'user_id' => $customerId,'reserve_id' => $reserveId,'data'=>$record->data,'status' => 2,'bet_type_id'=>$record->bet_type_id,'bet_type_name'=>$record->bet_type_name]);
        
                        $user->balance = $user->balance + $record->amount;
                        $user->save();
                        \DB::commit();
                        return response(BTIController::toText([
                            'error_code' => 0,
                            'error_message' => 'Already Debitted Reserve',
                            'balance' => $user->balance
                        ]))->header('Content-Type', 'text/plain');
                    }else{  //cancel reserve action : status == 0
                        
                        $reserveBalance = $record->amount + $user->balance;
                        
                        \VanguardLTE\BTiTransaction::create(['error_code' => 0,'error_message' => 'No Error','amount' => $record->amount,'balance' => $reserveBalance,'user_id' => $customerId,'reserve_id' => $reserveId,'data'=>$record->data,'status' => 2,'bet_type_id'=>$record->bet_type_id,'bet_type_name'=>$record->bet_type_name]);
        
                        $user->balance = $reserveBalance;
                        $user->save();
        
                        \DB::commit();
                        return response(BTIController::toText([
                            'error_code' => 0,
                            'error_message' => 'No Error',
                            'balance' => $reserveBalance
                        ]))->header('Content-Type', 'text/plain');
                        
                    }
                }

                
            }

            

        }

        public function debitreserve(\Illuminate\Http\Request $request){
            
            $xmlObject = simplexml_load_string($request->getContent());            
            $json = json_encode($xmlObject);
            $array = json_decode($json,true); 
            $custId = $request->cust_id;//$array['@attributes']['cust_id'];
            $reserveId = $request->reserve_id;//$array['@attributes']['reserve_id'];
            $amount = $request->amount;//$array['@attributes']['amount'];
            $reqId = $request->req_id;
            $purchaseId = $request->purchase_id;

            if(count($array['Bet'])>1){
                if(isset($array['Bet'][0])){
                    $betTypeId = $array['Bet'][0]['@attributes']['BetTypeID'];
                    $betTypeName = $array['Bet'][0]['@attributes']['BetTypeName'];
                }else{
                    $betTypeId = $array['Bet']['@attributes']['BetTypeID'];
                    $betTypeName = $array['Bet']['@attributes']['BetTypeName'];
                }
            }else{
                $betTypeId = $array['Bet']['@attributes']['BetTypeID'];
                $betTypeName = $array['Bet']['@attributes']['BetTypeName'];
            }

            $user = \VanguardLTE\User::where(['id'=> $custId])->first();

            $record = \VanguardLTE\BTiTransaction::where(['user_id' => $custId,'reserve_id'=>$reserveId])->get();

            if(!$record){
                $record_1 = \VanguardLTE\BTiTransaction::create(['error_code' => 0,'error_message' => 'ReserveID Not Exist','amount' => 0,'balance' => $user->balance,'user_id' => $custId,'reserve_id' => $reserveId,'data' => json_encode($array),'req_id'=>$reqId,'status' => -1,'bet_type_id'=>$betTypeId,'bet_type_name'=>$betTypeName,'purchase_id'=>$purchaseId]);

                return response(BTIController::toText([
                    'error_code' => 0,
                    'error_message' => 'ReserveID Not Exist',
                    'trx_id' => $record_1->id,
                    'balance' => $user->balance
                ]))->header('Content-Type', 'text/plain');
            }
            $canceledreserve = false;
            $commitedreserve = false;
            foreach($record as $recordx){
                if($recordx->status == 2){
                    $canceledreserve = true;
                }else if($recordx->status == 4){
                    $commitedreserve = true;
                }
            }
            $reserveRecord = \VanguardLTE\BTiTransaction::where(['user_id' => $custId,'reserve_id'=>$reserveId,'status'=>0])->first();
            if($canceledreserve){
                \VanguardLTE\BTiTransaction::create(['error_code' => 0,'error_message' => 'Already cancelled reserve','amount' => $reserveRecord->amount,'balance' => $user->balance,'user_id' => $custId,'reserve_id' => $reserveId,'data' => json_encode($array),'req_id'=>$reqId,'status' => 2,'bet_type_id'=>$betTypeId,'bet_type_name'=>$betTypeName,'purchase_id'=>$purchaseId]);

                return response(BTIController::toText([
                    'error_code' => 0,
                    'error_message' => 'Already cancelled reserve',
                    'trx_id' => $reserveRecord->id,
                    'balance' => $user->balance
                ]))->header('Content-Type', 'text/plain');
            }else if($commitedreserve){
                \VanguardLTE\BTiTransaction::create(['error_code' => 0,'error_message' => 'Already committed reserve','amount' => $reserveRecord->amount,'balance' => $user->balance,'user_id' => $custId,'reserve_id' => $reserveId,'data' => json_encode($array),'req_id'=>$reqId,'status' => 4,'bet_type_id'=>$betTypeId,'bet_type_name'=>$betTypeName,'purchase_id'=>$purchaseId]);

                return response(BTIController::toText([
                    'error_code' => 0,
                    'error_message' => 'Already committed reserve',
                    'trx_id' => $reserveRecord->id,
                    'balance' => $user->balance
                ]))->header('Content-Type', 'text/plain');
            }

            $sameRequest = false;
            $debitRecords = \VanguardLTE\BTiTransaction::where(['user_id' => $custId,'reserve_id'=>$reserveId,'status'=>1])->get();

            

            foreach($debitRecords as $debitRecord){
                if($debitRecord->req_id == $reqId){
                    $sameRequest = true;
                    break;
                }
            }            


            if($sameRequest){

                return response(BTIController::toText([
                    'error_code' => 0,
                    'error_message' => 'No Error',
                    'trx_id' => $reserveRecord->id,
                    'balance' => $reserveRecord->balance
                ]))->header('Content-Type', 'text/plain');
            }else{
                if($amount > $reserveRecord->amount){
                    \VanguardLTE\BTiTransaction::create(['error_code' => 0,'error_message' => 'Total DebitReserve amount larger than Reserve amount','amount' => $amount,'balance' => $reserveRecord->balance,'user_id' => $custId,'reserve_id' => $reserveId,'req_id'=>$reqId,'data' => json_encode($array),'status' => -1,'bet_type_id'=>$betTypeId,'bet_type_name'=>$betTypeName,'purchase_id'=>$purchaseId]);
    
                    return response(BTIController::toText([
                        'error_code' => 0,
                        'error_message' => 'Total DebitReserve amount larger than Reserve amount',
                        'trx_id' => $reserveRecord->id,
                        'balance' => $reserveRecord->balance
                    ]))->header('Content-Type', 'text/plain');
                }
                $recBalance = $user->balance;
                
                \VanguardLTE\BTiTransaction::create(['error_code' => 0,'error_message' => 'No Error','amount' => $amount,'balance' => $recBalance,'user_id' => $custId,'reserve_id' => $reserveId,'req_id'=>$reqId,'data' => json_encode($array),'status' => 1,'bet_type_id'=>$betTypeId,'bet_type_name'=>$betTypeName,'purchase_id'=>$purchaseId]);

                return response(BTIController::toText([
                    'error_code' => 0,
                    'error_message' => 'No Error',
                    'trx_id' => $reserveRecord->id,
                    'balance' => $reserveRecord->balance
                ]))->header('Content-Type', 'text/plain');
            }
            

            

        }

        public function commitreserve(\Illuminate\Http\Request $request){
            \DB::beginTransaction();
            $data = json_decode($request->getContent(), true);
            $agentId = $request->agent_id;// isset($data['agent_id'])?$data['agent_id']:0;
            $customerId = $request->cust_id;//isset($data['customer_id'])?$data['customer_id']:0;
            $custId = $request->customer_id;//isset($data['cust_id'])?$data['cust_id']:0;
            $reserveId = $request->reserve_id;//isset($data['reserve_id'])?$data['reserve_id']:0;
            $purchaseId = $request->purchase_id;//isset($data['purchase_id'])?$data['purchase_id']:0;

            $user = \VanguardLTE\User::lockForUpdate()->where(['id'=> $customerId])->first();
            $debitamount = 0;
            $debitbased_Record = \VanguardLTE\BTiTransaction::where(['user_id' => $customerId,'reserve_id'=>$reserveId,'status' => 1])->get(); 
            
            
            if(!isset($debitbased_Record)){
                

                $record_1 = \VanguardLTE\BTiTransaction::create(['error_code' => 0,'error_message' => 'ReserveID Not Exist','amount' => 0,'balance' => '0','user_id' => $customerId,'reserve_id' => $reserveId,'req_id'=>'0', 'status' => -1,'bet_type_id'=>'0','bet_type_name'=>'0']);
                
                \DB::commit();
                return response(BTIController::toText([
                    'error_code' => 0,
                    'error_message' => 'ReserveID Not Exists',
                    'trx_id' => $record_1->id,
                    'balance' => $user->balance
                ]))->header('Content-Type', 'text/plain');
            }

            $sameReserve = false;
            $reserveBased_Record = \VanguardLTE\BTiTransaction::where(['user_id' => $customerId, 'reserve_id' => $reserveId,'status' => 0])->first();
            if(!$reserveBased_Record){
                \DB::commit();
                return response(BTIController::toText([
                    'error_code' => 0,
                    'error_message' => 'ReserveID Not Exists',
                    'balance' => $user->balance
                ]))->header('Content-Type', 'text/plain');
            }else{
                $reserveamount = $reserveBased_Record->amount;
                //$reserveBalance = $reserveBased_Record->balance;
                $reserveBalance = $user->balance;
                $betTypeID = '';
                $betTypeName = '';
                $betDate = '';
                $betReqId = '';
                $sameRecord = false;         
                foreach($debitbased_Record as $debitRecord){
                    $transData = json_decode($debitRecord->data,true);
                        //if($transData['Bet']['@attributes']['PurchaseBetID'] == $purchaseId){
                            $debitamount += $debitRecord->amount;
                            $betTypeID = $debitRecord->bet_type_id;
                            $betTypeName = $debitRecord->bet_type_name;
                            $betDate = $transData['Bet']['@attributes']['CreationDate'];
                            $betReqId = $debitRecord->req_id;
                        //}  
                }
    
                $userbalance = ($reserveBalance + $reserveamount) - $debitamount;
                $record_1 = \VanguardLTE\BTiTransaction::create(['error_code' => 0,'error_message' => 'No error','amount' => $reserveamount,'balance' => $userbalance,'user_id' => $customerId,'reserve_id' => $reserveId,'req_id'=>$betReqId,'data'=>$reserveBased_Record->data, 'status' => 4,'bet_type_id'=>$betTypeID,'bet_type_name'=>$betTypeName,'purchase_id'=>$purchaseId]);
    
                $user->balance = $userbalance;
                $user->save();
    
                \VanguardLTE\StatGame::create([
                    'user_id' => $user->id, 
                    'balance' => $user->balance, 
                    'bet' => $debitamount, 
                    'win' => 0,
                    'game' =>  'sports', 
                    'type' => 'sports',
                    'percent' => 0, 
                    'percent_jps' => 0, 
                    'percent_jpg' => 0, 
                    'profit' => 0, 
                    'denomination' => 0, 
                    'shop_id' => $user->shop_id,
                    'category_id' => 61,
                    'game_id' => $reserveId . '_commit',
                    'roundid' =>  $purchaseId,
                    'date_time' => $betDate
                ]);
    
                \DB::commit();
                return response(BTIController::toText([
                    'error_code' => 0,
                    'error_message' => 'No error',
                    'trx_id' => $record_1->id,
                    'balance' => $userbalance
                ]))->header('Content-Type', 'text/plain');
            }
            
            
        }


        public function debitcustomer(\Illuminate\Http\Request $request){
            \DB::beginTransaction();
            $data = json_decode($request->getContent(), true);
            $agentId = $request->agent_id;//isset($data['agent_id'])?$data['agent_id']:0;
            $customerId = $request->customer_id;//isset($data['customer_id'])?$data['customer_id']:0;
            $custId = $request->cust_id;//isset($data['cust_id'])?$data['cust_id']:0;
            $reqId = $request->req_id;//isset($data['req_id'])?$data['req_id']:0;
            $purchaseId = $request->purchase_id;//isset($data['purchase_id'])?$data['purchase_id']:0;
            

            $xmlObject = simplexml_load_string($request->getContent());            
            $json = json_encode($xmlObject);
            $array = json_decode($json,true); 

            $amount = $array['@attributes']['Amount'];
            $reserveId = $array['Purchases']['Purchase']['@attributes']['ReserveID'];
            
            $user = \VanguardLTE\User::lockForUpdate()->where(['id'=> $custId])->first();

            $debitedBalance = 0;


            $record = \VanguardLTE\BTiTransaction::where(['user_id' => $custId,'purchase_id'=>$purchaseId,'status'=>4])->first();            

            $debitCustomerRecord = \VanguardLTE\BTiTransaction::where(['user_id' => $custId,'req_id'=>$reqId,'purchase_id'=>$purchaseId])->first();            
            if($record){
                $betTypeId = intval($record->bet_type_id);
                $betTypeName = $record->bet_type_name;
            }else{
                $betTypeId = 0;
                $betTypeName = '';
            }

            if(!$debitCustomerRecord || $debitCustomerRecord == null){
                
                $debitCustomerRecord = \VanguardLTE\BTiTransaction::create(['error_code' => 0,'error_message' => 'No error','amount' => $amount,'balance' => $user->balance,'user_id' => $custId,'reserve_id' => $array['Purchases']['Purchase']['@attributes']['ReserveID'],'req_id'=>$reqId, 'data'=>json_encode($array),'status' => 5,'bet_type_id' => $betTypeId,'bet_type_name' => $betTypeName,'purchase_id' => $purchaseId]);
                
                $prevStatRecord = \VanguardLTE\StatGame::where(['user_id' => $custId,'game_id'=>$reserveId . '_credit','roundid'=>$purchaseId])->first();
                // $prevStatRecords = \VanguardLTE\StatGame::where(['user_id' => $custId,'game_id'=>$reserveId,'roundid'=>$purchaseId])->first();
                // $prevWinMoney = 0;
                //$prevStatRecord = null;
                
                // $user->balance = $debitedBalance;
                // $user->save();
                
                if(isset($prevStatRecord)){
                    $winmoney = $prevStatRecord->win + $amount;
                    $user->balance = $user->balance + $amount;
                    $debitedBalance = $user->balance;
                    $user->save();
                    $prevStatRecord->update(['balance'=>$user->balance,'bet'=>0,'win' => $winmoney,'type'=>'sports','game_id'=>$reserveId . '_debit','roundid'=>$purchaseId,'date_time'=>$array['Purchases']['Purchase']['@attributes']['CreationDateUTC']]);    
                    $debitCustomerRecord->update(['balance'=>$user->balance]) ;             
                }else{
                    
                    $statRecord = \VanguardLTE\StatGame::where(['user_id' => $custId,'game_id'=>$reserveId . '_debit','roundid'=>$purchaseId])->first();
                    if(isset($statRecord)){
                        $winmoney = $statRecord->win + $amount;
                        $user->balance = $user->balance + $amount;
                        $debitedBalance = $user->balance;
                        $user->save();
                        $statRecord->update(['balance'=>$user->balance,'bet'=>0,'win' => $winmoney,'type'=>'sports','game_id'=>$reserveId . '_debit','date_time'=>$array['Purchases']['Purchase']['@attributes']['CreationDateUTC']]); 
                        $debitCustomerRecord->update(['balance'=>$user->balance]) ;
                    }else{
                        $tempWinMoney = 0;
                        $creditRecords = \VanguardLTE\BTiTransaction::where(['user_id' => $custId,'reserve_id'=>$reserveId,'status'=>6])->get();
                        if(isset($creditRecords)){
                            foreach($creditRecords as $creditRecord){
                                $tempWinMoney += $creditRecord->amount;
                            }                                                                
                        }
                        $tempWinMoney = $amount + $tempWinMoney;
        
        
                        $debitedBalance = $user->balance + $tempWinMoney;
                        $user->balance = $debitedBalance;
                        $user->save();
                        $debitCustomerRecord->update(['balance'=>$user->balance]) ;    

                        \VanguardLTE\StatGame::create([
                            'user_id' => $user->id, 
                            'balance' => $user->balance, 
                            'bet' => 0, 
                            'win' => $tempWinMoney,
                            'game' =>  'sports', 
                            'type' => 'sports',
                            'percent' => 0, 
                            'percent_jps' => 0, 
                            'percent_jpg' => 0, 
                            'profit' => 0, 
                            'denomination' => 0, 
                            'shop_id' => $user->shop_id,
                            'category_id' => 61,
                            'game_id' => $array['Purchases']['Purchase']['@attributes']['ReserveID'] . '_debit',
                            'roundid' =>  $purchaseId,
                            'date_time' => $array['Purchases']['Purchase']['@attributes']['CreationDateUTC']
                        ]);
                    }                                         
                }
                
            }else{
                $debitedBalance = $debitCustomerRecord->balance;
            }

            
            \DB::commit();
            return response(BTIController::toText([
                'error_code' => 0,
                'error_message' => 'No error',
                'trx_id' => $debitCustomerRecord->id,
                'balance' => $debitedBalance
            ]))->header('Content-Type', 'text/plain');
        }


        public function creditcustomer(\Illuminate\Http\Request $request){
            \DB::beginTransaction();
            $data = json_decode($request->getContent(), true);
            $agentId = $request->agent_id;//isset($data['agent_id'])?$data['agent_id']:0;
            $customerId = $request->customer_id;//isset($data['customer_id'])?$data['customer_id']:0;
            $custId = $request->cust_id;//isset($data['cust_id'])?$data['cust_id']:0;
            $reqId = $request->req_id;//isset($data['req_id'])?$data['req_id']:0;
            $purchaseId = $request->purchase_id;//isset($data['purchase_id'])?$data['purchase_id']:0;
            $amount = $request->amount;//isset($data['amount'])?$data['amount']:0;

            $xmlObject = simplexml_load_string($request->getContent());            
            $json = json_encode($xmlObject);
            $array = json_decode($json,true); 
            // $custId = $array['@attributes']['cust_id'];
             $reserveId = $array['Purchases']['Purchase']['@attributes']['ReserveID'];            
            $betTypeId = 0;
            $betTypeName = '';            

            $user = \VanguardLTE\User::lockForUpdate()->where(['id'=> $custId])->first();

            $debitedBalance = 0;
            $sendTrxId = 0;

            $record = \VanguardLTE\BTiTransaction::where(['user_id' => $custId,'purchase_id'=>$purchaseId,'status'=>4])->first();            

            $debitCustomerRecord = \VanguardLTE\BTiTransaction::where(['user_id' => $custId,'req_id'=>$reqId,'purchase_id'=>$purchaseId])->first();            
            if($record){
                $betTypeId = intval($record->bet_type_id);
                $betTypeName = $record->bet_type_name;
            }else{
                $betTypeId = 0;
                $betTypeName = '';
            }
            
           

            $winmoney = 0;

            if(!$debitCustomerRecord || $debitCustomerRecord == null){
                

                $betfinish = false;
                $winmoney = $amount;

                $debitCustomerRecord = \VanguardLTE\BTiTransaction::create(['error_code' => 0,'error_message' => 'No error','amount' => $amount,'balance' => $debitedBalance,'user_id' => $custId,'reserve_id' => $reserveId,'req_id'=>$reqId, 'data'=>json_encode($array),'status' => 6,'bet_type_id' => $betTypeId,'bet_type_name' => $betTypeName,'purchase_id' => $purchaseId]);
                $sendTrxId = $debitCustomerRecord->id;
                
                $settledNumber = $array['Purchases']['Purchase']['@attributes']['NumberOfSettledLines'];
                $betlinesNumber = $array['Purchases']['Purchase']['@attributes']['NumberOfLines'];
                $lostNumber = $array['Purchases']['Purchase']['@attributes']['NumberOfLostLines'];
                $wonNumber = $array['Purchases']['Purchase']['@attributes']['NumberOfWonLines'];
                $actionType = '';
                if(isset($array['Purchases']['Purchase']['Selections']['Selection']['Changes']['Change']['Bets']['Bet']['@attributes']['ActionType'])){
                    $actionType = $array['Purchases']['Purchase']['Selections']['Selection']['Changes']['Change']['Bets']['Bet']['@attributes']['ActionType'];
                }
                
                $tempWinMoney = 0;
                $comboCase = false;
                $comboMoney = 0;
                if($betTypeId == 2){
                    if($settledNumber == $betlinesNumber){
                        $prevStatRecord = \VanguardLTE\StatGame::where(['user_id' => $custId,'game_id'=>$reserveId . '_credit','roundid'=>$purchaseId])->first();
                        if($actionType == 'Combo Bonus' || $actionType == 'Risk Free Bet'){                            
                            if(isset($prevStatRecord)){
                                $winmoney = $prevStatRecord->win + $amount;
                                $user->balance = $user->balance + $amount;
                                $user->save();
    
                               $prevStatRecord->update(['balance'=>$user->balance,'win' => $winmoney,'date_time'=>$array['Purchases']['Purchase']['@attributes']['CreationDateUTC']]);
                                $winmoney = 0;
                            }else{
                                $statRecord = \VanguardLTE\StatGame::where(['user_id' => $custId,'game_id'=>$reserveId . '_debit','roundid'=>$purchaseId])->first();
                                if(isset($statRecord)){
                                    $winmoney = $statRecord->win + $amount;
                                    $user->balance = $user->balance + $amount;
                                    $user->save();
        
                                    $statRecord->update(['balance'=>$user->balance,'win' => $winmoney,'game_id'=>$reserveId . '_credit','date_time'=>$array['Purchases']['Purchase']['@attributes']['CreationDateUTC']]);
                                    $winmoney = 0;
                                }else{
                                    $creditRecords = \VanguardLTE\BTiTransaction::where(['user_id' => $custId,'reserve_id'=>$reserveId,'status'=>6])->get();
                                    if(isset($creditRecords)){
                                        foreach($creditRecords as $creditRecord){
                                            $tempWinMoney += $creditRecord->amount;
                                        }                                                                
                                    }
                                    $winmoney = $tempWinMoney;
                                }
                                
                            }
                            
                        }else{
                            if($lostNumber > 0){
                                if(isset($prevStatRecord)){
                                    $winmoney = $prevStatRecord->win + $amount;
                                    $user->balance = $user->balance + $amount;
                                    $user->save();

                                    $prevStatRecord->update(['balance'=>$user->balance,'win' => $winmoney,'game_id'=>$reserveId . '_credit','date_time'=>$array['Purchases']['Purchase']['@attributes']['CreationDateUTC']]);
                                    $winmoney = 0;
                                }else{
                                    $statRecord = \VanguardLTE\StatGame::where(['user_id' => $custId,'game_id'=>$reserveId . '_debit','roundid'=>$purchaseId])->first();
                                    if(isset($statRecord)){
                                        $winmoney = $statRecord->win + $amount;
                                        $user->balance = $user->balance + $amount;
                                        $user->save();
                                        
                                        $statRecord->update(['balance'=>$user->balance,'win' => $winmoney,'game_id'=>$reserveId . '_credit','date_time'=>$array['Purchases']['Purchase']['@attributes']['CreationDateUTC']]);
                                        $winmoney = 0;
                                    }else{
                                        $betfinish = true;
                                        $winmoney = 0;
                                    }
                                }
                                
                            }else if($wonNumber = $betlinesNumber){

                                if(isset($prevStatRecord)){
                                    $winmoney = $prevStatRecord->win + $amount;
                                    $user->balance = $user->balance + $amount;
                                    $user->save();

                                    $prevStatRecord->update(['balance'=>$user->balance,'win' => $winmoney,'game_id'=>$reserveId . '_credit','date_time'=>$array['Purchases']['Purchase']['@attributes']['CreationDateUTC']]);
                                    $winmoney = 0;
                                }else{
                                    $statRecord = \VanguardLTE\StatGame::where(['user_id' => $custId,'game_id'=>$reserveId . '_debit','roundid'=>$purchaseId])->first();
                                    if(isset($statRecord)){                                        
                                        $winmoney = $amount;
                                        $winmoney = $statRecord->win + $winmoney;
                                        $user->balance = $user->balance + $amount;
                                        $user->save();
                                        
                                        $statRecord->update(['balance'=>$user->balance,'win' => $winmoney,'game_id'=>$reserveId . '_credit','date_time'=>$array['Purchases']['Purchase']['@attributes']['CreationDateUTC']]);
                                        $winmoney = 0;
                                    }else{
                                        $creditRecords = \VanguardLTE\BTiTransaction::where(['user_id' => $custId,'purchase_id'=>$purchaseId,'status'=>6])->get();
                                        if(isset($creditRecords)){
                                            foreach($creditRecords as $creditRecord){
                                                if(strpos($creditRecord,'Combo Bonus')== false){
                                                    $tempWinMoney += $creditRecord->amount;
                                                }else{
                                                    $comboCase = true;
                                                    $comboMoney += $creditRecord->amount;
                                                }                                    
                                            }
                                        }
                                        $winmoney = $tempWinMoney;
                                        $betfinish = true;
                                    }
                                }
                                
                                
                            }
                        }
                        
                    } else{
                        $creditRecords = \VanguardLTE\BTiTransaction::where(['user_id' => $custId,'reserve_id'=>$reserveId,'status'=>6])->get();
                        if(isset($creditRecords)){
                            foreach($creditRecords as $creditRecord){
                                $tempWinMoney += $creditRecord->amount;
                            }                                                                
                        }
                        $winmoney = $tempWinMoney;
                    }
                }else{
                    if( $array['Purchases']['Purchase']['@attributes']['CurrentStatus'] = 'Closed' && $betlinesNumber == $settledNumber){
                        
                        //$debitCustomerRecord->update(['status' => 6]);
                        $prevStatRecord = \VanguardLTE\StatGame::where(['user_id' => $custId,'game_id'=>$reserveId . '_credit','roundid'=>$purchaseId])->first();
                        $statRecord = \VanguardLTE\StatGame::where(['user_id' => $custId,'game_id'=>$reserveId . '_debit','roundid'=>$purchaseId])->first();
                        if(!$prevStatRecord){
                            if(isset($statRecord)){
                                $winmoney = $amount;
                                $user->balance = $user->balance + $winmoney;
                                $user->save();
                                $statRecord->update(['balance'=>$user->balance,'bet'=>0,'win' => $statRecord->win + $amount,'game_id'=>$reserveId . '_credit','date_time'=>$array['Purchases']['Purchase']['@attributes']['CreationDateUTC']]);
                                $winmoney = 0;
                            }else{     
                                $creditRecords = \VanguardLTE\BTiTransaction::where(['user_id' => $custId,'reserve_id'=>$reserveId,'status'=>6])->get();
                                if(isset($creditRecords)){
                                    foreach($creditRecords as $creditRecord){
                                        $tempWinMoney += $creditRecord->amount;
                                    }      
                                    $winmoney = $tempWinMoney;                                                           
                                }else{
                                    $winmoney = $amount;
                                }
                                                          
                                
                                $betfinish = true;
                            }
                            
                        }else{
                            
                            if(isset($statRecord)){
                                $winmoney = $amount;
                                $user->balance = $user->balance + $winmoney;
                                $user->save();
                                $statRecord->update(['balance'=>$user->balance,'bet'=>0,'win' => $statRecord->win + $amount,'game_id'=>$reserveId . '_credit','date_time'=>$array['Purchases']['Purchase']['@attributes']['CreationDateUTC']]);
                                $winmoney = 0;
                            }else{
                                $winmoney = $amount;
                                $user->balance = $user->balance + $winmoney;
                                $user->save();
                                $prevStatRecord->update(['balance'=>$user->balance,'bet'=>0,'win' => $prevStatRecord->win + $amount,'game_id'=>$reserveId . '_credit','date_time'=>$array['Purchases']['Purchase']['@attributes']['CreationDateUTC']]);
                                $winmoney = 0;
                            }
                            
                        }
                        
                    }else{
                        $creditRecords = \VanguardLTE\BTiTransaction::where(['user_id' => $custId,'reserve_id'=>$reserveId,'status'=>6])->get();
                        if(isset($creditRecords)){
                            foreach($creditRecords as $creditRecord){
                                $tempWinMoney += $creditRecord->amount;
                            }                                                                
                        }
                        $winmoney = $tempWinMoney;
                    }
                }




                $debitedBalance = $user->balance + $winmoney;
                $debitCustomerRecord->update(['balance'=>$debitedBalance]);

                if($betfinish == true){
                    //$prevStatRecord = \VanguardLTE\StatGame::where(['user_id' => $custId,'game_id'=>$reserveId,'roundid'=>$purchaseId])->first();

                    $user->balance = $debitedBalance;
                    $user->save();
                    if($comboCase == true){
                        $winmoney = $winmoney + $comboMoney;
                    }
                    \VanguardLTE\StatGame::create([
                        'user_id' => $user->id, 
                        'balance' => $user->balance, 
                        'bet' => 0, 
                        'win' => $winmoney,
                        'game' =>  'sports', 
                        'type' => 'sports',
                        'percent' => 0, 
                        'percent_jps' => 0, 
                        'percent_jpg' => 0, 
                        'profit' => 0, 
                        'denomination' => 0, 
                        'shop_id' => $user->shop_id,
                        'category_id' => 61,
                        'game_id' => $reserveId . '_credit',
                        'roundid' =>  $purchaseId,
                        'date_time' => $array['Purchases']['Purchase']['@attributes']['CreationDateUTC']
                    ]);
                    //$prevStatRecord->update(['balance'=>$user->balance,'bet'=>$record->amount,'win' => $winmoney,'date_time'=>$array['Purchases']['Purchase']['@attributes']['CreationDateUTC']]);
                }                
            }else{
                $debitedBalance = $user->balance;
                $sendTrxId = $debitCustomerRecord->id;
            }

            
            \DB::commit();
            return response(BTIController::toText([
                'error_code' => 0,
                'error_message' => 'No error',
                'trx_id' => $sendTrxId,
                'balance' => $debitedBalance
            ]))->header('Content-Type', 'text/plain');
        }

        public function sendSession(\Illuminate\Http\Request $request){

            $data = json_decode($request->getContent(), true);
            $userToken = isset($data['token'])?$data['token']:'';

            $userRecord = \VanguardLTE\User::where(['api_token' => $userToken])->first();
            $status = 'success';
            $balance = 0;
            if(!$userRecord || $userRecord == null){
                $status = 'error';
            }
            $balance = $userRecord->balance;
            return response()->json([
                'status' => $status,
                'balance' => $balance
            ]);
        }


        public static function getgamelink($gamecode)
        {
            $detect = new \Detection\MobileDetect();

            if ($detect->isiOS() || $detect->isiPadOS())
            {
                $url = BTIController::makegamelink();
            }
            else
            {
                $url = '/spbt1/golobby' . $gamecode;
            }
            if ($url)
            {
                return ['error' => false, 'data' => ['url' => $url]];
            }
            return ['error' => true, 'msg' => '로그인하세요'];
        }

        public function embedGACgame(\Illuminate\Http\Request $request)
        {
            $url = BTIController::makegamelink();
            if ($url)
            {
                return view('frontend.Default.games.apigame',compact('url'));
            }
            else
            {
                abort(404);
            }
        }

        public static function makegamelink()
        {
            $user = auth()->user();
            $token = $user->remember_token;
            $url = config('app.bti_api') . '/?operatorToken='. $token;
            return $url;
        }


        public static function getgamedetail(\VanguardLTE\StatGame $stat){
        // public static function getgamedetail(){
        //     $stat = \VanguardLTE\StatGame::where(['user_id'=>23,'game_id'=>485359016982700032])->first();

            $bet_string = [
                '싱글',	
                '더블',	
                '트레블',	
                '트릭시',
                '페이턴트', 
                '얀키',
                '럭키 15',
                '슈퍼 얀키',
                '럭키 31',
                '하인츠',
                '럭키 63',
                '슈퍼 하인츠',
                '골리앗'               
            ]; 
            $bettype_string = [
                'Single bets',
                'Doubles X 1 bet',
                'Trebles X 1 bet',
                'Trixie',
                'Patent',
                'Yankee',
                'Lucky15',
                'SuperYankee',
                'Lucky31',
                'Heinz',
                'Lucky63',
                'SuperHeinz',
                'Goliath'
            ];           
            $gametype = 'BTISports';
            $reserveId = explode('_',$stat->game_id)[0];;
            $reqId = $stat->roundid;
            $user = $stat->user;
            if (!$user)
            {
                return null;
            }

            $waitRecord = false;
            $btiRecords = \VanguardLTE\BTiTransaction::where(['user_id'=>$user->id,'reserve_id'=>$reserveId])->get();
            
            $responseData = [];
            $count=0;
            foreach($btiRecords as $btiRecord){
                if($btiRecord->status > 3){
                    if($btiRecord->status == 4){
                        $array = json_decode($btiRecord->data,true);
                        $responseData[0][$count] = $array;
                    }else if($btiRecord->status == 5){
                        $array = json_decode($btiRecord->data,true);
                        $responseData[1][$count] = $array;
                    }else if($btiRecord->status == 6){
                        $array = json_decode($btiRecord->data,true);
                        $responseData[2][$count] = $array;
                    }
                    $count++;
                }
            }
            $stat = [];
            $result = [];
            $bets = [];
            if(!isset($responseData[1]) && !isset($responseData[2])){
                //array_push($stat,'대기');
                $tempArry = [];
                if(count($responseData[0][0]['Bet'])>1){
                    if(isset($responseData[0][0]['Bet'][0]))
                    {
                        for($i = 0;$i<count($responseData[0][0]['Bet']);$i++){
                            $tempArry = $responseData[0][0]['Bet'][$i]['@attributes'];
                            $result[$i] = BTIController::getCommitInfo($tempArry,$bettype_string,$bet_string);
                        }
                    }else if(isset($responseData[0][0]['Bet']['Lines'])){
                        for($i = 0;$i<count($responseData[0][0]['Bet']['Lines']);$i++){
                            $tempArry = $responseData[0][0]['Bet']['Lines'][$i]['@attributes'];
                            $result[$i] = BTIController::getCommitInfo($tempArry,$bettype_string,$bet_string);
                        }
                    }
                    
                    
                }else{
                    $tempArry = $responseData[0][0]['Bet']['@attributes'];
                    $result[0] = BTIController::getCommitInfo($tempArry,$bettype_string,$bet_string);
                }
                
            }else if(!isset($responseData[1])){
                for($i=1;$i<=count($responseData[2]);$i++){
                    $tempArry = [];
                    $tempArry = $responseData[2][$i]['Purchases'];
                    $betType = '';
                    if(isset($tempArry['Purchase']['Selections']['Selection'][0])){
                        for($j=0;$j<count($tempArry['Purchase']['Selections']['Selection']);$j++){
                            if(isset($tempArry['Purchase']['Selections']['Selection'][$j]['Changes']['Change']['Bets']['Bet'][0])){
                                $result = BTIController::getCreditInfo($tempArry['Purchase']['Selections']['Selection'][$j],$bet_string,$bettype_string);
                            }else{
                                $result[$j] = BTIController::getCreditInfo($tempArry['Purchase']['Selections']['Selection'][$j],$bet_string,$bettype_string);
                            }
                            
                        }
                        
                    }else{
                        $result[$i - 1] = BTIController::getCreditInfo($tempArry['Purchase']['Selections']['Selection'],$bet_string,$bettype_string);
                    }
                    
                }
            }else if(isset($responseData[1])){
                
                $debitStartNumber = count($responseData[2]);
                for($i=1;$i<=count($responseData[2]);$i++){
                    $tempArry = [];
                    $tempArry = $responseData[2][$i]['Purchases'];
                    $betType = '';
                    if(isset($tempArry['Purchase']['Selections']['Selection'][0])){
                        $result[$i - 1] = BTIController::getDebitInfo($tempArry['Purchase']['Selections']['Selection'][0],$bet_string,$bettype_string);
                    }else{
                        $result[$i - 1] = BTIController::getDebitInfo($tempArry['Purchase']['Selections']['Selection'],$bet_string,$bettype_string);
                    }
                    
                }
                
                $tempResult = [];
                for($i=1;$i<=count($responseData[1]);$i++){
                    $tempArry = [];
                    $tempArry = $responseData[1][$i + $debitStartNumber]['Purchases'];
                    $betType = '';
                    if(isset($tempArry['Purchase']['Selections']['Selection'][0])){
                        $tempResult[$i - 1] = BTIController::getDebitInfo($tempArry['Purchase']['Selections']['Selection'][0],$bet_string,$bettype_string);
                    }else{
                        $tempResult[$i - 1] = BTIController::getDebitInfo($tempArry['Purchase']['Selections']['Selection'],$bet_string,$bettype_string);
                    }
                }

                $tempGame = '';
                $tempValue = '';
                $tempValues = [];
                for($i=0;$i<count($result);$i++){
                    for($j=0;$j<count($tempResult);$j++){
                        if($result[$i]['game'] == $tempResult[$j]['game']){
                            if(strpos($result[$i]['stat'],'Combo Bonus') == false){
                                $tempValues = [];
                                if(strpos('콤보',$result[$i]['bettype'])){
                                    if(explode('(',$result[$i]['stat'])[0] > 0){
                                        $tempGameValue =  explode('(',$tempResult[$j]['stat'])[0] + explode('(',$result[$i]['stat'])[0];
                                        $result[$i]['stat'] = $tempGameValue . '(LOST)';
                                    }
                                }else{
                                    $tempGameValue =  explode('(',$tempResult[$j]['stat'])[0] + explode('(',$result[$i]['stat'])[0];
                                        $result[$i]['stat'] = $tempGameValue . '(LOST)';
                                }
                                
                                //$result[$i] = $tempResult[$j];
                                
                                $tempGame = $result[$i]['game']; 
                                $tempValues = explode('(',$result[$i]['stat']);
                                $tempValue = $tempValues[0];
                            }else{
                                $tempValues = [];
                                if($result[$i]['game'] == $tempResult[$j]['game']){
                                    $tempGameValue =  explode('(',$tempResult[$j]['stat'])[0] + explode('(',$result[$i]['stat'])[0];
                                    $tempResult[$j]['stat'] = $tempGameValue . '(Combo Bonus)';
                                }
                                $result[$i] = $tempResult[$j];
                            }                   
                            
                        }
                    }
                }
            }
            
            return [
                'type' => $gametype,
                'result' => $result
            ];

        }
        public static function getCommitInfo($tempArry,$bettype_string,$bet_string){
            $betType = '';
            $statString = '';
            if($tempArry['BetTypeID'] == 1){
                $betType = '싱글';
            }else if($tempArry['BetTypeID'] == 2){
                $betType = '콤보';
            }else if($tempArry['BetTypeID'] == 3){
                $betType = '시스템';
            }
            if(strpos($tempArry['BetTypeName'],'System')){                            
                $statString = '시스템' . explode(' ',$tempArry['BetTypeName'])[1];
            }else if(strpos($tempArry['BetTypeName'],'folds')){
                $statString = explode(' ',$tempArry['BetTypeName'])[0] . '폴드';
            }else{
                for($j=0;$j<count($bettype_string);$j++){
                    if($bettype_string[$j] == $tempArry['BetTypeName']){
                        $statString = $bet_string[$j];
                    }
                }
            }
            
            $result = [
                'date' => $tempArry['CreationDate'],
                'odd'  => $tempArry['OddsDec'],
                'yourbet' => $tempArry['YourBet'],
                'score'  => $tempArry['Score'],
                'branchname' => $tempArry['BranchName'],
                'leaguename' => $tempArry['LeagueName'],
                'game'  => $tempArry['HomeTeam'] . ' vs ' . $tempArry['AwayTeam'],
                'bettype' => $betType,
                'bettypename' => $statString, 
                'stat'   =>  '대기'
            ];
            return $result;
        }

        public static function getCreditInfo($tempArry,$bet_string,$bettype_string){
            $betType = '';
            $statString = '';
            if(isset($tempArry['Changes']['Change']['Bets']['Bet'][0])){
                if($tempArry['Changes']['Change']['Bets']['Bet'][0]['@attributes']['BetTypeID'] == 1){
                    $betType = '싱글';
                }else if($tempArry['Changes']['Change']['Bets'][0]['Bet']['@attributes']['BetTypeID'] == 2){
                    $betType = '콤보';
                }else if($tempArry['Changes']['Change']['Bets'][0]['Bet']['@attributes']['BetTypeID'] == 3){
                    $betType = '시스템';
                }
                $tempStat = '';
                $result = [];
                for($i = 0;$i<count($tempArry['Changes']['Change']['Bets']['Bet']);$i++){
                    if(isset($tempArry['Changes']['Change']['Bets']['Bet'][$i]['@attributes']['ActionType'])){
                        $tempStat = $tempArry['Changes']['Change']['Bets']['Bet'][$i]['@attributes']['Amount'] . '(' . $tempArry['Changes']['Change']['Bets']['Bet'][$i]['@attributes']['ActionType'] . ')';
                        //break;
                    }else{

                        $tempStat = $tempArry['Changes']['Change']['Bets']['Bet'][$i]['@attributes']['Amount'] . '(' . $tempArry['Changes']['Change']['@attributes']['NewStatus'] . ')';
                    }
                    if(strpos($tempArry['Changes']['Change']['Bets']['Bet'][$i]['@attributes']['BetType'],'System')){                            
                        $statString = '시스템' . explode(' ',$tempArry['Changes']['Change']['Bets']['Bet'][$i]['@attributes']['BetType'])[1];
                    }else if(strpos($tempArry['Changes']['Change']['Bets']['Bet'][$i]['@attributes']['BetType'],'folds')){
                        $statString = explode(' ',$tempArry['Changes']['Change']['Bets']['Bet'][$i]['@attributes']['BetType'])[0] . '폴드';
                    }else{
                        for($j=0;$j<count($bettype_string);$j++){
                            if($bettype_string[$j] == $tempArry['Changes']['Change']['Bets']['Bet'][$i]['@attributes']['BetType']){
                                $statString = $bet_string[$j];
                            }
                        }
                    }
                    $result[$i] = [
                        'date' => $tempArry['Changes']['Change']['@attributes']['DateUTC'],
                        'odd'  => $tempArry['@attributes']['OddsInUserStyle'],
                        'yourbet' => $tempArry['@attributes']['YourBet'],
                        'score'  => $tempArry['@attributes']['Score'],
                        'branchname' => $tempArry['@attributes']['BranchName'],
                        'leaguename' => $tempArry['@attributes']['LeagueName'],
                        'game'  => $tempArry['@attributes']['HomeTeam'] . ' vs ' . $tempArry['@attributes']['AwayTeam'],
                        'bettype' => $betType,
                        'bettypename' => $statString,
                        'stat'  => $tempStat
                    ];
                    
                }
                return $result;
                
            }else{
                if($tempArry['Changes']['Change']['Bets']['Bet']['@attributes']['BetTypeID'] == 1){
                    $betType = '싱글';
                }else if($tempArry['Changes']['Change']['Bets']['Bet']['@attributes']['BetTypeID'] == 2){
                    $betType = '콤보';
                }else if($tempArry['Changes']['Change']['Bets']['Bet']['@attributes']['BetTypeID'] == 3){
                    $betType = '시스템';
                }
                $tempStat = '';
                if(isset($tempArry['Changes']['Change']['Bets']['Bet']['@attributes']['ActionType'])){
                    $tempStat = $tempArry['Changes']['Change']['Bets']['Bet']['@attributes']['Amount'] . '(' . $tempArry['Changes']['Change']['Bets']['Bet']['@attributes']['ActionType'] . ')';
                }else{
                    $tempStat = $tempArry['Changes']['Change']['Bets']['Bet']['@attributes']['Amount'] . '(' . $tempArry['Changes']['Change']['@attributes']['NewStatus'] . ')';
                }
                if(strpos($tempArry['Changes']['Change']['Bets']['Bet']['@attributes']['BetType'],'System')){                            
                    $statString = '시스템' . explode(' ',$tempArry['Changes']['Change']['Bets']['Bet']['@attributes']['BetType'])[1];
                }else if(strpos($tempArry['Changes']['Change']['Bets']['Bet']['@attributes']['BetType'],'folds')){
                    $statString = explode(' ',$tempArry['Changes']['Change']['Bets']['Bet']['@attributes']['BetType'])[0] . '폴드';
                }else{
                    for($j=0;$j<count($bettype_string);$j++){
                        if($bettype_string[$j] == $tempArry['Changes']['Change']['Bets']['Bet']['@attributes']['BetType']){
                            $statString = $bet_string[$j];
                        }
                    }
                }
                $result = [
                    'date' => $tempArry['Changes']['Change']['@attributes']['DateUTC'],
                    'odd'  => $tempArry['@attributes']['OddsInUserStyle'],
                    'yourbet' => $tempArry['@attributes']['YourBet'],
                    'score'  => $tempArry['@attributes']['Score'],
                    'branchname' => $tempArry['@attributes']['BranchName'],
                    'leaguename' => $tempArry['@attributes']['LeagueName'],
                    'game'  => $tempArry['@attributes']['HomeTeam'] . ' vs ' . $tempArry['@attributes']['AwayTeam'],
                    'bettype' => $betType,
                    'bettypename' => $statString,
                    'stat'  => $tempStat
                ];
                return $result;
            }
            

            
        }

        public static function getDebitInfo($tempArry,$bet_string,$bettype_string){
            $betType = '';
            if(isset($tempArry['Changes']['Change']['Bets']['Bet'][0])){
                if($tempArry['Changes']['Change']['Bets']['Bet'][0]['@attributes']['BetTypeID'] == 1){
                    $betType = '싱글';
                }else if($tempArry['Changes']['Change']['Bets']['Bet'][0]['@attributes']['BetTypeID'] == 2){
                    $betType = '콤보';
                }else if($tempArry['Changes']['Change']['Bets']['Bet'][0]['@attributes']['BetTypeID'] == 3){
                    $betType = '시스템';
                }

                $tempStat = '';
                for($i = 0;$i<count($tempArry['Changes']['Change']['Bets']['Bet']);$i++){
                    if(isset($tempArry['Changes']['Change']['Bets']['Bet'][$i]['@attributes']['ActionType'])){
                        $tempStat = $tempArry['Changes']['Change']['Bets']['Bet'][$i]['@attributes']['Amount'] . '(' . $tempArry['Changes']['Change']['Bets']['Bet'][$i]['@attributes']['ActionType'] . ')';
                        break;
                    }else{
                        
                        $tempStat = $tempArry['Changes']['Change']['Bets']['Bet'][$i]['@attributes']['Amount'] . '(' . $tempArry['Changes']['Change']['@attributes']['NewStatus'] . ')';
                    }
                }
                if(strpos($tempArry['Changes']['Change']['Bets']['Bet']['@attributes']['BetType'],'System')){                            
                    $statString = '시스템' . explode(' ',$tempArry['Changes']['Change']['Bets']['Bet']['@attributes']['BetType'])[1];
                }else if(strpos($tempArry['Changes']['Change']['Bets']['Bet']['@attributes']['BetType'],'folds')){
                    $statString = explode(' ',$tempArry['Changes']['Change']['Bets']['Bet']['@attributes']['BetType'])[0] . '폴드';
                }else{
                    for($j=0;$j<count($bettype_string);$j++){
                        if($bettype_string[$j] == $tempArry['Changes']['Change']['Bets']['Bet']['@attributes']['BetType']){
                            $statString = $bet_string[$j];
                        }
                    }
                }
                $result = [
                    'date' => $tempArry['Changes']['Change']['@attributes']['DateUTC'],
                    'odd'  => $tempArry['@attributes']['OddsInUserStyle'],
                    'yourbet' => $tempArry['@attributes']['YourBet'],
                    'score'  => $tempArry['@attributes']['Score'],
                    'branchname' => $tempArry['@attributes']['BranchName'],
                    'leaguename' => $tempArry['@attributes']['LeagueName'],
                    'game'  => $tempArry['@attributes']['HomeTeam'] . ' vs ' . $tempArry['@attributes']['AwayTeam'],
                    'bettype' => $betType,
                    'bettypename' => $statString, 
                    'stat'   => $tempStat
                ];
            }else{
                if($tempArry['Changes']['Change']['Bets']['Bet']['@attributes']['BetTypeID'] == 1){
                    $betType = '싱글';
                }else if($tempArry['Changes']['Change']['Bets']['Bet']['@attributes']['BetTypeID'] == 2){
                    $betType = '콤보';
                }else if($tempArry['Changes']['Change']['Bets']['Bet']['@attributes']['BetTypeID'] == 3){
                    $betType = '시스템';
                }
                $tempStat = '';
                if(isset($tempArry['Changes']['Change']['Bets']['Bet']['@attributes']['ActionType'])){
                    $tempStat = $tempArry['Changes']['Change']['Bets']['Bet']['@attributes']['Amount'] . '(' . $tempArry['Changes']['Change']['Bets']['Bet']['@attributes']['ActionType'] . ')';
                }else{
                    
                    $tempStat = $tempArry['Changes']['Change']['Bets']['Bet']['@attributes']['Amount'] . '(' . $tempArry['Changes']['Change']['@attributes']['NewStatus'] . ')';
                }
                if(strpos($tempArry['Changes']['Change']['Bets']['Bet']['@attributes']['BetType'],'System')){                            
                    $statString = '시스템' . explode(' ',$tempArry['Changes']['Change']['Bets']['Bet']['@attributes']['BetType'])[1];
                }else if(strpos($tempArry['Changes']['Change']['Bets']['Bet']['@attributes']['BetType'],'folds')){
                    $statString = explode(' ',$tempArry['Changes']['Change']['Bets']['Bet']['@attributes']['BetType'])[0] . '폴드';
                }else{
                    for($j=0;$j<count($bettype_string);$j++){
                        if($bettype_string[$j] == $tempArry['Changes']['Change']['Bets']['Bet']['@attributes']['BetType']){
                            $statString = $bet_string[$j];
                        }
                    }
                }
                $result = [
                    'date' => $tempArry['Changes']['Change']['@attributes']['DateUTC'],
                    'odd'  => $tempArry['@attributes']['OddsInUserStyle'],
                    'yourbet' => $tempArry['@attributes']['YourBet'],
                    'score'  => $tempArry['@attributes']['Score'],
                    'branchname' => $tempArry['@attributes']['BranchName'],
                    'leaguename' => $tempArry['@attributes']['LeagueName'],
                    'game'  => $tempArry['@attributes']['HomeTeam'] . ' vs ' . $tempArry['@attributes']['AwayTeam'],
                    'bettype' => $betType,
                    'bettypename' => $statString, 
                    'stat'   => $tempStat
                ];
            }
            
            

            return $result;
        }
        public static function toText($obj) {
            $response = '';
            foreach ($obj as $key => $value) {
                if ($value !== null) {
                    $response = "{$response}\r\n{$key}={$value}";
                }
            }
            return trim($response, "\r\n");
        }
    }
}


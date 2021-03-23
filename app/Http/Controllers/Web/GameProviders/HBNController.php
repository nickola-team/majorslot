<?php 
namespace VanguardLTE\Http\Controllers\Web\GameProviders
{
    use Illuminate\Support\Facades\Http;
    class HBNController extends \VanguardLTE\Http\Controllers\Controller
    {
        /*
        * UTILITY FUNCTION
        */

        public function checktransfer($tid)
        {
            $record = \VanguardLTE\HBNTransaction::Where('transferid',$tid)->get()->first();
            return $record;
        }
        public function generateCode($limit){
            $code = 0;
            for($i = 0; $i < $limit; $i++) { $code .= mt_rand(0, 9); }
            return $code;
        }

        public function gamecodetoname($code)
        {
            $gameList = \Illuminate\Support\Facades\Redis::get('bnglist');
            if (!$gameList)
            {
                $gameList = \BNGController::getgamelist();
            }
            $gamename = $code;
            if ($gameList)
            {
                $games = json_decode($gameList, true);
                foreach($games as $game)
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
            list($usec, $sec) = explode(" ", microtime());
            $microstr =  strval(((float)$usec + (float)$sec));
            $microstr = str_replace('.', '', $microstr);
            return $microstr;
        }
        /*
        * FROM HABANERO, BACK API
        */

        public function endpoint(\Illuminate\Http\Request $request)
        {
            $data = json_decode($request->getContent());            
            $type = $data->type;

            $response = [];
            switch ($type)
            {
                case 'playerdetailrequest':
                    $response = $this->verifyAuth($data);
                    break;
                case 'fundtransferrequest':
                    $response = $this->makeFundTransferResponse($data);
                    break;
                case 'queryrequest':
                    $response = $this->makeQueryResponse($data);
                    break;
                default:
                    $response = ['error' => 'Invalid Message type.'];
            }
            return response()->json($response, 200);
        }

        public function verifyAuth($data)
        {
            if (!isset($data->playerdetailrequest) || !isset($data->playerdetailrequest->token))
            {
                return ['error' => 'invalid request'];
            }

            $token = $data->playerdetailrequest->token;
            $user = \VanguardLTE\User::Where('username',$token)->get()->first();
            if (!$user || !$user->hasRole('user')){
                $externalResponse = $this->externalResponse();
                $playerResponse = $this->playerResponse();
                $fundsReponse = $this->fundsResponse();
                $playerResponse['status']['message'] = 'Invalid Auth token';
                $fundsReponse['status']['message'] = 'Invalid Auth token';
                $externalResponse['playerdetailresponse'] = $playerResponse;
                $externalResponse['fundtransferresponse'] = $fundsReponse;
                return $externalResponse;
            }

            $playerResponse =	$this->playerResponse();
            $playerResponse['status']['autherror'] = false;
            $playerResponse['status']['success'] = true;
            $playerResponse['accountid'] = strval($user->id);
            $playerResponse['accountname'] = $user->username;
            $playerResponse['currencycode'] = 'KRW';
            $playerResponse['balance'] = $user->balance;

            $externalResponse = $this->externalResponse();
            $externalResponse['playerdetailresponse'] = $playerResponse;
            return $externalResponse;
        }

        function makeQueryResponse($data){
            $queryResponse = $this->externalQueryResponse();
            if (!isset($data->queryrequest) || !isset($data->queryrequest->transferid))
            {
                return ['error' => 'invalid request'];
            }

            $transferId = $data->queryrequest->transferid;
    
            $transaction = $this->checktransfer($transferId);
    
            if($transaction != null){
                $queryResponse['fundtransferresponse']['status']['success'] =  true;
                $queryResponse['fundtransferresponse']['remotetransferid'] = $transaction->timestamp;
            }
    
            return $queryResponse;
        }

        function makeFundTransferResponse($data){
            $transactionStatus = true;
            $response = $this->externalResponse();
    
            $response['fundtransferresponse'] = $this->fundsResponse();
    
            $fundtransferrequest = $data->fundtransferrequest;
            $accountid = $fundtransferrequest->accountid;
            $user = \VanguardLTE\User::find($accountid);
            if (!$user || !$user->hasRole('user')){
                $externalResponse = $this->externalResponse();
                $playerResponse = $this->playerResponse();
                $fundsReponse = $this->fundsResponse();
                $playerResponse['status']['message'] = 'Invalid Auth token';
                $fundsReponse['status']['message'] = 'Invalid Auth token';
                $externalResponse['playerdetailresponse'] = $playerResponse;
                $externalResponse['fundtransferresponse'] = $fundsReponse;
                return $externalResponse;
            }
            $playerResponse = $this->playerResponse();
            $playerResponse['status']['autherror'] = false;
    
            if($fundtransferrequest->funds->debitandcredit == true){
                // Debit as well as credit is passed
    
                $debit = $fundtransferrequest->funds->fundinfo[0];
                $credit = $fundtransferrequest->funds->fundinfo[1];
    
                $debitResult = $this->updateBalance($user,$debit->amount, $debit);
    
                if($debitResult['Success'] == true){
                    $response['fundtransferresponse']['status']['successdebit'] = true;			
                    //now do the Credit
                    $creditResult = $this->updateBalance($user, $credit->amount,  $credit);
                            
                    if ($creditResult['Success'])
                    {
                        $response['fundtransferresponse']['status']['successcredit'] = true;
                        $response['fundtransferresponse']['status']['success'] = true;
                    }
                    else
                    {
                        $response['fundtransferresponse']['status']['success'] = false;
                        $response['fundtransferresponse']['status']['message'] = "Couldn't credit";
                    }
                    $response['fundtransferresponse']['balance'] = $creditResult['BalanceAfterTransaction']; //set back the current balance after the credit
    
                }else{
                    //if the debit failed, then return...
                    $response['fundtransferresponse']['status']['message'] = "Insufficient funds";
                    $response['fundtransferresponse']['balance'] = $debitResult['BalanceAfterTransaction']; //set back the current balance after the credit
                }
    
                $response['fundtransferresponse']['remotetransferid'] = uniqid('', true);
    
                return $response;
            }
    
            //This is NOT a D&C (Debit and Credit in one request) Package
            if($fundtransferrequest->funds->debitandcredit == false){
            
                //If its a refund request then:
                if($fundtransferrequest->isrefund){
                    $refund = $fundtransferrequest->funds->refund;
    
                    //check if the refund request has been processed before?
                    $previousRefundTransfer = $this->checktransfer($refund->transferid);
                    if($previousRefundTransfer != null){
                        $response['fundtransferresponse']['status']['success'] = true;
                        $response['fundtransferresponse']['status']['refundstatus'] = "1";
                        return $response;
                    }
                    //else we have not done the refund before, so now lets check the status of the transaction we trying to refund.. and see if it must be refunded or not.
    
                    $originalTransfer = $this->checktransfer($refund->originaltransferid);
                    if($originalTransfer != null){
                        $refundResult = $this->updateBalance($user, $refund->amount, $refund);
                        if($refundResult['Success']){
                            $response['fundtransferresponse']['status']['success'] = true;
                            $response['fundtransferresponse']['status']['refundstatus'] = "1";
                        }else{
                            $response['fundtransferresponse']['status']['success'] = false;
                            $response['fundtransferresponse']['status']['message'] = "Could Not Refund";
    
                        }
                    }else{
                        $response['fundtransferresponse']['status']['success'] = true;
                        $response['fundtransferresponse']['status']['refundstatus'] = "2";
                        $response['fundtransferresponse']['status']['message'] = "Original request never debited. So closing record.";
                    }
    
                    $response['fundtransferresponse']['balance'] = $user->balance;
                    return $response;
                }
    
                //Is this a ReCredit request?
                if($fundtransferrequest->isrecredit){
                    $recreditRequest = $fundtransferrequest->funds->fundinfo[0];
                    $originalTransfer = $this->checktransfer($recreditRequest->originaltransferid);
    
                    if($originalTransfer != null){
                        $response['fundtransferresponse']['status']['autherror'] = false;
                        $response['fundtransferresponse']['status']['success'] = true;
                        $response['fundtransferresponse']['balance'] = "0";
                        $response['fundtransferresponse']['status']['message'] = "Orignal Credit was done - so no need to do it again.";
                        return $response;
                    }
                    
                    //credit the amount
                    $recreditResult = $this->updateBalance($user, $recreditRequest->amount, $recreditRequest);
                    if($recreditResult['Success']){
                        $response['fundtransferresponse']['status']['autherror'] = false;
                        $response['fundtransferresponse']['status']['success'] = true;
                        $response['fundtransferresponse']['balance'] = "0";
                        $response['fundtransferresponse']['status']['message'] = "Original request never credited, so we did the re-credit";
                    }
                    return $response;
                }
    
                //**** It's not a Refund and not a Recredit, then its just a regular single transaction for debit OR credit ***
                $transferRequest = $fundtransferrequest->funds->fundinfo[0];
                
                $transferResult = $this->updateBalance($user, $transferRequest->amount, $transferRequest);
        
                if($transferResult['Success']){
                    $response['fundtransferresponse']['status']['success'] = true;				
                }else{
                    $response['fundtransferresponse']['status']['success'] = false;
                    $response['fundtransferresponse']['status']['autherror'] = false;
                    $response['fundtransferresponse']['status']['nofunds'] = $transferResult['NoFunds'];
                    $response['fundtransferresponse']['status']['message'] = 'Insufficient funds';
                }
                $response['fundtransferresponse']['remotetransferid'] = uniqid('', true);
                $response['fundtransferresponse']['balance'] = $transferResult['BalanceAfterTransaction'];
    
                if($response['fundtransferresponse']['balance'] == 0){
                    $response['fundtransferresponse']['balance'] = $user->balance;
                }
                return $response;
            }
        }
        public function updateBalance($user, $amount, $fundinfo){

            $result = array(
                'NoFunds' => false,
                'Success' => false,
                'BalanceAfterTransaction' => 0,
                'TransactionId' => ''
            );
            $continueTransaction = false;
    
            if($amount < 0){
                // It is a debit
                if($user->balance >= abs($amount)){
                    $continueTransaction = true;
                }
                else{
                    $continueTransaction = false;
                    $result['NoFunds'] = true;
    
                }
            }else{
                // It is a credit
                $continueTransaction = true;
            }
    
            if($continueTransaction){
                $user->balance = floatval(sprintf('%.4f', $user->balance + floatval($amount)));
                $user->save();
                $result['Success'] = true;
                $result['BalanceAfterTransaction'] = $user->balance;
            }

            $transaction = \VanguardLTE\HBNTransaction::create([
                'transferid' => $fundinfo->transferid, 
                'timestamp' => $this->microtime_string(),
                'data' => json_encode($fundinfo),
            ]);
    
            $result['TransactionId'] = $transaction->timestamp;
    
            return $result;
        }

        /*
        * FROM CONTROLLER, API
        */
        
        public static function getgamelist()
        {
            $gameList = \Illuminate\Support\Facades\Redis::get('bnglist');
            if ($gameList)
            {
                $games = json_decode($gameList, true);
                return $games;
            }

            $data = [
                'api_token' => config('app.bng_api_token'),
            ];
            $reqbody = json_encode($data);
            $response = Http::withHeaders([
                'Security-Hash' => BNGController::calcSecurityHash($reqbody)
                ])->withBody($reqbody, 'text/plain')->post(config('app.bng_game_server') . config('app.bng_project_name') . '/api/v1/provider/list/');
            if (!$response->ok())
            {
                return [];
            }
            $providerId = 1;
            $data = $response->json();
            $gameList = [];

            if (isset($data['items'])){
                foreach ($data['items'] as $item)
                {
                    $data1 = [
                        'api_token' => config('app.bng_api_token'),
                        'provider_id' => $item['provider_id']
                    ];
                    $reqbody = json_encode($data1);
                    $response = Http::withHeaders([
                        'Security-Hash' => BNGController::calcSecurityHash($reqbody)
                        ])->withBody($reqbody, 'text/plain')->post(config('app.bng_game_server') . config('app.bng_project_name') . '/api/v1/game/list/');
                    
                    if (!$response->ok())
                    {
                        return [];
                    }
                    $data1 = $response->json();
                    foreach ($data1['items'] as $game)
                    {
                        if ($game['type'] == "SLOT")
                        {
                            $gameList[] = [
                                'provider' => 'bng',
                                'gamecode' => $game['game_id'],
                                'name' => preg_replace('/[_\s]+/', '', $game['game_name']),
                                'title' => $game['i18n']['en']['title'],
                                'icon' => $game['i18n']['en']['banner_path'],
                            ];
                        }
                    }
                    \Illuminate\Support\Facades\Redis::set('bnglist', json_encode($gameList));
                }
            }
            return $gameList;


            
        }

        public static function getgamelink($gamecode)
        {
            $detect = new \Detection\MobileDetect();
            $key = [
                'token' => auth()->user()->username,
                'game' => $gamecode,
                'ts' => time(),
                'lang' => 'ko',
                'platform' => ($detect->isMobile() || $detect->isTablet())?'mobile':'desktop',
            ];
            $str_params = implode('&', array_map(
                function ($v, $k) {
                    return $k.'='.$v;
                }, 
                $key,
                array_keys($key)
            ));
            $url = config('app.bng_game_server') . config('app.bng_project_name') . '/game.html?'.$str_params;
            return ['error' => false, 'data' => ['url' => $url]];
        }

        public static function playerResponse(){
            return array(
                "status" =>
                    array(
                        "success" => false,
                        "nofunds" => false,
                        "successdebit" => false,
                        "successcredit" => false,
                        "message" => "",
                        "autherror" => true,
                        "refundstatus" => 0
                    ),
                "accountid" => "",
                "accountname" => "",
                "balance" => 0,
                "currencycode" => "",
                "country" => null,
                "fname" => null,
                "lname" => null,
                "email" => null,
                "tel" => null,
                "telalt" => null
            );
        }
    
        public static function fundsResponse(){
            return array("status" =>
                array(
                    "success" => false,
                    "nofunds" => false,
                    "successdebit" => false,
                    "successcredit" => false,
                    "message" => null,
                    "autherror" => true,
                    "refundstatus" => 0
                ),
                "balance" => "",
                "currencycode" => "KRW",
                "remoteid" => null,
                "remotetransferid" => ""
            );
        }
    
        public static function externalResponse(){
            return array(
                "playerdetailresponse" => null,
                "configdetailresponse" => null,
                "fundtransferresponse" => null,
                "jackpotdetailresponse" => null
            );
        }
    
        public static function externalQueryResponse(){
            return array(
                "fundtransferresponse" => array(
                    "status" => array(
                        "success" => false
                    ),
                    "remotetransferid" => null
                )
            );
        }
    }



}

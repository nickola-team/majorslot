<?php 
namespace VanguardLTE\Games\LoyKrathongCQ9
{
    class Server
    {
        public $demon = 1;
        public $winLines = [];
        public function get($request, $game, $userId) // changed by game developer
        {
            $response = '';
            \DB::beginTransaction();
            if( $userId == null ) 
            {
            	$response = 'unlogged';
                exit( $response );
            }// changed by game developer
            
            $user = \VanguardLTE\User::lockForUpdate()->find($userId);
            $credits = $userId == 1 ? $request->action === 'doInit' ? 5000 : $user->balance : null;
            $slotSettings = new SlotSettings($game, $userId, $credits);
            $find = array("#i", "#b", "#s", "#f", "#l");
            // $paramData = trim(file_get_contents('php://input'));
            $paramData = json_decode(str_replace($find, "", trim(file_get_contents('php://input'))), true);
            $paramData = $paramData['gameData'];
            $originalbet = 3;
            $slotSettings->SetBet();  
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 5}],"msg": null}');
                    $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetBalance().'],"evt": 1}');                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());

                    $slotSettings->SetGameData($slotSettings->slotId . 'Multiple',1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);

                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeAction',0);
                }else if($paramData['req'] == 2){
                    $gameDatas = $this->parseMessage($paramData['vals']);
                    $response_packet = [];
                    $result_vals = [];
                    for($i = 0; $i < count($gameDatas); $i++){
                        $gameData = json_decode($gameDatas[$i]);
                        $type = $gameData->Type;
                        $packet_id = $gameData->ID;
                        $emulatorType = 0;
                        $result_val = [];
                        $result_val['Type'] = $type;
                        $result_val['ID'] = $packet_id + 100;
                        $result_val['Version'] = 0;
                        $result_val['ErrorCode'] = 0;
                        $initDenom = 100;
                        if($packet_id == 11){
                            $denomDefine = [];
                            $betButtons = [];
                            for($k = 0; $k < count($slotSettings->Bet); $k++){
                                array_push($denomDefine, $initDenom);
                                array_push($betButtons, $slotSettings->Bet[$k] + 0);
                            }
                            $result_val['DenomDefine'] = $denomDefine;
                            $result_val['BetButton'] = $betButtons;
                            $result_val['DefaultDenomIdx'] = 3;
                            $result_val['MaxBet'] = 2000;
                            $result_val['MaxLine'] = 1;
                            $result_val['WinLimitLock'] = 300000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
                            $result_val['ExtendFeatureByGame'] = null;
                            $result_val['ExtendFeatureByGame2'] = [["name"=>"FeaturePay","value"=>"1175"]];
                            $result_val['IsReelPayType'] = true;
                            $result_val['Cobrand'] = null;
                            $result_val['PlayerOrderURL'] = config('app.cq9history') . '/platform/?gametoken=' . auth()->user()->api_token;
                            $result_val['PromotionData'] = null;
                            $result_val['IsShowFreehand'] = false;
                            $result_val['IsAllowFreehand'] = false;
                            $result_val['FeedbackURL'] = null;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['FreeSpinLeftTimesInfoList'] = null;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = [
                                "g" => "222",
                                "s" => "5.27.1.0",
                                "l" => "1.07",
                                "si" => "58"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 2;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["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"],["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"]];
                            $result_val['FGStripCount'] = 2;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["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"],["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"]];
                        }else if($packet_id == 31 || $packet_id == 42 || $packet_id == 33){
                            $lines = 20;
                            if($packet_id == 31 || $packet_id == 33){
                                $betline = $gameData->PlayBet;// * $gameData->MiniBet;
                            }else if($packet_id == 42){
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            }
                            if($packet_id == 42 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                                $slotEvent['slotEvent'] = 'freespin';
                                //$slotSettings->SetGameData($slotSettings->slotId . 'FreeAction',1);
                            }else if($packet_id == 33 && $slotSettings->GetGameData($slotSettings->slotId . 'Respin') > 0){
                                $slotEvent['slotEvent'] = 'respin';                               
                            }else{
                                if($packet_id == 31){
                                    $slotEvent['slotEvent'] = 'bet';
                                }
                                $pur_level = -1;
                                if(isset($gameData->ReelPay)){
                                    if($gameData->ReelPay > 0){
                                        $pur_level = 0;
                                    }
                                }
                                
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks  릴배치표 저장
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                                if(isset($gameData->PlayBet)){
                                    $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                    $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lines);
                                }
                                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur_level);
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', $betline);
                                
                                $slotSettings->SetBet(); 
                                $isBuyFreespin = false;
                                $allBet = ($betline /  $this->demon) * $lines;
                                if($pur_level == 0){
                                    $allBet = $allBet * 58.75;
                                    $isBuyFreespin = true;
                                }       
                                if(isset($slotEvent['slotEvent'])){
                                    $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                                }
                                $_sum = $allBet / 100 * $slotSettings->GetPercent();
                                
                                if(isset($slotEvent['slotEvent'])){
                                    $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent'], $isBuyFreespin);
                                }
                                
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '665' . substr($roundstr, 3, 9);
                                $slotSettings->SetGameData($slotSettings->slotId . 'GamePlaySerialNumber', $roundstr);
                            }

                            $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet);
                            $result_val['EmulatorType'] = $emulatorType;
                            // if($packet_id == 33){
                            //     if(isset($result_val['IsTriggerFG']) && $result_val['IsTriggerFG']==true){
                            //         $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            //     }
                            // }
                            $slotSettings->SaveGameData();
                        }else if($packet_id == 32 || $packet_id == 41){
                            $result_val['ErrorCode'] = 0;
                            // if($type == 3){

                            //     $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance() - $slotSettings->GetGameData($slotSettings->slotId . 'ScatterWin'));
                            // }
                            if($packet_id == 41){
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance() - $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'));
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                                $result_val['PlayerBet'] = $betline;
                                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                                /*if(isset($slotEvent['slotEvent']) && $slotEvent['$slotEvent'] == 'respin'){
                                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 3);
                                }*/
                                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                                $result_val['AccumlateWinAmt'] = ($stack['AccumlateWinAmt'] / $originalbet * $betline);
                                $result_val['AccumlateJPAmt'] = 0;
                                $result_val['ScatterPayFromBaseGame'] = ($stack['ScatterPayFromBaseGame'] / $originalbet * $betline);
                                $result_val['MaxRound'] = $stack['MaxRound'];
                                $result_val['AwardRound'] = $stack['AwardRound'];
                                $result_val['CurrentRound'] = $stack['CurrentRound'];
                                $result_val['MaxSpin'] = $stack['MaxSpin'];
                                $result_val['AwardSpinTimes'] = $stack['AwardSpinTimes'];
                                $result_val['Multiple'] = $slotSettings->GetGameData($slotSettings->slotId . 'Multiple');
                                $result_val['GameExtraData'] = "";
                            }else{
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                //$slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
                                //$slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                                $result_val['IsAllowFreeHand'] = false;
                            }
                        }else if($packet_id == 43){
                            $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                            $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            $result_val['TotalWinAmt'] = ($stack['TotalWinAmt'] / $originalbet * $betline);
                            $result_val['ScatterPayFromBaseGame'] = ($stack['ScatterPayFromBaseGame'] / $originalbet * $betline);
                            $result_val['NextModule'] = 0;
                            $result_val['GameExtraData'] = "";
                            //$slotSettings->SetGameData($slotSettings->slotId . 'Respin',1);
                        }
                        array_push($result_vals, count($result_vals) + 1);
                        array_push($result_vals, json_encode($result_val));
                    }
                    $val_str = '';
                    $response_packet["err"] = 200;
                    $response_packet["res"] = 2;
                    $response_packet["vals"] = $result_vals;
                    $response_packet["msg"] = null;
                    // $response = $this->encryptMessage('{"err":200,"res":2,"vals":['. $val_str .'],"msg":null}');
                    $response = $this->encryptMessage(json_encode($response_packet));
                    if(($packet_id == 32 || $packet_id == 31) && $type == 3){
                        $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetGameData($slotSettings->slotId . 'CurrentBalance').'],"evt": 1}');
                    }
                }else if($paramData['req'] == 202){
                    $gameDatas = $this->parseMessage($paramData['vals']);
                    $game_id = json_decode($gameDatas[0])->GameID;
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1, "'. $slotSettings->GetNewGameLink($game_id) .'"],"msg": null}');
                }else if($paramData['req'] == 1000){  // socket closed
                    $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                        $lines = 20;
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                        // FreeSpin Balance add
                        $slotEvent['slotEvent'] = 'freespin';
                        
                        $count = 0;
                        while($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                            $result_val = [];
                            $result_val['Type'] = 3;
                            $result_val['ID'] = 142;
                            $result_val['Version'] = 0;
                            $result_val['ErrorCode'] = 0;
                            $result_val['EmulatorType'] = 0;
                            if($slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') == 1){
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            }
                            $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet);
                        }
                    }

                    if($slotSettings->GetGameData($slotSettings->slotId . 'Respin') > 0){
                        $slotEvent['slotEvent'] = 'respin';
                        while($slotSettings->GetGameData($slotSettings->slotId . 'Respin') > 0){
                            $result_val = [];
                            $result_val['Type'] = 3;
                            $result_val['ID'] = 133;
                            $result_val['Version'] = 0;
                            $result_val['ErrorCode'] = 0;
                            $result_val['EmulatorType'] = 0;
                            $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet);
                        }
                    }
                }
            }else if(isset($paramData['irq']) && $paramData['irq'] == 1){
                $response = $this->encryptMessage('{"err":0,"irs":1,"vals":[1,-2147483648,2,988435344],"msg":null}');
                $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetGameData($slotSettings->slotId . 'CurrentBalance').'],"evt": 1}');
            }
            $slotSettings->SaveGameData();
            \DB::commit();
            return $response;
        }

        public function parseMessage($vals){
            $result = [];
            $length = count($vals);
            for($i = 0; $i < floor($length / 2); $i++){
                $result[$i] = $vals[$i * 2 + 1];
            }
            return $result;
        }
        public function generateResult($slotSettings, $result_val, $slotEvent, $betline, $lines, $originalbet){
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, $betline * $lines, $lines);
            $winType = $_spinSettings[0];
            $_winAvaliableMoney = $_spinSettings[1];
             //$winType = 'win';
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);

            if($slotEvent == 'freespin' || $slotEvent == 'respin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
                
            }else{
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $winType = 'bonus';
                }
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, $betline * $lines, $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin'));
                if($tumbAndFreeStacks == null){
                    $response = 'unlogged';
                    exit( $response );
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $tumbAndFreeStacks);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                $stack = $tumbAndFreeStacks[0];
            }
            $isState = true;
            $isTriggerFG =false;
            if(isset($stack['GamePlaySerialNumber'])){
                $stack['GamePlaySerialNumber'] = $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber');
            }
            if(isset($stack['BaseWin']) && $stack['BaseWin'] > 0){
                $stack['BaseWin'] = ($stack['BaseWin'] / $originalbet * $betline);
            }
            $totalWin = 0;
            if(isset($stack['TotalWin']) && $stack['TotalWin'] > 0){
                $stack['TotalWin'] = ($stack['TotalWin'] / $originalbet * $betline);
                $totalWin = $stack['TotalWin'];
            }
            if(isset($stack['AccumlateWinAmt']) && $stack['AccumlateWinAmt'] > 0){
                $stack['AccumlateWinAmt'] = ($stack['AccumlateWinAmt'] / $originalbet * $betline);
            }
            if(isset($stack['AccumlateJPAmt']) && $stack['AccumlateJPAmt'] > 0){
                $stack['AccumlateJPAmt'] = ($stack['AccumlateJPAmt'] / $originalbet * $betline);
            }
            if(isset($stack['ScatterPayFromBaseGame']) && $stack['ScatterPayFromBaseGame'] > 0){
                $stack['ScatterPayFromBaseGame'] = ($stack['ScatterPayFromBaseGame'] / $originalbet * $betline);
            }

            

            $slotSettings->SetGameData($slotSettings->slotId . 'Multiple',$stack['Multiple']);
            $awardSpinTimes = 0;
            $currentSpinTimes = 0;
            if($slotEvent == 'freespin'){
                $awardSpinTimes = $stack['AwardSpinTimes'];   
                if(isset($stack['CurrentSpinTimes'])){
                    $currentSpinTimes = $stack['CurrentSpinTimes'];    
                }
            }
            if(isset($stack['udsOutputWinLine']) && $stack['udsOutputWinLine'] != null){
                foreach($stack['udsOutputWinLine'] as $index => $value){
                    if($value['LinePrize'] > 0){
                        $value['LinePrize'] = ($value['LinePrize'] / $originalbet * $betline);
                    }
                    $stack['udsOutputWinLine'][$index] = $value;
                }
            }
            if($slotEvent != 'freespin' && isset($stack['IsTriggerFG'])){
                $isTriggerFG = $stack['IsTriggerFG'];
            }
            $freespinNum = 0;
            if(isset($stack['FreeSpin']) && count($stack['FreeSpin']) > 0){
                $freespinNum = $stack['FreeSpin'][0];
            }else{
                    if(isset($stack['IsTriggerFG']) && $stack['IsTriggerFG'] == true){
                        $freespinNum = 10;
                    }
            }

            $newRespin = false;

            if($slotEvent != 'freespin'){
                if($stack['IsRespin'] == true){
                    if(isset($stack['IsTriggerFG']) && $stack['IsTriggerFG'] == false){
                        $newRespin = true;
                        $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 1);
                        //$slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 12);
                    }
                }else{
                    $newRespin = false;
                    $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
                    /*if(isset($stack['IsTriggerFG']) && $stack['IsTriggerFG'] == true){
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount',$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1 );
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 1);
                    }*/
                }
            }
            

            $stack['Type'] = $result_val['Type'];
            $stack['ID'] = $result_val['ID'];
            $stack['Version'] = $result_val['Version'];
            $stack['ErrorCode'] = $result_val['ErrorCode'];
            $result_val = $stack;
            
            if($totalWin > 0){
                $slotSettings->SetBalance($totalWin);
                if($winType == 'bonus'){
                    $slotSettings->SetBank('bonus', -1 * $totalWin);   
                }else{
                    $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
                }
                //$slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + ($totalWin));
            }

            // $result_val['Multiple'] = 0;
            if($freespinNum > 0)
            {
                $isTriggerFG = true;
                if($slotEvent != 'freespin'){                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespinNum);
                }else{
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $freespinNum);
                }
                $isState = false;
            }
            if($slotEvent == "respin"){
                if(($stack['IsRespin'] == true && $stack['IsTriggerFG'] == true)){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 10);
                }
            }
            if($slotEvent == 'freespin'){                
                $isState = false;
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes && $stack['IsRespin'] == false){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
                    $isState = true;
                }
            }else if($slotEvent == 'respin' && $slotSettings->GetGameData($slotSettings->slotId . 'FreeAction') > 0){
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeAction',0);
                $slotEvent = 'freespin';
                $isState = true;
            }else if($newRespin == true){
                $isState = false;
            }
            

            $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
            if($isState == true){
                $allBet = ($betline /  $this->demon) * $lines;
                if($slotEvent == 'freespin' && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                    $allBet = $allBet * 58.75;
                }
                $slotSettings->SaveLogReport(json_encode($gamelog), $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
                
            }

            if($slotEvent != 'freespin' && $stack['WinType'] <2){
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                // if($slotEvent == 'respin' && ($stack['IsRespin'] == true && $stack['IsTriggerFG'] == true)){

                // }else{
                //     $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                // }
                
            }
            return $result_val;
        }
        public function parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines){
            $currentTime = $this->getCurrentTime();
            $allBet = ($betline /  $this->demon) * $lines;
            if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                $allBet = $allBet * 58.75;
            }
            $proof = [];
            $proof['win_line_data']             = [];
            $proof['symbol_data']               = $result_val['SymbolResult'];
            $proof['symbol_data_after']         = [];
            $proof['extra_data']                = $result_val['ExtraData'];
            $proof['reel_pos_chg']              = $result_val['ReellPosChg'];
            $proof['reel_len_change']           = $result_val['ReelLenChange'];

            if(isset($result_val['ReelPay'])){
                $proof['reel_pay']                  = $result_val['ReelPay'];
            }
            
            $proof['respin_reels']              = $result_val['RespinReels'];
            $proof['bonus_type']                = $result_val['BonusType'];
            $proof['special_award']             = $result_val['SpecialAward'];
            $proof['special_symbol']            = $result_val['SpecialSymbol'];
            $proof['is_respin']                 = $result_val['IsRespin'];
            if(isset($result_val['FreeSpin'])){
                $proof['fg_times']                  = $result_val['FreeSpin'];
            }
            
            $proof['fg_rounds']                 = floor($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') / 8);
            $proof['next_s_table']              = $result_val['NextSTable'];
            $proof['extend_feature_by_game']    = [];
            $proof['extend_feature_by_game2']   = [];
            foreach($result_val['ExtendFeatureByGame2'] as $item){
                $newItem = [];
                $newItem['name'] = $item['Name'];
                if(isset($item['Value'])){
                    $newItem['value'] = $item['Value'];
                }
                $proof['extend_feature_by_game2'][] = $newItem;
            }
            $proof['l_v']                       = "1.2.6";
            $proof['s_v']                       = "5.27.1.0";

            foreach( $result_val['udsOutputWinLine'] as $index => $outWinLine) 
            {
                $lineData = [];
                $lineData['line_extra_data']    = $outWinLine['LineExtraData'];
                $lineData['line_multiplier']    = $outWinLine['LineMultiplier'];
                $lineData['line_prize']         = $outWinLine['LinePrize'];
                $lineData['line_type']          = $outWinLine['LineType'];
                $lineData['symbol_id']          = $outWinLine['SymbolId'];
                $lineData['symbol_count']       = $outWinLine['SymbolCount'];
                $lineData['num_of_kind']        = $outWinLine['NumOfKind'];
                $lineData['win_line_no']        = $outWinLine['WinLineNo'];
                $lineData['win_position']       = $outWinLine['WinPosition'];
                array_push($proof['win_line_data'], $lineData);
            }
            if($slotEvent == 'freespin' || $slotEvent == 'respin'){
                $log = $slotSettings->GetGameData($slotSettings->slotId . 'GameLog');
                $log['actionlist'][1]['amount']     = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                $log['actionlist'][1]['eventtime']  = $currentTime;
                
                $log['detail']['wager']['order_time']   = $currentTime;
                $log['detail']['wager']['end_time']     = $currentTime;
                $log['detail']['wager']['total_win']    = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');

                if(isset($result_val['LockPos'])){
                    $proof['lock_position']         = $result_val['LockPos'];
                }
                

                $sub_log = [];
                /*if(isset($result_val['CurrentSpinTimes'])){
                    $sub_log['sub_no']              = $result_val['CurrentSpinTimes'];
                }*/
                $sub_log['sub_no']              = count($log['detail']['wager']['sub']) + 1;
                if($slotEvent == 'freespin'){
                    $sub_log['game_type']           = 51;
                }else{
                    $sub_log['game_type']           = 30;
                }
                $sub_log['rng']                 = $result_val['RngData'];
                $sub_log['multiple']            = $result_val['Multiple'];
                $sub_log['win']                 = $result_val['TotalWin'];
                $sub_log['win_line_count']      = $result_val['WinLineCount'];
                $sub_log['win_type']            = $result_val['WinType'];
                $sub_log['proof']               = $proof;
                array_push($log['detail']['wager']['sub'], $sub_log);
            }else{
                $log = [];
                $log['account']                 = $slotSettings->playerId;
                $log['parentacc']               = 'major_prod';
                $log['actionlist']              = [];
                $log['detail']                  = [];
                $bet_action = [];
                $bet_action['action']           = 'bet';
                $bet_action['amount']           = $allBet;
                $bet_action['eventtime']        = $currentTime;
                array_push($log['actionlist'], $bet_action);
                $win_action = [];
                $win_action['action']           = 'win';
                $win_action['amount']           = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                $win_action['eventtime']        = $currentTime;
                array_push($log['actionlist'], $win_action);

                $wager= [];
                $wager['seq_no']                = $result_val['GamePlaySerialNumber'];
                $wager['order_time']            = $currentTime;
                $wager['end_time']              = $currentTime;
                $wager['user_id']               = $slotSettings->playerId;
                $wager['game_id']               = "222";
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = $allBet;
                $wager['play_denom']            = "100";
                $wager['bet_multiple']          = $betline;
                $wager['rng']                   = $result_val['RngData'];
                $wager['multiple']              = $result_val['Multiple'];
                $wager['base_game_win']         = $result_val['TotalWin'];
                $wager['win_over_limit_lock']   = 0;
                $wager['game_type']             = 0;
                $wager['win_type']              = $result_val['WinType'];
                $wager['settle_type']           = 0;
                $wager['wager_type']            = 0;
                $wager['total_win']             = "'" . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . "'";
                $wager['win_line_count']        = $result_val['WinLineCount'];
                $wager['bet_tid']               =  'pro-bet-' . $result_val['GamePlaySerialNumber'];
                $wager['win_tid']               =  'pro-win-' . $result_val['GamePlaySerialNumber'];
                $wager['proof']                 = $proof;
                $wager['sub']                   = [];
                $wager['pick']                  = [];
                
                $log['detail']['wager']         = $wager;
            }
            $slotSettings->SetGameData($slotSettings->slotId . 'GameLog', $log);
            return $log;
        }
        public function getCurrentTime(){
            $date = new \DateTime(date('Y-m-d H:i:s'));
            $timezoneName = timezone_name_from_abbr("", -4*3600, false);
            $date->setTimezone(new \DateTimeZone($timezoneName));
            $time= $date->format(DATE_RFC3339_EXTENDED);
            return $time;
        }
        public function encryptMessage($param){
            $param = "~j~" . $param;
            return "~m~" . strlen($param) . "~m~" . $param;
        }
    }

}

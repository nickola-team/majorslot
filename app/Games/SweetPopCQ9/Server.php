<?php 
namespace VanguardLTE\Games\SweetPopCQ9
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
            $originalbet = 2;
            $slotSettings->SetBet();  
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 10}],"msg": null}');
                    $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetBalance().'],"evt": 1}');                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'TempTotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Multiple',1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
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
                            $result_val['MaxBet'] = 2500;
                            $result_val['MaxLine'] = 1;
                            $result_val['WinLimitLock'] = 300000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
                            $result_val['ExtendFeatureByGame'] = null;
                            $result_val['ExtendFeatureByGame2'] = null;
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
                                "g" => "206",
                                "s" => "5.27.1.0",
                                "l" => "2.5.4.0",
                                "si" => "32"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["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"]];
                            $result_val['FGStripCount'] = 1;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["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"]];
                        }else if($packet_id == 31 || $packet_id == 42 || $packet_id == 33){
                            $lines = 40;
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
                                
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks  릴배치표 저장
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TempTotalWin', 0);
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
                                $roundstr = '568' . substr($roundstr, 3, 9);
                                $slotSettings->SetGameData($slotSettings->slotId . 'GamePlaySerialNumber', $roundstr);
                            }

                            $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet);
                            $result_val['EmulatorType'] = $emulatorType;
                            
                            $slotSettings->SaveGameData();
                        }else if($packet_id == 32 || $packet_id == 41){
                            $result_val['ErrorCode'] = 0;
                            
                            if($packet_id == 41){
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance() - $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'));
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                                $result_val['PlayerBet'] = $betline;
                                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                                
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
                                $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
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
                        $lines = 40;
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
                
                 if($slotEvent != 'freespin'){
                    $totalWin = $stack['TotalWin'];
                 }
                
            }
            if(isset($stack['SpecialAward']) && $stack['SpecialAward'] > 0){
                $stack['SpecialAward'] = ($stack['SpecialAward'] / $originalbet * $betline);
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
            if($slotEvent == 'freespin'){
                if(isset($stack['ExtendFeatureByGame2']) && $stack['ExtendFeatureByGame2'][5]['Value']>0){
                    $stack['ExtendFeatureByGame2'][5]['Value'] = $stack['ExtendFeatureByGame2'][5]['Value'] / $originalbet * $betline;
                    if($stack['IsRespin'] == true){
                        $slotSettings->SetGameData($slotSettings->slotId . 'TempTotalWin', $stack['ExtendFeatureByGame2'][5]['Value']);
                    }                    
                }
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
                    }
                }else{
                    $newRespin = false;
                    $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
                }
            }else{
                if($stack['IsRespin'] == false){
                    $totalWin = $slotSettings->GetGameData($slotSettings->slotId . 'TempTotalWin') * intval($stack['Multiple']);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TempTotalWin', 0);
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
                
                $slotSettings->SaveLogReport(json_encode($gamelog), $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
                
            }

            if($slotEvent != 'freespin' && $stack['WinType'] <2){
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                
            }
            return $result_val;
        }
        public function parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines){
            $currentTime = $this->getCurrentTime();
            $allBet = ($betline /  $this->demon) * $lines;
            
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
                    $newItem['value'] = strval($item['Value']);
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
                    $sub_log['game_type']           = 50;
                }else{
                    $sub_log['game_type']           = 30;
                }
                $sub_log['rng']                 = $result_val['RngData'];
                $sub_log['multiple']            = $result_val['Multiple'];
                $sub_log['win']                 = $result_val['SpecialAward'];
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
                $wager['game_id']               = "206";
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

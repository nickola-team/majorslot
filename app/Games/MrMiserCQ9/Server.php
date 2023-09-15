<?php 
namespace VanguardLTE\Games\MrMiserCQ9
{
    class Server
    {
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
            $originalbet = 5;
            $slotSettings->SetBet();  
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 14}],"msg": null}');
                    $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetBalance().'],"evt": 1}');                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());

                    $slotSettings->SetGameData($slotSettings->slotId . 'Multiple',1);


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
                            $result_val['MaxBet'] = 8000;
                            $result_val['MaxLine'] = 1;
                            $result_val['WinLimitLock'] = 300000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
                            $result_val['ExtendFeatureByGame'] = null;
                            $result_val['ExtendFeatureByGame2'] = null;
                            $result_val['IsReelPayType'] = false;
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
                            $lines = 20;
                            if($packet_id == 31 || $packet_id == 33){
                                $betline = $gameData->PlayBet;// * $gameData->MiniBet;
                            }else if($packet_id == 42){
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            }
                            if($packet_id == 42 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                                $slotEvent['slotEvent'] = 'freespin';
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeAction',1);
                            }else if($packet_id == 33 && $slotSettings->GetGameData($slotSettings->slotId . 'Respin') > 0){
                                $slotEvent['slotEvent'] = 'respin';
                            }else{
                                if($packet_id == 31){
                                    $slotEvent['slotEvent'] = 'bet';
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
                                
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', $betline);
                                
                                $slotSettings->SetBet();        
                                if(isset($slotEvent['slotEvent'])){
                                    $slotSettings->SetBalance(-1 * ($betline * $lines), $slotEvent['slotEvent']);
                                }
                                $_sum = ($betline * $lines) / 100 * $slotSettings->GetPercent();
                                if(isset($slotEvent['slotEvent'])){
                                    $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                }
                                
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '568' . substr($roundstr, 3, 9);
                                $slotSettings->SetGameData($slotSettings->slotId . 'GamePlaySerialNumber', $roundstr);
                            }

                            $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet,$packet_id);
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
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
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
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeAction',0);
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
                            $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet,1000);
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
                            $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet,1000);
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
        public function generateResult($slotSettings, $result_val, $slotEvent, $betline, $lines, $originalbet,$packId){
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
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, $betline * $lines);
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
                $currentSpinTimes = $stack['CurrentSpinTimes'];    
            }
            foreach($stack['udsOutputWinLine'] as $index => $value){
                if($value['LinePrize'] > 0){
                    $value['LinePrize'] = ($value['LinePrize'] / $originalbet * $betline);
                }
                $stack['udsOutputWinLine'][$index] = $value;
            }
            if($slotEvent != 'freespin' && isset($stack['IsTriggerFG'])){
                $isTriggerFG = $stack['IsTriggerFG'];
            }
            $freespinNum = 0;
            // if(isset($stack['FreeSpin']) && count($stack['FreeSpin']) > 0){
            //     $freespinNum = $stack['FreeSpin'][0];
            // }else{
            //         if(isset($stack['IsTriggerFG']) && $stack['IsTriggerFG'] == true){
            //             $freespinNum = 10;
            //         }
            // }
            if(isset($stack['IsTriggerFG']) && $stack['IsTriggerFG'] == true){
                $freespinNum = 10;
            }

            $newRespin = false;
            if($slotEvent != 'freespin'){
                if($stack['IsRespin'] == true){
                    if(isset($stack['IsTriggerFG']) && $stack['IsTriggerFG'] == false){
                        $newRespin = true;
                        $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 1);
                        //$slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 12);
                    }else{
                        $newRespin = false;
                        $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
                    }
                }else{
                    $newRespin = false;
                    $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
                    if(isset($stack['IsTriggerFG']) && $stack['IsTriggerFG'] == true){                        
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 1);
                    }
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
            if($slotEvent == 'freespin'){                
                $isState = false;
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes && $stack['AwardRound'] == $stack['CurrentRound'] && $stack['IsRespin'] == false){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    if($packId == 1000){
                        $isState = true;
                    }
                    
                }
            }else if($slotEvent == 'respin' && $slotSettings->GetGameData($slotSettings->slotId . 'FreeAction') > 0 && $stack['IsRespin'] == false){
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeAction',0);
                $slotEvent = 'freespin';
                $isState = true;
            }else if($newRespin == true){
                $isState = false;
            }
            

            $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
            if($isState == true){
                $slotSettings->SaveLogReport(json_encode($gamelog), $betline * $lines, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
                
            }

            if($slotEvent != 'freespin' && $freespinNum > 0){
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
            }
            return $result_val;
        }
        public function parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines){
            $currentTime = $this->getCurrentTime();
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
                $bet_action['amount']           = $betline * $lines;
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
                $wager['game_id']               = "225";
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = "'" . $betline * $lines. "'";
                $wager['play_denom']            = "100";
                $wager['bet_multiple']          = "'" . $betline . "'";
                $wager['rng']                   = $result_val['RngData'];
                $wager['multiple']              = $result_val['Multiple'];
                $wager['base_game_win']         = "'" . $result_val['TotalWin'] . "'";
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

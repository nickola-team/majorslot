<?php 
namespace VanguardLTE\Games\TenfoldEggCQ9
{
    class Server
    {
        public $demon = 10;
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
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 5}],"msg": null}');
                    $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetBalance().'],"evt": 1}');                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());

                    $slotSettings->SetGameData($slotSettings->slotId . 'winSpinArr', [0,0,0]);

                    $slotSettings->SetGameData($slotSettings->slotId . 'Multiple',1);

                    $slotSettings->SetGameData($slotSettings->slotId . 'SymbolIndex', -1);
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
                                array_push($denomDefine, $initDenom * $this->demon);
                                array_push($betButtons, $slotSettings->Bet[$k] + 0);
                            }
                            $result_val['DenomDefine'] = $denomDefine;
                            $result_val['BetButton'] = $betButtons;
                            $result_val['DefaultDenomIdx'] = 2;
                            $result_val['MaxBet'] = 200;
                            $result_val['MaxLine'] = 1;
                            $result_val['WinLimitLock'] = 30000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
                            $result_val['ExtendFeatureByGame'] = null;
                            $result_val['ExtendFeatureByGame2'] = [];
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
                            $result_val['Tag'] = [
                                "g" => "196",
                                "s" => "5.27.1.0",
                                "l" => "1.1.11",
                                "si" => "41"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = -1;
                            $result_val['BGStripCount'] = 0;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [];
                            $result_val['FGStripCount'] = 0;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [];
                        }else if($packet_id == 34 || $packet_id == 42 || $packet_id == 33){
                            $lines = 1;
                            if($packet_id == 34 || $packet_id == 33){
                                //$betline = $gameData->PlayBet;// * $gameData->MiniBet;
                                $betline = 1;
                            }else if($packet_id == 42){
                                $betline = 1;
                            }
                            if($packet_id == 42 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                                $slotEvent['slotEvent'] = 'bonus';
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeAction',1);
                            }else if($packet_id == 33 && $slotSettings->GetGameData($slotSettings->slotId . 'Respin') > 0){
                                $slotEvent['slotEvent'] = 'respin';
                            }else{
                                if($packet_id == 34){
                                    $slotEvent['slotEvent'] = 'bet';
                                }else{
                                    
                                }
                                $pur_level = -1;
                                
                                if(isset($gameData->PlayBet)){
                                    $betvalue = $gameData->PlayBet;
                                }else{
                                    $betvalue = $gameData->PlayerBetMultiples;
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
                                }else{
                                    $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayerBetMultiples);
                                }
                                $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lines);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur_level);
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet',($betline *  $this->demon) * $gameData->MiniBet * $betvalue[0]);
                                $slotSettings->SetBet(); 
                                $isBuyFreespin = false;
                                
                                $allBet = ($betline *  $this->demon) * $lines * $gameData->MiniBet * $betvalue[0];
                                if($pur_level >= 0){
                                    //$allBet = $allBet * 10;
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
                                $roundstr = '668' . substr($roundstr, 3, 9);
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
                                /*if(isset($slotEvent['slotEvent']) && $slotEvent['$slotEvent'] == 'respin'){
                                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 3);
                                }*/
                                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                                $result_val['AccumlateWinAmt'] = $stack['AccumlateWinAmt'];
                                $result_val['AccumlateJPAmt'] = 0;
                                $result_val['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'];
                                $result_val['MaxRound'] = $stack['MaxRound'];
                                $result_val['AwardRound'] = $stack['AwardRound'];
                                $result_val['CurrentRound'] = $stack['CurrentRound'];
                                $result_val['MaxSpin'] = $stack['MaxSpin'];
                                $result_val['AwardSpinTimes'] = $stack['AwardSpinTimes'];
                                $result_val['Multiple'] = $slotSettings->GetGameData($slotSettings->slotId . 'Multiple');
                                $result_val['GameExtraData'] = "";
                            }else{
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                            }
                        }else if($packet_id == 43){
                            $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                            $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            $result_val['TotalWinAmt'] = ($stack['TotalWinAmt'] / $originalbet * $betline[0]);
                            $result_val['ScatterPayFromBaseGame'] = ($stack['ScatterPayFromBaseGame'] / $originalbet * $betline[0]);
                            $result_val['NextModule'] = 0;
                            $result_val['GameExtraData'] = "";
                            $slotSettings->SetGameData($slotSettings->slotId . 'Respin',1);
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
                    if(($packet_id == 32 || $packet_id == 34) && $type == 3){
                        $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetGameData($slotSettings->slotId . 'CurrentBalance').'],"evt": 1}');
                    }
                }else if($paramData['req'] == 202){
                    $gameDatas = $this->parseMessage($paramData['vals']);
                    $game_id = json_decode($gameDatas[0])->GameID;
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1, "'. $slotSettings->GetNewGameLink($game_id) .'"],"msg": null}');
                }else if($paramData['req'] == 1000){  // socket closed
                    if($slotSettings->GetGameData($slotSettings->slotId . 'Respin') > 0){
                        // FreeSpin Balance add
                        $slotEvent['slotEvent'] = 'bonus';
                        $betline = 1;// $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                        $lines = 1;
                        $count = 0;
                        while($slotSettings->GetGameData($slotSettings->slotId . 'Respin') > 0){
                            $result_val = [];
                            $result_val['Type'] = 3;
                            $result_val['ID'] = 142;
                            $result_val['Version'] = 0;
                            $result_val['ErrorCode'] = 0;
                            $result_val['EmulatorType'] = 0;
                            // if($slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') == 1){
                                 $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 2);
                            // }
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
            $betValue = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, $betline * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet') * $betValue[0], $lines);
            $winType = $_spinSettings[0];
            $_winAvaliableMoney = $_spinSettings[1];
            $purValue = -1;
            $tempPurValue = -1;
            $winType = 'bonus';
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);
            
            if($slotEvent == 'bonus' || $slotEvent == 'respin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
            }else{
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $winType = 'bonus';
                }
                $randValue = mt_rand(0,1000);
                $tempPurValue = $slotSettings->GetGameData($slotSettings->slotId . 'SymbolIndex');
                if($winType != 'bonus'){
                    if($randValue<900){
                        $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', -1);
                    }else{                        
                        if($tempPurValue == 0){
                            if($randValue<950){
                                $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', 1);
                            }else{
                                $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', 2);
                            }
                        }else if($tempPurValue == 1){
                            if($randValue<950){
                                $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', 0);
                            }else{
                                $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', 2);
                            }
                        }else if($tempPurValue == 2){
                            if($randValue<950){
                                $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', 0);
                            }else{
                                $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', 1);
                            }
                        }else if($tempPurValue == -1){
                            if($randValue<940){
                                $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', 0);
                            }else if($randValue<970){
                                $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', 1);
                            }else{
                                $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', 2);
                            }
                        }
                    } 
                }else{
                    if($tempPurValue == 0){
                        $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', 5);
                    }else if($tempPurValue == 1){
                        $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', 4);
                    }else if($tempPurValue == 2){
                        $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', 3);
                    }else if($tempPurValue == 3){
                        $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', 0);
                    }else if($tempPurValue == 4){
                        $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', 1);
                    }else if($tempPurValue == 5){
                        $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', 2);
                    }else if($tempPurValue == -1){
                        $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', 6);
                    }
                }
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, ($betline / $originalbet) * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet') * $betValue[0], $slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount'));
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
                $stack['TotalWin'] = ($stack['TotalWin'] / $originalbet * $betline) * $betValue[0];
                $totalWin = $stack['TotalWin'] * $this->demon;
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

            
            if(isset($stack['Multiple'])){
                $slotSettings->SetGameData($slotSettings->slotId . 'Multiple',$stack['Multiple']);
            }
            
            $awardSpinTimes = 0;
            $currentSpinTimes = 0;
            if($slotEvent == 'bonus'){
                $awardSpinTimes = $stack['AwardSpinTimes'];
                if(isset($stack['CurrentSpinTimes'])){
                    $currentSpinTimes = $stack['CurrentSpinTimes'];    
                }
            }
            if(isset($stack['ExtraData'])){
                for($i=0;$i<count($stack['ExtraData']);$i++){
                    $stack['ExtraData'][$i] = ($stack['ExtraData'][$i] / $originalbet * $betline) * $betValue[0];
                }
            }
            
            if(isset($stack['udsOutputWinLine']) && $stack['udsOutputWinLine'] != null){
                foreach($stack['udsOutputWinLine'] as $index => $value){
                    if($value['LinePrize'] > 0){
                        $value['LinePrize'] = ($value['LinePrize'] / $originalbet * $betline) * $betValue[0];
                    }
                    $stack['udsOutputWinLine'][$index] = $value;
                }
            }
            

            $stack['ExtendFeatureByGame2'][3]['Value'] = 0;
            $stack['ExtendFeatureByGame2'][4]['Value'] = 0;
            $stack['ExtendFeatureByGame2'][5]['Value'] = 0;
            $stack['ExtendFeatureByGame2'][7]['Value'] = '0,0,0,0,0,0,0,0,0';
            $stack['ExtendFeatureByGame2'][8]['Value'] = '0,0,0,0,0,0,0,0,0';
            $stack['ExtendFeatureByGame2'][9]['Value'] = '0,0,0,0,0,0,0,0,0';

            $tempStr = '0,0,0,0,0,0,0,1,0';
            $tempSpinArr = [0,0,0];
            for($i = 0; $i < 10;$i++){
                $tempSymbol = $stack['SymbolResult'][$i];
                $symbolsRow = explode(",",$tempSymbol);
                for($j=0;$j<count($symbolsRow);$j++){
                    if($symbolsRow[$j] == 'B') {
                        if($purValue == 1){ //1 1 0
                            $purValue = 3;
                            $stack['ExtendFeatureByGame2'][3]['Value'] = 1;
                            $stack['ExtendFeatureByGame2'][4]['Value'] = 1;
    
                            $stack['ExtendFeatureByGame2'][7]['Value'] = $tempStr;
                            $stack['ExtendFeatureByGame2'][8]['Value'] = $tempStr;
    
                            $tempSpinArr = [1,1,0];
                        }else if($purValue == 2){ //1 0 1
                            $purValue = 4;
                            $stack['ExtendFeatureByGame2'][3]['Value'] = 1;
                            $stack['ExtendFeatureByGame2'][5]['Value'] = 1;
    
                            $stack['ExtendFeatureByGame2'][7]['Value'] = $tempStr;
                            $stack['ExtendFeatureByGame2'][9]['Value'] = $tempStr;
    
                            $tempSpinArr = [1,0,1];
                        }else if($purValue == 5){ //1 1 1
                            $purValue = 6;
                            $stack['ExtendFeatureByGame2'][3]['Value'] = 1;
                            $stack['ExtendFeatureByGame2'][4]['Value'] = 1;
                            $stack['ExtendFeatureByGame2'][5]['Value'] = 1;
                            $stack['ExtendFeatureByGame2'][7]['Value'] = $tempStr;
                            $stack['ExtendFeatureByGame2'][8]['Value'] = $tempStr;
                            $stack['ExtendFeatureByGame2'][9]['Value'] = $tempStr;
    
                            $tempSpinArr = [1,1,1];
                        }else{
                            $purValue = 0;
                            $stack['ExtendFeatureByGame2'][3]['Value'] = 1;
                            $stack['ExtendFeatureByGame2'][7]['Value'] = $tempStr;
                            $tempSpinArr = [1,0,0];
                        }                        
                    }else if($symbolsRow[$j] == 'B1'){
                        if($purValue == 0){ //1 1 0
                            $purValue = 3;
                            $stack['ExtendFeatureByGame2'][3]['Value'] = 1;
                            $stack['ExtendFeatureByGame2'][4]['Value'] = 1;
    
                            $stack['ExtendFeatureByGame2'][7]['Value'] = $tempStr;
                            $stack['ExtendFeatureByGame2'][8]['Value'] = $tempStr;
    
                            $$tempSpinArr = [1,1,0];
                        }else if($purValue == 2){ //0 1 1
                            $purValue = 5;
                            $stack['ExtendFeatureByGame2'][5]['Value'] = 1;
                            $stack['ExtendFeatureByGame2'][4]['Value'] = 1;
    
                            $stack['ExtendFeatureByGame2'][8]['Value'] = $tempStr;
                            $stack['ExtendFeatureByGame2'][9]['Value'] = $tempStr;
    
                            $tempSpinArr = [0,1,1];
                        }else if($purValue == 4){  //1 1 1
                            $purValue = 6;
                            $stack['ExtendFeatureByGame2'][3]['Value'] = 1;
                            $stack['ExtendFeatureByGame2'][4]['Value'] = 1;
                            $stack['ExtendFeatureByGame2'][5]['Value'] = 1;
                            $stack['ExtendFeatureByGame2'][7]['Value'] = $tempStr;
                            $stack['ExtendFeatureByGame2'][8]['Value'] = $tempStr;
                            $stack['ExtendFeatureByGame2'][9]['Value'] = $tempStr;
    
                            $tempSpinArr = [1,1,1];
                        }else{
                            $purValue = 1;
                            $stack['ExtendFeatureByGame2'][4]['Value'] = 1;
                            $stack['ExtendFeatureByGame2'][8]['Value'] = $tempStr;
                            $tempSpinArr = [0,1,0];
                        }
                    }else if($symbolsRow[$j] == 'B2'){
                        if($purValue == 0){ //1 0 1
                            $purValue = 4;
                            $stack['ExtendFeatureByGame2'][3]['Value'] = 1;
                            $stack['ExtendFeatureByGame2'][5]['Value'] = 1;
    
                            $stack['ExtendFeatureByGame2'][7]['Value'] = $tempStr;
                            $stack['ExtendFeatureByGame2'][9]['Value'] = $tempStr;
    
                            $tempSpinArr = [1,0,1];
                        }else if($purValue == 1){ //0 1 1
                            $purValue = 5;
                            $stack['ExtendFeatureByGame2'][5]['Value'] = 1;
                            $stack['ExtendFeatureByGame2'][4]['Value'] = 1;
    
                            $stack['ExtendFeatureByGame2'][8]['Value'] = $tempStr;
                            $stack['ExtendFeatureByGame2'][9]['Value'] = $tempStr;
                            $tempSpinArr = [0,1,1];
                        }else if($purValue == 3){  //1 1 1
                            $purValue = 6;
                            $stack['ExtendFeatureByGame2'][3]['Value'] = 1;
                            $stack['ExtendFeatureByGame2'][4]['Value'] = 1;
                            $stack['ExtendFeatureByGame2'][5]['Value'] = 1;
                            $stack['ExtendFeatureByGame2'][7]['Value'] = $tempStr;
                            $stack['ExtendFeatureByGame2'][8]['Value'] = $tempStr;
                            $stack['ExtendFeatureByGame2'][9]['Value'] = $tempStr;
    
                            $tempSpinArr = [1,1,1];
                        }else{
                            $purValue = 2;
                            $stack['ExtendFeatureByGame2'][5]['Value'] = 1;
                            $stack['ExtendFeatureByGame2'][9]['Value'] = $tempStr;
                            $tempSpinArr = [0,0,1];
                        }
                    }
                }
                
            }
            

            //134요청 받은 후의 collection info 추가 해야 함
            $realWinArr = $slotSettings->GetGameData($slotSettings->slotId . 'winSpinArr');
            for($i=0;$i<3;$i++){
                if($tempSpinArr[$i]==1){
                    $realWinArr[$i] = 1;
                }
            }
            

            if($realWinArr[0] == 1){
                if($realWinArr[1] == 1 && $realWinArr[2] == 1){
                    $purValue = -1;
                    $stack['ExtendFeatureByGame2'][3]['Value'] = 1;
                    $stack['ExtendFeatureByGame2'][4]['Value'] = 1;
                    $stack['ExtendFeatureByGame2'][5]['Value'] = 1;
                    $stack['ExtendFeatureByGame2'][7]['Value'] = $tempStr;
                    $stack['ExtendFeatureByGame2'][8]['Value'] = $tempStr;
                    $stack['ExtendFeatureByGame2'][9]['Value'] = $tempStr;
                    $realWinArr = [0,0,0];
                }else if($realWinArr[1] == 1){
                    $purValue = 3;
                    $stack['ExtendFeatureByGame2'][3]['Value'] = 1;
                    $stack['ExtendFeatureByGame2'][4]['Value'] = 1;

                    $stack['ExtendFeatureByGame2'][7]['Value'] = $tempStr;
                    $stack['ExtendFeatureByGame2'][8]['Value'] = $tempStr;
                }else if($realWinArr[2] == 1){
                    $purValue = 4;
                    $stack['ExtendFeatureByGame2'][3]['Value'] = 1;
                    $stack['ExtendFeatureByGame2'][5]['Value'] = 1;

                    $stack['ExtendFeatureByGame2'][7]['Value'] = $tempStr;
                    $stack['ExtendFeatureByGame2'][9]['Value'] = $tempStr;
                }else{
                    $purValue = 0;
                    $stack['ExtendFeatureByGame2'][3]['Value'] = 1;
                    $stack['ExtendFeatureByGame2'][7]['Value'] = $tempStr;
                }
            }else if($realWinArr[1] == 1){
                if($realWinArr[0] == 1 && $realWinArr[2] == 1){  //1 1 1
                    $purValue = -1;
                    $stack['ExtendFeatureByGame2'][3]['Value'] = 1;
                    $stack['ExtendFeatureByGame2'][4]['Value'] = 1;
                    $stack['ExtendFeatureByGame2'][5]['Value'] = 1;
                    $stack['ExtendFeatureByGame2'][7]['Value'] = $tempStr;
                    $stack['ExtendFeatureByGame2'][8]['Value'] = $tempStr;
                    $stack['ExtendFeatureByGame2'][9]['Value'] = $tempStr;

                    $realWinArr = [0,0,0];
                }else if($realWinArr[2] == 1){ //0 1 1
                    $purValue = 5;
                    $stack['ExtendFeatureByGame2'][5]['Value'] = 1;
                    $stack['ExtendFeatureByGame2'][4]['Value'] = 1;

                    $stack['ExtendFeatureByGame2'][8]['Value'] = $tempStr;
                    $stack['ExtendFeatureByGame2'][9]['Value'] = $tempStr;
                }else if($realWinArr[0] == 1){ //1 1 0
                    $purValue = 3;
                    $stack['ExtendFeatureByGame2'][3]['Value'] = 1;
                    $stack['ExtendFeatureByGame2'][4]['Value'] = 1;

                    $stack['ExtendFeatureByGame2'][7]['Value'] = $tempStr;
                    $stack['ExtendFeatureByGame2'][8]['Value'] = $tempStr;
                }else{
                    $purValue = 1;
                    $stack['ExtendFeatureByGame2'][4]['Value'] = 1;
                    $stack['ExtendFeatureByGame2'][8]['Value'] = $tempStr;
                }
            }else if($realWinArr[2] == 1){
                if($realWinArr[0] == 1 && $realWinArr[1] == 1){  //1 1 1
                    $purValue = -1;
                    $stack['ExtendFeatureByGame2'][3]['Value'] = 1;
                    $stack['ExtendFeatureByGame2'][4]['Value'] = 1;
                    $stack['ExtendFeatureByGame2'][5]['Value'] = 1;
                    $stack['ExtendFeatureByGame2'][7]['Value'] = $tempStr;
                    $stack['ExtendFeatureByGame2'][8]['Value'] = $tempStr;
                    $stack['ExtendFeatureByGame2'][9]['Value'] = $tempStr;

                    $realWinArr = [0,0,0];
                }else if($realWinArr[0] == 1){ //1 0 1
                    $purValue = 4;
                    $stack['ExtendFeatureByGame2'][3]['Value'] = 1;
                    $stack['ExtendFeatureByGame2'][5]['Value'] = 1;

                    $stack['ExtendFeatureByGame2'][7]['Value'] = $tempStr;
                    $stack['ExtendFeatureByGame2'][9]['Value'] = $tempStr;
                }else if($realWinArr[1] == 1){ //0 1 1
                    $purValue = 5;
                    $stack['ExtendFeatureByGame2'][5]['Value'] = 1;
                    $stack['ExtendFeatureByGame2'][4]['Value'] = 1;

                    $stack['ExtendFeatureByGame2'][8]['Value'] = $tempStr;
                    $stack['ExtendFeatureByGame2'][9]['Value'] = $tempStr;
                }else{
                    $purValue = 2;
                    $stack['ExtendFeatureByGame2'][5]['Value'] = 1;
                    $stack['ExtendFeatureByGame2'][9]['Value'] = $tempStr;
                }
            }else{
                $purValue = -1;
            }

            $slotSettings->SetGameData($slotSettings->slotId . 'SymbolIndex', $purValue);
            $slotSettings->SetGameData($slotSettings->slotId . 'winSpinArr', $realWinArr);

            $strArray = [];
            $strArray = explode(",",$stack['ExtendFeatureByGame2'][1]['Value']);
            $valueArray = [];
            $tempValueArr = [];
            for($i=0;$i<count($strArray);$i++){
                $valueArray = $strArray[$i];
                $valueArray = (intval($valueArray) / $originalbet) * $betValue[0]; //(5 = originalbet)
                array_push($tempValueArr,$valueArray);
            }
            
            $symbolTxt = $tempValueArr[0] . ",";
            for($i=1;$i<9;$i++){
                $symbolTxt = $symbolTxt . $tempValueArr[$i].",";
            }
            $symbolTxt = $symbolTxt . $tempValueArr[9];
            $stack['ExtendFeatureByGame2'][1]['Value'] = ($symbolTxt);

            if($slotEvent != 'bonus' && isset($stack['IsTriggerFG'])){
                $isTriggerFG = $stack['IsTriggerFG'];
            }
            $freespinNum = 0;
            if(isset($stack['FreeSpin']) && count($stack['FreeSpin']) > 0){
                $freespinNum = $stack['FreeSpin'][0];
            }else if(isset($stack['NextModule']) && $stack['NextModule'] == 20){
                $freespinNum = 1;
            }

            $newRespin = false;
            if(isset($stack['IsTriggerFG']) && $stack['IsTriggerFG'] == true){
                $newRespin = true;
                $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 1);
            }else{
                $newRespin = false;
                $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
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
                if($slotEvent != 'bonus'){                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespinNum);
                }else{
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $freespinNum);
                }
                $isState = false;
            }
            if($slotEvent == 'bonus'){                
                $isState = false;
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes && $newRespin == false){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $isState = true;
                }
            }else if($slotEvent == 'respin' && $slotSettings->GetGameData($slotSettings->slotId . 'FreeAction') > 0){
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeAction',0);
                $slotEvent = 'bonus';
                $isState = true;
            }else if($newRespin == true){
                $isState = false;
            }
            

            $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
            if($isState == true){
                $allBet = ($betline * $this->demon) * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet') * $betValue[0];
                /*if($slotEvent == 'freespin' && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                    $allBet = $allBet * 10;
                }*/
                $slotSettings->SaveLogReport(json_encode($gamelog), $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
                
            }

            if($slotEvent != 'freespin' && $freespinNum > 0){
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
            }
            return $result_val;
        }
        public function parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines){
            $currentTime = $this->getCurrentTime();
            $allBet = $betline * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
            $betValue = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
            /*if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                $allBet = $allBet * 10;
            }*/
            $proof = [];
            $proof['win_line_data']             = [];
            $proof['symbol_data']               = $result_val['SymbolResult'];
            //$proof['symbol_data_after']         = [];
            $proof['extra_data']                = $result_val['ExtraData'];
           /*$proof['reel_pos_chg']              = $result_val['ReellPosChg'];
            $proof['reel_len_change']           = $result_val['ReelLenChange'];

            if(isset($result_val['ReelPay'])){
                $proof['reel_pay']                  = $result_val['ReelPay'];
            }
            
            $proof['respin_reels']              = $result_val['RespinReels'];*/
            $proof['bonus_type']                = $result_val['BonusType'];
            $proof['special_award']             = $result_val['SpecialAward'];
            if(isset($result_val['SpecialSymbol'])){
                $proof['special_symbol']            = $result_val['SpecialSymbol'];
            }
            $proof['special_symbol'] = 0;
            $proof['denom_multiple'] =         10000;
            $proof['is_respin']                 = $result_val['IsRespin'];
            if(isset($result_val['FreeSpin'])){
                $proof['fg_times']                  = $result_val['FreeSpin'];
            }
            $proof['player_bet_multiples']      = [$slotSettings->GetGameData($slotSettings->slotId . 'MiniBet')];
            $proof['fg_rounds']                 = floor($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame'));
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
            $strArray = [];
            $strArray = $proof['extend_feature_by_game2'][1]['value'];
            $valueArray = [];
            $tempValueArr = [];
            $strArray = explode (",", $strArray);
            for($i=0;$i<count($strArray);$i++){
                $valueArray = $strArray[$i];
                array_push($tempValueArr,$valueArray);
            }
            $symbolTxt = $tempValueArr[0] . ",";
            for($i=1;$i<9;$i++){
                $symbolTxt = $symbolTxt . $tempValueArr[$i].",";
            }
            $symbolTxt = $symbolTxt . $tempValueArr[9];
            $proof['extend_feature_by_game2'][1]['value'] = ($symbolTxt);
            $proof['l_v']                       = "1.0.13";
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
            if($slotEvent == 'bonus' || $slotEvent == 'respin'){
                $log = $slotSettings->GetGameData($slotSettings->slotId . 'GameLog');
                $log['actionlist'][1]['amount']     = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                $log['actionlist'][1]['eventtime']  = $currentTime;
                
                $log['detail']['wager']['order_time']   = $currentTime;
                $log['detail']['wager']['end_time']     = $currentTime;
                $log['detail']['wager']['total_win']    = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');

                if(isset($result_val['LockPos'])){
                    $proof['lock_position']         = $result_val['LockPos'];
                }
                

                if($slotEvent == 'respin'){
                    $sub_log = [];
                    if(isset($result_val['CurrentSpinTimes'])){
                        $sub_log['sub_no']              = $result_val['CurrentSpinTimes'];
                    }
                    
                    $sub_log['game_type']           = 30;
                    if(isset($result_val['RngData'])){
                        $sub_log['rng']                 = $result_val['RngData'];
                    }
                    if(isset($result_val['Multiple'])){
                        $sub_log['multiple']                 = $result_val['Multiple'];
                    }
                    if(isset($result_val['TotalWin'])){
                        $sub_log['win']                 = $result_val['TotalWin'] / $this->demon;
                    }
                    if(isset($result_val['WinLineCount'])){
                        $sub_log['win_line_count']                 = $result_val['WinLineCount'];
                    }
                    if(isset($result_val['WinType'])){
                        $sub_log['win_type']                 = $result_val['WinType'];
                    }
                    $sub_log['proof']               = $proof;
                    array_push($log['detail']['wager']['sub'], $sub_log);
                    $pick_log = [];
                }else{
                    $sub_log = [];
                    $pick_log = [];
                    if(isset($result_val['CurrentSpinTimes'])){
                        $sub_log['sub_no']              = $result_val['CurrentSpinTimes'];
                    }else{
                        $sub_log['sub_no']              = 1;
                    }
                    $sub_log['multiple']           = "0";
                    $sub_log['game_type']           = 50;
                    
                    if(isset($result_val['TotalWin'])){
                        $sub_log['win']                 = $allBet;
                    }
                    
                    $sub_log['proof']               = $proof;
                    array_push($log['detail']['wager']['sub'], $sub_log);
                }
            }else{
                $log = [];
                $log['account']                 = $slotSettings->playerId;
                $log['parentacc']               = 'major_prod';
                $log['actionlist']              = [];
                $log['detail']                  = [];
                $bet_action = [];
                $bet_action['action']           = 'bet';
                $bet_action['amount']           = $allBet * $this->demon * $betValue[0];
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
                $wager['game_id']               = "196";
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = $allBet * $this->demon * $betValue[0];
                $wager['play_denom']            = "100000";
                //$wager['bet_multiple']          = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                $wager['bet_multiple']          = '[1]';
                $wager['rng']                   = $result_val['RngData'];
                $wager['multiple']              = "0";
                $wager['base_game_win']         = $result_val['TotalWin'] * $this->demon;
                $wager['win_over_limit_lock']   = 0;
                if($result_val['IsRespin'] == true){
                    $wager['game_type']             = 1;
                }else{
                    $wager['game_type']             = 0;   
                }
                
                $wager['win_type']              = $result_val['WinType'];
                $wager['settle_type']           = 0;
                $wager['wager_type']            = 0;
                $wager['total_win']             = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
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

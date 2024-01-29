<?php 
namespace VanguardLTE\Games\MeowCQ9
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
            $originalbet = 1;
            $slotSettings->SetBet();  
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 13}],"msg": null}');
                    $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetBalance().'],"evt": 1}');                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
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
                            $result_val['MaxBet'] = 1200;
                            $result_val['MaxLine'] = 1;
                            $result_val['WinLimitLock'] = 300000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
                            $result_val['ExtendFeatureByGame'] = [["name"=>"Postion_1","value"=>0],["name"=>"Postion_2","value"=>0],["name"=>"Postion_3","value"=>0],["name"=>"Postion_4","value"=>0],["name"=>"Postion_5","value"=>0],["name"=>"Postion_6","value"=>0],["name"=>"Postion_7","value"=>0],["name"=>"Postion_8","value"=>0],["name"=>"Postion_9","value"=>0],["name"=>"Postion_10","value"=>0],["name"=>"Postion_11","value"=>0],["name"=>"Postion_12","value"=>0],["name"=>"Postion_13","value"=>0],["name"=>"Postion_14","value"=>0],["name"=>"Postion_15","value"=>0]];
                            $result_val['ExtendFeatureByGame2'] = null;
                            $result_val['IsReelPayType'] = false;
                            $result_val['Cobrand'] = null;
                            $result_val['PlayerOrderURL'] = config('app.cq9history') . '/platform/?gametoken=' . auth()->user()->api_token;
                            $result_val['PromotionData'] = null;
                            $result_val['IsShowFreehand'] = false;
                            $result_val['IsAllowFreehand'] = false;
                            $result_val['FeedbackURL'] = '/feedback/?token=' . auth()->user()->api_token;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = [
                                "g" => "132",
                                "s" => "5.27.1.0",
                                "l" => "2.5.2.5",
                                "si" => "60"
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
                        }else if($packet_id == 31 || $packet_id == 42){
                            $lines = 50;
                            if($packet_id == 31){
                                 $betline = $gameData->PlayBet;// * $gameData->MiniBet;
                            }else if($packet_id == 42){
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            }
                            if($packet_id == 42 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                                $slotEvent['slotEvent'] = 'freespin';
                            }else{
                                $slotEvent['slotEvent'] = 'bet';
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks  릴배치표 저장
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                                $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', $betline);
                                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lines);
                                $slotSettings->SetBet();    
                                $slotSettings->SetBalance(-1 * ($betline * $lines), $slotEvent['slotEvent']);
                                $_sum = ($betline * $lines) / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '664' . substr($roundstr, 3, 9);
                                $slotSettings->SetGameData($slotSettings->slotId . 'GamePlaySerialNumber', $roundstr);
                            }

                            $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet);
                            $result_val['EmulatorType'] = $emulatorType;

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
                                $result_val['Multiple'] = $stack['Multiple'];
                                $result_val['GameExtraData'] = "";
                                $result_val['ExtendFeatureByGame'] = $stack['ExtendFeatureByGame'];
                            }else{
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
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
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                        // FreeSpin Balance add
                        $slotEvent['slotEvent'] = 'freespin';
                        $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                        $lines = 50;
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
            // $winType = 'bonus';
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);

            if($slotEvent == 'freespin'){
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
            $awardSpinTimes = 0;
            $currentSpinTimes = 0;
            if($slotEvent == 'freespin'){
                
                if(isset($stack['CurrentSpinTimes'])){
                    $awardSpinTimes = $stack['AwardSpinTimes']; 
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

            if(isset($stack['ExtendFeatureByGame'])){
                $result_val['ExtendFeatureByGame'] = $stack['ExtendFeatureByGame'];
            }

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
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes){
                    if(($stack['AwardRound'] == $stack['CurrentRound']) && ($stack['RetriggerAddSpins'] == 0)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                        $isState = true;
                    }
                } 
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
            $proof['fg_times']                  = $result_val['FreeSpin'];
            $proof['fg_rounds']                 = floor($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame'));
            $proof['next_s_table']              = $result_val['NextSTable'];
            $proof['extend_feature_by_game']    = [];
            foreach($result_val['ExtendFeatureByGame'] as $item){
                $newItem = [];
                $newItem['name'] = $item['Name'];
                if(isset($item['Value'])){
                    $newItem['value'] = $item['Value'];
                }else{
                    $newItem['value'] = null;
                }
                $proof['extend_feature_by_game'][] = $newItem;
            }
            $proof['extend_feature_by_game2']   = [];

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
            if($slotEvent == 'freespin'){
                $log = $slotSettings->GetGameData($slotSettings->slotId . 'GameLog');
                $log['actionlist'][1]['amount']     = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                $log['actionlist'][1]['eventtime']  = $currentTime;
                
                $log['detail']['wager']['order_time']   = $currentTime;
                $log['detail']['wager']['end_time']     = $currentTime;
                $log['detail']['wager']['total_win']    = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');

                $proof['lock_position']         = $result_val['LockPos'];

                $sub_log = [];
                $sub_log['sub_no']              = $result_val['CurrentSpinTimes'];
                $sub_log['game_type']           = 50;
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
                $wager['game_id']               = 132;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = $betline * $lines;
                $wager['play_denom']            = 100;
                $wager['bet_multiple']          = $betline;
                $wager['rng']                   = $result_val['RngData'];
                $wager['multiple']              = $result_val['Multiple'];
                $wager['base_game_win']         = $result_val['TotalWin'];
                $wager['win_over_limit_lock']   = 0;
                $wager['game_type']             = 0;
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

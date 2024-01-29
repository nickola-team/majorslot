<?php 
namespace VanguardLTE\Games\WolfDiscoCQ9
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
            $originalbet = 2;
            $slotSettings->SetBet();  
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 4}],"msg": null}');
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
                            $result_val['MaxBet'] = 2500;
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
                            $result_val['FeedbackURL'] = '/feedback/?token=' . auth()->user()->api_token;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = [
                                "g" => "183",
                                "s" => "5.27.1.0",
                                "l" => "2.5.1.5",
                                "si" => "39"
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
                            $lines = 30;
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
                                $roundstr = '541' . substr($roundstr, 3, 9);
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
                                // $result_val['Multiple'] = 0; 
                                $result_val['Multiple'] = $stack['Multiple'];                                
                                $result_val['GameExtraData'] = "";
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
                    $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                        $lines = 30;
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

            $result_val['Multiple'] = $stack['Multiple'];
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
            $proof['s_v']                       = "5.27.1.0";
            $proof['bonus_type']                = $result_val['BonusType'];
            $proof['special_award']             = $result_val['SpecialAward'];
            $proof['special_symbol']            = $result_val['SpecialSymbol'];
            $proof['l_v']                       = "2.5.1.5";
            $proof['denom_multiple']            = 100;
            $proof['is_respin']                 = $result_val['IsRespin'];
            $proof['fg_times']                  = $result_val['FreeSpin'][0];
            $proof['fg_rounds']                 = floor($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame'));
            $proof['next_s_table']              = $result_val['NextSTable'];
            $proof['extend_feature_by_game']    = [];
            $proof['extend_feature_by_game2']   = [];
            if(isset($result_val['LockPos'])){
                $proof['lock_position']         = $result_val['LockPos'];
            }else{
                $proof['lock_position']         = [];
            }            
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
                $wager['user_id']               = strval($slotSettings->playerId);
                $wager['game_id']               = "183";
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = strval($betline * $lines);
                $wager['play_denom']            = "100";
                $wager['bet_multiple']          = strval($betline);
                $wager['rng']                   = $result_val['RngData'];
                $wager['multiple']              = $result_val['Multiple'];
                $wager['base_game_win']         = strval($result_val['TotalWin']);
                $wager['win_over_limit_lock']   = 0;
                $wager['game_type']             = 0;
                $wager['win_type']              = $result_val['WinType'];
                $wager['settle_type']           = 0;
                $wager['wager_type']            = 0;
                $wager['total_win']             = strval($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'));
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

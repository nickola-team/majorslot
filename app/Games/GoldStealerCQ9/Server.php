<?php 
namespace VanguardLTE\Games\GoldStealerCQ9
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
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 10}],"msg": null}');
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
                            $result_val['MaxLine'] = 40;
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
                            $result_val['Currency'] ="KRW";
                            $result_val['Tag'] = [
                                "g" => "130",
                                "s" => "5.27.1.0",
                                "l" => "2.4.34.3",
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
                            $lines = 40;
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
                                $roundstr = '658' . substr($roundstr, 3, 9);
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
                                $result_val['Multiple'] = 0;
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
            $proof['bonus_type']                = $result_val['BonusType'];
            $proof['special_award']             = $result_val['SpecialAward'];
            $proof['special_symbol']            = $result_val['SpecialSymbol'];
            $proof['is_respin']                 = $result_val['IsRespin'];
            $proof['fg_times']                  = $result_val['FreeSpin'];
            $proof['fg_rounds']                 = floor($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') / 3);
            $proof['next_s_table']              = $result_val['NextSTable'];
            $proof['extend_feature_by_game']    = [];
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
                $wager['game_id']               = 130;
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

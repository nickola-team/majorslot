<?php 
namespace VanguardLTE\Games\MoveNJumpCQ9
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
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 15}],"msg": null}');
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
                                "g" => "138",
                                "s" => "5.27.1.0",
                                "l" => "2.5.2.9",
                                "si" => "36"
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
                                $roundstr = '641' . substr($roundstr, 3, 9);
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

            //$result_val['Multiple'] = "1";
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
            $proof['fg_rounds']                 = floor($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame'));
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
                $wager['game_id']               = 7;
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

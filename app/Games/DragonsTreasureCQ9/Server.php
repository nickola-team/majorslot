<?php 
namespace VanguardLTE\Games\DragonsTreasureCQ9
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
            $originalbet = 8;
            $slotSettings->SetBet();  
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 0}],"msg": null}');
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
                            $result_val['MaxBet'] = 12500;
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
                            $result_val['FreeTicketList'] = null;
                            $result_val['DenomMultiple'] = $initDenom;
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
                        }else if($packet_id == 31 || $packet_id == 42){
                            $lines = 10;
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
                                if(isset($slotEvent['slotEvent'])){
                                    $slotSettings->SetBalance(-1 * ($betline * $lines), $slotEvent['slotEvent']);
                                }
                                
                                $_sum = ($betline * $lines) / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '679' . substr($roundstr, 3, 9);
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
                        $lines = 10;
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
             //$winType = 'win';
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

            if(isset($stack['IsTriggerFG']) && $stack['IsTriggerFG'] == true){
                $slotSettings->SetGameData($slotSettings->slotId . 'TriggerFree', 1);
            }

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
                $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
            }

            $result_val['Multiple'] = 0;
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $isState = true;
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
                $wager['game_id']               = 197;
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

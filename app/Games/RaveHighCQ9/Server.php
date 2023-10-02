<?php 
namespace VanguardLTE\Games\RaveHighCQ9
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
                                "g" => "203",
                                "s" => "5.27.1.0",
                                "l" => "2.5.1.1",
                                "si" => "34"
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
                }else if($paramData['req'] == 1000){  // socket closed.
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
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes && ($stack['RetriggerAddRound'] == 0 && $stack['RetriggerAddSpins'] == 0)){
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
                $wager['game_id']               = 203;
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

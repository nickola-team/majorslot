<?php 
namespace VanguardLTE\Games\RaveJump2MCQ9
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
                                "g" => "121",
                                "s" => "5.27.1.0",
                                "l" => "2.5.1.1",
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
                                $roundstr = '656' . substr($roundstr, 3, 9);
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
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                        // FreeSpin Balance add
                        $slotEvent['slotEvent'] = 'freespin';
                        $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                        $lines = 40;
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
                $wager['game_id']               = 121;
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

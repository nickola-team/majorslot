<?php 
namespace VanguardLTE\Games\RaveJumpMCQ9
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
            $originalbet = 3;
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
                            $result_val['FeedbackURL'] = null;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = [
                                "g" => "109",
                                "s" => "5.27.1.0",
                                "l" => "2.5.1.1",
                                "si" => "41"
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
                        $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                        $lines = 30;
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
                //$result_val['Multiple'] = "'". $currentSpinTimes . "'";
                //$result_val['Multiple'] = "3";
                $result_val['Multiple'] = $stack['Multiple'];
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
                $wager['game_id']               = 109;
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

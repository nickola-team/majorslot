<?php 
namespace VanguardLTE\Games\LuckyBoxesCQ9
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
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 1}],"msg": null}');
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
                                "g" => "204",
                                "s" => "5.27.1.0",
                                "l" => "2.5.1.1",
                                "si" => "58"
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
                        $lines = 25;
                        $count = 0;
                        while($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                            $result_val = [];
                            $result_val['Type'] = 3;
                            $result_val['ID'] = 142;
                            $result_val['Version'] = 0;
                            $result_val['ErrorCode'] = 0;
                            $result_val['EmulatorType'] = 0;
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
            // $winType = 'win';
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

            $result_val['Multiple'] = "1";
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
                $result_val['Multiple'] = "'". $currentSpinTimes . "'";
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
                $wager['game_id']               = 204;
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

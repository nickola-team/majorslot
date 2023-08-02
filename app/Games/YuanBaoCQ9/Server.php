<?php 
namespace VanguardLTE\Games\YuanBaoCQ9
{
    class Server
    {
        public $demon = 1;
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
                                array_push($betButtons, $slotSettings->Bet[$k]* $this->demon + 0);
                            }
                            $result_val['DenomDefine'] = $denomDefine;
                            $result_val['BetButton'] = $betButtons;
                            $result_val['DefaultDenomIdx'] = 3;
                            $result_val['MaxBet'] = 6000;
                            $result_val['MaxLine'] = 8;
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
                            $result_val['DenomMultiple'] = $initDenom * $this->demon;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = [
                                "g" => "23",
                                "s" => "5.27.1.0",
                                "l" => "2.4.34.3",
                                "si" => "35"
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
                            $lines = 16;
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
                                $roundstr = '539' . substr($roundstr, 3, 9);
                                $slotSettings->SetGameData($slotSettings->slotId . 'GamePlaySerialNumber', $roundstr);
                            }

                            $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline* $this->demon, $lines, $originalbet);
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
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                        // FreeSpin Balance add
                        $slotEvent['slotEvent'] = 'freespin';
                        $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                        $lines = 55;
                        $count = 0;
                        while($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                            $result_val = [];
                            $result_val['Type'] = 3;
                            $result_val['ID'] = 142;
                            $result_val['Version'] = 0;
                            $result_val['ErrorCode'] = 0;
                            $result_val['EmulatorType'] = 0;
                            $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline* $this->demon, $lines, $originalbet);
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
                $stack['BaseWin'] = ($stack['BaseWin'] / $originalbet * $betline) / $this->demon;
            }
            $totalWin = 0;
            if(isset($stack['TotalWin']) && $stack['TotalWin'] > 0){
                $stack['TotalWin'] = ($stack['TotalWin'] / $originalbet * $betline) / $this->demon;
                $totalWin = $stack['TotalWin'] / $this->demon;
            }
            if(isset($stack['AccumlateWinAmt']) && $stack['AccumlateWinAmt'] > 0){
                $stack['AccumlateWinAmt'] = ($stack['AccumlateWinAmt'] / $originalbet * $betline) / $this->demon;
            }
            if(isset($stack['AccumlateJPAmt']) && $stack['AccumlateJPAmt'] > 0){
                $stack['AccumlateJPAmt'] = ($stack['AccumlateJPAmt'] / $originalbet * $betline) / $this->demon;
            }
            if(isset($stack['ScatterPayFromBaseGame']) && $stack['ScatterPayFromBaseGame'] > 0){
                $stack['ScatterPayFromBaseGame'] = ($stack['ScatterPayFromBaseGame'] / $originalbet * $betline) / $this->demon;
            }
            $awardSpinTimes = 0;
            $currentSpinTimes = 0;
            if($slotEvent == 'freespin'){
                $awardSpinTimes = $stack['AwardSpinTimes'];    
                $currentSpinTimes = $stack['CurrentSpinTimes'];   
            }
            foreach($stack['udsOutputWinLine'] as $index => $value){
                if($value['LinePrize'] > 0){
                    $value['LinePrize'] = ($value['LinePrize'] / $originalbet * $betline) / $this->demon;
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
                $slotSettings->SaveLogReport(json_encode($gamelog), ($betline / $this->demon) * $lines, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
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
                $bet_action['amount']           = ($betline / $this->demon) * $lines;
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
                $wager['game_id']               = 23;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = ($betline / $this->demon) * $lines;
                $wager['play_denom']            = 100;
                $wager['bet_multiple']          = ($betline / $this->demon);
                $wager['rng']                   = $result_val['RngData'];
                $wager['multiple']              = $result_val['Multiple'];
                $wager['base_game_win']         = $result_val['TotalWin'] / $this->demon;
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

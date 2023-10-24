<?php 
namespace VanguardLTE\Games\BurningXiYouCQ9
{
    class Server
    {
        public $demon = 10;
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
                                array_push($denomDefine, $initDenom * $this->demon);
                                array_push($betButtons, $slotSettings->Bet[$k] + 0);
                            }
                            $result_val['DenomDefine'] = $denomDefine;
                            $result_val['BetButton'] = $betButtons;
                            $result_val['DefaultDenomIdx'] = 3;
                            $result_val['MaxBet'] = 1000;
                            $result_val['MaxLine'] = 9;
                            $result_val['WinLimitLock'] = 30000000;
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
                            $result_val['Currency'] ="KRW";
                            $result_val['Tag'] = [
                                "g" => "212",
                                "s" => "5.27.1.0",
                                "l" => "2.5.4.23",
                                "si" => "52"
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
                            $lines = 9;
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
                                if(isset($gameData->PlayBet)){
                                    $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                    $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                }
                                
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', $betline);
                                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lines);
                                $slotSettings->SetBet();    
                                $slotSettings->SetBalance(-1 * (($betline * $this->demon) * $lines), $slotEvent['slotEvent']);
                                $_sum = (($betline * $this->demon) * $lines) / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '571' . substr($roundstr, 3, 9);
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
                        $lines = 9;
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
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, ($betline * $this->demon) * $lines, $lines);
            $winType = $_spinSettings[0];
            $_winAvaliableMoney = $_spinSettings[1];
            // $winType = 'bonus';
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);

            if($slotEvent == 'freespin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
                
            }else{
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, ($betline * $this->demon) * $lines);
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
                $totalWin = $stack['TotalWin'] * $this->demon;
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
            if(isset($stack['IsRespin']) && $stack['IsRespin'] == true){
                $freespinNum = 1;
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
                $slotSettings->SaveLogReport(json_encode($gamelog), ($betline * $this->demon) * $lines, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
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
            
            $proof['denom_multiple']            = 10000;
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
            /*foreach($result_val['ExtendFeatureByGame2'] as $item){
                $newItem = [];
                $newItem['name'] = $item['Name'];
                if(isset($item['Value'])){
                    $newItem['value'] = $item['Value'];
                }else{
                    $newItem['value'] = null;
                }
                $proof['extend_feature_by_game2'][] = $newItem;
            }*/
            $proof['g_v']                       = "5.27.1.0";
            $proof['l_v']                       = "2.5.2.76";
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
                $sub_log['win']                 = $result_val['TotalWin'] * $this->demon;
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
                $bet_action['amount']           = ($betline * $this->demon) * $lines;
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
                $wager['game_id']               = 212;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = ($betline *  $this->demon) * $lines;
                $wager['play_denom']            = 100000;
                $wager['bet_multiple']          = $betline;
                $wager['rng']                   = $result_val['RngData'];
                $wager['multiple']              = $result_val['Multiple'];
                $wager['base_game_win']         = $result_val['TotalWin'] * $this->demon;
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

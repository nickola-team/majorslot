<?php 
namespace VanguardLTE\Games\DaFaCaiCQ9
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
                            $result_val['FeedbackURL'] = null;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = [
                                "g" => "5007",
                                "s" => "5.27.1.0",
                                "l" => "2.4.34.1",
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
                            $lines = 50;
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
                                $result_val['Multiple'] = 1;
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
                        $lines = 50;
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
                $result_val['Multiple'] = strval($currentSpinTimes);
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes && $stack['AwardRound'] == $stack['CurrentRound']){
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
                $wager['game_id']               = 5007;
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

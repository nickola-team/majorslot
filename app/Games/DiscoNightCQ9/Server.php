<?php 
namespace VanguardLTE\Games\DiscoNightCQ9
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
                                array_push($denomDefine, $initDenom);
                                array_push($betButtons, $slotSettings->Bet[$k] + 0);
                            }
                            $result_val['DenomDefine'] = $denomDefine;
                            $result_val['BetButton'] = $betButtons;
                            $result_val['DefaultDenomIdx'] = 3;
                            $result_val['MaxBet'] = 1200;
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
                            $result_val['FreeTicketList'] = null;
                            $result_val['Currency'] = "KRW";
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['FreeSpinLeftTimesInfoList'] = null;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = [
                                "g" => "205",
                                "s" => "5.27.1.0",
                                "l" => "2.5.1.1",
                                "si" => "32"
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
                                $result_val['AccumlateWinAmt'] = $stack['AccumlateWinAmt'] / $originalbet * $betline;
                                $result_val['AccumlateJPAmt'] = 0;
                                $result_val['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
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
                            $result_val['TotalWinAmt'] = $stack['TotalWinAmt'] / $originalbet * $betline;
                            $result_val['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
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
                        $lines = 50;
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
                $stack['BaseWin'] = $stack['BaseWin'] / $originalbet * $betline;
            }
            $totalWin = 0;
            if(isset($stack['TotalWin']) && $stack['TotalWin'] > 0){
                $stack['TotalWin'] = $stack['TotalWin'] / $originalbet * $betline;
                $totalWin = $stack['TotalWin'];
            }
            if(isset($stack['AccumlateWinAmt']) && $stack['AccumlateWinAmt'] > 0){
                $stack['AccumlateWinAmt'] = $stack['AccumlateWinAmt'] / $originalbet * $betline;
            }
            if(isset($stack['AccumlateJPAmt']) && $stack['AccumlateJPAmt'] > 0){
                $stack['AccumlateJPAmt'] = $stack['AccumlateJPAmt'] / $originalbet * $betline;
            }
            if(isset($stack['ScatterPayFromBaseGame']) && $stack['ScatterPayFromBaseGame'] > 0){
                $stack['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
            }

            

            $awardSpinTimes = 0;
            $currentSpinTimes = 0;
            if($slotEvent == 'freespin'){
                $awardSpinTimes = $stack['AwardSpinTimes'];    
                $currentSpinTimes = $stack['CurrentSpinTimes'];    
            }
            foreach($stack['udsOutputWinLine'] as $index => $value){
                if($value['LinePrize'] > 0){
                    $value['LinePrize'] = $value['LinePrize'] / $originalbet * $betline;
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
                if($winType == 'bonus'){
                    $slotSettings->SetBank('bonus', -1 * $totalWin);   
                }else{
                    $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
                }
                //$slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + ($totalWin));
            }

            //$result_val['Multiple'] = 0;
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
            $proof['fg_rounds']                 = floor($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') / 8);
            $proof['next_s_table']              = $result_val['NextSTable'];
            if(isset($proof['extend_feature_by_game'])){
                $proof['extend_feature_by_game']    = [];
            }
            if(isset($proof['extend_feature_by_game2'])){
                $proof['extend_feature_by_game2']   = [];
            }

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
                $wager['game_id']               = 205;
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

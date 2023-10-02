<?php 
namespace VanguardLTE\Games\MagicWorldCQ9
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
                                "g" => "27",
                                "s" => "5.27.1.0",
                                "l" => "2.4.36.2",
                                "si" => "33"
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
                                $roundstr = '663' . substr($roundstr, 3, 9);
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
                $wager['game_id']               = 27;
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

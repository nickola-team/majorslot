<?php 
namespace VanguardLTE\Games\RaveJumpCQ9
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
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 11}],"msg": null}');
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
                            $result_val['MaxBet'] = 5000;
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
                            $result_val['PlayerOrderURL'] = config('app.cq9history') . '/?gametoken=' . auth()->user()->api_token;
                            $result_val['PromotionData'] = null;
                            $result_val['IsShowFreehand'] = false;
                            $result_val['IsAllowFreehand'] = false;
                            $result_val['FeedbackURL'] = null;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = [
                                "g" => "7",
                                "s" => "5.27.1.0",
                                "l" => "2.5.2.9",
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
                            if($packet_id == 31){
                                 $betline = $gameData->PlayBet;// * $gameData->MiniBet;
                                 $lines = $gameData->PlayLine;
                            }else if($packet_id == 42){
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                                $lines = 1;
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
                                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lines * $gameData->MiniBet);
                                $slotSettings->SetBet();    
                                $slotSettings->SetBalance(-1 * ($betline * $lines * $gameData->MiniBet), $slotEvent['slotEvent']);
                                $_sum = ($betline * $lines * $gameData->MiniBet) / 100 * $slotSettings->GetPercent();
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
                }else if($paramData['req'] == 1000){  // socket closed
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                        // FreeSpin Balance add
                        $slotEvent['slotEvent'] = 'freespin';
                        $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                        $lines = 1;
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
                $slotSettings->SaveLogReport(json_encode($gamelog), $betline * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet'), $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
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
                $bet_action['amount']           = $betline * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
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
                $wager['game_id']               = 7;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = $betline * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
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

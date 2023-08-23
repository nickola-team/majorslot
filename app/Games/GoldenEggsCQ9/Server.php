<?php 
namespace VanguardLTE\Games\GoldenEggsCQ9
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
                            $result_val['MaxBet'] = 1000;
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
                                "g" => "67",
                                "s" => "5.27.1.0",
                                "l" => "2.5.1.1",
                                "si" => "52"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = -1;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["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"]];
                            $result_val['FGStripCount'] = 0;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [];
                        }else if($packet_id == 31 || $packet_id == 42){
                            $lines = 80;
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
                        $lines = 80;
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
            if($winType == 'bonus'){
                $winType = 'win'; 
            }
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
                $wager['game_id']               = 67;
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

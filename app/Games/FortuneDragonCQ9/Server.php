<?php 
namespace VanguardLTE\Games\FortuneDragonCQ9
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
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 3}],"msg": null}');
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
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = [
                                "g" => "194",
                                "s" => "5.27.1.0",
                                "l" => "2.5.2.26",
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
                if($winType == 'bonus'){
                    $slotSettings->SetBank('bonus', -1 * $totalWin);   
                }else{
                    $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
                }
                //$slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + ($totalWin));
            }

            //$result_val['Multiple'] = "1";
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
            if(isset($result_val['ExtendFeatureByGame2'])){
                foreach($result_val['ExtendFeatureByGame2'] as $item){
                    $newItem = [];
                    $newItem['name'] = $item['Name'];
                    if(isset($item['Value'])){
                        $newItem['value'] = $item['Value'];
                    }
                    $proof['extend_feature_by_game2'][] = $newItem;
                }
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
                $wager['game_id']               = 194;
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

<?php 
namespace VanguardLTE\Games\FireQueen2CQ9
{
    
    class Server
    {
        public $demon = 0.1;
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
                        $initDenom = 1000;
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
                            $result_val['MaxBet'] = 2000;
                            $result_val['MaxLine'] = 5;
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
                            $result_val['DenomMultiple'] = $initDenom * $this->demon;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            /*$result_val['Tag'] = [
                                "g" => "54",
                                "s" => "5.27.1.0",
                                "l" => "2.5.2.6",
                                "si" => "35"
                            ];*/
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["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"]];
                            $result_val['FGStripCount'] = 10;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["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"],["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"],["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"],["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"],["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"],["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"],["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"],["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"],["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"],["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"]];
                        }else if($packet_id == 31 || $packet_id == 42){
                            $lines = 25;
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
                                $slotSettings->SetBalance(-1 * ($betline * $lines * 2), $slotEvent['slotEvent']);
                                $_sum = ($betline * $lines * 2) / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '568' . substr($roundstr, 3, 9);
                                $slotSettings->SetGameData($slotSettings->slotId . 'GamePlaySerialNumber', $roundstr);
                            }
                            
                            $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline * $this->demon, $lines, $originalbet);
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
                                $result_val['AccumlateWinAmt'] = floor(($stack['AccumlateWinAmt'] / $originalbet * $betline) + 0.05);
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
                                $result_val['IsAllowFreeHand'] = false;
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
                        $lines = 25;
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
                            $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline * $this->demon, $lines, $originalbet);
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
            $totalWin = 0;
            if(isset($stack['GamePlaySerialNumber'])){
                $stack['GamePlaySerialNumber'] = $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber');
            }
          
                
                if(isset($stack['BaseWin']) && $stack['BaseWin'] > 0){
                
                    $stack['BaseWin'] = floor(($stack['BaseWin'] / $originalbet * $betline) / $this->demon + 0.05);
                }
                if(isset($stack['TotalWin']) && $stack['TotalWin'] > 0){
                    
                    $stack['TotalWin'] = floor(($stack['TotalWin'] / $originalbet * $betline) / $this->demon + 0.05);
                    $totalWin = floor($stack['TotalWin'] / $this->demon + 0.05);
                }
            
            
            
            
            if(isset($stack['AccumlateWinAmt']) && $stack['AccumlateWinAmt'] > 0){
                $stack['AccumlateWinAmt'] = floor(($stack['AccumlateWinAmt'] / $originalbet * ($betline)) / $this->demon + 0.05) ;
            }
            if(isset($stack['AccumlateJPAmt']) && $stack['AccumlateJPAmt'] > 0){
                $stack['AccumlateJPAmt'] = floor(($stack['AccumlateJPAmt'] / $originalbet * ($betline)) / $this->demon + 0.05);
            }
            if(isset($stack['ScatterPayFromBaseGame']) && $stack['ScatterPayFromBaseGame'] > 0){
                $stack['ScatterPayFromBaseGame'] = floor(($stack['ScatterPayFromBaseGame'] / $originalbet * ($betline)) / $this->demon + 0.05);
            }
            $awardSpinTimes = 0;
            $currentSpinTimes = 0;
            if($slotEvent == 'freespin'){
                $awardSpinTimes = $stack['AwardSpinTimes'];    
                $currentSpinTimes = $stack['CurrentSpinTimes'];   
            }
            foreach($stack['udsOutputWinLine'] as $index => $value){
                if($value['LinePrize'] > 0){
                    $value['LinePrize'] = floor(($value['LinePrize'] / $originalbet * $betline) / $this->demon + 0.05);
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
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + ($totalWin));
            }

            $result_val['Multiple'] = "0";
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
                $slotSettings->SaveLogReport(json_encode($gamelog), ($betline / $this->demon) * $lines * 2, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
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
                $lineData['line_prize']         = floor($outWinLine['LinePrize'] + 0.05);
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
                $sub_log['win']                 = $result_val['TotalWin'] / $this->demon;
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
                $bet_action['amount']           = floor(($betline / $this->demon) * 2 * $lines  + 0.05);
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
                $wager['game_id']               = 186;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = floor(($betline/ $this->demon) * 2 * $lines + 0.05);
                $wager['play_denom']            = 1000;
                $wager['bet_multiple']          = floor($betline / $this->demon + 0.05);
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

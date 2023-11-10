<?php 
namespace VanguardLTE\Games\SkyLanternsCQ9
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
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 4}],"msg": null}');
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
                            $result_val['MaxLine'] = 50;
                            $result_val['WinLimitLock'] = 300000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
                            $result_val['ExtendFeatureByGame'] = [["name"=>"Postion_1","value"=>0],["name"=>"Postion_2","value"=>0],["name"=>"Postion_3","value"=>0],["name"=>"Postion_4","value"=>0],["name"=>"Postion_5","value"=>0],["name"=>"Postion_6","value"=>0],["name"=>"Postion_7","value"=>0],["name"=>"Postion_8","value"=>0]];
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
                                "g" => "146",
                                "s" => "5.27.1.0",
                                "l" => "2.4.34.3",
                                "si" => "37"
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
                                $result_val['Multiple'] = 0;
                                $result_val['GameExtraData'] = "";
                                $result_val['ExtendFeatureByGame'] = $stack['ExtendFeatureByGame'];
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
            // $winType = 'bonus';
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
            $proof['lock_position']         = [];

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
                $wager['game_id']               = 146;
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

<?php 
namespace VanguardLTE\Games\KingofAtlantisCQ9
{
    class Server
    {
        public $demon = 1;
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
                                array_push($betButtons, $slotSettings->Bet[$k]);
                            }
                            $result_val['DenomDefine'] = $denomDefine;
                            $result_val['BetButton'] = $betButtons;
                            $result_val['DefaultDenomIdx'] = 3;
                            $result_val['MaxBet'] = 600;// $slotSettings->Bet[count($slotSettings->Bet) - 4] * $this->demon;
                            $result_val['MaxLine'] = 1;
                            $result_val['WinLimitLock'] = 300000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
                            $result_val['ExtendFeatureByGame'] = null;
                            $result_val['ExtendFeatureByGame2'] = [["name"=>"FeaturePay","value"=>"3455"]];
                            $result_val['IsReelPayType'] = true;
                            $result_val['Cobrand'] = null;
                            $result_val['PlayerOrderURL'] = config('app.cq9history') . '/platform/?gametoken=' . auth()->user()->api_token;
                            $result_val['PromotionData'] = null; //$slotSettings->getPromotionData();
                            $result_val['IsShowFreehand'] = false;
                            $result_val['IsAllowFreehand'] = false;
                            $result_val['FeedbackURL'] = null;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['DenomMultiple'] = $initDenom * $this->demon;
                            $result_val['Currency'] ="KRW";
                            $result_val['Tag'] = ["g" => "211","s" => "5.27.1.0","l" => "2.5.4.19","si" => "34"];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            $result_val['BGContext'] = [
                                ["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"]
                              ];
                            $result_val['FGStripCount'] = 1;
                            $result_val['FGContext'] = [
                                ["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"]
                            ];
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
                                $pur_level = -1;
                                if($gameData->ReelPay > 0){
                                    $pur_level = 0;
                                }
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                                $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur_level);
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', $betline);
                                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 50);
                                $slotSettings->SetBet();
                                $allBet = ($betline /  $this->demon) * $lines;
                                $isBuyFreespin = false;
                                if($pur_level == 0){
                                    $allBet = $allBet * 69.1;
                                    $isBuyFreespin = true;
                                }
                                $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                                $_sum = $allBet / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent'], $isBuyFreespin);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '662' . substr($roundstr, 3, 7);
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
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, ($betline /  $this->demon) * $lines, $lines);
                $winType = $_spinSettings[0];
                //$winType = 'win';
            if($slotEvent == 'freespin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
            }else{
                
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $winType = 'bonus';
                }
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, ($betline /  $this->demon) * $lines, $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin'));
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
                //$stack['TotalWin'] = floor(($stack['TotalWin'] / $originalbet * $betline) / $this->demon + 0.05);
                $stack['TotalWin'] = ($stack['TotalWin'] / $originalbet * $betline);
                /*if($slotEvent == 'freespin'){
                    
                    
                }else{
                    $stack['TotalWin'] = ($stack['TotalWin'] / $originalbet * $betline);
                }*/
                $totalWin = $stack['TotalWin'] / $this->demon;
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
            if(isset($stack['SpecialAward']) && $stack['SpecialAward'] > 0){
                $stack['SpecialAward'] = $stack['SpecialAward'] / $originalbet * $betline;
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
                        $value['LinePrize'] = $value['LinePrize'] / $originalbet * $betline;
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

            if($freespinNum > 0){
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
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes && $stack['AwardRound'] == $stack['CurrentRound']){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $isState = true;
                }
            }
            

            $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
            if($isState == true){
                $allBet = ($betline /  $this->demon) * $lines;
                if($slotEvent == 'freespin' && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                    $allBet = $allBet * 69.1;
                }
                $slotSettings->SaveLogReport(json_encode($gamelog), $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
            }

            if($slotEvent != 'freespin' && $freespinNum > 0){
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', ($totalWin));
            }
            return $result_val;
        }
        public function parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines){
            $currentTime = $this->getCurrentTime();
            $allBet = ($betline /  $this->demon) * $lines;
            if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                $allBet = $allBet * 69.1;
            }
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
            if(isset($result_val['CurrentRound'])){
                $proof['fg_rounds']                 = $result_val['CurrentRound'];
            }else{
                $proof['fg_rounds']                 = 0;
            }
            $proof['denom_multiple']            = 100;
            $proof['next_s_table']              = $result_val['NextSTable'];
            $proof['extend_feature_by_game']    = [];
            $proof['extend_feature_by_game2']   = [];
            foreach($result_val['ExtendFeatureByGame2'] as $item){
                $newItem = [];
                $newItem['name'] = $item['Name'];
                if(isset($item['Value'])){
                    $newItem['value'] = $item['Value'];
                }
                $proof['extend_feature_by_game2'][] = $newItem;
            }

            foreach( $result_val['udsOutputWinLine'] as $index => $outWinLine) 
            {
                $lineData = [];
                $lineData['line_extra_data']    = $outWinLine['LineExtraData'];
                $lineData['line_multiplier']    = $outWinLine['LineMultiplier'];
                $lineData['line_prize']         = $outWinLine['LinePrize'] /  $this->demon;
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
                $bet_action['amount']           = $allBet;
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
                $wager['game_id']               = '211';
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = $allBet;
                $wager['play_denom']            = 100;
                $wager['bet_multiple']          = $betline;
                $wager['rng']                   = $result_val['RngData'];
                $wager['multiple']              = $result_val['Multiple'];
                $wager['base_game_win']         = $result_val['TotalWin'] /  $this->demon;
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

<?php 
namespace VanguardLTE\Games\Thor2CQ9
{
    class Server
    {
        public $demon = 1;
        public function get($request, $game, $userId) // changed by game developer
        {
            /*if( config('LicenseDK.APL_INCLUDE_KEY_CONFIG') != 'wi9qydosuimsnls5zoe5q298evkhim0ughx1w16qybs2fhlcpn' ) 
            {
                return false;
            }
            if( md5_file(base_path() . '/app/Lib/LicenseDK.php') != '3c5aece202a4218a19ec8c209817a74e' ) 
            {
                return false;
            }
            if( md5_file(base_path() . '/config/LicenseDK.php') != '951a0e23768db0531ff539d246cb99cd' ) 
            {
                return false;
            }
            $checked = new \VanguardLTE\Lib\LicenseDK();
            $license_notifications_array = $checked->aplVerifyLicenseDK(null, 0);
            if( $license_notifications_array['notification_case'] != 'notification_license_ok' ) 
            {
                $response = '{"responseEvent":"error","responseType":"error","serverResponse":"Error LicenseDK"}';
                exit( $response );
            }*/
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
                                array_push($betButtons, $slotSettings->Bet[$k] * $this->demon);
                            }
                            $result_val['DenomDefine'] = $denomDefine;
                            $result_val['BetButton'] = $betButtons;
                            $result_val['DefaultDenomIdx'] = 3;
                            $result_val['MaxBet'] = $slotSettings->Bet[count($slotSettings->Bet) - 4] * $this->demon;
                            $result_val['MaxLine'] = 1;
                            $result_val['WinLimitLock'] = 300000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
                            $result_val['ExtendFeatureByGame'] = null;
                            $result_val['ExtendFeatureByGame2'] = [["name" => "FeatureMinBet","value" => "2344"]];
                            $result_val['GameFlowBranch'] = 0;
                            $result_val['IsReelPayType'] = false;
                            $result_val['Cobrand'] = null;
                            $result_val['PlayerOrderURL'] = config('app.cq9history') . '/?gametoken=' . auth()->user()->api_token;
                            $result_val['PromotionData'] = null; //$slotSettings->getPromotionData();
                            $result_val['IsShowFreehand'] = false;
                            $result_val['IsAllowFreehand'] = false;
                            $result_val['FeedbackURL'] = null;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['FreeTicketList'] = null;
                            $result_val['DenomMultiple'] = $initDenom * $this->demon;
                            $result_val['FreeSpinLeftTimesInfoList'] = null;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = ["g" => "182","s" => "5.27.1.0","l" => "2.5.4.5","si" => "59"];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 2;
                            $result_val['BGContext'] = [
                                [
                                    "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"
                                ],[
                                    "xHvQGrtHH6cdDnHyG9u46+Vt1UzcRqtPEaYp1pQjZFkCdwb7MR4zBPxQenXkHsn/jkYRWsZQK6sAj6mUFJ9L4CpfcfLHF6ZM/3jB4Q==","7Fx3MVt8dkrqHT2kQqRC45PK28Tj5YxVy1CpP3JWDEMXydVNcMNXd2M8oQD0h8/ynPKar+vOitCPNh6XEFrNwSXkWIMoWZHfx+kR2g==","4qxa2trLQHUILJyREmeMe3ZsqOzPlF76yhzAZ0eSO5U1wRJVV7xvartPvylepKOvzNj0L4UcA8jEjA/kzG6EO+KODjgzFljBZ1y6ug==","K2OgaHNnCkHNuYOn1qHbSTiqT9wiTziYx7n67Lhet6Y29YhytXqHHZu1F6/fvrwoxEiRf/qyBexH62y/XI7dUukZ+sK/bN3nZeLzMFOr8HYAsUUrLP62mhHt4j8=","WmRKGTPuugkUmPmmDWKfyNSLdE/U7eWscfhDNueMihxGdGtdfccltdxCbMgJv8D87mkPMGK0nT+vfc8c514qlzPkPG4csIUOV04laQ=="
                                ]
                              ];
                            $result_val['FGStripCount'] = 2;
                            $result_val['FGContext'] = [
                                [
                                    "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"
                                ],[
                                    "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"
                                ]
                            ];
                        }else if($packet_id == 31 || $packet_id == 42){
                            $lines = 30;
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
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', ($betline /  $this->demon));
                                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 50);
                                $slotSettings->SetBet();
                                $allBet = ($betline /  $this->demon) * $lines;
                                $isBuyFreespin = false;
                                if($pur_level == 0){
                                    $allBet = $betline * 2344;
                                    $isBuyFreespin = true;
                                }
                                $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                                $_sum = $allBet / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent'], $isBuyFreespin);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '567' . substr($roundstr, 3, 7);
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
                        $lines = 30;
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
            if($slotEvent == 'freespin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
            }else{
                $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, ($betline /  $this->demon) * $lines, $lines);
                $winType = $_spinSettings[0];
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
            if(isset($stack['SpecialAward']) && $stack['SpecialAward'] > 0){
                $stack['SpecialAward'] = $stack['SpecialAward'] / $originalbet * $betline;
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
                $slotSettings->SetBalance($totalWin / $this->demon);
                $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin / $this->demon);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
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
                    $allBet = $betline * 2344;
                }
                $slotSettings->SaveLogReport(json_encode($gamelog), $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon, $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
            }

            if($slotEvent != 'freespin' && $freespinNum > 0){
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
            }
            return $result_val;
        }
        public function parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines){
            $currentTime = $this->getCurrentTime();
            $allBet = ($betline /  $this->demon) * $lines;
            if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                $allBet = $betline * 2344;
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
                $log['actionlist'][1]['amount']     = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon;
                $log['actionlist'][1]['eventtime']  = $currentTime;
                
                $log['detail']['wager']['order_time']   = $currentTime;
                $log['detail']['wager']['end_time']     = $currentTime;
                $log['detail']['wager']['total_win']    = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon;

                $proof['lock_position']         = $result_val['LockPos'];

                $sub_log = [];
                $sub_log['sub_no']              = $result_val['CurrentSpinTimes'];
                $sub_log['game_type']           = 50;
                $sub_log['rng']                 = $result_val['RngData'];
                $sub_log['multiple']            = $result_val['Multiple'];
                $sub_log['win']                 = $result_val['TotalWin'] /  $this->demon;
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
                $win_action['amount']           = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon;
                $win_action['eventtime']        = $currentTime;
                array_push($log['actionlist'], $win_action);

                $wager= [];
                $wager['seq_no']                = $result_val['GamePlaySerialNumber'];
                $wager['order_time']            = $currentTime;
                $wager['end_time']              = $currentTime;
                $wager['user_id']               = $slotSettings->playerId;
                $wager['game_id']               = '182';
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
                $wager['total_win']             = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon;
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

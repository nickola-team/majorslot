<?php 
namespace VanguardLTE\Games\DragonKoiCQ9
{
    class Server
    {
        public $demon = 10;
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
            $originalbet = 1;
            $slotSettings->SetBet();
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 5}],"msg": null}');
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
                            $result_val['MaxBet'] = 20000;// $slotSettings->Bet[count($slotSettings->Bet) - 4] * $this->demon;
                            $result_val['MaxLine'] = 1;
                            $result_val['WinLimitLock'] = 1500000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
                            $result_val['ExtendFeatureByGame'] = null;
                            $result_val['ExtendFeatureByGame2'] = [["name"=>"FeatureMinBet","value"=>"4000"],["name"=>"BetMultiples","value"=>"100,200,500,1000,2000,5000,10000,20000,10,20,50"],["name"=>"AccumulatedFGMultiples","value"=>"0,0,0,0,0,0,0,0,0,0,0"],["name"=>"FireTimeLeft","value"=>"0,0,0,0,0,0,0,0,0,0,0"]];
                            $result_val['GameFlowBranch'] = 0;
                            $result_val['IsReelPayType'] = true;
                            $result_val['Cobrand'] = null;
                            $result_val['PlayerOrderURL'] = config('app.cq9history') . '/platform/?gametoken=' . auth()->user()->api_token;
                            $result_val['PromotionData'] = null; //$slotSettings->getPromotionData();
                            $result_val['IsShowFreehand'] = false;
                            $result_val['IsAllowFreehand'] = false;
                            $result_val['FeedbackURL'] = null;
                            $result_val['UserAccount'] = $user->username;
                            //$result_val['FreeTicketList'] = null;
                            $result_val['DenomMultiple'] = $initDenom * $this->demon;
                            $result_val['Currency'] ="KRW";
                            //$result_val['FreeSpinLeftTimesInfoList'] = null;
                            //$result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = ["g" => "GB8","s" => "5.16.3.6","l" => "0.0.0.51","si" => "11"];
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
                                    $allBet = $allBet * 80;
                                    $isBuyFreespin = true;
                                }
                                $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                                $_sum = $allBet / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent'], $isBuyFreespin);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '134' . substr($roundstr, 3, 7);
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
                        $lines = 40;
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
                $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
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
                    $allBet = $allBet * 80;
                }
                $slotSettings->SaveLogReport(json_encode($gamelog), $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, 'GB' . $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
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
                $allBet = $allBet * 80;
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
                $wager['seq_no']                = 'GB' . $result_val['GamePlaySerialNumber'];
                $wager['order_time']            = $currentTime;
                $wager['end_time']              = $currentTime;
                $wager['user_id']               = $slotSettings->playerId;
                $wager['game_id']               = 'GB8';
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
                $wager['bet_tid']               =  'pro-bet-' . 'GB' . $result_val['GamePlaySerialNumber'];
                $wager['win_tid']               =  'pro-win-' . 'GB' . $result_val['GamePlaySerialNumber'];
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

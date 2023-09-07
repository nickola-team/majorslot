<?php 
namespace VanguardLTE\Games\DoubleFlyCQ9
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
            $originalbet = 3;
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
                            $result_val['DefaultDenomIdx'] = 5;
                            $result_val['MaxBet'] = $slotSettings->Bet[count($slotSettings->Bet) - 4] * $this->demon;
                            $result_val['MaxLine'] = 1;
                            $result_val['WinLimitLock'] = 300000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
                            $result_val['ExtendFeatureByGame'] = null;
                            $result_val['ExtendFeatureByGame2'] = null;
                            $result_val['GameFlowBranch'] = 0;
                            $result_val['IsReelPayType'] = false;
                            $result_val['Cobrand'] = null;
                            $result_val['PlayerOrderURL'] = config('app.cq9history') . '/platform/?gametoken=' . auth()->user()->api_token;
                            $result_val['PromotionData'] = null; //$slotSettings->getPromotionData();
                            $result_val['IsShowFreehand'] = false;
                            $result_val['IsAllowFreehand'] = false;
                            $result_val['FeedbackURL'] = null;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['FreeTicketList'] = null;
                            $result_val['DenomMultiple'] = $initDenom * $this->demon;
                            $result_val['FreeSpinLeftTimesInfoList'] = null;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['MemberSystemInfo'] = ["lobby"=>"","mission"=>"","playerInterfaceURL"=>"","item"=>["freeTicket"=>false,"payAdditionCard"=>false]];
                            $result_val['Tag'] = ["g"=>"39","s"=>"5.27.1.0","l"=>"2.5.2.5","si"=>"38"];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["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"]];
                            $result_val['FGStripCount'] = 1;
                            $result_val['FGContext'] = [["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"]];
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
                                $pur_level = -1;
                                $slotEvent['slotEvent'] = 'bet';
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
                                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lines);
                                $slotSettings->SetBet();
                                $allBet = ($betline /  $this->demon) * $lines;
                                $isBuyFreespin = false;
                                $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                                $_sum = $allBet / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent'], $isBuyFreespin);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '6587' . substr($roundstr, 3, 8);
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
                        $lines = 25;
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
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, ($betline /  $this->demon) * $lines, $lines);
                $winType = $_spinSettings[0];
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
                if($winType == 'bonus'){
                    $slotSettings->SetBank('bonus', -1 * $totalWin / $this->demon);   
                }else{
                    $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin / $this->demon);
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
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $isState = true;
                }
            }
            

            $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
            if($isState == true){
                $allBet = ($betline /  $this->demon) * $lines;
                // $pur_mul = [200, 120];
                // if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                //     $allBet = $allBet * $pur_mul[$slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin')];
                // }
                $slotSettings->SaveLogReport(json_encode($gamelog),$allBet ,$lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon, $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
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
            if(isset($result_val['CurrentRound'])){
                $proof['fg_rounds']                 = $result_val['CurrentRound'];
            }else{
                $proof['fg_rounds']                 = 0;
            }
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
                $sub_log['game_type']           = 51;
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
                $bet_action['amount']           = ($betline /  $this->demon) * $lines;
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
                $wager['game_id']               = '152';
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = ($betline /  $this->demon) * $lines;
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

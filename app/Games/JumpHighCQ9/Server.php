<?php 
namespace VanguardLTE\Games\JumpHighCQ9
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
                            $result_val['ExtendFeatureByGame2'] = null;
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
                            $result_val['MemberSystemInfo'] = ["lobby"=>"","mission"=>"","playerInterfaceURL"=>"","item"=>["freeTicket"=>false,"payAdditionCard"=>false]];
                            $result_val['Tag'] = ["g"=>"52","s"=>"5.27.1.0","l"=>"2.5.2.9","si"=>"37"];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [
                                [
                                    "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"
                                ]
                            ];
                            $result_val['FGStripCount'] = 1;
                            $result_val['FGContext'] = [
                                [
                                    "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"
                                ]
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
                $wager['game_id']               = '52';
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

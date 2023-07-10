<?php 
namespace VanguardLTE\Games\NightCityCQ9
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
                            $result_val['DefaultDenomIdx'] = 3;
                            $result_val['MaxBet'] = $slotSettings->Bet[count($slotSettings->Bet) - 4] * $this->demon;
                            $result_val['MaxLine'] = 1;
                            $result_val['WinLimitLock'] = 300000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
                            $result_val['ExtendFeatureByGame'] = null;
                            $result_val['ExtendFeatureByGame2'] = [["name" => "FeatureMinBet","value" => "2100"]];
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
                            $result_val['Tag'] = ["g" => "229","s" => "5.27.1.0","l" => "1.1.59","si" => "34"];
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
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [
                                [
                                    "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"
                                ]
                            ];
                        }else if($packet_id == 31 || $packet_id == 42 || $packet_id == 33){
                            $lines = 20;
                            if($packet_id == 31){
                                $betline = $gameData->PlayBet;// * $gameData->MiniBet;
                            }else if($packet_id == 33){
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            }else if($packet_id == 42){
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            }
                            if($packet_id == 42 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                                $slotEvent['slotEvent'] = 'freespin';
                            }else if($packet_id == 33){
                                $slotEvent['slotEvent'] = 'Tumb';
                            }else{
                                $slotEvent['slotEvent'] = 'bet';
                                $pur_level = -1;
                                if(isset($gameData->ReelPay) && $gameData->ReelPay > 0){
                                    $pur_level = 0;
                                }
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'IsRespin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                                $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur_level);
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', ($betline /  $this->demon));
                                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                                $slotSettings->SetBet();
                                $allBet = ($betline /  $this->demon) * $lines;
                                $isBuyFreespin = false;
                                if($pur_level == 0){
                                    $allBet = $allBet * 105;
                                    $isBuyFreespin = true;
                                }else if($gameData->MiniBet == 25){
                                    $allBet = ($betline /  $this->demon) * 25;
                                }
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
                    $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                    $lines = 20;
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                        // FreeSpin Balance add
                        $slotEvent['slotEvent'] = 'freespin';
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
                    if($slotSettings->GetGameData($slotSettings->slotId . 'IsRespin') > 0){
                        $slotEvent['slotEvent'] = 'Tumb';
                        while($slotSettings->GetGameData($slotSettings->slotId . 'IsRespin') > 0){
                            $result_val = [];
                            $result_val['Type'] = 3;
                            $result_val['ID'] = 133;
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
            if($slotEvent == 'freespin' || $slotEvent == 'Tumb'){
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
                if($stack['IsRespin'] == false){
                    $totalWin = $totalWin + $stack['SpecialAward'];
                }
            }
            foreach($stack['ExtendFeatureByGame2'] as $index => $item){
                if(isset($item['Name'])){
                    if($item['Name'] == 'SpinAccumulateWin'){
                        $item['Value'] = $item['Value'] / $originalbet * $betline;
                        $stack['ExtendFeatureByGame2'][$index] = $item;
                    }else if($item['Name'] == 'SpecialAward'){
                        $item['Value'] = $item['Value'] / $originalbet * $betline;
                        $stack['ExtendFeatureByGame2'][$index] = $item;
                    }
                }
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
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes && $stack['IsRespin'] == false){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $isState = true;
                }
            }else if($stack['IsRespin'] == true){
                $isState = false;
                $slotSettings->SetGameData($slotSettings->slotId . 'IsRespin', 1);
            }else{
                $slotSettings->SetGameData($slotSettings->slotId . 'IsRespin', 0);
            }
            

            $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
            if($isState == true){
                $allBet = ($betline /  $this->demon) * $lines;
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                    $allBet = $allBet * 105;
                }else if($slotSettings->GetGameData($slotSettings->slotId . 'MiniBet') == 25){
                    $allBet = ($betline /  $this->demon) * 25;
                }
                $slotSettings->SaveLogReport(json_encode($gamelog), $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon, $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
            }

            // if($slotEvent != 'freespin' && $freespinNum > 0){
            //     $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
            // }
            return $result_val;
        }
        public function parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines){
            $currentTime = $this->getCurrentTime();
            $allBet = $betline * $lines;
            if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                $allBet = $allBet * 105;
            }else if($slotSettings->GetGameData($slotSettings->slotId . 'MiniBet') == 25){
                $allBet = ($betline /  $this->demon) * 25;
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
            if(isset($result_val['FreeSpin'])){
                $proof['fg_times']                  = $result_val['FreeSpin'];
            }else{
                $proof['fg_times']                  = 0;
            }
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
            if($slotEvent == 'freespin' || $slotEvent == 'Tumb'){
                $log = $slotSettings->GetGameData($slotSettings->slotId . 'GameLog');
                $log['actionlist'][1]['amount']     = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon;
                $log['actionlist'][1]['eventtime']  = $currentTime;
                
                $log['detail']['wager']['order_time']   = $currentTime;
                $log['detail']['wager']['end_time']     = $currentTime;
                $log['detail']['wager']['total_win']    = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon;
                if(isset($result_val['LockPos'])){
                    $proof['lock_position']         = $result_val['LockPos'];
                }                

                $sub_log = [];
                $sub_log['sub_no']              = count($log['detail']['wager']['sub']) + 1;
                if($slotEvent == 'freespin'){
                    $sub_log['game_type']           = 51;
                }else{
                    $sub_log['game_type']           = 30;
                }
                $sub_log['rng']                 = $result_val['RngData'];
                $sub_log['multiple']            = $result_val['Multiple'];
                $sub_log['win']                 = $result_val['TotalWin'] /  $this->demon;
                if($result_val['TotalWin'] == 0){
                    $sub_log['win']                 = $result_val['SpecialAward'] /  $this->demon;
                }
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
                $wager['game_id']               = '229';
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

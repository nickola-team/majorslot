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
                            $result_val['PlayerOrderURL'] = config('app.cq9history') . '/platform/?gametoken=' . auth()->user()->api_token;
                            $result_val['PromotionData'] = null; //$slotSettings->getPromotionData();
                            $result_val['IsShowFreehand'] = false;
                            $result_val['IsAllowFreehand'] = false;
                            $result_val['FeedbackURL'] = '/feedback/?token=' . auth()->user()->api_token;
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
                                ["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"],["kgesIqBshIuBVRAViA6VMYt7bhMnWguO8BWENfHHeoHtonNcRpFURFbJMrkuIPkcmoBRiORHjVVWV+YT/ougCwQx/I6ZZ8E1XIFZ7Q==","MzRuEmO2y0eAqQGCcFDGvHEM4ZXpYg5A1SZ2IjDg5mGLLEAMhWMeIbDHn7+k9eeIDFDJWu5U5zCiV/sI9GuLj05EHLrZsBf4GwIF8w==","FDT5YDDrAiXw7Go5N0N13iEXVbfYxE3nXQIcfXHUlsy6MhdQWy/SckoYKDEseTRJSVaQDhLlwctJYKaYvf6ke27bVbhCnpoPwasf2w==","ql9BDZH5xzbkacoMLv5D0pvQki5GSxghK5X63jA10cu/MOtAy2Gk1A2G0kr8xrUkCLUesdW0KRJn1zFglMCOVSvu7H6tIujQy8OOBWSVdxjPryxi/7ezyGRT4BU=","oWclfVnGQebZugyDgkyKT414hs7d3zo4jK/Tw4/fhBDRE/0Q0GcqR0CM0qAtC3NJ++QlLdXccKyJAxSxahAasYsk/+XlnYYd+GSPiA=="]
                              ];
                            $result_val['FGStripCount'] = 2;
                            $result_val['FGContext'] = [
                                ["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"],["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"]
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
            $winType = 'none';
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
                $slotSettings->SetBalance($totalWin / $this->demon);
                if($winType == 'bonus'){
                    $slotSettings->SetBank('bonus', -1 * $totalWin / $this->demon);
                }else{
                    $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin / $this->demon);
                }
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

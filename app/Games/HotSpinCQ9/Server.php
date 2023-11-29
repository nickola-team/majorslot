<?php 
namespace VanguardLTE\Games\HotSpinCQ9
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
            $originalbet = 3;
            $slotSettings->SetBet();
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 8}],"msg": null}');
                    $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetBalance().'],"evt": 1}');                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                    //$slotSettings->SetGameData($slotSettings->slotId . 'FreeSpinAction', 'bet');
                }else if($paramData['req'] == 2){
                    $gameDatas = $this->parseMessage($paramData['vals']);
                    $response_packet = [];
                    $result_vals = [];
                    for($i = 0; $i < count($gameDatas); $i++){
                        $gameData = json_decode($gameDatas[$i]);
                        $type = $gameData->Type;
                        $packet_id = $gameData->ID;
                        $slotSettings->SetGameData($slotSettings->slotId . 'PackID', $packet_id);
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
                            $result_val['MaxBet'] = 4000;// $slotSettings->Bet[count($slotSettings->Bet) - 4] * $this->demon;
                            $result_val['MaxLine'] = 20;
                            $result_val['WinLimitLock'] = 300000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
                            $result_val['ExtendFeatureByGame'] = null;
                            $result_val['ExtendFeatureByGame2'] = [["name" => "FeatureMinBet","value" => "350"]];
                            $result_val['GameFlowBranch'] = 0;
                            $result_val['IsReelPayType'] = false;
                            $result_val['Cobrand'] = null;
                            $result_val['PlayerOrderURL'] = config('app.cq9history') . '/platform/?gametoken=' . auth()->user()->api_token;
                            $result_val['PromotionData'] = null; //$slotSettings->getPromotionData();
                            $result_val['IsShowFreehand'] = false;
                            $result_val['IsAllowFreehand'] = false;
                            $result_val['FeedbackURL'] = '/feedback/?token=' . auth()->user()->api_token;
                            $result_val['UserAccount'] = $user->username;
                            //$result_val['FreeTicketList'] = null;
                            $result_val['DenomMultiple'] = $initDenom * $this->demon;
                            $result_val['Currency'] ="KRW";
                            //$result_val['FreeSpinLeftTimesInfoList'] = null;
                            //$result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = ["g" => "19","s" => "5.27.1.0","l" => "2.4.36.1","si" => "36"];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = -1;
                            $result_val['BGStripCount'] = 1;
                            $result_val['BGContext'] = [
                                ["IkkfrqYgm4hhz8cGXWg/Lpfi46lR5cswBGAaO/BTX1WWZYtNJmd24cjaFU2p2609br5vsAyA6Dmvco3DrkmNfpAgVVvY+BgrfSuCuVQgsrSZIFHbDFZPrTfaP0slPxnA4lVJWCKjo0Abj+Vg53r82xQQYPOgc9sYMEEn9l/3t9FbvV/N7i1UY3ZAwSY=","ikroy9p9XiqjF5U2LM98ICj32pRahA+pHGj+/fsDQSvbuVxa7YCb6XXgk1h97ajkQM7vlcGi8kPOSycv55BlQwQ6ccxQEyfG7T9pI6MMz7AlvhZCsmkSs8Ib68tocvd5BFlQzpb4iUwB7ER/UP9e12xKZZupTP8cfuZAVw==","5UCEAIgxpbtM9bzweTG/Iait9KPO39zj5T+r9ie88XG+M6Nkg03uU45Hf31VOXO4jxFuIuPtIvdyjFlhOtxiKe5Rcx6lfuaRVPpobofXAMU/k/dIrWcN4wF3Mw10EExxx3v5v3Uvgns1U60JNFRfFCqEkIx1U67YKuhBh+Q420YtQkgUe7syaa0OKeA=","Tdiz1IrtrY1iVECxxESAWhqESZzN1tptKmdHEZN2eqmdZVFj1niCC780ftqFe11MDWfG1CLM76AXXaf+KyznvgfsryhH+xdsT6Z8et3Bw5NWQB+C8SwGaKD+ZaX5A+iuzL7/Pbm1nSS9R235F5hlUofUd3cXFXCnRjjWsESsunancBkiAnipSPmWcU8=","Hgel8fXo4XSoF3eQnm+Pev6kikjPAUMGZkKOfZVjWqnHmNFlbXMlFqbdDwH5lPjtZ5pgDu2GAhWNJR8H0qlrD7jayFhCotHG5J4IZbc0p/5+DRziofgEXvAsrdaBviYTHYmaKoYImAmbkQywqUTqy4FqKPTbLu8pyoxmlA=="]
                              ];
                            $result_val['FGStripCount'] = 0;
                            $result_val['FGContext'] = [];
                        }else if($packet_id == 31 ){
                            $lines = 20;
                            $betline = $gameData->PlayBet;// * $gameData->MiniBet;
                        
                            $slotEvent['slotEvent'] = 'bet';
                            $pur_level = -1;
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
                            $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 10);
                            $slotSettings->SetBet();
                            $allBet = ($betline /  $this->demon) * $lines;
                            
                            $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                            $_sum = $allBet / 100 * $slotSettings->GetPercent();
                            $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                            $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                            $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                            $roundstr = sprintf('%.4f', microtime(TRUE));
                            $roundstr = str_replace('.', '', $roundstr);
                            $roundstr = '672' . substr($roundstr, 3, 7);
                            $slotSettings->SetGameData($slotSettings->slotId . 'GamePlaySerialNumber', $roundstr);
                            

                            $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet,$packet_id);
                            $result_val['EmulatorType'] = $emulatorType;

                            $slotSettings->SaveGameData();
                        }else if($packet_id == 32 ){
                            $result_val['ErrorCode'] = 0;
                            
                            $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                            
                        }else if($packet_id == 44){
                            $slotEvent['slotEvent'] = 'bonus';
                            $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            $lines = 20;
                            $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet,$packet_id);
                            $result_val['EmulatorType'] = $emulatorType;

                            $slotSettings->SaveGameData();
                        }else if($packet_id == 45){
                            $slotEvent['slotEvent'] = 'bonus';
                            $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            $lines = 20;
                            $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet,$packet_id);
                            $result_val['EmulatorType'] = $emulatorType;

                            $slotSettings->SaveGameData();
                        }else if($packet_id == 46){
                            //$slotSettings->SetGameData($slotSettings->slotId . 'FreespinAction', 'bonus');
                            $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                            $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            $result_val['TotalWinAmt'] = $stack['TotalWinAmt'] / $originalbet * $betline;
                            $result_val['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                            $result_val['NextModule'] = 0;
                            $result_val['GameExtraData'] = "";
                            // $slotSettings->SetBalance($stack['TotalWinAmt']);
                            // $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $stack['TotalWinAmt']);
                            // $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $stack['TotalWinAmt']);
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
                    $lines = 20;
                    if($slotSettings->GetGameData($slotSettings->slotId . 'PackID') == 44 || $slotSettings->GetGameData($slotSettings->slotId . 'PackID') == 45){
                        $pur_level = -1;
                        $tumbAndFreeStacks= $slotSettings->GetReelStrips('bonus', ($betline /  $this->demon) * $lines,$slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin'));
                        if($tumbAndFreeStacks == null){
                            $response = 'unlogged';
                            exit( $response );
                        }
                        $stack = $tumbAndFreeStacks[0];
                        $freespinNum =30;
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespinNum);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $tumbAndFreeStacks);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 2);
                        if(isset($stack['TotalWinAmt'])){
                            $stack['TotalWinAmt'] = $stack['TotalWinAmt'] / $originalbet * $betline;
                        }
                        if(isset($stack['ScatterPayFromBaseGame'])){
                            $stack['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                        }
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                        // FreeSpin Balance add
                        $slotEvent['slotEvent'] = 'bonus';
                        // $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                        // $lines = 10;
                        $count = 0;
                        while($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                            $result_val = [];
                            $result_val['Type'] = 3;
                            $result_val['ID'] = 145;
                            $result_val['Version'] = 0;
                            $result_val['ErrorCode'] = 0;
                            $result_val['EmulatorType'] = 0;
                            $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet,30);
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
        public function generateResult($slotSettings, $result_val, $slotEvent, $betline, $lines, $originalbet,$packetID){
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, ($betline /  $this->demon) * $lines, $lines);
            $winType = $_spinSettings[0];
            //$winType = 'bonus';

            if($slotEvent == 'bonus'){
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
                $stack['TotalWin'] = ($stack['TotalWin'] / $originalbet * $betline);
                
                $totalWin = $stack['TotalWin'] / $this->demon;
            }
            if(isset($stack['AccumlateWinAmt']) && $stack['AccumlateWinAmt'] > 0){
                $stack['AccumlateWinAmt'] = $stack['AccumlateWinAmt'] / $originalbet * $betline;
                //$totalWin = $stack['AccumlateWinAmt'] / $this->demon;
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
            $nextModule = 0;
            //$currentSpinTimes = 0;
            if($slotEvent == 'bonus'){
                if(isset($stack['NextModule'])){
                    $nextModule = $stack['NextModule'];   
                }
                 
                //$currentSpinTimes = $stack['CurrentSpinTimes'];    
            }
            if($packetID ==31){
                if(isset($stack['udsOutputWinLine']) && $stack['udsOutputWinLine'] != null){
                    foreach($stack['udsOutputWinLine'] as $index => $value){
                        if($value['LinePrize'] > 0){
                            $value['LinePrize'] = $value['LinePrize'] / $originalbet * $betline;
                        }
                        $stack['udsOutputWinLine'][$index] = $value;
                    }
                }
            }
            
            if($slotEvent != 'bonus' && isset($stack['IsTriggerFG'])){
                $isTriggerFG = $stack['IsTriggerFG'];
            }
            $freespinNum = 0;
            if(isset($stack['NextModule'])){
                $freespinNum = $stack['NextModule'];
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
                if($slotEvent != 'bonus'){                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespinNum);
                }else{
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $freespinNum);
                }
                $isState = false;
            }
            if($slotEvent == 'bonus'){                
                $isState = false;
                /*if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes && $stack['AwardRound'] == $stack['CurrentRound']){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $isState = true;
                }*/
                if($nextModule == 0){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $isState = true;
                }
            }

            
            if($packetID == 44){

            }else{
                $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
                if($isState == true){
                    $allBet = ($betline /  $this->demon) * $lines;
                    $slotSettings->SaveLogReport(json_encode($gamelog), $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
                }
            }

            

            if($slotEvent != 'bonus' && $freespinNum > 0){
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
            }
            return $result_val;
        }
        public function parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines){
            $currentTime = $this->getCurrentTime();
            $allBet = ($betline /  $this->demon) * $lines;
            if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                $allBet = $allBet * 35;
            }
            $proof = [];
            $proof['win_line_data']             = [];
            if(isset($result_val['SymbolResult'])){
                $proof['symbol_data']               = $result_val['SymbolResult'];
            }
            
            $proof['symbol_data_after']         = [];
            if(isset($result_val['ExtraData'])){
                $proof['extra_data']                = $result_val['ExtraData'];
            }else{
                $proof['extra_data']                = [0,0,0,0,0,0,0,0,0];
            }
            if(isset($result_val['ReellPosChg'])){
                $proof['reel_pos_chg']              = $result_val['ReellPosChg'];
            }
            if(isset($result_val['ReelLenChange'])){
                $proof['reel_len_change']           = $result_val['ReelLenChange'];
            }
            
            if(isset($result_val['ReelPay'])){
                $proof['reel_pay']                  = $result_val['ReelPay'];
            }
            if(isset($result_val['RespinReels'])){
                $proof['respin_reels']              = $result_val['RespinReels'];
            }
            if(isset($result_val['BonusType'])){
                $proof['bonus_type']              = $result_val['BonusType'];
            }
            if(isset($result_val['SpecialAward'])){
                $proof['special_award']              = $result_val['SpecialAward'];
            }

            if(isset($result_val['SpecialSymbol'])){
                $proof['special_symbol']              = $result_val['SpecialSymbol'];
            }
            if(isset($result_val['IsRespin'])){
                $proof['is_respin']              = $result_val['IsRespin'];
            }
            /*if(isset($result_val['bonus'])){
                $proof['fg_times']              = $result_val['bonus'];
            }else{
                $proof['fg_times']              = 0;
            }*/
            $proof['fg_times']              = 0;
            if(isset($result_val['CurrentRound'])){
                $proof['fg_rounds']                 = $result_val['CurrentRound'];
            }else{
                $proof['fg_rounds']                 = 0;
            }
            if(isset($result_val['NextSTable'])){
                $proof['next_s_table']              = $result_val['NextSTable'];
            }
            $proof['denom_multiple'] = 100;
            
            $proof['extend_feature_by_game']    = [];
            $proof['extend_feature_by_game2']   = [];
            /*foreach($result_val['ExtendFeatureByGame2'] as $item){
                $newItem = [];
                $newItem['name'] = $item['Name'];
                if(isset($item['Value'])){
                    $newItem['value'] = $item['Value'];
                }else{
                    $newItem['value'] = null;
                }
                $proof['extend_feature_by_game2'][] = $newItem;
            }*/
            $proof['g_v']                       = "5.16.3.6";
            $proof['l_v']                       = "0.0.0.56";

            if(isset($result_val['udsOutputWinLine'])){
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
            }
            
            if($slotEvent == 'bonus'){
                $log = $slotSettings->GetGameData($slotSettings->slotId . 'GameLog');
                $log['actionlist'][1]['amount']     = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                $log['actionlist'][1]['eventtime']  = $currentTime;
                
                $log['detail']['wager']['order_time']   = $currentTime;
                $log['detail']['wager']['end_time']     = $currentTime;
                $log['detail']['wager']['total_win']    = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');

                if(isset($result_val['LockPos']))
                {
                    $proof['lock_position']         = $result_val['LockPos'];
                }

                /*$sub_log = [];
                if(isset($result_val['CurrentSpinTimes'])){
                    $sub_log['sub_no']              = $result_val['CurrentSpinTimes'];
                }
                
                $sub_log['game_type']           = 50;
                if(isset($result_val['RngData'])){
                    $sub_log['rng']                 = $result_val['RngData'];
                }
                if(isset($result_val['Multiple'])){
                    $sub_log['multiple']                 = $result_val['Multiple'];
                }
                if(isset($result_val['TotalWin'])){
                    $sub_log['win']                 = $result_val['TotalWin'];
                }
                if(isset($result_val['WinLineCount'])){
                    $sub_log['win_line_count']                 = $result_val['WinLineCount'];
                }
                if(isset($result_val['WinType'])){
                    $sub_log['win_type']                 = $result_val['WinType'];
                }
                $sub_log['proof']               = $proof;
                array_push($log['detail']['wager']['sub'], $sub_log);*/
                $pick_log = [];
                if(isset($result_val['CurrentSpinTimes'])){
                    $pick_log['pick_no']              = $result_val['CurrentSpinTimes'];
                }else{
                    $pick_log['pick_no']              = 1;
                }
                $pick_log['multiple']           = "0";
                $pick_log['game_type']           = 888;
                /*if(isset($result_val['RngData'])){
                    $pick_log['rng']                 = $result_val['RngData'];
                }*/
                /*if(isset($result_val['Multiple'])){
                    $pick_log['multiple']                 = $result_val['Multiple'];
                }*/
                if(isset($result_val['TotalWin'])){
                    $pick_log['win']                 = $allBet;
                }
                
                $pick_log['proof']               = $proof;
                array_push($log['detail']['wager']['pick'], $pick_log);
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
                $win_action['amount']           = $result_val['TotalWin'];
                $win_action['eventtime']        = $currentTime;
                array_push($log['actionlist'], $win_action);

                $wager= [];
                $wager['seq_no']                = $result_val['GamePlaySerialNumber'];
                $wager['order_time']            = $currentTime;
                $wager['end_time']              = $currentTime;
                $wager['user_id']               = $slotSettings->playerId;
                $wager['game_id']               = '19';
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
                if($result_val['WinType'] == 4){
                    $wager['wager_type']            = 1;
                }else{
                    $wager['wager_type']            = 0;
                }
                
                $wager['total_win']             = $result_val['TotalWin'];
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

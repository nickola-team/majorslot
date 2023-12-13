<?php 
namespace VanguardLTE\Games\HotDJCQ9
{
    class Server
    {
        public $demon = 10;
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
            $originalbet = 5;
            $slotSettings->SetBet();
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 6}],"msg": null}');
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
                            $result_val['MaxBet'] = 50000;// $slotSettings->Bet[count($slotSettings->Bet) - 4] * $this->demon;
                            $result_val['MaxLine'] = 1;
                            $result_val['WinLimitLock'] = 1500000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
                            $result_val['ExtendFeatureByGame'] = null;
                            $result_val['ExtendFeatureByGame2'] = [["name" => "FeatureMinBet","value" => "1000"]];
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
                            $result_val['Tag'] = ["g" => "GB5","s" => "5.16.3.6","l" => "0.0.0.49","si" => "10"];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            $result_val['BGContext'] = [
                               ["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"]
                              ];
                            $result_val['FGStripCount'] = 1;
                            $result_val['FGContext'] = [
                                ["neQ7Db4RnNMJpP5OWJbcfwqbVdwVxZMj25ynGWr+B61m/WxpvbuzzcNADc//ZxNpqUj8N3oHMJsAPOPQglzJ+qpT4N7M7x06wG6FJpF00Zc6toY5Avm5maXZ7axpHl+tziFTlRmsN4EO/uDoBUqlkE5k51lK0PENAvnQL0x8yyibH2SVOTFcTz5Cq9929eZ9f+CSSCAN31FQ81cc+l24U8OIFhcsL52NFjY5kVNZgIRpgXOj1eCrnisH5Dc=","x60t8kTMiYVQFP9G8oS/ZbtpzTGV0JR1niwdEatJddWIyW1MeT1yabfMeFxfFABrdPRfjMhgk6Ht+FkWolGdEyzZ1Px7sIoCqAiD1bhgac8phYlVknWqb1E+4gxBTJ9bzmYop1US6EhUnTUIPhJ8OL2/dlWY0u387F3kyAmUjpRUOFuGpaTb7fTG1j5RkAASmernBIzJcGnwDA2+","lK5qaJxf4wC1Z2uNpRnblljUFEWEHsCAOQX1iyFBoe2dab5XQAtgsU4NXNehmYHsnQUcvuNAgDin3b854F4FSQUzS/iBx7TXwrnhcXyV71uCdgzoLsGF6J6b08kZyGqhE2DxmL66oAHmFbdzjmgO82Lq4UpUxJ0xnhhlPKsb2Fe8Z8bNd+ZQtByyervKTOsVg8f3n8pF3CBd7J6kLVvC7aipafT5jM1zNlF/Ib8E5gg5ErVoZb0Sk4dI5TuaRz2eVSsrxnit3mrD4d/QOf4cmVvpljMoKEruLQiZujnu1szeWG1pYhNFBXBr7ZZcYmFU9KQE7diz/0zXwVQEjbhk8pDfWHKzbSlT0km9Ag==","I6g81sq5b9qk1d8MHIBYIev3Oxt8bUiYeKYWlosF2AuLlQ263fifFOWjc7hUoEH5/l5juw8YDvhcWjbMdyY9S0bS4fdi6VA2gPMf6ap55G4ijuKZGe1o/QDFnubsHqkGmf/D8fmtUmS+c+PXVRTRAus8ZBPcljUFYhqOithHxHlU9N2GW2m8UVbwo+UaS3kta0YbF4I/8KffUhPGLB9F1WQE72luwVPJfLd1h/Dj2uyCca5UtiuYg82cZ4rtL5A26GRymJmb2sgTI56UDQ7dMOYmNT+FIt9+qm50BQ2S5PeQgfs9v8ZN0MGFywE=","39elwtuBqL0MmQY9vpgH3pNpeqb18PlRL/lpZGaBeGIA+NAQfaNST8ehqIwMFd+kZgVuG062gh6qdumqFy0ZBQHB55X6JKp532q1BeyqOowa9xQnGoRj+ghABj+EvmDqNezCUMrTqadJwqTHmPeBsRBfGGqwCAbMMqcU2zBk9oqWxHvWJ1dsh/2b8T/rFdKVYHQRB8o+w8USLH1le3NWa4K6QGfIQG+MavkuaA==","Q3XMKGhk90UFHpbAUQzyNib9RVQvNzsxijq5eNXaYU8tZUk/wVfUP5paCkhhdZUgYebfPoei0t/YwKbPKIDTeU17YdGZGEkF151hCcnRBRMVBDsTe1TIdG8eusnMge2gN2PmGJkzW8XK6iULhGayYW7bvlkT/sPOpqcLvCcvGR90H/m/mfysaR1VeYlTN/Dfe41qWIJn69OFFlUU55i6dHsl2mTTv5WVzko+ZAH4RIbCbm0pmaLcqhdjkFM="]
                            ];
                        }else if($packet_id == 31 || $packet_id == 42 || $packet_id == 33){
                            $lines = 20;
                            if($packet_id == 31 || $packet_id == 33){
                                $betline = $gameData->PlayBet;// * $gameData->MiniBet;
                            }else if($packet_id == 42){
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            }
                            if($packet_id == 33 && $slotSettings->GetGameData($slotSettings->slotId . 'Respin') > 0){
                                $slotEvent['slotEvent'] = 'respin';
                            }else if($packet_id == 42 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                                $slotEvent['slotEvent'] = 'freespin';
                            }else{
                                $slotEvent['slotEvent'] = 'bet';
                                $pur_level = -1;
                                if($gameData->ReelPay > 0){
                                    $pur_level = 0;
                                }
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
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
                                $isBuyFreespin = false;
                                if($pur_level == 0){
                                    $allBet = $allBet * 50;
                                    $isBuyFreespin = true;
                                }
                                $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                                $_sum = $allBet / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent'], $isBuyFreespin);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '139' . substr($roundstr, 3, 7);
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
                    $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                    $lines = 20;
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
                    if($slotSettings->GetGameData($slotSettings->slotId . 'Respin') > 0){
                        $slotEvent['slotEvent'] = 'respin';
                        while($slotSettings->GetGameData($slotSettings->slotId . 'Respin') > 0){
                            $result_val = [];
                            $result_val['Type'] = 3;
                            $result_val['ID'] = 133;
                            $result_val['Version'] = 0;
                            $result_val['ErrorCode'] = 0;
                            $result_val['EmulatorType'] = 0;
                            $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet,1000);
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
            $allBet = ($betline /  $this->demon) * $lines;
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                    $allBet = $allBet * 50;
                }
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, $allBet, $lines);
                $winType = $_spinSettings[0];
                //$winType = 'win';
            if($slotEvent == 'freespin' || $slotEvent == 'respin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
            }else{
                
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $winType = 'bonus';
                }
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, $allBet, $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin'));
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
            /*$freespinNum = 0;
            if(isset($stack['FreeSpin']) && count($stack['FreeSpin']) > 0){
                $freespinNum = $stack['FreeSpin'][0];
            }*/
            $freespinNum = 0;
            if(isset($stack['NextModule'])){
                $freespinNum = $stack['NextModule'];
            }


            $newRespin = false;
            if(isset($stack['IsRespin'])){
                if($stack['IsRespin'] == true){
                    $newRespin = true;
                    $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 1);
                    
                }else{
                    $newRespin = false;
                    $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
                }
            }

            if(isset($stack['IsTriggerFG']) && $stack['IsTriggerFG'] == true){
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
                if($slotEvent != 'freespin'){         
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespinNum);
                    if(isset($stack['IsTriggerFG']) && $stack['IsTriggerFG'] == true){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $freespinNum);
                    } 
                }else{
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $freespinNum);
                }
                $isState = false;
            }
            if($slotEvent == 'freespin'){                
                $isState = false;
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes && $stack['AwardRound'] == $stack['CurrentRound'] && $newRespin == false){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $isState = true;
                }
            }else if($slotEvent == 'respin' && $slotSettings->GetGameData($slotSettings->slotId . 'FreeAction') > 0){
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeAction',0);
                $slotEvent = 'freespin';
                $isState = true;
            }else if($newRespin == true){
                $isState = false;
            }
            

            $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
            if($isState == true){
                $allBet = ($betline /  $this->demon) * $lines;
                if($slotEvent == 'freespin' && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                    $allBet = $allBet * 50;
                }
                $slotSettings->SaveLogReport(json_encode($gamelog), $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, 'GB' . $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
            }

            /*if($slotEvent != 'freespin' && $freespinNum > 0){
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
            }*/
            return $result_val;
        }
        public function parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines){
            $currentTime = $this->getCurrentTime();
            $allBet = ($betline /  $this->demon) * $lines;
            if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                $allBet = $allBet * 50;
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
            if($slotEvent == 'freespin' || $slotEvent == 'respin'){
                $log = $slotSettings->GetGameData($slotSettings->slotId . 'GameLog');
                $log['actionlist'][1]['amount']     = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                $log['actionlist'][1]['eventtime']  = $currentTime;
                
                $log['detail']['wager']['order_time']   = $currentTime;
                $log['detail']['wager']['end_time']     = $currentTime;
                $log['detail']['wager']['total_win']    = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                if(isset($result_val['LockPos'])){
                    $proof['lock_position']         = $result_val['LockPos'];
                }
                

                $sub_log = [];
                if(isset($result_val['CurrentSpinTimes'])){
                    $sub_log['sub_no']              = $result_val['CurrentSpinTimes'];
                }
                if($slotEvent == 'respin'){
                    $sub_log['game_type']           = 30;    
                }else{
                    $sub_log['game_type']           = 50;
                }
                
                if(isset($result_val['RngData'])){
                    $sub_log['rng']                 = $result_val['RngData'];
                }
                if(isset($result_val['Multiple'])){
                    $sub_log['multiple']                 = $result_val['Multiple'];
                }
                if(isset($result_val['TotalWin'])){
                    $sub_log['win']                 = $result_val['TotalWin'] / $this->demon;
                }
                if(isset($result_val['WinLineCount'])){
                    $sub_log['win_line_count']                 = $result_val['WinLineCount'];
                }
                if(isset($result_val['WinType'])){
                    $sub_log['win_type']                 = $result_val['WinType'];
                }
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
                $wager['game_id']               = 'GB5';
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

<?php 
namespace VanguardLTE\Games\SixTorosCQ9
{
    class Server
    {
        public $demon = 1;
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
            $originalbet = 5;
            $slotSettings->SetBet();  
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 11}],"msg": null}');
                    $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetBalance().'],"evt": 1}');                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());

                    $slotSettings->SetGameData($slotSettings->slotId . 'Multiple',1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);

                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeAction',0);
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
                            $result_val['MaxBet'] = 3500;
                            $result_val['MaxLine'] = 1;
                            $result_val['WinLimitLock'] = 255000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
                            $result_val['ExtendFeatureByGame'] = null;
                            $result_val['ExtendFeatureByGame2'] = null;
                            $result_val['IsReelPayType'] = true;
                            $result_val['Cobrand'] = null;
                            $result_val['PlayerOrderURL'] = config('app.cq9history') . '/platform/?gametoken=' . auth()->user()->api_token;
                            $result_val['PromotionData'] = null;
                            $result_val['IsShowFreehand'] = false;
                            $result_val['IsAllowFreehand'] = false;
                            $result_val['FeedbackURL'] = '/feedback/?token=' . auth()->user()->api_token;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['FreeSpinLeftTimesInfoList'] = null;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = [
                                "g" => "173",
                                "s" => "5.27.1.0",
                                "l" => "2.4.34.3",
                                "si" => "60"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["kTG4ChIdEsClAtFEyIW1dE/mqHOaEoGm6pRTnSxzPLXil+EDg8OuVxMoH+wcGeZXHS/0LtdkS4aTsrt5WFBKwIm1YBmq5Et/jKnbH68Ui44JbVAyhkqZVpbKwk+rrBSdvN+FOH7M0pzEIFFEMxcnndN91fz6dX50Bw0sVQ==","AKXeNbe2u8fSiqKKRKY7WYMr1OqxyVGu1vwFv/9lLKJRtDwegcx8WKSOyOIu42Zl2j4NiVDsRHx8cw2D6aoEhVvDfWvhogfMiwkwWgGi0IhtgKz8w0zRfsMG+DmER5DSt1xq7uD4vbboJQ0F6M1Jr8hc+5qO9BxOXt1paA==","2uExZC5CwaTA44F6ynhP1M7Fnef+6mq5IceuwDbtmV1Qof/iKNAm9kTWoeEcTQlHYHm+xBPB8WdDcdxw8E42ivi7TzaL+sS3q9SnGaRrQVL5JCZySZsE8vexhcMBqlNHlhmYIczabrcTKk+p6KsvkGi4Xa8sNjBkId7big==","4uo3DlJEgqXqUlZOyirkUow+KKUg1Uli6INM4NQX4LDeiTBx1O56CJceqJCwl4yBCZPT6NKbcFEuTiKdTOSR9g/2LOWf1EFClZBSxOoV4kDQWy6iO4xgq5jrHJYSewe0tSUOsRu/yzmlxup6sStI2lsQ+HVlvU1yDDbqAQ==","3jKx7odHNqRbPL5ICkR09pYNf+LQ3R+sa9qQkKOEC1Pw9bWe9oYdXK0EVE2HQwDQSkF/2rNL+sASR3KQOiwGiICjWYkCbX8bLsqAkiJ7GK1tm/tjhizYFjWnOJ07FqhLHiK9qBNtr2UIGaHk9dHCmS0VOszbg6tj73z3zw==","YMAwWSuhyJKr1LcE0e6s5UQmdhvFfMEQ5KlDtMm5qxEyfDAe0qCNp5AWj6EO77jahjQwaahTQ3wa3vPz2iYA1SaBaZ3sBXyVCXZBH1NSll8P6Ga41D0sAZtC6zZ57x8TFLfSZFz6yJviDYmQRcyNmgcxaTUhyoCJCKtLjg=="]];
                            $result_val['FGStripCount'] = 1;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["DElTUGxBBFCRBkdqnAFauFS3WlFxRZK8K0C1k+D7olLOjC5nyjhR1BTiMBQC8E//lO4l5lO8To/MmUz3dQbwcOKE7vV5wLgR1dqrBTO+9k/BPNYOrNDMfGnz06XD7WkIWh9MuDNm1PUqrW+WdiJz4CBJdgGHio/UAMx0hy3FnJzZ/Sgt5YSTGlAgDdQ=","WTUvejMJTolHwwh17gL5BvuZUMql8IXT8MZT0TSEK5nGRqtF27jAemDhlRU92w1HcCFt4CKRbr8ptEha8WtuOJO1OrwPjn4xYmPwnrYNlGO1/g8D66Xs85tDFhlmCVkEw8OQrJALVyLC8n58F2NHfE0z2BpyjjguaImbzEu4sGLjhD5CDqtzX4qKbQI=","T9D8xLXbqaYYsp7SIo4b+4PKkfhlp1cXIaGB2HVvfCQlBu8vunqVr4G4Ff3YGlcJtjDoLPRUY0ShV0MRebD5m0NI4coyGUeQ1UaHGv9IPGRfOlPDICxGlHWCNW9P5uWeIGSnupuBuYpQhy9MG0aaLsWiU11D58piJx1aeXOPR5ua/rrAnq2E8YcT1x0=","U70fRLcQ9ojjGL6fIJ3D1183XaOUqK/ppsE6UCYCpBMNKW/DnuYHFKXQbatJB9V9FW8bhsOkHm8TCkWKnIioQfxWwIQLhU6ahYF/cGfYa7mNitALe3zEkJuQosm3ZaQuvXTwsnynbDJEiohQzO/+c8t4WwE89T9niRrw5CoSlipZ+ooDI+FwPPT9VHI=","XEtLpjgmu7LsE1gtJlygS3r8jKOhKpJRdCJaDDvzFHPHnMNn6+TAvYbeS5T9otcJ9cdtUs8gfCOTYRksxQDbtlmwNDkZTg9fcYY4LxwiqgBLwC2m3dmhxK2gw/E8BoJAaNMy696bYffC+5q/dVoiQps90PtM9XSfI8CGtu142s3X4Ss+bRYtz7MEwmo=","mJStMk2fdjR1tfUtIm5e11fy5c3adZVq5H7IeqPWpvhF9SoAWjNQHMq6J/ImHE03PITXsFNEk6kqAYVA4JtIbcrFfJLx1duKrMGcYywCAEjS1JAk9es1kTKkzqX0qUsxrJQhWyXoA0KGEWLgpKHgPcEuhIufuCI0FwdexZ3PVzTK5kzZdnOr0iijijk="]];
                        }else if($packet_id == 31 || $packet_id == 42 || $packet_id == 33){
                            $lines = 20;
                            if($packet_id == 31 || $packet_id == 33){
                                $betline = $gameData->PlayBet;// * $gameData->MiniBet;
                            }else if($packet_id == 42){
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            }
                            if($packet_id == 42 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                                $slotEvent['slotEvent'] = 'freespin';
                                //$slotSettings->SetGameData($slotSettings->slotId . 'FreeAction',1);
                            }else if($packet_id == 33 && $slotSettings->GetGameData($slotSettings->slotId . 'Respin') > 0){
                                $slotEvent['slotEvent'] = 'respin';                               
                            }else{
                                if($packet_id == 31){
                                    $slotEvent['slotEvent'] = 'bet';
                                }
                                $pur_level = -1;
                                if($gameData->ReelPay > 0){
                                    $pur_level = 0;
                                }
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks  릴배치표 저장
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                                if(isset($gameData->PlayBet)){
                                    $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                    $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lines);
                                }
                                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur_level);
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', $betline);
                                
                                $slotSettings->SetBet(); 
                                $isBuyFreespin = false;
                                $allBet = ($betline /  $this->demon) * $lines;
                                if($pur_level == 0){
                                    $allBet = $allBet * 60;
                                    $isBuyFreespin = true;
                                }       
                                if(isset($slotEvent['slotEvent'])){
                                    $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                                }
                                $_sum = $allBet / 100 * $slotSettings->GetPercent();
                                
                                if(isset($slotEvent['slotEvent'])){
                                    $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent'], $isBuyFreespin);
                                }
                                
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '665' . substr($roundstr, 3, 9);
                                $slotSettings->SetGameData($slotSettings->slotId . 'GamePlaySerialNumber', $roundstr);
                            }

                            $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet);
                            $result_val['EmulatorType'] = $emulatorType;
                            // if($packet_id == 33){
                            //     if(isset($result_val['IsTriggerFG']) && $result_val['IsTriggerFG']==true){
                            //         $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            //     }
                            // }
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
                                /*if(isset($slotEvent['slotEvent']) && $slotEvent['$slotEvent'] == 'respin'){
                                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 3);
                                }*/
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
                                $result_val['Multiple'] = $slotSettings->GetGameData($slotSettings->slotId . 'Multiple');
                                $result_val['GameExtraData'] = "";
                            }else{
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
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
                            //$slotSettings->SetGameData($slotSettings->slotId . 'Respin',1);
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
             //$winType = 'win';
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);

            if($slotEvent == 'freespin' || $slotEvent == 'respin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
                
            }else{
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $winType = 'bonus';
                }
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, $betline * $lines, $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin'));
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

            

            $slotSettings->SetGameData($slotSettings->slotId . 'Multiple',$stack['Multiple']);
            $awardSpinTimes = 0;
            $currentSpinTimes = 0;
            if($slotEvent == 'freespin'){
                $awardSpinTimes = $stack['AwardSpinTimes'];    
                $currentSpinTimes = $stack['CurrentSpinTimes'];    
            }
            foreach($stack['udsOutputWinLine'] as $index => $value){
                if($value['LinePrize'] > 0){
                    $value['LinePrize'] = ($value['LinePrize'] / $originalbet * $betline);
                }
                $stack['udsOutputWinLine'][$index] = $value;
            }
            if($slotEvent != 'freespin' && isset($stack['IsTriggerFG'])){
                $isTriggerFG = $stack['IsTriggerFG'];
            }
            $freespinNum = 0;
            if(isset($stack['FreeSpin']) && count($stack['FreeSpin']) > 0){
                $freespinNum = $stack['FreeSpin'][0];
            }else{
                    if(isset($stack['IsTriggerFG']) && $stack['IsTriggerFG'] == true){
                        $freespinNum = 10;
                    }
            }

            $newRespin = false;

            if($slotEvent != 'freespin'){
                if($stack['IsRespin'] == true){
                    if(isset($stack['IsTriggerFG']) && $stack['IsTriggerFG'] == false){
                        $newRespin = true;
                        $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 1);
                        //$slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 12);
                    }
                }else{
                    $newRespin = false;
                    $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
                    /*if(isset($stack['IsTriggerFG']) && $stack['IsTriggerFG'] == true){
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount',$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1 );
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 1);
                    }*/
                }
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

            // $result_val['Multiple'] = 0;
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
            if($slotEvent == "respin"){
                if(($stack['IsRespin'] == true && $stack['IsTriggerFG'] == true)){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 10);
                }
            }
            if($slotEvent == 'freespin'){                
                $isState = false;
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes && $stack['IsRespin'] == false){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
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
                    $allBet = $allBet * 60;
                }
                $slotSettings->SaveLogReport(json_encode($gamelog), $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
                
            }

            if($slotEvent != 'freespin' && $stack['WinType'] <2){
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                // if($slotEvent == 'respin' && ($stack['IsRespin'] == true && $stack['IsTriggerFG'] == true)){

                // }else{
                //     $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                // }
                
            }
            return $result_val;
        }
        public function parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines){
            $currentTime = $this->getCurrentTime();
            $allBet = ($betline /  $this->demon) * $lines;
            if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                $allBet = $allBet * 60;
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
            
            $proof['fg_rounds']                 = floor($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') / 8);
            $proof['next_s_table']              = $result_val['NextSTable'];
            $proof['extend_feature_by_game']    = [];
            $proof['extend_feature_by_game2']   = [];
            /*foreach($result_val['ExtendFeatureByGame2'] as $item){
                $newItem = [];
                $newItem['name'] = $item['Name'];
                if(isset($item['Value'])){
                    $newItem['value'] = $item['Value'];
                }
                $proof['extend_feature_by_game2'][] = $newItem;
            }*/
            $proof['l_v']                       = "1.2.6";
            $proof['s_v']                       = "5.27.1.0";

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
                /*if(isset($result_val['CurrentSpinTimes'])){
                    $sub_log['sub_no']              = $result_val['CurrentSpinTimes'];
                }*/
                $sub_log['sub_no']              = count($log['detail']['wager']['sub']) + 1;
                if($slotEvent == 'freespin'){
                    $sub_log['game_type']           = 51;
                }else{
                    $sub_log['game_type']           = 30;
                }
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
                $wager['game_id']               = "173";
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = $allBet;
                $wager['play_denom']            = "100";
                $wager['bet_multiple']          = $betline;
                $wager['rng']                   = $result_val['RngData'];
                $wager['multiple']              = $result_val['Multiple'];
                $wager['base_game_win']         = $result_val['TotalWin'];
                $wager['win_over_limit_lock']   = 0;
                $wager['game_type']             = 0;
                $wager['win_type']              = $result_val['WinType'];
                $wager['settle_type']           = 0;
                $wager['wager_type']            = 0;
                $wager['total_win']             = "'" . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . "'";
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

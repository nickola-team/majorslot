<?php 
namespace VanguardLTE\Games\MoneyTreeCQ9
{
    class Server
    {
        public $demon = 10;
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
            $originalbet = 2;
            $slotSettings->SetBet();  
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 14}],"msg": null}');
                    $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetBalance().'],"evt": 1}');                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinAction', 0);
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
                                array_push($denomDefine, $initDenom * $this->demon);
                                array_push($betButtons, $slotSettings->Bet[$k] + 0);
                            }
                            $result_val['DenomDefine'] = $denomDefine;
                            $result_val['BetButton'] = $betButtons;
                            $result_val['DefaultDenomIdx'] = 3;
                            $result_val['MaxBet'] = 2000;
                            $result_val['MaxLine'] = 5;
                            $result_val['WinLimitLock'] = 30000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
                            $result_val['ExtendFeatureByGame'] = null;
                            $result_val['ExtendFeatureByGame2'] = null;
                            $result_val['IsReelPayType'] = false;
                            $result_val['Cobrand'] = null;
                            $result_val['PlayerOrderURL'] = config('app.cq9history') . '/platform/?gametoken=' . auth()->user()->api_token;
                            $result_val['PromotionData'] = null;
                            $result_val['IsShowFreehand'] = false;
                            $result_val['IsAllowFreehand'] = false;
                            $result_val['FeedbackURL'] = null;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Currency'] ="KRW";
                            $result_val['Tag'] = [
                                "g" => "208",
                                "s" => "5.27.1.0",
                                "l" => "2.5.2.44",
                                "si" => "36"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = -1;
                            $result_val['BGStripCount'] = 2;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["KGWRLcgtpbPMhihlrPSH6xIWn1HVKytApsZtSkj/cs2RSImA3DH5G69id3D/4hB9ttQ1PFfA4z8IBUuCuxc5bPRSThzkRfMx0Oa1Sspv8720tRwTxQ9GDCtd+fQv7X5Toru2OivDMTLTFVqYRYlg6+/xQ38IVL6MAcZLT8TBtAA4RM5UPhlA5ll8ZJ0j3AVDn9/dVjwUX5Pe3+ATM/wujnWP/qpS6aOpI99NzHJz70oKWxpNKOP9E7yPHvQjfNXnkh2KyU8gyI2SKZWNMpMrYfhaVMUnznwjf413dOh1XmeT+xeH3plXGDip8ceOEWx6kva5XMW1iQ4Tn4yyI9Ehn2hISPfkX5Xs0YBqCh8fHZsk5H2OZ6AQBlNMGZWIFWky0mC7HF1nmV888GYEb5Zl9l52c+NpjSdZm8l1ySu4ZW4ekXWg3VME2FlZj90hVLMKl5QCRD62JBVbvljJNDq7qunpQsQqg/05qJ43Jg==","60AbWRcxvQlhqJqgYGlFdNKknDl7i1PsHP9o3rZ0qHoBC4DPpTHst1V0jBauTifIm2V1N16ifOyEfKTGWEP7JFy8s9LKunyp5aXXnkf3Q0v3INK08QW7/XCF8BE2bSC3OEwMwDDp2E5VrebwL5LH6G+pirQqsw861iiYpSdgRvkh/6F8OzLDwpTjk7b2I5AwW+mRZv4kF1Yg4pYFQ+0n+Ir5WW671TyLy63BjMWu6/2yQQ0uUdRlwNYxI6JomH+ZpOk3ikb349lnm1cv+NL1tE4BKYes0wtqVSMBLVSMNqAHftmNz27Vfcqa/PCZa6c3z3LI9DaOIkJwvlDR7c2pe8tDgMYwo5hBSRCo7SBNpn2lsu9TOdceGVKfdx23hs69ecNG4vJ097mkisCIb2M6eazpZ+lWqqNYlYQMP/MTPC3yRVJzsBpWhowAvFwQO8hFZaAHw4zt/S7N7YbCsR6pxxCkyCcM/Zf2u5f4bYaT9/SFYJhRBFmFE7SmKRc=","BRagAQZFLlOj7BgC0cf8oqMP97yFm61xgiXNStLrkZDbVThoj5SQATQirELg8aK+e5fLmEIZNDFJuy6fLecANR/0b4IRAquh3bDSBGWGAs0JQQBavStlotPUxG/8+mBMTEMQhZs27ioQ3V8FKWjhseEeKarh1w9c6Jp3M2p/733oDeltENt8y3Uv8wmNardwqsERfNu3YPRMmp5L1Qntr8B6MQk2nHk0ZuDii0nROPDgPP2SIbxqDFfq59hbbpsfetbW9A0eyVOVkacYIkCmKTdZJKShZwK+NvxYvpwuixU5NGHcZ17yIetwHB3e5THF99ZVE7mP6hTZfqPzG53wYWK6T9C5qMOrict0AM9MCqU6Tuh+Nec3oPUbEatK9IwPWpWHzLW5ysRQbAPel4WGI2Kk5m+toGunhTZPbLUoC12RmpfeODU2Mr67XPFDPNp8FBYJlx8o4zCFOxaofLb2o38IRghotEUlGwQ8VA=="],["2h0iVqp6IzGop4v7svbRLBf1pzudmwKbzl2w1BYF2wsqbc3i5+r16CMb9Y4LAqktdE+IeIEseHZyxvmYAWJtaWqd3/gW5liylX8UJlyVVwexJCXj/KlJoTZajsSRWOvATiUz95ShNUOCr2+L6bPyklA532v6JcEbRxzkbIAWmfxp2m45C7mP3ywen/OplcHDFfKI1jche8xAkcgWh0rW2FknaGtC4l3ql5W4EWFXLr106XJO7aaBcsV57uDewHfJlTJdIV3vZYNObCs/75LV2bRhynhLo0TQgnz9yRKm/eovvagL/0cNKDgGtx4sHgLJhu/UPq3EI+KzahSwBuTFDecR9RFTGtFKeDPvScHuoD96xFqHGsGdb2EnAQg=","f1IFy6tHs3GtOkISSF3jnhLRtrxdtS/03eNXrw==","9YNz5Exo8rp9CuRCNQjuyRYDUqRnl+lMYs5SI4U7sMiMEWoY28/FA3R/Y0oEjsE0NPyYxwJcgHDN2Bk3b8pbavB2Ym0Jzmd+FlCrkSvPxkjRRKel0nHFb6hu5LZJCqLCYmmi4j4EB+CY0LO8VQUYrPT64oOqUYBZ3UczwTH4o6GqUV+29gMZ3Y1iJALxU5nDSxhmpXKNdxWA5B8XGdPOBliCoZKBuPk7siSBoSYu4KDSe4OzCMMl7CyUxJZt9eAeIco99y6SUIvBYV1ivs6tkdH/DFqCY/AcxBjyjyVIt7yt4VOUwrk0Iz8eU9pt1t60oqmPSspa3FwOms2fG5JGKQEmuay/taV/orUfGmX6CMRTTc8f2Cj6bupcZia4remxRy4TbIAALVD5rXBW"]];
                            $result_val['FGStripCount'] = 0;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [];
                        }else if($packet_id == 31 || $packet_id == 42 || $packet_id == 33){
                            $lines = 5;
                            if($packet_id == 31 || $packet_id == 33){
                                 $betline = $gameData->PlayBet;// * $gameData->MiniBet;
                            }else if($packet_id == 42){
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            }
                            if($packet_id == 42 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                                $slotEvent['slotEvent'] = 'freespin';
                            }else if($packet_id == 33 && $slotSettings->GetGameData($slotSettings->slotId . 'Respin') > 0){
                                $slotEvent['slotEvent'] = 'respin';                               
                            }else{
                                $slotEvent['slotEvent'] = 'bet';
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
                                }
                                
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', $betline * $this->demon);
                                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lines);
                                $slotSettings->SetBet();    
                                $slotSettings->SetBalance(-1 * (($betline * $this->demon) * $lines), $slotEvent['slotEvent']);
                                $_sum = (($betline * $this->demon) * $lines) / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '571' . substr($roundstr, 3, 9);
                                $slotSettings->SetGameData($slotSettings->slotId . 'GamePlaySerialNumber', $roundstr);
                            }

                            $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet);
                            $result_val['EmulatorType'] = $emulatorType;

                            $slotSettings->SaveGameData();
                        }else if($packet_id == 32 || $packet_id == 41){
                            $result_val['ErrorCode'] = 0;
                            if($packet_id == 41){
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance() - $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'));
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                                $result_val['PlayerBet'] = $betline;
                                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
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
                                //$result_val['Multiple'] = 0;
                                $result_val['GameExtraData'] = "";
                            }else{
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'RespinAction', 0);
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
                        }
                        array_push($result_vals, count($result_vals) + 1);
                        array_push($result_vals, json_encode($result_val));
                    }
                    $val_str = '';
                    $response_packet["err"] = 200;
                    $response_packet["res"] = 2;
                    $response_packet["vals"] = $result_vals;
                    $response_packet["msg"] = null;
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
                        $lines = 5;
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
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, ($betline * $this->demon) * $lines, $lines);
            $winType = $_spinSettings[0];
            if($slotSettings->GetGameData($slotSettings->slotId . 'RespinAction') == 1){
                $winType = 'bonus';
            }
            $_winAvaliableMoney = $_spinSettings[1];
            //$winType = 'bonus';

            if($slotEvent == 'freespin' || $slotEvent == 'respin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
            }else{
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, ($betline * $this->demon) * $lines);
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
                $totalWin = $stack['TotalWin'] * $this->demon;
            }
            if(isset($stack['AccumlateWinAmt']) && $stack['AccumlateWinAmt'] > 0){
                $stack['AccumlateWinAmt'] = ($stack['AccumlateWinAmt'] / $originalbet * $betline) * $this->demon;
            }
            if(isset($stack['AccumlateJPAmt']) && $stack['AccumlateJPAmt'] > 0){
                $stack['AccumlateJPAmt'] = ($stack['AccumlateJPAmt'] / $originalbet * $betline) * $this->demon;
            }
            if(isset($stack['ScatterPayFromBaseGame']) && $stack['ScatterPayFromBaseGame'] > 0){
                $stack['ScatterPayFromBaseGame'] = ($stack['ScatterPayFromBaseGame'] / $originalbet * $betline)* $this->demon;
            }
            $awardSpinTimes = 0;
            $currentSpinTimes = 0;
            if($slotEvent == 'freespin'){
                   
                if(isset($stack['CurrentSpinTimes'])){
                    $awardSpinTimes = $stack['AwardSpinTimes']; 
                    $currentSpinTimes = $stack['CurrentSpinTimes'];   
                }
                
            }
            if(isset($stack['udsOutputWinLine']) && $stack['udsOutputWinLine'] != null){
                foreach($stack['udsOutputWinLine'] as $index => $value){
                    if($value['LinePrize'] > 0){
                        $value['LinePrize'] = ($value['LinePrize'] / $originalbet * $betline);
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
            if(isset($stack['IsRespin']) && $stack['IsRespin'] == true){
                $freespinNum = 1;
            }

            if((isset($stack['IsRespin']) && $stack['IsRespin'] == false) && (isset($stack['NextSTable']) && $stack['NextSTable'] == 1)){
                $slotSettings->SetGameData($slotSettings->slotId . 'RespinAction', 1);
            }else{
                $slotSettings->SetGameData($slotSettings->slotId . 'RespinAction', 0);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
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

            //$result_val['Multiple'] = $stack['Multiple'];
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
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes){
                    if(($stack['AwardRound'] == $stack['CurrentRound']) && ($stack['RetriggerAddSpins'] == 0)){ //unused in this game
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                        $isState = true;
                    }
                } 
            }else if($slotEvent == 'respin' && $slotSettings->GetGameData($slotSettings->slotId . 'FreeAction') > 0){ //unused in this game
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeAction',0);
                $slotEvent = 'freespin';
                $isState = true;
            }else if($newRespin == true){
                $isState = false;
            }

            $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
            if($isState == true){
                $slotSettings->SaveLogReport(json_encode($gamelog), ($betline * $this->demon) * $lines, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
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
            
            $proof['denom_multiple']            = 10000;
            $proof['respin_reels']              = $result_val['RespinReels'];
            $proof['bonus_type']                = $result_val['BonusType'];
            $proof['special_award']             = $result_val['SpecialAward'];
            $proof['special_symbol']            = $result_val['SpecialSymbol'];
            $proof['is_respin']                 = $result_val['IsRespin'];
            if(isset($result_val['FreeSpin'])){
                $proof['fg_times']                  = $result_val['FreeSpin'];
            }
            
            $proof['fg_rounds']                 = floor($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') / 3);
            $proof['next_s_table']              = $result_val['NextSTable'];
            $proof['extend_feature_by_game']    = [];
            $proof['extend_feature_by_game2']   = [];
            // foreach($result_val['ExtendFeatureByGame2'] as $item){
            //     $newItem = [];
            //     $newItem['name'] = $item['Name'];
            //     if(isset($item['Value'])){
            //         $newItem['value'] = $item['Value'];
            //     }else{
            //         $newItem['value'] = null;
            //     }
            //     $proof['extend_feature_by_game2'][] = $newItem;
            // }
            $proof['g_v']                       = "5.27.1.0";
            $proof['l_v']                       = "2.5.2.76";
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
                $bet_action['amount']           = ($betline * $this->demon) * $lines;
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
                $wager['game_id']               = 208;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = ($betline *  $this->demon) * $lines;
                $wager['play_denom']            = 100000;
                $wager['bet_multiple']          = $betline;
                $wager['rng']                   = $result_val['RngData'];
                $wager['multiple']              = $result_val['Multiple'];
                $wager['base_game_win']         = $result_val['TotalWin'] * $this->demon;
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

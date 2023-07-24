<?php 
namespace VanguardLTE\Games\OrientalBeautyCQ9
{
    class Server
    {
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
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 15}],"msg": null}');
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
                                array_push($betButtons, $slotSettings->Bet[$k] + 0);
                            }
                            $result_val['DenomDefine'] = $denomDefine;
                            $result_val['BetButton'] = $betButtons;
                            $result_val['DefaultDenomIdx'] = 3;
                            $result_val['MaxBet'] = 8000;
                            $result_val['MaxLine'] = 1;
                            $result_val['WinLimitLock'] = 300000000;
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
                            $result_val['Tag'] = [
                                "g" => "202",
                                "s" => "5.27.1.0",
                                "l" => "2.5.2.4",
                                "si" => "37"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["G4h9smUi0LS5ADv3rsn/KzHdul0iE7/AjkXZLl/1X47w6TTf1p+F/HlKLEVrmzIN70sdC6E+wKKvHtDg8xmcUdb2bDGjh9PLBUqW3MVa/cXwDN5FhD70W2IlWClo8Bp0d1MyilANANWWheB7VDgRiNEuTRtasaQnFvS78G1JPhQtYBhQe1r2W6XtgpkdsbllY8QFcTMAmmzfbT0FA4SB6P6PyREmA8kfTXvYNw5q7ceV+7JZKmvywWTOkU+Ob85q+TWGgzmieZ66WU5vPqZDMuxXxoP/JWN1ExcosvBkQnviCkVLSaIsjZ1j+YARVZOB7xPUz3WSqX2QxB4lwKAgqC4dWRqYvTAarDySgoRkDa+CksEKAaRMHxt0AaxAdFNC326LDw38wArGMGa+zJ6SAFQdVArFsk2pBkLbFEilkfk41e2zLa/inseA45QhSeQvDrE+cs1+2aax3KaxBV7qk6GlrF8Q57nC18bEaiSPM3oPdCeiP5rwnYg5SuwJKn6ik7V2Y3EAmZnqfpYHfJRKm0GLA47kBQAeZuegtC51d8KKp0v/v9G/M30q5kERpXqcbeeuMulp+ajZn0N5tBPd4836V0rap3KP03Fl2XjwQFheAkD3SZ7eqjfkq9cOD/LaOIytbO4cmPVtvjd/y06+eRtQ7AdvmKtnq1j/MoFUxeLJSFTot2yZ9HsHM46MKa7cBMLr2rkXGbKPVw69CvqbxCNUmF5v1jWjES2GkuLOC4lgCnOxCf4BQJkZv8thzbmpDnLTxvaEcaT6M/N8Xbx+r4K9no4qLAixhQ4/CTVuisX0HBatO83lMRE/PyQ0Oi5UgjxUV3JQGXuef9CNbB7zlfCVrnDFvVh4Hato9tfo8KKz7EdLq7lz4SFYvx8=","OzyMdU9rcTrovP1gLV8SyQ5Zi3s1nRLZRfA1ZA+jUJf4viIu6wDK1g5Ngy7WSc/2LRjgNGr7l1fnAJFjMBOyFYrTCafpAvU1wku1F6do2S8fm13IkZ7dwl9GzVK+ouK0yqeqChQId315bokotpGK5wN8Q6qwh+UZpjqVzdy0CvLG4RRe6qG9dBgrXH7VQkSiPhrUxlY4L1HZ5wlhNaG3mIQn1ZQOkLVA7PY56Rd6LZOnL+RVIPqzC/nrcha+i6TEjX8MQ4UJBs5E5A5Fm6zG9Z4Zigqqf+K31QtNZIkQZ8JtQioyjKGUnJ44356TGeTGaLrp8td4UuqFs8f7U/QChtS4s1ezv5PIij/giJSpMkAlFirUESC5P/iPJWXECf2NalZ50DlmOK0rM6+/KBg3vIw01KG915paJvLfZaZiUHsuyCnKt42tuy8eGO5f9dCKjyoItpvvBzbQgFPMqbal8Eh29XJaYjEmhLGgs4zVkWFvE5tQix8t51PZbl5V1eZurP6lKQ8VoA3dNziP/DEO5mWNuNSZRs/YJWIkCGWi7eVC4PNOUnd4WES6Pfyj+kMTFJ/a3Er+DS5IWLQbynhcpSaVSP1oyz+1fckajmdVlgdmrYiNeM2sAkHSGi6XmNFk8JJvRSqqw9PHLKUkWBWb7l8NgtCDpsLTW6IiSvluz4ECLwQFWgiLh8ZG8hUTp2dvfpuhPOFWbWiXG+G+fJQ79hfkod/cH8OX6t3lWUY89JNd52raD9PfBL8orDLmb/vlwSHvI4nY+oh5KYcodMbtTBnfM31zAAVKMDSCWcSTjmMDmhFgiDG9KyDzm1WssEAZtyJK94q2GqJ6p2EVRPnULLR926z78nUMISv5VwUuQNI1NvbxoUlCMyDRivdheK+bxUAoIHlvjg795YHZ","OUZP04R1vTo9059rJSEzwB3iygbQO1qIU4qlfm44B4Wv2r1aOV+s1YmsNQbEa1Zg7VEC6fXoqDzDp2FS/G+MP2cFtKY2+3x2D5PGpL8PfXD+hLWqVjhh2wqw/6ssTQC0HhqcvcZuf9Q/p3Cb0ka7v3A9/fmefyFaabJjrFImc2AOOKNN7MFJg3amIuRdKyRi1Weufhxn7ee9nrp1Fvg8pDvVuawMeqB74roTua0kZkphkylfJwL166TeFgdGvYt4O7rHV1E8ZbzuXbpN/R5aB8F5szsQdKdxHocFd5tzmeQXE8JXS36utQvkLfy5wrUQM4U2edgqhol0LtM2iHvY1Ga73ASUINIfRzGSOBjciyX8ADxng5Slm0UGkLDdAvkR61Brlk0e8CU9SZXt74XZAuZNl0wCMCM3rW1qz1HdVYVAnJT/rXu/BJLST+TtyH+YsNYsoqG13JtogshcJgiyKOKcuR1ESZEf8uNZWZH1pyTZ+ym1VlBmjUAyGT2Nfb8U/Xbs+YVgVkNc+9hLHJ/D6p+xle+WfRZsZo5GQ6NiiM+pmYsQLEhh9X6lBXkkx5lj8aAWIkb5UCmG8xP1p7XZ9bisZwK+wEe70cyg3t2EaR+ALUFcdVLEjJVV4gu80g1swrorWtc5AArU490lNEizRdUvACxXPGAdS5hMILQqSZUXV08XklS4e5wJ+KtPaRsvK0LsEPbvuJcRom5VhOXI0r+NjYLdrbMNx1K0jmbgDU9KHDO4VqX4xALAcE/GTeCysJQ9/WHZMXdCUmETKwSeEYtvB0eaYbwggy09yV55jvkzVSSbkh9u2XP2kpMiTZshkJoB+hmobEM8tmv/9PqsOzQbJW5eMVdlDGRlDg==","T5c4F7dDgjPdLieNTvT82Tx4U+JyX02Peoe7POUGdHXLH2mrA4oUiUhvzx6acKGPQGw61imGcbSkXTdKvGzQI1EnbPNrR+1frGNnlViEb9tG9YI0opYe6Eej7F/tsZ7g5Y6j5ZiE3QYGPbCUm0EeI4FoMiElSJbw1o95Qd5Gu7pHalUh6A+JcH+/0O/wVWFLQb+0JJw9zIWSZeoeHiQ8k8rxCTXmGzYGDi677RDaEW4uez1rP5njMor/shylYnYgu8IAZr74ovDW5SdToGQI1p4r4RdG2u+hH9aivo3jiZEWdnd2YPDR7czcQWr12SfVEQym7jQaosbI1TL9r8Efl3dOhLGGijdrSagoWOp0kCkbWjbEao3B+t+YnSB4/HzueXIfTumhLWSD1QE/tBFJDh6HnjOvpCvvHyBv4LNNvN5wo1yyLsfMRksEQtsYvoWUHfgOn8rjVwFBDRpqAgrdlwM0xCszid8/Meng5195U+bk1ZyYf5gCIjUInhKr5t+DMSQEQch1HVydpTbvdZ/hCaQxMT4UUz9Oyzp/1XeG+I6bNZYDxwjQR56w8zztSgF872ChZvIp/UCissCzxf1TEcyYknTldmAlpUKEU++hOGs+VXI9tLRZb6AlH2M2+4/FHrsafjmYfSvlDE2IAG6yVF84Q76wyioj8Scl8AGYy7fVAGcZgyvAVn2QCMEtHEzCZH3MrQYPp7WQlDYEN7mKl5uFOINTzDkp7l+q0el5UNrC2/csCbKTWAiUXI1bOV9KOIe8MiznGmlqIYuZdx2Qok7d2R+xp3svhWhfSFcQjS5GafNJhBUz6jv3VHjUBXjkESZtnGiq2B5q0zqX99Glz9zH5xS3ncbnMVZwNtg5vOgBUkDpz+Ix0HPWOlRkfEayi9LSfgtA51MIRaTN","mYPMzZqJpnVLAZAxAvJGlIitxIYXcpeb1j9g7lr5zPNaaYSiQxc9b23JQ+lfzvWwW654OF7SWJr1UxtXzSjkzY0yePK7eqlIKB7Sd1/6xXh9Fdk+Oxgi/Jr0xyUEKc4djwUSqEM9nqY7RoNHSjO+1z0UsaGcAbChfrc5MuKCBuqfx/ql3strVlzMX3WfFLuGdw6N64Uj0N/f/bc+Mavz3QVUn+FgivcrEN9O8NR2taHTx6hXAVg1oC/EnAMFe0ymOJ5IzfDDhzJcnwnH7SLXRYWdpV0rPFT2+3GdL7wwygkR/+NC1YtB0B9jRVWAXgxAZuDho+KgB9vg9cSDOAomtujRrnS2O/U6urUikkTRh44IMc3REvze1xk15FxAIVQwYs6EENBEO4rl8AACTUwbre95a79J/adXJtCASLIWL+wdf59DWUbFYXYFNQyetV8SUb6f9Z+wLSXzC4T1PjnZWItkMFvSy+AbVrkvrinuEKcL/J8TW4AJ9Pw16Cf2WafCEiZL9PtUdmbaoTrzeG+w70rcDBj7K4ps6PibA6YGCiNeVoK/xJrVhhuZ9yyeMnC854iYzA0jkSac3eFolz8uq3UxJWxuvvJcoBZArRahtwb9x7o8AbR3knRqGJECmGL2jucpO3VclpTt7E/Kn9Ovx7z0Gt2SaKEdqZZHRpiuLI0zzjH3L6ZcR/jamXKAQzL3xPobyHT2nCAsvgEnVrri7ruAJo0VZA7xN/nUMs8xPqcYYjMtU1hwfraXfwBBCAJNg4G0ZMwqlAfI9eZ70UZTP53dIFQMxJWbm+S0K1mDoFRSn+bVJ9I2/Dhi0GGsfS66v8zL68ISVFY39kZnfo1v38jf7TE30SOaOakt5w=="]];
                            $result_val['FGStripCount'] = 1;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
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
                                $slotEvent['slotEvent'] = 'bet';
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks  릴배치표 저장
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                                $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', $betline);
                                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lines);
                                $slotSettings->SetBet();    
                                $slotSettings->SetBalance(-1 * ($betline * $lines), $slotEvent['slotEvent']);
                                $_sum = ($betline * $lines) / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '656' . substr($roundstr, 3, 9);
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
                                $result_val['AccumlateWinAmt'] = ($stack['AccumlateWinAmt'] / $originalbet * $betline);
                                $result_val['AccumlateJPAmt'] = 0;
                                $result_val['ScatterPayFromBaseGame'] = ($stack['ScatterPayFromBaseGame'] / $originalbet * $betline);
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
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, $betline * $lines, $lines);
            $winType = $_spinSettings[0];
            $_winAvaliableMoney = $_spinSettings[1];
            // $winType = 'win';
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);

            if($slotEvent == 'freespin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
                
            }else{
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, $betline * $lines);
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
            }
            $stack['Type'] = $result_val['Type'];
            $stack['ID'] = $result_val['ID'];
            $stack['Version'] = $result_val['Version'];
            $stack['ErrorCode'] = $result_val['ErrorCode'];
            $result_val = $stack;
            
            if($totalWin > 0){
                $slotSettings->SetBalance($totalWin);
                $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
            }

            $result_val['Multiple'] = "1";
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
            if($slotEvent == 'freespin'){                
                $isState = false;
                $result_val['Multiple'] = "'". $currentSpinTimes . "'";
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $isState = true;
                }
            }
            

            $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
            if($isState == true){
                $slotSettings->SaveLogReport(json_encode($gamelog), $betline * $lines, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
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
            $proof['fg_rounds']                 = floor($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame'));
            $proof['next_s_table']              = $result_val['NextSTable'];
            $proof['extend_feature_by_game']    = [];
            $proof['extend_feature_by_game2']   = [];

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
                $bet_action['amount']           = $betline * $lines;
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
                $wager['game_id']               = 202;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = $betline * $lines;
                $wager['play_denom']            = 100;
                $wager['bet_multiple']          = $betline;
                $wager['rng']                   = $result_val['RngData'];
                $wager['multiple']              = $result_val['Multiple'];
                $wager['base_game_win']         = $result_val['TotalWin'];
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

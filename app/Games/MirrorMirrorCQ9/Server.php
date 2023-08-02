<?php 
namespace VanguardLTE\Games\MirrorMirrorCQ9
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
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 10}],"msg": null}');
                    $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetBalance().'],"evt": 1}');                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());

                    $slotSettings->SetGameData($slotSettings->slotId . 'Multiple',1);


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
                            $result_val['MaxBet'] = 2000;
                            $result_val['MaxLine'] = 1;
                            $result_val['WinLimitLock'] = 300000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
                            $result_val['ExtendFeatureByGame'] = null;
                            $result_val['ExtendFeatureByGame2'] = [["name"=>"FeatureMinBet","value"=>"1200"]];
                            $result_val['IsReelPayType'] = true;
                            $result_val['Cobrand'] = null;
                            $result_val['PlayerOrderURL'] = config('app.cq9history') . '/platform/?gametoken=' . auth()->user()->api_token;
                            $result_val['PromotionData'] = null;
                            $result_val['IsShowFreehand'] = false;
                            $result_val['IsAllowFreehand'] = false;
                            $result_val['FeedbackURL'] = null;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['FreeSpinLeftTimesInfoList'] = null;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = [
                                "g" => "228",
                                "s" => "5.27.1.0",
                                "l" => "1.0.16",
                                "si" => "34"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["c3mE2EdURZrtBPTlYEsBHD6y7vFKnXjKJaxWdWOh60Cf5E0gUAIDYBNPfeQmJMgOSXptsFfplpKc9KNh1VXME2j7uQJT+YolBM8AUG3OmadvERdOawYUZfKdMHsCyZ1F0lS63Toi5IOHL9HUGDwa22daG0e2EtjEesLASWFgDZbSoet7fIY0r1JDhz7faXQtuS62cJw5TtEavJtVUYBuiY8CKySJh7MkZl5NLASKavP728TZQ3RbSreGUxzZ1t4A6RY8LFkY96e4S47ryCk8UL8e2u4gIgBXnHmOaYkWuTRO8CR+2Vk2fJybqffYn/xlGmAbtNxbJKrovVMwSPFmOeFUhmoxeyH2YwgdaGsPkO5WNDt4UOZGLd5P4Fgm6q3FJ7zDl8MOMlbbfvHERlUtIU4Eisc/Ytw0egXHZr9ArkBCtSNBmdoxis9VMjtItpm7Hj2QNhp4KdOra0XLGMrFqlda7yqBC8LyShVqvCbN+WwpYpt5KQtaCaMnpOGwi30uoLhCWQeDCDgvs7I9oG/cc1RtpC6gNoyJ72vubc2Yf5shlwzz4hyPuKVOzgs=","eKTrx9VDVrIsbkz2Krc5KbA2XSp/bZIakzLRfBSVhIRsSxEzx33ex8M03eCKrMsdqyYb63rhMP+JbDRfLzetCQkxFuou4GYE3RtRNeU+Frjey7Xd+qEcDu88RNRbDfIXDSD90Roh6S3txAXjnClvZn5nsrX6HdBIirpw4PvILACELaj216vll3R3brKHTX/k3G9GBGYUCa1J3IfhICa/tsht9txCbymva+Z2q21isWxWz56v2wGyUU4uVywaY+uQiWB6iagSdgKdZVrgAGuBOchwWqywTrTQjpWmL5o+S7ywAXFRoyw7AGhO69Mb6A+RZHuuvp/T289muMhuvQ+1Nudfj3RUDY0JpFkLLORSCZbVZrtwT+DTFA9aZyPOslV9bWv6H85gqYO337m4fSlWKgAhhU7HexhWxZ0T+emm5/tD7G9pK7J+CsknLNg0kdsubhRN7jlaMigZHZLzkEWQ7O6PGZFhDCIGvu7gV3Sa3jHPxinMr0W9wA8UeijKYQuv8Egi8cQ28ZbF1mNFdBESrO4lIWk5dLOwnBcAkQF3phC3NQbwwZLHNbzFvaCnEg5KdtrNGYIcdh+EestXflrlgNWB6/gq9eOvbzaTwLROzMB+GMWgsLFvRnIlClypJMJ5po3abb7Zw1gsB16y","ZomxUfd6LJTVLF0N8woUuJlXd+KPc580lyMxjeoR9T1KZ0b0cahnIT8xP4Z3JlpBFzXj94RJPmzGE9SPn8vMDykfBT9+h6K3xVGz2mln+J77l2pPR/YW3ulw1/bv5TthPDu/b9jbpbTQ0zMYQYGwg1EQhpswbUVCRScdhJhKrTxYx3hIGL27A4b4uPY1Rm6IWUv2CjqfIxsqralRmsouBthwVuA2pNGHwi7da/HX0dhcW+NXOrlsCa4dzARVy+fgWm3OzhgqjChRPDyhjxjxwYAXZOpm5X7HVHKqFkxrr3WkjA3x3f5Q8vKUSDv+HgErqecVsGOKZDTfo9sMJJOQhS3wrhMwzH7kw9gOVrbHBO6btQcIFYE+L+0SznAwOiNsVAalVWISZzextZPtAvDOMfLuhQypmmWhJWgX4ezmZeWUhl5c59mGNJtS8Ocz5i6yP5mnStSvhLKVSQlU2fMfYuhbzrmUp3pxgIGmEL32KAtGahqmpb4hRdzWiLueyKr6LXJcQrgnZcg4ekN86NL/kMkReAlZg5loLeppRqULs/W93o2dh5gNscsTj6Os24x+jAnjCWFh6QXBDTAebzRq5hDYv5obxChbWngGAYBK9bXUvlKxknnOctROLWuF/+KMp3VwmG3HGN2V/sAJaR8LYYMoMY8FTZQAsACdRA==","7JKASTcPHYuYH646d4UpFYL4WdpFtJ3glv2ZVzu+PTZvj0fpxcBebcHEVTOmMNTFf7OUDO/J1NGiNngvUYklpHKEXHTiXIq1ZwCfUF8iHnS4rfmK06tyrXNVs1LEsT5QH5CuM9jtzHReKtn47D98AmSZ3A65fVqyYkfNsWPAU9eLz8bXjh944eCFFVPlQ8mIJ/+WldpM/o2ewq5/3NeOewJzfoAU7nR6B91vdHeoTU2ewkDd/3YQ9OvbXng0SRhwpBaZoIhP0WiSUW0u2BfHHSQlbD2WLU6rEXPMCqSGDX03s70SpjKuv2FP28R+HIjLQI0R6NAQIUo+Det2bFmlEvjyt3B+KD5b5BYD+q9KCbbrDmizeXbZ0/D074Dkm7rq6TKUX5ZV919knGQgFa84+id6qkF2MWMlXGmfe57wLyv5oT0k8xB83M/bmaRUtFttuTXu3j7xVgrK9G/iIyUYy1wAIK77JtMVailXQ0pi7diEges3lAZoRliGCXqe8reCJXjnwaJ+HT4cbQcIfEKcXeAID/9IW52H1EKqMFwTrmwM+n1bm9aQmLaHuCpEVv4yE2rQxZQZCvJlZfsk66FdUL84LP073wTOqWzTi4IH+6cds27gwwGDnUmIt04=","vMZ8Ig6nj58n2WnYAxPNG4f5gxFEkRu08f/p7JgQKHziOGUjQsarx0/it5zzX8T+u4tnmehl4qTTqVDTxe+02P0ZbUU+CX2Kk2OXo7t2z4hUzkJiKba3W7kDRkQbNF8M9vz3+xsuZGC7Kgjn/MyLLkV6jUTXZWmJDZ5v/8wtNXi6XVbYHp+kYlvkJOy4BLKziyMl1hcs6h6b9ncdMqNdFp8Q2MJCC5A8FckgHv805d+U+BHlAUN7hJBYVVW0jj6D6sjTdrbl9woziNK04PxAJ0W/WO30Bo4N1zUds/lE6kW9mMZAkTA27SG/Fp8Ir2S/ZD8SUdmP+u9mWC5SU9cFYjLJgxk4ws0K3swjWoQbS/IAmtkijSulfFQwYLZVODRwbvhpZLPlVdTnuUO7PPmS0NzuXXf0WzcF0QgE9fKz9UhHZn8aGxFif6td693tTOvHLJtSmfGq4v7AzIvX39jShK5y468ig5pXjn0U3VDn+GNljOQ5klsRZa16y0RKrE4v1yHAbqGgOrTP+75CE7Lj/2ROFkU5XtlJCu+jLUZlZA+2XvtiKZsgW1GbY0I="]];
                            $result_val['FGStripCount'] = 1;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["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"]];
                        }else if($packet_id == 31 || $packet_id == 42 || $packet_id == 33){
                            $lines = 20;
                            if($packet_id == 31 || $packet_id == 33){
                                $betline = $gameData->PlayBet;// * $gameData->MiniBet;
                            }else if($packet_id == 42){
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            }
                            if($packet_id == 42 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                                $slotEvent['slotEvent'] = 'freespin';
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeAction',1);
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
                                $roundstr = '568' . substr($roundstr, 3, 9);
                                $slotSettings->SetGameData($slotSettings->slotId . 'GamePlaySerialNumber', $roundstr);
                            }

                            $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet);
                            $result_val['EmulatorType'] = $emulatorType;
                            /*if($packet_id == 33){
                                if(isset($result_val['IsTriggerFG']) && $result_val['IsTriggerFG']==true){
                                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                                }
                            }*/
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
                            $slotSettings->SetGameData($slotSettings->slotId . 'Respin',1);
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
                        $lines = 20;
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
            }

            $newRespin = false;
            if($stack['IsRespin'] == true){
                $newRespin = true;
                $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 1);
            }else{
                $newRespin = false;
                $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
                /*if(isset($stack['IsTriggerFG']) && $stack['IsTriggerFG'] == true){
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount',$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1 );
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 1);
                }*/
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
            if($slotEvent == 'freespin'){                
                $isState = false;
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes && $newRespin == false){
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
                    $allBet = $allBet * 60;
                }
                $slotSettings->SaveLogReport(json_encode($gamelog), $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
                
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
            foreach($result_val['ExtendFeatureByGame2'] as $item){
                $newItem = [];
                $newItem['name'] = $item['Name'];
                if(isset($item['Value'])){
                    $newItem['value'] = $item['Value'];
                }
                $proof['extend_feature_by_game2'][] = $newItem;
            }
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
                $wager['game_id']               = "228";
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = "'" . $allBet. "'";
                $wager['play_denom']            = "100";
                $wager['bet_multiple']          = "'" . $betline . "'";
                $wager['rng']                   = $result_val['RngData'];
                $wager['multiple']              = $result_val['Multiple'];
                $wager['base_game_win']         = "'" . $result_val['TotalWin'] . "'";
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

<?php 
namespace VanguardLTE\Games\FootballBootsCQ9
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
            $originalbet = 5;
            $roundId = 0;
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
                            $result_val['MaxBet'] = 1200;
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
                            $result_val['FeedbackURL'] = '/feedback/?token=' . auth()->user()->api_token;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = [
                                "g" => "95",
                                "s" => "5.27.1.0",
                                "l" => "2.4.1.0",
                                "si" => "32"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = -1;
                            $result_val['BGStripCount'] = 3;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["slHS0R49kfNN3zrWUwvQfbp2qffpV1Vs35Jq+YgNEGVrJydKai5xlrWxxl+cqGmkcQNLalmaVyAB4cI7366gd8xPqm7Cmmxl0JMYHC/xOs1UkhcqmN2ZYUEQA19z3B6JRKK6qKzoyQvihb44toa5i1T5EoDNggGZRHkcQ+1be2pmdjhDs6p/0aaVHJWVNsL5tZ1pRtDxzj4pO7TMT66ninJfmhDSYUYG6mD1VzD+sZdRqnoTegzdo5b06hzdWFOH99KSztouCsLY2e8Pv0e+oOJUJTqyVmhmxeHMm2G/TnvznUlzkrD4VnHTHUf/xIvefRezJzSsoff+eg4eJCeVKBzktp/T8W4XpZY7og==","NwKNmO1VN9612StgJdAq1zUTAFl6ccY/hUcuhHg/2yqCGj2ke0LQHe17A3J8DtFhg41VMPXvvTeaiO/afaMFTiwYeU2t3AeHqeSoqdDj1OQN3HlnUcaso+VAnRx0LX/mjUROaTQMoM8hutX1jFAZNRitPZn8bD2slsMFfcnk3LjS+7mQjZAnxWfSbZ+lB9Ifj/Irtl3bFWa0RCcLMd4hFH+2VEChb6Bss3wERqrpi2EUJGMfI+AJygl4SSftodHPqbAceMaZmOnRcbwonEH4ou2NcEuPBdli0oI9CikDYZx6t7nBFqCb1QMAySHIVRFiSXAUd2Z5hw5Ie2GdknvpUSe8GJnKfwGmZHnvXUZXSOAkwwtMpxjb/4xM7Ac+cE/kvbtRGKEHf12p/uouueGLHy/0WikBV/0Rzbd3WA==","LF8lTF5U3lBUBn8zFPsA45QbWaCBkdZzpgX/ajyzqN4hTYiAj9OHQuWowAqFbIMmdch/2hzaRG8oxw67TNDgAxFsmM61089SM1R51Sduoztu50+nxIYh5N28USDBjcHvLLyhJDQGzoQeD3JeDpwaJPppcr45avAKCdzd5k85z8982/rNRpL4gOVve1JMVIa6EHBhFvB7LZOZgAyKzuDZssaDF+C4aUxmmJUKOCEEbFZp6p4+xyria89m81J8OBfAsSG5pdK8uG/k3leorY8m6TNd1OhPWgHxjly0nNMM77iu7UG1y0V8W7H7kAIR+E31dY4BvRnlYSvOd6RZqPCDt6CnEmCkpAyEcAPhelRH3ZNH0skcxn2iDyaAqhbqFwrYvXY8EuuHBMXQBjLqupRGlRiwEdef5Ijg5myxq+mMwpJi59RjMg3fjSSlZookSpqQ8OcZYvnymfT8wc1ZNiJ0YTfBrbmz3CejbqB1upvr6FEkXmMHu5K0GhwW4JbvoN3+4gIBMNsrydJEDn0K"],["F0ic0uN4JuP2wesJZ6OTvygqfIUuYRXM7hMEV41kSVAEEomkCdDkGhEh9jjPqj0z2brKk/hAVMfL/v/pccMDT2XnymEBmrZfGqdr63bWS6UqS9USR8V0Ryf6Ttj+YgoRixgrOJGXy6I/MOevgiDxlyx8bB6MEcQy3hvJ4DhaIit01C2R2jEuqjwDNDudER0HzyeD5aLpIDMqDcvT0J0O0rOcYCV8MByRBfaIIWcnUI3OS2Gkl+uAN+4+M0+UOLJUGsbEvRXjAemrG7QlNmQbBZcFRYUcQryVf28fWYs/7mdBYI9hntysdoPRQWa66Peq/QovCwOtnRCV24e5CNgK0LbzOdjBQkDxeAS5wg==","3eyfV4Pk5swADQ9sEjhfI/EsDx2TTydUv4ndoYppvaQhr5kjLz2Wg7Sdd+1I5owbGk5jWNY2DqqnjxSAOvEXugUgX/wUNbsOrrJ0uXTmteK/YqsLf2XiCzK0x3DQYozP2s+F+OsfAKwrPwXg2hSFyuKwyuXm1kR3Qk44kNvNsGM7vYOEOYCBrBCoErd9HKCNjacSgZq+MQ7RKyUwb+ybGIFiX4WW6vFLMkxxdq9asV5ZRIB835ZB68AnhyKnBwGmRHBaJmCs4jTJRhrWpsEWe4arlmrHXuLL/W1+p5oln40EikDuIgeGta04vmhbgD4n4so4kcJ93sDJuJmj07nILjRuk/vREWySMU/gQEwJcTXesY5+wHTb/r1YZ2LM6kex/TNskGcnq0EEAyFneKkKKyDmkLQ+itkiNK5lIg==","YrG5tR0HqaRucDwpSl2idNj6TNVmgwT3mMe6AbR96FE/s7V97U8Y78+mPwILI6+qnsce1WDeR3IFeup31ztpfpbpmZy/oaP8V6/M3jdbgR5rPUiy+KFYven3NM7jhMQv+gFU1fVf/BM4ErbArTV1JmWPvxF7c/amOOwIHhqZoUQznzj+2tSLPQnT0e/DPnOxiDmI1ks9KBWpGnMrbX0AyQF/GX4u7vOevrZ1adRWJrQo5gej3CJooBKwvQpHkS9Fhe5ikB2xvjyAekd8gtC5/7GqCPYjPXrvqwUXFoon2j1OVcZGuS2YlaUqTOex7fqOC6OuUlV5VS6b1gWzDGq5mdfNWo16J0lMtiAHV3HMNtjnaj+nFPUoYDqzr2yjnoTJvo0zEbTBoeWYCt3FUzWlh4t67dQ3qQ2JSQ8U4NGtyYMc1TTOCMEuX/8AUHmkUmeEL8PqflX5C/pIeT9g2ZXlX/gZFUssyY2f6WPfXApclFY3kQ9KC0QZpwGxoi9y+910CtsOK4O8zHnkESot"],["haiQM3PLkJWL3mFbR/vFA0i53wSxFgXlxdCq7L72M30K5L2YK6TLnLZ+np5a1HXV80vH9JOiEtlWaxbycanU7igbwnTGxiTFehMhxm7tpczS1c3UkgfWqexpaBWzWuDxRsQwJ6RP95DxHTUEMvBrqErZw31y5zMLm//ICPNtDPOJDMnwsMbLCX91Nk10VW550UwieiIwmS/AWy9pmL9brXbSTVb9Yv9XtghfEodaYquvMHFDL27nJxnZNlPDuyVHdNAdmyB+SianwHo+ZFrelO5AOGCjJ+cHOpSmUJJMN72Sv0VV5B5+u3GtRVAY5Y4MPapK5kTSLniark3wbKB6p0OK21PK+X9ocFL8JZx4RMbS7kNsqcQYVbI1zt19PFlHg9hZI1RAoWSty81g","pnYlrnTV2CljFv3kDXT4C8HZz9+7a+FiOqQ/z7/1GW0cbESlSQDKL8iQvciqvWq4pObFdx173eBF7cgkCzs4J4CXpGEZWDW7msJn9ryXsl/vNbpooUKGtMw6giLyb3zRn/Gha9LI1OtxEhstkOHp/rQpNw7u6faxXbMjg3rH0/dvDksxeUrLiebIW57yqrRu2ExJ9HFSp9ypNupdq4qn9+goqRb+vgN6gjca8lfFPsNyLHNaKfTxKJ++3MjOL4ZXbD3h5bXT1cMWY5ubcQp5F8Xp2CyWWG+fWaPA4cUgi1gzd05Gs1dO7Lb7RTkCIVDBkNYK5+zzUrrnx54hbg6joIJNmNun7tPe4UnHZ8K9TLVs/LvTvkYeNJZ1odXSUoYMqi1CtKZVkDp4VZmYWnYSRXYuBUSBTvRUzMJzjg==","H0ueVjnwEGLkwi3Su3bGxTYzWMqokeDAhcXEYi1+cICB0AJl5zBa76WIblW8eyMysf6Cil/tFWksyeIJx8AGv25xSaUHoGb9fGFfzFY79EITiPzNN9oSmJiL4XUJcVDehZRjR74RTivcqbVyV8FlPg/QM+MGhjrGDqTcY4bID/fY6Q3aqObY6qF/7CxRs5YVuxZV1ybp21B12ts88/+c+EDlTWKoOOLCtbPNBrgVBcF3UCbYeDo9sC9aSl9sPgw6tUjL6h0yCNLTVnR1rLNmFNK6Hv+0LhYky7pWJwEwAs9pDRoz+rp1b2J4JktF6/St9nZhDzs70FG3ARaF3eyZXPU/KE6wqUFbN8dcB0pHVC+Oyn3S7IppWnHZmXPJkVI5FjBZIE0yuOZQSnhY0SRj5xcF59Kryvj0Oqszrc5k8CLCaKGiAYO+gkLIMONIdyimn4WqCZh7i7p4rIcYPXaKcRHgv4sewFIDs+4Lyw=="]];
                            $result_val['FGStripCount'] = 0;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [];
                        }else if($packet_id == 31 || $packet_id == 42){
                            if($packet_id == 31){
                                 $betline = $gameData->PlayBet;// * $gameData->MiniBet;
                                 $lines = $gameData->PlayLine;
                            }else if($packet_id == 42){
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                                $lines = 1;
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
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', $betline * $this->demon);
                                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lines * $gameData->MiniBet);

                                $slotSettings->SetBet();    
                                $slotSettings->SetBalance(-1 * ($betline * $this->demon * $lines * $gameData->MiniBet), $slotEvent['slotEvent']);
                                $_sum = ($betline * $this->demon * $lines * $gameData->MiniBet) / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '660' . substr($roundstr, 3, 9);
                                $slotSettings->SetGameData($slotSettings->slotId . 'GamePlaySerialNumber', $roundstr);
                            }
                            
                            $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline* $this->demon, $lines, $originalbet);
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
                                $result_val['AccumlateWinAmt'] = ($result_val['AccumlateWinAmt'] / 38) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
                                $result_val['AccumlateJPAmt'] = 0;
                                $result_val['ScatterPayFromBaseGame'] = ($stack['ScatterPayFromBaseGame'] / $originalbet * $betline);
                                
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
                            $result_val['TotalWinAmt'] = ($stack['TotalWinAmt'] / $originalbet * $betline);
                            //$result_val['TotalWinAmt'] = ($result_val['TotalWinAmt'] / 38) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
                            $result_val['ScatterPayFromBaseGame'] = ($stack['ScatterPayFromBaseGame'] / $originalbet * $betline);
                            //$result_val['ScatterPayFromBaseGame'] = ($result_val['ScatterPayFromBaseGame'] / 38) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
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
                        $lines = 1;
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
                            $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline* $this->demon, $lines, $originalbet);
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
            $roundId = 0;
            if($slotSettings->GetGameData($slotSettings->slotId . 'MiniBet') == 1){
                $roundId = 0;
            }else if($slotSettings->GetGameData($slotSettings->slotId . 'MiniBet') == 2){
                $roundId = 1;
            }else if($slotSettings->GetGameData($slotSettings->slotId . 'MiniBet') == 3){
                $roundId = 2;
            }
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, $betline * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet'), $lines);
            $winType = $_spinSettings[0];
            $_winAvaliableMoney = $_spinSettings[1];
             //$winType = 'bonus';
             if($winType == 'bonus'){
                $winType = 'win';
             }
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);
            // $originalbet = 38 / $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
            // $originalbet = round($originalbet,2);
            if($slotEvent == 'freespin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
            }else{
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, $betline * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet'),$roundId);
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
                $stack['BaseWin'] = (($stack['BaseWin'] / $originalbet * $betline) / $this->demon);
                //$stack['BaseWin'] = ($stack['BaseWin'] / 38) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
            }
            $totalWin = 0;
            if(isset($stack['TotalWin']) && $stack['TotalWin'] > 0){
                $stack['TotalWin'] = (($stack['TotalWin'] / $originalbet * $betline) / $this->demon);
                //$stack['TotalWin'] = floor(($stack['TotalWin'] / 38) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet'));
                $totalWin = ($stack['TotalWin'] * $this->demon);
            }
            if(isset($stack['AccumlateWinAmt']) && $stack['AccumlateWinAmt'] > 0){
                $stack['AccumlateWinAmt'] = (($stack['AccumlateWinAmt'] / $originalbet * $betline));
                //$stack['AccumlateWinAmt'] = ($stack['AccumlateWinAmt'] / 38) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
            }
            if(isset($stack['AccumlateJPAmt']) && $stack['AccumlateJPAmt'] > 0){
                $stack['AccumlateJPAmt'] = (($stack['AccumlateJPAmt'] / $originalbet * $betline ));
                //$stack['AccumlateJPAmt'] = ($stack['AccumlateJPAmt'] / 38) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
            }
            if(isset($stack['ScatterPayFromBaseGame']) && $stack['ScatterPayFromBaseGame'] > 0){
                $stack['ScatterPayFromBaseGame'] = (($stack['ScatterPayFromBaseGame'] / $originalbet * $betline ));
                //$stack['ScatterPayFromBaseGame'] = ($stack['ScatterPayFromBaseGame'] / 38) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
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
                        $value['LinePrize'] = (($value['LinePrize'] / $originalbet * $betline) / $this->demon);
                        //$value['LinePrize'] = ($value['LinePrize'] / 38) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
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
                $slotSettings->SetBalance($totalWin);
                if($winType == 'bonus'){
                    $slotSettings->SetBank('bonus', -1 * $totalWin);   
                }else{
                    $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
                }
                //$slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + ($totalWin));
            }

            //$result_val['Multiple'] = "1";
            $result_val['Multiple'] = $stack['Multiple'];
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
                //$result_val['Multiple'] = "'". $currentSpinTimes . "'";
                //$result_val['Multiple'] = "3";
                $result_val['Multiple'] = $stack['Multiple'];
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $isState = true;
                }
            }
            

            $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
            if($isState == true){
                $slotSettings->SaveLogReport(json_encode($gamelog), ($betline) * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet'), $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
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
                $lineData['line_prize']         = round($outWinLine['LinePrize'],2);
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
                $log['detail']['wager']['total_win']    = floor($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'));

                $proof['lock_position']         = $result_val['LockPos'];

                $sub_log = [];
                $sub_log['sub_no']              = $result_val['CurrentSpinTimes'];
                $sub_log['game_type']           = 50;
                $sub_log['rng']                 = $result_val['RngData'];
                $sub_log['multiple']            = $result_val['Multiple'];
                $sub_log['win']                 = round($result_val['TotalWin'],2);
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
                $bet_action['amount']           = round(($betline) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet'),2);
                $bet_action['eventtime']        = $currentTime;
                array_push($log['actionlist'], $bet_action);
                $win_action = [];
                $win_action['action']           = 'win';
                $win_action['amount']           = floor($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'));
                $win_action['eventtime']        = $currentTime;
                array_push($log['actionlist'], $win_action);

                $wager= [];
                $wager['seq_no']                = $result_val['GamePlaySerialNumber'];
                $wager['order_time']            = $currentTime;
                $wager['end_time']              = $currentTime;
                $wager['user_id']               = $slotSettings->playerId;
                $wager['game_id']               = 95;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = ($betline) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
                $wager['play_denom']            = 1000;
                $wager['bet_multiple']          = ($betline / $this->demon);
                $wager['rng']                   = $result_val['RngData'];
                $wager['multiple']              = $result_val['Multiple'];
                $wager['base_game_win']         = round($result_val['TotalWin'] * $this->demon,2);
                $wager['win_over_limit_lock']   = 0;
                $wager['game_type']             = 0;
                $wager['win_type']              = $result_val['WinType'];
                $wager['settle_type']           = 0;
                $wager['wager_type']            = 0;
                $wager['total_win']             = round($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'),2);
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

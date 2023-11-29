<?php 
namespace VanguardLTE\Games\SoSweetCQ9
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
            $originalbet = 3;
            $slotSettings->SetBet();  
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 5}],"msg": null}');
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
                            $result_val['MaxBet'] = 40000;
                            $result_val['MaxLine'] = 1;
                            $result_val['WinLimitLock'] = 3000000000;
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
                                "g" => "8",
                                "s" => "5.27.1.0",
                                "l" => "2.5.2.4",
                                "si" => "39"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["AUGi0SvaWbqo8p51fz5XPbKH8egbWA03hp6l6yKnzzfKduLC3qHnXgRqPRzFrt9o+eJ4MlOlPEokZHGu+hsF23gJw6wkmz16pJpunqnHV56si0byemN+ii1b2jfbvthAkSjHzm4yOhakGV5xgXG0Pb4EtE7wh0Pvm/tNRAvRFN7j5o3oYlka2XKDMpYA9iiiPLO3x+yff2yKO75Nr/KcZig/SuRlFDxEoa1xlmmgWo89lMBdzRhVryV/r+BATcMM7t+IpcoyqsQthpcpx7Fg+tpk/3b0kL3MEMqpBICQqc0sTxY6nk9hZJMyeeu1gY3mPqQsKc77+RNha7t72SoMwqnYS+U88sqLYhtiKSI37g7X04b0pWdlqycv0qmJRtW5EqwcXQmi7mSbTO/Eqgz1MxwWRsusxyrdz//UcSQM7TnsNuGoZvWd+A/bZsJ47Wuw+QfCTuBm39IFWzI0nLgwLRcqqNYywWp8aerIUCNUxOZLLst6mgaT97mGBxNSVIiLLuTMoS0Iqi8rg0dVqoaCM3MUYIwMtJRya9mWdxaHZpWLrIQWV0ZJA8UUXxPLnGaIdUKYMf/KZ/kL1O4ZzzvjoY67fVfXRDvDK0XEygg8zWGNWcXbeAQERe6Bjg5uNRhgaDWAH3HQq1V/fuNvle020mCj55Wx5lH3QGldFVAUvMptofVEmIzg+ni+ZvtFnZ6RTjS/kpNjLgzWstEeONsgCT49iuw+ZFur984qI+yPysNPOjYS2CJHZECx2VCtszQwc7M0KF8OSxKi1dY6iFi5MXvl+VYDubBah3sbY8zCywo5+VVjGLuiDib6YCYcZycTJKpoQ64LniqkjFshfVjArS2y2i6txPUfga03t857gXi2AV4OFKjUY7Zc9a0=","hDfTjbgUVop9xfjxGzv7uUj7bm8UBJ+gDiJLQKfEDhJzMJzhuhUbvE/9WOvZvqhgZH5oIOTnU5lXNd96IfWTyLvuWsNXkK2S0wMEXIS8owG1mRB4WpZo3oVTo1HsNGgs1HOSip0ghTWAY8X7LETCwBPvkFtt+3OrVv+NF+La7ZoYaut+Et+xryix9W0TC9aryxDUyE+beZFwu/9S1Ua9flyym9MKa2B9Hxx7A6/mlaPe250OK97RFCG4ZBvcFZwMemFFhafCnW4uW9CuZ5pS0Kyvh0nFXenW/uZQx9dp8K+MteCpb40MJBl0wUJec3q0Cfz5QAGWgHBXW7ijdeQOpfRQTgVvDQrS+i1F/yaRNOZBBuoQ6GaTPt3CyDayXqjVk+lQQ1XBaj3uE5RuXeMETIr+NY/YQ5lKugg8imu9FzVe52tcjo3DTLuidFvfglk5Go3FMmvFKmg6f3aju9izZRGMdM2My3zohX30r3AuDi+ONvMJJoh1YJZtZULwPMslgkGKa6R9c8f9hL9hDm68FTv1LgUU8NMF/5EoamPkBwg0Nl0Cz9B1b6+yl3xDI2ZDLYCU0zFW1XE1XP8hsD7mIGW+H/3w2kNOUuORNcopRxnkCLJSQIl8IGHeP25UuLRGxz9k6znG/RgC9GkNqIpaMdWIzAsRwmzblf1ZEtustZcOSEjqAxZj0M/FxbwGL02gIa9hzlmln1T53SToPAWjfxdhJBQod6OaN2q/6EsQfqcro8nnAFUEXcNNgeQaBqufBQ1aQSZswapYCLDtNp3BAvbOfaaiIdKJ0wla1j6YJZxH56mzAgCM0UAHQhpEgl1wFzhEF5EVxFPfREOsSZ9NOKuE0ryAVA8uAHPz5VVaXGxjYFTEj88SnLAWp9uhqpxAAn3b4+ilWH9kKhFR","Mhn7AFyTm9WL1CNgc6F2OXSfkASaY90JMrOeDAYdGOLJwWbqSRVkpEo/BgCc3MzeIH7NuIeNM/0WRqKrG5I+hrDz4vPHhS/P0ASuaCrDvzO0lFOnxMqe18FRKgfXb5xML3sNTOkN0KyWQwVLQXhcGgxs5vEK//nb+w34buktb15DiHde3gMFmxRs7m2vhXTAFxovVbGlGe5ynIXOe9XO7BAhrfxIreNxy31DsiN3Nmc8gOYmNEE18c3yf4ASEnz5DGGnXqPu9OIKPRUGuks4JH2xNpo/7SCAdf3xoVSGUxGpACHFB3OUFx9C9LihXZKws4ZSyFHjf8CL6DNvGGUcoapxctVrQDBTI/BXW5S46cI01LZQ22Wy+CBU7+p9lf4tqTbeS6SGz5+xSbSv9/HYpGRw/mabmLj30At7F0Zajq2s5fo26Y18WR4X1jpcKREPxEsAIHRZI18l1XL65NqapwqEmccEEovv9sMuWglIQYmqRJowjgXz1cRvZtpMlME+5Ys+1PEm7yFALUHxCEIQ+fOkkrWtswXCfydR07YVMfwjicVK33Aymsmv6AD7kK4/aue1pwx7LL3oy+ceNhD36K+GVaZapiTq4jB+xvlTYxzFG1KwtSCp0Bg/FeHjp0DeupTnuvnsDLINYj6ZUCadNKZk8KN6TuDsKdlt8DAg4YB+rmhn2T7kAnzlV9EoVR8pOTuR7bjiEeY0cruw2fQxHUVpYD88pyEjhAruSp3wxutKMy8UIzaGofqEBpW3+EE82gt7Td13mVoj3IJ0chBPfFvLGUFx0PxdVtEvMvVpYozlYJ0qQPDlISJ12e248I1xMfS2j1bzDQZGqJP/IaetctY+VLxKUx1N9qWpow==","emI6DsGZtPBUL1ZCSAfBk1d9CuP2uM/OhpUg5/7fAl6xAe619S9vwpvA9J2Q15UORaMy80N2t7wczFDhs74rAg0qoo186/ksI1IfuBpgLhNQsr8cQkzqj7k9SdYt17FV0GMTijwuvDblZhgXteIx+Tzym6Da8PA9+iTPr95ionmbYoZvNhvYYbCqroYK4ycOJWSZ15+SRhg2S7yQLXMGroOa974wbdxT6bn5py/YubUoXktRzv0jWjXEAPkSU7GyexVXzOP2Pwmmg9gJsWNuWvDNqSQiTJCslLFthj44s49XqfcYgmKrvNAIUKlG1jfd7Idjq5o+QOy4uUBu3J3G34LsUQM0+kGL3ub1t1DsWDfr3wEw3eSdpXOaNe3bhaEquS+bc6yKL0snKP8q0SolgXQSgu0Vjk++efOUpLG0Q5g7V2sTpXnkBDHgP0LnByJGy1BxC5yb6HJxdvkLEA5DOnfpMLHpnJ59K8+haniN9SO3+okyuks9lhWLr0rDox9nXIeyzDFzqSvVjKdy0xACbhgBCMVWSyugK/rzjit4/9moXJgEiP+dl/5pXdT1JJlfWNF6I3VhxsPeUx1pKWS/A6CURzKA4Wkc3Zsp5EsbqE5seoZLcAtg2AxlMiE7PuAuWRuhhuT8ewgA9ZZbLbEmbzS8eJnqxLPbwk3zvWrpHWZUp4DrwaicxawyqjBBIyWBvgvcFN2jQU3BOR1pAnjXTb+OkbNHn/CJ81GD8k7C95NKUTvmJm+DvGblyqIyeiwnnZEcbthHOVCu5K1bL6vvE4gp23+2GcbQ9dnFmyytjiT7TlW0J3bC+chLU6JaFwnyazWTE8w+0wiGmmL+VkB632RKuesjOUw06qCYAmLssnEU6wUDdk4oKS2P0V6QqoL3+it6O0OtDamMdEYp","ebOnKZq64cT9w3c1u6DcF59e8HCRh1aycLTbfXZekCaQwzsrwJMVl7K4SPxRNiCG/VKuGUZ/7haVXLv8yTZ/RHDAUSrtDNYRjXt91/jK3MiIvo1EvLbyP9CZWtlCS1TRqqt/muLpXJ4tjB0JYgikQ8CRixitcm3A+Ccj+Hj/N9LVC02aVsB3gTzBhIFxzCCkuVAON2PXbASnRHm/Li4pF3b/hL4ioz3PhZ4Z9VJq2Ss0Z3BqOlKHdHr8iY7ThEBZeMaEQnGigzWmlARyHxMd3j6Ou3BVnm1Plaf8EUZg1de3J/AP0LEHvFDhCBQ4AeUjE86gCH6wpGLEm5BRgfgGU9WbHoGu49ozLGhXSrV8di0T5GMxjz5xtJb2gVFbeSmlW41LC9gzgDS6gYY972vOhPwhxOIGlkePW7MN84C6xattZeXs/K6JKNWTAoDonEKqkPaP68kjlUR8cQ7XjyaaHGLc0K5p56Shn/jpvKlQryu5fwXOr3boYR4j488XSSzohip1DKtejm28AqznJ3K6iKRk0t9RpHaFKH8ZYlDXebm4DPwXwq4s8LFOSGz4RECtHUKzpY65AwYxFktT3WAa5G3mESCdBsx2QX4u9C3+97dtCLFFLIghwm8898RfWRf3eubj3HeKbS0cpaFqOGBd4ha2+q8oo7OIb3XSW3hwliv5LH5RCjr5MQniAeMsNbbX1eGKXSNuuYrWutJbALV7L9eM3xYENjUCg0G2RG5raKcjJeNzME0w3rkDG54/yuyotxe/IO6YRPHUiPd/4nM69uO+ks64iKR2a1cXXwCHUy7UgIpDlQRQEqysRMh7LRxnWHvKbY5rjunUK44uK2daFAJkUPs5Jf3wqq8uNQ=="]];
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
                                $roundstr = '672' . substr($roundstr, 3, 9);
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
                                $result_val['Multiple'] = 1;
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
                    $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                        $lines = 25;
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
            // $winType = 'bonus';
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
                if(isset($stack['CurrentSpinTimes'])){
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
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes && ($stack['RetriggerAddRound'] == 0 && $stack['RetriggerAddSpins'] == 0)){
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
                $wager['game_id']               = 8;
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

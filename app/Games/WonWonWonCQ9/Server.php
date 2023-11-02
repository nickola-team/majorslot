<?php 
namespace VanguardLTE\Games\WonWonWonCQ9
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
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 4}],"msg": null}');
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
                            $result_val['MaxBet'] = 10000;
                            $result_val['MaxLine'] = 1;
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
                            $result_val['Tag'] = [
                                "g" => "76",
                                "s" => "5.27.1.0",
                                "l" => "2.4.1.0",
                                "si" => "58"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = -1;
                            $result_val['BGStripCount'] = 3;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["SvPPgLRmVUzHSg5klxfjrRHU0UwSYEgiVrWXTarq+d1uDyFuGUhuIjGO5PnBdR2enxNN3uYE3JCdRTwwyDobPRbq5LyoTpcCa0T3nZT9+OLNFPWxHWnM4CQgHPAlHTQG/cDGRnhCdt7W4+bo4qB4ccySv1sRxowGsmQolUzJwCejcDQm1OJdQ8cxUvUD76kGKYbn3AitKkkMMJX5wOVLye0hLmpdy34im5xUIjPxY56nEDDMLs+uB4v/ezRJ1gS8G01dS1FPm3CO2vEapo5hCiAvZTAgQaZClWporOwEZ+Fgwjpq3K8v02OsJd7znnPo6++ksR771LTNXQCHYzmK/umL2HB501aI6MfyUQ==","75y15Xc66m8NqMiiZ5yVnHTvpbzYRP3nxXB/aUbTQI3XuriOCu6aKMPr//r4D58QGiBsO2lxizdYcPC19oYa6tGkPbj5qL4mQwR/3f5Ipq3SdnXkUgtNxx0ek7Y2qRiOVEfnh1PF7b4w8YIMicFxlRZBYtVt15jSLYZObjNh1u6AWeip9830+0+1qpod29yeymW0Gjrq5EEArMbTzYLYf4txZujYrjnFRZg4u/MksEijX3A11iaZjAwe0vVkNZggDi631AxkY6cFnRFAnLk/syJ0V2xHyENAobzWYGLz6ZPHNqx+gn4zFzdyLzm1tw77ZDPcftXICbc8cRtTugZ9KhC5JOC3zEA1rbV9zsPcpi4UGUDxWzotTCK47EIB/zk8IH/IqtpN8019uW0n1HXAdsScpwrlauq5BeZBSw==","wQ2jhEafnsasBeXrtAZOSlrb3BTzRLDdJpi7kbjlGtpOnsW6Ao0ENc+AhSOncC4SLlEbXRAY7CPFP4yI0Ij+2hSI5uSe+uv9nI8ZFmVEsWaoYKHkrdEHhtAl03uXwqKvPWz3IcmAOxYNz7Sl7HEd3O19vK5yx9y5XBBpEPZKpwaiAMHp2jcvwNzMucvRf90SnR+pOrXIMjD2I7uFd5sQdBYzFe28kQsLTTsF2c7t/JGxBsxYWJHzz7Ny5XiTL+dMYt42oDpI1kIdf9Hdqs735WKwnSl9AoFO+OaL+BMRWLPta6tWkhpJ6dJZa5FpQKv2sNdER8hH4B2h8WB4OjsNt2gq3UlqISOV/J6mV+jb7emxEDFRDcN1bqLBguuytQVpdboV8qQLWQrudgataiGu2AAMQZEhrtTWocZ2Rav4rb7d6LK4x6voLrnRHSJypoRKC59ogmG18ACL7svXLw5+LbfnrvjSSAOrGqtBKvDpql+jZ3nzxr7BPZF5wCcVqkMx2cmii/qXg3MvgbhT"],["p69EgrOb8jI1OXv0EHIuQbPVhbJzZTFHzWnZim87Wj7N2tML3lk5hm/XY5CT8xX75RGlAXmljfleOuWqHq3rXT37UkY18+AJSTc2PLpFfPtiOFihij0+aSYNwgbcJ71tfcZojFb8Il7w6JNQ8ZrC3isggxtPUmX3AoxtPWuM6PtFoTyURN9k21Fi2KGuOYYL8FV6v79aMFZrKQ03in7j9lzK21xCdOcHO2kGB05UI494QJe6KoCn/NRhZWP9QXUocln6/AsCQSJ4SHV1jBrSXJ1HJB1kuH9ts5y6B8y3wR1R8i48o02HjU3jXejuXtC+Z4N4FtbyEU/cP+psp9pTOEPSOkIwlvznV9XDsA==","uUGUxC2Qf9DdSjYvE6+70kWC4Qe2mpfx92zRuLzkUSJW1dTrWewIwjzZyyYMjgaOelvup4pqDgHEpM15rrPUlqDFDLY8MNH3hm+wS35lvnWCYabVg1uH6TmB1mc9FBtelJ1OkndBRTtmADLs7FJ6BSSK+u8E0oSJE3gyezFhvcXZE6NzsdYFHhu6kmBzHAbO9nj4xz6n3opJAI/mYdG3/ZuhEqroatw2CRxNbCYmJMfGnUS59P//DJ8zhgDUo0RlS1B61IgEqpaZqvqR/xT3sZAHYgdOSvus+mbOAONAZ2IBCPrxlk3ZpqwBRSj940datsEsWWMyvNd2JM0GRUAuo4kD5T9/cP0wOfA4UH6L3mOijO42reEUeEgRW+AKnAS5xKWnYearKmx6ATxau4J2cbC7TZctpBRjk3cgCg==","sL13jXO5rmdiv7PtQaFBRY9ygIOM0hz556dME2u9iRICmRtq5HqeXvQg4YLchkM04pR5LSaqWF1292DJXELl/2sSdvh/gUMbz/uIFvq6uDMxTwxYWyG8CczK5t7uCrq4CCnc2/nTA0KCZx6k7biX03sDzI77IUaHPt8n0PkjI5kgnOltZk7NNx03BzG+Kpsp6XMghRhkMDkOavuD8ay1vdrU6O3Up4d5fqpsX6XScVqZZYnJf8HBtWhZOB9uGUODrl84f32WDTSKS/fUSdmOqMrxTgJs2nLMIoybKDiVjQ6HKIYhhw+X2/QlgFKZz4xijOv5BRiXWWfq4Uur4GSNajduFllrSsV4jkqU/MFqSGkkbzAtaYkEYWuOW0OfK8kvyiHFRmgQM97gEdLvoHMf2IStF48KMcJBpcARLpK/42m0bm6qGp7AmGD+kK/E/1eTteRQ8Ersfm2qRTNzdJDbUti8EhWm4dTopkDwlXZSFnin5ZFLysUO1p+2FaKsbeqZTTFqHtgbvY+2KZAk"],["ubxihORFuoGACRV6ThP+K6zR+7r02H2cTCXKT2pLpR3Ku5kz420q+t/rAFzFjA/t9mrWfJqS3ix6KPl+LV6f9tuPYU7Uur3W1zNM0/OK9sZq4QV1lqXKASu6q1sgKFHuGdudcTMGmHTtsQ8SbonksfMuCn5hOI3qYpOEAdhZjQquV/jBIV/lQ7gSYlLRNYAUX6vZs6OEhN210QoCETvdpAR7ImdcJcJcak496mL9cW1moerozjK/bmTNRVigKS2LJXJvA0XsC7ViAxel67TCfWlvIFhwdvp38yEWj9bh9DAoCMwQWNLB+ufViiJ7d4OTPlw5tdQMxJcTU1whp9QOVEpaKmF2DnCfgDrlwu3RGOomoZ7kN+1cWZBBf4162srlWltoRFYnGru716j4","CXldexFyDoxiEVDSxmZhuNH9DtVPKhPg3Eg6aM9HTk/vzkSJjRIgs/lCg5Hr8BfsklqlmLytQVc0QDzX3O1/7eAbAOZEDXzUAA4osmZe9Qk7DayeNOCbKhJQD+WA9oeUjCjjuxxEURnu+u34mYX2jGs8Hzxe/xWVl7OVzejl5QT+BTfkzQGI9e7GVoZ6d8o2+CjzOuqs9O1ae5bOGsJire3fqDdS9ejY6YJ2I5fGlC0HX2WJBe7zKzXFf1oTmvTrPcK4uFyvDqve+Ap2EOlO5tqa97I7rsjT6dBGmnEVXkcXFy8ku2jLga5xMBtVLTwhtofjovobmFcD/w7Ubyb1usvCkaPa2DWdbtbqKTycyv9P2Qry2HJDoq6FJTQRor+NBQoOxv4gQfbvtnwohEwyoGbqj+9wBKC1fPYg0Q==","awAV9cORWxsHHcopqdxmalhzM1jgIyPoBrUe/m3+V6vxVxcpmoyBRsAjeBGTm/Gmkscd/3iXndYGTWKmWhmzD/6gwLLxVJX1Oe0cfVB03+0o5btUkNCd6uzXN6q2zS2hAKua6UjZk8iB1IwAnu25rr277ldXgQOXMTarhezxD97GyQybmJwdzAI3tI57/h/mCxqhAbqpQIhTI5znHeT1OP2hKH+qzm1ISlGiAaxtEMHw3oP1xTej6meYN6q/+3kUMw22vWApMSHtaDSJR+9FCITBlleWBTPC2pwzqjOtOGikeZVBP5peMOF7rs9BJZA7VYx8DXuJ2i/mrPyxg8UYr1Pq6jV0GiNTiZcwRORW8GXBMimUi29E0aiMBGMwGJjvOUQtm7cFR3KsjkQU1FGOWpaGtI8cesUGb5X7aB+Xqya3W4E4Drdv8D61Ea/4Pfa/5sz0OrOG1XaqxIz8dD9YMjn3J9+JnA7QkA6/Kw=="]];
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
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', $betline * $gameData->MiniBet);
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
                            // if($type == 3){

                            //     $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance() - $slotSettings->GetGameData($slotSettings->slotId . 'ScatterWin'));
                            // }
                            if($packet_id == 41){
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance() - $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'));
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                                $result_val['PlayerBet'] = $betline;
                                // $originalbet = 38 / $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
                                // $originalbet = round($originalbet,2);
                                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                                $result_val['AccumlateWinAmt'] = ($stack['AccumlateWinAmt'] / $originalbet * $betline);
                                $result_val['AccumlateWinAmt'] = ($result_val['AccumlateWinAmt'] / 38) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
                                $result_val['AccumlateJPAmt'] = 0;
                                $result_val['ScatterPayFromBaseGame'] = ($stack['ScatterPayFromBaseGame'] / $originalbet * $betline);
                                //$result_val['ScatterPayFromBaseGame'] = ($result_val['ScatterPayFromBaseGame'] / 38) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
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
                            // $originalbet = 38 / $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
                            // $originalbet = round($originalbet,2);
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
                $wager['game_id']               = 76;
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

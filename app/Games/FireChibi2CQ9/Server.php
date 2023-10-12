<?php 
namespace VanguardLTE\Games\FireChibi2CQ9
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
            $originalbet = 1;
            $slotSettings->SetBet();  
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 13}],"msg": null}');
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
                            $result_val['PromotionData'] = $slotSettings->getPromotionData();
                            $result_val['IsShowFreehand'] = false;
                            $result_val['IsAllowFreehand'] = false;
                            $result_val['FeedbackURL'] = '/feedback/?token=' . auth()->user()->api_token;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['FreeTicketList'] = null;
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['FreeSpinLeftTimesInfoList'] = null;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["A8swRIeoTbYCpYzJFRh+S3ZjCvKyS+WHTVewB+oEsnEoAWtNoLTAr14gx7mavbGYin/8UVOFhKzXhx1MjVDxUvMrtXzxXv4E04lmqA0lRPCn5eyJgjppBQPlbaEal8ScR6h5FoyyPu7Zw07NdB9SWZqIorbX04UKLb6aMX+BZ1vyhNecEVj7ScKbdpeX40EJ956Lcx8IEAtJfxlKruzpfB5m+cwRrkgfd83+fvdmXkmBsjYoVp4TTR0OfDX9mhsq8xKAeVuciXB5ER8Oq3Ok2xG5hZgKfi+eVNBgljq/fvv621MpQIP41HG//eZyJMxAsDigoJn21d/iT5HcfecBlsEm1Pssl2kv9dsTOjUjmsoftxlDqNvXEhDbEHnviXJkwxZvUmJxH+9SEGuiaRspWcFBO0t8g4QDP2dXHvJJXw46kQvnTfluv71gph7yr7y8mqlOP92pHnGV+TvkYJIK0t43MuPVY+0FLakr25tnBi21cCGZKF+Li7vKgn+Tb1LPfuNDBjj2ipbezdYt8hNcJbk2PIhlOE0yfeATayrmwnN4cjI+UINpqGW4yZyFe091xz4pL3jlvBQrY5Ui","xeLfZYYgNFd3m9Eh8QAPq5zEag4siIH64dyqUkxh0T9uDhTbxiPz6r4WGVZL4vRvhf7z8btftitLSuvuZvnbQMrLVTdiEspwLxJ84rniY4ffZsBejqPe1tFFqJ1pDKWE1VPgqeidaocfIAb7OwdkZ3sOgI08GbJ9Luil467QPyvdQPQWXaf7C+VGodUghcrL5gOMhE3hQXCkoe+SSK42jl5Ys5ooulYu5Y/sAIkULHbsjK0DcPJuczhmtFi/c1YFjCgUxIRXFaF3Iq3swqpQlisziuHXFZHZDnBj47rrZqmwUrGG0fckU15byPoQwKso61ZYXYRBZvsixe6BB+yInMO40/5JA/Vk9uWxJKArvjRtB4+/sdJKJNX3tczqkWSM6Sgt0QFIUt8kd1gy47zTnHX+cvqTqlAfqyl3X3ldJc97X+N12MhJM1AVA0RqicOfxcYIhNoknSVWwJsRQju9agUYTeFnD5jz3twbkyo3VTbwdi/KVdFSB4FjDYI=","xweJaC2uFe9VpR80SZkmmVRstMO5IrlyA6oRLePm/qjQUzuIdz8AEOPR6evLXOy/yK8nCHVaIjq8ysDP8CQ0LUUwsyDsIjqusHaxI4W9gRBsdrSqFXkJL4bU+6ZTPORN0sd9z8KFGSfBo63imbefT27i7xXJN6YdjrHkXxh/RQ7NUUvnAXlETM3ZBiczHlWV8wg01FQePXt9PH5mNh4tkKXoaR3VldA8WtWEmfrzdqgZINWdZHWp/oFlsS2sBhxxur+2Zz96dl4x80n4w6nDtcQBnJ21kZa2VuyEhB4BE1UeAoQBkoCgNm3KD1X3gy45D3R8Y8v/BI7uHM3nVtwaQG/bxwJPG46bFqfF0fEVsZsVX6ICWpCXXZ6uHQ3loQqlLGhE839NsmQYDWeRogSGKVrlBYa0GtdAZ5xEqr87egW9AV/EsnJsOcUg9PhSlChTIGPdvIlowdWjSoxr","FlF2j991APUgsKTQbjl2c7ve8Rx5RCuPc2rlFyFdwMHmrQFEX+e5qFDLwEACt/AEmkCvkFSPf9cWFu+rcL9QP/wUazCHxSLSlQZCLyZh14NwkydUkw5q5GXKJB1lAoOPn51OLOAPePdRjr5/mVdTZQQv3IfBdXx1TA6UP315KXny4qZoS3egTCuuW1ddgDJlhRoIQ7Zui2+wyuyJ+t7MKRnAy/Fs2kTSFWlJbuBQTDo8jkyT4+j84wh+AZolF7BV8FofGG7rYbA6FA8PPEKHe5BuXczW8NWDvUUA45EnEkS+UzkVCII4rMc+8Zr8qw3XUe8Fc2IycGB8q6vK","C9tHdoAREMTVp6BiQ6O5vK6tUrLQGCIfEaPVLOTG3zzXGisRrdyIofjFgeLt3ojwbQVKDSQXt4gzT3LfyolIvOgL1H69b6eTX0VuPW6q/w8Fp9V6UguAXtpTEz6qkgyTJCSzbyabxA78E4e0AbeZ4phmwN95y1FAiPCtU0LfFDFh3Dzpsj7FNCUx5tVm+W4zkgbRdfF/zRcxXwVTeWgeEztcdlU+f+gs432yJVCXEy0w1UTN0+nWrG2x3aevU+xr+6NL9I9m1/KcJ3twKhsTyXo3rWR2kFDo7LzwcWOTVdPE/rji5cbtbLmM0S/RpN8VSHBDQuw3KjwqFbbPM2yVZu4RTeB9eqMq+37Mg+QjNC18KGHdgGdf/bttYcporXJMpuQPan9niCMGdk/4wpRZ9ciQ0Qau+q98qOecaJNwztzYvtsKMVvc1yNDWjVmdESuMvicaxyunpXjTpuqNLOaAATkuQX/O6JFWRsuNcq5rwZs/tGSl1c09ufXQD9rMjgOiWTcrOFSA2koHeqwfBYER8T1+f5+//zyKolI5fucdN2TQP/piJzG6HLZ4HEZWPFJmpxxc72tD8SXjSrqUpjHCsMVwFOTVpwRazGKxhljy6yfHloNv+LcgKxe9mag+LYbRG9jDnpVNL1p3ZrrP8OUvbEIldwXudp00abpSfvPWBLbf3scXSzK11vEWl4="]];
                            $result_val['FGStripCount'] = 1;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["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"]];
                        }else if($packet_id == 31 || $packet_id == 42){
                            $lines = 50;
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
                                $roundstr = '657' . substr($roundstr, 3, 9);
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
                        $lines = 50;
                    
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
                $slotSettings->SetBalance($totalWin);
                if($winType == 'bonus'){
                    $slotSettings->SetBank('bonus', -1 * $totalWin);   
                }else{
                    $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
                }
                //$slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + ($totalWin));
            }

            $result_val['Multiple'] = 0;
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
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes){
                    if(($stack['AwardRound'] == $stack['CurrentRound']) && ($stack['RetriggerAddSpins'] == 0)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                        $isState = true;
                    }
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
            $proof['fg_rounds']                 = floor($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') / 8);
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
                // $bet_action['amount']           = $betline;
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
                $wager['game_id']               = 140;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                //$wager['play_bet']              = $betline;
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

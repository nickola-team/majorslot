<?php 
namespace VanguardLTE\Games\FloatingMarketCQ9
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
            $originalbet = 2;
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
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
                            $result_val['MaxBet'] = 1250;
                            $result_val['MaxLine'] = 1;
                            $result_val['WinLimitLock'] = 300000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
                            $result_val['ExtendFeatureByGame'] = null;
                            $result_val['ExtendFeatureByGame2'] = [["name"=>"FeatureMinBet","value"=>"3200"]];
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
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 3;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["OX7JnVxQvtWBJtG612vKKxCc9ncLd0BWIIy2YDBzOHC+A7PGS8AtNCqj/G9BHiASGkHYqEMHdYbWsUCMl5E2Gi0IiJJMu2+pJ9iiTGGPvn2RNb0UJZnUI2G6cbBEJKmf/akoJ9mQ4tS0ZjfbQilYPVFgUiANkA1kND2Kstrhxru81luDxf0hIoPxnXa2geY9xlWldEe2xLI4AcK+vqRwgur9tjw4a11cfGBLPdiEF8MBLp1Vtzn2qhGq13Vdy/8zzI6wnCc+rOJTc31tAYzFRRDkbesD85tv1+8yij8vXhxZb1VCKeqDMpgKKq3mIkJJRAwRlgDwzectvaWmZi9r1CwxvPF2sqIS9hIRTe9K1osiD0mVJA8KzQ1WDZ/63bEGvMIPeZhUrPVDKztKrr538M5l2eDTYWFuzO7eDeQ16UetyA5DKP0ydu+4TCMjkMG8QILfpSHEOIqEswW9GHTfGT2jS2hXxNsxYtJnQJ7rJH9mSHpa5l58F2260V0M4Zj5WbIHEYoxnsE1zjT3l1a8SmuuabIeOlfJIyf6fXvamvJ+FCJUaFQvJarmkP1CWcwd0sWonkaFDOhNakTlcOxlOsXkS45ehHF2GG8egbP7jSFQOCntFRqrHIvCikZQrp4pi1cEYP47PqEYWBsv","vTznLUI4xAqEmkHzBjiC7f88XQ7aV/RJm8b8wpIxZYKebyQZvGxxjJVZUuvgQ7wTTF+BLz4NVyaViS6l4z1IsLCSKtV9zYhpgbGdbVygc+XjLVdgTJWog+tQoPQBEJvSCZHjEAwssz0Zd82pA8EEBhLQ/xnz3sSwXSwpNlkYTXxaM9Igpev0VpQHWfPbMqGNx10Le2IupoKIN/uZSv67wI87wuZdEhhKKqziy78+DKpiZ7QcXGPRS3vMM8XKuu23ZFq2tI62kZlV+gbAY8rB29c1aI2wQzde/XF7eJyZOIH0Ue36qYCxJog/Ss0UZZggiUNstgnt5tQ5BUZRbRA9Cwyfo8wahseD6nYnhNrN3EI0ZeilKItwvJooeNsfJGlh8eXpiqVtdHTCYtYSsEIAka68jMBKIaVtLsGAQaertYy7/Ekc9dBXYJQ2+TB3E8XSdszo9Hhrj6aqwlJ27z/GC4zxF6lcNflWCTdDouA706AIYVOVm274UvNffyn3nU2JdZLE8Ll6GfElG7ym1gFQvJnu/W9MruhyY/CPKaDYoc/DPRTjhuzEsJJB/gr3Cy156YPkrkleFTOdKwj7oEwW8+BEdxrGP0MEHWNDHcLoS7CKv3GOEUQMOJcBlbWye0KMa9BdAIMsuN5Z2Lry","28H00Za663QU12BKlXurxsgx7WqeuTKF5OGPFa43E7ZwjnxTdNH6RLaS2f56irCtA6eN6e37OgpvTs5OBSD6VfaqXjjl6VLAeQQJgVEuuPRUD9szPzUFZOQYTt5yhQ3cKizpi2AF7l7F+M+rGDIoTe/JrA9fPbEP+SEVDDWj8Ym1fIZGvwxY9bD5GxvCWvZMFd+BBr5HaYIBln+K0XatTWOsPAp7E3yiMLg7i5OTcwW764ekr7nx8RHYaZiJGLPr7udyUE5jXd7fPjgypETpai+4dqVXU+4RXVYAAGayCS8esIr5aPlAR44UBdUaH8XnRU52cqcVyc5GUVsrmK4WrCKN1DsAe5HYFAKfOGWiEKaTs7LC2jJp7hbwMfnWf6aiR/XWuJ+hLkrb9toezDw7xC6ounglZ/pLbcTYP1QlQXUY3xYy9zQ3VPhzDNiVDmmkir5He9J+lyYWDSOy+aS+nmzPBxW/WP13bSIguIUeq2ZBaHufrTZJzmWC+ofadfz7Gw2FISpjlKoSiq/WDfvx1n6IMs5sdvD4sFX8s+I6YRXmHzW5uraE/6CqfseTyzPt99fmDpjML7dBymwIR8M9uLMGUhuZYpElmG2X0b69Q+g3yGTQmbPiKcEkMVug+GOvZz/zLE5wET6o8Z+m","WC3qYcwZuiqPGJK1Frm97bUQg/zqTyg5X5Xtpaf6qu/UT886QpVCQZNNmVFPMWmHK5P6xd6KnG02lGWJITqw5STWP8s22dUHQt/nzC/mZp98WH9PQVGAJ+Jn0ptj7csxkXJuNCNlJR2s8lryTLpEVZ1+qf6vWG7XFqt+exhw8+nTdjRhccLBkFZWPXv9/5Q0HZD/b1jA/DotIuVtkRWKyeGXp8lIKDxBbVJzgKcl6Wwu04PDAN39V8wDp2oLEaZGBc00MR22r5qjN3troCFmjSgSMj200RtKYdLN2WPaBXdRSaL1EKQC+fYh5du/krMCgr7RS4T38o9UPMbFQUyBY1xAgQ/s6L7NDUz8B+ENNtROjljBWJt71bLXx9J4nb6lmGtTBp7D5c24kCwqmPWqgxrgLqJL1RHydLw7UqAYiXR2QjQ2x6pySPm+G1NQp94k1g/i/SAiwGaeqVeoQMxvt6jMZB1SPHLea+Di7kB5nvAgaDZuesnjIsKWRyu3qdl1f1Bld2PcWs1CumfMNS14HD1SFEySQSjWaYzcJZxPgvK7g/T7Ho2yryZPnZmeubGrNTCkR7tk7SmRC6kMjJDsN7qOZFqahNY1Wd0F9VlNGQvEkgWk87DFp1c+4bo=","f0Fv8C3Ir9kL6QTv7k+eOek/S/vuYdswJn07omoR5qcK0wpIDeumQAhiiff/1B+P/EfMdFADRDPbJ0+dkIvHhPoxo6XsNZX+zacMXSNdYWF9v1JpK+AvfLyc8NJuqs3DVzOCrmrNc6KWNEBgwfG6cGCaPDMvnQmqokXdYaDl+3hu4OI1FgbsqdOMKJivxw1VNzYB8XDaYuljFPSeV3L7kQgTs3sa316ecs9ssdGnJP6ADV/3h7hMkSv1yxKhdZGGiawJZfcX0YV1Hy8MJ+tCtwTOz/NtMUNZSJtLKcEp4K4p78dj7DvAkvUtH0I4dqBDAs16vXfewx2lNJ0uc1D7sHHL7pvljzg6SrqVAWOT09DrkXJGb8N2m7k0pmqw6uDUvEKlkWY5/pKjRdfDBAxyQ40//kyG336z7HRz1M1C0OWlGR7qbLWbfKUfs66L14KKctRPL98obaYxXL2FNG4k4TRiTgpBJUpEHpELUDnHib1jLk4/+aGJxqXGRR3GVGaIUkIv8YFbRwvWMVXFnEze4pxdn8T/XnsFOkXObb1AajzAW3hLfFXSQVc0DeXbpwEXZ2shWntlOiMKa0VcLgz0K7xI394gdqlnFIwC9A==","PFdPeIXfto1HQg3P8L9jqMZgc3wpV0qsYS3nsR5abYZXgeXFzjuxQOBHCUYXlXQbkSUJu1OF0KXt+wfs63uZVyBT7hT/+Q5zt0RgA30dQPjnVVZd9rdAtDoXDxOWDDVRFKmc9JW6amlHAdQ1+wV/RxDpESIUbyyZx/ogJ8LGhD/Xb+bRWpFjwXbNB7qri4FsIv9zSK+AYd2+x/O52bT4BQ7hF1aiuTMQc++sWAdcH8b9rQ1EuNKIhqxUZsojUK6OETdDLoz9HBVZ2Ez2QvaB0h77ixch7yV+eVHhxId47SSgduQOkxS/9IsX7EMRZgXyM3tQMBdEbxKymWxh63L1NviFw8s9Ekzd3y+B/eKYq0rgoTJgvBD7/DZDB0KEfQSEhMKZwYHxrZ8DSjRWv7vjfaiT44+bAnyv8JiNUy+B55+9GNbP2p+8DJos9TRlnC/qkElWNKEeF5THgofro5T0rcu1BjIsIZW2ZcuIMM0i6IDVBlhY5qzfYNFZP+1ddJNV8iUPi0F8uVK77X2YrR+FhOAMnpeMNLq8JISv0zrvGgKjP3cT+J/uR1UnyOWdncFNx16t/T1UzGlaGeraTN4Bk+uyLs7GDonMWwZwEUdMIvpFUFXZsHgHwXksLnlv2ZrzNiCxzqRCvXoxHZWX"],["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"],["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"]];
                            $result_val['FGStripCount'] = 1;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["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"]];
                        }else if($packet_id == 31 || $packet_id == 42 || $packet_id == 33){
                            $lines = 40;
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
                                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur_level);
                                if(isset($gameData->PlayBet)){
                                    $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                    $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lines);
                                }
                                $slotSettings->SetGameData($slotSettings->slotId . 'Multiple',1);
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', $betline);
                                
                                $slotSettings->SetBet(); 
                                $isBuyFreespin = false;
                                $allBet = $betline * $lines;
                                if($pur_level == 0){
                                    $allBet = $allBet * 80;
                                    $isBuyFreespin = true;
                                }       
                                if(isset($slotEvent['slotEvent'])){
                                    $slotSettings->SetBalance(-1 * ($allBet), $slotEvent['slotEvent']);
                                }
                                $_sum = ($allBet) / 100 * $slotSettings->GetPercent();
                                if(isset($slotEvent['slotEvent'])){
                                    $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent'],$isBuyFreespin);
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
                        $lines = 40;
                   
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
             //   $winType = 'bonus';
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
                $allBet = $betline * $lines;
                if($slotEvent == 'freespin' && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                    $allBet = $allBet * 80;
                }
                $slotSettings->SaveLogReport(json_encode($gamelog), $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
                
            }

            if($slotEvent != 'freespin' && $stack['WinType'] <2){
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
            }
            return $result_val;
        }
        public function parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines){
            $allBet = $betline * $lines;
            if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                $allBet = $allBet * 80;
            }
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
                $wager['game_id']               = "220";
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = strval($allBet);
                $wager['play_denom']            = "100";
                $wager['bet_multiple']          = strval($betline);
                $wager['rng']                   = $result_val['RngData'];
                $wager['multiple']              = $result_val['Multiple'];
                $wager['base_game_win']         = strval($result_val['TotalWin']);
                $wager['win_over_limit_lock']   = 0;
                $wager['game_type']             = 0;
                $wager['win_type']              = $result_val['WinType'];
                $wager['settle_type']           = 0;
                $wager['wager_type']            = 0;
                $wager['total_win']             = strval($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'));
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

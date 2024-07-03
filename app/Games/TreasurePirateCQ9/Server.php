<?php 
namespace VanguardLTE\Games\TreasurePirateCQ9
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
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 0}],"msg": null}');
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
                            $result_val['MaxBet'] = 12500;
                            $result_val['MaxLine'] = 10;
                            $result_val['WinLimitLock'] = 150000000;
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
                                "g" => "242",
                                "s" => "5.27.1.0",
                                "l" => "1.1.58",
                                "si" => "23"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["ZVjglWSPw7nhyHcw+bTHhVD7avQxuX9UWg7CZ9BHgbajbyH54IrNLkJBrOp0nlitx8H3aZdf/mNebqwRiJUwkdsv7uhBgPToKNOdX//ZpEpDWTRF8OabQ2xMj4R2FSXRahjELctka2Us9ih4ho4cxBMpVMp50XLWX0pWoMBifcGJifwAIVpL2UqW5NGLbhMIDGbn0Za0uhxWgy0bBaJYVNie3n3OJDo/ffSjV9dHIbTx6l+ciNLBhDxTTq+FqU5i9ZnVXJYL2dNfxPfQGk7oWNiCrv5kAIf6F8+VEw==","ZZKxckyrLSIFuxKH/rphyMN6tJNSxiWzm2pwsq7O2LrgFf1WLN5a7AGVgHWYkpc99i+JYblHDzoWAPoFGDzwFCBDlMre8Tu0FGLI6RUVy9hSeO6Vq5AjOeqM1AhDxGggKmbxvHR+UbeG77cO7ngftmOBuAshKG8QiHctItwunl7coZ6FQACrzl575/oFCWpFTU75ulElj/AackAKwuv7u8RtehwXMbJxRJBTuIfb3r3+q9dUdFc9GbAnKYoNlRjGhqrYnFAZX9tLXOgq1Il95LZP+a1hLDvKkc27Jw==","lyyTfldcVSy0bHiwWA+RFoHfyK68e0gfzvFF8CCpNjtN6+mMLcDkV2IaR3dH+yFnI82GV7wbPp3AdkMux6W0FNilv4/QkKZ4JV+EpJnCuFw7GtW8/2VJZgg3OnKb1H8GUGvmr6AOKdipKYGAUsBAYDMHv6FE5lJBVWMNtrDQrZSU5TGErm+5w/ERePj4KQyNxP1YI/jq12+Uot4FPP5RnKdFpaUYzZ6DlR533+oeGrdR8DbvH5MDt0pzoHPPPMwyxv0BucWGh1gHdzFumgJZpxMM1DHG/WOpeNQ8y6uc55avRouw3Qn43dh/mqA=","J2w1gwmIcfATAmScobUa92CH/4FcqCEvt/IeeDRP578WbBFWp4EBNOnMGG/ap28hVNdIHWYjQVQh54K/9amDQqNO+RXIqJsbl4f+80lxiGbN8dg4L/6T4LU6E1I/6t7SjSSA/XbUfUhpdFVDqgxceGE1WK9oUwmp8p3/w3eVadDjTqRPbnJxtkfgNWVtFv3N2dEInQux34PEqWYnjfecK+1vimqBJOiOe87XQza5mk1Re4toZ4qV+xI/a+elGfusNmmdICH7MqzdhMt6qEX5q6vqqIE9K9sQcsp39A==","7Rpv16Gmrt6qW9jM8YxtL4DCnclSUfZSRCMkp6i1veA+E1XG0l+R/NDXRl4nUBnu7MvdwmXfbOEK4QtuNTWGT6rA0xVUZ1ozLcovAX86qfNYBTepMaAkfgu6MvdF4DD0FKnJPv9hBZrSd1TESHXS2CAmbnDE2s0FGcqJgbT5rmiDYfdpWsaJJW4YFD83CXFdkPrzhGiw5LjidrWy9YZRTxPVXP9O9u0Ct27ObBNBRa+GP0sBu0RatquM3Rae32sRFnb+JlmoTQRSd6D+B/li3TJxOEqssEO3mK3h6A=="]];
                            $result_val['FGStripCount'] = 2;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["ZlFDvPwetkHfZjdOstU7+lKlQyJqK8p2qrHTqO47AIwgN5fgzuWqeMKTNvDAVZ6smxRYzV6gf3nq7w5Qikkg2EDBcVfkqEocG0NrqEpqoanleMvLGrjomgYxTFxkkphyw1KF2/aVVP2PnmSTavpMWFimTkO0k259rVLrykF1rP7cuDs4hwHFnd8iCBI54B0IqTWkACD7rbTwd/Lw0XvCdr+ZWhmsel8q4AzIi5yy79BMppietBHb89oAlW4Pj8ANkQXQ4dCtDIRXrTpj7L9+kZVCYqjSZbCI4rrhlqUvu14uM4yCob0JYdbprtA=","Wy71zoywUldamuPE6wjUT8KPcJnRiP/xgJ8zyzmDRNYovZqF++NFd+bIAXC0eGpbsjQseWSffynxglVXgYLYqzoSLnhUEP0Z8fmsTqo2LbNcnLvo5SWGfMp/WGZoFONBugIbGfomfv9UzQTC4407+NTd7ZkT/Q2YcA6WAfu4D63fM0095XR4xI9lohXwuY16PXrdp11hL2KGyLMwlJAKLOSBkZ5qQ4VPbjhiW9PW7GPk9ECrBf7uk2scj+EXcW9GRMGpzrafSo49qIGtkbE3V4Dqcb50J3KBh7EToOlLkxC115v0SxWDyFnEGGE=","oSjjWquxSU0LnI7jswouCiuk/zfWlMKTe3YkqYD9uF22M9V1HNacsP9w97LdpVOpb5ZsGmYJWmJ7D5g/62UOXvf/hj8thHsqGgraitOZym1LowsqkWGfJ6PfRhlAvZgeEcMnfh1/9O+0z5vxM2H2okmPGE0xnsLb7qp3EVGGCqxtld9xwHxHkAcmbHWOowqmc7VpAN0867cOU4kOPHMsDKeOPISw2w/sD3HIHIlMiSrnleEczTasrwgSLeihk1TPymQDmZo8EF0/XlyTNm3WzYjeIbkstdX4A3N1g2BvoCaJKLi9eTqgo50JcWc=","LjLu0ENRJmCXGSBp68y1E62yorCr318lUQ3AkrST7KyUhaoD9VAlxee+qdkDH4bM6W6s2imaAZdzQ5a9Jz2BUQyDi4DQL68xuR+YcfmvEwtlGuN08wwzVK+GcBssuxduWyfeFcbLW8dsivjcFp2he7rkU0V+0YhEdQ/nb1F/0wUiCEu8I7aFtKkVp30Y1cI/xNTb211pywULhoiHqMh96xXW8YMEab/a5UwpnNQ62VD8MLM4hhXgQSz4k1B6P0JCv+hoiqif5HsICAtEmPM3q3t9YEEjiDUc4x7TKwlyyv7RfQScArmhtdzHMro=","xTgpAVqa0snEkB42UspzHbZ8dMP5knnZFRGOyzpUHMKP+RN/1OI/LaqhRXnldg0UKeFR7v3B2rQtci1ywPQUl/o+xREf+3npJQvqakaq0fWscA6WMrwmKbmU59iNWWxzIARaJKheA+3HGr98Y3c5FrGsc4g9YFdzHQbE/tmqI7VeeVmvMaTvhVRuNkcK78yh3HDNbPIpx9M3txlEMciOlVlh6EGk1PAK5gcFg2BY59a6yGvFuZWr4D0qHUyutTXIdStLpr2FPwP6x4loyHcPTwO3+uZIzDeTpLkOgiU48794kHKnqt1hA/h8G1M="],["svOrWBA4FJ6HMdQu75Z9G/rldMtao6vM7+Mza0QjZuQMRCb+WxtKKbrD7FFeZDz0bA7sBj8otPXvtLp6eiKu8cYnRMU2lBxP+5rAJ94/UDv84os7VBHnAQ0k9m/8morrSbPDsCWrVRghCkWVBotV8UYM3Q4lTLI/0o/HtzsEJBQr7/2mu39wfwGXyFy9DmTba49EJq3c1TVgJnnearsrGyznux1EoddfnYlFsA9zF35zv7msQ+3KL9GkeU46dpOy7pRDVOUUXbmzHs70MoQnKK1rkHHZlVjG1ar6REFLaoYYnTXoexF8ERvWPH0=","WuyQ4p8XpQlnbUevz1ERZ+m6T2SF5W8Jws3KXSUd4Cma8JJTWKM/s56TbJJuWgvS9oaiPcH8+W+w4s1ei0OlSh73vAB7AgrldpAPuDu0Ab45RSgNiMe242dv4bR+4IyqdS8fYGIAUndQ+Odi0jldQWmf54F9D4Rb9ujG9Rk6tfPBxwb2eHiT7jQW/K+qNZkWHZcP+xwJCTijI2ud3mJ0nK50207lzKzS3w4WzMIBee+fn0YPAuAWKS14XgT4TFExoPJrDFP5PkIjHuYu2KioK8IZvVHPp1fjnJ0QLsPYmCcgpMe3EGYzn/lUXSo=","jApyYRkFbTOovGXLfUanE5Aalm/hSrIVJQmcsxdKz1WiR2fTvX46i4IpjE4dueZQrlpV0TaYwC5c/dHqZ1rheRZCSiCGgpafljPsek4+DXGotRbIL+k4EuQU6VEf6wsvhwHcAHSgOAeZyZ9ZrA6dQbW6CKg8VI+2cKc3vkJrj40N6rvOA6wtApzSSFFdzOlQL1jpwyA/zkUf9+4jSUfyYXuRXv9ovTMBBp9+gqqdT7cXdtX8AlrKvfEquCCuWF1dtmWysuKP5lhU9qzUYx6GUrH9f35zRfdZRqNzsQ==","GItMMc8nHisfwX3Rl/BXTTWj7Qrxggj4JHgoImmKpj0AY8b96s3UwVvmwf+luDCcdb+8jFMIP4uA2FppNhNvCZR5zIFrk7gDRP5p9tv3DdLTouMQ+q0pnA0yD7WRxeyWyUgb2nZRKMbbzCEHTNVik3VlZJjIZ2nPnWA+cJk1mJk3pBvw1e+vRNc/VzxQCoVs5T4R+nDjv5dx8HWtT/2TzSfWp33l7DKJWk58Q8226/ElsikVeJqcKmymga5L9vJbEVIL0fgIzXNABI7UEeaIf3DJ/cRTPUSD3PZ98iQH566Kta6cHvSLa/K0/Io=","Q8W2H8AVSmCPXHCSKlMMQEPZICGtJSaLTjQzX1XXuUubj0Irp5XZaXCGtNnrruCWk7FReEXI0Ize/N7wU5G9lNhAJ3dUdIRdbqg3QUiCyuCAZvhv7Dj8+nWspblzEDGpozjc6JYhwGH6+ReARISspQJ5IWBFzxzhpBNmGYarAxuWDcnt9OlUR/aW+1t1pWLV+8SY94fOK2fhd/U20uutwE69bitl0TFOnDFvynnlhuGJOiYVB0ZpmwGPzaZ+EbqorpH66abhBSvxUF+/c44JQo/Ec9Z2luNLdx9JAOtQKWLf2XlGhPIKFEjmJmA="]];
                        }else if($packet_id == 31 || $packet_id == 42 || $packet_id == 33){
                            $lines = 10;
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
                                $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks  릴배치표 저장
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                                if(isset($gameData->PlayBet)){
                                    $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                    $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                }
                                
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', $betline);
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
                        $lines = 10;
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
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, ($betline * $this->demon) * $lines, $lines);
            $winType = $_spinSettings[0];
            $_winAvaliableMoney = $_spinSettings[1];
            
            //$winType = 'bonus'; 
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);

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
                $awardSpinTimes = $stack['AwardSpinTimes'];    
                if(isset($stack['CurrentSpinTimes'])){
                    $currentSpinTimes = $stack['CurrentSpinTimes'];   
                }
                $valueItem = [];
                if(isset($stack['ExtendFeatureByGame2'])){                                               
                    $stack['ExtendFeatureByGame2'][4]['Value'] = substr($stack['ExtendFeatureByGame2'][4]['Value'],0,-1);
                    $stack['ExtendFeatureByGame2'][4]['Value'] = substr($stack['ExtendFeatureByGame2'][4]['Value'],1);
                    $tempArr = explode('],',$stack['ExtendFeatureByGame2'][4]['Value']);
                    $valueArr= [[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0]];
                    for($i=0;$i<count($tempArr);$i++){
                        $tempArr[$i] = substr($tempArr[$i],1);
                        $subArr = explode(',',$tempArr[$i]);
                        for($k=0;$k<count($subArr);$k++){
                            $valueArr[$i][$k] = $subArr[$k];
                            $valueArr[$i][$k] = intval($valueArr[$i][$k]) / $originalbet * $betline;
                        }
                    }
                    
                    $tempStr = '[';
                    for($j=0;$j<count($valueArr);$j++){
                        $tempValueStr = '';
                        for($k = 0;$k<count($valueArr[$j]);$k++){
                            if($k == 0){
                                $tempValueStr = $tempValueStr . '[' . intval($valueArr[$j][$k]) . ',';
                            }else if($k == 4){
                                $tempValueStr = $tempValueStr . intval($valueArr[$j][$k]) . ']';
                            }else{
                                $tempValueStr = $tempValueStr . intval($valueArr[$j][$k]) . ',';
                            }
                            
                        }
                        if($j != 2){
                            $tempStr = $tempStr . $tempValueStr . ',';
                        }else{
                            $tempStr = $tempStr . $tempValueStr . ']';
                        }
                    }
                    $stack['ExtendFeatureByGame2'][4]['Value'] = $tempStr;
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
            /*if(isset($stack['IsRespin']) && $stack['IsRespin'] == true){
                $freespinNum = 1;
            }*/

            $newRespin = false;
            if($stack['IsRespin'] == true){
                $newRespin = true;
                $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 1);
            }else{
                $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
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
                if(($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes) && ($stack['RetriggerAddRound'] == 0 && $stack['RetriggerAddSpins'] == 0)){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $isState = true;
                }
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
            $proof['lock_position']         = [];
            foreach($result_val['ExtendFeatureByGame2'] as $item){
                $newItem = [];
                $newItem['name'] = $item['Name'];
                if(isset($item['Value'])){
                    $newItem['value'] = $item['Value'];
                }else{
                    $newItem['value'] = null;
                }
                $proof['extend_feature_by_game2'][] = $newItem;
            }
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
                $wager['game_id']               = 242;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = ($betline *  $this->demon) * $lines;
                $wager['play_denom']            = 10000;
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

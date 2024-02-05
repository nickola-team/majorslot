<?php 
namespace VanguardLTE\Games\BaseballFeverCQ9
{
    use Illuminate\Support\Facades\Log;
    include('CheckReels.php');

    class Server
    {
        public $winLines = [];
        public function get($request, $game, $userId) // changed by game developer
        {
            /*if( config('LicenseDK.APL_INCLUDE_KEY_CONFIG') != 'wi9qydosuimsnls5zoe5q298evkhim0ughx1w16qybs2fhlcpn' ) 
            {
                return false;
            }
            if( md5_file(base_path() . '/app/Lib/LicenseDK.php') != '3c5aece202a4218a19ec8c209817a74e' ) 
            {
                return false;
            }
            if( md5_file(base_path() . '/config/LicenseDK.php') != '951a0e23768db0531ff539d246cb99cd' ) 
            {
                return false;
            }
            $checked = new \VanguardLTE\Lib\LicenseDK();
            $license_notifications_array = $checked->aplVerifyLicenseDK(null, 0);
            if( $license_notifications_array['notification_case'] != 'notification_license_ok' ) 
            {
                $response = '{"responseEvent":"error","responseType":"error","serverResponse":"Error LicenseDK"}';
                exit( $response );
            }*/
            $response = '';
            \DB::beginTransaction();
            if( $userId == null ) 
            {
            	$response = 'unlogged';
                exit( $response );
            }
            $user = \VanguardLTE\User::lockForUpdate()->find($userId);
            $credits = $userId == 1 ? $request->action === 'doInit' ? 5000 : $user->balance : null;
            $slotSettings = new SlotSettings($game, $userId, $credits);
            $find = array("#i", "#b", "#s", "#f", "#l");
            // $paramData = trim(file_get_contents('php://input'));
            $paramData = json_decode(str_replace($find, "", trim(file_get_contents('php://input'))), true);
            $paramData = $paramData['gameData'];
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 3}],"msg": null}');
                    $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetBalance().'],"evt": 1}');                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BaseWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ScatterWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', [1,1,1]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReelPaies', [0, 0, 0, 0, 0]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RngData', [0, 0, 0, 0, 0]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TempRespinValue', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeScatterCount',0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TempRespinValue', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TempTotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentAwardWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'AwardWins', []);
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
                            $result_val['DefaultDenomIdx'] = 4;
                            $result_val['MaxBet'] = 600;
                            $result_val['MaxLine'] = 1;
                            $result_val['WinLimitLock'] = 300000000;
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
                            $result_val['FeedbackURL'] = null;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();

                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["d1hnLVVJ4oaQKFz79JHeW5gOXDxM4l6y7CkFXgmtuDaOQo6evV9CLwEc/fzyp/57TuKFFl9Q8j+R/AkUiGGjp/tOCLkBP02BrFGG6+1UEy9yXQjHumx8xDMT834/aCS+syuPpv+VNmWGAPIWAEVukkZwEGHgBpjeekiAJzGnLjdQ51XGySZlczCMQKMZR4hOkMPWx+OUaZacAlX8cLD21KvIgXHUmDqBmphbmP0j6DvrkikdHQ4A1312t4vgEZXF9vh7+HKr5P03OTG8y0DzHa234Mzz1MHWLzFlJpVAJiiSNIBe9UPVbhkqhmY=","GcTWI4OHrgOZy3B5v7zwS02ZH7jkP5Qfu7vFW1f0E/LvF5HYt8VXZ+onmeU4tSlm05QkH8UScWiWEbFiLrP/+iVPZ3gWxOPg7nSx9mdM70g+P8LJPX+OzwlE8pa1hU0TxZ/SsiX4Qtt91BJUR5QGE3Mgnpw5+5WAnmhK7RraWjnhnuQkALZW8jH/6cCznlpTfJpkJeBvuBMlr9UIu3gOOuySfdEcqqyDfFWdbcU/4QslWUvY7rdFLVF+2Zby3meDiwJ3Aicq6gyl+QjOiwBDb64RSP45UJ/Bjec5hYHC5TAp9ItnLSapMS0330tnYHK+XX/9bSI0U2puFEIQ","ZmFbRzByGYkAMjNjGV2z3C0csFi3UdsyNjuRNgVAKcqJe6XkLmU+948ufXQ07PfwYfZKZWeIVIyACxzYpRKjSmTEdkiX7Mu1veAHsWCgm8yFWLTemzfvTxPN4pXBRJMkGryrapILfdwT8Qzu4KoYoQZQsQ57V1m6z67Lx1jKo/j3lqYG9MPZa+qFiyhaPhETQP2gvbbsBer1Ula5WMH/uTPd026MQZa4MGL2zvzDv2wqyFoRlH3x2jb7/sZ0NzHj81Vpz851Pi0dTE00YSMIolJqH8EwJIZGd5JX5TxLfUteOyA40AGbf/ipM3k=","1Ae983Ggg3vOI5KRiD/9AVS9X5Tt6aFLblbiHsSyjV2F6hYAQJRqdzQ55/fY4xDkPMM8EfV6LhA6tJY9BwgVhq5vXnePfZvvnZNF4A0gpKuU/xSBYUpEn/V4AL3L9VPoS8uiLWxhDMeH7+/3Kla8wuaPVxfdb0+T0FsYskBzR5hyh23C0rvdCyx2gKbvaLvhg/DzQDmPeEuzhRkdjdkvRApB7mfEhM5YntQ4xkeV9zmGzekl02n5O8FqyczA/OefQfZQI4WvHh5+rJ65lQyQthuvWbpCK569Wepk5v8kfdIBKCFzr8qS8MLrkCQ=","b9dvrmWUQCWNdPqMBXvi8fQO5gVd7HLV0ZUHDivnDaFtO5KZUReCdQAgBoXe/zq8AzHfUQYdPj2hH9mZzmr9nzwyoTUHh8GWBu2GZ3Md8ymBxDWPm2/Rwp+DbQBabKW2dHW8w78F6qqaPJeYKaTHQvBR3gLQUrpib9a1mOYePeeL2HA4OISzbs3vLH+dZezpRV43qejxufRQTIYawEWAErhA26q+n5M/Zyj4UbA1e2tr206n26SKici/z3oV6cW/TDlnRLjT2TlMyzGlfYwRS3jrzGYBCAnUgwP4pIeGvfsG25/pujZEUIa/hfs="]];
                            $result_val['FGStripCount'] = 1;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["Y5jx9kqqfPQoSHpvNQlJUxd5WYagFuRIzOYzOA9cnbGTDUjQJcUFbu9UL/F1KhDdgFhL37LHxH/eNz/p0uvYGWyvrpfMBIZa49MxVmV6xP6CxelOx/egvZFhEAyjH1yYll0uPDU92rQ0RCEVdHpeWN2ccdhSajzOfcP+F/cliIIwcn5QB0Lll5wJ2XatEuOL+PqbxWYgVgMwESY1","vjju6OEsy7o8VGeSYryV7ZSMEpd6IOSr/dLWULUGiezHMMLZLq6yWvhl8P82fRCgUPxXH3oMc3CZE0OjhXCYAp2QY4HUQG1wD/T4hFbWRPHDJruRMhFJ8RJVmyKMIKcrkrOubJ8gvx8PONns/eeROKD3MjRV0zXQ/IKoOTkIj7e175Ye5Ja7M+aJ1CW/Z7oRp49LpDBpxadm5z7q","eMsfw0juDQ8MoD4kKPglMWIfKVcl0rcLNKGsk3aDDgsvd6oCAwmN38ZBfQD5TJrul1NVZGF9flViZ4BehxctHNqtRZ1V2Td8W62m8TnBmx6Vp5/gNX/uMbFxYhgKn0gT1/Jl5S+mWlr3N9rHfBeW2oQ+gx9I1RBicHEwjZDC7/rb6dbmSmbVR9mHuHR6vPC2EM60rzOqOsOBicIL","LBTG8xQrtTH1JW4ZuXDs0lf+rovLkpB7KTLDL1FRWR5tTwTPhR2lZ5/STW9ZNuzCYMh3b8dkWm654BjrWbuzAcE1W9f3ZrMCwRVwJCn3kP2k8hB73I+rJSgRlDmCwa1Jf2OJ5OwfZsq8zXAKcm9mSStZjKal9MdDC6RngpoP5jI+NZ39I5X98q9anFLP7LvLQx05UKmNmjtza/ZF","12e3IhLLsqf3UOkhbk+GZICAH7etf9oMevRQnF4Z2/mUqaSGJcWma6RkVj8J/+0chb5vZyQMPvu2/pXD5Ytor4jlrc1xcOxWfNZBZRKUs+uM31DLH1lBHP1W+0GfEdaW3cDb4gJlbWdClhJ9wxso3BAIZaVAZfQ9D1ROKDV9+QtHW91eHHZq6hBpxjbrS7ZIh+LOfHpMvTJRs85VSv9s7b8ZvDcEc7Z1datKZK/uDZEUiRgBkeQzBmsB8ZjYAHBRJhVdm53RbhGgWGfW"]];
                        }else if($packet_id == 31 || $packet_id == 42){
                            $respinReels = [0, 0, 0, 0, 0];
                            if($packet_id == 31){
                                $betline = $gameData->PlayBet;// * $gameData->MiniBet;
                                $lines = $gameData->PlayLine;
                            }else if($packet_id == 42){
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                                $lines = 1;
                            }
                            $respinReelNo = 0;
                            if($packet_id == 42 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame')){
                                $slotEvent['slotEvent'] = 'freespin';
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                                $totalbet = $betline * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
                            }else{
                                $slotEvent['slotEvent'] = 'bet';
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BaseWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'ScatterWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', [1,1,1]);
                                $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentAwardWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'AwardWins', []);
                                $totalbet = 0;
                                if($gameData->ReelPay == 0){
                                    $totalbet = $betline * $lines * $gameData->MiniBet;
                                    $slotSettings->SetBalance(-1 * ($totalbet), $slotEvent['slotEvent']);
                                    $slotSettings->UpdateJackpots($totalbet);
                                    $_sum = $totalbet / 100 * $slotSettings->GetPercent();
                                    $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                }else{
                                    $slotEvent['slotEvent'] = 'reel';
                                    $respinReels = $gameData->ReelSelected;
                                    for($k = 0; $k < 5; $k++){
                                        if($respinReels[$k] == 1 && $slotSettings->GetGameData($slotSettings->slotId . 'ReelPaies')[$k] == $gameData->ReelPay){
                                            $totalbet = $gameData->ReelPay;
                                            $slotSettings->SetBalance(-1 * ($totalbet), $slotEvent['slotEvent']);
                                            $slotSettings->UpdateJackpots($totalbet);
                                            $_sum = ($totalbet) / 100 * $slotSettings->GetPercent();
                                            $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                            $respinReelNo = $k + 1;
                                            break;
                                        }
                                    }
                                }
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '578' . substr($roundstr, 3, 9);
                                $slotSettings->SetGameData($slotSettings->slotId . 'GamePlaySerialNumber', $roundstr);
                            }

                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['RespinReels'] = $respinReels;
                            if($slotEvent['slotEvent'] == 'reel' && $respinReelNo == 0){
                                // Exit
                            }else{
                                $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $totalbet, $respinReelNo);
                            }

                            $slotSettings->SaveGameData();
                        }else if($packet_id == 32 || $packet_id == 41){
                            $result_val['ErrorCode'] = 0;
                            // if($type == 3){

                            //     $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance() - $slotSettings->GetGameData($slotSettings->slotId . 'ScatterWin'));
                            // }
                            if($packet_id == 41){
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance() - $slotSettings->GetGameData($slotSettings->slotId . 'ScatterWin') - $slotSettings->GetGameData($slotSettings->slotId . 'BaseWin'));
                                $result_val['PlayerBet'] = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                                $result_val['AccumlateWinAmt'] = $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                                $result_val['AccumlateJPAmt'] = 0;
                                $result_val['ScatterPayFromBaseGame'] = $slotSettings->GetGameData($slotSettings->slotId . 'ScatterWin');
                                $result_val['MaxRound'] = 1;
                                $result_val['AwardRound'] = 1;
                                $result_val['CurrentRound'] = 1;
                                $result_val['MaxSpin'] = 100;
                                $result_val['AwardSpinTimes'] = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames');
                                $result_val['Multiple'] = 0;
                                $result_val['GameExtraData'] = "";
                            }else{
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                            }
                        }else if($packet_id == 43){
                            $result_val['TotalWinAmt'] = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                            $result_val['ScatterPayFromBaseGame'] = $slotSettings->GetGameData($slotSettings->slotId . 'ScatterWin');
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
                        // $vals = [];
                        // $vals['FreeTicketList'] = [];
                        // $vals['FreeTicketList']['ThisGameFreeTicketList'] = null;
                        // $vals['FreeTicketList']['OtherGameFreeTicketList'] = null;
                        // $response = $response . '------' . $this->encryptMessage('{"vals":[11,'.json_encode($vals).'],"evt": 1}');
                    }
                }else if($paramData['req'] == 202){
                    $gameDatas = $this->parseMessage($paramData['vals']);
                    $game_id = json_decode($gameDatas[0])->GameID;
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1, "'. $slotSettings->GetNewGameLink($game_id) .'"],"msg": null}');
                }else if($paramData['req'] == 1000){  // socket closed
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame')){
                        // FreeSpin Balance add
                        $slotEvent['slotEvent'] = 'freespin';
                        $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                        $lines = 1;
                        $count = 0;
                        while($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame')){
                            $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                            $result_val = [];
                            $result_val['Type'] = 3;
                            $result_val['ID'] = 142;
                            $result_val['Version'] = 0;
                            $result_val['ErrorCode'] = 0;
                            $result_val['EmulatorType'] = 0;
                            $result_val['RespinReels'] = [0, 0, 0, 0, 0];
                            $totalbet = $betline * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
                            $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $totalbet, 0);
                        }
                    }
                }
            }else if(isset($paramData['irq']) && $paramData['irq'] == 1){
                $response = $this->encryptMessage('{"err":0,"irs":1,"vals":[1,-2147483648,2,988435344],"msg":null}');
                $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetGameData($slotSettings->slotId . 'CurrentBalance').'],"evt": 1}');
                $response = $response . '------' . $this->encryptMessage('{"vals":[11,{ "FreeTicketList":{"ThisGameFreeTicketList": null,"OtherGameFreeTicketList": null}}],"evt": 11}');
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
        public function generateResult($slotSettings, $result_val, $slotEvent, $betline, $lines, $totalbet, $respinReelNo){
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, $betline * $lines, $lines);
            $winType = $_spinSettings[0];
            $_winAvaliableMoney = $_spinSettings[1];
            // $winType = 'bonus';
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);
            $isFreeScatter = false;
            $defaultScatterCount = 0;
            if($winType == 'bonus'){
                $defaultScatterCount = $slotSettings->getScatterCount($slotEvent);
                //  $defaultScatterCount = 5;
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeScatterCount',$defaultScatterCount);
                
                
            }
            //$multiples = [0, 1, 1, 1, 1, 1];
            //$subMul = $slotSettings->getMultiple($slotEvent);
            // for($i = 0; $i < 3; $i++){
            //     $multiples[$i + 2] = $subMul[$i];
            // }
            for( $i = 0; $i <= 2000; $i++ ) 
            {
                $totalWin = 0;
                $lineWins = [];
                $lineWinNum = [];
                $wild = 'W';
                $scatter = 'SC';
                $_obf_winCount = 0;
                $strWinLine = '';                                
                $reels = $slotSettings->GetReelStrips($winType, $slotEvent);
                if($respinReelNo > 0){
                    $initRngData = $slotSettings->GetGameData($slotSettings->slotId . 'RngData');
                    for($k = 1; $k <=5 ; $k++){
                        if($respinReelNo == $k){
                            $initRngData[$k - 1] = $reels['rp'][$k];
                            break;
                        }
                    }
                    $reels = $slotSettings->GetRespinReelStrips($initRngData);
                }
                $awardMoney = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentAwardWin');
                $OutputWinChgLines = [];
                $ReellPosChg = 0;
                $lockPos = [];

                

                $scatter1Count = 0;  
                if($slotEvent == 'freespin'){
                    $scatter1Positions = [[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0]]; 
                    for($r = 0; $r < 5; $r++){
                        for( $k = 0; $k < 3; $k++ ) 
                        {
                            if( $reels['reel' . ($r+1)][$k] == 'SC1' ) 
                            {                                
                                $scatter1Count++;
                                $scatter1Positions[$k][$r] = 1;
                            }
                        }
                    }
                    if($scatter1Count > 0){
                        $OutputWinLines['SC1'] = [];
                        $OutputWinLines['SC1']['SymbolId'] = 'SC1';
                        $OutputWinLines['SC1']['LineMultiplier'] = 1;
                        $OutputWinLines['SC1']['LineExtraData'] = [0];
                        $OutputWinLines['SC1']['LineType'] = 0;
                        $OutputWinLines['SC1']['WinPosition'] = $scatter1Positions;
                        $OutputWinLines['SC1']['NumOfKind'] = $scatter1Count;
                        $OutputWinLines['SC1']['SymbolCount'] = $scatter1Count;
                        $OutputWinLines['SC1']['LinePrize'] = $awardMoney * $scatter1Count;
                        $OutputWinLines['SC1']['WinLineNo'] = 998;
                        $totalWin = $totalWin + $awardMoney * $scatter1Count;
                    }
                }
                $scattersCount = 0;  
                $scatterPositions = [[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0]]; 
                $scattersReel = [0, 0, 0, 0, 0];
                $wildReel = [1,1,1];
                $isWild = false;
                for($r = 0; $r < 5; $r++){
                    for( $k = 0; $k < 3; $k++ ) 
                    {
                        if( $reels['reel' . ($r+1)][$k] == $scatter ) 
                        {                                
                            $scattersCount++;
                            $scatterPositions[$k][$r] = 1;
                            $scattersReel[$r]++;
                        }
                        else if( $reels['reel' . ($r+1)][$k] == $wild ) 
                        {                                
                            $isWild = true;
                            $randNumber = mt_rand(0,3);
                            $bonusArray = [1,2,3,5];
                            $bonusMul = $bonusArray[$randNumber];
                            $wildReel[$r-2] = $bonusMul;
                        }
                    }
                }


                $winResults = $this->winLineCalc($slotSettings, $reels, $betline, 0, $respinReelNo,$slotEvent,$wildReel);
                $totalWin = $winResults['totalWin'];
                $OutputWinLines = $winResults['OutputWinLines'];

                $freespinNum = 0;
                $FiveSymbolCount = 0;
                $newAwardMoney = 0;
                if($isWild == true){
                    foreach( $OutputWinLines as $index => $outWinLine) 
                    {
                        if($outWinLine['NumOfKind'] == 5 && ($outWinLine['SymbolId'] == '1' || $outWinLine['SymbolId'] == '2' || $outWinLine['SymbolId'] == '3' || $outWinLine['SymbolId'] == '4')){
                            $freespinNum = 5;
                            $newAwardMoney = $outWinLine['LinePrize'];
                            $OutputWinLines[$index]['LineType'] = 1;
                            $FiveSymbolCount++;
                        }
                    }
                }
                $wildStr = "";
                $wildStr = $wildReel[0] . " " . $wildReel[1] . " " . $wildReel[2];
                $scatterWin = 0;
                $scatterPay = [0,0,0,2,10,20];
                if($scattersCount >= 3){
                    
                    $scatterWin = $betline * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet') * $scatterPay[$scattersCount];
                    $OutputWinLines[$scatter] = [];
                    $OutputWinLines[$scatter]['SymbolId'] = $scatter;
                    $OutputWinLines[$scatter]['LineMultiplier'] = 1;
                    $OutputWinLines[$scatter]['LineExtraData'] = [0];
                    $OutputWinLines[$scatter]['LineType'] = 0;
                    $OutputWinLines[$scatter]['WinPosition'] = $scatterPositions;
                    $OutputWinLines[$scatter]['NumOfKind'] = $scattersCount;
                    $OutputWinLines[$scatter]['SymbolCount'] = $scattersCount;
                    $OutputWinLines[$scatter]['LinePrize'] = $scatterWin;
                    $OutputWinLines[$scatter]['WinLineNo'] = 998;
                    $totalWin = $totalWin + $scatterWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', $wildStr);
                }
                if( $i > 1000 ) 
                {
                    $winType = 'none';
                }
                if( $i >= 2000 ) 
                {
                    break;
                }
                if( $freespinNum > 0 && $winType != 'bonus') 
                {
                }
                else if($winType == 'bonus' && $freespinNum == 0){
                }
                else if($FiveSymbolCount > 1){
                }
                else if($isFreeScatter == true){
                    if($scatter1Count == 1){
                        break;
                    }
                }
                else if( $totalWin <= $_winAvaliableMoney && $winType == 'bonus' ) 
                {
                    $_obf_0D163F390C080D0831380D161E12270D0225132B261501 = $slotSettings->GetBank((isset($slotEvent) ? $slotEvent : ''));
                    if( $_obf_0D163F390C080D0831380D161E12270D0225132B261501 < $_winAvaliableMoney ) 
                    {
                        $_winAvaliableMoney = $_obf_0D163F390C080D0831380D161E12270D0225132B261501;
                    }
                    else
                    {
                        break;
                    }
                }
                else if( $totalWin > 0 && $totalWin <= $_winAvaliableMoney && $winType == 'win' ) 
                {
                    $_obf_0D163F390C080D0831380D161E12270D0225132B261501 = $slotSettings->GetBank((isset($slotEvent) ? $slotEvent : ''));
                    if( $_obf_0D163F390C080D0831380D161E12270D0225132B261501 < $_winAvaliableMoney ) 
                    {
                        $_winAvaliableMoney = $_obf_0D163F390C080D0831380D161E12270D0225132B261501;
                    }
                    else
                    {
                        break;
                    }
                }
                else if( $totalWin == 0 && $winType == 'none' ) 
                {
                    break;
                }
            }
            if($scatter1Count > 0){
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeScatterCount', $slotSettings->GetGameData($slotSettings->slotId . 'FreeScatterCount') + 1);
            }
            if($totalWin > 0){
                $slotSettings->SetBalance($totalWin);
                $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
            }


                
            
            $result_val['Multiple'] = $wildStr;
            $isEnd = true;
            if($slotEvent == 'freespin'){
                $scatterMul = $scatterPay[$slotSettings->GetGameData($slotSettings->slotId . 'BonusMul')];
                
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin * $scatterMul);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin * $scatterMul);
                $result_val['AccumlateWinAmt'] = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                $result_val['AccumlateJPAmt'] = 0;
                $result_val['ScatterPayFromBaseGame'] = $slotSettings->GetGameData($slotSettings->slotId . 'ScatterWin');
                $result_val['AwardRound'] = 1;
                $result_val['CurrentRound'] = 1;
                $result_val['RetriggerAddRound'] = 0;
                $result_val['AwardSpinTimes'] = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames');
                $result_val['CurrentSpinTimes'] = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                $result_val['RetriggerAddSpins'] = 0;
                $result_val['LockPos'] = $lockPos;
                $result_val['Multiple'] = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') * $scatterMul;
                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $freespinNum > $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame')){
                    $isEnd = false;
                }
            }else{
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
            }
            // $result_mul = [];
            // for($k = 2; $k < 5; $k++){
            //     if($k == 3){
            //         if($slotEvent == 'freespin'){
            //             array_push($result_mul, $multiples[$k]);
            //         }
            //     }else{
            //         array_push($result_mul, $multiples[$k]);
            //     }
            // }
            
            $isTriggerFG = false;
            
            if($freespinNum > 0){
                $slotSettings->SetGameData($slotSettings->slotId . 'ScatterWin', $scatterWin);
                $isTriggerFG = true;
                $awardwins = $slotSettings->GetGameData($slotSettings->slotId . 'AwardWins');
                array_push($awardwins, $newAwardMoney);
                $slotSettings->SetGameData($slotSettings->slotId . 'AwardWins', $awardwins);
                if($slotEvent == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $freespinNum);
                    $result_val['RetriggerAddSpins'] = $freespinNum;
                }else{
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespinNum);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentAwardWin', $newAwardMoney);
                    $isEnd = false;
                }
            }
            $lastReel = [];
            $rngData = [];
            $symbolResult = [[],[],[]];
            for($k = 1; $k <=5 ; $k++){
                array_push($rngData, $reels['rp'][$k]);
                for($j = 0; $j < 3; $j++){
                    array_push($lastReel, $reels['reel'.$k][$j]);
                    $symbolResult[$j][$k - 1] = $reels['reel'.$k][$j];
                }
            }
            $slotSettings->SetGameData($slotSettings->slotId . 'RngData', $rngData);
            $result_val['GamePlaySerialNumber'] = $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'); //449660471
            $result_val['RngData'] = $rngData;
            $result_val['SymbolResult'] = [implode(',', $symbolResult[0]), implode(',', $symbolResult[1]), implode(',', $symbolResult[2])];
            if($freespinNum > 0){
                $result_val['WinType'] = 2;
            }else if($totalWin > 0){
                $result_val['WinType'] = 1;
            }else{
                $result_val['WinType'] = 0;
            }
            $result_val['BaseWin'] = $totalWin - $scatterWin;
            $result_val['TotalWin'] = $totalWin;      
            if($slotEvent != 'freespin'){                      
                $result_val['IsTriggerFG'] = $isTriggerFG;
                if($isTriggerFG == true){
                    $result_val['NextModule'] = 20;
                }else{
                    $result_val['NextModule'] = 0;
                }
            }
            $result_val['ExtraDataCount'] = 1;
            $result_val['ExtraData'] = [0];
            $result_val['BonusType'] = 0;
            $result_val['SpecialAward'] = 0;
            $result_val['SpecialSymbol'] = 0;
            $result_val['ReelLenChange'] = [];
            $result_val['IsRespin'] = false;
            $result_val['FreeSpin'] = [$freespinNum];
            $result_val['NextSTable'] = 0;
            
            $result_val['IsHitJackPot'] = false;
            $result_val['udsOutputWinLine'] = [];
            $lineCount = 0;
            $result_val['ReellPosChg'] = [0];
            foreach( $OutputWinLines as $index => $outWinLine) 
            {
                array_push($result_val['udsOutputWinLine'], $outWinLine);
                $lineCount++;
            }
            $result_val['WinLineCount'] = $lineCount;

            $result_val['ReelPay'] = [0, 0, 0, 0, 0];
            $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines, $totalbet, $respinReelNo);
            if($isEnd == true){
                if($slotEvent != 'freespin'){
                    // if($respinReelNo == 0 || $totalWin > 0){
                    //     $result_val['ReelPay'] = $this->getReelPay($reels, $betline, $slotSettings,$respinReelNo,$totalWin - $scatterWin);
                    // }else{                        
                    //     $result_val['ReelPay'] = $this->getReelPay($reels, $betline, $slotSettings,$respinReelNo,$slotSettings->GetGameData($slotSettings->slotId . 'TempRespinValue'));
                    // }
                    $result_val['ReelPay'] = $this->getReelPay($reels, $betline, $slotSettings,$respinReelNo,$totalWin,$wildReel);
                }
                $slotSettings->SaveLogReport(json_encode($gamelog), $totalbet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $slotSettings->GetGameData($slotSettings->slotId . 'BaseWin'), $slotEvent, $result_val['GamePlaySerialNumber']);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeScatterCount',0);
            }else{
                // if($scatterReelNumberCount >= 5){
                //     $reelWins = [0,0,0,0,0];
                //     for($i=0;$i<5;$i++){
                //         $reelWins[$i] += floor($betline * 838 * ($scatterReelNumberCount - 4)) + mt_rand(0, 8); 
                //     }
                //     $result_val['ReelPay'] = $reelWins;
                // }
            }
            $slotSettings->SetGameData($slotSettings->slotId . 'ReelPaies', $result_val['ReelPay']);
            //if($slotEvent != 'freespin' && $scatterReelNumberCount >= 3){
            if($slotEvent != 'freespin'){
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $scatterWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'BaseWin', $totalWin - $scatterWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
            }
            return $result_val;
        }
        public function getReelPay($reels, $betline, $slotSettings,$respinNo,$reeltotalWin,$wildReel){
            $wild = 'W';
            $symbolId = [1, 2, 3, 4, 11, 12, 13, 14,15];
            $reelWins = [1, 1, 1, 1, 1];
            $scatterReel = [0,0,0,0,0];
            $scatterPay = [2,10,20];
            $scatterReelCount = 0;
            $scatterCount = 0;
            for($k = 0; $k < 5; $k++){
                $isScatter = false;
                for($j = 0; $j < 3; $j++){
                    if($reels['reel' . ($k + 1)][$j] == 'SC'){
                        if($isScatter == false){
                            $scatterReel[$k] = 1;
                            $scatterReelCount++;
                            $isScatter = true;
                        }  
                        $scatterCount++;                      
                    }
                    
                }
            }
            $freeReel = [[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0]];
            $freeSymbolArr = [0,0,0,0];
            for($i = 0; $i < 4; $i++){
                for($k=0; $k < 5; $k++){
                    $isFree = false;
                    for($j = 0; $j < 3; $j++){
                        if($reels['reel' . ($k + 1)][$j] == $symbolId[$i] || $reels['reel' . ($k + 1)][$j] == $wild){
                            if($isFree == false){
                                $freeReel[$i][$k] = 1;
                                $freeSymbolArr[$i]++;
                            }
                            
                        }
                    }
                }
            }
            $subIntArray = [5,10];
            $subNumber = rand(0,1);      
            $tempWin = $slotSettings->GetGameData($slotSettings->slotId . 'TempTotalWin');
            if($tempWin>0){
                $reeltotalWin = $tempWin;
            }      
            for($k = 0; $k < 5; $k++){
                $totalWin = 0;
                if($k < 3 || $reeltotalWin > 0){
                    for($j = 0; $j < 9; $j++){
                        $bonusMul = 1;
                        $repeatCount = 0;
                        $wildCount = 1;
                        $symbolCorruptAry = [0,0,0,0,0];
                        for($l = 0; $l < 5; $l++){
                            $isSame = false;
                            $symbolCount = 1;
                            if($k == $l){
                                $isSame = true;
                                $wildCount = 3;
                            }else{
                                for($m = 0; $m < 3; $m++){
                                    if($reels['reel'. ($l+1)][$m] == $wild || $reels['reel'. ($l+1)][$m] == $symbolId[$j]){
                                        $isSame = true;
                                        $symbolCorruptAry[$l]++;
                                        if($reels['reel' . ($l+ 1)][$m] == $wild){
                                            $bonusMul = $wildReel[$l - 2];
                                        }
                                    }
                                    
                                }

                            }
                            if($isSame == true){
                                $repeatCount++;
                            }else{
                                break;
                            }
                        }
                        $tempPaytableValue = 0;
                        $tempCorruptValue = 1;
                        
                        for($i=0;$i<count($symbolCorruptAry);$i++){
                            if($symbolCorruptAry[$i] == 0){
                                $symbolCorruptAry[$i] = 1;
                            }
                            $tempCorruptValue = $tempCorruptValue * $symbolCorruptAry[$i];
                        }
                        $tempPaytableValue = $slotSettings->Paytable[$symbolId[$j]][$repeatCount] * $betline * $tempCorruptValue * $bonusMul;
                        $totalWin = $totalWin + $tempPaytableValue * $wildCount;
                    }
                }
                $freeSymbol = 0;
                for($h=0;$h<count($freeSymbolArr);$h++){
                    if($freeSymbolArr[$h] == 4){
                        $freeSymbol = $h + 1;
                    }
                }
                $freeReelPos = -1;
                if($freeSymbol > 0){
                    for($i = 0;$i<5;$i++){
                        if($freeReel[$freeSymbol - 1][$i] == 0){
                            $freeReelPos = $i;
                        }
                    }
                }
                if($freeReelPos == $k){
                    $reelWins[$k] = floor($totalWin / 2) + mt_rand(0, 8);
                }else{
                    if($totalWin > 0){
                        $reelWins[$k] = floor($totalWin / 10) + mt_rand(0, 8);
                    }
                }
                
                if($scatterReel[$k] == 0){
                    if($scatterReelCount >= 2){                        
                        $reelWins[$k] += floor($betline * 60 * $scatterPay[($scatterCount - 2)]);                     
                    }
                }
                // else if($scatterReel[$k] == 1 && $scatterReelCount == 5){             
                //     $reelWins[$k] += floor($betline * 838 * ($scatterCount - 3));              
                // }
                if($reelWins[$k] <= 1){
                    $reelWins[$k] = 1;
                }
                
            }
            return $reelWins;
        }
        public function parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines, $totalbet, $respinReelNo){
            $currentTime = $this->getCurrentTime();
            $proof = [];
            $proof['win_line_data']             = [];
            $proof['symbol_data']               = $result_val['SymbolResult'];
            $proof['symbol_data_after']         = [];
            $proof['extra_data']                = $result_val['ExtraData'];
            $proof['reel_pos_chg']              = $result_val['ReellPosChg'];
            $proof['reel_len_change']           = $result_val['ReelLenChange'];
            $proof['respin_reels']              = $result_val['RespinReels'];
            $proof['bonus_type']                = $result_val['BonusType'];
            $proof['special_award']             = $result_val['SpecialAward'];
            $proof['special_symbol']            = $result_val['SpecialSymbol'];
            $proof['is_respin']                 = $result_val['IsRespin'];
            $proof['fg_times']                  = $result_val['FreeSpin'][0];
            $proof['fg_rounds']                 = floor($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') / 8);
            $proof['next_s_table']              = $result_val['NextSTable'];
            $proof['extend_feature_by_game']    = [];
            $proof['extend_feature_by_game2']   = [];
            $proof['lock_position']         = [];

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
                $log['actionlist'][1]['amount']     = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $slotSettings->GetGameData($slotSettings->slotId . 'BaseWin');
                
                $log['actionlist'][1]['eventtime']  = $currentTime;
                
                $log['detail']['wager']['order_time']   = $currentTime;
                $log['detail']['wager']['end_time']     = $currentTime;
                $log['detail']['wager']['total_win']    = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $slotSettings->GetGameData($slotSettings->slotId . 'BaseWin');

                //$proof['lock_position']         = $result_val['LockPos'];
                $proof['lock_position']         = [];

                $sub_log = [];
                $sub_log['sub_no']              = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                $sub_log['game_type']           = 50;
                $sub_log['rng']                 = $result_val['RngData'];
                $sub_log['multiple']            = strval($result_val['Multiple']);
                $sub_log['win']                 = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                $sub_log['win_line_count']      = $result_val['WinLineCount'];
                $sub_log['win_type']            = $result_val['WinType'];
                $sub_log['proof']               = $proof;
                array_push($log['detail']['wager']['sub'], $sub_log);
            }else{
                $proof['reel_pay']                  = $result_val['ReelPay'];
                $log = [];
                $log['account']                 = $slotSettings->playerId;
                $log['parentacc']               = 'major_prod';
                $log['actionlist']              = [];
                $log['detail']                  = [];
                $bet_action = [];
                $bet_action['action']           = 'bet';
                $bet_action['amount']           = $betline * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
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
                $wager['game_id']               = 230;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                if($respinReelNo > 0){
                    $wager['wager_type']            = 1;
                }else{
                    $wager['wager_type']            = 0;
                }
                $wager['play_bet']              = $totalbet;
                $wager['play_denom']            = 100;
                $wager['bet_multiple']          = $betline;
                $wager['rng']                   = $result_val['RngData'];
                $wager['multiple']              = $result_val['Multiple'];
                $wager['base_game_win']         = $result_val['TotalWin'];
                $wager['win_over_limit_lock']   = 0;
                $wager['game_type']             = 0;
                $wager['win_type']              = $result_val['WinType'];
                $wager['settle_type']           = 0;
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
        public function winLineCalc($slotSettings, $reels, $betline, $lineType, $respinReelNo,$slotEvent,$wildReel){
            $this->winLines = [];
            for($r = 0; $r < 3; $r++){
                $this->findZokbos($reels, $r * 5, $reels['reel1'][$r], 1);
            }
            $isWilds = [0, 0, 0, 0, 0];
            for($r = 1; $r <= 5; $r++){
                for($k = 0; $k < 3; $k++){
                    if($reels['reel' . $r][$k] == 'W'){
                        $isWilds[$r - 1] = 1;
                        break;
                    }
                }
            }
            $OutputWinLines = [];
            $lineCount = 0;
            $totalWin = 0;
            $tempTotalWin = 0;
            for($r = 0; $r < count($this->winLines); $r++){
                $winLine = $this->winLines[$r];
                $bonusMul = 1;
                for($k = 0; $k < $winLine['RepeatCount']; $k++){
                    if($isWilds[$k] > 0){
                        $bonusMul = $bonusMul * $wildReel[$k - 2];
                    }
                }
                $winLineMoney = $slotSettings->Paytable[$winLine['FirstSymbol']][$winLine['RepeatCount']] * $betline * $bonusMul;                                    
                if($winLineMoney > 0 && $winLine['RepeatCount'] >= $respinReelNo){
                    if(!isset($OutputWinLines[$winLine['FirstSymbol']])){
                        if($slotSettings->GetGameData($slotSettings->slotId . 'FreeScatterCount') > 4){
                            $tempMul = $slotSettings->GetGameData($slotSettings->slotId . 'FreeScatterCount') - 4;
                        }else{
                            $tempMul = 1;
                        }
                        
                        $OutputWinLines[$winLine['FirstSymbol']] = [];
                        $OutputWinLines[$winLine['FirstSymbol']]['SymbolId'] = $winLine['FirstSymbol'];
                        $OutputWinLines[$winLine['FirstSymbol']]['LineMultiplier'] = $bonusMul;
                        $OutputWinLines[$winLine['FirstSymbol']]['LineExtraData'] = [0];
                        $OutputWinLines[$winLine['FirstSymbol']]['LineType'] = $lineType;
                        $OutputWinLines[$winLine['FirstSymbol']]['WinPosition'] = [[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0]];
                        $OutputWinLines[$winLine['FirstSymbol']]['NumOfKind'] = $winLine['RepeatCount'];
                        $OutputWinLines[$winLine['FirstSymbol']]['SymbolId'] = $winLine['FirstSymbol'];
                        if($slotEvent == 'freespin'){
                            $OutputWinLines[$winLine['FirstSymbol']]['LinePrize'] = $winLineMoney * $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') * $tempMul;
                        }else{
                            $OutputWinLines[$winLine['FirstSymbol']]['LinePrize'] = $winLineMoney;
                        }
                        
                        $OutputWinLines[$winLine['FirstSymbol']]['WinLineNo'] = $lineCount;
                        $lineCount++;
                    }else{
                        $OutputWinLines[$winLine['FirstSymbol']]['LinePrize'] += $winLineMoney;
                    }
                    $totalWin += $winLineMoney;
                    
                    $winSymbolPoses = explode('~', $winLine['StrWinLine']);
                    for($k = 0; $k < count($winSymbolPoses); $k++){
                        $val = 1;
                        if($reels['reel' . ($winSymbolPoses[$k] % 5 + 1)][floor($winSymbolPoses[$k] / 5)] == 'W'){
                            $val = 2;
                        }
                        $OutputWinLines[$winLine['FirstSymbol']]['WinPosition'][floor($winSymbolPoses[$k] / 5)][$winSymbolPoses[$k] % 5] = $val;
                    }
                    $symbolCount = 0;
                    for($k = 0; $k < 3; $k++){
                        for($j = 0; $j < 5; $j++){
                            if($OutputWinLines[$winLine['FirstSymbol']]['WinPosition'][$k][$j] >= 1){
                                $symbolCount++;
                            }
                        }
                    }
                    $OutputWinLines[$winLine['FirstSymbol']]['SymbolCount'] = $symbolCount;
                }else{
                    
                    if($winLineMoney > 0){
                        $tempTotalWin += $winLineMoney;
                        $slotSettings->SetGameData($slotSettings->slotId . 'TempTotalWin',$tempTotalWin);
                    }
                }
            }
            $result = [];
            $result['totalWin'] = $totalWin;
            $result['OutputWinLines'] = $OutputWinLines;
            return $result;
        }
        public function findZokbos($reels, $strLineWin, $firstSymbol, $repeatCount){
            $wild = 'W';
            $bPathEnded = true;
            if($repeatCount < 5){
                for($r = 0; $r < 3; $r++){
                    if($firstSymbol == $reels['reel'.($repeatCount + 1)][$r] || $reels['reel'.($repeatCount + 1)][$r] == $wild){
                        $this->findZokbos($reels, $strLineWin . '~' . ($repeatCount + $r * 5), $firstSymbol, $repeatCount + 1);
                        $bPathEnded = false;
                    }
                }
            }
            if($bPathEnded == true){
                if($repeatCount >= 3){
                    $winLine = [];
                    $winLine['FirstSymbol'] = $firstSymbol;
                    $winLine['RepeatCount'] = $repeatCount;
                    $winLine['StrWinLine'] = $strLineWin;
                    array_push($this->winLines, $winLine);
                }
            }
        }
    }

}

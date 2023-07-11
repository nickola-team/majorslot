<?php 
namespace VanguardLTE\Games\ThorCQ9
{
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
            }// changed by game developer
            
            $user = \VanguardLTE\User::lockForUpdate()->find($userId);
            $credits = $userId == 1 ? $request->action === 'doInit' ? 5000 : $user->balance : null;
            $slotSettings = new SlotSettings($game, $userId, $credits);
            $find = array("#i", "#b", "#s", "#f", "#l");
            // $paramData = trim(file_get_contents('php://input'));
            $paramData = json_decode(str_replace($find, "", trim(file_get_contents('php://input'))), true);
            $paramData = $paramData['gameData'];
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 11}],"msg": null}');
                    $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetBalance().'],"evt": 1}');                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'AwardWins', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentAwardWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'AwardLevel', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentLevel', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ScatterWin', 0);
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
                            $result_val['DefaultDenomIdx'] = 4;
                            $result_val['MaxBet'] = $slotSettings->Bet[count($slotSettings->Bet) - 1];
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
                            $result_val['PromotionData'] = $slotSettings->getPromotionData();
                            $result_val['IsShowFreehand'] = false;
                            $result_val['IsAllowFreehand'] = false;
                            $result_val['FeedbackURL'] = null;
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
                            $result_val['BGContext'] = [["8bNWwaomncqeRHfqWLv4gxqGtxThke+7sRUVZoAM8jXsnVT2vqC8eKc55x5GkcTfepmCYmgnSCEVzkGg8+ak42t5C/DuFOPvVpnp+pYVpf/6YkanBpH6HeJEmN2BsP1doojzLlNd8FoKe5GoC/I/1bpZnH/yo4Lj0nkUbqcCxcE9LBbQ45Hqbp53vhQyUXytByJ0sv4vcafTixqfRibQf3D6SyfoNXNX9brRY2WfC7GUo3QHpF5SWPCF+Qe9nWCJ7gDrRaP7QrUbFG+F5qVQeOlmHaEZIAZUJykgnEhrQ1n7tSZd3ILyVvVrtGHEXNaX7wwbkoLwaNeqlKa1O27Y3NlfSFBLwT8laXyJiE7NatAhRPdkqCECA7wZZL9wORVSAu9DgR8qt7bpPZ8G",
                            "VJ2iLiFVg23MBN0mD39GnXoEZKJxUThCtd1k+z+pcGyv/Iu1wRPFXGS1EvuXIMU+bDhesj+myqS/FbP8Kd0MhIkVM8kW7KWQYmee5EwQA5MjepaR+2tSwcYA7x5v11+W49TJoG5vqQy13J21Y+RnPuKVPTfYjqiFv2JDh3XnO1XtmTCT6h/tLKsQr2UUHDNcVVHyaLHeznCsNrramRbC7KYj5nD/+6NsUEAsudFwIPOQ9YV8YtPmVcaVmxjct50tG+ltKevdkDapi05w+tIr5yli46jkq+NlnqIy73xRA7iJ1RR0ck+PuhPH1rNRWy7MJ4OL2zOjc1oZVoYfMBRiTQxs9KATDXgUi7HLJELDiCOcfz/0/pXCfgmI7o+Uu+jw1rAeELWQYPEHCNL86WqQqOFzYMKBzIxaJEHC3ocMj2NxHDj+GEp9HG4zX6I=",
                            "TuWaLOYeQXxEmlfSmVxkR5HWd75T8Uz2dEqK5nTA4At5VHanuQ++FW1Jr7LoiHysQ+BZYpzzLUxDjoPJqCxeV6xYfuh3OGHO4ydz3OthIwoGf/2LnkkV/CaW9L5YooGofIy+6j5EGuky282Ksh9Snj5Ue0Bpo6JPfvvaWP1lXK/zHj7Zm5Px2/cXSIh8mxnR7SrlaCr/gi1tFMhGXu8CDqwrDD0iZzBACl3MpSHvCDfojBWlTaFQSY6yp5m8moMwVTRItEFDbFFATUQpCskTyZuFWWA8Z4KzHCI0Aykk46754C9ee7uWjjUo1YMlNCpBgiZbV52Q+yjZbrwKGfBRuS6NcBk0zMVVQB8/utcAusgf5Adrm9OOJxGvw6C7iYY6Zr5u9N1Vb4MLJugYpZINK5T4mVJgZbQsVdKBcpM60303C7OBwyI5vH/v7T0=",
                            "npXQ0se0sVgASnGqumV1oCB5/paurEkKwaBMelzIsuHgzDjA9hxY4sDneGjRSPVRrjwwJ3tkZSREdq4zKRn0JPl5EQeqWy2U+L41ALqeqmnSMpX3BGsTl/mYaWRU3A/HJYFetNuaXRs2LtZXfxnjx4MvyQLP38t9l5tP0bnG7Zx8OMB1Jtt3JXZ9K2uYcOx1utDhMFSa8J7pct+PKUcQFV4/HOxakiKUVgQnHarrMfho4sAxi07qZo4ZSiwZlbKKztUw65DgPGOq3wPgS8JzkBWvLtRZrkF3s8KywcucZCOr0kWbzDf27lcdl0Lot9x33DzhKkbVh8wo0XJzGdzzMSv4eYG0D2jjDQe6aEQc/xHuYGwBzWsEUEX+SHS98OEps76c0GEe7NBgtqSjJYQIUyaz46BetNvIQsmJEEcxCzG18+Nz8mcF767TXC4=",
                            "bZ197MjYWR94RUPPq9w860hrEbnKaMH6d/BWEPRHm3Is1SLP6gWhwG5b13vNfhbNrIo97Y1Gpsg4RIJ0Ep6h7B58kGEa5ac9PnJxIxf87XUEEJ3mzsZoIEdiyXg3WSgaOWP+dMU525CtanqVnieAV6IdLH8TGXZd2BP2uPXW79ZqeIRIZT9Wi1mD1p38V1Y58ZABp0GeEv4bIMsknIePbCKotboSm4JMTnEBo+fXk0PAVbstIVAz/j3r1IwD2mktb5MXCZhxGCWEyU804eSpTfPcbi4gn78gLcFmopXB40fgxtCQ8idlrSEUvH6/iAeFx0FvwF1/77s461nmOlo1efJboIE9JG/nD4Q507QGFfmVKK3bkgmqJHwkR9fZXEPtRFM1XJdkQfpQksnh320B6AjNDc4IoO9IxF0qSQ=="]];
                            $result_val['FGStripCount'] = 1;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["lXyET9atp9Iz1dw0Dw0aQEIAmLSv0yuQoTXZg3xHIuykqoIMxgcQKiG0b4JpLKNxf6QSDNK3Q4zNrzFuEMM2x1ODKawCg+QqiO8CujK0rLgGtspfHsQJ+Kjuo9/9kC2tJADtcbxBa+aac/f22bVOBWNjPMpi4fYr6QbAa2Ns8APT9SEkAOl4sC/PmC2PhygnIeYnGygLg7YIZ/KX8CeNDMXJN1Kuqv8WTMKhWfRw50V2lyRHTql9qqCjYzSl/NyZieICO3Jfrv8fIIvhpK+aJJRVBFAnsbo9cpmlQppydwGZumuwCRhfR3uN+anqr1SMNrdE8J36E76UNSMNrYrmG5FAUIs2Fqhj5+JP3qjdj2RXgx/ksKPCCGxNAcIWURqF6vmg/EfwAdnNgq0fa5R0e4um5VuGIqH0wjih0Q==",
                            "ddZGYoDYXZrurc2SGXcEIh691KDuuY6znqRSjCmlxkjjn8itcsyCQ/PZQsGqI2Crl/PrqoCdnxEw09G8J9eIcLiCHg+V+81W9QIpnDYDm8TDE9iYCWQfFtj904jteLn1lJo4YaxU2h902FK1wzEyAGbFXkImfi5uYeFhU2b0YfwxMXVCaTA7lZw3kpEsCwRtDC5FiD4O7DFv1nr/xy9eFSwqxNZw8zwJgE5ZH/W6bGRXDi/qLhgqYHW9P3uH2zt/+aMO/9AevFW3er9oVwi0y9ZK3AS1jCqTqhoqaQ8MNybfy49D2oliT4O+LDY7fDvY989bH3pWO7okfowdCpQsqFlqVVt6R/pij1S1uOGcpWKC2RSKUu0X72W/6Xzjhta2VA2IWYtLp3cMbTfSUd3GT7WpWIuo4XnJQtCjHA58l8605h81qeaT1nlMISE=",
                            "uQ9pO2YplYQlaULJ8nNjqgZdLSwLXL650OM183wt+76LrED1CAPU7i6Up5N0IbUrxjZYcenjUZUnCyeuN6X2LymBs1+1o+flJMcWNlkynLeSbMVqZBs9N/RneKYvFvL5bRfO7BiGBK92mOqReX/rNEfwZ2GERSD/Z7MM5ZtcdJrpvVteu0RRhsadLix3W0bL20Vh4JWauiNjXw9EPETdSrLIzZ8dbpwWTxyMQF7qrUaAV6zKTuRjlSe58Xk9oifgF66sMc25G0cMtREF7Gb5R106rwetR5X/v42UkKDD9I6cugypif/BJaQ6aXVPiIBEWtk2Ee3MsSUJVK8t15963w2mHT71opg4Mo97TRLYQBzzSBtx07rPYT2lp4JP0RTA4BLu50A/pb4lwBznBR2IJPDXGBXsBKeuTxTmZL6llNyGaxr6cMC+D9KmHX0hZyxYNeOe3gZc0MaQphsc",
                            "EL9JnCevqMX6rX14eeUzS+gPRn8uhg/7E80Fa5ThDWQ/HcmA96pA5pcHfRWoAj0t4NeZfsNEYnJH0kaj8jXw17XUb3xdSj4om0QBeywvG3WtrH7PhJWNx3fJLKJx3Wu/OLXquHA8Ue59gnlEANdH+uhcVMr5lc1lent/OgA01rddcvnirdLzRa7CKDaAy535+ozyN8YEQOgax/czv3rSS+mQVy5G2CF4OlUtzVsovO8lJ8X/iqKmCjLf/5uSge09NdsjABHJlXJHK17MpT76BtCcowMBu3AmT/4eLBNMUwKLSehAW/L+0Xzu+HLw9a6PJpPeH+DtEwh49JjmB06shj24VG9kA2fSsADQKFqY/7FP9sP9OQGmJMEpvGqN48TunL4Y0YnWOVH7vnROcCkfPpswDvmfjA5po5moM+dAvGkyOlcVh8dPBvIZcsc=",
                            "j6zjuvtE2npDAMy5RDgBouIDxbyhkoL+t3ASZUp2T+9medKg2DfnRlmNTiA0O0dGQ3hWuxxLKMe4b7Wiyysz4uTl9tMz4H1pIk3p9qv2fDbEh9mdLNbdgZv7cIfh8F1aJM6R7KBdlJLo8lmCkm1FaoPgISsNW9/A3ahwlqXU3EApeMT3oQiuae1w8lYDW3ordPdzgmG3wuo0kl+3sog7IYWGreHHwcAcfbIWww02+vfImGunxGyE6VOs/RWNxAjn8kQtsQNuUvMPmSV3HTp5Ejric+6Wl7ALlIlkjBrPObeeOubxVDaYH31r+2xRzmhox3zytWrp9fTi70lcPuQ69b/le0rcdxFYz/RXZsFMw6717eTJCxyQUvkWdyJ8G5GAVtGWhGB8WVj4keuLph0rjmVJtyKUoguJB1j9bg=="]];
                        }else if($packet_id == 31 || $packet_id == 42){
                            if($packet_id == 31){
                                $betline = $gameData->PlayBet;// * $gameData->MiniBet;
                                $lines = $gameData->PlayLine;
                            }else if($packet_id == 42){
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                                $lines = 1;
                            }
                            if($packet_id == 42 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame')){
                                $slotEvent['slotEvent'] = 'freespin';
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                            }else{
                                $slotEvent['slotEvent'] = 'bet';
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'ScatterWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'AwardWins', []);
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentAwardWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'AwardLevel', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentLevel', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeScatterCount', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                                $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                $slotSettings->SetBalance(-1 * ($betline * $lines * $gameData->MiniBet), $slotEvent['slotEvent']);
                                $slotSettings->UpdateJackpots($betline * $lines * $gameData->MiniBet);
                                $_sum = ($betline * $lines * $gameData->MiniBet) / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '578' . substr($roundstr, 3, 9);
                                $slotSettings->SetGameData($slotSettings->slotId . 'GamePlaySerialNumber', $roundstr);
                            }

                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines);

                            $slotSettings->SaveGameData();
                        }else if($packet_id == 32 || $packet_id == 41){
                            $result_val['ErrorCode'] = 0;
                            // if($type == 3){

                            //     $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance() - $slotSettings->GetGameData($slotSettings->slotId . 'ScatterWin'));
                            // }
                            if($packet_id == 41){
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance() - $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'));
                                $result_val['PlayerBet'] = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                                $result_val['AccumlateWinAmt'] = $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                                $result_val['AccumlateJPAmt'] = 0;
                                $result_val['ScatterPayFromBaseGame'] = $slotSettings->GetGameData($slotSettings->slotId . 'ScatterWin');
                                $result_val['MaxRound'] = 10;
                                $result_val['AwardRound'] = $slotSettings->GetGameData($slotSettings->slotId . 'AwardLevel');
                                $result_val['CurrentRound'] = floor($slotSettings->GetGameData($slotSettings->slotId . 'CurrentLevel'));
                                $result_val['MaxSpin'] = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames');
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
                            $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines);
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
        public function generateResult($slotSettings, $result_val, $slotEvent, $betline, $lines){
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, $betline * $lines, $lines);
            $winType = $_spinSettings[0];
            $_winAvaliableMoney = $_spinSettings[1];
            $isFreeScatter = false;
            if($slotEvent == 'freespin'){
                $leftFreeSpin = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') - $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                if($leftFreeSpin == mt_rand(0, 2) && $slotSettings->GetGameData($slotSettings->slotId . 'FreeScatterCount') == 0){
                    $isFreeScatter = true;
                }
            }            
            // $winType = 'none';
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);
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
                $bonusMul = $slotSettings->GetGameData($slotSettings->slotId . 'BonusMul');
                
                $awardMoney = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentAwardWin');
                $OutputWinChgLines = [];
                $ReellPosChg = 0;
                $lockPos = [];
                $winResults = $this->winLineCalc($slotSettings, $reels, $bonusMul, $betline, 0);
                $totalWin = $winResults['totalWin'];
                $OutputWinLines = $winResults['OutputWinLines'];
                
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
                        }
                    }
                }
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
                $scatterWin = 0;
                if($scattersCount >= 3){
                    $scatterPay = [0,0,0,3,20,50];
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
            $isEnd = true;
            $isTriggerFG = false;
            $result_val['Multiple'] = 0;
            if($freespinNum > 0){
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', $bonusMul);
                $awardwins = $slotSettings->GetGameData($slotSettings->slotId . 'AwardWins');
                array_push($awardwins, $newAwardMoney);
                $slotSettings->SetGameData($slotSettings->slotId . 'AwardWins', $awardwins);
                $isTriggerFG = true;
                if($slotEvent != 'freespin'){                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'AwardLevel', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentLevel', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespinNum);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentAwardWin', $newAwardMoney);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeScatterCount', 0);
                }
                $isEnd = false;
            }
            if($slotEvent == 'freespin'){
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);


                $result_val['AccumlateWinAmt'] = $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                $result_val['AccumlateJPAmt'] = 0;
                $result_val['ScatterPayFromBaseGame'] = $slotSettings->GetGameData($slotSettings->slotId . 'ScatterWin');
                if($freespinNum > 0){
                    $result_val['RetriggerAddRound'] = 1;
                    $slotSettings->SetGameData($slotSettings->slotId . 'AwardLevel', $slotSettings->GetGameData($slotSettings->slotId . 'AwardLevel') + 1);
                }else{
                    $result_val['RetriggerAddRound'] = 0;
                }
                $result_val['AwardRound'] = $slotSettings->GetGameData($slotSettings->slotId . 'AwardLevel');
                $result_val['CurrentRound'] = floor($slotSettings->GetGameData($slotSettings->slotId . 'CurrentLevel'));
                $result_val['AwardSpinTimes'] = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames');
                $result_val['CurrentSpinTimes'] = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                $result_val['RetriggerAddSpins'] = 0;
                $result_val['LockPos'] = $lockPos;
                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame')){
                    $isEnd = false;
                }else if($slotSettings->GetGameData($slotSettings->slotId . 'AwardLevel') > $slotSettings->GetGameData($slotSettings->slotId . 'CurrentLevel')){                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentLevel', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentLevel') + 1);
                    $awardwins = $slotSettings->GetGameData($slotSettings->slotId . 'AwardWins');
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentAwardWin', $awardwins[$slotSettings->GetGameData($slotSettings->slotId . 'CurrentLevel') - 1]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 5);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $isEnd = false;
                }
            }else{
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
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
            if($slotEvent != 'freespin'){
                $result_val['GamePlaySerialNumber'] = $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'); //449660471
            }
            $result_val['RngData'] = $rngData;
            $result_val['SymbolResult'] = [implode(',', $symbolResult[0]), implode(',', $symbolResult[1]), implode(',', $symbolResult[2])];
            if($freespinNum > 0){
                $result_val['WinType'] = 2;
            }else if($totalWin > 0){
                $result_val['WinType'] = 1;
            }else{
                $result_val['WinType'] = 0;
            }
            $result_val['BaseWin'] = $totalWin;
            $result_val['TotalWin'] = $totalWin;                            
            
            if($isTriggerFG){
                $result_val['IsTriggerFG'] = $isTriggerFG;
                $result_val['NextModule'] = 20;
            }else{
                // $result_val['NextModule'] = 0;
            }
            $result_val['ExtraDataCount'] = 1;
            $result_val['ExtraData'] = [0];
            $result_val['BonusType'] = 0;
            $result_val['SpecialAward'] = 0;
            $result_val['SpecialSymbol'] = 0;
            $result_val['ReelLenChange'] = [];
            $result_val['ReelPay'] = [];
            $result_val['IsRespin'] = false;
            $result_val['FreeSpin'] = [$freespinNum];
            $result_val['RespinReels'] = [0,0,0,0,0];
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

            $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
            if($isEnd == true){
                $slotSettings->SaveLogReport(json_encode($gamelog), $betline * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet'), $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'));
            }

            if($slotEvent != 'freespin' && $freespinNum > 0){
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
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
            $proof['reel_pay']                  = $result_val['ReelPay'];
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
                $sub_log['sub_no']              = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
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
                $wager['game_id']               = 89;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = $betline * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
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
        public function winLineCalc($slotSettings, $reels, $bonusMul, $betline, $lineType){
            $this->winLines = [];
            for($r = 0; $r < 3; $r++){
                if($reels['reel1'][$r] != 'SC1'){
                    $this->findZokbos($reels, $r * 5, $reels['reel1'][$r], 1);
                }
            }
            $OutputWinLines = [];
            $lineCount = 0;
            $totalWin = 0;
            for($r = 0; $r < count($this->winLines); $r++){
                $winLine = $this->winLines[$r];
                $winLineMoney = $slotSettings->Paytable[$winLine['FirstSymbol']][$winLine['RepeatCount']] * $betline * $bonusMul;                                    
                if($winLineMoney > 0){
                    if(!isset($OutputWinLines[$winLine['FirstSymbol']])){
                        $OutputWinLines[$winLine['FirstSymbol']] = [];
                        $OutputWinLines[$winLine['FirstSymbol']]['SymbolId'] = $winLine['FirstSymbol'];
                        $OutputWinLines[$winLine['FirstSymbol']]['LineMultiplier'] = $bonusMul;
                        $OutputWinLines[$winLine['FirstSymbol']]['LineExtraData'] = [0];
                        $OutputWinLines[$winLine['FirstSymbol']]['LineType'] = $lineType;
                        $OutputWinLines[$winLine['FirstSymbol']]['WinPosition'] = [[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0]];
                        $OutputWinLines[$winLine['FirstSymbol']]['NumOfKind'] = $winLine['RepeatCount'];
                        $OutputWinLines[$winLine['FirstSymbol']]['SymbolId'] = $winLine['FirstSymbol'];
                        $OutputWinLines[$winLine['FirstSymbol']]['LinePrize'] = $winLineMoney;
                        $OutputWinLines[$winLine['FirstSymbol']]['WinLineNo'] = $lineCount;
                        $lineCount++;
                    }else{
                        $OutputWinLines[$winLine['FirstSymbol']]['LinePrize'] += $winLineMoney;
                        // $OutputWinLines[$winLine['FirstSymbol']]['WinLineNo']++;
                    }
                    $totalWin += $winLineMoney;
                    
                    $winSymbolPoses = explode('~', $winLine['StrWinLine']);
                    for($k = 0; $k < count($winSymbolPoses); $k++){
                        $OutputWinLines[$winLine['FirstSymbol']]['WinPosition'][floor($winSymbolPoses[$k] / 5)][$winSymbolPoses[$k] % 5] = 1;
                        if($reels['reel' . ($winSymbolPoses[$k] % 5 + 1)][floor($winSymbolPoses[$k] / 5)] == 'W'){
                            $OutputWinLines[$winLine['FirstSymbol']]['WinPosition'][floor($winSymbolPoses[$k] / 5)][$winSymbolPoses[$k] % 5] = 2;
                        }
                    }
                    $symbolCount = 0;
                    for($k = 0; $k < 3; $k++){
                        for($j = 0; $j < 5; $j++){
                            if($OutputWinLines[$winLine['FirstSymbol']]['WinPosition'][$k][$j] > 0){
                                $symbolCount++;
                            }
                        }
                    }
                    $OutputWinLines[$winLine['FirstSymbol']]['SymbolCount'] = $symbolCount;
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

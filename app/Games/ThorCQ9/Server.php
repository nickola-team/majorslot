<?php 
namespace VanguardLTE\Games\ThorCQ9
{
    class Server
    {
        public $demon = 1;
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
            $originalbet = 2;
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
                                array_push($denomDefine, $initDenom);
                                array_push($betButtons, $slotSettings->Bet[$k] * $this->demon);
                            }
                            $result_val['DenomDefine'] = $denomDefine;
                            $result_val['BetButton'] = $betButtons;
                            $result_val['DefaultDenomIdx'] = 4;
                            $result_val['MaxBet'] = $slotSettings->Bet[count($slotSettings->Bet) - 4] * $this->demon;
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
                            $result_val['PromotionData'] = null; //$slotSettings->getPromotionData();
                            $result_val['IsShowFreehand'] = false;
                            $result_val['IsAllowFreehand'] = false;
                            $result_val['FeedbackURL'] = '/feedback/?token=' . auth()->user()->api_token;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['FreeTicketList'] = null;
                            $result_val['DenomMultiple'] = $initDenom * $this->demon;
                            $result_val['FreeSpinLeftTimesInfoList'] = null;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            $result_val['BGContext'] = [["8bNWwaomncqeRHfqWLv4gxqGtxThke+7sRUVZoAM8jXsnVT2vqC8eKc55x5GkcTfepmCYmgnSCEVzkGg8+ak42t5C/DuFOPvVpnp+pYVpf/6YkanBpH6HeJEmN2BsP1doojzLlNd8FoKe5GoC/I/1bpZnH/yo4Lj0nkUbqcCxcE9LBbQ45Hqbp53vhQyUXytByJ0sv4vcafTixqfRibQf3D6SyfoNXNX9brRY2WfC7GUo3QHpF5SWPCF+Qe9nWCJ7gDrRaP7QrUbFG+F5qVQeOlmHaEZIAZUJykgnEhrQ1n7tSZd3ILyVvVrtGHEXNaX7wwbkoLwaNeqlKa1O27Y3NlfSFBLwT8laXyJiE7NatAhRPdkqCECA7wZZL9wORVSAu9DgR8qt7bpPZ8G",
                            "VJ2iLiFVg23MBN0mD39GnXoEZKJxUThCtd1k+z+pcGyv/Iu1wRPFXGS1EvuXIMU+bDhesj+myqS/FbP8Kd0MhIkVM8kW7KWQYmee5EwQA5MjepaR+2tSwcYA7x5v11+W49TJoG5vqQy13J21Y+RnPuKVPTfYjqiFv2JDh3XnO1XtmTCT6h/tLKsQr2UUHDNcVVHyaLHeznCsNrramRbC7KYj5nD/+6NsUEAsudFwIPOQ9YV8YtPmVcaVmxjct50tG+ltKevdkDapi05w+tIr5yli46jkq+NlnqIy73xRA7iJ1RR0ck+PuhPH1rNRWy7MJ4OL2zOjc1oZVoYfMBRiTQxs9KATDXgUi7HLJELDiCOcfz/0/pXCfgmI7o+Uu+jw1rAeELWQYPEHCNL86WqQqOFzYMKBzIxaJEHC3ocMj2NxHDj+GEp9HG4zX6I=",
                            "TuWaLOYeQXxEmlfSmVxkR5HWd75T8Uz2dEqK5nTA4At5VHanuQ++FW1Jr7LoiHysQ+BZYpzzLUxDjoPJqCxeV6xYfuh3OGHO4ydz3OthIwoGf/2LnkkV/CaW9L5YooGofIy+6j5EGuky282Ksh9Snj5Ue0Bpo6JPfvvaWP1lXK/zHj7Zm5Px2/cXSIh8mxnR7SrlaCr/gi1tFMhGXu8CDqwrDD0iZzBACl3MpSHvCDfojBWlTaFQSY6yp5m8moMwVTRItEFDbFFATUQpCskTyZuFWWA8Z4KzHCI0Aykk46754C9ee7uWjjUo1YMlNCpBgiZbV52Q+yjZbrwKGfBRuS6NcBk0zMVVQB8/utcAusgf5Adrm9OOJxGvw6C7iYY6Zr5u9N1Vb4MLJugYpZINK5T4mVJgZbQsVdKBcpM60303C7OBwyI5vH/v7T0=",
                            "npXQ0se0sVgASnGqumV1oCB5/paurEkKwaBMelzIsuHgzDjA9hxY4sDneGjRSPVRrjwwJ3tkZSREdq4zKRn0JPl5EQeqWy2U+L41ALqeqmnSMpX3BGsTl/mYaWRU3A/HJYFetNuaXRs2LtZXfxnjx4MvyQLP38t9l5tP0bnG7Zx8OMB1Jtt3JXZ9K2uYcOx1utDhMFSa8J7pct+PKUcQFV4/HOxakiKUVgQnHarrMfho4sAxi07qZo4ZSiwZlbKKztUw65DgPGOq3wPgS8JzkBWvLtRZrkF3s8KywcucZCOr0kWbzDf27lcdl0Lot9x33DzhKkbVh8wo0XJzGdzzMSv4eYG0D2jjDQe6aEQc/xHuYGwBzWsEUEX+SHS98OEps76c0GEe7NBgtqSjJYQIUyaz46BetNvIQsmJEEcxCzG18+Nz8mcF767TXC4=",
                            "bZ197MjYWR94RUPPq9w860hrEbnKaMH6d/BWEPRHm3Is1SLP6gWhwG5b13vNfhbNrIo97Y1Gpsg4RIJ0Ep6h7B58kGEa5ac9PnJxIxf87XUEEJ3mzsZoIEdiyXg3WSgaOWP+dMU525CtanqVnieAV6IdLH8TGXZd2BP2uPXW79ZqeIRIZT9Wi1mD1p38V1Y58ZABp0GeEv4bIMsknIePbCKotboSm4JMTnEBo+fXk0PAVbstIVAz/j3r1IwD2mktb5MXCZhxGCWEyU804eSpTfPcbi4gn78gLcFmopXB40fgxtCQ8idlrSEUvH6/iAeFx0FvwF1/77s461nmOlo1efJboIE9JG/nD4Q507QGFfmVKK3bkgmqJHwkR9fZXEPtRFM1XJdkQfpQksnh320B6AjNDc4IoO9IxF0qSQ=="]];
                            $result_val['FGStripCount'] = 1;
                            $result_val['FGContext'] = [["lXyET9atp9Iz1dw0Dw0aQEIAmLSv0yuQoTXZg3xHIuykqoIMxgcQKiG0b4JpLKNxf6QSDNK3Q4zNrzFuEMM2x1ODKawCg+QqiO8CujK0rLgGtspfHsQJ+Kjuo9/9kC2tJADtcbxBa+aac/f22bVOBWNjPMpi4fYr6QbAa2Ns8APT9SEkAOl4sC/PmC2PhygnIeYnGygLg7YIZ/KX8CeNDMXJN1Kuqv8WTMKhWfRw50V2lyRHTql9qqCjYzSl/NyZieICO3Jfrv8fIIvhpK+aJJRVBFAnsbo9cpmlQppydwGZumuwCRhfR3uN+anqr1SMNrdE8J36E76UNSMNrYrmG5FAUIs2Fqhj5+JP3qjdj2RXgx/ksKPCCGxNAcIWURqF6vmg/EfwAdnNgq0fa5R0e4um5VuGIqH0wjih0Q==",
                            "ddZGYoDYXZrurc2SGXcEIh691KDuuY6znqRSjCmlxkjjn8itcsyCQ/PZQsGqI2Crl/PrqoCdnxEw09G8J9eIcLiCHg+V+81W9QIpnDYDm8TDE9iYCWQfFtj904jteLn1lJo4YaxU2h902FK1wzEyAGbFXkImfi5uYeFhU2b0YfwxMXVCaTA7lZw3kpEsCwRtDC5FiD4O7DFv1nr/xy9eFSwqxNZw8zwJgE5ZH/W6bGRXDi/qLhgqYHW9P3uH2zt/+aMO/9AevFW3er9oVwi0y9ZK3AS1jCqTqhoqaQ8MNybfy49D2oliT4O+LDY7fDvY989bH3pWO7okfowdCpQsqFlqVVt6R/pij1S1uOGcpWKC2RSKUu0X72W/6Xzjhta2VA2IWYtLp3cMbTfSUd3GT7WpWIuo4XnJQtCjHA58l8605h81qeaT1nlMISE=",
                            "uQ9pO2YplYQlaULJ8nNjqgZdLSwLXL650OM183wt+76LrED1CAPU7i6Up5N0IbUrxjZYcenjUZUnCyeuN6X2LymBs1+1o+flJMcWNlkynLeSbMVqZBs9N/RneKYvFvL5bRfO7BiGBK92mOqReX/rNEfwZ2GERSD/Z7MM5ZtcdJrpvVteu0RRhsadLix3W0bL20Vh4JWauiNjXw9EPETdSrLIzZ8dbpwWTxyMQF7qrUaAV6zKTuRjlSe58Xk9oifgF66sMc25G0cMtREF7Gb5R106rwetR5X/v42UkKDD9I6cugypif/BJaQ6aXVPiIBEWtk2Ee3MsSUJVK8t15963w2mHT71opg4Mo97TRLYQBzzSBtx07rPYT2lp4JP0RTA4BLu50A/pb4lwBznBR2IJPDXGBXsBKeuTxTmZL6llNyGaxr6cMC+D9KmHX0hZyxYNeOe3gZc0MaQphsc",
                            "EL9JnCevqMX6rX14eeUzS+gPRn8uhg/7E80Fa5ThDWQ/HcmA96pA5pcHfRWoAj0t4NeZfsNEYnJH0kaj8jXw17XUb3xdSj4om0QBeywvG3WtrH7PhJWNx3fJLKJx3Wu/OLXquHA8Ue59gnlEANdH+uhcVMr5lc1lent/OgA01rddcvnirdLzRa7CKDaAy535+ozyN8YEQOgax/czv3rSS+mQVy5G2CF4OlUtzVsovO8lJ8X/iqKmCjLf/5uSge09NdsjABHJlXJHK17MpT76BtCcowMBu3AmT/4eLBNMUwKLSehAW/L+0Xzu+HLw9a6PJpPeH+DtEwh49JjmB06shj24VG9kA2fSsADQKFqY/7FP9sP9OQGmJMEpvGqN48TunL4Y0YnWOVH7vnROcCkfPpswDvmfjA5po5moM+dAvGkyOlcVh8dPBvIZcsc=",
                            "j6zjuvtE2npDAMy5RDgBouIDxbyhkoL+t3ASZUp2T+9medKg2DfnRlmNTiA0O0dGQ3hWuxxLKMe4b7Wiyysz4uTl9tMz4H1pIk3p9qv2fDbEh9mdLNbdgZv7cIfh8F1aJM6R7KBdlJLo8lmCkm1FaoPgISsNW9/A3ahwlqXU3EApeMT3oQiuae1w8lYDW3ordPdzgmG3wuo0kl+3sog7IYWGreHHwcAcfbIWww02+vfImGunxGyE6VOs/RWNxAjn8kQtsQNuUvMPmSV3HTp5Ejric+6Wl7ALlIlkjBrPObeeOubxVDaYH31r+2xRzmhox3zytWrp9fTi70lcPuQ69b/le0rcdxFYz/RXZsFMw6717eTJCxyQUvkWdyJ8G5GAVtGWhGB8WVj4keuLph0rjmVJtyKUoguJB1j9bg=="]];
                        }else if($packet_id == 31 || $packet_id == 42){
                            $lines = 30;
                            if($packet_id == 31){
                                $betline = $gameData->PlayBet;// * $gameData->MiniBet;
                            }else if($packet_id == 42){
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            }
                            if($packet_id == 42 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                                $slotEvent['slotEvent'] = 'freespin';
                            }else{
                                $slotEvent['slotEvent'] = 'bet';
                                $pur_level = -1;
                                if($gameData->ReelPay > 0){
                                    $pur_level = 0;
                                }
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                                $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur_level);
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', ($betline /  $this->demon));
                                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 50);
                                $slotSettings->SetBet();
                                $allBet = ($betline /  $this->demon) * $lines;
                                $isBuyFreespin = false;
                                if($pur_level == 0){
                                    $allBet = $betline * 2344;
                                    $isBuyFreespin = true;
                                }
                                $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                                $_sum = $allBet / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent'], $isBuyFreespin);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '567' . substr($roundstr, 3, 7);
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
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                        // FreeSpin Balance add
                        $slotEvent['slotEvent'] = 'freespin';
                        $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                        $lines = 30;
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
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, ($betline /  $this->demon) * $lines, $lines);
                $winType = $_spinSettings[0];
            if($slotEvent == 'freespin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
            }else{
                
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $winType = 'bonus';
                }
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, ($betline /  $this->demon) * $lines, $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin'));
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
            if(isset($stack['SpecialAward']) && $stack['SpecialAward'] > 0){
                $stack['SpecialAward'] = $stack['SpecialAward'] / $originalbet * $betline;
            }
            $awardSpinTimes = 0;
            $currentSpinTimes = 0;
            if($slotEvent == 'freespin'){
                $awardSpinTimes = $stack['AwardSpinTimes'];    
                $currentSpinTimes = $stack['CurrentSpinTimes'];    
            }
            foreach($stack['udsOutputWinLine'] as $index => $value){
                if($value['LinePrize'] > 0){
                    $value['LinePrize'] = $value['LinePrize'] / $originalbet * $betline;
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
                $slotSettings->SetBalance($totalWin / $this->demon);
                if($winType == 'bonus'){
                    $slotSettings->SetBank('bonus', -1 * $totalWin / $this->demon);   
                }else{
                    $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin / $this->demon);
                }
                //$slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + ($totalWin));
            }

            if($freespinNum > 0){
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
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes && $stack['AwardRound'] == $stack['CurrentRound']){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $isState = true;
                }
            }
            

            $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
            if($isState == true){
                $allBet = ($betline /  $this->demon) * $lines;
                if($slotEvent == 'freespin' && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                    $allBet = $betline * 2344;
                }
                $slotSettings->SaveLogReport(json_encode($gamelog), $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon, $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
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
                $allBet = $betline * 2344;
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
            $proof['fg_times']                  = $result_val['FreeSpin'];
            if(isset($result_val['CurrentRound'])){
                $proof['fg_rounds']                 = $result_val['CurrentRound'];
            }else{
                $proof['fg_rounds']                 = 0;
            }
            $proof['next_s_table']              = $result_val['NextSTable'];
            $proof['extend_feature_by_game']    = [];
            $proof['extend_feature_by_game2']   = [];
            if(isset($result_val['ExtendFeatureByGame2'])){
                foreach($result_val['ExtendFeatureByGame2'] as $item){
                    $newItem = [];
                    $newItem['name'] = $item['Name'];
                    if(isset($item['Value'])){
                        $newItem['value'] = $item['Value'];
                    }
                    $proof['extend_feature_by_game2'][] = $newItem;
                }
            }

            foreach( $result_val['udsOutputWinLine'] as $index => $outWinLine) 
            {
                $lineData = [];
                $lineData['line_extra_data']    = $outWinLine['LineExtraData'];
                $lineData['line_multiplier']    = $outWinLine['LineMultiplier'];
                $lineData['line_prize']         = $outWinLine['LinePrize'] /  $this->demon;
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
                $log['actionlist'][1]['amount']     = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon;
                $log['actionlist'][1]['eventtime']  = $currentTime;
                
                $log['detail']['wager']['order_time']   = $currentTime;
                $log['detail']['wager']['end_time']     = $currentTime;
                $log['detail']['wager']['total_win']    = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon;

                $proof['lock_position']         = $result_val['LockPos'];

                $sub_log = [];
                $sub_log['sub_no']              = $result_val['CurrentSpinTimes'];
                $sub_log['game_type']           = 50;
                $sub_log['rng']                 = $result_val['RngData'];
                $sub_log['multiple']            = $result_val['Multiple'];
                $sub_log['win']                 = $result_val['TotalWin'] /  $this->demon;
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
                $win_action['amount']           = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon;
                $win_action['eventtime']        = $currentTime;
                array_push($log['actionlist'], $win_action);

                $wager= [];
                $wager['seq_no']                = $result_val['GamePlaySerialNumber'];
                $wager['order_time']            = $currentTime;
                $wager['end_time']              = $currentTime;
                $wager['user_id']               = $slotSettings->playerId;
                $wager['game_id']               = '89';
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = $allBet;
                $wager['play_denom']            = 100;
                $wager['bet_multiple']          = $betline;
                $wager['rng']                   = $result_val['RngData'];
                $wager['multiple']              = $result_val['Multiple'];
                $wager['base_game_win']         = $result_val['TotalWin'] /  $this->demon;
                $wager['win_over_limit_lock']   = 0;
                $wager['game_type']             = 0;
                $wager['win_type']              = $result_val['WinType'];
                $wager['settle_type']           = 0;
                $wager['wager_type']            = 0;
                $wager['total_win']             = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon;
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

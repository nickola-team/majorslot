<?php 
namespace VanguardLTE\Games\MafiaCQ9
{

    use Illuminate\Support\Facades\Log;
    use SebastianBergmann\Environment\Console;

    include('CheckReels.php');
    class Server
    {
        public $winLines = [];
        public $linesId = [];
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
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 11}],"msg": null}');
                    $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetBalance().'],"evt": 1}');                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BaseWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ScatterWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReelPaies', [0, 0, 0, 0, 0]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TempTotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RngData', [0, 0, 0, 0, 0]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ScatterReelsCount',0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeScatterCount',0);
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
                            $result_val['MaxBet'] = 2000;
                            $result_val['MaxLine'] = 20;
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
                            $result_val["MemberSystemInfo"] = ["lobby"=>"","mission"=>"","playerInterfaceURL"=>"","item"=>["freeTicket"=>false,"payAdditionCard"=>false]];
                            $result_val['Currency'] ="KRW";
                            $result_val['Tag'] = [
                                "g" => "243",
                                "s" => "5.27.1.0",
                                "l" => "1.1.52",
                                "si" => "41"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["lEAJtK2kx6zlQEmlByY+prX9i+nSEw46nZx0avilI+v9pLj1NXmqKgbf5p97uYzC+koZdNOCX9cDbGh7+giuw1zEeK3g3Yon6ShOHcMZ7ltj9T654NBgikj/D5KY7va/faR2Yc96JXurtMqG2okR9nddJLOWTcL8WUU0KFDfW5yZDUCUXju0yGhLDnPZIjbgDPPMGiodfyFAIDh9fsC9iIcGPzkVJ//pFY7d/uDa2cP+DNWomKFu8jsBiC/zYEphPcK5v4kR7jFdhFBF","8iKj99eQUBUPbCAahRTWo9Me5yEarwvjTrU0T2fIoPZ4ex+Fm7+GNXf95NuhA9uaRlWt6kxpGy0lGwIvTymstg+5hXbAMuqyG2DvUbjayygJJW7UW6u5pEuiWl7RSUBmnoaHQftqf40vjUCrMJDEIcHSnpojEinXMTocv5Ei1aTY7BHBEqhZV36iMyw=","OniIJJqzg9pUB1Zreoz9J4BLD0H9ip5A4qy1n2ZSD80ruxzGyEig/V097nsWQ4nEBHD56vRnfvhTb3/PICi5Hl7paNNPNJPx716CjlQoKqitq7USlos+LldcbMZpJNpHDw5nxraJBclYlZa8AEXrUM0LMsilWaA2qV1Ds7pKFGSJThyR/MQodtNZq3s=","2zN0k2xfdbHbaeoFoJLMVBhmGpk7Oa+Y/34R0zC7+PJXkDpGZUvPgZlHlDgJNLn/aA6zzmGUGRmGkkQWh/l5oVSCDk5/rqZfFbTFQwgsucvQUqz2H447Lmtnd/0J5sG4ibU7pNF3pYk4i5x1ASqr70Pzx2UBXfa752lH4NeDuRSkWBGibcpDcOStXSY=","a5cC5bOk7PMsr1KXQpQuIx51+zwHMKKUGFYfZ+J7keblrLhD7sU07IU9e9yEEGQB1jSHZASZVCcZh+gyfMSCeZqCvqkNg/tOxp/fd23VChspSlOOaNPFKQknzawg02UwEDRQt3PkaaO6QkyYXl9x4gAbjysubMQLEPzJ7m6qikpWtjyQRqUjoQuw8W0="]];
                            $result_val['FGStripCount'] = 1;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["ZPq8fcEfD3Dwiv4P0Qjh60+sDuvztXkdxIf29IAsCKq5Y06oG+FHTpH184SydwWZc4MT9WYrpKyjylL3cfxJ8H9oNioz+1LX1Ld5i5nh4MabpPpEEpGTIgnR3g2vhi7YGFA/t5mYvlMVpeEi39xZzqDDmA9loLsY/1QsFrOn6HKkvbuLpQREZ80kujw6QJ9hFYEBppkdvvLRRzdspuPx32tbfBPGx0B5qHS3rIZen3ZofGRXdMd3Dx8K9MYbU5i4CPZBiuF1+WdYs/d7MvodZDbx3x2loSZg1HBqKiS6SWKyYV1VjTZfD9V5uBM=","z8leK9vgUKj3pQRMZ4biQEXSrADADOj38FQG2GhoszfHMVs4RQNnHixWnKXafp0D24c1wPPjhmrHu/astzn3Ju6Enregb8xhh28bRj7onT7+1gL7oVrY3U/AeAaLHs002c87M+XB9JcG9aQZN7JUf+PkuVJCcpk3MU8Dhx5vzeFYYKSuaBAb7+8LrmsQFo9l3nnYtenSE4O+TEfjGJN5RSfeYHA+5Hz1K05syUayAqh0nJVp/LcPK6wArzBxBHBJaT0goXM+FB73Xc7Yg/iyGisjCjbXnOphmSRbmA==","3RjkRJasixpGpHeqzMJ6CQ/eKCAvS8j3SUF0OoUCsRWXCV3am1cP5Sj1FFsOrbT/npLyV3CAbE5bsYI7l0ROP0VXn3LjEIwJxZx0DjMbD6uXyt4oczI9tNKR8hTsp6BLPCQo7d8cKx/rkot0It19roPLW34UEFqC6DC4hHAmOGhFS78786WlqaJoFwoO3RxufcaqPYHx9pvjJp2An61Dgkwi/THbK16aEOK9dalYvWoe7Pn3gj+N9gtvhGJIfTxla6OfmfOBfOUosOeFnMYCTjOb7xOc/6gjSMtDhQWPC1Ob13qWkN/WD0gC5Ele1GuRzINZHD5wwVelddAy","fxI9J7qrbgjEvzSf6+Fxj8QFQY2jz4UtINaLgwlkdkOeEHv6lC/P+cAupGf9H8glrERrMBGoXPxa1l3hL/aSCLyhZzHgJ97pO33giYbPFfNW3zBo2oew1zs5fBJqaJAc/rCPzb3GqF+30M+HiXq6mePw4ai5EGYTFD01PtMqHBVCrTM8Njd/G8GibkmbdMQ1ZdMsAVOWK6w7mPq0EZv9LeBqFmg3kySai83/pd133bRcQNPi/s4wH0TuOzWkd8tAIWof1ysHERF5tISdb5Ghc8CSFuNlbizkmpGwPyIBT47Sxluc/Abpjfcl2CY=","Tz0j0FJcvFjZp9UqxaQhg1BeA12P1BuUlwogrOBfHJUdquGTYhZnIVxX9dQDEtn2myQ+DD+gQSgmpPwYsAuPneBWE6lZXWgYQpezc5n5Yswt/zkDFyWYqaBv8apX9qRcL3KUzB/nz2c3Rg3Y7K13bR8jik5kizaCezz+T/CN+SQeQWQrbQAg2q7EaavvCNFEAWq1bJazO4GUbocsMwU0B4cNavZBqpMYPa0afPHkdYkA/H3ljmQBinWXZU0jA7RiEWN/hjKEBGIzJxY3gfNmDI8hBOKlrHlKvt00EcgvWBljBnZuRb1cTZIsKA4="]];
                        }else if($packet_id == 31 || $packet_id == 42){
                            $respinReels = [0, 0, 0, 0, 0];
                            $lines = 20;
                            if($packet_id == 31){
                                $betline = $gameData->PlayBet;// * $gameData->MiniBet;
                                
                            }else if($packet_id == 42){
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                                //$lines = 1;
                            }
                            $respinReelNo = 0;
                            if($packet_id == 42 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame')){
                                $slotEvent['slotEvent'] = 'freespin';
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                                //$totalbet = $betline * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
                                $totalbet = $betline * $lines;
                            }else{
                                $slotEvent['slotEvent'] = 'bet';
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BaseWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'ScatterWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                                $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                $totalbet = 0;
                                if($gameData->ReelPay == 0){
                                    //$totalbet = $betline * $lines * $gameData->MiniBet;
                                    $totalbet = $betline * $lines;
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
                                $result_val['Multiple'] = $slotSettings->GetGameData($slotSettings->slotId . 'BonusMul');
                                $result_val['GameExtraData'] = "";
                            }else{
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                            }
                        }else if($packet_id == 43){
                            $result_val['TotalWinAmt'] = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $slotSettings->GetGameData($slotSettings->slotId . 'BaseWin');
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
                        $lines = 20;
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
                            //$totalbet = $betline * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
                            $totalbet = $betline * $lines;
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
            // $_winAvaliableMoney = $slotSettings->GetBank('bonus');
            $defaultScatterCount = 0;
            if($winType == 'bonus'){
                $defaultScatterCount = $slotSettings->getScatterCount($slotEvent);
                //$defaultScatterCount = 3;
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeScatterCount',$defaultScatterCount);
            }
            $multiples = [0, 1, 1, 1, 1, 1];
            $freeMultiple = 1;
            $subMul = $slotSettings->getMultiple($slotEvent,$defaultScatterCount);
            // if($winType != 'bonus' || $slotEvent != 'freespin'){
            //     for($i = 0; $i < 3; $i++){
            //         $multiples[$i + 2] = $subMul[$i];
            //     }
            // }
            

            $this->linesId[0] = [2,2,2,2,2];
            $this->linesId[1] = [1,1,1,1,1];
            $this->linesId[2] = [3,3,3,3,3];
            $this->linesId[3] = [1,2,3,2,1];
            $this->linesId[4] = [3,2,1,2,3];
            $this->linesId[5] = [2,3,3,3,2];
            $this->linesId[6] = [2,1,1,1,2];
            $this->linesId[7] = [3,3,2,1,1];
            $this->linesId[8] = [1,1,2,3,3];
            $this->linesId[9] = [3,2,2,2,1];
            $this->linesId[10] = [1,2,2,2,3];
            $this->linesId[11] = [2,3,2,1,2];
            $this->linesId[12] = [2,1,2,3,2];
            $this->linesId[13] = [1,2,1,2,1];
            $this->linesId[14] = [3,2,3,2,3];
            $this->linesId[15] = [2,2,1,2,2];
            $this->linesId[16] = [2,2,3,2,2];
            $this->linesId[17] = [1,3,1,3,1];
            $this->linesId[18] = [3,1,3,1,3];
            $this->linesId[19] = [3,1,2,1,3];


            for( $i = 0; $i <= 2000; $i++ ) 
            {
                $totalWin = 0;
                $lineWins = [];
                $lineWinNum = [];
                $wild = 'W';
                $scatter = 'F';
                $_obf_winCount = 0;
                $strWinLine = '';                                
                $reels = $slotSettings->GetReelStrips($winType, $slotEvent, $defaultScatterCount);
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
                $OutputWinChgLines = [];
                $ReellPosChg = 0;
                $lockPos = [];
                if($slotEvent == 'freespin'){
                    $freeMultiple = $subMul[$slotSettings->GetGameData($slotSettings->slotId . 'FreeScatterCount') - 3];
                }
                $winResults = $this->winLineCalc($slotSettings, $reels, $multiples, $betline, 0,$respinReelNo,$slotEvent,$freeMultiple);
                $totalWin = $winResults['totalWin'];
                
                if($slotEvent == 'freespin'){
                    
                    $totalWin = $totalWin * $freeMultiple;
                }
                
                $OutputWinLines = $winResults['OutputWinLines'];
                $scattersCount = 0;  
                $scatterPositions = [[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0]]; 
                $scatterReelNumberCount = 0;
                $scattersReel = [0, 0, 0, 0, 0];
                $tempScatterReel = $slotSettings->GetGameData($slotSettings->slotId . 'ScatterReelsCount');
                for($r = 0; $r < 5; $r++){
                    $isScatter = false;
                    
                    for( $k = 0; $k < 3; $k++ ) 
                    {
                        if( $reels['reel' . ($r+1)][$k] == $scatter ) 
                        {   
                                $scattersCount++;
                                if($isScatter == false){
                                    $scatterReelNumberCount++;
                                    $isScatter = true;
                                }
                                $scatterPositions[$k][$r] = 1;
                                $scattersReel[$r]++;                    
                        }
                    }
                }
                                 
                $scatterWin = 0;
                $freespinNum = 0;
                if($scatterReelNumberCount == 2){
                    $scatterCorrupt = false;
                    if($respinReelNo > 0){
                        for($r=0;$r<5;$r++){
                            if($scattersReel[$r] > 0 && $r == ($respinReelNo - 1)){
                                $scatterCorrupt = true;
                            }
                        }
                    } else if ($tempScatterReel == 0){
                        $scatterCorrupt = true;
                        
                    }else{
                        $scatterCorrupt =false;
                    }
                    if($scatterCorrupt == true){
                        $scatterWin = $betline * $lines * 2 * $freeMultiple;
                        $OutputWinLines[$scatter] = [];
                        $OutputWinLines[$scatter]['SymbolId'] = $scatter;
                        $OutputWinLines[$scatter]['LineMultiplier'] = 1;
                        $OutputWinLines[$scatter]['LineExtraData'] = [0];
                        $OutputWinLines[$scatter]['LineType'] = 0;
                        $OutputWinLines[$scatter]['WinPosition'] = $scatterPositions;
                        $OutputWinLines[$scatter]['NumOfKind'] = $scatterReelNumberCount;
                        $OutputWinLines[$scatter]['SymbolCount'] = $scattersCount;
                        $OutputWinLines[$scatter]['LinePrize'] = $scatterWin;
                        $OutputWinLines[$scatter]['WinLineNo'] = 998;
                        $totalWin = $totalWin + $scatterWin;  
                        $slotSettings->SetGameData($slotSettings->slotId . 'ScatterReelsCount',0); 
                    }
                    
                }else if($scatterReelNumberCount >= 3){
                    $scatterPay = [0,0,0,5,20,100];
                    //$scatterWin = $betline * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet') * $scatterPay[$scatterReelNumberCount];
                    $scatterWin = $betline * $lines * $scatterPay[$scatterReelNumberCount] * $freeMultiple;
                    $OutputWinLines[$scatter] = [];
                    $OutputWinLines[$scatter]['SymbolId'] = $scatter;
                    $OutputWinLines[$scatter]['LineMultiplier'] = 1;
                    $OutputWinLines[$scatter]['LineExtraData'] = [0];
                    $OutputWinLines[$scatter]['LineType'] = 0;
                    $OutputWinLines[$scatter]['WinPosition'] = $scatterPositions;
                    $OutputWinLines[$scatter]['NumOfKind'] = $scatterReelNumberCount;
                    $OutputWinLines[$scatter]['SymbolCount'] = $scattersCount;
                    $OutputWinLines[$scatter]['LinePrize'] = $scatterWin;
                    $OutputWinLines[$scatter]['WinLineNo'] = 999;
                    $totalWin = $totalWin + $scatterWin;
                    $freeNums = [0,0,0,12,12,12];
                    $freespinNum = $freeNums[$scatterReelNumberCount];
                    
                    
                }
                
                if( $i > 1000 ) 
                {
                    $winType = 'none';
                }
                if( $i >= 2000 ) 
                {
                    break;
                }
                if( $freespinNum > 0 && ($winType != 'bonus' || $scatterReelNumberCount != $defaultScatterCount)) 
                {
                }
                else if($respinReelNo > 0 && ($winType == 'win' || $winType == 'bonus') && $totalWin == 0 && $i > 50){
                    $winType = 'none';
                }
                else if( $totalWin <= $_winAvaliableMoney && $winType == 'bonus' ) 
                {
                    $sub_winAvaliableMoney = $slotSettings->GetBank((isset($slotEvent) ? $slotEvent : ''));
                    if( $sub_winAvaliableMoney < $_winAvaliableMoney ) 
                    {
                        $_winAvaliableMoney = $sub_winAvaliableMoney;
                    }
                    else
                    {
                        break;
                    }
                }
                else if( $totalWin > 0 && $totalWin <= $_winAvaliableMoney && $winType == 'win' ) 
                {
                    $sub_winAvaliableMoney = $slotSettings->GetBank((isset($slotEvent) ? $slotEvent : ''));
                    if( $sub_winAvaliableMoney < $_winAvaliableMoney ) 
                    {
                        $_winAvaliableMoney = $sub_winAvaliableMoney;
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
            if($totalWin > 0){
                $slotSettings->SetBalance($totalWin);
                $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
            }
            $isEnd = true;
            if($slotEvent == 'freespin'){
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
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
                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $freespinNum > $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame')){
                    $isEnd = false;
                }
            }else{
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
            }
            $result_mul = [];
            if($slotEvent == 'freespin'){
                
                $result_val['Multiple'] = $freeMultiple;
                $result_val['GameState'] = 50;
            }else{
                
                $result_val['Multiple'] = 1;
                $result_val['GameState'] = 0;
            }
            
            
            $isTriggerFG = false;
            
            if($freespinNum > 0){
                $slotSettings->SetGameData($slotSettings->slotId . 'ScatterWin', $scatterWin);
                $isTriggerFG = true;
                if($slotEvent == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $freespinNum);
                    $result_val['RetriggerAddSpins'] = $freespinNum;
                }else{
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespinNum);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
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
            if($slotEvent != 'freespin'){
                $result_val['BaseWin'] = $totalWin - $scatterWin;
                
            }
            
            $result_val['TotalWin'] = $totalWin;      
            if($slotEvent != 'freespin'){                      
                $result_val['IsTriggerFG'] = $isTriggerFG;
                if($isTriggerFG == true){
                    $result_val['NextModule'] = 20;
                }else{
                    $result_val['NextModule'] = 0;
                }
                $result_val['IsHitJackPot'] = false;
            }
            $result_val['ExtraDataCount'] = 1;
            if($scattersCount >= 3){
                $result_val['ExtraData'] = [$scattersCount];
            }else{
                $result_val['ExtraData'] = [0];
            }
            
            $result_val['BonusType'] = 0;
            $result_val['SpecialAward'] = 0;
            $result_val['SpecialSymbol'] = 0;
            $result_val['ReelLenChange'] = [];
            $result_val['IsRespin'] = false;
            $result_val['FreeSpin'] = [$freespinNum];
            $result_val['NextSTable'] = 0;
            
            
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
                    // if($respinReelNo == 0){
                    //     $result_val['ReelPay'] = $this->getReelPay($reels, [0,0,0,0,0], $betline, $slotSettings,$totalWin - $scatterWin);
                    // }else{                        
                    //     $result_val['ReelPay'] = $this->getReelPay($reels, $slotSettings->GetGameData($slotSettings->slotId . 'ReelPaies'), $betline, $slotSettings,$totalWin - $scatterWin);
                    // }
                    $result_val['ReelPay'] = $this->getReelPay($reels, [0,0,0,0,0], $betline, $slotSettings,$totalWin - $scatterWin);
                }
                $slotSettings->SaveLogReport(json_encode($gamelog), $totalbet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $slotSettings->GetGameData($slotSettings->slotId . 'BaseWin'), $slotEvent, $result_val['GamePlaySerialNumber']);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeScatterCount',0);
            }
            $slotSettings->SetGameData($slotSettings->slotId . 'ReelPaies', $result_val['ReelPay']);
            if($slotEvent != 'freespin' && $scatterReelNumberCount >= 3){
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $scatterWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'BaseWin', $totalWin - $scatterWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
            }
            return $result_val;
        }
        public function getReelPay($reels, $reelWins, $betline, $slotSettings,$reeltotalWin){
            $wild = 'W';
            $scatterReel = [0,0,0,0,0];
            $scatterCount = 0;
            for($k = 0; $k < 5; $k++){
                for($j = 0; $j < 3; $j++){
                    if($reels['reel' . ($k + 1)][$j] == 'F'){
                        $scatterReel[$k] = 1;
                        $scatterCount++;
                        break;
                    }
                }
            }
            $tempWin = $slotSettings->GetGameData($slotSettings->slotId . 'TempTotalWin');
            if($tempWin>0){
                $reeltotalWin = $tempWin;
            }
            for($i = 0; $i < 5; $i++){
                $totalWin = 0;
                if($i < 3 || $reeltotalWin > 0){
                    
                    for( $k = 0; $k < 20; $k++ ) 
                    {
                        if($i > 0){
                            $firstEle = $reels['reel1'][$this->linesId[$k][0] - 1];
                        }else{
                            $firstEle = $wild;
                        }                        
                        $lineWinNum[$k] = 1;
                        $lineWins[$k] = 0;
                        for($j = 1; $j < 5; $j++){
                            if($i == $j){
                                $ele = $wild;
                            }else{
                                $ele = $reels['reel'. ($j + 1)][$this->linesId[$k][$j] - 1];
                            }
                            if($firstEle == $wild){
                                $firstEle = $ele;
                                $lineWinNum[$k] = $lineWinNum[$k] + 1;
                            }else if($ele == $firstEle || $ele == $wild ){
                                $lineWinNum[$k] = $lineWinNum[$k] + 1;
                                if($j == 4){
                                    $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                    if($lineWins[$k] > 0){
                                        $totalWin += $lineWins[$k];
                                    }
                                }
                            }else{
                                if($slotSettings->Paytable[$firstEle][$lineWinNum[$k]] > 0){
                                    $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                    if($lineWins[$k] > 0){
                                        $totalWin += $lineWins[$k];
                                    }
                                }else{
                                    $lineWinNum[$k] = 0;
                                }
                                break;
                            }
                        }
                    }
                }
                if($totalWin > 0){                    
                    $reelWins[$i] = floor($totalWin / 10) - mt_rand(0, 3);
                }
                if($scatterReel[$i] == 0){
                    if($scatterCount == 2){
                        $reelWins[$i] += floor($betline * 138.5);                     
                    }else if($scatterCount == 1){
                        $reelWins[$i] += $betline * 3;
                    }
                }else if($scatterReel[$i] == 1 && $scatterCount == 2){
                    $reelWins[$i] += $betline * 3;
                }
                if($reelWins[$i] == 0){
                    $reelWins[$i] = 1;
                }
            }
            //$slotSettings->SetGameData($slotSettings->slotId . "TempReelPaies",$reelWins)
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
            $proof['fg_times']                  = $result_val['FreeSpin'];
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
                $proof['reel_pay']                  = $result_val['ReelPay'];
                $log = [];
                $log['account']                 = $slotSettings->playerId;
                $log['parentacc']               = 'major_prod';
                $log['actionlist']              = [];
                $log['detail']                  = [];
                $bet_action = [];
                $bet_action['action']           = 'bet';
                //$bet_action['amount']           = $betline * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
                $bet_action['amount']           = $totalbet;
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
                $wager['game_id']               = 243;
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
        public function winLineCalc($slotSettings, $reels, $muls, $betline, $lineType,$respinNo,$slotEvent,$freeMultiple){
            $totalWin = 0;
            $tempTotalWin = 0;
            $_obf_winCount = 0;
            $wild = 'W';
            $OutputWinLines = [];
            
            $_lineWinNumber = 1;
            for( $k = 0; $k < 20; $k++ ) 
            {
                $_lineWin = '';
                $winPosition = [[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0]];
                $firstEle = $reels['reel1'][$this->linesId[$k][0] - 1];
                $lineWinNum[$k] = 1;
                $lineWins[$k] = 0;
                for($j = 1; $j < 5; $j++){
                    $ele = $reels['reel'. ($j + 1)][$this->linesId[$k][$j] - 1];
                    if($firstEle == $wild){
                        $firstEle = $ele;
                        $lineWinNum[$k] = $lineWinNum[$k] + 1;
                    }else if($ele == $firstEle || $ele == $wild ){
                        $lineWinNum[$k] = $lineWinNum[$k] + 1;
                        if($j == 4){
                            $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                            if($lineWins[$k] > 0){
                                $totalWin += $lineWins[$k];
                            }
                            for($i=0;$i<5;$i++){
                                for($x=0;$x<$lineWinNum[$k];$x++){
                                    $winPosition[($this->linesId[$k][$i] - 1)][$i] = 1;
                                    $x++;
                                }
                                
                            }
                            $OutputWinLines[$firstEle] = [
                                'SymbolId' => $firstEle,
                                'LineMultiplier' => 1,
                                'LineExtraData' => [0],
                                'LineType' => $lineType,
                                'WinPosition' => $winPosition,
                                'NumOfKind' => $lineWinNum[$k],
                                'LinePrize' => $lineWins[$k],
                                'WinLineNo' => $k,
                                'SymbolCount' => $lineWinNum[$k],
                            ];
                            if($slotEvent == 'freespin'){
                                $OutputWinLines[$firstEle]['LinePrize'] = $lineWins[$k] * $freeMultiple;
                            }
                        }
                    }else{
                        if($slotSettings->Paytable[$firstEle][$lineWinNum[$k]] > 0){
                            if($respinNo <= $lineWinNum[$k]){
                                $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                if($lineWins[$k] > 0){
                                    $totalWin += $lineWins[$k];
                                    $_obf_winCount++;
                                    for($i=0;$i<5;$i++){
                                        for($x=0;$x<$lineWinNum[$k];$x++){
                                            $winPosition[($this->linesId[$k][$i] - 1)][$i] = 1;
                                            $x++;
                                        }
                                        
                                    }
                                    $OutputWinLines[$firstEle] = [
                                        'SymbolId' => $firstEle,
                                        'LineMultiplier' => 1,
                                        'LineExtraData' => [0],
                                        'LineType' => $lineType,
                                        'WinPosition' => $winPosition,
                                        'NumOfKind' => $lineWinNum[$k],
                                        'LinePrize' => $lineWins[$k],
                                        'WinLineNo' => $k,
                                        'SymbolCount' => $lineWinNum[$k],
                                    ];
                                    if($slotEvent == 'freespin'){
                                        $OutputWinLines[$firstEle]['LinePrize'] = $lineWins[$k] * $freeMultiple;
                                    }
                                }
                            }else{
                                $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                if($lineWins[$k] > 0){
                                    $tempTotalWin += $lineWins[$k];
                                }
                                $slotSettings->SetGameData($slotSettings->slotId . 'TempTotalWin',$tempTotalWin);
                            }
                            
                        }else{
                            $lineWinNum[$k] = 0;
                        }
                        break;
                    }
                }
            }
            $result = [];
            $result['totalWin'] = $totalWin;
            $result['OutputWinLines'] = $OutputWinLines;
            return $result;
        }
    }

}

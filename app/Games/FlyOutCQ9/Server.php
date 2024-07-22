<?php 
namespace VanguardLTE\Games\FlyOutCQ9
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
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 12}],"msg": null}');
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'InitReelPaies', [0, 0, 0, 0, 0]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RngData', [0, 0, 0, 0, 0]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'InitFreeRngData', [0, 0, 0, 0, 0]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeScatterCount',0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBet', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TempTotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'MaxRespinCount', mt_rand(10,25));
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeReelCounts',0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'IsFreeWin',0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'NoWinSpinMaxCount',0);
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
                            $result_val['MaxBet'] = 1250;
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
                            $result_val['FeedbackURL'] = '/feedback/?token=' . auth()->user()->api_token;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();

                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["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"]];
                            $result_val['FGStripCount'] = 1;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["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"]];
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
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                                $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'IsFreeWin',0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'NoWinSpinMaxCount',0);
                                $totalbet = 0;
                                if($gameData->ReelPay == 0){
                                    $totalbet = $betline * $lines * $gameData->MiniBet;
                                    $slotSettings->SetBalance(-1 * ($totalbet), $slotEvent['slotEvent']);
                                    $slotSettings->UpdateJackpots($totalbet);
                                    $_sum = $totalbet / 100 * $slotSettings->GetPercent();
                                    $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent'],$slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeReelCounts'));
                                }else{
                                    $slotEvent['slotEvent'] = 'reel';
                                    $respinReels = $gameData->ReelSelected;
                                    for($k = 0; $k < 5; $k++){
                                        if($respinReels[$k] == 1 && $slotSettings->GetGameData($slotSettings->slotId . 'ReelPaies')[$k] == $gameData->ReelPay){
                                            $totalbet = $gameData->ReelPay;
                                            $slotSettings->SetBalance(-1 * ($totalbet), $slotEvent['slotEvent']);
                                            $slotSettings->UpdateJackpots($totalbet);
                                            $_sum = ($totalbet) / 100 * $slotSettings->GetPercent();
                                            $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent'],$slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeReelCounts'));
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
                                $result_val['Multiple'] = $slotSettings->GetGameData($slotSettings->slotId . 'BonusMul') * 3;
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
            // if($slotEvent != 'freespin'){
            //     if($winType != 'none' && mt_rand(0, 100) < 40){
            //         $winType = 'none';
            //         $_winAvaliableMoney = 0;
            //     }
            // }
            if($slotSettings->GetGameData($slotSettings->slotId . 'FreeBet') == 1){
                $winType = 'bonus';

                $randValue = mt_rand(15,30);
                $slotSettings->SetGameData($slotSettings->slotId . 'MaxRespinCount', $randValue);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinCount', 0);
            }
            //$winType='bonus';
            $defaultScatterCount = 0;
            if($winType == 'bonus'){
                $defaultScatterCount = $slotSettings->getScatterCount($slotEvent);
                //$defaultScatterCount = 6;
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeScatterCount',$defaultScatterCount);
                
                
            }
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
                $winResults = $this->winLineCalc($slotSettings, $reels, $betline, 0, $respinReelNo,$slotEvent);
                $totalWin = $winResults['totalWin'];
                $OutputWinLines = $winResults['OutputWinLines'];
                $scattersCount = 0;  
                $scatterPositions = [[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0]]; 
                $scatterReelNumberCount = 0;
                $scattersReel = [0, 0, 0, 0, 0];
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
                if($scatterReelNumberCount >= 5){
                    $scatterPay = [0,0,0,5,5,5];
                    //$scatterWin = $betline * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
                    $OutputWinLines[$scatter] = [];
                    $OutputWinLines[$scatter]['SymbolId'] = $scatter;
                    $OutputWinLines[$scatter]['LineMultiplier'] = $scatterReelNumberCount - 4;
                    $OutputWinLines[$scatter]['LineExtraData'] = [0];
                    $OutputWinLines[$scatter]['LineType'] = 0;
                    $OutputWinLines[$scatter]['WinPosition'] = $scatterPositions;
                    $OutputWinLines[$scatter]['NumOfKind'] = $scatterReelNumberCount;
                    $OutputWinLines[$scatter]['SymbolCount'] = $scattersCount;
                    $OutputWinLines[$scatter]['LinePrize'] = $scatterWin;
                    $OutputWinLines[$scatter]['WinLineNo'] = 999;
                    //$totalWin = $totalWin + $scatterWin;
                    $freeNums = [0,0,0,13,13,13];
                    $freespinNum = $freeNums[$scatterReelNumberCount];
                    if($scattersCount >4){
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', $scattersCount - 4);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeScatterCount',$scattersCount);
                    }
                }
                if($slotEvent == 'freespin' && $slotSettings->GetGameData($slotSettings->slotId . 'IsFreeWin') == 0 && $slotSettings->GetGameData($slotSettings->slotId . 'NoWinSpinMaxCount') <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame'))
                {
                    if($totalWin > 0){
                        if($totalWin < $betline * $lines * 10){
                            break;
                        }else{
                            continue;
                        }
                    }else{
                        continue;
                    }
                }
                if( $i > 1000 ) 
                {
                    $winType = 'none';
                }
                if( $i >= 2000 ) 
                {
                    break;
                }
                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeBet') == 1 && $freespinNum > 0 && $totalWin == 0){
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
                if($slotEvent == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'IsFreeWin',1);
                    $slotSettings->SetBank('freespin', -1 *  $totalWin * $slotSettings->GetGameData($slotSettings->slotId . 'BonusMul') * 3);
                }else{
                    $slotSettings->SetBalance($totalWin);    
                    $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
                }
                
                // $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
            }
            $result_val['Multiple'] = "1";
            $isEnd = true;
            if($slotEvent == 'freespin'){
                //$scatterMul = $slotSettings->GetGameData($slotSettings->slotId . 'FreeScatterCount') - 4;
                $scatterMul = 3;
                
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin * $slotSettings->GetGameData($slotSettings->slotId . 'BonusMul') * $scatterMul);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin * $slotSettings->GetGameData($slotSettings->slotId . 'BonusMul') * $scatterMul);
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
                $result_val['Multiple'] = $slotSettings->GetGameData($slotSettings->slotId . 'BonusMul') * $scatterMul;
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
                if($slotEvent == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $freespinNum);
                    $result_val['RetriggerAddSpins'] = $freespinNum;
                }else{
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespinNum);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBet', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'IsFreeWin',0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'NoWinSpinMaxCount',mt_rand(4, 12));
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
                    $result_val['ReelPay'] = $this->getReelPay($reels, $betline, $slotSettings,$respinReelNo,$totalWin);
                }else{
                    $result_val['ReelPay'] = $slotSettings->GetGameData($slotSettings->slotId . 'InitReelPaies');
                    $slotSettings->SetGameData($slotSettings->slotId . 'RngData',$slotSettings->GetGameData($slotSettings->slotId . 'InitFreeRngData'));
                    $slotSettings->SetBalance($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin')); 
                }
                $slotSettings->SaveLogReport(json_encode($gamelog), $totalbet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $slotSettings->GetGameData($slotSettings->slotId . 'BaseWin'), $slotEvent, $result_val['GamePlaySerialNumber']);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeScatterCount',0);
            }else{
                if($scatterReelNumberCount >= 5){
                    $reelWins = [0,0,0,0,0];
                    for($i=0;$i<5;$i++){
                        $reelWins[$i] += floor($betline * 838 * ($scattersCount - 4)) + mt_rand(0, 8); 
                    }
                    if($slotEvent != 'freespin'){
                        $result_val['ReelPay'] = $reelWins;
                        $slotSettings->SetGameData($slotSettings->slotId . 'InitReelPaies', $reelWins);
                        $slotSettings->SetGameData($slotSettings->slotId . 'InitFreeRngData', $slotSettings->GetGameData($slotSettings->slotId . 'RngData'));
                    }else{
                        $result_val['ReelPay'] = $slotSettings->GetGameData($slotSettings->slotId . 'InitReelPaies');
                        
                    }
                }
            }
            $slotSettings->SetGameData($slotSettings->slotId . 'ReelPaies', $result_val['ReelPay']);
            if($slotEvent != 'freespin' && $scatterReelNumberCount >= 3){
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $scatterWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'BaseWin', $totalWin - $scatterWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
            }
            return $result_val;
        }
        public function getReelPay($reels, $betline, $slotSettings,$respinNo,$reeltotalWin){
            $wild = 'W';
            $symbolId = [1, 2, 3, 4, 11, 12, 13, 14];
            $reelWins = [1, 1, 1, 1, 1];
            $scatterReel = [0,0,0,0,0];
            $scatterReelCount = 0;
            $scatterCount = 0;
            for($k = 0; $k < 5; $k++){
                $isScatter = false;
                for($j = 0; $j < 3; $j++){
                    if($reels['reel' . ($k + 1)][$j] == 'F'){
                        if($isScatter == false){
                            $scatterReel[$k] = 1;
                            $scatterReelCount++;
                            $isScatter = true;
                        }  
                        $scatterCount++;                      
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
                    for($j = 0; $j < 8; $j++){
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
                        $tempPaytableValue = $slotSettings->Paytable[$symbolId[$j]][$repeatCount] * $betline * $tempCorruptValue;
                        $totalWin = $totalWin + $tempPaytableValue * $wildCount;
                    }
                }
                if($totalWin > 0){
                    $reelWins[$k] = floor($totalWin / 10) + mt_rand(0, 8);
                    
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeReelCounts',$scatterReelCount);
                if($scatterReel[$k] == 0){
                    if($scatterReelCount == 4){                        
                        $reelWins[$k] += floor($betline * 407 * ($scatterCount - 3)) + mt_rand(0,100);  
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinCount', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinCount') + 1);
                        if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinCount') >= $slotSettings->GetGameData($slotSettings->slotId . 'MaxRespinCount')){
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeBet', 1);
                        }   
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeReelCounts',$scatterReelCount);              
                    }
                }else if($scatterReel[$k] == 1 && $scatterReelCount == 5){             
                    $reelWins[$k] += floor($betline * 407 * ($scatterCount - 3)) + mt_rand(0,100);              
                }
                // else if($scatterReel[$k] == 1 && $scatterCount == 2){
                //     $reelWins[$k] += $betline * 3;
                // }
                if($reelWins[$k] <= 1){
                    $reelWins[$k] = $reelWins[$k] * $betline;
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
                $wager['game_id']               = 111;
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
                $wager['multiple']              = strval($result_val['Multiple']);
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
        public function winLineCalc($slotSettings, $reels, $betline, $lineType, $respinReelNo,$slotEvent){
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
                // for($k = 0; $k < $winLine['RepeatCount']; $k++){
                //     if($isWilds[$k] > 0){
                //         $bonusMul = $bonusMul * $muls[$k + 1];
                //     }
                // }
                $winLineMoney = $slotSettings->Paytable[$winLine['FirstSymbol']][$winLine['RepeatCount']] * $betline * $bonusMul;                                    
                if($winLineMoney > 0 && $winLine['RepeatCount'] >= $respinReelNo){
                    if(!isset($OutputWinLines[$winLine['FirstSymbol']])){
                        // if($slotSettings->GetGameData($slotSettings->slotId . 'FreeScatterCount') > 4){
                        //     $tempMul = $slotSettings->GetGameData($slotSettings->slotId . 'FreeScatterCount') - 4;
                        // }else{
                        //     $tempMul = 1;
                        // }
                        $tempMul = 3;
                        $OutputWinLines[$winLine['FirstSymbol']] = [];
                        $OutputWinLines[$winLine['FirstSymbol']]['SymbolId'] = $winLine['FirstSymbol'];
                        $OutputWinLines[$winLine['FirstSymbol']]['LineMultiplier'] = $bonusMul;
                        $OutputWinLines[$winLine['FirstSymbol']]['LineExtraData'] = [0];
                        $OutputWinLines[$winLine['FirstSymbol']]['LineType'] = $lineType;
                        $OutputWinLines[$winLine['FirstSymbol']]['WinPosition'] = [[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0]];
                        $OutputWinLines[$winLine['FirstSymbol']]['NumOfKind'] = $winLine['RepeatCount'];
                        $OutputWinLines[$winLine['FirstSymbol']]['SymbolId'] = $winLine['FirstSymbol'];
                        if($slotEvent == 'freespin'){
                            $OutputWinLines[$winLine['FirstSymbol']]['LinePrize'] = $winLineMoney * $slotSettings->GetGameData($slotSettings->slotId . 'BonusMul') * $tempMul;
                        }else{
                            $OutputWinLines[$winLine['FirstSymbol']]['LinePrize'] = $winLineMoney;
                        }
                        
                        $OutputWinLines[$winLine['FirstSymbol']]['WinLineNo'] = $lineCount;
                        $lineCount++;
                    }else{
                        $tempMul = 3;
                        if($slotEvent == 'freespin'){
                            $OutputWinLines[$winLine['FirstSymbol']]['LinePrize'] += $winLineMoney * $slotSettings->GetGameData($slotSettings->slotId . 'BonusMul') * $tempMul;
                        }else{
                            $OutputWinLines[$winLine['FirstSymbol']]['LinePrize'] += $winLineMoney;
                        }
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

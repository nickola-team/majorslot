<?php 
namespace VanguardLTE\Games\JumpHigherMobileCQ9
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReelPaies', [0, 0, 0, 0, 0]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RngData', [0, 0, 0, 0, 0]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TempRespinValue', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeScatterCount',0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TempRespinValue', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TempTotalWin', 0);
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
                                $result_val['Multiple'] = $slotSettings->GetGameData($slotSettings->slotId . 'BonusMul');
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
                    $freeNums = [0,0,0,12,12,12];
                    $freespinNum = $freeNums[$scatterReelNumberCount];
                    if($scattersCount >4){
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', $scattersCount - 4);
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
            $result_val['Multiple'] = "1";
            $isEnd = true;
            if($slotEvent == 'freespin'){
                $scatterMul = $slotSettings->GetGameData($slotSettings->slotId . 'FreeScatterCount') - 4;
                
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin * $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') * $scatterMul);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin * $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') * $scatterMul);
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
                    $result_val['ReelPay'] = $this->getReelPay($reels, $betline, $slotSettings,$respinReelNo,$totalWin);
                }
                $slotSettings->SaveLogReport(json_encode($gamelog), $totalbet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $slotSettings->GetGameData($slotSettings->slotId . 'BaseWin'), $slotEvent, $result_val['GamePlaySerialNumber']);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeScatterCount',0);
            }else{
                if($scatterReelNumberCount >= 5){
                    $reelWins = [0,0,0,0,0];
                    for($i=0;$i<5;$i++){
                        $reelWins[$i] += floor($betline * 838 * ($scatterReelNumberCount - 4)) + mt_rand(0, 8); 
                    }
                    $result_val['ReelPay'] = $reelWins;
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
                    // if($respinNo > 0){
                    //     $reelWins[$k] = floor($totalWin / $slotSettings->GetGameData($slotSettings->slotId . 'TempRespinValue')) + mt_rand(0, 8);
                    // }else{
                    //     $reelWins[$k] = floor($totalWin / $subIntArray[$subNumber]) + mt_rand(0, 8);
                    //     $slotSettings->SetGameData($slotSettings->slotId . 'TempRespinValue', $subIntArray[$subNumber]);
                    // }
                    $reelWins[$k] = floor($totalWin / 10) + mt_rand(0, 8);
                    
                }
                if($scatterReel[$k] == 0){
                    if($scatterReelCount == 4){                        
                        $reelWins[$k] += floor($betline * 838 * ($scatterCount - 3));                     
                    }
                }else if($scatterReel[$k] == 1 && $scatterReelCount == 5){             
                    $reelWins[$k] += floor($betline * 838 * ($scatterCount - 3));              
                }
                // else if($scatterReel[$k] == 1 && $scatterCount == 2){
                //     $reelWins[$k] += $betline * 3;
                // }
                if($reelWins[$k] <= 1){
                    $reelWins[$k] = $reelWins[$k] * $betline;
                }
                
            }
            // if($reeltotalWin>0){
            //     $slotSettings->SetGameData($slotSettings->slotId . 'TempRespinValue', $reeltotalWin);
            // }
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
                $wager['game_id']               = 108;
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

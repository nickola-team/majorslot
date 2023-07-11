<?php 
namespace VanguardLTE\Games\MummysTreasureCQ9
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
            $originalbet = 1;
            $slotSettings->SetBet();
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 0}],"msg": null}');
                    $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetBalance().'],"evt": 1}');                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', 0);
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
                            $result_val['DefaultDenomIdx'] = 3;
                            $result_val['MaxBet'] = $slotSettings->Bet[count($slotSettings->Bet) - 4] * $this->demon;
                            $result_val['MaxLine'] = 1;
                            $result_val['WinLimitLock'] = 1500000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
                            $result_val['ExtendFeatureByGame'] = null;
                            $result_val['ExtendFeatureByGame2'] = null;
                            $result_val['GameFlowBranch'] = 0;
                            $result_val['IsReelPayType'] = false;
                            $result_val['Cobrand'] = null;
                            $result_val['PlayerOrderURL'] = config('app.cq9history') . '/platform/?gametoken=' . auth()->user()->api_token;
                            $result_val['PromotionData'] = null; //$slotSettings->getPromotionData();
                            $result_val['IsShowFreehand'] = false;
                            $result_val['IsAllowFreehand'] = false;
                            $result_val['FeedbackURL'] = null;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['FreeTicketList'] = null;
                            $result_val['DenomMultiple'] = $initDenom * $this->demon;
                            $result_val['FreeSpinLeftTimesInfoList'] = null;
                            $result_val['Tag'] = [
                                "g"=>"GB7","s"=>"5.16.3.6","l"=>"0.0.0.20","si"=>"11"
                            ];
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = -1;
                            $result_val['BGStripCount'] = 0;
                            $result_val['BGContext'] = [];
                            $result_val['FGStripCount'] = 0;
                            $result_val['FGContext'] = [];
                        }else if($packet_id == 34 || $packet_id == 33){
                            $slotEvent['slotEvent'] = 'bet';
                            $bomb_value = 0;
                            $pkno_value = 0;
                            if(isset($gameData->Option)){
                                foreach($gameData->Option as $item){
                                    if($item->Name == 'Bomb'){
                                        $bomb_value = $item->Value;
                                    }
                                    if($item->Name == 'PKNO'){
                                        $pkno_value = $item->Value;
                                    }
                                }
                            }
                            
                            if($packet_id == 34){
                                $betline = $gameData->PlayerBetMultiples[0];// * $gameData->MiniBet;
                                $lines = $gameData->PlayLine;
                                $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                                $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayerBetMultiples[0]);
                                $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', ($betline * $gameData->MiniBet /  $this->demon));
                                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 1);
                                $slotSettings->SetBet();

                                $slotSettings->SetGameData($slotSettings->slotId . 'SymbolResult', ["N6,N6,N6,N6,N6","N6,N6,N6,N6,N6","N6,N6,N6,N6,N6","N6,N6,N6,N6,N6","N6,N6,N6,N6,N6"]);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalPknoCount', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalMulValue', [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BombValue', $bomb_value);
                                $slotSettings->SetGameData($slotSettings->slotId . 'EmptySpin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'NextValue', 0);

                                $slotSettings->SetBalance(-1 * ($betline * $gameData->MiniBet /  $this->demon) * $lines, $slotEvent['slotEvent']);
                                $_sum = ($betline * $gameData->MiniBet /  $this->demon) * $lines / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '130' . substr($roundstr, 3, 7);
                                $slotSettings->SetGameData($slotSettings->slotId . 'GamePlaySerialNumber', $roundstr);
                            }else{
                                
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                                $lines = 1;
                            }
                            if($slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') > 0){
                                $slotEvent['slotEvent'] = 'respin';
                            }
                            if($slotSettings->GetGameData($slotSettings->slotId . 'EmptySpin') == 1){
                                $result_val = $this->generateEmptyResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet, $pkno_value);
                            }else{
                                $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet, $bomb_value, $pkno_value);
                            }
                            
                            $result_val['EmulatorType'] = $emulatorType;

                            $slotSettings->SaveGameData();
                        }else if($packet_id == 32){
                            $result_val['ErrorCode'] = 0;
                            $totalBet = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet') * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
                            if($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') > 0){
                                $slotSettings->SetBalance($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') / $this->demon);
                                $slotSettings->SetBank('', -1 * $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') / $this->demon);
                            }  
                            $gamelog = $slotSettings->GetGameData($slotSettings->slotId . 'GameLog');
                            $slotSettings->SaveLogReport(json_encode($gamelog), $totalBet, 1, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon, 'bet', 'GB' . $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), true);
                            $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
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
                    if(($packet_id == 34) && $type == 3){
                        $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetGameData($slotSettings->slotId . 'CurrentBalance').'],"evt": 1}');
                    }
                }else if($paramData['req'] == 202){
                    $gameDatas = $this->parseMessage($paramData['vals']);
                    $game_id = json_decode($gameDatas[0])->GameID;
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1, "'. $slotSettings->GetNewGameLink($game_id) .'"],"msg": null}');
                }else if($paramData['req'] == 1000){  // socket closed
                    if($slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') > 0){
                        // FreeSpin Balance add
                        $slotEvent['slotEvent'] = 'respin';
                        $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                        $lines = 1;
                        $count = 0;
                        while($slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') > 0){
                            $result_val = [];
                            $result_val['Type'] = 3;
                            $result_val['ID'] = 133;
                            $result_val['Version'] = 0;
                            $result_val['ErrorCode'] = 0;
                            $result_val['EmulatorType'] = 0;
                            $this->generateEmptyResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet, -1);
                        }
                        $totalBet = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet') * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
                        $gamelog = $slotSettings->GetGameData($slotSettings->slotId . 'GameLog');
                        $slotSettings->SaveLogReport(json_encode($gamelog), $totalBet, 1, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon, 'bet', 'GB' . $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), true);
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
        public function generateResult($slotSettings, $result_val, $slotEvent, $betline, $lines, $originalbet, $bomb_value, $pkno_value){
            $totalBet = ($betline /  $this->demon) * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
            if($slotEvent == 'respin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
            }else{
                $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, $totalBet, $lines);
                $winType = $_spinSettings[0];
                $_winAvaliableMoney = $_spinSettings[1];
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, $totalBet, $bomb_value);
                if($tumbAndFreeStacks == null){
                    $response = 'unlogged';
                    exit( $response );
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $tumbAndFreeStacks);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                $stack = $tumbAndFreeStacks[0];
            }
            $symbolResult = [];
            $oldSymbolResult = [];
            for($k = 0; $k < 5; $k++){
                $symbolResult[$k] = explode(',', $slotSettings->GetGameData($slotSettings->slotId . 'SymbolResult')[$k]);
                $oldSymbolResult[$k] = explode(',', $stack['SymbolResult'][$k]);
            }
            ;
            $totalPknoCount = $slotSettings->GetGameData($slotSettings->slotId . 'TotalPknoCount');
            $totalMulValue = $slotSettings->GetGameData($slotSettings->slotId . 'TotalMulValue');
            
            $oldTotalMulValue = explode(',', $stack['ExtendFeatureByGame2'][1]['Value']);
            $oldPknoValue = $stack['ExtendFeatureByGame2'][0]['Value'];
            $oldWinLineCount = $stack['WinLineCount'];
            $isDouble = false;
            $multiples = [];
            if(isset($stack['Multiple'])){
                $currentMultiple = explode(',', $stack['Multiple'])[0];
                if($oldWinLineCount == 2){
                    $currentMultiple = $currentMultiple - $stack['udsOutputWinLine'][1]['LineMultiplier'];
                }
            }else{
                $currentMultiple = $slotSettings->GetGameData($slotSettings->slotId . 'NextValue');   
            }
            if($oldWinLineCount == 1){
                $symbolResult[($pkno_value % 5)][floor($pkno_value / 5)] = $oldSymbolResult[($oldPknoValue % 5)][floor($oldPknoValue / 5)];
                $totalMulValue[$pkno_value] = explode(':', $oldTotalMulValue[$oldPknoValue])[1];
                $totalPknoCount++;
            }else if($oldWinLineCount == 2){
                $diffX = $pkno_value % 5;
                $diffY = floor($pkno_value / 5);
                $move_arrow = [[0, 1], [0, -1], [1, 0], [-1, 0]];
                shuffle($move_arrow);
                $empty_arrowindex = 0;
                for($k = 0; $k < 4; $k++){
                    if($diffX + $move_arrow[$k][0]>= 0 && $diffX + $move_arrow[$k][0]< 5 && $diffY + $move_arrow[$k][1] >= 0 && $diffY + $move_arrow[$k][1] < 5 && $symbolResult[$diffX + $move_arrow[$k][0]][$diffY + $move_arrow[$k][1]] == 'N6'){
                        $isDouble = true;
                        $empty_arrowindex = $k;
                        break;
                    }
                }
                
                if($isDouble == true){
                    $totalPknoCount+= $oldWinLineCount;
                    $symbolResult[$diffX][$diffY] = $oldSymbolResult[($oldPknoValue % 5)][floor($oldPknoValue / 5)];
                    $totalMulValue[$pkno_value] = explode(':', $oldTotalMulValue[$oldPknoValue])[1];

                    $totalMulValue[($diffX + $move_arrow[$empty_arrowindex][0]) + ($diffY + $move_arrow[$empty_arrowindex][1]) * 5] = $currentMultiple + $stack['udsOutputWinLine'][1]['LineMultiplier'];
                    foreach($oldTotalMulValue as $index => $item){
                        if($item != '' && explode(':', $item)[1] == ($currentMultiple + $stack['udsOutputWinLine'][1]['LineMultiplier'])){
                            $symbolResult[$diffX + $move_arrow[$empty_arrowindex][0]][$diffY + $move_arrow[$empty_arrowindex][1]] = $oldSymbolResult[($index % 5)][floor($index / 5)];
                            break;
                        }
                    }
                }else{
                    $totalPknoCount++;
                    $symbolResult[$diffX][$diffY] = $oldSymbolResult[($oldPknoValue % 5)][floor($oldPknoValue / 5)];
                    $totalMulValue[$pkno_value] = explode(':', $oldTotalMulValue[$oldPknoValue])[1];

                    $stack['ExtendFeatureByGame2'][2]['Value'] = $stack['ExtendFeatureByGame2'][2]['Value'] - $stack['udsOutputWinLine'][1]['LineMultiplier'];
                    $stack['ExtendFeatureByGame2'][3]['Value'] = 0;
                    $stack['WinLineCount'] = 1;
                    $slotSettings->SetGameData($slotSettings->slotId . 'EmptySpin', 1);

                    array_splice($stack['udsOutputWinLine'], 1, 1);
                    $stack['BaseWin'] = $stack['udsOutputWinLine'][0]['LinePrize'];
                    $stack['TotalWin'] = $stack['udsOutputWinLine'][0]['LinePrize'];
                }
            }else{
                $symbolResult[($pkno_value % 5)][floor($pkno_value / 5)] = $oldSymbolResult[($oldPknoValue % 5)][floor($oldPknoValue / 5)];
                $totalMulValue[$pkno_value] = explode(':', $oldTotalMulValue[$oldPknoValue])[1];
            }
            $stack['ExtendFeatureByGame2'][0]['Value'] = $pkno_value;
            $newSymbolResult = [];
            $newTotalMulValue = [];

            for($k = 0; $k < 5; $k++){
                $newSymbolResult[] =  implode(',', $symbolResult[$k]);
            }
            for($k = 0; $k < 25; $k++){
                $newTotalMulValue[] = '[' . $k . ']:' . $totalMulValue[$k];
            }
            $multiples[0]  = $currentMultiple;
            $multiples[1]  = $stack['ExtendFeatureByGame2'][2]['Value'];
            $slotSettings->SetGameData($slotSettings->slotId . 'SymbolResult', $newSymbolResult);
            $slotSettings->SetGameData($slotSettings->slotId . 'TotalMulValue', $totalMulValue);
            $slotSettings->SetGameData($slotSettings->slotId . 'TotalPknoCount', $totalPknoCount);
            $slotSettings->SetGameData($slotSettings->slotId . 'NextValue', $stack['ExtendFeatureByGame2'][2]['Value']);
            $stack['ExtendFeatureByGame2'][1]['Value'] = implode(',', $newTotalMulValue);
            $stack['SymbolResult'] = $newSymbolResult;

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
            foreach($stack['udsOutputWinLine'] as $index => $value){
                if($value['LinePrize'] > 0){
                    $value['LinePrize'] = $value['LinePrize'] / $originalbet * $betline;
                }
                $stack['udsOutputWinLine'][$index] = $value;
            }
            $stack['ExtraData'] = [$slotSettings->GetGameData($slotSettings->slotId . 'BombValue'), $totalPknoCount, $pkno_value, 0];
            if($isDouble == true){
                $stack['ExtraData'][3] = 1;
            }
            
            

            $stack['Type'] = $result_val['Type'];
            $stack['ID'] = $result_val['ID'];
            $stack['Version'] = $result_val['Version'];
            $stack['ErrorCode'] = $result_val['ErrorCode'];
            $result_val = $stack;
            
            if($totalWin > 0){
                // $slotSettings->SetBalance($totalWin / $this->demon);
                // $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin / $this->demon);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
            }            
            if($stack['IsRespin'] == true){
                $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', 1);
            }else{
                $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', 0);
                if($stack['WinLineCount'] == 0){
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                }                
            }
            $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines, $multiples);
            // $slotSettings->SaveLogReport(json_encode($gamelog), $totalBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon, 'bet', $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), false);
            return $result_val;
        }
        public function generateEmptyResult($slotSettings, $result_val, $slotEvent, $betline, $lines, $originalbet, $pkno_value){
            $totalBet = ($betline /  $this->demon) * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
            $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
            $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
            $symbolResult = [];
            for($k = 0; $k < 5; $k++){
                $symbolResult[$k] = explode(',', $slotSettings->GetGameData($slotSettings->slotId . 'SymbolResult')[$k]);
            }
            if($pkno_value == -1){
                while(true){
                    $diffX = mt_rand(0, 4);
                    $diffY = mt_rand(0, 4);
                    if($symbolResult[$diffX][$diffY] == 'N6'){
                        $pkno_value = $diffY * 5 + $diffX;
                        break;
                    }
                }
            }
            $symbolResult[($pkno_value % 5)][floor($pkno_value / 5)] = 'H2';
            $totalPknoCount = $slotSettings->GetGameData($slotSettings->slotId . 'TotalPknoCount');
            $totalPknoCount++;
            $totalMulValue = $slotSettings->GetGameData($slotSettings->slotId . 'TotalMulValue');
            $totalMulValue[$k] = $slotSettings->GetGameData($slotSettings->slotId . 'NextValue');
            $stack['ExtendFeatureByGame2'][0]['Value'] = $pkno_value;
            $newSymbolResult = [];
            $newTotalMulValue = [];
            $multiples = [];
            if(isset($stack['Multiple'])){
                $currentMultiple = explode(',', $stack['Multiple'])[0];
            }else{
                $currentMultiple = $slotSettings->GetGameData($slotSettings->slotId . 'NextValue');   
            }
            $multiples[0]  = $currentMultiple;
            $multiples[1]  = $stack['ExtendFeatureByGame2'][2]['Value'];
            for($k = 0; $k < 5; $k++){
                $newSymbolResult[] =  implode(',', $symbolResult[$k]);
            }
            for($k = 0; $k < 25; $k++){
                $newTotalMulValue[] = '[' . $k . ']:' . $totalMulValue[$k];
            }
            $slotSettings->SetGameData($slotSettings->slotId . 'SymbolResult', $newSymbolResult);
            $slotSettings->SetGameData($slotSettings->slotId . 'TotalMulValue', $totalMulValue);
            $slotSettings->SetGameData($slotSettings->slotId . 'TotalPknoCount', $totalPknoCount);
            $slotSettings->SetGameData($slotSettings->slotId . 'NextValue', $stack['ExtendFeatureByGame2'][2]['Value']);
            $stack['ExtendFeatureByGame2'][1]['Value'] = implode(',', $newTotalMulValue);
            $stack['SymbolResult'] = $newSymbolResult;

            if(isset($stack['GamePlaySerialNumber'])){
                $stack['GamePlaySerialNumber'] = $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber');
            }
            $stack['BaseWin'] = 0;
            $stack['WinType'] = 0;
            $stack['TotalWin'] = 0;
            $stack['IsRespin'] = false;
            $stack['udsOutputWinLine'] = [];
            $stack['ExtraData'][1] = $totalPknoCount;
            $stack['ExtraData'][2] = $pkno_value;
            $stack['ExtraData'][3] = 0;
            $stack['ExtendFeatureByGame2'][3]['Value'] = 0;
            
            $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', 0);
            $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
            $slotSettings->SetGameData($slotSettings->slotId . 'EmptySpin', 0);

            $stack['Type'] = $result_val['Type'];
            $stack['ID'] = $result_val['ID'];
            $stack['Version'] = $result_val['Version'];
            $stack['ErrorCode'] = $result_val['ErrorCode'];
            $result_val = $stack;
            
            
            $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines, $multiples);
            // $slotSettings->SaveLogReport(json_encode($gamelog), $totalBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon, $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), false);
            return $result_val;
        }
        public function parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines, $multiples){
            $currentTime = $this->getCurrentTime();
            $proof = [];
            $proof['win_line_data']             = [];
            $proof['symbol_data']               = $result_val['SymbolResult'];
            $proof['symbol_data_after']         = [];
            $proof['extra_data']                = $result_val['ExtraData'];
            if(isset($result_val['ReelPay'])){
                $proof['reel_pay']                  = $result_val['ReelPay'];
            }
            
            $proof['bonus_type']                = $result_val['BonusType'];
            $proof['special_award']             = $result_val['SpecialAward'];
            if(isset($result_val['SpecialSymbol'])){
                $proof['special_symbol']            = $result_val['SpecialSymbol'];
            }
            $proof['is_respin']                 = $result_val['IsRespin'];
            $proof['next_s_table']              = $result_val['NextSTable'];
            $proof['extend_feature_by_game']    = [];
            $proof['player_bet_multiples']      = [$betline];
            $proof['extend_feature_by_game2']   = [];
            foreach($result_val['ExtendFeatureByGame2'] as $item){
                $proof['extend_feature_by_game2'][] = [
                    'name' => $item['Name'],
                    'value' => $item['Value']
                ];
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
            if($slotEvent == 'respin'){
                $log = $slotSettings->GetGameData($slotSettings->slotId . 'GameLog');
                $log['actionlist'][1]['amount']     = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon;
                $log['actionlist'][1]['eventtime']  = $currentTime;
                
                $log['detail']['wager']['order_time']   = $currentTime;
                $log['detail']['wager']['end_time']     = $currentTime;
                $log['detail']['wager']['total_win']    = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon;
                
                $sub_log = [];
                $sub_log['sub_no']              = count($log['detail']['wager']['sub']) + 1;
                $sub_log['game_type']           = 30;
                $sub_log['rng']                 = $result_val['RngData'];
                $sub_log['multiple']            = implode(',', $multiples);
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
                $bet_action['amount']           = ($betline /  $this->demon) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet') * $lines;
                $bet_action['eventtime']        = $currentTime;
                array_push($log['actionlist'], $bet_action);
                $win_action = [];
                $win_action['action']           = 'win';
                $win_action['amount']           = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon;
                $win_action['eventtime']        = $currentTime;
                array_push($log['actionlist'], $win_action);

                $wager= [];
                $wager['seq_no']                = 'GB' . $result_val['GamePlaySerialNumber'];
                $wager['order_time']            = $currentTime;
                $wager['end_time']              = $currentTime;
                $wager['user_id']               = $slotSettings->playerId;
                $wager['game_id']               = 'GB7';
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = ($betline /  $this->demon) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet') * $lines;
                $wager['play_denom']            = 100;
                $wager['bet_multiple']          = '[' . $betline . ']';
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
                $wager['bet_tid']               =  'pro-bet-' . 'GB' . $result_val['GamePlaySerialNumber'];
                $wager['win_tid']               =  'pro-win-' . 'GB' . $result_val['GamePlaySerialNumber'];
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

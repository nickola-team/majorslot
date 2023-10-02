<?php 
namespace VanguardLTE\Games\GophersWarCQ9
{

    use Dotenv\Loader\Value;

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
            $originalbet = 2;

            $slotSettings->SetBet();  
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 11}],"msg": null}');
                    $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetBalance().'],"evt": 1}');                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());

                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex', -1);
                }else if($paramData['req'] == 2){
                    $gameDatas = $this->parseMessage($paramData['vals']);
                    $response_packet = [];
                    $result_vals = [];
                    for($i = 0; $i < count($gameDatas); $i++){
                        $gameData = json_decode($gameDatas[$i]);
                        $type = $gameData->Type;
                        $packet_id = $gameData->ID;
                        $slotSettings->SetGameData($slotSettings->slotId . 'PackID', $packet_id);
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
                            $result_val['MaxBet'] = 2500;
                            $result_val['MaxLine'] = 40;
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
                            $result_val['PromotionData'] = null;
                            $result_val['IsShowFreehand'] = false;
                            $result_val['IsAllowFreehand'] = false;
                            $result_val['FeedbackURL'] = '/feedback/?token=' . auth()->user()->api_token;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['FreeTicketList'] = null;
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = [
                                "g" => "34",
                                "s" => "5.27.1.0",
                                "l" => "2.5.2.14",
                                "si" => "32"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["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"]];
                            $result_val['FGStripCount'] = 4;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["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"],["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"],["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"],["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"]];
                        }else if($packet_id == 31 || $packet_id == 42){
                            $lines = 40;
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
                                if($packet_id == 42){
                                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                                }else{
                                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                }
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                                if(isset($gameData->PlayBet)){
                                    $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                }
                                if(isset($gameData->MiniBet)){
                                    $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lines);
                                } 
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', $betline);
                                $slotSettings->SetBet();        
                                if(isset($slotEvent['slotEvent'])){
                                    $slotSettings->SetBalance(-1 * ($betline * $lines), $slotEvent['slotEvent']);
                                    
                                }
                                
                                $_sum = ($betline * $lines) / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '662' . substr($roundstr, 3, 9);
                                $slotSettings->SetGameData($slotSettings->slotId . 'GamePlaySerialNumber', $roundstr);
                            }

                            $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet,$packet_id);
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
                                if(isset($stack['AccumlateWinAmt'])){
                                    $result_val['AccumlateWinAmt'] = $stack['AccumlateWinAmt'] / $originalbet * $betline;
                                }
                                
                                $result_val['AccumlateJPAmt'] = 0;
                                $result_val['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                                if(isset($stack['MaxRound'])){
                                    $result_val['MaxRound'] = $stack['MaxRound'];
                                }else{
                                    $result_val['MaxRound'] = 1;
                                }
                                
                                $result_val['AwardRound'] = $stack['AwardRound'];
                                $result_val['CurrentRound'] = $stack['CurrentRound'];
                                if(isset($stack['MaxSpin'])){
                                    $result_val['MaxSpin'] = $stack['MaxSpin'];
                                }else{
                                    $result_val['MaxSpin'] = 100;
                                }
                                
                                $result_val['AwardSpinTimes'] = $stack['AwardSpinTimes'];
                                $result_val['Multiple'] = 0;
                                if(isset($stack['ExtendFeatureByGame'])){
                                    $result_val['ExtendFeatureByGame'] = $stack['ExtendFeatureByGame'];
                                }
                                
                                $result_val['GameExtraData'] = "";
                            }else{
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex', -1);
                               
                            }
                        }else if($packet_id == 44){
                            $slotEvent['slotEvent'] = 'bet';
                            $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                            $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            $result_val['PlayerBet'] = $betline;
                            if(isset($stack['AccumlateWinAmt'])){
                                $result_val['AccumlateWinAmt'] = $stack['AccumlateWinAmt'] / $originalbet * $betline;
                            }
                            
                            $result_val['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                            $result_val['MaxRound'] = $stack['MaxRound'];
                            $result_val['CurrentRound'] = $stack['CurrentRound'];
                            $result_val['udcDataSet']['SelExtraData'] = [];
                            $result_val['udcDataSet']['SelMultiplier'] = [];
                            $result_val['udcDataSet']['SelSpinTimes'] = [];
                            $result_val['udcDataSet']['SelWin'] = [];
                            $result_val['udcDataSet']['PlayerSelected'] = [0];
                            //$result_val['udcDataSet'] = ["SelExtraData"=>[],"SelMultiplier"=>[],"SelSpinTimes"=>[],"SelWin"=>[],"PlayerSelected"=>[0]];
                        }else if($packet_id == 45){
                            $slotEvent['slotEvent'] = 'bet';
                            $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []);
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                            $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex',$gameData->PlayerSelectIndex);
                            $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, 1, $originalbet,$packet_id);
                           
                        }else if($packet_id == 46){
                            $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            $result_val['PlayerBet'] = $betline;
                            $result_val['TotalWinAmt'] = $stack['TotalWinAmt'] / $originalbet * $betline;
                            $result_val['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                            $result_val['NextModule'] = 20;
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
                        $lines = 40;
                    if($slotSettings->GetGameData($slotSettings->slotId . 'PackID') == 44 || $slotSettings->GetGameData($slotSettings->slotId . 'PackID') == 45){
                        $pur_level = -1;
                        $tumbAndFreeStacks= $slotSettings->GetReelStrips('bet', ($betline /  $this->demon) * $lines, 0);
                        if($tumbAndFreeStacks == null){
                            $response = 'unlogged';
                            exit( $response );
                        }
                        $stack = $tumbAndFreeStacks[0];
                        $freespinNum = $stack['udcDataSet']['SelSpinTimes'][0];
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespinNum);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $tumbAndFreeStacks);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 3);
                        if(isset($stack['TotalWinAmt'])){
                            $stack['TotalWinAmt'] = $stack['TotalWinAmt'] / $originalbet * $betline;
                        }
                        if(isset($stack['ScatterPayFromBaseGame'])){
                            $stack['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                        }
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                        // FreeSpin Balance add
                        $slotEvent['slotEvent'] = 'freespin';
                        
                        $count = 0;
                        while($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                            $result_val = [];
                            $result_val['Type'] = 3;
                            $result_val['ID'] = 145;
                            $result_val['Version'] = 0;
                            $result_val['ErrorCode'] = 0;
                            $result_val['EmulatorType'] = 0;
                            $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet,30);
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
        public function generateResult($slotSettings, $result_val, $slotEvent, $betline, $lines, $originalbet,$packetID){
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, $betline * $lines, $lines);
            $winType = $_spinSettings[0];
            $_winAvaliableMoney = $_spinSettings[1];
            // $winType = 'win';
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);

            if($slotEvent == 'freespin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
                
            }else{
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, $betline * $lines,$slotSettings->GetGameData($slotSettings->slotId . 'FreeIndex'));
                if($tumbAndFreeStacks == null){
                    $response = 'unlogged';
                    exit( $response );
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $tumbAndFreeStacks);
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                if($packetID == 31){
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $stack = $tumbAndFreeStacks[0];
                }
            }
            if(isset($stack['PlayerBet'])){
                $stack['PlayerBet'] = $betline;
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
                if(isset($stack['AwardSpinTimes'])){
                    $awardSpinTimes = $stack['AwardSpinTimes'];    
                    if(isset($stack['CurrentSpinTimes'])){
                        $currentSpinTimes = $stack['CurrentSpinTimes'];
                    }
                    
                }
                    
            }
            if(isset($stack['udsOutputWinLine']) && count($stack['udsOutputWinLine'])>0){
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
            if(isset($stack['IsTriggerFG']) && $stack['IsTriggerFG'] == true){
                $freespinNum = 15;
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $isState = true;
                }
            }
            
            
            if($packetID == 44 || $packetID == 45){
                
            }else{
                $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
                if($isState == true){
                    $slotSettings->SaveLogReport(json_encode($gamelog), $betline * $lines, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
                }
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
            if(isset($result_val['SymbolResult'])){
                $proof['symbol_data']               = $result_val['SymbolResult'];
            }
            
            $proof['symbol_data_after']         = [];
            if(isset($result_val['ExtraData'])){
                $proof['extra_data']                = $result_val['ExtraData'];
            }
            
            if(isset($result_val['ReellPosChg'])){
                $proof['reel_pos_chg']              = $result_val['ReellPosChg'];
            }
            if(isset($result_val['ReelLenChange'])){
                $proof['reel_len_change']           = $result_val['ReelLenChange'];
            }
            
            if(isset($result_val['ReelPay'])){
                $proof['reel_pay']                  = $result_val['ReelPay'];
            }
            if(isset($result_val['RespinReels'])){
                $proof['respin_reels']              = $result_val['RespinReels'];
            }
            if(isset($result_val['BonusType'])){
                $proof['bonus_type']                = $result_val['BonusType'];
            }
            if(isset($result_val['SpecialAward'])){
                $proof['special_award']             = $result_val['SpecialAward'];
            }
            if(isset($result_val['SpecialSymbol'])){
                $proof['special_symbol']            = $result_val['SpecialSymbol'];
            }
            if(isset($result_val['IsRespin'])){
                $proof['is_respin']                 = $result_val['IsRespin'];
            }
            if(isset($result_val['FreeSpin'])){
                $proof['fg_times']                  = $result_val['FreeSpin'];
            }
            
            $proof['fg_rounds']                 = floor($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame'));
            if(isset($result_val['NextSTable'])){
                $proof['next_s_table']              = $result_val['NextSTable'];
            }
            
            //$proof['extend_feature_by_game']    = $result_val['ExtendFeatureByGame'];
            if(isset($result_val['ExtendFeatureByGame'])){
                $proof['extend_feature_by_game']    = $result_val['ExtendFeatureByGame'];
            }
            
            $proof['extend_feature_by_game2']   = [];
            $proof['denom_multiple'] = 100;
            $proof['l_v']                       = "2.4.32.1";
            $proof['s_v']                       = "5.27.1.0";
            if(isset($result_val['udsOutputWinLine'])){
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
            }
            
            if($slotEvent == 'freespin'){
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
                $sub_log['game_type']           = 53;
                if(isset($result_val['RngData'])){
                    $sub_log['rng']                 = $result_val['RngData'];
                }
                
                $sub_log['multiple']            = $result_val['Multiple'];
                if(isset($result_val['TotalWin'])){
                    $sub_log['win']                 = $result_val['TotalWin'];
                }
                if(isset($result_val['WinLineCount'])){
                    $sub_log['win_line_count']      = $result_val['WinLineCount'];
                }
                
                if(isset($result_val['WinType'])){
                    $sub_log['win_type']            = $result_val['WinType'];
                }
                
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
                $bet_action['amount']           = $betline * $lines;
                $bet_action['eventtime']        = $currentTime;
                array_push($log['actionlist'], $bet_action);
                $win_action = [];
                $win_action['action']           = 'win';
                $win_action['amount']           = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                $win_action['eventtime']        = $currentTime;
                array_push($log['actionlist'], $win_action);

                $wager= [];
                if(isset($result_val['GamePlaySerialNumber'])){
                    $wager['seq_no']                = $result_val['GamePlaySerialNumber'];
                }
                
                $wager['order_time']            = $currentTime;
                $wager['end_time']              = $currentTime;
                $wager['user_id']               = $slotSettings->playerId;
                $wager['game_id']               = 34;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = $betline * $lines;
                $wager['play_denom']            = 100;
                $wager['bet_multiple']          = $betline;
                if(isset($result_val['RngData'])){
                    $wager['rng']                   = $result_val['RngData'];
                }
                
                $wager['multiple']              = $result_val['Multiple'];
                if(isset($result_val['TotalWin'])){
                    $wager['base_game_win']         = $result_val['TotalWin'];
                }
                
                $wager['win_over_limit_lock']   = 0;
                $wager['game_type']             = 0;
                if(isset($result_val['WinType'])){
                    $wager['win_type']              = $result_val['WinType'];
                }
                
                $wager['settle_type']           = 0;
                $wager['wager_type']            = 0;
                $wager['total_win']             = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                if(isset($result_val['WinLineCount'])){
                    $wager['win_line_count']        = $result_val['WinLineCount'];
                }
                
                if(isset($result_val['GamePlaySerialNumber'])){
                    $wager['bet_tid']               =  'pro-bet-' . $result_val['GamePlaySerialNumber'];
                    $wager['win_tid']               =  'pro-win-' . $result_val['GamePlaySerialNumber'];
                }
                
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

<?php 
namespace VanguardLTE\Games\LonelyPlanetCQ9
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
            $originalbet = 1;

            $slotSettings->SetBet();  
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 10}],"msg": null}');
                    $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetBalance().'],"evt": 1}');                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'TriggerFree', 0);
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
                            $result_val['MaxBet'] = 1200;
                            $result_val['MaxLine'] = 1;
                            $result_val['WinLimitLock'] = 300000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
                            $result_val['ExtendFeatureByGame'] = [["name"=>"addWildCount","value"=>0]];
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
                                "g" => "49",
                                "s" => "5.27.1.0",
                                "l" => "2.4.37.5",
                                "si" => "35"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["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"]];
                            $result_val['FGStripCount'] = 3;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["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"],["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"],["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"]];
                        }else if($packet_id == 31 || $packet_id == 42){
                            $lines = 50;
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
                                $slotSettings->SetGameData($slotSettings->slotId . 'TriggerFree', 0);
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
                                $roundstr = '667' . substr($roundstr, 3, 9);
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
                                //$result_val['ExtendFeatureByGame'] = $stack['ExtendFeatureByGame'];
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
                        $lines = 50;
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
                    if($slotSettings->GetGameData($slotSettings->slotId . 'PackID') == 31 && $slotSettings->GetGameData($slotSettings->slotId . 'TriggerFree')>0){
                        //$slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 10);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex',0);
                        $tumbAndFreeStacks= $slotSettings->GetReelStrips('bet', ($betline /  $this->demon) * $lines, $slotSettings->GetGameData($slotSettings->slotId. 'GameRounds'),$slotSettings->GetGameData($slotSettings->slotId . 'FreeIndex'));
                        $stack = $tumbAndFreeStacks[0];
                        $freespinNum = $stack['udcDataSet']['SelSpinTimes'][0];
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespinNum);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $tumbAndFreeStacks);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 3);
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
             //$winType = 'bonus';
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


            if(isset($stack['IsTriggerFG']) && $stack['IsTriggerFG'] == true){
                $slotSettings->SetGameData($slotSettings->slotId . 'TriggerFree', 1);
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
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes && ($stack['RetriggerAddRound'] == 0 && $stack['RetriggerAddSpins'] == 0)){
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
                $wager['game_id']               = 49;
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

<?php 
namespace VanguardLTE\Games\JumpHigh2CQ9
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
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 0}],"msg": null}');
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
                                "g" => "179",
                                "s" => "5.27.1.0",
                                "l" => "2.5.4.18",
                                "si" => "39"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["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"]];
                            $result_val['FGStripCount'] = 5;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["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"],["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"],["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"],["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"],["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"]];
                        }else if($packet_id == 31 || $packet_id == 42){
                            $lines = 60;
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
                                //$result_val['Multiple'] = "1";
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
                            $result_val['udcDataSet'] = $stack['udcDataSet'];
                            /*$result_val['udcDataSet']['SelExtraData'] = [];
                            $result_val['udcDataSet']['SelMultiplier'] = [];
                            $result_val['udcDataSet']['SelSpinTimes'] = [];
                            $result_val['udcDataSet']['SelWin'] = [];
                            $result_val['udcDataSet']['PlayerSelected'] = [0];*/
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
                        $lines = 60;
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
             //$winType = 'win';
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
            $result_val['Multiple'] = strval(1);
            //$result_val['Multiple'] = 0;

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
                $wager['game_id']               = 179;
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

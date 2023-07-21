<?php 
namespace VanguardLTE\Games\DiamondTreasureCQ9
{

    use Dotenv\Loader\Value;

    class Server
    {
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
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 2}],"msg": null}');
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
                            $result_val['FeedbackURL'] = null;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['FreeTicketList'] = null;
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = [
                                "g" => "144",
                                "s" => "5.27.1.0",
                                "l" => "2.4.34.3",
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
                            $result_val['FGContext'] = [["oKbE5FXBMAoMgtuGO9jFuvGyE5GjTDsRg1g3uUKpScgSkRuF6XRsnm4cTHOvLc7CgW/OOoKJnhYgg/WyYdY7EL+wiN8FD3xTOWMiQCZiFSHAnsM45Dmhx/xhtCOe5ICcV13JY94DhXm1C4ccnepbWip6xDdFPsjJsZPem2bYX2tcDxxzusW6t9w5WzRfXuQR6WdlYRuYpQEC26KtCFIqWnXLynONEHSxlq5/lPQO4fB7zsP1Jm4QbSWgRAxNbQ5f609mc1qunFWIaE8A3mCNY8N3B47hU2i8JSx7jdx6aUAZZOd1PVG2d/CYZ+A2UWHKO23sV12uSZm8vMi/","3OihuuhTynVNG7K8Y/aMcluzDMhsASP5I/74djZwqEn/FGKvmzM7/eyvTE1kyhq+Oc2FsJrzazliPxCXgTFmbyZnpQ0nzxeLTmCAarzmtSYidHYTtUJwoC0nnrYtv1pHVzfSqtY5D6jr9cyQpGa69itt6YkZghr03QZ0p53Wu2751iOJd8kmg8q0WpDrW0+Fqo/tSjXLJ+8TCmMzfO2QHVPShX//fm2/0YASyQM/IAnNKw0+fHGYtNrqRWW5af+8b80mp6BFT20dB2yYQ+yZDpQC/0SiVruT4jN2iQ==","ni9Pqk0B0XpGGlg9HDQUU/xrbzvGaFK0Dwrgy7PHdyjo1dHfTgbk/Y2GbznCGgC8KtpiDpO5dodjJ0kElALlfVLcjME2CQt+IM8Peq8Q8S5cDQYFXIbJ0RxgczGKW7UkGWcf9LM3/opWzLFtRlQZTE0mTI9QpI1tJwsnJqX98hmUedhWBXKLG/micwLkC0XXD/+pYjs6X+i4Tq9S2BvRC3b8+dCH+rpe+boLHs7s0H9hKk9ko4j5piix38NE70qxRqxkqyTdymASqlrJ2bKqG9firSZcI87hFe0dpcKUoUSVpayLa/jTog3CP0LqAlJo70j1Mt9OHL0M+ALcc+Weaz/pGMxUb0usCDBqkxp+HXKVFPIWUpUfLXRY+x4N5qyz1eNtDM0TF5Gaez5pbjgxfVF6IYOO0mM/DMK7DrxtWkB7FwsjHNbgHeInCOE=","huEZFhOQ9xAKb19ZWoY4mpzpcfL+wEoewMDIxUJlxTzKX/WQSvfN9Qt2lFB4hKI0QhH09lFSGu6nDTxtg+sRYZ5LZ7Pu64LWQnX9k0fF5mJJxrviMoHFEh4sZdlClWHEfG+67qu0el/c30CryH95h/iPpHZyD2dZCu1PD30oEQBnEWbMowK5Aswc+NZbnG2owzMvLz/V+gtOmfuKRC6opiLtGKJRGtc5DUGVXLGN9+zepxr+zlgqlpS0JGgyY53L7eBkdtH8MShkFx4QbZdXebTmJcZ74s0HCZCpAZ71seBVW0a91IxXOltFadfRlZcnHVCcee5kMuiwWjqyb5fYndVquAp0CpqDYpQtiQ==","SCHMxAbEL8wOzK8G5dOTgjjbtEPVRqlb3Qd8oYmdBleBJHaDjTGHyO+oh7eSwmiTQx+9eQ2WQLgFbf8U97jLIosOitt7VL+UE1zrtvxvLC1tTJvAaYVEGwOnINSbWYrKW3Ka6l4RntwyJbbA036dhQ/3FK6O7Mxz6DJNB34jwE4l87HT7+UdjgwQ67+Wj0INtJdTj4WQPdmoewE449l6RNhNntPm1Y7F/iZLBk8bYMDHHBzixTxuKikBaNAUC8SgUn7G3eW0orEZMoFQjud2gEtRmD9+8JwKClw6bmh1TaXl6E6DPxYlXGxBIPpd9HAO9TaNlQWfqCebyrOW"],["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"],["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"]];
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
                                    if(isset($gameData->MiniBet)){
                                        $slotSettings->SetBalance(-1 * ($betline * $lines), $slotEvent['slotEvent']);
                                    }
                                    
                                }
                                
                                $_sum = ($betline * $lines) / 100 * $slotSettings->GetPercent();
                                if(isset($gameData->MiniBet)){
                                    $_sum = ($betline * $lines) / 100 * $slotSettings->GetPercent();
                                }
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '647' . substr($roundstr, 3, 9);
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
                                $result_val['ExtendFeatureByGame'] = $stack['ExtendFeatureByGame'];
                                $result_val['GameExtraData'] = "";
                            }else{
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                               
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
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                        // FreeSpin Balance add
                        $slotEvent['slotEvent'] = 'freespin';
                        $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                        $lines = 50;
                        $count = 0;
                        while($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                            $result_val = [];
                            $result_val['Type'] = 3;
                            $result_val['ID'] = 142;
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
                    $currentSpinTimes = $stack['CurrentSpinTimes'];
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
                $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
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
            $proof['extra_data']                = $result_val['ExtraData'];
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
            $proof['extend_feature_by_game']    = $result_val['ExtendFeatureByGame'];
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
                $bet_action['amount']           = $betline * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
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
                $wager['game_id']               = 144;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = $betline * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
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

<?php 
namespace VanguardLTE\Games\RaveJump2CQ9
{
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
            $originalbet = 2;
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
                            $result_val['MaxBet'] = 2500;
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
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = [
                                "g" => "24",
                                "s" => "5.27.1.0",
                                "l" => "2.5.1.1",
                                "si" => "41"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["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"]];
                            $result_val['FGStripCount'] = 1;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["3cL5KeXVDz7OaBtCLED5+bSwYpxkFs/My6ezaVNRAHT6LZYhCuKWjpg43YylhqKxDyebOwAZuezGb7m0S+FcoK+nrAZ+ePyvMTLCEIW02vVsTBEZNi36s32idmE/7yN+ZjjW9GUfJN+ZoPNvCBzY61rVDjCnefn2f59cmiWmAggnNowCtmR0FzIc+ecFJOv0A0TbDuDOaZaeS4T0Xx6md8ncP2cahe7jPeZEo6AY78zUB4JSZRfg5kR88cB4wbwuZxi2cgszfXshUV1Z+GsFoX4g456wXdw/+w6OmsIhdmV+2pcAzhGdpawQscqILBSMlYQu9p1SfHIAc2OK+kdkPwq39uvLdz+BSfxH1pnsCAWn+7R+WymNOfVC88VM7iD9ePlKWeYPxwPlIfxYSXhGYdIhjxFaU2gI4tP860MWfhYQMPDRIF2DxQCjmvLS0+32g0knj8X2neJdhkCrDYE1E5xutw6i6MCrEvSyYglRL1vKhpjibSv8m76NP1skALIV/d3UxXDpmZB6YvcnNDCMvZBDoc6ZZA9GjIplLw==","reP3VqjuyC4rtBW7IOjl1Nw0MnLkprbWdAt74Wtm6OfeeH7hOY80XfBci6Cgo03w6GEdXOJ9CKnEVzui1Ehb2HVpD49GPOUAb825q7HedP8tE01cWcwMe0a29CDXtTWvGg91LuyX7WRVTmUfsk6NDGhMditpaAP0kjYcUI6CkHx1+Nl5SowpnUAwm5bNzGUu/AKugSqyXA2pcALW6p44Vf0D4JPyZQeG2eVkTq/wCmmlwVEwdvlMipWq5WF+eZ6t8ZOyckLedffnF9vK6Z8+z8EUM5mEfC6pztzoVInLoPHq0nEJUBGXiZzYs4qbxIWlWe/TjNyKI0V04oqn3BI9XO2soCTIeM7mqZG1zajypEGUkUWfiTeQjhmIPlD2THuA8njRjtBspGJrP4S/PHk2Yhwhsc0ygw4S54GFu/cdnNW+zwPMOcXbrh+0Rl6TSoKYZd5+KD52q0nZwfxgWK0+VE/tkeIEY83XavSPscTFN6qI4kUVIQJfTXrHYnB6Qfoamw35KaiILKOyvNCz8sXVRZFec61O5yK15zxnvA==","fctczDVFv68qibts7wwlHe9sC3DAG+ihWNQHtCawg/VE4YQa8WWbqC2PXf79UN6WaX+vtkrHS3JfnzDa/WydRXd21/iFMtdZBoG9Ku+1NOQvi26kVw8Uqk2/UKOfJD2ATRQ6JPo6L1I3g6LZVJoxkHTN23F/06s2OG+yMdkLGpWuv2JS6EFg468SYxQFEMsqg4U41t5H+AgOD1BHgT1BJoWWYvCiJn6b/dOXy00mjlPZ0qPM/0VzXNeyP1a7fkdwtO6Q3ZUsZDZDDF2a6DtFiT3Xtrs6Bd82LOzBVQAuBswYgq7MBsEpQMR4KI2HR8lenrfx5DJZYQc6iCtkBV9jhOsQNue4R7p3xpRp1sn0NS90VKY6tdocKPyaVo3lQZKv0m3z+rJ5QKRKeT7vWaDywSKsjVIuv5gbaX22oyh+7SdnG9Ghggikre35W59U5ay/ih1gyk4OK/HQ2CHjjOUXaxaVazJqyAvhXNh9tNUcBg5Q+X3+/0AtH6/BQKMNwDNMCFItZF/uRo8u2hKH6yNDdID6kbITFyt/K1RZFA==","QhJZYygDkvqv1lK7Cu3cDI1iSGxH5hWvy0XwuE75J+C7167VTLajitacrBjjHzIgZToWYUdC+tvF5xHFOLF8fAkk5798zjvzca4GGkp6paJYX5kH+uPz1O5/4cg9Y/o45JK+7xPYPJOFPZ0e2uPYjz9SrEpEq0Pe9Eus7RxHuTwJd9wOtadkcaMfLUtZtlTk0sqdvnMc86ubEWArAswiGub61RhM5f5Gvhbn+6qP7+wLujPQxVtw7OZeZSZMBTftZPrWKB53lN4DllXjp7rHblCCJPBZ1Eb8ZYbp7F9wHGIzbVNCLNALzvJMz7vfdJSA+j9VsJ3wE/oMChw6dAMr7izp6oOLWx8OnTaVfg0GjIGvExwn05Vx+keEqTEkvlUS70F1yz77PA8AeIfgz5obfbLn42IrxT1lgoHd851xrXhyTSbN+HbzzU+/MWC04zIA9vX2rmsSbaiBOVC/cH1KJDtvxIVCemfPWMcUDSNdmdsFfd7/jpj8c/JONK/9kG8dDLSNKesfEUrp4TIr89JFDj50MlVP5zaQjpRZbQ==","xtEJXbiStPXoUhVltY2iYCdVF+/a283RScp4J1qBTAs4e9Qj+VHftB2Acy3uQAvE95xn9lbUpM/r5dsx0o2ZofX87ikk6yBowso16R13cIn2PLswh+7+dfqGEvdgMhe7Q3eujWFnaQN7OZ5s9tFVLmEGP57dB75JpDtOo+ghsGx4WA0eHxy6HjTThxSaQLo4yhAoioP0jXFoVovThRaYRgucT737YwYF0ZXvfv22oYWYO5US7vSm1uzBPjnkisigsyz+dVTwjWRQKR9HNRO+Ypil2EqKUkX8L11pOVq6nDH8/I8OANvEEzjL/XiC4+wE5tkl2ab9yOTXHWFFhA7QMMbVdshN8cNwNPbSWYm+kNvdms2KknfZ4/l/yY3avAoKL3O9/I+d6rdwsuwLLzz7KQKL6sllT3JSWpW7RWpruhhL5I6Ps94ZiSjWTZpafLxV8dELmocbSBMssCQBrEtO8/3KX32sY8nAxYYAvhhl7el6WzipogF6RiMOfGXKADjgX7SyKmXPu0IKzUu4N6gHGMyIt9odyBzcOtJSsg=="]];
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
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                                $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', $betline);
                                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lines);
                                $slotSettings->SetBet();    
                                $slotSettings->SetBalance(-1 * ($betline * $lines), $slotEvent['slotEvent']);
                                $_sum = ($betline * $lines) / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '656' . substr($roundstr, 3, 9);
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
                                $result_val['AccumlateWinAmt'] = ($stack['AccumlateWinAmt'] / $originalbet * $betline);
                                $result_val['AccumlateJPAmt'] = 0;
                                $result_val['ScatterPayFromBaseGame'] = ($stack['ScatterPayFromBaseGame'] / $originalbet * $betline);
                                $result_val['MaxRound'] = $stack['MaxRound'];
                                $result_val['AwardRound'] = $stack['AwardRound'];
                                $result_val['CurrentRound'] = $stack['CurrentRound'];
                                $result_val['MaxSpin'] = $stack['MaxSpin'];
                                $result_val['AwardSpinTimes'] = $stack['AwardSpinTimes'];
                                $result_val['Multiple'] = 0;
                                $result_val['GameExtraData'] = "";
                            }else{
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                            }
                        }else if($packet_id == 43){
                            $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                            $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            $result_val['TotalWinAmt'] = ($stack['TotalWinAmt'] / $originalbet * $betline);
                            $result_val['ScatterPayFromBaseGame'] = ($stack['ScatterPayFromBaseGame'] / $originalbet * $betline);
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
                        $lines = 40;
                        $count = 0;
                        while($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                            $result_val = [];
                            $result_val['Type'] = 3;
                            $result_val['ID'] = 142;
                            $result_val['Version'] = 0;
                            $result_val['ErrorCode'] = 0;
                            $result_val['EmulatorType'] = 0;
                            if($slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') == 1){
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            }
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
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, $betline * $lines, $lines);
            $winType = $_spinSettings[0];
            $_winAvaliableMoney = $_spinSettings[1];
            // $winType = 'bonus';
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);

            if($slotEvent == 'freespin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
                
            }else{
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, $betline * $lines);
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
                $stack['BaseWin'] = ($stack['BaseWin'] / $originalbet * $betline);
            }
            $totalWin = 0;
            if(isset($stack['TotalWin']) && $stack['TotalWin'] > 0){
                $stack['TotalWin'] = ($stack['TotalWin'] / $originalbet * $betline);
                $totalWin = $stack['TotalWin'];
            }
            if(isset($stack['AccumlateWinAmt']) && $stack['AccumlateWinAmt'] > 0){
                $stack['AccumlateWinAmt'] = ($stack['AccumlateWinAmt'] / $originalbet * $betline);
            }
            if(isset($stack['AccumlateJPAmt']) && $stack['AccumlateJPAmt'] > 0){
                $stack['AccumlateJPAmt'] = ($stack['AccumlateJPAmt'] / $originalbet * $betline);
            }
            if(isset($stack['ScatterPayFromBaseGame']) && $stack['ScatterPayFromBaseGame'] > 0){
                $stack['ScatterPayFromBaseGame'] = ($stack['ScatterPayFromBaseGame'] / $originalbet * $betline);
            }
            $awardSpinTimes = 0;
            $currentSpinTimes = 0;
            if($slotEvent == 'freespin'){
                $awardSpinTimes = $stack['AwardSpinTimes'];   
                if(isset($stack['CurrentSpinTimes'])){
                    $currentSpinTimes = $stack['CurrentSpinTimes'];   
                }
            }
            if(isset($stack['udsOutputWinLine']) && $stack['udsOutputWinLine'] != null){
                foreach($stack['udsOutputWinLine'] as $index => $value){
                    if($value['LinePrize'] > 0){
                        $value['LinePrize'] = ($value['LinePrize'] / $originalbet * $betline);
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

            $result_val['Multiple'] = $stack['Multiple'];
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
            

            $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
            if($isState == true){
                $slotSettings->SaveLogReport(json_encode($gamelog), $betline * $lines, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
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
            $proof['fg_rounds']                 = floor($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame'));
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
                $sub_log['sub_no']              = $result_val['CurrentSpinTimes'];
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
                $bet_action['amount']           = $betline * $lines;
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
                $wager['game_id']               = 24;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = $betline * $lines;
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
    }

}

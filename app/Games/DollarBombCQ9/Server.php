<?php 
namespace VanguardLTE\Games\DollarBombCQ9
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
            $originalbet = 1;
            $slotSettings->SetBet();  
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 15}],"msg": null}');
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
                            $result_val['FeedbackURL'] = null;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = [
                                "g" => "218",
                                "s" => "5.27.1.0",
                                "l" => "1.1.4",
                                "si" => "60"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 2;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["91KI7qjTuuUq7eU8c/O+0KZNAqaRb9a1HAtguUwmLLf6cU1j0pFKyJO9Ta0k9wBObF7Fi6OiOyvARle05oLjNSUDPljwM6f3HSEk024se+XKZhCN+QIubA5M+NibD8PViviuBw/FxTbxppeCsFZTPPZKS2k9aXmXKV55AEUnbUuCOskDNzpaA7b4VR1jYDi0J/2PoNyrLQz6dFPmqF4kpg2u/JlFpKF2og5lKf2E2suWBpCpsSosI5d25zs=","NzzsG5R3HYZb5dQymwZbqLMV7sQnr2zhCpEyecl3Hw0ScV3FL3jO4bZmlN3iMGhsDvKsDMKXHvBfhQXcl5Hxqn64UQ4nZl9ZzmXZl3M8GIJ8i2nNOWHE+vkYbZ4RmIz1CCWpebb5w/W23Hs8ylyvt5+2yJIFbvb8696bmE4LcXZ3Ldb/U4y0557fU/5F3mXZj4JQ2x8p4w+CNA0InYOQCP5V5S9bjc4DfRpVQ2bptm17kEe+WeS6g9wMFJiVB9Z/3VMMFiJOx5A2DryR","BOEakEtBajWfl3JxR2f7D4nXWiSPmB33g3NPuE2xcsG+j1fpYmQZWxzpw7vzBRGYPUPQmR1VgoSUsHZGn+0W95CJFGOh5v0YfR7pHj/ApKYFXLEsjXNCptECZbhJt3ZNbMwBl1SYwteg+Vb/Gvto5EletUib+SJaWFsQfFDNdFnBhiJdmUt2uMLFlpx249hO0FlnLWmL4vnyoXvtERyycPlp2Y6Txeb1Uib9CK4/e02QfdmDErurjJP23VU=","NEA0ko8nM65ethvOKGUgVOoygbTfz74tqfjuGpbkx8/E/uADnWp3Kh6qBi0v75OaBvOVm2TTelMWoil1GsC0A/AaPUhs6GvzyMPltJbmN5ftJS/7g/rR2DnZlkNifYt3kalSJYZ5xOww791T4n5UR6ds4bqVBoZs4rudMYh5W2DY/wud90tQb+sCMjTTyC6nDiBfiSfF4HFAS2PuDxNRO9Z45LVDVtmOpBWKS4Mft8O16uB5mrvNWWE06vdEHIO6TOnAK+OM292b2dCe","aLDVVOF9gK4J7TpczgEqI4C5L81ras3XpHzvik8BnoQeTUdF7bK5CzgTR+6gc+JKomRKH2IKyyoFiBxpTp/4S6CfmMWEJzWaBvwUPXIoPXVNvob201ZPSRshAmRLNWsM6F+jepdZIL/ONjtcl/jjF/CSzI8TZqQarjsp3xpj7v0u1tpbPn+8eEygmCBcuUqnPokVQ3atIx5JYN9qGneaMag+/8KrmTSrCBJdK3C7CU4yz/7O4Wf0sGIWflW2pya9lNdXshtaMu8NMMOW","RwrlBF7iKbSpOkxpRvS51kiCzSwJ10CpukID1TDG75z4T2DiJ32WwhhAtsTawvUSXUrBgip3y8QX1D5BKgJGGtaeXK8RHwW7uO/s06vnfePvLsrLNkQRAcxEE9Kscpl0J5jS4u351OHHrLkbgKsg0ebUBoqne9qLrCL39nq/VjdPgY5sRrx6R7FAVNG580v7iZb1WvRClnzmj8aoXik3u+FuLxY7oEaDmU/LaNKVbN0qzL9BmZlF/5acQH6GVnyWgNp6Rr52Y8Ag7Y9y"],["mjRA0HT1Pf6CRUfxJI/Lo4zPBGur33gLc9mUkCZHHxiOCNEeYK+VSuAhG5NZZKubTT6uTalgXGWZxx92f0mnQED+ig/tahiF1aBYZuusyIdJlhrVsXhZ2laleBlDDs6LEhCr1BCMBZTt32yUf72fL+u9rq0I9L01tAudA8vk7G6Erj3GrBjl66NOMUyWJL7QYn1dRvGS7+n+Q8JB","7sHwyH8hu8TTAGFgdcT4xweFSXmXGHFlYMsN8HyLn+iPgCsRFfqSzgUNH3SMYiNblVUIAg13zjpMHrjJ3Vauqnq/9evQmU5UASwIbwsG6seqwVWOs0+BT0KiawqWJzh79YZuKll6iDVOiUK89mp91yCmcPAAUkxAcaHH2CcLHnlDJDhVBCAsCX4s8cs=","OXOcFcP8iYkxKKmMuHkyh/0NITrcuel7/RW9ABBNFk2RwDH+sC8U26/FXgP7/Q50CLhPuE4nNylGehNj8mN5f+6j1l+L+xeW+TeUn/ssGcL39FKoLgyS8bkRo/CWoFOAXqeDOF6CxPnNvUWIZ/217nLi2sQwZgw0EGcd0I79SUbDxCT2uf5tjUIYIuE=","5J1p9bCmgS5rAedUrQHkTdLWVI+HsBL6NdULEQ1o4Qb2XO4zZ2X3Fe4R+JQx3lGIAlh5BDB9Mn3MrBR+WNdq1wzNmfGfy92AnF2v2hx45fStUO2XreEDD0WQUMcojbdkQEwHOu9/Z3hc5MSp/0rkBbLPlB6E6Wlq2MBuVI+Z9mxW72X3r+jr2L04cMA=","0WkAED38kBdSuMqiJ+9+LQNUBmCYGeXSLKGxkU9/qWEglBHYyvTIjB+fHp5iQAVVZHOJOTiFj0oOClBv+7Al1sr7bpmWpUam0YELEwBM0zEZ45JpPWMEJ1yoIGKciuWVJ/M7DnACwzvyZmWXPYihSbIt/BlLcvHfTFBPmqzh1JmVZC4djgckL7ClWPM=","6dnWH2Iy7ihGzeB3TNq2ElIQVfIohZk4t37bUN+LnYNhPK953OBLEv5JMewaixH6wcEsRJVleD2sxY/K3weCi68/mjIuOnx8jCEg73QEfbFFhz1aKKhv5cOMF/rlzr6S1hXY+KoC6BdFXJH8IjRCEKPM3y2MBwZaCdUP5wdtdWESmV1TBHxRHQZcCmk="]];
                            $result_val['FGStripCount'] = 1;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["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"]];
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
                                $roundstr = '641' . substr($roundstr, 3, 9);
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
                                $result_val['Multiple'] = 1;
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
                    $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                        $lines = 50;
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                        // FreeSpin Balance add
                        $slotEvent['slotEvent'] = 'freespin';
                        
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
             //$winType = 'win';
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
            foreach($stack['udsOutputWinLine'] as $index => $value){
                if($value['LinePrize'] > 0){
                    $value['LinePrize'] = ($value['LinePrize'] / $originalbet * $betline);
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
                $slotSettings->SetBalance($totalWin);
                if($winType == 'bonus'){
                    $slotSettings->SetBank('bonus', -1 * $totalWin);   
                }else{
                    $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
                }
                //$slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + ($totalWin));
            }

            $result_val['Multiple'] = "1";
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
                $result_val['Multiple'] = strval($currentSpinTimes);
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
                $wager['game_id']               = 218;
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

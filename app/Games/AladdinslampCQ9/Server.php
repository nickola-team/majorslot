<?php 
namespace VanguardLTE\Games\AladdinslampCQ9
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
                            $result_val['MaxBet'] = 1200;
                            $result_val['MaxLine'] = 50;
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
                                "g" => "177",
                                "s" => "5.27.1.0",
                                "l" => "2.5.2.75",
                                "si" => "58"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["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"]];
                            $result_val['FGStripCount'] = 1;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["eQPhRaJBkjiAILTVvlcKhY4b8lJcSf3Rle/tjHgKsr4/Px2/jR8OWHEVyWJ3xa9/ICoCvfX9daSASxW60rU1HxdJWFSiIs9iuZnSV837/h9wbGgGpfEtHwvyK1FuBzyFom6ojGl7+PH4FBIFITns88EMn4XfAmkFPNbRrJSdiHqG8LOt8bklIehGiQH9h8atE5rzssEuN/saZXpixCtIVzNz4VtYYeg4GRDxTVuLn4lJ1MN/M7n1V/olifAPKiID5nGymxrmTtvouw3wsYkFgXafIBWpCaSV36JGPqEtMMn5Y2BpkuxVDWPKRsoXtfmp56bj00Bdv+WbPxpB3/UHSkumHzzofNZuuQihSkyQU73LkVhVOXeb5xHbexA6Pyxfb8D0zJ+/0wwAMAuAOwZDDAoR1dJMr2ItNqIcqLleYLsrqWzUolJ19w/B/sn+PvQWHnlk2z88+ggj+aU8","ZLRfB5qN2RDfxAxzDDuvfXeTpHX/t1Rqzt0ir+sIGv1ylsBZH9YmZfzVoRp45TtMx2IvUtnu0jjTFG07IJcPfjZuoBmWAQT4Ot+25oT1PrpoXySqpltavAqjWE9TLmxdm9rb9J9iSE6vL9GKaJEnxhhYd5JqAi8CDlMxwxFhWjA7C0cSkJ4xomLuZmu6iJ38Hnn1VESKmyy0Pov4p8iHh6YOvAD8WdguJtBFwUQpXJNjpac3IZ6zM8MxGfxEJ2CZigBDivXIjxUqZ1QkQ+p7vvVXtnxZ9MWpxgHhYmeiFohwB4MRKW5XtJAdwKsEAUaXYI/9DbbiwSFrD1+1cbSmRyP7Ft0Zw5XFg3Cg3/C24yIf+ADKx3VPKmzrWVoYXxiZrtgRICxo+Ch88AaVlYYAs3tvXt8J5rGxou/0utnUBlpwW4DuqKwUFdyBD7UJxdfvGy5fTiR+vaB4LyamyueDd80tOdeECjsdEFiRxMVJdVcFzKMuTHvpG5kn4hZDGGxkIVouz51ZGgGBGXL8ByeveSPBf49WclnXmWLfaqNHJPkXtW/NKyQ//A9Hc0oA2awO+ZwzN1ogNjukYtCcW4ICljwTsdk+aZ5KsWwKTDXEnv6JcBt3Dg6S8zBaHW3ArVUXxsDAm8d6ziAvzPfaY3ij+98dYz1hS88/kk6qagAM47s3kIW59MSf7tOILjxRJD/3Q4olR1YyKQZK0ras7MzdsvubYiAvel1AUmBk1QS3311D8SGlmZvh6+VTFhs25pwMeDqW6yORSaKjDL8tr9W7HGAB06xBHXeNJivcg8NQsG1YmcIOyp2bBANTC48KmL+y5dr4uYpt2DM98Fc9","Sn2ua8VAqzcfsemnZMfSsjgBIIFhRrP3lnaNmoB9h9dNUskjWbqVUFQcLJbPEgJ8ZyFfL2rCQ/6sjTY8qaABkF99JveSKTRWGbqoc+x3YiCrzsFH1szwf4hUPEh62utMpySon3HFLM83sO3fUa7xg7jGkPogx7+F0/H268Vj4Y6Vn/n5Lrzj/q2Hsefj6ds7MJ1CPmyQCFGuC1JcwBEUhxGy2nWy5CjpMyVK78owYkns1fq5wcdW86y2TwHX5WCEOHpmybLiGGHA/H/snovuwjsNVvHikzbp3yq6qIZx+LV5f/GQhRuzYHX6l1Bf5Y4oIJYarEnS9f5ywo621B3vOPBE5tlg149T88R5i6ac8OzddV7JiZ5WgpdkiWikVLmax+Y9D5UMZYVhJH3kvr+J4JEm/4y5FuG99/5GB1Cc1HoAOX6u/PRoG1yl2aAOL41DENlu5DB41+z0gh6AvFn3+KpiA9j5y6vC0Hpwk24AECxA4rR8BiTO7QjDvM1RZsvseP3fuR1gs7zFxErDaJEDrYPX3IdfCEwSKCMm3D0ebCVjIXodqbM1dqwGPAx/DHzZNoGpOnOyFoXSwfXxJQetQ7+v/4uUF4cFgt5Ghvd3Vq4Sg5ebWZhBm6X2TQrQqzbdHBcS/LBdOa/xA5ApfIz8KX2q9f8/IEepNYi/dxpEwYdlY3ZQ6+ZfObcAEh1fUbUmZabdXnp8++iYd86ETGiKXJ7POF6PSnM4UmWCLccxmE3nFKNudArvXJZ1d+/Nzj/UMyuJhcjxdqO37YN6c/Low9o54gFsEvgG/EMZI+EurA6AWjL2ZoHTUuKCbb8=","pWogCqFVDkNflfZeylWkQ/SBaGN8/UhcRQwx36qvz8sIWIUS3U4jYI4q1eGOTuA9aqhVrfTpA+Kw4HaONv2aYLAjk5dROoakK+JWU3BLWJsMdErb0LGYx19O4FSCq/l44Hc9DYSK2nDxZbzoWYEhXIALELvr2CJUR24tKLji8afFgWXf6TSRvradjLAq0IozUKcZw3JxTTwKbSvO4Qee9ko3r+asW4rl1n3J6unj/3EdklJRlxpyNVyfG7a2EqGiaaykaNAUoNZeVX6mON54itS07Ckff2HESypsKCJl49W3BzkwiTRZ81gl/Cn4+yg1v/z5uPrdiF3Y82AJkxKBDIks2v6U8fA67B+wjw7mE0JBwV6RkGmRcOeheYrF9pLKeeyTHtl4bGwlOj4O6oXmsemUFB3L/VN6XxIdIgT3BTatO2hM8XS503iIMsZJzjIbKk7a2zY7b14Os313m9ET/fkXvTj/Ohjq5ydWcFv7htOX+gqh1S9Hcv4TS8m4CRodXefTNokisjPHDXHlnFUVpZnHJrLwmXNfJEBDD0yAg9D572qeeKRjC41ClMULK3nJ+JQWq6DzZEehwq+YCncWhIWsLpDBWGhF8Hz2cm8u0IwMObNL8Cf0/Jr8TUrUW41KtFvpDUrizC1XQtv5M6aCzxyIu6ZgLta8ghtElqjYT9Gx1aAupHEGfwltVck9AaviV42Ey1B+nZyC7GlW","1ZRKtzRgSZP4iuGsySbHaka2ApKidDd0aMTpFqalZl+Is/ots9hm6Rr9mN2UpBcyFLjXqK0A/xfxugU07XYKOLT72kX9giXeKGQT++B7enBQmbeI3Rmn+mWhqGgp5/6jf5n32b59juGD3dFdjWn4tu8mQxpknLHZnGuSv9kHT36VCRAHtyRPOEvjygmEcDGoebxOnZajzYzrOiEn8r1XwOcqYPzCez3/c/FC6lCl3OCtahc9SE9mEySt3v16C0GLW3ADfg/OnQGroZLfJAWYBS3J+vN+k4RYY+/CksUStC+cvIe2RHk4G4IccOWNtPo/Upw/L9R/3htTcLHmwpIocjf1SuicNhva28JdLn2wEvTFAubsq43o/9BrM4Q5344pbXgxjCAYL0ZrwYW31fa1vRBe7oKJYOqrs1aH1LniKkrDzTYs0s3R/NS5665kgvjukYSq0sPgERVftjfydzvPXuLashxsGALovS4WkjLOpV1fy/6COnSMr/QGyVVyYkG+ZdkRIIGuDiFOizk603wPyC1EVjG/yfurj9IBozEV+o7yzbyeX4htzgJlRUIQGT9FXVC255adMRzrbXqIhLNaF64RJeJSVkVJwmOEpnQwy9vh9pmymncUGbMKyvI="]];
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
                $currentSpinTimes = $stack['CurrentSpinTimes'];   
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
                $result_val['Multiple'] = "'". $currentSpinTimes . "'";
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes){
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
                $wager['game_id']               = 177;
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

<?php 
namespace VanguardLTE\Games\ChickyParmParmCQ9
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
            $originalbet = 10;
            $slotSettings->SetBet();     
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 5}],"msg": null}');
                    $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetBalance().'],"evt": 1}');                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());

                    $slotSettings->SetGameData($slotSettings->slotId. 'GameRounds',1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount',0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Multiple',1);
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
                            $result_val['MaxBet'] = 7000;
                            $result_val['MaxLine'] = 1;
                            $result_val['WinLimitLock'] = 255000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = [0,0,15,0];
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
                                "g" => "104",
                                "s" => "5.27.1.0",
                                "l" => "2.4.3.1",
                                "si" => "60"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 3;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["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"],["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"],["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"]];
                            $result_val['FGStripCount'] = 3;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["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"],["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"],["wwI1Jmuv9tCoqFrW1Qy8wMKYmh9LeXlo0EqnY1mI5RQkO/EwWOCD9akwpYiprwWlUOlCIUjtVCf5WrC0jQTRNlpoy4BqikOqzWhBj4HWU3CVoO0zJPuJU5ts+wNqgD4lWWxRw46G8LVwS07vy4eoceiK4LeP1yZCjmFJcJaogu1Eab5n1mDvMWs0pk+2BzDJsbZzJeHTxg277u+9QsAHerIDdjrkUxIiFfOIP6gu6mjKX76BPXznECerZ/Aup4ysAqnCA+T7cNSSgIEZKB3btHMR+05dvjJfJAjI4n/OCqZdJ/2WCy5hxa2142LWMID21j2rMPbyjURKX9wZSUB5AYSAx0ep+UThMkvFEnlTaxdKf/FvkFNj8dSg0urU67oGURGt7FEoOhukNaa2clkAk8lsb5J81m0R/epP81Rj3d4HLEy8UiD3bQnr8yqu68O/I7p2SD8hmBptgQWcPZqo+QYKukc7554vcf/UDvQrDQ/gG45KSuW9q6obyIy3gnSCycmvXXwx0s6ZCn8uILEIjjaLmGX20d93DQQ+G2fGEy5Gf9vVdjjrTHPfLa9xqLwtJyJW1Pbie0PqYgtIjTFBGoS6tqjPNljmdhMh42D/cISV7hF7HMQzwQWGESbs6ZdK2hKOHCUamupspd11UYo8bVYk35p2w+g//JZiSw==","ub2BmPtF5NM5rbmOAEOOVe5C/9ew2+NUtwWzU7XrHiAWGwerM9nTGF5CPecj49KZT9uNbl7vVIPBF21y0KQXA4jQzgi2ycXJqg0s/SBKtsqUmeQTJZ8E83zCt8G96y61blCK1J2aACfMrO4f9cq891Hk96MsSB8j3P9wGQ1qXD8nrsUrYpnKBS8K871CiC/3o6RpxrWP54thlts0v8Ux1SKXJbnc14jH4WRM9CcHyUyE9LsVxuzIWK+ci3GzVtedNqRRR9E/wsEBrxZJq99ay3SvZ43zeVyptbVM+8Rf/DvoPYlkuUR7jzcI3u9qHNqvZ8C2PuVKik/yIYZWUguNHkT4pbWwC15xXIIEhiAgQYAfQY30QZp38n6nD8JOhac/J0RXW2tC6Ys/Bnl0T4YVItf8ytKNah3Ky+4izSD1n+Z1bqm0CW51vaYnLuIWib+/YtQzsTiQI9h5JHnpxhpk4zd2/KEPbuWbRYFo/r9uBIS6CWWShwW+NrZDWUyBovl3nzkylRm3UEhHUCVch77U7am7QhbfdDtUjpmjYzP2/IMGDtubMW28BR98SSL0D0FWHLM8pb//Io9aFhRBBfUGGH/nB3c7jp9HDklWLtQdiYK7Oe21jo6oEPwp598GosY5ujlcePehE54gHAZ01L3Si34vc4Ku2rVH9Kv3Fw==","VvPN8mI5APbSSA0euy4MUzOWILQ6WiA+WxXDK95BXzc+qsOkGJXncJQvTqKoznlGbWgP1Wix6wKhfNBY04IBSI8tk+5hNR+bUicvg3KEiHTdnuz5Q53Z51y8CnpdwSLahAKcwmL++7WG6zt/O9dN6t8CkOk0czaMiNs3Z05WuSvH9Rpimq8WjrXvdwOoL0d7Pd4zWFK5aT6gV5SDrxCU5WjY1/Fjg36i5oVQkqZpwROy2XPNTyJdFQa0EkBkdms/xuhwPttIFmtaMD2lXcjxvaaLtGO5t2+XknSjcY5xzEtXVHhxgCDpmka19NfJN+cAd0UYsy8G3Y7LEtw4I0tXhQKjgKV+9kVE1taxXWN2ZVJrKxG/37+tDxaKKz+ZREc4mPcZSv+ceur7BkpLx1Ut/x0t9xVbY7DhI5KGb6KWRyB/aaZnqCaYdWk8btpAc9A6w5FD9XZDtCdZBWvNE9otPdiHmrTMLwV87DFJ3kWjugG/m5AWceQGD0oJjT+5gK6NrTaU2nBZYtKZQKSgxx+oFdCyQ2OhM6CaJr+LHSe6zcoVycziFKz94HtX5Iaz5APn3hUmha+ZrhQ48nqHZpZnqLgjPHVb5JzV9DDB94hzqfOom8LW1yWwRyOka58olSu7491QyUMUfiIGoGpT2js35wRkuJBgfh3UWTzi0Q==","nw31KlUoG5pbh1Uz7DMBjsQUPUOXJjFREvb1HTRyj37d5y6nVlZNKKSh3oFS2PETYBxXvOM5cIiQN+8WIeBMuN2YMrk65pG5A023wF2J+6Hrp4BMjJ5npNRLz8zugvAtvg+zVL0rNIZ8ABn7jcCA0uwdRaOw/5IfxwLKuT/gNrt1045IYY/3Mw/arq3npzvpBHJbgXbCn5BmnS6dbs0Qa9JWOFm+poS3+B0PP+j0fXa8b1o/dP+2yG38jsU6JIPbVUj3KPu1XmBd66ti4WjczVP93BXHze3fAUc2yNrK2tWcqlHd1/wiUeIZByBDThBhRuFpSaKl1lBzKu1BVq+glOKh3t+Ccf9cSfiqAwFjInWN6iA7APgBgpuyD6n8JbZJFjFtnYAGet7ifnXr4DLd+F1pPqvrJIfATWkW058CgEqDq+cQkgRtMrWPzLRUxjOmhIVy4dqZIsuRwTEKxSdnuVcTIIBS8aiAwwQHkQfeyaKU084tcJCxyrRJEHlzfKNaJyHZXC/ATnLk7UoxBvHdmPexj7PprK7SyryOlK2bT/Jv4DBSQgyy7fa3SSdcqStsa280DbWguitJuHVjSnER/LQ6qOiADGvT6Mkf3rEKAc8CtW1n9iUPZCeFa+8UfrrfMxibSb6Erk0nXOZdcX0SCknu8mSaZHy0rSby+Q==","yn8BQuf77PTwew9wYVDov+vGteHJFP+BZEz1g5wgZ0c2AiW1HDy9sdegFEiYvBT4S9OX3jKNhTRpQURNAGgho/7jlXRW0n0EgnWo2VxDivcjIeBdMnMKX9pBbzquI+RWdLOZbWkhVPQHQANlF4s4P/uLPYB9NHnQg6rJ58efCcRyF5GAe7OAhUd6EIebk2ZG+9GTOI6NOGjxC3G7KhyqSE4RVCAl8AHYEtg+NeNZIqYyj+asCgJNEUtg4hwNv5lbvYhwoYxMypjyzxo0GyhYlPYTbWE/R+6UH6KqnupJ1icXEDUr/1LpxiesrV2iKGLAt1h3A7SxR1mPniMBxE1QenTsjYihyMX7f+hfg2kWadE8HMzNhdeLWM8/snV3sEg3CaO9thZDt/iGwAC8bov/h7Ume70nVeog9cEgmjxApwjGMJKYBJyKlTBezC8jZvO465PragCV3uNX4jIyoFSLRQ+6YVF47oPn+lmiIBSTcNnfvjT08efL5jKfZ8w4at/22OMeXyNP3EL3hSa8MA2b6MQtiHAh5mN+KsdPW2qu3ljuEktUk3424Tn2mwtKGp5W/xRigCcQGso3+awbTtIu122UDz1RRbRxLdwsEkbn0G/VfEkRhdNJAVrfu9O/f4eooMoRhKkkKrTs4cNlH9oWZ5IDyHFO3e673tnAug==","95MqjO9g83mZcaEIua4O7Z62WSldkb5J26HJXlf51z8RBXedYUOvTdpw92S531JG2IxILvekaCpoJD5eugmSTiPFx2beMU94Ykbh3ab60qJKk72YOCvidvynF2qy4VRPmRJHCkM5uuBW0DgTU1gtJxpIBWTVt4fZPho0/zKNx5KRtyjnirg4Ge10RSQgfDd8YTZ2WbOMQI+q0EkmjRyNt9jCX/xmcygOtv9//JwXA7QdzaRXYndW2PfMaBDLoJJl9ZHuVBdqcfnRwdKphTTOxzUpADZAmO+FIe8UhQMmYziy2UVp+vU4guArIC+3EBZovKpahC4FlQtxZB773B56rudAkakINt1emxexwKlpVOTr1VeW343cwO6eLBQrz/NawCAnc4MFj6Yx+HgjI7GbfLGQ5LaWt47CQRxUkxmWOIAMkKV9BWwgdWSBQ2TZjlpkTC46uYeaMXHsBPdq88xGaSrkfEOCxaZ5pQFdGdvQ2zMCQNFnfSFBbq6klpdxWZBcgfyW4XDGgDUiEtA3Cmv8sGQ0GkR8fBipwXzd/PiYCjlXJ2YtSsiSFiIwo45ngNXni6P9/e6uMfsyUlsKxgD8qTidg73+IMBZ4cOhWw69B+bYFPKhUa50hl/nbmktaysGV1rB+jdZfQkFwcXBl6KTe+8gzfWaxjUEShR8gw=="]];
                        }else if($packet_id == 31 || $packet_id == 42 || $packet_id == 33){
                            $lines = 10;
                            if($packet_id == 31 || $packet_id == 33){
                                $betline = $gameData->PlayBet;// * $gameData->MiniBet;
                            }else if($packet_id == 42){
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            }
                            if($packet_id == 42 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                                $slotEvent['slotEvent'] = 'freespin';
                            }else if($packet_id == 33 && $slotSettings->GetGameData($slotSettings->slotId . 'Respin') > 0){
                                $slotEvent['slotEvent'] = 'respin';
                            }else{
                                if($packet_id == 31){
                                    $slotEvent['slotEvent'] = 'bet';
                                    $slotSettings->SetGameData($slotSettings->slotId. 'GameRounds',$slotSettings->GetGameData($slotSettings->slotId . 'GameRounds'));
                                    $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount',$slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount'));
                                }
                                
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
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
                                $roundstr = '662' . substr($roundstr, 3, 9);
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
                                $result_val['AccumlateWinAmt'] = $stack['AccumlateWinAmt'] / $originalbet * $betline;
                                $result_val['AccumlateJPAmt'] = 0;
                                $result_val['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                                $result_val['MaxRound'] = $stack['MaxRound'];
                                $result_val['AwardRound'] = $stack['AwardRound'];
                                $result_val['CurrentRound'] = $stack['CurrentRound'];
                                $result_val['MaxSpin'] = $stack['MaxSpin'];
                                $result_val['AwardSpinTimes'] = $stack['AwardSpinTimes'];
                                //$result_val['Multiple'] = 1;
                                $result_val['Multiple'] = $slotSettings->GetGameData($slotSettings->slotId . 'Multiple');
                                $result_val['GameExtraData'] = "";
                            }else{
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                if($packet_id == 32){
                                    if($slotSettings->GetGameData($slotSettings->slotId . 'NextRoundAction') == 1){
                                        $slotSettings->SetGameData($slotSettings->slotId . 'GameRounds', $slotSettings->GetGameData($slotSettings->slotId . 'GameRounds') + 1);
                                        $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', $slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount') - 15);
                                        if($slotSettings->GetGameData($slotSettings->slotId . 'GameRounds') > 3){
                                            $slotSettings->SetGameData($slotSettings->slotId . 'GameRounds', 1);
                                            $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', 0);
                                        }
                                        
                                        //$stack['ExtraData'] = [$slotSettings->GetGameData($slotSettings->slotId . 'GameRounds'),$slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount'),15,0];
                                        $nextRoundAction = false;
                                        $slotSettings->SetGameData($slotSettings->slotId . 'NextRoundAction', 0);
                                    }
                                }
                                
                            }
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
                    if(($packet_id == 32 || $packet_id == 31 || $packet_id == 33) && $type == 3){
                        $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetGameData($slotSettings->slotId . 'CurrentBalance').'],"evt": 1}');
                    }
                }else if($paramData['req'] == 202){
                    $gameDatas = $this->parseMessage($paramData['vals']);
                    $game_id = json_decode($gameDatas[0])->GameID;
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1, "'. $slotSettings->GetNewGameLink($game_id) .'"],"msg": null}');
                }else if($paramData['req'] == 1000){  // socket closed
                    $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                        $lines = 10;
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

                    if($slotSettings->GetGameData($slotSettings->slotId . 'Respin') > 0){
                        $slotEvent['slotEvent'] = 'respin';
                        while($slotSettings->GetGameData($slotSettings->slotId . 'Respin') > 0){
                            $result_val = [];
                            $result_val['Type'] = 3;
                            $result_val['ID'] = 133;
                            $result_val['Version'] = 0;
                            $result_val['ErrorCode'] = 0;
                            $result_val['EmulatorType'] = 0;
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

            if($slotEvent == 'freespin' || $slotEvent == 'respin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
            }else{
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, $betline * $lines, $slotSettings->GetGameData($slotSettings->slotId. 'GameRounds'));
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
            // $slotSettings->SetGameData($slotSettings->slotId . 'GameRounds', 2);
            // $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', 13);
            $slotSettings->SetGameData($slotSettings->slotId . 'Multiple',$stack['Multiple']);
            $newExtraSymbolCount = 0;            
            $symbolWinCount  = count($stack['udsOutputWinLine']);
            if(isset($stack['udsOutputWinLine']) && $symbolWinCount > 0){
                foreach($stack['udsOutputWinLine'] as $value){
                    if($value['SymbolId'] != "F" && $value['LinePrize'] == 0){
                        $newExtraSymbolCount += $value['SymbolCount'];
                    }
                }
            }
            
            if($newExtraSymbolCount > 0){
                $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', $slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount') + $newExtraSymbolCount);
            }
            $stack['ExtraData'] = [$slotSettings->GetGameData($slotSettings->slotId . 'GameRounds'),$slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount'),15,0];
            if($slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount') >= 15){   
                if($slotSettings->GetGameData($slotSettings->slotId . 'GameRounds') + 1 > 3){
                    $stack['ExtraData'][3] = 1;
                }else{
                    $stack['ExtraData'][3] = $slotSettings->GetGameData($slotSettings->slotId . 'GameRounds') + 1;
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'NextRoundAction', 1);
            }else{
                $stack['ExtraData'][3] = 0;
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
            $newRespin = false;
            if($stack['IsRespin'] == true){
                $newRespin = true;
                $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 1);
            }else{
                $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
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
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes && $newRespin == false){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $isState = true;
                }
            }else if($newRespin == true){
                $isState = false;
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
            $proof['bonus_type']                = $result_val['BonusType'];
            $proof['denom_multiple']            = 100;
            $proof['extend_feature_by_game']   = [];
            // foreach($result_val['ExtendFeatureByGame'] as $item){
            //     $newItem = [];
            //     $newItem['name'] = $item['Name'];
            //     if(isset($item['Value'])){
            //         $newItem['value'] = $item['Value'];
            //     }
            //     $proof['extend_feature_by_game'][] = $newItem;
            // }
            $proof['extend_feature_by_game2']   = [];
            $proof['extra_data']                = $result_val['ExtraData'];
            if($proof['extra_data'][0] == 1){
                $proof['extra_data'][4] = 30;
            }else if($proof['extra_data'][0] == 2){
                $proof['extra_data'][4] = 21;
            }else if($proof['extra_data'][0] == 3){
                $proof['extra_data'][4] = 11;
            }
            if(isset($result_val['CurrentRound'])){
                $proof['fg_rounds']                 = $result_val['CurrentRound'];
            }
            
            if(isset($result_val['FreeSpin'])){
                //$proof['fg_times']                  = $result_val['FreeSpin'];
                $proof['fg_times']                  = 0;
            }
            $proof['free_ticket']  = null;
            $proof['free_ticket_given_type'] = null;
            $proof['free_ticket_times'] = 0;
            $proof['free_ticket_type'] = null;
            $proof['free_ticket_used_times'] = 0;
            $proof['is_respin']                 = $result_val['IsRespin'];
            $proof['next_s_table']              = $result_val['NextSTable'];
            $proof['pay_addition_card_id']              = null;
            $proof['pay_addition_given_type']              = null;
            $proof['pay_addition_rate']              = null;
            $proof['reel_len_change']           = $result_val['ReelLenChange'];
            if(isset($result_val['ReelPay'])){
                $proof['reel_pay']                  = $result_val['ReelPay'];
            }
            $proof['reel_pos_chg']              = $result_val['ReellPosChg'];
            $proof['respin_reels']              = $result_val['RespinReels'];
            $proof['special_award']             = $result_val['SpecialAward'];
            $proof['special_symbol']            = $result_val['SpecialSymbol'];
            $proof['l_v']                       = "2.4.34.3";
            $proof['s_v']                       = "5.27.1.0";
            $proof['symbol_data']               = $result_val['SymbolResult'];
            $proof['symbol_data_after']         = [];
            $proof['win_line_data']             = [];
            if(isset($result_val['LockPos'])){
                $proof['lock_position']         = $result_val['LockPos'];
            }

            foreach( $result_val['udsOutputWinLine'] as $index => $outWinLine) 
            {
                $lineData = [];
                $lineData['line_extra_data']    = $outWinLine['LineExtraData'];
                $lineData['line_multiplier']    = $outWinLine['LineMultiplier'];
                $lineData['line_prize']         = $outWinLine['LinePrize'];
                $lineData['line_type']          = $outWinLine['LineType'];
                $lineData['num_of_kind']        = $outWinLine['NumOfKind'];
                $lineData['symbol_count']       = $outWinLine['SymbolCount'];
                $lineData['symbol_id']          = $outWinLine['SymbolId'];
                
                $lineData['win_line_no']        = $outWinLine['WinLineNo'];
                $lineData['win_position']       = $outWinLine['WinPosition'];
                array_push($proof['win_line_data'], $lineData);
            }
            if($slotEvent == 'freespin' || $slotEvent == 'respin'){
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
                if($slotEvent == 'freespin'){
                    $sub_log['game_type']           = 51;
                }else{
                    $sub_log['game_type']           = 30;
                }
                $sub_log['rng']                 = $result_val['RngData'];
                $sub_log['multiple']            = $result_val['Multiple'];
                $sub_log['win']                 = $result_val['TotalWin'];
                $sub_log['win_line_count']      = $result_val['WinLineCount'];
                $sub_log['win_type']            = $result_val['WinType'];
                $sub_log['proof']               = $proof;
                array_push($log['detail']['wager']['sub'], $sub_log);
            }else{
                $log = [];
                $log['account']                 = '' . $slotSettings->playerId;
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
                $wager['user_id']               = '' . $slotSettings->playerId;
                $wager['game_id']               = '104';
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = '' . $betline * $lines;
                $wager['play_denom']            = "100";
                $wager['bet_multiple']          = '' . $betline;
                $wager['rng']                   = $result_val['RngData'];
                $wager['multiple']              = $result_val['Multiple'];
                $wager['base_game_win']         = '' . $result_val['TotalWin'];
                $wager['win_over_limit_lock']   = 0;
                $wager['game_type']             = 0;
                $wager['win_type']              = $result_val['WinType'];
                $wager['settle_type']           = 0;
                $wager['wager_type']            = 0;
                $wager['total_win']             = '' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
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

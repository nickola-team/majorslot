<?php 
namespace VanguardLTE\Games\JewelLuxuryCQ9
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
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 14}],"msg": null}');
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
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 3;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["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"],["uuIFfJvjh2CYza1g4PQcDJ1oKlJeqFmDtMJBJ/jjWQE1As2Hq3vKBdBUzva9/D1L5gKJxZ8Sm14IJMahjKO43tIkz4jFHi+/Gkyly/vZ4jhgZnDhcIr58QXcvRB97+FVS5CtBibRDTNE2ad1zkPLYoZGPyTrog2hAt3ozqGfnBXdZF/a6Bi3RI9txgL7h2RX73UpeZAaNXV8z5ZPmNVUxPxFH7gUxcyRL85CR+XmZNmHjG0R9PA7K5qGy4RQeSRWjZIEY1Q7mihn/ufcx8vGj7eUQ/AYx/g3vinZipCdghhJXYBtDHR0qYK8lO4Ft5D53qwkvQ2T8cb4uWCxEh7wrCEkRIpf7EtI8AWtlM/IESmIdcDUSdAzS9EdSALEmIkQirhVNBHuBcdGmLrBrbdPzIS3PObecd0trkoUMVmOsQc9343PnLKq2HM4m+GcuQkQvC2XGdH+gkLZXfvRIiRkqP2WsnrO22jtXrBa0TFn9xPyLDkH4LlWerXsNbk3wqNGA6ab7faBkLJFDcKUAOl07HNBcpPd0MzBGt2OBbkbQQ35H3rv5pm2S9HW7sfFSVRVdXktkC1Zzb6uZfVKa1MUacfAj8COEOxCtIv34Z95ROCrRq//eB00UOS1sepMppOy/8xzWB061dJTypPtw0AsOXiJDe8Ya/mqQpedkw==","Y7ZMXq4mLhC5jJIEASOoo7jMVN5XK4U3UpSRCzCuPbbCyVuf8Ucgqm+aXX2Mkr1pe3+joJ6E520SpzMqghUUZ5Zm893RKT0CKa/T9u7PXqJbpkZBpj4zLl6tZaVlXlAnb9Ezo6EPydZDP8SoXyy9TS5zAKjwBNMR2TYz3DHgOvcOV8Xv/dvJJvrwNO8c5yEueiKciOK1ZadY2nAE4Or1bRnko2nxodFaIlzxQezKxKPaVEWn6ydRxO1oqW/o0ApjoooJMqntDoJDaPV0+ov/rENCBQjqjxNTVFiASE/HYDQJIS91Q33xmG2E4S/Ul9FBIE2P/cSsAYBAX0RIMPMUXTuqDYN24PUarkpNmZ+SC925Fw2KwHsbbBSt6XLAvnHxIb+qJr1wFvk7hoWvfu2TvdQ+rYTB0ibdaSzZE8oeFxec/z3BCXSUK2ePqkecukA3+cPdP1dKvk6GHOH2AuF6IXkWALg53NbU8sSmXw3orFjrCyAK25zhmK7P3RWW2OKIjaQK560NgZyJfCNhKYisiI9GWwZxO2HOKfaLhF/Iw7mCMDY3uAqE3OQKWtpFZwxDmhnzzrgcN6LoO+JoQy8bHfIhr4ihtv6CPIoDBJ9XFcMygeFaF8zihGRCoL8k5y3WFYLvofo62yiOpad9qk6Lmb7JhxYe5Tzf1ok9Pw==","KVptyYEQSCGEhdnnbR/ScR/XqoTst+g+YsXaG2Et6vyNi7paYiC/omBszgYL8NsGdC6U0HjY4YbmPU/0tQmbvNGFKFp3XmzTKwyouF05TzKFhCNkJQx/XUXDdhIqjDXEpXTLiv7jlFpeBPXS6/MHgusM3LqaZ6OBEFLtJ1f1/JJgVIFYMoKT8g16qiTQNATt7v3mhrZEhj9s2T6iqbTI8bsui+O4QtBcI/MVK8fNfjQUXP8hHuZyKjKsqq/0EmUqFvjfwWZEKUik8Ju9bNyqoEpvaD6IvDYiM3DX4EhC9c7qDCGVCKHz8WFdm2Z3DtQt47+NQy1QT9ShZFt3X4DQ1J9/35OuvMM46woO3sutRbXEkA8IRpZlYJQwcbLl1AsQlShnBxgkXAsfOmiNAWMKxBIhjVWkHRAPv3vNUADtF6h3UdxEao7qeIZeQPBUI0sQEOlnbyIo7ooKxXQwducKa0Ux+Xc7uK1UCANjkcUngPshB+KYQRFzgFcUxL5+jrYyunoUoP5kouwXnPbxvJATMvhfoHIB5l0/zmB2YWVqLzqpCYMeUtPD2jxch7UGaIt0Oy/G00cnAra+XFjuv6dCWg2iLV46aaCYbzki23ntkO3sureHnGSAZQ/pm6HHaY3Bi09Lsr+3mSaBJg2XknpiSocXMq5F+Pqsvkhd7g==","crL1RyNUqXppramOqA+yHjlE83mWljpS2+1EkSc3hbmH4YLOAVZ7aiolYo6DNz9fAXKpMCsSYUwgJFwok/0VL74EEAH0rSWwICLohkWzgu34eQm3zE0jOgUkDhIX/pDVFfOvMumoCM5HvlJUde8Ctotnrn/BszTsul5QrEJHgCNuGFMQ5Zu5+jWogN3JlKvRxOV4Pxv7aa9m9FmQL/I2lvKSR9/yplFm9AFbyVGSzKdOzjaBDapq71U1OHVcGmglh1VxXq7Z+axfeG6fAVerSChc5cIes4B7RkkmR1SvKNbR23frARmcmQtRG4I11KjaV26W4SXwB5lZR/AHSzf3xsjrKtSsxAAHIuVoYYMM3+8xV3KAKLQKw0Y4IzCzTQb/4rC+Xou6TMMrJ8EY+vQKlcpr7c33Q2EDhMPLMOqlF5FvTFdms5IlKsMG74258XoGRRK0LY0ZehukWw6O0XAnC9akSkDqKitrHz7D5YvdFVAJxsK8DOLLHB6LoDSe+3WH9goCAYzcWamUIw0Di6y9d71XGDApBzPPD5h77zaCFgOS+aTpJxSwYXA9LXBirSMvUIXExctNet/NQ9cuSd6sA8ipGZGu2oiVsHMHUhaF2ugZZR0GYlLs9ab3OKQPJx8ETJvVY+wViW0ov36kQqDFEl1U9WakT3SR+0+tHw==","7rhfzFHjl8SROsZc/5IzQS6Hn5xNfZE/oaokpVdhYDz6Ee3OZoazXITpiOayoGdjawjVqrN6qWdxbhN2u7srQeDlFpTQ0zVAmMRUK6cEcAkscGOilS6EM+jDyt67G6rRCH1Zo8o+vhlyYf7oonL+rg52HdQil/j4s3bFAykTphC/p7lCxkI8O8OYgXRHdP8SlDtvyNTqG4hal+2W+eUTRyDVPnImn0o8T5HBTZ6ItD+cLX3HwvuU9wGmL7QbZl4KDBqXgTyRQUh/R2uAZRveMUfPwCjT4daP1hbCvdlw0Ku2rJnYAu/qWv9oIbaUK6vAhSA6ZlP4DmCmnLJbp7SBM+YHOrA7WUIBu9zH1YdWnrlrqN9YSLBrcxsw/KS5fjM+NXotmkz2Jzka7qjnUnpFcK3g9O3W4lVfXVgTLAc+gm1LPCBToya4ADle8JVwYr+WLTeEBEEbyZiTqlgzi6ii3Yg9BtntFnzkiXjtsQGPB9/gl/wc37NCeR7a23ZlJJAKBEih65DKOJT/RJF9eJqZRujm5/cvYtnkeqANphUC4dw1lUpEHEXOGkyzZEwxoZU+ADbYzRyQTn+n08gXPgGKi+KRrCiEKGIbLNlwvkLKkGNoyNy6vhS89D/rlPWa1SSG+u1/SR+mk7MDlAFOFmUErfeO8MJm3QXWQEQAfQ==","m24L9wRNTfs3xVZfAKf59wrlcOtOWMM/sqRubRQi27hcpfIOfchRzD0BdlI="],["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"]];
                            $result_val['FGStripCount'] = 3;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["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"],["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"],["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"]];
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
                                $roundstr = '565' . substr($roundstr, 3, 9);
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
            foreach($stack['udsOutputWinLine'] as $index => $value){
                if($value['LinePrize'] > 0){
                    $value['LinePrize'] = $value['LinePrize'] / $originalbet * $betline;
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
            /*foreach($result_val['ExtendFeatureByGame'] as $item){
                $newItem = [];
                $newItem['name'] = $item['Name'];
                if(isset($item['Value'])){
                    $newItem['value'] = $item['Value'];
                }
                $proof['extend_feature_by_game'][] = $newItem;
            }*/
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
                $wager['game_id']               = '103';
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

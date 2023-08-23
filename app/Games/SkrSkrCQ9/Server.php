<?php 
namespace VanguardLTE\Games\SkrSkrCQ9
{

    use Dotenv\Loader\Value;

    class Server
    {
        public $demon= 1;
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
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 2}],"msg": null}');
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
                            $result_val['FeedbackURL'] = null;
                            $result_val['UserAccount'] = $user->username;
                            //$result_val['FreeTicketList'] = null;
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = [
                                "g" => "118",
                                "s" => "5.27.1.0",
                                "l" => "2.4.34.3",
                                "si" => "34"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 2;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["1VQLDNdCtTGUJ9i0VTVWO00CdT02OyDOCTDdomzgn+MO9hTGQ5xUlDeh7Fj62AloqZXOPq9aXmctZcNqDFDNjAg9ttkuO8Pb6b1IxOzSsga6J6p2X5uE6lJ5hQGEWy1jYTf3nNO+OMnTaL5Y","Mm0bO6dDBRL4oZ4yBgGHv4lXvXJGvw2s9VJAYXrHW2wmLd5L4B2fZNGxSZ/WfzVmcwAOh7AqLRVUwK7uK5mIhN+ZnRp2RskRn63jkhIPvP22ZRZMmWUS0wBdbnpjrpY5wQ3URg48Y8CIftdn22O6UWP3GN8l7No+9Q7Rt8UTV3LfpYxI9r+prXBJIrz0TgrPX6Hi0N6N1noqVJ44","yx4bv7WI9NaNpcw7pdRUrYTAklDKU4K+AJ4lq1LX1UmI1MafbpDVB0e2CgBZwyzzBxo9U6hAwIkXEQlMlBehNQQj+xOCcK3UFUgfnd/QkT5yJn7upCuE6L7lDQhC0PgFn7RO6/QSKm9LHK9swNNFxjvIhCztx4MRWud7Pfqf6zbhRZwXwwhsMcxaeednN5iwRbAykp0/GraB6LxT","O8jXgbsCH0Hk6AfEAyavxVvzwLS6MzgKYtYGHG39/XPdBg1mQgKpXJD4IBA0cLIMDNZuBFsGO5pq7bs5c7P03yCFT+DynaFRFOg3XetSf1VtCCb9MPGqP/hnqadCFD2VmMQDpzE3+fj08ycC+quwv+anKd54yM5nT9Q/yeSYay434MOlMhn4Sjuxj4Y=","eXugqOe1Nr5ERaCsSDLDgBWwG2wW+6uk/tU0TgbQs4XFmOZU//hI/QnvP1KWn6qlAaXquXQQdOXmGrjApVeGShekt+/0KEAeupx2q/rShm6icftVvqAA5mG0TFXNpYCRGVlRJK14Znzc6RbE2ZhZ9dTXp0LGWgg2bRdTWwOhTg69aiLwLB4RMCPN/NEGvSDRoaBOH9eVLkS3rX6yToHvPg8sBa6hgxuaqSAe1g=="],["KiHSArMD7hlDSUK8uM/aRKoqAkrAHkC0fmnLnhIUcxOQp3rcRZT19G1z1LcEuluGeuPpVN8wIeyTJPghkc51GeqAJbN7J527se9BsX6MdQ+ce/ZfNlGmMA9q82GdnpcHpaVu2QepD6oMD9GEqtMEvMybnncjDMDSU4LwSuZwHPvsudaYWygqNZso13g=","m9zE7oLwyjbZ5hyJXOkaXnEoTvesTWIFyrGuNEZFKoNvxvBfJ7qPSkGAShT2vYZatr4euJivurOc5DvZCkrNZiWejmrZtRU0aYGZKzm9agz+rElil5QHWdGqAOy2hjfO/ueHzp0WhkXCJEwVlJedOxP3Pf8DRj7o7ReNJ37s8+v8ypsS8TM6Ky7zWUI=","iZRhLp8rLqOxqsgUkQ6kwGp+Zx1y9AXuRAr6J9hXTR8ic3blvtAPYRV85EvzJB/KMG8KKmypqwjt++QB9lq8LMvO3DkfxmVw5p5+seyAqxtUcIg9Iaom/XAj9mUb+SZEeYij4xa+gYkmjlAHbvo2sbg9W9+UaAu2SUs19+syxKEDGhhhbQy+C7Jsgsw=","PWNWGc0QKWu2T6JgrZBh1P3G7KKmZaH1Vzjtf4ZFCDsvnHIn71om/7iiTKR5P6tQDav7Bysr4zz19eLnq9+HeYAYVtT+L68r+OZRZCITaL3KE4X2Vcd/bpbXlWwfdcwl6ZCGdIc0mbgef5t+5BvKTvqEEQZEBT35y6SHUnXvGRUIbw9qhRPPrCiKNJQ=","CRbCMXfGfQaBrmm59Su+KQtQDL4niRkSrQBsAqucLBQTda6IhoApfjxIk9GbhjketOgUBerA3nNCEt1dzFOJOGURo5vF77vAn8HEGN+vcMWsZS2amK/I8wjeo9C+OSbA6YTIluFFnlBf+5SD07bt2M46G2w9FNsCK1ZIVk0L6nLdlAMb9oVAatFwVV8="]];
                            $result_val['FGStripCount'] = 2;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["zY3DJ5mum2KoUz7CVt5IMwLbdYSW3kn/u5K5FmdEOIJZRJR3vh3Z67zYykqKC3wFK1M6qdt+x3MXV+xAcPpGcVQqlZWSUpalbJBLW84seoWwUo54NsSIL8QnfakVt5AS60dk1SnhR7m5wxt2qapwHDn462IUfU9xXcdM3zr9vRZxNzZQe8t1W5yhVRIqa6Me8E5rWMcslT8DXfnA","y4IdGaiDFZhCd0wEbi7YZkl582P6iBfhTbe3i03ViyDjWIhAQaBN7BP00vYChR66E/Sz0ssaV88wsrPmhNb88AdS6a571dAc9JFSmtPa+6CQC0YdVpgmiPiUB9haAMUbLXFhRlQv0WGLnTcvGwFoW7+WNd/3GlvJgiCJBD4Gj2CYEuuz5ZALFLUxEBc/cIn+Fp6Ng17OMtX6g+o4","SFipAZROa7tnla4HffD4zt0YBiZuw0WoMA/80VHHAFBvi9AQWvkxDePqS3yPRbi5CfqzWxGh5dSEhNMEKWUxJFD0l165asNg+KK/zyOtQLDPppARBHlEgx1uM8wdiao5nk50dzZrrw6ey825XiNhsu6Hqfeup+sBrf2Ne3TU4/N8I5ajNUrfhHLBl+fSefNwc3AKDH6pxF1jyEaW","P13vcvvxCyVko8tjk9FSJjixqjn5XULtQYgnezg0PxY32GkVXkWkRAWZbTbZfcl5huwrS/7hqk5xuebRMNe/Kss0LAmqrPvQ8SE9TwENgQ7zHWQ2+R7y3mwveJc+n608d3M94FKCkzfWqobDD4JTj2KOmPnplpAlLbO1QKQ/FtbBjLbGQu6xzLW1f7/4kXO8KkAEjmM+N9raFDAV","nlMTQ8xZe50pHTGRVpCbeXb2IT4Ne3QnYZmlO+qeAA206ffh8KjXJVfRKGk8Dk6Jcdd13I1ua/kwPIjumR7LNe5x4gCHcrhxaZPwPbpclzw5NDWYLpqCZWuZoq1h4YeTo3Y7zNnu0oxVQUZDjl+lEH948bkGB1mo30haw9lTXM6b1mHgl/6ECux0oEp3hFasC+wCqArMysc/LPKT"],["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"]];
                        }else if($packet_id == 31 || $packet_id == 42){
                            $lines = 30;
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
                                    if(isset($gameData->MiniBet)){
                                        $slotSettings->SetBalance(-1 * ($betline * $lines), $slotEvent['slotEvent']);
                                    }
                                    
                                }
                                
                                $_sum = ($betline * $lines) / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '652' . substr($roundstr, 3, 9);
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
                                $result_val['Multiple'] = 1;
                                //$result_val['ExtendFeatureByGame'] = $stack['ExtendFeatureByGame'];
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
                    
                    $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                    $lines = 30;
                        
                    if($slotSettings->GetGameData($slotSettings->slotId . 'PackID') == 44 || $slotSettings->GetGameData($slotSettings->slotId . 'PackID') == 45){
                        $pur_level = 0;
                        $tumbAndFreeStacks= $slotSettings->GetReelStrips('freespin', ($betline /  $this->demon) * $lines, 0);
                        if($tumbAndFreeStacks == null){
                            $response = 'unlogged';
                            exit( $response );
                        }
                        
                        $stack = $tumbAndFreeStacks[0];
                        $freespinNum = 1;
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
                    if(isset($stack['CurrentSpinTimes'])) {
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
                $wager['game_id']               = 118;
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

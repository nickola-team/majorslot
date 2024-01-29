<?php 
namespace VanguardLTE\Games\CrazyNuoZhaCQ9
{

    use Dotenv\Loader\Value;

    class Server
    {
        public $demon = 1;
        public $winLines = [];
        public $selectIndex=0;

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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TriggerFree', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeSelectIndex', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'PlayerSelectIndex', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeAction', 0);
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
                                "g" => "35",
                                "s" => "5.27.1.0",
                                "l" => "2.3.8.4",
                                "si" => "41"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["SS9nqizdlacIQgaCdyZA5p14baCBFnOqMEaDmMoR66IgCteXw8ymY7shBuTwZi4tudfJws03vxTXqR+e09RcQEavECuvP8xAJIauH+qq7NDk65hkwAFvNcEfcmT5M6E/U32FiuzanULUxgrKTLeAUUf2O2fxZMy5Aou/ctZrOH8FK0egauR/towd2/C9oQvs+a/1McosOyuxlLUKsO2ZYC7c0Xouxdc/VDnFA4DVfSC4FCDgkeYUd3hvl61J340Fklh4ToDFJ1p8xcVrvK6PQ3jw829tVRvj6qUZ4bq2VaJ5AbZbRPt0lyKb4ATj5y5weVHZzXlOo0v6fyYGhtWE0WVUMAg80fpcQiV2UA==","PydqwS8RkwQf8D9c0XwlU9KMYne5D6HMQk44c0tbcJ4Ea4nvr5MS+UZUyOpNTuqB0X4clLWY0rsEK8Mye+38CneugTOu/xTSOjiUtZZzxFFjPEOBjL9m/fqy7WsNZA/qv2hLrEd0vI6SMP5v1iRN594TfZLAJNNVIyXOO8PSlkolK5KliZVk5STsHv3QDEewddIORdUNAWA6le2drEIMfnWqlzMzfxyI9RJDeOFcDL1c1miReOj4VxyrWyKxw/kTr1kMqUX/hFyIEW2m6tnaGxoZWczq13xDa8lPRm3qdtCtpKUHbFhish6mfxcHuQmQrAAgc+WV/dHqeXok3QNYxxVNhZ5wc4uo0mm9ddkVDwbj7mkdBqULB9HnLMXX7NqOqiTgJHUpOzGrj4R3TTgn5lG5Y3cWf2jk6KCuYw==","0wv1NXt1RUzRPNfAixNnO11nZl3wl+FqevaoozDTdQ/5Fc896f3KJO1Fe1DOCYbGUesvdVC15QCpOjLLIP6asJKwm8Wijua6lshyeJPLSI19NE5Ax2rEHcAp/LhC0YPddNpJZLKE6SHGOzHP2aY363hyP0KNvdNEcuZZvXQSg0w4iQQ3FXhCOWm7QJL85UB2UW78I3SOiuM8XVAcXVFN0Crmo7Hi6PoYpSUuBM/l/1aZzsNtKS1uP6Ht2tli2kHp1k7tlX8TEqntbrXnWDM1nMRakBsrd3rR7eLQpLhKloJ2Jx+dYs2SLUt+2U0HUThTTK9egAHDBgnD+WrmnPXq8HlZxbp4Kmb/g3wt6Q==","FDSvaadTz0aUwSxbLHFZmCDit4w6yPYYMZtblpzvgupnS5uWHZAK8ZYf/R/MnLzTqQd32BTuDfDlcqo1JHDRL8v8iXp4m8JgHbX9T/+5iznyPNmzYJrTMBb9tnqZ4FSwEsBlqhRvoE7r71ioVMADyMmErkUwQuqVXW9VJPicg07MeYc08x3BvwHoaha5Uz5SXX9UtfNmB+JhZFvXnT29lGk8k2vHU2Qf8ckt/BiiCr9UggspKN037S2VxbioioTAEF+G5JAH064a4ItY","AmXsRk1TJgYxlcNmhmEh98lXnIyBfpW/lVLr/bU2GXvv3WVPcl64F50+FnsxbwhxI6iiBYL77HyG5btvzHWE89ki2msC9Y+gSF2v1urrkrVhzsqUSiIxoYkbVCSV1sbmlWe86DH+iu+3adNjAfaopurBwDo7IUlkynUDNi9UDzY+r9qfqkirzWS30oQMfhqVMtHpEsKHjhriLM8q"]];
                            $result_val['FGStripCount'] = 1;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["7KRGo06mF89VeQUfgKovk7J1eE8FKR4fthybZ6AOHJ8MTGs4tzHgHHTzDAQGt2owubqWXgsZY4f5Vtx6PMWdeLnSMm16xkAuIUl5TdSl2oVZeqJNzLjNqMRB/UQE1XnG1wu/KF6Mbn4i/riWfYJAD9tud+ts/0aM13Z519kNwIih7LvGzSdJed+dDGNE+Ga3hv6nIyIHbB/Q+C0hPzNR/2t/dZtT70QvTpc27hovLpN6rDwofyNDmfXSoysNQu/hOFjKGsbB4+tOzyU38KZMvygdkhwZjn12sQdU4w8KaGCTGvu0zExdKluzljU=","anUGYh63NT9UQ33j5YX+10Ls6arWuDRoTxGSb0n8/yBYnTtvj4OFPn8QydvlP3RYAusCimMbVVXVzTi/px5r7zDG5NrDLBhrhY9equIWhMOUKOKsIaw/5xm3wJej1DNi5y7HeffcbSNtfODR8LSd3XchKFGJMT0Dg6JXXW+A8d4kwrj1rxZwvuC//EI12nqSHdLL5p9SUB2e1b+btTmnzuhRFkAvo+7XxbVPfoYaoDXCDxSpb3nKT1COSZeSeiEetCtjzPkxV5Ajt7Rik6ljwBuKTw1nA+mj3xDGtDglRaz5SNF5E+bX/pqhpow=","mwZoFKeKGbbhx6NI9PX3hqsI/ii51VJpTsxfgdKsmxks8Wp4p3USIhRUEsR9XnB/rKZfzMi9pR8FgDI6shSz07Q3oXnJkN+/GJhOznvbBn2EDZ1WpGpoWcJWNhiu5CBqfsIZWvsGwNWGA1UJAHBXhRt82ULsZyCP389pD6ZOWl358zvgrO9IYPRBC4DsjCux9XW8KkN6gs6s3pG6Zw9nrMOnDtLMCIJWtn7ZcqvxAI4wLQ0mMOHtWYWCwjdFaKyvnCWZYYntgJ+tdVDwtjEW3rToV2eYNZSdhVxiL3R3g20YS6e4DpscmFpNmq4=","9Q4OzGNcyKzP1HcbWJqflhxsmlJiMSmqETC/2cwNxe7vzfI/sedQeaJnZJRCAI6Xy7NEadOxZECInhDHVrd/wcbt+bTZaKQJ5XAcXR15ckxNp1bMdxDlQcEo11C87uEGsYtoshVZl7ePSbwNJXPXLYdVhuZzSEDMAlZMEpU0BPh8znS4UMcnZhQPGB3d0fegbdQHeD6EqOX6GNNB","5TVs3T5GO4CEOcPZ9Tr8qwOzauCphj7Kk8UlTMI8/Omg4T/n9sHmSsqwVBTmTCBZ0UIBzbwX7K4rZPo81BhbDxMPPYFdQrGRSwc32bnRqFjdk2z6Bf5rzySK4guBAJx1k9kXxkwI3uT73pBFhs2zGkWdlU+SQz3fSxKv8jCyb7Wi0Yb20l3g/BAzMbzgI6+zYeHoOtGMyzZn7RyD"]];
                        }else if($packet_id == 31 || $packet_id == 42 || $packet_id == 33){
                            $lines = 60;
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
                                $slotEvent['slotEvent'] = 'bet';
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks  릴배치표 저장
                                if($packet_id == 31){
                                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex',rand(0,2));
                                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeAction', 0);
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
                                $result_val['Multiple'] = 1;
                                $result_val['GameExtraData'] = "";
                            }else{
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex', -1);
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeSelectIndex', -1);
                                $slotSettings->SetGameData($slotSettings->slotId . 'PlayerSelectIndex', -1);
                            }
                        }else if($packet_id == 44){
                            $slotEvent['slotEvent'] = 'bonus';
                            $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            $lines = 60;
                            $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet,$packet_id);
                            $result_val['EmulatorType'] = $emulatorType;

                            $slotSettings->SaveGameData();
                        }else if($packet_id == 45){
                            $slotEvent['slotEvent'] = 'bonus';
                            $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            $lines = 60;
                            $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet,$packet_id);
                            $result_val['EmulatorType'] = $emulatorType;

                            $slotSettings->SaveGameData();
                        }else if($packet_id == 46){
                            $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                            // $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            $result_val['PlayerBet'] = $betline;
                            $result_val['TotalWinAmt'] = $stack['TotalWinAmt'] / $originalbet * $betline;
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $result_val['TotalWinAmt']);
                            $result_val['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                            $result_val['NextModule'] = 0;
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
                        $lines = 60;
                    if($slotSettings->GetGameData($slotSettings->slotId . 'PackID') == 44 || $slotSettings->GetGameData($slotSettings->slotId . 'PackID') == 45){
                        $tumbAndFreeStacks= $slotSettings->GetReelStrips('bonus', ($betline /  $this->demon) * $lines, $slotSettings->GetGameData($slotSettings->slotId . 'FreeIndex'));
                        if($tumbAndFreeStacks == null){
                            $response = 'unlogged';
                            exit( $response );
                        }
                        $stack = $tumbAndFreeStacks[0];
                        $freespinNum =30;
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespinNum);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $tumbAndFreeStacks);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 2);
                        if(isset($stack['TotalWinAmt'])){
                            $stack['TotalWinAmt'] = $stack['TotalWinAmt'] / $originalbet * $betline;
                        }
                        if(isset($stack['ScatterPayFromBaseGame'])){
                            $stack['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                        }
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'PackID') == 31 && $slotSettings->GetGameData($slotSettings->slotId . 'TriggerFree')>0){
                        //$slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 10);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex',rand(0,2));
                        $tumbAndFreeStacks= $slotSettings->GetReelStrips('bet', ($betline /  $this->demon) * $lines, $slotSettings->GetGameData($slotSettings->slotId . 'FreeIndex'));
                        $stack = $tumbAndFreeStacks[0];
                        //$freespinNum = $stack['udcDataSet']['SelSpinTimes'][0];
                        $freespinNum = 12;
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespinNum);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $tumbAndFreeStacks);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 3);
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                        // FreeSpin Balance add
                        
                        $count = 0;
                        while($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                            $result_val = [];
                            $result_val['Type'] = 3;
                            if($slotSettings->GetGameData($slotSettings->slotId . 'FreeIndex')>0){
                                $slotEvent['slotEvent'] = 'bonus';
                                $result_val['ID'] = 145;
                                $result_val['PlayerSelectState'] = 3;
                                $result_val['PlayerSelectIndex'] = 3;
                            }else{
                                $slotEvent['slotEvent'] = 'freespin';
                                $result_val['ID'] = 142;
                                if($slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') == 1){
                                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                                }
                            }
                            
                            $result_val['Version'] = 0;
                            $result_val['ErrorCode'] = 0;
                            $result_val['EmulatorType'] = 0;
                            
                            $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet,30);
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
                            $this->generateResult($slotSettings, $result_val, 'respin', $betline, $lines, $originalbet,30);
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
            //  $winType = 'bonus';
            //  $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex', 0);   
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);
            

            if($slotEvent == 'freespin' || $slotEvent == 'respin' || $slotEvent == 'bonus'){
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
            if($packetID == 30){
                $isState = false;
            } 
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

            $nextModule = 1;
            if($slotEvent == 'bonus'){
                if(isset($stack['NextModule'])){
                    $nextModule = $stack['NextModule'];   
                }
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
            if(isset($stack['IsTriggerFG']) && $stack['IsTriggerFG'] == true){
                $freespinNum = 15;
            }
            if(isset($stack['FreeSpin']) && count($stack['FreeSpin']) > 0){
                $freespinNum = $stack['FreeSpin'][0];
            }
            if(isset($stack['NextModule']) && $stack['NextModule'] > 20){
                $freespinNum = 12;
            }
            
            if($packetID == 45){
                $stack['PlayerSelected'][$this->selectIndex] = $slotSettings->GetGameData($slotSettings->slotId . 'PlayerSelectIndex');
            }

            $newRespin = false;
            if($slotEvent != 'freespin' && $slotEvent != 'bonus'){
                if(isset($stack['IsRespin']) && $stack['IsRespin'] == true){
                    if(isset($stack['IsTriggerFG']) && $stack['IsTriggerFG'] == false){
                        if($packetID != 30){
                            $newRespin = true;
                            $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 1);
                        }
                        
                        
                    }else{
                        $newRespin = false;
                        $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeAction', 1);
                    }
                }else{
                    $newRespin = false;
                    $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 0);
                }
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

            if(isset($stack['Multiple'])){
                $result_val['Multiple'] = $stack['Multiple'];
            }
            

            if($freespinNum > 0)
            {
                $isTriggerFG = true;
                if($slotEvent != 'freespin' || $slotEvent != 'bonus'){                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespinNum);
                }else{
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $freespinNum);
                }
                $isState = false;
            }
            if($slotEvent == 'freespin' || $slotEvent == 'bonus'){                
                $isState = false;
                if($slotEvent == 'freespin'){                    
                    if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes && ($stack['RetriggerAddRound'] == 0 && $stack['RetriggerAddSpins'] == 0)){
                        // if($stack['IsRespin'] == false){
                        //     $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                        //     $isState = true;
                        // }
                        if($slotSettings->GetGameData($slotSettings->slotId . 'FreeAction') == 0){
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                            $isState = true;
                        }else if($slotSettings->GetGameData($slotSettings->slotId . 'FreeAction') == 1){
                            $slotSettings->SetGameData($slotSettings->slotId . 'Respin', 1);
                            
                        }
                        if($packetID == 30){
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                            $isState = true;
                        }   
                        
                    }
                }else if($slotEvent == 'bonus'){
                    if(isset($stack['GameComplete']) && $stack['GameComplete'] == true){
                        if($slotSettings->GetGameData($slotSettings->slotId . 'Respin') == 0){
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                            $isState = true;
                        }  
                        if($packetID == 30){
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                            if(isset($stack['AccumlateWinAmt'])){
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $stack['AccumlateWinAmt']);
                            }
                            $isState = true;
                        }                      
                    }
                }
            }else if($slotEvent == 'respin' && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeIndex') == 0){
                    $slotEvent = 'freespin';
                }
                //$slotEvent = 'freespin';
                $isState = true;
            }else if($newRespin == true){
                $isState = false;
            }

            if($packetID == 44){

            }else{
                $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
                if($isState == true){
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeIndex') > 0){
                        $slotEvent = 'bonus';
                    }
                    $slotSettings->SaveLogReport(json_encode($gamelog), $betline * $lines, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
                }
            }
            
            
            

            // if(($slotEvent != 'freespin' || $slotEvent != 'bonus') && $freespinNum > 0){
            //     $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
            // }
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
            
            if($slotEvent == 'freespin' || $slotEvent == 'respin' || $slotEvent == 'bonus'){
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
                $pick_log = [];
                
                if($slotEvent == 'freespin' || $slotEvent == 'bonus'){
                    if($slotEvent == 'bonus'){   
                        $sub_log['pick_no']              = count($log['detail']['wager']['sub']) + 1;                     
                        $pick_log['game_type']           = 888;
                        if(isset($result_val['udcDataSet'])){
                            $proof['extra_options']           = $result_val['udcDataSet']['SelExtraData'];
                            
                            $proof['multiple_options']           = $result_val['udcDataSet']['SelMultiplier'];
                            $proof['player_selected']           = $result_val['udcDataSet']['PlayerSelected'];
                            $proof['spin_times_options']           = $result_val['udcDataSet']['SelSpinTimes'];
                            $proof['win_options']           = $result_val['udcDataSet']['SelWin'];
                        }
                        $proof['fg_rounds']           = 0;
                        $proof['fg_times']           = 0;
                        $pick_log['proof']               = $proof;
                        array_push($log['detail']['wager']['pick'], $pick_log);
                    }else if($slotEvent == 'freespin'){
                        $sub_log['game_type']           = 50;
                        if(isset($result_val['RngData'])){
                            $sub_log['rng']                 = $result_val['RngData'];
                        }
                        
                        if(isset($result_val['Multiple'])){
                            $sub_log['multiple']            = $result_val['Multiple'];
                        }
                        
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
                    }
                }else{
                    $sub_log['game_type']           = 30;
                    if(isset($result_val['RngData'])){
                        $sub_log['rng']                 = $result_val['RngData'];
                    }
                    
                    if(isset($result_val['Multiple'])){
                        $sub_log['multiple']            = $result_val['Multiple'];
                    }
                    
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
                }
                
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
                $wager['game_id']               = 35;
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
                if(isset($result_val['Multiple'])){
                    $wager['multiple']              = $result_val['Multiple'];
                }
                
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

<?php 
namespace VanguardLTE\Games\InvincibleElephantCQ9
{
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
                                array_push($betButtons, $slotSettings->Bet[$k]* $this->demon + 0);
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
                            $result_val['FeedbackURL'] = '/feedback/?token=' . auth()->user()->api_token;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['DenomMultiple'] = $initDenom * $this->demon;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = [
                                "g" => "124",
                                "s" => "5.27.1.0",
                                "l" => "2.4.37.4",
                                "si" => "34"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["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"]];
                            $result_val['FGStripCount'] = 1;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["UzJfMpSceHceRuBYCgDirnj+6Uod4xemBxEqixG4/BL6mfa02VtyKePR+uzUEzQ9h4ecGhRQpkTQGzBY/llmudp4nKSSH6yVGZZLLimZJ1MMAjHavcSSFhhmRre1+8lwn1vzhLvkFGMbqUKjKBvAFfblcLWiLQJeWlBIncFP+jQi3EGN+qx/R8sUEw4=","QICsAGw0Fr8OCq3FtcTMYNZmfsLtbiUXV2sztsvugFhhaY0ZcV7lgVFbcE0SnxX2Dl8OCGLMHRcqoPnOuVDsu4E5fWXgwZIzCPPKtliaBnANdhjPz7IzIBIKwlZf8YfBqJT5tG56O7cYnEsWKp3LDFKRukQrjrEhTr/1rxhpiV2aHJFdgoVp38YHRlQ=","qXQMIF69fzOgefIqOu5VZ+vI1sfFAKCWn8FvySbTZOqP629mnbMJCBPK1nKub6Sim9vPVo2t71XsOWSm4Rd0nPSwufIE3sHSD67409wTpD5kmzqlbcGAI01i6jlBFxWxPojZ2Bean7x9NvWqSRedTRrcQRAwN0fVX3I3VKcwDIVccnr7JWu/KpWDtbo=","dkpXeURPcLdQyDnnN0M8QK4tQFoCHfqj2z1jsmM0V9rNvBFyYWk8YXQjVpsj1uoTsTrpgIo9jVVtnsAg6raxhxu8gYBtLID3rqFLvn5b9H9246go9d8mWcs7HqJu4y8pCQtlaz+ODwoBqF2SoI3JnZ0L7GvmU4Z9Af1q3fdL+7BdUNpXrjcaC7/joS4=","LbYFe3YepNmjAj48LTiVWcE5pkd+LKCSxF8PnrEE3ykjHMTTttBQPF9QZTUIWMPrRlmLMWR2pqVN8YAQ4UwNidJgRmd25YZtYnMD5jvhLggm5swXv8W1rB7j6xAJAtw6N8XDVu2U9EEkw4Kn0rK/P1hZu8VwzEk/Q20t8iOGShC8xNODD3fHnB2HkIc=","dV425A7aFTPVHF6wGnssar+RaCyAGDAdQiBHM9UkqU2oyw3zpoqYOEukQtvH9zBA8yXd2OlxzuIqRlt2m/61xDJxLExHT78QHhBpsn8ZYG6gM7ktdFzSqZ/OvM5liZaqRxg9R884qu/rEy19AbKIKXOZdx/1BdlR/05B+uf/i2O5rsL3fx/WXCVJc18=","tQtBRxMVyfclBBhx9MSonuMulhYUrk/7PNLM5bNYiJR/7irhpiC+CHTUJmq+bj+q8bacP/JdHnXo5LGtPOlp+AxUNNhwSD4YGFi4ujQyA2pTOMpXK9+JHWM+ne9uPTW1QLasqUByB8rqnnCVjmCzfaqXMrLYJXAC4nUuXMPuVU7B0NdHpsWPYzfbpdg=","OG0d99wn7BHHw9g8uaDLJUiOpSicQFE7FLiGZQqG91nNGhhvkZy1+SOyRzuhrQ26iKyH6Spu7v9iUWWoxgP7qqI1+ltrcGwID1L4YK/bmsc73qTy5YG/cdJZmG+XYxiQC4kHj+vTLjOOmnO+NMe2DxFLVVFelUITzecINvWxT05SFBDIRkLIDTXVp94=","fAa69LPvGtm5J7pHMHk+asr1nSUE5usBLVH/TRh0K2IWasYfDamlC2pBgfmN4urxPEzIBgBwudgAl3Y5ysqHxBWIOb5UfxJTTkEBNrXtXPZvZ4D3i+XwXQP27wBFBSt9DwmiuvnZjt6gWm5F1UKn1T7b4g3rkUKfqW89EaOG9zRV0CNKh7UO8DhpnPk=","o54Qyew08hD1m5HSftFM25Sv6kZqhIyiljituHpmmDKkuc24VqCD3EX/Cxev49RGsPYqGhL+SiP/hmtfhY6drHqEtbWgUeQKvWy2w6Ifsc68AIrlrIh0H3VAYaDzptu8n+cuMOkBvfBVzLBKo01+vgPwRGql75Mc139rSjhGl57d0kJuEiqsrVGJ4HE=","lORT89wLC1KE1J383ua/1Zs/axd+4g0MxrHIBFgyP7KTPZI+KHJXyLLLNgkUhwj+H05wdP5FdK1wnoP7k7pvMLk3lLC4tCaTCAIjqr0GjpP3ivJEigO0EItx8lqFMUSMpAGfxz/cm/n6tXnodNVfHBuKeV6fffW5Q+p1JSknBb1XJ599qAJO1FCo/mA=","OjKedxhKVtWljkWSe+1l+5bppQ4ELXqJfkUiHsM3qSurS5Dl9ehuXWeFiYVzjjP9FidBmAyqYdFt0WM28d0wfgFYhaeUczJjMuS6vN708uXRECBy0Z4SPHwKe+JmiQfwneUiBClZzhWJfe194bF1Wd/TeJpUF8qlZWwC2L25Mce9jj8aA8JSU7orIAo=","6bgcorko6lQeiUHuslKXSpfR2Qf74noI6rmvNQjLJAE0R+ZSBpAO3ejku3QklaaPt9vlYAf3HlzTZ00VDbvLpGeF1pdDXNR4vK3V3eD2n58676lsEPjiuwYAeuSgueBKCOx67VQMQHJvCTiTMtLi7+GJWT/tFaGk+S+tXerHgAXts6+PB/kOMlKc/7g=","ebcPiN8FNiezaJHwd6KLRjOadDUTEgKt+R7NXhZyEkap82cLUojVFXbAs0+IEbLciAV9kE45+UNFEMx82Ucn5PQmvJ3wzXJ3B1WMmlXat2drZl9f94EgaGyIli+P8xo22zozMF2lblAzVTO4gp/mGejhhdncNzGQP3vybYmf0uBnOL3yk/nefezXOXs=","7FinvwX1HxQ7rPcBVzGCfz+rYJhKoxdxv/ULsnoIRrSqrbik89mlDaoJBn5WKVkFth1zNkmKGbihJA4hOu7ooTtDmpAGs/WhRY6xQXHbsAEd3EsEQ5SE3qrypPQD9PDZtfaioal7JLlgfynt62MuIE7Qcv/ETBp504R/NPbhan/likelS0ZOWTVQ2Es=","GyuzXi6GP5WPrEgHSZFDL3d4OTUHIUBjVCx876wunotqATWl6Vg171MC4VIBVMwj8NbFb5ezbpiIO8u0RHNX6dBpH/m7OC9CDdk+I+3cJjjGOO8abX7U1LZF0i0mi+g8/BS3GLV2yYo0gvPcZVf0AgCrjG6G+f2hgX7PnqhvUw1lkEpsL3YJaetuog0=","6ETaWaVvodAZzLWK4bmBNpp0vW6WX68QKzykRqGbODeAUhSHigX9TC11npWR+VP4QiLE+clCm8vD9Eieye+Z9UTBBnTM0vsTsPmvmP0noXN4XAOxwjITTBasPXbyMBCsp+WjamwtALAj0TopqxjY7bJTx5EUROGzy0lyb7LQJmc101/5orIJWTBRqXo=","83oPszSIP7O2QzN2MVw54nBIV+/An03Q6xt8sevUClfpmP1gaOFfTdCJuMtBJkMbTPBlxewwFlOlkMHN+/NCLxOHzfbcD79gf3/X6en9YZTHLjSlXH8yOkPTeCIsAzan+gxfTrfLZDLDcov4N+KDzpMrpBMzUarhMc14bmAp2f1Zpk3Uh7pipIxNp+U=","wA48qw3Vn15qyuAokoLkcSXpLjg53NzTRyUIc/IYbg8CyZsoWlAnUCdffw8y2o580vOu9M1uFdUG16YpQHU8qc/kkWTaPbdwOUuUFLcwtffUnJ6Pqecnx+4Uee58W4JayQnXXJLe/MBZbOioWwVtffWWvfCp1PzIE/JSZlqqAj9JOOq41vBl7X82o3o=","jHzAXZXTBjQesgq6asA4zdbKxHc+aRU40CGLGgdqa3vqAGrFutfRsz9QJhITJghfay4U2G1bJObqaG0Ifao6WMCrv2Ti0/5QcY5zkBRv3TCpPGXvBO7KkYoqFPsmw+kVbovZUJWY6lLf0uqyHU+1GdgXrg37vKH/fpTinwX6SwbGHVEzgbBK8Oe5rOU="]];
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
                                $roundstr = '660' . substr($roundstr, 3, 9);
                                $slotSettings->SetGameData($slotSettings->slotId . 'GamePlaySerialNumber', $roundstr);
                            }

                            $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline* $this->demon, $lines, $originalbet);
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
                                $result_val['Multiple'] = $stack['Multiple'];
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
                        $lines = 50;
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
                            $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline* $this->demon, $lines, $originalbet);
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
                $stack['BaseWin'] = ($stack['BaseWin'] / $originalbet * $betline) / $this->demon;
            }
            $totalWin = 0;
            if(isset($stack['TotalWin']) && $stack['TotalWin'] > 0){
                $stack['TotalWin'] = ($stack['TotalWin'] / $originalbet * $betline) / $this->demon;
                $totalWin = $stack['TotalWin'] / $this->demon;
            }
            if(isset($stack['AccumlateWinAmt']) && $stack['AccumlateWinAmt'] > 0){
                $stack['AccumlateWinAmt'] = ($stack['AccumlateWinAmt'] / $originalbet * $betline) / $this->demon;
            }
            if(isset($stack['AccumlateJPAmt']) && $stack['AccumlateJPAmt'] > 0){
                $stack['AccumlateJPAmt'] = ($stack['AccumlateJPAmt'] / $originalbet * $betline) / $this->demon;
            }
            if(isset($stack['ScatterPayFromBaseGame']) && $stack['ScatterPayFromBaseGame'] > 0){
                $stack['ScatterPayFromBaseGame'] = ($stack['ScatterPayFromBaseGame'] / $originalbet * $betline) / $this->demon;
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
                        $value['LinePrize'] = ($value['LinePrize'] / $originalbet * $betline) / $this->demon;
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

            //$result_val['Multiple'] = "1";
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
                //$result_val['Multiple'] = "'". $currentSpinTimes . "'";
                //$result_val['Multiple'] = "3";
                $result_val['Multiple'] = $stack['Multiple'];
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes && ($stack['RetriggerAddRound'] == 0 && $stack['RetriggerAddSpins'] == 0)){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $isState = true;
                }
            }
            

            $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
            if($isState == true){
                $slotSettings->SaveLogReport(json_encode($gamelog), ($betline / $this->demon) * $lines, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
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
                $bet_action['amount']           = ($betline / $this->demon) * $lines;
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
                $wager['game_id']               = 124;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = ($betline / $this->demon) * $lines;
                $wager['play_denom']            = 100;
                $wager['bet_multiple']          = ($betline / $this->demon);
                $wager['rng']                   = $result_val['RngData'];
                $wager['multiple']              = $result_val['Multiple'];
                $wager['base_game_win']         = $result_val['TotalWin'] / $this->demon;
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

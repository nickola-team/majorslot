<?php 
namespace VanguardLTE\Games\FaCaiFuWaCQ9
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
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 6}],"msg": null}');
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
                            $result_val['ExtendFeatureByGame'] = [["name"=>"WildReel2","value"=>0],["name"=>"WildReel3","value"=>0],["name"=>"WildReel4","value"=>0],["name"=>"ExtraSpinTimes","value"=>0]];
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
                                "g" => "143",
                                "s" => "5.27.1.0",
                                "l" => "2.4.32.1",
                                "si" => "40"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["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"]];
                            $result_val['FGStripCount'] = 3;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["9TmBTyIdnBDrqyFNaINDPQyC8tGhkVNuYDn+u80C5/kmQFmMxuMRPsBnAD2HyX+/PflwzKCcs4/yl0ex68qW6zHzKJN10+Aso83T/QRtR56HVn9soPEK6jOk8gbyfTZ2WxWt8ikkE260fFdnCrfvuDiaXgLkbYNcGi7yrPmeWazHXj6cBgF4K5RD2A7bpbCwoB/yS7ClfB3gtQbiVPA9o/rWndFXVDYVza9vQfZZWu6kSKmGBSy4kfCdRGiRBplXP54V+W1rU0thS8yTy+EP51NpZNXDHDE9mV83588SQAmgSk3aVEuQ3IKWXMo9aC4/dVk4OtoyqzAPQvXFEtdLHujCN9BbP4yrrV+SkRWaCtkZMalAbxkKpKqVjVhJfsDxF037/R/J+bUcxSPdXyv++Fo5p++aCLAV243zzi6pH8rzbhSGgRshXPkGFVjpbZs/Pin+KKWbMfTjUoA1JXZOBh5OGkcMUMAQIEdjMEM8FdDTsnnbZIobmyape6M+DoKhdakp0NW+QGvfq5DUpD9b6skXYPyUChVQmOdscg==","tHEjgOSrc1NsEUDkn6/XwUAQQCEXpGhDEE+gXw==","Su2baZOPMZODfJ1OFOz08k4+7SVPGy0QmfzpaxFpmJyzkO8TN35ST3gmSVVFLDqGFS2Kx61sk7Fw7OwToF1WqsAMp05H7G7IXo7XxPEI/K1XEhmEniG0Nu1RNAilDAhOo4RtzgyL65Hvf8x8Qf863frnBPYV7sGPhfwejGDxLfLTfeCUcC4TRBtT+kBGJtn4qjd9T9QEvH897qQD3C3GyD8bZbHcZYY1kEoH8TvRGkiq21KX03LCDwWJOnuXf4c6IXDJJhmtLE4qvzder3vXLK8XukXbKkqr4qsavaUuaxLBAgdCPyMGj5VMMlV6UzIP9Pjj93Rjn4c3yrZi7qd9YEYoAcL8R8+rXI5y4bcqyrz0/ssaxyTjoIDCqnUDuXr+EHuDVUcQDwz5HeRcgU2GRQCUVMH/84zLiO29MCm7wNur8bPXFL4tp0fxcy2Y9yepeddsL0lAiDaL9np7veqUHN+lEY7Jpxzqq6V/l1A5PktJKNmgQe5Ke09a/rWJesdmCuo9ca7O33mJ/fotXCOKNnbshfj3JwHFoFyrCCE5XFIW8kSWT+MFeC6WIyQ+JhqJU6AMWm5P2tuJ58Ml0GuJ78ulUVg/fhTz/OC6cTM0jj4aU/OG7THP0b0sd25WQwB6yrxd/1zpif7Poc1x","Km9U4JtPTS4FyVAHgtFGAJrKHigqLxzZSYBcxVPZgOBpcpZBW0sn+Cnk17gymv80lJ7FdoSaFHOdrdX8cpbYdPkFR6L8DEBlhQko4hfZu71GlHDXN6LIR8OkdR8EFsyXfhtMVb8MLMKrZtOkEkrQ3MRbGWbT7KLjwcrlXfY3TMgs5enWOiSwNRKaCUkkOB012RpzN8htvaq0kDg2HbCfSV325sACtnD98QX2Tqqp8WZmNCANAFv9yb9qSgcuOOzIGC/AAkjAxo1rWj/ZVG0cUYaeLshzzSESm9o8OiiwnAYcDKo/NEZajNN+3Xu83SqR/idkymhpT4ku/q0p29pphErJbCdiatwZjIqLTT2gKlz8MTxQRm+MzohfZmfW9q61sGKBsmL0aUSHGTUKSQFVnHLTavD4Rew7nbqEYkDIskgaSwDR/pcm1ZALTTDH1/EK/fLIKZ62pgIbq0PrixIfROMjoQp+BG4veuh6pQ1Xd4gcLrJr/Ca6Dilojq4=","9RQzbxZ7vfQEFRlUedNcxKzs7NTqj7jw/rK3I3WfXVHH8Mfe2hQ3KXnvolmIPEIgOy1XhXoXuS/jZdoBZIGxrTGqpbR9WHdbTb25y5wsBggpSGSV0AGcTr5OVPU+L2k+ItVezWy/+fQR0Y5TenTgUAw2fPrygkvLGksOOfkPj00JgThjm16Q+Z+alZIJ32Thnfz/UHaC1BUE2Pujr8JAbDUs0vfBmnt5IR96jLZkvHhJk9IlsSIt0nHLePUlII+hY/zCXMT7or0NnDQbmRjqcZgUed+P11ZpxgZTlUWzngClHqBw3Nx16Mmr/SN7mux5ItRXaEcZgtnK3XLGxq6pLapjl+9RBEGvD2OL/pZs325p8O1KnyBPiKlE2SYvm+ahTIIWFuiH9+ZhG+VBQQ1Izt3nhOKw1wjUwJ6uQHwV8XvmjA/ZP3+prKDi9VgtsZ/hGO6hJAy1gPPd2MXZpS6svUn8UG7ILgxf591wRzbpfyxpWv48+u+XrEeDUMK9hEN1/2EwkHLzWYiMnz0v2Xfe0N9l0hdP3tR+oi1P8Ye00s9UjSKcbbVUoaQINxySTMssYVlI0c9XDP1CtLikday8Jns9Eaae9jNBAhW+Jw=="],["MDLfLeGfp1KvGdOYW3LktZVlw6Wg3Km3/vupHGCNHWHGW7Vu+HGfUtlv7gjvvOsFR0trv057TrIfHGt/boMkoqZ39ojAETAW5byh+tTnuOgbZTrJT2P2PoxKVewYUgNAyCBrgHY4J0VFtiZtIjxKng1IwJuhFnRU1LUhfaK/wlK5LW642Y/HYmFtPcLV+htKiIvxGSBtoG1uLwP5fNN/ZReSiAD4vI1Aqx0fRQUOpApnVvNmhF05eHrISb8n8pKFEyJn0LxbvzapYIf+k/x2XT9WHTLpwC0FWH3jsEdXVj3AEtJIei7eUFS9PYnBuwzRM6/kGjUs/WVhlvxPAWaSTFhxYdak7q4h17z3BTIWlaJTQIwOBskZhtcpAQ614+VsJZLGjlFl2J6UQ20mQxy0qPkA9K91gMvphMVwnAh3ERn5WFilqhuSVtKoUvZaqPb2ft5c+4ugUG0a4NOBFcy+VEEwXfpIpu1fcBkgscUiLtJc9Y4MDoYKg3K1hjt2GZejohPFjD+bZSJM8VFdTRoSQTymndlXjCCCFRUpfPOq+5VCkRmEiQ95wPqTtxs+eypqtcLNroluTUPhReRE","3k02fN3fIVxUK33A9EtOE6OILxRtnakeJ10POA==","sgPEZSFDtAsJNpwXwDSQfDhH9Oz7hEjHnrinAIczINotwE38OULA6aWw73uWx6grb1X5+UtHswBBsh8QjIFXqOuxbK/rq1zkXAj2wxxw3xXHnoM7njnxM5JJnULy1CSgIQbSS+nzBJEvhHBhypFDgfO4XS0+pZfppwupPDhwriluzA7JKx7kilfhGmi7zEIpJlvQ/MtgQIAhRvlVLEnwQVSGDd9RPnJzRk+DqfhRFTPjsipAX3J8UiAwntwfmldSHNYwyVKw56HZTFE3QVh67OGdqxhRQ7WJu7Y4+jCA0K5vTFm0CvXjpgR7MTGYJ+oUE8WurioB93/jV3UEshTk1GO1+2S/yRx7nCMVipPtWpLSWtMqvB7lucKTjC1JRwbc3esJaxxDoaQDZzOg9N2f02TLGXcpt8NdBC/o7zMwhg+OLBc9Joo3tKqXvdOreLulbTkYMNyx3S8jo9/PfrmpS5eyyE8xzRO+D14Lw86HMKYc+pewMBbyhnTASx61oMlZq/VUUQMV+Te4Pl6wRuZwMoYfyvitRTcPeftIXaGOrEaLZcgLG1Bk7iqU5DQ8HTPit93JMnqrE5Cbc/m9yWQy5JsOgaWmAayk+cTm2A==","nmWMAf4SxeILRhBLkiuF+cdca/dItQDqyFaavw==","G1tE5V5BACw7MVsQXRT6c1uCBq0gRbFoqwIlNfPy8+sCX0uLmJxNs+DqrwCOOW6URgzUHlDBbdoufgw8Z1A7HhKHxNjBZcSufFHtrkc43Hy0Cio64xHOoRKXqc5EMa7LH9lQ5875HcKQ8R4cg6y4wfiP+SPEjSMVhk8TJ9luVlrLErjACXEXSGJFJewrLzeVMGfaFx92+57v4OlI/I3lxTpqqwKTrgN+CSYJv3y+AIYA8lSOjL/dEtYiizJr12T5Jf+OWAzE8LF/dJnDYy+5/qeS2J27RTpq1fGgWvYzeJ1FnaWPz4vyHP+/135gadY9J0mKBuwAPZUFxO4M0BInAoSyMVAlBzffsCE1zXzehSWzMLcNE5QaDoF/XxHu4PuGrBBSsNSCnX5PPbiXtEHG5Rhi4/nE4y9v9XWLJhl+x+pAVFadlEsweiIyFEvDysxHD8a1nV++kfWihU8ik2TJEL8JZ3toXrbvQnpdGl8LcJdUfp59jx7cKhmztLsOuDk+POeTbcbd3NNaMQbffKj+pi8p7nnn9WoXrf+o/A=="],["hec49kssug15UVRx+6Mh8FZiy40Hm55lXzqHYIkEEBr5HGLs9HldzVhu4xsOjZWfCXxq/oKAlsp008QMsrHLR9Nf4FJ6EnCNC4EZctbFJLIr4TEpCix/5haqh3ayY1ZVrrnZk8g+0ck5a91s57NLIC3E6QuraigOGkSn9e0hlvK7Xt86mOfybjg8AeftmebgxZehDdNCx6KpaA08XFBHRFJ3EPVD2S+n8qK2qV6WlPi6rplobGdTCQIcRbQOk+iYVf/pTBjqcVKGVqmfeiSaxzegUQNHXqkFVH7YA4ugfJCyZKIMK3bzODAE70Ybouhvvvpv9BFf4HMw3JwbI8V40V1Hz0/LqMfnfeUa1ATT5Ztx0CajTqYaOAwAN6jLucDerPLX8cC9qKEi83RFp07J1X08z6KwOOcRpqKivw==","y6rXf54CIWAUSTdfM82iI7wk2Flgk9ZxwSqKUw==","OnGbcd1Yim7hx8NCZBY3etAt9QqyeYaOb2oiIw==","QkG1GrZjKifzooL5uR65vW7eUobC6hOh35fb1w==","FqvCydeY0qkWjqNf8Fps29s2LbuWTh9s32agiVZH1fc43aCPAi51vJCvShSB8sIV3tMzm5heQFzyfi7BzlkJ8KIbwQBiVZEAdwvN/WNzW2MwxBGReRtwVBDQHoB3U9siIUr1NoLKLxBCCMEcLbcioqMtmF48FzATI+OSWEq81FLv5QCnvGkRK9ip9tvN/CeryJaEXWt29TswPoP2F3/0CY/0LMBc8DueBHu7cZCJsz4PXClHoxkW6bmh87IdTLX6JVPUzsXLHnIALuwW3Fz5Om1ywuY4ahltysXV65vxlrXQoGYdxUlw7TU1TrUo8zyzVhykLsdgZ4WF2oNNnturbb8kcZGgXijj+q75KWyUa18RSPuYWQowiNjf4N1hv4jikXpaScLz978eY7psZkVRcRhs5PtS3QnMXgxX6Iy5BC/lFjyVMjIDIWXLm5Slwwd6coiX6d16YApJ10ct/uEHfdU70MfMjWIA4RwyjoT0NjqwZzuFhHRc7onIv3Bmiq+Qbh8F0XRU14e7ZLl7mpurfNoFVNsZI6XkSpVZ3A=="]];
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
                                $roundstr = '664' . substr($roundstr, 3, 9);
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
                                
                                $result_val['ExtendFeatureByGame'] = $stack['ExtendFeatureByGame'];
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 1);
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
                            $result_val['NextModule'] = $stack['NextModule'];
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
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
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
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
                            $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet,1000);
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
        public function generateResult($slotSettings, $result_val, $slotEvent, $betline, $lines, $originalbet,$packetid){
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
                 
                if(isset($stack['CurrentSpinTimes'])){
                    $awardSpinTimes = $stack['AwardSpinTimes'];
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

            $stack['ExtraData'] = [0];
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
            //$result_val['Multiple'] = $stack['Multiple'];
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
                    if(($stack['AwardRound'] == $stack['CurrentRound']) && ($stack['RetriggerAddSpins'] == 0)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                        $isState = true;
                    }
                    if($packetid == 1000){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                        $isState = true;
                    }
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
            if(isset($result_val['SymbolResult'])){
                
                $proof['symbol_data']               = $result_val['SymbolResult'];
                
            }
            $proof['extra_data']                = $result_val['ExtraData'];
                $proof['reel_pos_chg']              = $result_val['ReellPosChg'];
                $proof['reel_len_change']           = $result_val['ReelLenChange'];
                $proof['symbol_data_after']         = [];
            
            
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
            foreach($result_val['ExtendFeatureByGame'] as $item){
                $newItem = [];
                $newItem['name'] = $item['Name'];
                if(isset($item['Value'])){
                    $newItem['value'] = $item['Value'];
                }else{
                    $newItem['value'] = null;
                }
                $proof['extend_feature_by_game'][] = $newItem;
            }
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
                $wager['game_id']               = 143;
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

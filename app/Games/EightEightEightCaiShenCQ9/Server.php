<?php 
namespace VanguardLTE\Games\EightEightEightCaiShenCQ9
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
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 4}],"msg": null}');
                    $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetBalance().'],"evt": 1}');                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'FeatureMinBet', 0);
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
                            $result_val['MaxBet'] = 600;
                            $result_val['MaxLine'] = 1;
                            $result_val['WinLimitLock'] = 300000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
                            $result_val['ExtendFeatureByGame'] = null;
                            $result_val['ExtendFeatureByGame2'] = [["name"=>"FeatureMinBet","value"=>"4200"],["name"=>"Feature2MinBet","value"=>"9000"]];
                            $result_val['IsReelPayType'] = true;
                            $result_val['Cobrand'] = null;
                            $result_val['PlayerOrderURL'] = config('app.cq9history') . '/platform/?gametoken=' . auth()->user()->api_token;
                            $result_val['PromotionData'] = null;
                            $result_val['IsShowFreehand'] = false;
                            $result_val['IsAllowFreehand'] = false;
                            $result_val['FeedbackURL'] = null;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['DenomMultiple'] = $initDenom * $this->demon;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = [
                                "g" => "227",
                                "s" => "5.27.1.0",
                                "l" => "1.2.16",
                                "si" => "59"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["dwEGCCQIT0xV38eKik4cKYnvSoaD+mAt6FCIyaMkCACDHFcqrhx9X2lOLqmyrc0oo/uYrvUUa9h35hgvJ6Tsb65COl1yMffP3QUh4VPYGxZ2MuGfetBg4TigGrg8c1w4ZbUfCeDE0rXBFZpkNeVn/LoR/sv+bYDCERFPM2t11iLrnZK79RxZbsgdoClYObCDeU6g+6GNM9ZbhWdVG81DlTesM38F3NJwCREtuwBVnsZYe/kt81hoayGZe3PZPO9Tk2vvDJYyRFPvpq4uoCLwOYW6bf5aDiXD6bKC8deh94gOWyfoSLJrFZme8YHzycYFkAVN8Xq+9Hsqe2/w","YJgJ3GG5rwMoLFpLUsBgw2iKqpFXXApID8abWYQxHav753TJhkeUy5iASZcz5tzvBUZeoY2Mp+OhAiBpDmXoloqSxLCj54y0zkkXenCQ7DNU+IKDBwEbacPeJpolAVfSGP5QP+u/EONYtHJvXdLPoZoQ7E1guYWPV0PXnFaYlzgDG7BVS8sv3aZHHB1u6F9pJHVOlFWOlW9Smkptuy5NSOuVysJ/T2vi8V28NV3vJplMbuzYl/N8fiBP7sS2SV6+GlCwOIFP+XjgpX+bgwHBF1/ot64vAfJwfmAmxl224ySyaWQaBHMSeafLUXPak9UqResFVHykSkys0/lFwHvHsTm5izwuGdNloycn2aSnZDdFbd0NnTvzL2CKT8c=","fdfFtGJ7hyqmfjR8gQhIvfZglz6iNODVacm4rWuvE6WUwJ679VGes//husfzWQKhu3seM4XvZM3tgSsvQLhi5arZkQIW+77xvPtw6m/ykjzn3FsF0aLA2JW7POpFUc0Y90sN3NhP/z+22/g3noObMeEJCzWxofYV0ZsxhE9OpOgmDdBGEl9LkOFSxUXR+ti9sqX6ae5F8Tcxl1zvI2AkfJf1jqFWRw641EXyiwWQMLH9KOhAfORQtdAIBcp4xFCLBD6d3xdKfNUCllJdfhL08RqKgdr29u4PO+AwlIbZVBBFKDKf8jB5wxN6EStuaeIdLrVVp4lxIgNCYsFb2I/6ffUOZa3PM3VcxOsybfzRuT3pY82NWARto8De57bf0oN8Iz7/mN4zrUeTHlPV","xNYGrKgT1atrBlZX94hgR78sOT7CShWc27rBu9tXMbayx2mSpBodnnaXFIMQ+TTKREAQJakgxGChqbtKfcynQhQKrlC0UWqJOUlnaS4+gI0ZxHQI80a9WXMXE3IYSQqTwPEsYMbwKXrrYr0DVVwMAkSxlyeLdT/O35r7bj2eTVNUCwzBMPzHvapPKdNjiNw/nRCBDCV/arp/qj+7V+lj8dYwP4eBIvexM7Nd406zodpK4/X+z5omQVAM8wmYkyc4G7oer47mwXvOtfghekzKMji7IH9jDJwCMU93lA72sejA1A87wXynCCsXOcUDWdU4l+ggGo+Xr/kHBFxeBtRvR94UzhRqsknAReKNdg==","h0DqaoOv56Maxj6c9ow8is+xq8xsh761DKJbpK8+DyUT2W2EcsSeGGaD0Z0wEQ3hHvkX+HKSi81dPtqhPwoDwIE1FxFJ6GRSVztP59/xBSsMZ/+a/oLA+a6mJSP1kdajC120ffYOQxHIRvS02xMsLTgdadW15bgKGklHTMItvZzfGNfeL17IYfONHqebrPACqMngVa5HQyqnVDo8xFyY/MMARsuNhAi+bRI6c0V4ThArnYyw594+6VZC8WtpJmt53BDHZ1+3IXhjCLGCAxidMUZ286x1YZcM7GqxZJGrE3HgDbV6HVWkzGuMYq8HmNDS3wMT0TE6X5ahwrrfhXsiofLC+P4e66UZXRRFOQ=="]];
                            $result_val['FGStripCount'] = 2;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["jykblgp5SeFAXu6AGun+qi/wItvLgJW9g00iHnPeaqTZQj8Ncsqy68K9Aaq0TUsbHFERZVSm9ymJeF8HV72y647ErOnpdjG3Lb6M0pNRtvmxMVefWN1LxTuSyTnouAvrdn3PcJwmhdkLwcUURJTkBCrAiyzQsv8TD7kLg3QqQzVURy+rtcseoG5xT5dBdTngynD6iQynvIbNOA+7+RG9Td5Ed5XixCTqQWVVqG3PResAEhLcGwqeWd8Q16viyjOInxovJcqb5CeK4WMZOctOYR2i0XxTMKhu10R8HKKzZ/fmPukKQILnF85xZf71SepLEzRyXsFTlH/bWfcnGtzlduqNYSjUIPREdHF1SA==","1jNJAYFdaegMuVE6cyppufRHAdUJN5LTx2aq8AnTzkZ+HlRScbESKD1dtKdHdvwC0/8dFY8eAcvWaAEowNvjSHgtJdNVRMoaYHsIzTccZYrk4FNFreDq7ju2qzIkxMqpEN9YExd71j8xSwXy0TXqFfvVOTY44FF9jd8sXpm/F52S4atwPmcVYZ1uTKxiBzel8k7ncJ7H38KJLVe79wecItCAW/5xXN2xfXBZy8kPL3zeWIf8URxGL+qRZ63F487sfMRXKe/aUYNChBIIfG74R7HO0l0J+WYmyI5XZg==","PPnS1GYAkW8wb9328G0eqt/i0mYTnLI9+AEnFW8FDsTl8806HcK1rmASf/aWeKBciYwynAFG+v1wI1VV4BTPjxOeMFEJ3FBX3QuTmOd4JLtawGfa6OTDLIGjeGHc3N4l1BwoofoGdV8DW2JCDY1gdNppvJ2A1nawVQ6RRpCmRWzQ9Sk6gIsNC34bJPqsubGdr2sFjA9q6QlBG5vw0ITvNgREQwi5F8AVxY/KNjDDXWoFEEcbPKkX6oCtSsPMcLiQasl8vFa8tzTEpPG09c4Um6uYulxzlNaMIo6+Xw+8kiRsRSXC+D5ilzqq6y+p39xOMvGm7H7lxMV4Nrq+b0drmO4cVRrjHkPTIdkDKQ==","UYhWCkhWpYXyMCSq/NBdgabPiQsu8j+hOZvzfTGM98sO5/YbDJvzzOCWqUwT08KvT/hsexUnVcoFhQU9312bC3g/5KxZdMQ8avVi2crSzacBdHsi7KFJCNpg+A8Y5soblG0eCjjAYvaXynrbmjxaiRTmXuCpKPcICQEtFE9MvieN5V5s7mpFfkO3l7M/jKNEzBMNwBqSMDf3OUBKEZzgN1oh/GdZEuKIsTmYvBWrU1237jBLnrHmdBopDFLdLzp1AiE8caignY6UoMis6vUh9qnBaFzlBSh/5A1AcO59kcqvu/XZ8FdPLlNbeLb0vTTKzXxPfMCsmb8Zl0EAYkS9+mkiOnWVrQUxUuJeTA==","RZlBHAFU8sVoB0HdAsxJ6UztkIuf3NUT3tGgTbI5KjZ4pKvRp8JsRFz/P8FQ22/hqWl7zgh3FA1c+v+39DhXZhx719Bz/XSk8WXsh/JM1Ms9P9Xbr83kDg7JjGcXwYCj+yYxZBQyQeBvExh8GDybYX/bzAPjQrAHfDio5DYKhJIQPaiaEu3S8eeFMKrTh7AQxFoJc+re71TupJn3mBLj69qMfQ21R/YoEjwd4l4RPnX0Iq9IMjnuPLbCpNUOmixZDcoI8S+VRQi1NfFU7z99bXAOa8CVmizSlAps4w=="],["zoYcK2Ii5sWp5USTKX7q25YqBQ9PeC0XskcEPOCh18zjV5WCkClh3plFMogucShz4a4ViwZ18GRiY1VGqNpMWPY/mGUlN4/DdBgHSWOLimsI5qGwNTiREP1OOWQX5AhGqN711Qb1gNjVDOrvu7owV5gMhJHfvQ5L6E9D218Ugdj2Ejq3nsocCN2JdvVy+/wKzLXnELsF6n9qdEudYsao1TUpqxehc3SpFLYcMFv+bnPnY8slEMTImiEj2OMZ6+A/DeVaKryTHgRv1S95hp9HkHWDQHCdHrEuwAijGE0PuTkK0IJk4DDyHdAZmBSZQT+iNl8Wcx8Xk5Hnen/Ui7tSk7c3oJvooLgDQMBkUw==","G10k1L3psbkX1xMcH3mfhFJCYWT/DUuuQZxvczRJ5N17NVtk9PZ0owxmDD59OeUeTl3TiG68gLueJD3NQVE2byHLLZczZN4qckzfC+RA/stYLckOvxlT++QjmmOBi0hqMOnwJAU8fcIbLLtvbkTRPk2VQqGR0IqD6cQAT8wVSATfJtPPwQz73izokMWKGfcK24BrUwYgJ5a3YfZ7nBVmD/l0qhBLfE5GSx6k5sPMCuVNNTRdqp2+LBm6OmTluQE+hS5Bna7knQiL0GP9PTL8ajsClMkYKvBjVMY97Q==","iYKtxh6eSMhK88AsKAJos0sCcik6YSr+R84D1w4riV0YzUsrGNiKOi+z9jfkpAL4Z+EHaw9rSEYxFrZ67QjcWbmKoaBlCAq3sXSyiHhAwPo5kx0cdN/VrR4TFHTShKJHCidCbiduTnB7YIbDTYh4ArKhOHL2/caZGTasDvC7CXN1/t4UFTrZjcn/OtdevF4b8E2lOUiiZjWQ3wjxDDUeqCZCajMkAP6ckgtkRsG/GPtIzuml2az/UumaeFUpR/fEzPLq8zdbiQIZggSik0H4EzMiVqoGZN9EF1JkDiixRCe8FXI+hM7xEXj5tQDmgz/L2g6y6W/UWMv+EbjG07JS7nroRes+hnuK7G04bw==","TEM5I8cOrcCYDALEn7VkLHysFv528rPMpOMcpNsRh3K5n6auaNn8nn0Iny3nZg8gbGZP3I9oe3hj5S11e9veX6U0JrMQrjWqRaM2Ew6VkJJ6FcfDHfq3fWNdx6FFxFSFhtGaVPrO/6w/rz3rexpJfXUIgNO5SBVhuNVuk48cdAU7qAgy0tp/27BcIka8bLOytd/3SNt7EPUezzACo9AANOVrem+3z6yGxdE4yLxS0KW6bd7YayYTZP53JNSG/IBsJbbfL7cYAD4o9uQKqDMtYfCBnpx1TQRoA/VWiKeyQCl1IKBjYdTx1aBbzungiNm40BSJfR4ECB82dpvo5xw2EiWsboNjp39D7rE9Xw==","rFvVF61fuVIL5rTIUEUEEfcYmFb4SlrOSP4VOXGCNmy1PBx/fXhr0MOJv5pCvUxzSJK2/XV5bqk06l+A/WWqJzdotejAJjOIPcrUmp7JkvU1uznUqa8K17YRc60HC4gUOndpdqEwygpxNVvfJRRvvzZ0w5gDhpQuPbz1V3HuVZbStyfY+/oEea3Y9d3LTG/hSSHXFEsD9NJ53+bWJoNDjfs55oBKhhBMJGqpyeo273CXRSKP+EY3AeLW4KkXwAcuIS4K4M4+qFy6WSoMekatPGGCV1te2wrGjREA0A=="]];
                        }else if($packet_id == 31 || $packet_id == 42){
                            $lines = 60;
                            if($packet_id == 31){
                                 $betline = $gameData->PlayBet;// * $gameData->MiniBet;
                            }else if($packet_id == 42){
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            }
                            if($packet_id == 42 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                                $slotEvent['slotEvent'] = 'freespin';
                            }else{
                                $slotEvent['slotEvent'] = 'bet';
                                $pur_level = -1;
                                
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks  릴배치표 저장
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                                $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'FeatureMinBet', $gameData->ReelPay);
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', $betline);
                                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lines);
                                
                                $slotSettings->SetBet();  
                                $isBuyFreespin = false;
                                $allBet = ($betline /  $this->demon) * $lines;
                                if($gameData->ReelPay > 0){
                                    if(($gameData->ReelPay / $allBet) == 70){
                                        $pur_level = 0;
                                    }else if(($gameData->ReelPay / $allBet) == 150){
                                        $pur_level = 1;
                                    }
                                    
                                }
                                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur_level);
                                if($pur_level >= 0){
                                    $allBet = $slotSettings->GetGameData($slotSettings->slotId . 'FeatureMinBet');
                                    $isBuyFreespin = true;
                                }     
                                $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                                $_sum = $allBet / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent'], $isBuyFreespin);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '569' . substr($roundstr, 3, 9);
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
                        $lines = 60;
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
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $winType = 'bonus';
                }
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, $betline * $lines, $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin'));
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
                $currentSpinTimes = $stack['CurrentSpinTimes'];   
            }
            foreach($stack['udsOutputWinLine'] as $index => $value){
                if($value['LinePrize'] > 0){
                    $value['LinePrize'] = ($value['LinePrize'] / $originalbet * $betline) / $this->demon;
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
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $isState = true;
                }
            }
            

            $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
            if($isState == true){
                $allBet = ($betline /  $this->demon) * $lines;
                if($slotEvent == 'freespin' && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $slotSettings->GetGameData($slotSettings->slotId . 'FeatureMinBet');
                }
                $slotSettings->SaveLogReport(json_encode($gamelog), $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
            }
            

            if($slotEvent != 'freespin' && $freespinNum > 0){
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
            }
            return $result_val;
        }
        public function parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines){
            $currentTime = $this->getCurrentTime();
            $allBet = ($betline /  $this->demon) * $lines;
            if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                $allBet = $slotSettings->GetGameData($slotSettings->slotId . 'FeatureMinBet');
            }
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
            foreach($result_val['ExtendFeatureByGame2'] as $item){
                $newItem = [];
                $newItem['name'] = $item['Name'];
                if(isset($item['Value'])){
                    $newItem['value'] = $item['Value'];
                }
                $proof['extend_feature_by_game2'][] = $newItem;
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
                $bet_action['amount']           = $allBet;
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
                $wager['game_id']               = 227;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = $allBet;
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

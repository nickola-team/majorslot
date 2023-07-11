<?php 
namespace VanguardLTE\Games\CricketFeverCQ9
{
    include('CheckReels.php');
    class Server
    {
        public $winLines = [];
        public function get($request, $game, $userId) // changed by game developer
        {
            /*if( config('LicenseDK.APL_INCLUDE_KEY_CONFIG') != 'wi9qydosuimsnls5zoe5q298evkhim0ughx1w16qybs2fhlcpn' ) 
            {
                return false;
            }
            if( md5_file(base_path() . '/app/Lib/LicenseDK.php') != '3c5aece202a4218a19ec8c209817a74e' ) 
            {
                return false;
            }
            if( md5_file(base_path() . '/config/LicenseDK.php') != '951a0e23768db0531ff539d246cb99cd' ) 
            {
                return false;
            }
            $checked = new \VanguardLTE\Lib\LicenseDK();
            $license_notifications_array = $checked->aplVerifyLicenseDK(null, 0);
            if( $license_notifications_array['notification_case'] != 'notification_license_ok' ) 
            {
                $response = '{"responseEvent":"error","responseType":"error","serverResponse":"Error LicenseDK"}';
                exit( $response );
            }*/
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
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 11}],"msg": null}');
                    $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetBalance().'],"evt": 1}');                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'AwardWins', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentAwardWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'AwardLevel', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentLevel', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ScatterWin', 0);
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
                            $result_val['DefaultDenomIdx'] = 4;
                            $result_val['MaxBet'] = $slotSettings->Bet[count($slotSettings->Bet) - 1];
                            $result_val['MaxLine'] = 1;
                            $result_val['WinLimitLock'] = 3000000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
                            $result_val['ExtendFeatureByGame'] = null;
                            $result_val['ExtendFeatureByGame2'] = null;
                            $result_val['IsReelPayType'] = false;
                            $result_val['Cobrand'] = null;
                            $result_val['PlayerOrderURL'] = config('app.cq9history') . '/?gametoken=' . auth()->user()->api_token;
                            $result_val['PromotionData'] = $slotSettings->getPromotionData();
                            $result_val['IsShowFreehand'] = false;
                            $result_val['IsAllowFreehand'] = false;
                            $result_val['FeedbackURL'] = null;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['FreeTicketList'] = null;
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['FreeSpinLeftTimesInfoList'] = null;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["oMkW3v6SGaIrc4zWVxJ8s/myy5JyVDtf9MpkXqAyZVQ1sJoFnqX92kE8p9I75QHcLetIRy0a1omRUkd1io41ju1nFEqQF3OUUHo4j0iCbBL8lwnrV2OcWicQtme20en7J0ivBcai54yIZ+3kXh0S2Tu6AvtF19i6qECijMb8NheqK/B45AdxD7Mt+ZG11ncm3/gjJEGAht3GQnRUcDj12W69g6JKgs84XmgRTACbINL5/q+6Pn40sZoCiiE27ijGfGjZf9BZxhqcOiV9m4TMWvUcboExx0KZk4fUlVahjvdHJ/5ThOwa8GW+uaudATe3eLEfXtF9wVdeCLw1PB7OshuGcZ69E+jxqffbqkgs2GpeMCI2tam5gBX7Z5ksDVxFlRcncRPDNwRBkWkq",
                            "8dhGZiavPmxrpu5YVZcQdeQ3qw0Hvt1hJaWyvpAecGHBi8ir+sC8FOoDw++1vbkHbSoxgGYdtivwacXRZ9TzquBXojl6+md2Hlb62XdlU6eUKB/oHB15Kyb31NysCKpjTg9ItCEbdXscyzjGHbFX6+6Acr9mNTpxm6R1iFRVnskqHImcSUAgtnOpNvJCC+7v3DnibKIXQtxM9Glc8JUe3iHFXfbIDxK3vDuvEmQ6t/mSoKZW+C+3FgeS5UJpnPHvPtYtPozZxRNLKB9aCoM0FJ77nyCJWrweWLbt33326/OWnkdXa9EJj5KWDH8n+rtcBjVFtgAVWWv90nea9aS0bRPt6hKxPGVlPPUi8z619yPQ+HUwb5tbH0nGYieELiHDrqLh58+NBM/1bWw6xoVMlW/nClW4FSY5hrea6CyvkxcHuBYVx4BnXs541ww=",
                            "gtmZJDywWOvCprlOAVu1f3vJDxs6PcmpuTk4C0+j0UhrTPHRHOjKsQV2IRfuOnyWzSK6AY8qymAweOEQnBhgudA7SQCYAccQHkemuFcdMwjQcI7qyimLHZZWD4tSpsy9efpB18CaifRDMvSHvovTEiBGAbd/oo1/3VwuHVq1WzlBdEwbJeF1iEYBXESDi7Op2SaQfgiuYLpIW7vXXyh+KTjYnhj/4YsNgyhxp7R8dbafTFsmPu9I6Qk2JsEvAn6njgipdySBR1vdz8PLr1BHQ8xZBx+clV9ppRIf1ST54TPJCAfNtklh+86rDISRv6xSiRJ21yzq8cgnq+Ds2sRte+pzttXQ27gZtwW4xVeXs5g1tZOgn4g8ypqaA6qMwd/HJEidL23T/TJLfprJacXDocN5AKmhfAvGo61Jq4J1jfPRfvi52CJoDadLwqc=",
                            "eatOd8Vp4IEm1wGZOacdC/y9jGr67uRg0kL/BjjCF9WtOBEnIu4N4QxNIuBTZfVTLZpGfA6zteBYkEoY6ywusqUH0dxLeLjPFp695uTs6K0OsYw7LIMHGyxVBir3P9EwFijXYRADjkRy2/8Qs14R30jCHgKYq5FpnaPTXOPmI0vlZ3vcrX7SbcxP5FPjxPORhCkzH6XAWB/wL/W9dKSlkGhnw4HEuH1nJNQLsPz9cOohxmlf/Grad8LyfA9V9VNRwoG/WWv1Tm8tJdhJr/fAK3NDTzuXCnv04PLIN4Ybu9TmvyhBxU2tQ68bkRpm3CWIz3ZnGTGYx0IrkQgcKTue+yTikOwpicqjG2E4oHqaUC1CFPQ3vw6vjrTb2iZVYmYPn7lrl6kVF3xqyFlXDY3sQa/JPb+Qur7jlc1AdWx0BvoRI6Less0O2gbnhko=",
                            "hTdANupB8nw10P6lqGls5FQdfgFHBXtNdFTbzkXFNKbwnMmF9vfl1GClbrc14xPVfxV28p44zCsotgS4gvwG6op0nh9mNsP+nXe1Ab8ubgiXfZDK/okZDieuUjcSzepetr/+4gmIT25LU7rRQFlXQ3krq+aPpEKvT+Uo7+guaqJN35128OvVW01bnWxfH3ig9psuzoa4O1tbCSfvkNMfbENSs/VzjPw/qrh7ZH71q3+8soqnMGJVpm3US7JXmvIVYNGcRFeI3Q53tzD0+L5tT7f0sggf4zdA7fxZTOxyjU2T8aJ+wP7j4wEtQC4A/MvfXpe5jLGvEiSS45CAgz/2XDgjjTCd288ho3D6P3C8lbZCjYhUhho9AuEcpGqx3+VmZB5MLWxkUqnhYxOOI8GbajBHijRAJNdy7Vn1sA=="]];
                            $result_val['FGStripCount'] = 1;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["3ViquqVz85iF6NGF6/2TeTn2mZ/+a9pU5OwkX9YcYx/ogAWy5TEx29MhxnpVBa6uFhdcdeUWPrkbApQwencnR5Ou0wOUKRLW834pr5z+Z0zR1/LSatLr9h2N0OFlmIvQiXqWxrA840rhuW6pqCphpIdkjp3LvfCXakFybSr0K2JhjhtAHYg42SsvgHRa+L0xEW8KBPnxT1QaMr+Ar6HG/KHu+8bYrelPNI0V8kBma1rpxUOw1salT9SJLz/fohUACRPqBe+ZA/H5LfgkOmCigIPihpGZRYrURSh5ewQKScpLBrL1dw9B47V/QF9vTc6KCQPuh6dsrE8nyQLoWRzd3vHYdH3nLZFY4e1JHIAtmbHJghHFwBc/qav/kYmjKipcIWiCD/Oqe2IqPaCfEGkzWUBHmMauQwHwr6Kqgg==",
                            "fNMWMU5ZCxZhvYXaU3YGZlam7C7vbmG75MPq1opu7xRUGpf9zAJG9FiBpWt29T6ymp8NhstIgWBSEDSHdnWw2f4HM6QJQYjfDgFpwuVm2Hn93X8tNacoTqIk0UOS1LvoC2JEdkvgp5lR0Gb0ZffCME+d91hlQjBMclasrGurLz+en04seo7A9xATJONN9E7wUmDymR0CYIIfwFLXk4WpoYJwMscn3ftddI5ESog6DGZjdYPXpUHwOYlWSDiA1gqm/svuD70iLw9gm+6K4xtQpDk1NkE7WiZvJnL6Tl7Y6xLOD7/C8udWKVtMWLKG9znTMp5Qwv7ExGIyfzRIZcxr2WLPUzmtPBiIrsSCCv/d4gQUa7pQ6GG++g+dUbUjl6JAluzflALi29rxoFlmiN2BlZKc3cik6Q8Orj9N5t+19F8suzaH32huBaCc6RU=",
                            "Ue9o7e8vu5R1p3LpfioxAivDJp9pLP8SRuCBGXy/ED1F8X8SVj640Tt1qzJtYWdKseQ4okKI4bCzxxkQomcpyDQ0LEdjK/TWW5HdfDHyIpO5zysA/QclzybLFEK2qEdQPGpWH7+DC8oLwVSf0BfDmzgHz2bEl+sdV+OA6zgTLytdTGmxsI0zvrnPiJJO/YHGwDgr4BinVuBQSr5BZgHIAAHd/Kiwz/lWVu2L20oRJj07PteC+SY2H7DHMKSYPhrRFHB12lfHVcgwxnbAY6x0ECHnN3Cmh7SzG8k6RdxMNJH9XxeF//bqeMZGS4MivZTkLF1grAI+WIOnJsrqHF2a5mev1u1zdnyQjcdYqEY+7yn0Bkf2pvpn5at/GdF04xNW1Gg4bMWKwZqc2PukKCmpDVXUT8sX4FCrXe5q5k3p9YO2R3TEy3Wdqb/wznJlE87fUl051o1wkJUi8YC0",
                            "FVwGDGsHT52IaS5YPOE7At3tdKHtdppzRiuRFarcyFvGX/LJKF7BqczOmiNOgcRucy/6tARkojBmeMyPdxL+VrYk3qnHPLTreKBCPhZ3+qlfMQFKpngWpNln1SY6wfX5jBnyQJgiON5bOR1elU018RO98hFzw5zF5n4H/dLpxNvg1k+ISyqxtfbgK52sN8CLbdgqZKKJb1lQYEJq/mmlmnnrvzkAjjEOIcI73tzDyu5zx/pN5yKo/yzvLzK3tnimIaPWxULVWY7zkL5aoVFZQUIW+lRNy1YRlgtCGyG0HHLu49S4zsCeJbYYfGRhKhvAwWjZixv7dZEZGRzvwyI6gbSZ5JJmm7DfpyuN8BsLkrIZrt9ZrYfvMhrlf76j5TttGPqYK1O3e1ySpIbWCl3lrYFBTAgXW2aqRGdCoLY1LGxhRFcE/QiRFw0ysGs=",
                            "hLrQhOijw2JaFoQkmxkCv5wKnicfCmzvcifPCnLjlaySGF9IB9PZP3DL03n1m7KojwD0VSBdYInuoP43vGbMw/sAHv3NPqPXU7b31rbhs1YGjRbOwd3x1MFCJZzqHCogJ+7+BarhjhmfH5El0zHCGpxe+pu73DDgEL2V8M7pfKJ9YQH/4McUsYm6zLbiZWS3qyVYk/F+Y1RlN2T7qAJwbyfTxnh0NpTW2Pu0w8I2JxMpt4ksPULGfXUKNHxY9tYWEp8GhnMENSz/QUJ0JhGNL8v+IXpQ5CdzstEHwp6BNTTE6TINAJ+9dr1BfRIqkW/2Iko1zqgFmCKu+gKgul0SjwkHXMZXu7G3t/LGux9lMvXdgkNLF7+/vIju6LhUhZcvslgCyRHMgyw+uOuipFfKeAgiwFoKjVTjg9XmGQ=="]];
                        }else if($packet_id == 31 || $packet_id == 42){
                            if($packet_id == 31){
                                $betline = $gameData->PlayBet;// * $gameData->MiniBet;
                                $lines = $gameData->PlayLine;
                            }else if($packet_id == 42){
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                                $lines = 1;
                            }
                            if($packet_id == 42 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame')){
                                $slotEvent['slotEvent'] = 'freespin';
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                            }else{
                                $slotEvent['slotEvent'] = 'bet';
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'ScatterWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'AwardWins', []);
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentAwardWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'AwardLevel', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentLevel', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                                $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                $slotSettings->SetBalance(-1 * ($betline * $lines * $gameData->MiniBet), $slotEvent['slotEvent']);
                                $slotSettings->UpdateJackpots($betline * $lines * $gameData->MiniBet);
                                $_sum = ($betline * $lines * $gameData->MiniBet) / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '578' . substr($roundstr, 3, 9);
                                $slotSettings->SetGameData($slotSettings->slotId . 'GamePlaySerialNumber', $roundstr);
                            }

                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines);

                            $slotSettings->SaveGameData();
                        }else if($packet_id == 32 || $packet_id == 41){
                            $result_val['ErrorCode'] = 0;
                            // if($type == 3){

                            //     $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance() - $slotSettings->GetGameData($slotSettings->slotId . 'ScatterWin'));
                            // }
                            if($packet_id == 41){
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance() - $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'));
                                $result_val['PlayerBet'] = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                                $result_val['AccumlateWinAmt'] = $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                                $result_val['AccumlateJPAmt'] = 0;
                                $result_val['ScatterPayFromBaseGame'] = $slotSettings->GetGameData($slotSettings->slotId . 'ScatterWin');
                                $result_val['MaxRound'] = 10;
                                $result_val['AwardRound'] = $slotSettings->GetGameData($slotSettings->slotId . 'AwardLevel');
                                $result_val['CurrentRound'] = floor($slotSettings->GetGameData($slotSettings->slotId . 'CurrentLevel'));
                                $result_val['MaxSpin'] = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames');
                                $result_val['AwardSpinTimes'] = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames');
                                $result_val['Multiple'] = 0;
                                $result_val['GameExtraData'] = "";
                            }else{
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                            }
                        }else if($packet_id == 43){
                            $result_val['TotalWinAmt'] = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                            $result_val['ScatterPayFromBaseGame'] = $slotSettings->GetGameData($slotSettings->slotId . 'ScatterWin');
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
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame')){
                        // FreeSpin Balance add
                        $slotEvent['slotEvent'] = 'freespin';
                        $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                        $lines = 1;
                        $count = 0;
                        while($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame')){
                            $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                            $result_val = [];
                            $result_val['Type'] = 3;
                            $result_val['ID'] = 142;
                            $result_val['Version'] = 0;
                            $result_val['ErrorCode'] = 0;
                            $result_val['EmulatorType'] = 0;
                            $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines);
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
        public function generateResult($slotSettings, $result_val, $slotEvent, $betline, $lines){
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, $betline * $lines, $lines);
            $winType = $_spinSettings[0];
            $_winAvaliableMoney = $_spinSettings[1];
            // $winType = 'win';
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);
            for( $i = 0; $i <= 2000; $i++ ) 
            {
                $totalWin = 0;
                $lineWins = [];
                $lineWinNum = [];
                $wild = 'W';
                $scatter = 'SC';
                $_obf_winCount = 0;
                $strWinLine = '';                                
                $reels = $slotSettings->GetReelStrips($winType, $slotEvent);
                $bonusMul = $slotSettings->GetGameData($slotSettings->slotId . 'BonusMul');
                
                $awardMoney = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentAwardWin');
                $OutputWinChgLines = [];
                $ReellPosChg = 0;
                $lockPos = [];
                $winResults = $this->winLineCalc($slotSettings, $reels, $bonusMul, $betline, 0);
                $totalWin = $winResults['totalWin'];
                $OutputWinLines = $winResults['OutputWinLines'];
                
                if($slotEvent == 'freespin'){
                    $scatter1Count = 0;  
                    $scatter1Positions = [[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0]]; 
                    for($r = 0; $r < 5; $r++){
                        for( $k = 0; $k < 3; $k++ ) 
                        {
                            if( $reels['reel' . ($r+1)][$k] == 'SC1' ) 
                            {                                
                                $scatter1Count++;
                                $scatter1Positions[$k][$r] = 1;
                            }
                        }
                    }
                    if($scatter1Count > 0){
                        $OutputWinLines['SC1'] = [];
                        $OutputWinLines['SC1']['SymbolId'] = 'SC1';
                        $OutputWinLines['SC1']['LineMultiplier'] = 1;
                        $OutputWinLines['SC1']['LineExtraData'] = [0];
                        $OutputWinLines['SC1']['LineType'] = 0;
                        $OutputWinLines['SC1']['WinPosition'] = $scatter1Positions;
                        $OutputWinLines['SC1']['NumOfKind'] = $scatter1Count;
                        $OutputWinLines['SC1']['SymbolCount'] = $scatter1Count;
                        $OutputWinLines['SC1']['LinePrize'] = $awardMoney * $scatter1Count;
                        $OutputWinLines['SC1']['WinLineNo'] = 998;
                        $totalWin = $totalWin + $awardMoney * $scatter1Count;
                    }
                }
                $scattersCount = 0;  
                $scatterPositions = [[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0]]; 
                $scattersReel = [0, 0, 0, 0, 0];
                $isWild = false;
                for($r = 0; $r < 5; $r++){
                    for( $k = 0; $k < 3; $k++ ) 
                    {
                        if( $reels['reel' . ($r+1)][$k] == $scatter ) 
                        {                                
                            $scattersCount++;
                            $scatterPositions[$k][$r] = 1;
                            $scattersReel[$r]++;
                        }
                        else if( $reels['reel' . ($r+1)][$k] == $wild ) 
                        {                                
                            $isWild = true;
                        }
                    }
                }
                $freespinNum = 0;
                $FiveSymbolCount = 0;
                $newAwardMoney = 0;
                if($isWild == true){
                    foreach( $OutputWinLines as $index => $outWinLine) 
                    {
                        if($outWinLine['NumOfKind'] == 5 && ($outWinLine['SymbolId'] == '1' || $outWinLine['SymbolId'] == '2' || $outWinLine['SymbolId'] == '3' || $outWinLine['SymbolId'] == '4')){
                            $freespinNum = 5;
                            $newAwardMoney = $outWinLine['LinePrize'];
                            $OutputWinLines[$index]['LineType'] = 1;
                            $FiveSymbolCount++;
                        }
                    }
                }
                $scatterWin = 0;
                if($scattersCount >= 3){
                    $scatterPay = [0,0,0,3,20,50];
                    $scatterWin = $betline * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet') * $scatterPay[$scattersCount];
                    $OutputWinLines[$scatter] = [];
                    $OutputWinLines[$scatter]['SymbolId'] = $scatter;
                    $OutputWinLines[$scatter]['LineMultiplier'] = 1;
                    $OutputWinLines[$scatter]['LineExtraData'] = [0];
                    $OutputWinLines[$scatter]['LineType'] = 0;
                    $OutputWinLines[$scatter]['WinPosition'] = $scatterPositions;
                    $OutputWinLines[$scatter]['NumOfKind'] = $scattersCount;
                    $OutputWinLines[$scatter]['SymbolCount'] = $scattersCount;
                    $OutputWinLines[$scatter]['LinePrize'] = $scatterWin;
                    $OutputWinLines[$scatter]['WinLineNo'] = 998;
                    $totalWin = $totalWin + $scatterWin;
                }
                if( $i > 1000 ) 
                {
                    $winType = 'none';
                }
                if( $i >= 2000 ) 
                {
                    break;
                }
                if( $freespinNum > 0 && $winType != 'bonus') 
                {
                }
                else if($winType == 'bonus' && $freespinNum == 0){
                }
                else if($FiveSymbolCount > 1){
                }
                else if( $totalWin <= $_winAvaliableMoney && $winType == 'bonus' ) 
                {
                    $_obf_0D163F390C080D0831380D161E12270D0225132B261501 = $slotSettings->GetBank((isset($slotEvent) ? $slotEvent : ''));
                    if( $_obf_0D163F390C080D0831380D161E12270D0225132B261501 < $_winAvaliableMoney ) 
                    {
                        $_winAvaliableMoney = $_obf_0D163F390C080D0831380D161E12270D0225132B261501;
                    }
                    else
                    {
                        break;
                    }
                }
                else if( $totalWin > 0 && $totalWin <= $_winAvaliableMoney && $winType == 'win' ) 
                {
                    $_obf_0D163F390C080D0831380D161E12270D0225132B261501 = $slotSettings->GetBank((isset($slotEvent) ? $slotEvent : ''));
                    if( $_obf_0D163F390C080D0831380D161E12270D0225132B261501 < $_winAvaliableMoney ) 
                    {
                        $_winAvaliableMoney = $_obf_0D163F390C080D0831380D161E12270D0225132B261501;
                    }
                    else
                    {
                        break;
                    }
                }
                else if( $totalWin == 0 && $winType == 'none' ) 
                {
                    break;
                }
            }
            if($totalWin > 0){
                $slotSettings->SetBalance($totalWin);
                $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
            }
            $isEnd = true;
            $isTriggerFG = false;
            $result_val['Multiple'] = 0;
            if($freespinNum > 0){
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', $bonusMul);
                $awardwins = $slotSettings->GetGameData($slotSettings->slotId . 'AwardWins');
                array_push($awardwins, $newAwardMoney);
                $slotSettings->SetGameData($slotSettings->slotId . 'AwardWins', $awardwins);
                $isTriggerFG = true;
                if($slotEvent != 'freespin'){                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'AwardLevel', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentLevel', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespinNum);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentAwardWin', $newAwardMoney);
                }
                $isEnd = false;
            }
            if($slotEvent == 'freespin'){
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);


                $result_val['AccumlateWinAmt'] = $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                $result_val['AccumlateJPAmt'] = 0;
                $result_val['ScatterPayFromBaseGame'] = $slotSettings->GetGameData($slotSettings->slotId . 'ScatterWin');
                if($freespinNum > 0){
                    $result_val['RetriggerAddRound'] = 1;
                    $slotSettings->SetGameData($slotSettings->slotId . 'AwardLevel', $slotSettings->GetGameData($slotSettings->slotId . 'AwardLevel') + 1);
                }else{
                    $result_val['RetriggerAddRound'] = 0;
                }
                $result_val['AwardRound'] = $slotSettings->GetGameData($slotSettings->slotId . 'AwardLevel');
                $result_val['CurrentRound'] = floor($slotSettings->GetGameData($slotSettings->slotId . 'CurrentLevel'));
                $result_val['AwardSpinTimes'] = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames');
                $result_val['CurrentSpinTimes'] = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                $result_val['RetriggerAddSpins'] = 0;
                $result_val['LockPos'] = $lockPos;
                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame')){
                    $isEnd = false;
                }else if($slotSettings->GetGameData($slotSettings->slotId . 'AwardLevel') > $slotSettings->GetGameData($slotSettings->slotId . 'CurrentLevel')){                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentLevel', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentLevel') + 1);
                    $awardwins = $slotSettings->GetGameData($slotSettings->slotId . 'AwardWins');
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentAwardWin', $awardwins[$slotSettings->GetGameData($slotSettings->slotId . 'CurrentLevel') - 1]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 5);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $isEnd = false;
                }
            }else{
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
            }
            
            $lastReel = [];
            $rngData = [];
            $symbolResult = [[],[],[]];
            for($k = 1; $k <=5 ; $k++){
                array_push($rngData, $reels['rp'][$k]);
                for($j = 0; $j < 3; $j++){
                    array_push($lastReel, $reels['reel'.$k][$j]);
                    $symbolResult[$j][$k - 1] = $reels['reel'.$k][$j];
                }
            }
            if($slotEvent != 'freespin'){
                $result_val['GamePlaySerialNumber'] = $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'); //449660471
            }
            $result_val['RngData'] = $rngData;
            $result_val['SymbolResult'] = [implode(',', $symbolResult[0]), implode(',', $symbolResult[1]), implode(',', $symbolResult[2])];
            if($freespinNum > 0){
                $result_val['WinType'] = 2;
            }else if($totalWin > 0){
                $result_val['WinType'] = 1;
            }else{
                $result_val['WinType'] = 0;
            }
            $result_val['BaseWin'] = $totalWin;
            $result_val['TotalWin'] = $totalWin;                            
            
            if($isTriggerFG){
                $result_val['IsTriggerFG'] = $isTriggerFG;
                $result_val['NextModule'] = 20;
            }else{
                // $result_val['NextModule'] = 0;
            }
            $result_val['ExtraDataCount'] = 1;
            $result_val['ExtraData'] = [0];
            $result_val['BonusType'] = 0;
            $result_val['SpecialAward'] = 0;
            $result_val['SpecialSymbol'] = 0;
            $result_val['ReelLenChange'] = [];
            $result_val['ReelPay'] = [];
            $result_val['IsRespin'] = false;
            $result_val['FreeSpin'] = [$freespinNum];
            $result_val['RespinReels'] = [0,0,0,0,0];
            $result_val['NextSTable'] = 0;
            
            $result_val['IsHitJackPot'] = false;
            $result_val['udsOutputWinLine'] = [];
            $lineCount = 0;
            $result_val['ReellPosChg'] = [0];
            foreach( $OutputWinLines as $index => $outWinLine) 
            {
                array_push($result_val['udsOutputWinLine'], $outWinLine);
                $lineCount++;
            }
            $result_val['WinLineCount'] = $lineCount;

            $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
            if($isEnd == true){
                $slotSettings->SaveLogReport(json_encode($gamelog), $betline * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet'), $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'));
            }

            if($slotEvent != 'freespin' && $freespinNum > 0){
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
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
            $proof['reel_pay']                  = $result_val['ReelPay'];
            $proof['respin_reels']              = $result_val['RespinReels'];
            $proof['bonus_type']                = $result_val['BonusType'];
            $proof['special_award']             = $result_val['SpecialAward'];
            $proof['special_symbol']            = $result_val['SpecialSymbol'];
            $proof['is_respin']                 = $result_val['IsRespin'];
            $proof['fg_times']                  = $result_val['FreeSpin'];
            $proof['fg_rounds']                 = floor($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') / 8);
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
                $sub_log['sub_no']              = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
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
                $bet_action['amount']           = $betline * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
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
                $wager['game_id']               = 188;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = $betline * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
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
        public function winLineCalc($slotSettings, $reels, $bonusMul, $betline, $lineType){
            $this->winLines = [];
            for($r = 0; $r < 3; $r++){
                if($reels['reel1'][$r] != 'SC1'){
                    $this->findZokbos($reels, $r * 5, $reels['reel1'][$r], 1);
                }
            }
            $OutputWinLines = [];
            $lineCount = 0;
            $totalWin = 0;
            for($r = 0; $r < count($this->winLines); $r++){
                $winLine = $this->winLines[$r];
                $winLineMoney = $slotSettings->Paytable[$winLine['FirstSymbol']][$winLine['RepeatCount']] * $betline * $bonusMul;                                    
                if($winLineMoney > 0){
                    if(!isset($OutputWinLines[$winLine['FirstSymbol']])){
                        $OutputWinLines[$winLine['FirstSymbol']] = [];
                        $OutputWinLines[$winLine['FirstSymbol']]['SymbolId'] = $winLine['FirstSymbol'];
                        $OutputWinLines[$winLine['FirstSymbol']]['LineMultiplier'] = $bonusMul;
                        $OutputWinLines[$winLine['FirstSymbol']]['LineExtraData'] = [0];
                        $OutputWinLines[$winLine['FirstSymbol']]['LineType'] = $lineType;
                        $OutputWinLines[$winLine['FirstSymbol']]['WinPosition'] = [[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0]];
                        $OutputWinLines[$winLine['FirstSymbol']]['NumOfKind'] = $winLine['RepeatCount'];
                        $OutputWinLines[$winLine['FirstSymbol']]['SymbolId'] = $winLine['FirstSymbol'];
                        $OutputWinLines[$winLine['FirstSymbol']]['LinePrize'] = $winLineMoney;
                        $OutputWinLines[$winLine['FirstSymbol']]['WinLineNo'] = $lineCount;
                        $lineCount++;
                    }else{
                        $OutputWinLines[$winLine['FirstSymbol']]['LinePrize'] += $winLineMoney;
                        // $OutputWinLines[$winLine['FirstSymbol']]['WinLineNo']++;
                    }
                    $totalWin += $winLineMoney;
                    
                    $winSymbolPoses = explode('~', $winLine['StrWinLine']);
                    for($k = 0; $k < count($winSymbolPoses); $k++){
                        $OutputWinLines[$winLine['FirstSymbol']]['WinPosition'][floor($winSymbolPoses[$k] / 5)][$winSymbolPoses[$k] % 5] = 1;
                        if($reels['reel' . ($winSymbolPoses[$k] % 5 + 1)][floor($winSymbolPoses[$k] / 5)] == 'W'){
                            $OutputWinLines[$winLine['FirstSymbol']]['WinPosition'][floor($winSymbolPoses[$k] / 5)][$winSymbolPoses[$k] % 5] = 2;
                        }
                    }
                    $symbolCount = 0;
                    for($k = 0; $k < 3; $k++){
                        for($j = 0; $j < 5; $j++){
                            if($OutputWinLines[$winLine['FirstSymbol']]['WinPosition'][$k][$j] > 0){
                                $symbolCount++;
                            }
                        }
                    }
                    $OutputWinLines[$winLine['FirstSymbol']]['SymbolCount'] = $symbolCount;
                }
            }
            $result = [];
            $result['totalWin'] = $totalWin;
            $result['OutputWinLines'] = $OutputWinLines;
            return $result;
        }
        public function findZokbos($reels, $strLineWin, $firstSymbol, $repeatCount){
            $wild = 'W';
            $bPathEnded = true;
            if($repeatCount < 5){
                for($r = 0; $r < 3; $r++){
                    if($firstSymbol == $reels['reel'.($repeatCount + 1)][$r] || $reels['reel'.($repeatCount + 1)][$r] == $wild){
                        $this->findZokbos($reels, $strLineWin . '~' . ($repeatCount + $r * 5), $firstSymbol, $repeatCount + 1);
                        $bPathEnded = false;
                    }
                }
            }
            if($bPathEnded == true){
                if($repeatCount >= 3){
                    $winLine = [];
                    $winLine['FirstSymbol'] = $firstSymbol;
                    $winLine['RepeatCount'] = $repeatCount;
                    $winLine['StrWinLine'] = $strLineWin;
                    array_push($this->winLines, $winLine);
                }
            }
        }
    }

}

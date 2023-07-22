<?php 
namespace VanguardLTE\Games\CricketFeverCQ9
{
    class Server
    {
        public $demon = 1;
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
            $originalbet = 2;
            $slotSettings->SetBet();
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 0}],"msg": null}');
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
                                array_push($betButtons, $slotSettings->Bet[$k] * $this->demon);
                            }
                            $result_val['DenomDefine'] = $denomDefine;
                            $result_val['BetButton'] = $betButtons;
                            $result_val['DefaultDenomIdx'] = 4;
                            $result_val['MaxBet'] = $slotSettings->Bet[count($slotSettings->Bet) - 4] * $this->demon;
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
                            $result_val['PromotionData'] = null; //$slotSettings->getPromotionData();
                            $result_val['IsShowFreehand'] = false;
                            $result_val['IsAllowFreehand'] = false;
                            $result_val['FeedbackURL'] = null;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['FreeTicketList'] = null;
                            $result_val['DenomMultiple'] = $initDenom * $this->demon;
                            $result_val['FreeSpinLeftTimesInfoList'] = null;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            $result_val['BGContext'] = [["oMkW3v6SGaIrc4zWVxJ8s/myy5JyVDtf9MpkXqAyZVQ1sJoFnqX92kE8p9I75QHcLetIRy0a1omRUkd1io41ju1nFEqQF3OUUHo4j0iCbBL8lwnrV2OcWicQtme20en7J0ivBcai54yIZ+3kXh0S2Tu6AvtF19i6qECijMb8NheqK/B45AdxD7Mt+ZG11ncm3/gjJEGAht3GQnRUcDj12W69g6JKgs84XmgRTACbINL5/q+6Pn40sZoCiiE27ijGfGjZf9BZxhqcOiV9m4TMWvUcboExx0KZk4fUlVahjvdHJ/5ThOwa8GW+uaudATe3eLEfXtF9wVdeCLw1PB7OshuGcZ69E+jxqffbqkgs2GpeMCI2tam5gBX7Z5ksDVxFlRcncRPDNwRBkWkq",
                            "8dhGZiavPmxrpu5YVZcQdeQ3qw0Hvt1hJaWyvpAecGHBi8ir+sC8FOoDw++1vbkHbSoxgGYdtivwacXRZ9TzquBXojl6+md2Hlb62XdlU6eUKB/oHB15Kyb31NysCKpjTg9ItCEbdXscyzjGHbFX6+6Acr9mNTpxm6R1iFRVnskqHImcSUAgtnOpNvJCC+7v3DnibKIXQtxM9Glc8JUe3iHFXfbIDxK3vDuvEmQ6t/mSoKZW+C+3FgeS5UJpnPHvPtYtPozZxRNLKB9aCoM0FJ77nyCJWrweWLbt33326/OWnkdXa9EJj5KWDH8n+rtcBjVFtgAVWWv90nea9aS0bRPt6hKxPGVlPPUi8z619yPQ+HUwb5tbH0nGYieELiHDrqLh58+NBM/1bWw6xoVMlW/nClW4FSY5hrea6CyvkxcHuBYVx4BnXs541ww=",
                            "gtmZJDywWOvCprlOAVu1f3vJDxs6PcmpuTk4C0+j0UhrTPHRHOjKsQV2IRfuOnyWzSK6AY8qymAweOEQnBhgudA7SQCYAccQHkemuFcdMwjQcI7qyimLHZZWD4tSpsy9efpB18CaifRDMvSHvovTEiBGAbd/oo1/3VwuHVq1WzlBdEwbJeF1iEYBXESDi7Op2SaQfgiuYLpIW7vXXyh+KTjYnhj/4YsNgyhxp7R8dbafTFsmPu9I6Qk2JsEvAn6njgipdySBR1vdz8PLr1BHQ8xZBx+clV9ppRIf1ST54TPJCAfNtklh+86rDISRv6xSiRJ21yzq8cgnq+Ds2sRte+pzttXQ27gZtwW4xVeXs5g1tZOgn4g8ypqaA6qMwd/HJEidL23T/TJLfprJacXDocN5AKmhfAvGo61Jq4J1jfPRfvi52CJoDadLwqc=",
                            "eatOd8Vp4IEm1wGZOacdC/y9jGr67uRg0kL/BjjCF9WtOBEnIu4N4QxNIuBTZfVTLZpGfA6zteBYkEoY6ywusqUH0dxLeLjPFp695uTs6K0OsYw7LIMHGyxVBir3P9EwFijXYRADjkRy2/8Qs14R30jCHgKYq5FpnaPTXOPmI0vlZ3vcrX7SbcxP5FPjxPORhCkzH6XAWB/wL/W9dKSlkGhnw4HEuH1nJNQLsPz9cOohxmlf/Grad8LyfA9V9VNRwoG/WWv1Tm8tJdhJr/fAK3NDTzuXCnv04PLIN4Ybu9TmvyhBxU2tQ68bkRpm3CWIz3ZnGTGYx0IrkQgcKTue+yTikOwpicqjG2E4oHqaUC1CFPQ3vw6vjrTb2iZVYmYPn7lrl6kVF3xqyFlXDY3sQa/JPb+Qur7jlc1AdWx0BvoRI6Less0O2gbnhko=",
                            "hTdANupB8nw10P6lqGls5FQdfgFHBXtNdFTbzkXFNKbwnMmF9vfl1GClbrc14xPVfxV28p44zCsotgS4gvwG6op0nh9mNsP+nXe1Ab8ubgiXfZDK/okZDieuUjcSzepetr/+4gmIT25LU7rRQFlXQ3krq+aPpEKvT+Uo7+guaqJN35128OvVW01bnWxfH3ig9psuzoa4O1tbCSfvkNMfbENSs/VzjPw/qrh7ZH71q3+8soqnMGJVpm3US7JXmvIVYNGcRFeI3Q53tzD0+L5tT7f0sggf4zdA7fxZTOxyjU2T8aJ+wP7j4wEtQC4A/MvfXpe5jLGvEiSS45CAgz/2XDgjjTCd288ho3D6P3C8lbZCjYhUhho9AuEcpGqx3+VmZB5MLWxkUqnhYxOOI8GbajBHijRAJNdy7Vn1sA=="]];
                            $result_val['FGStripCount'] = 1;
                            $result_val['FGContext'] = [["3ViquqVz85iF6NGF6/2TeTn2mZ/+a9pU5OwkX9YcYx/ogAWy5TEx29MhxnpVBa6uFhdcdeUWPrkbApQwencnR5Ou0wOUKRLW834pr5z+Z0zR1/LSatLr9h2N0OFlmIvQiXqWxrA840rhuW6pqCphpIdkjp3LvfCXakFybSr0K2JhjhtAHYg42SsvgHRa+L0xEW8KBPnxT1QaMr+Ar6HG/KHu+8bYrelPNI0V8kBma1rpxUOw1salT9SJLz/fohUACRPqBe+ZA/H5LfgkOmCigIPihpGZRYrURSh5ewQKScpLBrL1dw9B47V/QF9vTc6KCQPuh6dsrE8nyQLoWRzd3vHYdH3nLZFY4e1JHIAtmbHJghHFwBc/qav/kYmjKipcIWiCD/Oqe2IqPaCfEGkzWUBHmMauQwHwr6Kqgg==",
                            "fNMWMU5ZCxZhvYXaU3YGZlam7C7vbmG75MPq1opu7xRUGpf9zAJG9FiBpWt29T6ymp8NhstIgWBSEDSHdnWw2f4HM6QJQYjfDgFpwuVm2Hn93X8tNacoTqIk0UOS1LvoC2JEdkvgp5lR0Gb0ZffCME+d91hlQjBMclasrGurLz+en04seo7A9xATJONN9E7wUmDymR0CYIIfwFLXk4WpoYJwMscn3ftddI5ESog6DGZjdYPXpUHwOYlWSDiA1gqm/svuD70iLw9gm+6K4xtQpDk1NkE7WiZvJnL6Tl7Y6xLOD7/C8udWKVtMWLKG9znTMp5Qwv7ExGIyfzRIZcxr2WLPUzmtPBiIrsSCCv/d4gQUa7pQ6GG++g+dUbUjl6JAluzflALi29rxoFlmiN2BlZKc3cik6Q8Orj9N5t+19F8suzaH32huBaCc6RU=",
                            "Ue9o7e8vu5R1p3LpfioxAivDJp9pLP8SRuCBGXy/ED1F8X8SVj640Tt1qzJtYWdKseQ4okKI4bCzxxkQomcpyDQ0LEdjK/TWW5HdfDHyIpO5zysA/QclzybLFEK2qEdQPGpWH7+DC8oLwVSf0BfDmzgHz2bEl+sdV+OA6zgTLytdTGmxsI0zvrnPiJJO/YHGwDgr4BinVuBQSr5BZgHIAAHd/Kiwz/lWVu2L20oRJj07PteC+SY2H7DHMKSYPhrRFHB12lfHVcgwxnbAY6x0ECHnN3Cmh7SzG8k6RdxMNJH9XxeF//bqeMZGS4MivZTkLF1grAI+WIOnJsrqHF2a5mev1u1zdnyQjcdYqEY+7yn0Bkf2pvpn5at/GdF04xNW1Gg4bMWKwZqc2PukKCmpDVXUT8sX4FCrXe5q5k3p9YO2R3TEy3Wdqb/wznJlE87fUl051o1wkJUi8YC0",
                            "FVwGDGsHT52IaS5YPOE7At3tdKHtdppzRiuRFarcyFvGX/LJKF7BqczOmiNOgcRucy/6tARkojBmeMyPdxL+VrYk3qnHPLTreKBCPhZ3+qlfMQFKpngWpNln1SY6wfX5jBnyQJgiON5bOR1elU018RO98hFzw5zF5n4H/dLpxNvg1k+ISyqxtfbgK52sN8CLbdgqZKKJb1lQYEJq/mmlmnnrvzkAjjEOIcI73tzDyu5zx/pN5yKo/yzvLzK3tnimIaPWxULVWY7zkL5aoVFZQUIW+lRNy1YRlgtCGyG0HHLu49S4zsCeJbYYfGRhKhvAwWjZixv7dZEZGRzvwyI6gbSZ5JJmm7DfpyuN8BsLkrIZrt9ZrYfvMhrlf76j5TttGPqYK1O3e1ySpIbWCl3lrYFBTAgXW2aqRGdCoLY1LGxhRFcE/QiRFw0ysGs=",
                            "hLrQhOijw2JaFoQkmxkCv5wKnicfCmzvcifPCnLjlaySGF9IB9PZP3DL03n1m7KojwD0VSBdYInuoP43vGbMw/sAHv3NPqPXU7b31rbhs1YGjRbOwd3x1MFCJZzqHCogJ+7+BarhjhmfH5El0zHCGpxe+pu73DDgEL2V8M7pfKJ9YQH/4McUsYm6zLbiZWS3qyVYk/F+Y1RlN2T7qAJwbyfTxnh0NpTW2Pu0w8I2JxMpt4ksPULGfXUKNHxY9tYWEp8GhnMENSz/QUJ0JhGNL8v+IXpQ5CdzstEHwp6BNTTE6TINAJ+9dr1BfRIqkW/2Iko1zqgFmCKu+gKgul0SjwkHXMZXu7G3t/LGux9lMvXdgkNLF7+/vIju6LhUhZcvslgCyRHMgyw+uOuipFfKeAgiwFoKjVTjg9XmGQ=="]];
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
                                $pur_level = -1;
                                if($gameData->ReelPay > 0){
                                    $pur_level = 0;
                                }
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                                $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur_level);
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', ($betline /  $this->demon));
                                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 50);
                                $slotSettings->SetBet();
                                $allBet = ($betline /  $this->demon) * $lines;
                                $isBuyFreespin = false;
                                if($pur_level == 0){
                                    $allBet = $betline * 2344;
                                    $isBuyFreespin = true;
                                }
                                $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                                $_sum = $allBet / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent'], $isBuyFreespin);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '567' . substr($roundstr, 3, 7);
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
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                        // FreeSpin Balance add
                        $slotEvent['slotEvent'] = 'freespin';
                        $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                        $lines = 30;
                        $count = 0;
                        while($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                            $result_val = [];
                            $result_val['Type'] = 3;
                            $result_val['ID'] = 142;
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
            if($slotEvent == 'freespin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
            }else{
                $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, ($betline /  $this->demon) * $lines, $lines);
                $winType = $_spinSettings[0];
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $winType = 'bonus';
                }
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, ($betline /  $this->demon) * $lines, $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin'));
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
            if(isset($stack['SpecialAward']) && $stack['SpecialAward'] > 0){
                $stack['SpecialAward'] = $stack['SpecialAward'] / $originalbet * $betline;
            }
            $awardSpinTimes = 0;
            $currentSpinTimes = 0;
            if($slotEvent == 'freespin'){
                $awardSpinTimes = $stack['AwardSpinTimes'];    
                $currentSpinTimes = $stack['CurrentSpinTimes'];    
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
            $stack['Type'] = $result_val['Type'];
            $stack['ID'] = $result_val['ID'];
            $stack['Version'] = $result_val['Version'];
            $stack['ErrorCode'] = $result_val['ErrorCode'];
            $result_val = $stack;
            
            if($totalWin > 0){
                $slotSettings->SetBalance($totalWin / $this->demon);
                $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin / $this->demon);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
            }

            if($freespinNum > 0){
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
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes && $stack['AwardRound'] == $stack['CurrentRound']){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $isState = true;
                }
            }
            

            $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
            if($isState == true){
                $allBet = ($betline /  $this->demon) * $lines;
                if($slotEvent == 'freespin' && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                    $allBet = $betline * 2344;
                }
                $slotSettings->SaveLogReport(json_encode($gamelog), $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon, $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
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
                $allBet = $betline * 2344;
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
            if(isset($result_val['CurrentRound'])){
                $proof['fg_rounds']                 = $result_val['CurrentRound'];
            }else{
                $proof['fg_rounds']                 = 0;
            }
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
                $lineData['line_prize']         = $outWinLine['LinePrize'] /  $this->demon;
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
                $log['actionlist'][1]['amount']     = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon;
                $log['actionlist'][1]['eventtime']  = $currentTime;
                
                $log['detail']['wager']['order_time']   = $currentTime;
                $log['detail']['wager']['end_time']     = $currentTime;
                $log['detail']['wager']['total_win']    = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon;

                $proof['lock_position']         = $result_val['LockPos'];

                $sub_log = [];
                $sub_log['sub_no']              = $result_val['CurrentSpinTimes'];
                $sub_log['game_type']           = 50;
                $sub_log['rng']                 = $result_val['RngData'];
                $sub_log['multiple']            = $result_val['Multiple'];
                $sub_log['win']                 = $result_val['TotalWin'] /  $this->demon;
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
                $win_action['amount']           = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon;
                $win_action['eventtime']        = $currentTime;
                array_push($log['actionlist'], $win_action);

                $wager= [];
                $wager['seq_no']                = $result_val['GamePlaySerialNumber'];
                $wager['order_time']            = $currentTime;
                $wager['end_time']              = $currentTime;
                $wager['user_id']               = $slotSettings->playerId;
                $wager['game_id']               = '188';
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = $allBet;
                $wager['play_denom']            = 100;
                $wager['bet_multiple']          = $betline;
                $wager['rng']                   = $result_val['RngData'];
                $wager['multiple']              = $result_val['Multiple'];
                $wager['base_game_win']         = $result_val['TotalWin'] /  $this->demon;
                $wager['win_over_limit_lock']   = 0;
                $wager['game_type']             = 0;
                $wager['win_type']              = $result_val['WinType'];
                $wager['settle_type']           = 0;
                $wager['wager_type']            = 0;
                $wager['total_win']             = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon;
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

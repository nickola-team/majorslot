<?php 
namespace VanguardLTE\Games\MuayThaiCQ9
{

    use Dotenv\Loader\Value;

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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TriggerFree', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'PurLevel', -1);
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
                            $result_val['MaxBet'] = 1000;
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
                                "g" => "201",
                                "s" => "5.27.1.0",
                                "l" => "2.5.1.1",
                                "si" => "40"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["mT2KvhITEPcrQ1yevqnabHSgm0IeDYyvpYo4loW9C5ShzQ5CaxzCl8OKT082coDTUEsjvkFgfp6a1+NRDbohBL0N5J6JXjaLUDSSIRag1nXXDV7lAD+4tNtcgpk1Yg+s7X9y9bOZ2nlGK8oS0Z4mWJdBJBKcGmG6c7o8E4q4sl6njna7MJ+erVlfxDI=","R77hI2ya0dSi1iifel/xCJuzHfqBzr8d+yR0AkOvaNVMO/T0URWgYAzPJrmwRG7sTUr6fky5iqQ6XvZkgPkkRJ//q4IvJ3R/0btdwt7zYJjuRSc7Q1XWSzHb5SJSHyyIU1IvmDSiRMwEYaEZ49o17jKYymvMx1XvFBfhep5lIsLc1nwFb5NIc5QkBRLndmtcU2kt7JNVjKEMKa+3","I4OnWtHaKH5ZLDXnqBrrBVnsa47vpONSMig5ewznSqg/tOtl+Mf8BJikuZC/WEEUIVWR3A+ScgupOfl8JNaZqP1fScHpksNrsfPHLAbzlP1ndjRmF46C86EZ0BPHCYbwWo8YWVIc4gVQszzvciyQdbmIdHfSJQg1qqSilXEqZs+Og7kXmmwPs0ZB68Jm3K+nDeS8DCUEupuu8qA0","kZ1ywvoLZ5UUoLbqdumar1nDdSLw170JvpIDMsRdThFdN/3iDTmEyt0TCOPri6sE9sJQ3MCSoqlkNpqz3ALjZ8KTCjGBkdOEpB5c0SH/FQdkGgwdejWPlJJqYUZSlQ5mgTDs+R7eK5IW6eAD2zPctbde2LcwEZXreGchv6ho32VMBaYi2PjVbjeG22wt/cVhizUSYYs8LEZOFacPeSBIxc0v8UWl+F/VZDy7cA==","Mtzb6UAmI2hRKIEbviWc3AiqAi7Ha52bgVzCU/ozsDpb4nNJsndsQIjFfrmma0iHrt1jpSls9JexDj+9JzJGUyMTkOdZO7Lpwn2FdYMs0kWFwa6a2DqfQ2hMFRX+BO4TI9GxdkNSGA7d2sK3"]];
                            $result_val['FGStripCount'] = 5;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["62qppuYOiLLYBzfBIhPtP8BCuSt61zeWj9rEaTET3MY3hfK6PehLvmiD9QjI/k/vxZiKTueaGX9rEUmdYGQ8aeNjW4LLHWuYf+GzmJLjO2s4pJ342kktjVizXfqxj3KX6bzp36H4vU1BOwuSQimG3pPPKNgQz0fOzQCCmnl8/TXR2oFee1bIZ5Icu9KgJ7aruVcp0SLErl6Mtm4u4ujzbnT76JZRLzJ44Xp+Qw==","NQdIMdfF2SSaFtLRpOzUDurdm2CUSW+7yVf1xK7rOIt/xYQP//7mov8+Ka4ymwbkdS+H2f/jv0AKJrBT4ofAaaidNZCQ2lFbptiXwgi0Gfn8bcrEOoK/LHMkVGkwzah91Or6nMxWdC5nMQrghwHznKAJR2gxOn3OuGtGQcc5PyITwgiXK2yaIyx0rw3SvtAQUlXUGXXcn+Kuhw7k2CWH0BGROsf37VpNctRHQQ==","Cm4D8srLUp48LdV8x0WcYXJhc0J/9L22ql8WBuZ3cdUzMuaHJ8TZtCxHfwNHt9hmL89exSR0B3TP1a6i6v9+HKYn4sFsy+9zAO2spI0zqxs5N1pcjO9fIGae4+Hf+mnrtLKKklK09hCVSVkHsQPCjLgXIy37DPcDPhRjeaczPvaI1iUBVcI3IrNQW6PKvFOVe9HJK3nl1T6ExC33+VAO4fLbC++4Yf0wVfBNjQ==","CPrX4w1zAf39FJ4meTxOUU7BuZMyjDAD8eanRfuZUJoZ4OuPxxFWinAcxwgoPlBNHiOH75Vh7qYtAF5PsbFdBEzAdbjeYmy4FKOls3e6fzz06Z14UzF5jwfItvXZWvRI0OB7KUlUGyudbgofsC2cJzYji81vqgIF0+/P0uS0smzSCu13QQI+ZAKLZlH48RsWYEzppXHbj/cembrn3M0W29TTZ+pwOFiSnPZqKg==","26esCdlJZVftxxWYoGsDrVib6qMh/CfHr7J2c4azT223FAyFWr8e1shRG0U3yfMvO8sjDua2QLMrs8hiQ/CpBzqzkqt+qPWUE0NSuTkbXSmU2Zjjv3pWQKcps1uqIsQhLMFGYER3CxX6CynPRhDAipUDuzM0sp8wCwjfMvijqlSYizmSAPz44tnDGmRbW9SCr8YBJRw1FY952MleCrpWrTaNA3lNCyr+zpyJYWaOpA/Fx9BlG+7VjEYJKN8="],["bvtG7HbsBHUIOZqX7a+vfNgem2LKRmFZ2daiDo33IzUsuAoHUfZkXhfvQY3ehmNPQsM43AqkVR0x086dmR7ay2o7z+d1n8iFzQczsBWwMLARVA2e4+cvu8dn7VU36+VO59SdIAYa8GqbRekM91i531bKnbaKZsEZV/LYFLVArSfmWJx66R9FlOitd39KrgpiXOwH9gQoD8uXK1MMpkG6MPqbnuH947s45UcF+Q==","nKP0fDDEqCLygxyWNjzHTh+QcMwkqaoLGE2kXejIM+6G0pWLOzN469vOn/Yze9NGORbqWOgyNT6Hc1rzySddQbmwHCNS3dXhXFLUQS4QiC/7ZaF6lR4JRwAnTWN9N2vKgBMUj3PqIiukZdPgRpM+dPF9PUus9vVwairwEjXZ9/HCLqfmG3kMRZ9wSkolMVImfgEBttSYUTeOF8JlOvBUpWNyfpvYA1yHNuaMjw==","noPoYyn314ocnB29rGWLBz6qLV+K/ShgpCTgkLAqP3SQDvurgSHfOO7Sg93fGlacz3DRvZoADUT06JKditirJI1/rPn722f9hgAGvDW/7Vw4iDjSnXpljYeIv7pPmsb7izldE9dZDyOWGRhXpv0EdpDzEbMeSyLqXp9SXuxVRUE7mXWWbe0IrawbU73AXvEzFW+yBmCLlImZTFEINI04vGff7Xr5QjmnDwOQ3A==","yRT561MVI7Wi2gEiqQPQiKoFUQdz3DHKggB1FYIu35UFec6IzDmqKkeXJ/OgLOGMPxO9jMsvLhCwyJJPCye9S+ikDjTbr6/fcEpATD2KX/6fVzCYMKPNvf8s/swEKpUHyVR0lQe6hLFUF8Ks2277YfGb2mZqT9Zws1I3bNQ8RW0qulYz1LPkxtT4J5cKSILZDDsNZo0cyCazlQSzpS/u2cbZvGv0aUCSUqDWng==","zglojRoxQxIVJd3tLx3NhLn5wr3dwr+lolDtRE6TfYnJ/Q11NmEIKVl0iSrdcNHlg7qCDuhxVs38EemtoTEpvt2ENP7jPehC2SHqeu44ppBSPkBev673F+REB/0l6S38Cx0S7WmMd42CZPeDbOPeFWyKHdZCkte66bdX/wM6GA4PGLZN0SGOsRkPYfnHMhT/6hAmwvRKYk5gFIUCbvAMi3qjMUpJzA/+rsMiCMClcEHhDlpFWsyinnV035I="],["9iyOP0wuMCSW9cgRLJDSPZhkjpZ/mODvIK6YkpiZli25mTTtrzd8NGNhQmXAztDfPCtFKyYXR9naWaZUQl44iBjO/zYrvW9dqCNoYZak2Npw3S4W2TGTeupwBeRaCZElUbOkyB7hIkTt2e9VgqxOZmSuD8AGJOaCNKSVHi0g+YlBZAeZVQ0OkgmQdODHXHSJdQc7J/GDu8HZ0aJIx2TiEOs9fIV8lsZmV+v75A==","uF203MeDQWojmWfOtAyL3Gfj4NOz3v3C5kGJi9RdKt9z9bQ0fvwx5YYkYIymjRIWM5F8CissmpP+TySvVb1rYCWVbZHqwVpC34oSDyAwxfp6y3hxdw4l1MdM+tw34oZ6PkYGSAL0KsaboG/HUGbNBR02NN2TGFQmHcDckN/QU+6swVrMjKQuD17qOd2/teiyGrKTt+W49qUlZ4xuhqskGHrtKQUEW1IQhZxznw==","xg1IBx3q4PhOc1Nzu7zHmpNLWo1INhAhoMkKsg4hCB+vzmo+5snbs5Ee08acj7slGeyLTZWUq8/t/wNf8xIe2pWPCpnlC/jFfduZzp7pbojs37KZqZrYFHt8IlTqUaBpnoOLC7bVKU7wNvtVkJmq01rOzmDHyf3mi8NzAwhBmawP3q6jEsk8e1x2YADCsCf4I5Wnb8w006xwHOtM4A8tYXdsdB+k50mHIMXeyg==","QRLlHLzbFLEDmNQoHvv6rGj8SwuftlK11McnlC3uRRQ6cyfkeXGSG91nLGzIXU0j1rFyE1Ow2/1JdbI67TdX+6cnHq0bnCCbUsjM+VhScnBPfmLVcnMGcQW+xRISGNqIpYCZ/W54Kh5+qxBozwq5ZPNFiIVWn2GeDdy+AV4OI4xn8x6lUIlMKX4IZ/JMChl+EVNWn1fZ8p0bTov7EhzGF+qwQ+Mj2RbFaC+WIQ==","a7JpjhIu937Gq71M5l5ny+yVUvFxvHt9czsAd2/siisq6tjdLChQXCx5P8W/v4H6IAisHL83ynsV1G1KYL1SzY4yh0uCWEUC40XA2FIPKiXe9SAytdn4O5O7QzHXl3HB22mok70fCDvtyFMohsGYi/framcBz6lu0ykh8mS/PzEHfFz9o3+o9YOWelZjX48Yk5jdAo1k7OFZJa3c8fWh1NXXAJIGEcQyZSpEVeYqD+wpNHvCt+3paQNaPnA="],["oUPruqfgoBFDoPVvV9CZsGdhcEzHgbInj4KNWLPhdPDATCnYfBHfWpC++dYXhrD/9oPG6K4uAFPZVApXGBsd3Dz/gbuCtr+hkx7AcvcyaFw0EVjMVTTkisK/z5McQXiwMd3I1krI1C6R5YKtGdACXNXYKcnrbG3Su4pYn4I/8TjOVDTHG7UG4iG7i79qrmI+OSPylfSGl5/UNe5gt6c5Yri5BfPBNi0HRmL0RA==","7GE4dECaL58gnUcc1VGenAaoO+px7VyXKyXzq0xT+H+A/xL/8ARmdEJl5OTXvQLLZsnyQXpXfLvxnejY4CqLLCKOONZVnMM0h5NU26tBOETRQ+KuVFwepI0/mtkoQ7anQxKX8yomM2rDocXhrnwk60s7i+YuCv0bhKExNOrV7700+cG+RYy3YlIABB6Z2TFiAXpNZEJOqMn6+IxRFNrVZMzABTPfEtUhnzUzuw==","jYgeTLixBDyzzSaSeD3qEWe37vcOus54ALMAWAWWMB4h3noSlFn/+s/Lb4vRP/pX6Oc7DCfGJIHWPMidzS5bMHAjFQV6EhgSX+zo+ttZkn9JFT4j5DA5zEMKVDsvxN80ySm3aXJyi/amTD/geQkV20n7FlsY5QwsM3XNyk+4wwk4x1ieTUryKGKAKZ7nKTcHUFxwYCwOLJuDfXH9sC6uVOSWTzn8k90WRFk4nA==","huS5WOBUKwIzOigPmFaNiBn4a3qoU24ZZ4Gl2aK20L7r4wieaR0v/bqhrt6QVkbG12+6BZVDcjeoZApwX/ciXqwiblgqojpZnupQ8OQ8XhTr9gMx4gpD50tjxkBVbuZ37tBKm1GsDY2K8iQIpFhBrQw5H8eVmsUI8qefM2gYm1FtQQAnG3Gqdn1qzy466ySrc+FlbSAXIElru0WhtvdCXDqC4bqXtwIiQ5aSzw==","0ZJqov6f81VG9QtjCoutQMj+3DeS1mp3HWraKJtCQVQoGeRhtzpc4cpL7C+I55PV9Hx470Ms2lFi8J/hki1LST1jA2BxgHXKBILAoO2eDPiUT07WcNAmOzZB7FZk9c0uGdONk5wWEkmN5XK0Gy8DeReAkSrf3Ajss9P+/6MFP7ALwPnVmIp+WETF7PrmFBAzwPxHJHT0Sc1DVPh+AWE6YKKNbitL8HGOuHwxJLdg9jYD11zJP6kugUopZcc="],["9ECN8XrkPh9puUvmm0kxNcFWhilTebUwEi7y9vjwubCk596lTdMAUi6BOh+GfOYxDqYmZGtZxmtt/rOk9soUlVR9xBNH7xTAGWxdOOHymLsOqacVk/5KNneWpJX/2fkFilRBowCOF5nMTCV2sY4E9o9GLanH+UguhlhnzT74elwQnZEWP+mIpsQV3aCAoQ4MsfFMn6tjrLw8l49jXSM016O4dDdP0vhOwJsx6A==","D96ouNxgf7MJ8RzzcjBP/YyGROMdQc1uipkxbaKFvrNYocMljxFHYQ6g2ejLX00Pvpfpfc4fdlf6cwF8fsQFlxxSbrulfs9G9kIvTb7Z4AEEGxcsUpUUxzKISJ0h5AaU+l+GN1IuFsw85c7rDz+Hw/IL51ixt/z6ZTdAPeEMWv4EYbA4vFGP4P+xnSJSfppgdssCbdaxEiiD2pEEk3TXactS0P5fHUhP4kArHg==","luyQUF9RjNz0cBaPmw/ndBuV7pthFS+jPaj462GzFJ9GMWwDxlR7ULz9oyvMRFfy8LLC2qOzWKTONivJQ+KmKP0WyCCkoZM0PWL6XCcTRq4p6rbN22tuSfWnDidMCfi9RWfppKCIelM4yDLzuDlmv6GMkk5FqdqX0caXXK48xhHrhvx1bqzxtX7dC48VJh+Guw2RWtesjBGjQb8Qi48QgglEfxOoYJ720Xkvcg==","T0e1MAotQJkbhh42OUprrGco+BvIZmNFJ8SKmFFG0UrJMJ/y9KSHx7GK4RevFaiTA+wzwXrrvS0f44d5EcG2jlVG0nh3s40dQYXSziqSVYioa+toswzTYp7FiTAPt+EFRQyc6b/n6C7GwXm4Q6s1zrByBUat7rmn12n1FL6zmLmRozLrRv/iH9m6uj5x0gBp4WdcmmqxQ3ObgiWmG1hVqV3h/J217x+diLZJKQ==","es13BJhKf9qwvdf4VQpRF5C+mSxCSrXj5E3U7+fyVXr2K6eanXHUITgs6/m7h9Zv0kFFYdmLkyeIcadA0iUVsBo2s5dxZ/qf7tIqf4zXPT1ENPHLRc4f+JvFfJ+MSrmhifi1BjuFItp+bwhOy+9XfgnTPKQqMOfPM0JokDwbzDFHMR5mfcisvIJfuNfaHkYi67qKUzAvbwAWNNIiudy//zClY+wvd2Usrcr4weecTTl+Z4wrgyfko5z3HbI="]];
                        }else if($packet_id == 31 || $packet_id == 42){
                            $lines = 88;
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
                                $slotSettings->SetGameData($slotSettings->slotId . 'PurLevel', -1);
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
                                // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance() - $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'));
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
                                $result_val['Multiple'] = 0;                                
                                $result_val['GameExtraData'] = "";
                            }else{
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex', -1);
                               
                            }
                        }else if($packet_id == 44){
                            $slotEvent['slotEvent'] = 'bet';
                            $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                            if($slotSettings->GetGameData($slotSettings->slotId . 'PurLevel') == 0){
                                $lines = 88;
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet,$packet_id);
                                
                            }else{
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
                            }
                            
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
                            //$result_val['TotalWinAmt'] = $stack['TotalWinAmt'] / $originalbet * $betline;
                            $result_val['TotalWinAmt'] = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                            $result_val['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                            $result_val['NextModule'] = 20;
                        }else if($packet_id == 43){
                            $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            
                            $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                            $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            if($stack['NextModule'] == 40){
                                $slotSettings->SetGameData($slotSettings->slotId . 'PurLevel', 0);
                            }
                            

                            //$result_val['TotalWinAmt'] = $stack['TotalWinAmt'] / $originalbet * $betline;
                            $result_val['TotalWinAmt'] = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                            $result_val['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                            $result_val['NextModule'] = $stack['NextModule'];
                            $result_val['GameExtraData'] = "";
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex',-1);
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
                        $lines = 88;
                    if($slotSettings->GetGameData($slotSettings->slotId . 'PackID') == 44 || $slotSettings->GetGameData($slotSettings->slotId . 'PackID') == 45){
                        $pur_level = -1;
                        $tumbAndFreeStacks= $slotSettings->GetReelStrips('bet', ($betline /  $this->demon) * $lines, 0,$pur_level);
                        if($tumbAndFreeStacks == null){
                            $response = 'unlogged';
                            exit( $response );
                        }
                        $stack = $tumbAndFreeStacks[0];
                        $freespinNum = $stack['udcDataSet']['SelSpinTimes'][0];
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespinNum);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $tumbAndFreeStacks);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 3);
                        if(isset($stack['TotalWinAmt'])){
                            $stack['TotalWinAmt'] = $stack['TotalWinAmt'] / $originalbet * $betline;
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $stack['TotalWinAmt']);
                        }
                        if(isset($stack['ScatterPayFromBaseGame'])){
                            $stack['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                        }
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'PackID') == 31 && $slotSettings->GetGameData($slotSettings->slotId . 'TriggerFree')>0){
                        //$slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 10);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex',0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'PurLevel',-1);
                        $tumbAndFreeStacks= $slotSettings->GetReelStrips('bet', ($betline /  $this->demon) * $lines, $slotSettings->GetGameData($slotSettings->slotId . 'FreeIndex'),$slotSettings->GetGameData($slotSettings->slotId . 'PurLevel'));
                        $stack = $tumbAndFreeStacks[0];
                        //$freespinNum = $stack['udcDataSet']['SelSpinTimes'][0];
                        $freespinNum = 12;
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
                            $result_val['ID'] = 142;
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
            // $winType = 'bonus';
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);
            if($slotSettings->GetGameData($slotSettings->slotId . 'FreeIndex') > -1 || $slotSettings->GetGameData($slotSettings->slotId . 'PurLevel') > -1){
                $winType = 'bonus';
            }

            if($slotEvent == 'freespin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
                
            }else{
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, $betline * $lines,$slotSettings->GetGameData($slotSettings->slotId . 'FreeIndex'),$slotSettings->GetGameData($slotSettings->slotId . 'PurLevel'));
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
                //$stack['AccumlateWinAmt'] = $stack['AccumlateWinAmt'] / $originalbet * $betline;
                $stack['AccumlateWinAmt'] = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + ($totalWin);
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
                if($winType == 'bonus'){
                    $slotSettings->SetBank('bonus', -1 * $totalWin);   
                }else{
                    $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
                }
                //$slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + ($totalWin));
            }

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
                $result_val['Multiple'] = $stack['Multiple'];
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes){
                    if(($stack['AwardRound'] == $stack['CurrentRound']) && ($stack['RetriggerAddSpins'] == 0)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                        $isState = true;
                    }
                } 
            }
            
            
            if($packetID == 44 || $packetID == 45){
                
            }else{
                $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
                if($isState == true){
                    $slotSettings->SaveLogReport(json_encode($gamelog), $betline * $lines, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
                }
            }
            

            // if($slotEvent != 'freespin' && $freespinNum > 0){
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
            }else{
                $proof['extend_feature_by_game']    = [];
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
                $wager['game_id']               = 201;
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

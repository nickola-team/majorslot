<?php 
namespace VanguardLTE\Games\GodofWarMCQ9
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
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 12}],"msg": null}');
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
                            $result_val['FeedbackURL'] = null;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['FreeTicketList'] = null;
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = [
                                "g" => "127",
                                "s" => "5.27.1.0",
                                "l" => "2.5.1.1",
                                "si" => "33"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["MpucdFp1orRcaQp49mwpErp2nmOFfHc4hWjcu+0b2zUi9PDiVi79kSjiI4D0+0y1GW9msSws3hU9/w3YHmtZ830xdYPTlG7ppqbBKZtCVCQe0HyE4Z52bj2PIuJMDyV9JjS1tEjBSx/3QFuZJLKaqQDyIs2Yn13n8Y5TDqfYY6BoQ5KIzXwV+AfDvKY=","7scSuPpxoxriSRRAxMcq5jdEy3nXeYVxiQ8ARioM6FIfrU+8A3ZXigsGE43Q0+9xTHP7K7U7lQAX5WZZ/FicNlp45cu/cy57E+iNeOvF3d47JMWwAm6H0j8tcm0MEXz7/B8wHU38+qJseIUYR37+miw5wlrLy30+dEtSVPizcZU4QvO86EIsieTkvRuX4F5aLpXnOX3OizhFWFkK","6SDdF6O7Y15TNciEzKTnZ8T8qQwEqF6CWKbP0y3wq7kGCcfoIQCspICfxCESSuNjfTLmn8T0xh9WAqtUh82l3CF+7qr3hhY7X4mMW92sbVbL0UmE88H4O009ewl+p+75FJOVvnk/s68NlOOBcA3bdhTqOSTo/a0jHkjJF0wfTKZbdhFkYO/vwjjMvzCUqTVo6LeY7I5rjRjiZAZF","ER4jFmmsar654lrcZOEZTXuhmqh2YvoJKOw2MhSjQxxipaJmF6NtllNdcAQa14zxN1u0qEyFXVjSy4umk4pkObmMSDoAiybMIPU477Yh5l7PzTOEIgp6qz0ZujmePUcJLk1Ezb3ISiEm88/R5J/3xv3sD0AmJAFZTs/cSxma8IH8wfjBFLnPp8Q3szE45sJY8J5+6bYgwBzvXN70kdS+pGblcaeCUGqhEWHjDA==","9rGsotBHrYUfBHOwhIJMPtVj1SzU7Ylfa/vwpWA2l5syD4BBrp5elnSEouQtp4yYF5kQODwvvZiDmg47+ksEH/Yc92Apb2kMHNm4WDA/YSvCIlZLzR5QwVIRoHpslBv21ZIT5hSYLNiCaU88"]];
                            $result_val['FGStripCount'] = 5;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["w0aiX7SeInS8bVoTmHz9c5RemK3W7fA3d93hIFlf+OEfCoIiIQ4Ap82bfuRPsmm6tNtMmvWujTy6Ft2yXELi+LlVweyLtE+2tkyiZHMeOiTpTS+dVB8ZcTOBDPsozgqQBBIk6a0hyj3LSXS0Nnb9eKB3zB/9J0ly71QQzZWoO5hAv5LS2uYyTCrbMaePFaoucs6X6JdNliry/XpKWViLCE+k5nxSFKHqWDCoJA==","rpTsTqYnzcn1Un30l3PNGyY5jCHccfD7Q8s6aidRmtf/yTnFR4ybbobzVjm4l8ScE1524dQMmiELzghLpSAxDaBggzaYKtD96pKWIRlZc3hn3+HN2HqQLqE0op5vG98LJ+JnMZhfzOynihecpp4YS+rYFxTvYlRZsEGo8gQ6P1q61jassS2AKueznK2XDP0cYbakgtqQ2kSTKqsoirvWcau+xnNJQfcnfp+Ohw==","ncLJmGxmiJdLXye4A0+3e4CuWSPnhYvDxvbmfW3qRW/Xx5DzIpP3Aj5/RSFVsSVFqDhOen+SIOIVe1MXY3mJRdwkBZSwyG0caP7UJcX4P9mOBXEx98KiujzO8gj/N5ulbUeTU9vjx49Oo0Gh9AWV8rboq0tIEMB5aqweJh7clW3GW79syZfQPMSOaxV0u/YFrWS7rsMKZVvyZ7XAhT6T9yiQZUsAuRtQo7aMoQ==","iIb3dXwX80r2muzdyzTZTcaTmp+0NY5wUjnfAWI2zB7gKagQRO/VbdsVpz6gW/0CNIkk+2n8EUxAqcT9gYW3Wn+2KPpaRjhETyX2godKV7WwC3aPlYO/4ivOyFD8eA6wHbRVgsULAMCTxaoT+Fn/FALdNoXoVGW6KTn/KPmOqJagsOWfDoEcylTXOj17mjFil7kBIzQL1o2nQ6hkKiFFPvxq9sfEZMXaystQzA==","Az0rLA9QQzJp6E7pfbtAYJT3qOgUoe/Lyt6IxbHKxV/uoYr8aYL0neULAdLieei4gHWy2UihI+YQ6S9g+D+ytlWAH4ZQM4RrjN6s2XuHtZgU2WygnMyN62lh3ALaJ9StnxNS7xcRjodnrWcHWgyabwkHcQdj6Wgp3I9STnkmL812yIRpAW8IXluPclvOfDfG+btkxQeAJO11WWR1U03uYZwiKKqDvWOLVALjQjkuGZK4/n/ZWsYdlXW/+QQ="],["N1KQFClGxOYPe0Wr3BazPUmYWvQnSalb8GF1XJMiVqgkiMbfRku2P8/vQnsZBRVGkyr2wDRlQofkOdJjwsdsN1pZSsW+ejT897t7QRjybOliHiEVCB2v0jw3IC2xJdaLzTg/S6tU0fwgyt65OJwt15sQ8cWjuQillqMWz75hgHAcszfQ6Qo6rlOImuLy5Eh4uHKMBsppSPTcUYkG1OaKfzCZwrN1nKF66Nm91Q==","TBE9ykaXYNQYJzAyMI81z4qDNAd4AwGfCS8tJwiy5hR9GXaY3pOltf/rvpubgS61YsrLj3X2MHaO0eqpsbfGvIXn24eqqc7nIcHf38jtFizFQOJaWPrCOVdtYVwfWRODrkZliUul6a3CQPRtmKUzV6y0R8MFB+fMXZdEjUw6Tvo5bP55c7udK2dK1TGvuB6HDtNlxcKu/XeIJj87DPSsyuqqr8u8mO2WX0Yf2g==","GTHHuhOGXLzFSNUFnafDqE3FnMKEINgWwiqRnUnlQ8UhBDYp6APA9M0pSkyTNiLQ+T9UBbgpjNbR8keagoosG4BQfi2tr8Y9PxbdAbAJy/eOQsXp468MKuCtOLJOXvHQF6O26azfyWC/LluVoQLZS+ybYtKYzIZezlqhezg7bwJZcAleH9nOncxD6KN/WW+bpwtp62ACSQyOq8j0P5tLFBxFYHGqk2Dw1rZj9Q==","uGWgllsBznp07cWru2GNWWu53MWD5f3bC2a7slVwJZvMM4/qxkfzEVNp4pvOQHyI5YwBCCfybz16iXOkL16lDKvN53xhSIBGAgiq70tLABXbE5E6IJzak69KVFJxY2jvdv9H1+CNfAZ3IiUGnaM5JxAf/872/FqftCDVBS5VaWkg04d3x+Vgxof1C2A+EY8MjwMLXFcI4Ke4NZYiBvRXdXJHG4rLs07T43OnOg==","7UTTAwazRKPLUzs7dDWsWdTI9qm2jCVIowIyX8Go2HDbQWa9pKpr5xR0+wtSWrTCE5TazkU25j5JGtV1/dvua1JNwkaOf3BYWoVV8voW+fCabVx55zShVZqJLOFyrjKNcwNCii6IiNLpCfbBatKjUMG4yv/8LHoB0wkCnu2mQm0Kmqj4kdK8oc+a14iKZdx7RiNRCG7TSiaqXJHcuzWwC6kvD+FndUfUBzpGhodNEbli31VmaMj5FdDUiBA="],["S8AfvgHpS4P5pTqSjzKmRgww6KW9rYurte+pk3xrKjJrxsLH5vnaPY1J8TdtHvNgH+vPZpUdXNP1EgoEK10ZbmSniXfORh7UtoK3xsl+gVOyaTZ5U+Pn4b6imsJoTaSHHHpiNk8wk2kZ2fPaS60E321XZex904UuVwgZRRTZmKkfcj9b8f7lU3mHv7gVbaaYhgZK6+bdWwpgdMFSNn+pAx8Lk5nFBW8IWep3tQ==","DnBY6pZR3yHGUzOJG/UPtre0bUya/pFRDRu4zKYqxlCgHYhIMLzr0WoPq8Ug+jj4kxPGhxf3vdZ5V7/qNMfxP4rXTh8BzXkAkGfqZWmsI87lmS8QqazhboPwDl/QrObWkpcobQAcn4Tvk7760cK3jky7jrV9ERzm3OdNftSqCY0lajkyb7CsbsxuF67ahwjPCVvAAl6vVyrFABiSC8hFuy8YTR+iDMW3XxowZg==","pMr6WzDpwhq8RzaGy/n5z6PjluPWEDsrw5Nn5cL3e2gL1GaiwdQpSChuK2CikXDEkM4NpNxC3Gv7sjhfMC1gt3g45kZxBcK5kEfsMokTD9+JTSZcK9TnFj62y9Cwr4pYakSX+FQ6KDxL1yXjFj1YWfm5hla/xOT6Bev2vZeY3Jldxmg7dg9aBCVnKCbpC/LQuGUABBEeJn9GoU097ABJAX2weUc7t6+KCcxtSw==","DMXfopnlJXPoDgEBpHeIf23vOoMtY9N9xSjmML76GZvqZN9eLsGRnAFyYqmPpiTdUwqQdyu9L4ka9MC4dLQMNqAB55XHE9BvmZClJQiqjFtAA15udPCou51UuaKAowKoG/SsNmOUOCqsKl9cwKpwFRtHdwf41JpD8sGiGsF6GF4tYxI+YV/SnFDM1EqI8+DJLvkRBfKmkxrP1ovwhZkTFeigZcvYeqQEw5Z/Wg==","6YikDjBjHft4LMArK/dAf66aRGDoWVFwWj2aPpVg26rCro8RqUEFEPEY2/T/PChiM1wpMBXvOSqiGaHQosvRuiWwton+hDl1Ss5Ws2VlLaXfM/noPEIat9o7DCp9n+vdw1jMfEJFndFS34jEP4FOoHVyEwPJU7GkkG9CFJW5XLbfx3R09q6qsmetjYQ1cQJOs3t/Q1Mov0NcgBlafBfoKHItZnuNgJpBPEPv+VPE3pITc0YxWn+XkrqQ4KY="],["sVFZpHH3otZ4PoFt0PVZsX9o+/GCqAYsmgM/DO8g9Mymo04F3gE5lIMl/+b1lY2DqE5tQEYb72fVj7NSEWNupapX0OgrxWqXxZUcoSSOq8AbbAlV/Whf9m2hFkgKxGXB8UGDzlLK0OeUtpQyIKWLZZQVx1c09j07iWdPNvR2G7PE0lL1AHXBZGhyuaJ5jDdW5R7DrqM3mcmqBqP0oK+QCl5Vjkrax+BG2dbDFQ==","NXPOKOH8xt9Wm0pP6Wzc9ohPt8LisVi1GuIqHU0xsD47l5mmOSOq8zwY+tf1TfnQEoLadovYKvFadf3gu7LzD47u8F1NkqfnsCCbgchNXb2pt702K/G6oAeI+lZPpCxJhaTh9LDFhbZPVoPmusTrBIEwsRu1dcPvHpxnWQdbodhIO/zV5ns90wfHyrJ95vUAMedHVcOOOJPOd1YZ6qxASRUtic4pYYZJSgEzTg==","Aqu9YlHGdXZkKo0fGJP6fSoaTGp0SaoByXO0SEgobp4aYDJLbV2kWPNTX1CSqheeQBT8qOgaoHi2Nj1P0AsufdkpM7GmaR727TCcJ/tug653PpH/V+RdMQCiK+gaJC8bFs3rdBIo1wWXcQdkK/SRH9zJDOBUEzA6dTGdaf87zrKkOETSgOIihL4xdWx03qu6n/DbX9ijxEdkvopnbUBgn21HMNIpK/cyyizCAA==","zwZJDIhBiIai4Q0x5J/T51+7GhX792O6xvF62Jj/neBPh9elJUWnYCltO/Syq5oDnO8rR40J6878Hwo3ATjFqKqHBiU8AiuBiifgjHvL0/0euO7hHV4WilDOtxVQjnKVuoiMI4Tnz2jNMNs/Ee3SAi4quhD7IBk7Ph0QJZdDTNEAxQdmU0A6fDf8mlLLTictxm8rLGKE6qYK0h6gUSlgw7+lKf93UGwA8nuJ7A==","NwK79LwjOrDVBNTISjsMRLwsw5zDur+hGxlh8aj+wiNWt61jPqzS2mhmSwnJsTVUH0oaZlXc+mwk9d1AvUGx/Ni7n5ts5TrX8JRJ6v74XqSy0EbvASILIqaMAP2eWMF0BTRNKGwmGJXrPGEvdVKI8rNpFc73CwDdW637kg7sNVdBpGt4Vvt4mEWiUA0oe6AQIZrdFq2dU+8cxKicGzlTT5d3Wcucb+JPehaOBCZfTCw6VKndIXPllLqhSHM="],["GTezF8z8OqgkQc7AGhT+D68OQc3V2vyvG98ZaDP0Br2ptC1yETwaNlvVmJz1oCvDjZPQOEMm9ATrz5/c79UxwmF1JF5c/tmmDnZB8N7HZdMF5vuYUlY9nInLsqcVlt56SXekBt78mKIORciv60v/9Yeky7IfVMxJjIsucXgTo9WhFu1CkPRzFD3NjQgxmjgOjG+4oLdG+hRk3Q2clAsFycBuDxkohLOJ9SkI6A==","rHY9SHvyCuB7gy5gaW1a4suN+tgNrsUwdtfZNA5sOlpixChWttZamU7smT6yx9BgLAD848fOA4vYIu8+E6eq9h2ucoo6lY9HpeiwlXUttKdUWK/sXRD8PVT9XxgSMfejapMZT+zx6AsE9ZiYgipCuHkudQRi9qIIE8P9QNHbiQJiKGxS931+Us7udS+2KNovmK8Jg21ZmdwoYQdvqcLOMekortTNbkMSdWwGjA==","B5i3bwqpAwIbZCxcji5GhhhS6y4dcFCYohl/aw8Wn7aB303y14/ctvlnwf6pUGsia94HzW2TowrR3mPesc3qaj2H+iNDrceRTk91U3bIZUz6gb19aco7rvsbdj6x+j2GcrC0mdHLtu73Drk2eL5NprWoFoJ9T6ACPCmU1BZZq7r07/QdizGi7Re1zTeuib1X3ynQt1ic9+yFvZskW0PvVN4+qM+WUco9ug6mIA==","MXnhQWgCbn7ulQaXpJkiv6KadIeaUOgtXEGZxT5FmHEoicr4E+YZdJI2SgiJH/6K/229mK7HzNz2nubXSQlV5eHatm7jBbmO86L14jvMCEXQDdXcEfxpAPk/lTisqPYqbxYMvJrhHx9sEL+vZuZWClFoJXlwVQAKG4CyFy8hhQ2wIrs9HYMqM8qgYdQEHdIDl6QW7YBnYw+312LxW5LNkzRoXeVi237EcpRfpw==","yKZuOfAyhVKeF4o6XsWksR6zcZLUWlq64DF2p8tw/prqCKQtCM9oRDeGdR/L28wMtXsdm7txvj1lSiLi9EMOQDQDV2yXOh71bjxCUlAaOfPyTHyDMz0rDydptip3pvSthC/R4zuVr/DDP1aLrwiecFB6a8n9M2QV5NawpO0Axzdl2I1bKMvRfNllKPDAVfhKV600cRMjhcnd1nNCoUZsY8c9+ct9FsLqToHdxWSg9HAn74mriyYuVHrkVOo="]];
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
                                $roundstr = '690' . substr($roundstr, 3, 9);
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
                            $result_val['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                            $result_val['TotalWinAmt'] = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                            //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $result_val['TotalWinAmt']);
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
            //$winType = 'bonus';
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
                $freespinNum = 20;
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
                $wager['game_id']               = 127;
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

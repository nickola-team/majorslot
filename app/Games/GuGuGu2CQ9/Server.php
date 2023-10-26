<?php 
namespace VanguardLTE\Games\GuGuGu2CQ9
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
            $originalbet = 5;

            $slotSettings->SetBet();  
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 7}],"msg": null}');
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
                            $result_val['MaxBet'] = 3000;
                            $result_val['MaxLine'] = 15;
                            $result_val['WinLimitLock'] = 300000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
                            $result_val['ExtendFeatureByGame'] = null;
                            $result_val['ExtendFeatureByGame2'] = null;
                            $result_val['IsReelPayType'] = true;
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
                                "g" => "58",
                                "s" => "5.27.1.0",
                                "l" => "2.5.2.5",
                                "si" => "36"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 3;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["cZU8qIPVPw4B92eMlbTj3qS2Xgz4HWFXiHQyfEdyEKDPKasu7mYvczdkqnfT4ChIuqpR3XBmv1hULqFfH2DSCQcQb2+m9WeaRhMyDTQgSJ6HfEPoSA+Lf+tnN/eWs0dadgfBpXTRkZOe/uOv5G6g6qjuyUANFCwA90JL5Q==","uIgSvgEQrMr12rmPXDIQRO+BxiD5N9NG4tPLQ7PLdN546srFuAV51RGd91uj+bx2jhnaV0QOVyckIYQ6c1xepLF/mtAPSnBaaO8g03XIJTBj+98oopctCUduEqEGh5XK7dCvSRHf5NSLp9bvB3/ABhh+NFN5mTWb2mY1Hw==","DE8XywkgYamvklAtewRfII9BMJ8AA50lFVDV75MCy76Alx4cTtngh75uYezQ1sLnBypZjjIKIAihFnctGnApVKC+JkSsMIArIG67KucE0RNuwNGCV5vGa+BpBOVnyA3cGYq1sO1soeMS3IrJBShGKHd4TL9oEGltNXJQdUzY+NwNMM5bnNFCxv2bXr8=","erJXLPObtUlqYU6JFXtHObljRMzxzJdqBuiBzGaOPou8woiPRHf4gkvWGLz/Az8fBoHaFzqJ2QbDVneMqgfuj4WOpPQNSRApCajQYj3bw4+CpOqK89BN1rQLFQ+xMWu021CA9epoXudh3Vtj+oZW4ogVJ02dkP0NWduJSJDM/4SwsdBdJRDhFPZEK0dEwijW0dzWsAx3Su7EsD7k6W3+M1qnInrBnOvs+LGS3tChY4hVHOMT3pIz6wLO0LKzv+ORnhlS1oGGtj/Ii8u4","6TCsqTyCEPnhTqBidmLvsuwAQyGOT7x7ybb5xfv1zttuJ6IHWcEioPu1LcBapm5hksbVAwhGk5eof/Z41fRRw77hoXVaLwYaj/NTVLK9UsmHmlL8BPahXg0rgot6Bi92+r9L1+lScJBeuBgSy1o1oftlaKFlIDwSO0ctd1XhQBaXaS1llJC66GHm9eu7q6dzZmyL2LyTmeqasm1b6Tn8BkApAOb8gCCiDHoP9QaExTSMr58ITVsYQ/h1W2DsmtiruqZIty0fMeLc7hE7fclvt/vHQY/YkZoPGIXdsw=="],["D39QE7r2umFjAkzaX1zEXy+kPx0SCv8ScHiWYQ3srEGbtGMNZQu6vw9RKoMcQoVD+o2dJ0qBlW3Bz3Cylnb7wP0VA83mb+VJhnvdzNtvYtJtNNICCIYS3m57X9ZOq10Ovujm9AGGJqvQj+hcqqzEHoj5B9zOY0pMTu7mAg==","YtbIzq2Av8teuMvdvL2ThrVkqZ5AMBglzGmrHsfpzyJ5O8/1iQZuV2PgCLRdYkvVzEXYive5fhJoELjKxxXY/3u0WdY6ovkmWkH9AkhDqcbVNCPGTvJxl4hvY0B61fvwNwWbDh8h5J5cdYT+zxi8Lr74TJROmtxP7bQ7KA==","YXfbw2I2qRsytV9QJjE1VpJhyKVKK9PfENeARM4uNwnm95Qsp3QjuShsBhid6W2BUWh2T1xgKXjR6uzUBfOtSC/0lm6qN/CfRnNSX/CxKt/+9GRhXYSKDcDbbsjeY3krMg54DwIhfG162ab3mvFu/uwzM868YS5M140IPg==","BL7hxJpF4dDu4g4dC3TVWzR4v+FvlhuNH+tRPMizomO9qNBTp6bmVfnEcugQFTrmTWt96RQGow9w9TCEe6TPcUsaSOExtfkaC5IBQ/9oH2KzYocyPy/FKoeLpXOD1SZxdcqqZIMlE7iXqdqmTQ+s+CHk5/5eyZPWxUBskmgZALQPjOdrRtKHz4+R/KysVGTWbUPvgcutPYa8wwNCZ+wZICEGRHaXodHku9hT1yv6BJNLoP+6HrR5VXKMvZpVPoUrIXQZhMI1fwN9+zXZ","cEtKb4O1fPK8u8VekBTg/6Mu+S3BWztV6x+OrekVsdrR9j7pemWXjr4406MyHJmbKZ0C8KEr5GEpOnSUzrwOwNy9W3qZKF0LPZO+ojFQE4drQ9/9Qaexc9oIJKs44WFij0ou0JlznewIneKmoRca7TlaXS0QRWecPlnMctr36akMT02froV7RPZ6koQzLqqB5sacJFXomWshiyoXr0itHLPxOjnrL4G22w0jIUUt2Rkm0DyY7vlY9e7GatrUsZWXwvG0AWtKfcpDQLPN0TPPZ81Jr5cHxc1avTcxMg=="],["dNeLTrGV359o6c5vEMR5IVC3va1Fy8FF/iLqUR9pyN1WZH/rxz7k/Fz/j+jMF6w41qscXSZDIXi1i9Q3VhyrZFaw8MgrHvqhru7TEe7QHhpp63N9/j4VzbcdRiHf+0EmMGxmrn1S3p+CWTA0oa/QzO9/6ImnixsYXM+l+g==","ahcUabq8I7nTxqxoWlZ0FRaCfnTMh4eqG5b5HuXFsQTOr74MyEUHwjsqIvXh5lyS7bNJDo+8fRLBsRsv8U6v+xG09II+QSLMH/NbEWFfKPW4PxDXDHVHkV6u9HVx16aEHK2eerP31Nt7VPUINy/PAACBAo1xUJcEZt6VrA==","UuohhQyoCc7OvheYc0sRQNrAUWIieskgJXnWSE62PnQt+Yf7ZJ5gG8d19EYQyJl2VK/neuWKuSLZ/FT/nIBcGuIp+I54km9HOUG+qAy32iCo503PQyP7JqpUDrbT7ugdnSq7IYM4I03OgI3A","pHWm6PfxHORL5EmF8b2jhbxOpavBkZ+86a4kyVPp0FPiAizpdNxZL++tJjU2wbrkWe0CRTvHPFVUgyY4+Y5VnqN0u005MEbdHWEZMWiee07Nta156nsed/z6wBeQSoUJMkx4koRqLClapndXuNmRNNMsDdXiPTgPzbUNcJXdbPoGn+Ckt2xYGMNEg/1RSniU4bdRG/O+Po5yOjvdFLW79fdwd7Sgaxi6+Ey8tQia0m9wmMXXEhm4Bb3/lED1ljrZk4oRXi6Asp/7L2fE","tzvAi32rpFzpML8O5FSKyeY8qY3R31fxK++2Mj6aPABc8g/u7Ms4rD60Z7F37oqNAzeEXQeG22Pi56umLvRwguApT1kFe/8b1VlfCstm/lUP7TRofN4GYyLvzy+8SpSPqKAfDRp+Jkp9bkvml0GmnytahNlVAVt2uOh7YG0UPlH3UaciVgAKBtmaRYC6PVV+oVgP3+XMDM+M2NAQsJIHX+l/0W6I5nxhsYlTx9S8z9zOTvyxqc/4OBPpm3l+plcfeJIKo7mams1V0dNwXJdtFD9e5Dd37T3pHSjxTg=="]];
                            $result_val['FGStripCount'] = 3;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["oI9kmoHAv29xeuHhvJaTy2i5cRSYtJjWIWRjWYAOK/EsUqvTuDwplnaeWf8TDB9KnsJAdWyZzy6ayPK/myxj9Em7+sdpx3U9yUNL4erIfSBALl59xuz89VmfncQdCwcxlfBERE6L+2IwG+jUm7G1DyuaudUF6Z4m/0ujuKiqX7aQ9f8hevbjVGUp4Tp6Pq8cxgbOwSfQrVP5zEEQvhtz8J1s152vlYCTlRdYfA==","v5cFtL2tHMi3NvPTCQZbjv0S9O+bmWcs08+bbYxBj3nXNgYRnEPQiU523KmwQe8y/j7zmNIqq3jhLPHTOjqcjo+TwYNpNwr9WOd5bxSvbYFJMtbHheMeqzM94VJxRm5og3jWgxzqlv3eq+K4rVj483cMY43+BHzM0ZoCXCtLuhggTPUPNuimTJ3Y2w98eqopdoOpe5yuDqstaoXVzP2g34dVepDza96QP5PzvQ==","D3GsmA8uGW2FEC0JiCTCO3emvqJeQzusCjSdLMW3I4YuO0MuhPtju3HBZmSJaNLmTYJDGhzI0H6LksgfEmtPQ65Uam40QtYFNbe5qKtwGC3rPSUMKbde0R/4dDmDnYFQ1X4Gf0XtKHCFQYW5UD+2Q0F6QUqXKJt/CZK+wrTh3Tf3MRaUU/k9NTuRZrXbY58AgvjirGGt4bG80rWclJCoPbXAS+ocWm9UNMMC5V5E1UZDoYcd2rlLwugY9Ig=","pmNEeKEuXHKSu0gPkkODX0d+FJXx2YlGNst0WiRaJj9gTvfYZfF14RtEbO4lGNb9+F+weN4an1p63dyk/VmEoCsQBjQfAkZyeJfNZ4TRCyyTzMx2otjaugAHGWBBlhVjHgCxHp3g926iSLOnSPaZahzNLTW4KqkztS/H3WRo6Df9YlUNbM7Tm3dX8pjgwx5TT8zTo0vf+AmUWP++9mHTYeprHyFsXTkJynJU6mpjoLpDYg8ixJqzmgqQaSY=","Jo5gzBKKT6Rh11sRA+wINapU0DUrlxv0RMNzgeBvP2l7fQ96dIaQ4VqQpsQeeP6IVxbjvhSNCDa2tS00g7xwjMTv2ROwKNMr3iZYE+7Xj+PPWVLsP02MeBfGiKRBc8A4lmRjdO9NSBJHFD2xbTV03CwYZksrl9obsagBUidEes2MOAbfMDfOVz3vkDF3YUQH5Mp7usElU5E/fGeMMdI51oWLCr0H0+ssZqAA5Ywgdzk1y7ftS+QJZjYW9OI="],["epoNuyjgnsaAfWsdK6Fofl1JdiOJYdr5WZV0tKRrSoRvjS7fvAIVKN9pduwpLROf5LwTQ/NE0nsW+F1ZXXPZROZlEJmM01jRECC/ECM2nnaCtGGYqYP3sOu22bIhxjuo/mmvsB5bQqV1M7bCu09OWjwhOWgstvAskogZGLH1T9SlvmnoSk/dNRCYvNWmcyUGc7cin61LHETybuyj","Pj3MuZnrfAejnnwvv95X6Ju7KXSNJbsQJujbBGcxXXFubA3TcLAkfsrEJIHOKnwupGHulV4cZ5szodTDoWSwTnyI3akVJlGJxADSnhqo7xZdLBjdIK169ReEwx/58N907ga4DUsV5/bv7kFdRfqqRL2Wp64wltqQSddB5SEniba8XwL9xLWdpx7NlcYw5f+zcVtZLCSrThKiabZoi/JNRZXV6l/npj3E+HQSxw==","fzfQ812FhvT2jvpXOWUrQQ5uUvWtuTcOOVpyXUS5aGd976Mwv4yG1qPwY85y0v7L9Iiu66MKBQg+0EsfV9is53Lpv9kKC9om93tOf9aithPo3JlSwC/7I9D+4YxMpRqrZk6qs39Ta9m6knT2Eu+EHRL3LzRGtXFpAgWNca4eX3Nv3DosXCJQ7Azrgonvj8JIpvPNvxPdMzzMD+OuW9atIr9COt4NY6g8sOcT3g==","9qAGTPBqoJsXuPTWzoPf0s5zTdjg2lIWh2pwuB+K6y79NCr9Hf9fvW9CB0jiQ4kSJTT7iVlFBHQr+Q7xXjOFT6PhC0KnkBqqW2ErmSCq3x0m8qBRGGUI2+9E5pu+tH0uSV2xplVupMI5rSU8kusGMUZfCj4dY0JhoycXu9RN6OhBc4pkaFotHFG5zhz0FWQ+p6KmpdZE0MRcGO5TQwJbOA9dIpjtRfW+RWflPQ==","LHhxJHbwXcKC224a5bs9Bq+SHJGY2VjUeV3mVDjFeQFNUb5zts7W0agopBZpHbuhnh13NkNbVHwggp73jxBVK60Pg+xX2W9Ws7thVmNhAjJbMeJdwCUzmiNCZvrquLozhfqeMK1MHNTHxOkaSS0sfWwRP2uuwEB/RWdI8VHGn99ep6Nej8zlxfOy6OnNxJKdRY9pyqwvKYqbs77ls6FyQijo8lH3UINM2dqr/w5ciYECtetrZYbiGB/e8CM="],["H0PqwKksvsCJ7hUoCZ1tNtDj3mIb91OtD82swlmU+xAzLoTDWJLtJPZBIals5vqq5vYDTAmUuVyGSI5gigVH8RsEsARh6Z2PlODTpZzPTjm7Y4+7j7OF+bj8CGDFeiMPo0V3o8Uga3zRYSuVnU5h4gbghpNKRcpY2up838cA1No3GH6miPuHgI2yeX4ZCsw3GPyN2acIFdzvVUN3","2v6KiEMolnzSFi6Ag2d6Y1Jc2cq4f8PKf11xs3op6AwPpNXgnwcm+JXjGaexRDRFcYd2qN4K282ytc9uPOfvaINQikMGgxDMNMMUd4rmPL057KfTTGFkX2xcKgiH2Djg3WpFqpDVxCA1I4Xh/tUxapoESbddAd522bCgiIrkli4G7jowKD0Murrd9cQOaVeLsm3iY1A5wUAbyUn7fV7doRg74Ys9gAfJyJqA+Q==","RtSnXzk0MnTxw1dlMpLW1bceF7TJWMQvNdAJbSkNd8GkFapqjoTgZs5RSSl3qEvTGMkLlDKjLwVA4N0SS3lMJDBru9SzoNi0foeRU/28d95tiKyMXq/gdIovyOqerE9HwyoV9wlXF3OnStkuLN0Wul7AHBFBsAM13EV8EzNAdncFFVEWa4PgwzBaSnSj8cDPlxoDpgB7QItZoA93lBIzknsMixBZwiBnhjllJA==","USWpaRiL8rhxF2iwQ1WpwR2guCOIZI3JZ206WWZHzBnftst7SsUfjw11IAhgEc2cJ/ve8I2k9QZF3le6e0G2tdOSMPPfY1uQnaL17G9IsdURbpVQBBFRXLtOeESPISy8Ip949kzgwKAkiT/K49SUjh2u/Jk/gdlw6EBIdTFJe8SSmkokBfEClTPjKC7aUYj8+nzpNDNTmCZbYZw1hT+LG3Y9xcrHX3NQjWZbWw==","dH5KBrNtJGLbB3UJeCUAnQ/KXTZ6nyP4JifH9x4SFRfSqiN6+B9QuyiYJ14SxjZyP916LOyH0tenJRDFU0oyhdRDbDs5+MprWe01VMbR9lmjmqeJ1tmCh27cG0oHfW5twX6bDraWROAh7XWyw7zSMb4ne/dbh8JwCrtVJXQCTJ9FGfEEv18iyi3p3BK0FtvKuaKOc+IR7MErtFJ7XaLpUArxxwVHjPpdmUzcarcc9z8u2j0IBBucfbDUxDE="]];
                        }else if($packet_id == 31 || $packet_id == 42){
                            $lines = 15;
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
                                if($packet_id == 42){
                                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                                }else{
                                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                }

                                $slotSettings->SetGameData($slotSettings->slotId . 'FeatureMinBet', $gameData->ReelPay);
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
                                $isBuyFreespin = false;   
                                $_sum = ($betline * $lines) / 100 * $slotSettings->GetPercent();
                                $allBet = ($betline /  $this->demon) * $lines;
                                if($gameData->ReelPay > 0){
                                    if(($gameData->ReelPay / $allBet) == 15.4){
                                        $pur_level = 0;
                                    }else if(($gameData->ReelPay / $allBet) == 25.6){
                                        $pur_level = 1;
                                    }
                                }
                                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur_level);
                                if($pur_level >= 0){
                                    $allBet = $slotSettings->GetGameData($slotSettings->slotId . 'FeatureMinBet');
                                    $isBuyFreespin = true;
                                }       

                                if(isset($slotEvent['slotEvent'])){
                                    $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                                    
                                }
                                
                                
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent'], $isBuyFreespin);
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
                        $lines = 15;
                    if($slotSettings->GetGameData($slotSettings->slotId . 'PackID') == 44 || $slotSettings->GetGameData($slotSettings->slotId . 'PackID') == 45){
                        $pur_level = -1;
                        $tumbAndFreeStacks= $slotSettings->GetReelStrips('bet', ($betline /  $this->demon) * $lines, 0);
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
                        }
                        if(isset($stack['ScatterPayFromBaseGame'])){
                            $stack['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                        }
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'PackID') == 31 && $slotSettings->GetGameData($slotSettings->slotId . 'TriggerFree')>0){
                        //$slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 10);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex',0);
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
            // $winType = 'bonus';
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);

            if($slotEvent == 'freespin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
                
            }else{
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, $betline * $lines,$slotSettings->GetGameData($slotSettings->slotId . 'FreeIndex'),$slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin'));
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

            if(isset($stack['IsRespin'])){
                if($stack['IsRespin'] == false){
                    $stack['ReelPay'][2] = 0;
                }else{
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == -1){
                        $stack['ReelPay'][2] = $betline * $lines * 15.4;
                    }else if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                        $stack['ReelPay'][2] = $betline * $lines * 25.6;
                    }
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
                } 
            }
            
            
            if($packetID == 44 || $packetID == 45){
                
            }else{
                $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
                if($isState == true){
                    $allBet = ($betline /  $this->demon) * $lines;
                    if($slotEvent == 'freespin' && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $allBet = $slotSettings->GetGameData($slotSettings->slotId . 'FeatureMinBet');
                    }
                    $slotSettings->SaveLogReport(json_encode($gamelog), $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
                }
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
                $bet_action['amount']           = $allBet;
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
                $wager['game_id']               = 58;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = $allBet;
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

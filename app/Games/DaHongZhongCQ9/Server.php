<?php 
namespace VanguardLTE\Games\DaHongZhongCQ9
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
            $originalbet = 3;
            $slotSettings->SetBet();  
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 5}],"msg": null}');
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
                            $result_val['MaxBet'] = 5000;
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
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = [
                                "g" => "5008",
                                "s" => "5.27.1.0",
                                "l" => "2.4.34.1",
                                "si" => "33"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["gr6cG73LkzDr2aCJWg/X4f/am0h3OrgCJ7w2k+0DaTddgSeDC+cImZ8QWZ0p36vUY8ux1J9nvAQCfnnD/eoHCHSXmiq/EhwmMNf68CE+npC3IFpLAz2wfQlfBVi1PL+Q1pAazVf//8QD/gFSkLgfz3kKY/h2Wl7u+fkWNy0oycK3JJZ6QgR1ogXW1nROVxxYPqlBHQaCOcL+rn8IdzqBtk/qjRfii8q6Jk4mhhf5nxzd6Rh/JrmJF9Sk4CQ2ct5E+zy5lFYwYzrDj65sglPtKfWF3ZKpIRH4Kv5K9CY8oOGiMNqwKAsWu4IvEvzwQHBiUYkvKEppAtSdUJTtnzdSHC4qZo4RVPdy49eJmjQorICov93C8tzmjIx1bjlZz0nS9IJlb4ooPQtFG3OJEAbHHx04K9US5pbiS/WS0BpzLELD1C1nGO+C9OXj9ZNJFA2g0TfY6GHA/PHDAZjwEG58mTq2hpLqdhQL698fCUEl+3Gil9z0VKXU8ReYyyoXCrbNsPXRjbWqBHEtw0Zl44cWGCoBOJNdiALGziZv7y4IeAHwbtsl6BFrSSLIC4/QnMGuwwP0SKaeQnFsltW5Fgv9VvqTS1XgDZp3vioHro79n5OwnpQdcbD9QDzqZzu+AuicAYK908oDHMbYhH3D","MYRB8nVkFr7G9svw9B0kBkZx0mNIMZ//IJnf7NvrCSw5/hqhvFzBxLt/Fss59dPx7VeJoy/OPyAO5llBPSseY68LN9gfYnLZwePdwhCNFqK4XF4x4n6WRwGXs5FLQ60tOnJONCu+Xw/vtXw1AcL+qRnFtigejXgvQrHn+Vv1LeMxwUZhdCRW3SbYSdvD12/B3LZKNz6Uu/DXkOVpMSmlJvsP3/yH6LPCmvNxz1Y6h7VwpUaRmNtNtdORZIWAZxQP5nsgQuu0plfhwABfytgcgTNh4UW0f4lQ6wels1F4qfPcwtDqRN2d0XWo75gCnEKH89c5Zp7UCUVr939XwYODagdVM3qVdDcP8ge7mUIf3tpEFN9PxuBQKiV3f7pLN6L+dfFTjkhvRy0QAD0OC81a7NSYpDb67oFkw9JRwotQ6mkgt4PYaTyUrJY153KK0XqZbJHpWJ4uDxu6K5A7KolVr359ophF1Oniiw1cvIOETLfOFTbaM00OWnxuY0T2JT3+Wi2M+Vydl5jG5YdzMKbwWJda3CjVasOzx0/l0zf/Ri+Lcjn46KSQvl1bzudgObiUNF7cCt9uFUZf5JXXplaXV2oG6YMDh1XsxgRfUiEL3KJLYmNmPaa0ZxR4Z366MbQghCVoW+Aq+2tDcsEFM2OEXHsZozjofSR8jWtbiw==","pzmVkBTHk1RjthfPN8heqiufJFT5GN081M8ZJB+8EcqX1ih6hzTy6Ac8jDkL/oGoRdB6BbNmnwhF1tmjQCzXHbdekBydz1/nEQKJ33YIaJSgMhg+AcBuXUUoxon8cYaEnc6TKw+91nfxtX/9s/n8vzHJFErsS+0obRQQ1D/94KWM97zBEDQ/snqjcQfrd5LCBweeBTnQKAgr5d0ASq1po6AMfmBw0o2IrKqd8ozZjSMno0NROOcvsXx8GsB7nEnUD8+hGANZr5TY9g7L0bNaJvIIF1qfPDnn7K3/NPJ1UGs2pHNRZMoprcMSasDMeksr0+jn9vzNmzDfTHexvEFKaE6BNbBHJtitBlw+BWd79VJMVwhF/Fb92MDJBQOAaq5U1N5u+uVXDUhmvjygQ5AfQZfEAE5mXGM6ypnm5n9lT4GNpr6jR7G4HDbBTqPr/qGmwmQXMw7kKEI3RRnA6i2oIYaxZ7FFpDY1+0mnAAgypvaLngnlaN+ZI7BxTevqybaTD/yS+M34u3pm8OVUxy+a7ButoXJ7or2qI0DzUU/R9JG33ZfyDdIDsrAA2olbvJLVkP4zLpby7UW1PXYjr0pWnsS3N+aSH/xKQ4f4WE1hcwMYz+vM38X1pxk+fhNnH4ZSmyy5+ADxJmpCQhbwnAEKs7Nq/LZJ9UUJEkgrpQ==","KYniiGmchidjzs7y8VGDxPgeUAKyOkR9iAmUrJ6AsAeCOftUzy12FrP0i+Wlecc6ceKHbUm1AIGPK20CUrtIEct8FWEfjFgZ24mqyjNTW4U3RffF6Ol7MjWcC6m+nLp8LhW/ClBlSaCjSD8MLG43T+n4UTTj4uXOvrleJ8Z6crfOCJchn+VvBuk3+kR6QagiKZnOtjKIW10D3Zi60plbD0HBN4AJlSDdqa9RhvFzvcPqeGTtMW46MVTaNXQ5iG+XUWVhOMV5Ia9IKy9pVf0jDn/Jc/+Sus/uBVk5Ohf01RuQb6TUSBV21QSQnPTtNGrlSG7/qIQ/gsb1AH0M0dRZ18VJqD+ExEHlphrE3BLi/kQ5eAxQi8MfBjPAZx1a8XXC/e1d61IWz729jBRWx6J1EUzu+/WKvmeKMMa0LYtTEKByu7pQBUf3HBW2fQXPveXIhwUr7wqCnfTjwvGFsY1eJM+BHMOBw4EZxCvj4SsjTQL0d/AhEB6ZoLkwPoc=","fw0iolltoQW3u6pkk4GFvmsabd2TxiNmd4evJpxthRmwMafWO82dhSAiYZF0pcnyS9ye5OriZQF2pSClrkuhRT0jOon7Z4sfvFdDfd3MymMY+b3khGMsZkDyltWhHTeJHkXT8tyzCeAeuOrkieAc5Ggktu3GxYXfSfaQVvOpKYn6yCPIOuRk2DSUFWWsUsuJNEOZ2WPfciyrqp5fjiyG9TB/CCRwfwXv9NbhGT+7JaHgTa90auNdroh7B/cwkGYOYuSi+BiuTSnx5WX968TBL+0DK7tpNZY69prHRBuPDEWMd06q7two46HocDJ497u+EGdPOXvqn1WcQh77E+OBP/59lNJnDqYLOTYf6Fq9/XwbT/MQoGfY9zvW97Bn6Rqp68ZGW9VjF4uBce5OpG9D4YJjEhy85FwKlHl5spKwbZx0aYa3JyC25cbdiq3b5jjhmcZkQEaQhNWxvhB+QX02AQhkBcqIBWTbjwNkW3ptwgKtGtAV4R7ZHvdVr3iPHf6kuB3m+8rEANj/NfamWzmbU2GWXKSiQb8kqDhSPyEnK1ejvDRQblqeqmmIKEyfXeF5V5gDizmAxc1Wryr5nKhQ40o99sBSuNcd5adgeA=="]];
                            $result_val['FGStripCount'] = 1;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["irCPgzBfAShqafpq6bCycDHy/zpd4UakITg1bhEsoT2biPuKnK8htF5cm4AIGbAA5lTI3xltCHtfA8dKE0Ok9F06vK2L3ZG0Oms+oM3BoMsl4BGlrCsLDKAMwGp1fVUCA4BXf6vpP273oCVxPWwZsLv7we58f32qoQRfK43sOCgylKbQk6gwFYPddXTp9uvW+OlA2D2jmWUYJBQGqO89egq92swKlLfJsF/vV8WKkZUM13qPXPssjNI6GVhPrgjNaeNjaApi585IqK/RYWygUIXDLtnitAr286xQ/jMIuuyU7AVjY/P8LdJFBtKw5NHKGRTwx32iWPus5U5TjVTAvD6A7q8EuF9Qu9KIMfwuM+rl5QNhnLRHJxgiGKnwk6/35T1eFfIWoDjGbKpKTozobMVhHJf9zbmV0fMe5IdBh3nwgfXV3fojpSamxZ0qxuiZbQ5ioLWSHenb0cPcBNJOkliGuHpbsOWoD7ryBknpOPUdML6NFmSsuI14lSs8fiLNfd6gd9MoXhop5Pi6CIq3F00LJmZVh3Sc2lF2bocE0O1Z9gOx2QZCCEUBaz6Ax5wmq7QLwCfzVXgTPipzScPpYzu/r423fraAEk0gCx+LInYP0+S4mYXZKFQfPBuxRLYdsDaashm6fPY8yPna","Uhd4JRW3Zq1BnSeM3sK31EfWS1UY3H1Uu8AZh1eV3evld0IkgWrPqtauvkoDez1TVRbuFP0znLLvkSpX6njvjuzhCnf9kPngrQjtLSuIYjhWJXHAmot+vIhWpKUZL8mNtF3TR7Dhu/pP/hbJRmkiA9XXkgMjdS2wLQVV7P8Jwa+kR6r9LiIXzFoyu7zJRcHPHW35UgvJlX6Llyub91vtzxsV29+1ii3E1ZLP2CQmBgUTO2mhy/2nBQJkKq3iCt6pick1ZQfOtXQFHQOqRqrrtqxVU1k9H3YlgErpI8LQd5SXXCP6Jn2JTYObHdXmqq1JCspI0FUt5VUKI+ZQ84c1NYv0QRDptNG15GG8MuAHImsvT5FMmEL1ZGs2eL/xuUncCRiFVAn80Uj/B6k8f6mniulGUd3zTrkupnWZDW/VC6AVYGYXYgn15e2Xnzl2C9Emif6qsiCUXiZYZnx4kTdXzzfIDBdZZ9voAdOarJ3cvbt20tH/x4WlYYIissoMtnSncLNeAOBhLzc+IxTSvtzMQqt07hpyw9WEqCU2FzK5oL6SSRxw8FbMNtV5XuHkJIvpOQj8Kv3bWiT5c538xVxWh+nCKNp20jviRqBJPBovdPKh6iWw2m6MmOrpMKNEQzzCp3FbSu3+QMAtFypq","PbV2weBajkajs6uyYBbqUrI0nfhaOykGNannHItztprmXnRx2h5P1/TkdKFrlRRxgA8d4gNuzb10NBg/1d+6nIshCiZEFsYJ20ALwoYVi3XE57pZgDzuGNIfmTuJoH629JzB5XAgEMkhQD+zcXLHm7wS7npAw7QIIp16VnOfIOWZxX68CVA3EdXNhn0bxcw3xHKuomamLRF48MljIWz35/w8GmODXMNtWneRvfDsxMYJapIX7SRTkD81r1KD+h7behUyIeX8baemvsuZYhQGKrvzYUuzEUqyTQc0z5UqlX9h6UPXw7n80SKvTgDmn/xLhL83Iy1KjGt0Y05yIpkw87CVA6aBGyRVMQbjFEz00rDxr43qEd4h07RcumzmOnbrlfHkhoYOfMJ2lKaKWwyZIKVk4COldKhsYZHKkJsaNxFIaRhZvu25Pa4IOoGeQWPGTEkxpSvSHnXwj6B9WkN1hFdnCmgqzxR1hCtQ+bxHVQ5drrY0Q5Olwn1CbsSWrQJl6gOyO3W4t1EB1SpXZouAw1qDHuSrCn1ytysK95CF746DVwS9aDm9ni2Nx/QZNlBdrj7JlyHCTstlgpg1HY6kzCuRsHQA/qsye3Nm+YtdqdTmhjTnLSH0E6tCzAg+kM1AkwpAD/yhSNfboSjYcU7xhbV1lKpjgcwpWxaQrQ==","wpof1iDFcTAJSVFKO5PmEVIr2d6gnVEHws1H27Ue4PZkb1a0qeI52MI+tRSDB7rWIyjX0SlvnY4wIUTYspj2m57TtfSzljwOYqt8J0+1HaqrfiaaOVzjYbohyHa6hWtWL4TN09qWakd+32EjsbXp5CKS8vOtrJXpoOsC1Ke5/dE+7EdelUwdueIHEtsa3Xxnbory+9HPGPXmwg1Nla6Cx3Pun4Z/C7UscpCVPE4/q13Fc5GO4i4Om8s613qG+ag/i59sHwIqBLV5WcXJzE4ff8+NQ/UAGyvMQMAK19y2X9fcsNNfwGwciZ6bLj2AFHuivw2kSn1sZGYeM4XHrX8ElBkWnjjf65y0MKLuoU459vaBXGpHgWw+L8WXiecTwDDh72Klp+ya+JMp1kO0+ZHgt2F38m6iAuMeeDNnuLlkLATfVvIF0/Z9FvXzlNw6DGFVnk1Z3xQKV6eZTafq9cXV/Ar8tjJFYk3F7lS96s1+7BI7CnDNfgXl+S2M5jI=","6NHLlu640LYHYJgcAogvSTwntNKKXDeQgUCP6UMoXdIwTMXg6DNkpI1Dc576TBG8Pl5R5KhGSU9fegCKsBnvT3lcMg0H9aaX0YNLAQA9Dui3ZLhSpdoF0iqULx8uXvD0v2jAQ2UKRPcAsm6lY0e8sTAKd94a2F7Fdpsi6mpnkI2pCBTqnHAALaH97OWczOdJcHmMlikuQZURI/GdW+MU8tYnkgXxEAhlUMuT1MriXvyWUfk7V/ytQGb8OjiE7tBYneFLdeP8QzNZXUx5+5U8hRfJyXbEuAvrTsR3oCtR/dWwxlRtDWKZooR2Oa3qa5Z4tOR3VMXhFeSrT2Ks6dGgq0FbElletegeEVMmQpI8ssCpDvtQDiID6m9z2rptNho5K+J4M/GcS6p/Xi5HQhccotQau7DIcDoryYAskHnNQKIzAMp/51tPKlPbMxaN3cKUlsD8qezaPLY3bBoXmVMophVnGw8QOVxmyrkf8qFdS69aSUxcWQXSpaCVxFJVbupM4QH03JWd/j5MXWlzh+PfQykrOT5pksBln67MMpILUxIUuCfOGYgRHZ3FE9B7xdLSo04fKsX3/6rIiJYzuoe8kKDDZf+e7T+Vp0skOw=="]];
                        }else if($packet_id == 31 || $packet_id == 42){
                            if($packet_id == 31){
                                 $betline = $gameData->PlayBet;// * $gameData->MiniBet;
                                 $lines = $gameData->PlayLine;
                            }else if($packet_id == 42){
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                                $lines = 1;
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
                                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lines * $gameData->MiniBet);
                                $slotSettings->SetBet();    
                                $slotSettings->SetBalance(-1 * ($betline * $lines * $gameData->MiniBet), $slotEvent['slotEvent']);
                                $_sum = ($betline * $lines * $gameData->MiniBet) / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '541' . substr($roundstr, 3, 9);
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
                                $result_val['AccumlateWinAmt'] = ($stack['AccumlateWinAmt'] / $originalbet * $betline);
                                $result_val['AccumlateJPAmt'] = 0;
                                $result_val['ScatterPayFromBaseGame'] = ($stack['ScatterPayFromBaseGame'] / $originalbet * $betline);
                                $result_val['MaxRound'] = $stack['MaxRound'];
                                $result_val['AwardRound'] = $stack['AwardRound'];
                                $result_val['CurrentRound'] = $stack['CurrentRound'];
                                $result_val['MaxSpin'] = $stack['MaxSpin'];
                                $result_val['AwardSpinTimes'] = $stack['AwardSpinTimes'];
                                $result_val['Multiple'] = 0;
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
                        $lines = 1;
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
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, $betline * $lines, $lines);
            $winType = $_spinSettings[0];
            $_winAvaliableMoney = $_spinSettings[1];
            // $winType = 'win';
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
                $awardSpinTimes = $stack['AwardSpinTimes'];    
                $currentSpinTimes = $stack['CurrentSpinTimes'];   
            }
            foreach($stack['udsOutputWinLine'] as $index => $value){
                if($value['LinePrize'] > 0){
                    $value['LinePrize'] = ($value['LinePrize'] / $originalbet * $betline);
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
                $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
            }

            $result_val['Multiple'] = "1";
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
                $result_val['Multiple'] = "'". $currentSpinTimes . "'";
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $isState = true;
                }
            }
            

            $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
            if($isState == true){
                $slotSettings->SaveLogReport(json_encode($gamelog), $betline * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet'), $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
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
                $wager['game_id']               = 5008;
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
    }

}

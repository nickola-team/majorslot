<?php 
namespace VanguardLTE\Games\SnowQueenCQ9
{
    
    class Server
    {
        public $demon = 0.1;
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
            $originalbet = 2;
            $slotSettings->SetBet();  
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 13}],"msg": null}');
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
                        $initDenom = 1000;
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
                            $result_val['MaxBet'] = 2000;
                            $result_val['MaxLine'] = 5;
                            $result_val['WinLimitLock'] = 30000000;
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
                            /*$result_val['Tag'] = [
                                "g" => "54",
                                "s" => "5.27.1.0",
                                "l" => "2.5.2.6",
                                "si" => "35"
                            ];*/
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["95FeqvKkCAAT5INBjRZOplACAyJCnSlkfDYAvvreRhNzHkp7fgaQwnut7AiqthCvEcb7UKBDGATXZZRtve9HR4bSnpIgL8tHUo15ZrGhzHi4bPV7RBr/BeRkutvAlaBAumoziKce7BjQsK5AandE/g3zqjAJdsLvv/EFTAD6mAxiybgTE3vYMm+aE2uECnsLAAwzQeAqOkpZg4G8wvjW0VEbD/nzmqFl1u8fgp+QRkik9mz/iIqB/ave1jvw80THRNaB63JPjmN7QZLIXuRnulC6Zw1tQ7cCUO9jiX8H64m4W3xASgJdsiByxdE4cQsAqh5C46bung7VNKmZo7CLC/3Xr4p/RYaCBswgEG8NbiywEKPGqLK4rx7e4MBqRW1d1zcyNGoUNbpjkb4qiFGZSqP7/+70w3GESJdbCE/Wnyy8J52JcT8sCFHZt/g2hvY6vtqhwhoGXOPKV7j68dgyEABuqqYH142i7OXZ++fnoVMyjoppHJ3QdAo0Gyt9vTejxx09xMbQpntSU44B5XWR92HM3AUHRefmS5+wHKPjlQIXMtKe5yNmIYEEQp8b0c69Y44fnHs0O4c3G0/rcgtTUYq3Bpan1oPkg8v7IrBS1N6+EFZl3BteGBw4702owZyXxp6yL+WiVDjWJ8xEnz36c0nBltxw58sMc4jAp/t+wORzGIGL9C+gGR3NV3UOre48Z/IcFUsHX+B96CTfrWYUYp+BAmyFFmpnuRGOnA2onsRSpmhkDv+1/RBgiqVQw4KH8marL5Ptom0FFyrnX1fhKYCuM7IrVAbEjJPVdLFnkfnah9myt5jOi2d4BUw=","75gjD8ZIYlBNJiqodxQEOh+6qPkfGc3tjVUi7ufa9dLXTii/+D621ohURpifvVll0s1rraQXGvRWNNPUPApPu2NnjJQxMsNg0WzrWN8qZAzfSmh5AJuA9IGIGzlBDCmIbTRCaQ4GuHTe/N33/J+2kWUN3kUOlPmxOaeKwIDeqczwvhPyrJvBysc9WgS2oTpiyrUH7yEvEojsoM6bGaAn/DhWhxMcXeMC1U6eh9WWZX+blWqh2ZDOH4HgZWAuX3jguBIpsoQcXOPMf3HB3S/MF3VodGE/lOT06e5l4nMEE8pe2vxyxR2PRBKDokdqSt6J+3fsxgLebF4QnJmUeCwLecL2IT4gFrgGEBQk+PzhkEBD6EkBrxHyfwSpdqxpGZ7QoLONqc1p3b5FJEv05hiyEiDTx5ZQZ/rzOFjDyEBqT3GW6tYo761HVP4u/yaGWaNc2RI/TCH21tG32f/7DkhnuOse8qE3CyTHhEhCBYcvs7ke/jINBb5TEPwDKq4GGvU4pubLGhHWPV1IHpf/lg+h/mLfcsaiLiR0IHWXpdDqaSGxB08z5sF0QElbVgBWR5QzVOqKwCSve2w72Az1K4nzwyLaslNrhtk+mH5y1qBTdPxd9YIdrT4tISrgv1PSTBOXuI1mKpfloeAr49vfprSTj6m6OrLirFhVnkxedFvMO1wsgMMwxOOxIOc6hrbhCcYF6bfXMHbZYwJ8XVvPPVeq8fBGlBesyg+88x4GvjG1Af2+GQPBciJrEdurh+obupJ8ghv7r/uTbVc3UMK1VuiLrxYaE+/0aPDSNsq+fg==","XkFFciK15qvvYDm3rB0kBdj/Gh8vdQc9jUlpTX+l5Nq3gM+MnBQSw1M8OxtnMiGM9s7xLHhL+TeFqqBSY0PPR1q8NOhdZBkTW7EEM2/lyxTRyQODSru+pOmdfzzvnEEnY6QywMpknMTNZ6PeNqmqcgq7Cwia6Twr+LDcSVM4/IVXekOCcMWgZBRkP8aMoWQOyjVyxbLw5FZNQCwBWr3KHBWsNtFttXNbIY+wuKPJBw0/Zal8JCNS/BvySFAsNS2zqlSfcw2vbbr1EO28HNC/Vr3uXPQkO+wTj31I+agY4f303V3eocX+z9yhfVxqjmdu00lQitquXJekMRmLvb2cksQezuw9EUBMciF45wMUjRtand1rH7cjeHavRG8QNOK2p1+LjdNNdRrDFGH1rRcrq5Z2cAQxFldOhxrbadgrTqcyqYf9dJh+q6BaPINYFykDxHK5hbKj0rF83Sq+UE75bzJXQDYnFpY+HYm35WZ+wPXhQUYiQFnUGsQ40f56NgI3GBvByoluTm6p7hQ/W/b8szfyKckaa4zYKqkUaaTpOj+w9dUh04IeRu7nsGF/W3FXHgH4LumH+JYYQLVnzWI+eq2GMau4isLvU45JsiWPKCn2Y/h/BiJFDI7EEe/D8+SuQ4a34KQjqgYpBH8MLBk2DxMwucuJv/YNDE+Pej7tXOIHGaW7urxgC0MHntEY5tV0QqHW9EOhUH9FO0DU8ReJZmmeCjNTp4DcZyPcuaMG9CD1tP+FH7CIr52qHUI+EgQYISb5I84b41iUZTUGseLnkvyLxfzUqfebVuuf31Kva9f9lofHMhGDbYXp8jg="]];
                            $result_val['FGStripCount'] = 10;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["fuIotOeFHPDWjCoslz7X6m9uqx7TjO+MnnBAxps6QS/AUgQDtnBJZTSZ3mZxhRfvTPEHdxuwV7VB8j0X4Sf+OTS4ePabGuZwVXVV5vNfbu01C7KQtrcYPPPRFWj26+KMJOQQG2J9nLZSzOy/JznXk8utoAKGqRMuNermXbXHI4qFDBYNiRyfJQJeWeXZq/tC93DAYXYCfudQQqmL1EADmNIdvsfZMpkj7lNh/NCQ3t9lzEh9iTBG0+VS9jGXzYaDmMtka2jEl28egoclBGqHeXKrtFiH7zLGxcJR+FER4OJp8z6hXnEDgRPdoSvC7BG6qJpfQ+kjSDw43BLg19101UKeBOWojyl4RtlBX9XVZMg95Gy9CpUIut1aYx+ILo62XmExG8w3bHysae0HDmuqGYbghZrnlORVAYZGgLg42yFGoikct8B01WgzKYYurocvzeqfJaW02a12RgyfrbVJRcH8k4IZjuycDQWooOeWbjOVennT2MYfbdKMRpDSxMtZEmI4/UNYirOzUgkCOrLSCG8YFGSJ30pEyh9F+wvyp6BbCFHSBk2fPammpMHsquJ6m9ZNZ87xzb/sSFJT","wdsM1AjgdSwea3bDlFwMkxaIOlZ2utGEusAs4SdvhOLMt8um3IF2st/CpDasN7nyRj9WxYpgOTPjqUChrWjFyvy7CrfwwXwVnORmoGBVZQmFr4io4Z8Ej38SJ87GNbkcOgO7ey2Lpv5ZUp1XnZy5CxrRh9kGQ+bokM/7nGiGoPTvKDdXoQj4ILDOxGoj0Km2l888GFeaQZw+MKW2Goi16IwkzpUwV1QqmJ3CEcj8aGIki/Ewuf8iFuZnk66HJHBwdChCfQ+IywaBSY+9XSYvCUYs7mtlmgbS2HA9Z+n8ARHS2N7B7nKCdvQXZ8OUs3xalGRWUsiK3CUFAjkIk7Gv/c64+D5eL6W0wEDKHxEEK5uViEz12nWZ24vSDdrJ6hVBQmj4CG57UfkFzqnlb6x2lUVifkZEnJdBL3+e0rO9wW49Xa4tnVG0DP04cQN/Q2rEMt0qlZxUhXaKIKXS9Fr4wWyRQNkFYJlxAEXs0hqU2jxT48hfsp9e0DwDEKtXLDdRFB1I3VATwyezKB3m","Wmh2tDnKuaZ8X5HuyWArHK21fKw+qKBz4+vhLvvyzqugDNEz7ZsQLk7s/XWquy2l94zRyyYAQf3q0qLiVZ6XOfDMza7eHyGEG+qFFHNK+GabB4rC7xSwVl+6pFeTFP2go7ccqcdvH7WHfZdlPRtUoppjVXB0RV+wI+sE83p4a/vC6FJAzvXo8mCiK/bf+7Md5dIJdUoCGclgxp8IhYOOTE7G0iRc+A/hlzrIBfrbIKXADD7eee4GS/3q/eD/q8eMbLb1d9eZkmOdja//PKBctyW7zEOrtPiTnvMqlysCZ9+gcMJXC5vgVCRrvNFYpmxWaf2MQczyK6H2HwCOrcXRl/kbGIyZXPeuSsSk9h8W9WGBEFOW+C1MdMo9gAsBaKb9wi0kuAbEFqz2phJpzM0tNL6SNneU0SXfdd+FwbRgW/cu+WOdDy6DHZqqamgmEuD4PtMkQe4+ZeRLEvOhBg5mLuOEkRVZlEFw0ODp4/HHI3h5jLfjNYGKJ2GPCPA="],["7GqjYdQtmaWnn8vU+CVZzLQ/y2kOk6afF8Pwj+ObsJkyXDO88wZFFOq4n7UtPvT0+tgqkoRi+RSpO1zSSsLCdIZ9DBrK3zC7vH8WkMLtANhVBydX8yvwBqEVnFi5EkDEE3h141dwQLtJpUUElU3M+4fBtJFsDi7neNyoU277t7f9MVyWWwe8t+Pga1C/BKGwEQzUT5A65CdsjPG1O32kgNuTi4KXFzHMg5f51yfjSj+71a0aBVYtK9q3aSuyaR2aNa6SEned+oUScRailFXl0ZLRE3RJT2sWfmXvHYVlC9Zs1vgtUw1+eIQ87lf3B2jUHrh7oan6tFEB6RtjijrmffSNHn/W689uU8soXoA6q7ZSgjudbNXHROSyhdkjycMEAYYQKcalnzbT43tPyg7QXBnFfrip8ydDl7oBu2PLJ1KA4WRS6qSyhxajVrlQRBgjtwGLnIPrinJc3/Dy8b14QSBIexGD0LOhW5RqtBx6Yyi3CZ1yhZjN784sIMlDOU+oXb7ljChmUC2qd6HIfDr3f83rtmHYUkacmbY4T9996F23p30e99IvxRO40qlCDhtgaZcgxbs/idYtsmSXeBpJtM9b2iE/P20gV0owpw==","VcADezPafjRwyR5P0VeumRXrrWJbs4U/5eUenKD0ZTPMMcOGJs6ElwBYFZtkmnxYq2h9XpsLZEmVx/t0Z+/2f9PF9Y5KGFAd5wPQjGlP48dHT9MABdz8FsTw8BbTQGODAO8wlbo+mHWVspoGZZG/JvC0+EoLD4bVoitfqI22Ukjmovfs3k1BSZL5xBtdlRynR7OFJPaEAr6QTvbG8san0Ru5nfzUAqN8FquY6G1jz3x8ZSsK8zYHcIAQ3RArwuSJtiZhmNuThbkS5rCU1jxmt+hDC1fCEbR7ZevO8PwMz3UrKAO1ti/1boqSwMfQa3JiYxX+E4+i0h3zr3XAty5/p7NM1ZEDcKt5g6A0anSXvoN+dXzRqJ1WzsujDpNaKDMNJUUFkW55jSLceimxS9NA5Hlh0SRnsDrRcY57Rxrg8q7Z4wGAxhYPzTu7SSXok1UPdyxxmn3YE9IPlCCxGMs9g0tVyQQk9IIc/v+BB84aQSpqve+Kaai0HcefyILhEmdSUjUjsfoNVSxWRoaNRAQTEIZpxMi6PFqRzMwSzQ==","xItdUOv7z1vRxKc3oHXtu8XGj/+POUlnkAD/2pRZQSBgINngJ2VubAD9ZgcKfdPlQWaW7PF+mjpUQZTdmbl0N6IO5ibQlJbZsw0OVF0dzZFi8es5AdYygjENdsoCnqfl1OUAo2RCV1i1tLc51GyNNbQ2iSUbVSS+FLd7loYX7MURya819upEbG7gf5kcRk88E72ATB1FYmYqUevxWRq5tJ5ViihkzspgWVr5mdKI4eDG1IhSX6fXJVcQgc/JRh3bIlmuZuPb2kNoY6Dblk1Dn1JgOhzCXYqCnZR1PxXhDXeKbbF58yC664EhmBU14Ey53MuqNoEvCSL6SIpIe1dOmg8wcE5+fDsNzGCle6X6cYFzlRIqcrDo55y/rL6EAr4IA14MmHIR8S0pCew2Nl+Pg0JypfcCYToHj3mmI1U9mF+ExrunFWBJIvtBjruwoppbIaqRBLdYRQdUfKSYlT9FhQ0Wwsm5hb2Fh0ac1ug8KoPC4PZXa/gdZ54z19c="],["PRNPAaK7NGhMH9HUf9ufL4EPvtf5m1k+rWdo/eo4xavpYsro0HX3WDw+IDDwTGSoYyOcw+UJj6aF2u5P0kk9nI05rcUr2RerSGnIXEVIg5WEPhH+9uSoAzXOb/obd3zKKObWkq5+4X9FfFQ5OGGYx/pPN54orJnR3O7pM4Au3El7fiOQQ6wIK/tAEQDFri+GkwxhTXfyuUy9iKH475pKLdPg02Jp7XGOqjztsuvWoI3/Zurom92GyEng9FVXL9i7yTXezjPKamTr5H1Ngi3CU9QoEJ5e1OgaCcUjj9P0sc4WciCDUnmoBoVpRNrjtK4Wo4HqcSLUC0bfWLY3V14vVe8kgy6ZeEzUc6eBxIJp08znJkNAxJ7pVky/nYbpZd78RuVEhw94tnB1d3cPqquURSaWRCmWkO6e3vVSdW+f93dpylxRTQjBTnq3Xux/DJz+X0bRabQfZznWZnuL4UIhv0mirRFrqGX2/EFVMnTHFEIAi/tKNm1gwpnNUKIRpcjG8NN9wClWARNpjPrDREppQFEyNdNdLyObrSlp7vwuJ+nzbChZQnQwf18VM/IKLavSBhWYoqBKDJPDpgSoIKI9+rm7ouwcN8ix29Yd7Q==","PBCbILV0h5jdMdurwymTSDsuxqmDPfcPxJvjHwKFwS7SSRGIQKNzwkpi0mQJR4PdixihK10h7stpcpnlS1zt/660QSwIIE9Vu32Otwfn9mbmAoorixvkjUST2xUAIn7SM+Ed+YC20CoEyKbhZiWgM0jF+bRf4MAzAo6fB4DNkkoQ/k1N74hGtLxM2FBNIxbhhD9xHwMo/Ynvc59aXDzX/W6Wf4MxQOs3wPCh/RHkkBTU+r3tRof8WHuIACbzr3dnTM6ZKmAe4aBPbo2hL41ySUHeG3hmWM3RWCsnBrwrNk5WmyZpEcwsi4FAuWXSUoawsgvZfP2Dx3P8KA8UZmLUEacySIIfLxxqkP6mi5hwONOgbRaaSgLZ+1ExMz/gvpK77aeKdZzDXi6LnjwPPQu/MRmWCLxp3cuVoSXupO8nk0+zavDGnAkoxAarL+A4N7tmQK1D00eHDMzw5fqmp36yyAR6V5qQ+5xDKjRLAqWyGwiR4W2eEBuMJmV9d3e3BSASix5dGyZY4fTXwaFlGq6DZ0vlEwHzEyPQeyTpSg==","0poQJNv0T3INiXRekFynfdEl5I7fxP6+a+DY6xffnJsvKKbpODKma4SFPNoBUDiV0YT8oYjOMRWLkmHi04Be7KPjmPY00KLcIJYtjWmWfPxSy/vIYA2+DtLcQgZX6aM0nmhxfdw1AryMaSqnR2/7ZWuxsG6f97IaTTggvRxwitUuJm2/730KfgnQyNSmwTJhPazk+58gGqWJnce90LkZP2my+HmkuC6c99Iga+UNBK/LrjZ86HNDc4FeXyZiuSTQPpLX0O8Qa18sb5LbyWlFqRZhM/wdewxn18gtKY+AgqFu5o4JTMCzjIbTSbx7bgRXHlNBIwFf2+2L0qhhyruVoOxX4UNa9qc982po5e4ijHdhz924Ls7X0JRZeLpiTzb09RJKK/+OtxbFpPD3vk7m6OFoyeSxFCJIdO5NIFLDooIbEXKSoz38CmhlIw/yhAPsSsUR1o2VFh0l+OGxAB3rqrhwJ//UhplGsV4AJ+wScSBCKa2hRCOCa6b6wrA="],["xAStLsALOaxOyXl8fQ2DRltKuK/8KKZgcf/NYaJHBYbOoUSe6IY593/pBeeTRSnnVgKlVK/SdqFkMx/uXy6Ap6+GAuhIdXRnBBKbzkNCWNIpemg/uR2kNqSi8rIZkoW/yU107QR3WKVWuJXr4w6jwzsrzlI38R9AZdy8qdiaR0HCsXJ+KVrobmnnwcs7pjAL9ZKmcazTYjibkldrXiB7QENS/d7F1qEYOlcDCtWK3lz7/hUuc2b9zDR2nILcnm245kHcDEJ9euUdsvc0gdYrESzNnSAvpF8htZqLCXdTt9k2C80MKX9KJxVbqg7NrJICCUexAKSQMQ6p7Pu24akpqDq1H10SM9FVs9Wk45JgiAn9PfRYhZ5cFBFVCcKBO8KnlYgzXKZPe2z1wwAto6WQpiJOxdjt4GPtoKKzwaVDFMQ4ycxr7EkKrIZNS2hKTAwX8m0p5wEjBSkV0mLSFUpe0tl665deRp45I+nZ2ShmyyzeHwTZ4E55uQqKnSk9oD6fgJHLKay1ie1abjXclTUJmAqGTsZ1fiv7RXFPjU6hdc9htNc4/Q5KwpXshQUZI4QVXEQmVcQ02CKNlHBCCHvQCiDARwd8Wa0kI4hm1w==","A8ufsbpXOchIsliRarhoUhv498o5vQ2vxOG+d2OQ0V9ey0L5+X0dCdshaUUJuQNlLRiduuoWgi0MZoj/QdU7CxOIlC3ir/rWNr5uuNWU1P9kTv4KVnykhrktO8aFEUJrH/fJkypufiiYqnGuVMuD0B/ZLsmH95cVqD5AyfhRDDQEdlR/YsKShA1l0huKoG/FFvDScjk4V8JBBimpWzqGEpYBZxEnIf6YTRw8jFIymXsJRfZiAHlw2v2Sm4Bfobzzl7Y714ppHNvu/j8eHf8Z/Zw6EOmp2FCway7vOD/mfLRypfGT7FLbDUpPD9jc9RVuATdcPygJLCmTTSbjMfpQ0x/ICBoSaHwNDk/HGeEu436GWWkvHjXGZXQZO0h+41OTTWXCJaorohxW4irj9y+5MWkLkUB4GwQmAZCEfk4nidbimxsHnYxrEibC0IHWMwcoA+sPZUxL3EIP7Q2iuY+ewNeTVnFceyKL6szOt6R3rc/0JLsrbzX8QPKNpmM05TPC9ByZe/6n49bYhCsYxdF8J1iRw7IDxp+hzErsag==","3U2BcxIcO1Rj6pjxJfVc+raPGI27kdy2VryUYqoFxYpZxGmcl4amoZTR/Nm5CHx9w/bEzZH0T67eZrkSJcCKUZxzLNNb0JTuJxzPxSG+4LHPG1oMqgs2d6xYwxwYpZ1QTfO9Nc98eDnMkumBr7qtmwjrGp28aOIkeyX/fIqUy6/LTxLgcI0NPAk0bWjsHcvP+YGCaYGABGdHTBUVXzEJoy1nE48QtGfkz2mYAlfmfg3tQp84OBdonzdcBZ35jLrOs/7f5QB7Rm5Pqn5ly7n6V7/lvXHb1/V9V2I1s9/P35l+6v10bX2e0gCooMQCGE1CvWFRqEea0ItV6ZxDuBGZOmS3eGpkw8oRhyY/mSEv6FAdWQp3Cy58O1ZVARK61myKdSwrIt/4jpgMzmwZUU/cZAwOeOjQYmDmUC+39I8sFnGyW3P/xXyn/1hVkzbtyVEA1Uasm9s79h8qRBi7lgBwMEAKaLrCrt0UBC8nxacLFpWw+Fyerz5iMeTTWuI="],["NeLx3MUGV1bRNOz6aa3++KGqcq4xRHemGLdKrmSmslGjSw3Z0kwo2rRf3y6Xnkhye1yD8UxI8qxsDf9yM7QmPY6KC2cRvfSArZYeNixSOgo/qDji2+9lKum0cN1W3IzI7gdhUFqA3V4QF43IYBDM16RIv9msHCy+UjVe5EIDtntpbWdgDsT7uaI/2rtt//uAATtxPHd8abNFFZ6bWSBiWE8RXXoU83p71XvVsuZejPE+D7rdX5v3Cq/Cp9F1fa+frIh8XbyLj8AsDj0+Ip/fZKlDVl2Pvr04v4YpEsVj6hYuR3DMblGjyypEKZXQNsySinRhYLHshuONRV4p8TIA9kw/fPT5VRN+ria8Kr3aUnjCyIXh6L0bUYtEBFvKWG1kD4O3TL0TKtmoZUnZ5rQKhq14kFRxkCMnHNGiAY/40nonkV+r2X+CgevqmJkZgaszBXDB3BJvlpK3BGyYndJrQZle07cI4l/BSigg8mKxlSYdZ6bfbx/ZTknD9SP/WuHpRtho+BeSe3kGljJipaSyCW7AqPy5D36oX+r0Sl+syXOnIYGQhIypS0grVQr5IBF8MuzBW/97rqi4I7XmqRb0Q+nshcZHv25rO8ADdg==","Lfbcoi7C6LdG9rM1UcLmHTZ3ir4fZrIbFWBlT0lFBkxw8T0zl//qJlWJvR2LlfNnaM1Qb18W/leb3M7qTOvpnCYnqBp/8jdDJPcPXc4ByvyANfJzSskD/E0GHTsBkOlWA0XS2qyu5a3yguQ1/R0qzptndKIHqsWWMfA2HB83BGEsdYpVvZpRmdMSoklt5D2za+SUnN/s5yE2srUcdiRq3+PfPO6yzaYTWH2wi5cLJNA5X9+rj/vvdxC9HWVfI8GrAAfiwmOpcTCHpAyurKBn2L2lyuMUe4gh+vf9a5ZGCdEgdLrCYWTzsI+YRnxRkAAfXbDFLec2afG2XVX0tS8t0ber27MjBtHfFp3GZngATGX90YmOE5BMmGIZES2OgQ5r00nWdVS2Xe3yFVK3Ebb5rn91hgaAkKNSs70r2b/JgLN5dmOjUp4bb5HhBikp3fSwhEGTv4Ui7AosBSvViEA+kJx02jpdwXUsOSW2s1b8oGiMTcx/P6lEh3BIxObSq6tYK72vMKrwjM0FhimGyfa3RA1G49nenhfbzVHh6w==","a5jcyT2rPnjPtJfFxsWhLmXBKDU0UNry7LwG+Hoczgro+KBfHdPoJAmd+dPxdAh1U2cg2kuvx+JVAk+yl76Mm4cKDTemwTsJ+KnuZz8LpOpJ+Vyy+RlIigIDxxthK3nf69+Lp8pXMALCr3YYPwTrENBscJ/duElrNDr54aRsEJS7bf/PT6jm5x+6p4Y3+T51vuxv4i4r8RTcJYFu8Cu2+WQtYTuzk19O/nLWrb3riXTVw6/aEoTpNMAA2MdxFM5y4sWQX5Fw2OR1SdecmOj+DdQCw/8/d6UxDOSa1vBh7Cmvt4Fv+M2U4A4MZTWa7bjTyx3Q4pXNErDweNnzRgQzgBIXtGQ6B1DDQYWht+1qTvtsJbqKybQV2a/XuD/hDD28oEAF+ddOygnND5Ij4B0+wURHuXHuteMOCjsUUlg9T2cwOK8NeKqVOKy1mV0xFBj30cgA+DOWkWJsJM5748dExqHTnFIEXw4eEg7dlwbPa1ppOjG6JHBkq0n21FY="],["MvXcW99VKuMUe6ehWE4ES4HtZqmqbO0A3vi/u2Fbpz9OUkBZFbvuHS19HvCy+TM4W4MWjVhBCgwJNVrcuCYc3fWmkpA6eFVquNEIJvvPYv3WH96JaLtd9Cosi7oWGm8o8DiNlGSjoy05JHzQAvwLLV7RF9YCNCuDDey2av7O/JGsOmFprLyJr13rBc+hyXpNkuTZRUFs98Mdx2Pf7QW2A3Czqpo7bKGgCRtU8k5B/AV9Tm89MH/OBGFv2kqC3FMX0I8NMauQkuhj8zsjYjezlR5mQhRYVgpd7OFwc0aSHt4G+7Q/XuiTerMRsxAVcHWr5xDmn2T9liq4rhJ6WtGRiwT7FJE3mB7DFr7paOU2nmWTBFqV8/EZwFpXLTWWOA6alKQqPW3JqvK7NtnQ+a/9oKLfFGhJpHK4Lh95x6jOx/8pQ+yMUYIq6xKIrgCTzyS6gXsjLG+PjoDRuLSFIMbT1GJDYg895FG5REaZyWWZnxYuFhAHnrMRto118vkWCaovcP1DBPd1gwx/WclHRQ6fIaYWka27RUajTORyTYosC81RpNKJiDOQ6P7fGNKnL/y0I+4ccCiwDYkoF3dcz+SsqxVr8NYb5cVM4XmlWg==","rzAKzsGLqhvhfxu1iheo+XZA44tpXJX/d4Owx0TIAnmTV9Xx51U9OOhoIpOBd2nw9bHP5yBSYEsTXeXvfJ0EYLxiIj/M8KjEqjeIQHcOjCPlUfmyhbPbIdKsKuKh9980cvJgh3WWQhSlYixnQ480kDaOMuA9yNe42QdyYVxw16+3Qj/UN/l1QRVhovv2tprgp+YMvuzPENtsdVp06xWYNXYDjsGZUKwTH4iNX6lTQCUKuqEdI2ZpmOJTK5qULfD7Swtn2yWt48LcSazcHqB0j1Mo+K378J9KUmIbJoJkIbjY5b3odZb4IpCKH7HWWlXBavOjJGNVJBo8T+72pkcwiC2gAnJWtLDA+kJKa5IQy2baov2jw79p+iPfwWAmlej7AKzz5TLdr4bf9WiTFy8kHIPdlLdy3GZCA5+oEIrhuENEReLacWFXRT658dd1/VHVyRpfrzL88Upw2sYMT55pm3zyR1/pV58w457l6yIo5Ln5EjYlHJHXgAzrU6rJkH5LgfOq2qw/sfN1nHVhFLQc2FujwoT17sHRYaDjug==","wuH69kpEzMt1FCtK8hcFf0V8LxN00aOWGy+/La4kHrx9IN/pmAXGN6BrLhFMa2sqoFbW2jIsJNbIj2gaqMu8RyDyqaqVvg3f0dGxODb8aN2d1XRzp0irP9OImXAlc1V7ykqnfGx6F4/In56NWeQO4FLZuR7U92jWa+LeQty0DPinxmCda9nrFWHcqu0lC71JVZ+6rwqN5EK2l1h8xU3uBAINbqcGFFcxNm6Dd+9xZPpV2CarbwNcfpII+tr29nnnkRcsFz4tKWtZGpVothOx2aVl4+VgqDNcbWEe0UD6XsqzZV/A1VjyEdtTaPSWiPIS1MpTJg0J+e39a0xQH7t2e/iDabjPNeIUtgpVcllD9O+zieB+TuGZTnrj9lji/CELXL/x7Dj/WPL5v/ZkGcc9mX+fyTBb4DwPTJyDe4keFbG3p1aepLfKnuCBljfUev8T06UdxRla7/xF9cjpQ1OmhhNnd3NIVB4hA2hYb0ObUe852R85xpp2a+d7blg="],["7F3kLaS2t49ZGA5Dvpx3qdwqWHJH3PiBwVQUP2PZAw++xwxeLwErwWWJly7YoylydNkoPlEWEFjA0QPbEvbQXwvy5hUwj193rD6HHT10HdkqszQL+YX1GQevzcq1XZt0Ga67vzuPgcKXh3jYNCF3fQcy0CWtTzbcth69nv90H6Fy0i0NAGX4nFR/BNz1hhc1znn9Q1d87Ew3Zv/aXPKHfUHbFIJ5x96yPBjUhE29yO4kVRd1+ipwetvOKEFbAEUViFHmw901yY3GFnEwnB/goM8r1/cjiIOU5SnUIA6zAU+/2ESEG5ftDrhYcLgYAzh7FxsSNd3mvdHp9yZhNFoG3BrA/Qff9jEKwtxuR4yW5tXO11Kf+U4ZjirucmFqIg7fQSN2WpZgk/YHa1EpPsS8ahHubKPZC2JfPaRB7Yv3u0T6bP6gw+4t2GknI6dO9YvNbvMPCpaz6Z00jEYyNTf1QYSKNzNGbMFFJNoCisRRU8cWg3JYF1mwFanBvTSP10K78e/hNoqFx7kSFFPyDrBBpQOAkVtyZgpE+8/ObU3A9LNQb01fpCggjwYc/8JAaGdlGHlKS0hXXgfBCxh0HKvPbN+9hI5MocNGA+BchA==","O751IkxQyjLGtwdiFCJMH6ZvpMWSX5IGmCWIe8F4lY1pnj14+ieUEO3s6qadphavvfdzF2eYEf/yyO+P8sATh5JzD3oWnxlk+YVumpDPA97iXr/1pbkdtvk3AFLn7hyo0CCR1KCTw5io7bLd7hjvP7hM2xxOziFE41g3WIgOLEZUhmoVepdwoQ9Dr1Ky14MrfPEAcM70SOXDs3ykIDacN+/fmQF+P6xv6IQQNx6RUi3V6w/7lvTvDqaQfZhGZNqQxmYX0uJ7oL7PlAgmZxTOH9+Tqd7+UUhCJeNVKi+1j3SnFApPZ0W9OvTQ7QEh//Ind5iH6Q4EFuN64E64/fOGL9MUVDSit+0XIrawK4XzmxhEEAB6yexitOnFYfF0tVfn6jJuJW162B2CS55Bx+BznWo7EvTcIDyFXohEG5gJMHszlwFQyjg8XjWQQpFI0fMc03hPXIZbTyT/lyyyCzDuvu/OOiugF64JoyLW8ce+Dala2PsxysBdG9GaAlYDcl6ehuuPIhwtHW+hApwTuA862sPsuSFKBOQU/+h08g==","X8RRJuVrJ9Z5RUHGKw5kkeMrxpwoGh7AKEvl3n51aj2Lz1hA2W22n6u9fUMwXi+dZNi4UXt3Qd3UECPrV8OXO4gy1HXXiSK+MjMCgMRFJta6NjRP9ZKtjSByFfMFm7XSrJ3768sIpnzP5RU8Jx0xDKgyWmfywuIyBRrRxRhsih+DQ7HvTcDudqMDfhly2JPW9sQxaOpO59DursgGLMO7HIsgKww9ry3RtcIJDXF10UXgBUkZFLplNBSBg5IJJWxohjhnl3XjjacILQUVi72Q5CDx7VYLnuOIU464UEWgLIntY5LF00xN6OTOOvohnGj6ZtCncJnRP7GGTGFjqA3fHpzV0fooGHu6jEnEPSKyK1TUscbeYzOiG3Hm9ooaIY7dEAPOFl4QGdhx3uYhdbsF9hb1RWwHMfbwyk/nFLAeTb+a24lko+m29sLpUV1S53lu0peYzcUWmkbL5Yhz0/4SalfRAI6jCuP8+uYe6+y9rQ5BXTiatjLKom3qLCg="],["hW9j82L9oQFLplbfiPuQxNTlpfIy3Kfr30qBRO9AJ3lx4TmsRweeZ1GR/e2wFSB544J+j8TqhHXYToiPrF4Ad6tZd9+aJWvsYDPjDbL5leK/Wt3pwgOvs8pk6VipSUlTfZeaRhVtquzOODt/OaGB3FY716gcYcPkM/63uBMvhRZyPCOjvjPId1i8ysaUI5ukdYuzsCG1YvW4xxf5h9f+/Y882Sf7Gq/cG5hHmg6VmD2HB8xikv6M7R49tVSVTBfNUKkyZVkpiwbmBi0T01U1TEszaiVPQcHE/XpPh5apEdjognpC8CUHbtfXUlrf/eiyYyhb41MfuuBT4nRGGijMx/TdWojXfssaXUFqcN393h1vqXhmmPHpUNeEaT0MIPlvKDL173DdRgAgCUJAoB7/JYIEACY78zGQnbCGZyhAPt0S1ueTfhwH6y5UuR+k4867dCXqxrXAnHjvtqKdYlf8EiW3xJahwtSEOy2yriR+xPdOFlXh9k6DalLlaCL9KVVXOZmQSXGMxOpB9bRe47WwrZcdTzomfD0si0j3l1/HfPU/7uBu1UK0H/Qb+wNuTEHJ3OCpOVgdJiiDXOpgRzXmliIOnTA0b9XByQAjMg==","GQwYgHPNNUsDsyqsd0b5i8xMkuHTI15Mk1jP+3r5Z9snH6a9pwaTQAPdyqdr7/XS7vELwWiInWCC3CC4p3fmD1QP5ojZrKjiXJ5H/wK8yVDM50zvzOigIfNIpak8wShmlS5ar5nNpIen2t88sY2XrD6qIJ4KbNzvgqV8X1QKTBGVk+Q42PCLnKN2bxfE0b9UcqkzTJVsblv3G76twGT5Cds00vgGAPKJzo+xSRMphnC/sEoaZHC0gAis4xDd3LFUMjXnybeamzY6QENE8VtWAGxBEr6OQ/dijzVSvV4E+3GkV7AcOeGfaIiJRSgyCZ0bWsGtxvHa39Ai99U5TbgN0ZVIW5PLtTADQf5kiUmJsTZKyTVG0QtS/lcJhkH4yzRw0/OHP/jn+SSarO7gUOy00k/3tMAs3Ztr7GVfA0vtopmuqe1BWPN3uBn4mKbUYs2ixNsRCoX/9YJyX9dlhlRdeXcJrxLWMbrQ8RqI2fKVe74gBw7q42E2iERVCoLVgxdZaIP1srW+IvMWe0apzVFGe6Pq4dfJZ2qlpO47nA==","xGOUuMK0uAagtUPggL9wOfRY14d50UEHdMIeBvrRxhVZ/4lI3HAtxmjpq04pGudZldBAw6lp7yen7t6kLTtdzXti7yQqFzSQOkuiAKHUjPI9M/c5oi9voKBzgfCPDw9OQpXPhTBZxevL63I2P6hQMYmPiDOyv6GU5fPQJZeoPglPsKQZ6qXWjG5LTN+KvYENMOqngT5phMcox7BVc92gEfSY9JPuIbJlayyTnqrW5UdvR8QwZCx4LdPw3MKpuytzOmAd91Rm65l2fo/++YSPvwRM2iUU/fFN/4SAZ3ExZqyBdxHPvoPQ0JeKpC+ClP71ELSrAzkE6cnvugQZedErIhNyQ9GEfmdLamAsD7+a4ImeZGO1Y9oWSNJuPEj+MK48d0nP9WztfobwR8GTj0Li0s/HhbYDfQtFvGLQiYW20qvK9sFJTflYdOzC6WQjyUkw0I987UbZ7RyBIQSV/IeNIw0HWYklfEIqXTwqCqsgn+pE/ZX61vR7h0+VytM="],["ZtQdCrVwi18TuJIRVETj/5XeZrsB13K7yIaxsnosaTqzX+usL/vqkGUoht4tyY6e3lWhUvFWLc5hnKRhz/WGleqa1ACilFhp0quOSaGm/VZqAwQini3yP/mGdB1WPoar8Qh2tWiGROTyeVRVj/48klfRHNSQWWv5Uus8+Flp0uFLPEnbO4VlmhIQUf+EWv7ODTzDom/lN86WSv05CdQN8aYcuU3F7rste724+QJ3EBPuR+bdHS01QDOmRZTc3YNIJCgIQU1rbmBwqQNZmUXGrX0scLyPCAzib0Ihbq43ORnqnkGkiKZFFaq0wezWw890kBaPnO12PYwWgpEKNKW2mVjxUnMP21P8Jl6QQeJRHFahshg43v1PScM+lABHG/8jOTO94L2vXXKN8pm9THWDmZPjOUgPPnyEld/yRRlq/ER1m5gwoD9xTooWOAXwfqdg4nSD4vgDKcCgO9y85ugRfYA/T0l8we8vgA0lNy8KhZ1mOcAoCEHqBcRH8i46/+SiHD/2dHiOQsafbJbvsi3plf2acBQZVQnVpEn4CZQS75b4hyk8zXyCgjDDvdqmExrsKEkLpY5IgaVDnMHLRh6mY+pflriKQnW/wBpNjg==","UHHgjV4Cyq62wiXMfvhY5Wn8I1BUyoONFIYE+uMSCRxO7k/eSbPSZphrjZq5CIrozi5ruayj30+fyImwOJfcaz9qisRQM2FthSShoytfkaT5CqMmgTwrxJf0a8BdU1qhzoLiedPvXG3AkUbeB5JsFFHbPnC8Vqg6Egf2eTFlLyBN4cJjggq018FBY1N90uUoSsDpq4CgoujOQtTVohm+FpDNuKq1Gv+t6pXKuMHrtZj44guwpRj76qVvACY+eOcEV9Lr8EQyuFQdkCtnhWnjSwlX/MFEkGgGTx6g74MbCQ4Z20jHp2MBqozaQTGefZsTu4OAm6IuxojCgnjKQEExUY0vi+koubmss1w6ZFKHiuyRP84acVqKbxps+4WXmiGHSr9JSr6HxkRoLiLNhi5Qg1iJJ8Xe+zlC/hUU4akZugLU2wjyWmHzhKXhoFahpwtgGZ9SV4BLzQaeXU8GYxGrmIH1bkVc0jw+U07vchjtvlShCUQCog3l0wwyCCVM3f3qT5XEmc48JyM7v2lVDQOEnw2q1WRruR6zphFQ4Q==","PoXldcoZ1O4MFUdaHuVhu3rMNKEvVoPU+PoA9lmRzSDZ/Av3l2VZI4GgLGoxtPozRmHRNfI17pku3gXg8kFIocCKzsFS6fxvInpTQ0StZXdt9Ue88E9RNgfaLE03tammZKLmfpbc7Gh5T8C7JhnquwcuOs9Re6zwFKY4XhNzK3XC7jwz6MiKkz2V7cXSd9dum4j42w/ux1/Fs6HscDjKY64RD7q17lq/XgqkKDBNxVSMqy07pQjWSr/+d47bGupEmBFXr3YdvYoTrVqcfMOU73UlG29eUYCCNo7OmWlvVOKYoxWZSwYc54KdwbzWmNqMDOdFSOWYF9a8bqgUFhZOngnFNEktbYEpPGMDy9XR/pHOCswqMB9531FDeD6Vhooq+x2ay7TuNGRroKBNrMsQb/28WMcyq+h+116WaWv4HTvOvhLlDGJElGvD2CA6Op+oeRPiOOuB4pVCYQG6aAECRqP0AtC1+H5Tv/RPU1nj8c7ccke9RQtDxxGIDW0="],["FiaTRFkRQs0GRTnTHQSt23z3BpXxhLh1XG2HZiqO0XneKn4ViG2E/tLJsmB0w0nasrsMSMR14v2iFAvRZ59lUYhePxb4Fb6xW8T5ddL2u88FAk36Ywx4lTMYzHBPVrvc+DapDLszsyU+uIzxpXp11yCGCvIXQehrzvCRsTiwmYfPOlTNb3H25EqymIH3KJIp9NRGAJzTr5sXoovIdXbW07gxx/rn+3orPjHRzFosy8ASCS84E0HXFRs9BvhZf5zG8VotSiNEpU0wCmzaiE+7l3V13FCjvj0nVPPoCzeGtV2RFnvudr3NskPZQaJeU1qvkVXMCasKo5C74E23SP06KPHL1KKx4y+GPB0wiSvvjZExPZfUu+Q4fTSj0wyAyAZQDyc4unAlIaMQX6N0FMpNArvHCBM58C9K5W8qXBzsyhOzpknpnJU8wtQDkPQui9kCtX0C+6HpoxB63foaFiXI4e5tcuMywTiFOZpaezE7K53J+vczjXOSfRI2alXd/gtwJ8sF+swnXq42etCkNX9guaX/Xx0X6LdrWSYFMslQP8v6Tng+VSnvUxGOMR2dgJuLmVfqEw1GqyOEEptPZZ8EI6lU0IkcW6OuRQkCyw==","4y2qyTUlUZvPTV3c5NguKeLV537i7WdmCKnkxF+W6JLU9fSZLI351AcHVE4TrG3YymOzVPq3lQA/6AasuedoNJ/QW36yaL/hDzEfG0QcTViI+qPtwS/Vw9dKkl7T6posZTo62spoIf+5dboHp21NogKMj45EZaE+FPNSXp3g11KGW0auBNkmj7pZqE5hHRlWVCcsMlVrqmDJtpeeOX+z4K1HjhTjPPJbcxqVua88uc0B6K9iZV8QHHgxAG0nohNH80f8U2hoc+3TTF2ES6fnD7sTG+R7NSXOfKhGlyyIxRTftjuPWO0ZHcAmPpulkE4RQmOFw8h4wVoQ+bz1VJ7hktkSqoXuvgK9N6tU5Bakdym2iEQFKgZfsUMJhbtvHtcbrWF2KuDoxpy7k8rmnHxV63reziSbBPbSJDo93W1Gkxqtu4XQ60cYhB70OYZBg7pSU4p3y46dKesHI/t3Z7Bg/TWrGW5fggOg53/39ZckzUgbPUCv0ewWKw2xIDTz66jlTS3riT8flWgzLIuuIMT+k4rO1og0f+XKKwG4lg==","Mivsc6PjXooqZ1OL5TZsXzXdYebymuZubaaHsJVad+a5AHP/Juu2co5dkb5F+hh8Ol44wZiQQZZ3PIRJBrs6Ui9nWO7CzHTOR7dWg4Qb1Q0CF54uWz7sUFIVIk41gv2vDPCga4tmr+ceECHoFbaTqze05pJlXcUM0zcsVA9heX8vsqsu+4L+DU95q8G/vwbUI7WyaSGfGstzrTYdPOdaCHucM3F9ZyNWsiYivSU25rvlXvNqbjTDYjyNc5WEMes9NX/wZhgxGzj2u4zlDUlohRwf9VLhuFhzV0Z39VDWQ8a2AIxIPr+hVgjlKbj18pi2he4sLAHDYpUqL45zBLeHHoJ3a2ZIr/iNOiDe35WjAcJsIbuOKrDapu9UTcVs0ddoJPYNNDz79HFVs/LOYg0m34+PGq6bvib/AP6swFI7PsJ5PniTPXtZrspBuiGGN6SVBPiik5WDV1aFeWkG7eKqIRnO4+mtlQQfDtyWqiLWtfGvC/Hsupwhw8Zuv2I="]];
                        }else if($packet_id == 31 || $packet_id == 42){
                            $lines = 25;
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
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', $betline * $originalbet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lines);
                                $slotSettings->SetBet();    
                                $slotSettings->SetBalance(-1 * ($betline * $lines * $originalbet), $slotEvent['slotEvent']);
                                $_sum = ($betline * $lines * $originalbet) / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '568' . substr($roundstr, 3, 9);
                                $slotSettings->SetGameData($slotSettings->slotId . 'GamePlaySerialNumber', $roundstr);
                            }
                            
                            $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet);
                            $result_val['EmulatorType'] = $emulatorType;                            
                            $slotSettings->SaveGameData();
                        }else if($packet_id == 32 || $packet_id == 41){
                            $result_val['ErrorCode'] = 0;
                            if($packet_id == 41){
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance() - $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'));
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                                $result_val['PlayerBet'] = $betline;
                                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                                $result_val['AccumlateWinAmt'] = floor(($stack['AccumlateWinAmt'] / $originalbet * $betline) + 0.05);
                                $result_val['AccumlateJPAmt'] = 0;
                                $result_val['ScatterPayFromBaseGame'] = ($stack['ScatterPayFromBaseGame'] / $originalbet * $betline);
                                $result_val['MaxRound'] = $stack['MaxRound'];
                                $result_val['AwardRound'] = $stack['AwardRound'];
                                $result_val['CurrentRound'] = $stack['CurrentRound'];
                                $result_val['MaxSpin'] = $stack['MaxSpin'];
                                $result_val['AwardSpinTimes'] = $stack['AwardSpinTimes'];
                                $result_val['Multiple'] = 0;
                                $result_val['GameExtraData'] = $stack['GameExtraData'];
                            }else{
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $result_val['IsAllowFreeHand'] = false;
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
                    $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                        $lines = 25;
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
                            if($slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') == 1){
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            }
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
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, $betline * $lines * $originalbet, $lines);
            $winType = $_spinSettings[0];
            $_winAvaliableMoney = $_spinSettings[1];
            // $winType = 'bonus';
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);

            if($slotEvent == 'freespin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
            }else{
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, $betline * $lines * $originalbet);
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
            $totalWin = 0;
            if(isset($stack['GamePlaySerialNumber'])){
                $stack['GamePlaySerialNumber'] = $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber');
            }
          
                
                if(isset($stack['BaseWin']) && $stack['BaseWin'] > 0){
                
                    $stack['BaseWin'] = floor(($stack['BaseWin'] / $originalbet * $betline) + 0.05);
                }
                if(isset($stack['TotalWin']) && $stack['TotalWin'] > 0){
                    
                    $stack['TotalWin'] = floor(($stack['TotalWin'] / $originalbet * $betline) + 0.05);
                    $totalWin = floor($stack['TotalWin'] / $this->demon + 0.05);
                }
            
            
            
            
            if(isset($stack['AccumlateWinAmt']) && $stack['AccumlateWinAmt'] > 0){
                $stack['AccumlateWinAmt'] = floor(($stack['AccumlateWinAmt'] / $originalbet * ($betline)) + 0.05) ;
            }
            if(isset($stack['AccumlateJPAmt']) && $stack['AccumlateJPAmt'] > 0){
                $stack['AccumlateJPAmt'] = floor(($stack['AccumlateJPAmt'] / $originalbet * ($betline)) + 0.05);
            }
            if(isset($stack['ScatterPayFromBaseGame']) && $stack['ScatterPayFromBaseGame'] > 0){
                $stack['ScatterPayFromBaseGame'] = floor(($stack['ScatterPayFromBaseGame'] / $originalbet * ($betline)) + 0.05);
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
                        $value['LinePrize'] = floor(($value['LinePrize'] / $originalbet * $betline) + 0.05);
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
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes){
                    if(($stack['AwardRound'] == $stack['CurrentRound']) && ($stack['RetriggerAddSpins'] == 0)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                        $isState = true;
                    }
                } 
            }
            

            $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
            if($isState == true){
                $slotSettings->SaveLogReport(json_encode($gamelog), ($betline) * $lines * $originalbet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
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
                $lineData['line_prize']         = floor($outWinLine['LinePrize'] + 0.05);
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
                $sub_log['win']                 = $result_val['TotalWin'] / $this->demon;
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
                $bet_action['amount']           = floor(($betline) * 2 * $lines  + 0.05);
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
                $wager['game_id']               = 115;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = floor(($betline) * 2 * $lines + 0.05);
                $wager['play_denom']            = 1000;
                $wager['bet_multiple']          = floor($betline + 0.05);
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

<?php 
namespace VanguardLTE\Games\FaCaiShen2CQ9
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
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 14}],"msg": null}');
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
                            $result_val['DenomMultiple'] = $initDenom * $this->demon;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = [
                                "g" => "160",
                                "s" => "5.27.1.0",
                                "l" => "2.4.32.1",
                                "si" => "35"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 3;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["8DHw6FHHIbG8cClUbET1wuV0pIdId2LNcxOwwoxIwdKTNEiR5FdybUUne8Su1Fc7EH9RJfKrZpShTL5XKaMXhuTNrZKg2zNikroVSErIOnDRi+syU1dsPcMZKS/T476Y58MX8KHdfb4HW75oPq8UXDKFSzaWC6FcoG8HjsnKgjKLpSTM+2K3KxI3F7bCudi6+38I+4HANSGf2m+Y4ZZ34K25PfJF4YanHK3/0k72nPzYMCofaWSI6HmPVVzG31uXJxWR7KpOBssJF4JUiu2Ml/hZeNWboDeAKPlIpPxSrkFCRLYSlJGWOpUTL24=","ZFdD733KcGx0MdGkT2zeO6OxLDAeDoIPDqLMgVtjW1vdaA4YAkNgmOUSvnZYkrOwurb0EjHqEE+GDTtZYylkyE37Z8EDJoq/MBhQyFtzFta4fHDLIwzn8Vixvo4N1SIwK/1Tq8yBknqOC9DIOYzPbVPWJhIy782aeW+swETeGJBufnLjmA+FTtMwKYiLhMz2opdXy6Jpw76jcveQhxH9a4t1t3U9lDRTwp+d8/cZ6Y4vPUWgcin6Zbu3Aqz/BYyyvbJDE0Nh8Wtn/qGSkrZnhHqMlDIUF2Orwjn2d+Jc6RUF9mj9deHzLfRcQQ3jWOEmUWbmjFdeQWi3kUrFiHSyCl11oYMSqsmDIYYvUyz5HnVC6DdpRs22KoT+O1s=","fbiAoKxfMcn7nCleoNH6x8zzE/S7gvGTBP2exbxcg6huHdzlkA5X8ML8/pN0cOf3kjUG9FfQYNj3RaETohq2NXWKDvwLHOmOPlH0dmGcrSmzoQJyf2PHHP/lPNt1LuWK/oo0EMis1LcnvQYzeqjsq4PSHdP+TMExXhU0+9ctmpjIqE9+SpqHj3imqtOGJhcC/hrMkY7USCY77x4IC64vvtXdbd/9CE6D+akZvgGUIQkNkhGKI8NjRk43uDf2P8q8QRvYS+i8ZeEe7YeTJot6+0IryrS8KV0Qjj27B2beAKVx/lLns1xS3pyVy3ds1yJbpSXyPskm4ghNY7dCfJFjhrSBP+dS7t5wd/kSg5CNu92FYaA8SU8f52D93DI=","8aABwAULUiqZTJLcnNM9N3f6km18rmT1MjqfOwgzxRdiHb5XwXa6YprWPGSLjXWNC1L/hm3dKhy+M45zBSy4KqjdmqgabSFfe1CGPrnLnZycAehJRdLq57v42nQELCmSWjDm/mDoALWKCFNYqxGmzgKxMng12t3V40h1Np3Z5YOBt28NSoZUfaWVUYSRtaczqlLsKTlsSE1sn14LEf6DHEJkP0iufBpEXDPFUZpmajRKshRuP3EMPI+sbCV/Pu4poW97vD1E+c3KXVI3NQFoHWEjv+QHeVg+0FgjE7AEEfJ7H9DneyHUrglwkak=","FzOvJJf8hJ9dbLuG1/qiiA7SjXarYgGFnlMXEqagWIzqUmUtw+9hfx2Lr3MEO9ChNv2e5xOZIFEWOzAqOJkyB/A6E9CkkN1xBpomgi23myb77tbb43CGVvDNX9p2y8s2BdWz2nPZSvxbPMWnEgSYlVYtRM5WcXPwLTXgDOwz/OxJMFrUwe248ZgC3oVt+1pBM8DU19AKXPAxuEGCBrlaRKtiS4DGZqUuXDUd8L643VApfs/ojFBb5uL9Ws57E5w6giAJ/lEcNLxZNZ++7OrYKPLV+5eIc+XfB6BdvW4dJbLRIHmmE0zeZapv/mHHlaDmyEF0FQ2D+tSjpVRtabxBIZ8knvOLEdFqRlpsHQ=="],["yB1bdKKaMP50MLKm5VK6T/4xIWAkRdHrldDxLbYgBdUz4S288VjZhdxOpNsCY5s4u4joiL90vam85LtQA0i+b9hloVV0I7gYlxO7APN4zT2wuNX46m38wcG9OpW3EIOVcsru9KlfWI6sgHrc6Cw1sd+n8bCSqxH+RrKPuPSru/09ZQDM++IFKQO3mEgctJ5nffTP8OQo0U8UtVJCk3o6PEpBDGjLmLGG/wUaCJd8IlTHnIH6L4dXGY112AuCW9x4Gu7gAENYpIDcANPDHvBiJfnDZ/3SIo/SAR4tGM0+xv2IY4HF2BtuxVe8/E4=","g9cT5LoGXkldAnyzjVgurOk5Wr65fWCnp/sJ7EqbrSMJwOtDvqair+AqB2aErXxo92C6+DsZDasjaByYiYsovClO3j+lmoje+ISh9ndJ1aOR/aszChjMb3PoM4UfGhmVlEFvrGkMdM0IZFRcCd5uUJWvWVhUkVf/CX7HKv6iUJp9TzwIuPE/2mvQugvD6R46YU5UhBu/lbizzvnFTtozPxRajvzwZrNVGPqKUEAEFiQZ6B1LCoXl8GN3Hq9ZYkbCf1OYYHC5bnBVCziy7LQOT5mdkneD7ltPuMmsKhbM1BicTrQ5Vpx8CV5v7k9Ac14R7qj7cp6vgXQ/ex6LOl764FNCqR9tfCob56A9hw==","F5w3JijfAXs3uhCZh+VuOPx2JUDhv0MpwpNOPinWCGIcUBpMpxHTjt7hmYeFCQoUHeHfEH47Yt+QcJbqblk3cuYZBN8p/cLw8WD+VdINmt0JFQzFr9jc1q2JO2encET1mlZJhc9CMwb8ILpTMU6YCzKt7Hk2OeBcJ3K6V1CQ1SIusDGbnZccUDBPy1TXuTW/9/kXlFPB22EJgbYMKHtql/8pa/AQ86uetr6BArp/KHjtJrlh7JpjuTKedYd642YxWKpJnr1dPRgmP/aCNK1a+lK3q1YGh04YSRH9K5wKqMYZsDdWu44bUvy1P+kC9VZLdlOTYO18TXgMrp/uLWCNZ2NWUKpAZQxyFCYsRibAsLo2CgqJZ5PB2u24BI4=","P6gwkhmBQcNj5OIzwar9r8lxl6pbea44dJ68rf1xFbtIM2fYKYVcAMARVAqeBp2gqSS/yM3lATW8qKZk0qsWRivWBi7lPIe5SBn+AywJEJJqYN4HTj5U/tgLGOUhuKlFn1atlhmcam9QB+YykPFaFiQ6SoymhnthSzzJT+UsQQxGiwiCdRMHHyI3AR1E6WkPEjEjK3Pcy6tFLcEu61OTyy30MeaB6IfuKFxpXipJht1c7o8MEU0kEIumQrjtlK97EUJWHRxjco0z02H+/NTIuqM12gffvnMwr6H3V64qPN5vjlXcpCYD+GsNDGc=","pYItdPz53AXRLVjXQ5BgKMThEhBY/39C4l2YZnrUULmJtpjiWpG1hAP9+0zhz/hX7G1F+WDhTlmbba1UpYDxLMle03AeWyJUhTPtqUBv8HuF2ybopCOHL6QJYVf2UQgFLQdYL90Io5Zg3ei3Kv7ySApSJrqhaTc279hS/fkXPaOTXVwTP7qzRkB0p+7mH8NJ3G45UJyqt97sKuOOODNnGhhp0xGxWTnTaP9RHfH2zeNHtG4fhwcdkZNjexiiu7f9rces21z+uIB3qXIwe5syLUXmIaUcLt9Or2zeHzYO+uQyUg1WWNaIPtsKX8Z60ol3kq/d0hr6z6uREBMNdluyfyyET6mXLvixqwV7oA=="],["r6e7n0WepXpslh6cVj7C0h7y8KwgL7ABvGHY9qSbH5OA7fVMyiVdx2wpgkbySo2xBr+Q7jBY2SdwoN2EHXm20NXePSxIDRUFD5NKg+ikfKm7vioKX7vAbrOh4GUSaFQ585Lp1COc/sJyicYSOYW8pPUJD7pB57/vtGk2Yg4kMDPw+7uSNVV/5BdzlG12iFVTHrYrggrHwgS3yfKHcwdg8gE3nfj3GEnqTlZDvWiJiMd6HuHNfwohq9rRmOIcKbHXfajt25pb45FhsjTvjL+jd3UfFNpSwejImAX2STP0OC2m9ciGd66wFD93yyM=","fwVkaQhdeaeoYM0NrVda9WJucDP4OOMPH6UgJBU8lbFJN1HR/MeX4788MSQXMdKxdVyinMkFe/AuabsQ/DGGUZDExbGHcOUwnUY5Eov4aVtDkBH2eGDWP08Qbsn1lEXG4SRq8bJgYCSUmVK4HvMVvCNMNqWTgtM8SNUfrHUDuiZHF7jq9b57KM41VHAxDnjyfOaJDdKyAmAAlCVpUAHnpQEUW458dd2lXoUrmvUp21x4zHf4lYFCJDYbRSkLN2yAUOSZbjP9Ze+qvaJZfAI+Sd6cyk5l8UL4rpYxzSExy/56PTfTumkdM3Hj1uI70b3lIZEbke10uHFAdRvPDTMW30MDu7hfpE/Ad0Ceug==","KiMY2NWImheB5IkA6pFugZDr90WVLfsixHXTRGFn/asHmj/z5CqjjDQK+op+OZbbPLLicaMLSqGkAmGGjMUavFqpda7Me65U5CISEMCFby4o+jO4jjVdfnoX2OFFdCW5ASYTVRiFQ4LDJMQaEZmbWZn+4u2vHI8TONtqK88ymFGkTp8GZQ2KHzqyLKZOyB5ZHBkLo5juxMeIuGbRBGt/EdxxGedzYOD/OY23cVE5H8e5aGtBYnFSwugOPsZhLv8czO/iebfw7XiA4WXiG5J2F4XM+cSEpfcldQ5Rx6ePhqA05kItEe7DuNgnJuJuC0mIMdERHXUULq9udSbc/PlNDoQ5M/BLblUohN1XAw==","tgjuG3YI3hMHSxTNpz+0m5EzAW63as2ToQ5Rbx2id00jaIl5L2fRCvc/EV7T8gS0deAi9Rp+GwLUwke8pmEnxpN32piBv7Tio01/qgIgBxNe7Qd+QlDTlTcijJ8lo4oYDnOIGFciDCW9bJir+ntlWhSi+dl3q6ANR8qzOnK4enA59h+R7T0dMDwErsL3nxB94qPC5PEc3mPbyQ8wFmAv5mzZGNLHdfsXW+wZMHdW8EfM957JhFhck2u7MfiaCjXf6/Ce2vVO+C0HMvqisObkYJ/TgsccN/SVmwSqXnOjpIJEQ3TkrPjnaCyYrgM=","nzFm8z7S665c39536ZWzaOP2gxRvyUKi1nzRPmplkX6c9SVsFAktZ6sjZhPAHk8qfx82XOSsKMIYfohbsMXH7jsCDN/ydoQhxKyJFLPfJeuNbvKezX3IhKQvmGt8r3roEHAGUe8czWeEeuSvv6ZJ+U6L/75gFAWSLHOogQaejFXNMsPe2p7uNZUhL1784y43TvM/fdVKlg9kk8UP8x/VSiJi7s9lpNWVprXJl2jYTWD37EldQGRDW+6z8qhcpeSZfVxauekhdU8MnHfoavsvgj3gV8oAmqvob5FZ5hpKGeanHdwkm2QjQeEfFxE1ALUOo/hjIzHAaHhM3R1ARnybUd09x+cz56RY0fGE+w=="]];
                            $result_val['FGStripCount'] = 3;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["6AcXTg1TzwzHKDHJke3zYI/syn/RYn22j06Mgh7u1+7/VfoyMhm9E+D8PZXs5kNxyRZVhThmnfQBXxBqfqu8J1ZmULW0KZtLzIjyHAqpBQi+rQ9+ulESwAkNUbEZXNMrEzKh1x2s25VnxCUfqqOcwsGkyIVxGCwznyVqwFazF0v8sqW5ZW6Ca6sso2dwG1bu0uLasSrSzFDTHAtgOoFEUkr8+6P5JX82BO293sigbcVVE9xDHRLNOA83zwD87kL/Xp2K/huBhffGpQUzfzVIMHtSj/e10dOicIxv4pgoBRsaYzk6l1KIf2fBnFhTqZI7sDX5FprSbulBUigHv6k0uI/X9YxdRYuNJiBt8w==","CaI5XLF3nY8FjI44OZDS2IbMSHe8GoJKF7MnkXFE8MfRFCXneFfhjCcQDrJql1/DlJ69jHRdwNU6NzTOJAWn57jwHUBuTKZO9emo/KG2fttGoaV17BghFqPKSkDo1XniO4laqz8wJBpIsWzE803F+XGdAmLXHn1wKQHi/tOfhgG+hweeQIWn0obTS5sVm/4AGtVWY7PE7idAlvqo0tgs8h0cG7cv4gX/ef09KTsqs09J73TYSZgxUyP6RtxxmRUs+OcCo/627YQjrz19gDJbCOxkcHsUirZzNbpaM+hVhUZXsG5il5Z3StWGvCo=","InLVcT1i4HgeCCSrDfWA4kVVw0j19BfWh+5wgUVzWDFbLV5prCWk3Kk3NpyqjiexJ2vC+igViTp6XDHuUDFnir0zt8sIarLngMjtuUzolkTbmztsF8N8d7l8f+Pjx92UWdCMElO760aMfprsNJizIjUZctkMFVpE3KjkQIRHr2Y+pai+KC2hP2VushGx1fzZxpvTik6oKeldUzlmeI8fccHM15X2ISEMtPdigyACofTGqpZCqyQw/mhb2SJ/lMw19pUCF5URaajvlaWD58gHI3nrORhMc6TkZ5ClCKs9TCX2Ay25seqW1m8JJcA80asb8aOY1ZyRcaX+/4Xm","cZTImdcL9OMy11vnTMKb+jUBlsM0u2mfUWPQcC/D6BfnZED9C+ALr0eAlpXIrfdf+bOcsTW2IqmEY1QScPy4pqEHKCIWobufx+DxFU43PcXUHx555p/9Wf0m57OG2jyuZ0LwfvNL64MP3N+t6ldUwP5NOspDbpNr4KiasMa4Na+z3jup+IskkXpTInkOoh59mmD2lpJdn6gp+8Eq1JE6OI3vxX65q3a85f4qVnBxMLgg9YWwz+FZqdPOKLWAbBz4qYV+Ix1Og5z2Hr/NaPJpbd7LYp+kumM6DF9WWg==","C7JzqcLjo2CqWXqOLz+cdk9OME4y8ZBLO+8d0+wLeu7mV8m7u7L4StwqR82qAXnkSJl9c6B3iq/FW/V43/AdX4dExa8fnVLu71BiunOMQFj36RtTidoJhDtdGoGVU441mi8ZgQnydpZ4X60Bx4VOpBbbZscregwX+wnZBFSjljTN/TGJpNq/ZYaxfwCkMaW0Agr9uiKT+gJEGa043Ug7N2MpTiqDoWuBsxGFr6PyUufuEpF2nM110fcZdEcrzOjXKgcmx0kX6rQmJLrZws8ntPVyxhC+A9iV/eFOGQ=="],["kAsqZIBm8XfQO5s5JzFPuASnw7bFqUyGTFDUv94LCPQLyK94GH8v+pme182dMSeaRDB8O1sexmsqZIoXFvOgt3+He48+bUnIeWSDncSXGhh8MAGEMGTwE1aq6e/qzBAvWyugYyJ/jN4ymY15R59qiwO8kca45f9zJvDrGq1+S/0q6WKylNk3/csKHjH5J+Sq9FoRCbdc2+zEF2Xce4fCUOoZf8drpBeiNybXXtC5OXdm1Jzy2LRYmVZg25w2ofYK9bZ4/EO50v6z0fLYbzrSqoX4T1VHqzB7eNtMa+hqo2Pnew30LpQ9ShsNCm1Z2fidxOkQ6CtzhoWDolCf66BxH5ToP+mknyI3t/tXBg==","TUz3b68WpBFTXgX6cEyWG7g1mQqNALgD+FBGWyqyEqF3SZ6f+knodFEAVXHuEfZsVIC0PmYFv35C/4EaPgXwLDYHQs6QunuznivZvv8UkZmvIaiXxPIwrEevUATBUYF44gFk3Ek6bbC7uDOlRuP7oaHZ0Vg6YndeRcphJpYTw7OwXI+gj+WDMBYUJ+M4nQNKveGEHrcq24LxH0vbtyx4DXzyfM4nlpKh1RMcCPOCXoOoO0Yk8xyeQlJzc/CswvAnGpHNPD2Zcja6wWcA7lnytPi3IGI65klzSNEkfgHePOu6Xa1wCa0LL7JvoTY=","O77ucVmlqWX7bcRp45xOcRnSHa7HCVAaNm54bIgJQvw1UmVqtuFYL1y1A5MzazXKzZwXzuIDWv8wablir5MJh1TCOKgJhzdf49eo1mh03d2vw2+O0Lj/oxx42SaUzt7ySOEtkANBwp5hUqi4MCvYWxhPr1ChXSqkhuaU6qRn/1GrR0vkT9NzrpRL+ABY9HdKdUMoTMcCqPL5RSxVO7WLhqt0y96+8xENX9Wo2RhC/+HM63KYu2+6Dq3HwuwQecdnWHiY/jCYdVjHUiS+eHO1L154HHb0gu6J55O58R4v6kRS+yeHLqRcAcWMveYLLAsrZ1VJ74u5KIUAjYi9","hoaeCfRp7qeqW8rvN2iXqFI1O+/UZtx+iiSy0mUu1gHILGmXG9msr2Ja/WDgVfWYqG0JbX4bTU5s0KwyPvuh3pxmOBAMOVqIjUTIQPIZGT+16dHIBCP+QiPNXzLxwXAgqh5F/v0Xm4ePM1j2Qeiq49wrwgJNnO7uNBastocTE4hwDfXjyUBOUm+Ewh0pG6K8x5ZEKfrqw5HdHZ/k5pA02g95UNsutRUhgXVBoz2Cr82Dlet1mxfhEXgKLeZijOt+KSrw93z2jXIKGYKXfbJKraRp0bBmkjTo7+lFlA==","KlXERTfEmP5QJbkqKBuJ8QVEQu0INZzE4eBZF89l+sD7ffpTETgECs5Xlg42eiVx/QoFDYA9SFSjWEM+KgJhh8bYuAL/clDal900xe+hJoiYoM+kjEkwMDivNvzK/kxGYZV6rgSetTpI7n+gC3EEif9p72+ZdWq/sxurRs7z0I83Ok4YOu5X52spfrI7a9sSR0Ig+kMkb0J0UhLMxDuOGEg3/8FcfRAx+zj/HYb8EzcH6D76JOanQ3qPWpphtZHQbw0I5QjnMtUk48YXIRZIoSqFtndJVku0rKOWBw=="],["OIBB9f9gVMD0yvo5M152uGP/kcc+Bj8VAJUQqXSgGAHa24bIyNPPpbFlggfdnXYnoOZ8cBQYMqjCrkwAcVyrYHeslu6ssLJRiY5RSBSrls/AYyOr2+2Olqztp/SrTDoLzwuDpxx7F2hC0HFJOFrMTbkVXrlLx5w9JQclj1RkTn3Khp+hgXWRJVqwei+ZdW/y5SbTeWuxWmz8WVHgyou10vM3hhlCvNxJ/PG4C1371vX5oBujiNVdV2jwMimoIWbnWYzdg9+hVYz6G9HUFSU+ZA9hROPugL780jC0A2ELBfFHlmop3+Fy5UJy+piRiEfXt1+heMEKIlrD0KTaXIuvoaQvBrQ5Se+QDUEVsA==","CFAlb6cmytPv7vi2DdkOxU13riS3ugOqPN1OkdAc4hzgfDh3673N8Z74O7YKIzSESj7xvqKqRwHAQyqHkX7xHOZoh67j87UOhV3EoAt/ibiVw+wZH4oX7gcl9/o0skrsNqRgUxRdaP1xFsJUJ/cqL5bY5XAggJhgU71vUdEgQNM+Zt73KTgHb6IT0AZYeirl9Ha+FLDfKOBG+LBQsCXsTbbqFqbKDchuQOH79XmNp6dy2oMQ/whTB6pX+IUJikDUzlK/k6DZ4/V1bXxZy5RxjgXyFf4LIOHL6gRxTgPpJ4EAmcr45FpSGiJcW3Y=","8XzEDulH8Ehm8S566VF6lzqptd+7ifr3/tTKliTMGlGxLFSL2z+UKj+NTEpcHzyQwEE4jCr9UAGTBE8O9tA+vIKQwCA/id/WbMOB2H3WZPIb62qWt6oID3ZFTz5yAGiu+2mkK9lrWX+rc2dVykO1M0g3Q7d+Po/nAhZENgypXB55q3uMh4mENsWY4Sm/ripmgWCIeVtLRtbc94Ygdx0xbtaTAqEoI0Jgt8Y0fFnTq1+Reheh1bosn0GAFaICcCYdruEP49Zl9jdVicjNXzWwx4PuBzFsYHo4kMC+Tz+67TFQ4MvEzj6HLQKteYqris+Y5UjcfaFCpyFEoqQH","VxXGNxeHCrCbVlPSWI/Rph5yGYv1cTnp6E9VzR94QQcV/CnB0xq9+31HsIULqVh7nyNNCFtSKkirlmDJMK4WCLItE742IKM2g0arCEXfSPf67CLvGFzDgAMEQD9TUeWnhleyQXgvjkUi4w671I20WcmZqnZtbegeNc/tGHHvW3eHfsViNV2wV+YPl0C8aqcbRDeV0gj3YV1EoI3EyELeh+Ber4WUdSuW2T4HppePmE6S4KReKb97K82DK0LfdEojfkLratOHhB6SQMIQNGddlYrDGONXRmtxVaOzJ9Kkx4voP9Z3sZaVuSwjTFk=","Y47LwpCnA8O4dK5uPm+BR2McUNUWcBP6IyTralg2h1u7ke011Pwq9i3arGdx/d/ot9ilBuv7cIq4gZwslqUDayHXNBtFA2rAwgbP2VnQygaRwN4W1zv0ByrhUxv32hdVzFTxseiw2wQz46jnEa5uGSCseX1QAH/XSwXK9U9ZY47+seJx35DLWAddwyvyeUQJNFCfF+xoV5cJqwKeECfUNuc4r2ObqiheX5NTCK1wlZ9Xc1ZhXf1omn2MviQijlDkz6voAL84y9012lCoeMUtHKv1tAH9d7KjI+DHOA=="]];
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
                                $roundstr = '539' . substr($roundstr, 3, 9);
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
                        $lines = 1;
                        $count = 0;
                        while($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                            $result_val = [];
                            $result_val['Type'] = 3;
                            $result_val['ID'] = 142;
                            $result_val['Version'] = 0;
                            $result_val['ErrorCode'] = 0;
                            $result_val['EmulatorType'] = 0;
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
                $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
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
                $slotSettings->SaveLogReport(json_encode($gamelog), ($betline / $this->demon) * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet'), $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
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
                $bet_action['amount']           = ($betline / $this->demon) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
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
                $wager['game_id']               = 160;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = ($betline / $this->demon) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
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

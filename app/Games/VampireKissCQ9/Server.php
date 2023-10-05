<?php 
namespace VanguardLTE\Games\VampireKissCQ9
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
                                array_push($betButtons, $slotSettings->Bet[$k] + 0);
                            }
                            $result_val['DenomDefine'] = $denomDefine;
                            $result_val['BetButton'] = $betButtons;
                            $result_val['DefaultDenomIdx'] = 3;
                            $result_val['MaxBet'] = 1200;
                            $result_val['MaxLine'] = 40;
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
                                "g" => "3",
                                "s" => "5.27.1.0",
                                "l" => "2.5.2.12",
                                "si" => "35"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 2;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["kJDSD2vFNSKzR7P5WJH8UvNLdkNc2zyfI1AwEF6jQb9T2l/tWW6nsWmE6O5MTw22oEjQITpSLc9ePuHjLOs/wnsF/RD1WMuEBQzmoCXt55VUFNw/yx7MvRkBeVa14Ko6zT10FWnpT+1R9wiby6JK2MsmdpRsg+eC0xEj3XohrP0ISqUCU8Zw0mRZ25snIcbmurFY4iiPa/nIDp9elhHFE99Lqamt4YrIE+pRrtxC8fGrlMwZySav7kH1/t16jzGij8BwERbrARyBbNaM","b9hfgoRWeF8HWW8YkG+vqNd0y3LsQVXGZTH36FDPji/wfv/bI6wMjFoBRK/YBu4W20rEDn3/FC9O9TP3N4hQgbXuOUWcCEDtPOUhuxPGrLohyVCfDCb0i1+JOSiJdXfy52uLeM6X5lJoRywpZ7ek0h0pxBlCja0C/J57L5mUz3/b9QrkE/KCkg6hYBjdBErPt37M0ecaB0UOQXJ2FdvOU/PUhCF0ImLeIIZgYTFZIV/qkA+61dRWrjl0eBskOXpUVI0G9XIIw+Cq3fxLoH2n3WmT3mHmx/bZd9JqHeuJy12rcp2iDdwHX7nUAp47FFx2r7GcjCAvpBX+1Fc5ZESnO0igb8w59HpWJC4tmiDU+iXh4YW94p0GGteeYuKuxcdj1iTSZf9c7ILLLKCL","ejtdsKB6EaqoPdhiaiYGbSFN4yXlE0fMc++yxxIdVuSTHGifUltfO0Sb6dUWZV36GpVDqDh6k1NPjU91eY4Ewyzbsa/QOsBUYcl8kMmsSEL09ep4sdQo+pHgFqR5TtWB1ePDfg0RTDbm0ergSFkPPvk7rx0XEcp8CZNSZQ2AAKPL7HuhmYOuVL4Z9e4RS+bY1TcHilWaFd8xnv5lDEwVC58OHFlh+MrMUbYjZHFsxxfJk95cd5qr1rQ4jvQ=","ldjlFY5FVDmnhfY63+m8g2m9ht35Dq+H9d8pM3b7d/ZLQjvYE6rttZat8fTelruk+tWpNxJd97eWGADjHkYz2/S41YF+SZgWmCFbcdBG9o6Q115+z9ViYC0e+IuJH9G20dSNwhS+4Fmz7GSOrgwB4jC2HVfHT9a0loYGcR/nv/IdSxtlJfR9cqK4F+w0PWo6YhbgmbtS5ZScnJ/vqnlAOQBHmHN7QdSMIeM3J7boDyakY3d/b/QsiHdpN5AXxCWldpJntbfCpFNz5v61wE+LiOJv5Ck76pC1kdxEMYuWDTfsHcH18hdTkfzX1hZJnHrGnBZIbZGSXHU/vnBP3Rw6tdIqh+hXTTTiIoJ4sA==","TdTooQapZE5rhhJLVpvEMrq9idIY6LhRJdpbhnqvp2u29lAa6ioLNdzk9Ej3xfyTc2Iw+4I35MFVWcu16VEsZdXYXQx88+sn4AuFZqNmOLNbsOwx5wYcrpVQiciXN/Qqdn2HcMJBYQnhsWqqPOhntB5lPbS1qjFz9+m9ZXeo2m0vHRcEj6XBsWHWwSmVKVLwkDdpwDIV0ibxNk4XW/XX8rR+iLCWQdTnimv+i7/zQ11QeBfwqoeQwvYE2paDGcp+FdIkP/LZBcSyj/W22weFWcnWpd9bElk3OTf6KSkWWH5Gr88UXs+iaP6Z0nE="],["rWPlMKrZfdYCvTrUYz+FJ/vSFzZ7v5Cj594dquIKzCwpnf3jGQxvL/yfsRuVGK8iOQPQA7qv6jbg8f8mBj41QIRC0MMqoROAF8IknHav1UUbfPYYD5xU+Db3B6qZa5pdpDM2blxv64iNaHeigGkUwdY6IbHftL55acPYg57ZisOQHe18HOKQoUoz5NUHNWD4TJz8mx1PFePcJ6TM4SWrXQn1XwzwFwGIrcE/OKTcQMYruhMNMuVGHWcmBE3vcBNQW4ppLvjk6xi5leiXs7GRJ4J/q7T9/JHwXtUCMw==","3xG9id6EmVrc53jlUE5aNKg6UzMJEhRDRgaFKBFZJrLRvVR0yKbYSRmSRtsNEjI2ZQqAypTNK7fc7VS9hYJF7JJWowJGa7T2tZVYXgTL0mxKyN52oZvhvHrBKEkU+aj6anHMgWHopJLhX++keYjP5XOptGQvy3eODKnCzl2TnQXyjoZsdne0Ml+dBzx/1hByW2rKKeneHCd2OAYNttTA6R+OhnqsudSVk4/yjm/EWZmyD1oVyMpxiIrmPS6YkarmKBmeCM5ypPVGc02uVjem002IeVobD2lxbELPTCfpErlUWuwuwjnoH98u5acq+IQQRzqFeK2L4lwmngujIZTaiXuNPKXeL49t2AOYPPREhjhsreqG5RWje70DgfPXsitWk+azq8EJ7lWttLla","HZhq3D5rQQIWcEerO5Pik/yUy0noXGKQ1GygFnT/RT7Aiu1YQ/8x4Uo3vDTkAPS0w8YGuJXT+0+NqpueX4ELGscspttNZrmak0MGJbP8I0Iq4k105TCa6pWi2tnFUyhet7T4WnPKGlKlYUkXg5Ad3afubnBGh5cho6mi2PaO54GT6XCBsDXLZkfDjSJBRoRvUQ+QSNjfaLMQ9a5+6tftS0UKGWvorSqkyV5h2lCFG/cghrkk6msYRktocPg=","nHwDIYxrwSp7MNuO2SVhTdpIPdDC+gvBOchkEbH6Qnt3J7ZuMyCNDYX/kH/OYZGvz5wKtOTfYXeAwA5yO2RLqAUTV/ztM+rBgnKt4KRSPiYYi3JbA3fxIfcCxXFPgrBaZvQ/X6dQNcqJAlZXGHi5vh8RimP0hsggrvn4FhS6X5Ve6ly7gQgbARlRFZL89YfuWsRIH+tkwgOtNlSyXzPzzHtyWYHw0WwX8AcR4CmX6Q0MRAWX/qjpzOT+92dJB/krelAvQi/kwT7f8Ww3JKtTvX03pF7A9ZVX6uHZpo+18jqYcG7q4Z61Lhwh4CCFcHuK5H34j+a8SWvVPAfqHl08IHGYWXfFtLDof6wBKn2hK8teZStfql4b3m0Hw28=","MxCewR3ZFdsn9mtVFI0LnVBtA3lxvEc8wRXsoS5s2CLX53M0hPyCx38VufxGl0zhgs7pDLZJeuhakDKNpwx+76ODAmypkEFvm8X+/6sZWv8W2r9JYCTlgJ7yaxiXU+jIhQYsFa9ulI/MJcXbkj5l298cLM6dJt+frCr5rz+SI5Sbp0wTVrPgkuXzSiCcL2YO0xfzn31CMVuBIYpblgYirKYYaVKZInfk+X9nwFw5aSMg8CkoidoZzgjq4vcoaFUslNa7wgvtTrdjsnU3Ak50Y8prUw+ML8/Fv0c53Ky6lmakFyoFiBb+rPPu4izCyeYPY1jXxzN2C4it6KHmoplAmbRhW1SwgPRYBsxWQw=="]];
                            $result_val['FGStripCount'] = 5;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["60CsDl0cAi3DVXkIEg028dN7oseCoMXiAgQCfLqQ0FFQRFQ8foWMOUiecV1H1dGOsLEKcjDRDBKJOGksx1I5XhHR2M2P3DwkYXTibdf+CRpVOMLITmR5OZRymWpbKJxPmkjI23uoLFUlLTiNxZTWZBgVIBRMoWXje2ftlUpBkIYsnCtDQSTNvsDVQawDte6mEFWA+Pdp77PjtXyLsRWx7B7CHQF1y8cs2LpmfNJRvOSwWZVohZEc1fNgbwdgwXOIOiwFG5UQPSOmpVyFVZhjei7WxawduhFBcg6HXFOMJR+pe9UQ0Qti8f5RGa8n3pbuPYVS0ui8yJIlxScxYj+rna5VYIbaqyZaAWf55vkfTOvH55n2RHxktv/Uey3YZHuQTph2KEqst4KaDpks","JSqQEDPnvv3xDgxTICbQM6txoO01O8ThCo5qcV6hqLJKHcGskjojGpEcTB0etkyxI0TLgYWQl94zu3XCBzJ7LQS5T7YqeyIq/rFgArvwxtC6RRvJ5R2m4ahOZQGq9EK+3CPXaosKaOj+sqoDkH3CZcKjeovAZ/RtKBIlb9glPqQUm5zJuF42JOwAdlSLyvw9Z5sQj+ARpWuG+iyPLtpv0+rCc8a02g1VQ6qnodc7kicd43gm7NmEX3TExz5xy4vPpbwduVnFeetH5wc5w6HI3EL440cymrFy5fKkYs/15g5D09Syyr0MfqHZa5ARo/LdaxTF08+x/jJfQNDfvdaQmEAmtq9djv5XumeQOg==","xokSSFkOfhPr3Tp6wwlvlaSlGzEUwHmtXH0imnSnbnj+8OmYjj2AyjCuzLkzfdf7vqq2tZp/EEbEsULSO4VOFMQUUnDhiOIT1bBxVSItzGnIgsEwXyv0AcuIft1Ti9f4QfeaaKgjVDfJFe/U6cufg18IzhcVB57YGeGIEnt2FnXi+zb+znoQ05z0j3FDEgJtDQoENDszhftY1++DJjFvlC7qh7IvPz1WAwCXabKEdUADl09l1Tj74XDbQpo7u3Lpqg4nj1m/o5DdXugwvH9uxlwuu3qqwlX3YKbL/qsxNWhng5UQnLz/MP6jla4nrO94E31WNWh8vPqrVKuRhJ8/eGThNVLL10qVQ7uYiluP2nc9CobAv/BTWz7tT1c=","5kqpaDBt4UG9KG8AG0iOK884wKzEZhqxSPmCUm3y/dzBj8YONSWhIVFXIeRowGjLU+j9RpwT27ZPRbM8TVUHBYjANIDpF4UXruwY3qa81CCA1IjJ2M0unRWz3Uxx/GEyttQgmy7od5FCdRT3MsjOlPhdZX7ML1xGfi8Kr1jS5mWauBB5HjtbzXXxIw7vIMJUAR7sDd9zGuQPllhG0BSWugWfcD8rYXwtWt+NSz4uh5X5SHWgCJUZckCFYre9V4qq1u104Cbugd8Mi/no","msxf8RzirDz5Mkejwq5ek4nlgRfXTYJ266l5J1Rc67vRzGhTp0nuOygdlMnJQ9dWa8xU+Xk+6XN5biIF1oIr5YEKkAtVcWvBbfVFxQ6YttcAzDFkx8xas2gYYpraZeaJpy1ShdjY2r7sqk4kosVGHlK30QWayA2L/9cFK45w9iNBkeTfiKf9U/Bm3sDWYTEvdKjEOAc0+m9y9aQ8qzkv2K8wT8N8mpA6bY4XAz3jlD253BqC3OWUF3d2nvYe6SFzLwrpNFDzNk+7yK9RQljFF+gyw4O0mD6zW2TsSWv6U1acxrBfOMHk8oVXqwGZy/IFS1rSTZ3Rp9+pMDaV/5c907sBQLaJMMmooqO5JA=="],["i0awx4az91ePV3BKEKzpFj1ZtZCRDXkxQUQh1X233MyRgeTnkYctadMJWQ5aM6d0bKdE/NUFA3OzaK4z7iv43Dtg46x0DjeQBnXrWUXA4xQt96oZHec3j3mf05lOCsqHf6ZEMvHrrGSoqWon2zzpcBYv3yd/n71ylEIQjSmfCqjPLd5LleLvKvAHR5kBAFi4M3FTg+cfj/bgC/0US4idouwnj3efXdTJUXB5SncYUDEZRfCehLZbzZt2d+Lgq3SHeXNJXu3ZCwIkYBqbFA2gA4kABK7ynlPr4djxbALA0K7Z7Br4n7LcOyloITLLIi95QFb8S4gpubTO1p7fnU0yeipm5rHpIzWqBpMjjuzwcpb5H9xvYJ9oO2P6gBIuzUC+2eahyKfYhxkWfH6vWoxjAhBMsNcTQGShczS40A==","ZfjMtw7VoBsKnjFiUequ439RtpYWX1AeqcpjV4RYwP0Jlq0IvNG6eItykfeTXwNs9ULYy6MaEdSVetYS+Xq+V4DRTX+rhSFc8479exTda6tMb2/4R4iAIPkCVP9JhH6JXjlXsaOQrJfNsgodhu6hkfAfX44o8rC6x0Wj+fOSth6cehc7RBDaZG+2USC8zZ2VpcX/UX5PEz/buE5kUXO20e091QF9dkmU5lBwldyNPJiX5wD/rfxl9oqjjtPHOwjPZRDiFEPqdq+lhW13wY02eupqSXTxwv+4lyDAY2VLarV3VbOi/PC571bvXngCHp+5dCEb0Z3ODWYUa798SJlKIAHtics9MqKtLRwRUwjgiQpq09YyJN0JDEDIXu1ylQo/JcrH2OmpMPOSXh8r","USaRU2sSPQiTQY3txuzI4/OzFCYF0UzF3Ejg0OP/WceuEYkUHUH/DniAplcvTk9AuBg9DgXR12vhKdTE6P+/SuBiiMrJSRQT8W3e9Wc3/hAvcKiN0pnu8Mip0fq+nW+rBoAX5o5fQ1qZRdCYPsrGCrKDzcoKHwq8a/iQ9cA8YupsJJfrwMmC2mO2P5kaXHatOuhU36Q3OAElSOTIuIlM+mHcNhijiNyhgtvxQOFCgDqt91vbsWRdf4//HinduLZCO2deVX13r/CN0za8Zx+9kk7k4JPVJTYznusbdbrnFxEnC7qcTQk+YaBP15I3FlWbDkzXK9+VI6EsuXHmv353Sjwrrrr3viTnflTzoxBiLibiOVJDoDE6s9RfYmNu7WaAbkCuiAQWV5QtEIPp78KbRd/RT6f6db2HhxRpXI7CtmTL7xNAQ8BW2yifS5Y=","3V54upGgZP8dvAG4NWzjrt6226J2/LCMDNKBkeSnQRaD3dHt9V0CxsC+6WVkN+gOS6dl1HSy8e/k9RuNiCUh4d6B8jZotiLVxRZLr4ERZ4PCX/OtIu1c3mHhaJqEzpTukTv9twiTmNgCHLHSlgJFbgs32P4ZuI9ucq//F0sUQBGOTIuzff4jzHZ8+IOcWV00TeXyu2cTZhrJcL05hO9hb9ZE+J+1VvypTFlFhW1BkamO1FRnFxvzt/6dqcW1f+1SrMrTwb+e+B4xkbRYFipZK5YzVE3H9ZjdbKrbNQ==","E4P8QgcVBs4u8LyYqOez7iPrXSXtiY4i7KyApA7dMwBGdJRHzNXNaVN+ULv6cXCDj3RSpc+QWHBmAcXXYdw1L6jXEUpSvUIiF4VAe8XwDQfjgBGRXe4tNyA6lTZqnTVxn+Allv+1gK+sWs+q/x3gY8RfYIOdcs1XaCwObaMjyKoIjIb4DH9SP88S6J9Q1lLouSFW68xcMr//7EnHkAYz9zXCBopC89Zf8bmCjKholiytVARFw9ZKXzJGfxTAFyZfdHlYpbiRpD1zikXNCw3kgEcBNIEa+XyjdYBwJfOHCLEcIQsJGonAFBt4NDrUnD/wh7LOgCTFRwjjfmxISvDVRx1ULxWj5kwFAR2CelFIZUKG2K/NdH5vd1nAqxs="],["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"],["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"],["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"]];
                        }else if($packet_id == 31 || $packet_id == 42){
                            $lines = 40;
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
                                if(isset($slotEvent['slotEvent'])){
                                    $slotSettings->SetBalance(-1 * ($betline * $gameData->MiniBet), $slotEvent['slotEvent']);
                                }
                                
                                $_sum = ($betline * $gameData->MiniBet) / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '662' . substr($roundstr, 3, 9);
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
                        $lines = 40;
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
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, $betline * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet'), $lines);
            $winType = $_spinSettings[0];
            $_winAvaliableMoney = $_spinSettings[1];
             //$winType = 'win';
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);

            if($slotEvent == 'freespin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
                
            }else{
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, $betline * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet'));
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
                if(isset($stack['CurrentSpinTimes'])){
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

            $result_val['Multiple'] = 0;
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
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes && ($stack['CurrentRound'] == $stack['AwardRound'])){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $isState = true;
                }
            }
            

            $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
            if($isState == true){
                $slotSettings->SaveLogReport(json_encode($gamelog), $betline * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet'), $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
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
                $wager['game_id']               = 3;
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

<?php 
namespace VanguardLTE\Games\TheChickenHouseCQ9
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
            $originalbet = 3;
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
                            $result_val['DefaultDenomIdx'] = 3;
                            $result_val['MaxBet'] = $slotSettings->Bet[count($slotSettings->Bet) - 4] * $this->demon;
                            $result_val['MaxLine'] = 20;
                            $result_val['WinLimitLock'] = 300000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
                            $result_val['ExtendFeatureByGame'] = null;
                            $result_val['ExtendFeatureByGame2'] = [["name" => "FG1_MinBet","value" => "4000"],["name" => "FG2_MinBet","value" => "2400"]];
                            $result_val['GameFlowBranch'] = 0;
                            $result_val['IsReelPayType'] = false;
                            $result_val['Cobrand'] = null;
                            $result_val['PlayerOrderURL'] = config('app.cq9history') . '/?gametoken=' . auth()->user()->api_token;
                            $result_val['PromotionData'] = null; //$slotSettings->getPromotionData();
                            $result_val['IsShowFreehand'] = false;
                            $result_val['IsAllowFreehand'] = false;
                            $result_val['FeedbackURL'] = null;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['FreeTicketList'] = null;
                            $result_val['DenomMultiple'] = $initDenom * $this->demon;
                            $result_val['FreeSpinLeftTimesInfoList'] = null;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['MemberSystemInfo'] = ["lobby"=>"","mission"=>"","playerInterfaceURL"=>"","item"=>["freeTicket"=>false,"payAdditionCard"=>false]];
                            $result_val['Tag'] = ["g"=>"241","s"=>"5.27.1.0","l"=>"1.1.60","si"=>"34"];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [
                                [
                                    "ObGHTJ5BCwsQHYfhJTXpu08DM5Bak0dIRsqVpGYx4a8+hMqSYV/phMPL24xUZ1JlSpQVe/cYenw4k/JkspIEE7z64zQx3vN0j+ZAqaL0HlvFwTxhIQqf6pLgIxyVao9s3iCExI0iyDr2ZQruGXYWqfmBUQl+oiDZqeHDM/gJx3Clzmvfr7h148GOn0+BiLBR6+Ncir843PtjncClMz89iuHCO81pdqcTMpFYZ+jVfZiq84T3AzokBoCllc8OVwA1Mmj+X3M3GkLnyQ8ciLHUulliCX2qmRKqOTfoPM/unEKbht5NxHftnqvyXBunXhZudFvjMCFCWytKYCY0/sTOUGJb34Ezer0ZZwfpwnHOAd2n0IjqJ2kwodU8LnqwWbV/1devMz7zcr6ss4FvL9CiJTDyC1qGkY5qGduJ+yzakp4LGsocY31POCfGdIrLiCyx92LSvgUzytqt8MSKedCQ9ZK/6RSFMLsgIEU92oNr6fUEGin7sZ6nXjSqNdf9pqyt7WM0FGK+8msOlJriHccqtKVE+iYVbvcFRqwPIJixYccW9c1+jzB/RNghoeM=",
                                    "CaZGpttDRijH2iJfttZ1zY5dy2pUc0bmc4q8QWzOS4kvoaEDT/Pg67fxwbneX31Lj4sfgil7Fs1yj7e3f59DA2GeKARJ8M3TBeOsHvq+rbRSirRS+feq78Q7D/li6HeHGU73EgjtUPbgiXcE4NPHMIw4yalmy47tGuSMNuVE9Fo78hX3Kgw/mpp6iXaNWlKDmwmDXIIkEbjWuHttn6ZjxNDWkoiwv9vl+VXXs489zf/JkesLd0/0GhVHdBakfVSDed+79KrIvjEWHBaQqFQnMFS9zaPb35d/evlCWrO+OcnPcf/eVfqknWuMPxxwgSWNLOg0AJjLLfcs+ycrjE6PltpMhVB7EvauphkoFSgW6B1gafo6fRlhbiaAPwM4qsTrnSrxZHI8ItRiF/moW+c4wavum/49g1DRIygaaNjqIPF800BZhBafF3Y39WVll2CExYU331zHfkImHxnmejJySqe0Ak/CwBKSq2J/4lNb6GPqOFJqHvmiMTuke5bFhbZbzXqJCOW0dlvulmx3nbuPCNQLf+I8SS57h+9HJnUqz5O8qsJYdCgVCkd3LzQ=",
                                    "b1ExTfS8RQAMEyvVu8S332NBGDBTEykoH8gZtk6qpD2q3O2FWK8Bp9r4cDobQhPgsVwA/Dx6nJdW7BCYYNOLnWILiZ53D4l9kzvFp9f04yHguX/+FaC8yz4sgmNlOpgo4zZEDwtVEGGRwVGz15q87yix3un35XFRtQJUlbFKy3Ml63VsPcAWO/CITpHX3kJpw32uDgqu8AvEs2ZgWGql9IixLWMNd9IdNOJmP6EDld5HF1rI34GvcUXK4kekGB+SfLBhYp29IrdmP8DftWVmXRNbBNbP3BwPwRh5V/0ztLwYFUE84zMqaWzIvPIb+flxGf4HQewKmyyXlPMEqzIrIzHMS65SqGYXbZerpvZf8d7uLG/s5Tmx+vnCO9TmsY4JSRDqTVdHWlT80pL/WNZE84RuXXlO16dxqDAytznzbnAkT3Cm8TiUZVbJdBZuQh8XEXemnG/CgJZfyVzoLdZhDHm4xgRdVKRt4+7o7yncn2r2gXJT/dCp/1UNMtUxpGJU0jeoFefkaEFf+ZtZW6gyWRq9fQ+YgnA6lcHkfg==",
                                    "GRq7A7dFwcbXUdtV6XT3aOgLvqcqag5lrJeVV7OOAb0He2H2oWX4HDmWkUMzxhqmA06z7nB+pWfbuh3guxiMsmTpFp3jBdqytiD3DXymbYr602fwFfjMYbQuei19hj4dK/E+9FT+rIOyc2gAeWZFO4PV31/mvaKfWv7DN3qny8dVk0JWk+k6QrGX79Lm0DPRw8n8uVUQGADgrL5ocbJqpi75Xdhl4mpvZDzfm9u2qOE+5syp72No6UzZJ9a8IoT2VOligSGlk5RrcjG061V+pBcQdkfYRW0fiFjCDPRT1sECZyvYukLihIK5waIixjfu7nuhMUKRU2D5HEAyP39WhEY/5DrxoPmjJYLEWlmlOqDIlNWFpdMGJU/CZQGDo6nnK7YiU6TVnEwGdxO4vsxd/Xzti7hqL+iQ82COENL6y9nCjN0IdLdrxQddE30gOQ2oceAmw2aLv4pPbx0lZC/H2fZzV3i2BxXNRZVX1mLAdFqyFeLDWBB/1dthfPr6IKoCY6pSytNomwsR+i7L2xX/I41jStV8LZzri1vQYJvwk0kdREvvXbcrC1EXjPQ=",
                                    "WMFWKsh9RakkCfZu2SwKa5MuDy8/jy9yh8bPuf4Xm4oqsIUOdMMVlprBcf8TxoR6eZEZxBQphsfkMdj1RA+xGKANXW63WIZDBy9DmFPtZNT+4PvUuqxRxoqQ4k4FqcuPAk1JD9P5g/RFZaCtbEyEzZ14BLQFmHrVlXBgbDAtr6J3TZ6G5NxWdUcwzh9iMP/n63ucSnGelEiUh2gA7RZiok9pp8wgrBaaHQRKOeqapoqlTlAXxd+J5q0JmeF8O3iwFTYniCz//YCbYnM86NqINlFhd9EdSzxnNNU6LE6/WPLovgfQ3LBHa8/ZJKdJlXrNR7sj4GaGPScR2O+1k7Qxf/SbZkWQ8qtd0/D6zuykx8HKJ8sEuxI7mjFB4YdxB24MwGv+2xqJOhiTajnorlNhlbT56HpkqP2X0NVfrXYge1ZTrKLTjG2oK9F7gYAiHfN40Nf4eOJfS31uawBw8qcSu/vxQ28XlqZxp0+TIwk3uZjEb+TeMO3AzcJnIG6ar93OsigHHGFFL/D+IoWe533X8/N3XxVoCT0rviiGbXxv+xu21L8PmPQFxlcuSO0K+fh04gUEw9vMaC5ICZID"
                                ]
                            ];
                            $result_val['FGStripCount'] = 2;
                            $result_val['FGContext'] = [[
                                "t5CjRDQnWe455ZEdkyytTEzzWjhCn8MZt6q423lh0/02a8KlJazZ3VWdClZrP6pYrwsT+CQolSsE6NoHqDau3YH5ZYwI1EgjFtICjR8dltE7oAX4tIDCIv8XzL3QVZhydYvYOhCAtVCUJfTSHi8RAuORkIIBdq6NC+kiq9LD8/V0XIAO2i5pz9mRIDprYGT4p6K/68xFbJKRS5eF5QM6F/iFWVyqITEfe5cu1O4EmJHsy99NuKBlrhhHcOk0AgJvoNRE3VZstJ0QtMaxCr5JRKFc/6JAhCMr40Xz/jq+d3ppQw0XAm/ZlAgcuS4M+StQUvz1nzaZoyU3/9nH",
                                "0OpPICg9kiQ28QERHlGSREVx6KnhTpqg3DyRnP8dlJYrQuNPsKAXQ7Avue0H+ByU2lj2yz+MwSWC9p60XcK7gx1cUu4B88xo7Yq7tikcBVZ08u6jAU4OkTmEoksLNioYo29QphgdZ9MLc2rXroiKmk/yJUVPBFh+DLrBdqo1296Ef/wWTLvNsr8045jx2vb5zvgjMX1HsQMCAr8r9vO/wzZWzjaJSs9qrHXlHLOXjS9oMR5ab3D4+rY481KZOSuK/8ZQF8hiXrAeQ6CFA+ksTPOOtH0RkNWDCHz7n4A5eYT9At4ibpAkXsXZCiONetg3iGA53ipUrWMee6f8l56BC3GhW6m9Ea0/lzyyX+bEdhIlIGpHgtqtuXJxcfEXCGDn3KdQTllVB1ZygiWU",
                                "2QbTsQDDzeY2cD4OmjbI1Iu6bjsl6IC7yF7M8vtl5RUgRXWX7b/po68mVjFOIPj2a6eOBX4jyUVVbi9SoVrsNalml4Y9gqtv4RiAD2NFqXT0avSBOlAU56ctKcEXKPwtPx2Qij6H8ivuPP4z9xJwHBBV3Yd/EmwD7u2vA2zCjJSZxbkFDJLnaXUdSg3FrHYiX+iaHc5xU5IKss6CbRkuTxq9o0AU5jXLlS5eqgWx1FHz+6sAn+2fNiLug47E8vC1y6keanI96GsNZv6IxzY8rfUqSAVEwdUbm/1/SGdtLraQNLiyCjUHezPpzg7r3wovanQs0Woa6PW40uoFNdxdwzDjbrIdoADNOdMMt3OGAxxDdeOdQ4jTTwQGBbYcZv9gRWze4Bq7KovuCjxxL9qXzLkZBU7l1QzsGcyVTA0wXt+vgttrHOnXWVRdVa92GiffzeAVXmANbkdl586r",
                                "0akcfKz7v2d0ovf464IeFjme2Il96cAB0xPW7NrP7/K9+cLskxzzkc/A36Gpf5BTOAcpm6fAqmYaBhGg+atdybyDS9lRsxdXY7tXh8V0z0hcoRbgBL7R+CfYGJ+WjWDtHhr7FbwFiJkBISNGii9RsU7ED235mDTD2poxTG/Yy+OK5uRGzn43j4p9czwsjONQcn6Zh8CcfAGxryInuOdhLFFLQ7HQUeM5/iYjY9bTDYYNKFgm31H/zi+F6GZ6usoEzbL01PdUEndLgEQeNk7K4jj7RMiukNgPMmYANTRSpo7m5iQ9B/G9FTx0jb1WT50wZKvukuDWTMBjvlG9B8SdGj7n+MWgKcwhkGhN3GuOiniSuBA0xwOuLlpewNkSm78XJG9Npn/MzwXtTtByAtUIp/DGfF9hcpLRttZe98tOhgBZhSsL/GtTqSLMGEt25KHJ4EUhNF45SxTLjp6OeEPPtK/6/ouLvpBswJgLHw==",
                                "LSO7lzSTljnoVKfZnc41pl13UJf0VVaB00b1Exw8PEiJTmNeTy0oQC950w5rHp27bLcNyYP+UzbcYaEgcAYseYV+ZhscZED4V4CsFtOqTGQ+kGA7cLOvK4MsJ/jtmTL3MBykBRYKYIeMekNEqNu8Iz6zjVEdixKpA2z9UWvcD8k4NwHeLVYJgDW5zSV0zDqvcIcWTeoJcpOQ2dhtrzsnTvAOoB2AIL01OC2ftCON1MT5LXbbe/9vRqszCL2RyBp1z8nKJ9RqQs0K8CwfZfxE8eRd36zGLlhFKcEa86trkWJ1t3M+EA0kelzqVzdocY9gTMToxElxqvyMV8RlYAgvlWWG/2il6HQNRvWk0M4UNM7Ew3bZZScjBe9mA+ldymy2tvAnLiq13eej9t9KLhw4ec0/W703KWkivwm0kTFB7pDVNm0C83XfRnlY4R4M8A4XeIIJR8p7j5JWbZtbvFs4bazg5jiRrU2juoGLbHMr3LHG7OXO9I6tPQ3mglU="
                            ],
                            [
                                "HAtJmQv3Lr9e8OQjDUxW/Ot5jXx7BuPebWDD6C8VpXyuuftHOSi8uDfYEQ7ZKA2W9D03LXzYQOMl4vTBek9QlGQxpXQyP2QIi4M4LqE55Vbntf89+88QPQdvqE4Ny/WQp8443JXpLOef+t7YVIA1qNvdR47uV6LkzR2RvQDP9paTX5U3TtCoFAHSRuWMhYadPERzDlX2Qh3bX6N5tzsJn9cpzlRlTRo60j9TbIqXhk+1Qq0qc2o4hY7MEFmJzrcN7gPiEzRc7L+31HGruNtLOI2aE+vAonbPHnA7ZCftyh+tztC642pDdUlDw+7xIF3tbmE3DUXoP9kCu3tX",
                                "5WWX4i0YYfJaCFqGu7+pVvoJmzHMQ2nby8hAEpannc5ixP3bB/WaqkbvrNgzelKKfOG/BC/aIaAbdzug3DNtdwHSu0eykSZwDJc+Ka6Vt8H+QkDBPJo51RXqZYRBH+C5+IJbStSuzU7YWA80k7kZmFlZAy8UxfPCHxwjOHusIL9qOP0N1XaC9mIDl94ovq3FzOOyf91yYt3PD1pcDh8iptO9+ppHG02hYRlSpY2wR+kne2BfBnEQMxoM0ddXMrvEZnx+aEleGgM/JdevgeN7JqOMQJQf7IEJsF2ScdsqanPEgDu2XJmzFAW646cdEyJv3nbk2dZW0m0E418O16JRvFP8TCGNG9ehUwQEmgHCvHMN00amDQv4aDHf2ocT6ucRC2i6+fYyRoxQRWZj",
                                "zPkBUfT4sSPaFCreZObG9SZ4cDE8J+Fm7JFbQQ4EQOlVqCWQA8COGqflUsDP3gZ/i1UAeT94WKovCcH20K0DdjUjkiH2DuTz8DeG8GyNB7Th664BUpGYfQx3zgTsODiwJ0nWQzzkOnyT6sxBnxoNECy21oZI3GXduNcKiKCp9TkrJxPvR6/YNm4nuHlHE2JPf/qRyhXS6003yS8C+H/U2ActF38SyxBaMiVVKCnZhcyU0Ox33wdvsPG74erNPAvxFrrhNy9pLpitghfBu0GPVWLn6icT9z9R+mY2V+whKbbAsSnf8DEX7nrT2OOibGmka3lV1k51bfj1SkffZI6OEAN7P8EN1etlcJ+/tl+rhrPi1fKGXiy5kbompkLt5A86wDVw/FJQvKo5n0qR3EceS0GT0WQuBK2G2Ayg7LesolLpKUSGnkYwIJnIHiG6kG7DnAjRoDVe3s0kfX9FyiuhNOohDO4yPLypNPiWQg==",
                                "GkFLHaVzepZ06k9KXU+XMJPLwg5Bpke+1UvPGZDoGrAOrnpDfWgrl2XmKHbzVh/yx6ZsEqWiO0VgaaVZHEgav8ohd+xqdNH2M4kshHUnTOyAMV+xqlWPVZnsw81gQj90dGGeGVT2nWvVvTyABycGTKrrzR760FFPl1vQM8MoY4xl0lDaPAUqWGSyyYVQGq931R+Zlq5GhkJEEmZn0X/N5+wPMIpoLEGtPmFF6Ox15WDFk7lutzETO/OwFwKRKJbUAWAEPZF+OKnv5Tj63qApi4Wwk+GqzrrrBYQ4Qr47CMYfXggGDF04KRP68Dk8BSgyQNPHTcWACuP28LJ+f0k4mpKcKyJNpt9/Gd8uc01IktmJZo/V3tXPzJco1M4i7/3/dP4LvcBwJfOvHFrA1EgqIC3FRMc+w5T/P4s1HH5AweXqJX8FTkek+tl/w+a0Py2Cfk5z/aCGEiYDMNbpogejfGqW8X4jMJKP+ukbO+7Bw9otyqox9V9krTVEombEFHsMLFeAZoFL+csR11O3",
                                "csgcQ0DboCptqB9dlvJN3vvnnd3Q15gimVRSQG/fN6SjMhb6dAbeiUsC7TMKpvdbFEN9qCC/piepgJAIIAMjNwZOkEmtkNz4HQrTzgB3iGQDjzfxAbRUHhBSRLyNfnOjpP73eRyWXK8gkwmQonyjSecAFS6BtXli5HKfMypAFLzVgJ9fm9GaUiInFc3Z+y6P1qOplosDkCad9WX9ESlg6X1sp6AMvZScchCLGmUm8BvIXTrnxnC50s1ukOUfjho55yn3SwKImdLVwMkQ0HMLN/cvypIri0LTPBS3CvyGwr5TfPdT2moWR8TgMoQxBuJfqg5aC2l0stEtjL2ZpZT7hFXjcMMe/bp99A/fDUPncsmJaT1v92LWM41catoxLM/WJ7UUTxpFq1DpLWupxp7ZXQbu1uVEAxshuIo4rReM2MR3rbIIEIjSMuxulh8="
                            ]
                        ];
                        }else if($packet_id == 31 || $packet_id == 42){
                            if($packet_id == 31){
                                $betline = $gameData->PlayBet;// * $gameData->MiniBet;
                                $lines = 20;
                            }else if($packet_id == 42){
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                                $lines = 20;
                            }
                            if($packet_id == 42 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                                $slotEvent['slotEvent'] = 'freespin';
                            }else{
                                $pur_level = -1;
                                for($k = 0; $k < count($gameData->ReelSelected); $k++){
                                    if($gameData->ReelSelected[$k] == 1){
                                        $pur_level = $k;
                                        break;
                                    }
                                }
                                $slotEvent['slotEvent'] = 'bet';
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
                                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                                $slotSettings->SetBet();
                                $allBet = ($betline /  $this->demon) * $lines;
                                $isBuyFreespin = false;
                                if($pur_level == 0){
                                    $allBet = $allBet * 200;
                                    $isBuyFreespin = true;
                                }else if($pur_level == 1){
                                    $allBet = $allBet * 120;
                                    $isBuyFreespin = true;
                                }
                                $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                                $_sum = $allBet / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent'], $isBuyFreespin);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '6587' . substr($roundstr, 3, 8);
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
                }else if($paramData['req'] == 1000){  // socket closed
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                        // FreeSpin Balance add
                        $slotEvent['slotEvent'] = 'freespin';
                        $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                        $lines = 20;
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
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $isState = true;
                }
            }
            

            $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
            if($isState == true){
                $allBet = ($betline /  $this->demon) * $lines;
                $pur_mul = [200, 120];
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * $pur_mul[$slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin')];
                }
                $slotSettings->SaveLogReport(json_encode($gamelog),$allBet ,$lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') /  $this->demon, $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
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
                $allBet = $allBet * 200;
            }else if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 1){
                $allBet = $allBet * 120;
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
                $sub_log['game_type']           = 51;
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
                $wager['game_id']               = '241';
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

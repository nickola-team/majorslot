<?php 
namespace VanguardLTE\Games\TheBeastWarCQ9
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
            $originalbet = 2;

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
                            $result_val['MaxBet'] = 2500;
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
                                "g" => "57",
                                "s" => "5.27.1.0",
                                "l" => "2.3.11.0",
                                "si" => "60"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["jl8NYECGZ6MRZFu4oki8zey3Z93fVUFZOzpJflScP5ZpW3Bd9Y910HWrFAWw/cznUprQh1MNr21f1ahutBAeTI16MqpI+BMKBNHi5V2JYUxaEdH6lBkVVCKoq8iVicFNa6/xmOgGQi1grKS9qWKTFvIAWAnqYAbKfX/RbhoZDJgCv9HLV6NhSSONEMC/W+j+GhZzCQNOHZz/VkrEkCpcgfBfEaB0Zd8/+EVir44D/RhHmP+1zbeQqvHObJtXsFVAHbQ3rCAQEcdxYApjJAwVrXFanUZ9LLooWhreDI4zqFCrzPJiOXjmHVtfjnzdNDsOVDyeItipg9hNLP2v1QYlAEswRPoNC+gVFXNztCIbPv4IbwVmvpGbZ0Pp4z0=","DJTCt4QJBIXaXvyAvoxmUJUTlz5KE9SpnGvq/kaj0T9p4HUrbIoM1Y8gSqFnpcM+CbUUqBHYu/JDZ9cq4/br+uv6Luzd/iC2Uy1v7nLPhgBEUmWv1GLb97opngQyfH9gJxzgsml45Kw3fNljw+NnBJegCGbk5n/q0FAHuFQLewbdVd5RdNHPYghV83rC6V9fu+UuIntS10m2xSBMxTfV/Qw7gRxN3LyjmnNoSgqUYYnpruxASYNhEX9xaxUOySz78ewvfmCEIoRndV/77YO8PboMySmcmjau1AoB1jjoAVbtKemqfNYMe5Nw4x4cFbtapZRcE3Ue/UorCffS7yxbaGnh+CNZ01uUPTYYOg==","cFFjh3dGSkA527dQ6CuOhAehzaPoZbNVv4coC9Wu7caGEarkJdkTD/GJj7mRKRvrRE99F5OfAVlYLuDFvkrel2KlRNN2FRfwHUaBXfp9Zu66G+uRlHFKwAzp/QlG3xBMmrn7EKh045L6DWh/ITGz6PU530Ur/McYUJh6mrtM1w97tWIlFmUCl+rdoOZj3pNC1T1JQHBMWkj4DsvzukpzqayvyJiErCLEEgD2I/QrM+boaFwum1hyELI+ElwyPMzQbPYFYCE2DnAbOnGAXbUMTB97Sc1aKY76y4b/+gnPJlr1aCMUD8OecCYT0vBJAO1GKLDm2g/7xzkO0JLmdrlH9TmYmSYF1+5/vkUJiwkNoA3PBkwfhnhlpmzjV/w=","UH5jOezzYoNKZqnqpEo1GuJmv3QsqkkFJ5OrSgyy5t5GJ3KrJ8rtdo1Nq/4iTcuC8ZYUjQ+1QL5vZB0K1Lxjp6nT7j7zXXNWLHLyamRgwnu22ZwkTswxjeqeNN6Jt1BljdJdiC60N6AeUX7kcfFEnZqpToszj+t/RSK7/fJrMKMqrPdxTZ4igEHlC+u1GQDMVnm6xcw1RCkckiCvI1Mj3mb6hbUok95U2nphozZHtAfLPL01fwCO8RWlTY849UzWb0LWufdvgYdBPzz+B9nwq5kJ3VSQBzAm2RtccqQcRpkNvrKKb+SEX6pnJ7kD86vxUqCuWeWGPZHFIiHdYQu/AQahIMnrvm0HSBwtP3iVtm3kpYaUVgdAuYtTZDI=","PrzVP6rpwkSvZ4hjYZ6A22sc0eOsLLuQGEvcusSQGLctraop017eyYFpNqze+097GakuVQlAFg3rzAiJpi078Z1ydydhRlzNh3bsUw7N++y3PR++T4hSXGCZE6MV9KrmaRO3ucP9pouHoSA34iQuHJcSorOOLYgzoB+ac+P5URYGEz+Lpc9xHzuVNZTVbi2ADXkckIDdVyQZvulsPyHFnPe4qaXSIjjOxXTfjO4GYsJ1zWAl1ucKQDQitr0yT3XOwyameS3E0Zk8icYfb01kSBSSrAzHVcab6p67o3vKC6H4jubgXjpL2NHdfZKPjldzu1JrjKzlHuivlacAkusDvqCzWuTmDvLFJtMe1g=="]];
                            $result_val['FGStripCount'] = 5;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["4HC4HgMR5wi652wKHkzYYr5nPLeR2cXoGYhqNpFN9YHkq87yd/fa53htRuJaJ8K/OeB6v4ieYee7ccC9KuslMb6YhsokrY04eI8+SEzVAjldFc+x6tuTmdAvjMcPYtSUxnMav0dBHZcfSZHIdStWCOSQ/3MSKdUtlQP6/KD9EksZQE7Znmxpme9jtlKG22IDlcs2BRyYBrEbvnUP18/mbRIP5nKyyd991dbVljQY/l/5gqY0CZYwvlJn4Sygnz+yYuLEPGBEtYONOYO8O/PJPumGahxXefzI6qZ5fTdetkgqy0Fic6OEjs5rISc=","KaH67OGpxbkH4ZJvUE8lxN1p3R7NbilJBlawCHnFFHuYeFuKqQ+Gs1OXRhpe+Ca3qukWUm/SVgBPou8eHnO9Xca/OxxIf9cl0VWsjf1UytY3zZ/HO1Jt+1gatsDk5trTY7/6F5GsBhiS65DWY5kvT2JkPMstvvxIGK4LrqYvaXMK327l9fW1xTeVdv2iXWYVqP7z/sl+Or/UC3sEwFSC46cXhFoPRH7+pm8abFAvaPxyHzoHraiEnt5U6YC0fMiLKj3ptKTGy8Y/1rxjQ38Ef0wR86ADwpv4eWFfnYHGZzjvbLGX/GRg2C7NZMlM/Jg38jdh/ua/TK45oS4AkDjlWgNR98SxvXS8uAQk3QHFdGFSreSyRx4C5nLKc/I=","ZfwgI7rVm3xKhNL6s0t2gZ82Dgt4tH6IttvykFlVlFVHQ7Zb8EOJ2YSsTrBOWK+p3Y4U7op2dxIdDrnR9a7YOcyxIcTxWlr0mgvlgLKMvNrfNtOK/Vxr2e3iPa+19trBJm5NEjMVWxY7s6VexpAqDe1Bm+oRS12TMF5h89/6KL8NJsufRcFLwuLH1Isj2G0sF5Wm1Hk6ZRCuSqfI21yh6/ZWRQRb+k45+/pvTwuq7oRrfzeuDwLTRYfGCEDFp7f2vrX/C01EoZ8ZlQPm92bfTdjKlm96oiiGf5WWp0NB+aY9C8L5yMFavc0eZibZbicc9kw/b/Ldk2EAUf/ajSgSZuDZU6w+4l7SwKpmf91XYrNyi9B8JKLYvFB2gak=","t8sHK0qNDJMUCd1k83bk5g24foXJaUl+d3UuNmbmbKaa/jdmthfPjSlMOM3d+7rzagaA4P2sSStG0AmbsJ0NBjJK72fnmD64qPQ7Tcnc0K55lRVMmKXzXUYzP4bLH7mzilKGfZOL+xx+YARdY9r2zcbtlPu91sib7qp9C82sRcT5J7PGNKtuWifgaRbYqrO7/ibAIUkuqTXlIy20tRzy92m5hqrTjJrji3xL6ArZqh/2e9C874QbzvuDPzo96Gx6Bst90y68Cfilb53d8xzskhiua+tYD4nAeQCXIonl4ATxE3v1CYUWkfFryZRa6/TiqnAw7ye5wKw+1c58BjhfhgN825424NG6Fw7MPy92j0qZvlH0ZtchPdGwnNs=","FyU2LB1gHpNOiBt2X5XfF98ryojazfDTzKoc8P8H9K7gjVY3dy/sMlXLh/7HX/Zo12PaZ3YJoE/oF1jUkqnf1jjodzvge9qxxaIWxwqlKibaCHmYl/Wi4wzrLm4NaOBNi+a6b6H0GBfpxJVC1wCJw+VRFtjtSsw4kMzF9gVHktn5N8hztdaOAJ40KSH4v2LnP2rU6G2P6sC4lH6i3g858TOb0VqNymbElW9QvpUIQwKerRn19GnEwbQiluWkbYnAtSlDn/ir5OIatLBv"],["VXzWaLaQv70awVFqI9cu0v0Yy6y7ZWn1RSt05HkVuSX/QRzx4jlXjZ/EU+oi3SXLMjrD/i4YyDYuHqDnkVP/HykfpVo+GoDjQckMEXrXgBF80FXDHnROrgedS9Eb1PhuJodQ6KptaTkF5wfjS37/EkJH9GnRSA7YbwoJSLWfBJZIkBaJNAP6flx3DjHd1aMIfGaXGXJhjousfu+tv6wEGM9T3vxBe+D4zgHHRsv5LUC79t8Lti0yiaT7K28=","G96OvC5wlNw1ShNxptuxTeaRsWbUky1OAVzYVcuHi+Cmp11YzKBSJn+TTTyqC68+DodC9ZxXcgFqrKOo+qa17Z3pULxCGtZRnNIBecy10s4R1PoT43/yGMdRkuVGfejnoBjbCLzqRO9ZR1NuUQIkkZ+oT+oOJl/MCUj1WnfR2mdULadMflRm2hIcKt0umwjXvQ8YGxlXCMMsoPd2I3jOGGLSdHsHU3lQPe8stLBCY3RudvOlprIeI20wYnM=","X8cdT6i8CGN72bhOXeA+cJ+YG1z51C7Ddrz9oVCTJgqtOCUWuRAEQh0AsUZM7XytVikfvxqkFuJHfIv31Ch05xupRVKhX9+PRvnYqO+2HrK9zz+cGLX3sbFpMiTzZaaR1Cf6AU1ld6sL0TGPWf+Y/i+l0I9NWzVVX7c1FdcWW1UNDqdemMZmSDnMyJESAr++IXOZo16pFY0fU6RBbapPH+B1vo6f2UMvIRo7UuCGxQUlSivs7hNvi7LWQw4=","u3fpqZ34M0eX7Uw5hbBBpRMoqhajz+sSr4VmWcbj5+DEriCPO/MhOhxN6cGpIG0adPQ4ykChnmg5LRj0W/2DN/DfRGUUhRNr28P5KQMAOzgRvsQP/s9L3Xc100Rgd3gI7HcNK2c/QbSaPj2OZBEOENBetaB9lJHOHTdlgcbRinzdhrC70EQbJd6E429Uvy8wc7tntmSsfEko4PdptIv2FijTQVtPzQ1Vi1mCDoS/4XmsfOirlveKY96YmBQ=","n51kDJFYRHqxLulLU8wc/3xLEY7BrORAm4wX8VDy3Lfex94SeKngNyyiJFROothoTpcKKQJvKEaxaaV3ecOD4cOOpDU3mEeubz0IdDzJo+894AaZlZR+WOzK473ALzwM1jCpqvIc/CoqZkECMDJQkrOgRq4bqLg0MuRQ6YcgGz/34BwjDRqq2Q+K3xtAbDJK4t6ZGo9Lv9yWIEG7VJ9Lq/Qr49Qk0DidvqXcvzfGMZX8FTdwD78fCZ2Y1ec="],["aQLiQQn2G3Zr0ZKcAoBzQRjxvuJiGhVLAps7+RfE9catjoRs+0AMeTtM0j0u7OtPQViJwOLQDLOUnJoP5peXqjoC9qlYUCSbP/Pkbmfm3ZncVntqPJLZXCFEl4IZgJS9cHymzXcaccbxr6e+CyXevZR59cJ33FIDlm2ziIlhh3sOI6a1SV2StzdTjwQ8P5Dajp6qmlme7XQqGlEfk9mBCqrT/qSt27RXAqdnhJVdJcLoSoaBbVLuoeHoTw8iDtdz0Gg1qPDQwtLyECfW","ez4j7HOzrT12U0lEVbnbTDNqYflll4/y02eYWVL6ENiTcAcXz7KiA5gaXmRVKakuq/bFcUfqBJziTBUi91wVPgVXMx01nZuctoUhzhFwCgbaZVH6R/OOzLRWleEEZrry+CjjWkg9lFX93hoqsTBiGIM31j+WNkQWNY3yGEkigsSnse7DNBfeX4/6xjZnlbqD5DbYvXb8ZF5iGleL9QbmAqvCugc4YIrV+xT63U/qr2GYIlBq9KBk6dNk3HTSrE5LNx3TZeZC92ZvDJXafBmsZB7/de5/1SA+/qnkEw==","ZXplecE9CkVyIpJe2UW3iKA40VKBQlN/DnMxsYd/5uyFTGyfGoZdEnjAGf0E3hZpWOBfg6gkaiYCMwzBu3KxErWgjNu19wVS5lpfOPjhunXAbTQb0WGXKCRLaYwbWGVbDqyeVkMfdWjxb1ksysyMcRe/7UgwunYXrAh20TfHjO9BsF4NjoEJSMGDVD7zcPl2dsq5ZN/9HJQhPMoxeLmNE5vfbi3kkOK6N6EkEVQDCEvKJWSTh2ha0VPTkeCOWbdtU+52CgEdluNts9JDvPPVZIy6q/3sA2dNuGZTbw==","4BoyLa4voy9yeH9Y6+CF9twPt5DGDYOqIHKQTdemeEahSkRgieUO92Tp3riRBFcEK0s6ohdX4/BRythmhXLBeYI45zpv0qglGPzhZAbGa1KIUs09PhO8m7r/UNuDMhn66rxhM7oZTlO1SO8nwszNTA4TVdhSc29G0OgCPsslGbj8LkN3f0SenvwJAVaIbLL1TfGBOXMW1NViLsT0CVjuy1imN52bErvL7KJdD49hMa7wN4CIjdgFxutORrcblxhnHfg+A/LSWlwspq2/jjomOgAMDrUfEerneo2MAA==","WTnzYyVpdDyNvaWzW/sLozHuWeaMVUJCuqoZpHwfpbPYOtuc1tp0R3+aHKowVwP0j1GHlcGvBgVtVdGuOnOlDyGyaguDMfVk9D0ZCU4S7RK3AHDYF0WCcxTNGJlSYz6NSBryo22fGYes8dpCQyQh8yO1GWJ3v20ZzhI1D4meiaQuM/jLQ82y3ntQCXg="],["EfvKKvDYzs9f44FYZNOzUBUvArySQUumgC+UgxWuCqf504EFiLd4Jgpd/s8RIQ4g3k1K9lZhL5WMLztc4Sui32UOsEMpCqCOr1t8YRwdI+qWrh7RH4HuInCNm2qSul8V6zA4FHCDVsX327FcSZ8D8M5rdEA1L3ZlP20H249DJVN5acLVd4XzUnHFFOAr4abLrYbo+9/+yoTUP0k8rAFRsFv0WuF+tx4ETg+oJ9VVyzWuBqiOiIJBk/J+3HY0U2Dv18MF8hf/qyU7yphi3LobnCBQfozVmvKS7om+7w==","4aLnEW3rqBUI6KSAh8PnhVJ8RvSjPidTB4S07tE2P0qxrK2FSE2spqan/9OP/XkqonCDNfwjcpJb4PtQvfkxRaWTknLl5UIuuosgj0zgx3RK9eV5Qg2mHuofa/SdMKOQU2n7NmxRhp1hzuhLm/Cxv/12O5G2QGJD78esU8g/YIJnGkZDTUJ7WdGoKcgCPbKWvyK+Ytatqak1I52hzMdv8FM2mf5qSmOe3slG/deXK425lW6uodLs8a7IP7LUnXhSa7Q/ArQi894xTi19reg8pAZFEywO0citf9UeiTxM4qzn7RFbrC1JLvRAQy8=","7KajIjdywSSQG0yZtoo1/TrSKYaTZXNri+G5kHlAOXqhw3IP3r+l+atPYOm9tqnMaTGMOSmb9bfBeoNcF+PhJLJlkoMykpmh+QCgKW+vdPOMpK6V7DRMuUET7WK0yXz2a0MfhFQF3/QD4BDMu7OsGwiHR435Z13HVvvGkPPCAFZDJcIdNkxgclpgaMEc4maRAnYoVYaCwZL6qwutw3lp1UcttPlK/Ja5oJc4YGmyyrfwDn3EfWlvm0Cmo8qplDN3jwFp0/8zAeJ3ndG1uMisY5F0xiRHp50FOJLAFGm4rfQdA44azFuA3p8ZX2Y=","sLoLSw0AeGdMrAod+Vhm9IE5tjj4cdnixh56o/Oi7R8tF/wTSm8d9lrsr1dWllZip87su82vOL79GeM76GRLfZoEl/k4JC3e5BMLlhjYeE4Iit0QVK3ERdgjkEdCUVXU3kxTT+rgsTOsx1X39xlMYEZU+oc2jph7DiNqAhPqst+DuDJIwnFGSaNeulwF6LvQEeRMYte+W3O1GcQV07+L4SU5+JR88zEYpkzdx3gsP41obhwcGKlvW5AlKU/7/8bCa77+OGdwMVwu2X4WyeAvVMQcZuGdTVN42Usvww==","1rgklXxSDoIci3fGQuh7leHKkMup9JvVh7Gi2umvHpRIuAo2ZOqRTJWxF4MSslSH1Xf0XYwD7rzZumIwY4plUOY6WQL0ep4mjcBl7hJ8O1HOoOJJjfu86+ZZsVrsSHypBSxHWiDIbny+Co6mkmn0wM8OQld2QnP7ejuuneRli8QaVXCRMuj1kFHW8Mr1HuV5FmsZN9KqMTM0uwIiRitUJnf5YHIaOHzUqai+4w=="],["sPJ2nhmArU2LvFDEr11jZumRWzs7UJhGNE41OCtAsojXcz9017p96MzTANQUd59bKT7qh9LiiAjUKa7dYJctWoDVxloEhb4aFOtfKPrVib8TK7/is00RqMQ7eZ3TXVhg4ewQ5Olw4dKLRV9vXqGRq60Q5Ss0kbETybA/HJDjx8y0ukRoychtCZYte0cReay0Ww4RK8QM5jbrnR2UQepDQRtE5PFDhRKWdNWHoal66JUWeWEoPtutVNj/CWDuxbzB7mGXc7Dg10Hv2R51","ELLCyc2DpMFtOXVvlprTIafa6pIc1FynpEioB2Xpj/ESobQ1ELz2fD9u8T+LAjsene62nwd5m5UgGKfqpJtwIfmS4E9xCJzwnH3du8ArfZQv6ocJ+NbSvzk/OsCATTf7WYftUy8QXVFw9R93Ca+Z0f9CwWXH+PRW1SJ/DcIPiBC/mx451PeUTtANtc8IgVEJ9vKDhHN+edhUR5FWc9lw/Qtv+7mH+bSkWsOW4XgFkQHE13lzRn6Z7AElGHoCvib4jtevaNejGnu+ybZAyEdqnMNnY+q+YAF9dbKZcR+TCVXTFkP81OpF/pmqVQ4=","Ww5217Qu5TSSNbuAIHuu0/38mvY+I6zeolaCiIervu7QAB4TC0jxalvNxk0JUOszCF2kcvCgtlfhLArWXoobLLHDTxz1uIVnBY8vhNfPJyU62G9oe89D2wvk2Py48phPv5aV0LiT5c85mey/U1uLczXT9kDbitJH1J8DpCifgcuP3NEYcIjvBxwQ9vvUE5yiRLE6gSS5eu73Z94fLbb7N/juDMhQAKYW95HsvS+Ynf3MmWxzE1jcEbHPdacPREtAVuXi+3VeTGe6cOuS9wxYJq0zMipdDCQZ/rfaRUwkQcYtHQICfVwsM1nmADw=","R8jjxFU28LLmcDjajqBkSfu/8MM+b5PA6Bj3S7Bb+iZ8C3N6DrkWBgXsQVmC3WQRuhGVlzfg+HiSi3PvgeZAJL7nFZIpUZLUIL0LErZ+ZfVuhF9LsTforzUFFr/RkJP7+yD3dpovgyhpKvDMqsZRE3Fjc991t9y1FJKGOj4csT/XdAoD37669jy4IX5bz+swugaP7P8TvQ8D2DcuWaXLWJ9Nr9YdKHE3jXo+U25JrV0XnuzBV9tiLgg9GKmF1ouNC9X1UvKBi4m3C0H2yuUZAWxUOEFx5hj1mYtyaga5pwAZCI+apv2Z/coeNMU=","CewUWzBEPp37m1CXkIhhFCeOH8145d3BTOKRwd9rqwzK3bgdEC4s1RimJXsfCVi2iKfG1RnavPiaAumSO8ixq9gTP7S5AyDG0GW5/Ic032y6qT2oa4xLE/QYIBUAvRATPpLHoG82j8jlXFgLLg/fGnxu6oGBLQyrrghpV0xaafC8mCxOLQVX/+Q0BnZX3LqhLLvBztxGkX5y/mEosxfc4EdGA5YFrL/di3uHsqo9x5U9kEncBMimizuF8KE="]];
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
                                $roundstr = '672' . substr($roundstr, 3, 9);
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
                        $lines = 40;
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
                $wager['game_id']               = 57;
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

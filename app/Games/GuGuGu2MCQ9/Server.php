<?php 
namespace VanguardLTE\Games\GuGuGu2MCQ9
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
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 0}],"msg": null}');
                    $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetBalance().'],"evt": 1}');                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'TriggerFree', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Respin', false);
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
                                "g" => "129",
                                "s" => "5.27.1.0",
                                "l" => "2.5.2.5",
                                "si" => "32"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 3;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["Hh4YaKzE0MjmNCtbmgRfjzjGg+4eFbEqVMpam/Re81FENGM2vhW473g3ztRYHlkL2MPOnYMXn7j0WOXhtG3Y/UG3j0dKwj/l1+VO6v9wMARotkR6Hho2KSO4Rn1lkbl5HHwVgH5dQsoXEUoKm+Ar2aHqTtRYFmIYmURGWA==","5Bv5GSvYdvbh7Lm6cnDdxmDJP3XFCa4hig2JtE7IW4CmNnwuLHWe4pBVS3FSvwO2iXnojbPTT3Jdtu+MtASEQ5iKfS8o/GGLfcJYZbeEKXM8wOgvvegI3pZJC0YWWckwgLBp2qNHo2uyNozYpCYCuMVJHkGvrMWWW6WoYA==","7Vt2xEdxve08mc5ixWhAM1s955w54Rt4hJFy6vbKDPA7FmDkd7pL61J2kGQ/CVdEu0f6QmAuOZVgBbqObKtcvbpdRPI2LyeXi7A0UTEQC6eMz30O4hGq62kiZ2LgqLwHW9UgHMnc1lwWM66pi7rLjQfYlnNdQ1/jg0e5EDmpKKcq89r5fNqldLw3T8A=","AiQBf6A4Y2rEG4AGKTMmynR1OU2WyjAeEXI+kezQtrfbLWqY0UzPheEAlTC2rQzBeCaDMCCuy2tqisof10+6niojxm1DRvyrhbHu5WtYM3p8IfcFYOSIejxYd+Pbr6EbOobGyiS8iv1GQTAaXjtxcKXROv/t/S57rXLqIv9ae7g3LLKCi95cqpqvWXHWwweqTh9bs7nnn1igZIZ4hIuUhJw24jnvEZ04MDK9SUYNflrVA3mxO9Wyj/FKHIf98ZdvbtCsxcL3daASyzDR","A5HvQ5quI0rpHSjxgXb08sYviKMX2m4vRHY4PTYyEXZAAyjj9oG7zZ75jOQswygbKeEkWpGa9a3AFi1eaKLHwfgMDnfmiJfuNPS1PRNpVsNElz3PlOMuJONQLhkDLc492NhsPVXKC55csPoygPzhP0f9FdX3LjRspNahJU/wm5Up3/ZDCj3NUW6BBB1oCjiIAWPW/GWDyYwxXkYry8bowOCajHEoewTtio42Y/oNJcKWPWNtmBvNvBnr4Hi8ihMpHpqG/C0W56EIgIwheCYMib2LnyJfjXWttAbWMA=="],["ytsqTKIKNRG0c1t6QDN8Tyyqm3xoExwIAYo7fU0QgBztPq7oZTAK4/IFFKlrekOl/AJsLiwaziUlCv473VPecrV85OUTevHnT7P2YfLFe5JIqIK5tuIVH/FsYHPE5pt2D1PzOA3riz0BHf3QJU9XEqK3D0vysf89nHcJNA==","7uZz1dHEt7OrOA12x1nStcE6vaYYLACF1vY25gBJbw73VzEf/tMeWV1iJY5dpHC9rnI1n45rzNc2MeSsscoXwmIIyAPIIe+1gOSy5lsf+21BP++Uea6Nk6vbEZtMNrsE10okCOsg7yflY0oo08pLX2gao4aF98nRIXorFA==","P2w91wxZNJwte2kr+ZVYIgHxG5TgrqFrb88+NiE63w/RJ3g1upkMmy9bG/nkEIMZ3WsfsTKxqmY8FZr0L8aaKqx/TO5FPCUHipCSiZ808fJASODl6IkYsta+hSJV/r8TCuQNROoKz75v2N1aeGZmkdtqtzpXESl53NfHEA==","SNbuIeepThGIfr2a0BTv43/U/ZqoyF3VfaDafLieOPhh3tb98BGvUPi7BFCWjPxGVooywkt3TWeKJPmJrjol1VFQlgCSX73NsqejukbnpHLnuL13DWfjpUocotPQ7k9nuzCp9XNqTy0GCwtKMoFXD6fwalh2w6o3PXUHRo2r9mOnfn7TMNqf8AjZqbgNlv+4Sqm8nn3W86fw+iQGhfXrTrZQFBqNafYZiJMrPO+5EzWIXMb+NgjIY/azHVGSOuDBB8dRsa+UpNKZvgHH","NL5Zsg3Amupn7l5iPGvmMf29OOhz9oeV52SMPn3O6SLh0Jfmaj+Jio3nvgCEhylS+H3pXHmXdBIhG3VGkrljgfK5htMxKDEy7fOPrmlBaBJ5hBtPufwsh3Pb9It0P23MCW0E+FnO6XMx4ejgffCDrFuUPWS1jOgIVrxlCJDigm57GUXGeYWu40xqN2MbwJvJshTQv8vkAfN6CF+6XcVRkn7Cao4DfE1ZQ/WNs+1IkbOUo6gJQ1+KovGA9sffPf0K7lSPwPCUCC+fELm6AxvQM4upmTKwi6XSE2qX9g=="],["4cDfWN7Y2DtDLpBxc+D6fSEdYFSBrRhWRpHxPM0Q4l5FaHjbOwSVumN8HzP56ohNRojxh6JOSPJXqSXneGoebnEmr1VMWqoYzEDFF/hc38egFM7Osh7FadL0qZaLonFuR4UywGQM20M/PrhVregYJDk7XpnM5pJZX4GHkA==","9LFcEXevpIzsvQpJRUD9gnRa11kWAPvwpaGVdwCgtdZeDPqGb6VQBzzz9oWnxRGxNihSeE5nfJKxKHSZpznFsGOk9ImhYb0wr26U01v3KTHljnelMT829D+UhMaRB/6TSNZ8pZUvXoChUjgR8pyN6d/nF5JOglR33uZ2hQ==","fRc9TQYV3vvLLlbo6TmH3BQEql/7IIUgCQuHeCb5jVBRY4S7Gn1q1H1SB8TylCD1veABLHcmjE8RgFUu/BJ+u747QbPrtIK+yFFjYO5ucADPbDT0LINPV9Met5OEJzHQd1QPfylamXJZ3eRD","7EFLZb7pcUbWVmMvelWo5/8V7LFx/1YrT0+D9M/AUtMNc168tvavYrABGWAihVLseDvINyS4camqLpRCC3irOMjIuHywjA9TiaYw+iWVH9cyq370lOC75Kykrh/Vdg3wXvSHCmJ8JavngT+sfEVDylFqEk5abbafEkuZ7pBgHDsUPlYUqqMZVdxotN5G/CfDrteC0DGEyXpLM2jpt/WkU6FPmBgzkv5pbUSrsLx3WFBPJ0lzgZXSvG2P7EH/oYr7jWKnecFAxAtVrcfo","wIeoalDLwzh3wBMXWxkHPsdupunrkJqowHj+d5AibKkYNZYAfcGvs3Liev5qaFDo+AXRTkxZIO6HptSv78lpSutCxZPQK199nBPyaIqXS3EnWbwoKRU0Z0QZAuLkfi7fZauCoUWEpF4n6UEnNxLwG2auZdsxwH9pWxMiiV1UpS7Ku28rYswl7w7YV4P9tECmMkgiIW3ldZV0QToMZcy9FVD3IU5UREbzC9dM0j0WdTNKrBIZhfsUnVb3FDUIiawDFYwjnyb2c3ESKsfl53PwfBAHjLVwyTSaM2QzSg=="]];
                            $result_val['FGStripCount'] = 3;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["H7VivuPAhQCFxRp7RZ5k5SDqpqwLyIApPZvF0unapcp4x6d+CiOQEmssVuXMpzvXVC2TYaG0eaZ98WMhpA2cl05n9zQW9tR4nZ3GCmxuQma7xNi6aOoOq5ZsUqspME3ZZ7aVclFdGcqL4xMhUHD4Ty4Mpl+zNTTdFabQq5bMuPz7BvyLpz2kOPEZVQbLvCEJzWa81Qa8QDjjsTPcN4nK4w1s1wkxDF4KpJY1Ig==","dJtf7fWIBbwLtcqs+xrEuqu9lTtfXOjJzRHn6XkMf7fnnLTHwcAr1aPYqYrmEFf9yjj4HJeZ4jVkIMjMs7AHNgh1gELwAPo1YErbGjdDDfcYhOSCSlqOkCwTn9OMRVhO9OnwvNZZiCC23sW4T4DR6LcCWSwlYLitFnmrux+mPXOY2bEX/Ju9csGNx8Vo9CuCFBIatef5VMvvifOQUw8j8z9PJWIcDMgzQiVqKw==","bi2XvTwUNvL3EqgMiN3k2u8E9LhVWUD3M97ssoiQxHqXzK3v+odpk75lCoSdYcrWlvrU/DPOeihaRCAOoAVYL3Vqjmtz+nSb+/hY6GgOjRnkVENd7LaXYwUaXyi0pH1xLZYnEKmWvl1qGdFIh/DpEjid8M9lN+3bu1xIXSuybvnStXGtvvRpcbIjdtGsr1ivy4TWR+dHI/aQudhI9/F8GQwOSq+5WxTxRDKoNEDxTu02jeWdivEBC+Z/4dY=","g06fXa1T8S8A3OVD+3KJ4+qBSzRmm3FeUCJlt+cMkGBCA8NupdY0KYmD/ydDed14IV02uY8i4e1O8/ALdTS/e9au60rhOq25cEl6Qyuo7p1XmAWc1EksbzxERYityVFS8EgZ+llgFM6f+CQ+GAB9IxmbC1tQZNCe/5pPQYkzmRMRt27c9x4lOsDAy0nC+C+m+7xPsUfBBwNdivpsI6Bhj2T4n7Efya8ph7sbjoh/lw9ePm4FQrg9ygFBtiQ=","feqlSx4tmiLBvLy4td620VpFaLItIzdOY6ICLhiis3pWNb7UU7HswTzDdgEItMRx+ahuCjgfsH6DHw+2+ZW4QojsY97oWsEirZz9u4fQWGjb3NbeUEh0lXHKALKvG5f6Cylr/x0K1j1oaOiYHgLLYBTcfG567dEQhfj2qJcJW24ufrP1I/jVoTUb2WVutT0/JQaTHbnznRLGAo75ELw/9CWklJUbdRqvVi6yxWxroRIOPDk/IoA9Rat8Dlc="],["2leC3jShM7sOT4MahAI2C/Gcl2oHTFqlPcmzFa8M2v3zodTFM/4vAewH9OGPLC/aF5FC90X2OjJVi9sk9xxn5696IfQhlNNeyDOuqaGupwUPmtysBKIKMg6W/gPxNAK/zPtB4qVZigVA/RfE9H2p75T1wi4lNH0Sc8Fqzy03QlP0dasS8lotFMyxf32FOG59Vy+1p/CL418YrHCj","Z5MZBkY96ruUsTOJ8GRpVn9xVFGFKUHQpxE93FN4KpwZInRe1J2C4NCxpn+emhcfEK83Ep6PsnkCk/NGMbpVX1Akzd8270IALP0HBJgPQDrK69BP1+AejkDu4ISnF6ootE0WQkbfenLxGrZug0ZxPUsk43dpA1WtvaEud+bg/EwdDOPPXjJpYv+FjRCYvH8/zEOfcOIOxXtuocT6tPw4TGc5lHHn6vZ4fORauw==","TssS21BRiE0Mo4YQ7Nyr91h915pDvl7ESlH+PkP7BZb5e4MsUR08st1zqgBTX36lrU0ebEfTgQnaeoY9AG5w9e9bFOsFKvKXyu02mqbzC0inFW9/LZg4qX17qqi1k3D36kKde+nV4/jtKxzsEaCfigjb0nRPfF9k2V2MZwrKqSEuncYWrnIvHL6i2fJJPKBk5ce1ThWymLQG+f68h9sDDwY1rtEpCeA58lQnog==","nSvVEOKJ5sJcqAAFnP+59/HhO9uNI41YcAHpgHGGFF/C/hGzu0/uQ7SEqfODhaU61FO5Zb9vQgW8dmJicSbaPBCwmV+lGNQ8FbOaqJ1iXZOFPxfTl3ttTNbQ2+d3qPHm4V1mc4hbI3RrT1puLFuY4wlUpSQhTN0stYAqpKffTKmpXfSTF+AYkLKtjME+ivCaKO66gQQnmsBECD/fwxEMavwqkNTZ3PHjEgMNPg==","Eer8A5lTvd4TGTaSbivwnOsxa/pJ0h8j0YM0DVkN8VPiz95yy/bdd6vWzXlf54ympUf2U+coDF3ZvQzSi80Z19JvExOYyLrGEsiY4Fg1vap74Rg+emjFdHjEOCOwf8ggP8cwnqEftTYMni6LSwJZ9yfE7rzfsV8bAiTiT7CKmSw+8jDbZlBPbne9Yk34iT2DbhXa/UKSt+YW53bmq8RQr/5Zn8qRHtQfyhH1Kgqouu1j0ZEXlTf3g3D2Bvw="],["lnag2zj3mNZ1wUUIFzkjQlm3g9fyXAxkY5k3Dl5jcpKWhLfhie5cmoeCph5mOZdX7hcP4Is8Sufc7uB6Bj8SEInznpotZ0fai5iFjzVP9GBJNUUbq6cit+DisFAk4XJjP1Rq5uJ/3Fth5xFGlNwiR9svDIIwxuiEJic0ZTqmo6zQKN2/dE0fFiXQOcgx+4qtyQhNkE63jKs1NKsB","RrI8r1KRJkdYYWvl0Wfi6RKlQu5rgA7X19q5mVZTlDhrou7WRUC+FUc4rHEoyKZCSg0JKd8Flw9fqep+VxZDcN9euJ1JKheKprVvw4jIqX24BpMCiUNXmBq0SEmMp6wc8r8qk5OQLzaqCmh9w1bbuG1fPA7SKx/41Awm+6FY4lFC7WYR3GxnUBaOCHLXNHp9d+DfAslrlTrLIwvCGJGgOXxdp0QvRd9ZaSLMYA==","fZXCvkAritws0c6IY4pJ3j1THEHJbmPID3RidVSisqu1S5FvmLMRN+xxZuvNo5jv2SPnvN32wDRREHEsDMIek/d2hiOm1qp8MRfOAH6x6jLKRmiQRM6vM7DTP2EGWlEWZnZs9qrhL5nQFmkkwM9/AANA6dZa+JSSnRy2/U9X0BKGTMnKcYCgFrU9HEIdoCG5xpRV2dsuByNUye74CNPTdLkcabNI+scw1jJmKA==","F88snhev7554AdeBgFY7I4sd8YkQIp9kMERb17od6TcBKHRd5h+8AdrxuQdIxsYu9+3KgfYv7O3MT0EPuXSFoOnbfTSMRCnv5w3UPZRt+9B64TVtlLh3Xd7dXM3v9bXB4yJFluVPMnfCNuuDe5L3w3d6zvlyjDO/ohPfiqbop9U/w2lMt1UbnWSCoU5PBNrh7oViaEcb0vt7LkVXCO7g4bFOGG4TA6SZSpLqqw==","K1w9oWUADRbSjfmituVIDwJw1KVO/jCWBAQCXilO7+xgBGmVc/hh5tiPBusb9tJOVFfjS1pDreD0ivI4QAZwWHN9dU8jHhBAz1v9r0xYXpw8M/isp8k8HGuS7fwc4vCmi1fvkdXYsLZMU7BnCI/7x9B9wGrH6twZRGUA00eDT+q6+q4G+Grjw4Cp+3XlYprNUnVk+9TTADek+bmckCsrHsddeWcXQf+vCHO8YLYMCpCGsezFohp4rn/aw1g="]];
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
                                if($slotSettings->GetGameData($slotSettings->slotId . 'Respin') == true && $gameData->ReelPay>0){
                                    $slotEvent['slotEvent'] = 'respin';
                                    
                                }else{
                                    $slotEvent['slotEvent'] = 'bet';
                                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks  릴배치표 저장
                                }
                                
                                $pur_level = -1;
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                                
                                if($packet_id == 42){
                                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                                }else if($slotEvent['slotEvent'] == 'respin'){

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

            if($slotEvent == 'freespin' || $slotEvent == 'respin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
                
            }else{
                // $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, $betline * $lines,$slotSettings->GetGameData($slotSettings->slotId . 'FreeIndex'),$slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin'));
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, $betline * $lines,$slotSettings->GetGameData($slotSettings->slotId . 'FreeIndex'));
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
                    $stack['NextSTable'] = 0;
                    $slotSettings->SetGameData($slotSettings->slotId . 'Respin', false);
                }else{
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == -1){
                        $stack['ReelPay'][2] = $betline * $lines * 15.4;
                        $stack['NextSTable'] = 1;
                        //$slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    }else if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                        $stack['ReelPay'][2] = $betline * $lines * 25.6;
                        $stack['NextSTable'] = 2;
                        //$slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 2);
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'Respin', true);
                    
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
            if($slotEvent == "respin"){
                if(($stack['IsRespin'] == true && $stack['IsTriggerFG'] == true)){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 12);
                }
            }


            if($slotEvent == 'freespin'){                
                $isState = false;
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes && $stack['IsRespin'] ==false){
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
                $wager['game_id']               = 129;
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

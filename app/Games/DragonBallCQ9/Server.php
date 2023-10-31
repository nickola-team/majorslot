<?php 
namespace VanguardLTE\Games\DragonBallCQ9
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
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 9}],"msg": null}');
                    $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetBalance().'],"evt": 1}');                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());

                    $slotSettings->SetGameData($slotSettings->slotId. 'GameRounds',1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount',0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ScatterGameValue', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TriggerFree', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastLevelSpinCount',0);
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
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 4;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["87Wc7MbT51gzPjdeRrdJoCAYtpGZ4gnlu6koX90O3NvETMona6yRfTPOj21y+BjC/k7EB1DeisG3zhPj+b4Ck3b1upFsmKQbNDlVUEPFGlFq7x7C735EZUdHabt4n9ooqgHT8uR+rDZ4q53m74PUO+mIgpxGe80Y9+99yKjiOukq4OUW1lO/XcLPbBdp9uLCwfitaIUdXnbj/qShlHyd2SgcUV9fgM79NY+IEVZkC2QndWVMklLAVIruMAM=","skS9TwxWWl10zo3LJeqMqU/pN9VWywEagcAjX7e8cnEpVjfz0/z2UQPb4lDbvsl6DqYAQ6kV1Cht5XPRwpoKnSKABRhAkyJzeC9Qv1RqZonW9ftKIu6ImJkG/00qX3GWmW2mnH3iknU7BbFCphhj3nBn8PCCKM9KxRDZYVrAu5HwKV03nmQ60M8LnjPR3nIaefMkajrrDIwY+HSJt2vucAQBXIy1wR8BvjvllAp+/cQv2TUBWTehBTjAcFl789DV0Jmra0+P8U+wdmkI","KVvx7MluwTizeh1MLzKJFRrQi2pZI3Hp8t597l4ryEWAWr/N/Tvlu4C7XOARvjBPAZOqB3qSkCdXteb3Ccrd/GbWTouzWDHvnenFOqqCSwR0ODUIAR7LUoDXJuFlStaiuJYuobdp4LIIf5hc/pNIvVsyg7vScLsPjwnfCt+L76GOU0F5TxwXEnjPZM7+AE+/kIPD5CnTB5rXpoNgRIdCMX6hESd7cHxm0IT8oZHkDLG5El4UAbTcGCh/Hp6Etf1mo4sFFw6edrS0LsFCCPQBBzN3Yx1SFP1CAc3rGVFU5UHEuDmT500F5wvCRJdazm/q861tEp0G3baP9TCUuE4TVLtSfLYoowlwIKEiMk4dIyv/yY5v7IY7Nmz80rzhzCi0vK8gxa7/8azawQoI5vvvi7ocOT2JRKQtDkGW0XfuP1fGLNygUBGN/uqAKTVGZk10Vc7moMC7+WmA0Cox","2umGOFmqvCr7Hi1V6f+oDEfZkaGI6nxOr4gD+4ARGiqkjGtfIqqvy5mnDKdiyzYyT0rRlJf+hzOrskWfp1aYMW7eUripr0qz34U0IzRg7yaeBxBav0W5iynlE96Y2dn/OeGFkDWoqukoFaSn0Ebo4F25zzn58EpIr/mD7S4EXjt1kOOhPQhyv71F8PVxR/ZPCDj6tPYGzg/Rg6JuHRswGCIXZHPAHfOlT2jWyFJIxuloEr7U2rbw1kanAn8fG94Vs7ntC0ZgcVEnLpsuxFQK/3tCGct5qingBetzlA==","LnMAblUQumOzIrLX5khzmas08+swJNDtDcVNTIwh+78eFp+yfB0NkpWNY7AdK3/wwc4IaAKD0rw5Hc9in9o1WdNxxkTb1vMNu2jdLWsO/dB2aV9QGEXTTp4PdgLSGReI2FpINUqHzImzUBqwmvd4WsYV095rTgE3SG00SI/ZidE4dZaxA8pf6HejQKKKrh3q3zMXvKR+zXC0PVOG9BvQ2WOxNMVHNVA+KJ1SJpLl85hZLNneCRpiRDUx91fVn1KCp7AF8uuMed0puANI2OdryzO1xJKep+dEuhgp5TCbd3bMdxpp3iSqW7JFDhI="],["Ablg3BHLOzWkpmb3vuR2hOArVc2TzqT5hyUUyVvM16/wOlp3qMxIcD7q3RzMTEprt0EUgFiNOKkfXZZek9a4WYp3EYXvi5O6tRAxNV2x8LiGmYQFtAUcv2WhfrsxAZexL7WAB52y3FhnGTe+nc60P44TjM/mzpntlbUfqPwJbvjwrA/+RnmuSzm2Z+2dwPyPiprOR0rD2GhFeE3Sj1p/pZKVyLwer8pZzQlcUA==","ds5EQ9krkPmfwQk3SkvBozEJwa3txQNR+5vvTd66/ynIRYH7om0bNteU76Whqw19mFc0lIWSBunzczGWIz0voPZGRAmn2+0+2B292li1U1PZo0Z1m/Ya2Rh/aUYkshjiH66N13afnDw3tyoK0zUbRgqvY+PkZUq8JqrPHBS2qalg91qIU/zKQAnWdMcQwI9SsmtLAXBrbsuY6N6Fa9Mi16tELTyzNxUl221XraU3KDzYNDQ1zcjP9aZ00jQwnjBS7WBQHNW1k7QQNMNJ","NVqlkLvJhzy4W4t6eLfnJs3lrJxEEsnpaJDQD3Am9hyRZ00nNlWvgFwneGdorXj49cbGxN2twK/IDIPCImzIdM1i+Q+msEsb7PFUvlekezMb0st0Uw3eL/ykxj18kMzsZzo+q2NEB6vFrwBG9GOkYJhb8ZcrQnBl4Om/cIbN3YK1HEEESO73cVCOiUE8TWu1GevN5H2lbRhr2sMezbLXDxo5HMvG0AJ6E7Z0GBB07JdJ0o08Kw+cPtkFXak2jNak4MiodHd27c3QePkmaw/sJ5OA6ypoYGNN96wZb1OyrbdbwojKV87xa5Ojhye5O+AVDm5W5ajgZDKWnEV1Hmn/P1mepsvqqW3qRaQwent6aANZc77qoxodH6Vub0txJUaC+mFjNXrOTzUvZthX4VOP3reet9zItJSJS3vg/zyCCr7uRLO0hamZlBBsv7zCrli//C7A+gAtxPtGuoGUCyQowjA2Lb16XlRZABKnyg==","dWY9y3CPFDh845RC2APLJPEtz8ziY266SATsW0KLwi1NFV5BiY7xvrfGft33wqDYtxqy1ePyB4w5Bam3Fr0unRYZNa5x4Ml4QoQSl6+f7LxfvPo0ieVAktglG5fPbTfV4Ff2j7FTXoOpzrXLRuDSQor0iCXDMV1PBwwyRLXm6FP/DRxvK45oYGxVB6V6lK/k96+ZP+2HPj6J8QK/+laRtRwAm8sYvRWrmTKZckM4YT5kdjYZvaoSi72k6QI=","D2gwFIq2pfxFK0Qqt6EHJNmMZ/YIAQ96URZSjTUFMnIlyqhI/YBRd1/pO3Oih9zhxZsYJgqycenwEZNed66xTH7ZS6U+9UOb3yiya6kgf349hoIo7ZhDRe/QBFJ1zOD7H3a4kWmrIbh7tjH0gyAxXz6FAH7qegVGZ2rSFkl4Sjp+3ae5f/pCHSwHGPOWRGkN83I4pD6Y4lk83fm1qdA+Xybz1gF0UIeDTyI6yupc3SSXzpHdLJh8oHHDYzUQfE5PgCTxUKhBhE8M9k2T"],["waomUvf6VHy3Tgww16FdWkpzFglg0FS6oJQX8YOfMrbXALw9ob62leHGD6Ts87tdLvO4dvzOm9R/OoS7y6WVsREvLWknHdnXOHor87RMRphikkpxSZJUXECZ0pAIt2dbWObHgu7otdF4gu+mlSoeuj8scAzq2kcX5iwgIyz3GI1V1jbXakp2LfokX238UVw7i2IX0AmLhuV28a7o64Kkg8nPFw9EkTkF7zZEow==","Zo2OZCKVBZwpcDMbP0ATFr7C/owlWhjzfpHKIua/F6MNs/kVJUaoEaZMoVmGoEIn0YJ7/uTVhbXKg174ZQz+aZg9ZfVouclB7gq/TPIWi0XJnU7MZeAa80kxgUSm71BEogegwOIq/tLAovsboaW7V66wyreTGz7ZkafiPMkinSHXia8vU/OVdx3zUrZzZzW5JwuHs1gSLDvFSm3bixw3aePYB7aX/PskrfBGOQk6/D5AKnZPEa8+PygyhQqm4bb7ac49ZhDYlf1GZiJC","qJpXOrS9mfuk2bNQS5PPrE+nIeXfIzyGvdWGlIMekYlMIsRb8ojBz8o/LasgOemGtXXFXe2SW81oPymckimad7hkJRKXjDkaP2M2/xLM0zQsfbqL+1jqitkkilp981RbKLnMZ6XwtuPnXUXBq/uzRoKCGvem0K3pY2RY4Lbio3EleHYokYZtHl9TchoygNUuCcH/6bv9J2CEVihEPj1p5HFPjx67+n0djVX058efHA+maE6OZZU+D/lzOqHkU3ilIH3jQYNigCWcKd8GTZ67NQgs0H56u+7sBQJAQhtW0HB4IMmVYHuS9sTngRbmGiQ/AaiUfCOD09Syb2rbKYtxctfuWKusvbG8eRaHh4tsJsf/xTvVQxqPNnigdeDLrdwqkX2NOsDDxLb0G0qkba1F4eK7AILw8fND+c+R6IVsZQcumuL0djhdFiZEB9uf8RGjAa7pTtBxK4+c+BBqrd3zS47sye/XLcPpahip9b1C77dIFU2KM8tYZC4EDEITonVkdQhRZat9cT1WtaIBaCoKGfdLphhmhtmIOMJ1sITSKblpRrCLoLDi1zKdS7knKKBO++IOm5iZO+eMSqln","dlvsdB6JrnQ727EgwqqwN7mwMt+ivZZRlgVmuOYd1mRjGVIRm3V1ga+D0yiUInM7/hdcYbVMcLiT4dzEBp8DzWkl9TqNuSjvnwhDFg43rHLyUi6tWDRmiQiBBfsCnurwjkuMlUhUDub95ThAh2e+nbZVIduZtFvtSjfOxxwnnk7kxkMu3/3Q5GVSsumzJSgCnokWtVDplcJ/IFjV6clGnUNFSe1vY2B5ksIV5uuSPalL1KZ1/jge7X9k8qqWYaNV5C3v3ej4vf53NQaE","bHYMz0X6uXfcvztWVdaFIZ0lJPS1QYany85hMD60RXspDAumYwe0ji2LmePtEEdpopCZnYMVPupyYCdFOeu0uki1nqDGV6+hwZijEgT7S5MODuYzKOaBdtlipSijZS9GIafO44Jgq14rpz5SjMx2v+Da7kXvfe29GFhqLXEE031xWcsCUPLP4SD2eG8QgpCgnqVCeRvZetuj6fDJAviN83qoAKBeBR4gCLVgK5CUG26foFAgAJs0gwxout2D+h/tCaNZGuTpOwmB8V3v"],["Fh8qB1K1fpx113kaJTQzoR0/oUdwa7JwLWgqcfKcFMSALMEy0sKWG/ti9oOMm6lKq694iDlUJqN8Q+S2CguESxBSzcuSmNXULipC4l4NgnSiQgbCARM/iMNhscU6KyVEm7TFNL5Q5VXgt+Mp/uS7g5QyDCsjeVqQgg4lcWxTN7xMtrf7YkRnPIvubnMcdTeHAiXjHku1zoPjLpLhiioB3kFbOmI/YtiRzkQ9VQ==","FaYbqEyzNEgen8lR6pXPFs3nKM3ihIGkb/qjf0uPNziHS92zXeKTb69hL2eXALowV3FcsObAsbzXXzODx7o0Hk35Y5V6wE2SgGC5sfSrDj/WFzRPLluRhzwgVUm8SOAUu5OQ+VOqLCzsB0yZHzsu0XjudU1F7kKLa4AeYIqPD1N7M6FDAiWtELanq4Q0EmQNdhpI2237R+gSsSlILPf29vBNakDV9sxFvLFaeGV3ZeN3sTbvnaqQe5mZ/Ow=","SxeV5jPhEQF6y679J90KiJ6HwA9a/Zfm53S5RxqlG+rJPCT9RCJVj3360Ld4mhqRnzJp1KbT/WcEOAKR3UAMZ94RmbmxdQA7Q91jgZ+Oaj07tGQ+nHXajHI5bKCP1VhnWc9wOa2wTdoG4V6WlOKl5w8IzToc9Cp0nqKDCyHlNyCaRt5eS9nwPsisLzkijVL/5PE1TMIAliXuW2zRqsyWEH4pdd5Zp0pgovNMWVpXQTxQsXJKprnaqtUNXU4Ego+HCHU27cT2j3VU7KtW","Fm9ucmtB0lIgS4E9oQ6kEca0Um0t/gH+Po2KHSNlBO5w7hFZvqVKKFVNn6kQQEd0UMgwcaG8kYUXDhDWhoI9EGWH0M97qRl1s795M3HQEsJsLNj7rJ0edlheFiR2hiJkZ3eJFIqoDb6J6iBHV1Kxyz1k0PH9x8Bya/9gkCJ1/koQFWbpkX26+jNpbZo5dnJll9A59Ke6T1VvZKlkIewwinFitcr3wzXCC1grM3/+USJSfW+YaB1OJ0Q1XNrYHJP9gQ4iF1RMIN3C6NSx","YbklRNV72pmVyON1lX3nEz/ukw0V27Tjx2X0WdYde6WsYLm7sKG04IQRT7F/YnWZ7N+cwKOoKZr/Swscp/iUQrQqGJCYsWCouI08GLkNJjYKcmNsc2i78Dyyw2wAlZF5TKA48za4ADqGQbxSI92NXpVjtKV/h6/Sgyk/6Zb8tkRCUX/joTKs5wVTCVnuifg50xqzvsBgzbQjnTsCU9KBjYaNr282huadjXabPx6Xwxzkb6Uq24YxiPhVF9qqgz12J442fTfajejFy+5J"]];
                            $result_val['FGStripCount'] = 4;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["5x2SactYX8HPuB6Kzj8SYhYnu4xF63zYeqDTvKdnU/kPQxnj0DJ9Quo3TE2N8rh17/QHZ2UZ0DRcwfwa2+VhA9+S0ePmq6lnYlomYMRVdryXDUniilhBtvaL+pKWfC9bkta3zHPDQ41YN0KeA/eIk7QdUovNtnLOlfBevXsaoPZMgecPC/iDyJNl2X7WsGU4WcdaSKoLNI9JDYywel/2/sP8LUKrTCZ5BTZLqNo2uMO2KIGFlwKd3OgT+m0as0X6vC66XiQMs/362/KByQsw6TbeU0NK3jvx6pzFfn9yacnyMCuaf3QTloshwQYKZ9lb44g/ThFbHKE1dhsBdGixg/KpHRc2YC72nPXqGKZzijVCIcn+aqs1v7+yqMX/1V45ZWWMgXEXqCdKHr4IKknM6T9VR6tzB7sBd892cg==","lmxMvBsDcJQSbaALcz6dfzs6rWx2j/zMTOPGIOj5X3J0ySgawFq5FUruxlQdm2hMs/semtMseUA/iYwmmHbqxf65WzrP7plsLUEaTClIyHUB255qUJf1i2QcJb0s7C6YNwqi8C8OTaPH5U4flQZXnEfuIiT3AG1hH5sZildVXn6YfO0fhuG0DIlY1XbG+6+EGUiojpMVsEvOIT7PZ9ROvpdX/f8XBUofZ1SpxQ==","Fi2qpxeG251WYk8hch0TTwKhQp80KTw954BRrFqjjGAwaTWy4d+XU1/BhyAzM2ELl6tzg/to7eSu82djTybx5Ggmgkg7JRrBqzZiamUB6TTCNoQ0N03sQviBPADDVUyKn0AofQXH1PuOxl6Sktayfv52jvv7e9BTF+I4uFw+71pj4w9ythFOVjWRBJuD12jQbBm0M55yAT0WXdNDoMr5+1TEITLoaGpN9GImbPU2QGdj5m5ipMpX75BkSiM=","T7MoXmDtwskhQ1TtvtzpIjLEaxGn4NBpEa4tOrDhJWauDr/Fhs7d3HAZjKe2KUauw7krJ8f/YFucKBjugSBB8cKMXSrFUnUCgpot18D6Dcm9U0Y6LwpJKFnJnTw7AvWUmDO59TydVZYyNkvsm2eFr2MH+ge3FLOC+Fa114i51LFer7kmnlu/Mag+AHO8bTmgqLU4X5PFAl47t8yUfo+ZXnGQTgIw7f2jGaG2W+SxsI/O6GtPYG3ccZF3gKAjMjbj4M64sesxhQ095JQ9SKLWL5QQwsWN/G+rd0f8rJwCjKIP9A9Qfk9iXq+D5ZA=","Pgrl6DC530dA6mooD/NMgDitWIUoMgBVkKUmLUwvYkSBVqoMQOJKHlGXIopijWh5Ux22fkmfZyYNOmK1XVmlZv1vbqZCnAHKab1W4MjzU5xW4Xkdl10Ix0Ms3D2rMbGRxvimxdW4ZRJcKS8cg+mbv/gECxrwq/jLrRyxtQfC0x3qmCZvd4r2Gb+fe09e59N+SIU+/W2H1khRTIqOTAvUFv8Y/0mTOWdNfVDoSSSOIGPmyvKGzN/yD/TGCkLrUDdN6moL0X7EI1NJnk8M"],["xGnAmruYPOFg9aBvsofMJdPzAWeOPR+7oanlBuAmapDk8hgAQ4GlMGykjIwCuujd7YWnWh6uNeXI+BAUXQuudBirfAabZiK1FCa1TKuAkiidLccu98fA6aOoUnWfyeRop1GMT/Cz8WBIB6xgHO2RPeVIed5shxD/rgvyMl+mzeEI+6e4PlMjtQAciGA3wP1QedYYfQCjc25q5Jf5AtME+RJScusxa/u8cpq8rKjoqDRypKCG9a/2s43qv68WCpgnUfY2SgLUEcakRoFupIwKyGtOoKkPCuRlzbv/PYAU4Xn9I1qFiM3yn9eGROh918JrJxTc7gujxwHimqV2TtCnJ5gYNyrXVE9b/iefjm0TEd7Nhv5Thq2I1r1XdrigFpLvMEhTSXBjVeVlHtbA4wSMkpxVvFl5g8Y9nP8n6Q9DZfnkoQpoU1vvYgBMVQC58qEJRzcHlCxplkuUPHJhxGTVKe1iTUf9/ei5MJupGa3p4UjYJtoYfF/2WymaFxuldlTLFAHuibJJw4GP+fDBgle7Q7RFa5fD+L2cQyqrM6KBDOs1TTc13oAPvT3lMH+lll2jpNciYNqtEuEQc2WDaBBi+c4ierrxvPKGOqK1VS4VW4AcInfMP1nY0p6bwC4=","L2C881OghOLqi36JHcrftXCmnXTfTvF3sg9lvTTupaaPFb+OapbNVWf2rewBGojywWi2YEpm0LkV2Q2QxpRkcZrZvrHahYdo07TSBsXsnuPJzpOtgbOx0Gt6iEUFEo6x4r2U1tKeDIY+amJF6Hh1QTLspC7lyChlCGnCF9P/zh9bTRHugI1l8dhUGXtUrdoUBb50q7WFCDGT7Ren2lRugWSRtKugTQGqFdB0RNqQ3Z43IcNyuzKDvXm/AdA=","hzOZJKqQK3UihrZzmsSKvYgmANouuLE60UU/NrOgGFZgZNMPUeq4/WTUIpRA/PnY2d7xsfxKzZ5CHZPXa+PrBx22fZPdmDIgpZF6xDENFpNnZRuQfH4CbL6Jmpcr0PyHW+kgMYrBBng0wfVZLSLvagIwOehwr+WK7mGDYX6bo3XVTr6q9g+AGsqz+QJq3OxzSwKdRcGfddsUc4ow/Jc8r92liPtrDK7ZpNOShmj4j8r91M74Vm2mPO13Fzk=","x8rk9lm4iyKaVAsQz8e8OfojU/PmDLIESJugnCV8fQ/RHC/REF0Zzm0bBNb8CENuTUH8hSoO5GCFh/yeMidFxfuhwcXedn69mzPlVcCYA4nZFUVOA3VMufj7MFNHsbC+fOzjsMOijc1ZycOfI/ZzjuyhpFlNCdAYh9p4n1s0any7bZlBbRKseKd7+LqObRdIFRw2Fh5x9hIYgYFJ0QwtjiXQcVhbgu3i1dym0sdrEXpSblaEbCVvtPwy9E8=","s24Uew90I5wyCccA1M2FPrKkQIhICuLstwFPkNwGxlivagI12+TscBp9eZWUsdPq3KctDygoxtWJfXs20nWUh9APqD4Kbsh8h2lv5qr0hmIRU6Z9gfhwDlOeidNarffaBDZ+itpRP51MSBYBGXE8WzQQaDjoirTJhJxZBWka5OcPjlsJg7Xq/Edp1hEZQlFMf/cYJoNgd2Xq339hnqXej1DG2wEtjDyr7ZkKeRHzlCr492VyjgirMXdUMStj0I8PDMRZOLd8YRBKRgsP"],["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"],["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"]];
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
                                if($packet_id == 31){
                                    $slotSettings->SetGameData($slotSettings->slotId. 'GameRounds',$slotSettings->GetGameData($slotSettings->slotId . 'GameRounds'));
                                    $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount',$slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount'));
                                }
                                //$slotSettings->SetGameData($slotSettings->slotId. 'GameRounds',0);
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
                                $roundstr = '657' . substr($roundstr, 3, 9);
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
                                if($packet_id == 32){
                                    if($slotSettings->GetGameData($slotSettings->slotId . 'NextRoundAction') == 1){
                                        $slotSettings->SetGameData($slotSettings->slotId . 'GameRounds', $slotSettings->GetGameData($slotSettings->slotId . 'GameRounds') + 1);
                                        $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', 0);
                                        
                                        $nextRoundAction = false;
                                        $slotSettings->SetGameData($slotSettings->slotId . 'NextRoundAction', 0);
                                    }
                                }
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
                        $lines = 40;
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
                            $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet,1000);
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
            $winType = 'win';
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);

            if($slotEvent == 'freespin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
                
            }else{
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, $betline * $lines, $slotSettings->GetGameData($slotSettings->slotId. 'GameRounds'),$slotSettings->GetGameData($slotSettings->slotId . 'FreeIndex'));
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
                $stack['AccumlateWinAmt'] = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin;
            }
            if(isset($stack['AccumlateJPAmt']) && $stack['AccumlateJPAmt'] > 0){
                $stack['AccumlateJPAmt'] = $stack['AccumlateJPAmt'] / $originalbet * $betline;
            }
            if(isset($stack['ScatterPayFromBaseGame']) && $stack['ScatterPayFromBaseGame'] > 0){
                //$stack['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                $stack['ScatterPayFromBaseGame'] = $slotSettings->GetGameData($slotSettings->slotId . 'ScatterGameValue');
            }

            if(isset($stack['IsTriggerFG']) && $stack['IsTriggerFG'] == true){
                $slotSettings->SetGameData($slotSettings->slotId . 'TriggerFree', 1);
            }
            //$slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', 13);
            $newExtraSymbolCount = 0; 
            if(isset($stack['SymbolResult'])){
                $gameRound = $slotSettings->GetGameData($slotSettings->slotId. 'GameRounds');
                if($slotEvent == 'freespin'){

                }else{
                    //if()
                    for($i = 0; $i < 4;$i++){
                        $tempSymbol = $stack['SymbolResult'][$i];
                        if(str_contains($tempSymbol,'F1') ){
                            $newExtraSymbolCount += 1; 
                        }
                    }
                    // if($newExtraSymbolCount > 0){
                    //     for($i = 0;$i<2;$i++){
                    //         $tempSymbol = $stack['SymbolResult'][$i];
                    //         if(str_contains($tempSymbol,'F1') ){
                    //             $newExtraSymbolCount += 1; 
                    //         }
                    //     }
                    // }
                }
                
            }


            if($newExtraSymbolCount > 0){
                $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', $slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount') + $newExtraSymbolCount);
            }
            $stack['ExtraData'][0] = $slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount');
            
            
            if($slotSettings->GetGameData($slotSettings->slotId. 'GameRounds') != 4){
                //$stack['ExtendFeatureByGame2'] = $result_val['ExtendFeatureByGame2'];
                $stack['ExtendFeatureByGame2'][0]['Value']    = $slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount');
                $stack['ExtendFeatureByGame2'][1]['Value']    = 3;
                $stack['ExtendFeatureByGame2'][2]['Value']    = $slotSettings->GetGameData($slotSettings->slotId . 'GameRounds');
                $stack['ExtendFeatureByGame2'][3]['Value']    = ($slotSettings->GetGameData($slotSettings->slotId . 'GameRounds') + 1);
                // $stack['ExtendFeatureByGame2'][] = [["Name"=>"AccumulateUpCount","Value"=>$slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount')],["Name"=>"LevelUpCount","Value"=>3],["Name"=>"CurrentLevel","Value"=>$slotSettings->GetGameData($slotSettings->slotId . 'GameRounds')],["Name"=>"NextLevel","Value"=>($slotSettings->GetGameData($slotSettings->slotId . 'GameRounds') + 1)],["Name"=>"WildMultiplierReel2","Value"=>"0"],["Name"=>"WildMultiplierReel3","Value"=>"0"],["Name"=>"WildMultiplierReel4","Value"=>"0"]];
            }else{
                //$stack['ExtendFeatureByGame2'] = $result_val['ExtendFeatureByGame2'];
                $stack['ExtendFeatureByGame2'][0]['Value']    = 0;
                $stack['ExtendFeatureByGame2'][1]['Value']    = 3;
                $stack['ExtendFeatureByGame2'][2]['Value']    = $slotSettings->GetGameData($slotSettings->slotId . 'GameRounds');
                $stack['ExtendFeatureByGame2'][3]['Value']    = ($slotSettings->GetGameData($slotSettings->slotId . 'GameRounds'));
                // $stack['ExtendFeatureByGame2'] = [["Name"=>"AccumulateUpCount","Value"=>0],["Name"=>"LevelUpCount","Value"=>3],["Name"=>"CurrentLevel","Value"=>$slotSettings->GetGameData($slotSettings->slotId . 'GameRounds')],["Name"=>"NextLevel","Value"=>($slotSettings->GetGameData($slotSettings->slotId . 'GameRounds'))],["Name"=>"WildMultiplierReel2","Value"=>"0"],["Name"=>"WildMultiplierReel3","Value"=>"0"],["Name"=>"WildMultiplierReel4","Value"=>"0"]];
                $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', 0);
                $stack['ExtraData'][0] = 0;
            }
            $stack['NextSTable'] = 0;
            if($slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount') == 3 && $slotSettings->GetGameData($slotSettings->slotId. 'GameRounds') != 4){   
                
                $stack['NextSTable'] = 1;
                //$stack['SpecialAward'] = 1;
                $slotSettings->SetGameData($slotSettings->slotId . 'NextRoundAction', 1);
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
            

            if($slotEvent != 'freespin' && $freespinNum > 0){
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
            }
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
            $proof['extra_data']                = $result_val['ExtraData'];
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
                $wager['game_id']               = 185;
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

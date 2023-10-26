<?php 
namespace VanguardLTE\Games\WolfMoonCQ9
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
            $roundId = 0;
            $slotSettings->SetBet();  
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 11}],"msg": null}');
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
                                array_push($denomDefine, $initDenom * $this->demon);
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
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = [
                                "g" => "46",
                                "s" => "5.27.1.0",
                                "l" => "2.4.1.0",
                                "si" => "41"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 2;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["0fQQXbUaXmTn9eyByJKctwxBQ9SNis1Ep4YQt/HRgW38xc8GWKuUEaytK00kP5SpDe/U0uxcWoRyBnPQTwFWBKddRCAfoPYOQOHi4k5/Rr0qtKaDRCntK3ucmZYno3p/mn+Rf0kFBPl3dMk+zvsgWDB0ezSsvZyzJ7seTROanSnE6WvTKxReXzc8PVQFCcru5uEG8MWZSfPK3VJCQ8IFeEvl5qrGvaL+GETXFHxKlf95cGB0d2+mmq0kfaC2f0WfKks5T4wWgblZrvo24ggUJQz8dqyVVFjCKb5mgDPHzNR/y3rN26MsbPNtii0=","WaihjVrY00srTq9YAKXmWSH39XoSRem/598Ex4G5wNadJt3Ld3Uesv9TOzfsFkY8ZmwMtQIwc71scVBZbh8JcJvJ7IZm/nLsuc4l5WjQ3PACnTjgHXtt52jgETWWxEom9npjhWBwt86wSHGmgEs7VDHjz/ZZm8CWRj/DRlu+BrCW2cLLSrOpgw9wy6EgbQ83c/67Vn+9FXKWSEUH8+DmuoBmysNuM/YWFIXgIaHN3lxJgz8VnYfSu2d1dPPwUNVkZSziywlvQliIlHdrS5RSuJcYRYb05UneiQVhG85Ambb7m2HJ9uZhofZu1Dz8PhO0AJvNf9w7jZ/GjmOAQP46ICWBKI5zvoCsKK+AUAYXKx/P3iVSTXNe3ywzOgk=","EGbAratPqOeZijDUXALZibg/rvEUEzTUNVAcwN/H2QA/R0A6RzIAuYO6RO3mRcwCP9YyRlqmpYpimRRFsINXsRe3AI2W0GfPofBuZrY9B3Jcbfa6KMegsMgdI2jYCOjIXH6FC95nUFCQA1lbPXAtWQ+he8lERoRrX6zQSodPM/Re9pu54RDcSv25lSt6JL0uF6HKdK2uAY/Zj8R5zBTTL/xYxfheeM9djPYBdA==","Rr1jBq9TBwAdtriqrD7mW4K8DgxV0qyFCfUTez3IUtMkF98M08rI89DGB6Rswax8S0/acZfVsbdLVRQTaqWtSIdqTNZ+2i25RhdvmeGfyV9DJ6Lrchcv8jfGs1H16tdHPxcgR4EQ57ll40QlT56e2MykjIFhbcsjC/NIzsnIPKjgZfuHYrpuFIR5P0HdNjh2MXq25WwcTsNinQExJGDKS04pUjtM+6u7NcGhECtCpLp2MZ3RLjUi16t4aqh4Ostelno1h8xH0UGan4CHBHl3MM0MCE6S98J0SHte8jmW4DzTJNOmSck2pQUleeJtL8HNfvNKYTsLq5/b3/t/g27TlduJXS9fIgFP6L9oFwp3AUChWIErYw7PBWIpLKk=","JjDB312QzmPUN4TaS8L4wkyf+aOxSatKmna4Q70cqnwH9e3hvKkMqXo9iugFO4thWHu/ZFdNoASWOdvmItYFN7TsCm7RIuESp7ewx6NLimiYCShq+q7IgoU5qsmOHaOSANY08s262xSKa1x5X5xOCi5k+CnKfPE3X4EI6y6HGDaq2NaHn6SPsovip9kmE5/m8Oqt8oPf8VVG63mtQ/9oxBziIo9z63ZLLx1f8Mj4qUdVReg8toAd4vcNJLgrT+jutycP/bgZ0Xmv2YVJ9HOxuD+6QEDFThaQk6ZDbZ8JWa3Gc5BRjtCuuDksbdo="],["uh5y7SiJcv17n6L9r24zIyW9633gpeElBhjEYS8zsJUrQ6rDVci1sioxhv8+AmSgp686sakAlKjCCliMpVGNh4pICerYiMrYXQcybFYZPw0W8udAr5vPEEC4+yHCnmeSdsMtm4Yki31BhCC8i5KeHZthOWj+EnRR0wY8SPQiUxE2efSY6aSJDuoJn2I17IO0DfA2h/2jg80ZFaTJBelQ6IiEnQzLt8ThDbPI+5vLoQ59j7Wp9yc5AjOxyV3Yl5yWfuKYJK5P5xw+cD12AsQiOV2GZhtrgJ7xM/bRxVfkFlR91O55dQU4HFPjaz8=","A5fFmmPLpV5jKbcGyBaxcZ+yjCeDntYBfcGzrj41FQ2HO08jrinuagoJHwu5iS9a9gJpWJOCigjMGLdqkja7bpgUAP8xxqALJ6ywg7OruFYUqFDnKIV5HkwCcNUi+srRc0uZmZHxHN3P5gRo+EQdlI/OD4e86CS0QZAyQLVvojBUJH9R1/KqHxz4p3YQsxdRGvBkP6iawz7HOjhXXI3JBpJJ/9sHTvUBXWx9NOyaFRy4GDkmvqnvoGgtiJHMMYjjm3HsIQ52dbd9I8LhzOIhqIxtDnfdFOzwXWb7CPbjqEwcudmgy1F43UxgfV9hSozfMQX8ZqhlBBMb4cLS5Jn3s1j7z75vMt/8u5TOCH8G3HWECA6VGsQpEELEYhU=","H6aLNlSimawBIpU2e03R/R5jvbPyvdNTp1MhC3KDCp2fJ4ebYR1PU8PtxHh5YdOLoCRc4wvdNaO9fpKrOJXTuANBIh3emwmJ+TqFCKetRIgpvWJr8YXk+GFPoUvCS2CQGOu4RPHJRPoJfX1SsJ4aN+enIXiOUSCx2C/0rWjxm0WrU9VCRy3qP6ca+LDHAscfYKhknvUK/BzFLNPiCL3Y2Yj2uzURcOT4nCwG+Q==","fMiLn2jmW3BlNLBZeXibtve0oNZ635xZdj53DbhZc9cReJ7nTCJCOaKSF4XP5Hs7bVV5YJF6m3ousmSnmvMzVewy5rPmQO0Tkvi1QP3gpvRj1LDmtm2ItXGcGnXATdA6nv+MdZ8ci6bmqelnre9/oyybj2xPjO7hLBEJa7VQau193uwb994j1qwgnl1S6ajuHLx2eNMjKJ36l4Plq8Urwh/RJ3vL1req7I+V/Nnu+2nAAv6T78kfDcAOmCNgP6aTssc4SJR8aQnAkQBUc0g0FiR2Uml+6CYfjG4bdpv4cQUSYuLl4BG9igvqxD5JX+R4gJOtoavl8iYYrDEreVAUEiVkYTzWxufA0g3wXLtrN02y6eO9jS+G+4cU9T8=","Un2iaZNN1M5B3c6CV5PDMhGlbRChjCptmeDkv4YsV8BQjxW6QqH7vxayemD9mWYNvo/vgxS3LzoskUkdTb8zTQjK0EoJZDCz7EVA2TdNqJaEAVBW53jtG5YgFYjFpkqMTPJsOvSHh38N94GpnbZ9fx/W1LP2mDxfCVLZ6ZM3ONJ5XM1JiTKvMXtukKv1UOjpZrMaIm0RyLZFhy7Rdfp8FHxf2Siy5M/VO2dntGoWacnlkcpLKDVpujhClZTFT7wrfWBFEgbL1JcOoGAsz4I8XWuqeFHqepOHjl0Ei5YOGRKGvgh3FZcASPqhd38="]];
                            $result_val['FGStripCount'] = 6;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["3rU41j92uwT7OKAYaEQoEf/4N853hO1lgkYK5iK8sdLIk1t4Jm9PuR9me7MAoHEaysGRYoAm9e3PufsurhoO+gJamKbSH/tQ9bVF4pvqiZsdo4ImXfkmgqqAgS/TbUP48YR3AFeh5JvgVkprhqzEBPi6xpTjzvfS485rlFhk/lZAn8lUF/gyjZUHn9E6PcOc8X+i39xG0ALhLmOzrXZpJXz2Zyo+y6XBlNlnzw==","3RBJDBWkF9Vezra6x5ZB2PZ6EHW58Ddyo5Qm4hU4UOhIouKghZRF2yXlwlhVbg8MxI0C3zjtUcGjQIQa/gfvYeP+86qhBzk+IL5J+Q0Sk2Ekbt38+J80tnDHgt9a9q8E6m3hn6Asx3RlhJnttYUDTWrzRicMPdRc8TLTMYoakDLIHdTghIlS+Vi4/rolifPrWoBoGMx7tPS8s2C5YVBg0/NjJ8sreoLQdUzhVQ==","Q6EJoUJSvX9xk57opBSz4K87nwJSPKHiXnvbZldRYWypPiX5Fd8oEdldtCfViNZpDQ3VakNH+L5DDO0ZaqD6b1QuxYMJtqnrsR43xt2a8KwPy/WWgL3Ulo6zFaBE2mh5mZjZdQ4yPaBHVRoYfq82WLbtHoBwqG3Vab5ocPmPMobze2ZTgIzBbQ4u1tm+hQ0u7taGl5OSCDcZPjK2","bmEFhuLb0GGmymI8x6u/VMVGVo+2xwwl00Xp9sRpu/9vctKftw7kZT8lcb4UkEkVbExwB7vMsKp39VSaPZCKMSJpPDDKI6Jl/nyC/3PnviBJj2BiHx60xFbPYqgqiNJsvIF2SM4oqf4wC1ntyeScmSFHjWNLR3RbmTo5jaZr2IW9q97F+/rTrVR9OI8=","ofzLgDg3aL5dTPwwE7NtmTg44sEBbvqozr9/erYzdyVXbyTpkYiLmyi4Qs9lN3GBUJgZtt+WLcOTIWbc5LhkeB0apPuFeqavh52PuZYBZI7DwWPC+3UHy1eu6LiRUcrGrSSz6Grmi+Vz5lmfJJKA0bIUzj7Boi9oYHOpteeyJtfeVhaBkUGwrKifvuk="],["h2lSWcyDgslyJ27i3aZzFpgecn46SgQDeLLr/Wh8SN7wN+b5Lh+pGY+f3KOPOfpyZYa+MU6I8zcrCCNDklpuTA4SY1Fj8mchFoaU0t7Y0W2jkJVu0PK7L2UHu0kwQqmTPGII4HtyUzj8RiaKPYZm7xnJa/DHzfMKIPXBivluc9Vi13ASeFDwTJjw4BdfJy689tErRXLcqLonR78BLVhXfdU6IUs1tGWlAAUyUA==","6E96C59oTHWSjtYLVC6RVTn8hjXJrfYY7pxoDFsPV203/NE5ieFzraje08Cn35vaklg4Aj8kZnebt14DOCipThBe0Cm90NAk/o0TVPbvjDNc8xqbal8dFY5GxZFXWxDiGOlUgK3V7B07qd3cYaRObr/wzMLec99TCYhzCqrKstpfgrwBpZnCKP+SC39Agx7OZAlSUkcqC9s2dseBPdyQGZ/tOeTBBWIsf5U7CQ==","uxeLebcqlbZbm0sXPZZIWXLHE4Lnp/Mibg372V0ELpWMaHAWTay2iIBjOBAwO/iD6htqGtgUf7Lt9ZpQT4jV6BPNKlyNJh41zTWWzj/13b290xeNq8Pbo2WCr8klQ2nlUvEXYpP1493F5P/bzm6qDC7mQjHooVmqKFgteS68iqzqcUe4/L3AvEq911ThjTHvd1yARYydbeFQx/VoR2fz8d5D+/oAKpsSmxqXOA==","wmdp0bCXqz8I5bqR7ak2Bv/9F4L+05uGpDyLbyz1n1Np243/0v9xkGDor43qKqjC394vJjbmlQed52M2AZG7CfXNoMwepVnl89ALiTkt1n01KV0fKB7HU3H63cCHliaZcfp/pavczjEk5cKzlBRvAJemL4tecHlQGOFa1vLH25Ph8aX3HQhA91fbrQQ=","l08h27tCFQkQNLsN1mFXsuSaOunl41PrpwODaKD4DOVr29MHExUwxp1rz/3kAvhvnpf+aDdNeRuBYZZTtxeDE5kHuwdvUTb3aONKN0cD4UQnRVl+EveHoIpE4YvGLHvB10lJz6Bpzl6G8cDDZ+LrcMesvlxs4kEzl1zEnDWbJ0jFIs7ZsWkSGWS3Oko="],["QNbSC3JeuYeCs1Dmh+rsF6OqL+LXWmiD8iK85/G9fSHJc+o1pv14ucYOqKG+b06pU2z7IKsufc1qZVxnRIPuiRskQy0tG4D3ezTXAoZjhK0OqPzNke1sHAPD1gpujvPXP+F4ahT887ERA0Vce+lmUYvZ6/TV2FADj9+JuTW0oj9bIw+FyNaXOlEmCEskvGegPHXRZiUzsbBz5o85gZ3XozalGNJqVeS9NyUW6w==","bmO6JqrfNhvN33aCm5AN3sDXgRBkazE1C27Qtb9xU+ldXXPI8vrOBi+ppbbD/JNAe+63xeJKEPM7pF/U4jsAOlEd0uF7Beowh3Tnib7Pj2Lcitvexacx55rr2MAetpxxOuQMYuLpbMZ2C/4SQpFNuic81tSkxOpe8k/5ZGVX0uz9W39TUBa2cR5pXcWHUGEfezD36Av/ibfn8EUhk0tKGwtl7Rtg2izGS1e+7g==","rbyPbnj50kP6jq1JJ6dttseQuZuEABHCYci2dtG9KnsUP+JhSFH+RKBudEkUlgm5Oc7JUqdHKdmVWD1gOSu4phcbCuOhvoNcNZWSzjoBf6vXAk2+3lolw2WwrhPMQzcpcbpALLifIleSamovCyhcKVTB0md56RjwBI6XhVy6r1Ix6TI6jZF1np370NxOpf4vtR/0b3ag37he1ALAgCvMHg3vge75ZbrjGym+QA==","Y3lBFL9XLnZPnTUXMcEkOzdohwxOVZBnJhpBDnUWGhO0ItmhC6cUSBRjZVnBgXj8lcyWXhN6zh0Pby86YX7zyMmvT7kmNqbNC4vuiym5M3C9fgXTkxeCAmM9PjHSpjkAMoq8Gjj+KjE/NCnF9YmLwvtk9F3HuU+8YmDu01Jbk4rlrK6LlCytmDYj6hRsSJ1YGaD/aJL2/W6v2MVQ","swhrHjZlosAptpcjt2wTCsqU9D/iyBgofi279uhoYJRbXYum6kv8SCrpVu66Joru8dfYRhFmFH8VD3D8vDpM0nSGBbMOZkiVYy/DiPiLQrMHXJluymHE/+IBcd86mU2TDf7/w7NilvzeSJXOU25vjTIJH17O52kB1nENxXxsJcwSpiMwVAJfTBrzu5XuLxkPSbkTgzihq8AVGo1k"],["REd2qfcFCvZShNo82eYf16WgZkEn+JFU5PCiDft2LMWXs2YO0qSTVqOyxEN52SxTHIxE5fkVchreDtqzq6cUNytejQAH81wDxEpwarpM67S44wwNeUJHOq9e75gJAH0b3RRw+imqzNBeI1UTW88QAPWXglrEn1aBFMGb/BNYm9D1Lzjf0Lqh3xUn6Zv+d2uw2n0OV58jhqdIYH9SBTjK8My+I7gLAwIkDhZrbg==","8vg3EmwATTbRQAlPvtzpiKse8kOQAXdmI2JXZe3x6neP4saRaW84ydegk8HxeMnMyb+VpKaTpPUvU8AsuHZHDX6lCyopmZgHiOYVCx16BIp1by8/YXn732BtUqoj8HRWTZq7U+jvM07wjQJCiVjKya4FSVRm5tBfOC+veoqd+Iy4SCXgUH3cgCJhYBmQqrW+vt0WwhY7K+F8bVIZ24Z4yfAfTbTrbypwn9o+besrXa8Nu0KwkmDMipvVyU4=","IF63BuGcACaYQtcvt76oyeWnw3hN6XSLeAK82EullQJ74qzDmjhdM3bvdnYD/sAyK7hweKgpNHOU3nFl8MNvlfvrBbjE/f1wYp30si9Su0JbHjeYlGy+/xfyc41KMoJVXZDt7RcoQ0i8efo+FL5EkrdIaa2UAj7DWXod0/NGh5yosubcIlB4q7EIgHYPi8/hkZVy/FyI4nGe9OghmlD5GKnhtLMWtwpiiJjDgw==","G3fXhgIbKhoy1CFzwFaKttBdG9ipXk99jV7+qVnN7+70nKLum3kPrNYBFZR1RX7SyPQ3vU4VfGxDbAUrHRREeN6vXjTP4PzDdQNsOFMB3WKl3UInmu1/CipS13/v0Yggi9NIm/TKCXRRwvK0AZEfvTIZcXzp911Wj8QhfsbJPXnFUtOEPcJwz9W9zygyucp3kndcTYuQOXoEuX31","nbHubZZjJLWvf08aQHZ6RioDh0aL7DxLD8csdHvV8JZJqzrscpJVD+hRnLWB+an8FTu/lBKeSQIJce6pY8yke278drT6DQY9ILUobHxS2Fbtlvg7M1Wnb08d35Mwr8Bt7eyZn840quQz5qRJspqL0OI1v1xOV0khuFcAk2PkzdA5hOfSTB3Z3bWHhQ1g02U90DDkNEohGMooDwCL"],["Ib2ndQxWiWK3J8Loc80+938bUNEVxrArUB4nPEyAVMp9UXuqYgBdS7yLheyvudF3HDTtuEXjwr4FD71U3kF1R7h2flUfrEkm8JRGOdqYJNNJfgQOAbcipgL51gYmr6CSOedR8neaWc8Br+BeeIO+edggFyEKnyIgkiBLweyL7irQEwdLaQfis7Uutqn/bNuhLz51N53k7bltvdaqKWVi9nq+n7RKRgm/2VPDVzGhhF3SnGLmA4/hbtY7R3I=","UcTSNMVBVHyLDMlaqRMD7NSIfdpt18mFvQziOsN+PByZaovJcWGOvmhdteYJABhPTcqir1xGJMYG0xiYj3UtLZCFI1jgAfk28JuIHw8kTSHuo0UQF7aDxNYfrCoGQqbnYR+/WWrt/uT/sDcQx7qPyIA4Q8E+Ck4vRccKh80+itfkyYt26uOlp4vnCA683ctnShdJokIBDBnwioC5Ka6mOLbmw+zYUlS3yOaMqq0Y48VnXlQu1ra6FfEEJoE=","MQUqDMMcT3itsCqxtU/94VFTwKdZWLRi8uQHDo5zr+fUnQzTQfz1MtMjNPMiHZjhF/FpOScIFHLCoBwSBsWQXPl7Ss/k+FbOhNwE3i9NMwFoExwmPzkHwf0a3X/zUVrNzpIR+CDisAL6i9zWOq0uMLCtowxS7pnFSvUsERf/NmNz42rbprSOvMwEI9Fp0LEkpz7vqJnR3F3uM11myC5pjcYD4dyuR9CXIlltq7q7stbblQG5IPHnSXVql+A=","baJLYe0fboBMTwrSBOG+uh2V2nIsHyf1kDDcz/DmVIaY62Jzo0F+Z1dPRDcNcgp/WoP5gQt3bRxXgX45cZF/4RVlJW2NuhAPetDdHBZ1Hs4V4KIpjsNcKC4tmkZKe20woflkick0wR4rQwE4dzQzX/DK2T+Uk/gjaoBWOelHFQFygZF/10VqwUTsBbVshcB3mUe9Tmf5rThx4NNumAiha8HSZhW3Uku/ggc7Fg==","9kl49uVdtAbBqPL9a5rg0itffzun/RFsTXZkt3UW695H3u2jpPcM4OSPwKRtOmmM0ywTwuC6eNwli5+40EjGEdQm9s0dHMwjkrPA9ykjsMCwbjSHolkSS5JdZiqHXaoLJ6sWUJCLpXhmvPxP1q8rsPK3Kl2r2A1HNRMqDCzOAP2msTdROUoq/G5mGAnROppPkH1NcpdaN7xTpaVL"],["DyCjpQ6CEvI6EDoc5muBaTV63Ahc60aaAl0TJSC2mA1HsMzHHVKmZOV1CfzTNFr6JgbJ65ZbIJ6J08uZmwfsHSlusQyoPBndCqA26R1m6LFrQFSIedfow9Ivbju2mExUN+yXEfZgWCnpAga0ExcsKUZhaINWxZO0ZD2lXolFDb1ADwIb5dfISVOODwOx/Aukl2rToPkyxm5btKG7QO368WMwcv8G+F35RyiPBvMJBE11oYy+Tu2axhkE91M=","1CeBsMOmYkQbyNetYo50/kNVsQrrfSdJqUCJvkE69XXxI1XcMDdb7hAANfp55836DVK5zPBre7ZZf4cpQ6BevX6GjwR9+FvnyO7lb2ee7YY1K0VsTjoIrps+Los4uhRiZitnxGSbEwXy6Xx1yohy3XsarHcY/UeGYRgHTTaq52es2bpoEeadSPXXkwsYLXddLaVrc5ZepYjy821LHo43HpMmG0Qyt8DCeswhCG99bvXPhi9LC02zhTVxJxyZQIhdrXmQLytJOeshiIkE","LtUK8n8HilOGqkdtllu6AqG5ZJVyvD3MPsJrgQTBMxO7aI3Afl9BJvRskGNd3vmSpqqfS0aJNDWXe5eaNxHlweLUh1BPu4ufbAEmc/Pj+GxmrWE2fQurwx6AGmfccDw9IYwmrhHA5BuLTqgiMm6ZEX9MmeZi9e6LjOTmR2oTWBu9Yg0dAmaXObXQfpPVzOguAEtthhNMaus/KWiTeifgl2FiSFadiKWiVgeZOYK8cDPP6Iv0cRE2uwiyqVk=","DMFKQbry8pChm1VsQ/duZUS+5MgMnJcfPaS7VddCaSJdcwxxUeBkpIqutq4r4x0EFVbjkYRcSUaffaOB5SB44oWBSE0zehr7vvl06eutuiYGiruz2dv8mW5P20ygjafGiyDgblKP+nAq1ezd/Pzqo+3mp+K6lzsAvp0u2Y0Yy0m0EIzI1RBDOL6HNnKTIiLJ+ddmPuiDw88XOaEVCQBFMx1QE/LY1l5B82q7ZQ==","DjmEdrQqm3xn6MWqVdCF5e3DUuvzsD3sdMKukXgO37KFoTJbphcNqc4w5C4nPHf/sEQj0Mr+3KsCRqA5+LXNTPb/2p18lKVJQNU6Ty1FlS2mMdslMvMtmykGQgIh3nD9L21NepWJQbz5fO+4Bl8mkdoaHIXNUwbedW14xzYB23/KFTv2Y54phJ9TSx/RY59y+eJm1USx11KqSVbx39JdS/nthdN/tm1IG/OoKg=="]];
                        }else if($packet_id == 31 || $packet_id == 42){
                            if($packet_id == 31){
                                 $betline = $gameData->PlayBet;// * $gameData->MiniBet;
                                 //$lines = $gameData->PlayLine;
                                 $lines = 1;
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
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', $betline * $gameData->MiniBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lines * $gameData->MiniBet);

                                $slotSettings->SetBet();    
                                $slotSettings->SetBalance(-1 * ($betline * $this->demon * $lines * $gameData->MiniBet), $slotEvent['slotEvent']);
                                $_sum = ($betline * $this->demon * $lines * $gameData->MiniBet) / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '660' . substr($roundstr, 3, 9);
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
                                // $originalbet = 38 / $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
                                // $originalbet = round($originalbet,2);
                                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                                $result_val['AccumlateWinAmt'] = ($stack['AccumlateWinAmt'] / $originalbet * $betline);
                                //$result_val['AccumlateWinAmt'] = ($result_val['AccumlateWinAmt'] / 38) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
                                $result_val['AccumlateJPAmt'] = 0;
                                $result_val['ScatterPayFromBaseGame'] = ($stack['ScatterPayFromBaseGame'] / $originalbet * $betline);
                                //$result_val['ScatterPayFromBaseGame'] = ($result_val['ScatterPayFromBaseGame'] / 38) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
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
                            // $originalbet = 38 / $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
                            // $originalbet = round($originalbet,2);
                            $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                            $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            $result_val['TotalWinAmt'] = ($stack['TotalWinAmt'] / $originalbet * $betline);
                            //$result_val['TotalWinAmt'] = ($result_val['TotalWinAmt'] / 38) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
                            $result_val['ScatterPayFromBaseGame'] = ($stack['ScatterPayFromBaseGame'] / $originalbet * $betline);
                            //$result_val['ScatterPayFromBaseGame'] = ($result_val['ScatterPayFromBaseGame'] / 38) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
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
                        $lines = 1;
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
            $roundId = 0;
            if($slotSettings->GetGameData($slotSettings->slotId . 'MiniBet') == 40){
                $roundId = 0;
            }else if($slotSettings->GetGameData($slotSettings->slotId . 'MiniBet') == 50){
                $roundId = 1;
            }
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, $betline * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet'), $lines);
            $winType = $_spinSettings[0];
            $_winAvaliableMoney = $_spinSettings[1];
            // $winType = 'bonus';             
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);
            if($slotEvent == 'freespin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
                
            }else{
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, $betline * $lines * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet'),$roundId);
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
                $stack['BaseWin'] = (($stack['BaseWin'] / $originalbet * $betline) / $this->demon);
            }
            $totalWin = 0;
            if(isset($stack['TotalWin']) && $stack['TotalWin'] > 0){
                $stack['TotalWin'] = (($stack['TotalWin'] / $originalbet * $betline) / $this->demon);
                $totalWin = ($stack['TotalWin'] * $this->demon);
            }
            if(isset($stack['AccumlateWinAmt']) && $stack['AccumlateWinAmt'] > 0){
                $stack['AccumlateWinAmt'] = (($stack['AccumlateWinAmt'] / $originalbet * $betline));
            }
            if(isset($stack['AccumlateJPAmt']) && $stack['AccumlateJPAmt'] > 0){
                $stack['AccumlateJPAmt'] = (($stack['AccumlateJPAmt'] / $originalbet * $betline ));
            }
            if(isset($stack['ScatterPayFromBaseGame']) && $stack['ScatterPayFromBaseGame'] > 0){
                $stack['ScatterPayFromBaseGame'] = (($stack['ScatterPayFromBaseGame'] / $originalbet * $betline ));
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
                        $value['LinePrize'] = (($value['LinePrize'] / $originalbet * $betline) / $this->demon);
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
                $lineData['line_prize']         = round($outWinLine['LinePrize'],2);
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
                $log['detail']['wager']['total_win']    = floor($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'));

                $proof['lock_position']         = $result_val['LockPos'];

                $sub_log = [];
                $sub_log['sub_no']              = $result_val['CurrentSpinTimes'];
                $sub_log['game_type']           = 50;
                $sub_log['rng']                 = $result_val['RngData'];
                $sub_log['multiple']            = $result_val['Multiple'];
                $sub_log['win']                 = round($result_val['TotalWin'],2);
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
                $bet_action['amount']           = round(($betline) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet'),2);
                $bet_action['eventtime']        = $currentTime;
                array_push($log['actionlist'], $bet_action);
                $win_action = [];
                $win_action['action']           = 'win';
                $win_action['amount']           = floor($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'));
                $win_action['eventtime']        = $currentTime;
                array_push($log['actionlist'], $win_action);

                $wager= [];
                $wager['seq_no']                = $result_val['GamePlaySerialNumber'];
                $wager['order_time']            = $currentTime;
                $wager['end_time']              = $currentTime;
                $wager['user_id']               = $slotSettings->playerId;
                $wager['game_id']               = 46;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = ($betline) * $slotSettings->GetGameData($slotSettings->slotId . 'MiniBet');
                $wager['play_denom']            = 100;
                $wager['bet_multiple']          = ($betline / $this->demon);
                $wager['rng']                   = $result_val['RngData'];
                $wager['multiple']              = $result_val['Multiple'];
                $wager['base_game_win']         = round($result_val['TotalWin'] * $this->demon,2);
                $wager['win_over_limit_lock']   = 0;
                $wager['game_type']             = 0;
                $wager['win_type']              = $result_val['WinType'];
                $wager['settle_type']           = 0;
                $wager['wager_type']            = 0;
                $wager['total_win']             = round($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'),2);
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

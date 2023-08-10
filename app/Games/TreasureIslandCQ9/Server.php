<?php 
namespace VanguardLTE\Games\TreasureIslandCQ9
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
                            $result_val['DenomMultiple'] = $initDenom * $this->demon;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = [
                                "g" => "81",
                                "s" => "5.27.1.0",
                                "l" => "2.3.8.4",
                                "si" => "38"
                            ];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 1;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["T7dBEh6F3Aw5qXujGh82xArnw34R/+LJNqUZGA/Wxz2CKZSsq6gsYCOJJVk0Yfc1DnOnk53g0dq6PlLTstsAA7haHnfbdAfgE1yeIUN02WQVs1GbtqNSLeBHuJCcm1GKo+Bars1QU3g+g8jHbqfJMucXWsMZCZYqIV5y6T2BruOIRTKx7zS2rElzkNiIm3c1fSRyPPIo04iISToyxjvENWP2jNCPXL0cFN2Q1n9pswIVjL/THnenlWI1Ip1MxOwCy+lqyGYcs52jpv2YhAHpIrvhvPf5IiFxsDIXXg==","Cso1ougpWhvCExSw9759tootn6fIMZV2lNvSmql3bum5mbAeXzzDrkGvmK4GFpXCM7Op47Btz0PmeeessMJvigExdzfFV/7Y1fPbUHjG19ZPA507r6CUhqRAIubHuHnythGkxL4NuV/n/e+ydlGP43DiOD18M0r81UaFShISW81ioGmoHsSw3Tq2eqdGlH9zKUdSoWXyZuM4HPXfRZVZceccJn6zNO1vKf6bdg==","OuM902pXfZxCwAEnUzPeLc3sQzx/SZRab2IXSECM9abpNdibigtXKqX0J2woGtGOdQUQyB6xEO/vnwvaA97UX/lO/lvC7R3Xgu8+YTQBgnj0RkpH+dROBW85I5MMgzclLWz3fjZO/XCiTL08tO2PxVQf/6Zzf07giOilcBaBrcJn1NaGKBiSQn2GXcyd1jSmE6PnPkPvoXK/uAAHgTbcPEcMnScwagPvPMYc3Q==","Cvjvkf2v0BVmtmueb1ChPv6rTur4jzsiw4fw3tYpgDc5hZ/m0xQSo2GTWsmeGD/d7ODnOFUmcNHOWVaIkQV7F/zSGyD9xktF2XQZvr58lP6eabKwziz8zgs5ZOAylKf+6antbEHegSy0ciCnkoUme7r0siKX1xs1rvsg0lnqM8rPJLcPiyIw7zFKApQsdzmkIvnV3hOZLDTMpj2J5w+6hn+4zqraXMp2RccJ4RyMDh+3t6yIJy2eQ/2kdPE=","dTwyWGqxuCvMtnHA+1I85zJ05LoaME6L9CeMM8DGdf3Wb85dg7odcbMxoLtQjDxgluLkprrtY5OwLVN+xJZOzsFE0NRF6za1PFiREPcowOEE/u9GeEbVwsQOuXkTowJkJ0sazjEzFMHetNaJZfhboiiNUEtn9b81WcBkozlOiKemj8Zu5q4wcQRcCKEpzhusk+mjBIp9oyVxOy5ASvaa8NMhN1EFT+25/+ySMe4Px8nsiEM9xJQSVd+/jmEEOVOl4DE0P/PBlujszqb8"]];
                            $result_val['FGStripCount'] = 5;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["ijG0lChGgFkUIBCuCMHXXCRyKT6IyUg+BAqVG75Rla3kp9TawgWYydAp1w65ZT6vqpHyiYLGCihNPbH4doqzw8t50iUKk/yLCPOl4w/QX0R7N2iN5UHzKK00LXBwoBy5kTCTVsdUbXe4QJHOcMIw/JP/CilkxnimUhXMMjMTWX6tzrN6pPrEE10LhDj/nm7JZZvZsb0cxXsM6e2j","XcNDXuTI2P5w9ZZEiK3neNf9N5qfGJKb6YXWGBFwSrYOGcFHCWGPmcK0fYS7G5pG+Y5kc6Y79Gzqlu9J2Ge4AZ+Ggj0yQib54bty++cJH+hJY4Jxs2/K0WdS+4rtIgvz9qELvVIBdmAFSfnw5rVaitRTp0KPOEKsFiadglTkS5oxXKcoo3LWZnq8pZBtVRW1o89Xl6SQvXzkpu+l2HOKbkigsIwWnoqLY7GhIg==","aTKhd90m97QxkhSB7LtkMbXS+ZbjcrAL+5XtK37GKgereGFeWy9AVFBYroTl3w0Me8Cx2tC3//WHuwpJ+N+IyX5DY1taFHQu0i4PsamqhSvfJYVC6uBdrKq3qDRzZMcO4zPZIOV4XqW0+ngrhuVyb0aUFTx/YseiOgQFjEmrH6mqPsw1CFC4FA17qUi1dMPITvBYefIVCjYO0y7MGioRN9xq8rOc568qXgfLbQ==","uUc3c5d4NmG0Ue8s5sRCpy7KJ5e8qJemGU1DHzu40++9pAVQaIWO4Jf+pDwNykK6rfdUMRZl/Q1P9tkJsH/uIwH7qPR/A4tDDJ4jNnORdozB48kuI75ojUJ8GHybBY6nZi9uYA0yn/SlQ/zoKAQokrNHVcwYvQITUG6LDzSQfzwaMwrJ5AdFkP3glw56jkz2BjuSoxJNaFV8FWJAmfbYnh9IiCbsDRr+vxMHXUP0PT/yG2H10u1pKFIXHZY=","aRFD8jxvuwj8D0mySwpqP7btPIQu0/QYW10ZdSOQawiTo3zrC+OL44PYAmgWFJGbwuXHmebYymV5l2L30Vyu6jT4vxJy43CP05yBdOvhbU24cVJO5ZedgkprB4mbhi9QMcKL9qx7UHMKy7Xo+TUbTSNqaBqUdRrWg8KwB6lif1J69T2qqwTnKYANfiFrbV4c+a274obYSZunhwXdaBeXwvCUiIgzNfQPbXinEYfZnX576HQii3EykU3NKGfeFzfitDRdYWb8oi0T/aqCB/K10v5MHJPmdXBkzKhAvA=="],["BkG0fEdrfw0VvdGnFfBdNTPBzs5+bqCygRl0gNk43AEABMhIFa6BlJdr4CpdbUjcgfwLGXkIcbLyNwIol8ZL/Ll34hU7tZR3rvBG3A3MBbJJbgJT27ya9D8Mq4BZUi7oHXvS/gP97dizB5OHaeJm9kWDNKkD/W+0hoi1u4fOXANfdQ0JkTWeGnwsPMexcnGYqt8ycdfMIeszPn4/","9pjRUddp3k9rxbLScZS1qoteuEaGU29a3N2hTVcOKS8PXzxe3ZK8zaLUeuuHcXhI6k1Bd6A/i0MsOjwaWAuKTnXEWR22pUfpgRKZT4xtE97U968vOd5WDQM9hBj6Ekr8suYq3d0YL/H6toFmJQUjA7cs5bBp/W+if1jYzh/jBAf/lWnTYtwDnvoE7tdt+rT1UwBdRKN7uQ6kni6eiOSr/Mps0CWoTLARQHmD8+K+Ms6V71U9z2qFXSgzmAc=","Msch2IQfCfei4FUR2AhEDQh3bXP+5IrIsC0HIuwm2XS7GeCoAaY5LRKpJszs8pRSHVqas1BufAazviDiC0DgWpLtgSWFQLZ9P0fT+VqHoK/nPF3wWHGseVhQ7NUp6vZi9po3+AuP79psB8vCUupKh0ojTzEzch06Dz532smrIZQia/ye4k1LVUWVq+Z19IoUFQavGYKmW5mL+ifjr9Wk9i3EE5DCBca6CdkzMg0V/cz9KuaT8/Kr+ZQFDgw=","PCoR8YOFNG6jsZTeXZbbwY8g+vz1mTzSgWKntolBaY/S7hcuMsZWJ5QlcVuUM3ZtWMmGG1XYzq0jwdUa9imPaoIUu+ecZfhhAKtNRBky2kznQTPcob3teHXBw22eGNH2SJOH/OTcLoQxiglI07Pd3RcRKnk17vq79MK445UBQcHcoX3XSPx8YerlTns04hwwRulClwgbrCLw2S9994coBTKColaODbbNmQTvzsAWndXosJFxE1y46tAxdFCNkFDpvcclCabpVeO9yjaL","EOoJ26BPulDVa2YyAHNfYda+ZcJuKGpnH0ENpVbrPYW13M+aUjIbhE8CUaOs6AA2iiLdkk5GIsouqmGUbE2tL6qZ92D4NyQEOQlmD4131ngow+R2fhE0RO4KNIbOx4CvfjNyQhufLepZ5vTzVftE9iNWEOsMj4OHJCyWYcPMrwg5ayEi96beEvcUe80tGROz3HsxQIFsZP8et7nlAyZrWOijTMih/LhowQ+mk/3qP0SZktVidnBhmuE2crcbQLGXQ18gizfIav0ZZ/XwTmr5/L9hA2N4+F5Jyj7XrQ=="],["fIs86YcZo4mC1zs8hV+F+bOA8NjHkZgh2ILG45/bzjXIMlpGu+3RWm0gb7v0C07Rs6MEvssX0nKxY/XND6+BownLXgkpgelEbcVhu2xheomZq/ipesJwAXIvhem0Irw1Le5RbkZAFS16Rae+UwNRep7HiIjjd9qQoqnk3ef44DEjfnpruG/VzSvJR9rKTjOUsokxVDglDoOisQcx","j9vVpuA87foQUkQmYCFk+ArWh7hSK/s1okoWc3lfTvOF11LOIlOqGueJ+2pZnl2hGCT+n8qJhJON0GXi9de+cigAQCcHnnSS/7V972+ts5LtK+ATmwNUzjfuh+lzHn/DDhIKckSe0dfZuZ+k7H3vpWExqEDT1Ve/F8MhLJLK272jrVEV5JZP8wp9m/mvWgQiVmdZg8zkaV0tRxlicucQ/Bt/rA1W/laGrAj1TsGZWVVcCpFnH3/26aWcxo0=","r45SImjsPRSZ79rCXswctR/rM5rOP3h/Fk7PPzh6Pdau9L7GfWU35iUKl8FYFzkXBIzdvmcsJ8CPJN0wMOrPZwF72mphm5RNkn16sCHeffo9yfZbStLXfU46oPK1/G+iT/xogBT8H870m39wYGDxUPK4d4EiNmhrd3WHW4LJbOOirM5mMcIEAycMSxZUw54iwkcf5SX0azu+tldsDWIB4kK47u4oZGO74qDTX0hkje4MrdegzRCs7t8Z6c9mt+C5/PfByFw1UkjgnmZQ","EuFUKla2VofcvQ3gYJ5PjUSIkzMku7lSch8pANv4ycK6jm92JMecLbnN9E3l++fxErSRZWQuGAsgfUAyNV8p9VqlmqNnSHOKzCcxJEV9qIc/x5buytvzDtI3yAQ70Xii5rNj2R0qGIPv4o35EkYcHJ9rdHYD9ykNfh0tObwO8Q3Zt26Dm6fI3IeJr/FzMnNn02K63jwuDl3KoJTiMbqbeiWIsNVScKfUT/NAnGZZWteyA5RJOdPKoChME8PCf/e14onbIT8n8yIR8/vJ","2IDT90hGSlwqrvV3Kzxr9WsayeEqinevXAiIoupep9PGhfhrV0gyrek8Nq8onmSKUHhvsfiq2j/a/fX0vLuveGo7EbQlxz8IYFvJhA/LNyUsOk0WSTMJQVkvg8dsY7IL4fnR+HXS2ve9ctNPs7Zai+/2GzXPp83ygZdtj5CpuDtY2ivrzl53aNAQi3cLZ/+/WurlP4G9i5tWZifM0u3cWEV7IUprDmQ6+KQdo11Iw9FbBVet9YW/yW5NZPCcXGIvTm07Gyu0yWsuMWDt89wuSOSvDQ7kGvPvvXedwsAsXd7dM/+7ye48/CArFB4="],["wCzz4sWe9HRBrKGIQKbIA+SAqZqnvPmvrHT/z7HyhUSN5LIYHXBjmpegdlmpqpDJv9B8lcGkkcnyP2erx9SfNmAmgF5Fe3ILwf2V+jEhx/445D80l7gVbsD53amutF3iXhbbfd2wUPyGFDz9UmCuQA4USN+gTt7Ff4IrWCEiC40VeGUKMsIFo88Adz5k/Bw/RbKjyepoZQizgc0a","O0UX5yub8LloqnC2haYReHz9X3KqykaNf6Qa0sWY3Qshz9zkfVDIuhRl5MZ9diJl0Hyrfl65wk/bNy1Kx6o5e0sMIj3emWLNjvyp0zOHYFkM73MlWecpCdLuuApd+P4mTlfE9f7XHr3srLBkbnOeAosoMWmEA5Ht8Ieh47nMUVOX/6IyM8EOQdHwkBo33aXF2shYFyf1WN4jjTuq3t4XZJQifw7wN7NYUztEyb73G0hWdntEzH4YbWkwnD2Tk+Zx5WAa4B1RoIeeyXpq","tr6nn0xwNKHPXW4nokeCEdVdu+fmAv0H1Ujeb6v2RcCLj04VzTD4/Y8VqYBZW1CReKLNXh1zh3a8/eg6I5WRUOTTww92MAjGgjx9c79WyZKN+NC2a7VYL3DnaTSc3bIJVgKyPx2JXNhnKeK47HWg61UkJm0en1Bd+5UK2FHcTa7iXH0WfxzaE6NKGt5MV8I8WRZZG4eiKhnrroVmNhWQi4B6fXVnZiqbGRe9Z4EniWO5aWohKODYFj4Crwn6KyGpT8s1Lx9IqyVS9jcxIRYyNS/1DCNZORH0gH21aA==","tm0pBzIH6uW5FbAc5sKyIcF++z9cp+rCl8Q/zObXoNTPMaTZDmA8c7GZWkVzZ13MzYkJ2jhkl8raXDAQpH9loCMR/AZY3GLulsSPAYDZ55M1IIGivY1rLZW7i9oorxv2IyNFMmwH9JWt9j6KWovPejfgECG7ZJetcKgumHrZBvgDI/xdRICD3uQ59aocbPESvUsr8Tq/Zy77vJieLcfi+umI+QQByywaktzz1lzzTQOBp/zEmlsItRACP4wyq0+RxpV8XnsCMh9WkJBU/nZFdDgddJz5H/YYxWb9TA==","vcg8K4WiWTjkNoIaBp99yDiqpOTb67+Veqxj+EOHjOZjfkgN/fc7UeUk0NcefsrdG7Kiur0lYVZSC/buWmpIo/X/v2jxKfHiu4tFU9xdhsA4XH8AutfDCcKgduleLmCZSuEDB+zVEgbbKwPr6prW80CpPD9QZVmOA/3MZtN/aGdkzKZOLvA8P/Q8Yw463l51Hzpn+Q2ANsiL+SBQFfBKoznW/lvhNFryAOcc1akGDI9W+rtW3pLRGKjmywlK3YvqgrDk2ngzGb6e3ms6FofN34KKhW95Ng1T8/FvpREBRSNHbBEKULyFZo0H0wwL1G3EW0gV2UhZl6cMY3T4"],["9njWPPklXE2v9sWoyiN2xXxXNdUjT55V/Cp7Q3yMkw1TnSFUjXibvK50fwSSHw+TierVsjT0OQ2hpQ6WBTWP9O95RF5RFy/j7dV2csM8HFdAlnP4+GAaP1WA1+PVtFlXbp5tOuc9qClDxUfHT0A6gZW12taqixsHp4fcq8N3IODwV0uce2HaktM1kUKFLjW1mCl59JUa/JvWbWYd","GqD357jaredM30z1XSnX2IJzhXlZd2EuOCtRB8iP+cxlw58NX9+ww8wzZTunWLRjGNYLv4RIV/ynvbQfPhAIJUwInikOBrnW1Q82YHEGldWmrHT1Nv+sxKXiSp6+Y5ZOAHmmJ0E98o6LcP8ghm4l5cUht68b7gHx11KvqmuL3Byql17/OPD6qYQrAVpMhKNp4I4rRZ9sltA2jn6U+YHr1LYcqdix4YfQkwFTKYD28DeXbCWG36bSXAyV66o5ebtQgb4+kxCdHcmt7HWIUQfDpGfou+R0Rgm32gfl2A==","1WyrA5twICuw4aMQbqxWureNKVTDHGrqyH7Y12jlC5vEurHqRuHdWYd8HElbqsBvk+VEQludw+xla1H6Vym2/GesVOhHiVGrRRzgRk9ZMXnuh8hCOTjdsOfQPsnuVq0WRUB4KQ5Xci3qmBHNngngXFi6XGUiToDfIXi4kBlDvGkzSdm8Gg+QvHTnYiTKlZScX19aIBub2y5fvcjfsq7g2s2Yl9dEgjPLbW2OuxFIHYfveZQy09Ay0v5bx76RM+OcDxliHrIZETFC/Barlufr6u4NZoBdthVe2yw88A==","r88VexIcYnnSWItseaUaffsilWjiUdbMXXbkeXNQgv3D1zl+JJ0/yfHs9744IgwYPaY1ND9Eqv6e5IhdUz1e5BPXPMFWP3hHCmwZMICJzxs3rQ6KcPhPGiphUJJBDK78tZiH/nT6FpLAqdxJ1d7uFkJXIdrW2AuXROen6ctjWUT+fMbmkyh9kkaZcyJedlhTG0NcAa6etPmZfh/+zCow5kVpBxYh2PGTD8abAfyHBD9GrxhMIsAjNSj2oJmwfm7o4sar1mLp2oACcbvsUgcf1dRa55zCAj4rfo7OaZ6eRu82ALgZOsan2qi4X1A=","nozQIl9f50QWowWjevG5qsDN4g3kk3D3DpTJ5HKDiYCH4q3/fpnKufwcfeQltx/nGWkuqCIou52wLzXWmjUF+0CM1nU65dLJmyXKg0bRW/lzXPtolsTTJI9CmIdmxr0ElDlfe3O7sIGPi5+S71SWohAKz3HCxW5+d0iS12u/IbWXktCkbmEDWjV2jJx85bF8VXx4GEmNqBnNIK002KJXt7zLLfQ/5rWKkOCMbsKyreuiiobyKEgQ6t6BcA+zgBACUdx1PYj2/xZMkzIBT5YGbNU0+WBcAyCCvocuWMuLQ5uS2k/uGUIAyfpYkzXNekQ9BnzNBQdVYJbUqC8l"]];
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
                                $slotSettings->SetBalance(-1 * ($betline * $lines), $slotEvent['slotEvent']);
                                $_sum = ($betline * $lines) / 100 * $slotSettings->GetPercent();
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
                        $lines = 40;
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
                $slotSettings->SaveLogReport(json_encode($gamelog), ($betline / $this->demon) * $lines, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
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
                $bet_action['amount']           = ($betline / $this->demon) * $lines;
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
                $wager['game_id']               = 81;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = ($betline / $this->demon) * $lines;
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

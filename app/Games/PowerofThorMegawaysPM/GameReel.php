<?php 
namespace VanguardLTE\Games\PowerOfThorMegawaysPM
{
    class GameReel
    {
        public $reelsStrip = [
            'reelStrip0_0' => [],

            'reelStrip1_0' => [],
            'reelStrip1_1' => [],
            'reelStrip1_2' => [],
            'reelStrip1_3' => [],
            'reelStrip1_4' => [],
            'reelStrip1_5' => [],

            'reelStrip2_0' => [],

            'reelStrip3_0' => [],
            'reelStrip3_1' => [],
            'reelStrip3_2' => [],
            'reelStrip3_3' => [],
            'reelStrip3_4' => [],
            'reelStrip3_5' => [],

            'reelStrip4_0' => [],

            'reelStrip5_0' => [],
            'reelStrip5_1' => [],
            'reelStrip5_2' => [],
            'reelStrip5_3' => [],
            'reelStrip5_4' => [],
            'reelStrip5_5' => [],

            'reelStrip6_0' => [],

            'reelStrip7_0' => [],
            'reelStrip7_1' => [],
            'reelStrip7_2' => [],
            'reelStrip7_3' => [],
            'reelStrip7_4' => [],
            'reelStrip7_5' => [],

            'reelStrip8_0' => [],

            'reelStrip9_0' => [],
            'reelStrip9_1' => [],
            'reelStrip9_2' => [],
            'reelStrip9_3' => [],
            'reelStrip9_4' => [],
            'reelStrip9_5' => [],
        ];
        
        public function __construct()
        {
            $temp = file(base_path() . '/app/Games/PowerofThorMegawaysPM/reels.txt');
            foreach( $temp as $str ) 
            {
                $str = explode('=', $str);
                if( isset($this->reelsStrip[$str[0]]) ) 
                {
                    $data = explode(',', $str[1]);
                    foreach( $data as $elem ) 
                    {
                        $elem = trim($elem);
                        if( $elem != '' ) 
                        {
                            $this->reelsStrip[$str[0]][] = $elem;
                        }
                    }
                }
            }
        }
        public $winLines = [];
        public $repeatCount = 0;
        public $slotSettings = NULL;
        public function generationFreeStacks($slotSettings, $game_id){   
            $WILD = '2';
            $SCATTER = '1';
            $REELCOUNT = 6;
            $freeSpins = [10, 14, 18, 22, 26, 30];
            $this->slotSettings = $slotSettings;
            $slotEvent = [];
            for($i = 0; $i < 4; $i++){
                $bfinish = false;
                while($bfinish == false){
                    $totalWin = 0;
                    $totalReel = [];
                    $freespinCount = $freeSpins[$i];
                    $freespinType = $freespinCount;
                    $slotEvent['slotEvent'] = 'freespin';
                    $tumbleMultiplier = 1;
                    for($j = 0; $j < $freespinCount; $j++){                        
                        $isTumb = false;
                        $tumbTotalWin = 0;
                        $lines = 20;
                        $bet = 1;
                        $lastReels = NULL;
                        while(true){
                            $_spinSettings = $slotSettings->GetSpinSettings('freespin', $bet * $lines, $lines);
                            $winType = $_spinSettings[0];
                            if($winType == 'bonus'){
                                $winType = 'win';
                            }
                            for($kk = 0; $kk < 50; $kk++){
                                $this->winLines = [];
                                $winMoney = 0;
                                $reels = $slotSettings->GetReelStrips($winType, $slotEvent['slotEvent'], $lastReels, 0);
                                    /* 윈라인 검사 */
                                $this->checkWinLines($reels);

                                /* 스캐터심볼 검사 */
                                $scatterCount = $this->getScatterCount($reels);

                                $winMoney = array_reduce($this->winLines, function($carry, $winLine) {
                                    $carry += $winLine['Money']; 
                                    return $carry;
                                }, 0) * $bet;
                                $winMoney = $winMoney * $tumbleMultiplier;
                                
                                if($scatterCount >= 3){
    
                                }else if($winType == 'win' && $winMoney > 0){
                                    break;
                                }else if($winType == 'none' && $winMoney == 0){
                                    if($kk >= 40){
                                        $winType = 'win';
                                    }
                                    break;
                                }
                            }

                            $lastReels = $this->buildReelSetResponse($reels);
                            $item = [];
                            $item['Reel'] = $reels;
                            $item['TumbleMultiplier'] = $tumbleMultiplier;
                            array_push($totalReel, $item);
                            if($winMoney > 0){
                                $tumbleMultiplier++;
                                $totalWin = $totalWin + $winMoney / $lines;
                            }else{
                                break;
                            }
                        }
                    }
                    if ($totalWin > 30 &&  $totalWin < 150) {
                        $this->SaveReel($game_id, $freespinType, $totalReel, $totalWin, $freespinCount);
                    }
                    $count = \VanguardLTE\PPGameFreeStack::where([
                        'game_id' => $game_id, 
                        'free_spin_type' => $freespinType, 
                    ])->count();
                    if ($count > 10000)
                    {
                        $bfinish = true;
                        break;
                    }
                }
            }
            $theEnd = false;
        }
        public function SaveReel($game_id, $freespinType, $totalReel, $totalWin, $freespinCount){
            \VanguardLTE\PPGameFreeStack::create([
                'game_id' => $game_id, 
                'odd' => $totalWin, 
                'free_spin_type' => $freespinType, 
                'free_spin_count' => $freespinCount, 
                'free_spin_stack' => json_encode($totalReel)
            ]);
        }
        public function checkWinLines($reels) {
            $REELCOUNT = 6;
            $EXTENDEDSYMBOLCOUNT = 8;
            $S_BLANK = 19;
            $S_SCATTER = 1;

            /* top, reg 릴 합치기 */
            $extended_reels = $reels['reg']['symbols'];
            for ($i=0; $i < $REELCOUNT; $i++) { 
                if ($i == 0 || $i == $REELCOUNT - 1) {
                    /* 2,3,4,5번 릴이 아닌 경우 빈값 등록 */
                    $extended_reels[$i][-1] = $S_BLANK;
                }
                else {
                    /* 매 릴의 -1 위치에 top 릴 심볼 등록 */
                    $extended_reels[$i][-1] = $reels['top']['symbols'][$i - 1];
                }

                /* 베이스인덱스 0으로 리셋, -1 인덱스 없애기 */
                ksort($extended_reels[$i]);
                $extended_reels[$i] = array_values($extended_reels[$i]);
            }
            
            for ($symbolId=0; $symbolId < $EXTENDEDSYMBOLCOUNT; $symbolId++) { 
                $firstSymbol = $extended_reels[0][$symbolId];
                
                /* SCATTER, 빈 심볼이면 스킵 */
                if ($firstSymbol == $S_BLANK || $firstSymbol == $S_SCATTER) {
                    continue;
                }

                $this->findZokbos($extended_reels, $firstSymbol, 1, [$symbolId * $REELCOUNT]);
            }
        }
        
        public function findZokbos($reels, $firstSymbol, $repeatCount, $positions){
            $S_WILD = 2;
            $REELCOUNT = 6;
            $EXTENDEDSYMBOLCOUNT = 8;
            $bPathEnded = true;

            if($repeatCount < 6){
                /* reg, top 통합릴셋이므로 최대심볼갯수는 7 + 1 */
                for($r = 0; $r < $EXTENDEDSYMBOLCOUNT; $r++){
                    if($firstSymbol == $reels[$repeatCount][$r] || $reels[$repeatCount][$r] == $S_WILD){
                        $this->findZokbos($reels, $firstSymbol, $repeatCount + 1, array_merge($positions, [$repeatCount + $r * $REELCOUNT]));
                        $bPathEnded = false;
                    }
                }
            }

            if($bPathEnded == true){
                if(($firstSymbol == 3 && $repeatCount == 2) || $repeatCount >= 3){
                    $winLine = [];
                    $winLine['FirstSymbol'] = $firstSymbol;
                    $winLine['RepeatCount'] = $repeatCount;
                    $winLine['Positions'] = $positions;

                    /* 페이테이블 검사 */
                    $winLine['Money'] = $this->slotSettings->PayTable[$firstSymbol][$REELCOUNT - $repeatCount];
                    array_push($this->winLines, $winLine);
                }
            }
        }

        public function getScatterCount($reels) {
            $S_SCATTER = 1;
            $REELCOUNT = 6;
            $topScatterCount = count(array_keys($reels['top']['symbols'], $S_SCATTER));
            $regScatterCount = 0;

            for ($reelId=0; $reelId < $REELCOUNT; $reelId++) { 
                $regScatterCount += count(array_keys($reels['reg']['symbols'][$reelId], $S_SCATTER));
            }

            return $topScatterCount + $regScatterCount;
        }

        public function getRandomValue($probabilityMap) {
            $max = array_sum(array_values($probabilityMap));
            $randNum = random_int(1, $max);

            $sum = 0;
            foreach ($probabilityMap as $key => $probability) {
                $sum += $probability;
                if ($randNum <= $sum) {
                    return $key;
                }
            }

            return null;
        }

        public function getTumbleSymbolCount($reels) {
            $REELCOUNT = 6;

            /* 텀블심볼정보 추가 */
            $count = 0;
            $winLines = $this->winLines;

            if (count($winLines) > 0) {
                /* 윈라인 심볼 얻기 */
                $winSymbolPositions = [];
                foreach ($winLines as $winLine) {
                    $winSymbolPositions = array_merge($winSymbolPositions, $winLine['Positions']);
                }
                $winSymbolPositions = array_unique($winSymbolPositions);

                /* 윈라인 심볼분리 */
                foreach ($winSymbolPositions as $pos) {
                    if ($pos < $REELCOUNT) {
                        /* top 릴에 위치한 심볼 */
                    }
                    else {
                        /* reg 릴셋에 위치한 심볼 */
                        $count ++;
                    }
                }
            }

            return $count;
        }

        public function buildReelSetResponse($reels) {
            $REELCOUNT = 6;
            $S_WILD = 2;
            $S_HAMMER = 13;

            /* reg 릴셋 1차원배열 생성 */
            $flattenRegSymbols = [];
            $saRegSymbols = [];
            $sbRegSymbols = [];

            foreach ($reels['reg']['symbols'] as $reelId => $symbols) {
                foreach ($symbols as $k => $symbol) {
                    $flattenRegSymbols[$reelId + $k * $REELCOUNT] = $symbol;
                }
            }
            ksort($flattenRegSymbols);

            /* top 릴셋 1차원배열 생성, 역방향 정렬 */
            krsort($reels['top']['symbols']);
            $flattenTopSymbols = array_values($reels['top']['symbols']);

            /* 텀블심볼정보 추가 */
            $topSymbols = [];
            $regSymbols = [];
            $winLines = $this->winLines;

            if (count($winLines) > 0) {
                /* 윈라인 심볼 얻기 */
                $winSymbolPositions = [];
                foreach ($winLines as $winLine) {
                    $winSymbolPositions = array_merge($winSymbolPositions, $winLine['Positions']);
                }
                $winSymbolPositions = array_unique($winSymbolPositions);

                /* 윈라인 심볼분리 */
                foreach ($winSymbolPositions as $pos) {
                    if ($pos < $REELCOUNT) {
                        $pos = 3 - ($pos - 1);
                        $symbol = $flattenTopSymbols[$pos];
                        /* top 릴에 위치한 심볼 */
                        array_push($topSymbols, "${pos},${symbol}");
                    }
                    else {
                        /* reg 릴셋에 위치한 심볼 */
                        $pos = $pos - $REELCOUNT;
                        $reelPos = intdiv($pos, $REELCOUNT);
                        $reelId = $pos % $REELCOUNT;
                        $symbol = $reels['reg']['symbols'][$reelId][$reelPos];
                        array_push($regSymbols, "${pos},${symbol}");
                    }
                }

                /* 망치심볼 추가 */
                $hammerReels = $reels['reg']['hammer_reels'] ?? null;
                if (isset($hammerReels)) {
                    // 1, 2번릴에 나란히 있을때만
                    if (count($hammerReels) >= 2 && ($hammerReels[0] + $hammerReels[1] == 3)) {
                        foreach ($hammerReels as $reelId) {
                            /* top 릴기준 변환 */
                            $pos = 3 - ($reelId - 1);
                            array_push($topSymbols, "${pos},${S_HAMMER}");
                        }
                    }
                }
            }

            $objRes = [
                'reg' => [
                    'reel_set' => $reels['reg']['reelset_id'],
                    's' => implode(",", $flattenRegSymbols),
                    'sa' => implode(",", $reels['reg']['after_symbols']),
                    'sb' => implode(",", $reels['reg']['before_symbols']),
                    'sh' => '7',
                    'st' => 'rect',
                    'sw' => '6',
                ],
                'top' => [
                    'reel_set' => $reels['top']['reelset_id'],
                    's' => implode(",", $flattenTopSymbols),
                    'sa' => $reels['top']['after_symbols'],
                    'sb' => $reels['top']['before_symbols'],
                    'sh' => '4',
                    'st' => 'rect',
                    'sw' => '1',
                ]
            ];

            /* 텀블심볼 결정 */
            if (count($regSymbols) > 0) {
                $objRes['reg']['tmb'] = implode("~", $regSymbols);
            }

            if (count($topSymbols) > 0) {
                $objRes['top']['tmb'] = implode("~", $topSymbols);
            }

            /* 망치심볼 결정 */
            if (isset($reels['reg']['hammer_reels'])) {
                /* 원본 릴셋 */
                $flattenRegSymbols = [];
                foreach ($reels['reg']['isymbols'] as $reelId => $symbols) {
                    foreach ($symbols as $k => $symbol) {
                        $flattenRegSymbols[$reelId + $k * $REELCOUNT] = $symbol;
                    }
                }
                ksort($flattenRegSymbols);

                $objRes['reg']['is'] = implode(",", $flattenRegSymbols);

                /* 망치심볼에 의한 WILD 심볼 셋팅 */
                $wildSymbols = [];
                foreach ($reels['reg']['hammer_reels'] as $reelId) {
                    $reel = $reels['reg']['symbols'][$reelId];
                    $symbols = array_map(function($pos, $symbol) use ($reelId, $S_WILD, $REELCOUNT) {
                        if ($symbol == $S_WILD) {
                            $flattenPos = $reelId + $pos * $REELCOUNT;
                            return "${S_WILD}~${flattenPos}";
                        }
                        return null;
                    }, array_keys($reel), $reel);

                    $wildSymbols = array_merge($wildSymbols, $symbols);
                }

                /* null인 심볼 제거 */
                $wildSymbols = array_filter($wildSymbols);
                $wildSymbolCount = count($wildSymbols);

                $objRes['reg']['ds'] = implode(";", $wildSymbols);

                $dsa = array_fill(0, $wildSymbolCount, 0);
                $objRes['reg']['dsa'] = implode(";", $dsa);

                $dsam = array_fill(0, $wildSymbolCount, 'v');
                $objRes['reg']['dsam'] = implode(";", $dsam);
            }
            
            ksort($objRes['reg']);
            ksort($objRes['top']);
            return $objRes;
        }

        public function stringifyReels($reels) {
            /* 다시 역정렬 */
            $topSymbols = explode(",", $reels['top']['s']);
            krsort($topSymbols);
            $reversed_top_reel = array_values($topSymbols);

            /* [19, top_reel, 19, reg_reel] */
            $regSymbols = explode(",", $reels['reg']['s']);
            $res = array_merge([19], $reversed_top_reel, [19], $regSymbols);
            return implode(",", $res);
        }
    }

}

<?php 
namespace VanguardLTE\Games\TheDogHouseMegawaysPM
{
    class GameReel
    {
        public $reelsStrip = [
            'reelStrip0_1' => [], 
            'reelStrip0_2' => [], 
            'reelStrip0_3' => [], 
            'reelStrip0_4' => [], 
            'reelStrip0_5' => [], 
            'reelStrip0_6' => [], 

            'reelStrip1_1' => [], 
            'reelStrip1_2' => [], 
            'reelStrip1_3' => [], 
            'reelStrip1_4' => [], 
            'reelStrip1_5' => [], 
            'reelStrip1_6' => [], 

            'reelStrip2_1' => [], 
            'reelStrip2_2' => [], 
            'reelStrip2_3' => [], 
            'reelStrip2_4' => [], 
            'reelStrip2_5' => [], 
            'reelStrip2_6' => [], 

            'reelStrip3_1' => [], 
            'reelStrip3_2' => [], 
            'reelStrip3_3' => [], 
            'reelStrip3_4' => [], 
            'reelStrip3_5' => [], 
            'reelStrip3_6' => [], 

            'reelStrip4_1' => [], 
            'reelStrip4_2' => [], 
            'reelStrip4_3' => [], 
            'reelStrip4_4' => [], 
            'reelStrip4_5' => [], 
            'reelStrip4_6' => [], 

            'reelStrip5_1' => [], 
            'reelStrip5_2' => [], 
            'reelStrip5_3' => [], 
            'reelStrip5_4' => [], 
            'reelStrip5_5' => [], 
            'reelStrip5_6' => [], 

            'reelStrip6_1' => [], 
            'reelStrip6_2' => [], 
            'reelStrip6_3' => [], 
            'reelStrip6_4' => [], 
            'reelStrip6_5' => [], 
            'reelStrip6_6' => [], 

            'reelStrip7_1' => [], 
            'reelStrip7_2' => [], 
            'reelStrip7_3' => [], 
            'reelStrip7_4' => [], 
            'reelStrip7_5' => [], 
            'reelStrip7_6' => [], 

            'reelStrip8_1' => [], 
            'reelStrip8_2' => [], 
            'reelStrip8_3' => [], 
            'reelStrip8_4' => [], 
            'reelStrip8_5' => [], 
            'reelStrip8_6' => [], 

            'reelStrip9_1' => [], 
            'reelStrip9_2' => [], 
            'reelStrip9_3' => [], 
            'reelStrip9_4' => [], 
            'reelStrip9_5' => [], 
            'reelStrip9_6' => [], 

            'reelStrip10_1' => [], 
            'reelStrip10_2' => [], 
            'reelStrip10_3' => [], 
            'reelStrip10_4' => [], 
            'reelStrip10_5' => [], 
            'reelStrip10_6' => [], 
        ];
        public function __construct()
        {
            $temp = file(base_path() . '/app/Games/TheDogHouseMegawaysPM/reels.txt');
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
        public function generationFreeStacks($slotSettings, $game_id){
            $WILD = '2';
            $SCATTER = '1';
            $REELCOUNT = 6;
            $scatters = [
                [15, 18, 25, 30],
                [7, 12, 15, 20]
            ];
            $slotEvent = [];
            for($fsType = 0; $fsType < 2; $fsType++){  // $fsType -> 0, 1 : Raining / Sticky
                for($try = 0; $try < 2; $try++){
                    $bfinish = false;
                    while($bfinish == false){
                        $freespinType = $fsType * 4 + $try;
                        $totalWin = 0;
                        $totalReel = [];
                        $freespinCount = $scatters[$fsType][$try];
                        $fssticky_wildset = $this->GetStickyWildSet($fsType);
                        $lastWILDCollection = [];
                        $lastNewWILDCollection = [];
                        $slotEvent['slotEvent'] = 'fsRaining';
                        if($fsType == 1){
                            $slotEvent['slotEvent'] = 'fsSticky';
                        }
                        for($subtry = 0; $subtry < $freespinCount; $subtry++){                        
                            $lines = 20;
                            $betline = 1;
                            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $betline , $lines);
                            $winType = $_spinSettings[0];
                            if($winType == 'bonus'){
                                $winType = 'win';
                            }
                            for($kk = 0; $kk < 50; $kk++){
                                $this->winLines = [];
                                $subtotalWin = 0;
                                $reels = $slotSettings->GetReelStrips($winType, $slotEvent['slotEvent'], 0, $lastWILDCollection, false);

                                $originReels = array_merge(array(), $reels);        // 변경되지 않은 릴배치표 보관, 프리스핀에 이용

                                $wildsCollection = [];
                                $newWILDCollection = [];  

                                /* 프리스핀일때 와일드심볼 생성 */
                                if ($slotEvent['slotEvent'] == 'fsRaining') {
                                    $reels = $this->generateWILDs($reels, $slotEvent['slotEvent']);
                                }
                                else if ($slotEvent['slotEvent'] == 'fsSticky') {
                                    $reels = $this->generateStickyWILDs($reels, $fssticky_wildset, $lastWILDCollection, $subtry + 1, $freespinCount);

                                    /* 이전 WILD심볼을 그대로 유지 */
                                    $wildsCollection = $lastWILDCollection;
                                }
                                /* 와일드 멀티플라이어 할당 */ 
                                for ($i=1; $i <= $REELCOUNT; $i++) { 
                                    $wilds = array_keys($reels["reel{$i}"], $WILD);

                                    foreach ($wilds as $idx => $pos) {
                                        if ($slotEvent['slotEvent'] == 'fsRaining') {
                                            /* 레이닝 프리스핀 WILD 멀티플라이어는 최소 x2 */
                                            $multiplier = $slotSettings->GetMultiValue(2);
                                        }
                                        else if ($slotEvent['slotEvent'] == 'fsSticky' && ($i == 2 || $i == 3)) {
                                            /* 스티키 프리스핀 WILD 멀티플라이어 2,3번릴에서는 x2 */
                                            $multiplier = 2;
                                        }
                                        else {
                                            $multiplier = $slotSettings->GetMultiValue(1);
                                        }
                                        
                                        $wildPos = $pos * 6 + $i - 1;

                                        /* 스티키프리스핀은 와일드심볼 유지, */
                                        if (!array_key_exists($wildPos, $wildsCollection)) {
                                            $wildsCollection[$wildPos] = $multiplier;
                                            $newWILDCollection[$wildPos] = $multiplier;
                                        }
                                    }
                                }
                                /* 윈라인 검사 */
                                for($r = 0; $r < 7; $r++){
                                    if($reels['reel1'][$r] != $SCATTER && $reels['reel1'][$r] != 14){
                                        $this->findZokbos($reels, $reels['reel1'][$r], 1, [$r * 6]);
                                    }                        
                                }

                                /* 윈라인 응답빌드 */
                                $uniqueFirstSymbols = array_unique(
                                    array_map(function ($line) {return $line['FirstSymbol'];}, $this->winLines));

                                foreach ($uniqueFirstSymbols as $idx => $symbol) {
                                    $dupLines = array_filter($this->winLines, function($line) use ($symbol) {return $line['FirstSymbol'] === $symbol;});

                                    // 윈라인 심볼위치배열
                                    $symbols = array_unique(array_flatten(array_map(function($line) {return $line['Positions'];}, $dupLines)));
                                    $strSymbols = implode(',', $symbols);

                                    // 윈라인 당첨금
                                    $winLineMoney = $this->calculateLineMoney($slotSettings, $dupLines, $wildsCollection) * $betline;

                                    // 윈라인 응답
                                    $dupCount = count($dupLines);
                                    $firstLineKey = array_key_first($dupLines);

                                    // 총보상
                                    $subtotalWin += $winLineMoney;
                                }

                                /* 프리스핀 검사 */
                                $scattersCount = 0;
                                $_obf_scatterposes = [];
                                
                                $dogsCount = 0;
                                for($r = 0; $r < 6; $r++){
                                    for( $k = 0; $k < 7; $k++ ) 
                                    {
                                        if( $reels['reel' . ($r + 1)][$k] == $SCATTER ) 
                                        {                                
                                            $scattersCount++;
                                        }

                                        if ($slotSettings->IsKindOfDog($reels['reel' . ($r + 1)][$k])) {
                                            $dogsCount ++;
                                        }
                                    }
                                }
                                if ($dogsCount > 8) {
                                    continue;
                                }else if($scattersCount >= 3){
    
                                }else if($winType == 'win' && $subtotalWin > 0){
                                    break;
                                }else if($winType == 'none' && $subtotalWin == 0){
                                    if($kk >= 40){
                                        $winType = 'win';
                                    }
                                    break;
                                }
                            }
                            $totalWin = $totalWin + $subtotalWin / $lines;
                            $item = [];
                            $item['Reel'] = $reels;
                            $item['WildsCollection'] = $wildsCollection;
                            $item['NewWILDCollection'] = $newWILDCollection;
                            $item['OriginalReels'] = $originReels;
                            $lastWILDCollection = $wildsCollection;
                            array_push($totalReel, $item);
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
        public function findZokbos($reels, $firstSymbol, $repeatCount, $positions){
            $WILD = '2';
            $bPathEnded = true;
            if($repeatCount < 6){
                for($r = 0; $r < 7; $r++){
                    if($firstSymbol == $reels['reel'.($repeatCount + 1)][$r] || $reels['reel'.($repeatCount + 1)][$r] == $WILD){
                        $this->findZokbos($reels, $firstSymbol, $repeatCount + 1, array_merge($positions, [($repeatCount + $r * 6)]));
                        $bPathEnded = false;
                    }
                }
            }
            if($bPathEnded == true){
                if($repeatCount >= 3){
                    $winLine = [];
                    $winLine['FirstSymbol'] = $firstSymbol;
                    $winLine['RepeatCount'] = $repeatCount;
                    $winLine['Positions'] = $positions;
                    array_push($this->winLines, $winLine);
                }
            }
        }

        public function calculateLineMoney($slotSettings, $lines, $wildsCollection) {
            /* 윈라인 보상금 계산, WILD배당율 계산포함 */
            $linesMoney = array_map(function($line) use ($slotSettings, $wildsCollection) {
                $symbol = $line['FirstSymbol'];
                $repeatCount = $line['RepeatCount'];

                if (count($wildsCollection) > 0) {
                    /* 윈라인에 포함된 WILD 배당율 */
                    $multiplier = array_reduce($line['Positions'], function ($carry, $pos) use ($wildsCollection) {
                        $carry = isset($wildsCollection[$pos]) ? $carry * $wildsCollection[$pos] : $carry;
                        return $carry;
                    }, 1);
                }
                else {
                    /* WILD 가 없을때 */
                    $multiplier = 1;
                }

                return $slotSettings->Paytable[$symbol][$repeatCount] * $multiplier;
            }, $lines);

            return array_sum($linesMoney);
        }
        public function GetStickyWildSet($fsType){
            if($fsType == 1){
                $wildCountProbabilityMap =[
                    [0, 1, 1, 1],
                    [0, 1, 1, 2],
                    [0, 1, 1, 0, 1],
                    [0, 1, 1, 0, 2],
                    [0, 1, 1, 1, 1],
                    [0, 1, 2],
                    [0, 1, 2, 1],
                    [0, 2, 1],
                    [0, 2, 1, 0, 1],
                    [0, 2, 2],
    
                    // [0, 1, 2, 2]

                    // 높은빈도
                    [0, 1, 1, 0, 1],
                    [0, 1, 1, 0, 2],
                    [0, 1, 2],
                    [0, 2, 1],
                ];
    
                $wildSetId = array_rand($wildCountProbabilityMap);
                return $wildCountProbabilityMap[$wildSetId];
            }
            else{
                return [0, 1, 1, 1];
            }
        }
        public function generateStickyWILDs($reels, $wildSet, $lastWILDCollection = [], $fs, $fsmax) {
            $REELCOUNT = 6;
            $S_WILD = 2;

            /* 이전 WILD 심볼 복원 */
            foreach ($lastWILDCollection as $pos => $multiplier) {
                // 릴에 배치가능한 심볼갯수가 변하는 경우 WILD 심볼 위치 조정
                $reelPos = intdiv($pos, $REELCOUNT);
                $reelId = $pos % $REELCOUNT + 1;
                $reels["reel{$reelId}"][$reelPos] = 2;

                // 이미 배치된 WILD는 제외
                $wildSet[$reelId - 1] -= 1;
            }

            /* 한번에 생성할 WILD 심볼갯수 랜덤선택, 최대 2개 */
            if ($fs == 1) {
                $newWILDCount = random_int(0, 1);
            }
            /* 프리스핀 3회이내에 2,3번릴에 와일드심볼 생성 */
            else if ($fs == 3 && (array_search($S_WILD, $reels["reel2"]) === false || array_search($S_WILD, $reels["reel3"]) === false)) {
                $newWILDCount = array_search($S_WILD, $reels["reel2"]) === false ? 1 : 0;
                $newWILDCount += array_search($S_WILD, $reels["reel3"]) === false ? 1 : 0;
            }
            else {
                $newWILDCount = random_int(1, 2);
            }

            for ($i=0; $i < $newWILDCount; $i++) { 
                /* WILD심볼을 이미 갯수이상 배치했다면 */
                if (array_sum($wildSet) <= 0) {
                    break;
                }   

                /* WILD를 배치할 릴선택 */
                $availableReels = array_where($wildSet, function ($count, $key) {
                    return $count > 0;
                });

                if (count($availableReels) === 0) {
                    continue;
                }

                if ($fs >= 3) {
                    if (array_search($S_WILD, $reels["reel2"]) === false) {
                        $reelId = 2;
                    }
                    else if (array_search($S_WILD, $reels["reel3"]) === false) {
                        $reelId = 3;
                    }
                    else {
                        $reelId = array_rand($availableReels) + 1;
                    }
                }
                else {
                    $reelId = array_rand($availableReels) + 1;
                }


                /* WILD를 배치할 위치선택 */ 
                $availablePoses = array_where($reels["reel{$reelId}"], function ($symbol, $key) {
                    return $symbol != 14 && $symbol != 2 && $key != -1 && $key != 7;
                });

                if (count($availablePoses) === 0 ) {
                    continue;
                }

                $randomPos = array_rand($availablePoses);            

                $reels["reel{$reelId}"][$randomPos] = 2;

                $wildSet[$reelId - 1] -= 1;
            }
            
            return $reels;
        }
        public function generateWILDs($reels, $fsType, $lastWILDCollection = []) {
            if ($fsType == 'fsSticky') {
                /* Sticky 프리스핀일때 WILD 심볼갯수당 확률 */
                $wildsCountProbabilityMap = [
                    0 => 5,
                    1 => 5,
                    2 => 10,
                    3 => 45,
                    4 => 30,
                    5 => 4,
                    6 => 1
                ];

                /* 확룔에 기초한 WILD 심볼갯수 결정 */
                $wildCount = $this->getRandomValue($wildsCountProbabilityMap);

                /* 스티키 프리스핀 경우 이전 스핀결과의 WILD 심볼 복귀 */
                $lastWILDCount = count($lastWILDCollection);

                $REELCOUNT = 6;

                /* 이전 WILD 심볼 복원 */
                foreach ($lastWILDCollection as $pos => $multiplier) {
                    // 릴에 배치가능한 심볼갯수가 변하는 경우 WILD 심볼 위치 조정
                    $reelPos = intdiv($pos, $REELCOUNT);
                    $reelId = $pos % $REELCOUNT + 1;
                    $reels["reel{$reelId}"][$reelPos] = 2;
                }

                /* 이미 생성된 WILD 갯수가 더 많다면 더 생성하지 않는다 */
                if ($wildCount <= $lastWILDCount) {
                    return $reels;
                }

                /* 새로 생성해야 될 WILD 갯수 */
                $wildCount = $wildCount - $lastWILDCount;

                /* 한번에 생성되는 WILD 최대갯수 제한 */
                $wildCount = random_int(1, $wildCount) % 3;
            }
            else {
                /* 일반스핀, Raining 프리스핀일때 WILD 심볼갯수당 확률 */
                $wildsCountProbabilityMap = [
                    0 => 30,
                    1 => 30,
                    2 => 20,
                    3 => 10,
                    4 => 6,
                    5 => 3,
                    6 => 1
                ];

                /* 확룔에 기초한 WILD 심볼갯수 결정 */
                $wildCount = $this->getRandomValue($wildsCountProbabilityMap);
            }
            
            /* WILD 심볼 위치 결정 */
            for ($i=0; $i < $wildCount; $i++) { 
                /* 2번부터 5번사이 랜덤릴 */
                $randomReelId = random_int(2, 5);       
                
                /* 릴의 랜덤 위치 */
                $availablePoses = array_where($reels["reel{$randomReelId}"], function ($symbol, $key) {
                    return $symbol != 14 && $symbol != 2 && $key != -1 && $key != 7;
                });

                if (count($availablePoses) === 0 ) {
                    continue;
                }

                $randomPos = array_rand($availablePoses);            

                $reels["reel{$randomReelId}"][$randomPos] = 2;
            }

            return $reels;
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
    }

}

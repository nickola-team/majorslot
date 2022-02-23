<?php 
namespace VanguardLTE\Games\GatesofOlympusPM
{
    class GameReel
    {
        public $reelsStrip = [
            'reelStrip0_0' => [],
            'reelStrip0_1' => [],
            'reelStrip0_2' => [],
            'reelStrip0_3' => [],
            'reelStrip0_4' => [],
            'reelStrip0_5' => [],

            'reelStrip1_0' => [],
            'reelStrip1_1' => [],
            'reelStrip1_2' => [],
            'reelStrip1_3' => [],
            'reelStrip1_4' => [],
            'reelStrip1_5' => [],

            'reelStrip2_0' => [],
            'reelStrip2_1' => [],
            'reelStrip2_2' => [],
            'reelStrip2_3' => [],
            'reelStrip2_4' => [],
            'reelStrip2_5' => [],

            'reelStrip3_0' => [],
            'reelStrip3_1' => [],
            'reelStrip3_2' => [],
            'reelStrip3_3' => [],
            'reelStrip3_4' => [],
            'reelStrip3_5' => [],

            'reelStrip4_0' => [],
            'reelStrip4_1' => [],
            'reelStrip4_2' => [],
            'reelStrip4_3' => [],
            'reelStrip4_4' => [],
            'reelStrip4_5' => [],

            'reelStrip5_0' => [],
            'reelStrip5_1' => [],
            'reelStrip5_2' => [],
            'reelStrip5_3' => [],
            'reelStrip5_4' => [],
            'reelStrip5_5' => [],

            'reelStrip6_0' => [],
            'reelStrip6_1' => [],
            'reelStrip6_2' => [],
            'reelStrip6_3' => [],
            'reelStrip6_4' => [],
            'reelStrip6_5' => [],

            'reelStrip7_0' => [],
            'reelStrip7_1' => [],
            'reelStrip7_2' => [],
            'reelStrip7_3' => [],
            'reelStrip7_4' => [],
            'reelStrip7_5' => [],
        ];
        
        public function __construct()
        {
            $temp = file(base_path() . '/app/Games/GatesofOlympusPM/reels.txt');
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
            $S_SCATTER = 1;
            $S_MULTIPLIER = 12;
            $freeSpins = [15];
            $this->slotSettings = $slotSettings;
            $slotEvent = [];
            for($i = 0; $i < 1; $i++){
                $bfinish = false;
                while($bfinish == false){
                    $totalWin = 0;
                    $totalReel = [];
                    $freespinCount = $freeSpins[$i];
                    $freespinType = $freespinCount;
                    $slotEvent['slotEvent'] = 'freespin';
                    $fsMultiplier = 0;
                    for($j = 0; $j < $freespinCount; $j++){                        
                        $isTumb = false;
                        $tumbTotalWin = 0;
                        $lines = 20;
                        $bet = 1;
                        $lastReels = NULL;
                        $lastMULTIPLIERCollection = [];
                        $lastTumbleWin = 0;
                        while(true){
                            $_spinSettings = $slotSettings->GetSpinSettings('freespin', $bet * $lines, $lines, false);
                            $winType = $_spinSettings[0];
                            if($winType == 'bonus'){
                                $winType = 'win';
                            }
                            for($kk = 0; $kk < 50; $kk++){
                                $this->winLines = [];
                                $winMoney = 0;
                                $reels = $slotSettings->GetReelStrips($winType, $slotEvent['slotEvent'], $lastReels == NULL ? NULL : json_decode($lastReels), 0, $lastMULTIPLIERCollection);
                                /* 윈라인 체크 */
                                $this->checkWinLines($reels);

                                /* 스캐터심볼 체크 */
                                $scatterCount = $this->getScatterCount($reels);

                               /* 스핀 당첨금 */
                                $winMoney = array_reduce($this->winLines, function($carry, $winLine) {
                                    $carry += $winLine['Money']; 
                                    return $carry;
                                }, 0) * $bet;   
                                
                                /* 텀블당첨금이 한도이상일때 스킵 */
                                $multiplier = $this->getTumbleMultiplier($reels);
                                
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


                            $tumbleSymbols = [];
                            if ($winMoney > 0) {
                                /* 윈라인 구성 */
                                foreach ($this->winLines as $idx => $winLine) {
                                    /* 텀블심볼 조사 */
                                    $baseSymbol = $winLine['FirstSymbol'];
                                    $lineTumbles = array_map(function($pos) use ($baseSymbol) { return "${pos},${baseSymbol}"; }, $winLine['Positions']);
                                    $tumbleSymbols = array_merge($tumbleSymbols, $lineTumbles);
                                }
                            }
                            $multiplierSymbols = $reels['multiplierSymbols'];
                            if (count($multiplierSymbols) > 0) {
                                $lastMULTIPLIERCollection = [];
                                $strMultipliers = array_map(function($pos, $val) use ($S_MULTIPLIER) {
                                    return "${S_MULTIPLIER}~${pos}~${val}";
                                }, array_keys($multiplierSymbols), $multiplierSymbols);
                
                                foreach ($strMultipliers as $strMultiplier) {
                                    $details = explode("~", $strMultiplier);
                                    $lastMULTIPLIERCollection[$details[1]] = $details[2];
                                }
                            }
                            // $lastReels["s"] = implode(",", $reels['flattenSymbols']);
                            // $lastReels["tmb"] = implode("~", $tumbleSymbols);
                            // $lastReels["reel_set"] = $reels['id'];
                            // $lastReels["sa"] = implode(",", $reels['symbolsAfter']);
                            // $lastReels["sb"] = implode(",", $reels['symbolsBefore']);
                            $lastReels = [
                                's' => implode(",", $reels['flattenSymbols']),
                                'tmb' => implode("~", $tumbleSymbols),
                                'reel_set' => $reels['id'],
                                'sa' => implode(",", $reels['symbolsAfter']),
                                'sb' => implode(",", $reels['symbolsBefore']),
                            ];
                            $lastReels = json_encode($lastReels);
                            $item = [];
                            $item['Reel'] = $reels;
                            $item['FreeMultiplier'] = $fsMultiplier;
                            array_push($totalReel, $item);
                            if($winMoney > 0){
                                $lastTumbleWin = $lastTumbleWin + $winMoney / $lines;
                                $totalWin = $totalWin + $winMoney / $lines;
                                $isTumb = true;
                            }else{
                                if($isTumb == true && $multiplier > 1){
                                    $winMoney = $lastTumbleWin * ($multiplier + $fsMultiplier - 1);
                                    $totalWin = $totalWin + $winMoney;
                                    $fsMultiplier += $multiplier;
                                }
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
            $LINESYMBOLCOUNT = 30;
            
            /* 동의심볼 검출 */
            for ($baseSymbol=3; $baseSymbol <= 11; $baseSymbol++) { 
                $sameSymbols = array_filter($reels['flattenSymbols'], function ($symbol, $pos) use ($baseSymbol) {
                    return $symbol == $baseSymbol;
                }, ARRAY_FILTER_USE_BOTH);    

                /* 당첨조건이 8개이상 */
                $repeatCount = count($sameSymbols);
                if ($repeatCount >= 8) {
                    $linePositions = array_keys($sameSymbols);
                    $lineMoney =  $this->slotSettings->PayTable[$baseSymbol][$LINESYMBOLCOUNT - $repeatCount];
                    
                    array_push($this->winLines, [
                        'FirstSymbol' => $baseSymbol,
                        'RepeatCount' => $repeatCount,
                        'Positions' => $linePositions,
                        'Money' => $lineMoney,
                    ]);
                }
            }
        }

        public function getTumbleMultiplier($reels) {
            if (count($reels['multiplierSymbols']) > 0) {
                $multiplier = array_sum($reels['multiplierSymbols']);
                return $multiplier;
            }

            return 1;
        }

        public function getScatterCount($reels) {
            $S_SCATTER = 1;
            $REELCOUNT = 6;
            $scatterCount = 0;

            for ($reelId=0; $reelId < $REELCOUNT; $reelId++) { 
                $scatterCount += count(array_keys($reels['symbols'][$reelId], $S_SCATTER));
            }

            return $scatterCount;
        }

        public function stringfyScatterSymbols($reels, $bet) {
            $LINESYMBOLCOUNT = 30;
            $S_SCATTER = 1;

            $symbols = array_keys($reels['flattenSymbols'], $S_SCATTER);
            $repeatCount = count($symbols);
            $winMoney = $this->slotSettings->PayTable[$S_SCATTER][$LINESYMBOLCOUNT - $repeatCount] * $bet;

            // '1~300.00~3,7,20,22'
            return "${S_SCATTER}~${winMoney}~" . implode(",", $symbols);
        }
    }

}

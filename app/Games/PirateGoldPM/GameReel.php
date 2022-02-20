<?php 
namespace VanguardLTE\Games\PirateGoldPM
{
    class GameReel
    {
        public $reelsStrip = [
            'reelStrip0_0' => [], 
            'reelStrip0_1' => [], 
            'reelStrip0_2' => [], 
            'reelStrip0_3' => [], 
            'reelStrip0_4' => [], 

            'reelStrip1_0' => [], 
            'reelStrip1_1' => [], 
            'reelStrip1_2' => [], 
            'reelStrip1_3' => [], 
            'reelStrip1_4' => [], 
        ];
        public function __construct()
        {
            $temp = file(base_path() . '/app/Games/PirateGoldPM/reels.txt');
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
            $WILD = 2;
            $BONUS = 1;
            $MONEY = 13;
            $MoneyTable = $slotSettings->MoneyTable;
            $slotEvent = [];
            for($try = 0; $try < 1; $try++){
                $bfinish = false;
                while($bfinish == false){
                    $freespinType = $try;
                    $totalWin = 0;
                    $totalReel = [];
                    $freespinCount = 10;
                    $slotEvent['slotEvent'] = 'freespin';
                    for($subtry = 0; $subtry < $freespinCount; $subtry++){                        
                        $lines = 40;
                        $betline = 1;
                        $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $betline , $lines);
                        $winType = $_spinSettings[0];
                        if($winType == 'bonus'){
                            $winType = 'win';
                        }
                        for($kk = 0; $kk < 50; $kk++){
                            $winMoney = 0;
                            $luckyMoney = 0;
                            $bonusSymbols = [];
                            
                            /* 머니심볼 초기화 */
                            $moneySymbols = [];
                            $moneySymbolValues = array_fill(0, 20, 0);
                            $moneySymbolTypes = array_fill(0, 20, 'r');
                            
                            $reels = $slotSettings->GetReelStrips($winType, $slotEvent['slotEvent']);

                            /* 릴셋 1차원배열 생성 */
                            $flattenSymbols = [];
                            foreach ($reels['symbols'] as $reelId => $symbols) {
                                foreach ($symbols as $k => $symbol) {
                                    $flattenSymbols[$reelId + $k * 5] = $symbol;
                                }
                            }
                            ksort($flattenSymbols);

                            
                            /* 윈라인 검사 */
                            $winLines = $this->checkWinLines($flattenSymbols, $slotSettings->PayLines, $slotSettings->PayTable, $betline, $slotEvent['slotEvent']);
                            /* 스핀 당첨금 */
                            $winMoney = array_reduce($winLines, function($carry, $winLine) {
                                $carry += $winLine['Money']; 
                                return $carry;
                            }, 0);

                            /* 머니심볼, 보너스심볼 조사 */
                            $moneySymbols = array_filter($flattenSymbols, function ($value) use ($MONEY) { return $value == $MONEY; });
                            $bonusSymbols = array_filter($flattenSymbols, function ($value) use ($BONUS) { return $value == $BONUS; });

                            /* 머니심볼 머니배당 */
                            foreach ($moneySymbols as $pos => $symbol) {
                                $idx = array_rand($MoneyTable["standard"]);
                                $moneySymbolValues[$pos] = $MoneyTable["standard"][$idx];
                                $moneySymbolTypes[$pos] = "v";
                            }

                            if (count($bonusSymbols) == 3 || count($moneySymbols) >= 8) {
                                continue;
                            }else if($winType == 'win' && $winMoney > 0){
                                break;
                            }else if($winType == 'none' && $winMoney == 0){
                                if($kk >= 40){
                                    $winType = 'win';
                                }
                                break;
                            }
                        }
                        $totalWin = $totalWin + $winMoney / $lines;
                        $item = [];
                        $item['Reel'] = $reels;
                        $item['MoneySymbolValues'] = $moneySymbolValues;
                        array_push($totalReel, $item);
                    }
                    if ($totalWin > 30 &&  $totalWin < 150) {
                        $this->SaveReel($game_id, $freespinType, $totalReel, $totalWin, $freespinCount);
                    }
                    $count = \VanguardLTE\PPGameFreeStack::where([
                        'game_id' => $game_id, 
                        'free_spin_type' => $freespinType, 
                    ])->count();
                    if ($count > 2)
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
        public function checkWinLines($flattenSymbols, $PayLines, $PayTable, $bet, $spinType) {
            $WILD = 2;
            $fsWILD1 = 15;
            $fsWILD2 = 16;

            $REELCOUNT = 5;

            $winLines = [];

            foreach ($PayLines as $payLineId => $payLine) {
                $sameSymbolsCount = 0;

                /* 라인 검사 */
                foreach ($payLine as $idx => $pos) {
                    /* 첫심볼은 등록 */
                    if ($idx == 0) {
                        $firstSymbolPos = $payLine[0];
                        $firstSymbol = $flattenSymbols[$firstSymbolPos];
        
                        /* 머니심볼이면 break */
                        if ($firstSymbol == 13) {
                            break;
                        }

                        $sameSymbolsCount = 1;
                        continue;
                    }

                    /* 같은 심볼, WILD인 경우 혹 프리스핀에서 15,16번 ( WILD로 본다 )심볼인 경우 */
                    if ($flattenSymbols[$pos] == $firstSymbol || $flattenSymbols[$pos] == $WILD) {
                        $sameSymbolsCount += 1;
                    }
                    else if ($spinType == 'freespin' && ($flattenSymbols[$pos] == $fsWILD1 || $flattenSymbols[$pos] == $fsWILD2)) {
                        $sameSymbolsCount += 1;
                    }
                    else {
                        break;
                    }
                }

                /* 같은 심볼갯수가 1개이면 스킵 */
                if ($sameSymbolsCount <= 1) {
                    continue;
                }

                /* 페이테이블 검사 */
                $lineMoney = $PayTable[$firstSymbol][$REELCOUNT - $sameSymbolsCount];

                if ($lineMoney > 0) {
                    array_push($winLines, [
                        'FirstSymbol' => $firstSymbol,
                        'RepeatCount' => $sameSymbolsCount,
                        'PayLineId' => $payLineId,
                        'Money' => $lineMoney * $bet,
                        'Positions' => array_slice($payLine, 0, $sameSymbolsCount)
                    ]);
                }
            }
            
            return $winLines;
        }
    }

}

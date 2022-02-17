<?php 
namespace VanguardLTE\Games\PandaFortune2PM
{
    class GameReel
    {
        public $reelsStrip = [
            'reelStrip1' => [], 
            'reelStrip2' => [], 
            'reelStrip3' => [], 
            'reelStrip4' => [], 
            'reelStrip5' => [], 
            'reelStrip6' => []
        ];
        public $reelsStripBonus = [
            'reelStripBonus1' => [], 
            'reelStripBonus2' => [], 
            'reelStripBonus3' => [], 
            'reelStripBonus4' => [], 
            'reelStripBonus5' => [], 
            'reelStripBonus6' => []
        ];
        public function __construct()
        {
            $temp = file(base_path() . '/app/Games/PandaFortune2PM/reels.txt');
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
                if( isset($this->reelsStripBonus[$str[0]]) ) 
                {
                    $data = explode(',', $str[1]);
                    foreach( $data as $elem ) 
                    {
                        $elem = trim($elem);
                        if( $elem != '' ) 
                        {
                            $this->reelsStripBonus[$str[0]][] = $elem;
                        }
                    }
                }
            }
        }
        public function generationFreeStacks($slotSettings, $game_id){   
            $wild = '2';
            $linesId = $slotSettings->winLines;
            $freeSpins = [12];
            $repeatCounts = [8000, 1000, 1000]; // normal, minor, major
            for($ii = 0; $ii < 3; $ii++){
                $bfinish = false;
                while($bfinish == false){
                    for($i = 0; $i < 1; $i++){
                        $freespinType = $ii;
                        $totalWin = 0;
                        $totalReel = [];
                        $freespinCount = $freeSpins[$i];
                        $totalGoldenPoses = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                        $jackpotReelNumbers = [];
                        if($freespinType > 0){
                            if(mt_rand(0, 100) <= 100){
                                $jackpotCount = 1;
                            }else{
                                $jackpotCount = 2;
                            }
                            $jackpotReelNumbers = $slotSettings->GetRandomNumber(0, $freespinCount - 2, $jackpotCount);
                        }
                        for($k = 0; $k < $freespinCount; $k++){                        
                            $lines = 25;
                            $betline = 1;
                            $_spinSettings = $slotSettings->GetSpinSettings('freespin', $betline * $lines, $lines);
                            $winType = $_spinSettings[0];
                            if($winType == 'bonus'){
                                $winType = 'win';
                            }
                            $isGeneratJackpot = false;
                            if($freespinType > 0){
                                for($kk = 0; $kk < count($jackpotReelNumbers); $kk++){
                                    if($k == $jackpotReelNumbers[$kk]){
                                        $isGeneratJackpot = true;
                                        $winType = 'win';
                                    }
                                }
                            }
                            $isJackpot = false;
                            $jackpotPosCount = 0;
                            $jackpotLine = -1;
                            if($winType == 'win' && $isGeneratJackpot == true){                    
                                $isJackpot = true;
                                for( $m = 0; $m < $lines; $m++ ) 
                                {
                                    $lineId = $linesId[$m];
                                    $goldenCount = 0;
                                    for($n = 0; $n < 5; $n++){
                                        $pos = ($lineId[$n] - 1) * 5 + $n;
                                        if($totalGoldenPoses[$pos] == 1){
                                            $goldenCount++;
                                        }
                                    }
                                    if($jackpotPosCount == 0 || ($goldenCount > 0 && $goldenCount < $jackpotPosCount)){
                                        $jackpotPosCount = $goldenCount;
                                        $jackpotLine = $m;
                                    }
                                }
                            }
                            for($kk = 0; $kk < 10; $kk++){
                                $lineWins = [];
                                $lineWinNum = [];
                                $wild = 2;
                                $scatter = 1;
                                $scattersCount = 0;
                                $jackpotWin = 0;
                                $goldenWin = 0;
                                $goldenWins = [];
                                $goldenPoses = $totalGoldenPoses;
                                if($isJackpot == true){
                                    if($jackpotLine == -1){
                                        $jackpotLine = mt_rand(0, count($lines)-1);
                                    }
                                    $lastReels = $slotSettings->GenerateJackpotReel($jackpotLine);
                                    if($jackpotPosCount == 0){
                                        $goldenReelPos = mt_rand(0, 4);
                                        $goldenPoses[($linesId[$jackpotLine][$goldenReelPos] - 1) * 5 + $goldenReelPos] = 1;
                                    }
                                }else{                                
                                    $lastReels = $slotSettings->GetReelStrips($winType, 'freespin', $betline, 0);
                                    for($r = 0; $r < 15; $r++){
                                        if($goldenPoses[$r] == 0 && $slotSettings->CheckGoldenSymbol() && $lastReels['reel' . ($r % 5 + 1)][floor($r / 5)] != $scatter){
                                            $goldenPoses[$r] = 1;
                                        }
                                    }
                                }
                                $subtotalWin = 0;    
                                for( $m = 0; $m < $lines; $m++ ) 
                                {
                                    $firstEle = $lastReels['reel1'][$linesId[$m][0] - 1];
                                    $lineWinNum[$m] = 1;
                                    $lineWins[$m] = 0;
                                    $isWild = false;
                                    $isJackpotPay = false;
                                    for($n = 1; $n < 5; $n++){
                                        $ele = $lastReels['reel'. ($n + 1)][$linesId[$m][$n] - 1];
                                        if($firstEle == $wild){
                                            $firstEle = $ele;
                                            $lineWinNum[$m] = $lineWinNum[$m] + 1;
                                            $isWild = true;
                                        }else if($ele == $firstEle || $ele == $wild){
                                            if($ele == $wild){
                                                $isWild = true;
                                            }
                                            $lineWinNum[$m] = $lineWinNum[$m] + 1;
                                            if($n == 4){
                                                if($firstEle > 2){
                                                    $lineWins[$m] = $slotSettings->Paytable[$firstEle][$lineWinNum[$m]] * $betline;
                                                    if($lineWins[$m] > 0){
                                                        $subtotalWin += $lineWins[$m];
                                                        for($r = 0; $r < $lineWinNum[$m]; $r++){
                                                            $pos = ($linesId[$m][$r] - 1) * 5 + $r;
                                                            $symbol = $lastReels['reel'. ($r + 1)][$linesId[$m][$r] - 1];
                                                            if($goldenPoses[$pos] == 1){
                                                                if($isJackpot == true && $isJackpotPay == false){
                                                                    array_push($goldenWins, [$symbol, $slotSettings->GetGoldenMul($symbol, $lineWinNum[$m], $isJackpot, $freespinType)]);
                                                                    $isJackpotPay = true;
                                                                }else{
                                                                    array_push($goldenWins, [$symbol, $slotSettings->GetGoldenMul($symbol, $lineWinNum[$m])]);
                                                                }
                                                            }
                                                        }
                                                    }
                                                }else{
                                                    $lineWinNum[$m] = 0;
                                                }
                                            }
                                        }else{
                                            if($slotSettings->Paytable[$firstEle][$lineWinNum[$m]] > 0){
                                                if($firstEle > 2){
                                                    $lineWins[$m] = $slotSettings->Paytable[$firstEle][$lineWinNum[$m]] * $betline;
                                                    if($lineWins[$m] > 0){
                                                        $subtotalWin += $lineWins[$m];
                                                        for($r = 0; $r < $lineWinNum[$m]; $r++){                                                
                                                            $pos = ($linesId[$m][$r] - 1) * 5 + $r;
                                                            $symbol = $lastReels['reel'. ($r + 1)][$linesId[$m][$r] - 1];
                                                            if($goldenPoses[$pos] == 1){
                                                                array_push($goldenWins, [$symbol, $slotSettings->GetGoldenMul($symbol, $lineWinNum[$m])]);
                                                            }
                                                        }   
                                                    }
                                                }else{
                                                    $lineWinNum[$k] = 0;
                                                }
                                            }else{
                                                $lineWinNum[$m] = 0;
                                            }
                                            break;
                                        }
                                    }
                                }
    
                                for( $r = 1; $r <= 5; $r++ ) 
                                {
                                    for( $m = 0; $m <= 2; $m++ ) 
                                    {
                                        if( $lastReels['reel' . $r][$m] == $scatter ) 
                                        {
                                            $scattersCount++;
                                            if($goldenPoses[$m * 5 + $r - 1] == 1){
                                                continue;
                                            }
                                        }
                                    }
                                }
    
                                for($r = 0; $r < count($goldenWins); $r++){
                                    $goldenWin = $goldenWin + $betline * $lines * $goldenWins[$r][1];
                                }
                                $subtotalWin += $goldenWin;
                                if($scattersCount >= 3){
    
                                }else if($winType == 'win' && $subtotalWin > 0){
                                    break;
                                }else if($winType == 'none' && $subtotalWin == 0){
                                    if($kk >= 8){
                                        $winType = 'win';
                                    }
                                    break;
                                }
                            }
                            
                            $totalGoldenPoses = $goldenPoses;
                            $totalWin = $totalWin + $subtotalWin / $lines;
                            $item = [];
                            $item['Reel'] = $lastReels;
                            $item['GoldenPoses'] = $totalGoldenPoses;
                            $item['GoldenWins'] = $goldenWins;
                            array_push($totalReel, $item);
                        }
                        if ($freespinType > 0 || ($totalWin > 30 &&  $totalWin < 150)) {
                            $this->SaveReel($game_id, $freespinType, $totalReel, $totalWin, $freespinCount);
                        }
                        $count = \VanguardLTE\PPGameFreeStack::where([
                            'game_id' => $game_id, 
                            'free_spin_type' => $freespinType, 
                        ])->count();
                        if ($count > $repeatCounts[$ii])
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
    }

}

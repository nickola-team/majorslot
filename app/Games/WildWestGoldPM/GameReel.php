<?php 
namespace VanguardLTE\Games\WildWestGoldPM
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
            $temp = file(base_path() . '/app/Games/WildWestGoldPM/reels.txt');
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
            $linesId = [];
            $linesId[0] = [1,1,1,1,1];
            $linesId[1] = [2,2,2,2,2];
            $linesId[2] = [3,3,3,3,3];
            $linesId[3] = [4,4,4,4,4];
            $linesId[4] = [1,2,1,2,1];
            $linesId[5] = [2,1,2,1,2];
            $linesId[6] = [2,3,2,3,2];
            $linesId[7] = [3,2,3,2,3];
            $linesId[8] = [3,4,3,4,3];
            $linesId[9] = [4,3,4,3,4];
            $linesId[10] = [1,4,1,4,1];
            $linesId[11] = [4,1,4,1,4];
            $linesId[12] = [1,2,3,2,1];
            $linesId[13] = [2,3,4,3,2];
            $linesId[14] = [3,2,1,2,3];
            $linesId[15] = [4,3,2,3,4];
            $linesId[16] = [1,1,2,1,1];
            $linesId[17] = [2,2,3,2,2];
            $linesId[18] = [3,3,4,3,3];
            $linesId[19] = [4,4,1,4,4];
            $linesId[20] = [4,4,3,4,4];
            $linesId[21] = [3,3,2,3,3];
            $linesId[22] = [2,2,1,2,2];
            $linesId[23] = [1,1,4,1,1];
            $linesId[24] = [1,2,2,2,1];
            $linesId[25] = [2,3,3,3,2];
            $linesId[26] = [3,4,4,4,3];
            $linesId[27] = [4,1,1,1,4];
            $linesId[28] = [4,3,3,3,4];
            $linesId[29] = [3,2,2,2,3];
            $linesId[30] = [2,1,1,1,2];
            $linesId[31] = [1,4,4,4,1];
            $linesId[32] = [2,4,2,4,2];
            $linesId[33] = [1,3,1,3,1];
            $linesId[34] = [3,1,3,1,3];
            $linesId[35] = [4,2,4,2,4];
            $linesId[36] = [4,3,2,1,2];
            $linesId[37] = [1,2,3,4,3];
            $linesId[38] = [4,1,2,1,4];
            $linesId[39] = [1,4,3,4,1];

            $wild = '2';
            $bfinish = false;
            while ($bfinish==false)
            {
                $freespinType = 1;
                $totalWin = 0;
                $totalReel = [];
                $wildValue = [];
                $wildPos = [];
                $freespinCount = 8;
                $lastFreeSpins = [8, 12, 16];
                $lastFreeSpin = $lastFreeSpins[mt_rand(0, 2)];
                for($k = 0; $k < $freespinCount; $k++){                        
                    $lines = 40;
                    $betline = 1;
                    $_spinSettings = $slotSettings->GetSpinSettings('freespin', $betline * $lines, $lines);
                    $winType = $_spinSettings[0];
                    $isScatter = false;
                    if(mt_rand(0, 100) < 10 && $k < $freespinCount - 1){
                        $isScatter = true;
                    }
                    for($kk = 0; $kk < 50; $kk++){
                        $subWildReelValue = $slotSettings->CheckMultiWild();
                        $reels = $slotSettings->GetReelStrips($winType, 'freespin');
                        $tempReels = [];
                        $tempWildReels = [];
                        $lineWinNum = [];
                        $lineWins = [];
                        $subtotalWin = 0;
                        if (count($wildPos) > 3)
                        {
                            $subWildReelValue = [2,2,2];
                        }
                        for($r = 0; $r < 5; $r++){
                            $tempWildReels[$r] = [];
                            $tempReels['reel' . ($r+1)] = [];
                            for( $m = 0; $m < 4; $m++ ) 
                            {
                                if( $reels['reel' . ($r+1)][$m] == $wild) 
                                {                                
                                    if(($r == 2 && rand(0, 100) < 70) || $winType == 'none'){
                                        $reels['reel' . ($r+1)][$m] = '' . rand(3, 10);
                                        $tempWildReels[$r][$m] = 0;    
                                    }else{
                                        if($r > 0 && $r < 4){
                                            $tempWildReels[$r][$m] = $subWildReelValue[$r - 1];
                                        }else{
                                            $tempWildReels[$r][$m] = 0;    
                                        }
                                    }
                                }else{
                                    $tempWildReels[$r][$m] = 0;
                                }
                                $tempReels['reel' . ($r+1)][$m] = $reels['reel' . ($r+1)][$m];
                            }
                        }
                        $isReelWild = [0,0,0,0,0];
                        for($r = 0; $r < count($wildPos); $r++){
                            $col = $wildPos[$r] % 5;
                            $row = floor($wildPos[$r] / 5);
                            $reels['reel'.($col + 1)][$row] = $wild;
                            $tempWildReels[$col][$row] = $wildValue[$r];
                            $isReelWild[$col] = 1;
                        }
                        if($isReelWild[1] == 1 && $isReelWild[2] == 1 && $winType = 'none'){
                            $winType = 'win';
                            $_winAvaliableMoney = $slotSettings->GetBank('bonus');
                        }
                        
                        for( $m = 0; $m < $lines; $m++ ) 
                        {
                            $firstEle = $reels['reel1'][$linesId[$m][0] - 1];
                            $lineWinNum[$m] = 1;
                            $lineWins[$m] = 0;
                            $mul = 0;
                            for($n = 1; $n < 5; $n++){
                                $ele = $reels['reel'. ($n + 1)][$linesId[$m][$n] - 1];
                                if($firstEle == $wild){
                                    $firstEle = $ele;
                                    $lineWinNum[$m] = $lineWinNum[$m] + 1;
                                    $mul = $mul + $tempWildReels[$n][$linesId[$m][$n] - 1];
                                }else if($ele == $firstEle || $ele == $wild){
                                    if($ele == $wild){
                                        $mul = $mul + $tempWildReels[$n][$linesId[$m][$n] - 1];
                                    }
                                    $lineWinNum[$m] = $lineWinNum[$m] + 1;
                                    if($n == 4){
                                        if($mul == 0){$mul = 1;}
                                        $lineWins[$m] = $slotSettings->Paytable[$firstEle][$lineWinNum[$m]] * $betline / $lines * $mul;
                                        if($lineWins[$m] > 0){
                                            $subtotalWin += $lineWins[$m];
                                        }
                                    }
                                }else{
                                    if($slotSettings->Paytable[$firstEle][$lineWinNum[$m]] > 0){
                                        if($mul == 0){$mul = 1;}
                                        $lineWins[$m] = $slotSettings->Paytable[$firstEle][$lineWinNum[$m]] * $betline / $lines * $mul;
                                        if($lineWins[$m] > 0){
                                            $subtotalWin += $lineWins[$m];
                                        }
                                    }else{
                                        $lineWinNum[$m] = 0;
                                    }
                                    break;
                                }
                            }
                        }

                        $freeSpinScatters = $slotSettings->GetFreeScatters($isScatter, $wildPos);
                        $subfreespinCount = 0;
                        for($r = 0; $r < count($freeSpinScatters); $r++){
                            if($freeSpinScatters[$r] >= 0){
                                $subfreespinCount++;
                            }
                        }
                        $subfreeSpins = [0,0,4,8,12,20];
                        $freeSpinNum = $subfreeSpins[$subfreespinCount];
                        if($freespinCount >= $lastFreeSpin && $freeSpinNum > 0){
                            $isScatter = false;
                        }else if($winType == 'win' && $subtotalWin > 0){
                            break;
                        }else if($winType == 'none' && $subtotalWin == 0){
                            if($kk >= 30){
                                $winType = 'win';
                                $isScatter = false;
                            }
                            break;
                        }
                    }

                    $wildValue = [];
                    $wildPos = [];
                    for($l = 0; $l < 4; $l++){
                        for($m = 0; $m < 5; $m++){
                            if($tempWildReels[$m][$l] > 0){
                                array_push($wildValue, $tempWildReels[$m][$l]);
                                array_push($wildPos, $l * 5 + $m);
                            }
                        }
                    }
                    if($freeSpinNum > 0){
                        $freespinCount += $freeSpinNum;
                    }
                    $totalWin = $totalWin + $subtotalWin * 2;
                    $item = [];
                    $item['TempReels'] = $tempReels;
                    $item['Reel'] = $reels;
                    $item['TempWildReels'] = $tempWildReels;
                    $item['FreeSpinScatters'] = $freeSpinScatters;
                    $item['WildReelValue'] = $subWildReelValue;
                    $item['WildValue'] = $wildValue;
                    $item['WildPos'] = $wildPos;
                    array_push($totalReel, $item);
                }
                if ($totalWin > 30 &&  $totalWin < 150) {
                    $this->SaveReel($game_id, $freespinType, $totalReel, $totalWin, $freespinCount);
                }
                $count = \VanguardLTE\PPGameFreeStack::where('game_id', $game_id)->count();
                if ($count > 10000)
                {
                    $bfinish = true;
                    break;
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

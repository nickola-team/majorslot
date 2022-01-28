<?php 
namespace VanguardLTE\Games\TheHandofMidasPM
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
            'reelStrip9_6' => []
        ];
        public function __construct()
        {
            $temp = file(base_path() . '/app/Games/TheHandofMidasPM/reels.txt');
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
        public function generationFreeStacks($slotSettings, $game_id){   
            $linesId = [];
            $linesId[0] = [2,2,2,2,2];
            $linesId[1] = [1,1,1,1,1];
            $linesId[2] = [3,3,3,3,3];
            $linesId[3] = [1,2,3,2,1];
            $linesId[4] = [3,2,1,2,3];
            $linesId[5] = [2,1,1,1,2];
            $linesId[6] = [2,3,3,3,2];
            $linesId[7] = [1,1,2,3,3];
            $linesId[8] = [3,3,2,1,1];
            $linesId[9] = [2,3,2,1,2];
            $linesId[10] = [2,1,2,3,2];
            $linesId[11] = [1,2,2,2,1];
            $linesId[12] = [3,2,2,2,3];
            $linesId[13] = [2,2,1,2,2];
            $linesId[14] = [2,2,3,2,2];
            $linesId[15] = [1,3,1,3,1];
            $linesId[16] = [3,1,3,1,3];
            $linesId[17] = [1,1,3,1,1];
            $linesId[18] = [3,3,1,3,3];
            $linesId[19] = [1,3,3,3,1];

            for($i = 9; $i <= 45; $i++){

                $wild = '2';
                $scatter = '1';
                $bfinish = false;
                $freespinCount = $i;
                $freespinType = $i;
                while ($bfinish==false)
                {
                    $totalWin = 0;
                    $totalReel = [];
                    $wildValue = [];
                    $wildPos = [];
                    for($k = 0; $k < $freespinCount; $k++){                        
                        $lines = 20;
                        $betline = 1;
                        $_spinSettings = $slotSettings->GetSpinSettings('freespin', $betline * $lines, $lines, 0);
                        $winType = $_spinSettings[0];
                        if($winType == 'bonus'){
                            $winType = 'win';
                        }
                        for($kk = 0; $kk < 50; $kk++){
                            $subWildReelValue = $slotSettings->CheckMultiWild();
                            $reels = $slotSettings->GetReelStrips($winType, 'freespin', 1, -1);
                            $tempReels = [];
                            $tempWildReels = [];
                            $lineWinNum = [];
                            $lineWins = [];
                            $subtotalWin = 0;
                            for($r = 0; $r < 5; $r++){
                                $tempWildReels[$r] = [];
                                $tempReels['reel' . ($r+1)] = [];
                                for( $m = 0; $m < 3; $m++ ) 
                                {
                                    if( $reels['reel' . ($r+1)][$m] == $wild) 
                                    {                                
                                        if(($r == 2 && rand(0, 100) < 70) || $winType == 'none'){
                                            $reels['reel' . ($r+1)][$m] = '' . rand(6, 13);
                                            $tempWildReels[$r][$m] = 0;    
                                        }else{
                                            if($r > 0 && $r < 4){
                                                $tempWildReels[$r][$m] = $slotSettings->CheckMultiWild();
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
                            for($r = 0; $r < count($wildPos); $r++){
                                $col = $wildPos[$r] % 5;
                                $row = floor($wildPos[$r] / 5);
                                $reels['reel'.($col + 1)][$row] = $wild;
                                $tempWildReels[$col][$row] = $wildValue[$r];
                            }
                            $totalWildValue = 0;
                            for($r = 0; $r < 5; $r++){
                                for($m = 0; $m < 3; $m++){
                                    $totalWildValue = $totalWildValue + $tempWildReels[$r][$m];
                                }
                            }
                            for( $m = 0; $m < $lines; $m++ ) 
                            {
                                $firstEle = $reels['reel1'][$linesId[$m][0] - 1];
                                $lineWinNum[$m] = 1;
                                $lineWins[$m] = 0;
                                $mul = $totalWildValue + 1;
                                for($n = 1; $n < 5; $n++){
                                    $ele = $reels['reel'. ($n + 1)][$linesId[$m][$n] - 1];
                                    if($firstEle == $wild){
                                        $firstEle = $ele;
                                        $lineWinNum[$m] = $lineWinNum[$m] + 1;
                                    }else if($ele == $firstEle || $ele == $wild){
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
                            $scattersCount = 0;
                            for( $r = 1; $r <= 5; $r++ ) 
                            {
                                for( $m = 0; $m < 3; $m++ ) 
                                {
                                    if( $reels['reel' . $r][$m] == $scatter ) 
                                    {
                                        $scattersCount++;
                                    }
                                }
                            }

                            if($scattersCount > 2){
                                
                            }else if($winType == 'win' && $subtotalWin > 0){
                                break;
                            }else if($winType == 'none' && $subtotalWin == 0){
                                if($kk >= 10){
                                    $winType = 'win';
                                    $isScatter = false;
                                }
                                break;
                            }
                        }
    
                        $wildValue = [];
                        $wildPos = [];
                        for($l = 0; $l < 3; $l++){
                            for($m = 0; $m < 5; $m++){
                                if($tempWildReels[$m][$l] > 0){
                                    array_push($wildValue, $tempWildReels[$m][$l]);
                                    array_push($wildPos, $l * 5 + $m);
                                }
                            }
                        }
                        
                        $totalWin = $totalWin + $subtotalWin;
                        $item = [];
                        $item['TempReels'] = $tempReels;
                        $item['Reel'] = $reels;
                        $item['TempWildReels'] = $tempWildReels;
                        array_push($totalReel, $item);
                    }
                    if ($totalWin > 30 &&  $totalWin < 150) {
                        $this->SaveReel($game_id, $freespinType, $totalReel, $totalWin, $freespinCount);
                    }
                    $count = \VanguardLTE\PPGameFreeStack::where([
                        'game_id' => $game_id, 
                        'free_spin_type' => $freespinType
                    ])->count();
                    if ($count > 5000)
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
    }

}

<?php 
namespace VanguardLTE\Games\ReleasetheKrakenPM
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
            $temp = file(base_path() . '/app/Games/ReleasetheKrakenPM/reels.txt');
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
            $linesId[1] = [4,4,4,4,4];
            $linesId[2] = [2,2,2,2,2];
            $linesId[3] = [3,3,3,3,3];
            $linesId[4] = [1,2,3,2,1];
            $linesId[5] = [4,3,2,3,4];
            $linesId[6] = [3,2,1,2,3];
            $linesId[7] = [2,3,4,3,2];
            $linesId[8] = [1,2,1,2,1];
            $linesId[9] = [4,3,4,3,4];
            $linesId[10] = [2,1,2,1,2];
            $linesId[11] = [3,4,3,4,3];
            $linesId[12] = [2,3,2,3,2];
            $linesId[13] = [3,2,3,2,3];
            $linesId[14] = [1,2,2,2,1];
            $linesId[15] = [4,3,3,3,4];
            $linesId[16] = [2,1,1,1,2];
            $linesId[17] = [3,4,4,4,3];
            $linesId[18] = [2,3,3,3,2];
            $linesId[19] = [3,2,2,2,3];
            $linesId[20] = [1,1,2,1,1];
            $linesId[21] = [4,4,3,4,4];
            $linesId[22] = [2,2,1,2,2];
            $linesId[23] = [3,3,4,3,3];
            $linesId[24] = [2,2,3,2,2];
            $linesId[25] = [3,3,2,3,3];
            $linesId[26] = [1,1,3,1,1];
            $linesId[27] = [4,4,2,4,4];
            $linesId[28] = [3,3,1,3,3];
            $linesId[29] = [2,2,4,2,2];
            $linesId[30] = [1,3,3,3,1];
            $linesId[31] = [4,2,2,2,4];
            $linesId[32] = [3,1,1,1,3];
            $linesId[33] = [2,4,4,4,2];
            $linesId[34] = [2,1,3,1,2];
            $linesId[35] = [3,4,2,4,3];
            $linesId[36] = [2,3,1,3,2];
            $linesId[37] = [3,2,4,2,3];
            $linesId[38] = [1,3,1,3,1];
            $linesId[39] = [4,2,4,2,4];

            $wild = '2';
            for($i = 0; $i < 3; $i++)
            {
                $freeSpins = [7, 8, 9];
                $bfinish = false;
                $freespinType = $i;
                while ($bfinish==false){
                    $totalWin = 0;
                    $totalReel = [];
                    $freespinCount = $freeSpins[$freespinType];
                    $totalMul = 1;
                    for($k = 0; $k < $freespinCount; $k++){                        
                        $lines = 40;
                        $betline = 1;
                        $_spinSettings = $slotSettings->GetSpinSettings('freespin', $betline * $lines, $lines);
                        $winType = $_spinSettings[0];
                        if($winType == 'bonus'){
                            $winType = 'win';
                        }
                        $isGeneration = false;
                        if($k == 0 && rand(0, 100) < 50){
                            $isGeneration = true;
                        }else if($k == 1 && $totalMul <= 1){
                            $isGeneration = true;
                        }
                        for($kk = 0; $kk < 50; $kk++){
                            $tempReels = $slotSettings->GetReelStrips($winType, 'freespin', false);
                            if(rand(0, 100) > 90 || $isGeneration == true){
                                $pos = rand(0, 19);  
                                $tempReels['reel' . ($pos % 5 + 1)][floor($pos / 5)] = 2;
                            }
                            $lineWinNum = [];
                            $lineWins = [];
                            $reels = [];
                            $subtotalWin = 0;
                            for( $r = 1; $r <= 5; $r++ ) 
                            {
                                $reels['reel' . $r] = [];
                                for( $m = 0; $m < 4; $m++ ) 
                                {
                                    $reels['reel' . $r][$m] = $tempReels['reel' . $r][$m];
                                }
                            }
                            if($totalMul > 1){
                                for($m = 0; $m < $totalMul - 1; $m++){
                                    while(true){
                                        $pos = rand(0, 19);
                                        if($reels['reel' . ($pos % 5 + 1)][floor($pos / 5)] != 2){
                                            $reels['reel' . ($pos % 5 + 1)][floor($pos / 5)] = 2;
                                            break;
                                        }
                                    }
                                }
                            }
                            $wildPosCount = 0;
                            for( $r = 1; $r <= 5; $r++ ) 
                            {
                                for( $m = 0; $m < 4; $m++ ) 
                                {
                                    if( $reels['reel' . $r][$m] == $wild) 
                                    {
                                        $wildPosCount++;
                                    }
                                }
                            }
                            $mul = $wildPosCount + 1;
                            for( $m = 0; $m < $lines; $m++ ) 
                            {
                                $firstEle = $reels['reel1'][$linesId[$m][0] - 1];
                                $lineWinNum[$m] = 1;
                                $lineWins[$m] = 0;
                                for($n = 1; $n < 5; $n++){
                                    $ele = $reels['reel'. ($n + 1)][$linesId[$m][$n] - 1];
                                    if($firstEle == $wild){
                                        $firstEle = $ele;
                                        $lineWinNum[$m] = $lineWinNum[$m] + 1;
                                    }else if($ele == $firstEle || $ele == $wild){
                                        $lineWinNum[$m] = $lineWinNum[$m] + 1;
                                        if($n == 4){
                                            $lineWins[$m] = $slotSettings->Paytable[$firstEle][$lineWinNum[$m]] * $betline  * $mul;
                                            if($lineWins[$m] > 0){
                                                $subtotalWin += $lineWins[$m];
                                            }
                                        }
                                    }else{
                                        if($slotSettings->Paytable[$firstEle][$lineWinNum[$m]] > 0){
                                            $lineWins[$m] = $slotSettings->Paytable[$firstEle][$lineWinNum[$m]] * $betline  * $mul;
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
                            if($winType == 'win' && $subtotalWin > 0){
                                break;
                            }else if($winType == 'none' && $subtotalWin == 0){
                                if($kk >= 30){
                                    $winType = 'win';
                                }
                                break;
                            }
                        }
                        $totalMul = $mul;
                        $totalWin = $totalWin + $subtotalWin / 20;
                        $item = [];
                        $item['TempReels'] = $tempReels;
                        $item['Reel'] = $reels;
                        array_push($totalReel, $item);
                    }
                    if ($totalWin > 30 &&  $totalWin < 150) {
                        $this->SaveReel($game_id, $freespinType, $totalReel, $totalWin, $freespinCount);
                    }
                    $count = \VanguardLTE\PPGameFreeStack::where([
                        'game_id' => $game_id, 
                        'free_spin_type' => $freespinType
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
    }

}

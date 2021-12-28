<?php 
namespace VanguardLTE\Games\WildBoosterPM
{
    class GameReel
    {
        public $reelsStrip = [
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
            'reelStrip10_6' => []
        ];
        public function __construct()
        {
            $temp = file(base_path() . '/app/Games/WildBoosterPM/reels.txt');
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
        public $linesId = [];
        public function generationFreeStacks($slotSettings, $game_id){   
            $this->linesId[0] = [2,2,2,2,2];
            $this->linesId[1] = [1,1,1,1,1];
            $this->linesId[2] = [3,3,3,3,3];
            $this->linesId[3] = [1,2,3,2,1];
            $this->linesId[4] = [3,2,1,2,3];
            $this->linesId[5] = [2,1,1,1,2];
            $this->linesId[6] = [2,3,3,3,2];
            $this->linesId[7] = [1,1,2,3,3];
            $this->linesId[8] = [3,3,2,1,1];
            $this->linesId[9] = [2,3,2,1,2];           
            $this->linesId[10] = [2,1,2,3,2];
            $this->linesId[11] = [1,2,2,2,1];
            $this->linesId[12] = [3,2,2,2,3];
            $this->linesId[13] = [1,2,1,2,1];
            $this->linesId[14] = [3,2,3,2,3];
            $this->linesId[15] = [2,2,1,2,2];
            $this->linesId[16] = [2,2,3,2,2]; 
            $this->linesId[17] = [1,1,3,1,1];
            $this->linesId[18] = [3,3,1,3,3];
            $this->linesId[19] = [1,3,3,3,1];
            $slotSettings->SetGameData($slotSettings->slotId . 'scatterUpDownSameMaskCounts', [0,0,0,0,0,0,0,0,0,0]);
            $wild = '2';
            $scatter = '1';
            $scatterCounts = [3, 4, 5, 3, 4, 5];
            for($i = 0; $i < 6; $i++){
                for($j = 0; $j < 3; $j++){
                    $freespinType = floor($i / 3);
                    $wildMuls = $slotSettings->GetWildMul($freespinType);
                    $totalScatters = $scatterCounts[$i];
                    $totalWin = 0;
                    $totalReel = [];
                    $freespinCount = 5;
                    for($k = 0; $k < $freespinCount; $k++){                        
                        $lines = 20;
                        $betline = 1;
                        $bonusMpl = $wildMuls[floor($k / 5)];
                        $_spinSettings = $slotSettings->GetSpinSettings('freespin', $betline * $lines, $lines);
                        $winType = $_spinSettings[0];
                        if($winType == 'bonus'){
                            $winType = 'win';
                        }
                        if($freespinType == 0){
                            $currentReelSet = 6;
                        }else{
                            $currentReelSet = 5;
                        }
                        for($kk = 0; $kk < 50; $kk++){
                            $reels = $slotSettings->GetReelStrips($winType, 'freespin', $currentReelSet, 0);
                            $lineWinNum = [];
                            $lineWins = [];
                            $subtotalWin = 0;
                            for( $m = 0; $m < $lines; $m++ ) 
                            {
                                $firstEle = $reels['reel1'][$this->linesId[$m][0] - 1];
                                $lineWinNum[$m] = 1;
                                $lineWins[$m] = 0;
                                $isWild = false;
                                $scattersCount = 0;
                                for($n = 1; $n < 5; $n++){
                                    $ele = $reels['reel' . ($n + 1)][$this->linesId[$m][$n] - 1];
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
                                            $lineWins[$m] = $slotSettings->Paytable[$firstEle][$lineWinNum[$m]] * $betline / $lines;
                                            if($isWild == true){
                                                $lineWins[$m] = $lineWins[$m] * $bonusMpl;
                                            }
                                            if($lineWins[$m] > 0){
                                                $subtotalWin += $lineWins[$m];
                                            }
                                        }
                                    }else{
                                        if($slotSettings->Paytable[$firstEle][$lineWinNum[$m]] > 0){
                                            $lineWins[$m] = $slotSettings->Paytable[$firstEle][$lineWinNum[$m]] * $betline / $lines;
                                            if($isWild == true){
                                                $lineWins[$m] = $lineWins[$m] * $bonusMpl;
                                            }
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
                            for( $r = 1; $r <= 5; $r++ ) 
                            {
                                for( $m = 0; $m <= 2; $m++ ) 
                                {
                                    if( $reels['reel' . $r][$m] == $scatter ) 
                                    {
                                        $scattersCount++;
                                    }
                                }
                            }

                            $freeSpinNums = 0;                    
                            $currentWildStep = floor(($totalScatters + $scattersCount) / 5);
                            if($k + 1 == $currentWildStep * 5 ){
                                $freeSpinNums = 5;
                            }

                            if($scattersCount >= 3 || ($totalScatters + $scattersCount) > 15){

                            }else if($winType == 'win' && $subtotalWin > 0){
                                break;
                            }else if($winType == 'none' && $subtotalWin == 0){
                                if($kk >= 8){
                                    $winType = 'win';
                                }
                                break;
                            }
                        }

                        $totalScatters = $totalScatters + $scattersCount;
                        $freespinCount = $freespinCount + $freeSpinNums;
                        $totalWin = $totalWin + $subtotalWin;
                        $item = [];
                        $item['Reel'] = $reels;
                        $item['BonusMpl'] = $bonusMpl;
                        array_push($totalReel, $item);
                    }
                    $this->SaveReel($game_id, $i, $totalReel, $totalWin, $freespinCount);
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

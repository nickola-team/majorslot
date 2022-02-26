<?php 
namespace VanguardLTE\Games\ReturnoftheDeadPM
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
            $temp = file(base_path() . '/app/Games/ReturnoftheDeadPM/reels.txt');
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
            $this->linesId[3] = [2, 1, 1, 1, 2];
            $this->linesId[4] = [2, 3, 3, 3, 2];
            $this->linesId[5] = [3, 2, 1, 2, 3];
            $this->linesId[6] = [1, 2, 3, 2, 1];
            $this->linesId[7] = [3, 3, 2, 1, 1];
            $this->linesId[8] = [1, 1, 2, 3, 3];
            $this->linesId[9] = [3, 2, 2, 2, 1];

            $scatter = '1';
            $wild = '1';
            $bonusSymbols = [3, 4, 5, 6, 7, 8, 9, 10, 11];
            for($i = 0; $i < 9; $i++)
            {
                $bfinish = false;
                while ($bfinish==false){
                    $bonusSymbol = $bonusSymbols[$i];
                    $freespinType = $bonusSymbol;
                    $totalWin = 0;
                    $totalReel = [];
                    $wildReelValue = [];
                    $wildReelPos= [];
                    $freespinCount = 10;
                    for($sub_k = 0; $sub_k < $freespinCount; $sub_k++){                        
                        $lines = 10;
                        $betline = 1;
                        $_spinSettings = $slotSettings->GetSpinSettings('freespin', $betline * $lines, $lines);
                        $winType = $_spinSettings[0];
                        if($winType == 'bonus'){
                            $winType = 'win';
                        }
                        for($kk = 0; $kk < 50; $kk++){
                            $reels = $slotSettings->GetReelStrips($winType, 'freespin', $bonusSymbol - 2, 0);   
                            $lineWinNum = [];
                            $lineWins = [];
                            $subtotalWin = 0;
                            for( $k = 0; $k < $lines; $k++ ) 
                            {
                                $_lineWin = '';
                                $firstEle = $reels['reel1'][$this->linesId[$k][0] - 1];
                                $lineWinNum[$k] = 1;
                                $lineWins[$k] = 0;
                                $isWild = false;
                                for($j = 1; $j < 5; $j++){
                                    $ele = $reels['reel'. ($j + 1)][$this->linesId[$k][$j] - 1];
                                    if($firstEle == $wild){
                                        $firstEle = $ele;
                                        $lineWinNum[$k] = $lineWinNum[$k] + 1;
                                    }else if($ele == $firstEle || $ele == $wild){
                                        $lineWinNum[$k] = $lineWinNum[$k] + 1;
                                        if($j == 4){
                                            $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                            if($lineWins[$k] > 0){
                                                $subtotalWin += $lineWins[$k];
                                            }
                                        }
                                    }else{
                                        if($slotSettings->Paytable[$firstEle][$lineWinNum[$k]] > 0){
                                            $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                            if($lineWins[$k] > 0){
                                                $subtotalWin += $lineWins[$k];
                                            }
                                        }else{
                                            $lineWinNum[$k] = 0;
                                        }
                                        break;
                                    }
                                }
                            }
                            $me_bonusCount = 0;
                            $me_bonusWin = 0;
                            $scattersCount = 0;
                            for( $r = 1; $r <= 5; $r++ ) 
                            {
                                for( $k = 0; $k <= 2; $k++ ) 
                                {
                                    if( $reels['reel' . $r][$k] == $scatter ) 
                                    {
                                        $scattersCount++;
                                    }
                                    if( $reels['reel' . $r][$k] == $bonusSymbol ) 
                                    {
                                        $me_bonusCount++;
                                    }
                                }
                            }
                            $me_bonusWin = $slotSettings->Paytable[$bonusSymbol][$me_bonusCount] * $betline * 10;
                            if($me_bonusWin > 0){
                                $isExtend = true;
                                $subtotalWin = $subtotalWin + $me_bonusWin;
                            }
                            if($scattersCount >= 2){

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

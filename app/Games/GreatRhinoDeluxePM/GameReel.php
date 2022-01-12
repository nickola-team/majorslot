<?php 
namespace VanguardLTE\Games\GreatRhinoDeluxePM
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
            $temp = file(base_path() . '/app/Games/GreatRhinoDeluxePM/reels.txt');
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
        public $linesId = [];
        public function generationFreeStacks($slotSettings, $game_id){  
            $this->linesId[0] = [2, 2, 2, 2, 2];
            $this->linesId[1] = [1, 1, 1, 1, 1];
            $this->linesId[2] = [3, 3, 3, 3, 3];
            $this->linesId[3] = [1, 2, 3, 2, 1];
            $this->linesId[4] = [3, 2, 1, 2, 3];
            $this->linesId[5] = [2, 1, 1, 1, 2];
            $this->linesId[6] = [2, 3, 3, 3, 2];
            $this->linesId[7] = [1, 1, 2, 3, 3];
            $this->linesId[8] = [3, 3, 2, 1, 1];
            $this->linesId[9] = [2, 3, 2, 1, 2];
            $this->linesId[10] = [2, 1, 2, 3, 2];
            $this->linesId[11] = [1, 2, 2, 2, 1];
            $this->linesId[12] = [3, 2, 2, 2, 3];
            $this->linesId[13] = [1, 2, 1, 2, 1];
            $this->linesId[14] = [3, 2, 3, 2, 3];
            $this->linesId[15] = [2, 2, 1, 2, 2];
            $this->linesId[16] = [2, 2, 3, 2, 2];
            $this->linesId[17] = [1, 1, 3, 1, 1];
            $this->linesId[18] = [3, 3, 1, 3, 3];
            $this->linesId[19] = [1, 3, 3, 3, 1];
            $linesId = $this->linesId;
            $wild = '2';
            $scatter = '1';
            $scatterCounts = [3];
            for($i = 0; $i < 1; $i++){
                $bfinish = false;
                while($bfinish == false){
                    $freespinType = $i;
                    $totalWin = 0;
                    $totalReel = [];
                    $freespinCount = 10;
                    $respinSymbolPoses = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                    for($j = 0; $j < $freespinCount; $j++){                        
                        $lines = 20;
                        $betline = 1;
                        $_spinSettings = $slotSettings->GetSpinSettings('freespin', $betline * $lines, $lines);
                        $winType = $_spinSettings[0];
                        if($winType == 'bonus'){
                            $winType = 'win';
                        }
                        for($kk = 0; $kk < 50; $kk++){
                            $reels = $slotSettings->GetReelStrips($winType, 'freespin', 0);
                            $lineWinNum = [];
                            $lineWins = [];
                            $subtotalWin = 0;
                            $subRespinSymbolPoses = $respinSymbolPoses;
                            for( $k = 0; $k < $lines; $k++ ) 
                            {
                                $_lineWin = '';
                                $firstEle = $reels['reel1'][$linesId[$k][0] - 1];
                                $lineWinNum[$k] = 1;
                                $lineWins[$k] = 0;
                                for($m = 1; $m < 5; $m++){
                                    $ele = $reels['reel'. ($m + 1)][$linesId[$k][$m] - 1];
                                    if($firstEle == $wild){
                                        $firstEle = $ele;
                                        $lineWinNum[$k] = $lineWinNum[$k] + 1;
                                    }else if($ele == $firstEle || $ele == $wild){
                                        $lineWinNum[$k] = $lineWinNum[$k] + 1;
                                        if($m == 4){
                                            $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline / $lines;
                                            if($lineWins[$k] > 0){
                                                $subtotalWin += $lineWins[$k];
                                            }
                                        }
                                    }else{
                                        if($slotSettings->Paytable[$firstEle][$lineWinNum[$k]] > 0){
                                            $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline / $lines;
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
                            $respinCount = 0;
                            $_obf_respinposes = [];
                            $scattersCount = 0;
                            for( $r = 1; $r <= 5; $r++ ) 
                            {
                                for( $k = 0; $k <= 2; $k++ ) 
                                {
                                    if( $reels['reel' . $r][$k] == $scatter ) 
                                    {
                                        $scattersCount++;
                                    }
                                    if( $reels['reel' . $r][$k] == 3 ) {
                                        $subRespinSymbolPoses[$k * 5 + $r - 1] = 1;
                                    }
                                }
                                if($reels['reel' . $r][0] == '3' && $reels['reel' . $r][1] == '3' && $reels['reel' . $r][2] == '3'){
                                    $respinCount++;
                                }
                            }
                            $acc_count = 0;
                            for($k = 0; $k < count($subRespinSymbolPoses); $k++){
                                if($subRespinSymbolPoses[$k] > 0){
                                    $acc_count++;
                                }
                            }


                            if($respinCount >= 2 || $scattersCount >= 3){

                            }else if($acc_count > 10){

                            }else if($winType == 'win' && $subtotalWin > 0){
                                break;
                            }else if($winType == 'none' && $subtotalWin == 0){
                                if($kk >= 40){
                                    $winType = 'win';
                                }
                                break;
                            }
                        }
                        $totalWin = $totalWin + $subtotalWin;
                        $item = [];
                        $item['Reel'] = $reels;
                        $respinSymbolPoses = $subRespinSymbolPoses;
                        array_push($totalReel, $item);
                    }
                    if($acc_count > 0){
                        $respinReel = $this->generateRespinReel($slotSettings, $respinSymbolPoses);
                        $totalWin = $totalWin + $respinReel[0] / $lines;
                        $item = [];
                        $item['Reel'] = $respinReel[1];
                        array_push($totalReel, $item);
                    }
                    if ($totalWin > 30 &&  $totalWin < 150) {
                        $this->SaveReel($game_id, $i, $totalReel, $totalWin, $freespinCount);
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
        public function generateRespinReel($slotSettings, $respinSymbolPoses){
            $linesId = $this->linesId;                     
            $lines = 20;
            $betline = 1;
            for($i = 0; $i < 100; $i++){
                $acc_count = 0;
                $totalWin = 0;
                $rhinoCount = 0;
                $reels=[[12,12,12],[12,12,12],[12,12,12],[12,12,12],[12,12,12]];
                $isMake = false;
                $mainX = -1;
                $mainY = 0;
                $lineWinNum = [];
                $lineWins = [];
                if($i > 90){
                    $isMake = true;
                    $mainX = 0;
                    $mainY = mt_rand(0, 2);
                }
                for($k = 0; $k < count($respinSymbolPoses); $k++){
                    if($respinSymbolPoses[$k] != 0){
                        $rhinoCount++;
                        $acc_count++;
                        if($isMake == true && $mainX < 3){
                            if($reels[$mainX][$mainY] == 12){
                                $reels[$mainX][$mainY] = 3;
                                $mainX++;
                            }
                        }else{
                            while(true){
                                $posX = rand(0, 4);
                                $posY = rand(0, 2);
                                if($reels[$posX][$posY] == 12){
                                    $reels[$posX][$posY] = 3;
                                    break;
                                }
                            }
                        }
                    }
                }
                if($rhinoCount == 14){
                    $totalWin = $lines * $betline * 375;
                }else if ($rhinoCount == 15){
                    $totalWin = $lines * $betline * 500;
                }else{
                    $_obf_winCount = 0;
                    for( $k = 0; $k < $lines; $k++ ) 
                    {
                        $_lineWin = '';
                        $firstEle = $reels[0][$linesId[$k][0] - 1];
                        $lineWinNum[$k] = 1;
                        $lineWins[$k] = 0;
                        for($j = 1; $j < 5; $j++){
                            $ele = $reels[$j][$linesId[$k][$j] - 1];
                            if($ele == $firstEle){
                                $lineWinNum[$k] = $lineWinNum[$k] + 1;
                                if($j == 4){
                                    $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                    if($lineWins[$k] > 0){
                                        $totalWin += $lineWins[$k];
                                    }
                                }
                            }else{
                                if($slotSettings->Paytable[$firstEle][$lineWinNum[$k]] > 0){
                                    $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                    if($lineWins[$k] > 0){
                                        $totalWin += $lineWins[$k];
                                    }
                                }else{
                                    $lineWinNum[$k] = 0;
                                }
                                break;
                            }
                        }
                    }
                }
                $winLineCount = 0;
                for($k = 0; $k < 5; $k++){
                    if($reels[$k][0] == 3 || $reels[$k][1] == 3 || $reels[$k][2] == 3){
                        $winLineCount++;
                    }else{
                        break;
                    }
                }
                if($totalWin > 0){
                    break;
                }
            }
            return[$totalWin, $reels];
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

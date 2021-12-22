<?php 
namespace VanguardLTE\Games\TheTweetyHousePM
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
            $temp = file(base_path() . '/app/Games/TheTweetyHousePM/reels.txt');
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
        public $Paytable = [];
        public function generationFreeStacks($slotSettings, $game_id){   
            $this->Paytable[1] = [0,0,0,0,0,0];
            $this->Paytable[2] = [0,0,0,0,0,0];
            $this->Paytable[3] = [0,0,0,50,150,750];
            $this->Paytable[4] = [0,0,0,35,100,500];
            $this->Paytable[5] = [0,0,0,25,60,300];
            $this->Paytable[6] = [0,0,0,20,40,200];
            $this->Paytable[7] = [0,0,0,12,25,150];
            $this->Paytable[8] = [0,0,0,8,20,100];
            $this->Paytable[9] = [0,0,0,5,10,50];
            $this->Paytable[10] = [0,0,0,5,10,50];
            $this->Paytable[11] = [0,0,0,2,5,25];
            $this->Paytable[12] = [0,0,0,2,5,25];
            $this->Paytable[13] = [0,0,0,2,5,25];
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

            $wild = '2';
            $freeSpins = [9, 10, 11, 12, 13, 14, 15, 16, 17];
            $bfinish = false;
            while ($bfinish==false)
            {
                for($i = 0; $i < 9; $i++){
                    $freespinType = 1;
                    $totalWin = 0;
                    $totalReel = [];
                    $wildReelValue = [];
                    $wildReelPos= [];
                    $freespinCount = $freeSpins[$i];
                    for($k = 0; $k < $freespinCount; $k++){                        
                        $lines = 20;
                        $betline = 1;
                        $_spinSettings = $slotSettings->GetSpinSettings('freespin', $betline * $lines, $lines);
                        $winType = $_spinSettings[0];
                        for($kk = 0; $kk < 10; $kk++){
                            $subWildReelValue = $slotSettings->CheckMultiWild();
                            $initReels = $slotSettings->GetReelStrips($winType, 'freespin');
                            $tempReels = [];
                            $reels = [];
                            $tempWildReels = [];
                            $lineWinNum = [];
                            $lineWins = [];
                            $subtotalWin = 0;
                            for($l = 0; $l < 5; $l++){       
                                $reels[$l] = [];
                                $tempWildReels[$l] = [];
                                $tempReels[$l] = [];
                                for($m = 0; $m < 3; $m++){
                                    if( $initReels['reel' . ($l+1)][$m] == $wild) 
                                    {                                
                                        if(($l == 2 && rand(0, 100) < 70) || $winType == 'none'){
                                            while(true){
                                                $newSymbol = rand(4, 10);
                                                if($initReels['reel' . ($l+1)][0] != $newSymbol && $initReels['reel' . ($l+1)][1] != $newSymbol && $initReels['reel' . ($l+1)][2] != $newSymbol){
                                                        $initReels['reel' . ($l+1)][$m] = $newSymbol;
                                                        break;
                                                }
                                            }
                                            $tempWildReels[$l][$m] = 0;    
                                        }else{
                                            if($l > 0 && $l < 4){
                                                $tempWildReels[$l][$m] = $subWildReelValue[$l - 1];
                                            }else{
                                                $tempWildReels[$l][$m] = 0;    
                                            }
                                        }
                                    }else{
                                        $tempWildReels[$l][$m] = 0;
                                    }
                                    $tempReels[$l][$m] = $initReels['reel' . ($l+1)][$m];
                                    $reels[$l][$m] = $initReels['reel' . ($l+1)][$m];
                                }
                            }
    
                            for($l = 0; $l < count($wildReelPos); $l++){
                                $col = $wildReelPos[$l] % 5;
                                $row = floor($wildReelPos[$l] / 5);
                                $reels[$col][$row] = $wild;
                                $tempWildReels[$col][$row] = $wildReelValue[$l];
                            }
                            for( $m = 0; $m < $lines; $m++ ) 
                            {
                                $firstEle = $reels[0][$this->linesId[$m][0] - 1];
                                $lineWinNum[$m] = 1;
                                $lineWins[$m] = 0;
                                $mul = 0;
                                for($n = 1; $n < 5; $n++){
                                    $ele = $reels[$n][$this->linesId[$m][$n] - 1];
                                    if($firstEle == $wild){
                                        $firstEle = $ele;
                                        $lineWinNum[$m] = $lineWinNum[$m] + 1;
                                        $mul = $mul + $tempWildReels[$n][$this->linesId[$m][$n] - 1];
                                    }else if($ele == $firstEle || $ele == $wild){
                                        if($ele == $wild){
                                            $mul = $mul + $tempWildReels[$n][$this->linesId[$m][$n] - 1];
                                        }
                                        $lineWinNum[$m] = $lineWinNum[$m] + 1;
                                        if($n == 4){
                                            if($mul == 0){$mul = 1;}
                                            $lineWins[$m] = $this->Paytable[$firstEle][$lineWinNum[$m]] * $betline / $lines * $mul;
                                            if($lineWins[$m] > 0){
                                                $subtotalWin += $lineWins[$m];
                                            }
                                        }
                                    }else{
                                        if($this->Paytable[$firstEle][$lineWinNum[$m]] > 0){
                                            if($mul == 0){$mul = 1;}
                                            $lineWins[$m] = $this->Paytable[$firstEle][$lineWinNum[$m]] * $betline / $lines * $mul;
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
                                if($kk >= 8){
                                    $winType = 'win';
                                }
                                break;
                            }
                        }

                        $wildReelValue = [];
                        $wildReelPos = [];
                        for($l = 0; $l < 3; $l++){
                            for($m = 0; $m < 5; $m++){
                                if($tempWildReels[$m][$l] > 0){
                                    array_push($wildReelValue, $tempWildReels[$m][$l]);
                                    array_push($wildReelPos, $l * 5 + $m);
                                }
                            }
                        }
                        $totalWin = $totalWin + $subtotalWin;
                        $item = [];
                        $item['initReel'] = $tempReels;
                        $item['Reel'] = $reels;
                        $item['SubWilds'] = $subWildReelValue;
                        $item['WildValue'] = $wildReelValue;
                        $item['WildPos'] = $wildReelPos;
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

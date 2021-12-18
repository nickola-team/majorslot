<?php 
namespace VanguardLTE\Games\PandasFortunePM
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
            $temp = file(base_path() . '/app/Games/PandasFortunePM/reels.txt');
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
            $freeSpins = [8, 10, 15];
            $repeatCounts = [8000, 1000, 1000]; // normal, major, grand
            for($ii = 0; $ii < 3; $ii++){// normal, major, grand
                $bfinish = false;
                while($bfinish == false){
                    for($i = 0; $i < 3; $i++){
                        $freespinType = $ii;
                        $totalWin = 0;
                        $totalReel = [];
                        $freespinCount = $freeSpins[$i];
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
                                    }
                                }
                            }
                            for($kk = 0; $kk < 10; $kk++){
                                $lineWins = [];
                                $lineWinNum = [];
                                $wild = 2;
                                $scatter = 1;
                                $lastReels = [];
                                $scattersCount = 0;
                                $isJackpots = [];
                                $jackpotWinLines = [];
                                $jackpotWin = 0;
                                $_lineWinNumber = 1;
                                $bonusSymbol = rand(2, 13);
                                $lastReels = [];
                                if($freespinType > 0 && $isGeneratJackpot == true){
                                    $initReels = $slotSettings->GenerateJackpotReel($freespinType==2);
                                }else{
                                    $initReels = $slotSettings->GetReelStrips($winType, 'freespin', $betline);
                                }
                                for($r = 0; $r < 5; $r++){
                                    $lastReels['reel' . ($r+1)] = [];
                                    for( $m = 0; $m < 3; $m++ ) 
                                    {
                                        if( $initReels['reel' . ($r+1)][$m] == 14) 
                                        {                                
                                            $lastReels['reel' . ($r+1)][$m] = $bonusSymbol;
                                        }else{
                                            $lastReels['reel' . ($r+1)][$m] = $initReels['reel' . ($r+1)][$m];
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
                                                    $jackpotLine = [];
                                                    $jackpotLine['isWild'] = $isWild;
                                                    $jackpotLine['pos'] = $linesId[$m][$n] - 1;
                                                    array_push($jackpotWinLines, $jackpotLine);
                                                    $lineWins[$m] = $slotSettings->Paytable[$firstEle][$lineWinNum[$m]] * $betline;
                                                    if($lineWins[$m] > 0){
                                                        $subtotalWin += $lineWins[$m];
                                                    }
                                                }else{
                                                    $lineWinNum[$k] = 0;
                                                }
                                            }
                                        }else{
                                            if($slotSettings->Paytable[$firstEle][$lineWinNum[$m]] > 0){
                                                if($firstEle > 2){
                                                    $lineWins[$m] = $slotSettings->Paytable[$firstEle][$lineWinNum[$m]] * $betline;
                                                    if($lineWins[$m] > 0){
                                                        $subtotalWin += $lineWins[$m];
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
                                        }
                                    }
                                }
    
                                for($m = 0; $m < 3; $m++){
                                    if($initReels['reel5'][$m] == $scatter){
                                        $isJackpots[$m] = false;
                                    }else{
                                        if($isGeneratJackpot == true){
                                            $isJackpots[$m] = true;
                                        }else{
                                            $isJackpots[$m] = $slotSettings->CheckJackpotSymbol();
                                        }
                                    }
                                }
                                
                                for($m = 0; $m < count($jackpotWinLines); $m++){
                                    $pos = $jackpotWinLines[$m]['pos'];
                                    if($isJackpots[$pos] == true){
                                        if($jackpotWinLines[$m]['isWild'] == true){
                                            $jackpotWin = $jackpotWin + $betline * $lines * $slotSettings->jackpotMulti[0];
                                        }else{
                                            if($lastReels['reel5'][$pos] >= 3 && $lastReels['reel5'][$pos] <= 7 ){
                                                $jackpotWin = $jackpotWin + $betline * $lines * $slotSettings->jackpotMulti[2];
                                            }else if($lastReels['reel5'][$pos] > 7){
                                                $jackpotWin = $jackpotWin + $betline * $lines * $slotSettings->jackpotMulti[1];
                                            }
                                        }
                                    }
                                }
                                $subtotalWin += $jackpotWin;
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
    
                            $totalWin = $totalWin + $subtotalWin / $lines;
                            $item = [];
                            $item['initReel'] = $initReels;
                            $item['Reel'] = $lastReels;
                            $item['IsJackpots'] = $isJackpots;
                            $item['BonusSymbol'] = $bonusSymbol;
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

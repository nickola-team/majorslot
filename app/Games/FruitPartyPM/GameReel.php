<?php 
namespace VanguardLTE\Games\FruitPartyPM
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
            'reelStrip1_7' => [],
            'reelStrip2_1' => [], 
            'reelStrip2_2' => [], 
            'reelStrip2_3' => [], 
            'reelStrip2_4' => [], 
            'reelStrip2_5' => [], 
            'reelStrip2_6' => [],
            'reelStrip2_7' => [],
            'reelStrip3_1' => [], 
            'reelStrip3_2' => [], 
            'reelStrip3_3' => [], 
            'reelStrip3_4' => [], 
            'reelStrip3_5' => [], 
            'reelStrip3_6' => [],
            'reelStrip3_7' => [],
            'reelStrip4_1' => [], 
            'reelStrip4_2' => [], 
            'reelStrip4_3' => [], 
            'reelStrip4_4' => [], 
            'reelStrip4_5' => [], 
            'reelStrip4_6' => [],
            'reelStrip4_7' => [],
            'reelStrip5_1' => [], 
            'reelStrip5_2' => [], 
            'reelStrip5_3' => [], 
            'reelStrip5_4' => [], 
            'reelStrip5_5' => [], 
            'reelStrip5_6' => [],
            'reelStrip5_7' => []
        ];
        public function __construct()
        {
            $temp = file(base_path() . '/app/Games/FruitPartyPM/reels.txt');
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
        public $strCheckSymbol = '';
        public $repeatCount = 0;
        public $strWinLinePos = '';
        public $binaryReel = [];
        public $bonusMulReel = [];
        public function generationFreeStacks($slotSettings, $game_id){   
            $wild = '2';
            $scatter = '1';
            $scatterCounts = [3, 4, 5];
            for($i = 0; $i < 3; $i++){
                $bfinish = false;
                while($bfinish == false){
                    $freespinType = $i;
                    $totalWin = 0;
                    $totalReel = [];
                    $freespinCount = $slotSettings->freespinCounts[$scatterCounts[$i]];
                    for($j = 0; $j < $freespinCount; $j++){                        
                        $isTumb = false;
                        $lines = 20;
                        $betline = 1;
                        $lastReel = [];
                        $this->binaryReel = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                        $this->bonusMulReel = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                        while(true){
                            if($isTumb == true){
                                $reelsAndPoses = $slotSettings->GetLastReel($lastReel, $this->binaryReel, $this->bonusMulReel);
                                $lastReel = $reelsAndPoses[0];
                            }
                            $_spinSettings = $slotSettings->GetSpinSettings('freespin', $betline * $lines, $lines);
                            $winType = $_spinSettings[0];
                            if($winType == 'bonus'){
                                $winType = 'win';
                            }
                            $currentReelSet = 1;
                            if($winType == 'none'){
                                $currentReelSet = 3;
                            }
                            $isBonusMul = false;
                            if($winType == 'win'){
                                $isBonusMul = $slotSettings->IsBonusMul('freespin');
                            }
                            for($kk = 0; $kk < 50; $kk++){
                                $this->winLines = [];
                                $this->strCheckSymbol = '';
                                $this->repeatCount = 0;
                                $this->strWinLinePos = '';
                                if($isTumb == true && $lastReel != null){
                                    $reels = $slotSettings->GetTumbReelStrips($lastReel, $currentReelSet);
                                }else{
                                    $reels = $slotSettings->GetReelStrips($winType, 'freespin', $currentReelSet, 0);
                                }
                                
                                $subtotalWin = 0;
                                $scattersCount = 0;
                                for($k = 0; $k < 7; $k++){
                                    for( $l = 0; $l < 7; $l++ ) 
                                    {
                                        if($reels['reel' . ($l + 1)][$k] == $scatter){
                                            $scattersCount++;
                                        }else if(strpos($this->strCheckSymbol, $l . '-' . $k) == false){
                                            $this->repeatCount = 1;
                                            $this->strWinLinePos = ($k * 7 + $l);
                                            $this->strCheckSymbol = $this->strCheckSymbol . ';' . $l . '-' . $k;
                                            $this->findZokbos($reels, $l, $k, $reels['reel' . ($l + 1)][$k]);

                                            if($this->repeatCount >= 5){
                                                $winLine = [];
                                                $winLine['FirstSymbol'] = $reels['reel' . ($l + 1)][$k];
                                                $winLine['RepeatCount'] = $this->repeatCount;
                                                $winLine['StrLineWin'] = $this->strWinLinePos;
                                                array_push($this->winLines, $winLine);
                                            }
                                        }
                                    }   
                                }
                                $this->binaryReel = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                                $this->bonusMulReel = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                                $bonusMulCount = 0;
    
                                $isNewTumb = false;
                                for($r = 0; $r < count($this->winLines); $r++){
                                    $winLine = $this->winLines[$r];
                                    $arr_symbols = explode('~', $winLine['StrLineWin']);
                                    $line_mul = 1;
                                    for($k = 0; $k < count($arr_symbols); $k++){
                                        $this->binaryReel[$arr_symbols[$k]] = $reels['reel' . ($arr_symbols[$k] % 7 + 1)][floor($arr_symbols[$k] / 7)];
                                        if($isBonusMul == true && $this->bonusMulReel[$arr_symbols[$k]] == 0 && mt_rand(0, 100) < 20){
                                            if(mt_rand(0, 100) < 97){
                                                $this->bonusMulReel[$arr_symbols[$k]] = 2;
                                            }else{
                                                $this->bonusMulReel[$arr_symbols[$k]] = 4;
                                            }
                                            $bonusMulCount++;
                                            $line_mul = $line_mul * $this->bonusMulReel[$arr_symbols[$k]];
                                        }
                                    }
                                    $winLineMoney = $slotSettings->Paytable[$winLine['FirstSymbol']][$winLine['RepeatCount']] * $betline ;
                                    if($line_mul > 1 && $winLineMoney > 0){
                                        $winLineMoney = $winLineMoney * $line_mul;
                                    }
                                    if($winLineMoney > 0){                            
                                        $isNewTumb = true;
                                        $subtotalWin += $winLineMoney;
                                    }
                                }   
    
                                if($scattersCount >= 3){
    
                                }else if($scattersCount == 2 && mt_rand(0, 100) < 70){
    
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
                            $item['BonusMulReel'] = $this->bonusMulReel;
                            $item['IsBonusMul'] = $isBonusMul;
                            array_push($totalReel, $item);
                            if($subtotalWin > 0){
                                $isTumb = true;
                                for($k = 0; $k < 7; $k++){
                                    for($l = 1; $l <= 7; $l++){
                                        $lastReel[($l - 1) + $k * 7] = $reels['reel'.$l][$k];
                                    }
                                }
                            }else{
                                break;
                            }
                        }
                    }
                    if ($totalWin > 30 &&  $totalWin < 150) {
                        $this->SaveReel($game_id, $i, $totalReel, $totalWin, $freespinCount);
                    }
                    $count = \VanguardLTE\PPGameFreeStack::where([
                        'game_id' => $game_id, 
                        'free_spin_type' => $i, 
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
        public function findZokbos($reels, $i, $j, $firstSymbol){
            $scatter = '2';
            $bPathEnded = true;
            if($i < 6 && $firstSymbol == $reels['reel' . ($i + 2)][$j] && strpos($this->strCheckSymbol, ($i + 1) . '-' . $j) == false){
                $this->repeatCount++;
                $this->strWinLinePos = $this->strWinLinePos . '~'.($j * 7 + $i + 1);
                $this->strCheckSymbol = $this->strCheckSymbol . ';' . ($i + 1) . '-' . $j;
                $this->findZokbos($reels, $i + 1, $j, $firstSymbol);
                $bPathEnded = false;
            }

            if($j < 6 && $firstSymbol == $reels['reel' . ($i + 1)][$j + 1] && strpos($this->strCheckSymbol, $i . '-' . ($j + 1)) == false){
                $this->repeatCount++;
                $this->strWinLinePos = $this->strWinLinePos . '~'.(($j + 1) * 7 + $i);
                $this->strCheckSymbol = $this->strCheckSymbol . ';' . $i . '-' . ($j + 1);
                $this->findZokbos($reels, $i, $j + 1, $firstSymbol);
                $bPathEnded = false;
            }

            if($i > 0 && $firstSymbol == $reels['reel' . $i][$j] && strpos($this->strCheckSymbol, ($i - 1) . '-' . $j) == false){
                $this->repeatCount++;
                $this->strWinLinePos = $this->strWinLinePos . '~'.($j * 7 + $i - 1);
                $this->strCheckSymbol = $this->strCheckSymbol . ';' . ($i - 1) . '-' . $j;
                $this->findZokbos($reels, $i - 1, $j, $firstSymbol);
                $bPathEnded = false;
            }

            if($j > 0 && $firstSymbol == $reels['reel' . ($i + 1)][$j - 1] && strpos($this->strCheckSymbol, $i . '-' . ($j - 1)) == false){
                $this->repeatCount++;
                $this->strWinLinePos = $this->strWinLinePos . '~'.(($j - 1) * 7 + $i);
                $this->strCheckSymbol = $this->strCheckSymbol . ';' . $i . '-' . ($j - 1);
                $this->findZokbos($reels, $i, $j - 1, $firstSymbol);
                $bPathEnded = false;
            }
        }
    }

}

<?php 
namespace VanguardLTE\Games\BuffaloKingPM
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
            'reelStrip9_6' => []
        ];
        public function __construct()
        {
            $temp = file(base_path() . '/app/Games/BuffaloKingPM/reels.txt');
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
        public $winLines = [];
        public function generationFreeStacks($slotSettings, $game_id){   
            $wild = '2';
            $scatter = '1';
            $scatters = [3, 4, 5, 6];
            for($i = 0; $i < 3; $i++){
                $bfinish = false;
                while($bfinish == false){
                    $freespinType = $i;
                    $totalWin = 0;
                    $totalReel = [];
                    $freespinCount = $slotSettings->freespinCount[0][$scatters[$i]];
                    for($j = 0; $j < $freespinCount; $j++){                        
                        $lines = 40;
                        $betline = 1;
                        $_spinSettings = $slotSettings->GetSpinSettings('freespin', $betline * $lines, $lines);
                        $winType = $_spinSettings[0];
                        $reelSet_Num = mt_rand(3, 8);
                        for($kk = 0; $kk < 50; $kk++){
                            $this->winLines = [];
                            $subtotalWin = 0;
                            $tempWildReels = [];
                            $reels = $slotSettings->GetReelStrips($winType, 'freespin', $reelSet_Num);
                            for($r = 0; $r < 6; $r++){
                                $tempWildReels[$r] = [];
                                for( $k = 0; $k < 4; $k++ ) 
                                {
                                    if( $reels['reel' . ($r+1)][$k] == $wild) 
                                    {                                
                                        $tempWildReels[$r][$k] = $slotSettings->CheckMultiWild();
                                    }else{
                                        $tempWildReels[$r][$k] = 0;
                                    }
                                }
                            }
                            for($r = 0; $r < 4; $r++){
                                if($reels['reel1'][$r] != $scatter){
                                    $this->findZokbos($reels, $tempWildReels, 0, $reels['reel1'][$r], 1, '~'.($r * 6));
                                }                        
                            }
                            
                            for($r = 0; $r < count($this->winLines); $r++){
                                $winLine = $this->winLines[$r];
                                $winLineMoney = $slotSettings->Paytable[$winLine['FirstSymbol']][$winLine['RepeatCount']] * $betline;
                                if($winLine['Mul'] > 0){
                                    $winLineMoney = $winLineMoney * $winLine['Mul'];
                                }
                                $subtotalWin += $winLineMoney;
                            }

                            $scattersCount = 0;
                            $scattersWin = 0;
                            for( $r = 1; $r <= 6; $r++ ) 
                            {
                                for( $k = 0; $k <= 3; $k++ ) 
                                {
                                    if( $reels['reel' . $r][$k] == $scatter ) 
                                    {
                                        $scattersCount++;
                                    }else if( $reels['reel' . $r][$k] == $wild ) 
                                    {
                                        $isWild = true;
                                    }
                                }
                            }
                            $freeSpinNum = $slotSettings->freespinCount[1][$scattersCount];
                            $subtotalWin = $subtotalWin + $scattersWin;

                            if($scattersCount > 2){

                            }else if($winType == 'win' && $subtotalWin > 0){
                                break;
                            }else if($winType == 'none' && $subtotalWin == 0){
                                if($kk >= 40){
                                    $winType = 'win';
                                }
                                break;
                            }
                        }
                        if($freeSpinNum > 0){
                            $freespinCount += $freeSpinNum;
                        }
                        $totalWin = $totalWin + $subtotalWin / $lines;
                        $item = [];
                        $item['Reel'] = $reels;
                        $item['TempWildReels'] = $tempWildReels;
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
        public function findZokbos($reels, $tempWildReels, $mul, $firstSymbol, $repeatCount, $strLineWin){
            $wild = '2';
            $bPathEnded = true;
            if($repeatCount < 6){
                for($r = 0; $r < 4; $r++){
                    if($firstSymbol == $reels['reel'.($repeatCount + 1)][$r] || $reels['reel'.($repeatCount + 1)][$r] == $wild){
                        if($reels['reel'.($repeatCount + 1)][$r] == $wild){
                            $mul = $mul + $tempWildReels[$repeatCount][$r];
                        }
                        $this->findZokbos($reels, $tempWildReels, $mul, $firstSymbol, $repeatCount + 1, $strLineWin . '~' . ($repeatCount + $r * 6));
                        $bPathEnded = false;
                    }
                }
            }
            if($bPathEnded == true){
                if($repeatCount >= 3){
                    $winLine = [];
                    $winLine['FirstSymbol'] = $firstSymbol;
                    $winLine['Mul'] = $mul;
                    $winLine['RepeatCount'] = $repeatCount;
                    $winLine['StrLineWin'] = $strLineWin;
                    array_push($this->winLines, $winLine);
                }
            }
        }
    }

}

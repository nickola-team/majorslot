<?php 
namespace VanguardLTE\Games\BuffaloKingMegawaysPM
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
            'reelStrip5_7' => [],
            'reelStrip6_1' => [], 
            'reelStrip6_2' => [], 
            'reelStrip6_3' => [], 
            'reelStrip6_4' => [], 
            'reelStrip6_5' => [], 
            'reelStrip6_6' => [], 
            'reelStrip6_7' => [],
            'reelStrip7_1' => [], 
            'reelStrip7_2' => [], 
            'reelStrip7_3' => [], 
            'reelStrip7_4' => [], 
            'reelStrip7_5' => [], 
            'reelStrip7_6' => [], 
            'reelStrip7_7' => []
        ];
        public function __construct()
        {
            $temp = file(base_path() . '/app/Games/BuffaloKingMegawaysPM/reels.txt');
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
        public $bonusMulPoses = [];
        public $bonusMuls = [];
        public $bonusMul = 0;
        public $reelSymbolCount = [0, 0, 0, 0, 0];
        public function generationFreeStacks($slotSettings, $game_id){   
            $wild = '2';
            $scatter = '1';
            $scatterCounts = [4, 5, 6];
            for($i = 0; $i < 3; $i++){
                $bfinish = false;
                while($bfinish == false){
                    $freespinType = $i;
                    $totalWin = 0;
                    $totalReel = [];
                    $freespinCount = $slotSettings->GetFreeSpin($scatterCounts[$i]);
                    for($j = 0; $j < $freespinCount; $j++){                        
                        $isTumb = false;
                        $tumbTotalWin = 0;
                        $lines = 20;
                        $betline = 1;
                        $lastReel = [];
                        $this->repeatCount = 0;
                        $this->binaryReel = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                        $this->bonusMulPoses = [];
                        $this->bonusMuls = [];
                        $this->bonusMul = 1;
                        $this->reelSymbolCount = [0, 0, 0, 0, 0];
                        while(true){
                            $_spinSettings = $slotSettings->GetSpinSettings('freespin', $betline * $lines, $lines, 0);
                            $winType = $_spinSettings[0];
                            if($winType == 'bonus'){
                                $winType = 'win';
                            }
                            $currentReelSet = 6;
                            if($isTumb == true){
                                $arr_lastReel = $slotSettings->GetLastReel($lastReel, $this->binaryReel, $this->bonusMulPoses, $this->bonusMuls); // 텀브스핀일때 이전 릴배치도에서 당첨되지않은 심볼 배열, 와일드 위치, 와일드 배당값을 결정
                                $lastReel = $arr_lastReel[0];
                                $this->bonusMulPoses = $arr_lastReel[1];
                                $this->bonusMuls = $arr_lastReel[2];
                                if($this->repeatCount > 3){
                                    $winType = 'none';
                                    $_winAvaliableMoney = 0;
                                }
                            }
                            
                            for($kk = 0; $kk < 50; $kk++){
                                $this->winLines = [];
                                $bonusMul = $this->bonusMul;
                                $bonusMuls = $this->bonusMuls;
                                $bonusMulPoses = $this->bonusMulPoses;             
                                $reelSymbolCount = $this->reelSymbolCount;      
                                $reels = $slotSettings->GetReelStrips($winType, 'freespin', $currentReelSet, 0, $isTumb, $reelSymbolCount);                               
                                $subtotalWin = 0;
                                for( $k = 0; $k < 8; $k++ ){
                                    $isNewWild = false; // 릴에 와일드가 이미 생성되었으면 true or false
                                    for($r = 0; $r < 6; $r++)
                                    {
                                        if($lastReel != null && $lastReel[$r][$k] > -1 && $lastReel[$r][$k] < 13) 
                                        {                                
                                            $reels['reel' . ($r+1)][$k] = $lastReel[$r][$k]; // 텀브스핀일때 이전심볼배열을 새배열에 추가
                                        }else{
                                            if($r > 0 && $r < 5 && $reels['reel' . ($r+1)][$k] < 13 && rand(0, 100) < 3 && $isNewWild == false){  // 프리스핀에서 와일드 생성및 배당값 설정
                                                $reels['reel' . ($r+1)][$k] = $wild;
                                                array_push($bonusMuls, $slotSettings->GetMultiValue());
                                                if($k == 0){
                                                    array_push($bonusMulPoses, 5 - $r);
                                                }else{                                            
                                                    array_push($bonusMulPoses, $k * 6 + $r);
                                                }
                                                $isNewWild == true;
                                            }
                                        }                                
                                    }
                                }
                                for($r = 0; $r < 8; $r++){
                                    if($reels['reel1'][$r] != $scatter && $reels['reel1'][$r] != 13){
                                        $this->findZokbos($reels, $reels['reel1'][$r], 1, '~'.($r * 6));
                                    }                        
                                }
                                $isNewTumb = false;
                                $this->binaryReel = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                                for($r = 0; $r < count($this->winLines); $r++){
                                    $winLine = $this->winLines[$r];
                                    $winLineMoney = $slotSettings->Paytable[$winLine['FirstSymbol']][$winLine['RepeatCount']] * $betline;
                                    if($winLineMoney > 0){   
                                        $isNewTumb = true;
                                        $subtotalWin += $winLineMoney;
                                        for($k = 0; $k < $winLine['RepeatCount']; $k++){
                                            for($l = 0; $l < 8; $l++){
                                                if($reels['reel'.($k + 1)][$l] == $winLine['FirstSymbol'] || $reels['reel'.($k + 1)][$l] == 2){ // 당첨된 심볼들은 심볼값 or 0
                                                    if($l == 0){
                                                        $this->binaryReel[5 - $k] = $reels['reel'.($k + 1)][$l]; // Top에서는 릴배치도가 반대로 되어있음
                                                    }else{
                                                        $this->binaryReel[$l * 6 + $k] = $reels['reel'.($k + 1)][$l];
                                                    }
                                                }
                                            }
                                        }
                                    }
                                } 
                                for($k = 0; $k < count($bonusMulPoses); $k++){
                                    if($this->binaryReel[$bonusMulPoses[$k]] == 2 || ($isTumb == true && $isNewTumb == false)){     // 텀브스핀이 끝날때 당첨되지않은 와일드 멀티값도 추가함
                                        $bonusMul = $bonusMul * $bonusMuls[$k];
                                    }
                                }

                                $scattersCount = 0;
                                $_obf_scatterposes = [];
                                for($r = 0; $r < 6; $r++){
                                    for( $k = 0; $k < 8; $k++ ) 
                                    {
                                        if( $reels['reel' . ($r+1)][$k] == $scatter ) 
                                        {                                
                                            $scattersCount++;
                                            if($k == 0){
                                                array_push($_obf_scatterposes, 4 - $r);
                                            }else{
                                                array_push($_obf_scatterposes, $k * 6 + $r);
                                            }
                                        }
                                    }
                                }
                                $freeSpinNum = 0;
                                $scatterWin = 0;    
                                if($scattersCount >= 3){
    
                                }else if($winType == 'win' && $subtotalWin > 0){
                                    break;
                                }else if($winType == 'none' && $subtotalWin == 0){
                                    if($kk >= 40){
                                        $winType = 'win';
                                    }
                                    break;
                                }
                            }
                            $this->bonusMul = $bonusMul;
                            $this->bonusMuls = $bonusMuls;
                            $this->bonusMulPoses = $bonusMulPoses;    
                            $reelSymbolCount = [0,0,0,0,0,0];
                            for($k = 0; $k < 8; $k++){
                                for($r = 1; $r <= 6; $r++){
                                    if($k == 0){
                                        $lastReel[$r - 1] = $reels['reel'.(7 - $r)][$k];
                                    }else{
                                        $lastReel[($r - 1) + $k * 6] = $reels['reel'.$r][$k];
                                        if($reels['reel'.$r][$k] == 13 && $reelSymbolCount[$r - 1] == 0){
                                            $reelSymbolCount[$r - 1] = $k - 1;
                                        }
                                    }
                                }
                            }
                            for($k = 0; $k < 6; $k++){
                                if($reelSymbolCount[$k] == 0){
                                    $reelSymbolCount[$k] = 7;
                                }
                            }
                            if($isTumb == false){                                
                                $this->reelSymbolCount = $reelSymbolCount; 
                            }
                            $item = [];
                            $item['Reel'] = $reels;
                            $item['BonusMul'] = $this->bonusMul;
                            $item['BonusMuls'] = $this->bonusMuls;
                            $item['BonusMulPoses'] = $this->bonusMulPoses;
                            $item['ReelSymbolCount'] = $this->reelSymbolCount;
                            array_push($totalReel, $item);
                            if($subtotalWin > 0){
                                $totalWin = $totalWin + $subtotalWin / $lines;
                                $tumbTotalWin = $tumbTotalWin + $subtotalWin / $lines;
                                $isTumb = true;
                                $this->repeatCount++;
                            }else{
                                if($bonusMul > 1){
                                    $totalWin = $totalWin + $tumbTotalWin * ($bonusMul - 1);
                                }
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
        public function findZokbos($reels, $firstSymbol, $repeatCount, $strLineWin){
            $wild = '2';
            $bPathEnded = true;
            if($repeatCount < 6){
                for($r = 0; $r < 8; $r++){
                    if($firstSymbol == $reels['reel'.($repeatCount + 1)][$r] || $reels['reel'.($repeatCount + 1)][$r] == $wild){
                        $this->findZokbos($reels, $firstSymbol, $repeatCount + 1, $strLineWin . '~' . ($repeatCount + $r * 6));
                        $bPathEnded = false;
                    }
                }
            }
            if($bPathEnded == true){
                if($repeatCount >= 3 || ($firstSymbol == 3 && $repeatCount == 2)){
                    $winLine = [];
                    $winLine['FirstSymbol'] = $firstSymbol;
                    $winLine['RepeatCount'] = $repeatCount;
                    $winLine['StrLineWin'] = $strLineWin;
                    array_push($this->winLines, $winLine);
                }
            }
        }
    }

}

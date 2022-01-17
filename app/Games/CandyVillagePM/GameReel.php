<?php 
namespace VanguardLTE\Games\CandyVillagePM
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
            'reelStrip5_6' => []
        ];
        public function __construct()
        {
            $temp = file(base_path() . '/app/Games/CandyVillagePM/reels.txt');
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
        public function generationFreeStacks($slotSettings, $game_id){   
            $wild = '2';
            $scatter = '1';
            $bonus = '12';
            $scatterCounts = [4];
            for($i = 0; $i < 1; $i++){
                $bfinish = false;
                while($bfinish == false){
                    $freespinType = $i;
                    $totalWin = 0;
                    $totalReel = [];
                    $freespinCount = $slotSettings->freespinCount;
                    for($j = 0; $j < $freespinCount; $j++){                        
                        $isTumb = false;
                        $tumbTotalWin = 0;
                        $lines = 20;
                        $betline = 1;
                        $lastReel = [];
                        $this->binaryReel = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                        $this->bonusMulPoses = [];
                        $this->bonusMuls = [];
                        while(true){
                            if($isTumb == true){
                                $reelsAndPoses = $slotSettings->GetLastReel($lastReel, $this->binaryReel, $this->bonusMulPoses);
                                $lastReel = $reelsAndPoses[0];
                                $this->bonusMulPoses = $reelsAndPoses[1];
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
                            
                            for($kk = 0; $kk < 50; $kk++){
                                $winLines = [];
                                $bonusMuls = $this->bonusMuls;
                                $bonusMulPoses = $this->bonusMulPoses;
                                $bonusMul = 0;
                                if($isTumb == true && $lastReel != null){
                                    $reels = $slotSettings->GetTumbReelStrips($lastReel, $currentReelSet);
                                }else{
                                    $reels = $slotSettings->GetReelStrips($winType, 'freespin', $currentReelSet, 0);
                                }                                
                                $subtotalWin = 0;

                                for($l = 1; $l < 13; $l++){
                                    $arr_symbols = [];
                                    for( $k = 0; $k < 5; $k++ ) 
                                    {
                                        for($r = 0; $r < 6; $r++){ 
                                            if( $reels['reel' . ($r+1)][$k] == $l) 
                                            {                                
                                                array_push($arr_symbols, $r + $k * 6);
                                            }
                                        }
                                    }                      
                                    $winLines[$l] = $arr_symbols;
                                }

                                $this->binaryReel = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                                for($r = 3; $r < 12; $r++){
                                    $winLine = $winLines[$r];
                                    $winLineMoney = $slotSettings->Paytable[$r][count($winLine)] * $betline;
                                    if($winLineMoney > 0){
                                        $isNewTumb = true;
                                        for($k = 0; $k < count($winLine); $k++){
                                            $this->binaryReel[$winLine[$k]] = $r;
                                        }
                                        $subtotalWin += $winLineMoney;
                                    }
                                } 
                                $bonusSymbolCount = 0;
                                for($r = 0; $r < count($winLines[$bonus]); $r++){
                                    $isExit = false;
                                    for($k = 0; $k < count($bonusMulPoses);$k++){
                                        if($winLines[$bonus][$r] == $bonusMulPoses[$k]){
                                            $isExit = true;
                                            break;
                                        }
                                    }
                                    if($isExit == false){
                                        array_push($bonusMuls, $slotSettings->GetBonusMul($isTumb, $winType));
                                        array_push($bonusMulPoses, $winLines[$bonus][$r]);
                                    }
                                    $bonusSymbolCount++;
                                }

                                $scattersCount = count($winLines[$scatter]);
                                $isdoubleScatter = false;
                                for($r = 0; $r < 6; $r++){ 
                                    $lineScatters = 0;
                                    for( $k = 0; $k < 5; $k++ ) 
                                    {
                                        if( $reels['reel' . ($r+1)][$k] == $scatter) 
                                        {                                
                                            $lineScatters++;
                                        }
                                    }                      
                                    if($lineScatters >= 2){
                                        $isdoubleScatter = true;
                                        break;
                                    }
                                }
                                for($k = 0; $k < count($bonusMuls);$k++){
                                    $bonusMul = $bonusMul + $bonusMuls[$k];
                                }
                                if($bonusMul == 0){
                                    $bonusMul = 1;
                                }
    
                                if($scattersCount >= 3){
    
                                }else if($isdoubleScatter == true){

                                }else if($bonusSymbolCount > 2){
                            
                                }else if($winType == 'win' && $subtotalWin > 0){
                                    break;
                                }else if($winType == 'none' && $subtotalWin == 0){
                                    if($kk >= 40){
                                        $winType = 'win';
                                    }
                                    break;
                                }
                            }
                            $this->bonusMuls = $bonusMuls;
                            $this->bonusMulPoses = $bonusMulPoses;
                            $item = [];
                            $item['Reel'] = $reels;
                            $item['BonusMuls'] = $this->bonusMuls;
                            $item['BonusMulPoses'] = $bonusMulPoses;
                            array_push($totalReel, $item);
                            if($subtotalWin > 0){
                                $totalWin = $totalWin + $subtotalWin / $lines;
                                $tumbTotalWin = $tumbTotalWin + $subtotalWin / $lines;
                                $isTumb = true;
                                for($k = 0; $k < 5; $k++){
                                    for($l = 1; $l <= 6; $l++){
                                        $lastReel[($l - 1) + $k * 6] = $reels['reel'.$l][$k];
                                    }
                                }
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
    }

}

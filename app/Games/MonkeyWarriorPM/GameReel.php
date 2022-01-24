<?php 
namespace VanguardLTE\Games\MonkeyWarriorPM
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
            $temp = file(base_path() . '/app/Games/MonkeyWarriorPM/reels.txt');
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
        public $winLines = [];
        public function generationFreeStacks($slotSettings, $game_id){   
            $wild = '2';
            $scatter = '1';
            $moneysymbol = '11';
            for($i = 0; $i < 1; $i++){
                $bfinish = false;
                while($bfinish == false){
                    $freespinType = 0;
                    $totalWin = 0;
                    $totalReel = [];
                    $freespinCount = $slotSettings->slotFreeCount;
                    for($k = 0; $k < $freespinCount; $k++){                        
                        $lines = 25;
                        $betline = 1;
                        $_spinSettings = $slotSettings->GetSpinSettings('freespin', $betline * $lines, $lines);
                        $winType = $_spinSettings[0];
                        for($kk = 0; $kk < 50; $kk++){
                            $this->winLines = [];
                            $subtotalWin = 0;
                            $reels = $slotSettings->GetReelStrips($winType, 'freespin', false, []);                            
                            for($r = 0; $r < 3; $r++){
                                if($reels['reel1'][$r] != $scatter && $reels['reel1'][$r] != $moneysymbol){
                                    $this->findZokbos($reels, $reels['reel1'][$r], 1, '~'.($r * 5));
                                }                        
                            }
                            for($r = 0; $r < count($this->winLines); $r++){
                                $winLine = $this->winLines[$r];
                                $winLineMoney = $slotSettings->Paytable[$winLine['FirstSymbol']][$winLine['RepeatCount']] * $betline;
                                if($winLineMoney > 0){
                                    $subtotalWin += $winLineMoney;
                                }
                            }      
                            $scattersCount = 0;
                            $moneyCount = 0;
                            for( $r = 1; $r <= 5; $r++ ) 
                            {
                                for( $m = 0; $m <= 2; $m++ ) 
                                {
                                    if( $reels['reel' . $r][$m] == $scatter ) 
                                    {
                                        $scattersCount++;
                                    }
                                    if( $reels['reel' . $r][$m] == $moneysymbol ) 
                                    {
                                        $moneyCount++;
                                    }
                                }
                            }
                            if($scattersCount >= 3 || $moneyCount >= 6){

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
        public function findZokbos($reels, $firstSymbol, $repeatCount, $strLineWin){
            $wild = '2';
            $bPathEnded = true;
            if($repeatCount < 5){
                for($r = 0; $r < 3; $r++){
                    if($firstSymbol == $reels['reel'.($repeatCount + 1)][$r] || $reels['reel'.($repeatCount + 1)][$r] == $wild){
                        $this->findZokbos($reels, $firstSymbol, $repeatCount + 1, $strLineWin . '~' . ($repeatCount + $r * 5));
                        $bPathEnded = false;
                    }
                }
            }
            if($bPathEnded == true){
                if($repeatCount >= 3){
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

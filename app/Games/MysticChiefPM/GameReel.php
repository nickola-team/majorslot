<?php 
namespace VanguardLTE\Games\MysticChiefPM
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
            'reelStrip10_6' => [],
            'reelStrip11_1' => [], 
            'reelStrip11_2' => [], 
            'reelStrip11_3' => [], 
            'reelStrip11_4' => [], 
            'reelStrip11_5' => [], 
            'reelStrip11_6' => [],
            'reelStrip12_1' => [], 
            'reelStrip12_2' => [], 
            'reelStrip12_3' => [], 
            'reelStrip12_4' => [], 
            'reelStrip12_5' => [], 
            'reelStrip12_6' => []
        ];
        public function __construct()
        {
            $temp = file(base_path() . '/app/Games/MysticChiefPM/reels.txt');
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
            $wild = '14';
            $bonus = '15';
            for($i = 0; $i < 1; $i++){
                for($j = 0; $j < 3; $j++){
                    $freespinType = 1;
                    $totalWin = 0;
                    $totalReel = [];
                    $freespinCount = 8;
                    for($k = 0; $k < $freespinCount; $k++){                        
                        $lines = 20;
                        $betline = 1;
                        $_spinSettings = $slotSettings->GetSpinSettings('freespin', $betline * $lines, $lines);
                        $winType = $_spinSettings[0];
                        for($kk = 0; $kk < 10; $kk++){
                            $this->winLines = [];
                            $subtotalWin = 0;
                            $initReels = $slotSettings->GetReelStrips($winType, 'freespin', 4);
                            $wildReels = $slotSettings->GetWildReels('freespin');
                            $bonusCount = 0;
                            for($r = 1; $r <= 5; $r++){
                                for($m = 0; $m < 4; $m++){
                                    if($initReels['reel'.$r][$m] == $bonus){
                                        $bonusCount++;
                                        break;
                                    }
                                }
                            }
                            $isChangedWild = false;
                            $wildMuls = [1,1,1,1,1];
                            $reels = [];
                            $arr_ep_initwilds = [];
                            $arr_ep_wilds = [];
                            for($r = 0; $r < count($wildReels); $r++){
                                $row = mt_rand(0, 3);
                                $initReels['reel' . ($wildReels[$r] + 1)][$row] = 14;
                                $wildMuls[$wildReels[$r]] = $slotSettings->getWildMul();
                                array_push($arr_ep_initwilds, $row * 5 + $wildReels[$r]);
                            }
                            for($r = 1; $r <= 5; $r++){
                                $reels['reel'.$r] = [];
                                $isWild = false;
                                for($m = 0; $m < 4; $m++){
                                    $reels['reel'.$r][$m] = $initReels['reel'.$r][$m];
                                    if($reels['reel'.$r][$m] == 14){
                                        $isWild = true;
                                    }
                                }
                                if($isWild == true){                                    
                                    $isChangedWild = true;
                                    for($m = 0; $m < 4; $m++){
                                        $reels['reel'.$r][$m] = 14;
                                        array_push($arr_ep_wilds, $m * 5 + $r - 1);
                                    }   
                                }
                            }
                            $str_ep = '';
                            if(count($arr_ep_initwilds) > 0){
                                $str_ep = '&ep=14~' . implode(',', $arr_ep_initwilds) . '~' . implode(',', $arr_ep_wilds);
                            }
                            
                            for($r = 0; $r < 4; $r++){
                                if($reels['reel1'][$r] < 13){
                                    $this->findZokbos($reels, $wildMuls, 0, $reels['reel1'][$r], 1, '~'.($r * 5));
                                }                        
                            }
                            for($r = 0; $r < count($this->winLines); $r++){
                                $winLine = $this->winLines[$r];
                                $bonusMpl = 1;
                                if($winLine['Mul'] > 0){
                                    $bonusMpl = $winLine['Mul'];
                                }
                                $winLineMoney = $slotSettings->Paytable[$winLine['FirstSymbol']][$winLine['RepeatCount']] * $betline * $bonusMpl;
                                $subtotalWin += $winLineMoney;
                            }
                            if($bonusCount >= 2){

                            }else if($winType == 'win' && $subtotalWin > 0){
                                break;
                            }else if($winType == 'none' && $subtotalWin == 0){
                                if($kk >= 8){
                                    $winType = 'win';
                                }
                                break;
                            }
                        }
                        if($bonusCount == 1){
                            $freespinCount += 1;
                        }
                        $totalWin = $totalWin + $subtotalWin / $lines;
                        $item = [];
                        $item['initReel'] = $initReels;
                        $item['Reel'] = $reels;
                        $item['WildReels'] = $wildReels;
                        $item['WildMuls'] = $wildMuls;
                        $item['Str_ep'] = $str_ep;
                        array_push($totalReel, $item);
                    }
                    $this->SaveReel($game_id, $freespinType, $totalReel, $totalWin, $freespinCount);
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
            if($repeatCount < 5){
                for($r = 0; $r < 4; $r++){
                    if($firstSymbol == $reels['reel'.($repeatCount + 1)][$r] || $reels['reel'.($repeatCount + 1)][$r] == $wild || $reels['reel'.($repeatCount + 1)][$r] == 14){
                        $addMul = 0;
                        if($reels['reel'.($repeatCount + 1)][$r] == 14 && $tempWildReels[$repeatCount] > 1){
                            $addMul = $tempWildReels[$repeatCount];
                        }
                        $this->findZokbos($reels, $tempWildReels, $mul + $addMul, $firstSymbol, $repeatCount + 1, $strLineWin . '~' . ($repeatCount + $r * 5));
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

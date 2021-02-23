<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend
{
    class SpinGeneraterController extends \VanguardLTE\Http\Controllers\Controller
    {
        public $winLines = [];

        public $reelStrip1 = null;
        public $reelStrip2 = null;
        public $reelStrip3 = null;
        public $reelStrip4 = null;
        public $reelStrip5 = null;
        public $reelStrip6 = null;
        public $reelStripBonus1 = null;
        public $reelStripBonus2 = null;
        public $reelStripBonus3 = null;
        public $reelStripBonus4 = null;
        public $reelStripBonus5 = null;
        public $reelStripBonus6 = null;
        public $Paytable = [];


        public function __construct()
        {
            $this->middleware('auth');
            $this->middleware('permission:access.admin.panel');
            $this->middleware('permission:api.manage');


            $this->Paytable[0]  = [0,0,0,0,0,0];
            $this->Paytable[1]  = [0,0,0,100,150,750];
            $this->Paytable[2]  = [0,0,0,50,100,450];
            $this->Paytable[3]  = [0,0,0,40,80,300];
            $this->Paytable[4]  = [0,0,0,20,50,200];
            $this->Paytable[5]  = [0,0,0,10,25,138];
            $this->Paytable[6]  = [0,0,0,5,10,30];
            $this->Paytable[7]  = [0,0,0,5,10,30];
            $this->Paytable[8]  = [0,0,0,5,10,25];
            $this->Paytable[9]  = [0,0,0,5,10,25];
            $this->Paytable[10] = [0,0,0,5,10,20];
            $this->Paytable[11] = [0,0,0,5,10,20];
            $this->Paytable[12] = [0,0,0,0,0,0];
            $reel = new \VanguardLTE\Games\DuoFuDuoCai5Treasures\GameReel();

            foreach( [
                'reelStrip1', 
                'reelStrip2', 
                'reelStrip3', 
                'reelStrip4', 
                'reelStrip5', 
                'reelStrip6'
            ] as $reelStrip ) 
            {
                if( count($reel->reelsStrip[$reelStrip]) ) 
                {
                    $this->$reelStrip = $reel->reelsStrip[$reelStrip];
                }
            }
            foreach( [
                'reelStripBonus1', 
                'reelStripBonus2', 
                'reelStripBonus3', 
                'reelStripBonus4', 
                'reelStripBonus5', 
                'reelStripBonus6'
            ] as $reelStrip ) 
            {
                if( count($reel->reelsStripBonus[$reelStrip]) ) 
                {
                    $this->$reelStrip = $reel->reelsStripBonus[$reelStrip];
                }
            }


        }
        
        public function generateFreespin()
        {
            $freespin_types = [88, 98, 108, 118, 128];
            $freespin_symbols = [1, 2, 3, 4, 5];

            $lines = 88;
            $betline = 10;
            $totalbet = $lines * $betline;
            for($j = 0; $j < count($freespin_types); $j++){
                for( $i = 0; $i < 10000; $i++ ) 
                {
                    $totalWin = 0;
                    $freespin_stack = [];                    
                    $strFreeStack = '[';
                    for($k = 0; $k < 6; $k++) {
                        $lineWins = [];
                        $lineWinNum = [];
                        $wild = '0';
                        $scatter = '12';
                        $_obf_winCount = 0;
                        $strWinLine = '';
                        $this->winLines = [];
                        $winType = 'none';
                        $slotEvent = 'freespin';
                        $reels = $this->GetReelStrips($winType, $slotEvent);
                        
                        for( $r = 1; $r <= 5; $r++ ) 
                        {
                            for( $kk = 0; $kk <= 2; $kk++ ) 
                            {
                                if( $reels['reel' . $r][$kk] <= 5 ) 
                                {
                                    $reels['reel' . $r][$kk] = $freespin_symbols[$j];
                                }
                            }
                        }

                        for($r = 0; $r < 3; $r++){
                            $this->findZokbos($reels, 1, $reels['reel1'][$r], 1);
                        }
                        
                        for($r = 0; $r < count($this->winLines); $r++){
                            $winLine = $this->winLines[$r];
                            $winLineMoney = $this->Paytable[$winLine['FirstSymbol']][$winLine['RepeatCount']];
                            $totalWin += $winLineMoney;
                        }   
                        $lastReel = [];
                        $lastReel[0] = $freespin_types[$j];
                        for($m = 1; $m <= 5; $m++){
                            for($n = 0; $n <= 2; $n++){
                                array_push($lastReel, $reels['reel'.$m][$n]);
                            }
                        }
                        if($k == 5){
                            $strFreeStack = $strFreeStack . '[' . implode(',', $lastReel) . ']';
                        }else{                            
                            $strFreeStack = $strFreeStack . '[' . implode(',', $lastReel) . '], ';
                        }
                    }
                    $strFreeStack = $strFreeStack . ']';

                    \VanguardLTE\GameFreeSpin::create([
                        'game_id' => '1', 
                        'odd' => $totalWin, 
                        'free_spin_stack' => $strFreeStack, 
                        'free_spin_count' => 6,
                        'free_spin_type' => $freespin_types[$j]
                    ]);

                }
            }
        }


        public function GetReelStrips($winType, $slotEvent)
        {
            if($slotEvent=='freespin'){
                if( $winType != 'bonus' ) 
                {
                    $_obf_reelStripCounts = [];
                    foreach( [
                        'reelStripBonus1', 
                        'reelStripBonus2', 
                        'reelStripBonus3', 
                        'reelStripBonus4', 
                        'reelStripBonus5', 
                        'reelStripBonus6'
                    ] as $index => $reelStrip ) 
                    {
                        if( is_array($this->$reelStrip) && count($this->$reelStrip) > 0 ) 
                        {
                            $_obf_reelStripCounts[$index + 1] = mt_rand(0, count($this->$reelStrip) - 3);
                        }
                    }
                }
            }
            $reel = [
                'rp' => []
            ];
            foreach( $_obf_reelStripCounts as $index => $value ) 
            {
                $key = $this->{'reelStrip' . $index};
                if($slotEvent=='freespin'){
                    $key = $this->{'reelStripBonus' . $index};
                }
                $rc = count($key);
                $key[-1] = $key[$rc - 1];
                $key[$rc] = $key[0];
                $reel['reel' . $index][0] = $key[$value];
                $reel['reel' . $index][1] = $key[$value + 1];
                $reel['reel' . $index][2] = $key[$value + 2];
                $reel['rp'][] = $value;
            }
            return $reel;
        }

        public function findZokbos($reels, $mul, $firstSymbol, $repeatCount){
            $wild = '0';
            $bPathEnded = true;
            if($repeatCount < 5){
                for($r = 0; $r < 3; $r++){
                    if($firstSymbol == $reels['reel'.($repeatCount + 1)][$r] || $reels['reel'.($repeatCount + 1)][$r] == $wild){
                        $this->findZokbos($reels, $mul, $firstSymbol, $repeatCount + 1);
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
                    array_push($this->winLines, $winLine);
                }
            }
        }



    }

}
namespace 
{
    function onkXppk3PRSZPackRnkDOJaZ9()
    {
        return 'OkBM2iHjbd6FHZjtvLpNHOc3lslbxTJP6cqXsMdE4evvckFTgS';
    }

}

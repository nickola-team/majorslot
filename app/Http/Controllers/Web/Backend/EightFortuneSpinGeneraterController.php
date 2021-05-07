<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend
{
    class EightFortuneSpinGeneraterController extends \VanguardLTE\Http\Controllers\Controller
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
            $this->Paytable[1]  = [0,0,0,100,200,1000];
            $this->Paytable[2]  = [0,0,0,50,100,500];
            $this->Paytable[3]  = [0,0,0,40,80,400];
            $this->Paytable[4]  = [0,0,0,25,50,250];
            $this->Paytable[5]  = [0,0,0,10,20,100];
            $this->Paytable[6]  = [0,0,0,5,10,50];
            $this->Paytable[7]  = [0,0,0,5,10,50];
            $this->Paytable[8]  = [0,0,0,5,10,50];
            $this->Paytable[9]  = [0,0,0,5,10,50];
            $this->Paytable[10] = [0,0,0,5,10,50];
            $this->Paytable[11] = [0,0,0,5,10,50];
            $this->Paytable[12] = [0,0,0,0,0,0];
            $reel = new \VanguardLTE\Games\DuoFuDuoCai88Fortune\GameReel();

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
            /*$freespin_types = [1];
            $freespin_counts = [10];

            $lines = 88;
            $betline = 10;
            $totalbet = $lines * $betline;
            for($j = 0; $j < count($freespin_types); $j++){
                for( $i = 0; $i < 20000; $i++ ) 
                {
                    $totalWin = 0;
                    $freespin_stack = [];                    
                    $strFreeStack = '[';
                    $repeatCount = 0;
                    while($repeatCount < $freespin_counts[$j]){
                        $lineWins = [];
                        $lineWinNum = [];
                        $wild = '0';
                        $scatter = '12';
                        $_obf_winCount = 0;
                        $normalWin = 0;
                        $strWinLine = '';
                        $this->winLines = [];
                        $winType = 'none';
                        $slotEvent = 'freespin';
                        $reels = $this->GetReelStrips($winType, $slotEvent);
                        
                        for($r = 0; $r < 3; $r++){
                            $this->findZokbos($reels, 1, $reels['reel1'][$r], 1);
                        }
                        $scatterCount = 0;
                        for($r = 0; $r < count($this->winLines); $r++){
                            $winLine = $this->winLines[$r];
                            $winLineMoney = $this->Paytable[$winLine['FirstSymbol']][$winLine['RepeatCount']];
                            $normalWin += $winLineMoney;
                            if($winLine['RepeatCount'] >= 3 && $winLine['FirstSymbol'] == $scatter){
                                $scatterCount = $winLine['RepeatCount'];
                                break;
                            }
                        }   
                        if($scatterCount < 3){
                            $repeatCount++;
                            $lastReel = [];
                            $lastReel[0] = $freespin_types[$j];
                            for($m = 1; $m <= 5; $m++){
                                for($n = 0; $n < 3; $n++){
                                    array_push($lastReel, $reels['reel'.$m][$n]);
                                }
                            }
                            if($repeatCount == $freespin_counts[$j]){
                                $strFreeStack = $strFreeStack . '[' . implode(',', $lastReel) . ']';
                            }else{                            
                                $strFreeStack = $strFreeStack . '[' . implode(',', $lastReel) . '], ';
                            }
                            $totalWin = $totalWin + $normalWin;
                        }
                    }
                    $strFreeStack = $strFreeStack . ']';

                    \VanguardLTE\GameFreeSpinEF::create([
                        'game_id' => '981', 
                        'odd' => $totalWin, 
                        'free_spin_stack' => $strFreeStack, 
                        'free_spin_count' => 10,
                        'free_spin_type' => $freespin_types[$j]
                    ]);

                }
            }*/
            set_time_limit(0);
            $time = strtotime("-1 hours") * 10000;
            $records = \VanguardLTE\PPTransaction::where('timestamp', '>', $time)->orderBy('timestamp','DESC')->get();
            $msg = '<table class="table table-bordered table-striped">
            <thead>
            <tr>
                        <th>레퍼런스</th>
                        <th>요청시간</th>
                        <th>수신시간</th>
						<th>응답시간</th>
						<th>인터넷시간</th>
                        <th>처리시간</th>
                        <th>전체시간</th>
						</tr>
                        </thead>
					<tbody>';
            foreach ($records as $record)
            {
                $timestamp = $record->timestamp;
                $data = json_decode($record->data);
                if (isset($data) && isset($data->timestamp) && $timestamp / 10 - $data->timestamp >= 5*1000)
                {
                    if (isset($data->herestamp)){
                        $msg = $msg . "<tr><td>" . $record->reference . "</td><td>" .  $data->timestamp . "</td><td>". ($data->herestamp/10) . "</td><td>". $timestamp/ 10 ."</td><td>" . ($data->herestamp / 10 - $data->timestamp) . "</td><td>" . ($timestamp/10 - $data->herestamp / 10 ). "</td><td>" . ($timestamp / 10 - $data->timestamp) . "</td></tr>";
                    }
                    else
                    {
                        $msg = $msg . "<tr><td>" . $record->reference . "</td><td>" .  $data->timestamp . "</td><td>". '?' . "</td><td>". ($timestamp/ 10) ."</td><td>" . '?' . "</td><td>" . "?" ."</td><td>" . ($timestamp / 10 - $data->timestamp) . "</td></tr>";
                    }
                }
                
            }
            $msg = $msg . '</tbody></table>';
            $res = ['error' => true, 'msg' => $msg];
            return response()->json($res);
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
                for($k=0; $k < 3; $k++){
                    $reel['reel' . $index][$k] = $key[$value + $k];
                }
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

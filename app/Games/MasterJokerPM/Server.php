<?php 
namespace VanguardLTE\Games\MasterJokerPM
{
    class Server
    {
        public $winLines = [];
        public function get($request, $game, $userId) // changed by game developer
        {
            $response = '';
            \DB::beginTransaction();
            if( $userId == null ) 
            {
            	$response = 'unlogged';
                exit( $response );
            }
            $user = \VanguardLTE\User::lockForUpdate()->find($userId);
            $lines = 1;
            $credits = null;
            $slotSettings = new SlotSettings($game, $userId, $credits);
            $paramData = trim(file_get_contents('php://input'));
            $_obf_params = explode('&', $paramData);
            $slotEvent = [];
            foreach( $_obf_params as $_obf_param ) 
            {
                $_obf_arr = explode('=', $_obf_param);
                $slotEvent[$_obf_arr[0]] = $_obf_arr[1];
            }
            if( !isset($slotEvent['action']) ) 
            {
                return '';
            }
            $slotEvent['slotEvent'] = $slotEvent['action']; 

            if($slotEvent['slotEvent'] == 'doSpin' && isset($slotEvent['c'])) 
            { 
               $slotSettings->SetGameData($slotSettings->slotId . 'Bet', $slotEvent['c']); 
               $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 1); 
            } 
            $slotSettings->SetBet(); 


            if( $slotEvent['slotEvent'] == 'doInit' ) 
            { 
                $lastEvent = $slotSettings->GetHistory();
                $_obf_StrResponse = '';
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [6,7,9,9,5]);
                
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $bet = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $bet = '1000.00';
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                $Balance = $slotSettings->GetBalance();

                $response = 'tw=0.00&def_s=5,6,3,6,8&balance='. $Balance .'&action=doSpin&cfgs=2694&reel1=3,7,4,7,3,6,7,6,7,6,7,6,8,8,8,6,7,6,7,5,9,8&ver=2&reel0=3,4,5,6,4,5,6,7,8,4,5,6,4,5,9&index=1&balance_cash='. $Balance .'&def_sb=5,7,8,5,4&def_sa=4,8,7,6,5&reel3=9,9,9,8,7,8,7,8,7,8,7,8,8,7,8,7,8,3,8,4,8,5,8,6,8,7&reel2=8,7,5,8,2,4,8,7,3,8,7,2,6,7,8,6,8,6,8,4,5,6,8,6,9,8,6,8&reel4=8,8,3,8,8,4,8,8,5,8,8,5,8,8,6,8,5,8,8,5,7,9&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0,0~0,0,0,0,0~0,0,0,0,0,0&gmb=0,0,0&rt=d&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=5,8,8,8,6&sb=7,8,7,4,9&sc='. implode(',', $slotSettings->Bet) .'&defc=2000.00&sh=1&wilds=2~0,0,0,0,0,0~1,1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&counter=2&l=1&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;100,50,15,0,0;50,25,10,0,0;25,15,8,0,0;15,10,5,0,0;12,8,4,0,0;10,5,2,0,0;8,3,1,0,0&s='.$lastReelStr. '&t=symbol_count&w=0.00';
            }
            else if( $slotEvent['slotEvent'] == 'doCollect') 
            {
                $Balance = $slotSettings->GetBalance();
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);    
                $response = 'balance=' . $Balance . '&index=' . $slotEvent['index'] . '&balance_cash=' . $Balance . '&balance_bonus=0.00&na=s&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sver=5&counter=' . ((int)$slotEvent['counter'] + 1);
            }
            else if( $slotEvent['slotEvent'] == 'doSpin' ) 
            {
                $linesId = [];
                $linesId[0] = [2,2,2,2,2];
                $linesId[1] = [1,1,1,1,1];
                $linesId[2] = [3,3,3,3,3];
                $linesId[3] = [1,2,3,2,1];
                $linesId[4] = [3,2,1,2,3];
                $lastEvent = $slotSettings->GetHistory();
                $slotEvent['slotBet'] = $slotEvent['c'];
                $slotEvent['slotLines'] = $lines;
                
                $betline = $slotEvent['slotBet'];
                
                //check irlegal request
                if( $lines <= 0 || $betline <= 0.0001 ) 
                {
                    $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"invalid bet state"}';
                    exit( $response );
                }
                if( $slotEvent['slotEvent'] == 'doSpin' && $slotSettings->GetBalance() < ($lines * $betline) ) 
                {
                    $balance_cash = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                        if(!isset($balance_cash)){
                            $balance_cash = $slotSettings->GetBalance();
                        }
                        $response = 'nomoney=1&balance='. $balance_cash .'&error_type=i&index='.$slotEvent['index'].'&balance_cash='. $balance_cash .'&balance_bonus=0.00&na=s&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&ext_code=SystemError&sver=5&counter='. ((int)$slotEvent['counter'] + 1);
                    exit( $response );
                }
                
                
                $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $betline * $lines, $lines);
                $winType = $_spinSettings[0];
                $_winAvaliableMoney = $_spinSettings[1];
                $allBet = $betline * $lines;

                $slotEvent['slotEvent'] = 'bet';
                $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                $_sum = $allBet / 100 * $slotSettings->GetPercent();
                $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $_sum, $slotEvent['slotEvent']);

                $roundstr = sprintf('%.4f', microtime(TRUE));
                $roundstr = str_replace('.', '', $roundstr);
                $roundstr = '561' . substr($roundstr, 4, 10);
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                
                $Balance = $slotSettings->GetBalance();
                $totalWin = 0;
                $strWinLine = '';
                $wheel_symbol = 2;
                $multiplier = 1;
                $lastReel = null;
                $win_symbol = 0;
                $sa = null;
                $sb = null;
                
                $stack = $slotSettings->GetReelStrips($winType, $betline * $lines);
                if($stack == null){
                    $response = 'unlogged';
                    exit( $response );
                }
                //analyze reel
                $lastReel = explode(',', $stack['reel']);
                $symcounts = array_count_values($lastReel);

                if (array_key_exists($wheel_symbol, $symcounts))
                {
                    $multiplier = $stack['lm_v'];
                }

                foreach ($symcounts as $sym => $s_count)
                {
                    if ($multiplier>1)
                    {
                        if ($s_count >= 2) {
                            $totalWin += $multiplier * $slotSettings->Paytable[$sym][$s_count] * $betline * $lines;
                            $win_symbol = $sym;
                        }
                    }
                    else if ($s_count >= 3)
                    {
                        $totalWin +=  $slotSettings->Paytable[$sym][$s_count-1] * $betline * $lines;
                        $win_symbol = $sym;
                    }
                }
                
                $spinType = 's';
                if( $totalWin > 0) 
                {
                    $spinType = 'c';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                    //make winline str
                    $strWinLineArr = [0, $totalWin];
                    foreach ($lastReel as $_index => $sym)
                    {
                        if ($sym == $win_symbol || $sym == $wheel_symbol)
                        {
                            $strWinLineArr[] = $_index;
                        }
                    }
                    $strWinLine = '&l0=' . implode('~', $strWinLineArr);


                }
                $_obf_totalWin = $totalWin;
                $strLastReel = implode(',', $lastReel);
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 5; $i++){
                    $reelA[$i] = mt_rand(3, 9);
                    $reelB[$i] = mt_rand(3, 9);
                }
                $strReelSa = implode(',', $reelA); 
                $strReelSb = implode(',', $reelB); 
               
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                
                $str_wheelResponse = '';
                if($multiplier > 1){
                    $str_wheelResponse = '&lm_v=0~' . $multiplier . '&lm_m=l~m';
                }
                
                $response = 'tw='. $totalWin . $str_wheelResponse .'&balance='.$Balance .'&index='.$slotEvent['index'].'&balance_cash='.$Balance . '&balance_bonus=0.00&na='.$spinType.$strWinLine.'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=1&c='.$betline.'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=1&s='.$strLastReel.'&w='.$totalWin;
                
                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"LastReel":'.json_encode($lastReel).',"bet":'.$betline.'}}';
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $totalWin, $slotEvent['slotEvent']);
            }
            if($slotEvent['action'] == 'doSpin' || $slotEvent['action'] == 'doCollect'){
                $this->saveGameLog($slotEvent, $response, $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'), $slotSettings);
            }
            $slotSettings->SaveGameData();
            \DB::commit();
            return $response;
        }
        public function saveGameLog($slotEvent, $response_log, $roundId, $slotSettings){
            $game_log = [];
            $game_log['roundId'] = $roundId;
            $response_loges = explode('&', $response_log);
            $response = [];
            foreach( $response_loges as $param ) 
            {
                $_obf_arr = explode('=', $param);
                $response[$_obf_arr[0]] = $_obf_arr[1];
            }

            $request = [];
            foreach( $slotEvent as $index => $value ) 
            {
                if($index != 'slotEvent'){
                    $request[$index] = $value;
                }
            }
            $game_log['request'] = $request;
            $game_log['response'] = $response;
            $game_log['currency'] = 'KRW';
            $game_log['currencySymbol'] = '₩';
            $game_log['configHash'] = '02344a56ed9f75a6ddaab07eb01abc54';

            $str_gamelog = json_encode($game_log);
            $slotSettings->saveGameLog($str_gamelog, $roundId);
        }
    }

}

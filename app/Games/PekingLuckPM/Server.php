<?php 
namespace VanguardLTE\Games\PekingLuckPM
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
            } 
            $slotSettings->SetBet(); 


            if( $slotEvent['slotEvent'] == 'update' ) 
            {
                $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                if(!isset($Balance)){
                    $Balance = $slotSettings->GetBalance();
                }
                $response = 'balance=' . $Balance . '&balance_cash=' . $Balance . '&balance_bonus=0.00&na=s&stime=' . floor(microtime(true) * 1000);
                exit( $response );
            }

            $linesId = [];
            $linesId[0] = [2,2,2,2,2];
            $linesId[1] = [1,1,1,1,1];
            $linesId[2] = [3,3,3,3,3];
            $linesId[3] = [1,2,3,2,1];
            $linesId[4] = [3,2,1,2,3];
            $linesId[5] = [2,1,1,1,2];
            $linesId[6] = [2,3,3,3,2];
            $linesId[7] = [1,1,2,3,3];
            $linesId[8] = [3,3,2,1,1];
            $linesId[9] = [2,3,2,1,2];                
            $linesId[10] = [2,1,2,3,2];
            $linesId[11] = [1,2,2,2,1];
            $linesId[12] = [3,2,2,2,3];
            $linesId[13] = [1,2,1,2,1];
            $linesId[14] = [3,2,3,2,3];
            $linesId[15] = [2,2,1,2,2];
            $linesId[16] = [2,2,3,2,2];
            $linesId[17] = [1,1,3,1,1];
            $linesId[18] = [3,3,1,3,3];
            $linesId[19] = [1,3,3,3,1];
            $orginal_bet = 0.1;
            if( $slotEvent['slotEvent'] == 'doInit' ) 
            { 
                $lastEvent = $slotSettings->GetHistory();
                $_obf_StrResponse = '';
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 25);
                $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Wins', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'Wins_Mask', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'Status', '');
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [6,9,8,4,7,7,3,4,11,11,10,11,6,5,10]);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $strOtherResponse = '';
                $stack = null;
                
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', $lastEvent->serverResponse->Bgt);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Wins', $lastEvent->serverResponse->Wins);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Wins_Mask', $lastEvent->serverResponse->Wins_Mask);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Status', $lastEvent->serverResponse->Status);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', $lastEvent->serverResponse->BonusState);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    if (isset($lastEvent->serverResponse->ReplayGameLogs)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', json_decode(json_encode($lastEvent->serverResponse->ReplayGameLogs), true)); //ReplayLog
                    }
                    if (isset($lastEvent->serverResponse->FreeStacks)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', json_decode(json_encode($lastEvent->serverResponse->FreeStacks), true)); // FreeStack
                        $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                        if($slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') > 0){
                            $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') - 1];
                        }
                    }
                    $bet = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $bet = '40.00';
                }
                $currentReelSet = 0;
                $fsmul = 1;
                $spinType = 's';
                if(isset($stack)){    
                    if($stack['reel_set'] >= 0){
                        $currentReelSet = $stack['reel_set'];
                    }
                    $bgt = $stack['bgt'];
                    $level = $stack['level'];
                    $bw = $stack['bw'];
                    $end = $stack['end'];
                    $bgid = $stack['bgid'];
                    $str_wins_mask = $stack['wins_mask'];
                    $str_wins = $stack['wins'];
                    $str_status = $stack['wins_status'];
                    $fsmul = $stack['fsmul'];
                    $fsmore = $stack['fsmore'];
                    $strWinLine = $stack['win_line'];
                    
                    if($bw == 1){
                        $strOtherResponse = $strOtherResponse .'&bw=1';
                    }
                    if($str_wins != ''){
                        $strOtherResponse = $strOtherResponse . '&wins=' . $slotSettings->GetGameData($slotSettings->slotId . 'Wins') . '&wins_mask=' . $slotSettings->GetGameData($slotSettings->slotId . 'Wins_Mask') . '&status=' . $slotSettings->GetGameData($slotSettings->slotId . 'Status');
                    }
                    
                    if($bgt > 0){
                        $strOtherResponse = $strOtherResponse . '&bgt=' . $bgt;
                        $spinType = 'b';
                        if($end == 1){
                            $spinType = 's';
                        }
                    }
                    if($level > -1){
                        $strOtherResponse = $strOtherResponse . '&level=' . $level;
                    }
                    if($end > -1){
                        $strOtherResponse = $strOtherResponse . '&end=' . $end;
                    }
                    if($bgid > -1){
                        $strOtherResponse = $strOtherResponse . '&bgid=' . $bgid;
                    }
                    if($strWinLine != ''){
                        $arr_lines = explode('&', $strWinLine);
                        for($k = 0; $k < count($arr_lines); $k++){
                            $arr_sub_lines = explode('~', $arr_lines[$k]);
                            $arr_sub_lines[1] = str_replace(',', '', $arr_sub_lines[1]) / $orginal_bet * $bet;
                            $arr_lines[$k] = implode('~', $arr_sub_lines);
                        }
                        $strWinLine = implode('&', $arr_lines);
                        $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                    }
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0 || $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 )
                {
                    $fs = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                        $strOtherResponse = $strOtherResponse . '&fs=' . $fs . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul=' . $fsmul;
                    }else if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0){
                        $strOtherResponse = $strOtherResponse . '&fs_total='. ($fs - 1) .'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=' . $fsmul . '&fsend_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                } 
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));

                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=6,9,8,4,7,7,3,4,11,11,10,11,6,5,10&bgid=0&sps_levels=nff,m&sps_wins=8,12,15,18,28,38,2,3,5,8,10,18&balance='. $Balance .'&sps_wins_mask=nff,nff,nff,nff,nff,nff,m,m,m,m,m,m&cfgs=1712&ver=2&index=1&balance_cash='. $Balance .'&reel_set_size=2&def_sb=13,12,8,10,6&def_sa=6,3,1,8,12&balance_bonus=0.00&na='. $spinType .'&scatters=1~250,10,3,1,0~0,0,0,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&stime=' . floor(microtime(true) * 1000) . $strOtherResponse . '&bgt=28&sa=6,3,1,8,12&sb=13,12,8,10,6&sc='. implode(',', $slotSettings->Bet) .'&defc=40.00&sh=3&wilds=2~10000,4000,500,20,0~2,2,2,2,2&bonuses=0&fsbonus=&c='.$bet.'&sver=5&n_reel_set=0&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;750,125,30,2,0;500,100,25,2,0;400,80,20,0,0;300,75,15,0,0;250,60,15,0,0;200,50,10,0,0;150,40,10,0,0;125,30,5,0,0;100,30,5,0,0;100,25,5,0,0;100,25,5,2,0&l=25&reel_set0=6,9,9,2,2,2,11,9,12,5,9,11,6,11,5,9,11,4,12,10,5,9,8,7,6,6,3,11,11,8,13,10,7,12,4,13,3,11,1,8~3,2,2,2,9,6,13,7,10,10,11,9,13,10,11,10,6,5,1,12,10,12,7,10,5,11,4,12,7,13,3,12,8,11,6,12,13,10,8,10,4,5,13,13,7,10~1,12,13,10,7,7,4,13,12,5,7,9,12,8,13,12,12,10,8,4,5,6,10,13,7,9,11,3,12,5,2,2,2,9,11,13,3,8,6,12,6,4,9,12,11,6,13,8,1~8,4,5,5,12,2,2,2,6,11,12,8,13,8,10,7,13,7,13,6,9,8,7,10,12,1,11,8,5,8,3,5,3,12,3,13,11,4,11,10,11,10,8,1,9~12,9,10,10,4,6,13,1,6,11,13,10,10,7,9,6,13,9,11,3,12,7,11,11,5,8,10,2,2,2,3,3,8,13,9,9,4,5,12,13,9,6,1,8,13,13,6,10,10,12&s='.$lastReelStr.'&reel_set1=6,7,2,2,2,4,11,13,10,11,11,12,1,12,6,5,8,9,7,7,11,5,10,9,4,13,9,8,13,12,3,9,11,3,10,8~4,12,12,10,3,13,10,4,9,13,10,12,8,7,5,8,2,2,2,13,7,1,12,7,11,6,13,10,6,9,13,10,13,10,5,11,3,7,6,13~11,2,2,2,12,3,13,5,8,6,9,13,13,8,12,11,7,11,13,7,4,7,1,4,12,13,10,8,13,10,11,6,12,9,6,8,4,8,3,12,6,5,9,5,10~5,9,4,3,5,7,11,7,8,5,12,12,2,2,2,3,9,3,5,12,11,1,6,6,8,8,13,8,8,11,13,4,10,1,12,13,10,11,8,11,8,10,7,8,10~9,10,8,1,12,5,11,12,10,10,4,13,11,5,5,3,8,6,4,13,1,3,10,7,2,2,2,9,7,3,12,11,11,9,13,13,12,9,10,8,3,6,13,6,9,12,9,6,10,6,10,13,10';
            }
            else if( $slotEvent['slotEvent'] == 'doCollect' || $slotEvent['slotEvent'] == 'doCollectBonus') 
            {
                $Balance = $slotSettings->GetBalance();
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);    
                $response = 'balance=' . $Balance . '&index=' . $slotEvent['index'] . '&balance_cash=' . $Balance . '&balance_bonus=0.00&na=s&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sver=5&counter=' . ((int)$slotEvent['counter'] + 1);
                
                //------------ ReplayLog ---------------                
                $lastEvent = $slotSettings->GetHistory();
                if($lastEvent != NULL){
                    $betline = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $betline = $slotSettings->Bet[0];
                }
                $lines = 25;      
                $allBet = $betline * $lines;
                $totalWin = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if($replayLog && count($replayLog) && $totalWin >= ($allBet * 10)){
                    $current_replayLog["cr"] = $paramData;
                    $current_replayLog["sr"] = $response;
                    array_push($replayLog, $current_replayLog);

                    \VanguardLTE\Jobs\UpdateReplay::dispatch([
                        'user_id' => $userId,
                        'game_id' => $slotSettings->game->original_id,
                        'bet' => $allBet,
                        'brand_id' => config('app.stylename'),
                        'base_bet' => $allBet,
                        'win' => $totalWin,
                        'rtp' => $totalWin / $allBet,
                        'game_logs' => urlencode(json_encode($replayLog))
                    ]);
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []);
                //------------ *** ---------------
            }
            else if( $slotEvent['slotEvent'] == 'doSpin') 
            {
                $lastEvent = $slotSettings->GetHistory();

                $slotEvent['slotBet'] = $slotEvent['c'];
                $slotEvent['slotLines'] = 25;
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    $slotEvent['slotEvent'] = 'freespin';
                }
                $lines = $slotEvent['slotLines'];
                $betline = $slotEvent['slotBet'];
                if( $slotEvent['slotEvent'] == 'doSpin'  || $slotEvent['slotEvent'] == 'freespin') 
                {
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
                    // if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') < $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame')) 
                    // {
                    //     $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"invalid bonus state"}';
                    //     exit( $response );
                    // }
                    if($slotEvent['slotEvent'] == 'respin' || $slotEvent['slotEvent'] == 'freespin'){
                        if ($lastEvent->serverResponse->bet != $betline){
                            $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"invalid Bets"}';
                        exit( $response );
                        }
                    }
                }
                
                $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $betline * $lines, $lines);
                $winType = $_spinSettings[0];
                $_winAvaliableMoney = $_spinSettings[1];
                $allBet = $betline * $lines;

                // $winType = 'bonus';

                $freeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                    $isGeneratedFreeStack = true;
                }
                else
                {
                    $slotEvent['slotEvent'] = 'bet';
                    $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                    $_sum = $allBet / 100 * $slotSettings->GetPercent();
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $_sum, $slotEvent['slotEvent']);
                    $bonusMpl = 1;
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Wins', '');
                    $slotSettings->SetGameData($slotSettings->slotId . 'Wins_Mask', '');
                    $slotSettings->SetGameData($slotSettings->slotId . 'Status', '');
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '561' . substr($roundstr, 4, 10);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $leftFreeGames = 0;

                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []);
                }
                
                $wild = '2';
                $scatter = '1';
                $Balance = $slotSettings->GetBalance();
                
                $totalWin = 0;
                $lineWins = [];
                $lineWinNum = [];
                $winLineCount = 0;
                $str_initReel = '';
                $strWinLine = '';
                $bgt = 0;
                $level = -1;
                $lifes = -1;
                $bw = 0;
                $end = -1;
                $bgid = -1;
                $str_wins_mask = '';
                $str_wins = '';
                $str_status = '';
                $fsmul = 0;
                $fsmore = 0;
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $bgt = $stack['bgt'];
                    $level = $stack['level'];
                    $bw = $stack['bw'];
                    $end = $stack['end'];
                    $bgid = $stack['bgid'];
                    $str_wins_mask = $stack['wins_mask'];
                    $str_wins = $stack['wins'];
                    $str_status = $stack['wins_status'];
                    $fsmul = $stack['fsmul'];
                    $fsmore = $stack['fsmore'];
                    $strWinLine = $stack['win_line'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $stack);  
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $lastReel = explode(',', $stack[0]['reel']);
                    $currentReelSet = $stack[0]['reel_set'];
                    $bgt = $stack[0]['bgt'];
                    $level = $stack[0]['level'];
                    $bw = $stack[0]['bw'];
                    $end = $stack[0]['end'];
                    $bgid = $stack[0]['bgid'];
                    $str_wins_mask = $stack[0]['wins_mask'];
                    $str_wins = $stack[0]['wins'];
                    $str_status = $stack[0]['wins_status'];
                    $fsmul = $stack[0]['fsmul'];
                    $fsmore = $stack[0]['fsmore'];
                    $strWinLine = $stack[0]['win_line'];
                }
                $reels = [];
                $scatterCount = 0;
                $scatterWin = 0;
                $scatterPoses = [];
                for($i = 0; $i < 15; $i++){
                    if($lastReel[$i] == 1){
                        $scatterCount++;
                        array_push($scatterPoses, $i);
                    }
                }
                if($strWinLine != ''){
                    $arr_lines = explode('&', $strWinLine);
                    for($k = 0; $k < count($arr_lines); $k++){
                        $arr_sub_lines = explode('~', $arr_lines[$k]);
                        $arr_sub_lines[1] = str_replace(',', '', $arr_sub_lines[1]) / $orginal_bet * $betline;
                        $totalWin = $totalWin + $arr_sub_lines[1];
                        $arr_lines[$k] = implode('~', $arr_sub_lines);
                    }
                    $strWinLine = implode('&', $arr_lines);
                    if($slotEvent['slotEvent'] == 'freespin' && $fsmul > 1){
                        $totalWin = $totalWin * $fsmul;
                    }
                } 

                if($scatterCount >= 2){
                    $scatterMuls = [0,0,1,3,10,25];
                    $scatterWin = $scatterMuls[$scatterCount] * $betline * $lines;
                    if($fsmul > 1){
                        $scatterWin = $scatterWin * $fsmul;
                    }
                    $totalWin = $totalWin + $scatterWin;
                }
                
                $spinType = 's';
                if( $totalWin > 0) 
                {
                    $spinType = 'c';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }
                $_obf_totalWin = $totalWin;

                $strLastReel = implode(',', $lastReel);
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 5; $i++){
                    $reelA[$i] = mt_rand(5, 11);
                    $reelB[$i] = mt_rand(5, 11);
                }
                $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
               
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                if($slotEvent['slotEvent'] == 'freespin' && $fsmore > 0){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                }
                $strOtherResponse = '';
                $isState = true;
                $isEnd = true;
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);

                if($fsmul < 1){
                    $fsmul = 1;
                }
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $spinType = 's';
                    $isEnd = false;
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                    {
                        $spinType = 'c';
                        $isEnd = true;
                        $strOtherResponse = '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fsmul_total='. $fsmul .'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')/$fsmul). '&fsres_total='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                    else
                    {
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul='. $fsmul .'&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') .'&fswin=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') / $fsmul) . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 's';
                    }
                }else{
                    if($scatterCount >= 3){
                        $strOtherResponse = $strOtherResponse . '&psym=1~' . $scatterWin . '~' . implode(',', $scatterPoses);
                    }
                }
                if($bw == 1 && $end == 0){
                    $isState = false;
                    $spinType = 'b';                  
                }
                if($bw == 1){
                    $strOtherResponse = $strOtherResponse .'&bw=1';
                }
                if($str_wins != ''){
                    $strOtherResponse = $strOtherResponse . '&wins=' . $str_wins . '&wins_mask=' . $str_wins_mask . '&status=' . $str_status;
                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'Wins', $str_wins);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Wins_Mask', $str_wins_mask);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Status', $str_status);
                }
                
                if($currentReelSet >= 0){
                    $strOtherResponse = $strOtherResponse . '&n_reel_set=' . $currentReelSet;
                }
                if($bgt > 0){
                    $strOtherResponse = $strOtherResponse . '&bgt=' . $bgt;
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', $bgt);
                }
                if($level > -1){
                    $strOtherResponse = $strOtherResponse . '&level=' . $level;
                }
                if($end > -1){
                    $strOtherResponse = $strOtherResponse . '&end=' . $end;
                }
                if($bgid > -1){
                    $strOtherResponse = $strOtherResponse . '&bgid=' . $bgid;
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                }
                if($isEnd == true){
                    $strOtherResponse = $strOtherResponse . '&w=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                }else{
                    $strOtherResponse = $strOtherResponse . '&w=' . ($totalWin / $fsmul);
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&c='.$betline.'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&l=25&sh=3&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&s='.$strLastReel;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) && $spinType != 'b') 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }

                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'). ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount'). ',"Wins":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Wins'). '","Wins_Mask":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Wins_Mask'). '","Status":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Status') . '","FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if(($end == 0 || $bw == 1) && $slotEvent['slotEvent'] != 'freespin') 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                }

            }else if( $slotEvent['slotEvent'] == 'doBonus' ){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lines = 20;
                $scatter = 1;
                $wild = 2;
                $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                
                $ind = -1;
                if(isset($slotEvent['ind'])){
                    $ind = $slotEvent['ind'];
                }
                $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                $slotSettings->SetGameData($slotSettings->slotId . 'Ind', $ind);
                $lastReel = $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'); 
                $str_reel = '';
                if($stack['reel'] != ''){
                    $lastReel = explode(',', $stack['reel']);
                    $str_reel = $stack['reel'];
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                }
                $currentReelSet = $stack['reel_set'];
                $bgt = $stack['bgt'];
                $level = $stack['level'];
                $bw = $stack['bw'];
                $end = $stack['end'];
                $bgid = $stack['bgid'];
                $str_wins_mask = $stack['wins_mask'];
                $str_wins = $stack['wins'];
                $str_status = $stack['wins_status'];
                $fsmul = $stack['fsmul'];
                $fsmore = $stack['fsmore'];
                $fsmax = $stack['fsmax'];
                $strWinLine = $stack['win_line'];
                $spinType = 'b';
                $coef = $betline * $lines;
                $isState = false;
                $strOtherResponse = '';

                $arr_wins = explode(',', $str_wins);
                $arr_status = [0,0,0,0,0,0];
                if($ind >= 0){
                    $win_item = $arr_wins[0];
                    $arr_wins[0] = $arr_wins[$ind];
                    $arr_wins[$ind] = $win_item;
                    $arr_status[$ind] = $level;
                    $str_wins = implode(',', $arr_wins);
                    $str_status = implode(',', $arr_status);

                    if($level == 1){
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $win_item);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                    }
                }
                if($end == 1){
                    $spinType = 's';
                    $strOtherResponse = $strOtherResponse . '&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=0.00&fs=1&fsres=0.00';
                }
                
                $strOtherResponse = $strOtherResponse . '&wins='. $str_wins .'&status='. $str_status .'&wins_mask='. $str_wins_mask;
                $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', $bgt);
                $slotSettings->SetGameData($slotSettings->slotId . 'Wins', $str_wins);
                $slotSettings->SetGameData($slotSettings->slotId . 'Wins_Mask', $str_wins_mask);
                $slotSettings->SetGameData($slotSettings->slotId . 'Status', $str_status);
                
                if($currentReelSet >= 0){
                    $strOtherResponse = $strOtherResponse . '&n_reel_set=' . $currentReelSet;
                }
                if($bgt > 0){
                    $strOtherResponse = $strOtherResponse . '&bgt=' . $bgt;
                }
                if($level > -1){
                    $strOtherResponse = $strOtherResponse . '&level=' . $level;
                }
                if($end > -1){
                    $strOtherResponse = $strOtherResponse . '&end=' . $end;
                }
                if($bgid > -1){
                    $strOtherResponse = $strOtherResponse . '&bgid=' . $bgid;
                }
                if($fsmul > 0){
                    $strOtherResponse = $strOtherResponse . '&fsmul=' . $fsmul;
                }
                
                $response = 'balance='. $Balance .'&index='.$slotEvent['index'] .'&balance_cash='. $Balance .'&balance_bonus=0.00&na='. $spinType . $strOtherResponse .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sver=5&counter='. ((int)$slotEvent['counter'] + 1);

                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'). ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount'). ',"Wins":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Wins'). '","Wins_Mask":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Wins_Mask'). '","Status":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Status') . '","FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
            }
            if($slotEvent['action'] == 'doSpin' || $slotEvent['action'] == 'doCollect' || $slotEvent['action'] == 'doBonus' || $slotEvent['action'] == 'doCollectBonus'){
                $this->saveGameLog($slotEvent, $response, $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'), $slotSettings);
            }
            try{
                $slotSettings->SaveGameData();
                \DB::commit();
            }catch (\Exception $e) {
                $slotSettings->InternalError('TicTacTakeDBCommit : ' . $e);
                \DB::rollBack();
                $response = 'unlogged';
            }
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

<?php 
namespace VanguardLTE\Games\_7PiggiesPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 7);
                $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Wins', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'Wins_Mask', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'Status', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'FscSessions', []);
                $slotSettings->SetGameData($slotSettings->slotId . 'FscWinTotal', []);
                $slotSettings->SetGameData($slotSettings->slotId . 'FscTotal', []);
                $slotSettings->SetGameData($slotSettings->slotId . 'FscMuls', []);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [3,4,4,2,5,3,4,4,2,5,3,4,4,2,5]);
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

                    if($lastEvent->serverResponse->FscSessions != ''){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FscSessions', explode(',', $lastEvent->serverResponse->FscSessions));
                    }
                    if($lastEvent->serverResponse->FscWinTotal != ''){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FscWinTotal', explode(',', $lastEvent->serverResponse->FscWinTotal));
                    }
                    if($lastEvent->serverResponse->FscTotal != ''){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FscTotal', explode(',', $lastEvent->serverResponse->FscTotal));
                    }
                    if($lastEvent->serverResponse->FscMuls != ''){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FscMuls', explode(',', $lastEvent->serverResponse->FscMuls));
                    }
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
                    $bet = '150.00';
                }
                $currentReelSet = 0;
                $fsmul = 1;
                $spinType = 's';
                if(isset($stack)){    
                    if($stack['reel_set'] >= 0){
                        $currentReelSet = $stack['reel_set'];
                    }
                    $str_i_pos = $stack['i_pos'];
                    $level = $stack['level'];
                    $bw = $stack['bw'];
                    $end = $stack['end'];
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
                        
                        $arr_wins = explode(',', $slotSettings->GetGameData($slotSettings->slotId . 'Wins'));
                        $arr_wins_mask = explode(',', $slotSettings->GetGameData($slotSettings->slotId . 'Wins_Mask'));
                        $arr_status = explode(',', $slotSettings->GetGameData($slotSettings->slotId . 'Status'));
                        $win_fs = 5;
                        $win_mul = 1;
                        for($k = 0; $k < count($arr_status); $k++){
                            if($arr_status[$k] > 0){
                                if($arr_wins_mask[$k] == 'nff'){
                                    $win_fs += $arr_wins[$k];
                                }else if($arr_wins_mask[$k] == 'm'){
                                    $win_mul += $arr_wins[$k];
                                }
                            }
                        }
                        if($win_fs > 0){
                            $strOtherResponse = $strOtherResponse . '&win_fs=' . $win_fs;
                        }
                        if($win_mul > 0){
                            $strOtherResponse = $strOtherResponse . '&win_mul=' . $win_mul;
                        }
                    }
                    
                    if($str_i_pos != ''){
                        $strOtherResponse = $strOtherResponse . '&i_pos=' . $str_i_pos;
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'Bgt') > 0){
                        $spinType = 'b';
                    }
                    if($level > -1){
                        $strOtherResponse = $strOtherResponse . '&level=' . $level;
                    }
                    if($end > -1){
                        $strOtherResponse = $strOtherResponse . '&end=' . $end;
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
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0 || $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 )
                    {
                        $fs = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                        if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                            $strOtherResponse = $strOtherResponse . '&fs=' . $fs . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul=' . $fsmul;
                        }else if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0){
                            $strOtherResponse = $strOtherResponse . '&fs_total='. ($fs - 1) .'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=' . $fsmul . '&fsend_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        }
                        $fscWinTotal = $slotSettings->GetGameData($slotSettings->slotId . 'FscWinTotal');
                        $fscTotal = $slotSettings->GetGameData($slotSettings->slotId . 'FscTotal');
                        $fscMuls = $slotSettings->GetGameData($slotSettings->slotId . 'FscMuls');
                        if(count($fscWinTotal) > 0){
                            $strOtherResponse = $strOtherResponse . '&fsc_win_total='. implode(',', $fscWinTotal).'&fsc_res_total='. implode(',', $fscWinTotal) .'&fsc_mul_total='. implode(',', $fscMuls) .'&fsc_total=' . implode(',', $fscTotal);
                        }
                    } 
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }
                
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));

                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=3,4,4,2,5,3,4,4,2,5,3,4,4,2,5&balance='. $Balance .'&cfgs=2237&ver=2&index=1&balance_cash='. $Balance .'&reel_set_size=2&def_sb=6,6,6,6,6&def_sa=4,4,4,4,4&balance_bonus=0.00&na='. $spinType .'&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=4,4,4,4,4&sb=6,6,6,6,6&sc='. implode(',', $slotSettings->Bet) .'&defc=250.00&sh=3&wilds=2~1500,400,50,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&n_reel_set='.$currentReelSet.$strOtherResponse.'&&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;150,50,20,0,0;50,15,5,0,0;50,15,5,0,0;15,5,3,0,0;15,5,3,0,0&l=7&reel_set0=7,7,7,7,7,4,4,4,4,4,6,6,6,6,6,6,6,6,3,3,3,3,5,5,5,5,5,1,7,7,7,7,7,7,7,7,4,4,4,4,4,1,6,6,6,6,6,6,6,5,5,5,5,5,1,7,7,7,7,7,7,7,7,3,3,3,3,5,5,5,5,5,1,6,6,6,6,6,6,6,6,3,3,3,3,5,5,5,5,5,2,2,2,2,2~7,7,7,7,7,7,7,1,4,4,4,4,4,6,6,6,6,6,5,5,5,5,5,1,7,7,7,7,7,7,7,7,3,3,3,3,5,5,5,5,5,1,6,6,6,6,6,6,6,6,4,4,4,4,4,7,7,7,7,7,2,2,2,2,2,4,4,4,4,4,1,6,6,6,6,6,6,6,6,3,3,3,3,5,5,5,5,5~7,7,7,7,7,4,4,4,4,6,6,6,3,3,3,5,5,5,5,5,1,7,7,7,7,7,7,7,7,4,4,4,4,4,6,6,6,6,6,6,5,5,5,5,5,1,7,7,7,7,7,7,7,7,3,3,3,3,5,5,5,5,5,1,6,6,6,6,6,6,6,6,4,4,4,4,4,7,7,7,7,7,7,7,7,2,2,2,2,2,2,2,2,4,4,4,4,4,1,6,6,6,6,6,6,6,6,3,3,3,3,5,5,5,5,5~7,7,7,7,7,7,7,7,4,4,4,4,4,6,6,6,6,6,3,3,3,3,5,5,5,5,5,1,7,7,7,7,7,7,7,7,4,4,4,4,4,6,6,6,6,6,6,6,6,5,5,5,5,5,1,7,7,7,7,7,3,3,3,3,5,5,5,5,5,1,6,6,6,6,4,4,4,4,7,7,7,7,7,7,7,7,2,2,2,2,2,2,2,2,4,4,4,4,4,1,6,6,6,6,6,6,6,6,3,3,3,3,5,5,5,5,5~7,7,7,7,4,4,4,4,4,6,6,6,6,6,6,6,6,3,3,3,3,5,5,5,5,5,1,7,7,7,7,7,7,7,7,4,4,4,4,4,6,6,6,6,6,6,6,6,5,5,5,5,5,1,7,7,7,7,7,7,7,7,3,3,3,3,5,5,5,5,5,1,6,6,6,6,6,6,6,6,4,4,4,4,4,1,6,6,6,6,6,6,6,6,3,3,3,3,5,5,5,5,5,2,2,2,2,2&s='.$lastReelStr.'&reel_set1=7,7,7,7,7,4,4,4,4,4,6,6,6,6,6,6,6,6,3,3,3,3,5,5,5,5,5,1,7,7,7,7,7,7,7,7,4,4,4,4,4,1,6,6,6,6,6,6,6,5,5,5,5,5,1,7,7,7,7,7,7,7,7,3,3,3,3,5,5,5,5,5,1,6,6,6,6,6,6,6,6,3,3,3,3,5,5,5,5,5,2,2,2,2,2~7,7,7,7,7,7,7,1,4,4,4,4,4,6,6,6,6,6,5,5,5,5,5,1,7,7,7,7,7,7,7,7,3,3,3,3,5,5,5,5,5,1,6,6,6,6,6,6,6,6,4,4,4,4,4,7,7,7,7,7,2,2,2,2,2,4,4,4,4,4,1,6,6,6,6,6,6,6,6,3,3,3,3,5,5,5,5,5~7,7,7,7,7,4,4,4,4,6,6,6,3,3,3,5,5,5,5,5,1,7,7,7,7,7,7,7,7,4,4,4,4,4,6,6,6,6,6,6,5,5,5,5,5,1,7,7,7,7,7,7,7,7,3,3,3,3,5,5,5,5,5,1,6,6,6,6,6,6,6,6,4,4,4,4,4,7,7,7,7,7,7,7,7,2,2,2,2,2,2,2,2,4,4,4,4,4,1,6,6,6,6,6,6,6,6,3,3,3,3,5,5,5,5,5~7,7,7,7,7,7,7,7,4,4,4,4,4,6,6,6,6,6,3,3,3,3,5,5,5,5,5,1,7,7,7,7,7,7,7,7,4,4,4,4,4,6,6,6,6,6,6,6,6,5,5,5,5,5,1,7,7,7,7,7,3,3,3,3,5,5,5,5,5,1,6,6,6,6,4,4,4,4,7,7,7,7,7,7,7,7,2,2,2,2,2,2,2,2,4,4,4,4,4,1,6,6,6,6,6,6,6,6,3,3,3,3,5,5,5,5,5~7,7,7,7,4,4,4,4,4,6,6,6,6,6,6,6,6,3,3,3,3,5,5,5,5,5,1,7,7,7,7,7,7,7,7,4,4,4,4,4,6,6,6,6,6,6,6,6,5,5,5,5,5,1,7,7,7,7,7,7,7,7,3,3,3,3,5,5,5,5,5,1,6,6,6,6,6,6,6,6,4,4,4,4,4,1,6,6,6,6,6,6,6,6,3,3,3,3,5,5,5,5,5,2,2,2,2,2';
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
                $lines = 7;      
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
                $slotEvent['slotLines'] = 7;
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'FscSessions', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FscWinTotal', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FscTotal', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FscMuls', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.1f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '6074458' . substr($roundstr, 4, 10);
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
                $str_i_pos = '';
                $strWinLine = '';
                $level = -1;
                $bw = 0;
                $end = -1;
                $str_wins_mask = '';
                $str_wins = '';
                $str_status = '';
                $win_mul = 0;
                $win_fs = 0;
                $fsmul = 0;
                $fsmore = 0;
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $str_i_pos = $stack['i_pos'];
                    $level = $stack['level'];
                    $bw = $stack['bw'];
                    $end = $stack['end'];
                    $str_wins_mask = $stack['wins_mask'];
                    $str_wins = $stack['wins'];
                    $str_status = $stack['wins_status'];
                    $win_mul = $stack['win_mul'];
                    $win_fs = $stack['win_fs'];
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
                    $str_i_pos = $stack[0]['i_pos'];
                    $level = $stack[0]['level'];
                    $bw = $stack[0]['bw'];
                    $end = $stack[0]['end'];
                    $str_wins_mask = $stack[0]['wins_mask'];
                    $str_wins = $stack[0]['wins'];
                    $str_status = $stack[0]['wins_status'];
                    $win_mul = $stack[0]['win_mul'];
                    $win_fs = $stack[0]['win_fs'];
                    $fsmul = $stack[0]['fsmul'];
                    $fsmore = $stack[0]['fsmore'];
                    $strWinLine = $stack[0]['win_line'];
                }
                $reels = [];
                $scatterCount = 0;
                for($i = 0; $i < 15; $i++){
                    if($lastReel[$i] == 1){
                        $scatterCount++;
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
                    $reelA[$i] = mt_rand(3, 7);
                    $reelB[$i] = mt_rand(3, 7);
                }
                $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
               
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                
                $strOtherResponse = '';
                $isState = true;
                $isEnd = true;
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);

                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $spinType = 's';
                    $isEnd = false;
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $fscWinTotal = $slotSettings->GetGameData($slotSettings->slotId . 'FscWinTotal');
                    $fscTotal = $slotSettings->GetGameData($slotSettings->slotId . 'FscTotal');
                    $fscMuls = $slotSettings->GetGameData($slotSettings->slotId . 'FscMuls');
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                    {
                        array_push($fscWinTotal, $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin'));
                        array_push($fscTotal, $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames'));
                        array_push($fscMuls, $fsmul);
                        $fscSessions = $slotSettings->GetGameData($slotSettings->slotId . 'FscSessions');
                        $slotSettings->SetGameData($slotSettings->slotId . 'FscWinTotal', $fscWinTotal);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FscTotal', $fscTotal);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FscMuls', $fscMuls);
                        $strOtherResponse = '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fsmul_total='. $fsmul .'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres_total='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        if(count($fscSessions) > 0){
                            $isState = false;
                            $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                            $arr_sessions = explode(',', $fscSessions[0]);
                            $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $arr_sessions[1]);
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $arr_sessions[0]);
                            $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);    

                            array_shift($fscSessions);
                            $slotSettings->SetGameData($slotSettings->slotId . 'FscSessions', $fscSessions);
                            $strOtherResponse = $strOtherResponse . '&fsmul='. $fsmul .'&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                            $spinType = 's';
                        }else{
                            $spinType = 'c';
                        }
                    }
                    else
                    {
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul='. $fsmul .'&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') .'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 's';
                    }
                    $fscWinTotal = $slotSettings->GetGameData($slotSettings->slotId . 'FscWinTotal');
                    $fscTotal = $slotSettings->GetGameData($slotSettings->slotId . 'FscTotal');
                    $fscMuls = $slotSettings->GetGameData($slotSettings->slotId . 'FscMuls');
                    if(count($fscWinTotal) > 0){
                        $strOtherResponse = $strOtherResponse . '&fsc_win_total='. implode(',', $fscWinTotal).'&fsc_res_total='. implode(',', $fscWinTotal) .'&fsc_mul_total='. implode(',', $fscMuls) .'&fsc_total=' . implode(',', $fscTotal);
                    }
                }
                if($bw == 1 && $end == 0){
                    $isState = false;
                    $spinType = 'b';            
                }
                if($bw == 1){
                    $strOtherResponse = $strOtherResponse .'&bw=1';
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 11);
                }
                if($str_wins != ''){
                    $strOtherResponse = $strOtherResponse . '&wins=' . $str_wins . '&wins_mask=' . $str_wins_mask . '&status=' . $str_status;
                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'Wins', $str_wins);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Wins_Mask', $str_wins_mask);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Status', $str_status);
                }
                
                if($str_i_pos != ''){
                    $strOtherResponse = $strOtherResponse . '&i_pos=' . $str_i_pos;
                }
                if($currentReelSet >= 0){
                    $strOtherResponse = $strOtherResponse . '&n_reel_set=' . $currentReelSet;
                }
                if($level > -1){
                    $strOtherResponse = $strOtherResponse . '&level=' . $level;
                }
                if($end > -1){
                    $strOtherResponse = $strOtherResponse . '&end=' . $end;
                }
                if($win_mul > 0){
                    $strOtherResponse = $strOtherResponse . '&win_mul=' . $win_mul;
                }                
                if($win_fs > 0){
                    $strOtherResponse = $strOtherResponse . '&win_fs=' . $win_fs;
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                }
                if($fsmul < 1){
                    $fsmul = 1;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&c='.$betline.'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&l=20&sh=3&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&s='.$strLastReel.'&w='.($totalWin / $fsmul);
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt')  . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'). ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"FscSessions":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'FscSessions')) . '","FscWinTotal":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'FscWinTotal')) . '","FscTotal":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'FscTotal')). '","FscMuls":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'FscMuls')). '","Wins":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Wins'). '","Wins_Mask":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Wins_Mask'). '","Status":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Status') . '","FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
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
                $lines = 7;
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
                $str_i_pos = $stack['i_pos'];
                $level = $stack['level'];
                $bw = $stack['bw'];
                $end = $stack['end'];
                $str_wins_mask = $stack['wins_mask'];
                $str_wins = $stack['wins'];
                $str_status = $stack['wins_status'];
                $fsmul = $stack['fsmul'];
                $fsmore = $stack['fsmore'];
                $fsmax = $stack['fsmax'];
                $strWinLine = $stack['win_line'];
                $totalWin = 0;
                $spinType = 'b';
                $isState = false;
                $strOtherResponse = '';
                $win_mul = 1;
                $win_fs = 5;
                $arr_wins = explode(',', $slotSettings->GetGameData($slotSettings->slotId . 'Wins'));
                $arr_wins_mask = explode(',', $slotSettings->GetGameData($slotSettings->slotId . 'Wins_Mask'));
                $arr_status = explode(',', $slotSettings->GetGameData($slotSettings->slotId . 'Status'));
                
                if($ind >= 0){
                    $arr_i_pos = explode(',', $str_i_pos);
                    $index = 0;
                    for($k = 0; $k < count($arr_i_pos); $k++){
                        if($arr_i_pos[$k] == $ind){
                            $index = $k;
                            break;       
                        }
                    }
                    $arr_status[$index] = $level;
                    $win_fs = 5;
                    $win_mul = 1;
                    for($k = 0; $k < count($arr_status); $k++){
                        if($arr_status[$k] > 0){
                            if($arr_wins_mask[$k] == 'nff'){
                                $win_fs += $arr_wins[$k];
                            }else if($arr_wins_mask[$k] == 'm'){
                                $win_mul += $arr_wins[$k];
                            }
                        }
                    }
                }
                
                $str_wins = implode(',', $arr_wins);
                $str_wins_mask = implode(',', $arr_wins_mask);
                $str_status = implode(',', $arr_status);
                if($end == 1 && $win_fs > 0){
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                    {
                        $fscSessions = $slotSettings->GetGameData($slotSettings->slotId . 'FscSessions');
                        array_push($fscSessions, $win_fs . ',' . $win_mul);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FscSessions', $fscSessions);
                        if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') >= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1){
                            $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                            $arr_sessions = explode(',', $fscSessions[0]);
                            $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $arr_sessions[1]);
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $arr_sessions[0]);
                            $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);   
                            array_shift($fscSessions);
                            $slotSettings->SetGameData($slotSettings->slotId . 'FscSessions', $fscSessions); 
                        }
                    }else{
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $win_mul);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $win_fs);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);   
                    }
                    $strOtherResponse = $strOtherResponse . '&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fs='.$slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                    $spinType = 's';
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);

                    $fscWinTotal = $slotSettings->GetGameData($slotSettings->slotId . 'FscWinTotal');
                    $fscTotal = $slotSettings->GetGameData($slotSettings->slotId . 'FscTotal');
                    $fscMuls = $slotSettings->GetGameData($slotSettings->slotId . 'FscMuls');
                    if(count($fscWinTotal) > 0){
                        $strOtherResponse = $strOtherResponse . '&fsc_win_total='. implode(',', $fscWinTotal).'&fsc_res_total='. implode(',', $fscWinTotal) .'&fsc_mul_total='. implode(',', $fscMuls) .'&fsc_total=' . implode(',', $fscTotal);
                    }
                }
                $strOtherResponse = $strOtherResponse . '&wins='. $str_wins .'&status='. $str_status .'&wins_mask='. $str_wins_mask;
                

                $slotSettings->SetGameData($slotSettings->slotId . 'Wins', $str_wins);
                $slotSettings->SetGameData($slotSettings->slotId . 'Wins_Mask', $str_wins_mask);
                $slotSettings->SetGameData($slotSettings->slotId . 'Status', $str_status);
                
                if($str_i_pos != ''){
                    $strOtherResponse = $strOtherResponse . '&i_pos=' . $str_i_pos;
                }
                if($currentReelSet >= 0){
                    $strOtherResponse = $strOtherResponse . '&n_reel_set=' . $currentReelSet;
                }
                if($level > -1){
                    $strOtherResponse = $strOtherResponse . '&level=' . $level;
                }
                if($end > -1){
                    $strOtherResponse = $strOtherResponse . '&end=' . $end;
                    if($end == 1){
                        $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    }
                }
                if($win_fs > 0){
                    $strOtherResponse = $strOtherResponse . '&win_fs=' . $win_fs;
                }
                if($win_mul > 0){
                    $strOtherResponse = $strOtherResponse . '&win_mul=' . $win_mul;
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"Ind":' . $slotSettings->GetGameData($slotSettings->slotId . 'Ind') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'). ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"FscSessions":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'FscSessions')) . '","FscWinTotal":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'FscWinTotal')) . '","FscTotal":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'FscTotal')). '","FscMuls":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'FscMuls')). '","Wins":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Wins'). '","Wins_Mask":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Wins_Mask'). '","Status":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Status') . '","FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
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

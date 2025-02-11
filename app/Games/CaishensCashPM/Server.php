<?php 
namespace VanguardLTE\Games\CaishensCashPM
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
            $original_bet = 0.1;
            if( $slotEvent['slotEvent'] == 'doInit' ) 
            { 
                $lastEvent = $slotSettings->GetHistory();
                $_obf_StrResponse = '';
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 25);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [6,11,13,14,8,12,7,10,4,3,9,4,5,4,3]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks

                $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Wins', []);
                $slotSettings->SetGameData($slotSettings->slotId . 'Status', []);
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $strOtherResponse = '';
                $currentReelSet = 0;
                $stack = null;
                $strWinLine = '';
                $winMoney = 0;
                
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
                                        
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', $lastEvent->serverResponse->Bgt);
                    if($lastEvent->serverResponse->Wins != ''){
                        $slotSettings->SetGameData($slotSettings->slotId . 'Wins', explode(',', $lastEvent->serverResponse->Wins));
                    }
                    if($lastEvent->serverResponse->Status != ''){
                        $slotSettings->SetGameData($slotSettings->slotId . 'Status', explode(',', $lastEvent->serverResponse->Status));
                    }

                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    if (isset($lastEvent->serverResponse->ReplayGameLogs)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', json_decode(json_encode($lastEvent->serverResponse->ReplayGameLogs), true)); //ReplayLog
                    }
                    if (isset($lastEvent->serverResponse->TumbAndFreeStacks)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', json_decode(json_encode($lastEvent->serverResponse->TumbAndFreeStacks), true)); // FreeStack

                        $FreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                        if($slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') > 0){
                            $stack = $FreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') -1];
                        }
                    }
                    $bet = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $bet = '40.00';
                }
                $spinType = 's';
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                $bonusType = '';
                if(isset($stack)){
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t']; 
                    $mo_tv = $stack['mo_tv'];
                    $mo_c = $stack['mo_c'];
                    $mo_s = $stack['mo_s'];
                    $mo_iv = $stack['mo_iv'];                 
                    $bgid = $stack['bgid'];
                    $bgt = $stack['bgt'];
                    $bw = $stack['bw'];
                    $wp = $stack['wp'];
                    $end = $stack['end'];
                    $level = $stack['level'];
                    $lifes = $stack['lifes'];
                    $str_wins = $stack['wins'];
                    $str_wins_mask = $stack['wins_mask'];
                    $str_status = $stack['wins_status'];
                    $str_bg_i = $stack['bg_i'];
                    $str_bg_i_mask = $stack['bg_i_mask'];
                    $str_win_line = $stack['win_line'];
                    $rsb_m = $stack['rsb_m'];
                    $rsb_c = $stack['rsb_c'];
                    $str_rsb_s = $stack['rsb_s'];
                    $str_rs_s = $stack['rs_s'];
                    $fsmore = $stack['fsmore'];
                    $fsmax = $stack['fsmax'];

                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0 && $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0){
                        $strOtherResponse = $strOtherResponse . '&fs_total='.($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 1).'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsend_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                    if($str_mo != ''){
                        $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                    }else if($lastEvent->serverResponse->Mo != ''){
                        $strOtherResponse = $strOtherResponse . '&mo=' . $lastEvent->serverResponse->Mo . '&mo_t=' . $lastEvent->serverResponse->MoT;
                    }
                    if($mo_s >= 0){
                        $strOtherResponse = $strOtherResponse . '&mo_s=' . $mo_s;
                    }
                    if($mo_c >= 0){
                        $strOtherResponse = $strOtherResponse . '&mo_c=' . $mo_c;
                    }
                    if($mo_iv > 0){
                        $strOtherResponse = $strOtherResponse . '&mo_iv=' . $mo_iv;
                    }
                    if($mo_tv > 0){
                        $strOtherResponse = $strOtherResponse . '&mo_tv=' . $mo_tv;
                    }
                    if($bgid >= 0){
                        $strOtherResponse = $strOtherResponse . '&bgid=' . $bgid;
                    }
                    $coef = 0;
                    if($bgt > 0){
                        if($bgt == 31){
                            $coef = $bet;
                            if($rsb_m > 0){
                                $strOtherResponse = $strOtherResponse . '&rsb_m=' . $rsb_m . '&rsb_c=' . $rsb_c;
                            }
                            if($str_rsb_s != ''){
                                $strOtherResponse = $strOtherResponse . '&rsb_s=' . $str_rsb_s;
                            }
                            if($str_rs_s != ''){
                                $strOtherResponse = $strOtherResponse . '&rs_s=' . $str_rs_s;
                            }
                        }else{
                            $coef = $bet * 25;
                        }
                        $strOtherResponse = $strOtherResponse . '&bgt=' . $bgt . '&coef=' . $coef;
                        if($end == 0){
                            $spinType = 'b';
                        }
                    }
                    if($bw > 0){
                        $strOtherResponse = $strOtherResponse . '&bw=' . $bw;
                    }
                    if($wp >= 0){
                        $strOtherResponse = $strOtherResponse . '&wp=' . $wp . '&rw=' . ($wp * $coef);
                    }
                    if($end >= 0){
                        $strOtherResponse = $strOtherResponse . '&end=' . $end;
                    }
                    if($level >= 0){
                        $strOtherResponse = $strOtherResponse . '&level=' . $level;
                    }
                    if($lifes >= 0){
                        $strOtherResponse = $strOtherResponse . '&lifes=' . $lifes;
                    }
                    if($str_wins != ''){                    
                        $arr_wins = $slotSettings->GetGameData($slotSettings->slotId . 'Wins');
                        $arr_status = $slotSettings->GetGameData($slotSettings->slotId . 'Status');
                        $arr_wins_mask = [];
                        if($bgt == 21){
                            $old_wins_mask = explode(',', $str_wins_mask);
                            if($arr_status[1] == 1){
                                $arr_wins_mask[0] = $old_wins_mask[1];
                                $arr_wins_mask[1] = $old_wins_mask[0];
                            }else{
                                $arr_wins_mask[0] = $old_wins_mask[0];
                                $arr_wins_mask[1] = $old_wins_mask[1];
                            }      
                            if($end == 1){
                                $bonusType = $old_wins_mask[0];
                            }
                        }else if($bgt == 15){                            
                            for($k=0; $k<12; $k++){
                                if($arr_status[$k] > 0){
                                    $arr_wins_mask[$k] = 'pw';
                                }else{
                                    $arr_wins_mask[$k] = 'h';
                                }
                            }
                        }
                        $strOtherResponse = $strOtherResponse . '&wins=' . implode(',', $arr_wins) . '&status=' . implode(',', $arr_status) . '&wins_mask=' . implode(',', $arr_wins_mask);
                    }
                    if($str_bg_i != ''){
                        $strOtherResponse = $strOtherResponse . '&bg_i=' . $str_bg_i . '&bg_i_mask=' . $str_bg_i_mask;
                    }else if($bgt == 15){
                        $strOtherResponse = $strOtherResponse . '&bg_i=1000,100,50,25&bg_i_mask=pw,pw,pw,pw';
                    }
                    if($str_win_line != ''){
                        $arr_lines = explode('&', $str_win_line);
                        for($k = 0; $k < count($arr_lines); $k++){
                            $arr_sub_lines = explode('~', $arr_lines[$k]);
                            $arr_sub_lines[1] = str_replace(',', '', $arr_sub_lines[1]) / $original_bet * $bet;
                            $arr_lines[$k] = implode('~', $arr_sub_lines);
                        }
                        $str_win_line = implode('&', $arr_lines);
                        $strOtherResponse = $strOtherResponse . '&' . $str_win_line;
                    }
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }
                if($bonusType == 'rsb'){
                    $spinType = 'b';
                }else{
                    if( ($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)) 
                    {
                        $strOtherResponse = $strOtherResponse . '&fs=' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&w=0.00&fsmul=1';
                    }
                }
                $Balance = $slotSettings->GetBalance();  
                $response = 'def_s=6,11,13,14,8,12,7,10,4,3,9,4,5,4,3&balance='. $Balance .'&cfgs=2472&ver=2&mo_s=14&index=1&balance_cash='. $Balance .'&reel_set_size=2&def_sb=2,7,5,3,5&mo_v=8,18,28,38,58,68,78,88,118,138,188,238,288,588,888,1888&def_sa=6,2,7,3,8&bonusInit=[{bgid:1,bgt:15,bg_i:"1000,100,50,25",bg_i_mask:"pw,pw,pw,pw"}]&balance_bonus=0.00&na='. $spinType .'&scatters=&gmb=0,0,0&rt=d&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime='. floor(microtime(true) * 1000) .'&sa=6,2,7,3,8&sb=2,7,5,3,5&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=40.00&sh=3&wilds=2~0,0,0,0,0~1,1,1,1,1;15~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='. $bet .'&sver=5&n_reel_set=0&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;150,50,30,0,0;100,30,25,0,0;75,30,20,0,0;60,25,15,0,0;50,20,15,0,0;25,15,12,0,0;25,15,12,0,0;20,12,8,0,0;20,12,8,0,0;20,12,8,0,0;20,12,8,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0&l=25&reel_set0=11,8,8,3,9,5,7,5,3,3,3,4,10,6,3,4,4,8,8,10,6,11,7,11,6,14,14,14,10,6,3,11,7,12,6,13,4,4,4~14,14,14,11,3,2,11,5,10,13,3,3,13,9,3,3,13,9,7,5,13,6,13,2,3,4,4,6,9,2,12,8~3,13,9,7,9,8,14,14,4,13,12,12,5,10,12,13,8,6,2,4,4,4,5,5,12,5,11,5,2,11,11,10,6,3,12,7,8,8~5,11,4,14,14,10,7,13,3,14,14,13,13,8,4,5,5,13,4,9,5,11,5,10,9,4,10,2,12,9,9,6,8,9,2,10~8,6,6,9,4,7,9,9,6,9,8,14,14,9,10,5,13,4,9,3,11,10,5,9,5,10,9,11,5,14,14,3,5,9,4,9,3,11,3,13,4,6,6,14,14,12&s='.$lastReelStr.'&t=243&reel_set1=3,7,3,3,3,6,3,5,4,7,7,4,5,5,3,3,5,5,3,6,6,7,3,4,4,4,4,7,6,3,4,6,4,3,5,6,4,4,4,3,7,4,4,7,6,5,5,5,3~5,7,4,6,5,5,3,6,6,3,3,6,6,4,6,3,15,15,4,7,6,6,6,5,5,5,4,6,7,5,6,4,4,3,7,4,4,3,7,3~4,6,5,5,5,7,7,7,3,3,5,4,5,15,15,4,5,7,3,7,4,4,5,5,3,7,4,3,3,7,3,7,7,5,3,7,7,7,6,7,6~6,6,4,4,4,4,7,5,6,4,6,3,15,15,6,6,5,5,5,4,6,4,7,3,7,4,6,4,4,3,4,7,4,7,3,4,5,6,7,6,4,5~5,4,3,3,3,6,6,5,7,6,6,3,4,5,5,5,6,3,7,7,6,6,6,5,6,4,5,4,3,6,5,7,7,7,7,6,6,6,4';
            }
            else if( $slotEvent['slotEvent'] == 'doCollect' || $slotEvent['slotEvent'] == 'doCollectBonus') 
            {
                $Balance = $slotSettings->GetBalance();
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);    
                $response = 'balance=' . $Balance . '&index=' . $slotEvent['index'] . '&balance_cash=' . $Balance . '&balance_bonus=0.00&na=s&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&na=s&sver=5&counter=' . ((int)$slotEvent['counter'] + 1);
                
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
            else if( $slotEvent['slotEvent'] == 'doSpin' ) 
            {
                $lastEvent = $slotSettings->GetHistory();
                $slotEvent['slotBet'] = $slotEvent['c'];
                $slotEvent['slotLines'] = 25;
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    $slotEvent['slotEvent'] = 'freespin';
                }
                $lines = $slotEvent['slotLines'];
                $betline = $slotEvent['slotBet'];
                if( $slotEvent['slotEvent'] == 'doSpin' || $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    if( $lines <= 0 || $betline <= 0.0001 ) 
                    {
                        $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"invalid bet state"}';
                        exit( $response );
                    }
                    if( $slotEvent['slotEvent'] == 'doSpin' && $slotSettings->GetBalance() < ($lines * $betline)  && $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') == 0) 
                    {
                        $balance_cash = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                        if(!isset($balance_cash)){
                            $balance_cash = $slotSettings->GetBalance();
                        }
                        $response = 'nomoney=1&balance='. $balance_cash .'&error_type=i&index='.$slotEvent['index'].'&balance_cash='. $balance_cash .'&balance_bonus=0.00&na=s&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&ext_code=SystemError&sver=5&counter='. ((int)$slotEvent['counter'] + 1);
                        exit( $response );
                    }
                    if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1  < $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame')) && $slotEvent['slotEvent'] == 'freespin' ) 
                    {
                        $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"invalid bonus state"}';
                        exit( $response );
                    }
                    if($slotEvent['slotEvent'] == 'freespin'){
                        if ($lastEvent->serverResponse->bet != $betline){
                            $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"invalid Bets"}';
                        exit( $response );
                        }
                    }
                }

                $allBet = $betline * $lines;
                $tumbAndFreeStacks = []; 
                $isGeneratedFreeStack = false;
                $slotSettings->SetGameData($slotSettings->slotId . 'Wins', []);
                $slotSettings->SetGameData($slotSettings->slotId . 'Status', []);

                $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $betline * $lines, $lines);
                $winType = $_spinSettings[0];
                
                if($slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                }
                else
                {
                    $slotEvent['slotEvent'] = 'bet';
                    $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                    $_sum = $allBet / 100 * $slotSettings->GetPercent();
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $_sum, $slotEvent['slotEvent']);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.1f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '57409' . substr($roundstr, 2, 9);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $leftFreeGames = 0;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                }
                
                $wild = '2';
                $scatter = '1';
                $Balance = $slotSettings->GetBalance();
                $totalWin = 0;
                $bonusMpl = 1;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $currentReelSet = 0;
                $str_mo = '';
                $str_mo_t = '';
                $mo_tv = 0;
                $mo_s = -1;
                $mo_c = -1;
                $mo_iv = 0;
                $bgid = -1;
                $bgt = 0;
                $bw = 0;
                $wp = -1;
                $end = -1;
                $level = -1;
                $lifes = -1;
                $str_wins = '';
                $str_wins_mask = '';
                $str_status = '';
                $str_bg_i_mask = '';
                $str_bg_i = '';
                $str_win_line = '';
                $rsb_m = 0;
                $rsb_c = 0;
                $str_rsb_s = '';
                $str_rs_s = '';
                $fsmore = 0;
                $fsmax = 0;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t']; 
                    $mo_tv = $stack['mo_tv'];
                    $mo_c = $stack['mo_c'];
                    $mo_s = $stack['mo_s'];
                    $mo_iv = $stack['mo_iv'];                 
                    $bgid = $stack['bgid'];
                    $bgt = $stack['bgt'];
                    $bw = $stack['bw'];
                    $wp = $stack['wp'];
                    $end = $stack['end'];
                    $level = $stack['level'];
                    $lifes = $stack['lifes'];
                    $str_wins = $stack['wins'];
                    $str_wins_mask = $stack['wins_mask'];
                    $str_status = $stack['wins_status'];
                    $str_bg_i = $stack['bg_i'];
                    $str_bg_i_mask = $stack['bg_i_mask'];
                    $str_win_line = $stack['win_line'];
                    $rsb_m = $stack['rsb_m'];
                    $rsb_c = $stack['rsb_c'];
                    $str_rsb_s = $stack['rsb_s'];
                    $str_rs_s = $stack['rs_s'];
                    $fsmore = $stack['fsmore'];
                    $fsmax = $stack['fsmax'];
                }else{
                    // $winType = 'bonus';
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $lastReel = explode(',', $stack[0]['reel']);
                    $str_mo = $stack[0]['mo'];
                    $str_mo_t = $stack[0]['mo_t']; 
                    $mo_tv = $stack[0]['mo_tv'];
                    $mo_c = $stack[0]['mo_c'];
                    $mo_s = $stack[0]['mo_s'];
                    $mo_iv = $stack[0]['mo_iv'];                 
                    $bgid = $stack[0]['bgid'];
                    $bgt = $stack[0]['bgt'];
                    $bw = $stack[0]['bw'];
                    $wp = $stack[0]['wp'];
                    $end = $stack[0]['end'];
                    $level = $stack[0]['level'];
                    $lifes = $stack[0]['lifes'];
                    $str_wins = $stack[0]['wins'];
                    $str_wins_mask = $stack[0]['wins_mask'];
                    $str_status = $stack[0]['wins_status'];
                    $str_bg_i = $stack[0]['bg_i'];
                    $str_bg_i_mask = $stack[0]['bg_i_mask'];
                    $str_win_line = $stack[0]['win_line'];
                    $rsb_m = $stack[0]['rsb_m'];
                    $rsb_c = $stack[0]['rsb_c'];
                    $str_rsb_s = $stack[0]['rsb_s'];
                    $str_rs_s = $stack[0]['rs_s'];
                    $fsmore = $stack[0]['fsmore'];
                    $fsmax = $stack[0]['fsmax'];
                }
                $reels = [];
                $scatterCount = 0;
                if($str_win_line != ''){
                    $arr_lines = explode('&', $str_win_line);
                    for($k = 0; $k < count($arr_lines); $k++){
                        $arr_sub_lines = explode('~', $arr_lines[$k]);
                        $arr_sub_lines[1] = str_replace(',', '', $arr_sub_lines[1]) / $original_bet * $betline;
                        $totalWin = $totalWin + $arr_sub_lines[1];
                        $arr_lines[$k] = implode('~', $arr_sub_lines);
                    }
                    $str_win_line = implode('&', $arr_lines);
                } 
                if($slotEvent['slotEvent'] == 'freespin' && $mo_tv > 0){
                    $totalWin += $mo_tv * $betline;
                }
                $spinType = 's';
                if( $totalWin > 0) 
                {
                    $spinType = 'c';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }
                $_obf_totalWin = $totalWin;
                $isState = true;
                // if($scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin'){
                //     $freespins = [0,0,0,10,12,15];
                //     $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespins[$scatterCount]);
                //     $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                //     $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                // }
                if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                }
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 5; $i++){
                    $reelA[$i] = mt_rand(4, 8);
                    $reelB[$i] = mt_rand(4, 8);
                }
                $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
                $strLastReel = implode(',', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $strOtherResponse = '';                
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $spinType = 's';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $isEnd = false;
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                    {
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsend_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 'c';
                    }
                    else
                    {
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 's';
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                }
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                }
                if($mo_s >= 0){
                    $strOtherResponse = $strOtherResponse . '&mo_s=' . $mo_s;
                }
                if($mo_c >= 0){
                    $strOtherResponse = $strOtherResponse . '&mo_c=' . $mo_c;
                }
                if($mo_tv > 0){
                    $strOtherResponse = $strOtherResponse . '&mo_tv=' . $mo_tv;
                    if($slotEvent['slotEvent'] == 'freespin'){
                        $strOtherResponse = $strOtherResponse . '&mo_tw=' . ($mo_tv * $betline);
                    }
                }
                if($mo_iv > 0){
                    $strOtherResponse = $strOtherResponse . '&mo_iv=' . $mo_iv;
                }
                if($bgid >= 0){
                    $strOtherResponse = $strOtherResponse . '&bgid=' . $bgid;
                }
                if($bgt > 0){
                    $strOtherResponse = $strOtherResponse . '&bgt=' . $bgt;
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', $bgt);
                }
                if($bw > 0){
                    $strOtherResponse = $strOtherResponse . '&bw=' . $bw;
                    if($end == 0){
                        $spinType = 'b';
                        $isState = false;
                    }
                }
                if($wp >= 0){
                    $strOtherResponse = $strOtherResponse . '&wp=' . $wp . '&rw=0';
                }
                if($end >= 0){
                    $strOtherResponse = $strOtherResponse . '&end=' . $end;
                }
                if($level >= 0){
                    $strOtherResponse = $strOtherResponse . '&level=' . $level;
                }
                if($lifes >= 0){
                    $strOtherResponse = $strOtherResponse . '&lifes=' . $lifes;
                }
                if($str_wins != ''){                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'Wins', explode(',', $str_wins));
                    $slotSettings->SetGameData($slotSettings->slotId . 'Status', explode(',', $str_status));
                    $strOtherResponse = $strOtherResponse . '&wins=' . $str_wins . '&status=' . $str_status . '&wins_mask=' . $str_wins_mask;
                }
                if($str_bg_i != ''){
                    $strOtherResponse = $strOtherResponse . '&bg_i=' . $str_bg_i . '&bg_i_mask=' . $str_bg_i_mask;
                }
                if($str_win_line != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $str_win_line;
                }


                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse  .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&n_reel_set='. $currentReelSet .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&c='.$betline .'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=18&w='.$totalWin.'&s=' . $strLastReel;
                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 && $bw != 1) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if( $slotEvent['slotEvent'] != 'freespin' && $bw == 1) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"Wins":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'Wins'))  . '","Status":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'Status')). '","Mo":"' . $str_mo. '","MoT":"' . $str_mo_t . '","TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
            }else if( $slotEvent['slotEvent'] == 'doBonus' ){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lines = 25;
                $lastReel = $lastEvent->serverResponse->LastReel; 
                $Balance =  $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                $ind = -1;
                if(isset($slotEvent['ind'])){
                    $ind = $slotEvent['ind'];
                }
                $isState = false;
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
                $str_mo = $stack['mo'];
                $str_mo_t = $stack['mo_t']; 
                $mo_tv = $stack['mo_tv'];
                $mo_c = $stack['mo_c'];
                $mo_s = $stack['mo_s'];
                $mo_iv = $stack['mo_iv'];                 
                $bgid = $stack['bgid'];
                $bgt = $stack['bgt'];
                $bw = $stack['bw'];
                $wp = $stack['wp'];
                $end = $stack['end'];
                $level = $stack['level'];
                $lifes = $stack['lifes'];
                $str_wins = $stack['wins'];
                $str_wins_mask = $stack['wins_mask'];
                $str_status = $stack['wins_status'];
                $str_bg_i = $stack['bg_i'];
                $str_bg_i_mask = $stack['bg_i_mask'];
                $str_win_line = $stack['win_line'];
                $rsb_m = $stack['rsb_m'];
                $rsb_c = $stack['rsb_c'];
                $str_rsb_s = $stack['rsb_s'];
                $str_rs_s = $stack['rs_s'];
                $fsmore = $stack['fsmore'];
                $fsmax = $stack['fsmax'];
                $arr_wins = explode(',', $str_wins);
                $arr_status = explode(',', $str_status);
                $arr_wins_mask = explode(',', $str_wins_mask);
                $isState = false;
                $spinType = 'b';
                $coef = $betline * $lines;
                $totalWin = 0;
                $bonusType = '';
                $strOtherResponse = '';
                $new_wins = [];
                $new_status = [];
                if($bgt == 21){
                    $new_wins = [1,1];
                    $new_status = [0,0];
                    $new_status[$ind] = 1;
                    $new_wins_mask = [];
                    $bonusType = $arr_wins_mask[0];
                    if($ind == 1){
                        $new_wins_mask[0] = $arr_wins_mask[1];
                        $new_wins_mask[1] = $arr_wins_mask[0];
                    }else{
                        $new_wins_mask[0] = $arr_wins_mask[0];
                        $new_wins_mask[1] = $arr_wins_mask[1];
                    }
                    if($bonusType == 'nff'){
                        $isState = false;
                        $spinType = 's';
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $arr_wins[0]);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                    }else{
                        $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 31);
                    }
                }else if($bgt == 15){
                    $new_wins = $slotSettings->GetGameData($slotSettings->slotId . 'Wins');
                    $new_status = $slotSettings->GetGameData($slotSettings->slotId . 'Status');
                    $new_wins_mask = [];
                    if($ind > -1){
                        $new_wins[$ind] = $arr_wins[$level - 1];
                        $new_status[$ind] = $arr_status[$level - 1];
                    }
                    for($k = 0; $k < 12; $k++){
                        if($new_status[$k] > 0){
                            $new_wins_mask[$k] = 'pw';
                        }else{
                            $new_wins_mask[$k] = 'h';
                        }
                    }
                }else if($bgt == 31){
                    $coef = $betline;
                }
                
                if($end == 1){
                    if($bgt == 31 || $bgt ==15){
                        $totalWin = $wp * $coef;
                        if($totalWin > 0){
                            $spinType = 'cb';
                            $slotSettings->SetBalance($totalWin);
                            $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                            $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                            $isState = true;
                        }
                    }
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                        $spinType = 's';
                        $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                        $isEnd = false;
                        if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                        {
                            $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsend_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                            $spinType = 'c';
                        }
                        else
                        {
                            $isState = false;
                            $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                            $spinType = 's';
                        }
                    }
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }
                
                $slotSettings->SetGameData($slotSettings->slotId . 'Wins', $new_wins);
                $slotSettings->SetGameData($slotSettings->slotId . 'Status', $new_status);

                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                }else{
                    $str_mo = $lastEvent->serverResponse->Mo; 
                    $str_mo_t = $lastEvent->serverResponse->MoT; 
                }
                if($mo_s >= 0){
                    $strOtherResponse = $strOtherResponse . '&mo_s=' . $mo_s;
                }
                if($mo_c >= 0){
                    $strOtherResponse = $strOtherResponse . '&mo_c=' . $mo_c;
                }
                if($mo_iv > 0){
                    $strOtherResponse = $strOtherResponse . '&mo_iv=' . $mo_iv;
                }
                if($bgid >= 0){
                    $strOtherResponse = $strOtherResponse . '&bgid=' . $bgid;
                }
                if($bgt > 0){
                    $strOtherResponse = $strOtherResponse . '&bgt=' . $bgt;
                }
                if($bw > 0){
                    $strOtherResponse = $strOtherResponse . '&bw=' . $bw;
                }
                if($wp >= 0){
                    $strOtherResponse = $strOtherResponse . '&wp=' . $wp . '&rw=' . ($wp * $coef);
                }
                if($end >= 0){
                    $strOtherResponse = $strOtherResponse . '&end=' . $end;
                }
                if($level >= 0){
                    $strOtherResponse = $strOtherResponse . '&level=' . $level;
                }
                if($lifes >= 0){
                    $strOtherResponse = $strOtherResponse . '&lifes=' . $lifes;
                }
                if(count($new_wins) > 0){                    
                    $strOtherResponse = $strOtherResponse . '&wins=' . implode(',', $new_wins) . '&status=' . implode(',', $new_status) . '&wins_mask=' . implode(',', $new_wins_mask);
                }
                if($str_bg_i != ''){
                    $strOtherResponse = $strOtherResponse . '&bg_i=' . $str_bg_i . '&bg_i_mask=' . $str_bg_i_mask;
                }
                if($bgt == 31){
                    if($rsb_m > 0){
                        $strOtherResponse = $strOtherResponse . '&rsb_m=' . $rsb_m . '&rsb_c=' . $rsb_c;
                    }
                    if($str_rsb_s != ''){
                        $strOtherResponse = $strOtherResponse . '&rsb_s=' . $str_rsb_s;
                    }
                    if($str_rs_s != ''){
                        $strOtherResponse = $strOtherResponse . '&rs_s=' . $str_rs_s;
                    }
                }



                $response = 'balance='.$Balance . '&coef=' . $coef . $strOtherResponse . '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='. $spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sver=5&counter='. ((int)$slotEvent['counter'] + 1);
                
                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------
                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"Wins":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'Wins'))  . '","Status":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'Status')). '","Mo":"' . $str_mo. '","MoT":"' . $str_mo_t . '","TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), 'Bonus', $isState);
            }
            if($slotEvent['action'] == 'doSpin' || $slotEvent['action'] == 'doFSOption' || $slotEvent['action'] == 'doCollect' || $slotEvent['action'] == 'doCollectBonus' || $slotEvent['action'] == 'doBonus'){                
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
        
        public function findZokbos($reels, $firstSymbol, $repeatCount, $positions, $reelndex){
            $wild = '2';
            $bPathEnded = true;
            if($reelndex < 6){
                for($r = 0; $r < 7; $r++){
                    if($firstSymbol == $reels[$reelndex][$r] || $reels[$reelndex][$r] == $wild){
                        $this->findZokbos($reels, $firstSymbol, $repeatCount + 1, array_merge($positions, [($reelndex + $r * 6)]), $reelndex + 1);
                        $bPathEnded = false;
                    }
                }
            }
            if($bPathEnded == true){
                if($repeatCount >= 3){
                    $winLine = [];
                    $winLine['FirstSymbol'] = $firstSymbol;
                    $winLine['RepeatCount'] = $repeatCount;
                    $winLine['Positions'] = $positions;
                    $winLine['Mul'] = 1;
                    array_push($this->winLines, $winLine);
                }
            }
        }
    }
}

<?php 
namespace VanguardLTE\Games\DaVincisTreasurePM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Ind', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Wins', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'Wins_Mask', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'Status', '');
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [3,8,10,6,3,3,1,12,5,3,3,10,4,9,3]);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'Ind', $lastEvent->serverResponse->Ind);
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
                $bgt = 0;
                $Balance = $slotSettings->GetBalance();
                if($slotSettings->GetGameData($slotSettings->slotId . 'BonusState') <= 1){
                    if(isset($stack)){    
                        if($stack['reel_set'] >= 0){
                            $currentReelSet = $stack['reel_set'];
                        }
                        $wp = $stack['wp'];
                        $bgt = $stack['bgt'];
                        $level = $stack['level'];
                        $lifes = $stack['lifes'];
                        $bw = $stack['bw'];
                        $end = $stack['end'];
                        $bgid = $stack['bgid'];
                        $str_wins_mask = $stack['wins_mask'];
                        $str_wins = $stack['wins'];
                        $str_status = $stack['wins_status'];
                        $fslim = $stack['fslim'];
                        $str_go_i_mask = $stack['go_i_mask'];
                        $str_g_t = $stack['g_t'];
                        $str_go_i = $stack['go_i'];
                        $fsmore = $stack['fsmore'];
                        $str_prg_m = $stack['prg_m'];
                        $str_prg = $stack['prg'];
                        $strWinLine = $stack['win_line'];
                        $str_wof_mask = $stack['wof_mask'];
                        $str_wof_set = $stack['wof_set'];
                        $str_wof_map = $stack['wof_map'];
                        $wof_mi = $stack['wof_mi'];
                        $wof_p = $stack['wof_p'];
                        $gwm = $stack['gwm'];
                        $coef = $bet * 25;
                        if($wp >= 0){
                            $strOtherResponse = $strOtherResponse . '&rw='. ($wp * $coef) .'&wp=' . $wp;
                        }                    
                        if($bw == 1){
                            $strOtherResponse = $strOtherResponse .'&bw=1';
                        }
                        if($bgt > 0){
                            $strOtherResponse = $strOtherResponse . '&bgt=' . $bgt . '&coef=' . $coef;
                        }
                        if($level > -1){
                            $strOtherResponse = $strOtherResponse . '&level=' . $level;
                        }
                        if($lifes > -1){
                            $strOtherResponse = $strOtherResponse . '&lifes=' . $lifes;
                        }
                        if($end > -1){
                            $strOtherResponse = $strOtherResponse . '&end=' . $end;
                            if($end == 1){                                
                                if($bgt == 23 || $bgt == 9){
                                    $strOtherResponse = $strOtherResponse . '&g_ra=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                                    $spinType = 'go';
                                }else if($bgt == 21){
                                    $arr_wins_mask = explode(',', $str_wins_mask);
                                    if($arr_wins_mask[0] != 'psm'){
                                        $spinType = 'b';
                                    }
                                }
                            }else{
                                $spinType = 'b';
                            }
                        }
                        if($bgid > -1){
                            $strOtherResponse = $strOtherResponse . '&bgid=' . $bgid;
                        }
                        if($str_go_i != ''){
                            $strOtherResponse = $strOtherResponse . '&go_i_mask=' . $str_go_i_mask . '&g_t=' . $str_g_t . '&go_i=' . $str_go_i;
                        }   
                        if($str_wins != ''){
                            $strOtherResponse = $strOtherResponse . '&wins=' . $slotSettings->GetGameData($slotSettings->slotId . 'Wins') . '&wins_mask=' . $slotSettings->GetGameData($slotSettings->slotId . 'Wins_Mask') . '&status=' . $slotSettings->GetGameData($slotSettings->slotId . 'Status');
                        }
                        if($str_prg != ''){
                            $strOtherResponse = $strOtherResponse . '&prg=' . $str_prg . '&prg_m=' . $str_prg_m;
                        }
                        if($gwm > 0){
                            $strOtherResponse = $strOtherResponse .'&gwm=' . $gwm;
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
                        if($fslim > 0){
                            $strOtherResponse = $strOtherResponse . '&fslim=' . $fslim;
                        }
                        if($str_wof_map != ''){
                            $strOtherResponse = $strOtherResponse . '&wof_mask=' . $str_wof_mask . '&wof_set=' . $str_wof_set . '&wof_map=' . $str_wof_map . '&wof_mi=' . $wof_mi . '&wof_p=' . $wof_p;
                        }
                        if(($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0 || $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0))
                        {
                            $fs = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                            if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                                $strOtherResponse = $strOtherResponse . '&fs=' . $fs . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul=1';
                            }else if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0){
                                $strOtherResponse = $strOtherResponse . '&fs_total='. ($fs - 1) .'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) .'&g_ra=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsend_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                                $spinType = 'go';
                            }
                        } 
                        if($slotSettings->GetGameData($slotSettings->slotId . 'BonusState') == 1){
                            $strOtherResponse = $strOtherResponse . '&tw=0&g_ra=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                            if($slotSettings->GetGameData($slotSettings->slotId . 'Ind') > -1){
                                $spinType = 'g';
                                $strOtherResponse = $strOtherResponse . '&g_o=' . $slotSettings->GetGameData($slotSettings->slotId . 'Ind');
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', ($slotSettings->GetBalance() - $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')));
                                $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                            }
                        }else{
                            $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                        }
                    }
                }else if($slotSettings->GetGameData($slotSettings->slotId . 'BonusState') == 2){
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));

                $response = 'def_s=3,8,10,6,3,3,1,12,5,3,3,10,4,9,3&balance='. $Balance .'&cfgs=1772&ver=2&index=1&balance_cash='. $Balance .'&reel_set_size=2&def_sb=10,11,8,1,7&def_sa=8,3,2,3,13&prg_cfg_m=wm&balance_bonus=0.00&na='. $spinType .'&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&stime=' . floor(microtime(true) * 1000). $strOtherResponse . '&sa=8,3,2,3,13&sb=10,11,8,1,7&prg_cfg=0&sc='. implode(',', $slotSettings->Bet) .'&defc=50.00&sh=3&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&n_reel_set=0&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;800,200,50,10,0;500,150,30,5,0;500,150,30,5,0;300,100,20,0,0;300,100,20,0,0;150,15,5,0,0;150,15,5,0,0;150,15,5,0,0;100,10,5,0,0;100,10,5,0,0;100,10,5,0,0&l=25&reel_set0=4,13,13,10,11,8,10,13,6,6,6,11,8,7,11,12,8,12,6,5,11,13,10,4,12,9,3,3,3~11,4,7,10,10,2,12,6,8,12,11,13,8,5,9,1,10,3,3,3~13,13,11,13,11,9,5,10,7,1,9,1,11,8,9,13,6,9,10,2,9,12,4,11,10,10,4,9,1,11,2,6,3,3,3~9,12,9,5,8,12,7,13,10,11,1,12,8,9,2,10,12,8,7,6,1,4,5,9,3,3,3~5,5,13,10,11,8,9,11,12,6,13,2,4,7,7,4,13,3,3,3&s='.$lastReelStr.'&&reel_set1=6,11,7,5,4,4,8,10,13,9,6,12,3,3,3,3,3~9,8,8,5,12,2,12,1,7,11,2,7,10,10,13,4,6,5,1,3,3,3,3,3~4,2,11,13,11,10,5,13,9,5,8,13,5,2,1,6,13,11,9,9,11,7,12,3,3,3,3,3~13,12,11,1,11,5,12,10,5,8,7,13,10,9,5,10,11,6,4,1,2,3,3,3,3,3~9,7,7,6,13,13,9,13,8,7,4,5,10,10,12,2,4,5,11,4,3,3,3,3,3';
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'Ind', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Wins', '');
                    $slotSettings->SetGameData($slotSettings->slotId . 'Wins_Mask', '');
                    $slotSettings->SetGameData($slotSettings->slotId . 'Status', '');
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.1f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '56671' . substr($roundstr, 4, 9);
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
                $wp = -1;
                $bgt = 0;
                $level = -1;
                $lifes = -1;
                $bw = 0;
                $end = -1;
                $bgid = -1;
                $str_wins_mask = '';
                $str_wins = '';
                $str_status = '';
                $fsmore = 0;
                $str_prg_m = '';
                $str_prg = '';
                $gwm = 0;
                $fslim = 0;
                $str_go_i_mask = '';
                $str_g_t = '';
                $str_go_i = '';
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $wp = $stack['wp'];
                    $bgt = $stack['bgt'];
                    $level = $stack['level'];
                    $lifes = $stack['lifes'];
                    $bw = $stack['bw'];
                    $end = $stack['end'];
                    $bgid = $stack['bgid'];
                    $str_wins_mask = $stack['wins_mask'];
                    $str_wins = $stack['wins'];
                    $str_status = $stack['wins_status'];
                    $fsmore = $stack['fsmore'];
                    $str_prg_m = $stack['prg_m'];
                    $str_prg = $stack['prg'];
                    $strWinLine = $stack['win_line'];
                    $gwm = $stack['gwm'];
                    $fslim = $stack['fslim'];
                    $str_go_i_mask = $stack['go_i_mask'];
                    $str_g_t = $stack['g_t'];
                    $str_go_i = $stack['go_i'];
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
                    $wp = $stack[0]['wp'];
                    $bgt = $stack[0]['bgt'];
                    $level = $stack[0]['level'];
                    $lifes = $stack[0]['lifes'];
                    $bw = $stack[0]['bw'];
                    $end = $stack[0]['end'];
                    $bgid = $stack[0]['bgid'];
                    $str_wins_mask = $stack[0]['wins_mask'];
                    $str_wins = $stack[0]['wins'];
                    $str_status = $stack[0]['wins_status'];
                    $fsmore = $stack[0]['fsmore'];
                    $str_prg_m = $stack[0]['prg_m'];
                    $str_prg = $stack[0]['prg'];
                    $strWinLine = $stack[0]['win_line'];
                    $gwm = $stack[0]['gwm'];
                    $fslim = $stack[0]['fslim'];
                    $str_go_i_mask = $stack[0]['go_i_mask'];
                    $str_g_t = $stack[0]['g_t'];
                    $str_go_i = $stack[0]['go_i'];
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

                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $spinType = 's';
                    $isEnd = false;
                    $isState = false;
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                    {
                        $spinType = 'go';
                        $isEnd = true;
                        $strOtherResponse = '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fsmul_total=1&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres_total='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&g_ra='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                    else
                    {
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') .'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 's';
                    }
                }
                if($bw == 1 && $end == 0){
                    $isState = false;
                    $spinType = 'b';
                    $strOtherResponse = $strOtherResponse .'&rw=0.00&wp=0&coef='. ($betline * $lines);                   
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
                    $strOtherResponse = $strOtherResponse . '&reel_set=' . $currentReelSet;
                }
                if($bgt > 0){
                    $strOtherResponse = $strOtherResponse . '&bgt=' . $bgt;
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', $bgt);
                }
                if($level > -1){
                    $strOtherResponse = $strOtherResponse . '&level=' . $level;
                }
                if($lifes > -1){
                    $strOtherResponse = $strOtherResponse . '&lifes=' . $lifes;
                }
                if($end > -1){
                    $strOtherResponse = $strOtherResponse . '&end=' . $end;
                }
                if($bgid > -1){
                    $strOtherResponse = $strOtherResponse . '&bgid=' . $bgid;
                }
                if($str_go_i != ''){
                    $strOtherResponse = $strOtherResponse . '&go_i_mask=' . $str_go_i_mask . '&g_t=' . $str_g_t . '&go_i=' . $str_go_i;
                }   
                if($str_prg != ''){
                    $strOtherResponse = $strOtherResponse . '&prg=' . $str_prg . '&prg_m=' . $str_prg_m;
                }
                if($gwm > 0){
                    $strOtherResponse = $strOtherResponse .'&gwm=' . $gwm;
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                }
                if($fslim > 0){
                    $strOtherResponse = $strOtherResponse . '&fslim=' . $fslim;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&c='.$betline.'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&n_reel_set='. $currentReelSet .'&l=25&sh=3&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&s='.$strLastReel.'&w='. $totalWin;
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"Ind":' . $slotSettings->GetGameData($slotSettings->slotId . 'Ind') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'). ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount'). ',"Wins":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Wins'). '","Wins_Mask":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Wins_Mask'). '","Status":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Status') . '","FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
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
                $lines = 25;
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
                $wp = $stack['wp'];
                $bgt = $stack['bgt'];
                $level = $stack['level'];
                $lifes = $stack['lifes'];
                $bw = $stack['bw'];
                $end = $stack['end'];
                $bgid = $stack['bgid'];
                $str_wins_mask = $stack['wins_mask'];
                $str_wins = $stack['wins'];
                $str_status = $stack['wins_status'];
                $fslim = $stack['fslim'];
                $str_go_i_mask = $stack['go_i_mask'];
                $str_g_t = $stack['g_t'];
                $str_go_i = $stack['go_i'];
                $fsmore = $stack['fsmore'];
                $str_prg_m = $stack['prg_m'];
                $str_prg = $stack['prg'];
                $strWinLine = $stack['win_line'];
                $str_wof_mask = $stack['wof_mask'];
                $str_wof_set = $stack['wof_set'];
                $str_wof_map = $stack['wof_map'];
                $wof_mi = $stack['wof_mi'];
                $wof_p = $stack['wof_p'];
                $gwm = $stack['gwm'];
                $totalWin = 0;
                $spinType = 'b';
                $coef = $betline * $lines;
                $rw = 0;
                $isState = false;
                $strOtherResponse = '';
                if($bgt == 23){
                    if($wp > 0){
                        $totalWin = $wp * $coef;
                    }
                    if($totalWin > 0 && $end == 1){
                        $spinType = 'go';
                        $slotSettings->SetBalance($totalWin);
                        $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                        $strOtherResponse = $strOtherResponse . '&g_ra=' . $totalWin;
                    }
                }else if($bgt == 21){                    
                    $arr_wins_mask = explode(',', $str_wins_mask);
                    if($arr_wins_mask[0] == 'psm'){
                        $spinType = 's';
                        $arr_wins = explode(',', $str_wins);
                        if($arr_wins[0] > 0){
                            $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $arr_wins[0]);
                            $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                            $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=0.00&fs=1&fsres=0.00';
                        }
                    }
                }else if($bgt == 9){
                    $arr_wins = explode(',', $str_wins);
                    $arr_status = [0,0,0];
                    if($ind >= 0){
                        $win_item = $arr_wins[0];
                        $arr_wins[0] = $arr_wins[$ind];
                        $arr_wins[$ind] = $win_item;
                        $arr_status[$ind] = 1;
                        $str_wins = implode(',', $arr_wins);
                        $str_status = implode(',', $arr_status);
                    }
                    if($wp > 0){
                        $totalWin = $wp * $coef;
                    }
                    if($totalWin > 0){
                        $spinType = 'go';
                        $slotSettings->SetBalance($totalWin);
                        $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                        $strOtherResponse = $strOtherResponse . '&g_ra=' . $totalWin;
                    }
                }
                $strOtherResponse = $strOtherResponse . '&wins='. $str_wins .'&status='. $str_status .'&wins_mask='. $str_wins_mask;
                if($wp >= 0){
                    $strOtherResponse = $strOtherResponse  .'&rw='. ($wp * $coef) .'&wp='. $wp;
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', $bgt);

                $slotSettings->SetGameData($slotSettings->slotId . 'Wins', $str_wins);
                $slotSettings->SetGameData($slotSettings->slotId . 'Wins_Mask', $str_wins_mask);
                $slotSettings->SetGameData($slotSettings->slotId . 'Status', $str_status);
                
                if($currentReelSet >= 0){
                    $strOtherResponse = $strOtherResponse . '&reel_set=' . $currentReelSet;
                }
                if($bgt > 0){
                    $strOtherResponse = $strOtherResponse . '&bgt=' . $bgt;
                }
                if($level > -1){
                    $strOtherResponse = $strOtherResponse . '&level=' . $level;
                }
                if($lifes > -1){
                    $strOtherResponse = $strOtherResponse . '&lifes=' . $lifes;
                }
                if($end > -1){
                    $strOtherResponse = $strOtherResponse . '&end=' . $end;
                    if($end == 1){
                        $strOtherResponse = $strOtherResponse . '&tw=0';
                    }else{
                        $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    }
                }
                if($bgid > -1){
                    $strOtherResponse = $strOtherResponse . '&bgid=' . $bgid;
                }
                if($str_go_i != ''){
                    $strOtherResponse = $strOtherResponse . '&go_i_mask=' . $str_go_i_mask . '&g_t=' . $str_g_t . '&go_i=' . $str_go_i;
                }
                if($str_prg != ''){
                    $strOtherResponse = $strOtherResponse . '&prg=' . $str_prg . '&prg_m=' . $str_prg_m;
                }
                if($gwm > 0){
                    $strOtherResponse = $strOtherResponse .'&gwm=' . $gwm;
                }
                if($str_wof_map != ''){
                    $strOtherResponse = $strOtherResponse . '&wof_mask=' . $str_wof_mask . '&wof_set=' . $str_wof_set . '&wof_map=' . $str_wof_map. '&wof_mi=' . $wof_mi . '&wof_p=' . $wof_p;
                }
                
                $response = 'balance='. $Balance .'&index='.$slotEvent['index'].'&coef='. $coef .'&balance_cash='. $Balance .'&balance_bonus=0.00&na='. $spinType . $strOtherResponse .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sver=5&counter='. ((int)$slotEvent['counter'] + 1);

                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"Ind":' . $slotSettings->GetGameData($slotSettings->slotId . 'Ind') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'). ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount'). ',"Wins":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Wins'). '","Wins_Mask":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Wins_Mask'). '","Status":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Status') . '","FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
            }else if( $slotEvent['slotEvent'] == 'doGambleOption'){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lastReel = $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'); 
                $lines = 25;
                $scatter = 1;
                $wild = 2;
                $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 1);
                $ind = -1;
                if(isset($slotEvent['g_o_ind'])){
                    $ind = $slotEvent['g_o_ind'];
                }
                $spinType = 'b';
                $coef = $betline * $lines;
                $rw = 0;
                $isState = false;
                $strOtherResponse = '';
                $spinType = 'c';
                if($slotEvent['g_a'] == 'stand'){
                    $isState = true;
                    $strOtherResponse = '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    if($slotSettings->GetGameData($slotSettings->slotId . 'Bgt') == '23' || $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') == '9'){
                        $spinType = 'cb';
                    }
                }else{
                    $spinType = 'g';
                    $strOtherResponse = '&tw=0&g_o=' . $ind;
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'Ind', $ind);                
                $response = 'balance='. $Balance .'&index='.$slotEvent['index'] .'&balance_cash='. $Balance .'&balance_bonus=0.00&na='. $spinType . $strOtherResponse .'&g_ra='. $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sver=5&counter='. ((int)$slotEvent['counter'] + 1);

                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"Ind":' . $slotSettings->GetGameData($slotSettings->slotId . 'Ind') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'). ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount'). ',"Wins":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Wins'). '","Wins_Mask":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Wins_Mask'). '","Status":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Status') . '","FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
            }else if($slotEvent['slotEvent'] == 'doGamble'){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lines = 25;
                $scatter = 1;
                $wild = 2;
                $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 2);
                $lastReel = $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'); 
                $ind = $slotSettings->GetGameData($slotSettings->slotId . 'Ind'); 
                $g_ind = -1;
                if(isset($slotEvent['g_ind'])){
                    $g_ind = $slotEvent['g_ind'];
                }
                $spinType = 'b';
                $coef = $betline * $lines;
                $rw = 0;
                $isState = true;
                $strOtherResponse = '';
                $muls = [2,3,4,5];
                $totalWin = 0;
                if(isset($slotEvent['g_a']) && $slotEvent['g_a'] == 'gamble' && $ind > -1){
                    $winMul = $slotSettings->winGameble($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin'), $ind);
                    $win_id = $g_ind;
                    if($winMul > 0){
                        $strOtherResponse = $strOtherResponse . '&g_r=1';
                        $totalWin = $winMul * $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        if($totalWin > 0){
                            if($slotSettings->GetGameData($slotSettings->slotId . 'Bgt') > 0){
                                $spinType = 'cb';
                            }else{
                                $spinType = 'c';
                            }
                            $slotSettings->SetBalance($totalWin);
                            $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                            // $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                            $strOtherResponse = $strOtherResponse . '&g_ra=' . $totalWin;
                        }
                    }else{
                        $spinType = 's';
                        while(true){
                            $win_id = mt_rand(0, ($muls[$ind] - 1));
                            if($win_id != $g_ind){
                                break;
                            }
                        }
                        $strOtherResponse = $strOtherResponse . '&g_r=0';
                        $slotSettings->SetBalance(-1 * $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin'));
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') - $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin'));
                    }
                    $strOtherResponse = $strOtherResponse . '&g_l=1&g_wi='. $win_id .'&g_w='. $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') .'&g_si='. $g_ind .'&g_mul=' . $muls[$ind];
                }else{
                    if($slotSettings->GetGameData($slotSettings->slotId . 'Bgt') > 0){
                        $spinType = 'cb';
                    }else{
                        $spinType = 'c';
                    }
                }
                $response = 'tw='. $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='. $Balance .'&index='.$slotEvent['index'] .'&balance_cash='. $Balance .'&balance_bonus=0.00&na='. $spinType . '&g_ra=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&g_end=1&sver=5&counter='. ((int)$slotEvent['counter'] + 1);

                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"Ind":' . $slotSettings->GetGameData($slotSettings->slotId . 'Ind') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'). ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount'). ',"Wins":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Wins'). '","Wins_Mask":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Wins_Mask'). '","Status":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Status') . '","FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
            }
            if($slotEvent['action'] == 'doSpin' || $slotEvent['action'] == 'doCollect' || $slotEvent['action'] == 'doBonus' || $slotEvent['action'] == 'doGambleOption' || $slotEvent['action'] == 'doGamble' || $slotEvent['action'] == 'doCollectBonus'){
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

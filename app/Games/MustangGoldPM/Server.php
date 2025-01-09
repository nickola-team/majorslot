<?php 
namespace VanguardLTE\Games\MustangGoldPM
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
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [6,7,4,2,8,9,8,5,6,7,8,6,7,3,9]);
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
                    if($mo_c >= 0){
                        $strOtherResponse = $strOtherResponse . '&mo_c=' . $mo_c;
                    }
                    if($mo_tv > 0){
                        $strOtherResponse = $strOtherResponse . '&mo_tv=' . $mo_tv. '&mo_tw=' . ($mo_tv * $bet);
                    }
                    if($bgid >= 0){
                        $strOtherResponse = $strOtherResponse . '&bgid=' . $bgid;
                    }else{
                        $strOtherResponse = $strOtherResponse . '&bgid=0';
                    }
                    $coef = 0;
                    if($bgt > 0){
                        $coef = $bet * 25;
                        $strOtherResponse = $strOtherResponse . '&coef=' . $coef;
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
                        for($k=0; $k<12; $k++){
                            if($arr_status[$k] > 0){
                                $arr_wins_mask[$k] = 'pw';
                            }else{
                                $arr_wins_mask[$k] = 'h';
                            }
                        }
                        $strOtherResponse = $strOtherResponse . '&wins=' . implode(',', $arr_wins) . '&status=' . implode(',', $arr_status) . '&wins_mask=' . implode(',', $arr_wins_mask);
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

                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                    {
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsend_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    }
                    else if( ($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)) 
                    {
                        $strOtherResponse = $strOtherResponse . '&fs=' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&w=0.00&fsmul=1';
                    }
                }else{
                    $strOtherResponse = $strOtherResponse . '&bgid=0';
                }
                
                $Balance = $slotSettings->GetBalance();  

                $response = 'def_s=6,7,4,2,8,9,8,5,6,7,8,6,7,3,9&balance='. $Balance .'&cfgs=1&ver=2&mo_s=11&index=1&balance_cash='.$Balance.'&reel_set_size=2&def_sb=10,11,9,6,8&mo_v=25,50,75,125,200,250,300,375,450,500,625,750,875,0&def_sa=7,5,4,4,3&bonusInit=[{bgid:0,bgt:18,bg_i:"1000,200,100,50",bg_i_mask:"pw,pw,pw,pw"}]&mo_jp=0&balance_bonus=0.00&na='. $spinType .
                    '&scatters=1~0,0,1,0,0~0,0,8,0,0~1,1,1,1,1&gmb=0,0,0&bg_i=1000,200,100,50&rt=d&mo_jp_mask=jpb&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime='. floor(microtime(true) * 1000) .'&bgt=18&sa=7,5,4,4,3&sb=10,11,9,6,8&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=40&sh=3&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&n_reel_set='.$currentReelSet.
                    '&bg_i_mask=pw,pw,pw,pw&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;500,50,10,0,0;300,50,10,0,0;250,25,10,0,0;200,25,10,0,0;100,15,5,0,0;100,15,5,0,0;100,15,5,0,0;100,15,5,0,0;0,0,0,0,0;0,0,0,0,0&l=25&rtp=96.53&reel_set0=5,7,6,3,10,9,4,5,7,6,9,7,11,11,11,11,8,10,6,9,5,10,6,8,3,4,10,5,7,9,6,10,4,9,5,8,9,6,10,3,7,6,8,10,8,4,7,10,6,9,5,3,10,7~7,2,2,2,2,2,9,3,8,5,9,1,10,6,9,4,7,8,11,11,11,11,7,9,4,8,3,9,5,10,4,9,1,7,5,9,3,8,5,4,9,10,6,9,3,7,4,8,5,9,6,10~3,2,2,2,2,2,8,5,9,1,10,4,8,7,3,8,6,5,8,4,7,6,8,5,9,6,3,9,8,3,10,2,2,2,2,2,8,6,8,5,7,1,8,6,9,3,10,11,11~10,2,2,2,2,2,9,8,5,4,7,1,10,3,7,11,11,11,11,4,9,3,10,1,7,6,9,4,7,8,6,9,3,7,4,10,6,7,1,8,7,3,9,5,7,1,8,6,7,9,5,7,9,6,7,4,5,8,3~7,2,2,2,2,8,6,10,3,9,5,8,3,4,7,6,10,9,5,10,7,6,9,4,3,7,6,10,5,7,12,10,6,7,4,9,6,8,7,6,9,5,3,10,6,7,5,3,8,9,6,10,4,7,3,9,4,9,8&s='.$lastReelStr.'&reel_set1=5,7,6,3,10,9,4,5,7,6,9,7,11,11,11,5,8,10,6,9,5,6,10,8,3,4,10,11,11~7,2,2,2,2,2,9,3,8,5,9,1,10,6,9,4,7,8,11,11,11,5,7,9,4,8,3,9~3,2,2,2,2,2,8,5,9,1,10,4,8,11,11,11,6,5,8,4,7,1,8,5,9,6,3,9~10,2,2,2,2,2,2,2,2,4,7,1,10,3,7,5,11,11,11,4,9,3,10,1,7,6,9,4,8~12,2,2,9,6,10,12,9,5,8,3,4,9,6,10,3,9,10,12,6,9,3,7,12,10,8,8,3';
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
                    $roundstr = '6074458' . substr($roundstr, 4, 10);
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
                $mo_c = -1;
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
                $fsmore = 0;
                $fsmax = 0;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t']; 
                    $mo_tv = $stack['mo_tv'];
                    $mo_c = $stack['mo_c'];          
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
                    $currentReelSet = $stack[0]['reel_set'];
                    $str_mo = $stack[0]['mo'];
                    $str_mo_t = $stack[0]['mo_t']; 
                    $mo_tv = $stack[0]['mo_tv'];
                    $mo_c = $stack[0]['mo_c'];                
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
                    $fsmore = $stack[0]['fsmore'];
                    $fsmax = $stack[0]['fsmax'];
                }
                $reels = [];
                $scatterCount = 0;
                $scatterPoses = [];
                for($k = 0; $k < count($lastReel); $k++){
                    if($lastReel[$k] == 1){
                        $scatterCount++;
                        $scatterPoses[] = $k;
                    }
                }
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
                $moneyWin = 0;
                if($mo_tv > 0){
                    $moneyWin = $mo_tv * $betline;
                    $totalWin = $totalWin + $moneyWin;
                }
                $scatterWin = 0;
                if($scatterCount >= 3){
                    $scatterWin = $betline * $lines;
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
                $isState = true;
                if($fsmax > 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $fsmax);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1); 
                }else if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
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
                    if($fsmax > 0){
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&psym=1~'. $scatterWin .'~' . implode(',', $scatterPoses);
                        $spinType = 's';
                        
                    }
                }
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                }
                if($mo_c >= 0){
                    $strOtherResponse = $strOtherResponse . '&mo_c=' . $mo_c;
                }
                if($mo_tv > 0){
                    $strOtherResponse = $strOtherResponse . '&mo_tv=' . $mo_tv. '&mo_tw=' . $moneyWin;
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
                if( $slotEvent['slotEvent'] != 'freespin' && ($bw == 1 || $fsmax > 0 )) 
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
                
                if($end == 1){
                    $totalWin = $wp * $coef;
                    if($totalWin > 0){
                        $spinType = 'cb';
                        $slotSettings->SetBalance($totalWin);
                        $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                        $isState = true;
                    }
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                    {
                        $spinType = 's';
                        $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                        $isEnd = false;
                        if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                        {
                            $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsend_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
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
                if($mo_c >= 0){
                    $strOtherResponse = $strOtherResponse . '&mo_c=' . $mo_c;
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

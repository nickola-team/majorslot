<?php 
namespace VanguardLTE\Games\DiamondStrikePM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 15);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [6,7,4,2,8,4,3,5,6,7,8,5,7,3,4]);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Wins', []);
                $slotSettings->SetGameData($slotSettings->slotId . 'Status', []);
                $slotSettings->SetGameData($slotSettings->slotId . 'WinsMask', []);
                $stack = null;
                
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', $lastEvent->serverResponse->BonusState);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', $lastEvent->serverResponse->Bgt);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Wins', explode(',', $lastEvent->serverResponse->Wins));
                    $slotSettings->SetGameData($slotSettings->slotId . 'Status', explode(',', $lastEvent->serverResponse->Status));
                    $slotSettings->SetGameData($slotSettings->slotId . 'WinsMask', explode(',', $lastEvent->serverResponse->WinsMask));
                    if (isset($lastEvent->serverResponse->ReplayGameLogs)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', json_decode(json_encode($lastEvent->serverResponse->ReplayGameLogs), true)); //ReplayLog
                    }
                    if (isset($lastEvent->serverResponse->FreeStacks)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', json_decode(json_encode($lastEvent->serverResponse->FreeStacks), true)); // FreeStack
                        $FreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                        $stack = $FreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') -1];
                    }
                    $bet = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $bet = '60.00';
                }
                $currentReelSet = 0;
                $spinType = 's';
                $strOtherResponse = '';
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1) && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                    {
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                    else
                    {
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                }
                if($stack != null){
                    $str_gsf_r = $stack['gsf_r'];
                    $str_gsf = $stack['gsf'];
                    $str_wins = $stack['wins'];
                    $str_status = $stack['wins_status'];
                    $str_wins_mask = $stack['wins_mask'];
                    $bw = $stack['bw'];
                    $end = $stack['end'];
                    $bgt = $stack['bgt'];
                    $wp = $stack['wp'];
                    $bgid = $stack['bgid'];
                    $level = $stack['level'];
                    $lifes = $stack['lifes'];
                    $str_bg_i = $stack['bg_i'];
                    $str_bg_i_mask = $stack['bg_i_mask'];
                    $fsmore = $stack['fsmore'];
                    $strWinLine = $stack['win_line'];
                    if($stack['reel_set'] > -1){
                        $currentReelSet = $stack['reel_set'];
                    }
                    
                    if($str_gsf != ''){
                        $strOtherResponse = $strOtherResponse . '&gsf=' . $str_gsf;
                    }
                    if($str_gsf_r != ''){
                        $strOtherResponse = $strOtherResponse  . '&gsf_r=' . $str_gsf_r;
                    }
                    $rw = 0;
                    $coef = $bet * 15;
                    if($wp > 0){
                        $rw = $coef * $wp;
                    }  
                    if($slotSettings->GetGameData($slotSettings->slotId . 'Bgt') == 18){
                        $spinType = 'b';
                    }
                    if($bw > 0){
                        $strOtherResponse = $strOtherResponse . '&bw=' . $bw;
                    }
                    if($wp >= 0){
                        $strOtherResponse = $strOtherResponse . '&wp=' . $wp . '&rw=' . ($wp * $coef);
                    }
                    if($end >= 0){
                        $strOtherResponse = $strOtherResponse . '&end=' . $end . '&coef=' . $coef;
                    }
                    if($level >= 0){
                        $strOtherResponse = $strOtherResponse . '&level=' . $level;
                    }
                    if($lifes >= 0){
                        $strOtherResponse = $strOtherResponse . '&lifes=' . $lifes;
                    }
                    if($bgid >= 0){
                        $strOtherResponse = $strOtherResponse . '&bgid=' . $bgid;
                    }
                    if($str_wins != ''){
                        $arr_wins = $slotSettings->GetGameData($slotSettings->slotId . 'Wins');
                        $arr_status = $slotSettings->GetGameData($slotSettings->slotId . 'Status');
                        $arr_wins_mask = $slotSettings->GetGameData($slotSettings->slotId . 'WinsMask');
                        $strOtherResponse = $strOtherResponse . '&wins=' . implode(',', $arr_wins) . '&status=' . implode(',', $arr_status) . '&wins_mask=' . implode(',', $arr_wins_mask);
                    }
                    
                    if($strWinLine != ''){
                        $arr_lines = explode('&', $strWinLine);
                        for($k = 0; $k < count($arr_lines); $k++){
                            $arr_sub_lines = explode('~', $arr_lines[$k]);
                            $arr_sub_lines[1] = str_replace(',', '', $arr_sub_lines[1]) / $original_bet * $bet;
                            $arr_lines[$k] = implode('~', $arr_sub_lines);
                        }
                        $strWinLine = implode('&', $arr_lines);
                    }
                    $strOtherResponse = $strOtherResponse  . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));

                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=6,7,4,2,8,4,3,5,6,7,8,5,7,3,4&bgid=0&balance='. $Balance .'&cfgs=2186&ver=2&index=1&balance_cash='. $Balance .'&reel_set_size=2&def_sb=7,4,7,5,7&def_sa=3,6,6,8,8&bonusInit=[{bgid:0,bgt:18,bg_i:"1000,100,30,10",bg_i_mask:"pw,pw,pw,pw"}]&balance_bonus=0.00&na='.$spinType.'&scatters=1~0,0,0,0,0~0,0,8,0,0~1,1,1,1,1&gmb=0,0,0&bg_i=1000,100,30,10&rt=d&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&bgt=18&sa=3,6,6,8,8&sb=7,4,7,5,7&sc='. implode(',', $slotSettings->Bet) .'&defc=100.00&sh=3&wilds=2~300,60,20,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='. $bet .'&sver=5&n_reel_set='.$currentReelSet.$strOtherResponse.'&bg_i_mask=pw,pw,pw,pw&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;200,20,10,0,0;100,20,10,0,0;40,10,5,0,0;40,10,5,0,0;40,10,5,0,0;40,10,5,0,0&l=15&reel_set0=6,5,7,8,6,2,2,2,7,3,1,4,7,8,4,7,2,2,7,6,3,6,7,6,4,6~6,8,4,8,5,5,3,8,2,2,8,3,8,8,5,4,5,8,4,6,8,3,5,4,5,2,2,2,7~7,3,6,5,2,2,2,8,5,6,5,2,2,5,4,5,5,7,1~6,4,3,6,8,4,7,6,5,6,2,2,2,7,5,8,6,7,6,4,6,8,4,2~4,5,6,5,6,4,5,1,7,5,2,2,2,7,5,7,1,5,8,7,4,5,6,7,5,7,3,7,5,7,3,5,4,7,4,5,7&s='.$lastReelStr.'&reel_set1=4,8,3,7,6,4,6,1,4,6,2,2,2,7,4,1,5,7,8,5,7,4,6,8,2,2,5,3~3,4,6,4,3,8,5,2,2,3,6,4,4,5,2,2,2,6,7,8,4,8,5~7,2,2,6,4,3,1,6,2,2,2,3,4,5,4,5,8,7,4,5,6,8~8,6,3,6,4,2,2,2,4,8,4,5,7,6,2,2,5,5,8,6,4,7~1,8,1,6,2,2,2,5,4,8,7,4,5,4,7,4,1,5,6,8,7,8,2,2,3,6';
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
                $lines = 15;      
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
                $slotEvent['slotLines'] = 15;
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
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
                    if( $slotEvent['slotEvent'] == 'doSpin' && $slotSettings->GetBalance() < ($lines * $betline) ) 
                    {
                        $balance_cash = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                        if(!isset($balance_cash)){
                            $balance_cash = $slotSettings->GetBalance();
                        }
                        $response = 'nomoney=1&balance='. $balance_cash .'&error_type=i&index='.$slotEvent['index'].'&balance_cash='. $balance_cash .'&balance_bonus=0.00&na=s&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&ext_code=SystemError&sver=5&counter='. ((int)$slotEvent['counter'] + 1);
                        exit( $response );
                    }
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') < $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotEvent['slotEvent'] == 'freespin' ) 
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
                
                $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $betline * $lines, $lines);
                $winType = $_spinSettings[0];
                $_winAvaliableMoney = $_spinSettings[1];

                // $winType = 'win';

                $allBet = $betline * $lines;
                $freeStacks = []; 
                $isGeneratedFreeStack = false;
                $slotSettings->SetGameData($slotSettings->slotId . 'Wins', []);
                $slotSettings->SetGameData($slotSettings->slotId . 'Status', []);
                $slotSettings->SetGameData($slotSettings->slotId . 'WinsMask', []);
                if($slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    $bonusMpl = $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl');
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '4174458' . substr($roundstr, 4, 10);
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
                $strWinLine = '';
                $winLineCount = 0;
                $str_gsf_r = '';
                $str_gsf = '';
                $bw = 0;
                $end = -1;
                $bgt = 0;
                $wp = 0;
                $bgid = -1;
                $level = -1;
                $lifes = -1;
                $str_wins = '';
                $str_status = '';
                $str_wins_mask = '';
                $str_bg_i_mask = '';
                $str_bg_i = '';
                $fsmore = 0;
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_gsf_r = $stack['gsf_r'];
                    $str_gsf = $stack['gsf'];
                    $str_wins = $stack['wins'];
                    $str_status = $stack['wins_status'];
                    $str_wins_mask = $stack['wins_mask'];
                    $bw = $stack['bw'];
                    $end = $stack['end'];
                    $bgt = $stack['bgt'];
                    $wp = $stack['wp'];
                    $bgid = $stack['bgid'];
                    $level = $stack['level'];
                    $lifes = $stack['lifes'];
                    $str_bg_i = $stack['bg_i'];
                    $str_bg_i_mask = $stack['bg_i_mask'];
                    $fsmore = $stack['fsmore'];
                    $currentReelSet = $stack['reel_set'];
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
                    $str_gsf_r = $stack[0]['gsf_r'];
                    $str_gsf = $stack[0]['gsf'];
                    $str_wins = $stack[0]['wins'];
                    $str_status = $stack[0]['wins_status'];
                    $str_wins_mask = $stack[0]['wins_mask'];
                    $bw = $stack[0]['bw'];
                    $end = $stack[0]['end'];
                    $bgt = $stack[0]['bgt'];
                    $wp = $stack[0]['wp'];
                    $bgid = $stack[0]['bgid'];
                    $level = $stack[0]['level'];
                    $lifes = $stack[0]['lifes'];
                    $str_bg_i = $stack[0]['bg_i'];
                    $str_bg_i_mask = $stack[0]['bg_i_mask'];
                    $fsmore = $stack[0]['fsmore'];
                    $currentReelSet = $stack[0]['reel_set'];
                    $strWinLine = $stack[0]['win_line'];
                }
                $reels = [];
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;

                for($i = 0; $i < 5; $i++){
                    $reels[$i] = [];
                    for($j = 0; $j < 3; $j++){
                        $reels[$i][$j] = $lastReel[$j * 5 + $i];
                        if($lastReel[$j * 5 + $i] == $scatter){
                            $scatterCount++;
                            $scatterPoses[] = $j * 5 + $i;   
                        }
                    }
                }
                if($strWinLine != ''){
                    $arr_lines = explode('&', $strWinLine);
                    for($k = 0; $k < count($arr_lines); $k++){
                        $arr_sub_lines = explode('~', $arr_lines[$k]);
                        $arr_sub_lines[1] = str_replace(',', '', $arr_sub_lines[1]) / $original_bet * $betline;
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
                if( $scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin') 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 8);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                }else if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                }

                $strLastReel = implode(',', $lastReel);
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 5; $i++){
                    $reelA[$i] = mt_rand(4, 8);
                    $reelB[$i] = mt_rand(4, 8);
                }
                $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
               
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                
                $strOtherResponse = '';
                $isState = true;
                $isEnd = true;
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $spinType = 's';
                    $isEnd = false;
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                    {
                        $spinType = 'c';
                        $isEnd = true;
                        $strOtherResponse = '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                    else
                    {
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 's';
                    }
                    
                    if($fsmore > 0 ){
                        $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                    if($scatterCount >= 3 ){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse = '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fs=1&fsres=0.00';
                    }
                }
                if($bw == 1){
                    $spinType = 'b';
                    $isState = false;    
                    $strOtherResponse = $strOtherResponse . '&coef='. ($betline * $lines) .'&level=0&rw=0.00&bgt='. $bgt .'&lifes=1&bw=1&wp='. $wp .'&end=' . $end;
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', $bgt);
                }
                if($str_gsf != ''){
                    $strOtherResponse = $strOtherResponse . '&gsf=' . $str_gsf;
                }
                if($str_gsf_r != ''){
                    $strOtherResponse = $strOtherResponse  . '&gsf_r=' . $str_gsf_r;
                }
                if($str_bg_i != ''){
                    $strOtherResponse = $strOtherResponse . '&bg_i=' . $str_bg_i . '&bg_i_mask=' . $str_bg_i_mask;
                }
                if($str_wins != ''){
                    $slotSettings->SetGameData($slotSettings->slotId . 'Wins', explode(',', $str_wins));
                    $slotSettings->SetGameData($slotSettings->slotId . 'Status', explode(',', $str_status));
                    $slotSettings->SetGameData($slotSettings->slotId . 'WinsMask', explode(',', $str_wins_mask));
                    $strOtherResponse = $strOtherResponse . '&wins=' . $str_wins . '&status=' . $str_status . '&wins_mask=' . $str_wins_mask;
                }
                if($bgid > 0){
                    $strOtherResponse = $strOtherResponse . '&bgid=' . $bgid;
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                }
                
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&c='.$betline.'&sver=5&n_reel_set='.$currentReelSet.'&counter='. ((int)$slotEvent['counter'] + 1) .'&l=15&s='.$strLastReel.'&w='.$totalWin;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) && $bw == 0) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount'). ',"Wins":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'Wins'))  . '","Status":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'Status')). '","WinsMask":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'WinsMask')) . '","FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if( ($scatterCount >= 3 || $bw == 1) && $slotEvent['slotEvent'] != 'freespin') 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                }

            }else if( $slotEvent['slotEvent'] == 'doBonus' ){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lastReel = $lastEvent->serverResponse->LastReel;
                $lines = 15;
                $ind = -1;
                if(isset($slotEvent['ind'])){
                    $ind = $slotEvent['ind'];
                }
                $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                while(true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    if($stack['wins'] != ''){
                        break;
                    }
                }
                               
                $str_wins = $stack['wins'];
                $str_status = $stack['wins_status'];
                $str_wins_mask = $stack['wins_mask'];
                $bw = $stack['bw'];
                $end = $stack['end'];
                $bgt = $stack['bgt'];
                $wp = $stack['wp'];
                $bgid = $stack['bgid'];
                $level = $stack['level'];
                $lifes = $stack['lifes'];
                $str_bg_i = $stack['bg_i'];
                $str_bg_i_mask = $stack['bg_i_mask'];
                $currentReelSet = 0;
                if($stack['reel_set'] >= 0){
                    $currentReelSet = $stack['reel_set'];
                }

                $arr_wins = explode(',', $str_wins);
                $arr_status = explode(',', $str_status);
                $arr_wins_mask = explode(',', $str_wins_mask);
                
                $new_wins = $slotSettings->GetGameData($slotSettings->slotId . 'Wins');
                $new_status = $slotSettings->GetGameData($slotSettings->slotId . 'Status');
                $new_wins_mask = $slotSettings->GetGameData($slotSettings->slotId . 'WinsMask');

                if($ind > -1){
                    $new_wins[$ind] = $arr_wins[$level - 1];
                    $new_status[$ind] = $arr_status[$level - 1];
                    $new_wins_mask[$ind] = $arr_wins_mask[$level - 1];
                }

                $totalWin = 0;
                $coef = $betline * $lines;
                $rw = 0;
                if($wp > 0){
                    $rw = $wp * $coef;                        
                }
                $isState = false;
                if($end == 1){
                    $totalWin = $rw;
                    $isState = true;
                }
                $spinType = 'b';
                if( $totalWin > 0) 
                {
                    $spinType = 'cb';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                $strOtherResponse = '';
                if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                    {
                        $strOtherResponse = '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                    else
                    {
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        if($end == 1){
                            $spinType = 's';
                            $isState = false;
                        }
                    }
                }
                
                $slotSettings->SetGameData($slotSettings->slotId . 'Wins', $new_wins);
                $slotSettings->SetGameData($slotSettings->slotId . 'Status', $new_status);
                $slotSettings->SetGameData($slotSettings->slotId . 'WinsMask', $new_wins_mask);
                if(count($new_wins) > 0){                    
                    $strOtherResponse = $strOtherResponse . '&wins=' . implode(',', $new_wins) . '&status=' . implode(',', $new_status) . '&wins_mask=' . implode(',', $new_wins_mask);
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
                    $strOtherResponse = $strOtherResponse . '&wp=' . $wp . '&rw=' . $rw;
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
                if($str_bg_i != ''){
                    $strOtherResponse = $strOtherResponse . '&bg_i=' . $str_bg_i . '&bg_i_mask=' . $str_bg_i_mask;
                }
                
                
                $response = 'balance='. $Balance  . $strOtherResponse .'&coef='. $coef .'&index='.$slotEvent['index'].'&balance_cash='. $Balance .'&balance_bonus=0.00&na='. $spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sver=5&counter='. ((int)$slotEvent['counter'] + 1);

                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount'). ',"Wins":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'Wins'))  . '","Status":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'Status')). '","WinsMask":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'WinsMask')) . '","FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
            }
            if($slotEvent['action'] == 'doSpin' || $slotEvent['action'] == 'doBonus' || $slotEvent['action'] == 'doCollect' || $slotEvent['action'] == 'doCollectBonus' || $slotEvent['action'] == 'doMysteryScatter'){
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

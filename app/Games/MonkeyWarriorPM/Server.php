<?php 
namespace VanguardLTE\Games\MonkeyWarriorPM
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
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [10,3,6,8,9,9,7,10,3,5,4,5,8,4,10]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $strOtherResponse = '';
                $currentReelSet = 0;
                $stack = null;
                
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
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
                        $stack = $FreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') -1];
                    }
                    if(isset($lastEvent->serverResponse->Init_G)){
                        $arr_init_g = json_decode(json_encode($lastEvent->serverResponse->Init_G), true);
                    }
                    $bet = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $bet = '40.00';
                }
                $spinType = 's';
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                $fsmore = 0;
                if(isset($stack)){
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $rw = str_replace(',', '', $stack['rw']);
                    $bpw = str_replace(',', '', $stack['bpw']);
                    $str_rsb_s = $stack['rsb_s'];
                    $rsb_m = $stack['rsb_m'];
                    $rsb_c = $stack['rsb_c'];
                    $rsb_more = $stack['rsb_more'];
                    $bw = $stack['bw'];
                    $bgt = $stack['bgt'];
                    $end = $stack['end'];
                    $str_wins = $stack['wins'];
                    $str_status = $stack['wins_status'];
                    $str_wins_mask = $stack['wins_mask'];
                    $strWinLine = $stack['win_line'];
                    $fsmore = $stack['fsmore'];
                    $fsmax = $stack['fsmax'];
                    
                    if($str_mo != ''){
                        $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                    }
                    if($rw > 0){
                        $strOtherResponse = $strOtherResponse . '&rw=' . ($rw / $original_bet * $bet);
                    }
                    if($bpw > 0){
                        $strOtherResponse = $strOtherResponse . '&bpw=' . ($bpw / $original_bet * $bet);
                    }
                    if($str_rsb_s != ''){
                        $strOtherResponse = $strOtherResponse . '&rsb_s=' . $str_rsb_s;
                    }
                    if($rsb_m > 0){
                        $strOtherResponse = $strOtherResponse . '&rsb_m=' . $rsb_m . '&rsb_c=' . $rsb_c;
                    }
                    if($rsb_more > -1){
                        $strOtherResponse = $strOtherResponse . '&rsb_more=' . $rsb_more;
                    }
                    if($bw > 0){
                        $strOtherResponse = $strOtherResponse . '&bw=' . $bw;
                    }
                    if($bgt > 0){
                        $strOtherResponse = $strOtherResponse . '&bgid=0&bgt=' . $bgt;
                        if($end == 0 || $bw == 1){
                            $spinType = 'b';                        
                        }
                    }
                    if($end >= 0){
                        $strOtherResponse = $strOtherResponse . '&end=' . $end;
                    }
                    if($lastEvent->serverResponse->StrTotalWins != ''){
                        $strOtherResponse = $strOtherResponse . $lastEvent->serverResponse->StrTotalWins;
                    }
                    
                    if($strWinLine != ''){
                        if($strWinLine != ''){
                            $arr_lines = explode('&', $strWinLine);
                            for($k = 0; $k < count($arr_lines); $k++){
                                $arr_sub_lines = explode('~', $arr_lines[$k]);
                                $arr_sub_lines[1] = str_replace(',', '', $arr_sub_lines[1]) / $original_bet * $bet;
                                $arr_lines[$k] = implode('~', $arr_sub_lines);
                            }
                            $strWinLine = implode('&', $arr_lines);
                        }
                        $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                    }
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 || $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0)
                    {
                        if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0) 
                        {
                            $strOtherResponse = $strOtherResponse . '&fs_total='.($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 1).'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&w=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        }
                        else
                        {
                            $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&w=0';
                        }
                        if($fsmore > 0){
                            $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                        }  
                    }
                }
                $Balance = $slotSettings->GetBalance();       
                $response = 'def_s=10,3,6,8,9,9,7,10,3,5,4,5,8,4,10&balance='. $Balance .'&cfgs=2998&ver=2&mo_s=11&index=1&balance_cash='. $Balance .'&reel_set_size=2&def_sb=5,9,3,10,3&mo_v=25,50,75,100,125,150,175,200,250,350,400,450,500,600,750,1250,2500,5000&def_sa=4,9,7,9,10&mo_jp=750;1250;2500;5000&balance_bonus=0.00&na='. $spinType.'&scatters=1~0,0,2,0,0~8,8,8,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&mo_jp_mask=jp4;jp3;jp2;jp1&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa=4,9,7,9,10&sb=5,9,3,10,3&sc='. implode(',', $slotSettings->Bet) .'&defc=100.00&sh=3&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&n_reel_set='.$currentReelSet. $strOtherResponse . '&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;500,50,25,0,0;300,40,25,0,0;200,35,20,0,0;150,30,20,0,0;50,20,10,0,0;50,20,10,0,0;50,15,10,0,0;50,15,10,0,0;0,0,0,0,0;0,0,0,0,0&l=25&rtp=95.50&reel_set0=6,4,10,9,4,5,8,7,6,9,7,11,10,11,11,9,10,6,9,5,3,10,7,4,10,5,8,6,10,9~2,9,3,7,5,9,6,5,8,9,1,10,8,5,7,4,8,11,11,11,5,9,6,10,8,1,9,7,5,8,6,9,10~2,7,6,10,8,3,7,9,1,10,4,8,6,9,11,11,11,8,6,3,9,5,8,1,10,8,3,7,9,4~2,9,8,3,4,10,8,4,9,7,1,10,1,7,4,9,7,11,11,11,10,9,6,7,4,5,8,1,9,3,10,4,8,7,3,9,7,5,10,9,3,7,4,10,6,4,7,8~2,10,9,5,10,3,9,5,8,3,4,7,5,4,10,8,5,10,4,6,7,3,8,10,11,11,11,8,4,9&s='.$lastReelStr.'&t=243&reel_set1=3,5,11,11,5,5,4,4,5,5,6,6,4,4,5,5,6,6,11,11,6,6,4,4,5,5,6,6,6,4~5,6,3,1,5,6,4,5,1,5,5,11,11,6,6,5,5,5,1,6,3,3,4,11,11,11,5,6,6,6,6,5,5,6~6,6,3,1,3,3,4,4,4,3,3,4,4,3,11,11,3,6,6,6,5,3,3,5,11,11,11,3,3,6,6,6,6~3,4,3,4,4,4,4,1,5,3,3,3,11,11,11,4,4,1,3,4,4,3,3,4,4,3,3,6,6,1,6,5~4,3,3,4,4,5,5,3,3,11,11,11,3,3,4,4,5,5,4,4,3,3,4,4,5,5,6,6,11,11,4,4';
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
                    if( $slotEvent['slotEvent'] == 'doSpin' && $slotSettings->GetBalance() < ($lines * $betline)  && $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') == -1) 
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
                
                $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $betline * $lines, $lines);
                $winType = $_spinSettings[0];
                $_winAvaliableMoney = $_spinSettings[1];

                // $winType = 'win';

                $allBet = $betline * $lines;
                $tumbAndFreeStacks = []; 
                $isGeneratedFreeStack = false;
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.1f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '628' . substr($roundstr, 3, 8). '023';
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $leftFreeGames = 0;

                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []);
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
                $str_initReel = '';
                $str_mo = '';
                $str_mo_t = '';
                $rw = 0;
                $bpw = 0;
                $str_rsb_s = '';
                $rsb_m = 0;
                $rsb_c = -1;
                $rsb_more = -1;
                $bw = 0;
                $bgt = 0;
                $end = -1;
                $str_wins = '';
                $str_status = '';
                $str_wins_mask = '';
                $fsmore = 0;
                $fsmax = 0;
                $subScatterReel = null;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $rw = str_replace(',', '', $stack['rw']);
                    $bpw = str_replace(',', '', $stack['bpw']);
                    $str_rsb_s = $stack['rsb_s'];
                    $rsb_m = $stack['rsb_m'];
                    $rsb_c = $stack['rsb_c'];
                    $rsb_more = $stack['rsb_more'];
                    $bw = $stack['bw'];
                    $bgt = $stack['bgt'];
                    $end = $stack['end'];
                    $str_wins = $stack['wins'];
                    $str_status = $stack['wins_status'];
                    $str_wins_mask = $stack['wins_mask'];
                    $strWinLine = $stack['win_line'];
                    $fsmore = $stack['fsmore'];
                    $fsmax = $stack['fsmax'];
                }else{
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
                    $rw = str_replace(',', '', $stack[0]['rw']);
                    $bpw = str_replace(',', '', $stack[0]['bpw']);
                    $str_rsb_s = $stack[0]['rsb_s'];
                    $rsb_m = $stack[0]['rsb_m'];
                    $rsb_c = $stack[0]['rsb_c'];
                    $rsb_more = $stack[0]['rsb_more'];
                    $bw = $stack[0]['bw'];
                    $bgt = $stack[0]['bgt'];
                    $end = $stack[0]['end'];
                    $str_wins = $stack[0]['wins'];
                    $str_status = $stack[0]['wins_status'];
                    $str_wins_mask = $stack[0]['wins_mask'];
                    $strWinLine = $stack[0]['win_line'];
                    $fsmore = $stack[0]['fsmore'];
                    $fsmax = $stack[0]['fsmax'];
                }
                $scatterCount = 0;
                $scatterWin = 0;
                $scatterPoses = [];
                for($k = 0; $k < 15; $k++){
                    if($lastReel[$k] == 1){
                        $scatterCount++;
                        $scatterPoses[] = $k;
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
                if($scatterCount >= 3){
                    $scatterWin = 2 * $betline * $lines;
                    $totalWin += $scatterWin;
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
                if($slotEvent['slotEvent'] != 'freespin' && $fsmax > 0){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $fsmax);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                }else if($slotEvent['slotEvent'] == 'freespin' && $fsmore > 0){
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
                        $isEnd = true;
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsend_total=1&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $strOtherResponse = $strOtherResponse . '&w='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 'c';
                    }
                    else
                    {
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $strOtherResponse = $strOtherResponse . '&w='.$totalWin;
                        $spinType = 's';
                    }
                    if($fsmore > 0){
                        $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    if($fsmax > 0){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1';
                        if($scatterCount >= 3){
                            $strOtherResponse = $strOtherResponse . '&psym=1~'. $scatterWin .'~' .  implode(',', $scatterPoses);
                        }
                    }
                    $strOtherResponse = $strOtherResponse . '&w='.$totalWin;
                }
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                }
                if($rw > 0){
                    $strOtherResponse = $strOtherResponse . '&rw=' . ($rw / $original_bet * $betline);
                }
                if($bpw > 0){
                    $strOtherResponse = $strOtherResponse . '&bpw=' . ($bpw / $original_bet * $betline);
                }
                if($str_rsb_s != ''){
                    $strOtherResponse = $strOtherResponse . '&rsb_s=' . $str_rsb_s;
                }
                if($rsb_m > 0){
                    $strOtherResponse = $strOtherResponse . '&rsb_m=' . $rsb_m . '&rsb_c=' . $rsb_c;
                }
                if($rsb_more > -1){
                    $strOtherResponse = $strOtherResponse . '&rsb_more=' . $rsb_more;
                }
                if($bw > 0){
                    $strOtherResponse = $strOtherResponse . '&bgid=0&bw=' . $bw;
                    $spinType = 'b';
                    $isState = false;
                }
                if($bgt > 0){
                    $strOtherResponse = $strOtherResponse . '&bgt=' . $bgt;
                }
                if($end >= 0){
                    $strOtherResponse = $strOtherResponse . '&end=' . $end;
                }
                $str_totalWins = '';
                if($str_wins != ''){
                    $str_totalWins = '&wins=' . $str_wins . '&status=' . $str_status . '&wins_mask=' . $str_wins_mask;
                    $strOtherResponse = $strOtherResponse . $str_totalWins;
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                }

                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse . '&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType  .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&st=rect&c='.$betline.'&sh=3&sw=5&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=25&s=' . $strLastReel;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)  && $bw == 0) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if( $slotEvent['slotEvent'] != 'freespin' && ($fsmax > 0 || $bw == 1)) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"StrTotalWins":"' . $str_totalWins  . '","TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
            }else if( $slotEvent['slotEvent'] == 'doBonus' ){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $ind = -1;
                if(isset($slotEvent['ind'])){
                    $ind = $slotEvent['ind'];
                }
                $lines = 25;
                $Balance =  $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                $isState = false;
                $spinType = 'b';
                $strOtherResponse = '';
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
                $lastReel = explode(',', $stack['reel']);
                $str_mo = $stack['mo'];
                $str_mo_t = $stack['mo_t'];
                $rw = str_replace(',', '', $stack['rw']);
                $bpw = str_replace(',', '', $stack['bpw']);
                $str_rsb_s = $stack['rsb_s'];
                $rsb_m = $stack['rsb_m'];
                $rsb_c = $stack['rsb_c'];
                $rsb_more = $stack['rsb_more'];
                $bw = $stack['bw'];
                $bgt = $stack['bgt'];
                $end = $stack['end'];
                $str_wins = $stack['wins'];
                $str_status = $stack['wins_status'];
                $str_wins_mask = $stack['wins_mask'];

                if($ind >= 0 && $str_wins != ''){
                    $arr_wins = explode(',', $str_wins);
                    $arr_status = explode(',', $str_status);
                    $arr_wins_mask = explode(',', $str_wins_mask);
                    if($ind > 0 && $ind < 3){
                        $old_wins = $arr_wins[0];
                        $old_status = $arr_status[0];
                        $old_wins_mask = $arr_status[0];
                        $arr_wins[0] = $arr_wins[$ind];
                        $arr_status[0] = $arr_status[$ind];
                        $arr_wins_mask[0] = $arr_wins_mask[$ind];
                        
                        $arr_wins[$ind] = $old_wins;
                        $arr_status[$ind] = $old_status;
                        $arr_wins_mask[$ind] = $old_wins_mask;
                    }
                    $str_wins = implode(',', $arr_wins);
                    $str_status = implode(',', $arr_status);
                    $str_wins_mask = implode(',', $arr_wins_mask);
                }
                if($rw > 0){
                    $rw = $rw / $original_bet * $betline;
                }
                if($bpw > 0){
                    $bpw = $bpw / $original_bet * $betline;
                }

                if($end == 1){
                    $totalWin = $rw;
                    if($totalWin > 0){
                        $slotSettings->SetBalance($totalWin);
                        $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);                                
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $isState = true;
                    $spinType = 'cb';
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                        if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                        {
                            $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsend_total=1&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                        }
                        else
                        {
                            $isState = false;
                            $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                            $spinType = 's';
                        }
                    }
                }
                
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                }
                if($rw > 0){
                    $strOtherResponse = $strOtherResponse . '&rw=' . $rw;
                }
                if($bpw > 0){
                    $strOtherResponse = $strOtherResponse . '&bpw=' . $bpw;
                }
                if($str_rsb_s != ''){
                    $strOtherResponse = $strOtherResponse . '&rsb_s=' . $str_rsb_s;
                }
                if($rsb_m > 0){
                    $strOtherResponse = $strOtherResponse . '&rsb_m=' . $rsb_m . '&rsb_c=' . $rsb_c;
                }
                if($rsb_more > -1){
                    $strOtherResponse = $strOtherResponse . '&rsb_more=' . $rsb_more;
                }
                if($bgt > 0){
                    $strOtherResponse = $strOtherResponse . '&bgt=' . $bgt;
                }
                if($end >= 0){
                    $strOtherResponse = $strOtherResponse . '&end=' . $end;
                }
                $str_totalWins = '';
                if($str_wins != ''){
                    $str_totalWins = '&wins=' . $str_wins . '&status=' . $str_status . '&wins_mask=' . $str_wins_mask;
                    $strOtherResponse = $strOtherResponse . $str_totalWins;
                }

                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);

                $response = 'tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&bgid=0&balance='.$Balance . $strOtherResponse .'&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='. $spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) . '&s=' . implode(',', $lastReel);
                
                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------
                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"StrTotalWins":"' . $str_totalWins . '","TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
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
    }
}

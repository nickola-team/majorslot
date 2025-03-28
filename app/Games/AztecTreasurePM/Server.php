<?php 
namespace VanguardLTE\Games\AztecTreasurePM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [9,3,11,6,6,11,5,9,11,4,6,12,4,9,9,6,8,11,9,9,1,3,11,9,9,4,3,8,4,4]);
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
                    $bet = '50.00';
                }
                $currentReelSet = 0;
                $spinType = 's';
                if(isset($stack)){    
                    if($stack['reel_set'] >= 0){
                        $currentReelSet = $stack['reel_set'];
                    }
                    $str_initReel = $stack['init_reel'];
                    $str_msr = $stack['msr'];
                    $bgt = $stack['bgt'];
                    $level = $stack['level'];
                    $lifes = $stack['lifes'];
                    $bw = $stack['bw'];
                    $end = $stack['end'];
                    $bgid = $stack['bgid'];
                    $str_wins_mask = $stack['wins_mask'];
                    $str_wins = $stack['wins'];
                    $str_status = $stack['wins_status'];
                    $n_aw_reel = $stack['n_aw_reel'];
                    $aw_p = $stack['aw_p'];
                    $aw_reel = $stack['aw_reel'];
                    $gwm = $stack['gwm'];
                    $str_prg_m = $stack['prg_m'];
                    $str_prg = $stack['prg'];
                    $fsmore = $stack['fsmore'];

                    if($bw == 1 && $end == 0){
                        $spinType = 'b';                 
                    }
                    if($bw == 1){
                        $strOtherResponse = $strOtherResponse .'&bw=1';
                    }
                    if($str_wins != ''){
                        $strOtherResponse = $strOtherResponse . '&wins=' . $str_wins . '&status='. $str_status .'&wins_mask=' . $str_wins_mask;
                    }                
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($bgt > 0){
                        $strOtherResponse = $strOtherResponse . '&bgt=' . $bgt;
                        $strOtherResponse = $strOtherResponse .'&rw=0.00&wp=0&coef='. ($bet * 20);  
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
                    if($str_msr != ''){
                        $strOtherResponse = $strOtherResponse . '&msr=' . $str_msr;
                    }
                    if($n_aw_reel > -1){
                        $strOtherResponse = $strOtherResponse . '&n_aw_reel=' . $n_aw_reel;
                    }
                    if($aw_p > -1){
                        $strOtherResponse = $strOtherResponse . '&aw_p=' . $aw_p;
                    }
                    if($aw_reel > -1){
                        $strOtherResponse = $strOtherResponse . '&aw_reel=' . $aw_reel;
                    }
                    if($gwm > 1){
                        $strOtherResponse = $strOtherResponse . '&gwm=' . $gwm;
                    }
                    if($str_prg_m != ''){
                        $strOtherResponse = $strOtherResponse . '&prg_m=' . $str_prg_m . '&prg=' . $str_prg;
                    }
                    if($fsmore > 0){
                        $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                    }
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }else{
                    $strOtherResponse = $strOtherResponse . '&msr=8~2';
                }
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0 || $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 )
                {
                    $fs = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                        $strOtherResponse = $strOtherResponse . '&fs=' . $fs . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul=1';
                    }else if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0){
                        $strOtherResponse = $strOtherResponse . '&fs_total='. ($fs - 1) .'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsend_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                } 
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));

                $Balance = $slotSettings->GetBalance();

                $response = 'msi=13~14&def_s=9,3,11,6,6,11,5,9,11,4,6,12,4,9,9,6,8,11,9,9,1,3,11,9,9,4,3,8,4,4&balance='. $Balance .'&cfgs=2444&nas=15&ver=2&index=1&balance_cash='. $Balance .'&reel_set_size=2&def_sb=5,9,3,10,3&def_sa=4,9,7,9,10&bonusInit=[{bgid:0,bgt:21,bg_i:"5,10,15,20,25",bg_i_mask:"nff,nff,nff,nff,nff"},{bgid:1,bgt:21,bg_i:"10,15,20,25",bg_i_mask:"nff,nff,nff,nff"},{bgid:2,bgt:21,bg_i:"15,20,25",bg_i_mask:"nff,nff,nff"},{bgid:3,bgt:21,bg_i:"3,4,5,10,15",bg_i_mask:"nff,nff,nff,nff,nff"},{bgid:4,bgt:21,bg_i:"4,5,10,15",bg_i_mask:"nff,nff,nff,nff"},{bgid:5,bgt:21,bg_i:"5,10,15",bg_i_mask:"nff,nff,nff"},{bgid:6,bgt:21,bg_i:"10,15",bg_i_mask:"nff,nff"}]&prg_cfg_m=rtfs_left&balance_bonus=0.00&na='. $spinType .'&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=4,9,7,9,10&sb=5,9,3,10,3&prg_cfg=0&sc='. implode(',', $slotSettings->Bet) .'&defc=50.00&aw_reel_count=6&sh=6&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&aw_reel0=m~2;m~3;m~5;m~7;m~10&aw_reel2=m~3&sver=5&aw_reel1=m~2&n_reel_set='.$currentReelSet.$strOtherResponse.'&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;80,30,15,0,0;50,25,12,0,0;30,15,10,0,0;20,12,8,0,0;20,12,8,0,0;15,10,5,0,0;10,8,5,0,0;10,8,5,0,0;8,6,4,0,0;8,6,4,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0&l=20&reel_set0=7,6,7,8,12,4,5,5,8,8,10,11,11,6,10,4,9,9,7,7,7,3,3,8,5,8,10,11,11,11,8,9,6,6,10,10,12,12,6,9,9,9,9,1,7,8,7,7,10,12,12,5,12,5,8,4,11,7,3~11,10,9,5,9,10,10,2,11,12,12,12,1,3,3,5,5,10,10,8,11,11,9,9,8,8,7,7,7,8,8,8,11,11,11,12,4,4,12,12,6,6,9,9,9,5,5,7,7,6,6,6,2,2,5,10,10,1,10,3,4,4,7,6,8,6,5,4,8,8,10,4,4~7,9,11,9,4,4,11,11,8,8,2,2,9,1,3,3,11,8,7,7,11,12,12,6,6,9,9,4,4,1,7,10,10,11,2,9,7,12,7,9,9,9,5,3,8,5,12,12,4,4,10,11,1,8,5,5,11,8,6,8,4,10,10,10,4,5,12,12,6~10,6,11,9,6,6,8,8,12,5,5,5,5,11,1,9,9,8,8,11,3,3,4,4,2,2,4,6,11,11,5,5,12,12,7,7,6,3,3,4,4,9,9,7,8,10,10,2,11,4,11,10,10,1,6,11,11,12,12,12,6,8,8,5,7,10,4~4,5,10,8,4,4,7,12,12,8,8,9,3,6,6,4,7,7,6,8,1,10,8,8,8,9,5,5,7,7,7,6,6,6,3,3,4,10,5,12,11,11,11,5,5,9,10,10,9,9,12,12,11,4,11,11,5,8&s='.$lastReelStr.'&t=243&reel_set1=7,6,7,8,12,12,12,4,9,9,5,5,8,10,11,11,6,9,10,3,3,1,6,6,10,10,11,11,11,12,6,9,9,9,9,3,7,7,8,7,11,10,12,12,5,12,5,8,4,11,7,3~11,10,9,5,5,9,10,11,11,1,7,7,9,6,8,2,2,3,8,7,7,7,8,8,12,10,10,10,4,4,12,12,6,9,9,9,5,7,2,5,10,10,4,10,12,12,12,3,4,7,6,8,6,5,4,8,8,10,4,4~7,9,11,9,4,11,11,8,8,2,9,6,11,12,3,3,4,7,1,7,8,10,10,11,9,7,2,2,6,6,6,12,7,7,10,9,5,5,5,3,8,12,12,4,4,10,9,11,8,5,5,12,11,8,6,6,8,10,4,10,5,12,6~10,6,11,9,7,6,6,8,8,11,11,10,10,10,12,3,9,9,7,4,1,6,11,11,11,5,5,8,2,12,12,7,6,4,9,9,9,7,8,3,3,10,10,11,4,11,10,6,11,12,12,12,6,8,8,11,10,5,7,11,10,4~4,5,10,8,4,4,7,10,12,12,1,8,8,9,6,6,6,7,7,9,5,12,3,6,6,6,10,7,7,7,5,12,12,11,8,8,8,5,5,9,10,9,12,11,4,11,11,5,8&aw_reel4=m~7&aw_reel3=m~5&aw_reel5=m~10';
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
                $lines = 20;      
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
                $slotEvent['slotLines'] = 20;
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    $slotEvent['slotEvent'] = 'freespin';
                }
                $lines = $slotEvent['slotLines'];
                $betline = $slotEvent['slotBet'];
                if( $slotEvent['slotEvent'] == 'doSpin' || $slotEvent['slotEvent'] == 'respin'  || $slotEvent['slotEvent'] == 'freespin') 
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
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') < $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotEvent['slotEvent'] == 'respin' ) 
                    {
                        $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"invalid bonus state"}';
                        exit( $response );
                    }
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.1f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '628' . substr($roundstr, 3, 8). '023';
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
                $str_msr = '';
                $bgt = 0;
                $level = -1;
                $lifes = -1;
                $bw = 0;
                $end = -1;
                $bgid = -1;
                $str_wins_mask = '';
                $str_wins = '';
                $str_status = '';
                $n_aw_reel = -1;
                $aw_p = -1;
                $aw_reel = -1;
                $gwm = -1;
                $str_prg_m = '';
                $str_prg = '';
                $fsmore = 0;
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $str_initReel = $stack['init_reel'];
                    $str_msr = $stack['msr'];
                    $bgt = $stack['bgt'];
                    $level = $stack['level'];
                    $lifes = $stack['lifes'];
                    $bw = $stack['bw'];
                    $end = $stack['end'];
                    $bgid = $stack['bgid'];
                    $str_wins_mask = $stack['wins_mask'];
                    $str_wins = $stack['wins'];
                    $str_status = $stack['wins_status'];
                    $n_aw_reel = $stack['n_aw_reel'];
                    $aw_p = $stack['aw_p'];
                    $aw_reel = $stack['aw_reel'];
                    $gwm = $stack['gwm'];
                    $str_prg_m = $stack['prg_m'];
                    $str_prg = $stack['prg'];
                    $fsmore = $stack['fsmore'];
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
                    $str_initReel = $stack[0]['init_reel'];
                    $str_msr = $stack[0]['msr'];
                    $bgt = $stack[0]['bgt'];
                    $level = $stack[0]['level'];
                    $lifes = $stack[0]['lifes'];
                    $bw = $stack[0]['bw'];
                    $end = $stack[0]['end'];
                    $bgid = $stack[0]['bgid'];
                    $str_wins_mask = $stack[0]['wins_mask'];
                    $str_wins = $stack[0]['wins'];
                    $str_status = $stack[0]['wins_status'];
                    $n_aw_reel = $stack[0]['n_aw_reel'];
                    $aw_p = $stack[0]['aw_p'];
                    $aw_reel = $stack[0]['aw_reel'];
                    $gwm = $stack[0]['gwm'];
                    $str_prg_m = $stack[0]['prg_m'];
                    $str_prg = $stack[0]['prg'];
                    $fsmore = $stack[0]['fsmore'];
                }
                $reels = [];
                $scatterCount = 0;
                for($i = 0; $i < 5; $i++){
                    $reels[$i] = [];
                    for($j = 0; $j < 6; $j++){
                        $reels[$i][$j] = $lastReel[$j * 5 + $i];
                    }
                }
                $_lineWinNumber = 1;
                $_obf_winCount = 0;
                
                for($r = 0; $r < 6; $r++){
                    if($reels[0][$r] != $scatter && $reels[0][$r] != 15){
                        $this->findZokbos($reels, $reels[0][$r], 1, '~'.($r * 5));
                    }                        
                }
                for($r = 0; $r < count($this->winLines); $r++){
                    $winLine = $this->winLines[$r];
                    $winLineMoney = $slotSettings->Paytable[$winLine['FirstSymbol']][$winLine['RepeatCount']] * $betline;
                    if($gwm > 1){
                        $winLineMoney = $winLineMoney * $gwm;
                    }
                    if($winLineMoney > 0){   
                        $isNewTumb = true;
                        $strWinLine = $strWinLine . '&l'. $r.'='.$r.'~'.$winLineMoney . $winLine['StrLineWin'];
                        $totalWin += $winLineMoney;
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
                $isState = true;
                $isEnd = true;
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $spinType = 's';
                    $isEnd = false;
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                    {
                        $spinType = 'c';
                        $isEnd = true;
                        $strOtherResponse = '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fsmul_total=1&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres_total='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                    else
                    {
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 's';
                    }
                    if($fsmore > 0){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                        $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
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
                    $strOtherResponse = $strOtherResponse . '&wins=' . $str_wins . '&status='. $str_status .'&wins_mask=' . $str_wins_mask;
                }                
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($currentReelSet >= 0){
                    $strOtherResponse = $strOtherResponse . '&n_reel_set=' . $currentReelSet;
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
                }
                if($bgid > -1){
                    $strOtherResponse = $strOtherResponse . '&bgid=' . $bgid;
                }
                if($str_msr != ''){
                    $strOtherResponse = $strOtherResponse . '&msr=' . $str_msr;
                }
                if($n_aw_reel > -1){
                    $strOtherResponse = $strOtherResponse . '&n_aw_reel=' . $n_aw_reel;
                }
                if($aw_p > -1){
                    $strOtherResponse = $strOtherResponse . '&aw_p=' . $aw_p;
                }
                if($aw_reel > -1){
                    $strOtherResponse = $strOtherResponse . '&aw_reel=' . $aw_reel;
                }
                if($gwm > 1){
                    $strOtherResponse = $strOtherResponse . '&gwm=' . $gwm;
                }
                if($str_prg_m != ''){
                    $strOtherResponse = $strOtherResponse . '&prg_m=' . $str_prg_m . '&prg=' . $str_prg;
                }
                
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse . $strWinLine .'&balance='.$Balance. '&index='.$slotEvent['index'].'&c='.$betline.'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=' . $strReelSa . '&sb=' . $strReelSb .'&sh=6&c='. $betline .'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&s='.$strLastReel.'&w='.$totalWin;
                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 && $bw == 0) 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'). ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
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
                while(true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    if($slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') >= count($freeStacks) || $stack['wins'] != ''){
                        break;
                    }
                }                
                $lastReel = $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'); 
                $str_msr = $stack['msr'];
                $bgt = $stack['bgt'];
                $level = $stack['level'];
                $lifes = $stack['lifes'];
                $bw = $stack['bw'];
                $end = $stack['end'];
                $bgid = $stack['bgid'];
                $str_wins_mask = $stack['wins_mask'];
                $str_wins = $stack['wins'];
                $str_status = $stack['wins_status'];
                $n_aw_reel = $stack['n_aw_reel'];
                $aw_p = $stack['aw_p'];
                $aw_reel = $stack['aw_reel'];
                $gwm = $stack['gwm'];
                $str_prg_m = $stack['prg_m'];
                $str_prg = $stack['prg'];
                $fsmore = $stack['fsmore'];
                $totalWin = 0;
                $spinType = 's';
                $coef = $betline * $lines;
                $rw = 0;
                $isState = false;
                $strOtherResponse = '';
                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0){
                    $arr_wins = explode(',', $str_wins);
                    $arr_status = explode(',', $str_status);                
                    $freeNum = 0;
                    for($k = 0; $k < count($arr_wins); $k++){
                        if($arr_status[$k] == 1){
                            $freeNum = $arr_wins[$k];
                            break;
                        }
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freeNum);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                }
                $strOtherResponse = '&fsmul=1&wins='. $str_wins .'&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&status='. $str_status .'&rw=0.00&wins_mask='. $str_wins_mask .'&fswin=0.00&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') .'&wp=0&fsres=0.00';
                
                if($fsmore > 0){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                    $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
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
                }
                if($bgid > -1){
                    $strOtherResponse = $strOtherResponse . '&bgid=' . $bgid;
                }
                if($str_msr != ''){
                    $strOtherResponse = $strOtherResponse . '&msr=' . $str_msr;
                }
                if($n_aw_reel > -1){
                    $strOtherResponse = $strOtherResponse . '&n_aw_reel=' . $n_aw_reel;
                }
                if($aw_p > -1){
                    $strOtherResponse = $strOtherResponse . '&aw_p=' . $aw_p;
                }
                if($aw_reel > -1){
                    $strOtherResponse = $strOtherResponse . '&aw_reel=' . $aw_reel;
                }
                if($gwm > 1){
                    $strOtherResponse = $strOtherResponse . '&gwm=' . $gwm;
                }
                if($str_prg_m != ''){
                    $strOtherResponse = $strOtherResponse . '&prg_m=' . $str_prg_m . '&prg=' . $str_prg;
                }
                $response = 'balance='. $Balance .'&index='.$slotEvent['index'].'&balance_cash='. $Balance .'&balance_bonus=0.00&na='. $spinType . $strOtherResponse .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sver=5&counter='. ((int)$slotEvent['counter'] + 1);

                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'). ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
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
        public function findZokbos($reels, $firstSymbol, $repeatCount, $strLineWin){
            $wild = '2';
            $bPathEnded = true;
            if($repeatCount < 5){
                for($r = 0; $r < 6; $r++){
                    if($firstSymbol == $reels[$repeatCount][$r] || $reels[$repeatCount][$r] == $wild){
                        $this->findZokbos($reels, $firstSymbol, $repeatCount + 1, $strLineWin . '~' . ($repeatCount + $r * 5));
                        $bPathEnded = false;
                    }
                }
            }
            if($bPathEnded == true){
                if($repeatCount >= 3){
                    $winLine = [];
                    $winLine['FirstSymbol'] = $firstSymbol;
                    $winLine['RepeatCount'] = $repeatCount;
                    $winLine['StrLineWin'] = $strLineWin;
                    array_push($this->winLines, $winLine);
                }
            }
        }
    }
}


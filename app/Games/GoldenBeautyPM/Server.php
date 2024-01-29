<?php 
namespace VanguardLTE\Games\GoldenBeautyPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 25);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [11,13,14,10,12,8,7,5,13,7,13,9,15,14,11,14,9,15,7,10]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks

                $slotSettings->SetGameData($slotSettings->slotId . 'SpinType', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeSpinType', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'FscSessions', []);
                $slotSettings->SetGameData($slotSettings->slotId . 'FscWinTotal', []);
                $slotSettings->SetGameData($slotSettings->slotId . 'FscTotal', []);
                $slotSettings->SetGameData($slotSettings->slotId . 'Accv_No', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Accv', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'StackIndex', []);

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
                                        
                    $slotSettings->SetGameData($slotSettings->slotId . 'SpinType', $lastEvent->serverResponse->SpinType);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeSpinType', $lastEvent->serverResponse->FreeSpinType);
                    if($lastEvent->serverResponse->FscSessions != ''){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FscSessions', explode(',', $lastEvent->serverResponse->FscSessions));
                    }
                    if($lastEvent->serverResponse->FscWinTotal != ''){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FscWinTotal', explode(',', $lastEvent->serverResponse->FscWinTotal));
                    }
                    if($lastEvent->serverResponse->FscTotal != ''){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FscTotal', explode(',', $lastEvent->serverResponse->FscTotal));
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'Accv_No', $lastEvent->serverResponse->Accv_No);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Accv', $lastEvent->serverResponse->Accv);
                    if (isset($lastEvent->serverResponse->StackIndex)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'StackIndex', json_decode(json_encode($lastEvent->serverResponse->StackIndex), true)); //ReplayLog
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
                if(isset($stack)){
                    $str_InitReel = $stack['initReel'];
                    $str_accm = $stack['accm'];
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                        $str_accv = $slotSettings->GetGameData($slotSettings->slotId . 'Accv');
                    }else{
                        $str_accv = $stack['accv'];
                    }                    
                    $str_rwd = $stack['rwd'];
                    $str_fs_opt = $stack['fs_opt'];
                    $str_fs_opt_mask = $stack['fs_opt_mask'];
                    $fsmore = $stack['fsmore'];
                    $currentReelSet = $stack['reel_set'];
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    if($str_InitReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_InitReel;
                    }
                    if($str_accv != ''){
                        $strOtherResponse = $strOtherResponse . '&accv=' . $str_accv . '&acci=0&accm=' . $str_accm;
                    }
                    if($str_rwd != ''){
                        $strOtherResponse = $strOtherResponse . '&rwd=' . $str_rwd;
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0 && $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0){
                        $strOtherResponse = $strOtherResponse . '&fs_total='.($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 1).'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsend_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }else if($str_fs_opt != ''){
                        $strOtherResponse = $strOtherResponse . '&fs_opt_mask=' . $str_fs_opt_mask . '&fs_opt=' . $str_fs_opt;
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'SpinType') > 0){                        
                        $spinType = 'fso';
                    }
                }else{
                    $strOtherResponse = '&accm=cp~tp&acci=0&accv=0~10';
                }
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) || count($slotSettings->GetGameData($slotSettings->slotId . 'FscSessions')) > 0 ) 
                {
                    $strOtherResponse = $strOtherResponse . '&fs=' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&w=0.00&fsmul=1';
                    $fscWinTotal = $slotSettings->GetGameData($slotSettings->slotId . 'FscWinTotal');
                    $fscTotal = $slotSettings->GetGameData($slotSettings->slotId . 'FscTotal');
                    $fscMuls = [];
                    for($k = 0; $k < count($fscWinTotal); $k++){
                        $fscMuls[] = 1;
                    }
                    if(count($fscWinTotal) > 0){
                        $strOtherResponse = $strOtherResponse . '&fsc_win_total='. implode(',', $fscWinTotal).'&fsc_res_total='. implode(',', $fscWinTotal) .'&fsc_mul_total='. implode(',', $fscMuls) .'&fsc_total=' . implode(',', $fscTotal);
                    }
                    if(count($slotSettings->GetGameData($slotSettings->slotId . 'FscSessions')) > 0){
                        $strOtherResponse = $strOtherResponse . '&fsc_sessions=' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'FscSessions'));
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeSpinType') >= 0){
                        $strOtherResponse = $strOtherResponse . '&fsopt_i=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeSpinType');
                    }
                }
                $Balance = $slotSettings->GetBalance();    
                $response = 'def_s=11,13,14,10,12,8,7,5,13,7,13,9,15,14,11,14,9,15,7,10&balance='. $Balance .'&cfgs=2737&ver=2&index=1&balance_cash='. $Balance .'&reel_set_size=4&def_sb=15,3,4,6,7&def_sa=11,12,14,8,9&balance_bonus=0.00&na='. $spinType .'&scatters=1~0,0,0,0,0,0~0,0,0,0,0~1,1,1,1,1,1&gmb=0,0,0&rt=d&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime='. floor(microtime(true) * 1000) .'&sa=11,12,14,8,9&sb=15,3,4,6,7&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=40.00&sh=4&wilds=2~70,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='. $bet .'&sver=5&n_reel_set='. $currentReelSet .'&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;70,15,5,1,0;30,8,4,0,0;30,8,4,0,0;25,6,3,0,0;25,6,3,0,0;20,4,2,0,0;20,4,2,0,0;8,2,1,0,0;8,2,1,0,0;8,2,1,0,0;8,2,1,0,0;8,2,1,0,0&l=25&reel_set0=15,15,15,15,14,14,14,14,10,10,10,10,12,12,12,12,14,8,8,8,8,4,4,4,4,11,11,11,11,13,13,13,13,14,7,7,7,7,12,3,3,3,3,15,12,12,8,9,9,9,9,10,8,12,9,8,4,13,4,12,7,12,9,15,11,1,13,11,13,11,10,14,7,15,5,5,5,5,13,3,9,3,6,6,6,6,13,13,14,11,5,14,14,10,15,15~12,12,12,12,9,9,9,9,10,10,10,10,12,10,14,14,14,14,13,13,13,13,12,15,15,15,15,3,3,3,12,6,6,6,6,8,8,8,8,5,5,5,5,7,7,7,7,7,14,12,6,13,13,14,14,11,11,11,11,9,11,11,10,8,8,15,11,12,15,9,11,9,15,15,4,4,4,4,14,3,7,10,3,4,1,13,7,13,11~12,12,12,12,11,11,11,11,8,8,8,8,6,6,6,6,4,4,4,4,13,13,13,13,10,10,10,10,11,15,15,15,15,7,7,7,7,11,11,15,13,10,3,3,3,5,5,5,5,11,13,12,8,14,14,14,14,12,6,6,5,9,9,9,9,15,14,3,12,9,13,1,14,15,11,7,9,13,5,9,4,13,8,7,12,15,3,9,14,8,4,14,13,12~15,15,15,15,9,9,9,9,14,14,14,14,8,8,8,8,13,13,13,13,11,11,11,11,9,8,12,12,12,12,6,6,6,6,11,15,11,14,12,7,7,7,7,15,13,13,5,5,5,5,7,12,14,12,4,4,4,4,8,1,14,10,10,10,10,15,10,13,3,3,3,6,10,5,8,3,5,6,9,15,11,6,14,9,14,11,13,7,12,15,11,10,12,7,13,3,4~6,6,6,6,11,11,11,11,8,8,8,8,13,13,13,12,12,12,12,10,10,10,10,8,13,14,14,14,14,5,5,5,5,15,15,15,15,5,12,6,15,13,15,9,9,9,9,10,11,14,5,3,3,3,3,11,12,8,13,10,14,9,9,7,7,7,7,10,3,7,15,5,3,4,4,4,4,12,14,5,12,11,15,1,11,15,1,14,6,7,13,13,6,4,12&s='.$lastReelStr.'&accInit=[{id:0,mask:"cp;tp;s;sp"}]&reel_set2=15,13,5,8,11,13,10,9,15,8,14,4,4,4,4,10,6,11,12,12,7,15,8,1,11,12,11,13,14,13,5,14,13,10,7,14,7,13,14,12,12,15,15,9,15,13,11,12,1,9,15,11,12,14,14,11~13,14,9,6,12,14,12,7,14,4,4,4,4,11,8,10,12,13,8,11,9,9,6,13,10,4,14,7,15,1,10,11,8,11,13,14,15,15,8,13,9,5,11,9,8,5,15,12,14,6,7,10,12,10,12~15,13,12,12,10,6,14,14,15,5,15,11,11,10,10,15,13,10,9,15,11,1,11,13,8,4,4,4,4,6,14,13,8,14,5,6,5,10,12,10,4,7,13,13,12,9,9,8,13,7,13,12,7,1,7,11,14,12,14,11,8,9~13,13,11,9,14,11,13,13,9,11,9,7,15,14,12,12,11,12,10,15,10,11,10,15,9,14,14,7,6,10,13,10,15,13,13,14,5,7,6,14,4,4,4,4,8,11,12,12,13,10,15,8,7,8,11,1,8,12,15,12~15,12,13,10,1,12,8,8,13,9,7,10,12,11,5,9,13,14,8,14,14,9,11,7,8,9,11,11,15,15,12,12,6,13,15,15,5,14,1,14,15,13,4,4,4,4,11,12,10,14,14,12,11,11,10,13,8,7,13&reel_set1=8,8,1,4,4,4,4,12,8,11,14,12,9,9,14,11,9,9,8,13,14,12,13,13,14,5,12,14,12,7,13,13,7,15,13,12,4,14,10,6,15,11,7,10,15,8,10,10,6,1,10,12,13,13,5,12,11,11,15~14,4,4,4,4,14,8,15,12,10,14,9,7,15,13,14,10,13,1,4,7,5,11,9,15,11,13,5,12,13,6,15,9,13,11,4,6,9,12,8,12,8,15,12,14,13,12,11,5,13,10,15,8,7,11,14,10,8,13~15,6,15,5,8,13,12,12,14,14,12,13,13,11,12,7,7,8,6,8,7,11,10,5,4,4,4,4,8,8,13,10,13,13,11,10,1,9,7,7,15,9,13,13,14,12,11,15,14,1,10,8,12,9,6,15,9,10,9,4~8,13,12,10,11,1,14,6,13,12,9,10,9,14,15,4,4,4,4,13,7,10,8,7,13,6,8,5,9,5,9,11,14,6,11,7,7,11,12,13,11,1,9,8,14,10,12,14,10,13,13,14,15,10,12,12,15,15,11~15,11,12,7,10,8,14,12,11,13,15,9,7,15,5,10,12,13,8,13,10,12,14,13,8,12,9,9,12,13,11,11,10,7,4,4,4,4,11,9,14,9,6,12,14,10,1,15,8,11,5,8,15,14,13,15,15,9&reel_set3=10,14,14,9,15,11,5,12,13,15,15,13,9,6,14,1,8,11,8,11,12,11,12,4,4,4,4,11,14,12,12,11,13,14,8,7,15,10,14,10,5,12,11,12,1,13,12,13,14,13,13,6,15,7,13,7,4~15,13,1,12,15,13,7,13,7,11,14,7,5,13,11,4,4,4,4,15,15,10,15,11,14,10,14,11,11,12,10,6,9,11,8,13,15,12,11,5,14,12,10,14,9,6,9,12,9,14,8,14,12,13,13,12,9,8,8,4~10,15,8,7,7,13,12,10,1,9,4,4,4,4,15,13,8,5,9,12,8,15,14,11,10,11,15,15,13,12,11,9,12,14,5,14,12,6,7,7,15,14,11,6,10,13,11,9,10,1,9,4,6,10,13,14,12,12~12,12,6,14,11,13,9,15,9,14,13,8,11,5,11,13,10,13,13,8,6,14,12,13,12,9,7,10,12,15,15,7,10,12,1,11,15,4,4,4,4,14,7,14,13,11,11,9,10,14,8,15,8,5,10,12,10,14~11,14,11,11,7,10,15,15,4,4,4,4,1,12,5,15,11,8,14,1,12,14,14,15,14,4,9,12,13,6,12,9,12,13,10,8,12,7,10,8,14,13,14,11,5,8,9,9,12,15,15,11,11,9,14,15,10';
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
                $linesId = [];
                $linesId[0] = [1,1,1,1,1];
                $linesId[1] = [2,2,2,2,2];
                $linesId[2] = [2,3,4,3,2];
                $linesId[3] = [3,3,3,3,3];
                $linesId[4] = [4,4,4,4,4];
                $linesId[5] = [1,2,3,4,4];
                $linesId[6] = [2,1,1,1,2];
                $linesId[7] = [3,2,1,2,3];
                $linesId[8] = [4,3,2,1,1];
                $linesId[9] = [3,4,4,4,3];                
                $linesId[10] = [1,1,2,1,1];
                $linesId[11] = [2,2,3,3,2];
                $linesId[12] = [2,2,3,2,2];
                $linesId[13] = [3,3,2,3,3];
                $linesId[14] = [4,4,3,4,4];
                $linesId[15] = [1,2,3,2,1];
                $linesId[16] = [1,1,1,2,1];
                $linesId[17] = [3,3,2,1,2];
                $linesId[18] = [4,3,2,3,4];
                $linesId[19] = [4,4,4,3,4];                
                $linesId[20] = [1,2,1,2,1];
                $linesId[21] = [1,1,2,2,1];
                $linesId[22] = [2,3,3,2,2];
                $linesId[23] = [4,3,4,3,4];
                $linesId[24] = [4,4,3,3,4];
                $linesId[25] = [2,1,2,1,2];
                $linesId[26] = [2,2,1,2,2];
                $linesId[27] = [3,2,2,3,3];
                $linesId[28] = [3,3,4,3,3];
                $linesId[29] = [3,4,3,4,3];                
                $linesId[30] = [1,2,2,2,1];
                $linesId[31] = [2,3,2,1,2];
                $linesId[32] = [3,2,3,4,3];
                $linesId[33] = [4,4,3,2,1];
                $linesId[34] = [4,3,3,3,4];
                $linesId[35] = [1,1,3,1,1];
                $linesId[36] = [1,1,2,3,4];
                $linesId[37] = [1,2,2,1,1];
                $linesId[38] = [4,3,3,4,4];
                $linesId[39] = [4,4,2,4,4];                
                $linesId[40] = [1,2,1,1,1];
                $linesId[41] = [2,3,2,3,2];
                $linesId[42] = [2,3,3,3,2];
                $linesId[43] = [3,2,2,2,3];
                $linesId[44] = [4,3,4,4,4];
                $linesId[45] = [2,1,2,3,2];
                $linesId[46] = [2,2,4,2,2];
                $linesId[47] = [3,2,3,2,3];
                $linesId[48] = [3,4,3,2,3];
                $linesId[49] = [3,3,1,3,3];                
                $linesId[50] = [2,2,2,1,2];
                $linesId[51] = [2,1,2,2,2];
                $linesId[52] = [2,3,2,2,2];
                $linesId[53] = [3,2,3,3,3];
                $linesId[54] = [3,4,3,3,3];
                $linesId[55] = [2,2,2,2,1];
                $linesId[56] = [2,2,2,3,2];
                $linesId[57] = [3,3,3,3,4];
                $linesId[58] = [3,3,3,4,3];
                $linesId[59] = [3,3,3,2,3];                
                $linesId[60] = [1,2,3,4,3];
                $linesId[61] = [1,2,2,2,2];
                $linesId[62] = [2,3,4,3,4];
                $linesId[63] = [3,2,1,2,1];
                $linesId[64] = [4,3,2,1,2];
                $linesId[65] = [2,1,1,1,1];
                $linesId[66] = [2,3,3,3,3];
                $linesId[67] = [3,2,2,2,2];
                $linesId[68] = [3,4,4,4,4];
                $linesId[69] = [4,3,3,3,3];                
                $linesId[70] = [1,2,2,2,3];
                $linesId[71] = [2,1,2,3,4];
                $linesId[72] = [3,2,2,2,1];
                $linesId[73] = [2,3,3,3,4];
                $linesId[74] = [4,3,3,3,2];

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
                $stackIndexes = $slotSettings->GetGameData($slotSettings->slotId . 'StackIndex');
                if($lastEvent != 'NULL' && $lastEvent->serverResponse->bet != $betline){
                    if(isset($stackIndexes[$betline])){
                        $stacks = $slotSettings->GetIndexReelStrips($stackIndexes[$betline][0]);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stacks);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $stackIndexes[$betline][1]);
                        if($stackIndexes[$betline][2] == 10){
                            $slotSettings->SetGameData($slotSettings->slotId . 'Accv_No', 0);
                        }else{
                            $slotSettings->SetGameData($slotSettings->slotId . 'Accv_No', $stackIndexes[$betline][2]);
                        }
                    }else{
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'Accv_No', 0);
                        $stackIndexes[$betline] = [0,0,0];
                    }
                }

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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'SpinType', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeSpinType', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FscSessions', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FscWinTotal', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FscTotal', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '561' . substr($roundstr, 4, 10);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $leftFreeGames = 0;
                    if($slotSettings->GetGameData($slotSettings->slotId . 'Accv_No') == 0){
                        $slotSettings->SetGameData($slotSettings->slotId . 'Accv', '');
                        // $slotSettings->SetGameData($slotSettings->slotId . 'StackIndex', [0,0,0]); // DB_id, TotalSpinCount, Accv_No
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    }else{
                        $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                    }
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
                $str_InitReel = '';
                $str_accm = '';
                $str_accv = 0;
                $str_rwd = '';
                $str_fs_opt_mask = '';
                $str_fs_opt = '';
                $currentReelSet = 0;
                $fsmore = 0;
                $accv_no = 0;
                $fscSessions = $slotSettings->GetGameData($slotSettings->slotId . 'FscSessions');
                if($slotEvent['slotEvent'] == 'freespin' || $slotSettings->GetGameData($slotSettings->slotId . 'Accv_No') > 0){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_InitReel = $stack['initReel'];
                    $str_accm = $stack['accm'];
                    if($slotEvent['slotEvent'] == 'freespin'){
                        $str_accv = $slotSettings->GetGameData($slotSettings->slotId . 'Accv');
                    }else{
                        $str_accv = $stack['accv'];
                    }                    
                    $str_rwd = $stack['rwd'];
                    $str_fs_opt = $stack['fs_opt'];
                    $str_fs_opt_mask = $stack['fs_opt_mask'];
                    $fsmore = $stack['fsmore'];
                    $currentReelSet = $stack['reel_set'];
                }else{
                
                    $_spinSettings = $slotSettings->GetSpinSettings('doSpin', $betline * $lines, $lines);
                    $winType = $_spinSettings[0];
    
                    // $winType = 'bonus';
                    $reelStrip = $slotSettings->GetReelStrips($winType, $betline * $lines);
                    $stack = $reelStrip[1];
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $stackIndexes[$betline] = [$reelStrip[0], 1, 0];
                    $lastReel = explode(',', $stack[0]['reel']);
                    $str_InitReel = $stack[0]['initReel'];
                    $str_accm = $stack[0]['accm'];
                    $str_accv = $stack[0]['accv'];                   
                    $str_rwd = $stack[0]['rwd'];
                    $str_fs_opt = $stack[0]['fs_opt'];
                    $str_fs_opt_mask = $stack[0]['fs_opt_mask'];
                    $fsmore = $stack[0]['fsmore'];
                    $currentReelSet = $stack[0]['reel_set'];
                }
                $reels = [];
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;
                for($i = 0; $i < 5; $i++){
                    $reels[$i] = [];
                    for($j = 0; $j < 4; $j++){
                        $reels[$i][$j] = $lastReel[$j * 5 + $i];
                        if($lastReel[$j * 5 + $i] == 1){
                            $scatterCount++;
                        }
                    }
                }
                if($slotEvent['slotEvent'] == 'freespin'){
                    $accv_no = $slotSettings->GetGameData($slotSettings->slotId . 'Accv_No');
                }else{
                    if($str_accv != ''){
                        $arr_accv = explode('~', $str_accv);
                        $accv_no = $arr_accv[0];
                    }
                    $stackIndexes[$betline][0] = $stackIndexes[$betline][0];
                    $stackIndexes[$betline][1] = $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount');
                    $stackIndexes[$betline][2] = $accv_no;
                    $slotSettings->SetGameData($slotSettings->slotId . 'StackIndex', $stackIndexes);
                    if($accv_no == 10 && $str_InitReel != ''){
                        $initReels = explode(',', $str_InitReel);
                        $scatterCount = 0;
                        for($k = 0; $k < 20; $k++){
                            if($initReels[$k] == 1){
                                $scatterCount++;
                            }
                        }
                    }
                }

                $_lineWinNumber = 1;
                $_obf_winCount = 0;
                for( $k = 0; $k < 75; $k++ ) 
                {
                    $_lineWin = '';
                    $firstEle = $reels[0][$linesId[$k][0] - 1];
                    $lineWinNum[$k] = 1;
                    $lineWins[$k] = 0;
                    $mul = 0;
                    for($j = 1; $j < 5; $j++){
                        $ele = $reels[$j][$linesId[$k][$j] - 1];
                        if($firstEle == $wild){
                            $firstEle = $ele;
                            $lineWinNum[$k] = $lineWinNum[$k] + 1;
                            if($j == 4){
                                $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                if($lineWins[$k] > 0){
                                    $totalWin += $lineWins[$k];
                                    $_obf_winCount++;
                                    $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                    for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                        $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                    }
                                }
                            }
                        }else if($ele == $firstEle || $ele == $wild){
                            $lineWinNum[$k] = $lineWinNum[$k] + 1;
                            if($j == 4){
                                $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                if($lineWins[$k] > 0){
                                    $totalWin += $lineWins[$k];
                                    $_obf_winCount++;
                                    $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                    for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                        $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                    }
                                }
                            }
                        }else{
                            if($slotSettings->Paytable[$firstEle][$lineWinNum[$k]] > 0){
                                $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                if($lineWins[$k] > 0){
                                    $totalWin += $lineWins[$k];
                                    $_obf_winCount++;
                                    $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                    for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                        $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                    }   
                                }

                            }else{
                                $lineWinNum[$k] = 0;
                            }
                            break;
                        }
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
                $isState = true;
                // if($scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin'){
                //     $freespins = [0,0,0,10,12,15];
                //     $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespins[$scatterCount]);
                //     $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                //     $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                // }
                // if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                //     $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                // }
                if( $scatterCount >= 3 ) 
                {
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                    {
                        array_push($fscSessions, $scatterCount);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FscSessions', $fscSessions);
                    }
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
                    $fscWinTotal = $slotSettings->GetGameData($slotSettings->slotId . 'FscWinTotal');
                    $fscTotal = $slotSettings->GetGameData($slotSettings->slotId . 'FscTotal');
                    $fscMuls = [];
                    for($k = 0; $k < count($fscWinTotal); $k++){
                        $fscMuls[] = 1;
                    }
                    $isEnd = false;
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                    {
                        array_push($fscWinTotal, $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin'));
                        array_push($fscTotal, $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames'));
                        array_push($fscMuls, 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FscWinTotal', $fscWinTotal);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FscTotal', $fscTotal);
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsend_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        if(count($fscSessions) > 0){
                            $isState = false;
                            $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                            $strOtherResponse = $strOtherResponse . '&fsdss=1';
                            $slotSettings->SetGameData($slotSettings->slotId . 'SpinType', $fscSessions[0]);
                            array_shift($fscSessions);
                            $slotSettings->SetGameData($slotSettings->slotId . 'FscSessions', $fscSessions);
                            $spinType = 'fso';
                        }else{
                            $spinType = 'c';
                            $stacks = $slotSettings->GetIndexReelStrips($stackIndexes[$betline][0]);
                            $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stacks);
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $stackIndexes[$betline][1]);
                            $slotSettings->SetGameData($slotSettings->slotId . 'Accv_No', $stackIndexes[$betline][2]);
                            $accv_no = $slotSettings->GetGameData($slotSettings->slotId . 'Accv_No');
                        }
                    }
                    else
                    {
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 's';
                    }
                    if($fsmore > 0){
                        $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                    }
                    if(count($fscSessions) > 0){
                        $strOtherResponse = $strOtherResponse . '&fsc_sessions=' . implode(',', $fscSessions);
                    }
                    if(count($fscWinTotal) > 0){
                        $strOtherResponse = $strOtherResponse . '&fsc_win_total='. implode(',', $fscWinTotal).'&fsc_res_total='. implode(',', $fscWinTotal) .'&fsc_mul_total='. implode(',', $fscMuls) .'&fsc_total=' . implode(',', $fscTotal);
                    }
                    if($scatterCount >= 3 ){
                        $strOtherResponse = $strOtherResponse . '&fsc_sw=1';
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeSpinType') >= 0){
                        $strOtherResponse = $strOtherResponse . '&fsopt_i=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeSpinType');
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    if($scatterCount >=3){
                        $isState = false;
                        $spinType = 'fso';
                        $slotSettings->SetGameData($slotSettings->slotId . 'SpinType', $scatterCount);
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'Accv_No', $accv_no);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Accv', $str_accv);
                }
                if($str_InitReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_InitReel;
                }
                if($str_accv != ''){
                    $strOtherResponse = $strOtherResponse . '&accv=' . $str_accv . '&acci=0&accm=' . $str_accm;
                }
                if($str_rwd != ''){
                    $strOtherResponse = $strOtherResponse . '&rwd=' . $str_rwd;
                }
                if($str_fs_opt != ''){
                    $strOtherResponse = $strOtherResponse . '&fs_opt_mask=' . $str_fs_opt_mask . '&fs_opt=' . $str_fs_opt;
                }
                if($accv_no == 10){
                    $slotSettings->SetGameData($slotSettings->slotId . 'Accv_No', 0);
                }

                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse . $strWinLine .'&ls=0&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&n_reel_set='. $currentReelSet .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=4&c='.$betline .'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=25&w='.$totalWin.'&s=' . $strLastReel;
                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 && $spinType != 'fso') 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if( $slotEvent['slotEvent'] != 'freespin' && $scatterCount >= 3) 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"SpinType":' . $slotSettings->GetGameData($slotSettings->slotId . 'SpinType') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"FreeSpinType":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeSpinType') . ',"Accv":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Accv') . '","Accv_No":' . $slotSettings->GetGameData($slotSettings->slotId . 'Accv_No') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"FscSessions":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'FscSessions')) . '","FscWinTotal":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'FscWinTotal')) . '","FscTotal":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'FscTotal')) . '","StackIndex":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'StackIndex')) . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
            }else if( $slotEvent['slotEvent'] == 'doFSOption' ){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lines = 25;
                $lastReel = $lastEvent->serverResponse->LastReel; 
                $Balance =  $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                $ind = -1;
                if(isset($slotEvent['ind'])){
                    $ind = $slotEvent['ind'];
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeSpinType', $ind);
                }
                $scatterCount = $slotSettings->GetGameData($slotSettings->slotId . 'SpinType');
                if($scatterCount == 0){
                    $response = 'unlogged';
                    exit( $response );
                }
                $isState = false;
                $reelStrip = $slotSettings->GetReelStrips('bonus', $betline * $lines, $scatterCount , $ind);
                $stack = $reelStrip[1];
                if($stack == null){
                    $response = 'unlogged';
                    exit( $response );
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'SpinType', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                $str_accm = $stack[0]['accm'];
                $str_accv = $slotSettings->GetGameData($slotSettings->slotId . 'Accv');                   
                $str_fs_opt = $stack[0]['fs_opt'];
                $str_fs_opt_mask = $stack[0]['fs_opt_mask'];
                $currentReelSet = $stack[0]['reel_set'];

                $arr_fs_opt = explode('~', $str_fs_opt);
                $freespin = explode(',', $arr_fs_opt[$ind]);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespin[0]);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);

                $strOtherResponse = '';
                if($str_accv != ''){
                    $strOtherResponse = $strOtherResponse . '&accv=' . $str_accv . '&acci=0&accm=' . $str_accm;
                }

                $response = 'fsmul=1&ls=0&fs_opt_mask=fs,m,wn&balance='.$Balance . $strOtherResponse . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na=s&fswin=0.00&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&fs=1&fs_opt='. $str_fs_opt .'&fsres=0.00&sver=5&n_reel_set='. $currentReelSet .'&counter='. ((int)$slotEvent['counter'] + 1) . '&fsopt_i=' . $ind;
                
                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------
                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"SpinType":' . $slotSettings->GetGameData($slotSettings->slotId . 'SpinType') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"FreeSpinType":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeSpinType') . ',"Accv":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Accv') . '","Accv_No":' . $slotSettings->GetGameData($slotSettings->slotId . 'Accv_No') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"FscSessions":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'FscSessions')) . '","FscWinTotal":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'FscWinTotal')) . '","FscTotal":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'FscTotal')) . '","StackIndex":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'StackIndex')) . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), 'WheelBonus', $isState);
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

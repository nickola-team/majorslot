<?php 
namespace VanguardLTE\Games\WildSpellsPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'FsOPT', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeSpinType', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 25);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [9,6,3,4,5,4,8,6,9,8,5,3,8,8,7]);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'FsOPT', $lastEvent->serverResponse->FsOPT);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeSpinType', $lastEvent->serverResponse->FreeSpinType);
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
                    $bet = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $bet = '40.00';
                }
                $spinType = 's';
                $fsmore = 0;
                $fsmul = 1;
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if(isset($stack)){
                    $fsmore = $stack['fsmore'];
                    $str_fs_opt = $stack['fs_opt'];
                    $str_fs_opt_mask = $stack['fs_opt_mask'];
                    $wp = $stack['wp'];
                    $bw = $stack['bw'];
                    $str_bg_i = $stack['bg_i'];
                    $str_bg_i_mask = $stack['bg_i_mask'];
                    $strWinLine = $stack['win_line'];
                    if($stack['reel_set'] >= 0){
                        $currentReelSet = $stack['reel_set'];
                    }
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    if($str_bg_i != ''){
                        $strOtherResponse = $strOtherResponse . '&bg_i=' . $str_bg_i . '&bg_i_mask=' . $str_bg_i_mask;
                    }
                    if($bw == 1 && $wp > 0){
                        $strOtherResponse = $strOtherResponse . '&coef='. ($bet * 25) .'&rw='. ($bet * 25 * $wp) .'&bw=1&wp='. $wp .'&end=1';
                    }
                    if($strWinLine != ''){
                        $arr_lines = explode('&', $strWinLine);
                        for($k = 0; $k < count($arr_lines); $k++){
                            $arr_sub_lines = explode('~', $arr_lines[$k]);
                            $arr_sub_lines[1] = str_replace(',', '', $arr_sub_lines[1]) / $original_bet * $bet;
                            $arr_lines[$k] = implode('~', $arr_sub_lines);
                        }
                        $strWinLine = implode('&', $arr_lines);
                        $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FsOPT') == 1){
                        $strOtherResponse = $strOtherResponse . '&fs_opt_mask='. $str_fs_opt_mask .'&fs_opt=' . $str_fs_opt;
                        $spinType = 'fso';
                    }else if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 || $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0)
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
                        if($slotSettings->GetGameData($slotSettings->slotId . 'FreeSpinType') >= 0){
                            $strOtherResponse = $strOtherResponse . '&fsopt_i=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeSpinType');
                        }
                    }
                }
                
                
                $Balance = $slotSettings->GetBalance();  
                $response = 'def_s=9,6,3,4,5,4,8,6,9,8,5,3,8,8,7&balance='. $Balance .'&cfgs=2017&ver=2&index=1&balance_cash='. $Balance .'&reel_set_size=4&def_sb=11,13,3,3,3&def_sa=7,4,4,4,11&balance_bonus=0.00&na='. $spinType .'&scatters=&gmb=0,0,0&bg_i=50,3,200,4,1250,5&rt=d&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime='. floor(microtime(true) * 1000) .'&sa=7,4,4,4,11&sb=11,13,3,3,3&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=40.00&sh=3&wilds=2~0,0,0,0,0~1,1,1,1,1;15~200,100,30,3,0~1,1,1,1,1;16~125,60,20,3,0~1,1,1,1,1;17~100,50,15,3,0~1,1,1,1,1&bonuses=0&fsbonus=&c='. $bet .'&sver=5&n_reel_set='. $currentReelSet .'&bg_i_mask=pw,ic,pw,ic,pw,ic&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;200,100,30,3,0;125,60,20,3,0;100,50,15,3,0;60,30,10,0,0;60,30,10,0,0;60,30,10,0,0;40,15,5,0,0;40,15,5,0,0;30,10,5,0,0;30,10,5,0,0;30,10,5,0,0;30,10,5,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0&l=25&reel_set0=4,4,4,5,5,5,14,3,3,3,9,12,10,3,3,3,10,8,9,6,12,13,9,10,11,8,6,1,4,4,4,5,5,5,3,3,3,12,11,6,13,9,7,11,7,13,5,5,5,14,10,1,1,8,4,4,4,7,11~9,4,4,4,13,10,7,10,9,5,5,5,11,2,2,2,14,3,3,3,3,4,4,4,8,12,12,7,14,5,5,5,8,2,2,2,13,11,6,3,3,3,2,2,2,7,11,13,10,3,3,3,6,13,5,5,5,8,9,12,6,4,4,4,11,12~9,9,4,4,4,8,2,2,2,7,14,12,3,3,3,5,5,5,10,6,1,4,4,4,1,5,5,5,8,12,14,2,2,2,11,3,3,3,13,5,5,5,10,11,2,2,2,1,7,14,3,3,3,13,12,1,10,13,12,5,5,5,5,4,4,4,6,7,8~4,4,4,7,12,5,5,5,9,12,2,2,2,2,14,10,11,8,2,2,2,12,5,5,5,8,7,4,4,4,3,3,3,5,5,5,8,10,11,14,3,3,3,13,6,12,13,11,5,5,5,12,4,4,4,13,2,2,2,7,3,3,3,6,8,9,10~1,5,5,5,5,10,11,1,1,6,8,4,4,4,12,6,13,7,3,3,3,2,2,2,13,6,11,6,5,5,5,9,13,9,7,10,1,13,8,2,2,2,14,3,3,3,4,4,4,5,5,5,12,10,11,11,3,3,3,14,4,4,4,6,2,2,2,4&s='.$lastReelStr.'&reel_set2=11,9,9,4,4,4,13,8,1,6,8,11,10,1,5,5,5,12,9,11,4,4,4,3,3,3,1,13,7,13,6,12,6,5,5,5,13,4,4,4,8,9,8,7,7,3,3,3,6,11,7,14,4,10,12,5,5,5,14,3,3,3,13,10,1~12,14,3,3,3,3,10,2,2,2,16,16,16,14,11,8,8,8,14,12,3,3,3,13,16,16,16,11,17,17,17,13,11,9,7,12,7,2,2,2,9,7,16,16,16,2,2,2,17,17,17,6,13,6,10,9,3,3,3,13,17,17,17,12,11,9,10~14,17,17,17,3,3,3,3,8,1,2,2,2,6,6,11,12,13,11,8,7,2,2,2,17,17,17,12,9,16,16,16,16,13,14,1,3,3,3,2,2,2,16,16,16,7,12,14,7,10,17,17,10,14,10,13,8,3,3,3,12,8,16,16,16,9,1~9,14,7,14,12,12,12,11,2,2,2,10,3,3,3,9,16,16,16,6,3,3,3,6,17,17,17,17,12,16,16,16,14,13,12,7,13,7,13,8,2,2,2,17,17,17,11,16,16,16,3,3,3,8,11,9,17,17,17,8,11,7,2,2,2,10~1,6,14,10,6,8,7,13,3,3,3,14,16,16,16,11,17,17,17,2,2,2,11,9,12,2,2,2,17,17,17,16,16,16,12,3,3,3,8,11,9,6,11,9,13,17,17,17,12,1,10,3,3,3,10,16,16,16,2,2,2,1,6,1&reel_set1=10,3,3,3,6,9,14,5,5,5,7,7,12,10,9,4,4,4,11,6,1,6,13,13,8,10,3,3,3,8,11,4,4,4,11,5,5,5,12,13,1,7,10,14,6,9,4,4,4,14,3,3,3,11,8,9,5,5,5,13,1~8,13,6,9,17,17,17,14,12,13,14,2,2,2,3,3,3,4,4,4,13,6,8,8,7,9,10,9,3,3,3,11,8,14,11,11,2,2,2,3,3,3,7,12,4,4,4,2,2,2,17,17,17,10,12,10,17,17,17,12,13,13,10,11~8,13,14,13,2,2,2,17,17,17,17,1,6,3,3,3,7,1,9,17,17,17,13,4,4,4,12,9,8,2,2,2,3,3,3,9,4,4,4,6,17,17,17,10,1,6,1,9,10,12,8,3,3,3,11,2,2,2,14,13,4,4,4,10,7,11,7~3,3,3,11,11,10,8,13,2,2,2,3,3,3,12,8,13,9,4,4,4,7,9,11,13,12,14,2,2,2,17,17,17,9,4,4,4,12,12,7,6,6,13,4,4,4,3,3,3,17,17,17,6,8,13,14,7,7,2,2,2,8,17,17,17,9~2,2,2,11,8,3,3,3,17,17,17,9,10,6,11,11,1,4,4,4,4,14,13,17,17,17,13,14,2,2,2,4,4,4,7,3,3,3,6,10,13,6,7,4,4,4,2,2,2,3,3,3,9,13,11,8,6,12,17,17,17,1,12,1,1,10&reel_set3=11,4,4,4,7,5,5,5,6,13,11,8,6,9,8,7,3,3,3,8,5,5,5,9,13,14,3,3,3,4,4,4,7,6,12,6,7,1,11,10,10,4,4,4,11,12,13,9,5,5,5,12,14,3,3,3,10,1,1,9,11,8,1~13,13,8,2,2,2,17,17,17,2,2,2,7,11,11,8,16,16,16,17,17,17,13,12,9,10,9,10,15,15,15,17,17,17,6,16,16,16,10,2,6,15,15,15,11,12,9,14,8,16,16,16,12,15,15,15,13,14,6,11,12,10~6,16,16,16,2,2,2,13,8,8,10,15,15,15,17,17,17,2,15,15,15,14,13,9,2,2,2,9,12,13,1,7,1,16,16,16,17,17,17,6,7,10,11,14,8,1,15,15,15,2,2,2,11,7,14,14,16,16,16,12,17,17,17~7,2,2,2,7,12,17,17,17,15,15,15,8,11,6,16,16,16,13,10,6,7,2,2,2,12,17,17,17,9,8,16,16,16,10,8,15,15,15,8,12,12,14,10,13,7,17,17,17,11,11,14,9,16,16,16,11,13,13,12,6,2,2,2~12,15,15,15,14,2,2,2,6,16,16,16,6,14,10,17,17,17,11,15,15,15,11,11,14,6,9,16,16,16,2,2,2,12,11,10,1,10,11,1,17,17,17,7,1,15,15,15,13,9,13,8,1,2,2,2,7,16,16,16,6,9,17,17,7';
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
                        if($isTumb == false){
                            $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"invalid bonus state"}';
                            exit( $response );
                        }
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

                // $winType = 'bonus';

                $allBet = $betline * $lines;
                $tumbAndFreeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    $leftFreeGames = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') - $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame'); 
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'FsOPT', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeSpinType', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.1f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '6074458' . substr($roundstr, 4, 10);
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
                $reels = [];
                $fsmore = 0;
                $str_fs_opt = '';
                $str_fs_opt_mask = '';
                $wp = 0;
                $bw = 0;
                $str_bg_i = '';
                $str_bg_i_mask = '';
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $fsmore = $stack['fsmore'];
                    $str_fs_opt = $stack['fs_opt'];
                    $str_fs_opt_mask = $stack['fs_opt_mask'];
                    $wp = $stack['wp'];
                    $bw = $stack['bw'];
                    $str_bg_i = $stack['bg_i'];
                    $str_bg_i_mask = $stack['bg_i_mask'];
                    $strWinLine = $stack['win_line'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $lastReel = explode(',', $stack[0]['reel']);
                    $currentReelSet = $stack[0]['reel_set'];
                    $fsmore = $stack[0]['fsmore'];
                    $str_fs_opt = $stack[0]['fs_opt'];
                    $str_fs_opt_mask = $stack[0]['fs_opt_mask'];
                    $wp = $stack[0]['wp'];
                    $bw = $stack[0]['bw'];
                    $str_bg_i = $stack[0]['bg_i'];
                    $str_bg_i_mask = $stack[0]['bg_i_mask'];
                    $strWinLine = $stack[0]['win_line'];
                }
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
                $bonusWin = 0;
                if($bw == 1 && $wp > 0){
                    $bonusWin = $betline * $lines * $wp;
                    $totalWin = $totalWin + $bonusWin;
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
                if($scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin'){
                    // $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 5);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
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
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&w=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 'c';
                        $isEnd = true;
                    }
                    else
                    {
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&w=' . $totalWin;
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
                    if($scatterCount >= 3){
                        $isState = false;
                        $spinType = 'fso';
                        // $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1';
                        $strOtherResponse = $strOtherResponse . '&fs_opt_mask='. $str_fs_opt_mask .'&fs_opt=' . $str_fs_opt;
                        $slotSettings->SetGameData($slotSettings->slotId . 'FsOPT', 1);
                    }
                    $strOtherResponse = $strOtherResponse . '&w=' . $totalWin;
                }
                if($str_bg_i != ''){
                    $strOtherResponse = $strOtherResponse . '&bg_i=' . $str_bg_i . '&bg_i_mask=' . $str_bg_i_mask;
                }
                if($bw == 1 && $wp > 0){
                    $strOtherResponse = $strOtherResponse . '&coef='. ($betline * $lines) .'&rw='. $bonusWin .'&bw=1&wp='. $wp .'&end=1';
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                }
                
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin')  . '&n_reel_set='. $currentReelSet . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&st=rect&c='.$betline.'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=25&s=' . $strLastReel;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if( $slotEvent['slotEvent'] != 'freespin' && $scatterCount >= 3) 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"FsOPT":' . $slotSettings->GetGameData($slotSettings->slotId . 'FsOPT') . ',"FreeSpinType":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeSpinType') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);

            }else if($slotEvent['slotEvent'] == 'doFSOption'){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lastReel = $lastEvent->serverResponse->LastReel;
                $ind = 0;
                if(isset($slotEvent['ind'])){
                    $ind = $slotEvent['ind'];
                }
                $lines = 25;
                $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                $Balance = $slotSettings->GetBalance();
                $stack = $slotSettings->GetReelStrips('bonus', $betline * $lines, $ind);
                if($stack == null){
                    $response = 'unlogged';
                    exit( $response );
                }
                $freeSpins = [20,10,5];
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freeSpins[$ind]);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                $currentReelSet = $stack[0]['reel_set'];
                $slotSettings->SetGameData($slotSettings->slotId . 'FsOPT', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeSpinType', $ind);

                $response = 'fsmul=1&balance='.$Balance.'&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&n_reel_set='. $currentReelSet .'&balance_bonus=0.00&na=s&fswin=0.00&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&fs=1&fsres=0.00&sver=5&counter='. ((int)$slotEvent['counter'] + 1);

                
                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------
                
                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                        $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"FsOPT":' . $slotSettings->GetGameData($slotSettings->slotId . 'FsOPT') . ',"FreeSpinType":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeSpinType') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $slotSettings->SaveLogReport($_GameLog, $betline * $lines, $lines, 0, $slotEvent['slotEvent'], false);
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

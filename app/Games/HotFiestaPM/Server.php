<?php 
namespace VanguardLTE\Games\HotFiestaPM
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

            if($slotEvent['slotEvent'] == 'doSpin' && isset($slotEvent['c'])){
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
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'Bl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 25);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [2,13,8,3,8,1,11,4,6,10,5,13,13,4,9]);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bl', $lastEvent->serverResponse->Bl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);
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
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if(isset($stack)){
                    $fsmore = $stack['fsmore'];
                    $fsmax = $stack['fsmax'];
                    $str_sty = $stack['sty'];
                    $str_trail = $stack['trail'];
                    $str_slm_lmi = $stack['slm_lmi'];
                    $str_slm_lmv = $stack['slm_lmv'];
                    $str_slm_mp = $stack['slm_mp'];
                    $str_slm_mv = $stack['slm_mv'];
                    $strWinLine = $stack['win_line'];
                    if($stack['reel_set'] >= 0){
                        $currentReelSet = $stack['reel_set'];
                    }
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=0';
                    }
                    if($str_sty != ''){
                        $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                    }
                    if($str_trail != ''){
                        $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                    }
                    if($str_slm_lmv != ''){
                        $strOtherResponse = $strOtherResponse . '&slm_lmv=' . $str_slm_lmv . '&slm_lmi=' . $str_slm_lmi;
                    }
                    if($str_slm_mv != ''){
                        $strOtherResponse = $strOtherResponse . '&slm_mv=' . $str_slm_mv . '&slm_mp=' . $str_slm_mp;
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
                $response = 'def_s=2,13,8,3,8,1,11,4,6,10,5,13,13,4,9&balance='. $Balance .'&cfgs=5320&ver=2&index=1&balance_cash='. $Balance .'&def_sb=2,9,8,5,11&reel_set_size=4&def_sa=6,13,10,6,13&reel_set='.$currentReelSet.'&balance_bonus=0.00&na=s&scatters=1~0,0,3,0,0~0,0,0,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"1652826",max_rnd_win:"5000",max_rnd_win_a:"5000"}}&wl_i=tbm~5000&bl='. $slotSettings->GetGameData($slotSettings->slotId . 'Bl') .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa=6,13,10,6,13&sb=2,9,8,5,11&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=40.00&purInit_e=1&sh=3&wilds=2~1000,250,50,0,0~1,1,1,1,1,1;14~1000,250,50,0,0~1,1,1,1,1,1;15~1000,250,50,0,0~1,1,1,1,1,1;16~1000,250,50,0,0~1,1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&bls=25,35&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;750,150,50,0,0;500,100,35,0,0;300,60,25,0,0;200,40,20,0,0;150,25,12,0,0;100,20,8,0,0;50,10,5,0,0;50,10,5,0,0;25,5,2,0,0;25,5,2,0,0;25,5,2,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0&l=25&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=12,7,13,8,11,10,8,11,3,9,7,13,10,6,1,10,12,9,8,4,12,8,7,13,11,5,9,13,10,5,12,13,6,12,6,11,6,10,9,8,13,12,1,7,9,2,1,6,5,12,3,11,13,11,4,7,9,7,10~7,13,10,13,5,10,11,2,10,7,12,6,13,11,8,9,11,3,11,7,12,11,7,5,6,5,8,10,8,10,13,11,4,9,4,3,4,13,10,2,10,12,13,8,9,8,9,12,9,7,9,12,10,5,4,9,8,6,9,7,8,12,11,13~11,10,9,7,12,9,4,11,7,13,12,1,13,8,6,12,6,5,13,9,5,11,7,11,6,11,1,13,1,5,12,3,6,8,13,10,9,13,7,13,6,10,6,11,1,3,4,1,2,10,12,8,10,2,8,9,12,8,10,4,8,5,7,12,9,7,3,8,2,11~13,10,3,12,7,8,12,13,2,9,8,5,13,6,10,4,11,7,6,4,5,8,10,5,11,6,13,12,3,12,10,9,6,10,12,7,11,9,11,10,9,11,13,6~7,9,13,6,11,1,7,11,8,5,12,9,10,13,8,12,9,13,12,5,3,11,4,11,13,12,7,5,3,9,6,12,7,8,10,6,10,8,9,6,11,6,7,11,7,8,4,5,12,11,2,9,10,12,8,10,12,8,7,13,9,8,5,13,5,11,6,4,1,6,9,13,3,7,1,11,5,10,1&s='.$lastReelStr.'&reel_set2=8,9,3,8,11,13,11,13,13,9,11,5,9,5,13,3,13,13,9,6,13,6~4,12,10,12,10,12,7,10,7,12,7,4,10,7,10,7,12,7,12,10~9,5,13,11,8,3,11,5,6,13,9,5,8,9,13,9,13,3,13,6,13,11,13,9,13,11~12,10,7,12,7,4,7,10,12,10,7,10,7,12,10,7,10,12,10,12,7,12,4~11,9,13,13,11,9,6,13,3,5,13,11,9,8,13,8,13,13,9,6,3,5,9,11,13,5,11,3,13&reel_set1=12,3,4,11,8,10,13,10,5,9,12,13,6,8,12,6,13,5,11,8,7,3,12,8,9,6,5,10,3,3,3,11,9,8,13,10,13,10,4,10,12,9,7,8,7,12,8,12,9,4,5,12,13,11,6,11,4,11,7~12,7,12,10,9,6,9,3,7,11,10,6,10,11,13,12,6,8,11,6,4,8,11,7,3,4,12,5,7,13,7,4,9,11,4,10,11,6,8,11,12,13,5,10,8,5,10,8,5,3,11,13,9,13,7,13,9,6,13,8,12,7,9,10~12,6,13,10,7,9,10,9,5,11,6,12,10,7,10,7,5,10,8,13,6,10,13,6,4,11,12,8,12,5,13,8,11,8,13,7,8,6,7,6,11,9,3,10,8,3,5,9,4,11,12,7,3,5,11,4,7,4,13,6,13,12,8,13,11,13~10,5,12,4,12,6,10,11,13,12,8,9,8,12,7,4,5,6,11,9,11,3,4,13,9,10,6,10,5,7,6,7,13,9,8,9,3,11,8,6,5,10,13,12,7,11,7,8~11,9,10,9,8,13,3,8,12,11,10,13,7,11,13,4,10,9,8,7,3,3,3,12,5,6,9,8,7,10,3,8,9,4,6,13,11,7,5,12,6,11,12,6&purInit=[{type:"fsbl",bet:2500,bet_level:0}]&reel_set3=13,7,6,10,9,6,13,11,7,10,9,11,8,13,8,11,12,13,11,7,11,13,12,5,2,9,8,13,1,9,13,1,12,6,13,6,12,4,8,5,10,9,3,7,3,10,9,1,6,12,4,7,10,11,8,5,10,8,9,4,10,1,11,12,1,12~4,13,12,10,11,10,13,3,6,9,8,2,11,8,12,9,11,9,4,13,3,5,9,12,4,13,3,6,12,8,9,10,8,11,5,9,7,13,11,7,12,13,7,8,13,5,10,9,2,11,10,4,6,2,12,5,10,13,12,13,11~10,5,6,9,7,10,1,2,11,6,7,4,8,12,3,1,12,9,6,13,7,12,13,12,7,9,8,9,13,10,1,9,6,10,7,2,10,8,10,8,11,1,4,11,5,9,13,6,2,8,13,11,13,11,8,12,5,11,12,6,10,5,4,13,7,10,1,3,12,11,9,6,7,11,3,4~9,11,9,8,10,12,6,9,11,10,9,2,12,7,11,6,8,5,10,8,13,5,13,12,7,13,4,10,4,9,11,12,13,11,5,3,12,13,7,6,10,11,12,13,10,3,9~10,11,9,13,11,9,7,1,9,3,12,5,9,10,6,13,1,8,4,7,10,5,7,9,13,8,10,5,8,1,11,10,6,4,6,1,12,4,9,4,9,5,1,13,11,13,12,13,3,12,10,7,2,8,10,12,5,3,7,12,11,6&total_bet_min=8.00';
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
                $pur = -1;
                if(isset($slotEvent['pur'])){
                    $pur = $slotEvent['pur'];
                }
                $bl = $slotEvent['bl'];
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
                
                $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $betline * $lines, $lines, $bl);
                $winType = $_spinSettings[0];
                $_winAvaliableMoney = $_spinSettings[1];

                // $winType = 'bonus';

                $allBet = $betline * $lines;
                if($pur >= 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $allBet * 100;
                }else if($bl == 1){
                    $allBet = $betline * 35;
                }
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
                    if($pur >= 0){                            
                        $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $_sum, $slotEvent['slotEvent'], true);
                        $winType = 'bonus';
                        $_winAvaliableMoney = $slotSettings->GetBank('bonus');
                    }else{
                        $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $_sum, $slotEvent['slotEvent']);
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bl', $bl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.4f', microtime(TRUE));
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
                $str_sty = '';
                $str_trail = '';
                $str_slm_lmi = '';
                $str_slm_lmv = '';
                $str_slm_mp = '';
                $str_slm_mv = '';
                $fsmax = 0;
                $fsmore = 0;
                $str_initReel = '';
                $scatterWin = 0;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $fsmore = $stack['fsmore'];
                    $str_initReel = $stack['initreel'];
                    $fsmax = $stack['fsmax'];
                    $str_sty = $stack['sty'];
                    $str_trail = $stack['trail'];
                    $str_slm_lmi = $stack['slm_lmi'];
                    $str_slm_lmv = $stack['slm_lmv'];
                    $str_slm_mp = $stack['slm_mp'];
                    $str_slm_mv = $stack['slm_mv'];
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
                    $str_initReel = $stack[0]['initreel'];
                    $fsmax = $stack[0]['fsmax'];
                    $str_sty = $stack[0]['sty'];
                    $str_trail = $stack[0]['trail'];
                    $str_slm_lmi = $stack[0]['slm_lmi'];
                    $str_slm_lmv = $stack[0]['slm_lmv'];
                    $str_slm_mp = $stack[0]['slm_mp'];
                    $str_slm_mv = $stack[0]['slm_mv'];
                    $strWinLine = $stack[0]['win_line'];
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
                    $scatterWin = $betline * $lines * 3;
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
                    $strOtherResponse = $strOtherResponse . '&w=' . $totalWin;
                    if($fsmax > 0){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='. $fsmax .'&fswin=0.00&fs=1&fsres=0.00&psym=1~'. $scatterWin .'~' . implode(',', $scatterPoses);
                    }
                }
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $strOtherResponse = $strOtherResponse . '&puri=0';
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($pur >= 0){
                    $strOtherResponse = $strOtherResponse . '&purtr=1';
                }
                if($str_sty != ''){
                    $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                }
                if($str_trail != ''){
                    $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                }
                if($str_slm_lmv != ''){
                    $strOtherResponse = $strOtherResponse . '&slm_lmv=' . $str_slm_lmv . '&slm_lmi=' . $str_slm_lmi;
                }
                if($str_slm_mv != ''){
                    $strOtherResponse = $strOtherResponse . '&slm_mv=' . $str_slm_mv . '&slm_mp=' . $str_slm_mp;
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                }
                
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin')  . '&reel_set='. $currentReelSet . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&bl='.$bl .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&sw=5&st=rect&c='.$betline.'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=25&s=' . $strLastReel;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if( $slotEvent['slotEvent'] != 'freespin' && $fsmax > 0) 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')  . ',"Bl":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bl')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin'). ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($slotEvent['slotEvent'] == 'freespin' && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * 100;
                }else if($slotSettings->GetGameData($slotSettings->slotId . 'Bl') > 0){
                    $allBet = $betline * 35;
                }
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

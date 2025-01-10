<?php 
namespace VanguardLTE\Games\DiamondsofEgyptPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 88);
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', [5,12,13,7,10,6,14,6,6,12,7,13,8,5,13]);
                $slotSettings->SetGameData($slotSettings->slotId . 'G', []);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $strOtherResponse = '';
                $currentReelSet = 0;
                $stack = null;
                $strWinLine = '';
                $spinType = 's';
                
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'G', $lastEvent->serverResponse->G);
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
                    $bet = '12.00';
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                $fsmore = 0;
                if(isset($stack)){
                    $str_initReel = $stack['is'];
                    $str_slm_mp = $stack['slm_mp'];
                    $str_slm_mv = $stack['slm_mv'];
                    $arr_g = null;
                    if($stack['g'] != ''){
                        $arr_g = $stack['g'];
                    }                    
                    $bw = $stack['bw'];
                    $fsmax = $stack['fsmax'];
                    $fsmore = $stack['fsmore'];
                    $strWinLine = $stack['wlc_v'];
                    if($stack['reel_set'] > -1){
                        $currentReelSet = $stack['reel_set'];
                    }
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    
                    if($arr_g != null){
                        if(count(json_decode(json_encode($slotSettings->GetGameData($slotSettings->slotId . 'G')), true)) > 0){
                            $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($slotSettings->GetGameData($slotSettings->slotId . 'G')));
                        }else{
                            $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($arr_g));
                        }
                        if($arr_g['bg_0']['end'] == 0){
                            $spinType = 'b';                            
                        }
                    }
                    
                    if($str_slm_mv != ''){
                        $strOtherResponse = $strOtherResponse . '&slm_mp=' . $str_slm_mp . '&slm_mv=' . $str_slm_mv;
                    }
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($bw == 1){
                        $strOtherResponse = $strOtherResponse . '&bw=' . $bw;
                        $spinType = 'b';
                    }
                    if($strWinLine != ''){
                        $arr_lines = explode(';', $strWinLine);
                        for($k = 0; $k < count($arr_lines); $k++){
                            $arr_sub_lines = explode('~', $arr_lines[$k]);
                            $arr_sub_lines[1] = str_replace(',', '', $arr_sub_lines[1]) / $original_bet * $bet;
                            $arr_lines[$k] = implode('~', $arr_sub_lines);
                        }
                        $strWinLine = implode(';', $arr_lines);
                        $strOtherResponse = $strOtherResponse . '&wlc_v=' . $strWinLine;
                    }
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0 || $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 )
                    {
                        $fs = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                        if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) || ($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0)) 
                        {
                            $strOtherResponse = $strOtherResponse . '&fs_total='.($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 1).'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsend_total=1&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        }
                        else
                        {
                            $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $fs.'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        }
                        if($fsmore > 0){
                            $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                        }
                    }
                }
                    
                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=5,12,13,7,10,6,14,6,6,12,7,13,8,5,13&balance='. $Balance .'&cfgs=8288&ver=3&index=1&balance_cash='. $Balance .'&def_sb=5,12,4,6,10&reel_set_size=3&def_sa=6,14,12,5,11&reel_set=0&balance_bonus=0.00&na='. $spinType .'&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"3315650",jp1:"1000",max_rnd_win:"2500",jp3:"20",jp2:"100",jp4:"10"}}&wl_i=tbm~2500&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=6,14,12,5,11&sb=5,12,4,6,10&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=12.00&sh=3&wilds=2~0,0,0,0,0~1,1,1,1,1;3~0,0,0,0,0~1,1,1,1,1&bonuses=0&st=rect&c='.$bet.'&sw=5&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;680,128,68,0,0;380,88,38,0,0;280,68,28,0,0;180,38,18,0,0;128,28,8,0,0;15,8,5,0,0;15,8,5,0,0;15,8,5,0,0;15,8,5,0,0;15,8,5,0,0;15,8,5,0,0;4400,880,440,0,0&l=88&reel_set0=7,6,7,10,14,15,5,5,5,10,5,11,7,8,5,8,8,8,15,11,8,5,14,4,7,6,6,6,13,4,9,7,12,6,8,4,4,4,5,7,6,5,7,4,8,7,7,7,9,8,12,6,5,8,5,6~7,7,6,6,8,7,6,4,4,4,13,2,12,8,5,13,8,7,5,6,6,6,15,2,4,4,7,4,4,13,7,7,7,2,5,6,8,14,7,8,4,11,5,5,5,9,8,6,11,6,10,9,6,8,8,8,9,7,7,10,15,10,8,12~4,9,10,8,7,14,7,6,5,5,6,8,8,15,9,5,6,6,2,14,7,15,8,8,6,15,11,7,7,7,4,9,12,8,13,15,5,12,7,9,8,15,7,7,8,11,8,8,6,7,2,15,6,5,5,7,8,8,8,7,6,12,14,4,4,7,6,8,13,7,7,10,6,13,3,13,3,2,7,4,6,7,6,8,8,5,12,6,6,6,11,8,9,7,6,7,10,7,8,6,8,7,9,7,14,6,13,6,2,5,8,6,11,6,15,4,6,8,10~12,11,4,13,10,9,7,7,7,10,14,2,10,14,7,5,8,8,8,13,9,8,10,4,8,12,5,6,6,6,11,12,14,5,6,14,9,4,14,5,5,5,7,12,6,8,6,7,13,4,4,4,8,5,5,15,8,15,6,6,2,9~7,7,9,8,11,5,8,4,13,5,5,5,6,4,7,4,10,15,7,7,12,4,4,4,12,14,15,11,6,12,13,12,11,4,8,8,8,9,4,5,6,14,12,13,5,9,7,7,7,15,8,4,9,11,6,7,6,10,6,6,6,14,10,12,13,10,5,11,9,13,8,9,10&s='.$lastReelStr.'&reel_set2=14,5,5,6,12,5,5,5,7,5,11,7,8,5,8,8,8,9,5,8,7,4,6,8,6,6,6,5,6,4,6,12,5,4,4,4,8,10,14,8,10,7,8,7,7,7,13,6,7,8,7,9,6,7~7,8,11,6,4,4,4,7,4,8,13,9,10,8,6,6,6,4,6,6,8,9,6,8,7,7,7,4,7,13,7,10,5,5,5,12,8,6,6,8,5,2,8,8,8,7,11,14,2,7,8,2,12~8,9,11,5,8,7,8,6,13,4,7,8,6,7,7,6,7,7,14,6,2,6,7,7,2,12,7,4,14,5,7,8,4,14,6,6,7,4,8,7,7,7,6,8,2,9,2,12,8,7,6,9,4,5,11,2,14,5,8,10,6,7,9,5,8,8,2,6,7,2,11,5,2,8,6,14,8,7,6,9,7,8,6,8,8,8,4,8,4,8,3,2,7,12,13,5,7,8,7,10,6,8,7,2,8,7,6,13,2,7,8,6,7,6,4,12,2,8,6,8,7,6,13,5,6,7,6,6,6,11,9,11,8,6,8,7,6,13,5,8,2,8,7,10,8,6,9,3,5,13,2,5,8,7,6,2,10,8,9,2,5,12,6,10,6,8,4,6~8,12,14,7,8,6,6,11,2,7,7,7,4,6,9,5,12,6,7,4,12,2,8,8,8,6,13,10,7,14,8,5,12,8,6,6,6,12,2,7,11,5,13,8,9,13,5,5,5,8,10,9,5,5,10,7,4,7,5,4,4,4,14,2,9,11,14,4,13,9,4,14~9,10,7,6,10,5,13,5,5,5,6,13,11,12,6,12,6,4,4,4,9,11,4,14,7,7,6,8,13,8,8,8,6,14,4,5,4,8,4,9,7,7,7,8,11,7,12,5,10,4,11,6,6,6,9,14,7,11,9,13,10,8,12&reel_set1=5,7,4,8,6,8,7,15,7,7,7,5,7,5,7,7,8,4,4,7,5,5,5,7,8,6,7,8,8,7,5,8,7,6,6,6,5,6,6,7,8,15,6,8,5,4,4,4,7,6,4,6,4,4,8,8,5,6~4,6,6,7,7,7,5,2,8,7,8,8,8,6,15,7,4,8,4,5,5,5,7,8,5,8,8,6,6,6,5,5,6,7,8,4,4,4,8,4,6,6,8,5,4~7,8,4,7,6,8,15,2,6,3,7,5,8,7,6,8,6,8,8,8,7,4,2,7,4,7,6,3,4,5,5,15,8,7,7,6,3,4,6,6,6,15,8,8,7,6,3,8,7,5,7,3,6,5,8,8,6,5,4,5,5,8~5,8,7,5,7,7,7,6,8,8,4,2,8,8,8,7,5,4,6,6,5,4,4,4,7,5,7,8,8,5,5,5,4,7,6,8,8,6,6,6,4,8,6,7,7,15,6~6,6,7,8,4,7,4,4,4,8,4,15,8,4,8,15,6,8,8,8,5,6,5,4,8,7,5,7,7,7,5,7,6,5,7,4,6,8,6,6,6,8,4,7,7,8,6,6,5,5';
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
                $lines = 88;      
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
                $slotEvent['slotLines'] = 88;
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
                    if( $slotEvent['slotEvent'] == 'doSpin' && $slotSettings->GetBalance() < ($lines * $betline)) 
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'G', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.1f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '56671' . substr($roundstr, 4, 9);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $leftFreeGames = 0;

                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []);
                }
                
                $wild = '2';
                $scatter = '1';
                $Balance = $slotSettings->GetBalance();
                $totalWin = 0;
                $this->winLines = [];
                $bonusMpl = 1;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $str_slm_mp = '';
                $str_slm_mv = '';
                $str_initReel = '';
                $arr_g = null;
                $bw = 0;
                $fsmax = 0;
                $fsmore = 0;
                $str_sa = '';
                $str_sb = '';
                if($slotEvent['slotEvent'] == 'freespin'){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_initReel = $stack['is'];
                    $str_slm_mp = $stack['slm_mp'];
                    $str_slm_mv = $stack['slm_mv'];
                    if($stack['g'] != ''){
                        $arr_g = $stack['g'];
                    }                    
                    $bw = $stack['bw'];
                    $fsmax = $stack['fsmax'];
                    $fsmore = $stack['fsmore'];
                    $strWinLine = $stack['wlc_v'];
                    $currentReelSet = $stack['reel_set'];
                    $str_sa = $stack['sa'];
                    $str_sb = $stack['sb'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $lastReel = explode(',', $stack[0]['reel']);
                    $str_initReel = $stack[0]['is'];
                    $str_slm_mp = $stack[0]['slm_mp'];
                    $str_slm_mv = $stack[0]['slm_mv'];
                    if($stack[0]['g'] != ''){
                        $arr_g = $stack[0]['g'];
                    }                    
                    $bw = $stack[0]['bw'];
                    $fsmax = $stack[0]['fsmax'];
                    $fsmore = $stack[0]['fsmore'];
                    $strWinLine = $stack[0]['wlc_v'];
                    $currentReelSet = $stack[0]['reel_set'];
                    $str_sa = $stack[0]['sa'];
                    $str_sb = $stack[0]['sb'];
                }
                if($strWinLine != ''){
                    $arr_lines = explode(';', $strWinLine);
                    for($k = 0; $k < count($arr_lines); $k++){
                        $arr_sub_lines = explode('~', $arr_lines[$k]);
                        $arr_sub_lines[1] = str_replace(',', '', $arr_sub_lines[1]) / $original_bet * $betline;
                        $totalWin = $totalWin + $arr_sub_lines[1];
                        $arr_lines[$k] = implode('~', $arr_sub_lines);
                    }
                    $strWinLine = implode(';', $arr_lines);
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
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsend_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 'c';
                    }
                    else
                    {
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
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
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1';
                        $spinType = 's';
                    }
                }
                if($str_slm_mv != ''){
                    $strOtherResponse = $strOtherResponse . '&slm_mp=' . $str_slm_mp . '&slm_mv=' . $str_slm_mv;
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($bw == 1){
                    $spinType = 'b';
                    $isState = false;
                    $strOtherResponse = $strOtherResponse . '&bw=' . $bw;
                }
                if($arr_g != null){
                    $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($arr_g));
                    $slotSettings->SetGameData($slotSettings->slotId . 'G', $arr_g);
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&wlc_v=' . $strWinLine;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse  .'&balance='.$Balance. '&index='.$slotEvent['index'] .'&balance_cash='.$Balance.'&reel_set='. $currentReelSet .'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&st=rect&c='.$betline.'&sa='. $str_sa .'&sb='. $str_sb .'&sh=3&sw=5&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=88&s='. $strLastReel .'&w='.$totalWin;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) && $bw == 0) 
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
                    // $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')  . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') .',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) .',"G":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'G')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
            }else if($slotEvent['slotEvent'] == 'doBonus'){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lastReel = $lastEvent->serverResponse->LastReel;
                $lines = 88;
                $ind = $slotEvent['ind'];
                $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
                $fsmax = $stack['fsmax'];
                $old_arr_g = null;
                if($stack['g'] != ''){
                    $old_arr_g = $stack['g'];
                }
                $totalWin = 0;                
                $old_win = explode(',', $old_arr_g['bg_0']['wins']);
                $old_status = explode(',', $old_arr_g['bg_0']['status']);
                $old_wins_mask = explode(',', $old_arr_g['bg_0']['wins_mask']);
                $old_wi = $old_arr_g['bg_0']['wi'];

                $arr_g = $slotSettings->GetGameData($slotSettings->slotId . 'G');
                $win = explode(',', $arr_g['bg_0']['wins']);
                $status = explode(',', $arr_g['bg_0']['status']);
                $wins_mask = explode(',', $arr_g['bg_0']['wins_mask']);
                
                $win[$ind] = $old_win[$old_wi];
                $status[$ind] = $old_status[$old_wi];
                $wins_mask[$ind] = $old_wins_mask[$old_wi];
                if(!isset($arr_g['bg_0']['ch_h'])){
                    $arr_g['bg_0']['ch_h'] = [];
                }
                $arr_g['bg_0']['ch_h'][] = '0~' . $ind;
                $arr_g['bg_0']['wi'] = $ind;
                $arr_g['bg_0']['rw'] = $old_arr_g['bg_0']['rw'];
                if($arr_g['bg_0']['rw'] > 0){
                    $arr_g['bg_0']['rw'] = '' . ($arr_g['bg_0']['rw'] / $original_bet * $betline);
                }
                $arr_g['bg_0']['end'] = $old_arr_g['bg_0']['end'];
                if($arr_g['bg_0']['end'] == 1){
                    $totalWin = $arr_g['bg_0']['rw'];
                }
                $arr_g['bg_0']['level'] = $old_arr_g['bg_0']['level'];
                $arr_g['bg_0']['lifes'] = $old_arr_g['bg_0']['lifes'];
                $arr_g['bg_0']['status'] = implode(',', $status);
                $arr_g['bg_0']['wins'] = implode(',', $win);
                $arr_g['bg_0']['wins_mask'] = implode(',', $wins_mask);

                $slotSettings->SetGameData($slotSettings->slotId . 'G', $arr_g);
                $spinType = 'b';
                $isState = false;
                $strOtherResponse = '';
                if( $totalWin > 0) 
                {
                    $spinType = 'cb';
                    $isState = true;
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                if($arr_g != null){
                    $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($arr_g));
                }
                if($totalWin > 0){
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                    {
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsend_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                    else if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)
                    {
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }else if($fsmax > 0){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $fsmax);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1';
                        $spinType = 's';
                    }
                }
                $response = 'tw='. $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') .'&balance='.$Balance . $strOtherResponse .'&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='. $spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sver=5&counter='. ((int)$slotEvent['counter'] + 1);

                
                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------
                
                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')  . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')  .',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) .',"G":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'G')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
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
        public function findZokbos($reels, $firstSymbol, $repeatCount, $strLineWin){
            $wild = '2';
            $bPathEnded = true;
            if($repeatCount < 6){
                for($r = 0; $r < 8; $r++){
                    if($firstSymbol == $reels[$repeatCount][$r] || $reels[$repeatCount][$r] == $wild){
                        $this->findZokbos($reels, $firstSymbol, $repeatCount + 1, $strLineWin . '~' . ($repeatCount + $r * 6));
                        $bPathEnded = false;
                    }
                }
            }
            if($bPathEnded == true){
                if($repeatCount >= 3 || ($firstSymbol == 3 && $repeatCount == 2)){
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

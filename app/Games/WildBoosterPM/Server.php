<?php 
namespace VanguardLTE\Games\WildBoosterPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->SetGameData($slotSettings->slotId . 'ScatterCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FSOPT', -1);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [3,4,5,6,7,8,9,10,2,11,7,6,1,4,3]);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $str_mo = '';
                $strOtherResponse = '';
                $stack = null;
                
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', $lastEvent->serverResponse->BonusState);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ScatterCount', $lastEvent->serverResponse->ScatterCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FSOPT', $lastEvent->serverResponse->FSOPT);
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
                    $bet = '50.00';
                }
                $currentReelSet = 0;
                $spinType = 's';
                if(isset($stack)){
                    if($stack['reel_set'] >= 0){
                        $currentReelSet = $stack['reel_set'];
                    }
                    $str_accm = $stack['accm'];
                    $str_accv = $stack['accv'];
                    $str_acci = $stack['acci'];
                    $str_lm_m = $stack['lm_m'];
                    $str_lm_v = $stack['lm_v'];
                    $str_fs_opt = $stack['fs_opt'];
                    $str_fs_opt_mask = $stack['fs_opt_mask'];
                    $fsmax = $stack['fsmax'];
                    $strWinLine = $stack['win_line'];
                    $fsmore = $stack['fsmore'];
                    
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                    }  
                    if($str_lm_v != ''){
                        $strOtherResponse = $strOtherResponse . '&lm_v='. $str_lm_v .'&lm_m='. $str_lm_m;
                    }
                    if($str_accv != ''){
                        $strOtherResponse = $strOtherResponse . '&accm=' . $str_accm . '&accv=' . $str_accv . '&acci=' . $str_acci;
                    }
                    if($str_fs_opt != ''){
                        $strOtherResponse = $strOtherResponse . '&fs_opt_mask='. $str_fs_opt_mask .'&fs_opt='. $str_fs_opt;
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'ScatterCount') >= 3){
                        $spinType= 'fso';
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
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0 || $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                    {
                        if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0 ) 
                        {
                            $strOtherResponse = $strOtherResponse . '&fs_total='.($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 1).'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        }
                        else
                        {
                            $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        }
                        if($slotSettings->GetGameData($slotSettings->slotId . 'FSOPT') >= 0){
                            $strOtherResponse = $strOtherResponse . '&fsopt_i=' . $slotSettings->GetGameData($slotSettings->slotId . 'FSOPT');
                        }
                    }
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));

                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=3,4,5,6,7,8,9,10,2,11,7,6,1,4,3&balance='. $Balance . $strOtherResponse .'&cfgs=6707&ver=2&index=1&balance_cash='. $Balance .'&def_sb=1,2,3,4,5&reel_set_size=10&def_sa=1,2,3,4,5&reel_set='. $currentReelSet .'&balance_bonus=0.00&na='.$spinType.'&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&gameInfo={rtps:{purchase:"96.47",regular:"96.47"},props:{max_rnd_sim:"1",max_rnd_hr:"836890",max_rnd_win:"5000"}}&wl_i=tbm~5000&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa=1,2,3,4,5&sb=1,2,3,4,5&sc='. implode(',', $slotSettings->Bet) .'&defc=100.00&sh=3&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;600,100,50,0,0;300,50,25,0,0;200,40,20,0,0;150,25,12,0,0;100,20,10,0,0;60,12,6,0,0;60,12,6,0,0;50,10,5,0,0;50,10,5,0,0&l=20&rtp=96.47&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=5,8,10,7,11,6,9,11,4,5,5,3,10,10,9,6,4,6,8,9,10,1,11,9,10,8,8,7,7,9,8,3,11,3~7,5,4,1,10,2,7,10,4,8,6,11,9,7,10,3,8,11,9,5,4,9,10,11,9,11,3,4,9,5,6,8,11,11,6,2,8,4,11,8,10,1,5,7,6,9~10,7,9,4,3,11,3,6,2,11,7,5,9,10,1,9,4,7,2,5,11,11,8,10,3,2,10,6,9,9,8,1,5,5,9,10,2,10,11,4,4,7,8,9,5,8,8,4,8,7~10,3,8,2,2,9,10,3,5,4,1,8,9,4,6,9,3,9,11,7,10,4,6,11,5,11,8~9,10,6,9,11,9,4,7,9,4,8,6,4,1,10,7,5,6,3,3,8,11,5,9,8,11,10,3,8,10,5,3,8&s='.$lastReelStr.'&accInit=[{id:0,mask:"cp;tp;lvl;sc;cl"}]&reel_set2=5,10,9,3,7,4,11,8,7,10,8,6,8,4,10,8,7,3,6,9,6,8,9,5,5,4,9,10,10,8,4,3,4,9,7,11,10,10,9,11,5,11,3,9,11~6,7,9,4,3,2,8,8,9,7,9,5,8,2,11,11,7,10,9,6,6,5,11,10,11,4,11,3,4,11,9,2,10,3,5~9,2,9,11,8,2,10,10,2,11,8~10,7,6,3,5,3,10,11,4,3,9,3,3,9,6,9,10,9,8,11,8,3,11,5,2,5,11,2,8,6,9,5,4,8,7,10,11,9,7,9,5,4,11,8,8,4,10~8,5,10,8,9,9,4,8,9,11,9,3,10,4,11,7,8,5,11,6,7,3,9,4,11,10,6,3,5,6,6,4,10,3,9,8,5,5,10,7,8,7,10,11&reel_set1=10,9,7,7,8,10,11,8,4,5,11,11,9,4,3,8,6,10,10,3,8,10,7,11,8,5,3,5,10,9,4,4,5,9,6,6,3,9,4,9,11,8,7~11,2,2,11,11,8,10,2,9,8,10,9,2,10~7,11,9,9,8,10,9,4,11,5,9,5,4,10,4,3,9,9,10,11,3,6,6,5,8,6,7,4,8,8,3,6,7,7,5,4,8,5,10,10,8,5,10,2,3,2,2,11~4,10,3,10,4,7,9,11,9,3,11,10,8,7,4,5,8,9,5,8,6,2,9,3,5,11,3,8,6,2,11~11,8,10,5,5,3,10,9,5,8,7,5,4,6,9,11,4,6,10,8,5,9,8,3,9,8,11,10,3,9,10,11,4,6,8,11,7,7,9,6,3,7,4&reel_set4=9,8,8,11,5,11,6,10,3,11,1,3,5,10,4,7,9,9,4,8,4,10,7~8,9,11,7,10,2,6,11,5,10,10,11,3,11,5,3,2,7,9,9,2,8,11,6,9,8,2,6,11,4,6,3,5,10,4,2,8,11,5,1,9,9~1,2,2,3,4,4,5,5,6,7,8,8,9,9,10,10,11,11~7,1,6,7,10,1,3,9,11,5,2,3,11,3,9,5,8,9,8,11,11,2,8,9,4,2,10,8,4,6,10~10,8,9,8,1,3,4,7,10,10,7,9,9,10,6,10,11,9,6,5,8,4,3,9,4,8,6,3,5,5,7,11&purInit=[{type:"fs",bet:1500}]&reel_set3=10,6,8,5,7,9,11,3,6,3,9,10,4,10,4,11,7,8,9,8,5~10,4,3,8,6,2,11,5,3,6,7,11,9,4,7,10,8,5,11,2,9,9~2,8,7,6,5,9,4,9,8,10,3,2,11,8,9,10,10,11,3,6,6,2,10,7,4,11,5,5,3,5,8,9,4~9,11,9,2,8,2,10,11,10,8,2~7,8,3,9,6,9,8,3,4,9,11,11,5,11,6,4,8,7,6,3,11,7,9,5,8,5,8,4,10,8,8,7,3,8,9,10,7,10,4,8,10,3,6,5,9,10,9,4,11,10,7,5,10,11,11,9,4,6,3,10,9,5,6,5&reel_set6=8,10,8,10,9,5,11,4,3,7,7,3,11,11,8,6,5,9,9,7,10,6,11,10,10,9,8,4,3,9,4,5,4,1~10,11,8,9,10,5,10,9,2,2,11,9,6,3,9,3,6,11,7,8,9,11,5,4,8,11,2,6,5,11,7,5,4,1,2,8,11,10,9,2,6,4~4,2,3,4,9,9,8,4,9,3,5,2,5,8,7,7,6,10,11,2,1,6,10,5,8,8,9,10,11,10,11~9,3,3,4,10,8,2,11,7,11,2,9,8,9,1,6,5,10~9,11,7,9,3,5,7,10,8,10,8,9,6,11,6,10,3,5,8,4,8,5,6,11,9,1,3,4&reel_set5=6,11,8,5,3,8,6,4,9,10,5,8,5,8,10,8,9,5,8,11,4,9,9,10,10,3,10,1,9,3,10,9,4,9,7,6,7,4,3,11,7,10,11~1,9,11,5,9,7,5,4,2,2,3,6,11,2,8,11,10,7,11,10,8,5,5,3,2,4,3,9,3,4,10,6,8,9,10,8,11~9,7,5,1,4,8,11,10,6,4,10,8,3,1,8,2,3,4,5,10,11,8,7,9,11,9,7,3,9,5,6,5,9,2,10,11,10,5,2,6,2,8,4,11~1,2,2,3,3,4,5,6,7,8,8,9,9,10,10,11,11~1,3,3,4,5,6,7,8,8,9,9,10,11,11&reel_set8=5,4,11,8,10,5,6,7,1,7,4,10,9,10,8,7,9,9,3,3,8,10,9,5,5,8,11,11,7,9,10,9,10,9,10,4,3,4,4,8,11,11,8,6,3~9,2,11,5,6,1,10,11,3,11,2,5,6,8,4,2,7,4,9,11,10,9,8~10,11,11,7,3,5,11,9,10,4,9,4,11,5,9,5,1,7,5,4,8,10,10,9,9,6,2,8,3,1,10,4,8,5,8,6,2,3,7,6,2,8,2~2,3,8,11,9,5,5,6,10,8,3,9,7,4,11,1,2,4,10,11,6,8,9~1,10,8,11,9,4,4,3,10,8,9,3,8,10,6,7,3,6,11,6,8,10,3,6,7,10,5,1,5,7,9,8,9,3,8,11,5,9,5,9,8,9,3,11,5,4,7,6,11,10,4&reel_set7=9,7,8,4,11,5,3,1,9,8,3,6,10,10,4,9,8~8,4,2,8,11,6,9,3,9,9,11,4,10,2,2,3,5,9,4,10,6,9,11,11,9,10,6,7,8,5,3,1,5,2,8,7,11,6,11,2,10~2,11,8,9,5,10,11,11,7,2,9,10,3,4,1,6,8,2,8,9,6,10,9,3,5,4,6,7,5,4,5,3,10,5~1,2,2,3,3,4,5,6,7,8,8,9,9,10,10,11,11~9,4,7,11,5,5,8,11,7,4,5,6,11,9,7,9,8,8,5,6,10,10,6,4,8,1,9,10,11,3,9,8,4,6,1,10,7,8,8,11,3,3,9,10,3,10,8,6,8,5,10,9,3,3&reel_set9=8,9,5,10,9,10,8,4,11,1,6,10,4,11,7,5,3,9,3~9,9,10,8,9,8,10,11,5,9,2,4,11,6,1,3,2,6,11,11,5,2,7~4,6,6,10,2,10,9,9,5,9,3,11,3,2,11,7,7,2,5,9,4,8,2,1,6,4,11,1,10,10,8,4,3,5,4,3,2,10,8,8,9,5,10,11,11,6,7,9,9,5,8,8,6,11~4,2,7,2,10,6,9,3,8,11,1,5,11,6,5,8,3,10,9,11,9,10,2~3,7,8,4,5,8,9,1,5,5,8,7,11,11,9,10,9,3,6,6,10,7,8,8,3,11,9,9,8,3,6,10,6,9,1,11,4,8,5,10,11,4,10,9,9,10,5,10,11,4,3,10,4,7,6&total_bet_min=10.00';
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
            else if( $slotEvent['slotEvent'] == 'doSpin' ) 
            {
                $lastEvent = $slotSettings->GetHistory();
                $pur = -1;
                if(isset($slotEvent['pur'])){
                    $pur = $slotEvent['pur'];
                }
                $slotEvent['slotBet'] = $slotEvent['c'];
                $slotEvent['slotLines'] = 20;
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
                $allBet = $betline * $lines;
                if($pur >= 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $allBet * 75;
                }
                $freeStacks = []; 
                $isGeneratedFreeStack = false;
                $slotSettings->SetGameData($slotSettings->slotId . 'ScatterCount', 0);
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
                    if($pur >= 0){                            
                        $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $_sum, $slotEvent['slotEvent'], true);
                        $winType = 'bonus';
                        $_winAvaliableMoney = $slotSettings->GetBank('bonus');
                    }else{
                        $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $_sum, $slotEvent['slotEvent']);
                    }
                    $bonusMpl = 1;
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FSOPT', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.4f', microtime(TRUE));
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
                $strWinLine = '';
                $winLineCount = 0;
                $str_accm = '';
                $str_accv = '';
                $str_acci = '';
                $str_lm_v = '';
                $str_lm_m = '';
                $str_fs_opt = '';
                $str_fs_opt_mask = '';
                $fsmax = 0;
                $fsmore = 0;
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $str_accm = $stack['accm'];
                    $str_accv = $stack['accv'];
                    $str_acci = $stack['acci'];
                    $str_lm_m = $stack['lm_m'];
                    $str_lm_v = $stack['lm_v'];
                    $str_fs_opt = $stack['fs_opt'];
                    $str_fs_opt_mask = $stack['fs_opt_mask'];
                    $fsmax = $stack['fsmax'];
                    $strWinLine = $stack['win_line'];
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
                    $str_accm = $stack[0]['accm'];
                    $str_accv = $stack[0]['accv'];
                    $str_acci = $stack[0]['acci'];
                    $str_lm_m = $stack[0]['lm_m'];
                    $str_lm_v = $stack[0]['lm_v'];
                    $str_fs_opt = $stack[0]['fs_opt'];
                    $str_fs_opt_mask = $stack[0]['fs_opt_mask'];
                    $fsmax = $stack[0]['fsmax'];
                    $strWinLine = $stack[0]['win_line'];
                    $fsmore = $stack[0]['fsmore'];
                }
                $reels = [];
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;
                $wildReels = [];
                for($i = 0; $i < 15; $i++){
                    if($lastReel[$i] == $scatter){
                        $scatterCount++;
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
                // if( $scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin') 
                // {
                //     if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0 ) 
                //     {
                //         $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                //         $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 8);
                //         $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                //     }
                // }
                if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
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
                    $strOtherResponse = $strOtherResponse . '&fsopt_i=' . $slotSettings->GetGameData($slotSettings->slotId . 'FSOPT');
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                    if($scatterCount >= 3 ){
                        $isState = false;
                        $spinType = 'fso';
                        $slotSettings->SetGameData($slotSettings->slotId . 'ScatterCount', $scatterCount);
                        // $strOtherResponse = '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fs=1&fsres=0.00';
                    }
                }
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                }                        
                if($pur >= 0){
                    $strOtherResponse = $strOtherResponse . '&purtr=1';
                }
                if($str_lm_v != ''){
                    $strOtherResponse = $strOtherResponse . '&lm_v='. $str_lm_v .'&lm_m='. $str_lm_m;
                }
                if($str_accv != ''){
                    $strOtherResponse = $strOtherResponse . '&accm=' . $str_accm . '&accv=' . $str_accv . '&acci=' . $str_acci;
                }
                if($str_fs_opt != ''){
                    $strOtherResponse = $strOtherResponse . '&fs_opt_mask='. $str_fs_opt_mask .'&fs_opt='. $str_fs_opt;
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&c='.$betline.'&sver=5&reel_set='.$currentReelSet.'&w='.$totalWin.'&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&s='.$strLastReel;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)) 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin')  . ',"ScatterCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'ScatterCount')  . ',"FSOPT":' . $slotSettings->GetGameData($slotSettings->slotId . 'FSOPT') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($slotEvent['slotEvent'] == 'freespin' && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * 75;
                }
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if( $scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin') 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                }
            }else if($slotEvent['slotEvent'] == 'doFSOption'){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lastReel = $lastEvent->serverResponse->LastReel;
                $ind = -1;
                if(isset($slotEvent['ind'])){
                    $ind = $slotEvent['ind'];
                }
                $lines = 20;
                $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                $scatterCount = $slotSettings->GetGameData($slotSettings->slotId . 'ScatterCount');
                if($scatterCount == 0){
                    $response = 'unlogged';
                    exit( $response );
                }
                $Balance = $slotSettings->GetBalance();
                $stack = $slotSettings->GetReelStrips('bonus', $betline * $lines, $ind, $scatterCount);
                if($stack == null){
                    $response = 'unlogged';
                    exit( $response );
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $stack);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                $fsmax = $stack[0]['fsmax'];
                $fs_opt = $stack[0]['fs_opt'];
                $fs_opt_mask = $stack[0]['fs_opt_mask'];
                $slotSettings->SetGameData($slotSettings->slotId . 'FSOPT', $ind);

                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $fsmax);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                $slotSettings->SetGameData($slotSettings->slotId . 'ScatterCount', 0);

                $response = 'fsmul=1&fs_opt_mask='. $fs_opt_mask .'&balance='.$Balance.'&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&index='.$slotEvent['index'].'&balance_cash='.$Balance .'&balance_bonus=0.00&na=s&fswin=0.00&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&fs=1&fs_opt='. $fs_opt .'&fsres=0.00&sver=5&counter='. ((int)$slotEvent['counter'] + 1) . '&fsopt_i=' . $ind;

                
                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------
                
                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin')  . ',"ScatterCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'ScatterCount')  . ',"FSOPT":' . $slotSettings->GetGameData($slotSettings->slotId . 'FSOPT') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $slotSettings->SaveLogReport($_GameLog, $betline * $lines, $lines, 0, $slotEvent['slotEvent'], false);
            }
            if($slotEvent['action'] == 'doSpin' || $slotEvent['action'] == 'doFSOption' || $slotEvent['action'] == 'doCollect' || $slotEvent['action'] == 'doCollectBonus'){
                $this->saveGameLog($slotEvent, $response, $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'), $slotSettings);
            }
            try{
                $slotSettings->SaveGameData();
                \DB::commit();
            }catch (\Exception $e) {
                $slotSettings->InternalError('TheDogHouseMegawaysDBCommit : ' . $e);
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

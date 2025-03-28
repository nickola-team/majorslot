<?php 
namespace VanguardLTE\Games\FrogsAndBugsPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 25);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [14,14,14,14,14,14,14,14,14,14,14,14,4,10,3,9,5,13,5,8,11,13,4,9,6,12,6,9,3,13]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $lastEvent->serverResponse->TumbWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', $lastEvent->serverResponse->TumbleState);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);
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
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                $fsmore = 0;
                $rs_p = -1;
                if(isset($stack)){
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $str_s_mark = $stack['s_mark'];
                    $fsmore = $stack['fsmore'];
                    $strWinLine = $stack['wlc_v'];
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    if($rs_p >= 0){
                        $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p . '&rs_c='. $rs_c .'&rs_m=' . $rs_m;
                    }
                    else if($rs_t > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_t='.$rs_t.'&rs_win='.($slotSettings->GetGameData($slotSettings->slotId . 'TumbWin'));
                    }
                    if($str_s_mark != ''){
                        $strOtherResponse = $strOtherResponse . '&s_mark=' . $str_s_mark;
                    }
                    $pw = 0;
                    if($strWinLine != ''){
                        $arr_lines = explode(';', $strWinLine);
                        for($k = 0; $k < count($arr_lines); $k++){
                            $arr_sub_lines = explode('~', $arr_lines[$k]);
                            $arr_sub_lines[1] = str_replace(',', '', $arr_sub_lines[1]) / $original_bet * $bet;
                            $pw = $pw + $arr_sub_lines[1];
                            $arr_lines[$k] = implode('~', $arr_sub_lines);
                        }
                        $strWinLine = implode(';', $arr_lines);
                        $strOtherResponse = $strOtherResponse . '&wlc_v=' . $strWinLine;
                    }
                    if($pw > 0){
                        $strOtherResponse = $strOtherResponse . '&pw=' . $pw;
                    }
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0 || $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 )
                    {
                        $fs = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                        if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) || ($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0)) 
                        {
                            $strOtherResponse = $strOtherResponse . '&fs_total='.($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 1).'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                            if($rs_p >= 0){
                                $strOtherResponse = $strOtherResponse . '&fsend_total=0';
                            }else{
                                $strOtherResponse = $strOtherResponse . '&fsend_total=1';
                            }                        
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
                $response = 'def_s=14,14,14,14,14,14,14,14,14,14,14,14,4,10,3,9,5,13,5,8,11,13,4,9,6,12,6,9,3,13&balance='. $Balance .'&cfgs=5952&ver=3&index=1&balance_cash='. $Balance .'&def_sb=3,8,5,9,7,10&reel_set_size=4&def_sa=7,9,8,3,7,9&reel_set=0&balance_bonus=0.00&na=s&scatters=1~35,30,25,20,15,10,5,0,0,0~0,0,0,0,0,0,0,0,0,0~1,1,1,1,1,1,1,1,1,1&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"1333333333",max_rnd_win:"3000"}}&wl_i=tbm~3000&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=7,9,8,3,7,9&sb=3,8,5,9,7,10&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=40.00&purInit_e=1&sh=5&wilds=2~0,0,0,0,0,0~1,1,1,1,1,1&bonuses=0&st=rect&c='.$bet.'&sw=6&sver=5&counter=2&paytable=0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0;50,30,20,15,0,0;30,25,15,10,0,0;25,20,12,7,0,0;20,15,10,5,0,0;15,10,7,4,0,0;10,7,5,3,0,0;7,5,3,2,0,0;7,5,3,2,0,0;4,3,2,1,0,0;4,3,2,1,0,0;4,3,2,1,0,0;0,0,0,0,0,0&l=25&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=12,9,12,13,9,12,10,12,10,4,11,5,9,11,10,5,13,6,13,6,11,13,7,9,10,9,8,4,13,5,8,7,8,3,13,12,10,5,10,9,11,13,7,4,3,11,10,9,12,3,6,8,13,7,4,11,12,10,13,12,11,8,9~13,11,12,10,12,4,13,7,3,10,7,8,11,10,1,8,5,9,13,10,4,9,6,8,12,11,9,3,11,10,11,4,10,1,12,5,13,10,7,13,7,12,5,11,9,11,13,13,10,9,12,5,9,12,1,9,7,12,11,13,3,6,13,12,8,6,3,5,9,4,6,13,12,13,6~8,9,11,4,12,11,13,3,9,6,13,10,11,10,5,13,11,10,12,9,5,10,13,9,11,12,4,13,12,9,7,6,12,3,11,10,8,9,1,7,5,8,11,4,12,9,5,7,10,13,6,7,13,12,10,1,9,3,10,12,13,6,8,4,11~10,1,9,3,11,10,4,3,11,13,5,9,9,9,13,12,7,11,3,12,9,10,13,11,6,8,10,12,11,10,5,10,11,8,11,4,9,8,9,9,10,8,11,13,9,1,13,11,13,6,4,7,13,9,7,12,5,4,3,6,13,12,13,12,6,10,13,12,5,9,4,7,11,12,9,6,12,7,11~9,5,12,1,4,10,12,13,11,8,11,9,13,11,10,5,12,5,4,13,3,10,11,9,7,9,12,11,1,5,10,13,11,1,10,3,5,9,6,12,1,3,7,10,9,13,12,11,6,10,8,9,6,7,4,9,6,7,13,8,13,8,5,4,3,8,7,10,12,10,11,3,13,11,13,7,9,13,12,3,10~10,12,4,11,13,11,4,12,10,9,10,5,12,11,6,8,6,13,10,5,12,7,8,7,13,10,7,9,6,3,9,3,11,13,11,13,3,11,13,9,12,9,13,4,6,8,9,11,10,7,13,4,9,5,8&s='.$lastReelStr.'&reel_set2=12,9,11,12,4,9,11,12,4,12,11,10,7,5,12,9,12,9,6,9,3,12,12,12,12,5,10,3,8,12,5,11,8,10,6,7,4,11,12,7,12,12,6,8,11,12,12,10,9~3,13,1,7,11,1,11,9,5,1,11,5,9,1,3,1,13,1,11,9,1,11,13,1,11,13,3,1,13,1,3,9,1,9,11,7,9,11,1,9,1,5,13,11,1,11,11,13,11,9,7,1,9,5~10,1,6,1,8,1,12,12,8,10,12,10,10,1,13,1,8,12,8,10,1,4,12,6,10,1,13,1,12,1,4,10,1,10,4,1,4,1,6,8,10,1,6,12,12,10,8,1~1,4,10,1,11,1,8,1,3,1,3,11,12,11,10,1,4,1,9,4,13,1,5,10,7,13,10,1,5,1,11,1,12,1,8,1,13,1,3,10,9,4,6,7,1,10,11,8,11,13,6,1,12,1,13,6,1,7,1,9,10,1,13,12,1,10,1,10,11,3,12,7,1,12,11,9,9~13,1,8,13,10,1,10,1,5,1,8,9,1,7,1,13,1,4,1,10,5,4,1,3,1,12,7,4,11,1,10,8,1,9,11,10,6,4,7,1,13,12,9,1,11,9,1,7,6,13,1,3,12,1,10,13,12,13,1,8,1,6,12,11,1,10,13,1,11,13,9,3,12,1,9,1~9,12,11,10,4,5,12,8,4,12,8,13,10,9,5,12,7,5,12,7,11,10,8,11,6,3,11,4,7,13,12,13,4,10,11,13,10,10,9,3,9,5,3,13,6,8,7,13,9,7,6,11,13,9,13,6&reel_set1=12,11,7,13,11,5,8,5,13,9,10,11,8,3,13,12,9,6,11,8,11,13,6,13,5,12,4,13,6,10,5,6,7,3,8,4,13,9,12,9,8,13,6,12,11,9,8,12,13,7,4,12,9,13,4,11,7,13,12,9,12,11,10,11,10,6,10,9,11,3,7,3,12,10,12,13,10,13,6~10,11,10,7,10,11,5,11,13,12,9,11,3,11,10,13,6,7,12,4,7,13,12,6,8,13,11,13,8,3,4,10,3,12,9,8,7,8,9,12,13,7,13,11,12,4,3,12,11,9,6,12,9,10,9,6,5,13,10,13,4,7,12,6,5,10,12,9,5,13,11~13,10,12,13,3,8,5,13,12,8,13,12,10,8,11,8,13,6,13,7,9,10,11,6,3,9,11,4,10,9,11,12,9,6,12,4,10,12,5,3,9,8,11,10,7,13,13,11,11,6,12,3,11,11,7,6,10,11,10,4,9,12,7,11,5,13,13,9,10,12,4,8,5,12,7~7,11,13,4,10,6,12,13,11,13,5,12,8,12,5,9,10,11,7,13,10,9,6,11,13,11,13,4,9,10,12,5,13,11,8,10,9,11,6,13,7,13,12,7,4,9,8,13,9,3,13,3,11,11,3,6,12,7,12,10,9,11,12,6,13,11,4,8~8,9,13,11,12,10,11,13,8,6,7,5,10,13,9,13,5,10,7,13,10,12,13,5,9,12,6,11,3,8,7,10,12,6,8,4,9,13,3,4,11,8,10,12,4,3,12,11,12,9,10,7,6,9~13,11,13,7,10,12,13,3,11,12,13,6,9,12,5,6,13,7,3,13,10,11,12,11,12,10,9,5,13,8,10,13,5,12,10,12,10,13,9,11,3,7,8,10,5,8,11,9,13,11,6,11,4,9,11,13,7,12,4,11,9,5,8,10,8,12,6,13,8,4,7,3,8,9,10,9&purInit=[{bet:2500,type:"default"}]&reel_set3=11,3,12,13,12,10,13,9,10,11,4,13,10,6,9,7,11,10,4,13,8,9,12,6,8,9,5,8,7,10,3,13,11,12,13,7,3,9,11,13,12,9,12,13,5,11,13,5,11,12,9,8,4,10,6,8,12,10,8,7,6,12~10,11,10,13,12,7,8,3,5,6,11,12,5,9,13,11,13,9,5,3,13,12,7,9,12,9,8,13,8,13,6,8,10,7,12,11,12,9,12,4,10,6,8,13,12,13,4,7,11,7,10,12,11,4,13,6,5,11,9,4,10,11,3~13,12,7,12,11,12,10,11,8,10,8,9,7,6,11,3,13,12,9,13,12,8,11,12,4,9,4,5,9,7,10,5,13,10,6,3,11,12,13,8,6,10,5,13,9,13,9,13,7,6,11,6,4,8,13,5,11,12,9,3,4,11,10,8,12,11,5,12,9,3,9,10,12,10,11,10,13,6,11,12,10,7,11,13,7,13,4,11~10,7,10,12,11,13,11,6,11,9,7,11,12,11,8,7,4,11,10,13,12,8,12,9,11,9,4,11,7,3,13,5,13,4,13,11,6,8,11,9,12,13,8,6,10,5,3,5,12,3,7,13,6,12,9,5,13,6,10,3,13,12,8,10,13,11,9,10,9,13,8,13,13,4~12,11,10,11,10,5,4,12,5,13,6,7,9,8,11,12,6,11,9,7,8,13,12,9,13,12,10,6,13,9,3,10,6,11,10,12,8,3,7,4,8,13,11,13,9,12,5,11,10,5,7,4,12,13,9,12,9,7,5,8,10,4,12,13,10,4,10,13,7,11,8,13,3,7,6,9,12,8,11,13,3,13,9,10,12~11,10,13,5,3,10,11,9,4,5,13,12,13,7,13,8,10,11,10,11,6,8,3,12,7,5,4,5,8,9,12,10,6,12,7,3,9,13,11,12,6,12,8,11,13,4,10,9,13,9,10,13,6,9&total_bet_min=8.00';
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
                $pur = -1;
                if(isset($slotEvent['pur'])){
                    $pur = $slotEvent['pur'];
                }
                $slotEvent['slotBet'] = $slotEvent['c'];
                $slotEvent['slotLines'] = 25;
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    $slotEvent['slotEvent'] = 'freespin';
                }
                $isTumb = false;
                if($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') >= 0){
                    $isTumb = true;
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
                // $winType = 'win';
                $allBet = $betline * $lines;
                if($pur >= 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $allBet * 100;
                }
                $tumbAndFreeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'freespin' || $isTumb == true){
                    if($slotEvent['slotEvent'] == 'freespin' && $isTumb == false){
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    }
                    $leftFreeGames = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') - $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame'); 
                    $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                    if($isTumb == false){
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', -1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                    }
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur);
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
                $this->winLines = [];
                $bonusMpl = 1;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $rs_p = -1;
                $rs_m = 0;
                $rs_c = 0;
                $rs_t = 0;
                $rs_more = 0;
                $str_s_mark = '';
                $subScatterReel = null;
                $str_sa = '';
                $str_sb = '';
                if($slotEvent['slotEvent'] == 'freespin' || $isTumb == true){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $rs_more = $stack['rs_more'];
                    $str_s_mark = $stack['s_mark'];
                    $fsmore = $stack['fsmore'];
                    $strWinLine = $stack['wlc_v'];
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
                    $rs_p = $stack[0]['rs_p'];
                    $rs_c = $stack[0]['rs_c'];
                    $rs_m = $stack[0]['rs_m'];
                    $rs_t = $stack[0]['rs_t'];
                    $fsmore = $stack[0]['fsmore'];
                    $rs_more = $stack[0]['rs_more'];
                    $str_s_mark = $stack[0]['s_mark'];
                    $strWinLine = $stack[0]['wlc_v'];
                    $str_sa = $stack[0]['sa'];
                    $str_sb = $stack[0]['sb'];
                }
                $reels = [];
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;
                for($i = 0; $i < 30; $i++){
                    if($lastReel[$i] == $scatter){
                        $scatterCount++;
                        $scatterPoses[] = $i;
                    }
                }
                $pw = 0;
                if($strWinLine != ''){
                    $arr_lines = explode(';', $strWinLine);
                    for($k = 0; $k < count($arr_lines); $k++){
                        $arr_sub_lines = explode('~', $arr_lines[$k]);
                        $arr_sub_lines[1] = str_replace(',', '', $arr_sub_lines[1]) / $original_bet * $betline;
                        $pw = $pw + $arr_sub_lines[1];
                        $arr_lines[$k] = implode('~', $arr_sub_lines);
                    }
                    $strWinLine = implode(';', $arr_lines);
                } 

                $spinType = 's';
                $isNewTumb = false;
                if($rs_p >= 0){
                    $isNewTumb = true;
                }else{
                    $totalWin = $pw;
                    $pw = 0;
                    if($scatterCount >= 4){
                        $muls = [0,0,0,0,5,10,15,20,25,30,35];
                        $scatterWin = $betline * $lines * $muls[$scatterCount];
                        $totalWin = $totalWin + $scatterWin;
                    }
                }
                if( $totalWin > 0) 
                {
                    $spinType = 'c';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }
                $_obf_totalWin = $totalWin;
                $isState = true;
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', $rs_p);
                if($isNewTumb == true){
                    $isState = false;
                    $spinType = 's';
                }else{
                    if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                    }
                }
                $strLastReel = implode(',', $lastReel);

                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $strOtherResponse = '';
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') + $totalWin);
                    $spinType = 's';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $isEnd = false;
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                    {
                        $isEnd = true;
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        if($isNewTumb == true){
                            $strOtherResponse = $strOtherResponse . '&fsend_total=0';
                            $isState = false;
                        }else{
                            $strOtherResponse = $strOtherResponse . '&fsend_total=1';
                            $spinType = 'c';
                        }
                    }
                    else
                    {
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 's';
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                    }
                    if($fsmore > 0){
                        $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') + $totalWin);
                    if($isNewTumb == false){
                        if($scatterCount >=4){
                            $isState = false;
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 8);
                            $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                            $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                            $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1&psym=1~'. $scatterWin .'~' . implode(',', $scatterPoses);
                            $spinType = 's';
                        }else{
                            if($slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') > 0){
                                $spinType = 'c';
                            }
                        }
                        if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                            $strOtherResponse = $strOtherResponse . '&purtr=1&puri=' . $pur;
                        }
                    }
                }
                if($rs_p >= 0){
                    if($rs_p == 0){
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                    }else{
                        $strOtherResponse = $strOtherResponse . '&rs_more=1&rs_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin');
                    }
                    $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p . '&rs_c='. $rs_c .'&rs_m=' . $rs_m;
                }
                else if($rs_t > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_t='.$rs_t.'&rs_win=' . ($slotSettings->GetGameData($slotSettings->slotId . 'TumbWin'));
                }
                if($str_s_mark != ''){
                    $strOtherResponse = $strOtherResponse . '&s_mark=' . $str_s_mark;
                }
                if($pw > 0){
                    $strOtherResponse = $strOtherResponse . '&pw=' . $pw;
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&wlc_v=' . $strWinLine;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse  .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='. $str_sa .'&sb='. $str_sb .'&st=rect&c='.$betline.'&sh=5&sw=6&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=25&w='.$totalWin.'&s=' . $strLastReel;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)  && $isNewTumb == false) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if($isNewTumb == false){
                    if( $slotEvent['slotEvent'] != 'freespin' && $scatterCount >= 4) 
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin'));
                    }
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"TumbWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"TumbleState":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState')  . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')  .',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($slotEvent['slotEvent'] == 'freespin' && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * 100;
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

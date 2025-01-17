<?php 
namespace VanguardLTE\Games\SuperJokerPM
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
            $original_bet = 0.2;
            if( $slotEvent['slotEvent'] == 'doInit' ) 
            { 
                $lastEvent = $slotSettings->GetHistory();
                $_obf_StrResponse = '';
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 5);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [3,4,5,3,4,5,3,4,5]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $lastEvent->serverResponse->CurrentRespin);
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
                        $FreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                        $stack = $FreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') -1];
                    }
                    $bet = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $bet = '200.00';
                }
                $spinType = 's';
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if(isset($stack)){
                    $str_initReel = $stack['init_reel'];
                    $rs_p = $stack['rs_p'];
                    $rs_m = $stack['rs_m'];
                    $rs_c = $stack['rs_c'];
                    $rs_t = $stack['rs_t'];
                    $str_n_rsl = $stack['n_rsl'];
                    $str_rs = $stack['rs'];
                    $rs_more = $stack['rs_more'];
                    $str_sty = $stack['sty'];
                    $str_wof_status = $stack['wof_status'];
                    $str_wof_mask = $stack['wof_mask'];
                    $str_wof_set = $stack['wof_set'];
                    $bw = $stack['bw'];
                    $end = $stack['end'];
                    $bgt = $stack['bgt'];
                    $wp = $stack['wp'];
                    $bgid = $stack['bgid'];
                    $wof_wi = $stack['wof_wi'];
                    if($stack['reel_set'] > -1){
                        $currentReelSet = $stack['reel_set'];
                    }
                    $rw = 0;
                    $coef = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    if($wp > 0){
                        $coef = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') / ($wp+1);
                        $rw = $coef * $wp;
                    }                   
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    if($rs_p >= 0){
                        $strOtherResponse = $strOtherResponse . '&rs='. $str_rs .'&rs_p='. $rs_p .'&rs_c='. $rs_c .'&rs_m=' . $rs_m;
                    }else if($rs_t > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_t='. $rs_t .'&rs_win=' . $coef;
                    }
                    if(($bw == 1 && $end == 0) || ($end==1 && $wp==0)){
                        $strOtherResponse = $strOtherResponse . '&coef='. $coef .'&level=0&rw=0.00&bgt='. $bgt .'&lifes=1&bw=1&wp='. $wp .'&end=' . $end;
                        $spinType = 'b';
                    }else if($end == 1){
                        $strOtherResponse = $strOtherResponse . '&coef='. $coef .'&level=0&rw='. $rw .'&bgt='. $bgt .'&lifes=1&bw=1&wp='. $wp .'&end=' . $end; 
                    }
                    if($str_n_rsl != ''){
                        $strOtherResponse = $strOtherResponse . '&n_rsl=' . $str_n_rsl;
                    }
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($rs_more > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_more=' . $rs_more;
                    }
                    if($str_sty != ''){
                        $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                    }
                    if($str_wof_status != ''){
                        $strOtherResponse = $strOtherResponse . '&wof_status=' . $str_wof_status . '&wof_mask=' . $str_wof_mask . '&wof_set=' . $str_wof_set;
                    }
                    if($bgid > 0){
                        $strOtherResponse = $strOtherResponse . '&bgid=' . $bgid;
                    }
                    if($wof_wi >= 0){
                        $strOtherResponse = $strOtherResponse . '&wof_wi=' . $wof_wi;
                    }
                }                
                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=3,4,5,3,4,5,3,4,5&balance='. $Balance . $strOtherResponse .'&cfgs=2479&ver=2&index=1&balance_cash='. $Balance .'&reel_set_size=17&def_sb=3,4,5&def_sa=3,4,5&reel_set='. $currentReelSet .'&balance_bonus=0.00&na='. $spinType.'&scatters=1~0,0,0~0,0,0~1,1,1&gmb=0,0,0&rt=d&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa=3,4,5&sb=3,4,5&reel_set10=5,5,5,6,3,7,8,4,6,4,4,4,7,2,2,4,10,7,8,9,2,10,10,10,7,6,6,6,2,2,9,9,9,7,10,10,6,7,3,3,3~5,5,5,6,3,7,8,4,6,4,4,4,7,2,2,4,10,7,8,9,2,10,10,10,7,6,6,6,2,2,9,9,9,7,10,10,6,7,3,3,3~5,5,5,6,3,7,8,4,6,4,4,4,7,2,2,4,10,7,8,9,2,10,10,10,7,6,6,6,2,2,9,9,9,7,10,10,6,7,3,3,3&sc='. implode(',', $slotSettings->Bet) .'&defc=200.00&reel_set11=3,3,9,5,5,5,4,6,4,4,4,8,8,8,7,9,3,9,10,7,4,5,10,6,6,7,6,2,2,2~3,3,9,5,5,5,4,6,4,4,4,8,8,8,7,9,3,9,10,7,4,5,10,6,6,7,6,2,2,2~3,3,9,2,2,2,5,5,5,4,6,4,4,4,7,9,3,9,10,7,4,5,10,6,8,8,8,6,7,6&reel_set12=6,7,8,3,4,6,8,2,2,4,5,5,5,10,8,3,9,2,10,10,10,8,4,4,4,2,6,6,6,2,9,9,9,8,10,10,6,8,7,7,7~6,7,8,3,4,6,8,2,2,4,5,5,5,10,8,3,9,2,10,10,10,8,4,4,4,2,6,6,6,2,9,9,9,8,10,10,6,8,7,7,7~6,7,8,3,4,6,8,2,2,4,5,5,5,10,8,3,9,2,10,10,10,8,4,4,4,2,6,6,6,2,9,9,9,8,10,10,6,8,7,7,7&reel_set13=8,8,3,5,5,5,4,6,4,4,4,7,3,8,3,10,7,4,5,10,6,9,9,9,6,7,6,2,2,2~8,8,3,5,5,5,4,6,4,4,4,9,9,9,7,3,8,2,2,2,3,10,7,4,5,10,6,6,7,6~8,8,3,2,2,2,5,5,5,4,6,4,4,4,7,3,8,3,10,7,4,5,10,6,9,9,9,6,7,6&sh=3&wilds=2~75,0,0~1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&counter=2&reel_set14=7,7,7,6,7,9,8,4,3,3,3,4,4,4,6,9,2,2,4,10,9,8,3,2,10,10,10,9,6,6,6,2,5,5,5,2,9,10,10,6,9~7,7,7,6,7,9,8,4,3,3,3,4,4,4,6,9,2,2,4,10,9,8,3,2,10,10,10,9,6,6,6,2,5,5,5,2,9,10,10,6,9~7,7,7,6,7,9,8,4,3,3,3,4,4,4,6,9,2,2,4,10,9,8,3,2,10,10,10,9,6,6,6,2,5,5,5,2,9,10,10,6,9&paytable=0,0,0;0,0,0;0,0,0;25,0,0;15,0,0;10,0,0;7,0,0;6,0,0;5,0,0;3,0,0;2,0,0&l=5&reel_set15=8,8,9,5,5,5,4,6,4,4,4,10,10,10,7,9,8,9,3,7,4,5,3,6,6,7,6,2,2,2~8,8,9,5,5,5,4,6,4,4,4,10,10,10,7,9,8,9,3,7,4,5,3,6,6,7,6,2,2,2~8,8,9,2,2,2,5,5,5,4,6,4,4,4,7,9,8,9,3,7,4,5,3,6,10,10,10,6,7,6&reel_set16=6,7,10,8,4,6,10,9,9,9,6,6,6,2,2,4,3,10,8,9,2,3,3,3,10,4,4,4,2,5,5,5,2,10,3,3,6,10,7,7,7~6,7,10,8,4,6,10,4,4,4,6,6,6,2,2,4,3,10,8,9,2,3,3,3,10,2,5,5,5,2,7,7,7,9,9,9,10,3,3,6,10~6,7,10,8,4,6,10,6,6,6,9,9,9,2,2,4,3,10,8,9,2,3,3,3,10,4,4,4,2,5,5,5,2,7,7,7,10,3,3,6,10&reel_set0=6,2,7,8,8,8,2,2,2,5,5,5,6,6,6,7,7,7,4,4,4,9,9,9,8,8,8,3,3,3,6,2,8,7,7,7,10,10,10,6,6,6,2,2,2,8,8,8,9,9,9,4,4,4~2,2,2,6,6,6,7,7,7,10,10,10,5,5,5,6,6,6,9,9,9,9,4,5,7,7,7,4,10,9,8,8,8,2,2,2,5,5,5,4,4,4,8,8,8,5,2,4,3,3,3,6,6,6,8,8,8,5,5,5~10,10,10,3,3,3,7,7,7,9,9,9,6,6,6,2,2,2,5,5,5,8,2,7,8,8,8,6,6,6,8,9,7,4,4,4,9,9,9,7,7,7,2,2,2,5,5,5,9,9,9,8,8,8,4,4,4&s='.$lastReelStr .'&reel_set2=6,7,3,8,4,6,3,2,7,7,7,2,4,10,3,8,9,2,6,6,6,10,10,10,3,2,5,5,5,2,9,9,9,4,4,4,3,10,10,6,3~6,7,3,8,4,6,3,7,7,7,6,6,6,2,2,4,10,3,8,9,2,4,4,4,10,10,10,3,2,5,5,5,2,9,9,9,3,10,10,6,3~6,7,3,8,4,6,3,6,6,6,2,10,10,10,2,4,10,3,8,9,2,3,4,4,4,7,7,7,2,5,5,5,2,9,9,9,3,10,10,6,3&reel_set1=8,8,9,5,5,5,4,6,3,3,3,7,9,8,9,10,7,4,4,4,4,5,10,6,6,7,6,2,2,2~8,8,9,4,6,4,4,4,7,9,8,9,10,7,4,5,10,6,3,3,3,6,7,6,2,2,2,5,5,5~8,8,9,5,5,5,4,6,4,4,4,3,3,3,7,9,8,9,10,7,4,5,10,6,6,2,2,2,7,6&reel_set4=6,7,4,8,3,6,4,2,7,7,7,2,3,10,4,8,9,2,10,10,10,6,6,6,4,3,3,3,2,5,5,5,2,9,9,9,4,10,10,6,4~6,7,4,8,3,6,4,7,7,7,6,6,6,2,2,3,10,4,8,9,2,10,10,10,4,3,3,3,2,5,5,5,2,9,9,9,4,10,10,6,4~6,7,4,8,3,6,4,6,6,6,2,7,7,7,9,9,9,2,3,10,4,8,9,2,4,3,3,3,2,5,5,5,2,10,10,10,4,10,10,6,4&reel_set3=8,8,9,5,5,5,3,6,3,3,3,7,9,8,9,10,7,3,5,10,6,4,4,4,6,7,6,2,2,2,4,4,4~8,8,9,3,6,5,5,5,3,3,3,4,4,4,7,9,8,9,10,7,3,5,10,6,4,4,4,6,7,6,2,2,2~8,8,9,3,6,3,3,3,4,4,4,5,5,5,7,9,8,2,2,2,9,10,7,3,5,10,6,4,4,4,6,7,6&reel_set6=6,7,5,8,4,6,5,6,6,6,2,4,4,4,7,7,7,9,9,9,2,4,10,5,8,9,2,10,10,10,5,2,3,3,3,2,5,10,10,6,5~6,7,5,8,4,6,5,6,6,6,2,2,4,10,5,8,9,2,10,10,10,7,7,7,5,4,4,4,2,2,9,9,9,5,10,10,6,5,3,3,3~6,7,5,8,4,6,5,10,10,10,6,6,6,2,7,7,7,2,4,10,5,8,9,2,5,9,9,9,4,4,4,2,3,3,3,2,5,10,10,6,5&reel_set5=8,8,9,4,6,4,4,4,7,9,8,2,2,2,9,10,7,4,3,10,6,5,5,5,3,3,3,6,7,6~8,8,9,2,2,2,3,3,3,4,6,4,4,4,7,9,8,9,10,7,4,3,10,6,5,5,5,6,7,6~8,8,9,3,3,3,4,6,5,5,5,7,9,8,9,10,7,4,3,10,6,5,5,5,4,4,4,6,7,6,2,2,2&reel_set8=3,7,6,8,4,3,6,2,7,7,7,2,4,10,6,8,9,2,10,10,10,3,3,3,6,4,4,4,2,5,5,5,2,9,9,9,6,10,10,3,6~3,7,6,8,4,3,6,10,10,10,3,3,3,2,7,7,7,2,4,10,6,8,9,2,6,4,4,4,2,5,5,5,2,9,9,9,6,10,10,3,6~3,7,6,8,4,3,6,3,3,3,2,2,4,10,6,8,9,2,10,10,10,6,4,4,4,2,5,5,5,2,9,9,9,6,10,10,3,6,7,7,7&reel_set7=8,8,9,5,5,5,4,3,7,9,8,9,10,7,4,5,10,3,6,6,6,3,7,3,2,2,2,4,4,4~8,8,9,2,2,2,5,5,5,4,3,4,4,4,7,9,8,9,10,7,4,5,10,3,6,6,6,3,7,3~8,8,9,5,5,5,4,3,6,6,6,7,9,8,2,2,2,9,10,7,4,5,10,3,3,7,3,4,4,4&reel_set9=8,8,9,5,5,5,4,6,4,4,4,3,9,8,9,10,3,4,5,10,6,7,7,7,6,3,6,2,2,2~8,8,9,5,5,5,4,6,4,4,4,7,7,7,3,9,8,9,10,3,4,5,10,6,6,3,6,2,2,2~8,8,9,2,2,2,5,5,5,4,6,4,4,4,3,9,8,9,10,3,4,5,10,6,7,7,7,6,3,6';
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
                $lines = 5;      
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
                $linesId = [];
                $linesId[0] = [2,2,2];
                $linesId[1] = [1,1,1];
                $linesId[2] = [3,3,3];
                $linesId[3] = [1,2,3];
                $linesId[4] = [3,2,1];
                $slotEvent['slotBet'] = $slotEvent['c'];
                $slotEvent['slotLines'] = 5;
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') >= 0 ) 
                {
                    $slotEvent['slotEvent'] = 'respin';
                }
                $lines = $slotEvent['slotLines'];
                $betline = $slotEvent['slotBet'];
                if( $slotEvent['slotEvent'] == 'doSpin' || $slotEvent['slotEvent'] == 'respin' ) 
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
                    if($slotEvent['slotEvent'] == 'respin'){
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
                $freeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'respin'){
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.1f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '57409' . substr($roundstr, 2, 9);
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
                $rs_p = -1;
                $rs_c = 0;
                $rs_m = 0;
                $rs_t = 0;
                $rs_more=0;
                $str_n_rsl = '';
                $str_rs = '';
                $str_initReel = '';
                $str_sty = '';
                $str_wof_status = '';
                $str_wof_mask = '';
                $str_wof_set = '';
                $bw = 0;
                $end = -1;
                $bgt = 0;
                $wp = 0;
                $bgid = -1;
                $wof_wi = -1;
                $currentReelSet = 0;
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_initReel = $stack['init_reel'];
                    $rs_p = $stack['rs_p'];
                    $rs_m = $stack['rs_m'];
                    $rs_c = $stack['rs_c'];
                    $rs_t = $stack['rs_t'];
                    $str_n_rsl = $stack['n_rsl'];
                    $str_rs = $stack['rs'];
                    $rs_more = $stack['rs_more'];
                    $str_sty = $stack['sty'];
                    $str_wof_status = $stack['wof_status'];
                    $str_wof_mask = $stack['wof_mask'];
                    $str_wof_set = $stack['wof_set'];
                    $bw = $stack['bw'];
                    $end = $stack['end'];
                    $bgt = $stack['bgt'];
                    $wp = $stack['wp'];
                    $bgid = $stack['bgid'];
                    $wof_wi = $stack['wof_wi'];
                    $currentReelSet = $stack['reel_set'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $lastReel = explode(',', $stack[0]['reel']);
                    $str_initReel = $stack[0]['init_reel'];
                    $rs_p = $stack[0]['rs_p'];
                    $rs_m = $stack[0]['rs_m'];
                    $rs_c = $stack[0]['rs_c'];
                    $rs_t = $stack[0]['rs_t'];
                    $str_n_rsl = $stack[0]['n_rsl'];
                    $str_rs = $stack[0]['rs'];
                    $rs_more = $stack[0]['rs_more'];
                    $str_sty = $stack[0]['sty'];
                    $str_wof_status = $stack[0]['wof_status'];
                    $str_wof_mask = $stack[0]['wof_mask'];
                    $str_wof_set = $stack[0]['wof_set'];
                    $bw = $stack[0]['bw'];
                    $end = $stack[0]['end'];
                    $bgt = $stack[0]['bgt'];
                    $wp = $stack[0]['wp'];
                    $bgid = $stack[0]['bgid'];
                    $wof_wi = $stack[0]['wof_wi'];
                    $currentReelSet = $stack[0]['reel_set'];
                }

                $reels = [];
                for($i = 0; $i < 3; $i++){
                    $reels[$i] = [];
                    for($j = 0; $j < 3; $j++){
                        $reels[$i][$j] = $lastReel[$j * 3 + $i];
                    }
                }
                $_lineWinNumber = 1;
                $_obf_winCount = 0;
                for( $k = 0; $k < $lines; $k++ ) 
                {
                    $_lineWin = '';
                    $firstEle = $reels[0][$linesId[$k][0] - 1];
                    $lineWinNum[$k] = 1;
                    $lineWins[$k] = 0;
                    for($j = 1; $j < 3; $j++){
                        $ele = $reels[$j][$linesId[$k][$j] - 1];
                        if($firstEle == $wild){
                            $firstEle = $ele;
                            $lineWinNum[$k] = $lineWinNum[$k] + 1;
                            if($j == 2){
                                $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                if($lineWins[$k] > 0){
                                    $totalWin += $lineWins[$k];
                                    $_obf_winCount++;
                                    $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                    for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                        $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 3 + $kk);
                                    }
                                }
                            }
                        }else if($ele == $firstEle || $ele == $wild){
                            $lineWinNum[$k] = $lineWinNum[$k] + 1;
                            if($j == 2){
                                $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                if($lineWins[$k] > 0){
                                    $totalWin += $lineWins[$k];
                                    $_obf_winCount++;
                                    $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                    for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                        $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 3 + $kk);
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
                                        $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 3 + $kk);
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
                
                $strLastReel = implode(',', $lastReel);
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 3; $i++){
                    $reelA[$i] = mt_rand(3, 7);
                    $reelB[$i] = mt_rand(3, 7);
                }
                $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
               
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $strOtherResponse = '';
                $isState = true;
                  
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $rs_p);
                if($rs_p >= 0){
                    $isState = false;
                    $spinType = 's';
                    $strOtherResponse = $strOtherResponse . '&rs='. $str_rs .'&rs_p='. $rs_p .'&rs_c='. $rs_c .'&rs_m=' . $rs_m;
                }else if($rs_t > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_t='. $rs_t .'&rs_win=' . $totalWin;
                }
                if($bw == 1){
                    $spinType = 'b';
                    $isState = false;    
                    $strOtherResponse = $strOtherResponse . '&coef='. $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') .'&level=0&rw=0.00&bgt='. $bgt .'&lifes=1&bw=1&wp='. $wp .'&end=' . $end;
                }
                if($str_n_rsl != ''){
                    $strOtherResponse = $strOtherResponse . '&n_rsl=' . $str_n_rsl;
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($rs_more > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_more=' . $rs_more;
                }
                if($str_sty != ''){
                    $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                }
                if($str_wof_status != ''){
                    $strOtherResponse = $strOtherResponse . '&wof_status=' . $str_wof_status . '&wof_mask=' . $str_wof_mask . '&wof_set=' . $str_wof_set;
                }
                if($bgid > 0){
                    $strOtherResponse = $strOtherResponse . '&bgid=' . $bgid;
                }
                if($wof_wi >= 0){
                    $strOtherResponse = $strOtherResponse . '&wof_wi=' . $wof_wi;
                }

                
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse . $strWinLine .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&c='.$betline.'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=5&s='.$strLastReel.'&w='.$totalWin;
                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"CurrentRespin":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') .',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if( $bw == 1) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                }
            }else if( $slotEvent['slotEvent'] == 'doBonus' ){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lastReel = $lastEvent->serverResponse->LastReel;
                $lines = 5;
                $slotEvent['slotEvent'] = 'doRespin';
                $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                if($stack['reel'] != ''){
                    $lastReel = explode(',', $stack['reel']);
                }
                $str_initReel = $stack['init_reel'];
                $rs_p = $stack['rs_p'];
                $rs_m = $stack['rs_m'];
                $rs_c = $stack['rs_c'];
                $rs_t = $stack['rs_t'];
                $str_n_rsl = $stack['n_rsl'];
                $str_rs = $stack['rs'];
                $rs_more = $stack['rs_more'];
                $str_sty = $stack['sty'];
                $str_wof_status = $stack['wof_status'];
                $str_wof_mask = $stack['wof_mask'];
                $str_wof_set = $stack['wof_set'];
                $bw = $stack['bw'];
                $end = $stack['end'];
                $bgt = $stack['bgt'];
                $wp = $stack['wp'];
                $bgid = $stack['bgid'];
                $wof_wi = $stack['wof_wi'];
                $currentReelSet = $stack['reel_set'];
                $coef = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                $rw = 0;
                if($wp > 0){
                    $rw = $coef * $wp;
                }
                
                $totalWin = 0;
                $isState = false;
                $spinType = 'b';
                if($end == 1){
                    $totalWin = $rw;
                }
                if( $totalWin > 0) 
                {
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                    if($end == 1){
                        $isState = true;
                        $spinType = 'cb';
                    }
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                $strOtherResponse = 'bgid='. $bgid .'&bgt='. $bgt .'&end='. $end .'&rw='. $rw;
                if($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') > 0){
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                
                if($str_wof_status != ''){
                    $strOtherResponse = $strOtherResponse . '&wof_status=' . $str_wof_status . '&wof_mask=' . $str_wof_mask . '&wof_set=' . $str_wof_set;
                }
                if($wof_wi > -1){
                    $strOtherResponse = $strOtherResponse . '&wof_wi=' . $wof_wi;
                }

                $response = $strOtherResponse .'&balance='. $Balance .'&coef='. $coef .'&level=1&index='.$slotEvent['index'].'&balance_cash='. $Balance .'&balance_bonus=0.00&na='. $spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&lifes=0&wp='. $wp .'&sver=5&counter='. ((int)$slotEvent['counter'] + 1);

                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')  . ',"CurrentRespin":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') .',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
            }


            
            if($slotEvent['action'] == 'doSpin' || $slotEvent['action'] == 'doCollect' || $slotEvent['action'] == 'doCollectBonus' || $slotEvent['action'] == 'doBonus'){
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
        public function findZokbos($reels, $firstSymbol, $repeatCount, $positions){
            $wild = '2';
            $bPathEnded = true;
            if($repeatCount < 5){
                for($r = 0; $r < 3; $r++){
                    if($firstSymbol == $reels[$repeatCount][$r] || $reels[$repeatCount][$r] == $wild){
                        $this->findZokbos($reels, $firstSymbol, $repeatCount + 1, array_merge($positions, [($repeatCount + $r * 5)]));
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

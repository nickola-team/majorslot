<?php 
namespace VanguardLTE\Games\HoneyHoneyHoneyPM
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

            $linesId = [];
            $linesId[0] = [2,2,2,2,2];
            $linesId[1] = [1,1,1,1,1];
            $linesId[2] = [3,3,3,3,3];
            $linesId[3] = [1,2,3,2,1];
            $linesId[4] = [3,2,1,2,3];
            $linesId[5] = [2,1,1,1,2];
            $linesId[6] = [2,3,3,3,2];
            $linesId[7] = [1,1,2,3,3];
            $linesId[8] = [3,3,2,1,1];
            $linesId[9] = [2,3,2,1,2];                
            $linesId[10] = [2,1,2,3,2];
            $linesId[11] = [1,2,2,2,1];
            $linesId[12] = [3,2,2,2,3];
            $linesId[13] = [1,2,1,2,1];
            $linesId[14] = [3,2,3,2,3];
            $linesId[15] = [2,2,1,2,2];
            $linesId[16] = [2,2,3,2,2];
            $linesId[17] = [1,1,3,1,1];
            $linesId[18] = [3,3,1,3,3];
            $linesId[19] = [1,3,3,3,1];

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
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'Ind', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [10,9,11,10,10,11,8,8,7,9,3,4,1,6,3]);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $lastEvent->serverResponse->CurrentRespin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Ind', $lastEvent->serverResponse->Ind);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', $lastEvent->serverResponse->Bgt);
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
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $str_rs_f = $stack['rs_f'];
                    $str_rs = $stack['rs'];
                    $rs_more = $stack['rs_more'];
                    $bgt = $stack['bgt'];
                    $level = $stack['level'];
                    $lifes = $stack['lifes'];
                    $bw = $stack['bw'];
                    $end = $stack['end'];
                    $bgid = $stack['bgid'];
                    $str_wins_mask = $stack['wins_mask'];
                    $str_wins = $stack['wins'];
                    $str_srf = $stack['srf'];
                    $str_cwin_p = $stack['cwin_p'];
                    $str_win_p = $stack['win_p'];
                    $str_fstype = $stack['fstype'];
                    $str_rwd = $stack['rwd'];
                    $gwm = $stack['gwm'];
                    $wnd = $stack['wnd'];

                    if($bw == 1){
                        $strOtherResponse = $strOtherResponse .'&bw=1';
                        if($end == 0){
                            $spinType = 'b';
                            $strOtherResponse = $strOtherResponse . '&coef='. ($bet * 20) .'&rw=0.00&wp=0';
                        }
                    }
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($currentReelSet >= 0){
                        $strOtherResponse = $strOtherResponse . '&reel_set=' . $currentReelSet;
                    }
                    if($rs_p >= 0){
                        $strOtherResponse = $strOtherResponse . '&rs='. $str_rs .'&rs_p=' . $rs_p;
                    }
                    if($rs_c > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_c=' . $rs_c;
                    }
                    if($rs_m > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_m=' . $rs_m;
                    }
                    if($rs_t > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t;
                    }
                    if($rs_more > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_more=' . $rs_more;
                    }
                    if($str_rs_f != ''){
                        $strOtherResponse = $strOtherResponse . '&rs_f=' . $str_rs_f;
                    }
                    if($bgt > 0){
                        $strOtherResponse = $strOtherResponse . '&bgt=' . $bgt;                        
                        $ind = $slotSettings->GetGameData($slotSettings->slotId . 'Ind');
                        if($end == 1 && ($bgt == 30 || $bgt ==41)){
                            if($bgt == 41){
                                $arr_wins = [0,0,0];
                                $arr_wins_mask = ['h', 'h', 'h'];
                                $arr_status = [0,0,0];
                
                                $old_wins_mask = explode(',', $str_wins_mask);
                                if($ind >= 0){
                                    $arr_wins[$ind] = 1;
                                    $arr_status[$ind] = 1;
                                    $arr_wins_mask[$ind] = $old_wins_mask[0];
                                }
                                $str_wins = implode(',', $arr_wins);
                                $str_wins_mask = implode(',', $arr_wins_mask);
                                $str_status = implode(',', $arr_status);
                                $strOtherResponse = '&wins='. $str_wins .'&status='. $str_status .'&rw=0.00&wp=0&wins_mask='. $str_wins_mask;
                            }else if($bgt == 30){
                                $arr_status = [0,0];
                                $arr_status[$ind] = 1;
                                $str_status = implode(',', $arr_status);
                                $strOtherResponse = '&wins='. $str_wins .'&status='. $str_status .'&rw=0.00&wins_mask='. $str_wins_mask .'&wp=0&fsres=0.00';
                            }
                        }
                    }
                    if($level > -1){
                        $strOtherResponse = $strOtherResponse . '&level=' . $level;
                    }
                    if($lifes > -1){
                        $strOtherResponse = $strOtherResponse . '&lifes=' . $lifes;
                    }
                    if($end > -1){
                        $strOtherResponse = $strOtherResponse . '&end=' . $end;
                        if($end == 0){                            
                            if($bgt == 40){
                                $spinType = 'b';
                            }
                        }
                    }
                    if($bgid > -1){
                        $strOtherResponse = $strOtherResponse . '&bgid=' . $bgid;
                    }
                    if($str_srf != ''){
                        $strOtherResponse = $strOtherResponse . '&srf=' . $str_srf;
                    }
                    if($str_win_p != ''){
                        $strOtherResponse = $strOtherResponse . '&win_p=' . $str_win_p;
                    }
                    if($str_cwin_p != ''){
                        $strOtherResponse = $strOtherResponse . '&cwin_p=' . $str_cwin_p;
                    }
                    if($str_fstype != ''){
                        $strOtherResponse = $strOtherResponse . '&fstype=' . $str_fstype;
                    }
                    if($str_rwd != ''){
                        $strOtherResponse = $strOtherResponse . '&rwd=' . $str_rwd;
                    }
                    if($wnd >= 0){
                        $strOtherResponse = $strOtherResponse . '&wnd=' . $wnd;
                    }
                    if($gwm > 0){
                        $strOtherResponse = $strOtherResponse . '&wmt=wb&wmv='. $gwm .'&gwm=' . $gwm;
                    }
                    
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0 || $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 )
                {
                    $fs = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                        $strOtherResponse = $strOtherResponse . '&fs=' . $fs . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&fsmul=1';
                    }else if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0){
                        $strOtherResponse = $strOtherResponse . '&fs_total='. ($fs - 1) .'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsend_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                } 
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));

                $Balance = $slotSettings->GetBalance();

                $response = 'def_s=10,9,11,10,10,11,8,8,7,9,3,4,1,6,3&balance='. $Balance .'&cfgs=2542&ver=2&index=1&balance_cash='. $Balance .'&reel_set_size=4&def_sb=3,7,11,10,3&def_sa=9,5,7,11,6&reel_set='.$currentReelSet.$strOtherResponse.'&bonusInit=[{bgid:2,bgt:40,av_symb:"3,4,5,6,7,8,9,10,11"},{bgid:3,bgt:40,av_symb:"3,4,5,6,7,8,9,10,11"}]&balance_bonus=0.00&na='. $spinType .'&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&cls_s=16&gmb=0,0,0&rt=d&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=9,5,7,11,6&sb=3,7,11,10,3&sc='. implode(',', $slotSettings->Bet) .'&defc=50.00&sh=3&wilds=2~500,200,50,2,0~1,1,1,1,1;12~500,200,50,2,0~1,1,1,1,1;13~500,200,50,2,0~1,1,1,1,1;14~500,200,50,2,0~1,1,1,1,1;15~500,200,50,2,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;400,200,40,2,0;300,100,30,0,0;200,80,20,0,0;200,80,20,0,0;150,40,8,0,0;150,40,8,0,0;100,10,4,0,0;100,10,4,0,0;100,10,4,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0&l=20&reel_set0=4,8,7,3,3,3,3,10,6,2,11,5,9,1,8,5,7,10,6,11,4,9,2,8,10,4,7,6,1,5,9,8,5,7~4,8,7,3,3,3,3,10,6,2,11,5,9,8,5,7,10,6,11,4,9,8,2,10,4,7,6,11,5,9,8,5,7~4,8,7,3,3,3,3,10,6,2,11,5,9,1,8,5,7,10,6,11,4,9,2,8,10,4,7,6,1,5,9,8,5,7~4,8,7,3,3,3,3,10,6,2,11,5,9,8,5,7,10,6,11,4,9,8,2,10,4,7,6,11,5,9,8,5,7~4,8,7,3,3,3,3,10,6,2,11,5,9,1,8,5,7,10,6,11,4,9,2,8,10,4,7,6,1,5,9,8,5,7&s='.$lastReelStr.'&reel_set2=4,8,7,3,3,3,3,10,6,11,5,9,2,8,5,7,10,6,11,4,9,8,10,4,2,7,6,11,5,9,8,5,7~4,8,7,3,3,3,3,10,6,11,5,9,2,8,5,7,10,6,11,4,9,8,10,4,2,7,6,11,5,9,8,5,7~4,8,7,3,3,3,3,10,6,11,5,9,2,8,5,7,10,6,11,4,9,8,10,4,2,7,6,11,5,9,8,5,7~4,8,7,3,3,3,3,10,6,11,5,9,2,8,5,7,10,6,11,4,9,8,10,4,2,7,6,11,5,9,8,5,7~4,8,7,3,3,3,3,10,6,11,5,9,2,8,5,7,10,6,11,4,9,8,10,4,2,7,6,11,5,9,8,5,7&reel_set1=4,8,7,3,3,3,3,10,6,11,5,9,8,5,7,10,6,11,4,9,8,10,4,7,6,11,5,9,8,5,7~4,8,7,3,3,3,3,10,6,11,5,9,8,5,7,10,6,11,4,9,8,10,4,7,6,11,5,9,8,5,7~4,8,7,3,3,3,3,10,6,11,5,9,8,5,7,10,6,11,4,9,8,10,4,7,6,11,5,9,8,5,7~4,8,7,3,3,3,3,10,6,11,5,9,8,5,7,10,6,11,4,9,8,10,4,7,6,11,5,9,8,5,7~4,8,7,3,3,3,3,10,6,11,5,9,8,5,7,10,6,11,4,9,8,10,4,7,6,11,5,9,8,5,7&reel_set3=11,9,5,8,10,3,11,4,8,9,7,5,10,6~3,11,4,8,9,7,5,10,6,11,9,5,8,10~6,11,9,5,8,10,3,11,4,8,9,7,5,10~8,9,7,5,10,6,11,9,5,8,10,3,11,4~5,8,10,3,11,4,8,9,7,5,10,6,11,9';
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
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') >= 0) 
                {
                    $slotEvent['slotEvent'] = 'respin';
                }
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
                if($slotEvent['slotEvent'] == 'respin' || $slotEvent['slotEvent'] == 'freespin'){
                    if($slotEvent['slotEvent'] == 'freespin'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    }
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Ind', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
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
                $winLineCount = 0;
                $str_initReel = '';
                $strWinLine = '';
                $rs_p = -1;
                $rs_c = -1;
                $rs_m = -1;
                $rs_t = -1;
                $str_rs_f = '';
                $str_rs = '';
                $rs_more = 0;
                $bgt = 0;
                $level = -1;
                $lifes = -1;
                $bw = 0;
                $end = -1;
                $bgid = -1;
                $str_wins_mask = '';
                $str_wins = '';
                $str_srf = '';
                $str_cwin_p = '';
                $str_win_p = '';
                $str_fstype = '';
                $str_rwd = '';
                $gwm = -1;
                $wnd = -1;
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $str_initReel = $stack['init_reel'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $str_rs_f = $stack['rs_f'];
                    $str_rs = $stack['rs'];
                    $rs_more = $stack['rs_more'];
                    $bgt = $stack['bgt'];
                    $level = $stack['level'];
                    $lifes = $stack['lifes'];
                    $bw = $stack['bw'];
                    $end = $stack['end'];
                    $bgid = $stack['bgid'];
                    $str_wins_mask = $stack['wins_mask'];
                    $str_wins = $stack['wins'];
                    $str_srf = $stack['srf'];
                    $str_cwin_p = $stack['cwin_p'];
                    $str_win_p = $stack['win_p'];
                    $str_fstype = $stack['fstype'];
                    $str_rwd = $stack['rwd'];
                    $gwm = $stack['gwm'];
                    $wnd = $stack['wnd'];
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
                    $rs_p = $stack[0]['rs_p'];
                    $rs_c = $stack[0]['rs_c'];
                    $rs_m = $stack[0]['rs_m'];
                    $rs_t = $stack[0]['rs_t'];
                    $str_rs_f = $stack[0]['rs_f'];
                    $str_rs = $stack[0]['rs'];
                    $rs_more = $stack[0]['rs_more'];
                    $bgt = $stack[0]['bgt'];
                    $level = $stack[0]['level'];
                    $lifes = $stack[0]['lifes'];
                    $bw = $stack[0]['bw'];
                    $end = $stack[0]['end'];
                    $bgid = $stack[0]['bgid'];
                    $str_wins_mask = $stack[0]['wins_mask'];
                    $str_wins = $stack[0]['wins'];
                    $str_srf = $stack[0]['srf'];
                    $str_cwin_p = $stack[0]['cwin_p'];
                    $str_win_p = $stack[0]['win_p'];
                    $str_fstype = $stack[0]['fstype'];
                    $str_rwd = $stack[0]['rwd'];
                    $gwm = $stack[0]['gwm'];
                    $wnd = $stack[0]['wnd'];
                }
                $reels = [];
                $scatterCount = 0;
                for($i = 0; $i < 5; $i++){
                    $reels[$i] = [];
                    for($j = 0; $j < 3; $j++){
                        $reels[$i][$j] = $lastReel[$j * 5 + $i];
                    }
                }
                $_lineWinNumber = 1;
                $_obf_winCount = 0;
                if($bgt != 40 || $end == 1){
                    for( $k = 0; $k < $lines; $k++ ) 
                    {
                        $_lineWin = '';
                        $firstEle = $reels[0][$linesId[$k][0] - 1];
                        $lineWinNum[$k] = 1;
                        $lineWins[$k] = 0;
                        $wildWin = 0;
                        $wildWinNum = 1;
                        $mul = 0;
                        for($j = 1; $j < 5; $j++){
                            $ele = $reels[$j][$linesId[$k][$j] - 1]; 
                            if($firstEle == $wild || ($firstEle >= 13 && $firstEle < 16)){
                                $firstEle = $ele;
                                $lineWinNum[$k] = $lineWinNum[$k] + 1;
                                if($j == 4){
                                    $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                    if($gwm > 0){
                                        $lineWins[$k] = $lineWins[$k] * $gwm;
                                    }
                                    $totalWin += $lineWins[$k];
                                    $_obf_winCount++;
                                    $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                    for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                        $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                    }
                                }else if($j >= 2 && ($ele == $wild || ($ele >= 13 && $ele < 16))){
                                    $wildWin = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                    if($gwm > 0){
                                        $wildWin = $wildWin * $gwm;
                                    }
                                    $wildWinNum = $lineWinNum[$k];
                                }
                            }else if($ele == $firstEle || $ele == $wild || ($ele >= 13 && $ele < 16)){
                                $lineWinNum[$k] = $lineWinNum[$k] + 1;
                                if($j == 4){
                                    $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                    if($gwm > 0){
                                        $lineWins[$k] = $lineWins[$k] * $gwm;
                                    }
                                    if($lineWins[$k] < $wildWin){
                                        $lineWins[$k] = $wildWin;
                                        $lineWinNum[$k] = $wildWinNum;
                                    }
                                    $totalWin += $lineWins[$k];
                                    $_obf_winCount++;
                                    $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                    for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                        $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                    }
                                }
                            }else{
                                if($slotSettings->Paytable[$firstEle][$lineWinNum[$k]] > 0){
                                    $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                    if($gwm > 0){
                                        $lineWins[$k] = $lineWins[$k] * $gwm;
                                    }
                                    if($lineWins[$k] < $wildWin){
                                        $lineWins[$k] = $wildWin;
                                        $lineWinNum[$k] = $wildWinNum;
                                    }
                                    $totalWin += $lineWins[$k];
                                    $_obf_winCount++;
                                    $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                    for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                        $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                    }   
    
                                }else{
                                    $lineWinNum[$k] = 0;
                                }
                                break;
                            }
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
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $rs_p);

                $strLastReel = implode(',', $lastReel);
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 5; $i++){
                    $reelA[$i] = mt_rand(5, 11);
                    $reelB[$i] = mt_rand(5, 11);
                }
                $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
               
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                
                $strOtherResponse = '';
                $isState = true;
                $isEnd = true;
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                if($rs_p >= 0){
                    $isState = false;
                    $spinType = 's';
                }else if($rs_t > 0 && $slotEvent['slotEvent'] == 'respin'){
                    $spinType = 'c';
                    $isEnd = true;
                }
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $spinType = 's';
                    $isEnd = false;
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                    {
                        $spinType = 'c';
                        $isEnd = true;
                        $strOtherResponse = '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fsmul_total=1';
                        if($str_fstype == 'ssf'){
                            $strOtherResponse = $strOtherResponse.'&fswin_total=0&fsres_total=0';
                        }else if($str_fstype == ''){
                            $strOtherResponse = $strOtherResponse.'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres_total='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        }
                    }
                    else
                    {
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                        if($str_fstype == 'ssf'){
                            $strOtherResponse = $strOtherResponse.'&fswin=0&fsres=0';
                        }else if($str_fstype == ''){
                            $strOtherResponse = $strOtherResponse .'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        }
                        $spinType = 's';
                    }
                }else
                {
                    if($scatterCount >= 3 ){
                        $isState = false;
                        $spinType = 's';
                    }
                }
                if($bw == 1 && $end == 0){
                    $isState = false;
                    $spinType = 'b';
                    if($bgt != 40){
                        $strOtherResponse = $strOtherResponse .'&rw=0.00&wp=0&coef='. ($betline * $lines);
                    }                    
                }
                if($bw == 1){
                    $strOtherResponse = $strOtherResponse .'&bw=1';
                }
                if($str_wins != ''){
                    $strOtherResponse = $strOtherResponse . '&wins=' . $str_wins . '&wins_mask=' . $str_wins_mask;
                    if($bgt == 30){
                        $strOtherResponse = $strOtherResponse . '&status=0,0';
                    }else{
                        $strOtherResponse = $strOtherResponse . '&status=0,0,0';
                    }
                }
                
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($currentReelSet >= 0){
                    $strOtherResponse = $strOtherResponse . '&reel_set=' . $currentReelSet;
                }
                if($rs_p >= 0){
                    $strOtherResponse = $strOtherResponse . '&rs='. $str_rs .'&rs_p=' . $rs_p;
                }
                if($rs_c > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_c=' . $rs_c;
                }
                if($rs_m > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_m=' . $rs_m;
                }
                if($rs_t > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t. '&rs_win=' . $totalWin;
                }
                if($rs_more > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_more=' . $rs_more;
                }
                if($str_rs_f != ''){
                    $strOtherResponse = $strOtherResponse . '&rs_f=' . $str_rs_f;
                }
                if($bgt > 0){
                    $strOtherResponse = $strOtherResponse . '&bgt=' . $bgt;
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', $bgt);
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
                if($str_srf != ''){
                    $strOtherResponse = $strOtherResponse . '&srf=' . $str_srf;
                }
                
                if($str_win_p != ''){
                    $strOtherResponse = $strOtherResponse . '&win_p=' . $str_win_p;
                }
                if($str_cwin_p != ''){
                    $strOtherResponse = $strOtherResponse . '&cwin_p=' . $str_cwin_p;
                }
                if($str_fstype != ''){
                    $strOtherResponse = $strOtherResponse . '&fstype=' . $str_fstype;
                }
                if($str_rwd != ''){
                    $strOtherResponse = $strOtherResponse . '&rwd=' . $str_rwd;
                }
                if($wnd >= 0){
                    $strOtherResponse = $strOtherResponse . '&wnd=' . $wnd;
                }
                if($gwm > 0){
                    $strOtherResponse = $strOtherResponse . '&wmt=wb&wmv='. $gwm .'&gwm=' . $gwm;
                }
                
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse . $strWinLine .'&balance='.$Balance. '&index='.$slotEvent['index'].'&c='.$betline.'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&l=20&sh=3&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&s='.$strLastReel.'&w='.$totalWin;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) && $spinType != 'b') 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"CurrentRespin":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"Ind":' . $slotSettings->GetGameData($slotSettings->slotId . 'Ind') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'). ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
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
                
                if($slotSettings->GetGameData($slotSettings->slotId . 'Bgt') == 30){
                    $freeStacks = $slotSettings->GetReelStrips('bonus', $betline * $lines, $ind);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $freeStacks);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                }else{
                    $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                }
                $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                $slotSettings->SetGameData($slotSettings->slotId . 'Ind', $ind);
                $lastReel = $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'); 
                $str_reel = '';
                if($stack['reel'] != ''){
                    $lastReel = explode(',', $stack['reel']);
                    $str_reel = $stack['reel'];
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                }
                $currentReelSet = $stack['reel_set'];
                $str_initReel = $stack['init_reel'];
                $rs_p = $stack['rs_p'];
                $rs_c = $stack['rs_c'];
                $rs_m = $stack['rs_m'];
                $rs_t = $stack['rs_t'];
                $str_rs_f = $stack['rs_f'];
                $str_rs = $stack['rs'];
                $rs_more = $stack['rs_more'];
                $bgt = $stack['bgt'];
                $level = $stack['level'];
                $lifes = $stack['lifes'];
                $bw = $stack['bw'];
                $end = $stack['end'];
                $bgid = $stack['bgid'];
                $str_wins_mask = $stack['wins_mask'];
                $str_wins = $stack['wins'];
                $str_status = '';
                $str_srf = $stack['srf'];
                $str_cwin_p = $stack['cwin_p'];
                $str_win_p = $stack['win_p'];
                $str_fstype = $stack['fstype'];
                $str_rwd = $stack['rwd'];
                $gwm = $stack['gwm'];
                $wnd = $stack['wnd'];
                $totalWin = 0;
                $spinType = 's';
                $coef = $betline * $lines;
                $rw = 0;
                $isState = false;
                $strOtherResponse = '';
                if($bgt == 41){
                    $arr_wins = [0,0,0];
                    $arr_wins_mask = ['h', 'h', 'h'];
                    $arr_status = [0,0,0];
    
                    $old_wins_mask = explode(',', $str_wins_mask);
                    if($ind >= 0){
                        $arr_wins[$ind] = 1;
                        $arr_status[$ind] = 1;
                        $arr_wins_mask[$ind] = $old_wins_mask[0];
                    }
                    $str_wins = implode(',', $arr_wins);
                    $str_wins_mask = implode(',', $arr_wins_mask);
                    $str_status = implode(',', $arr_status);
                    $strOtherResponse = '&wins='. $str_wins .'&status='. $str_status .'&rw=0.00&wp=0&wins_mask='. $str_wins_mask;
                }else if($bgt == 30){
                    $arr_status = [0,0];
                    $arr_status[$ind] = 1;
                    $str_status = implode(',', $arr_status);
                    $freeNums = [8, 5];
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freeNums[$ind]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                    $strOtherResponse = '&fsmul=1&wins='. $str_wins .'&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&status='. $str_status .'&rw=0.00&wins_mask='. $str_wins_mask .'&fswin=0.00&fs=1&wp=0&fsres=0.00';
                }else if($bgt == 40){
                    $spinType = 'b';
                    if($end == 1){
                        $strWinLine = '';
                        $lineWins = [];
                        $lineWinNum = [];
                        $reels = [];
                        for($i = 0; $i < 5; $i++){
                            $reels[$i] = [];
                            for($j = 0; $j < 3; $j++){
                                $reels[$i][$j] = $lastReel[$j * 5 + $i];
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
                            $wildWin = 0;
                            $wildWinNum = 1;
                            $mul = 0;
                            for($j = 1; $j < 5; $j++){
                                $ele = $reels[$j][$linesId[$k][$j] - 1]; 
                                if($firstEle == $wild || ($firstEle >= 13 && $firstEle < 16)){
                                    $firstEle = $ele;
                                    $lineWinNum[$k] = $lineWinNum[$k] + 1;
                                    if($j == 4){
                                        $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                        if($gwm > 0){
                                            $lineWins[$k] = $lineWins[$k] * $gwm;
                                        }
                                        $totalWin += $lineWins[$k];
                                        $_obf_winCount++;
                                        $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                        for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                            $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                        }
                                    }else if($j >= 2 && ($ele == $wild || ($ele >= 13 && $ele < 16))){
                                        $wildWin = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                        if($gwm > 0){
                                            $wildWin = $wildWin * $gwm;
                                        }
                                        $wildWinNum = $lineWinNum[$k];
                                    }
                                }else if($ele == $firstEle || $ele == $wild || ($ele >= 13 && $ele < 16)){
                                    $lineWinNum[$k] = $lineWinNum[$k] + 1;
                                    if($j == 4){
                                        $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                        if($gwm > 0){
                                            $lineWins[$k] = $lineWins[$k] * $gwm;
                                        }
                                        if($lineWins[$k] < $wildWin){
                                            $lineWins[$k] = $wildWin;
                                            $lineWinNum[$k] = $wildWinNum;
                                        }
                                        $totalWin += $lineWins[$k];
                                        $_obf_winCount++;
                                        $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                        for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                            $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                        }
                                    }
                                }else{
                                    if($slotSettings->Paytable[$firstEle][$lineWinNum[$k]] > 0){
                                        $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                        if($gwm > 0){
                                            $lineWins[$k] = $lineWins[$k] * $gwm;
                                        }
                                        if($lineWins[$k] < $wildWin){
                                            $lineWins[$k] = $wildWin;
                                            $lineWinNum[$k] = $wildWinNum;
                                        }
                                        $totalWin += $lineWins[$k];
                                        $_obf_winCount++;
                                        $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                        for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                            $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                        }   

                                    }else{
                                        $lineWinNum[$k] = 0;
                                    }
                                    break;
                                }
                            }
                        }
                        $strOtherResponse = $strWinLine . '&rw='.$totalWin;
                        if($totalWin > 0){
                            $spinType = 'cb';
                            $isState = true;
                            $slotSettings->SetBalance($totalWin);
                            $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                            $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                            $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                            
                            if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                                if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') < $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1){
                                    $spinType = 's';
                                    $isState = false;
                                }else if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame')) 
                                {
                                    // $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                                    $spinType = 'c';
                                    // $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                                }
                            }
                        }
                    }
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', $bgt);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $rs_p);
                if($str_reel != ''){
                    $strOtherResponse = $strOtherResponse . '&s=' . $str_reel;
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($currentReelSet >= 0){
                    $strOtherResponse = $strOtherResponse . '&reel_set=' . $currentReelSet;
                }
                if($rs_p >= 0){
                    $strOtherResponse = $strOtherResponse . '&rs='. $str_rs .'&rs_p=' . $rs_p;
                }
                if($rs_c > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_c=' . $rs_c;
                }
                if($rs_m > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_m=' . $rs_m;
                }
                if($rs_t > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t;
                }
                if($rs_more > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_more=' . $rs_more;
                }
                if($str_rs_f != ''){
                    $strOtherResponse = $strOtherResponse . '&rs_f=' . $str_rs_f;
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
                if($str_srf != ''){
                    $strOtherResponse = $strOtherResponse . '&srf=' . $str_srf;
                }
                if($str_win_p != ''){
                    $strOtherResponse = $strOtherResponse . '&win_p=' . $str_win_p;
                }
                if($str_cwin_p != ''){
                    $strOtherResponse = $strOtherResponse . '&cwin_p=' . $str_cwin_p;
                }
                if($str_fstype != ''){
                    $strOtherResponse = $strOtherResponse . '&fstype=' . $str_fstype;
                }
                if($str_rwd != ''){
                    $strOtherResponse = $strOtherResponse . '&rwd=' . $str_rwd;
                }
                if($wnd >= 0){
                    $strOtherResponse = $strOtherResponse . '&wnd=' . $wnd;
                }
                if($gwm > 0){
                    $strOtherResponse = $strOtherResponse . '&wmt=wb&wmv='. $gwm .'&gwm=' . $gwm;
                }
                if($bgt != 40){
                    $strOtherResponse = $strOtherResponse .'&coef='. $coef;
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"CurrentRespin":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"Ind":' . $slotSettings->GetGameData($slotSettings->slotId . 'Ind') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'). ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
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
    }
}

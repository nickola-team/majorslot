<?php 
namespace VanguardLTE\Games\HerculesandPegasusPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'Ind', -1);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [9,6,3,4,5,4,8,6,9,8,5,3,8,8,7]);
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
                    $str_rwd = $stack['rwd'];
                    $msr = $stack['msr'];
                    $bw = $stack['bw'];
                    $bgt = $stack['bgt'];
                    $end = $stack['end'];
                    $str_wins_mask = $stack['wins_mask'];
                    $str_wins = $stack['wins'];
                    $str_prg_m = $stack['prg_m'];
                    $str_prg = $stack['prg'];
                    $str_srf = $stack['srf'];
                    $str_apt = $stack['apt'];
                    $str_apv = $stack['apv'];
                    $apwa = $stack['apwa'];
                    $gwm = $stack['gwm'];
                    if($bw == 1){
                        $spinType = 'b';
                        $strOtherResponse = $strOtherResponse . '&bgid=0&wins=0,0,0&coef='. ($bet * 25) .'&level=0&status=0,0,0&rw=0.00&lifes=1&bw=1&wins_mask=h,h,h&wp=0&end=0';
                    }
                    
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($str_rwd != ''){
                        $strOtherResponse = $strOtherResponse . '&rwd=' . $str_rwd;
                    }
                    if($str_prg != ''){
                        $strOtherResponse = $strOtherResponse . '&prg_m=' . $str_prg_m . '&prg=' . $str_prg;
                    }
                    if($str_srf != ''){
                        $strOtherResponse = $strOtherResponse . '&srf=' . $str_srf;
                    }
                    if($msr >= 0){
                        $strOtherResponse = $strOtherResponse . '&msr=' . $msr;
                    }
                    if($str_apv != ''){
                        $strOtherResponse = $strOtherResponse . '&apwa=' . $apwa . '&apt='. $str_apt .'&apv=' . $str_apv;
                    }
                    if($gwm > 0){
                        $strOtherResponse = $strOtherResponse . '&gwm=' . $gwm;
                    }
                    if($bgt > 0){
                        $strOtherResponse = $strOtherResponse . '&bgt=' . $bgt;
                        if($end == 1){
                            $arr_wins = [0,0,0];
                            $arr_status = [0,0,0];
                            $arr_wins_mask = ['h','h','h'];
                            $old_wins_mask = explode(',', $str_wins_mask);
                            $ind = $slotSettings->GetGameData($slotSettings->slotId . 'Ind');
                            if($ind >= 0){
                                $arr_wins[$ind] = 1;
                                $arr_status[$ind] = 1;
                                $arr_wins_mask[$ind] = $old_wins_mask[0];
                            }
                            $strOtherResponse = $strOtherResponse . '&bgid=0&wins='. implode(',', $arr_wins) .'&coef='. ($bet * 25) .'&level=1&status='. implode(',', $arr_status) .'&rw=0.00&lifes=0&wins_mask='. implode(',', $arr_wins_mask) .'&wp=0&end=1';
                        }
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
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }else{
                    $strOtherResponse = $strOtherResponse . '&msr=3';
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));

                $Balance = $slotSettings->GetBalance();
                $response = 'msi=12&def_s=9,6,3,4,5,4,8,6,9,8,5,3,8,8,7&balance='. $Balance .'&cfgs=2593&ver=2&index=1&balance_cash='. $Balance .'&reel_set_size=8&def_sb=2,7,5,3,5&def_sa=6,2,7,3,8&reel_set='.$currentReelSet.$strOtherResponse.'&prg_cfg_m=wm,s,s,wms,s&balance_bonus=0.00&na='. $spinType .'&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&cls_s=-1&gmb=0,0,0&rt=d&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=6,2,7,3,8&sb=2,7,5,3,5&prg_cfg=1,16,15,16,15&sc='. implode(',', $slotSettings->Bet) .'&defc=50.00&sh=3&wilds=2~500,200,40,2,0~1,1,1,1,1;17~500,200,40,2,0~1,1,1,1,1;18~500,200,40,2,0~1,1,1,1,1&bonuses=0;13;14&fsbonus=&c='.$bet.'&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;400,200,40,2,0;300,140,40,0,0;200,100,20,0,0;200,100,20,0,0;100,20,8,0,0;100,20,8,0,0;50,10,4,0,0;50,10,4,0,0;50,10,4,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0&l=20&reel_set0=2,9,10,11,3,3,3,9,10,8,6,10,4,9,8,5,9,0,7,8~5,9,8,4,7,4,9,11,4,7,5,2,10,9,5,11,6,9,10,11,3,3,3,10,6,9,8~11,10,5,7,6,11,4,7,11,3,3,3,9,11,0,9,10,4,8,5,10,2,7,0~11,10,7,6,10,11,3,3,3,8,5,9,7,2,4,9,8,5,9~6,10,9,3,3,3,9,10,2,8,10,5,8,6,11,9,6,11,14,9,13,4,11,6,7,8,11,4,9,8,5,10,7,14,4,11,10,2,8&s='.$lastReelStr.'&reel_set2=4,9,10,11,3,3,3,5,7,11,9,5,10,8,11,9,7,10,8,11,10,6,11,7~5,9,8,4,7,11,4,8,10,6,7,11,8,7,9,6,11,3,3,3~7,11,8,6,11,10,5,7,6,11,5,3,3,3,7,4,8,5,10,6,9,8~11,10,7,6,10,6,7,4,11,6,10,11,5,8,3,3,3,3,11,9,5,8,10,4,8,10,9,7,4,11,5,9,8~6,10,9,3,3,3,3,9,10,11,8,10,5,8,6,7,5,10,6,7,4,8&reel_set1=8,12,9,12,12,12,9,12,8,12,12,11,12,8,2,4,5,12,12,12,12,12,7,12,12,12,6,2,8,12,12,10,6,3~12,12,9,12,10,4,12,9,12,12,12,3,12,2,5,10,12,12,12,12,10,12,11,7,12,8,12,10,6,12~12,8,12,12,10,12,8,12,12,7,12,12,12,12,12,12,12,12,6,12,7,2,9,6,2,9,12,12,3,4,4,5,11~5,10,4,7,6,10,5,12,12,9,12,6,12,12,10,12,11,12,5,12,4,8,3,11,2,9,3,8,12,10,12,7,12~6,2,11,12,7,12,12,12,5,12,8,12,9,12,8,12,12,12,12,10,12,7,12,10,12,9,12,9,11,12,4,3&reel_set4=9,10,11,3,3,3,9,10,8,6,10,4,9,8,5,9,10,2,2,2,7~5,9,8,4,7,11,4,8,10,2,7,11,8,2,3,3,3,10,6,9,8,7,9,6,11~7,11,8,7,2,10,7,2,8,9,2,7,4,8,5,10,6,9,8,3,3,3~11,10,7,6,10,11,5,5,8,3,3,3,11,9,5,8,10,2,8,10,9,7,4,11,5,9,8,6,9~6,10,9,7,6,9,11,3,10,6,7,0,10,5,8,3,11,8,5,10,2,0,7,8,9,5,10,4,11,10,8,5,10,3,7,9&reel_set3=9,2,7,10,11,3,3,3,9,10,8,6,10,4,9,8,5,9,10~5,9,8,4,7,11,4,8,10,2,7,11,8,2,3,3,3,10,6,9,8,7,9,6,11~7,11,8,7,2,10,7,2,8,9,2,7,4,8,5,10,6,9,8,3,3,3~11,10,7,6,10,11,5,5,8,3,3,3,11,9,5,8,10,2,8,10,9,7,4,11,5,9,8,6,9~6,10,9,7,6,9,11,3,10,6,7,0,10,5,8,3,11,8,5,10,0,7,8,9,2,5,10,4,11,10,8,5,10,3,7,9&reel_set6=9,10,11,3,3,3,9,10,8,6,10,4,9,8,5,9,10,2,2,2,7~5,9,8,4,7,11,4,8,10,2,7,11,8,2,3,3,3,10,6,9,8,7,9,6,11~7,11,8,7,2,10,7,2,8,9,2,2,2,7,4,8,5,10,6,9,8,3,3,3~11,10,7,6,10,11,5,5,8,3,3,3,11,9,5,8,10,2,8,10,9,7,4,11,5,9,2,2,2,8,6,9~6,10,9,7,6,9,11,3,10,6,7,0,10,5,8,3,11,8,5,10,0,7,8,9,2,5,10,4,11,10,8,5,10,3,7,2,2,2,9&reel_set5=7,9,10,11,3,3,3,9,2,2,2,10,8,6,10,4,9,8,5,9,10~5,9,8,4,7,11,4,8,10,2,7,11,8,2,3,3,3,10,6,9,8,7,9,6,11~7,11,8,7,2,10,7,2,8,9,2,7,4,8,5,10,6,9,8,3,3,3~11,10,7,6,10,11,5,5,8,3,3,3,11,9,5,8,10,2,8,10,9,2,2,2,7,4,11,5,9,8,6,9~6,10,9,7,6,9,11,3,10,6,7,0,10,5,8,3,11,8,5,10,0,2,7,8,9,5,10,4,11,10,8,5,10,3,7,9&reel_set7=9,10,11,3,3,3,9,10,8,6,10,4,9,2,2,2,2,8,5,9,10,2,7~5,9,8,4,7,11,4,8,10,2,7,11,8,2,3,3,3,10,6,9,8,7,9,6,11~7,11,8,7,2,10,7,2,2,2,8,9,2,7,3,3,3,4,8,5,10,6,9,8~11,10,7,6,10,11,5,5,8,3,3,3,11,9,5,8,10,2,8,10,9,7,4,11,5,9,8,6,2,2,2,9~6,10,9,7,6,9,11,3,10,6,7,0,10,5,8,3,11,8,5,10,0,7,8,9,2,5,10,4,11,2,2,2,10,8,5,10,3,7,9';
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

                $slotEvent['slotBet'] = $slotEvent['c'];
                $slotEvent['slotLines'] = 20;
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') >= 0) 
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
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') < $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotEvent['slotEvent'] == 'respin' ) 
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
                $allBet = $betline * $lines;
                
                $freeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'respin'){
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.1f', microtime(TRUE));
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
                $str_rwd = '';
                $msr = -1;
                $bw = 0;
                $bgt = 0;
                $str_wins_mask = '';
                $str_wins = '';
                $str_prg_m = '';
                $str_prg = '';
                $str_srf = '';
                $str_apt = '';
                $str_apv = '';
                $apwa = -1;
                $gwm = -1;
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
                    $str_rwd = $stack['rwd'];
                    $msr = $stack['msr'];
                    $bw = $stack['bw'];
                    $bgt = $stack['bgt'];
                    $str_wins_mask = $stack['wins_mask'];
                    $str_wins = $stack['wins'];
                    $str_prg_m = $stack['prg_m'];
                    $str_prg = $stack['prg'];
                    $str_srf = $stack['srf'];
                    $str_apt = $stack['apt'];
                    $str_apv = $stack['apv'];
                    $apwa = $stack['apwa'];
                    $gwm = $stack['gwm'];
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
                    $str_rwd = $stack[0]['rwd'];
                    $msr = $stack[0]['msr'];
                    $bw = $stack[0]['bw'];
                    $bgt = $stack[0]['bgt'];
                    $str_wins_mask = $stack[0]['wins_mask'];
                    $str_wins = $stack[0]['wins'];
                    $str_prg_m = $stack[0]['prg_m'];
                    $str_prg = $stack[0]['prg'];
                    $str_srf = $stack[0]['srf'];
                    $str_apt = $stack[0]['apt'];
                    $str_apv = $stack[0]['apv'];
                    $apwa = $stack[0]['apwa'];
                    $gwm = $stack[0]['gwm'];
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
                        if($firstEle == $wild || $firstEle >= 17){
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
                            }else if($j >= 2 && $ele == $wild){
                                $wildWin = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                if($gwm > 0){
                                    $wildWin = $wildWin * $gwm;
                                }
                                $wildWinNum = $lineWinNum[$k];
                            }
                        }else if($ele == $firstEle || $ele == $wild || $ele >= 17){
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
                    $reelA[$i] = mt_rand(5, 12);
                    $reelB[$i] = mt_rand(5, 12);
                }
                $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
               
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                
                $strOtherResponse = '';
                $isState = true;
                $isEnd = true;
                if( $slotEvent['slotEvent'] == 'respin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $isEnd = false;
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    
                    if( $rs_t > 0 ) 
                    {
                        $spinType = 'c';
                        $isEnd = true;
                    }
                    else
                    {
                        $isState = false;
                        $spinType = 's';
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin); 
                    if($rs_p >= 0 ){
                        $isState = false;
                        $spinType = 's';
                    }
                }
                if($bw == 1){
                    $isState = false;
                    $spinType = 'b';
                    $strOtherResponse = $strOtherResponse . '&bgid=0&wins=0,0,0&coef='. ($betline * $lines) .'&level=0&status=0,0,0&rw=0.00&lifes=1&bw=1&wins_mask=h,h,h&wp=0&end=0';
                }
                
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($str_rwd != ''){
                    $strOtherResponse = $strOtherResponse . '&rwd=' . $str_rwd;
                }
                if($str_prg != ''){
                    $strOtherResponse = $strOtherResponse . '&prg_m=' . $str_prg_m . '&prg=' . $str_prg;
                }
                if($str_srf != ''){
                    $strOtherResponse = $strOtherResponse . '&srf=' . $str_srf;
                }
                if($msr >= 0){
                    $strOtherResponse = $strOtherResponse . '&msr=' . $msr;
                }
                if($str_apv != ''){
                    $strOtherResponse = $strOtherResponse . '&apwa=' . $apwa . '&apt='. $str_apt .'&apv=' . $str_apv;
                }
                if($gwm > 0){
                    $strOtherResponse = $strOtherResponse . '&gwm=' . $gwm;
                }
                if($bgt > 0){
                    $strOtherResponse = $strOtherResponse . '&bgt=' . $bgt;
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
                
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse . $strWinLine .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&l=20&sh=3&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&s='.$strLastReel.'&w='.$totalWin;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)) 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"CurrentRespin":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') . ',"Ind":' . $slotSettings->GetGameData($slotSettings->slotId . 'Ind') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'). ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if(($rs_p == 0 || $bw == 1) && $slotEvent['slotEvent'] != 'respin') 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                }

            }else if( $slotEvent['slotEvent'] == 'doBonus' ){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lines = 20;
                $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                $ind = -1;
                if(isset($slotEvent['ind'])){
                    $ind = $slotEvent['ind'];
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'Ind', $ind);
                               
                $rs_p = $stack['rs_p'];
                $rs_c = $stack['rs_c'];
                $rs_m = $stack['rs_m'];
                $rs_t = $stack['rs_t'];
                $str_rs_f = $stack['rs_f'];
                $str_rs = $stack['rs'];
                $rs_more = $stack['rs_more'];
                $str_rwd = $stack['rwd'];
                $msr = $stack['msr'];
                $bw = $stack['bw'];
                $bgt = $stack['bgt'];
                $end = $stack['end'];
                $str_wins_mask = $stack['wins_mask'];
                $str_wins = $stack['wins'];
                $str_prg_m = $stack['prg_m'];
                $str_prg = $stack['prg'];
                $str_srf = $stack['srf'];
                $str_apt = $stack['apt'];
                $str_apv = $stack['apv'];
                $apwa = $stack['apwa'];
                $gwm = $stack['gwm'];
                $currentReelSet = $stack['reel_set'];

                $arr_wins = [0,0,0];
                $arr_wins_mask = ['h', 'h', 'h'];
                $arr_status = [0,0,0];

                $old_wins_mask = explode(',', $str_wins_mask);
                if($ind >= 0){
                    $arr_wins[$ind] = 1;
                    $arr_status[$ind] = 1;
                    $arr_wins_mask[$ind] = $old_wins_mask[0];
                }
                $totalWin = 0;
                $coef = $betline * $lines;
                $rw = 0;
                $isState = false;
                $spinType = 's';
                $strOtherResponse = '';
                
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $rs_p);
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
                $lastReel = $slotSettings->GetGameData($slotSettings->slotId . 'LastReel');
                
                $response = 'bgid=0&balance='. $Balance .'&wins='. implode(',', $arr_wins) .'&coef='. $coef .'&level=1&index='.$slotEvent['index'].'&balance_cash='. $Balance .'&balance_bonus=0.00&na='. $spinType . $strOtherResponse .'&status='. implode(',', $arr_status) .'&rw=0.00&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&bgt='. $bgt .'&lifes=0&wins_mask='. implode(',', $arr_wins_mask) .'&wp=0&end=1&sver=5&counter='. ((int)$slotEvent['counter'] + 1);

                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"CurrentRespin":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') . ',"Ind":' . $slotSettings->GetGameData($slotSettings->slotId . 'Ind') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'). ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
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

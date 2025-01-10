<?php 
namespace VanguardLTE\Games\VampiresvsWolvesPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 10);
                $slotSettings->SetGameData($slotSettings->slotId . 'Ind', -1);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [8,10,5,8,5,8,10,4,7,1,3,7,4,7,1]);
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
                    $bet = '100.00';
                }
                $currentReelSet = 0;
                $spinType = 's';
                if(isset($stack)){    
                    if($stack['reel_set'] >= 0){
                        $currentReelSet = $stack['reel_set'];
                    }
                    $str_initReel = $stack['init_reel'];
                    $str_sa = $stack['sa'];
                    $str_sb = $stack['sb'];
                    $bgt = $stack['bgt'];
                    $level = $stack['level'];
                    $lifes = $stack['lifes'];
                    $bw = $stack['bw'];
                    $end = $stack['end'];
                    $bgid = $stack['bgid'];
                    $str_wins_mask = $stack['wins_mask'];
                    $str_wins = $stack['wins'];
                    $str_status = $stack['wins_status'];
                    $str_srf = $stack['srf'];
                    $str_sty = $stack['sty'];
                    $str_fstype = $stack['fstype'];

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
                    if($bgt > 0){
                        $strOtherResponse = $strOtherResponse . '&bgt=' . $bgt;                        
                        // $ind = $slotSettings->GetGameData($slotSettings->slotId . 'Ind');
                        $strOtherResponse = '&wins='. $str_wins .'&status='. $str_status .'&rw=0.00&wins_mask='. $str_wins_mask .'&wp=0&fsres=0.00';
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
                            $spinType = 'b';
                        }
                    }
                    if($bgid > -1){
                        $strOtherResponse = $strOtherResponse . '&bgid=' . $bgid;
                    }
                    if($str_srf != ''){
                        $strOtherResponse = $strOtherResponse . '&srf=' . $str_srf;
                    }
                    if($str_sty != ''){
                        $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                    }
                    if($str_fstype != ''){
                        $strOtherResponse = $strOtherResponse . '&fstype=' . $str_fstype;
                    }                    
                    if($str_sa != ''){
                        $strOtherResponse = $strOtherResponse . '&sa=' . $str_sa;
                    }
                    if($str_sb != ''){
                        $strOtherResponse = $strOtherResponse . '&sb=' . $str_sb;
                    }
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }else{
                    $strOtherResponse = $strOtherResponse . '&sa=1,10,8,3,5&sb=4,3,5,7,1';
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
                $response = 'def_s=8,10,5,8,5,8,10,4,7,1,3,7,4,7,1&balance='. $Balance .'&cfgs=2449&ver=2&index=1&balance_cash='. $Balance .'&reel_set_size=3&def_sb=4,3,5,7,1&def_sa=1,10,8,3,5&reel_set='.$currentReelSet.$strOtherResponse.'&balance_bonus=0.00&na='. $spinType .'&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sc='. implode(',', $slotSettings->Bet) .'&defc=100.00&sh=3&wilds=2~1000,150,25,0,0~1,1,1,1,1;19~1000,150,25,0,0~1,1,1,1,1;20~1000,150,25,0,0~1,1,1,1,1;21~1000,150,25,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;800,125,25,0,0;800,125,25,0,0;300,75,15,0,0;300,75,15,0,0;180,50,10,0,0;180,50,10,0,0;100,30,8,0,0;60,20,6,0,0;50,15,5,0,0;30,12,5,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;1000,150,25,0,0;1000,150,25,0,0;1000,150,25,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0&l=10&reel_set0=7,9,12,7,5,9,1,1,1,7,2,5,11,7,10,11,5,4,11,6,4,11,8,6,4,1,1,1,10,3,5,4,9,3,4,7,11,12,8,4,6,9,10,11,2,6,11,7,6,10,3,8,11,2,7,11,3,5,12,2,6,12,10,11,1,1,1,12,4,11,12,9,5,12,9,3,8,12,9,10,5,12,10,3,9,6,12,8,10,3,8,6,9,12,6,10,2,7,12,10,7,9,10,2,7,8,11,10,8,12,5,2,12,5,8,4,11,8,3~12,11,10,2,10,8,9,11,4,5,11,12,8,3,6,9,3,8,12,6,2,12,5,8,4,10,7,9,11,5,12,10,4,11,12,5,6,12,3,7,12,2,9,11,7,9,3,6,7,12,10,11,12,6,3,12,2,9,3,8,2,9,8,12,9,8,11,4,7,9,5,12,7,6,10,11,5,10,3,4,7,8,6,7,5,10,4,5~8,9,12,10,9,5,12,8,10,12,7,3,12,2,4,12,8,11,12,6,7,11,12,8,10,4,12,10,9,2,10,7,4,3,6,10,9,6,3,11,10,2,10,8,4,9,7,8,12,2,7,12,10,7,5,10,3,8,9,11,10,3,8,11,2,7,11,3,9,6,5,4,9,6,12,4,11,8,5,2,9,5,7,9,5,11,6,5,11,12,7~11,8,12,4,5,12,4,8,10,6,2,10,12,6,7,2,3,10,9,12,6,2,12,6,7,11,6,10,8,11,4,8,7,4,12,9,10,8,6,11,7,6,11,9,4,10,9,12,4,3,5,7,2,5,10,3,7,11,6,3,9,5,2,9,5,8,7,4,10,3,9,11,10,12,8,4,9,12,11,9,12,6,8,2,11,12,5~8,5,10,12,5,8,9,10,7,5,2,7,5,3,10,11,1,1,1,12,9,2,8,12,7,10,6,2,9,6,7,8,11,4,9,12,11,8,12,9,3,12,6,1,1,1,11,10,3,11,4,10,12,4,10,11,7,10,5,8,10,3,12,7,6,9,7,3,2,4,6,12,7,8,12,7,1,1,1,9,6,7,11,8,9,5,2,9,8,3,4,11,3,5,12,11,6,10,11,6&s='.$lastReelStr.'&reel_set2=8,7,11,7,9,12,5,9,11,11,7,10,5,11,7,5,12,7,4,8,5,10,3,6,10,4,3,8,11,11,8,6,9,10,6,2,11,6,12,6,7,10,3,8,11,11,2,7,4,5,12,12,6,5,12,10,11,11,4,9,9,12,3,9,12,9,10,12,5,12,10,9,6,8,9,10,3,8,6~12,11,10,3,6,4,8,12,10,11,8,10,11,4,5,12,4,3,7,10,4,8,12,6,12,2,6,8,12,5,3,10,11,12,4,7,10,11,5,12,10,6,11,5,6,12,3,12,7,2,9,9,11,7,4,6,8,12,12,10,11,7,3,6,11,11,9,12,5,10,7,9,8~8,10,12,11,10,5,12,9,11,12,7,3,4,12,5,8,12,11,6,12,5,11,7,5,12,7,8,9,7,8,11,12,9,11,5,12,11,9,8,5,4,7,11,10,7,3,12,11,10,12,11,10,4,7,2,6,11,9,10,8,9,12,8,11,7,10,6,3,10,3,4,9,9~12,9,8,11,9,11,6,9,11,10,12,6,9,7,11,12,7,8,11,10,12,7,8,9,7,12,10,6,9,8,10,9,7,10,3,12,4,2,11,12,5,11,9,7,12,11,8,7,10,11,10,11,7,8,11,5,8,12,7,3,10,5,10,9,8,11,10,12,11,11~6,9,11,6,10,3,11,9,6,4,7,5,11,4,12,6,9,3,5,9,11,9,6,7,10,9,12,10,12,11,9,3,10,5,12,6,5,8,12,5,10,12,6,11,12,5,2,9,11,8,11,9,5,11,4,6,12,11,9,7,11,9,12,8,10,5,7,4,2,12,12,11,10,12,10,12,10,10,3,9,6,5,12,12,3,8,11,12,6,12,4,12,10&reel_set1=11,6,4,11,8,18,6,4,10,16,17,4,9,16,4,18,11,12,8,6,9,16,10,11,2,6,11,18,6,10,16,8,11,2,18,11,16,17,12,2,6,12,10,11,12,4,12,10,16,9,6,12,8,10,16,8,6,9,12,6,10,2,18,12,10,18,9,10,2,9,18,8,12,17,2,12,17,8,4,11,8,16~12,11,10,4,6,18,10,11,2,10,8,9,11,4,17,11,12,8,16,6,9,18,16,8,12,6,2,12,17,8,11,17,16,10,2,4,10,16,18,9,11,17,12,10,11,12,17,6,12,16,18,12,2,9,11,18,9,16,6,18,12,10,17,11,12,6,16,12,2,6,10,11,9,12,4,10,18,8,2~8,9,12,4,10,9,17,12,8,10,12,18,16,12,2,4,12,8,11,12,17,18,11,4,6,11,4,6,11,18,8,6,18,11,12,8,10,16,4,12,10,9,2,10,18,4,6,10,9,6,16,11,10,2,11,9,16,6,2,17,10,8,17,16,9,18,8,12,2,18,12,10,18,17,10,16,8,9,11,10,8~11,8,12,4,18,10,17,18,8,10,16,9,12,4,17,12,4,8,10,6,2,18,10,12,6,18,2,16,10,9,12,6,2,12,6,18,11,16,6,10,8,11,4,8,18,4,12,9,8,17,9,11,10,2,11,16,10,8,6,11,18,6,17,11,9,4,10,9,12,4,16,17,18,2,17,10,16,18,11,6,9,17,2,9,17,8,18,4,10,16,9,11,10,12~17,11,9,4,17,2,8,17,10,12,17,8,9,10,18,17,2,18,17,16,10,18,11,12,9,2,8,12,18,10,6,2,9,6,18,8,11,16,9,12,11,6,16,11,10,16,11,4,10,8,11,4,12,8,10,12,18,8,12,18,9,6,18,11,8,9,17,2,9,8,16,4,11,16,17,12,11,6,10,11,6,10,2,12,4,11,12,6,9';
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
                $lines = 10;      
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
                $slotEvent['slotLines'] = 10;
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'Ind', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusCheck', 0);
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
                $lineWins = [];
                $lineWinNum = [];
                $winLineCount = 0;
                $str_initReel = '';
                $str_sa = '';
                $str_sb = '';
                $strWinLine = '';
                $bgt = 0;
                $level = -1;
                $lifes = -1;
                $bw = 0;
                $end = -1;
                $bgid = -1;
                $str_wins_mask = '';
                $str_wins = '';
                $str_status = '';
                $str_srf = '';
                $str_sty = '';
                $str_fstype = '';
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $str_initReel = $stack['init_reel'];
                    $str_sa = $stack['sa'];
                    $str_sb = $stack['sb'];
                    $bgt = $stack['bgt'];
                    $level = $stack['level'];
                    $lifes = $stack['lifes'];
                    $bw = $stack['bw'];
                    $end = $stack['end'];
                    $bgid = $stack['bgid'];
                    $str_wins_mask = $stack['wins_mask'];
                    $str_wins = $stack['wins'];
                    $str_status = $stack['wins_status'];
                    $str_srf = $stack['srf'];
                    $str_sty = $stack['sty'];
                    $str_fstype = $stack['fstype'];
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
                    $str_sa = $stack[0]['sa'];
                    $str_sb = $stack[0]['sb'];
                    $bgt = $stack[0]['bgt'];
                    $level = $stack[0]['level'];
                    $lifes = $stack[0]['lifes'];
                    $bw = $stack[0]['bw'];
                    $end = $stack[0]['end'];
                    $bgid = $stack[0]['bgid'];
                    $str_wins_mask = $stack[0]['wins_mask'];
                    $str_wins = $stack[0]['wins'];
                    $str_status = $stack[0]['wins_status'];
                    $str_srf = $stack[0]['srf'];
                    $str_sty = $stack[0]['sty'];
                    $str_fstype = $stack[0]['fstype'];
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
                        if($firstEle == $wild || $firstEle >= 19){
                            $firstEle = $ele;
                            $lineWinNum[$k] = $lineWinNum[$k] + 1;
                            if($j == 4){
                                $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                $totalWin += $lineWins[$k];
                                $_obf_winCount++;
                                $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                    $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                }
                            }else if($j >= 2 && ($ele == $wild || $ele >= 19)){
                                $wildWin = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                $wildWinNum = $lineWinNum[$k];
                            }
                        }else if($ele == $firstEle || $ele == $wild || $ele >= 19){
                            $lineWinNum[$k] = $lineWinNum[$k] + 1;
                            if($j == 4){
                                $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
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
                }
                if($bw == 1 && $end == 0){
                    $isState = false;
                    $spinType = 'b';
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusCheck', 1);
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
                    $strOtherResponse = $strOtherResponse . '&reel_set=' . $currentReelSet;
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
                if($str_sty != ''){
                    $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                }
                if($str_fstype != ''){
                    $strOtherResponse = $strOtherResponse . '&fstype=' . $str_fstype;
                }
                if($str_sa != ''){
                    $strOtherResponse = $strOtherResponse . '&sa=' . $str_sa;
                }
                if($str_sb != ''){
                    $strOtherResponse = $strOtherResponse . '&sb=' . $str_sb;
                }
                
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse . $strWinLine .'&balance='.$Balance. '&index='.$slotEvent['index'].'&c='.$betline.'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&l=10&sh=3&c='. $betline .'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&s='.$strLastReel.'&w='.$totalWin;
                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Ind":' . $slotSettings->GetGameData($slotSettings->slotId . 'Ind') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'). ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
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
                $lines = 10;
                $scatter = 1;
                $wild = 2;
                $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                if($slotSettings->GetGameData($slotSettings->slotId . 'BonusCheck') == 0){
                    $response = 'unlogged';
                    exit( $response );
                }
                $ind = -1;
                if(isset($slotEvent['ind'])){
                    $ind = $slotEvent['ind'];
                }
                
                $freeStacks = $slotSettings->GetReelStrips('bonus', $betline * $lines, $ind);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $freeStacks);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);

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
                $bgt = $stack['bgt'];
                $level = $stack['level'];
                $lifes = $stack['lifes'];
                $bw = $stack['bw'];
                $end = $stack['end'];
                $bgid = $stack['bgid'];
                $str_wins_mask = $stack['wins_mask'];
                $str_wins = $stack['wins'];
                $str_status = $stack['wins_status'];
                $str_srf = $stack['srf'];
                $str_sty = $stack['sty'];
                $str_fstype = $stack['fstype'];
                $totalWin = 0;
                $spinType = 's';
                $coef = $betline * $lines;
                $rw = 0;
                $isState = false;
                $strOtherResponse = '';
                $freeNums = [14, 8];
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freeNums[$ind]);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                $strOtherResponse = '&fsmul=1&wins='. $str_wins .'&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&status='. $str_status .'&rw=0.00&wins_mask='. $str_wins_mask .'&fswin=0.00&fs=1&wp=0&fsres=0.00';
                if($str_reel != ''){
                    $strOtherResponse = $strOtherResponse . '&s=' . $str_reel;
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($currentReelSet >= 0){
                    $strOtherResponse = $strOtherResponse . '&reel_set=' . $currentReelSet;
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
                if($str_sty != ''){
                    $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                }
                if($str_fstype != ''){
                    $strOtherResponse = $strOtherResponse . '&fstype=' . $str_fstype;
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusCheck', 0);
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Ind":' . $slotSettings->GetGameData($slotSettings->slotId . 'Ind') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'). ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
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


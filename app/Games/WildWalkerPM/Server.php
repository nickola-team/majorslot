<?php 
namespace VanguardLTE\Games\WildWalkerPM
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
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [2,3,11,6,6,13,13,13,2,10,5,3,10,13,13,13,2,10,11,4,10,13,13,13]);
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
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if(isset($stack)){
                    $str_initReel = $stack['initReel'];                    
                    $str_rwd = $stack['rwd'];
                    $str_accv = $stack['accv'];
                    $str_accm = $stack['accm'];
                    $currentReelSet = $stack['reel_set'];
                    $fsmore = $stack['fsmore'];
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($str_rwd != ''){
                        $strOtherResponse = $strOtherResponse . '&rwd=' . $str_rwd;
                    }
                    if($str_accv != ''){
                        $strOtherResponse = $strOtherResponse . '&accm=' . $str_accm . '&acci=0&accv=' . $str_accv;
                    }
                    $strOtherResponse = $strOtherResponse . '&action=doSpin&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }

                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 || $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0)
                {
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0) 
                    {
                        $strOtherResponse = $strOtherResponse . '&fs_total='.($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 1).'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsend_total=1&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&w=0';
                    }
                    else
                    {
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&w=0.00';
                    }
                        
                    if($fsmore > 0){
                        $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                    }     
                }
                
                $Balance = $slotSettings->GetBalance();  
                $response = 'def_s=2,3,11,6,6,13,13,13,2,10,5,3,10,13,13,13,2,10,11,4,10,13,13,13&balance='. $Balance .'&cfgs=3477&ver=2&index=1&balance_cash='. $Balance .'&def_sb=3,4,7,4,6,13,13,13&reel_set_size=5&def_sa=8,7,5,3,3,13,13,13&reel_set='. $currentReelSet .'&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0,0,0,0~0,0,0,0,0,0,0,0~1,1,1,1,1,1,1,1&gmb=0,0,0&rt=d&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime='. floor(microtime(true) * 1000) .'&sa=8,7,5,3,3,13,13,13&sb=3,4,7,4,6,13,13,13&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=100.00&sh=3&wilds=2~0,0,0,0,150,30,0,0~1,1,1,1,1,1,1,1&bonuses=0&fsbonus=&c='. $bet .'&sver=5&counter=2&paytable=0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0;10000,4500,1500,500,150,30,0,0;7000,3000,1200,400,100,25,0,0;5000,2000,900,300,75,20,0,0;3000,1500,500,200,50,15,0,0;2000,900,300,120,30,10,0,0;1500,500,200,75,20,8,0,0;500,200,90,30,10,4,0,0;500,200,90,30,10,4,0,0;500,200,90,30,10,4,0,0;500,200,90,30,10,4,0,0;0,0,0,0,0,0,0,0&l=25&reel_set0=10,10,10,4,7,9,8,3,12,12,12,5,11,10,12,6,1,11,11,11,5~1,11,11,11,9,8,5,10,11,7,10,10,10,3,6,12,12,12,12,4,11,12,8,7,12,11,6,10,6,7,10,11,10,7,11,7,11,10,7,12,11,6,12,4,12,7,11,7,10,11,6,12~4,10,10,10,7,12,11,5,9,12,12,12,10,11,11,11,3,8,1,6,8,11,10,8,12,11,12,11,10,6,11,12,11,12,6,7,11,8,11,5,11,8,10,9,11,12,11,10,12,11,3,7,3,8,11,12,11,10,9,11,12,10,12,11,9,7,12,8,10,8,10,12,5,3,9,1,8,10,7,9,12,7,12,10,11,9,12,10,9,11,7,12~4,4,4,4,8,12,9,11,5,6,1,3,7,11,11,11,10,5,5,5,6,6,6,12,12,12,11,5,12,6,11,12,7,5,6,5,12,5,12,11,6,12,11,5,3,12,3,7,3,12,6,7,3,6,12,5,11,12,11,3,5,11,3,12,6,7,12,3,5,12,5,11,5,11,12,11,8,11,8,3,11,5,12,11,5,11~10,6,7,7,7,1,3,4,4,4,8,12,12,12,5,9,4,11,11,11,12,7,11,12,9,12,9,7,12,9,7,12,9,5,12,4,12,7,9,7,9,4,9,11,9,12,4,11,5~13,13,13,13,13~13,13,13,13,13~13,13,13,13,13&s='.$lastReelStr.'&accInit=[{id:0,mask:"cp;tp;lvl;sc;cl"}]&reel_set2=2,2,2,2,2~9,3,10,10,10,1,7,11,11,11,11,12,12,12,12,6,5,4,10,8,10,12,4,10,11,7,6,8,12,10,1,12,11,10,7,12,7,10,11,12,11,12,10,8,12,3,11,10,3,11,10,12,7,8,12,7,12,10,8,11,12,11,12,3,12,7,10,11,10,8,7,12,4,12,10,11,12,3,12,10,11,10,12,8,7,8,7,10,8,12,1,8,10~11,11,11,11,7,8,10,10,10,3,12,12,12,9,1,10,4,6,5,12,4,12~3,1,8,11,11,11,11,10,10,10,10,7,6,12,12,12,9,5,4,12,10,4,12,11,6,9,12,10,11,9,6,12,9,6,12,8,11,10,11,10,11,6,11,10,6,9,12,11,10,9,10,11,10,1,9,12,5,11,12,11,12,10,9,10,9,10,6,11,12,8,6,8,11,10,12,11,4,9,11,8,1,4,6,11,6,10,11,9,11,10,11,12,10,6,11,12,8,10,11,12~6,7,12,12,12,5,10,10,10,1,11,11,11,4,8,11,12,10,9,3,12~5,7,1,4,11,11,11,9,11,12,12,12,10,10,10,10,3,6,12,8,9,10,11,9,12,11,12,10,8,10,4,12,7,8,10,12,4,11,9,10,7~13,13,13,13,13~13,13,13,13,13&reel_set1=11,1,8,10,10,10,6,11,11,11,4,12,12,12,12,5,7,3,10,9,12,1,10,3,5,12,5,10,5,8,4,12,10,8,4,10,5,12,5,10,5~10,10,10,4,12,12,12,3,6,12,9,8,11,1,7,10,5,11,11,11,7,8,7,12,6,5,11,12,1,5,8,6,11,1,8,11,12,9,12~9,12,12,12,11,6,10,12,3,1,11,11,11,7,8,10,10,10,5,4,11,7,11,7,11,8,1,10,12,1,12,5,11,12,11,5,1,10,12,10,12,7,5,12,10,5,11,10,7,11,3,12,7,3,1,7,11,7,12,11,7,1,5,12,11,5,11,12,10~12,12,12,1,9,8,12,6,5,11,4,11,11,11,7,3,10,10,10,10,11,5,10,5,11,5,11,1,9,4,7,5,9,10,11,10,11,9,11,9,11,10,11,9~11,11,11,5,12,12,12,4,10,10,10,10,12,3,6,7,11,9,1,8,10,8,10,12,10,12,10,12,10,12,10,12,10,9,12,10,7,8,12,9,8,12,7,10,12,8,3,8,12,4,12,10,12,8,12,7,12,10,12,9,10,12,10,8,10,12,10,12,3,10,6,8,12~13,13,13,13,13~13,13,13,13,13~13,13,13,13,13&reel_set4=2,2,2,2,2~2,2,2,2,2~2,2,2,2,2~11,11,11,6,7,10,3,4,10,10,10,8,12,12,12,9,12,11,1,5,12,8,5,9,12,9,10,1,9,12,1,10,12,10,9,8,10,6,12,8,12,7,9,12,8,12,8,12~11,11,11,12,11,12,12,12,7,4,3,9,6,1,10,10,10,5,8,10,1,10,12,10,12,10,12,8,10,7,10,9,12,7,3,4,8,10,3,4,5,12,4~9,12,12,12,8,11,11,11,6,10,3,1,12,5,7,10,10,10,4,11,6,10,7,1,7,6,10,11,10,11,8,7,10,11,12,3,12,5,10,1,10,6~5,11,10,10,10,10,9,12,12,12,12,7,1,11,11,11,4,8,3,6,1,4,11,4,9,11,3,9,11,12,11,12,9,7,6,12,10,11,10,11,4,11,12,10,11~12,12,12,4,11,11,11,12,5,10,10,10,6,7,3,8,9,10,1,11,1,10,8,3,1,11,1,10,7,11,9,10,9,3,11,10,9,10,3,10,3,9,10,6,1,6,3,6,11,10,9,7,11,10,6,4,1,6,10,3,11,9,11,4,6,11,1,11,3,10,1,3,1,6,1,3,10,3,10,1,10,9,7,3,1,3,1,10,6,1,7,11,1,11,1,10,1,11,6,10,6,9&reel_set3=2,2,2,2,2~2,2,2,2,2~11,11,11,8,10,1,11,12,12,12,9,4,10,10,10,3,12,5,7,6,12,8,12,9,10,9,12,9,6,1,6,8,9,3,10,7,8,10,7,5,7,12,9,12,8,9,8,7,8,4,6,12,10,4,10,3,5,10,7,4,9,10,5,10,3,12,3~1,9,8,5,6,4,12,10,10,10,11,10,12,12,12,7,11,11,11,3,11,7,12,11,7,11,4,12,10,7,11,12,11,12,11,10,11,4,11,10,11,8,9,11,8,11,10,11,10,11,7,11,7,4,9,11,7,11,7,10,11,7,11~11,11,11,8,5,11,12,12,12,6,10,4,10,10,10,3,1,12,9,7,4,10,8,6,4,8,6,8,6,10,4,8,6,4,9,3~9,6,4,3,8,7,11,11,11,1,10,11,12,5,10,10,10,12,12,12,11,5,6,4,10,12,6,11,5,12,7,4,11,7,1,11~12,12,12,12,10,11,6,4,10,10,10,8,5,11,11,11,3,7,9,1,11,4,10,3,11,9,4,10,9,6,4,9,11,8,4~13,13,13,13,13';
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
                $linesId = [];
                $linesId[0] = [1,1,1,1,1,1,1,1];
                $linesId[1] = [2,2,2,2,2,2,2,2];
                $linesId[2] = [3,3,3,3,3,3,3,3];
                $linesId[3] = [1,1,2,3,3,3,3,3];
                $linesId[4] = [3,3,2,1,1,1,1,1];
                $linesId[5] = [3,2,1,2,3,3,3,3];
                $linesId[6] = [1,2,3,2,1,1,1,1];
                $linesId[7] = [3,2,2,2,3,3,3,3];
                $linesId[8] = [2,1,1,1,2,2,2,2];
                $linesId[9] = [1,2,2,2,1,1,1,1];
                $linesId[10] = [2,3,3,3,2,2,2,2];
                $linesId[11] = [2,2,3,2,2,2,2,2];
                $linesId[12] = [2,2,1,2,2,2,2,2];
                $linesId[13] = [2,3,2,3,2,2,2,2];
                $linesId[14] = [2,1,2,1,2,2,2,2];
                $linesId[15] = [1,2,1,2,1,1,1,1];
                $linesId[16] = [3,2,3,2,3,3,3,3];
                $linesId[17] = [1,3,1,3,1,1,1,1];
                $linesId[18] = [3,1,3,1,3,3,3,3];
                $linesId[19] = [1,1,2,1,1,1,1,1];
                $linesId[20] = [3,3,2,3,3,3,3,3];
                $linesId[21] = [2,1,2,3,2,2,2,2];
                $linesId[22] = [2,3,2,1,2,2,2,2];
                $linesId[23] = [1,1,3,1,1,1,1,1];
                $linesId[24] = [3,3,1,3,3,3,3,3];
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
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
                $bonusMpl = 1;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $str_initReel = '';
                $reels = [];
                $fsmore = 0;
                $str_rwd = '';
                $str_accv = '';
                $str_accm = '';
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_initReel = $stack['initReel'];
                    $currentReelSet = $stack['reel_set'];
                    $fsmore = $stack['fsmore'];
                    $str_rwd = $stack['rwd'];
                    $str_accv = $stack['accv'];
                    $str_accm = $stack['accm'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $lastReel = explode(',', $stack[0]['reel']);
                    $str_initReel = $stack[0]['initReel'];
                    $currentReelSet = $stack[0]['reel_set'];
                    $fsmore = $stack[0]['fsmore'];
                    $str_rwd = $stack[0]['rwd'];
                    $str_accv = $stack[0]['accv'];
                    $str_accm = $stack[0]['accm'];
                }
                for($i = 0; $i < 8; $i++){
                    $reels[$i] = [];
                    for($j = 0; $j < 3; $j++){
                        $reels[$i][$j] = $lastReel[$j * 8 + $i];
                        if($lastReel[$j * 8 + $i] == $scatter){
                            $scatterCount++;
                            $scatterPoses[] = $j * 8 + $i;
                        }
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
                    for($j = 1; $j < 8; $j++){
                        $ele = $reels[$j][$linesId[$k][$j] - 1];
                        if($firstEle == $wild){
                            $firstEle = $ele;
                            $lineWinNum[$k] = $lineWinNum[$k] + 1;
                        }else if($ele == $firstEle || $ele == $wild){
                            $lineWinNum[$k] = $lineWinNum[$k] + 1;
                            if($j == 7){
                                $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                if($lineWins[$k] > 0){
                                    $totalWin += $lineWins[$k];
                                    $_obf_winCount++;
                                    $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                    for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                        $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 8 + $kk);
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
                                        $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 8 + $kk);
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
                if($scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin'){
                    $freeSpins = [0,0,0,8,8,8];
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freeSpins[$scatterCount]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                }else if($fsmore > 0){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                }
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 8; $i++){
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
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsend_total=1&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 'c';
                        $isEnd = true;
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
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    if($scatterCount >= 3){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1';
                    }
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($str_rwd != ''){
                    $strOtherResponse = $strOtherResponse . '&rwd=' . $str_rwd;
                }
                if($str_accv != ''){
                    $strOtherResponse = $strOtherResponse . '&accm=' . $str_accm . '&acci=0&accv=' . $str_accv;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin')  . '&reel_set='. $currentReelSet . $strOtherResponse . $strWinLine .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&st=rect&c='.$betline.'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=25&w='.$totalWin.'&s=' . $strLastReel;
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
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
    }
}

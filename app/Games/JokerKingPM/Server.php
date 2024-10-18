<?php 
namespace VanguardLTE\Games\JokerKingPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 25);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [3,6,6,6,6,6,7,6,6,6,6,6,7,6,6,6,6,6,7,6,6,6,6,6]);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', $lastEvent->serverResponse->BonusState);
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
                    $bet = '40.00';
                }
                $spinType = 's';
                
                $strInitReel = '';
                $str_srf = '';
                $fsmore = 0;
                if(isset($stack)){
                    $currentReelSet = $stack['reel_set'];
                    $strInitReel = $stack['is'];
                    $str_srf = $stack['srf'];
                    $wmv = $stack['wmv'];
                    $gwm = $stack['gwm'];
                    $str_rmul = $stack['rmul'];
                    $fsmore = $stack['fsmore'];
                    if($str_rmul != ''){
                        $strOtherResponse = $strOtherResponse . '&rmul=' . $str_rmul;
                    }
                    if($str_srf != ''){
                        $strOtherResponse = $strOtherResponse . '&srf=' . $str_srf;
                    }
                    if($wmv >= 0){
                        $strOtherResponse = $strOtherResponse . '&wmt=pr&wmv='. $wmv;
                    }
                    if($gwm > 0){
                        $strOtherResponse = $strOtherResponse . '&gwm=' . $gwm;
                    }
                    if($strInitReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $strInitReel;
                    }
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }
                if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                {
                    $strOtherResponse = '&fs=' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul=1';
                    if($fsmore > 0){
                        $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                    }
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));

                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=3,6,6,6,6,6,7,6,6,6,6,6,7,6,6,6,6,6,7,6,6,6,6,6&balance='. $Balance .'&cfgs=3934&ver=2&index=1&balance_cash='. $Balance .'&def_sb=8,8,8,8,3,8&prm=1~2,3,5,10,25;1~25,25,25,25,25&reel_set_size=2&def_sa=8,8,8,8,8,8&reel_set='.$currentReelSet.$strOtherResponse.'&balance_bonus=0.00&na=s&scatters=1~1,1,1,1,0,0~20,15,10,8,0,0~1,1,1,1,1,1&gmb=0,0,0&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"533902",max_rnd_win:"5000"}}&wl_i=tbm~5000&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=8,8,8,8,8,8&sb=8,8,8,8,3,8&sc='. implode(',', $slotSettings->Bet) .'&defc=100.00&sh=4&wilds=2~125,40,15,10,0,0~1,1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;125,40,15,10,0,0;125,40,15,8,0,0;50,25,12,6,0,0;40,15,10,5,0,0;30,12,8,4,0,0;25,10,5,3,0,0&l=25&reel_set0=4,4,4,4,8,3,6,6,6,7,5,6,3,3,3,7,7,7,5,5,5,8,8,8,3,7,5,6,7,6,7,5,6,7,3,5,3,8,6,5,7,3,7,6,3,6,7,6,7,5,3,7,5,6,7,8,6,7,3,6,7,8,5,7,6,7,8,6,7,3,7,3,5,6,7,8,7,3,6,3,7,5,8,3,7,8,7,3,7,8,6,5,6,8,7,8,7,8,5,7,3,6,3,7,8,7,3,7,6,8,6,8,7,3,6,7,5,3,6,7,8,6,5,3,5,3,8,7,3,5,8,6,7,6,7,6,8,5,3,7,3,7,3,5,7,8,7,3,8,7,5,3,6,3,7,6,8,7,3,6,7,6,7,3,5,3,7,3,7,8,6,7,3,7,5,7,3,5,7,3,5,6,3,8,3,7,3,5,3,8,6,8,5,8,5,7,3,7,3,7,3,7,3,7,5,8,6,3,5,7,3,7~4,5,7,7,7,3,7,8,6,3,3,3,5,5,5,6,6,6,8,8,8,4,4,4,5,3,8,5,6,5,7,5,7,3,6,5,6,8,7,6,5,6,5,3,7,3,5,8,5,3,7,8,3,8,3,5,8,6,8,3,5,7,8,3,8,5,3,6,5,8,5,6,8,5,8,3,5,6,7,6,7,5,8,5,7,5,3,6,8,5,8,6,7,3,5,8,6,3,5,8,7,3,8,5,7,5,6,3,8,6,8,3,8,5,3,8,3,5,8,3,8,3,7,8,3,6,3,7,8,5,8,3~4,8,7,7,7,5,6,4,4,4,7,3,6,6,6,5,5,5,8,8,8,7,3,6,7,5,6,5,8,7,5,8,7,3,6,7,8,6,5,6,8,5,8,5,7,5,6,3,6,7,8,5,3,7,6,7,6,8,7,5,8,6,5,7,5,8,5,6,5,6,7,8~5,4,4,4,3,3,3,3,7,4,5,5,5,6,8,7,7,7,6,6,6,8,8,8,7,6~3,3,3,8,5,5,5,7,3,5,4,6,8,8,8,7,7,7,4,4,4,6,6,6,4,7,5,6,8,6,8,5,4,8,7,8,5,8,5,4,8,6,7,6,7,6,5,8,6,5,8,6,5,4,5,8,4,8,6,8,5,8,5,6,5,8,4,5,7,4,6,7,6,8,5,6,8,6,8,7,8,7,6,7,4,7,5,8,4,8,7,8,4,7,6,8,6,7,4,8,5,8,5,6,5,6,8,7,8,7,8,7,8,4,8,7,5,8,4,8,5,7,5,8,7,6,7,8,7,5,7,6,4,8,7,4,5,8,5,6,5,7,8,5,8,6,8,7,8,4,5,8,6,7,8,4,7,6,8,7,8,5,6,7,8,6,4,5,6,5,8,6,4,7,8,6,7,6,8,4,8,6,8,5,8,7,6,8,4~8,5,5,5,7,7,7,7,5,8,8,8,4,3,6,4,4,4,3,3,3,6,6,6,7,6,7,6,3,4,7,6,7,6,3,6,4,5,6,7,5,6,5,7,3,7,4,6,5,6,5,7,6,3,5,6,3,5,6,5,6,7,3,6,3,6,5,7,6,3,4,5,6,5,7,6,5,4,6,5,7,5,7,6,4,5,4,5,3,5,7,3,5,6,5,3,6,5,6,5,6&s='.$lastReelStr.'&reel_set1=6,8,6,6,6,4,7,4,4,4,5,3,7,7,7,8,8,8,5,5,5,4,8,7,8,5,4,5,7,8,5,8,5,7,8,7,8,7,4,8,4,8,4,8,5,7,5,8,4,5,4,7,4,8,5,8,5,4,7,4,5,7,8,4,8,4,5,8,5,7,8,5,8,5,8,5,4~3,8,8,8,4,7,4,4,4,6,8,7,7,7,5,3,3,3,6,6,6,5,5,5,6,5,6,7,5,7,5,6,4,5,6,7,6,4,7,5,4,8,5,4,7,8,5,4,5,7,4,6,5,8,7,8,4,5,4,5,4,5,4,7,4,6,5,7,5,6,5,7,6,8,5,7,4,6,4,7,5,6,8,5,6,8,6,8,6,5,7,4,8,4,6,7,8,4,5,4,5,4,8,5,7,5,6,8,7,6,7,5,8,6,4,8,5,8,4,6,7,6,7,4,7,5,6,4,8,5,4,7,5,8,6,7,4,8,4,7,5,4,8,4,6,5,4,8,4,8,5,7,4,5,6,4,8,4,8,7,4,8,7,4,8,7,5,6,7,4,7,4,5,4,5,8,5,4,8,7,5,8,4,5,8,4,6,5,6,5,4,6,7,5,4,8,5,8,5,7,8,4,6,4,5,4,6,5,6,7,4,7,4,7,4,5,8,7,5,8,5,8,7,5,4,7,8,7,8,5,7,8,6,4,5,8,5,8,5,4,7,4,8,5,6,4,7,4,6,7,8,7,8,5,8,6,8,6,4,6,7,4,8,7,5,4,8,5,8,4,8,7,8,7,4,8,5~4,6,7,5,8,3,8,8,8,7,7,7,4,4,4,5,5,5,6,6,6,5,7,8,5,8,7,8,7,8,3,8,3,7,8,6,7,5,8,7,6,7,8,5,7,8,7,8,3,8,5,7,5,3,6,7,8,5,6,5,7,5,8,7,5,7,6,5,7,5,7,5,8,5,7,8,5,6,8,5,7,5,3,5,6,7,8,3,6,7,5,8,5,7,8,7,8,7,3,7,5,7,5,7,6,8,7,5,7,8,7,6,5,7,5,6,8,5,3,8,5,7,5,7,6,7,6,7,8,6,5,6,8,5,8,7,3,5,6,5,8,5,7,5,7,8,3,6,7,5,3,8,5,6,5,8,7,8,3,6,5,6,5,7,8,7,8,5,6,8,5,8,6,7,3,8,7,5,6,7,8,5,8,5,7,8,5,8,6,8,5,8,5,7,8,5,7,3,8,7,5,6,8,5,6,8,6,8,5,8,5,8,7,6,8,7,6,5,8,5,8,7,6,7,3,8,5,8,7,5,8,3,5,7,6,5,7,5,7,8,6,7,5,6,7,6,8,5,8,5~4,7,5,6,3,8,8,8,8,3,3,3,7,7,7,6,6,6,5,5,5,4,4,4,7,3,7,5,7,8,6,7,6,7,6,3,6,3,5,7,3,7,8,7,6,3,8,6,3,7,5,7,6,7,6,8,6,7,5,7,6,7,3,6,7,6,7,6,3,7,5,3,7,8,6,7,3,7,3,6,8,3,7,5,3,6,3,7,6,7,5,7,3,7,3,6,3,7,5,7,8,7,3,7,6,3,8,7,8,7,3,6,3,7,8,7,5,3,5,7,6,8~6,4,4,4,5,5,5,5,7,3,4,8,3,3,3,6,6,6,7,7,7,8,8,8,5,3,4,5,7,5,4,5,8,5,7,3,7,5,7,4,5,7,8,7,4,8,4,5,8,7,3,4,5,3,8,4,7,8,7,5,7,8,4,7,5,3,8,5,4,5,7,4,7,5,3,7,3,8,5,7,3,4,5,7,4,5,8,7,8,4,5,8,4,7,3,5,3,5,8,5,7,3,8,5,7,4,8,4,7,8,3,8,5,4,5,8,7,4,5,3,7,5,4,3,4,8,5,3,4,7,5,8,5,3,7,3,7,3,7,5,3,4,7,5,4,3,7,5,8,5,4,3,4,3,5,7,4,7~4,8,8,8,7,3,3,3,3,6,6,6,5,6,8,7,7,7,5,5,5,4,4,4,5,6,3,5,8,7,6,8,3,6,8,6,5,7,8,7,5,6,8,6,3,6,8,6,3,8,6,7,5,8,3,6,5,3,8,3,5,7,3,6,3,7,3,6,5,8,6,3,8,3,6,7,8,5,6,8,6,5,6,3,5,8,3,6,8,6,3,5,6,5,8,6,8,3,8,6,8,5,7,6,7,5,8,3,7,3,6,3,7,5,8,3,5,8,5,6,3,8,7,5,3,8,7,8,7,3,7,5,8,3,8,7,8,6,5,6,3,7,8,3,6,3,7,8,7,5,3,5,6,5,8,7,8,3,5,6,7,8,7,8,6,3,6,7,8,6,8,6,3,5,8,6,3,6,8,7,6,3,6,5,8,5,8';
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
                $linesId[0] = [2, 2, 2, 2, 2, 2];
                $linesId[1] = [1, 1, 1, 1, 1, 1];
                $linesId[2] = [3, 3, 3, 3, 3, 3];
                $linesId[3] = [4, 4, 4, 4, 4, 4];
                $linesId[4] = [2, 1, 1, 1, 1, 2];
                $linesId[5] = [2, 3, 3, 3, 3, 2];
                $linesId[6] = [1, 2, 2, 2, 2, 1];
                $linesId[7] = [3, 2, 2, 2, 2, 3];
                $linesId[8] = [3, 4, 4, 4, 4, 3];
                $linesId[9] = [4, 3, 3, 3, 3, 4];
                $linesId[10] = [2, 2, 1, 1, 2, 2];
                $linesId[11] = [2, 2, 3, 3, 2, 2];
                $linesId[12] = [1, 1, 2, 2, 1, 1];
                $linesId[13] = [3, 3, 2, 2, 3, 3];
                $linesId[14] = [3, 3, 4, 4, 3, 3];
                $linesId[15] = [4, 4, 3, 3, 4, 4];
                $linesId[16] = [2, 1, 2, 1, 2, 1];
                $linesId[17] = [2, 3, 2, 3, 2, 3];
                $linesId[18] = [2, 3, 4, 3, 2, 1];
                $linesId[19] = [1, 2, 1, 2, 1, 2];
                $linesId[20] = [1, 2, 3, 4, 3, 2];
                $linesId[21] = [3, 2, 3, 2, 3, 2];
                $linesId[22] = [3, 2, 1, 2, 3, 4];
                $linesId[23] = [3, 4, 3, 4, 3, 4];
                $linesId[24] = [4, 3, 2, 1, 2, 3];
                
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
                    if( $slotEvent['slotEvent'] == 'doSpin' && $slotSettings->GetBalance() < ($lines * $betline) ) 
                    {
                        $balance_cash = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                        if(!isset($balance_cash)){
                            $balance_cash = $slotSettings->GetBalance();
                        }
                        $response = 'nomoney=1&balance='. $balance_cash .'&error_type=i&index='.$slotEvent['index'].'&balance_cash='. $balance_cash .'&balance_bonus=0.00&na=s&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&ext_code=SystemError&sver=5&counter='. ((int)$slotEvent['counter'] + 1);
                        exit( $response );
                    }
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1< $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotEvent['slotEvent'] == 'freespin' ) 
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
                
                // $winType = 'win';
                // $_winAvaliableMoney = $slotSettings->GetBank('bonus');

                $freeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '6074458' . substr($roundstr, 4, 10);
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
                $strInitReel = '';
                $str_srf = '';
                $fsmore = 0;
                $wmv = -1;
                $gwm = -1;
                $str_rmul = '';
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $strInitReel = $stack['is'];
                    $str_srf = $stack['srf'];
                    $wmv = $stack['wmv'];
                    $gwm = $stack['gwm'];
                    $str_rmul = $stack['rmul'];
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
                    $strInitReel = $stack[0]['is'];
                    $str_srf = $stack[0]['srf'];
                    $wmv = $stack[0]['wmv'];
                    $gwm = $stack[0]['gwm'];
                    $str_rmul = $stack[0]['rmul'];
                    $fsmore = $stack[0]['fsmore'];
                }
                $reels = [];
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;
                for($i = 0; $i < 6; $i++){
                    $reels[$i] = [];
                    for($j = 0; $j < 4; $j++){
                        $reels[$i][$j] = $lastReel[$j * 6 + $i];
                        if($reels[$i][$j] == $scatter){
                            $scatterCount++;
                            $scatterPoses[] = $j * 6 + $i;   
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
                        $wildWin = 0;
                        $wildWinNum = 1;
                        for($j = 1; $j < 6; $j++){
                            $ele = $reels[$j][$linesId[$k][$j] - 1];
                            if($firstEle == $wild){
                                $firstEle = $ele;
                                $lineWinNum[$k] = $lineWinNum[$k] + 1;
                                if($j == 5){
                                    $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                    if($lineWins[$k] < $wildWin){
                                        $lineWins[$k] = $wildWin;
                                        $lineWinNum[$k] = $wildWinNum;
                                    }
                                    if($gwm > 0){
                                        $lineWins[$k] = $lineWins[$k] * $gwm;
                                    }
                                    if($lineWins[$k] > 0){
                                        $totalWin += $lineWins[$k];
                                        $_obf_winCount++;
                                        $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                        for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                            $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 6 + $kk);
                                        }
                                    }
                                }else if($j >= 2 && ($ele == $wild)){
                                    $firstEle = $wild;
                                    $wildWin = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                    $wildWinNum = $lineWinNum[$k];
                                }
                            }else if($ele == $firstEle || $ele == $wild){
                                $lineWinNum[$k] = $lineWinNum[$k] + 1;
                                if($j == 5){
                                    $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                    if($lineWins[$k] < $wildWin){
                                        $lineWins[$k] = $wildWin;
                                        $lineWinNum[$k] = $wildWinNum;
                                    }
                                    if($gwm > 0){
                                        $lineWins[$k] = $lineWins[$k] * $gwm;
                                    }
                                    $totalWin += $lineWins[$k];
                                    $_obf_winCount++;
                                    $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                    for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                        $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 6 + $kk);
                                    }
                                }
                            }else{
                                if($slotSettings->Paytable[$firstEle][$lineWinNum[$k]] > 0){
                                    $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                    if($lineWins[$k] < $wildWin){
                                        $lineWins[$k] = $wildWin;
                                        $lineWinNum[$k] = $wildWinNum;
                                    }
                                    if($gwm > 0){
                                        $lineWins[$k] = $lineWins[$k] * $gwm;
                                    }
                                    $totalWin += $lineWins[$k];
                                    $_obf_winCount++;
                                    $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                    for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                        $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 6 + $kk);
                                    }   
    
                                }else{
                                    $lineWinNum[$k] = 0;
                                }
                                break;
                            }
                        }
                    }
                

                if($scatterCount >= 3){
                    $scatterWin = $betline * $lines;
                    if($gwm > 0){
                        $scatterWin = $scatterWin * $gwm;
                    }
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
                if( $scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin') 
                {
                    $freeNums = [0,0,0,8,10,15,20];
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freeNums[$scatterCount]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                }
                if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);    
                }

                $strLastReel = implode(',', $lastReel);
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 6; $i++){
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
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin').'&fsmul_total=1&fsend_total=1&fsres_total='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 'c';
                        $isState = true;
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                    if($scatterCount >= 3 ){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse = $strOtherResponse  .'&fsmul=1&fsmax='. $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fswin=0.00&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') .'&fsres=0.00&psym=1~'. $scatterWin .'~' . implode(',', $scatterPoses);
                    }
                }
                if($str_rmul != ''){
                    $strOtherResponse = $strOtherResponse . '&rmul=' . $str_rmul;
                }
                if($str_srf != ''){
                    $strOtherResponse = $strOtherResponse . '&srf=' . $str_srf;
                }
                if($wmv >= 0){
                    $strOtherResponse = $strOtherResponse . '&wmt=pr&wmv='. $wmv;
                }
                if($gwm > 0){
                    $strOtherResponse = $strOtherResponse . '&gwm=' . $gwm;
                }
                if($strInitReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $strInitReel;
                }
                
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&ls=0&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .$strWinLine .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=4&c='.$betline.'&sver=5&reel_set='.$currentReelSet.'&counter='. ((int)$slotEvent['counter'] + 1) .'&l=25&s='.$strLastReel.'&w='. $totalWin;
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if( $scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin') 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                }
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
    }
}

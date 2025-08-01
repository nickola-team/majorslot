<?php 
namespace VanguardLTE\Games\DiscoLadyPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 50);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [3,10,5,4,7,5,8,4,3,10,4,6,9,5,7]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    if (isset($lastEvent->serverResponse->ReplayGameLogs)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', json_decode(json_encode($lastEvent->serverResponse->ReplayGameLogs), true)); //ReplayLog
                    }
                    if (isset($lastEvent->serverResponse->FreeStacks)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', json_decode(json_encode($lastEvent->serverResponse->FreeStacks), true)); // FreeStack
                    }
                    $bet = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $bet = '20.00';
                }
                $currentReelSet = 0;
                $spinType = 's';
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                $strOtherResponse = '';
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 )
                {

                    $strOtherResponse = '&fs=' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&w=0.00&fsmul=1';
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') > 0){
                        $strOtherResponse = $strOtherResponse . '&wmv=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . '&gwm=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl');    
                    }
                }
                
                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=3,10,5,4,7,5,8,4,3,10,4,6,9,5,7&balance='. $Balance .'&cfgs=5480&ver=3&index=1&balance_cash='. $Balance .'&def_sb=5,10,7,4,10&reel_set_size=4&def_sa=5,8,1,3,6&reel_set='.$currentReelSet.$strOtherResponse.'&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"892857",max_rnd_win:"5000"}}&wl_i=tbm~5000&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa=5,8,1,3,6&sb=5,10,7,4,10&sc='. implode(',', $slotSettings->Bet) .'&defc=20.00&sh=3&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&st=rect&c='. $bet .'&sw=5&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;400,150,75,0,0;300,150,50,0,0;250,100,40,0,0;200,100,30,0,0;125,30,15,0,0;125,30,15,0,0;100,20,10,0,0;100,20,10,0,0&l=50&reel_set0=1,9,9,4,7,8,6,8,1,8,9,9,9,7,8,8,9,3,3,10,3,10,6,10,4,4,4,7,6,9,5,6,7,10,1,7,1,3,1,5,5,5,10,3,1,4,1,8,5,7,7,10,6,6,6,4,9,5,1,7,8,10,10,9,1,7,7,7,9,8,10,7,4,6,10,9,5,5,7,6,8,8,8,5,4,4,10,10,5,9,4,4,3,8,3,3,3,7,8,7,6,1,10,4,8,8,6,8,8~5,3,10,6,2,7,9,1,3,9,10,2,2,2,8,2,9,5,2,6,9,8,9,10,9,8,5,5,5,7,8,9,6,5,6,7,5,6,6,8,9,4,4,4,9,2,6,9,9,1,4,8,10,6,4,8,8,8,5,6,4,1,7,8,8,1,6,3,4,6,9,9,9,7,8,9,1,8,8,1,6,5,8,4,6,6,6,8,6,1,10,2,9,1,3,1,8,3,9~7,3,9,4,10,8,5,10,10,5,5,6,9,9,5,5,4,3,5,10,2,8,9,5,1,3,7,9,3,1,5,2,2,2,10,10,4,1,9,10,1,10,10,3,1,9,5,9,9,6,9,7,1,7,4,6,6,9,1,10,8,9,5,4,2,9,7,10,10,7,6~6,5,6,7,7,6,9,6,6,5,4,9,9,9,3,6,7,5,6,6,1,7,6,8,10,3,8,8,8,9,6,1,6,2,8,3,4,2,9,1,4,6,6,6,5,3,7,10,1,8,8,6,4,7,9,4,4,4,6,7,4,2,6,7,9,7,6,8,7,5,5,5,8,2,5,3,1,6,8,6,7,7,1,7,7,7,4,10,7,1,3,2,6,3,1,9,7,7,2,2,2,10,7,3,4,4,10,1,6,2,4,1,3,3,3,4,6,3,4,4,10,8,9,1,7,5,7,1,7~7,2,4,8,5,9,1,1,10,9,4,4,4,5,1,4,1,2,3,10,4,4,7,1,5,5,5,7,1,6,7,5,7,7,6,8,9,3,1,1,1,9,9,5,10,8,3,9,10,1,3,7,3,3,3,8,6,10,4,5,3,7,2,10,3,4,7,7,7,2,1,8,8,9,10,7,8,9,9,1,6,6,6,5,1,1,7,9,10,7,5,9,9,8,8,8,10,9,4,4,7,7,5,1,3,1,5,10,10,10,8,5,7,6,8,6,4,1,5,9,2,2,2,4,10,10,8,5,5,10,10,2,6,8,1,7&s='.$lastReelStr.'&reel_set2=9,8,8,1,5,8,1,7,8,5,5,3,3,6,3,3,3,7,10,5,8,10,4,4,8,8,1,9,7,10,7,4,7,3,4,4,4,10,4,1,6,8,6,4,10,4,6,3,9,3,8,1,8,3,5,5,5,3,8,9,7,4,5,9,7,5,10,1,7,3,7,10,7,1~5,6,1,9,8,7,6,1,9,8,8,7,9,8,8,8,2,10,1,3,8,8,9,7,4,1,4,8,5,4,4,4,7,5,4,9,2,5,10,9,3,5,6,8,6,1,3,3,3,9,10,2,6,4,1,9,10,9,1,3,8,8,9,5,5,5,7,8,6,1,8,8,4,3,10,9,6,1,8,9,3~8,10,9,4,9,8,1,10,7,8,9,4,10,9,8,9,10,4,5,6,7,1,9,7,5,8,3,3,3,10,5,1,3,5,6,9,4,1,7,3,5,5,7,10,10,4,3,10,1,6,10,7,9,6,5,4,4,4,3,1,7,1,5,10,3,2,9,5,1,8,9,6,4,2,10,4,4,8,9,8,7,1,9,4,9~5,1,7,1,10,7,6,10,7,7,3,10,5,10,8,1,7,3,3,3,6,7,1,6,7,2,7,1,7,7,6,9,8,9,8,6,3,7,6,6,6,10,9,7,3,6,8,4,5,6,6,9,8,7,4,8,7,9,4,4,4,7,4,6,8,4,6,10,3,8,1,4,1,9,4,4,6,3,9,1,3~8,10,9,1,9,7,5,1,6,5,5,1,5,4,1,10,4,4,4,9,8,4,5,6,7,10,3,2,7,3,3,1,8,1,8,10,4,3,3,3,9,4,8,10,8,9,9,1,5,3,4,10,5,4,10,5,7,7,5,5,5,1,7,3,10,4,7,4,9,10,7,9,4,7,1,5,10,8,4,7,7,7,9,1,10,7,10,3,9,6,9,10,7,3,10,5,10,6,5,1,4&reel_set1=6,4,10,7,5,4,3,8,10,9,5,1,10,9,7,1,10,4,7,3,9,5,10,3,1,8,8,1,8,4,8,5,5,5,7,7,5,8,6,6,9,1,7,5,1,8,4,7,10,3,8,10,1,8,8,3,10,3,9,7,5,8,7,4,6,6,3,9~8,6,3,1,9,8,8,3,8,1,9,7,9,4,5,6,5,6,5,3,7,3,1,8,8,8,7,2,4,1,5,8,10,6,8,8,4,10,6,9,4,6,8,10,2,8,6,8,2,10,4,1,9,5,5,5,1,3,1,7,10,9,8,9,5,7,1,8,9,8,1,9,1,6,9,8,9,8,8,9,5~9,7,8,9,7,9,4,8,6,5,9,1,9,4,7,1,10,9,8,1,5,2,8,1,5,3,6,9,10,9,1,6,9,5,1,3,9,4,10,8,3,5,9,10,10,4,7,10,7,9,1,5,10,9,4,6,1,4,7,10,5,8,2,4,7,6,5,10,1,10~6,7,7,6,3,9,3,9,7,4,8,9,7,3,3,3,7,9,7,8,2,10,7,8,8,1,7,5,6,7,6,6,6,7,8,7,7,10,5,3,1,4,8,6,1,4,6,4,4,4,10,1,3,1,9,7,8,10,1,6,3,6,4,4~1,10,9,4,5,6,7,7,3,4,7,8,3,10,5,10,3,9,4,4,4,7,4,5,1,9,10,1,9,5,4,10,7,1,5,10,5,8,6,10,3,3,3,2,9,10,8,1,9,9,10,5,8,7,3,6,8,9,9,7,7,3,5,5,5,7,10,5,1,7,4,4,3,10,4,4,7,6,10,1,4,9,3,5,7,7,7,9,10,5,1,4,4,5,1,8,5,8,3,10,1,6,5,5,10,9,5,5&reel_set3=3,5,7,8,6,10,7,4,8,6,9,9,9,7,1,5,10,7,7,9,10,3,7,5,10,4,4,4,7,9,8,5,10,8,3,8,9,3,6,5,5,5,8,8,4,5,10,9,1,4,8,9,1,10,6,6,6,8,1,10,5,1,9,7,7,6,5,1,6,7,7,7,4,9,6,1,5,4,7,4,10,1,4,8,8,8,9,9,4,8,7,10,6,1,10,1,6,10,3,3,3,4,8,1,10,8,7,8,3,3,8,3,4~1,5,7,9,10,4,9,1,6,9,5,6,6,8,2,2,2,8,1,7,6,1,2,5,4,4,2,6,4,9,1,5,5,5,6,9,8,3,8,10,5,3,5,9,6,8,6,9,6,4,4,4,2,8,6,9,1,7,6,8,1,5,9,10,6,8,8,8,3,9,5,8,1,10,6,9,7,9,8,8,1,7,1,9,9,9,10,8,3,8,6,4,8,8,10,9,6,3,9,2,6,6,6,9,8,10,8,1,2,9,8,3,5,3,8,9,8,7,9,2~10,9,9,1,10,3,10,9,6,7,8,1,9,6,1,5,10,10,6,5,5,10,7,5,9,1,10,9,6,9,10,9,2,10,9,1,5,5,2,1,4,7,9,10,3,2,8,10,8,9,10,5,1,5,9,9,4,2,2,2,1,7,5,9,10,9,8,2,4,9,3,10,5,4,10,5,1,5,6,4,3,9,6,1,9,5,10,5,1,4,7,2,7,1,5,5,7,7,10,3,8,10,6,4,6,10,9,4,9,9,3,7,1,3,6,5,7,10,4,3~7,3,6,10,9,8,7,1,9,9,9,7,1,6,7,6,1,7,7,6,8,8,8,1,9,6,4,5,4,6,7,3,4,6,6,6,1,6,9,8,10,1,2,6,4,4,4,6,5,7,5,8,6,6,7,4,6,3,5,5,5,2,4,2,7,3,10,4,5,8,1,7,7,7,4,1,3,7,8,7,8,9,3,7,2,2,2,5,7,10,4,6,2,1,2,3,3,3,6,6,4,9,6,4,3,9,7,10,5,7~3,1,2,10,10,6,8,10,8,7,4,4,4,7,9,8,1,6,7,9,5,10,8,1,5,5,5,7,4,2,4,4,8,7,9,9,10,6,4,1,1,1,4,5,7,1,3,7,3,5,8,9,5,3,3,3,1,1,5,1,1,9,7,1,1,5,1,9,7,7,7,5,7,4,6,10,5,7,5,5,2,1,5,6,6,6,1,3,4,9,10,9,8,9,3,4,10,8,8,8,9,6,10,10,2,7,7,3,2,9,7,10,10,10,4,8,6,8,1,10,9,4,10,9,3,5,2,2,2,1,5,10,9,8,1,8,10,10,7,7,3,7';
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
                $lines = 50;      
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
                $slotEvent['slotLines'] = 50;
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
                $freeStacks = []; 
                $isGeneratedFreeStack = false;
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
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $_sum, $slotEvent['slotEvent']);
                    $bonusMpl = 1;
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.1f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '628' . substr($roundstr, 3, 8). '023';
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
                $wildValues = [];
                $wildPoses = [];
                $strWinLine = '';
                $winLineCount = 0;
                $fsmore = 0;
                $gwm = -1;
                $wmv = -1;
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 1];
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $gwm = $stack['gwm'];
                    $wmv = $stack['wmv'];
                    $fsmore = $stack['fsmore'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $stack);
                    $lastReel = explode(',', $stack[0]['reel']);
                    $currentReelSet = $stack[0]['reel_set'];
                    $gwm = $stack[0]['gwm'];
                    $wmv = $stack[0]['wmv'];
                    $fsmore = $stack[0]['fsmore'];
                }
                $reels = [];
                $scatterCount = 0;
                $scatterReels = [0,0,0,0,0];
                $scatterWin = 0;
                for($i = 0; $i < 5; $i++){
                    $reels[$i] = [];
                    for($j = 0; $j < 3; $j++){
                        $reels[$i][$j] = $lastReel[$j * 5 + $i];
                        if($reels[$i][$j] == $scatter){
                            $scatterReels[$i]++;
                        }
                    }
                }
                for($i = 0; $i < 5; $i++){
                    if($scatterReels[$i] > 0){
                        $scatterCount += $scatterReels[$i];
                    }else{
                        break;
                    }
                }
                
                for($r = 0; $r < 3; $r++){
                    if($reels[0][$r] != $scatter){
                        $this->findZokbos($reels, $reels[0][$r], 1, [($r * 5)]);
                    }                        
                }
                $uniqueFirstSymbols = array_unique(
                    array_map(function ($line) {return $line['FirstSymbol'];}, $this->winLines)
                );
                $wlc_vs = [];
                foreach ($uniqueFirstSymbols as $idx => $symbol) {
                    $dupLines = array_filter($this->winLines, function($line) use ($symbol) {return $line['FirstSymbol'] === $symbol;});
                    $uniqueMulSymbols = array_unique(
                        array_map(function ($line) {return $line['Mul'];}, $dupLines)
                    );
                    foreach($uniqueMulSymbols as $mul){
                        $dupMulLines = array_filter($dupLines, function($line) use ($mul) {return $line['Mul'] === $mul;});
                        // 윈라인 심볼위치배열
                        $symbols = array_unique(array_flatten(array_map(function($line) {return $line['Positions'];}, $dupMulLines)));
                        $strSymbols = implode(',', $symbols);
                        // 윈라인 응답
                        $dupCount = count($dupMulLines);
                        if($dupCount > 0){
                            $firstLineKey = array_key_first($dupMulLines);
                            $winLine = $dupMulLines[$firstLineKey];
                            if($gwm <= 0){
                                $gwm = 1;
                            }
                            $winLineMoney = $slotSettings->Paytable[$symbol][$winLine['RepeatCount']] * $betline * $gwm * $dupCount;
                            $totalWin += $winLineMoney;
                            $strWinLine = "{$symbol}~{$winLineMoney}~{$dupCount}~{$winLine['RepeatCount']}~{$strSymbols}~l" . ($gwm > 1 ? "~" . $gwm : "");
                            $wlc_vs[] = $strWinLine;
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
                if( $scatterCount >= 5 && $slotEvent['slotEvent'] != 'freespin') 
                {
                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 12);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                }
                if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                }
                
                $strLastReel = implode(',', $lastReel);
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 5; $i++){
                    $reelA[$i] = mt_rand(7, 10);
                    $reelB[$i] = mt_rand(7, 10);
                }
                $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
               
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $strOtherResponse = '';
                $isState = true;
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    if($wmv > 0){
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $wmv);
                    }
                    $spinType = 's';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $isEnd = false;
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                    {
                        $isEnd = true;
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsend_total=1&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 'c';
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
                    $strOtherResponse = $strOtherResponse . '&wmt=pr';
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                    if($scatterCount >= 5 ){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1';
                    }
                }
                if(count($wlc_vs) > 0){
                    $strOtherResponse = $strOtherResponse . '&wlc_v=' . implode(';', $wlc_vs);
                }
                if($gwm > 0){
                    $strOtherResponse = $strOtherResponse . '&gwm=' . $gwm;
                }
                if($wmv > 0){
                    $strOtherResponse = $strOtherResponse . '&wmv=' . $wmv;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&c='.$betline.'&sw=5&sver=5&reel_set='.$currentReelSet.'&counter='. ((int)$slotEvent['counter'] + 1) .'&l=50&s='.$strLastReel.'&w='.$totalWin;
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if( $scatterCount >= 5 && $slotEvent['slotEvent'] != 'freespin') 
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
        public function findZokbos($reels, $firstSymbol, $repeatCount, $positions){
            $wild = '2';
            $bPathEnded = true;
            if($repeatCount < 5){
                for($r = 0; $r < 3; $r++){
                    if($firstSymbol == $reels[$repeatCount][$r] || $wild == $reels[$repeatCount][$r]){
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

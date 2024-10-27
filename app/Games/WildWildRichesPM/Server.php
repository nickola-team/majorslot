<?php 
namespace VanguardLTE\Games\WildWildRichesPM
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
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [7,8,6,11,11,7,7,6,8,11,2,9,6,8,11,13,13,6,8,3]);
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
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $str_mo_wpos = $stack['mo_wpos'];
                    $mo_tv = $stack['mo_tv'];
                    $currentReelSet = $stack['reel_set'];
                    $fsmore = $stack['fsmore'];
                    
                    if($str_mo != ''){
                        $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                    }
                    if($mo_tv > 0){
                        $strOtherResponse = $strOtherResponse . '&mo_tv=' . $mo_tv . '&mo_tw=' . ($mo_tv * $bet);
                    }
                    if($str_mo_wpos > 0){
                        $strOtherResponse = $strOtherResponse . '&mo_wpos=' . $str_mo_wpos;
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
                $response = 'def_s=7,8,6,11,11,7,7,6,8,11,2,9,6,8,11,13,13,6,8,3&balance='. $Balance .'&nas=13&cfgs=3390&ver=2&mo_s=12&index=1&balance_cash='. $Balance .'&def_sb=10,2,1,5,3&mo_v=25,50,75,100,125,150,200,250,375,625,1250,2500,12500&reel_set_size=3&def_sa=4,8,11,12,11&reel_set='. $currentReelSet .'&mo_jp=1250;2500;12500&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&bl=0&mo_jp_mask=jp3;jp2;jp1&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime='. floor(microtime(true) * 1000) .'&sa=4,8,11,12,11&sb=10,2,1,5,3&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=40.00&sh=4&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='. $bet .'&sver=5&bls=25,30&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;200,75,50,0,0;100,50,20,0,0;50,25,10,0,0;25,10,8,0,0;20,8,4,0,0;15,8,4,0,0;10,5,2,0,0;10,5,2,0,0;10,5,2,0,0;0,0,0,0,0;0,0,0,0,0&l=25&total_bet_max=120,000.00&reel_set0=11,11,11,5,5,8,8,3,9,8,8,5,5,7,6,6,9,9,2,10,9,8,6,6,7,7,5,5,10,6,10,11,2,8,8,9,4,4,7,10,10,7,10,4,7,7,5,5,11,5,11,11,9,9,11,2,7,11,7,7,6,6,11,10,10,2,10,4,4,11,6,11,4,11,9,9,3,3,11,9,2,11,8,9,11,7,9,9,10,3,3,10,9,8,10,8,8,2,8,9,4,9,8,8,2,8,4,4,10,9,7,10,7,7,2,7,5,5,11,11,5,5,10,10,7,2,7,7,6,7,10,6,6,10,10,2,4,10,9,4,11,11,7,8,8,3,3,11,5,7,7,6,9,6,10,4,10,10,6,10,4,11,3,11,11,4,11,9,9,3,9,8,9,11,9,9,11,9,9,3,8,7,9,10,7,8,10,5,10,9,8,2,10,11,7,8,9,4,10,8,5,10,7,10~7,7,8,8,5,5,7,7,2,8,8,11,11,3,5,9,4,11,6,11,11,9,2,6,10,3,9,6,6,8,8,11,9,9,6,6,10,10,7,5,5,10,7,11,11,11,2,9,9,5,5,7,9,6,6,7,7,4,7,7,9,6,8,2,9,8,8,3,6,9,11,10,11,2,9,9,6,10,2,10,10,6,6,4,4,7,2,7,7,4,4,6,6,10,10,2,10,10,11,11,11,3,11,10,9,5,4,9,5,3,11,8,9,2,11,3,6,10,11,7,10,2,7,8,9~11,10,3,11,4,12,12,12,12,10,11,6,6,6,6,1,7,10,3,3,3,3,10,7,10,9,9,10,12,10,9,12,12,6,8,12,12,8,11,3,3,3,3,12,7,5,5,12,11,3,4,11,7,8,3,10,9,9,5,4,7,12,12,7,3,3,10,6,11,1,7,4,5,3,3,3,7,12,12,12,10,7,10,10,4,5,3,3,3,7,12,12,12,12,8,12,12~7,7,12,12,12,12,6,6,11,11,11,12,12,7,7,12,12,4,4,9,9,12,6,6,6,6,12,12,10,10,12,8,8,4,4,4,12,12,12,10,8,8,8,5,5,5,5,10,10,10,12,12,12,3,3,8,8,8,12,12,9,9,9,9,11,11,11,5,5,5,5,12,12,12,12,4,4,4,4,7,7,7,7,6,6,6,12,12,9,9,12,12,12,4,4,11,11,11,12,12,6,6,6,8,8,12,12,3,3,3,3,8,8,12,12,12,7,12,12,5,5,5,5,7,7,7,12,12,8,8,8,12,12,12,10,8,12,12,12,12,8,5,6,7,3,8,5,4,8,3,6,8,3,5,6,9,11,12,12,12,7,4,7,5,8,7,3,8,5,6,3,8,10,9,6,8,4,7,11,6,5,11,6,3,8,9,7,5,9,12,12,12,12,12,12,12,12,9,5,6,9~11,11,11,11,3,3,3,3,8,8,8,8,10,10,10,9,9,9,9,12,12,12,12,4,4,4,4,7,7,7,7,9,5,9,11,8,5,10,11,7,10,12,12,4,4,12,12,9,9,7,7,7,5,5,5,5,12,12,12,12,12,12,10,11,11,11,3,11,10,9,5,4,9,11,7,6,11,10,3,8,11,3,9,12,12,12,12,11,10,9,11,8,5,10,11,7,10,8,12,12,12,12,12,12,12,12,5,9,9,9,9,7,7,7,7,12,12,12,12,10,10,11,11,11,3,11,10,9,5,4,9,11,7,6,11,10,3,8,11,12,12,12,12,3,6,11,10,9,11,8,5,10,11,7,10,9,4,8,10,9,6,10,9,11,12,12,8,11,9,4,12,12,12,12,7,8,10,7,8,6,5,8,12,12,12,12,12,12,12,5,9,3,10,9,5,12,12,12,12,12,12&s='.$lastReelStr.'&reel_set2=11,11,11,5,5,8,10,10,2,8,8,5,5,2,6,6,8,9,2,8,9,8,6,6,7,7,5,5,2,6,10,5,7,11,7,7,6,6,10,11,10,2,10,4,4,11,6,11,4,11,2,9,3,3,11,9,2,11,3,9,11,3,9,10,9,3,3,10,9,2,10,8,8,2,8,9,4,9,8,8,2,8,4,4,10,9,7,2,7,7,2,7,5,5,11,2,5,5,10,10,7,2,7,7,6,7,2,6,6,10,10,2,4,10,9,4,11,11,3,11,2,3,3,8,5,9,7,6,2,6,10,4,10,10,6,10,4,11,3,11,11,4,11,3,2,3,9,8,9,11,9,2,11,9,9,3,8,3,9,10,7,2,10,5,10,9,8,2,10,11,7,8,9,4,10,2,5,10,7,10~7,7,2,8,5,5,7,7,2,8,8,11,2,11,5,9,9,2,6,11,11,9,2,6,10,10,2,6,6,8,8,5,7,4,7,2,9,6,8,2,8,9,8,3,3,2,9,11,11,2,9,3,9,10,2,10,10,6,2,4,4,7,2,7,7,4,4,6,6,10,10,2,10,10,11,2,11,3,11,10,2,5,4,9,3,3,11,8,9,2,11,3,6,10,11,7,10,2,7,8,9~11,10,3,11,4,12,12,12,12,10,11,6,6,6,6,1,7,10,7,3,3,3,10,7,10,9,9,12,12,12,9,10,9,1,5,4,7,12,12,7,3,3,10,6,11,1,7,4,5,3,3,3,7,12,12,12,12,12,12,12,10,7,10,12,12,12,12,10,4,5,3,3,3,7,12,12,12,12,8,12,12~7,7,12,12,12,12,6,6,11,11,11,12,12,7,7,12,12,4,4,9,9,12,6,6,6,6,12,12,10,10,12,12,8,8,12,12,9,9,11,11,11,5,5,5,5,12,12,12,12,10,10,10,4,4,4,4,7,12,7,7,6,6,6,12,12,9,9,9,9,12,12,12,4,4,12,11,11,12,12,6,6,6,8,8,12,12,3,3,3,3,8,8,12,12,12,7,12,12,5,5,5,5,7,7,7,12,12,8,8,8,12,12,12,10,8,12,12,12,12,8,5,6,7,3,8,5,4,8,3,6,8,3,5,6,9,11,12,12,12,7,4,7,5,8,7,3,8,5,6,3,8,10,9,6,8,4,7,11,6,5,11,6,3,8,9,7,5,9,12,12,12,12,12,12,12,12,9,5,6,9~11,11,11,11,3,3,3,3,8,8,8,8,10,10,10,9,9,9,9,12,12,12,12,4,4,4,4,7,7,7,7,12,12,12,12,12,12,12,10,11,11,11,3,11,12,9,5,4,12,11,7,6,11,10,3,8,11,3,9,12,12,12,12,11,10,9,11,8,5,10,11,7,10,8,12,12,12,12,12,12,12,12,5,5,5,5,9,9,9,12,12,7,7,7,12,12,12,12,10,10,11,11,11,3,11,10,9,5,4,9,11,7,6,11,10,3,8,11,12,12,12,12,3,6,11,10,9,11,8,5,10,11,7,10,9,4,8,10,9,6,10,9,11,12,12,8,11,9,4,12,12,12,12,7,8,10,7,8,6,5,8,12,12,12,12,12,12,12,5,9,3,10,9,5,12,12,12,12,12,12&t=243&reel_set1=5,2,9,7,2,11,11,3,2,9,8,2,7,6,2,7,10,2,4~8,2,8,3,2,10,10,2,11,2,8,11,2,9,9,4,2,6,5,10,2,10,9,2,11,10,2,7~11,11,3,11,4,12,12,12,12,10,10,10,6,6,6,6,9,9,7,7,7,1,12,12,10,10,10,12,12,12,12,8,8,8,8,11,11,12,12,12,12,4,4,12,12,3,6,12,12,12,8,11,9,3,3,3,12,12,5,5,12,12,3,4,11,7,8,3,10,9,5,5,4,12,12,12,12,3,11,8,6,6,5,3,10,6,11,7,7,4,5,3,3,3,7,12,12,12,12,12,12,12~7,7,12,12,12,12,6,6,11,6,6,6,6,12,12,10,10,12,12,5,5,8,8,8,8,3,3,3,11,11,12,12,12,9,9,9,12,12,4,4,4,12,12,12,10,8,8,8,5,5,5,5,10,10,10,12,12,12,3,3,8,8,8,12,12,9,9,11,11,11,5,5,5,5,12,12,12,12,4,4,4,4,12,7,7,7,6,6,6,12,12,9,9,12,12,12,4,4,11,11,11,12,12,6,6,6,8,8,12,12,3,3,3,3,8,8,12,12,12,12,12,12,5,5,5,5,7,7,7,12,12,8,8,8,12,12,12,12,12,12,12,12,12,8,5,6,7,3,8,5,4,8,3,6,8,3,5,6,9,11,12,12,12,12,4,7,5,8,7,3,8,5,6,3,8,10,9,6,8,4,7,11,6,5,11,6,3,8,9,7,5,9,12,12,12,12,12,12,12,12,9,5,6,9~11,11,11,11,3,3,3,3,8,12,4,4,4,4,7,7,7,7,12,9,9,9,5,5,5,5,7,7,7,12,11,11,11,12,12,12,11,10,9,11,8,8,8,8,5,10,11,12,12,12,12,4,4,12,12,12,9,7,7,7,5,5,5,5,12,12,12,12,12,12,10,11,11,11,3,11,10,9,5,4,9,11,7,6,11,10,3,8,11,3,9,12,12,12,12,11,10,9,11,8,5,10,11,7,10,10,10,8,12,12,12,12,12,12,12,12,5,9,9,9,9,7,7,7,7,12,12,12,12,10,10,11,11,11,3,11,10,9,5,4,9,11,7,6,11,10,3,8,11,12,12,12,12,3,6,11,10,9,11,8,5,10,11,7,10,9,4,8,10,9,6,10,9,11,12,12,8,11,9,4,12,12,12,12,7,8,10,7,8,6,5,8,12,12,12,12,12,12,12,5,9,3,10,9,5,12,12,12,12,12,12&total_bet_min=8.00';
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
                $slotEvent['slotBet'] = $slotEvent['c'];
                $bl = $slotEvent['bl'];
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
                
                $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $betline * $lines, $lines, $bl);
                $winType = $_spinSettings[0];
                $_winAvaliableMoney = $_spinSettings[1];

                // $winType = 'bonus';

                $allBet = $betline * $lines;
                if($bl == 1){
                    $allBet = $betline * 30;
                }
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
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '6074458' . substr($roundstr, 4, 10);
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
                $reels = [];
                $fsmore = 0;
                $str_mo = '';
                $str_mo_t = '';
                $str_mo_wpos = '';
                $mo_tv = 0;
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $fsmore = $stack['fsmore'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $str_mo_wpos = $stack['mo_wpos'];
                    $mo_tv = $stack['mo_tv'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $lastReel = explode(',', $stack[0]['reel']);
                    $currentReelSet = $stack[0]['reel_set'];
                    $fsmore = $stack[0]['fsmore'];
                    $str_mo = $stack[0]['mo'];
                    $str_mo_t = $stack[0]['mo_t'];
                    $str_mo_wpos = $stack[0]['mo_wpos'];
                    $mo_tv = $stack[0]['mo_tv'];
                }
                $wildCount = 0;
                for($i = 0; $i < 5; $i++){
                    $reels[$i] = [];
                    $isWild = false;
                    for($j = 0; $j < 4; $j++){
                        $reels[$i][$j] = $lastReel[$j * 5 + $i];
                        if($lastReel[$j * 5 + $i] == $scatter){
                            $scatterCount++;
                            $scatterPoses[] = $j * 5 + $i;
                        }
                        if($i < 2 && $lastReel[$j * 5 + $i] == $wild){
                            $isWild = true;
                            $wildCount++;
                        }
                    }
                }
                $_lineWinNumber = 1;
                $_obf_winCount = 0;
                for($r = 0; $r < 4; $r++){
                    if($reels[0][$r] != $scatter && $reels[0][$r] != 13){
                        $this->findZokbos($reels, $reels[0][$r], 1, '~'.($r * 5));
                    }                        
                }
                for($r = 0; $r < count($this->winLines); $r++){
                    $winLine = $this->winLines[$r];
                    $winLineMoney = $slotSettings->Paytable[$winLine['FirstSymbol']][$winLine['RepeatCount']] * $betline;
                    if($winLineMoney > 0){   
                        $isNewTumb = true;
                        $strWinLine = $strWinLine . '&l'. $r.'='.$r.'~'.$winLineMoney . $winLine['StrLineWin'];
                        $totalWin += $winLineMoney;
                    }
                } 
                $moneyWin = 0;
                if($mo_tv > 0){
                    $moneyWin = $mo_tv * $betline;
                    $totalWin = $totalWin + $moneyWin;
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
                if($wildCount == 2 && $scatterCount >= 1 && $slotEvent['slotEvent'] != 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 10);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                }else if($fsmore > 0){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                }
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 5; $i++){
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
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
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
                    if($wildCount == 2 && $scatterCount >= 1){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1';
                    }
                }
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                }
                if($mo_tv > 0){
                    $strOtherResponse = $strOtherResponse . '&mo_tv=' . $mo_tv . '&mo_tw=' . $moneyWin;
                }
                if($str_mo_wpos > 0){
                    $strOtherResponse = $strOtherResponse . '&mo_wpos=' . $str_mo_wpos;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin')  . '&reel_set='. $currentReelSet . $strOtherResponse . $strWinLine .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=4&st=rect&c='.$betline.'&sw=5&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=25&w='.$totalWin.'&bl=' . $bl.'&s=' . $strLastReel;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if( $slotEvent['slotEvent'] != 'freespin' && $wildCount == 2 && $scatterCount >= 1) 
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
                if($bl == 1){
                    $allBet = $betline * 30;
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
            if($repeatCount < 5){
                for($r = 0; $r < 4; $r++){
                    if($firstSymbol == $wild || $firstSymbol == $reels[$repeatCount][$r] || $reels[$repeatCount][$r] == $wild){
                        if($firstSymbol == $wild){
                            $this->findZokbos($reels, $reels[$repeatCount][$r], $repeatCount + 1, $strLineWin . '~' . ($repeatCount + $r * 5));
                        }else{
                            $this->findZokbos($reels, $firstSymbol, $repeatCount + 1, $strLineWin . '~' . ($repeatCount + $r * 5));
                        }
                        $bPathEnded = false;
                    }
                }
            }
            if($bPathEnded == true){
                if($repeatCount >= 3 || (($firstSymbol == 3 || $firstSymbol == 13) && $repeatCount == 2)){
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

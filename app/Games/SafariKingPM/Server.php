<?php 
namespace VanguardLTE\Games\SafariKingPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 50);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [5,6,7,8,6,5,6,7,8,6,10,3,4,11,5,11,8,9,3,11]);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', $lastEvent->serverResponse->BonusState);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
                    if (isset($lastEvent->serverResponse->ReplayGameLogs)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', json_decode(json_encode($lastEvent->serverResponse->ReplayGameLogs), true)); //ReplayLog
                    }
                    if (isset($lastEvent->serverResponse->FreeStacks)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', json_decode(json_encode($lastEvent->serverResponse->FreeStacks), true)); // FreeStack
                        $FreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                        if($slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') > 0){
                            $stack = $FreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') -1];
                        }
                    }
                    $bet = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $bet = '20.00';
                }
                $currentReelSet = 0;
                $spinType = 's';
                $fsmore = 0;
                if($stack != null){
                    $str_prg_m = $stack['prg_m'];
                    $str_prg = $stack['prg'];
                    $currentReelSet = $stack['reel_set'];
                    $fsmore = $stack['fsmore'];
                    $strWinLine = $stack['win_line'];
                    
                    if($str_prg != ''){
                        $strOtherResponse = $strOtherResponse . '&prg=' . $str_prg;
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
                }else{
                    $strOtherResponse = $strOtherResponse . '&prg_m=cp,lvl,tp&prg=0,0,1';
                }
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 == $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame')) 
                    {
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsend_total=1';
                    }
                    else
                    {
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                    if($fsmore > 0 ){
                        $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                    }
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
   
                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=5,6,7,8,6,5,6,7,8,6,10,3,4,11,5,11,8,9,3,11&balance='. $Balance .'&cfgs=2254&ver=2&index=1&balance_cash='. $Balance .'&reel_set_size=21&def_sb=10,11,8,1,7&def_sa=8,3,2,3,13&prg_cfg_m=lvl&balance_bonus=0.00&na=s&scatters=1~4,4,4,0,0~8,8,8,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&stime=' . floor(microtime(true) * 1000) . $strOtherResponse . '&sa=8,3,2,3,13&sb=10,11,8,1,7&reel_set10=3,3,9,5,8,4,3,3,3,10,6,9,7,11,12,6,4,11,5,10,7,12,4,12,6,10,7,9,11,5,1,12,4,13,6,9,10,7,11~3,3,3,8,4,10,6,13,5,12,2,3,9,2,4,10,6,7,13,2,9,2,2,2,2,2,10,2,2,6,9,11,4,8,13,10,6,13,5,10,7,8,4,7,10,6,13~4,8,11,3,3,13,6,8,7,12,5,11,4,2,6,9,2,2,2,10,12,11,4,13,2,1,5,2,11,7,9,2,11,5,2,2,13,4,9,7,10,4,9,11,7,13~3,3,3,3,3,10,4,8,6,11,9,7,2,2,2,2,12,4,2,2,2,13,11,5,13,8,12,5,10,2,2,13,4,9,2,6,11,5~10,3,3,10,4,11,5,10,7,1,12,4,10,2,2,2,8,2,2,13,6,2,8,2,2,10,5,13,9,7,10,12,5,1&prg_cfg=0&sc='. implode(',', $slotSettings->Bet) .'&defc=100.00&reel_set11=3,3,9,5,8,4,3,3,3,10,6,9,7,11,12,6,4,11,5,10,7,12,9,4,12,6,10,7,9,11,5,1,12,4,13,6,9,10,7,11~3,3,3,8,4,10,6,13,5,9,12,8,3,2,2,2,3,4,2,2,10,6,2,7,13,10,2,2,6,11,9,4,8,2,6,2,2,13,10,6,13,5,10,7,8,4,7,10,6,13~4,8,11,3,3,13,6,8,7,12,5,11,4,6,9,2,2,2,2,2,2,10,12,11,4,2,2,13,1,12,2,2,9,5,11,7,9,4,11,5,13,4,9,7,10,4,9,11~3,3,3,3,3,10,4,8,6,11,9,2,2,7,2,12,4,13,11,2,2,13,8,12,5,10,2,2,13,2,2,4,9,2,2,6,11,5~10,3,3,10,4,11,5,10,7,1,2,2,2,2,2,6,2,8,4,10,2,2,2,2,6,10,5,13,2,2,9,7,8,10,12,5,1&reel_set12=3,3,9,5,8,4,3,3,3,10,6,9,7,11,12,6,4,11,5,10,7,12,9,4,12,6,10,7,9,5,1,12,4,13,6,9,10,7,11~3,3,3,8,4,10,6,13,5,9,12,2,2,2,2,8,3,3,4,10,6,7,13,10,2,9,2,6,11,4,8,13,2,2,9,2,2,10,6,13,5,2,2,10,7,8,4,7,10,6,13~4,8,11,3,3,13,6,8,7,12,5,11,4,6,9,2,2,10,12,11,2,2,2,2,4,13,2,2,2,2,1,5,2,2,11,7,9,4,11,5,13,4,9,7,10,4,9,11,7,13~3,3,3,3,3,10,4,8,6,11,9,7,12,4,13,2,2,2,2,2,2,2,2,11,5,13,8,12,5,10,2,2,13,4,2,2,9,6,11,5~10,8,3,3,10,4,11,5,2,2,2,2,10,7,2,2,2,1,12,4,8,10,2,13,6,10,5,13,9,7,2,2,2,2,10,12,5,1&reel_set13=3,3,9,5,8,4,3,3,3,10,6,9,7,11,12,6,4,11,5,10,7,12,9,4,12,6,7,9,11,5,1,12,4,13,6,9,10,7,11~3,3,3,8,4,10,6,13,9,5,12,8,3,3,2,2,2,2,4,10,2,2,2,6,7,13,10,2,2,9,6,11,2,2,2,2,4,8,13,10,6,13,5,10,7,8,4,7,10,6,13~4,8,11,3,3,13,6,8,7,12,5,11,4,1,6,9,2,2,10,2,12,11,2,2,2,4,13,1,2,2,2,5,11,2,2,2,7,9,2,4,11,5,13,4,9,7,10,4,9,11,7,13~3,3,3,3,3,10,4,8,6,11,9,7,12,4,13,2,2,2,2,11,2,5,13,8,12,5,10,2,2,13,4,2,2,2,9,6,2,2,2,11,2,2,2,8,5~10,3,3,10,8,4,11,5,10,7,1,12,4,2,2,2,8,2,2,2,10,2,13,6,10,2,2,2,5,13,2,2,2,9,7,10,12,5,1&sh=4&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&reel_set18=3,3,3,3,3,9,5,8,4,10,6,9,7,11,5,12,6,9,4,11,5,10,12,9,4,12,6,10,7,9,11,5,10,1,12,4,13,6,9,10,7,11~3,3,3,3,3,8,4,10,6,13,5,12,8,4,10,6,8,7,13,9,5,10,2,2,2,2,2,2,2,2,2,2,6,11,4,8,2,2,2,2,13,6,2,2,2,2,13,8,7,10,6,13,5,10,7,8,4,9,7,10,6,13~3,3,3,3,3,13,6,8,7,12,5,11,4,1,8,6,9,2,2,2,2,2,2,2,2,2,2,2,2,10,7,12,2,2,2,2,5,11,4,2,2,2,2,13,11,5,13,4,9,7,8,6,11,12,7,10,4,9,5,11,7,13,8~3,3,3,3,3,10,4,8,6,11,5,9,7,12,4,13,2,2,2,2,11,2,2,2,2,5,13,2,2,2,2,7,2,8,4,2,2,12,5,10,2,2,2,13,4,9,6,11,5,12~3,3,3,3,3,8,6,9,10,4,11,5,10,7,1,12,4,10,2,2,2,2,2,2,8,2,2,2,13,6,10,2,2,2,2,5,2,2,2,2,2,13,9,7,10,12,5,11&n_reel_set=0&reel_set19=3,3,3,3,3,9,5,8,4,10,6,9,11,5,12,6,9,4,11,5,10,7,12,9,4,12,6,10,7,9,11,5,10,1,12,4,13,6,9,10,7,11~3,3,3,3,3,8,4,10,6,13,5,12,8,4,10,6,8,7,13,5,9,10,2,2,2,2,2,2,2,2,2,2,2,6,11,4,8,13,6,13,2,2,2,2,8,7,2,2,2,2,10,6,13,5,10,7,8,4,9,7,10,6,13~3,6,8,7,12,5,11,4,8,6,9,2,2,2,2,2,2,2,2,2,10,7,12,5,11,4,13,1,12,6,9,5,13,2,2,2,2,2,2,2,2,11,7,9,2,2,4,11,5,13,4,9,7,1,6,11,12,7,10,4,9,5,11,7,13,8~3,3,3,3,3,10,4,8,6,11,5,9,7,12,4,2,2,2,2,2,2,2,2,2,2,13,11,5,13,7,8,4,12,5,10,2,2,2,2,13,4,2,2,2,2,9,2,6,11,5,12~3,3,3,3,3,8,6,9,10,4,11,5,10,2,2,2,2,2,2,2,2,2,1,12,4,10,8,2,2,2,2,13,6,10,5,13,2,2,2,2,9,7,2,2,10,12,5,11&counter=2&reel_set14=3,3,9,5,8,4,3,3,3,10,6,9,7,11,12,6,4,10,7,12,9,4,12,6,10,7,9,11,5,1,12,4,13,6,9,10,7,11~3,3,3,8,4,10,6,13,5,9,2,2,2,12,8,2,2,2,3,3,4,2,2,2,10,6,7,13,10,2,2,6,11,9,4,8,2,2,13,10,6,13,2,5,10,7,8,4,7,10,6,13~4,8,11,3,3,13,6,8,7,12,5,11,4,6,9,2,2,2,2,2,2,2,2,2,2,2,2,2,10,12,11,4,13,2,2,2,1,5,11,7,9,4,11,5,13,4,9,7,10,4,9,11,7,13~3,3,3,3,3,7,10,4,8,6,11,9,2,2,2,2,4,13,11,5,2,2,2,13,7,8,2,2,2,12,5,2,2,7,10,2,2,13,4,9,6,11,5~10,3,3,10,4,11,5,2,2,2,8,2,2,2,2,8,10,7,9,1,12,4,10,2,13,6,10,2,2,2,5,13,9,7,2,2,2,10,2,2,2,12,5,1&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;1000,200,40,10,0;500,150,40,4,0;500,150,40,4,0;400,125,25,0,0;400,100,20,0,0;400,75,20,0,0;300,50,20,0,0;200,40,10,0,0;200,30,10,0,0;150,20,10,0,0;150,20,10,0,0&l=50&reel_set15=3,3,9,5,8,4,3,3,3,10,6,9,7,11,12,6,4,10,7,12,9,4,12,6,10,7,9,11,5,1,12,4,13,6,9,10,7,11~3,3,3,8,4,10,6,13,9,5,12,8,3,3,4,10,6,7,13,10,2,2,6,11,4,8,2,2,2,13,2,2,2,10,6,2,9,2,2,2,2,2,13,5,10,7,8,4,7,10,6,13~4,8,11,3,3,13,6,8,7,12,5,11,4,1,9,2,2,10,12,11,4,13,2,1,5,11,2,2,2,7,9,4,11,5,13,4,2,2,2,9,2,2,2,2,2,2,7,10,4,9,11,7,13~3,3,3,3,3,10,4,8,6,11,9,7,12,4,2,2,2,2,2,2,2,2,2,13,11,2,2,2,5,13,2,8,12,5,10,2,2,13,4,9,6,11,5~10,3,3,10,4,11,5,10,7,1,12,4,10,2,8,13,6,10,5,13,9,2,2,2,2,2,5,2,2,2,7,10,2,2,2,12,2,2,2,5,1,8&reel_set16=3,3,9,5,8,4,3,3,3,10,6,9,7,11,12,6,4,11,5,10,7,12,9,4,12,6,10,7,9,11,5,1,12,4,13,6,9,10,7,11~3,3,3,8,4,10,6,13,5,12,8,3,3,4,9,10,6,7,13,10,2,2,6,11,4,2,2,2,9,2,2,2,2,2,2,2,9,2,2,13,10,2,2,6,13,5,10,7,8,4,7,10,6,13~4,8,11,3,3,13,6,8,7,12,5,11,4,2,2,2,2,2,2,2,6,9,2,2,10,12,11,4,13,1,5,11,7,9,4,11,5,13,4,9,2,2,2,2,2,2,7,10,2,4,9,11,7,13~3,3,3,3,3,10,4,8,6,11,9,7,2,2,2,2,2,2,12,4,13,11,5,13,8,12,5,10,2,2,2,2,2,2,2,2,2,2,13,4,9,6,11,5~10,3,3,10,4,11,5,10,7,1,12,2,2,2,2,2,2,2,4,8,10,2,13,8,6,2,2,2,2,2,5,13,9,7,10,12,5,1&reel_set17=3,3,9,5,8,4,3,3,3,10,6,9,7,11,12,6,4,11,5,10,7,12,9,4,12,6,10,7,9,11,5,1,12,4,13,6,9,10,7,11~3,3,3,8,4,10,6,13,5,12,8,3,3,9,4,10,2,2,2,2,2,2,2,2,7,13,9,10,2,2,6,11,4,8,2,2,2,13,9,2,2,2,10,2,2,6,13,5,10,7,8,4,7,10,6,13~4,8,11,3,3,13,6,8,7,12,5,11,1,4,6,9,2,2,2,2,2,2,2,2,2,2,2,2,2,2,10,12,11,4,13,1,5,11,7,9,4,11,5,2,2,2,13,4,9,7,10,4,9,11,7,13~3,3,3,3,3,10,4,8,2,2,2,2,2,2,2,2,2,6,11,9,7,12,4,13,11,5,13,8,12,5,10,2,2,13,2,2,2,4,9,2,2,2,6,11,5~10,3,3,10,4,11,8,5,10,7,1,12,4,10,2,13,6,2,2,2,2,2,2,2,8,2,2,10,5,13,9,7,2,2,2,10,2,2,2,12,2,5,1&reel_set0=3,5,8,4,10,6,9,7,11,5,1,13,12,6,5,10,7,12,1,9,4,12,6,10,7,9,13,11,5,4,3,6,13,9,10,7,11~3,3,3,11,8,4,10,2,12,6,4,4,7,10,6,8,7,13,4,8,13,6,12,13,5,10,7,11,8,4,9,12,7,13~5,3,3,3,6,5,11,4,8,6,5,9,9,10,7,12,5,11,4,13,1,8,2,12,6,9,5,13,11,7,9,13,4,9,1,8,6,11,7,13,8~3,3,3,10,4,8,6,11,5,9,7,12,4,13,11,5,13,7,8,4,12,5,10,2,13,4,9,6,11,5,12,8~4,9,3,8,6,9,10,4,11,5,10,7,2,1,12,4,6,10,5,13,9,7,9&s='.$lastReelStr.'&reel_set2=3,3,9,5,8,4,3,3,3,10,6,9,7,11,12,6,4,11,10,7,12,9,4,12,6,10,7,9,11,5,1,12,4,13,6,9,10,7,11~3,3,3,8,4,9,10,6,13,5,12,8,3,3,4,10,6,7,13,2,10,5,2,9,6,11,4,8,13,10,6,13,5,10,7,8,4,7,10,6,13~4,8,11,3,3,13,6,8,7,12,5,11,4,6,9,2,2,3,10,12,11,4,13,1,5,11,7,9,4,11,5,13,4,9,7,10,4,9,11,7,13~3,3,3,3,3,10,4,8,6,11,9,7,12,4,13,11,2,5,13,8,12,5,10,9,2,13,4,9,6,11,5~10,3,3,10,4,11,5,8,10,7,1,12,4,10,2,2,13,6,8,10,5,13,9,7,10,12,5,1&reel_set1=3,3,9,5,8,4,3,3,3,10,6,9,7,11,12,6,4,11,5,10,7,12,9,4,12,6,10,7,9,11,5,1,12,4,13,6,9,10,7,11~3,3,3,8,4,10,6,13,5,2,12,8,3,3,4,10,6,7,13,10,9,4,7,6,11,4,8,9,13,10,6,13,5,10,7,8,4,7,10,6,13~4,8,11,3,3,13,6,8,7,12,5,11,4,6,9,2,8,10,12,11,4,13,1,5,11,7,9,4,11,5,13,4,9,7,10,4,9,11,7,13~3,3,3,3,3,10,4,8,6,11,9,7,12,4,13,11,5,13,8,12,5,10,5,2,13,4,9,6,11,5~10,3,3,10,4,11,5,10,7,1,8,12,4,10,9,13,6,2,10,5,13,9,8,7,10,12,5,1&reel_set4=3,3,9,5,8,4,3,3,3,10,6,9,7,11,12,6,4,11,5,10,7,12,9,4,12,6,10,7,9,11,5,1,12,4,13,6,9,10,7,11~3,3,3,8,4,10,6,13,5,12,8,3,3,4,2,4,10,9,6,7,13,10,7,2,6,9,11,4,2,2,8,13,9,10,6,13,5,10,7,8,4,7,10,6,13~4,8,11,3,3,13,6,8,7,12,5,11,4,6,9,2,6,2,10,12,11,4,13,1,5,2,2,11,7,9,4,11,5,13,4,9,7,10,4,9,11,7,13~3,3,3,3,3,10,4,8,6,11,9,7,12,4,13,11,2,13,8,12,5,10,2,2,13,4,9,6,2,11,5~10,3,3,10,4,11,5,10,7,1,8,12,4,10,2,8,13,6,2,2,2,10,5,8,13,9,7,10,12,5,1&reel_set3=3,3,9,5,8,4,3,3,3,10,6,9,7,11,12,6,4,11,5,10,7,12,9,4,12,6,10,7,9,11,5,1,12,4,13,6,9,10,7,11~3,3,3,8,4,10,6,13,5,12,8,3,3,2,4,10,6,9,7,13,10,2,2,6,11,4,8,9,13,10,6,13,5,10,7,8,4,7,10,6,13~4,8,11,3,3,13,6,8,7,12,5,11,4,6,9,2,9,10,12,2,2,11,4,13,1,5,11,7,9,4,11,5,13,4,9,7,10,4,9,11,7,13~3,3,3,3,3,10,4,8,6,11,9,7,12,4,13,11,5,2,13,8,12,5,10,2,2,13,4,9,6,11,5~10,3,3,10,4,11,5,10,7,1,12,4,8,10,2,13,6,10,5,13,9,7,2,2,10,8,12,5,1&reel_set20=3,3,3,3,3,9,5,8,4,10,6,9,11,5,12,6,9,4,11,5,10,7,12,9,4,12,6,10,7,9,11,5,10,1,12,4,13,6,9,10,7,11~3,3,3,3,3,8,4,10,6,13,5,12,8,4,10,6,8,7,13,5,10,2,2,9,2,2,2,2,2,2,2,2,2,6,11,4,8,13,2,2,2,2,6,13,2,2,2,2,8,7,10,6,13,5,10,7,8,4,9,7,10,6,13~3,6,8,7,12,5,11,4,8,6,9,2,2,2,2,2,2,2,2,2,10,7,12,5,11,4,13,1,12,6,9,5,13,2,2,2,2,11,2,2,2,7,9,4,11,5,13,4,2,2,2,2,9,7,1,6,11,12,7,10,4,9,5,11,7,13,8~3,3,3,3,3,10,4,8,6,11,5,9,7,12,4,2,2,2,2,2,2,2,2,2,2,13,11,5,13,7,8,4,12,5,10,2,2,2,2,13,4,2,2,2,2,9,2,2,6,11,5,12~3,3,3,3,3,8,6,9,10,4,11,5,10,2,2,2,2,2,2,2,2,2,1,12,4,10,8,2,2,2,2,13,6,10,5,2,2,2,2,2,2,13,9,7,10,12,5,11&reel_set6=3,3,9,5,8,4,3,3,3,10,6,9,7,11,12,6,4,11,5,10,7,12,9,4,12,6,10,7,9,11,5,1,12,4,13,6,9,10,7,11~3,3,3,8,4,10,6,13,5,2,12,8,3,2,4,10,6,7,13,10,9,2,2,6,11,4,9,2,2,8,13,10,6,13,5,10,7,8,4,7,10,6,13~4,8,11,3,3,13,6,8,7,12,2,5,11,4,6,9,2,2,10,12,11,4,13,1,2,2,5,11,7,9,4,11,5,13,4,9,2,10,4,9,11,7,13~3,3,3,3,3,10,4,8,6,11,2,9,7,2,4,13,11,5,13,8,12,5,10,2,2,13,4,9,2,2,6,11,5~10,3,3,10,2,4,11,5,10,2,2,7,8,1,12,4,10,2,13,6,10,5,8,2,2,13,9,7,10,12,5,1&reel_set5=3,3,9,5,8,4,3,3,3,10,6,9,7,11,12,6,4,11,5,10,7,12,9,4,12,6,10,7,9,11,5,1,12,4,13,6,9,10,7,11~3,3,3,8,4,10,6,13,5,12,8,3,3,4,2,9,10,6,7,13,10,2,2,6,11,9,4,8,13,10,6,13,5,10,7,2,2,8,4,7,10,6,13~4,8,11,3,3,13,6,8,7,12,5,11,4,6,9,2,2,2,10,12,2,2,11,4,13,1,5,11,7,9,4,11,5,13,4,9,7,10,4,9,11,7,13~3,3,3,3,3,10,4,8,6,11,9,7,12,4,3,2,2,2,13,11,5,13,8,12,5,10,2,2,13,4,9,6,11,5~10,3,3,10,4,11,8,8,2,2,5,10,7,1,12,4,10,2,8,13,6,10,5,13,9,7,2,2,8,10,12,5,1&reel_set8=3,3,9,5,8,4,3,3,3,10,6,9,7,11,12,6,4,11,5,10,7,12,9,4,12,6,10,7,9,11,5,1,12,4,13,6,9,10,7,11~3,3,3,8,4,10,6,13,9,5,2,12,8,3,3,4,10,2,2,6,7,2,13,9,10,2,2,6,11,9,4,8,13,10,6,13,5,10,2,2,7,8,4,7,10,6,13~4,8,11,3,3,13,6,8,7,12,5,11,4,6,9,2,2,10,12,11,4,13,1,5,2,2,9,7,9,2,11,5,2,4,9,2,2,7,10,4,9,11,13~3,3,3,3,3,10,4,8,2,2,2,6,11,9,7,12,4,2,2,13,11,5,2,8,12,5,10,2,2,13,4,9,6,11,5~10,3,3,10,4,11,5,10,7,1,12,4,10,2,8,13,2,2,6,10,5,2,2,13,9,2,2,7,8,2,10,12,5,1&reel_set7=3,3,9,5,8,4,3,3,3,10,6,9,7,11,12,6,4,11,5,10,7,12,9,4,12,6,10,7,9,11,5,1,12,4,13,6,9,10,7,11~3,3,3,8,4,10,6,13,9,5,12,2,2,8,3,3,2,4,10,6,7,13,9,10,2,2,6,11,2,2,4,8,13,10,6,13,5,10,7,8,4,7,10,6,13~4,8,11,3,3,13,6,8,7,12,5,11,4,6,9,2,2,2,10,12,11,2,4,13,1,9,2,11,7,9,4,2,5,13,4,9,7,10,4,9,11,7~3,3,3,3,3,10,4,8,6,2,9,7,12,4,13,11,5,2,13,8,12,5,10,2,2,2,13,4,9,6,2,2,11,5~10,3,3,10,4,11,5,10,7,2,2,1,8,12,2,4,2,10,2,8,13,6,10,5,13,9,7,10,12,5,2,2,1&reel_set9=3,3,9,5,8,4,3,3,3,10,6,9,7,11,12,6,4,11,5,10,7,12,9,4,12,6,10,7,9,11,5,1,12,4,13,6,10,7,11~3,3,3,8,9,4,10,6,13,5,12,8,2,3,2,5,3,4,10,6,9,7,13,10,2,2,6,11,4,8,2,2,2,13,10,2,6,13,5,10,7,8,4,2,10,6,13~4,8,11,3,3,13,6,8,7,12,5,11,4,6,9,2,2,10,2,2,12,11,4,13,1,2,2,5,11,2,7,9,4,11,5,13,4,2,2,9,7,10,4,9,7,13~3,3,3,3,3,10,4,8,6,11,9,7,12,4,13,11,5,13,8,12,5,10,2,2,2,2,13,4,9,6,11,2,2,2,2,2,5~10,3,3,10,4,11,5,10,7,2,1,8,12,4,10,2,13,6,2,2,10,5,13,2,2,9,7,8,10,12,5,12,2,2,2';
            }
            else if( $slotEvent['slotEvent'] == 'doCollect' || $slotEvent['slotEvent'] == 'doCollectBonus') 
            {
                $Balance = $slotSettings->GetBalance();
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);    
                $response = 'balance=' . $Balance . '&index=' . $slotEvent['index'] . '&balance_cash=' . $Balance . '&balance_bonus=0.00&na=s&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&na=s&sver=5&counter=' . ((int)$slotEvent['counter'] + 1);
                
                //------------ ReplayLog ---------------                
                $lastEvent = $slotSettings->GetHistory();
                if($lastEvent  != 'NULL'){
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
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames')+1 < $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotEvent['slotEvent'] == 'freespin' ) 
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.1f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '57409' . substr($roundstr, 2, 9);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $leftFreeGames = 0;
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                }
                
                $wild = '2';
                $scatter = '1';
                $Balance = $slotSettings->GetBalance();
                
                $totalWin = 0;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $str_prg_m = '';
                $str_prg = '';
                $currentReelSet = 0;
                $fsmore = 0;
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_prg_m = $stack['prg_m'];
                    $str_prg = $stack['prg'];
                    $currentReelSet = $stack['reel_set'];
                    $fsmore = $stack['fsmore'];
                    $strWinLine = $stack['win_line'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $lastReel = explode(',', $stack[0]['reel']);
                    $str_prg_m = $stack[0]['prg_m'];
                    $str_prg = $stack[0]['prg'];
                    $currentReelSet = $stack[0]['reel_set'];
                    $fsmore = $stack[0]['fsmore'];
                    $strWinLine = $stack[0]['win_line'];
                }
                $reels = [];
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;
                for($i = 0; $i < 20; $i++){
                    if($lastReel[$i] == $scatter){
                        $scatterCount++;
                        $scatterPoses[] = $i;   
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
                if($scatterCount >= 3){
                    $scatterWin = $lines * $betline * 4;
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 8);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                }
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
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {                    
                    $spinType = 's';
                    $isEnd = false;
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');                    
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                    {
                        $strOtherResponse = '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsend_total=1&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
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
                }else
                {
                    if($scatterCount >= 3 ){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse = '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fs=1&fsres=0.00&psym=1~' . $scatterWin . '~' . implode(',', $scatterPoses);
                    }
                }
                if($str_prg != ''){
                    $strOtherResponse = $strOtherResponse . '&prg=' . $str_prg;
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&reel_set='.$currentReelSet .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=4&c='.$betline.'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=50&s='.$strLastReel .'&w='.$totalWin;
                
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame')  . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if($scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin') 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                }

            }
            if($slotEvent['action'] == 'doSpin' || $slotEvent['action'] == 'doCollect' || $slotEvent['action'] == 'doCollectBonus'){
                $this->saveGameLog($slotEvent, $response, $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'), $slotSettings);
            }
            try{
                $slotSettings->SaveGameData();
                \DB::commit();
            }catch (\Exception $e) {
                $slotSettings->InternalError('RiseofSamurai3DBCommit : ' . $e);
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

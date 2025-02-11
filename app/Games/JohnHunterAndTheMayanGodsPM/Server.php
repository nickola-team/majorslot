<?php 
namespace VanguardLTE\Games\JohnHunterAndTheMayanGodsPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 10);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [9,11,12,12,9,9,6,12,5,9,6,6,12,5,4]);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $stack = null;
                
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', $lastEvent->serverResponse->BonusState);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
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
                    $bet = '100.00';
                }
                $currentReelSet = 0;
                $spinType = 's';
                $strOtherResponse = '';
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    $strOtherResponse = '&fs=' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&w=0.00&fsmul=1';
                }
                if($stack != null){
                    $str_initReel = $stack['init_reel'];
                    $str_accm = $stack['accm'];
                    $str_accv = $stack['accv'];
                    $str_ep = $stack['ep'];
                    $fsmore = $stack['fsmore'];
                    $currentReelSet = $stack['reel_set'];
                    $strWinLine = $stack['win_line'];
                    if($stack['reel_set'] > -1){
                        $currentReelSet = $stack['reel_set'];
                    }
                    if($fsmore > 0 ){
                        $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                    }
                    if($strWinLine != ''){
                        $arr_lines = explode('&', $strWinLine);
                        for($k = 0; $k < count($arr_lines); $k++){
                            $arr_sub_lines = explode('~', $arr_lines[$k]);
                            $arr_sub_lines[1] = str_replace(',', '', $arr_sub_lines[1]) / $original_bet * $bet;
                            $arr_lines[$k] = implode('~', $arr_sub_lines);
                        }
                        $strWinLine = implode('&', $arr_lines);
                    }
                    
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($str_accv != ''){
                        $strOtherResponse = $strOtherResponse . '&accm=' . $str_accm . '&acci=0&accv=' . $str_accv;
                    }
                    if($str_ep != ''){
                        $strOtherResponse = $strOtherResponse . '&ep=' . $str_ep;
                    }
                    $strOtherResponse = $strOtherResponse  . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }else{
                    $strOtherResponse = $strOtherResponse . '&prg_m=cp,tp,lvl&prg=0,5,0';
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));

                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=9,11,12,12,9,9,6,12,5,9,6,6,12,5,4&balance='. $Balance .'&cfgs=3710&ver=2&index=1&balance_cash='. $Balance .'&def_sb=6,10,2,10,4&reel_set_size=7&def_sa=7,11,1,12,9&reel_set='.$currentReelSet.$strOtherResponse.'&balance_bonus=0.00&na=s&scatters=1~50,20,2,0,0~12,12,12,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=7,11,1,12,9&sb=6,10,2,10,4&sc='. implode(',', $slotSettings->Bet) .'&defc=100.00&sh=3&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='. $bet .'&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;500,250,100,0,0;400,200,50,0,0;300,125,20,0,0;250,100,20,0,0;200,50,10,0,0;200,50,10,0,0;100,20,5,0,0;100,20,5,0,0;100,20,5,0,0;100,20,5,0,0&l=10&reel_set0=9,9,5,5,12,12,7,9,9,6,6,6,10,10,5,5,11,11,11,8,8,10,10,4,11,11,11,1,12,12,7,9,9,6,12,12,12,7,7,9,9,5,11,11,6,10,10,10,8,8,8,9,9,3,10,10,1,12,12,12,5,5,9,9,8,12,12,7,7,7,9,9,6,6,10,10,4,12,12,3,9,9,6,6,12,12,8,8,9,9,9,4,12,12,5,5,9,9,1,12,12,4,11,11,11,7,7,9,9,3,10,10,8,11,11,11,7~11,11,11,8,8,8,11,11,11,6,6,10,10,4,4,12,12,5,5,1,12,12,8,8,8,9,9,2,10,10,4,4,12,12,8,8,11,11,6,12,12,12,1,9,9,4,4,12,12,3,9,9,4,4,10,10,8,8,8,10,10,10,4,4,11,11,1,9,9,6,6,6,12,12,3,11,11,11,7,10,10,8,8,12,12,4,4,10,10,2,11,11,11,7,9,9,1,10,10,5,5,12,12,4,11,11,11,3,9,9,9,4,10,10,1~12,12,12,2,11,11,11,8,8,10,10,5,9,9,9,3,10,10,1,11,11,3,10,10,1,11,11,6,6,12,12,7,7,10,10,10,7,7,9,9,6,5,5,10,10,7,7,7,9,9,2,11,11,3,12,12,4,9,9,6,6,6,5,5,10,10,10,1,11,11,7,7,9,9,3,12,12,12,8,8,8,10,10,10,5,5,9,9,2,11,11,11,8,8,10,10,7,11,11,5,9,9,2,12,12,7,7,7,9,9,4,4,10,10,10,8,11,11,1~7,7,12,12,12,5,5,10,10,10,1,9,9,3,12,12,12,7,7,11,11,1,10,10,5,12,12,7,7,7,11,11,11,8,8,12,12,4,10,10,10,1,12,12,12,8,8,10,10,6,9,9,9,8,8,8,10,10,10,4,11,11,6,6,6,12,12,5,10,10,1,9,9,9,6,11,11,11,2,10,10,4,11,11,7,7,10,10,8,11,11,11,5,9,9,4,4,12,12,3,11,11,1,10,10,7,11,11,11,4,4,10,10,5,11,11~12,10,10,10,6,6,9,9,9,4,4,11,11,11,8,8,8,10,10,11,11,11,1,10,10,8,8,12,12,4,4,9,9,9,11,11,11,3,12,12,4,4,10,10,10,3,9,9,7,7,7,12,12,9,9,3,12,12,5,5,10,10,1,9,9,9,6,6,6,10,10,5,9,9,8,8,12,12,12,7,11,11,11,6,6,9,9,3,11,11,11,1,12,12,12,6,6,9,9,9,7,7,11,11,11,4,4,9,9,9,3,10,10,8,12&s='.$lastReelStr.'&accInit=[{id:0,mask:"cp;tp;lvl;sc;cl"}]&reel_set2=9,9,5,5,12,12,7,9,9,6,6,6,10,10,7,7,11,11,11,12,12,10,10,7,7,12,7,7,7,10,10,4,11,11,11,1,12,12,7,9,9,6,12,12,12,7,7,9,9,5,11,11,6,10,10,10,7,7,9,9,3,10,10,10,12,12,12,5,5,9,9,7,12,12,7,7,9,9,7,7,10,10,4,12,12,3,9,9,6,6,12,12,7,7,9,9,9,4,12,12,7,7,9,9,1,12,12,4,11,11,11,7,7,9,9,3,10,10,7,11,11,11,7~11,11,11,7,7,7,11,11,11,7,7,10,10,4,4,12,12,7,7,11,11,2,9,9,6,9,1,12,12,7,7,7,9,9,2,10,10,4,4,12,12,7,7,11,11,6,12,12,12,9,9,9,4,4,12,12,7,7,10,10,3,9,9,7,7,7,10,10,10,4,4,11,11,1,9,9,7,7,7,12,12,3,11,11,11,7,10,10,7,7,12,12,4,4,10,10,2,11,11,11,7,9,9,1,10,10,7,7,5,12,12,4,11,11,11,3,9,9,9,4,10,10,1~12,12,12,2,11,11,11,7,7,10,10,5,9,9,9,3,10,10,1,12,12,12,3,9,9,7,7,11,11,3,10,10,11,11,11,6,6,12,12,7,7,10,10,10,7,7,6,9,9,5,5,10,10,7,7,7,9,9,2,11,11,3,6,6,6,12,12,4,9,9,5,5,10,10,10,1,11,11,7,7,9,9,3,12,12,12,7,7,7,10,10,10,7,7,9,9,2,11,11,11,7,7,10,10,7,11,11,5,9,9,2,12,12,7,7,7,9,9,4,4,10,10,10,7,11,11,1~7,7,12,12,12,7,7,10,10,10,9,9,9,3,12,12,12,7,7,10,10,6,6,11,11,5,5,11,11,1,10,10,5,12,12,7,7,7,11,11,11,7,7,12,12,4,10,10,10,1,12,12,12,7,7,10,10,6,9,9,9,7,7,10,10,10,4,11,11,6,6,6,12,12,5,10,10,10,9,9,9,6,11,11,11,2,10,10,4,11,11,7,7,10,10,7,11,11,11,5,9,9,7,7,12,12,3,11,11,1,10,10,7,11,11,11,4,4,10,10,5,11,11~12,10,10,10,6,6,9,9,9,4,4,11,11,11,7,7,7,10,10,10,3,12,12,7,7,9,3,11,11,11,1,10,10,7,7,12,12,4,4,9,9,9,11,11,11,3,12,12,4,4,10,10,10,3,9,9,7,7,7,12,12,9,9,3,12,12,5,5,10,10,1,9,9,9,6,6,6,10,10,5,9,9,7,7,12,12,12,7,11,11,11,6,6,9,9,3,11,11,11,1,12,12,12,6,6,9,9,9,7,7,11,11,11,4,4,9,9,9,3,10,10,7,12&reel_set1=9,9,5,5,12,12,7,9,9,6,6,6,10,10,8,8,11,11,11,12,12,10,10,10,10,4,11,11,11,1,12,12,7,9,9,6,12,12,12,7,7,9,9,5,11,11,6,10,10,10,8,8,8,9,9,3,10,10,10,12,12,12,5,5,9,9,8,12,12,7,7,7,9,9,8,8,10,10,4,12,12,3,9,9,6,6,12,12,8,8,9,9,9,4,12,12,5,5,9,9,1,12,12,4,11,11,11,8,8,9,9,3,10,10,8,11,11,11,7~11,11,11,8,8,8,11,11,11,6,6,10,10,4,4,12,12,8,8,11,11,2,9,8,8,8,9,9,2,10,10,4,4,12,12,8,8,11,11,6,12,12,12,9,9,9,8,8,12,12,4,4,10,10,3,9,9,8,8,8,10,10,10,4,4,11,11,1,9,9,8,8,8,12,12,3,11,11,11,7,10,10,8,8,12,12,4,4,10,10,2,11,11,11,7,9,9,1,10,10,5,5,5,12,12,4,11,11,11,3,9,9,9,4,10,10,1~12,12,12,2,11,11,11,8,8,10,10,5,9,9,9,3,10,10,1,12,12,12,3,3,10,10,11,11,11,6,6,12,12,7,7,10,10,10,7,7,9,9,6,5,5,10,10,7,7,7,9,9,2,11,11,3,12,12,4,6,6,6,9,9,5,5,10,10,10,1,11,11,7,7,9,9,3,12,12,12,8,8,8,10,10,10,5,5,9,9,2,11,11,11,8,8,10,10,7,11,11,5,9,9,2,12,12,7,7,7,9,9,4,4,10,10,10,8,11,11,1~7,7,12,12,12,5,5,10,10,10,9,9,9,3,12,12,12,7,7,10,10,6,6,1,10,10,5,12,12,7,7,7,11,11,11,8,8,8,12,12,4,10,10,10,1,12,12,12,8,8,10,10,6,9,9,9,8,8,10,10,10,4,11,11,6,6,6,12,12,5,10,10,10,9,9,9,6,11,11,11,2,10,10,4,11,11,7,7,10,10,8,11,11,11,5,9,9,4,4,12,12,3,11,11,1,10,10,7,11,11,11,4,4,10,10,5,11,11~12,10,10,10,6,6,9,9,9,4,4,11,11,11,8,8,8,10,10,10,3,12,12,11,1,10,10,8,8,12,12,4,4,9,9,9,11,11,11,3,12,12,4,4,10,10,10,3,9,9,7,7,7,12,12,9,9,3,12,12,5,5,10,10,1,9,9,9,6,6,6,10,10,5,9,9,8,8,12,12,12,7,11,11,11,6,6,9,9,3,11,11,11,1,12,12,12,6,6,9,9,9,7,7,11,11,11,4,4,9,9,9,3,10,10,8,12&reel_set4=9,9,5,5,12,12,5,9,9,5,5,5,10,10,5,5,11,11,11,5,5,10,10,5,5,9,11,1,11,11,5,5,9,9,9,5,5,12,12,5,5,5,9,1,11,11,5,5,10,10,5,5,9,9,3,10,10,10,12,12,5,5,5,9,9,5,12,12,5,5,9,9,5,5,10,10,4,12,12,3,9,9,5,5,12,12,5,5,5,9,9,4,12,12,5,5,9,9,1,12,12,4,11,11,5,5,5,9,9,3,10,10,5,5,11,11,5~11,11,11,5,5,5,11,11,5,5,5,10,10,4,4,12,12,5,5,11,11,2,5,5,5,10,9,2,10,10,4,4,12,12,12,5,5,11,11,5,5,12,12,9,9,9,4,4,12,12,4,4,10,10,3,9,9,5,5,5,10,10,10,4,4,11,11,1,9,9,5,5,5,12,12,3,11,11,5,5,10,10,5,5,12,12,4,4,10,10,2,11,11,5,5,9,9,1,10,10,5,5,5,12,12,5,5,11,11,3,9,9,9,4,10,10,1~12,12,12,2,11,11,5,5,5,10,10,5,5,9,9,3,10,10,1,5,5,10,10,5,5,5,11,11,11,5,5,12,12,5,5,5,10,10,5,5,9,9,5,5,10,10,5,5,5,9,9,2,5,5,5,12,12,4,9,9,5,5,10,5,5,5,11,11,5,5,9,9,5,5,12,12,5,5,5,10,10,10,5,5,9,9,2,11,11,1,5,5,10,10,5,11,11,5,5,5,2,12,12,5,5,5,9,9,4,4,10,10,5,5,11,11,1~5,5,12,12,12,5,5,10,10,10,9,9,9,3,12,12,12,9,9,10,10,5,5,11,11,11,12,12,5,5,5,11,11,11,10,10,12,12,4,10,10,10,1,12,12,12,5,5,10,10,5,9,9,9,5,5,10,10,10,4,11,11,10,10,10,12,12,5,10,10,10,9,9,9,5,11,11,11,2,10,10,4,11,11,9,9,10,10,5,11,11,11,5,9,9,4,4,12,12,3,11,11,1,10,10,5,11,11,11,4,4,10,10,5,11,11~12,10,10,10,11,11,9,9,9,4,4,11,11,11,5,5,5,10,10,10,3,12,12,5,5,5,5,5,12,12,4,4,9,9,9,11,11,11,3,12,12,4,4,10,10,10,3,9,9,4,4,4,12,12,9,9,3,12,12,5,5,10,10,1,9,9,9,3,3,3,10,10,5,9,9,5,5,12,12,12,5,11,11,11,5,5,9,9,3,11,11,11,1,12,12,12,10,10,9,9,9,5,5,11,11,11,4,4,9,9,9,3,10,10,5,12&reel_set3=9,9,5,5,12,12,6,9,9,6,6,6,10,10,5,5,11,11,11,12,12,10,10,6,10,10,4,11,11,11,1,12,12,6,9,9,6,12,12,12,6,6,9,9,5,11,11,6,10,10,10,6,6,9,9,3,10,10,10,12,12,12,5,5,9,9,6,12,12,6,6,9,9,6,6,10,10,4,12,12,3,9,9,6,6,12,12,6,6,9,9,9,4,12,12,5,5,9,9,1,12,12,4,11,11,11,6,6,9,9,3,10,10,6,11,11,11,6~11,11,11,6,6,6,11,11,11,6,6,10,10,4,4,12,12,5,5,11,11,2,9,9,6,6,6,9,9,2,10,10,4,4,12,12,6,6,11,11,6,12,12,12,9,9,9,4,4,12,12,4,4,10,10,3,9,9,6,6,6,10,10,10,4,4,11,11,1,9,9,6,6,6,12,12,3,11,11,11,6,10,10,6,6,12,12,4,4,10,10,2,11,11,11,6,9,9,1,10,10,5,5,5,12,12,4,11,11,11,3,9,9,9,4,10,10,1~12,12,12,2,11,11,11,6,6,10,10,5,9,9,9,3,10,10,1,12,12,12,3,9,3,10,10,11,11,11,6,6,12,12,6,6,10,10,10,6,6,9,9,5,5,10,10,6,6,6,9,9,2,11,11,3,12,12,4,9,9,5,5,10,10,10,1,11,11,6,6,9,9,3,12,12,12,6,6,6,10,10,10,5,5,9,9,2,11,11,11,6,6,10,10,6,11,11,5,9,9,2,12,12,6,6,6,9,9,4,4,10,10,10,6,11,11,1~6,6,12,12,12,5,5,10,10,10,9,9,9,3,12,12,12,6,6,10,10,6,6,11,1,10,10,5,12,12,6,6,6,11,11,11,6,6,12,12,4,10,10,10,1,12,12,12,6,6,10,10,6,9,9,9,6,6,10,10,10,4,11,11,6,6,6,12,12,5,10,10,10,9,9,9,6,11,11,11,2,10,10,4,11,11,6,6,10,10,6,11,11,11,5,9,9,4,4,12,12,3,11,11,1,10,10,6,11,11,11,4,4,10,10,5,11,11~12,10,10,10,6,6,9,9,9,4,4,11,11,11,6,6,6,10,10,10,3,12,12,6,11,1,10,10,6,6,12,12,4,4,9,9,9,11,11,11,3,12,12,4,4,10,10,10,3,9,9,6,6,6,12,12,9,9,3,12,12,5,5,10,10,1,9,9,9,6,6,6,10,10,5,9,9,6,6,12,12,12,6,11,11,11,6,6,9,9,3,11,11,11,1,12,12,12,6,6,9,9,9,6,6,11,11,11,4,4,9,9,9,3,10,10,6,12&reel_set6=9,9,3,3,12,12,3,3,9,9,3,3,10,10,3,3,11,11,11,3,3,10,10,10,3,11,11,1,11,11,3,3,9,9,3,3,12,12,3,3,3,9,1,11,11,3,3,10,10,3,3,9,9,9,3,10,10,10,12,12,3,3,3,9,9,3,12,12,3,3,9,9,3,3,10,10,3,12,12,3,9,9,3,3,12,12,12,3,3,9,9,3,12,12,3,3,9,9,1,12,12,3,11,11,11,3,3,9,9,3,10,10,3,3,11,11,3~11,11,11,3,3,3,11,11,11,3,3,10,10,10,3,12,12,3,3,1,12,12,12,3,3,9,9,2,10,10,3,3,12,12,3,3,11,11,3,3,12,12,9,9,9,3,3,12,12,3,3,10,10,3,9,9,9,3,3,10,10,10,3,3,11,11,2,9,9,3,3,12,12,12,3,11,11,3,3,10,10,3,3,12,12,3,3,10,10,2,11,11,11,3,9,9,1,10,10,10,3,3,12,12,3,11,11,11,3,9,9,9,3,10,10,1~12,12,12,2,11,11,3,3,3,10,10,3,3,9,9,3,10,10,1,3,11,11,3,10,10,1,11,11,3,3,12,12,3,3,10,10,10,3,3,9,9,9,3,10,10,10,3,3,9,9,2,11,3,3,12,12,3,9,9,3,10,10,3,3,12,11,11,11,3,3,9,9,3,3,12,12,3,3,3,10,10,10,3,3,9,9,2,11,11,1,3,3,10,10,3,11,11,3,3,3,2,12,12,3,3,3,9,9,3,3,10,10,10,3,11,11,1~3,3,12,12,12,3,3,10,10,10,9,9,9,3,12,12,12,9,9,3,11,11,1,10,10,3,12,12,3,3,3,11,11,11,10,10,12,12,3,10,10,10,1,12,12,12,3,3,10,10,3,9,9,9,3,3,10,10,10,3,11,11,10,10,10,12,12,3,10,10,10,9,9,9,3,11,11,11,2,10,10,3,11,11,9,9,10,10,3,11,11,11,3,9,9,3,3,12,12,3,11,11,1,10,10,3,11,11,11,3,3,10,10,3,11~12,10,10,10,11,11,3,9,9,3,3,11,11,11,3,3,3,10,10,3,11,11,11,1,10,10,3,3,12,12,3,3,9,9,9,11,11,11,12,12,12,3,3,10,10,10,3,9,9,3,3,3,12,12,9,9,3,12,12,3,3,10,10,1,9,9,9,3,3,3,10,10,10,9,9,3,3,12,12,12,3,11,11,11,3,3,9,9,9,11,11,11,1,12,12,12,10,10,9,9,9,3,3,11,11,11,3,3,9,9,9,3,10,3,3,12&reel_set5=9,9,4,12,12,12,4,9,9,9,4,4,10,10,4,4,11,11,11,4,4,10,4,11,11,1,11,11,4,4,9,9,4,4,12,12,4,4,4,9,1,11,11,4,4,10,10,4,4,9,9,3,10,10,10,12,12,4,4,4,9,9,4,12,12,4,4,9,9,4,4,10,10,4,12,12,3,9,9,4,4,12,12,12,4,4,9,9,4,12,12,4,4,9,9,1,12,12,4,11,11,11,4,4,9,9,3,10,10,4,4,11,11,4~11,11,11,4,4,4,11,11,11,4,4,10,10,10,4,12,12,4,4,11,11,4,4,9,9,2,10,10,4,4,12,12,4,4,11,11,4,4,12,12,9,9,9,4,4,12,12,4,4,10,10,3,9,9,9,4,4,10,10,10,4,4,11,11,9,9,9,4,4,12,12,12,3,11,11,4,4,10,10,4,4,12,12,4,4,10,10,2,11,11,11,4,9,9,1,10,10,10,4,4,12,12,4,11,11,11,3,9,9,9,4,10,10,1~12,12,12,2,11,11,4,4,4,10,10,4,4,9,9,3,10,10,1,4,4,10,10,1,11,11,4,4,12,12,4,4,10,10,10,4,4,9,9,9,4,10,10,10,4,4,9,9,2,11,4,4,12,12,4,9,9,4,10,10,4,4,12,11,11,11,4,4,9,9,4,4,12,12,4,4,4,10,10,10,4,4,9,9,2,11,11,1,4,4,10,10,4,11,11,4,4,4,2,12,12,4,4,4,9,9,4,4,10,10,10,4,11,11,1~4,4,12,12,12,4,4,10,10,10,9,9,9,3,12,12,12,9,9,10,10,10,10,4,12,12,4,4,4,11,11,11,10,10,12,12,4,10,10,10,1,12,12,12,4,4,10,10,4,9,9,9,4,4,10,10,10,4,11,11,10,10,10,12,12,4,10,10,10,9,9,9,4,11,11,11,2,10,10,4,11,11,9,9,10,10,4,11,11,11,4,9,9,4,4,12,12,3,11,11,1,10,10,4,11,11,11,4,4,10,10,4,11,11~12,10,10,10,11,11,9,9,9,4,4,11,11,11,4,4,4,10,10,10,3,1,10,10,4,4,12,12,3,3,9,9,9,11,11,11,3,12,12,4,4,10,10,10,3,9,9,4,4,4,12,12,9,9,3,12,12,4,4,10,10,1,9,9,9,3,3,3,10,10,4,9,9,4,4,12,12,12,3,11,11,11,4,4,9,9,3,11,11,11,1,12,12,12,10,10,9,9,9,4,4,11,11,11,3,3,9,9,9,3,10,10,4,12';
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
            else if( $slotEvent['slotEvent'] == 'doSpin' ) 
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

                // $winType = 'win';

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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
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
                }
                
                $wild = '2';
                $scatter = '1';
                $Balance = $slotSettings->GetBalance();
                
                $totalWin = 0;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $str_initReel = '';
                $str_accm = '';
                $str_accv = '';
                $str_ep = '';
                $fsmore = 0;
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_initReel = $stack['init_reel'];
                    $str_accm = $stack['accm'];
                    $str_accv = $stack['accv'];
                    $str_ep = $stack['ep'];
                    $fsmore = $stack['fsmore'];
                    $currentReelSet = $stack['reel_set'];
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
                    $str_initReel = $stack[0]['init_reel'];
                    $str_accm = $stack[0]['accm'];
                    $str_accv = $stack[0]['accv'];
                    $str_ep = $stack[0]['ep'];
                    $fsmore = $stack[0]['fsmore'];
                    $currentReelSet = $stack[0]['reel_set'];
                    $strWinLine = $stack[0]['win_line'];
                }
                $reels = [];
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;

                for($i = 0; $i < 5; $i++){
                    $reels[$i] = [];
                    for($j = 0; $j < 3; $j++){
                        $reels[$i][$j] = $lastReel[$j * 5 + $i];
                        if($lastReel[$j * 5 + $i] == $scatter){
                            $scatterCount++;
                            $scatterPoses[] = $j * 5 + $i;   
                        }
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
                    $muls = [0,0,0,2,20,50];
                    $scatterWin = $betline * $lines * $muls[$scatterCount];
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 12);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                }else if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
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
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $spinType = 's';
                    $isEnd = false;
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                    {
                        $spinType = 'c';
                        $isEnd = true;
                        $strOtherResponse = '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
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
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                    if($scatterCount >= 3 ){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse = '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fs=1&fsres=0.00&psym=1~'. $scatterWin .'~' . implode(',', $scatterPoses);
                    }
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($str_accv != ''){
                    $strOtherResponse = $strOtherResponse . '&accm=' . $str_accm . '&acci=0&accv=' . $str_accv;
                }
                if($str_ep != ''){
                    $strOtherResponse = $strOtherResponse . '&ep=' . $str_ep;
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                }
                
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&c='.$betline.'&sver=5&reel_set='.$currentReelSet.'&counter='. ((int)$slotEvent['counter'] + 1) .'&l=10&s='.$strLastReel.'&w='.$totalWin;
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if( $scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin') 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                }

            }
            if($slotEvent['action'] == 'doSpin' || $slotEvent['action'] == 'doBonus' || $slotEvent['action'] == 'doCollect' || $slotEvent['action'] == 'doCollectBonus' || $slotEvent['action'] == 'doMysteryScatter'){
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

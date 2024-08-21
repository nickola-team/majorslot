<?php 
namespace VanguardLTE\Games\MightofRaPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [7,5,4,3,6,7,7,9,4,3,8,3,6,6,4,9,8,4,4,5,3,7,6,3]);
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
                    $bet = '50.00';
                }
                $spinType = 's';
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 )
                {
                    $strOtherResponse = '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&w=0.00';
                    $strOtherResponse = $strOtherResponse . '&accm=cp&acci=0';
                }
                if($stack != null){
                    $strInitReel = $stack['initReel'];
                    $currentReelSet = $stack['reel_set'];
                    $str_trail = $stack['trail'];
                    $str_stf = $stack['stf'];
                    $str_slm_mp = $stack['slm_mp'];
                    $str_slm_mv = $stack['slm_mv'];
                    $accv = $stack['accv'];
                    $str_slm_lmi = $stack['slm_lmi'];
                    $str_slm_lmv = $stack['slm_lmv'];
                    $fsmore = $stack['fsmore'];

                    if($str_slm_mv != ''){
                        $strOtherResponse = $strOtherResponse . '&slm_mp='. $str_slm_mp .'&slm_mv=' . $str_slm_mv;
                    }
                    if($accv != ''){
                        $strOtherResponse = $strOtherResponse . '&accv=' . $accv;
                    }
                    if($str_trail != ''){
                        $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                    }
                    if($str_stf != ''){
                        $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                    }
                    if($str_slm_lmi != ''){
                        $strOtherResponse = $strOtherResponse . '&slm_lmi=' . $str_slm_lmi;
                    }
                    if($str_slm_lmv != ''){
                        $strOtherResponse = $strOtherResponse . '&slm_lmv=' . $str_slm_lmv;
                    }
                    if($strInitReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $strInitReel;
                    }
                }
                
                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=7,5,4,3,6,7,7,9,4,3,8,3,6,6,4,9,8,4,4,5,3,7,6,3&balance='. $Balance .'&cfgs=5120&ver=2&index=1&balance_cash='. $Balance .'&def_sb=3,8,4,7,3,8&reel_set_size=5&def_sa=7,4,6,3,3,6&reel_set='.$currentReelSet.$strOtherResponse.'&balance_bonus=0.00&na=s&scatters=1~0,0,5,2,0,0~0,0,15,15,0,0~1,1,1,1,1,1&cls_s=-1&gmb=0,0,0&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"1022013",max_rnd_win:"22500"}}&wl_i=tbm~22500&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa=7,4,6,3,3,6&sb=3,8,4,7,3,8&sc='. implode(',', $slotSettings->Bet) .'&defc=50.00&sh=4&wilds=2~500,250,50,25,0,0~1,1,1,1,1,1&bonuses=0&fsbonus=&c='. $bet .'&sver=5&counter=2&paytable=0,0,0,0,0,0;0,0,0,0,0,0;500,250,50,25,0,0;300,200,75,20,0,0;250,150,40,15,0,0;200,100,25,10,0,0;150,75,15,7,0,0;100,50,10,5,0,0;75,30,6,3,0,0;75,30,6,3,0,0;50,20,5,2,0,0;50,20,5,2,0,0;0,0,0,0,0,0&l=20&reel_set0=10,4,11,4,4,6,4,7,5,9,10,9,7,10,10,4,5,10,2,3,9,10,5,2,2,2,2,9,8,9,3,5,11,6,7,8,9,5,9,11,5,3,6,9,7,5,4,6,10,7,4,3,3,3,3,4,6,9,4,6,3,11,9,9,5,6,4,6,5,9,4,7,7,5,7,10,5,5,7,5,6,6,6,6,7,3,10,3,4,11,7,7,3,6,7,4,10,11,7,4,9,7,3,5,5,3,8,3,4,4,4,4,6,5,4,3,10,5,3,10,11,11,4,10,7,4,10,6,4,6,3,5,10,11,4,11,5,5,5,5,7,3,4,4,6,6,9,11,6,10,11,7,2,11,4,9,6,5,7,3,7,7,11,3,3,7,7,7,11,5,5,3,11,9,3,3,11,9,10,4,4,6,6,3,5,4,11,10,9,4,10,7,10,10,10,6,9,7,9,5,3,3,5,7,6,9,10,6,5,2,11,4,4,5,9,10,10,6,3,11,11,11,3,9,5,11,3,6,4,11,3,11,6,6,3,10,11,7,7,6,6,4,3,6,7,5,3,7~9,11,10,10,1,11,10,5,9,6,8,10,6,11,2,2,2,2,3,9,8,6,11,5,5,9,6,8,9,11,11,10,10,2,5,5,5,5,8,11,11,6,8,9,5,5,9,11,5,9,5,6,10,3,3,3,3,9,7,4,4,10,8,9,8,6,9,6,5,7,5,11,11,9,9,9,2,9,10,11,3,6,11,6,11,5,9,8,6,6,10,10,4,4,4,4,8,9,10,5,4,6,10,5,8,10,5,10,9,6,8,6,6,6,6,9,6,10,6,9,6,11,3,11,2,8,8,9,2,8,10,8,8,8,9,5,5,8,10,5,10,8,8,5,9,11,6,8,5,11,11,11,10,11,5,6,6,9,10,11,11,10,8,11,4,8,6,8,10,10,10,7,7,1,9,5,6,9,6,11,10,5,11,3,8,5,5,8~10,8,8,7,5,1,10,7,4,8,8,1,11,11,8,11,3,11,2,2,2,2,8,6,9,8,8,9,8,7,10,5,7,4,8,9,9,11,6,10,7,6,6,6,6,2,11,9,11,8,10,10,11,9,8,10,9,6,11,8,11,11,8,8,9,8,8,8,2,5,3,8,9,9,11,7,11,9,7,10,11,9,8,7,8,10,11,11,11,9,11,8,8,11,11,10,10,5,9,10,7,6,3,10,9,5,2,9,10,10,10,7,4,11,7,7,11,9,10,9,7,3,9,11,4,11,4,2,7,9,10,3,3,3,3,7,7,9,10,11,8,7,8,9,11,7,11,3,9,3,5,9,7,7,7,11,8,7,7,10,10,8,7,10,8,11,9,11,8,7,9,11,10,9,9,9,7,10,8,11,9,7,10,10,9,9,5,6,11,10,9,10,11,10,9,6,4,4,4,4,8,7,7,11,8,7,3,7,4,8,8,2,4,7,7,11,9,8,2,5,5,5,5,10,10,8,1,10,10,9,11,7,10,10,8,10,7,9,2,10,7,10,6,11~4,3,7,8,8,1,4,4,8,3,3,2,3,7,3,8,3,3,2,2,2,2,4,11,7,4,10,8,2,5,8,4,1,7,3,4,5,10,7,8,7,8,3,3,3,3,8,7,7,8,4,4,7,8,7,7,3,8,9,7,4,8,3,8,1,6,4,4,4,4,3,7,3,3,8,7,4,6,3,9,8,2,2,4,6,3,4,11,9,4,7,7,7,8,7,7,4,4,8,11,7,10,3,4,10,8,7,4,3,4,4,1,4,5,5,5,5,3,2,7,3,5,8,6,8,9,3,7,7,4,8,8,5,4,3,11,3,6,6,6,6,7,4,5,4,3,11,7,3,4,1,6,3,10,7,3,8,8,3,9,4,7~10,8,3,8,5,4,9,11,10,9,5,11,9,6,3,6,2,2,2,2,5,8,1,5,4,6,2,5,9,11,5,6,5,6,5,9,11,3,3,3,3,7,10,10,5,11,11,10,8,3,6,4,9,7,11,10,5,6,6,6,6,9,6,5,3,6,9,4,3,8,4,10,9,3,7,6,9,3,4,4,4,4,11,6,5,9,10,9,8,5,10,3,5,4,7,9,5,9,4,5,5,5,5,1,10,3,1,11,2,4,3,6,10,5,3,4,9,4,7,10,4,10,10,10,10,3,8,11,6,9,11,5,4,10,11,6,11,2,11,1,6,11,11,11,11,4,3,10,3,11,5,10,9,3,11,10,3,6,4,11,4,6,9,9,9,7,4,3,7,4,4,9,6,5,2,1,4,11,11,3,3,10,9,10~2,7,4,7,8,6,5,4,5,6,7,6,7,6,7,5,11,5,6,10,8,3,2,2,2,2,8,5,3,6,7,7,6,8,9,8,7,6,4,3,7,9,5,8,5,6,11,5,9,3,3,3,3,7,6,8,7,3,8,5,5,8,7,3,11,6,5,5,8,5,7,6,8,11,4,5,6,6,6,6,8,8,6,7,8,8,5,7,7,6,8,6,8,5,11,5,7,5,4,8,3,8,7,6,5,5,5,5,2,8,8,7,6,7,6,8,8,6,5,6,7,5,5,8,5,6,7,7,5,2,6,7,7,7,10,7,8,5,8,5,4,9,9,4,6,8,7,5,4,8,8,4,10,8,5,7,7,4,4,4,4,5,7,5,8,6,7,2,2,6,5,7,6,6,7,2,5,7,7,5,10,7,6,10,8,8,8,5,8,6,4,7,8,7,9,8,6,4,6,6,11,8,6,4,6,6,5,5,6,8,10,5&s='.$lastReelStr.'&accInit=[{id:0,mask:"cp;mp"}]&reel_set2=10,3,7,6,7,4,4,11,4,3,2,10,7,4,4,3,6,11,10,9,5,3,9,4,7,8,7,3,9,12,3,3,11,5,12,11,2,2,2,2,6,9,3,7,9,5,7,9,5,10,4,7,6,4,7,3,9,4,10,4,6,2,6,6,7,10,6,4,11,6,9,5,5,12,9,11,5,5,5,5,12,9,5,5,9,5,2,3,6,4,6,5,3,3,6,5,2,4,11,4,10,6,7,5,5,10,11,2,5,10,3,9,4,11,6,7,5,4,4,4,4,7,5,9,6,11,7,5,10,11,10,7,3,8,12,6,9,11,6,2,10,2,5,7,6,11,6,4,5,11,3,10,7,9,10,11,9,10,6,6,6,6,7,3,11,5,11,3,9,3,10,11,7,11,9,10,3,11,3,2,12,4,3,3,10,11,5,3,4,10,9,6,11,10,7,5,6,10,5,3,3,3,3,12,6,6,10,5,9,4,9,4,6,9,4,7,11,3,8,3,9,2,4,10,11,7,11,9,4,2,7,2,7,5,11,3,6,2,10,5~11,8,9,8,10,9,2,6,8,6,8,11,9,6,9,2,8,5,6,9,2,9,11,7,5,9,5,6,5,9,2,11,2,2,2,2,10,5,10,5,8,8,9,2,9,11,6,2,8,10,3,8,8,11,8,10,6,10,9,10,11,9,6,6,1,8,6,3,3,3,3,8,10,2,11,5,1,6,9,6,5,6,3,6,5,5,6,11,2,10,11,9,10,3,9,10,5,6,11,6,12,11,5,2,4,4,4,4,9,10,1,5,11,12,10,5,11,9,8,2,5,9,5,4,5,6,11,5,11,8,7,9,6,6,4,8,10,7,1,9,5,5,5,5,8,10,5,8,5,9,11,4,7,8,11,8,6,5,8,11,10,8,6,2,8,7,10,6,10,5,6,9,8,6,6,6,6,11,5,5,2,11,2,1,8,5,8,11,12,9,11,10,6,5,12,9,5,8,6,12,6,11,8,9,10,9,5,10,8,11,5~7,9,10,7,5,8,2,9,11,7,4,7,11,5,11,10,8,9,7,9,1,3,10,9,8,7,11,10,2,2,2,2,5,2,8,4,3,10,9,10,8,11,3,7,8,2,11,12,10,9,6,10,9,7,8,11,10,3,3,3,3,7,2,11,9,11,8,11,9,8,7,4,10,1,11,12,10,9,8,11,8,11,12,1,9,8,2,10,8,4,4,4,4,11,7,8,7,9,11,2,10,8,11,8,9,7,11,10,8,11,12,3,8,10,7,11,9,2,2,5,5,5,5,7,5,7,6,7,10,7,10,8,11,7,11,9,1,9,11,10,7,8,7,9,2,10,2,10,6,6,6,6,7,11,12,10,6,4,7,8,10,12,11,9,2,9,10,11,2,11,7,8,2,6,11,9,10,8,10,8,7~4,7,12,3,11,4,3,7,8,7,4,2,9,8,6,11,3,2,2,2,2,4,1,6,4,2,7,4,3,3,8,7,8,4,3,7,8,5,7,4,7,5,5,5,5,10,3,4,2,7,8,4,3,7,11,8,3,7,4,3,4,7,4,4,3,3,3,3,2,4,7,3,2,4,9,8,4,1,7,2,3,8,3,4,12,8,10,3,6,6,6,6,1,7,2,3,3,4,9,2,8,7,5,8,7,12,7,3,8,6,4,4,4,4,2,3,8,4,2,10,4,7,3,8,4,1,8,12,3,3,11,5,7~10,9,6,10,12,11,9,10,4,3,4,7,2,6,5,4,11,6,4,6,6,10,11,3,11,5,6,3,2,2,2,2,12,4,10,11,5,9,5,2,12,7,2,6,10,5,5,9,5,9,3,3,8,10,5,7,5,4,6,12,3,9,3,3,3,3,10,2,4,9,5,6,9,3,3,11,9,6,5,10,6,2,4,3,6,11,6,3,9,3,3,4,9,10,4,4,4,4,9,4,2,4,5,11,6,12,11,5,6,10,4,8,3,4,5,4,4,10,4,3,3,5,3,5,10,4,3,5,5,5,5,6,9,3,5,2,4,9,5,5,11,4,4,5,11,10,4,11,6,11,11,9,6,8,9,1,9,3,10,5,6,6,6,6,11,9,3,6,4,12,11,6,10,9,11,8,3,2,11,9,6,10,5,5,11,2,1,4,11,7,10,9,6,11,2~2,2,7,6,2,5,6,7,6,8,6,7,6,5,6,4,5,7,5,2,2,2,2,8,2,11,8,5,7,6,8,2,12,7,5,6,11,5,2,5,8,7,4,4,4,4,6,7,8,7,5,6,6,8,7,8,2,6,6,7,6,6,7,3,3,3,3,7,5,5,8,2,9,11,9,3,8,5,8,6,2,12,4,5,6,12,5,5,5,5,5,8,6,5,7,2,8,5,5,10,8,10,7,5,7,4,4,2,12,3,6,6,6,6,9,7,8,5,6,8,6,10,8,5,7,6,6,8,3,8,10,5,5,7,5,5&reel_set1=11,9,11,6,11,5,4,4,11,9,11,9,2,11,10,3,7,9,5,7,5,11,9,4,5,6,3,7,9,3,3,6,12,7,11,5,2,4,11,9,10,11,2,2,2,2,4,10,3,9,2,10,7,9,7,11,6,4,3,3,4,10,7,3,10,9,7,5,3,4,6,3,11,7,9,6,12,4,5,5,10,7,6,3,2,5,2,6,10,5,5,5,5,9,7,10,6,9,6,7,6,10,12,6,3,2,7,4,9,7,5,5,4,6,7,3,10,9,7,6,11,10,5,2,11,10,7,11,9,3,3,4,6,12,9,4,4,4,4,12,6,3,5,4,11,10,6,11,6,9,11,9,5,2,4,7,4,5,11,3,6,11,3,7,5,12,5,11,6,4,2,5,9,10,6,7,8,3,6,10,3,6,6,6,6,7,11,5,3,10,9,5,5,4,5,10,11,3,5,11,9,6,4,11,4,5,11,4,5,10,3,7,10,6,9,7,5,9,4,10,9,8,10,9,6,3,3,3,3,11,10,4,11,8,7,5,11,10,6,4,3,10,3,4,3,7,6,4,2,9,3,6,2,5,5,4,4,3,11,10,7,3,10,7,6,7,4,3,10,7,5,6~8,5,11,10,8,8,9,10,9,8,9,10,6,10,2,11,9,2,6,2,8,3,8,5,6,10,9,11,2,5,5,8,5,10,11,8,10,9,5,10,11,10,6,2,2,2,2,8,11,7,5,8,10,5,9,11,8,8,9,7,4,6,11,9,8,11,2,8,5,6,9,12,6,6,8,5,6,8,10,6,8,8,5,6,5,12,8,11,6,10,2,9,11,10,5,3,3,3,3,10,2,9,5,12,8,9,5,9,6,11,5,6,10,5,6,8,5,7,6,9,6,2,9,10,12,10,9,6,1,8,10,9,8,7,11,5,5,6,2,2,9,6,11,4,4,4,4,10,12,8,8,11,8,9,5,6,9,6,5,5,6,9,5,10,11,9,10,11,12,11,10,7,2,11,1,6,8,8,10,11,10,8,10,6,6,9,8,10,9,5,9,10,9,5,5,5,5,11,8,5,6,11,1,11,9,8,6,10,11,8,6,11,9,11,8,1,11,8,11,5,4,10,8,11,2,6,7,8,10,9,11,5,6,10,8,11,1,11,10,9,6,9,10,6,6,6,6,3,12,4,5,2,11,9,6,5,10,5,6,5,10,5,11,7,11,10,9,6,10,8,11,5,1,4,5,6,5,6,8,6,6,2,6,5,11,3,3,8,11,2,5,5,9,8,5,8~11,9,11,5,9,10,7,7,10,4,7,11,10,9,7,10,7,7,10,5,8,2,2,2,2,10,2,7,10,7,8,7,11,7,8,10,9,6,9,7,11,9,8,3,2,11,8,10,7,3,3,3,3,11,10,3,8,11,8,10,2,10,9,12,2,3,2,8,10,8,10,8,11,10,11,10,11,9,7,11,2,4,4,4,4,8,9,7,7,6,11,5,7,7,2,10,7,8,9,10,7,1,11,2,9,1,6,7,11,8,5,5,5,5,12,11,9,10,11,7,11,6,7,2,8,7,7,1,9,8,5,7,10,7,9,11,10,3,1,6,6,6,6,9,11,10,4,8,12,11,9,8,12,10,4,10,8,9,12,8,1,4,7,8,9,11,8,10,8~3,4,4,8,12,7,8,7,12,8,2,3,7,8,7,4,4,3,4,7,9,4,8,7,2,2,2,2,3,4,8,3,2,8,7,2,6,4,5,8,3,2,6,4,7,8,5,4,7,3,1,5,5,5,5,3,3,8,10,7,8,4,3,2,3,7,11,6,8,9,8,3,3,7,8,4,8,11,3,3,3,3,12,3,3,1,7,2,1,9,2,3,1,7,4,4,3,3,4,10,8,3,2,8,9,7,3,4,8,6,6,6,6,7,4,8,2,7,3,3,4,7,10,4,7,4,2,7,12,4,12,8,7,4,8,3,11,2,4,4,4,4,3,3,10,4,8,6,4,7,1,3,3,4,4,10,2,7,3,5,5,3,7,4,4,8,11,3~6,2,6,5,6,11,6,3,2,6,9,5,8,6,10,2,11,3,4,10,11,3,4,4,5,4,6,4,10,9,4,2,2,2,2,11,5,10,11,6,9,12,10,6,3,11,6,5,4,3,7,5,4,4,11,10,9,5,12,3,5,3,11,3,10,6,6,3,3,3,3,4,10,4,6,3,10,4,1,4,11,9,9,12,9,10,3,12,3,10,7,6,5,10,6,11,9,3,5,6,1,9,10,4,4,4,4,9,3,3,1,9,8,6,4,10,11,8,11,2,3,11,6,5,3,7,1,2,10,5,10,3,11,2,11,3,8,9,11,5,5,5,5,9,6,5,3,6,11,6,9,11,4,3,4,4,9,4,4,9,11,5,11,11,10,5,4,2,11,2,2,5,6,4,3,9,6,6,6,6,7,10,5,3,6,5,9,6,5,5,4,9,5,4,9,4,11,5,11,10,11,9,1,3,7,5,3,9,4,3,9,12,6,10~8,7,8,2,2,5,6,8,6,8,5,10,6,10,5,7,6,10,7,6,12,4,8,7,11,6,7,5,4,7,6,2,2,2,2,6,5,4,4,7,5,8,5,7,11,2,9,5,6,8,11,9,4,12,5,4,7,8,5,7,9,3,12,5,6,8,6,7,4,4,4,4,6,12,8,6,5,11,8,9,5,7,5,7,6,6,8,7,10,5,6,7,2,5,8,5,6,5,2,8,5,8,2,3,3,3,3,7,2,5,2,5,8,6,5,9,8,5,3,5,6,5,3,6,8,5,12,6,7,5,11,6,8,6,7,2,6,2,7,5,5,5,5,5,11,6,8,7,6,9,7,12,6,6,5,4,5,6,7,8,6,2,7,3,8,7,8,7,8,6,6,5,8,6,6,6,6,7,8,7,3,8,6,5,6,8,7,5,7,6,5,11,7,5,7,6,6,8,9,8,12,5,2,7,5,8,5,8,10,7,2,6&reel_set4=10,2,11,9,3,10,9,11,2,3,2,4,6,3,5,11,4,7,5,9,12,6,3,11,4,12,7,5,6,12,10,4,6,9,11,7,6,3,3,2,2,2,2,4,10,9,10,5,8,11,4,5,5,2,10,3,5,12,7,10,9,10,9,11,4,7,9,6,9,2,3,7,10,3,11,3,4,8,4,7,6,5,5,5,5,7,2,5,6,11,2,11,5,4,5,11,6,9,11,3,9,4,4,10,7,4,5,11,2,9,11,9,3,10,9,7,5,9,2,5,7,3,10,7,3,6,4,4,4,4,3,6,5,10,6,5,11,4,11,5,6,11,10,3,7,10,6,9,11,12,3,4,7,2,6,11,5,4,3,9,3,2,7,5,2,6,5,6,4,3,6,6,6,6,2,5,4,11,6,2,6,2,3,5,10,2,6,11,7,8,3,9,4,4,11,5,5,4,3,4,10,6,10,7,11,5,6,9,4,11,7,4,2,4,3,3,3,3,10,2,2,6,7,3,5,10,4,6,3,12,9,7,6,2,7,9,7,10,7,9,10,3,9,11,3,4,10,5,4,2,7,6,6,10,11,7,10,3,7,6~8,2,11,4,4,11,8,6,5,2,6,8,9,2,9,6,2,5,11,10,5,7,3,1,8,6,6,5,9,10,11,6,6,10,2,1,10,2,2,2,2,8,5,8,6,8,11,2,9,5,5,6,10,2,5,10,8,11,8,11,6,2,9,10,11,10,11,1,9,7,9,5,3,2,6,10,4,9,5,3,3,3,3,6,9,6,9,5,5,8,10,2,8,10,5,8,9,11,9,6,8,7,11,8,5,6,9,12,2,7,11,6,8,11,7,8,11,10,2,11,10,4,4,4,4,2,11,2,6,9,5,11,6,5,8,6,9,10,11,5,8,9,10,2,6,11,5,10,9,12,7,10,8,11,9,7,2,2,11,10,8,5,5,9,5,5,5,5,9,8,5,2,5,9,10,2,6,8,11,1,5,6,3,9,10,6,10,9,8,11,6,2,9,3,8,6,3,11,6,10,11,5,8,10,8,11,6,6,6,6,5,11,2,5,8,9,8,10,2,5,5,6,6,2,5,5,9,8,6,6,8,5,6,9,12,2,6,8,2,12,10,6,10,8,1,11,5,5,11~3,10,8,11,9,11,10,6,2,11,8,12,7,9,10,7,12,9,11,8,10,7,11,5,8,7,9,2,11,8,2,11,7,8,11,2,2,2,2,7,10,2,9,10,12,7,11,7,8,11,2,1,3,7,10,8,10,11,4,6,9,8,2,10,8,10,8,10,2,9,4,11,5,8,3,3,3,3,10,11,9,2,10,2,7,10,11,9,4,4,7,2,2,7,11,7,4,3,2,9,7,2,8,9,11,5,2,7,2,11,9,10,11,7,2,4,4,4,4,3,8,11,10,4,7,11,10,8,2,9,2,9,7,2,9,1,9,10,11,7,12,8,7,2,9,10,4,5,7,8,6,11,5,5,5,5,10,9,8,7,12,9,10,9,10,9,11,9,10,8,7,8,10,7,9,11,9,2,10,11,9,4,11,2,11,8,11,10,3,6,6,6,6,8,2,7,6,8,9,10,9,11,8,10,8,11,8,10,9,7,9,2,2,10,1,9,7,6,7,2,7,2,5,8,7,1,7~8,7,2,4,4,3,11,7,3,4,4,1,3,6,10,1,7,8,7,2,2,2,2,8,3,4,4,3,2,2,4,3,8,1,3,2,4,2,4,8,10,7,4,5,5,5,5,9,8,3,7,12,4,3,8,3,4,7,3,5,8,11,4,3,4,11,3,3,3,3,2,2,3,4,2,3,8,7,8,3,4,4,2,1,7,4,8,7,2,6,6,6,6,9,7,8,3,7,2,3,3,10,7,8,6,5,6,4,8,7,4,7,2,4,4,4,4,7,5,2,7,4,12,2,3,4,2,3,2,7,2,8,7,12,3,3,9~4,2,7,9,10,9,8,11,5,9,11,3,4,7,5,9,12,11,4,10,5,8,10,3,10,3,9,2,6,11,5,10,6,5,4,11,11,2,2,2,2,4,4,10,3,9,4,5,3,9,8,6,4,1,6,5,5,2,10,4,1,5,10,9,6,5,2,4,4,6,10,4,4,3,5,4,12,6,2,7,3,3,3,3,5,11,9,6,10,4,10,4,5,6,11,9,4,2,6,11,3,10,12,9,10,6,11,4,9,10,7,10,8,10,12,9,6,11,1,9,4,4,4,4,6,5,4,8,6,2,3,5,7,6,3,9,4,2,11,2,11,11,3,3,10,6,6,10,11,8,10,11,3,5,6,9,6,5,7,4,7,3,5,5,5,5,4,11,3,11,3,3,9,11,6,11,3,9,3,2,5,5,3,6,9,10,5,10,3,1,9,2,4,4,2,8,9,4,4,11,8,4,5,4,6,6,6,6,3,2,5,2,5,6,3,9,2,6,2,12,9,6,5,11,9,2,3,3,9,5,10,2,6,2,11,1,4,5,10,6,3,2,11,6,6,11,5,6~6,7,6,8,7,5,7,5,8,6,7,5,7,2,5,11,6,4,9,2,6,12,8,5,2,11,8,2,7,6,12,8,7,2,2,2,2,6,2,3,6,4,8,2,5,6,5,9,7,2,5,5,7,6,5,6,5,5,6,6,7,2,8,4,7,8,5,7,6,6,8,7,4,4,4,4,8,6,5,8,6,8,5,10,5,2,9,5,2,4,7,4,6,2,8,5,2,5,8,5,7,8,9,8,6,5,6,5,2,7,2,12,3,3,3,3,2,6,2,8,5,5,6,11,5,10,6,7,2,7,8,7,10,2,5,8,5,7,3,6,8,7,5,2,7,2,6,9,6,8,5,5,5,5,5,2,4,6,7,8,3,8,9,6,5,2,11,8,5,3,8,7,12,3,5,8,5,10,5,12,2,7,6,7,6,7,8,12,2,6,6,6,6,2,7,5,8,7,2,7,6,7,6,7,4,8,11,6,2,9,8,5,6,2,7,4,10,5,4,6,6,7,6,8,5,5,6,2,5,12&reel_set3=11,10,6,9,5,6,4,5,10,12,7,5,7,9,12,10,11,3,5,9,3,4,7,9,5,3,10,6,4,6,3,10,3,9,6,6,9,5,10,7,2,10,7,3,10,11,6,4,2,2,2,2,8,5,3,6,6,2,5,9,4,2,9,10,6,11,4,11,10,11,5,4,7,9,2,7,3,5,9,12,3,4,12,10,7,6,10,3,7,2,5,4,9,6,10,5,2,7,11,3,6,11,5,5,5,5,10,11,6,10,11,9,6,3,5,9,4,11,12,11,5,10,4,6,4,3,9,2,6,3,6,3,5,10,4,7,11,4,10,7,2,9,10,11,4,3,7,9,6,11,7,4,9,3,3,8,9,3,4,4,4,4,6,3,7,4,9,5,9,4,4,9,3,11,12,2,11,3,4,4,7,10,3,5,9,4,5,2,4,7,3,11,10,6,5,7,9,3,11,12,2,7,10,11,9,2,12,10,3,9,11,2,11,6,6,6,6,7,2,10,12,7,4,6,6,3,6,6,7,3,10,6,9,11,4,10,3,4,7,5,3,3,10,5,5,3,11,2,3,7,9,11,4,11,10,3,11,7,5,8,5,5,6,6,2,12,5,6,6,3,3,3,3,2,9,5,11,7,3,6,4,11,2,10,4,5,3,11,2,2,6,6,9,5,7,3,5,10,6,7,3,7,5,11,10,4,4,2,9,4,7,6,5,6,4,5,8,11,5,6,10,11,4,6,7,11,9~8,10,1,8,9,8,5,9,11,6,11,5,5,11,9,11,6,1,6,9,6,11,3,10,11,5,9,5,11,4,5,4,5,6,7,6,2,9,2,2,2,2,3,7,9,5,2,5,2,6,2,5,7,5,5,11,5,11,10,6,8,11,8,6,6,5,2,8,7,9,5,6,8,5,1,6,2,11,9,10,3,3,3,3,8,9,12,6,11,8,5,9,6,7,2,9,5,6,2,10,1,9,10,6,11,7,6,11,2,9,12,8,11,2,9,5,6,2,11,8,11,5,10,4,4,4,4,10,12,6,8,9,8,10,8,11,9,5,3,6,10,5,8,5,9,2,6,11,9,8,12,10,11,6,4,9,10,6,9,2,10,6,6,5,10,5,5,5,5,6,12,11,5,11,9,10,3,10,8,2,2,6,8,10,12,8,11,10,11,5,8,6,8,2,7,9,10,6,5,10,9,5,3,8,9,6,6,6,6,7,6,12,8,11,6,11,5,11,8,5,2,9,2,11,9,5,10,8,11,10,8,11,1,9,8,9,5,10,8,6,6,5,6,1,5,9~8,7,9,10,9,2,11,8,9,10,9,7,5,9,11,10,7,9,10,2,10,2,8,11,10,7,4,2,11,7,8,5,3,2,2,8,11,2,9,2,2,2,2,8,7,8,2,7,10,11,7,8,10,11,5,10,9,10,7,8,7,6,8,11,2,9,11,7,8,10,7,9,7,11,9,8,9,8,9,4,11,3,3,3,3,9,3,7,11,7,9,10,2,1,2,11,8,11,3,10,7,4,1,12,8,7,8,9,10,8,12,11,10,11,8,7,8,10,11,9,10,11,4,4,4,4,8,9,2,7,10,9,11,10,2,7,2,11,6,9,5,1,9,10,11,2,7,10,7,6,12,11,7,4,12,8,10,9,10,9,8,11,10,12,8,5,5,5,5,2,11,10,11,5,10,9,2,7,10,5,9,7,10,4,3,7,8,7,9,11,7,9,11,10,9,12,10,9,11,8,9,6,7,11,2,2,7,8,6,6,6,6,9,10,1,7,10,8,3,7,11,8,9,7,3,8,10,1,8,9,7,8,10,11,8,1,2,9,7,9,10,9,4,11,12,7,6,10,2,6,7,10,7~2,5,9,3,9,3,6,3,3,6,8,3,3,8,4,4,2,4,7,4,12,3,4,3,4,4,12,6,7,2,2,2,2,8,4,7,10,7,5,11,3,2,4,8,7,8,11,7,8,4,10,4,8,11,1,4,2,3,9,8,7,5,5,5,5,3,11,4,3,4,7,3,4,7,2,8,4,4,12,3,7,10,8,7,8,7,8,7,3,12,8,3,2,4,3,3,3,3,7,4,3,7,3,3,9,3,3,4,1,8,4,4,3,8,7,2,2,7,4,7,8,4,3,1,2,7,6,6,6,6,11,4,7,3,7,8,7,5,4,2,2,4,7,2,4,3,8,2,8,3,4,10,2,7,8,1,4,2,4,4,4,4,7,8,9,4,1,3,12,4,4,8,3,11,4,3,12,1,9,3,5,4,3,7,2,8,2,6,2,3,8,2,7~6,11,11,3,10,9,12,3,4,5,11,5,6,4,9,6,2,6,10,2,6,3,10,5,4,5,11,5,10,3,11,7,10,3,6,10,5,12,5,11,11,2,10,6,8,3,1,9,10,3,10,2,2,2,2,5,5,9,12,11,7,10,9,5,10,3,6,9,9,8,1,4,2,11,6,11,2,6,3,4,4,11,6,10,5,11,10,4,8,9,11,4,6,3,6,4,4,10,5,3,10,12,4,3,12,11,3,3,3,3,9,5,10,5,9,1,11,6,7,9,4,5,9,10,9,5,5,9,4,12,10,6,7,10,7,2,4,7,12,2,9,8,6,1,4,3,3,10,9,9,4,4,10,2,11,8,3,9,8,3,3,4,4,4,4,5,11,6,10,5,6,12,5,3,4,9,7,4,6,5,6,9,11,9,5,4,11,3,3,4,3,6,11,7,2,6,11,6,3,11,10,6,5,4,11,11,9,10,9,3,9,9,11,2,12,1,5,11,5,5,5,5,7,10,4,6,1,2,11,2,11,9,10,5,4,7,5,3,5,10,6,5,8,10,11,4,6,5,2,11,4,9,4,1,6,9,11,2,9,11,10,5,6,3,10,9,3,5,2,3,3,11,5,6,6,6,6,4,4,10,9,2,5,8,3,4,4,3,11,4,6,4,4,5,11,9,6,6,4,11,6,9,3,6,5,6,3,4,10,8,4,9,3,2,3,3,6,2,9,9,5,10,4,2,2,6,3,6,6,4~5,6,12,6,6,5,5,7,2,8,4,7,2,7,2,4,6,8,5,3,8,10,8,7,6,8,2,2,2,2,8,11,8,3,12,6,6,12,7,6,5,7,11,10,6,5,7,8,6,8,12,2,8,7,2,5,4,12,5,7,4,4,4,4,2,10,5,6,5,7,8,5,6,9,5,8,2,8,2,6,5,8,7,2,12,5,2,5,12,6,6,7,6,3,3,3,3,6,7,9,7,2,8,5,7,5,2,8,7,8,7,5,8,6,7,5,5,2,8,5,2,6,11,8,7,6,9,5,5,5,5,5,4,2,11,5,7,6,7,8,5,6,6,5,4,7,6,10,12,3,5,5,6,6,7,5,8,7,2,8,6,6,6,6,9,7,8,6,6,3,5,7,8,5,7,6,6,12,2,7,2,7,4,8,12,9,5,2,5,5,8,5,8,9';
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
            else if( $slotEvent['slotEvent'] == 'doSpin' ) 
            {
                $lastEvent = $slotSettings->GetHistory();
                $linesId = [];
                $linesId[0] = [1,1,1,1,1,1];
                $linesId[1] = [2,2,2,2,2,2];
                $linesId[2] = [3,3,3,3,3,3];
                $linesId[3] = [4,4,4,4,4,4];
                $linesId[4] = [3,3,2,3,2,3];
                $linesId[5] = [1,2,1,2,1,2];
                $linesId[6] = [2,3,2,3,2,3];
                $linesId[7] = [3,4,3,4,3,4];
                $linesId[8] = [2,1,2,1,2,1];
                $linesId[9] = [3,2,3,2,3,2];
                $linesId[10] = [4,3,4,3,4,3];
                $linesId[11] = [1,2,3,4,3,2];
                $linesId[12] = [4,3,2,1,2,3];
                $linesId[13] = [2,3,4,3,2,1];
                $linesId[14] = [3,4,3,2,1,2];
                $linesId[15] = [2,1,1,1,1,2];
                $linesId[16] = [3,2,2,2,2,3];
                $linesId[17] = [4,3,3,3,3,4];
                $linesId[18] = [1,2,2,2,2,1];
                $linesId[19] = [2,3,3,3,3,2];
                $linesId[20] = [3,4,4,4,4,3];
                $linesId[21] = [1,1,4,4,1,1];
                $linesId[22] = [1,1,3,3,1,1];
                $linesId[23] = [1,1,2,2,1,1];
                $linesId[24] = [2,2,1,1,2,2];
                $linesId[25] = [2,2,3,3,2,2];
                $linesId[26] = [2,2,4,4,2,2];
                $linesId[27] = [3,3,1,1,3,3];
                $linesId[28] = [3,3,2,2,3,3];
                $linesId[29] = [3,3,4,4,3,3];
                $linesId[30] = [1,3,1,3,1,3];
                $linesId[31] = [2,4,2,4,2,4];
                $linesId[32] = [3,1,3,1,3,1];
                $linesId[33] = [4,2,4,2,4,2];
                $linesId[34] = [1,4,1,4,1,4];
                $linesId[35] = [4,1,4,1,4,1];
                $linesId[36] = [4,1,1,1,1,4];
                $linesId[37] = [1,4,4,4,4,1];
                $linesId[38] = [1,3,3,3,3,1];
                $linesId[39] = [2,4,4,4,4,2];
                $linesId[40] = [4,2,2,2,2,4];
                $linesId[41] = [3,1,1,1,1,3];
                $linesId[42] = [3,2,1,1,2,3];
                $linesId[43] = [2,3,4,4,3,2];
                $linesId[44] = [2,2,3,4,3,2];
                $linesId[45] = [3,3,2,1,2,3];
                $linesId[46] = [4,4,3,3,4,4];
                $linesId[47] = [4,4,2,2,4,4];
                $linesId[48] = [4,4,1,1,4,4];
                $linesId[49] = [2,2,3,2,3,2];
                $slotEvent['slotBet'] = $slotEvent['c'];
                $slotEvent['slotLines'] = 20;
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
                
                // $winType = 'bonus';

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
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '4174458' . substr($roundstr, 4, 10);
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
                $strInitReel = '';
                $str_trail = '';
                $str_stf = '';
                $str_slm_lmi = '';
                $str_slm_lmv = '';
                $arr_slm_mp = [];
                $arr_slm_mv = [];
                $accv = '';
                $fsmore = -1;
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $strInitReel = $stack['initReel'];
                    $currentReelSet = $stack['reel_set'];
                    $str_trail = $stack['trail'];
                    $str_stf = $stack['stf'];
                    if($stack['slm_mp'] != ''){
                        $arr_slm_mp = explode(',', $stack['slm_mp']);
                    }
                    if($stack['slm_mv'] != ''){
                        $arr_slm_mv = explode(',', $stack['slm_mv']);
                    }
                    $accv = $stack['accv'];
                    $str_slm_lmi = $stack['slm_lmi'];
                    $str_slm_lmv = $stack['slm_lmv'];
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
                    $strInitReel = $stack[0]['initReel'];
                    $currentReelSet = $stack[0]['reel_set'];
                    $str_trail = $stack[0]['trail'];
                    $str_stf = $stack[0]['stf'];
                    if($stack[0]['slm_mp'] != ''){
                        $arr_slm_mp = explode(',', $stack[0]['slm_mp']);
                    }
                    if($stack[0]['slm_mv'] != ''){
                        $arr_slm_mv = explode(',', $stack[0]['slm_mv']);
                    }
                    $str_slm_lmi = $stack[0]['slm_lmi'];
                    $str_slm_lmv = $stack[0]['slm_lmv'];
                    $accv = $stack[0]['accv'];
                    $fsmore = $stack[0]['fsmore'];
                }
                $reels = [];
                $scatterCount = 0;
                $scatterWin = 0;
                $scatterPoses = [];
                $wildReel = [];
                for($i = 0; $i < 6; $i++){
                    $reels[$i] = [];
                    $wildReel[$i] = [];
                    for($j = 0; $j < 4; $j++){
                        $reels[$i][$j] = $lastReel[$j * 6 + $i];
                        $wildReel[$i][$j] = 0;
                        if($reels[$i][$j] == $scatter){
                            $scatterCount++;
                            $scatterPoses[] = $j * 6 + $i;
                        }
                    }
                }
                if(count($arr_slm_mp) > 0){
                    for($i = 0; $i < count($arr_slm_mp); $i++){
                        $wildReel[$arr_slm_mp[$i] % 6][floor($arr_slm_mp[$i] / 6)] = $arr_slm_mv[$i];
                    }
                }
                
                $_lineWinNumber = 1;
                $_obf_winCount = 0;
                for( $k = 0; $k < 50; $k++ ) 
                {
                    $_lineWin = '';
                    $firstEle = $reels[0][$linesId[$k][0] - 1];
                    $lineWinNum[$k] = 1;
                    $lineWins[$k] = 0;
                    $wildWin = 0;
                    $wildWinNum = 1;
                    $mul = 1;
                    if($firstEle == $wild){
                        if($wildReel[0][$linesId[$k][0] - 1] > 0){
                            $mul = $wildReel[0][$linesId[$k][0] - 1];
                        }
                    }
                    for($j = 1; $j < 6; $j++){
                        $ele = $reels[$j][$linesId[$k][$j] - 1];                        
                        if($ele == $wild && $wildReel[$j][$linesId[$k][$j] - 1] > 0){
                            $mul = $wildReel[$j][$linesId[$k][$j] - 1];
                        }
                        if($firstEle == $wild){
                            $firstEle = $ele;
                            $lineWinNum[$k] = $lineWinNum[$k] + 1;
                            if($j == 5){
                                $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline * $mul;
                                $totalWin += $lineWins[$k];
                                $_obf_winCount++;
                                $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                    $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 6 + $kk);
                                }
                            }else if($j >= 2 && $ele == $wild){
                                $wildWin = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline * $mul;
                                $wildWinNum = $lineWinNum[$k];
                            }
                        }else if($ele == $firstEle || $ele == $wild){
                            $lineWinNum[$k] = $lineWinNum[$k] + 1;
                            if($j == 5){
                                $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline * $mul;
                                if($lineWins[$k] < $wildWin){
                                    $lineWins[$k] = $wildWin;
                                    $lineWinNum[$k] = $wildWinNum;
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
                                $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline * $mul;
                                if($lineWins[$k] < $wildWin){
                                    $lineWins[$k] = $wildWin;
                                    $lineWinNum[$k] = $wildWinNum;
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
                $spinType = 's';
                if($scatterCount >= 3){
                    $scater_muls = [0,0,0,2,5,0];
                    $scatterWin = $betline * $lines * $scater_muls[$scatterCount];
                    $totalWin = $totalWin + $scatterWin;
                }
                if( $totalWin > 0) 
                {
                    $spinType = 'c';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }
                $_obf_totalWin = $totalWin;
                if( $scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin') 
                {
                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 15);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                }
                if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                }
                
                $strLastReel = implode(',', $lastReel);
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 6; $i++){
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
                    $strOtherResponse = $strOtherResponse . '&accm=cp&acci=0';
                    if($accv != ''){
                        $strOtherResponse = $strOtherResponse . '&accv=' . $accv;
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                    if($scatterCount >= 3 ){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1&psym=1~' . $scatterWin . '~' . implode(',', $scatterPoses);
                    }
                }
                if(count($arr_slm_mp) > 0){
                    $strOtherResponse = $strOtherResponse . '&slm_mp='. implode(',', $arr_slm_mp) .'&slm_mv=' . implode(',', $arr_slm_mv);
                }
                if($str_trail != ''){
                    $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                }
                if($str_stf != ''){
                    $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                }
                if($str_slm_lmi != ''){
                    $strOtherResponse = $strOtherResponse . '&slm_lmi=' . $str_slm_lmi;
                }
                if($str_slm_lmv != ''){
                    $strOtherResponse = $strOtherResponse . '&slm_lmv=' . $str_slm_lmv;
                }
                if($strInitReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $strInitReel;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse . $strWinLine .'&balance='.$Balance. '&ls=0&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=4&c='.$betline.'&sw=6&sver=5&reel_set='.$currentReelSet.'&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&s='.$strLastReel.'&w='.$totalWin;
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
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

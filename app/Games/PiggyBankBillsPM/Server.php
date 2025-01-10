<?php 
namespace VanguardLTE\Games\PiggyBankBillsPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [12,7,10,7,12,10,9,5,6,8,8,6,11,9,9,1,7,1]);
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
                    $bet = '50.00';
                }
                $currentReelSet = 0;
                $spinType = 's';
                $fsmore = 0;
                if($stack != null){
                    $currentReelSet = $stack['reel_set'];
                    $str_initReel = $stack['initReel'];
                    $arr_slm_mp = [];
                    if($stack['slm_mp'] != ''){
                        $arr_slm_mp = explode(',', $stack['slm_mp']);
                    }
                    $arr_slm_mv = [];
                    if($stack['slm_mv'] != ''){
                        $arr_slm_mv = explode(',', $stack['slm_mv']);
                    }
                    $str_slm_lmi = $stack['slm_lmi'];
                    $str_slm_lmv = $stack['slm_lmv'];
                    $str_sr = $stack['sr'];
                    $fsmore = $stack['fsmore'];

                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if(count($arr_slm_mp) > 0){
                        $strOtherResponse = $strOtherResponse . '&slm_mp=' . implode(',', $arr_slm_mp);
                    }
                    if(count($arr_slm_mv) > 0){
                        $strOtherResponse = $strOtherResponse . '&slm_mv=' . implode(',', $arr_slm_mv);
                    }
                    if($str_slm_lmi != ''){
                        $strOtherResponse = $strOtherResponse . '&slm_lmi=' . $str_slm_lmi;
                    }
                    if($str_slm_lmv != ''){
                        $strOtherResponse = $strOtherResponse . '&slm_lmv=' . $str_slm_lmv;
                    }
                    if($str_sr != ''){
                        $strOtherResponse = $strOtherResponse . '&sr=' . $str_sr;
                    }
                    $strOtherResponse = $strOtherResponse  . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0 || $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0) 
                    {
                        $strOtherResponse = $strOtherResponse . '&fs_total='.($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 1).'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsend_total=1';
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

                $response = 'balance='. $Balance .'&cfgs=4908&ver=2&index=1&balance_cash='. $Balance .'&reel_set_size=16&reel_set='.$currentReelSet.$strOtherResponse.'&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&gameInfo={props:{max_rnd_sim:"0",max_rnd_hr:"4760694",max_rnd_win:"5000"}}&wl_i=tbm~5000&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=10,8,9,11,8,10&sb=6,12,10,11,5,9&reel_set10=12,11,12,10,2,5,12,11,12,11,11,12,8,12,6,11,11,12,12,9,12,12,11,11,11,12,5,12,12,12,11,12,10,12,8,12,11,8,12,12,12,12,11,9,7,12,10,11,12,12,12,12,10,11,8,11,11,11,4,12,12,10,11,11,6,12,11,12,11,12,7,12,12,12,11,11,9~4,10,12,8,8,11,9,8,8,9,9,8,10,10,10,9,9,12,8,12,9,10,8,9,9,8,7,11,8,9,9,10,11,10,9,9,9,9,10,9,8,12,8,9,8,9,11,10,9,9,8,8,2,12,10,9,10,12,8,8,11,9,6,10,9,8,10,12,8,8,10,5,9,10~12,12,12,12,12,11,11,11,12,10,5,12,7,11,11,11,12,8,11,12,12,12,12,12,12,11,12,11,2,9,11,11,10,11,4,12,11,9,10,10,10,7,12,11,11,11,11,12,12,12,12,12,12,11,12,6,12,12,11,8,11,12,10,12,10,12,12,12,12,11~10,8,8,9,9,11,8,8,9,8,8,11,12,9,9,9,8,9,7,10,8,10,12,12,8,6,10,8,4,9,9,8,8,8,10,2,9,10,11,10,9,10,9,10,9,9,12,5,8~11,8,12,11,12,11,11,12,4,11,9,12,10,12,12,12,12,6,2,11,10,12,12,11,12,9,11,12,12,5,12,7,12,12~9,9,12,7,8,11,8,9,9,9,9,10,8,8,9,2,10,4,9,8,8,8,8,9,9,10,12,9,10,6,11,10,10,10,11,10,10,8,8,12,5,10,8,8&sc='. implode(',', $slotSettings->Bet) .'&defc=50.00&reel_set11=12,11,12,10,2,5,12,11,12,11,11,12,8,12,6,11,11,12,12,9,12,12,11,11,11,12,5,12,12,12,11,12,10,12,8,12,11,8,12,12,12,12,11,9,7,12,10,11,12,12,12,12,10,11,8,11,11,11,4,12,12,10,11,11,6,12,11,12,11,12,7,12,12,12,11,11,9~4,10,12,8,8,11,9,8,8,9,9,8,10,10,10,9,9,12,8,12,9,10,8,9,9,8,7,11,8,9,9,10,11,10,9,9,9,9,10,9,8,12,8,9,8,9,11,10,9,9,8,8,2,12,10,9,10,12,8,8,11,9,6,10,9,8,10,12,8,8,10,5,9,10~12,12,12,12,12,11,11,11,12,10,5,12,7,11,11,11,12,8,11,12,12,12,12,12,12,11,12,11,2,9,11,11,10,11,4,12,11,9,10,10,10,7,12,11,11,11,11,12,12,12,12,12,12,11,12,6,12,12,11,8,11,12,10,12,10,12,12,12,12,11~10,8,8,9,9,11,8,8,9,8,8,11,12,9,9,9,8,9,7,10,8,10,12,12,8,6,10,8,4,9,9,8,8,8,10,2,9,10,11,10,9,10,9,10,9,9,12,5,8~11,8,12,11,12,11,11,12,4,11,9,12,10,12,12,12,12,6,2,11,10,12,12,11,12,9,11,12,12,5,12,7,12,12~9,9,12,7,8,11,8,9,9,9,9,10,8,8,9,2,10,4,9,8,8,8,8,9,9,10,12,9,10,6,11,10,10,10,11,10,10,8,8,12,5,10,8,8&reel_set12=12,11,12,10,2,5,12,11,12,11,11,12,8,12,6,11,11,12,12,9,12,12,11,11,11,12,5,12,12,12,11,12,10,12,8,12,11,8,12,12,12,12,11,9,7,12,10,11,12,12,12,12,10,11,8,11,11,11,4,12,12,10,11,11,6,12,11,12,11,12,7,12,12,12,11,11,9~4,10,12,8,8,11,9,8,8,9,9,8,10,10,10,9,9,12,8,12,9,10,8,9,9,8,7,11,8,9,9,10,11,10,9,9,9,9,10,9,8,12,8,9,8,9,11,10,9,9,8,8,2,12,10,9,10,12,8,8,11,9,6,10,9,8,10,12,8,8,10,5,9,10~12,12,12,12,12,11,11,11,12,10,5,12,7,11,11,11,12,8,11,12,12,12,12,12,12,11,12,11,2,9,11,11,10,11,4,12,11,9,10,10,10,7,12,11,11,11,11,12,12,12,12,12,12,11,12,6,12,12,11,8,11,12,10,12,10,12,12,12,12,11~10,8,8,9,9,11,8,8,9,8,8,11,12,9,9,9,8,9,7,10,8,10,12,12,8,6,10,8,4,9,9,8,8,8,10,2,9,10,11,10,9,10,9,10,9,9,12,5,8~11,8,12,11,12,11,11,12,4,11,9,12,10,12,12,12,12,6,2,11,10,12,12,11,12,9,11,12,12,5,12,7,12,12~9,9,12,7,8,11,8,9,9,9,9,10,8,8,9,2,10,4,9,8,8,8,8,9,9,10,12,9,10,6,11,10,10,10,11,10,10,8,8,12,5,10,8,8&reel_set13=12,11,12,10,2,5,12,11,12,11,11,12,8,12,6,11,11,12,12,9,12,12,11,11,11,12,5,12,12,12,11,12,10,12,8,12,11,8,12,12,12,12,11,9,7,12,10,11,12,12,12,12,10,11,8,11,11,11,4,12,12,10,11,11,6,12,11,12,11,12,7,12,12,12,11,11,9~4,10,12,8,8,11,9,8,8,9,9,8,10,10,10,9,9,12,8,12,9,10,8,9,9,8,7,11,8,9,9,10,11,10,9,9,9,9,10,9,8,12,8,9,8,9,11,10,9,9,8,8,2,12,10,9,10,12,8,8,11,9,6,10,9,8,10,12,8,8,10,5,9,10~12,12,12,12,12,11,11,11,12,10,5,12,7,11,11,11,12,8,11,12,12,12,12,12,12,11,12,11,2,9,11,11,10,11,4,12,11,9,10,10,10,7,12,11,11,11,11,12,12,12,12,12,12,11,12,6,12,12,11,8,11,12,10,12,10,12,12,12,12,11~10,8,8,9,9,11,8,8,9,8,8,11,12,9,9,9,8,9,7,10,8,10,12,12,8,6,10,8,4,9,9,8,8,8,10,2,9,10,11,10,9,10,9,10,9,9,12,5,8~11,8,12,11,12,11,11,12,4,11,9,12,10,12,12,12,12,6,2,11,10,12,12,11,12,9,11,12,12,5,12,7,12,12~9,9,12,7,8,11,8,9,9,9,9,10,8,8,9,2,10,4,9,8,8,8,8,9,9,10,12,9,10,6,11,10,10,10,11,10,10,8,8,12,5,10,8,8&sh=3&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&counter=2&reel_set14=12,11,12,10,2,5,12,11,12,11,11,12,8,12,6,11,11,12,12,9,12,12,11,11,11,12,5,12,12,12,11,12,10,12,8,12,11,8,12,12,12,12,11,9,7,12,10,11,12,12,12,12,10,11,8,11,11,11,4,12,12,10,11,11,6,12,11,12,11,12,7,12,12,12,11,11,9~4,10,12,8,8,11,9,8,8,9,9,8,10,10,10,9,9,12,8,12,9,10,8,9,9,8,7,11,8,9,9,10,11,10,9,9,9,9,10,9,8,12,8,9,8,9,11,10,9,9,8,8,2,12,10,9,10,12,8,8,11,9,6,10,9,8,10,12,8,8,10,5,9,10~12,12,12,12,12,11,11,11,12,10,5,12,7,11,11,11,12,8,11,12,12,12,12,12,12,11,12,11,2,9,11,11,10,11,4,12,11,9,10,10,10,7,12,11,11,11,11,12,12,12,12,12,12,11,12,6,12,12,11,8,11,12,10,12,10,12,12,12,12,11~10,8,8,9,9,11,8,8,9,8,8,11,12,9,9,9,8,9,7,10,8,10,12,12,8,6,10,8,4,9,9,8,8,8,10,2,9,10,11,10,9,10,9,10,9,9,12,5,8~11,8,12,11,12,11,11,12,4,11,9,12,10,12,12,12,12,6,2,11,10,12,12,11,12,9,11,12,12,5,12,7,12,12~9,9,12,7,8,11,8,9,9,9,9,10,8,8,9,2,10,4,9,8,8,8,8,9,9,10,12,9,10,6,11,10,10,10,11,10,10,8,8,12,5,10,8,8&paytable=0,0,0,0,0,0,0;0,0,0,0,0,0,0;0,0,0,0,0,0,0;0,0,0,0,0,20000,0;0,0,0,0,0,2000,0;0,0,0,0,0,1000,0;0,0,0,0,0,400,0;0,0,0,0,0,200,0;0,0,0,0,0,100,0;0,0,0,0,0,40,0;0,0,0,0,0,20,0;0,0,0,0,0,10,0;0,0,0,0,0,5,0&l=20&reel_set15=12,11,12,10,2,5,12,11,12,11,11,12,8,12,6,11,11,12,12,9,12,12,11,11,11,12,5,12,12,12,11,12,10,12,8,12,11,8,12,12,12,12,11,9,7,12,10,11,12,12,12,12,10,11,8,11,11,11,4,12,12,10,11,11,6,12,11,12,11,12,7,12,12,12,11,11,9~4,10,12,8,8,11,9,8,8,9,9,8,10,10,10,9,9,12,8,12,9,10,8,9,9,8,7,11,8,9,9,10,11,10,9,9,9,9,10,9,8,12,8,9,8,9,11,10,9,9,8,8,2,12,10,9,10,12,8,8,11,9,6,10,9,8,10,12,8,8,10,5,9,10~12,12,12,12,12,11,11,11,12,10,5,12,7,11,11,11,12,8,11,12,12,12,12,12,12,11,12,11,2,9,11,11,10,11,4,12,11,9,10,10,10,7,12,11,11,11,11,12,12,12,12,12,12,11,12,6,12,12,11,8,11,12,10,12,10,12,12,12,12,11~10,8,8,9,9,11,8,8,9,8,8,11,12,9,9,9,8,9,7,10,8,10,12,12,8,6,10,8,4,9,9,8,8,8,10,2,9,10,11,10,9,10,9,10,9,9,12,5,8~11,8,12,11,12,11,11,12,4,11,9,12,10,12,12,12,12,6,2,11,10,12,12,11,12,9,11,12,12,5,12,7,12,12~9,9,12,7,8,11,8,9,9,9,9,10,8,8,9,2,10,4,9,8,8,8,8,9,9,10,12,9,10,6,11,10,10,10,11,10,10,8,8,12,5,10,8,8&reel_set0=12,11,12,10,2,5,12,11,12,11,11,12,8,12,6,11,11,12,12,9,12,12,11,11,11,12,5,12,12,12,11,12,10,12,8,12,11,8,12,12,12,12,11,9,7,12,10,11,12,12,12,12,10,11,8,11,11,11,4,12,12,10,11,11,6,12,11,12,11,12,7,12,12,12,11,11,9~4,10,12,8,8,11,9,8,8,9,9,8,10,1,10,10,9,9,12,8,12,9,10,8,9,9,8,7,11,8,9,9,10,11,10,9,9,9,9,10,9,8,12,8,9,8,9,11,10,9,9,8,8,2,12,10,9,10,12,8,8,11,9,6,10,9,8,10,12,8,8,10,5,9,10~12,12,12,12,12,11,11,11,12,10,5,12,7,11,11,11,12,8,11,12,12,12,12,12,12,11,12,11,2,9,11,11,10,11,4,12,11,9,10,10,10,7,12,11,11,11,11,12,12,12,12,12,12,11,12,6,12,12,11,8,11,12,10,12,10,12,12,12,12,11~10,8,8,9,9,11,8,8,9,8,8,11,12,9,9,9,8,9,7,10,8,10,12,12,8,6,10,8,4,9,9,8,8,8,10,2,9,10,11,10,9,10,9,10,9,9,12,5,1,8~11,8,12,11,12,11,11,12,4,1,11,9,12,10,12,12,12,12,6,2,11,10,12,12,11,12,9,11,12,12,5,12,7,12,12~9,9,12,7,8,11,8,9,9,9,9,10,8,8,9,2,10,4,9,8,8,8,8,9,9,10,12,9,10,6,11,10,10,10,11,10,10,8,8,12,5,10,8,8&s='.$lastReelStr.'&reel_set2=12,11,12,10,2,5,12,11,12,11,11,12,8,12,6,11,11,12,12,9,12,12,11,11,11,12,5,12,12,12,11,12,10,12,8,12,11,8,12,12,12,12,11,9,7,12,10,11,12,12,12,12,10,11,8,11,11,11,4,12,12,10,11,11,6,12,11,12,11,12,7,12,12,12,11,11,9~4,10,12,8,8,11,9,8,8,9,9,8,10,1,10,10,9,9,12,8,12,9,10,8,9,9,8,7,11,8,9,9,10,11,10,9,9,9,9,10,9,8,12,8,9,8,9,11,10,9,9,8,8,2,12,10,9,10,12,8,8,11,9,6,10,9,8,10,12,8,8,10,5,9,10~12,12,12,12,12,11,11,11,12,10,5,12,7,11,11,11,12,8,11,12,12,12,12,12,12,11,12,11,2,9,11,11,10,11,4,12,11,9,10,10,10,7,12,11,11,11,11,12,12,12,12,12,12,11,12,6,12,12,11,8,11,12,10,12,10,12,12,12,12,11~10,8,8,9,9,11,8,8,9,8,8,11,12,9,9,9,8,9,7,10,8,10,12,12,8,6,10,8,4,9,9,8,8,8,10,2,9,10,11,10,9,10,9,10,9,9,12,5,1,8~11,8,12,11,12,11,11,12,4,1,11,9,12,10,12,12,12,12,6,2,11,10,12,12,11,12,9,11,12,12,5,12,7,12,12~9,9,12,7,8,11,8,9,9,9,9,10,8,8,9,2,10,4,9,8,8,8,8,9,9,10,12,9,10,6,11,10,10,10,11,10,10,8,8,12,5,10,8,8&reel_set1=12,11,12,10,2,5,12,11,12,11,11,12,8,12,6,11,11,12,12,9,12,12,11,11,11,12,5,12,12,12,11,12,10,12,8,12,11,8,12,12,12,12,11,9,7,12,10,11,12,12,12,12,10,11,8,11,11,11,4,12,12,10,11,11,6,12,11,12,11,12,7,12,12,12,11,11,9~4,10,12,8,8,11,9,8,8,9,9,8,10,1,10,10,9,9,12,8,12,9,10,8,9,9,8,7,11,8,9,9,10,11,10,9,9,9,9,10,9,8,12,8,9,8,9,11,10,9,9,8,8,2,12,10,9,10,12,8,8,11,9,6,10,9,8,10,12,8,8,10,5,9,10~12,12,12,12,12,11,11,11,12,10,5,12,7,11,11,11,12,8,11,12,12,12,12,12,12,11,12,11,2,9,11,11,10,11,4,12,11,9,10,10,10,7,12,11,11,11,11,12,12,12,12,12,12,11,12,6,12,12,11,8,11,12,10,12,10,12,12,12,12,11~10,8,8,9,9,11,8,8,9,8,8,11,12,9,9,9,8,9,7,10,8,10,12,12,8,6,10,8,4,9,9,8,8,8,10,2,9,10,11,10,9,10,9,10,9,9,12,5,1,8~11,8,12,11,12,11,11,12,4,1,11,9,12,10,12,12,12,12,6,2,11,10,12,12,11,12,9,11,12,12,5,12,7,12,12~9,9,12,7,8,11,8,9,9,9,9,10,8,8,9,2,10,4,9,8,8,8,8,9,9,10,12,9,10,6,11,10,10,10,11,10,10,8,8,12,5,10,8,8&reel_set4=12,11,12,10,2,5,12,11,12,11,11,12,8,12,6,11,11,12,12,9,12,12,11,11,11,12,5,12,12,12,11,12,10,12,8,12,11,8,12,12,12,12,11,9,7,12,10,11,12,12,12,12,10,11,8,11,11,11,4,12,12,10,11,11,6,12,11,12,11,12,7,12,12,12,11,11,9~4,10,12,8,8,11,9,8,8,9,9,8,10,1,10,10,9,9,12,8,12,9,10,8,9,9,8,7,11,8,9,9,10,11,10,9,9,9,9,10,9,8,12,8,9,8,9,11,10,9,9,8,8,2,12,10,9,10,12,8,8,11,9,6,10,9,8,10,12,8,8,10,5,9,10~12,12,12,12,12,11,11,11,12,10,5,12,7,11,11,11,12,8,11,12,12,12,12,12,12,11,12,11,2,9,11,11,10,11,4,12,11,9,10,10,10,7,12,11,11,11,11,12,12,12,12,12,12,11,12,6,12,12,11,8,11,12,10,12,10,12,12,12,12,11~10,8,8,9,9,11,8,8,9,8,8,11,12,9,9,9,8,9,7,10,8,10,12,12,8,6,10,8,4,9,9,8,8,8,10,2,9,10,11,10,9,10,9,10,9,9,12,5,1,8~11,8,12,11,12,11,11,12,4,1,11,9,12,10,12,12,12,12,6,2,11,10,12,12,11,12,9,11,12,12,5,12,7,12,12~9,9,12,7,8,11,8,9,9,9,9,10,8,8,9,2,10,4,9,8,8,8,8,9,9,10,12,9,10,6,11,10,10,10,11,10,10,8,8,12,5,10,8,8&reel_set3=12,11,12,10,2,5,12,11,12,11,11,12,8,12,6,11,11,12,12,9,12,12,11,11,11,12,5,12,12,12,11,12,10,12,8,12,11,8,12,12,12,12,11,9,7,12,10,11,12,12,12,12,10,11,8,11,11,11,4,12,12,10,11,11,6,12,11,12,11,12,7,12,12,12,11,11,9~4,10,12,8,8,11,9,8,8,9,9,8,10,1,10,10,9,9,12,8,12,9,10,8,9,9,8,7,11,8,9,9,10,11,10,9,9,9,9,10,9,8,12,8,9,8,9,11,10,9,9,8,8,2,12,10,9,10,12,8,8,11,9,6,10,9,8,10,12,8,8,10,5,9,10~12,12,12,12,12,11,11,11,12,10,5,12,7,11,11,11,12,8,11,12,12,12,12,12,12,11,12,11,2,9,11,11,10,11,4,12,11,9,10,10,10,7,12,11,11,11,11,12,12,12,12,12,12,11,12,6,12,12,11,8,11,12,10,12,10,12,12,12,12,11~10,8,8,9,9,11,8,8,9,8,8,11,12,9,9,9,8,9,7,10,8,10,12,12,8,6,10,8,4,9,9,8,8,8,10,2,9,10,11,10,9,10,9,10,9,9,12,5,1,8~11,8,12,11,12,11,11,12,4,1,11,9,12,10,12,12,12,12,6,2,11,10,12,12,11,12,9,11,12,12,5,12,7,12,12~9,9,12,7,8,11,8,9,9,9,9,10,8,8,9,2,10,4,9,8,8,8,8,9,9,10,12,9,10,6,11,10,10,10,11,10,10,8,8,12,5,10,8,8&reel_set6=12,11,12,10,2,5,12,11,12,11,11,12,8,12,6,11,11,12,12,9,12,12,11,11,11,12,5,12,12,12,11,12,10,12,8,12,11,8,12,12,12,12,11,9,7,12,10,11,12,12,12,12,10,11,8,11,11,11,4,12,12,10,11,11,6,12,11,12,11,12,7,12,12,12,11,11,9~4,10,12,8,8,11,9,8,8,9,9,8,10,10,10,9,9,12,8,12,9,10,8,9,9,8,7,11,8,9,9,10,11,10,9,9,9,9,10,9,8,12,8,9,8,9,11,10,9,9,8,8,2,12,10,9,10,12,8,8,11,9,6,10,9,8,10,12,8,8,10,5,9,10~12,12,12,12,12,11,11,11,12,10,5,12,7,11,11,11,12,8,11,12,12,12,12,12,12,11,12,11,2,9,11,11,10,11,4,12,11,9,10,10,10,7,12,11,11,11,11,12,12,12,12,12,12,11,12,6,12,12,11,8,11,12,10,12,10,12,12,12,12,11~10,8,8,9,9,11,8,8,9,8,8,11,12,9,9,9,8,9,7,10,8,10,12,12,8,6,10,8,4,9,9,8,8,8,10,2,9,10,11,10,9,10,9,10,9,9,12,5,8~11,8,12,11,12,11,11,12,4,11,9,12,10,12,12,12,12,6,2,11,10,12,12,11,12,9,11,12,12,5,12,7,12,12~9,9,12,7,8,11,8,9,9,9,9,10,8,8,9,2,10,4,9,8,8,8,8,9,9,10,12,9,10,6,11,10,10,10,11,10,10,8,8,12,5,10,8,8&reel_set5=12,11,12,10,2,5,12,11,12,11,11,12,8,12,6,11,11,12,12,9,12,12,11,11,11,12,5,12,12,12,11,12,10,12,8,12,11,8,12,12,12,12,11,9,7,12,10,11,12,12,12,12,10,11,8,11,11,11,4,12,12,10,11,11,6,12,11,12,11,12,7,12,12,12,11,11,9~4,10,12,8,8,11,9,8,8,9,9,8,10,1,10,10,9,9,12,8,12,9,10,8,9,9,8,7,11,8,9,9,10,11,10,9,9,9,9,10,9,8,12,8,9,8,9,11,10,9,9,8,8,2,12,10,9,10,12,8,8,11,9,6,10,9,8,10,12,8,8,10,5,9,10~12,12,12,12,12,11,11,11,12,10,5,12,7,11,11,11,12,8,11,12,12,12,12,12,12,11,12,11,2,9,11,11,10,11,4,12,11,9,10,10,10,7,12,11,11,11,11,12,12,12,12,12,12,11,12,6,12,12,11,8,11,12,10,12,10,12,12,12,12,11~10,8,8,9,9,11,8,8,9,8,8,11,12,9,9,9,8,9,7,10,8,10,12,12,8,6,10,8,4,9,9,8,8,8,10,2,9,10,11,10,9,10,9,10,9,9,12,5,1,8~11,8,12,11,12,11,11,12,4,1,11,9,12,10,12,12,12,12,6,2,11,10,12,12,11,12,9,11,12,12,5,12,7,12,12~9,9,12,7,8,11,8,9,9,9,9,10,8,8,9,2,10,4,9,8,8,8,8,9,9,10,12,9,10,6,11,10,10,10,11,10,10,8,8,12,5,10,8,8&reel_set8=12,11,12,10,2,5,12,11,12,11,11,12,8,12,6,11,11,12,12,9,12,12,11,11,11,12,5,12,12,12,11,12,10,12,8,12,11,8,12,12,12,12,11,9,7,12,10,11,12,12,12,12,10,11,8,11,11,11,4,12,12,10,11,11,6,12,11,12,11,12,7,12,12,12,11,11,9~4,10,12,8,8,11,9,8,8,9,9,8,10,10,10,9,9,12,8,12,9,10,8,9,9,8,7,11,8,9,9,10,11,10,9,9,9,9,10,9,8,12,8,9,8,9,11,10,9,9,8,8,2,12,10,9,10,12,8,8,11,9,6,10,9,8,10,12,8,8,10,5,9,10~12,12,12,12,12,11,11,11,12,10,5,12,7,11,11,11,12,8,11,12,12,12,12,12,12,11,12,11,2,9,11,11,10,11,4,12,11,9,10,10,10,7,12,11,11,11,11,12,12,12,12,12,12,11,12,6,12,12,11,8,11,12,10,12,10,12,12,12,12,11~10,8,8,9,9,11,8,8,9,8,8,11,12,9,9,9,8,9,7,10,8,10,12,12,8,6,10,8,4,9,9,8,8,8,10,2,9,10,11,10,9,10,9,10,9,9,12,5,8~11,8,12,11,12,11,11,12,4,11,9,12,10,12,12,12,12,6,2,11,10,12,12,11,12,9,11,12,12,5,12,7,12,12~9,9,12,7,8,11,8,9,9,9,9,10,8,8,9,2,10,4,9,8,8,8,8,9,9,10,12,9,10,6,11,10,10,10,11,10,10,8,8,12,5,10,8,8&reel_set7=12,11,12,10,2,5,12,11,12,11,11,12,8,12,6,11,11,12,12,9,12,12,11,11,11,12,5,12,12,12,11,12,10,12,8,12,11,8,12,12,12,12,11,9,7,12,10,11,12,12,12,12,10,11,8,11,11,11,4,12,12,10,11,11,6,12,11,12,11,12,7,12,12,12,11,11,9~4,10,12,8,8,11,9,8,8,9,9,8,10,10,10,9,9,12,8,12,9,10,8,9,9,8,7,11,8,9,9,10,11,10,9,9,9,9,10,9,8,12,8,9,8,9,11,10,9,9,8,8,2,12,10,9,10,12,8,8,11,9,6,10,9,8,10,12,8,8,10,5,9,10~12,12,12,12,12,11,11,11,12,10,5,12,7,11,11,11,12,8,11,12,12,12,12,12,12,11,12,11,2,9,11,11,10,11,4,12,11,9,10,10,10,7,12,11,11,11,11,12,12,12,12,12,12,11,12,6,12,12,11,8,11,12,10,12,10,12,12,12,12,11~10,8,8,9,9,11,8,8,9,8,8,11,12,9,9,9,8,9,7,10,8,10,12,12,8,6,10,8,4,9,9,8,8,8,10,2,9,10,11,10,9,10,9,10,9,9,12,5,8~11,8,12,11,12,11,11,12,4,11,9,12,10,12,12,12,12,6,2,11,10,12,12,11,12,9,11,12,12,5,12,7,12,12~9,9,12,7,8,11,8,9,9,9,9,10,8,8,9,2,10,4,9,8,8,8,8,9,9,10,12,9,10,6,11,10,10,10,11,10,10,8,8,12,5,10,8,8&reel_set9=12,11,12,10,2,5,12,11,12,11,11,12,8,12,6,11,11,12,12,9,12,12,11,11,11,12,5,12,12,12,11,12,10,12,8,12,11,8,12,12,12,12,11,9,7,12,10,11,12,12,12,12,10,11,8,11,11,11,4,12,12,10,11,11,6,12,11,12,11,12,7,12,12,12,11,11,9~4,10,12,8,8,11,9,8,8,9,9,8,10,10,10,9,9,12,8,12,9,10,8,9,9,8,7,11,8,9,9,10,11,10,9,9,9,9,10,9,8,12,8,9,8,9,11,10,9,9,8,8,2,12,10,9,10,12,8,8,11,9,6,10,9,8,10,12,8,8,10,5,9,10~12,12,12,12,12,11,11,11,12,10,5,12,7,11,11,11,12,8,11,12,12,12,12,12,12,11,12,11,2,9,11,11,10,11,4,12,11,9,10,10,10,7,12,11,11,11,11,12,12,12,12,12,12,11,12,6,12,12,11,8,11,12,10,12,10,12,12,12,12,11~10,8,8,9,9,11,8,8,9,8,8,11,12,9,9,9,8,9,7,10,8,10,12,12,8,6,10,8,4,9,9,8,8,8,10,2,9,10,11,10,9,10,9,10,9,9,12,5,8~11,8,12,11,12,11,11,12,4,11,9,12,10,12,12,12,12,6,2,11,10,12,12,11,12,9,11,12,12,5,12,7,12,12~9,9,12,7,8,11,8,9,9,9,9,10,8,8,9,2,10,4,9,8,8,8,8,9,9,10,12,9,10,6,11,10,10,10,11,10,10,8,8,12,5,10,8,8';
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
                $linesId[0] = [0, 1];
                $linesId[1] = [2, 3];
                $linesId[2] = [4, 5];
                $linesId[3] = [6, 7];
                $linesId[4] = [8, 9];
                $linesId[5] = [10, 11];
                $linesId[6] = [12, 13];
                $linesId[7] = [14, 15];
                $linesId[8] = [16, 17];

                $slotEvent['slotBet'] = $slotEvent['c'];
                $slotEvent['slotLines'] = 20;
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
                
                $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $betline * $lines / 2, $lines);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '56671' . substr($roundstr, 4, 9);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $leftFreeGames = 0;
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
                $str_slm_lmi = '';
                $str_slm_lmv = '';
                $arr_slm_mp = [];
                $arr_slm_mv = [];
                $str_sr = '';
                $fsmore = 0;
                if($isGeneratedFreeStack == true || $slotSettings->GetGameData($slotSettings->slotId . 'WildSpin') > 0){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $str_initReel = $stack['initReel'];
                    if($stack['slm_mp'] != ''){
                        $arr_slm_mp = explode(',', $stack['slm_mp']);
                    }
                    if($stack['slm_mv'] != ''){
                        $arr_slm_mv = explode(',', $stack['slm_mv']);
                    }
                    $str_slm_lmi = $stack['slm_lmi'];
                    $str_slm_lmv = $stack['slm_lmv'];
                    $str_sr = $stack['sr'];
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
                    $str_initReel = $stack[0]['initReel'];
                    if($stack[0]['slm_mp'] != ''){
                        $arr_slm_mp = explode(',', $stack[0]['slm_mp']);
                    }
                    if($stack[0]['slm_mv'] != ''){
                        $arr_slm_mv = explode(',', $stack[0]['slm_mv']);
                    }
                    $str_slm_lmi = $stack[0]['slm_lmi'];
                    $str_slm_lmv = $stack[0]['slm_lmv'];
                    $str_sr = $stack[0]['sr'];
                    $fsmore = $stack[0]['fsmore'];
                }
                $reels = [];
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;
                $_obf_winCount = 0;
                for($i = 0; $i < 9; $i++){
                    if($lastReel[$linesId[$i][0]] == $lastReel[$linesId[$i][1]] && $lastReel[$linesId[$i][0]] == $scatter){
                        $scatterCount++;
                    }else if(($lastReel[$linesId[$i][0]] == $lastReel[$linesId[$i][1]]) || $lastReel[$linesId[$i][0]] == $wild || $lastReel[$linesId[$i][1]] == $wild){
                        $firstEle = $lastReel[$linesId[$i][0]];
                        if($firstEle == $wild){
                            $firstEle = $lastReel[$linesId[$i][1]];
                        }
                        $mul = 1;
                        if(count($arr_slm_mv) > 0){
                            for($k = 0; $k < 2; $k++){
                                if($arr_slm_mv[$linesId[$i][$k]] > 1){
                                    $mul = $mul * $arr_slm_mv[$linesId[$i][$k]]; 
                                }
                            }
                        }
                        $lineWin = $slotSettings->Paytable[$firstEle] * $betline * $mul;
                        if($lineWin > 0){
                            $totalWin += $lineWin;
                            $_obf_winCount++;
                            $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$i.'~'.$lineWin . '~' . $linesId[$i][0] . '~' . $linesId[$i][1];
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
                if( $scatterCount > 0 && $slotEvent['slotEvent'] != 'freespin') 
                {
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0 ) 
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 8);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                    }
                }
                if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                }

                $strLastReel = implode(',', $lastReel);
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 6; $i++){
                    $reelA[$i] = mt_rand(8, 12);
                    $reelB[$i] = mt_rand(8, 12);
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
                        $strOtherResponse = '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsend_total=1';
                        if($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') > 0){
                            $spinType = 'c';
                        }
                        $isEnd = true;
                    }
                    else
                    {
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                    if($fsmore > 0 ){
                        $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                    if($scatterCount > 0 ){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse = '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fs=1&fsres=0.00';
                    }
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if(count($arr_slm_mp) > 0){
                    $strOtherResponse = $strOtherResponse . '&slm_mp=' . implode(',', $arr_slm_mp);
                }
                if(count($arr_slm_mv) > 0){
                    $strOtherResponse = $strOtherResponse . '&slm_mv=' . implode(',', $arr_slm_mv);
                }
                if($str_slm_lmi != ''){
                    $strOtherResponse = $strOtherResponse . '&slm_lmi=' . $str_slm_lmi;
                }
                if($str_slm_lmv != ''){
                    $strOtherResponse = $strOtherResponse . '&slm_lmv=' . $str_slm_lmv;
                }
                if($str_sr != ''){
                    $strOtherResponse = $strOtherResponse . '&sr=' . $str_sr;
                }
                
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .$strWinLine .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&c='.$betline.'&sver=5&reel_set='.$currentReelSet.'&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&s='.$strLastReel .'&w='.$totalWin;
                
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount'). ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if( $scatterCount > 0 && $slotEvent['slotEvent'] != 'freespin') 
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
                $slotSettings->InternalError('PiggyBankBillsDBCommit : ' . $e);
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

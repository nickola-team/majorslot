<?php 
namespace VanguardLTE\Games\RiseofGizaPowerNudgePM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 10);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [5,6,6,9,7,5,9,6,9,7,5,9,6,4,7]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $strOtherResponse = '';
                $stack = null;
                
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $lastEvent->serverResponse->TumbWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', $lastEvent->serverResponse->TumbleState);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    if (isset($lastEvent->serverResponse->ReplayGameLogs)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', json_decode(json_encode($lastEvent->serverResponse->ReplayGameLogs), true)); //ReplayLog
                    }
                    if (isset($lastEvent->serverResponse->TumbAndFreeStacks)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', json_decode(json_encode($lastEvent->serverResponse->TumbAndFreeStacks), true)); // FreeStack
                        $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
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
                    $str_initReel = $stack['initReel'];
                    $wmv = $stack['wmv'];
                    $rs_p = $stack['rs_p'];
                    $rs_t = $stack['rs_t'];
                    $str_rs = $stack['rs'];
                    $rs_more = $stack['rs_more'];
                    $str_accv = $stack['accv'];
                    $str_ds = $stack['ds'];
                    $str_dsa = $stack['dsa'];
                    $str_dsam = $stack['dsam'];
                    $str_sn = $stack['sn'];
                    $fsmore = $stack['fsmore'];
                    $currentReelSet = $stack['reel_set'];
                    
                    if($wmv > 0){
                        $strOtherResponse = $strOtherResponse . '&wmt=pr&wmv=' . $wmv;
                        if($wmv > 1){
                            $strOtherResponse = $strOtherResponse . '&gwm=' . $wmv;
                        }
                    }
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($str_accv != ''){
                        $strOtherResponse = $strOtherResponse . '&accm=cp&acci=0&accv=' . $str_accv;
                    }
                    if($str_ds != ''){
                        $strOtherResponse = $strOtherResponse . '&ds=' . $str_ds;
                    }
                    if($str_dsa != ''){
                        $strOtherResponse = $strOtherResponse . '&dsa=' . $str_dsa;
                    }
                    if($str_dsam != ''){
                        $strOtherResponse = $strOtherResponse . '&dsam=' . $str_dsam;
                    }
                    if($str_sn != ''){
                        $strOtherResponse = $strOtherResponse . '&sn=' . $str_sn;
                    }
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    if($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') > 0){
                        $strOtherResponse = $strOtherResponse.'&rs=t&rs_win='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbWin').'&rs_p='.($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') - 1).'&rs_c='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbleState').'&rs_m='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbleState');                    
                    }
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0 || $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 )
                    {
                        if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                        {
                            $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                            if($rs_p >= 0){
                                $strOtherResponse = $strOtherResponse . '&fsend_total=0';
                            }else{
                                $strOtherResponse = $strOtherResponse . '&fsend_total=1';
                            }
                        }
                        else
                        {
                            $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        }
                        if($fsmore > 0){
                            $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                        }
                        if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                            $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                        }
                    }
                }else{
                    $strOtherResponse = $strOtherResponse . '&accm=cp&acci=0&accv=0';
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                $Balance = $slotSettings->GetBalance();
                $response = 'balance='. $Balance .'&cfgs=4752&ver=2&index=1&balance_cash='. $Balance .'&reel_set_size=23&reel_set='. $currentReelSet .'&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&gameInfo={props:{max_rnd_sim:"0",max_rnd_hr:"143631551",max_rnd_win:"4000"}}&wl_i=tbm~4000&stime='. floor(microtime(true) * 1000) . '&sa=6,9,5,7,8&sb=9,5,4,6,3&reel_set10=7,8,6,6,5,6,6,6,6,7,6,5,9,4,7,7,7,7,7,8,5,5,7,5,5,5,5,6,8,6,6,9,9,3,3,3,6,4,9,9,6,9,9,9,9,6,4,6,6,8,5,4,8,8,8,7,6,3,3,6,8,4,4,4,6,3,6,6,3,6,6,8~7,3,6,5,7,4,9,3,3,3,3,5,6,4,6,8,8,4,8,4,6,6,6,6,6,9,9,3,5,8,6,5,6,9,9,9,6,8,6,6,7,6,7,3,4,6,7,7,7,3,7,6,4,6,6,4,8,9,4,4,4,6,6,9,6,5,6,3,6,6,4,8,8,8,6,9,8,9,6,6,8,8,5,5,5,5,9,6,6,7,9,7,6,6,7,4,7~6,8,9,5,6,4,4,4,5,5,6,4,8,7,8,6,6,6,3,6,7,6,3,9,3,3,3,6,4,6,4,6,6,9,9,9,9,5,6,6,5,6,7,8,8,8,7,6,7,9,7,6,9,7,7,7,6,3,6,8,6,8,5,5,5,5,6,6,8,4,6,3,9~9,3,3,3,3,7,9,7,7,7,4,7,6,8,8,8,9,4,8,9,9,9,5,9,5,5,5,7,7,8,6,6,6,8,3,5,4,4,4,4,3,5,8~7,6,9,7,7,7,9,7,9,7,8,8,8,5,8,6,7,3,3,3,9,8,4,8,6,6,6,6,4,8,8,9,9,9,3,4,3,5,5,5,5,5,3,3,5,4,4,4,7,9,8,7,9&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=200.00&reel_set11=7,7,6,3,3,3,8,9,7,5,7,7,7,8,4,9,7,8,8,8,7,9,4,7,9,9,9,3,4,8,5,5,5,5,9,6,9,9,6,6,6,4,5,5,3,4,4,4,3,8,3,8,8~9,6,3,3,3,3,6,8,6,6,6,9,5,7,9,9,9,8,6,6,7,7,7,8,3,6,4,4,4,9,5,6,8,8,8,7,6,4,5,5,5,4,6,7,4,6~7,9,6,6,4,4,4,8,7,6,6,5,8,6,6,6,6,6,3,3,6,8,8,8,9,5,5,6,9,8,9,9,9,6,5,4,6,8,6,3,3,3,6,4,6,6,7,7,7,7,9,4,6,8,3,6,5,5,5,3,4,9,5,7,6,7~8,4,8,9,6,6,6,6,8,6,6,9,6,7,7,7,7,7,6,6,7,6,6,4,5,5,5,9,5,6,6,5,6,9,9,9,6,8,6,5,9,6,8,8,8,9,6,5,6,3,8,3,4,4,4,5,6,4,5,4,7,3,3,3,9,8,3,7,3,6,7,6~9,7,7,7,7,9,3,8,8,8,8,3,5,5,5,4,5,8,9,9,9,7,5,8,3,3,3,7,7,4,4,4,5,8,3,6,6,6,4,9,9,6&reel_set12=3,3,3,6,7,7,7,7,7,8,8,8,4,4,6,6,6,9,9,9,9,9,5,5,5,5,5,4,4,4,8,8,3~9,4,4,3,6,5,7,7,7,7,8,5,9,5,3,9,7,8,8,8,5,7,8,8,9,3,8,8,3,3,3,7,8,7,8,8,3,6,3,5,5,5,7,5,3,7,9,9,6,4,9,9,9,5,9,8,4,5,7,9,8,4,4,4,4,3,7,4,8,3,7,9,6,6,6,3,7,9,8,9,7,5,9,5~9,6,9,7,5,6,6,4,4,4,8,6,6,4,6,4,7,6,9,6,6,6,6,8,6,5,7,4,7,6,3,3,3,6,7,8,6,6,9,6,3,7,9,9,9,6,7,9,8,3,9,4,6,8,8,8,3,6,3,5,6,8,8,6,4,7,7,7,5,6,5,9,7,9,8,6,5,5,5,5,6,5,3,6,8,6,6,5,6~5,6,6,6,7,3,7,7,7,5,7,5,5,5,9,6,6,3,3,3,4,9,9,9,9,6,6,8,8,8,4,6,4,4,4,6,3,8,8~3,3,3,6,6,6,6,5,9,9,9,8,7,7,7,4,4,4,4,3,8,8,8,9,5,5,5,6,6,7&purInit_e=1&reel_set13=4,3,8,7,5,7,3,6,7,7,7,7,5,7,8,5,5,9,3,7,7,3,3,3,9,7,9,7,9,7,7,3,7,4,5,5,5,6,8,7,7,8,6,7,7,4,9,6,6,6,6,8,8,9,6,5,6,5,7,9,9,9,3,7,4,6,4,7,8,5,7,8,8,8,8,9,7,5,7,4,7,3,7,7,9,4,4,4,7,8,7,9,7,5,6,7,6,7,7~6,7,6,6,6,9,9,3,7,7,7,7,3,7,9,9,9,7,4,7,3,3,3,5,7,6,4,4,4,9,4,6,8,8,8,7,8,8,5,5,5,4,5,7,8~4,5,6,8,7,7,5,6,4,4,4,7,6,7,8,7,8,7,7,5,4,7,7,7,7,6,5,7,4,8,7,7,4,6,6,6,4,7,5,9,7,3,9,7,9,3,9,9,9,9,8,6,7,4,5,7,7,3,9,8,8,8,9,7,7,6,7,6,7,8,3,3,3,3,7,3,5,9,8,6,8,7,5,9,5,5,5,7,7,3,7,7,6,9,8,7,7,5~5,4,8,4,6,6,6,6,8,7,7,3,3,6,3,3,3,8,4,4,9,3,3,8,8,8,8,6,7,4,8,6,8,9,9,9,5,3,6,9,8,9,5,5,5,9,4,6,9,9,5,3,7,7,7,5,9,7,8,3,9,4,4,4,5,9,8,5,7,5,6,4~6,5,5,9,4,8,3,3,3,5,7,4,6,8,4,9,8,8,8,9,6,8,7,3,7,7,6,6,6,6,8,4,6,9,4,9,5,5,5,6,7,3,6,9,8,8,9,9,9,5,9,9,8,8,5,8,7,7,7,5,8,6,3,9,3,3,4,4,4,5,5,9,6,3,6,3,3&sh=3&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&reel_set18=7,6,6,6,7,9,4,7,7,7,8,5,5,3,3,3,4,9,3,9,9,9,5,6,5,5,5,7,3,9,8,8,8,6,3,9,4,4,4,7,6,8,4~8,7,7,7,5,6,8,3,3,3,7,4,5,6,6,6,9,9,5,5,5,5,6,7,9,9,9,6,3,7,8,8,8,9,4,3,4,4,4,9,7,3,6~5,3,3,9,4,4,4,7,6,6,8,6,8,8,8,8,5,5,9,8,6,6,6,7,8,9,9,8,9,9,9,9,8,3,8,8,7,3,3,3,8,8,5,7,8,7,7,7,4,7,8,5,8,5,5,5,8,6,4,8,4,8,6~9,5,8,6,3,8,8,8,7,8,8,9,8,7,7,7,7,7,4,8,5,8,4,5,5,5,5,3,5,9,8,8,9,6,6,6,6,8,8,4,7,8,9,9,9,4,8,6,3,8,6,6,4,4,4,8,3,5,7,8,9,3,3,3,7,8,6,8,8,9,5,8~8,9,6,6,6,8,6,7,8,8,8,9,4,8,9,9,9,6,8,8,7,7,7,4,7,8,4,4,4,3,9,7,5,5,5,3,8,5,3,3,3,5,8,6,4&reel_set19=9,9,9,9,9,8,7,7,7,9,7,5,5,5,9,9,6,6,6,5,9,3,3,3,5,4,8,8,8,7,3,4,4,4,6,8,6~8,8,7,9,9,6,6,6,6,9,9,3,9,9,5,9,9,9,9,8,7,6,9,9,8,5,3,3,3,9,6,4,4,8,7,9,7,7,7,8,9,9,6,8,6,9,4,4,4,9,4,3,4,3,6,5,8,8,8,7,3,4,7,9,7,4,5,5,5,9,9,4,9,7,5,6,9,9~3,3,4,6,4,4,4,5,3,9,4,9,7,9,9,9,9,4,5,7,8,6,6,6,8,9,9,8,5,9,8,8,8,5,7,9,9,6,8,7,7,7,6,9,9,6,9,3,3,3,7,9,5,3,9,8,5,5,5,9,4,9,7,9,9,6~6,6,6,6,6,7,7,7,7,8,8,8,7,4,5,5,5,4,5,9,9,9,5,3,3,3,9,8,4,4,4,8,3,3~6,5,6,8,9,7,7,7,9,8,6,7,6,3,8,8,8,8,3,8,7,3,8,6,6,6,5,7,6,7,8,4,5,5,5,8,6,7,3,5,3,9,9,9,7,4,5,6,9,7,3,3,3,9,4,6,4,7,8,4,4,4,6,5,4,8,9,5,3,7&counter=2&reel_set14=8,6,4,3,7,8,6,6,6,5,8,7,8,3,9,8,9,3,3,3,9,4,3,7,4,5,8,8,8,8,9,8,9,4,3,9,4,6,9,9,9,4,3,5,3,5,3,8,5,5,5,6,9,7,5,6,9,8,6,7,7,7,6,5,7,9,6,9,9,4,4,4,4,7,4,6,5,8,5,8,3~7,5,4,9,7,6,6,6,6,4,9,7,9,3,7,8,7,7,7,5,6,8,7,7,4,7,9,9,9,7,6,7,4,8,7,9,3,3,3,4,5,8,8,4,7,7,4,4,4,6,6,5,6,7,7,3,8,8,8,3,7,7,8,3,7,7,5,5,5,9,7,6,4,7,9,9,7,8~7,5,6,4,4,4,8,7,7,9,7,7,7,9,8,7,7,9,6,6,6,6,3,5,7,9,9,9,3,7,6,7,8,8,8,7,4,4,9,5,3,3,3,8,3,8,7,5,5,5,7,4,7,5,7,6~9,7,5,9,7,5,7,7,7,5,7,3,3,6,7,8,7,3,3,3,4,7,6,4,9,8,9,5,5,5,7,5,4,7,9,9,7,5,6,6,6,7,7,9,7,6,3,4,7,9,9,9,7,5,8,7,3,6,7,8,8,8,7,8,5,7,7,8,7,6,4,4,4,7,4,6,8,7,6,3,7,8~9,3,3,3,5,6,6,8,8,8,4,8,7,6,6,6,9,4,6,5,5,5,6,7,7,7,7,8,8,5,9,9,9,3,5,8,4,4,4,9,3,3,9&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;120,40,10,0,0;100,30,9,0,0;70,20,6,0,0;50,15,5,0,0;25,8,3,0,0;15,5,2,0,0;10,3,1,0,0&l=10&reel_set15=6,8,5,7,5,3,6,6,6,8,4,8,4,9,9,3,4,3,3,3,9,6,4,6,9,9,8,8,8,8,5,5,7,9,6,6,7,9,9,9,9,5,4,4,9,9,8,5,5,5,5,9,3,9,8,7,7,8,7,7,7,7,6,3,8,5,3,5,6,4,4,4,3,4,8,4,6,8,8,3,3~6,3,3,3,5,4,8,8,8,8,8,6,6,6,5,6,5,5,5,4,8,9,9,9,3,6,7,7,7,7,9,4,4,4,9,3,9,7~7,7,5,9,4,4,4,7,8,7,9,4,7,7,7,3,7,9,5,8,6,6,6,7,9,5,7,4,6,9,9,9,5,4,3,5,7,8,8,8,8,7,7,8,7,3,3,3,7,6,8,7,3,5,5,5,6,7,9,7,7,6,6~5,8,6,7,7,7,7,7,4,7,3,3,3,7,9,3,4,9,5,5,5,3,6,7,9,6,6,6,5,7,7,6,9,9,9,8,7,7,4,6,8,8,8,7,3,7,9,4,4,4,8,7,5,8,5,7~7,6,6,6,9,8,7,7,7,7,3,6,9,9,9,9,6,3,3,3,7,5,4,4,4,7,7,4,8,8,8,8,7,5,5,5,3,5,4,7&reel_set16=6,3,8,8,8,8,8,7,8,7,5,3,7,7,7,8,5,8,6,5,5,5,5,8,6,8,6,8,8,6,6,6,4,8,4,8,9,9,9,9,8,6,8,9,3,3,3,3,8,9,5,4,7,4,4,4,9,7,9,7,5,8,8~4,6,6,6,8,4,8,8,8,8,5,9,9,9,6,8,7,7,7,7,7,4,4,4,3,8,5,5,5,8,9,3,3,3,6,9,8~9,8,6,8,7,7,6,8,4,4,4,4,5,7,6,5,6,8,7,5,4,8,8,8,6,8,8,4,8,8,4,7,5,6,6,6,8,8,9,5,8,9,8,8,3,8,9,9,9,8,5,8,5,9,6,3,8,3,8,3,3,3,8,6,8,4,7,8,9,8,6,7,7,7,8,8,9,7,7,5,9,8,6,8,5,5,5,3,9,3,9,8,4,8,3,5,7,8~9,6,6,6,7,3,5,7,7,7,3,9,9,3,3,3,7,4,5,9,9,9,5,4,5,5,5,6,7,9,8,8,8,8,4,6,4,4,4,6,7,3,8~3,6,8,6,4,7,7,7,3,9,6,4,5,3,3,3,3,9,3,7,5,9,8,8,6,6,6,6,6,4,4,9,4,5,5,5,7,8,3,7,5,7,9,9,9,5,8,7,9,9,6,5,8,8,8,7,3,5,5,9,6,4,4,4,7,3,9,6,9,7,6,7&reel_set17=5,9,6,7,6,6,6,6,9,7,4,5,9,7,7,7,7,8,9,5,4,6,6,5,3,3,3,8,9,3,7,3,5,9,9,9,8,7,6,7,9,4,5,5,5,5,4,3,8,3,4,7,8,8,8,3,5,9,6,4,4,4,4,4,8,9,3,6,7,3,7,9~8,4,6,7,6,8,6,6,6,8,9,9,3,3,9,5,5,8,8,8,7,8,5,7,8,6,8,8,9,9,9,4,7,8,8,4,4,7,9,7,7,7,8,8,9,8,8,9,4,4,4,4,5,8,8,3,8,8,6,8,5,5,5,8,6,3,8,9,6,7,8,3,3,3,4,3,4,8,8,6,5,7,8~9,5,4,4,4,6,8,8,4,8,8,8,3,7,8,8,6,6,6,3,7,9,9,9,9,9,8,5,8,6,3,3,3,5,7,9,8,7,7,7,4,7,8,6,5,5,5,8,5,8,6,8~7,8,6,5,8,3,8,8,8,7,8,7,8,8,7,8,4,7,7,7,3,8,6,3,8,4,8,4,5,5,5,4,8,9,6,8,8,5,6,6,6,6,8,8,6,7,5,9,8,9,9,9,9,8,4,7,5,8,8,6,3,3,3,8,7,9,8,8,9,5,8,4,4,4,9,9,5,5,8,8,3,3,6~4,6,7,7,7,9,3,6,9,8,8,8,6,5,4,6,6,6,5,9,7,3,5,5,5,9,5,6,7,9,9,9,3,7,7,3,3,3,8,9,8,5,4,4,4,4,3,8,7,6&total_bet_max='.$slotSettings->game->rezerv.'&reel_set21=4,3,4,6,6,6,7,5,6,9,5,7,7,7,9,4,7,5,8,8,8,8,3,3,4,5,4,5,5,5,8,7,7,6,9,9,9,8,7,6,6,5,3,3,3,8,3,8,6,9,4,4,4,3,8,9,7,7,8~4,7,6,7,7,7,8,6,4,8,5,4,4,4,4,8,7,6,8,8,8,8,7,7,5,3,3,6,6,6,8,5,9,3,5,5,5,3,5,6,6,7,3,3,3,8,7,6,4,3,9,9,9,5,4,9,6,8,7~9,9,4,4,4,6,9,9,9,9,9,7,8,8,6,6,6,9,3,7,9,8,8,8,7,9,8,7,7,7,9,5,4,3,3,3,9,5,3,5,5,5,6,4,9,6,5~9,5,3,6,5,9,9,9,9,8,3,7,6,9,7,7,7,9,8,5,6,7,6,9,5,5,5,6,9,4,7,4,3,6,6,6,9,7,9,7,8,6,3,3,3,9,8,9,9,5,9,3,8,8,8,9,9,8,5,9,8,4,4,4,9,7,5,9,4,9,4,9~6,9,4,9,6,6,6,3,6,6,8,7,9,9,9,6,5,4,9,6,7,7,7,4,9,7,8,8,9,4,4,4,9,5,7,3,9,8,8,8,9,4,9,9,5,3,3,3,9,8,9,9,8,5,5,5,7,9,7,9,4,9,3&reel_set22=9,6,9,8,4,8,3,9,9,9,8,4,4,9,8,9,8,9,4,4,4,9,8,4,3,4,4,9,4,3,8,8,8,8,6,9,9,8,3,9,8,3,3,3,8,3,9,8,6,6,8,4,6,6,6,6,8,9,9,6,9,9,3,4,6~7,5,7,6,7,5,6,6,6,8,7,7,5,5,8,3,5,5,5,3,7,6,8,3,6,6,8,7,7,7,6,7,6,8,6,5,7,8,8,8,6,7,7,3,3,5,6,3,3,3,5,8,6,8,8,3,8,8,5~9,7,5,5,9,9,9,9,7,9,5,5,4,7,7,7,7,5,9,7,9,9,7,7,5,5,5,9,9,7,4,9,7,4,4,4,9,4,5,4,9,7,7,4~9,9,6,8,9,8,6,6,8,9,6,6,6,6,9,6,9,8,9,3,4,6,9,8,9,8,8,8,8,8,9,6,9,8,4,4,6,9,8,8,4,4,4,6,6,3,9,9,3,6,8,9,6,6,8,9,9,9,4,3,4,8,8,3,8,9,9,3,8,6,3,3,3,6,8,9,9,4,3,4,6,8,9,3,9,4~7,5,7,6,6,8,5,7,5,8,6,5,7,7,7,5,6,3,5,8,8,6,8,5,3,6,6,8,7,6,6,6,8,3,7,7,5,6,5,3,7,8,8,6,3,5,5,5,5,8,7,8,7,6,6,7,7,3,3,8,3,3,3,3,3,7,3,3,8,5,6,6,5,7,3,5,3,8,8,8,8,8,5,8,6,8,3,5,8,6,8,8,7,7,3,8&reel_set0=5,5,5,3,8,8,8,8,9,9,9,5,6,7,7,7,7,4,3,3,3,9,4,4,4,6,6,6,7,3,4,6,7,8,9,8,9,4,3,8,4,6,9,7,6,4,8,4,3,8,6,3,8,3,6,9,4,6,7,9,7,4,8,7,8,9~9,4,6,9,9,9,5,8,7,3,6,6,6,7,7,7,5,5,5,4,4,4,8,8,8,3,3,3,7,5,6,5,7,5,8,5,4,6,4,6,4,5,8,6,5,4,3,4,6,3,5,8,5,8,6,7,4,8,5~9,5,5,5,5,7,8,8,8,3,6,6,6,8,9,9,9,6,4,3,3,3,7,7,7,4,4,4,8,5,6,5,7,3,5,7,4,7,3,5,6,4,3,5,6,5,3,8,5,6,7,6,7,5,7,3,6,7,5,3,7,5,6,3,6,3,7,6,4,7,5,8,6,7,3,6,3,5,6,3,4,6,3,8,7,6,5,6,5,3,7,8,6,4,7,6,7,6,8,6,7,6,5,6,5,7,6,3,6,8,6,7,8,7,8,4,7,5,7,5~9,8,8,8,5,3,9,9,9,6,7,8,4,3,3,3,5,5,5,4,4,4,7,7,7,6,6,6,5,8,6,7,4,5,3,6,3,6,3,6,8,3,7,3,7,4,3,7,8,5,8,6,7,3,6,5,8,7,5,3,5,3,6,8,5,3,6~3,5,5,5,9,6,6,6,7,6,4,5,7,7,7,8,3,3,3,9,9,9,4,4,4,8,8,8,4,6,7,6,7,5,6,4,5,6,8,5,9,6,4,9,8,4,5,6,4,7,4,5,6,5,6,8,4,5,4,9,6,4,8,9,4,6,9,4,6,5,4,7,4,5&s='.$lastReelStr.'&accInit=[{id:0,mask:"cp;mp"}]&reel_set2=9,7,8,8,6,6,6,7,9,7,3,5,7,7,7,7,8,8,6,3,9,8,8,8,5,9,4,7,5,9,9,9,9,7,9,9,6,5,5,5,5,4,8,5,8,9,6,3,3,3,5,4,6,4,4,4,4,4,6,4,8,8,7,6,7~5,3,3,6,6,6,7,4,8,3,6,3,3,3,3,9,8,7,9,9,9,3,3,8,3,4,7,7,7,9,9,6,8,3,4,4,4,7,7,6,3,8,8,8,9,3,4,6,4,5,5,5,3,3,5,3,3,5~4,3,3,6,5,8,4,4,4,6,3,3,7,3,7,8,9,3,3,3,7,8,3,9,8,9,4,6,6,6,3,8,3,4,8,6,6,3,9,9,9,3,7,6,3,9,5,3,9,8,8,8,8,9,3,3,5,3,4,7,7,7,3,5,5,3,7,3,3,9,5,5,5,6,7,5,3,3,5,6,3,7~5,3,7,3,3,3,3,8,7,8,3,7,7,7,4,3,3,4,5,5,5,3,3,6,9,5,6,6,6,6,5,6,8,9,9,9,3,9,5,3,3,8,8,8,9,3,9,8,4,4,4,3,4,3,6,7,7~8,8,7,7,4,7,7,7,7,5,9,6,7,5,8,7,8,8,8,8,9,6,6,9,4,9,5,6,6,6,3,9,7,8,7,6,6,8,5,5,5,9,3,6,4,4,5,8,5,9,9,9,5,7,9,8,6,3,9,4,4,4,9,7,4,8,5,6,6,7,3,3,3,6,4,8,8,9,9,6,5,7&reel_set1=6,4,9,6,3,8,3,3,3,3,5,7,3,8,3,3,5,7,7,7,6,4,8,9,6,7,3,3,5,5,5,6,7,3,3,8,9,7,3,6,6,6,3,5,5,6,4,3,3,7,9,9,9,6,5,3,8,3,5,8,9,8,8,8,4,3,3,9,3,8,9,3,4,4,4,7,5,3,3,7,9,3,3,4~9,8,4,6,6,6,9,3,8,6,3,3,3,5,8,3,6,9,9,9,3,7,3,4,5,7,7,7,3,3,6,7,4,4,4,3,7,3,7,8,8,8,9,9,3,4,5,5,5,5,3,3,6,4,8~3,3,5,4,3,3,4,4,4,6,9,5,6,9,3,9,5,3,3,3,3,5,3,7,3,6,3,6,6,6,7,3,3,9,9,6,8,8,9,9,9,3,8,8,3,7,9,8,9,8,8,8,5,6,7,5,3,7,4,7,7,7,5,3,8,3,6,4,3,3,5,5,5,7,3,8,6,3,7,3,3,4~9,5,5,6,6,6,6,9,4,7,8,4,5,7,7,7,8,9,4,8,3,8,8,8,6,8,7,4,6,9,9,9,9,9,8,7,9,7,5,5,5,6,5,7,5,4,9,3,3,3,8,8,5,6,4,4,4,4,8,7,3,9,7,6,7~8,6,5,7,9,7,7,7,5,9,4,7,6,6,8,8,8,8,7,9,5,7,3,6,6,6,6,9,4,7,9,3,5,5,5,5,8,6,9,4,6,8,9,9,9,4,8,8,5,7,7,4,4,4,5,9,8,6,9,6,3,3,3,4,8,8,7,9,6,5,7&reel_set4=3,6,4,9,7,4,4,4,5,5,7,4,7,9,6,7,7,7,6,4,3,4,4,8,4,5,5,5,5,8,4,8,6,9,3,6,6,6,8,4,9,4,9,4,9,9,9,5,4,4,6,7,7,4,8,8,8,5,9,4,5,6,4,4,3,3,3,7,4,4,3,8,4,4,8~6,6,6,7,4,4,4,4,9,9,9,4,7,7,7,3,3,3,3,4,8,8,8,5,5,5,5,9,8,6~4,7,3,3,3,9,8,5,4,4,4,4,5,7,6,6,6,9,3,9,9,9,9,7,8,4,8,8,8,3,4,6,7,7,7,4,4,5,5,5,5,6,4,6,8,4~8,7,3,4,6,6,6,6,6,3,6,7,9,6,8,9,7,7,7,9,6,7,9,9,5,7,8,8,8,8,3,9,7,5,8,9,9,9,9,6,7,5,6,8,8,3,5,5,5,3,9,6,3,8,3,7,4,4,4,3,7,5,5,8,4,7,3,3,3,7,8,5,9,5,9,8,5,4~5,6,8,7,7,7,6,8,6,4,7,8,8,8,9,7,9,5,5,6,6,6,8,7,3,8,7,5,5,5,6,3,3,5,9,9,9,9,7,6,5,9,9,3,3,3,8,8,6,4,8,4,4,4,9,9,7,3,6,7&purInit=[{type:"d",bet:800}]&reel_set3=5,5,4,7,7,9,9,6,6,6,4,5,8,5,4,9,7,9,7,7,7,7,9,6,6,7,8,6,4,8,8,8,5,9,8,4,8,4,8,9,8,9,9,9,6,7,7,8,4,6,9,4,5,5,5,6,3,9,5,3,6,4,8,3,3,3,7,8,6,8,5,3,5,8,4,4,4,6,7,7,9,5,9,8,7,7,9~6,7,7,7,9,3,6,8,8,8,7,9,5,6,6,6,8,7,7,5,5,5,6,8,9,9,9,8,4,4,4,4,4,5,5,7,3,3,3,6,8,9,9~7,5,7,3,8,7,3,4,4,4,8,6,5,3,3,9,6,9,9,3,3,3,6,3,3,4,4,6,3,9,9,6,6,6,3,3,8,3,8,7,4,5,8,9,9,9,5,7,9,7,3,6,3,3,8,8,8,8,3,3,5,5,3,3,6,3,7,7,7,8,6,7,4,3,3,8,7,9,5,5,5,4,3,3,6,3,9,3,3,5,5~3,8,6,3,7,5,3,3,3,7,8,3,8,3,9,5,3,7,7,7,5,7,3,3,4,8,9,6,5,5,5,9,9,3,3,8,6,7,8,6,6,6,3,4,3,3,6,3,5,3,9,9,9,5,3,3,5,7,7,3,4,8,8,8,3,8,9,4,4,3,9,3,4,4,4,7,3,3,9,6,5,3,6,6~4,6,8,3,6,6,6,4,7,3,3,7,3,3,3,3,5,3,3,4,5,7,9,9,9,6,6,3,9,4,9,7,7,7,3,5,3,6,3,4,4,4,8,3,4,9,3,3,8,8,8,8,3,6,7,3,5,5,5,5,8,9,7,8,3,3,9&reel_set20=9,4,6,6,6,6,9,3,8,4,7,7,7,4,5,7,5,6,8,8,8,5,7,9,3,5,5,5,8,6,5,8,9,9,9,5,3,4,7,7,3,3,3,3,4,8,8,4,4,4,3,6,6,7,7,8~6,6,6,9,8,9,9,9,7,4,7,7,7,9,3,4,4,4,5,4,8,8,8,6,9,3,3,3,9,7,5,5,5,8,9,6~4,4,4,9,9,9,9,9,7,8,6,6,6,6,4,8,8,8,5,9,7,7,7,6,5,3,3,3,9,7,5,5,5,3,9,8~3,8,9,9,9,9,5,4,7,7,7,6,6,9,5,5,5,8,7,9,6,6,6,7,9,9,3,3,3,6,9,9,8,8,8,8,5,3,4,4,4,5,9,4,7,9~9,6,5,8,3,7,7,7,6,3,6,4,3,7,8,8,8,3,7,9,8,5,3,5,6,6,6,5,7,9,6,8,5,5,5,5,3,4,8,7,7,4,3,3,3,3,4,6,8,6,4,7,9,9,9,8,9,7,6,8,8,4,4,4,5,6,8,7,6,5,9,7&reel_set6=9,6,9,6,6,6,3,5,7,8,7,7,7,7,8,7,4,8,8,8,6,8,7,3,9,9,9,8,6,9,7,5,5,5,3,8,9,8,4,4,4,6,5,9,7,3,3,3,9,5,4,3,5~6,9,5,9,8,4,7,7,7,8,7,8,3,6,3,8,6,8,8,8,7,9,3,4,8,7,5,6,6,6,8,8,6,7,5,5,8,9,5,5,5,6,9,7,8,5,9,3,7,9,9,9,6,7,6,4,7,6,5,3,3,3,9,9,5,3,7,6,7,8,4,4,4,7,9,6,9,6,9,5,3,8~3,3,3,3,5,4,4,4,4,6,4,6,6,6,8,4,8,9,9,9,4,5,8,8,8,6,9,7,7,7,4,4,5,5,5,9,7,3,7~4,8,4,4,4,4,4,3,7,6,8,7,7,7,7,5,6,4,5,5,5,8,4,8,4,4,6,6,6,6,5,6,9,9,9,9,9,4,4,9,4,8,8,8,9,5,4,4,7,3,3,3,4,3,7,4,3,5~4,4,3,9,5,6,6,6,4,9,9,4,6,4,9,4,4,4,7,4,4,3,6,8,9,9,9,3,3,7,4,3,8,8,7,7,7,7,7,4,4,6,8,3,3,3,9,4,4,5,4,8,4,8,8,8,5,7,5,9,8,4,5,5,5,6,4,6,6,7,4,4,3&reel_set5=3,5,6,6,6,5,4,3,7,7,7,7,8,8,7,8,8,8,8,9,8,9,9,9,9,9,7,7,3,5,5,5,5,3,9,4,4,4,8,6,4,9,3,3,3,6,5,6,6,7~3,4,4,6,4,7,8,4,6,6,6,8,8,4,4,5,4,4,3,4,4,4,4,8,7,6,9,8,4,9,6,5,9,9,9,6,9,6,4,4,6,5,4,4,7,7,7,8,5,7,7,4,5,7,4,3,3,3,3,9,4,9,7,4,9,9,4,3,8,8,8,3,4,6,3,3,4,6,4,8,5,5,5,4,9,3,4,5,7,7,4,8,4,4~4,3,5,9,7,4,3,3,3,4,8,6,3,4,9,4,6,4,4,4,8,4,8,6,4,9,3,4,6,6,6,8,6,4,8,9,3,5,4,9,9,9,8,7,9,6,6,4,9,8,8,8,4,7,4,7,4,5,9,4,7,7,7,5,4,4,5,4,5,4,8,5,5,5,4,3,7,7,4,7,4,6,5~5,9,8,4,3,4,8,4,4,4,4,8,6,4,9,4,9,5,7,4,7,7,7,5,7,9,5,4,6,8,8,6,5,5,5,4,4,6,4,4,5,4,9,9,6,6,6,7,4,4,3,7,6,4,4,8,9,9,9,4,3,4,4,7,3,9,4,4,8,8,8,6,5,8,5,6,4,4,6,7,3,3,3,8,5,7,4,4,3,9,4,4,7~8,3,7,8,6,8,4,7,7,7,7,3,7,5,9,7,6,9,8,8,8,8,5,9,7,7,6,9,8,6,6,6,6,9,9,7,6,8,4,8,5,5,5,5,6,5,3,5,4,5,7,5,9,9,9,6,9,9,5,3,6,3,9,3,3,3,7,5,7,7,8,8,6,3,4,4,4,6,9,6,8,9,8,9,7,8,6&reel_set8=4,8,8,6,8,8,6,6,6,7,8,6,9,9,7,7,4,7,7,7,4,8,9,7,8,5,9,8,8,8,3,9,7,4,9,7,6,7,9,9,9,5,7,4,3,9,7,6,3,3,3,6,4,4,7,8,6,6,9,5,5,5,8,4,9,9,3,7,3,4,4,4,3,8,3,8,9,3,3,6,5~6,6,6,5,5,5,5,9,9,9,9,3,6,7,7,7,8,4,4,4,5,8,8,8,4,3,3,3,5,5,7~5,7,5,3,4,4,4,5,6,5,6,3,5,5,5,5,3,5,4,8,6,6,6,9,6,8,7,7,9,9,9,9,8,5,5,4,8,8,8,8,5,9,5,5,4,7,7,7,5,6,9,6,9,3,3,3,7,5,5,8,5,5,7~5,5,5,5,9,3,7,7,7,7,5,6,6,6,5,4,9,9,9,5,9,3,3,3,5,5,8,8,8,6,7,4,4,4,6,8,8~6,7,7,7,6,7,8,8,8,8,6,6,6,6,7,4,3,3,3,4,9,9,9,9,8,9,4,4,4,5,8,5,5,5,3,3,7,9&reel_set7=3,5,9,8,5,6,5,5,5,5,5,8,5,6,6,7,4,5,9,7,7,7,7,3,3,5,5,6,8,9,5,3,3,3,7,5,3,5,5,9,8,8,7,6,6,6,9,8,6,5,6,5,3,9,5,9,9,9,5,7,5,5,4,3,5,5,8,8,8,8,7,6,5,9,7,5,8,5,5,4,4,4,4,3,7,5,3,9,5,4,6,4~9,5,6,6,6,5,4,5,5,5,5,6,5,7,7,9,9,9,5,4,9,7,7,7,5,9,5,4,4,4,8,6,8,5,8,8,8,4,5,5,3,3,3,7,3,6,3,8~8,5,9,4,4,4,7,7,5,5,5,5,5,3,7,3,5,5,6,6,6,9,3,5,5,9,9,9,4,5,6,5,8,8,8,5,3,5,6,4,7,7,7,6,7,8,9,3,3,3,9,4,6,8,8,5~9,8,4,6,6,6,6,8,7,4,7,9,7,7,7,3,7,4,7,7,8,8,8,6,4,9,6,8,7,9,9,9,8,6,8,3,9,5,5,5,3,5,9,6,8,4,4,4,9,9,7,8,6,3,3,3,7,5,9,4,3,4,8~6,3,8,7,5,7,7,7,3,6,8,6,7,8,3,8,8,8,9,5,9,4,6,8,8,6,6,6,4,9,4,6,6,9,7,5,5,5,4,7,7,3,9,5,8,9,9,9,7,9,9,6,7,5,9,3,3,3,8,9,6,7,6,8,4,4,4,4,8,8,6,9,5,7,7,3&reel_set9=8,6,6,6,7,4,8,7,7,7,9,8,5,8,8,8,6,3,5,9,9,9,9,3,3,3,3,9,8,4,5,5,5,7,6,7,4,4,4,6,4,7,9~6,9,9,7,8,3,7,7,7,4,6,6,9,3,8,5,8,8,8,8,6,8,8,7,8,6,3,8,6,6,6,3,9,7,6,9,4,8,7,9,9,9,7,6,6,7,8,7,7,6,3,3,3,7,4,7,9,3,7,5,3,4,4,4,9,6,9,4,9,9,5,4,5,5,5,7,4,9,8,8,6,8,9,6~5,7,4,4,4,4,3,6,9,4,5,5,5,5,9,5,5,3,6,6,6,3,5,5,6,6,9,9,9,9,7,8,8,8,8,8,7,9,5,5,7,7,7,7,5,4,5,5,3,3,3,8,3,5,8,6,5~4,5,5,3,7,3,5,6,3,5,5,5,5,5,8,3,5,3,8,7,5,4,7,7,7,5,8,5,5,8,5,6,3,5,5,3,3,3,6,9,8,5,7,7,5,9,3,9,6,6,6,7,6,5,5,9,5,6,6,3,5,9,9,9,6,8,4,5,9,5,5,9,5,5,8,8,8,9,5,5,8,4,7,6,5,8,5,4,4,4,7,7,8,5,4,6,9,9,7,3,5,4~5,4,9,7,6,6,6,7,5,5,4,5,5,5,5,5,6,5,9,9,8,9,9,9,5,5,3,7,4,7,7,7,5,8,3,5,6,4,4,4,8,5,6,8,7,5,8,8,8,4,5,8,9,9,3,3,3,6,7,5,3,5,4,6&total_bet_min=20.00';
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
                $linesId = [];
                $linesId[1] = [2, 2, 2, 2, 2];
                $linesId[0] = [1, 1, 1, 1, 1];
                $linesId[2] = [3, 3, 3, 3, 3];
                $linesId[3] = [1, 2, 3, 2, 1];
                $linesId[4] = [3, 2, 1, 2, 3];
                $linesId[5] = [2, 1, 1, 1, 2];
                $linesId[6] = [2, 3, 3, 3, 2];
                $linesId[7] = [1, 1, 2, 3, 3];
                $linesId[8] = [3, 3, 2, 1, 1];
                $linesId[9] = [2, 3, 2, 1, 2];
                $pur = -1;
                if(isset($slotEvent['pur'])){
                    $pur = $slotEvent['pur'];
                }
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    $slotEvent['slotEvent'] = 'freespin';
                }
                $isTumb = false;
                if($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') > 0){
                    $isTumb = true;
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
                if($pur >= 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $allBet * 80;
                }
                $tumbAndFreeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'freespin' || $isTumb == true){
                    if($slotEvent['slotEvent'] == 'freespin' && $isTumb == false){
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    }
                    $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                    if($isTumb == false){
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                    }
                }
                else
                {
                    $slotEvent['slotEvent'] = 'bet';
                    $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                    $_sum = $allBet / 100 * $slotSettings->GetPercent();
                    if($pur >= 0){                            
                        $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $_sum, $slotEvent['slotEvent'], true);
                        $winType = 'bonus';
                        $_winAvaliableMoney = $slotSettings->GetBank('bonus');
                    }else{
                        $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $_sum, $slotEvent['slotEvent']);
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
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
                $this->winLines = [];
                $bonusMpl = 1;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $str_initReel = '';
                $wmv = 0;
                $str_rs = '';
                $rs_p = -1;
                $rs_c = 0;
                $rs_more = 0;
                $str_accv = '';
                $str_ds = '';
                $str_dsa = '';
                $str_dsam = '';
                $str_sn = '';
                $fsmore = 0;
                $currentReelSet = 0;
                if($slotEvent['slotEvent'] == 'freespin' || $isTumb == true){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_initReel = $stack['initReel'];
                    $wmv = $stack['wmv'];
                    $rs_p = $stack['rs_p'];
                    $rs_t = $stack['rs_t'];
                    $str_rs = $stack['rs'];
                    $rs_more = $stack['rs_more'];
                    $str_accv = $stack['accv'];
                    $str_ds = $stack['ds'];
                    $str_dsa = $stack['dsa'];
                    $str_dsam = $stack['dsam'];
                    $str_sn = $stack['sn'];
                    $fsmore = $stack['fsmore'];
                    $currentReelSet = $stack['reel_set'];
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
                    $wmv = $stack[0]['wmv'];
                    $rs_p = $stack[0]['rs_p'];
                    $rs_t = $stack[0]['rs_t'];
                    $str_rs = $stack[0]['rs'];
                    $rs_more = $stack[0]['rs_more'];
                    $str_accv = $stack[0]['accv'];
                    $str_ds = $stack[0]['ds'];
                    $str_dsa = $stack[0]['dsa'];
                    $str_dsam = $stack[0]['dsam'];
                    $str_sn = $stack[0]['sn'];
                    $fsmore = $stack[0]['fsmore'];
                    $currentReelSet = $stack[0]['reel_set'];
                }

                $reels = [];
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;
                for($i = 0; $i < 5; $i++){
                    $reels[$i] = [];
                    for($j = 0; $j < 3; $j++){
                        $reels[$i][$j] = $lastReel[$j * 5 + $i];
                    }
                }
                if($str_accv != ''){
                    $scatterCount = $str_accv;
                }
                $_lineWinNumber = 1;
                $_obf_winCount = 0;
                for( $k = 0; $k < 10; $k++ ) 
                {
                    $_lineWin = '';
                    $firstEle = $reels[0][$linesId[$k][0] - 1];
                    $lineWinNum[$k] = 1;
                    $lineWins[$k] = 0;
                    for($j = 1; $j < 5; $j++){
                        $ele = $reels[$j][$linesId[$k][$j] - 1];
                        if($firstEle == $wild){
                            $firstEle = $ele;
                            $lineWinNum[$k] = $lineWinNum[$k] + 1;
                        }else if($ele == $firstEle || $ele == $wild){
                            $lineWinNum[$k] = $lineWinNum[$k] + 1;
                            if($j == 4){
                                $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                if($wmv > 0){
                                    $lineWins[$k] = $lineWins[$k] * $wmv;
                                }
                                if($lineWins[$k] > 0){
                                    $totalWin += $lineWins[$k];
                                    $_obf_winCount++;
                                    $diffX = $j + 1 - $lineWinNum[$k];
                                    $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                    for($kk = $diffX; $kk < $lineWinNum[$k] + $diffX; $kk++){
                                        $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                    }
                                }
                            }
                        }else{
                            if($slotSettings->Paytable[$firstEle][$lineWinNum[$k]] > 0){
                                $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                if($wmv > 0){
                                    $lineWins[$k] = $lineWins[$k] * $wmv;
                                }
                                if($lineWins[$k] > 0){
                                    $totalWin += $lineWins[$k];
                                    $_obf_winCount++;
                                    $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                    $diffX = $j - $lineWinNum[$k];
                                    for($kk = $diffX; $kk < $lineWinNum[$k] + $diffX; $kk++){
                                        $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                    }   
                                }
                                break;
                            }else{                 
                                $lineWinNum[$k] = 0;
                                if($j <= 2){
                                    $firstEle = $reels[$j][$linesId[$k][$j] - 1];
                                    $lineWinNum[$k] = 1;
                                }else{
                                    break;
                                }                   
                            }
                        }
                    }
                }
                $isNewTumb = false;
                if($rs_p >= 0){
                    $isNewTumb = true;
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
                if($isNewTumb == true){
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') + 1);
                    $isState = false;
                    $spinType = 's';
                }else{
                    if( $scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin') 
                    {
                        $fs = 8 + ($scatterCount - 3) * 2;
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $fs);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                    }
                    else if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin')
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                    }
                }
                $strLastReel = implode(',', $lastReel);
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 5; $i++){
                    $reelA[$i] = mt_rand(4, 9);
                    $reelB[$i] = mt_rand(4, 9);
                }
                $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                $strReelSb = implode(',', $reelB); // '3,8,4,7,10';

                if($isNewTumb == true && (count($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) >= $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1)){
                    $subSaStack = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $subsaReel = explode(',', $subSaStack['reel']);
                    for($i = 0; $i < 5; $i++){
                        $reelA[$i] = $subsaReel[$i];
                    }
                    $strReelSa = implode(',', $reelA);
                }

                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $strOtherResponse = '';
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    if($rs_p > 0){
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') + $totalWin);
                    }
                    $spinType = 's';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $isEnd = false;
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                    {
                        $isEnd = true;
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        if($isNewTumb == true){
                            $isState = false;
                            $strOtherResponse = $strOtherResponse . '&fsend_total=0';
                        }else{
                            $isState = true;
                            if($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') > 0){
                                $spinType = 'c';
                            }
                            $strOtherResponse = $strOtherResponse . '&fsend_total=1';
                        }
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
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    if($rs_p > 0){
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') + $totalWin);
                    }
                    if($scatterCount >= 3 && $isNewTumb == false){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1';
                        if($pur >= 0){
                            $strOtherResponse = $strOtherResponse . '&purtr=1&puri=' . $pur;
                        }
                    }
                }
                if($isNewTumb == true){
                    $strOtherResponse = $strOtherResponse . '&rs=mc&rs_p=' . ($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') - 1) . '&rs_c='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbleState').'&rs_m='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbleState');
                    if($rs_p > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin');
                    }
                }
                else if($isTumb == true){
                    if($slotEvent['slotEvent'] != 'freespin' && $scatterCount < 3){
                        $spinType = 'c';
                        $isState = true;
                    }
                    $strOtherResponse = $strOtherResponse.'&rs_win='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbWin').'&rs_t='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbleState').'&tmb_win='.($slotSettings->GetGameData($slotSettings->slotId . 'TumbWin'));
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', 0);
                }
                if($wmv > 0){
                    $strOtherResponse = $strOtherResponse . '&wmt=pr&wmv=' . $wmv;
                    if($wmv > 1){
                        $strOtherResponse = $strOtherResponse . '&gwm=' . $wmv;
                    }
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($str_accv != ''){
                    $strOtherResponse = $strOtherResponse . '&accm=cp&acci=0&accv=' . $str_accv;
                }
                if($str_ds != ''){
                    $strOtherResponse = $strOtherResponse . '&ds=' . $str_ds;
                }
                if($str_dsa != ''){
                    $strOtherResponse = $strOtherResponse . '&dsa=' . $str_dsa;
                }
                if($str_dsam != ''){
                    $strOtherResponse = $strOtherResponse . '&dsam=' . $str_dsam;
                }
                if($str_sn != ''){
                    $strOtherResponse = $strOtherResponse . '&sn=' . $str_sn;
                }
                if($rs_more > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_more=' . $rs_more;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse . $strWinLine .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&reel_set='. $currentReelSet .'&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&st=rect&c='.$betline.'&sw=5&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=10&w='.$totalWin.'&s=' . $strLastReel;
                
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)  && $isNewTumb == false) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if($isNewTumb == false){
                    if( $slotEvent['slotEvent'] != 'freespin' && $scatterCount >= 3) 
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin'));
                    }
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"TumbWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin')  . ',"TumbleState":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState')  . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') .',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($slotEvent['slotEvent'] == 'freespin' && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * 80;
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
    }
}

<?php 
namespace VanguardLTE\Games\GatesofValhallaPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [3,6,3,3,7,5,9,6,4,9,4,7,8,5,7,5,8,6,4,6,4,7,8,3,8]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $strOtherResponse = '';
                $currentReelSet = 0;
                $stack = null;
                $strWinLine = '';
                $winMoney = 0;
                
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);
                    if (isset($lastEvent->serverResponse->ReplayGameLogs)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', json_decode(json_encode($lastEvent->serverResponse->ReplayGameLogs), true)); //ReplayLog
                    }
                    if (isset($lastEvent->serverResponse->TumbAndFreeStacks)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', json_decode(json_encode($lastEvent->serverResponse->TumbAndFreeStacks), true)); // FreeStack

                        $FreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                        $stack = $FreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') -1];
                    }
                    $bet = $lastEvent->serverResponse->bet;
                    $strWinLine = $lastEvent->serverResponse->WinLine;
                    $winMoney = $lastEvent->serverResponse->WinMoney;
                }
                else
                {
                    $bet = '50.00';
                }
                $spinType = 's';
                $accv = 0;
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if(isset($stack)){
                    $str_tmb = $stack['tmb'];
                    $str_sts = $stack['sts'];
                    $str_sty = $stack['sty'];
                    $str_slm_lmi = $stack['slm_lmi'];
                    $str_slm_lmv = $stack['slm_lmv'];
                    $accv = $stack['accv'];
                    $arr_slm_mp = [];
                    if($stack['slm_mp'] != ''){
                        $arr_slm_mp = explode(',', $stack['slm_mp']);
                    }
                    $arr_slm_mv = [];
                    if($stack['slm_mv'] != ''){
                        $arr_slm_mv = explode(',', $stack['slm_mv']);
                    }
                    $currentReelSet = $stack['reel_set'];
                    if($str_sts != ''){
                        $strOtherResponse = $strOtherResponse . '&sts=' . $str_sts;
                    }
                    if($str_sty != ''){
                        $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                    }
                    if($str_tmb != ''){
                        $strOtherResponse = $strOtherResponse . '&tmb=' . $str_tmb;
                    }
                    if($str_slm_lmi != ''){
                        $strOtherResponse = $strOtherResponse . '&slm_lmi=' . $str_slm_lmi;
                    }
                    if($str_slm_lmv != ''){
                        $strOtherResponse = $strOtherResponse . '&slm_lmv=' . $str_slm_lmv;
                    }
                    if(count($arr_slm_mp) > 0){
                        $strOtherResponse = $strOtherResponse . '&slm_mp=' . implode(',', $arr_slm_mp);
                    }
                    if(count($arr_slm_mv) > 0){
                        $strOtherResponse = $strOtherResponse . '&slm_mv=' . implode(',', $arr_slm_mv);
                    }
                    $strOtherResponse = $strOtherResponse . '&action=doSpin';
                }else{
                    $strOtherResponse = $strOtherResponse . '&def_s=3,6,3,3,7,5,9,6,4,9,4,7,8,5,7,5,8,6,4,6,4,7,8,3,8';
                }
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 )
                {
                    $fs = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                    // if($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') > 0){
                    //     $fs -= 1;
                    // }
                    $strOtherResponse = $strOtherResponse . '&fs=' . $fs . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&fsmul=1';

                    $strOtherResponse = $strOtherResponse . '&accm=cp&acci=0&accv='. $accv .'&wmt=pr2&wmv='. $accv;
                    if($accv > 1){
                        $strOtherResponse = $strOtherResponse . '&gwm=' . $accv;
                    }
                }
                    
                if($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') > 0){
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }
                if($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') > 0){
                    $strOtherResponse = $strOtherResponse.'&rs=mc&tmb_win='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbWin').'&rs_p='.($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') - 1).'&rs_c=1&rs_m=1&w='.$winMoney;                    
                }else{
                    $strOtherResponse = $strOtherResponse . '&w=0';
                }
                $Balance = $slotSettings->GetBalance();     
                $response = 'balance='. $Balance .'&cfgs=1&ver=2&index=1&balance_cash='. $Balance .'&reel_set_size=3&reel_set='. $currentReelSet . $strWinLine .'&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0~15,12,10,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&gameInfo={rtps:{purchase:"96.60",regular:"96.46"},props:{max_rnd_sim:"1",max_rnd_hr:"5524862",max_rnd_win:"10000"}}&wl_i=tbm~10000&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime='. floor(microtime(true) * 1000) .'&sa=3,6,5,5,6&sb=3,7,3,4,7&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=50.00&sh=5&wilds=2~1500,100,20,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='. $bet .'&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;1500,100,20,0,0;400,50,10,0,0;200,25,10,0,0;100,20,6,0,0;70,10,4,0,0;50,10,4,0,0;50,10,4,0,0&l=20&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=3,3,7,6,6,3,6,4,1,7,7,8,5,6,7,2,6,8,6,5,2,5,6,6,4,5,8,5,9,5,3,9,5,7,8,3,4,3,6,8,9,9,5,5,6,9,7,6,8,7,5,7,9,4,6,9,6,4,8,6,9,7,8,1,9,7,6,7,6,1,9,5,3,5,8,8,5,4,4,4,8,9,6,7,8,6,9,7,8,6,4,8,6,4,7,3,8,4,8,5,8,4,9,4,8,5,5,6,6,7,7,5,3,6,9,4,6,4,8,5,3,7,9,8,8,7,8,6,6,9,5,4,6,9,4,3,3,9,8,7,6,3,5,5,7,6,4,6,8,5,6,8,7,4,5,6,8,4,8,5,5,5,8,6,4,6,5,9,7,5,3,4,9,4,5,3,8,5,3,4,5,6,4,7,6,8,3,3,6,4,9,6,5,8,5,5,9,6,6,4,8,7,5,4,8,4,4,6,8,5,5,6,6,3,4,5,7,8,3,5,3,7,5,6,6,9,7,9,6,7,4,9,6,8,7,3,9,2,8,9,4,7~3,9,3,7,7,3,9,9,6,5,8,8,6,9,9,8,7,7,9,3,9,7,9,6,7,7,4,7,8,1,3,8,7,3,9,3,7,9,9,6,6,4,7,3,7,9,9,4,9,9,8,7,8,9,4,9,5,3,7,7,5,9,8,5,6,7,9,5,9,3,7,7,4,8,5,7,5,2,8,4,4,7,7,8,9,1,5,7,9,7,5,7,4,6,5,7,8,4,3,8,8,7,7,3,5,5,7,6,7,4,1,6,7,9,9,8,8,6,3,7,4,7,8,7,9,9,9,4,9,3,8,7,9,5,6,9,8,9,9,7,3,5,7,8,7,6,8,3,3,9,7,9,2,8,7,7,3,6,8,5,7,9,9,7,9,7,9,9,7,6,7,7,3,9,7,9,9,7,3,9,9,7,9,6,6,9,9,8,9,7,7,2,3,3,9,7,7,9,4,9,8,4,9,7,7,9,7,5,5,9,9,6,7,9,3,9,9,4,7,9,9,8,7,9,9,7,9,4,9,5,9,7,7,8,9,8,9,7,7,8,9,6,9,3,9,7,8,7,3,3,8~9,7,7,3,4,6,5,3,8,6,5,5,6,9,3,8,5,3,7,9,4,9,8,7,5,4,5,3,2,7,6,9,4,9,5,7,8,8,5,9,5,9,4,8,8,5,3,8,5,4,8,3,4,8,5,4,5,3,7,4,5,8,6,8,7,8,6,8,4,8,3,9,9,4,9,6,8,6,5,7,5,4,8,6,8,4,7,5,1,4,8,7,9,7,6,3,6,7,9,6,1,4,9,9,7,8,8,5,8,6,8,6,3,8,5,8,9,5,8,2,4,4,7,9,4,6,9,6,7,4,4,5,8,4,5,8,7,4,6,9,8,9,3,5,4,7,4,3,5,4,5,6,6,5,4,9~4,7,9,3,9,7,7,6,4,6,9,7,4,6,5,7,9,7,6,3,7,7,8,9,3,5,8,6,7,9,4,9,8,9,9,4,9,2,7,3,9,4,3,3,9,9,3,4,8,7,6,7,9,7,4,3,9,8,9,5,9,9,3,3,9,6,6,8,4,7,9,7,1,7,6,9,7,6,9,7,3,3,9,7,7,9,1,9,8,7,7,7,7,7,4,3,5,7,5,9,9,6,9,7,9,6,9,7,2,9,9,3,7,7,8,7,8,7,9,6,6,7,9,8,3,8,9,9,3,7,4,7,3,3,5,3,9,5,7,9,4,8,7,6,7,4,7,3,9,7,3,9,3,3,9,3,5,5,3,8,7,5,5,9,6,7,9,7,3,9,7,9,5,8,7,9,7,3,7,3,8,7,5,9,5,9,7,3,4~4,7,6,7,4,4,7,5,8,5,7,3,4,7,7,5,7,9,8,9,6,4,4,8,7,5,6,5,7,6,8,6,9,6,5,6,6,5,9,9,7,3,4,2,3,4,6,4,4,8,6,6,4,6,6,5,8,4,8,7,8,8,6,5,8,4,5,9,5,8,7,7,5,6,1,4,4,5,5,7,8,3,5,6,6,6,5,6,9,7,5,6,4,3,6,8,8,4,4,9,9,6,4,6,7,8,7,9,9,3,4,7,9,6,7,7,3,4,7,6,9,8,3,4,3,8,7,6,3,9,6,1,4,9,6,8,3,1,8,4,9,7,4,5,8,4,3,5,4,8,2,9,6,8,9,6,4,2,6,4,8,4,8,8,5,8,6,4,5,8,6,5,5,5,3,9,9,6,8,5,4,4,6,6,3,5,3,8,8,6,5,9,3,5,9,8,7,6,6,4,6,5,4,6,4,6,5,6,9,4,4,9,6,5,5,9,7,3,5,5,6,6,8,9,7,8,8,9,4,5,8,6,8,6,4,8,8,5,6,7,6,9,4,6,3,4,9,8,7,6,8,5,5,7,5,8,3,8,5,6&s='.$lastReelStr.'&accInit=[{id:0,mask:"cp;mp"}]&reel_set2=5,7,8,3,9,8,8,7,6,6,4,4,7,8,5,3,5,7,8,6,4,4,7,4,1,6,7,6,3,8,5,4,5,7,9,6,7,8,4,6,3,9,9,4,8,3,8,8,5,5,6,3,9,5,7,5,5,6,6,4,5,6,4,3,6,8,5,7,1,6,5,7,7,8,8,5,4,5,3,4,7,9,8,3,9,5,8,7,6,7,6,5,3,5,9,4,5,5,5,9,6,5,8,6,5,5,6,7,8,9,7,9,5,9,6,8,6,5,6,4,4,9,4,6,5,9,3,4,7,3,7,6,8,1,4,5,4,7,8,9,5,4,8,2,8,5,5,6,8,8,3,6,3,8,4,2,3,6,6,3,6,6,4,7,9,8,6,5,5,7,4,8,4,8,5,6,3,5,9,2,8,8,3,4,9,6,9,6,3,7,9,9,6,5,6,8,9,9,7,7~5,8,3,9,7,8,3,9,9,5,2,6,2,7,9,7,9,7,9,6,9,9,6,3,7,5,9,7,8,7,7,5,5,8,9,7,7,6,6,7,7,8,3,5,9,7,3,6,7,3,3,9,6,4,8,5,9,3,6,7,7,1,9,8,8,7,3,7,3,9,4,7,9,9,4,4,7,9,7,7,9,8,9,8,8,2,9,3,8,7,8,9,9,7,5,9,6,7,3,4,7,8,3,9,9,3,7,1,4,3,4,8,8,7,6,8,5,9,4,8,7,7,5,2,9,9,9,1,7,7,8,7,3,9,9,5,7,5,9,8,9,5,7,7,5,8,8,7,9,4,9,6,5,9,6,7,9,4,9,7,7,9,8,9,8,6,7,4,7,7,9,9,7,7,3,8,3,8,9,9,7,9,7,7,4,9,5,8,9,7,9,8,8,9,9,8,3,9,9,3,7,7,9,8,9,7,3,6,9,9,7,9,9,7,7,8,5,8,9,8,4,8,7,7,3,9,6,9,5,7,3,7,3,6,9,3,2,7,2,7,8,3,8,9,5,5,9,7,5,7,7,9,9,8~5,6,4,8,8,5,6,9,5,5,6,8,8,7,7,9,9,8,8,6,8,9,5,5,7,6,4,7,4,6,4,8,4,3,6,4,6,9,4,8,9,2,3,5,5,4,5,3,7,6,5,8,7,6,9,9,5,7,9,8,4,4,5,4,7,5,4,4,5,6,1,9,4,4,5,3,7,7,3,3,6,4,5,8,8,5,8,6,5,3,5,6,2,7,5,7,4,7,8,5,6,8,3,4,7,5,3,8,9,9,4,8,8,9,6,7,6,9,2,7,4,4,5,6,3,4,6,9,4,8,9,4,3,8,8,9,6,8,5,5,4,3,4,5,2,8,8,7,8,7,4,9,8,6,6,5,1,7,5,4,9,8,4,9,6,9,5,6,5,9,3,5,5,8,2,8,6,9,6,8,5,4,3,3,8,4,6,8,9,8,9,8,7,4,7,8,3,6,6,8,7,9,7,6,8,3,9,4,5,9,9,5,3,8,7,5,8,5,1,5,4,8,9,6,4,3,4,9,5,6,5,4,9,7,3,8~9,7,9,8,5,9,2,8,4,7,9,7,3,9,7,5,9,7,9,3,3,1,7,9,6,7,7,4,7,8,7,2,9,9,7,9,9,3,3,2,6,7,9,9,7,9,9,3,7,8,9,9,5,7,4,8,6,3,5,7,8,9,7,7,5,3,7,7,3,9,6,9,3,7,6,7,9,3,6,7,7,6,8,6,6,3,4,5,7,5,7,7,9,7,3,5,3,6,6,2,5,2,4,8,6,4,8,9,9,6,8,7,9,7,3,9,7,9,9,7,9,7,9,5,1,4,3,9,3,7,9,3,9,5,7,9,7,3,9,3,9,3,7,9,4,3,9,3,9,9,3,7,1,9,8,7,9,3,7,3,8,9,3,3,5,6,9,4,5,9,7,8,4,9,9,8,3,7,3,7,7,9,6,6,8,9,9,7,9,8,5,4,3,7,3,4,4,8,9,7,6,8,4,7,9,9,6,8,9,6,4,9,7,3,3,5,7,9,5,4,9,4,3,7,3,8,9,8,9,6,7,7,2,9,3,7,8,9,7,5,7,3,7,7,4,7,3,7,3,9,7,7,9,5,9,6,3,9,9,6,7,4,9,4,3,7~3,8,9,1,4,8,5,9,4,7,9,4,8,4,6,7,4,9,6,5,6,7,8,4,8,6,7,6,5,5,9,6,5,7,4,8,6,8,8,4,9,7,7,4,5,8,6,8,8,9,4,6,8,5,8,6,5,8,5,6,4,9,7,6,9,6,3,3,6,2,8,3,4,5,5,5,6,4,6,5,6,4,5,6,7,6,6,8,7,3,8,8,7,5,5,6,5,5,9,8,7,8,5,5,9,5,7,9,4,3,8,4,6,8,5,6,9,7,9,9,5,5,8,8,7,8,2,4,5,8,9,7,4,3,3,6,3,6,7,4,8,8,3,6,6,3,4,9,5,1,5&t=stack&reel_set1=4,1,2,6,4,3,7,5,6,9,6,4,8,8,6,6,4,4,8,4,9,4,7,6,4,5,8,6,4,9,3,4,7,5,3,5,5,9,5,8,5,8,7,8,6,6,7,3,3,7,6,5,6,8,8,4,8,5,8,5,4,6,4,8,4,5,7,9,8,7,7,6,9,6,8,5,6,9,3,5,9,4,9,8,6,7,5,9,5,5,6,4,8,1,5,6,6,8,6,6,9,5,5,5,6,5,3,7,8,9,8,9,8,4,5,6,7,5,6,7,6,5,5,4,5,5,7,4,3,5,7,3,7,8,5,7,7,5,9,3,8,8,6,8,6,7,6,6,7,5,6,9,6,2,9,4,5,8,1,3,4,4,3,7,3,3,7,6,5,8,8,3,7,8,7,4,7,9,3,2,9,5,9,8,6,9,8,5,5,9,8,8,5,9,6,5,5,6,6,4,6,4,9,3,3,6,3~9,6,8,7,7,4,8,7,9,7,8,9,7,7,3,8,6,4,7,7,8,7,4,5,3,8,7,9,6,5,4,7,3,8,9,5,5,9,6,9,7,9,7,6,9,7,9,7,4,2,7,3,5,7,7,9,9,7,7,9,6,9,8,9,6,8,7,9,9,3,9,6,9,6,8,9,7,7,8,7,3,3,9,9,8,3,7,1,9,6,9,1,2,7,8,5,3,1,7,7,9,9,9,4,8,5,9,3,7,8,7,2,7,8,9,7,5,9,4,9,8,3,3,9,9,7,9,9,5,9,9,7,4,7,8,9,3,7,9,5,8,9,3,9,4,2,8,7,4,5,5,9,7,7,3,5,5,3,9,8,3,6,9,6,4,7,9,7,6,4,7,7,4,3,9,7,3,7,9,8,7,6,9,3,9,6,8,9,8,7,9,7,7,9,7,8,9,9,4,7,9,4,2,5,3,7~8,7,6,5,9,6,8,3,6,5,5,4,5,6,6,5,9,7,5,9,4,8,4,5,6,4,4,2,5,9,4,8,5,6,9,7,5,9,6,2,3,8,5,4,4,6,9,7,5,1,8,9,3,4,6,9,8,4,1,6,5,9,5,3,3,9,8,9,8,6,7,9,8,7,5,4,6,8,7,8,8,4,9,6,8,8,6,4,7,4,7,8,4,5,8,5,4,8,5,4,3,9,7,3,6,6,8,6,5,9,7,7,6,8,7,4,4,3,9,2,8,8,2,5,5,3,8,3,5,9,4,4,3,3,8,7,8,7,9,4,4,9,7,3,5,7,8,6~7,5,6,3,7,7,8,6,6,7,7,9,3,7,5,9,7,3,6,3,6,6,7,3,6,7,8,7,5,7,6,2,6,4,9,9,6,9,7,6,7,3,1,8,9,9,6,7,7,9,9,8,9,7,9,8,3,8,7,9,3,7,4,9,4,4,7,9,7,9,9,3,3,8,7,4,7,8,9,7,3,5,9,3,6,3,7,3,7,8,9,5,9,6,9,4,9,7,9,9,5,3,9,7,9,8,5,2,9,8,3,9,3,9,7,7,9,9,8,5,3,6,7,9,7,5,4,5,3,9,9,7,8,1,7,7,3,3,4,3,7,9,4,9,3,9,3,3,4,2,3,3,7,7,9,9,7,9,2,7,9,5,4,9,4,3,9,9,8,3,3,7,8,7,7,8,9,5,4,7,9,7,9,9,7,9,4,6~4,4,8,9,3,4,9,7,8,7,8,6,9,6,7,6,8,7,3,6,4,7,7,3,8,6,4,6,9,9,6,8,7,5,6,5,7,4,5,7,5,9,7,9,9,7,9,8,5,8,6,6,4,4,3,3,6,4,9,5,3,7,1,6,4,4,6,4,8,4,8,6,6,7,5,3,6,5,9,5,4,3,5,4,3,6,4,7,4,3,4,5,4,6,4,8,6,9,8,8,4,4,8,8,7,4,9,6,9,4,6,5,6,8,6,5,4,8,8,4,6,5,3,4,5,9,5,5,4,7,6,6,8,6,5,5,5,4,7,8,3,6,5,4,5,5,8,6,3,7,6,5,6,5,9,9,8,8,7,6,4,7,9,6,4,6,4,9,6,8,8,4,8,8,9,9,5,4,6,9,6,5,7,8,7,7,3,2,5,4,6,6,5,4,6,8,5,5,8,6,9,8,4,6,5,8,5,2,8,6,4,9,9,6,5,7,7,3,5,8,8,5,4,5,6,7,3,8,4,8,4,2,3,7,8,5,9,3,9,6,1,5,6,7,7,6,5,6,3,6,6,4,8,1,3,8,5,9,8,9,8,8,7,7,6,4,6,8,9,9,4,8&purInit=[{type:"fs",bet:2000}]&total_bet_min=10.00';
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
                $linesId[0] = [[0,0],[1,0],[2,0],[3,0],[4,0]];
                $linesId[1] = [[0,1],[1,1],[2,1],[3,1],[4,1]];
                $linesId[2] = [[0,2],[1,2],[2,2],[3,2],[4,2]];
                $linesId[3] = [[0,3],[1,3],[2,3],[3,3],[4,3]];
                $linesId[4] = [[0,4],[1,4],[2,4],[3,4],[4,4]];
                $linesId[5] = [[0,0],[0,1],[0,2],[0,3],[0,4]];
                $linesId[6] = [[1,0],[1,1],[1,2],[1,3],[1,4]];
                $linesId[7] = [[2,0],[2,1],[2,2],[2,3],[2,4]];
                $linesId[8] = [[3,0],[3,1],[3,2],[3,3],[3,4]];
                $linesId[9] = [[4,0],[4,1],[4,2],[4,3],[4,4]];
                $pur = -1;
                if(isset($slotEvent['pur'])){
                    $pur = $slotEvent['pur'];
                }
                $slotEvent['slotBet'] = $slotEvent['c'];
                $slotEvent['slotLines'] = 20;
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

                // $winType = 'win';

                $allBet = $betline * $lines;
                if($pur >= 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $betline * $lines * 100;
                }
                $tumbAndFreeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'freespin' || $isTumb == true){
                    if($slotEvent['slotEvent'] == 'freespin' && $isTumb == false){
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    }
                    $leftFreeGames = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') - $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame'); 
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.1f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '628' . substr($roundstr, 3, 8). '023';
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $leftFreeGames = 0;

                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []);
                }
                
                $wild = '2';
                $scatter = '1';
                $empty = '15';
                $Balance = $slotSettings->GetBalance();
                $totalWin = 0;
                $this->strCheckSymbol = '';
                $this->wildMul = 0;
                $this->winLines = [];
                $bonusMpl = 1;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $str_tmb = '';
                $str_sts = '';
                $str_sty = '';
                $str_slm_lmi = '';
                $str_slm_lmv = '';
                $arr_slm_mp = [];
                $arr_slm_mv = [];
                $accv = 0;
                $subScatterReel = null;
                if($slotEvent['slotEvent'] == 'freespin' || $isTumb == true){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_tmb = $stack['tmb'];
                    $str_sts = $stack['sts'];
                    $str_sty = $stack['sty'];
                    $str_slm_lmi = $stack['slm_lmi'];
                    $str_slm_lmv = $stack['slm_lmv'];
                    $accv = $stack['accv'];
                    if($stack['slm_mp'] != ''){
                        $arr_slm_mp = explode(',', $stack['slm_mp']);
                    }
                    if($stack['slm_mv'] != ''){
                        $arr_slm_mv = explode(',', $stack['slm_mv']);
                    }
                    $currentReelSet = $stack['reel_set'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $pur, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $lastReel = explode(',', $stack[0]['reel']);
                    $str_tmb = $stack[0]['tmb'];
                    $str_sts = $stack[0]['sts'];
                    $str_sty = $stack[0]['sty'];
                    $str_slm_lmi = $stack[0]['slm_lmi'];
                    $str_slm_lmv = $stack[0]['slm_lmv'];
                    $accv = $stack[0]['accv'];
                    if($stack[0]['slm_mp'] != ''){
                        $arr_slm_mp = explode(',', $stack[0]['slm_mp']);
                    }
                    if($stack[0]['slm_mv'] != ''){
                        $arr_slm_mv = explode(',', $stack[0]['slm_mv']);
                    }
                    $currentReelSet = $stack[0]['reel_set'];
                }

                $reels = [];
                $wildReel = [];
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;
                for($i = 0; $i < 5; $i++){
                    $reels[$i] = [];
                    $wildReel[$i] = [];
                    for($j = 0; $j < 5; $j++){
                        $reels[$i][$j] = $lastReel[$j * 5 + $i];
                        $wildReel[$i][$j] = 0;
                        if($lastReel[$j * 5 + $i] == $scatter){
                            $scatterCount++;
                            $scatterPoses[] = $j * 5 + $i;
                        }else if($lastReel[$j * 5 + $i] == $wild){
                            for($k=0; $k < count($arr_slm_mp); $k++){
                                if(($j * 5 + $i) == $arr_slm_mp[$k]){                                
                                    $wildReel[$i][$j] = $arr_slm_mv[$k];
                                }
                            }
                        }
                    }
                }

                $_lineWinNumber = 1;
                $_obf_winCount = 0;
                $isNewTumb = false;
                for( $k = 0; $k < 10; $k++ ) 
                {
                    $_lineWin = '';
                    $firstEle = $reels[$linesId[$k][0][0]][$linesId[$k][0][1]];
                    $lineWinNum[$k] = 1;
                    $lineWins[$k] = 0;
                    $winSymbol = 0;
                    $mul = $wildReel[$linesId[$k][0][0]][$linesId[$k][0][1]];                    
                    for($j = 1; $j < 5; $j++){
                        $ele = $reels[$linesId[$k][$j][0]][$linesId[$k][$j][1]];
                        if($wildReel[$linesId[$k][$j][0]][$linesId[$k][$j][1]] > 0){
                            $mul += $wildReel[$linesId[$k][$j][0]][$linesId[$k][$j][1]];
                        }
                        if($firstEle == $wild){
                            $firstEle = $ele;
                            $lineWinNum[$k] = $lineWinNum[$k] + 1;
                        }else if(($ele == $firstEle || $ele == $wild) && $scatter != $firstEle){
                            $lineWinNum[$k] = $lineWinNum[$k] + 1;
                            if($j == 4){
                                $isNewTumb = true;
                                $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                if($mul > 1){
                                    $lineWins[$k] = $lineWins[$k] * $mul;
                                }
                                if($accv > 1){
                                    $lineWins[$k] = $lineWins[$k] * $accv;
                                }
                                $totalWin += $lineWins[$k];
                                $_obf_winCount++;
                                $diffX = $j + 1 - $lineWinNum[$k];
                                $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                for($kk = $diffX; $kk < $lineWinNum[$k] + $diffX; $kk++){
                                    $strWinLine = $strWinLine . '~' . ($linesId[$k][$kk][1] * 5 + $linesId[$k][$kk][0]);
                                }
                                $winSymbol = $firstEle;
                            }
                        }else{
                            if($slotSettings->Paytable[$firstEle][$lineWinNum[$k]] > 0){
                                $isNewTumb = true;
                                $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                $winSymbol = $firstEle;
                                if($mul > 1){
                                    $lineWins[$k] = $lineWins[$k] * $mul;
                                }
                                if($accv > 1){
                                    $lineWins[$k] = $lineWins[$k] * $accv;
                                }
                                $totalWin += $lineWins[$k];
                                $_obf_winCount++;
                                $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                $diffX = $j - $lineWinNum[$k];
                                for($kk = $diffX; $kk < $lineWinNum[$k] + $diffX; $kk++){
                                    $strWinLine = $strWinLine . '~' . ($linesId[$k][$kk][1] * 5 + $linesId[$k][$kk][0]);
                                }   
                                break;
                            }else{                 
                                $lineWinNum[$k] = 0;
                                if($j <= 2){
                                    $firstEle = $reels[$linesId[$k][$j][0]][$linesId[$k][$j][1]];
                                    $mul = $wildReel[$linesId[$k][$j][0]][$linesId[$k][$j][1]];
                                    $lineWinNum[$k] = 1;
                                    for($kk=($j-1); $kk>=0; $kk--){
                                        if($reels[$linesId[$k][$kk][0]][$linesId[$k][$kk][1]] == $wild){
                                            $lineWinNum[$k]++;
                                            if($wildReel[$linesId[$k][$kk][0]][$linesId[$k][$kk][1]] > 0){
                                                $mul += $wildReel[$linesId[$k][$kk][0]][$linesId[$k][$kk][1]];
                                            }
                                        }else{
                                            break;
                                        }
                                    }
                                }else{
                                    break;
                                }                   
                            }
                        }
                    }

                    $_lineWin = '';
                    $firstEle = $reels[$linesId[$k][4][0]][$linesId[$k][4][1]];
                    $lineWinNum[$k] = 1;
                    $lineWins[$k] = 0;
                    $mul = $wildReel[$linesId[$k][4][0]][$linesId[$k][4][1]];                    
                    for($j = 3; $j >= 0; $j--){
                        $ele = $reels[$linesId[$k][$j][0]][$linesId[$k][$j][1]];
                        if($wildReel[$linesId[$k][$j][0]][$linesId[$k][$j][1]] > 0){
                            $mul += $wildReel[$linesId[$k][$j][0]][$linesId[$k][$j][1]];
                        }
                        if($firstEle == $wild){
                            $firstEle = $ele;
                            $lineWinNum[$k] = $lineWinNum[$k] + 1;
                        }else if(($ele == $firstEle || $ele == $wild) && $scatter != $firstEle){
                            $lineWinNum[$k] = $lineWinNum[$k] + 1;
                            if($j == 0 && $winSymbol != $firstEle){
                                $isNewTumb = true;
                                $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                if($mul > 1){
                                    $lineWins[$k] = $lineWins[$k] * $mul;
                                }
                                if($accv > 1){
                                    $lineWins[$k] = $lineWins[$k] * $accv;
                                }
                                $totalWin += $lineWins[$k];
                                $_obf_winCount++;
                                $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                for($kk = $j; $kk < $lineWinNum[$k] + $j; $kk++){
                                    $strWinLine = $strWinLine . '~' . ($linesId[$k][$kk][1] * 5 + $linesId[$k][$kk][0]);
                                }
                            }
                        }else{
                            if($slotSettings->Paytable[$firstEle][$lineWinNum[$k]] > 0 && $winSymbol != $firstEle){
                                $isNewTumb = true;
                                $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                if($mul > 1){
                                    $lineWins[$k] = $lineWins[$k] * $mul;
                                }
                                if($accv > 1){
                                    $lineWins[$k] = $lineWins[$k] * $accv;
                                }
                                $totalWin += $lineWins[$k];
                                $_obf_winCount++;
                                $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                for($kk = $j + 1; $kk < $lineWinNum[$k] + $j + 1; $kk++){
                                    $strWinLine = $strWinLine . '~' . ($linesId[$k][$kk][1] * 5 + $linesId[$k][$kk][0]);
                                }   
                                break;
                            }else{                 
                                $lineWinNum[$k] = 0;
                                if($j >= 2){
                                    $firstEle = $reels[$linesId[$k][$j][0]][$linesId[$k][$j][1]];
                                    $mul = $wildReel[$linesId[$k][$j][0]][$linesId[$k][$j][1]];
                                    $lineWinNum[$k] = 1;

                                    // if($j < 4 && $reels[$linesId[$k][$j + 1][0]][$linesId[$k][$j + 1][1]] == $wild){
                                    //     $lineWinNum[$k]++;
                                    //     if($wildReel[$linesId[$k][$j + 1][0]][$linesId[$k][$j + 1][1]] > 1){
                                    //         $mul += $wildReel[$linesId[$k][$j + 1][0]][$linesId[$k][$j + 1][1]];
                                    //     }
                                    // }
                                }else{
                                    break;
                                }                   
                            }
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
                if($isNewTumb == true){
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') + 1);
                    $isState = false;
                    $spinType = 's';
                }else{
                    if( $scatterCount >= 3) 
                    {
                        if($slotEvent['slotEvent'] != 'freespin'){
                            $freeSpins = [0,0,0,10,12,15];
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freeSpins[$scatterCount]);
                            $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                            $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                        }else{
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 10);
                        }
                    }
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') + $totalWin);
                    $spinType = 's';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $isEnd = false;
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                    {
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        if($isNewTumb == false){
                            $spinType = 'c';
                            $isEnd = true;
                            $strOtherResponse = $strOtherResponse . '&fsend_total=1';
                        }else{
                            $strOtherResponse = $strOtherResponse . '&fsend_total=0';
                            $isState = false;
                            $spinType = 's';
                        }
                    }
                    else
                    {
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 's';
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                    }
                    if($isNewTumb == false && $scatterCount >= 3){
                        $strOtherResponse = $strOtherResponse . '&fsmore=10';
                    }
                    $strOtherResponse = $strOtherResponse . '&accm=cp&acci=0&accv='. $accv .'&wmt=pr2&wmv='. $accv;
                    if($accv > 1){
                        $strOtherResponse = $strOtherResponse . '&gwm=' . $accv;
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') + $totalWin);
                    if($scatterCount >= 3 && $isNewTumb == false){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1';
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&purtr=1&puri=' . $pur;
                    }
                }
                if($isNewTumb == true){
                    $isState = false;
                    $strOtherResponse = $strOtherResponse . '&rs=mc&tmb_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') . '&rs_p=' . ($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') - 1) . '&rs_c=1&rs_m=1';
                }
                else if($isTumb == true){
                    if($slotEvent['slotEvent'] != 'freespin' && $scatterCount < 3){
                        $spinType = 'c';
                        $isState = true;
                    }
                    $strOtherResponse = $strOtherResponse.'&tmb_res='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbWin').'&rs_t='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbleState').'&tmb_win='.($slotSettings->GetGameData($slotSettings->slotId . 'TumbWin'));
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', 0);
                }
                if($str_sts != ''){
                    $strOtherResponse = $strOtherResponse . '&sts=' . $str_sts;
                }
                if($str_sty != ''){
                    $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                }
                if($str_tmb != ''){
                    $strOtherResponse = $strOtherResponse . '&tmb=' . $str_tmb;
                }
                if($str_slm_lmi != ''){
                    $strOtherResponse = $strOtherResponse . '&slm_lmi=' . $str_slm_lmi;
                }
                if($str_slm_lmv != ''){
                    $strOtherResponse = $strOtherResponse . '&slm_lmv=' . $str_slm_lmv;
                }
                if(count($arr_slm_mp) > 0){
                    $strOtherResponse = $strOtherResponse . '&slm_mp=' . implode(',', $arr_slm_mp);
                }
                if(count($arr_slm_mv) > 0){
                    $strOtherResponse = $strOtherResponse . '&slm_mv=' . implode(',', $arr_slm_mv);
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse . $strWinLine .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&reel_set='. $currentReelSet .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=5&st=rect&c='.$betline.'&sw=5&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&w='.$totalWin.'&s=' . $strLastReel;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)  && $isNewTumb == false) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"TumbWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"TumbleState":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState')  . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')  . ',"WinMoney":' . $totalWin . ',"WinLine":"' . $strWinLine . '","TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($slotEvent['slotEvent'] == 'freespin' && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * 100;
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
        
        public function findZokbos($reels, $i, $j, $firstSymbol){
            $wild = '2';
            $bPathEnded = true;
            if($firstSymbol == $wild){
                return;
            }
            if($i < 6 && ($firstSymbol == $reels[$i + 1][$j] || $reels[$i + 1][$j] == $wild) && (strpos($this->strCheckSymbol, '<'.($i + 1) . '-' . $j.'>') == false || $reels[$i + 1][$j] == $wild)){
                $strCoord = '<'.($i + 1) . '-' . $j.'>';
                $bChecked = false;
                if($reels[$i + 1][$j] == $wild){
                    $strCoord = '<'.$firstSymbol.'-'.($i + 1) . '-' . $j.'>';
                    if(strpos($this->strCheckSymbol, $strCoord)){
                        $bChecked = true;
                    }
                }
                if(!$bChecked){
                    $this->repeatCount++;
                    $this->strWinLinePos = $this->strWinLinePos . '~'.($j * 7 + $i + 1);
                    $this->strCheckSymbol = $this->strCheckSymbol . ';' . $strCoord;
                    $this->findZokbos($reels, $i + 1, $j, $firstSymbol);
                    $bPathEnded = false;
                }
                
            }

            if($j < 6 && ($firstSymbol == $reels[$i][$j + 1] || $reels[$i][$j + 1] == $wild ) && (strpos($this->strCheckSymbol, '<'.$i . '-' . ($j + 1).'>') == false || $reels[$i][$j + 1] == $wild )){
                $strCoord = '<'.$i . '-' . ($j + 1).'>';
                $bChecked = false;
                if($reels[$i][$j + 1] == $wild ){
                    $strCoord = '<'.$firstSymbol.'-'.$i . '-' . ($j + 1).'>';
                    if(strpos($this->strCheckSymbol, $strCoord)){
                        $bChecked = true;
                    }
                }
                if(!$bChecked){
                    $this->repeatCount++;
                    $this->strWinLinePos = $this->strWinLinePos . '~'.(($j + 1) * 7 + $i);
                    $this->strCheckSymbol = $this->strCheckSymbol . ';' . $strCoord;
                    $this->findZokbos($reels, $i, $j + 1, $firstSymbol);
                    $bPathEnded = false;
                }
                
            }

            if($i > 0 && ($firstSymbol == $reels[$i - 1][$j] || $reels[$i - 1][$j] == $wild ) && (strpos($this->strCheckSymbol, '<'.($i - 1) . '-' . $j.'>') == false|| $reels[$i - 1][$j] == $wild )){
                $strCoord = '<'.($i - 1) . '-' . $j.'>';
                $bChecked = false;
                if($reels[$i - 1][$j] == $wild){
                    $strCoord = '<'.$firstSymbol.'-'.($i - 1) . '-' . $j.'>';
                    if(strpos($this->strCheckSymbol, $strCoord)){
                        $bChecked = true;
                    }
                }
                if(!$bChecked){
                    $this->repeatCount++;
                    $this->strWinLinePos = $this->strWinLinePos . '~'.($j * 7 + $i - 1);
                    $this->strCheckSymbol = $this->strCheckSymbol . ';' . $strCoord;
                    $this->findZokbos($reels, $i - 1, $j, $firstSymbol);
                    $bPathEnded = false;
                }
                
            }

            if($j > 0 && ($firstSymbol == $reels[$i][$j - 1] || $reels[$i][$j - 1] == $wild) && (strpos($this->strCheckSymbol, '<'.$i . '-' . ($j - 1).'>') == false || $reels[$i][$j - 1] == $wild)){
                $strCoord =  '<'.$i . '-' . ($j - 1).'>';
                $bChecked = false;
                if($reels[$i][$j - 1] == $wild){
                    $strCoord = '<'.$firstSymbol.'-'.$i . '-' . ($j - 1).'>';
                    if(strpos($this->strCheckSymbol, $strCoord)){
                        $bChecked = true;
                    }
                }
                if(!$bChecked){
                    $this->repeatCount++;
                    $this->strWinLinePos = $this->strWinLinePos . '~'.(($j - 1) * 7 + $i);
                    $this->strCheckSymbol = $this->strCheckSymbol . ';' .$strCoord;
                    $this->findZokbos($reels, $i, $j - 1, $firstSymbol);
                    $bPathEnded = false;
                }
            }
        }
    }
}

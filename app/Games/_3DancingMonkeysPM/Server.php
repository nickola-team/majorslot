<?php 
namespace VanguardLTE\Games\_3DancingMonkeysPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 30);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [6,10,9,4,11,3,12,5,3,12,6,9,10,8,10,21,21,21,21,21,21,21,21,21,21]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $strOtherResponse = '';
                $currentReelSet = 0;
                $stack = null;
                $strWinLine = '';
                $spinType = 's';
                
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);
                    if (isset($lastEvent->serverResponse->ReplayGameLogs)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', json_decode(json_encode($lastEvent->serverResponse->ReplayGameLogs), true)); //ReplayLog
                    }
                    if (isset($lastEvent->serverResponse->TumbAndFreeStacks)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', json_decode(json_encode($lastEvent->serverResponse->TumbAndFreeStacks), true)); // FreeStack

                        $FreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                        if($slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') > 0){
                            $stack = $FreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') -1];
                        }
                    }
                    $bet = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $bet = '30.00';
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                $fsmore = 0;
                if(isset($stack)){
                    $str_initReel = $stack['is'];
                    $str_accm = $stack['accm'];
                    $str_accv = $stack['accv'];
                    $str_acci = $stack['acci'];
                    $str_slm_mp = $stack['slm_mp'];
                    $str_slm_mv = $stack['slm_mv'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $str_mo_wpos = $stack['mo_wpos'];
                    $mo_tv = $stack['mo_tv'];
                    $mo_m = $stack['mo_m'];
                    $str_trail = $stack['trail'];
                    $str_stf = $stack['stf'];
                    $fsmax = $stack['fsmax'];
                    $fsmore = $stack['fsmore'];
                    $strWinLine = $stack['wlc_v'];
                    $currentReelSet = $stack['reel_set'];
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($str_trail != ''){
                        $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                    }
                    if($str_stf != ''){
                        $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                    }                    
                    if($str_accv != ''){
                        $strOtherResponse = $strOtherResponse . '&accm=' . $str_accm . '&acci=' . $str_acci . '&accv=' . $str_accv;
                    }
                    if($str_slm_mv != ''){
                        $strOtherResponse = $strOtherResponse . '&slm_mp=' . $str_slm_mp . '&slm_mv=' . $str_slm_mv;
                    }
                    if($str_mo != ''){
                        $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                    }
                    if($str_mo_wpos != ''){
                        $strOtherResponse = $strOtherResponse . '&mo_wpos=' . $str_mo_wpos;
                    }
                    if($mo_tv > 0){
                        $moneyWin = $mo_tv * $bet;
                        if($mo_m > 1){
                            $moneyWin = $moneyWin * $mo_m;
                        }
                        $strOtherResponse = $strOtherResponse . '&mo_c=1&mo_tv=' . $mo_tv . '&mo_tw=' . $moneyWin;
                        if($mo_m > 0){
                            $strOtherResponse = $strOtherResponse . '&mo_m=' . $mo_m;
                        }
                    }
                    if($strWinLine != ''){
                        $arr_lines = explode(';', $strWinLine);
                        for($k = 0; $k < count($arr_lines); $k++){
                            $arr_sub_lines = explode('~', $arr_lines[$k]);
                            $arr_sub_lines[1] = str_replace(',', '', $arr_sub_lines[1]) / $original_bet * $bet;
                            $arr_lines[$k] = implode('~', $arr_sub_lines);
                        }
                        $strWinLine = implode(';', $arr_lines);
                        $strOtherResponse = $strOtherResponse . '&wlc_v=' . $strWinLine;
                    }
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0 || $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 )
                    {
                        $fs = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                        if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) || ($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0)) 
                        {
                            $strOtherResponse = $strOtherResponse . '&fs_total='.($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 1).'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsend_total=1&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        }
                        else
                        {
                            $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $fs.'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        }
                        if($fsmore > 0){
                            $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                        }
                    }
                }
                    
                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=6,10,9,4,11,3,12,5,3,12,6,9,10,8,10,21,21,21,21,21,21,21,21,21,21&balance='. $Balance .'&cfgs=8096&ver=3&mo_s=22;23&index=1&balance_cash='. $Balance .'&mo_v=30,35,40,45,50,60,70,80,100,200,250,1000,5000;1,2,3,4,5,6,7,8,9,10,12,15,20,25,30,42&def_sb=6,12,6,6,10&reel_set_size=9&def_sa=3,11,5,8,11&reel_set=0&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"156223251",max_rnd_win:"12077"}}&wl_i=tbm~12077&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=3,11,5,8,11&sb=6,12,6,6,10&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=30.00&purInit_e=1&sh=5&wilds=2~0,0,0,0,0~1,1,1,1,1;13~0,0,0,0,0~1,1,1,1,1;14~0,0,0,0,0~1,1,1,1,1;15~0,0,0,0,0~1,1,1,1,1&bonuses=0&st=rect&c='.$bet.'&sw=5&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;200,75,30,0,0;150,50,25,0,0;70,30,20,0,0;60,25,18,0,0;50,20,15,0,0;35,15,10,0,0;20,12,8,0,0;15,8,7,0,0;12,5,3,0,0;10,5,2,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0&l=30&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=4,8,3,10,7,11,9,11,6,16,8,10,7,8,10,8,6,5,9,8,11,17,4,12,8,10,12,9,8,12,8,7,4,11,6,8,9,7,5,9,7,8,6,10,11,12,9,3,10,3,10,7,5,3,4,8,4,9,18,6,9,7,12,10,9,6,11,9,10,8,4,8,9,10,7,9,10,11,7,11,5,8,5,12,8,3,8,6,12,7,12,9,4,18,11,16,9,4,5,9,11,12,6,9,12,10,8,9,11,17,4,9,12,4,10,11,8,9,11,6,5,10~2,9,7,11,12,6,12,11,9,10,3,17,8,10,11,16,4,9,8,6,3,8,9,8,2,6,12,9,18,7,10,8,9,10,7,11,10,6,11,8,11,9,18,9,11,7,11,5,11,6,9,10,8,12,10,4,8,10,8,4,11,12,16,8,10,12,5,9,10,9,8,7,8,9,11,8,11,12,9,8,9,17,3,5,6,12,8,12,7,10,8,11,12,6,8,9,4,8,5,9,8,5,9,12,8~7,4,3,10,12,8,7,12,9,4,10,12,7,9,11,17,12,10,9,11,10,6,4,6,9,11,2,8,8,9,7,11,16,8,8,3,18,9,8,10,3,5,6,9,12,11,10,5,12,11,10,9,7,12,4,17,8,16,4,9,12,3,8,8,8,10,11,8,8,10,4,10,7,8,9,10,11,4,11,9,10,8,10,9,7,3,9,7,11,6,10,4,8,4,11,2,10,18,8,6,8,11,4,8,9,6,9,11,9,3,7~11,8,11,9,5,6,4,10,9,5,3,9,11,11,10,6,12,10,4,10,9,2,10,12,11,18,11,8,8,11,11,10,8,12,11,12,8,8,7,11,12,10,11,8,10,17,3,10,7,9,12,11,11,8,8,3,8,10,6,10,9,11,18,6,9,4,11,9,10,9,8,10,4,10,8,11,3,2,8,8,10,11,10,12,16,17,7,10,9,8,8,9,5,9,16,9,8,8,7,12,11,8,10,12,10,11,8,6,8,10,11,8,10,9,11,4,12,9,8,8,4,3,11,7,8,12,10,4,9,4,8,9,10,8,4,11,9~3,16,8,4,9,11,3,8,3,11,7,18,3,9,11,17,5,10,8,10,3,12,6,7,12,16,8,5,8,11,9,11,10,12,7,8,11,5,11,8,5,11,5,11,8,9,10,6,9,10,12,11,4,12,9,6,18,8,5,17,7,4,8,10,3,11,8,9,3,5,7,6,4,7,7,8,4,6,7,8,9,12,11,6,12,7,4,9,8,7,8,11,9,6,4,12,4,6,8&s='.$lastReelStr.'&accInit=[{id:0,mask:"bd_sways;bd_swilds;bd_swins"},{id:1,mask:"sways;swilds;swins"}]&reel_set2=12,5,9,7,3,8,11,5,11,3,8,5,11,8,7,3,9,4,6,11,19,9,12,3,9,6,19,10,19,4,9,18,17,7,4,10,7,9,5,4,9,10,12,11,10,8,10,12,11,5,8,10,5,17,11,8,10,9,7,6,4,4,6,9,11,8,5,12,8,6,8~4,9,7,9,8,11,7,9,2,12,11,9,8,17,10,6,5,8,12,8,12,9,12,8,12,8,6,10,7,12,9,11,8,5,8,4,19,17,18,5,9,6,11,3,2,6,10,8,7,9,10,12,9,19~18,11,9,7,17,10,7,9,4,2,5,9,10,6,11,9,11,9,10,7,19,2,8,10,7,10,3,4,8,4,3,9,8,7,9,8,12,9,10,11,19,8,6,11,8,7,11,8,6,10,6,12,9,3,11~9,10,8,10,9,7,11,9,10,8,6,8,5,2,9,3,8,10,17,9,12,8,2,10,8,6,17,2,12,9,3,4,6,9,10,11,4,19,11,8,9,8,10,11,9,6,19,10,5,11,9,10,11,7,9,8,11,9,8,11,9,4,10,11,19,11,8,4,9,10,12,8,11,11,9,8,8,9,11,12,4,10,12,11,10,11,11,10,4,11,11,5,8,9,18,8,19,8,11,8,4,2,11,9,3,11,12,9,10,11,4,11,9,7,9,5,9,10,4,9,11,10,8,18,12,9,10,7,10,9,3,10,6,10,9,10,11,11,6,8,11,9,3,8,3,12~12,9,12,10,11,10,3,11,3,12,19,8,9,4,9,18,8,11,4,12,8,6,5,12,10,6,8,7,10,6,9,8,6,10,8,7,8,11,5,4,8,6,8,10,7,19,8,9,4,12,4,6,10,8,4,8,7,11,3,8,7,5,8,10,11,3,6,4,11,8,3,19,8,12,9,6,11,7,3,9,6,10,5,12,11,5,9,12,8,9,17,9,3,8,3,5,11,7,8,7,5,3,11,4,10,3,12,4,10,6,11,9,12,4,9,11,4,17,7,19,8,11,7,10&reel_set1=4,3,12,10,12,8,17,5,10,6,8,11,10,9,8,5,7,18,5,8,12,5,3,9,12,11,9,11,9,11,4,4,8,7,9,10,8,5,4,11,8,12,5,10,11,12,10,8,11,9,5,9,8,9,4,3,12,10,4,10,8,11,7,10,12,7,12,9,5,9,5,12,11,7,4,12,9,4,7,17,3,8,6,8,6,7,5,6,9,6,4,18,12~9,4,8,11,2,3,12,8,9,6,10,16,9,8,3,9,5,11,3,6,11,8,4,11,10,5,8,9,12,7,9,8,9,12,7,11,10,9,5,12,2,9,12,8,7,12,8,6,12,9,10,11,8,12,2,17,7,18,6,5,8,10~11,8,4,12,9,11,12,9,12,10,11,8,12,2,4,5,8,8,17,4,11,7,8,11,8,10,9,7,3,8,10,3,11,8,6,6,7,6,9,4,6,12,7,9,10,3,6,11,11,7,8,9,10,12,4,3,8,7,5,16,11,9,3,4,10,11,8,3,11,12,8,8~9,7,9,8,9,11,11,17,11,11,3,10,8,4,12,9,11,8,6,9,8,8,10,8,8,6,11,8,9,10,9,10,9,12,10,12,9,10,5,8,18,16,3,4,8,10,8,8,6,3,12,4,9,11,12,7,16,3,11,5,11,5,10,4,10,8,11,4,6,7,8,18,8,2,11,11,9,11,10,17,10,11,2,9,11,9,6,8,9,4,10,12,10,12,12,2,9,12,10,8~11,12,9,8,6,10,3,11,12,5,8,6,8,5,12,3,11,9,11,3,10,11,8,3,7,7,5,8,9,3,4,9,11,3,7,5,4,8,9,8,10,12,10,3,12,5,8,6,7,9,18,10,4,9,8,10,7,8,9,4,6,12,8,12,6,9,6,17,7,8,7,4,6,10,12,6,11,10,11,17,8,11,4,9,4,4,7,3,10,8,7,9,12,5,4,18,5,11,4,10,7,7&reel_set4=12,8,9,10,7,8,12,5,6,3,5,4,7,19,11,10,8,4,9,11,12,11,16,9,11,4,12,17,19,6,8,10,17,19,10,11,3,7,8,5,12,10,11,3,6,7,9,5,8,5,11,19,8,9,7,9,5,4,9,5~9,4,11,12,8,19,7,6,11,7,9,13,9,8,9,8,9,8,3,7,19,10,6,9,12,6,10,13,19,7,8,12,8,11,5,12,11,12,12,9,5,19,9,10,3,19,15,17,4,5,14,7,8,10,17,12,9,12,8,9,8,7,12,6,8,3,8,11,10,8,6,10,9,16,12~11,8,19,15,10,6,9,7,10,9,6,4,6,10,7,8,7,9,8,7,11,10,6,4,8,9,8,7,9,12,8,4,7,11,5,3,11,3,17,10,7,13,16,9,19,4,9,8,19,9,10,8,6,9,13,4,6,10,5,17,7,3,11,8,3,4,9,11,9,10,14,12,5,12,9,11,8,14,9,8,19,7,11,7,12,10,11,16,7,9,4,19,12,3,19,3,11,4,15,8~9,12,10,11,14,11,17,3,16,15,11,9,14,4,6,8,19,6,8,11,9,10,19,11,19,10,4,3,8,9,10,12,9,4,19,12,6,5,8,8,17,8,4,10,13,11,11,8,10,5,6,9,8,9,11,11,16,9,4,10,4,9,7,3,9,10,11,3,9,12,9,11,7,10,3,19,11,12,8,12,9,10,11,11,9,8,9,8,10,9,10,8,11,19,9,8,10,4,7,11,11,10,3,9,10,11,8,9,8,5,9,11,12,12,8,11,10,11,4,9,11,19,9,8,15,5,8,13,9,10,8,12,11,11,10,11,10,7,9,12,9,19,8,9,10,8~9,7,11,12,8,19,4,8,7,10,12,6,10,8,4,10,11,8,4,11,12,19,10,7,9,4,11,8,12,7,11,3,7,6,16,6,10,6,9,3,8,17,6,9,8,12,8,7,19,10,9,8,4,8,9,8,7,9,5,6,12,5,7,10,5,4,11,9,7,12,5,10,19,9,6,19,12,8,7,9,10,11,6,5,4,8,11,5,9,4,8,3,11,7,8,11,3,11,8,12,10,3,6,3,9,12,16,11,3,17,8,4,7,6,8,8,10,11,3,11,19,8,3&purInit=[{bet:3000,type:"default"}]&reel_set3=3,9,6,8,5,12,9,12,11,5,10,8,3,4,8,9,8,5,11,8,9,19,11,7,18,9,8,10,7,19,10,12,11,8,8,16,3,3,11,8,4,7,8,5,8,6,5,19,4,9,8,5,4,9,7,10,11,8,11,5,11,19,9,11,8,6,9,12,11,10,16,5,7,9,8,5,9,6,16,3,6,18,9,4,12,10,5,9,12,11,10,12,7,9~12,6,9,12,5,8,6,8,8,7,12,2,12,9,3,8,19,7,18,4,12,9,12,11,3,9,9,2,16,9,8,5,8,19,6,8,2,8,2,7,9,10,4,10,16,2,9,9,10,3,11,6,12,19,9,5,11,8,12,11,7,10,12~9,10,8,3,12,11,6,4,10,11,8,4,5,6,19,10,9,12,6,2,8,11,10,8,4,19,3,16,11,12,9,19,7,11,9,7,2,5,19,12,18,3,9,8,2,11,6,9,3,7,9,8,10,11,9,7,2,9,11,10,7,4,7,10~11,12,11,8,16,9,10,9,8,19,9,2,8,9,11,10,4,19,10,6,9,12,4,8,11,19,10,9,11,9,8,4,6,11,11,10,8,2,8,5,10,9,3,11,10,4,5,10,11,9,19,7,3,10,12,9,11,11,9,19,8,12,2,10,19,9,4,9,4,10,9,2,2,11,10,7,2,9,8,7,3,4,2,18,8,10,6,11,9,8,10,6,18,3,11,11,3,9,5,12,10,11,8,11,10,12,10,11,8,19,8,9,10,11,8,5,8,7,2,10,8,9,8,11,9,4,11,9,12,16~4,7,11,4,5,11,8,6,11,6,19,9,4,3,11,9,3,6,11,9,11,16,12,8,4,3,11,10,12,10,3,11,12,5,4,11,7,8,19,4,12,10,8,19,5,19,10,9,4,19,12,3,7,6,12,8,12,4,6,8,9,12,10,6,10,11,12,9,3,10,8,3,5,7,9,10,19,11,10,5,10,4,19,18,8,9,10,8,6,11,6,8,12,3,6,8,7,3,7,8,16,11,9,5,11,8,12,7,8,9,8,7,3,19,8,6,10,9,7,10,11,9,8,10,11,7,10,8,10,6,8,7,11,8,12,4,7,8,18,12,8,9,4&reel_set6=7,8,10,8,9,11,5,10,4,6,10,11,9,17,7,8,5,8,7,8,4,5,6,10,11,10,3,11,10,7,11,5,7,8,10,6,19,6,10,12,9,12,7,4,9,8,7,9,12,9,11,8,12,7,19,9,7,12,4,5,4,3,8,17,5,4,9,11,3,12,4,3,8,5,9,12,8,5,4,11,8,5,12,8,5,6,4,17,11,3,11,9,17,11,10,9,5,8,11,12~19,8,9,11,5,3,12,8,11,5,9,8,10,7,12,10,9,13,17,8,4,5,12,8,11,6,13,7,8,7,10,10,9,3,7,15,6,10,12,3,6,12,12,9,12,11,4,9,5,8,7,17,9,8,12,14,9,11,4,6,9,8~10,6,8,10,9,3,9,8,6,9,7,10,3,5,10,8,9,11,9,9,4,8,6,14,8,10,4,8,9,7,9,7,11,13,4,11,12,4,3,9,10,12,11,7,8,10,3,6,15,3,17,19,12,5,8,7,10,8,17,10,8,6,12,4,8,11,9,7,4,11,7,12,9,9,11,10,7,11,7,11~12,9,12,11,11,3,12,8,9,8,10,11,7,17,11,9,10,8,10,8,12,9,7,11,10,12,13,14,9,8,9,11,11,9,10,9,11,10,9,17,9,8,10,8,6,12,6,10,19,10,6,12,10,12,8,11,11,5,10,12,15,9,6,9,8,17,4,5,4,11,10,4,3,12,10,4,11,9,10,5,3,13,5,8,11,11,8,11,11,9,8,7,6,10,3,8,9,8,11,3,9,11,11,8,11,10,11,7,11,9,17,8,4,8,9,19,10,14,4,9,8,9,15,9,10,4,10,17,10,8,11,11~4,19,17,3,11,7,10,11,7,17,11,8,9,10,12,4,3,11,8,4,6,12,6,5,4,3,11,6,17,8,7,8,6,11,17,9,12,10,9,7,9,7,5,6,4,8,11,10,8,4,9,10,12,10,12,9,10,8,10,9,11,6,3,11,12,8,7,8,12,8,4,11,9,3,11,8,6,12,5,7&reel_set5=10,9,10,11,12,11,18,4,12,10,11,6,8,7,11,4,8,4,6,11,9,5,12,18,10,8,5,9,10,8,7,5,10,8,18,4,6,9,7,12,9,8,6,10,8,9,4,8,5,12,9,11,4,19,11,8,3,12,11,7,11,9,5,8,9,7,3,5,10,5,3,7,9,7,6,4,5,8,9,11,5,3~11,9,6,7,12,4,8,12,8,12,6,8,10,3,7,12,2,8,9,7,3,2,12,11,8,6,11,8,18,9,10,12,5,12,2,9,18,6,8,4,9,8,9,5,9,8,11,9,12,2,7,19,5,12,9~9,8,4,7,3,10,12,5,9,7,9,2,10,8,11,9,2,12,6,4,10,12,9,8,18,3,6,7,3,9,12,9,11,3,8,11,7,4,8,19,7,9,7,18,11,4,6,2,9,11,6,11,9,2,10,10,8,11,10,9,4,8,4,7,10,11~8,2,11,5,11,7,8,4,10,11,8,2,6,11,11,12,8,9,10,11,4,9,10,11,11,11,11,12,9,2,3,4,12,9,10,11,6,6,18,8,3,11,9,8,18,8,10,9,11,6,10,5,9,8,9,4,9,8,4,10,9,4,10,12,5,2,8,12,9,10,9,11,8,11,12,11,19,10,6,9,7,9,8,3,11,10,18,8,12,9,3,11,10,8,11,10,3,9,2,9,8,10,11,11,4,18,8,11,9,8,18,8,11,4,9,8,2,9,11,10,19,9,3,10,9,8,12,9,10,8,12,10,18,9,10,11,12,11,2,10,11,2,6,8,12,5~12,8,10,8,4,10,8,9,7,11,10,8,7,6,12,11,8,10,7,8,9,6,18,4,8,11,18,11,7,11,8,10,11,8,12,18,9,18,6,3,8,6,3,5,11,4,10,9,19,10,8,3,8,12,10,6,3,7,4,8,11,3,9,4,6,9,4,9,7,8,6,3,12,4,18,18,4,9,12,5,12,10,12,3,6,3,7,6,11,9,7,11,4,19,12,18,4,10,6,5,10,11,7,5,8,11,9,11,3,9,4,11,8,7,4,5,8,12,5,8,9,12,7,7,10,8,10,10,11,6,11,11,6&reel_set8=10,9,11,7,8,7,8,11,8,4,9,19,8,5,6,10,3,12,6,4,3,9,11,12,6,3,9,10,4,12,4,12,5,9,11,8,11,5,11,9,10,12,6,9,8,4,7,9,5,9,8,7,8,10,9,10,12,7,11,8,7,3,12,8,8,19,3,11,5,8,11,8,4,11,5,8,5,7,10,6,9,7,6,11,8,12,5,8,5,4,5,4~8,3,5,8,12,4,7,12,13,12,4,9,5,11,9,8,13,12,7,4,11,10,11,8,13,7,8,9,14,8,6,12,8,9,12,12,15,9,12,5,3,8,10,9,10,8,9,11,5,9,12,9,8,7,9,8,10,8,6,7,6,12,5,9,19,10,9,12,8,10,7,8,3,6,12,8,6,9,11~4,7,3,10,11,9,10,11,9,10,7,10,8,12,9,15,4,3,4,8,5,11,6,11,8,13,11,9,9,10,9,7,6,7,12,4,12,4,8,7,3,12,6,10,9,8,6,9,12,10,5,12,14,7,11,9,11,10,8,19~11,5,9,9,4,10,11,10,11,11,13,9,8,11,10,11,10,8,6,8,9,12,3,6,11,7,11,8,11,11,8,10,4,11,7,10,12,13,10,9,8,14,4,9,11,12,8,10,11,9,8,11,8,9,9,11,13,10,9,3,9,9,5,10,12,4,11,5,8,10,8,11,11,15,8,5,8,9,12,13,9,9,4,8,10,8,8,10,19,4,8,12,10,9,11,3,9,10,9,9,6,4,9,11,12,15,10,7,12,9,11,6,10,11,8,4,9,19,9,10,8,10,4,12,11,12,10,11,11,3,8,14,10,7,12,8,10,9,11,6,3,9,8,9,10,8,11,3,9,10,8~7,6,10,7,8,19,7,8,12,8,4,10,4,9,8,11,9,10,8,12,10,9,12,3,12,6,11,5,8,4,3,10,5,11,10,12,7,9,11,6,9,10,11,7,8,10,6,4,6,4,7,8,7,9,11,9,6,12,8,12,3,10,5,10,8,3,10,12,9,6,7,3,4,12,11,5,8,8,9,11,3,4,11,8,11,8,6,11,8,19,9,4,3&reel_set7=19,5,11,9,4,9,10,5,10,9,5,6,9,6,10,9,3,11,8,6,5,11,6,11,7,4,7,5,10,3,8,4,8,9,8,12,8,4,12,10,7,16,9,10,12,11,16,10,12,19,11,4,9,16,12,8,5,4,8,3,9,5,8,5,7,9,3,11,16,7,8,9,12,11,7~12,8,11,5,9,6,3,12,8,12,7,9,4,12,8,14,9,7,9,11,8,15,11,5,12,10,19,7,12,5,4,7,10,8,10,4,12,19,13,8,9,6,16,12,9,8,10,11,9,8,13,11,6,8,6,3,9,8,9,16,8,16,9,13~8,9,10,9,11,10,6,4,9,10,16,9,19,14,9,12,8,6,12,11,8,9,12,8,10,8,8,7,9,6,9,7,10,11,7,4,7,4,8,3,13,4,5,8,11,3,8,12,6,11,10,15,11,5,7,10,4,12,3,7,11,8,7,11,4,9,10,3,11,9,16,12,10,3,8,4,7,9~9,13,7,9,10,8,3,11,3,12,8,12,8,12,11,12,4,9,4,16,11,6,4,5,8,11,13,8,11,4,9,8,8,10,11,8,3,9,12,9,8,9,16,10,11,15,8,7,11,11,9,10,5,4,9,11,8,19,11,9,6,14,10,11,10,9,11,11,10,16,9,10,8,11,8,8,10,6,10,9,9,10~10,4,9,11,8,3,12,8,6,10,3,7,10,11,12,9,8,4,12,8,6,4,8,6,19,9,5,16,10,7,9,10,8,12,10,7,12,8,3,11,12,8,11,6,11,8,16,3,11,9,3,9,5,7,4,10,9,9,4,9,16,4,8,4,8,11,11,16,11,8,5,11,19,3,6,11,9,8,11,8,6,10,12,10,10,7,16,3,7,6,7,5&total_bet_min=6.00';
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
                $lines = 30;      
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
                $pur = -1;
                if(isset($slotEvent['pur'])){
                    $pur = $slotEvent['pur'];
                }
                $slotEvent['slotBet'] = $slotEvent['c'];
                $slotEvent['slotLines'] = 30;
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
                    if( $slotEvent['slotEvent'] == 'doSpin' && $slotSettings->GetBalance() < ($lines * $betline)) 
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
                if($pur >= 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $allBet * 100;
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
                $Balance = $slotSettings->GetBalance();
                $totalWin = 0;
                $this->winLines = [];
                $bonusMpl = 1;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $str_trail = '';
                $str_stf = '';
                $str_initReel = '';
                $str_accm = '';
                $str_accv = '';
                $str_acci = '';
                $str_mo = '';
                $str_mo_t = '';
                $str_mo_wpos = '';
                $mo_tv = 0;
                $mo_m = 0;
                $str_slm_mp = '';
                $str_slm_mv = '';
                $fsmax = 0;
                $fsmore = 0;
                $str_sa = '';
                $str_sb = '';
                if($slotEvent['slotEvent'] == 'freespin'){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_trail = $stack['trail'];
                    $str_stf = $stack['stf'];
                    $str_initReel = $stack['is'];
                    $str_accm = $stack['accm'];
                    $str_accv = $stack['accv'];
                    $str_acci = $stack['acci'];
                    $str_slm_mp = $stack['slm_mp'];
                    $str_slm_mv = $stack['slm_mv'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $str_mo_wpos = $stack['mo_wpos'];
                    $mo_tv = $stack['mo_tv'];
                    $mo_m = $stack['mo_m'];
                    $fsmax = $stack['fsmax'];
                    $fsmore = $stack['fsmore'];
                    $strWinLine = $stack['wlc_v'];
                    $currentReelSet = $stack['reel_set'];
                    $str_sa = $stack['sa'];
                    $str_sb = $stack['sb'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $lastReel = explode(',', $stack[0]['reel']);
                    $str_trail = $stack[0]['trail'];
                    $str_stf = $stack[0]['stf'];
                    $str_initReel = $stack[0]['is'];
                    $str_accm = $stack[0]['accm'];
                    $str_accv = $stack[0]['accv'];
                    $str_acci = $stack[0]['acci'];
                    $str_slm_mp = $stack[0]['slm_mp'];
                    $str_slm_mv = $stack[0]['slm_mv'];
                    $str_mo = $stack[0]['mo'];
                    $str_mo_t = $stack[0]['mo_t'];
                    $str_mo_wpos = $stack[0]['mo_wpos'];
                    $mo_tv = $stack[0]['mo_tv'];
                    $mo_m = $stack[0]['mo_m'];
                    $fsmax = $stack[0]['fsmax'];
                    $fsmore = $stack[0]['fsmore'];
                    $strWinLine = $stack[0]['wlc_v'];
                    $currentReelSet = $stack[0]['reel_set'];
                    $str_sa = $stack[0]['sa'];
                    $str_sb = $stack[0]['sb'];
                }
                if($strWinLine != ''){
                    $arr_lines = explode(';', $strWinLine);
                    for($k = 0; $k < count($arr_lines); $k++){
                        $arr_sub_lines = explode('~', $arr_lines[$k]);
                        $arr_sub_lines[1] = str_replace(',', '', $arr_sub_lines[1]) / $original_bet * $betline;
                        $totalWin = $totalWin + $arr_sub_lines[1];
                        $arr_lines[$k] = implode('~', $arr_sub_lines);
                    }
                    $strWinLine = implode(';', $arr_lines);
                } 
                $moneyWin = 0;
                if($mo_tv > 0){
                    $moneyWin = $mo_tv * $betline;
                    if($mo_m > 1){
                        $moneyWin = $moneyWin * $mo_m;
                    }
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
                if($fsmax > 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $fsmax);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                }else if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                }
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
                        $isEnd = true;
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsend_total=1&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 'c';
                    }
                    else
                    {
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                    }
                    if($fsmore > 0){
                        $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    if($fsmax > 0){
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1';
                        $spinType = 's';
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&purtr=1&puri=' . $pur;
                    }
                }
                if($str_trail != ''){
                    $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                }
                if($str_stf != ''){
                    $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($str_accv != ''){
                    $strOtherResponse = $strOtherResponse . '&accm=' . $str_accm . '&acci=' . $str_acci . '&accv=' . $str_accv;
                }
                if($str_slm_mv != ''){
                    $strOtherResponse = $strOtherResponse . '&slm_mp=' . $str_slm_mp . '&slm_mv=' . $str_slm_mv;
                }
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                }
                if($str_mo_wpos != ''){
                    $strOtherResponse = $strOtherResponse . '&mo_wpos=' . $str_mo_wpos;
                }
                if($mo_tv > 0){
                    $strOtherResponse = $strOtherResponse . '&mo_c=1&mo_tv=' . $mo_tv . '&mo_tw=' . $moneyWin;
                    if($mo_m > 0){
                        $strOtherResponse = $strOtherResponse . '&mo_m=' . $mo_m;
                    }
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&wlc_v=' . $strWinLine;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse  .'&balance='.$Balance. '&index='.$slotEvent['index'] .'&balance_cash='.$Balance.'&reel_set='. $currentReelSet .'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&st=rect&c='.$betline.'&sa='. $str_sa .'&sb='. $str_sb .'&sh=5&sw=5&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&s='. $strLastReel .'&w='.$totalWin;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if( $slotEvent['slotEvent'] != 'freespin' && $fsmax > 0) 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')  . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')  .',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
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
        public function findZokbos($reels, $firstSymbol, $repeatCount, $strLineWin){
            $wild = '2';
            $bPathEnded = true;
            if($repeatCount < 6){
                for($r = 0; $r < 8; $r++){
                    if($firstSymbol == $reels[$repeatCount][$r] || $reels[$repeatCount][$r] == $wild){
                        $this->findZokbos($reels, $firstSymbol, $repeatCount + 1, $strLineWin . '~' . ($repeatCount + $r * 6));
                        $bPathEnded = false;
                    }
                }
            }
            if($bPathEnded == true){
                if($repeatCount >= 3 || ($firstSymbol == 3 && $repeatCount == 2)){
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

<?php 
namespace VanguardLTE\Games\DemonPotsPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 40);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [6,12,7,7,9,14,8,7,7,14,14,15,11,8,16]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'IsBonusBank', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Bl', 0);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $lastEvent->serverResponse->CurrentRespin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'IsBonusBank', $lastEvent->serverResponse->IsBonusBank);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bl', $lastEvent->serverResponse->Bl);
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
                    $bet = '25.00';
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                $fsmore = 0;
                if(isset($stack)){
                    $str_initReel = $stack['is'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $str_mo_wpos = $stack['mo_wpos'];
                    $mo_tv = $stack['mo_tv'];
                    $mo_m = $stack['mo_m'];
                    $str_trail = $stack['trail'];
                    $str_stf = $stack['stf'];
                    $fsmax = $stack['fsmax'];
                    $fsmore = $stack['fsmore'];
                    $strWinLine = $stack['win_line'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $rs_more = $stack['rs_more'];
                    $rs_iw = $stack['rs_iw'];
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
                    if($rs_p >= 0){
                        $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p . '&rs_c=' . $rs_c . '&rs_m=' . $rs_m;
                    }
                    if($rs_more > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_more=' . $rs_more;
                    }
                    if($rs_t > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t;
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
                $response = 'def_s=6,12,7,7,9,14,8,7,7,14,14,15,11,8,16&balance='. $Balance .'&cfgs=9921&ver=3&index=1&balance_cash='. $Balance .'&def_sb=10,11,6,10,6&reel_set_size=16&def_sa=10,13,8,15,15&reel_set=0&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"1000000",max_rnd_win:"6000",max_rnd_win_a:"4000",max_rnd_hr_a:"1000000"}}&wl_i=tbm~6000;tbm_a~4000&bl='. $slotSettings->GetGameData($slotSettings->slotId . 'Bl') .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=10,13,8,15,15&sb=10,11,6,10,6&reel_set10=5,13,11,12,8,12,7,12,11,10,15,16,14,9,7,9,7,12,11,10,12,8,11,8,9,11,11,12,5,10~7,5,10,7,8,5,9,10,16,8,11,8,9,8,11,12,14,11,7,10,13,15,9,9,7,10,10,7,9,12,8~13,12,5,15,8,11,11,12,14,11,7,9,8,12,8,8,10,12,7,7,7,10,7,10,5,9,8,7,10,8,11,9,9,10,9,12,12,9,11,10,16,11~7,11,9,5,9,9,5,12,5,8,7,15,8,5,10,5,10,16,11,12,5,10,14,13,8,11,12~5,8,12,5,5,14,10,7,5,12,7,8,10,11,7,8,13,5,8,15,16,11,5,12,9,9,11,5,11,10,9,9,12,5,7~10,5,8,14,10,11,8,12,7,13,16,12,9,11,8,11,5,9,11,12,15,11,10,9,7,12,12~5,12,7,10,8,9,9,10,7,14,7,9,15,7,9,12,13,9,10,5,10,8,11,12,16,7,8,11,8,8,10~12,8,7,9,10,11,9,10,8,11,8,12,9,7,12,11,7,8,8,8,5,10,11,10,8,10,2,12,2,12,8,9,11,9,5,8,10,12,9~13,11,9,11,7,5,5,10,9,15,7,5,16,10,14,8,12,5,8,12~9,9,15,10,8,11,12,5,8,16,12,10,5,11,12,9,7,11,13,7,5,7,5,5,8,14,8,12,5,10,11,7,9,10,5~12,11,16,11,12,11,14,10,11,9,5,12,7,9,15,13,10,7,8,11,9,12,5,7,8,12~10,13,9,8,15,10,16,7,9,10,7,12,10,9,10,8,8,5,11,5,8,7,9,7,8,11,12,9,9,11,8,10,14~9,15,9,7,10,8,8,9,11,14,11,12,5,8,7,12,10,12,7,7,7,11,10,7,12,9,8,13,9,12,16,11,10,11,10,5,12,9,8,11,8,10~7,9,11,9,5,13,12,5,9,5,5,8,5,11,10,8,11,15,8,14,12,5,12,7,10,7,16,10~5,5,7,12,5,5,8,5,10,12,8,5,5,8,7,5,10,11,9,9,11,15,9,8,7,12,7,9,10,13,14,11,16&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=25.00&reel_set11=9,15,7,13,16,13,8,11,15,16,5,8,13,16,14,12,16,15,10,11,10,15,14,7,9,14,14,16,14,13,16,12~15,13,15,16,12,9,15,13,10,16,9,8,16,11,7,16,5,14,14,11,14,7,14,13,10,12,8,15~7,16,8,14,8,16,13,11,9,16,10,16,11,15,5,7,14,12,9,15,10,13,14,12~15,11,15,16,12,7,9,13,5,16,11,13,7,15,9,13,8,16,14,10,15,14,8,12,14,10,14,16,13,16,14~14,9,16,7,10,15,5,11,15,13,9,13,16,14,16,7,14,12,10,8,15,11,14,15,12,8,16,13~15,9,16,7,9,14,14,13,15,13,8,16,10,11,15,12,10,11,16,16,13,14,12,5,14,8,16,15,7,13~16,8,5,13,12,13,15,11,14,16,14,16,8,16,15,13,14,7,16,15,16,15,13,14,9,14,12,10,7,9,10,11,15~2,11,12,2,7,5,2,9,10,8~13,8,12,13,8,15,16,7,12,15,9,7,10,16,16,14,14,16,13,15,11,9,5,11,14,10,15,14,16~13,15,14,16,10,12,11,15,14,11,8,16,12,9,16,14,15,9,7,14,5,16,7,8,14,13,16,13,16,10~7,14,14,9,15,11,13,7,15,14,8,16,16,10,12,13,16,16,5,14,10,11,15,8,12,9~7,13,14,5,12,13,8,16,16,15,13,11,16,16,14,12,15,14,11,14,14,7,15,10,9,15,16,15,8,9,16,10~14,16,14,9,14,14,16,11,12,5,16,16,8,7,13,10,7,10,15,16,13,15,8,15,14,11,12,16,9,13,15~14,11,9,14,16,15,8,16,10,15,13,8,13,12,10,12,7,11,16,14,15,7,9,5,16~16,13,11,15,13,14,16,8,14,7,10,5,12,16,15,14,9&reel_set12=6,11,10,12,11,7,11,10,7,8,16,10,11,8,6,6,10,14,7,12,6,10,11,15,11,9,11,8,10,12,11,10,9,10,7,12,13,9,12,12,8,12,12~8,10,12,12,10,9,10,16,12,8,6,7,11,11,14,11,11,15,7,11,10,12,7,12,10,13,9,12,11,9,8,6,6,10~10,15,11,10,11,10,12,6,7,12,12,11,7,14,16,8,10,11,6,8,9,12,13,9~7,6,8,8,9,9,10,8,7,9,8,12,11,10,8,9,11,7,14,9,6,9,6,8,7,13,12,12,9,8,6,7,10,7,7,12,8,16,9,7,15~11,7,10,12,14,12,8,6,10,7,8,7,10,9,8,13,9,8,9,8,7,8,7,12,7,9,9,6,8,9,9,7,8,16,11,15,9,11,6,6,12,7~12,7,10,10,7,9,8,10,12,8,16,11,6,10,6,11,12,12,14,11,12,13,11,9,10,11,7,12,9,10,8,6,15,11~8,14,12,7,7,10,12,11,8,10,10,11,12,13,9,6,12,11,6,11,8,10,11,7,12,12,11,10,6,16,9,10,15,9~2,7,12,10,9,2,11,9,12,7,9,11,11,10,12,11,11,10,11,8,10,6,10,6,11,12,7,12,2,10,6,8,2,12,2,12,8,10~7,9,6,12,15,10,7,11,8,9,8,10,7,16,10,11,9,12,11,13,8,9,7,6,6,12,9,7,8,14,8~7,8,7,9,10,11,6,9,12,9,10,8,9,8,7,7,12,9,7,10,13,11,8,8,12,6,9,14,16,6,8,7,15~12,12,14,11,6,11,7,11,11,12,10,11,7,12,10,8,8,10,16,12,13,9,11,12,6,8,10,6,10,10,15,7,9~11,12,9,12,11,9,6,12,15,10,11,10,16,10,9,12,11,14,6,7,8,11,10,12,10,8,7,7,6,11,13,8,12,10~11,7,10,11,11,7,14,8,10,8,7,10,10,12,8,9,10,11,13,12,6,11,6,15,12,6,16,10,6,8,12,11,12,12,10,12,7,9,11,12,11,10,9~7,9,8,8,9,9,12,10,9,10,6,8,8,12,8,14,7,10,7,6,9,10,15,12,8,7,9,9,8,8,7,11,16,7,11,6,13,11,9,7,7,6,12,11~7,10,15,12,7,9,6,9,12,11,8,6,6,8,8,7,14,9,11,7,8,13,7,9,8,10,16,9,8,7,12,9,10&purInit_e=1&reel_set13=14,11,12,6,12,7,10,7,16,9,15,8,6,9,10,8,6,13,6~8,9,7,10,6,11,6,10,13,12,6,6,8,12,9,8,12,6,14,11,15,9,16,7~7,6,14,11,10,8,15,7,6,6,13,16,12,9,8,10,12,11,9,6~8,16,10,6,6,7,6,14,11,6,6,6,9,10,6,8,11,13,6,6,7,9,12,15~8,11,6,9,6,6,7,7,6,8,9,13,10,12,14,6,6,6,11,6,15,6,6,16,10,6,11,9,12,6,7,6,8,10,6~12,8,10,9,14,15,6,10,11,16,12,7,9,6,6,8,12,8,13,9,11,6,7,6,7,10,11~6,10,11,8,12,7,10,9,16,12,12,8,6,6,9,10,13,6,9,15,8,11,7,6,14,7,11~11,7,12,2,8,11,6,6,6,10,6,2,9,6,6,7,9,10,8,12~8,6,7,9,6,12,6,7,10,12,6,6,6,13,10,16,14,11,6,8,6,15,11,9,6,6~6,8,12,7,6,6,9,6,9,7,6,8,6,6,6,14,6,10,9,15,6,11,10,11,10,13,6,6,11,12,16,7~6,16,10,8,9,12,9,13,11,15,7,14,10,6,8,6,11,6,7,12~7,16,11,7,6,14,9,8,12,12,6,10,13,15,6,6,11,10~11,7,6,9,12,6,8,7,10,8,14,6,9,8,6,16,12,10,9,12,11,15,10,13,6,11~6,6,7,6,12,6,6,11,7,14,8,6,6,6,9,10,15,11,6,9,6,6,13,8,12,16~10,6,6,11,8,16,7,15,6,6,6,11,6,6,9,12,9,10,12,7,8,14,6,13&sh=3&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&st=rect&c='.$bet.'&sw=5&sver=5&bls=40,60&counter=2&reel_set14=6,11,16,13,12,11,12,8,7,12,6,9,8,7,11,11,12,10,14,10,9,15~6,7,9,15,13,9,10,12,7,10,7,9,8,7,10,8,12,8,16,11,8,6,11,9,10,9,14,7,12,10,8,11~12,16,8,10,12,7,9,9,7,6,8,9,8,12,10,9,12,11,7,7,7,12,11,15,9,10,14,12,10,8,7,9,11,11,13,8,10,11,8,6,10,11~8,10,16,14,11,6,7,8,7,15,11,6,9,6,12,9,10,13,6,12~10,7,16,6,9,15,8,11,6,9,13,14,6,11,6,11,12,6,7,10,12,10,9,8,7~11,8,7,10,8,12,15,9,11,6,12,11,10,11,12,7,12,12,16,11,12,14,10,9,9,8,12,6,7,11,13,8,6,10,7,11,9~8,6,9,16,14,9,10,9,6,9,12,8,7,9,7,10,8,9,10,11,7,13,11,7,12,11,10,12,7,8,7,10,8,8,15,10~11,9,6,10,12,11,12,2,9,6,8,9,11,8,8,8,7,10,7,11,8,12,9,10,2,12,8,12,10,8,9,10~7,6,14,9,6,11,6,10,11,6,12,16,13,9,10,6,8,6,7,11,8,9,12,7,12,10,15,8~10,12,7,10,9,12,16,11,6,11,8,13,11,14,11,8,9,6,6,8,7,6,6,7,12,10,12,9,6,6,7,9,15,8,6,10~11,10,11,9,9,8,12,12,11,7,10,12,11,15,13,16,12,9,11,14,12,6,8,7,12,7,11,8,6~7,10,8,10,13,11,7,8,8,9,14,10,8,6,12,9,9,8,11,10,12,6,8,9,10,16,12,7,11,9,12,9,9,7,10,7,8,7,11,8,10,7,15,10,7,9~11,9,12,8,11,10,6,14,9,9,12,10,11,10,12,8,8,7,9,11,11,7,7,7,9,8,6,11,16,10,9,8,13,12,15,11,10,7,9,10,8,12,7,12,6,7,12,8~13,6,6,14,8,15,10,12,9,9,12,10,8,7,11,9,11,10,6,7,12,6,7,11,16,6,8~6,14,10,12,6,11,10,11,10,12,7,6,8,6,8,6,9,7,11,16,9,9,8,13,6,15&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;200,80,40,0,0;80,40,36,0,0;40,36,32,0,0;36,32,28,0,0;32,28,24,0,0;20,16,12,0,0;20,16,12,0,0;20,16,12,0,0;12,8,4,0,0;12,8,4,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0&l=40&reel_set15=11,6,9,10,13,14,15,11,16,15,16,14,10,7,15,12,14,13,15,14,8,13,12,16,8,7,16~16,15,16,14,15,12,16,14,16,11,6,15,16,16,11,14,9,12,9,15,8,15,7,7,13,10,13,8,14,14,10~14,12,16,11,9,15,16,16,15,6,13,8,10,15,16,11,16,13,14,10,14,13,12,16,8,7,14,9,13,15,7,15,14~11,13,7,8,16,6,13,14,10,15,11,15,9,8,15,14,16,7,12,16,10,16,12,16,9,15,14,14,13~9,16,11,16,14,6,12,14,15,11,8,7,13,16,14,12,8,9,13,14,15,16,7,15,16,10,13,10~9,14,11,16,8,7,13,12,15,13,9,14,15,6,13,10,14,10,15,14,12,16,15,16,16,14,16,13,16,11,7,8~15,12,15,16,16,14,8,13,11,9,6,16,15,10,14,16,14,13,10,14,13,14,16,13,9,7,16,15,7,12,8~2,11,2,6,7,9,8,2,9,8,12,10,12,11~14,12,14,15,16,15,13,7,16,15,14,10,14,14,10,8,16,11,6,7,16,12,16,9,13,15,11,13,8,13,15~14,11,14,13,6,16,8,11,16,12,13,10,7,16,15,8,15,12,14,7,10,13,9,16,15,9~16,15,16,12,13,7,16,11,14,9,16,15,8,13,15,10,13,16,6,14,15,10,8,7,14,12,14,16,15,14,9,13~15,14,13,8,15,16,9,16,14,14,7,11,10,16,9,13,6,16,12,15,13,12,8,15,14,7,16,10~8,11,6,15,16,7,10,7,13,10,13,16,15,14,12,8,12,16,11,15,14,16,14,9,15,14,9~14,11,16,13,15,11,13,16,15,9,10,13,10,12,15,14,8,14,12,16,14,16,13,15,7,8,7,16,9,6~9,15,7,15,10,9,13,11,15,12,8,11,14,13,16,16,13,14,14,10,12,6,16,8,7,16&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=11,10,11,3,11,13,12,7,16,8,7,12,10,12,15,10,9,11,9,8,10,12,14,12,11,11,3,11,3,12,7,9,8,10,3,10,7,10,12,12,11,10,8~11,7,3,9,3,9,14,12,10,16,11,12,11,10,10,15,12,12,10,10,12,8,8,7,8,11,7,9,11,13,3~10,13,11,3,11,3,10,11,10,12,10,11,16,3,10,8,8,9,11,7,11,7,12,8,15,9,10,10,12,7,12,7,8,12,12,11,9,3,14,12,9~7,8,9,3,9,16,13,10,7,3,11,8,9,10,7,12,7,8,11,9,8,7,11,8,12,10,14,12,15,9,3~9,14,8,9,10,7,9,10,8,12,3,11,10,8,7,8,9,7,3,7,11,12,13,16,15,3,7,9,8,12,11~10,16,10,7,12,13,15,11,12,12,11,8,3,14,12,10,10,3,11,7,11,7,9,12,8,10,12,9,11,8,9,10,3,11~12,8,11,8,12,10,13,7,3,10,12,8,10,3,12,9,15,10,3,7,10,9,11,7,11,16,11,9,11,12,14~11,12,9,8,11,8,2,7,2,11,10,12,10,3,10,12,3,8,10,7,10,10,9,3,11,2,12,8,11,2,9,7,10,3,11,10,11,2,12,9,12,11,12~10,7,3,3,12,7,9,8,8,9,7,10,7,15,7,11,7,8,3,10,12,13,9,8,9,9,12,8,11,14,8,9,16,11~9,8,3,3,8,3,9,7,7,13,9,7,10,8,3,7,9,8,7,11,12,10,11,9,14,10,9,11,9,12,8,11,7,8,12,9,8,10,15,7,7,16,8,12~10,11,10,11,11,12,10,12,8,7,14,8,13,16,7,3,9,15,11,12,3,11,12,7,10,10,9,12,10,3,11,9,8,12~9,10,12,10,3,10,8,15,11,10,16,3,9,7,12,7,8,11,12,14,8,11,7,10,11,12,12,13,11,3~12,7,16,3,12,7,11,9,8,10,11,10,8,10,12,11,15,10,7,8,10,12,7,12,11,9,3,14,9,11,12,10,11,8,11,3,12,10,3,9,13~12,12,10,7,7,13,8,8,9,7,15,9,10,8,14,8,9,3,16,11,10,9,7,7,8,9,3,11,12,3,8,11,9,7~3,8,12,9,3,9,8,9,16,9,11,7,15,9,8,7,13,8,7,7,8,12,14,11,7,3,9,10,8,10,7,10,11,12&s='.$lastReelStr.'&reel_set2=12,11,11,8,12,9,9,3,14,3,12,13,7,12,10,11,12,15,8,12,11,11,9,11,3,12,10,7,12,8,16,8,11,7,9,11,7~8,8,12,9,9,7,14,11,10,3,10,13,8,12,16,11,9,7,10,10,7,8,3,9,15~10,12,7,3,9,15,7,10,9,8,12,9,3,12,10,8,12,11,11,8,10,11,7,7,7,8,9,8,9,11,3,12,16,12,7,11,9,10,12,9,7,8,14,10,13,8,11,11~8,3,3,8,15,12,11,9,3,10,7,14,12,8,16,10,12,3,11,7,9,7,10,3,9,13~3,16,14,9,11,15,3,10,9,11,7,10,3,13,12,3,8,9,12,3,8,7,3,10,7,11,12~13,14,12,8,9,9,12,8,10,3,12,11,12,10,7,8,12,9,11,7,11,16,11,7,15,11,3~10,15,7,9,10,8,3,9,8,9,7,8,9,14,3,16,11,10,7,13,7,10,12,11,8~9,12,9,10,7,12,11,2,10,3,9,11,10,7,8,12,11,12,8,8,8,9,2,12,9,10,11,8,10,3,8,11,7,10,8,12,2,11,8~10,12,7,14,13,10,9,16,7,9,11,8,15,3,7,3,3,12,11,3,10,8,11,3,9,3,12~3,11,8,9,7,16,10,15,3,8,12,11,10,3,12,10,14,11,7,13,9,3,8,9,3~9,3,14,10,3,7,12,8,11,11,10,11,12,12,8,12,8,11,15,7,9,13,11,16,12,7,9,10~10,11,10,7,8,9,15,7,14,11,9,13,3,10,7,8,9,8,12,9,10,9,12,8,16,3,7,11,7,8,10,12~7,12,11,11,8,9,11,9,12,10,8,16,10,11,12,13,14,7,7,7,9,8,3,7,10,11,15,8,12,9,10,7,10,8,8,11,12,12,9,9,3~3,7,9,12,7,9,10,15,7,3,13,8,12,3,3,14,9,11,8,3,10,12,7,10,3,3,10,11,8,11,9,16,3,11,8~8,14,7,12,9,7,8,12,3,10,8,11,3,15,10,3,10,16,7,3,11,9,3,9,9,3,3,12,13,8,11,12,11,7&reel_set1=8,12,12,15,9,14,8,3,8,3,3,11,10,3,7,10,9,11,7,10,7,3,13,11,12,16~11,15,7,3,12,16,13,8,12,11,3,3,7,8,14,9,3,9,10~7,11,14,11,13,11,3,3,9,8,3,3,9,12,12,10,12,16,10,3,8,7,10,15,7,8~3,8,11,8,3,3,7,3,3,10,7,9,3,3,12,14,3,3,3,11,12,3,9,3,3,15,8,13,3,9,16,11,10,7,3,10~3,9,8,3,9,3,12,10,3,8,16,12,7,13,7,3,3,3,9,8,15,12,3,10,11,3,11,3,7,3,3,10,3,11,14~8,9,15,7,11,8,3,10,3,10,13,11,3,16,9,3,12,14,12~10,8,13,3,7,11,9,7,3,15,8,12,11,3,16,9,3,10,14,12~10,2,8,9,12,9,7,9,3,10,3,10,3,3,3,12,8,11,7,11,8,3,11,12,2,3,2,3,7~3,3,9,12,9,3,13,3,12,11,3,3,3,10,7,3,11,7,8,15,14,10,3,3,8,16~3,3,9,3,3,10,8,15,13,7,3,3,3,11,3,7,12,3,12,3,9,10,11,14,3,16~7,9,15,8,10,12,14,12,3,3,11,10,3,9,11,8,16,3,7,13~12,7,11,7,9,10,7,3,9,12,15,8,3,3,10,11,8,13,9,10,3,3,16,14,8,3,11,12~12,15,3,7,13,7,8,3,11,9,10,8,3,11,12,9,3,16,14,10~3,3,9,3,12,11,3,10,3,3,3,8,3,10,7,14,13,16,12,3,7,8,15,11~7,3,3,14,9,16,8,9,12,10,13,8,3,12,7,9,3,3,3,10,3,10,3,7,3,11,3,11,15,3,3,12,8,3,11&reel_set4=4,12,12,7,7,11,13,8,9,7,4,11,10,12,10,12,8,9,11,12,4,10,11,11,10,8,10,14,12,12,16,10,10,7,15,9,4,10,12,11,8,9,11,11~8,8,11,12,10,7,10,14,11,9,13,11,15,4,8,12,10,8,4,16,10,12,12,11,11,4,11,11,10,7,10,12,9,12,10,12,4,7,12,7,9,10,9,11~11,8,7,10,7,15,7,10,10,8,4,11,16,11,4,12,9,12,9,10,11,13,10,12,14,9,8,12,4,11,12~10,7,7,13,11,12,10,4,11,4,7,10,9,8,8,12,8,8,4,15,7,9,9,8,9,14,8,16,7,12,9,7,9,11~14,8,7,7,9,8,10,9,8,7,8,15,10,9,10,7,11,7,9,16,7,8,7,4,10,12,13,11,7,8,8,12,9,11,12,8,4,9,4,9,4,9,11,12~12,12,9,13,12,16,11,11,10,12,10,4,9,11,12,10,12,15,10,10,11,8,14,4,9,7,10,12,8,11,9,11,12,10,4,8,11,11,8,7,7,10,7,4~10,7,7,9,10,8,14,4,16,10,9,12,7,11,12,13,12,11,8,11,11,12,10,8,11,4,11,9,15,10,4,12,12,10~2,8,11,9,4,12,10,10,2,10,11,7,12,8,12,10,12,4,9,11,7,11~9,4,16,14,12,7,9,9,13,8,9,7,9,15,8,11,7,4,8,11,8,4,10,7,11,10,7,7,8,12,4,12,10,8,9,8,9,12,7,11,10,9,8,7~10,8,8,12,11,7,8,9,8,9,12,11,4,14,12,4,9,4,10,9,7,10,8,8,12,11,16,9,7,8,7,7,10,9,9,7,13,15,7,4~12,11,16,9,12,7,9,7,15,11,10,10,12,12,11,9,10,4,12,11,8,13,12,11,14,4,11,8,7,10,10,4,10,8~7,12,11,10,8,10,8,9,16,8,14,12,11,12,10,4,7,10,4,10,11,11,12,12,13,10,7,9,12,11,4,15,9,11~8,7,11,7,11,15,8,11,10,12,10,14,7,7,12,10,4,12,9,4,12,10,9,8,13,11,12,4,10,9,11,4,11,8,10,11,12,12,16,10~4,9,7,11,8,7,4,7,12,11,8,10,7,9,4,15,13,11,12,11,8,9,4,7,14,8,9,8,10,8,10,9,7,8,7,12,9,16,12,9,10~9,8,7,10,9,7,12,4,16,11,8,4,7,10,12,7,14,9,9,11,8,7,9,7,4,10,8,15,8,12,7,8,11,9,4,8,9,10,13,7,9,12,11,8&purInit=[{bet:4000,type:"default"}]&reel_set3=16,15,10,14,15,8,13,8,3,12,9,14,7,16,7,14,16,10,14,15,11,16,9,15,13,12,11,13~11,16,15,14,7,14,3,16,13,16,9,8,16,8,15,10,11,12,15,13,10,9,7,14,12,13~16,16,7,7,15,13,14,10,12,9,8,9,3,15,13,14,13,16,14,8,15,16,14,16,15,11,16,13,14,10,11,12~14,15,7,16,16,9,16,14,11,8,13,14,15,8,13,11,3,14,9,12,10,16,12,7,13,14,15,10,16,13,15,16~9,16,14,12,14,8,16,14,15,7,13,11,15,13,10,12,16,14,3,15,16,15,10,13,16,9,7,8~13,16,16,13,3,10,12,15,14,10,12,11,14,16,9,16,9,15,8,7,14,7,8,15,13,11~10,14,12,16,15,13,12,10,11,9,14,8,16,16,11,8,7,15,16,15,16,14,14,15,13,9,7,15,3,16,13,14~7,8,9,12,11,2,7,11,3,10,9,2,10,12,2~16,11,16,9,7,12,14,14,11,8,10,14,13,12,9,7,16,15,3,15,13,10,16,15,8,16,15,14,16,15,13,14,13~15,12,16,14,3,14,9,16,10,14,7,13,14,16,16,11,8,14,11,15,7,8,16,15,13,9,10,15,13,15,12,13,16~14,16,14,15,9,12,16,11,10,15,10,9,7,15,12,13,11,13,3,16,14,8,13,16,15,14,16,7,13,8,14,16,15~10,14,3,8,15,8,16,13,11,16,11,13,12,15,9,10,16,13,12,15,14,7,16,16,14,7,15,13,14,14,16,9~7,14,15,13,11,14,7,10,13,3,8,11,16,16,14,13,10,15,16,16,15,8,12,9~11,13,16,13,8,16,12,9,3,7,15,14,7,10,9,13,16,16,15,11,10,14,8,14~16,13,3,7,16,13,11,16,8,15,7,12,16,13,11,14,14,15,13,14,15,10,8,14,16,15,12,10,9&reel_set6=7,10,11,15,10,9,16,10,11,9,11,11,9,11,12,11,8,11,12,13,9,8,7,8,12,4,12,4,12,10,4,11,12,7,14,8,7,12,12~7,10,14,4,11,10,9,12,9,9,16,13,7,12,9,11,7,10,8,8,15,11,8,10,8,10,7,10,10,9,4,12,9,7,8,4,8,7,9,8~12,13,8,8,10,11,16,12,9,8,10,9,10,11,4,10,12,8,14,8,9,10,7,7,7,10,9,9,11,4,11,9,8,11,12,12,10,9,7,7,8,11,12,11,15,12,4~8,11,15,11,10,12,7,12,14,9,8,9,4,16,4,13,7,4,10,4~16,4,9,4,8,15,10,11,9,9,10,8,11,7,10,12,4,4,13,14,12,11,12,7,4,8,7~11,10,12,12,11,12,10,7,10,8,12,8,9,12,11,7,11,7,10,9,12,11,13,7,15,16,11,8,11,12,8,12,4,11,9,4,14,9~8,9,13,8,7,12,9,12,7,10,10,8,9,14,4,10,15,11,9,12,7,8,10,8,11,10,7,16,9,11,7,4~9,8,10,11,10,11,9,11,12,10,2,7,2,11,2,9,8,8,8,10,8,12,8,8,4,8,10,9,12,7,12,10,8,11,7,11,12,12~8,15,8,11,4,16,12,4,13,9,7,10,4,10,4,7,11,12,4,11,4,8,11,14,7,9,9,12,9,10,4~9,8,4,7,14,12,11,4,10,9,4,11,12,16,4,10,15,9,8,7,8,10,4,12,13,11,4~14,10,7,8,11,9,8,7,16,12,7,10,4,12,10,12,7,4,10,9,11,11,12,11,9,8,12,11,11,15,12,13,8,11,9,11,12,12~9,11,9,8,9,12,9,8,9,13,7,14,8,4,10,10,11,7,10,4,8,10,8,9,9,11,7,10,8,7,10,16,4,15,11,12,10,12,9,7,10,8,8,7~10,8,12,13,4,12,16,8,11,10,8,12,14,15,12,9,7,7,7,4,7,10,11,8,7,10,11,8,9,12,9,9,10,7,11,11,9~9,8,14,4,9,7,11,12,15,4,12,10,8,11,16,4,7,13,4,10~4,10,4,7,11,12,14,9,7,8,4,10,12,16,4,8,7,11,13,9,4,10,8,11,15,7,9,4,4,10,9,4,12,11&reel_set5=4,4,16,8,12,9,4,10,15,7,14,13,7,8,12,10,11~16,12,10,4,4,9,10,8,13,12,15,4,8,9,11,7,14,7,11,4,10,12,4,11,7,8,4~8,15,9,4,8,7,4,9,7,10,11,4,16,12,10,12,13,14,4,11~8,10,4,4,11,4,7,8,12,4,4,10,4,9,11,4,4,4,12,4,4,9,7,7,4,4,10,4,11,16,15,4,12,8,13,14~11,7,8,4,9,7,9,12,16,4,4,8,10,4,4,4,11,4,4,9,12,10,11,4,15,13,12,4,7,10,14,4,8~12,11,16,9,7,4,8,12,8,14,10,4,4,10,4,8,9,10,13,15,4,7,4,9,11,12~11,4,4,11,10,12,15,9,16,7,12,4,10,4,12,4,7,10,8,11,4,8,14,13,8,9,7~11,4,9,4,11,12,10,10,12,4,4,8,4,4,4,2,7,2,8,12,2,9,7,11,2,4,10,9,8~4,11,4,8,4,8,13,12,11,4,10,4,4,4,16,10,4,9,4,15,9,14,12,4,7,7~14,8,12,4,9,11,4,12,4,4,4,9,15,16,4,4,10,4,4,7,10,11,13,7,4~4,12,9,4,14,13,11,12,7,10,8,15,4,12,4,9,7,10,8,16,9,4,11,8,4,10,11,7~4,11,7,12,13,12,16,4,10,7,8,9,14,11,15,8,4,10~16,4,9,10,7,13,7,15,9,4,11,12,11,4,14,10,8,12,8,4~8,14,12,4,8,4,4,11,16,10,4,4,7,12,7,4,4,4,10,11,4,9,15,4,9,12,10,4,4,8,4,11,13,9,7~7,12,4,4,8,11,4,10,4,4,9,8,14,10,13,4,4,4,7,12,4,11,8,7,12,4,4,9,16,4,9,15,4,11,10,4&reel_set8=9,12,7,11,10,8,7,10,10,11,12,9,12,7,11,13,11,10,8,5,10,5,12,11,8,14,12,9,12,7,12,10,11,12,11,9,10,15,5,5,11,8,10,16~16,14,12,15,10,5,11,7,11,5,8,8,12,7,13,11,9,10,10,9,7,11,12,5,11,10,12,10,9,5,7,10,10,12,8,11,8,11,12,12~9,5,5,10,7,11,10,7,10,5,12,12,11,11,9,7,10,11,16,11,15,12,8,8,11,11,14,9,12,10,9,10,7,13,10,12,8,12,12,8,5~10,7,9,7,9,8,5,12,7,12,11,7,5,8,8,7,15,8,5,5,10,7,14,7,8,9,11,13,8,12,12,10,9,11,9,8,10,9,7,8,9,16,9~9,10,9,13,8,12,11,7,8,9,8,5,12,15,9,9,8,12,11,7,14,8,10,7,7,11,9,8,5,16,10,5,7,7~11,15,12,11,10,12,7,5,13,12,10,11,11,7,5,8,10,12,12,14,10,12,7,9,16,8,9,10,5,10,11,8,11~12,7,12,15,5,7,10,10,8,11,16,8,11,11,14,12,9,10,9,5,12,10,11,13~5,2,9,12,10,8,5,11,11,10,5,7,12,2,7,10,9,10,9,11,8,2,11,10,12,2,11~9,8,5,7,9,7,7,9,12,13,8,5,12,11,9,5,12,11,9,7,10,16,10,7,7,8,7,8,5,12,9,15,11,7,8,14,9,8,11,8,10,8,9~8,9,7,5,13,14,16,10,12,12,10,11,7,10,8,15,5,8,11,9,8,7,12,9,7,11,8,9,9,5,7~10,9,12,8,5,11,11,9,7,9,10,16,12,5,8,11,13,11,10,5,8,7,7,11,14,12,10,15,12,10,12~8,12,14,10,7,11,11,15,12,11,10,12,16,10,5,9,7,12,8,11,8,13,12,10,11,11,10,5,9,12,7,10,5,9~13,15,5,10,12,9,11,10,5,7,11,11,16,12,8,12,10,9,11,7,12,12,10,11,9,7,8,5,8,14,10~9,13,5,12,9,10,5,10,8,7,5,10,9,14,9,8,12,8,7,7,11,8,8,7,8,7,9,10,12,5,12,7,16,15,7,11,9,8,9,11~7,12,5,9,5,12,8,8,16,7,9,11,7,9,10,12,7,13,14,9,9,8,15,7,8,10,5,8,11,10,11&reel_set7=16,12,14,11,16,14,15,10,16,10,9,4,16,16,14,9,15,8,12,13,14,15,13,8,7,13,7,14,16,13,15~16,7,9,11,15,14,12,16,9,15,7,16,4,13,10,14,13,11,15,8,16,15,13,12,10,16,14,14,8~4,16,8,16,8,9,16,15,12,15,13,7,14,10,14,15,9,12,11,13,11,10,14,7,16~16,14,14,13,10,15,16,13,12,7,11,16,4,15,8,9,14,15~16,11,9,16,7,16,13,14,8,13,10,15,16,14,15,7,15,9,8,12,14,14,15,12,4,11~13,7,13,8,16,15,11,16,12,10,14,15,14,9,16,14,4~12,15,7,15,13,8,14,10,15,16,10,15,14,14,9,11,12,13,9,13,16,16,8,16,7,14,4~2,11,7,2,10,12,9,7,9,12,2,11,4,8,10,8,2~16,7,11,14,12,9,14,15,13,15,13,16,16,10,14,4,15,8~13,8,12,16,9,11,12,15,10,11,7,14,9,10,13,15,16,4,13,15,8,14,14,7,16,16~16,13,12,8,16,16,10,11,9,14,7,11,15,16,14,4,10,7,9,14,13,15,13~9,16,10,14,7,15,13,14,11,7,15,14,9,14,13,10,8,16,12,16,13,12,14,11,13,15,16,16,15,4,15,16,8~15,9,13,7,10,13,15,7,16,14,10,11,12,4,14,16,16,14,13,11,8,15,9,8,14,15,16,12,16~8,7,16,7,13,9,15,9,11,14,10,13,12,14,4,15,12,14,16,16,14,16,14,16,13,10,13,15,11,15,16~7,9,13,8,16,13,14,16,16,7,11,12,13,14,15,4,9,11,14,15,13,16,10,12,10,8,14,16,15&reel_set9=9,13,8,7,11,5,14,10,9,8,16,12,11,12,10,5,15,5~5,10,11,8,15,5,5,12,7,5,8,16,9,10,14,11,12,7,13~8,10,5,14,16,13,9,5,7,11,5,10,15,8,12,12,11,9,5,7~5,9,12,8,16,10,8,10,5,11,8,11,5,9,5,5,5,12,10,5,11,5,7,5,5,7,9,5,13,7,5,14,15~5,9,14,5,5,11,7,10,16,7,5,5,5,11,8,10,5,8,12,5,5,9,15,13,5~10,8,5,5,12,11,7,9,12,9,5,5,11,7,11,10,13,8,14,16,5,10,12,9,15,8~5,8,5,5,9,8,5,9,12,7,10,7,13,10,12,16,15,11,14,11~5,12,11,7,2,9,5,8,2,10,5,9,8,5,5,5,2,8,12,7,2,11,5,7,10,12,9,5,5,10~10,7,9,5,12,5,12,5,10,11,8,5,9,5,5,5,7,9,11,5,12,5,8,15,8,16,14,5,7,5,13,11~10,5,5,7,5,5,9,7,16,5,5,10,9,10,11,5,5,5,12,14,8,13,11,8,7,12,5,11,5,5,12,5,9,15,5~10,5,14,5,5,9,13,8,16,7,15,11,12,10,9,12,7,11,5~11,9,7,16,10,14,7,8,5,5,8,9,10,11,8,12,15,5,9,10,12,13,5,7,5,12,11,5~5,8,13,5,7,14,8,10,9,7,9,12,5,12,11,10,11,16,15~9,12,8,5,5,15,8,7,11,5,5,16,11,5,11,5,5,5,7,7,5,5,12,5,14,5,5,10,8,12,10,13,9,10,5,9~7,7,9,5,12,8,10,16,5,11,5,5,7,5,5,5,8,15,5,5,11,10,5,5,14,8,5,5,12,13,9,5,5,11&total_bet_min=5.00';
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
                $lines = 40;      
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
                $bl = $slotEvent['bl'];
                $slotEvent['slotBet'] = $slotEvent['c'];
                $slotEvent['slotLines'] = 40;
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    $slotEvent['slotEvent'] = 'freespin';
                }
                $lines = $slotEvent['slotLines'];
                $betline = $slotEvent['slotBet'];
                if( $slotEvent['slotEvent'] == 'doSpin' || $slotEvent['slotEvent'] == 'freespin' || $slotEvent['slotEvent'] == 'respin' ) 
                {
                    if( $lines <= 0 || $betline <= 0.0001 ) 
                    {
                        $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"invalid bet state"}';
                        exit( $response );
                    }
                    if( $slotEvent['slotEvent'] == 'doSpin' && $slotSettings->GetBalance() < ($lines * $betline) && $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') < 0) 
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
                }else if($bl == 1){
                    $allBet = $betline * 60;
                }
                $tumbAndFreeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'freespin' || $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') >= 0){
                    if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') < 0){
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                        $leftFreeGames = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') - $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame'); 
                    }
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                    if($winType == 'bonus'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'IsBonusBank', 1);
                    }else{
                        $slotSettings->SetGameData($slotSettings->slotId . 'IsBonusBank', 0);
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bl', $bl);
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
                $str_psym = '';
                $str_mo = '';
                $str_mo_t = '';
                $str_mo_wpos = '';
                $mo_tv = 0;
                $mo_m = 0;
                $wmv = 0;
                $fsmax = 0;
                $fsmore = 0;
                $rs_p = -1;
                $rs_c = 0;
                $rs_m = 0;
                $rs_t = 0;
                $rs_more = 0;
                $rs_iw = '';
                $str_sa = '';
                $str_sb = '';
                if($slotEvent['slotEvent'] == 'freespin' || $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') >= 0){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_trail = $stack['trail'];
                    $str_stf = $stack['stf'];
                    $str_initReel = $stack['is'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $str_mo_wpos = $stack['mo_wpos'];
                    $mo_tv = $stack['mo_tv'];
                    $mo_m = $stack['mo_m'];
                    $fsmax = $stack['fsmax'];
                    $fsmore = $stack['fsmore'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $rs_more = $stack['rs_more'];
                    $rs_iw = $stack['rs_iw'];
                    $strWinLine = $stack['win_line'];
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
                    $str_mo = $stack[0]['mo'];
                    $str_mo_t = $stack[0]['mo_t'];
                    $str_mo_wpos = $stack[0]['mo_wpos'];
                    $mo_tv = $stack[0]['mo_tv'];
                    $mo_m = $stack[0]['mo_m'];
                    $fsmax = $stack[0]['fsmax'];
                    $fsmore = $stack[0]['fsmore'];
                    $strWinLine = $stack[0]['win_line'];
                    $currentReelSet = $stack[0]['reel_set'];
                    $rs_p = $stack[0]['rs_p'];
                    $rs_c = $stack[0]['rs_c'];
                    $rs_m = $stack[0]['rs_m'];
                    $rs_t = $stack[0]['rs_t'];
                    $rs_more = $stack[0]['rs_more'];
                    $rs_iw = $stack[0]['rs_iw'];
                    $str_sa = $stack[0]['sa'];
                    $str_sb = $stack[0]['sb'];
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
                    if($slotSettings->GetGameData($slotSettings->slotId . 'IsBonusBank') > 0){
                        $slotSettings->SetBank(('bonus'), -1 * $totalWin);
                    }else{
                        $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                    }
                }
                $_obf_totalWin = $totalWin;
                $isState = true;
                if($fsmax > 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $fsmax);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'IsBonusBank', 0);
                }else if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                }
                $strLastReel = implode(',', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $rs_p);
                if($rs_p >= 0){
                    $spinType = 's';
                    $isState = false;
                }
                $strOtherResponse = '';
                if($rs_p > 0 || $rs_t > 0){
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                }
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $spinType = 's';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $isEnd = false;
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 && $rs_p < 0) 
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
                if($rs_p >= 0){
                    $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p . '&rs_c=' . $rs_c . '&rs_m=' . $rs_m;
                }
                if($rs_more > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_more=' . $rs_more;
                }
                if($rs_t > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t;
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0){
                        $spinType = 'c';
                        $isState = true;
                    }
                }
                if($rs_iw != ''){
                    $rs_iw = str_replace(',', '', $rs_iw) / $original_bet * $betline;
                    $strOtherResponse = $strOtherResponse . '&rs_iw=' . $rs_iw;
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse  .'&balance='.$Balance. '&index='.$slotEvent['index'] .'&balance_cash='.$Balance.'&reel_set='. $currentReelSet .'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&st=rect&c='.$betline.'&sa='. $str_sa .'&sb='. $str_sb .'&sh=3&sw=5&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&bl='. $slotSettings->GetGameData($slotSettings->slotId . 'Bl') .'&l=20&s='. $strLastReel .'&w='.$totalWin;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) && $rs_p < 0) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if( $slotEvent['slotEvent'] != 'freespin' && (($fsmax > 0 && $rs_p < 0 && $rs_t == 0) || $rs_p == 0)) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"CurrentRespin":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin'). ',"IsBonusBank":' . $slotSettings->GetGameData($slotSettings->slotId . 'IsBonusBank') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')  . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"Bl":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bl') . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')  .',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if(($slotEvent['slotEvent'] == 'freespin') && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * 100;
                }else if($isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'Bl') > 0){
                    $allBet = $betline * 60;
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

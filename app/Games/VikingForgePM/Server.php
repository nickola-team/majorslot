<?php 
namespace VanguardLTE\Games\VikingForgePM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [5,4,7,8,6,10,7,6,5,11,3,4,3,10,5,6,7,8,11,3,4,5,6,8,9,10,3,10,9,8]);
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
                    $bet = '50.00';
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
                    $currentReelSet = $stack['reel_set'];
                    $rs_iw = $stack['rs_iw'];
                    $str_rs_win = $stack['rs_win'];
                    $str_sa = $stack['sa'];
                    $str_sb = $stack['sb'];
                    $str_pw = $stack['pw'];
                    $str_accm = $stack['accm'];
                    $str_accv = $stack['accv'];
                    $str_s_mark = $stack['s_mark'];
                    $str_psym = $stack['psym'];
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
                    if($rs_iw != ''){
                        $rs_iw = str_replace(',', '', $rs_iw) / $original_bet * $bet;
                        $strOtherResponse = $strOtherResponse . '&rs_iw=' . $rs_iw;
                    }
                    if($str_rs_win != ''){
                        $str_rs_win = str_replace(',', '', $str_rs_win) / $original_bet * $bet;
                        $strOtherResponse = $strOtherResponse . '&rs_win=' . $str_rs_win;
                    }
                    if($str_pw != ''){
                        $str_pw = str_replace(',', '', $str_pw) / $original_bet * $bet;
                        $strOtherResponse = $strOtherResponse . '&pw=' . $str_pw;
                    }
                    if($str_s_mark != ''){
                        $strOtherResponse = $strOtherResponse . '&s_mark=' . $str_s_mark;
                    }
                    if($str_accv != ''){
                        $strOtherResponse = $strOtherResponse . '&accm=' . $str_accm . '&acci=0&accv=' . $str_accv;
                    }
                    if($str_psym != ''){
                        $arr_psym = explode('~', $str_psym);
                        $arr_psym[1] = str_replace(',', '', $arr_psym[1]) / $original_bet * $bet;
                        $str_psym = implode('~', $arr_psym);
                        $strOtherResponse = $strOtherResponse . '&psym=' . $str_psym;
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
                $response = 'def_s=5,4,7,8,6,10,7,6,5,11,3,4,3,10,5,6,7,8,11,3,4,5,6,8,9,10,3,10,9,8&balance='. $Balance .'&cfgs=10092&ver=3&mo_s=13;14&index=1&balance_cash='. $Balance .'&mo_v=2,5,10,20,40,60,100,160,200,240,300,400;2,3,5,10&def_sb=7,8,4,6,10,5&reel_set_size=5&def_sa=3,6,9,10,5,11&reel_set=0&balance_bonus=0.00&na=s&scatters=1~100,5,3,0,0,0~0,0,0,0,0,0~1,1,1,1,1,1&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"4000000",max_rnd_win:"10000",max_rnd_win_a:"8000",max_rnd_hr_a:"2000000"}}&wl_i=tbm~10000;tbm_a~8000&bl='. $slotSettings->GetGameData($slotSettings->slotId . 'Bl') .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=3,6,9,10,5,11&sb=7,8,4,6,10,5&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=50.00&purInit_e=1&sh=5&wilds=2~0~1&bonuses=0&st=rect&c='. $bet .'&sw=6&sver=5&bls=20,25&counter=2&paytable=0;0,0,0,0,0,0;0;2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,500,500,200,200,0,0,0,0,0,0,0;400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,200,200,40,40,0,0,0,0,0,0,0;300,300,300,300,300,300,300,300,300,300,300,300,300,300,300,300,300,300,300,100,100,30,30,0,0,0,0,0,0,0;200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,40,40,25,25,0,0,0,0,0,0,0;160,160,160,160,160,160,160,160,160,160,160,160,160,160,160,160,160,160,160,40,40,15,15,0,0,0,0,0,0,0;120,120,120,120,120,120,120,120,120,120,120,120,120,120,120,120,120,120,120,30,30,12,12,0,0,0,0,0,0,0;100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,25,25,10,10,0,0,0,0,0,0,0;80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,20,20,8,8,0,0,0,0,0,0,0;60,60,60,60,60,60,60,60,60,60,60,60,60,60,60,60,60,60,60,15,15,6,6,0,0,0,0,0,0,0;0;0;0&l=20&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=5,5,11,8,10,6,9,11,5,7,9,3,3,6,6,6,10,6,10,4,11,10,10,7,10,7,7,11,11,8,8,8,11,10,8,9,8,3,11,4,4,7,9,11,8,8,9,9,9,10,6,9,8,4,5,8,7,11,6,4,10,11,6,3,3,3,10,4,10,6,7,7,10,11,9,5,10,8,5,3,4,10,10,10,11,8,11,10,9,7,7,3,5,10,1,11,10,11,7,7,7,1,6,8,9,11,11,6,10,9,10,5,9,10,6,5,5,5,8,4,10,7,11,7,3,8,11,4,11,8,7,8,11,11,11,11,10,7,7,10,6,8,7,5,8,5,6,9,11,4,4,4,5,6,10,8,6,5,4,4,7,7,8,4,10,9,11,11~5,5,11,8,10,6,9,11,5,7,9,3,3,6,6,6,10,6,10,4,11,10,10,7,10,7,7,11,11,8,8,8,11,10,8,9,8,3,11,4,4,7,9,11,8,8,9,9,9,10,6,9,8,4,5,8,7,11,6,4,10,11,6,3,3,3,10,4,10,6,7,7,10,11,9,5,10,8,5,3,4,10,10,10,11,8,11,10,9,7,7,3,5,10,1,11,10,11,7,7,7,1,6,8,9,11,11,6,10,9,10,5,9,10,6,5,5,5,8,4,10,7,11,7,3,8,11,4,11,8,7,8,11,11,11,11,10,7,7,10,6,8,7,5,8,5,6,9,11,4,4,4,5,6,10,8,6,5,4,4,7,7,8,4,10,9,11,11~5,5,11,8,10,6,9,11,5,7,9,3,3,6,6,6,10,6,10,4,11,10,10,7,10,7,7,11,11,8,8,8,11,10,8,9,8,3,11,4,4,7,9,11,8,8,9,9,9,10,6,9,8,4,5,8,7,11,6,4,10,11,6,3,3,3,10,4,10,6,7,7,10,11,9,5,10,8,5,3,4,10,10,10,11,8,11,10,9,7,7,3,5,10,1,11,10,11,7,7,7,1,6,8,9,11,11,6,10,9,10,5,9,10,6,5,5,5,8,4,10,7,11,7,3,8,11,4,11,8,7,8,11,11,11,11,10,7,7,10,6,8,7,5,8,5,6,9,11,4,4,4,5,6,10,8,6,5,4,4,7,7,8,4,10,9,11,11~5,5,11,8,10,6,9,11,5,7,9,3,3,6,6,6,10,6,10,4,11,10,10,7,10,7,7,11,11,8,8,8,11,10,8,9,8,3,11,4,4,7,9,11,8,8,9,9,9,10,6,9,8,4,5,8,7,11,6,4,10,11,6,3,3,3,10,4,10,6,7,7,10,11,9,5,10,8,5,3,4,10,10,10,11,8,11,10,9,7,7,3,5,10,1,11,10,11,7,7,7,1,6,8,9,11,11,6,10,9,10,5,9,10,6,5,5,5,8,4,10,7,11,7,3,8,11,4,11,8,7,8,11,11,11,11,10,7,7,10,6,8,7,5,8,5,6,9,11,4,4,4,5,6,10,8,6,5,4,4,7,7,8,4,10,9,11,11~5,5,11,8,10,6,9,11,5,7,9,3,3,6,6,6,10,6,10,4,11,10,10,7,10,7,7,11,11,8,8,8,11,10,8,9,8,3,11,4,4,7,9,11,8,8,9,9,9,10,6,9,8,4,5,8,7,11,6,4,10,11,6,3,3,3,10,4,10,6,7,7,10,11,9,5,10,8,5,3,4,10,10,10,11,8,11,10,9,7,7,3,5,10,1,11,10,11,7,7,7,1,6,8,9,11,11,6,10,9,10,5,9,10,6,5,5,5,8,4,10,7,11,7,3,8,11,4,11,8,7,8,11,11,11,11,10,7,7,10,6,8,7,5,8,5,6,9,11,4,4,4,5,6,10,8,6,5,4,4,7,7,8,4,10,9,11,11~5,5,11,8,10,6,9,11,5,7,9,3,3,6,6,6,10,6,10,4,11,10,10,7,10,7,7,11,11,8,8,8,11,10,8,9,8,3,11,4,4,7,9,11,8,8,9,9,9,10,6,9,8,4,5,8,7,11,6,4,10,11,6,3,3,3,10,4,10,6,7,7,10,11,9,5,10,8,5,3,4,10,10,10,11,8,11,10,9,7,7,3,5,10,1,11,10,11,7,7,7,1,6,8,9,11,11,6,10,9,10,5,9,10,6,5,5,5,8,4,10,7,11,7,3,8,11,4,11,8,7,8,11,11,11,11,10,7,7,10,6,8,7,5,8,5,6,9,11,4,4,4,5,6,10,8,6,5,4,4,7,7,8,4,10,9,11,11&s='.$lastReelStr.'&accInit=[{id:0,mask:"coins"}]&reel_set2=5,8,6,8,4,8,8,7,10,11,8,11,11,11,4,10,7,9,5,7,5,3,5,11,8,3,11,3,3,3,7,5,9,5,11,10,11,10,6,10,11,10,11,10,10,10,4,4,8,10,11,9,5,11,5,9,11,11,9,9,9,8,4,10,11,9,8,7,3,6,9,10,9,3,8,8,8,4,5,3,5,9,11,6,10,11,9,10,9,7,7,7,4,8,6,8,6,7,8,10,4,6,11,8,4,5,5,5,6,3,5,6,11,8,10,9,10,11,6,7,4,4,4,1,8,4,11,1,5,10,11,8,10,8,10,6,6,6,8,11,6,11,6,7,11,6,6,10,5,3,10,6~5,8,6,8,4,8,8,7,10,11,8,11,11,11,4,10,7,9,5,7,5,3,5,11,8,3,11,3,3,3,7,5,9,5,11,10,11,10,6,10,11,10,11,10,10,10,4,4,8,10,11,9,5,11,5,9,11,11,9,9,9,8,4,10,11,9,8,7,3,6,9,10,9,3,8,8,8,4,5,3,5,9,11,6,10,11,9,10,9,7,7,7,4,8,6,8,6,7,8,10,4,6,11,8,4,5,5,5,6,3,5,6,11,8,10,9,10,11,6,7,4,4,4,1,8,4,11,1,5,10,11,8,10,8,10,6,6,6,8,11,6,11,6,7,11,6,6,10,5,3,10,6~5,8,6,8,4,8,8,7,10,11,8,11,11,11,4,10,7,9,5,7,5,3,5,11,8,3,11,3,3,3,7,5,9,5,11,10,11,10,6,10,11,10,11,10,10,10,4,4,8,10,11,9,5,11,5,9,11,11,9,9,9,8,4,10,11,9,8,7,3,6,9,10,9,3,8,8,8,4,5,3,5,9,11,6,10,11,9,10,9,7,7,7,4,8,6,8,6,7,8,10,4,6,11,8,4,5,5,5,6,3,5,6,11,8,10,9,10,11,6,7,4,4,4,1,8,4,11,1,5,10,11,8,10,8,10,6,6,6,8,11,6,11,6,7,11,6,6,10,5,3,10,6~5,8,6,8,4,8,8,7,10,11,8,11,11,11,4,10,7,9,5,7,5,3,5,11,8,3,11,3,3,3,7,5,9,5,11,10,11,10,6,10,11,10,11,10,10,10,4,4,8,10,11,9,5,11,5,9,11,11,9,9,9,8,4,10,11,9,8,7,3,6,9,10,9,3,8,8,8,4,5,3,5,9,11,6,10,11,9,10,9,7,7,7,4,8,6,8,6,7,8,10,4,6,11,8,4,5,5,5,6,3,5,6,11,8,10,9,10,11,6,7,4,4,4,1,8,4,11,1,5,10,11,8,10,8,10,6,6,6,8,11,6,11,6,7,11,6,6,10,5,3,10,6~5,8,6,8,4,8,8,7,10,11,8,11,11,11,4,10,7,9,5,7,5,3,5,11,8,3,11,3,3,3,7,5,9,5,11,10,11,10,6,10,11,10,11,10,10,10,4,4,8,10,11,9,5,11,5,9,11,11,9,9,9,8,4,10,11,9,8,7,3,6,9,10,9,3,8,8,8,4,5,3,5,9,11,6,10,11,9,10,9,7,7,7,4,8,6,8,6,7,8,10,4,6,11,8,4,5,5,5,6,3,5,6,11,8,10,9,10,11,6,7,4,4,4,1,8,4,11,1,5,10,11,8,10,8,10,6,6,6,8,11,6,11,6,7,11,6,6,10,5,3,10,6~5,8,6,8,4,8,8,7,10,11,8,11,11,11,4,10,7,9,5,7,5,3,5,11,8,3,11,3,3,3,7,5,9,5,11,10,11,10,6,10,11,10,11,10,10,10,4,4,8,10,11,9,5,11,5,9,11,11,9,9,9,8,4,10,11,9,8,7,3,6,9,10,9,3,8,8,8,4,5,3,5,9,11,6,10,11,9,10,9,7,7,7,4,8,6,8,6,7,8,10,4,6,11,8,4,5,5,5,6,3,5,6,11,8,10,9,10,11,6,7,4,4,4,1,8,4,11,1,5,10,11,8,10,8,10,6,6,6,8,11,6,11,6,7,11,6,6,10,5,3,10,6&reel_set1=10,7,9,3,9,11,6,9,8,10,11,11,11,9,7,6,7,11,8,4,1,4,9,6,6,11,10,10,10,11,3,8,7,10,8,9,11,10,9,9,8,6,6,6,11,10,5,3,9,8,5,9,6,4,6,7,7,8,8,8,5,7,4,6,7,11,4,9,11,5,11,7,7,9,9,9,11,4,6,3,10,9,4,7,7,11,10,11,4,4,4,11,10,5,4,10,7,9,8,5,9,3,10,5,3,3,3,8,8,7,1,5,8,9,5,7,6,6,8,5,5,5,8,6,9,11,8,5,6,4,11,10,10,8,11,7,7,7,8,10,8,9,8,11,11,10,10,5,3,11,10,11~10,7,9,3,9,11,6,9,8,10,11,11,11,9,7,6,7,11,8,4,1,4,9,6,6,11,10,10,10,11,3,8,7,10,8,9,11,10,9,9,8,6,6,6,11,10,5,3,9,8,5,9,6,4,6,7,7,8,8,8,5,7,4,6,7,11,4,9,11,5,11,7,7,9,9,9,11,4,6,3,10,9,4,7,7,11,10,11,4,4,4,11,10,5,4,10,7,9,8,5,9,3,10,5,3,3,3,8,8,7,1,5,8,9,5,7,6,6,8,5,5,5,8,6,9,11,8,5,6,4,11,10,10,8,11,7,7,7,8,10,8,9,8,11,11,10,10,5,3,11,10,11~10,7,9,3,9,11,6,9,8,10,11,11,11,9,7,6,7,11,8,4,1,4,9,6,6,11,10,10,10,11,3,8,7,10,8,9,11,10,9,9,8,6,6,6,11,10,5,3,9,8,5,9,6,4,6,7,7,8,8,8,5,7,4,6,7,11,4,9,11,5,11,7,7,9,9,9,11,4,6,3,10,9,4,7,7,11,10,11,4,4,4,11,10,5,4,10,7,9,8,5,9,3,10,5,3,3,3,8,8,7,1,5,8,9,5,7,6,6,8,5,5,5,8,6,9,11,8,5,6,4,11,10,10,8,11,7,7,7,8,10,8,9,8,11,11,10,10,5,3,11,10,11~10,7,9,3,9,11,6,9,8,10,11,11,11,9,7,6,7,11,8,4,1,4,9,6,6,11,10,10,10,11,3,8,7,10,8,9,11,10,9,9,8,6,6,6,11,10,5,3,9,8,5,9,6,4,6,7,7,8,8,8,5,7,4,6,7,11,4,9,11,5,11,7,7,9,9,9,11,4,6,3,10,9,4,7,7,11,10,11,4,4,4,11,10,5,4,10,7,9,8,5,9,3,10,5,3,3,3,8,8,7,1,5,8,9,5,7,6,6,8,5,5,5,8,6,9,11,8,5,6,4,11,10,10,8,11,7,7,7,8,10,8,9,8,11,11,10,10,5,3,11,10,11~10,7,9,3,9,11,6,9,8,10,11,11,11,9,7,6,7,11,8,4,1,4,9,6,6,11,10,10,10,11,3,8,7,10,8,9,11,10,9,9,8,6,6,6,11,10,5,3,9,8,5,9,6,4,6,7,7,8,8,8,5,7,4,6,7,11,4,9,11,5,11,7,7,9,9,9,11,4,6,3,10,9,4,7,7,11,10,11,4,4,4,11,10,5,4,10,7,9,8,5,9,3,10,5,3,3,3,8,8,7,1,5,8,9,5,7,6,6,8,5,5,5,8,6,9,11,8,5,6,4,11,10,10,8,11,7,7,7,8,10,8,9,8,11,11,10,10,5,3,11,10,11~10,7,9,3,9,11,6,9,8,10,11,11,11,9,7,6,7,11,8,4,1,4,9,6,6,11,10,10,10,11,3,8,7,10,8,9,11,10,9,9,8,6,6,6,11,10,5,3,9,8,5,9,6,4,6,7,7,8,8,8,5,7,4,6,7,11,4,9,11,5,11,7,7,9,9,9,11,4,6,3,10,9,4,7,7,11,10,11,4,4,4,11,10,5,4,10,7,9,8,5,9,3,10,5,3,3,3,8,8,7,1,5,8,9,5,7,6,6,8,5,5,5,8,6,9,11,8,5,6,4,11,10,10,8,11,7,7,7,8,10,8,9,8,11,11,10,10,5,3,11,10,11&reel_set4=5,9,5,7,9,3,3,7,7,5,9,9,7,3,3,5~8,6,6,8,10,4,8,10,10,4~5,9,5,7,9,3,3,7,7,5,9,9,7,3,3,5~8,6,6,8,10,4,8,10,10,4~5,9,5,7,9,3,3,7,7,5,9,9,7,3,3,5~8,6,6,8,10,4,8,10,10,4&purInit=[{bet:2000,type:"default"}]&reel_set3=11,7,10,4,4,7,8,8,9,5,8,9,9,9,9,7,11,11,5,9,6,1,6,5,7,6,7,5,6,3,3,3,7,10,9,8,9,3,10,8,6,9,9,5,9,8,8,8,7,9,11,10,3,9,6,6,7,9,11,10,6,9,10,10,10,6,6,10,8,11,11,7,5,11,7,3,7,7,4,7,7,7,10,8,9,10,5,8,6,9,10,4,4,5,10,4,4,4,9,5,4,5,5,9,8,4,9,10,4,7,8,3,11,11,11,4,6,7,3,10,8,11,3,7,5,11,6,10,6,6,6,10,11,11,3,10,5,7,4,10,4,9,11,9,8,5,5,5,6,10,1,9,9,8,11,6,10,7,5,10,6,11,6~11,7,10,4,4,7,8,8,9,5,8,9,9,9,9,7,11,11,5,9,6,1,6,5,7,6,7,5,6,3,3,3,7,10,9,8,9,3,10,8,6,9,9,5,9,8,8,8,7,9,11,10,3,9,6,6,7,9,11,10,6,9,10,10,10,6,6,10,8,11,11,7,5,11,7,3,7,7,4,7,7,7,10,8,9,10,5,8,6,9,10,4,4,5,10,4,4,4,9,5,4,5,5,9,8,4,9,10,4,7,8,3,11,11,11,4,6,7,3,10,8,11,3,7,5,11,6,10,6,6,6,10,11,11,3,10,5,7,4,10,4,9,11,9,8,5,5,5,6,10,1,9,9,8,11,6,10,7,5,10,6,11,6~11,7,10,4,4,7,8,8,9,5,8,9,9,9,9,7,11,11,5,9,6,1,6,5,7,6,7,5,6,3,3,3,7,10,9,8,9,3,10,8,6,9,9,5,9,8,8,8,7,9,11,10,3,9,6,6,7,9,11,10,6,9,10,10,10,6,6,10,8,11,11,7,5,11,7,3,7,7,4,7,7,7,10,8,9,10,5,8,6,9,10,4,4,5,10,4,4,4,9,5,4,5,5,9,8,4,9,10,4,7,8,3,11,11,11,4,6,7,3,10,8,11,3,7,5,11,6,10,6,6,6,10,11,11,3,10,5,7,4,10,4,9,11,9,8,5,5,5,6,10,1,9,9,8,11,6,10,7,5,10,6,11,6~11,7,10,4,4,7,8,8,9,5,8,9,9,9,9,7,11,11,5,9,6,1,6,5,7,6,7,5,6,3,3,3,7,10,9,8,9,3,10,8,6,9,9,5,9,8,8,8,7,9,11,10,3,9,6,6,7,9,11,10,6,9,10,10,10,6,6,10,8,11,11,7,5,11,7,3,7,7,4,7,7,7,10,8,9,10,5,8,6,9,10,4,4,5,10,4,4,4,9,5,4,5,5,9,8,4,9,10,4,7,8,3,11,11,11,4,6,7,3,10,8,11,3,7,5,11,6,10,6,6,6,10,11,11,3,10,5,7,4,10,4,9,11,9,8,5,5,5,6,10,1,9,9,8,11,6,10,7,5,10,6,11,6~11,7,10,4,4,7,8,8,9,5,8,9,9,9,9,7,11,11,5,9,6,1,6,5,7,6,7,5,6,3,3,3,7,10,9,8,9,3,10,8,6,9,9,5,9,8,8,8,7,9,11,10,3,9,6,6,7,9,11,10,6,9,10,10,10,6,6,10,8,11,11,7,5,11,7,3,7,7,4,7,7,7,10,8,9,10,5,8,6,9,10,4,4,5,10,4,4,4,9,5,4,5,5,9,8,4,9,10,4,7,8,3,11,11,11,4,6,7,3,10,8,11,3,7,5,11,6,10,6,6,6,10,11,11,3,10,5,7,4,10,4,9,11,9,8,5,5,5,6,10,1,9,9,8,11,6,10,7,5,10,6,11,6~11,7,10,4,4,7,8,8,9,5,8,9,9,9,9,7,11,11,5,9,6,1,6,5,7,6,7,5,6,3,3,3,7,10,9,8,9,3,10,8,6,9,9,5,9,8,8,8,7,9,11,10,3,9,6,6,7,9,11,10,6,9,10,10,10,6,6,10,8,11,11,7,5,11,7,3,7,7,4,7,7,7,10,8,9,10,5,8,6,9,10,4,4,5,10,4,4,4,9,5,4,5,5,9,8,4,9,10,4,7,8,3,11,11,11,4,6,7,3,10,8,11,3,7,5,11,6,10,6,6,6,10,11,11,3,10,5,7,4,10,4,9,11,9,8,5,5,5,6,10,1,9,9,8,11,6,10,7,5,10,6,11,6&total_bet_min=10.00';
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
                $pur = -1;
                if(isset($slotEvent['pur'])){
                    $pur = $slotEvent['pur'];
                }
                $bl = $slotEvent['bl'];
                $slotEvent['slotBet'] = $slotEvent['c'];
                $slotEvent['slotLines'] = 20;
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    $slotEvent['slotEvent'] = 'freespin';
                }
                $lines = $slotEvent['slotLines'];
                $betline = $slotEvent['slotBet'];
                if( $slotEvent['slotEvent'] == 'doSpin' || $slotEvent['slotEvent'] == 'freespin') 
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
                    $allBet = $betline * 25;
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
                    $roundstr = '6074458' . substr($roundstr, 4, 10);
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
                $str_rs_win = '';
                $str_sa = '';
                $str_sb = '';
                $str_pw = '';
                $str_accm = '';
                $str_accv = '';
                $str_s_mark = '';
                $str_psym = '';
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
                    $str_rs_win = $stack['rs_win'];
                    $strWinLine = $stack['win_line'];
                    $currentReelSet = $stack['reel_set'];
                    $str_sa = $stack['sa'];
                    $str_sb = $stack['sb'];
                    $str_pw = $stack['pw'];
                    $str_accm = $stack['accm'];
                    $str_accv = $stack['accv'];
                    $str_s_mark = $stack['s_mark'];
                    $str_psym = $stack['psym'];
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
                    $str_rs_win = $stack[0]['rs_win'];
                    $str_sa = $stack[0]['sa'];
                    $str_sb = $stack[0]['sb'];
                    $str_pw = $stack[0]['pw'];
                    $str_accm = $stack[0]['accm'];
                    $str_accv = $stack[0]['accv'];
                    $str_s_mark = $stack[0]['s_mark'];
                    $str_psym = $stack[0]['psym'];
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
                if($str_psym != ''){
                    $arr_psym = explode('~', $str_psym);
                    $arr_psym[1] = str_replace(',', '', $arr_psym[1]) / $original_bet * $betline;
                    $totalWin = $totalWin + $arr_psym[1];
                    $str_psym = implode('~', $arr_psym);
                }
                $moneyWin = 0;
                if($mo_tv > 0){
                    $moneyWin = $mo_tv * $betline;
                    if($mo_m > 1){
                        $moneyWin = $moneyWin * $mo_m;
                    }
                    if($str_pw == 0){
                        $totalWin = $totalWin + $moneyWin;
                    }
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
                    $strOtherResponse = $strOtherResponse . '&mo_tv=' . $mo_tv . '&mo_tw=' . $moneyWin;
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
                if($str_rs_win != ''){
                    $str_rs_win = str_replace(',', '', $str_rs_win) / $original_bet * $betline;
                    $strOtherResponse = $strOtherResponse . '&rs_win=' . $str_rs_win;
                }
                if($str_pw != ''){
                    $str_pw = str_replace(',', '', $str_pw) / $original_bet * $betline;
                    if($str_pw == 0){
                        $strOtherResponse = $strOtherResponse . '&mo_c=1';
                    }
                    $strOtherResponse = $strOtherResponse . '&pw=' . $str_pw;
                }
                if($str_s_mark != ''){
                    $strOtherResponse = $strOtherResponse . '&s_mark=' . $str_s_mark;
                }
                if($str_accv != ''){
                    $strOtherResponse = $strOtherResponse . '&accm=' . $str_accm . '&acci=0&accv=' . $str_accv;
                }
                if($str_psym != ''){
                    $strOtherResponse = $strOtherResponse . '&psym=' . $str_psym;
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse  .'&balance='.$Balance. '&index='.$slotEvent['index'] .'&balance_cash='.$Balance.'&reel_set='. $currentReelSet .'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&st=rect&c='.$betline.'&sa='. $str_sa .'&sb='. $str_sb .'&sh=5&sw=6&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&bl='. $slotSettings->GetGameData($slotSettings->slotId . 'Bl') .'&l=20&s='. $strLastReel .'&w='.$totalWin;
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
                    $allBet = $betline * 25;
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

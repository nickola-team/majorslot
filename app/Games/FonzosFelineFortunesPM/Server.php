<?php 
namespace VanguardLTE\Games\FonzosFelineFortunesPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'RespinWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'Bl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 10);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [3,11,5,5,11,6,8,9,4,10,5,7,11,5,7]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'IsBonusBank', 0);
                $strOtherResponse = '';
                $currentReelSet = 0;
                $stack = null;
                
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinWin', $lastEvent->serverResponse->RespinWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $lastEvent->serverResponse->CurrentRespin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bl', $lastEvent->serverResponse->Bl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    $slotSettings->SetGameData($slotSettings->slotId . 'IsBonusBank', $lastEvent->serverResponse->IsBonusBank);
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
                    $bet = '100.00';
                }
                $spinType = 's';
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if(isset($stack)){
                    $str_initReel = $stack['initreel'];
                    $currentReelSet = $stack['reel_set'];
                    $fsmore = $stack['fsmore'];
                    $str_stf = $stack['stf'];
                    $str_srf = $stack['srf'];
                    $str_rs_p = $stack['rs_p'];
                    $str_rs_c = $stack['rs_c'];
                    $str_rs_m = $stack['rs_m'];
                    $str_rs_t = $stack['rs_t'];
                    $str_rs_more = $stack['rs_more'];
                    $str_rs_iw = $stack['rs_iw'];
                    $str_rs_win = $stack['rs_win'];
                    $str_rs = $stack['rs'];
                    $strReelSa = $stack['sa'];
                    $strReelSb = $stack['sb'];
                    $str_s_mark = $stack['s_mark'];
                    $str_sts = $stack['sts'];
                    $str_sty = $stack['sty'];
                    $wmv = $stack['wmv'];
                    $fsmax = $stack['fsmax'];
                    $apv = $stack['apv'];
                    $str_trail = $stack['trail'];
                    $str_pw = $stack['pw'];
                    $str_psym = $stack['psym'];
                    $str_accm = $stack['accm'];
                    $str_accv = $stack['accv'];
                    $str_aam = $stack['aam'];
                    $str_aav = $stack['aav'];
                    $str_lmi = $stack['lmi'];
                    $str_lmv = $stack['lmv'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $mo_tv = $stack['mo_tv'];
                    $str_slm_mp = $stack['slm_mp'];
                    $str_slm_mv = $stack['slm_mv'];
                    $strWinLine = $stack['win_line'];
                    $str_rmul = $stack['rmul'];
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                    }
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($str_stf != ''){
                        $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                    }
                    if($str_srf != ''){
                        $strOtherResponse = $strOtherResponse . '&srf=' . $str_srf;
                    }
                    if($str_trail != ''){
                        $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                    }
                    if($str_s_mark != ''){
                        $strOtherResponse = $strOtherResponse . '&s_mark=' . $str_s_mark;
                    }
                    if($str_rs_p != ''){
                        $strOtherResponse = $strOtherResponse . '&rs_p=' . $str_rs_p . '&rs_m=' . $str_rs_m . '&rs_c=' . $str_rs_c;
                    }
                    if($str_rs_t != ''){
                        $strOtherResponse = $strOtherResponse . '&rs_t=' . $str_rs_t;
                    }
                    if($str_rs != ''){
                        $strOtherResponse = $strOtherResponse . '&rs=' . $str_rs;
                    }
                    if($str_rs_iw != ''){
                        if(is_numeric($str_rs_iw)){
                            $strOtherResponse = $strOtherResponse . '&rs_iw=' . (str_replace(',', '', $str_rs_iw) / $original_bet * $bet);
                        }
                    }
                    if($str_rs_win != ''){
                        $strOtherResponse = $strOtherResponse . '&rs_win=' . (str_replace(',', '', $str_rs_win) / $original_bet * $bet);
                    }
                    if($str_pw != ''){
                        $strOtherResponse = $strOtherResponse . '&pw=' . (str_replace(',', '', $str_pw) / $original_bet * $bet);
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
                    if($str_lmv != ''){
                        $strOtherResponse = $strOtherResponse . '&lmi=' . $str_lmi . '&lmv=' . $str_lmv;
                    }
                    if($str_slm_mv != ''){
                        $strOtherResponse = $strOtherResponse . '&slm_mp=' . $str_slm_mp . '&slm_mv=' . $str_slm_mv;
                    }
                    if($str_rs_more != ''){
                        $strOtherResponse = $strOtherResponse . '&rs_more=' . $str_rs_more;
                    }
                    if($wmv > 0){
                        $strOtherResponse = $strOtherResponse . '&wmt=pr&wmv=' . $wmv;
                        if($wmv > 1){
                            $strOtherResponse = $strOtherResponse . '&gwm=' . $wmv;
                        }
                    }
                    if($str_sts != ''){
                        $strOtherResponse = $strOtherResponse . '&sts=' . $str_sts;
                    }
                    if($str_sty != ''){
                        $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                    }
                    if($str_mo != ''){
                        $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                    }
                    if($mo_tv > 0){
                        $strOtherResponse = $strOtherResponse . '&mo_tv=' . $mo_tv . '&mo_tw=' . ($mo_tv * $bet);
                    }
                    if($str_rmul != ''){
                        $strOtherResponse = $strOtherResponse . '&rmul=' . $str_rmul;
                    }
                    if($str_aav != ''){
                        $strOtherResponse = $strOtherResponse . '&aam=' . $str_aam . '&aav=' . $str_aav;
                    }
                    $str_apaw = '';
                    if($apv > 0){
                        $arr_apvs = explode(',', $apv);
                        $arr_apaw = [];
                        for($k = 0; $k < count($arr_apvs); $k++){
                            $apaw = $arr_apvs[$k] * $bet;
                            $arr_apaw[] = $apaw;
                        }
                        $str_apaw = implode(',', $arr_apaw);
                        $strOtherResponse = $strOtherResponse . '&apaw=' . $str_apaw . '&apv=' . $apv;
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
                }
                
                $Balance = $slotSettings->GetBalance(); 
                $response = 'def_s=3,11,5,5,11,6,8,9,4,10,5,7,11,5,7&balance='. $Balance .'&cfgs=13873&ver=3&index=1&balance_cash='. $Balance .'&def_sb=6,7,6,4,10&reel_set_size=16&def_sa=3,8,4,6,8&reel_set=0&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"303090909",max_rnd_win:"5000",max_rnd_win_a:"3334",max_rnd_hr_a:"263210526"}}&wl_i=tbm~5000;tbm_a~3334&bl='. $slotSettings->GetGameData($slotSettings->slotId . 'Bl') .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime='. floor(microtime(true) * 1000) .'&sa=3,8,4,6,8&sb=6,7,6,4,10&reel_set10=11,5,6,7,9,1,4,5,11,6,10,5,4,9,5,4,7,6,6,11,3,9,4,7,3,9,2,11,7,9,3,7,8,1,11,9,8,3,2,9,4,7,11,2,5,7,11,5,3,9,10,11,7,2,2,2,2,2~5,3,2,8,6,10,2,7,5,2,8,6,10,4,9,5,2,8,6,6,11,2,3,10,2,5,1,8,4,10,8,5,10,3,8,2,4,3,10,5,2,4,10,1,11,8,9,3,2,10,4,1,8,7,2,2,2,2,2~5,11,1,2,5,6,10,2,4,6,9,3,2,4,1,8,3,4,5,6,6,11,8,4,3,2,7,4,5,10,3,5,1,9,2,5,3,2,7,2,2,2,2,2~3,4,6,5,1,11,4,1,7,6,5,3,10,2,8,6,6,9,5,2,4,1,2,2,2,2,2~4,3,5,6,10,1,3,7,6,4,2,11,1,6,6,9,1,5,8,2,2,2,2,2&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=100.00&reel_set11=11,5,6,7,9,1,4,5,11,6,10,5,4,9,5,4,7,6,6,11,3,9,4,7,3,9,2,11,7,9,3,7,8,1,11,9,8,3,2,9,4,7,11,2,5,7,11,5,3,9,10,11,7,2,2,2,2,2~5,3,2,8,6,10,2,7,5,2,8,6,10,4,9,5,2,8,6,6,11,2,3,10,2,5,1,8,4,10,8,5,10,3,8,2,4,3,10,5,2,4,10,1,11,8,9,3,2,10,4,1,8,7,2,2,2,2,2~5,11,1,2,5,6,10,2,4,6,9,3,2,4,1,8,3,4,5,6,6,11,8,4,3,2,7,4,5,10,3,5,1,9,2,5,3,2,7,2,2,2,2,2~3,4,6,5,1,11,4,1,7,6,5,3,10,2,8,6,6,9,5,2,4,1,2,2,2,2,2~4,3,5,6,10,1,3,7,6,4,2,11,1,6,6,9,1,5,8,2,2,2,2,2&reel_set12=11,5,6,7,9,1,4,5,11,6,10,5,4,9,5,4,7,6,6,11,3,9,4,7,3,9,2,11,7,9,3,7,8,1,11,9,8,3,2,9,4,7,11,2,5,7,11,5,3,9,10,11,7,2,2,2,2,2~5,3,2,8,6,10,2,7,5,2,8,6,10,4,9,5,2,8,6,6,11,2,3,10,2,5,1,8,4,10,8,5,10,3,8,2,4,3,10,5,2,4,10,1,11,8,9,3,2,10,4,1,8,7,2,2,2,2,2~5,11,1,2,5,6,10,2,4,6,9,3,2,4,1,8,3,4,5,6,6,11,8,4,3,2,7,4,5,10,3,5,1,9,2,5,3,2,7,2,2,2,2,2~3,4,6,5,1,11,4,1,7,6,5,3,10,2,8,6,6,9,5,2,4,1,2,2,2,2,2~4,3,5,6,10,1,3,7,6,4,2,11,1,6,6,9,1,5,8,2,2,2,2,2&purInit_e=1,1&reel_set13=11,5,6,7,9,1,4,5,11,6,10,5,4,9,5,4,7,6,6,11,3,9,4,7,3,9,2,11,7,9,3,7,8,1,11,9,8,3,2,9,4,7,11,2,5,7,11,5,3,9,10,11,7,2,2,2,2,2~5,3,2,8,6,10,2,7,5,2,8,6,10,4,9,5,2,8,6,6,11,2,3,10,2,5,1,8,4,10,8,5,10,3,8,2,4,3,10,5,2,4,10,1,11,8,9,3,2,10,4,1,8,7,2,2,2,2,2~5,11,1,2,5,6,10,2,4,6,9,3,2,4,1,8,3,4,5,6,6,11,8,4,3,2,7,4,5,10,3,5,1,9,2,5,3,2,7,2,2,2,2,2~3,4,6,5,1,11,4,1,7,6,5,3,10,2,8,6,6,9,5,2,4,1,2,2,2,2,2~4,3,5,6,10,1,3,7,6,4,2,11,1,6,6,9,1,5,8,2,2,2,2,2&sh=3&wilds=2~1000,100,30,0,0~1,1,1,1,1&bonuses=0&st=rect&c='.$bet.'&sw=5&sver=5&bls=10,15&counter=2&ntp=0.00&reel_set14=11,5,6,7,9,1,4,5,11,6,10,5,4,9,5,4,7,6,6,11,3,9,4,7,3,9,2,11,7,9,3,7,8,1,11,9,8,3,2,9,4,7,11,2,5,7,11,5,3,9,10,11,7,2,2,2,2,2~5,3,2,8,6,10,2,7,5,2,8,6,10,4,9,5,2,8,6,6,11,2,3,10,2,5,1,8,4,10,8,5,10,3,8,2,4,3,10,5,2,4,10,1,11,8,9,3,2,10,4,1,8,7,2,2,2,2,2~5,11,1,2,5,6,10,2,4,6,9,3,2,4,1,8,3,4,5,6,6,11,8,4,3,2,7,4,5,10,3,5,1,9,2,5,3,2,7,2,2,2,2,2~3,4,6,5,1,11,4,1,7,6,5,3,10,2,8,6,6,9,5,2,4,1,2,2,2,2,2~4,3,5,6,10,1,3,7,6,4,2,11,1,6,6,9,1,5,8,2,2,2,2,2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;1000,100,30,0,0;250,50,10,0,0;100,20,5,0,0;100,20,5,0,0;50,10,2,0,0;50,10,2,0,0;30,5,1,0,0;30,5,1,0,0;30,5,1,0,0&l=10&reel_set15=11,5,6,7,9,1,4,5,11,6,10,5,4,9,5,4,7,6,6,11,3,9,4,7,3,9,2,11,7,9,3,7,8,1,11,9,8,3,2,9,4,7,11,2,5,7,11,5,3,9,10,11,7,2,2,2,2,2~5,3,2,8,6,10,2,7,5,2,8,6,10,4,9,5,2,8,6,6,11,2,3,10,2,5,1,8,4,10,8,5,10,3,8,2,4,3,10,5,2,4,10,1,11,8,9,3,2,10,4,1,8,7,2,2,2,2,2~5,11,1,2,5,6,10,2,4,6,9,3,2,4,1,8,3,4,5,6,6,11,8,4,3,2,7,4,5,10,3,5,1,9,2,5,3,2,7,2,2,2,2,2~3,4,6,5,1,11,4,1,7,6,5,3,10,2,8,6,6,9,5,2,4,1,2,2,2,2,2~4,3,5,6,10,1,3,7,6,4,2,11,1,6,6,9,1,5,8,2,2,2,2,2&total_bet_max=27,000,000.00&reel_set0=11,5,6,7,9,1,4,5,11,6,10,5,4,9,5,4,7,6,6,11,3,9,4,7,3,9,2,11,7,9,3,7,8,1,11,9,8,3,2,9,4,7,11,2,5,7,11,5,3,9,10,11,7,2,2,2,2,2~5,3,2,8,6,10,2,7,5,2,8,6,10,4,9,5,2,8,6,6,11,2,3,10,2,5,1,8,4,10,8,5,10,3,8,2,4,3,10,5,2,4,10,1,11,8,9,3,2,10,4,1,8,7,2,2,2,2,2~5,11,1,2,5,6,10,2,4,6,9,3,2,4,1,8,3,4,5,6,6,11,8,4,3,2,7,4,5,10,3,5,1,9,2,5,3,2,7,2,2,2,2,2~3,4,6,5,1,11,4,1,7,6,5,3,10,2,8,6,6,9,5,2,4,1,2,2,2,2,2~4,3,5,6,10,1,3,7,6,4,2,11,1,6,6,9,1,5,8,2,2,2,2,2&s='.$lastReelStr.'&reel_set2=11,5,6,7,9,1,4,5,11,6,10,5,4,9,5,4,7,6,6,11,3,9,4,7,3,9,2,11,7,9,3,7,8,1,11,9,8,3,2,9,4,7,11,2,5,7,11,5,3,9,10,11,7,2,2,2,2,2~5,3,2,8,6,10,2,7,5,2,8,6,10,4,9,5,2,8,6,6,11,2,3,10,2,5,1,8,4,10,8,5,10,3,8,2,4,3,10,5,2,4,10,1,11,8,9,3,2,10,4,1,8,7,2,2,2,2,2~5,11,1,2,5,6,10,2,4,6,9,3,2,4,1,8,3,4,5,6,6,11,8,4,3,2,7,4,5,10,3,5,1,9,2,5,3,2,7,2,2,2,2,2~3,4,6,5,1,11,4,1,7,6,5,3,10,2,8,6,6,9,5,2,4,1,2,2,2,2,2~4,3,5,6,10,1,3,7,6,4,2,11,1,6,6,9,1,5,8,2,2,2,2,2&reel_set1=11,5,6,7,9,1,4,5,11,6,10,5,4,9,5,4,7,6,6,11,3,9,4,7,3,9,2,11,7,9,3,7,8,1,11,9,8,3,2,9,4,7,11,2,5,7,11,5,3,9,10,11,7,2,2,2,2,2~5,3,2,8,6,10,2,7,5,2,8,6,10,4,9,5,2,8,6,6,11,2,3,10,2,5,1,8,4,10,8,5,10,3,8,2,4,3,10,5,2,4,10,1,11,8,9,3,2,10,4,1,8,7,2,2,2,2,2~5,11,1,2,5,6,10,2,4,6,9,3,2,4,1,8,3,4,5,6,6,11,8,4,3,2,7,4,5,10,3,5,1,9,2,5,3,2,7,2,2,2,2,2~3,4,6,5,1,11,4,1,7,6,5,3,10,2,8,6,6,9,5,2,4,1,2,2,2,2,2~4,3,5,6,10,1,3,7,6,4,2,11,1,6,6,9,1,5,8,2,2,2,2,2&reel_set4=11,5,6,7,9,1,4,5,11,6,10,5,4,9,5,4,7,6,6,11,3,9,4,7,3,9,2,11,7,9,3,7,8,1,11,9,8,3,2,9,4,7,11,2,5,7,11,5,3,9,10,11,7,2,2,2,2,2~5,3,2,8,6,10,2,7,5,2,8,6,10,4,9,5,2,8,6,6,11,2,3,10,2,5,1,8,4,10,8,5,10,3,8,2,4,3,10,5,2,4,10,1,11,8,9,3,2,10,4,1,8,7,2,2,2,2,2~5,11,1,2,5,6,10,2,4,6,9,3,2,4,1,8,3,4,5,6,6,11,8,4,3,2,7,4,5,10,3,5,1,9,2,5,3,2,7,2,2,2,2,2~3,4,6,5,1,11,4,1,7,6,5,3,10,2,8,6,6,9,5,2,4,1,2,2,2,2,2~4,3,5,6,10,1,3,7,6,4,2,11,1,6,6,9,1,5,8,2,2,2,2,2&purInit=[{bet:1000},{bet:2700}]&reel_set3=11,5,6,7,9,1,4,5,11,6,10,5,4,9,5,4,7,6,6,11,3,9,4,7,3,9,2,11,7,9,3,7,8,1,11,9,8,3,2,9,4,7,11,2,5,7,11,5,3,9,10,11,7,2,2,2,2,2~5,3,2,8,6,10,2,7,5,2,8,6,10,4,9,5,2,8,6,6,11,2,3,10,2,5,1,8,4,10,8,5,10,3,8,2,4,3,10,5,2,4,10,1,11,8,9,3,2,10,4,1,8,7,2,2,2,2,2~5,11,1,2,5,6,10,2,4,6,9,3,2,4,1,8,3,4,5,6,6,11,8,4,3,2,7,4,5,10,3,5,1,9,2,5,3,2,7,2,2,2,2,2~3,4,6,5,1,11,4,1,7,6,5,3,10,2,8,6,6,9,5,2,4,1,2,2,2,2,2~4,3,5,6,10,1,3,7,6,4,2,11,1,6,6,9,1,5,8,2,2,2,2,2&reel_set6=11,5,6,7,9,1,4,5,11,6,10,5,4,9,5,4,7,6,6,11,3,9,4,7,3,9,2,11,7,9,3,7,8,1,11,9,8,3,2,9,4,7,11,2,5,7,11,5,3,9,10,11,7,2,2,2,2,2~5,3,2,8,6,10,2,7,5,2,8,6,10,4,9,5,2,8,6,6,11,2,3,10,2,5,1,8,4,10,8,5,10,3,8,2,4,3,10,5,2,4,10,1,11,8,9,3,2,10,4,1,8,7,2,2,2,2,2~5,11,1,2,5,6,10,2,4,6,9,3,2,4,1,8,3,4,5,6,6,11,8,4,3,2,7,4,5,10,3,5,1,9,2,5,3,2,7,2,2,2,2,2~3,4,6,5,1,11,4,1,7,6,5,3,10,2,8,6,6,9,5,2,4,1,2,2,2,2,2~4,3,5,6,10,1,3,7,6,4,2,11,1,6,6,9,1,5,8,2,2,2,2,2&reel_set5=11,5,6,7,9,1,4,5,11,6,10,5,4,9,5,4,7,6,6,11,3,9,4,7,3,9,2,11,7,9,3,7,8,1,11,9,8,3,2,9,4,7,11,2,5,7,11,5,3,9,10,11,7,2,2,2,2,2~5,3,2,8,6,10,2,7,5,2,8,6,10,4,9,5,2,8,6,6,11,2,3,10,2,5,1,8,4,10,8,5,10,3,8,2,4,3,10,5,2,4,10,1,11,8,9,3,2,10,4,1,8,7,2,2,2,2,2~5,11,1,2,5,6,10,2,4,6,9,3,2,4,1,8,3,4,5,6,6,11,8,4,3,2,7,4,5,10,3,5,1,9,2,5,3,2,7,2,2,2,2,2~3,4,6,5,1,11,4,1,7,6,5,3,10,2,8,6,6,9,5,2,4,1,2,2,2,2,2~4,3,5,6,10,1,3,7,6,4,2,11,1,6,6,9,1,5,8,2,2,2,2,2&reel_set8=11,5,6,7,9,1,4,5,11,6,10,5,4,9,5,4,7,6,6,11,3,9,4,7,3,9,2,11,7,9,3,7,8,1,11,9,8,3,2,9,4,7,11,2,5,7,11,5,3,9,10,11,7,2,2,2,2,2~5,3,2,8,6,10,2,7,5,2,8,6,10,4,9,5,2,8,6,6,11,2,3,10,2,5,1,8,4,10,8,5,10,3,8,2,4,3,10,5,2,4,10,1,11,8,9,3,2,10,4,1,8,7,2,2,2,2,2~5,11,1,2,5,6,10,2,4,6,9,3,2,4,1,8,3,4,5,6,6,11,8,4,3,2,7,4,5,10,3,5,1,9,2,5,3,2,7,2,2,2,2,2~3,4,6,5,1,11,4,1,7,6,5,3,10,2,8,6,6,9,5,2,4,1,2,2,2,2,2~4,3,5,6,10,1,3,7,6,4,2,11,1,6,6,9,1,5,8,2,2,2,2,2&reel_set7=11,5,6,7,9,1,4,5,11,6,10,5,4,9,5,4,7,6,6,11,3,9,4,7,3,9,2,11,7,9,3,7,8,1,11,9,8,3,2,9,4,7,11,2,5,7,11,5,3,9,10,11,7,2,2,2,2,2~5,3,2,8,6,10,2,7,5,2,8,6,10,4,9,5,2,8,6,6,11,2,3,10,2,5,1,8,4,10,8,5,10,3,8,2,4,3,10,5,2,4,10,1,11,8,9,3,2,10,4,1,8,7,2,2,2,2,2~5,11,1,2,5,6,10,2,4,6,9,3,2,4,1,8,3,4,5,6,6,11,8,4,3,2,7,4,5,10,3,5,1,9,2,5,3,2,7,2,2,2,2,2~3,4,6,5,1,11,4,1,7,6,5,3,10,2,8,6,6,9,5,2,4,1,2,2,2,2,2~4,3,5,6,10,1,3,7,6,4,2,11,1,6,6,9,1,5,8,2,2,2,2,2&reel_set9=11,5,6,7,9,1,4,5,11,6,10,5,4,9,5,4,7,6,6,11,3,9,4,7,3,9,2,11,7,9,3,7,8,1,11,9,8,3,2,9,4,7,11,2,5,7,11,5,3,9,10,11,7,2,2,2,2,2~5,3,2,8,6,10,2,7,5,2,8,6,10,4,9,5,2,8,6,6,11,2,3,10,2,5,1,8,4,10,8,5,10,3,8,2,4,3,10,5,2,4,10,1,11,8,9,3,2,10,4,1,8,7,2,2,2,2,2~5,11,1,2,5,6,10,2,4,6,9,3,2,4,1,8,3,4,5,6,6,11,8,4,3,2,7,4,5,10,3,5,1,9,2,5,3,2,7,2,2,2,2,2~3,4,6,5,1,11,4,1,7,6,5,3,10,2,8,6,6,9,5,2,4,1,2,2,2,2,2~4,3,5,6,10,1,3,7,6,4,2,11,1,6,6,9,1,5,8,2,2,2,2,2&total_bet_min=200.00';
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
                $pur = -1;
                if(isset($slotEvent['pur'])){
                    $pur = $slotEvent['pur'];
                }
                $bl = $slotEvent['bl'];
                $slotEvent['slotLines'] = 10;
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
                    if( $slotEvent['slotEvent'] == 'doSpin' && $slotSettings->GetBalance() < ($lines * $betline)  && $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') < 0) 
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
                
                $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $betline * $lines, $lines, $bl);
                $winType = $_spinSettings[0];
                $_winAvaliableMoney = $_spinSettings[1];

                // $winType = 'bonus';

                $allBet = $betline * $lines;
                $purMuls = [100, 270, 500];
                if($pur >= 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $allBet * $purMuls[$pur];
                }else if($bl > 0){
                    $allBet = $betline * 15;
                }
                $tumbAndFreeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'freespin' || $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') >= 0){
                    if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') < 0){
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
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
                    if($winType == 'bonus'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'IsBonusBank', 1);
                    }else{
                        $slotSettings->SetGameData($slotSettings->slotId . 'IsBonusBank', 0);
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bl', $bl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinWin', 0);
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
                $bonusMpl = 1;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $str_initReel = '';
                $fsmore = 0;
                $str_stf = '';
                $str_srf = '';
                $str_trail = '';
                $fsmore = 0;
                $str_rs_p = '';
                $str_rs_c = '';
                $str_rs_m = '';
                $str_rs_t = '';
                $str_rs_more = '';
                $str_rs_iw = '';
                $str_rs_win = '';
                $str_rs = '';
                $fsmax = 0;
                $strReelSa = '';
                $strReelSb = '';
                $str_s_mark = '';
                $str_sts = '';
                $str_sty = '';
                $str_pw = '';
                $str_accm = '';
                $str_accv = '';
                $str_psym = '';
                $str_lmi = '';
                $str_lmv = '';
                $str_slm_mp = '';
                $str_slm_mv = '';
                $str_rmul = '';
                $str_aam = '';
                $str_aav = '';
                $str_mo = '';
                $str_mo_t = '';
                $mo_tv = 0;
                $apv = 0;
                $wmv = 0;
                if($slotEvent['slotEvent'] == 'freespin' || $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') >= 0){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_initReel = $stack['initreel'];
                    $currentReelSet = $stack['reel_set'];
                    $fsmore = $stack['fsmore'];
                    $str_stf = $stack['stf'];
                    $str_srf = $stack['srf'];
                    $str_rs_p = $stack['rs_p'];
                    $str_rs_c = $stack['rs_c'];
                    $str_rs_m = $stack['rs_m'];
                    $str_rs_t = $stack['rs_t'];
                    $str_rs_more = $stack['rs_more'];
                    $str_rs_iw = $stack['rs_iw'];
                    $str_rs_win = $stack['rs_win'];
                    $str_rs = $stack['rs'];
                    $strReelSa = $stack['sa'];
                    $strReelSb = $stack['sb'];
                    $str_s_mark = $stack['s_mark'];
                    $str_sts = $stack['sts'];
                    $str_sty = $stack['sty'];
                    $wmv = $stack['wmv'];
                    $fsmax = $stack['fsmax'];
                    $str_trail = $stack['trail'];
                    $apv = $stack['apv'];
                    $str_pw = $stack['pw'];
                    $str_psym = $stack['psym'];
                    $str_accm = $stack['accm'];
                    $str_accv = $stack['accv'];
                    $str_aam = $stack['aam'];
                    $str_aav = $stack['aav'];
                    $str_lmi = $stack['lmi'];
                    $str_lmv = $stack['lmv'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $mo_tv = $stack['mo_tv'];
                    $str_slm_mp = $stack['slm_mp'];
                    $str_slm_mv = $stack['slm_mv'];
                    $strWinLine = $stack['win_line'];
                    $str_rmul = $stack['rmul'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines, $pur);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $lastReel = explode(',', $stack[0]['reel']);
                    $str_initReel = $stack[0]['initreel'];
                    $currentReelSet = $stack[0]['reel_set'];
                    $fsmore = $stack[0]['fsmore'];
                    $str_stf = $stack[0]['stf'];
                    $str_srf = $stack[0]['srf'];
                    $str_rs_p = $stack[0]['rs_p'];
                    $str_rs_c = $stack[0]['rs_c'];
                    $str_rs_m = $stack[0]['rs_m'];
                    $str_rs_t = $stack[0]['rs_t'];
                    $str_rs_more = $stack[0]['rs_more'];
                    $str_rs_iw = $stack[0]['rs_iw'];
                    $str_rs_win = $stack[0]['rs_win'];
                    $str_rs = $stack[0]['rs'];
                    $strReelSa = $stack[0]['sa'];
                    $strReelSb = $stack[0]['sb'];
                    $str_s_mark = $stack[0]['s_mark'];
                    $str_sts = $stack[0]['sts'];
                    $str_sty = $stack[0]['sty'];
                    $wmv = $stack[0]['wmv'];
                    $fsmax = $stack[0]['fsmax'];
                    $str_trail = $stack[0]['trail'];
                    $apv = $stack[0]['apv'];
                    $str_pw = $stack[0]['pw'];
                    $str_psym = $stack[0]['psym'];
                    $str_accm = $stack[0]['accm'];
                    $str_accv = $stack[0]['accv'];
                    $str_aam = $stack[0]['aam'];
                    $str_aav = $stack[0]['aav'];
                    $str_lmi = $stack[0]['lmi'];
                    $str_lmv = $stack[0]['lmv'];
                    $str_mo = $stack[0]['mo'];
                    $str_mo_t = $stack[0]['mo_t'];
                    $mo_tv = $stack[0]['mo_tv'];
                    $str_slm_mp = $stack[0]['slm_mp'];
                    $str_slm_mv = $stack[0]['slm_mv'];
                    $strWinLine = $stack[0]['win_line'];
                    $str_rmul = $stack[0]['rmul'];
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
                $str_apaw = '';
                if($apv > 0){
                    $arr_apvs = explode(',', $apv);
                    $arr_apaw = [];
                    for($k = 0; $k < count($arr_apvs); $k++){
                        $apaw = $arr_apvs[$k] * $betline;
                        $totalWin = $totalWin + $apaw;
                        $arr_apaw[] = $apaw;
                    }
                    $str_apaw = implode(',', $arr_apaw);
                }
                $mo_tw = 0;
                if($mo_tv > 0){
                    $mo_tw = $mo_tv * $betline;
                    $totalWin = $totalWin + $mo_tw;
                }
                if($str_psym != ''){
                    $arr_psym = explode('~', $str_psym);
                    $arr_psym[1] = str_replace(',', '', $arr_psym[1]) / $original_bet * $betline;
                    $totalWin = $totalWin + $arr_psym[1];
                    $str_psym = implode('~', $arr_psym);
                }

                $spinType = 's';
                if( $totalWin > 0) 
                {
                    $spinType = 'c';
                    $slotSettings->SetBalance($totalWin);
                    if($slotSettings->GetGameData($slotSettings->slotId . 'IsBonusBank') > 0){
                        $slotSettings->SetBank(('bonus'), -1 * $totalWin);
                    }else{
                        if($str_rs == 'fs'){
                            $slotSettings->SetBank(('bonus'), -1 * $totalWin);
                        }else{
                            $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                        }
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
                // $reelA = [];
                // $reelB = [];
                // for($i = 0; $i < 5; $i++){
                //     $reelA[$i] = mt_rand(4, 8);
                //     $reelB[$i] = mt_rand(4, 8);
                // }
                // $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                // $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
                $strLastReel = implode(',', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                if($str_rs_p != ''){
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', 0);
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                }else{
                    if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') >= 0){
                        $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                }
                $strOtherResponse = '';
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $spinType = 's';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $isEnd = false;
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 && $str_rs_p == '') 
                    {
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsend_total=1&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
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
                    if($fsmax > 0){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1';
                    }
                }
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                }
                if($pur >= 0){
                    $strOtherResponse = $strOtherResponse . '&purtr=1';
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($str_stf != ''){
                    $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                }
                if($str_srf != ''){
                    $strOtherResponse = $strOtherResponse . '&srf=' . $str_srf;
                }
                if($str_trail != ''){
                    $arr_trail = explode(';', $str_trail);
                    for($k = 0; $k < count($arr_trail); $k++){
                        if(strpos($arr_trail[$k], 'nmwin') !== false)
                        {
                            $arr_nmwin = explode('~', $arr_trail[$k]);
                            if(count($arr_nmwin) == 2)
                            {
                                $arr_nmwin[1] = '' . (str_replace(',', '', $arr_nmwin[1]) / $original_bet * $betline);
                            }
                            $arr_trail[$k] = implode('~', $arr_nmwin);
                        }
                    }
                    $strOtherResponse = $strOtherResponse . '&trail=' . implode(';', $arr_trail);
                }
                if($str_s_mark != ''){
                    $strOtherResponse = $strOtherResponse . '&s_mark=' . $str_s_mark;
                }
                if($str_rs_p != ''){
                    $isState = false;
                    $spinType = 's';
                    $strOtherResponse = $strOtherResponse . '&rs_p=' . $str_rs_p . '&rs_m=' . $str_rs_m . '&rs_c=' . $str_rs_c;
                }
                if($str_rs_t != ''){
                    $strOtherResponse = $strOtherResponse . '&rs_t=' . $str_rs_t;
                    if($slotEvent['slotEvent'] != 'freespin' && $fsmax <= 0){
                        $spinType = 'c';
                    }
                }
                if($str_rs != ''){
                    $strOtherResponse = $strOtherResponse . '&rs=' . $str_rs;
                }
                if($str_rs_iw != ''){
                    if(is_numeric($str_rs_iw)){
                        $strOtherResponse = $strOtherResponse . '&rs_iw=' . (str_replace(',', '', $str_rs_iw) / $original_bet * $betline);
                    }
                }
                if($str_rs_win != ''){
                    if(is_numeric($str_rs_win)){
                        $strOtherResponse = $strOtherResponse . '&rs_win=' . (str_replace(',', '', $str_rs_win) / $original_bet * $betline);
                    }                    
                    
                }
                if($str_pw != ''){
                    $strOtherResponse = $strOtherResponse . '&pw=' . (str_replace(',', '', $str_pw) / $original_bet * $betline);
                }
                if($str_accv != ''){
                    $strOtherResponse = $strOtherResponse . '&accm=' . $str_accm . '&acci=0&accv=' . $str_accv;
                }
                if($str_aav != ''){
                    $strOtherResponse = $strOtherResponse . '&aam=' . $str_aam . '&aav=' . $str_aav;
                }
                if($str_psym != ''){
                    $strOtherResponse = $strOtherResponse . '&psym=' . $str_psym;
                }
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                }
                if($mo_tv > 0){
                    $strOtherResponse = $strOtherResponse . '&mo_tv=' . $mo_tv . '&mo_tw=' . $mo_tw;
                }
                if($str_lmv != ''){
                    $strOtherResponse = $strOtherResponse . '&lmi=' . $str_lmi . '&lmv=' . $str_lmv;
                }
                if($str_slm_mv != ''){
                    $strOtherResponse = $strOtherResponse . '&slm_mp=' . $str_slm_mp . '&slm_mv=' . $str_slm_mv;
                }
                if($str_rs_more != ''){
                    $strOtherResponse = $strOtherResponse . '&rs_more=' . $str_rs_more;
                }
                if($wmv > 0){
                    $strOtherResponse = $strOtherResponse . '&wmt=pr&wmv=' . $wmv;
                    if($wmv > 1){
                        $strOtherResponse = $strOtherResponse . '&gwm=' . $wmv;
                    }
                }
                if($str_sts != ''){
                    $strOtherResponse = $strOtherResponse . '&sts=' . $str_sts;
                }
                if($str_sty != ''){
                    $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                }
                if($str_rmul != ''){
                    $strOtherResponse = $strOtherResponse . '&rmul=' . $str_rmul;
                }
                if($apv > 0){
                    $strOtherResponse = $strOtherResponse . '&apaw=' . $str_apaw . '&apt=ma&apv=' . $apv;
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance . '&reel_set='. $currentReelSet.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&st=rect&c='.$betline.'&sw=7&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=10&w='.$totalWin.'&s=' . $strLastReel;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) && $str_rs_p == '') 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if( $slotEvent['slotEvent'] != 'freespin' && $fsmax > 0 && $str_rs == '') 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"CurrentRespin":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RespinWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin')  . ',"Bl":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bl'). ',"IsBonusBank":' . $slotSettings->GetGameData($slotSettings->slotId . 'IsBonusBank') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if(($slotEvent['slotEvent'] == 'freespin' || ($str_rs_p == '' && $str_rs_t != '')) && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * $purMuls[$slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin')];
                }else if($slotSettings->GetGameData($slotSettings->slotId . 'Bl') > 0){
                    $allBet = $betline * 15;
                }
                if($str_rs_p == '' && $str_rs_t != '' && $isState == true){
                    $slotEvent['slotEvent'] == 'doRespin';
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

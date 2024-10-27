<?php 
namespace VanguardLTE\Games\FortuneHitnRollPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 40);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [6,12,9,7,11,8,13,8,8,13,6,12,11,6,12,5,9,5,7,10,8,13,6,6,12]);
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
                    $bet = '25.00';
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
                        $strOtherResponse = $strOtherResponse . '&rs_iw=' . (str_replace(',', '', $str_rs_iw) / $original_bet * $bet);
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
                $response = 'def_s=6,12,9,7,11,8,13,8,8,13,6,12,11,6,12,5,9,5,7,10,8,13,6,6,12&balance='. $Balance .'&cfgs=12797&ver=3&index=1&balance_cash='. $Balance .'&def_sb=8,10,8,7,12&reel_set_size=12&def_sa=7,9,5,8,9&reel_set=0&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"10000000",max_rnd_win:"5000"}}&wl_i=tbm~5000&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime='. floor(microtime(true) * 1000) .'&sa=7,9,5,8,9&sb=8,10,8,7,12&reel_set10=8,13,5,11,11,12,11,7,10,9,12,7,11,6,12,4,10,8,13,6,7,4,5,13,4,12,10,12,8,3,9,6,13,12,7,13,3,13,12,7,12,11,10,7,9,12,9,3,7,6,5,13,10,12,11,8,13,5,8,11,4,2,11,11,10,8,5,7,9,8,12,13,11,9,11,13,5,11,7,12,10,12,8,4,12,13,13,7,9,3,10,2,6,13,8,11,5,6,9,8,13,13,12,10,9,8,9,11,10,13~13,7,2,13,5,7,8,10,3,13,9,12,13,9,12,11,12,9,6,12,5,6,12,11,4,7,13,4,13,8,2,12,9,12,13,13,10,11,10,13,13,9,12,10,13,7,2,10,6,10,13,8,13,6,9,8,10,7,3,12,7,4,12,11,13,9,11,3,12,11,7,13,9,5,9,4,5,2,8,11,9,12,6,11,7,12,5,11,12,10,5,13,6,3,12,2,8,4,7,8,3,11,10,6,8,10~2,11,10,12,12,8,13,4,10,5,9,11,8,7,13,6,2,9,7,13,11,8,10,12,12,13,11,13,5,6,3,4,10,12,13,7,10,4,7,9,4,9,13,7,12,13,8,12,12,6,10,4,12,8,9,13,9,13,9,12,6,11,8,10,7,5,10,11,9,12,9,8,5,9,2,12,6,9,12,2,3,13,10,11,12,12,11,3,11,7,12,6,12,11,2,13,12,8,11,5,11,13,11,5,8,11,8,10,6,5,4,13,8,6,13,10,7,11,7,13~7,5,8,4,9,10,8,6,5,3,12,5,10,6,9,2,7,6,13,9,4,11,13,9,3,12,10,9,12,12,4,13,9,11,13,12,8,12,10,11,3,12,8,11,8,6,7,6,13,9,5,9,13,11,6,5,10,8,11,11,8,7,11,9,11,12,10,12,7,13,5,12,11,13,12,13,11,13,11,10,8,13,7,13,12,10,12,10~12,11,11,3,8,12,9,5,6,11,9,12,13,5,6,12,8,13,9,13,9,10,13,5,13,11,12,10,11,5,13,10,7,5,9,12,11,8,13,11,11,10,11,7,5,8,4,10,2,3,4,11,7,12,13,12,13,8,11,8,4,11,6,13,10,6,9,13,12,9,3,8,12,13,9,6,10,12,8,4,13,8,3,2,12,10,13,6,8,9,11,9,12,8,9,10,7,10,12,7,11,9,6,7,4,7,5,4,12,13,5,11,7,13,13,12,13,11,7,10&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=25.00&reel_set11=6,4,12,5,8,9,11,7,11,10,11,12,9,11,5,11,10,2,13,5,3,8,13,11,13,10,6,7,2,10,12,2,3,4,8,9,2,13,12,7,11,8,13,11,8,11,6,9,5,4,9,5,13,9,7,3,6,3,10,9,10,6,7,6,13,5,13,7,2,4,8,9,11,6,2,7,5,7,12,8,5,9,13,8,13,2,10,13,8,10,3,12,7,12,6,10,11,9,12,4,12,4,2,3,12,8,4,9,13,12,6,10,6,2,11,13,10~2,11,5,11,3,2,6,10,8,11,7,12,4,12,3,11,12,2,11,9,6,9,13,7,9,10,2,6,7,13,4,9,7,8,4,8,12,13,10,3,13,5,7,10,4,13,5,10,7,2,12,2,10,13,6,8,12,2,6,12,8,2,4,9,5,11,13,12,3,6,8,2,10,8,12,11,6,4,9,5,7,11,13,9,3,9,13,5,8~3,6,7,11,4,12,13,12,8,5,13,12,10,12,7,5,8,11,13,9,13,6,2,5,4,13,7,12,13,11,12,8,3,2,6,11,10,3,6,10,5,7,8,7,11,12,8,11,10,9,2,11,10,13,11,8,13,9,6,9,12,2,3,10,3,6,7,9,2,11,8,12,10,9,7,5,13,11,10,8,4,12,2,11,2,4,5,4,9,11,7,8,5,10,6,10,13,7,11,10,2,5,9,6,2,7,13,4,13,5,8,3,12,2,3,13,6,12,8,12,11,9,2,4,10,11,9,8,13,2,12,7,13,4,6,13,9,3,6,11,7,10,4,9,12~13,4,7,13,10,11,8,3,6,12,3,12,9,3,5,6,12,11,4,2,13,8,13,4,11,2,11,6,2,11,3,7,8,13,6,10,9,5,7,13,8,10,13,7,11,2,5,4,11,7,3,7,2,3,8,10,5,9,12,7,8,11,2,6,9,8,12,5,13,12,10,12,6,10,9,11,8,9,6,9,11,9,4,2,6,13,2,9,5,12,4,6,8,10,5,10,11,7,13,10,7,8,12,13,12,2,12,10,2~6,12,2,7,8,7,4,6,11,4,3,12,13,12,3,12,10,13,6,2,3,8,2,6,11,7,11,13,4,5,8,9,2,10,13,6,3,11,13,11,4,2,5,12,10,5,13,11,12,8,5,13,10,3,8,11,7,9,7,5,9,10,9,11,12,8,9,10,9,5,10,3,9,8,11,10,12,7,6,5,13,9,7,12,2,10,13,8,11,4,2,12,7,4,7,9&purInit_e=1&sh=5&wilds=2~250,80,40,0,0~1,1,1,1,1&bonuses=0&st=rect&c='.$bet.'&sw=5&sver=5&counter=2&ntp=0.00&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;250,80,40,0,0;200,60,32,0,0;160,40,28,0,0;120,32,24,0,0;100,28,20,0,0;80,24,16,0,0;60,20,12,0,0;60,20,12,0,0;40,12,8,0,0;40,12,8,0,0;40,12,8,0,0&l=40&total_bet_max=10,000,000.00&reel_set0=10,11,5,11,7,11,11,8,11,11,9,11,5,11,11,9,10,1,11,11,5,11,5,11,12,7,8,11,11,8,11,12,8,7,11,10,9,11,8,4,11,1,12,8,11,3,1,11,9,4,7,10,13,11,13,5,11,11,5,11,11,11,6,11,9,11,11,8,11,11,2,11,9,11,8,3,6,5,11,11,13,5,3,11,11,10,2,10,12,6,11,1,7,8,6,10,11,11,12,8,11,11,10,11,12,7,9,13,11,11,9,4,7,12,11,3,8,7,11,11,10,11,11~12,10,12,3,5,12,12,8,13,12,8,12,8,10,12,12,5,3,12,13,3,4,12,12,4,10,12,10,8,12,12,9,9,12,7,6,3,8,12,12,4,10,12,12,6,8,12,3,6,12,12,7,12,12,10,12,12,5,12,4,10,12,12,3,8,12,12,13,13,12,10,3,12,12,12,7,12,8,12,12,13,3,7,9,9,10,3,12,13,10,12,7,12,4,12,12,3,12,6,12,3,12,10,12,12,10,12,12,5,3,6,12,8,2,12,7,10,12,13,5,12,12,9,8,7,12,7,12,8,10,12,2,6,8,11,6,12,13,11,5,8,12,6,12,10,8,12,12,3,10~13,12,9,6,13,13,12,13,13,6,13,7,13,13,10,9,6,13,13,11,13,8,13,3,5,10,10,13,13,4,13,2,13,9,9,4,6,13,1,13,13,8,4,9,13,8,10,6,12,4,3,8,13,13,7,8,12,10,7,13,11,8,13,9,12,13,13,4,13,2,3,13,8,13,4,13,13,5,13,8,3,13,4,13,12,13,13,1,13,13,6,9,8,9,12,12,13,9,13,13,8,13,12,5,4,12,10,13,6,13,12,8,6,13,13,3,12,13,9,11,13,13,9,13,10,13,13,7,13,8,9,7,13,13,5,8,13,6,10,1,5,13~11,9,11,11,5,11,11,11,5,7,5,10,11,11,5,8,11,5,11,10,11,12,11,11,11,10,5,11,11,8,3,7,11,11,7,11,11,11,9,8,13,10,11,12,11,11,8,11,11,12,11,11,12,6,3,8,5,3,8,12,6,3,5,12,11,9,9,2,8,11,11,11,2,6,11,7,12,10,11,7,4,11,13,12,9,9,11,4,6,9,8,10,7,11,8,10,6,10,11,8,12,9,10,11,11,11,10,9,3,10,7,11,10,11,8,11,4,9,11,11,12,11,4,11,11,11,5,10,9,11,4,11,11,10,11,11,11,12,11,10~13,7,13,13,5,13,6,10,12,8,12,1,13,13,12,9,13,2,13,13,11,1,13,12,13,4,13,9,13,9,13,13,3,13,13,9,8,11,13,5,9,13,13,9,13,2,13,5,13,13,5,13,11,13,13,13,8,4,13,13,6,7,5,13,4,6,7,13,7,9,13,11,6,3,8,13,1,10,12,6,8,13,9,10,13,8,12,10,3,13,4,1,8,9,10,13,13,6,12,8,13,3,13,13,7,4,9,8,13,12&s='.$lastReelStr.'&reel_set2=2,9,7,10,12,13,10,11,8,13,5,9,3,4,9,6,10,7,13,5,10,11,5,7,5,11,3,4,10,3,10,7,11,3,11,4,7,10,9,12,8,13,7,12,6,5,6,12,13,7,3,6,8,3,6,8,4,7,9,13,11,12,7,12,5,9,10,11,6,12,8,13,7,8,13,9,11,3,11,4,12,6,9,8,9,8,13,9,13,4,5,11,10,13,12,8,12,4,6,13,12,7,2,9,7,8,5,6,10,9,6,11,12,13,8,12,11~12,8,6,13,10,7,12,11,5,11,5,10,12,8,12,10,8,11,3,12,10,6,7,12,8,11,6,11,7,11,13,5,9,10,12,8,6,12,3,6,13,8,9,7,13,10,12,6,10,7,13,7,6,5,13,3,2,10,4,10,11,7,13,9,4,7,12,3,10,11,13,6,9,4,9,5,3,12,12,9,4,13,5,12,6,13,4,8,4,9,7,11,8,9,3,11,5,2,3,10,8,9,11,13,9,4,7,9,13,4,7,8,5,6,9,8,13~5,8,5,11,7,12,11,13,4,6,8,13,6,11,12,6,7,2,6,10,4,11,10,9,5,12,13,12,10,7,13,11,7,13,9,8,7,9,11,12,10,4,7,13,11,13,9,12,7,12,6,12,8,12,10,9,4,11,8,13,6,4,5,8,12,8,7,12,10,4,5,3,6,9,5,11,7,3,9,10,4,2,5,9,3,12,6,3,9,6,13,9,8,7,5,8,13,9,12,10,3,11,4,13,10,3,8,10,8,10,11,7,10,9,6,13,8,5,6,7,8,13,11,8,12,5~10,5,3,8,13,9,11,10,6,13,7,8,11,8,4,10,12,7,9,8,13,9,7,3,12,4,7,8,13,10,5,4,12,5,11,9,11,6,13,2,11,7,6,12,7,3,13,12,11,9,12,9,7,6,7,13,4,6,5,10,11,8,11,12,2,13,11,12,11,3,10,5,12,4,6,13,10,9,6,10,9,4,12,13,3,11,9,5,8,5,6,9,5,9,8,4,11,10,8,12,8,4,12,7,12,3,8,9,13,6,13,3,10,7,10,6,5~6,11,10,13,10,12,4,13,10,11,10,8,11,6,12,7,9,13,6,10,4,3,9,7,3,5,2,8,11,12,8,7,8,12,10,12,11,10,13,7,6,9,13,7,8,13,11,5,4,7,8,6,10,3,7,10,3,4,5,8,12,13,5,12,5,11,13,9,3,12,6,12,5,4,9,7,5,2,9,8,9,11,13,6,7,8,10,12,9,5,6,11,13,9,4,9,8&reel_set1=12,11,10,4,6,11,8,2,8,10,6,11,11,6,3,13,3,8,12,6,9,11,8,4,8,7,2,12,7,10,8,6,9,10,9,10,13,4,10,11,10,13,5,9,12,11,9,13,9,6,13,12,8,13,12,9,1,10,12,3,4,13,8,7,13,5,12,5,9,7,12,12,10,8,4,1,11,12,13,7,10,3,6,10,11,13,13,1,3,5,5,12,5,12,10,12,9,7,6,9,1,13,7,3,7,5,5,7,13,11,12,11,6,8,9,11,13,6,3,9,7,11,8,4,11,11,13~10,12,13,13,5,12,13,9,10,3,9,2,8,5,6,10,11,12,5,8,10,13,8,3,9,8,11,6,4,13,6,13,7,13,8,11,7,11,8,4,7,8,5,10,6,4,8,11,12,5,11,12,10,6,11,9,13,8,7,11,5,7,11,13,6,9,7,5,11,6,7,10,13,12,4,12,12,12,3,13,12,11,12,8,11,5,11,6,13,13,7,11,7,11,10,11,9,12,6,5,7,4,10,4,7,12,10,11,9,12,7,5,12,4,3,13,10,12,10,10,11,9,2,9,8,3,4,10,9,5,9,4,3,13,9,13,8,9,9,12,8,11,6,12,13,4,13,8,13,7,12,3,12,9,9~13,10,13,9,12,13,10,12,3,7,12,13,4,8,9,7,4,9,10,11,12,5,13,8,11,6,13,7,6,8,6,8,11,8,11,7,11,8,7,9,7,2,3,12,5,1,4,11,6,3,6,13,8,7,12,9,6,10,12,9,9,12,5,9,10,12,11,3,5,11,11,10,1,12,8,13,10,13,7,10,11,5,11,4,8,13,4,5,11,10,4,8,7,5,7,8,1,12,11,6,8,10,11,3,9,4,13,5,12,11,3,2,13,13,10,6,13,7,4,13,5,9,11,7,11,12,9,10,8,12,13,11,11,1,12,9,8,10,5,3,12,9,10,13,6,11,13,10,1,9,9,12,5,11,9,13,7,12,10,8,7,4,3,9,6,5,12,8,4,13,9,7,3,12,12,13,13,6,7,6,11,10,1,10,12~10,5,6,3,9,8,4,11,9,13,5,6,4,12,13,13,6,11,10,11,13,11,10,6,8,12,13,10,12,11,8,13,5,3,12,10,12,5,11,7,13,5,13,9,13,12,9,12,11,10,13,7,8,4,13,8,3,7,4,8,6,10,7,6,10,2,11,8,13,4,12,5,9,3,5,9,11,10,4,10,12,5,10,7,6,7,12,9,12,12,9,4,11,7,12,2,4,12,6,13,13,3,5,10,11,11,8,5,7,6,8,7,11,10,9,13,9,12,13,8,12,6,9,10,9,7,9,11,3,12,11,13,3,8~4,7,9,3,10,9,1,6,4,9,12,3,8,8,6,13,12,11,11,10,10,12,9,10,9,6,10,11,12,13,11,12,5,10,4,11,12,12,5,10,4,11,7,13,9,4,13,6,3,13,8,2,9,5,1,13,5,4,5,10,4,11,12,13,12,13,7,11,9,8,13,13,1,7,5,12,8,13,6,1,7,10,3,5,6,11,12,11,13,6,11,7,9,11,1,9,9,12,9,10,8,7,5,3,12,8,6,9,11,8,6,8,13,10,13,13,6,12,13,10,12,4,8,6,12,11,10,13,10,7,11,11,7,8,9,11,3,8,3,7,12,2,5&reel_set4=3,6,10,5,12,4,6,10,12,7,12,13,8,9,11,8,12,11,12,13,12,2,6,11,9,10,3,6,8,7,5,10,13,8,10,9,7,13,4,8,2,9,3,9,12,11,12,4,13,8,10,3,11,4,10,11,9,12,11,12,3,11,5,9,3,5,7,11,12,6,12,9,13,6,11,7,13,6,4,8,10,11,4,12,13,11,9,13,3,10,8,4,8,6,9,7,5,12,4,10,6,13,5,13,10,13,9,13,5,12,8,4,5,3,5,3,13,9,7,12,8,7,11,7,13,6,7~7,6,7,12,10,11,6,7,10,9,12,7,12,3,2,4,6,3,11,2,6,12,3,13,9,10,5,3,2,7,4,8,9,5,2,13,7,11,8,12,9,12,8,5,9,7,3,11,5,11,6,8,4,12,4,8,9,13,11,9,6,8,5,7,5,12,9,10,9,13,10,8,13,10,5,4,7,10,5,12,6,4,8,13,11,2,8,13,10,12,10,11,13,13,4~12,5,8,6,5,12,10,11,8,3,12,7,2,8,3,13,4,13,3,13,11,10,11,4,13,4,6,9,6,8,9,11,8,9,12,5,4,12,13,11,7,4,7,10,3,6,5,3,2,11,7,10,3,6,8,10,13,4,9,11,13,8,12,6,9,4,11,7,10,13,7,12,8,10,9,5,4,12,6,10,13,11,9,12,11,5,3,9,5,12,10,5,13,5,7,12,13,6,9,3,10,7,12,13,7,11,8,6,11,7,12,8~4,11,3,10,12,13,11,5,11,10,9,4,6,13,6,10,5,8,4,8,7,13,11,8,9,8,11,6,3,11,5,8,9,12,8,3,5,11,7,12,5,4,7,4,12,13,7,12,3,11,6,9,6,7,12,6,10,13,8,10,4,7,9,10,11,13,7,13,12,10,9,6,8,5,9,2,12,13,3,13,3,12,10,9,11~3,12,9,10,6,11,7,10,13,11,8,13,6,12,3,13,8,9,3,4,9,7,10,7,5,13,4,5,11,5,6,11,3,13,9,8,3,12,11,10,8,12,9,13,7,4,5,11,6,11,13,10,11,8,13,6,9,7,12,4,8,9,10,11,12,7,12,5,11,8,6,11,12,9,10,13,6,4,10,8,12,5,9,12,7,3,5,13,2&purInit=[{bet:4000,type:"default"}]&reel_set3=13,9,11,7,10,12,11,12,6,9,12,6,10,8,7,13,5,13,3,12,5,6,11,5,11,13,7,13,12,9,10,13,6,7,11,2,10,13,11,4,9,11,13,4,3,12,3,6,9,10,4,12,13,12,8,12,5,12,8,10,8,4,9,8,5,13,6,13,9,11,7,3,11,7,11,9,11,3,4,5,6,7,5,3,12,8,9,7,8,10~8,3,7,3,13,5,8,11,6,9,10,8,3,11,9,13,12,11,4,8,6,4,6,13,7,12,10,9,5,2,9,8,12,9,5,13,10,11,3,10,13,7,6,8,6,13,2,6,7,13,4,7,12,2,10,3,12,4,9,10,4,5,2,7,12,11,10,13,3,11,6,13,9,5,4,9,11,7,9,11,2,12,9,2,4,11,8,9,7,6,10,12,11,13,8,13,4,12,11,12,7,10,3,10,11,7,12,10,8,2,5,12,2,6,5,13~10,8,11,6,5,8,3,11,10,12,11,7,13,10,13,6,11,6,3,13,4,11,8,5,13,4,10,13,9,8,5,11,7,6,9,8,10,5,8,2,4,6,11,6,11,7,6,10,9,13,10,7,5,9,11,7,13,3,6,11,7,5,13,12,8,3,12,10,7,12,9,8,10,12,9,2,9,2,7,8,12,7,12,13,4,13,12,9,11,4,11,4,6,4,10,12,9,12,11,5,3,5,4,2,8,5,13,7,3~8,3,11,12,4,9,13,12,6,12,3,11,6,5,11,12,8,4,7,13,11,13,3,7,11,10,6,9,7,5,13,8,5,3,4,10,11,7,2,6,12,5,13,6,10,5,4,9,7,12,8,7,3,5,9,10,9,6,4,12,11,12,7,8,11,9,13,9,8,13,9,6,10,11,5,7,5,13,6,13,10,13,9,13,12,11,9,6,10,3,10,12,8,3,4,10,2,8,10,12,11,4,8~5,6,11,12,7,12,4,10,3,10,3,11,9,2,11,7,11,13,8,13,8,9,13,9,3,8,12,6,4,10,8,9,13,3,11,7,5,4,9,6,5,8,12,10,13,8,7,13,5,12,5,13,6,9,3,8,12,13,6,11,9,12,10,9,13,4,6,9,10,4,3,7,5,12,11,8,11,13,10,12,11,8,4,12,6,10,7,13,11&reel_set6=9,9,8,7,12,5,11,2,1,10,5,1,12,11,9,11,9,1,13,1,9,1,7,6,9,5,5,1,4,12,12,12,1,8,4,5,8,7,5,4,10,3,11,1,12,13,10,8,3,12,3,1,10,6,13,1,6,8,3,13,1,12,7~5,13,11,8,4,10,8,6,9,13,13,9,11,12,7,6,12,10,4,5,13,11,13,7,13,9,3,12,9,2,12,3,5,11,12,8,12,10,3,8,9,13,10,8,3,11,7,6,3,6,13,10,12,3,5,11~3,10,6,13,1,7,8,3,10,5,12,1,13,6,13,4,8,13,11,13,4,13,1,4,1,12,7,12,7,12,1,6,1,2,9,1,3,10,1,13,11,1,3,8,6,13,9,6,11,12,9,1,13,1,7,9,13,5,10,4,10,5,6,12,7,8,4,7,12,4,9,8,11,6,1,1,5,12~3,4,13,7,11,13,7,11,4,11,5,10,13,6,10,8,7,12,5,12,10,12,2,8,3,5,9,9,12,13,13,13,9,12,5,13,5,6,10,8,13,12,9,13,13,8,12,10,9,8,3,9,11,13,7,12,11,5,3,10,4,11~1,13,3,12,10,3,7,13,12,11,10,9,10,12,1,12,3,2,5,9,9,12,1,9,4,5,8,6,3,11,1,5,7,10,1,7,1,4,12,5,13,1,6,7,13,4,10,12,6,1,3,8,3,10,4,6,1,8,4,1,10,1,8,12,10,13,5,8,11,11,1,3,9,3,7,5,8,1,13,1,8,6&reel_set5=11,5,13,12,8,10,11,12,9,7,8,5,7,6,3,11,10,9,4,13,12,10,13,9,11,3,12,6,9,6,7,10,5,9,4,7,8,7,3,7,6,4,6,8,3,7,2,11,12,13,4,10,5,9,13,8,13,12,10,4,9,4,9,12,8,11,6,11,13,11,3,13,10,5,8,10,12,5,11,13,8,6,12~5,8,10,12,3,12,6,2,7,11,12,9,4,3,5,4,11,8,13,12,10,3,5,13,4,13,3,5,4,10,6,7,6,5,11,7,12,4,8,6,13,2,6,9,7,8,10,5,11,9,6,7,12,2,3,8,13,2,13,8,12,13,12,13,10,11,10,11,4,13,12,13,11,8,13,8,9,2,12,11,12,7,11,9,10,11,6,2,13,11,9,12,4,7,3,7,10,3,12,8,3,2,11,10,6,2,9,2,7,9,12,8,10,5,13,9,5,4,11,4,11,5,6,10,12,8,5,9,7,13,8,10,13,9,11,10,4,11,10,7,12,7~11,13,3,12,6,9,12,11,2,9,12,11,12,10,13,7,10,4,9,13,10,7,10,11,7,11,8,5,3,7,12,5,8,13,11,5,11,8,5,3,10,3,13,10,8,10,12,11,8,13,12,2,9,6,12,6,8,3,11,10,7,13,9,6,10,6,8,5,11,7,4,12,2,4,6,13,2,7,6,9,5,9,7,9,4,2,4,12,11,9,2,9,5,8,4,7,6~12,3,8,4,11,10,13,6,11,13,3,10,8,10,7,9,5,11,5,12,8,12,9,2,12,5,7,13,8,13,10,7,9,10,5,4,12,9,7,12,3,13,7,11,7,11,7,11,9,4,6,7,8,11,9,6,7,3,8,9,4,12,6,8,12,7,11,4,9,4,5,13,12,6,13,3,13,5,9,3,10,12,9,6,12,13,9,12,11,12,8,10,2,12,8,10,13,3,12,5,13,11,6,12,11,13,4,5,6,8,10,11,8,10,13,11,6,11,5,10,3,5,4,6,9,10,8,6,11,7~13,7,13,12,4,6,5,11,2,12,11,12,4,7,13,5,11,3,6,12,13,7,6,9,12,5,10,9,6,8,9,6,7,12,13,4,8,10,8,13,9,10,8,5,6,4,9,12,9,8,13,7,11,12,13,12,5,12,11,8,9,10,11,7,10,7,8,3,12,11,3,10,6,3,13,10,7,6,11,5,11,13,10,8,5,10,11,9,3,4,8,3&reel_set8=7,12,12,6,8,7,11,12,3,6,10,4,10,11,7,8,9,6,3,5,12,9,11,10,13,11,12,11,13,8,11,12,12,5,4,10,13,12,11,10,8,12,10,9,13,11,9,8,13,8,10,7,11,13,5,9,12,7,12,10,9,8,6,9,13,8,7,2,12,6,10,11,12,13,6,10,9,13,8,9,4,11,13,7,13,11~2,13,12,4,11,10,3,11,7,11,13,5,12,5,7,12,2,13,10,11,4,3,12,6,13,12,12,8,11,9,11,7,13,10,7,11,6,12,10,11,6,9,10,11,10,9,13,12,9,13,8,13,9,2,6,10,12,9,10,2,13,9,2,11,13,9,8,2,11,9,13,12,13,12,8,6,12,8,2,7,6,7,9,11,2,13,9,13,11,6,2,10,9,5,12,13,12,11,4,10,7,11,8,3,8,7,10,8~8,7,12,13,12,7,8,9,13,12,5,9,13,12,12,9,3,6,10,3,9,10,4,7,6,13,12,11,13,13,11,8,10,9,8,6,7,12,13,13,12,10,11,2,11,6,10,2,3,6,7,9,13,13,5,12,11,12,2,10,7,4,12,12,9,2,11,13,10,6,10,8,5,8,13,12,10,13,8,6,7,13,9,13,7,9,9,12,12,9,9,7,8,11,10,13,8,12,11,13,11,12,13,11,5,11,4,11,13~12,12,11,9,10,5,13,11,12,9,11,8,13,6,13,8,11,10,2,7,6,2,9,9,11,12,4,10,7,3,10,6,7,8,3,11,12,11,12,12,13,11,13,4,12,10,5,11,7,12,12,5,10,12,9,13,6,10,13,10,4,8,13,9,6,9,7,8,10,11,13,12,13,10,9,13,11,8,9,6,12,5,13,7,13,8,7,10,8,9,8,13,11,12,12,3,6,10,13~10,4,8,12,7,8,12,13,5,7,13,9,12,10,9,13,8,7,13,8,13,12,9,8,10,13,10,11,12,9,5,11,6,11,6,7,8,11,12,4,11,6,7,12,5,4,13,12,3,9,12,10,2,13,9,6,10,6,7,8,9,12,9,11,12,10,13,7,12,3,13,6,12,7,12,2,12,11,13,6,12,10,7,11,10,5,4,12,8,13,12,7,11,13,11,13,10,9,12,8,13,8,11,9,10,9,7,13,3,10,11,13,11,6,12&reel_set7=13,11,7,12,13,9,5,12,9,12,11,13,10,8,7,13,12,12,11,8,10,8,11,6,8,10,6,5,3,9,13,13,4,12,11,13,13,6,12,13,13,3,11,6,4,12,7,11,13,9,2,13,10,8,7,10,3,10,13,5,13,5,12,8,10,12,7,8,3,4,13,5,9,12,12,11,6,9,7,5,7,12,9,10,6,11,10,8,10,6,9,9,3,11,3,13,10,7,8,10,7,11,13,13,6,10,7,12,11,11,12,13,5,12,10,12,8,7,12,11,8,13,9,8,11,11,9,10,13,12,9,11,10,6,5,9,12,7,10,9,12,13,9,11,4,10,11,4,11,4,6,7,13,10,10,8,2,10,6,10,12,8,11,9,11,9,11,12,10,9,9,13,11,8,9,12,13,9,12,11~7,11,7,12,8,9,3,10,12,10,9,9,11,8,13,12,11,10,11,13,11,10,6,9,10,5,13,8,7,8,13,11,9,11,4,7,12,11,7,8,12,13,9,5,11,8,9,8,12,13,8,3,13,8,7,10,8,6,4,8,6,11,13,9,5,8,5,11,6,10,4,10,13,2,9,5,11,13,11,10,11,10,6,12,12,11,12,13,7,10,6,11,13,11,13,11,10,12,9,6,7,11,5,9,12,9,10,12,11,8,12,8,10,7,8,12,13,11,2,11,6,9,12,13,10,11,12,7,4,5,10,13,12,13,5,13,13,9,4,11,12,10,9,10,12,4,12,13,12,13,12,13,13,7,3,12,9,9,7,6,10,3,13,8,6,9,10,3,12,6,8,9,9~11,12,12,6,9,5,8,13,2,10,13,6,13,9,13,12,10,6,10,7,13,8,9,12,10,13,12,11,13,12,4,8,4,9,3,9,11,11,12,7,10,11,13,12,9,7,13,10,8,10,12,11,2,7,5,13,10,8,6,7,13,8,10,8,7,10,11,12,12,9,9,3,11,3,6,5,11,9,13,13,10,10,12,9,4,8,11,13,11,13,9,9,10,11,6,11,11,12,8,12,13,11,12,8,10,8,11,8,6,12,9,13,7,11,5,12,11,7,9,7,9,5,7,13,4,3,6,13,10,12~10,9,10,12,9,11,13,10,13,6,3,5,13,8,13,6,10,12,13,4,7,11,10,13,6,8,13,6,11,8,10,4,7,12,13,11,12,11,13,3,12,7,12,12,13,12,12,5,13,9,8,9,9,3,10,6,9,11,13,7,12,12,13,8,5,10,11,9,13,10,12,10,7,11,9,12,12,12,7,13,10,12,9,5,6,7,8,13,8,12,5,11,4,11,6,13,4,11,12,11,10,9,13,13,9,10,6,11,8,9,11,12,13,12,12,13,9,6,12,7,8,12,11,5,11,3,10,11,7,9,7,5,4,8,2,11,6,3,11,13,2,9,9,10,11,10,9,9,8,10,10,7,13,11,12,8,8~13,10,11,4,9,8,11,10,12,9,10,11,13,11,10,9,4,11,10,6,11,13,5,10,12,7,13,5,11,11,13,8,7,9,3,8,11,9,12,13,9,7,6,11,6,9,6,10,5,13,12,13,3,13,12,7,10,4,11,13,13,9,3,12,13,3,11,13,11,11,10,9,11,9,13,10,10,9,8,7,13,12,12,8,9,10,12,5,12,7,6,10,3,12,10,6,12,9,3,7,13,13,11,11,8,11,12,10,4,7,6,12,9,8,11,13,13,12,8,13,2,7,10,9,10,6,5,9,7,11,7,9,12,12,8,6,10,9,11,9,2,12,5,7,5,11,8,7,13,13,12,10,8,13,8,9,8,12,13,9,11,10,13,12,6,11,6,13,8,9,10,8,13,11,12,12,5,8,10,12,4,8,12&reel_set9=13,6,7,9,12,4,8,6,7,12,9,6,11,12,11,6,4,12,13,10,13,12,7,13,5,9,11,8,3,2,10,12,13,5,7,4,13,9,5,3,10,8,6,13,10,11,4,10,3,11,12,8,9,5,11,10,13,5,10,7,11,8,6,5,13,8,11,6,12,8,9,7,9~9,7,11,10,11,2,13,11,6,9,6,8,7,5,11,13,9,4,10,6,5,3,4,8,11,8,5,12,13,5,9,10,5,9,13,11,10,9,6,13,8,7,2,9,3,7,13,3,12,12,8,12,3,5,6,12,13,9,7,10,9,10,13,12,6,8,12,8,12,8,13,7,4,6,8,6,11,10,3,5,2,7,13,11,5,11,5,9,11,13,11,6,9,12,9,11,12,8,11,12,8,5,12,13,7,8,5,12,13,7,4,12,3,10,11,12,8,10,9,7,9,8,7,10,2,4,7,4,11,9,10,4,5,13,10,12,12,13,6,12,13,10,6,10,12,13,6,10,12~5,9,3,7,10,6,7,9,4,7,12,5,13,12,7,9,13,12,13,8,5,7,2,10,8,6,8,11,4,3,9,13,6,11,10,8,11,6,10,12,11,5,11,10,9,11,13,4,9,2,13,5,11,13,12,4,6,10,13,8,11,12,8,10,9,11,12,7,10,12,2,3,5,8,3~12,13,8,11,13,9,11,5,12,13,10,3,13,10,11,3,6,10,13,6,7,9,8,12,9,7,8,2,3,5,7,13,5,11,5,9,6,4,5,7,8,9,13,11,6,12,7,9,12,4,5,13,3,8,10,9,10,7,9,8,12,10,8,12,11,3,13,6,10,11,9,6,12,13,5,13,7,10,12,11,8,11,8,11,12,4,13,7,8,13,9,12,3,4,13,11,12,5,7,12,9,6,11,10,12,13,12,11,4,10,11,10,13,3,6,9,5,13,8,6,10,11,12,13,12,2,8,7,6,10,12,4,7,4,5,9,8,11~4,13,7,6,5,10,9,13,6,12,8,13,12,4,6,12,11,7,8,13,6,8,13,9,12,4,13,9,13,8,9,11,3,11,10,12,7,11,7,10,11,5,6,8,7,10,7,4,12,3,8,9,11,9,10,6,10,9,6,11,5,12,3,10,8,13,12,10,13,5,4,2,12,11,7,8,13,5,12,6,11,3,7,6,5,3,10,13,5,13,8,9,13,9,2,12,10,12,11,12&total_bet_min=100.00';
                
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
                $slotEvent['slotBet'] = $slotEvent['c'];
                $pur = -1;
                if(isset($slotEvent['pur'])){
                    $pur = $slotEvent['pur'];
                }
                $bl = 0;// $slotEvent['bl'];
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
                $purMuls = [100];
                if($pur >= 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $allBet * $purMuls[$pur];
                }else if($bl > 0){
                    $allBet = $betline * 25;
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
                    $roundstr = sprintf('%.4f', microtime(TRUE));
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
                if($str_rs_p == ''){
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                }else{
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', 0);
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
                    $strOtherResponse = $strOtherResponse . '&rs_iw=' . (str_replace(',', '', $str_rs_iw) / $original_bet * $betline);
                }
                if($str_rs_win != ''){
                    $strOtherResponse = $strOtherResponse . '&rs_win=' . (str_replace(',', '', $str_rs_win) / $original_bet * $betline);
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
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance . '&reel_set='. $currentReelSet.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=5&st=rect&c='.$betline.'&sw=5&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=40&w='.$totalWin.'&s=' . $strLastReel;
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
                    $allBet = $betline * 25;
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

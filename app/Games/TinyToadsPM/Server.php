<?php 
namespace VanguardLTE\Games\TinyToadsPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 25);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [3,9,7,4,12,6,10,6,5,8,4,11,11,7,11,3,10,12,3,12]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FscWinTotal', []);
                $slotSettings->SetGameData($slotSettings->slotId . 'FscTotal', []);
                $slotSettings->SetGameData($slotSettings->slotId . 'IsBonusBank', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'ACCV', '0~0;0~0;0~0');
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'IsBonusBank', $lastEvent->serverResponse->IsBonusBank);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ACCV', $lastEvent->serverResponse->ACCV);
                    if(isset($lastEvent->serverResponse->FscWinTotal)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FscWinTotal', explode(',', $lastEvent->serverResponse->FscWinTotal));
                    }
                    if(isset($lastEvent->serverResponse->FscTotal)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FscTotal', explode(',', $lastEvent->serverResponse->FscTotal));
                    }
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
                    $bet = '40.00';
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
                    $str_lmi = $stack['lmi'];
                    $str_lmv = $stack['lmv'];
                    $str_accv = $stack['accv'];
                    $str_acci = $stack['acci'];
                    $str_accm = $stack['accm'];
                    $str_slm_mv = $stack['slm_mv'];
                    $str_slm_mp = $stack['slm_mp'];
                    $apv = $stack['apv'];
                    $str_trail = $stack['trail'];
                    $strWinLine = $stack['win_line'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $arr_g = null;
                    if($stack['g'] != ''){
                        $arr_g = $stack['g'];
                    }
                    $str_mo_wpos = $stack['mo_wpos'];
                    $mo_tv = $stack['mo_tv'];
                    $str_fsdss = $stack['fsdss'];
                    $str_fsc_sw = $stack['fsc_sw'];
                    $str_fsc_left = $stack['fsc_left'];
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
                        $arr_rs_iw = explode($str_rs_iw, ',');
                        for($k = 0; $k < count($arr_rs_iw); $k++){
                            if($arr_rs_iw[$k] != null && $arr_rs_iw[$k] != '' && $arr_rs_iw[$k] > 0){
                                $arr_rs_iw[$k] = '' . (str_replace(',', '', $arr_rs_iw[$k]) / $original_bet * $bet);
                            }
                        }
                        $strOtherResponse = $strOtherResponse . '&rs_iw=' . implode(',', $arr_rs_iw);
                    }
                    if($str_rs_win != ''){
                        $arr_rs_win = explode($str_rs_win, ',');
                        for($k = 0; $k < count($arr_rs_win); $k++){
                            if($arr_rs_win[$k] != null && $arr_rs_win[$k] != '' && $arr_rs_win[$k] > 0){
                                $arr_rs_win[$k] = '' . (str_replace(',', '', $arr_rs_win[$k]) / $original_bet * $bet);
                            }
                        }
                        $strOtherResponse = $strOtherResponse . '&rs_win=' . implode(',', $arr_rs_win);
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
                    if($str_lmi != ''){
                        $strOtherResponse = $strOtherResponse . '&lmi=' . $str_lmi . '&lmv=' . $str_lmv;
                    }
                    if($str_slm_mv != ''){
                        $strOtherResponse = $strOtherResponse . '&slm_mv=' . $str_slm_mv . '&slm_mp=' . $str_slm_mp;
                    }
                    if($str_accv != ''){                        
                        $arr_oldaccv = explode(';', $slotSettings->GetGameData($slotSettings->slotId . 'ACCV')); //'0~0;0~0;0~0'
                        $arr_accv = explode(';', $str_accv);
                        for($i = 0; $i < 3; $i++){
                            $arr_accv[$i] = $arr_oldaccv[$i];
                        }
                        $strOtherResponse = $strOtherResponse . '&accm=' . $str_accm . '&acci=' . $str_acci . '&accv=' . implode(';', $arr_accv);
                    }
                    if($str_fsdss != ''){
                        $strOtherResponse = $strOtherResponse . '&fsdss=' . $str_fsdss;
                    }
                    if($str_fsc_sw != ''){
                        $strOtherResponse = $strOtherResponse . '&fsc_sw=' . $str_fsc_sw;
                    }
                    if($str_fsc_left != ''){
                        $strOtherResponse = $strOtherResponse . '&fsc_left=' . $str_fsc_left;
                    }
                    if($str_mo != ''){
                        $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                    }
                    if($str_mo_wpos != ''){
                        $strOtherResponse = $strOtherResponse . '&mo_wpos=' . $str_mo_wpos;
                    }
                    if($mo_tv > 0){
                        $strOtherResponse = $strOtherResponse . '&mo_tv=' . $mo_tv . '&mo_tw=' . ($mo_tv * $bet) . '&mo_c=1';
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
                    if($arr_g != null){
                        if(isset($arr_g['gs']))
                        {
                            for($i = 0; $i < 1000; $i++){
                                if(isset($arr_g['gs']['l' . $i])){
                                    $arr_sub_lines = explode('~', $arr_g['gs']['l' . $i]);
                                    $arr_sub_lines[1] = str_replace(',', '', $arr_sub_lines[1]) / $original_bet * $bet;
                                    $arr_g['gs']['l' . $i] = implode('~', $arr_sub_lines);
                                }else{
                                    break;
                                }
                            }
                            if(!isset($arr_g['gs']['def_s'])){
                                $arr_g['gs']['def_s'] = '3,9,7,4,12,3,6,10,6,5,8,4,4,11,11,7,11,10,3,10,12,4,12,9,4,3,5,7,3,8';
                                $arr_g['gs']['def_sa'] = '5,11,5,5,12';
                                $arr_g['gs']['def_sb'] = '4,9,3,4,12';
                            }
                        }
                        $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($arr_g));
                    }else{
                        $strOtherResponse = $strOtherResponse . '&g={gs:{def_s:"3,9,7,4,12,3,6,10,6,5,8,4,4,11,11,7,11,10,3,10,12,4,12,9,4,3,5,7,3,8",def_sa:"5,11,5,5,12",def_sb:"4,9,3,4,12",s:"3,9,7,4,12,3,6,10,6,5,8,4,4,11,11,7,11,10,3,10,12,4,12,9,4,3,5,7,3,8",sa:"5,11,5,5,12",sb:"4,9,3,4,12",sh:"6",st:"rect",sw:"5"}}';
                    }
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 || $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0)
                    {
                        if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0) 
                        {
                            $fscWinTotal = $slotSettings->GetGameData($slotSettings->slotId . 'FscWinTotal');
                            $fscTotal = $slotSettings->GetGameData($slotSettings->slotId . 'FscTotal');
                            $fscMuls = [];
                            for($k = 0; $k < count($fscWinTotal); $k++){
                                $fscMuls[] = 1;
                            }
                            $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 1).'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsend_total=1&fsmul_total=1&fsc_win_total=' . implode(',', $fscWinTotal) . '&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsc_res_total=' . implode(',', $fscWinTotal).'&fsc_mul_total='. implode(',', $fscMuls) .'&fsc_total=' . implode(',', $fscTotal);
                        }
                        else
                        {
                            $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&w=0.00';
                        }
                        if($fsmore > 0){
                            $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                        }     
                    }
                }else{
                    $strOtherResponse = $strOtherResponse . '&g={gs:{def_s:"3,9,7,4,12,3,6,10,6,5,8,4,4,11,11,7,11,10,3,10,12,4,12,9,4,3,5,7,3,8",def_sa:"5,11,5,5,12",def_sb:"4,9,3,4,12",s:"3,9,7,4,12,3,6,10,6,5,8,4,4,11,11,7,11,10,3,10,12,4,12,9,4,3,5,7,3,8",sa:"5,11,5,5,12",sb:"4,9,3,4,12",sh:"6",st:"rect",sw:"5"}}';
                }
                
                $Balance = $slotSettings->GetBalance();  
                $response = 'def_s=3,9,7,4,12,6,10,6,5,8,4,11,11,7,11,3,10,12,3,12&balance='. $Balance .'&cfgs=13610&ver=3&mo_s=14&index=1&balance_cash='. $Balance .'&mo_v=25,250,1,2&def_sb=4,9,3,4,12&reel_set_size=14&def_sa=5,11,5,5,12&reel_set=0&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1;14~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1;15~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1;16~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1;17~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&rt=d&gameInfo={props:{max_rnd_sim:"1",cmp:"6",max_rnd_hr:"5230126",jp1:"250",max_rnd_win:"4000",jp3:"8750",jp2:"750",jp4:"87500"}}&wl_i=tbm~4000&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime='. floor(microtime(true) * 1000) .'&sa=5,11,5,5,12&sb=4,9,3,4,12&reel_set10=5,11,9,3,11,7,9,3,5,10,5,11,6,14,11,12,4,12,15,4,10,7,4,11,3,6,12,7,6,9,8,3,10,6,8,3,3,8,4,16,5,7,9,12,9,8,10,6,11,3,5,9,7,4,7,6,9,10,8,10,7,3,9,12,10,8,3,9,8~2,11,5,8,16,4,16,12,9,7,8,12,16,9,7,2,3,16,12,14,6,3,4,11,15,2,2,5,11,6,15,5,14,2,16,7,6,5,11,16,12,10,4,2,2,2,9,11,2,2,15,10,12,8,6,9,3,10,16,2,15,9,6,15,15,10~8,7,9,5,9,7,10,12,4,5,15,11,6,8,5,7,4,12,2,4,7,10,7,3,16,2,11,16,6,12,3,11,12,11,2,12,5,4,9,12,12,9,11,16,5,6,8,2,2,2,2,4,3,4,11,6,2,8,10,5,6,8,4,3,7,6,12,3,3,2,10,11,5~11,7,9,6,10,8,5,6,8,2,16,8,7,4,12,10,11,9,12,8,9,2,11,10,11,12,7,8,4,3,2,2,10,5,2,5,2,5,5,9,10,2,2,2,2,8,7,4,3,6,16,9,4,12,5,15,6,10,4,3,12,10,11,6,7,10,6~2,5,4,12,5,12,15,3,7,11,2,2,14,7,9,6,4,9,12,4,4,3,11,10,9,12,3,11,5,2,8,12,3,8,7,10,2,8,3,9,4,6,9,11,5,2,11,6,12,6,2,10,8,16,6,2,3,4,6,2,2,2,2,4,12,3,9,15,2,11,8,10,5,3,10,2,10,11,6,2,2,8,10,9,7,8,11,8,4,11,12,7,10,7&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=40.00&reel_set11=4,3,8,12,4,8,3,6,5,7,8,3,8,12,7,11,5,12,6,12,8,14,4,11,14,12,7,6,4,9,9,3,7,11,7,9,10,9,11,5,3,10,11,9,10,9,10,16,9,3~4,12,2,5,7,12,2,11,5,4,11,7,9,9,3,5,9,10,3,5,2,9,2,8,10,4,2,12,5,12,11,2,10,6,2,2,6,3,2,12,8,12,9,7,2,5,12,11,2,2,2,2,5,10,9,14,11,2,3,7,6,4,8,2,6,3,2,4,5,11,10,6,15,9,10,11,10,6,10~5,11,15,12,3,14,12,14,11,4,10,12,3,8,12,16,6,15,6,12,2,8,4,3,5,6,16,9,11,2,16,3,14,15,15,7,5,14,5,15,8,14,2,4,11,12,15,2,2,2,6,8,5,4,9,5,7,15,8,11,16,4,10,6,15,3,5,11,2,6,4,9,2,14,12,11,7,15~9,5,9,10,4,7,6,3,8,12,7,4,3,2,12,6,11,9,8,10,10,4,5,6,8,7,15,9,2,4,3,6,11,5,4,11,10,9,12,5,8,5,2,8,2,12,11,12,14,2,2,2,2,11,8,16,10,10,5,7,5,4,8,9,7,2,10,5,10,7,2,9,3,9,6,7,6,10,6,12,6,11,14~3,5,3,12,4,3,9,8,10,11,4,7,2,4,15,8,2,14,5,7,11,2,12,10,2,10,6,4,7,8,6,12,6,8,15,10,11,3,2,3,10,12,5,11,12,3,2,2,2,2,7,4,6,11,9,8,11,2,12,6,9,2,8,3,2,6,10,5,11,15,10,9,5,9&reel_set12=3,4,8,7,3,11,8,4,12,15,11,9,3,11,3,7,12,7,6,8,11,7,9,12,5,4,10,6,3,4,3,3,12,9,15,10,16,8,3,8,6,9,11,3,6,12,5,9,7,12,11,14,10,4,9,6,10,4,7,16,8,12,7,5,10,15,7,12,10,5,3,5,10,9,5,4,6,12,6,9,10,11,14,7,8,3,9,8,9~2,11,12,2,12,5,4,11,2,3,11,2,15,12,7,10,9,4,6,2,2,10,3,2,6,4,9,5,12,10,5,4,8,11,6,10,11,2,2,2,2,7,2,12,3,5,7,14,9,8,2,9,6,3,9,5,11,10,9,5~6,5,11,4,11,6,12,8,7,11,3,14,2,8,12,7,3,4,6,14,11,15,5,3,8,7,12,4,12,5,12,7,12,10,9,4,2,2,2,2,12,5,4,5,7,6,2,5,10,9,10,12,3,2,8,2,6,4,2,11,6~10,16,5,16,4,9,12,11,2,14,9,8,15,6,10,9,4,15,6,14,11,16,8,7,3,7,2,4,10,9,7,14,12,7,10,15,8,16,10,6,4,2,2,2,11,4,6,16,5,9,5,16,2,6,5,15,7,8,15,12,14,11,7,3~3,12,2,6,9,6,7,5,11,10,9,8,7,2,4,2,8,5,2,11,3,11,3,7,9,2,4,10,9,10,4,9,6,7,12,3,2,2,2,2,5,3,4,10,12,11,10,3,6,8,4,8,16,11,2,8,2,15,5,6,11,2,12&purInit_e=1,1&reel_set13=12,10,8,12,7,6,4,11,9,11,3,11,3,9,6,4,7,11,4,7,3,6,8,5,3,12,10,3,4,11,9,12,4,6,5,3,5,3,10,3,9,11,10,7,10,8,8,12,7,10,3,6,8,16,10,9,3,7,8,16,12,3,12,7,11,6,8,5,14,9,10,9,16,5,4,9,4,14,10,9,16,12,11,3,9,7,4,10,6~2,2,9,11,12,4,3,12,2,5,4,5,2,7,12,10,3,5,3,12,8,5,9,6,10,6,2,10,11,5,5,11,9,6,8,11,3,9,6,11,12,2,12,4,14,2,2,7,8,15,9,2,7,2,2,2,2,3,10,9,4,7,10,2,6,2,11,9,3,4,10,9,6,12,8,2,2,10,14,6,10,16,12,2,2,5,11,12,9~4,2,3,8,12,6,16,9,8,7,6,10,2,4,3,4,8,5,11,7,11,12,5,4,6,10,12,2,5,11,5,11,2,7,2,2,2,2,12,7,8,6,3,9,5,3,9,6,12,2,15,10,3,14,7,12~5,8,10,2,3,6,7,7,6,4,5,10,7,8,12,5,7,4,10,2,7,9,11,9,3,10,5,10,2,8,10,14,9,8,11,4,14,2,10,11,6,7,2,2,2,2,7,3,6,3,5,9,12,15,2,5,6,11,9,12,6,11,4,12,5,12,2,8,10,9~8,16,12,15,8,16,7,15,11,4,11,9,11,6,14,3,16,4,15,11,2,5,8,16,7,9,12,9,2,6,5,3,15,4,2,5,12,3,11,16,9,11,2,2,2,6,4,14,2,14,2,15,10,2,4,10,2,6,10,16,5,14,3,9,8,12&sh=4&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&st=rect&c='.$bet.'&sw=5&sver=5&counter=2&ntp=0.00&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;50,10,5,0,0;25,8,3,0,0;25,8,3,0,0;25,8,3,0,0;25,8,3,0,0;15,5,2,0,0;15,5,2,0,0;15,5,2,0,0;15,5,2,0,0;15,5,2,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0&l=25&total_bet_max=40,000,000.00&reel_set0=8,6,10,9,5,11,7,9,12,11,9,8,12,11,3,7,9,5,4,10,4,6,7,8,10,8,9,7,12,8,3,6,5,10,12,10,4,7,11,7,10,6,12,11,5,10,4,9,7,3,10,16,3,6,4,11,5,8,3,9,4,10,9,12,7,6,3,4,3,12,10,9,3,11,7,3,11,8,3~10,6,12,4,6,11,10,5,7,11,5,11,12,2,10,12,4,9,9,10,2,2,12,4,8,5,15,8,10,11,2,5,2,11,10,9,3,9,2,2,2,2,7,12,6,5,9,5,3,2,6,2,4,2,11,2,2,6,3,7,12,3,9~7,10,5,14,3,7,8,5,9,12,8,11,4,12,3,6,11,3,2,7,6,12,11,9,7,5,11,8,6,4,4,9,5,10,3,2,2,2,2,3,12,6,12,4,12,7,4,2,10,5,8,12,10,2,6,14,2,6,5~9,5,7,2,5,14,12,10,9,10,7,6,5,6,7,11,8,12,8,4,10,8,9,10,4,6,12,6,5,11,8,10,12,4,7,2,16,2,8,2,2,2,2,5,11,9,6,5,9,9,2,3,6,7,2,7,3,4,9,3,10,8,15,10,2,3,10~7,4,8,12,11,5,9,3,4,2,7,4,10,9,6,4,4,3,14,12,15,7,11,4,2,2,5,11,3,10,5,9,2,3,12,8,12,2,6,10,12,6,3,2,9,3,7,12,8,2,8,11,2,2,2,2,10,12,9,6,8,10,3,9,6,3,6,9,5,10,2,11,2,4,11,2,8,4,11,5,10,8,12,7&s='.$lastReelStr.'&accInit=[{id:0,mask:"pfd;pfp"},{id:1,mask:"gfd;gfp"},{id:2,mask:"rfd;rfp"},{id:3,mask:"pdp;ppp"},{id:4,mask:"gdp;gpp"},{id:5,mask:"rdp;rpp"},{id:6,mask:"g_a;mj_a;mn_a;mi_a;g;mj;mn;mi"}]&reel_set2=14,9,3,8,7,10,11,4,9,3,12,8,12,9,6,10,3,4,7,12,5,3,8,3,12,7,3,7,4,6,5,11,9,4,10,10,8,7,10,8,9,5,11,4,6,11~5,6,2,2,11,6,11,2,8,12,6,4,9,12,4,15,12,5,3,5,11,8,4,2,3,5,8,11,9,2,2,6,10,2,11,9,16,5,2,11,10,2,6,5,2,8,3,12,6,10,12,4,3,2,8,7,2,2,2,2,2,2,10,2,2,5,10,2,8,10,2,12,9,4,2,10,7,11,4,2,10,9,11,2,4,12,7,8,4,8~6,5,2,8,3,5,3,2,5,2,11,4,9,2,2,7,12,8,10,2,4,3,10,11,6,7,2,8,12,3,6,2,8,11,9,12,11,4,3,7,9,11,16,3,2,2,2,2,2,2,3,2,11,4,2,2,11,12,12,7,2,7,12,2,8,6,7,4,6,12,6,5,4,2,11,8~10,11,10,9,4,10,4,9,3,9,5,11,5,8,3,4,10,6,11,6,11,10,7,4,6,2,2,3,12,3,4,3,6,3,10,15,5,6,12,8,2,14,9,8,5,7,8,10,9,8,7,2,2,2,2,2,2,4,2,12,10,7,12,7,4,3,7,8,5,10,9,3,5,12,3,6,11,6,6,2,12,9~9,5,2,3,11,2,9,10,5,12,10,2,6,9,11,11,12,2,4,2,4,9,12,2,12,5,2,2,6,8,3,11,2,6,2,10,16,2,11,9,2,8,15,10,4,9,2,2,2,2,2,2,3,8,2,3,2,4,2,11,3,5,8,12,9,6,2,7,2,5,16,2,7,3,11,4,10,2&reel_set1=11,3,9,4,11,4,8,5,8,9,11,9,12,10,9,4,6,7,10,12,7,3,6,3,10,3,6,8,9,5,15,10,3,9,6,5,9,8,12,5,3,10,4,7,14,12,11,7,9,8,12,7,8,15,6,7,4,8,4,11,7,3,11,4,10,12~11,8,6,8,2,2,2,11,6,7,5,3,6,12,6,9,12,9,2,2,10,8,12,2,12,7,10,12,3,2,9,8,2,5,11,2,3,11,7,2,11,5,4,2,2,2,2,11,6,12,2,5,2,15,8,5,9,10,5,4,10,4,2,4,3,2,10,6,14,12,10~2,12,2,2,14,16,2,15,2,2,4,5,4,11,6,4,2,6,2,5,2,11,3,11,2,8,5,7,12,2,2,8,10,8,11,7,3,2,9,2,4,7,3,11,14,2,2,2,2,11,12,10,5,2,9,12,10,9,6,12,6,12,11,7,4,7,5,9,2,5,3,12,11,3,10,8,12,11~8,5,7,11,7,9,11,2,9,8,12,6,7,10,2,6,10,2,12,15,6,10,6,8,6,9,14,5,7,9,2,8,4,11,4,4,3,5,10,5,9,10,9,6,11,3,6,2,2,2,2,5,12,3,12,2,6,9,2,10,9,12,2,7,10,5,4,2,4,7,16,9,10,3,8,3,2,10,15,2,7,10~11,4,12,4,3,8,12,6,10,12,3,2,9,12,3,11,8,2,2,8,12,6,9,2,4,7,10,4,7,11,2,6,14,6,8,3,12,5,2,4,9,6,3,11,7,2,10,4,8,9,6,3,2,2,2,2,4,7,3,2,14,9,3,8,11,5,16,2,5,2,10,6,10,15,11,8,2,11,16,5,2,9&reel_set4=7,3,11,3,8,3,10,4,6,8,4,6,7,8,7,5,4,6,9,12,8,5,4,12,3,5,8,10,12,10,8,4,9,12,5,6,10,11,7,10,7,9,12,10,3,4,3,9,11,9,11,9,10,3,6,8,12,4,6,9,7,3,14,11,11,7,5,9~4,4,11,10,11,5,12,9,12,10,7,6,10,11,2,3,10,12,6,8,12,3,8,2,15,3,11,2,2,2,8,7,10,3,5,11,2,2,2,11,9,12,4,2,4,16,2,2,2,2,2,2,9,6,4,9,3,2,8,4,8,5,2,3,2,12,10,5,5,6,2,10,2,5,2,12,8~14,6,7,8,12,11,12,9,6,7,3,9,4,12,2,3,7,3,4,12,10,12,7,5,4,5,8,11,2,11,2,11,11,8,2,2,2,2,2,2,9,7,3,6,9,2,8,11,5,11,12,10,2,6,2,2,4,10,2,7,5,11~12,16,5,7,8,7,6,4,6,8,6,4,9,7,10,3,6,8,10,4,4,7,2,10,11,3,12,8,12,8,16,7,8,4,10,6,10,6,4,7,11,9,3,4,9,11,2,9,10,4,12,9,2,7,11,10,2,2,2,2,2,2,10,3,2,2,10,9,5,3,5,6,11,2,9,3,6,11,4,8,6,8,5,3,4,10,5,16,12,11~7,3,8,7,6,4,7,3,8,2,10,8,9,10,5,3,5,11,9,12,2,3,8,4,10,4,10,2,10,12,3,15,12,4,12,2,2,5,11,2,7,6,11,2,8,5,2,2,2,2,2,2,2,9,3,9,11,4,5,6,11,2,7,9,6,11,4,16,9,12,11,12,16&purInit=[{bet:2500,type:"default"},{bet:10000,type:"default"}]&reel_set3=11,4,3,12,7,3,5,8,4,17,5,17,11,6,3,8,12,10,3,9,3,9,4,11,10,9,4,9,12,10,6,7,4,11,8,17,3,12,3,6,9,7,10,6,12,7,11,10,8,10,9,11,3,7,11,7,17,10,7,12,9,10,5,17,9,11,6,12,5,6,3,4,10,9,7,3,12,8,8,8~2,5,9,11,4,12,2,10,7,11,6,17,6,10,3,10,6,7,9,5,2,11,8,2,9,11,2,2,7,2,12,17,8,17,12,8,6,2,2,2,2,5,2,9,4,5,8,12,2,4,17,10,2,12,3,9~12,6,5,12,2,17,11,4,3,2,8,6,10,8,9,11,4,5,4,5,17,4,12,5,8,2,4,3,11,7,10,17,17,6,2,2,2,2,3,9,2,5,2,12,10,2,17,7,11,7,2,2,2,11,12~11,17,8,10,4,9,2,5,2,2,7,9,6,7,11,2,11,3,10,2,9,3,9,2,4,17,4,5,6,7,2,10,12,6,8,8,10,3,17,2,2,2,2,5,9,7,4,17,5,6,17,7,2,6,11,12,2,8,2,10,12,3,7~4,17,3,4,10,2,6,3,2,8,2,4,7,17,12,5,7,2,2,3,9,12,9,3,5,2,8,2,11,4,6,2,12,4,2,4,6,2,9,3,11,17,6,17,6,9,4,5,17,8,17,10,12,11,2,11,12,3,10,11,2,2,2,2,12,2,12,9,8,17,12,2,17,2,5,9,7,10,2,12,17,2,9,4,3,10,8,17,2,8,10,6,2,2,3&reel_set6=12,9,4,3,12,6,5,10,4,9,12,8,7,9,10,3,11,17,7,10,7,3,17,4,11,6,4,8,9,12,3,8,17,5,4,7,3,4,5,10,4,6,10,11,5,8,6,10,3,7,3,12,6,5,3,7,8,4,11,3,6,7,7,12,5,17,11,10,9,4,9,8,10,6~5,4,10,6,10,4,5,7,3,11,12,2,8,2,17,5,3,4,12,2,9,4,2,17,5,2,3,8,8,3,9,2,2,6,2,2,2,2,2,2,10,4,7,8,11,10,6,12,3,12,11,2,8,9,17,2~9,2,17,7,5,8,4,7,12,11,2,6,2,7,10,6,5,7,2,5,8,6,2,9,17,2,7,2,2,8,9,11,10,8,17,17,9,11,2,4,11,3,2,11,4,8,12,2,12,8,2,2,12,6,12,3,5,12,2,2,2,2,2,2,10,4,7,5,12,3,7,4,12,3,2,3,5,6,17,11,4,12,2,4,11,7,3,2,4,17,8,6,2~2,17,5,9,7,11,6,3,7,11,10,6,2,12,17,5,17,7,10,8,6,9,8,7,6,11,12,2,2,17,2,8,4,8,3,7,2,10,9,5,6,2,10,12,2,2,2,2,2,2,10,8,3,10,3,7,4,17,11,9,17,11,4,2,3,3,6,11,7,3,2,4,5,3,6~10,12,3,12,8,9,3,17,3,4,3,5,9,10,10,7,5,8,2,7,6,12,9,5,17,3,17,4,2,2,11,3,17,5,8,2,9,9,7,2,12,11,5,3,7,17,10,2,2,2,2,2,2,12,9,6,12,17,9,12,4,11,8,4,4,10,4,5,17,2,17,11,2,8,5,11,6,11,2,9,4&reel_set5=11,8,12,11,12,5,7,9,11,10,5,7,6,12,7,3,8,9,7,8,6,7,3,5,10,6,4,17,10,4,11,7,9,17,9,11,3,12,4,9,8,9,8,3,4,10,6,3,11,3,17,3,17,9,5,9,12,7,10,4,6,10,9,6~2,8,4,7,9,3,2,12,2,12,8,2,3,17,17,2,3,2,8,6,5,9,6,17,4,5,9,11,10,9,2,11,10,5,17,10,12,2,2,5,6,11,8,10,2,2,2,2,7,2,10,7,10,4,12,8,17,6,9,11,17,12,2,5,11,8,2,2,12,6~12,8,6,3,8,4,2,10,4,11,17,12,2,6,12,5,10,3,12,8,17,9,12,2,6,2,10,3,9,8,4,7,4,7,17,11,3,8,10,7,9,2,5,11,17,12,7,9,11,5,7,11,5,11,3,6,3,9,2,2,2,2,3,17,10,8,2,8,2,4,11,5,12,4,12,2,11,17,4,12,5,11,7,4,2,7,12,5,12,7,6,2,2,5,5~17,8,6,7,6,7,6,2,5,9,3,9,8,7,9,4,2,2,17,11,9,10,7,5,6,2,3,17,11,9,2,7,4,5,12,10,6,7,17,9,4,17,10,4,5,3,7,10,8,2,2,2,2,4,8,10,9,2,12,11,2,4,10,2,8,10,12,5,17,12,17,11,7,5,9,6,11,3,6,2,11~7,5,3,12,4,6,5,2,3,17,4,8,12,2,2,12,2,17,8,6,6,12,10,9,2,3,4,7,11,8,9,17,7,4,7,8,3,17,9,10,11,12,2,2,2,2,4,17,8,2,10,17,3,5,2,2,11,17,2,2,11,9,7,3,9&reel_set8=10,6,15,10,15,4,10,14,6,10,15,8,10,12,6,8,12,4,12,4,12,6,16,8,4,6,15~11,3,5,11,7,3,7,7,3,9,3,7,9,11,5,7,3,7,7,5,3,9,3,11,9,5,11,11,9,5,11,3,7,11,11,3,9~16,7,8,4,16,3,11,9,3,12,14,8,10,16,6,5,8,16,6,10,3,15,7,4,6~11,5,3,12,10,8,5,9,10,4,7,12,7,8,11,10,3,6,11,8,3,5,10,7,6,12,5,9,4,6,8,9,11,4,12,3~16,5,10,8,15,8,11,8,9,14,4,3,12,9,6,11,5,14,5,16,8,15,6,9,7,9,10,3,16,7,3,14,4,11,5,6&reel_set7=10,11,8,11,4,12,17,3,7,3,9,7,11,12,5,9,6,4,8,12,7,17,10,17,5,10,4,17,6,5,6,4,7,3,3,9,4,3,8,4,12,3,9,10,7,4,6,10,8,12,10,11,12,8,4,9,11,3,9,8,7,10,6,10,5,11,5,3,9,9,7,6~9,2,12,17,12,2,8,5,2,4,2,11,7,4,8,2,10,4,7,8,5,5,17,3,11,3,4,12,4,5,10,11,2,2,6,8,4,6,3,4,2,2,11,10,2,3,17,8,9,2,2,2,2,2,2,17,2,3,4,10,2,10,2,8,6,10,5,12,12,3,11,12,2,6,2,2,3,7,9,5,10~17,8,11,4,2,17,11,2,6,11,7,6,7,3,8,4,7,4,5,4,6,10,3,12,3,2,2,6,12,8,17,12,6,17,10,3,6,5,11,7,2,2,2,2,2,2,8,8,11,7,8,2,2,6,12,9,5,9,12,9,4,12,7,10,12,11,2~3,7,10,7,9,6,4,6,12,9,17,2,4,5,9,3,4,2,12,10,9,12,9,4,3,6,11,17,11,5,11,2,17,17,6,2,2,8,6,7,3,10,3,12,7,3,10,6,17,7,3,2,11,7,10,2,10,2,2,2,2,2,2,17,5,10,5,8,5,8,2,6,7,11,11,3,7,9,4,6,9,2,17,3,8,2,6,8,2~2,17,11,4,9,10,8,12,11,3,7,12,2,17,6,3,4,2,7,9,8,17,17,7,9,2,5,12,11,5,11,17,17,10,5,12,8,11,12,10,2,2,2,2,2,2,3,4,9,9,2,4,6,4,12,5,3,2,2,9,10,4,2,5,3,5&reel_set9=16,10,9,8,16,7,8,11,14,4,11,15,12,3,12,3,3,9,11,8,16,10,14,12,7,12,10,9,10,7,15,4,3,16,6,16,14,9,14,3,14,7,9,15,12,4,3,9,16,3,8,4,15,5,14,11,16,6,14,12,9,7,4,9,7,5,8,10,9,10,7,4,10,14,11~9,6,11,10,6,4,6,7,4,2,9,8,10,9,12,9,2,12,5,9,10,4,7,8,9,11,2,8,15,10,5,2,12,9,3,2,10,6,3,6,12,2,2,2,2,11,5,11,2,12,14,5,4,2,10,2,5,7,2,2,3,11,5~12,2,4,6,10,7,4,5,11,4,7,5,8,7,2,12,9,7,15,3,6,5,6,6,4,8,10,11,3,14,11,12,10,9,12,9,7,3,9,7,5,11,12,16,8,11,12,2,2,2,2,12,6,8,5,3,4,10,12,2,4,6,4,10,11,2,12,2,2,3,12,11,6,5,3,8~10,12,3,14,8,5,3,9,10,7,8,15,11,9,3,5,12,5,6,11,2,10,4,11,8,2,5,4,12,11,16,12,5,8,6,2,8,10,7,16,10,4,12,15,12,7,4,6,3,7,10,6,7,9,8,9,5,6,2,9,7,2,2,2,2,5,7,11,2,9,10,4,16,4,8,12,9,7,11,2,6,2,6,11,10,8,2,8,6,10,5,9,5,6,3~7,2,2,4,11,12,11,6,2,7,3,12,8,9,12,6,8,10,16,11,3,6,4,7,6,10,5,2,14,5,10,3,2,10,2,2,2,2,5,12,5,2,4,10,9,4,8,11,12,11,9,2,3,7,8,2&total_bet_min=200.00';
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
                $lines = 25;      
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
                $slotEvent['slotLines'] = 25;
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
                
                $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $betline * $lines, $lines);
                $winType = $_spinSettings[0];
                $_winAvaliableMoney = $_spinSettings[1];

                // $winType = 'bonus';

                $allBet = $betline * $lines;
                $purMuls = [100, 400];
                if($pur >= 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $allBet * $purMuls[$pur];
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FscWinTotal', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FscTotal', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.4f', microtime(TRUE));
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
                $str_lmi = '';
                $str_lmv = '';
                $str_accm = '';
                $str_acci = '';
                $str_accv = '';
                $str_slm_mv = '';
                $str_slm_mp = '';
                $str_fsdss = '';
                $str_fsc_sw = '';
                $str_fsc_left = '';
                $str_mo = '';
                $str_mo_t = '';
                $str_mo_wpos = '';
                $mo_tv = 0;
                $apv = 0;
                $wmv = 0;
                $arr_g = null;                
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
                    $str_lmi = $stack['lmi'];
                    $str_lmv = $stack['lmv'];
                    $str_accv = $stack['accv'];
                    $str_acci = $stack['acci'];
                    $str_accm = $stack['accm'];
                    $str_slm_mv = $stack['slm_mv'];
                    $str_slm_mp = $stack['slm_mp'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $str_mo_wpos = $stack['mo_wpos'];
                    $mo_tv = $stack['mo_tv'];
                    $str_fsdss = $stack['fsdss'];
                    $str_fsc_sw = $stack['fsc_sw'];
                    $str_fsc_left = $stack['fsc_left'];
                    if($stack['g'] != ''){
                        $arr_g = $stack['g'];
                    }
                    $apv = $stack['apv'];
                    $strWinLine = $stack['win_line'];
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
                    $str_lmi = $stack[0]['lmi'];
                    $str_lmv = $stack[0]['lmv'];
                    $str_accv = $stack[0]['accv'];
                    $str_acci = $stack[0]['acci'];
                    $str_accm = $stack[0]['accm'];
                    $str_slm_mv = $stack[0]['slm_mv'];
                    $str_slm_mp = $stack[0]['slm_mp'];
                    $str_mo = $stack[0]['mo'];
                    $str_mo_t = $stack[0]['mo_t'];
                    $str_mo_wpos = $stack[0]['mo_wpos'];
                    $mo_tv = $stack[0]['mo_tv'];
                    $str_fsdss = $stack[0]['fsdss'];
                    $str_fsc_sw = $stack[0]['fsc_sw'];
                    $str_fsc_left = $stack[0]['fsc_left'];
                    if($stack[0]['g'] != ''){
                        $arr_g = $stack[0]['g'];
                    }
                    $apv = $stack[0]['apv'];
                    $strWinLine = $stack[0]['win_line'];
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
                if($arr_g != null){
                    if(isset($arr_g['gs']))
                    {
                        for($i = 0; $i < 1000; $i++){
                            if(isset($arr_g['gs']['l' . $i])){
                                $arr_sub_lines = explode('~', $arr_g['gs']['l' . $i]);
                                $arr_sub_lines[1] = str_replace(',', '', $arr_sub_lines[1]) / $original_bet * $betline;
                                $totalWin = $totalWin + $arr_sub_lines[1];
                                $arr_g['gs']['l' . $i] = implode('~', $arr_sub_lines);
                            }else{
                                break;
                            }
                        }
                    }
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
                if($str_accv != ''){                    
                    $arr_oldaccv = explode(';', $slotSettings->GetGameData($slotSettings->slotId . 'ACCV')); //'0~0;0~0;0~0'
                    $arr_accv = explode(';', $str_accv);
                    for($i = 0; $i < 3; $i++){
                        $sub_arraccv = explode('~', $arr_accv[$i]);
                        $sub_arroldaccv = explode('~', $arr_oldaccv[$i]);
                        if($sub_arraccv[0] > 0)
                        {
                            if($sub_arraccv[1] == 6){
                            }
                            else if($sub_arroldaccv[1] >= 5){
                                $sub_arraccv[0] = 0;
                                $sub_arraccv[1] = $sub_arroldaccv[1];
                                $arr_accv[$i] = implode('~', $sub_arraccv);
                            }else{
                                if($sub_arroldaccv[1] + $sub_arraccv[0] > 5){
                                    $sub_arraccv[0] = 5 - $sub_arroldaccv[1];
                                    $sub_arraccv[1] = 5;
                                }
                                $sub_arraccv[1] = $sub_arroldaccv[1] + $sub_arraccv[0];
                                $arr_accv[$i] = implode('~', $sub_arraccv);
                            }
                        }
                        else if($sub_arraccv[0] < 0){
                            $sub_arraccv[1] = $sub_arroldaccv[1] + $sub_arraccv[0];
                            $arr_accv[$i] = implode('~', $sub_arraccv);
                        }else{
                            $sub_arraccv[1] = $sub_arroldaccv[1];
                            $arr_accv[$i] = implode('~', $sub_arraccv);
                        }
                        
                        $arr_accv[$i] = implode('~', $sub_arraccv);
                        $arr_oldaccv[$i] = implode('~', $sub_arraccv);
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'ACCV', implode(';', $arr_oldaccv));
                    $str_accv = implode(';', $arr_accv);
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
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 && $str_rs_p == '') 
                    {
                        $fscWinTotal = $slotSettings->GetGameData($slotSettings->slotId . 'FscWinTotal');
                        $fscTotal = $slotSettings->GetGameData($slotSettings->slotId . 'FscTotal');
                        array_push($fscWinTotal, $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin'));
                        array_push($fscTotal, $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames'));
                        $slotSettings->SetGameData($slotSettings->slotId . 'FscWinTotal', $fscWinTotal);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FscTotal', $fscTotal);
                        $fscMuls = [];
                        for($k = 0; $k < count($fscWinTotal); $k++){
                            $fscMuls[] = 1;
                        }
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsend_total=1&fsmul_total=1&fsc_win_total=' . implode(',', $fscWinTotal) . '&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsc_res_total=' . implode(',', $fscWinTotal).'&fsc_mul_total='. implode(',', $fscMuls) .'&fsc_total=' . implode(',', $fscTotal);
                        if($str_fsdss != ''){
                            $spinType = 's';
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $fsmax);
                            $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                            $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                            $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);

                            $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        }else{
                            $spinType = 'c';
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
                    $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
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
                }
                if($str_rs != ''){
                    $strOtherResponse = $strOtherResponse . '&rs=' . $str_rs;
                }
                if($str_rs_iw != ''){
                    $arr_rs_iw = explode($str_rs_iw, ',');
                    for($k = 0; $k < count($arr_rs_iw); $k++){
                        if($arr_rs_iw[$k] != null && $arr_rs_iw[$k] != '' && $arr_rs_iw[$k] > 0){
                            $arr_rs_iw[$k] = '' . (str_replace(',', '', $arr_rs_iw[$k]) / $original_bet * $betline);
                        }
                    }
                    $strOtherResponse = $strOtherResponse . '&rs_iw=' . implode(',', $arr_rs_iw);
                }
                if($str_rs_win != ''){
                    $arr_rs_win = explode($str_rs_win, ',');
                    for($k = 0; $k < count($arr_rs_win); $k++){
                        if($arr_rs_win[$k] != null && $arr_rs_win[$k] != '' && $arr_rs_win[$k] > 0){
                            $arr_rs_win[$k] = '' . (str_replace(',', '', $arr_rs_win[$k]) / $original_bet * $betline);
                        }
                    }
                    $strOtherResponse = $strOtherResponse . '&rs_win=' . implode(',', $arr_rs_win);
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
                if($str_lmi != ''){
                    $strOtherResponse = $strOtherResponse . '&lmi=' . $str_lmi . '&lmv=' . $str_lmv;
                }
                if($str_slm_mv != ''){
                    $strOtherResponse = $strOtherResponse . '&slm_mv=' . $str_slm_mv . '&slm_mp=' . $str_slm_mp;
                }
                if($str_accv != ''){
                    $strOtherResponse = $strOtherResponse . '&accm=' . $str_accm . '&acci=' . $str_acci . '&accv=' . $str_accv;
                }
                if($apv > 0){
                    $strOtherResponse = $strOtherResponse . '&apaw=' . $str_apaw . '&apv=' . $apv;
                }
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                }
                if($str_mo_wpos != ''){
                    $strOtherResponse = $strOtherResponse . '&mo_wpos=' . $str_mo_wpos;
                }
                if($mo_tv > 0){
                    $strOtherResponse = $strOtherResponse . '&mo_tv=' . $mo_tv . '&mo_tw=' . $mo_tw . '&mo_c=1';
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                }
                if($str_fsdss != ''){
                    $strOtherResponse = $strOtherResponse . '&fsdss=' . $str_fsdss;
                }
                if($str_fsc_sw != ''){
                    $strOtherResponse = $strOtherResponse . '&fsc_sw=' . $str_fsc_sw;
                }
                if($str_fsc_left != ''){
                    $strOtherResponse = $strOtherResponse . '&fsc_left=' . $str_fsc_left;
                }
                if($arr_g != null){                    
                    $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($arr_g));
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance . '&reel_set='. $currentReelSet.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=4&st=rect&c='.$betline.'&sw=5&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&w='.$totalWin.'&s=' . $strLastReel;
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"CurrentRespin":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin'). ',"IsBonusBank":' . $slotSettings->GetGameData($slotSettings->slotId . 'IsBonusBank') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RespinWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"ACCV":"' . $slotSettings->GetGameData($slotSettings->slotId . 'ACCV') . '","TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"FscWinTotal":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'FscWinTotal')) . '","FscTotal":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'FscTotal')) . '","TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks'))  . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if(($slotEvent['slotEvent'] == 'freespin' || ($str_rs_p == '' && $str_rs_t != '')) && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * $purMuls[$slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin')];
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

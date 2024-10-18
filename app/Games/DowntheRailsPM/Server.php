<?php 
namespace VanguardLTE\Games\DowntheRailsPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'Level', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [3,9,7,4,11,6,11,3,7,9,4,10,9,4,8]);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $strOtherResponse = '';
                $stack = null;
                
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $lastEvent->serverResponse->CurrentRespin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex', $lastEvent->serverResponse->FreeIndex);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Level', $lastEvent->serverResponse->Level);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', $lastEvent->serverResponse->Bgt);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', $lastEvent->serverResponse->BonusState);
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
                        $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                        if($slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') > 0){
                            $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') - 1];
                        }
                    }
                    $bet = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $bet = '50.00';
                }
                $currentReelSet = 0;
                $fsmul = 1;
                $spinType = 's';
                if(isset($stack)){    
                    if($stack['reel_set'] >= 0){
                        $currentReelSet = $stack['reel_set'];
                    }
                    $str_initReel = $stack['init_reel'];
                    $str_lmi = $stack['lmi'];
                    $str_lmv = $stack['lmv'];
                    $str_stf = $stack['stf'];
                    $str_slm_mp = $stack['slm_mp'];
                    $str_slm_mv = $stack['slm_mv'];
                    $g = null;
                    if($stack['g'] != ''){
                        $g = $stack['g'];
                    }
                    $bw = $stack['bw'];
                    $str_trail = $stack['trail'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $mo_tv = $stack['mo_tv'];
                    $str_mo_wpos = $stack['mo_wpos'];
                    $mo_m = $stack['mo_m'];
                    $apv = $stack['apv'];
                    $str_sts = $stack['sts'];
                    $str_sty = $stack['sty'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $rs_more = $stack['rs_more'];
                    $fsmax = $stack['fsmax'];
                    $str_accm = $stack['accm'];
                    $str_accv = $stack['accv'];
                    $pw = str_replace(',', '', $stack['pw']);
                    $strWinLine = $stack['win_line'];

                    $arr_freetypes = ['pv', 'kc', 'cw', 'bp', 'eol'];
                    $freeIndex = $slotSettings->GetGameData($slotSettings->slotId . 'FreeIndex');
                    $bgt = $slotSettings->GetGameData($slotSettings->slotId . 'Bgt');
                    $level = $slotSettings->GetGameData($slotSettings->slotId . 'Level');
                    if($bgt > 0){
                        $spinType ='b';
                    }
                    if($bw == 1){
                        $strOtherResponse = $strOtherResponse .'&bw=1';
                        if($freeIndex == 4){
                            $spinType = 's';
                        }
                    }
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($str_lmv != ''){
                        $strOtherResponse = $strOtherResponse . '&lmi=' . $str_lmi . '&lmv=' . $str_lmv;
                    }
                    if($str_stf != ''){
                        $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                    }
                    if($str_slm_mv != ''){
                        $strOtherResponse = $strOtherResponse . '&slm_mp=' . $str_slm_mp . '&slm_mv=' . $str_slm_mv;
                    }
                    if($g != null){
                        $end = 0;
                        if($level > 0){
                            $arr_ch_h = [];
                            for($k = 0; $k < $level; $k++){
                                $arr_ch_h[] = '0~0';
                            }
                            if(isset($g['gmb'])){
                                $g['gmb']['ch_h'] = implode(',', $arr_ch_h);
                            }
                            if($bgt == 0){
                                $end = 1;
                            }
                            $g['gmb']['end'] = '' . $end;
                        }
                        if($apv > 0 && isset($g['gmb'])){
                            $g['gmb']['rw'] = '' . ($apv * $bet);
                        }
                        if(isset($g['eol_g']) && isset($g['eol_g']['mo_tw']) && $rs_t > 0){
                            $eol_moneyWin = str_replace(',', '', $g['eol_g']['mo_tw']) / $original_bet * $bet;
                            $g['eol_g']['mo_tw'] = '' . $eol_moneyWin;
                        }
                        if(!isset($g['eol_g'])){
                            $g['eol_g'] = [];
                        }
                        $g['eol_g']['def_s'] = '14,14,14,14,14,14,14,14,14,14,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15';
                        $g['eol_g']['def_sa'] = '15,15,15,15,15';
                        $g['eol_g']['def_sb'] = '15,15,15,15,15';
                        $g['eol_g']['sh'] = '5';
                        $g['eol_g']['st'] = 'rect';
                        $g['eol_g']['sw'] = '5';
                        $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($g));
                    }else{
                        $strOtherResponse = $strOtherResponse . '&g={eol_g:{def_s:"14,14,14,14,14,14,14,14,14,14,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15",def_sa:"15,15,15,15,15",def_sb:"15,15,15,15,15",sh:"5",st:"rect",sw:"5"}}';
                    }
                    if($str_trail != ''){
                        if($freeIndex >= 0 && $freeIndex < 4){
                            $str_trail =  'fs_t~'. $arr_freetypes[$freeIndex] .';fs_gmb_p~' . $arr_freetypes[$freeIndex + 1];
                        }
                        $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                    }
                    if($str_mo != ''){
                        $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                    }
                    if($mo_tv > 0){
                        $strOtherResponse = $strOtherResponse . '&mo_tv='. $mo_tv .'&mo_wpos=' . $str_mo_wpos . '&mo_c=1&mo_tw=' . ($mo_tv * $bet);
                    }
                    if($mo_m > 0){
                        $strOtherResponse = $strOtherResponse . '&mo_m=' . $mo_m;
                    }
                    if($str_sts != ''){
                        $strOtherResponse = $strOtherResponse . '&sts=' . $str_sts;
                    }
                    if($str_sty  != ''){
                        $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                    }
                    if($rs_p >= 0){
                        $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p . '&rs_c=' . $rs_c . '&rs_m=' . $rs_m;
                        if($rs_more > 0){
                            $strOtherResponse = $strOtherResponse . '&rs_more=' . $rs_more;
                        }
                        if($rs_p > 0){
                            $strOtherResponse = $strOtherResponse . '&rs_win=0.00';
                        }
                    }
                    if($rs_t > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t . '&rs_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                    if($str_accv != ''){
                        $strOtherResponse = $strOtherResponse . '&accm=' . $str_accm . '&acci=0&accv=' . $str_accv;
                    }                
                    if($pw > 0){
                        $strOtherResponse = $strOtherResponse . '&pw=' . ($pw / $original_bet * $bet);
                    }
                    if($apv > 0){
                        $strOtherResponse = $strOtherResponse . '&apwa='. ($apv * $bet) .'&apt=ma&apv=' . $apv;
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
                            $strOtherResponse = $strOtherResponse . '&fs_total='.($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 1).'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                            if($rs_p >= 0){
                                $strOtherResponse = $strOtherResponse . '&fsend_total=0';
                            }else{
                                $strOtherResponse = $strOtherResponse . '&fsend_total=1';
                            }                        
                        }
                        else
                        {
                            $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $fs.'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        }
                    }
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }else{
                    $strOtherResponse = $strOtherResponse . '&g={eol_g:{def_s:"14,14,14,14,14,14,14,14,14,14,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15",def_sa:"15,15,15,15,15",def_sb:"15,15,15,15,15",sh:"5",st:"rect",sw:"5"}}';
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));

                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=3,9,7,4,11,6,11,3,7,9,4,10,9,4,8&balance='. $Balance .'&cfgs=6259&ver=3&mo_s=16;3;4;5;6;7&index=1&balance_cash='. $Balance .'&mo_v=1,2,3,4,5,10,15,20,25,30,35,40,45,50,100,200,300,500,1000,2500;10,20,50;5,10,30;3,6,15;2,4,10;1,2,5&def_sb=4,10,6,5,8&reel_set_size=9&def_sa=3,11,10,3,8&reel_set='.$currentReelSet.$strOtherResponse.'&balance_bonus=0.00&na='. $spinType .'&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&rt=d&gameInfo={props:{kc_cw_win_chance:"66.04",max_rnd_sim:"1",pn_kc_win_chance:"79.40",bp_eol_win_chance:"50.55",max_rnd_hr:"9141818",max_rnd_win:"5000",cw_bp_win_chance:"56.76"}}&wl_i=tbm~5000&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=3,11,10,3,8&sb=4,10,6,5,8&sc='. implode(',', $slotSettings->Bet) .'&defc=50.00&sh=3&wilds=2~500,100,40,0,0~1,1,1,1,1&bonuses=0&st=rect&c='.$bet.'&sw=5&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;500,100,40,0,0;250,80,20,0,0;250,80,20,0,0;200,60,10,0,0;200,60,10,0,0;100,40,8,0,0;80,20,6,0,0;60,10,4,0,0;50,10,4,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0&l=20&total_bet_max=10,000,000.00&reel_set0=7,5,8,6,3,6,10,6,11,8,9,10,10,3,4,11,8,3,3,3,3,9,7,10,11,7,6,9,7,6,5,3,11,3,9,8,4,5,6,6,6,4,5,1,11,5,2,10,4,2,1,9,10,3,7,5,11,8,9,10,4~11,11,9,11,5,11,9,3,10,5,6,10,6,7,11,4,2,7,3,2,6,4,6,4,10,9,5,5,9,11,10,3,3,3,3,7,6,4,3,10,3,4,10,3,5,8,5,4,11,10,9,11,10,5,6,6,10,9,8,11,8,9,7,1,5,1,6,7~7,11,10,4,7,7,2,7,5,8,2,10,11,10,4,8,9,7,9,7,11,2,10,3,1,3,5,10,7,7,1,4,9,6,5,3,8,9,11,9,3,8,10,7,4,5,10,9,8,10,6,7,3,2,3,8,1,3,7,9,3,4,3,4,10,8,9,5,7,5,3,3,3,3,7,4,4,1,11,9,6,3,11,6,3,9,10,4,4,7,9,5,11,1,9,8,11,5,3,6,5,11,7,10,3,10,8,3,5,10,2,10,4,3,11,4,3,8,5,5,6,11,6,5,11,4,3,6,8,7,9,8,6,2,5,3,4,2,7,4,2,3,10,9,3~8,4,9,4,3,10,5,1,8,6,3,3,4,4,11,6,3,11,6,8,4,4,8,5,5,2,9,6,3,7,8,10,6,8,2,6,9,8,10,3,3,3,3,2,9,10,9,11,3,3,2,10,7,5,9,4,3,4,4,3,2,7,8,3,9,7,6,7,4,7,3,6,11,1,3,3,11,3,8,8,10,5,6~5,2,9,4,5,6,6,4,9,6,6,11,8,7,7,4,7,4,11,9,8,9,11,6,10,4,5,3,9,6,11,9,2,7,9,11,6,6,2,5,9,8,11,10,1,11,8,3,3,3,3,8,5,5,11,4,9,10,11,3,8,2,10,8,9,6,3,4,11,6,1,10,6,10,8,11,10,3,3,5,10,5,7,3,10,1,10,4,8,9,3,6,2,10,11,7,6,10,1,4,8&s='.$lastReelStr.'&accInit=[{id:0,mask:"el_c;el_l;el_t;el_a;xp_c;xp_l;xp_t;b_c;b_l;b_t;m_c;m_l;m_t;m_v"}]&reel_set2=4,9,7,6,8,2,11,8,9,11,3,11,8,4,7,2,6,10,9,7,3,3,3,2,10,5,6,8,10,4,2,10,11,9,8,11,10,5,6,3,10,8,11,7,2~9,10,6,8,2,11,2,8,6,10,9,11,9,8,11,7,9,5,4,5,4,7,6,9,3,5,9,3,3,3,10,10,11,10,6,3,2,5,2,11,10,4,10,5,10,9,2,7,11,6,4,11,8,2,7,4,9,3~8,9,6,5,11,2,9,8,5,5,9,3,6,4,5,3,7,4,6,11,7,7,10,8,11,10,9,2,9,2,3,3,3,4,10,7,4,7,10,11,7,9,11,8,11,4,5,11,6,10,6,7,3,2,8,7,10,11,10,11,8,10~9,2,11,2,11,6,4,2,10,5,10,9,8,3,4,10,2,7,9,4,11,10,11,5,10,5,8,9,10,5,6,4,9,8,10,5,11,3,3,3,9,10,5,8,11,7,11,4,7,10,3,6,11,6,10,11,4,8,7,3,8,10,8,11,9,6,5,6,11,7,8,6,9,8,2,7,8,7,9~10,7,7,10,5,4,10,5,8,9,10,9,8,4,4,9,8,10,11,7,10,7,9,5,6,4,6,6,11,10,7,11,7,2,7,6,7,8,4,9,5,9,8,9,10,8,6,8,7,3,3,3,9,6,4,8,10,9,11,8,6,9,8,9,8,7,5,10,5,8,10,5,5,9,6,10,3,10,2,11,6,11,10,9,6,5,8,6,9,6,4,6,7,5,3,10,11,6,5,4,6,9,6,10,8,3,9&reel_set1=12,12,12,12,11,12,12,12,12,8,12,12,12,12,9,12,12,11,10,12,8,6,12,12,12,12,9,12,10,12,12,12,9,12,12,5,12,12,12,10,12,3,12,12,12,12,2,7,12,8,12,4,9~12,12,12,12,11,12,12,12,12,11,12,12,12,12,2,12,12,12,12,4,7,12,12,12,12,8,12,12,12,12,5,9,12,12,12,12,8,12,12,11,12,12,6,12,12,6,11,12,3,10,12,5,12~2,12,12,8,10,12,10,12,12,12,6,12,12,12,7,6,12,12,6,9,12,9,12,12,12,12,12,12,12,11,12,12,12,12,12,12,12,8,5,12,12,12,12,12,12,12,5,9,12~12,4,9,11,7,5,12,12,10,6,8,12,9,11,9,12,11,8,12,12,10,12,12,6,7,5,11,4,3,12,12,8,12,7,12,10,12,12,10,12,3,12,5,12,12,6,7,2,7,11,8,12,4,6,5,9~9,12,12,8,9,12,12,5,10,7,3,12,12,11,9,11,12,12,4,10,9,12,12,8,12,8,12,12,12,12,4,12,5,12,5,12,2,6,11,10,12,6,10,9,12,7,8,10,12,4,8,12,8,12,12,10,7,12,12&reel_set4=11,8,9,7,10,8,4,9,5,6,6,11,10,4,7,10,3,6,9,3,3,3,3,11,8,9,4,9,5,5,8,11,3,3,10,3,4,11,7,5,6,7,9,8,10~3,11,3,10,11,6,8,3,5,11,6,8,10,4,9,11,9,5,6,4,8,9,4,9,11,6,11,10,8,3,5,3,3,3,3,8,10,5,9,7,5,3,9,11,7,8,10,6,7,3,10,11,6,10,11,9,10,4,7,7,5,5,4,4,9,11,9,8,10,3,5,7,9~11,4,10,8,6,8,4,9,7,3,6,6,8,11,3,6,8,3,11,11,5,10,8,9,5,6,5,10,7,10,3,3,9,11,8,11,3,3,3,3,9,8,7,3,7,7,4,4,10,5,9,4,7,5,4,4,10,5,6,8,5,9,7,7,9,11,8,11,9,3,11,4,3,9,7,11,9,10,5,7~3,10,5,6,7,7,8,11,10,3,8,3,10,6,8,9,6,9,8,10,4,10,9,8,3,7,3,7,9,7,11,9,7,6,8,11,8,7,5,3,3,3,8,9,6,4,11,7,10,8,4,8,10,9,11,10,9,10,7,9,5,8,6,6,8,6,11,6,5,4,11,7,10,11,6,10,4,11,9,11,11,3,4~11,10,3,9,5,10,8,6,10,11,10,5,11,8,6,5,8,11,9,5,3,6,6,4,9,4,9,6,11,7,10,8,9,6,5,9,7,8,11,10,3,4,10,4,3,8,9,10,6,11,7,7,10,8,9,3,3,3,3,4,6,6,8,7,10,8,6,8,10,5,11,9,5,10,8,5,7,6,11,7,9,10,4,5,7,5,8,9,11,4,4,3,3,6,8,11,9,8,4,9,11,5,4,6,11,5,10,3,5,8,6,7,7,9&reel_set3=7,11,3,6,8,4,6,10,5,8,4,5,9,10,6,9,11,3,3,7,10,11,7,4,6,9,6,7,3,3,3,3,8,9,4,3,8,10,7,5,8,7,10,9,11,3,9,5,11,7,3,6,8,9,5,8,3,11,6,11,8,9,10,4~10,9,5,7,8,6,10,8,4,3,11,3,5,8,11,7,3,8,9,11,3,8,3,3,3,3,9,11,10,9,6,6,10,7,10,9,11,4,9,3,4,7,10,11,9,5,5,11,4,5,6~11,8,7,11,11,5,10,3,8,9,9,10,3,7,7,8,7,7,6,3,6,7,9,10,8,11,5,4,5,4,11,6,3,3,3,3,4,8,5,8,10,5,7,4,9,9,6,11,5,9,10,8,11,3,5,4,8,6,10,7,3,10,4,9,10,9,3,3,4,11~10,8,11,4,8,10,11,10,3,3,8,6,8,9,5,11,11,9,10,9,7,8,5,8,4,11,11,10,6,7,6,4,9,3,3,3,7,6,7,7,10,9,11,5,10,4,7,7,11,6,3,8,10,8,7,7,6,6,10,4,9,8,10,9,3,6,6,3,11~4,5,3,8,9,7,4,7,6,5,7,3,7,8,5,4,9,8,3,8,10,7,11,8,4,11,9,11,9,11,6,6,8,7,10,6,11,10,5,4,3,11,9,5,11,10,8,11,10,7,9,11,4,8,4,5,6,3,3,3,3,3,10,8,7,4,8,9,6,3,9,6,3,10,6,10,6,9,8,9,5,3,6,6,8,6,5,9,10,8,6,3,10,11,8,5,11,10,8,6,11,9,10,5,11,10,5,8,5,8,11,7,9,11,4,7,9,5,10,4&reel_set6=3,9,11,5,9,10,10,4,4,10,4,8,11,9,6,11,7,10,10,11,5,6,6,3,3,3,3,5,3,3,5,7,8,10,7,9,6,4,8,3,7,9,8,11,11,8,3,7,9,8,11,6~10,9,10,8,9,11,5,6,3,4,3,6,3,4,9,11,4,11,9,10,10,5,11,9,5,7,5,3,3,3,3,7,8,10,11,11,9,8,7,5,7,9,11,3,11,10,3,6,4,8,3,11,8,9,8,5,10,9,6,8~9,6,4,9,9,10,11,4,3,3,10,5,9,8,5,6,11,11,7,8,10,3,10,8,9,11,11,3,3,8,7,3,3,3,3,9,6,10,3,11,5,11,6,7,7,6,7,8,4,8,9,4,8,5,10,4,10,10,5,7,8,7,9,5,7,4,10,11~11,10,9,4,11,10,3,9,8,4,10,9,8,7,10,9,8,10,6,9,10,5,11,10,3,6,8,8,7,8,10,6,8,7,4,11,7,8,4,11,3,3,3,10,7,11,11,8,6,4,3,11,9,6,3,10,5,5,7,4,4,7,9,3,11,6,9,6,9,7,11,9,6,6,9,8,8,11,5,8,10,7,10,8,10~7,11,3,11,6,7,9,9,11,8,11,4,8,6,9,5,4,11,5,6,7,10,5,5,10,8,11,4,6,10,9,8,9,4,8,11,6,9,6,4,5,10,6,11,9,10,9,11,6,10,8,3,3,3,3,3,8,9,9,8,3,6,3,8,11,4,8,7,8,7,9,9,10,11,7,7,11,9,10,6,10,11,8,4,3,3,8,11,5,10,5,10,9,5,6,8,5,10,7,5,10,11,10,6,4,5,5,9,9,4&reel_set5=9,10,4,8,4,3,3,4,2,5,11,2,8,10,10,2,10,9,5,6,3,11,2,11,2,10,5,7,7,9,2,11,7,3,3,3,3,9,6,8,7,11,8,7,2,10,8,11,9,3,3,4,6,7,8,11,6,10,8,5,11,9,3,8,9,11,4,2,10,9,6,5~8,10,3,8,11,5,3,10,9,8,5,8,9,2,4,5,9,6,9,2,3,10,4,2,10,10,11,11,10,5,11,10,4,6,11,9,2,8,4,9,3,7,8,3,3,3,3,7,3,7,10,6,11,8,7,2,11,4,9,8,2,7,9,5,4,11,6,5,10,3,7,11,8,11,9,2,10,8,2,3,11,3,6,9,2,11,6,5,5~8,9,9,5,9,9,10,9,7,4,10,10,2,5,8,7,6,8,3,6,11,11,10,9,8,4,7,11,8,4,3,7,10,5,9,4,2,10,8,9,11,3,3,3,3,8,11,7,10,3,8,6,6,5,10,11,10,4,7,10,5,3,2,11,9,6,3,3,5,7,8,3,7,5,2,11,7,7,4,6,11,2,10,11,9,4,8,10~9,6,8,9,4,11,10,8,6,8,8,11,10,11,7,2,5,10,8,8,4,11,3,3,3,6,10,5,9,6,6,11,9,10,3,7,4,3,8,7,3,7,9,10,9,8,11,7,2,4~8,4,10,4,4,11,4,6,7,9,10,8,6,10,8,11,8,3,3,8,9,8,7,10,9,7,2,10,11,9,11,6,2,10,5,8,9,9,5,9,7,6,7,5,9,4,8,10,9,8,3,3,3,3,3,6,3,6,5,11,8,11,5,7,10,11,9,5,7,8,3,6,5,11,5,10,11,6,11,3,8,9,9,6,8,4,4,8,10,6,10,7,4,3,11,4,10,5,11,6,2,9,9,11,5,9&reel_set8=9,11,9,10,3,10,6,11,6,10,9,11,10,7,9,10,10,11,10,3,3,3,10,9,11,8,7,9,11,9,10,9,9,7,10,4,8,7,10,8,8,5,10,10,7,6,10,11,6,3,6,9,3,8,11,8,9,6,8,5,8,9,11,11,8~9,8,4,11,9,7,10,10,9,10,10,5,11,5,10,11,7,10,11,7,8,11,9,8,8,6,11,6,11,7,3,3,3,6,7,7,11,9,4,10,4,10,5,9,8,8,3,10,4,6,8,8,9,11,9,6,3,7,5,10,9,9,3,11,5,8~7,9,10,8,10,8,9,5,11,10,3,11,8,5,11,10,4,7,11,5,4,8,4,7,4,9,10,8,10,11,9,11,5,11,11,8,9,11,7,9,11,8,10,5,9,11,9,11,7,8,9,11,8,7,11,6,3,3,3,9,9,3,11,9,8,9,10,7,10,9,8,10,10,6,5,4,8,5,10,10,5,6,9,6,7,6,6,11,7,10,6,11,7,8,10,10,9,8,10,7,10,8,9,9,3,10,8,5,9,8,5,10,10,11,4,8~9,10,7,4,10,11,9,5,7,5,11,8,10,7,9,11,10,8,11,7,7,8,10,6,11,7,8,7,8,3,3,3,10,3,8,10,11,7,8,9,6,10,11,8,9,8,5,6,6,9,10,11,4,3,9,8,6,7,8,11,10,8,6~9,10,8,7,8,10,8,9,9,11,11,9,10,8,5,11,9,6,11,9,10,8,10,11,10,8,4,9,11,6,8,9,7,9,8,6,4,10,10,11,8,9,8,8,5,8,4,8,10,3,8,8,6,5,11,9,7,9,3,6,5,3,3,3,6,9,9,11,11,10,10,5,10,10,11,11,7,9,6,8,8,11,11,9,5,9,10,8,3,11,10,10,8,8,5,11,8,9,11,8,8,7,8,5,7,11,10,10,7,8,11,9,9,10,11,5,7,10,9,10,9,6,11,7,11,6,10,4&reel_set7=10,2,9,6,7,6,7,8,9,10,10,9,5,2,7,4,10,10,11,7,8,3,3,3,2,9,5,11,8,3,8,10,5,11,8,9,11,6,9,10,10,8,2,3,11,11,10,10,7,7,3,11,11,4,6,9,6,5,4,2,10,5,7,10,9,8,8,6,8,11,6~7,7,10,6,2,9,10,4,10,11,9,4,3,11,8,9,10,11,8,8,11,11,10,9,10,5,8,6,8,5,3,3,3,8,11,11,10,9,5,5,9,3,2,10,6,7,11,6,5,7,8,9,8,7,5,2,6,11,7,11,9,2,7,5,10,4~9,5,11,10,6,7,4,5,10,4,9,8,9,8,6,5,6,11,8,10,7,4,8,11,11,6,10,3,8,7,7,8,5,6,4,11,10,5,9,11,6,9,8,10,5,7,9,6,9,10,7,7,11,7,7,4,11,7,3,5,9,5,4,9,11,3,3,3,11,10,7,6,7,11,5,10,9,5,5,11,2,11,10,10,8,6,8,10,11,10,11,9,3,7,10,4,11,11,8,10,11,7,4,6,6,7,9,7,9,8,10,4,7,4,8,9,10,8,9,10,9,10,10,8,9,7,5,9,7,8,2,8,5,8,5,7~8,5,4,9,10,6,9,5,10,8,11,11,7,7,9,6,8,7,6,7,9,11,8,9,11,8,7,11,10,7,8,3,8,2,4,3,7,10,3,3,3,4,8,8,10,6,7,5,11,4,11,10,8,9,10,8,11,7,6,9,6,9,6,6,5,8,10,7,9,3,9,2,11,10,11,6,10,9,8,11,6,7~10,10,6,8,11,3,11,10,10,11,6,6,8,6,10,6,10,5,6,9,7,9,9,8,7,8,8,11,6,5,10,6,5,9,5,8,5,7,4,11,6,10,4,8,4,4,5,8,9,4,7,9,8,5,10,9,11,10,3,3,3,7,9,2,9,8,11,10,9,10,9,11,5,8,10,6,2,6,7,4,8,2,9,7,5,10,7,7,8,11,11,3,8,10,6,9,3,5,7,11,11,8,9,10,9,8,8,11,8,9,7,11,4,5,8,6,11,10,8,9,11,11&total_bet_min=10.00';
            }
            else if( $slotEvent['slotEvent'] == 'doCollect' || $slotEvent['slotEvent'] == 'doCollectBonus') 
            {
                $Balance = $slotSettings->GetBalance();
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);    
                $response = 'balance=' . $Balance . '&index=' . $slotEvent['index'] . '&balance_cash=' . $Balance . '&balance_bonus=0.00&na=s&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sver=5&counter=' . ((int)$slotEvent['counter'] + 1);
                
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
            else if( $slotEvent['slotEvent'] == 'doSpin') 
            {
                $lastEvent = $slotSettings->GetHistory();

                $slotEvent['slotBet'] = $slotEvent['c'];
                $slotEvent['slotLines'] = 20;
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    $slotEvent['slotEvent'] = 'freespin';
                }
                $lines = $slotEvent['slotLines'];
                $betline = $slotEvent['slotBet'];
                if( $slotEvent['slotEvent'] == 'doSpin'  || $slotEvent['slotEvent'] == 'freespin') 
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
                    // if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') < $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame')) 
                    // {
                    //     $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"invalid bonus state"}';
                    //     exit( $response );
                    // }
                    if($slotEvent['slotEvent'] == 'respin' || $slotEvent['slotEvent'] == 'freespin'){
                        if ($lastEvent->serverResponse->bet != $betline){
                            $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"invalid Bets"}';
                        exit( $response );
                        }
                    }
                }
                
                $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $betline * $lines, $lines);
                $winType = $_spinSettings[0];
                $_winAvaliableMoney = $_spinSettings[1];
                $allBet = $betline * $lines;

                // $winType = 'bonus';

                $freeStacks = []; 
                $isGeneratedFreeStack = false;
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'Level', 0);
                if($slotEvent['slotEvent'] == 'freespin' || $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') >= 0){
                    if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') < 0){
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    }
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Status', '');
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '6074458' . substr($roundstr, 4, 10);
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
                $winLineCount = 0;
                $str_initReel = '';
                $strWinLine = '';
                $str_lmi = '';
                $str_lmv = '';
                $str_stf = '';
                $str_slm_mp = '';
                $str_slm_mv = '';
                $g = null;
                $bw = 0;
                $str_trail = '';
                $str_mo = '';
                $str_mo_t = '';
                $mo_tv = 0;
                $str_mo_wpos = '';
                $mo_m = 0;
                $apv = 0;
                $str_sts = '';
                $str_sty = '';
                $rs_p = -1;
                $rs_c = 0;
                $rs_m = 0;
                $rs_t = 0;
                $rs_more = 0;
                $fsmax = 0;
                $str_accm = '';
                $str_accv = '';
                $pw = 0;
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $str_initReel = $stack['init_reel'];
                    $str_lmi = $stack['lmi'];
                    $str_lmv = $stack['lmv'];
                    $str_stf = $stack['stf'];
                    $str_slm_mp = $stack['slm_mp'];
                    $str_slm_mv = $stack['slm_mv'];
                    if($stack['g'] != ''){
                        $g = $stack['g'];
                    }
                    $bw = $stack['bw'];
                    $str_trail = $stack['trail'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $mo_tv = $stack['mo_tv'];
                    $str_mo_wpos = $stack['mo_wpos'];
                    $mo_m = $stack['mo_m'];
                    $apv = $stack['apv'];
                    $str_sts = $stack['sts'];
                    $str_sty = $stack['sty'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $rs_more = $stack['rs_more'];
                    $fsmax = $stack['fsmax'];
                    $str_accm = $stack['accm'];
                    $str_accv = $stack['accv'];
                    $pw = str_replace(',', '', $stack['pw']);
                    $strWinLine = $stack['win_line'];
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
                    $str_initReel = $stack[0]['init_reel'];
                    $str_lmi = $stack[0]['lmi'];
                    $str_lmv = $stack[0]['lmv'];
                    $str_stf = $stack[0]['stf'];
                    $str_slm_mp = $stack[0]['slm_mp'];
                    $str_slm_mv = $stack[0]['slm_mv'];
                    if($stack[0]['g'] != ''){
                        $g = $stack[0]['g'];
                    }
                    $bw = $stack[0]['bw'];
                    $str_trail = $stack[0]['trail'];
                    $str_mo = $stack[0]['mo'];
                    $str_mo_t = $stack[0]['mo_t'];
                    $mo_tv = $stack[0]['mo_tv'];
                    $str_mo_wpos = $stack[0]['mo_wpos'];
                    $mo_m = $stack[0]['mo_m'];
                    $apv = $stack[0]['apv'];
                    $str_sts = $stack[0]['sts'];
                    $str_sty = $stack[0]['sty'];
                    $rs_p = $stack[0]['rs_p'];
                    $rs_c = $stack[0]['rs_c'];
                    $rs_m = $stack[0]['rs_m'];
                    $rs_t = $stack[0]['rs_t'];
                    $rs_more = $stack[0]['rs_more'];
                    $fsmax = $stack[0]['fsmax'];
                    $str_accm = $stack[0]['accm'];
                    $str_accv = $stack[0]['accv'];
                    $pw = $stack[0]['pw'];
                    $strWinLine = $stack[0]['win_line'];
                }
                if($strWinLine != ''){
                    $arr_lines = explode('&', $strWinLine);
                    for($k = 0; $k < count($arr_lines); $k++){
                        $arr_sub_lines = explode('~', $arr_lines[$k]);
                        $arr_sub_lines[1] = str_replace(',', '', $arr_sub_lines[1]) / $original_bet * $betline;
                        if($rs_p < 0){
                            $totalWin = $totalWin + $arr_sub_lines[1];
                        }
                        $arr_lines[$k] = implode('~', $arr_sub_lines);
                    }
                    $strWinLine = implode('&', $arr_lines);
                } 
                $moneyWin = 0;
                if($mo_tv > 0){
                    $moneyWin = $mo_tv * $betline;
                    if($mo_m > 0){
                        $moneyWin = $moneyWin * $mo_m;
                    }
                    $totalWin = $totalWin + $moneyWin;
                }
                if($g != null && isset($g['eol_g']) && isset($g['eol_g']['mo_tw']) && $rs_t > 0){
                    $eol_moneyWin = str_replace(',', '', $g['eol_g']['mo_tw']) / $original_bet * $betline;
                    $g['eol_g']['mo_tw'] = '' . $eol_moneyWin;
                    $totalWin = $totalWin + $eol_moneyWin;
                }
                $spinType = 's';
                if( $totalWin > 0) 
                {
                    $spinType = 'c';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }
                $_obf_totalWin = $totalWin;

                $strLastReel = implode(',', $lastReel);
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 5; $i++){
                    $reelA[$i] = mt_rand(5, 11);
                    $reelB[$i] = mt_rand(5, 11);
                }
                $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
               
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                // if($slotEvent['slotEvent'] == 'freespin' && $fsmore > 0){
                //     $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                // }
                $strOtherResponse = '';
                $isState = true;
                $isEnd = true;
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);

                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $rs_p);
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $spinType = 's';
                    $isEnd = false;
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                    {
                        $spinType = 'c';
                        $isEnd = true;
                        $strOtherResponse = '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fsmul_total=1&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres_total='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        if($rs_p < 0){
                            $strOtherResponse = $strOtherResponse . '&fsend_total=1';
                        }else{
                            $strOtherResponse = $strOtherResponse . '&fsend_total=0';
                            $spinType = 's';
                            $isState = false;
                        }
                    }
                    else
                    {
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') .'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 's';
                    }
                }
                if($bw == 1){
                    $isState = false;
                    $spinType = 'b';
                    $strOtherResponse = $strOtherResponse .'&bw=1';
                    $arr_trail = explode(';', $str_trail);
                    $freetype = 'pv';
                    for($k = 0; $k < count($arr_trail); $k++){
                        $sub_trail = $arr_trail[$k];
                        $arr_sub_trail = explode('~', $arr_trail[$k]);
                        if(count($arr_sub_trail) == 2){
                            if($arr_sub_trail[0] == 'fs_t'){
                                $freetype = $arr_sub_trail[1];
                                break;
                            }
                        }
                    }
                    $arr_freetypes = ['pv', 'kc', 'cw', 'bp', 'eol'];
                    $freeIndex = 0;
                    for($k = 0; $k < 5; $k++){
                        if($arr_freetypes[$k] == $freetype){
                            $freeIndex = $k;
                            break;
                        }
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex', $freeIndex);
                    if($freeIndex == 4){
                        $spinType = 's';
                        $stacks = $slotSettings->GetReelStrips('bonus', $betline * $lines, 1, 4);
                        if($stacks == null){
                            $response = 'unlogged';
                            exit( $response );
                        }
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $stacks);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $stacks[0]['fsmax']);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=0.00&fs=1&fsres=0.00';
                    }else{                        
                        $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 69);
                    }
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($currentReelSet >= 0){
                    $strOtherResponse = $strOtherResponse . '&reel_set=' . $currentReelSet;
                }
                if($str_lmv != ''){
                    $strOtherResponse = $strOtherResponse . '&lmi=' . $str_lmi . '&lmv=' . $str_lmv;
                }
                if($str_stf != ''){
                    $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                }
                if($str_slm_mv != ''){
                    $strOtherResponse = $strOtherResponse . '&slm_mp=' . $str_slm_mp . '&slm_mv=' . $str_slm_mv;
                }
                if($g != null){
                    $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($g));
                }
                if($str_trail != ''){
                    $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                }
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                }
                if($mo_tv > 0){
                    $strOtherResponse = $strOtherResponse . '&mo_tv='. $mo_tv .'&mo_wpos=' . $str_mo_wpos . '&mo_c=1&mo_tw=' . $moneyWin;
                }
                if($mo_m > 0){
                    $strOtherResponse = $strOtherResponse . '&mo_m=' . $mo_m;
                }
                if($str_sts != ''){
                    $strOtherResponse = $strOtherResponse . '&sts=' . $str_sts;
                }
                if($str_sty  != ''){
                    $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                }
                if($rs_p >= 0){
                    $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p . '&rs_c=' . $rs_c . '&rs_m=' . $rs_m;
                    if($rs_more > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_more=' . $rs_more;
                    }
                    if($rs_p > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_win=0.00';
                    }
                    $spinType = 's';
                }
                if($rs_t > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t . '&rs_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                }
                if($str_accv != ''){
                    $strOtherResponse = $strOtherResponse . '&accm=' . $str_accm . '&acci=0&accv=' . $str_accv;
                }                
                if($pw > 0){
                    $strOtherResponse = $strOtherResponse . '&pw=' . ($pw / $original_bet * $betline);
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&c='.$betline.'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&l=20&sh=3&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&s='.$strLastReel.'&w='. $totalWin;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) && $rs_p < 0) 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"CurrentRespin":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') . ',"FreeIndex":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeIndex') . ',"Level":' . $slotSettings->GetGameData($slotSettings->slotId . 'Level') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'). ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if($bw == 1 && $slotEvent['slotEvent'] != 'freespin') 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                }

            }else if( $slotEvent['slotEvent'] == 'doBonus' ){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lastReel = $lastEvent->serverResponse->LastReel;
                $lines = 20;
                $scatter = 1;
                $wild = 2;
                $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                $freeIndex = $slotSettings->GetGameData($slotSettings->slotId . 'FreeIndex');
                $level = $slotSettings->GetGameData($slotSettings->slotId . 'Level');
                $arr_ch_h = [];
                for($k = 0; $k < $level; $k++){
                    $arr_ch_h[] = '0~0';
                }
                $level++;
                $ind = -1;
                if(isset($slotEvent['ind'])){
                    $ind = $slotEvent['ind'];
                }
                $isGambleChance = true;
                if($ind == 0){
                    $isGambleChance = $slotSettings->IsGameChance();
                    if($freeIndex >= 3){
                        $isGambleChance = false;
                    }
                    if($isGambleChance == true){
                        $freeIndex++;
                    }
                }
                $arr_ch_h[] = '0~' . $ind;
                $arr_freetypes = ['pv', 'kc', 'cw', 'bp', 'eol'];
                $g = [
                    'gmb' => [
                        'bgid' => '0',
                        'bgt' => '69',
                        'ch_h' => implode(',', $arr_ch_h),
                        'ch_k' => 'gmb_y,gmb_n',
                        'ch_v' => '0,1',
                        'rw' => '0.00'
                    ]
                ];
                $isState = false;
                $spinType = 'b';
                $strOtherResponse = '';
                $end = 0;
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex', $freeIndex);
                $slotSettings->SetGameData($slotSettings->slotId . 'Level', $level);
                if($ind == 1 || $isGambleChance == false || $freeIndex == 4){
                    $spin_type = 1;
                    $end = 1;
                    if($isGambleChance == false){
                        $spin_type = 3;
                    }
                    $stacks = $slotSettings->GetReelStrips('bonus', $betline * $lines, $spin_type, $freeIndex);
                    if($stacks == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $stacks);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                    $stack = $stacks[0];
                    $spinType = 's';
                    $str_trail = $stack['trail'];
                    $apv = $stack['apv'];
                    $fsmax = $stack['fsmax'];
                    if($isGambleChance == false){
                        $totalWin = 0;
                        if($apv > 0){
                            $totalWin = $apv * $betline;
                            $strOtherResponse = $strOtherResponse . '&apwa='. $totalWin .'&apt=ma&apv=' . $apv;
                        }
                        $isState = true;
                        if($totalWin > 0){
                            $spinType = 'cb';
                            $slotSettings->SetBalance($totalWin);
                            $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                            $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                            $g['gmb']['rw'] = '' . $totalWin;
                        }
                        $strOtherResponse = $strOtherResponse . '&trail=fs_t~'. $arr_freetypes[$freeIndex] .';fs_gmb_p~' . $arr_freetypes[$freeIndex + 1];
                    }else{
                        $spinType = 's';
                        if($fsmax > 0){
                            $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $fsmax);
                            $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                            $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=0.00&fs=1&fsres=0.00';
                        }
                        $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                    }                    
                }else{
                    $strOtherResponse = $strOtherResponse  . '&trail=fs_t~'. $arr_freetypes[$freeIndex] .';fs_gmb_p~' . $arr_freetypes[$freeIndex + 1];
                }
                if($end == 0){
                    $g['gmb']['ask'] = '0';
                }
                $g['gmb']['end'] = $end;
                $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                
                $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($g));
                
                $response = 'tw='. $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') .'&balance='. $Balance .'&index='.$slotEvent['index'] . '&balance_cash='. $Balance .'&balance_bonus=0.00&na='. $spinType . $strOtherResponse .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sver=5&counter='. ((int)$slotEvent['counter'] + 1);

                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"CurrentRespin":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') . ',"FreeIndex":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeIndex') . ',"Level":' . $slotSettings->GetGameData($slotSettings->slotId . 'Level') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'). ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
            }
            if($slotEvent['action'] == 'doSpin' || $slotEvent['action'] == 'doCollect' || $slotEvent['action'] == 'doBonus' || $slotEvent['action'] == 'doCollectBonus'){
                $this->saveGameLog($slotEvent, $response, $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'), $slotSettings);
            }
            try{
                $slotSettings->SaveGameData();
                \DB::commit();
            }catch (\Exception $e) {
                $slotSettings->InternalError('TicTacTakeDBCommit : ' . $e);
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

<?php 
namespace VanguardLTE\Games\PenguinsChristmasPartyTimePM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'Bl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 5);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusStep', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', -1);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [4,9,4,4,8,5,10,10,5,7,6,9,6,3,8]);
                $slotSettings->SetGameData($slotSettings->slotId . 'G', []);
                $slotSettings->SetGameData($slotSettings->slotId . 'ScatterCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'Ind', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $strOtherResponse = '';
                $currentReelSet = 0;
                $stack = null;
                
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $lastEvent->serverResponse->CurrentRespin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusStep', $lastEvent->serverResponse->BonusStep);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', $lastEvent->serverResponse->Bgt);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bl', $lastEvent->serverResponse->Bl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'G', json_decode(json_encode($lastEvent->serverResponse->G), true));
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ScatterCount', $lastEvent->serverResponse->ScatterCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Ind', $lastEvent->serverResponse->Ind);
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
                    $bet = '200.00';
                }
                $spinType = 's';
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if(isset($stack)){
                    $str_initReel = $stack['initreel'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $mo_tv = $stack['mo_tv'];
                    $mo_m = $stack['mo_m'];
                    $str_mo_wpos = $stack['mo_wpos'];
                    $str_prg_m = $stack['prg_m'];
                    $str_prg = $stack['prg'];
                    if($stack['reel_set'] > 0){
                        $currentReelSet = $stack['reel_set'];
                    }
                    $fsmore = $stack['fsmore'];
                    $str_accm = $stack['accm'];
                    $str_acci = $stack['acci'];
                    $str_accv = $stack['accv'];
                    $str_stf = $stack['stf'];
                    $str_srf = $stack['srf'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $bw = $stack['bw'];
                    $str_rs = $stack['rs'];
                    $str_rs_iw = $stack['rs_iw'];
                    $str_rs_win = $stack['rs_win'];
                    $str_pw = $stack['pw'];
                    $str_sts = $stack['sts'];
                    $str_sty = $stack['sty'];
                    $str_apv = $stack['apv'];
                    $str_s_mark = '';
                    if(isset($stack['s_mark'])){                        
                        $str_s_mark = $stack['s_mark'];
                    }
                    $arr_g = null;
                    if($stack['g'] != ''){
                        $arr_g = $slotSettings->GetGameData($slotSettings->slotId . 'G');
                    }
                    $fsmax = $stack['fsmax'];
                    $str_psym = $stack['psym'];
                    $str_trail = $stack['trail'];
                    $strWinLine = $stack['win_line'];
                    $str_lmi = $stack['lmi'];
                    $str_lmv = $stack['lmv'];
                    $wmv = $stack['wmv'];
                    $str_slm_mp = $stack['slm_mp'];
                    $str_slm_mv = $stack['slm_mv'];
                    $ind = $slotSettings->GetGameData($slotSettings->slotId . 'Ind');

                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
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
                        $moneyWin = $mo_tv * $bet;
                        if($mo_m > 0){
                            $moneyWin = $moneyWin * $mo_m;
                        }
                        $strOtherResponse = $strOtherResponse . '&mo_c=1&mo_tv=' . $mo_tv . '&mo_tw=' . $moneyWin;
                    }
                    if($mo_m > 0){
                        $strOtherResponse = $strOtherResponse . '&mo_m=' . $mo_m;
                    }
                    if($str_prg != ''){
                        $strOtherResponse = $strOtherResponse . '&prg=' . $str_prg;
                    }
                    if($str_prg_m != ''){
                        $strOtherResponse = $strOtherResponse . '&prg_m=' . $str_prg_m;
                    }
                    if($str_accm != ''){
                        $strOtherResponse = $strOtherResponse . '&accm=' . $str_accm;
                    }
                    if($str_accv != ''){
                        $strOtherResponse = $strOtherResponse . '&accv=' . $str_accv;
                    }
                    if($str_acci != ''){
                        $strOtherResponse = $strOtherResponse . '&acci=' . $str_acci;
                    }
                    if($str_stf != ''){
                        $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                    }
                    if($str_srf != ''){
                        $strOtherResponse = $strOtherResponse . '&srf=' . $str_srf;
                    }
                    if($str_trail != ''){
                        if($ind > 0){
                            $arr_trails = explode('~', $str_trail);
                            if(count($arr_trails) == 2)
                            {
                                $arr_sub_trails = explode(',', $arr_trails[1]);
                                $temp_trail = $arr_sub_trails[0];
                                $arr_sub_trails[0] = $arr_sub_trails[$ind];
                                $arr_sub_trails[$ind] = $temp_trail;
                                $str_trail = $arr_trails[0] . '~' . implode(',', $arr_sub_trails);
                            }
                        }
                        $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                    }
                    if($str_s_mark != ''){
                        $strOtherResponse = $strOtherResponse . '&s_mark=' . $str_s_mark;
                    }
                    if($rs_p != ''){
                        $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p . '&rs_m=' . $rs_m . '&rs_c=' . $rs_c;
                    }
                    if($rs_t != ''){
                        $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t;
                    }
                    
                    if($str_rs != ''){
                        $strOtherResponse = $strOtherResponse . '&rs=' . $str_rs;
                    }
                    if($str_rs_iw != ''){
                        $strOtherResponse = $strOtherResponse . '&rs_iw=' . str_replace(',', '', $str_rs_iw) / $original_bet * $bet;
                    }
                    if($str_rs_win != ''){
                        $strOtherResponse = $strOtherResponse . '&rs_win=' . str_replace(',', '', $str_rs_win) / $original_bet * $bet;
                    }
                    if($str_pw != ''){
                        $strOtherResponse = $strOtherResponse . '&pw=' . str_replace(',', '', $str_pw) / $original_bet * $bet;
                    }
                    if($str_sts != ''){
                        $strOtherResponse = $strOtherResponse . '&sts=' . $str_sts;
                    }
                    if($str_sty != ''){
                        $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                    }
                    if($str_apv != ''){
                        $strOtherResponse = $strOtherResponse . '&apwa='. ($str_apv * $bet) .'&apt=ma&apv=' . $str_apv;
                    }
                    if($bw > 0){
                        $spinType = 'b';
                        $strOtherResponse = $strOtherResponse . '&bw=' . $bw;
                    }
                    if($arr_g != null){
                        $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($arr_g));
                    }
                    if($str_lmv != ''){
                        $strOtherResponse = $strOtherResponse . '&lmi=' . $str_lmi . '&lmv=' . $str_lmv;
                    }
                    if($str_slm_mv != ''){
                        $strOtherResponse = $strOtherResponse . '&slm_mv=' . $str_slm_mv . '&slm_mp=' . $str_slm_mp;
                    }
                    if($wmv > 0){
                        $strOtherResponse = $strOtherResponse . '&wmt=pr&wmv=' . $wmv;
                        if($wmv > 1){
                            $strOtherResponse = $strOtherResponse . '&gwm=' . $wmv;
                        }
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'Bgt') >= 0){
                        $spinType = 'b';
                    }
                    if($str_psym != ''){
                        $arr_psym = explode('~', $str_psym);
                        $arr_psym[1] = str_replace(',', '', $arr_psym[1]) / $original_bet * $bet;
                        $str_psym = implode('~', $arr_psym);
                        $strOtherResponse = $strOtherResponse . '&psym=' . $str_psym;
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
                $response = 'def_s=4,9,4,4,8,5,10,10,5,7,6,9,6,3,8&balance='. $Balance .'&cfgs=12892&ver=3&index=1&balance_cash='. $Balance .'&def_sb=3,9,10,4,8&reel_set_size=5&def_sa=4,10,6,5,8&reel_set=0&balance_bonus=0.00&na='. $spinType .'&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"17612",max_rnd_win:"2000",max_rnd_win_a:"1000",max_rnd_hr_a:"6944"}}&wl_i=tbm~2000;tbm_a~1000&bl='. $slotSettings->GetGameData($slotSettings->slotId . 'Bl') .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime='. floor(microtime(true) * 1000) .'&sa=4,10,6,5,8&sb=3,9,10,4,8&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=200.00&purInit_e=1&sh=3&wilds=2~5000,100,20,0,0~1,1,1,1,1&bonuses=0&st=rect&c='. $bet .'&sw=5&sver=5&bls=5,10&counter=2&ntp=0.00&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;5000,100,20,0,0;50,20,10,0,0;20,10,4,0,0;10,4,2,0,0;10,4,2,0,0;4,2,1,0,0;4,2,1,0,0;4,2,1,0,0&l=5&total_bet_max=20,000,000.00&reel_set0=10,4,1,7,8,8,8,3,8,5,7,1,9,9,9,6,9,6,10,5,10,10,10,4,7,6,8,10,9,9~5,3,9,9,10,7,8,8,8,10,9,4,9,2,10,9,9,9,6,5,7,8,8,7,9,10,10,10,6,6,4,6,10,7,5,10,8~6,10,5,4,8,8,8,9,10,2,7,9,9,8,9,9,9,5,8,1,10,6,10,7,10,10,10,6,3,9,5,7,8,4,1~7,5,6,9,7,8,8,8,4,8,6,10,4,5,9,9,9,8,9,2,6,10,10,10,3,5,10,8,10,7,8~10,5,9,10,4,8,8,8,9,8,5,4,6,9,9,9,1,8,9,1,10,8,10,10,10,3,7,6,9,10,6,7,7&s='.$lastReelStr.'&reel_set2=9,8,4,9,8,4,6,8,8,8,9,8,6,7,10,5,1,9,9,9,6,10,7,5,7,1,6,7,10,10,10,8,10,10,3,10,1,8,5,9~4,10,9,7,9,8,8,8,7,6,6,5,10,8,9,9,9,2,3,8,6,6,7,10,10,10,9,7,5,9,4,8,10~8,6,10,1,10,4,9,8,8,8,1,4,6,3,6,2,9,9,9,10,5,6,9,9,7,10,1,10,10,10,7,7,10,8,8,9,8,7,5~8,5,9,10,4,8,8,8,9,10,10,8,7,6,9,9,9,5,2,7,4,3,10,10,10,8,7,6,10,8,6,5,9~10,4,6,6,8,8,8,5,1,9,10,8,8,9,9,9,1,10,6,5,7,10,10,10,7,4,9,7,8,3,9&reel_set1=4,6,5,9,8,8,8,1,9,6,1,9,9,9,7,5,9,3,10,8,10,10,10,6,10,7,10,8,7,4~10,9,4,7,7,6,8,8,8,5,7,2,10,9,6,8,9,9,9,7,8,9,5,10,6,8,10,10,10,8,4,10,5,10,6,3,9,8~5,1,5,8,10,8,8,8,10,8,1,9,10,9,9,9,6,6,4,7,9,10,10,10,7,10,2,3,8,6,7,8~10,10,7,4,8,8,8,10,7,10,4,6,5,6,9,9,9,5,8,7,9,8,10,10,10,6,8,10,7,9,2,9,6,3~8,9,5,6,10,8,8,8,5,6,1,10,8,7,6,8,9,9,9,3,4,7,9,1,10,6,10,10,10,7,8,4,9,7,10,10,9,5&reel_set4=4,10,6,9,8,8,8,7,7,6,8,6,7,9,9,9,10,10,8,4,3,9,10,10,10,5,9,8,10,5,9,8~3,8,10,8,10,8,8,8,7,10,5,6,7,5,9,9,9,10,4,9,5,9,6,10,10,10,8,7,9,9,6,8,4,2~8,5,7,6,8,5,8,8,8,2,10,5,9,10,4,7,9,9,9,8,4,7,10,6,8,10,10,10,3,9,10,9,7,6,10,9,6~7,5,9,8,8,8,7,6,5,8,10,6,9,9,9,8,7,10,10,6,9,10,10,10,9,2,4,8,9,4,3,10~8,5,6,10,6,8,8,8,3,8,4,5,10,5,9,9,9,4,9,7,7,8,8,10,10,10,9,6,7,9,10,9,10&purInit=[{bet:500,type:"default"}]&reel_set3=9,8,7,6,5,2,7,8,6,4,9,3,6,7,10,5,10~4,9,5,6,9,8,7,4,10,6,3,10,7,8,6,5,2,7~9,4,10,7,6,6,4,5,9,8,3,8,5,6,2,7,7,10~10,6,2,6,9,3,5,7,9,5,4,7,8,10,8,6,4,7~6,6,8,2,9,5,7,4,7,5,6,3,8,10,9,8,7,9,5,10&total_bet_min=100.00';
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
                $lines = 5;      
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
                $slotEvent['slotLines'] = 5;
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
                $pur_mul = [100, 60];
                $allBet = $betline * $lines;
                if($pur >= 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $allBet * $pur_mul[$pur];
                }else if($bl > 0){
                    $allBet = $betline * 10;
                }
                $tumbAndFreeStacks = []; 
                $isGeneratedFreeStack = false;
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusStep', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'Ind', -1);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bl', $bl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'G', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', -1);
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
                $bonusMpl = 1;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $str_initReel = '';
                $str_mo = '';
                $str_mo_t = '';
                $str_mo_wpos = '';
                $mo_tv = 0;
                $mo_m = 0;
                $str_prg_m = '';
                $str_prg = '';
                $fsmore = 0;
                $str_accm = '';
                $str_acci = '';
                $str_accv = '';
                $str_stf = '';
                $str_srf = '';
                $str_trail = '';
                $fsmore = 0;
                $rs_p = '';
                $rs_c = '';
                $rs_m = '';
                $rs_t = '';
                $bw = 0;
                $fsmax = 0;
                $arr_g = null;
                $scatterCount = 0;
                $strReelSa = '';
                $strReelSb = '';
                $str_s_mark = '';
                $str_rs = '';
                $str_rs_iw = '';
                $str_rs_win = '';
                $str_pw = '';
                $str_sts = '';
                $str_sty = '';
                $str_apv = '';
                $str_psym = '';
                $str_lmi = '';
                $str_lmv = '';
                $wmv = 0;
                $str_slm_mp = '';
                $str_slm_mv = '';
                if($slotEvent['slotEvent'] == 'freespin' || $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') >= 0){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_initReel = $stack['initreel'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $mo_tv = $stack['mo_tv'];
                    $mo_m = $stack['mo_m'];
                    $str_mo_wpos = $stack['mo_wpos'];
                    $str_prg_m = $stack['prg_m'];
                    $str_prg = $stack['prg'];
                    $currentReelSet = $stack['reel_set'];
                    $fsmore = $stack['fsmore'];
                    $str_accm = $stack['accm'];
                    $str_acci = $stack['acci'];
                    $str_accv = $stack['accv'];
                    $str_stf = $stack['stf'];
                    $str_srf = $stack['srf'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $str_rs = $stack['rs'];
                    $str_rs_iw = $stack['rs_iw'];
                    $str_rs_win = $stack['rs_win'];
                    $str_pw = $stack['pw'];
                    $str_sts = $stack['sts'];
                    $str_sty = $stack['sty'];
                    $str_apv = $stack['apv'];
                    $strReelSa = $stack['sa'];
                    $strReelSb = $stack['sb'];
                    $str_s_mark = $stack['s_mark'];
                    $bw = $stack['bw'];
                    if($stack['g'] != ''){
                        $arr_g = $stack['g'];
                    }
                    $str_lmi = $stack['lmi'];
                    $str_lmv = $stack['lmv'];
                    $wmv = $stack['wmv'];
                    $str_slm_mp = $stack['slm_mp'];
                    $str_slm_mv = $stack['slm_mv'];
                    $fsmax = $stack['fsmax'];
                    $str_psym = $stack['psym'];
                    $str_trail = $stack['trail'];
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
                    $str_mo = $stack[0]['mo'];
                    $str_mo_t = $stack[0]['mo_t'];
                    $mo_tv = $stack[0]['mo_tv'];
                    $str_mo_wpos = $stack[0]['mo_wpos'];
                    $str_prg_m = $stack[0]['prg_m'];
                    $str_prg = $stack[0]['prg'];
                    $currentReelSet = $stack[0]['reel_set'];
                    $fsmore = $stack[0]['fsmore'];
                    $str_accm = $stack[0]['accm'];
                    $str_acci = $stack[0]['acci'];
                    $str_accv = $stack[0]['accv'];
                    $str_stf = $stack[0]['stf'];
                    $str_srf = $stack[0]['srf'];
                    $rs_p = $stack[0]['rs_p'];
                    $rs_c = $stack[0]['rs_c'];
                    $rs_m = $stack[0]['rs_m'];
                    $rs_t = $stack[0]['rs_t'];
                    $str_rs = $stack[0]['rs'];
                    $str_rs_iw = $stack[0]['rs_iw'];
                    $str_rs_win = $stack[0]['rs_win'];
                    $str_pw = $stack[0]['pw'];
                    $str_sts = $stack[0]['sts'];
                    $str_sty = $stack[0]['sty'];
                    $str_apv = $stack[0]['apv'];
                    $strReelSa = $stack[0]['sa'];
                    $strReelSb = $stack[0]['sb'];
                    $str_s_mark = $stack[0]['s_mark'];
                    $bw = $stack[0]['bw'];
                    if($stack[0]['g'] != ''){
                        $arr_g = $stack[0]['g'];
                    }
                    $str_lmi = $stack[0]['lmi'];
                    $str_lmv = $stack[0]['lmv'];
                    $wmv = $stack[0]['wmv'];
                    $str_slm_mp = $stack[0]['slm_mp'];
                    $str_slm_mv = $stack[0]['slm_mv'];
                    $fsmax = $stack[0]['fsmax'];
                    $str_psym = $stack[0]['psym'];
                    $str_trail = $stack[0]['trail'];
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
                if($str_psym != ''){
                    $arr_psym = explode('~', $str_psym);
                    $arr_psym[1] = str_replace(',', '', $arr_psym[1]) / $original_bet * $betline;
                    $totalWin = $totalWin + $arr_psym[1];
                    $str_psym = implode('~', $arr_psym);
                }
                $moneyWin = 0;
                if($mo_tv > 0){
                    $moneyWin = $mo_tv * $betline; // * $mul;
                    if($mo_m > 1){
                        $moneyWin = $moneyWin * $mo_m;
                    }
                    $totalWin += $moneyWin;
                }
                $apwa = 0;
                if($str_apv != ''){
                    $apwa = $str_apv * $betline;
                    $totalWin += $apwa;
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
                // $reelA = [];
                // $reelB = [];
                // for($i = 0; $i < 5; $i++){
                //     $reelA[$i] = mt_rand(4, 8);
                //     $reelB[$i] = mt_rand(4, 8);
                // }
                // $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                // $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
                $scatterCount = 0;
                for($i = 0; $i < count($lastReel); $i++){
                    if($lastReel[$i] == 1){
                        $scatterCount++;
                    }
                }
                $strLastReel = implode(',', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'ScatterCount', $scatterCount);
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                if($rs_p != ''){
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', 0);
                }else{
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
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
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
                    // if($scatterCount >= 3){
                    //     $isState = false;
                    //     $spinType = 's';
                    //     $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1';
                    // }
                }
                if($fsmax > 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $isState = false;
                    $spinType = 's';
                    $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1';
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
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                }
                if($str_mo_wpos != ''){
                    $strOtherResponse = $strOtherResponse . '&mo_wpos=' . $str_mo_wpos;
                }
                if($mo_tv > 0){
                    $strOtherResponse = $strOtherResponse . '&mo_c=1&mo_tv=' . $mo_tv . '&mo_tw=' . $moneyWin;
                }
                if($mo_m > 0){
                    $strOtherResponse = $strOtherResponse . '&mo_m=' . $mo_m;
                }
                if($str_prg != ''){
                    $strOtherResponse = $strOtherResponse . '&prg=' . $str_prg;
                }
                if($str_prg_m != ''){
                    $strOtherResponse = $strOtherResponse . '&prg_m=' . $str_prg_m;
                }
                if($str_accm != ''){
                    $strOtherResponse = $strOtherResponse . '&accm=' . $str_accm;
                }
                if($str_accv != ''){
                    $strOtherResponse = $strOtherResponse . '&accv=' . $str_accv;
                }
                if($str_acci != ''){
                    $strOtherResponse = $strOtherResponse . '&acci=' . $str_acci;
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
                if($str_lmv != ''){
                    $strOtherResponse = $strOtherResponse . '&lmi=' . $str_lmi . '&lmv=' . $str_lmv;
                }
                if($str_slm_mv != ''){
                    $strOtherResponse = $strOtherResponse . '&slm_mv=' . $str_slm_mv . '&slm_mp=' . $str_slm_mp;
                }
                if($wmv > 0){
                    $strOtherResponse = $strOtherResponse . '&wmt=pr&wmv=' . $wmv;
                    if($wmv > 1){
                        $strOtherResponse = $strOtherResponse . '&gwm=' . $wmv;
                    }
                }
                if($rs_p != ''){
                    $isState = false;
                    $spinType = 's';
                    $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p . '&rs_m=' . $rs_m . '&rs_c=' . $rs_c;
                }
                if($rs_t != ''){
                    $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t;
                }
                if($bw > 0){
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusStep', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 69);
                    $strOtherResponse = $strOtherResponse . '&bw=' . $bw;
                    $spinType = 'b';
                    $isState = false;
                }
                if($str_rs != ''){
                    $strOtherResponse = $strOtherResponse . '&rs=' . $str_rs;
                }
                if($str_rs_iw != ''){
                    $strOtherResponse = $strOtherResponse . '&rs_iw=' . str_replace(',', '', $str_rs_iw) / $original_bet * $betline;
                }
                if($str_rs_win != ''){
                    $strOtherResponse = $strOtherResponse . '&rs_win=' . str_replace(',', '', $str_rs_win) / $original_bet * $betline;
                }
                if($str_pw != ''){
                    $strOtherResponse = $strOtherResponse . '&pw=' . str_replace(',', '', $str_pw) / $original_bet * $betline;
                }
                if($str_sts != ''){
                    $strOtherResponse = $strOtherResponse . '&sts=' . $str_sts;
                }
                if($str_sty != ''){
                    $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                }
                if($str_apv != ''){
                    $strOtherResponse = $strOtherResponse . '&apwa='. $apwa .'&apt=ma&apv=' . $str_apv;
                }
                if($arr_g != null){
                    $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($arr_g));
                    $slotSettings->SetGameData($slotSettings->slotId . 'G', $arr_g);
                }else{
                    $slotSettings->SetGameData($slotSettings->slotId . 'G', []);
                }
                if($str_psym != ''){
                    $strOtherResponse = $strOtherResponse . '&psym=' . $str_psym;
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance . '&reel_set='. $currentReelSet.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&st=rect&c='.$betline.'&sw=5&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=25&w='.$totalWin.'&s=' . $strLastReel;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if( $slotEvent['slotEvent'] != 'freespin' && ($fsmax > 0 || $bw == 1)) 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"CurrentRespin":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"ScatterCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'ScatterCount') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"Ind":' . $slotSettings->GetGameData($slotSettings->slotId . 'Ind') . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin'). ',"BonusStep":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusStep'). ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt'). ',"Bl":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bl'). ',"G":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'G')) . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                if($str_rs == 'stack_the_cash' && $isState == true){
                    $slotEvent['slotEvent'] == 'respin';
                }
                $allBet = $betline * $lines;
                if(($slotEvent['slotEvent'] == 'freespin' || $slotEvent['slotEvent'] == 'respin') && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * $pur_mul[$slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin')];
                }else if($isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'Bl') > 0){
                    $allBet = $betline * 10;
                }
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
            }else if($slotEvent['slotEvent'] == 'doBonus'){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lastReel = $lastEvent->serverResponse->LastReel;
                $lines = 20;
                $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                $ind = $slotEvent['ind'];
                $strOtherResponse = '';
                $spinType = 's';
                $isState = false;
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                $slotSettings->SetGameData($slotSettings->slotId . 'Ind', $ind);
                $arr_g = $stack['g'];
                $str_trail = $stack['trail'];
                $fsmax = $stack['fsmax'];
                $end = 0;
                $totalWin = 0;
                if($ind > 0){
                    $arr_trails = explode('~', $str_trail);
                    if(count($arr_trails) == 2)
                    {
                        $arr_sub_trails = explode(',', $arr_trails[1]);
                        $temp_trail = $arr_sub_trails[0];
                        $arr_sub_trails[0] = $arr_sub_trails[$ind];
                        $arr_sub_trails[$ind] = $temp_trail;
                        $str_trail = $arr_trails[0] . '~' . implode(',', $arr_sub_trails);
                    }
                }
                if(isset($arr_g['bg_0']))
                {
                    if(isset($arr_g['bg_0']['ch_h']))
                    {
                        $arr_g['bg_0']['ch_h'] = '0~' . $ind;
                    }
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $fsmax);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', -1);
                if($str_trail != ''){
                    $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                }
                if($arr_g != null){
                    $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($arr_g));
                    $slotSettings->SetGameData($slotSettings->slotId . 'G', $arr_g);
                }

                $response = 'balance='.$Balance . $strOtherResponse .'&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='. $spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&fsres=0.00&sver=5&counter='. ((int)$slotEvent['counter'] + 1);

                
                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------
                
                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"CurrentRespin":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"ScatterCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'ScatterCount') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"Ind":' . $slotSettings->GetGameData($slotSettings->slotId . 'Ind') . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin'). ',"BonusStep":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusStep'). ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt'). ',"Bl":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bl'). ',"G":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'G')) . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $pur_mul = [100];
                    $allBet = $allBet * $pur_mul[$slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin')];
                }else if($isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'Bl') > 0){
                    $allBet = $betline * 10;
                }
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $totalWin, $slotEvent['slotEvent'], $isState);
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

<?php 
namespace VanguardLTE\Games\_5LionsGoldPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 18);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [4,3,7,7,4,11,5,6,7,11,10,5,9,8,8]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks

                $slotSettings->SetGameData($slotSettings->slotId . 'SpinType', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeSpinType', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'FscSessions', []);
                $slotSettings->SetGameData($slotSettings->slotId . 'FscWinTotal', []);
                $slotSettings->SetGameData($slotSettings->slotId . 'FscTotal', []);
                $slotSettings->SetGameData($slotSettings->slotId . 'Wins', []);
                $slotSettings->SetGameData($slotSettings->slotId . 'Status', []);
                $slotSettings->SetGameData($slotSettings->slotId . 'Gri', '');
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
                                        
                    $slotSettings->SetGameData($slotSettings->slotId . 'SpinType', $lastEvent->serverResponse->SpinType);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeSpinType', $lastEvent->serverResponse->FreeSpinType);
                    if($lastEvent->serverResponse->FscSessions != ''){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FscSessions', explode(',', $lastEvent->serverResponse->FscSessions));
                    }
                    if($lastEvent->serverResponse->FscWinTotal != ''){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FscWinTotal', explode(',', $lastEvent->serverResponse->FscWinTotal));
                    }
                    if($lastEvent->serverResponse->FscTotal != ''){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FscTotal', explode(',', $lastEvent->serverResponse->FscTotal));
                    }
                    if($lastEvent->serverResponse->Wins != ''){
                        $slotSettings->SetGameData($slotSettings->slotId . 'Wins', explode(',', $lastEvent->serverResponse->Wins));
                    }
                    if($lastEvent->serverResponse->Status != ''){
                        $slotSettings->SetGameData($slotSettings->slotId . 'Status', explode(',', $lastEvent->serverResponse->Status));
                    }
                    if($lastEvent->serverResponse->Gri != ''){
                        $slotSettings->SetGameData($slotSettings->slotId . 'Gri', $lastEvent->serverResponse->Gri);
                    }

                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
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
                $spinType = 's';
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                $bonusType = '';
                if(isset($stack)){
                    $aw = $stack['aw'];
                    $str_gri = $stack['gri'];                  
                    $bgid = $stack['bgid'];
                    $bgt = $stack['bgt'];
                    $bw = $stack['bw'];
                    $wp = $stack['wp'];
                    $end = $stack['end'];
                    $level = $stack['level'];
                    $lifes = $stack['lifes'];
                    $str_fs_opt = $stack['fs_opt'];
                    $str_fs_opt_mask = $stack['fs_opt_mask'];
                    $str_wrlm_cs = $stack['wrlm_cs'];
                    $str_wrlm_res = $stack['wrlm_res'];
                    $str_wins = $stack['wins'];
                    $str_wins_mask = $stack['wins_mask'];
                    $str_status = $stack['wins_status'];
                    $str_bg_i = $stack['bg_i'];
                    $str_bg_i_mask = $stack['bg_i_mask'];
                    $str_win_line = $stack['win_line'];

                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0 && $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0){
                        $strOtherResponse = $strOtherResponse . '&fs_total='.($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 1).'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsend_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'SpinType') > 0){
                        $spinType = 'fso';
                    }
                    if($aw >= 0){
                        $strOtherResponse = $strOtherResponse . '&aw=' . $aw . '&awt=rsf';
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'Gri') != ''){
                        $strOtherResponse = $strOtherResponse . '&gri=' . $slotSettings->GetGameData($slotSettings->slotId . 'Gri');
                    }
                    if($bgid >= 0){
                        $strOtherResponse = $strOtherResponse . '&bgid=' . $bgid;
                    }
                    if($bgt > 0){
                        $strOtherResponse = $strOtherResponse . '&bgt=' . $bgt . '&coef=' . ($bet * 18);
                        if($end == 0){
                            $spinType = 'b';
                        }
                    }
                    if($bw > 0){
                        $strOtherResponse = $strOtherResponse . '&bw=' . $bw;
                    }
                    if($wp >= 0){
                        $strOtherResponse = $strOtherResponse . '&wp=' . $wp . '&rw=' . ($wp * $bet * 18);
                    }
                    if($end >= 0){
                        $strOtherResponse = $strOtherResponse . '&end=' . $end;
                    }
                    if($level >= 0){
                        $strOtherResponse = $strOtherResponse . '&level=' . $level;
                    }
                    if($lifes >= 0){
                        $strOtherResponse = $strOtherResponse . '&lifes=' . $lifes;
                    }
                    if($spinType == 'fso'){
                        $strOtherResponse = $strOtherResponse . '&fs_opt_mask=fs,m,ts,rm&fs_opt=25,1,2,2;3;5~20,1,2,3;5;8~15,1,2,5;8;10~13,1,2,8;10;15~10,1,2,10;15;30~6,1,2,15;30;40~-1,-1,2,-1';
                    }
                    if($str_wrlm_cs != ''){
                        $strOtherResponse = $strOtherResponse . '&wrlm_cs=' . $str_wrlm_cs . '&wrlm_res=' . $str_wrlm_res;
                    }
                    if($str_wins != ''){                    
                        $arr_wins = $slotSettings->GetGameData($slotSettings->slotId . 'Wins');
                        $arr_status = $slotSettings->GetGameData($slotSettings->slotId . 'Status');
                        $arr_wins_mask = [];
                        if($bgt == 21){
                            $old_wins_mask = explode(',', $str_wins_mask);
                            if($arr_status[1] == 1){
                                $arr_wins_mask[0] = $old_wins_mask[1];
                                $arr_wins_mask[1] = $old_wins_mask[0];
                            }else{
                                $arr_wins_mask[0] = $old_wins_mask[0];
                                $arr_wins_mask[1] = $old_wins_mask[1];
                            }      
                            if($end == 1){
                                $bonusType = $old_wins_mask[0];
                            }
                        }else if($bgt == 18){                            
                            for($k=0; $k<9; $k++){
                                if($arr_status[$k] > 0){
                                    $arr_wins_mask[$k] = 'pw';
                                }else{
                                    $arr_wins_mask[$k] = 'h';
                                }
                            }
                        }
                        $strOtherResponse = $strOtherResponse . '&wins=' . implode(',', $arr_wins) . '&status=' . implode(',', $arr_status) . '&wins_mask=' . implode(',', $arr_wins_mask);
                    }
                    if($str_bg_i != ''){
                        $strOtherResponse = $strOtherResponse . '&bg_i=' . $str_bg_i . '&bg_i_mask=' . $str_bg_i_mask;
                    }
                    if($str_win_line != ''){
                        $arr_lines = explode('&', $str_win_line);
                        for($k = 0; $k < count($arr_lines); $k++){
                            $arr_sub_lines = explode('~', $arr_lines[$k]);
                            $arr_sub_lines[1] = str_replace(',', '', $arr_sub_lines[1]) / $original_bet * $bet;
                            $arr_lines[$k] = implode('~', $arr_sub_lines);
                        }
                        $str_win_line = implode('&', $arr_lines);
                        $strOtherResponse = $strOtherResponse . '&' . $str_win_line;
                    }
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }
                if($bonusType == 'stp'){
                    $spinType = 'b';
                }else{
                    if( ($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) || count($slotSettings->GetGameData($slotSettings->slotId . 'FscSessions')) > 0 ) 
                    {
                        $strOtherResponse = $strOtherResponse . '&fs=' . ($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 1) . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&w=0.00&fsmul=1';
                        $fscWinTotal = $slotSettings->GetGameData($slotSettings->slotId . 'FscWinTotal');
                        $fscTotal = $slotSettings->GetGameData($slotSettings->slotId . 'FscTotal');
                        $fscMuls = [];
                        for($k = 0; $k < count($fscWinTotal); $k++){
                            $fscMuls[] = 1;
                        }
                        if(count($fscWinTotal) > 0){
                            $strOtherResponse = $strOtherResponse . '&fsc_win_total='. implode(',', $fscWinTotal).'&fsc_res_total='. implode(',', $fscWinTotal) .'&fsc_mul_total='. implode(',', $fscMuls) .'&fsc_total=' . implode(',', $fscTotal);
                        }
                        if(count($slotSettings->GetGameData($slotSettings->slotId . 'FscSessions')) > 0){
                            $strOtherResponse = $strOtherResponse . '&fsc_sessions=' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'FscSessions'));
                        }
                        if($slotSettings->GetGameData($slotSettings->slotId . 'FreeSpinType') >= 0){
                            $strOtherResponse = $strOtherResponse . '&fsopt_i=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeSpinType');
                        }
                    }
                }
                $Balance = $slotSettings->GetBalance();  
                $response = 'def_s=4,3,7,7,4,11,5,6,7,11,10,5,9,8,8&balance='. $Balance .'&cfgs=2435&ver=2&index=1&balance_cash='. $Balance .'&reel_set_size=8&def_sb=7,11,8,10,6&def_sa=6,3,5,8,11&bonusInit=[{bgid:0,bgt:33,bg_i:"15,150,2000",bg_i_mask:"wp,wp,wp"},{bgid:2,bgt:18,bg_i:"2000,150,15",bg_i_mask:"pw,pw,pw"}]&balance_bonus=0.00&wrlm_sets=2~0~2,3,5~1~3,5,8~2~5,8,10~3~8,10,15~4~10,15,30~5~15,30,40&na='. $spinType .'&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&fs_aw=t;n&gmb=0,0,0&rt=d&base_aw=t;n&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime='. floor(microtime(true) * 1000) .'&sa=6,3,5,8,11&sb=7,11,8,10,6&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=50.00&sh=3&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='. $bet .'&sver=5&n_reel_set=0&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;126,45,15,2,0;81,30,12,0,0;81,30,12,0,0;63,24,9,0,0;63,24,9,0,0;36,12,4,0,0;36,12,4,0,0;27,9,3,0,0;27,9,3,0,0;18,6,2,0,0;18,6,2,0,0&l=18&reel_set0=9,8,10,5,10,13,5,7,13,8,12,5,4,7,7,3,4,9,9,8,5,5,12,3,3,6,6,4,4,11,11,13,4,8,8,13,3,9,7,11,13,7,6,11,7,12,9,13,13,3,8,6,12,13,4,11,7,8,11,10,10,3,6,12,10,12,5,6,11,10,6,10,13,9,10,7,12,8,12,11,9,11,8,9,13,12,5,9,6,10,4~4,6,9,9,1,11,11,13,2,7,8,8,12,6,10,2,3,13,7,11,9,3,9,12,8,6,5,5,3,7,11,13,8,1,5,8,9,4,6,10,4,4,13,13,1,5,10,10,3,6,9,11,1,10,6,4,12,2,6,6,4,9,13,8,1,3,10,12,12,11,5,3,12,13,2,4,12,7,7,10,13,9,2,11,5,5,9,8,1,6,7,11,2,10,12,10,8,1,7,4,13,12,7,8~4,12,9,13,11,6,2,7,4,4,5,8,3,12,9,9,1,8,11,8,10,11,2,12,4,3,10,11,12,5,1,8,6,4,13,11,11,2,6,8,3,7,10,5,5,3,10,7,6,6,4,1,3,6,7,10,9,13,1,12,5,12,12,2,8,8,5,12,11,9,2,6,7,13,13,4,6,2,7,13,12,3,6,7,7,10,10,8,1,5,10,8,7,13,9,9,13,11,1,9,9,10,11~13,6,13,7,7,10,6,1,8,9,9,6,10,2,12,13,10,10,8,11,2,12,6,5,13,7,2,5,9,5,4,1,11,11,11,12,12,4,7,4,6,8,1,9,13,13,12,6,3,10,5,10,7,3,8,4,9,3,4,6,6,12,1,8,8,12,7,3,10,10,9,11,2,12,10,5,9,2,13,11,8,11,11,2,13,9,5,5,3,12,9,8,11,3,7,4,4,13,6,7,13,8,12,4,5,7,11~13,8,10,13,5,13,5,5,9,11,10,6,13,6,10,10,11,9,9,13,13,11,6,3,8,10,7,7,5,3,12,7,10,4,12,12,11,8,11,13,12,4,6,8,11,6,6,3,8,12,7,13,4,6,9,3,12,4,4,9,12,11,11,7,8,8,10,5,4,12,3,13,7,11,6,9,8,7,3,9,10,5,9,7,4,12,10,9,8&s='.$lastReelStr.'&reel_set2=9,8,10,5,10,13,5,7,7,13,8,12,12,5,4,7,3,4,9,8,5,12,3,6,4,11,13,4,8,13,3,9,7,11,13,7,6,11,7,12,9,13,3,8,8,6,12,13,13,4,11,7,8,11,10,3,6,12,10,12,5,5,6,6,11,10,6,10,13,9,10,7,12,8,12,11,9,11,8,9,13,12,5,9,6,10,4~4,6,9,1,11,11,13,2,7,8,12,6,10,2,3,13,7,11,9,3,9,12,8,6,6,5,5,3,3,7,11,13,8,1,5,8,9,4,6,10,4,13,13,1,5,10,3,6,9,9,11,1,10,6,4,12,2,6,6,4,9,13,8,1,3,10,12,12,11,5,3,12,13,13,13,2,4,12,7,7,10,13,9,2,11,5,5,9,8,1,6,7,11,2,10,12,10,8,1,7,4,13,12,7,8~4,12,9,13,11,6,2,7,4,5,8,3,12,9,1,8,11,8,10,11,2,12,4,3,10,11,12,5,1,8,6,4,13,11,11,2,6,8,3,7,10,5,5,3,10,10,7,6,4,1,3,6,7,10,9,13,1,12,5,12,2,8,5,12,11,9,2,6,7,13,13,4,6,6,2,7,13,12,3,6,7,10,8,1,5,10,8,7,7,13,9,9,13,11,1,9,9,10,11~13,6,13,7,10,6,1,8,9,6,10,2,12,13,10,8,11,2,12,6,5,13,7,2,5,9,5,4,1,11,12,12,4,7,4,6,8,8,1,9,13,13,6,3,10,5,10,7,3,8,4,9,3,4,6,12,1,8,12,7,7,3,10,10,9,9,11,2,12,10,5,9,2,13,11,8,11,2,13,9,5,3,12,9,8,11,3,7,4,4,13,6,7,13,8,12,4,5,7,11~13,8,10,13,5,13,5,5,9,11,10,6,13,6,10,11,9,13,11,11,6,3,8,10,10,7,5,3,12,7,10,4,4,12,11,8,11,13,12,4,6,8,11,6,6,6,3,8,12,7,7,13,4,6,9,3,12,4,9,9,12,11,7,8,10,5,4,12,3,13,7,11,6,9,8,7,3,9,10,5,9,7,4,12,10,9,8&t=243&reel_set1=9,8,10,5,10,13,5,7,7,13,8,12,12,5,4,7,3,4,9,8,5,12,3,6,4,11,13,4,8,13,3,9,7,11,13,7,6,11,7,12,9,13,3,8,8,6,12,13,13,4,11,7,8,11,10,3,6,12,10,12,5,5,6,6,11,10,6,10,13,9,10,7,12,8,12,11,9,11,8,9,13,12,5,9,6,10,4~4,6,9,1,11,11,13,2,7,8,12,6,10,2,3,13,7,11,9,3,9,12,8,6,6,5,5,3,3,7,11,13,8,1,5,8,9,4,6,10,4,13,13,1,5,10,3,6,9,9,11,1,10,6,4,12,2,6,6,4,9,13,8,1,3,10,12,12,11,5,3,12,13,13,13,2,4,12,7,7,10,13,9,2,11,5,5,9,8,1,6,7,11,2,10,12,10,8,1,7,4,13,12,7,8~4,12,9,13,11,6,2,7,4,5,8,3,12,9,1,8,11,8,10,11,2,12,4,3,10,11,12,5,1,8,6,4,13,11,11,2,6,8,3,7,10,5,5,3,10,10,7,6,4,1,3,6,7,10,9,13,1,12,5,12,2,8,5,12,11,9,2,6,7,13,13,4,6,6,2,7,13,12,3,6,7,10,8,1,5,10,8,7,7,13,9,9,13,11,1,9,9,10,11~13,6,13,7,10,6,1,8,9,6,10,2,12,13,10,8,11,2,12,6,5,13,7,2,5,9,5,4,1,11,12,12,4,7,4,6,8,8,1,9,13,13,6,3,10,5,10,7,3,8,4,9,3,4,6,12,1,8,12,7,7,3,10,10,9,9,11,2,12,10,5,9,2,13,11,8,11,2,13,9,5,3,12,9,8,11,3,7,4,4,13,6,7,13,8,12,4,5,7,11~13,8,10,13,5,13,5,5,9,11,10,6,13,6,10,11,9,13,11,11,6,3,8,10,10,7,5,3,12,7,10,4,4,12,11,8,11,13,12,4,6,8,11,6,6,6,3,8,12,7,7,13,4,6,9,3,12,4,9,9,12,11,7,8,10,5,4,12,3,13,7,11,6,9,8,7,3,9,10,5,9,7,4,12,10,9,8&reel_set4=9,8,10,5,10,13,5,7,7,13,8,12,12,5,4,7,3,4,9,8,5,12,3,6,4,11,13,4,8,13,3,9,7,11,13,7,6,11,7,12,9,13,3,8,8,6,12,13,13,4,11,7,8,11,10,3,6,12,10,12,5,5,6,6,11,10,6,10,13,9,10,7,12,8,12,11,9,11,8,9,13,12,5,9,6,10,4~4,6,9,1,11,11,13,2,7,8,12,6,10,2,3,13,7,11,9,3,9,12,8,6,6,5,5,3,3,7,11,13,8,1,5,8,9,4,6,10,4,13,13,1,5,10,3,6,9,9,11,1,10,6,4,12,2,6,6,4,9,13,8,1,3,10,12,12,11,5,3,12,13,13,2,4,12,7,7,10,13,9,2,11,5,5,9,8,1,6,7,11,2,10,12,10,8,1,7,4,13,12,7,8~4,12,9,13,11,6,2,7,4,5,8,3,12,9,1,8,11,8,10,11,2,12,4,3,10,11,12,5,1,8,6,4,13,11,11,2,6,8,3,7,10,5,5,3,10,10,7,6,4,1,3,6,7,10,9,13,1,12,5,12,2,8,5,12,11,9,2,6,7,13,13,4,6,6,2,7,13,12,3,6,7,10,8,1,5,10,8,7,7,13,9,9,13,11,1,9,9,10,11~13,6,13,7,10,6,1,8,9,6,10,2,12,13,10,8,11,2,12,6,5,13,7,2,5,9,5,4,1,11,12,12,4,7,4,6,8,8,1,9,13,13,6,3,10,5,10,7,3,8,4,9,3,4,6,12,1,8,12,7,7,3,10,10,9,9,11,2,12,10,5,9,2,13,11,8,11,2,13,9,5,3,12,9,8,11,3,7,4,4,13,6,7,13,8,12,4,5,7,11~13,8,10,13,5,13,5,5,9,11,10,6,13,6,10,11,9,13,11,11,6,3,8,10,10,7,5,3,12,7,10,4,4,12,11,8,11,13,12,4,6,8,11,6,6,3,8,12,7,7,13,4,6,9,3,12,4,9,9,12,11,7,8,10,5,4,12,3,13,7,11,6,9,8,7,3,9,10,5,9,7,4,12,10,9,8&reel_set3=9,8,10,5,10,13,5,7,7,13,8,12,12,5,4,7,3,4,9,8,5,12,3,6,4,11,13,4,8,13,3,9,7,11,13,7,6,11,7,12,9,13,3,8,8,6,12,13,13,4,11,7,8,11,10,3,6,12,10,12,5,5,6,6,11,10,6,10,13,9,10,7,12,8,12,11,9,11,8,9,13,12,5,9,6,10,4~4,6,9,1,11,11,13,2,7,8,12,6,10,2,3,13,7,11,9,3,9,12,8,6,6,5,5,3,3,7,11,13,8,1,5,8,9,4,6,10,4,13,13,1,5,10,3,6,9,9,11,1,10,6,4,12,2,6,6,4,9,13,8,1,3,10,12,12,11,5,3,12,13,13,13,2,4,12,7,7,10,13,9,2,11,5,5,9,8,1,6,7,11,2,10,12,10,8,1,7,4,13,12,7,8~4,12,9,13,11,6,2,7,4,5,8,3,12,9,1,8,11,8,10,11,2,12,4,3,10,11,12,5,1,8,6,4,13,11,11,2,6,8,3,7,10,5,5,3,10,10,7,6,4,1,3,6,7,10,9,13,1,12,5,12,2,8,5,12,11,9,2,6,7,13,13,4,6,6,2,7,13,12,3,6,7,10,8,1,5,10,8,7,7,13,9,9,13,11,1,9,9,10,11~13,6,13,7,10,6,1,8,9,6,10,2,12,13,10,8,11,2,12,6,5,13,7,2,5,9,5,4,1,11,12,12,4,7,4,6,8,8,1,9,13,13,6,3,10,5,10,7,3,8,4,9,3,4,6,12,1,8,12,7,7,3,10,10,9,9,11,2,12,10,5,9,2,13,11,8,11,2,13,9,5,3,12,9,8,11,3,7,4,4,13,6,7,13,8,12,4,5,7,11~13,8,10,13,5,13,5,5,9,11,10,6,13,6,10,11,9,13,11,11,6,3,8,10,10,7,5,3,12,7,10,4,4,12,11,8,11,13,12,4,6,8,11,6,6,6,3,8,12,7,7,13,4,6,9,3,12,4,9,9,12,11,7,8,10,5,4,12,3,13,7,11,6,9,8,7,3,9,10,5,9,7,4,12,10,9,8&reel_set6=9,8,10,5,10,13,5,7,7,13,8,12,12,5,4,7,3,4,9,8,5,12,3,6,4,11,13,4,8,13,3,9,7,11,13,7,6,11,7,12,9,13,3,8,8,6,12,13,13,4,11,7,8,11,10,3,6,12,10,12,5,5,6,6,11,10,6,10,13,9,10,7,12,8,12,11,9,11,8,9,13,12,5,9,6,10,4~4,6,9,1,11,11,13,2,7,8,12,6,10,2,3,13,7,11,9,3,9,12,8,6,6,5,5,3,3,7,11,13,8,1,5,8,9,4,6,10,4,13,13,1,5,10,3,6,9,9,11,1,10,6,4,12,2,6,6,4,9,13,8,1,3,10,12,12,11,5,3,12,13,13,2,4,12,7,7,10,13,9,2,11,5,5,9,8,1,6,7,11,2,10,12,10,8,1,7,4,13,12,7,8~4,12,9,13,11,6,2,7,4,5,8,3,12,9,1,8,11,8,10,11,2,12,4,3,10,11,12,5,1,8,6,4,13,11,11,2,6,8,3,7,10,5,5,3,10,10,7,6,4,1,3,6,7,10,9,13,1,12,5,12,2,8,5,12,11,9,2,6,7,13,13,4,6,6,2,7,13,12,3,6,7,10,8,1,5,10,8,7,7,13,9,9,13,11,1,9,9,10,11~13,6,13,7,10,6,1,8,9,6,10,2,12,13,10,8,11,2,12,6,5,13,7,2,5,9,5,4,1,11,12,12,4,7,4,6,8,8,1,9,13,13,6,3,10,5,10,7,3,8,4,9,3,4,6,12,1,8,12,7,7,3,10,10,9,9,11,2,12,10,5,9,2,13,11,8,11,2,13,9,5,3,12,9,8,11,3,7,4,4,13,6,7,13,8,12,4,5,7,11~13,8,10,13,5,13,5,5,9,11,10,6,13,6,10,11,9,13,11,11,6,3,8,10,10,7,5,3,12,7,10,4,4,12,11,8,11,13,12,4,6,8,11,6,6,3,8,12,7,7,13,4,6,9,3,12,4,9,9,12,11,7,8,10,5,4,12,3,13,7,11,6,9,8,7,3,9,10,5,9,7,4,12,10,9,8&reel_set5=9,8,10,5,10,13,5,7,7,13,8,12,12,5,4,7,3,4,9,8,5,12,3,6,4,11,13,4,8,13,3,9,7,11,13,7,6,11,7,12,9,13,3,8,8,6,12,13,13,4,11,7,8,11,10,3,6,12,10,12,5,5,6,6,11,10,6,10,13,9,10,7,12,8,12,11,9,11,8,9,13,12,5,9,6,10,4~4,6,9,1,11,11,13,2,7,8,12,6,10,2,3,13,7,11,9,3,9,12,8,6,6,5,5,3,3,7,11,13,8,1,5,8,9,4,6,10,4,13,13,1,5,10,3,6,9,9,11,1,10,6,4,12,2,6,6,4,9,13,8,1,3,10,12,12,11,5,3,12,13,13,2,4,12,7,7,10,13,9,2,11,5,5,9,8,1,6,7,11,2,10,12,10,8,1,7,4,13,12,7,8~4,12,9,13,11,6,2,7,4,5,8,3,12,9,1,8,11,8,10,11,2,12,4,3,10,11,12,5,1,8,6,4,13,11,11,2,6,8,3,7,10,5,5,3,10,10,7,6,4,1,3,6,7,10,9,13,1,12,5,12,2,8,5,12,11,9,2,6,7,13,13,4,6,6,2,7,13,12,3,6,7,10,8,1,5,10,8,7,7,13,9,9,13,11,1,9,9,10,11~13,6,13,7,10,6,1,8,9,6,10,2,12,13,10,8,11,2,12,6,5,13,7,2,5,9,5,4,1,11,12,12,4,7,4,6,8,8,1,9,13,13,6,3,10,5,10,7,3,8,4,9,3,4,6,12,1,8,12,7,7,3,10,10,9,9,11,2,12,10,5,9,2,13,11,8,11,2,13,9,5,3,12,9,8,11,3,7,4,4,13,6,7,13,8,12,4,5,7,11~13,8,10,13,5,13,5,5,9,11,10,6,13,6,10,11,9,13,11,11,6,3,8,10,10,7,5,3,12,7,10,4,4,12,11,8,11,13,12,4,6,8,11,6,6,3,8,12,7,7,13,4,6,9,3,12,4,9,9,12,11,7,8,10,5,4,12,3,13,7,11,6,9,8,7,3,9,10,5,9,7,4,12,10,9,8&reel_set7=9,8,10,5,10,13,5,7,7,13,8,12,12,5,4,7,3,4,9,8,5,12,3,6,4,11,13,4,8,13,3,9,7,11,13,7,6,11,7,12,9,13,3,8,8,6,12,13,13,4,11,7,8,11,10,3,6,12,10,12,5,5,6,6,11,10,6,10,13,9,10,7,12,8,12,11,9,11,8,9,13,12,5,9,6,10,4~4,6,9,1,11,11,13,2,7,8,12,6,10,2,3,13,7,11,9,3,9,12,8,6,6,5,5,3,3,7,11,13,8,1,5,8,9,4,6,10,4,13,13,1,5,10,3,6,9,9,11,1,10,6,4,12,2,6,6,4,9,13,8,1,3,10,12,12,11,5,3,12,13,13,2,4,12,7,7,10,13,9,2,11,5,5,9,8,1,6,7,11,2,10,12,10,8,1,7,4,13,12,7,8~4,12,9,13,11,6,2,7,4,5,8,3,12,9,1,8,11,8,10,11,2,12,4,3,10,11,12,5,1,8,6,4,13,11,11,2,6,8,3,7,10,5,5,3,10,10,7,6,4,1,3,6,7,10,9,13,1,12,5,12,2,8,5,12,11,9,2,6,7,13,13,4,6,6,2,7,13,12,3,6,7,10,8,1,5,10,8,7,7,13,9,9,13,11,1,9,9,10,11~13,6,13,7,10,6,1,8,9,6,10,2,12,13,10,8,11,2,12,6,5,13,7,2,5,9,5,4,1,11,12,12,4,7,4,6,8,8,1,9,13,13,6,3,10,5,10,7,3,8,4,9,3,4,6,12,1,8,12,7,7,3,10,10,9,9,11,2,12,10,5,9,2,13,11,8,11,2,13,9,5,3,12,9,8,11,3,7,4,4,13,6,7,13,8,12,4,5,7,11~13,8,10,13,5,13,5,5,9,11,10,6,13,6,10,11,9,13,11,11,6,3,8,10,10,7,5,3,12,7,10,4,4,12,11,8,11,13,12,4,6,8,11,6,6,3,8,12,7,7,13,4,6,9,3,12,4,9,9,12,11,7,8,10,5,4,12,3,13,7,11,6,9,8,7,3,9,10,5,9,7,4,12,10,9,8&awt=rsf';
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
                $lines = 18;      
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
                $slotEvent['slotLines'] = 18;
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
                $allBet = $betline * $lines;
                $tumbAndFreeStacks = []; 
                $isGeneratedFreeStack = false;
                $slotSettings->SetGameData($slotSettings->slotId . 'Wins', []);
                $slotSettings->SetGameData($slotSettings->slotId . 'Status', []);
                $slotSettings->SetGameData($slotSettings->slotId . 'Gri', '');

                if($slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                }
                else
                {
                    $slotEvent['slotEvent'] = 'bet';
                    $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                    $_sum = $allBet / 100 * $slotSettings->GetPercent();
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $_sum, $slotEvent['slotEvent']);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeSpinType', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'SpinType', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FscSessions', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FscWinTotal', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FscTotal', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '4174458' . substr($roundstr, 4, 10);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $leftFreeGames = 0;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
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
                $currentReelSet = 0;
                $aw = -1;
                $str_gri = '';
                $bgid = -1;
                $bgt = 0;
                $bw = 0;
                $wp = -1;
                $end = -1;
                $level = -1;
                $lifes = -1;
                $str_fs_opt_mask = '';
                $str_fs_opt = '';
                $str_wrlm_cs = '';
                $str_wrlm_res = '';
                $str_wins = '';
                $str_wins_mask = '';
                $str_status = '';
                $str_bg_i_mask = '';
                $str_bg_i = '';
                $str_win_line = '';
                $fscSessions = $slotSettings->GetGameData($slotSettings->slotId . 'FscSessions');
                if($slotEvent['slotEvent'] == 'freespin'){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $aw = $stack['aw'];
                    $str_gri = $stack['gri'];                  
                    $bgid = $stack['bgid'];
                    $bgt = $stack['bgt'];
                    $bw = $stack['bw'];
                    $wp = $stack['wp'];
                    $end = $stack['end'];
                    $level = $stack['level'];
                    $lifes = $stack['lifes'];
                    $str_fs_opt = $stack['fs_opt'];
                    $str_fs_opt_mask = $stack['fs_opt_mask'];
                    $str_wrlm_cs = $stack['wrlm_cs'];
                    $str_wrlm_res = $stack['wrlm_res'];
                    $str_wins = $stack['wins'];
                    $str_wins_mask = $stack['wins_mask'];
                    $str_status = $stack['wins_status'];
                    $str_bg_i = $stack['bg_i'];
                    $str_bg_i_mask = $stack['bg_i_mask'];
                    $str_win_line = $stack['win_line'];
                }else{
    
                    // $winType = 'bonus';
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $lastReel = explode(',', $stack[0]['reel']);
                    $aw = $stack[0]['aw'];
                    $str_gri = $stack[0]['gri'];                  
                    $bgid = $stack[0]['bgid'];
                    $bgt = $stack[0]['bgt'];
                    $bw = $stack[0]['bw'];
                    $wp = $stack[0]['wp'];
                    $end = $stack[0]['end'];
                    $level = $stack[0]['level'];
                    $lifes = $stack[0]['lifes'];
                    $str_fs_opt = $stack[0]['fs_opt'];
                    $str_fs_opt_mask = $stack[0]['fs_opt_mask'];
                    $str_wrlm_cs = $stack[0]['wrlm_cs'];
                    $str_wrlm_res = $stack[0]['wrlm_res'];
                    $str_wins = $stack[0]['wins'];
                    $str_wins_mask = $stack[0]['wins_mask'];
                    $str_status = $stack[0]['wins_status'];
                    $str_bg_i = $stack[0]['bg_i'];
                    $str_bg_i_mask = $stack[0]['bg_i_mask'];
                    $str_win_line = $stack[0]['win_line'];
                }
                $reels = [];
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;
                for($i = 0; $i < 15; $i++){
                    if($lastReel[$i] == 1){
                        $scatterCount++;
                    }
                }
                if($str_win_line != ''){
                    $arr_lines = explode('&', $str_win_line);
                    for($k = 0; $k < count($arr_lines); $k++){
                        $arr_sub_lines = explode('~', $arr_lines[$k]);
                        $arr_sub_lines[1] = str_replace(',', '', $arr_sub_lines[1]) / $original_bet * $betline;
                        $totalWin = $totalWin + $arr_sub_lines[1];
                        $arr_lines[$k] = implode('~', $arr_sub_lines);
                    }
                    $str_win_line = implode('&', $arr_lines);
                } 
                $jackpotWin = 0;
                if($bgt == 33 && $bw == 1 && $end == 1 && $wp > 0){
                    $jackpotWin = $wp * $betline * $lines;
                    $totalWin = $totalWin + $jackpotWin;
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
                // if($scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin'){
                //     $freespins = [0,0,0,10,12,15];
                //     $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespins[$scatterCount]);
                //     $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                //     $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                // }
                // if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                //     $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                // }
                if( $scatterCount >= 3 ) 
                {
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                    {
                        array_push($fscSessions, $scatterCount);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FscSessions', $fscSessions);
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
                    $spinType = 's';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $fscWinTotal = $slotSettings->GetGameData($slotSettings->slotId . 'FscWinTotal');
                    $fscTotal = $slotSettings->GetGameData($slotSettings->slotId . 'FscTotal');
                    $fscMuls = [];
                    for($k = 0; $k < count($fscWinTotal); $k++){
                        $fscMuls[] = 1;
                    }
                    $isEnd = false;
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                    {
                        array_push($fscWinTotal, $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin'));
                        array_push($fscTotal, $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames'));
                        array_push($fscMuls, 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FscWinTotal', $fscWinTotal);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FscTotal', $fscTotal);
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsend_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        if(count($fscSessions) > 0){
                            $isState = false;
                            $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                            $strOtherResponse = $strOtherResponse . '&fsdss=1';
                            $slotSettings->SetGameData($slotSettings->slotId . 'SpinType', $fscSessions[0]);
                            array_shift($fscSessions);
                            $slotSettings->SetGameData($slotSettings->slotId . 'FscSessions', $fscSessions);
                            $spinType = 'fso';
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
                    
                    if(count($fscSessions) > 0){
                        $strOtherResponse = $strOtherResponse . '&fsc_sessions=' . implode(',', $fscSessions);
                    }
                    if(count($fscWinTotal) > 0){
                        $strOtherResponse = $strOtherResponse . '&fsc_win_total='. implode(',', $fscWinTotal).'&fsc_res_total='. implode(',', $fscWinTotal) .'&fsc_mul_total='. implode(',', $fscMuls) .'&fsc_total=' . implode(',', $fscTotal);
                    }
                    if($scatterCount >= 3 ){
                        $strOtherResponse = $strOtherResponse . '&fsc_sw=1';
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeSpinType') >= 0){
                        $strOtherResponse = $strOtherResponse . '&fsopt_i=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeSpinType');
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    if($scatterCount >=3){
                        $isState = false;
                        $spinType = 'fso';
                        $slotSettings->SetGameData($slotSettings->slotId . 'SpinType', $scatterCount);
                    }
                }
                if($aw >= 0){
                    $strOtherResponse = $strOtherResponse . '&aw=' . $aw . '&awt=rsf';
                }
                if($str_gri != ''){
                    $strOtherResponse = $strOtherResponse . '&gri=' . $str_gri;
                    $slotSettings->SetGameData($slotSettings->slotId . 'Gri', $str_gri);
                }
                if($bgid >= 0){
                    $strOtherResponse = $strOtherResponse . '&bgid=' . $bgid;
                }
                if($bgt > 0){
                    $strOtherResponse = $strOtherResponse . '&bgt=' . $bgt;
                }
                if($bw > 0){
                    $strOtherResponse = $strOtherResponse . '&bw=' . $bw;
                    if($end == 0){
                        $spinType = 'b';
                        $isState = false;
                    }
                }
                if($wp >= 0){
                    $strOtherResponse = $strOtherResponse . '&wp=' . $wp . '&rw=' . $jackpotWin;
                }
                if($end >= 0){
                    $strOtherResponse = $strOtherResponse . '&end=' . $end;
                }
                if($level >= 0){
                    $strOtherResponse = $strOtherResponse . '&level=' . $level;
                }
                if($lifes >= 0){
                    $strOtherResponse = $strOtherResponse . '&lifes=' . $lifes;
                }
                if($spinType == 'fso'){
                    $strOtherResponse = $strOtherResponse . '&fs_opt_mask=fs,m,ts,rm&fs_opt=25,1,2,2;3;5~20,1,2,3;5;8~15,1,2,5;8;10~13,1,2,8;10;15~10,1,2,10;15;30~6,1,2,15;30;40~-1,-1,2,-1';
                }
                if($str_wrlm_cs != ''){
                    $strOtherResponse = $strOtherResponse . '&wrlm_cs=' . $str_wrlm_cs . '&wrlm_res=' . $str_wrlm_res;
                }
                if($str_wins != ''){                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'Wins', explode(',', $str_wins));
                    $slotSettings->SetGameData($slotSettings->slotId . 'Status', explode(',', $str_status));
                    $strOtherResponse = $strOtherResponse . '&wins=' . $str_wins . '&status=' . $str_status . '&wins_mask=' . $str_wins_mask;
                }
                if($str_bg_i != ''){
                    $strOtherResponse = $strOtherResponse . '&bg_i=' . $str_bg_i . '&bg_i_mask=' . $str_bg_i_mask;
                }
                if($str_win_line != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $str_win_line;
                }

                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse  .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&n_reel_set='. $currentReelSet .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&c='.$betline .'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=18&w='.$totalWin.'&s=' . $strLastReel;
                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 && $spinType != 'fso') 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if( $slotEvent['slotEvent'] != 'freespin' && $spinType == 'fso') 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"SpinType":' . $slotSettings->GetGameData($slotSettings->slotId . 'SpinType') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"FreeSpinType":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeSpinType') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"FscSessions":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'FscSessions')) . '","FscWinTotal":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'FscWinTotal')) . '","FscTotal":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'FscTotal'))  . '","Wins":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'Wins'))  . '","Status":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'Status'))  . '","Gri":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Gri'). '","TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
            }else if( $slotEvent['slotEvent'] == 'doFSOption' ){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lines = 18;
                $lastReel = $lastEvent->serverResponse->LastReel; 
                $Balance =  $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                $ind = -1;
                if(isset($slotEvent['ind'])){
                    $ind = $slotEvent['ind'];
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeSpinType', $ind);
                }
                $scatterCount = $slotSettings->GetGameData($slotSettings->slotId . 'SpinType');
                $isState = false;
                $stack = $slotSettings->GetReelStrips('bonus', $betline * $lines , $ind);
                if($stack == null){
                    $response = 'unlogged';
                    exit( $response );
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'SpinType', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);              
                $str_fs_opt = $stack[0]['fs_opt'];
                $str_fs_opt_mask = $stack[0]['fs_opt_mask'];
                $currentReelSet = $stack[0]['reel_set'];
                $aw = $stack[0]['aw'];
                $str_gri = $slotSettings->GetGameData($slotSettings->slotId . 'Gri');

                $arr_fs_opt = explode('~', $str_fs_opt);
                $freespin = explode(',', $arr_fs_opt[$ind]);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespin[0]);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);

                $strOtherResponse = '';
                if($aw >= 0){
                    $strOtherResponse = $strOtherResponse . '&aw=' . $aw . '&awt=rsf';
                }

                $response = 'fsmul=1&fs_opt_mask='. $str_fs_opt_mask .'&balance='.$Balance . $strOtherResponse . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na=s&fswin=0.00&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&fs=1&fs_opt='. $str_fs_opt .'&fsres=0.00&sver=5&n_reel_set='. $currentReelSet .'&counter='. ((int)$slotEvent['counter'] + 1) . '&fsopt_i=' . $ind;
                
                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------
                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"SpinType":' . $slotSettings->GetGameData($slotSettings->slotId . 'SpinType') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"FreeSpinType":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeSpinType') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"FscSessions":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'FscSessions')) . '","FscWinTotal":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'FscWinTotal')) . '","FscTotal":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'FscTotal'))  . '","Wins":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'Wins'))  . '","Status":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'Status'))  . '","Gri":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Gri') . '","TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), 'WheelBonus', $isState);
            }else if( $slotEvent['slotEvent'] == 'doBonus' ){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lines = 18;
                $lastReel = $lastEvent->serverResponse->LastReel; 
                $Balance =  $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                $ind = -1;
                if(isset($slotEvent['ind'])){
                    $ind = $slotEvent['ind'];
                }
                $scatterCount = $slotSettings->GetGameData($slotSettings->slotId . 'SpinType');
                $isState = false;
                $slotSettings->SetGameData($slotSettings->slotId . 'SpinType', 0);
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
                $aw = $stack['aw'];
                $str_gri = $stack['gri'];                  
                $bgid = $stack['bgid'];
                $bgt = $stack['bgt'];
                $bw = $stack['bw'];
                $wp = $stack['wp'];
                $end = $stack['end'];
                $level = $stack['level'];
                $lifes = $stack['lifes'];
                $str_fs_opt = $stack['fs_opt'];
                $str_fs_opt_mask = $stack['fs_opt_mask'];
                $str_wrlm_cs = $stack['wrlm_cs'];
                $str_wrlm_res = $stack['wrlm_res'];
                $str_wins = $stack['wins'];
                $str_wins_mask = $stack['wins_mask'];
                $str_status = $stack['wins_status'];
                $str_bg_i = $stack['bg_i'];
                $str_bg_i_mask = $stack['bg_i_mask'];
                $str_win_line = $stack['win_line'];
                $arr_wins = explode(',', $str_wins);
                $arr_status = explode(',', $str_status);
                $arr_wins_mask = explode(',', $str_wins_mask);
                $isState = false;
                $spinType = 'b';
                $coef = $betline * $lines;
                $totalWin = 0;
                $bonusType = '';
                $strOtherResponse = '';
                $fscSessions = $slotSettings->GetGameData($slotSettings->slotId . 'FscSessions');
                if($bgt == 21){
                    $new_wins = [1,1];
                    $new_status = [0,0];
                    $new_status[$ind] = 1;
                    $new_wins_mask = [];
                    $bonusType = $arr_wins_mask[0];
                    if($ind == 1){
                        $new_wins_mask[0] = $arr_wins_mask[1];
                        $new_wins_mask[1] = $arr_wins_mask[0];
                    }else{
                        $new_wins_mask[0] = $arr_wins_mask[0];
                        $new_wins_mask[1] = $arr_wins_mask[1];
                    }
                    if($bonusType == 'fss'){
                        $isState = false;
                        $spinType = 'fso';
                        if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                        {
                            $spinType = 's';
                            array_push($fscSessions, 3);
                            $slotSettings->SetGameData($slotSettings->slotId . 'FscSessions', $fscSessions);
                        }else{                            
                            $slotSettings->SetGameData($slotSettings->slotId . 'SpinType', 3);
                        }
                    }else{
                        $slotSettings->SetGameData($slotSettings->slotId . 'Wins', [0,0,0,0,0,0,0,0,0]);
                        $slotSettings->SetGameData($slotSettings->slotId . 'Status', [0,0,0,0,0,0,0,0,0]);
                    }
                }else{
                    $new_wins = $slotSettings->GetGameData($slotSettings->slotId . 'Wins');
                    $new_status = $slotSettings->GetGameData($slotSettings->slotId . 'Status');
                    $new_wins_mask = [];
                    if($ind > -1){
                        $new_wins[$ind] = $arr_wins[$level - 1];
                        $new_status[$ind] = $arr_status[$level - 1];
                    }else{
                        $new_wins = [0,0,0,0,0,0,0,0,0];
                        $new_status = [0,0,0,0,0,0,0,0,0];
                    }
                    for($k = 0; $k < 9; $k++){
                        if($new_status[$k] > 0){
                            $new_wins_mask[$k] = 'pw';
                        }else{
                            $new_wins_mask[$k] = 'h';
                        }
                    }
                    if($end == 1){
                        $totalWin = $wp * $coef;
                        if($totalWin > 0){
                            $spinType = 'cb';
                            $slotSettings->SetBalance($totalWin);
                            $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                            $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                            $isState = true;
                        }
                    }
                }
                if($end == 1 && $bonusType != 'stp'){
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                    {
                        $fscWinTotal = $slotSettings->GetGameData($slotSettings->slotId . 'FscWinTotal');
                        $fscTotal = $slotSettings->GetGameData($slotSettings->slotId . 'FscTotal');
                        $fscMuls = [];
                        for($k = 0; $k < count($fscWinTotal); $k++){
                            $fscMuls[] = 1;
                        }
                        if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                        {
                            array_push($fscWinTotal, $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin'));
                            array_push($fscTotal, $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames'));
                            array_push($fscMuls, 1);
                            $slotSettings->SetGameData($slotSettings->slotId . 'FscWinTotal', $fscWinTotal);
                            $slotSettings->SetGameData($slotSettings->slotId . 'FscTotal', $fscTotal);
                            $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsend_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                            if(count($fscSessions) > 0){
                                $isState = false;
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                                $strOtherResponse = $strOtherResponse . '&fsdss=1';
                                $slotSettings->SetGameData($slotSettings->slotId . 'SpinType', $fscSessions[0]);
                                array_shift($fscSessions);
                                $slotSettings->SetGameData($slotSettings->slotId . 'FscSessions', $fscSessions);
                                $spinType = 'fso';
                            }else if($bgt == 18){
                                $spinType = 'cb';
                            }
                        }
                        else
                        {
                            $isState = false;
                            $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                            if($bgt == 18){
                                $spinType = 's';
                            }
                        }
                        
                        if(count($fscSessions) > 0){
                            $strOtherResponse = $strOtherResponse . '&fsc_sessions=' . implode(',', $fscSessions);
                        }
                        if(count($fscWinTotal) > 0){
                            $strOtherResponse = $strOtherResponse . '&fsc_win_total='. implode(',', $fscWinTotal).'&fsc_res_total='. implode(',', $fscWinTotal) .'&fsc_mul_total='. implode(',', $fscMuls) .'&fsc_total=' . implode(',', $fscTotal);
                        }
                        if($slotSettings->GetGameData($slotSettings->slotId . 'FreeSpinType') >= 0){
                            $strOtherResponse = $strOtherResponse . '&fsopt_i=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeSpinType');
                        }
                    }
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }
                
                $slotSettings->SetGameData($slotSettings->slotId . 'Wins', $new_wins);
                $slotSettings->SetGameData($slotSettings->slotId . 'Status', $new_status);

                if($aw >= 0){
                    $strOtherResponse = $strOtherResponse . '&aw=' . $aw . '&awt=rsf';
                }
                if($str_gri != ''){
                    $strOtherResponse = $strOtherResponse . '&gri=' . $str_gri;
                    $slotSettings->SetGameData($slotSettings->slotId . 'Gri', $str_gri);
                }
                if($bgid >= 0){
                    $strOtherResponse = $strOtherResponse . '&bgid=' . $bgid;
                }
                if($bgt > 0){
                    $strOtherResponse = $strOtherResponse . '&bgt=' . $bgt;
                }
                if($bw > 0){
                    $strOtherResponse = $strOtherResponse . '&bw=' . $bw;
                }
                if($wp >= 0){
                    $strOtherResponse = $strOtherResponse . '&wp=' . $wp . '&rw=' . $totalWin;
                }
                if($end >= 0){
                    $strOtherResponse = $strOtherResponse . '&end=' . $end;
                }
                if($level >= 0){
                    $strOtherResponse = $strOtherResponse . '&level=' . $level;
                }
                if($lifes >= 0){
                    $strOtherResponse = $strOtherResponse . '&lifes=' . $lifes;
                }
                if($str_fs_opt != ''){
                    $strOtherResponse = $strOtherResponse . '&fs_opt_mask=' . $str_fs_opt_mask . '&fs_opt=' . $str_fs_opt;
                }
                if($str_wrlm_cs != ''){
                    $strOtherResponse = $strOtherResponse . '&wrlm_cs=' . $str_wrlm_cs . '&wrlm_res=' . $str_wrlm_res;
                }
                if(count($new_wins) > 0){                    
                    $strOtherResponse = $strOtherResponse . '&wins=' . implode(',', $new_wins) . '&status=' . implode(',', $new_status) . '&wins_mask=' . implode(',', $new_wins_mask);
                }
                if($str_bg_i != ''){
                    $strOtherResponse = $strOtherResponse . '&bg_i=' . $str_bg_i . '&bg_i_mask=' . $str_bg_i_mask;
                }



                $response = 'balance='.$Balance . '&coef=' . $coef . $strOtherResponse . '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='. $spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sver=5&counter='. ((int)$slotEvent['counter'] + 1);
                
                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------
                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"SpinType":' . $slotSettings->GetGameData($slotSettings->slotId . 'SpinType') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"FreeSpinType":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeSpinType') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"FscSessions":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'FscSessions')) . '","FscWinTotal":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'FscWinTotal')) . '","FscTotal":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'FscTotal'))  . '","Wins":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'Wins'))  . '","Status":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'Status'))  . '","Gri":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Gri') . '","TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), 'WheelBonus', $isState);
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
        
        public function findZokbos($reels, $firstSymbol, $repeatCount, $positions, $reelndex){
            $wild = '2';
            $bPathEnded = true;
            if($reelndex < 6){
                for($r = 0; $r < 7; $r++){
                    if($firstSymbol == $reels[$reelndex][$r] || $reels[$reelndex][$r] == $wild){
                        $this->findZokbos($reels, $firstSymbol, $repeatCount + 1, array_merge($positions, [($reelndex + $r * 6)]), $reelndex + 1);
                        $bPathEnded = false;
                    }
                }
            }
            if($bPathEnded == true){
                if($repeatCount >= 3){
                    $winLine = [];
                    $winLine['FirstSymbol'] = $firstSymbol;
                    $winLine['RepeatCount'] = $repeatCount;
                    $winLine['Positions'] = $positions;
                    $winLine['Mul'] = 1;
                    array_push($this->winLines, $winLine);
                }
            }
        }
    }
}

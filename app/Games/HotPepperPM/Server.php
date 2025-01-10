<?php 
namespace VanguardLTE\Games\HotPepperPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [5,6,5,3,9,6,9,8,7,9,5,3,6,6,8,4,5,6,7,8,9,5,6,5,3,9,6,9,8,7,9,5,3,6,6,8,4,5,6,7,8,9,7,3,9,6,9,3,5]);
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
                $rs_p = -1;
                if(isset($stack)){
                    $str_initReel = $stack['is'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $rs_more = $stack['rs_more'];
                    $str_trail = $stack['trail'];
                    $str_stf = $stack['stf'];
                    $str_s_mark = $stack['s_mark'];
                    $str_accv = $stack['accv'];
                    $str_accm = $stack['accm'];
                    $str_acci = $stack['acci'];
                    $str_slm_mp = $stack['slm_mp'];
                    $str_slm_mv = $stack['slm_mv'];
                    $str_lmi = $stack['lmi'];
                    $str_lmv = $stack['lmv'];
                    $fsmore = $stack['fsmore'];
                    $fsmax = $stack['fsmax'];
                    $str_rs_iw = $stack['rs_iw'];
                    $strWinLine = $stack['win_line'];
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    if($rs_p >= 0){
                        $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p . '&rs_c='. $rs_c .'&rs_m=' . $rs_m;
                    }
                    else if($rs_t > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_t='.$rs_t.'&rs_win='.($slotSettings->GetGameData($slotSettings->slotId . 'TumbWin'));
                    }
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($str_trail != ''){
                        $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                    }
                    if($str_stf != ''){
                        $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                    }
                    if($str_s_mark != ''){
                        $strOtherResponse = $strOtherResponse . '&s_mark=' . $str_s_mark;
                    }
                    if($str_accv != ''){
                        $strOtherResponse = $strOtherResponse . '&accv=' . $str_accv . '&accm=' . $str_accm . '&acci=' . $str_acci;
                    }
                    if($str_slm_mv != ''){
                        $strOtherResponse = $strOtherResponse . '&slm_mp=' . $str_slm_mp . '&slm_mv=' . $str_slm_mv;
                    }
                    if($str_lmv != ''){
                        $strOtherResponse = $strOtherResponse . '&lmv=' . $str_lmv . '&lmi=' . $str_lmi;
                    }
                    if($str_rs_iw != ''){
                        $str_rs_iw = str_replace(',', '', $str_rs_iw) / $original_bet * $bet;
                        $strOtherResponse = $strOtherResponse . '&rs_iw=' . $str_rs_iw;
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
                        if($fsmore > 0){
                            $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                        }
                    }
                }
                    
                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=5,6,5,3,9,6,9,8,7,9,5,3,6,6,8,4,5,6,7,8,9,5,6,5,3,9,6,9,8,7,9,5,3,6,6,8,4,5,6,7,8,9,7,3,9,6,9,3,5&balance='. $Balance .'&cfgs=6802&ver=3&index=1&balance_cash='. $Balance .'&def_sb=3,3,8,4,6,2,8&reel_set_size=3&def_sa=2,6,4,6,4,3,8&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0~0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0~1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"107142857",max_rnd_win:"10000"}}&wl_i=tbm~10000&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=2,6,4,6,4,3,8&sb=3,3,8,4,6,2,8&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=50.00&purInit_e=1&sh=7&wilds=2~0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0~1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1;11~1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,500,500,200,200,100,80,60,50,40,20,0,0,0,0~1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1;12~1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,500,500,200,200,100,80,60,50,40,20,0,0,0,0~1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1;13~1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,500,500,200,200,100,80,60,50,40,20,0,0,0,0~1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1;14~1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,500,500,200,200,100,80,60,50,40,20,0,0,0,0~1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1;15~1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,500,500,200,200,100,80,60,50,40,20,0,0,0,0~1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1&bonuses=0&st=rect&c='.$bet.'&sw=7&sver=5&counter=2&paytable=0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,500,500,200,200,100,80,60,50,40,20,0,0,0,0;500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,300,300,100,100,80,60,50,40,20,8,0,0,0,0;500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,300,300,100,100,80,60,50,40,20,8,0,0,0,0;400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,200,200,80,80,40,30,20,8,6,4,0,0,0,0;400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,200,200,80,80,40,30,20,8,6,4,0,0,0,0;200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,100,100,40,40,30,20,8,6,4,2,0,0,0,0;200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,100,100,40,40,30,20,8,6,4,2,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0&l=20&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=9,9,9,3,4,8,7,9,8,1,4,5,8,4,7,6,8,4,9,3,4,8,1,7,4,9,4,7,1,4,8,7,6,4,4,8,1,6,4,7,5,1,4,5,7,8,3,1,8,4,10,4,4,4,1,5,5,5,4,7,6,6,6,9,8,1,4,10,5,8,5,6,8,4,9,3,3,8,1,7,4,9,5,7,1,4,6,8,4,8,6,4,4,5,1,4,5,1,4,7,7,7,3,8,8,8,1,4,4,3,3,3,4,1,5,4,7,6,6,6,1,7,8,9,4,7,8,1,4,9,7,8,3,9,8,1,4,7,9,4,7,1~8,6,9,4,5,9,1,7,5,8,9,4,8,7,2,3,8,5,6,9,1,6,9,5,8,4,9,3,4,6,8,4,1,8,7,6,8,9,1,8,4,2,2,2,3,3,1,5,5,5,9,8,1,9,6,7,7,6,2,8,6,9,4,4,4,1,9,6,2,9,8,1,6,9,8,6,1,8,6,3,8,9,4,8,1,4,8,6,9,1,8,5,9,1,7,3,3,9,4,8,7,3,6,4,8,5,6,9,1,6,9,5,4,9,3,8,6,1,8,4,1,8,9,6,2,4,10,6,8,8,8,1,9,9,9,6,6,6,1~10,6,6,8,1,4,9,6,9,9,2,3,3,1,9,4,9,5,4,4,1,4,5,4,3,9,5,4,9,5,4,4,9,1,5,4,10,5,1,4,5,5,7,6,8,5,9,3,4,9,4,4,3,9,6,4,1,6,4,7,9,1,3,3,7,8,1,4,5,2,5,9,1,4,4,9,4,1,9,7,7,5,5,6,1,3,9,9,5,1,7,5,5,7,6,8,9,2,4,8,1,9,4,4,3,9,6,4,1,6,4,7,9,1,8,5,7,8,1,4,5,5,9,9,4,1,4,4,4,9,4,1,7,7,5,9,7,5,2,2,2~7,4,9,9,9,1,4,10,2,6,8,8,8,6,4,7,6,9,7,6,8,4,7,6,6,6,3,1,8,4,5,1,4,5,7,6,5,3,6,6,1,7,6,1,7,3,3,7,9,1,6,6,7,1,4,4,4,1,7,5,5,5,7,4,9,9,9,1,4,10,2,6,8,8,1,4,7,8,6,8,2,6,6,1,7,4,2,7,4,3,7,6,6,6,3,7,7,7,3,8,4,3,5,4,3,6,6,7,1,6,7,4,4,7,2,2,2,5,9,1,7,6,6,7,1,4,4,4,1,3,3,3~4,8,1,4,4,4,4,9,9,1,6,8,8,1,5,5,5,2,9,7,7,6,9,4,4,1,1,1,8,7,4,1,5,8,3,9,7,5,4,7,1,5,5,7,9,4,8,6,6,1,3,3,3,4,9,9,2,4,7,4,5,5,5,9,7,7,7,1,9,6,6,6,8,8,8,1,9,4,4,1,7,4,9,4,3,3,8,7,1,4,10,4,9,8,7,4,9,1,4,4,7,9,5,3,8,7,4,1,5,8,3,4,1,7,4,4,5,5,2,2,2~4,8,1,4,4,4,4,9,9,1,6,8,8,1,5,5,5,2,9,7,7,6,9,4,4,1,1,1,8,7,4,1,5,8,3,9,7,5,4,7,1,5,5,7,9,4,8,6,6,1,3,3,3,4,9,9,2,4,7,4,5,5,5,9,7,7,7,1,9,6,6,6,8,8,8,1,9,4,4,1,7,4,9,4,3,3,8,7,1,4,10,4,9,8,7,4,9,1,4,4,7,9,5,3,8,7,4,1,5,8,3,4,1,7,4,4,5,5,2,2,2~10,6,6,8,1,4,9,6,9,9,2,3,3,1,9,4,9,5,4,4,1,4,5,4,3,9,5,4,9,5,4,4,9,1,5,4,10,5,1,4,5,5,7,6,8,5,9,3,4,9,4,4,3,9,6,4,1,6,4,7,9,1,3,3,7,8,1,4,5,2,5,9,1,4,4,9,4,1,9,7,7,5,5,6,1,3,9,9,5,1,7,5,5,7,6,8,9,2,4,8,1,9,4,4,3,9,6,4,1,6,4,7,9,1,8,5,7,8,1,4,5,5,9,9,4,1,4,4,4,9,4,1,7,7,5,9,7,5,2,2,2&s='.$lastReelStr.'&accInit=[{id:0,mask:"lvl;cp;tp;bl"},{id:1,mask:"cp;tp"}]&reel_set2=9,9,9,3,4,8,7,9,8,1,4,5,8,4,7,6,8,4,9,3,4,8,1,7,4,9,4,7,1,4,8,7,6,4,4,8,1,6,4,7,5,1,4,5,7,8,3,1,8,4,10,4,4,4,1,5,5,5,4,7,6,6,6,9,8,1,4,10,5,8,5,6,8,4,9,3,3,8,1,7,4,9,5,7,1,4,6,8,4,8,6,4,4,5,1,4,5,1,4,7,7,7,3,8,8,8,1,4,4,3,3,3,4,1,5,4,7,6,6,6,1,7,8,9,4,7,8,1,4,9,7,8,3,9,8,1,4,7,9,4,7,1~8,6,9,4,5,9,1,7,5,8,9,4,8,7,2,3,8,5,6,9,1,6,9,5,8,4,9,3,4,6,8,4,1,8,7,6,8,9,1,8,4,2,2,2,3,3,1,5,5,5,9,8,1,9,6,7,7,6,2,8,6,9,4,4,4,1,9,6,2,9,8,1,6,9,8,6,1,8,6,3,8,9,4,8,1,4,8,6,9,1,8,5,9,1,7,3,3,9,4,8,7,3,6,4,8,5,6,9,1,6,9,5,4,9,3,8,6,1,8,4,1,8,9,6,2,4,10,6,8,8,8,1,9,9,9,6,6,6,1~10,6,6,8,1,4,9,6,9,9,2,3,3,1,9,4,9,5,4,4,1,4,5,4,3,9,5,4,9,5,4,4,9,1,5,4,10,5,1,4,5,5,7,6,8,5,9,3,4,9,4,4,3,9,6,4,1,6,4,7,9,1,3,3,7,8,1,4,5,2,5,9,1,4,4,9,4,1,9,7,7,5,5,6,1,3,9,9,5,1,7,5,5,7,6,8,9,2,4,8,1,9,4,4,3,9,6,4,1,6,4,7,9,1,8,5,7,8,1,4,5,5,9,9,4,1,4,4,4,9,4,1,7,7,5,9,7,5,2,2,2~7,4,9,9,9,1,4,10,2,6,8,8,8,6,4,7,6,9,7,6,8,4,7,6,6,6,3,1,8,4,5,1,4,5,7,6,5,3,6,6,1,7,6,1,7,3,3,7,9,1,6,6,7,1,4,4,4,1,7,5,5,5,7,4,9,9,9,1,4,10,2,6,8,8,1,4,7,8,6,8,2,6,6,1,7,4,2,7,4,3,7,6,6,6,3,7,7,7,3,8,4,3,5,4,3,6,6,7,1,6,7,4,4,7,2,2,2,5,9,1,7,6,6,7,1,4,4,4,1,3,3,3~4,8,1,4,4,4,4,9,9,1,6,8,8,1,5,5,5,2,9,7,7,6,9,4,4,1,1,1,8,7,4,1,5,8,3,9,7,5,4,7,1,5,5,7,9,4,8,6,6,1,3,3,3,4,9,9,2,4,7,4,5,5,5,9,7,7,7,1,9,6,6,6,8,8,8,1,9,4,4,1,7,4,9,4,3,3,8,7,1,4,10,4,9,8,7,4,9,1,4,4,7,9,5,3,8,7,4,1,5,8,3,4,1,7,4,4,5,5,2,2,2~4,8,1,4,4,4,4,9,9,1,6,8,8,1,5,5,5,2,9,7,7,6,9,4,4,1,1,1,8,7,4,1,5,8,3,9,7,5,4,7,1,5,5,7,9,4,8,6,6,1,3,3,3,4,9,9,2,4,7,4,5,5,5,9,7,7,7,1,9,6,6,6,8,8,8,1,9,4,4,1,7,4,9,4,3,3,8,7,1,4,10,4,9,8,7,4,9,1,4,4,7,9,5,3,8,7,4,1,5,8,3,4,1,7,4,4,5,5,2,2,2~10,6,6,8,1,4,9,6,9,9,2,3,3,1,9,4,9,5,4,4,1,4,5,4,3,9,5,4,9,5,4,4,9,1,5,4,10,5,1,4,5,5,7,6,8,5,9,3,4,9,4,4,3,9,6,4,1,6,4,7,9,1,3,3,7,8,1,4,5,2,5,9,1,4,4,9,4,1,9,7,7,5,5,6,1,3,9,9,5,1,7,5,5,7,6,8,9,2,4,8,1,9,4,4,3,9,6,4,1,6,4,7,9,1,8,5,7,8,1,4,5,5,9,9,4,1,4,4,4,9,4,1,7,7,5,9,7,5,2,2,2&reel_set1=9,9,9,3,4,8,7,9,8,1,4,5,8,4,7,6,8,4,9,3,4,8,1,7,4,9,4,7,1,4,8,7,6,4,4,8,1,6,4,7,5,1,4,5,7,8,3,1,8,4,10,4,4,4,1,5,5,5,4,7,6,6,6,9,8,1,4,10,5,8,5,6,8,4,9,3,3,8,1,7,4,9,5,7,1,4,6,8,4,8,6,4,4,5,1,4,5,1,4,7,7,7,3,8,8,8,1,4,4,3,3,3,4,1,5,4,7,6,6,6,1,7,8,9,4,7,8,1,4,9,7,8,3,9,8,1,4,7,9,4,7,1~8,6,9,4,5,9,1,7,5,8,9,4,8,7,2,3,8,5,6,9,1,6,9,5,8,4,9,3,4,6,8,4,1,8,7,6,8,9,1,8,4,2,2,2,3,3,1,5,5,5,9,8,1,9,6,7,7,6,2,8,6,9,4,4,4,1,9,6,2,9,8,1,6,9,8,6,1,8,6,3,8,9,4,8,1,4,8,6,9,1,8,5,9,1,7,3,3,9,4,8,7,3,6,4,8,5,6,9,1,6,9,5,4,9,3,8,6,1,8,4,1,8,9,6,2,4,10,6,8,8,8,1,9,9,9,6,6,6,1~10,6,6,8,1,4,9,6,9,9,2,3,3,1,9,4,9,5,4,4,1,4,5,4,3,9,5,4,9,5,4,4,9,1,5,4,10,5,1,4,5,5,7,6,8,5,9,3,4,9,4,4,3,9,6,4,1,6,4,7,9,1,3,3,7,8,1,4,5,2,5,9,1,4,4,9,4,1,9,7,7,5,5,6,1,3,9,9,5,1,7,5,5,7,6,8,9,2,4,8,1,9,4,4,3,9,6,4,1,6,4,7,9,1,8,5,7,8,1,4,5,5,9,9,4,1,4,4,4,9,4,1,7,7,5,9,7,5,2,2,2~7,4,9,9,9,1,4,10,2,6,8,8,8,6,4,7,6,9,7,6,8,4,7,6,6,6,3,1,8,4,5,1,4,5,7,6,5,3,6,6,1,7,6,1,7,3,3,7,9,1,6,6,7,1,4,4,4,1,7,5,5,5,7,4,9,9,9,1,4,10,2,6,8,8,1,4,7,8,6,8,2,6,6,1,7,4,2,7,4,3,7,6,6,6,3,7,7,7,3,8,4,3,5,4,3,6,6,7,1,6,7,4,4,7,2,2,2,5,9,1,7,6,6,7,1,4,4,4,1,3,3,3~4,8,1,4,4,4,4,9,9,1,6,8,8,1,5,5,5,2,9,7,7,6,9,4,4,1,1,1,8,7,4,1,5,8,3,9,7,5,4,7,1,5,5,7,9,4,8,6,6,1,3,3,3,4,9,9,2,4,7,4,5,5,5,9,7,7,7,1,9,6,6,6,8,8,8,1,9,4,4,1,7,4,9,4,3,3,8,7,1,4,10,4,9,8,7,4,9,1,4,4,7,9,5,3,8,7,4,1,5,8,3,4,1,7,4,4,5,5,2,2,2~4,8,1,4,4,4,4,9,9,1,6,8,8,1,5,5,5,2,9,7,7,6,9,4,4,1,1,1,8,7,4,1,5,8,3,9,7,5,4,7,1,5,5,7,9,4,8,6,6,1,3,3,3,4,9,9,2,4,7,4,5,5,5,9,7,7,7,1,9,6,6,6,8,8,8,1,9,4,4,1,7,4,9,4,3,3,8,7,1,4,10,4,9,8,7,4,9,1,4,4,7,9,5,3,8,7,4,1,5,8,3,4,1,7,4,4,5,5,2,2,2~10,6,6,8,1,4,9,6,9,9,2,3,3,1,9,4,9,5,4,4,1,4,5,4,3,9,5,4,9,5,4,4,9,1,5,4,10,5,1,4,5,5,7,6,8,5,9,3,4,9,4,4,3,9,6,4,1,6,4,7,9,1,3,3,7,8,1,4,5,2,5,9,1,4,4,9,4,1,9,7,7,5,5,6,1,3,9,9,5,1,7,5,5,7,6,8,9,2,4,8,1,9,4,4,3,9,6,4,1,6,4,7,9,1,8,5,7,8,1,4,5,5,9,9,4,1,4,4,4,9,4,1,7,7,5,9,7,5,2,2,2&purInit=[{bet:2000,type:"default"}]&total_bet_min=10.00';
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
                $slotEvent['slotBet'] = $slotEvent['c'];
                $slotEvent['slotLines'] = 20;
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    $slotEvent['slotEvent'] = 'freespin';
                }
                $isTumb = false;
                if($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') >= 0){
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
                    if( $slotEvent['slotEvent'] == 'doSpin' && $slotSettings->GetBalance() < ($lines * $betline)  && $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') == -1) 
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
                    $allBet = $allBet * 100;
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
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', -1);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur);
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
                $this->winLines = [];
                $bonusMpl = 1;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $str_initReel = '';
                $rs_p = -1;
                $rs_m = 0;
                $rs_c = 0;
                $rs_t = 0;
                $rs_more = 0;
                $str_trail = '';
                $str_stf = '';
                $str_accv = '';
                $str_accm = '';
                $str_acci = '';
                $str_s_mark = '';
                $str_slm_mp = '';
                $str_slm_mv = '';
                $str_lmi = '';
                $str_lmv = '';
                $str_sa = '';
                $str_sb = '';
                $fsmax = 0;
                $fsmore = 0;
                $str_rs_iw = '';
                if($slotEvent['slotEvent'] == 'freespin' || $isTumb == true){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_initReel = $stack['is'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $rs_more = $stack['rs_more'];
                    $str_trail = $stack['trail'];
                    $str_stf = $stack['stf'];
                    $str_s_mark = $stack['s_mark'];
                    $str_accv = $stack['accv'];
                    $str_accm = $stack['accm'];
                    $str_acci = $stack['acci'];
                    $str_slm_mp = $stack['slm_mp'];
                    $str_slm_mv = $stack['slm_mv'];
                    $str_lmi = $stack['lmi'];
                    $str_lmv = $stack['lmv'];
                    $fsmore = $stack['fsmore'];
                    $fsmax = $stack['fsmax'];
                    $str_rs_iw = $stack['rs_iw'];
                    $strWinLine = $stack['win_line'];
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
                    $str_initReel = $stack[0]['is'];
                    $rs_p = $stack[0]['rs_p'];
                    $rs_c = $stack[0]['rs_c'];
                    $rs_m = $stack[0]['rs_m'];
                    $rs_t = $stack[0]['rs_t'];
                    $rs_more = $stack[0]['rs_more'];
                    $str_trail = $stack[0]['trail'];
                    $str_stf = $stack[0]['stf'];
                    $str_s_mark = $stack[0]['s_mark'];
                    $str_accv = $stack[0]['accv'];
                    $str_accm = $stack[0]['accm'];
                    $str_acci = $stack[0]['acci'];
                    $str_slm_mp = $stack[0]['slm_mp'];
                    $str_slm_mv = $stack[0]['slm_mv'];
                    $str_lmi = $stack[0]['lmi'];
                    $str_lmv = $stack[0]['lmv'];
                    $fsmore = $stack[0]['fsmore'];
                    $fsmax = $stack[0]['fsmax'];
                    $strWinLine = $stack[0]['win_line'];
                    $str_sa = $stack[0]['sa'];
                    $str_sb = $stack[0]['sb'];
                    $str_rs_iw = $stack[0]['rs_iw'];  
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

                $spinType = 's';
                $isNewTumb = false;
                if($rs_p >= 0){
                    $isNewTumb = true;
                }
                if( $totalWin > 0) 
                {
                    $spinType = 'c';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }
                $_obf_totalWin = $totalWin;
                $isState = true;
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', $rs_p);
                if($isNewTumb == true){
                    $isState = false;
                    $spinType = 's';
                }else{
                    if($fsmax > 0 && $slotEvent['slotEvent'] != 'freespin'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $fsmax);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                    }else if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                    }
                }
                $strLastReel = implode(',', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                if($rs_p > 0){
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') + $totalWin);
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
                        $isEnd = true;
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        if($isNewTumb == true){
                            $strOtherResponse = $strOtherResponse . '&fsend_total=0';
                            $isState = false;
                        }else{
                            $strOtherResponse = $strOtherResponse . '&fsend_total=1';
                            $spinType = 'c';
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
                    if($fsmore > 0){
                        $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    if($isNewTumb == false){
                        if($fsmax > 0){
                            $isState = false;
                            $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1';
                            $spinType = 's';
                        }else{
                            if($slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') > 0){
                                $spinType = 'c';
                            }
                        }
                        if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                            $strOtherResponse = $strOtherResponse . '&purtr=1&puri=' . $pur;
                        }
                    }
                }
                if($rs_p >= 0){
                    if($rs_p == 0){
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                    }else{
                        $strOtherResponse = $strOtherResponse . '&rs_more=1&rs_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin');
                    }
                    $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p . '&rs_c='. $rs_c .'&rs_m=' . $rs_m;
                }
                else if($rs_t > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_t='.$rs_t.'&rs_win=' . ($slotSettings->GetGameData($slotSettings->slotId . 'TumbWin'));
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($str_trail != ''){
                    $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                }
                if($str_stf != ''){
                    $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                }
                if($str_s_mark != ''){
                    $strOtherResponse = $strOtherResponse . '&s_mark=' . $str_s_mark;
                }
                if($str_accv != ''){
                    $strOtherResponse = $strOtherResponse . '&accv=' . $str_accv . '&accm=' . $str_accm . '&acci=' . $str_acci;
                }
                if($str_slm_mv != ''){
                    $strOtherResponse = $strOtherResponse . '&slm_mp=' . $str_slm_mp . '&slm_mv=' . $str_slm_mv;
                }
                if($str_lmv != ''){
                    $strOtherResponse = $strOtherResponse . '&lmv=' . $str_lmv . '&lmi=' . $str_lmi;
                }
                if($str_rs_iw != ''){
                    $str_rs_iw = str_replace(',', '', $str_rs_iw) / $original_bet * $betline;
                    $strOtherResponse = $strOtherResponse . '&rs_iw=' . $str_rs_iw;
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse  .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='. $str_sa .'&sb='. $str_sb .'&st=rect&c='.$betline.'&sh=7&sw=7&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&w='.$totalWin.'&s=' . $strLastReel;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)  && $isNewTumb == false) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if($isNewTumb == false){
                    if( $slotEvent['slotEvent'] != 'freespin' && $fsmax > 0) 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"TumbWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"TumbleState":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState')  . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')  .',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
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

<?php 
namespace VanguardLTE\Games\CandyStarsPM
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
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [11,4,10,7,11,3,6,8,5,6,3,7,10,4,7,4,6,9,3,10]);
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
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $str_slm_mp = $stack['slm_mp'];
                    $str_slm_mv = $stack['slm_mv'];
                    $arr_g = null;
                    if($stack['g'] != ''){
                        $arr_g = $stack['g'];
                    }
                    $fsmore = $stack['fsmore'];
                    $strWinLine = $stack['wlc_v'];
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    if($rs_p >= 0){
                        $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p . '&rs_c='. $rs_c .'&rs_m=' . $rs_m;
                    }
                    else if($rs_t > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_t='.$rs_t.'&rs_win='.($slotSettings->GetGameData($slotSettings->slotId . 'TumbWin'));
                    }
                    if($str_slm_mv != ''){
                        $strOtherResponse = $strOtherResponse . '&slm_mp=' . $str_slm_mp . '&slm_mv=' . $str_slm_mv;
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
                    if($arr_g != null){
                        $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($arr_g));
                    }else{
                        $strOtherResponse = $strOtherResponse . '&g={reg:{def_s:"3,6,8,5,6,3,7,10,4,7,4,6,9,3,10",def_sa:"5,8,10,3,6",def_sb:"5,7,8,5,10",reel_set:"0",s:"3,6,8,5,6,3,7,10,4,7,4,6,9,3,10",sa:"5,8,10,3,6",sb:"5,7,8,5,10",sh:"3",st:"rect",sw:"5"},top:{def_s:"7,10,4",def_sa:"3",def_sb:"5",reel_set:"1",s:"7,10,4",sa:"3",sb:"5",sh:"3",st:"rect",sw:"1"}}';
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
                }else{
                    $strOtherResponse = $strOtherResponse . '&g={reg:{def_s:"3,6,8,5,6,3,7,10,4,7,4,6,9,3,10",def_sa:"5,8,10,3,6",def_sb:"5,7,8,5,10",reel_set:"0",s:"3,6,8,5,6,3,7,10,4,7,4,6,9,3,10",sa:"5,8,10,3,6",sb:"5,7,8,5,10",sh:"3",st:"rect",sw:"5"},top:{def_s:"7,10,4",def_sa:"3",def_sb:"5",reel_set:"1",s:"7,10,4",sa:"3",sb:"5",sh:"3",st:"rect",sw:"1"}}';
                }
                    
                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=11,4,10,7,11,3,6,8,5,6,3,7,10,4,7,4,6,9,3,10&balance='. $Balance .'&cfgs=6564&ver=3&index=1&balance_cash='. $Balance .'&def_sb=5,7,8,5,10&reel_set_size=6&def_sa=5,8,10,3,6&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"2431118",max_rnd_win:"5000"}}&wl_i=tbm~5000&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=5,8,10,3,6&sb=5,7,8,5,10&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=50.00&purInit_e=1&sh=4&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&st=rect&c='.$bet.'&sw=5&sver=5&counter=2&paytable=0,0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;150,60,20,0,0;60,25,10,0,0;50,20,10,0,0;40,20,5,0,0;30,15,5,0,0;30,10,5,0,0;25,8,3,0,0;20,6,3,0,0;0,0,0,0,0&l=20&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=6,9,8,7,10,9,8,1,3,6,8,10,6,8,1,6,6,10,10,8,7,10,10,10,5,6,10,5,6,6,4,6,10,4,8,6,6,10,8,8,10,10,3,8,3,3,3,6,1,6,8,6,8,1,6,10,8,8,3,4,8,6,10,10,4,8,10,6,8,8,3,10,10,6,10,3,10,6,7,6,4,8,10,3,10,8,6,8,6,8,10,8,3,1,10~9,8,7,8,5,5,3,8,3,7,7,9,7,5,8,9,6,5,10,9,7,4,5,5,6,4,7,10,4,7,3,7,7,9,9,7,4,7,5,4,3,3,7,5,10,4,3,3,3,6,9,3,7,6,7,7,5,8,4,3,9,9,3,5,7,4,9,4,5,9,6,9,9,4,9,5,4,4,9,5,5,8,4,4,5,3,5,5,3,8,7,9,3,4,5,7,9,9,9,7,10,4,9,8,5,9,7,7,3,4,9,7,9,5,9,4,4,5,7,7,9,9,7,6,3,10,3,5,7,5,9,7,4,8,9,9,5,7,5,4,4,5,9,8,10,4,4,5~10,7,10,1,6,7,10,7,6,7,9,6,9,6,7,10,10,8,9,10,9,4,3,3,5,10,10,6,9,6,9,9,7,9,10,9,7,3,5,10,9,4,3,3,3,6,10,9,10,4,9,7,6,4,9,10,9,7,10,7,3,10,3,3,6,8,9,1,7,10,10,7,10,10,7,7,4,9,6,1,9,3,10,7,5,8,10,7,9,10,10,10,5,6,9,9,6,9,1,7,1,10,10,3,9,3,10,7,10,6,7,3,10,6,8,3,10,5,6,10,10,7,7,3,4,9,7,8,9,7,7,4,7,6,9,7,7,9,9,9,6,7,5,10,7,8,9,9,10,9,9,7,3,1,5,6,7,7,3,10,7,9,1,7,10,9,6,3,3,10,4,10,9,6,7,9,7,7,5,10,7,9,7,10,9,6~4,8,5,6,9,7,8,5,3,9,10,9,9,8,5,8,9,4,10,5,8,7,5,5,9,4,5,3,9,9,6,5,8,9,8,8,6,4,5,9,9,9,5,4,5,4,3,7,9,4,9,9,8,10,4,5,5,4,9,10,9,9,5,4,10,9,4,8,5,3,8,5,8,9,5,3,5,3,6,9,8,3,3,3,3,8,10,9,8,9,8,9,8,3,8,3,10,5,8,5,3,4,3,8,9,3,8,4,9,9,8,5,5,3,9,5,7,9,8,4,8,3,5,5,9,3,8~5,6,5,6,10,7,10,5,10,9,10,9,5,10,8,7,5,10,4,4,3,5,4,7,7,6,10,5,3,6,5,7,6,3,6,4,5,7,10,3,3,3,6,5,7,9,7,3,10,6,10,1,4,10,10,5,6,3,4,6,10,6,4,3,4,8,5,6,1,6,6,10,5,4,6,10,10,6,4,4,1,7,10,6,6,6,5,3,7,6,3,7,4,7,9,7,5,5,7,8,3,10,5,1,5,10,5,6,4,7,3,6,1,3,6,3,9,5,5,3,5,3,10,1,8,7,3,1,6&s='.$lastReelStr.'&reel_set2=3,4,6,4,3,6,4,9,3,5,6,4,3,5,8,4,7,7,7,7,5,7,3,8,5,3,6,8,1,3,4,8,6,4,8,5,1,5,5,5,5,9,7,8,7,6,3,5,7,8,5,4,4,3,5,8,4,7,4,4,4,7,9,6,6,7,3,6,8,6,4,6,5,6,7,7,8,5,6,6,6,6,6,7,7,6,4,6,5,8,5,10,3,8,5,7,3,10,5,4,3,3,3,5,7,4,3,6,4,7,3,5,1,6,5,4,3,7,6,5,8,8,8,8,5,9,4,10,7,5,4,6,8,4,5,6,6,7,4,8,5,8,8~5,6,10,6,5,3,8,6,3,5,8,8,3,8,7,10,3,9,4,7,10,5,8,7,6,10,6,6,8,4,5,7,10,10,8,4,4,4,7,7,3,5,8,8,10,3,7,3,10,9,7,6,10,10,7,4,10,10,6,8,5,6,10,8,10,5,6,7,10,9,8,4,8,3,3,3,8,4,8,6,3,3,6,4,4,9,9,8,4,9,10,3,6,8,6,3,9,6,7,3,4,10,8,8,7,3,10,8,10,8,5,5,6,6,6,6,9,4,5,6,3,6,8,9,8,7,4,3,9,4,9,4,8,3,6,6,8,4,7,6,9,4,10,5,10,3,6,4,8,8,9,6,5,5,5,10,3,6,7,5,4,5,6,8,9,3,9,9,5,3,8,10,6,6,3,6,6,10,3,10,6,5,10,9,10,7,5,6,6,4,10,10,3~6,6,7,9,5,3,6,7,4,10,7,9,10,9,3,6,7,3,6,6,6,10,9,1,10,5,9,7,7,4,10,4,9,6,6,9,7,10,9,10,9,3,3,3,7,3,9,7,10,10,9,3,7,4,6,9,8,4,6,3,5,9,5,4,4,4,10,7,6,10,3,10,7,6,10,4,9,4,10,10,9,7,7,10,10,7,5,5,5,7,6,9,6,7,10,1,9,4,9,3,3,9,10,3,9,3,5,10,9,9,9,3,6,4,5,9,10,7,8,9,7,4,10,4,7,8,7,10,9,8,1,10~9,9,3,7,7,10,5,8,4,9,3,3,4,9,5,5,8,3,3,3,8,5,9,8,5,8,9,8,3,9,8,6,4,5,6,8,5,4,4,4,3,3,6,4,8,5,7,4,6,6,10,9,4,4,5,4,7,7,8,5,5,5,4,9,3,5,5,4,8,5,3,4,8,9,9,10,5,8,8,8,9,8,8,5,5,6,8,3,8,5,6,5,7,5,7,8,3,7,9,9,9,5,4,10,7,3,5,8,3,3,5,9,6,5,9,9,8,9,6,4~9,3,5,4,10,5,4,3,6,7,10,4,10,9,10,8,6,3,5,5,9,5,10,7,5,6,8,1,3,10,10,6,3,5,5,5,8,6,5,6,10,4,7,4,6,10,6,7,4,3,5,5,4,7,3,10,6,10,5,5,3,5,10,7,4,5,4,10,7,10,7,3,3,3,7,4,5,4,4,10,3,7,4,4,5,7,10,10,6,1,5,4,7,6,10,3,6,7,8,6,6,4,6,8,3,5,6,1,3,6,7,4,4,4,5,3,3,6,5,10,9,5,7,6,7,6,10,1,6,7,10,5,10,4,6,5,7,6,5,8,6,5,5,3,6,5,5,10,10,7,5,6,6,6,10,7,10,6,10,4,10,6,3,6,5,7,5,4,5,4,6,7,4,3,9,6,5,10,9,5,3,10,7,6,7,5,6,6,10,3&reel_set1=8,5,7,10,6,10,6,5,7,3,7,5,6,9,5,10,4,9,8,6,8,7,3,7,5,2,9,8,9,5,9,10,4,7,9,3,9,3,10,10,6,7,10,8,9,10,6,8,4,3,7,3,2,8,4&reel_set4=5,6,4,8,4,1,3,4,1,6,8,7,5,8,5,4,5,7,7,7,7,3,4,6,7,6,6,7,5,8,10,5,10,5,7,5,6,5,5,5,5,6,6,8,5,5,4,4,7,8,4,4,7,1,4,7,4,10,6,4,4,4,3,8,5,7,4,5,8,5,3,8,3,8,6,6,7,7,5,7,6,6,6,6,6,3,3,7,6,7,9,4,4,5,5,8,4,7,4,4,7,6,8,3,3,3,5,9,3,5,7,6,5,3,5,3,6,7,8,3,7,9,8,8,8,8,4,6,3,5,3,8,8,4,5,4,8,6,6,4,7,3,3,6,3,6~9,10,8,6,8,4,10,9,4,7,6,8,6,10,10,9,9,7,5,5,10,8,7,3,5,4,4,4,3,3,7,6,4,8,5,7,8,8,3,5,10,6,9,3,10,10,6,9,4,8,8,3,4,8,6,3,3,3,8,6,7,6,3,6,10,10,5,7,6,6,10,8,6,9,3,8,7,3,3,6,5,4,10,8,6,6,6,6,5,4,6,3,3,8,6,5,8,9,8,6,9,4,10,9,6,7,4,10,6,7,9,10,8,3,6,5,5,5,4,4,5,8,9,4,3,10,10,3,5,6,10,8,3,10,5,9,3,6,10,5,4,7,6,4,10,8~9,4,5,7,10,7,9,6,7,9,10,6,10,7,10,10,6,3,10,7,10,8,6,6,6,9,10,4,9,4,4,10,1,10,9,6,4,10,4,9,9,7,3,9,9,10,7,5,7,3,3,3,4,7,3,9,10,6,9,9,10,9,9,7,7,6,3,10,7,3,10,3,9,10,9,6,4,4,4,3,10,9,4,9,5,9,5,7,7,3,10,1,7,9,3,6,10,3,1,3,7,8,5,5,5,10,10,5,10,7,6,6,7,9,10,4,10,9,7,10,6,10,6,7,5,10,10,8,9,9,9,6,7,9,7,3,6,3,4,8,7,4,10,9,5,9,3,3,7,4,9,8,4,9,6,7~8,7,5,8,4,5,6,5,5,9,7,4,9,7,8,6,3,3,3,7,3,3,9,8,8,10,9,10,9,5,8,3,4,8,5,9,6,4,4,4,3,4,5,4,4,8,3,3,5,9,4,5,3,5,8,9,8,5,5,5,8,7,5,3,8,3,4,5,4,9,9,10,5,3,8,4,5,9,8,8,8,9,10,4,7,5,10,3,8,3,8,8,9,6,5,6,7,9,8,9,9,9,5,5,6,7,6,6,7,6,8,8,9,9,5,5,9,5,4,3,4,5~6,6,5,4,7,6,5,10,7,9,6,4,7,9,3,7,5,3,5,10,1,3,6,7,3,7,6,5,6,5,5,5,6,7,4,10,10,5,6,9,10,10,5,5,10,5,5,7,10,10,3,5,6,3,5,10,6,10,6,4,7,6,6,5,3,3,3,7,4,1,4,4,10,10,5,4,3,6,6,5,8,7,10,6,3,7,5,6,10,5,4,10,3,8,7,4,4,5,4,4,4,1,6,10,6,5,6,7,4,5,3,4,7,3,6,3,8,5,6,10,5,10,5,9,5,10,4,10,9,6,6,1,6,6,6,9,3,7,10,10,3,5,9,10,6,5,8,10,7,5,5,6,5,4,4,6,7,6,10,6,3,4,8,4,3,7,4,3,10&purInit=[{bet:1600,type:"default"}]&reel_set3=7,7,9,7,8,3,9,3,5,10,6,4,9,6,5,7,9,4,8,7,8,4,10,8,6,8,10,5,7,6,6,9,10,3,9,8,10,10,6,8,3,7,5,9,10,4&reel_set5=10,7,6,7,7,6,4,8,7,10,4,8,6,8,10,6,6,3,10,8,7,8,10,6,8,9,9,8,3,10,10,9,3,7,7,8,7,5,5,9,5,3,9,8,9,10,6,6,5,9,6,10,10,9,7,4,6,5,9,10,8,4,9,4,8,7,3,4,8,9,5,10,5,7,8,3&total_bet_min=10.00';
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
                    $allBet = $allBet * 80;
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
                    $roundstr = sprintf('%.4f', microtime(TRUE));
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
                $arr_g = null;
                $rs_p = -1;
                $rs_m = 0;
                $rs_c = 0;
                $rs_t = 0;
                $str_slm_mp = '';
                $str_slm_mv = '';
                $subScatterReel = null;
                $str_rmul = '';
                $str_sa = '';
                $str_sb = '';
                if($slotEvent['slotEvent'] == 'freespin' || $isTumb == true){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    if($stack['g'] != ''){
                        $arr_g = $stack['g'];
                    }
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $str_slm_mp = $stack['slm_mp'];
                    $str_slm_mv = $stack['slm_mv'];
                    $fsmore = $stack['fsmore'];
                    $strWinLine = $stack['wlc_v'];
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
                    if($stack[0]['g'] != ''){
                        $arr_g = $stack[0]['g'];
                    }
                    $rs_p = $stack[0]['rs_p'];
                    $rs_c = $stack[0]['rs_c'];
                    $rs_m = $stack[0]['rs_m'];
                    $rs_t = $stack[0]['rs_t'];
                    $fsmore = $stack[0]['fsmore'];
                    $str_slm_mp = $stack[0]['slm_mp'];
                    $str_slm_mv = $stack[0]['slm_mv'];
                    $strWinLine = $stack[0]['wlc_v'];
                    $str_sa = $stack[0]['sa'];
                    $str_sb = $stack[0]['sb'];
                }
                $reels = [];
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;
                for($i = 0; $i < 20; $i++){
                    if($lastReel[$i] == $scatter){
                        $scatterCount++;
                        $scatterPoses[] = $i;
                    }
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
                    if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                    }
                }
                $strLastReel = implode(',', $lastReel);
                $lastLogReel = [];
                for($k = 0; $k < count($lastReel); $k++){
                    if($k < 6){
                        $lastLogReel[6-$k-1] = $lastReel[$k];
                    }else{
                        $lastLogReel[$k] = $lastReel[$k];
                    }
                }
                $strLastLogReel = implode(',', $lastLogReel);

                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $strOtherResponse = '';
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') + $totalWin);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') + $totalWin);
                    if($isNewTumb == false){
                        if($scatterCount >=3){
                            $isState = false;
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 8);
                            $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                            $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
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
                if($arr_g != null){
                    $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($arr_g));
                }
                if($str_slm_mv != ''){
                    $strOtherResponse = $strOtherResponse . '&slm_mp=' . $str_slm_mp . '&slm_mv=' . $str_slm_mv;
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&wlc_v=' . $strWinLine;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse  .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='. $str_sa .'&sb='. $str_sb .'&st=rect&c='.$betline.'&sh=4&sw=5&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&w='.$totalWin.'&s=';
                $response_log = $response.$strLastLogReel;
                $response = $response . $strLastReel;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)  && $isNewTumb == false) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if($isNewTumb == false){
                    if( $slotEvent['slotEvent'] != 'freespin' && $scatterCount >= 3) 
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
                    $allBet = $allBet * 80;
                }
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                

            }
            if($slotEvent['action'] == 'doSpin' || $slotEvent['action'] == 'doFSOption' || $slotEvent['action'] == 'doCollect' || $slotEvent['action'] == 'doCollectBonus' || $slotEvent['action'] == 'doBonus'){
                if($slotEvent['action'] == 'doSpin'){
                    $this->saveGameLog($slotEvent, $response_log, $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'), $slotSettings);
                }else{
                    $this->saveGameLog($slotEvent, $response, $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'), $slotSettings);
                }
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

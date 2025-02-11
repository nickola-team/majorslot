<?php 
namespace VanguardLTE\Games\VampyPartyPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Level', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'G', []);
                $slotSettings->SetGameData($slotSettings->slotId . 'Trail', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                // $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [19,8,7,10,12,19,10,7,3,9,5,12,11,9,1,11,12,5,11,9,8,5,12,6,11,9,10,5,19,9,11,9,19,19,19,9,19,9,19,19,19,9,19,19,19,19,19,12]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Bl', 0);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', $lastEvent->serverResponse->TumbleState);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', $lastEvent->serverResponse->Bgt);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Level', $lastEvent->serverResponse->Level);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Trail', $lastEvent->serverResponse->Trail);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bl', $lastEvent->serverResponse->Bl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);
                    if (isset($lastEvent->serverResponse->ReplayGameLogs)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', json_decode(json_encode($lastEvent->serverResponse->ReplayGameLogs), true)); //ReplayLog
                    }
                    if (isset($lastEvent->serverResponse->TumbWin)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $lastEvent->serverResponse->TumbWin); //ReplayLog
                    }
                    if (isset($lastEvent->serverResponse->G)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'G', json_decode(json_encode($lastEvent->serverResponse->G), true)); //ReplayLog
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
                // $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                $fsmore = 0;
                if(isset($stack)){
                    $currentReelSet = $stack['reel_set'];
                    $str_rs = $stack['rs'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $wmv = $stack['wmv'];
                    $str_rs_iw = $stack['rs_iw'];
                    $str_stf = $stack['stf'];
                    $str_s_mark = $stack['s_mark'];
                    $bw = $stack['bw'];
                    $fsmore = $stack['fsmore'];
                    $strWinLine = $stack['win_line'];
                    $arr_g = null;
                    if($stack['g'] != ''){
                        $arr_g = $slotSettings->GetGameData($slotSettings->slotId . 'G');
                    }
                    $str_trail = $slotSettings->GetGameData($slotSettings->slotId . 'Trail');
                    $level = $slotSettings->GetGameData($slotSettings->slotId . 'Level');
                    $bgt = $slotSettings->GetGameData($slotSettings->slotId . 'Bgt');
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    if($bgt > 0){
                        $spinType = 'b';
                    }
                    if($str_trail != ''){
                        $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                    }
                    if($str_rs_iw != ''){
                        $str_rs_iw = str_replace(',', '', $str_rs_iw) / $original_bet * $bet;
                        $strOtherResponse = $strOtherResponse . '&rs_iw=' . $str_rs_iw;
                    }
                    if($str_stf != ''){
                        $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                    }
                    if($str_s_mark != ''){
                        $strOtherResponse = $strOtherResponse . '&s_mark=' . $str_s_mark;
                    }
                    if($bw == 1){
                        $strOtherResponse = $strOtherResponse . '&bw=' . $bw;
                    }
                    if($arr_g != null && count($arr_g) > 0){
                        if(isset($arr_g['main'])){
                            $arr_g['main']['def_s'] = '6,8,5,7,5,10,5,10,4,9,6,8,4,9,8,3,5,7,11,7,3,8,4,11,11,11,5,9,11,11';
                            $arr_g['main']['def_sa'] = '3,9,8,10,3,7';
                            $arr_g['main']['def_sb'] = '3,10,6,9,6,10';
                            $arr_g['main']['reel_set'] = '0';
                        }
                        if(isset($arr_g['pattern'])){
                            $arr_g['pattern']['def_s'] = '1,1,1,1,1,1,1,1,1,1';
                            $arr_g['pattern']['def_sa'] = '1,1';
                            $arr_g['pattern']['def_sb'] = '1,1';
                            $arr_g['pattern']['reel_set'] = '0';
                        }
                        $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($arr_g));                    
                    }else{
                        $strOtherResponse = $strOtherResponse . '&g={main:{def_s:"6,8,5,7,5,10,5,10,4,9,6,8,4,9,8,3,5,7,11,7,3,8,4,11,11,11,5,9,11,11",def_sa:"3,9,8,10,3,7",def_sb:"3,10,6,9,6,10",reel_set:"0",s:"6,8,5,7,5,10,5,10,4,9,6,8,4,9,8,3,5,7,11,7,3,8,4,11,11,11,5,9,11,11",sa:"3,9,8,10,3,7",sb:"3,10,6,9,6,10",sh:"5",st:"rect",sw:"6"},pattern:{def_s:"1,1,1,1,1,1,1,1,1,1",def_sa:"1,1",def_sb:"1,1",reel_set:"0",s:"1,1,1,1,1,1,1,1,1,1",sa:"1,1",sb:"1,1",sh:"5",st:"rect",sw:"2"}}';
                    }
                    if($wmv > 0){
                        $strOtherResponse = $strOtherResponse . '&wmt=pr&wmv=' . $wmv;
                        if($wmv > 1){
                            $strOtherResponse = $strOtherResponse . '&gwm=' . $wmv;
                        }
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
                }else{
                    $strOtherResponse = $strOtherResponse . '&g={main:{def_s:"6,8,5,7,5,10,5,10,4,9,6,8,4,9,8,3,5,7,11,7,3,8,4,11,11,11,5,9,11,11",def_sa:"3,9,8,10,3,7",def_sb:"3,10,6,9,6,10",reel_set:"0",s:"6,8,5,7,5,10,5,10,4,9,6,8,4,9,8,3,5,7,11,7,3,8,4,11,11,11,5,9,11,11",sa:"3,9,8,10,3,7",sb:"3,10,6,9,6,10",sh:"5",st:"rect",sw:"6"},pattern:{def_s:"1,1,1,1,1,1,1,1,1,1",def_sa:"1,1",def_sb:"1,1",reel_set:"0",s:"1,1,1,1,1,1,1,1,1,1",sa:"1,1",sb:"1,1",sh:"5",st:"rect",sw:"2"}}';
                }
                    
                $Balance = $slotSettings->GetBalance();
                $response = 'balance='. $Balance .'&cfgs=12514&ver=3&index=1&balance_cash='. $Balance .'&reel_set_size=10&balance_bonus=0.00&na='. $spinType .'&scatters=1~0,0,0,0,0,0~0,0,0,0,0,0~1,1,1,1,1,1&rt=d&gameInfo={props:{gamble_x16:"52.00",max_rnd_sim:"1",gamble_x32:"52.08",gamble_x256:"59.49",max_rnd_hr:"1364256",max_rnd_win:"5000",gamble_x64:"50.74",gamble_x128:"54.93",max_rnd_win_a:"3572",max_rnd_hr_a:"460405"}}&wl_i=tbm~5000;tbm_a~3572&bl='. $slotSettings->GetGameData($slotSettings->slotId . 'Bl') .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=50.00&purInit_e=1,1,1,1&wilds=2~0,0,0,0,0,0~1,1,1,1,1,1&bonuses=0&c='.$bet.'&sver=5&bls=20,28&counter=2&ntp=0.00&paytable=0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;50,30,20,10,0,0;30,20,15,8,0,0;20,15,10,5,0,0;20,15,10,5,0,0;10,6,4,2,0,0;10,6,4,2,0,0;5,3,2,1,0,0;5,3,2,1,0,0;0,0,0,0,0,0&l=20&total_bet_max=28,800,000.00&reel_set0=4,7,10,7,9,8,7,9,7,10,5,5,3,8,10,10,5,8,3,4,9,5,5,8,8,7,8,1,8,9,7,5,3,10,1,3,9,10,10,8,8,10,6,10,3,5,8,9,8,3,10,7,9,3,10,8,5,7,8,4,8,5,7,6,10,7,5,7,6,5~3,5,6,4,4,10,3,8,10,6,10,9,9,6,3,6,9,8,4,9,7,9,10,7,4,9,10,4,9,6,4,6,5,10,4,10,6,9,6,6,4,10,7,8,6,3,10,6,9,4,9,4,4,9,9,1,8,9,6,6,10,4,7,4,9,6,9,10,6,10,10,6,10,6,4,6,4,9,9,10,9,9,7,10,4,5,6,4~3,7,10,7,10,7,10,7,5,5,8,9,5,5,8,8,7,5,3,7,6,7,3,8,5,8,1,3,3,9,4,7,8,6,9,9,4,5,7,3,9,8,8,7,8,7,8,8,5,8,5,9,8,10~8,9,10,9,4,8,6,7,5,7,6,3,7,8,3,10,8,7,10,8,4,6,9,10,9,8,7,5,10,4,5,1,6,4,6,7,6,8,9,4,3,10,4,10,8,5,9,4,7,5,7,6,7,9,6,4,4,6,9,3,10,6,10,7,10,9,6,3,9,3,9,10,9,3,7,4,10,6,10,4,6~8,5,1,7,3,5,3,7,4,5,10,8,5,3,10,5,7,8,9,9,1,6,7,10,4,8,9,8,10,8,3,9,7,8,7,10,7,5,10,5,10,8,9,4,6,7,4,7,3,10,5,7,5,6,7,8,10,9,8,6,7~9,4,9,4,10,9,6,9,1,7,6,9,10,7,9,10,9,8,5,6,5,10,9,6,4,5,4,9,6,7,8,4,9,4,6,4,10,6,7,10,7,9,6,10,10,8,10,6,10,4,9,9,6,1,9,3,6,4,7,6,4,8,10,6,4,10,3,7,9,10,3,7,6,5,3,9,4,8,10&reel_set2=3,9,5,7,8,10,7,9,4,8,9,8,7,10,5,6,5,8,7,6,8,5,5,9,3,7,5,5,4,10,7,10,5,5,3,7,10,3,8,9,7,9,7,8,10,6,10,9,8,7,10,7,6,9,3,8,10,5,5,8,7,8,8,10,1,7,10,5,7,8,4,10,8,3,1,7,8,7,9,8,10~4,7,9,9,4,4,10,6,4,9,6,6,4,10,4,4,9,6,5,1,6,7,9,4,10,10,8,6,10,3,10,9,6,6,9,4,10,10,4,6,6,9,6,6,3,8,5,6,9,8,6,6,9,5,4,10,8,4,9,3,9,6,10,9,10,10,7,6,9,10,10,4,9,9,3,9,4,10,6,4,9,4,10,9,7,4,7,6,10,4,9,9,6,6,4~9,7,10,9,10,7,5,6,8,7,9,8,5,7,5,7,8,3,7,8,8,5,8,8,3,8,3,7,6,3,5,5,4,9,8,5,9,8,6,5,6,9,7,10,7,5,9,8,7,1,3,9,6,4,7,10,8,8,7,9,7,9,5,5,8,10,8,8,7,5,7,10,3,1,3,7,8,4,7,3,5,8,7,5,7,8,9,5,5,3,8,3,8,7,8,3~9,6,3,1,3,7,10,9,8,10,8,7,9,5,7,6,4,4,8,6,4,3,6,4,3,10,8,6,8,9,6,8,6,4,10,7,9,7,10,4,5,10,7,10,9,4,7,5,6,5,9,10,6,4,7,6,4,7,10,8~5,9,3,5,8,5,9,10,8,5,8,5,10,4,10,7,8,5,9,5,7,8,7,9,8,10,6,7,3,8,3,7,3,4,9,10,8,10,8,7,5,4,8,7,6,5,6,7,10,4,6,9,5,7,10,7,1,10,7,3,10,3,4,5,3,10,6,9,8~9,9,8,10,5,10,4,9,10,6,7,6,7,5,10,6,8,10,9,8,5,6,4,9,10,9,8,6,3,9,4,3,6,9,8,4,7,9,6,10,5,7,4,10,7,1,7,4,6,9,10,6,9,4,6,4,10,4,7,9,10,9,3,6,9,9,3,10,6&reel_set1=4,7,1,9,9,7,5,6,6,9,6,10,8,9,7,9,9,7,6,9,3,10,4,10,10,8,6,3,6,9,7,10,9,10,6,6,3,1,6,10,4,9,10,9,4,3,8,7,8,4,6,10,9,10,5,10,4,8,9,10,7,4,9,4,10,4,6,8,10,9,10,4,3,10,4,6,6,9,7,6,3,9,6,9,6,4,10,5,4,8,3,9,3,6,10,8,4,6,9,9,10,3,9,6,5,10~5,9,7,8,4,5,8,7,7,8,8,7,3,5,9,5,10,7,3,1,7,3,7,3,7,8,5,9,5,8,5,5,6,5,3,6,8,10,9,6,7,1,8,5,8,7,7,10,3,9,4,8,5,3,7,3,10,8,5,3,4,5,5,10,8,7,9,5,10,8,10,5,3,9,10,5,6,8,8,7,7~5,10,6,10,3,9,9,10,6,9,7,8,10,4,10,6,4,8,9,4,9,6,10,6,10,10,4,9,6,6,9,9,4,9,9,10,7,10,7,9,6,8,7,8,9,4,7,5,10,9,10,10,4,10,9,4,6,8,3,6,1,4,4,9,6~6,10,9,4,5,3,8,7,9,4,10,7,6,7,10,9,9,8,10,8,5,7,5,6,5,8,6,5,8,7,8,9,9,7,10,3,6,10,4,9,10,8,3,5,1,7,1,10,9,5,4,7,8,7,4~9,10,6,3,9,10,6,3,9,3,9,5,9,7,6,9,4,10,4,6,4,10,9,6,4,10,5,10,4,8,1,7,6,4,9,7,6,8,6,9,8,4,9,10,4,8,10,9,3,5,6,7,3,10,6,9,6,10,6,9,5,9,3,7,9,3,8,10,5,6,5,4,5,4,9,7,9,8,4~10,6,7,5,8,10,9,7,5,7,5,7,10,6,4,5,7,9,10,5,10,7,1,8,8,9,7,10,10,7,10,6,3,5,9,6,7,8,8,9,10,8,7,8,8,3,7,4,8,10,4,9&reel_set4=8,7,5,5,7,8,5,9,7,10,3,8,10,8,4,10,7,5,3,8,7,10,8,8,9,6,10,5,10,10,5,10,3,5,5,8,10,3,8,6,10,8,9,5,5,7,10,10,9,8,4,7,8,5,10,8,9,8,7,1,7,5,7,6,7,3,7,8,7,1,10,9,3,9,7,8,9,5,3,7,4,5,8,10,10,8~10,8,6,4,5,10,4,4,1,10,6,10,6,8,9,9,7,10,9,4,10,4,6,6,10,4,6,6,10,6,6,9,3,8,9,10,7,3,9,4,9,8,4,6,9,4,6,5,6,4,9,6,10,9,6,9,7,6,9,3,10,9,10,9,6,9,7,9,4,4,3,9,6,10,4,4,6,10,10,5,4,6,4,10,4,9,9~8,1,7,3,7,4,3,3,9,3,6,4,3,10,9,5,5,10,9,7,8,8,10,7,5,9,8,10,8,3,3,8,5,4,9,9,8,7,8,7,8,7,5,7,9,8,5,6,8,9,10,5,3,5,7,8,5,8,7,6,7,6,5,5,7,8,7,9,8,8,5,5,7,8,5,5,8,7,3,1,9,6,3,8,8,10~6,9,3,5,6,1,5,9,3,6,9,10,5,10,3,10,3,9,10,6,4,4,9,10,7,8,6,9,4,6,4,9,7,3,6,7,10,9,5,3,9,8,6,4,7,4,7,9,7,8,10,6,7,9,6,10,3,7,9,6,7,7,8,4,6,8,10,4,8,7,10,6,10,4,5,9,8,4,7,4,4,7,9,10,8,10,3,5,8,9,4,6~3,10,5,8,7,9,5,8,10,8,7,4,10,4,8,4,6,9,9,3,5,8,6,7,9,8,5,8,10,3,7,6,4,10,8,10,1,8,7,8,5,9,5,9,9,6,5,10,6,3,10,8,5,1,7,10,5,7,3,10,7,10,7,8,3,5,6,5,10,3,10,5,8,3,7,8~10,8,10,7,4,9,9,10,10,9,4,6,9,6,10,7,5,9,9,6,1,3,4,9,9,6,10,4,7,10,6,9,8,10,4,6,8,4,9,9,6,5,7,10,6,10,4,9,10,6,3,9,4,10,9,9,8,6,10,4,6,8,1,8,10,4,7,6,5,7,5,7,9,9,3,5,3&purInit=[{bet:1560,type:"default"},{bet:3000,type:"default"},{bet:5760,type:"default"},{bet:2560,type:"default"}]&reel_set3=10,3,6,6,8,4,6,6,10,10,7,4,1,6,7,8,9,6,6,7,10,4,10,10,5,10,4,6,4,7,9,10,4,10,5,10,9,4,6,3,9,9,8,10,9,9,10,8,3,7,9,4,9,9,3,6,6,9,9,4,9~10,7,6,5,1,5,3,9,3,4,8,7,3,8,5,5,10,8,3,5,8,7,10,7,9,5,8,8,10,6,5,7,4,6,8,5,5,7,5,7,9,8,7,5,5,1,3,7,3,7,3,8,9,8,9~7,8,10,9,10,8,6,4,4,5,9,10,9,10,4,5,7,6,4,10,9,10,8,6,7,4,6,10,9,4,6,4,6,9,4,10,4,5,10,9,6,6,9,4,10,6,9,10,7,9,10,1,6,9,10,3,9,9,10,10,4,8,8,6,6,10,8,4,4,10,9,6,10,9,6,6,7,4,6,9,3,8,10,8,9,9,4,3,9,10,9,7,9,10~4,5,6,5,9,7,9,8,5,3,7,8,7,4,7,9,4,10,8,4,10,10,9,10,7,1,8,7,6,4,5,10,8,9,7,6,7,9,10,10,8,5,8,5,10,3,5,3,6,5,7,10,4,7,8,9,4,6,9,8,9~10,6,9,4,10,4,10,9,10,9,7,9,4,8,1,4,9,8,10,4,10,6,4,5,3,9,4,5,3,9,4,3,9,6,9,10,5,6,7,8,6,10,9,4,10,5,9,3,6,9,7,10,6,7,4,5,3,5,3,6,9,7,8,9,8,6,10,9,8,6~4,3,8,5,9,8,5,9,3,7,10,6,8,8,6,7,4,7,8,4,5,7,4,8,7,10,10,9,7,5,10,6,1,10,5,9,7,6,10,8,7,4,7,10,4,3,8,9,1,10,10,8,9,6,5,10,10,8,5,7,10,6,8,7,10,10,6,7,7,8,10,7,7,4,9,3,10,3,9,5,8,7,8,8,7,10,6,7,10,5,8,7,9&reel_set6=10,3,8,3,8,10,10,5,10,5,3,5,3,7,8,8,5,7,8,7,10,10,8,8,7,10,5,7,3,8,8,7,7,10,5,10,8,7,7,1,10,5,10,7,7,1,7,8,10,8,8,7,3,5,10,10,7,10,7,8,5,5,8,3,7,10,3,3,7,8,7,3,8,8,7,5,8,7,1,3,5,10,10,7,10,5,8,5,8,10,5~1,9,6,9,6,9,9,10,6,10,4,6,9,6,4,4,6,9,4,9,4,4,6,6,1,9,6,10,10,4,4,6,10,10,10,6,6,9,10,10,9,4,10,6,4,9,10,6,4,4,10,9,9,4,6,9,9,10,6,6,9,4,10,10,9,10,9,4~5,5,3,8,7,5,5,9,3,5,8,3,5,7,8,3,7,6,4,8,7,9,8,9,6,8,4,8,9,3,7,3,7,5,5,9,4,8,6,3,9,5,6,8,4,8,9,8,9,7,8,8,7,8,7,5,3,7,8,9,7,8,5,5,3,7,8,7,7~6,4,8,8,10,8,8,3,6,10,6,5,7,7,10,9,6,7,10,5,3,6,4,10,6,10,9,10,6,4,9,8,9,4,3,7,8,4,6,9,4,10,4,3,9,7,8,9,5,7,7,10,4,6,3,9,3,9,7~3,8,6,3,4,10,8,7,5,7,6,10,5,8,9,3,5,10,9,8,7,9,10,6,4,10,4,3,10,8,7,5,3,5,10,8,7,5,7,6,5,10,7,10,3,7,6,9,4,8,9,9,3,10,8,7,8,6,3,9,7,3,5,7,5,9,10,4,10,6,4,7,8,10,5,8,8~5,10,9,4,6,9,9,5,6,10,10,3,6,8,10,4,10,8,7,10,8,6,10,9,10,6,8,6,10,4,10,10,3,6,7,9,6,3,9,4,6,8,9,4,8,4,9,9,4,3,5,9,6,7,9,9,4,10,9,6,5,9,7,4,10,9,6,7,6,5,10,9,3,9,9,7,6,10,4,7&reel_set5=9,9,5,10,9,9,6,7,9,9,4,10,9,9,10,9,4,3,10,4,6,6,4,9,8,9,4,8,6,6,3,10,9,7,4,3,8,6,5,6,3,10,7,10,6,10,10,3,8,10,6,10,4,10,10,6,6,3,4,6,10,4,7,10,5,7,9,3,1,4,6,8,6,8,9,3,9,10,4,6,9,7,9,7~3,7,8,5,9,7,8,7,10,1,9,1,7,8,5,7,5,10,8,5,6,5,4,8,3,5,9,8,10,7,10,7,3,9,10,8,10,8,5,6,7,4,3,7,9,5,8,3,5,7,3,5,6,5,8,3,8,7,5~9,6,10,4,4,1,10,4,4,8,9,9,6,9,6,9,9,10,6,3,6,7,6,4,9,7,9,6,9,6,10,8,10,8,5,9,8,5,9,9,6,10,10,4,4,10,7,10,9,9,4,6,3,10,4,10,4,6,9,9,7,6,8,6,7,4,4,9,4,10,9,4,10,5,7,10,6,8,10,10,8,9,9,10,3,10,6,10~4,8,5,9,9,6,7,9,6,10,3,8,5,10,4,10,10,9,1,4,6,10,10,4,9,5,7,4,7,8,10,5,7,6,8,7,5,7,3,10,6,9,8,9,9,10,7,8,4,7,3,5,8,5,3,5,7,3,4,5,1,7,4,6,9,8,9,10,5,10,6,5,8,9,7,5~5,7,9,7,10,9,10,6,7,9,3,4,6,4,9,6,10,9,6,9,10,4,3,9,4,6,8,3,10,4,9,4,5,8,10,8,4,10,6,3,9,10,6,5,4,9,5,6,3,4,5,9,4,9,10,3,10,4,7,3,6,5,4,7,10,9,7,8,6,7,9,8,6,9,1,5,6,9,10,3,9,6,4,8,10,5,10,6,9,10,4,9,4,6,8~6,8,8,10,8,9,4,8,9,8,7,5,8,3,7,5,6,7,10,8,8,7,3,5,10,10,7,5,10,4,6,7,3,1,7,3,10,8,9,10,10,4,6,8,10,7,5,10,9,4,10,8,7,10,7,7,9,7,5,6,8,7,4,9,5,10,6,7&reel_set8=5,10,7,3,10,8,10,7,10,10,5,5,7,8,1,3,8,7,8,8,7,10,10,5,5,8,3,8,5,3,8,7,10,1,10,10,5,8,7,8,5,5,7,7,5,5,10,7,7,10,3,3,7,8,7,5,3,7,10,7,3,1,3,8,10,8,7,8,8,10,10,7,10,5,10,1,5,5,8,7,3,8,10,7,3,10,7,8,10,7,5,8,7,7,8,8,3,10,8,5~6,9,10,9,4,6,4,6,6,4,4,9,10,9,1,4,6,6,9,4,4,9,10,4,9,10,6,9,6,9,10,10,10,4,9,6,1,9,6,9,10,10,6,10,4,9,9,6,6,9,4,6,9,6,10,1,9,4,4,10,9,9,6,4,10,4,10,10~3,9,8,9,7,8,3,3,5,8,8,7,9,8,7,5,5,4,7,8,7,5,5,7,8,1,3,1,3,4,8,9,1,8,8,5,5,7,3,9,6,3,7,9,9,8,7,8,9,6,7,8,4,1,6,8,3,5,5,7~4,5,9,1,8,10,6,9,8,1,9,6,1,8,5,10,7,10,9,8,1,6,1,7,7,8,4,10,6,3,1,9,6,7,6,10,1,4,7,1,9,6,4,1,7,3,7,9,6,10,1,6,10,7,6,4,9,6,1,10,5,3,9,10,9,10,8,1,3,1,9~10,1,10,7,3,7,6,1,8,6,3,5,1,8,7,3,9,1,7,3,10,9,10,5,9,1,8,4,8,5,8,1,10,7,8,9,6,1,7,5,10,5,1,7,1,8,1,10,7,1,5~6,4,6,4,7,9,5,3,8,6,1,7,10,7,6,9,9,3,8,10,9,7,9,10,9,3,6,9,5,10,8,4,9,6,5,9,4,9,4,10,1,4,10,8,10,10,6,10,4,7,6,9,8,10&reel_set7=9,4,3,10,6,4,10,9,7,3,9,9,10,9,6,7,6,10,10,5,3,10,7,10,9,9,3,9,6,10,10,5,9,3,10,6,3,5,6,9,4,6,4,6,4,7,4,6,4,7,6,10,4,7,9,10,9,4,3,9,4,6,10,5,4,9,3,6~5,3,7,8,7,5,5,7,8,8,7,8,5,7,8,5,7,3,3,8,8,5,3,3,7,8,8,5,5,5,3,7,7,8,3,3,5,8,7,7,8,5,8,5,5,8,3,7,8,8,5,7,3,5,8,5,7,3,5,7,7~9,8,4,4,9,9,8,9,6,10,4,9,4,10,10,4,4,10,9,6,10,4,6,9,9,4,8,10,8,10,10,9,4,6,10,6,10,9,9,6,4,8,10,10,9,6,6,10,9,6,9,9,4,10,9,8,6,10,8,10,4,4,9,4,9,6,6,9~6,4,9,4,10,7,5,9,9,5,7,10,3,1,4,5,9,7,5,7,9,7,8,3,5,10,8,9,4,9,6,8,10,4,5,10,8,1,7,3,6,5,6,8,10,7,5,7,5,8,3,8,7,4,10,9,5,6,9,10,8,9,8~6,9,1,4,10,6,10,6,9,10,4,6,9,4,9,8,9,7,9,4,3,5,3,10,9,10,5,4,3,8,4,6,9,7,8,5,6,10,6,7,6,1,9,8,7,5,9,4,10,4,10,6,4~10,8,9,3,5,7,9,8,7,5,8,10,9,6,8,6,10,7,7,8,8,9,7,5,8,3,10,8,3,7,7,5,6,10,10,4,10,8,6,8,8,4,5,10,7,10,7,4,5,4,7,7,8,10,10,9,6,7,5,9,7,5,6,10,7,10,7,10,9,7,9,7,8,5,8,6,3,8,7,10,6,8,5,9,4,10,3,10,8,7,5,4&reel_set9=1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,2,1,1,1,1,1,1,2,1,2,1,1,2,1,1,1,1,2,1,1,1,1,1,1,1,1,2,2,1,1,1,1,1,1,1,1,1,1,1,1,1,1,2,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,2,1,1,1,1,1,1,1,2,1,1,1~1,1,1,1,1,1,1,1,1,1,1,1,1,2,1,1,1,1,1,1,2,1,1,1,1,1,2,1,2,2,1,1,1,1,2,1,1,1,1,1,1,1,1,2,1,1,1,2,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,2,1,1,1,1,1,1,1,1,1,1,1,1,1,1,2,1,1,1,1,1,1,1&total_bet_min=100.00';
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
                $pur_muls = [78, 150, 288, 128];
                if($pur >= 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $allBet * $pur_muls[$pur];
                }else if($bl > 0){
                    $allBet = $betline * 28;
                }
                $tumbAndFreeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'freespin' || $isTumb == true){
                    if($slotEvent['slotEvent'] == 'freespin' && $isTumb == false){
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    }
                    $leftFreeGames = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') - $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame'); 
                    $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                    if($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') < 0)
                    {
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Level', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'G', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Trail', '');
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bl', $bl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.1f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '57409' . substr($roundstr, 2, 9);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $leftFreeGames = 0;

                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []);
                }
                
                $wild = '2';
                $scatter = '1';
                $empty = '19';
                $Balance = $slotSettings->GetBalance();
                $totalWin = 0;
                $this->winLines = [];
                $bonusMpl = 1;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $arr_g = null;
                $wmv = 0;
                $str_rs = '';
                $rs_p = -1;
                $rs_m = 0;
                $rs_c = 0;
                $rs_t = 0;
                $str_rs_iw = '';
                $str_trail = '';
                $str_stf = '';
                $str_s_mark = '';
                $bw = 0;
                $fsmore = 0;
                if($slotEvent['slotEvent'] == 'freespin' || $isTumb == true){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    if($stack['g'] != ''){
                        $arr_g = $stack['g'];
                    }
                    $currentReelSet = $stack['reel_set'];
                    $str_rs = $stack['rs'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $wmv = $stack['wmv'];
                    $str_rs_iw = $stack['rs_iw'];
                    $str_trail = $stack['trail'];
                    $str_stf = $stack['stf'];
                    $str_s_mark = $stack['s_mark'];
                    $bw = $stack['bw'];
                    $fsmore = $stack['fsmore'];
                    $strWinLine = $stack['win_line'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines, $pur);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    if($stack[0]['g'] != ''){
                        $arr_g = $stack[0]['g'];
                    }
                    $str_rs = $stack[0]['rs'];
                    $rs_p = $stack[0]['rs_p'];
                    $rs_c = $stack[0]['rs_c'];
                    $rs_m = $stack[0]['rs_m'];
                    $rs_t = $stack[0]['rs_t'];
                    $wmv = $stack[0]['wmv'];
                    $str_rs_iw = $stack[0]['rs_iw'];
                    $str_trail = $stack[0]['trail'];
                    $str_stf = $stack[0]['stf'];
                    $str_s_mark = $stack[0]['s_mark'];
                    $bw = $stack[0]['bw'];
                    $fsmore = $stack[0]['fsmore'];
                    $strWinLine = $stack[0]['win_line'];
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
                if( $totalWin > 0) 
                {
                    $spinType = 'c';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }
                $_obf_totalWin = $totalWin;
                $isState = true;
                if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', $rs_p);
                $strOtherResponse = '';
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                if($rs_p > 0)
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') + $totalWin);
                }
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $spinType = 's';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $isState = false;
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 && $rs_p < 0) 
                    {
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsend_total=1&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 'c';
                        $isState = true;
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
                }
                else
                {
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&purtr=1&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                    }
                }
                if($rs_p >= 0){
                    $isState = false;
                    $spinType = 's';
                }
                if($rs_p >= 0){
                    $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p . '&rs_c='. $rs_c .'&rs_m=' . $rs_m;
                }
                if($rs_t > 0){
                    $strOtherResponse = $strOtherResponse.'&rs_t='.$rs_t;
                }
                if($rs_p > 0 || $rs_t > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin');
                }
                if($str_rs != ''){
                    $strOtherResponse = $strOtherResponse . '&rs=' . $str_rs;
                }
                if($bw == 1){
                    $isState = false;
                    $strOtherResponse = $strOtherResponse . '&bw=' . $bw;
                    $spinType = 'b';
                    $slotSettings->SetGameData($slotSettings->slotId . 'Level', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 69);
                }
                if($str_trail != ''){
                    $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                    $slotSettings->SetGameData($slotSettings->slotId . 'Trail', $str_trail);
                }
                if($str_rs_iw != ''){
                    $str_rs_iw = str_replace(',', '', $str_rs_iw) / $original_bet * $betline;
                    $strOtherResponse = $strOtherResponse . '&rs_iw=' . $str_rs_iw;
                }
                if($str_stf != ''){
                    $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                }
                if($str_s_mark != ''){
                    $strOtherResponse = $strOtherResponse . '&s_mark=' . $str_s_mark;
                }
                if($arr_g != null){
                    $slotSettings->SetGameData($slotSettings->slotId . 'G', $arr_g);
                    $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($arr_g));
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&wlc_v=' . $strWinLine;
                }
                if($wmv > 0){
                    $strOtherResponse = $strOtherResponse . '&wmt=pr&wmv=' . $wmv;
                    if($wmv > 1){
                        $strOtherResponse = $strOtherResponse . '&gwm=' . $wmv;
                    }
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse  .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&bl=' . $slotSettings->GetGameData($slotSettings->slotId . 'Bl') .'&stime=' . floor(microtime(true) * 1000) .'&st=rect&c='.$betline.'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&w='.$totalWin;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)  && $rs_p < 0) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if($rs_p < 0){
                    if( $slotEvent['slotEvent'] != 'freespin' && $bw == 1) 
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"Bl":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bl')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"TumbleState":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') . ',"TumbWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin')  . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"Level":' . $slotSettings->GetGameData($slotSettings->slotId . 'Level') . ',"Trail":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Trail')  . '","G":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'G')) .',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":""}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($slotEvent['slotEvent'] == 'freespin' && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * $pur_muls[$slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin')];
                }else if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') > 0){
                    $allBet = $betline * 28;
                }
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);

            }else if( $slotEvent['slotEvent'] == 'doBonus' ){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lines = 20;
                $lastReel = $lastEvent->serverResponse->LastReel; 
                $Balance =  $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                $bgt = $slotSettings->GetGameData($slotSettings->slotId . 'Bgt');
                if($bgt == 0){
                    $response = 'unlogged';
                    exit( $response );
                }
                $level = $slotSettings->GetGameData($slotSettings->slotId . 'Level');
                $arr_g = $slotSettings->GetGameData($slotSettings->slotId . 'G');
                $arr_trails = explode(';', $slotSettings->GetGameData($slotSettings->slotId . 'Trail'));
                $fsmax = 0;
                for($i = 0; $i < count($arr_trails); $i++)
                {
                    $arr_subtrail = explode('~', $arr_trails[$i]);
                    if($arr_subtrail[0] == 'sm'){
                        $fsmax = $arr_subtrail[1];
                    }
                }
                $currentIndex = 0;
                $freeSpins = [8,16,32,64,128,256];
                for($k = 0; $k < 5; $k++){
                    if($freeSpins[$k] == $fsmax){
                        $currentIndex = $k;
                        break;
                    }
                }
                $ind = -1;
                if(isset($slotEvent['ind'])){
                    $ind = $slotEvent['ind'];
                }
                $arr_ch_h = [];
                if(isset($arr_g['bg']) && isset($arr_g['bg']['ch_h'])){
                    $arr_ch_h = explode(',', $arr_g['bg']['ch_h']);
                }
                $arr_ch_h[] = '0~' . $ind;
                if($ind == 1){
                    $isWinChance = $slotSettings->BonusWinChance($currentIndex, $betline * $lines);
                    if($isWinChance == true){
                        $currentIndex++;
                    }else{
                        $currentIndex = -1;
                    }
                    if($currentIndex == -1){
                        $fsmax = 0;
                    }else{
                        $fsmax = $freeSpins[$currentIndex];
                    }
                }else{
                    $fsmax = $freeSpins[$currentIndex];
                }
                $level++;
                $isState = false;
                $strOtherResponse = '';
                $spinType = 's';
                $coef = $betline * $lines;
                $end = 0;
                
                if($fsmax == 0 || $fsmax == 256 || $ind == 0){
                    if($fsmax > 0){                        
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 12);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                        
                        $stack = $slotSettings->GetReelStrips('bonus', $betline * $lines, -1, $fsmax);
                        if($stack == null){
                            $response = 'unlogged';
                            exit( $response );
                        }
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    }else{
                        $isState = true;
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                    $end = 1;
                    $str_trail = 'sm~' . $fsmax;
                }else{
                    $spinType = 'b';
                    $str_trail = 'sm~' . $fsmax . ';try~x2';
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'Level', $level);
                $slotSettings->SetGameData($slotSettings->slotId . 'Trail', $str_trail);
                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                {
                    $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1';
                }
                $new_arr_g = [
                    'bg' => [
                        'bgid' => '0',
                        'bgt' => '69',
                        'ch_h' => implode(',', $arr_ch_h),
                        'ch_k' => 'n,y',
                        'ch_v' => '0, 1',
                        'end' => '' . $end,
                        'rw' => '0.00'
                    ]
                ];
                $arr_g['bg'] = $new_arr_g['bg'];
                $slotSettings->SetGameData($slotSettings->slotId . 'G', $arr_g);
                $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($new_arr_g));
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse . '&balance='. $Balance .'&index='.$slotEvent['index'].'&balance_cash='. $Balance .'&balance_bonus=0.00&na='. $spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sver=5&counter='. ((int)$slotEvent['counter'] + 1);

                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"Level":' . $slotSettings->GetGameData($slotSettings->slotId . 'Level') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"Bl":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bl')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"Trail":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Trail') . '","G":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'G')) . ',"TumbleState":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') . ',"TumbWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin')  . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":""}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $pur_muls = [78, 150, 288, 128];
                    $allBet = $allBet * $pur_muls[$slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin')];
                }else if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') > 0){
                    $allBet = $betline * 28;
                }
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
    }
}

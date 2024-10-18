<?php 
namespace VanguardLTE\Games\_6JokersPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 5);
                $slotSettings->SetGameData($slotSettings->slotId . 'Bl', 0);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [5,7,7,4,3,8,6,10,11,11,6,11,3,7,3,5,5,8,6,10,4,4,6,10,3,11,9,3,5,8]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bl', $lastEvent->serverResponse->Bl);
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
                        $stack = $FreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') -1];
                    }
                    $bet = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $bet = '200.00';
                }
                $spinType = 's';
                $fsmore = 0;
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if($stack != null){
                    if($stack['win_line'] != ''){
                        $strWinLine = $stack['win_line'];
                        if($strWinLine != ''){
                            $arr_lines = explode('&', $strWinLine);
                            for($k = 0; $k < count($arr_lines); $k++){
                                $arr_sub_lines = explode('~', $arr_lines[$k]);
                                $arr_sub_lines[1] = str_replace(',', '', $arr_sub_lines[1]) / $original_bet * $bet;
                                $arr_lines[$k] = implode('~', $arr_sub_lines);
                            }
                            $strWinLine = implode('&', $arr_lines);
                        } 
                        $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                    }
                    $str_initReel = $stack['is'];
                    $str_stf = $stack['stf'];
                    $str_trail = $stack['trail'];
                    $str_psym = $stack['psym'];
                    $str_slm_mp = $stack['slm_mp'];
                    $str_slm_mv = $stack['slm_mv'];
                    $str_lmi = $stack['lmi'];
                    $str_lmv = $stack['lmv'];
                    $str_rs_p = $stack['rs_p'];
                    $str_rs_m = $stack['rs_m'];
                    $str_rs_c = $stack['rs_c'];
                    $str_rs_t = $stack['rs_t'];
                    $str_rs_more = $stack['rs_more'];
                    $str_rs_iw = $stack['rs_iw'];
                    $str_rs_win = $stack['rs_win'];
                    $str_rmul = $stack['rmul'];
                    $str_s_mark = $stack['s_mark'];
                    $wmv = $stack['wmv'];
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($str_stf != ''){
                        $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                    }
                    if($str_trail != ''){
                        $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                    }
                    if($str_psym != ''){
                        $arr_psym = explode('~', $str_psym);
                        $arr_psym[1] = str_replace(',', '', $arr_psym[1]) / $original_bet * $bet;
                        $str_psym = implode('~', $arr_psym);
                        $strOtherResponse = $strOtherResponse . '&psym=' . $str_psym;
                    }
                    if($str_slm_mv != ''){
                        $strOtherResponse = $strOtherResponse . '&slm_mp=' . $str_slm_mp . '&slm_mv=' . $str_slm_mv;
                    }
                    if($str_lmv != ''){
                        $strOtherResponse = $strOtherResponse . '&lmi=' . $str_lmi . '&lmv=' . $str_lmv;
                    }
                    if($str_rs_p != ''){
                        $strOtherResponse = $strOtherResponse . '&rs_p=' . $str_rs_p . '&rs_c=' . $str_rs_c . '&rs_m=' . $str_rs_m;
                    }
                    if($str_rs_t != ''){
                        $strOtherResponse = $strOtherResponse . '&rs_t=' . $str_rs_t;
                    }
                    if($str_rs_more != ''){
                        $strOtherResponse = $strOtherResponse . '&rs_more=' . $str_rs_more;
                    }
                    if($str_rs_win != ''){
                        $strOtherResponse = $strOtherResponse . '&rs_win=' . str_replace(',', '', $str_rs_win) / $original_bet * $bet;
                    }
                    if($str_rs_iw != ''){
                        $strOtherResponse = $strOtherResponse . '&rs_iw=' . str_replace(',', '', $str_rs_iw) / $original_bet * $bet;
                    }
                    if($str_rmul != ''){
                        $strOtherResponse = $strOtherResponse . '&rmul=' . $str_rmul;
                    }
                    if($str_s_mark != ''){
                        $strOtherResponse = $strOtherResponse . '&s_mark=' . $str_s_mark;
                    }
                    if($wmv > 0){
                        $strOtherResponse = $strOtherResponse . '&wmt=pr&wmv=' . $wmv;
                        if($wmv > 1){
                            $strOtherResponse = $strOtherResponse . '&gwm=' . $wmv;
                        }
                    }
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');

                }
                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=5,7,7,4,3,8,6,10,11,11,6,11,3,7,3,5,5,8,6,10,4,4,6,10,3,11,9,3,5,8&balance='. $Balance .'&cfgs=11995&ver=3&index=1&balance_cash='. $Balance .'&def_sb=4,8,5,6,3,8&reel_set_size=8&def_sa=3,8,11,5,5,11&reel_set=0&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0~0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0~1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1&rt=d&gameInfo={props:{max_rnd_win_a1:"3572",max_rnd_hr_a1:"45454545",max_rnd_win_a3:"893",max_rnd_win_a2:"1786",max_rnd_sim:"1",max_rnd_hr_a4:"2853881",max_rnd_hr_a3:"7112376",max_rnd_hr_a2:"17605634",max_rnd_hr:"156250000",max_rnd_win:"5000",max_rnd_win_a4:"417"}}&wl_i=tbm~5000;tbm_a1~3572;tbm_a2~1786;tbm_a3~893;tbm_a4~417&bl='. $slotSettings->GetGameData($slotSettings->slotId . 'Bl') .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=3,8,11,5,5,11&sb=4,8,5,6,3,8&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse . '&defc=200.00&sh=5&wilds=2~0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0~1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1&bonuses=0&st=rect&c='. $bet .'&sw=6&sver=5&bls=5,7,14,28,60&counter=2&ntp=0.00&paytable=0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;50,50,50,50,50,50,50,50,50,50,50,50,50,50,50,50,50,50,50,50,50,20,20,0,0,0,0,0,0,0;20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,12,12,0,0,0,0,0,0,0;15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,10,10,0,0,0,0,0,0,0;10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,7,7,0,0,0,0,0,0,0;6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,6,5,5,0,0,0,0,0,0,0;5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,4,4,0,0,0,0,0,0,0;4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,3,3,0,0,0,0,0,0,0;3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,2,2,0,0,0,0,0,0,0;2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,1,1,0,0,0,0,0,0,0&l=5&reel_set0=8,10,3,4,4,11,11,11,5,3,10,7,8,3,3,3,3,9,11,10,11,7,4,3,4,4,4,4,3,6,3,9,5,5,5,8,7,8,3,3,8,5,6,6,6,6,8,3,5,10,3,7,7,7,5,10,9,6,9,11,11,8,8,8,7,11,5,7,6,5,9,9,9,5,4,5,7,6,10,9,6~9,8,9,3,5,4,5,9,5,9,8,11,11,11,10,5,3,7,9,4,8,6,10,10,9,4,3,3,3,7,3,11,11,9,3,4,6,3,7,4,11,4,4,4,8,3,11,6,10,7,11,4,5,11,6,6,5,5,5,5,10,9,6,7,8,6,5,4,4,7,6,4,6,6,6,6,8,6,8,9,4,5,7,6,9,6,7,3,7,7,7,7,6,8,10,11,5,3,6,5,10,6,7,5,9,9,9,7,4,8,8,5,3,11,3,6,7,7,3,9~9,7,10,6,5,4,9,11,11,11,11,10,6,11,5,3,4,5,3,4,3,3,3,4,6,8,8,10,8,7,9,4,4,4,4,7,9,8,8,5,7,5,5,3,5,5,5,5,4,11,9,10,7,7,10,8,6,6,6,6,10,9,3,4,11,6,6,10,7,7,7,10,3,6,11,5,6,5,11,3,8,8,8,3,11,11,9,4,5,5,7,6,10,10,10,10,8,3,8,4,5,4,5,9,5,7~9,3,10,6,11,8,9,3,7,11,11,11,11,8,11,4,7,10,6,10,11,6,3,4,4,4,3,8,7,9,11,10,11,9,5,10,9,5,5,5,3,10,4,9,5,5,4,9,5,8,6,7,7,7,6,11,11,4,11,8,7,10,10,7,9,8,8,8,6,4,9,5,5,4,8,5,9,4,7,9,9,9,7,3,7,4,4,6,8,3,8,10,5,10,10,10,11,10,3,6,9,8,7,11,5,6,3,5~8,5,5,11,6,3,3,3,9,11,6,8,3,8,4,4,4,4,3,5,10,10,9,7,4,5,5,5,10,6,9,4,9,6,6,6,9,4,4,11,8,11,10,7,7,7,5,3,6,10,5,7,11,8,8,8,9,6,3,3,7,9,9,9,11,8,6,7,10,10,8,10,10,10,9,3,6,4,6,5,5,4~5,9,3,3,10,6,9,11,11,11,11,9,7,10,5,3,6,4,10,6,3,3,3,3,6,5,6,10,3,11,5,5,4,4,4,4,3,7,7,11,4,3,8,10,4,5,5,5,5,9,8,8,6,4,11,9,8,5,6,6,6,8,10,11,7,4,5,6,10,7,7,7,10,6,7,8,10,4,9,7,4,8,8,8,8,5,5,4,5,7,4,9,11,9,9,9,9,11,7,5,8,6,11,11,5,3,10,10,10,10,3,8,10,11,4,3,11,11,8,10&s='. $lastReelStr .'&reel_set2=7,11,7,10,6,3,8,10,7,11,9,9,7,6,8,9,10,5,9,11,11,11,11,9,6,9,10,5,11,10,9,3,4,11,9,5,7,4,11,6,5,6,11,3,3,3,7,4,9,5,7,6,10,6,10,4,11,5,5,11,3,9,7,8,7,3,9,9,9,9,10,10,5,10,8,6,6,9,7,11,10,10,7,3,9,10,9,5,9,10,10,10,10,8,8,11,8,11,11,10,8,9,7,9,11,8,3,10,8,8,10,10,4,4~11,8,11,7,5,5,10,6,10,7,3,9,11,5,4,4,6,11,11,11,11,10,7,5,10,4,10,11,8,11,6,8,10,9,7,9,8,5,9,9,4,4,4,8,11,6,6,9,5,11,3,9,11,10,7,11,9,10,9,10,4,10,10,10,10,11,3,11,11,6,9,9,11,4,6,4,10,9,8,5,11,4,10,8~7,3,11,10,8,9,5,4,11,7,6,11,6,11,11,11,11,8,3,9,5,5,10,6,11,10,6,8,4,9,10,9,9,9,9,8,10,11,4,11,11,9,5,11,10,9,7,11,10,10,10,4,8,9,11,5,9,11,5,9,10,9,11,8,7,7,6~9,5,6,3,9,8,10,10,4,11,10,3,11,11,11,7,8,10,4,9,11,7,4,8,11,10,11,8,8,8,11,11,7,7,9,8,10,8,5,8,7,7,3,9,9,9,11,6,6,10,4,9,11,9,7,9,7,5,6,10,10,10,10,11,6,8,11,5,9,9,10,5,3,10,9,10~11,3,6,8,11,4,4,11,9,5,7,6,6,11,11,11,11,8,6,7,11,8,8,6,11,10,3,10,5,7,11,9,4,4,4,9,11,11,4,11,10,9,9,10,10,7,10,8,11,10,10,10,11,4,5,10,11,11,9,11,3,10,7,7,5,9,9~9,9,11,3,9,11,4,8,10,11,4,11,10,8,11,9,6,11,8,3,9,4,10,3,11,11,11,6,8,5,10,8,10,6,6,8,5,9,9,6,10,7,7,11,9,11,9,10,11,9,8,5,9,9,9,7,10,10,8,9,4,9,6,9,3,7,10,7,11,7,7,5,9,5,6,6,7,4,10,10,10,8,9,11,8,4,11,4,9,8,7,11,9,8,9,9,3,10,9,3,11,6,10,11,4,10,5&reel_set1=7,7,9,11,3,9,10,6,8,11,11,11,11,11,4,4,9,8,8,11,6,6,4,9,7,5,5,5,10,6,7,4,9,11,8,8,9,7,8,6,6,6,5,10,5,6,6,11,5,11,10,9,9,7,7,7,7,8,9,8,10,6,3,6,11,7,11,7,8,8,8,9,5,9,7,9,7,11,9,5,4,10,9,9,9,11,3,5,4,10,10,11,9,8,3,5,10,10,10,3,9,11,10,7,10,11,6,6,11,5,4~10,3,8,6,8,8,11,6,10,7,10,11,11,11,11,8,8,7,5,7,11,9,9,10,9,11,6,6,6,11,10,5,10,10,9,10,3,9,3,10,4,5,8,8,8,8,6,7,3,7,6,4,5,8,6,8,8,9,9,9,9,3,4,9,11,11,7,9,5,11,8,8,7,9,10,10,10,10,5,9,9,10,6,6,4,9,7,7,11,11,5,4~10,10,8,6,9,5,7,8,11,11,11,6,11,9,9,10,11,9,8,3,7,7,7,9,11,5,3,8,5,4,6,4,7,8,8,8,7,9,10,4,11,9,3,6,11,9,9,9,9,4,6,6,10,10,9,11,3,5,10,10,10,10,6,9,8,4,10,9,8,10,9,5,7~4,6,11,6,11,11,10,11,9,11,5,9,9,6,11,3,5,11,11,11,11,7,9,7,9,8,10,10,9,4,4,9,10,8,6,7,8,6,4,8,8,8,6,5,10,10,6,10,11,8,11,7,4,8,5,7,8,3,11,10,9,9,9,9,10,11,9,11,6,6,10,3,8,6,5,8,8,7,5,3,11,10,10,10,10,10,5,9,3,8,4,11,5,10,9,3,10,11,9,9,7,5,8,10,11~9,11,7,3,7,7,5,11,5,11,9,6,6,3,7,9,8,11,11,11,11,6,8,8,9,8,4,11,11,10,10,9,8,11,9,9,7,11,10,6,6,6,11,6,6,8,6,10,5,6,5,8,4,4,10,8,9,8,8,7,6,9,9,9,3,9,8,9,11,5,7,5,5,7,7,3,7,9,11,9,7,11,10,10,10,10,7,6,10,6,10,10,4,9,4,10,10,4,3,9,5,10,11,4,11,10~4,10,8,10,9,8,9,11,11,3,8,3,11,11,8,11,11,11,11,6,7,10,7,9,10,8,5,4,7,10,10,8,10,9,6,7,7,7,9,5,9,5,10,11,6,5,11,11,5,11,4,7,9,7,8,8,8,8,5,9,11,9,7,7,3,8,11,7,10,3,11,8,10,10,9,9,9,6,11,6,8,6,7,9,10,9,10,9,8,11,8,6,11,7,10,10,10,11,6,10,6,6,4,3,11,11,9,9,11,6,10,9,5,3,6&reel_set4=5,3,9,6,6,9,6,6,7,9,5,6,5,7,9,9,3,7,6,3,6,5,7,9,5,5,7,9,6,5,7,3,3,6,7,7,5,6,9,9,6,7,3,5,6,9,9,5,6,7,7,9,9,6,6,5,3,9,5,6,9,7,6,9,3,5,5,3,6,6,3,9,6,3,5,5,6,7,7,5,9,5,9,5,7,6~10,7,10,11,11,7,11,4,7,10,10,7,11,10,4,11,3,11,7,3,4,10,3,10,7,10,7,4,7,3,11,4,10,4,10,10,11,7,11,10,3,7,11,11,10,3,11,4,7,11,10,7,11,7,10,11,3,10,11,10,10,11,4,10,4,11,11,10,11,3,7,10,3,4,3,4,10,11,11,4,10,11,4,7,10,4,10,4,7,11,11,4,7,3,11,3,7,10,4,4~7,11,6,7,6,7,6,11,11,7,9,6,7,9,9,7,9,9,11,6,7,11,6,6,11,9,11,4,11,6,11,9,7,11,7,11,4,11,9,11,7,6,9,11,9,9,7,7,4,6,11,6,6,7,9,7,7,9,9,6,4,11,9,7,9~8,4,5,10,5,4,4,10,5,5,8,8,5,4,8,8,10,10,8,5,10,7,5,8,5,4,10,7,8,4,4,5,8,8,10,7,5,4,5,4,8,10,4,8,10,8,8,10,8,10,4,4,8,10,5,4,4,7,4,10,8,5,5,7,4,4,10,4,7,8~6,6,11,3,6,3,8,11,10,6,6,10,6,3,10,6,11,6,3,8,6,10,8,3,10,6,3,6,3,10,10,6,6,3,10,11,10,3,8,11,8,10,10,6,6,3,8,6,3,11,6,8,8,3,10,8,3,10,6,3,10,8,10,3,8,8,3,11,3,8,8,3,11,8,6,6,3,10,3,8~5,11,4,4,9,9,5,11,11,8,8,9,5,11,4,8,11,4,5,9,4,11,11,5,5,9,8,4,5,5,9,11,5,8,9,8,9,8,11,8,4,9,9,8,5,5,11,5,4,11,8,8,9,11,5,5,8,11,9,4,8,9,8,9,9,11,4,4,9,9&reel_set3=11,10,9,4,7,11,8,7,8,11,11,11,11,9,10,9,10,8,4,9,10,10,9,5,7,7,7,3,11,9,8,8,9,7,10,8,7,10,8,8,8,8,8,5,6,7,6,9,9,11,8,3,7,9,9,9,9,9,9,3,11,6,11,8,9,9,5,10,11,9,10,10,10,10,10,7,11,3,10,10,11,4,4,11,5,6,8~3,10,6,7,11,11,11,11,10,9,4,4,8,11,10,4,4,4,8,10,4,7,8,3,7,5,5,5,5,11,5,9,10,7,9,6,6,6,11,5,8,11,9,8,4,7,7,7,7,6,11,9,9,10,11,3,8,8,8,8,8,6,6,10,6,5,5,11,9,9,9,9,9,10,8,9,6,9,10,8,10,10,10,10,9,6,10,7,9,11,11,8~9,10,8,7,11,10,11,9,7,9,4,7,11,11,11,11,11,11,6,8,11,8,11,9,8,9,4,9,7,10,11,4,4,4,9,7,4,10,11,11,9,9,7,9,8,11,10,7,7,7,6,8,10,10,5,11,5,9,11,9,10,9,9,8,8,8,10,5,11,10,3,11,11,4,8,11,11,10,10,9,9,9,9,8,11,10,4,11,10,5,3,6,7,4,8,4,10,10,10,10,10,8,5,5,11,7,11,10,10,7,6,6,9,10,11,6~9,9,5,7,4,5,6,11,11,11,11,10,6,11,10,10,8,9,8,9,8,5,5,5,7,11,9,11,11,7,11,7,10,7,7,7,5,9,10,7,6,6,11,7,11,11,8,8,8,9,8,8,7,3,8,9,10,9,6,9,9,9,9,9,11,7,9,4,10,5,6,6,5,10,10,10,10,10,3,9,3,10,9,5,3,10,10,4,10~3,8,6,4,7,9,11,11,11,11,11,7,5,6,10,4,7,10,3,3,3,6,9,6,9,11,10,10,6,6,6,8,6,10,5,8,6,11,7,7,7,7,5,7,11,11,9,11,8,8,8,6,3,10,4,5,10,8,9,9,9,11,5,6,10,11,10,10,10,10,10,11,8,8,10,11,7,9,4~11,11,7,7,10,5,6,11,11,11,11,6,5,9,5,10,11,9,8,6,6,6,9,4,10,8,9,10,8,10,7,7,7,11,4,11,7,4,8,8,10,8,8,8,8,8,8,5,11,5,8,10,9,6,5,9,9,9,9,9,4,10,8,7,11,10,11,8,10,10,10,10,10,10,3,10,9,11,10,6,8,11,6,8&reel_set6=6,2,10,4,4,11,10,10,5,3,7,6,10,11,11,11,8,7,9,9,5,10,7,5,8,4,8,5,4,7,6,6,6,8,6,11,11,8,11,11,6,2,5,6,7,9,11,7,7,7,11,4,7,9,8,8,9,9,2,11,10,11,9,10,8,8,8,9,8,11,9,8,4,6,4,8,10,7,2,11,2,9,9,9,2,4,7,2,3,9,9,8,6,6,11,10,8,10,10,10,5,6,7,7,3,3,7,9,2,6,6,10,10,5,5~7,3,6,10,11,2,11,3,9,11,8,3,4,8,6,11,11,10,7,2,10,11,2,8,8,8,10,9,10,4,5,10,3,11,2,9,8,5,8,2,10,9,9,5,10,4,11,8,10,6,9,9,9,8,6,7,10,9,2,8,5,6,7,10,4,8,11,5,10,5,9,11,8,7,7,11,7,8,10,10,10,5,8,6,9,11,7,9,7,8,3,8,7,4,2,6,9,6,6,11,8,5,2,4,4,10,8~8,5,10,8,4,4,7,9,9,2,11,10,2,8,6,6,6,8,9,5,8,11,6,6,10,11,3,9,3,9,9,7,9,10,9,9,9,6,7,6,8,3,2,4,5,5,2,8,11,9,3,11,10,10,10,4,5,7,5,7,7,2,11,10,6,6,2,11,11,7,9,9~5,10,10,11,9,11,4,7,11,4,4,4,7,10,5,9,6,9,11,5,4,2,5,5,5,8,7,4,2,7,9,10,6,9,7,6,6,6,2,8,11,2,11,9,9,3,7,3,7,7,7,5,8,6,6,8,10,8,6,10,5,3,9,9,9,5,7,10,2,11,9,3,3,10,10,10,8,6,4,5,4,8,8,5,9,7,6,2~4,7,11,4,10,8,10,8,2,9,9,11,11,11,4,6,6,11,5,4,10,9,4,2,9,9,10,4,4,4,10,3,5,8,10,8,7,6,7,6,9,3,7,11,5,5,5,8,3,2,10,5,3,2,8,5,4,2,6,6,7,7,7,5,5,3,4,8,11,6,11,7,8,2,10,4,8,8,8,10,11,5,11,8,7,9,2,10,7,9,11,2,9,9,9,11,2,5,4,6,10,9,8,3,7,5,7,5,7,11~9,4,2,7,11,8,4,5,9,11,2,10,7,11,5,3,8,10,4,8,2,4,2,7,7,7,2,10,7,11,5,6,5,8,6,10,7,8,8,10,3,8,5,10,5,9,6,11,2,5,7,8,8,8,7,9,11,6,11,10,2,7,10,2,11,9,2,7,9,11,6,9,10,11,6,4,8,9,5,10,10,10,3,10,10,9,3,11,7,6,3,6,6,4,10,10,8,6,11,10,4,8,9,10,8,3,10,7&reel_set5=10,8,6,7,5,10,8,3,3,6,7,11,11,11,7,6,7,11,9,8,7,10,9,10,6,9,8,5,5,5,9,8,5,4,11,4,3,8,4,6,9,8,7,7,7,4,8,6,7,4,9,5,5,7,5,11,3,9,8,8,8,5,9,9,7,2,11,11,4,11,5,11,2,9,9,9,4,10,6,10,11,9,5,6,6,10,10,2,7,3~9,6,7,5,10,11,3,11,8,11,11,11,9,4,11,7,10,2,5,10,8,5,10,5,5,5,8,11,7,7,6,7,11,9,4,11,10,7,7,7,6,9,9,7,6,7,8,4,6,8,7,8,8,8,7,11,4,11,4,2,3,7,2,8,7,9,9,9,3,11,10,7,8,8,5,5,6,7,10,10,10,9,8,9,9,10,5,11,3,11,11,7,5~11,11,2,5,11,8,7,5,9,6,11,6,11,11,11,9,10,8,7,6,4,6,10,2,7,7,8,7,7,7,11,8,11,6,11,9,3,5,3,11,10,7,9,9,9,10,8,7,9,11,5,10,5,4,9,10,7,3,10,10,10,8,4,5,11,8,9,2,10,9,6,4,3,10~6,11,8,7,10,6,9,5,11,11,11,4,7,2,9,6,11,2,9,6,3,3,3,7,9,11,8,7,10,11,4,6,9,4,4,4,6,9,4,5,9,2,3,11,5,5,5,3,7,7,3,8,10,8,10,9,8,8,8,10,6,3,3,8,10,11,6,7,6,9,9,9,5,7,10,8,8,5,11,4,6,10,10,10,3,11,10,2,10,11,9,7,9,8,10~4,5,10,11,8,3,7,7,11,7,10,4,3,3,11,9,11,11,11,7,2,6,8,10,7,5,4,8,10,6,11,7,10,11,10,9,8,5,5,5,9,8,10,10,5,11,7,9,2,10,10,7,5,3,7,11,3,7,7,7,11,11,10,11,5,8,8,6,10,3,11,2,4,9,5,11,7,9,10,10,10,2,5,7,4,10,10,6,9,8,3,6,11,9,9,8,11,5,11,9~6,8,11,8,9,8,4,4,4,6,3,8,10,3,10,7,3,5,5,5,2,3,9,8,10,3,11,6,6,6,4,4,10,11,4,6,2,7,7,7,10,8,7,8,6,11,4,4,8,8,8,11,5,7,8,7,10,10,9,9,9,5,7,10,5,6,8,9,6,10,10,10,7,11,9,5,4,11,5,9,5&reel_set7=11,6,11,7,6,10,6,3,3,3,9,5,7,5,4,9,7,11,5,4,4,4,5,6,6,11,10,3,10,7,6,5,5,5,7,11,4,8,7,3,9,8,7,7,7,5,3,8,4,4,10,11,9,6,8,8,8,2,5,5,9,10,8,7,4,9,9,9,4,9,7,4,8,3,10,3,11,7~11,5,5,8,3,11,11,11,9,5,11,4,9,3,3,3,6,11,9,8,7,4,4,4,6,7,9,4,6,7,7,5,5,5,10,10,6,4,2,6,6,6,8,8,7,9,2,8,7,7,7,5,8,11,10,5,9,6,8,8,8,10,3,10,4,3,11,9,9,9,4,7,5,8,10,5,10,10,10,9,6,7,4,6,7,3,11~6,8,5,9,6,9,9,7,3,11,11,11,4,11,7,3,10,6,9,6,3,11,3,3,3,11,7,10,3,10,11,10,2,9,8,5,5,5,8,7,5,6,8,8,9,9,4,5,6,6,6,4,9,11,3,4,11,6,11,6,10,7,7,7,10,9,7,6,6,5,6,8,10,3,9,9,9,5,5,11,4,9,4,7,9,7,10,10,10,11,11,8,5,8,7,11,5,7,9,10~4,3,5,5,6,8,3,11,11,11,4,9,7,10,11,3,8,7,7,3,3,3,6,9,5,3,8,9,8,9,11,4,4,4,3,6,4,7,6,6,3,11,9,5,5,5,10,10,3,5,7,10,8,11,11,6,6,6,9,9,6,9,6,6,11,9,7,7,7,10,11,6,10,6,10,7,8,4,8,8,8,3,4,7,4,8,11,4,11,7,9,9,9,8,9,5,10,10,6,2,5,10,10,10,5,3,11,8,6,7,6,7,8,5~10,5,2,3,8,4,8,10,4,5,4,7,11,11,11,7,3,9,10,9,10,11,9,3,2,7,11,10,11,4,4,4,7,6,8,3,10,7,3,11,3,6,7,10,8,9,5,5,5,10,6,4,6,4,9,8,9,9,6,11,7,10,4,9,6,6,6,10,7,5,5,6,7,10,7,8,5,10,4,6,7,7,7,5,4,8,9,4,5,5,3,8,9,11,6,11,6,10,10,10,8,10,11,9,5,8,5,10,5,7,3,11,11,4,10~5,4,10,9,3,10,6,3,9,8,11,3,3,3,8,3,9,7,4,7,5,10,10,6,11,9,4,4,4,10,10,5,6,11,8,7,9,6,11,4,5,7,5,5,5,6,8,8,3,7,4,11,3,10,7,5,3,6,6,6,11,7,5,3,11,5,4,3,11,4,10,6,8,8,8,7,4,8,5,10,11,7,6,4,8,3,9,5,9,9,9,8,10,4,8,4,8,9,3,11,6,8,6,10,10,10,2,6,4,5,7,8,6,11,10,3,9,4,9,11';
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
                if($winType == 'bonus'){
                    $winType = 'win';
                }
                // $winType = 'bonus';
                $pur_mul = [5,7,14,28,60];
                $allBet = $betline * $lines;
                if($bl > 0){
                    $allBet = $betline * $pur_mul[$bl];
                }
                $tumbAndFreeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'freespin' || $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') >= 0){
                    if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') == -1){
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
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $_sum, $slotEvent['slotEvent']);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bl', $bl);
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
                $specialScatter = '11';
                $scatter = '1';
                $Balance = $slotSettings->GetBalance();
                $totalWin = 0;
                $bonusMpl = 1;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $str_stf = '';
                $str_initReel = '';
                $str_trail = '';
                $str_paym = '';
                $str_slm_mp = '';
                $str_slm_mv = '';
                $str_lmi = '';
                $str_lmv = '';
                $str_rs_p = '';
                $str_rs_m = '';
                $str_rs_c = '';
                $str_rs_t = '';
                $str_rs_more = '';
                $str_rs_iw = '';
                $str_rs_win = '';
                $str_rmul = '';
                $str_s_mark = '';
                $wmv = 0;
                $lastReel = null;
                $scatterCount = 0;
                if($slotEvent['slotEvent'] == 'freespin' || $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') >= 0){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $strWinLine = $stack['win_line'];
                    $str_initReel = $stack['is'];
                    $str_stf = $stack['stf'];
                    $str_trail = $stack['trail'];
                    $str_psym = $stack['psym'];
                    $str_slm_mp = $stack['slm_mp'];
                    $str_slm_mv = $stack['slm_mv'];
                    $str_lmi = $stack['lmi'];
                    $str_lmv = $stack['lmv'];
                    $currentReelSet = $stack['reel_set'];
                    $str_rs_p = $stack['rs_p'];
                    $str_rs_m = $stack['rs_m'];
                    $str_rs_c = $stack['rs_c'];
                    $str_rs_t = $stack['rs_t'];
                    $str_rs_more = $stack['rs_more'];
                    $str_rs_iw = $stack['rs_iw'];
                    $str_rs_win = $stack['rs_win'];
                    $str_rmul = $stack['rmul'];
                    $str_s_mark = $stack['s_mark'];
                    $wmv = $stack['wmv'];
                    $str_sa = $stack['sa'];
                    $str_sb = $stack['sb'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines, $bl);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $lastReel = explode(',', $stack[0]['reel']);
                    $strWinLine = $stack[0]['win_line'];
                    $str_initReel = $stack[0]['is'];
                    $str_stf = $stack[0]['stf'];
                    $str_trail = $stack[0]['trail'];
                    $str_psym = $stack[0]['psym'];
                    $str_slm_mp = $stack[0]['slm_mp'];
                    $str_slm_mv = $stack[0]['slm_mv'];
                    $str_lmi = $stack[0]['lmi'];
                    $str_lmv = $stack[0]['lmv'];
                    $currentReelSet = $stack[0]['reel_set'];
                    $str_rs_p = $stack[0]['rs_p'];
                    $str_rs_m = $stack[0]['rs_m'];
                    $str_rs_c = $stack[0]['rs_c'];
                    $str_rs_t = $stack[0]['rs_t'];
                    $str_rs_more = $stack[0]['rs_more'];
                    $str_rs_iw = $stack[0]['rs_iw'];
                    $str_rs_win = $stack[0]['rs_win'];
                    $str_rmul = $stack[0]['rmul'];
                    $str_s_mark = $stack[0]['s_mark'];
                    $wmv = $stack[0]['wmv'];
                    $str_sa = $stack[0]['sa'];
                    $str_sb = $stack[0]['sb'];
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
                $spinType = 's';
                if( $totalWin > 0) 
                {
                    $spinType = 'c';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }
                $_obf_totalWin = $totalWin;
                $isState = true;
                
                $strLastReel = implode(',', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                if($str_rs_p != '')
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', 1);
                    $spinType = 's';
                    $isState = false;
                }
                else
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                    if($str_rs_t != ''){
                        $spinType = 'c';
                    }
                }
                $strOtherResponse = '';
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($str_stf != ''){
                    $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                }
                if($str_trail != ''){
                    $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                }
                if($str_psym != ''){
                    $strOtherResponse = $strOtherResponse . '&psym=' . $str_psym;
                }
                if($str_slm_mv != ''){
                    $strOtherResponse = $strOtherResponse . '&slm_mp=' . $str_slm_mp . '&slm_mv=' . $str_slm_mv;
                }
                if($str_lmv != ''){
                    $strOtherResponse = $strOtherResponse . '&lmi=' . $str_lmi . '&lmv=' . $str_lmv;
                }
                if($str_rs_p != ''){
                    $strOtherResponse = $strOtherResponse . '&rs_p=' . $str_rs_p . '&rs_c=' . $str_rs_c . '&rs_m=' . $str_rs_m;
                }
                if($str_rs_t != ''){
                    $strOtherResponse = $strOtherResponse . '&rs_t=' . $str_rs_t;
                }
                if($str_rs_more != ''){
                    $strOtherResponse = $strOtherResponse . '&rs_more=' . $str_rs_more;
                }
                if($str_rs_win != ''){
                    $strOtherResponse = $strOtherResponse . '&rs_win=' . str_replace(',', '', $str_rs_win) / $original_bet * $betline;
                }
                if($str_rs_iw != ''){
                    $strOtherResponse = $strOtherResponse . '&rs_iw=' . str_replace(',', '', $str_rs_iw) / $original_bet * $betline;
                }
                if($str_rmul != ''){
                    $strOtherResponse = $strOtherResponse . '&rmul=' . $str_rmul;
                }
                if($str_s_mark != ''){
                    $strOtherResponse = $strOtherResponse . '&s_mark=' . $str_s_mark;
                }
                if($wmv > 0){
                    $strOtherResponse = $strOtherResponse . '&wmt=pr&wmv=' . $wmv;
                    if($wmv > 1){
                        $strOtherResponse = $strOtherResponse . '&gwm=' . $wmv;
                    }
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&bl='. $slotSettings->GetGameData($slotSettings->slotId . 'Bl') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$str_sa.'&sb='.$str_sb.'&sh=5&c='.$betline.'&sw=6&sver=5&counter='. ((int)$slotEvent['counter'] + 1) . '&w=' . $totalWin .'&l=5&s=' . $strLastReel;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if( $slotEvent['slotEvent'] != 'freespin' && $scatterCount >= 3) 
                {
                    // $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"CurrentRespin":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"Bl":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bl') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'Bl') > 0){
                    $allBet = $betline * $pur_mul[$slotSettings->GetGameData($slotSettings->slotId . 'Bl')];
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

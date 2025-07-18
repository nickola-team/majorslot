<?php 
namespace VanguardLTE\Games\PirateGoldenAgePM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [6,10,11,5,9,7,8,7,7,10,5,10,10,6,9]);
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
                    $bet = '50.00';
                }
                $spinType = 's';
                $arr_g = null;
                $moneyWin = 0;
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if(isset($stack)){
                    $str_initReel = $stack['initreel'];
                    $currentReelSet = $stack['reel_set'];
                    $fsmore = $stack['fsmore'];
                    $str_accm = $stack['accm'];
                    $str_acci = $stack['acci'];
                    $str_accv = $stack['accv'];
                    $str_stf = $stack['stf'];
                    $str_lmi = $stack['lmi'];
                    $str_lmv = $stack['lmv'];
                    $str_slm_mp = $stack['slm_mp'];
                    $str_slm_mv = $stack['slm_mv'];
                    $strWinLine = $stack['win_line'];
                    
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
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
                    if($str_lmv != ''){
                        $strOtherResponse = $strOtherResponse . '&lmi=' . $str_lmi . '&lmv=' . $str_lmv;
                    }
                    if($str_slm_mv != ''){
                        $strOtherResponse = $strOtherResponse . '&slm_mp=' . $str_slm_mp . '&slm_mv=' . $str_slm_mv;
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
                            $strOtherResponse = $strOtherResponse . '&fs_total='.($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 1).'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsend_total=1&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        }
                        else
                        {
                            $fs = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                            $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $fs .'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&w=0.00';
                        }
                        if($fsmore > 0){
                            $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                        }     
                    }
                }
                
                $Balance = $slotSettings->GetBalance();  
                $response = 'def_s=6,10,11,5,9,7,8,7,7,10,5,10,10,6,9&balance='. $Balance .'&cfgs=6490&ver=3&index=1&balance_cash='. $Balance .'&def_sb=4,11,6,4,10&reel_set_size=5&def_sa=4,10,3,7,3&reel_set='. $currentReelSet .'&balance_bonus=0.00&na=s&scatters=1~200,20,2,0,0~0,0,0,0,0~1,1,1,1,1&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"1984126",max_rnd_win:"3000"}}&wl_i=tbm~3000&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime='. floor(microtime(true) * 1000) .'&sa=4,10,3,7,3&sb=4,11,6,4,10&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=50.00&sh=3&wilds=2~1500,400,125,0,0~1,1,1,1,1&bonuses=0&st=rect&c='. $bet .'&sw=5&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;1500,400,125,0,0;1000,250,80,0,0;600,150,50,0,0;400,100,30,0,0;250,40,20,0,0;125,35,12,0,0;60,20,6,0,0;60,20,6,0,0;30,10,3,0,0;30,10,3,0,0;0,0,0,0,0;0,0,0,0,0&l=20&reel_set0=12,9,12,12,8,7,2,10,12,10,8,8,9,8,1,12,8,8,12,9,9,1,9,8,11,11,12,10,11,6,12,2,6,11,11,1,6,8,12,11,8,12,3,10,7,12,6,2,12,5,10,2,8,11,4,1,9,7,11,5,8,12,10,5,10,10,12,2,2,2,10,7,1,3,12,7,9,10,4,9,10,11,10,6,12,7,12,9,9,6,11,5,10,10,8,11,11,8,9,5,5,12,8,10,4,4,11,9,11,8,7,5,6,12,12,11,6,10,11,4,11,11,7,9,11,9,7,9,11,7,12,6,9,11,11,8,12~9,7,9,9,2,12,9,11,12,4,6,8,12,8,11,8,2,5,7,12,10,6,7,4,8,10,1,10,12,10,12,9,10,3,11,8,11,11,12,8,12,8,8,7,10,6,5,11,9,12,12,8,10,10,2,2,2,12,11,3,11,8,2,9,8,10,8,12,8,8,9,12,7,7,10,4,8,12,12,11,10,7,7,10,9,11,11,10,1,12,9,8,6,12,7,12,8,3,12,12,11,12,5,9,9,1,11,5,2,4,12,12,5,9~2,2,10,6,3,10,9,9,11,12,11,2,6,10,4,10,5,11,11,12,4,7,5,9,6,11,5,7,9,9,11,11,8,12,9,11,5,6,11,4,11,12,12,11,2,9,8,11,9,9,6,12,5,11,7,12,1,9,3,11,8,11,10,2,7,12,6,9,1,12,8,11,12,2,2,2,10,11,11,10,8,11,11,9,6,7,6,10,7,12,11,12,7,12,9,10,9,8,10,8,12,11,12,10,12,9,10,12,7,11,9,12,6,10,8,10,7,6,11,10,11,10,4,10,11,9,9,10,8,11,1,11,1,8,8,10,9,5,11,7,3,12,11,7,11,11,5,3,11,6,11~9,10,5,11,10,12,11,9,5,10,8,10,2,12,8,11,12,6,4,7,1,11,5,9,4,10,8,7,7,1,11,12,1,7,7,6,9,6,9,12,2,8,7,2,7,4,6,8,2,6,5,2,2,2,9,12,9,2,10,2,5,11,4,9,8,12,10,7,11,3,9,7,7,8,12,8,10,2,6,3,3,9,3,10,6,8,4,11,9,3,5,6,12,9,6,8,12,12,6,10,1,11,5,1,7,11,11,9~4,10,10,8,10,2,7,9,11,7,5,1,7,9,7,6,9,2,11,7,5,9,6,8,8,6,10,12,8,6,5,6,8,9,12,10,6,6,9,11,6,11,6,8,12,8,11,5,4,8,12,5,11,11,12,12,6,11,2,8,10,7,5,12,11,2,2,2,12,11,12,6,8,12,6,7,10,4,7,7,12,7,6,10,11,12,12,11,8,9,3,1,10,8,5,7,8,2,9,4,3,2,8,7,4,7,9,10,2,12,11,3,11,1,4,11,5,5,2,6,10,9,9,6,5,7,12,7,9,5,5,10,10,9,8,5&s='.$lastReelStr.'&accInit=[{id:0,mask:"smc;tp;cp"}]&reel_set2=12,13,10,10,12,8,6,12,7,11,8,9,5,12,7,13,11,9,9,7,2,11,9,11,8,10,12,10,11,12,12,9,9,11,6,12,6,9,6,12,11,6,11,10,6,11,5,11,4,8,10,7,12,10,7,11,10,9,5,8,7,12,9,4,12,8,5,11,9,8,10,8,7,8,7,12,5,11,10,7,11,7,12,3,3,11,12,10,10,5,8,8,10,12,11,10,8,9,11,9,6,11,7,8,11,12,6,12,11,5,8,12,9,5,10,12,4,9,8,8,9,9,10,10,9,12,9,12,11,12,12,11,13,5,2,8,8,6,11,11,6,11,13,13,4,11,12,12,7,4,10~4,11,10,11,3,9,5,4,8,5,7,10,10,13,9,10,3,6,8,10,6,8,12,11,10,8,8,12,12,8,11,12,7,11,9,9,8,12,9,12,12,6,11,11,12,7,12,12,13,10,12,10,5,9,2,9,13,6,12,8,9,7,4,13,10,11,9,8,8,9,9,6,12,3,8,12,3,10,5,7,7,8,12,11,13,12,8,11,10,4,8,11,8,9,5,12,7,11,2,5,10,4,10,8,8,3,7,10,12,8,9,12,10,9,10,11,12,6,9,13,12,11,8,7,12,12,9,4,11,10,5,7,8,8,12,5,7,9,10,8,12,12,9,12,7,12,11,12~9,7,10,6,12,11,11,6,10,12,7,12,11,2,10,4,9,9,11,13,8,9,7,12,10,11,9,6,8,11,12,5,8,11,5,12,11,11,12,9,6,6,4,3,10,5,9,11,9,13,11,10,7,6,11,11,5,12,12,8,8,11,6,10,7,11,12,9,10,11,13,12,6,3,11,13,10,10,9,11,4,9,11,8,11,11,7,11,10,10,8,7,8,12,5,9,3,7~10,9,10,13,11,8,9,12,7,8,12,2,5,5,7,9,11,13,10,6,7,6,12,7,12,9,6,7,13,11,5,3,8,12,4,11,6,9,4,11,3,6,8,10,9,10,11,5,8~12,7,6,10,11,11,6,5,12,13,12,10,9,8,7,11,8,2,4,9,9,10,11,11,7,6,4,11,5,13,9,10,7,5,7,6,6,8,9,12,12,13,5,6,3,5,8,12,8,8,7,10&reel_set1=11,7,6,10,6,10,13,8,12,5,11,11,6,12,8,9,10,8,9,6,12,9,13,11,11,7,11,9,5,11,7,2,9,12,12,5,12,12,10,8,11,4,12,10,8,11,8,11,10,9,3,13,4,12,7,8,12,13,10~10,5,13,5,12,8,4,9,9,4,11,10,3,9,10,10,12,10,9,8,9,4,11,13,8,8,12,8,7,10,11,13,7,11,12,4,10,8,6,6,11,13,10,12,12,8,11,12,11,12,7,5,12,8,11,9,13,6,10,12,9,9,10,12,12,7,5,12,11,12,12,9,8,9,4,7,12,2,12,2,8,8,6,13,7,12,9,12,8,12,7,9,12,5,9,12,9,11,12,10,10,13,7,12,7,12,8,12,10,7,8,8,11,10,12,10,5,12,8,8,5,9,11,11,10,3,11,8,3,9,13,12,8,7,13,12,9,9,8,8,13,6,10,8,10,7,11,11,12,8,3~13,11,7,4,11,11,10,9,12,9,3,12,6,10,13,12,12,11,7,10,5,11,8,8,6,11,6,11,5,4,11,10,3,11,8,12,7,6,13,9,11,5,13,9,10,12,11,10,7,2,9,9,11,8~10,12,12,2,11,8,3,12,5,8,9,10,3,9,12,7,11,13,11,4,7,5,13,6,9,11,9,6,8,4,10,6,13,7,7~6,7,5,10,6,8,5,4,5,12,6,3,12,11,10,6,12,9,8,9,4,8,7,8,8,13,9,13,11,9,9,11,3,5,11,2,5,7,9,4,5,13,10,8,6,7,7,12,12,6,10,7,11,10,13,12,10,13,11&reel_set4=11,8,2,5,12,9,12,5,11,12,10,9,11,7,10,3,2,10,11,12,9,8,9,11,10,10,6,3,12,10,11,12,4,12,8,6,6,12,4,10,11,10,7,11,6,10,4,11,12,8,11,12,11,12,9,8,8,2,12,3,2,8,9,8,9,4,8,5,12,5,8,2,10,9,11,5,9,10,10,12,2,2,2,12,7,8,10,11,5,7,6,10,11,10,7,11,10,11,8,8,10,7,9,8,2,7,6,9,12,2,5,12,3,11,8,9,11,12,6,12,10,9,11,11,4,11,5,9,9,12,11,11,12,6,9,8,6,12,5,11,12,11,9,4,12,8,4,12,7,12,11,8,9,12,9,10,7,12,7,8,7,11,12,6,7~2,10,10,3,10,12,10,7,12,11,9,7,10,4,8,11,8,6,10,9,10,9,7,8,9,12,12,8,11,9,8,12,4,7,10,12,8,7,8,7,10,3,8,8,10,8,12,2,8,10,12,11,11,10,9,12,9,6,11,12,8,4,12,7,8,7,2,2,2,6,12,12,5,12,6,12,8,10,12,4,11,12,8,12,12,5,12,11,10,11,12,7,9,12,4,11,2,12,12,2,3,4,6,5,9,11,8,11,11,10,12,5,8,2,12,9,9,11,11,9,8,9,8,5,12,9,11,7,9,11,8,9,5,12,8,10,5~11,9,6,12,11,7,2,10,6,3,4,10,9,7,9,10,6,2,9,7,2,3,9,11,11,8,12,9,11,5,8,12,7,7,10,12,12,11,12,10,10,8,9,7,12,8,8,6,6,11,9,11,11,8,2,6,8,4,11,11,10,10,9,9,8,10,10,11,6,11,2,2,2,12,5,11,12,12,7,11,11,9,11,11,12,11,8,10,6,11,10,7,10,4,11,11,3,9,12,7,6,11,12,11,11,5,9,3,8,11,12,10,12,9,2,11,11,3,9,9,5,11,10,11,12,5,7,12,10,11,8,11,9,6,4,5,10,11,6,12,4,7,10,9,11~7,8,5,10,4,4,11,12,11,12,6,11,7,12,3,7,9,11,2,11,10,2,12,6,10,9,3,12,8,10,11,5,9,2,9,7,3,8,9,9,7,9,11,7,12,7,6,6,4,6,5,2,2,2,7,8,5,2,9,7,11,10,8,4,12,11,8,6,9,10,9,2,11,5,4,2,12,9,7,5,10,8,8,10,11,12,8,5,6,9,10,12,6,8,3,11,12,6,7,9,10,3,9,12,8,7,11,6~8,9,5,7,11,12,6,12,2,11,5,12,12,11,9,8,12,10,7,3,11,6,6,7,8,4,10,8,2,7,8,8,5,12,9,6,8,6,7,9,12,10,9,5,8,3,9,6,9,5,10,11,10,9,11,5,6,7,11,4,7,6,5,2,2,2,10,11,7,8,11,10,10,5,6,9,8,9,6,8,11,5,9,11,7,11,7,12,5,10,8,6,10,12,4,6,7,8,11,12,7,4,7,12,9,2,9,12,4,10,7,2,4,6,5,8,7,12,5,2,12,2,11,11,3,5,6,12,10,6,8,10,10&reel_set3=3,6,1,10,1,8,12,1,7,1,2,8,1,4,7,2,1,11,3,1,12,10,1,5,2,12,2,7,1,10,4,5,6,1,9,11,12,1,8,9,11,1~10,1,2,4,5,3,1,10,1,6,1,9,3,1,12,1,5,8,1,11,1,12,6,7,1,8,4,12,2,11,7~1,12,9,3,10,1,4,1,12,5,4,11,1,2,1,3,10,11,1,6,10,5,8,7,1,6,1,12,4,1,9,2,8,1,3~11,9,1,2,1,4,8,3,11,1,7,5,1,6,8,10,1,2,1,10,12,9,7,6,5,7,12,3,1,10,12,1,11~6,1,2,7,1,12,1,8,12,4,9,1,10,12,11,4,10,6,7,1,7,5,2,1,2,8,1,8,9,11,3,1,5,11,1,10,1';
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
                $slotEvent['slotBet'] = $slotEvent['c'];
                $slotEvent['slotLines'] = 20;
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
                $allBet = $betline * $lines;
                $tumbAndFreeStacks = []; 
                $isGeneratedFreeStack = false;
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
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
                $reels = [];
                $fsmore = 0;
                $str_accm = '';
                $str_acci = '';
                $str_accv = '';
                $str_stf = '';
                $str_lmi = '';
                $str_lmv = '';
                $str_slm_mp = '';
                $str_slm_mv = '';
                $scatterCount = 0;
                $scatterWin = 0;
                $scatterPoses = [];
                if($slotEvent['slotEvent'] == 'freespin'){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $lastReel = explode(',', $stack['reel']);
                    $str_initReel = $stack['initreel'];
                    $currentReelSet = $stack['reel_set'];
                    $fsmore = $stack['fsmore'];
                    $str_accm = $stack['accm'];
                    $str_acci = $stack['acci'];
                    $str_accv = $stack['accv'];
                    $str_stf = $stack['stf'];
                    $str_lmi = $stack['lmi'];
                    $str_lmv = $stack['lmv'];
                    $str_slm_mp = $stack['slm_mp'];
                    $str_slm_mv = $stack['slm_mv'];
                    $strWinLine = $stack['win_line'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines);
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
                    $str_accm = $stack[0]['accm'];
                    $str_acci = $stack[0]['acci'];
                    $str_accv = $stack[0]['accv'];
                    $str_stf = $stack[0]['stf'];
                    $str_lmi = $stack[0]['lmi'];
                    $str_lmv = $stack[0]['lmv'];
                    $str_slm_mp = $stack[0]['slm_mp'];
                    $str_slm_mv = $stack[0]['slm_mv'];
                    $strWinLine = $stack[0]['win_line'];
                }
                for($i = 0; $i < 15; $i++){
                    if($lastReel[$i] == 1){
                        $scatterCount++;
                        $scatterPoses[] = $i;
                    }
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
                if($scatterCount >= 3){
                    $scatterMuls = [0,0,0,2,20,200];
                    $scatterWin = $betline * $lines * $scatterMuls[$scatterCount];
                    $totalWin = $totalWin + $scatterWin;
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
                if($scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin'){
                    // $freeSpins = [0,0,0,0,10,15,20];
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 7);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                }else if($fsmore > 0){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                }
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 5; $i++){
                    $reelA[$i] = mt_rand(5, 10);
                    $reelB[$i] = mt_rand(5, 10);
                }
                $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
                $strLastReel = implode(',', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                $strOtherResponse = '';                
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $spinType = 's';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $isEnd = false;
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                    {
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsend_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 'c';
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
                    if($scatterCount >= 3){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1&psym=1~'. $scatterWin .'~' . implode(',', $scatterPoses);
                    }
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
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
                if($str_lmv != ''){
                    $strOtherResponse = $strOtherResponse . '&lmi=' . $str_lmi . '&lmv=' . $str_lmv;
                }
                if($str_slm_mv != ''){
                    $strOtherResponse = $strOtherResponse . '&slm_mp=' . $str_slm_mp . '&slm_mv=' . $str_slm_mv;
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse  .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance . '&reel_set='. $currentReelSet.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&st=rect&c='.$betline.'&sw=5&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&w='.$totalWin.'&s=' . $strLastReel;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if( $slotEvent['slotEvent'] != 'freespin' && $scatterCount >= 3) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin'));
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"CurrentRespin":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
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

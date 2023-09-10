<?php 
namespace VanguardLTE\Games\BlackBullPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [4,9,11,4,11,6,10,9,3,9,5,11,3,4,7,4,10,9,5,9]);
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
                if(isset($stack)){
                    $str_accm = $stack['accm'];
                    $str_acci = $stack['acci'];
                    $str_accv = $stack['accv'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $str_mo_wpos = $stack['mo_wpos'];
                    $mo_tv = $stack['mo_tv'];
                    $mo_m = $stack['mo_m'];
                    $fsmore = $stack['fsmore'];
                    $fsmax = $stack['fsmax'];
                    $strWinLine = $stack['win_line'];
                    $currentReelSet = $stack['reel_set'];
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    
                    if($str_accv != ''){
                        $strOtherResponse = $strOtherResponse . '&accm='.$str_accm.'&acci=' . $str_acci . '&accv=' . $str_accv;
                    }
                    if($str_mo != ''){
                        $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                    }
                    if($mo_tv > 0){
                        $moneyWin = $mo_tv * $bet;
                        if($mo_m > 0){
                            $moneyWin = $moneyWin * $mo_m;
                        }
                        $strOtherResponse = $strOtherResponse . '&mo_tv=' . $mo_tv . '&mo_c=1&mo_wpos=' . $str_mo_wpos. '&mo_tw=' . $moneyWin;
                    }
                    if($mo_m > 0){
                        $strOtherResponse = $strOtherResponse . '&mo_m=' . $mo_m;
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
                            $strOtherResponse = $strOtherResponse . '&fs_total='.($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 1).'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsend_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
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
                $response = 'def_s=4,9,11,4,11,6,10,9,3,9,5,11,3,4,7,4,10,9,5,9&balance='. $Balance .'&cfgs=6101&ver=3&mo_s=12&index=1&balance_cash='. $Balance .'&mo_v=10,20,30,40,60,80,100,150,200,400,1000&def_sb=6,11,7,6,11&reel_set_size=10&def_sa=4,8,7,3,8&reel_set=0&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"1140696",max_rnd_win:"4500"}}&wl_i=tbm~4500&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=4,8,7,3,8&sb=6,11,7,6,11&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=50.00&purInit_e=1&sh=4&wilds=2~500,150,50,0,0~1,1,1,1,1&bonuses=0&st=rect&c='.$bet.'&sw=5&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;500,150,50,0,0;200,75,40,0,0;150,45,30,0,0;80,40,20,0,0;80,40,20,0,0;40,15,8,0,0;40,15,8,0,0;40,15,8,0,0;40,15,8,0,0;0,0,0,0,0&l=20&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=7,11,10,9,11,11,6,2,10,10,10,6,8,11,10,7,6,10,8,11,11,9,9,9,3,11,8,4,10,2,9,7,10,8,8,8,9,8,10,6,8,6,11,9,6,9,10,12,12,12,12,5,4,9,9,10,12,12,9,10,10,9,11,11,11,11,10,6,5,11,11,7,6,9,10,6,6,6,7,8,12,9,8,10,6,10,2,8,10,2,2,2,2,8,9,8,9,8,8,9,10,4,9,3,3,3,10,8,10,3,11,11,9,9,7,10,9,7,7,7,3,9,10,9,5,3,5,10,4,11,6,11~11,4,11,11,6,5,9,7,2,5,5,5,9,3,3,9,10,8,3,8,4,5,6,6,6,5,4,8,6,7,9,9,5,10,6,9,12,12,12,12,8,4,7,8,5,4,5,11,9,9,4,4,4,8,3,11,10,2,9,2,12,6,5,10,3,3,3,8,10,8,12,3,10,4,7,10,9,2,2,2,2,10,5,9,8,5,9,11,10,10,9,6,9,9,9,8,7,8,4,10,9,8,4,4,11,11,11,9,10,4,5,4,9,8,4,6,6,8,8,8,11,7,12,6,7,11,3,12,5,9,7,7,7,11,6,12,4,3,11,8,8,4,5,8,6~10,12,7,7,4,8,6,7,11,11,11,4,8,9,8,6,2,5,3,4,11,7,7,7,9,5,7,8,3,7,8,7,9,11,8,6,6,6,8,3,5,10,10,9,8,7,6,7,11,12,12,12,12,11,8,7,6,11,5,7,9,10,10,11,8,8,8,2,11,9,8,7,10,9,3,2,10,2,2,2,2,11,9,5,11,11,10,9,6,7,10,8,3,3,3,5,5,11,10,7,6,4,11,3,9,10,10,10,12,6,8,11,10,6,11,8,3,8,4,9,9,9,12,8,10,4,8,10,7,7,10,5,5~9,7,7,5,4,5,11,9,11,11,11,9,11,7,9,6,7,7,9,10,6,7,7,7,9,5,11,9,2,4,3,11,10,7,4,4,4,6,9,7,9,4,3,4,8,5,12,12,12,12,12,6,9,8,8,9,12,4,3,4,6,3,3,3,11,10,4,3,7,3,8,6,5,12,7,9,9,9,6,3,7,10,10,5,8,11,11,7,8,8,8,7,5,2,7,11,11,10,11,11,9,6,6,6,9,2,3,12,11,11,9,10,7,4,2,2,2,2,6,12,11,10,5,11,11,8,11,10,5,5,5,9,8,7,7,12,6,5,8,6,3,8,6~7,9,4,3,6,7,7,10,3,7,9,4,9,5,4,4,4,6,6,10,9,7,9,4,10,5,11,9,12,5,4,8,12,7,7,7,6,2,8,11,8,10,10,2,3,5,9,9,11,4,9,9,9,6,7,4,5,11,7,8,4,9,6,11,5,6,11,4,11,11,11,7,11,8,10,5,11,5,8,6,8,11,8,6,6,10,5,6,6,6,5,8,10,9,9,4,7,4,8,7,7,2,10,9,6,2,2,2,2,3,7,5,7,9,8,5,3,11,5,2,3,10,5,7,7,8,8,8,12,5,7,6,8,12,11,9,8,12,4,8,9,11,7,5,5,5,4,11,5,4,5,6,4,8,11,12,11,11,9,8,12,8,12,12,12,12,12,6,11,9,9,8,9,10,7,11,10,11,4,6,10,4,3,4&s='.$lastReelStr.'&accInit=[{id:0,mask:"cp"},{id:1,mask:"cp;tp;lvl;sc;cl"}]&reel_set2=8,4,3,5,11,7,4,7,2,8,8,11,5,11,11,11,5,10,5,11,9,4,8,9,7,3,5,4,5,3,11,7,7,7,10,6,4,10,7,11,5,3,4,11,6,4,8,11,8,8,8,5,3,11,8,3,3,7,3,2,3,11,8,5,11,12,12,12,7,5,11,9,7,11,2,5,8,10,10,7,10,7,4,4,4,10,9,9,11,7,11,7,8,12,7,7,8,5,7,11,2,2,2,11,7,5,5,2,3,9,4,2,9,3,8,5,12,5,5,5,9,7,9,4,10,11,10,2,4,6,6,10,4,11,5,3,3,3,7,9,7,7,9,2,7,8,6,3,10,4,3,9,5,10,10,10,11,2,7,9,8,11,4,7,5,3,11,11,9,8,4,11~8,5,5,4,7,4,5,3,8,8,8,6,5,9,10,2,9,10,6,6,10,8,2,2,2,8,9,8,2,6,4,11,3,2,10,10,10,8,7,11,5,11,6,4,6,6,4,6,4,4,4,10,2,9,5,4,11,9,6,9,12,12,12,3,10,6,8,7,8,5,9,3,4,6,3,3,3,8,6,7,3,3,5,10,11,4,10,6,6,6,9,5,3,4,9,9,11,11,2,6,4,5,5,5,8,6,8,3,4,6,12,11,2,9,9,9,6,8,8,11,6,4,9,12,8,9,4,5~5,7,2,2,6,2,11,10,10,11,11,11,5,12,3,6,7,7,6,11,4,12,8,7,7,7,10,11,7,7,10,5,4,10,8,6,6,6,10,6,9,10,6,2,9,8,6,7,6,12,12,12,7,2,2,10,10,4,9,7,10,10,6,10,10,10,11,8,11,3,11,6,11,6,10,7,2,2,2,10,9,6,7,7,6,7,6,6,11,11,3,3,3,10,9,5,11,10,6,11,11,9,6,3,10~4,3,6,3,12,6,5,8,8,8,9,11,12,7,4,4,8,3,2,2,2,9,6,9,10,5,8,5,10,10,10,11,8,3,9,11,8,6,10,5,4,4,4,6,9,6,4,5,9,9,5,12,12,12,10,7,5,4,10,6,11,6,6,6,8,4,4,8,4,6,4,6,5,5,5,4,3,7,2,8,6,4,3,2,9,9,9,10,11,9,7,6,8,8,6,3,3,3,9,11,8,2,5,8,10,3,11,11,11,6,6,8,8,11,6,5,11,9,4~10,11,6,8,5,2,4,11,11,11,7,7,4,6,7,10,10,11,5,7,7,7,10,11,7,11,3,9,10,11,5,5,5,9,7,4,10,5,11,4,10,4,12,12,12,7,9,7,5,11,10,3,11,10,10,10,12,10,4,3,2,5,7,7,4,3,3,3,4,5,5,3,11,3,5,4,7,4,4,4,4,9,6,2,8,5,8,9,5,3,2,2,2,8,4,8,11,3,4,11,10,10,7&reel_set1=7,8,9,10,7,11,10,10,7,9,10,10,11,11,8,5,3,8,10,8,8,8,10,9,7,11,11,9,8,10,11,10,10,6,9,7,10,10,11,9,10,11,12,9,9,9,9,9,10,10,5,11,9,7,9,4,11,4,6,11,8,10,11,8,9,9,2,9,11,11,11,10,11,7,9,11,10,8,5,10,11,12,8,9,8,7,12,11,11,10,10,9,7,7,7,2,7,10,11,8,10,8,7,3,11,5,7,8,11,8,11,9,4,9,2,2,2,2,11,11,7,7,9,10,11,3,10,9,11,10,10,7,9,11,6,6,10,11,7,10,10,10,8,9,9,11,11,6,5,10,10,11,9,6,10,8,9,11,12,7,10,11,12,12,12,12,11,10,9,7,10,10,11,9,9,5,6,3,10,9,9,8,7,9,11,9,4,11~6,11,9,4,9,12,10,7,5,8,3,6,8,10,10,4,9,4,4,4,6,6,9,6,6,3,3,7,2,8,4,5,10,5,10,8,11,9,9,9,10,7,7,10,9,2,5,10,6,10,6,4,9,8,4,8,9,7,12,12,12,12,6,9,6,4,5,10,6,8,10,3,10,8,8,11,7,10,9,9,8,8,8,4,8,7,10,6,9,7,10,6,4,8,10,12,9,5,8,4,9,3,3,3,11,3,5,11,10,8,8,4,10,5,9,9,10,4,8,6,4,10,6,6,6,3,4,5,5,10,8,8,6,4,5,6,8,7,7,10,11,8,3,7,7,7,12,5,9,8,8,6,11,9,7,4,8,8,3,10,8,6,9,11,2,2,2,2,7,8,4,11,4,8,5,7,4,5,8,7,5,4,9,7,9,6,10,10,10,9,8,8,12,8,5,6,9,4,8,11,11,4,9,6,9,9,8,5,11~8,5,6,10,11,4,6,11,4,8,5,6,11,5,11,7,11,7,10,6,7,11,3,3,3,11,12,11,6,6,7,7,10,11,8,8,11,11,6,9,5,10,6,10,3,8,12,8,11,11,11,8,12,8,6,9,11,11,7,3,8,7,10,11,8,11,7,6,10,7,4,6,9,11,3,6,6,6,8,8,3,5,3,5,3,10,10,5,3,11,3,8,5,10,11,7,3,8,10,3,11,10,10,10,6,11,3,6,7,3,9,5,4,9,11,10,3,11,11,2,9,10,9,11,11,9,9,10,2,2,2,2,8,3,7,7,6,8,8,10,11,8,8,3,11,6,8,12,4,9,7,8,10,6,10,8,8,8,6,3,6,7,7,3,9,8,11,11,8,4,3,7,4,9,4,3,5,11,11,4,6,8,9,9,9,11,8,8,11,5,11,9,11,8,11,11,7,10,7,6,3,10,4,9,2,8,7,7,7,8,9,6,3,11,10,10,5,10,11,3,5,5,4,9,9,8,11,8,8,4,11,9,5,5,5,5,3,11,4,7,4,2,12,5,11,4,6,7,8,10,3,10,11,11,6,10,7,9,11,12,12,12,12,9,8,10,5,9,7,6,11,10,8,12,11,11,6,5,11,11,5,6,11,4,11,6,9,9~4,4,5,4,3,4,10,9,5,4,7,3,11,10,4,6,3,3,3,2,4,7,9,12,7,8,3,8,10,7,10,8,10,12,12,6,8,8,8,6,7,10,9,8,10,3,12,10,4,6,3,3,9,3,11,11,9,9,9,4,3,7,9,10,7,8,9,9,11,3,3,8,2,2,7,9,4,4,4,3,9,6,10,9,11,4,6,8,4,6,6,9,3,11,6,4,6,12,12,12,12,8,6,4,6,10,8,3,6,9,10,3,8,9,6,3,3,6,6,6,8,3,4,5,9,6,11,4,8,6,8,10,10,3,5,8,12,4,10,10,10,9,2,9,8,10,10,7,5,2,3,6,7,4,6,10,4,7,2,2,2,11,3,11,6,3,3,10,6,9,8,9,12,10,5,8,7,10,5,3~6,3,9,10,3,10,4,4,11,5,7,9,10,10,4,6,7,9,11,7,9,11,7,8,10,9,11,11,11,8,9,11,9,11,6,9,11,6,6,8,2,11,8,9,7,10,9,11,4,5,10,3,6,9,10,9,8,3,3,3,9,9,11,10,4,10,9,6,10,4,11,9,6,9,10,9,10,6,6,11,11,10,11,3,12,9,7,2,2,2,4,5,8,7,11,9,7,4,11,11,9,12,6,9,3,7,10,6,3,5,6,6,11,11,7,10,10,7,9,9,9,6,11,9,11,7,6,12,10,4,11,6,11,10,7,7,4,11,11,10,4,9,10,12,7,6,6,11,7,7,7,6,5,3,5,11,6,8,7,11,7,6,5,9,5,11,8,5,11,4,9,9,4,10,10,12,5,8,7,12,12,12,12,8,10,9,7,8,7,9,6,3,6,11,9,10,8,9,5,4,5,11,10,9,10,9,2,3,3,7,6,6,6,8,11,3,9,10,10,8,11,9,8,7,11,7,9,7,7,5,8,9,6,11,5,8,7,12,4,6,10,10,10,9,7,8,4,5,11,11,6,2,12,6,11,8,6,11,7,8,10,11,5,10,3,10,5,11,5,10,8,8,8,4,9,8,10,6,11,8,8,6,9,4,5,9,9,8,12,9,5,11,9,5,10,8,9,8,7,9,12,11&reel_set4=8,7,6,11,6,6,7,10,7,11,11,11,10,9,4,5,7,11,9,5,3,9,10,8,8,8,7,7,11,6,5,10,6,11,11,10,12,12,12,12,8,11,9,9,3,7,5,7,2,10,9,4,4,4,8,12,7,2,9,8,11,9,9,4,10,10,10,8,10,3,3,5,10,10,5,10,8,10,5,5,5,8,3,6,3,2,11,8,6,5,7,11,7,7,7,8,11,4,11,6,5,4,10,5,4,9,9,9,7,9,5,4,10,5,4,8,8,7,12,3,3,3,6,3,5,4,11,6,3,11,9,7,2,2,2,2,8,9,10,7,3,11,11,4,4,10,9,6,6,6,7,9,4,10,7,10,6,12,4,11,9,8~4,10,6,10,8,6,3,6,4,9,9,7,4,5,8,8,8,7,3,9,9,11,5,5,6,8,6,8,3,8,5,6,2,2,2,2,8,6,3,6,11,8,6,8,8,6,5,6,4,6,6,5,10,10,10,4,4,6,8,3,8,9,5,10,12,6,7,8,4,8,4,4,4,4,5,4,6,4,4,8,9,3,3,4,6,4,11,12,8,6,12,12,12,12,9,6,6,5,4,5,10,9,10,5,4,8,9,8,9,4,3,3,3,5,4,5,4,7,8,3,8,10,6,11,2,9,8,6,6,6,9,4,10,6,3,9,7,3,11,9,8,8,6,3,10,11,9,9,9,4,9,4,12,8,2,12,11,2,8,9,11,3,9,5,5,5,9,2,5,5,11,4,4,6,8,4,10,6,4,6,8,6,6~12,10,2,11,6,7,10,7,11,5,9,10,11,11,11,10,6,7,11,6,8,10,11,8,5,7,11,5,7,6,6,6,12,8,8,5,6,9,10,10,7,10,3,11,6,11,12,12,12,12,6,11,9,7,9,5,11,7,10,11,3,9,6,6,10,10,10,7,7,9,6,10,4,3,7,11,10,7,6,4,3,8,8,8,7,6,6,9,7,7,11,8,11,11,5,6,2,11,3,9,9,9,7,10,9,7,4,8,11,2,6,10,2,11,12,6,2,2,2,2,5,10,11,11,8,8,3,11,9,11,11,7,6,7,7,7,7,5,6,3,7,9,10,7,11,10,11,6,8,6,5,5,5,7,7,6,4,10,5,10,11,10,11,10,9,10,8,3,3,3,10,7,7,5,7,12,6,9,7,10,6,11,11,7,6~4,5,4,8,10,5,3,9,9,7,9,8,8,8,4,4,6,8,5,2,8,8,10,6,3,12,4,10,10,10,5,9,7,5,5,10,10,9,10,9,11,5,8,4,4,4,6,6,7,5,8,4,10,11,5,9,9,4,12,12,12,12,7,9,8,4,6,4,11,4,6,6,3,4,4,3,3,3,8,8,9,2,6,8,4,6,3,2,11,5,6,6,6,7,5,8,3,9,4,6,8,6,6,8,3,9,9,9,5,7,4,11,9,4,5,10,6,3,3,6,6,5,5,5,9,4,6,9,5,8,7,11,8,6,12,6,9,2,2,2,2,11,5,6,6,3,9,6,5,12,9,3,8,5,6~10,4,5,5,6,6,4,6,11,6,11,11,11,4,2,5,5,4,7,12,11,10,11,5,5,5,3,5,7,11,4,7,4,7,8,7,11,12,12,12,12,10,3,6,9,7,11,11,5,11,3,8,4,4,4,9,7,8,11,12,10,10,12,3,10,7,10,10,10,9,7,9,5,7,4,4,7,2,10,10,6,6,6,4,2,3,11,7,9,10,5,3,11,3,3,3,7,7,5,3,10,5,6,11,8,4,2,2,2,2,6,5,6,10,11,3,10,5,7,9,6,7,7,7,7,5,3,3,10,6,11,3,5,9,4,3,9,9,9,7,4,3,7,5,11,10,11,7,7,5,4,11&purInit=[{bet:1500,type:"default"}]&reel_set3=10,7,4,4,5,8,4,8,8,8,11,8,10,2,3,7,12,7,5,3,3,3,11,4,7,11,11,3,5,10,10,10,8,7,10,10,9,10,4,12,10,7,7,7,6,10,2,9,3,3,5,9,9,9,8,8,11,7,6,10,10,11,2,11,11,11,10,7,5,9,3,5,9,11,8,12,12,12,10,3,7,11,4,11,12,5,11,4,4,4,6,11,5,11,7,5,5,9,7,6,6,6,4,8,8,10,8,9,10,4,5,2,2,2,9,6,5,6,11,7,2,12,9,5,5,5,9,11,4,9,4,8,7,11,5,4~2,3,7,4,8,11,6,5,11,5,8,5,8,5,8,8,8,11,6,10,5,10,5,9,11,3,3,9,6,9,10,8,11,11,11,4,6,6,12,10,8,12,11,11,12,9,6,7,8,11,10,10,10,4,6,4,4,10,11,5,7,10,9,6,4,10,8,8,3,3,3,5,9,4,3,10,3,5,9,6,5,12,8,6,9,3,12,12,12,9,8,11,5,5,3,8,10,8,5,3,5,9,12,3,9,5,5,5,11,6,11,9,5,8,9,2,6,4,11,11,9,4,4,2,2,2,9,7,10,9,11,4,9,11,8,3,6,11,4,6,9,9,9,9,12,8,6,10,11,3,7,6,6,4,10,9,4,8,9,4,4,4,3,8,9,6,4,3,5,5,10,3,8,6,7,10,9,6,6,6,8,9,5,6,10,11,9,8,10,8,5,4,11,10,7,4,6~6,10,3,12,8,11,9,9,9,9,7,10,7,6,6,11,2,7,7,6,6,6,9,10,7,7,9,7,10,9,10,10,10,10,11,7,9,4,11,7,10,6,5,5,5,8,7,7,10,2,6,11,7,10,2,2,2,7,9,11,8,8,5,6,4,11,11,11,6,6,11,11,3,11,9,8,11,7,7,7,7,6,7,4,3,11,11,7,6,12,12,12,2,10,6,10,10,6,12,10,6,8,8,8,12,10,11,10,7,6,9,7,5,10~11,8,9,3,8,5,11,9,3,6,5,3,6,8,8,8,9,9,4,11,11,8,6,11,8,8,3,11,3,11,5,6,6,6,4,8,5,6,3,9,7,3,9,11,3,4,4,6,6,3,3,3,12,10,5,4,3,4,9,9,8,3,4,4,3,6,4,4,4,5,9,6,4,4,6,8,5,9,10,4,5,5,7,4,12,12,12,10,6,5,12,5,11,4,7,6,9,6,9,8,9,3,11,11,11,5,6,8,6,12,9,5,2,6,7,7,8,12,10,9,9,9,4,6,8,9,8,3,5,5,2,5,6,5,12,6,5,5,5,6,9,6,8,5,6,8,8,11,8,8,10,7,4,8,2,2,2,3,8,10,4,3,4,6,5,10,10,9,6,10,11,4,5~11,6,11,4,7,4,8,3,9,9,9,12,10,7,5,4,4,10,10,8,8,6,6,6,7,4,3,4,10,11,7,5,9,4,11,11,11,7,6,5,8,11,4,11,11,8,4,3,3,3,3,7,5,8,9,3,7,7,10,4,4,4,11,8,11,12,6,10,7,7,3,11,5,5,5,7,8,11,2,4,11,7,3,10,3,2,2,2,7,10,5,10,4,11,10,11,5,9,10,10,10,3,10,5,3,5,10,5,2,7,12,12,12,10,11,10,11,11,4,7,5,10,8,8,8,8,11,3,7,10,5,4,7,4,5,4,7,7,7,7,11,6,5,12,10,9,5,12,9,9,5&reel_set6=7,7,8,8,4,11,3,5,11,7,8,11,11,11,10,7,4,11,10,11,8,2,11,3,5,6,12,7,7,7,10,4,10,8,9,11,3,12,2,9,9,12,7,8,8,8,3,9,7,9,3,6,5,11,9,5,8,7,4,12,12,12,11,7,4,10,7,3,4,7,10,2,5,8,7,4,4,4,5,2,5,9,9,10,5,9,8,3,5,11,2,2,2,4,11,4,5,4,5,11,6,11,5,3,11,11,5,5,5,3,10,4,7,7,10,7,4,5,7,5,5,2,3,3,3,12,11,7,7,11,3,6,7,2,4,11,8,9,10,10,10,7,2,11,8,3,9,10,11,9,11,3,4,7,5~4,10,4,9,2,7,3,8,4,6,2,6,8,2,8,8,8,3,9,10,6,10,6,10,6,9,7,6,5,4,2,11,2,2,2,6,11,6,12,10,6,9,9,6,2,4,5,10,6,4,10,10,10,9,8,8,4,11,4,10,6,9,6,8,11,3,8,4,4,4,6,8,7,5,4,4,2,11,8,7,8,10,10,6,12,12,12,9,6,3,7,4,9,9,8,8,5,4,8,8,5,11,3,3,3,2,5,3,11,8,5,9,10,5,5,6,6,11,12,4,6,6,6,5,11,8,6,11,5,4,9,4,11,9,2,12,11,4,5,5,5,6,3,6,3,6,8,9,8,6,3,6,8,5,3,9,9,9,5,8,12,11,3,5,4,4,10,9,8,6,12,8,4,9~11,5,7,7,6,6,4,2,7,11,11,11,10,12,9,9,10,7,2,2,9,10,7,7,7,6,5,5,2,6,6,3,12,2,11,6,6,6,4,7,10,7,6,10,7,6,10,6,12,12,12,11,10,5,10,8,7,12,3,11,2,10,10,10,3,6,11,10,6,10,6,6,10,12,4,2,2,2,7,11,9,11,11,9,8,10,7,11,6,3,3,3,8,11,10,11,7,6,7,10,9,10,10,8~5,5,6,3,5,5,11,9,5,6,8,8,8,6,3,5,2,8,7,7,9,8,7,2,4,2,2,2,4,2,8,8,6,9,6,4,6,6,7,10,10,10,4,9,9,6,9,9,11,8,12,4,4,4,10,11,10,9,8,6,11,2,2,8,7,3,6,6,6,8,3,11,3,6,8,2,11,4,10,8,2,5,5,5,6,4,6,4,6,5,4,11,8,4,6,3,12,12,12,4,7,4,4,8,6,9,10,8,8,5,9,9,9,6,6,10,6,6,9,6,8,5,8,11,6,3,3,3,12,9,6,9,8,12,5,3,8,9,4,11,11,11,3,8,10,4,11,4,11,10,4,5,6,9,9~5,4,12,11,8,10,4,11,11,11,9,3,4,8,3,7,11,7,10,4,7,7,7,6,6,11,11,2,7,7,4,7,4,10,5,5,5,9,10,7,4,12,7,3,4,5,7,12,12,12,10,7,4,10,2,7,3,9,4,5,10,10,10,2,3,12,10,5,11,8,4,10,5,11,3,3,3,8,5,9,5,10,9,11,5,7,5,4,4,4,4,3,11,10,8,5,3,7,10,11,11,2,2,2,5,9,10,6,4,7,8,3,7,4,5,10&reel_set5=5,10,3,11,7,10,11,5,8,7,11,8,7,11,3,10,7,8,5,3~9,4,6,8,5,10,8,5,9,6,10,8,5,10,4,9,4,6~6,4,9,11,3,6,11,4,3,6,7,3,7,9,4,6,4,9,11,3,9,6,7,9,4,11,7~3,7,5,7,10,6,10,8,3,7,6,10,9,11,5,9,4,7,3,4,9,4,11,6,4,6,9,11,5,8,5,10,8,5,4,3,8,11,6,11,3,7,8,10~11,3,8,9,4,8,10,4,5,8,5,11,4,10,7,6,11,3,5,10,9,7,10,7,6,4,9,5,9,3,7,10,5,6,8,6,3,11,3,4,7,6,8&reel_set8=10,2,3,3,8,3,6,11,4,10,4,8,11,11,11,7,11,10,4,6,5,7,5,2,9,8,5,7,3,8,8,8,2,6,7,6,8,9,7,10,8,8,4,5,10,10,12,12,12,12,3,11,8,11,9,5,9,5,10,12,7,9,4,10,4,4,4,3,11,6,9,7,7,11,11,6,10,9,11,9,5,10,10,10,4,10,4,4,10,8,8,6,5,9,7,2,9,11,5,5,5,4,7,4,5,11,10,10,5,3,3,7,9,11,7,7,7,2,8,10,9,8,4,11,6,10,10,11,11,4,7,9,9,9,6,9,11,8,4,7,4,11,8,3,5,12,7,3,2,2,2,11,2,10,7,4,9,10,10,12,11,3,4,7,6,3,3,3,9,5,3,9,2,7,9,11,7,5,5,7,10,5,6,6,6,10,11,7,6,8,6,9,6,11,10,12,7,10,3,5~4,8,4,6,11,8,8,11,9,12,6,8,4,10,8,8,8,4,5,4,8,6,9,8,5,3,3,6,9,5,8,6,8,2,2,2,6,6,4,11,6,9,9,4,8,10,8,6,5,8,4,4,10,10,10,9,7,6,3,11,3,5,6,5,12,9,6,2,6,3,6,4,4,4,4,8,6,9,12,9,6,8,3,3,5,11,2,6,11,2,4,12,12,12,12,10,10,9,8,4,8,8,4,4,8,4,7,9,6,8,7,3,3,3,6,9,9,6,2,9,4,2,4,6,7,2,7,8,6,10,6,6,6,5,9,6,5,4,6,8,4,5,8,10,4,5,9,8,9,9,9,4,6,11,10,6,5,2,3,5,4,10,9,11,8,6,12,5,5,5,6,8,3,4,9,5,4,2,2,9,9,4,11,4,6,9,6,10~7,4,10,2,11,6,10,2,3,9,11,11,11,9,7,10,8,6,6,9,12,10,10,7,6,6,6,4,10,11,7,2,5,11,5,6,4,6,7,12,12,12,12,10,5,10,11,2,11,7,11,3,5,10,10,10,5,11,7,7,2,11,8,8,7,11,10,6,8,8,8,11,8,11,3,6,7,3,7,10,11,7,11,9,9,9,8,11,9,7,7,6,10,11,7,10,12,12,2,2,2,7,9,6,6,3,8,8,3,11,10,3,7,7,7,7,11,11,6,2,7,9,11,7,7,8,10,5,5,5,6,11,6,9,5,11,11,7,6,10,5,7,3,3,3,2,10,7,10,11,11,6,7,10,10,6,6,5~2,9,9,8,6,8,4,5,10,8,5,6,3,8,8,8,6,7,8,9,10,8,5,7,9,12,6,4,3,5,10,10,10,5,8,3,6,11,4,9,7,9,8,5,6,3,8,4,4,4,7,5,4,6,11,6,6,4,5,6,6,5,4,3,12,12,12,6,4,7,8,6,4,5,5,4,9,3,2,9,10,4,3,3,3,11,12,11,6,4,2,10,11,7,6,3,9,4,6,6,6,9,5,6,6,11,3,6,8,9,3,2,8,4,6,9,9,9,6,8,5,4,11,9,10,3,5,8,4,11,7,10,5,5,5,7,8,9,10,6,9,5,9,2,8,10,5,5,6,2,2,2,9,9,6,4,9,9,8,11,5,6,4,8,8,4,6,4~10,5,5,3,11,11,7,3,11,3,3,7,5,11,11,11,4,7,3,6,4,6,3,3,10,5,6,6,10,7,7,5,5,5,11,8,7,8,11,3,6,5,3,7,8,3,11,11,3,12,12,12,8,4,7,5,5,6,6,9,2,10,10,12,4,5,2,4,4,4,11,11,6,10,6,4,11,7,9,5,11,10,6,12,4,10,10,10,7,9,7,7,6,2,4,8,5,10,4,4,7,11,9,6,6,6,2,11,2,9,10,10,3,4,4,7,5,4,7,11,10,2,2,2,2,7,7,4,5,10,3,10,7,10,7,11,3,5,5,2,3,3,3,10,11,3,9,5,9,4,11,3,10,3,7,7,11,5,7,7,7,7,6,4,10,3,11,5,11,11,5,7,7,3,9,10,3,9,9,9,4,5,3,10,5,7,6,7,6,8,5,4,9,5,7,11&reel_set7=7,3,3,7,5,10,9,12,2,11,5,7,8,8,8,6,7,5,7,4,2,5,9,8,9,3,9,7,11,3,3,3,9,10,10,8,5,11,11,3,9,10,5,10,7,2,10,10,10,8,11,4,10,5,2,7,11,8,7,10,7,11,7,7,7,10,9,11,10,8,5,5,9,2,5,11,12,4,5,9,9,9,6,10,8,9,7,8,11,8,6,4,11,11,10,11,11,11,8,4,8,10,11,11,4,3,10,8,4,8,12,12,12,5,7,7,3,10,5,9,4,4,11,9,9,8,4,4,4,11,7,10,10,6,7,10,9,10,7,5,10,7,3,6,6,6,4,5,3,10,6,9,6,4,11,10,5,8,5,2,2,2,4,4,10,2,11,8,11,5,5,6,9,11,4,11,5,5,5,7,4,3,9,5,8,2,3,11,11,9,9,4,8,7~10,9,11,3,4,9,7,12,8,6,6,11,8,8,8,9,9,11,8,10,6,6,4,4,6,10,2,3,12,12,12,4,8,9,8,9,10,6,10,9,4,3,6,7,10,10,10,8,7,8,8,9,6,3,7,8,11,5,12,11,11,11,12,6,12,11,5,8,5,5,3,8,9,4,9,3,3,3,8,5,10,12,12,4,11,3,5,3,11,10,3,5,5,5,8,4,11,10,11,4,9,5,6,5,8,6,8,2,2,2,9,11,9,12,5,3,4,3,6,6,9,3,4,9,9,9,9,6,8,3,7,12,10,10,9,9,2,8,10,9,4,4,4,5,9,7,4,5,5,11,6,9,10,4,6,6,6,5,6,9,5,10,5,8,11,8,9,4,4,10,5,11~10,11,6,10,9,6,10,7,6,5,9,9,9,9,10,3,7,9,6,2,7,11,10,7,11,6,6,6,7,6,11,8,7,11,8,7,4,11,3,12,10,10,10,10,2,8,2,6,10,6,12,9,7,3,7,5,5,5,10,6,9,5,11,7,6,7,7,6,4,10,2,2,2,11,3,7,6,6,10,7,7,11,2,11,11,11,6,7,6,11,10,11,2,6,10,7,5,7,7,7,7,5,11,7,10,10,11,8,7,9,4,4,9,12,12,12,10,9,10,7,6,6,11,10,8,10,2,8,8,8,7,10,6,7,7,9,9,8,11,10,9,9,11~8,6,3,6,4,9,6,5,8,8,8,3,9,7,10,4,9,4,5,4,12,12,12,6,5,9,6,8,9,11,6,10,3,3,3,5,5,12,8,4,10,3,11,8,4,4,4,12,6,5,10,6,8,8,2,11,11,11,7,9,4,9,5,9,8,11,5,4,9,9,9,5,9,8,8,10,12,6,8,11,5,5,5,7,3,3,6,3,8,9,10,3,11,2,2,2,11,4,6,6,5,9,4,6,9,6,6,6,9,5,3,5,3,4,11,8,3,5,7~8,9,5,2,9,11,12,10,5,11,4,4,9,9,9,5,11,10,4,4,10,10,4,7,8,2,6,7,6,6,6,4,4,9,10,7,11,7,11,11,4,11,4,6,11,11,11,10,5,6,3,10,10,7,5,5,7,8,11,7,3,3,3,3,5,5,11,9,5,5,4,10,3,9,11,4,10,4,4,4,5,8,11,8,7,8,3,7,8,11,10,11,7,11,5,5,5,7,2,11,4,6,4,5,7,10,4,3,2,11,2,2,2,8,3,8,3,11,5,12,7,11,11,4,11,8,10,10,10,9,4,3,7,10,10,9,5,10,5,3,10,7,12,12,12,7,9,9,3,5,4,11,5,3,11,7,3,10,8,8,8,8,10,8,10,10,5,5,7,4,7,7,11,3,11,7,7,7,7,8,4,5,10,6,4,10,7,4,7,10,2,4,11&reel_set9=5,10,9,12,10,4,7,9,12,2,8,12,12,9,3,12,11,12,11,7,12,11,2,7,5,12,3,12,12,4,12,8,11,2,12,12,12,12,12,10,7,12,2,12,4,12,2,7,8,12,4,2,10,2,9,5,10,12,2,3,9,4,10,12,2,7,11,6,2,6,12,4,11,5,2,2,2,2,5,3,12,2,2,12,2,2,11,12,8,12,8,10,7,5,7,2,2,11,2,8,11,12,12,7,2,12,9,12,7,12,10,6,12,3,8,12~12,9,2,12,2,2,6,12,12,3,2,9,12,5,12,6,8,7,5,3,12,5,4,7,4,9,2,8,12,2,3,12,5,12,12,2,12,12,4,6,12,2,12,8,12,2,2,6,12,4,5,12,12,9,6,12,2,2,2,2,4,11,4,3,5,7,6,2,8,5,3,2,12,12,6,12,9,12,2,2,12,6,12,10,4,2,2,12,12,2,12,3,12,8,4,11,4,6,12,4,12,4,9,12,9,8,12,6,9,3,12,12,5,2,8,2,6,12,12,12,12,12,10,2,12,12,2,4,2,8,6,5,12,12,9,6,2,5,9,6,4,8,12,5,7,8,2,11,2,12,5,12,3,8,12,9,11,12,12,6,3,6,2,2,12,12,4,10,2,9,12,12,2,5,12,8,9,12,8,10~11,2,2,7,4,7,12,12,10,6,7,2,11,12,12,10,12,12,10,6,5,2,7,5,12,3,6,2,12,2,9,2,6,2,2,2,2,6,12,7,12,7,6,3,12,7,12,12,10,6,2,9,6,12,11,12,2,2,11,2,9,12,11,12,10,12,10,6,12,12,12,12,12,7,4,12,8,12,10,2,2,8,9,5,4,7,12,6,12,2,11,10,2,12,8,12,6,2,12,12,7,8,12,3,2,10,12,11,12,11~12,2,2,4,2,12,2,9,2,2,8,3,5,12,12,5,12,12,7,2,4,8,12,12,8,6,12,5,2,3,12,9,12,9,12,10,4,3,2,2,12,3,4,5,2,12,9,3,12,4,6,12,6,12,12,6,7,12,2,6,11,12,2,12,12,12,12,12,2,12,9,8,2,3,5,11,3,5,3,11,8,12,12,2,12,2,12,5,12,2,6,5,12,10,2,8,12,12,7,12,12,2,6,12,11,2,6,9,11,12,2,12,12,4,12,5,12,6,2,2,5,12,9,6,5,2,12,2,6,12,7,5,2,2,2,2,6,4,9,4,12,7,2,12,8,9,2,6,12,12,3,2,12,5,4,12,12,9,10,12,12,8,10,12,8,6,8,9,12,2,6,4,12,2,12,2,9,12,8,3,12,8,12,2,2,12,4,5,8,9,2,8,3,9,4,6,4,10,12~3,2,2,5,2,12,4,12,11,4,2,5,2,2,6,10,2,12,12,11,7,2,4,10,12,11,12,10,2,2,4,11,8,9,3,2,2,10,7,4,12,3,10,2,12,4,2,12,12,12,12,12,5,12,6,3,12,12,2,11,12,11,12,7,10,12,6,12,10,12,11,12,7,12,7,5,12,12,2,7,2,7,10,2,11,12,2,12,2,12,12,2,12,4,8,2,5,12,12,2,2,2,2,7,11,8,11,12,12,4,2,12,3,7,12,4,11,9,7,9,12,10,12,2,4,12,5,2,12,5,11,7,2,12,8,12,3,12,5,2,5,2,5,9,10,6,12,12,4,12,10,12,7,3,12&total_bet_min=10.00';
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
                // $winType = 'win';
                $allBet = $betline * $lines;
                if($pur >= 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $allBet * 75;
                }
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '561' . substr($roundstr, 4, 10);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $leftFreeGames = 0;

                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []);
                }
                
                $wild = '2';
                $scatter = '1';
                $money = '12';
                $Balance = $slotSettings->GetBalance();
                $totalWin = 0;
                $this->winLines = [];
                $bonusMpl = 1;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $str_accm = '';
                $str_accv = '';
                $str_acci = '';
                $str_mo = '';
                $str_mo_t = '';
                $str_mo_wpos = '';
                $mo_tv = 0;
                $mo_m = 0;
                $fsmore = 0;
                $fsmax = 0;
                $currentReelSet = 0;
                $str_rmul = '';
                $str_sa = '';
                $str_sb = '';
                if($slotEvent['slotEvent'] == 'freespin'){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_accm = $stack['accm'];
                    $str_acci = $stack['acci'];
                    $str_accv = $stack['accv'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $str_mo_wpos = $stack['mo_wpos'];
                    $mo_tv = $stack['mo_tv'];
                    $mo_m = $stack['mo_m'];
                    $fsmore = $stack['fsmore'];
                    $fsmax = $stack['fsmax'];
                    $strWinLine = $stack['win_line'];
                    $str_sa = $stack['sa'];
                    $str_sb = $stack['sb'];
                    $currentReelSet = $stack['reel_set'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $lastReel = explode(',', $stack[0]['reel']);
                    $str_accm = $stack[0]['accm'];
                    $str_acci = $stack[0]['acci'];
                    $str_accv = $stack[0]['accv'];
                    $str_mo = $stack[0]['mo'];
                    $str_mo_t = $stack[0]['mo_t'];
                    $str_mo_wpos = $stack[0]['mo_wpos'];
                    $mo_tv = $stack[0]['mo_tv'];
                    $mo_m = $stack[0]['mo_m'];
                    $fsmore = $stack[0]['fsmore'];
                    $fsmax = $stack[0]['fsmax'];
                    $strWinLine = $stack[0]['win_line'];
                    $str_sa = $stack[0]['sa'];
                    $str_sb = $stack[0]['sb'];
                    $currentReelSet = $stack[0]['reel_set'];
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
                $moneyWin = 0;
                if($mo_tv > 0){
                    $moneyWin = $mo_tv * $betline;
                    if($mo_m > 0){
                        $moneyWin = $moneyWin * $mo_m;
                    }
                    $totalWin = $moneyWin + $totalWin;
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
                $strLastReel = implode(',', $lastReel);

                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
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
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsend_total=1&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 'c';
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
                    if($fsmax > 0){
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1';
                        $spinType = 's';
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&purtr=1&puri=' . $pur;
                    }
                }
                if($str_accv != ''){
                    $strOtherResponse = $strOtherResponse . '&accm='.$str_accm.'&acci=' . $str_acci . '&accv=' . $str_accv;
                }
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                }
                if($mo_tv > 0){
                    $strOtherResponse = $strOtherResponse . '&mo_tv=' . $mo_tv . '&mo_c=1&mo_wpos=' . $str_mo_wpos. '&mo_tw=' . $moneyWin;
                }
                if($mo_m > 0){
                    $strOtherResponse = $strOtherResponse . '&mo_m=' . $mo_m;
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse  .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&reel_set='. $currentReelSet .'&na='.$spinType .'&stime=' . floor(microtime(true) * 1000).'&sa='. $str_sa .'&sb='. $str_sb .'&st=rect&c='.$betline.'&sh=4&sw=5&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&w='.$totalWin.'&s=' . $strLastReel;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if( $slotEvent['slotEvent'] != 'freespin' && $fsmax > 0) 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin')  . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')  .',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($slotEvent['slotEvent'] == 'freespin' && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * 75;
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

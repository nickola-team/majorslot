<?php 
namespace VanguardLTE\Games\ReturnoftheDeadPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 10);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [5,8,7,9,8,8,7,3,4,4,11,6,8,11,10]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
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
                    $bet = '100.00';
                }
                $spinType = 's';
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                $bonusType = '';
                if(isset($stack)){
                    $currentReelSet = $stack['reel_set'];
                    $str_ms = $stack['ms'];
                    $str_me = $stack['me'];
                    $str_mes = $stack['mes'];
                    $str_psym = $stack['psym'];
                    $mb = $stack['mb'];
                    $na = $stack['na'];
                    $str_win_line = $stack['win_line'];
                    $fsmore = $stack['fsmore'];
                    $fsmax = $stack['fsmax'];
                    if($na == 'm'){
                        $spinType = $na;
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0 && $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0){
                        $strOtherResponse = $strOtherResponse . '&fs_total='.($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 1).'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsend_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                    if($str_psym != ''){
                        $arr_psym = explode('~', $str_psym);
                        $scatter_win = str_replace(',', '', $arr_psym[1]) / $original_bet * $bet;
                        $arr_psym[1] = $scatter_win;
                        $str_psym = implode('~', $arr_psym);
                        $strOtherResponse = $strOtherResponse . '&psym=' . $str_psym;
                    }
                    if($mb > 0){
                        $strOtherResponse = $strOtherResponse . '&mb=' . $mb;
                    }
                    if($str_ms != ''){
                        $strOtherResponse = $strOtherResponse . '&ms=' . $str_ms;
                    }
                    if($str_me != ''){
                        $strOtherResponse = $strOtherResponse . '&me=' . $str_me . '&mes=' . $str_mes;
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

                    if( ($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)) 
                    {
                        $fs = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                        if($fs > $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames')){
                            $fs = $fs - 1;
                        }
                        $strOtherResponse = $strOtherResponse . '&fs=' . $fs. '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&w=0.00&fsmul=1';
                    }
                }
                
                $Balance = $slotSettings->GetBalance();  
                $response = 'wsc=1~bg~200,20,2,0,0~10,10,10,0,0~fs~200,20,2,0,0~10,10,10,0,0&def_s=5,8,7,9,8,8,7,3,4,4,11,6,8,11,10&reel_set25=5,8,7,10,4,7,1,9,6,1,9,7,6,10,5,7,9,8,10,9,5,7,9,5,10,9,8,11,9,8,3,4,8,10,7,5,10,11,8,7,11,9,7,11,8,7,11,8,7,3,8,7,9,1,8,9,5,8,7,3,9,8,7,9,8,5,9,4,11,9,8,5,9,11,3,8,10,5,8,10,9,8,4,7,9,10,6,7,10,6,7,1,5,7,8,11,10,5,8,10,9,11~9,10,7,8,4,10,8,7,11,10,7,11,4,7,10,6,7,9,6,10,9,6,11,10,7,8,9,6,1,10,6,7,10,8,4,11,8,9,6,8,5,10,8,11,9,10,8,11,9,10,5,9,11,8,7,11,8,10,11,7,10,8,11,10,6,11,10,9,11,10,8,6,3,10,11,6,10,8,11,1,10,8,11,4,10,11,6,10,8,9,11,8,3,9,8,11,4,8,1,11,7,10~9,11,3,7,9,10,8,6,7,11,6,10,11,7,9,11,10,9,11,4,9,11,3,9,11,10,9,11,3,7,11,6,1,11,7,10,11,1,10,11,5,7,11,9,7,11,4,9,11,7,10,11,9,4,5,10,7,11,5,7,10,8,7,10,4,3,7,11,9,1,7,11,10,7,11,10,9,6,10,4,5,10,7,4,10,6,8,10,4,5,10,7,6,4,11,9,7,11,9~6,4,7,9,4,5,10,11,6,8,4,9,5,4,7,5,8,9,1,8,7,5,11,9,5,11,7,8,10,7,11,5,1,10,11,9,10,8,7,11,9,7,11,9,7,8,5,7,8,11,3,9,10,6,9,4,11,1,9,10,3,7,4,11,8,10,1,8,9,7,4,9,10,11,8,9,11,6,10,8,3,5,7,1,11,7,5,9,6,8,9,5,10,9,8,6,10~4,8,10,3,4,10,7,4,11,10,6,8,10,9,5,10,9,11,5,9,1,7,11,1,10,8,5,10,9,8,5,6,9,10,8,3,9,11,7,9,5,7,8,5,4,11,3,4,10,6,9,10,6,8,1,6,9,3,8,9,10,11,8,6,7,11,9,7,8,1,7,10,8,7,11,8,7,9,8,7,5,10,7,9,6,11,5,7,11,5,7,10,9,7,11,6,4,11&reel_set26=5,8,7,10,4,7,1,9,6,1,9,7,6,9,11,7,9,8,11,9,5,7,11,5,10,9,8,11,9,8,3,4,11,10,7,5,10,11,8,7,11,9,7,11,8,7,11,8,7,3,8,7,9,1,8,9,5,8,7,3,9,8,7,9,8,5,9,4,11,9,8,5,9,11,3,8,10,5,8,10,9,8,4,7,9,10,6,7,10,6,7,1,5,7,8,11,10,5,8,10,9,11~9,10,7,8,4,10,8,7,11,9,7,11,4,7,8,6,11,9,6,10,9,6,11,10,7,8,9,6,1,10,6,7,10,8,4,11,8,9,6,8,5,10,8,11,9,10,8,11,9,10,5,9,11,8,7,11,8,10,11,7,10,8,11,10,6,11,10,9,11,10,8,6,3,10,11,6,10,8,11,1,10,8,11,4,10,11,6,10,8,9,11,8,3,9,8,11,4,8,1,11,7,10~9,11,3,7,9,10,8,6,7,11,6,10,11,7,9,11,10,9,11,4,9,11,3,9,11,10,9,11,3,7,11,6,1,11,7,10,11,1,10,11,5,7,11,9,7,11,4,9,11,7,10,11,9,4,5,10,7,11,5,7,10,8,7,10,4,3,7,11,9,1,7,11,10,7,11,10,9,6,10,4,5,10,7,4,10,6,8,10,4,5,10,7,6,4,11,9,7,11,9~6,4,7,9,4,5,9,11,6,8,4,9,10,4,7,5,8,9,1,8,7,5,11,9,5,11,7,8,4,7,11,5,1,10,11,9,10,8,7,11,9,7,11,9,7,8,5,7,8,11,3,9,10,6,9,4,11,1,9,10,3,7,4,11,8,10,1,8,9,7,4,9,10,11,8,9,11,6,10,8,3,5,7,1,11,7,5,9,6,8,9,5,10,9,8,6,10~4,8,10,11,4,8,7,4,11,10,6,8,10,11,5,10,9,11,5,9,1,7,11,1,10,8,5,10,9,11,5,6,9,7,8,3,9,11,7,9,5,7,8,5,4,11,3,4,10,6,9,10,6,8,1,6,9,3,8,9,10,11,8,6,7,11,9,7,8,1,7,10,8,7,11,8,7,9,8,7,5,8,7,9,6,11,5,7,11,5,7,10,9,7,11,6,4,11&balance='. $Balance .'&cfgs=1&ver=2&index=1&balance_cash='. $Balance .'&reel_set_size=27&def_sb=5,3,4,6,7&def_sa=11,12,10,8,9&reel_set='.$currentReelSet. $strOtherResponse .'&balance_bonus=0.00&na='. $spinType.'&scatters=&gmb=0,0,0&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"23809523",max_rnd_win:"5000"}}&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa=11,12,10,8,9&sb=5,3,4,6,7&reel_set10=3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7&sc='. implode(',', $slotSettings->Bet) .'&defc=0.20&reel_set11=3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7&reel_set12=1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10&reel_set13=1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7&sh=3&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&reel_set18=5,8,7,10,4,7,1,9,6,1,9,7,6,9,5,7,9,8,7,9,5,7,9,5,10,9,8,11,9,8,3,4,8,10,7,5,10,11,8,7,11,9,7,11,8,7,11,8,7,3,8,7,9,1,8,9,5,8,7,3,9,8,7,9,8,5,9,4,11,9,8,5,9,11,3,8,10,5,8,10,9,8,4,7,9,10,6,7,10,6,7,1,5,7,8,11,10,5,8,10,9,11~9,10,7,8,4,10,8,7,11,9,7,11,3,7,8,6,7,9,6,10,9,6,11,10,7,8,9,6,1,10,6,7,10,8,4,11,8,9,6,8,5,10,8,11,9,10,8,11,9,10,5,9,11,8,7,11,8,10,11,7,10,8,11,10,6,11,10,9,11,10,8,6,3,10,11,6,10,8,11,1,10,8,11,4,10,11,6,10,8,9,11,8,3,9,8,11,4,8,1,11,7,10~9,11,3,7,9,10,8,6,7,11,3,10,11,7,9,11,10,9,11,4,9,11,3,9,11,10,9,11,3,7,11,6,1,11,7,10,11,1,10,11,5,7,11,9,7,11,4,9,11,7,10,11,9,4,5,10,7,11,5,7,10,8,7,10,4,3,7,11,9,1,7,11,10,7,11,10,9,6,10,4,5,10,7,4,10,6,8,10,4,5,10,7,6,4,11,9,7,11,9~6,4,7,9,4,5,9,11,6,8,4,9,5,4,7,3,8,9,1,8,7,5,11,9,5,11,7,8,4,7,11,5,1,10,11,9,10,8,7,11,9,7,11,9,7,8,5,7,8,11,3,9,10,6,9,4,11,1,9,10,3,7,4,11,8,10,1,8,9,7,4,9,10,11,8,9,11,6,10,8,3,5,7,1,11,7,5,9,6,8,9,5,10,9,8,6,10~4,8,10,3,4,8,7,4,11,10,3,8,10,9,5,10,9,11,5,9,1,7,11,1,10,8,5,10,9,8,5,6,9,7,8,3,9,11,7,9,5,7,8,5,4,11,3,4,10,6,9,10,6,8,1,6,9,3,8,9,10,11,8,6,7,11,9,7,8,1,7,10,8,7,11,8,7,9,8,7,5,8,7,9,6,11,5,7,11,5,7,10,9,7,11,6,4,11&reel_set19=5,8,7,10,9,7,1,9,6,1,9,7,4,9,5,7,9,4,7,9,5,7,4,5,10,9,8,11,9,8,3,4,8,10,7,5,10,11,8,7,11,9,7,11,8,7,11,8,7,3,8,7,9,1,8,9,5,8,7,3,9,8,7,9,8,5,9,4,11,9,8,5,9,11,3,8,10,5,8,10,9,8,4,7,9,10,6,7,10,6,7,1,5,7,8,11,10,5,8,10,9,11~9,10,7,8,4,10,8,7,11,9,7,11,9,7,8,6,7,9,6,10,9,6,4,10,7,8,9,6,1,10,6,7,10,8,4,11,8,9,6,8,5,10,8,11,9,10,8,11,9,10,5,9,11,8,7,11,8,10,11,7,10,8,11,10,6,11,10,9,11,10,8,6,3,10,11,6,10,8,11,1,10,8,11,4,10,11,6,10,8,9,11,8,3,9,8,11,4,8,1,11,7,10~9,11,3,7,9,10,8,6,7,11,6,10,11,7,4,11,10,9,11,4,9,11,3,9,11,10,9,11,3,7,11,6,1,11,7,10,11,1,10,11,5,7,11,9,7,11,4,9,11,7,10,11,9,4,5,10,7,11,5,7,10,8,7,10,4,3,7,11,9,1,7,11,10,7,11,10,9,6,10,4,5,10,7,4,10,6,8,10,4,5,10,7,6,4,11,9,7,11,9~6,4,7,9,4,5,9,11,6,8,4,9,5,4,7,5,8,9,1,8,7,5,11,4,5,11,7,8,4,7,11,5,1,10,11,9,10,8,7,11,9,7,11,9,7,8,5,7,8,11,3,9,10,6,9,4,11,1,9,10,3,7,4,11,8,10,1,8,9,7,4,9,10,11,8,9,11,6,10,8,3,5,7,1,11,7,5,9,6,8,9,5,10,9,8,6,10~4,8,10,3,4,8,7,9,11,10,6,8,10,9,5,10,9,11,5,9,1,7,11,1,10,8,4,10,9,8,5,6,9,7,4,3,9,11,7,9,5,7,8,5,4,11,3,4,10,6,9,10,6,8,1,6,9,3,8,9,10,11,8,6,7,11,9,7,8,1,7,10,8,7,11,8,7,9,8,7,5,8,7,9,6,11,5,7,11,5,7,10,9,7,11,6,4,11&counter=2&reel_set14=1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;5000,1000,100,10,0;2000,400,40,5,0;750,100,30,5,0;750,100,30,5,0;150,40,5,0,0;150,40,5,0,0;100,25,5,0,0;100,25,5,0,0;100,25,5,0,0&l=10&reel_set15=1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7&reel_set16=3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7&reel_set17=1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7&rtp=96.50&total_bet_max=100,000.00&reel_set21=5,8,7,10,4,7,1,9,6,1,9,7,6,9,5,7,9,8,6,9,5,7,9,5,6,9,8,11,9,8,3,4,8,10,7,5,10,11,8,7,11,9,7,11,8,7,11,8,7,3,8,7,9,1,8,9,5,8,7,3,9,8,7,9,8,5,9,4,11,9,8,5,9,11,3,8,10,5,8,10,9,8,4,7,9,10,6,7,10,6,7,1,5,7,8,11,10,5,8,10,9,11~9,10,7,8,4,10,8,7,11,9,7,11,4,7,8,6,7,9,6,10,9,6,11,10,7,8,9,6,1,10,6,7,10,8,4,11,8,9,6,8,5,10,8,11,9,10,8,11,9,10,5,9,11,8,7,11,8,10,11,7,10,8,11,10,6,11,10,9,11,10,8,6,3,10,11,6,10,8,11,1,10,8,11,4,10,11,6,10,8,9,11,8,3,9,8,11,4,8,1,11,7,10~9,11,3,6,9,10,8,6,7,11,6,10,11,7,9,11,10,6,11,4,9,11,3,6,11,10,9,11,3,7,11,6,1,11,7,10,11,1,10,11,5,7,11,9,7,11,4,9,11,7,10,11,9,4,5,10,7,11,5,7,10,8,7,10,4,3,7,11,9,1,7,11,10,7,11,10,9,6,10,4,5,10,7,4,10,6,8,10,4,5,10,7,6,4,11,9,7,11,9~6,4,7,9,4,5,9,11,6,8,4,9,5,4,7,5,8,9,1,8,7,5,11,9,5,11,7,8,4,7,11,5,1,10,11,9,10,8,7,11,9,7,11,9,7,8,5,7,8,11,3,9,10,6,9,4,11,1,9,10,3,7,4,11,8,10,1,8,9,7,4,9,10,11,8,9,11,6,10,8,3,5,7,1,11,7,5,9,6,8,9,5,10,9,8,6,10~4,8,10,3,4,8,7,4,11,10,6,8,10,9,5,6,9,11,5,9,1,7,11,1,10,8,5,6,9,8,5,6,9,7,8,3,9,11,7,9,5,7,8,5,4,11,3,4,10,6,9,10,6,8,1,6,9,3,8,9,10,11,8,6,7,11,9,7,8,1,7,10,8,7,11,8,7,9,8,7,5,8,7,9,6,11,5,7,11,5,7,10,9,7,11,6,4,11&reel_set22=5,8,7,10,4,7,1,9,6,1,9,7,6,9,5,7,9,8,7,9,5,7,9,5,10,7,8,11,9,7,3,4,8,10,7,5,10,11,8,7,11,9,7,11,8,7,11,8,7,3,8,7,9,1,8,9,5,8,7,3,9,8,7,9,8,5,9,4,11,9,8,5,9,11,3,8,10,5,8,10,9,8,4,7,9,10,6,7,10,6,7,1,5,7,8,11,10,5,8,10,9,11~9,10,7,8,4,10,8,7,11,9,7,11,4,7,8,6,7,9,6,10,9,6,11,10,7,8,9,6,1,10,6,7,10,8,4,11,8,9,6,8,5,10,8,11,9,10,8,11,9,10,5,9,11,8,7,11,8,10,11,7,10,8,11,10,6,11,10,9,11,10,8,6,3,10,11,6,10,8,11,1,10,8,11,4,10,11,6,10,8,9,11,8,3,9,8,11,4,8,1,11,7,10~9,11,3,7,9,10,8,6,3,11,6,10,11,7,9,11,10,9,11,7,9,11,3,7,11,10,9,11,3,7,11,6,1,11,7,10,11,1,10,11,5,7,11,9,7,11,4,9,11,7,10,11,9,4,5,10,7,11,5,7,10,8,7,10,4,3,7,11,9,1,7,11,10,7,11,10,9,6,10,4,5,10,7,4,10,6,8,10,4,5,10,7,6,4,11,9,7,11,9~6,4,7,9,4,5,9,11,7,8,4,9,5,4,7,5,8,9,1,8,7,5,11,9,5,11,7,8,4,7,11,5,1,10,11,9,10,8,7,11,9,7,11,9,7,8,5,7,8,11,3,9,10,6,9,4,11,1,9,10,3,7,4,11,8,10,1,8,9,7,4,9,10,11,8,9,11,6,10,8,3,5,7,1,11,7,5,9,6,8,9,5,10,9,8,6,10~4,8,10,3,4,8,7,4,11,10,7,8,10,9,5,3,9,11,5,9,1,7,11,1,10,8,5,10,7,8,5,6,9,7,8,3,9,11,7,9,5,7,8,5,4,11,3,4,10,6,7,10,6,8,1,6,9,3,8,9,10,11,8,6,7,11,9,7,8,1,7,10,8,7,11,8,7,9,8,7,5,8,7,9,6,11,5,7,11,5,7,10,9,7,11,6,4,11&reel_set0=9,5,11,7,8,5,1,7,11,8,10,5,11,4,10,5,9,3,4,6,10,8,1,10,6,11,9,10,3,8~6,9,8,7,10,5,3,7,4,6,8,5,1,4,9,10,11,3,1,6,4,7,1,11,10,5,11,6,3,1~7,9,1,10,11,5,9,3,4,6,7,5,9,10,11,4,8,1,9,6,5,3,4,6,9,1,6,7,5,4~11,6,3,10,4,6,1,7,4,1,9,11,10,5,1,9,11,5,7,8,4,9,1,4,5,10,7,5,3,7~6,4,9,5,8,11,5,10,7,9,3,1,11,7,8,9,11,3,7,6,11,3,9,4,3,5,11,7,1,5&reel_set23=5,8,7,10,4,7,1,9,6,1,9,7,6,9,5,7,9,8,7,9,5,8,9,5,10,9,8,11,9,8,3,4,8,10,7,5,10,11,8,7,11,9,7,11,8,7,11,8,7,3,8,7,9,1,8,9,5,8,7,3,9,8,7,9,8,5,9,4,11,9,8,5,9,11,3,8,10,5,8,10,9,8,4,7,9,8,6,7,10,6,7,1,5,7,8,11,10,5,8,10,9,11~9,10,7,8,4,10,8,7,11,9,7,11,4,7,8,6,7,9,6,10,9,6,11,10,7,8,9,6,1,10,6,7,10,8,4,11,8,9,6,8,5,10,8,11,9,10,8,11,9,10,5,9,11,8,7,11,8,10,11,7,10,8,11,10,6,11,10,9,11,10,8,6,3,10,11,6,10,8,11,1,10,8,11,4,10,11,6,10,8,9,11,8,3,9,8,11,4,8,1,11,7,10~9,11,3,7,9,10,3,6,7,11,6,8,11,7,9,11,10,9,11,4,9,8,3,9,11,10,9,11,3,7,11,6,1,11,7,10,11,1,10,11,5,7,11,9,7,11,4,9,11,7,10,8,9,4,5,10,7,11,5,7,10,8,7,10,4,3,7,11,9,1,7,11,10,7,11,10,9,6,10,4,5,10,7,4,10,6,8,10,4,5,10,7,6,4,11,9,7,11,9,7~6,4,7,9,4,5,9,11,6,8,4,9,5,4,7,5,8,9,1,8,7,5,11,9,5,11,7,8,4,7,11,5,1,10,11,9,10,8,7,11,9,7,11,9,7,8,5,7,8,11,3,9,10,6,9,4,11,1,9,10,3,7,4,11,8,10,1,8,9,7,4,9,10,11,8,9,11,6,10,8,3,5,7,1,11,7,5,9,6,8,9,5,10,9,8,6,10~4,8,10,3,4,8,7,4,11,10,6,8,10,9,5,3,9,11,5,9,1,7,11,1,10,8,5,10,9,8,5,6,9,7,8,3,9,11,7,9,5,7,8,5,4,11,3,8,10,6,9,10,6,8,1,6,9,3,8,9,10,11,8,6,7,11,9,7,8,1,7,10,8,7,11,8,7,9,8,7,5,8,7,9,6,11,5,7,11,5,7,10,9,7,11,6,4,11&s='.$lastReelStr.'&reel_set24=5,8,7,10,4,7,1,9,6,1,9,7,6,9,5,7,9,8,7,9,5,7,9,5,10,9,8,11,9,8,3,4,8,9,7,5,10,9,8,7,11,9,7,11,8,7,11,8,7,3,8,7,9,1,8,9,5,8,7,3,9,8,7,9,8,5,9,4,11,9,8,5,9,11,3,8,10,5,8,10,9,8,4,7,9,10,6,7,10,6,7,1,5,7,8,11,10,5,8,10,9,11~9,10,7,8,4,10,8,7,11,9,7,11,4,7,8,6,7,9,6,10,9,6,11,10,7,8,9,6,1,10,6,7,9,8,4,11,8,9,6,8,5,10,8,11,9,10,8,11,9,10,5,9,11,8,7,11,8,9,11,7,10,8,11,10,6,11,10,9,11,10,8,6,3,10,11,6,10,8,11,1,10,8,11,4,10,11,6,10,8,9,11,8,3,9,8,11,4,8,1,11,7,10~9,11,3,7,9,10,8,6,7,11,6,10,11,7,9,11,10,9,11,4,9,11,3,9,11,10,9,11,3,7,11,6,1,11,7,10,11,1,10,11,5,7,11,9,7,11,4,9,11,7,10,11,9,4,5,10,7,11,5,7,10,8,9,10,4,3,7,11,9,1,7,11,10,7,11,10,9,6,10,4,5,10,7,4,10,6,8,10,4,5,10,7,6,4,11,9,7,11,9~6,4,7,9,4,5,9,11,6,8,4,9,5,4,7,5,8,9,1,8,7,5,11,9,5,11,7,8,4,7,11,5,1,10,11,9,10,8,7,11,9,7,11,9,7,8,5,7,8,11,3,9,10,6,9,4,11,1,9,10,3,7,4,11,8,10,1,8,9,7,4,9,10,11,8,9,11,6,10,8,3,5,7,1,11,7,5,9,6,8,9,5,10,9,8,6,10~4,8,10,3,9,8,7,4,11,9,6,8,10,9,5,10,9,11,5,9,1,7,11,1,10,8,5,10,9,8,5,6,9,7,8,3,9,11,7,9,5,7,8,5,4,11,3,4,10,6,9,10,6,8,1,6,9,3,8,9,10,11,9,6,7,11,9,7,8,1,7,10,8,7,11,8,7,9,8,7,5,8,7,9,6,11,5,7,11,5,7,10,9,7,11,6,4,11&reel_set2=1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10&reel_set1=10,5,7,10,8,9,1,7,8,11,10,9,6,4,10,11,1,4,3,9,4,1,6,8,10,11,7,1,3,8~6,9,5,1,10,8,3,7,6,4,8,9,3,4,8,3,11,9,8,6,11,3,1,7,3,5,7,10,3,7~3,9,6,10,11,6,1,3,10,4,7,6,8,10,7,3,8,11,5,6,10,3,4,5,9,1,10,7,5,1~5,6,7,10,4,3,6,7,11,10,9,11,3,5,8,4,11,1,3,8,5,9,1,7,11,10,9,11,3,1~9,4,1,7,8,9,6,10,1,5,3,8,5,7,6,1,11,5,3,6,11,3,9,6,10,5,3,4,1,11&reel_set4=1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7&reel_set3=1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10&reel_set20=5,8,7,5,4,7,1,9,6,1,9,7,6,9,5,7,9,8,7,9,5,7,9,5,10,9,8,11,9,8,3,4,8,10,7,5,10,11,8,7,11,9,7,11,8,7,11,8,7,3,8,7,9,1,8,9,5,8,7,3,9,8,7,9,8,5,9,4,11,9,8,5,9,11,3,8,10,5,8,10,9,8,4,7,9,10,6,7,10,6,7,1,5,7,8,11,10,5,8,10,9,11~9,10,7,8,4,10,8,7,5,9,7,11,4,7,8,5,7,9,6,10,9,6,11,10,7,8,9,6,1,10,6,7,10,8,4,11,8,9,6,8,5,10,8,11,9,10,8,11,9,10,5,9,11,8,7,11,8,10,11,7,10,8,11,10,6,11,10,9,11,10,8,6,3,10,11,6,10,8,11,1,10,8,11,4,10,11,6,10,8,9,11,8,3,9,8,11,4,8,1,11,7,10~9,11,3,7,9,10,8,6,7,9,5,10,11,7,9,11,10,9,11,4,9,11,5,9,11,10,9,5,3,7,11,6,1,11,7,10,11,1,10,11,5,7,11,9,7,11,4,9,11,7,10,11,9,4,5,10,7,11,5,7,10,8,7,10,4,3,7,11,9,1,7,11,10,7,11,10,9,6,10,4,5,10,7,4,10,6,8,10,4,5,10,7,6,4,11,9,7,11,9,7~6,4,7,9,4,5,9,11,6,8,4,9,5,4,7,5,8,9,1,8,7,5,11,9,5,11,7,8,4,7,11,5,1,10,11,9,10,8,7,11,9,7,11,9,7,8,5,7,8,11,3,9,10,6,9,4,11,1,9,10,3,7,4,11,8,10,1,8,9,7,4,9,10,11,8,9,11,6,10,8,3,5,7,1,11,7,5,9,6,8,9,5,10,9,8,6,10~4,8,10,3,4,5,7,4,11,5,6,8,10,9,5,10,9,11,5,9,1,7,11,1,10,8,5,10,9,8,5,6,9,7,8,3,9,11,7,9,5,7,8,5,4,11,3,4,10,6,9,10,6,8,1,6,9,3,8,9,10,11,8,6,7,11,9,7,8,1,7,10,8,7,11,8,7,9,8,7,5,8,7,9,6,11,5,7,11,5,7,10,9,7,11,6,4,11&purInit=[{type:"fs",bet:1000,fs_count:10}]&reel_set6=1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7&reel_set5=1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10&reel_set8=3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10&reel_set7=1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7&reel_set9=3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7&total_bet_min=20';
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
                $lines = 10;      
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
                $slotEvent['slotLines'] = 10;
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

                $allBet = $betline * $lines;
                $tumbAndFreeStacks = []; 
                $isGeneratedFreeStack = false;
                $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $betline * $lines, $lines);
                $winType = $_spinSettings[0];
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '561' . substr($roundstr, 4, 10);
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
                $str_ms = '';
                $str_me = '';
                $str_mes = '';
                $str_psym = '';
                $mb = 0;
                $fsmore = 0;
                $fsmax = 0;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $str_ms = $stack['ms'];
                    $str_me = $stack['me'];
                    $str_mes = $stack['mes'];
                    $str_psym = $stack['psym'];
                    $mb = $stack['mb'];
                    $str_win_line = $stack['win_line'];
                    $fsmore = $stack['fsmore'];
                    $fsmax = $stack['fsmax'];
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
                    $currentReelSet = $stack[0]['reel_set'];
                    $str_ms = $stack[0]['ms'];
                    $str_me = $stack[0]['me'];
                    $str_mes = $stack[0]['mes'];
                    $str_psym = $stack[0]['psym'];
                    $mb = $stack[0]['mb'];
                    $str_win_line = $stack[0]['win_line'];
                    $fsmore = $stack[0]['fsmore'];
                    $fsmax = $stack[0]['fsmax'];
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
                if($str_psym != ''){
                    $arr_psym = explode('~', $str_psym);
                    $scatter_win = str_replace(',', '', $arr_psym[1]) / $original_bet * $betline;
                    $arr_psym[1] = $scatter_win;
                    $totalWin = $totalWin + $scatter_win;
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
                if($fsmax > 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $fsmax);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1); 
                }else if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
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
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    if($fsmax > 0){
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 'm';
                        
                    }
                }
                if($str_psym != ''){
                    $strOtherResponse = $strOtherResponse . '&psym=' . $str_psym;
                }
                if($mb > 0){
                    $strOtherResponse = $strOtherResponse . '&mb=' . $mb;
                }
                if($str_ms != ''){
                    $strOtherResponse = $strOtherResponse . '&ms=' . $str_ms;
                }
                if($str_me != ''){
                    $strOtherResponse = $strOtherResponse . '&me=' . $str_me . '&mes=' . $str_mes;
                }
                if($str_win_line != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $str_win_line;
                }


                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse  .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&reel_set='. $currentReelSet .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&c='.$betline .'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=18&w='.$totalWin.'&s=' . $strLastReel;
                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if( $slotEvent['slotEvent'] != 'freespin' && $fsmax > 0 ) 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
            }else if( $slotEvent['slotEvent'] == 'doMysteryScatter' ){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lastReel = $lastEvent->serverResponse->LastReel;
                $lines = 10;
                $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');

                $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                $response = 'fsmul=1&balance='. $Balance .'&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&ms='. $stack['ms'] .'&index='.$slotEvent['index'].'&balance_cash='. $Balance .'&reel_set=0&balance_bonus=0.00&na=s&fswin=0.00&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fsres=0.00&sver=5&counter='. ((int)$slotEvent['counter'] + 1);

                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], false);
            }
            if($slotEvent['action'] == 'doSpin' || $slotEvent['action'] == 'doMysteryScatter' || $slotEvent['action'] == 'doCollect' || $slotEvent['action'] == 'doCollectBonus' || $slotEvent['action'] == 'doBonus'){                
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

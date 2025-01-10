<?php 
namespace VanguardLTE\Games\TheAlterEqoPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'RespinWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'Bl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [4,9,7,5,12,7,12,4,8,9,8,13,9,5,11,3,10,10,4,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'IsBonusBank', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $strOtherResponse = '';
                $currentReelSet = 0;
                $stack = null;
                
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinWin', $lastEvent->serverResponse->RespinWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $lastEvent->serverResponse->CurrentRespin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bl', $lastEvent->serverResponse->Bl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'IsBonusBank', $lastEvent->serverResponse->IsBonusBank);
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
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if(isset($stack)){
                    $str_initReel = $stack['initreel'];
                    $currentReelSet = $stack['reel_set'];
                    $fsmore = $stack['fsmore'];
                    $str_stf = $stack['stf'];
                    $str_srf = $stack['srf'];
                    $str_rs_p = $stack['rs_p'];
                    $str_rs_c = $stack['rs_c'];
                    $str_rs_m = $stack['rs_m'];
                    $str_rs_t = $stack['rs_t'];
                    $str_rs_more = $stack['rs_more'];
                    $str_rs_iw = $stack['rs_iw'];
                    $str_rs_win = $stack['rs_win'];
                    $str_rs = $stack['rs'];
                    $strReelSa = $stack['sa'];
                    $strReelSb = $stack['sb'];
                    $str_s_mark = $stack['s_mark'];
                    $fsmax = $stack['fsmax'];
                    $str_trail = $stack['trail'];
                    $strWinLine = $stack['win_line'];

                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                    }
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
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
                    if($str_rs_p != ''){
                        $strOtherResponse = $strOtherResponse . '&rs_p=' . $str_rs_p . '&rs_m=' . $str_rs_m . '&rs_c=' . $str_rs_c;
                    }
                    if($str_rs_t != ''){
                        $strOtherResponse = $strOtherResponse . '&rs_t=' . $str_rs_t;
                    }
                    if($str_rs_iw != ''){
                        $str_rs_iw = str_replace(',', '', $str_rs_iw) / $original_bet * $bet;
                        $strOtherResponse = $strOtherResponse . '&rs_iw=' . $str_rs_iw;
                    }
                    if($str_rs_win != ''){
                        $str_rs_win = str_replace(',', '', $str_rs_win) / $original_bet * $bet;
                        $strOtherResponse = $strOtherResponse . '&rs_win=' . $str_rs_win;
                    }
                    if($str_rs_more != ''){
                        $strOtherResponse = $strOtherResponse . '&rs_more=' . $str_rs_more;
                    }
                    if($str_rs != ''){
                        $strOtherResponse = $strOtherResponse . '&rs=' . $str_rs;
                    }
                    
                    if($str_s_mark != ''){
                        $strOtherResponse = $strOtherResponse . '&s_mark=' . $str_s_mark;
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
                $response = 'def_s=4,9,7,5,12,7,12,4,8,9,8,13,9,5,11,3,10,10,4,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3&balance='. $Balance .'&cfgs=10736&ver=3&index=1&balance_cash='. $Balance .'&def_sb=4,11,4,4,10&reel_set_size=9&def_sa=6,13,9,5,11&reel_set=0&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"10989011",max_rnd_win:"10000"}}&wl_i=tbm~10000&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa=6,13,9,5,11&sb=4,11,4,4,10&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=50.00&purInit_e=1,1,1&sh=10&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&st=rect&c='. $bet .'&sw=5&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;120,40,20,0,0;60,20,10,0,0;50,18,8,0,0;40,15,6,0,0;35,12,5,0,0;35,12,5,0,0;35,12,5,0,0;30,10,4,0,0;30,10,4,0,0;30,10,4,0,0;0,0,0,0,0&l=20&total_bet_max=20,000,000.00&reel_set0=8,7,10,4,12,7,7,4,4,4,9,11,6,5,6,11,10,6,6,6,12,5,11,10,9,8,8,13,7,11,11,11,6,10,10,5,10,6,11,6,12,12,12,13,4,5,6,13,4,13,13,13,12,12,13,8,12,11,8,4,6,10,10,10,5,9,5,7,8,4,9,13,8,8,8,4,12,5,12,13,6,10,7,9,9,9,10,8,9,11,5,8,13,11,11,9~8,4,11,6,10,11,8,6,4,4,4,4,8,2,6,6,9,13,2,6,13,8,8,8,8,7,10,12,5,13,6,6,9,4,6,6,6,6,13,11,7,2,8,5,7,4,12,5,5,5,5,4,11,12,9,8,12,6,12,11,11,11,5,9,11,10,5,13,5,7,10,7,7,7,7,12,8,12,5,9,10,13,11,8,13,13,13,13,8,6,6,8,7,12,4,10,6,12,12,12,6,10,8,5,4,7,13,4,11,10,10,10,13,8,11,4,4,7,6,9,4,9,13~4,11,13,12,12,6,5,4,4,4,4,9,13,8,7,6,10,7,7,13,13,13,5,8,8,6,12,9,10,13,9,9,9,13,6,7,8,6,6,12,5,11,11,11,8,11,6,13,9,12,6,11,6,6,6,6,12,13,13,11,8,11,11,5,10,10,10,7,10,10,5,6,5,2,4,12,12,12,4,8,13,12,11,4,10,9,7,7,7,9,13,12,12,10,13,9,6,8,8,8,4,11,9,8,6,7,7,5,5,5,6,6,4,11,4,7,13,5,10~4,11,4,11,12,9,6,10,7,6,9,9,9,9,6,4,6,4,6,7,13,8,9,9,11,7,7,7,7,4,9,13,5,12,5,5,9,6,11,4,12,12,12,12,10,10,13,10,4,12,12,13,6,6,10,5,5,5,12,5,7,8,11,5,8,6,13,8,7,6,6,6,6,8,6,11,6,7,6,12,5,10,10,4,10,10,10,9,13,12,8,13,12,10,8,9,7,12,13,13,13,13,13,7,2,11,8,13,4,8,13,11,9,13,8,8,8,10,4,6,7,13,8,12,5,11,4,9,5,13~4,4,10,7,12,6,6,4,4,4,4,11,7,4,10,8,12,5,9,13,13,13,13,6,5,6,10,11,13,4,8,7,7,7,7,10,13,10,9,9,11,8,5,11,11,11,11,5,10,7,12,13,13,4,12,6,6,6,6,8,8,9,12,6,6,8,12,8,8,8,9,9,13,4,11,7,4,4,12,12,12,12,13,11,10,9,5,8,7,9,9,9,9,5,8,7,8,13,13,10,11,10,10,10,10,13,10,6,12,10,5,11,11,13,7&s='.$lastReelStr.'&reel_set2=5,11,5,13,11,9,13,7,5,11,5,13,11,7,11,13,13,5,5,11,5,7,11,13,11,5,9,9,13,5,11,13,9,7,7,13,11,11,13,7,13,7,9,11,9,13,5,9,13,11,9,11,5,11,5,13,11,5,9,13,7,9,5,7,7,5,9,7,13,9,5,7,9,7,13,11,11,9,9,7,9,11,7,5,9,11,13,7,5,11,5,9,5,11,11,9,7,7,9,11~8,6,12,12,4,8,6,4,4,12,8,6,4,10,6,4,12,4,4,4,4,4,10,4,4,10,6,6,4,8,12,12,10,4,12,6,10,12,12,4,4,8,8,8,8,8,12,8,4,8,8,10,8,4,8,8,4,8,10,8,6,10,10,8,12,10,6~5,13,6,6,4,6,2,11,5,9,11,11,5,6,13,11,9,13,12,7,8,10,5,5,5,9,10,5,4,12,4,6,8,13,11,9,8,5,12,4,11,12,10,5,13,11,9,10,11,11,11,5,5,13,6,10,12,2,8,13,12,13,7,9,4,7,10,7,8,12,7,5,7,13,8,4~12,11,7,8,5,10,7,7,4,10,8,5,13,4,8,13,9,5,12,5,10,5,13,9,5,4,13,4,6,7,10,6,5,7,5,13,10,6,10,6,9,11,5,7,13,13,11,2,8,12,12,11,13,12,5,9,10,4,5,11,9,9,12,9,8,11,8,11,9,7,12,8,12,4,2,6,6,4,8,10,13,11,6,13~5,9,4,9,13,10,11,8,6,9,13,7,5,8,13,8,13,11,7,5,10,6,4,10,5,6,12,9,13,6,11,7,10,10,12,10,5,5,11,5,4,10,13,11,13,8,12,9,10,5,8,8,11,12,11,7,10,11,7,12,10,13,13,8,4,12,9,12,4,8,13,12,6,10,7,8,11,12,11,6,4,5,11,6,7,11,8,10,13,6,4,9,11,4,9,8,7,12,13&reel_set1=13,10,6,6,7,5,8,4,12,11,9,4,4,5,13,9,12,6,13,10,5,6,6,5,8,13,6,11,7,8,9,4,5,5,5,7,12,12,5,9,9,12,10,4,6,8,5,6,11,12,10,8,13,8,4,7,9,10,7,11,11,7,4,11,10,11,9,11,12,12,12,10,9,10,8,4,5,9,11,6,5,12,11,9,13,7,12,7,13,4,11,10,13,12,9,12,5,13,4,6,10,4,10,8~10,7,10,13,2,9,4,7,10,6,11,8,4,4,12,9,7,6,11,4,4,4,12,5,8,13,5,4,13,7,9,12,2,10,12,9,8,11,13,5,4,12,5,9,9,9,11,8,6,5,9,4,5,4,9,4,10,11,12,6,13,5,11,6,13,6,8~12,4,12,10,10,12,7,9,5,12,4,9,12,9,4,13,10,7,11,11,8,4,8,5,6,8,13,13,4,13,2,6,4,4,4,7,13,11,4,6,13,9,5,4,5,6,11,9,12,4,13,7,12,9,6,10,12,10,8,6,4,2,12,9,10,11,11,11,10,13,7,7,5,11,7,4,12,6,10,12,12,11,8,5,4,5,9,13,7,11,8,6,7,5,8,9,6,8,13,11,4,12~10,5,11,10,6,5,6,12,5,9,7,5,10,7,12,8,9,13,8,4,8,12,5,4,8,8,9,12,12,11,13,8,10,6,4,5,11,9,8,12,7,11,9,13,7,13,12,4,13,9,11,5,7,9,13,12,4,4,6,11,5,10,6,13,4,11,10,4,12,4,7,4,4,13,6,9,10~7,10,12,11,4,11,4,5,7,13,4,8,11,11,8,11,12,12,10,4,13,11,12,12,10,9,6,9,11,13,7,10,9,12,5,4,6,10,10,13,6,7,6,5,8,11,5,8,13,9,7,12,5,6,9,13,10,8,9&reel_set4=9,6,13,4,6,10,6,13,9,13,10,13,11,6,9,10,6,6,6,5,7,6,12,10,7,7,12,12,13,12,12,8,4,10,8,5,11,5,5,5,4,10,8,5,11,9,10,11,13,11,13,5,10,6,4,9,11,9,12,12,12,10,12,8,6,6,5,4,10,8,11,7,7,9,12,11,13,11,5,13,13,13,5,8,7,13,8,11,12,5,8,4,6,8,6,4,5,7,8,4~5,4,9,13,4,13,13,2,7,7,13,5,13,11,8,8,8,8,7,10,8,4,8,11,6,6,11,12,13,8,6,7,8,9,6,6,6,6,7,4,11,8,8,5,4,6,8,9,2,6,6,5,4,7,7,7,8,12,10,6,5,9,2,6,12,11,12,2,4,7,8,9,12,12,12,10,10,12,13,7,5,8,4,10,6,12,6,4,10,5,11,11,11,8,12,2,11,11,9,5,2,6,13,10,11,13,9,6,4,6~6,7,6,2,13,8,6,6,4,6,2,5,5,13,9,13,2,7,6,6,6,10,10,7,13,6,10,10,13,4,9,7,12,9,4,12,4,12,5,8,12,12,12,8,5,11,12,13,6,13,6,10,11,10,13,7,11,8,7,5,8,4,5,5,5,6,5,11,12,9,6,9,7,8,11,13,10,11,12,9,11,8,11,6,9,7,7,7,12,6,7,6,5,13,5,12,11,6,12,4,13,13,4,11,7,8,10,12,13~11,10,9,7,10,11,9,4,2,11,5,13,7,10,9,9,9,10,7,13,13,6,13,6,10,5,6,4,12,8,6,11,4,7,7,7,7,13,4,8,6,7,8,5,6,9,12,8,7,8,6,5,6,6,6,13,11,4,12,12,13,9,12,4,9,5,10,10,5,6,8,13,13,13,6,7,8,6,11,9,4,13,9,11,4,2,8,13,2,12,12~10,8,9,11,6,4,8,13,4,12,13,9,12,4,4,4,4,12,6,7,13,12,5,4,12,11,6,8,13,4,11,13,13,13,13,12,11,6,4,13,4,10,11,13,10,11,10,7,11,11,11,8,4,5,10,4,8,12,5,10,13,9,5,11,6,6,6,8,13,13,8,4,10,13,9,10,9,11,6,4,9,9,9,8,7,8,5,8,7,6,12,6,9,10,12,11,9,10,10,10,10,9,5,7,10,13,11,7,5,12,8,10,9,7,13,7,10&purInit=[{bet:2000,type:"default"},{bet:3000,type:"default"},{bet:4000,type:"default"}]&reel_set3=4,8,8,10,6,8,10,4,10,6,6,4,12,4,6,4,4,12,4,4,12,10,10,4,4,4,4,4,8,8,12,4,4,6,6,8,6,8,6,12,4,8,12,6,12,8,4,8,4,12,4,12,6,8,8,8,8,8,12,10,8,8,10,8,10,6,4,10,12,8,6,4,8,10,4,4,8,10,10,12,4,12,12,10~5,7,9,11,9,7,11,5,7,7,11,11,7,9,11,7,9,11,13,11,5,13,9,9,11,7,13,11,9,13,13,11,7,11,7,9,5,5,11,5,13,7,11,13,5,9,13,7,11,13,13,11,5,9,13,9,9,5,5,9,5,13,5,5,7~13,5,11,12,7,4,5,7,6,6,8,13,11,4,11,6,13,10,13,11,10,6,11,5,5,8,5,11,13,12,7,5,5,5,4,9,5,5,8,10,7,13,9,5,11,4,12,8,10,12,7,6,9,13,12,9,13,10,4,8,12,9,12,11,8,5,6,11,11,11,13,7,10,5,6,7,8,6,7,10,13,10,12,7,2,6,9,13,11,13,4,5,8,4,9,12,8,2,9,5,12,5,4,11~5,9,8,4,10,11,5,2,5,10,6,13,7,6,5,5,12,9,2,13,4,10,5,8,7,12,8,6,8,13,11,6,12,4,7,10,12,12,4,5,6,13,13,11,13,8,4,12,11,10,9,9,7,9,11,4,13,5,7,8~5,7,8,12,8,11,7,9,11,10,7,7,8,9,7,13,8,12,4,8,4,9,13,9,13,13,10,5,11,6,11,6,5,13,10,5,12,13,6,4,11,12,12,8,6,4,10,5,10,6,12,11,10,10,13,8,11,11&reel_set6=13,6,6,6,13,6,6~6,6,6,6,12,6,13,6,9~6,13,6,6,6,6,13,6,11,6~6,5,10,13,10,9,6,8,11,10,4,9,5,13,12,8,7,6,9,5,11,4,2,8,8,5,9,13,12,6,7,12,6,12,5,10,6,5,6,11,7,10,4,12,11,12,7,9,4,9,13,12,5,10,11,13,2,9,8,4,12,13,6,6,7,11,8,13~10,11,13,7,7,9,6,11,7,8,13,4,13,9,13,12,10,6,4,10,8,11,9,5,4,10,6,6,12,9,10,13,8,5,11,8,10,8,12,4,12,11,11,9,8,6,7,5,11,12,10,5,12,10,11,13,9,4,12,7,6,12,13,9&reel_set5=4,4,4,4,4~4,4,4,4,4,4~4,4,4,4,4,4~9,5,7,11,6,9,9,8,13,4,8,8,4,5,13,10,5,4,9,12,12,7,6,12,7,10,5,4,9,10,13,6,10,13,11,13,12,9,5,13,12,8,10,13,7,4,10,11,8,5,7,6,11,4,12,6,13,2,6,4,7,7,8,11,4,12,4,12,12,10,9,12,5,9,5,6,11,5,8,13,13,4,9,8,2,4,11,10,6,10,12,9,7,5,4,11,12,11,8,4~13,11,6,4,6,11,5,10,6,9,4,4,9,4,4,10,13,10,11,10,11,10,8,10,5,8,5,6,11,11,12,7,4,13,8,12,5,12,12,8,11,9,10,12,9,7,8,13,12,7,9,9,13,10,8,12,8,5,4,7,11,12,7,13,11,5,12,11,7,9,10,13,13,6,6,9&reel_set8=12,11,13,4,8,6,13,4,8,13,8,7,9,6,11,13,11,5,13,11,4,4,4,10,13,9,11,12,12,6,5,4,5,10,4,8,11,9,9,12,7,9,6,6,10,13,13,13,11,10,8,6,7,5,10,12,5,10,5,8,6,4,13,10,5,8,4,7,12,7~12,7,8,4,5,12,6,8,13,8,9,10,9,5,6,10,11,7,10,9,9,13,4,4,11,11,8,12,5,10,5,5,5,7,13,5,11,11,13,4,8,12,5,10,11,11,7,10,8,10,13,6,11,5,8,9,10,13,11,13,2,5,4,4,5,8,8,8,12,5,4,13,6,8,4,8,6,12,8,4,5,7,9,9,12,5,6,6,9,13,8,5,12,4,7,6,7,6,2,5,12~10,6,5,8,9,11,4,4,11,13,13,7,12,6,12,10,8,13,4,13,13,5,5,5,4,10,7,9,13,7,9,9,10,5,6,8,6,5,9,13,11,8,4,12,11,12,11,11,11,13,5,5,8,8,12,2,10,7,12,6,5,11,12,5,7,11,5,6,5,11,7~7,10,13,4,5,4,13,9,10,9,9,5,6,2,11,11,5,11,8,12,13,6,7,5,8,5,8,9,8,8,13,11,12,10,5,2,12,13,13,11,4,13,9,9,4,7,13,4,10,4,7,12,11,12,10,6,12,10,6,6,5,8,5,7~11,7,10,8,10,13,11,4,11,13,13,9,13,5,8,13,9,5,7,8,9,10,12,12,8,6,10,6,5,12,11,11,9,11,12,6,7,10,5,13,8,6,4,8,5,5,7,6,7,8,12,10,7,4,12,5,10,13,11,11,7,11,10,7,10,4,8,4,6,4,5,5,4,6,9,11,13,11,13,10,11,13,9,8,12,8,12,9,12,6,10,13&reel_set7=7,7,7,13,7,7~13,7,7,7,7,12,7,9,7~7,11,7,7,7,7,13,13,7~9,8,5,6,10,6,9,9,11,2,11,7,12,5,12,9,8,11,10,13,11,13,12,8,7,4,6,9,5,4,13,5,12,7,7,13,8,4,2,6,4,11,12,12,13,6,9,12,7,10,10,6,7,8,10,7,10,8,4,5,4,12,5,7,13,5,13,11,7,9~12,7,9,12,8,6,9,9,5,9,8,11,12,7,4,9,12,10,11,11,7,13,12,7,6,10,13,13,8,6,6,12,7,13,5,13,11,10,4,8,9,10,13,5,9,11,8,12,9,4,10,12,13,5,9,7,11,9,10,8,10,11,10,7,11,10,11,6,4,9,13,5,4,6,10,11,13,12,11,5,10,4,4,5,12,6,9,12,4,8,10,11,7,13,5,6,4,7,7,11,11,5,12,10,12,6,13,8&total_bet_min=10.00';
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
                $pur = -1;
                if(isset($slotEvent['pur'])){
                    $pur = $slotEvent['pur'];
                }
                $bl = 0; //$slotEvent['bl'];
                $slotEvent['slotLines'] = 20;
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    $slotEvent['slotEvent'] = 'freespin';
                }
                $lines = $slotEvent['slotLines'];
                $betline = $slotEvent['slotBet'];
                if( $slotEvent['slotEvent'] == 'doSpin' || $slotEvent['slotEvent'] == 'freespin' || $slotEvent['slotEvent'] == 'respin' ) 
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

                // $winType = 'bonus';

                $allBet = $betline * $lines;
                $purMuls = [100, 150, 200];
                if($pur >= 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $allBet * $purMuls[$pur];
                }else if($bl > 0){
                    $allBet = $betline * 30;
                }
                $tumbAndFreeStacks = []; 
                $isGeneratedFreeStack = false;
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
                    if($winType == 'bonus'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'IsBonusBank', 1);
                    }else{
                        $slotSettings->SetGameData($slotSettings->slotId . 'IsBonusBank', 0);
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bl', $bl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinWin', 0);
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
                $bonusMpl = 1;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $str_initReel = '';
                $fsmore = 0;
                $str_stf = 0;
                $str_srf = 0;
                $str_trail = 0;
                $fsmore = 0;
                $str_rs_p = '';
                $str_rs_c = '';
                $str_rs_m = '';
                $str_rs_t = '';
                $str_rs_more = '';
                $str_rs_iw = '';
                $str_rs_win = '';
                $str_rs = '';
                $fsmax = 0;
                $strReelSa = '';
                $strReelSb = '';
                $str_s_mark = '';
                if($slotEvent['slotEvent'] == 'freespin' || $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') >= 0){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_initReel = $stack['initreel'];
                    $currentReelSet = $stack['reel_set'];
                    $fsmore = $stack['fsmore'];
                    $str_stf = $stack['stf'];
                    $str_srf = $stack['srf'];
                    $str_rs_p = $stack['rs_p'];
                    $str_rs_c = $stack['rs_c'];
                    $str_rs_m = $stack['rs_m'];
                    $str_rs_t = $stack['rs_t'];
                    $str_rs_more = $stack['rs_more'];
                    $str_rs_iw = $stack['rs_iw'];
                    $str_rs_win = $stack['rs_win'];
                    $str_rs = $stack['rs'];
                    $strReelSa = $stack['sa'];
                    $strReelSb = $stack['sb'];
                    $str_s_mark = $stack['s_mark'];
                    $fsmax = $stack['fsmax'];
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
                    $currentReelSet = $stack[0]['reel_set'];
                    $fsmore = $stack[0]['fsmore'];
                    $str_stf = $stack[0]['stf'];
                    $str_srf = $stack[0]['srf'];
                    $str_rs_p = $stack[0]['rs_p'];
                    $str_rs_c = $stack[0]['rs_c'];
                    $str_rs_m = $stack[0]['rs_m'];
                    $str_rs_t = $stack[0]['rs_t'];
                    $str_rs_more = $stack[0]['rs_more'];
                    $str_rs_iw = $stack[0]['rs_iw'];
                    $str_rs_win = $stack[0]['rs_win'];
                    $str_rs = $stack[0]['rs'];
                    $strReelSa = $stack[0]['sa'];
                    $strReelSb = $stack[0]['sb'];
                    $str_s_mark = $stack[0]['s_mark'];
                    $fsmax = $stack[0]['fsmax'];
                    $str_trail = $stack[0]['trail'];
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
                    if($slotSettings->GetGameData($slotSettings->slotId . 'IsBonusBank') > 0){
                        $slotSettings->SetBank(('bonus'), -1 * $totalWin);
                    }else{
                        $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                    }
                }

                $_obf_totalWin = $totalWin;
                $isState = true;
                if($fsmax > 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $fsmax);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'IsBonusBank', 0);
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
                $strLastReel = implode(',', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                if($str_rs_p == ''){
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                }else{
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', 0);
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
                    if($fsmax > 0){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1';
                    }
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
                if($str_stf != ''){
                    $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                }
                if($str_srf != ''){
                    $strOtherResponse = $strOtherResponse . '&srf=' . $str_srf;
                }
                if($str_trail != ''){
                    $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                }
                if($str_rs_p != ''){
                    $isState = false;
                    $spinType = 's';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $strOtherResponse = $strOtherResponse . '&rs_p=' . $str_rs_p . '&rs_m=' . $str_rs_m . '&rs_c=' . $str_rs_c;
                }
                if($str_rs_t != ''){
                    $strOtherResponse = $strOtherResponse . '&rs_t=' . $str_rs_t;
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0){
                        $isState = true;
                        $spinType = 'c';
                    }
                }
                if($str_rs_iw != ''){
                    $str_rs_iw = str_replace(',', '', $str_rs_iw) / $original_bet * $betline;
                    $strOtherResponse = $strOtherResponse . '&rs_iw=' . $str_rs_iw;
                }
                if($str_rs_win != ''){
                    $str_rs_win = str_replace(',', '', $str_rs_win) / $original_bet * $betline;
                    $strOtherResponse = $strOtherResponse . '&rs_win=' . $str_rs_win;
                }
                if($str_rs_more != ''){
                    $strOtherResponse = $strOtherResponse . '&rs_more=' . $str_rs_more;
                }
                if($str_rs != ''){
                    $strOtherResponse = $strOtherResponse . '&rs=' . $str_rs;
                }
                if($str_s_mark != ''){
                    $strOtherResponse = $strOtherResponse . '&s_mark=' . $str_s_mark;
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&wlc_v=' . $strWinLine;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance . '&reel_set='. $currentReelSet.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=10&st=rect&c='.$betline.'&sw=5&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&w='.$totalWin.'&s=' . $strLastReel;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) && $str_rs_p == '') 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if( $slotEvent['slotEvent'] != 'freespin' && $fsmax > 0 && $str_rs_p == '') 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"CurrentRespin":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin'). ',"IsBonusBank":' . $slotSettings->GetGameData($slotSettings->slotId . 'IsBonusBank') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RespinWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"Bl":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bl') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if(($slotEvent['slotEvent'] == 'freespin' || ($str_rs_p == '' && $str_rs_t != '')) && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * $purMuls[$slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin')];
                }else if($slotSettings->GetGameData($slotSettings->slotId . 'Bl') > 0 && $isState == true){
                    $allBet = $betline * 15;
                }
                if($str_rs_p == '' && $str_rs_t != '' && $isState == true){
                    $slotEvent['slotEvent'] == 'doRespin';
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

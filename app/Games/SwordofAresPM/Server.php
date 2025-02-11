<?php 
namespace VanguardLTE\Games\SwordofAresPM
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
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [1,7,4,9,16,11,4,9,7,16,4,7,5,7,10,8,5,6,4,6,16,16,3,7,16,10,6,3,16,10]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
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
                $fsmore = 0;
                if(isset($stack)){
                    $str_s_mark = $stack['s_mark'];
                    $str_trail = $stack['trail'];
                    $currentReelSet = $stack['reel_set'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $rs_more = $stack['rs_more'];
                    $fsmore = $stack['fsmore'];
                    $fsmax = $stack['fsmax'];
                    $strWinLine = $stack['win_line'];
                    $apwa = str_replace(',', '', $stack['apwa']);
                    $apv = $stack['apv'];
                    $str_apt = $stack['apt'];
                    if($strWinLine != ''){
                        $arr_lines = explode('&', $strWinLine);
                        for($k = 0; $k < count($arr_lines); $k++){
                            $arr_sub_lines = explode('~', $arr_lines[$k]);
                            $arr_sub_lines[1] = str_replace(',', '', $arr_sub_lines[1]) / $original_bet * $bet;
                            $arr_lines[$k] = implode('~', $arr_sub_lines);
                        }
                        $strWinLine = implode('&', $arr_lines);
                    }
                    if($apwa > 0){
                        $apwa = $apwa / $original_bet * $bet;
                    }
                    if($rs_p >= 0){
                        $strOtherResponse = $strOtherResponse . '&rs_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') . '&rs_p=' . $rs_p . '&rs_c='. $rs_c .'&rs_m=' . $rs_m;
                    }
                    else if($rs_t>0){
                        $strOtherResponse = $strOtherResponse.'&rs_t='. $rs_t .'&rs_win='.($slotSettings->GetGameData($slotSettings->slotId . 'TumbWin')); // .'&tmb_res='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbWin')
                    }
                    if($str_s_mark != ''){
                        $strOtherResponse = $strOtherResponse . '&s_mark=' . $str_s_mark;
                    }
                    if($str_trail != ''){
                        $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                    }
                    if($rs_more > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_more=' . $rs_more;
                    }
                    
                    if($apv > 0){
                        $strOtherResponse = $strOtherResponse . '&apwa=' . $apwa . '&apv=' . $apv . '&apt=' . $str_apt;
                    }
                    if($strWinLine != ''){
                        $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                    }
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0 || $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 )
                {
                    $fs = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                    if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) || ($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0)) 
                    {
                        $isEnd = true;
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
                $Balance = $slotSettings->GetBalance();      
                $response = 'def_s=1,7,4,9,16,11,4,9,7,16,4,7,5,7,10,8,5,6,4,6,16,16,3,7,16,10,6,3,16,10&balance='. $Balance .'&cfgs=6669&ver=3&index=1&balance_cash='. $Balance .'&def_sb=3,7,5,7,5,11&reel_set_size=4&def_sa=3,6,10,4,16,10&reel_set='.$currentReelSet.'&balance_bonus=0.00&na=s&scatters=1~100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,15,3,0,0,0~0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0~1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"6426497",max_rnd_win:"10000"}}&wl_i=tbm~10000&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=3,6,10,4,16,10&sb=3,7,5,7,5,11&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=50.00&purInit_e=1&sh=5&wilds=2~0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0~1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1&bonuses=0&st=rect&c='. $bet .'&sw=6&sver=5&counter=2&paytable=0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,80,80,50,50,0,0,0,0,0,0,0;150,150,150,150,150,150,150,150,150,150,150,150,150,150,150,150,150,150,150,60,60,40,40,0,0,0,0,0,0,0;100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,40,40,30,30,0,0,0,0,0,0,0;60,60,60,60,60,60,60,60,60,60,60,60,60,60,60,60,60,60,60,24,24,20,20,0,0,0,0,0,0,0;50,50,50,50,50,50,50,50,50,50,50,50,50,50,50,50,50,50,50,20,20,16,16,0,0,0,0,0,0,0;40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,16,16,10,10,0,0,0,0,0,0,0;30,30,30,30,30,30,30,30,30,30,30,30,30,30,30,30,30,30,30,12,12,8,8,0,0,0,0,0,0,0;20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,20,8,8,5,5,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,120,120,80,80,0,0,0,0,0,0,0&l=20&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=4,9,7,8,8,10,4,9,10,9,4,16,6,8,10,6,16,3,5,9,7,7,6,6,9,3,10,5,10,10,4,10,9,10,9,7,8,6,4,10,7,7,7,6,1,4,10,6,5,7,7,3,3,5,8,9,8,7,7,10,9,5,8,10,8,5,3,16,5,7,10,8,6,10,5,10,7,5,7,10,6,7,9,6,11,9,9,9,7,6,10,5,8,3,9,6,7,7,8,10,3,5,7,4,4,9,6,9,5,7,7,4,7,4,4,9,8,6,5,9,16,3,1,7,9,6,8,3,9,8,8,8,16,5,8,6,9,7,4,8,3,8,11,7,4,3,16,16,5,8,5,9,6,3,4,16,7,9,5,7,10,6,7,8,10,8,6,3,9,7,6,3,9,6,6,6,10,4,9,9,4,4,10,9,6,9,5,7,7,9,4,9,11,6,9,7,5,7,6,9,9,10,7,16,8,8,4,8,7,3,1,16,10,4,10,5,7,6,10,10,10,9,9,8,10,7,9,10,9,6,8,1,3,5,1,7,16,16,8,8,5,9,5,9,7,8,3,8,6,10,7,5,16,9,10,8,4,9,7,6,6,7,3,6~10,9,8,5,7,10,6,5,8,7,8,10,3,6,11,16,16,6,3,7,3,8,8,5,5,7,1,5,7,6,6,9,3,7,10,10,8,10,8,4,1,7,6,9,16,10,5,5,6,6,1,6,6,10,10,10,6,9,6,8,10,10,7,1,9,5,16,8,6,9,10,8,5,8,5,7,10,9,6,9,6,6,4,3,8,4,9,11,8,9,10,16,6,4,5,10,3,10,6,10,8,9,8,6,4,3,5,9,6,8,6,10,6,6,6,10,8,9,8,9,3,4,7,4,6,10,4,3,9,5,4,8,1,9,7,10,4,16,10,5,10,16,10,10,5,6,5,8,6,8,6,9,8,4,3,7,6,10,5,1,10,8,4,4,9,7,8,10,7,10,9,8,8,8,7,3,10,3,9,7,16,9,4,16,6,8,16,6,11,6,7,6,10,8,10,5,8,6,16,9,6,16,8,10,6,5,4,5,4,7,1,8,3,6,6,10,7,4,7,3,6,10,16,6,9,8,8,3,6,9,9,9,3,6,9,9,7,16,4,8,10,8,3,6,10,7,7,4,10,8,9,8,10,8,10,3,9,8,8,9,10,4,3,5,5,6,4,16,4,6,16,8,5,16,6,6,10,5,5,8,8,10,8,8,3,7,9,9,7,7,7,5,5,10,10,4,5,6,10,5,9,10,8,7,6,6,3,3,8,3,6,6,9,6,8,5,4,8,3,10,10,16,4,9,4,4,6,8,10,6,6,7,10,5,10,8,4,6,9,7,10,9,8,11,8,7,6,8~6,10,6,10,4,5,5,6,10,5,3,10,8,10,7,6,9,6,10,8,5,9,7,6,6,16,3,16,8,9,10,4,8,1,8,10,10,5,10,7,1,7,9,4,9,8,5,7,8,6,7,10,6,9,4,10,9,16,7,7,7,6,10,8,4,9,9,4,6,7,9,3,10,10,5,4,4,10,5,10,6,9,6,10,7,6,8,1,8,10,9,5,8,8,6,9,16,6,7,10,6,4,9,8,6,4,1,8,7,10,3,8,3,16,7,6,8,6,3,9,10,9,9,9,4,8,7,4,9,5,4,6,4,10,16,7,16,7,10,9,6,10,5,10,6,5,5,1,6,6,11,10,7,4,4,5,5,7,5,6,9,5,16,3,8,10,4,5,9,8,7,16,4,3,11,6,3,16,10,7,7,3,8,3,8,8,8,9,10,3,10,3,10,5,4,6,6,9,7,9,7,6,7,9,7,7,9,4,5,9,6,10,16,3,6,10,9,8,5,10,5,8,11,7,6,4,5,4,9,5,4,8,7,8,10,7,16,1,10,3,16,6,9,10,8,7,7,6,6,6,4,9,7,10,8,16,6,8,7,16,8,6,5,10,8,3,7,1,16,7,4,5,8,7,5,16,3,11,7,10,8,16,8,8,10,10,6,10,9,9,8,10,3,8,10,3,3,6,4,8,5,10,10,9,5,9,9,10,10,6,10,10,10,3,10,10,8,10,7,1,4,8,10,8,9,6,10,9,10,3,4,9,8,6,10,8,7,10,9,10,16,5,3,8,5,10,5,8,4,6,9,7,6,8,8,10,7,9,3,5,10,6,9,3,7,4,8,3,8,10,5,6,7,8~6,6,3,9,9,8,3,10,4,4,3,7,10,3,9,8,7,9,9,4,9,10,5,5,4,3,10,9,10,10,7,9,6,8,4,6,7,3,1,8,6,7,8,16,5,7,10,6,6,7,9,4,7,8,8,3,7,8,7,8,3,3,8,7,1,9,4,8,5,9,7,1,8,16,7,7,7,9,8,9,3,8,6,5,8,7,8,16,5,6,9,4,16,9,8,8,9,4,16,4,7,9,7,6,16,7,3,9,5,5,7,8,9,7,10,8,11,4,5,3,9,6,7,16,3,6,10,8,10,8,7,7,10,8,4,10,7,8,10,5,9,10,7,16,10,6,5,3,8,16,10,10,8,8,8,9,16,11,6,8,8,5,8,4,10,8,4,7,3,8,6,9,10,8,7,7,5,3,8,7,9,8,3,10,3,6,9,5,9,10,7,8,9,5,9,6,9,7,7,10,8,7,10,4,8,7,7,4,4,11,5,10,9,10,3,4,4,10,7,6,9,5,8,16,5,5,7,10,8,4,1,9,9,9,10,10,6,7,7,6,9,5,9,8,8,16,8,7,10,7,7,6,3,7,10,10,4,9,7,6,8,16,6,6,16,8,5,4,5,7,10,16,7,7,16,10,8,7,10,8,5,7,5,7,6,10,7,4,5,4,7,10,4,7,8,7,8,5,7,9,10,8,6,10,5,7,6,8,9,10,10,10,8,10,4,9,5,7,7,5,16,3,7,1,3,16,8,10,10,7,7,8,5,10,8,4,9,10,10,8,5,3,1,8,16,4,9,10,11,6,4,10,7,3,8,3,10,7,10,4,9,5,8,4,9,7,7,8,3,5,1,8,4,1,10,7,7,9,10,9,5,5,16,7,5,3,3,7,9~4,16,6,5,16,10,9,5,8,3,3,9,6,10,8,9,5,4,7,5,9,4,10,9,6,1,10,5,9,9,5,5,8,8,7,8,10,9,6,16,10,5,4,7,8,7,10,8,4,5,9,9,5,16,3,5,3,5,3,4,8,9,9,9,7,8,6,4,10,9,10,16,11,7,9,7,4,1,6,10,11,10,3,9,6,6,7,10,3,8,9,3,16,6,9,16,3,10,9,7,10,6,7,9,10,7,3,9,9,6,16,1,7,4,9,9,8,10,8,4,8,6,5,6,7,3,6,6,6,10,5,7,10,6,6,9,6,4,5,6,4,9,3,3,5,10,9,9,4,6,7,6,3,8,10,8,1,16,7,6,9,8,5,6,10,8,16,5,6,8,7,5,6,1,16,3,8,6,10,5,8,7,4,6,7,8,6,4,9,9,6,8,8,8,10,5,9,1,9,10,6,16,7,4,3,7,7,9,9,6,6,3,5,9,5,4,9,8,3,7,6,6,7,10,16,5,3,8,8,9,8,5,10,5,7,5,5,3,10,4,3,10,6,7,10,1,5,7,8,9,7,5,8,9,8,7,10,10,10,9,9,7,10,5,5,10,9,11,7,9,8,8,16,4,10,9,6,7,4,9,5,8,9,9,3,7,6,9,7,3,6,4,11,9,1,6,4,4,10,6,8,7,9,9,10,6,4,6,3,3,7,10,9,4,4,5,9,10,10,9,7,7,7,6,4,10,8,9,9,6,10,3,4,16,7,8,7,5,7,10,7,9,8,10,6,4,9,10,9,5,7,10,4,10,10,6,7,16,8,8,9,6,5,9,16,4,16,7,9,6,6,16,5,8,3,10,6,10,6,16,8,10,9,6,10,9,5~8,5,4,8,4,7,10,4,3,3,7,6,10,9,6,10,5,8,8,6,7,6,9,7,10,8,6,6,9,5,11,8,7,5,7,9,11,4,6,5,9,9,9,4,9,6,10,10,16,8,5,7,9,7,9,7,6,1,7,9,5,8,6,6,5,3,10,8,9,10,9,7,1,3,4,8,10,10,4,8,9,10,9,5,8,8,8,3,6,9,10,9,7,3,7,9,16,8,8,7,8,7,10,8,9,3,7,5,6,16,10,9,9,8,3,16,5,8,4,16,3,6,4,9,10,10,9,7,6,6,6,8,9,8,8,3,7,16,9,5,3,9,10,10,5,1,6,16,6,8,6,4,16,1,10,8,16,3,5,4,7,5,9,6,3,5,10,10,4,10,8,9,10,10,10,5,9,5,7,10,6,4,7,6,9,3,4,4,7,9,7,3,9,6,9,7,5,8,8,1,3,9,10,4,8,8,6,10,4,8,8,7,10,6,16,9,7,7,7,16,8,8,10,6,9,10,7,4,4,10,10,1,5,9,16,9,4,9,5,3,4,10,8,5,10,9,10,8,8,3,6,10,11,9,16,5,8,9,9,8,9,6&s='.$lastReelStr.'&reel_set2=10,5,8,16,5,9,6,4,3,4,7,8,9,4,10,7,8,10,6,4,16,5,3,9,10,6,8~6,16,5,4,10,8,4,9,10,7,6,8,4,8,9,7,16,7,9,8,4,9,3,10,3,5,6,5~4,3,8,7,16,9,7,8,4,3,6,8,5,9,10,9,7,10,8,16,6,10,6,4,9,5,9~7,10,3,4,16,6,9,10,16,9,4,5,9,6,5,9,5,8,6,8,10,3,4,7,4,8,9,6,7~6,10,16,9,8,7,8,10,8,3,4,5,4,6,3,4,9,16,6,7,5~9,3,9,7,5,3,9,8,7,10,5,6,16,6,4,10,9,10,8,5,7,4,6,16,10,6,10,4,8,4&reel_set1=16,9,7,7,11,7,4,10,5,7,5,16,6,6,9,4,4,9,9,4,4,9,10,10,8,5,7,6,16,6,8,3,10,6,7,3,7,6,3,10,7,5,3,6,8,10,5,4,7,3,6,4,3,7,10,10,10,5,9,10,16,10,5,6,10,10,4,5,16,16,9,9,4,9,4,7,7,10,8,8,6,8,9,11,4,8,3,8,6,7,4,7,8,10,6,7,10,9,6,10,6,3,8,4,7,8,10,8,6,4,8,7,7,7,9,8,7,5,7,6,8,10,6,9,5,9,8,9,7,9,7,7,9,16,10,6,4,7,9,10,3,7,4,7,4,10,9,4,10,6,7,3,9,16,9,10,8,8,9,8,6,7,7,10,7,9,4,5,7,5,5,5,6,3,3,10,5,7,7,9,16,10,11,9,6,10,8,5,9,3,5,8,8,9,10,6,9,6,5,8,10,4,7,3,10,10,9,10,9,9,6,9,8,7,16,16,9,5,8,10,8,8,10,16,9,5,6,9,9,9,3,9,6,8,10,9,10,5,7,6,16,10,10,8,16,7,10,7,6,9,9,11,4,10,6,9,5,4,16,10,7,10,5,10,8,8,9,8,5,9,7,9,8,7,5,6,6,9,6,6,8,3,8,3,8,6,6,6,10,9,10,6,8,9,10,10,16,16,6,9,7,5,9,6,5,10,8,8,5,6,16,10,8,8,3,7,10,6,9,8,6,5,7,10,8,3,7,7,5,7,5,9,8,6,16,4,11,5,9,4,9,10,8,8,8,16,10,10,4,5,9,3,5,10,4,7,6,7,9,10,10,9,5,7,8,10,3,9,7,3,3,8,8,7,8,4,4,5,10,6,8,6,10,8,6,7,7,9,4,3,9,8,6,6,7,8,8,10,3,16,10,6~4,6,9,7,10,9,7,8,9,8,10,4,10,9,10,8,7,9,16,8,3,9,5,8,3,10,10,8,9,8,4,10,7,4,10,6,5,10,5,10,5,7,8,7,3,16,5,5,8,6,9,6,6,10,7,10,16,9,9,9,8,6,10,11,5,8,9,8,10,16,16,7,4,8,6,10,9,8,8,16,6,5,7,8,10,7,7,9,7,6,9,10,6,6,8,7,6,16,6,9,6,6,8,9,11,3,10,9,9,10,7,9,11,6,8,10,3,5,10,10,10,8,9,7,10,5,7,10,5,7,7,10,9,5,8,4,6,6,9,9,7,9,9,8,5,8,6,8,8,6,7,10,3,4,9,5,7,8,7,6,11,10,9,3,3,5,4,4,8,6,6,9,6,4,3,4,9,16,4,6,6,6,8,11,10,16,10,7,8,10,6,4,9,8,10,9,10,10,8,16,10,8,4,10,8,7,10,9,6,7,9,9,10,7,5,5,8,16,6,7,9,5,3,3,6,7,8,6,9,5,7,10,16,9,4,9,3,10,16,7,7,7,10,10,9,5,6,8,6,7,7,4,9,16,10,8,3,5,9,10,10,16,10,4,9,10,5,7,4,8,7,8,7,6,9,9,8,6,3,10,3,5,9,3,3,10,6,10,10,6,8,7,8,7,10,5,7,8,7,10,8,8,8,4,4,16,3,8,7,7,8,6,5,8,7,9,9,8,6,5,7,16,6,10,5,10,8,9,9,7,9,3,9,10,4,3,8,3,5,6,10,3,4,5,10,7,7,8,6,8,3,6,7,16,9,7,3,10,6,9,9,4,4,4,10,16,6,10,6,9,10,16,9,9,6,4,10,8,7,4,9,4,10,5,7,5,16,7,4,4,9,6,6,7,5,10,10,8,5,4,5,6,4,8,16,9,8,8,9,9,4,9,6,10,3,7,3,8,5,6,6,10,4~7,16,7,10,5,9,10,7,16,7,8,9,9,8,4,7,9,9,10,9,6,3,6,9,16,6,7,10,8,5,5,5,8,4,10,9,7,9,5,7,8,5,6,6,7,7,5,7,7,6,6,9,10,9,8,10,7,8,9,10,3,5,9,9,9,11,7,10,10,3,7,6,8,8,10,10,5,8,8,5,6,9,16,8,10,7,6,7,10,9,9,8,9,16,4,16,16,16,6,3,3,9,8,10,8,6,9,9,7,11,3,5,10,5,6,9,4,10,6,3,4,6,8,5,8,6,5,10,8,8,8,6,3,9,10,9,8,10,5,8,6,10,4,9,4,5,8,7,10,9,3,6,4,16,9,9,4,6,8,10,5,7,7,7,4,16,6,7,10,4,5,10,9,10,5,3,6,3,7,7,8,7,4,9,3,8,10,4,6,7,10,5,10,10,10,7,16,9,7,10,10,6,8,6,7,16,6,10,16,4,8,8,4,7,9,8,3,7,8,10,10,3,11,6,3,6,6,6,10,16,5,10,4,10,8,10,9,3,8,9,8,9,6,4,4,5,16,6,9,16,9,6,5,8,9,6,5,7,8~10,10,16,9,7,9,10,9,7,10,7,10,5,10,3,9,6,8,6,7,7,4,8,6,8,8,16,16,9,9,5,7,10,8,10,3,6,3,6,8,4,10,10,10,9,3,7,8,10,10,4,10,16,6,10,10,8,10,9,16,7,7,8,6,8,10,16,9,6,4,9,5,6,10,6,10,11,8,16,9,10,9,6,5,8,10,7,7,7,6,6,8,6,8,6,4,9,10,7,5,8,4,6,7,3,8,5,16,3,9,10,5,10,4,11,4,9,3,9,3,6,8,4,6,9,9,7,3,7,4,7,5,5,5,6,9,10,5,7,5,10,10,3,5,5,7,7,5,9,8,8,6,5,6,9,8,4,5,9,10,9,8,6,7,10,8,10,6,5,9,16,7,7,10,3,9,9,9,4,8,10,5,10,4,10,9,9,8,7,9,16,8,4,5,7,7,10,5,6,7,16,9,8,10,4,8,6,10,6,5,5,3,10,3,6,16,5,10,8,10,6,6,6,7,11,7,8,10,8,16,7,9,6,8,7,6,8,9,6,3,9,8,3,16,9,10,4,16,5,7,5,7,7,9,4,8,7,16,8,4,9,7,11,7,5,8,8,8,9,4,7,3,8,6,3,4,10,9,6,9,9,6,7,10,6,10,9,6,9,8,5,10,10,4,10,6,7,3,10,6,4,8,7,8,6,9,9,3,9,8~6,4,9,4,9,4,3,6,8,8,6,8,6,3,6,9,10,7,4,6,10,6,9,7,16,8,10,7,5,5,10,6,5,7,16,9,5,4,9,9,9,5,4,8,10,10,3,9,8,9,9,7,6,8,10,4,10,3,10,6,10,3,9,5,6,3,7,9,8,9,10,5,7,10,8,5,9,8,3,3,10,10,10,8,6,10,10,6,8,5,8,7,9,7,9,10,9,6,8,8,4,10,8,9,7,9,6,10,7,7,10,7,8,10,4,6,10,8,6,10,6,9,7,6,6,6,9,6,10,10,7,9,3,6,10,5,5,7,8,8,3,10,5,16,8,7,5,3,9,6,3,6,10,3,10,9,7,10,7,9,9,5,10,6,8,7,7,7,16,9,4,7,9,9,10,8,6,7,3,4,16,7,9,7,4,6,5,11,10,5,10,4,7,10,5,16,10,7,8,6,8,16,4,7,8,6,7,3,8,8,8,16,10,7,8,9,8,9,8,6,10,6,10,8,9,8,9,9,10,9,4,3,16,5,9,16,9,7,8,8,7,10,8,6,6,5,16,8,9,10,9,4,4,4,9,8,4,11,6,8,5,6,10,6,16,3,10,7,5,4,5,10,10,5,7,9,5,8,8,4,16,4,3,9,7,11,4,7,7,9,16,6,4,16,6~10,7,9,7,7,6,4,10,10,7,8,9,10,8,6,10,9,9,7,16,4,6,5,8,11,9,10,7,3,10,4,9,7,7,10,6,8,6,16,9,6,16,9,6,7,10,5,5,5,10,7,6,3,6,10,6,10,7,7,6,10,3,6,7,10,8,8,10,4,6,9,8,8,16,6,7,10,7,3,6,3,8,9,6,10,8,4,3,10,3,10,7,5,10,3,9,9,9,5,6,8,8,9,10,6,9,7,6,9,8,11,5,10,6,9,11,5,8,5,8,5,10,8,9,9,10,4,6,8,10,8,7,10,9,9,8,9,8,16,10,5,9,8,10,16,16,16,3,7,8,4,6,16,9,6,6,10,10,5,8,8,6,9,9,10,10,9,5,10,4,10,7,10,7,16,6,8,5,4,4,9,10,9,10,4,9,10,10,8,6,4,3,3,7,8,8,8,16,7,8,8,7,8,10,6,16,9,7,16,4,7,6,7,7,10,8,5,6,6,5,6,5,8,9,6,8,7,10,3,5,4,3,9,7,4,8,8,9,8,6,4,5,5,9,7,7,7,9,16,3,10,9,7,4,5,5,3,8,16,3,8,10,10,6,16,8,16,6,5,16,7,7,9,10,9,9,6,7,5,10,16,8,4,8,9,6,4,5,9,6,8,9,7,6,10,10,10,8,10,9,3,4,5,3,8,7,3,5,7,9,10,7,9,5,16,7,11,16,7,7,9,6,5,3,6,3,4,9,6,7,8,3,8,9,9,10,7,16,10,8,16,7,8,5,6,6,6,7,10,6,7,10,5,10,10,4,3,9,6,9,4,7,4,4,10,4,9,6,7,9,7,9,8,8,7,8,5,4,6,9,10,5,6,9,7,5,10,10,8,4,7,9,8,10,3&purInit=[{bet:2000,type:"default"}]&reel_set3=8,10,6,5,7,10,8,7,7,6,5,9,6,6,5,4,9,8,9,8,10,7,3,10,3,16,7,8,9,8,9,6,4,9,8,9,10,16,10,10,10,8,6,10,8,9,8,16,9,6,3,6,9,16,8,7,6,10,9,4,9,9,5,4,7,10,8,5,10,4,10,6,7,8,7,6,10,7,10,9,7,7,7,9,8,10,4,10,10,16,8,9,8,8,3,7,8,7,9,6,4,7,9,10,16,10,10,3,5,3,9,10,9,7,9,8,6,9,3,7,10,6,5,5,5,10,7,4,3,6,4,16,7,7,10,8,8,11,8,8,7,10,9,9,11,10,6,3,10,5,16,10,10,4,9,5,9,6,9,10,6,5,10,3,9,9,9,4,5,10,9,9,4,6,6,7,8,7,9,5,7,7,10,6,4,8,3,3,4,5,9,7,8,10,7,6,4,16,10,8,5,8,6,5,7,3,6,6,6,10,16,4,8,4,4,8,8,9,7,9,3,3,10,5,9,7,6,9,6,10,9,9,6,16,6,9,7,5,6,10,16,5,8,11,8,10,6,3,8,8,8,10,6,7,4,10,5,6,7,10,10,8,16,10,5,16,6,6,7,7,5,8,4,8,5,8,6,16,7,3,9,8,5,5,9,3,7,9,5,6,10,4~10,5,8,5,16,6,6,10,11,6,16,9,6,8,9,10,6,10,4,5,4,16,10,10,6,7,16,16,10,10,16,16,7,7,6,10,10,4,10,8,4,9,9,9,7,6,7,5,3,9,8,9,16,9,3,3,7,10,8,10,6,5,10,7,8,9,9,5,16,3,6,6,5,6,9,9,10,8,9,8,6,6,5,7,7,9,10,10,10,5,7,9,8,10,7,8,5,9,6,7,6,9,8,3,8,4,10,10,6,9,10,9,6,9,10,5,8,4,10,6,7,6,9,3,9,7,5,7,8,10,3,6,6,6,3,4,6,7,5,9,10,8,4,5,16,9,7,5,5,8,5,10,4,3,10,5,10,16,4,9,11,5,11,10,7,4,9,6,10,6,5,9,8,7,9,7,7,7,16,6,8,6,10,8,4,4,3,6,6,8,8,7,10,5,8,3,8,7,10,8,6,8,3,10,10,6,10,6,6,10,6,3,7,3,9,10,16,4,10,10,8,8,8,7,6,6,3,8,5,9,10,9,9,10,4,4,9,8,4,6,10,16,9,7,8,4,9,10,5,9,10,6,8,10,7,9,5,8,10,9,4,8,7,8,8,4,4,4,16,8,3,8,6,3,9,9,10,7,8,6,7,7,8,9,9,7,7,9,8,4,4,10,7,7,16,9,8,7,4,5,7,3,7,9,9,8,7,9,3,7,8~7,10,8,7,6,9,6,8,10,4,10,10,7,8,8,5,8,6,8,5,3,8,7,7,5,10,9,6,7,3,8,5,5,5,16,10,16,10,3,7,5,4,3,10,3,5,9,10,4,8,4,4,10,6,10,10,5,10,10,6,3,9,8,8,5,5,9,9,9,3,8,6,10,9,7,10,9,3,8,10,10,9,10,6,6,10,8,10,7,6,9,7,7,8,7,11,9,4,7,16,9,16,16,16,6,7,6,5,4,7,7,16,8,7,4,10,7,7,9,10,4,6,10,16,5,9,5,16,8,7,6,10,5,5,7,3,7,8,8,8,5,16,10,8,6,8,7,3,6,16,7,10,5,5,4,6,4,6,9,9,6,9,6,4,10,7,8,10,9,3,7,10,7,7,7,9,8,9,8,9,7,8,10,8,6,9,9,16,5,10,6,9,5,8,3,8,6,4,9,9,7,3,9,3,9,9,6,9,10,10,10,9,4,7,8,4,8,9,9,10,5,9,5,10,8,7,9,8,6,9,3,10,8,6,5,9,6,10,7,6,3,10,7,10,6,6,6,4,10,11,7,5,8,10,4,6,6,8,7,16,4,16,11,5,10,9,8,6,8,4,10,9,16,6,6,3,6,16,4,8,9~10,9,7,5,7,9,9,4,6,8,4,9,5,10,7,8,7,10,6,5,10,7,9,9,8,10,3,6,4,3,5,9,9,7,8,6,16,7,16,8,7,10,10,10,4,7,6,11,10,10,6,16,10,8,8,7,6,10,9,5,5,8,8,9,8,6,4,7,5,9,8,6,4,9,6,8,16,9,4,7,5,8,6,5,10,7,7,7,9,5,9,7,10,3,10,8,8,10,10,9,5,7,4,6,8,6,6,3,16,3,16,6,10,8,7,10,6,9,8,4,7,5,10,16,8,8,10,8,6,9,5,5,5,10,9,7,7,9,7,3,9,3,6,8,6,7,6,10,3,6,7,3,10,10,6,5,5,4,7,11,3,8,9,10,5,9,9,8,4,11,8,9,8,3,16,9,9,9,7,9,4,16,8,9,7,8,9,6,8,6,5,10,7,10,6,9,6,7,9,5,10,9,5,6,9,4,10,8,3,10,4,9,4,9,10,16,6,3,8,10,6,6,6,10,3,5,9,5,3,9,8,9,5,4,7,7,6,6,9,16,6,7,16,8,8,4,3,10,7,8,10,5,10,7,10,7,6,8,10,8,8,5,7,16,9,8,8,8,10,3,9,16,10,9,4,10,7,10,6,9,5,3,5,10,16,10,6,6,4,10,16,7,6,4,8,6,9,10,7,10,4,8,4,10,10,3,11,5,6,9,7,8~10,6,9,3,5,6,9,8,16,4,7,11,7,9,5,6,7,7,9,5,4,5,7,16,9,8,6,10,8,4,5,8,9,9,9,7,7,4,5,10,6,8,6,4,6,8,3,10,10,6,9,7,7,6,11,9,8,10,9,9,10,8,9,10,8,7,9,8,10,10,10,4,10,4,10,7,9,9,3,10,6,3,7,7,9,10,5,10,6,7,5,10,7,4,8,6,3,6,9,7,5,10,16,10,6,6,6,3,4,8,9,9,6,7,10,6,8,9,10,8,16,10,8,5,9,16,10,9,7,7,6,7,4,8,9,7,16,9,7,6,7,7,7,10,5,10,3,10,3,8,6,6,9,4,3,4,5,10,10,9,8,7,3,8,11,6,10,4,10,5,4,6,8,10,7,16,8,8,8,4,9,8,3,16,16,8,4,10,10,8,5,5,6,4,7,5,3,9,4,9,10,6,16,10,7,8,10,8,9,5,7,7,4,4,4,10,7,16,9,8,8,6,9,8,8,6,9,3,9,6,10,6,3,5,5,6,8,16,9,5,8,6,16,7,10,3,9,10,9,8~8,10,6,6,9,6,10,8,9,6,9,7,9,6,5,4,8,9,3,5,10,5,10,10,6,7,8,8,16,5,7,8,5,6,11,7,10,8,10,8,7,16,10,5,10,5,5,5,6,6,10,9,7,9,10,7,8,4,9,3,10,10,8,8,9,10,5,8,4,8,7,8,7,4,8,9,9,6,4,6,10,5,7,5,7,5,9,3,16,7,5,6,6,9,10,9,9,9,6,5,6,10,10,8,16,3,8,9,3,5,7,4,6,16,10,7,4,8,9,10,5,6,7,9,5,8,9,7,5,5,9,7,9,9,8,7,8,4,10,9,6,11,4,10,16,16,16,10,7,7,8,5,6,6,8,9,5,4,5,6,10,9,10,8,4,10,9,9,6,4,9,8,9,9,3,9,4,3,9,7,7,6,7,10,7,10,8,7,11,8,10,4,3,10,8,8,8,10,3,10,16,9,6,8,8,10,6,4,4,16,10,8,9,8,10,8,10,7,6,9,9,8,6,10,3,10,16,11,5,5,9,7,6,7,10,8,7,9,4,10,7,16,3,7,7,7,5,10,10,6,5,9,5,6,10,9,6,8,8,9,6,9,3,7,9,9,3,8,7,9,10,6,3,10,3,3,7,5,9,5,10,6,10,7,10,4,10,16,16,8,7,8,9,10,10,10,7,9,7,9,10,6,8,5,4,10,6,3,9,9,7,3,7,4,4,8,8,4,3,6,3,16,6,7,9,5,9,10,8,16,7,3,3,10,8,16,7,4,10,16,6,7,6,6,6,10,10,6,5,16,8,9,4,6,8,7,4,9,7,6,4,9,10,5,5,16,6,6,4,3,10,9,8,7,16,6,8,9,7,10,16,3,8,6,8,8,7,8,6,4,6,10,16&total_bet_min=10.00' ;
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
                    $allBet = $betline * $lines * 100;
                }
                $tumbAndFreeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'freespin' || $isTumb == true){                    
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
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
                    $roundstr = '57409' . substr($roundstr, 2, 9);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $leftFreeGames = 0;

                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []);
                    $Balance = $slotSettings->GetBalance();
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                }
                
                $wild = '2';
                $scatter = '1';
                $totalWin = 0;
                $this->strCheckSymbol = '';
                $this->wildMul = 0;
                $this->winLines = [];
                $bonusMpl = 1;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $str_s_mark = '';
                $str_trail = '';
                $rs_p = -1;
                $rs_c = 0;
                $rs_m = 0;
                $rs_t = 0;
                $rs_more = 0;
                $fsmore = 0;
                $fsmax = 0;
                $apwa = 0;
                $apv = 0;
                $str_apt = '';
                $currentReelSet = 0;
                $subScatterReel = null;
                if($slotEvent['slotEvent'] == 'freespin' || $isTumb == true){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_s_mark = $stack['s_mark'];
                    $str_trail = $stack['trail'];
                    $currentReelSet = $stack['reel_set'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $rs_more = $stack['rs_more'];
                    $fsmore = $stack['fsmore'];
                    $fsmax = $stack['fsmax'];
                    $strWinLine = $stack['win_line'];
                    $apwa = str_replace(',', '', $stack['apwa']);
                    $apv = $stack['apv'];
                    $str_apt = $stack['apt'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $pur, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $lastReel = explode(',', $stack[0]['reel']);
                    $str_s_mark = $stack[0]['s_mark'];
                    $str_trail = $stack[0]['trail'];
                    $currentReelSet = $stack[0]['reel_set'];
                    $rs_p = $stack[0]['rs_p'];
                    $rs_c = $stack[0]['rs_c'];
                    $rs_m = $stack[0]['rs_m'];
                    $rs_t = $stack[0]['rs_t'];
                    $rs_more = $stack[0]['rs_more'];
                    $fsmore = $stack[0]['fsmore'];
                    $fsmax = $stack[0]['fsmax'];
                    $strWinLine = $stack[0]['win_line'];
                    $apwa = str_replace(',', '', $stack[0]['apwa']);
                    $apv = $stack[0]['apv'];
                    $str_apt = $stack[0]['apt'];
                }

                $isNewTumb = false;
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
                if($apwa > 0){
                    $apwa = $apwa / $original_bet * $betline;
                    $totalWin = $totalWin + $apwa;
                }
                $scatterWin = 0;
                $scatterPoses = [];
                $scatterCount = 0;
                for($k = 0; $k < 30; $k++){
                    if($lastReel[$k] == $scatter){
                        $scatterCount++;
                        $scatterPoses[] = $k;
                    }
                }
                if($scatterCount >= 4){
                    $muls = [0,0,0,0,3,15,100];
                    $scatterWin = $muls[$scatterCount] * $betline * $lines;
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
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', $rs_p);
                if($rs_p >= 0){
                    $isState = false;
                    $spinType = 's';
                    $isNewTumb = true;
                }else{
                    if($slotEvent['slotEvent'] != 'freespin' && $scatterCount >= 4){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 15);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                    }else if($slotEvent['slotEvent'] == 'freespin' && $fsmore > 0){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                    }
                }
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 6; $i++){
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') + $totalWin);
                    $spinType = 's';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $isEnd = false;
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                    {
                        $isEnd = true;
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        if($isNewTumb == true){
                            $isState = false;
                            $strOtherResponse = $strOtherResponse . '&fsend_total=0';
                        }else{
                            $spinType = 'c';
                            $isState = true;
                            $strOtherResponse = $strOtherResponse . '&fsend_total=1';
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
                    if($isNewTumb == false && $fsmore > 0){
                        $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') + $totalWin);
                    if($scatterCount >= 4 && $isNewTumb == false){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1&psym=1~'. $scatterWin .'~' . implode(',', $scatterPoses);
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&purtr=1&puri=' . $pur;
                    }
                }
                if($rs_p >= 0){
                    $strOtherResponse = $strOtherResponse . '&rs_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') . '&rs_p=' . $rs_p . '&rs_c='. $rs_c .'&rs_m=' . $rs_m;
                    $isState = false;
                }
                else if($isTumb == true){
                    if($slotEvent['slotEvent'] != 'freespin' && $scatterCount < 4){
                        $spinType = 'c';
                        $isState = true;
                    }
                    $strOtherResponse = $strOtherResponse.'&rs_win='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbWin').'&rs_t='. $rs_t ; //.'&tmb_win='.($slotSettings->GetGameData($slotSettings->slotId . 'TumbWin'))
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', -1);
                }
                if($str_s_mark != ''){
                    $strOtherResponse = $strOtherResponse . '&s_mark=' . $str_s_mark;
                }
                if($str_trail != ''){
                    $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                }
                if($rs_more > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_more=' . $rs_more;
                }
                if($apv > 0){
                    $strOtherResponse = $strOtherResponse . '&apwa=' . $apwa . '&apv=' . $apv . '&apt=' . $str_apt;
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                }


                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse . '&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&reel_set='. $currentReelSet .'&na='.$spinType  .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=5&st=rect&c='.$betline.'&sw=6&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&w='.$totalWin.'&s=' . $strLastReel;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)  && $isNewTumb == false) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if($isNewTumb == false){
                    if( $slotEvent['slotEvent'] != 'freespin' && $scatterCount >= 4) 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"TumbWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"TumbleState":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState')  . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
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
        
        public function findZokbos($reels, $i, $j, $firstSymbol){
            $wild = '2';
            $bPathEnded = true;
            if($firstSymbol == $wild){
                return;
            }
            if($i < 6 && ($firstSymbol == $reels[$i + 1][$j] || $reels[$i + 1][$j] == $wild) && (strpos($this->strCheckSymbol, '<'.($i + 1) . '-' . $j.'>') == false || $reels[$i + 1][$j] == $wild)){
                $strCoord = '<'.($i + 1) . '-' . $j.'>';
                $bChecked = false;
                if($reels[$i + 1][$j] == $wild){
                    $strCoord = '<'.$firstSymbol.'-'.($i + 1) . '-' . $j.'>';
                    if(strpos($this->strCheckSymbol, $strCoord)){
                        $bChecked = true;
                    }
                }
                if(!$bChecked){
                    $this->repeatCount++;
                    $this->strWinLinePos = $this->strWinLinePos . '~'.($j * 7 + $i + 1);
                    $this->strCheckSymbol = $this->strCheckSymbol . ';' . $strCoord;
                    $this->findZokbos($reels, $i + 1, $j, $firstSymbol);
                    $bPathEnded = false;
                }
                
            }

            if($j < 6 && ($firstSymbol == $reels[$i][$j + 1] || $reels[$i][$j + 1] == $wild ) && (strpos($this->strCheckSymbol, '<'.$i . '-' . ($j + 1).'>') == false || $reels[$i][$j + 1] == $wild )){
                $strCoord = '<'.$i . '-' . ($j + 1).'>';
                $bChecked = false;
                if($reels[$i][$j + 1] == $wild ){
                    $strCoord = '<'.$firstSymbol.'-'.$i . '-' . ($j + 1).'>';
                    if(strpos($this->strCheckSymbol, $strCoord)){
                        $bChecked = true;
                    }
                }
                if(!$bChecked){
                    $this->repeatCount++;
                    $this->strWinLinePos = $this->strWinLinePos . '~'.(($j + 1) * 7 + $i);
                    $this->strCheckSymbol = $this->strCheckSymbol . ';' . $strCoord;
                    $this->findZokbos($reels, $i, $j + 1, $firstSymbol);
                    $bPathEnded = false;
                }
                
            }

            if($i > 0 && ($firstSymbol == $reels[$i - 1][$j] || $reels[$i - 1][$j] == $wild ) && (strpos($this->strCheckSymbol, '<'.($i - 1) . '-' . $j.'>') == false|| $reels[$i - 1][$j] == $wild )){
                $strCoord = '<'.($i - 1) . '-' . $j.'>';
                $bChecked = false;
                if($reels[$i - 1][$j] == $wild){
                    $strCoord = '<'.$firstSymbol.'-'.($i - 1) . '-' . $j.'>';
                    if(strpos($this->strCheckSymbol, $strCoord)){
                        $bChecked = true;
                    }
                }
                if(!$bChecked){
                    $this->repeatCount++;
                    $this->strWinLinePos = $this->strWinLinePos . '~'.($j * 7 + $i - 1);
                    $this->strCheckSymbol = $this->strCheckSymbol . ';' . $strCoord;
                    $this->findZokbos($reels, $i - 1, $j, $firstSymbol);
                    $bPathEnded = false;
                }
                
            }

            if($j > 0 && ($firstSymbol == $reels[$i][$j - 1] || $reels[$i][$j - 1] == $wild) && (strpos($this->strCheckSymbol, '<'.$i . '-' . ($j - 1).'>') == false || $reels[$i][$j - 1] == $wild)){
                $strCoord =  '<'.$i . '-' . ($j - 1).'>';
                $bChecked = false;
                if($reels[$i][$j - 1] == $wild){
                    $strCoord = '<'.$firstSymbol.'-'.$i . '-' . ($j - 1).'>';
                    if(strpos($this->strCheckSymbol, $strCoord)){
                        $bChecked = true;
                    }
                }
                if(!$bChecked){
                    $this->repeatCount++;
                    $this->strWinLinePos = $this->strWinLinePos . '~'.(($j - 1) * 7 + $i);
                    $this->strCheckSymbol = $this->strCheckSymbol . ';' .$strCoord;
                    $this->findZokbos($reels, $i, $j - 1, $firstSymbol);
                    $bPathEnded = false;
                }
            }
        }
    }
}

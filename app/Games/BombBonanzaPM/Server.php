<?php 
namespace VanguardLTE\Games\BombBonanzaPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 25);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [5,10,10,5,7,4,8,3,6,8,6,9,7,3,7,4,7,6,5,10,5,9,4,6,9]);
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
                    $bet = '40.00';
                }
                $spinType = 's';
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if(isset($stack)){
                    $str_initReel = $stack['initreel'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $str_s_mark = $stack['s_mark'];
                    if($stack['reel_set'] > 0){
                        $currentReelSet = $stack['reel_set'];
                    }
                    $fsmore = $stack['fsmore'];
                    $str_accm = $stack['accm'];
                    $str_acci = $stack['acci'];
                    $str_accv = $stack['accv'];
                    $str_stf = $stack['stf'];
                    $str_srf = $stack['srf'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $fsmax = $stack['fsmax'];
                    $str_trail = $stack['trail'];
                    $strWinLine = $stack['win_line'];

                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($str_mo != ''){
                        $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                    }
                    if($str_s_mark != ''){
                        $strOtherResponse = $strOtherResponse . '&s_mark=' . $str_s_mark;
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
                    if($str_srf != ''){
                        $strOtherResponse = $strOtherResponse . '&srf=' . $str_srf;
                    }
                    if($str_trail != ''){
                        $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                    }
                    if($rs_p >= 0){
                        $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p . '&rs_m=' . $rs_m . '&rs_c=' . $rs_c;
                    }
                    if($rs_t > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t;
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
                $response = 'def_s=5,10,10,5,7,4,8,3,6,8,6,9,7,3,7,4,7,6,5,10,5,9,4,6,9&balance='. $Balance .'&cfgs=5840&ver=3&index=1&balance_cash='. $Balance .'&def_sb=3,7,6,6,9&reel_set_size=8&def_sa=3,10,8,6,8&reel_set='. $currentReelSet .'&balance_bonus=0.00&na='. $spinType .'&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&rt=d&gameInfo={props:{max_rnd_sim:"0",max_rnd_hr:"100000000",max_rnd_win:"1665"}}&wl_i=tbm~1665&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime='. floor(microtime(true) * 1000) .'&sa=3,10,8,6,8&sb=3,7,6,6,9&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=40.00&sh=5&wilds=2~300,100,25,0,0~1,1,1,1,1&bonuses=0&st=rect&c='. $bet .'&sw=5&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;300,100,25,0,0;250,75,20,0,0;200,50,15,0,0;150,40,10,0,0;75,20,5,0,0;75,20,5,0,0;50,15,5,0,0;50,15,5,0,0&l=25&reel_set0=10,9,4,10,7,6,5,9,7,5,3,3,3,8,8,9,6,3,6,10,9,7,7,3,10,7,7,7,7,8,10,5,4,4,7,9,7,9,7,4,8,9,9,9,6,7,5,4,9,1,2,9,5,6,7,6,10,10,10,10,7,5,10,7,3,10,6,8,9,6,3,7,4,4,4,8,4,3,10,6,9,9,10,6,1,4,8,5,5,5,5,9,8,4,6,4,9,7,1,7,2,7,9,6,6,6,10,3,10,9,10,4,3,5,10,10,1,3,8,8,8,10,10,5,8,6,10,7,3,5,5,3,6,6~10,8,3,10,8,4,8,8,6,9,3,3,3,4,5,6,6,10,10,5,10,9,7,3,9,9,9,9,9,10,4,2,3,9,3,10,3,9,5,10,8,8,8,8,8,3,10,8,8,7,10,10,5,3,9,10,10,10,8,5,5,8,8,4,6,4,10,7,8,4,4,4,4,4,8,4,10,8,4,7,6,8,10,3,10,5,5,5,5,5,6,10,9,6,3,9,6,3,8,6,9,6,6,6,4,9,5,7,5,3,8,4,6,4,6,9,8~8,7,8,7,7,5,8,4,1,4,3,3,3,4,6,7,5,3,4,8,8,6,7,3,8,8,8,10,9,5,4,10,7,9,8,7,4,3,8,9,9,9,9,9,7,5,8,6,1,2,6,7,3,7,2,4,4,4,4,4,7,4,7,7,4,9,9,4,9,3,9,7,7,7,7,7,8,9,5,5,9,5,5,3,9,9,8,10,5~10,6,5,7,2,5,5,10,3,3,3,6,10,4,6,10,7,8,4,6,6,6,8,9,2,10,9,5,8,10,9,10,10,10,4,8,4,5,10,3,4,9,7,4,4,4,3,5,10,4,6,5,8,5,9,7,7,7,7,5,7,4,6,7,10,4,9,5,7,5,5,5,5,8,3,9,9,6,10,3,6,7,8,8,8,10,5,5,10,6,7,8,8,7,9,9,9,9,6,9,10,10,3,9,7,7,8,10,9~8,3,3,4,9,7,1,7,9,8,8,8,4,10,3,4,9,4,9,3,8,9,5,3,3,3,10,8,8,9,10,9,9,7,6,9,10,10,10,5,9,3,8,10,1,7,8,3,10,6,9,9,9,4,5,3,8,5,7,7,8,2,6,4,4,4,10,9,10,9,8,7,10,8,8,6,5,7,7,7,3,7,6,5,10,9,10,8,8,1,6,6,6,7,1,7,8,5,10,9,7,10,5,7,5,5,5,5,10,3,8,6,5,5,4,10,7,7,6,9&s='.$lastReelStr.'&accInit=[{id:0,mask:"cp;tp;lvl;sc;cl"}]&reel_set2=5,7,2,9,3,5,10,3,4,6,3,3,3,2,5,10,6,5,10,9,9,6,3,8,1,7,7,7,7,7,4,1,9,7,9,5,9,8,4,5,2,3,9,9,9,2,9,5,10,7,5,3,1,9,8,10,4,4,4,3,8,5,9,7,3,7,7,3,7,4,8,8,8,4,7,1,6,9,3,8,4,4,9,6,7,5,5,5,5,7,8,6,6,9,6,7,7,9,6,10,10,10,9,3,3,5,8,9,8,6,6,7,5,6,6,6,7,4,7,1,8,7,9,6,6,9,4,2,4~5,9,4,5,6,7,3,6,8,3,3,3,10,6,9,8,2,7,6,9,1,8,9,9,9,9,9,8,6,5,9,3,5,3,1,5,8,7,7,7,9,5,6,9,8,3,9,8,9,7,4,4,4,4,8,4,9,6,6,3,9,10,3,4,8,8,8,8,8,7,1,8,2,6,7,4,7,9,10,5,5,5,5,5,4,7,3,4,10,2,3,7,5,10,8~7,10,9,1,7,4,4,6,3,4,4,6,9,3,4,5,3,3,3,5,4,7,6,7,8,6,6,3,2,9,1,2,5,6,3,10,5,8,8,8,9,3,7,5,3,8,9,8,8,7,7,5,4,9,6,8,4,9,9,9,9,9,2,5,4,1,5,9,5,6,9,8,6,1,7,3,8,8,9,2,4,4,4,4,4,5,7,6,5,6,3,3,5,8,5,1,8,6,7,8,10,7,7,7,7,4,10,3,5,3,8,5,10,7,6,5,7,3,9,5,4,9,9~3,8,6,3,5,7,5,5,9,5,10,2,6,3,3,3,4,5,8,10,6,3,10,4,1,8,7,9,4,5,7,6,6,6,10,7,5,9,5,1,5,1,5,9,9,3,6,7,4,4,4,10,4,7,5,6,5,10,6,5,9,5,8,4,1,7,7,7,7,6,8,5,8,8,7,2,6,10,7,4,6,4,3,5,5,5,5,9,10,7,10,7,5,4,3,6,5,5,3,2,3,4,8,8,8,10,3,7,6,4,8,9,5,7,2,5,5,10,8,9,9,9,9,8,8,4,3,9,3,10,3,7,8,9,6,7,1,9~7,8,5,3,6,8,9,7,3,8,8,8,9,9,5,4,10,7,4,9,3,2,3,3,3,10,8,5,8,3,7,5,9,10,9,9,9,8,7,10,4,7,7,1,7,5,7,7,7,6,6,1,9,8,9,9,3,5,9,6,6,6,9,7,7,1,6,8,2,6,7,8,5,5,5,2,7,9,8,4,4,10,10,7,5,9&reel_set1=10,9,4,10,7,6,5,9,7,5,3,3,3,8,8,9,6,3,6,10,9,7,7,3,10,7,7,7,7,8,10,5,4,4,7,9,7,9,7,4,8,9,9,9,6,7,5,4,9,1,2,9,5,6,7,6,10,10,10,10,7,5,10,7,3,10,6,8,9,6,3,7,4,4,4,8,4,3,10,6,9,9,10,6,1,4,8,5,5,5,5,9,8,4,6,4,9,7,1,7,2,7,9,6,6,6,10,3,10,9,10,4,3,5,10,10,1,3,8,8,8,10,10,5,8,6,10,7,3,5,5,3,6,6~10,8,3,10,8,4,8,8,6,9,3,3,3,4,5,6,6,10,10,5,10,9,7,3,9,9,9,9,9,10,4,2,3,9,3,10,3,9,5,10,8,8,8,8,8,3,10,8,8,7,10,10,5,3,9,10,10,10,8,5,5,8,8,4,6,4,10,7,8,4,4,4,4,4,8,4,10,8,4,7,6,8,10,3,10,5,5,5,5,5,6,10,9,6,3,9,6,3,8,6,9,6,6,6,4,9,5,7,5,3,8,4,6,4,6,9,8~8,7,8,7,7,5,8,4,1,4,3,3,3,4,6,7,5,3,4,8,8,6,7,3,8,8,8,10,9,5,4,10,7,9,8,7,4,3,8,9,9,9,9,9,7,5,8,6,1,2,6,7,3,7,2,4,4,4,4,4,7,4,7,7,4,9,9,4,9,3,9,7,7,7,7,7,8,9,5,5,9,5,5,3,9,9,8,10,5~10,6,5,7,2,5,5,10,3,3,3,6,10,4,6,10,7,8,4,6,6,6,8,9,2,10,9,5,8,10,9,10,10,10,4,8,4,5,10,3,4,9,7,4,4,4,3,5,10,4,6,5,8,5,9,7,7,7,7,5,7,4,6,7,10,4,9,5,7,5,5,5,5,8,3,9,9,6,10,3,6,7,8,8,8,10,5,5,10,6,7,8,8,7,9,9,9,9,6,9,10,10,3,9,7,7,8,10,9~8,3,3,4,9,7,1,7,9,8,8,8,4,10,3,4,9,4,9,3,8,9,5,3,3,3,10,8,8,9,10,9,9,7,6,9,10,10,10,5,9,3,8,10,1,7,8,3,10,6,9,9,9,4,5,3,8,5,7,7,8,2,6,4,4,4,10,9,10,9,8,7,10,8,8,6,5,7,7,7,3,7,6,5,10,9,10,8,8,1,6,6,6,7,1,7,8,5,10,9,7,10,5,7,5,5,5,5,10,3,8,6,5,5,4,10,7,7,6,9&reel_set4=3,6,10,10,6,10,10,5,9,7,9,7,10,10,10,4,6,2,7,1,10,9,6,5,1,9,8,6,6,6,8,4,8,10,7,3,5,7,9,3,6,10,9,7,7,7,7,9,5,8,1,9,8,10,6,7,8,9,10,9,9,9,10,8,6,3,7,6,4,7,6,2,6,8,7,4,4,4,3,7,6,9,10,4,6,7,6,6,7,7,10,5,5,5,5,8,4,2,4,9,6,10,4,9,5,8,8,3,3,3,9,6,7,7,5,5,7,7,10,5,10,6,3,8,8,8,6,3,4,9,10,9,6,3,6,7,9,6,7,9~4,6,8,6,10,10,8,5,8,9,4,10,8,7,3,3,3,9,6,6,8,9,3,9,9,3,7,10,2,9,2,8,9,9,9,9,10,7,9,8,10,4,6,4,1,6,3,7,5,7,8,8,7,7,7,10,4,9,7,6,6,9,10,6,10,8,3,7,10,1,5,5,5,5,2,5,10,4,5,10,6,10,3,6,3,7,5,8,10,3,8,8,8,8,8,10,6,1,3,10,8,5,9,3,5,4,10,4,5,3,8,9~8,4,5,9,7,9,6,6,3,6,5,6,2,7,7,10,4,3,7,8,8,5,4,6,6,7,3,8,6,3,2,7,7,7,7,8,9,8,1,4,8,8,9,7,8,7,5,5,8,8,6,5,9,1,10,2,6,5,3,5,6,6,7,4,9,5,7,9,9,9,9,9,7,5,1,5,9,8,5,7,9,7,9,5,9,4,9,3,4,5,3,3,5,5,7,8,8,5,8,1,4,3,7,5,4,4,4,4,4,3,4,6,9,9,10,7,4,5,9,5,5,7,6,9,9,8,6,7,7,6,5,3,3,7,8,3,8,6,7,5,6,5,5,5,7,10,4,8,9,4,4,5,9,6,3,6,5,4,3,4,7,7,2,1,5,5,10,9,7,9,9,8,5,3,5,8,8,8,7,5,4,8,9,7,9,8,8,3,7,10,10,9,7,4,9,8,4,10,10,9,3,9,8,9,7,4,7,10,6,4,1~5,4,7,9,5,10,1,6,3,3,3,5,7,10,10,4,8,1,9,7,4,4,4,9,7,10,8,10,7,10,9,7,7,7,7,5,2,3,8,4,3,5,3,8,5,5,5,5,3,9,1,10,8,9,3,7,5,8,8,8,6,5,7,4,10,10,3,6,4,9,9,9,9,2,4,7,7,6,4,8,7,10,5,3~7,7,4,7,10,9,5,7,3,6,8,9,3,8,8,8,9,10,9,1,7,10,6,8,5,9,9,8,8,7,3,3,3,7,10,9,5,1,9,8,8,7,8,3,8,7,9,10,10,10,9,10,5,6,1,8,7,6,2,9,5,3,7,5,9,9,9,3,3,9,10,4,10,5,10,6,2,6,9,7,9,7,7,7,5,3,7,9,6,10,9,9,3,8,6,7,4,5,6,6,6,4,8,5,5,8,7,10,5,9,7,7,9,7,7,5,5,5,3,7,9,10,4,4,9,4,2,3,2,7,10,7,8,1&reel_set3=4,7,8,6,4,9,6,4,10,10,6,10,10,10,5,5,10,3,9,5,9,4,8,6,8,5,7,7,7,7,7,6,10,7,9,7,9,6,3,6,3,9,9,9,1,10,1,10,10,6,3,10,7,8,9,6,4,4,4,7,9,10,7,7,5,10,3,3,8,7,2,5,5,5,5,8,9,3,9,10,6,9,4,2,7,7,10,3,3,3,6,5,4,6,3,4,7,6,7,4,10,5,6,6,6,2,6,3,4,6,7,10,4,5,6,9,9,7~4,3,4,5,6,10,5,5,1,5,3,3,3,8,10,9,8,7,9,3,6,7,6,8,9,9,9,9,10,9,10,3,9,1,8,9,8,3,10,7,7,7,10,6,3,3,2,8,6,10,7,8,9,7,8,8,8,8,8,6,3,9,10,2,8,10,8,10,8,6,5,5,5,5,3,10,9,8,8,9,5,4,7,9,5,10,10,10,8,10,7,6,10,8,4,5,10,6,7,2,10~6,10,8,10,6,5,7,4,7,7,4,5,4,5,8,3,8,7,7,7,7,5,7,6,5,9,3,7,7,5,1,9,3,4,9,7,8,4,8,8,8,5,5,8,9,7,8,3,7,8,9,8,5,7,6,6,4,4,2,3,9,9,9,9,9,8,8,9,5,6,9,8,6,6,9,5,10,7,10,1,7,2,9,4,4,4,4,4,9,7,3,8,5,6,5,9,5,8,4,4,9,9,2,6,4,9,7,3~10,3,2,4,4,1,7,5,3,5,3,3,3,8,8,7,3,9,8,4,3,7,5,7,2,4,4,4,8,9,7,10,9,8,3,7,5,7,3,7,7,7,7,5,7,5,9,3,8,10,10,6,10,6,5,5,5,5,4,10,10,7,5,9,7,10,5,8,5,8,8,8,10,4,2,10,6,9,7,3,6,10,4,5,9,9,9,9,4,10,5,9,4,4,3,5,6,6,7,1~3,10,8,9,8,7,7,10,9,8,8,8,3,7,2,1,3,4,6,9,9,6,3,3,3,7,7,10,3,5,5,7,8,8,10,10,10,4,6,7,9,3,7,9,5,9,8,2,9,9,9,6,10,3,6,5,9,1,5,6,4,9,7,7,7,10,9,7,8,2,9,3,5,9,8,6,6,6,5,7,6,7,5,7,10,8,7,4,7,5,5,5,7,5,9,4,4,9,10,8,9,9,10,7&reel_set6=8,8,6,7,6,8,6,4,10,6,6,10,5,10,10,6,8,5,5,10,10,10,7,6,9,7,7,5,6,7,10,7,5,10,9,8,9,9,6,6,9,9,6,6,6,10,10,6,8,10,7,8,10,6,9,10,3,7,6,9,5,8,10,6,6,7,7,7,7,7,8,7,4,4,9,9,10,10,7,6,8,10,7,7,8,9,9,6,3,7,5,9,9,9,3,9,8,7,5,7,7,2,3,10,10,4,9,6,6,5,7,9,10,8,8,8,3,4,8,3,6,9,7,4,3,3,7,8,10,7,10,8,9,10,7,6,8,5,5,5,5,6,9,9,7,5,3,9,7,4,10,4,5,9,5,9,6,4,5,8,6,3,3,3,9,8,10,6,4,9,7,10,7,9,9,10,9,10,8,5,3,10,7,9,6,4,4,4,9,6,3,8,4,6,3,4,7,7,9,2,10,9,6,7,7,5,7,5,10,6~3,7,6,7,8,8,5,10,9,8,8,10,10,7,3,3,3,10,6,10,9,3,10,10,2,6,9,9,2,8,7,9,9,9,9,8,6,5,3,8,10,6,6,7,8,3,8,6,8,3,9,7,7,7,4,3,10,5,9,9,7,3,9,8,10,7,9,8,4,8,8,8,8,8,7,8,10,8,10,7,5,10,9,10,4,8,3,3,10,8,5,5,5,5,8,10,6,4,6,3,8,5,9,2,3,4,6,6,7,10,10,10,6,9,5,5,8,7,9,9,4,10,8,10,6,5,6,5~7,9,10,9,7,9,7,9,5,5,8,10,5,9,9,8,6,5,7,5,9,5,6,5,7,7,7,7,8,6,6,4,6,6,3,7,6,10,6,9,8,3,3,7,3,9,8,4,6,9,5,9,8,8,8,4,6,9,3,4,7,10,5,8,8,9,3,10,6,8,6,9,9,8,6,8,7,10,8,7,9,9,9,9,9,8,9,7,8,2,7,8,7,8,7,4,5,3,9,4,8,7,6,5,5,4,4,7,8,5,4,4,4,4,4,5,9,10,5,9,9,5,5,3,8,4,4,9,4,6,5,5,6,4,8,7,4,7,5,4,5,5,5,4,8,8,10,7,9,10,7,5,2,3,4,7,5,3,6,6,5,8,7,8,6,10,10,3,7,9~4,7,9,4,2,8,3,8,3,3,3,5,7,6,3,4,5,5,8,3,4,4,4,2,4,7,8,7,5,7,3,5,7,7,7,7,3,7,10,7,9,3,4,7,10,6,5,5,5,5,10,3,10,5,7,4,9,10,10,8,8,8,4,9,8,10,5,6,8,6,9,9,9,9,10,4,5,9,7,9,8,5,10,8,10~10,8,8,7,3,6,8,9,8,8,8,7,10,8,5,7,7,6,5,3,9,3,3,3,4,8,10,7,10,6,8,10,3,10,10,10,9,9,7,7,5,5,9,8,9,2,9,9,9,7,8,9,9,3,7,8,3,8,7,7,7,10,9,4,8,4,7,9,4,8,6,6,6,5,4,7,9,9,5,6,10,6,7,5,5,5,9,5,3,6,10,9,3,10,7,5,10&reel_set5=3,4,7,3,4,9,8,6,9,4,1,7,6,9,8,10,9,6,6,10,10,10,4,7,8,9,5,5,8,5,7,6,4,10,10,8,7,7,9,4,6,4,3,6,6,6,7,7,9,9,5,6,8,10,4,7,5,7,10,7,7,6,6,10,10,7,6,7,7,7,7,7,9,10,6,3,8,8,3,9,9,7,9,9,10,4,3,9,5,6,8,10,5,9,9,9,5,8,6,10,9,9,7,10,7,10,7,6,6,9,10,7,10,2,8,9,2,8,8,8,10,7,5,8,9,8,3,9,10,9,5,6,5,6,7,8,6,8,9,10,5,5,5,5,8,10,9,3,6,6,3,3,8,3,7,9,7,4,9,4,9,3,10,7,1,3,3,3,7,5,10,5,6,6,8,7,9,6,10,6,8,7,5,7,10,10,9,6,10,4,4,4,10,9,9,8,6,9,7,7,10,10,6,4,6,6,9,6,7,6,10,5,7,6,7~6,6,8,8,7,8,8,10,3,3,8,10,3,10,3,3,3,10,7,3,10,6,8,6,5,8,5,4,9,6,10,7,8,9,9,9,9,6,6,8,9,7,6,6,1,10,7,10,3,10,9,6,3,7,7,7,10,5,8,4,6,9,8,7,2,3,6,4,3,5,2,8,8,8,8,8,3,9,7,9,4,7,10,4,10,10,5,7,9,1,5,8,5,5,5,5,10,9,3,9,8,10,9,5,9,8,7,8,10,9,5,10,8~7,6,4,7,7,9,6,5,8,6,6,7,9,10,9,3,5,8,10,5,5,7,7,8,4,7,10,3,9,6,10,7,7,7,7,10,8,7,7,6,9,9,6,8,10,4,4,5,7,10,2,3,7,9,9,5,9,7,6,7,9,9,7,6,5,6,6,8,8,8,4,4,8,5,8,9,4,4,8,10,4,6,6,9,1,9,7,9,7,3,3,5,10,7,5,8,4,4,3,5,8,9,9,9,9,9,8,3,7,3,9,8,3,9,8,5,7,6,8,4,9,8,4,3,6,9,8,5,7,7,5,8,7,8,7,5,5,6,4,4,4,4,4,8,5,1,7,6,8,3,3,10,5,7,4,8,6,7,5,6,5,5,4,4,7,7,4,5,5,8,7,5,9,5,9,5,5,5,8,2,4,6,9,10,8,9,5,8,6,5,7,9,8,5,3,9,7,4,3,9,9,4,5,9,9,8,8,10,6,7~8,10,3,9,8,10,5,10,5,7,3,3,3,7,9,10,7,8,2,5,4,2,3,10,4,4,4,2,3,5,6,7,9,6,3,5,7,5,7,7,7,7,3,3,8,3,7,5,9,4,4,10,7,5,5,5,5,1,9,7,10,5,9,4,6,8,3,5,8,8,8,6,8,4,9,10,5,4,7,10,1,3,9,9,9,9,10,6,4,10,9,4,7,4,7,8,5,7~10,5,8,10,7,7,9,7,8,8,8,5,10,3,3,7,2,10,7,2,5,3,3,3,9,9,8,9,7,2,9,8,10,10,10,5,10,1,9,8,9,6,7,4,6,9,9,9,8,7,8,4,3,4,3,7,7,6,6,6,8,5,9,6,7,5,9,9,10,3,7,7,7,10,7,3,8,10,1,6,4,5,5,5,6,5,9,4,9,5,8,6,9,9,5&reel_set7=10,4,1,10,1,7,10,10,10,1,10,4,7,10,4,3,10,3,3,3,10,1,3,7,3,3,10,3,4~10,9,7,10,3,3,3,6,9,3,5,8,9,9,9,8,9,9,7,6,7,7,7,10,5,3,3,5,8,8,8,4,4,6,7,5,5,5,10,8,3,8,6,5~9,6,9,9,9,9,1,6,8,8,8,5,8,5,5,6,6,6,1,9,1,5,5,5,5,9,8,6,9,6~10,10,6,8,10,7,9,6,4,10,10,10,6,10,5,6,2,7,4,7,3,4,4,4,3,10,10,5,7,4,5,10,5,4,4~9,7,4,7,3,6,8,3,10,10,10,8,3,10,8,1,7,7,7,6,1,3,10,1,7,8,1,3,3';
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
                $lines = 25;      
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
                $slotEvent['slotLines'] = 25;
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
                $str_mo = '';
                $str_mo_t = '';
                $fsmore = 0;
                $str_accm = '';
                $str_acci = '';
                $str_accv = '';
                $str_stf = 0;
                $str_srf = 0;
                $str_s_mark = 0;
                $str_trail = 0;
                $fsmore = 0;
                $rs_p = -1;
                $rs_c = 0;
                $rs_m = 0;
                $rs_t = 0;
                $fsmax = 0;
                $scatterCount = 0;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_initReel = $stack['initreel'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $str_s_mark = $stack['s_mark'];
                    $currentReelSet = $stack['reel_set'];
                    $fsmore = $stack['fsmore'];
                    $str_accm = $stack['accm'];
                    $str_acci = $stack['acci'];
                    $str_accv = $stack['accv'];
                    $str_stf = $stack['stf'];
                    $str_srf = $stack['srf'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $fsmax = $stack['fsmax'];
                    $str_trail = $stack['trail'];
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
                    $str_mo = $stack[0]['mo'];
                    $str_mo_t = $stack[0]['mo_t'];
                    $str_s_mark = $stack[0]['s_mark'];
                    $currentReelSet = $stack[0]['reel_set'];
                    $fsmore = $stack[0]['fsmore'];
                    $str_accm = $stack[0]['accm'];
                    $str_acci = $stack[0]['acci'];
                    $str_accv = $stack[0]['accv'];
                    $str_stf = $stack[0]['stf'];
                    $str_srf = $stack[0]['srf'];
                    $rs_p = $stack[0]['rs_p'];
                    $rs_c = $stack[0]['rs_c'];
                    $rs_m = $stack[0]['rs_m'];
                    $rs_t = $stack[0]['rs_t'];
                    $fsmax = $stack[0]['fsmax'];
                    $str_trail = $stack[0]['trail'];
                    $strWinLine = $stack[0]['win_line'];
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
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $rs_p);
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
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                }
                if($str_s_mark != ''){
                    $strOtherResponse = $strOtherResponse . '&s_mark=' . $str_s_mark;
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
                if($str_srf != ''){
                    $strOtherResponse = $strOtherResponse . '&srf=' . $str_srf;
                }
                if($str_trail != ''){
                    $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                }
                if($rs_p >= 0){
                    $isState = false;
                    $spinType = 's';
                    $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p . '&rs_m=' . $rs_m . '&rs_c=' . $rs_c;
                }
                if($rs_t > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t;
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance . '&reel_set='. $currentReelSet.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=5&st=rect&c='.$betline.'&sw=5&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=50&w='.$totalWin.'&s=' . $strLastReel;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
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

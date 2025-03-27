<?php 
namespace VanguardLTE\Games\FirebirdSpiritConnectCollectPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [8,9,19,11,12,19,11,10,7,6,7,19,11,12,8,9,11,19,4,12,3,8,6,19,7,6,19,8,12,19]);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);
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
                $arr_g = null;
                $moneyWin = 0;
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if(isset($stack)){
                    $str_initReel = $stack['initreel'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $currentReelSet = $stack['reel_set'];
                    $fsmore = $stack['fsmore'];
                    $str_sts = $stack['sts'];
                    $str_sty = $stack['sty'];
                    $str_stf = $stack['stf'];
                    $str_trail = $stack['trail'];
                    $str_s_mark = $stack['s_mark'];
                    $pw = $stack['pw'];
                    $str_apv = $stack['apv'];
                    $str_apt = $stack['apt'];
                    $str_mo_wpos = $stack['mo_wpos'];
                    $mo_tv = $stack['mo_tv'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $strWinLine = $stack['wlc_v'];
                    
                    
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                    }
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($str_mo != ''){
                        $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                    }
                    if($pw >= 0){
                        if($pw > 0){
                            $pw = str_replace(',', '', $pw) / $original_bet * $bet;
                        }
                        $strOtherResponse = $strOtherResponse . '&pw=' . $pw;
                    }
                    
                    if($str_apv != ''){
                        $arr_apaw = [];
                        $arr_apv = explode(',', $str_apv);
                        for($k = 0; $k < count($arr_apv); $k++){
                            $apvWin = $arr_apv[$k] * $bet; // * $mul;
                            $arr_apaw[] = $apvWin;
                        }
                        $str_apaw = implode(',', $arr_apaw);
                        $strOtherResponse = $strOtherResponse . '&apwa='. $str_apaw .'&apt='. $str_apt .'&apv=' . $str_apv;
                    }
                    if($str_sts != ''){
                        $strOtherResponse = $strOtherResponse . '&sts=' . $str_sts;
                    }
                    if($str_sty != ''){
                        $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                    }
                    if($str_stf != ''){
                        $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                    }
                    if($str_s_mark != ''){
                        $strOtherResponse = $strOtherResponse . '&s_mark=' . $str_s_mark;
                    }
                    if($str_trail != ''){
                        $arr_trails = explode('~', $str_trail);
                        if(isset($arr_trails[1]) && $arr_trails[1] > 0){
                            $arr_trails[1] = str_replace(',', '', $arr_trails[1]) / $original_bet * $bet;
                        }
                        $strOtherResponse = $strOtherResponse . '&trail=' . implode('~', $arr_trails);
                    }
                    if($rs_p >= 0){
                        $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p . '&rs_c=' . $rs_c . '&rs_m=' . $rs_m;
                        if($rs_p > 0){
                            $strOtherResponse = $strOtherResponse . '&rs_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin');
                        }
                    }
                    if($rs_t > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t . '&rs_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin');
                    }
                    if($str_mo_wpos != ''){
                        $strOtherResponse = $strOtherResponse . '&mo_wpos=' . $str_mo_wpos;
                    }
                    if($mo_tv > 0){
                        $strOtherResponse = $strOtherResponse . '&mo_tv=' . $mo_tv . '&mo_c=1&mo_tw=' . ($mo_tv * $bet);
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
                $response = 'def_s=8,9,19,11,12,19,11,10,7,6,7,19,11,12,8,9,11,19,4,12,3,8,6,19,7,6,19,8,12,19&balance='. $Balance .'&cfgs=6530&ver=3&mo_s=18;19;15&index=1&balance_cash='. $Balance .'&mo_v=1250,5000,100000;50,75,100,150,200,250,300,400,500,750,1000,1250,1500,2000,2500,3000,4000,5000,7500,10000,20000;5,8,10,15,20,25&def_sb=12,9,9,6,12,19&reel_set_size=2&def_sa=8,6,6,10,11,19&reel_set='. $currentReelSet .'&mo_jp=1250;5000;100000&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0,0~0,0,0,0,0,0~1,1,1,1,1,1&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"11210762",max_rnd_win:"4250"}}&wl_i=tbm~4250&mo_jp_mask=jp1;jp2;jp3&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime='. floor(microtime(true) * 1000) .'&sa=8,6,6,10,11,19&sb=12,9,9,6,12,19&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=40.00&purInit_e=1&sh=5&wilds=2~0,0,0,0,0,0~1,1,1,1,1,1&bonuses=0&st=rect&c='. $bet .'&sw=6&sver=5&counter=2&paytable=0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,50,25,10,0,0;0,20,15,8,0,0;0,15,10,5,0,0;0,10,8,5,0,0;0,8,5,3,0,0;0,8,5,3,0,0;0,5,3,2,0,0;0,5,3,2,0,0;0,5,3,2,0,0;0,5,3,2,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0&l=25&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=5,5,5,5,5,9,7,3,10,4,4,4,4,4,3,10,11,12,8,8,8,8,8,7,12,7,7,7,6,4,12,12,12,12,12,1,3,10,8,8,8,5,12,11,11,11,11,11,8,4,8,4,8,3,3,3,3,3,5,7,11,12,10,10,10,10,10,11,7,8,7,7,7,7,7,6,8,9,9,9,9,9,11,8,7,8,1,10,7,6,6,6,6,6,11,12,10,5,5,5,6,12,4,4,4~4,5,9,1,10,6,7,3,12,12,12,12,12,2,2,2,2,2,6,5,1,1,1,1,1,12,9,7,11,11,11,11,11,12,11,6,6,6,6,6,8,6,7,6,7,6,9,3,9,10,10,10,10,10,6,2,9,9,9,9,9,5,7,6,12,11,5,6,3,3,3,3,3,4,7,6,8,2,8,4,4,4,4,4,12,10,6,2,10,5,8,8,8,8,8,1,8,9,6,2,5,7,11,6,3,10,7,7,7,7,7,6,11,2,5,5,5,5,5,6,2,6,12,6,3,6,6,6,6,6,8,2,6,7,4,5,8,7,6,2,6,9,6,9,6,3,6,6,2,5,6,7,6,12,7,5,7,8,5,6,5,6,7,2,7,6,9,1,6,10,7,6,8,6,2,11,8,7,2,6,12,6~12,12,12,12,12,4,2,2,2,2,2,5,7,10,10,10,10,10,12,12,1,1,1,1,1,9,5,11,11,11,11,11,3,2,12,8,11,9,6,8,8,8,8,8,5,7,10,12,3,9,1,9,9,9,9,9,12,10,4,9,12,1,4,10,3,3,3,3,3,1,9,4,5,8,8,8,4,6,4,2,9,10,3,5,4,4,4,4,4,9,3,7,7,7,7,7,1,2,6,6,6,6,6,5,2,1,10,12,10,5,5,5,5,5,3,9,2,1,10,4,9,5,12,8,12,1,2,8,6,10,3,9,1,9,1,2,1,4,1,10,9,1,3,1,8,2,1,2,10,6,2,12,4,9,1,4,2,1,9,1,9,12,5,9~1,1,1,1,1,9,8,12,12,12,12,12,3,2,2,2,2,2,10,12,11,3,3,3,3,3,12,11,10,6,6,6,6,6,3,10,12,11,5,7,4,4,4,4,4,5,6,2,7,5,5,5,5,5,9,4,10,10,10,10,10,11,3,8,9,6,5,10,11,11,11,11,11,8,6,4,9,9,9,9,9,2,5,8,8,8,8,8,2,4,12,7,7,7,7,7,10,8,12,4,7,6,4,6,8,2,8,6,12,4,2,10,11,9,12,4,12,8,4,6,2,12,10,4,7,4,12~6,6,6,6,6,11,12,12,12,12,12,6,8,3,3,3,3,3,1,1,1,1,1,11,11,11,11,11,5,12,10,1,7,10,10,10,10,10,4,9,11,1,7,12,9,9,9,9,9,10,1,8,4,4,4,4,4,7,10,8,1,10,5,5,5,5,5,3,1,10,5,3,8,8,8,8,8,7,1,8,10,7,7,7,7,7,4,8,4,9,10,6,3,5,1,5,6,6,6,4,1,11,10,1,12,10,3,12,8,1,10,8,12,1,9,4,3,4,7,10,4,8,10,3,7,5,3,11,1,7,10,4,8,1,4,11,3,10,1,5,11~19,18,19,19,17,19,19,15,19,19,15,19,19,15,17,19,15&s='.$lastReelStr.'&reel_set1=7,7,7,5,12,12,12,12,12,11,10,1,1,1,1,6,3,3,3,3,3,7,1,10,10,10,8,9,4,5,3,12,11,11,11,11,11,5,5,5,4,4,4,4,4,6,6,6,6,6,3,3,3,12,8,4,10,1,10,12,10,4,10,6,12,4,3,1,6,4,11,12,10,1,10,11,10,7,7,7,7,7,9,10,3,1,10,8,9,3,6,7,9,10,6,5,5,5,5,5,12,3,10,1,12,9,5,4,10,6,4,10,1,4,6,12,10,12,6,8,8,8,8,8,12,1,9,10,4,12,4,10,3,6,8,6,1,7,3,10,1,11,12,4,6,8,12,12,12,3,10,12,3,12,1,4,6,12,8,4,12,4,3,12,9,9,9,9,9,11,12,4,11,4,10,3,11,11,1,6,12,3,6,3,8,12,6,4,10,9,4,8,12,6,11,12,10,12,4,10,12,4,1,3,10,4,12,8,4,6,4,10,4,1,12,11,3,4,1,3,12,10,4,11,10,9,1,4,11,12,10,10,10,10,10,3,8,9,3,6,1,4,6,3,4,1,10,12,4,10,6,4,6,10,6,3,10,9,10,4,11,12,10,11,12,3,12,10,12,3,4,10,6,3,4,7,12,11,12,1,12,10,11,12,3,12,6,10,4,11,6,12,4,6,4,5,4,9,7,1,6,8,12,4,10,3,12,3,11,4,12,6,12,9,4,10,12,3,6,10,6,8,3,6,11,12,10,1,10,6,11,6,3,4,10,4,12,5,9,12,3,6,10,4,10,3,4,3,12,11,10,4,9,4,10,6,8,3,4,3,4,8,10,4,11,12,6,4,11,4,12,4,5,6,4,12,3,11,12,9,8,6,7,1,12,1,10,9,4,12,1,10,4,10,3,12,10,11,10,12,1,12,4,12,10,4,12,1,4,10,4,3,5,12,11,7,4,8,6,12,10,6,11,9,3,12,5,6,12,11,8,12,10,11,10,4,12,10,4,8,11,4,10,12,4,3,12,10,12,10,9,10,8,3,12,10,11,12,4,11,12,1,10,1,12,3,12,12,8,11,3,9,3,4,10,9,1,12,10,11,4,1,10,11,10,4,10,6,4,12,10,8,1,9,3,9~7,7,7,5,12,12,12,12,12,11,10,1,1,1,1,6,3,3,3,3,3,7,1,10,10,10,8,9,4,5,3,12,11,11,11,11,11,5,5,5,4,4,4,4,4,6,6,6,6,6,3,3,3,12,8,4,10,1,10,12,10,4,10,6,12,4,3,1,6,4,11,12,10,1,10,11,10,7,7,7,7,7,9,10,3,1,10,8,9,3,6,7,9,10,6,5,5,5,5,5,12,3,10,1,12,9,5,4,10,6,4,10,1,4,6,12,10,12,6,8,8,8,8,8,12,1,9,10,4,12,4,10,3,6,8,6,1,7,3,10,1,11,12,4,6,8,12,12,12,3,10,12,3,12,1,4,6,12,8,4,12,4,3,12,9,9,9,9,9,11,12,4,11,4,10,3,11,11,1,6,12,3,6,3,8,12,6,4,10,9,4,8,12,6,11,12,10,12,4,10,12,4,1,3,10,4,12,8,4,6,4,10,4,1,12,11,3,4,1,3,12,10,4,11,10,9,1,4,11,12,10,10,10,10,10,3,8,9,3,6,1,4,6,3,4,1,10,12,4,10,6,4,6,10,6,3,10,9,10,4,11,12,10,11,12,3,12,10,12,3,4,10,6,3,4,7,12,11,12,1,12,10,11,12,3,12,6,10,4,11,6,12,4,6,4,5,4,9,7,1,6,8,12,4,10,3,12,3,11,4,12,6,12,9,4,10,12,3,6,10,6,8,3,6,11,12,10,1,10,6,11,6,3,4,10,4,12,5,9,12,3,6,10,4,10,3,4,3,12,11,10,4,9,4,10,6,8,3,4,3,4,8,10,4,11,12,6,4,11,4,12,4,5,6,4,12,3,11,12,9,8,6,7,1,12,1,10,9,4,12,1,10,4,10,3,12,10,11,10,12,1,12,4,12,10,4,12,1,4,10,4,3,5,12,11,7,4,8,6,12,10,6,11,9,3,12,5,6,12,11,8,12,10,11,10,4,12,10,4,8,11,4,10,12,4,3,12,10,12,10,9,10,8,3,12,10,11,12,4,11,12,1,10,1,12,3,12,12,8,11,3,9,3,4,10,9,1,12,10,11,4,1,10,11,10,4,10,6,4,12,10,8,1,9,3,9~8,8,8,2,3,3,3,9,10,7,7,7,11,5,5,5,6,11,10,10,10,5,3,12,1,2,2,2,7,8,9,9,9,4,4,4,5,5,5,1,1,1,1,1,12,12,12,5,8,8,8,3,11,4,11,11,11,3,9,9,9,1,5,6,6,6,7,9,8,6,4,9~4,4,4,4,4,11,9,9,9,9,9,10,3,6,5,6,6,6,6,6,8,1,2,8,8,8,8,8,12,9,1,1,1,1,1,10,11,11,11,11,11,7,3,3,3,3,3,4,10,10,10,10,10,5,5,5,5,5,2,2,2,2,2,7,7,7,7,7,12,12,12,12,12,3,9,9,9,10,1,10,1,6,2,10,7,10,7,2,12,8,6,11,6,1,9,5,11,12,2,1,7,9,10,12,3,10,3,5,8,2,6,2,5,12,2,10,3,7,11,8,12,9,11,1,6,3,7,9,1,12,1,10,12,9,7,11,12,1,11,10,7,10,8,1,9,2,10,2,7,12,7,2,11,3,9,7,1,11,10,7,11,2,5,1,11,4,12,3,2,12,5,10,2,6,10,1,6,1,11,2,10,5,11,10,3,2,6,10,2,3,2,10,5,7,3,12,3,10,1,2,8,1,12,5,1,7,1,10,12,1,3,1,8,12,11,2,4,3,7,11,12,10,9,7,8,10,11,10,12,8,12,11,5,2,11,9,5,12,1,10,7,9,2,1,5,1,8,7,12,7,6,5,7,2,1,2,8,6,1,3,12,5,3,4,11,6,10,2,9,5,6,10,8,11,1,6,12,3,10,5,9,2,1,6~4,4,4,4,4,11,9,9,9,9,9,10,3,6,5,6,6,6,6,6,8,1,2,8,8,8,8,8,12,9,1,1,1,1,1,10,11,11,11,11,11,7,3,3,3,3,3,4,10,10,10,10,10,5,5,5,5,5,2,2,2,2,2,7,7,7,7,7,12,12,12,12,12,3,9,9,9,10,1,10,1,6,2,10,7,10,7,2,12,8,6,11,6,1,9,5,11,12,2,1,7,9,10,12,3,10,3,5,8,2,6,2,5,12,2,10,3,7,11,8,12,9,11,1,6,3,7,9,1,12,1,10,12,9,7,11,12,1,11,10,7,10,8,1,9,2,10,2,7,12,7,2,11,3,9,7,1,11,10,7,11,2,5,1,11,4,12,3,2,12,5,10,2,6,10,1,6,1,11,2,10,5,11,10,3,2,6,10,2,3,2,10,5,7,3,12,3,10,1,2,8,1,12,5,1,7,1,10,12,1,3,1,8,12,11,2,4,3,7,11,12,10,9,7,8,10,11,10,12,8,12,11,5,2,11,9,5,12,1,10,7,9,2,1,5,1,8,7,12,7,6,5,7,2,1,2,8,6,1,3,12,5,3,4,11,6,10,2,9,5,6,10,8,11,1,6,12,3,10,5,9,2,1,6~19,18,19,19,17,19,19,15,19,19,15,19,19,15,17,19,15&purInit=[{bet:2500,type:"fs"}]&total_bet_min=8.00' ;
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
                $pur = -1;
                if(isset($slotEvent['pur'])){
                    $pur = $slotEvent['pur'];
                }
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
                if($pur >= 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $allBet * 100;
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur);
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
                $str_mo = '';
                $str_mo_t = '';
                $reels = [];
                $fsmore = 0;
                $str_sts = '';
                $str_sty = '';
                $str_stf = '';
                $str_trail = '';
                $str_s_mark = '';
                $pw = -1;
                $str_apv = '';
                $str_apt = '';
                $str_mo_wpos = '';
                $mo_tv = 0;
                $fsmax = 0;
                $rs_p = -1;
                $rs_c = 0;
                $rs_m = 0;
                $rs_t = 0;
                $scatterCount = 0;
                $scatterWin = 0;
                $scatterPoses = [];
                if($slotEvent['slotEvent'] == 'freespin' || $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') >= 0){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $lastReel = explode(',', $stack['reel']);
                    $str_initReel = $stack['initreel'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $currentReelSet = $stack['reel_set'];
                    $fsmore = $stack['fsmore'];
                    $str_sts = $stack['sts'];
                    $str_sty = $stack['sty'];
                    $str_stf = $stack['stf'];
                    $str_trail = $stack['trail'];
                    $str_s_mark = $stack['s_mark'];
                    $pw = $stack['pw'];
                    $str_apv = $stack['apv'];
                    $str_apt = $stack['apt'];
                    $str_mo_wpos = $stack['mo_wpos'];
                    $mo_tv = $stack['mo_tv'];
                    $fsmax = $stack['fsmax'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $strWinLine = $stack['wlc_v'];
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
                    $str_mo = $stack[0]['mo'];
                    $str_mo_t = $stack[0]['mo_t'];
                    $currentReelSet = $stack[0]['reel_set'];
                    $fsmore = $stack[0]['fsmore'];
                    $str_sts = $stack[0]['sts'];
                    $str_sty = $stack[0]['sty'];
                    $str_stf = $stack[0]['stf'];
                    $str_trail = $stack[0]['trail'];
                    $str_s_mark = $stack[0]['s_mark'];
                    $pw = $stack[0]['pw'];
                    $str_apv = $stack[0]['apv'];
                    $str_apt = $stack[0]['apt'];
                    $str_mo_wpos = $stack[0]['mo_wpos'];
                    $mo_tv = $stack[0]['mo_tv'];
                    $fsmax = $stack[0]['fsmax'];
                    $rs_p = $stack[0]['rs_p'];
                    $rs_c = $stack[0]['rs_c'];
                    $rs_m = $stack[0]['rs_m'];
                    $rs_t = $stack[0]['rs_t'];
                    $strWinLine = $stack[0]['wlc_v'];
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
                $str_apaw = '';
                if($str_apv != ''){
                    $arr_apaw = [];
                    $arr_apv = explode(',', $str_apv);
                    for($k = 0; $k < count($arr_apv); $k++){
                        $apvWin = $arr_apv[$k] * $betline; // * $mul;
                        $arr_apaw[] = $apvWin;
                        $totalWin = $totalWin + $apvWin;
                    }
                    $str_apaw = implode(',', $arr_apaw);
                }
                $moneyWin = 0;
                if($mo_tv > 0 && $rs_p < 0){
                    $moneyWin = $mo_tv * $betline;
                    $totalWin = $totalWin + $moneyWin;
                }

                $spinType = 's';
                if( $totalWin > 0) 
                {
                    $spinType = 'c';
                    $slotSettings->SetBalance($totalWin);
                    if($winType == 'bonus'){
                        $slotSettings->SetBank('bonus', -1 * $totalWin);
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
                }else if($fsmore > 0){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                }
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 5; $i++){
                    $reelA[$i] = mt_rand(5, 10);
                    $reelB[$i] = mt_rand(5, 10);
                }
                $reelA[5] = 19;
                $reelB[5] = 19;
                $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
                $strLastReel = implode(',', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $rs_p);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') + $totalWin);
                $strOtherResponse = '';
                if($rs_p >= 0){
                    $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p . '&rs_c=' . $rs_c . '&rs_m=' . $rs_m;
                    if($rs_p == 0){
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                    }else{
                        $strOtherResponse = $strOtherResponse . '&rs_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin');
                    }
                    $spinType = 's';
                    $isState = false;
                }
                if($rs_t > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t . '&rs_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin');                    
                    if($slotEvent['slotEvent'] != 'freespin' && $fsmax <= 0){
                        $spinType = 'c';
                        $slotEvent['slotEvent'] = 'doRespin';
                    }
                }
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $spinType = 's';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $isEnd = false;
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                    {
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        if($rs_p < 1){
                            $spinType = 'c';
                            $strOtherResponse = $strOtherResponse . '&fsend_total=1';
                        }else{
                            $isState = false;   
                            $strOtherResponse = $strOtherResponse . '&fsend_total=0';
                        }
                        
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
                    if($fsmax > 0){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1&psym=1~'. $scatterWin .'~' . implode(',', $scatterPoses);
                    }
                }
                if($pur > 0){
                    $strOtherResponse = $strOtherResponse . '&purtr=1';
                }
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                }
                if($pw >= 0){
                    if($pw > 0){
                        $pw = str_replace(',', '', $pw) / $original_bet * $betline;
                    }
                    $strOtherResponse = $strOtherResponse . '&pw=' . $pw;
                }
                if($str_apv != ''){
                    $strOtherResponse = $strOtherResponse . '&apwa='. $str_apaw .'&apt='. $str_apt .'&apv=' . $str_apv;
                }
                if($str_sts != ''){
                    $strOtherResponse = $strOtherResponse . '&sts=' . $str_sts;
                }
                if($str_sty != ''){
                    $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                }
                if($str_s_mark != ''){
                    $strOtherResponse = $strOtherResponse . '&s_mark=' . $str_s_mark;
                }
                if($str_stf != ''){
                    $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                }
                if($str_trail != ''){
                    $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                }
                if($str_mo_wpos != ''){
                    $strOtherResponse = $strOtherResponse . '&mo_wpos=' . $str_mo_wpos;
                }
                if($mo_tv > 0){
                    $strOtherResponse = $strOtherResponse . '&mo_tv=' . $mo_tv . '&mo_c=1&mo_tw=' . $moneyWin;
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&wlc_v=' . $strWinLine;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse  .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance . '&reel_set='. $currentReelSet.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=5&st=rect&c='.$betline.'&sw=6&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=25&w='.$totalWin.'&s=' . $strLastReel;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) && $rs_p < 0) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if( $slotEvent['slotEvent'] != 'freespin' && ($fsmax > 0 || $rs_p == 0)) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin'));
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"CurrentRespin":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if(($slotEvent['slotEvent'] == 'freespin' || $rs_t > 0) && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
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
    }
}

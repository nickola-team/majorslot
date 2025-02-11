<?php 
namespace VanguardLTE\Games\PowerofMerlinMegawaysPM
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);
                    if (isset($lastEvent->serverResponse->ReplayGameLogs)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', json_decode(json_encode($lastEvent->serverResponse->ReplayGameLogs), true)); //ReplayLog
                    }
                    if (isset($lastEvent->serverResponse->G)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'G', json_decode(json_encode($lastEvent->serverResponse->G), true)); //ReplayLog
                    }
                    if (isset($lastEvent->serverResponse->TumbWin)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $lastEvent->serverResponse->TumbWin); //ReplayLog
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
                        if(isset($arr_g['base'])){
                            $arr_g['base']['def_s'] = '5,11,8,12,6,10,4,9,9,3,7,9,6,11,12,11,6,11,4,8,7,3,3,9,6,11,12,6,5,12,7,10,7,4,4,8,5,9,11,5,6,12';
                            $arr_g['base']['def_sa'] = '4,10,7,8,4,12';
                            $arr_g['base']['def_sb'] = '4,10,7,8,4,12';
                            $arr_g['base']['reel_set'] = '0';
                        }
                        if(isset($arr_g['main'])){
                            $arr_g['main']['def_s'] = '14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14';
                            $arr_g['main']['def_sa'] = '4,11,7,9,6,12';
                            $arr_g['main']['def_sb'] = '5,11,8,12,7,11';
                        }
                        if(isset($arr_g['train'])){
                            $arr_g['train']['def_s'] = '13,12,10,13';
                            $arr_g['train']['def_sa'] = '8';
                            $arr_g['train']['def_sb'] = '9';
                            $arr_g['train']['reel_set'] = '12';
                        }
                        $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($arr_g));                    
                    }else{
                        $strOtherResponse = $strOtherResponse . '&g={base:{def_s:"5,11,8,12,6,10,4,9,9,3,7,9,6,11,12,11,6,11,4,8,7,3,3,9,6,11,12,6,5,12,7,10,7,4,4,8,5,9,11,5,6,12",def_sa:"4,10,7,8,4,12",def_sb:"5,11,11,12,7,11",reel_set:"0",s:"5,11,8,12,6,10,4,9,9,3,7,9,6,11,12,11,6,11,4,8,7,3,3,9,6,11,12,6,5,12,7,10,7,4,4,8,5,9,11,5,6,12",sa:"4,10,7,8,4,12",sb:"5,11,11,12,7,11",sh:"7",st:"rect",sw:"6"},main:{def_s:"14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14",def_sa:"4,11,7,9,6,12",def_sb:"5,11,8,12,7,11",s:"14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14",sa:"4,11,7,9,6,12",sb:"5,11,8,12,7,11",sh:"8",st:"rect",sw:"6"},train:{def_s:"13,12,10,13",def_sa:"8",def_sb:"9",reel_set:"12",s:"13,12,10,13",sa:"8",sb:"9",sh:"4",st:"rect",sw:"1"}}';
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
                    $strOtherResponse = $strOtherResponse . '&g={base:{def_s:"5,11,8,12,6,10,4,9,9,3,7,9,6,11,12,11,6,11,4,8,7,3,3,9,6,11,12,6,5,12,7,10,7,4,4,8,5,9,11,5,6,12",def_sa:"4,10,7,8,4,12",def_sb:"5,11,11,12,7,11",reel_set:"0",s:"5,11,8,12,6,10,4,9,9,3,7,9,6,11,12,11,6,11,4,8,7,3,3,9,6,11,12,6,5,12,7,10,7,4,4,8,5,9,11,5,6,12",sa:"4,10,7,8,4,12",sb:"5,11,11,12,7,11",sh:"7",st:"rect",sw:"6"},main:{def_s:"14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14",def_sa:"4,11,7,9,6,12",def_sb:"5,11,8,12,7,11",s:"14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14",sa:"4,11,7,9,6,12",sb:"5,11,8,12,7,11",sh:"8",st:"rect",sw:"6"},train:{def_s:"13,12,10,13",def_sa:"8",def_sb:"9",reel_set:"12",s:"13,12,10,13",sa:"8",sb:"9",sh:"4",st:"rect",sw:"1"}}';
                }
                    
                $Balance = $slotSettings->GetBalance();
                $response = 'balance='. $Balance .'&cfgs=8815&ver=3&index=1&balance_cash='. $Balance .'&reel_set_size=24&balance_bonus=0.00&na='. $spinType .'&scatters=1~0,0,0,0,0,0~0,0,0,0,0,0~1,1,1,1,1,1&rt=d&gameInfo={props:{gamble_lvl_4:"76.58",gamble_lvl_5:"78.92",max_rnd_sim:"1",max_rnd_hr:"14285714",max_rnd_win:"40000",gamble_lvl_2:"69.37",gamble_lvl_3:"73.95",gamble_lvl_1:"63.10"}}&wl_i=tbm~40000&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&reel_set10=reel_set5&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=50.00&reel_set11=reel_set5&reel_set12=7,8,8,11,9,8,11,12,9,5,10,9,6,11,11,10,5,11,6,10,12,12,11,3,3,4,8,7,11,7,12,10,10,7,8,12,7,5,7,8,10,10,9,1,11,10,12,10,6,3,4,8,10,4,9,8,7,8,9,8,7,9,12,12,2,10,9,10,9,11,11,7,10,3,3,12,10,13,10,2,9,8,11,8,2,6,4,10,12,3,6,7,12,8,9,6,11,6,3,10,3,5,7,12,13,8,5,1,2,4,7,11,10,7,12,8,5,2,12,11,11,9,5,11,4,4,10,2,10,2,11,8,9,11,11,6,3,4,11,11,10,7,6,8&purInit_e=1&reel_set13=reel_set12&wilds=2~0,0,0,0,0,0~1,1,1,1,1,1&bonuses=0&c='.$bet.'&sver=5&reel_set18=reel_set17&reel_set19=reel_set17&counter=2&reel_set14=reel_set12&paytable=0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;400,200,100,20,10,0;100,50,40,10,0,0;50,30,20,10,0,0;40,15,10,5,0,0;30,12,8,4,0,0;25,10,8,4,0,0;20,10,5,3,0,0;18,10,5,3,0,0;16,8,4,2,0,0;12,8,4,2,0,0;0,0,0,0,0,0;0,0,0,0,0,0&l=20&reel_set15=12,7,10,10,12,9,10,10,8,13,12,9,7,4,12,10,11,3,8,4,8,4,10,10,9,7,11,13,11,5,9,7,13,9,11,13,4,2,4,10,5,12,2,7,6,11,10,3,9,8,13,3,11,9,11,11,10,8,7,12,3,6,7,8,5,3,5,11,7,6,7,8&reel_set16=reel_set15&reel_set17=5,7,5,3,3,9,9,3,11,11,5,3,3,3,3,9,7,5,11,11,7,5,7,3,5,7,11,5,5,5,5,9,3,9,9,11,5,9,11,3,7,3,9,7,7,7,7,5,11,9,9,5,11,9,9,3,11,7,9,9,9,9,7,9,9,11,5,5,11,7,5,11,7,11,11,11,11,9,5,11,3,9,7,9,7,3,3,5,3,7,5&total_bet_max='.$slotSettings->game->rezerv.'&reel_set21=reel_set17&reel_set22=reel_set17&reel_set0=8,12,12,8,8,3,12,3,12,5,9,5,5,9,5,3,9,12,4,5,8,11,5,4,5,10,8,8,11,11,11,12,8,10,12,7,9,9,5,5,9,7,3,12,7,6,1,10,7,12,7,5,8,3,5,8,9,5,3,10,12,9,9,9,7,10,8,5,5,12,9,3,8,9,6,8,5,11,9,5,8,12,8,10,8,3,7,12,10,8,10,12,4,10,12,12,12,11,12,10,3,4,12,1,12,9,8,10,8,7,12,11,12,6,10,5,6,10,10,4,12,4,12,3,8,9,6,8,8,8,6,3,10,9,9,3,8,8,7,12,10,7,10,12,3,9,7,10,9,12,10,11,5,6,12,4,11,8,5,10,10,10,8,3,8,3,5,12,12,10,10,9,8,12,8,6,8,11,12,8,9,8,6,9,9,12,5,5,1,9,5,9,7,7,7,11,9,9,8,12,12,8,8,12,10,5,11,9,8,3,9,8,7,12,11,12,10,9,7,9,3,11,6,9,5,6~3,7,7,12,6,1,8,7,11,10,11,8,11,10,7,10,7,6,4,6,11,7,10,8,8,8,11,9,7,7,3,6,12,10,9,7,3,8,11,6,3,9,12,8,11,10,11,4,8,8,11,11,11,3,12,7,5,3,10,10,7,7,10,7,11,12,7,7,3,9,3,11,10,8,9,4,7,8,7,7,7,7,3,11,6,7,4,11,11,5,11,3,11,10,4,12,9,8,8,3,12,7,12,6,11,12,10,10,10,7,12,7,4,1,12,8,7,12,10,7,8,7,6,11,11,5,10,6,10,10,7,11,8,12,12,12,12,7,9,3,3,7,10,7,7,6,4,7,11,7,3,9,7,12,4,11,7,8,10,4,6,9,9,9,7,12,10,4,8,8,11,7,9,7,4,9,5,11,4,7,10,7,11,7,6,10,12,7,9,12~7,9,11,6,11,6,12,12,6,8,12,8,9,12,11,9,12,12,5,8,11,12,9,12,9,4,9,9,9,6,6,7,10,6,7,6,9,9,10,4,6,8,11,6,6,11,6,9,8,4,9,4,5,12,8,8,11,6,6,6,4,12,9,12,10,5,8,5,6,4,11,10,9,6,8,7,8,6,8,11,8,4,3,10,6,11,12,11,11,11,8,12,8,6,6,12,9,4,12,7,8,10,4,8,3,10,4,11,8,12,10,12,4,7,12,6,12,11,10,10,10,8,6,9,9,6,12,4,6,11,8,6,7,8,7,11,8,8,11,9,9,11,12,4,8,9,6,11,8,8,8,5,6,9,7,5,12,11,12,5,8,10,7,12,7,4,12,9,1,4,8,12,6,11,9,8,5,8,12,12,12,7,6,1,6,6,12,6,9,12,11,8,4,6,5,9,9,11,4,12,9,5,9,9,6,5,11,4,10~7,12,10,6,5,7,7,12,9,11,12,6,9,9,9,9,9,6,9,1,4,12,8,6,5,12,9,9,4,4,4,12,10,9,10,5,6,9,7,5,12,10,12,6,9,10,10,10,10,6,5,12,8,6,11,10,3,12,10,10,4,11,6,6,6,6,7,6,6,9,6,9,11,9,10,7,9,12,4,12,12,12,10,9,12,12,6,6,11,9,7,12,9,6,11,8,5,5,5,8,4,12,6,3,7,8,10,8,12,9,12,1,3,3,3,5,10,7,12,3,6,10,3,3,4,10,9,12,7,7,7,7,7,11,11,9,10,12,10,11,10,5,8,9,12,12,4,12~3,6,6,8,7,5,7,6,12,5,11,4,6,8,5,6,6,6,6,6,9,8,10,4,4,8,6,5,5,6,11,12,11,10,6,6,10,8,8,8,8,7,5,5,6,11,6,6,10,11,6,11,10,11,6,3,8,4,4,4,9,10,12,10,8,8,12,7,8,7,6,8,11,11,6,5,4,3,3,3,10,7,6,7,6,6,8,12,12,10,11,10,6,1,4,8,10,10,10,10,8,11,8,11,6,9,12,12,6,12,8,11,11,5,1,6,6,11,11,11,12,8,5,4,9,6,11,6,8,4,8,11,4,8,9,6,5,5,5,11,8,8,9,9,11,11,6,10,5,12,11,10,6,10,7,9,7,7,7,8,10,9,10,12,11,6,4,8,11,12,6,5,8,6,6,11,12~10,6,9,5,10,4,11,5,10,11,4,7,9,9,9,9,9,10,12,6,8,7,4,10,11,12,4,8,12,11,4,4,4,9,11,9,7,10,11,11,12,8,12,7,5,8,10,5,5,5,9,3,9,11,12,7,7,4,9,11,12,5,11,8,8,8,11,8,4,11,12,9,12,7,5,9,12,7,10,12,12,12,11,5,11,4,5,10,9,10,12,8,10,10,11,11,10,10,10,10,5,5,10,5,10,11,10,6,8,9,3,12,4,11,11,11,9,12,9,10,12,5,11,12,12,10,12,12,6,10,7,7,7,7,7,9,1,12,8,12,11,9,8,9,9,1,5,6,4,8&reel_set23=reel_set17&reel_set2=reel_set0&reel_set1=reel_set0&reel_set4=reel_set3&purInit=[{bet:3000,type:"free_spins"}]&reel_set3=4,8,10,5,8,4,11,3,10,12,8,12,5,8,12,5,9,6,8,6,11,3,5,10,5,6,12,5,10,8,9,8,3,3,3,9,12,8,4,10,1,5,7,4,9,4,8,12,12,9,7,3,8,7,7,5,11,9,12,7,5,5,10,9,12,9,11,12,10,7,7,7,10,9,4,1,9,3,10,3,11,4,9,10,7,9,10,6,9,9,5,9,7,9,10,3,11,12,12,10,3,8,5,12,8,8,8,10,12,6,7,11,9,6,8,5,11,10,8,4,12,7,11,10,12,8,7,8,8,7,12,12,5,12,8,11,8,5,8,5,12,12~10,7,5,8,10,9,3,10,8,6,1,7,8,9,4,7,12,10,12,4,8,10,11,10,11,11,3,3,3,12,5,8,2,7,8,7,2,8,10,5,8,12,12,9,8,2,10,7,9,12,8,9,12,5,10,11,5,7,7,7,12,5,6,9,11,12,9,11,4,5,5,3,12,4,9,5,9,8,4,3,12,9,4,3,8,8,5,12,8,8,8,3,8,5,10,9,6,7,9,8,5,6,5,1,3,9,8,10,4,12,12,7,7,6,3,10,9,11,11,12~8,9,5,11,6,12,7,12,9,10,8,12,7,12,7,12,5,8,4,9,8,10,8,10,7,12,11,8,12,3,5,8,10,4,9,12,11,7,12,10,8,4,10,5,7,4,9,12,3,3,3,7,11,12,4,8,11,4,1,5,3,8,5,5,6,8,3,5,2,7,8,8,10,8,9,9,4,9,11,7,4,10,12,7,10,11,4,9,5,2,11,7,10,9,9,10,10,12,9,8,7,7,7,9,11,4,5,6,5,8,5,7,9,8,12,6,3,8,9,10,10,9,12,5,8,3,8,5,8,8,11,7,8,4,10,11,12,5,8,6,6,9,12,1,5,9,11,12,9,12,6,5,8,8,8,12,4,3,9,7,9,10,12,12,5,7,5,12,7,5,8,3,12,5,10,8,11,12,3,8,5,6,9,9,3,10,9,11,9,12,10,8,12,10,10,8,12,5,2,3,10,5,9,3~10,9,4,8,12,10,8,11,3,7,10,1,12,9,12,10,12,12,5,9,8,8,7,8,9,7,8,10,5,8,12,8,11,8,3,3,3,11,8,3,7,10,7,12,10,11,8,12,5,5,9,9,5,5,8,11,4,10,9,3,6,10,12,10,6,8,2,10,1,3,12,9,7,7,7,2,10,12,3,5,6,7,10,10,8,5,4,3,7,5,3,3,5,9,8,9,12,12,4,5,9,4,8,9,12,11,9,9,12,5,8,8,8,3,9,5,12,6,8,7,8,5,12,12,5,12,4,2,8,9,5,10,9,11,8,4,7,11,11,7,6,5,9,6,7,8,11,4,4~8,11,12,9,2,3,12,11,3,12,9,9,7,12,6,10,9,8,8,9,4,8,11,9,11,12,10,8,2,10,8,3,5,3,3,3,6,8,6,12,8,5,11,6,11,10,8,10,10,12,9,12,10,8,11,9,10,6,8,10,9,10,10,12,12,5,12,5,11,10,7,7,7,4,5,4,3,7,8,8,7,8,5,7,5,3,11,10,12,5,12,6,4,9,7,8,9,9,4,12,1,8,7,12,4,5,9,8,8,8,7,7,9,9,5,3,10,12,5,10,2,8,5,4,8,12,9,7,5,7,5,1,8,7,4,9,11,3,9,3,5,8,12,5,12~4,9,5,2,1,12,8,12,11,10,5,12,8,6,9,3,4,7,12,9,4,11,5,4,11,9,4,11,12,5,7,9,3,3,3,5,6,3,7,7,12,5,7,3,8,6,12,12,8,9,12,4,11,10,10,12,10,8,9,12,7,5,5,6,8,3,7,10,8,7,7,7,5,8,12,9,8,4,3,5,9,10,11,3,8,10,8,9,10,5,10,6,10,12,5,11,12,8,11,9,11,12,6,9,8,7,8,8,8,7,9,5,2,8,10,5,9,7,7,8,5,2,10,10,8,8,1,9,5,12,12,10,3,4,11,12,9,8,3,8,12,9,10&reel_set20=reel_set17&reel_set6=reel_set5&reel_set5=8,12,12,1,8,12,6,8,10,6,12,12,10,4,4,4,10,1,4,6,6,1,10,4,1,8,6,1,12,12,6,6,6,10,12,1,4,10,1,6,1,8,1,10,10,1,10,1,10,10,10,1,6,10,6,12,1,6,1,8,6,4,1,10,8,12,12,12,8,1,6,4,12,4,4,6,12,8,4,1,6,10,4,1~11,9,5,7,11,5,9,3,9,5,3,3,5,5,3,3,3,3,9,9,11,7,9,9,3,5,5,11,11,3,9,3,3,5,5,5,5,7,11,11,7,9,5,11,5,7,9,5,11,11,9,7,7,7,7,3,9,11,11,7,9,11,3,3,9,11,5,5,9,9,9,9,7,7,11,5,3,3,9,7,7,11,5,5,7,5,3,11,11,11,11,5,9,3,5,9,7,7,9,5,7,7,3,9,5,3,7,11~10,7,5,1,11,7,3,10,9,1,9,6,10,12,6,1,4,7,8,9,11,1,11,1,6,1,5,1,12,7,1,10,1,4,1,12,1,3,1,12,6,10,1,11,9,8,1,8,9,6,3,8,3,1,11,5,1,7,12,3,4,5,1,10,1,7,1,6,7,8,1,3~4,4,6,3,5,11,3,12,6,7,5,8,3,3,3,6,6,7,5,4,3,5,8,3,4,10,4,5,5,5,5,6,8,11,6,10,8,10,9,11,5,6,7,11,4,4,4,4,9,8,9,5,11,12,7,11,9,5,7,11,7,10,10,10,12,8,7,8,8,11,9,5,7,6,5,12,4,6,6,6,6,8,8,10,9,3,9,12,5,3,7,10,6,10,8,8,8,11,9,3,10,12,3,7,5,4,10,6,3,9,12~7,11,5,7,7,1,10,11,6,4,1,11,5,3,12,1,9,1,11,1,8,1,10,1,8,4,6,11,8,7,12,3,1,6,12,1,9,3,5,1,8,10,1,7,1,6,1,12,7,3,6,6,3,1,8,10,4,4,3,7,5,1,9,1,6,5,7,8,12,5,1,10,12,3,1,12,1,10,11,10,1~3,12,1,4,7,1,8,5,11,1,11,5,3,1,7,4,6,5,5,5,8,1,8,8,1,10,9,1,5,7,3,3,10,9,6,8,12,1,6,6,6,5,1,8,10,4,1,10,5,9,4,1,3,6,6,1,11,12,7,9,11&reel_set8=reel_set5&reel_set7=reel_set5&reel_set9=reel_set5&total_bet_min=10.00';
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
                    $allBet = $allBet * 150;
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
                    $stack = $slotSettings->GetReelStrips($winType, 0, $betline * $lines);
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
                        $strOtherResponse = $strOtherResponse . '&purtr=1&puri=' . $pur;
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
                    $arr_trails = explode(';', $str_trail);
                    $fsmax = 0;
                    if(count($arr_trails) == 2){
                        $arr_subtrail = explode('~', $arr_trails[1]);
                        if($arr_subtrail[0] == 'fs'){
                            $fsmax = $arr_subtrail[1];
                        }
                    }
                    if($fsmax >= 30){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $fsmax);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                        $stack = $slotSettings->GetReelStrips('bonus', $fsmax, $betline * $lines);
                        if($stack == null){
                            $response = 'unlogged';
                            exit( $response );
                        }
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1';
                    }
                    else
                    {
                        $spinType = 'b';
                        $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 69);
                    }
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
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse  .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&st=rect&c='.$betline.'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&w='.$totalWin;
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"TumbleState":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') . ',"TumbWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin')  . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"Level":' . $slotSettings->GetGameData($slotSettings->slotId . 'Level') . ',"Trail":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Trail')  . '","G":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'G')) .',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":""}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($slotEvent['slotEvent'] == 'freespin' && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * 150;
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
                if(count($arr_trails) == 2){
                    $arr_subtrail = explode('~', $arr_trails[1]);
                    if($arr_subtrail[0] == 'fs'){
                        $fsmax = $arr_subtrail[1];
                    }
                }
                $currentIndex = 0;
                $freeSpins = [10,14,18,22,26,30];
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
                
                if($fsmax == 0 || $fsmax == 30 || $ind == 0){
                    if($fsmax > 0){                        
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $fsmax);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                        
                        $stack = $slotSettings->GetReelStrips('bonus', $fsmax, $betline * $lines);
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
                    $str_trail = 'fs~' . $fsmax;
                }else{
                    $spinType = 'b';
                    $str_trail = 'try~4;fs~' . $fsmax;
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"Level":' . $slotSettings->GetGameData($slotSettings->slotId . 'Level') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"Trail":"' . $slotSettings->GetGameData($slotSettings->slotId . 'Trail') . '","G":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'G')) . ',"TumbleState":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') . ',"TumbWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin')  . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":""}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * 150;
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

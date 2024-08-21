<?php 
namespace VanguardLTE\Games\PizzaPizzaPizzaPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Bl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,3,3,15,15,11,3,3,3,3,4,3,3,3,3,4,4,3,3,3,6,4,8,3,3,15,4,8,7,3,15,6,9,7,4,15,15,9,1,4,15,15,5,8,4,15,15,15,8,4,15,15,15,5,4,15,15,15,15,4,15,15,15,15,7,15,15,15,15,15]);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bl', $lastEvent->serverResponse->Bl);
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
                    $bet = '50.00';
                }
                $spinType = 's';
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if(isset($stack)){
                    $str_initReel = $stack['initreel'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $arr_g = null;
                    if($stack['g'] != ''){
                        $arr_g = $stack['g'];
                    }
                    $str_prg_m = $stack['prg_m'];
                    $str_prg = $stack['prg'];
                    if($stack['reel_set'] > 0){
                        $currentReelSet = $stack['reel_set'];
                    }
                    $fsmore = $stack['fsmore'];
                    $str_accm = $stack['accm'];
                    $str_acci = $stack['acci'];
                    $str_accv = $stack['accv'];
                    $str_stf = $stack['stf'];
                    $str_srf = $stack['srf'];
                    $fsmax = $stack['fsmax'];
                    $str_trail = $stack['trail'];
                    $strWinLine = $stack['wlc_v'];

                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=0';
                    }
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($str_mo != ''){
                        $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                    }
                    
                    if($arr_g != null){
                        foreach( $arr_g as $index => $value ) 
                        {
                            if(isset($value['mo_m']) && isset($value['mo_tv']) && isset($value['mo_tw'])){
                                $submoneyWin = $value['mo_tv'] * $bet;
                                if($value['mo_m'] > 1){
                                    $submoneyWin = $submoneyWin * $value['mo_m'];
                                }
                                $value['mo_tw'] = '' . $submoneyWin;
                                $arr_g[$index] = $value;
                            }
                        }
                        $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($arr_g));
                    }else{
                        $strOtherResponse = $strOtherResponse . '&g={aperture_s:{def_s:"15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,16,16,15,15,16,16,16,16,16,4,3,3,3,3,4,4,3,3,3,16,4,8,3,3,15,16,8,7,3,15,16,16,7,4,15,15,16,16,4,15,15,16,16,16,15,15,15,16,16,15,15,15,16,16,15,15,15,15,16,15,15,15,15,16,15,15,15,15,15",def_sa:"15,15,15,15,15",def_sb:"15,15,15,15,15",s:"15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,16,16,15,15,16,16,16,16,16,4,3,3,3,3,4,4,3,3,3,16,4,8,3,3,15,16,8,7,3,15,16,16,7,4,15,15,16,16,4,15,15,16,16,16,15,15,15,16,16,15,15,15,16,16,15,15,15,15,16,15,15,15,15,16,15,15,15,15,15",sa:"15,15,15,15,15",sb:"15,15,15,15,15",sh:"18",st:"rect",sw:"5"}}';
                    }
                    if($str_prg != ''){
                        $strOtherResponse = $strOtherResponse . '&prg=' . $str_prg;
                    }
                    if($str_prg_m != ''){
                        $strOtherResponse = $strOtherResponse . '&prg_m=' . $str_prg_m;
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
                }else{
                    $strOtherResponse = $strOtherResponse . '&g={aperture_s:{def_s:"15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,16,16,15,15,16,16,16,16,16,4,3,3,3,3,4,4,3,3,3,16,4,8,3,3,15,16,8,7,3,15,16,16,7,4,15,15,16,16,4,15,15,16,16,16,15,15,15,16,16,15,15,15,16,16,15,15,15,15,16,15,15,15,15,16,15,15,15,15,15",def_sa:"15,15,15,15,15",def_sb:"15,15,15,15,15",s:"15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,16,16,15,15,16,16,16,16,16,4,3,3,3,3,4,4,3,3,3,16,4,8,3,3,15,16,8,7,3,15,16,16,7,4,15,15,16,16,4,15,15,16,16,16,15,15,15,16,16,15,15,15,16,16,15,15,15,15,16,15,15,15,15,16,15,15,15,15,15",sa:"15,15,15,15,15",sb:"15,15,15,15,15",sh:"18",st:"rect",sw:"5"}}';
                }
                
                $Balance = $slotSettings->GetBalance();  
                $response = 'def_s=15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,3,3,15,15,11,3,3,3,3,4,3,3,3,3,4,4,3,3,3,6,4,8,3,3,15,4,8,7,3,15,6,9,7,4,15,15,9,1,4,15,15,5,8,4,15,15,15,8,4,15,15,15,5,4,15,15,15,15,4,15,15,15,15,7,15,15,15,15,15&balance='. $Balance .'&cfgs=6853&ver=3&index=1&balance_cash='. $Balance .'&def_sb=3,7,11,5,8&reel_set_size=5&def_sa=8,5,4,12,6&reel_set='. $currentReelSet .'&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0,0~0,0,0,0,0,0~1,1,1,1,1,1&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"230450090",max_rnd_win:"7200",max_rnd_win_a:"4800",max_rnd_hr_a:"12333498"}}&wl_i=tbm~7200;tbm_a~4800&bl='. $slotSettings->GetGameData($slotSettings->slotId . 'Bl') .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime='. floor(microtime(true) * 1000) .'&sa=8,5,4,12,6&sb=3,7,11,5,8&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=50.00&purInit_e=1&sh=18&wilds=2~0,0,0,0,0~1,1,1,1,1;13~0,0,0,0,0,0~1,1,1,1,1,1;14~0,0,0,0,0,0~1,1,1,1,1,1&bonuses=0&st=rect&c='. $bet .'&sw=5&sver=5&bls=20,30&counter=2&paytable=0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0;200,30,6,1,0;80,20,4,0,0;50,10,3,0,0;50,10,3,0,0;50,10,3,0,0;40,8,2,0,0;40,8,2,0,0;20,5,1,0,0;20,5,1,0,0;20,4,1,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0&l=20&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=6,11,9,4,11,3,7,10,8,10,11,3,7,8,9,6,4,7,3,5,6,5,11,9,9,5,9,6,4,11,4,12,10,12,11,3,4,12,4,6,9,8,5,10,12,12,8,5,8,7,10,6,8,7,12,5,3,7,10,3,1~12,6,6,1,4,8,5,7,5,3,3,3,12,3,9,5,9,6,5,6,3,7,11,4,4,4,10,10,6,3,6,7,12,11,6,12,8,7,7,7,7,5,8,11,7,9,5,10,1,12,11,5,5,5,8,8,4,11,7,4,12,7,9,7,7,6,6,6,8,5,4,7,11,3,6,9,3,5,4,9~5,8,5,6,4,3,3,3,3,4,11,6,8,11,5,5,5,5,9,5,9,7,10,3,7,7,7,7,7,11,12,8,7,1,6,6,6,6,12,7,6,7,12,10,4,4,4,4,3,6,6,9,4,3,5,7~5,4,9,5,7,3,3,3,3,3,12,4,5,6,7,11,5,5,5,5,5,3,8,5,10,3,1,4,4,4,4,4,5,7,12,11,7,11,7,7,7,7,7,7,9,6,12,6,6,8,6,6,6,6,6,8,3,4,9,7,1~9,7,9,5,7,5,3,3,3,3,3,3,11,10,7,6,3,4,11,4,4,4,4,4,4,9,5,5,12,12,1,12,7,7,7,7,7,7,7,5,5,1,3,7,6,6,6,6,6,6,3,8,8,4,3,6,5,5,5,5,5,5,11,6,4,7,8,10,6,4&s='.$lastReelStr.'&accInit=[{id:0,mask:"cp;cl;mx"}]&reel_set2=12,12,3,6,11,9,4,5,8,4,4,10,11,3,9,11,6,7,10,7,8,10,5,12,5,3,8,6,9,7~10,12,5,9,11,6,3,3,3,9,1,8,7,1,3,12,6,6,6,8,8,6,10,5,9,5,7,7,7,7,11,6,12,12,5,3,3,4,4,4,5,7,4,6,7,4,9,5,5,5,11,6,7,7,4,11,8,7,1~11,8,7,12,9,8,3,3,3,3,7,4,10,10,9,12,6,4,4,4,4,5,11,9,3,3,5,6,6,6,6,4,5,5,3,8,12,3,7,7,7,7,7,11,5,7,9,8,5,5,5,5,4,4,12,6,6,7,7,11,6~11,5,7,3,3,3,3,3,6,4,7,6,3,5,5,5,5,5,8,4,5,6,1,6,6,6,6,6,5,11,5,12,4,7,7,7,7,7,7,10,9,3,3,4,4,4,4,4,6,12,9,1,8,7~3,5,5,3,8,3,3,3,3,3,3,8,6,3,11,10,6,6,6,6,6,6,7,12,4,7,5,9,7,7,7,7,7,7,7,9,8,5,6,5,7,4,4,4,4,4,4,9,11,4,5,12,7,6,5,5,5,5,5,5,6,7,4,12,7,4,10,11&reel_set1=9,3,7,3,7,9,12,8,1,3,10,8,1,8,6,10,1,12,12,9,11,11,5,5,1,4,7,10,6,11,4,5,4,6~9,3,10,8,7,3,3,3,4,8,4,8,8,10,6,4,4,4,7,12,7,3,5,9,12,7,7,7,7,9,11,6,6,5,12,5,5,5,9,5,3,11,5,6,7,6,6,6,4,5,7,7,11,12,11~12,7,5,7,12,1,11,3,3,3,3,8,7,11,5,6,6,4,4,4,4,7,9,8,3,5,3,11,12,12,7,7,7,7,7,10,7,3,5,5,3,10,1,6,6,6,6,8,6,6,4,8,11,6,7,5,5,5,5,7,5,1,9,1,6,9,9,4,4~7,12,12,10,9,12,5,6,9,3,3,3,3,3,11,4,11,8,6,11,5,6,10,10,10,10,10,9,5,10,6,12,4,9,7,10,11,6,6,6,6,6,8,10,4,6,7,5,5,3,5,6,8,7,7,7,7,7,10,5,9,12,6,9,12,7,3,10,11,11,11,11,11,7,4,11,11,3,3,7,5,7,8,8,8,8,8,9,8,4,6,8,5,7,10,11,3,9,9,9,9,9,3,6,6,11,11,12,10,8,4,5,4,12,12,12,12,12,10,8,12,4,3,7,7,9,8,4,4,4,4,4,10,5,8,4,7,12,7,8,7,12,5,5,5,5,5,12,6,3,3,11,11,9,9,3,4,9,7~11,10,6,8,3,8,4,8,11,5,7,7,6,6,3,7,5,3,3,3,3,3,3,10,9,3,4,11,5,7,9,3,10,8,12,5,5,9,6,7,7,9,9,9,9,9,5,8,10,10,11,8,11,3,5,8,4,5,3,11,3,10,4,9,5,5,5,5,5,5,12,11,4,7,12,10,7,5,8,6,4,8,4,3,6,12,12,7,7,7,7,7,7,7,10,7,6,10,7,11,10,4,7,8,8,4,9,5,9,10,6,4,8,8,8,8,8,8,12,11,11,5,3,7,9,12,5,9,4,9,11,8,6,11,12,8,11,11,11,11,11,11,8,4,8,9,4,5,6,3,9,12,6,4,10,5,4,6,11,11,12,12,12,12,12,12,4,12,8,6,4,6,6,8,7,6,9,10,10,5,4,3,10,12,10,10,10,10,10,10,6,5,12,8,5,4,9,7,3,3,10,6,12,7,7,12,12,4,4,4,4,4,4,11,11,5,10,6,7,3,7,7,3,7,10,3,3,11,7,12,6,6,6,6,6,6,3,12,12,11,11,9,12,7,3,10,8,5,8,4,12,7,3,11,7&reel_set4=12,10,7,3,12,6,6,11,8,3,3,9,10,5,3,7,9,11,4,10,5,8,9,5,8,8,12,7,10,3,8,10,12,6,9,11,11,4,7,5,9,11,3,6,9,4,7,10,7,8,4,6,4,6,4,12,11,12,5,5~8,6,4,11,5,10,4,7,7,5,3,3,3,11,11,7,7,10,9,5,11,10,6,9,9,4,4,4,11,9,5,6,7,8,8,11,7,4,9,7,7,7,7,5,6,5,7,6,8,3,5,3,6,8,4,5,5,5,3,10,6,7,12,12,9,12,12,7,8,8,6,6,6,7,12,3,3,12,9,5,5,7,11,12~7,11,5,8,12,9,3,3,3,3,7,3,6,5,8,3,12,5,5,5,5,12,4,5,11,7,3,7,7,7,7,7,8,9,12,6,11,4,6,6,6,6,7,3,5,9,8,10,4,4,4,4,10,5,11,4,7,4,6,9~10,10,11,5,6,3,3,3,3,3,6,3,6,5,12,5,5,5,5,5,4,7,11,7,11,7,4,4,4,4,4,7,5,4,7,12,6,7,7,7,7,7,7,9,8,3,9,4,6,6,6,6,6,8,4,9,5,6,3,12,8~4,7,8,11,5,3,3,3,3,3,3,12,5,12,3,6,7,4,4,4,4,4,4,12,6,7,8,6,9,7,7,7,7,7,7,7,4,7,4,6,5,8,6,6,6,6,6,6,5,7,4,3,7,5,5,5,5,5,5,9,10,3,9,11,11,6,3&purInit=[{bet:2000,type:"default"}]&reel_set3=6,12,10,5,5,3,12,7,4,12,11,4,9,8,7,12,9,11,12,3,6,9,10,9,4,8,8,11,12,10,8,10,10,12,3,8,8,12,10,6,7,10,8,5,10,11,5,11,4,10,6,12,8,7,8~9,10,6,5,5,5,11,11,7,7,9,9,9,3,9,12,9,4,4,4,9,11,6,11,3,3,3,9,7,10,7,8,7,7,7,11,12,4,11,11,11,3,4,4,8,6,6,6,5,10,10,7,9,10,10,10,11,3,12,8,12,12,12,7,5,5,11,6,8~7,5,7,8,7,7,7,7,10,8,6,7,8,11,5,6,6,6,6,7,11,12,5,3,8,7,3,3,3,3,5,5,7,4,12,7,4,4,4,4,11,7,9,9,5,12,4,11,11,11,11,6,3,8,10,7,7,12,5,5,5,5,9,9,7,7,9,7,6,9,9,9,9,6,10,6,11,10,3,10,10,10,10,6,7,4,8,12,4,10,8,8,8,8,11,11,9,7,7,3,10,12,12,12,12,4,7,3,12,4,6,5,4~4,5,11,8,3,7,3,3,3,3,3,12,9,6,5,7,4,10,8,8,8,8,8,10,9,11,6,5,11,7,11,11,11,11,11,5,10,4,7,5,4,5,5,5,5,5,10,6,9,9,7,3,11,7,7,7,7,7,9,11,10,6,12,11,4,4,4,4,4,3,7,12,3,12,3,3,10,10,10,10,10,4,4,7,11,7,8,12,9,9,9,9,9,4,3,7,6,8,7,6,6,6,6,6,4,6,7,9,12,5,7,12,12,12,12,12,7,6,8,12,5,8,8,10,7~4,12,8,8,12,11,4,4,4,4,4,4,11,7,5,9,6,9,10,7,7,7,7,7,7,7,6,4,11,6,8,5,8,11,11,11,11,11,11,7,6,3,6,12,9,10,7,5,5,5,5,5,5,12,12,5,11,8,7,7,3,3,3,3,3,3,4,11,7,10,10,7,3,8,8,8,8,8,8,3,4,12,7,6,5,7,6,6,6,6,6,6,3,6,4,10,7,4,8,7,10,10,10,10,10,10,3,9,9,3,4,11,5,9,9,9,9,9,9,7,10,7,7,11,7,12,12,12,12,12,12,7,10,12,5,5,8,7,7,9&total_bet_min=10.00';
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
                $bl = $slotEvent['bl'];
                $pur = -1;
                if(isset($slotEvent['pur'])){
                    $pur = $slotEvent['pur'];
                }
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

                // $winType = 'bonus';

                $allBet = $betline * $lines;
                if($pur >= 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $allBet * 100;
                }else if($bl > 0){
                    $allBet = $betline * 30;
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bl', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '4174458' . substr($roundstr, 4, 10);
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
                $str_prg_m = '';
                $str_prg = '';
                $fsmore = 0;
                $str_accm = '';
                $str_acci = '';
                $str_accv = '';
                $str_stf = 0;
                $str_srf = 0;
                $str_trail = 0;
                $fsmore = 0;
                $fsmax = 0;
                $arr_g = null;
                $scatterCount = 0;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_initReel = $stack['initreel'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    if($stack['g'] != ''){
                        $arr_g = $stack['g'];
                    }
                    $str_prg_m = $stack['prg_m'];
                    $str_prg = $stack['prg'];
                    $currentReelSet = $stack['reel_set'];
                    $fsmore = $stack['fsmore'];
                    $str_accm = $stack['accm'];
                    $str_acci = $stack['acci'];
                    $str_accv = $stack['accv'];
                    $str_stf = $stack['stf'];
                    $str_srf = $stack['srf'];
                    $str = $stack['srf'];
                    $strReelSa = $stack['sa'];
                    $strReelSb = $stack['sb'];
                    $fsmax = $stack['fsmax'];
                    $str_trail = $stack['trail'];
                    $strWinLine = $stack['wlc_v'];
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
                    if($stack[0]['g'] != ''){
                        $arr_g = $stack[0]['g'];
                    }
                    $str_prg_m = $stack[0]['prg_m'];
                    $str_prg = $stack[0]['prg'];
                    $currentReelSet = $stack[0]['reel_set'];
                    $fsmore = $stack[0]['fsmore'];
                    $str_accm = $stack[0]['accm'];
                    $str_acci = $stack[0]['acci'];
                    $str_accv = $stack[0]['accv'];
                    $str_stf = $stack[0]['stf'];
                    $str_srf = $stack[0]['srf'];
                    $strReelSa = $stack[0]['sa'];
                    $strReelSb = $stack[0]['sb'];
                    $fsmax = $stack[0]['fsmax'];
                    $str_trail = $stack[0]['trail'];
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
                if($arr_g != null){
                    foreach( $arr_g as $index => $value ) 
                    {
                        if(isset($value['mo_m']) && isset($value['mo_tv']) && isset($value['mo_tw'])){
                            $submoneyWin = $value['mo_tv'] * $betline;
                            if($value['mo_m'] > 1){
                                $submoneyWin = $submoneyWin * $value['mo_m'];
                            }
                            $totalWin = $totalWin + $submoneyWin;
                            $value['mo_tw'] = '' . $submoneyWin;
                            $arr_g[$index] = $value;
                        }
                    }
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Bl', $bl);
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
                    $strOtherResponse = $strOtherResponse . '&puri=0';
                }
                if($pur >= 0){
                    $strOtherResponse = $strOtherResponse . '&purtr=1';
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                }
                if($str_prg != ''){
                    $strOtherResponse = $strOtherResponse . '&prg=' . $str_prg;
                }
                if($str_prg_m != ''){
                    $strOtherResponse = $strOtherResponse . '&prg_m=' . $str_prg_m;
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
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&wlc_v=' . $strWinLine;
                }
                if($arr_g != null){
                    $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($arr_g));
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance . '&reel_set='. $currentReelSet.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&bl='. $bl .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=18&st=rect&c='.$betline.'&sw=5&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&w='.$totalWin.'&s=' . $strLastReel;
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Bl":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bl') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($slotEvent['slotEvent'] == 'freespin' && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * 100;
                }else if($slotSettings->GetGameData($slotSettings->slotId . 'Bl') > 0){
                    $allBet = $betline * 30;
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

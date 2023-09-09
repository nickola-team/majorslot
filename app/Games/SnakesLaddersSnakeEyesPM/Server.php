<?php 
namespace VanguardLTE\Games\SnakesLaddersSnakeEyesPM
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
            $original_bet = 20;
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
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [4,10,8,4,9,5,8,5,5,6,3,9,6,3,9]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $strOtherResponse = '';
                $currentReelSet = 0;
                $stack = null;
                if($lastEvent == 'NULL'){
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '561' . substr($roundstr, 4, 10);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);
                }
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $lastEvent->serverResponse->CurrentRespin);
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
                    $bet = '100.00';
                }
                $spinType = 's';
                $fsmore = 0;
                $fsmul = 1;
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if(isset($stack)){
                    $str_trail = $stack['trail'];
                    $str_accm = $stack['accm'];
                    $str_accv = $stack['accv'];
                    $mo_tv = $stack['mo_tv'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $str_mo_wpos = $stack['mo_wpos'];
                    $wmv = $stack['wmv'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $rs_more = $stack['rs_more'];
                    $g = null;
                    if($stack['g'] != ''){
                        $g = $stack['g'];
                    }
                    $strWinLine = $stack['win_line'];
                    if($stack['reel_set'] >= 0){
                        $currentReelSet = $stack['reel_set'];
                    }
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    
                    if($rs_p >= 0){
                        $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p . '&rs_c=' . $rs_c . '&rs_m=' . $rs_m;
                    }else if($rs_t > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t;
                    }
                    if($str_trail != ''){
                        $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                    }                
                    if($str_mo != ''){
                        $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                    }
                    if($str_mo_wpos != ''){
                        $strOtherResponse = $strOtherResponse . '&mo_wpos=' . $str_mo_wpos;
                    }
                    if($mo_tv > 0){
                        $strOtherResponse = $strOtherResponse . '&mo_tv=' . $mo_tv . '&mo_tw=' . ($mo_tv * $bet);
                    }
                    if($str_accv != ''){
                        $strOtherResponse = $strOtherResponse . '&accv=' . $str_accv . '&acci=0&accm=' . $str_accm;
                    }
                    if($g != null){
                        $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($g));
                    }else{
                        $strOtherResponse = $strOtherResponse . '&g={respin:{def_s:"12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12",def_sa:"12,12,12,12,12,12,12,12,12,12,12,12",def_sb:"12,12,12,12,12,12,12,12,12,12,12,12",s:"12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12",sa:"12,12,12,12,12,12,12,12,12,12,12,12",sb:"12,12,12,12,12,12,12,12,12,12,12,12",sh:"12",st:"rect",sw:"12"}}';
                    }
                    if($wmv > 0){
                        $strOtherResponse = $strOtherResponse . '&wmv=' . $wmv . '&wmt=pr';
                        if($wmv > 1){
                            $strOtherResponse = $strOtherResponse . '&gwm=' . $wmv;
                        }
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
                }else{
                    $strOtherResponse = $strOtherResponse . '&g={respin:{def_s:"12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12",def_sa:"12,12,12,12,12,12,12,12,12,12,12,12",def_sb:"12,12,12,12,12,12,12,12,12,12,12,12",s:"12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12,12",sa:"12,12,12,12,12,12,12,12,12,12,12,12",sb:"12,12,12,12,12,12,12,12,12,12,12,12",sh:"12",st:"rect",sw:"12"}}';
                }                
                $Balance = $slotSettings->GetBalance();  

                $response = 'def_s=4,10,8,4,9,5,8,5,5,6,3,9,6,3,9&balance='. $Balance .'&cfgs=6701&ver=3&mo_s=2&index=1&balance_cash='. $Balance .'&mo_v=1,2,3&def_sb=3,11,11,3,8&reel_set_size=3&def_sa=3,7,10,4,11&reel_set='. $currentReelSet .'&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"3891051",max_rnd_win:"5300"}}&wl_i=tbm~5300&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime='. floor(microtime(true) * 1000) .'&sa=3,7,10,4,11&sb=3,11,11,3,8&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=100.00&sh=3&wilds=2~500,250,100,0,0~1,1,1,1,1&bonuses=0&st=rect&c='. $bet .'&sw=5&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;500,250,100,0,0;200,100,50,0,0;200,100,50,0,0;150,75,30,0,0;100,40,15,0,0;100,40,15,0,0;50,15,5,0,0;50,15,5,0,0;50,15,5,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0&l=10&reel_set0=6,5,8,6,10,3,5,4,3,4,9,5,9,2,5,11,4,8,8,10,7,3,7,4,8,8,4,8,11,4,6,11,6,9,7,4,5,9,3,11,7,3,4,8,5,6,9,9,8,9,1,6,4,7,1,10,11,1,4,10,4,6,1,5,1,8,5,3,8,6,10,11,7,6,7,6,10,9,11,5,7,6,10,6,11,5,3,1,3,5,7,8,10,7,5,7,2,6,9,11,3,11,11,7,8,8,11,9,1,11,4,8,8,9,5,8,7,9,7,8,11,6,5,7,8,5,3,6,11,9,9,5,9,10,8,7,10,4,5,7,6,8,6,1,10,11,8,9,6,9,4,1,3,6,9,3,10,3,11,8,11,7,8,7~8,7,9,5,11,9,8,4,5,10,3,7,8,5,3,8,11,10,6,8,4,8,5,6,8,10,6,3,6,3,10,7,1,10,3,6,8,1,6,1,11,10,5,6,11,10,5,1,4,11,4,11,8,8,10,11,1,7,4,9,4,5,11,4,6,8,9,8,7,3,10,1,4,11,8,10,4,5,7,4,7,8,3,4,8,5,9,10,3,10,11,7,3,11,7,9,3,10,9,10,6,11,7,9,5,6,9,11,6,1,5,7,5,8,5,1,7,1,3,7,8,11,10,5,7,5,9,3,9,11,4,3,7,4,8,2,8,11,7,10,6,11,6,11,9,4,8,9,5,3,10,7,8,5,9,6,4,11,2,5,8,5,10,8,8,6,8,1,6,11,6,8,4,6,8,4,10,6,8~7,5,7,8,5,6,4,10,7,6,8,10,8,6,5,4,9,11,9,11,10,7,10,8,6,4,10,3,4,9,8,5,8,7,5,7,4,8,4,11,6,8,10,5,11,8,4,11,7,3,6,7,6,7,3,6,3,7,11,10,6,10,3,5,8,6,9,10,11,10,5,11,3,10,6,10,8,4,3,1,10,5,7,9,10,7,8,9,7,6,8,2,4,9,5,10,8,7,11,7,5,8,4,6,4,3,10,7,11,9,8,10,9,8,10,4,6,8,11,7,4,6,8,11,5,4,5,7,6,10,1,8,4,5,8,7,6,11,8,9,7,11,7,8,3,4,3,7,9,7,8,6,7,9,5,3,2,8,4,11,9,6~9,10,11,5,3,10,6,7,10,5,4,10,7,11,6,7,9,11,4,9,11,11,9,3,8,9,4,7,9,11,10,8,3,11,10,11,10,4,6,9,10,3,6,8,9,7,10,8,9,7,5,7,1,2,7,10,5,1,11,2,11,10,4,11,7,10,9,11,10,8,7,10,9,4,10,3,11,7,10,8,7,9,4,7,8,7,11,9,6,10,6,7,4,5,8,3,10,9,4,11,5,6,8,8,10,5,8,9,3,1,7,6,11,9,10,11,11,1,7,5,10,6,1,3,11,11,7,11,10,3,8,6,7,11,3,9,7,9,11,5,10,4,11,10,8,9,10,11~3,6,10,9,4,9,8,9,7,3,5,2,7,7,8,11,10,5,7,8,11,7,11,11,7,8,9,11,3,6,10,8,10,4,9,7,10,6,10,10,11,6,8,11,7,5,7,7,8,11,11,10,6,10,7,8,9,8,4,1,7,11,9,10,6,4,7,7,10,10,10,8,7,9,11,5,8,4,1,6,11,7,4,10,3,7,8,10,8,11,8,9,7,5,11,8,10,9,3,8,3,8,9,10,8,4,3,7,11,8,9,10,5,3,7,10,11,1,8,6,8,7,10,5,7,5,3,9,8,9,7,10,3,2,11,11,8,7,5,6,9,6,11&s='.$lastReelStr.'&accInit=[{id:0,mask:"d"}]&reel_set2=11,9,7,9,11,8,6,8,1,3,9,11,4,3,8,5,9,8,7,4,11,11,9,1,3,6,8,4,5,6,8,7,11,7,10,8,1,11,10,6,5,11,11,8,1,9,7,7,8,4,11,8,6,11,9,1,10,5,4,5,11,2,5,4,5,10,2,1,8,5,7,3,7,8,8,10,5,9,10,1,7,7,8,6,10,9,8,10,1,5,6,11,5,11,4,7,6,3,8,9,6,8,1,4,6,5,6,10,9,11,9,7,3,5,1,11,5,4,7,10,7,7,11~5,2,10,9,4,10,7,8,7,3,6,5,8,1,7,6,3,7,8,10,2,10,11,11,8,8,6,1,8,9,8,10,4,6,7,9,8,9,7,11,7,9,4,7,8,5,11,8,7,4,1,6,4,11,3,10,8,7,8,7,8,4,3,10,6,8,8,4,8,1,9,3,11,7,11,11,5,4,5,10,8,1,10,9,8,4,9,6,5,6,10,7,5,11,8,4,9,5,11,7,1,6,10,5,6,3,10,7,3,11,6,5,3,11,1,10,8,4,11,5,8,10,4,8,7,11,3,5,7,5,9,10,1,8,9,5,3,8,10,11,11,9,11,6,5,9,1,11,5,10,6,4,7,8,8,3,11,8,7,10,5,1,9,10,6,10,4,11,5,3,11,5,4,8,10,6,1,7,11,10,3,11,8,3,10,6~3,6,7,10,4,6,11,4,9,8,5,7,9,1,8,4,3,7,4,5,9,11,4,10,7,6,8,6,4,1,7,9,8,3,8,7,2,10,7,4,7,10,7,11,10,4,7,5,10,8,3,1,9,6,8,5,8,10,7,3,6,8,4,8,6,11,4,6,1,8,6,5,8,5,6,3,5,4,8,7,5,9,3,5,8,1,10,7,10,8,7,10,11,3,8,11,4,10,6,11,1,8,11,5,8,6,10,9,10,7,8,6,4,10,5,1,7,10,5,9,11,3,11,7,11,8,9,10,6,4,7,8,6,5,1,3,5,6,7,9,11,9,8,2,8,6,5,1,10,7,3,10,5,9,3,7,10,11,7,11~5,4,9,7,10,11,3,10,10,9,6,5,1,10,7,10,9,11,4,7,9,4,8,10,6,10,1,7,3,11,11,10,7,8,7,11,9,2,6,5,2,8,10,6,7,11,3,7,1,10,10,11,8,9,7,9,9,10,11,10,10,3,11,1,5,8,11,11,7,11,10,7,11,7,4,1,7,9,9,11,11,6,9,6,3,11,10,10,11,11,9,8,1,11,7,9,10,4,6,7,6,3,11,11,3,7,8,1,8,7,9,8,11,5,10,4,3,10,5,9,5,6,3,4,8,10,1,7,11,10,10,5,9,7,4,7,11,1,8,10,9,6~8,3,5,10,5,11,10,9,6,9,7,11,1,7,7,9,11,5,7,11,6,11,10,11,4,5,1,10,7,9,11,5,8,3,10,11,7,11,10,9,4,8,3,10,8,11,3,1,8,10,8,10,6,7,6,4,10,8,6,3,8,7,6,11,1,7,5,2,11,3,7,10,10,7,3,9,7,7,11,8,1,10,8,10,10,3,6,10,7,8,6,4,10,4,10,1,10,10,10,5,9,8,11,3,11,5,11,7,8,7,1,11,7,5,9,8,7,8,7,8,7,10,7,7,10,8,9,9,7,1,4,10,7,9,11,3,11,6,10,10,9,11,5,1,8,9,5,4,7,5,7,8,5,9,11,4,8,3,6,1,7,8,7,11,9,11,9,11,9,10,4,8,6,1,5,10,7,6,11,7,7,11,9,7,4,7,7,10,1,8,11,11,8,3,8,2,10,9,8,9&reel_set1=7,4,8,8,7,6,8,7,9,3,8,11,6,8,5,11,6,10,8,4,3,9,8,3,5,11,11,10,8,5,9,11,7,8,5,7,10,8,5,4,6,7,8,4,9,11,4,8,11,11,9,5,3,10,8,8,6,7,10,4,8,10,8,8,10,9,9,11,6,3,4,7,3,11,11,4,8,5,10,11,7,8,3,6,5,7,4,9,10,3,6,9,11,9,9,6,8,10,9,7,4,11,5,4,10,9,8,6,5,7,6,11,5,11,5,7~4,7,8,11,10,8,5,9,11,7,4,8,6,11,6,4,5,10,6,7,8,3,8,8,6,8,8,9,3,6,4,9,4,11,7,11,8,11,7,3,5,8,9,3,6,4,3,9,8,6,3,8,5,6,7,8,10,4,8,10,11,8,7,5,6,5,10,5,10,3,8,8,11,10,9,5,9,7,11,8,7,10,5,3,10,9,4,9,10,5,8,6,11,7,10,4,8,4,11,8,11,8,7,6,5,9,6,11,3,9~8,4,8,11,7,8,11,4,7,4,5,11,7,10,6,10,7,10,8,7,4,6,9,7,9,7,6,10,7,11,9,6,8,9,7,9,8,7,5,10,4,8,3,6,3,5,11,8,6,9,9,5,6,7,8,10,9,7,5,4,6,11,4,11,8,6,3,8,5,10,5,4,6,3,11,3,4,5,9,11,4,7,3,4,5,6,7,6,8,9,7,5,7,11,10,5,8,7,9,3,5,6,4,10,8,6,10,5,7,11,7,8,9,8,4,10,8,10,4,5,8,3,7,10,8,5,4,7,5,7,10,9,4,8,10,8,7,3,10,11,8,11,3,8,6,3,8,10,3,8,7,6,11,7,10,5,8,3,10,11,7,3,8,7,4,11,8,6~11,3,3,10,11,10,7,3,7,10,7,4,9,4,9,8,11,10,5,7,6,11,7,3,10,9,8,7,3,4,9,11,4,10,9,5,11,8,11,7,9,7,11,9,9,10,7,6,3,10,6,9,8,11,9,4,11,8,11,6,5,6,10,7,10,7,3,8,8,11,11,10,8,6,10,8,7,11,10,11,9,5,7,9,4,10,9,3,6,10,5,11,8,7,11,8,9,11,4,6,5,11,9,7,10,4,6,11,11,7,11,7,9,10,11,8,10,9,7,9,10,3,7,3,5,9~3,10,7,7,3,9,7,11,10,7,10,11,8,10,3,7,7,9,11,11,10,10,9,7,8,3,8,7,6,11,11,7,11,5,8,4,5,7,6,3,7,9,9,10,8,9,7,11,11,5,7,11,8,7,6,8,7,11,10,7,8,10,11,5,10,9,9,7,9,8,6,8,9,3,10,8,10,10,10,3,8,5,11,7,6,8,11,8,11,7,7,8,3,7,11,7,11,9,8,11,9,6,9,11,4,10,8,9,10,10,7,10,4,9,11,5,11,4,10,8,6,7,5,6,8,7,5,3,7,10,3,4,6,4,10,8,11,4,8,10,3,8,10,9,7,11,6,7,9,3,7,9';
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
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') >= 0 ) 
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
                $tumbAndFreeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'freespin'){
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    // $leftFreeGames = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') - $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame'); 
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
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
                $Balance = $slotSettings->GetBalance();
                $totalWin = 0;
                $bonusMpl = 1;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $g = null;
                $fsmore = 0;
                $str_trail = '';
                $str_accv = '';
                $str_accm = '';
                $str_mo = '';
                $str_mo_t = '';
                $str_mo_wpos = '';
                $mo_tv = 0;
                $wmv = 0;
                $rs_p = -1;
                $rs_c = 0;
                $rs_m = 0;
                $rs_t = 0;
                $rs_more = 0;
                $fsmore = 0;
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $str_trail = $stack['trail'];
                    $str_accm = $stack['accm'];
                    $str_accv = $stack['accv'];
                    $mo_tv = $stack['mo_tv'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $str_mo_wpos = $stack['mo_wpos'];
                    $wmv = $stack['wmv'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $rs_more = $stack['rs_more'];
                    if($stack['g'] != ''){
                        $g = $stack['g'];
                    }
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
                    $currentReelSet = $stack[0]['reel_set'];
                    $str_trail = $stack[0]['trail'];
                    $str_accm = $stack[0]['accm'];
                    $str_accv = $stack[0]['accv'];
                    $mo_tv = $stack[0]['mo_tv'];
                    $str_mo = $stack[0]['mo'];
                    $str_mo_t = $stack[0]['mo_t'];
                    $str_mo_wpos = $stack[0]['mo_wpos'];
                    $wmv = $stack[0]['wmv'];
                    $rs_p = $stack[0]['rs_p'];
                    $rs_c = $stack[0]['rs_c'];
                    $rs_m = $stack[0]['rs_m'];
                    $rs_t = $stack[0]['rs_t'];
                    $rs_more = $stack[0]['rs_more'];
                    if($stack[0]['g'] != ''){
                        $g = $stack[0]['g'];
                    }
                    $strWinLine = $stack[0]['win_line'];
                }
                for($i = 0; $i < 15; $i++){
                    if($lastReel[$i] == $scatter){
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
                $moneyWin = 0;
                if($mo_tv > 0){
                    $moneyWin = $betline * $mo_tv;
                    if($rs_t > 0){
                        $totalWin = $totalWin + $moneyWin;
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
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $rs_p);
                // if($scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin'){
                //     $freeSpins = [0,0,0,8,10,15];
                //     $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freeSpins[$scatterCount]);
                //     $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                //     $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                // }else if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                //     $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                // }
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
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                $strOtherResponse = '';
                if($rs_p >= 0){
                    $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p . '&rs_c=' . $rs_c . '&rs_m=' . $rs_m;
                    $isState = false;
                    $spinType = 's';
                }else if($rs_t > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t;
                    $isState = true;
                    $spinType = 'c';
                }
                $strOtherResponse = $strOtherResponse . '&w=' . $totalWin;
                if($str_trail != ''){
                    $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                }                
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                }
                if($str_mo_wpos != ''){
                    $strOtherResponse = $strOtherResponse . '&mo_wpos=' . $str_mo_wpos;
                }
                if($mo_tv > 0){
                    $strOtherResponse = $strOtherResponse . '&mo_tv=' . $mo_tv . '&mo_tw=' . $moneyWin;
                }
                if($str_accv != ''){
                    $strOtherResponse = $strOtherResponse . '&accv=' . $str_accv . '&acci=0&accm=' . $str_accm;
                }
                if($g != null){
                    $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($g));
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                }
                if($wmv > 0){
                    $strOtherResponse = $strOtherResponse . '&wmv=' . $wmv . '&wmt=pr';
                    if($wmv > 1){
                        $strOtherResponse = $strOtherResponse . '&gwm=' . $wmv;
                    }
                }
                
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin')  . '&reel_set='. $currentReelSet . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&st=rect&c='.$betline.'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=10&s=' . $strLastReel;
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

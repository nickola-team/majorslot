<?php 
namespace VanguardLTE\Games\BarnFestivalPM
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
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [3,8,4,8,11,10,6,10,5,7,8,9,6,9,8,7,4,5,3,4,3,8,4,8,11,10,6,10,5,7]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $strOtherResponse = '';
                $currentReelSet = 8;
                
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
                        $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                        // $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                        $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') -1];
                        if(isset($stack)){
                            $str_initreel = $stack['initReel'];
                            $currentReelSet = $stack['reel_set'];
                            $str_trail = $stack['trail'];
                            $str_mo = $stack['mo'];
                            $str_mo_t = $stack['mo_t'];
                            $mo_tv = $stack['mo_tv'];
                            $str_mo_wpos = $stack['mo_wpos'];
                            $mo_m = $stack['mo_m'];
                            $lmi = $stack['lmi'];
                            $lmv = $stack['lmv'];
                            $rs_more = $stack['rs_more'];
                            $str_s_mark = $stack['s_mark'];
                            $wmv = $stack['wmv'];
                            $gwm = $stack['gwm'];
                            $str_stf = $stack['stf'];
                            $rs_m = $stack['rs_m'];
                            $rs_c = $stack['rs_c'];
                            $rs_p = $stack['rs_p'];
                            $rs_t = $stack['rs_t'];
                            if($str_initreel != ''){
                                $strOtherResponse = $strOtherResponse . '&is=' . $str_initreel;
                            }
                            if($str_trail != ''){
                                $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                            }
                            if($str_mo != ''){
                                $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo;
                            }
                            if($str_mo_t != ''){
                                $strOtherResponse = $strOtherResponse . '&mo_t=' . $str_mo_t;
                            }
                            if($str_mo_wpos != ''){
                                $strOtherResponse = $strOtherResponse . '&mo_wpos=' . $str_mo_wpos;
                            }
                            if($mo_tv > 0){
                                $strOtherResponse = $strOtherResponse . '&mo_tv=' . $mo_tv;
                            }
                            if($mo_m > 0){
                                $strOtherResponse = $strOtherResponse . '&mo_m=' . $mo_m;
                            }
                            if($lmi >= 0){
                                $strOtherResponse = $strOtherResponse . '&lmi=' . $lmi;
                            }
                            if($lmv >= 0){
                                $strOtherResponse = $strOtherResponse . '&lmv=' . $lmv;
                            }
                            if($rs_more >= 0){
                                $strOtherResponse = $strOtherResponse . '&rs_more=' . $rs_more;
                            }
                            if($str_s_mark != ''){
                                $strOtherResponse = $strOtherResponse . '&s_mark=' . $str_s_mark;
                            }
                            if($wmv >= 0){
                                $strOtherResponse = $strOtherResponse . '&wmv=' . $wmv;
                            }
                            if($gwm >= 0){
                                $strOtherResponse = $strOtherResponse . '&gwm=' . $gwm;
                            }
                            if($rs_m > 0){
                                $strOtherResponse = $strOtherResponse . '&rs_m=' . $rs_m;
                            }
                            if($rs_c > 0){
                                $strOtherResponse = $strOtherResponse . '&rs_c=' . $rs_c;
                            }
                            if($rs_p >= 0){
                                $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p;
                            }
                            if($rs_t > 0){
                                $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t;
                            }
                            if($str_stf != ''){
                                $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                            }
                            if($rs_p >= 0 || $rs_t > 0){
                                $strOtherResponse = $strOtherResponse . '&rs_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin');
                                if($rs_t > 0){
                                    $strOtherResponse = $strOtherResponse . '&w=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                                }else{
                                    $strOtherResponse = $strOtherResponse . '&w=0';
                                }
                            }else{
                                $strOtherResponse = $strOtherResponse . '&w=0';
                            }
                            $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                            if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                                $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                            }
                        }
                    }
                    $bet = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $bet = '50.00';
                }
                $spinType = 's';
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if($currentReelSet >= 0){
                    $strOtherResponse = $strOtherResponse . '&reel_set='. $currentReelSet;
                }
                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=3,8,4,8,11,10,6,10,5,7,8,9,6,9,8,7,4,5,3,4,3,8,4,8,11,10,6,10,5,7&balance='. $Balance .'&cfgs=5228&ver=3&mo_s=20&index=1&balance_cash='. $Balance .'&mo_v=1,2,3,4,5,6,8,10,12,15,20,25,50,100,250,500&def_sb=5,10,11,8,9,7&reel_set_size=11&def_sa=8,3,4,3,11,3&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0~0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0~1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"2958579",max_rnd_win:"20000"}}&wl_i=tbm~20000&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=8,3,4,3,11,3&sb=5,10,11,8,9,7&reel_set10=5,5,11,3,5,11,7,5,5,7,11,7,9,5,3,7,3,3,5,7,7,3,11,7,11,9,3,7,3,3,7,7,9,3,3,9,5,3,9,11,9,9,5,9,3,9,5,5,7,5,7,7,9,5,9,5,9,5,7,9,5,5,7,3,9,7,5,9,3,11,7,9,9,3,5,11,3,9,5,11,9,11,9,7,7,3,3,9,5,7,3,3,7,5,5,7,7,9,11,9~6,4,6,11,4,10,4,6,8,4,11,6,11,8,10,10,8,10,4,6,8,8,6,4,11,8,10,4,4,8,11,8,8,10,6,6,4,4,10,10,6,10,10,4,10,8~5,9,9,7,5,5,3,3,7,7,9,7,3,3,5,9,7,5,7,3,9,9,3,5,7~8,10,11,11,4,10,6,8,6,8,4,4,10,8,10,10,6,4,8,4,10,11,4,6,8,10,11,4,6,6,8,8,4,6,10,6,8,10,4,4,8,6,11,11,6,10,11,4,10,6,8,10,4,10,6,10,8,8,6,4,10,10,6,4,4,8,10,8,4,4,11~5,7,9,5,3,5,9,9,5,5,9,11,9,5,7,5,3,9,7,9,3,5,9,3,7,7,3,9,11,11,9,11,5,5,3,11,3,5,7,7,3,3,5,7,11,7,7,9,7,7,3,7,3,5,11,9,3,3,9,3,9,7,11,7,3,7,7,5,9,5,9,7,5,9,5,3,11,9,7,5,9,5~10,6,10,4,8,8,11,10,10,6,4,8,11,11,10,10,6,6,4,10,11,8,8,10,11,10,6,10,10,4,6,4,8,4,6,4,6,8,8,11,10,4,4,10,8,4,6,8,4,4,11,8,10,8,6,6,4,4,6&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=50.00&purInit_e=1&sh=5&wilds=2~0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0~1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1&bonuses=0&st=rect&c='.$bet.'&sw=6&sver=5&counter=2&paytable=0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,500,500,200,200,0,0,0,0,0,0,0;500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,200,200,50,50,0,0,0,0,0,0,0;300,300,300,300,300,300,300,300,300,300,300,300,300,300,300,300,300,300,300,100,100,40,40,0,0,0,0,0,0,0;240,240,240,240,240,240,240,240,240,240,240,240,240,240,240,240,240,240,240,40,40,30,30,0,0,0,0,0,0,0;200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,30,30,20,20,0,0,0,0,0,0,0;160,160,160,160,160,160,160,160,160,160,160,160,160,160,160,160,160,160,160,24,24,16,16,0,0,0,0,0,0,0;100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,20,20,10,10,0,0,0,0,0,0,0;80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,18,18,8,8,0,0,0,0,0,0,0;40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,15,15,5,5,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0&l=20&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=9,6,7,8,11,9,5,11,8,10,8,4,10,11,11,10,8,10,11,11,11,9,11,10,10,9,9,10,8,10,5,10,3,4,5,8,11,9,6,9,8,8,8,5,4,8,11,10,11,7,8,3,11,11,6,9,10,6,11,11,10,11,10,10,10,6,7,11,3,4,11,8,10,6,11,5,7,6,8,7,9,4,8,10,11,10~10,10,10,10,10,8,8,6,11,11,11,4,3,5,9,9,9,11,11,7,9,9~7,5,10,7,5,8,5,9,4,11,3,10,8,7,4,7,8,7,9,6,4,10,7,10,8,4,7,6,4,9,8,10,7,7,7,7,5,8,7,7,4,11,9,11,8,6,7,10,3,7,8,6,7,4,11,9,11,7,11,4,7,5,9,7,9,9,4,3,10,11,11,8,8,8,3,11,8,3,6,8,7,11,7,5,3,10,3,11,6,11,10,6,10,3,9,9,5,8,8,11,8,8,11,6,11,8,10,8,5,5,5,5,5,9,5,7,10,4,8,8,7,9,10,11,5,5,11,9,4,4,11,7,7,5,7,6,5,7,5,7,4,5,5,3,8,5,8~11,8,9,7,7,7,6,4,10,10,6,6,6,3,3,11,5,10,10,10,11,6,4,9,3,3,3,5,11,7,7,10~10,8,11,10,7,9,8,4,8,4,4,4,11,4,9,9,5,4,5,10,4,9,8,8,8,8,11,5,11,6,10,9,11,6,8,9,9,9,4,3,8,7,11,7,9,10,10,7,11,11,11,11,6,5,6,11,6,9,7,5,7,7,7,7,11,7,10,9,5,11,9,8,3,9,6~8,7,8,5,6,6,9,3,8,8,9,10,4,10,6,9,9,7,9,11,4,5,5,8,3,9,9,11,9,10,6,6,6,9,10,4,10,11,6,4,9,10,5,10,8,10,10,8,5,6,11,10,9,6,9,11,3,8,5,10,9,8,6,5,9,9,9,6,11,11,5,6,11,8,6,7,7,9,11,4,11,11,3,9,9,7,8,7,5,11,11,3,5,4,11,5,10,11,10,10,10,8,7,11,7,3,9,8,9,6,9,3,8,4,11,10,8,6,10,7,6,10,6,3,3,11,7,9,9,10,8,11,4,4,4,5,6,6,5,11,9,8,8,3,9,10,4,11,7,9,10,11,10,7,4,3,11,9,4,7,9,4,4,9,9,10,11,11,11,11,3,5,11,10,5,9,9,7,11,8,4,5,6,11,9,9,5,7,8,5,8,4,7,4,4,8,8,6,7,7,9&s='.$lastReelStr.'&reel_set2=3,4,10,3,8,8,11,8,11,9,7,8,8,5,11,11,11,8,11,6,9,11,10,11,8,10,10,7,10,4,6,6,8,8,8,8,5,9,5,9,10,11,11,4,10,6,10,7,10,11,4,9,10,10,10,11,10,8,10,10,11,9,11,6,6,11,5,9,9,11,11,7~20,8,10,20,6,10,10,3,4,3,4,4,4,4,10,11,11,10,10,9,9,11,6,9,11,10,10,10,7,5,9,6,8,8,10,20,5,6,11,9,9,9,7,4,10,3,9,10,6,4,9,11,9,5,11,11,11,8,11,3,8,9,6,9,8,10,10,8,11,5~7,3,20,8,8,8,6,9,8,7,9,5,5,5,10,4,4,5,7,7,7,11,8,11,5,7,10~10,6,4,4,11,7,4,7,5,6,5,11,9,7,11,3,10,9,4,6,10,8,8,8,3,7,8,5,9,4,9,11,7,9,11,4,11,6,8,11,3,3,8,9,7,9,10,3,3,3,11,8,11,10,10,7,6,3,3,9,7,9,9,10,6,9,6,10,10,9,11,11,4,7,7,7,10,4,8,10,10,11,11,7,6,10,6,4,10,9,10,4,9,10,7,7,11,11,7,6,6,6,9,7,10,3,9,6,10,7,7,9,10,9,11,11,6,7,4,10,11,5,11,7,9,9,9,11,6,5,6,11,10,8,5,4,10,6,11,8,9,4,9,7,11,10,5,3,10,11,10,10,10,5,11,8,10,4,10,9,5,4,9,3,11,10,10,11,4,3,7,11,7,5,10,5,11,11,11,11,9,3,10,7,6,3,11,11,9,10,5,11,9,4,5,11,11,3,11,10,3,5,11~8,5,8,4,11,9,11,9,10,10,11,7,6,3,10,20,8,11,7,6,4,11,6,9,9,7,11,20,4,10,11,9,6,10,20,8,11,10,4,3,8,8,8,6,8,4,6,9,11,8,5,11,8,11,11,9,5,9,6,8,9,20,11,6,8,8,20,11,8,11,9,5,7,8,9,4,8,4,11,7,5,11,3,11,9,9,9,9,10,10,8,9,10,11,10,9,7,5,10,8,7,6,9,5,4,7,5,3,5,6,6,10,11,7,10,4,9,9,6,7,6,6,9,10,11,4,10,5,9,8,11,11,11,10,10,6,9,9,6,20,5,8,5,11,9,9,10,11,11,9,11,7,9,5,7,11,6,9,11,8,9,7,11,7,8,9,11,5,10,11,9,7,9,9,10,5,5,5,7,7,10,5,8,3,5,8,7,3,11,11,9,8,8,5,3,10,4,9,5,8,4,4,20,20,9,11,9,10,10,9,5,10,9,5,11,6,7,11,20,10,7~4,11,11,8,3,6,6,6,5,11,8,8,10,9,9,9,9,11,9,5,9,9,8,10,10,10,5,4,5,6,3,10,4,4,4,9,9,7,3,11,9,8,8,8,11,6,7,7,10,10,11,11,11,9,8,8,7,6,10,6,4&reel_set1=11,11,6,10,9,11,10,7,5,7,11,8,3,11,10,11,7,4,11,7,7,8,5,4,6,8,7,10,3,11,5,9,11,6,5,6,11,7,11,10,11,6,3,11,8,11,4,6,7,10,11,3,4,6,10,6,11,8,11,10,9,7,9,9,3,11,8,8,10,8,11,7,6,4,11,11,7,10,8,5,4,10,11,6,3,9,10,11,8,9,6,10,4,4,11,8,10,6,8,8,9,11,9,8,5,8,11,9,11,10,5,9,8,10,9,6,9,11,10,10,9,5,6,10,7~7,9,10,5,5,10,10,8,4,11,9,10,8,11,9,5,6,10,10,4,7,4,8,10,11,6,8,7,9,3,7,6,9,10,4,8,8,10,11,9,11,4,6,9,10,11,7,7,10,11,7,11,6,11,4,5,6,6,9,6,8,10,9,10,9,8,10,9,3,9,8,5,3,7,10,5,11,8,9,8,11,6,9,9,7,6,11,11,7,8~9,8,4,11,7,10,4,9,11,7,10,3,7,7,11,7,3,9,8,3,10,7,9,11,9,5,10,9,5,11,5,10,10,8,9,11,10,8,4,8,7,10,6,7,8,8,10,5,6,11,8,8,11,11,7,9,6,7,9,4,5,7,8,5,4,5,11,7,8,4,6,7,11,7,11,11,9,7,8,8,11,10,7,10,8,7,3,9,10,11,7,10,8,11,7,11,8,10,7,9,5,7,9,11,7,8,7,11,7,11,9,10,5,4,10,11,10,6,5,11,5,11,9,10,9,11,7,7,4,6,10,10,4,7,4,3,10,7,7,9,5,4,8,7,4~10,10,4,6,11,8,4,4,9,3,9,4,4,11,7,10,5,6,9,7,5,5,11,8,4,10,8,7,3,6,5,10,4,4,3,4,10,3,3,11,11,8,7,10,11,6,4,9,9,6,11,8,11,9,5,9,8,9,6,10,6,7,9,7,9,8,10,11,11,10,4,5,11,9,11,5,9,6,3,9,3,8,8,8,9,4,6,5,9,9,10,6,11,8,7,11,11,10,8,3,10,6,7,5,10,4,10,9,11,10,11,10,11,4,10,5,4,8,11,11,5,10,7,10,9,10,11,11,3,9,10,11,7,7,11,10,6,4,11,7,7,11,10,7,8,11,7,9,4,11,3,10,11,11,4,9,9,10,3,7,4,9,9,11,11,5,8~9,8,10,4,10,10,8,9,8,10,11,8,7,8,8,11,10,7,6,10,5,11,6,8,9,6,4,7,11,10,4,9,6,11,11,3,9,6,9,9,5,11,11,5,9,9,6,6,11,9,9,11,11,10,7,11,4,7,8,7,5,5,11,4,9,11,7,8,5,5,10,5,3,7,11,7,6,10,7,3,9,10,8,10,8,4,10,7~4,9,3,6,11,7,3,5,6,11,11,9,5,9,11,7,9,8,9,9,9,7,9,6,10,8,9,3,9,10,4,11,10,7,8,11,9,6,10,5,8,10&reel_set4=11,9,11,9,6,4,6,10,8,11,4,11,11,11,10,10,6,10,10,9,8,10,7,11,7,11,8,8,8,11,10,11,11,5,6,3,11,7,10,5,8,10,10,10,9,10,9,9,8,8,4,3,8,6,5,11,8~10,9,8,5,4,4,4,6,8,10,9,10,8,10,10,10,20,10,20,3,11,9,9,9,9,9,7,11,11,3,11,11,11,6,4,11,6,4,10,5~11,5,6,10,7,7,8,8,8,6,11,3,20,7,7,3,4,5,5,5,8,8,7,8,9,20,10,7,7,7,9,4,5,9,5,11,11,10,4~7,4,9,6,6,6,4,5,7,20,20,10,10,10,8,6,10,3,9,7,7,7,11,11,10,10,9,3,3,3,11,11,6,3,4,9,9,9,7,11,5,10,9,10~8,11,11,3,4,10,10,9,11,11,10,5,11,8,8,8,8,10,11,4,8,5,9,9,11,11,6,11,4,6,9,9,9,5,9,9,8,8,5,9,8,8,4,11,7,4,6,7,7,7,6,5,5,9,7,7,8,3,7,5,9,5,7,11,11,11,11,7,20,20,6,9,10,9,7,10,7,10,10,9,11,5,5,5,11,6,5,9,8,10,10,11,10,9,8,4,7,9,20,3~9,8,3,8,6,9,4,6,6,6,11,3,10,7,6,8,7,8,10,9,9,9,3,9,7,9,11,10,7,5,5,10,10,10,10,9,11,4,11,10,6,9,10,4,4,4,8,8,11,9,9,5,10,6,4,8,8,8,11,5,9,3,5,9,4,4,6,11,11,11,8,11,8,7,6,5,11,8,7,9&purInit=[{bet:2000,type:"default"}]&reel_set3=11,8,11,11,6,8,9,7,11,7,11,9,10,8,10,7,9,9,11,11,11,5,11,11,6,8,6,10,6,5,8,11,4,10,9,11,8,8,11,10,8,8,8,4,11,10,6,6,11,10,4,10,9,8,7,8,11,10,9,11,10,8,10,10,10,4,11,5,10,11,3,4,9,7,9,8,11,3,5,5,10,10,3,6,10,10~8,10,8,3,20,9,8,6,5,9,11,3,6,4,8,6,5,10,5,8,10,8,4,4,4,11,9,7,10,3,9,9,3,4,11,8,10,6,5,7,4,6,11,20,9,5,10,10,6,10,10,10,6,10,10,5,6,11,9,9,8,10,9,9,10,8,9,5,9,3,9,9,10,10,4,9,9,9,20,9,11,11,10,10,11,4,4,10,3,11,10,9,6,10,11,8,8,10,11,7,10,9,11,11,11,8,6,11,7,9,11,6,20,3,10,8,5,9,8,10,11,3,8,10,4,20,4,11,20,11~4,3,5,7,7,10,4,9,8,8,8,8,4,8,6,11,3,7,20,5,5,11,5,5,5,20,9,9,11,7,5,11,5,10,8,7,7,7,7,3,10,9,10,4,8,8,11,7,6,6~6,11,10,3,10,3,11,9,6,6,6,9,8,10,5,7,4,10,7,5,10,10,10,4,11,9,3,5,9,10,11,10,7,7,7,10,4,9,3,11,10,10,11,10,7,3,3,3,9,11,9,10,4,11,7,6,20,9,9,9,6,20,11,9,6,5,9,7,4,4,4,4,5,3,7,11,10,6,8,4,7,11,11~11,7,11,11,7,5,7,9,8,8,9,9,4,8,8,11,6,11,11,7,11,4,7,4,4,4,11,8,4,10,5,11,5,6,8,9,7,4,11,3,9,9,7,8,8,7,8,7,10,5,8,8,8,6,11,9,7,5,10,9,5,7,7,9,10,4,4,8,11,9,8,5,11,11,8,4,5,9,9,9,6,11,9,9,8,11,11,7,11,7,8,10,7,10,10,8,4,9,10,11,9,10,9,6,10,10,10,11,10,4,9,4,11,3,9,10,9,11,10,11,4,3,11,10,4,5,7,9,9,11,6,7,7,7,5,9,11,9,4,9,9,8,8,11,6,6,9,10,8,3,7,6,9,6,4,11,11,10,11,11,11,8,9,7,10,6,8,5,5,10,9,9,10,7,3,9,11,5,5,8,5,9,11,10,9,3,3,3,11,10,9,5,3,6,11,3,7,10,7,7,8,6,10,10,7,10,9,6,9,11,6,5,5,5,5,5,6,5,9,6,6,8,9,7,8,10,7,10,10,8,11,11,10,4,5,11,11,9,10,4~10,8,9,5,9,11,6,6,6,7,10,10,11,9,3,4,9,9,9,9,5,6,9,6,6,11,10,10,10,9,9,10,4,7,5,6,8,4,4,4,5,3,6,8,8,10,4,8,8,8,7,11,11,8,11,4,5,11,11,11,7,3,9,8,11,10,8,9,7&reel_set6=11,7,10,10,11,6,10,10,4,7,10,9,11,4,9,11,11,8,10,8,4,11,3,11,6,7,4,10,11,6,9,3,10,3,10,11,11,3,9,11,8,7,11,4,6,8,5,11,4,5,10,4,8,9,8,11,9,7,8,8,11,9,11,5,6,11,9,9,7,7,11,3,9,6,5,11,11,8,7,10,7,5,11,8,10,8,10,10,3,7,8,7,6,8,5,8,6,11,4,6,9,6,11,6,10,8,11,6,8,9,6,10,9,6,10,11,10,11,11,5,7,10,11,5,9~8,9,11,10,11,11,4,10,11,5,6,7,11,10,5,6,4,3,9,8,10,5,10,10,4,6,9,6,9,6,7,7,10,9,3,10,10,6,8,8,9,8,10,9,8,11,4,7,9,4,7,8,9,10,6,8,9,9,10,10,5,11,8,7,11,10,9,11,7,6,5,6,9,7,9,8,6,10,11,8,11,9,7,8,3,5,9,7,11,11,6,4,11,7,10,8~7,11,11,9,9,3,5,3,5,9,5,5,9,7,9,7,9,7,7,9,5,9,9,7,5,11,3,9,11,5,9,3,5,3,7,11,7,11,9,9,9,5,9,9,11,5,9,5,7,7,9,7,11,3,7,3,5,5,9,11,9,9,7,5,7,11,3,9,9,7,5,5,7,9,7,9,9,3,7,7,9,5~11,10,4,11,8,4,10,6,11,10,8,10,8,10,11,8,4,8,11,11,11,6,8,10,10,10,10,6,10,11,10,8,8,10,8,10,10,4,6,6,6,6,6,4,10,8,8,4,10,8,4,6,8,8,10,6,8,8,4,4,4,8,8,8,8,10,8,8,6,10,8,10,8,6,8,11,4,11,4,11,4,6,8,11,6,11,10,6,6,6,11,8,10,8,10,6,10,6,11,10,8,4,11,10,10,4,10,8,4,6,8,10,6,10,11,6,11,4,8,11,8,11,6,6,8,8,10,8,10,10,10,10,10,4,10,8,6,6,6,8,6,11,8,8,8,8,11,8,4,11,10,6,8,6,6,6,6,6,8,4,8,8,11,4,10,11,10,6,6,10,10,10,11,10,11,10,6,8,6,10,8,10,6,6,8,8,6,8,4,10,10,10,10,11,4,10,4~7,9,9,5,9,5,9,5,11,5,5,11,9,9,9,3,7,9,7,5,9,9,9,5,7,5,7,7,7,9,5,7,7,5,7,7,9,5,7,9,7,5,9,7,3,5,3,7,11,3,7,5,9,9,5,5,9,9,9,5,9,9,11,3,11,9,11,11,5,9,9,7,9,3,5,9,9,9,9,3,5,7,9,7,11,5,5,7,9,7,3,7,11,7,9,11,5,7,9,11,7,7,5,3,9,9,5,9,7,5,9,5,5,11,9,11,9,7,11,9,7,9,7,9,3,7,5,7,3,9,3,9,11,7,7,5,11,3,9,11,3,5,3,9,11,7,3,9,7,5,7,9,11,9,3,5,9,3~11,8,6,11,8,8,4,10,6,10,11,6,8,8,10,8,8,10,8,4,10,6,10,10,4,10,4,11,10,6,10,11,11,10,6,4,8,8,8,6,6,8,6,10,10,11,4,8,11,6,6,10,8,6,6,8,6,10,11,4,10,10,10,10,8,8,8,8,6,8,11,10,4,8,6,11,11,11,8,8,4,6,10,4,10,6,11,10,6,8,6,11,10,6,4,8,8,6,10,11,6,10,4,8,4,4,8,11,8,6,8,8,8,10,10,10,8,10,8,4,11,11,6,10,11,6,10,6,6,10,4,10,10,10,10,10,10,8,8,10,11,10,8,11,6,6,4,10,6,11,8,10,6,8,10,4,8,8,6,8,8,11,10,8,6,4,4,10,6,4,10,11,11,10,10,10,8,4,6,8,4,6,6,4,6,11,8,8,10,8,6,8,6,10,6,6,8,10,11,11,8,10,8,4,8&reel_set5=11,5,3,9,7,7,8,10,5,6,8,7,10,11,8,11,10,9,9,7,11,6,11,11,5,8,6,7,9,8,6,10,10,5,11,9,11,6,11,6,7,11,6,8,4,5,5,11,11,5,10,7,11,10,9,7,7,3,11,8,11,9,11,5,11,6,11,11,4,10,6,4,6,5,9,11,7,8,9,11,4,6,9,11,8,8,3,3,10,3,10,9,10,11,11,10,8,11,4,11,10,11,9,9,6,10,11,8,11,7,3,5,8,4,10,10,6,9,10,10,11,11,7,10,10,9,4,6,11,8,8,4,6,8,9,8,11,4,11,6,8,7,7,10,10,7,8,6,11,10,10,5,7,3,10,9,11,6,11,10,10,8,10,4,7,4,9,11,3,9,7,8,4,5,9,8,7,8,11,9,11,4,11,11,8,6,11,6,10,11,6,9,5,10,11,8,6,3,9,10,6~9,9,6,3,11,11,7,4,11,5,10,11,8,9,9,5,10,8,10,8,7,4,7,6,10,6~11,10,6,6,6,8,8,4,6,10,10,10,8,8,6,6,10,6,8,8,6,8,11,6,11,11,8,4,8,6,4,10,8,6,6,4,4,6,4,4,10,6,8,8,8,8,8,10,11,10,10,11,11,11,6,8,6,8,11,8,10,8,11,6,8,10,10,4,8,4,10,4,11,10,11,8,6,10,8,6,10,10,11,4,6,8,6,6,10,4,10,10,10,4,4,10,10,10,8,8,11,10,10,10,6,10,8,6,6,8,8,4,6,8,10,8,6,11,8,10,10,4,11,11,10,6,8,8,6,10,10,8,8,4,11,11,10,8,10~9,5,7,11,9,7,3,9,9,9,7,3,9,9,5,5,11,5,7,9~6,6,4,11,4,8,10,10,10,4,11,4,11,10,8,6,4,8,10,8,10,8,8,6,10,8,6,10,10,10,6,6,10,8,11,4,11,10,4,6,8,6,8,11,6,8,10,10,4,8,6,6,10,6,8,8,8,8,4,10,8,11,4,11,8,10,6,10,6,10,11,10,6,6,11,4,8,8,8,10,11,6,6,4,6,8,10,10,4,11,8,8,11,4,10,8,11,8,10,10,8,4,10,10,8,6,6,6,11,6,4,4,8,10,10,10,6,11,6,6,8,8,10,6,10,8,10,6,10,6,8,6,8,10,8,10,11,10,8,8,10,10,8,11,10,10,11,8,8,8,11,4,10,8,8,11,10,10,10,11,8,6,6,6,6,11,6,8,8,4,11,4,4~11,7,7,3,9,3,11,7,7,9,7,5,5,9,9,9,9,3,5,9,7,9,7,11,5,9,3,5,9,5,5,9,9,11&reel_set8=12,12,12,12,12,12,12,12,12,12,12~12,12,12,12,12,12,12,12,12,12,12~12,12,12,12,12,12,12,12,12,12,12~12,12,12,12,12,12,12,12,12,12,12~12,12,12,12,12,12,12,12,12,12,12~12,12,12,12,12,12,12,12,12,12,12&reel_set7=8,9,11,5,8,5,11,11,5,10,8,10,8,6,11,3,8,10,10,7,9,6,11,5,9,6,11,4,3,3,5,11,8,8,11,11,11,11,11,8,7,6,10,11,9,6,10,10,8,10,4,4,11,8,10,10,6,10,9,8,4,9,11,5,5,6,11,10,10,8,10,4,8,8,8,6,10,7,11,9,10,4,10,10,11,4,9,10,11,6,10,7,3,7,6,8,9,10,9,11,9,9,8,10,11,11,8,5,9,4,10,10,10,11,11,6,8,7,11,7,10,11,8,11,9,11,4,5,11,7,11,10,10,11,3,11,9,10,6,8,7,10,11,6,8,9,11,8,11~9,3,10,10,6,11,10,10,10,8,10,5,7,4,6,10,11,11,11,8,5,11,3,8,10,8,9,9,9,9,4,11,9,11,10,9,6,9~8,11,7,11,7,11,8,7,11,9,7,4,4,3,7,5,8,4,5,7,6,7,4,6,4,10,7,8,5,6,5,8,10,8,7,8,7,7,7,3,11,5,8,9,5,7,9,11,4,10,5,8,9,5,6,8,7,5,9,8,11,7,5,9,8,11,10,9,3,4,11,4,8,6,10,9,8,8,8,6,9,7,4,7,10,11,7,8,4,9,6,8,7,11,7,8,5,7,4,9,6,7,4,9,3,7,10,8,3,3,11,7,4,7,4,9,5,5,5,10,10,11,7,11,3,10,5,5,3,11,8,8,5,10,5,3,10,5,5,11,3,5,7,6,8,9,8,11,5,10,10,8,7,11,6,7,11~10,10,4,9,11,7,10,6,4,4,9,7,7,7,11,3,11,8,7,11,10,11,3,5,7,11,10,6,6,6,6,5,5,9,6,11,5,9,3,10,3,4,9,10,10,10,7,7,8,9,6,10,11,4,10,7,11,9,9,3,3,3,7,3,3,10,5,11,10,11,6,10,11,4,5,6~8,9,11,4,6,11,11,4,7,7,10,4,8,10,3,10,9,9,4,4,4,11,10,6,9,8,9,9,3,5,10,11,5,9,7,8,3,7,5,11,8,8,8,6,5,6,5,9,9,4,11,10,7,4,4,8,8,4,11,10,8,5,5,9,9,9,11,8,9,6,11,6,7,11,9,11,10,10,8,7,10,11,7,11,5,11,11,11,7,8,8,6,5,4,10,6,9,6,7,10,9,11,5,8,7,10,9,7,7,7,7,11,9,3,9,9,11,10,8,9,6,11,4,5,4,10,9,11,11,7,6~5,11,11,5,11,4,11,11,10,9,10,9,9,5,4,5,4,5,3,6,6,6,3,10,11,4,9,9,8,6,3,4,9,5,11,4,9,3,7,9,10,7,6,9,9,9,11,9,10,3,4,8,6,7,9,8,9,10,9,8,11,7,5,10,8,4,11,10,10,10,11,8,8,7,10,4,7,10,8,7,8,6,10,8,4,11,6,5,9,11,9,4,4,4,8,5,9,10,7,11,8,10,11,9,9,3,7,6,7,8,5,11,9,5,7,11,11,11,6,4,6,5,10,6,7,10,6,9,8,11,8,3,6,11,8,9,3,9,9,6&reel_set9=10,4,6,6,8,4,10,6,11,10,4,4,11,8,4,10,6,8,10,8~11,7,5,9,7,7,5,3,11,5,5,9,3,9,7,11,11,3,5,7,9,3,9,11,9,3,7,7,3,5,7,3,7,9,9,5,7,11,9,3,9,11,5,3,7,9,3,9,5,7,5,5,9,5,5,3,3,7~11,10,8,4,4,6,8,4,4,6,10,10,8,6,10,11,6,10,4,8~3,9,5,5,7,7,9,5,7,5,9,9,5,3,5,7,5,3,7,5,9,9,3,7,9,7,9,3,7,3,7,7,3,3,5,7,3,9~10,10,8,4,6,8,6,4,6,10,4,10,8,11,8,10,4,4,6,6,8,11,4,4,10,10,11,11,4,11,8,10,4,6,10,6,8,10,4,8,4,10,6,8,6~7,5,11,11,3,7,7,9,7,3,7,3,3,11,9,11,5,5,9,5,9,5,5,3,11,3,9,5,3,7,9,5,7,5,7,9,9,7,11,5,9,9,11,9,7,3,5,7,5,5,9,3,3,9,5,7,7,3,3,9,7&total_bet_min=10.00';
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
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    $slotEvent['slotEvent'] = 'respin';
                }
                $isTumb = false;
                if($slotEvent['slotEvent'] != 'respin' && $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') >= 0){
                    $isTumb = true;
                }
                $lines = $slotEvent['slotLines'];
                $betline = $slotEvent['slotBet'];
                if( $slotEvent['slotEvent'] == 'doSpin' || $slotEvent['slotEvent'] == 'respin' ) 
                {
                    if( $lines <= 0 || $betline <= 0.0001 ) 
                    {
                        $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"invalid bet state"}';
                        exit( $response );
                    }
                    if( $slotEvent['slotEvent'] == 'doSpin' && $slotSettings->GetBalance() < ($lines * $betline)  && $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') < 0) 
                    {
                        $balance_cash = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                        if(!isset($balance_cash)){
                            $balance_cash = $slotSettings->GetBalance();
                        }
                        $response = 'nomoney=1&balance='. $balance_cash .'&error_type=i&index='.$slotEvent['index'].'&balance_cash='. $balance_cash .'&balance_bonus=0.00&na=s&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&ext_code=SystemError&sver=5&counter='. ((int)$slotEvent['counter'] + 1);
                        exit( $response );
                    }
                    if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1  < $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame')) && $slotEvent['slotEvent'] == 'respin' ) 
                    {
                        if($isTumb == false){
                            $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"invalid bonus state"}';
                            exit( $response );
                        }
                    }
                    if($slotEvent['slotEvent'] == 'respin'){
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
                if($pur >= 0 && $slotEvent['slotEvent'] != 'respin'){
                    $allBet = $allBet * 100;
                }
                $tumbAndFreeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'respin' || $isTumb == true){
                    if($slotEvent['slotEvent'] == 'respin' && $isTumb == false){
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    }
                    $leftFreeGames = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') - $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame'); 
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
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
                $winLines = [];
                $bonusMpl = 1;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $str_trail = '';
                $str_mo = 1;
                $str_mo_t = '';
                $mo_tv = -1;
                $str_mo_wpos = '';
                $mo_m = -1;
                $str_initreel = '';
                $currentReelSet = 0;
                $lmi = -1;
                $lmv = -1;
                $rs_more = -1;
                $str_s_mark = '';
                $wmv = -1;
                $gwm = -1;
                $str_stf = '';
                $rs_m = -1;
                $rs_c = -1;
                $rs_p = -1;
                $rs_t = -1;
                $subScatterReel = null;
                if($slotEvent['slotEvent'] == 'respin' || $isTumb == true){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_initreel = $stack['initReel'];
                    $currentReelSet = $stack['reel_set'];
                    $str_trail = $stack['trail'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $mo_tv = $stack['mo_tv'];
                    $str_mo_wpos = $stack['mo_wpos'];
                    $mo_m = $stack['mo_m'];
                    $lmi = $stack['lmi'];
                    $lmv = $stack['lmv'];
                    $rs_more = $stack['rs_more'];
                    $str_s_mark = $stack['s_mark'];
                    $wmv = $stack['wmv'];
                    $gwm = $stack['gwm'];
                    $str_stf = $stack['stf'];
                    $rs_m = $stack['rs_m'];
                    $rs_c = $stack['rs_c'];
                    $rs_p = $stack['rs_p'];
                    $rs_t = $stack['rs_t'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $pur, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $lastReel = explode(',', $stack[0]['reel']);
                    $str_initreel = $stack[0]['initReel'];
                    $currentReelSet = $stack[0]['reel_set'];
                    $str_trail = $stack[0]['trail'];
                    $str_mo = $stack[0]['mo'];
                    $str_mo_t = $stack[0]['mo_t'];
                    $mo_tv = $stack[0]['mo_tv'];
                    $str_mo_wpos = $stack[0]['mo_wpos'];
                    $mo_m = $stack[0]['mo_m'];
                    $lmi = $stack[0]['lmi'];
                    $lmv = $stack[0]['lmv'];
                    $rs_more = $stack[0]['rs_more'];
                    $str_s_mark = $stack[0]['s_mark'];
                    $wmv = $stack[0]['wmv'];
                    $gwm = $stack[0]['gwm'];
                    $str_stf = $stack[0]['stf'];
                    $rs_m = $stack[0]['rs_m'];
                    $rs_c = $stack[0]['rs_c'];
                    $rs_p = $stack[0]['rs_p'];
                    $rs_t = $stack[0]['rs_t'];
                }
                
                $reels = [];
                for($i = 0; $i < 6; $i++){
                    $reels[$i] = [];
                    for($j = 0; $j < 5; $j++){
                        $reels[$i][$j] = $lastReel[$j * 6 + $i];
                    }
                }
                for($j = 3; $j < $money; $j++){
                    $arr_symbols = [];
                    for( $k = 0; $k < 5; $k++ ) 
                    {
                        for($r = 0; $r < 6; $r++){ 
                            if( $reels[$r][$k] == $j) 
                            {                                
                                array_push($arr_symbols, $r + $k * 6);
                            }
                        }
                    }                      
                    $winLines[$j] = $arr_symbols;
                }
                $winlineindex = 0;
                if($gwm <= 0){
                    $gwm = 1;
                }
                for($r = 3; $r < $money; $r++){
                    $winLine = $winLines[$r];
                    $winLineMoney = $slotSettings->Paytable[$r][count($winLine)] * $betline * $gwm;
                    if($winLineMoney > 0){
                        $isNewTumb = true;
                        $strWinLine = $strWinLine . '&l'. $winlineindex.'='.$winlineindex.'~'.$winLineMoney . '~' . implode('~', $winLine);
                        $winlineindex++;
                        $totalWin += $winLineMoney;
                    }
                } 
                if($slotEvent['slotEvent'] == 'respin' && $rs_t > 0){
                    if($mo_tv > 0){
                        $totalWin = $mo_tv * $betline * $lines;
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
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                if($slotEvent['slotEvent'] != 'respin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') + $totalWin);
                }
                $strOtherResponse = '';
                if($rs_p >= 0){
                    if($rs_p == 0){                        
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    }
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', $rs_p);
                    $isState = false;
                    $spinType = 's';
                    if($slotEvent['slotEvent'] != 'respin' && $rs_c == 1 && $rs_m == 3){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 3);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    }else if($slotEvent['slotEvent'] == 'respin'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $rs_m);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $rs_c);
                    }       
                    $strOtherResponse = '&rs_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin');
                }else if($rs_t > 0){
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $isState = true;
                    if($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') > 0){
                        $spinType = 'c';
                    }                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', -1);
                    $strOtherResponse = '&rs_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin');
                }
                $strLastReel = implode(',', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                if($str_initreel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initreel;
                }
                if($str_trail != ''){
                    $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                }
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo;
                }
                if($str_mo_t != ''){
                    $strOtherResponse = $strOtherResponse . '&mo_t=' . $str_mo_t;
                }
                if($str_mo_wpos != ''){
                    $strOtherResponse = $strOtherResponse . '&mo_wpos=' . $str_mo_wpos;
                }
                if($mo_tv > 0){
                    $strOtherResponse = $strOtherResponse . '&mo_tv=' . $mo_tv;
                }
                if($mo_m > 0){
                    $strOtherResponse = $strOtherResponse . '&mo_m=' . $mo_m;
                }
                if($lmi >= 0){
                    $strOtherResponse = $strOtherResponse . '&lmi=' . $lmi;
                }
                if($lmv >= 0){
                    $strOtherResponse = $strOtherResponse . '&lmv=' . $lmv;
                }
                if($rs_more >= 0){
                    $strOtherResponse = $strOtherResponse . '&rs_more=' . $rs_more;
                }
                if($str_s_mark != ''){
                    $strOtherResponse = $strOtherResponse . '&s_mark=' . $str_s_mark;
                }
                if($wmv >= 0){
                    $strOtherResponse = $strOtherResponse . '&wmv=' . $wmv;
                }
                if($gwm >= 0){
                    $strOtherResponse = $strOtherResponse . '&gwm=' . $gwm;
                }
                if($rs_m > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_m=' . $rs_m;
                }
                if($rs_c > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_c=' . $rs_c;
                }
                if($rs_p >= 0){
                    $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p;
                }
                if($rs_t > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t;
                }
                if($str_stf != ''){
                    $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                }
                if($slotEvent['slotEvent'] == 'respin'){
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                    }
                }else{
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&purtr=1&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                    }
                }

                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse . $strWinLine .'&balance='.$Balance. '&index='.$slotEvent['index'].'&reel_set='. $currentReelSet .'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&stime=' . floor(microtime(true) * 1000).'&sh=5&st=rect&c='.$betline.'&sw=6&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&sa=8,3,4,3,11,3&sb=5,10,11,8,9,7&l=20&w='.$totalWin.'&s=' . $strLastReel;
                
                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog); 
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------
                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"TumbWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"TumbleState":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState')  . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') .',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($slotEvent['slotEvent'] == 'respin' && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
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

<?php 
namespace VanguardLTE\Games\FruitPartyPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [5,9,3,7,7,9,9,9,3,6,6,5,6,9,9,3,7,7,9,9,3,3,6,6,5,6,4,3,3,7,7,9,9,4,6,6,6,5,6,4,5,7,7,7,9,9,4,3,6]);
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
                    $bet = '50.00';
                }
                $spinType = 's';
                $arr_g = null;
                $moneyWin = 0;
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if(isset($stack)){
                    $str_initReel = $stack['initreel'];
                    $currentReelSet = $stack['reel_set'];
                    $fsmore = $stack['fsmore'];
                    $fsmax = $stack['fsmax'];
                    $str_slm_lmv = $stack['slm_lmv'];
                    $str_slm_lmi= $stack['slm_lmi'];
                    $str_slm_mp= $stack['slm_mp'];
                    $str_slm_mv= $stack['slm_mv'];
                    $str_tmb = $stack['tmb'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $strWinLine = $stack['win_line'];
                    
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                    }
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($str_slm_lmv != ''){
                        $strOtherResponse = $strOtherResponse . '&slm_lmi='. $str_slm_lmi . '&slm_lmv='. $str_slm_lmv;
                    }
                    if($str_slm_mv!= ''){
                        $strOtherResponse = $strOtherResponse . '&slm_mv=' . $str_slm_mv . '&slm_mp=' . $str_slm_mp;
                    }
                    if($str_tmb != ''){
                        $strOtherResponse = $strOtherResponse . '&tmb=' . $str_tmb;
                    }
                    if($rs_p >= 0){
                        $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p . '&rs_c=' . $rs_c . '&rs_m=' . $rs_m. '&rs=mc&tmb_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin');
                    }
                    if($rs_t > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t . '&tmb_res=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') . '&tmb_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin');
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
                            $strOtherResponse = $strOtherResponse . '&fs_total='.($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 1).'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsend_total=1&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        }
                        else
                        {
                            $fs = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                            if($rs_p >= 0){
                                $fs = $fs - 1;
                            }
                            $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $fs .'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&w=0.00';
                        }
                        if($fsmore > 0){
                            $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                        }     
                    }
                }
                
                $Balance = $slotSettings->GetBalance();  
                $response = 'def_s=5,9,3,7,7,9,9,9,3,6,6,5,6,9,9,3,7,7,9,9,3,3,6,6,5,6,4,3,3,7,7,9,9,4,6,6,6,5,6,4,5,7,7,7,9,9,4,3,6&balance='. $Balance .'&cfgs=1&ver=2&index=1&balance_cash='. $Balance .'&def_sb=6,5,6,4,5,9,7&reel_set_size=5&def_sa=4,9,3,6,6,5,5&reel_set=' .$currentReelSet. '&balance_bonus=0.00&na='.$spinType.'&scatters=1~0,0,0,0,0,0,0~10,10,10,10,10,0,0~1,1,1,1,1,1,1&gmb=0,0,0&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"88817",max_rnd_win:"5000"}}&wl_i=tbm~5000&stime=1631107107339&sa=4,9,3,6,6,5,5&sb=6,5,6,4,5,9,7&sc='. implode(',', $slotSettings->Bet) .'&defc=100.00&sh=7&wilds=2~0,0,0,0,0,0,0~1,1,1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet. $strOtherResponse .'&sver=5&counter=2&paytable=0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,1400,700,300,150,100,50,40,35,30,20,0,0,0,0;2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,1200,600,250,120,80,40,30,25,20,15,0,0,0,0;1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1000,500,200,90,60,30,25,20,15,10,0,0,0,0;1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,800,400,100,60,40,25,20,15,10,8,0,0,0,0;1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,600,300,70,50,30,20,15,10,8,6,0,0,0,0;800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,400,200,60,40,25,15,10,8,6,5,0,0,0,0;400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,200,100,50,30,20,10,8,6,5,4,0,0,0,0&l=20&rtp=96.47&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=9,9,3,3,6,7,6,7,5,1,6,9,4,4,5,3,9,8,8,4,6,9,9,5,5,8,8,7,7,9,6,8,7,5,5,9,8,7,9,6,4,6,9,8,7,6,6,9,9,8,6,9,8,9,4,9,9,7,6,9,9,8,3,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,8,4,5,3,7,8,8,7,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,8,9,8,8,4,7,9,7,6,9,9,8,5,3,7,7,8,6,9,7,6,8,5,5,4,5,3,7,7,8,4,5,7,4,8,8,6,6,6~9,9,3,3,6,7,6,7,5,9,6,9,4,4,5,3,1,8,8,4,6,9,9,5,5,8,8,7,7,9,6,8,7,5,5,9,8,7,9,6,4,6,9,8,7,6,6,9,9,8,6,9,8,9,4,9,9,7,6,9,9,8,3,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,8,4,5,3,7,8,8,7,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,8,9,8,8,4,7,9,7,6,9,9,8,5,3,7,7,8,6,9,7,6,8,5,5,4,5,3,7,7,8,4,5,7,4,8,8,6,6,6~9,9,3,3,6,7,6,7,5,9,6,9,4,4,5,3,9,1,8,4,6,9,9,5,5,8,8,7,7,9,6,8,7,5,5,9,8,7,9,6,4,6,9,8,7,6,6,9,9,8,6,9,8,9,4,9,9,7,6,9,9,8,3,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,8,4,5,3,7,8,8,7,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,8,3,3,8,9,8,8,4,7,9,7,6,9,9,8,5,3,7,7,8,6,9,7,6,8,5,5,4,5,3,7,7,8,4,5,7,4,8,8,6,6,6~9,9,3,3,6,7,6,7,5,9,6,9,4,4,5,3,9,8,8,4,6,9,1,5,5,8,8,7,7,9,6,8,7,5,5,9,8,7,9,6,4,6,9,8,7,6,6,9,9,8,6,9,8,9,4,9,9,7,6,9,9,8,3,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,8,4,5,3,7,8,8,7,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,8,9,8,8,4,7,9,7,6,9,9,8,5,3,7,7,8,6,9,7,6,8,5,5,4,5,3,7,7,8,4,5,7,4,8,8,6,6,6~9,9,3,3,6,7,6,7,5,9,6,9,4,4,5,3,1,8,8,4,6,9,9,5,5,8,8,7,7,9,6,8,7,5,5,9,8,7,9,6,4,6,9,8,7,6,6,9,9,8,6,9,8,9,4,9,9,7,6,9,9,8,3,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,8,4,5,3,7,8,8,7,8,4,5,7,1,8,8,5,4,4,3,9,9,7,8,9,3,3,8,9,8,8,4,7,9,7,6,9,9,8,5,3,7,7,8,6,9,7,6,8,5,5,4,5,3,7,7,8,4,5,7,4,8,8~9,9,3,3,6,7,6,7,5,9,6,9,4,4,5,3,9,8,8,1,6,9,9,5,5,8,8,7,7,9,6,8,7,5,5,9,8,7,9,6,4,6,9,8,7,6,6,9,9,8,6,9,8,9,4,9,9,7,6,9,9,8,3,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,8,4,5,3,7,8,8,7,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,8,9,8,8,4,7,9,7,6,9,9,8,5,3,7,7,8,6,9,7,6,8,5,5,4,5,3,7,7,8,4,5,7,4,8,8~9,9,3,3,6,7,6,7,5,9,5,9,4,4,5,3,1,8,8,4,6,9,9,5,5,8,8,7,7,9,6,8,7,5,5,9,8,7,9,6,4,6,9,8,7,6,6,9,9,8,6,9,8,9,4,9,9,7,6,9,9,8,3,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,8,4,5,3,7,8,8,7,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,8,9,8,8,4,7,9,7,6,9,9,8,5,3,7,7,8,6,9,7,6,8,5,5,4,5,3,7,7,8,4,5,7,4,8,8&s='.$lastReelStr.'&reel_set2=3,7,6,5,8,9,4,5,6,8,4,3,7,9,3,7,8,5,6,9,4,5,6,8,7,3,4,9,3,7,6,5,8,9,4,5,6,8,4,3,7,9,3,7~3,7,6,5,8,9,4,5,6,8,4,3,7,9,3,7,8,5,6,9,4,5,6,8,7,3,4,9,3,7,6,5,8,9,4,5,6,8,4,3,7,9,3,7~3,7,6,5,8,9,4,5,6,8,4,3,7,9,3,7,8,5,6,9,4,5,6,8,7,3,4,9,3,7,6,5,8,9,4,5,6,8,4,3,7,9,3,7~3,7,6,5,8,9,4,5,6,8,4,3,7,9,3,7,8,5,6,9,4,5,6,8,7,3,4,9,3,7,6,5,8,9,4,5,6,8,4,3,7,9,3,7~3,7,6,5,8,9,4,5,6,8,4,3,7,9,3,7,8,5,6,9,4,5,6,8,7,3,4,9,3,7,6,5,8,9,4,5,6,8,4,3,7,9,3,7~3,7,6,5,8,9,4,5,6,8,4,3,7,9,3,7,8,5,6,9,4,5,6,8,7,3,4,9,3,7,6,5,8,9,4,5,6,8,4,3,7,9,3,7~3,7,6,5,8,9,4,5,6,8,4,3,7,9,3,7,8,5,6,9,4,5,6,8,7,3,4,9,3,7,6,5,8,9,4,5,6,8,4,3,7,9,3,7&t=stack&reel_set1=9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,9,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,4,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,9,8,8,4,7,7,7,6,9,9,8,3,7,7,8,6,6,9,7,6,5,5,5,7,6,6,6,8,4,4,4~9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,9,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,4,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,9,8,8,4,7,7,7,6,9,9,8,3,7,7,8,6,6,9,7,6,5,5,5,7,6,6,6,8,4,4,4~9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,9,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,4,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,9,8,8,4,7,7,7,6,9,9,8,3,7,7,8,6,6,9,7,6,5,5,5,7,6,6,6,8,4,4,4~9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,9,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,4,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,9,8,8,4,7,7,7,6,9,9,8,3,7,7,8,6,6,9,7,6,5,5,5,7,6,6,6,8,4,4,4~9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,9,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,5,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,9,8,8,4,7,7,7,6,9,9,8,3,7,7,8,6,6,9,7,6,5,5,5,7,6,6,6,8,4,4,4~9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,9,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,5,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,3,8,8,4,7,7,7,6,9,9,8,3,7,7,8,6,6,9,7,6,5,5,5,7,6,6,6,8,4,4,4~9,9,3,3,6,7,6,7,5,9,5,6,9,4,3,3,9,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,5,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,3,8,8,4,7,7,7,6,9,9,8,3,7,7,8,6,6,9,7,6,5,5,5,7,6,6,6,8,4,4,4&reel_set4=9,9,3,3,6,7,6,7,5,1,6,6,9,4,3,3,9,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,4,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,9,8,8,4,7,7,7,6,9,9,8,5,3,7,7,8,6,9,7,6,5,5,5,9,6,6,6~9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,1,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,4,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,9,8,8,4,7,7,7,6,9,9,8,5,3,7,7,8,6,9,7,6,5,5,5,9,6,6,6~9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,9,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,4,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,1,3,3,3,9,8,8,4,7,7,7,6,9,9,8,5,3,7,7,8,6,9,7,6,5,5,5,9,6,6,6~9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,9,8,8,4,6,9,1,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,4,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,9,8,8,4,7,7,7,6,9,9,8,5,3,7,7,8,6,9,7,6,5,5,5,9,6,6,6~9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,1,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,5,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,9,8,8,4,7,7,7,6,9,9,8,5,3,7,7,8,6,9,7,6,5,5,5,9,6,6,6,5,4,4,4~9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,9,8,8,1,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,5,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,3,8,8,4,7,7,7,6,9,9,8,5,3,7,7,8,6,9,7,6,5,5,5,9,6,6,6,5,4,4,4~9,9,3,3,6,7,6,7,5,9,5,6,9,4,3,3,1,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,5,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,3,8,8,4,7,7,7,6,9,9,8,5,3,7,7,8,6,9,7,6,5,5,5,9,6,6,6,5,4,4,4&purInit=[{type:"fs",bet:2000,fs_count:10}]&reel_set3=9,9,3,3,6,7,6,7,5,1,6,6,9,4,3,3,9,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,4,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,9,8,8,4,7,7,7,6,9,9,8,5,3,7,7,8,6,9,7,6,5,5,5,7,6,6,6~9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,1,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,4,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,9,8,8,4,7,7,7,6,9,9,8,5,3,7,7,8,6,9,7,6,5,5,5,7,6,6,6~9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,9,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,4,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,1,3,3,3,9,8,8,4,7,7,7,6,9,9,8,5,3,7,7,8,6,9,7,6,5,5,5,7,6,6,6~9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,9,8,8,4,6,9,1,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,4,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,9,8,8,4,7,7,7,6,9,9,8,5,3,7,7,8,6,9,7,6,5,5,5,7,6,6,6~9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,1,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,5,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,9,8,8,4,7,7,7,6,9,9,8,5,3,7,7,8,6,9,7,6,5,5,5,7,6,6,6,5,4,4,4~9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,9,8,8,1,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,5,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,3,8,8,4,7,7,7,6,9,9,8,5,3,7,7,8,6,9,7,6,5,5,5,7,6,6,6,5,4,4,4~9,9,3,3,6,7,6,7,5,9,5,6,9,4,3,3,1,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,5,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,3,8,8,4,7,7,7,6,9,9,8,5,3,7,7,8,6,9,7,6,5,5,5,7,6,6,6,5,4,4,4&total_bet_min=10.00';
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
                $pur_muls = [100];
                $allBet = $betline * $lines;
                if($pur >= 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $allBet * $pur_muls[$pur];
                }
                $tumbAndFreeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'freespin' || $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') >= 0){
                    if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') < 0){
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
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
                $reels = [];
                $fsmore = 0;
                $fsmax = 0;
                $str_tmb = '';
                $str_slm_lmv = '';
                $str_slm_lmi = '';
                $str_slm_mp = '';
                $str_slm_mv = '';
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
                    $currentReelSet = $stack['reel_set'];
                    $fsmore = $stack['fsmore'];
                    $fsmax = $stack['fsmax'];
                    $str_slm_lmv = $stack['slm_lmv'];
                    $str_slm_lmi= $stack['slm_lmi'];
                    $str_slm_mp= $stack['slm_mp'];
                    $str_slm_mv= $stack['slm_mv'];
                    $str_tmb = $stack['tmb'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
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
                    $fsmax = $stack[0]['fsmax'];
                    $str_slm_lmv = $stack[0]['slm_lmv'];
                    $str_slm_lmi= $stack[0]['slm_lmi'];
                    $str_slm_mp= $stack[0]['slm_mp'];
                    $str_slm_mv= $stack[0]['slm_mv'];
                    $str_tmb = $stack[0]['tmb'];
                    $rs_p = $stack[0]['rs_p'];
                    $rs_c = $stack[0]['rs_c'];
                    $rs_m = $stack[0]['rs_m'];
                    $rs_t = $stack[0]['rs_t'];
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
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 7; $i++){
                    $reelA[$i] = mt_rand(5, 10);
                    $reelB[$i] = mt_rand(5, 10);
                }
                $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
                $strLastReel = implode(',', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $rs_p);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                $strOtherResponse = '';
                if($rs_p >= 0){
                    $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p . '&rs_c=' . $rs_c . '&rs_m=' . $rs_m. '&rs=mc&tmb_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin');
                    $spinType = 's';
                    $isState = false;
                }else{
                    
                    if($fsmax > 0 && $slotEvent['slotEvent'] != 'freespin'){
                        // $freeSpins = [0,0,0,0,10,15,20];
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $fsmax);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                    }else if($fsmore > 0){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                    }
                }
                if($rs_t > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t . '&tmb_res=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin'). '&tmb_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin');
                    if($slotEvent['slotEvent'] != 'freespin' && $fsmax == 0){
                        $spinType = 'c';
                    }
                }
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $spinType = 's';
                    $isEnd = false;
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                    {
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        if($rs_p < 0){
                            $strOtherResponse = $strOtherResponse . '&fsend_total=1';
                            $spinType = 'c';
                            $isEnd = true;
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
                    if($fsmax > 0 && $rs_p < 0){
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
                if($str_slm_lmv != ''){
                    $strOtherResponse = $strOtherResponse . '&slm_lmi='. $str_slm_lmi . '&slm_lmv='. $str_slm_lmv;
                }
                if($str_slm_mv!= ''){
                    $strOtherResponse = $strOtherResponse . '&slm_mv=' . $str_slm_mv . '&slm_mp=' . $str_slm_mp;
                }
                if($str_tmb != ''){
                    $strOtherResponse = $strOtherResponse . '&tmb=' . $str_tmb;
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse  .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance . '&reel_set='. $currentReelSet.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=7&st=rect&c='.$betline.'&sw=7&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&w='.$totalWin.'&s=' . $strLastReel;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) && $rs_p < 0) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if($slotEvent['slotEvent'] != 'freespin' && ($rs_p == 0 || ($fsmax > 0 && $rs_t <= 0 && $rs_p < 0))){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                }
                if( $slotEvent['slotEvent'] != 'freespin' && $fsmax > 0) 
                {                    
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
                if($slotEvent['slotEvent'] == 'freespin' && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * $pur_muls[$slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin')];
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

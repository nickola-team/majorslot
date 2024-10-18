<?php 
namespace VanguardLTE\Games\TheDogHouseMegawaysPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->SetGameData($slotSettings->slotId . 'ScatterCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FSOPT', -1);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [8,3,12,3,12,11,8,12,3,12,3,6,10,12,3,12,3,6,10,7,13,7,13,12,14,7,14,7,13,15,14,11,14,14,8,14,14,9,14,14,8,14]);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $str_mo = '';
                $strOtherResponse = '';
                $stack = null;
                
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', $lastEvent->serverResponse->BonusState);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ScatterCount', $lastEvent->serverResponse->ScatterCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FSOPT', $lastEvent->serverResponse->FSOPT);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
                    if (isset($lastEvent->serverResponse->ReplayGameLogs)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', json_decode(json_encode($lastEvent->serverResponse->ReplayGameLogs), true)); //ReplayLog
                    }
                    if (isset($lastEvent->serverResponse->FreeStacks)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', json_decode(json_encode($lastEvent->serverResponse->FreeStacks), true)); // FreeStack

                        $FreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                        $stack = $FreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') -1];
                    }
                    $bet = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $bet = '50.00';
                }
                $currentReelSet = 0;
                $spinType = 's';
                if(isset($stack)){
                    if($stack['reel_set'] >= 0){
                        $currentReelSet = $stack['reel_set'];
                    }
                    $str_initReel = $stack['initReel'];
                    $str_wlm_v = $stack['wlm_v'];
                    $str_wlm_p = $stack['wlm_p'];
                    $str_sty = $stack['sty'];
                    $str_rwd = $stack['rwd'];
                    $str_fs_opt = $stack['fs_opt'];
                    $str_fs_opt_mask = $stack['fs_opt_mask'];
                    $fsmax = $stack['fsmax'];
                    $strWinLine = $stack['wlc_v'];
                    $fsmore = $stack['fsmore'];
                    
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                    }     
                    if($str_wlm_v != ''){
                        $strOtherResponse = $strOtherResponse . '&wlm_v='. $str_wlm_v .'&wlm_p='. $str_wlm_p;
                    }
                    if($str_sty != ''){
                        $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                    }
                    if($str_rwd != ''){
                        $strOtherResponse = $strOtherResponse . '&rwd=' . $str_rwd;
                    }
                    if($str_fs_opt != ''){
                        $strOtherResponse = $strOtherResponse . '&fs_opt_mask='. $str_fs_opt_mask .'&fs_opt='. $str_fs_opt;
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'ScatterCount') >= 3){
                        $spinType= 'fso';
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
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0 || $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                    {
                        if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0 ) 
                        {
                            $strOtherResponse = $strOtherResponse . '&fs_total='.($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 1).'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        }
                        else
                        {
                            $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        }
                        if($slotSettings->GetGameData($slotSettings->slotId . 'FSOPT') >= 0){
                            $strOtherResponse = $strOtherResponse . '&fsopt_i=' . $slotSettings->GetGameData($slotSettings->slotId . 'FSOPT');
                        }
                    }
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));

                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=8,3,12,3,12,11,8,12,3,12,3,6,10,12,3,12,3,6,10,7,13,7,13,12,14,7,14,7,13,15,14,11,14,14,8,14,14,9,14,14,8,14&balance='. $Balance .'&nas=14&cfgs=5125&ver=2&index=1&balance_cash='. $Balance .'&def_sb=13,11,13,11,13,11&reel_set_size=11&def_sa=6,6,6,13,13,13&reel_set='.$currentReelSet.$strOtherResponse.'&balance_bonus=0.00&na=' . $spinType . '&scatters=1~0,0,0,0,0,0~0,0,0,0,0,0~1,1,1,1,1,1&gmb=0,0,0&rt=d&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=6,6,6,13,13,13&sb=13,11,13,11,13,11&reel_set10=13,13,13,4,12,12,12,6,12,5,5,5,13,10,9,11,4,4,4,5,3,7,8,11,11,11,3,3,3,8,8,8,9,9,9,6,6,6,4,11~11,11,11,9,3,3,3,4,9,9,9,3,13,13,13,12,8,6,10,6,6,6,13,12,12,12,5,7,5,5,5,11,4,4,4,8,8,8,6,3,9,13,3,9,3,13,9,12,6,9,6,3,13,9,13,6,4,9,3,12,9,12,6,4,6,4,9,4,8,13,9,4,3~8,8,8,11,4,10,3,3,3,6,13,8,3,6,6,6,7,13,13,13,9,5,12,11,11,11,4,4,4,9,9,9,5,5,5,12,12,12,3,12,3,6,9,6,12,5,12,13,5,9,12,5,11,7,4,9,4,6,13,6,13,3,5,12,3,6,11,9,12,9,13,3,13,9,4,9,6,12,11,4,12,11,4,3~12,12,12,11,8,8,8,8,5,13,13,13,7,5,5,5,10,9,9,9,12,6,6,6,3,3,3,3,13,4,4,4,6,4,9,11,11,11,6,9,6,13,8,6,8,6,3,6,9,4,9,6,11,8,5,9,4,9,7,9,8,9,4,13,5,8,9~13,5,3,3,3,9,9,9,9,7,6,11,4,3,8,12,10,11,11,11,12,12,12,5,5,5,6,6,6,4,4,4,8,8,8,13,13,13,3,8,9,6,12,8,9,3,8,4,3,11,4,3,12,6,11,9,12,4,11,9,3,8,3,11,3,11,6,12,5,9,12,9,5,8,4,9,3,12,9,6,5,9,12,3,9,5,12,4,6,4,6~8,8,8,7,10,9,13,13,13,12,6,12,12,12,13,3,8,11,4,6,6,6,5,5,5,5,4,4,4,9,9,9,3,3,3,11,11,11,4,13,6,3,11,13,3,6,5,6,9,3&sc='. implode(',', $slotSettings->Bet) .'&defc=50.00&purInit_e=1&sh=7&wilds=2~0,0,0,0,0,0~1,1,1,1,1,1;15~0,0,0,0,0,0~1,1,1,1,1,1;16~0,0,0,0,0,0~1,1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&counter=2&paytable=0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;150,60,40,15,0,0;60,30,20,10,0,0;40,20,15,7,0,0;30,15,10,4,0,0;30,10,8,3,0,0;30,10,8,3,0,0;20,6,4,2,0,0;20,6,4,2,0,0;20,6,4,2,0,0;10,4,2,1,0,0;10,4,2,1,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0&l=20&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=4,4,4,1,10,3,13,13,13,12,6,12,12,12,8,11,11,11,11,5,8,8,8,7,4,9,9,9,9,13,3,3,3,5,5,5,6,6,6,7,1,8,13,6,8,12,6,9,3,8,9,3,8,5,6,1,11,12,11,1,13,8,3,9,3,8,12,6,3,11,8,13,6~8,3,9,9,9,13,7,11,13,13,13,2,6,11,11,11,9,1,3,3,3,12,5,10,5,5,5,4,8,8,8,12,12,12,4,4,4,3,9,13,12,4,11,3,13,12,4,2,9,5,12,9,4,5,4,6,4,13,5,13,12,13~11,11,11,8,7,1,13,13,13,13,12,12,12,9,9,9,9,4,5,5,5,3,3,3,3,10,4,4,4,11,5,12,2,6,2,12,4,9,12,2,13,5,3,2~4,4,4,4,9,9,9,11,13,13,13,5,7,12,12,12,12,1,5,5,5,2,8,13,11,11,11,6,3,10,9,3,3,3,5,13,9,11,9,3,9,12,9,5,13,5,7,9,3,5,3,5,9,3,13,3,5,3,13~11,12,12,12,1,10,9,12,11,11,11,13,5,5,5,8,7,3,3,3,4,5,2,6,4,4,4,3,13,13,13,9,9,9,12,7,4,5~10,12,12,12,11,8,8,8,3,8,6,3,3,3,1,11,11,11,9,5,5,5,5,13,12,6,6,6,7,4,13,13,13,9,9,9,4,4,4,6,12,5,4,8,5,12,6,5,4,5,4,6,5,8,7,9,8,9,13,4,6,13,8,4,12,6,12,7,1,3,13,11,5,8,9,6,8,5,9,5,9,5,12&s='.$lastReelStr.'&reel_set2=8,8,8,12,10,11,11,11,13,3,13,13,13,9,3,3,3,4,6,12,12,12,11,6,6,6,8,5,5,5,7,4,4,4,5,9,9,9,10,3,13,3,4,11,9,5,3,11,10,11,13,11,9,3,9,4,5,11,10,3,11,6,5,13,3,9,5,13,5,10,3,9,6,11,3,13,11,6,4,9,4,3,11,4,11,4,5,9,3,11,13,11,3,5,12,3,4,3,6~9,9,9,5,11,11,11,12,7,11,4,4,4,13,13,13,13,4,5,5,5,10,6,3,3,3,8,8,8,8,3,12,12,12,9,5,4,3,4,5,6,11,3,13,11,3,5,11,8,11,8,12,11,13,4,13,10,4,13,4,11,8,5,3,8,3,13,5,4,13,3~5,10,4,4,4,11,8,11,11,11,9,13,6,4,3,3,3,3,8,8,8,12,7,9,9,9,12,12,12,13,13,13,5,5,5,9,8,12,4,12,4,6,11,7,11,4,3,7,11,3,8,4,8,3,4,9,11,9,11,12,11,9,7,3,11,4,7,8,3,7,9,4,3,4,3,9,11,9,12~9,7,6,3,3,3,5,8,3,4,4,4,13,4,11,12,12,12,12,10,9,9,9,13,13,13,5,5,5,11,11,11,4,5,10,13,4,3,13,12,3,12,5,13,7,3,11,5,13,3,5,4,3,13,11,7,4,12,3,5,8,5,7,13,8,3,5,12,7,3,6,5,3,7,3,6,13~4,4,4,9,3,3,3,10,12,3,4,11,11,11,8,6,5,7,11,13,9,9,9,12,12,12,5,5,5,13,13,13,11,13,9,5,9,3,9,6,13,3,13,9,13,11,9,13,11,6,9,13,3,5,12~12,13,13,13,5,4,3,12,12,12,10,9,9,9,7,11,9,6,6,6,8,13,6,4,4,4,3,3,3,11,11,11,5,5,5,8,8,8,6&t=243&reel_set1=4,8,10,6,6,6,13,9,9,9,9,7,12,5,13,13,13,11,4,4,4,6,8,8,8,3,11,11,11,3,3,3,12,12,12,5,5,5,7,5,6,11,13,9,6,10,3,6,13,3,9,3,6,8,9,10,12,3,12,10,6,3,8,13,9,8,3,13,3,13,6,13,3,8,12,6,9,12,6,12,11,10,12,9,3,10,13~3,3,3,11,8,8,8,6,13,13,13,9,9,9,9,10,5,5,5,13,12,12,12,5,3,4,2,12,8,7,11,11,11,4,4,4,5~8,8,8,13,13,13,13,6,10,4,7,11,9,11,11,11,5,3,3,3,8,2,3,12,9,9,9,4,4,4,5,5,5,12,12,12,5,13,9,13,5,11,13,2,13,3,13,5,4,2,12,13,3,11,5,3,11,4,13,9,3,13,4,13,5,13,4,11,3,13,12,7,13,4,13,2,5,3,4~4,12,12,12,10,13,13,13,5,3,3,3,2,11,7,6,13,11,11,11,3,12,9,9,9,9,8,5,5,5,4,4,4,5,8,12,11,12,8,3,13,11,2,9,6,2,3,2,3,12,5,8,5,9,5,12,8,3,7,9,5,12,7,3,12,9,3,11,3,9,2,8,11,12,5~4,9,8,13,3,7,6,11,13,13,13,5,2,9,9,9,12,10,4,4,4,11,11,11,12,12,12,5,5,5,3,3,3,13,3,11,2,13,2,12,3,13,7,13,9,3,2,6,12,13,12,13,3,5,13,3,13,3,13,7,13,3,13,9,5,3,9,3,5,12,13,5,13,9,12,9,2,11,12,5,9,12,3,13,9,12,11,13,5,9,13,9,3,12,3,13,12,5,9~13,13,13,3,12,12,12,7,9,8,8,8,6,6,6,6,10,4,5,5,5,5,13,11,11,11,8,12,11,9,9,9,3,3,3,4,4,4,5,11,9,6,4,8,11,4,9,5,11,6,4,6,5,11,12,8,11,6,12,11,4,3,12,6,9,8,4,11,4,3,6,5,3,8,6,9,3,6,12,5,11,3,4,12,5,9,4,6,4,6,4,9,8,11,6,4,5,11,9,6&reel_set4=7,11,11,11,9,8,8,8,12,4,6,5,9,9,9,13,10,8,5,5,5,11,3,13,13,13,12,12,12,3,3,3,6,6,6,4,4,4,5,4,9,8,5,13,4,5,12,13,4,12,4,5,4,12,5,4,3,13,12~13,9,11,11,11,10,9,9,9,4,12,12,12,7,8,3,4,4,4,5,13,13,13,12,6,5,5,5,11,3,3,3,11,3,5,11,5,11,9,5,11,12,5,11,12,11,5,12,4,11,3,4,9,3,11,5,4,9,11,3,9,11,7,3,12,3,12,3,9,11,5,4,11,9,11,12,9,4,3,11,5,4,7,8,3,11,3,11,12,4,3~12,12,12,5,12,7,11,6,11,11,11,9,13,13,13,13,4,10,3,8,9,9,9,4,4,4,3,3,3,5,5,5,11,9,13,3,13,11,3,9,4,13,9,5,11,13,4,13,4,13,5,13,11,13,11,9,3,9,13,3,13,9,13,9,13,4,13,4,3,13,5,13,9,4,13,3,13~10,8,8,8,5,13,13,13,13,12,12,12,4,9,4,4,4,2,8,11,11,11,7,3,3,3,6,5,5,5,12,11,3,9,9,9,12,7,8,5,12,8,5,9,3,4,11,12,11,8,3~9,9,9,9,6,8,8,8,2,12,12,12,12,5,10,4,7,3,8,11,13,4,4,4,11,11,11,3,3,3,13,13,13,5,5,5,12,7,3,8,5,8,12,13,11,13,8,12,11,7,5,3,12,4,8,12,8,11,3,5,8~13,8,9,11,11,11,10,12,11,4,3,7,5,6,12,12,12,5,5,5,13,13,13,4,4,4,3,3,3,8,8,8,9,9,9,6,6,6,5,8,6,12,11,5,4,12,3,11,4,12,11,7,8,11,12,4,6,11,3,4,10,9,12,11,8,3,9,11,8,11,8,11,5,3,6,12,6,8,9,8,5,11,5,6,11,9&purInit=[{type:"fsbl",bet:2000,bet_level:0}]&reel_set3=3,3,3,7,5,11,9,9,9,13,12,6,6,6,9,4,4,4,10,11,11,11,6,12,12,12,3,4,5,5,5,8,13,13,13,8,8,8,6,9,6,12,7,13,5,13,6,9,11,6,13,12,7,9,4,8,6,5,7,9,11,4,6,9,8,12,4,8,6,4,5,10,4,7,9,4,13,11,9,5,12,4,12,4,11,9,4~3,3,3,3,4,12,12,12,5,13,13,13,9,12,2,4,4,4,10,9,9,9,8,8,8,8,6,11,7,13,5,5,5,11,11,11,5,9,13,12,7,12,9,11,13,11,13,12,13,12,8,11~3,9,13,13,13,11,12,5,5,5,2,13,8,5,7,10,4,6,9,9,9,8,8,8,11,11,11,12,12,12,4,4,4,3,3,3,4,12,11,9,13,5,13,4,12,4,11,12,13,8,4,6,9,5,11,4,9,5,4,13,6,11,5,9,12,5,4,11,5,9,12,13,9,6,11,12,8,4,11,13,12,9,8,4,12,8,4,12,5,12,4,9,4,11,5,8,4,9,8~9,9,9,8,11,7,12,12,12,5,6,13,10,9,3,3,3,4,12,3,13,13,13,5,5,5,11,11,11,4,4,4,8,3,13,5,3,10,8,3,5,12,11,3,12,3,5,13,5,12,11,5,10,13,5,4,12,13,12,13,12,13,5,13,5~4,4,4,8,12,5,5,5,5,9,3,9,9,9,6,4,11,13,13,13,7,12,12,12,13,10,3,3,3,11,11,11,3,7,5,12,3,12,7,3,11~4,3,3,3,3,7,6,13,13,13,13,5,10,11,11,11,11,9,8,12,12,12,12,6,6,6,5,5,5,9,9,9,8,8,8,4,4,4,5,11,3,6,9,13,5,13,10,9,5,13,9,5,11,6,9,3,13,9,13,11,8,9,13,9,5,11,13,9,8&reel_set6=11,3,10,13,5,4,8,6,9,12,7,10,12,9,10,4,5,7,13,7,6,4,10,7,9,6,4,5,10,4,7,4,9,10,9,6,5,6,10,5,4,5,6,4,5,10,5,6,5,4,7,9,4,7,10,4,6,7,5,7,6,7,5,9,7,9,13,4,7,5,4,9,4,6,9,10,13,4,10,9,6~4,13,9,2,11,6,8,10,12,7,5,3,7,6,7,11,2,6,2,8,7,8,7,11,5,11,2,7,3,7,3,11,3,6,5,6,8,11,2,8,3,6,12,5,11,6,7,8,7,8,5,2,3,8,2,3,11~7,12,8,10,2,9,4,5,11,6,13,3,11,3,6,12,13,3,4,6,10,11,13,11,6,12,8,5,12,6,3,5,10,5,3,12,8,5,12,9,4,11,12,8,3,4,3,12,4,8,5,6,10,3,8,5,8,4,5,6,8,11,12,4,8,10,12,11,4,5,8,3,6,12,3,11,6,12,13,12~6,10,11,8,9,12,2,7,4,3,13,5,4,11,3,13,7,2,5,7,5,4,12,4,7,5,9,13,2,4,2,4,5,4,2,4,10,12~12,11,9,7,5,2,13,4,6,8,10,3,11,5,3,13,7,2,11,8,11,10,8,11,2,8,11,10,11,3,11,6,8,13,7,3,7,11,13,2,13,8,11,9,13,3,11,6,11,13,8,11,8,13,5,7,11,2,8,13,11,9,11,3,8,11,13,10,11,13,8,9,13~8,3,6,13,11,12,4,5,10,9,7,9,7,3,5,12,5,6,10,6,10,6,13,12,3,5,7,10,7,9,13,7,3,5,10,9,6,3,12,6,7,13,6,3,7,10,13,9,7,3,10,12,6,7,6,9,7,9,12,5,7,9,12,5,10,12,3,5,6,9&reel_set5=5,6,7,13,10,4,11,12,3,1,8,9,11,12,6,1,6,11~5,11,6,1,10,4,13,9,7,8,3,12,2,8,3,2,7,8,2,7,2,8,6,12,1,8,1,2,12,8,2,1,3,7,12,7,6~2,7,10,1,11,8,3,9,4,12,13,5,6,13,3,1,3,1,9,5,3,4,10,4,9,5,4,1,6,3,4,7,5,11,1,13,1,5,10,1,9,3,4,5,6,3~4,13,12,10,6,7,5,2,3,1,9,11,8,3,9,7,8,1,3,1,9,7,5,12,13,3,5,9,8,1,7,1,13,10,5,1,9,1,12,1,3,9,5,9,5,9,12,1,5,8,5,10,1,9,5~6,13,12,8,11,2,3,4,7,9,1,10,5,7,2,5,10,5,9,3,4,10,7,5,12,13,5,3,9,10,8,11,2,7,8,13,10,5,10,7,13,3,4,2,7,5,10,8,1,7,8,2,5,4,5,2,8,2,5,8,5,2,10,2,7,2,8,5,2,7,5,8,13,8,5,13,2,5,9,2,13,2,1,13,9,4,10,2,3,2,7,2~9,1,4,11,10,6,8,3,7,12,13,5,13,3,13,5,10,7,3,7,3,5,13,7,13,7,8,1,7,3,6,3,8,13,3,13,8,3,13,11,13,5,13,7,5,6,13,3,6,3,7,3,8,3,8,7,12,8,5,13,7,3,8,13,7,5,13,5,13,12,3,8,5,3,5,3,7,6,11,6,3,5,7,13,8,13,12,3&reel_set8=13,11,5,6,8,13,13,13,10,7,3,5,5,5,12,8,8,8,9,4,4,4,4,11,11,11,9,9,9,3,3,3,12,12,12,7,7,7,3,12,11,5,8,7,3,6,3,12,10,5,11,3,9,6,3,4,5,12,3,4,5,11,12,7,4,3,12,8,3,5,12,7,4,5,3,7,6,4,8~7,6,10,13,13,13,13,12,11,9,7,7,7,3,5,2,4,8,8,8,8,12,12,12,4,4,4,11,11,11,9,9,9,3,3,3,4,12,5,3,5,2,3,5,11,13,5,12,10,5,8,11,12,13,5,11,9,12,13,9,2,10,12,9,13,11,3~3,3,3,11,7,13,13,13,9,8,8,8,6,8,3,13,4,2,12,5,5,5,10,5,11,11,11,4,4,4,12,12,12,9,9,9,8,13,11,9,8,11,13,4,8,13,9,11,4,5,11,4,5,13,4,9,4,13,5,4,13,4,11,13~4,4,4,3,7,9,9,9,9,12,13,13,13,13,8,8,8,6,12,12,12,11,4,10,7,7,7,2,5,8,3,3,3,11,11,11,8,12,13,7,13,12,7,11,3,7,5,13,8,7,3,13,5,9,12,5,10,13,9,6,12,7,12,9,12,11,10,11,8,5,13,12,9,3,7,5,9,11,13,5,7,3,12,13,11,12,9,11,10,3,5~6,3,8,12,4,5,10,11,9,7,13,5,4,11,5,4,12,5,9,5,13,8,5,9,10,5,10,8,4,5,11,5,9,10,9,5,9,3,4,9,5,12,4,10,9,4,9,5,3,5,9,13,10,11,5,4,5,8,9,10,5,9,4,5,10,13,5,10,5,9,10,5,4,5,8,13,10,12,4,5,10,12,10,13,3,9,5,9,13,4,10~7,12,3,13,8,10,6,5,11,9,4,6,3,6,5,3,11,6,9,6,5,13,6,9,4,5,13,11,6,13,9,6,5,3,6,11,5,3,11,6,5,3,4,6,3,6,3,5,6,11,10,5,6,4,3,6,11,6,4,13,6,11,6,5,4,6,10,4,3,11,6,11,5,11,6,5,4,5,6,4,9,5,6,4,13,6,5,3,6,9,3,4,6,9,4,5,6&reel_set7=3,6,6,6,12,13,13,13,11,7,4,9,11,11,11,8,10,9,9,9,13,8,8,8,6,3,3,3,5,5,5,5,12,12,12,4,4,4,13,12,11,13,9,11,5~12,12,12,3,5,3,3,3,6,2,7,8,8,8,10,4,4,4,13,4,13,13,13,8,9,12,9,9,9,11,11,11,11,5,5,5,7,13,5,7,5,13,5,2,6,13,5,4,8,7,5,11,3,5,2,9,5,11,8,3~6,2,3,13,7,11,11,11,10,12,12,12,5,12,4,4,4,4,11,9,9,9,8,9,5,5,5,13,13,13,3,3,3,8,8,8,12,10,9,3,12,11,7,4,5,10,12,8,9,3,4,9,13,5,8,13,5,4,3,8,12,3,7,9,10,13,7,3,10,3,11,4,5,4,3,7,4,5,4,3,7,8,5,7,2,9,5,7,13,4~4,4,4,5,8,9,9,9,6,3,9,8,8,8,12,10,5,5,5,2,11,12,12,12,7,4,13,3,3,3,11,11,11,13,13,13,8,2,8,6,3,11,12,8,3,9~13,2,5,4,4,4,8,11,9,9,9,9,10,3,12,4,6,7,5,5,5,11,11,11,13,13,13,8,8,8,3,3,3,12,12,12,8,9,10,3,4,10,12,8,5~4,9,9,9,10,12,12,12,7,11,6,12,9,5,13,13,13,13,3,11,11,11,8,3,3,3,8,8,8,4,4,4,5,5,5,6,6,6,13,8,5,12,13,12,11,10,11,8,6,5,10,9,13,5,13,5,12,13,8,5,8,13,5,12,13,5,12,8,6,5,6,5,11,5,10,8,13,5,8,5,12,8,11,5,8,5,8,13,11,10,5,12,13,12,13,12,6&reel_set9=7,5,8,11,9,9,9,6,10,12,3,4,4,4,4,13,9,5,5,5,8,8,8,12,12,12,13,13,13,3,3,3,11,11,11,6,6,6,9,13,9,11,8,6,5,3,5,13,12,5,13,10,13,5,9,5,13,12,5,11,4,12,13,11,9,13,12,8~9,9,9,8,3,3,3,3,6,13,13,13,9,11,4,13,5,5,5,5,7,10,8,8,8,12,11,11,11,4,4,4,12,12,12,8,3,5,4,12,3,12,11,12,3,12,11,4,13,6,11,13,12,5,4,5,8,13,5,3,4,10,3,8,11,10,5,12,11,3,4,3,5,3,10,13,8,3,4,13,7,4,12,13,12,10,13,11,3,4~13,8,8,8,12,7,4,10,8,5,6,3,9,11,11,11,11,4,4,4,5,5,5,3,3,3,13,13,13,12,12,12,9,9,9,10,12,3,10,4,11,10,7,4,11,12,4,11,12,10,3,10,8,12,9,7,11,3,10,12,11,3~8,12,3,12,12,12,10,9,9,9,4,5,11,7,4,4,4,9,6,11,11,11,13,13,13,13,3,3,3,5,5,5,4,13,11,4,3,11,10,9,11,9,11,4,3,13,3,9,11,12,3,13,9,3,11~9,5,6,11,11,11,8,4,4,4,4,7,12,12,12,3,12,13,3,3,3,10,11,13,13,13,5,5,5,9,9,9,8,5,8,3,11,13,4,11,5,11,8,11,4,5,4,11,13,11,8,11,5,7,11,5,11,6,11,4,12,11,5,11,8,5~9,9,9,12,5,5,5,13,11,12,12,12,7,6,6,6,8,6,5,9,4,10,11,11,11,3,3,3,3,4,4,4,13,13,13,8,8,8,13,8,13,8,3,13,3,5,11,6,13,4,8,4,8,13,4,6,8,7,5,8,11,13,12,6,11,13,3,4,13,5,12,4,6,3,4,8,4,11,5,11,12,7,13,12,13,5,8,4,13,10,13,12,4,12,5,13,6,5&total_bet_min=10.00';
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
                    if( $slotEvent['slotEvent'] == 'doSpin' && $slotSettings->GetBalance() < ($lines * $betline) ) 
                    {
                        $balance_cash = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                        if(!isset($balance_cash)){
                            $balance_cash = $slotSettings->GetBalance();
                        }
                        $response = 'nomoney=1&balance='. $balance_cash .'&error_type=i&index='.$slotEvent['index'].'&balance_cash='. $balance_cash .'&balance_bonus=0.00&na=s&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&ext_code=SystemError&sver=5&counter='. ((int)$slotEvent['counter'] + 1);
                        exit( $response );
                    }
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') < $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotEvent['slotEvent'] == 'freespin' ) 
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
                $allBet = $betline * $lines;
                if($pur >= 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $allBet * 100;
                }
                $freeStacks = []; 
                $isGeneratedFreeStack = false;
                $slotSettings->SetGameData($slotSettings->slotId . 'ScatterCount', 0);
                if($slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    $bonusMpl = $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl');
                    $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                    $isGeneratedFreeStack = true;
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
                    $bonusMpl = 1;
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FSOPT', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '6074458' . substr($roundstr, 4, 10);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $leftFreeGames = 0;

                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []);
                }
                
                $wild = '2';
                $scatter = '1';
                $Balance = $slotSettings->GetBalance();
                
                $totalWin = 0;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $str_initReel = '';
                $str_wlm_v = '';
                $str_wlm_p = '';
                $str_sty = '';
                $str_rwd = '';
                $str_fs_opt = '';
                $str_fs_opt_mask = '';
                $fsmax = 0;
                $fsmore = 0;
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $str_initReel = $stack['initReel'];
                    $str_wlm_v = $stack['wlm_v'];
                    $str_wlm_p = $stack['wlm_p'];
                    $str_sty = $stack['sty'];
                    $str_rwd = $stack['rwd'];
                    $str_fs_opt = $stack['fs_opt'];
                    $str_fs_opt_mask = $stack['fs_opt_mask'];
                    $fsmax = $stack['fsmax'];
                    $strWinLine = $stack['wlc_v'];
                    $fsmore = $stack['fsmore'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $lastReel = explode(',', $stack[0]['reel']);
                    $currentReelSet = $stack[0]['reel_set'];
                    $str_initReel = $stack[0]['initReel'];
                    $str_wlm_v = $stack[0]['wlm_v'];
                    $str_wlm_p = $stack[0]['wlm_p'];
                    $str_sty = $stack[0]['sty'];
                    $str_rwd = $stack[0]['rwd'];
                    $str_fs_opt = $stack[0]['fs_opt'];
                    $str_fs_opt_mask = $stack[0]['fs_opt_mask'];
                    $fsmax = $stack[0]['fsmax'];
                    $strWinLine = $stack[0]['wlc_v'];
                    $fsmore = $stack[0]['fsmore'];
                }
                $reels = [];
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;
                $wildReels = [];
                for($i = 0; $i < 42; $i++){
                    if($lastReel[$i] == $scatter){
                        $scatterCount++;
                    }
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
                // if( $scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin') 
                // {
                //     if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0 ) 
                //     {
                //         $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                //         $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 8);
                //         $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                //     }
                // }
                if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                }

                $strLastReel = implode(',', $lastReel);
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 6; $i++){
                    $reelA[$i] = mt_rand(4, 8);
                    $reelB[$i] = mt_rand(4, 8);
                }
                $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
               
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                
                $strOtherResponse = '';
                $isState = true;
                $isEnd = true;
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $spinType = 's';
                    $isEnd = false;
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                    {
                        $spinType = 'c';
                        $isEnd = true;
                        $strOtherResponse = '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                    else
                    {
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 's';
                    }
                    if($fsmore > 0 ){
                        $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                    }
                    $strOtherResponse = $strOtherResponse . '&fsopt_i=' . $slotSettings->GetGameData($slotSettings->slotId . 'FSOPT');
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                    if($scatterCount >= 3 ){
                        $isState = false;
                        $spinType = 'fso';
                        $slotSettings->SetGameData($slotSettings->slotId . 'ScatterCount', $scatterCount);
                        // $strOtherResponse = '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fs=1&fsres=0.00';
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
                if($str_wlm_v != ''){
                    $strOtherResponse = $strOtherResponse . '&wlm_v='. $str_wlm_v .'&wlm_p='. $str_wlm_p;
                }
                if($str_sty != ''){
                    $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                }
                if($str_rwd != ''){
                    $strOtherResponse = $strOtherResponse . '&rwd=' . $str_rwd;
                }
                if($str_fs_opt != ''){
                    $strOtherResponse = $strOtherResponse . '&fs_opt_mask='. $str_fs_opt_mask .'&fs_opt='. $str_fs_opt;
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&wlc_v=' . $strWinLine;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=7&c='.$betline.'&sver=5&reel_set='.$currentReelSet.'&w='.$totalWin.'&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&s='.$strLastReel;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin')  . ',"ScatterCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'ScatterCount')  . ',"FSOPT":' . $slotSettings->GetGameData($slotSettings->slotId . 'FSOPT') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($slotEvent['slotEvent'] == 'freespin' && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * 100;
                }
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if( $scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin') 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                }
            }else if($slotEvent['slotEvent'] == 'doFSOption'){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lastReel = $lastEvent->serverResponse->LastReel;
                $ind = -1;
                if(isset($slotEvent['ind'])){
                    $ind = $slotEvent['ind'];
                }
                $lines = 20;
                $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                $scatterCount = $slotSettings->GetGameData($slotSettings->slotId . 'ScatterCount');
                if($scatterCount == 0){
                    $response = 'unlogged';
                    exit( $response );
                }
                $Balance = $slotSettings->GetBalance();
                $stack = $slotSettings->GetReelStrips('bonus', $betline * $lines, $ind, $scatterCount);
                if($stack == null){
                    $response = 'unlogged';
                    exit( $response );
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $stack);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                $fsmax = $stack[0]['fsmax'];
                $fs_opt = $stack[0]['fs_opt'];
                $fs_opt_mask = $stack[0]['fs_opt_mask'];
                $slotSettings->SetGameData($slotSettings->slotId . 'FSOPT', $ind);

                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $fsmax);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                $slotSettings->SetGameData($slotSettings->slotId . 'ScatterCount', 0);

                $response = 'fsmul=1&fs_opt_mask='. $fs_opt_mask .'&balance='.$Balance.'&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&index='.$slotEvent['index'].'&balance_cash='.$Balance .'&balance_bonus=0.00&na=s&fswin=0.00&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&fs=1&fs_opt='. $fs_opt .'&fsres=0.00&sver=5&counter='. ((int)$slotEvent['counter'] + 1) . '&fsopt_i=' . $ind;

                
                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------
                
                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin')  . ',"ScatterCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'ScatterCount')  . ',"FSOPT":' . $slotSettings->GetGameData($slotSettings->slotId . 'FSOPT') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $slotSettings->SaveLogReport($_GameLog, $betline * $lines, $lines, 0, $slotEvent['slotEvent'], false);
            }
            if($slotEvent['action'] == 'doSpin' || $slotEvent['action'] == 'doFSOption' || $slotEvent['action'] == 'doCollect' || $slotEvent['action'] == 'doCollectBonus'){
                $this->saveGameLog($slotEvent, $response, $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'), $slotSettings);
            }
            try{
                $slotSettings->SaveGameData();
                \DB::commit();
            }catch (\Exception $e) {
                $slotSettings->InternalError('TheDogHouseMegawaysDBCommit : ' . $e);
                \DB::rollBack();
                $response = 'unlogged';
            }
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

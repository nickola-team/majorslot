<?php 
namespace VanguardLTE\Games\BuffaloKingPM
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

            if($slotEvent['slotEvent'] == 'doSpin' && isset($slotEvent['c'])){
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
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 40);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [9,3,11,6,6,11,5,9,11,4,6,12,4,9,9,6,8,11,12,9,9,1,3,13]);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    if(isset($lastEvent->serverResponse->TotalSpinCount)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
                    }
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
                    $bet = '25.00';
                }
                $spinType = 's';
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if(isset($stack)){
                    $fsmore = $stack['fsmore'];
                    $fsmax = $stack['fsmax'];
                    $str_stf = $stack['stf'];
                    $str_slm_lmi = $stack['slm_lmi'];
                    $str_slm_lmv = $stack['slm_lmv'];
                    $str_slm_mp = $stack['slm_mp'];
                    $str_slm_mv = $stack['slm_mv'];
                    $strWinLine = $stack['win_line'];
                    if($stack['reel_set'] >= 0){
                        $currentReelSet = $stack['reel_set'];
                    }
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=0';
                    }
                    if($str_stf != ''){
                        $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                    }
                    if($str_slm_lmv != ''){
                        $strOtherResponse = $strOtherResponse . '&slm_lmv=' . $str_slm_lmv . '&slm_lmi=' . $str_slm_lmi;
                    }
                    if($str_slm_mv != ''){
                        $strOtherResponse = $strOtherResponse . '&slm_mv=' . $str_slm_mv . '&slm_mp=' . $str_slm_mp;
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
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 || $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0)
                    {
                        if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0) 
                        {
                            $strOtherResponse = $strOtherResponse . '&fs_total='.($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 1).'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&w=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        }
                        else
                        {
                            $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&w=0';
                        }
                        if($fsmore > 0){
                            $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                        }  
                    }
                }
                
                
                $Balance = $slotSettings->GetBalance();  
                $response = 'def_s=9,3,11,6,6,11,5,9,11,4,6,12,4,9,9,6,8,11,12,9,9,1,3,13&balance='. $Balance .'&cfgs=5284&ver=2&index=1&balance_cash='. $Balance .'&def_sb=3,4,7,6,8,4&reel_set_size=9&def_sa=8,7,5,3,7,5&reel_set='.$currentReelSet.'&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0,0~100,25,15,8,0,0~1,1,1,1,1,1&gmb=0,0,0&rt=d&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa=8,7,5,3,7,5&sb=3,4,7,6,8,4&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=25.00&purInit_e=1&sh=4&wilds=2~0,0,0,0,0,0~1,1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;300,250,200,80,0,0;250,200,100,60,0,0;250,200,100,60,0,0;200,120,80,40,0,0;200,120,80,40,0,0;120,80,40,20,0,0;120,80,40,20,0,0;100,60,30,10,0,0;100,60,30,10,0,0;80,40,20,10,0,0;80,40,20,10,0,0&l=40&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=3,3,3,3,8,7,10,9,9,4,11,8,8,10,6,9,1,11,11,5,5,10,12,10,11,6,6,6,4,13,13,7,7,7,12,10,6,13,4,11,11,10,12,5,13,1,12,12,3,8,11,11,10,6,6,9,9,5,5,5,10,10,12,7,7,13,13,11,4,4,4,10,10,9,7,13,13,5,9,8,12,12~12,12,8,9,5,13,13,7,9,10,10,4,4,4,11,13,13,7,7,12,10,10,12,12,6,6,6,13,13,7,11,7,5,8,13,6,9,12,3,3,11,8,8,7,7,7,13,13,5,5,5,6,12,12,4,4,8,8,11,10,7,9,9,10,5,5,11,11,1,9,6,10,8,2,11,4,9,9,7,8,8,3,3,3,3~3,3,3,3,8,8,7,9,9,4,11,6,8,10,6,9,1,11,11,5,5,10,6,6,6,12,10,11,4,13,13,7,7,7,12,10,6,2,4,11,11,10,12,5,13,1,12,12,3,8,11,11,10,6,6,9,9,5,5,5,10,10,12,7,7,13,13,11,4,4,4,10,10,9,7,13,13,5,9,8,12,12~12,12,8,9,5,13,13,7,9,10,10,4,4,4,11,13,13,7,7,12,10,10,12,12,6,6,6,13,13,7,11,7,5,8,13,6,9,12,3,3,11,8,8,13,13,7,7,7,5,5,5,6,12,12,4,4,8,8,11,10,7,9,9,10,5,5,11,11,1,9,6,10,8,2,11,4,9,9,7,8,8,3,3,3,3~3,3,3,3,8,8,7,9,9,4,11,6,8,10,6,9,1,11,11,5,5,10,12,10,11,4,13,13,7,7,7,12,10,6,2,4,11,11,10,6,6,6,12,5,13,1,12,12,3,8,11,11,10,6,6,9,9,5,5,5,10,10,12,7,7,13,13,11,4,4,4,10,10,9,7,13,13,5,9,8,12,12~12,12,8,9,5,13,13,7,9,10,10,4,4,4,11,5,5,5,13,13,7,7,12,10,10,12,12,6,6,6,13,13,7,11,7,5,8,13,6,9,12,3,3,11,7,7,7,8,8,13,13,6,12,12,4,4,8,8,11,10,7,9,9,10,5,5,11,11,1,9,6,10,8,2,11,4,9,9,7,8,8,3,3,3,3&s='.$lastReelStr.'&reel_set2=12,12,7,9,5,13,13,7,9,12,3,3,3,3,13,13,13,7,7,12,10,10,5,5,5,9,9,9,7,7,7,9,9,13,13,7,12,7,5,10,13,9,9,12,3,3,10,9,9,13,13,7,12,12,5,5,10,10,10,12,7,9,9,10,5,5,12,12,1,10,7,3,3,3,13,5,9,9,7,10,10,3,3,3,3~5,5,5,8,8,6,6,9,9,11,11,8,8,12,6,9,1,11,11,5,5,9,9,11,12,12,5,8,8,4,9,9,6,6,6,12,12,12,4,4,4,11,11,5,12,8,8,1,9,9,5,8,11,11,12,6,6,9,9,5,5,5,8,8,12,5,5,11,11,11,4,4,4,9,9,9,4,11,11,11,9,8,12,12~3,3,3,3,8,7,10,10,4,11,3,3,3,10,6,13,1,11,11,11,3,3,10,8,8,7,10,11,8,8,4,13,13,7,7,7,13,13,6,10,3,3,3,10,4,4,13,1,11,11,3,8,8,8,10,7,7,7,13,13,6,6,6,10,8,7,7,13,13,11,4,4,4,10,10,10,3,3,3,11,6,8,8,8~12,12,8,9,5,13,13,7,9,10,10,4,4,4,11,13,13,7,7,12,10,10,5,5,5,9,9,6,6,6,6,13,13,7,11,7,5,8,13,6,9,12,7,7,7,3,3,3,3,8,8,11,6,12,12,4,4,8,8,11,10,7,9,9,10,5,5,11,11,1,9,6,10,8,2,11,4,9,9,7,8,8,3,3,3,3~3,3,3,3,8,8,7,9,9,4,11,2,8,10,6,9,1,11,11,5,5,10,9,9,6,6,6,7,10,11,8,8,4,13,13,7,7,7,12,10,6,6,4,11,11,10,12,5,13,1,12,12,3,3,3,3,8,6,6,9,9,5,5,5,10,10,12,7,7,13,13,11,4,4,4,10,10,9,7,13,13,5,9,8,12,12~12,12,8,9,5,13,13,7,9,10,10,4,4,4,11,13,13,7,7,12,10,10,5,5,5,9,9,6,6,6,6,13,13,7,11,7,5,8,13,6,9,12,7,7,7,3,3,3,3,8,8,11,6,12,12,4,4,8,8,11,10,7,9,9,10,5,5,11,11,1,9,6,10,8,2,11,4,9,9,7,8,8,3,3,3,3&t=243&reel_set1=3,3,3,3,8,7,10,10,4,11,3,3,3,10,6,13,1,11,11,3,3,10,8,8,7,10,11,8,6,11,11,11,10,4,13,13,7,7,7,13,13,6,10,3,3,3,10,4,4,13,1,11,11,3,8,8,8,10,7,7,7,13,13,6,6,6,10,8,7,7,13,13,11,4,4,4,10,10,10,3,3,3,11,6,8,8,8~12,12,7,9,5,13,13,7,9,12,3,3,3,3,13,13,13,7,7,12,10,10,5,5,5,9,9,7,10,10,12,12,9,9,9,13,13,7,12,7,5,10,13,9,9,7,7,7,12,3,3,10,9,9,13,13,7,12,12,5,5,10,10,10,12,7,9,9,10,5,5,12,12,1,10,7,3,3,3,13,5,9,9,7,10,10,3,3,3,3~5,5,5,8,8,6,6,9,9,11,11,8,8,12,6,9,1,11,11,5,5,9,9,11,12,12,5,8,6,12,12,12,11,4,9,9,6,6,6,12,12,4,4,4,11,11,5,12,8,8,1,9,9,5,8,11,11,12,6,6,9,9,5,5,5,8,8,12,5,5,11,11,11,4,4,4,9,9,9,4,11,11,11,9,8,12,12~12,12,8,9,5,13,13,7,9,10,10,4,4,4,11,13,13,7,7,12,10,10,5,5,5,9,9,6,11,3,3,10,6,6,6,13,13,7,11,7,5,8,13,6,9,12,3,3,3,3,7,7,7,8,8,11,6,12,12,4,4,8,8,11,10,7,9,9,10,5,5,11,11,1,9,6,10,8,2,11,4,9,9,7,8,8,3,3,3,3~3,3,3,3,8,8,7,9,9,4,11,2,8,10,6,9,1,11,11,5,5,6,6,6,10,9,9,7,10,11,8,6,10,3,3,11,4,13,13,7,7,7,12,10,6,6,4,11,11,10,12,5,13,1,12,12,3,3,3,3,8,6,6,9,9,5,5,5,10,10,12,7,7,13,13,11,4,4,4,10,10,9,7,13,13,5,9,8,12,12~12,12,8,9,5,13,13,7,9,10,10,4,4,4,11,13,13,7,7,12,10,10,5,5,5,9,9,6,11,3,3,10,6,6,6,13,13,7,11,7,5,8,13,6,9,12,3,3,3,3,8,8,7,7,7,11,6,12,12,4,4,8,8,11,10,7,9,9,10,5,5,11,11,1,9,6,10,8,2,11,4,9,9,7,8,8,3,3,3,3&reel_set4=3,3,3,3,10,10,12,6,6,11,11,5,8,8,3,10,10,5,5,5,12,12,1,8,11,6,6,6,11,10,1,8,11,5,12,12,3,3,3,11,11,10,10,10,5,5,8,8,8,11,11,6,6,6,10,12,12~9,9,7,7,7,13,13,4,10,10,9,4,4,4,10,10,12,7,13,13,3,12,12,4,9,9,4,9,13,13,7,7,7,13,13,1,12,12,3,10,10,10,13,12,4,9,9,3,12,12,13,13,3,3,3,3~6,6,6,13,13,5,8,6,6,11,4,4,9,7,7,7,8,6,9,9,5,13,13,1,8,7,9,4,4,4,11,9,1,9,8,7,7,11,4,13,13,7,7,8,8,5,5,5,9,8,8,4,11,11,7,7~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,13,13,3,3,12,12,12,7,8,9,9,4,13,13,6,6,6,11,4,4,4,4,8,1,12,12,5,10,10,3,3,3,11,11,11,6,6,12,13,13,3,3,3,3~3,3,3,3,13,13,12,6,6,11,11,11,3,3,3,10,10,5,12,12,1,8,11,6,6,6,4,4,4,4,10,1,9,8,7,12,12,12,4,3,3,13,13,10,10,5,5,5,9,8,8,2,11,11,7,7,7,9,9~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,13,13,3,3,12,12,12,7,8,9,9,4,13,13,6,6,6,11,8,1,4,4,4,4,12,12,5,10,10,3,3,3,11,11,11,6,6,12,13,13,3,3,3,3&purInit=[{type:"fs",bet:3000}]&reel_set3=3,3,3,3,13,13,12,6,6,11,11,4,9,8,3,10,10,5,12,12,1,8,11,4,4,4,6,6,6,13,12,12,3,13,13,7,12,10,10,5,5,5,9,8,8,11,11,7,7,7,9,9,13~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,12,7,6,6,6,13,13,3,12,12,4,4,4,7,8,9,1,6,11,8,1,12,12,5,10,10,3,8,9,4,11,11,6,6,12,13,13,3,3,3,3~3,3,3,3,13,13,12,6,6,11,11,4,9,8,3,10,10,5,12,12,1,8,11,6,6,6,4,4,4,13,7,12,12,3,13,13,7,12,10,10,5,5,5,9,8,8,2,11,11,7,7,7,9,9~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,12,7,6,6,6,13,13,3,4,4,4,12,12,7,8,9,1,6,11,8,1,12,12,5,10,10,3,8,9,4,11,11,6,6,12,13,13,3,3,3,3~3,3,3,3,13,13,12,6,6,11,11,4,9,8,3,10,10,5,12,12,1,8,11,6,6,6,13,7,4,4,4,12,12,3,13,13,7,12,10,10,5,5,5,9,8,8,2,11,11,7,7,7,9,9~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,12,7,6,6,6,13,13,3,12,12,7,4,4,4,8,9,1,6,11,8,1,12,12,5,10,10,3,8,9,4,11,11,6,6,12,13,13,3,3,3,3&reel_set6=3,3,3,3,13,13,12,6,6,11,11,4,9,8,3,10,10,5,12,12,1,10,4,4,4,6,6,6,11,10,1,9,8,7,12,12,3,13,13,7,12,10,10,5,5,5,9,8,8,11,11,7,7,7,9,9,13~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,12,7,13,13,3,8,2,10,10,9,4,4,4,13,13,6,6,6,11,8,7,12,12,5,10,10,3,8,9,4,11,11,6,6,12,13,13,3,3,3,3~3,3,3,3,13,13,12,6,6,11,11,4,9,8,3,10,10,5,12,12,2,5,10,6,6,6,4,4,4,11,10,7,9,8,7,12,12,6,3,13,13,7,12,10,10,5,5,5,9,8,8,2,11,11,7,7,7,9,9~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,12,7,13,13,3,8,2,10,10,9,4,4,4,13,13,6,6,6,11,8,2,12,12,5,10,10,3,8,9,4,11,11,6,6,12,13,13,3,3,3,3~3,3,3,3,13,13,12,6,6,11,11,4,9,8,3,10,10,5,12,12,2,6,6,6,5,10,4,4,4,11,10,7,6,9,8,7,12,12,3,13,13,7,12,10,10,5,5,5,9,8,8,2,11,11,7,7,7,9,9~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,12,7,13,13,3,8,2,10,10,9,4,4,4,13,13,6,6,6,11,8,2,12,12,5,10,10,3,8,9,4,11,11,6,6,12,13,13,3,3,3,3&reel_set5=3,3,3,3,10,10,12,6,6,11,11,5,8,8,3,10,10,5,12,12,1,8,11,6,6,5,5,11,10,1,8,11,5,12,12,3,3,3,11,11,10,10,10,5,5,5,8,8,8,11,11,6,6,6,10,12,12~6,6,6,13,13,5,8,6,6,11,4,4,9,7,7,7,8,6,9,9,5,13,13,1,8,11,5,9,4,4,4,11,9,1,9,8,7,7,11,4,13,13,7,7,8,8,5,5,5,9,8,8,4,11,11,7,7~9,9,7,7,7,13,13,3,3,10,10,9,4,4,10,10,12,7,13,13,3,12,12,7,9,10,4,4,4,9,13,13,7,7,7,13,13,1,12,12,3,10,10,10,13,12,4,9,9,7,12,12,13,13,3,3,3,3~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,13,13,3,3,12,12,12,7,8,10,10,9,4,13,13,4,4,4,4,6,6,6,11,8,1,12,12,5,10,10,3,3,3,11,11,11,6,6,12,13,13,3,3,3,3~3,3,3,3,13,13,12,6,6,11,11,11,3,3,3,10,10,5,12,12,1,8,11,6,6,6,4,4,4,4,10,1,9,8,7,12,12,12,4,3,3,13,13,10,10,5,5,5,9,8,8,2,11,11,7,7,7,9,9~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,13,13,3,3,12,12,12,7,8,10,10,9,4,13,13,6,6,6,11,8,1,4,4,4,4,12,12,5,10,10,3,3,3,11,11,11,6,6,12,13,13,3,3,3,3&reel_set8=3,3,3,3,13,13,12,6,6,11,11,4,9,8,3,10,10,5,12,12,7,8,4,4,4,11,10,6,9,8,7,12,12,3,6,6,6,13,13,7,12,10,10,5,5,5,9,8,8,11,11,7,7,7,9,9,13~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,12,7,13,13,3,12,2,10,10,9,4,4,4,13,13,6,6,6,11,8,2,12,12,5,10,10,3,8,9,4,11,11,6,6,12,13,13,3,3,3,3~3,3,3,3,13,13,12,6,6,11,11,4,9,8,3,10,10,5,12,12,2,8,10,4,4,4,11,6,6,6,10,7,9,8,7,12,12,3,13,13,7,12,10,10,5,5,5,9,8,8,2,11,11,7,7,7,9,9~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,12,7,13,13,7,12,2,10,10,9,4,4,4,13,13,6,6,6,11,8,1,12,12,4,10,10,5,8,9,2,11,11,6,6,12,13,13,3,3,3,3~3,3,3,3,13,13,12,6,6,11,11,4,9,8,3,10,10,5,12,12,2,8,10,4,4,4,11,6,6,6,10,7,9,8,7,12,12,3,13,13,7,12,10,10,5,5,5,9,8,8,2,11,11,7,7,7,9,9~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,12,7,13,13,3,12,2,10,10,9,4,4,4,13,13,6,6,6,11,8,2,12,12,5,10,10,3,8,9,4,11,11,6,6,12,13,13,3,3,3,3&reel_set7=3,3,3,3,13,13,12,6,6,11,11,4,9,8,3,10,10,5,12,12,7,8,11,6,6,6,4,4,4,11,10,6,9,8,7,12,12,3,13,13,7,12,10,10,5,5,5,9,8,8,11,11,7,7,7,9,9,13~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,12,7,13,13,7,12,12,3,3,9,10,10,9,4,4,4,13,13,6,6,6,11,8,1,12,12,4,10,10,5,8,9,2,11,11,6,6,12,13,13,3,3,3,3~3,3,3,3,13,13,12,6,6,11,11,4,9,8,3,10,10,5,12,12,2,8,11,6,6,6,4,4,4,11,10,7,9,8,7,12,12,3,13,13,7,12,10,10,5,5,5,9,8,8,2,11,11,7,7,7,9,9~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,12,7,13,13,3,12,12,7,8,9,10,10,9,4,4,4,13,13,6,6,6,11,8,2,12,12,5,10,10,3,8,9,4,11,11,6,6,12,13,13,3,3,3,3~3,3,3,3,13,13,12,6,6,11,11,4,9,8,3,10,10,5,12,12,2,8,11,6,6,6,4,4,4,11,10,7,9,8,7,12,12,3,13,13,7,12,10,10,5,5,5,9,8,8,2,11,11,7,7,7,9,9~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,12,7,13,13,3,12,12,7,8,9,10,10,9,4,4,4,13,13,6,6,6,11,8,2,12,12,5,10,10,3,8,9,4,11,11,6,6,12,13,13,3,3,3,3&total_bet_min=5.00';
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
                $lines = 40;      
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
                $slotEvent['slotLines'] = 40;
                $pur = -1;
                if(isset($slotEvent['pur'])){
                    $pur = $slotEvent['pur'];
                }
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
                if($pur >= 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $allBet * 75;
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
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
                $reels = [];
                $fsmore = 0;
                $str_stf = '';
                $str_slm_lmi = '';
                $str_slm_lmv = '';
                $str_slm_mp = '';
                $str_slm_mv = '';
                $fsmax = 0;
                $fsmore = 0;
                $scatterWin = 0;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $fsmore = $stack['fsmore'];
                    $fsmax = $stack['fsmax'];
                    $str_stf = $stack['stf'];
                    $str_slm_lmi = $stack['slm_lmi'];
                    $str_slm_lmv = $stack['slm_lmv'];
                    $str_slm_mp = $stack['slm_mp'];
                    $str_slm_mv = $stack['slm_mv'];
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
                    $fsmore = $stack[0]['fsmore'];
                    $fsmax = $stack[0]['fsmax'];
                    $str_stf = $stack[0]['stf'];
                    $str_slm_lmi = $stack[0]['slm_lmi'];
                    $str_slm_lmv = $stack[0]['slm_lmv'];
                    $str_slm_mp = $stack[0]['slm_mp'];
                    $str_slm_mv = $stack[0]['slm_mv'];
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
                    $spinType = 's';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $isEnd = false;
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                    {
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&w=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 'c';
                        $isEnd = true;
                    }
                    else
                    {
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&w=' . $totalWin;
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
                    $strOtherResponse = $strOtherResponse . '&w=' . $totalWin;
                    if($fsmax > 0){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='. $fsmax .'&fswin=0.00&fs=1&fsres=0.00';
                    }
                }
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $strOtherResponse = $strOtherResponse . '&puri=0';
                }
                if($pur >= 0){
                    $strOtherResponse = $strOtherResponse . '&purtr=1';
                }
                if($str_stf != ''){
                    $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                }
                if($str_slm_lmv != ''){
                    $strOtherResponse = $strOtherResponse . '&slm_lmv=' . $str_slm_lmv . '&slm_lmi=' . $str_slm_lmi;
                }
                if($str_slm_mv != ''){
                    $strOtherResponse = $strOtherResponse . '&slm_mv=' . $str_slm_mv . '&slm_mp=' . $str_slm_mp;
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                }
                
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin')  . '&reel_set='. $currentReelSet . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=4&sw=6&st=rect&c='.$betline.'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=40&s=' . $strLastReel;
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin'). ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($slotEvent['slotEvent'] == 'freespin' && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * 75;
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

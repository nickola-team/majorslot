<?php 
namespace VanguardLTE\Games\TreesofTreasurePM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'RespinWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'Bl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [6,7,2,4,9,5,12,8,6,7,3,8,10,5,10]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'IsBonusBank', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $strOtherResponse = '';
                $currentReelSet = 0;
                $stack = null;
                
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinWin', $lastEvent->serverResponse->RespinWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $lastEvent->serverResponse->CurrentRespin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bl', $lastEvent->serverResponse->Bl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'IsBonusBank', $lastEvent->serverResponse->IsBonusBank);
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
                    $currentReelSet = $stack['reel_set'];
                    $fsmore = $stack['fsmore'];
                    $str_stf = $stack['stf'];
                    $str_srf = $stack['srf'];
                    $str_rs_p = $stack['rs_p'];
                    $str_rs_c = $stack['rs_c'];
                    $str_rs_m = $stack['rs_m'];
                    $str_rs_t = $stack['rs_t'];
                    $str_rs_more = $stack['rs_more'];
                    $str_rs_iw = $stack['rs_iw'];
                    $str_rs_win = $stack['rs_win'];
                    $str_rs = $stack['rs'];
                    $strReelSa = $stack['sa'];
                    $strReelSb = $stack['sb'];
                    $str_pw = $stack['pw'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $str_mo_wpos = $stack['mo_wpos'];
                    $mo_tv = $stack['mo_tv'];
                    $str_sts = $stack['sts'];
                    $str_sty = $stack['sty'];
                    $fsmax = $stack['fsmax'];
                    $str_trail = $stack['trail'];
                    $strWinLine = $stack['win_line'];

                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                    }
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
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
                    if($str_rs_p != ''){
                        $strOtherResponse = $strOtherResponse . '&rs_p=' . $str_rs_p . '&rs_m=' . $str_rs_m . '&rs_c=' . $str_rs_c;
                    }
                    if($str_rs_t != ''){
                        $strOtherResponse = $strOtherResponse . '&rs_t=' . $str_rs_t;
                    }
                    if($str_rs_iw != ''){
                        $str_rs_iw = str_replace(',', '', $str_rs_iw) / $original_bet * $bet;
                        $strOtherResponse = $strOtherResponse . '&rs_iw=' . $str_rs_iw;
                    }
                    if($str_rs_win != ''){
                        $str_rs_win = str_replace(',', '', $str_rs_win) / $original_bet * $bet;
                        $strOtherResponse = $strOtherResponse . '&rs_win=' . $str_rs_win;
                    }
                    if($str_rs_more != ''){
                        $strOtherResponse = $strOtherResponse . '&rs_more=' . $str_rs_more;
                    }
                    if($str_rs != ''){
                        $strOtherResponse = $strOtherResponse . '&rs=' . $str_rs;
                    }
                    
                    if($str_pw != ''){
                        $str_pw = str_replace(',', '', $str_pw) / $original_bet * $bet;
                        $strOtherResponse = $strOtherResponse . '&pw=' . $str_pw;
                    }
                    if($str_mo != ''){
                        $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                    }
                    if($str_mo_wpos != ''){
                        $strOtherResponse = $strOtherResponse . '&mo_wpos=' . $str_mo_wpos;
                    }
                    if($mo_tv > 0){
                        $mo_tw = $mo_tv * $bet;
                        $strOtherResponse = $strOtherResponse . '&mo_c=1&mo_tv=' . $mo_tv . '&mo_tw=' . $mo_tw;
                    }
                    if($str_sts != ''){
                        $strOtherResponse = $strOtherResponse . '&sts=' . $str_sts;
                    }
                    if($str_sty != ''){
                        $strOtherResponse = $strOtherResponse . '&sts=' . $str_sty;
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
                $response = 'def_s=6,7,2,4,9,5,12,8,6,7,3,8,10,5,10&balance='. $Balance .'&cfgs=10703&ver=3&index=1&balance_cash='. $Balance .'&def_sb=6,10,4,3,9&reel_set_size=13&def_sa=6,11,6,3,10&reel_set=0&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"126507399",max_rnd_win:"15000",max_rnd_win_a:"10000",max_rnd_hr_a:"53604831"}}&wl_i=tbm~15000;tbm_a~10000&bl='. $slotSettings->GetGameData($slotSettings->slotId . 'Bl') .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa=6,11,6,3,10&sb=6,10,4,3,9&reel_set10=6,1,8,10,8,1,6,12,12,1,12,1,6,6,1,12,1,12,4,4,1,12,8,1,4,1,8,1,12,10,12,12,4,4,8,1,12,8,12,4,4,1,6,10,10,1,10,1,4,6,1,4~1,11,3,1,5,3,5,7,1,7,5,5,1,9,11,9,7,11,1,7,11,1,11,1,7,3,3,5,1,11,1,3,11,3,11,1,11,1,9,1,5,3,9,1,11,1,3~12,1,10,1,10,1,12,4,4,1,8,1,6,8,1,12,8,8,12,6,10,8,10,8,1,4,1,10,4,1,12,1,12,1,12,6,4,4,1,12,1,6,1,6,4,12,12,4,1,12,12,1,6,4,6~11,11,3,7,7,5,3,9,3,9,11,5,5,7,5,11,3,9,11,11,7,9,3,5,5,11,9,3,11,7,3,11,3,11,11,7,7,3,7,5,11,3,9,5~1,12,1,4,10,8,1,12,6,12,12,4,1,6,12,1,4,8,8,6,6,10,1,4,6,1,4,10,4,1,12,1,12,12,1,12,6,1,4,4,1,8,1,12,1,8,10&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=50.00&reel_set11=7,3,1,11,1,3,5,11,1,9,1,11,7,11,1,3,1,5,11,11,7,3,1,9,5,7,1,5,9,3,1,11~8,4,1,12,1,10,1,8,6,6,12,4,1,12,1,12,1,12,8,4,6,1,4,8,12,1,10,10,6,12,4~9,11,3,1,3,5,3,3,1,9,3,11,5,5,11,11,5,5,11,1,11,1,7,1,9,1,7,7,11,3,11,1,7,1,3,1,7,11,3,1,5,1,9,1,11~1,6,12,1,6,12,8,8,12,4,1,6,1,10,6,4,8,4,12,8,1,10,12,4,1,10,12,4,1,12,1~7,7,1,9,3,1,7,5,1,5,1,11,9,3,11,1,3,5,11,3,11,3,1,9,1,11,5,1,7,11,11,3,1&reel_set12=10,1,10,1,12,1,6,10,1,12,8,1,12,6,4,4,6,1,8,6,1,10,8,12,1,12,4,4,1,4,12,12,8,1,6,4,12,1,12,1,4,1,8,1,4,12~3,1,5,1,9,11,5,3,11,1,11,1,11,3,1,5,9,7,11,5,1,7,11,1,11,1,3,9,1,7,11,1,3,3,1,3,9,1,3,5,11,11,7,1,7,1,5~1,12,1,12,12,10,1,12,1,4,1,8,12,6,8,10,8,4,4,10,1,12,4,8,12,6,1,6,1,6,4,1,4~3,9,3,7,11,1,5,3,1,9,1,7,1,3,7,1,5,1,11,3,11,11,5,1,9,11,5,3,11,1,11,7~1,6,6,1,10,1,12,6,8,10,4,12,8,1,10,6,1,12,1,12,1,8,1,4,10,1,10,12,4,1,12,4,8,12,6,4,6,4,4,8,1,12,1,4,12,4,8,1,12,1,12&sh=3&wilds=2~5000,600,400,200,0~1,1,1,1,1&bonuses=0&st=rect&c='. $bet .'&sw=5&sver=5&bls=20,30&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;2000,600,400,200,0;800,320,160,80,0;320,160,80,0,0;240,80,40,0,0;160,60,20,0,0;160,60,20,0,0;80,40,15,0,0;80,40,10,0,0;40,20,10,0,0;40,20,10,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0&l=20&reel_set0=5,11,10,2,9,6,12,11,7,12,6,9,7,8,11,7,5,7,8,12,9,5,9,5,9,12,10,11,6,10,9,7,10,11,8,3,7,6,11,4,11~10,7,6,8,7,5,6,12,3,9,8,6,12,6,5,8,7,2,10,5,7,4,11,10,11,6,8,12,9,6,11,9,10,11,10,9~7,11,5,7,4,11,5,6,12,10,7,10,11,6,9,12,9,8,7,11,9,11,7,12,3,7,6,8,9,8,10,11,5,9,10,9,5,2,8,6,9~3,5,8,6,7,8,10,6,11,8,11,6,4,10,12,10,9,10,11,9,12,6,8,12,10,7,11,9,10,11,5,7,12,10,12,6,9,6,9,8,5,12,2,12,7~5,9,12,9,10,8,11,9,11,6,8,11,2,11,8,12,10,6,11,7,5,7,6,12,5,9,3,7,4,7,10&s='.$lastReelStr.'&reel_set2=4,4,10,12,10,8,6,10,12,4,8,12,8,12,12,6,12,6,6,12,4,12,6,6,6,8,10,4,6,12,12,6,6,8,10,6,4,8,6,4,4,12,10,8,6,6,8,10,10,12~5,5,11,7,3,3,11,11,9,5,5,7,9,9,11,3,9,5,11,7,5,5,5,9,5,7,3,3,11,11,7,3,9,7,11,5,5,11,11,5,5,11,9,3,3,7~10,6,6,4,12,8,12,10,12,6,6,10,6,10,12,4,6,12,6,6,4,8,4,4,6,12,6,6,6,8,10,4,4,12,12,4,6,8,8,12,12,4,10,8,12,10,12,10,6,6,12,8,12,10,6,4,8,8~9,11,5,5,3,11,7,11,5,11,11,3,7,5,9,5,9,9,11,9,7,11,11,3,11,5,7,5,9,5,5,5,3,3,11,7,5,7,5,11,7,3,5,9,7,3,3,9,7,11,9,9,11,5,11,7,3,5,11,5,3,3,5~6,6,4,10,8,10,6,4,12,6,12,4,8,10,12,10,6,10,6,8,8,4,12,10,12,4,12,4,4,6,6,6,12,8,8,10,10,12,6,6,4,4,12,6,10,6,10,8,4,6,6,4,8,12,12,6,12,12,8,6,12,8,12&reel_set1=11,11,5,7,5,11,11,5,5,7,11,3,7,9,11,11,9,3,5,3,7,5,3,5,9,9,5,5,5,11,5,7,3,11,11,5,7,7,5,5,3,3,11,5,11,11,9,3,7,9,9,11,5,3,3,7,3,9~12,4,6,10,12,6,10,4,12,4,6,8,8,12,4,4,8,10,6,8,10,6,6,6,10,4,12,4,4,12,12,6,12,12,6,6,8,6,8,4,6,12,6,8,10,12,10~11,7,3,3,5,9,11,9,11,5,5,11,9,5,11,3,5,11,5,5,5,7,11,3,9,7,5,3,5,11,7,3,5,11,9,7,7,3,3,11,5,9~4,8,6,10,8,12,4,12,8,12,4,4,12,12,10,6,10,4,4,10,8,12,4,4,6,12,8,10,6,6,6,10,4,6,4,6,6,8,6,10,10,4,8,12,6,8,6,12,6,12,12,10,6,12,8,12,12,6,4,8,12,6~5,11,7,3,11,7,11,3,11,7,3,5,5,3,9,5,5,5,9,3,9,5,5,9,11,7,11,11,7,3,11,5,3,9,5&reel_set4=9,3,9,5,11,11,9,7,5,3,1,5,7,5,9,1,7,3,1,11,11,1,11,9,3,3,1,3,9,3,5,7,11,3,11,5,11,11,7,7,3,11,5,7~8,12,8,12,12,8,8,6,4,4,12,4,8,10,4,12,8,10,10,1,8,4,12,4,12,12,10,6,12,12,10,1,6,4,1,8,4,6,6,4,10,4,1,6,10,8,10,6,6,12,4,4,12,1,6,12~5,7,9,1,5,3,11,5,7,11,5,5,7,11,3,5,11,11,3,3,7,7,9,11,9,1,7,9,11,3,1,9,3,11,9,5,3,11,1,3,7,11,3,7,1,5,11,3~12,8,10,8,4,12,6,1,12,6,8,4,4,12,4,12,1,12,6,6,10,8,10,4,1,8,10,4,6,1,10,12,4,12,12,4,8,6~3,3,11,11,7,9,1,3,11,3,11,3,1,11,9,3,5,11,5,5,9,7,7,1,9,5,7,7,5,7,11,3,9,5,11,9,5,3,5,5,11,11,3,7,7,3,9,7,9,11,11,1,11,3,11,3,11&reel_set3=11,3,10,11,12,9,3,3,3,7,12,11,8,6,5,4,4,4,7,3,4,12,3,4,11,11,5,5,5,8,4,6,7,9,7,6,6,6,7,5,6,3,12,9,8,10,7,7,7,12,12,4,7,11,4,10,8,8,8,11,9,12,5,10,7,5,5,9,9,9,7,9,9,7,4,12,4,10,10,10,6,7,8,10,6,7,9,5,11,11,11,8,6,8,10,12,10,3,12,12,12,6,8,5,11,9,7,11,8,11~10,3,7,7,8,3,3,3,4,9,12,11,5,6,7,4,4,4,11,9,7,9,6,11,5,5,5,10,5,3,4,9,12,11,6,6,6,8,6,5,8,4,3,7,7,7,10,6,3,12,6,5,12,8,8,8,7,5,10,6,7,5,9,9,9,7,11,4,11,8,8,11,10,10,10,9,9,12,7,7,4,11,11,11,12,8,7,10,11,6,8,12,12,12,9,4,11,4,12,7,12,10~10,4,7,12,11,3,3,3,4,4,10,12,11,7,4,4,4,6,12,8,9,10,4,3,5,5,5,9,12,6,11,4,7,6,6,6,11,4,6,3,8,8,7,7,7,10,9,5,5,7,3,8,8,8,6,4,7,7,9,12,11,9,9,9,10,7,12,5,12,8,10,10,10,8,9,8,8,11,7,5,11,11,11,6,5,11,11,9,7,12,12,12,11,9,6,6,10,5,12,3~6,4,5,11,10,3,3,3,4,8,6,11,12,8,10,4,4,4,9,10,6,5,8,11,5,5,5,7,12,4,11,9,7,11,6,6,6,11,3,4,11,12,7,7,7,4,9,8,6,3,6,12,8,8,8,3,7,7,10,3,7,9,9,9,6,8,9,11,4,8,7,10,10,10,7,10,7,12,7,9,11,11,11,12,4,10,5,8,12,11,12,12,12,9,5,12,6,5,7,9,5~9,11,6,6,4,3,3,3,6,9,9,11,8,4,11,4,4,4,12,7,12,7,10,3,5,5,5,11,6,12,6,6,7,8,6,6,6,10,8,10,4,11,8,7,7,7,9,10,7,4,8,3,11,8,8,8,9,10,5,9,7,3,9,9,9,8,4,11,12,12,5,7,10,10,10,7,4,11,3,7,12,11,11,11,7,7,4,6,12,5,9,12,12,12,7,5,10,5,5,8,11,12&reel_set6=16,16,16~16,16,16~16,16,16~16,16,16~16,16,16&reel_set5=1,12,4,1,10,10,8,4,6,4,12,10,8,6,1,4,6,4,12,1,8,4,8,10,6,1,6,10,10,10,1,10,4,1,12,1,10,6,4,6,8,4,1,8,4,12,4,6,12,1,6,6,1,10,8,12,1,6,12~7,1,9,9,5,7,7,5,1,5,1,9,5,3,11,3,3,1,11,1,7,9,1,7,7,9,9,9,3,9,3,7,5,11,5,1,11,5,3,5,1,11,5,3,9,1,5,9,11,3,9,3,1,11,1,11,1,3~1,10,8,1,6,12,6,4,12,1,12,6,8,4,10,6,4,6,10,4,1,10,4,12,1,8,1,12,10,10,10,6,4,1,6,8,12,6,1,12,6,10,1,4,1,10,6,4,4,10,10,8,12,1,8,6,4,1,8,1,4~3,9,1,7,1,5,5,1,5,3,1,11,1,11,3,9,9,9,1,3,1,3,5,9,1,11,5,5,7,7,11,9,11,1,7,3,9~1,6,12,10,12,10,10,1,10,1,4,10,12,4,12,6,6,4,8,1,6,8,12,1,6,10,10,10,4,1,8,1,10,1,8,4,4,1,6,6,10,1,12,1,6,12,1,8,4,6,4,6,8,4,1,4&reel_set8=12,12,1,12,1,12,6,1,4,6,1,6,10,10,6,4,1,8,1,4,12,1,4,12,8,1,12,8,12,12,4,1,6,10,4,4,1,4,8,10,1,10,8,1,4,1,12,6,1,12,8~9,7,3,9,11,7,3,3,11,5,7,7,5,5,9,3,5,11,11,3,5,7,11,11,3,11,3,9,11,3,11,9,9,5,11,11,5,3,5,9,3,7,11,7,11,11,3,7,7,5,3,5,7~10,1,6,12,4,4,8,6,12,12,4,1,8,10,4,1,4,8,1,6,12,1,8,12,1,12,1,4,6,1,10,1,12~7,11,9,11,11,3,7,7,3,5,3,7,3,11,3,9,11,9,11,5,7,5,5,3,7,3,9,7,11,3,11,3,9,3,5,5,11,9,3,11,5,9,7,5,11,5,3,7,5,11,11,3,11,7~1,4,12,1,10,10,4,1,6,1,12,4,8,4,1,8,12,4,1,8,12,1,8,12,6,6,12,1,4,12,10,12,1,6&reel_set7=1,5,3,5,1,7,11,1,7,5,3,1,9,11,11,1,3,1,3,1,11,1,11,9,7,11,9,1,11,3,7,5,11,1,3~12,10,12,12,4,10,12,8,6,10,4,8,6,12,12,4,6,12,4,6,10,12,10,12,8,4,6,12,4,4,6,6,8,4,4,10,8,4,4,6,4,4,10,12,8,6,12,8,8,12,12,6,12,10,8,8~3,7,1,5,1,3,9,1,11,11,1,3,1,3,1,5,11,11,7,1,7,3,1,11,1,11,3,9,9,7,1,9,7,11,3,7,1,5,1,3,5,3,5,1,9,11,11,3,11,11,1,5,11,5,1~6,10,8,10,4,10,12,12,4,12,10,12,6,6,10,12,6,4,12,4,4,8,6,4,12,8,8,6,4,8,8,6,4,12,12,8~3,3,7,11,3,9,1,11,1,9,1,5,11,1,7,1,7,1,11,1,11,11,1,5,5,1,9,3,7,5,3,11,1,7,11,11,9,5,1,3,11,3,1,3,1,5,5,1,11,3,9,7,3,11,1&reel_set9=11,1,7,7,3,1,5,7,1,3,11,3,11,9,3,1,3,3,1,9,1,9,1,7,11,3,1,7,1,5,11,11,1,5,1,3,1,11,5,11,1,3,9,11,1,5,1,11,11,5,9,7,1~10,8,10,6,10,4,8,12,4,12,12,4,4,6,6,12,8,10,4,8,4,6,10,12,6,12,6,8,10,12,6,12,8,12,8,8,6,4,4,12,12,4,4~5,3,1,7,11,1,11,11,3,1,7,1,5,1,3,1,9,11,1,5,11,3,9,11,3,11,5,11,5,1,7,3,3,5,7,9,1,9,7,1,11,1,3,11~1,4,6,1,4,10,12,6,8,4,10,8,4,1,8,12,4,1,4,1,12,1,12,6,1,12,10,1,12,8,12,4,1,12,6,1,12,4,1,6,12,6,1,10,8,1~7,1,11,11,3,1,3,5,5,1,11,1,9,11,3,11,11,3,5,1,7,7,11,5,9,1,5,1,3,7,1,3,3,1,7,9,1,5,1,9,3,1,11,1,11,1,11';
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
                $bl = $slotEvent['bl'];
                $slotEvent['slotLines'] = 20;
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    $slotEvent['slotEvent'] = 'freespin';
                }
                $lines = $slotEvent['slotLines'];
                $betline = $slotEvent['slotBet'];
                if( $slotEvent['slotEvent'] == 'doSpin' || $slotEvent['slotEvent'] == 'freespin' || $slotEvent['slotEvent'] == 'respin' ) 
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
                $purMuls = [100, 60, 140, 350];
                if($pur >= 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $allBet * $purMuls[$pur];
                }else if($bl > 0){
                    $allBet = $betline * 30;
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
                    if($winType == 'bonus'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'IsBonusBank', 1);
                    }else{
                        $slotSettings->SetGameData($slotSettings->slotId . 'IsBonusBank', 0);
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bl', $bl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinWin', 0);
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
                $str_initReel = '';
                $fsmore = 0;
                $str_stf = 0;
                $str_srf = 0;
                $str_trail = 0;
                $fsmore = 0;
                $str_rs_p = '';
                $str_rs_c = '';
                $str_rs_m = '';
                $str_rs_t = '';
                $str_rs_more = '';
                $str_rs_iw = '';
                $str_rs_win = '';
                $str_rs = '';
                $fsmax = 0;
                $strReelSa = '';
                $strReelSb = '';
                $str_pw = '';
                $str_mo = '';
                $str_mo_t = '';
                $mo_tv = 0;
                $str_mo_wpos = '';
                $str_sts = '';
                $str_sty = '';
                if($slotEvent['slotEvent'] == 'freespin' || $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') >= 0){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_initReel = $stack['initreel'];
                    $currentReelSet = $stack['reel_set'];
                    $fsmore = $stack['fsmore'];
                    $str_stf = $stack['stf'];
                    $str_srf = $stack['srf'];
                    $str_rs_p = $stack['rs_p'];
                    $str_rs_c = $stack['rs_c'];
                    $str_rs_m = $stack['rs_m'];
                    $str_rs_t = $stack['rs_t'];
                    $str_rs_more = $stack['rs_more'];
                    $str_rs_iw = $stack['rs_iw'];
                    $str_rs_win = $stack['rs_win'];
                    $str_rs = $stack['rs'];
                    $strReelSa = $stack['sa'];
                    $strReelSb = $stack['sb'];
                    $str_pw = $stack['pw'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $str_mo_wpos = $stack['mo_wpos'];
                    $mo_tv = $stack['mo_tv'];
                    $str_sts = $stack['sts'];
                    $str_sty = $stack['sty'];
                    $fsmax = $stack['fsmax'];
                    $str_trail = $stack['trail'];
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
                    $str_stf = $stack[0]['stf'];
                    $str_srf = $stack[0]['srf'];
                    $str_rs_p = $stack[0]['rs_p'];
                    $str_rs_c = $stack[0]['rs_c'];
                    $str_rs_m = $stack[0]['rs_m'];
                    $str_rs_t = $stack[0]['rs_t'];
                    $str_rs_more = $stack[0]['rs_more'];
                    $str_rs_iw = $stack[0]['rs_iw'];
                    $str_rs_win = $stack[0]['rs_win'];
                    $str_rs = $stack[0]['rs'];
                    $strReelSa = $stack[0]['sa'];
                    $strReelSb = $stack[0]['sb'];
                    $str_pw = $stack[0]['pw'];
                    $str_mo = $stack[0]['mo'];
                    $str_mo_t = $stack[0]['mo_t'];
                    $str_mo_wpos = $stack[0]['mo_wpos'];
                    $mo_tv = $stack[0]['mo_tv'];
                    $str_sts = $stack[0]['sts'];
                    $str_sty = $stack[0]['sty'];
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
                $mo_tw = 0;
                if($mo_tv > 0){
                    $mo_tw = $mo_tv * $betline;
                    $totalWin = $totalWin + $mo_tw;
                }

                $spinType = 's';
                if( $totalWin > 0) 
                {
                    $spinType = 'c';
                    $slotSettings->SetBalance($totalWin);
                    if($slotSettings->GetGameData($slotSettings->slotId . 'IsBonusBank') > 0){
                        $slotSettings->SetBank(('bonus'), -1 * $totalWin);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'IsBonusBank', 0);
                }else if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                }
                // $reelA = [];
                // $reelB = [];
                // for($i = 0; $i < 5; $i++){
                //     $reelA[$i] = mt_rand(4, 8);
                //     $reelB[$i] = mt_rand(4, 8);
                // }
                // $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                // $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
                $strLastReel = implode(',', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                if($str_rs_p == ''){
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                }else{
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', 0);
                }
                
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
                    $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                }
                if($pur >= 0){
                    $strOtherResponse = $strOtherResponse . '&purtr=1';
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
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
                if($str_rs_p != ''){
                    $isState = false;
                    $spinType = 's';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $strOtherResponse = $strOtherResponse . '&rs_p=' . $str_rs_p . '&rs_m=' . $str_rs_m . '&rs_c=' . $str_rs_c;
                }
                if($str_rs_t != ''){
                    $strOtherResponse = $strOtherResponse . '&rs_t=' . $str_rs_t;
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0){
                        $isState = true;
                        $spinType = 'c';
                    }
                }
                if($str_rs_iw != ''){
                    $str_rs_iw = str_replace(',', '', $str_rs_iw) / $original_bet * $betline;
                    $strOtherResponse = $strOtherResponse . '&rs_iw=' . $str_rs_iw;
                }
                if($str_rs_win != ''){
                    $str_rs_win = str_replace(',', '', $str_rs_win) / $original_bet * $betline;
                    $strOtherResponse = $strOtherResponse . '&rs_win=' . $str_rs_win;
                }
                if($str_rs_more != ''){
                    $strOtherResponse = $strOtherResponse . '&rs_more=' . $str_rs_more;
                }
                if($str_rs != ''){
                    $strOtherResponse = $strOtherResponse . '&rs=' . $str_rs;
                }
                if($str_pw != ''){
                    $str_pw = str_replace(',', '', $str_pw) / $original_bet * $betline;
                    $strOtherResponse = $strOtherResponse . '&pw=' . $str_pw;
                }
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                }
                if($str_mo_wpos != ''){
                    $strOtherResponse = $strOtherResponse . '&mo_wpos=' . $str_mo_wpos;
                }
                if($mo_tv > 0){
                    $strOtherResponse = $strOtherResponse . '&mo_c=1&mo_tv=' . $mo_tv . '&mo_tw=' . $mo_tw;
                }
                if($str_sts != ''){
                    $strOtherResponse = $strOtherResponse . '&sts=' . $str_sts;
                }
                if($str_sty != ''){
                    $strOtherResponse = $strOtherResponse . '&sts=' . $str_sty;
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance . '&reel_set='. $currentReelSet.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&bl='.$slotSettings->GetGameData($slotSettings->slotId . 'Bl') .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&st=rect&c='.$betline.'&sw=5&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&w='.$totalWin.'&s=' . $strLastReel;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) && $str_rs_p == '') 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if( $slotEvent['slotEvent'] != 'freespin' && $fsmax > 0 && $str_rs_p == '') 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"CurrentRespin":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin'). ',"IsBonusBank":' . $slotSettings->GetGameData($slotSettings->slotId . 'IsBonusBank') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RespinWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"Bl":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bl') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if(($slotEvent['slotEvent'] == 'freespin' || ($str_rs_p == '' && $str_rs_t != '')) && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * $purMuls[$slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin')];
                }else if($slotSettings->GetGameData($slotSettings->slotId . 'Bl') > 0 && $isState == true){
                    $allBet = $betline * 15;
                }
                if($str_rs_p == '' && $str_rs_t != '' && $isState == true){
                    $slotEvent['slotEvent'] == 'doRespin';
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

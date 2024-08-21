<?php 
namespace VanguardLTE\Games\CleocatraPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [6,13,12,5,9,7,8,7,7,10,5,10,10,6,13,4,11,8,4,11]);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', $lastEvent->serverResponse->BonusState);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $lastEvent->serverResponse->CurrentRespin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
                    if (isset($lastEvent->serverResponse->ReplayGameLogs)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', json_decode(json_encode($lastEvent->serverResponse->ReplayGameLogs), true)); //ReplayLog
                    }
                    if (isset($lastEvent->serverResponse->FreeStacks)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', json_decode(json_encode($lastEvent->serverResponse->FreeStacks), true)); // FreeStack
                        $FreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
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
                $currentReelSet = 0;
                $spinType = 's';
                $fsmore = 0;
                if($stack != null){
                    $currentReelSet = $stack['reel_set'];
                    $str_initReel = $stack['initReel'];
                    $arr_slm_mp = [];
                    if($stack['slm_mp'] != ''){
                        $arr_slm_mp = explode(',', $stack['slm_mp']);
                    }
                    $arr_slm_mv = [];
                    if($stack['slm_mv'] != ''){
                        $arr_slm_mv = explode(',', $stack['slm_mv']);
                    }
                    $str_lmi = $stack['lmi'];
                    $str_lmv = $stack['lmv'];
                    $str_sts = $stack['sts'];
                    $str_sty = $stack['sty'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $rs_more = $stack['rs_more'];
                    $fsmore = $stack['fsmore'];

                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if(count($arr_slm_mp) > 0){
                        $strOtherResponse = $strOtherResponse . '&slm_mp=' . implode(',', $arr_slm_mp);
                    }
                    if(count($arr_slm_mv) > 0){
                        $strOtherResponse = $strOtherResponse . '&slm_mv=' . implode(',', $arr_slm_mv);
                    }
                    if($str_lmi != ''){
                        $strOtherResponse = $strOtherResponse . '&lmi=' . $str_lmi;
                    }
                    if($str_lmv != ''){
                        $strOtherResponse = $strOtherResponse . '&lmv=' . $str_lmv;
                    }
                    if($str_sts != ''){
                        $strOtherResponse = $strOtherResponse . '&sts=' . $str_sts;
                    }
                    if($str_sty != ''){
                        $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                    }
                    if($rs_p >= 0){
                        $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p;
                    }
                    if($rs_c > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_c=' . $rs_c;
                    }
                    if($rs_m > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_m=' . $rs_m;
                    }
                    if($rs_t > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t;
                    }
                    if($rs_more > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_more=' . $rs_more;
                    }
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 == $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame')) 
                    {
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsend_total=0';
                    }
                    else
                    {
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                    if($fsmore > 0 ){
                        $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                    }
                }else if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0 && $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') >= 0){
                    $strOtherResponse = $strOtherResponse . '&fs_total='.($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 1).'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsend_total=1';
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
   
                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=6,13,12,5,9,7,8,7,7,10,5,10,10,6,13,4,11,8,4,11&balance='. $Balance .'&cfgs=5523&ver=3&index=1&balance_cash='. $Balance .'&def_sb=4,11,6,4,10&reel_set_size=5&def_sa=4,10,13,7,12&reel_set='.$currentReelSet.$strOtherResponse.'&balance_bonus=0.00&na=s&scatters=1~5,5,5,0,0~0,0,0,0,0~1,1,1,1,1&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"950000",max_rnd_win:"5000"}}&wl_i=tbm~5000&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=4,10,13,7,12&sb=4,11,6,4,10&sc='. implode(',', $slotSettings->Bet) .'&defc=50.00&purInit_e=1&sh=4&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&st=rect&c='.$bet.'&sw=5&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;400,100,30,0,0;250,75,25,0,0;150,40,15,0,0;100,25,10,0,0;75,15,7,0,0;50,10,5,0,0;30,6,3,0,0;30,6,3,0,0;20,5,2,0,0;20,5,2,0,0;20,5,2,0,0&l=20&total_bet_max=10,000,000.00&reel_set0=4,11,8,3,9,10,12,8,5,6,12,9,13,7,1,5,5,5,5,10,7,11,7,4,11,8,13,11,12,4,11,5,13,8,10,3,3,3,3,4,13,4,8,7,11,5,9,12,12,13,10,11,13,10,3,4,4,4,4,12,10,9,7,12,10,7,11,13,13,8,9,13,10,11,7,3,6,6,6,6,8,6,11,3,13,8,12,9,10,9,6,12,9,5,10,6,5,12~7,2,12,11,10,12,9,7,11,5,12,4,3,4,11,12,13,6,5,9,7,13,11,3,6,2,13,8,11,13,6,12,11,12,9,6,4,9,9,10,13,6,9,7,13,12,8,5,10,9,9,11,8,12,7,3,12,8,1,7,11,8,13,8,10,13,12,6,7,12,11,8,10,10,9,13,10,5~10,3,8,11,13,6,9,8,10,7,13,8,1,9,10,13,5,4,10,11,12,8,5,9,11,7,5,13,5,13,9,11,8,7,11,8,2,10,4,2,11,6,12,13,3,12,6,4,7,9,6,9,5,12,12,6~13,13,9,4,8,6,1,12,11,12,8,7,8,11,10,10,7,10,9,13,10,8,2,7,11,9,3,10,12,6,9,12,6,13,12,11,10,4,5,8,13,5,11,13,11,6,7,12,5,10,3,4,5,13,6,7,11,9~9,12,13,13,10,12,10,7,10,13,11,4,13,5,11,4,12,9,10,5,10,6,12,6,7,6,11,13,11,11,3,9,4,12,6,13,3,6,9,7,9,13,4,1,10,12,8,13,11,13,5,11,5,7,10,10,9,8,6,5,3,7,8,9,3,2,4,3,9,11,7,5,8,12,9,8,1,5,13,7,8,7,6,4,13,11,12&s='.$lastReelStr.'&reel_set2=8,6,9,6,7,10,4,8,10,13,12,6,10,5,5,5,5,8,13,7,9,9,4,12,12,13,10,3,9,7,13,11,3,3,3,3,11,10,5,7,8,13,12,7,4,10,13,3,10,13,13,4,4,4,4,10,13,12,5,11,12,12,11,7,12,6,9,12,3,6,6,6,6,9,4,5,11,9,4,11,5,8,11,10,8,11,5,8,11~12,13,13,6,7,13,10,7,3,6,8,9,5,8,12,10,10,8,11,7,12,12,13,7,4,2,5,9,11,12,5,13,3,2,12,9,11,11,9,9,4,8,11,6,10~11,13,7,5,11,2,8,12,4,5,2,8,9,13,9,11,6,3,9,10,8,11,6,8,10,8,6,13,5,9,10,4,12,13,5,8,10,11,11,12,4,12,10,11,7,13,5,7,9,3,12,13,10,9,6,10,2,12,7,9,12,11,7,13,13,9,3,8~11,7,13,2,11,5,2,12,13,13,12,2,8,10,9,5,7,12,10,10,12,13,12,5,9,13,7,6,11,7,10,11,10,3,11,8,13,8,9,6,10,13,4,6,9,6,7,10,3,6,11,4,8,4,11,5~8,7,10,6,8,9,4,13,11,13,5,13,13,8,6,11,13,11,13,12,5,2,11,6,10,10,3,5,7,5,11,10,7,5,4,11,13,7,6,13,7,3,10,10,9,8,12,3,9,3,8,9,4,5,12,2,12,9,7,6,9,11,4,12,12,9,6&reel_set1=7,12,8,13,11,6,4,11,12,6,11,13,9,13,13,6,12,8,10,11,9,10,11,9,5,10,10,8,11,9,9,4,12,13,10,6,8,11,10,11,6,12,8,5,11,5,10,10,7,4,12,3,9,9,7,8,7,10,7,5,11,13,8,13,3,8,9,7,1,3,10,13,8,7~9,12,13,9,6,11,10,1,4,10,11,8,13,7,5,8,6,12,13,3,13,2,4,9,6,12,12,11,6,9,12,12,4,7,8,9,8,9,10,5,9,13,7,2,12,11,13,7,2,5,9,10,8,11,12,13,3,10,11,12,5,6,7,13~5,8,11,11,2,11,9,8,13,1,9,10,5,7,11,7,3,4,8,9,13,12,9,8,6,7,3,8,6,13,6,2,13,6,11,12,3,12,10,13,7,12,8,13,5,6,4,9,9,13,9,8,10,1,9,5,7,5,8,10,9,12,12,10,4,12,7,11,8,11~2,13,10,13,6,8,10,9,9,13,13,8,5,2,8,11,7,6,11,13,3,9,7,6,13,10,12,11,10,1,5,4,8,3,9,7,11,7,11,10,10,6,9,12,10,4,7,10,13,11,12,5,12,5,12,6,11,4,13,12,11,7~12,13,4,9,10,8,2,9,5,10,9,11,7,4,1,11,6,1,9,13,2,11,13,6,13,7,3,6,12,12,8,2,9,7,5,6,4,13,3,8,9,10,5,11,8,13,4,10,8,13,4,12,11,5,7,12,5,3,1,10,8,11,11,12,13,7,10,7,3,13,9,6,2,9,11,5&reel_set4=4,6,10,8,6,12,6,4,10,12,10,4,10,10,12,8,8,6,8,8,10,10,8,6,10,8,12,10,4,8,8,12,12~9,9,13,3,7,9,7,9,9,5,9,5,11,11,3,13,13,11,7,13,7,11,5,11,13,13,7~2,7,6,12,7,9,6,3,13,11,11,13,4,12,12,8,5,11,6,10,7,8,5,11,8,10,9,13,8,4,5,9,9,13,7,5,11,13,8,9,12,4,12,10,3~8,13,12,3,8,11,9,6,13,10,7,9,13,5,9,11,8,12,10,2,10,11,9,8,12,12,13,3,13,7,11,4,7,13,10,4,6,10,6,10,11,12,8,12,11,5,8,10,13,7,13,7,6,5,2,9,6,7,11,4,11,10~5,7,13,5,7,13,2,10,12,12,4,5,6,4,11,13,13,10,5,9,10,2,10,9,12,7,10,10,9,9,13,3,9,8,11,12,11,8,11,3,6,13,3,12,5,8,9,7,13,13,11,11,9,10,6,13,8,7,11,6,12,6,2,13,4,8,11,8,9,7,5,12,13,7,4,2,9,7,6,3,5,11,8,5,12,4,2,13,10,6,11,9&purInit=[{bet:2000,free_spins:"8,12,16",type:"free_spins"}]&reel_set3=11,10,10,7,8,7,9,11,11,12,9,12,13,9,9,13,4,10,13,12,7,8,9,8,10,6,11,7,13,8,11,12,9,13,8,10,12,10,12,11,13,13,10,5,8,12,11,7~9,8,13,9,11,13,4,11,13,9,9,7,12,7,12,11,12,10,10,11,12,10,11,9,8,2,13,3,6,12,13,2,4,13,3,8,9,5,11,13,7,9,6,8,7,6,8,5,12,12,11,5,6,10,2,10,10,12~10,4,9,2,7,8,12,8,9,12,8,12,13,6,3,5,13,12,13,9,8,13,11,2,4,11,8,13,7,12,10,10,9,5,8,3,6,7,13,9,11,5,3,9,4,6,11,5,12,11,11,6,10,10,13,12,11,7,5,8,9,9,12,11,7,11,2,5,13,8,10,7,4,13,9,9,10,6,10,8,11,13,6~13,2,6,10,13,6,11,3,10,10,12,4,6,13,5,11,6,9,8,4,7,12,10,7,3,10,8,6,7,8,9,9,6,8,5,7,12,11,11,10,13,9,9,2,8,12,13,13,11,2,11,10,10,11,13,5,7,8,11,7,12,12,5,4,13~13,4,8,4,7,6,10,11,6,8,7,10,6,2,8,5,4,9,3,9,7,11,4,11,8,10,13,11,12,13,13,5,12,10,5,9,13,3,11,9,9,2,11,7,6,12,7,10,13,13,9,12,8,6,12,5,3&total_bet_min=10.00';
            }
            else if( $slotEvent['slotEvent'] == 'doCollect' || $slotEvent['slotEvent'] == 'doCollectBonus') 
            {
                $Balance = $slotSettings->GetBalance();
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);    
                $response = 'balance=' . $Balance . '&index=' . $slotEvent['index'] . '&balance_cash=' . $Balance . '&balance_bonus=0.00&na=s&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&na=s&sver=5&counter=' . ((int)$slotEvent['counter'] + 1);
                
                //------------ ReplayLog ---------------                
                $lastEvent = $slotSettings->GetHistory();
                if($lastEvent  != 'NULL'){
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
                $linesId = [];
                $linesId[0] = [1,1,1,1,1];
                $linesId[1] = [2,2,2,2,2];
                $linesId[2] = [3,3,3,3,3];
                $linesId[3] = [4,4,4,4,4];
                $linesId[4] = [1,2,1,2,1];
                $linesId[5] = [2,1,2,1,2];
                $linesId[6] = [2,3,2,3,2];
                $linesId[7] = [3,2,3,2,3];
                $linesId[8] = [3,4,3,4,3];
                $linesId[9] = [4,3,4,3,4];
                $linesId[10] = [1,4,1,4,1];
                $linesId[11] = [4,1,4,1,4];
                $linesId[12] = [1,2,3,2,1];
                $linesId[13] = [2,3,4,3,2];
                $linesId[14] = [3,2,1,2,3];
                $linesId[15] = [4,3,2,3,4];
                $linesId[16] = [1,1,2,1,1];
                $linesId[17] = [2,2,3,2,2];
                $linesId[18] = [3,3,4,3,3];
                $linesId[19] = [4,4,1,4,4];
                $linesId[20] = [4,4,3,4,4];
                $linesId[21] = [3,3,2,3,3];
                $linesId[22] = [2,2,1,2,2];
                $linesId[23] = [1,1,4,1,1];
                $linesId[24] = [1,2,2,2,1];
                $linesId[25] = [2,3,3,3,2];
                $linesId[26] = [3,4,4,4,3];
                $linesId[27] = [4,1,1,1,4];
                $linesId[28] = [4,3,3,3,4];
                $linesId[29] = [3,2,2,2,3];
                $linesId[30] = [2,1,1,1,2];
                $linesId[31] = [1,4,4,4,1];
                $linesId[32] = [2,4,2,4,2];
                $linesId[33] = [1,3,1,3,1];
                $linesId[34] = [3,1,3,1,3];
                $linesId[35] = [4,2,4,2,4];
                $linesId[36] = [4,3,2,1,2];
                $linesId[37] = [1,2,3,4,3];
                $linesId[38] = [4,1,2,1,4];
                $linesId[39] = [1,4,3,4,1];
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
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames')+1 < $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotEvent['slotEvent'] == 'freespin' ) 
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
                
                $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $betline * $lines / 2, $lines);
                $winType = $_spinSettings[0];
                $_winAvaliableMoney = $_spinSettings[1];

                $allBet = $betline * $lines;
                if($pur >= 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $allBet * 100;
                }
                $freeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'freespin' || $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') >= 0){
                    if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') == -1){
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    }
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '4174458' . substr($roundstr, 4, 10);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $leftFreeGames = 0;
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
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
                $str_lmi = '';
                $str_lmv = '';
                $arr_slm_mp = [];
                $arr_slm_mv = [];
                $str_sts = '';
                $str_sty = '';
                $rs_p = -1;
                $rs_c = 0;
                $rs_m = 0;
                $rs_t = 0;
                $rs_more = 0;
                $fsmore = 0;
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $str_initReel = $stack['initReel'];
                    if($stack['slm_mp'] != ''){
                        $arr_slm_mp = explode(',', $stack['slm_mp']);
                    }
                    if($stack['slm_mv'] != ''){
                        $arr_slm_mv = explode(',', $stack['slm_mv']);
                    }
                    $str_lmi = $stack['lmi'];
                    $str_lmv = $stack['lmv'];
                    $str_sts = $stack['sts'];
                    $str_sty = $stack['sty'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $rs_more = $stack['rs_more'];
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
                    if($stack[0]['slm_mp'] != ''){
                        $arr_slm_mp = explode(',', $stack[0]['slm_mp']);
                    }
                    if($stack[0]['slm_mv'] != ''){
                        $arr_slm_mv = explode(',', $stack[0]['slm_mv']);
                    }
                    $str_lmi = $stack[0]['lmi'];
                    $str_lmv = $stack[0]['lmv'];
                    $str_sts = $stack[0]['sts'];
                    $str_sty = $stack[0]['sty'];
                    $rs_p = $stack[0]['rs_p'];
                    $rs_c = $stack[0]['rs_c'];
                    $rs_m = $stack[0]['rs_m'];
                    $rs_t = $stack[0]['rs_t'];
                    $rs_more = $stack[0]['rs_more'];
                    $fsmore = $stack[0]['fsmore'];
                }
                $reels = [];
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;
                $wildReels = [];
                for($i = 0; $i < 5; $i++){
                    $reels[$i] = [];
                    $wildReels[$i] = [];
                    for($j = 0; $j < 4; $j++){
                        $reels[$i][$j] = $lastReel[$j * 5 + $i];
                        $wildReels[$i][$j] = 0;
                        if($lastReel[$j * 5 + $i] == $scatter){
                            $scatterCount++;
                            $scatterPoses[] = $j * 5 + $i;   
                        }
                    }
                }
                if(count($arr_slm_mp) > 0){
                    for($i = 0; $i < count($arr_slm_mp); $i++){
                        $wildReels[$arr_slm_mp[$i] % 5][floor($arr_slm_mp[$i] / 5)] = $arr_slm_mv[$i];
                    }
                }
                $_lineWinNumber = 1;
                $_obf_winCount = 0;
                for( $k = 0; $k < 40; $k++ ) 
                {
                    $_lineWin = '';
                    $firstEle = $reels[0][$linesId[$k][0] - 1];
                    $lineWinNum[$k] = 1;
                    $lineWins[$k] = 0;
                    $mul = $wildReels[0][$linesId[$k][0] - 1];
                    for($j = 1; $j < 5; $j++){
                        $ele = $reels[$j][$linesId[$k][$j] - 1];
                        if($firstEle == $wild){
                            $firstEle = $ele;
                            $lineWinNum[$k] = $lineWinNum[$k] + 1;
                        }else if($ele == $firstEle || $ele == $wild){
                            if($ele == $wild){
                                $mul = $mul + $wildReels[$j][$linesId[$k][$j] - 1];
                            }
                            $lineWinNum[$k] = $lineWinNum[$k] + 1;
                            if($j == 4){
                                $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                if($mul > 1){
                                    $lineWins[$k] = $lineWins[$k] * $mul;
                                }
                                $totalWin += $lineWins[$k];
                                $_obf_winCount++;
                                $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                    $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                }
                            }
                        }else{
                            if($slotSettings->Paytable[$firstEle][$lineWinNum[$k]] > 0){
                                $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                if($mul > 1){
                                    $lineWins[$k] = $lineWins[$k] * $mul;
                                }
                                $totalWin += $lineWins[$k];
                                $_obf_winCount++;
                                $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                    $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                }   

                            }else{
                                $lineWinNum[$k] = 0;
                            }
                            break;
                        }
                    }
                }
                if($scatterCount >= 3){
                    $scatterWin = 5 * $betline * $lines;
                    $totalWin = $totalWin + $scatterWin; 
                }
                
                $spinType = 's';
                if( $totalWin > 0) 
                {
                    $spinType = 'c';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }
                $_obf_totalWin = $totalWin;
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $rs_p);
                if( $scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin') 
                {
                    if( $rs_p == -1 ) 
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                        $freeNums = [0, 0, 0, 8, 12, 16];
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freeNums[$scatterCount]);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                    }
                }
                if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                }

                $strLastReel = implode(',', $lastReel);
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 5; $i++){
                    $reelA[$i] = mt_rand(8, 10);
                    $reelB[$i] = mt_rand(8, 10);
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
                        $strOtherResponse = '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        if($rs_p >= 0){
                            $strOtherResponse = $strOtherResponse . '&fsend_total=0';
                            $isState = false;
                            $spinType = 's';
                        }else{
                            $strOtherResponse = $strOtherResponse . '&fsend_total=1';
                            $spinType = 'c';
                            $isEnd = true;
                        }
                        if($str_sty != ''){
                            $arr_sty = explode('~', $str_sty);
                            if(count($arr_sty) < count($arr_slm_mp)){
                                $arr_pw = [];
                                $arr_initReel = explode(',', $str_initReel);
                                for($i = 0; $i < count($arr_slm_mp); $i++){
                                    $is_new = true;
                                    for($k = 0; $k < count($arr_sty); $k++){
                                        if($arr_slm_mp[$i] . ',-1' == $arr_sty[$k]){
                                            $is_new == false;
                                            break;
                                        }
                                    }
                                    if($is_new == true){
                                        $arr_pw[] = $arr_initReel[$arr_slm_mp[$i]] . '~' . $lastReel[$arr_slm_mp[$i]] . '~' . $arr_slm_mp[$i];
                                    }
                                }
                                if(count($arr_pw) > 0){
                                    $strOtherResponse = $strOtherResponse . '&stf=pw:' . implode(';', $arr_pw);
                                }
                            }
                        }
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
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                    if($scatterCount >= 3 ){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse = '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fs=1&fsres=0.00&psym=1~' . $scatterWin . '~' . implode(',', $scatterPoses);
                        if($pur >= 0){
                            $strOtherResponse = $strOtherResponse . '&purtr=1&puri=' . $pur;
                        }
                    }
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if(count($arr_slm_mp) > 0){
                    $strOtherResponse = $strOtherResponse . '&slm_mp=' . implode(',', $arr_slm_mp);
                }
                if(count($arr_slm_mv) > 0){
                    $strOtherResponse = $strOtherResponse . '&slm_mv=' . implode(',', $arr_slm_mv);
                }
                if($str_lmi != ''){
                    $strOtherResponse = $strOtherResponse . '&lmi=' . $str_lmi;
                }
                if($str_lmv != ''){
                    $strOtherResponse = $strOtherResponse . '&lmv=' . $str_lmv;
                }
                if($str_sts != ''){
                    $strOtherResponse = $strOtherResponse . '&sts=' . $str_sts;
                }
                if($str_sty != ''){
                    $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                }
                if($rs_p >= 0){
                    $spinType = 's';
                    $isState = false;
                    $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p;
                }
                if($rs_c > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_c=' . $rs_c;
                }
                if($rs_m > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_m=' . $rs_m;
                }
                if($rs_t > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t;
                }
                if($rs_more > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_more=' . $rs_more;
                }
                
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .$strWinLine .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=4&c='.$betline.'&sver=5&reel_set='.$currentReelSet.'&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&s='.$strLastReel .'&w='.$totalWin;
                
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) && $rs_p == -1) 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"CurrentRespin":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
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

            }
            if($slotEvent['action'] == 'doSpin' || $slotEvent['action'] == 'doCollect' || $slotEvent['action'] == 'doCollectBonus'){
                $this->saveGameLog($slotEvent, $response, $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'), $slotSettings);
            }
            try{
                $slotSettings->SaveGameData();
                \DB::commit();
            }catch (\Exception $e) {
                $slotSettings->InternalError('RiseofSamurai3DBCommit : ' . $e);
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

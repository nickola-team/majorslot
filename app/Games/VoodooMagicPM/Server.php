<?php 
namespace VanguardLTE\Games\VoodooMagicPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'RespinCount', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'RespinType', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusType', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [3,9,10,8,8,9,8,5,11,11,11,11,8,7,7,4,5,6,7,8]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinCount', $lastEvent->serverResponse->RespinCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinType', $lastEvent->serverResponse->RespinType);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusType', $lastEvent->serverResponse->BonusType);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', $lastEvent->serverResponse->Bgt);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);
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
                $spinType = 's';
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if(isset($stack)){
                    $str_initReel = $stack['initReel'];
                    if($stack['reel_set'] >= 0){
                        $currentReelSet = $stack['reel_set'];
                    }
                    $str_fstype = $stack['fstype'];
                    $fsmore = $stack['fsmore'];
                    $str_srf = $stack['srf'];
                    $bgt = $stack['bgt'];
                    $rs_p = $stack['rs_p'];
                    $rs_t = $stack['rs_t'];
                    $str_tmb = $stack['tmb'];
                    $bw = $stack['bw'];

                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                    {
                        if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                        {
                            $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsend_total=1&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        }
                        else
                        {
                            $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        }
                        if($fsmore > 0){
                            $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                        }
                        $strOtherResponse = $strOtherResponse . '&bgid=1';
                    }else if($slotSettings->GetGameData($slotSettings->slotId . 'Bgt') == 21){
                        if($slotSettings->GetGameData($slotSettings->slotId . 'BonusType') == 2){
                            $strOtherResponse = $strOtherResponse . '&bgid=0&wins=0,0&status=0,0&wins_mask=h,h';
                        }else if($slotSettings->GetGameData($slotSettings->slotId . 'BonusType') == 3){
                            $strOtherResponse = $strOtherResponse . '&bgid=1&wins=0,0,0&status=0,0,0&wins_mask=h,h,h';
                        }
                        $strOtherResponse = $strOtherResponse . '&coef='. ($bet * 20) .'&level=0&rw=0.00&bgt=21&lifes=1&bw=1&wp=0&end=0';
                        $spinType = 'b';
                    }else{
                        $strOtherResponse = $strOtherResponse . '&bgid=0';
                    }
                    $wins = json_decode(json_encode($lastEvent->serverResponse->Wins), true);
                    $status = json_decode(json_encode($lastEvent->serverResponse->Status), true);
                    $wins_mask = json_decode(json_encode($lastEvent->serverResponse->WinsMask), true);
                    if(($slotSettings->GetGameData($slotSettings->slotId . 'BonusType') == 2 && $rs_p == 0) || ($slotSettings->GetGameData($slotSettings->slotId . 'BonusType') == 3 &&$slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') == 1)){
                        $strOtherResponse = $strOtherResponse .'&wins='. implode(',', $wins) .'&coef='. ($bet * 20) .'&level=1&status='. implode(',', $status) .'&rw=0&bgt=21&lifes=0&wins_mask='. implode(',', $wins_mask) .'&wp=0&end=1';
                    }
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                    }
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($str_fstype != ''){
                        $strOtherResponse = $strOtherResponse . '&fstype=' . $str_fstype;
                    }
                    if($rs_p >= 0){
                        $strOtherResponse = $strOtherResponse . '&rs=mc&rs_p=' . $rs_p . '&rs_c=' . ($rs_p + 1) . '&rs_m=' . ($rs_p + 1);
                    }
                    if($str_tmb != ''){
                        $strOtherResponse = $strOtherResponse . '&tmb=' . $str_tmb;
                    }
                    if($rs_t > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t;
                    }
                    if($str_srf != ''){
                        $strOtherResponse = $strOtherResponse . '&srf=' . $str_srf;
                    }
                }else{
                    $strOtherResponse = $strOtherResponse . '&whi=0&whm=fs,jp,mp,ew,cr,mp,jp,fs,fs,cr,ew,mp,mp,ew,cr,ew,mp,cr,ew,cr&whw=50,0,5,4,1000,20,0,20,10,2000,7,3,100,5,2500,4,50,200,3,750';
                }
                if($currentReelSet >= 0){
                    $strOtherResponse = $strOtherResponse . '&reel_set='. $currentReelSet;
                }
                
                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=3,9,10,8,8,9,8,5,11,11,11,11,8,7,7,4,5,6,7,8&balance='. $Balance .'&cfgs=3765&ver=2&index=1&balance_cash='. $Balance .'&def_sb=10,10,11,9,5&reel_set_size=6&def_sa=11,7,3,4,6&balance_bonus=0.00&na='. $spinType. $strOtherResponse . '&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&cls_s=13&gmb=0,0,0&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"141443",max_rnd_win:"1000"}}&wl_i=tbm~1000&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa=11,7,3,4,6&sb=10,10,11,9,5&sc='. implode(',', $slotSettings->Bet) .'&defc=100.00&purInit_e=1&sh=4&wilds=2~250,100,25,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;250,100,25,0,0;200,50,20,0,0;100,40,10,0,0;75,30,10,0,0;75,30,10,0,0;50,20,4,0,0;50,20,4,0,0;40,10,4,0,0;40,10,4,0,0;20,5,2,0,0;20,5,2,0,0;0,0,0,0,0,0&l=20&total_bet_max=&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=2,2,2,2,7,10,8,12,3,3,12,3,8,8,5,5,12,12,11,11,11,8,8,11,11,12,12,6,12,6,6,12,11,11,12,12,9,12,4~2,2,2,2,7,12,12,12,9,9,8,8,11,11,10,10,9,6,6,4,9,4,4,8,9,10,3,10,5,9,4,11,6,8,7,7,12,12,7,7,3,3,8,3,3,11,8,8,5,5,11,5,11,12,12,4,4,10,4,9,11,11,9,5,5,12,7,1~2,2,2,2,9,10,1,10,4,4,8,4,3,7,3,3,8,4,8,4,12,9,9,5,5,10,5,9,6,6,10,3,3,11~2,2,2,2,8,5,5,9,7,1,9,4,4,9,8,12,6,3,3,9,12,6,6,8,11,4,11,4,10~5,7,6,12,6,10,8,11,8,2,2,2,2,7,4,4,9,3&s='.$lastReelStr.'&reel_set2=5,12,12,8,11,11,11,8,13,13,13,13,13,13,13,13,13,13,8,4,9,4,9,11,3,3,13,13,13,13,13,7,12,8,11,12,7,12,7,13,13,13,13,13,9,12,12,11,4,11,6,6,11,4,11,2,2,2,2,7,10~4,8,10,2,2,2,2,2,10,9,11,8,7,7,12,12,7,7,13,13,13,13,11,5,11,3,13,13,13,13,12,10,7,10,6~2,2,2,2,6,11,10,4,4,10,13,13,13,13,13,13,13,13,13,13,12,9,9,5,5,10,13,13,13,13,13,3,3,8,10,3,8,3,3,6,7~8,12,10,4,4,11,4,4,12,4,13,13,13,13,13,5,12,5,5,12,7,2,2,2,2,2,10,9,5,5,12,12,6,6,12,3~6,8,2,2,2,2,2,8,11,12,9,4,4,7,3,7,13,13,13,13,13,8,8,4,9,12,12,10,5&reel_set1=5,12,12,8,11,11,11,8,13,13,13,13,8,4,9,4,9,11,3,3,13,13,13,13,7,12,8,11,12,7,12,7,13,13,13,13,13,9,12,12,11,4,11,6,6,11,4,11,2,2,2,2,7,10~2,2,2,2,12,7,13,13,13,13,3,3,8,11,4,8,4,8,5,13,13,13,13,6,6,9,10~3,7,4,4,11,5,5,8,9,2,2,2,2,9,10,4,11,4,4,10,11,7,12,6,6,10,13,13,13,13~4,6,2,2,2,2,11,12,10,8,5,5,9,9,9,7,7,9,13,13,13,13,3~2,2,2,2,11,8,4,7,7,10,5,5,9,8,3,3,8,11,6,6,8,7,11,11,9,12,10,13,13,13,13&reel_set4=2,2,2,2,7,10,8,12,3,3,12,3,8,8,5,5,12,12,11,11,11,8,8,11,11,12,12,6,12,6,6,12,11,11,12,12,9,12,4~11,1,12,7,8,1,9,10,7,1,5,12,7,1,6~4,10,11,1,3,9,10,1,2~4,10,10,1,5,9,12,1,8,12,7,1,6,11~5,7,6,12,6,10,8,11,8,2,2,2,2,2,7,4,4,9,3&purInit=[{type:"fs",bet:1600}]&reel_set3=5,12,12,8,11,11,11,8,13,13,13,13,8,4,9,4,9,11,3,3,13,13,13,13,13,7,12,8,11,12,7,12,7,13,13,13,13,9,12,12,11,4,11,6,6,11,4,11,2,2,2,2,7,10~6,13,13,13,13,13,10,12,11,13,13,13,13,13,4,4,8,9,10,10,2,2,2,2,12,7,13,13,13,13,4,8,5~2,2,2,2,6,11,10,4,4,10,13,13,13,13,13,13,13,13,13,13,12,9,9,5,5,10,13,13,13,13,13,3,3,8,10,3,8,3,3,6,7~9,13,13,13,13,11,4,11,4,10,6,11,6,6,12,10,2,2,2,2,8,12,10,4,4,11,4,4,12,4,13,13,13,13,13,5,12,5,5,12,7,2,2,2,2,12,6,6,12,3~6,8,7,11,11,9,13,13,13,13,13,13,13,13,13,10,8,11,8,2,2,2,2,2,7,4,4,9,3,3,7,9,8,3,13,13,13,13,4,12,4,10,5&reel_set5=5,9,11,11,11,11,7,3,3,3,3~6,1,8,12,12,12,10,10,10,1,4,4,4~2,2,2,1,10,10,4,1,4,8,4,1,7,3,3,1,4,8,4,1,9,9,5,1,10,5,9,1,6,10,3,1,11,12~2,2,2,8,5,1,9,7,9,1,4,4,9,1,12,6,3,1,9,12,6,1,8,11,4,1,4,10~5,7,6,12,6,10,8,11,8,2,2,2,2,7,4,4,9,3&total_bet_min=10.00';
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
                $linesId = [];
                $linesId[0] = [2, 2, 2, 2, 2];
                $linesId[1] = [3, 3, 3, 3, 3];
                $linesId[2] = [1, 1, 1, 1, 1];
                $linesId[3] = [4, 4, 4, 4, 4];
                $linesId[4] = [4, 3, 2, 3, 4];
                $linesId[5] = [3, 2, 1, 2, 3];
                $linesId[6] = [1, 2, 3, 2, 1];
                $linesId[7] = [2, 3, 4, 3, 2];
                $linesId[8] = [4, 3, 4, 3, 4];
                $linesId[9] = [3, 2, 3, 2, 3];
                $linesId[10] = [2, 1, 2, 1, 2];
                $linesId[11] = [1, 2, 1, 2, 1];
                $linesId[12] = [2, 3, 2, 3, 2];
                $linesId[13] = [3, 4, 3, 4, 3];
                $linesId[14] = [2, 2, 1, 2, 2];
                $linesId[15] = [3, 3, 2, 3, 3];
                $linesId[16] = [4, 4, 3, 4, 4];
                $linesId[17] = [3, 3, 4, 3, 3];
                $linesId[18] = [2, 2, 3, 2, 2];
                $linesId[19] = [1, 1, 2, 1, 1];                
                $linesId[20] = [4, 4, 1, 4, 4];
                $linesId[21] = [4, 4, 2, 4, 4];
                $linesId[22] = [3, 3, 1, 3, 3];
                $linesId[23] = [1, 1, 4, 1, 1];
                $linesId[24] = [1, 1, 3, 1, 1];
                $linesId[25] = [2, 2, 4, 2, 2];
                $linesId[26] = [4, 2, 4, 2, 4];
                $linesId[27] = [3, 1, 3, 1, 3];
                $linesId[28] = [1, 3, 1, 3, 1];
                $linesId[29] = [2, 4, 2, 4, 2];                
                $linesId[30] = [1, 2, 2, 2, 1];
                $linesId[31] = [2, 3, 3, 3, 2];
                $linesId[32] = [3, 4, 4, 4, 3];
                $linesId[33] = [4, 3, 3, 3, 4];
                $linesId[34] = [3, 2, 2, 2, 3];
                $linesId[35] = [2, 1, 1, 1, 2];
                $linesId[36] = [1, 3, 3, 3, 1];
                $linesId[37] = [2, 4, 4, 4, 2];
                $linesId[38] = [3, 1, 1, 1, 3];
                $linesId[39] = [4, 2, 2, 2, 4];

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

                // $winType = 'win';

                $allBet = $betline * $lines;
                if($pur >= 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $betline * $lines * 80;
                }
                $freeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'freespin' || $slotSettings->GetGameData($slotSettings->slotId . 'RespinCount') >= 0){
                    if($slotEvent['slotEvent'] == 'freespin'){
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinCount', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinType', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusType', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '56671' . substr($roundstr, 4, 9);
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
                $str_fstype = '';
                $fsmore = 0;
                $str_srf = '';
                $bgt = 0;
                $rs_p = -1;
                $rs_t = 0;
                $str_tmb = '';
                $bw = -1;
                $subScatterReel = null;
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    
                    $str_initReel = $stack['initReel'];
                    $currentReelSet = $stack['reel_set'];
                    $str_fstype = $stack['fstype'];
                    $fsmore = $stack['fsmore'];
                    $str_srf = $stack['srf'];
                    $bgt = $stack['bgt'];
                    $rs_p = $stack['rs_p'];
                    $rs_t = $stack['rs_t'];
                    $str_tmb = $stack['tmb'];
                    $bw = $stack['bw'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $pur, $betline * $lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $lastReel = explode(',', $stack[0]['reel']);
                    $str_initReel = $stack[0]['initReel'];
                    $currentReelSet = $stack[0]['reel_set'];
                    $str_fstype = $stack[0]['fstype'];
                    $fsmore = $stack[0]['fsmore'];
                    $str_srf = $stack[0]['srf'];
                    $bgt = $stack[0]['bgt'];
                    $rs_p = $stack[0]['rs_p'];
                    $rs_t = $stack[0]['rs_t'];
                    $str_tmb = $stack[0]['tmb'];
                    $bw = $stack[0]['bw'];
                }


                $reels = [];
                $scatterCount = 0;
                for($i = 0; $i < 5; $i++){
                    $reels[$i] = [];
                    for($j = 0; $j < 4; $j++){
                        $reels[$i][$j] = $lastReel[$j * 5 + $i];
                        if($reels[$i][$j] == 1){
                            $scatterCount++;
                        }
                    }
                }
                if($rs_p == -1){
                    $_lineWinNumber = 1;
                    $_obf_winCount = 0;
                    for( $k = 0; $k < 40; $k++ ) 
                    {
                        $_lineWin = '';
                        $firstEle = $reels[0][$linesId[$k][0] - 1];
                        $lineWinNum[$k] = 1;
                        $lineWins[$k] = 0;
                        $wildWin = 0;
                        $wildWinNum = 1;
                        for($j = 1; $j < 5; $j++){
                            $ele = $reels[$j][$linesId[$k][$j] - 1];
                            if($firstEle == $wild){
                                $firstEle = $ele;
                                $lineWinNum[$k] = $lineWinNum[$k] + 1;
                                if($j == 4){
                                    $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                    if($lineWins[$k] < $wildWin){
                                        $lineWins[$k] = $wildWin;
                                        $lineWinNum[$k] = $wildWinNum;
                                    }
                                    if($lineWins[$k] > 0){
                                        $totalWin += $lineWins[$k];
                                        $_obf_winCount++;
                                        $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                        for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                            $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                        }
                                    }
                                }else if($j >= 2 && ($ele == $wild)){
                                    $firstEle = $wild;
                                    $wildWin = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                    $wildWinNum = $lineWinNum[$k];
                                }
                            }else if($ele == $firstEle || $ele == $wild){
                                $lineWinNum[$k] = $lineWinNum[$k] + 1;
                                if($j == 4){
                                    $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                    if($lineWins[$k] < $wildWin){
                                        $lineWins[$k] = $wildWin;
                                        $lineWinNum[$k] = $wildWinNum;
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
                                    if($lineWins[$k] < $wildWin){
                                        $lineWins[$k] = $wildWin;
                                        $lineWinNum[$k] = $wildWinNum;
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
                }
                $spinType = 's';
                if( $totalWin > 0) 
                {
                    $spinType = 'c';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }
                $_obf_totalWin = $totalWin;
                if( $fsmore > 0) 
                {
                    if($slotEvent['slotEvent'] == 'freespin'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                    }else{                        
                        // $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                        // $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $fsmore);
                        // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                    }
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'RespinCount', $rs_p);
                $strLastReel = implode(',', $lastReel);
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 5; $i++){
                    $reelA[$i] = mt_rand(7, 10);
                    $reelB[$i] = mt_rand(7, 10);
                }
                $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
               
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $strOtherResponse = '';
                $isState = true;
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $spinType = 's';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $isEnd = false;
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                    {
                        $isEnd = true;
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsend_total=1&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 'c';
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
                    $strOtherResponse = $strOtherResponse . '&bgid=1';
                }else if($rs_p >= 0 || $rs_t > 0){
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    if($rs_t > 0){
                        $isState = true;
                    }else{
                        $isState = false;
                    }
                    // $strOtherResponse = $strOtherResponse . '&bgid=0';
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                    if($bgt == 21){                        
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusType', $scatterCount);
                        $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', $bgt);
                        $isState = false;
                        $spinType = 'b';
                        // $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1';
                        if($scatterCount == 2){
                            $strOtherResponse = $strOtherResponse . '&bgid=0&wins=0,0&status=0,0&wins_mask=h,h';
                        }else if($scatterCount == 3){
                            $strOtherResponse = $strOtherResponse . '&bgid=1&wins=0,0,0&status=0,0,0&wins_mask=h,h,h';
                        }
                        $strOtherResponse = $strOtherResponse . '&coef='. ($betline * $lines) .'&level=0&rw=0.00&bgt=21&lifes=1&bw=1&wp=0&end=0';
                        if($pur >= 0){
                            $strOtherResponse = $strOtherResponse . '&purtr=1&puri=' . $pur;
                        }
                    }
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($str_fstype != ''){
                    $strOtherResponse = $strOtherResponse . '&fstype=' . $str_fstype;
                }
                if($str_srf != ''){
                    $strOtherResponse = $strOtherResponse . '&srf=' . $str_srf;
                }
                if($rs_p >= 0){
                    $strOtherResponse = $strOtherResponse . '&rs=mc&rs_p=' . $rs_p . '&rs_c=' . ($rs_p + 1) . '&rs_m=' . ($rs_p + 1);
                }
                if($rs_t > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t;
                }
                if($str_tmb != ''){
                    $strOtherResponse = $strOtherResponse . '&tmb=' . $str_tmb;
                }
                
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse . $strWinLine .'&ls=0&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=4&c='.$betline.'&sver=5&reel_set='.$currentReelSet.'&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&s='.$strLastReel.'&w='.$totalWin;
                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"RespinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinCount') . ',"RespinType":' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinType') . ',"BonusType":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusType') . ',"Wins":[],"Status":[],"WinsMask":[],"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($slotEvent['slotEvent'] == 'freespin' && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * 80;
                }
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if( $bgt == 21) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                }
            }else if( $slotEvent['slotEvent'] == 'doBonus' ){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lastReel = $lastEvent->serverResponse->LastReel;
                $lines = 20;
                $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');

                $ind = -1;
                if(isset($slotEvent['ind'])){
                    $ind = $slotEvent['ind'];
                }
                
                $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                $str_fstype = $stack['fstype'];
                $bgt = $stack['bgt'];
                $rs_p = $stack['rs_p'];
                $rs_t = $stack['rs_t'];
                $wins = explode(',', $stack['wins']);
                $status = explode(',', $stack['status']);
                $wins_mask = explode(',', $stack['wins_mask']);
                $isState = false;
                $coef = $betline * $lines;
                $strOtherResponse = '';
                $slotSettings->SetGameData($slotSettings->slotId . 'RespinCount', $rs_p);
                $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                if($slotSettings->GetGameData($slotSettings->slotId . 'BonusType') == 2){
                    $new_status = [0, 0];
                    $new_status[$ind] = 1;
                    $new_wins = $wins;
                    $new_wins_mask = $wins_mask;
                    for($k = 0; $k < 2; $k++){
                        if($status[$k] == 1){
                            $old_mask = $new_wins_mask[$ind];
                            $new_wins_mask[$ind] = $new_wins_mask[$k];
                            $new_wins_mask[$k] = $old_mask;
                        }
                    }
                    $strOtherResponse = $strOtherResponse . '&rs=mc&rs_p=0&rs_c=1&rs_m=1';
                }else{
                    $new_status = [0, 0, 0];
                    $new_wins_mask = $wins_mask;
                    $new_wins = $wins;
                    $mask = '';
                    $fsmax = 0;
                    for($k = 0; $k < 3; $k++){
                        if($status[$k] == 1){
                            $old_mask = $new_wins_mask[$ind];
                            $new_wins_mask[$ind] = $new_wins_mask[$k];
                            $new_wins_mask[$k] = $old_mask;
                            $fsmax = $wins[$k];
                        }
                    }
                    $new_status[$ind] = 1;
                    if($fsmax > 0){
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $fsmax);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                    }
                    $strOtherResponse = $strOtherResponse . '&fsmul=1&bgid=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1';
                }
                if($str_fstype != ''){
                    $strOtherResponse = $strOtherResponse . '&fstype=' . $str_fstype;
                }

                
                $response = 'tw='. $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='. $Balance .'&wins='. implode(',', $new_wins) .'&coef='. $coef .'&level=1&index='.$slotEvent['index'].'&balance_cash='. $Balance .'&balance_bonus=0.00&na=s&status='. implode(',', $new_status) .'&rw=0&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&bgt=21&lifes=0&wins_mask='. implode(',', $new_wins_mask) .'&wp=0&end=1&sver=5&counter='. ((int)$slotEvent['counter'] + 1);

                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"RespinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinCount') . ',"RespinType":' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinType') . ',"BonusType":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusType') .',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"Wins":' . json_encode($new_wins) . ',"Status":' . json_encode($new_status) . ',"WinsMask":' . json_encode($new_wins_mask) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * 80;
                }
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
            }


            
            if($slotEvent['action'] == 'doSpin' || $slotEvent['action'] == 'doCollect' || $slotEvent['action'] == 'doCollectBonus' || $slotEvent['action'] == 'doBonus'){
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

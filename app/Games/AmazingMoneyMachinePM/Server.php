<?php 
namespace VanguardLTE\Games\AmazingMoneyMachinePM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Level', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [19,19,19,19,19,19,19,19,19,19,5,10,3,13,7,4,8,10,7,6,3,8,9,11,5]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'Wins', [0,0,0,0,0,0,0,0,0,0,0,0]);
                $slotSettings->SetGameData($slotSettings->slotId . 'Status', [0,0,0,0,0,0,0,0,0,0,0,0]);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'Level', $lastEvent->serverResponse->Level);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', $lastEvent->serverResponse->Bgt);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Wins', json_decode(json_encode($lastEvent->serverResponse->Wins), true));
                    $slotSettings->SetGameData($slotSettings->slotId . 'Status', json_decode(json_encode($lastEvent->serverResponse->Status), true));
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
                    if($stack['reel_set'] > -1){
                        $currentReelSet = $stack['reel_set'];
                    }
                    $str_msr = $stack['msr'];
                    $pw = str_replace(',', '', $stack['pw']);
                    $str_ls = $stack['ls'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $mo_tv = $stack['mo_tv'];
                    $str_mo_wpos = $stack['mo_wpos'];
                    $rs_more = $stack['rs_more'];
                    $str_srf = $stack['srf'];
                    $str_sty = $stack['sty'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $str_accv = $stack['accv'];
                    $acci = $stack['acci'];
                    $bw = $stack['bw'];
                    $level = $stack['level'];
                    $wp = $stack['wp'];

                    $original_bet = 0.1;
                    if($pw > 0){
                        $pw = $pw / 0.1 * $bet;
                    }
                    $moneyWin = 0;
                    if($mo_tv > 0){
                        $moneyWin = $mo_tv * $bet;
                    }
                    $wins = $slotSettings->GetGameData($slotSettings->slotId . 'Wins');
                    $status = $slotSettings->GetGameData($slotSettings->slotId . 'Status');
                    $wins_mask = [];
                    for($k = 0; $k < count($wins); $k++){
                        if($wins[$k] == 0){
                            $wins_mask[$k] = 'h';
                        }else{
                            $wins_mask[$k] = 'pw';
                        }
                    }
                    
                    if($slotSettings->GetGameData($slotSettings->slotId . 'Bgt') == 18 || $wp > 0){
                        $strOtherResponse = $strOtherResponse . '&bgid=0&rw=0&status='. implode(',', $status) .'&wins='. implode(',', $wins).'&wins_mask='. implode(',', $wins_mask) .'&coef='. ($bet * 20);
                        if($slotSettings->GetGameData($slotSettings->slotId . 'Level') >= 0){
                            $strOtherResponse = $strOtherResponse  .'&level='. $slotSettings->GetGameData($slotSettings->slotId . 'Level');
                        }
                        if($wp > 0){
                            $strOtherResponse = $strOtherResponse . '&lifes=0&end=1';
                        }else{
                            $strOtherResponse = $strOtherResponse . '&lifes=1&end=0';
                        }
                        if($wp > -1){
                            $strOtherResponse = $strOtherResponse . '&wp=' . $wp;
                            if($wp > 0){   
                            }else{
                                $spinType = 'b';
                            }
                        }
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') > 0){                                                     
                        $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    }
                    
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($str_msr != ''){
                        $strOtherResponse = $strOtherResponse . '&msr=' . $str_msr;
                    }
                    if($str_ls != ''){
                        $strOtherResponse = $strOtherResponse . '&ls=' . $str_ls;
                    }
                    if($pw > 0){
                        $strOtherResponse = $strOtherResponse . '&pw=' . $pw;
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
                    if($rs_more > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_more=' . $rs_more;
                    }
                    if($str_srf != ''){
                        $strOtherResponse = $strOtherResponse . '&srf=' . $str_srf;
                    }
                    if($str_sty != ''){
                        $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                    }
                    if($rs_p >= 0){
                        $strOtherResponse = $strOtherResponse . '&rs=mc&rs_p=' . $rs_p . '&rs_c=' . $rs_c . '&rs_m=' . $rs_m;
                    }
                    if($rs_t > 0){
                        $strOtherResponse  = $strOtherResponse . '&mo_c=1&rs_t=' . $rs_t;
                    }
                    if($str_accv != ''){
                        $strOtherResponse = $strOtherResponse . '&accm=cp~mp&acci='. $acci .'&accv=' . $str_accv;
                    }
                    if($bw >= 0){
                        $strOtherResponse = $strOtherResponse . '&bw=' . $bw;
                    }
                }else{
                    $strOtherResponse = $strOtherResponse . '&msr=2';
                }
                
                $Balance = $slotSettings->GetBalance();
                $response = 'msi=14&def_s=19,19,19,19,19,19,19,19,19,19,5,10,3,13,7,4,8,10,7,6,3,8,9,11,5&bgid=0&balance='. $Balance .'&nas=19&cfgs=4294&ver=2&mo_s=16;17;18;15&index=1&balance_cash='. $Balance .'&mo_v=20,25,40,50,75,100,125,150,200,250,500,800,1000,1500,2000,2500,4000,5000;20,25,40,50,75,100,125,150,200,250,500,800,1000,1500,2000,2500,4000,5000;20,25,40,50,75,100,125,150,200,250,500,800,1000,1500,2000,2500,4000,5000;20,25,40,50,75,100,125,150,200,250,500,800,1000,1500,2000,2500,4000,5000&def_sb=4,13,4,7,8&reel_set_size=3&def_sa=10,3,5,3,7&reel_set='. $currentReelSet .$strOtherResponse.'&bonusInit=[{bgid:0,bgt:18,bg_i:"5000,500,50,25",bg_i_mask:"pw,pw,pw,pw"}]&balance_bonus=0.00&na='. $spinType .'&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&gmb=0,0,0&bg_i=5000,500,50,25&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"13315579",max_rnd_win:"5100"}}&wl_i=tbm~5100&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&bgt=18&sa=10,3,5,3,7&sb=4,13,4,7,8&sc='. implode(',', $slotSettings->Bet) .'&defc=100.00&sh=5&wilds=2~1000,200,50,0,0~1,1,1,1,1;16~1000,200,50,0,0~1,1,1,1,1;20~1000,200,50,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&bg_i_mask=pw,pw,pw,pw&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;200,100,20,0,0;150,50,15,0,0;100,30,10,0,0;100,30,10,0,0;50,20,5,0,0;50,20,5,0,0;40,15,5,0,0;40,15,5,0,0;25,10,5,0,0;25,10,5,0,0;25,10,5,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0&l=20&reel_set0=12,14,14,14,11,2,2,2,8,6,14,15,4,3,5,2,10,9,13,7,14,8,2,11,14,2,14,7,14,2,3,2~11,9,2,2,2,7,8,14,2,13,6,1,3,12,15,14,14,14,10,5,4,2,4,8,6,2,9,13,14,2,15,14,7,12,13,2,5,7,3,14,9,8,10,2,9,13,2,8,2,5,8,2,13,10,15,13~15,15,15,13,8,3,15,10,12,6,4,1,14,14,14,5,14,9,2,2,2,2,11,7,2,6,4,2,3,2,12,10,14,2,11,14,2,14,3,2,14,2,14,2,13,3,11,10,2,10,3,13,5,14,2,14,2,14,10,3,6,14,1,11,14,13,14,12,6,9,2,10,3,14,3,2,14,5,14,2,8,14,11,13,11,2,14,2,13,12~13,1,9,15,2,2,2,7,3,12,6,10,4,11,5,2,14,14,14,8,14,10,11,14,1,15,2,10,2,1,10,2,10,2,5,10,9,2,9,5,14,10,2,10,1,5,1,8,9,2,5,1,5,10,5,10,14,2,10,6,5,2,10,2,14,5,8,2,10,14,8,2,5,10,11,14,2,11,12,14,5,11,5,2,15,2,10,2,5,2,5,10,15,10,14,2,5,8,14,12,10,15,5,14,5,2,5,8,1,11,14,8,2,8,10,5,14,10,2,15,14,7,1,10,5,2,10,11,2,10,15,5,10,14,5,1,12,1,5,9,14,5,2,14,1,12,2,14,2,14,10,2,15,14,1,9,7,11,8,2,11,8,15,10,2,8,5,11,10,2,14,2,10,14,5,2,8,14,9,8,5,14,2,14,10,8,2,12,2,14,12,9,14,1,14,9,8,5,2,11,1,8,14,10,5,15,1~13,14,14,14,4,2,2,2,9,15,14,12,8,2,6,11,7,5,10,3,10,2,9,11,2,14,11,2,11&s='.$lastReelStr.'&accInit=[{id:0,mask:"cp;mp"}]&reel_set2=13,5,8,10,3,12,7,11,6,9,4,4,3,9,3,4,10,11,4,3,4,6,7,3,7,10,4,7,3,5,3,4,12,7,4,7,4,12,4,4~13,5,8,10,3,12,7,11,6,9,4,4,3,9,3,4,10,11,4,3,4,6,7,3,7,10,4,7,3,5,3,4,12,7,4,7,4,12,4,4~13,5,8,10,3,12,7,11,6,9,4,4,3,9,3,4,10,11,4,3,4,6,7,3,7,10,4,7,3,5,3,4,12,7,4,7,4,12,4,4~13,5,8,10,3,12,7,11,6,9,4,4,3,9,3,4,10,11,4,3,4,6,7,3,7,10,4,7,3,5,3,4,12,7,4,7,4,12,4,4~13,5,8,10,3,12,7,11,6,9,4,4,3,9,3,4,10,11,4,3,4,6,7,3,7,10,4,7,3,5,3,4,12,7,4,7,4,12,4,4&reel_set1=13,9,6,5,7,10,15,4,2,2,2,12,14,11,14,14,14,8,2,3,14,4~14,14,14,5,6,2,2,2,8,4,7,10,14,12,9,2,15,11,3,13,15,10,9,3,9,5,11,3,13,2,15,13,15,10,15,10,2,9,5,2,10,3,15,5,10,5,4,12,13~3,8,2,2,2,4,5,14,14,14,7,2,10,12,15,11,13,6,9,14,11,9,14,2,9,14,2,10,11,9,14,15,14,2,7,6,9,2,9~13,4,14,14,14,9,5,12,2,2,2,3,15,8,7,14,10,6,11,2,9,2,9,15,6,15,2,14,7,10,5,2,15,2,7,5,15,14,2,15,7,2,14,9,2,5,2,14,2,15,4~14,2,2,2,8,3,7,6,12,14,14,14,5,13,10,4,2,9,15,11,2,10,9,11';
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
                $linesId = [];
                $linesId[0] = [4, 4, 4, 4, 4];
                $linesId[1] = [3, 3, 3, 3, 3];
                $linesId[2] = [5, 5, 5, 5, 5];
                $linesId[3] = [3, 4, 5, 4, 3];
                $linesId[4] = [5, 4, 3, 4, 5];
                $linesId[5] = [4, 3, 3, 3, 4];
                $linesId[6] = [4, 5, 5, 5, 4];
                $linesId[7] = [5, 5, 4, 3, 3];
                $linesId[8] = [3, 3, 4, 5, 5];
                $linesId[9] = [5, 4, 4, 4, 3];
                $linesId[10] = [2, 2, 2, 2, 2];
                $linesId[11] = [2, 3, 2, 3, 2];
                $linesId[12] = [4, 3, 2, 3, 4];
                $linesId[13] = [3, 2, 2, 2, 3];
                $linesId[14] = [3, 3, 2, 3, 3];
                $linesId[15] = [2, 2, 2, 2, 3];
                $linesId[16] = [2, 4, 2, 4, 2];
                $linesId[17] = [2, 2, 2, 3, 4];
                $linesId[18] = [4, 4, 2, 4, 4];
                $linesId[19] = [3, 2, 2, 3, 4];
                $linesId[20] = [4, 3, 2, 3, 3];
                $linesId[21] = [4, 3, 2, 2, 3];
                $linesId[22] = [4, 2, 2, 2, 4];
                $linesId[23] = [3, 3, 2, 3, 4];
                $linesId[24] = [3, 2, 2, 2, 2];
                $linesId[25] = [2, 3, 2, 2, 2];
                $linesId[26] = [4, 3, 2, 2, 2];
                $linesId[27] = [2, 2, 2, 3, 3];
                $linesId[28] = [3, 3, 2, 2, 2];
                $linesId[29] = [3, 4, 2, 4, 3];
                $linesId[30] = [1, 1, 1, 1, 1];
                $linesId[31] = [1, 2, 1, 2, 1];
                $linesId[32] = [3, 2, 1, 2, 3];
                $linesId[33] = [2, 1, 1, 1, 2];
                $linesId[34] = [2, 2, 1, 2, 2];
                $linesId[35] = [1, 1, 1, 1, 2];
                $linesId[36] = [1, 3, 1, 3, 1];
                $linesId[37] = [1, 1, 1, 2, 3];
                $linesId[38] = [3, 3, 1, 3, 3];
                $linesId[39] = [2, 1, 1, 2, 3];
                $linesId[40] = [3, 2, 1, 2, 2];
                $linesId[41] = [3, 2, 1, 1, 2];
                $linesId[42] = [3, 1, 1, 1, 3];
                $linesId[43] = [2, 2, 1, 2, 3];
                $linesId[44] = [2, 1, 1, 1, 1];
                $linesId[45] = [2, 1, 2, 2, 2];
                $linesId[46] = [3, 2, 1, 1, 1];
                $linesId[47] = [1, 1, 1, 2, 2];
                $linesId[48] = [2, 2, 1, 1, 1];
                $linesId[49] = [2, 3, 1, 3, 2];
                $linesId[50] = [1, 2, 2, 2, 1];
                $linesId[51] = [1, 1, 2, 1, 1];
                $linesId[52] = [1, 1, 2, 3, 3];
                $linesId[53] = [3, 3, 2, 1, 1];
                $linesId[54] = [2, 1, 2, 3, 2];
                $linesId[55] = [1, 2, 2, 2, 3];
                $linesId[56] = [3, 2, 2, 2, 1];
                $linesId[57] = [2, 1, 2, 1, 2];
                $linesId[58] = [1, 1, 2, 3, 4];
                $linesId[59] = [1, 1, 1, 2, 1];
                
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

                // $winType = 'win';

                $allBet = $betline * $lines;
                
                $freeStacks = []; 
                $isGeneratedFreeStack = false;
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
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $_sum, $slotEvent['slotEvent']);
                    $bonusMpl = 1;
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Level', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Wins', [0,0,0,0,0,0,0,0,0,0,0,0]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Status', [0,0,0,0,0,0,0,0,0,0,0,0]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.1f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '56671' . substr($roundstr, 4, 9);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $leftFreeGames = 0;

                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []);
                }
                
                $wild = '2';
                $moneyWild = '20';
                $scatter = '1';
                $Balance = $slotSettings->GetBalance();
                $totalWin = 0;
                $lineWins = [];
                $lineWinNum = [];
                $wildValues = [];
                $wildPoses = [];
                $strWinLine = '';
                $winLineCount = 0;
                $str_initReel = '';
                $str_msr = '';
                $str_ls = '';
                $str_mo = '';
                $str_mo_t = '';
                $mo_tv = 0;
                $str_mo_wpos = '';
                $rs_more = '';
                $str_srf = '';
                $str_sty = '';
                $rs_p = -1;
                $rs_c = 0;
                $rs_m = 0;
                $rs_t = 0;
                $str_accv = '';
                $acci = -1;
                $bw = -1;
                $pw = 0;
                $level = -1;
                $subScatterReel = null;
                $scatterCount = 0;
                $moneyCount = 0;
                $moneyWin = 0;
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    
                    $str_initReel = $stack['initReel'];
                    $currentReelSet = $stack['reel_set'];
                    $str_msr = $stack['msr'];
                    $pw = str_replace(',', '', $stack['pw']);
                    $str_ls = $stack['ls'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $mo_tv = $stack['mo_tv'];
                    $str_mo_wpos = $stack['mo_wpos'];
                    $rs_more = $stack['rs_more'];
                    $str_srf = $stack['srf'];
                    $str_sty = $stack['sty'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $str_accv = $stack['accv'];
                    $acci = $stack['acci'];
                    $bw = $stack['bw'];
                    $level = $stack['level'];

                    $original_bet = 0.1;
                    if($pw > 0){
                        $pw = $pw / 0.1 * $betline;
                    }
                    if($mo_tv > 0){
                        $moneyWin = $mo_tv * $betline;
                        $totalWin = $moneyWin;
                    }
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $lastReel = explode(',', $stack[0]['reel']);
                    $str_initReel = $stack[0]['initReel'];
                    $currentReelSet = $stack[0]['reel_set'];
                    $pw = $stack[0]['pw'];
                    $str_msr = $stack[0]['msr'];
                    $str_ls = $stack[0]['ls'];
                    $str_mo = $stack[0]['mo'];
                    $str_mo_t = $stack[0]['mo_t'];
                    $mo_tv = $stack[0]['mo_tv'];
                    $str_mo_wpos = $stack[0]['mo_wpos'];
                    $rs_more = $stack[0]['rs_more'];
                    $str_srf = $stack[0]['srf'];
                    $str_sty = $stack[0]['sty'];
                    $rs_p = $stack[0]['rs_p'];
                    $rs_c = $stack[0]['rs_c'];
                    $rs_m = $stack[0]['rs_m'];
                    $rs_t = $stack[0]['rs_t'];
                    $str_accv = $stack[0]['accv'];
                    $acci = $stack[0]['acci'];
                    $bw = $stack[0]['bw'];
                    $level = $stack[0]['level'];

                    $reels = [];
                    for($i = 0; $i < 5; $i++){
                        $reels[$i] = [];
                        for($j = 0; $j < 5; $j++){
                            $reels[$i][$j] = $lastReel[$j * 5 + $i];
                            if($reels[$i][$j] == $scatter){
                                $scatterCount++;
                            }else if($reels[$i][$j] > 14){
                                $moneyCount++;
                            }
                        }
                    }
                    $_lineWinNumber = 1;
                    $_obf_winCount = 0;
                    for( $k = 0; $k < 60; $k++ ) 
                    {
                        $_lineWin = '';
                        $firstEle = $reels[0][$linesId[$k][0] - 1];
                        $lineWinNum[$k] = 1;
                        $lineWins[$k] = 0;
                        $wildWin = 0;
                        $wildWinNum = 1;
                        $mul = 0;
                        for($j = 1; $j < 5; $j++){
                            $ele = $reels[$j][$linesId[$k][$j] - 1];
                            if($firstEle == $wild || $firstEle == $moneyWild){
                                $firstEle = $ele;
                                $lineWinNum[$k] = $lineWinNum[$k] + 1;
                                if($j == 4){
                                    $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                    $totalWin += $lineWins[$k];
                                    $_obf_winCount++;
                                    $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                    for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                        $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                    }
                                }else if($j >= 2 && ($ele == $wild || $firstEle == $moneyWild)){
                                    $firstEle = $wild;
                                    $wildWin = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                    $wildWinNum = $lineWinNum[$k];
                                }
                            }else if($ele == $firstEle || $ele == $wild || $ele == $moneyWild){
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
                                }
                            }else{
                                if($slotSettings->Paytable[$firstEle][$lineWinNum[$k]] > 0){
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
                
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $rs_m);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $rs_c);
                
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
                    if($rs_t > 0){
                        $spinType = 'c';
                    }else{
                        $isState = false;
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                    if($rs_p == 0 ){
                        $spinType = 's';      
                        $isState = false;                  
                    }
                }
                if($bw == 1){                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'Level', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 18);
                    $spinType = 'b'; 
                    $strOtherResponse = $strOtherResponse . '&wins=0,0,0,0,0,0,0,0,0,0,0,0&coef='.($betline * $lines).'&level=0&status=0,0,0,0,0,0,0,0,0,0,0,0&bg_i=5000,500,50,25&rw=0.00&bgt=18&lifes=1&bw=1&wins_mask=h,h,h,h,h,h,h,h,h,h,h,h&wp=0&end=0&bg_i_mask=pw,pw,pw,pw';
                    $isState = false;
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($str_msr != ''){
                    $strOtherResponse = $strOtherResponse . '&msr=' . $str_msr;
                }
                if($str_ls != ''){
                    $strOtherResponse = $strOtherResponse . '&ls=' . $str_ls;
                }
                if($pw > 0){
                    $strOtherResponse = $strOtherResponse . '&pw=' . $pw;
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
                if($rs_more > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_more=' . $rs_more;
                }
                if($str_srf != ''){
                    $strOtherResponse = $strOtherResponse . '&srf=' . $str_srf;
                }
                if($str_sty != ''){
                    $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                }
                if($rs_p >= 0){
                    $strOtherResponse = $strOtherResponse . '&rs=mc&rs_p=' . $rs_p . '&rs_c=' . $rs_c . '&rs_m=' . $rs_m;
                }
                if($rs_t > 0){
                    $strOtherResponse  = $strOtherResponse . '&mo_c=1&rs_t=' . $rs_t;
                }
                if($str_accv != ''){
                    $strOtherResponse = $strOtherResponse . '&accm=cp~mp&acci='. $acci .'&accv=' . $str_accv;
                }


                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse . $strWinLine .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=5&c='.$betline.'&sver=5&reel_set='.$currentReelSet.'&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&s='.$strLastReel.'&w='.$totalWin;
                if($rs_t > 0 && $bw < 0) 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"Level":' . $slotSettings->GetGameData($slotSettings->slotId . 'Level') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"Wins":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'Wins')) . ',"Status":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'Status')) .',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if( $rs_p == 0 && $slotEvent['slotEvent'] != 'freespin') 
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
                $bgt = $slotSettings->GetGameData($slotSettings->slotId . 'Bgt');
                $level = $slotSettings->GetGameData($slotSettings->slotId . 'Level');
                $new_wins = $slotSettings->GetGameData($slotSettings->slotId . 'Wins');
                $new_status = $slotSettings->GetGameData($slotSettings->slotId . 'Status');
                $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');

                $ind = 0;
                if(isset($slotEvent['ind'])){
                    $ind = $slotEvent['ind'];
                }
                $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                $currentReelSet = $stack['reel_set'];
                $level = $stack['level'];
                $bw = $stack['bw'];
                $wp = $stack['wp'];
                $wins = explode(',', $stack['wins']);
                $status = explode(',', $stack['status']);
                $wins_mask = explode(',', $stack['wins_mask']);
                $isState = false;
                $strOtherResponse = '';

                $new_wins_mask = [];
                for($k = 0; $k < count($new_wins); $k++){
                    if($new_wins[$k] == 0){
                        $new_wins_mask[$k] = 'h';
                    }else{
                        $new_wins_mask[$k] = 'pw';
                    }
                }
                if($ind >= 0 && $level > 0){
                    $new_wins[$ind] = $wins[$level - 1];
                    $new_wins_mask[$ind] = $wins_mask[$level - 1];
                    $new_status[$ind] = $status[$level - 1];
                }
                $totalWin = 0;
                $coef = $betline * $lines;
                if($wp > 0){
                    $totalWin = $wp * $coef;
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                }
                $spinType = 'b';
                if( $totalWin > 0) 
                {
                    $spinType = 'cb';
                    $isState = true;
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                if($totalWin > 0){
                    $strOtherResponse = $strOtherResponse . '&lifes=0&end=1&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }else{
                    $strOtherResponse = $strOtherResponse . '&lifes=1&end=0';
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'Level', $level);
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'Wins', $new_wins);
                $slotSettings->SetGameData($slotSettings->slotId . 'Status', $new_status);
                if($wp > -1){
                    $strOtherResponse = $strOtherResponse . '&wp=' . $wp;
                }
                $response = 'bgid=0' . $strOtherResponse .'&balance='. $Balance .'&wins='. implode(',', $new_wins) .'&coef='. $coef .'&level='. $level .'&index='.$slotEvent['index'].'&balance_cash='. $Balance .'&balance_bonus=0.00&na='. $spinType .'&status='. implode(',', $new_status) .'&bg_i=5000,500,50,25&rw='. $totalWin .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&bgt='. $bgt .'&wins_mask='. implode(',', $new_wins_mask) .'&sver=5&bg_i_mask=pw,pw,pw,pw&counter='. ((int)$slotEvent['counter'] + 1);

                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"Level":' . $slotSettings->GetGameData($slotSettings->slotId . 'Level') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"Wins":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'Wins')) . ',"Status":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'Status')) .',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
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
        public function findZokbos($reels, $firstSymbol, $repeatCount, $positions){
            $wild = '2';
            $bPathEnded = true;
            if($repeatCount < 5){
                for($r = 0; $r < 3; $r++){
                    if($firstSymbol == $reels[$repeatCount][$r] || $reels[$repeatCount][$r] == $wild){
                        $this->findZokbos($reels, $firstSymbol, $repeatCount + 1, array_merge($positions, [($repeatCount + $r * 5)]));
                        $bPathEnded = false;
                    }
                }
            }
            if($bPathEnded == true){
                if($repeatCount >= 3){
                    $winLine = [];
                    $winLine['FirstSymbol'] = $firstSymbol;
                    $winLine['RepeatCount'] = $repeatCount;
                    $winLine['Positions'] = $positions;
                    $winLine['Mul'] = 1;
                    array_push($this->winLines, $winLine);
                }
            }
        }
    }
}

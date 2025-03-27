<?php 
namespace VanguardLTE\Games\LuckyGraceCharmPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'RespinWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 10);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [13,12,5,13,13,11,10,11,9,9,7,7,9,5,5]);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinWin', $lastEvent->serverResponse->respinWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', $lastEvent->serverResponse->BonusState);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
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
                    $bet = '100.00';
                }
                $spinType = 's';
                if(isset($stack)){
                    $currentReelSet = $stack['reel_set'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $mo_tv = $stack['mo_tv'];
                    $str_accv = $stack['accv'];
                    $str_trail = $stack['trail'];
                    $fsmore = $stack['fsmore'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $wmv = $stack['wmv'];
                    $g = null;
                    if($stack['g'] != ''){
                        $g = $stack['g'];
                    }
                    if($mo_tv > 0){
                        $strOtherResponse = $strOtherResponse . '&mo_tv='. $mo_tv .'&mo_tw=' . ($mo_tv * $bet);
                    }
                    if($str_mo != ''){
                        $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                    }
                    if($str_accv != ''){
                        $strOtherResponse = $strOtherResponse . '&accm=cp~tp~lvl~sc;cp~tp~lvl~sc&acci=0;1&accv=' . $str_accv;
                    }
                    if($wmv > 0){
                        $strOtherResponse = $strOtherResponse . '&wmt=pr&wmv=' . $wmv;
                        if($wmv > 1){
                            $strOtherResponse = $strOtherResponse . '&gwm=' . $wmv;
                        }
                    }
                    if($str_trail != ''){
                        $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                    }
                    if($rs_p >= 0){
                        $strOtherResponse = $strOtherResponse . '&rs=mc&rs_p='. $rs_p .'&rs_c='. $rs_c .'&rs_m=' . $rs_m;
                        if($rs_p > 0){
                            $strOtherResponse = $strOtherResponse . '&sn_mult=1&sn_i=respin_screen&sn_pd=0';
                        }
                    }
                    if($rs_t > 0){
                        $strOtherResponse = $strOtherResponse . '&mo_c=1&rs_t=' . $rs_t . '&w=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    }
                    if($g != null){
                        $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($g));
                    }else{
                        $strOtherResponse = $strOtherResponse . '&g={respin_screen:{def_s:"15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15",def_sa:"15,15,15,15,15",def_sb:"15,15,15,15,15",s:"15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15",sa:"15,15,15,15,15",sb:"15,15,15,15,15",sh:"20",st:"rect",sw:"5"}}';
                    }
                    
                    if($fsmore > 0){
                        $strOtherResponse = $strOtherResponse  .'&fsmore='. $fsmore;
                    }
                    $strOtherResponse = $strOtherResponse  . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }else{
                    $strOtherResponse = $strOtherResponse . '&g={respin_screen:{def_s:"15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15",def_sa:"15,15,15,15,15",def_sb:"15,15,15,15,15",s:"15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15",sa:"15,15,15,15,15",sb:"15,15,15,15,15",sh:"20",st:"rect",sw:"5"}}';
                }
                if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)
                {
                    $strOtherResponse = $strOtherResponse . '&fs=' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&w=0.00&fsmul=1';
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));

                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=13,12,5,13,13,11,10,11,9,9,7,7,9,5,5&balance='. $Balance .'&screenOrchInit={type:"mini_slots"}&cfgs=4606&ver=2&index=1&balance_cash='. $Balance .'&def_sb=12,8,3,12,12&reel_set_size=6&def_sa=7,7,13,5,5&reel_set='.$currentReelSet.$strOtherResponse.'&balance_bonus=0.00&na=s&scatters=1~500,20,5,2,0~12,12,12,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"4946010",max_rnd_win:"10000"}}&wl_i=tbm~10000&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=7,7,13,5,5&sb=12,8,3,12,12&sc='. implode(',', $slotSettings->Bet) .'&defc=200.00&sh=3&wilds=2~9000,2500,250,10,0~2,2,2,2,2&bonuses=0;14&fsbonus=&st=rect&c='.$bet.'&sw=5&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;9000,2500,250,10,0;750,125,25,2,0;750,125,25,2,0;250,75,15,0,0;250,75,15,0,0;400,100,20,0,0;125,50,10,0,0;125,50,10,0,0;100,25,5,0,0;100,25,5,0,0;100,25,5,0,0;100,25,5,2,0;0,0,0,0,0;0,0,0,0,0&l=10&reel_set0=11,9,5,11,9,5,13,1,11,7,13,11,7,12,9,5,11,9,5,13,11,6,8,9,4,11,9,1,11,9,3,11,9,2,13,11,10,9,11,14,14,14~10,8,6,10,1,8,6,12,10,7,12,10,7,8,10,6,8,10,6,13,12,5,11,10,1,8,10,3,11,10,4,9,8,2,12,10,8,14,14~5,11,9,5,11,9,5,11,1,13,5,11,9,3,11,9,3,13,11,5,13,11,5,13,9,7,13,9,7,11,13,1,11,9,2,11,9,7,11,9,7,13,3,5,14,14,14,12,4,6,10,8~11,9,5,11,9,5,13,1,9,5,13,9,5,12,10,4,12,10,4,11,9,4,11,8,3,11,13,1,11,10,7,11,13,2,11,12,3,11,9,7,11,8,6,14,14,14,14~11,9,5,11,1,9,5,13,9,5,13,9,5,12,10,4,12,10,4,11,9,4,11,8,3,11,13,1,11,10,7,11,1,13,2,11,12,3,11,9,7,11,8,6,14,14,14&s='.$lastReelStr.'&accInit=[{id:0,mask:"cp;tp;lvl;sc;cl"},{id:1,mask:"cp;tp;lvl;sc;cl"}]&reel_set2=11,9,5,11,9,5,13,1,11,7,13,11,7,12,9,5,11,9,5,13,11,6,8,9,4,11,9,1,11,9,3,11,9,2,13,11,10,9,11,14,14,14~10,8,6,10,1,8,6,12,10,7,12,10,7,8,10,6,8,10,6,13,12,5,11,10,1,8,10,3,11,10,4,9,8,2,12,10,8,14,14~5,11,9,5,11,9,5,11,1,13,5,11,9,3,11,9,3,13,11,5,13,11,5,13,9,7,13,9,7,11,13,1,11,9,2,11,9,7,11,9,7,13,3,5,14,14,14,12,4,6,10,8~11,9,5,11,9,5,13,1,9,5,13,9,5,12,10,4,12,10,4,11,9,4,11,8,3,11,13,1,11,10,7,11,13,2,11,12,3,11,9,7,11,8,6,14,14,14,14~11,9,5,11,1,9,5,13,9,5,13,9,5,12,10,4,12,10,4,11,9,4,11,8,3,11,13,1,11,10,7,11,1,13,2,11,12,3,11,9,7,11,8,6,14,14,14&reel_set1=11,9,5,11,9,5,13,1,11,7,13,11,7,12,9,5,11,9,5,13,11,6,8,9,4,11,9,1,11,9,3,11,9,2,13,11,10,9,11,14,14,14~10,8,6,10,1,8,6,12,10,7,12,10,7,8,10,6,8,10,6,13,12,5,11,10,1,8,10,3,11,10,4,9,8,2,12,10,8,14,14~5,11,9,5,11,9,5,11,1,13,5,11,9,3,11,9,3,13,11,5,13,11,5,13,9,7,13,9,7,11,13,1,11,9,2,11,9,7,11,9,7,13,3,5,14,14,14,12,4,6,10,8~11,9,5,11,9,5,13,1,9,5,13,9,5,12,10,4,12,10,4,11,9,4,11,8,3,11,13,1,11,10,7,11,13,2,11,12,3,11,9,7,11,8,6,14,14,14,14~11,9,5,11,1,9,5,13,9,5,13,9,5,12,10,4,12,10,4,11,9,4,11,8,3,11,13,1,11,10,7,11,1,13,2,11,12,3,11,9,7,11,8,6,14,14,14&reel_set4=13,12,7,11,10,6,9,8,1,13,10,7,12,8,5,11,9,7,13,9,1,10,12,1,6,8,11,5,13,12,4,11,9,3,10,8,2,1~12,10,6,12,10,1,12,8,6,12,10,5,8,10,6,10,8,1,12,10,5,12,10,6,12,8,6,12,10,5,12,10,5,12,10,8,1,4,2,13,11,9,7,3~13,11,7,13,11,7,13,11,4,13,9,3,11,13,1,11,9,4,11,1,13,9,7,11,9,4,13,11,3,13,9,7,11,9,3,1,2,12,10,8,6,5~13,12,7,11,10,6,9,8,3,11,12,5,13,8,1,9,10,7,11,12,1,13,9,3,10,12,4,13,9,2,11,8,5,11,8,6,13,10,7,1~13,12,7,11,1,10,6,9,8,3,13,9,1,8,10,7,12,8,5,11,9,2,13,9,1,8,10,4,13,11,5,12,10,7,12,11,6,1&reel_set3=11,9,5,11,9,5,13,1,11,7,13,11,7,12,9,5,11,9,5,13,11,6,8,9,4,11,9,1,11,9,3,11,9,2,13,11,10,9,11,14,14,14~10,8,6,10,1,8,6,12,10,7,12,10,7,8,10,6,8,10,6,13,12,5,11,10,1,8,10,3,11,10,4,9,8,2,12,10,8,14,14~5,11,9,5,11,9,5,11,1,13,5,11,9,3,11,9,3,13,11,5,13,11,5,13,9,7,13,9,7,11,13,1,11,9,2,11,9,7,11,9,7,13,3,5,14,14,14,12,4,6,10,8~11,9,5,11,9,5,13,1,9,5,13,9,5,12,10,4,12,10,4,11,9,4,11,8,3,11,13,1,11,10,7,11,13,2,11,12,3,11,9,7,11,8,6,14,14,14,14~11,9,5,11,1,9,5,13,9,5,13,9,5,12,10,4,12,10,4,11,9,4,11,8,3,11,13,1,11,10,7,11,1,13,2,11,12,3,11,9,7,11,8,6,14,14,14&reel_set5=13,12,7,11,10,6,9,8,1,13,10,7,12,8,5,11,9,7,13,9,1,10,12,1,6,8,11,5,13,12,4,11,9,3,10,8,2,1~12,10,6,12,10,1,12,8,6,12,10,5,8,10,6,10,8,1,12,10,5,12,10,6,12,8,6,12,10,5,12,10,5,12,10,8,1,4,2,13,11,9,7,3~13,11,7,13,11,7,13,11,4,13,9,3,11,13,1,11,9,4,11,1,13,9,7,11,9,4,13,11,3,13,9,7,11,9,3,1,2,12,10,8,6,5~13,12,7,11,10,6,9,8,3,11,12,5,13,8,1,9,10,7,11,12,1,13,9,3,10,12,4,13,9,2,11,8,5,11,8,6,13,10,7,1~13,12,7,11,1,10,6,9,8,3,13,9,1,8,10,7,12,8,5,11,9,2,13,9,1,8,10,4,13,11,5,12,10,7,12,11,6,1';
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
                $linesId = [];
                $linesId[0] = [2, 2, 2, 2, 2];
                $linesId[1] = [1, 1, 1, 1, 1];
                $linesId[2] = [3, 3, 3, 3, 3];
                $linesId[3] = [2, 1, 1, 1, 2];
                $linesId[4] = [2, 3, 3, 3, 2];
                $linesId[5] = [3, 2, 1, 2, 3];
                $linesId[6] = [1, 2, 3, 2, 1];
                $linesId[7] = [3, 3, 2, 1, 1];
                $linesId[8] = [1, 1, 2, 3, 3];
                $linesId[9] = [3, 2, 2, 2, 1];
                
                $slotEvent['slotBet'] = $slotEvent['c'];
                $slotEvent['slotLines'] = 10;
                $isRespin = false;
                if($slotSettings->GetGameData($slotSettings->slotId . 'BonusState') > 0){
                    $isRespin = true;
                }
                if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') < $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
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
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') < $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotEvent['slotEvent'] == 'freespin' && $isRespin == false ) 
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

                // $winType = 'bonus';

                $freeStacks = []; 
                $isGeneratedFreeStack = false;
                $bonusSymbol = 0;
                if($slotEvent['slotEvent'] == 'freespin' || $isRespin == true){
                    if($isRespin == false){
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'RespinWin', 0);
                    }
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.1f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '628' . substr($roundstr, 3, 8). '023';
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
                $g = null;
                $str_mo = '';
                $str_mo_t = '';
                $mo_tv = 0;
                $str_trail = '';
                $str_accv = '';
                $rs_p = -1;
                $rs_c = 0;
                $rs_m = 0;
                $rs_t = 0;
                $fsmore = 0;
                $wmv = 0;
                $currentReelSet = 0;
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $mo_tv = $stack['mo_tv'];
                    $str_accv = $stack['accv'];
                    $str_trail = $stack['trail'];
                    $fsmore = $stack['fsmore'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $wmv = $stack['wmv'];
                    if($stack['g'] != ''){
                        $g = $stack['g'];
                    }
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
                    $str_mo = $stack[0]['mo'];
                    $str_mo_t = $stack[0]['mo_t'];
                    $mo_tv = $stack[0]['mo_tv'];
                    $str_accv = $stack[0]['accv'];
                    $str_trail = $stack[0]['trail'];
                    $fsmore = $stack[0]['fsmore'];
                    $rs_p = $stack[0]['rs_p'];
                    $rs_c = $stack[0]['rs_c'];
                    $rs_m = $stack[0]['rs_m'];
                    $rs_t = $stack[0]['rs_t'];
                    $wmv = $stack[0]['wmv'];
                    if($stack[0]['g'] != ''){
                        $g = $stack[0]['g'];
                    }
                }

                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;
                $moneyWin = 0;
                if($isRespin == true){
                    if($rs_t > 0){
                        $moneyWin = $mo_tv * $betline;
                    }
                }else{
                    $reels = [];
                    for($i = 0; $i < 5; $i++){
                        $reels[$i] = [];
                        for($j = 0; $j < 3; $j++){
                            $reels[$i][$j] = $lastReel[$j * 5 + $i];
                            if($lastReel[$j * 5 + $i] == $scatter){
                                $scatterCount++;
                                $scatterPoses[] = $j * 5 + $i;   
                            }
                        }
                    }
                    $_lineWinNumber = 1;
                    $_obf_winCount = 0;
                    for( $k = 0; $k < $lines; $k++ ) 
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
                            if($firstEle == $wild){
                                $firstEle = $ele;
                                $mul = 2;
                                $lineWinNum[$k] = $lineWinNum[$k] + 1;
                                if($j == 4){
                                    $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                    $totalWin += $lineWins[$k];
                                    $_obf_winCount++;
                                    $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                    for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                        $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                    }
                                }else if($j >= 1 && $ele == $wild){
                                    $wildWin = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                    $wildWinNum = $lineWinNum[$k];
                                }
                            }else if($ele == $firstEle || $ele == $wild){
                                $lineWinNum[$k] = $lineWinNum[$k] + 1;
                                if($ele == $wild){
                                    $mul = 2;
                                }
                                if($j == 4){
                                    $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                    if($mul > 0){
                                        $lineWins[$k] = $lineWins[$k] * $mul;
                                    }
                                    if($lineWins[$k] < $wildWin){
                                        $lineWins[$k] = $wildWin;
                                        $lineWinNum[$k] = $wildWinNum;
                                    }
                                    if($wmv > 1){
                                        $lineWins[$k] = $lineWins[$k] * $wmv;
                                    }
                                    $totalWin += $lineWins[$k];
                                    $_obf_winCount++;
                                    $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                    for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                        $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                    }
                                }
                            }else{
                                if($slotSettings->Paytable[$firstEle][$lineWinNum[$k]] > 0 || $wildWin > 0){
                                    $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                    if($mul > 0){
                                        $lineWins[$k] = $lineWins[$k] * $mul;
                                    }
                                    if($lineWins[$k] < $wildWin){
                                        $lineWins[$k] = $wildWin;
                                        $lineWinNum[$k] = $wildWinNum;
                                    }
                                    if($wmv > 1){
                                        $lineWins[$k] = $lineWins[$k] * $wmv;
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
                    if($scatterCount >= 2){
                        $muls = [0, 0, 2, 5, 20, 500];
                        $scatterWin = $betline * $lines * $muls[$scatterCount];
                        if($wmv > 1){
                            $scatterWin = $scatterWin * $wmv;
                        }

                    }
                }
                
                $totalWin = $totalWin + $moneyWin + $scatterWin; 
                
                $spinType = 's';
                if( $totalWin > 0) 
                {
                    $spinType = 'c';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }
                $_obf_totalWin = $totalWin;
                if( $scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin') 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 12);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                }else if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                }

                
                $strOtherResponse = '';
                $isState = true;
                $isEnd = true;

                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinWin', $slotSettings->GetGameData($slotSettings->slotId . 'RespinWin') + $totalWin);
                    $spinType = 's';
                    $isEnd = false;
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 && $isRespin == false) 
                    {
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fsend_total=1&fswin_total='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin').'&fsmul_total=1&fsres_total='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 'c';
                    }
                    else
                    {
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 's';
                    }
                    if($fsmore > 0){
                        $strOtherResponse = $strOtherResponse  .'&fsmore='. $fsmore;
                    }
                }
                else{
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinWin', $totalWin);
                    if($scatterCount >= 3){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse =$strOtherResponse . '&fsmul=1&fsmax='. $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames')  .'&fswin=0.00&fs=1&fsres=0.00';
                    }
                }                
                if($scatterCount >= 2){
                    $strOtherResponse = $strOtherResponse . '&psym=1~'. $scatterWin .'~' . implode(',', $scatterPoses);
                }

                if($isRespin == false){
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
                    if($rs_p >= 0){
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 1);
                    }
                    $strOtherResponse = $strOtherResponse .'&sh=3&sa='.$strReelSa.'&sb='.$strReelSb . '&s='.$strLastReel;
                }else{
                    if($rs_t > 0){
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                        if($mo_tv > 0){
                            $strOtherResponse = $strOtherResponse . '&mo_c=1';
                        }
                        if($slotEvent['slotEvent'] != 'freespin'){
                            $spinType = 'c';
                        }else{
                            $isState = false;
                        }
                    }else{
                        $isState = false;
                    }
                    $strOtherResponse = $strOtherResponse . '&sn_mult=1&sn_i=respin_screen&sn_pd=0';
                }

                if($mo_tv > 0){
                    $strOtherResponse = $strOtherResponse . '&mo_tv='. $mo_tv .'&mo_tw=' . ($mo_tv * $betline);
                }
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                }
                if($str_accv != ''){
                    $strOtherResponse = $strOtherResponse . '&accm=cp~tp~lvl~sc;cp~tp~lvl~sc&acci=0;1&accv=' . $str_accv;
                }
                if($wmv > 0){
                    $strOtherResponse = $strOtherResponse . '&wmt=pr&wmv=' . $wmv;
                    if($wmv > 1){
                        $strOtherResponse = $strOtherResponse . '&gwm=' . $wmv;
                    }
                }
                if($str_trail != ''){
                    $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                }
                if($rs_p >= 0){
                    $strOtherResponse = $strOtherResponse . '&rs=mc&rs_p='. $rs_p .'&rs_c='. $rs_c .'&rs_m=' . $rs_m;
                }
                if($rs_t > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t;
                }
                if($g != null){
                    $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($g));
                }
                if($currentReelSet >= 0){
                    $strOtherResponse = $strOtherResponse . '&reel_set='.$currentReelSet;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&ls=0&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .$strWinLine .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&c='.$betline.'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=10&w='. $totalWin;
                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 && $isRespin == false) 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"respinWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                    
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if( $scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin') 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                }
            }
            if($slotEvent['action'] == 'doSpin' || $slotEvent['action'] == 'doCollect' || $slotEvent['action'] == 'doCollectBonus' || $slotEvent['action'] == 'doMysteryScatter' || $slotEvent['action'] == 'doBonus'){
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

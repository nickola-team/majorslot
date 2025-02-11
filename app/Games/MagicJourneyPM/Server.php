<?php 
namespace VanguardLTE\Games\MagicJourneyPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [12,12,12,12,12]);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $strOtherResponse = '';
                $stack = null;
                
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $lastEvent->serverResponse->CurrentRespin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
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
                        $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                        if($slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') > 0){
                            $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') - 1];
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
                if(isset($stack)){                    
                    $currentReelSet = $stack['reel_set'];
                    $str_as_reel = $stack['as_reel'];
                    $str_as_initReel = $stack['as_initReel'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $as_lc = $stack['as_lc'];
                    $str_as_srf = $stack['as_srf'];
                    $str_prg_m = $stack['prg_m'];
                    $str_prg = $stack['prg'];
                    $str_as_line = $stack['as_line'];
                    $rs_more = $stack['rs_more'];
                    if($str_as_reel != ''){
                        $strOtherResponse = $strOtherResponse . '&as_s=' . $str_as_reel;
                    }
                    if($str_as_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&as_is=' . $str_as_initReel;
                    }
                    if($str_prg_m != ''){
                        $strOtherResponse = $strOtherResponse . '&prg_m=' . $str_prg_m;
                    }
                    if($str_prg != ''){
                        $strOtherResponse = $strOtherResponse . '&prg=' . $str_prg;
                    }
                    if($str_as_srf != ''){
                        $strOtherResponse = $strOtherResponse . '&as_srf=' . $str_as_srf;
                    }
                    if($str_as_line != ''){
                        $strOtherResponse = $strOtherResponse . $str_as_line;
                    }
                    if($as_lc > 0){
                        $strOtherResponse = $strOtherResponse . '&as_lc='. $as_lc .'&as_lcw=' . ($bet * 20);
                    }
                    if($rs_p >= 0){
                        $strOtherResponse = $strOtherResponse . '&rs=mc&rs_p=' . $rs_p;
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
                }else{
                    $strOtherResponse = $strOtherResponse . '&as_s=3,4,5,6,7,8,9,10,11';
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));

                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=12,12,12,12,12&as_sh=3&balance='. $Balance .'&cfgs=2625&ver=2&index=1&balance_cash='. $Balance .'&reel_set_size=2&def_sb=3,6,7,7,7&def_sa=5,8,9,6,6&reel_set='.$currentReelSet.$strOtherResponse.'&prg_cfg_m=clc,mlc&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&as_paytable=1~40;2~100;3~200;4~300;5~600;6~2000;8~8000&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=5,8,9,6,6&sb=3,6,7,7,7&prg_cfg=0,8&sc='. implode(',', $slotSettings->Bet) .'&defc=50.00&sh=1&wilds=2~0,0,0,0,0,0~1,1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&as_def_s=3,4,5,6,7,8,9,10,11&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0&l=20&reel_set0=12,3,12,4,12,5,12,6,12,7,12,8,12,9,12,10,12,11,12,10,12,6,12,6,12,6,12,6,12,5,12,7,12,7,12,8,12,10,12,10,12,9,12,11,12,11,12,10,12,10,12,6,12,6,12,6,12,6,12,6,12,7,12,7,12,8,12,10,12,10,12,9,12,10,12,10,12,10,12,10,12,12,6,12,6,12,6,12,6,12,6,12,7,12,7,12,8,12,10,12,10,12,9,12,10,12,10,12,10,12,10,12,6,12,6,12,6,12,6,12,12,6,12,7,12,7,12,8,12,10,12,10,12,9,12,10,12,10,12,10,12,10,12,8,12~12,6,12,7,12,9,12,9,12,9,12,9,12,4,12,11,12,11,12,11,12,8,12,8,12,8,12,8,12,5,12,12,6,12,9,12,9,12,10,12,9,12,9,12,11,12,11,12,11,12,11,12,8,12,8,12,8,12,8,12,8,12,6,12,9,12,9,12,9,12,9,12,9,12,11,12,11,12,11,12,11,12,12,8,12,8,12,8,12,8,12,8,12,6,12,5,12,9,12,9,12,9,12,9,12,11,12,11,12,11,12,11,12,8,12,8,12,8,12,8,12,12,8,12,6,12,5,12,9,12,9,12,9,12,9,12,11,12,11,12,11,12,11,12,8,12,8~12,7,12,7,12,9,12,6,12,6,12,6,12,6,12,6,12,6,12,4,12,7,12,7,12,9,12,9,12,5,12,12,9,12,7,12,8,12,10,12,5,12,5,12,6,12,11,12,8,12,6,12,9,12,9,12,9,12,9,12,9,12,9,12,7,12,8,12,5,12,6,12,6,12,6,12,6,12,6,12,6,12,12,9,12,9,12,9,12,9,12,9,12,5,12,5,12,8,12,5,12,5,12,5,12,6,12,6,12,6,12,6,12,9,12,9,12,9,12,9,12,12,9,12,5,12,5,12,8,12,5,12,8,12,5,12,6,12,6,12,6,12,6,12,9,12,9~12,7,12,9,12,5,12,10,12,10,12,10,12,4,12,6,12,6,12,6,12,6,12,6,12,6,12,6,12,6,12,12,9,12,9,12,9,12,8,12,10,12,10,12,5,12,11,12,6,12,6,12,6,12,6,12,6,12,6,12,6,12,9,12,9,12,9,12,10,12,10,12,10,12,5,12,6,12,6,12,5,12,12,6,12,6,12,6,12,6,12,6,12,5,12,9,12,9,12,10,12,10,12,10,12,5,12,6,12,6,12,5,12,6,12,6,12,6,12,6,12,12,6,12,5,12,9,12,9,12,10,12,10,12,10,12,5,12,8,12,8,12,5,12,6~12,7,12,5,12,9,12,10,12,4,12,10,12,5,12,5,12,5,12,5,12,5,12,6,12,6,12,6,12,6,12,12,7,12,8,12,9,12,10,12,8,12,10,12,5,12,11,12,5,12,5,12,6,12,6,12,6,12,6,12,6,12,7,12,8,12,9,12,10,12,8,12,10,12,5,12,5,12,5,12,5,12,12,6,12,6,12,6,12,6,12,6,12,7,12,8,12,9,12,10,12,8,12,10,12,5,12,8,12,8,12,5,12,6,12,6,12,6,12,6,12,12,6,12,7,12,8,12,9,12,10,12,8,12,10,12,5,12,5,12,8,12,8,12,6&s='.$lastReelStr.'&reel_set1=12,3,12,4,12,5,12,6,12,7,12,8,12,9,12,10,12,11,12,10,12,6,12,6,12,6,12,6,12,5,12,11,12,7,12,8,12,10,12,10,12,9,12,11,12,11,12,10,12,10,12,6,12,6,12,6,12,6,12,6,12,7,12,7,12,8,12,10,12,10,12,9,12,10,12,10,12,10,12,10,12,12,6,12,6,12,6,12,6,12,6,12,7,12,7,12,8,12,10,12,10,12,9~12,6,12,11,12,9,12,9,12,9,12,9,12,4,12,11,12,11,12,11,12,8,12,8,12,8,12,8,12,5,12,12,6,12,9,12,9,12,10,12,9,12,9,12,11,12,11,12,11,12,11,12,8,12,8,12,8,12,8,12,8,12,6,12,9,12,9,12,9,12,9,12,9,12,11,12,11,12,11,12,11,12,12,8,12,8,12,8,12,8,12,8,12,6,12,5,12,9,12,9,12,9,12,9~12,7,12,11,12,9,12,11,12,6,12,6,12,11,12,6,12,6,12,4,12,7,12,11,12,9,12,9,12,5,12,12,9,12,11,12,8,12,10,12,5,12,5,12,6,12,11,12,8,12,6,12,9,12,9,12,9,12,9,12,9,12,9,12,3,12,8,12,5,12,6,12,6,12,6,12,6,12,6,12,6,12,12,9,12,9,12,9,12,9,12,9,12,5,12,5,12,8,12,5,12,5,12~12,7,12,9,12,5,12,10,12,10,12,10,12,4,12,6,12,6,12,4,12,6,12,6,12,6,12,6,12,6,12,12,9,12,9,12,9,12,8,12,10,12,10,12,5,12,11,12,6,12,6,12,6,12,6,12,6,12,6,12,6,12,9,12,9,12,9,12,10,12,10,12,10,12,5,12,6,12,6,12,5,12,12,6,12,6,12,6,12,6,12,6,12,5,12,9,12,9,12,10,12~12,7,12,5,12,9,12,10,12,4,12,10,12,5,12,5,12,5,12,5,12,5,12,4,12,6,12,6,12,6,12,12,11,12,8,12,9,12,10,12,8,12,10,12,5,12,11,12,5,12,5,12,6,12,6,12,6,12,6,12,6,12,7,12,8,12,9,12,10,12,8,12,10,12,5,12,5,12,5,12,5,12,12,6,12,6,12,6,12,6,12,6,12,3,12,8,12,9,12,10,12,8&as_paytable_m=c~p;c~p;c~p;c~p;c~p;c~p;c~p';
            }
            else if( $slotEvent['slotEvent'] == 'doCollect' || $slotEvent['slotEvent'] == 'doCollectBonus') 
            {
                $Balance = $slotSettings->GetBalance();
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);    
                $response = 'balance=' . $Balance . '&index=' . $slotEvent['index'] . '&balance_cash=' . $Balance . '&balance_bonus=0.00&na=s&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sver=5&counter=' . ((int)$slotEvent['counter'] + 1);
                
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
            else if( $slotEvent['slotEvent'] == 'doSpin' || $slotEvent['slotEvent'] == 'doBonus' ) 
            {
                $lastEvent = $slotSettings->GetHistory();
                $linesId = [];
                $linesId[0] = [1, 1, 1, 1, 1];
                $linesId[1] = [2, 2, 2, 2, 2];
                $linesId[2] = [3, 3, 3, 3, 3];
                $linesId[3] = [1, 2, 3, 2, 1];
                $linesId[4] = [3, 2, 1, 2, 3];
                $linesId[5] = [1, 1, 2, 3, 3];
                $linesId[6] = [3, 3, 2, 1, 1];
                $linesId[7] = [2, 1, 1, 1, 2];
                $linesId[8] = [2, 3, 3, 3, 2];
                $linesId[9] = [1, 2, 2, 2, 1];

                $slotEvent['slotBet'] = $slotEvent['c'];
                $slotEvent['slotLines'] = 20;
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') >= 0) 
                {
                    $slotEvent['slotEvent'] = 'respin';
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
                    if( $slotEvent['slotEvent'] == 'doSpin' && $slotSettings->GetBalance() < ($lines * $betline) ) 
                    {
                        $balance_cash = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                        if(!isset($balance_cash)){
                            $balance_cash = $slotSettings->GetBalance();
                        }
                        $response = 'nomoney=1&balance='. $balance_cash .'&error_type=i&index='.$slotEvent['index'].'&balance_cash='. $balance_cash .'&balance_bonus=0.00&na=s&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&ext_code=SystemError&sver=5&counter='. ((int)$slotEvent['counter'] + 1);
                        exit( $response );
                    }
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') < $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotEvent['slotEvent'] == 'respin' ) 
                    {
                        $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"invalid bonus state"}';
                        exit( $response );
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
                $allBet = $betline * $lines;
                if($winType == 'bonus'){
                    $winType = 'win';
                }
                $freeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'respin'){
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.1f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '57409' . substr($roundstr, 2, 9);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $leftFreeGames = 0;

                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []);
                }
                
                $wild = '2';
                $scatter = '1';
                $Balance = $slotSettings->GetBalance();
                
                $totalWin = 0;
                $winLineCount = 0;
                $str_as_reel = '';
                $str_as_initReel = '';
                $rs_p = -1;
                $rs_c = -1;
                $rs_m = -1;
                $rs_t = -1;
                $as_lc = 0;
                $str_as_srf = '';
                $str_prg_m = '';
                $str_prg = '';
                $str_as_line = '';
                $rs_more = 0;
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $str_as_reel = $stack['as_reel'];
                    $str_as_initReel = $stack['as_initReel'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $as_lc = $stack['as_lc'];
                    $str_as_srf = $stack['as_srf'];
                    $str_prg_m = $stack['prg_m'];
                    $str_prg = $stack['prg'];
                    $str_as_line = $stack['as_line'];
                    $rs_more = $stack['rs_more'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $stack);  
                    $lastReel = explode(',', $stack[0]['reel']);
                    $currentReelSet = $stack[0]['reel_set'];
                    $str_as_reel = $stack[0]['as_reel'];
                    $str_as_initReel = $stack[0]['as_initReel'];
                    $rs_p = $stack[0]['rs_p'];
                    $rs_c = $stack[0]['rs_c'];
                    $rs_m = $stack[0]['rs_m'];
                    $rs_t = $stack[0]['rs_t'];
                    $as_lc = $stack[0]['as_lc'];
                    $str_as_srf = $stack[0]['as_srf'];
                    $str_prg_m = $stack[0]['prg_m'];
                    $str_prg = $stack[0]['prg'];
                    $str_as_line = $stack[0]['as_line'];
                    $rs_more = $stack[0]['rs_more'];
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                $reels = [];
                
                $lineMuls = [0, 40, 100, 200, 300, 600, 2000, 0, 8000];
                if($as_lc > 0){
                    $totalWin = $lineMuls[$as_lc] * $betline;
                }
                
                $spinType = 's';
                if( $totalWin > 0) 
                {
                    $spinType = 'c';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank('', -1 * $totalWin);
                }
                $_obf_totalWin = $totalWin;
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $rs_p);

                $strLastReel = implode(',', $lastReel);
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 5; $i++){
                    $reelA[$i] = mt_rand(5, 12);
                    $reelB[$i] = mt_rand(5, 12);
                }
                $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
               
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                
                $strOtherResponse = '';
                $isState = true;
                $isEnd = true;
                if( $slotEvent['slotEvent'] == 'respin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $strOtherResponse = $strOtherResponse . '&rw=' . $totalWin;
                    $isEnd = false;
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    
                    if( $rs_t > 0 ) 
                    {
                        $spinType = 'c';
                        $isEnd = true;
                    }
                    else
                    {
                        $isState = false;
                        $spinType = 's';
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin); 
                    if($rs_p >= 0 ){
                        $isState = false;
                        $spinType = 's';
                    }
                }
                
                if($str_as_reel != ''){
                    $strOtherResponse = $strOtherResponse . '&as_s=' . $str_as_reel;
                }
                if($str_as_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&as_is=' . $str_as_initReel;
                }
                if($str_prg_m != ''){
                    $strOtherResponse = $strOtherResponse . '&prg_m=' . $str_prg_m;
                }
                if($str_prg != ''){
                    $strOtherResponse = $strOtherResponse . '&prg=' . $str_prg;
                }
                if($str_as_srf != ''){
                    $strOtherResponse = $strOtherResponse . '&as_srf=' . $str_as_srf;
                }
                if($str_as_line != ''){
                    $strOtherResponse = $strOtherResponse . $str_as_line;
                }
                if($currentReelSet >= 0){
                    $strOtherResponse = $strOtherResponse . '&reel_set=' . $currentReelSet;
                }
                if($as_lc > 0){
                    $strOtherResponse = $strOtherResponse . '&as_lc='. $as_lc .'&as_lcw=' . $totalWin;
                }
                if($rs_p >= 0){
                    $strOtherResponse = $strOtherResponse . '&rs=mc&rs_p=' . $rs_p;
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
                
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&as_sh=3&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&l=20&sh=1&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&s='.$strLastReel.'&w='.$totalWin;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)) 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"CurrentRespin":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'). ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if($rs_p == 0 && $slotEvent['slotEvent'] != 'respin') 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                }

            }
            if($slotEvent['action'] == 'doSpin' || $slotEvent['action'] == 'doCollect' || $slotEvent['action'] == 'doBonus' || $slotEvent['action'] == 'doCollectBonus'){
                $this->saveGameLog($slotEvent, $response, $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'), $slotSettings);
            }
            try{
                $slotSettings->SaveGameData();
                \DB::commit();
            }catch (\Exception $e) {
                $slotSettings->InternalError('TicTacTakeDBCommit : ' . $e);
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

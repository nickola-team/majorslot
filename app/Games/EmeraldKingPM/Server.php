<?php 
namespace VanguardLTE\Games\EmeraldKingPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'RespinCount', -1);    
                $slotSettings->SetGameData($slotSettings->slotId . 'Wmv', 1);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [3,4,5,6,7,3,4,5,6,7,3,4,5,6,7]);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinCount', $lastEvent->serverResponse->RespinCount);    
                    $slotSettings->SetGameData($slotSettings->slotId . 'Wmv', $lastEvent->serverResponse->Wmv);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
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
                        $stack = $FreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') -1];
                    }
                    $bet = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $bet = '100.00';
                }
                $spinType = 's';
                $g = null;
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if(isset($stack)){
                    if($stack['reel_set'] >= 0){
                        $currentReelSet = $stack['reel_set'];
                    }
                    $ls = $stack['ls'];
                    $str_accm = $stack['accm'];
                    $str_accv = $stack['accv'];
                    $rs_p = $stack['rs_p'];
                    $rs_t = $stack['rs_t'];
                    $wmv = $stack['wmv'];
                    if($stack['g'] != ''){
                        $g = $stack['g'];
                    }

                    if($g != null){
                        if($rs_p > 0 || $rs_t > 0){
                            foreach($g as $key => $item){
                                if(isset($item['l0'])){
                                    $arr_wins = explode('~', $item['l0']);
                                    if(count($arr_wins) > 0){
                                        $sub_win = str_replace(',', '', $arr_wins[1]) / $original_bet * $bet;
                                        $arr_wins[1] = $sub_win;
                                        $g[$key]['l0'] = implode('~', $arr_wins);
                                    }
                                }
                            }
                        }
                        // if(!isset($g['comm'])){
                        //     $g['comm'] = [
                        //             'reel_set' => '2',
                        //             'screenOrchInit' => '{type:"mini_slots",layout_h:1,layout_w:5}'
                        //         ];
                        // }
                        // for($i = 0; $i <= 14; $i++){
                        //     if(!isset($g['ms0' . $i])){
                        //         $g['ms0' . $i] = [
                        //             'def_s' => '13,13,13',
                        //             'sh' => '1',
                        //             'st' => 'rect',
                        //             'sw' => '3'
                        //         ];
                        //     }
                        // }
                    }
                    
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    
                    if($str_accv != ''){
                        $strOtherResponse = $strOtherResponse . '&acci=0&accv=' . $str_accv . '&accm=' . $str_accm;
                    }
                    if($rs_p >= 0){
                        $strOtherResponse = $strOtherResponse . '&rs=mc&rs_p='. $rs_p .'&rs_c='. ($rs_p + 1) .'&rs_m=' . ($rs_p + 1);
                    }
                    if($rs_t > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t;
                    }
                    if($wmv > 0){
                        $strOtherResponse = $strOtherResponse . '&wmt=pr&wmv=' . $wmv;
                        if($wmv > 1){
                            $strOtherResponse = $strOtherResponse . '&gwm=' . $wmv;
                        }
                    }
                    if($ls >= 0){
                        $strOtherResponse = $strOtherResponse . '&ls=' . $ls;
                    }                    
                }else{
                    $strOtherResponse = $strOtherResponse . '&accm=cp~pp;cp~pp&acci=0;1&accv=1~1;0~0';
                }
                if($g != null){
                    $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($g));
                }else{
                    $strOtherResponse = $strOtherResponse . '&g={comm:{reel_set:"2",screenOrchInit:"{type:\"mini_slots\",layout_h:3,layout_w:5}"},ms00:{def_s:"13,13,13",sh:"1",st:"rect",sw:"3"},ms01:{def_s:"13,13,13",sh:"1",st:"rect",sw:"3"},ms02:{def_s:"13,13,13",sh:"1",st:"rect",sw:"3"},ms03:{def_s:"13,13,13",sh:"1",st:"rect",sw:"3"},ms04:{def_s:"13,13,13",sh:"1",st:"rect",sw:"3"},ms05:{def_s:"13,13,13",sh:"1",st:"rect",sw:"3"},ms06:{def_s:"13,13,13",sh:"1",st:"rect",sw:"3"},ms07:{def_s:"13,13,13",sh:"1",st:"rect",sw:"3"},ms08:{def_s:"13,13,13",sh:"1",st:"rect",sw:"3"},ms09:{def_s:"13,13,13",sh:"1",st:"rect",sw:"3"},ms10:{def_s:"13,13,13",sh:"1",st:"rect",sw:"3"},ms11:{def_s:"13,13,13",sh:"1",st:"rect",sw:"3"},ms12:{def_s:"13,13,13",sh:"1",st:"rect",sw:"3"},ms13:{def_s:"13,13,13",sh:"1",st:"rect",sw:"3"},ms14:{def_s:"13,13,13",sh:"1",st:"rect",sw:"3"}}';
                }
                // if($currentReelSet >= 0){
                //     $strOtherResponse = $strOtherResponse . '&reel_set='. $currentReelSet;
                // }
                
                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=3,4,5,6,7,3,4,5,6,7,3,4,5,6,7&balance='. $Balance .'&cfgs=3473&ver=2&index=1&balance_cash='. $Balance .'&def_sb=5,7,7,8,2&reel_set_size=3&def_sa=8,3,4,3,3&reel_set='. $currentReelSet .'&balance_bonus=0.00&na='. $spinType . $strOtherResponse.'&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&wl_i=tbm~20000&cpri=2&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa=8,3,4,3,3&sb=5,7,7,8,2&sc='. implode(',', $slotSettings->Bet) .'&defc=100.00&sh=3&wilds=2~500,100,50,0,0~1,1,1,1,1&bonuses=0&fsbonus=&st=rect&c='.$bet.'&sw=5&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;500,100,50,0,0;100,50,30,0,0;50,30,20,0,0;30,20,10,0,0;30,20,10,0,0;20,10,5,0,0;20,10,5,0,0;400,0,0;300,0,0;200,0,0;0,0,0&l=20&reel_set0=3,6,8,9,7,7,9,9,9,7,9,9,9,5,5,7,9,4,6,5,5,5,7,8,7,8,7,5,7,7,7,3,9,9,9,9,2,5,6,9,9,9,7,7,7,7~3,8,8,8,9,9,4,4,4,8,8,8,6,6,6,8,8,6,6,6,4,4,8,5,8,8,6,6,8,8,8,8,6,6,6,2,6,6,8,4,7,3,3~3,9,9,9,7,9,7,9,7,7,7,4,4,4,9,9,9,5,9,9,8,8,8,6,6,6,5,5,5,9,7,2,9,3,3~3,7,8,6,6,8,8,8,6,5,5,5,8,8,8,6,5,9,7,7,7,7,4,4,4,2,8,9,9,9,4,8,3,3,3,6,6,6~3,9,5,7,7,7,4,4,4,9,9,7,7,7,9,9,9,8,8,8,7,5,5,5,5,9,9,5,5,5,6,9,9,9,6,6,6,2,5,2,3,3,3,5&s='.$lastReelStr.'&accInit=[{id:0,mask:"cp;pp;mp"},{id:1,mask:"cp;pp;mp"}]&reel_set2=10,12,12,12,11,11,10,12,12,10,10,11,13,13,13,10,10,10,12,12,11,11,11,11,13~10,12,12,12,10,10,10,11,13,13,11,11,10,10,10,11,11,11,12,12,12,12,13~10,12,12,12,13,13,10,10,13,13,13,11,11,10,10,10,11&reel_set1=3,3,3,6,6,2,4,4,4,9,9,2,2,2,5,5,5,7,2,3,2,2,2,9,8,8,8,8,7,9,9,9,9,7,7,7,2,2,9,7,9~3,3,3,8,8,2,9,6,4,4,4,8,8,8,9,9,9,6,6,6,2,2,4,5,5,5,8,8,7,7,7,6,2,2,2,3~3,3,3,6,6,6,2,7,3,8,7,7,7,5,5,9,8,8,8,2,2,2,4,4,4,2,6,6,5,5,5,2,2,9,9,9,9,7~3,3,3,7,8,9,9,9,7,8,2,2,8,5,8,8,8,6,2,2,2,7,7,7,2,2,2,8,6,6,6,6,4,4,7,4,4,4,8~3,3,3,9,5,7,7,4,4,4,6,2,2,2,8,7,9,9,8,8,8,5,3,9,2,6,5,2,2,2,9,9,9,7,7,7,2,9,9&cpri_mask=tbw';
            }
            else if( $slotEvent['slotEvent'] == 'doCollect' || $slotEvent['slotEvent'] == 'doCollectBonus') 
            {
                $Balance = $slotSettings->GetBalance();
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);    
                $response = 'balance=' . $Balance . '&index=' . $slotEvent['index'] . '&balance_cash=' . $Balance . '&balance_bonus=0.00&na=s&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&na=s&sver=5&counter=' . ((int)$slotEvent['counter'] + 1);
                
                //------------ ReplayLog ---------------                
                $lastEvent = $slotSettings->GetHistory();
                if($lastEvent != 'NULL'){
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
                $linesId[0] = [2, 2, 2, 2, 2];
                $linesId[1] = [1, 1, 1, 1, 1];
                $linesId[2] = [3, 3, 3, 3, 3];
                $linesId[3] = [1, 2, 3, 2, 1];
                $linesId[4] = [3, 2, 1, 2, 3];                
                $linesId[5] = [1, 1, 2, 3, 3];
                $linesId[6] = [3, 3, 2, 1, 1];
                $linesId[7] = [2, 1, 1, 1, 2];
                $linesId[8] = [2, 3, 3, 3, 2];
                $linesId[9] = [1, 2, 2, 2, 1];
                $linesId[10] = [3, 2, 2, 2, 3];
                $linesId[11] = [2, 1, 2, 3, 2];
                $linesId[12] = [2, 3, 2, 1, 2];
                $linesId[13] = [1, 3, 1, 3, 1];
                $linesId[14] = [3, 1, 3, 1, 3];
                $linesId[15] = [1, 2, 1, 2, 1];
                $linesId[16] = [3, 2, 3, 2, 3];
                $linesId[17] = [1, 3, 3, 3, 3];
                $linesId[18] = [3, 1, 1, 1, 1];
                $linesId[19] = [2, 2, 1, 2, 2];

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
                if($lastEvent == 'NULL' || $lastEvent->serverResponse->bet != $betline){
                    $slotSettings->SetGameData($slotSettings->slotId . 'Wmv', 1);
                }
                
                $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $betline * $lines, $lines);
                $winType = $_spinSettings[0];
                $_winAvaliableMoney = $_spinSettings[1];
                if($winType == 'bonus'){
                    $winType = 'win';
                }
                // $winType = 'bonus';

                $allBet = $betline * $lines;
                $freeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'freespin' || $slotSettings->GetGameData($slotSettings->slotId . 'RespinCount') >= 0){
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinCount', -1); 
                    // $slotSettings->SetGameData($slotSettings->slotId . 'Wmv', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '561' . substr($roundstr, 4, 10);
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
                $wildValues = [];
                $wildPoses = [];
                $strWinLine = '';
                $winLineCount = 0;
                $ls = 0;
                $str_accm = '';
                $str_accv = '';
                $rs_p = -1;
                $rs_t = 0;
                $wmv = -1;
                $g = null;
                $subScatterReel = null;
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $ls = $stack['ls'];
                    $str_accm = $stack['accm'];
                    $str_accv = $stack['accv'];
                    $rs_p = $stack['rs_p'];
                    $rs_t = $stack['rs_t'];
                    $wmv = $stack['wmv'];
                    if($stack['g'] != ''){
                        $g = $stack['g'];
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
                    $currentReelSet = $stack[0]['reel_set'];
                    $ls = $stack[0]['ls'];
                    $str_accm = $stack[0]['accm'];
                    $str_accv = $stack[0]['accv'];
                    $rs_p = $stack[0]['rs_p'];
                    $rs_t = $stack[0]['rs_t'];
                    $wmv = $stack[0]['wmv'];
                    if($stack[0]['g'] != ''){
                        $g = $stack[0]['g'];
                    }
                }


                $reels = [];
                $rainbowCount = 0;
                $emeraldCount = 0;
                for($i = 0; $i < 5; $i++){
                    $reels[$i] = [];
                    for($j = 0; $j < 3; $j++){
                        $reels[$i][$j] = $lastReel[$j * 5 + $i];
                    }
                }
                if($rs_p > 0 || $rs_t > 0){
                    foreach($g as $key => $item){
                        if(isset($item['l0'])){
                            $arr_wins = explode('~', $item['l0']);
                            if(count($arr_wins) > 0){
                                $sub_win = str_replace(',', '', $arr_wins[1]) / $original_bet * $betline;
                                $arr_wins[1] = $sub_win;
                                $totalWin = $totalWin + $sub_win;
                                $g[$key]['l0'] = implode('~', $arr_wins);
                            }
                        }
                    }
                }else{
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
                                    if($wmv > 1){
                                        $lineWins[$k] = $lineWins[$k] * $wmv;
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
                                    if($wmv > 1){
                                        $lineWins[$k] = $lineWins[$k] * $wmv;
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
                                    if($wmv > 1){
                                        $lineWins[$k] = $lineWins[$k] * $wmv;
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
                if($rs_p == 0){
                    $bonuswin = $betline * $lines * 2;
                    if($wmv > 1){
                        $bonuswin = $bonuswin * $wmv;
                    }
                    $totalWin = $totalWin + $bonuswin;
                }
                $spinType = 's';
                if( $totalWin > 0) 
                {
                    $spinType = 'c';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }
                $_obf_totalWin = $totalWin;
                
                $strLastReel = implode(',', $lastReel);
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 5; $i++){
                    $reelA[$i] = mt_rand(4, 7);
                    $reelB[$i] = mt_rand(4, 7);
                }
                $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
               
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $strOtherResponse = '';
                $isState = true;
                
                $slotSettings->SetGameData($slotSettings->slotId . 'RespinCount', $rs_p);    
                if( $rs_p >= 0 || $rs_t > 0) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    if($rs_t > 0){
                        $spinType = 'c';
                    }else{
                        $isState = false;
                        $spinType = 's';
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                }
                if($str_accv != ''){
                    $strOtherResponse = $strOtherResponse . '&acci=0;1&accv=' . $str_accv . '&accm=' . $str_accm;
                    $arr_accv = explode(';', $str_accv);
                    $next_wmv = 1;
                    if(isset($arr_accv[0])){
                        $next_wmv = explode('~', $arr_accv[0])[0];
                        $slotSettings->SetGameData($slotSettings->slotId . 'Wmv', $next_wmv);
                    }

                }
                if($rs_p >= 0){
                    $strOtherResponse = $strOtherResponse . '&rs=mc&rs_p='. $rs_p .'&rs_c='. ($rs_p + 1) .'&rs_m=' . ($rs_p + 1);
                }
                if($rs_t > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t;
                }
                if($wmv > 0){
                    $strOtherResponse = $strOtherResponse . '&wmt=pr&wmv=' . $wmv;
                    if($wmv > 1){
                        $strOtherResponse = $strOtherResponse . '&gwm=' . $wmv;
                    }
                }
                if($g != null){
                    $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($g));
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse . $strWinLine .'&ls='. $ls .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&c='.$betline.'&sw=5&sver=5&reel_set='.$currentReelSet.'&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&s='.$strLastReel.'&w='.$totalWin;
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"RespinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinCount') . ',"Wmv":' . $slotSettings->GetGameData($slotSettings->slotId . 'Wmv') .',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if( $rs_p == 0 || $rainbowCount >= 3 || $emeraldCount >= 3) 
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
                $ind = -1;
                if(isset($slotEvent['ind'])){
                    $ind = $slotEvent['ind'];
                }

                $bgt = $slotSettings->GetGameData($slotSettings->slotId . 'Bgt');
                $bonusType = $slotSettings->GetGameData($slotSettings->slotId . 'BonusType');
                $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                               
                $str_trail = $stack['trail'];
                $ls = $stack['ls'];
                $str_accm = $stack['accm'];
                $str_accv = $stack['accv'];
                $rs_p = $stack['rs_p'];
                $rs_t = $stack['rs_t'];
                $bmw = $stack['bmw'];
                $bw = $stack['bw'];
                $level = $stack['level'];
                $wp = $stack['wp'];
                $lifes = $stack['lifes'];
                $end = $stack['end'];
                $g = null;
                if($stack['g'] != ''){
                    $g = $stack['g'];
                }
                if($g != null){
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BonusType') == 1){
                        if($ind >= 0){
                            $new_wins = $slotSettings->GetGameData($slotSettings->slotId . 'Wins');
                            $new_status = $slotSettings->GetGameData($slotSettings->slotId . 'Status');
    
                            $wins = explode(',', $g['eb']['wins']);
                            $status = explode(',', $g['eb']['status']);
                            $wins_mask = explode(',', $g['eb']['wins_mask']);
                            $wi = $g['eb']['wi'];
                            $new_wins_mask = [];
                            for($k = 0; $k < count($new_wins); $k++){
                                if($new_wins[$k] == 0){
                                    $new_wins_mask[$k] = 'h';
                                }else{
                                    $new_wins_mask[$k] = 'ma';
                                }
                            }
                            if($ind >= 0 && $level > 0){
                                $new_wins[$ind] = $wins[$wi];
                                $new_wins_mask[$ind] = $wins_mask[$wi];
                                $new_status[$ind] = $status[$wi];
                            }
                            $g['eb']['ch'] = 'ind~' . $ind;
                            $g['eb']['status'] = implode(',', $new_status);
                            $g['eb']['wins'] = implode(',', $new_wins);
                            $g['eb']['wins_mask'] = implode(',', $new_wins_mask);
                            $g['eb']['wi'] = $ind;
                        }
                        $slotSettings->SetGameData($slotSettings->slotId . 'Wins', explode(',', $g['eb']['wins']));
                        $slotSettings->SetGameData($slotSettings->slotId . 'Status', explode(',', $g['eb']['status']));
                    }else{
                        if($rs_p > 0 || $rs_t > 0){
                            foreach($g as $key => $item){
                                if(isset($item['l0'])){
                                    $arr_wins = explode('~', $item['l0']);
                                    if(count($arr_wins) > 0){
                                        $sub_win = $arr_wins[1] / $original_bet * $betline;
                                        $arr_wins[1] = $sub_win;
                                        $g[$key]['l0'] = implode('~', $arr_wins);
                                    }
                                }
                            }
                        }
                    }
                }

                $slotSettings->SetGameData($slotSettings->slotId . 'Level', $level);
                $slotSettings->SetGameData($slotSettings->slotId . 'Ind', $ind);
                
                $totalWin = 0;
                $coef = $betline * $lines;
                if($bmw > 0){
                    $bmw = $bmw / $original_bet * $betline;                        
                }
                $isState = false;
                if($end == 1){
                    if($wp > 0){
                        $totalWin = $wp * $coef;
                    }
                    if($bmw > 0){
                        $totalWin += $bmw;
                    }
                    $isState = true;
                }
                $spinType = 'b';
                if( $totalWin > 0) 
                {
                    $spinType = 'cb';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusType', 0);
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                $strOtherResponse = '';
                if($totalWin > 0){
                    $strOtherResponse = $strOtherResponse . '&end=1&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }else{
                    $strOtherResponse = $strOtherResponse . '&end=0';
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                if($end < 1){
                    if($bmw > 0){
                        $strOtherResponse = $strOtherResponse . '&rw=' . $bmw;
                    }else{
                        $strOtherResponse = $strOtherResponse . '&rw=0';
                    }                    
                }else{
                    $strOtherResponse = $strOtherResponse . '&rw=' . $totalWin;
                }
                if($lifes >= 0){
                    $strOtherResponse = $strOtherResponse . '&lifes=' . $lifes;
                }
                if($bmw >= 0){
                    $strOtherResponse = $strOtherResponse . '&bmw=' . $bmw;
                }
                if($bonusType == 1){
                    $strOtherResponse = $strOtherResponse . '&bgid=0';
                }else{
                    $strOtherResponse = $strOtherResponse . '&bgid=1';
                }
                if($rs_p >= 0){
                    $strOtherResponse = $strOtherResponse . '&rs=mc&rs_p='. $rs_p .'&rs_c='. ($rs_p + 1) .'&rs_m=' . ($rs_p + 1);
                }
                if($rs_t > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t;
                }
                if($g != null){
                    $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($g));
                }
                
                $response = 'ls=1' . $strOtherResponse .'&balance='. $Balance .'&coef='. $coef .'&level='. $level .'&index='.$slotEvent['index'].'&balance_cash='. $Balance .'&balance_bonus=0.00&na='. $spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&bgt=50&wp='. $wp .'&sver=5&counter='. ((int)$slotEvent['counter'] + 1);

                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"RespinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinCount') . ',"BonusType":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusType') . ',"Ind":' . $slotSettings->GetGameData($slotSettings->slotId . 'Ind')  . ',"Level":' . $slotSettings->GetGameData($slotSettings->slotId . 'Level') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"Wins":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'Wins')) . ',"Status":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'Status')) .',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
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

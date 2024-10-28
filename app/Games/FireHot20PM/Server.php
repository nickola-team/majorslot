<?php 
namespace VanguardLTE\Games\FireHot20PM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [1,5,7,4,7,11,10,5,2,10,3,6,9,4,8]);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
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
                    $bet = '50.00';
                }
                $spinType = 's';
                $fsmore = 0;
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if($stack != null){
                    if($stack['win_line'] != ''){
                        $strWinLine = $stack['win_line'];
                        if($strWinLine != ''){
                            $arr_lines = explode('&', $strWinLine);
                            for($k = 0; $k < count($arr_lines); $k++){
                                $arr_sub_lines = explode('~', $arr_lines[$k]);
                                $arr_sub_lines[1] = str_replace(',', '', $arr_sub_lines[1]) / $original_bet * $bet;
                                $arr_lines[$k] = implode('~', $arr_sub_lines);
                            }
                            $strWinLine = implode('&', $arr_lines);
                        } 
                        $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                    }
                    
                    $str_stf = $stack['stf'];
                    $str_initReel = $stack['is'];
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($str_stf != ''){
                        $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                    }
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');

                }
                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=1,5,7,4,7,11,10,5,2,10,3,6,9,4,8&balance='. $Balance .'&cfgs=6220&ver=3&index=1&balance_cash='. $Balance .'&def_sb=11,7,6,3,8&reel_set_size=1&def_sa=11,9,5,2,6&reel_set=0&balance_bonus=0.00&na=s&scatters=1~100,25,3,0,0~0,0,0,0,0~1,1,1,1,1;11~0,0,20,0,0~0,0,0,0,0~1,1,1,1,1&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"38019144"}}&stime='. floor(microtime(true) * 1000) . $strOtherResponse .'&sa=11,9,5,2,6&sb=11,7,6,3,8&sc='. implode(',', $slotSettings->Bet) . '&defc=50.00&sh=3&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&st=rect&c='. $bet .'&sw=5&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;2700,230,50,10,0;520,110,40,0,0;520,110,40,0,0;230,60,20,0,0;100,35,10,0,0;100,35,10,0,0;100,35,10,0,0;100,35,10,0,0;0,0,0,0,0&l=20&reel_set0=3,9,4,5,7,9,8,8,7,4,8,4,11,10,10,10,10,6,3,7,7,9,10,11,8,1,10,11,7,9,7,7,7,7,10,5,7,4,8,9,9,5,7,8,8,10,10,8,5,5,5,5,8,10,7,9,9,10,7,3,9,3,10,6,10,5,6,6,6,6,7,10,5,6,10,8,9,10,4,1,4,10,5,4,4,4,4,10,8,9,11,3,10,3,8,10,7,6,3,9,9,8,9,9,9,9,7,10,10,9,8,6,9,1,5,4,5,10,4,6,10,8,8,8,8,6,9,1,6,5,7,10,10,8,6,8,10,11,8,6~8,1,10,5,9,10,1,8,6,7,7,7,7,10,8,9,10,8,1,10,4,9,2,9,9,9,9,3,2,5,5,9,4,5,9,3,5,10,10,10,10,8,7,4,9,9,10,9,7,7,5,5,5,5,3,6,7,8,7,5,6,7,9,8,8,8,8,6,4,5,7,5,10,8,8,7,4,4,4,4,10,6,5,7,3,9,4,8,7,10,6,6,6,6,4,7,9,9,7,4,8,9,10,4,7~8,9,6,10,4,7,4,9,10,2,7,5,8,9,9,9,9,7,6,5,3,6,3,9,10,8,4,10,4,6,3,9,4,4,4,4,6,7,1,6,3,8,1,6,7,6,8,3,8,4,10,10,10,10,6,7,11,2,7,6,7,4,9,8,7,5,3,6,7,7,7,7,11,6,8,7,7,9,4,7,6,11,7,5,5,5,10,10,6,4,10,4,9,6,5,8,1,9,3,7,6,6,6,6,10,1,5,3,8,5,8,9,7,7,8,2,9,4,7,8,8,8,8,6,10,7,9,4,4,8,10,6,5,8,7,8,11,9,7~5,3,7,9,3,9,7,7,5,7,8,10,8,8,8,8,9,8,4,6,7,6,5,3,9,7,6,9,9,9,9,10,7,7,9,2,6,6,7,6,10,8,1,5,4,4,4,4,5,6,1,7,10,3,10,8,6,8,7,3,4,10,10,10,10,6,8,4,4,9,9,7,1,10,5,9,9,8,7,7,7,7,10,9,4,8,6,5,4,10,9,7,9,10,4,5,6,6,6,6,4,10,4,8,8,4,6,3,8,6,6,3,8,5,5,5,5,4,8,9,9,8,5,2,10,7,10,7,1,4,8~6,11,10,6,9,7,8,5,6,7,7,8,9,8,8,8,8,7,3,7,11,9,11,9,4,3,8,10,3,9,6,6,6,6,4,10,3,7,8,5,1,4,10,4,9,3,7,5,10,7,7,7,7,9,9,7,8,9,8,4,8,10,4,7,6,9,9,11,9,9,9,9,6,4,8,1,10,1,4,4,8,8,9,10,9,9,6,5,5,5,5,8,9,4,9,7,1,8,6,10,3,5,10,4,8,5,10,10,10,10,6,7,4,8,7,6,8,6,7,4,5,7,9,8,6,4,4,4,4,9,3,8,9,7,3,6,10,8,4,5,7,5,4,10&s='.$lastReelStr;
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
                if($winType == 'bonus'){
                    $winType = 'win';
                }
                // $winType = 'bonus';

                $allBet = $betline * $lines;
                $tumbAndFreeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    $leftFreeGames = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') - $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame'); 
                    $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                }
                else
                {
                    $slotEvent['slotEvent'] = 'bet';
                    $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                    $_sum = $allBet / 100 * $slotSettings->GetPercent();
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $_sum, $slotEvent['slotEvent']);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '6074458' . substr($roundstr, 4, 10);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $leftFreeGames = 0;

                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []);
                }
                
                $wild = '2';
                $specialScatter = '11';
                $scatter = '1';
                $Balance = $slotSettings->GetBalance();
                $totalWin = 0;
                $bonusMpl = 1;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $str_stf = '';
                $str_initReel = '';
                $reels = [];
                if($slotEvent['slotEvent'] == 'freespin'){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $strWinLine = $stack['win_line'];
                    $str_stf = $stack['stf'];
                    $str_initReel = $stack['is'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $lastReel = explode(',', $stack[0]['reel']);
                    $strWinLine = $stack[0]['win_line'];
                    $str_stf = $stack[0]['stf'];
                    $str_initReel = $stack[0]['is'];
                }
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;
                $specialScatterCount = 0;
                $specialScatterPoses = [];
                $specialScatterWin = 0;
                for($i = 0; $i < 15; $i++){
                    if($lastReel[$i] == $scatter){
                        $scatterCount++;
                        $scatterPoses[] = $i;
                    }else if($lastReel[$i] == $specialScatter){
                        $specialScatterCount++;
                        $specialScatterPoses[] = $i;
                    }
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
                if($scatterCount >= 3){
                    $muls = [0, 0, 0, 3, 25, 100];
                    $scatterWin = $muls[$scatterCount] * $betline * $lines;
                    $totalWin = $totalWin + $scatterWin;
                }
                if($specialScatterCount == 3){
                    $specialScatterWin = $betline * $lines * 20;
                    $totalWin = $totalWin + $specialScatterWin;
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
                for($i = 0; $i < 5; $i++){
                    $reelA[$i] = mt_rand(4, 9);
                    $reelB[$i] = mt_rand(4, 9);
                }
                $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
                $strLastReel = implode(',', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                $strOtherResponse = '';
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($str_stf != ''){
                    $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                }
                $arr_psym = [];
                if($scatterCount >= 3){
                    $arr_psym[] = '1~'. $scatterWin .'~' . implode(',', $scatterPoses);
                }
                if($specialScatterCount == 3){
                    $arr_psym[] = '11~'. $specialScatterWin .'~' . implode(',', $specialScatterPoses);
                }
                if(count($arr_psym) > 0){
                    $strOtherResponse = $strOtherResponse . '&psym=' . implode(';', $arr_psym);
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&c='.$betline.'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) . '&w=' . $totalWin .'&l=20&s=' . $strLastReel;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if( $slotEvent['slotEvent'] != 'freespin' && $scatterCount >= 3) 
                {
                    // $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
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

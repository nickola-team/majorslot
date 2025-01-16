<?php 
namespace VanguardLTE\Games\PirateGoldPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 40);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [6,7,4,2,8,4,3,5,6,7,8,5,7,3,4,4,3,5,6,7]);
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
                    $bet = '25.00';
                }
                $spinType = 's';
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if(isset($stack)){
                    if($stack['reel_set'] >= 0){
                        $currentReelSet = $stack['reel_set'];
                    }
                    $str_initReel = $stack['initReel'];
                    $str_rsb_s = $stack['rsb_s'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $rsb_rt = $stack['rsb_rt'];
                    $rsb_c = $stack['rsb_c'];
                    $rsb_m = $stack['rsb_m'];
                    $rsb_mu = $stack['rsb_mu'];
                    $bw = $stack['bw'];
                    $wp = $stack['wp'];
                    $e_aw = $stack['e_aw'];
                    $bpw = str_replace(',', '', $stack['bpw']);
                    $rw = str_replace(',', '', $stack['rw']);
                    $fsmax = $stack['fsmax'];
                    $fsmore = $stack['fsmore'];
                    $na = $stack['na'];
                    $strWinLine = $stack['win_line'];
                    if($na == 'b'){
                        $spinType = 'b';
                    }
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($str_rsb_s != ''){
                        $strOtherResponse = $strOtherResponse . '&rsb_s=' . $str_rsb_s;
                    }
                    if($str_mo != ''){
                        $strOtherResponse = $strOtherResponse . '&mo='. $str_mo .'&mo_t='. $str_mo_t;
                    }
                    if($rsb_c >= 0){
                        $strOtherResponse = $strOtherResponse . '&rsb_c='. $rsb_c .'&rsb_m='. $rsb_m;
                    }
                    if($rsb_rt >= 0){
                        $strOtherResponse = $strOtherResponse . '&rsb_rt=' . $rsb_rt;
                    }
                    if($rsb_mu >= 0){
                        $strOtherResponse = $strOtherResponse . '&rsb_mu=' . $rsb_mu;
                    }
                    if($wp >= 0){
                        $strOtherResponse = $strOtherResponse . '&wp=' . $wp;
                    }
                    if($bpw > 0){
                        $strOtherResponse = $strOtherResponse . '&bpw=' . ($bpw / $original_bet * $bet);
                    }
                    if($rw > 0){
                        $strOtherResponse = $strOtherResponse . '&rw=' . ($rw / $original_bet * $bet);
                    }
                    if($e_aw >= 0){
                        $strOtherResponse = $strOtherResponse . '&e_aw=' . $e_aw;
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
                $response = 'def_s=6,7,4,2,8,4,3,5,6,7,8,5,7,3,4,4,3,5,6,7&balance='. $Balance . $strOtherResponse .'&cfgs=2419&ver=2&mo_s=13&index=1&balance_cash='. $Balance . '&reel_set_size=2&def_sb=3,4,7,6,8&mo_v=40,80,120,160,200,240,280,320,400,560,640,720,800,960,2000,8000&def_sa=8,7,5,3,7&mo_jp=2000;8000;40000&balance_bonus=0.00&na='. $spinType.'&scatters=1~0,0,1,0,0~10,10,10,0,0~1,1,1,1,1&gmb=0,0,0&bg_i=2,3,5,2,3,5&rt=d&mo_jp_mask=jp3;jp2;jp1&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa=8,7,5,3,7&sb=3,4,7,6,8&sc='. implode(',', $slotSettings->Bet) .'&defc=25.00&sh=4&wilds=2~0,0,0,0,0~1,1,1,1,1;15~0,0,0,0,0~1,1,1,1,1;16~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&n_reel_set=0&bg_i_mask=bgm,bgm,bgm,fgm,fgm,fgm&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;500,100,20,2,0;500,100,20,2,0;300,50,15,2,0;300,50,15,2,0;200,40,10,2,0;200,40,10,2,0;75,25,5,0,0;75,25,5,0,0;50,15,5,0,0;50,15,5,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0&l=40&reel_set0=8,10,3,3,3,3,11,9,8,8,6,6,5,5,7,7,4,4,4,4,9,6,6,13,13,13,10,12,5,5,11,8,8,7,7~11,10,9,1,12,5,5,7,7,6,6,2,2,11,9,7,7,3,3,3,3,10,8,8,6,6,13,13,13,13,4,4,4,4,5,5,8,8~8,10,6,6,7,7,3,3,3,3,1,11,9,5,5,8,8,13,13,13,13,6,6,2,2,11,4,4,4,4,5,5,12,9,8,8,10,7,7~8,11,13,13,13,13,3,3,3,3,10,9,1,12,7,7,7,7,6,6,6,6,2,2,2,2,10,4,4,4,4,5,5,5,5,9,8,8,8,8,11~8,11,9,10,6,6,6,6,2,2,2,2,10,12,4,4,4,4,9,3,3,3,3,13,13,13,13,13,5,5,5,5,11,8,8,8,8,7,7,7,7&s='.$lastReelStr.'&reel_set1=8,10,3,3,3,3,11,9,8,8,6,6,5,5,7,7,4,4,4,4,9,6,6,13,13,13,10,12,5,5,11,8,8,7,7~11,10,9,1,12,5,5,7,7,6,6,2,2,11,15,15,15,15,9,7,7,16,16,16,16,10,8,8,6,6,13,13,13,13,5,5,8,8~8,10,16,16,16,16,6,6,7,7,1,11,9,15,15,15,15,5,5,8,8,13,13,13,13,6,6,2,2,11,5,5,12,9,8,8,10,7,7~8,11,13,13,13,13,10,16,16,16,16,9,1,12,7,7,7,7,6,6,6,6,15,15,15,15,2,2,2,2,10,5,5,5,5,9,8,8,8,8,11~8,11,9,10,6,6,6,6,2,2,2,2,10,16,16,16,16,12,9,13,13,13,13,13,5,5,5,5,11,15,15,15,15,8,8,8,8,7,7,7,7';
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

                // $winType = 'bonus';

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
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $str_initReel = '';
                $str_rsb_s = '';
                $str_mo = '';
                $str_mo_t = '';
                $rsb_rt = -1;
                $rsb_c = -1;
                $rsb_m = -1;
                $rsb_mu = -1;
                $bw = -1;
                $wp = -1;
                $e_aw = -1;
                $bpw = 0;
                $rw = 0;
                $fsmax = 0;
                $fsmore = 0;
                $na = '';
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $str_initReel = $stack['initReel'];
                    $str_rsb_s = $stack['rsb_s'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $rsb_rt = $stack['rsb_rt'];
                    $rsb_c = $stack['rsb_c'];
                    $rsb_m = $stack['rsb_m'];
                    $rsb_mu = $stack['rsb_mu'];
                    $bw = $stack['bw'];
                    $wp = $stack['wp'];
                    $e_aw = $stack['e_aw'];
                    $bpw = str_replace(',', '', $stack['bpw']);
                    $rw = str_replace(',', '', $stack['rw']);
                    $fsmax = $stack['fsmax'];
                    $fsmore = $stack['fsmore'];
                    $na = $stack['na'];
                    $strWinLine = $stack['win_line'];
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
                    $str_initReel = $stack[0]['initReel'];
                    $str_rsb_s = $stack[0]['rsb_s'];
                    $str_mo = $stack[0]['mo'];
                    $str_mo_t = $stack[0]['mo_t'];
                    $rsb_rt = $stack[0]['rsb_rt'];
                    $rsb_c = $stack[0]['rsb_c'];
                    $rsb_m = $stack[0]['rsb_m'];
                    $rsb_mu = $stack[0]['rsb_mu'];
                    $bw = $stack[0]['bw'];
                    $wp = $stack[0]['wp'];
                    $e_aw = $stack[0]['e_aw'];
                    $bpw = str_replace(',', '', $stack[0]['bpw']);
                    $rw = str_replace(',', '', $stack[0]['rw']);
                    $fsmax = $stack[0]['fsmax'];
                    $fsmore = $stack[0]['fsmore'];
                    $na = $stack[0]['na'];
                    $strWinLine = $stack[0]['win_line'];
                }


                $scatterCount = 0;
                $scatterWin = 0;
                $scatterPoses = [];
                for($i = 0; $i < 20; $i++){
                    if($lastReel[$i] == 1){
                        $scatterCount++;
                        $scatterPoses[] = $i;
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
                    $scatterWin = $betline * $lines;
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
                $isState = true;
                if($fsmax > 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $fsmax);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                }else if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                }
                
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
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1&psym=1~'. $scatterWin .'~' . implode(',', $scatterPoses);
                    }
                }  

                if($na == 'b'){
                    $spinType = 'b';
                    $isState = false;
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($str_rsb_s != ''){
                    $strOtherResponse = $strOtherResponse . '&rsb_s=' . $str_rsb_s;
                }
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo='. $str_mo .'&mo_t='. $str_mo_t;
                }
                if($rsb_c >= 0){
                    $strOtherResponse = $strOtherResponse . '&rsb_c='. $rsb_c .'&rsb_m='. $rsb_m;
                }
                if($rsb_rt >= 0){
                    $strOtherResponse = $strOtherResponse . '&rsb_rt=' . $rsb_rt;
                }
                if($rsb_mu >= 0){
                    $strOtherResponse = $strOtherResponse . '&rsb_mu=' . $rsb_mu;
                }
                if($wp >= 0){
                    $strOtherResponse = $strOtherResponse . '&wp=' . $wp;
                }
                if($bpw > 0){
                    $strOtherResponse = $strOtherResponse . '&bpw=' . ($bpw / $original_bet * $betline);
                }
                if($rw > 0){
                    $strOtherResponse = $strOtherResponse . '&rw=' . ($rw / $original_bet * $betline);
                }
                if($e_aw >= 0){
                    $strOtherResponse = $strOtherResponse . '&e_aw=' . $e_aw;
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse . '&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=4&c='.$betline.'&sw=5&sver=5&n_reel_set='.$currentReelSet.'&counter='. ((int)$slotEvent['counter'] + 1) .'&l=40&s='.$strLastReel.'&w='.$totalWin;
                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  .  ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') .',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if( $slotEvent['slotEvent'] != 'freespin' && ($fsmax > 0 || $spinType == 'b')) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                }
            }else if( $slotEvent['slotEvent'] == 'doBonus' ){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lastReel = $lastEvent->serverResponse->LastReel;
                $lines = 40;

                $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                if($stack['reel'] != ''){
                    $lastReel = explode(',', $stack['reel']);   
                }
                $str_initReel = $stack['initReel'];
                $str_rsb_s = $stack['rsb_s'];
                $str_mo = $stack['mo'];
                $str_mo_t = $stack['mo_t'];
                $rsb_rt = $stack['rsb_rt'];
                $rsb_c = $stack['rsb_c'];
                $rsb_m = $stack['rsb_m'];
                $rsb_mu = $stack['rsb_mu'];
                $bw = $stack['bw'];
                $wp = $stack['wp'];
                $e_aw = $stack['e_aw'];
                $bpw = str_replace(',', '', $stack['bpw']);
                $rw = str_replace(',', '', $stack['rw']);
                $fsmax = $stack['fsmax'];
                $fsmore = $stack['fsmore'];
                $spinType = $stack['na'];
                
                $totalWin = 0;
                $isState = false;
                if($rw > 0){
                    $totalWin = $rw / $original_bet * $betline;
                }
                if( $totalWin > 0) 
                {
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }

                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                $strOtherResponse = '';
                if($totalWin > 0){
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&rw=' . $totalWin;
                }
                if($spinType != 'b') 
                {
                    $isState = true;
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $isState = false;
                    }
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($str_rsb_s != ''){
                    $strOtherResponse = $strOtherResponse . '&rsb_s=' . $str_rsb_s;
                }
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo='. $str_mo .'&mo_t='. $str_mo_t;
                }
                if($rsb_c >= 0){
                    $strOtherResponse = $strOtherResponse . '&rsb_c='. $rsb_c .'&rsb_m='. $rsb_m;
                }
                if($rsb_rt >= 0){
                    $strOtherResponse = $strOtherResponse . '&rsb_rt=' . $rsb_rt;
                }
                if($rsb_mu >= 0){
                    $strOtherResponse = $strOtherResponse . '&rsb_mu=' . $rsb_mu;
                }
                if($wp >= 0){
                    $strOtherResponse = $strOtherResponse . '&wp=' . $wp;
                }
                if($bpw >= 0){
                    $strOtherResponse = $strOtherResponse . '&bpw=' . ($bpw / $original_bet * $betline);
                }
                if($rw >= 0){
                    $strOtherResponse = $strOtherResponse . '&rw=' . ($rw / $original_bet * $betline);
                }
                if($e_aw >= 0){
                    $strOtherResponse = $strOtherResponse . '&e_aw=' . $e_aw;
                }
                $response = 'balance='. $Balance . $strOtherResponse .'&index='.$slotEvent['index'].'&balance_cash='. $Balance .'&balance_bonus=0.00&na='. $spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sver=5&counter='. ((int)$slotEvent['counter'] + 1).'&s='.implode(',', $lastReel);

                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')  .',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
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

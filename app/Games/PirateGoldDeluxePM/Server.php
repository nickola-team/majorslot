<?php 
namespace VanguardLTE\Games\PirateGoldDeluxePM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', $lastEvent->serverResponse->Bgt);
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
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if(isset($stack)){
                    if($stack['reel_set'] >= 0){
                        $currentReelSet = $stack['reel_set'];
                    }
                    $str_initReel = $stack['initReel'];
                    $str_rsReel = $stack['rsReel'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $str_bmo = $stack['bmo'];
                    $str_bmo_t = $stack['bmo_t'];
                    $rsb_rt = $stack['rsb_rt'];
                    $rsb_c = $stack['rsb_c'];
                    $rsb_m = $stack['rsb_m'];
                    $rsb_mu = $stack['rsb_mu'];
                    $bw = $stack['bw'];
                    $wp = $stack['wp'];
                    $e_aw = $stack['e_aw'];
                    $end = $stack['end'];

                    if($slotSettings->GetGameData($slotSettings->slotId . 'Bgt') == 49 || $end == 1){                        
                        $strOtherResponse = $strOtherResponse . '&coef='. $bet .'&bgid=0&bgt=49';
                        if($end != 1 || $rsb_rt > 0){
                            $spinType = 'b';
                        }
                    }
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    if($bw == 1){
                        $strOtherResponse = $strOtherResponse . '&bw=' . $bw;
                    }
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($str_rsReel != ''){
                        $strOtherResponse = $strOtherResponse . '&rs_s=' . $str_rsReel;
                    }
                    if($str_mo != ''){
                        $strOtherResponse = $strOtherResponse . '&mo='. $str_mo .'&mo_t='. $str_mo_t;
                    }
                    if($str_bmo != ''){
                        $strOtherResponse = $strOtherResponse . '&bmo='. $str_bmo .'&bmo_t='. $str_bmo_t;
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
                        $strOtherResponse = $strOtherResponse . '&wp=' . $wp . '&bpw=' . ($wp * $bet);
                    }
                    if($end >= 0){
                        $strOtherResponse = $strOtherResponse . '&end=' . $end;
                        if($end == 1){
                            $strOtherResponse = $strOtherResponse . '&rw=' . ($wp * $bet);
                        }
                    }
                    if($e_aw >= 0){
                        $strOtherResponse = $strOtherResponse . '&e_aw=' . $e_aw;
                    }
                    
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                    }
                }                
                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=6,7,4,2,8,4,3,5,6,7,8,5,7,3,4,4,3,5,6,7&balance='. $Balance . $strOtherResponse .'&cfgs=3668&ver=2&mo_s=13&index=1&balance_cash='. $Balance . '&def_sb=3,4,7,6,8&mo_v=2,4,8,12,16,20,40,60,80,100,160,200,240,300,400,1000,4000,20000&reel_set_size=2&def_sa=8,7,5,3,7&reel_set='. $currentReelSet .'&mo_jp=1000;4000;20000&balance_bonus=0.00&na='. $spinType.'&scatters=1~0,0,0~0,0,0~1,1,1&gmb=0,0,0&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"23809500",max_rnd_win:"15000"}}&wl_i=tbm~15000&mo_jp_mask=jp3;jp2;jp1&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa=8,7,5,3,7&sb=3,4,7,6,8&sc='. implode(',', $slotSettings->Bet) .'&defc=100.00&purInit_e=1&sh=4&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;250,50,10,2,0;250,50,10,2,0;150,25,10,1,0;150,25,10,1,0;100,20,6,1,0;100,20,6,1,0;40,10,4,0,0;40,10,4,0,0;20,8,4,0,0;20,8,4,0,0;0,0,0,0,0;0,0,0,0,0&l=20&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=11,8,8,3,9,5,7,5,3,3,3,4,10,6,6,6,4,8,8,10,6,11,7,11,6,13,13,13,10,6,3,11,7,12,6,13,4,4,4~13,13,13,11,3,2,2,2,11,5,10,3,3,3,13,9,3,3,13,9,7,5,13,2,4,4,4,6,9,2,12,8~3,3,3,13,9,7,9,8,4,13,13,13,5,10,12,13,8,8,8,6,2,2,2,4,4,4,5,5,12,5,11,5,2,11,11,10,6,3,12,7,8,8~5,5,5,11,4,4,4,10,7,7,7,13,3,3,3,13,13,13,13,8,8,8,4,5,5,13,4,9,5,11,5,10,9,4,10,2,2,2,12,9,9,6,6,6,8,9,2,10~8,8,8,8,6,6,9,4,7,7,7,9,9,6,9,8,13,13,13,9,12,5,5,5,13,4,4,4,9,3,3,3,11,10,5,9,5,10,9,11,5,3,5,9,4,9,3,11,3,13,6,6,6,2,2,2&s='.$lastReelStr.'&reel_set1=3,7,3,3,3,5,7,5,5,3,3,5,5,3,7,3,7,7,7,5,5,5,3,9,11~8,8,8,10,12,4,6,6,6,6,4,6,4,4,4,6,6,6,4,6,6,4,4,4,4~8,9,10,11,12,4,6,5,7,7,3,3,3,5,4,4,4,5,4,5,7,3,7,4,4,5,5,3,7,4,3,3,7,3,7,7,5,3,7,6~8,8,8,9,10,11,12,6,6,4,4,4,4,7,5,6,4,6,6,6,5,5,5,4,4,4,7,7,7,3,3,3,7,4,6,4,4,3,4,7,4,7,3,4,5,6,7,6,4,5~8,8,8,4,4,4,5,4,3,3,3,6,6,5,7,6,6,3,4,5,5,5,6,3,7,7,6,6,6,5,6,4,5,4,3,6,5,7,7,7,7,6,6,6,4,9,10,11,12&purInit=[{type:"wbg",bet:2000,game_ids:[2]}]&total_bet_min=10.00';
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
                $linesId = [];
                $linesId[0] = [1,1,1,1,1];
                $linesId[1] = [4,4,4,4,4];
                $linesId[2] = [2,2,2,2,2];
                $linesId[3] = [3,3,3,3,3];
                $linesId[4] = [1,2,3,2,1];
                $linesId[5] = [4,3,2,3,4];
                $linesId[6] = [3,2,1,2,3];
                $linesId[7] = [2,3,4,3,2];
                $linesId[8] = [1,2,1,2,1];
                $linesId[9] = [4,3,4,3,4];
                $linesId[10] = [2,1,2,1,2];
                $linesId[11] = [3,4,3,4,3];
                $linesId[12] = [2,3,2,3,2];
                $linesId[13] = [3,2,3,2,3];
                $linesId[14] = [1,2,2,2,1];
                $linesId[15] = [4,3,3,3,4];
                $linesId[16] = [2,1,1,1,2];
                $linesId[17] = [3,4,4,4,3];
                $linesId[18] = [2,3,3,3,2];
                $linesId[19] = [3,2,2,2,3];
                $linesId[20] = [1,1,2,1,1];
                $linesId[21] = [4,4,3,4,4];
                $linesId[22] = [2,2,1,2,2];
                $linesId[23] = [3,3,4,3,3];
                $linesId[24] = [2,2,3,2,2];
                $linesId[25] = [3,3,2,3,3];
                $linesId[26] = [1,1,3,1,1];
                $linesId[27] = [4,4,1,4,4];
                $linesId[28] = [3,3,1,3,3];
                $linesId[29] = [2,2,4,2,2];
                $linesId[30] = [1,3,3,3,1];
                $linesId[31] = [4,2,2,2,4];
                $linesId[32] = [3,1,1,1,3];
                $linesId[33] = [2,4,4,4,2];
                $linesId[34] = [2,1,3,1,2];
                $linesId[35] = [3,4,2,4,3];
                $linesId[36] = [2,3,1,3,2];
                $linesId[37] = [3,2,4,2,3];
                $linesId[38] = [1,3,1,3,1];
                $linesId[39] = [4,2,4,2,4];
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

                // $winType = 'bonus';

                $allBet = $betline * $lines;
                if($pur >= 0){
                    $allBet = $betline * $lines * 100;
                }
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
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
                $strWinLine = '';
                $winLineCount = 0;
                $str_initReel = '';
                $str_rsReel = '';
                $str_mo = '';
                $str_mo_t = '';
                $str_bmo = '';
                $str_bmo_t = '';
                $rsb_rt = -1;
                $rsb_c = -1;
                $rsb_m = -1;
                $rsb_mu = -1;
                $bw = -1;
                $wp = -1;
                $e_aw = -1;
                $end = -1;
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $str_initReel = $stack['initReel'];
                    $str_rsReel = $stack['rsReel'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $str_bmo = $stack['bmo'];
                    $str_bmo_t = $stack['bmo_t'];
                    $rsb_rt = $stack['rsb_rt'];
                    $rsb_c = $stack['rsb_c'];
                    $rsb_m = $stack['rsb_m'];
                    $rsb_mu = $stack['rsb_mu'];
                    $bw = $stack['bw'];
                    $wp = $stack['wp'];
                    $e_aw = $stack['e_aw'];
                    $end = $stack['end'];
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
                    $str_rsReel = $stack[0]['rsReel'];
                    $str_mo = $stack[0]['mo'];
                    $str_mo_t = $stack[0]['mo_t'];
                    $str_bmo = $stack[0]['bmo'];
                    $str_bmo_t = $stack[0]['bmo_t'];
                    $rsb_rt = $stack[0]['rsb_rt'];
                    $rsb_c = $stack[0]['rsb_c'];
                    $rsb_m = $stack[0]['rsb_m'];
                    $rsb_mu = $stack[0]['rsb_mu'];
                    $bw = $stack[0]['bw'];
                    $wp = $stack[0]['wp'];
                    $e_aw = $stack[0]['e_aw'];
                    $end = $stack[0]['end'];
                }


                $reels = [];
                for($i = 0; $i < 5; $i++){
                    $reels[$i] = [];
                    for($j = 0; $j < 4; $j++){
                        $reels[$i][$j] = $lastReel[$j * 5 + $i];
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
                    for($j = 1; $j < 5; $j++){
                        $ele = $reels[$j][$linesId[$k][$j] - 1];
                        if($firstEle == $wild){
                            $firstEle = $ele;
                            $lineWinNum[$k] = $lineWinNum[$k] + 1;
                        }else if($ele == $firstEle || $ele == $wild){
                            $lineWinNum[$k] = $lineWinNum[$k] + 1;
                            if($j == 4){
                                $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
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
                    $reelA[$i] = mt_rand(7, 10);
                    $reelB[$i] = mt_rand(7, 10);
                }
                $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
               
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $strOtherResponse = '';
                $isState = true;
                  
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);

                if($bw == 1){
                    $spinType = 'b';
                    $isState = false;
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 49);     
                    $strOtherResponse = $strOtherResponse . '&coef='. $betline .'&bgid=0&bgt=49&bw=' . $bw;
                }
                if($pur >= 0){
                    $strOtherResponse = $strOtherResponse . '&purtr=1&puri=' . $pur;
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($str_rsReel != ''){
                    $strOtherResponse = $strOtherResponse . '&rs_s=' . $str_rsReel;
                }
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo='. $str_mo .'&mo_t='. $str_mo_t;
                }
                if($str_bmo != ''){
                    $strOtherResponse = $strOtherResponse . '&bmo='. $str_bmo .'&bmo_t='. $str_bmo_t;
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
                    $strOtherResponse = $strOtherResponse . '&wp=' . $wp . '&bpw=' . ($wp * $betline);
                }
                if($end >= 0){
                    $strOtherResponse = $strOtherResponse . '&end=' . $end;
                }
                if($e_aw >= 0){
                    $strOtherResponse = $strOtherResponse . '&e_aw=' . $e_aw;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse . $strWinLine .'&ls=0&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=4&c='.$betline.'&sw=5&sver=5&reel_set='.$currentReelSet.'&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&s='.$strLastReel.'&w='.$totalWin;
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin'). ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"Wins":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'Wins')) . ',"Status":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'Status')) .',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($slotEvent['slotEvent'] == 'freespin' && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * 100;
                }
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if( $bw == 1) 
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
                $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                               
                $str_initReel = $stack['initReel'];
                $str_rsReel = $stack['rsReel'];
                $str_mo = $stack['mo'];
                $str_mo_t = $stack['mo_t'];
                $str_bmo = $stack['bmo'];
                $str_bmo_t = $stack['bmo_t'];
                $rsb_rt = $stack['rsb_rt'];
                $rsb_c = $stack['rsb_c'];
                $rsb_m = $stack['rsb_m'];
                $rsb_mu = $stack['rsb_mu'];
                $bw = $stack['bw'];
                $wp = $stack['wp'];
                $e_aw = $stack['e_aw'];
                $end = $stack['end'];
                
                $totalWin = 0;
                $moneyWin = $wp * $betline;
                $isState = false;
                $spinType = 'b';
                if($end == 1){
                    $totalWin = $moneyWin;
                    $moneyWin = 0;
                }
                if( $totalWin > 0) 
                {
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                    if($rsb_rt == 0){
                        $isState = true;
                        $spinType = 'cb';
                        $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                    }
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                $strOtherResponse = '';
                if($totalWin > 0){
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&rw=' . $totalWin;
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($str_rsReel != ''){
                    $strOtherResponse = $strOtherResponse . '&rs_s=' . $str_rsReel;
                }
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo='. $str_mo .'&mo_t='. $str_mo_t;
                }
                if($str_bmo != ''){
                    $strOtherResponse = $strOtherResponse . '&bmo='. $str_bmo .'&bmo_t='. $str_bmo_t;
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
                    $strOtherResponse = $strOtherResponse . '&wp=' . $wp . '&bpw=' . $moneyWin;
                }
                if($end >= 0){
                    $strOtherResponse = $strOtherResponse . '&end=' . $end;
                }
                if($e_aw >= 0){
                    $strOtherResponse = $strOtherResponse . '&e_aw=' . $e_aw;
                }
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                }
                $response = 'bgid=0' . $strOtherResponse .'&balance='. $Balance .'&coef='. $betline .'&index='.$slotEvent['index'].'&balance_cash='. $Balance .'&balance_bonus=0.00&na='. $spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&bgt=49&sver=5&counter='. ((int)$slotEvent['counter'] + 1);

                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')  . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"Wins":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'Wins')) . ',"Status":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'Status')) .',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * 100;
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

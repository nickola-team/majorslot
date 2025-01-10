<?php 
namespace VanguardLTE\Games\TemujinTreasuresPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Level', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 38);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [3,4,11,9,10,9,6,5,6,7,9,10,8,10,8,6,7,8,3,7]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $strOtherResponse = '';
                $currentReelSet = 0;
                $stack = null;
                $strWinLine = '';
                $winMoney = 0;
                
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', $lastEvent->serverResponse->Bgt);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Level', $lastEvent->serverResponse->Level);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);
                    if (isset($lastEvent->serverResponse->ReplayGameLogs)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', json_decode(json_encode($lastEvent->serverResponse->ReplayGameLogs), true)); //ReplayLog
                    }
                    if (isset($lastEvent->serverResponse->TumbAndFreeStacks)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', json_decode(json_encode($lastEvent->serverResponse->TumbAndFreeStacks), true)); // FreeStack

                        $FreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                        if($slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') > 0){
                            $stack = $FreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') -1];
                        }
                    }
                    $bet = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $bet = '25.00';
                }
                $spinType = 's';
                $str_mo = '';
                $str_mo_t = '';
                $mo_tv = 0;
                $wmv = 0;
                $str_ds = '';
                $str_dsa = '';
                $str_dsam = '';
                $mo_c = 0;
                $bw = 0;
                $wp = 0;
                $level = -1;
                $str_wlc_v = '';
                $currentReelSet = 0;
                $fsmore = 0;
                $g = null;
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if(isset($stack)){
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $mo_tv = $stack['mo_tv'];
                    $wmv = $stack['wmv'];
                    $str_ds = $stack['ds'];
                    $str_dsa = $stack['dsa'];
                    $str_dsam = $stack['dsam'];
                    $mo_c = $stack['mo_c'];
                    $str_wlc_v = $stack['wlc_v'];
                    $fsmore = $stack['fsmore'];
                    $bw = $stack['bw'];
                    $wp = $stack['wp'];
                    $level = $stack['level'];
                    if($stack['g'] != ''){
                        $g = $stack['g'];
                    }
                    $currentReelSet = $stack['reel_set'];
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    if($str_mo != ''){
                        $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo;
                    }
                    if($str_mo_t != ''){
                        $strOtherResponse = $strOtherResponse . '&mo_t=' . $str_mo_t;
                    }
                    if($mo_tv > 0){
                        $strOtherResponse = $strOtherResponse . '&mo_tv=' . $mo_tv . '&mo_tw=' . ($mo_tv * $bet);
                    }
                    if($str_ds != ''){
                        $strOtherResponse = $strOtherResponse . '&ds=' . $str_ds . '&dsa=' . $str_dsa . '&dsam=' . $str_dsam;
                    }
                    if($mo_c > 0){
                        $strOtherResponse = $strOtherResponse . '&mo_c=' . $mo_c;
                    }
                    if($bw > 0){
                        $strOtherResponse = $strOtherResponse . '&bw=' . $bw;
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
                }
                if($slotSettings->GetGameData($slotSettings->slotId . 'Bgt') == 50){
                    $spinType = 'b';
                    $strOtherResponse = $strOtherResponse . '&bmw=0.00&bgid=0&level='. $slotSettings->GetGameData($slotSettings->slotId . 'Level') .'&coef='. $bet .'&index='.$slotEvent['index'].'&rw='. ($wp * $bet) .'&bgt=50&wp='.$wp.'&lifes=1&end=0';
                }
                else if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') == 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                    $strOtherResponse = $strOtherResponse . '&bmw=0.00&bgid=0&level='. $slotSettings->GetGameData($slotSettings->slotId . 'Level') .'&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1&lifes=0&end=1&wp='.$wp.'&rw='. ($wp * $bet);
                }
                else if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 )
                {
                    $fs = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                    $strOtherResponse = $strOtherResponse . '&fs=' . $fs . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&fsmul=1';
                    if($fsmore > 0){
                        $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                    }
                }
                
                $Balance = $slotSettings->GetBalance();    
                $response = 'def_s=3,4,11,9,10,9,6,5,6,7,9,10,8,10,8,6,7,8,3,7&balance='. $Balance .'&cfgs=4085&ver=2&mo_s=14&index=1&balance_cash='. $Balance .'&mo_v=100,200,250,500,1000,2000,5000,10944,3344,1064&def_sb=5,3,4,6,7&reel_set_size=2&def_sa=11,11,10,8,9&reel_set='. $currentReelSet .'&mo_jp=10944;3344;1064&balance_bonus=0.00&na='. $spinType .'&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"8665511",jwt_jp:"jp1,jp2,jp3,jp4",max_rnd_win:"9000",ma_jp:"337744,10944,3344,1064"}}&wl_i=tbm~9000&mo_jp_mask=jp2;jp3;jp4&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime='. floor(microtime(true) * 1000) .'&sa=11,11,10,8,9&sb=5,3,4,6,7&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=50.00&purInit_e=1&sh=4&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='. $bet .'&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;100,40,20,0,0;25,15,10,0,0;15,10,5,0,0;10,5,3,0,0;10,5,3,0,0;5,3,2,0,0;5,3,2,0,0;5,3,2,0,0;5,3,2,0,0;5,3,2,0,0;0,0,0,0,0;0,0,0,0,0&l=38&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=8,8,8,6,10,10,10,10,9,3,13,5,4,7,7,7,8,12,12,12,12,11,9,9,9,7,11,11,11,5,5,5,6,6,6,13,13,13,12,7,9,5,10,11,7,11,10,7,6,10,9,10,7,10,11,6~9,7,12,12,12,1,3,2,5,10,10,10,13,10,6,9,9,9,8,11,12,4,7,7,7,13,13,13,2,2,2,3,3,3,5,13,2,3,12,5,3,5,3,10,7,10,3,2,3~10,1,7,12,5,5,5,2,2,2,2,6,5,9,8,13,13,13,11,8,8,8,3,13,11,11,11,4,4,4,4,7,7,7,4,3,11,2,4,2,6,2,11,9,5,11,7,4,8,1,8,11,7,12,8,11,7,5,13,4,13,6~13,13,13,2,12,12,12,7,4,9,9,9,10,11,4,4,4,8,6,12,2,2,2,1,5,9,13,10,10,10,3,10,7,12,2,4,9,2,10,2,10,9,10,9,1,10,2,4,2,4,10,2,10,9,3,9,10,2,4,10,9,10,9,4,12,2,10,12,2,10,9,5,2,1,2,9,12,10,1,4,2,10,3,10,2,12,10,8,10,3~3,13,13,13,7,11,10,10,10,5,11,11,11,4,13,8,6,9,10,12,4,6,12,13,10,13,9,7,13,4&s='.$lastReelStr.'&t=243&reel_set1=7,10,10,10,11,9,5,4,10,6,11,11,11,12,13,3,3,3,3,13,13,13,8,7,7,7,5,5,5,3,10~12,2,2,2,3,4,13,5,5,5,5,11,6,4,4,4,10,13,13,13,7,8,8,8,14,9,2,8,12,12,12,4,7,5,4,10,6,4,9,14,4,2,9,11,13,4,8,9,7,9,7,11,9,4,9,2,4,8,2,9,4,7,4,7,9,6,7,4,11~4,11,7,7,7,14,2,12,9,9,9,3,2,2,2,8,6,5,10,9,7,3,3,3,13,11,11,11,13,13,13,12,12,12,2,7,3,7,10,14,2,6,3,7,9,11,9,11,2,3,11,6,2,13~8,5,7,2,2,2,11,10,10,10,9,12,11,11,11,4,13,13,13,10,3,13,6,14,2,13,10,11,2,11,5,11,5,13,2,13,10,5,2,10,4,9,10,9,13,2,7,6,3,5,7,5,11,5,3,4,5,10,2~12,6,6,6,10,7,13,6,9,9,9,8,5,3,9,7,7,7,4,12,12,12,11,8,8,8,11,11,11,13,13,13,10,10,10,13,8,13,11,13,9,13,11&purInit=[{type:"d",bet:3800}]&total_bet_min=5.00';
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
                $slotEvent['slotLines'] = 38;
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
                if($pur >= 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $betline * $lines * 100;
                }
                $tumbAndFreeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Level', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.1f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '56671' . substr($roundstr, 4, 9);
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
                $str_mo = '';
                $str_mo_t = '';
                $mo_tv = 0;
                $wmv = 0;
                $str_ds = '';
                $str_dsa = '';
                $str_dsam = '';
                $mo_c = 0;
                $bw = 0;
                $wp = 0;
                $level = -1;
                $str_wlc_v = '';
                $currentReelSet = 0;
                $fsmore = 0;
                $g = null;
                $subScatterReel = null;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $mo_tv = $stack['mo_tv'];
                    $wmv = $stack['wmv'];
                    $str_ds = $stack['ds'];
                    $str_dsa = $stack['dsa'];
                    $str_dsam = $stack['dsam'];
                    $mo_c = $stack['mo_c'];
                    $str_wlc_v = $stack['wlc_v'];
                    $fsmore = $stack['fsmore'];
                    $bw = $stack['bw'];
                    $wp = $stack['wp'];
                    $level = $stack['level'];
                    if($stack['g'] != ''){
                        $g = $stack['g'];
                    }
                    $currentReelSet = $stack['reel_set'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $lastReel = explode(',', $stack[0]['reel']);
                    $str_mo = $stack[0]['mo'];
                    $str_mo_t = $stack[0]['mo_t'];
                    $mo_tv = $stack[0]['mo_tv'];
                    $wmv = $stack[0]['wmv'];
                    $str_ds = $stack[0]['ds'];
                    $str_dsa = $stack[0]['dsa'];
                    $str_dsam = $stack[0]['dsam'];
                    $mo_c = $stack[0]['mo_c'];
                    $str_wlc_v = $stack[0]['wlc_v'];
                    $fsmore = $stack[0]['fsmore'];
                    $bw = $stack[0]['bw'];
                    $wp = $stack[0]['wp'];
                    $level = $stack[0]['level'];
                    if($stack[0]['g'] != ''){
                        $g = $stack[0]['g'];
                    }
                    $currentReelSet = $stack[0]['reel_set'];
                }

                $reels = [];
                $wildReel = [];
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;
                for($i = 0; $i < 20; $i++){
                    if($lastReel[$i] == $scatter){
                        $scatterCount++;
                        $scatterPoses[] = $i;
                    }
                }

                $_lineWinNumber = 1;
                $_obf_winCount = 0;
                $oldBetLine = 0.05;
                $wlc_vs = [];
                if($str_wlc_v != ''){
                    $old_wlc_vs = explode(';', $str_wlc_v);
                    foreach($old_wlc_vs as $index=>$wlc){
                        $arr_wlc = explode('~', $wlc);
                        if(isset($arr_wlc[1])){
                            $arr_wlc[1] = $arr_wlc[1] / $oldBetLine * $betline;
                            $totalWin = $totalWin + $arr_wlc[1];
                        }
                        $wlc_vs[] = implode('~', $arr_wlc);
                    }
                }
                $moneyWin = 0;
                if($mo_tv > 0){
                    $moneyWin = $mo_tv * $betline;
                    $totalWin = $totalWin + $moneyWin;
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
                // if($scatterCount >= 6 && $slotEvent['slotEvent'] != 'freespin'){
                //     $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 12);
                //     $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                //     $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                // }
                if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                }
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 5; $i++){
                    $reelA[$i] = mt_rand(4, 8);
                    $reelB[$i] = mt_rand(4, 8);
                }
                $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
                $strLastReel = implode(',', $lastReel);
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
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsend_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 'c';
                        $isEnd = true;
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
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    if($scatterCount >=3){
                        $isState = false;
                        $spinType = 'b';
                        $slotSettings->SetGameData($slotSettings->slotId . 'Level', $level);
                        $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 50);
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&purtr=1&puri=' . $pur;
                    }
                }
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo;
                }
                if($str_mo_t != ''){
                    $strOtherResponse = $strOtherResponse . '&mo_t=' . $str_mo_t;
                }
                if($mo_tv > 0){
                    $strOtherResponse = $strOtherResponse . '&mo_tv=' . $mo_tv . '&mo_tw=' . $moneyWin;
                }
                if($str_ds != ''){
                    $strOtherResponse = $strOtherResponse . '&ds=' . $str_ds . '&dsa=' . $str_dsa . '&dsam=' . $str_dsam;
                }
                if($mo_c > 0){
                    $strOtherResponse = $strOtherResponse . '&mo_c=' . $mo_c;
                }
                if($bw > 0){
                    $strOtherResponse = $strOtherResponse . '&bw=' . $bw;
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
                if(count($wlc_vs) > 0){
                    $strOtherResponse = $strOtherResponse . '&wlc_v=' . implode(';', $wlc_vs);
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&reel_set='. $currentReelSet .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=4&c='.$betline .'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=38&w='.$totalWin.'&s=' . $strLastReel;
                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if( $slotEvent['slotEvent'] != 'freespin' && $scatterCount >= 3) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"Level":' . $slotSettings->GetGameData($slotSettings->slotId . 'Level') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin')  . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($slotEvent['slotEvent'] == 'freespin' && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * 100;
                }
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
            }else if( $slotEvent['slotEvent'] == 'doBonus' ){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lines = 38;
                $lastReel = $lastEvent->serverResponse->LastReel; 
                $Balance =  $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                $bgt = $slotSettings->GetGameData($slotSettings->slotId . 'Bgt');
                $spinType = 'b';
                $isState = false;
                $strOtherResponse = '';
                $coef = $betline;
                $totalWin = 0;
                if($slotSettings->GetGameData($slotSettings->slotId . 'Level') == -1){
                    $slotSettings->SetGameData($slotSettings->slotId . 'Level', 0);
                    $strOtherResponse = $strOtherResponse . '&rw=0.00&lifes=1&wp=0&end=0';
                }else{
                    $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $fsmax = $stack['fsmax'];
                    $wp = $stack['wp'];
                    $level = $stack['level'];
                    $g = null;
                    if($stack['g'] != ''){
                        $g = $stack['g'];
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'Level', $level);
                    if($fsmax > 0){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $fsmax);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                        $spinType = 's';
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1&lifes=0&end=1';
                        $totalWin = $wp * $betline;
                        if( $totalWin > 0) 
                        {
                            $slotSettings->SetBalance($totalWin);
                            $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                            $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                            $strOtherResponse = $strOtherResponse . '&tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                        }
                    }else{
                        $strOtherResponse = $strOtherResponse . '&lifes=1&end=0';
                    }
                    $strOtherResponse = $strOtherResponse . '&wp=' . $wp . '&rw=' . ($wp * $betline);
                    if($g != null){
                        $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($g));
                    }
                }
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                }
                
               
                $response =  'bmw=0.00&bgid=0&level='. $slotSettings->GetGameData($slotSettings->slotId . 'Level') . $strOtherResponse .'&balance='. $Balance .'&coef='. $coef .'&index='.$slotEvent['index'].'&balance_cash='. $Balance .'&balance_bonus=0.00&na='. $spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&bgt=50&sver=5&counter='. ((int)$slotEvent['counter'] + 1);

                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"Level":' . $slotSettings->GetGameData($slotSettings->slotId . 'Level') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), 'WheelBonus', $isState);
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
        
        public function findZokbos($reels, $firstSymbol, $repeatCount, $positions, $reelndex){
            $wild = '2';
            $bPathEnded = true;
            if($reelndex < 6){
                for($r = 0; $r < 7; $r++){
                    if($firstSymbol == $reels[$reelndex][$r] || $reels[$reelndex][$r] == $wild){
                        $this->findZokbos($reels, $firstSymbol, $repeatCount + 1, array_merge($positions, [($reelndex + $r * 6)]), $reelndex + 1);
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

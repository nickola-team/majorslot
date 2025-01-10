<?php 
namespace VanguardLTE\Games\DragoJewelsofFortunePM
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
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [3,9,10,8,6,3,9,10,8,6,3,9,10,8,6,3,9,10,8,6,3,20,20,20,6]);
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
                    $bet = '100.00';
                }
                $spinType = 's';
                $fsmore = 0;
                $fsmul = 1;
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if(isset($stack)){
                    $str_initReel = $stack['is'];
                    $str_rsReel = $stack['rs_s'];
                    $str_rsb_w = $stack['rsb_w'];
                    $fsmul = $stack['fsmul'];
                    $str_rsb_w_mask = $stack['rsb_w_mask'];
                    $rsb_p = $stack['rsb_p'];
                    $rsb_m = $stack['rsb_m'];
                    $rsb_c = $stack['rsb_c'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $str_rwd = $stack['rwd'];
                    $bgt = $stack['bgt'];
                    $bw = $stack['bw'];
                    $end = $stack['end'];
                    $str_wlc_v = $stack['wlc_v'];
                    $currentReelSet = $stack['reel_set'];
                    $fsmore = $stack['fsmore'];
                    $str_trail = $stack['trail'];
                    $str_sty = $stack['sty'];
                    $str_srf = $stack['srf'];
                    $bgid = $stack['bgid'];
                    $str_pr_t = $stack['pr_t'];
                    $bpw = $stack['bpw'];
                    $rw = $stack['rw'];
                    $str_csc = $stack['csc'];
                    $rs_p = $stack['rs_p'];
                    $rs_m = $stack['rs_m'];
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                    }  
                    if($bw == 1){
                        $strOtherResponse = $strOtherResponse . '&bw=' . $bw;
                    }    
                    if($str_rsb_w != ''){
                        $strOtherResponse = $strOtherResponse . '&rsb_w=' . $str_rsb_w . '&rsb_w_mask=' . $str_rsb_w_mask;
                    }
                    if($bgid > -1){
                        $strOtherResponse = $strOtherResponse . '&bgid=' . $bgid;
                    }
                    if($rsb_p > -1){
                        $strOtherResponse = $strOtherResponse . '&rsb_p=' . $rsb_p . '&rsb_c=' . $rsb_c . '&rsb_m=' . $rsb_m;
                    }
                    if($str_mo != ''){
                        $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                    }
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($str_rsReel != ''){
                        $strOtherResponse = $strOtherResponse . '&rs_s=' . $str_rsReel;
                    }
                    if($str_rwd != ''){
                        $strOtherResponse = $strOtherResponse . '&rwd=' . $str_rwd;
                    }
                    if($bgt > 0){
                        $strOtherResponse = $strOtherResponse . '&bgt=' . $bgt;
                        if($bgt == 44){
                            $strOtherResponse = $strOtherResponse . '&coef=' . ($bet * 25);
                        }
                    }
                    if($end > -1){
                        $strOtherResponse = $strOtherResponse . '&end=' . $end;
                    }
                    if($str_rwd != ''){
                        $strOtherResponse = $strOtherResponse . '&rwd=' . $str_rwd;
                    }
                    if($rs_p > -1){
                        $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p . '&rs=0&rs_m=' . $rs_m . '&bpw=' . (str_replace(',', '', $bpw) / $bet * 20);
                    }
                    if($str_pr_t != ''){
                        $strOtherResponse = $strOtherResponse . '&pr_t=' . $str_pr_t;
                    }
                    if($str_csc != ''){
                        $strOtherResponse = $strOtherResponse . '&csc=' . $str_csc;
                    }
                    if($str_trail != ''){
                        $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                    }
                    if($str_sty != ''){
                        $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                    }
                    if($str_srf != ''){
                        $strOtherResponse = $strOtherResponse . '&srf=' . $str_srf;
                    }
                    if($bgt > 0){
                        $spinType = 'b';
                        if($end == 1){
                            $spinType = 's';
                        }
                    }
                }
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 )
                {
                    $fs = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                    $strOtherResponse = $strOtherResponse . '&fs=' . $fs . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&fsmul=' . $fsmul;
                    if($fsmore > 0){
                        $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                    }
                }
                
                $Balance = $slotSettings->GetBalance();    
                $response = 'def_s=3,9,10,8,6,3,9,10,8,6,3,9,10,8,6,3,9,10,8,6,3,20,20,20,6&balance='. $Balance .'&cfgs=3087&nas=20&ver=2&index=1&balance_cash='. $Balance .'&reel_set_size=3&def_sb=10,7,9,11,8&def_sa=11,4,9,7,5&reel_set=0&bonusInit=[{bgid:0,bgt:45,bg_i:"13,16,17,15,18",bg_i_mask:"r,wd,ms,fs,m"}]&balance_bonus=0.00&na='. $spinType .'&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&gameInfo={}&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime='. floor(microtime(true) * 1000) .'&sa=11,4,9,7,5&sb=10,7,9,11,8&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=50.00&purInit_e=1&sh=5&wilds=2~0,0,0,0,0~1,1,1,1,1;19~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='. $bet .'&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;200,50,25,0,0;100,40,20,0,0;50,25,15,0,0;40,20,10,0,0;20,10,8,0,0;15,8,6,0,0;10,8,6,0,0;10,6,4,0,0;10,6,4,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0&l=20&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=9,10,11,3,3,3,3,9,10,8,6,10,6,9,4,10,4,11,9,7,5,8,9,10,6,3,10~5,9,8,5,7,9,5,8,9,6,7,11,8,11,7,8,5,6,2,10,6,9,5,9,8,5,7,11,7,7,6,8,9,5,11,6,9,7,11,3,4,5,6,11,6,9,5,9,8,5,7,9,5,8,9,6,7,11,8,3,11,8,7,9,11,7,7,6,8,9,5,11,6,9,8,11,7,8,5,6,10,6,9,5,9,8,5,7,11,5,8,9,6,7,11,8,7,11,8,11,9,11,7,7,6,8,9,5,11,6,9,7,11,3,4,5,6,11,6,9~7,11,8,6,11,10,5,7,3,11,4,7,10,7,11,5,10,4,7,2,10,8,7,11,5,4,11,11,3,8,10,4,8,5,10,7,9,10,7,11,8,10,4,7,10,8~11,10,7,6,10,11,6,9,3,3,3,3,5,6,11,2,10,7,10,6,9,2,9,7,10,9,7,4,9,7,6,7,4,11,6,10,9,8,7,3,4,5,6,11,10~6,10,11,3,3,3,3,9,10,7,11,10,3,9,8,5,10,9,7,8,9,6,10,5,8,4,7,11,4,8,10,5,7,6,9,7,5,11,6,7,4,8,11,7,5,9,11,10&s='.$lastReelStr.'&reel_set2=10,11,3,3,3,3,10,6,11,4,10,11,6,4,10,4,11,5,11,10,6,11,10~5,9,8,5,7,11,5,8,11,9,11,7,7,6,9,5,11,6,9,11,5,6,6,9~7,8,10,7,8,4,7,4,8,3,10,9,7,10,7,8,10,4,7,10,8~11,10,7,6,10,11,5,4,9,7,6,7,4,11,6,10,2,8,7,3,4,5,6,11,2,10,7,10,6,9,10,9,7,4,6~6,10,9,3,3,3,3,6,10,9,9,8,5,10,9,7,8,9,6,10,5,8,4,7,10,4,8,10,5,7,6,9,7,5,10,6,7,4,8,11,7,5,9,11,10&t=243&reel_set1=9,10,11,3,3,3,3,9,10,7,6,10,10,6,11,8,6,9,4,10,4,11,9,7,6,9,11,10,6,11,5~5,9,8,5,7,11,5,8,9,6,7,11,11,6,9,11,11,2,7,4,5,6,10,8,7,11,8,11,9,11,5,7,6,10,9,5,11,6,9,7,11,3,9,5,6,8,6,9,8,7,9,6,11~7,11,8,6,11,10,5,7,8,11,4,7,10,7,8,10,9,7,11,8,10,2,8,11,4,7,11,3,4,5,6,10,11,7,11,10,4,8,5,10,7,8,10,7,11,8,10,11,7,4,8,5,10~11,10,7,6,10,11,5,8,7,11,9,10,7,3,4,5,6,11,9,10,7,5,9,7,2,11,9,10,9,7,4,9,8,6,7,4,11,6,10,11,8,7,3,4,5,6,11,9,10,7,10,8,9~6,10,9,3,3,3,3,9,10,7,8,10,7,8,10,10,9,8,5,10,9,7,8,9,6,10,5,8,4,7,10,4,8,10,5,7,6,9,7,5,10,6,7,4,8,9,7,5,9,11,10&purInit=[{type:"wbg",bet:2000,game_ids:[0]}]&total_bet_min=10.00';
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

                // $winType = 'bonus';

                $allBet = $betline * $lines;
                if($pur >= 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $betline * $lines * 100;
                }
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
                $empty = '20';
                $Balance = $slotSettings->GetBalance();
                $totalWin = 0;
                $bonusMpl = 1;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $str_initReel = '';
                $str_rsReel = '';
                $str_rsb_w = '';
                $str_rsb_w_mask = '';
                $rsb_p = -1;
                $rsb_m = 0;
                $rsb_c = -1;
                $str_mo = '';
                $str_mo_t = '';
                $str_rwd = '';
                $bgt = 0;
                $bw = 0;
                $end = -1;
                $str_wlc_v = '';                
                $currentReelSet = 0;
                $fsmore = 0;
                $str_trail = '';
                $str_sty = '';
                $str_srf = '';
                $bgid = -1;
                $str_pr_t = '';
                $bpw = 0;
                $rw = 0;
                $str_csc = '';
                $rs_p = -1;
                $rs_m = 0;
                $fsmul = 1;
                $subScatterReel = null;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_initReel = $stack['is'];
                    $str_rsReel = $stack['rs_s'];
                    $str_rsb_w = $stack['rsb_w'];
                    $fsmul = $stack['fsmul'];
                    $str_rsb_w_mask = $stack['rsb_w_mask'];
                    $rsb_p = $stack['rsb_p'];
                    $rsb_m = $stack['rsb_m'];
                    $rsb_c = $stack['rsb_c'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $str_rwd = $stack['rwd'];
                    $bgt = $stack['bgt'];
                    $bw = $stack['bw'];
                    $end = $stack['end'];
                    $str_wlc_v = $stack['wlc_v'];
                    $currentReelSet = $stack['reel_set'];
                    $fsmore = $stack['fsmore'];
                    $str_trail = $stack['trail'];
                    $str_sty = $stack['sty'];
                    $str_srf = $stack['srf'];
                    $bgid = $stack['bgid'];
                    $str_pr_t = $stack['pr_t'];
                    $bpw = $stack['bpw'];
                    $rw = $stack['rw'];
                    $str_csc = $stack['csc'];
                    $rs_p = $stack['rs_p'];
                    $rs_m = $stack['rs_m'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines, $pur);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $lastReel = explode(',', $stack[0]['reel']);
                    $str_initReel = $stack[0]['is'];
                    $str_rsReel = $stack[0]['rs_s'];
                    $str_rsb_w = $stack[0]['rsb_w'];
                    $str_rsb_w_mask = $stack[0]['rsb_w_mask'];
                    $rsb_p = $stack[0]['rsb_p'];
                    $rsb_m = $stack[0]['rsb_m'];
                    $rsb_c = $stack[0]['rsb_c'];
                    $str_mo = $stack[0]['mo'];
                    $str_mo_t = $stack[0]['mo_t'];
                    $str_rwd = $stack[0]['rwd'];
                    $bgt = $stack[0]['bgt'];
                    $bw = $stack[0]['bw'];
                    $end = $stack[0]['end'];
                    $str_wlc_v = $stack[0]['wlc_v'];
                    $currentReelSet = $stack[0]['reel_set'];
                    $fsmore = $stack[0]['fsmore'];
                    $str_trail = $stack[0]['trail'];
                    $str_sty = $stack[0]['sty'];
                    $str_srf = $stack[0]['srf'];
                    $bgid = $stack[0]['bgid'];
                    $str_pr_t = $stack[0]['pr_t'];
                    $bpw = $stack[0]['bpw'];
                    $rw = $stack[0]['rw'];
                    $str_csc = $stack[0]['csc'];
                    $rs_p = $stack[0]['rs_p'];
                    $rs_m = $stack[0]['rs_m'];
                }

                $_lineWinNumber = 1;
                $_obf_winCount = 0;
                $wlc_vs = [];
                if($str_wlc_v != ''){
                    $old_wlc_vs = explode(';', $str_wlc_v);
                    foreach($old_wlc_vs as $index=>$wlc){
                        $arr_wlc = explode('~', $wlc);
                        if(isset($arr_wlc[1])){
                            $arr_wlc[1] = str_replace(',', '', $arr_wlc[1]) / $original_bet * $betline;
                            $totalWin = $totalWin + ($arr_wlc[1] * $fsmul);
                        }
                        $wlc_vs[] = implode('~', $arr_wlc);
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
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') / $fsmul) . '&fsmul_total='. $fsmul .'&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin'). '&fsend_total=1';
                        $spinType = 'c';
                        $isEnd = true;
                    }
                    else
                    {
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul='. $fsmul .'&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
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
                }
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                }                
                if($pur >= 0){
                    $strOtherResponse = $strOtherResponse . '&puri=' . $pur;
                }
                if($bw == 1){
                    $spinType = 'b';
                    $isState = false;
                    $strOtherResponse = $strOtherResponse . '&bw=' . $bw;
                }

                if($str_rsb_w != ''){
                    $strOtherResponse = $strOtherResponse . '&rsb_w=' . $str_rsb_w . '&rsb_w_mask=' . $str_rsb_w_mask;
                }
                if($bgid > -1){
                    $strOtherResponse = $strOtherResponse . '&bgid=' . $bgid;
                }
                if($rsb_p > -1){
                    $strOtherResponse = $strOtherResponse . '&rsb_p=' . $rsb_p . '&rsb_c=' . $rsb_c . '&rsb_m=' . $rsb_m;
                }
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($str_rsReel != ''){
                    $strOtherResponse = $strOtherResponse . '&rs_s=' . $str_rsReel;
                }
                if($str_rwd != ''){
                    $strOtherResponse = $strOtherResponse . '&rwd=' . $str_rwd;
                }
                if($bgt > 0){
                    $strOtherResponse = $strOtherResponse . '&bgt=' . $bgt;
                    if($bgt == 44){
                        $strOtherResponse = $strOtherResponse . '&coef=' . ($betline * $lines);
                    }
                }
                if($end > -1){
                    $strOtherResponse = $strOtherResponse . '&end=' . $end;
                }
                if($rs_p > -1){
                    $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p . '&rs=0&rs_m=' . $rs_m . '&bpw=' . (str_replace(',', '', $bpw) / $original_bet * $betline);
                }
                if($str_pr_t != ''){
                    $strOtherResponse = $strOtherResponse . '&pr_t=' . $str_pr_t;
                }
                if($str_csc != ''){
                    $strOtherResponse = $strOtherResponse . '&csc=' . $str_csc;
                }
                if($str_trail != ''){
                    $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                }
                if($str_sty != ''){
                    $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                }
                if($str_srf != ''){
                    $strOtherResponse = $strOtherResponse . '&srf=' . $str_srf;
                }
                if(count($wlc_vs) > 0){
                    $strOtherResponse = $strOtherResponse . '&wlc_v=' . implode(';', $wlc_vs);
                }
                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                    $strOtherResponse = $strOtherResponse . '&w='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }else{
                    $strOtherResponse = $strOtherResponse . '&w='.($totalWin / $fsmul);
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&reel_set='. $currentReelSet .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=5&st=rect&c='.$betline .'&sw=5&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&s=' . $strLastReel;

                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if( $slotEvent['slotEvent'] != 'freespin' && $bw == 1) 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($slotEvent['slotEvent'] == 'freespin' && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * 100;
                }
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
            }else if( $slotEvent['slotEvent'] == 'doBonus' ){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lines = 20;
                $lastReel = $lastEvent->serverResponse->LastReel; 
                $Balance =  $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);

                $str_initReel = $stack['is'];
                $str_rsReel = $stack['rs_s'];
                $str_rsb_w = $stack['rsb_w'];
                $str_rsb_w_mask = $stack['rsb_w_mask'];
                $rsb_p = $stack['rsb_p'];
                $rsb_m = $stack['rsb_m'];
                $rsb_c = $stack['rsb_c'];
                $str_mo = $stack['mo'];
                $str_mo_t = $stack['mo_t'];
                $str_rwd = $stack['rwd'];
                $bgt = $stack['bgt'];
                $bw = $stack['bw'];
                $end = $stack['end'];
                $str_wlc_v = $stack['wlc_v'];
                $currentReelSet = $stack['reel_set'];
                $fsmore = $stack['fsmore'];
                $str_trail = $stack['trail'];
                $str_sty = $stack['sty'];
                $str_srf = $stack['srf'];
                $bgid = $stack['bgid'];
                $str_pr_t = $stack['pr_t'];
                $bpw = $stack['bpw'];
                if($bpw > 0){
                    $bpw = $bpw / $original_bet * $betline;
                }
                $rw = $stack['rw'];
                if($rw > 0){
                    $rw = $rw / $original_bet * $betline;
                }
                $str_csc = $stack['csc'];
                $rs_p = $stack['rs_p'];
                $rs_m = $stack['rs_m'];


                $isState = false;
                $strOtherResponse = '';
                $spinType = 'b';
                $coef = $betline * $lines;
                $totalWin = 0;
                if($end == 1){
                    $isEnd= true;
                    if($bgt == 45){
                        $arr_rsb_ws = explode(',', $str_rsb_w);
                        $arr_rsb_w_masks = explode(',', $str_rsb_w_mask);
                        for($k = 0; $k < count($arr_rsb_ws); $k++){
                            if($arr_rsb_w_masks[$k] == 'fs'){
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $arr_rsb_ws[$k]);
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                                break;
                            }
                        }
                        $spinType = 's';
                    }else if($bgt == 44){
                        $spinType = 'cb';
                        $totalWin = $rw;
                        if($totalWin > 0){
                            $slotSettings->SetBalance($totalWin);
                            $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                        }
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    }
                }
                
                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                {
                    $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1';
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                    }
                }
                if($stack['reel'] != ''){
                    $lastReel = explode(',', $stack['reel']);
                    $strOtherResponse = $strOtherResponse . '&s=' . $stack['reel'];
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                }
                if($totalWin > 0){
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }
                if($str_rsb_w != ''){
                    $strOtherResponse = $strOtherResponse . '&rsb_w=' . $str_rsb_w . '&rsb_w_mask=' . $str_rsb_w_mask;
                }
                if($rsb_p > -1){
                    $strOtherResponse = $strOtherResponse . '&rsb_p=' . $rsb_p . '&rsb_c=' . $rsb_c . '&rsb_m=' . $rsb_m;
                }
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($str_rsReel != ''){
                    $strOtherResponse = $strOtherResponse . '&rs_s=' . $str_rsReel;
                }
                if($str_rwd != ''){
                    $strOtherResponse = $strOtherResponse . '&rwd=' . $str_rwd;
                }
                if($bgt > 0){
                    $strOtherResponse = $strOtherResponse . '&bgt=' . $bgt;
                    if($bgt == 44){
                        $strOtherResponse = $strOtherResponse . '&coef=' . $coef;
                    }
                }
                if($end > -1){
                    $strOtherResponse = $strOtherResponse . '&end=' . $end;
                }
                if($str_rwd != ''){
                    $strOtherResponse = $strOtherResponse . '&rwd=' . $str_rwd;
                }
                if($rs_p > -1){
                    $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p . '&rs=0&rs_m=' . $rs_m . '&bpw=' . $bpw;
                }
                if($str_pr_t != ''){
                    $strOtherResponse = $strOtherResponse . '&pr_t=' . $str_pr_t;
                }
                if($str_csc != ''){
                    $strOtherResponse = $strOtherResponse . '&csc=' . $str_csc;
                }
                if($str_trail != ''){
                    $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                }
                if($str_sty != ''){
                    $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                }
                if($str_srf != ''){
                    $strOtherResponse = $strOtherResponse . '&srf=' . $str_srf;
                }
               
                $response = 'bgid='. $bgid . $strOtherResponse .'&balance='. $Balance .'&index='.$slotEvent['index'].'&balance_cash='. $Balance .'&balance_bonus=0.00&na='. $spinType  . '&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sver=5&counter='. ((int)$slotEvent['counter'] + 1);

                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin')  . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * 100;
                }
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

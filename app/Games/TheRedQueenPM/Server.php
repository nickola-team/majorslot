<?php 
namespace VanguardLTE\Games\TheRedQueenPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', []);
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
                    // $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $lastEvent->serverResponse->CurrentRespin);
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
                $fsmul = 1;
                // $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if(isset($stack)){
                    $str_trail = $stack['trail'];                    
                    $str_stf = $stack['stf'];
                    $str_slm_mp = $stack['slm_mp'];
                    $str_slm_mv = $stack['slm_mv'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $rs_more = $stack['rs_more'];
                    $g = null;
                    if($stack['g'] != ''){
                        $g = $stack['g'];
                    }
                    $strWinLine = $stack['wlc_v'];
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    
                    if($rs_p >= 0){
                        $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p . '&rs_c=' . $rs_c . '&rs_m=' . $rs_m;
                    }else if($rs_t > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t;
                    }
                    if($rs_more > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_more=' . $rs_more;
                    }
                    if($str_trail != ''){
                        $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                    }                
                    if($str_stf != ''){
                        $strOtherResponse = $strOtherResponse . '&stf='. $str_stf;
                    }
                    if($str_slm_mp != ''){
                        $strOtherResponse = $strOtherResponse . '&slm_mp=' . $str_slm_mp . '&slm_mv=' . $str_slm_mv;
                    }
                    if($g != null){
                        $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($g));
                    }else{
                        $strOtherResponse = $strOtherResponse . '&g={base:{def_s:"7,10,11,9,4,14,3,12,7,8,3,14,7,11,12,4,6,14",def_sa:"5,11,4,3,3,14",def_sb:"5,12,5,12,4,14",reel_set:"0",s:"7,10,11,9,4,14,3,12,7,8,3,14,7,11,12,4,6,14",sa:"5,11,4,3,3,14",sb:"5,12,5,12,4,14",sh:"3",st:"rect",sw:"6"},top_tracker:{def_s:"14,15,14,15",def_sa:"14",def_sb:"15",reel_set:"6",s:"14,15,14,15",sa:"14",sb:"15",sh:"4",st:"rect",sw:"1"}}';
                    }
                    if($strWinLine != ''){
                        $arr_lines = explode(';', $strWinLine);
                        for($k = 0; $k < count($arr_lines); $k++){
                            $arr_sub_lines = explode('~', $arr_lines[$k]);
                            $arr_sub_lines[1] = str_replace(',', '', $arr_sub_lines[1]) / $original_bet * $bet;
                            $arr_lines[$k] = implode('~', $arr_sub_lines);
                        }
                        $strWinLine = implode(';', $arr_lines);
                        $strOtherResponse = $strOtherResponse . '&wlc_v=' . $strWinLine;
                    }
                }else{
                    $strOtherResponse = $strOtherResponse . '&g={base:{def_s:"7,10,11,9,4,14,3,12,7,8,3,14,7,11,12,4,6,14",def_sa:"5,11,4,3,3,14",def_sb:"5,12,5,12,4,14",reel_set:"0",s:"7,10,11,9,4,14,3,12,7,8,3,14,7,11,12,4,6,14",sa:"5,11,4,3,3,14",sb:"5,12,5,12,4,14",sh:"3",st:"rect",sw:"6"},top_tracker:{def_s:"14,15,14,15",def_sa:"14",def_sb:"15",reel_set:"6",s:"14,15,14,15",sa:"14",sb:"15",sh:"4",st:"rect",sw:"1"}}';
                }                
                $Balance = $slotSettings->GetBalance();  
                $response = 'balance='. $Balance .'&cfgs=7896&ver=3&index=1&balance_cash='. $Balance .'&reel_set_size=12&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0,0~0,0,0,0,0,0~1,1,1,1,1,1&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"32763809",max_rnd_win:"5000"}}&wl_i=tbm~5000&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime='. floor(microtime(true) * 1000) .'&reel_set10=12,8,7,10,7,6,11,8,6,11,4,3,5,4,7,10,3,4,9,12,6,12,10,9,6,5,9~3,5,2,10,12,4,6,9,7,4,9,8,9,5,8,10,3,4,11,6,5,4,12,1,10,6,12,11,3,8,10,11~11,1,6,7,9~11,10,6,1~9,12,10,4,10,6,3,12,6,6,3,4,12,9,10,5,10,11,10,8,7,5,11,5,11,4,9,8,8,7,11,7~14,14,14,14,14,14&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=50.00&reel_set11=10,11,6,12,10,8,6,3,5,3,6,7,9,7,9,8,12,4,10,8,7,4,11,5~1,3,11,9~11,6,7,1,9~6,11,1,10~9,8,11,12,4,11,10,11,5,3,8,4,3,10,6,4,12,6,8,7,7,10,12,5,7,5,9,10,9,6~14,14,14,14,14,14&wilds=2~0,0,0,0,0,0~1,1,1,1,1,1&bonuses=0&c='. $bet .'&sver=5&counter=2&paytable=0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;100,50,25,15,0,0;75,40,15,10,0,0;60,30,10,5,0,0;40,25,10,5,0,0;30,20,8,5,0,0;25,10,6,4,0,0;25,10,6,4,0,0;20,8,4,2,0,0;20,8,4,2,0,0;10,6,3,2,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0&l=20&reel_set0=7,13,6,10,8,10,12,13,3,3,4,4,6,7,7,5,11,5,11,4,7,9,11,13,6,5,4,8,12,13,6,7,6,13,8,12,13,13,12,9,13,10,13,9,3,8,10,13~8,13,12,5,13,2,3,13,13,4,10,13,5,13,9,4,12,6,7,9,5,9,11,1,3,6,11,4,11,10,8,10~6,1,6,5,7,13,10,11,8,6,5,11,4,12,10,7,4,2,13,13,3,13,10,13,13,9,5,11,6,8,9,13,3,4,13,5,13,11,10,7,13,10,9,10,12,13~9,6,7,8,4,4,3,11,13,11,12,11,8,1,5,5,10,9,10,13,7,2,5,9,3,10,11,12,10,5,13,4,6,12,4,13~11,5,6,3,11,9,8,8,12,11,13,6,4,10,13,4,7,2,7,12,10,9,11,5,10,12,3~14,14,14,14,14,14&reel_set2=10,8,10,8,12,7,12,11,13,11,13,3,13,6,13,13,5,13,9,4,12,4,13,4,8,3,9,10,6,11,9,9,8,12,7,3,5,11,10,6,13~4,1,8,5,10,10,13,3,13,4,11,10,2,13,7,3,8,6,1,12,8,2,4,6,9,6,10,12,13,9,7,11,13,13,11,5,7,12,9~5,11,13,7,8,13,6,6,1,11,2,4,3,13,9,9,8,13,10,10,1,8,10,9,6,12,13,12,4,11,13,7,12,4,5,11,13~8,5,12,10,9,9,10,12,11,4,13,5,6,10,10,4,9,10,11,4,11,11,12,11,5,7,4,8,6,9,7,12,4,6,8,12,3,8,13,12,2,13,6,8,12,13,7,10,10,11,13,3,4~3,2,11,11,12,10,7,6,8,7,5,9,10,10,9,3,12,11,12,4,11,3,12,6,10,4,8,4,10,9,5,12,8,5~14,14,14,14,14,14&reel_set1=4,7,6,10,6,10,7,3,8,4,7,13,13,5,3,13,12,5,5,8,12,8,13,12,4,11,13,3,7,9,11,4,13,6,9,13,8,12,10,9,7,5,12,8,5,6,11,9,10,13,11,13,13~11,2,4,12,10,13,3,11,11,6,6,9,10,9,8,5,7,2,10,13,5,3,9,4,13,7,13,11,12,13,8,4,7,10~1,6,8,13,11,13,6,12,10,12,5,13,11,6,4,7,9,4,8,9,9,12,12,13,3,11,5,9,7,13,1,11,8,10,8,13,2,6,10,13,7,10,13,12,13,5,9,4,3,3,10,11,6,4,10~6,11,13,9,1,6,4,12,3,3,13,11,10,10,12,9,12,5,3,6,7,8,5,8,4,10,10,11,12,11,1,10,12,8,5,7,8,11,4,4,9,9,2,12,7~12,4,9,5,10,8,10,4,12,5,3,4,4,11,10,11,6,5,7,8,11,10,7,4,6,9,8,12,3,10,7,8,5,10,12,9,9,11,12,2,6,11,13,3,12,8,11,10,12~14,14,14,14,14,14&reel_set4=5,4,9,13,4,12,4,8,7,12,13,13,11,13,11,10,11,13,7,13,8,12,6,13,9,3,10,7,5,13,9,13,8,13,13,6,10,11,13,13,10,5,11,10,8~10,7,2,13,6,10,13,9,5,13,12,13,11,12,11,13,13,3,9,11,8,10,4,12,10,6,8,13,6,4,10,12,13,11,9,11,10,13,10,13,4,7,9,6,5,2,7,13,5,8,13,11,3,13,9,7,12,8,12,8,13,13,8,4,12,9,11,13,13,5,13~13,11,9,6,12,13,12,8,5,10,11,5,13,11,7,12,10,9,13,13,13,3,9,12,13,10,13,6,4,8,2,13,10,11,8,7,4,13,12,10~8,10,9,3,10,12,3,10,7,11,12,11,12,8,12,13,4,10,7,9,6,8,10,12,13,13,10,4,9,12,13,6,5,12,11,8,7,11,5,4,11,6,2,11,9,11,5~10,8,11,4,11,13,6,9,8,10,7,12,9,7,6,7,12,11,5,4,12,9,8,11,12,10,9,3,2,5,11,8,13,12,11,10,9,10,12,6,3,10,5,10,13,12,8,12~11,9,6,9,10,9,10,12,8,11,4,11,5,11,10,6,5,12,8,13,4,8,10,7,3,12,13,9,7&reel_set3=13,9,7,11,11,3,8,6,12,5,9,4,9,12,12,7,8,4,12,9,4,7,13,12,8,12,11,13,8,13,13,6,5,12,8,3,11,10,5,5,10,9,11,10,11,13,10,13,3,10,4,6,9~13,12,4,9,11,12,5,11,4,10,12,3,6,4,11,11,3,8,10,7,7,9,10,13,6,6,5,10,13,12,10,2,9,11,13,8,12,5,7,8~9,12,4,10,2,11,5,4,12,9,4,6,3,12,11,7,8,5,3,13,5,8,9,6,9,11,10,9,10,7,8,11,12,6,7,13,10,10,13,12,11~11,12,9,8,12,12,5,12,11,10,8,11,4,2,10,9,5,4,9,3,10,12,12,13,7,3,11,10,6,6,7,10~8,10,11,9,10,10,5,6,11,12,12,5,9,7,3,12,11,13,7,4,8,4,6,10,12,2~4,11,8,7,9,3,6,11,8,12,9,12,10,11,10,6,4,12,8,13,11,12,10,5,11,3,10,12,9,7,5&reel_set6=14,14,15,14,2,14,14,1,14,15,14,2,14,2,14,14,15&reel_set5=13,10,9,7,3,10,9,9,6,7,11,9,9,10,12,12,8,10,4,12,5,12,3,12,9,7,10,8,6,6,11,11,13,11,6,11,8,4,5,10,8,11,12,5,8~8,7,1,4,9~6,5,10,1,11~8,1,3,4,12~8,3,12,9,13,10,5,12,12,5,7,7,10,9,13,6,11,10,11,4,12,11,10,6,12,4,9,10,11,3,8~14,14,14,14,14,14&reel_set8=11,7~11,3,1,9~11,1,6,7,9~10,1,12,7,10,8,5,5,9,6,9,4,3,12,4,4,6,9,2,7,11,11,5,11,10,11~12,5,10,8,11,10,8,11,7,6,3,12,9,4,5,3,7,9,6,4,12,11~14,14,14,14,14,14&reel_set7=14,2,2,15,14,14,14,2,14,14,14,14,15,2,2,2,14,14,15,2,14,14,14,14,14,14,14,14,14,14,2,14,14,14,14,14,14,14,14,2,14,2,14,2,2,1,14,14,2,14,14,14,2,2,2,1,2,14,14,14,2,14,2,14,14,2,2,1,14,14,14,14,14,2,2,14,14,15,15,15,15,2,2,2,14,14,15,14,1,2,1,14,14,14,14,14,2,14,14,14,14,14,14,14,14&reel_set9=9,10,6,8,3,11,5,10,6,7,7,12,9,8,3,12,9,4,5,7,6,6,8,4,4,11,12~11,3,1,9~5,10,7,8,10,4,11,10,10,1,6,11,8,11,3,3,12,9,6,6,11,10,7,7,6,5,12,2,5,9,10,4,4,9,5,9~11,10,6,1~9,4,11,8,6,8,10,7,10,5,12,11,3,9,5,7,10,4,12,6~14,14,14,14,14,14';
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
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
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
                    if( $slotEvent['slotEvent'] == 'doSpin' && $slotSettings->GetBalance() < ($lines * $betline)  && $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') < 0) 
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
                $tumbAndFreeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'freespin' || $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') >= 0){
                    if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') < 0){
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    }                    
                    // $leftFreeGames = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') - $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame'); 
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
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
                $g = null;
                $fsmore = 0;
                $str_trail = '';
                $str_stf = '';
                $str_slm_mp = '';
                $str_slm_mv = '';
                $rs_p = -1;
                $rs_c = 0;
                $rs_m = 0;
                $rs_t = 0;
                $rs_more = 0;
                $fsmore = 0;
                $lastReel = [];
                $fsmax = 0;
                $scatterCount = 0;
                if($slotEvent['slotEvent'] == 'freespin' || $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') >= 0){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    // $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $str_trail = $stack['trail'];
                    $str_stf = $stack['stf'];
                    $str_slm_mp = $stack['slm_mp'];
                    $str_slm_mv = $stack['slm_mv'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $rs_more = $stack['rs_more'];
                    if($stack['g'] != ''){
                        $g = $stack['g'];
                    }
                    $strWinLine = $stack['wlc_v'];
                    $fsmax = $stack['fsmax'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    // $lastReel = explode(',', $stack[0]['reel']);
                    $currentReelSet = $stack[0]['reel_set'];
                    $str_trail = $stack[0]['trail'];
                    $str_stf = $stack[0]['stf'];
                    $str_slm_mp = $stack[0]['slm_mp'];
                    $str_slm_mv = $stack[0]['slm_mv'];
                    $rs_p = $stack[0]['rs_p'];
                    $rs_c = $stack[0]['rs_c'];
                    $rs_m = $stack[0]['rs_m'];
                    $rs_t = $stack[0]['rs_t'];
                    $rs_more = $stack[0]['rs_more'];
                    if($stack[0]['g'] != ''){
                        $g = $stack[0]['g'];
                    }
                    $strWinLine = $stack[0]['wlc_v'];
                    $fsmax = $stack[0]['fsmax'];
                }
                if($strWinLine != ''){
                    $arr_lines = explode(';', $strWinLine);
                    for($k = 0; $k < count($arr_lines); $k++){
                        $arr_sub_lines = explode('~', $arr_lines[$k]);
                        $arr_sub_lines[1] = str_replace(',', '', $arr_sub_lines[1]) / $original_bet * $betline;
                        $totalWin = $totalWin + $arr_sub_lines[1];
                        $arr_lines[$k] = implode('~', $arr_sub_lines);
                    }
                    $strWinLine = implode(';', $arr_lines);
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
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $rs_p);
                if($fsmax > 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $fsmax);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                }else if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                }
                
                // $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                $strOtherResponse = '';
                if($rs_p >= 0){
                    $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p . '&rs_c=' . $rs_c . '&rs_m=' . $rs_m;
                    $isState = false;
                    $spinType = 's';
                }else if($rs_t > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t;
                }
                if($rs_more > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_more=' . $rs_more;
                }
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
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
                    }
                    if($fsmore > 0){
                        $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                    }
                }else
                {
                    if($fsmax > 0){
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1';
                        $spinType = 's';
                    }
                }
                $strOtherResponse = $strOtherResponse . '&w=' . $totalWin;
                if($str_trail != ''){
                    $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                }                
                if($str_stf != ''){
                    $strOtherResponse = $strOtherResponse . '&stf='. $str_stf;
                }
                if($str_slm_mp != ''){
                    $strOtherResponse = $strOtherResponse . '&slm_mp=' . $str_slm_mp . '&slm_mv=' . $str_slm_mv;
                }
                if($g != null){
                    $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($g));
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&wlc_v=' . $strWinLine;
                }
                
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&st=rect&c='.$betline.'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20';
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if( $slotEvent['slotEvent'] != 'freespin' && $fsmax > 0) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"CurrentRespin":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
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

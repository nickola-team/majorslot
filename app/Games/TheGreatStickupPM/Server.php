<?php 
namespace VanguardLTE\Games\TheGreatStickupPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [5,9,2,3,7,4,13,10,5,13,5,10,1,6,12]);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $str_mo = '';
                
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', $lastEvent->serverResponse->BonusState);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    if (isset($lastEvent->serverResponse->ReplayGameLogs)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', json_decode(json_encode($lastEvent->serverResponse->ReplayGameLogs), true)); //ReplayLog
                    }
                    if (isset($lastEvent->serverResponse->FreeStacks)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', json_decode(json_encode($lastEvent->serverResponse->FreeStacks), true)); // FreeStack
                    }
                    $bet = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $bet = '50.00';
                }
                $currentReelSet = 0;
                $spinType = 's';
                $strOtherResponse = '';
                if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                {
                    $strOtherResponse = '&fs=' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul=1';
                    $currentReelSet = 1;
                    if(isset($lastEvent->serverResponse->ReplayGameLogs) && $lastEvent->serverResponse->StrAccv != ''){
                        $strOtherResponse = $strOtherResponse .'&accv='. $lastEvent->serverResponse->StrAccv;
                    }
                    if(isset($lastEvent->serverResponse->StrStf) && $lastEvent->serverResponse->StrStf != ''){
                        $strOtherResponse = $strOtherResponse .'&stf='. $lastEvent->serverResponse->StrStf;
                    }
                    if(isset($lastEvent->serverResponse->StrSts) && $lastEvent->serverResponse->StrSts != ''){
                        $strOtherResponse = $strOtherResponse .'&sts='. $lastEvent->serverResponse->StrSts;
                    }
                    if(isset($lastEvent->serverResponse->StrSty) && $lastEvent->serverResponse->StrSty != ''){
                        $strOtherResponse = $strOtherResponse .'&sty='. $lastEvent->serverResponse->StrSty;
                    }
                }
                if($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') > 0){
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));

                $Balance = $slotSettings->GetBalance();
                // $response = 'wsc=1~bg~200,20,2,0,0~0,0,0,0,0&def_s=6,7,10,4,9,5,11,1,8,3,8,4,8,10,6&balance='. $Balance .'&cfgs=1971&ver=2&index=1&balance_cash='. $Balance .'&reel_set_size=2&def_sb=5,3,4,6,7&def_sa=11,11,10,8,9&balance_bonus=0.00&na='.$spinType.'&scatters=&gmb=0,0,0&rt=d&stime=1657006681057&sa=11,11,10,8,9&sb=5,3,4,6,7&sc='. implode(',', $slotSettings->Bet) .'&defc=100.00&sh=3&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&n_reel_set='.$currentReelSet.$strOtherResponse.'&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;5000,1000,100,10,0;2000,400,40,5,0;750,100,25,5,0;750,100,25,5,0;150,40,5,0,0;150,40,5,0,0;150,40,5,0,0;100,25,5,0,0;100,25,5,0,0&l=10&reel_set0=9,6,9,5,4,10,8,7,9,8,7,11,8,5,1,10,5,11,8,4,3,8~7,8,9,10,5,11,6,10,7,10,8,8,11,1,3,4,4,11,6,8,10,11~5,10,9,6,8,11,1,11,7,9,11,3,10,3,9,4,10,8,5,7,7~9,7,3,10,10,11,11,9,1,3,8,11,7,5,6,4,5,10,8,9~10,1,10,11,9,6,4,9,8,3,7,5,8,5,9,1,7,10&s='.$lastReelStr.'&reel_set1=5,7,5,1,9,8,11,4,4,11,7,6,6,5,3,9,8,3,10,10,8~4,1,9,6,8,6,7,4,9,11,3,5,8,7,3,10,11,7,10,10~11,1,4,10,9,11,3,9,3,8,8,6,10,9,6,5,5,10,4,11,11,7~4,11,9,9,10,7,11,5,8,11,4,7,3,10,6,1,7,10~9,9,8,4,7,4,6,5,3,11,11,10,7,3,7,10,10,1,9,1,8,6';
                $response = 'def_s=5,9,2,3,7,4,13,10,5,13,5,10,1,6,12&balance='. $Balance .'&cfgs=5485&ver=3&index=1&balance_cash='. $Balance .'&def_sb=2,9,2,4,9&reel_set_size=6&def_sa=4,8,13,4,11&reel_set='.$currentReelSet.$strOtherResponse.'&balance_bonus=0.00&na='.$spinType.'&scatters=1~100,10,2,0,0~0,0,0,0,0~1,1,1,1,1&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"14285714",max_rnd_win:"5000"}}&wl_i=tbm~5000&stime=1657072599396&sa=4,8,13,4,11&sb=2,9,2,4,9&sc='. implode(',', $slotSettings->Bet) .'&defc=50.00&sh=3&wilds=2~1500,300,100,0,0~1,1,1,1,1&bonuses=0&st=rect&c='.$bet.'&sw=5&sver=5&g={overlay:{def_s:"15,15,15,15,15,15,15,15,15,15,15,15,15,15,15",def_sa:"15,15,15,15,15",def_sb:"15,15,15,15,15",reel_set:"4",s:"15,15,15,15,15,15,15,15,15,15,15,15,15,15,15",sa:"15,15,15,15,15",sb:"15,15,15,15,15",sh:"3",st:"rect",sw:"5"}}&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;1000,200,65,0,0;700,150,50,0,0;500,100,30,0,0;300,60,20,0,0;200,35,12,0,0;100,15,7,0,0;60,10,5,0,0;40,9,4,0,0;30,7,3,0,0;25,6,2,0,0;20,5,1,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0&l=20&reel_set0=8,4,9,11,7,12,10,8,6,7,3,4,11,7,12,6,4,12,8,5,11,13,5,10,3,11,5,11,4,7,2,4,3,7,8,10,1,12,3,13,10,6,9,10~7,6,13,10,5,7,12,5,10,5,8,5,10,9,7,6,8,1,11,8,6,9,6,13,7,6,11,9,4,9,4,11,13,12,6,3,8,5,13,1,4,2,13,10,11,12,8,4,8,2,12,3,9,13,3,11,5,8,3~4,8,1,9,10,7,3,13,10,9,2,1,4,1,6,13,6,7,3,9,6,8,12,4,10,6,5,9,6,12,5,13,4,3,8,13,9,7,5,13,4,11,8,3,10,5,10,11,8,9,6,13,4,2,7,12,7,9,5,10,13,9,10,6,7,3,5,11,7,11,8~6,3,9,4,10,8,12,7,10,12,10,7,11,10,11,7,5,10,8,11,4,5,2,11,8,13,3,13,6,9,7,11,9,4,2,8,12,3,1,5,4,9,13,12,7,11,4,10,13,9,8,6,12,5,12,13,6,2,11,1,8,13,9,5,4~9,2,5,4,10,12,6,10,3,13,1,12,9,10,11,2,10,5,12,12,9,8,13,11,7,6,2,13,11,13,3,9,3,6,5,12,12,4,8,9,12,13,8,6,5,11,1,4,3,12,10,13,7,13,6,11,13,11,7,11,4,7,4,10,3,1,4,5,3,7,1,11,8,7,12&s='.$lastReelStr.'&accInit=[{id:0,mask:"cp;tp;lvl;sc;cl"}]&reel_set2=15,15,15,16,15,15,15,15,15,15,15,16,15,15,15,15,15,15,16,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,16,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15~16,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,16,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,16,15,15,15,15,16,15,15,15,15,15,15,15,15,15~15,15,15,15,15,15,15,15,15,15,16,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,16,15,15,15,15,15,15,15,15,15,15,15,15,15,15~15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,16,15,15,15,15,15,16,15,15,15,15,15,15,15,15,15,15,15~15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,16,15,16,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,16,15,15,15,15,15,15,15&reel_set1=11,10,14,6,11,3,10,12,5,8,13,10,12,4,11,3,7,9,10,8,14,13,5,3,7,13,5,6,8,11,12,2,12,9,13,14,7,13,11,4,6,9,4,10,7,9,12,9,12,9,14,2,11,6,8,13,5~5,8,4,9,6,5,9,3,14,3,13,5,13,12,7,12,13,2,10,6,13,10,4,12,13,5,14,9,6,11,12,10,11,3,6,9,11,5,7,10,3,9,11,10,7,2,4,7,4,8,11,14,9,8~12,11,6,7,9,14,5,11,4,12,13,9,7,13,6,9,8,14,4,10,3,9,6,11,10,8,10,13,2,13,14,12,11,12,13,12,11,3,8,11,4,5,8,10,5~11,6,8,9,7,13,5,8,13,14,6,5,7,12,4,8,9,7,9,12,4,5,4,11,13,10,12,9,10,14,8,5,13,10,12,9,11,10,6,3,2,4,13,3,12,5,11,12,6,2,7,11,9,7~13,14,8,3,8,6,2,6,12,13,12,3,8,10,4,12,11,5,10,11,12,9,8,12,5,10,7,9,10,7,3,10,11,13,9,3,12,7,9,6,14,4,11&reel_set4=15,15,15~15,15,15~15,15,15~15,15,15~15,15,15&reel_set3=15,15,15,15,15,15,15,16,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,16,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,16,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15~15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,16,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15~15,16,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,16,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15~15,15,15,15,15,16,15,15,15,15,15,15,15,15,16,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15~15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,16,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,16,15,15&reel_set5=9,1,9,13,11,7,1,11,1,3,11,1,5,13,1,9,1,5,1,13,7,11,7,1,9,7,9,13,9,13,3,1,9,1,11,9,1,7,11,13,1,11,5,11,9,1,7,1,11,1,5,3,13,9,1,5,1,7,1,11,5,7~6,1,8,1,12,1,12,10,10,6,4,1,8,12,4,1,12,8,1,6,1,12,4,1,10,4,12,6,6,4,8,10,6,1,8,12,8,4,8,6,1,6,6,12,8,4,1,12,1,4,1,12,1,8,1,10,12,1,12~1,7,1,11,10,7,12,1,3,1,9,10,1,5,1,7,13,7,1,4,1,8,12,13,1,13,1,4,13,9,10,1,10,1,9,5,11,1,10,1,12,5,1,7,10,1,9,1,6,1,9,1,7,9,3,6,4,9,11,1,6,10,4,9,1,4,5,1,13,1,6,10,5,13,1,7,8,3~11,1,4,3,9,4,5,7,3,2,13,5,10,4,12,5,8,11,7,5,12,5,12,4,13,9,8,2,8,7,12,9,4,7,10,11,6,12,11,10,8,11,7,4,11,3,7,5,13,6,8,11,5,11,2,4,13,2,10,12,7,12,7,9,4,12,13,11,8,3,13,1,4,7,10,6,12,9,6,7,13,3,6,8,6,12,5,11,8,7,8,9,4,12,5,13,10,8,6,10,3,9,10,13,2,12,6,7,9,7,8,10,13,11,13,8,10,11,6,9,2,9~4,9,3,6,13,10,13,5,11,5,4,12,9,11,7,4,5,6,7,4,10,12,11,13,10,8,12,8,7,13,5,3,11,12,11,3,13,7,8,5,4,11,8,5,12,2,10,7,12,4,10,8,11,5,12,5,9,1,9,11,10,9,11,12,10,12,6,8,5,13,2,7,3,7,9,10,11,9,8,11,12,3,9,11,10,12,13,6,11,12,2,13,10,7,4,12,13,6,12,6,13,5,4,11,2,8,6,7,10,7,2,13,4,3,6,12,3,9,6,13,11,3,6,8,7,8,5,9,12,10,2,5,4,9,4,9,6,13,1,10,13,8,13,7';
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
                $linesId[0] = [2, 2, 2, 2, 2];
                $linesId[1] = [1, 1, 1, 1, 1];
                $linesId[2] = [3, 3, 3, 3, 3];
                $linesId[3] = [1, 2, 3, 2, 1];
                $linesId[4] = [3, 2, 1, 2, 3];
                $linesId[5] = [2, 1, 1, 1, 2];
                $linesId[6] = [2, 3, 3, 3, 2];
                $linesId[7] = [1, 1, 2, 3, 3];
                $linesId[8] = [3, 3, 2, 1, 1];
                $linesId[9] = [2, 3, 2, 1, 2];
                $linesId[10] = [2, 1, 2, 3, 2];
                $linesId[11] = [1, 2, 2, 2, 1];
                $linesId[12] = [3, 2, 2, 2, 3];
                $linesId[13] = [1, 2, 1, 2, 1];
                $linesId[14] = [3, 2, 3, 2, 3];
                $linesId[15] = [2, 2, 1, 2, 2];
                $linesId[16] = [2, 2, 3, 2, 2];
                $linesId[17] = [1, 1, 3, 1, 1];
                $linesId[18] = [3, 3, 1, 3, 3];
                $linesId[19] = [1, 3, 3, 3, 1];
                
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
                $allBet = $betline * $lines;
                
                // $winType = 'bonus';
                // $_winAvaliableMoney = $slotSettings->GetBank('bonus');

                $freeStacks = []; 
                $isGeneratedFreeStack = false;
                $bonusSymbol = 0;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
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
                $strInitReel = '';
                $strAccv = '';
                $strStf = '';
                $strSts = '';
                $strSty = '';
                $arr_g = [];
                $fsmore = 0;
                
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 1];
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $arr_g = $stack['g'];
                    $strInitReel = $stack['is'];
                    $strAccv = $stack['accv'];
                    $strStf = $stack['stf'];
                    $strSts = $stack['sts'];
                    $strSty = $stack['sty'];
                    $fsmore = $stack['fsmore'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $lastReel = explode(',', $stack[0]['reel']);
                    $currentReelSet = $stack[0]['reel_set'];
                    $arr_g = $stack[0]['g'];
                }
                $reels = [];
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;

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
                    for($j = 1; $j < 5; $j++){
                        $ele = $reels[$j][$linesId[$k][$j] - 1];
                        if($firstEle == $wild){
                            $firstEle = $ele;
                            $lineWinNum[$k] = $lineWinNum[$k] + 1;
                        }else if($ele == $firstEle || $ele == $wild){
                            $lineWinNum[$k] = $lineWinNum[$k] + 1;
                            if($j == 4){
                                $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                $totalWin += $lineWins[$k];
                                $_obf_winCount++;
                                $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                    $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                }
                            }
                        }else{
                            if($slotSettings->Paytable[$firstEle][$lineWinNum[$k]] > 0){
                                $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
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
                

                if($scatterCount >= 3){
                    $muls = [0, 0, 0, 2, 10, 100];
                    $scatterWin = $muls[$scatterCount] * $betline * $lines;
                }
                $totalWin = $totalWin + $scatterWin; 
                
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 7);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                }
                if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);    
                }

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
                $strOtherResponse = '';
                $isState = true;
                $isEnd = true;
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $spinType = 's';
                    $isEnd = false;
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                    {
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin').'&fsmul_total=1&fsend_total=1&fsres_total='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 'c';
                        $isState = true;
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
                    if($strAccv != ''){
                        $strOtherResponse = $strOtherResponse .'&accv='. $strAccv;
                    }
                    if($strStf != ''){
                        $strOtherResponse = $strOtherResponse .'&stf='. $strStf;
                    }
                    if($strSts != ''){
                        $strOtherResponse = $strOtherResponse .'&sts='. $strSts;
                    }
                    if($strSty != ''){
                        $strOtherResponse = $strOtherResponse .'&sty='. $strSty;
                    }
                    $strOtherResponse = $strOtherResponse . '&accm=cp~tp~lvl~sc&acci=0';
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                    if($scatterCount >= 3 ){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse = $strOtherResponse  .'&fsmul=1&fsmax='. $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fswin=0.00&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') .'&fsres=0.00&psym=1~'. $scatterWin .'~' . implode(',', $scatterPoses);
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $stack);                    
                }

                $strOtherResponse = $strOtherResponse . '&g={overlay:{reel_set:"'. $arr_g['overlay']['reel_set'] .'",s:"'. $arr_g['overlay']['s'] .'",sa:"15,15,15,15,16",sb:"15,15,15,15,15",sh:"3",st:"rect",sw:"5"}}';
                
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .$strWinLine .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&c='.$betline.'&sver=5&n_reel_set='.$currentReelSet.'&counter='. ((int)$slotEvent['counter'] + 1) .'&l=10&s='.$strLastReel.'&w='. $totalWin;
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"StrAccv":"' . $strAccv . '","StrStf":"' . $strStf . '","StrSts":"' . $strSts . '","StrSty":"' . $strSty . '","FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
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

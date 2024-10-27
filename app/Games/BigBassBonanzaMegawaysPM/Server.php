<?php 
namespace VanguardLTE\Games\BigBassBonanzaMegawaysPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [13,10,6,12,6,13,4,8,10,8,3,10,6,9,11,9,6,9,5,8,3,8,3,10,4,13,12,9,5,11,13,13,11,13,4,12,13,13,13,13,13,9]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $strOtherResponse = '';
                $stack = null;
                
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $lastEvent->serverResponse->TumbWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', $lastEvent->serverResponse->TumbleState);
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
                        if($slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') > 0){
                            $stack = $FreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') -1];
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
                $g = null;
                $wmv = 1;
                $str_mo = '';
                $str_mo_t = '';
                $mo_tv = 0;
                $str_accv = '';                
                $fsmore = 0;
                $str_mo_wpos = '';
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if(isset($stack)){
                    if($stack['g'] != ''){
                        $g = $stack['g'];
                    }
                    $wmv = $stack['wmv'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $str_mo_wpos = $stack['mo_wpos'];
                    $str_accv = $stack['accv'];
                    $mo_tv = $stack['mo_tv'];
                    $fsmore = $stack['fsmore'];
                }


                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 )
                {
                    $fs = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                    $strOtherResponse = '&fs=' . $fs . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&w=0.00&fsmul=1';

                    $strOtherResponse = $strOtherResponse . '&accm=cp~mp;cp~mp&acci=0;1&wmt=pr2&wmv=' . $wmv;
                    if($mo_tv > 0){
                        $strOtherResponse = $strOtherResponse . '&mo_tv='. $mo_tv .'&mo_tw=' . ($mo_tv * $bet);
                    }
                    if($str_accv != ''){
                        $strOtherResponse = $strOtherResponse . '&accv=' . $str_accv;
                    }
                    if($str_mo_wpos != ''){
                        $strOtherResponse = $strOtherResponse . '&mo_wpos=' . $str_mo_wpos;
                    }
                }
                    
                if($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') > 0){
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }
                if($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') > 0){
                    $strOtherResponse = $strOtherResponse.'&rs=t&tmb_win='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbWin').'&rs_p='.($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') - 1).'&rs_c=1&rs_m=1';                    
                }
                if($wmv > 1){
                    $strOtherResponse = $strOtherResponse . '&gwm=' . $wmv;
                }
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                }
                if($g != null){
                    $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($g));
                }else{
                    $strOtherResponse = $strOtherResponse . '&g={reg:{def_s:"4,8,10,8,3,10,6,9,11,9,6,9,5,8,3,8,3,10,4,13,12,9,5,11,13,13,11,13,4,12,13,13,13,13,13,9",def_sa:"3,8,11,12,6,9",def_sb:"4,9,8,9,3,12",reel_set:"0",s:"4,8,10,8,3,10,6,9,11,9,6,9,5,8,3,8,3,10,4,13,12,9,5,11,13,13,11,13,4,12,13,13,13,13,13,9",sa:"3,8,11,12,6,9",sb:"4,9,8,9,3,12",sh:"6",st:"rect",sw:"6"},top:{def_s:"6,12,6,10",def_sa:"8",def_sb:"5",reel_set:"1",s:"6,12,6,10",sa:"8",sb:"5",sh:"4",st:"rect",sw:"1"}}';
                }
                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=13,10,6,12,6,13,4,8,10,8,3,10,6,9,11,9,6,9,5,8,3,8,3,10,4,13,12,9,5,11,13,13,11,13,4,12,13,13,13,13,13,9&balance='. $Balance .'&nas=13&cfgs=5070&ver=2&mo_s=7&index=1&balance_cash='. $Balance .'&mo_v=40,100,200,300,400,500,1000,80000&reel_set_size=10&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0,0~0,0,0,0,0,0~1,1,1,1,1,1&gmb=0,0,0&rt=d&gameInfo={props:{nas:"13",max_rnd_sim:"1",max_rnd_hr:"1313543",max_rnd_win:"4000"}}&wl_i=tbm~4000&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=50.00&sh=7&wilds=2~0,0,0,0,0,0~1,1,1,1,1,1&bonuses=0&fsbonus=&st=rect&c='.$bet.'&sw=6&sver=5&counter=2&paytable=0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;1000,500,200,100,40,0;150,50,40,20,0,0;40,30,20,5,0,0;40,15,10,5,0,0;35,12,8,4,0,0;35,12,8,4,0,0;20,12,5,4,0,0;20,10,5,3,0,0;18,10,5,3,0,0;16,8,4,2,0,0;0,0,0,0,0,0;0,0,0,0,0,0&l=20&reel_set0=6,8,7,5,9,7,5,9,7,9,4,11,8,12,6,7,3,8,11,12,4,7,10,7,7,7,7,7,5,1,9,6,7,1,7,6,11,7,5,9,8,9,11,7,11,6,4,3,11,4,7,8,5,6,10~10,1,12,7,8,12,3,4,6,7,7,10,6,7,8,9,10,5,11,5,6,10,7,7,7,7,7,12,11,5,4,6,8,5,12,4,7,10,11,8,7,3,12,8,10,6,12,7,6,5,4,9~1,8,5,11,8,10,5,4,12,11,7,12,3,9,6,4,7,7,7,7,3,9,12,7,9,7,9,10,6,5,8,6,10,7,3,7,4,7,7,11~7,7,6,7,9,10,11,1,8,11,5,7,7,1,7,10,4,7,7,7,7,4,5,3,6,5,12,8,5,3,4,9,11,6,9,12,8,9,6,8,10~8,6,7,9,11,6,11,12,4,7,4,6,4,9,6,7,5,12,4,1,5,7,8,12,7,7,7,7,1,11,7,9,5,10,7,7,3,9,3,10,5,9,8,6,10,11,8,10,5,12,7,7,6,7~11,12,9,6,8,3,5,7,8,7,5,12,9,3,7,4,10,9,7,1,7,7,7,7,12,9,7,6,4,1,6,7,11,8,5,7,6,7,9,6,10,5,4&s='.$lastReelStr.'&accInit=[{id:0,mask:"cp;mp"},{id:1,mask:"cp;mp"}]&reel_set2=11,7,5,7,9,5,11,7,9,7,7,7,7,7,1,7,3,11,7,11,1,3,5,7,1,9,7,11,9~10,12,8,4,12,6,8,4,10,6,8,6,12,4,10,6,12,10,12,6,4,10,6,8~10,8,3,1,10,7,7,9,3,5,1,6,5,10,8,10,3,6,11,7,11,7,7,7,7,9,4,12,7,7,6,4,9,12,8,7,6,7,7,1,11,4,5,9,5,6,8,12,9,10~7,5,11,7,12,4,8,4,6,10,7,12,9,8,7,7,7,7,4,7,11,5,3,9,6,7,11,8,6,10,6,3,10,9~9,7,12,11,6,7,8,7,6,12,5,3,9,5,3,10,9,11,12,9,10,4,8,5,11,6,7,7,7,7,4,7,8,5,12,8,10,7,7,9,6,7,7,8,7,6,5,6,7,4,11,10,12,9,3,7,4,11~12,5,9,3,11,10,5,6,9,5,11,8,12,7,7,4,7,7,7,7,11,10,6,7,8,7,7,6,3,4,10,12,8,9,7,4,9,8,7,6&t=243&reel_set1=8,11,12,5,11,12,11,5,11,10,6,11,12,7,5,6,3,3,3,9,4,2,11,9,8,11,11,4,12,12,8,5,6,7,10,9,7,10,10,10,5,3,10,4,11,11,3,5,10,8,8,3,12,7,4,12,11,6,8,8,8,3,8,7,4,12,11,8,6,8,12,12,11,10,4,9,4,5,7,7,7,12,6,11,6,9,9,10,10,4,12,11,11,12,11,11,12,6,12,11,11,11,10,8,2,7,7,11,5,2,11,9,3,9,12,7,2,3,7,10,12&reel_set4=8,12,10,8,10,4,6,10,6,10,8,6,12,6,4,8~9,7,9,11,5,7,11,7,7,3,7,7,7,7,7,5,7,7,9,3,1,11,7,5,7,11,1~7,8,4,7,4,5,7,10,8,11,9,6,4,5,7,12,10,3,7,10,7,7,7,7,6,7,5,8,12,3,6,9,7,7,9,10,11,6,11,10,6,12,3,11,9,8~8,7,1,11,8,4,9,6,7,9,6,7,8,9,7,1,7,6,10,7,11,12,7,7,7,7,6,12,6,3,4,11,6,12,8,9,3,1,10,5,7,5,4,10,5,9,5,4,9~9,10,9,12,6,5,6,7,3,4,3,9,10,7,4,5,7,9,11,7,9,6,5,12,7,7,7,7,6,8,9,8,4,8,12,6,11,7,10,5,12,7,5,6,7,11,10,7,8,4,7,7,11~9,11,7,10,4,7,7,9,6,7,9,8,7,6,9,10,12,5,9,3,8,4,5,7,7,7,7,11,9,11,6,7,7,6,8,6,3,5,10,7,4,11,8,12,6,5,7,12,5,10,7&reel_set3=4,6,8,10,12,10,6,4,12,10,12,12,12,6,10,6,12,10,4,8,12,12,4,6,8,10,10,10,8,12,12,6,12,6,12,12,10,12,8,4,8,8,8,12,12,10,12,10,8,4,8,4,10,12,8,8&reel_set6=7,9,11,7,7,3,11,9,7,7,7,7,7,9,5,7,5,11,7,7,11,5,7~8,6,12,8,10,12,6,4,8,6,8,10,12,4,6,10~5,3,10,9,4,9,6,3,4,11,10,12,7,10,5,6,7,12,7,7,7,7,5,10,11,7,4,3,8,11,9,7,6,8,12,6,7,3,5,7,12,7,10,7,8,6~4,8,12,9,7,5,6,9,8,6,10,6,7,7,7,7,4,7,1,8,7,10,11,1,9,7,3,5,12,7,11,5~4,7,8,10,9,10,7,8,11,12,11,1,6,7,4,6,1,12,6,12,7,7,7,7,5,3,5,1,10,4,9,8,6,7,11,9,10,6,7,3,5,7,9,7,5,12~9,7,5,7,6,7,5,9,4,7,12,6,12,9,4,8,10,6,7,7,7,7,9,8,5,7,8,3,11,9,11,4,5,10,3,10,8,7,7,12,6&reel_set5=11,9,11,3,7,5,5,7,11,11,9,11,3,3,3,5,9,11,7,11,11,3,9,11,5,5,9,7,7,7,9,9,11,11,7,9,11,3,11,11,7,11,3,11,11,11,5,11,7,11,7,5,11,11,7,7,3,11,5,11,3&reel_set8=9,8,6,7,10,10,3,10,4,8,10,12,9,5,9,11,12,5,6,9,12,7,10,10,14,3,8,9,11,5,7,8,11,7,11,7,10,9,12,4,10,11,7,9~11,7,4,8,10,10,12,11,8,11,10,3,7,6,11,9,3,10,11,4,6,8,12,14,12,5,7,10~8,7,4,12,10,8,8,4,5,12,14,12,3,6,11,7,9,9,6,5,10,11,7,12,11,3,10,11,5~11,7,8,9,11,4,10,12,6,12,12,12,4,7,6,9,5,3,11,12,12,10,9,10,11,11,11,8,8,5,12,5,10,11,14,6,3,11,7~10,11,6,10,9,11,5,11,12,9,9,12,12,12,12,8,3,8,7,9,14,4,4,12,9,7,6,9,9,9,10,11,11,12,12,11,3,12,7,12,5,11,11,11,4,8,6,7,12,5,11,11,10,12,5,10,12~5,7,14,10,6,7,11,11,5,6,6,6,8,10,6,11,12,9,11,7,7,11,9,9,9,4,4,12,3,9,5,14,12,11,4,3,11,11,11,6,10,11,12,8,9,12,11,6,10&reel_set7=10,6,8,12,4,12,12,6,4,8,10,12,12,12,4,10,10,8,6,12,6,4,8,8,10,10,10,12,10,8,4,12,12,10,12,6,12,8,6,8,8,8,12,4,12,4,12,6,8,12,8,10,12,10,12&reel_set9=9,7,11,3,8,9,11,6,11,2,2,9,8,5,11,10,11,12,8,12,2,3,3,3,8,9,11,8,4,6,2,4,5,9,8,7,4,5,10,12,10,9,11,6,2,12,10,10,10,2,11,11,12,12,10,11,4,10,11,10,7,6,8,8,10,12,12,2,11,11,2,12,8,8,8,3,11,2,9,12,3,9,12,11,8,11,6,2,11,12,7,3,11,7,14,10,12,7,7,7,10,7,12,7,5,3,5,8,12,2,12,11,10,11,7,6,10,12,11,4,9,4,11,11,11,14,12,8,8,4,11,7,10,4,11,5,5,4,2,6,11,10,2,12,12,2,9,8,11';
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
                $isTumb = false;
                if($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') > 0){
                    $isTumb = true;
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
                $tumbAndFreeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'freespin' || $isTumb == true){
                    if($slotEvent['slotEvent'] == 'freespin' && $isTumb == false){
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    }
                    $leftFreeGames = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') - $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame'); 
                    $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                    if($isTumb == false){
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                    }
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
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
                $scatter = '1';
                $empty = '13';
                $Balance = $slotSettings->GetBalance();
                $totalWin = 0;
                $this->winLines = [];
                $bonusMpl = 1;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $g = null;
                $wmv = 1;
                $str_mo = '';
                $str_mo_t = '';
                $mo_tv = 0;
                $str_accv = '';                
                $fsmore = 0;
                $str_mo_wpos = '';
                if($slotEvent['slotEvent'] == 'freespin' || $isTumb == true){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    if($stack['g'] != ''){
                        $g = $stack['g'];
                    }
                    $wmv = $stack['wmv'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $str_mo_wpos = $stack['mo_wpos'];
                    $str_accv = $stack['accv'];
                    $mo_tv = $stack['mo_tv'];
                    $fsmore = $stack['fsmore'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $lastReel = explode(',', $stack[0]['reel']);
                    if($stack[0]['g'] != ''){
                        $g = $stack[0]['g'];
                    }
                    $wmv = $stack[0]['wmv'];
                    $str_mo = $stack[0]['mo'];
                    $str_mo_t = $stack[0]['mo_t'];
                    $str_mo_wpos = $stack[0]['mo_wpos'];
                    $str_accv = $stack[0]['accv'];
                    $mo_tv = $stack[0]['mo_tv'];
                    $fsmore = $stack[0]['fsmore'];
                }

                $reels = [];
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;
                $fishwildcount = 0;
                for($i = 0; $i < 6; $i++){
                    $reels[$i] = [];
                    for($j = 0; $j < 7; $j++){
                        $reels[$i][$j] = $lastReel[$j * 6 + $i];
                        if($lastReel[$j * 6 + $i] == $scatter){
                            $scatterCount++;
                            $scatterPoses[] = $j * 6 + $i;
                        }else if($lastReel[$j * 6 + $i] == 14){
                            $fishwildcount++;
                        }
                    }
                }
                for($r = 0; $r < 7; $r++){
                    if($reels[0][$r] != $scatter && $reels[0][$r] != 13){
                        $this->findZokbos($reels, $reels[0][$r], 1, [($r * 6)]);
                    }                        
                }
                $uniqueFirstSymbols = array_unique(
                    array_map(function ($line) {return $line['FirstSymbol'];}, $this->winLines)
                );
                $isNewTumb = false;
                $wlc_vs = [];
                $money_normalwin = 0;
                $money_normalwinLine = '';
                foreach ($uniqueFirstSymbols as $idx => $symbol) {
                    $dupLines = array_filter($this->winLines, function($line) use ($symbol) {return $line['FirstSymbol'] === $symbol;});
                    $uniqueMulSymbols = array_unique(
                        array_map(function ($line) {return $line['Mul'];}, $dupLines)
                    );
                    foreach($uniqueMulSymbols as $mul){
                        $dupMulLines = array_filter($dupLines, function($line) use ($mul) {return $line['Mul'] === $mul;});
                        // 윈라인 심볼위치배열
                        $symbols = array_unique(array_flatten(array_map(function($line) {return $line['Positions'];}, $dupMulLines)));
                        $strSymbols = implode(',', $symbols);
                        // 윈라인 응답
                        $dupCount = count($dupMulLines);
                        if($dupCount > 0){
                            $firstLineKey = array_key_first($dupMulLines);
                            $winLine = $dupMulLines[$firstLineKey];
                            if($slotEvent['slotEvent'] != 'freespin' || $symbol != 7){
                                $isNewTumb = true;
                                $winLineMoney = $slotSettings->Paytable[$symbol][$winLine['RepeatCount']] * $betline * $wmv * $dupCount;
                                $totalWin += $winLineMoney;
                                $strWinLine = "{$symbol}~{$winLineMoney}~{$dupCount}~{$winLine['RepeatCount']}~{$strSymbols}~l" . ($wmv > 1 ? "~" . $wmv : "");
                                $wlc_vs[] = $strWinLine;
                            }else{
                                $winLineMoney = $slotSettings->Paytable[$symbol][$winLine['RepeatCount']] * $betline * $wmv * $dupCount;
                                $money_normalwin = $winLineMoney;
                                $money_normalwinLine = "{$symbol}~{$winLineMoney}~{$dupCount}~{$winLine['RepeatCount']}~{$strSymbols}~l" . ($wmv > 1 ? "~" . $wmv : "");
                            }
                        }
                    }
                }
                if($isNewTumb == false && $money_normalwin > 0){
                    $totalWin += $money_normalwin;
                    $wlc_vs[] = $money_normalwinLine;
                }
                if($isNewTumb == false && isset($g) && isset($g['reg']) && isset($g['reg']['tmb']) && $g['reg']['tmb'] != ''){
                    $isNewTumb = true;
                }
                if($slotEvent['slotEvent'] == 'freespin' && $mo_tv > 0){
                    $fishwin =$mo_tv * $betline * $wmv;
                    if($fishwildcount > 1){
                        $fishwin = $fishwin * $fishwildcount;
                    }
                    $totalWin = $totalWin + $fishwin;
                }
                // if($scatterCount >= 4){
                //     $muls = [0,0,0,0,5,10,100];
                //     $scatterWin = $betline * $lines * $muls[$scatterCount];
                //     $totalWin = $totalWin + $scatterWin;
                // }


                $spinType = 's';
                if( $totalWin > 0) 
                {
                    $spinType = 'c';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }
                $_obf_totalWin = $totalWin;
                $isState = true;
                if($isNewTumb == true){
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') + 1);
                    $isState = false;
                    $spinType = 's';
                }else{
                    if( $scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin') 
                    {
                        $freeSpins = [0,0,0,10,15,20,25];
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freeSpins[$scatterCount]);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                    }else if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                    }

                }
                $strLastReel = implode(',', $lastReel);
                $lastLogReel = [];
                for($k = 0; $k < count($lastReel); $k++){
                    if($k < 6){
                        $lastLogReel[6-$k-1] = $lastReel[$k];
                    }else{
                        $lastLogReel[$k] = $lastReel[$k];
                    }
                }
                $strLastLogReel = implode(',', $lastLogReel);

                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $strOtherResponse = '';
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') + $totalWin);
                    $spinType = 's';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $isEnd = false;
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 && $isNewTumb == false) 
                    {
                        $isEnd = true;
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsend_total=1&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 'c';
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
                    $strOtherResponse = $strOtherResponse . '&accm=cp~mp;cp~mp&acci=0;1&wmt=pr2&wmv=' . $wmv;
                    if($mo_tv > 0){
                        $strOtherResponse = $strOtherResponse . '&mo_tv='. $mo_tv .'&mo_tw=' . ($mo_tv * $betline);
                    }
                    if($str_accv != ''){
                        $strOtherResponse = $strOtherResponse . '&accv=' . $str_accv;
                    }
                    if($str_mo_wpos != ''){
                        $strOtherResponse = $strOtherResponse . '&mo_wpos=' . $str_mo_wpos;
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') + $totalWin);
                    if($scatterCount >= 3 && $isNewTumb == false){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&accm=cp~mp;cp~mp&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&acci=0;1&fswin=0.00&fsres=0.00&fs=1';
                        if($str_accv != ''){
                            $strOtherResponse = $strOtherResponse . '&accv=' . $str_accv;
                        }
                    }
                }
                if($isNewTumb == true){
                    $strOtherResponse = $strOtherResponse . '&rs=mc&tmb_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') . '&rs_p=' . ($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') - 1) . '&rs_c=1&rs_m=1';
                }
                else if($isTumb == true){
                    if($slotEvent['slotEvent'] != 'freespin' && $scatterCount < 3){
                        $spinType = 'c';
                        $isState = true;
                    }
                    $strOtherResponse = $strOtherResponse.'&tmb_res='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbWin').'&rs_t='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbleState').'&tmb_win='.($slotSettings->GetGameData($slotSettings->slotId . 'TumbWin'));
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', 0);
                }
                if($wmv > 1){
                    $strOtherResponse = $strOtherResponse . '&gwm=' . $wmv;
                }
                if(count($wlc_vs) > 0){
                    $strOtherResponse = $strOtherResponse . '&wlc_v=' . implode(';', $wlc_vs);
                }
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                }
                if($g != null){
                    $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($g));
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sh=7&st=rect&c='.$betline.'&sw=6&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&w='.$totalWin.'&s=';
                $response_log = $response.$strLastLogReel;
                $response = $response . $strLastReel;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)  && $isNewTumb == false) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if($isNewTumb == false){
                    if( $slotEvent['slotEvent'] != 'freespin' && $scatterCount >= 3) 
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin'));
                    }
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"TumbWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"TumbleState":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState')  . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') .',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);

            }
            if($slotEvent['action'] == 'doSpin' || $slotEvent['action'] == 'doFSOption' || $slotEvent['action'] == 'doCollect' || $slotEvent['action'] == 'doCollectBonus' || $slotEvent['action'] == 'doBonus'){
                if($slotEvent['action'] == 'doSpin'){
                    $this->saveGameLog($slotEvent, $response_log, $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'), $slotSettings);
                }else{
                    $this->saveGameLog($slotEvent, $response, $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'), $slotSettings);
                }
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
            if($repeatCount < 6){
                for($r = 0; $r < 7; $r++){
                    if($firstSymbol == $reels[$repeatCount][$r] || $reels[$repeatCount][$r] == $wild){
                        $this->findZokbos($reels, $firstSymbol, $repeatCount + 1, array_merge($positions, [($repeatCount + $r * 6)]));
                        $bPathEnded = false;
                    }
                }
            }
            if($bPathEnded == true){
                if($repeatCount >= 3 || ($firstSymbol == 3 && $repeatCount >= 2)){
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

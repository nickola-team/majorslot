<?php 
namespace VanguardLTE\Games\PyramidKingPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 25);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [7,5,11,3,2,1,9,11,10,2,6,6,11,9,2]);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusSymbol', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'MysteryScatter', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $stack = null;
                
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusSymbol', $lastEvent->serverResponse->BonusSymbol);
                    $slotSettings->SetGameData($slotSettings->slotId . 'MysteryScatter', $lastEvent->serverResponse->MysteryScatter);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', $lastEvent->serverResponse->BonusState);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', $lastEvent->serverResponse->Bgt);
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
                    $bet = '40.00';
                }
                $currentReelSet = 0;
                $spinType = 's';
                $strOtherResponse = '';
                if($slotSettings->GetGameData($slotSettings->slotId . 'MysteryScatter') == 1){
                    $spinType = 'm';
                    $strOtherResponse = '&fsmul=1&mb=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fs=1&fsres=0.00';
                }else if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    $strOtherResponse = '&fs=' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&w=0.00&fsmul=1';

                    
                    $strOtherResponse = $strOtherResponse  .'&ms='. $slotSettings->GetGameData($slotSettings->slotId . 'BonusSymbol');
                }
                $str_initReel = '';
                $str_mo = '';
                $str_mo_t = '';
                $str_rsb_s = '';
                $rsb_m = 0;
                $rsb_c = -1;
                $bw = 0;
                $bpw = 0;
                $rw = 0;
                $end = -1;
                if($slotSettings->GetGameData($slotSettings->slotId . 'Bgt') == 11){
                    $spinType = 'b';
                }
                if($stack != null){
                    $str_initReel = $stack['initReel'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $str_rsb_s = $stack['rsb_s'];
                    $rsb_m = $stack['rsb_m'];
                    $rsb_c = $stack['rsb_c'];
                    $bw = $stack['bw'];
                    $bpw = str_replace(',', '', $stack['bpw']);
                    $end = $stack['end'];
                    $fsmore = $stack['fsmore'];
                    if($stack['reel_set'] > -1){
                        $currentReelSet = $stack['reel_set'];
                    }
                    if($bpw > 0){
                        $bpw = $bpw / $original_bet * $bet;
                    }
                    if($rw > 0){
                        $rw = $rw / $original_bet * $bet;                        
                    }
                    if($fsmore > 0 ){
                        $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                    }
                    $strOtherResponse = $strOtherResponse  . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                }
                if($str_rsb_s != ''){
                    $strOtherResponse = $strOtherResponse . '&bgt=11&rsb_s=' . $str_rsb_s . '&rsb_m=' . $rsb_m . '&rsb_c=' . $rsb_c;
                }
                if($bw == 1){
                    $strOtherResponse = $strOtherResponse . '&bw=' . $bw;
                }
                if($rw > 0){
                    $strOtherResponse = $strOtherResponse . '&rw=' . $rw;
                }
                if($bpw > 0){
                    $strOtherResponse = $strOtherResponse . '&bpw=' . $bpw;
                }
                if($end >= 0){
                    $strOtherResponse = $strOtherResponse . '&end=' . $end;
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));

                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=7,5,11,3,2,1,9,11,10,2,6,6,11,9,2&balance='. $Balance .'&cfgs=3084&ver=2&mo_s=11&index=1&balance_cash='. $Balance .'&reel_set_size=11&def_sb=5,9,3,10,3&mo_v=25,50,75,100,125,150,175,200,250,350,400,450,500,600,750,2500&def_sa=4,9,7,9,10&reel_set='.$currentReelSet.$strOtherResponse.'&mo_jp=750;2500;25000&balance_bonus=0.00&na='.$spinType.'&scatters=1~0,0,1,0,0~0,0,10,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&mo_jp_mask=jp3;jp2;jp1&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=4,9,7,9,10&sb=5,9,3,10,3&reel_set10=9,5,7,6,10,7,8,3,10,8,7,9,11,11,8,8,9,5,8,2,5,7,8,5,4,8,11,9,8,2,3,4,5,1,4,8,6,7,9~4,2,9,3,7,5,9,6,7,10,3,8,10,6,11,11,8,4,8,7,6,2,3,10,9,6,4,8,11,6,8,2,10,4,5,7,8,10,9,7,10~2,6,9,8,9,6,5,9,1,10,4,2,10,11,11,9,5,6,7,10,2,9,10,4,3,10,9,11,10,6,2,10,8,5,10,8,4,9,7,4~10,8,2,4,6,10,7,9,5,8,6,7,8,2,7,11,11,5,9,7,8,3,2,6,9,3,8,4,5,11,9,3,2,10,5,4,10,7,4,9,7,6~2,5,10,9,7,3,5,1,10,4,8,2,6,11,11,8,3,6,1,9,2,10,9,4,10,9,8,11,9,5,2,10,1,4,10,7,8,9,1&sc='. implode(',', $slotSettings->Bet) .'&defc=40.00&sh=3&wilds=2~500,250,25,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='. $bet .'&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;500,250,25,0,0;400,150,20,0,0;300,100,15,0,0;200,50,10,0,0;50,20,10,0,0;50,20,5,0,0;50,20,5,0,0;50,20,5,0,0;0,0,0,0,0;0,0,0,0,0&l=25&reel_set0=6,8,7,1,6,9,4,5,8,4,2,2,2,7,6,10,7,3,10,8,7,9,11,11,11,8,9,8,9,5,7,8,5,6,8,5,9,8~9,7,8,10,9,7,2,2,7,5,9,6,7,10,3,8,6,11,11,11,4,8,7,6,9,3,10,9,6,4,8,10,8,5,10,4,5,7,8,9,7,10~7,1,10,8,6,9,10,3,2,2,2,8,9,5,9,1,10,4,6,10,11,11,11,5,6,7,10,6,9,4,3,10,9,4,10,6,9,10,8,5~9,7,3,10,8,5,7,9,10,8,2,2,3,10,7,9,5,8,6,7,8,10,7,11,11,6,9,7,8,3,4,6,9,3,8,4,5,6,9,3,6~7,9,1,10,7,5,3,9,10,3,2,2,2,9,7,3,5,1,10,4,8,10,6,11,11,11,3,6,1,9,5,10,9,4,10,9,8,10,9,5,4&s='.$lastReelStr.'&reel_set2=8,3,9,8,7,9,11,10,3,8,9,5,8,9,5,7,2,5,3,8,5,11,11,5,3,8,5,1,4,6,2,7,9~5,8,11,11,7,4,2,10,9,7,5,3,6,7,10,3,8,6,10,11,6,10,4,8,7,6,9,3,10,2,6,4,8,10,11,8,5,2,3,5,7,8,2,9,7,6~10,4,2,3,8,4,9,6,5,9,1,10,4,6,10,11,9,10,3,6,7,4,6,9,10,2,3,10,6,4,11,11,9,10,3,5,10,8,2,5,7,6~11,11,10,8,2,4,3,5,7,3,5,8,6,7,2,10,7,9,11,7,9,6,7,3,4,6,9,2,8,4,5,6,11,3,2,10,5,4,10,7,2,9,7,6~3,2,8,4,9,2,6,5,1,10,4,2,10,6,11,9,3,10,6,1,9,5,3,9,2,10,4,8,10,11,11,2,10,1,4,6,2,7,9,1,8&reel_set1=7,8,4,9,8,7,6,5,7,8,3,9,8,7,9,11,10,3,8,9,5,8,9,5,7,2,5,3,8,5,11,11,5,3,8,5,1,4,6,2,7,9~11,11,7,4,2,10,9,7,5,3,6,7,10,3,8,6,10,11,6,10,4,8,7,6,9,3,10,2,6,4,8,10,11,8,5,2,3,5,7,8,2,9,7,6~11,9,10,4,2,3,8,4,9,6,5,9,1,10,4,6,10,11,9,10,5,6,7,4,6,9,10,2,3,10,6,4,11,11,9,10,3,5,10,8,10,5,7,6~9,3,7,10,4,5,11,11,10,8,2,4,3,5,7,3,5,8,6,7,2,10,7,9,11,7,9,6,7,3,4,6,9,2,8,4,5,6~8,4,9,2,6,5,1,10,4,2,10,6,11,9,3,10,6,1,9,5,3,9,2,10,4,8,10,11,11,2,10,1,4,6,9,7,9,1,8&reel_set4=7,8,4,9,8,7,6,5,7,8,3,9,8,7,9,11,5,3,8,9,5,10,8,9,5,7,2,5,3,8,5,11,11,5,3,8,5,1,4,6,2,7,9~9,8,7,10,5,8,11,11,7,5,6,10,11,6,10,4,8,7,6,9,3,10,2,6,4,8,10,11,8,5,2,3,5,7,8,2,9,7,6~9,10,4,2,3,8,4,9,6,5,9,1,10,5,6,10,11,9,10,5,6,7,4,6,9,10,2,3,10,6,4,11,11,9,10,3,5,10,8,2,5,7,6~9,3,7,10,4,5,11,11,6,8,2,4,9,11,7,9,6,7,3,4,6,9,2,8,4,5,6,11,3,2,10,5,4,10,7,2,9,7,6~9,10,3,2,8,4,9,2,6,5,1,10,4,2,10,6,11,9,5,10,6,1,9,5,3,9,2,10,4,8,10,11,11,2,10,1,4,6,2,7,9,1,8&reel_set3=11,4,7,8,4,9,8,4,6,5,7,8,3,9,8,7,9,11,10,3,8,9,5,8,9,5,7,2,5,4,8,5,11,11,5,3,8,5,1,4,6,2,7,9~9,8,7,10,5,8,11,11,7,4,2,10,9,7,5,3,6,7,10,3,8,6,10,11,6,10,4,8,7,4,9,3,10,2,6,4,8,10~9,10,4,2,3,8,4,9,6,5,9,1,10,4,6,10,11,9,10,5,6,7,4,6,9,10,2,3,10,6,4,11,11,9,10,3,5,10,8,2,5,7,6~11,11,10,8,2,4,3,5,7,3,5,8,6,7,2,10,7,9,11,7,9,6,7,3,4,6,9,2,8,4,5,6,11,3,2,10,5,4,10,7,2,4,7,6~9,10,3,2,8,4,9,2,6,5,1,10,4,2,10,6,11,9,3,10,6,1,9,5,3,9,2,10,4,8,10,11,11,2,10,1,4,6,2,7,9,1,8&reel_set6=4,7,8,4,9,8,7,6,5,7,8,3,9,8,7,9,11,10,3,8,9,7,8,9,5,7,2,5,3,8,5,11,11,5,3,8,5,1,4,6,2,7,9~11,11,7,4,2,10,9,7,5,3,6,7,10,3,8,6,10,11,7,10,4,8,7,6,9,3,10,7,6,4,8,7,11,8,5,2,3,5,7,8,2,9,7,6~9,10,4,2,3,8,4,9,6,5,9,1,10,4,6,10,11,9,10,5,6,7,4,6,7,10,2,3,10,6,4,11,11,9,10,3,5,7,8,2,5,7,6~11,11,10,7,2,4,3,5,7,3,5,8,6,7,2,10,7,9,11,7,9,6,7,3,4,6,9,2,8,4,5,6,11,3,2,10,5,4,10,7,2,9,7,6~9,10,3,2,8,4,9,2,6,7,1,10,4,2,10,6,11,9,3,10,6,1,9,5,7,9,2,10,4,7,10,11,11,2,10,1,4,6,2,7,9,1,8&reel_set5=7,8,4,9,8,7,6,5,7,8,3,9,8,7,9,11,10,3,8,6,5,8,9,5,7,2,5,3,6,5,11,11,5,3,8,5,1,4,6,2,7,9~9,8,7,10,5,8,11,11,7,4,2,10,9,7,5,3,6,7,10,3,8,6,10,11,6,10,4,8,7,6,9,3,10,2,6,4,8,10~9,6,4,2,3,8,4,9,6,5,9,1,10,4,6,10,11,9,10,5,6,7,4,6,9,10,2,3,10,6,4,11,11,9,10,3,5,10,8,2,5,7,6~9,3,7,10,4,5,11,11,10,8,2,4,3,5,7,3,5,8,6,7,2,10,6,9,11,7,9,6,7,3,4,6,9~9,10,3,2,8,4,9,2,6,5,1,10,4,2,10,6,11,9,3,10,6,1,9,5,3,9,2,10,4,8,10,11,11,2,10,1,4,6,2,7,9,1,8&reel_set8=9,7,8,4,9,8,7,6,9,7,8,3,9,8,7,9,11,10,3,8,9,5,8,9,5,7,2,9,3,8,5,11,11,5,3,8,9,1,4,9,2,7,9~11,11,7,4,2,10,9,7,5,9,6,7,10,3,8,9,10,11,6,10,4,9,7,6,9,3,10,2,6,4,8,10,11,9,5,2,3,5,9,8,2,9,7,6~9,10,4,2,3,8,4,9,6,5,9,1,10,4,6,10,11,9,10,5,6,7,4,6,9,10,2,3,10,6,9,11,11,9,10,3,5,10,8,2,5,7,6~11,11,10,8,2,4,3,5,7,3,5,8,6,7,2,10,7,9,11,7,9,6,7,3,4,6,9,2,8,4,5,6,11,3,2,10,5,9,10,7,2,9,7,6~9,10,3,2,8,4,9,2,6,5,1,10,9,2,10,6,11,9,3,10,6,1,9,5,3,9,2,10,4,8,10,11,11,2,10,1,4,6,2,7,9,1,8&reel_set7=4,7,8,4,9,8,7,6,5,7,8,3,9,8,7,9,11,10,3,8,9,5,8,9,5,7,2,5,3,8,5,11,11,5,3,8,5,1,4,6,2,8,9~11,11,7,4,2,10,9,7,5,3,6,7,10,3,8,6,10,11,8,10,4,8,7,6,9,3,10,2,6,4,8,10,11,8,5,2,3,5,7,8,2,9,7,6~9,10,4,2,3,8,4,9,6,5,9,1,10,8,6,10,11,9,10,5,6,7,4,6,9,10,2,8,10,6,4,11,11,8,10,3,5,10,8,2,5,7,8~11,11,10,8,2,4,8,5,7,3,5,8,6,7,2,10,7,8,11,7,8,6,7,3,8,6,9,2,8,4,5,6,11,3,2,10,5,8,10,7,2,9,8,6~9,8,3,2,8,4,9,2,6,5,1,10,4,2,10,6,11,8,3,10,6,1,9,5,3,8,2,10,4,8,10,11,11,2,10,1,4,6,2,7,9,1,8&reel_set9=11,10,4,9,10,7,6,5,7,8,3,9,10,7,9,11,10,3,8,10,5,8,9,10,7,2,5,3,8,10,11,11,5,3,8,10,1,4,6,2,7,9~11,11,7,4,2,10,9,7,5,3,6,7,10,3,8,6,10,11,6,10,4,8,7,6,9,3,10,2,6,4,8,10,11,8,5,2,3,5,7,8,2,9,7,6~9,10,4,2,3,8,4,10,6,5,9,1,10,4,6,10,11,9,10,5,6,7,4,6,9,10,2,3,10,6,4,11,11,9,10,3,5,10,8,2,5,7,6~11,11,10,8,2,4,10,5,7,3,5,10,6,7,2,10,7,9,11,10,9,6,7,3,10,6,9,2,8,4,5,10,11,3,2,10,5,4,10,7,2,9,7,6~9,10,3,2,8,4,9,2,6,5,1,10,4,2,10,6,11,9,3,10,6,1,9,5,3,9,2,10,4,8,10,11,11,2,10,1,4,6,2,7,9,1,8';
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
                $lines = 25;      
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
                $linesId[20] = [3, 1, 1, 1, 3];
                $linesId[21] = [2, 3, 1, 3, 2];
                $linesId[22] = [2, 1, 3, 1, 2];
                $linesId[23] = [1, 3, 1, 3, 1];
                $linesId[24] = [3, 1, 3, 1, 3];

                $slotEvent['slotBet'] = $slotEvent['c'];
                $slotEvent['slotLines'] = 25;
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

                // $winType = 'win';

                $allBet = $betline * $lines;
                $freeStacks = []; 
                $isGeneratedFreeStack = false;
                $bonusSymbol = 0;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $bonusSymbol = $slotSettings->GetGameData($slotSettings->slotId . 'BonusSymbol');
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusSymbol', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'MysteryScatter', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
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
                $str_initReel = '';
                $str_rsb_s = '';
                $rsb_m = 0;
                $rsb_c = -1;
                $str_mo = '';
                $str_mo_t = '';
                $bw = 0;
                $bpw = 0;
                $end = -1;
                $bgt = 0;
                $fsmore = 0;
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_initReel = $stack['initReel'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $str_rsb_s = $stack['rsb_s'];
                    $rsb_m = $stack['rsb_m'];
                    $rsb_c = $stack['rsb_c'];
                    $bw = $stack['bw'];
                    $bpw = $stack['bpw'];
                    $bgt = $stack['bgt'];
                    $fsmore = $stack['fsmore'];
                    $currentReelSet = $stack['reel_set'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $lastReel = explode(',', $stack[0]['reel']);
                    $str_initReel = $stack[0]['initReel'];
                    $str_mo = $stack[0]['mo'];
                    $str_mo_t = $stack[0]['mo_t'];
                    $str_rsb_s = $stack[0]['rsb_s'];
                    $rsb_m = $stack[0]['rsb_m'];
                    $rsb_c = $stack[0]['rsb_c'];
                    $bw = $stack[0]['bw'];
                    $bpw = $stack[0]['bpw'];
                    $bgt = $stack[0]['bgt'];
                    $fsmore = $stack[0]['fsmore'];
                    $currentReelSet = $stack[0]['reel_set'];
                }
                $reels = [];
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;
                $me_reels = [];
                $me_bonusPoses = [];
                $me_bonusChangedPoses = [];
                $me_bonusReelPoses = [0,0,0,0,0];
                $me_bonusCount = 0;
                $me_bonusWin = 0;

                for($i = 0; $i < 5; $i++){
                    $reels[$i] = [];
                    for($j = 0; $j < 3; $j++){
                        $reels[$i][$j] = $lastReel[$j * 5 + $i];
                        if($lastReel[$j * 5 + $i] == $scatter){
                            $scatterCount++;
                            $scatterPoses[] = $j * 5 + $i;   
                        }else if($me_bonusReelPoses[$i] == 0 && $lastReel[$j * 5 + $i] == $bonusSymbol){
                            $me_bonusCount++;
                            $me_bonusPoses[] = $j * 5 + $i;   
                            $me_bonusReelPoses[$i] = 1;
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
                                if($lineWinNum[$k] > 0){
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
                                if($lineWinNum[$k] > 0){
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
                if($slotEvent['slotEvent'] == 'freespin'){
                    $me_bonusWin = $slotSettings->Paytable[$bonusSymbol][$me_bonusCount] * $betline * 25;
                    if($me_bonusWin > 0){
                        for( $r = 0; $r < 5; $r++ ) 
                        {
                            $me_reels[$r] = [];
                            for( $k = 0; $k <= 2; $k++ ) 
                            {
                                if($me_bonusReelPoses[$r] == 1){
                                    $me_reels[$r][$k] = $bonusSymbol;
                                    if($reels[$r][$k] != $bonusSymbol){
                                        array_push($me_bonusChangedPoses, $k * 5 + $r);
                                    }
                                }else{
                                    $me_reels[$r][$k] = $reels[$r][$k];
                                }
                            }
                        }
                    }
                }

                if($scatterCount >= 3){
                    $muls = [0, 0, 0, 1, 20, 200];
                    $scatterWin = $muls[$scatterCount] * $betline * $lines;
                }
                $totalWin = $totalWin + $scatterWin + $me_bonusWin; 
                
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 10);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                }else if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
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
                        $spinType = 'c';
                        $isEnd = true;
                        $strOtherResponse = '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                    else
                    {
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 's';
                    }
                    
                    if($me_bonusWin > 0){
                        $lastBonusReel = [];
                        for($k = 0; $k < 3; $k++){
                            for($j = 0; $j < 5; $j++){
                                $lastBonusReel[$j + $k * 5] = $me_reels[$j][$k];
                            }
                        }
                        $strOtherResponse = $strOtherResponse . '&me='. $bonusSymbol .'~'. implode(',', $me_bonusPoses) .'~'. implode(',', $me_bonusChangedPoses) .'&mes='.implode(',', $lastBonusReel).'&psym='. $bonusSymbol .'~'.$me_bonusWin.'~' . implode(',', $me_bonusPoses);
                    }
                    $strOtherResponse = $strOtherResponse  .'&ms='. $bonusSymbol;
                    if($fsmore > 0 ){
                        $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                    if($scatterCount >= 3 ){
                        $isState = false;
                        $spinType = 'm';
                        $strOtherResponse = '&fsmul=1&mb=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fs=1&fsres=0.00&psym=1~' . $scatterWin.'~' . implode(',', $scatterPoses);
                        $slotSettings->SetGameData($slotSettings->slotId . 'MysteryScatter', 1);
                    }
                }
                if($bw == 1 && $bgt == 11){
                    $spinType = 'b';
                    $isState = false;
                    $strOtherResponse = $strOtherResponse . '&rsb_s=11,12&bgid=0&rsb_m=3&rsb_c=0&bgt=11&bw=1&bpw=' . (str_replace(',', '', $bpw) / $original_bet * $betline);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 11);
                }
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .$strWinLine .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&c='.$betline.'&sver=5&reel_set='.$currentReelSet.'&counter='. ((int)$slotEvent['counter'] + 1) .'&l=25&s='.$strLastReel.'&w='.$totalWin;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) && $bw == 0) 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"MysteryScatter":' . $slotSettings->GetGameData($slotSettings->slotId . 'MysteryScatter') . ',"BonusSymbol":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusSymbol'). ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if( ($scatterCount >= 3 || $bw == 1) && $slotEvent['slotEvent'] != 'freespin') 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                }

            }else if($slotEvent['slotEvent'] == 'doMysteryScatter'){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lastReel = $lastEvent->serverResponse->LastReel;
                $lines = 25;
                $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                $Balance = $slotSettings->GetBalance();
                $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks'); 
                $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                if($stack == null){
                    $response = 'unlogged';
                    exit( $response );
                }
                $bonusSymbol = $stack['ms'];
                $slotSettings->SetGameData($slotSettings->slotId . 'MysteryScatter', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusSymbol', $bonusSymbol);

                $response = 'fsmul=1&balance='.$Balance.'&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&ms='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusSymbol') . '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&reel_set=1&balance_bonus=0.00&na=s&fswin=0.00&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&fs=1&fsres=0.00&sver=5&counter='. ((int)$slotEvent['counter'] + 1);

                
                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------
                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"MysteryScatter":' . $slotSettings->GetGameData($slotSettings->slotId . 'MysteryScatter') . ',"BonusSymbol":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusSymbol') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $slotSettings->SaveLogReport($_GameLog, $betline * $lines, $lines, 0, $slotEvent['slotEvent'], false);
            }else if( $slotEvent['slotEvent'] == 'doBonus' ){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lines = 25;
                $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                               
                $str_lastReel = $stack['reel'];
                $lastReel = explode(',', $str_lastReel);
                $str_initReel = $stack['initReel'];
                $str_mo = $stack['mo'];
                $str_mo_t = $stack['mo_t'];
                $str_rsb_s = $stack['rsb_s'];
                $rsb_m = $stack['rsb_m'];
                $rsb_c = $stack['rsb_c'];
                $bw = $stack['bw'];
                $bpw = $stack['bpw'];
                $bgt = $stack['bgt'];
                $rw = $stack['rw'];
                $end = $stack['end'];
                $currentReelSet = $stack['reel_set'];

                
                
                $totalWin = 0;
                $coef = $betline * $lines;
                if($bpw > 0){
                    $bpw = $bpw / $original_bet * $betline;
                }
                if($rw > 0){
                    $rw = $rw / $original_bet * $betline;                        
                }
                $isState = false;
                if($end == 1){
                    $totalWin = $rw;
                    $isState = true;
                }
                $spinType = 'b';
                if( $totalWin > 0) 
                {
                    $spinType = 'cb';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                $strOtherResponse = '';
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                    {
                        $strOtherResponse = '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                    else
                    {
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        if($end == 1){
                            $spinType = 's';
                            $isState = false;
                        }
                    }
                    $strOtherResponse = $strOtherResponse  .'&ms='. $slotSettings->GetGameData($slotSettings->slotId . 'BonusSymbol');
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', explode(',', $str_lastReel));
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($str_lastReel != ''){
                    $strOtherResponse = $strOtherResponse . '&s=' . $str_lastReel;
                }
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                }
                if($str_rsb_s != ''){
                    $strOtherResponse = $strOtherResponse . '&rsb_s=' . $str_rsb_s;
                }
                if($totalWin > 0){
                    $strOtherResponse = $strOtherResponse . '&end=1&rw='. $totalWin .'&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }else{
                    $strOtherResponse = $strOtherResponse . '&end=0';
                }
                
                
                $response = 'bgid=0&rsb_m=' . $rsb_m . $strOtherResponse .'&balance='. $Balance .'&rsb_c='. $rsb_c .'&index='.$slotEvent['index'].'&balance_cash='. $Balance .'&balance_bonus=0.00&na='. $spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&bgt=11&bpw='. $bpw .'&sver=5&counter='. ((int)$slotEvent['counter'] + 1);

                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"MysteryScatter":' . $slotSettings->GetGameData($slotSettings->slotId . 'MysteryScatter') . ',"BonusSymbol":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusSymbol') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
            }
            if($slotEvent['action'] == 'doSpin' || $slotEvent['action'] == 'doBonus' || $slotEvent['action'] == 'doCollect' || $slotEvent['action'] == 'doCollectBonus' || $slotEvent['action'] == 'doMysteryScatter'){
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

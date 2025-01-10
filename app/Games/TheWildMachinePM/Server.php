<?php 
namespace VanguardLTE\Games\TheWildMachinePM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [12,4,12,7,11,13,13,8,10,9,6,5,13,13,11,11,8,8,4,13,13,6,9,12,7,3,13,13,5,6,12,12,3,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13]);
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
                    $bet = '50.00';
                }
                $spinType = 's';
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                $fsmore = 0;
                if(isset($stack)){
                    $str_initReel = $stack['initReel'];
                    $bgt = $stack['bgt'];
                    $bw = $stack['bw'];
                    $end = $stack['end'];
                    $rw = $stack['rw'];
                    $str_wins = $stack['wins'];
                    $str_status = $stack['wins_status'];
                    $str_wins_mask = $stack['wins_mask'];
                    $bgid = $stack['bgid'];
                    $lifes = $stack['lifes'];
                    $ls = $stack['ls'];
                    $wp = $stack['wp'];
                    $level = $stack['level'];
                    $str_na = $stack['na'];
                    $str_rwd = $stack['rwd'];
                    $str_sty = $stack['sty'];
                    $fsmore = $stack['fsmore'];
                    if($wp > 0){
                        $rw = $wp * $bet;
                    }
                   
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    if($bw == 1){
                        $strOtherResponse = $strOtherResponse . '&bw=' . $bw;
                    }
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($bgt > 0){
                        $strOtherResponse = $strOtherResponse . '&bgt=' . $bgt. '&coef=' . $bet;
                        $spinType = 'b';
                    }
                    if($end >= 0){
                        $strOtherResponse = $strOtherResponse . '&end=' . $end;
                        if($end == 1){
                            $spinType = 's';
                        }
                    }
                    if($wp > 0){
                        $strOtherResponse = $strOtherResponse . '&wp=' . $wp;
                    }
                    if($rw >= 0){
                        $strOtherResponse = $strOtherResponse . '&rw=' . $rw;
                    }
                    if($str_wins != ''){
                        $strOtherResponse = $strOtherResponse . '&wins=' . $str_wins;
                    }
                    if($str_status != ''){
                        $strOtherResponse = $strOtherResponse . '&status=' . $str_status;
                    }
                    if($str_wins_mask != ''){
                        $strOtherResponse = $strOtherResponse . '&wins_mask=' . $str_wins_mask;
                    }
                    if($bgid > -1){
                        $strOtherResponse = $strOtherResponse . '&bgid=' . $bgid;
                    }
                    if($lifes > -1){
                        $strOtherResponse = $strOtherResponse . '&lifes=' . $lifes;
                    }
                    if($ls > -1){
                        $strOtherResponse = $strOtherResponse . '&ls=' . $ls;
                    }
                    if($level > -1){
                        $strOtherResponse = $strOtherResponse . '&level=' . $level;
                    }
                    if($str_rwd != ''){
                        $strOtherResponse = $strOtherResponse . '&rwd=' . $str_rwd;
                    }
                    if($str_sty != ''){
                        $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                    }
                }    
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0 || $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 )
                {
                    $fs = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                        $strOtherResponse = $strOtherResponse . '&fs=' . $fs . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&fsmul=1';
                    }else if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') > 0){
                        $strOtherResponse = $strOtherResponse . '&fs_total='. ($fs - 1) .'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsend_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                    if($fsmore > 0){
                        $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                    }
                }            
                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=12,4,12,7,11,13,13,8,10,9,6,5,13,13,11,11,8,8,4,13,13,6,9,12,7,3,13,13,5,6,12,12,3,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13&balance='. $Balance . '&cfgs=2802&nas=13&ver=2&index=1&balance_cash='. $Balance . '&reel_set_size=4&def_sb=5,10,11,8,1,7,13,13&def_sa=8,3,4,3,11,3,13,13&reel_set=0&bonusInit=[{bgid:0,bgt:39,sps_wins:"1,75,150,125,250,100,1,125,100,75,150,250,1,100,75,250,150,100,1,125,250,100,150,75,1,250,125,200,100,75",sps_wins_mask:"pbf,w,w,w,w,w,pbf,w,w,w,w,w,pbf,w,w,w,w,w,pbf,w,w,w,w,w,pbf,w,w,w,w,w"}]&balance_bonus=0.00&na='. $spinType . $strOtherResponse .'&scatters=1~0,0,0,0,0,0,0~0,0,0,0,0,0,0~1,1,1,1,1,1,1&gmb=0,0,0&rt=d&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa=8,3,4,3,11,3,13,13&sb=5,10,11,8,1,7,13,13&sc='. implode(',', $slotSettings->Bet) .'&defc=50.00&sh=7&wilds=2~3500,0,500,200,25,0,0~1,1,1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&counter=2&paytable=0,0,0,0,0,0,0;0,0,0,0,0,0,0;0,0,0,0,0,0,0;3500,0,500,100,25,0,0;1000,0,250,75,10,0,0;750,0,125,50,10,0,0;600,0,100,25,5,0,0;450,0,85,18,5,0,0;375,0,75,12,5,0,0;250,0,50,8,2,0,0;250,0,50,8,2,0,0;250,0,50,8,2,0,0;250,0,50,8,2,0,0;0,0,0,0,0,0,0&l=20&reel_set0=7,7,9,9,2,11,11,8,8,4,4,10,10,7,7,6,6,12,12,3,3,9,9,7,7,10,10,5,5,11,11,7,7,8,8,10,10,4,4,12,12,7,7,9,9,6,6,8,8,11,11,7,7,10,10,2,12,12,8,8,5,5,9,9,10,10,7,7,11,11,4,4,10,10,12,12,6,6,11,11,2,12,12,5,5,9,9,11,11,6,6,10,10,3,3,12,12,8,8,9,9~4,4,10,10,6,6,1,12,12,7,7,11,11,4,4,9,9,7,7,1,11,11,5,5,12,12,8,8,2,10,10,6,6,1,11,11,4,4,9,9,8,8,3,3,12,9,6,6,1,10,10,8,8,12,12,4,4,11,11,7,7,2,9,9,6,6,11,11,1,10,10,5,5,12,12,8,8,1,9,9,6,6,10,10,4,4,11,11,7,7,1,12,12,6,6,10,10,5,5,11,11,1,6,6,9,9,2,8,8,12,12,3,3,11,11,7,7,10~3,3,6,6,12,6,7,7,9,9,1,10,10,8,8,9,9,5,5,2,11,11,8,8,12,12,6,6,9,9,1,8,10,10,4,4,9,9,5,5,10,10,7,7,11,11,8,9,12,12,1,9,9,8,8,2,10,10,5,5,12,12,7,7,11,11,1,8,8,9,9,5,5,1,12,12,4,4,10,10,8,8,9,9,2,11,11,6,6,12,12,1,7,7,10,10,3,3,9,9,8,8,11,11,5,5,12,12,1,10,10,8,8,9,9,6~7,7,12,12,7,4,1,9,9,5,5,8,8,10,10,3,3,5,6,12,12,4,4,9,9,7,7,11,11,2,10,10,5,5,12,12,1,6,6,11,11,7,7,3,3,9,9,8,8,5,5,10,10,6,6,1,9,9,5,5,12,12,7,5,3,9,9,8,8,11,11,5,5,12,12,1,7,7,10,10,4,4,7,7,2,9,9,5,5,6,6,11,11,8,8,3,3,12,12,7,7,10,10,5,5,9,9,6,6,1,12,12,7,7,4,4,9,9,7,7,2,12,12,6,6,3,3,11,11,7,8~8,8,11,11,4,4,6,6,9,9,5,5,2,10,10,7,7,4,4,9,9,6,6,11,11,3,3,7,7,10,10,4,7,6,6,11,11,2,9,9,8,8,5,5,11,11,4,7,10,10,7,7,8,8,12,12,5,5,6,6,10,10,7,7,8,8,9,9,4,4,11,11,8,8,10,10,6,6,12,12,3,3,9,9,6,6,8,8,12,12,6,6,10,10,2,11,11,7,7,12,12,4,4,10,10,6,6,11,11,5,5,9,9,8,8,6,6,10,10,4,4,7,7,11,6,12~13,13,13,13,13,13,13~13,13,13,13,13,13,13&s='.$lastReelStr.'&reel_set2=8,8,10,10,5,5,12,12,2,9,9,4,4,11,11,7,7,10,10,12,12,5,5,11,11,8,8,12,12,6,6,10,10,7,7,9,9,11,11,3,3,12,12,6,6,11,11,8,8,2,10,10,4,4,9,9,5,5,12,12,7,7,10,10,6,6,9,9,8,8,11,11,4,4,9,9~8,8,11,11,6,6,9,9,7,7,12,12,4,4,9,9,5,5,10,10,8,8,11,11,2,12,12,6,6,10,10,3,3,9,9,5,5,11,11,7,7,10,10,4,4,12,12,6,6,11,11,7,7,9,9,12,12,3,3,10,7,6,6,11,11,2,9,9,5,5,12,12,8,11,10,10,7,7,9,9~8,8,10,10,6,6,11,11,8,8,9,9,4,4,12,12,7,7,11,11,2,10,10,5,5,12,12,7,7,10,10,3,3,8,10,9,9,6,6,10,10,4,4,11,11,7,7,12,12,5,5,11,11,8,8,9,9,6,6,5,10,2,12,12,7,7,11,11,4,8,3,3,10,10,5,5,12,12,7,7,9,9~8,8,11,11,6,5,10,10,5,5,12,12,8,8,11,11,3,4,9,9,7,7,12,12,8,8,10,10,4,4,9,9,6,6,11,11,7,7,12,12,8,8,11,11,2,9,9,5,5,10,10,7,7,12,12,3,3,10,7,6,6,9,9,8,8,11,10,7,7,12,12,5,5,9,9~6,6,12,12,7,7,11,11,4,4,9,9,8,8,10,10,5,5,12,12,6,6,11,11,3,3,10,10,7,7,9,9,2,12,12,7,8,11,11,5,5,8,10,6,6,12,12,8,8,9,9,4,4,10,10,6,9,11,11,7,7,9,9,3,3,10,12,5,5,11,11,7,7,9,9~13,13,13,13,13,13,13~13,13,13,13,13,13,13&reel_set1=2,2,2,2,2,2,2~3,3,9,9,10,10,8,8,12,12,7,7,9,9,5,5,10,10,8,8,9,9,6,6,11,11,4,4,10,10,8,8,9,9,7,7,12,12,6,6,11,11,8,8,9,9,10,10,7,7,11,11,9,9,3,3,12,12,8,8,10,10,7,7,9,9,10,10,5,5,11,11,6,6,10,10,2,12,12,8,8,9,9,11,11,7,7,12,12,10,10,4,4,11,11,8,8,9,9,12,12,5,5,11,11,6,6~3,3,9,7,11,11,5,5,10,10,6,6,12,12,7,7,9,9,11,11,4,4,12,12,10,10,6,6,11,11,7,7,12,12,5,5,9,9,11,11,8,6,10,10,2,12,12,7,7,11,11,3,3,12,12,8,8,9,9,4,4,7,11,6,6,4,10,8,8,12,12,7,7,11,11,12,12,2,9,9,10,10,6,6,11,11,9,9,7,7,12,12,8,8,10,10,4,4,9,9,7,7,11,11,8,8,12,12,5~2,2,2,2,2,2,2~3,3,9,9,4,4,8,8,12,12,3,3,6,6,9,9,5,5,12,4,4,5,5,10,10,3,3,8,8,9,9,5,5,4,4,12,12,5,5,7,7,9,9,2,11,11,3,3,10,10,5,5,7,7,9,9,6,6,4,4,11,11,5,5,9,9,6,6,12,12,5,5,10,10,7,7,2,11,11,5,5,8,8,12,12,7,7,4,4,10,10,6,6,9,9,7,7,3,3,11,11,8,8,7,7,9,9,6,6,10,10,7,7,12,12,8,8,11,11,12,12~4,4,5,5,9,9,7,7,11,11,8,8,10,10,4,4,9,9,3,3,12,12,6,6,11,11,7,7,5,5,11,11,8,4,10,10,2,5,5,12,12,6,6,7,7,11,11,4,4,5,5,10,10,6,7,8,8,12,12,7,7,9,9,8,8,6,6,10,5,4,4,5,5,11,11,6,6,7,7,9,9,8,8,4,4,10,10,6,6,8,8,12,12,2,11,11,6,6,8,8,10,10,3,3,9,9,6,6,7,7,10,10,5,11,11,4,10,8,8,11,11,6,6,12,12~2,2,2,2,2,2,2&reel_set3=2,2,2,2,2,2,2~3,3,11,11,7,7,10,10,6,6,9,9,8,8,12,12,4,4,11,11,4,8,10,10,7,7,4,9,12,12,5,5,10,10,9,9,8,8,10,10,6,6,9,9,11,11,7,7,12,12,9,9,5,5,11,11,8,8,10,10,9,9,6,6,10,10,8,8,12,12,2,7,7,10,10,8,8,11,11,9,9,3,3,12,12,5,5,11,11,9,9,6~3,3,11,5,6,6,12,12,10,10,2,11,11,4,4,12,12,9,9,7,7,11,11,5,5,12,12,8,8,10,10,2,12,12,6,6,11,11,7,7,9,9,8,8,2,10,10,4,4,12,12,6,6,11,11,8,8,9,9,2,8,10,7,7,12,12,5,5,11,11,7,7,2,9,9,8,8,10,10,6,6,12,12,4,4,11,11,2,12,12,3,11,11,5,5,12,12,7,7,2,10,10,8,8,9,9,7,7~2,2,2,2,2,2,2~3,3,12,12,6,6,7,7,10,10,4,4,8,8,9,9,5,5,7,7,12,12,6,6,11,11,7,7,9,9,5,5,12,12,4,4,10,10,7,7,9,9,3,3,8,8,12,12,7,7,4,4,11,11,6,6,7,7,9,9,3,3,5,5,12,12,7,7,10,10,6,6,8,8,9,9,5,5,7,7,11,11,2,12,12,5,5,8,8,10,10,4,5,5,6,6,12,12,4,4,11,11,5,5,10,10,8,8,9,9,3,3,6,6,11,11,4,4,12,12,5,5,9,9,4,4~3,3,9,9,8,8,10,10,5,5,6,6,11,11,8,8,7,7,10,10,4,4,8,8,11,11,5,5,4,6,9,9,4,4,7,7,10,10,2,6,6,11,4,4,12,12,7,7,6,7,9,9,8,8,4,4,11,11,5,5,10,4,6,6,7,7,8,12,4,4,8,11,6,6,10,10,5,5,8,8,12,12,3,8,8,9,9,4,4,10,10,6,6,11,11,5,5,12,12,8,8,9,9,6,6,10,10,4,4,11,11,7,7,12,12,8,8,10,10~2,2,2,2,2,2,2';
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
                $linesId_00 = [];
                $linesId_00[0] = [1,1,1,1,1];
                $linesId_00[1] = [2,2,2,2,2];
                $linesId_00[2] = [3,3,3,3,3];
                $linesId_00[3] = [4,4,4,4,4];
                $linesId_00[4] = [5,5,5,5,5];
                $linesId_00[5] = [1,2,3,4,5];
                $linesId_00[6] = [2,3,4,5,4];
                $linesId_00[7] = [3,4,5,4,3];
                $linesId_00[8] = [4,5,4,3,2];
                $linesId_00[9] = [5,4,3,2,1];                
                $linesId_00[10] = [4,3,2,1,2];
                $linesId_00[11] = [3,2,1,2,3];
                $linesId_00[12] = [2,1,2,3,4];
                $linesId_00[13] = [1,2,2,2,1];
                $linesId_00[14] = [2,3,3,3,2];
                $linesId_00[15] = [2,1,1,1,2];
                $linesId_00[16] = [3,2,2,2,3];
                $linesId_00[17] = [3,4,4,4,3];
                $linesId_00[18] = [4,3,3,3,4];
                $linesId_00[19] = [4,5,5,5,4];                
                $linesId_00[20] = [5,4,4,4,5];
                $linesId_00[21] = [2,1,2,1,2];
                $linesId_00[22] = [2,3,2,3,2];
                $linesId_00[23] = [3,2,3,2,3];
                $linesId_00[24] = [3,4,3,4,3];
                $linesId_00[25] = [4,3,4,3,4];
                $linesId_00[26] = [4,5,4,5,4];
                $linesId_00[27] = [5,4,5,4,5];
                $linesId_00[28] = [1,2,1,2,1];
                $linesId_00[29] = [1,1,2,1,1];                
                $linesId_00[30] = [2,2,3,2,2];
                $linesId_00[31] = [3,3,4,3,3];
                $linesId_00[32] = [4,4,5,4,4];
                $linesId_00[33] = [5,5,4,5,5];
                $linesId_00[34] = [4,4,3,4,4];
                $linesId_00[35] = [3,3,2,3,3];
                $linesId_00[36] = [2,2,1,2,2];
                $linesId_00[37] = [1,5,1,5,1];
                $linesId_00[38] = [5,1,5,1,5];
                $linesId_00[39] = [3,1,5,1,3];

                $linesId_01 = [];
                $linesId_01[0] = [4,3,2,1,2,3,4];
                $linesId_01[1] = [4,3,2,2,2,3,4];
                $linesId_01[2] = [4,3,2,3,2,3,4];
                $linesId_01[3] = [4,3,3,2,3,3,4];
                $linesId_01[4] = [4,3,3,3,3,3,4];
                $linesId_01[5] = [4,3,3,4,3,3,4];
                $linesId_01[6] = [4,3,4,3,4,3,4];
                $linesId_01[7] = [4,3,4,4,4,3,4];
                $linesId_01[8] = [4,3,4,5,4,3,4];
                $linesId_01[9] = [4,4,3,2,3,4,4];                
                $linesId_01[10] = [4,4,3,3,3,4,4];
                $linesId_01[11] = [4,4,3,4,3,4,4];
                $linesId_01[12] = [4,4,4,3,4,4,4];
                $linesId_01[13] = [4,4,4,4,4,4,4];
                $linesId_01[14] = [4,4,4,5,4,4,4];
                $linesId_01[15] = [4,4,5,4,5,4,4];
                $linesId_01[16] = [4,4,5,5,5,4,4];
                $linesId_01[17] = [4,4,5,6,5,5,4];
                $linesId_01[18] = [4,5,4,3,4,5,4];
                $linesId_01[19] = [4,5,4,4,4,5,4];                
                $linesId_01[20] = [4,5,4,5,4,5,4];
                $linesId_01[21] = [4,5,5,4,5,5,4];
                $linesId_01[22] = [4,5,5,5,5,5,4];
                $linesId_01[23] = [4,5,5,6,5,5,4];
                $linesId_01[24] = [4,5,6,5,6,5,4];
                $linesId_01[25] = [4,5,6,6,6,5,4];
                $linesId_01[26] = [4,5,6,7,6,5,4];
                $linesId_01[27] = [4,3,3,1,3,3,4];
                $linesId_01[28] = [4,5,5,7,5,5,4];
                $linesId_01[29] = [4,3,4,1,4,3,4];                
                $linesId_01[30] = [4,5,4,7,4,5,4];
                $linesId_01[31] = [4,4,4,1,4,4,4];
                $linesId_01[32] = [4,4,4,7,4,4,4];
                $linesId_01[33] = [4,4,3,1,3,4,4];
                $linesId_01[34] = [4,4,5,7,5,4,4];
                $linesId_01[35] = [4,5,3,5,3,5,4];
                $linesId_01[36] = [4,3,5,3,5,3,4];
                $linesId_01[37] = [4,3,5,5,5,3,4];
                $linesId_01[38] = [4,4,4,2,4,4,4];
                $linesId_01[39] = [4,4,4,6,4,4,4];

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

                // $winType = 'win';

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
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.1f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '56671' . substr($roundstr, 4, 9);
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
                $bgt = 0;
                $bw = 0;
                $end = -1;
                $rw = 0;
                $str_wins = '';
                $str_status = '';
                $str_wins_mask = '';
                $bgid = -1;
                $lifes = -1;
                $ls = -1;
                $wp = -1;
                $level = -1;
                $str_rwd = '';                
                $str_sty = '';
                $str_na = '';
                $fsmore = 0;
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_initReel = $stack['initReel'];
                    $bgt = $stack['bgt'];
                    $bw = $stack['bw'];
                    $end = $stack['end'];
                    $rw = $stack['rw'];
                    $str_wins = $stack['wins'];
                    $str_status = $stack['wins_status'];
                    $str_wins_mask = $stack['wins_mask'];
                    $bgid = $stack['bgid'];
                    $lifes = $stack['lifes'];
                    $ls = $stack['ls'];
                    $wp = $stack['wp'];
                    $level = $stack['level'];
                    $str_na = $stack['na'];
                    $str_rwd = $stack['rwd'];
                    $str_sty = $stack['sty'];
                    $fsmore = $stack['fsmore'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $lastReel = explode(',', $stack[0]['reel']);
                    $str_initReel = $stack[0]['initReel'];
                    $bgt = $stack[0]['bgt'];
                    $bw = $stack[0]['bw'];
                    $end = $stack[0]['end'];
                    $rw = $stack[0]['rw'];
                    $str_wins = $stack[0]['wins'];
                    $str_status = $stack[0]['wins_status'];
                    $str_wins_mask = $stack[0]['wins_mask'];
                    $bgid = $stack[0]['bgid'];
                    $lifes = $stack[0]['lifes'];
                    $ls = $stack[0]['ls'];
                    $wp = $stack[0]['wp'];
                    $level = $stack[0]['level'];
                    $str_na = $stack[0]['na'];
                    $str_rwd = $stack[0]['rwd'];
                    $str_sty = $stack[0]['sty'];
                    $fsmore = $stack[0]['fsmore'];
                }


                $reels = [];
                for($i = 0; $i < 7; $i++){
                    $reels[$i] = [];
                    for($j = 0; $j < 7; $j++){
                        $reels[$i][$j] = $lastReel[$j * 7 + $i];
                    }
                }
                $_lineWinNumber = 1;
                $reelCount = 5;
                $linesId = $linesId_00;
                if($ls == 1){
                    $reelCount = 7;
                    $linesId = $linesId_01;
                }
                $_obf_winCount = 0;
                for( $k = 0; $k < 40; $k++ ) 
                {
                    $_lineWin = '';
                    $firstEle = $reels[0][$linesId[$k][0] - 1];
                    $lineWinNum[$k] = 1;
                    $lineWins[$k] = 0;
                    $wildWin = 0;
                    $wildWinNum = 1;
                    for($j = 1; $j < $reelCount; $j++){
                        $ele = $reels[$j][$linesId[$k][$j] - 1];
                        if($firstEle == $wild){
                            $firstEle = $ele;
                            $lineWinNum[$k] = $lineWinNum[$k] + 1;
                            if($j == $reelCount-1){
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
                                        $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 7 + $kk);
                                    }
                                }
                            }else if($j >= 2 && ($ele == $wild)){
                                $firstEle = $wild;
                                $wildWin = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                $wildWinNum = $lineWinNum[$k];
                            }
                        }else if($ele == $firstEle || $ele == $wild){
                            $lineWinNum[$k] = $lineWinNum[$k] + 1;
                            if($j == $reelCount - 1){
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
                                        $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 7 + $kk);
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
                                if($lineWins[$k] > 0){
                                    $totalWin += $lineWins[$k];
                                    $_obf_winCount++;
                                    $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                    for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                        $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 7 + $kk);
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
                for($i = 0; $i < 7; $i++){
                    if($i >= 5 && $ls == 0){
                        $reelA[$i] = 13;
                        $reelB[$i] = 13;
                    }else{
                        $reelA[$i] = mt_rand(3, 12);
                        $reelB[$i] = mt_rand(3, 12);
                    }
                }
                $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
               
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $strOtherResponse = '';
                $isState = true;
                $isEnd = false;
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $spinType = 's';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
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
                    if($fsmore > 0){
                        $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    
                    if($bw == 1){
                        $spinType = 'b';
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&bw=' . $bw;
                    }
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($bgt > 0){
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', $bgt);
                    $strOtherResponse = $strOtherResponse . '&bgt=' . $bgt. '&coef=' . $betline;
                }
                if($end >= 0){
                    $strOtherResponse = $strOtherResponse . '&end=' . $end;
                }
                if($wp >= 0){
                    $rw = $wp * $betline;
                    $strOtherResponse = $strOtherResponse . '&wp=' . $wp;
                }
                if($rw >= 0){
                    $strOtherResponse = $strOtherResponse . '&rw=' . $rw;
                }
                if($str_wins != ''){
                    $strOtherResponse = $strOtherResponse . '&wins=' . $str_wins;
                }
                if($str_status != ''){
                    $strOtherResponse = $strOtherResponse . '&status=' . $str_status;
                }
                if($str_wins_mask != ''){
                    $strOtherResponse = $strOtherResponse . '&wins_mask=' . $str_wins_mask;
                }
                if($bgid > -1){
                    $strOtherResponse = $strOtherResponse . '&bgid=' . $bgid;
                }
                if($lifes > -1){
                    $strOtherResponse = $strOtherResponse . '&lifes=' . $lifes;
                }
                if($ls > -1){
                    $strOtherResponse = $strOtherResponse . '&ls=' . $ls;
                }
                if($level > -1){
                    $strOtherResponse = $strOtherResponse . '&level=' . $level;
                }
                if($str_rwd != ''){
                    $strOtherResponse = $strOtherResponse . '&rwd=' . $str_rwd;
                }
                if($str_sty != ''){
                    $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                }
                if($isEnd == true){
                    $strOtherResponse = $strOtherResponse .'&w=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }else{
                    $strOtherResponse = $strOtherResponse .'&w=' . $totalWin;
                }

                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse . $strWinLine .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=7&c='.$betline.'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&s='.$strLastReel;
                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') .',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
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
                $ind = -1;
                if(isset($slotEvent['ind'])){
                    $ind = $slotEvent['ind'];
                }
                $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                if($slotSettings->GetGameData($slotSettings->slotId . 'Bgt') == 0){
                    $response = 'unlogged';
                    exit( $response );
                }
                if($slotSettings->GetGameData($slotSettings->slotId . 'Bgt') == 30){
                    $freeStacks = $slotSettings->GetReelStrips('bonus', $betline * $lines, $ind);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $freeStacks);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                }else{
                    $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                if($stack['reel'] != ''){
                    $lastReel = explode(',', $stack['reel']);
                }
                $str_initReel = $stack['initReel'];
                $bgt = $stack['bgt'];
                $bw = $stack['bw'];
                $end = $stack['end'];
                $rw = $stack['rw'];
                $str_wins = $stack['wins'];
                $str_status = $stack['wins_status'];
                $str_wins_mask = $stack['wins_mask'];
                $bgid = $stack['bgid'];
                $lifes = $stack['lifes'];
                $ls = $stack['ls'];
                $wp = $stack['wp'];
                $level = $stack['level'];
                $str_na = $stack['na'];
                $str_rwd = $stack['rwd'];
                $str_sty = $stack['sty'];
                $fsmore = $stack['fsmore'];
                if($wp > 0){
                    $rw = $wp * $betline;
                }
                
                $totalWin = 0;
                $isState = false;
                $spinType = 'b';
                if($rw > 0 && $end == 1){
                    $totalWin = $rw;
                }
                $strOtherResponse = '';
                $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', $bgt);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                if( $end == 1) 
                {
                    if($bgt == 39 && $str_na == 'cb'){
                        $slotSettings->SetBalance($totalWin);
                        $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                        $isState = true;
                        $spinType = 'cb';
                        $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                    }else if($bgt == 30 && $str_na == 's'){
                        $spinType = 's';
                        $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                        $freespins = [8, 5];
                        if($ind < 0){
                            $ind = 0;
                        }
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespins[$ind]);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                }

                
                if($bgt == 39){
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                
                if($bgt > 0){
                    $strOtherResponse = $strOtherResponse . '&bgt=' . $bgt. '&coef=' . $betline;
                }
                if($end >= 0){
                    $strOtherResponse = $strOtherResponse . '&end=' . $end;
                }
                if($wp >= 0){
                    $strOtherResponse = $strOtherResponse . '&wp=' . $wp;
                }
                if($rw >= 0){
                    $strOtherResponse = $strOtherResponse . '&rw=' . $rw;
                }
                if($str_wins != ''){
                    $strOtherResponse = $strOtherResponse . '&wins=' . $str_wins;
                }
                if($str_status != ''){
                    $strOtherResponse = $strOtherResponse . '&status=' . $str_status;
                }
                if($str_wins_mask != ''){
                    $strOtherResponse = $strOtherResponse . '&wins_mask=' . $str_wins_mask;
                }
                if($bgid > -1){
                    $strOtherResponse = $strOtherResponse . '&bgid=' . $bgid;
                }
                if($lifes > -1){
                    $strOtherResponse = $strOtherResponse . '&lifes=' . $lifes;
                }
                if($ls > -1){
                    $strOtherResponse = $strOtherResponse . '&ls=' . $ls;
                }
                if($level > -1){
                    $strOtherResponse = $strOtherResponse . '&level=' . $level;
                }

                $response = 'balance='. $Balance . $strOtherResponse .'&index='.$slotEvent['index'].'&balance_cash='. $Balance .'&balance_bonus=0.00&na='. $spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sver=5&counter='. ((int)$slotEvent['counter'] + 1);

                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')  . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') .',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
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

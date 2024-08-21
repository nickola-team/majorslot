<?php 
namespace VanguardLTE\Games\ColossalCashZonePM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [7,5,4,3,6,7,9,7,3,8,6,6,4,9,8]);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
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
                $wmv = 1;
                $fsmore = 0;
                $strReelSa = '7,4,6,3,3';
                $strReelSb = '3,8,4,7,3';
                if(isset($stack)){
                    $currentReelSet = $stack['reel_set'];
                    $wmv = $stack['wmv'];
                    $fsmore = $stack['fsmore'];
                    $strReelSa = $stack['sa'];
                    $strReelSb = $stack['sb'];
                }
                if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                {
                    $strOtherResponse = '&fs=' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul=1';
                    $strOtherResponse = $strOtherResponse . '&ls=0&wmt=pr2&wmv=' . $wmv;
                    if($wmv > 1){
                        $strOtherResponse = $strOtherResponse . '&gwm=' . $wmv;
                    }
                }
                if($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') > 0){
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));

                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=7,5,4,3,6,7,9,7,3,8,6,6,4,9,8&balance='. $Balance .'&cfgs=5045&ver=2&index=1&balance_cash='. $Balance .'&def_sb=3,8,4,7,3&reel_set_size=19&def_sa=7,4,6,3,3&reel_set='.$currentReelSet.$strOtherResponse.'&balance_bonus=0.00&na=s&scatters=1~250,100,50,25,15,10,5,2,1~0,0,0,0,0,0,0,0,0~1,1,1,1,1,1,1,1,1&gmb=0,0,0&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"2325581",max_rnd_win:"5000"}}&wl_i=tbm~5000&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa='.$strReelSa.'&sb='.$strReelSb.'&reel_set10=9,5,10,11,5,5,8~5,5,5,8,11,5,5,9,11,10,5,11,8,5,5,10,2~5,5,5,11,2,5,5,10,8,5,5,2,9~5,5,5,8,10,9,11,2~9,5,5,10,8,5,11&sc='. implode(',', $slotSettings->Bet) .'&defc=50.00&reel_set11=8,6,9,10,6,6,11~6,6,6,11,10,6,6,8,10,9,6,10,11,6,6,9,2~6,6,6,10,2,6,6,9,11,6,6,2,8~6,6,6,11,9,8,10,2~8,6,6,9,11,6,10&reel_set12=10,7,8,11,7,7,9~7,7,7,9,11,7,7,10,11,8,7,11,9,7,7,8,2~7,7,7,11,2,7,7,8,9,7,7,2,10~7,7,7,9,8,10,11,2~10,7,7,8,9,7,11&reel_set13=6,8,3,4,8,8,5~8,8,8,5,4,8,8,6,4,3,8,4,5,8,8,3,2~8,8,8,4,2,8,8,3,5,8,8,2,6~8,8,8,5,3,6,4,2~6,8,8,3,5,8,4&sh=3&wilds=2~500,100,25,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&reel_set18=3,11,10,4,9,5,9,11,6,10,9,8,7~4,4,4,9,9,2,10,5,5,5,11,11,6,6,7,7,8,8,1,1,1,1,4,6,6,6,10,10,6,4,3,3,3,8,4,10,2,9,10,6,6,5,4,2,2,8,8,8,10,4,5,2,10,10,10,2,2,4,6,11,11,11,4,4,10,10,1,1,5,5,9,9,9,1,1,7,7,7~4,4,4,9,9,8,8,5,5,5,11,11,2,2,7,7,8,8,1,1,1,10,10,6,6,6,10,10,1,1,3,3,3,4,4,5,5,2,2,6,6,1,1,2,2,8,8,8,2,2,11,11,10,10,10,2,2,6,6,11,11,11,4,4,10,10,6,6,5,5,9,9,9,1,1,7,7,7~4,4,4,4,2,8,8,5,5,5,1,1,2,2,1,8,2,1,1,1,1,10,10,6,6,6,4,1,1,1,3,3,3,4,4,5,5,2,2,6,1,1,1,10,9,8,8,8,2,2,11,11,10,10,10,9,4,6,6,11,11,11,2,4,2,4,6,6,1,1,9,9,9,4,8,7,7,7~9,5,11,11,3,11,4,10,7,11,6,8&counter=2&reel_set14=3,9,6,5,9,9,4~9,9,9,4,5,9,9,3,5,6,9,5,4,9,9,6,2~9,9,9,5,2,9,9,6,4,9,9,2,3~9,9,9,4,6,3,5,2~3,9,9,6,4,9,5&paytable=0,0,0,0,0;0,0,0,0,0;500,100,25,0,0;500,100,25,0,0;200,40,20,0,0;150,30,15,0,0;100,20,10,0,0;50,10,5,0,0;30,8,3,0,0;30,8,3,0,0;20,5,2,0,0;20,5,2,0,0;0,0,0,0,0&l=20&reel_set15=4,10,5,6,10,10,3~10,10,10,3,6,10,10,4,6,5,10,6,3,10,10,5,2~10,10,10,6,2,10,10,5,3,10,10,2,4~10,10,10,3,5,4,6,2~4,10,10,5,3,10,6&reel_set16=3,11,4,5,11,11,6~11,11,11,6,5,11,11,3,5,4,11,5,6,11,11,4,2~11,11,11,5,2,11,11,4,6,11,11,2,3~11,11,11,6,4,3,5,2~3,11,11,4,6,11,5&reel_set17=10,10,4,9,8,3,8,10,11,5,7,4,11,10,3,11,10,4,9,5,9,11,6~2,2,2,1,4,4,11,11,7,6,6,9,11,2,3,5,6,6,10,10,8,1,2,2,6,6,5,11,10,3,3,3,4,6,5,6,9,9,1,10,6,10,8,8,9,10,8,3,11,11,2,2,10,11,4,4,9,9,2,2,7,2,5,5~3,3,3,2,2,11,11,4,4,11,11,7,7,9,9,4,4,3,3,6,6,10,10,8,8,2,2,6,6,5,5,10,10,3,3,6,6,8,8,9,9,2,2,10,10,8,8,1,1~3,3,3,4,3,11,11,1,4,2,11,7,7,9,9,4,4,3,3,5,6,5,10,8,8,1,2,6,5,5,5,10,10,2,3,6,6,8,8,9,1,2,2,10,10,8,9,1,1~9,5,11,11,3,11,4,10,7,11,6,8&reel_set0=4,3,11,7,9,5,7,8,9,5,8,7,8,11,3,9,8,3,7,6,8,6,10,9,8,12~9,9,5,10,3,2,10,8,3,4,2,4,8,7,8,4,2,5,10,6,7,5,11,8,6,10,3,3,7,7,10,2,10,6,2,3,9,8,10,4,8,8~9,9,11,2,11,11,9,3,4,4,5,2,8,8,7,7,6,2,5,2,10,9,10,11,11,10,3,3,7,7,10,5,10,6,6,8,5,8,11,3,8,8,5,11,11,6,6,10,8,10,10~4,4,11,10,8,8,7,2,6,5,8,7,5,9,4,6,10,8,2,3,7,11,7,9,7,6,6,9,11,9,11,4,10,11,9,11,11,5,10,7,10,10,10,7,11,8,8,4,2,10,8,7,3,11,10,9,9~12,9,5,10,11,6,10,11,4,8,9,5,8,9,6,10,11,3,10,11,7&s='.$lastReelStr.'&reel_set2=10,10,6,9,8,3,8,10,11,5,7,9,11,10,3,11,10,4,5,9,9,11,6,10,9,8,8~1,1,1,8,9,1,10,11,1,10,9,10,11,8,1,1,11,10,2,2,2,11,11,1,11,3,10,8,5,9,8,9,1,10,11,1,10,2,2,2,8,1,1,11,8,3,10,4~1,1,1,1,1,1,10,11,11,8,1,9,10,9,10,1,1,9,9,2,2,2,5~1,1,1,1,1,1,8,10,11,7,1,10,8,1,9,7,9,5,2,2,2,6,10,9,3,9,10,1,8,8~11,11,3,11,9,10,7,11,6,8,4,10,11,5&reel_set1=11,11,3,10,12,10,8,4,8,7,9,10,7,8,7,6,5~4,4,4,11,11,7,6,6,9,3,3,8,8,9,6,6,6,10,10,1,4,7,2,2,6,6,5,11,2,10,3,3,3,4,6,5,2,8,9,9,6,10,6,10,8,1,1,3,1,3,11,11,11,2,2,10,11,4,4,9,9,9,8,2,7,11,5,5,5~3,3,3,2,2,11,11,4,4,4,11,11,1,1,9,9,2,2,8,12,12,6,6,6,10,10,8,8,4,2,2,6,6,5,5,2,10,10,3,3,6,6,8,8,2,9,9,2,2,10,10,8,1,1,1,3,3,2,11,11,2,2,7,7~3,3,3,4,3,11,11,4,4,4,1,2,1,1,9,9,2,2,7,12,12,5,6,10,5,1,8,8,2,4,5,6,5,5,5,2,10,10,2,3,6,6,8,8,2,9,6,2,2,10,10,11,9,4,6,3,3,9,11,10,3,4,7,7~10,10,8,6,3,11,8,5,8,10,9,11,6,8,10,5,9,6,9,11,8,9,7,9,11,3,7,10,4,7,11,10,11,12&reel_set4=10,10,4,3,11,7,8,9,10,6,9,3,10,6,10,11,9,8,9,7,9,7,6,8,11,3,11,10,4,10,8,10,8,7,9,10,8,7,7,6,5,11,9,8,6,9,4,7,5,7,5,11,11,11~3,3,3,9,9,5,2,4,4,4,11,11,1,6,10,10,9,8,8,7,7,7,6,5,6,6,6,10,10,2,4,2,2,2,6,6,8,5,10,11,8,3,3,4,5,3,7,8,8,8,10,9,5,6,10,10,10,9,8,3,4,6,11,11,11,3,4,2,2,10,11,4,4,9,9,9,8,9,7,7,7,5,5,5~3,3,3,9,9,11,11,4,4,4,11,11,6,1,10,10,9,8,8,7,7,7,10,10,6,6,6,10,10,8,8,2,2,2,6,6,5,5,2,11,9,3,3,6,6,5,7,8,8,8,9,10,2,2,10,10,10,9,11,8,3,3,11,11,11,1,3,2,2,7,7,4,4,9,9,9,1,2,7,7,7,5,5,5~3,3,3,4,9,11,11,4,4,4,4,11,1,6,9,11,9,6,2,7,7,7,10,10,6,6,6,10,8,8,8,2,2,2,3,4,5,5,10,8,11,4,11,6,6,5,9,8,8,8,10,6,2,2,10,10,10,9,4,11,3,3,11,11,11,4,1,3,4,7,7,5,6,9,9,9,3,8,7,7,7,5,5,5~4,7,11,10,11,10,10,8,6,3,11,8,5,8,10,9&reel_set3=10,10,6,10,12,10,4,10,8,6,8,10,6,8,8~9,9,9,11,12,12,5,12,12,12,11,11,7,5,11,12,12,7,7,9,11,11,9,11,12,12,5,5,3,3~5,5,9,12,12,11,11,9,11,12,12,9,11,11,12,12,11,12,12,12,11,11,9,9,11,12,12,7,7,12,12,11,3,3~5,5,5,11,5,11,11,9,11,12,12,1,9,5,3,11,11,12,12,12,5,7,9,9,11,11,11,3,5,12,12,11,3,3,5,11,7,7~9,7,10,6,10,5,8,3,10,12,8,4,8,10,7,11&reel_set6=12,11,7,11,9,5,9,11,7,9,9,5,11,7,9,9,5,9,9,5,7,11,5,11,11,11,11~6,6,6,4,6,8,6,10,10,6,4,3,4,3,4,6,8,1,10,8,10,4,4,4,4,1,6,8,8,8~11,2,6,6,6,9,4,9,6,10,9,9,8,3,3,10,6,5,4,8,4,1,3,4,4,4,6,7,1,9,3,8,4,10,9,10,8,9,10,8,8,8~2,4,4,10,8,8,10,8,6,8,11,6,6,6,10,6,4,9,4,6,8,11,9,3,3,4,8,4,4,10,4,1,4,4,4,7,10,8,8,10,8,4,6,10,11,11,8,10,8,8,8~5,11,10,3,7,11,10,7,10,10,7,8,4,11,8,3,8,12,9,6&reel_set5=12,10,4,10,8,6,8,10,6,8,8,6,10,4,8,8,6,8,8,6,4,10,6,10,4,8,8,6,10,10,10,10~10,11,11,5,9,11,11,7,7,9,9,11,9,9,9,11,11,7,3,3,3,3,11,11,7,5,11,5,11,7,7,9,9,11,9,11,3,1,7,7,7,3,5,3,5,11,9,5,5,5,5~3,3,3,3,3,11,10,9,11,9,10,7,7,10,5,11,11,3,1,11,7,7,7,2,7,3,4,11,11,10,5,5,5,10,9,5,9,3,3,7,8~2,11,5,11,8,9,11,9,7,8,9,5,3,11,3,3,3,5,7,5,9,10,11,3,11,3,10,5,10,11,3,1,5,11,7,7,7,9,5,7,5,6,7,9,5,5,5~9,7,10,6,10,5,8,3,10,11,8,4,8,10,7,11,12&reel_set8=11,3,8,9,3,3,10~3,3,3,10,9,3,3,11,9,8,3,9,10,3,3,8,2~3,3,3,9,2,3,3,8,10,3,3,2,11~3,3,3,10,8,11,9,2~11,3,3,8,10,3,9&reel_set7=3,3,9,7,9,7,6,8,2,11,3,10,12,10,8,4,8,7,9,2,8,7,7,6,5~2,2,10,10,5,5,5,11,11,12,6,6,9,5,5,5,7,12,6,6,6,10,10,4,4,4,7,7,6,6,5,5,10,10,3,3,3,6,6,5,5,8,9,9,6,6,4,4,8,8,11,11,1,1,4,4,4,10,10,10~7,7,7,11,11,10,10,9,9,7,7,8,8,8,6,6,6,10,10,8,8,1,1,7,6,6,5,5,10,10,3,3,3,6,6,8,8,9,9,9,8,8,4,4,8,8,11,11,3,3,2,2,11,10,10,7,7,4,4,4,9,9,3,3,7,7,5,5,5,4,4,12~11,11,11,6,6,11,11,10,10,9,9,7,7,7,12,4,5,5,10,10,1,8,8,8,6,6,6,5,5,5,10,10,7,7,7,6,6,6,9,9,9,8,8,8,3,3,11,12,4,4,5,5,2,2,11,9,9,7,7,5,4,6,9,3,3,3,7,7,5,5,4,4,4,9,9,9,10,10,10~4,7,11,2,11,12,10,8,6,3,11,8,5,8,10,9&reel_set9=10,4,11,8,4,4,9~4,4,4,9,8,4,4,10,8,11,4,8,9,4,4,11,2~4,4,4,8,2,4,4,11,9,4,4,2,10~4,4,4,9,11,10,8,2~10,4,4,11,9,4,8';
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '4174458' . substr($roundstr, 4, 10);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $leftFreeGames = 0;

                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []);
                }
                
                $wild = '2';
                $scatter = '12';
                $cashzone = '1';
                $Balance = $slotSettings->GetBalance();
                $totalWin = 0;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $wmv = 1;
                $fsmore = 0;
                $strReelSa = '';
                $strReelSb = '';
                
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $wmv = $stack['wmv'];
                    $strReelSa = $stack['sa'];
                    $strReelSb = $stack['sb'];
                    $fsmore = $stack['fsmore'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $lastReel = explode(',', $stack[0]['reel']);
                    $currentReelSet = $stack[0]['reel_set'];
                    $strReelSa = $stack[0]['sa'];
                    $strReelSb = $stack[0]['sb'];
                    $wmv = $stack[0]['wmv'];
                }
                $reels = [];
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;

                $cashzoneCount = 0;
                $cashzonePoses = [];
                $cashzoneWin = 0;

                for($i = 0; $i < 5; $i++){
                    $reels[$i] = [];
                    for($j = 0; $j < 3; $j++){
                        $reels[$i][$j] = $lastReel[$j * 5 + $i];
                        if($reels[$i][$j] == $scatter){
                            $scatterCount++;
                            $scatterPoses[] = $j * 5 + $i;   
                        }else if($reels[$i][$j] == $cashzone){
                            $cashzoneCount++;
                            $cashzonePoses[] = $j * 5 + $i;   
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
                                    $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline * $wmv;
                                    $totalWin += $lineWins[$k];
                                    $_obf_winCount++;
                                    $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                    for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                        $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                    }
                                }else if($j >= 2 && $ele == $wild){
                                    $wildWin = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline * $wmv;
                                    $wildWinNum = $lineWinNum[$k];
                                }
                            }else if($ele == $firstEle || $ele == $wild){
                                $lineWinNum[$k] = $lineWinNum[$k] + 1;
                                if($j == 4){
                                    $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline * $wmv;
                                    if($lineWins[$k] < $wildWin){
                                        $lineWins[$k] = $wildWin;
                                        $lineWinNum[$k] = $wildWinNum;
                                    }
                                    $totalWin += $lineWins[$k];
                                    $_obf_winCount++;
                                    $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                    for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                        $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                    }
                                }
                            }else{
                                if($slotSettings->Paytable[$firstEle][$lineWinNum[$k]] > 0){
                                    $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline * $wmv;
                                    if($lineWins[$k] < $wildWin){
                                        $lineWins[$k] = $wildWin;
                                        $lineWinNum[$k] = $wildWinNum;
                                    }
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
                

                if($cashzone > 0){
                    $muls = [0,1,2,5,10,15,25,50,100,250];
                    $cashzoneWin = $muls[$cashzoneCount] * $betline * $lines;
                    if($slotEvent['slotEvent'] == 'freespin' && $wmv > 1){
                        $cashzoneWin = $cashzoneWin * $wmv;
                    }
                    $totalWin = $totalWin + $cashzoneWin; 
                }
                
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
                    $freeNums = [0,0,0,6,8,10,12,14,16,18,20,22,0,0,0,0];
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freeNums[$scatterCount]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                }
                if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);    
                }

                $strLastReel = implode(',', $lastReel);
               
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
                    $strOtherResponse = $strOtherResponse . '&ls=0&wmt=pr2&wmv=' . $wmv;
                    if($wmv > 1){
                        $strOtherResponse = $strOtherResponse . '&gwm=' . $wmv;
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                    if($scatterCount >= 3 ){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse = $strOtherResponse  .'&ls=0&fsmul=1&fsmax='. $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fswin=0.00&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') .'&fsres=0.00';
                    }
                }
                if($cashzoneCount > 0){
                    $strOtherResponse = $strOtherResponse . '&psym=1~'. $cashzoneWin .'~' . implode(',', $cashzonePoses);
                }
                
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .$strWinLine .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&c='.$betline.'&sver=5&n_reel_set='.$currentReelSet.'&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&s='.$strLastReel.'&w='. $totalWin;
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if( $scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin') 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                }
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
    }
}

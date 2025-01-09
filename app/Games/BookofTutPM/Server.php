<?php 
namespace VanguardLTE\Games\BookofTutPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 10);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [5,8,7,9,8,8,7,3,4,4,11,6,8,11,10]);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusSymbol', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'MysteryScatter', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $str_mo = '';
                
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);
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
                    $bet = '100.00';
                }
                $currentReelSet = 0;
                $spinType = 's';
                $strOtherResponse = '';
                if($slotSettings->GetGameData($slotSettings->slotId . 'MysteryScatter') == 1){
                    $spinType = 'm';
                    $strOtherResponse = '&fsmul=1&mb=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fs=1&fsres=0.00';
                }else if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    $strOtherResponse = '&fs=' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&w=0.00&fsmul=1';
                    $currentReelSet = 3;
                }
                
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));

                $Balance = $slotSettings->GetBalance();
                $response = 'wsc=1~bg~200,20,2,0,0~10,10,10,0,0~fs~200,20,2,0,0~10,10,10,0,0&def_s=5,8,7,9,8,8,7,3,4,4,11,6,8,11,10&reel_set25=5,8,7,10,4,7,1,9,6,1,9,7,6,10,5,7,9,8,10,9,5,7,9,5,10,9,8,11,9,8,3,4,8,10,7,5,10,11,8,7,11,9,7,11,8,7,11,8,7,3,8,7,9,1,8,9,5,8,7,3,9,8,7,9,8,5,9,4,11,9,8,5,9,11,3,8,10,5,8,10,9,8,4,7,9,10,6,7,10,6,7,1,5,7,8,11,10,5,8,10,9,11~9,10,7,8,4,10,8,7,11,10,7,11,4,7,10,6,7,9,6,10,9,6,11,10,7,8,9,6,1,10,6,7,10,8,4,11,8,9,6,8,5,10,8,11,9,10,8,11,9,10,5,9,11,8,7,11,8,10,11,7,10,8,11,10,6,11,10,9,11,10,8,6,3,10,11,6,10,8,11,1,10,8,11,4,10,11,6,10,8,9,11,8,3,9,8,11,4,8,1,11,7,10~9,11,3,7,9,10,8,6,7,11,6,10,11,7,9,11,10,9,11,4,9,11,3,9,11,10,9,11,3,7,11,6,1,11,7,10,11,1,10,11,5,7,11,9,7,11,4,9,11,7,10,11,9,4,5,10,7,11,5,7,10,8,7,10,4,3,7,11,9,1,7,11,10,7,11,10,9,6,10,4,5,10,7,4,10,6,8,10,4,5,10,7,6,4,11,9,7,11,9~6,4,7,9,4,5,10,11,6,8,4,9,5,4,7,5,8,9,1,8,7,5,11,9,5,11,7,8,10,7,11,5,1,10,11,9,10,8,7,11,9,7,11,9,7,8,5,7,8,11,3,9,10,6,9,4,11,1,9,10,3,7,4,11,8,10,1,8,9,7,4,9,10,11,8,9,11,6,10,8,3,5,7,1,11,7,5,9,6,8,9,5,10,9,8,6,10~4,8,10,3,4,10,7,4,11,10,6,8,10,9,5,10,9,11,5,9,1,7,11,1,10,8,5,10,9,8,5,6,9,10,8,3,9,11,7,9,5,7,8,5,4,11,3,4,10,6,9,10,6,8,1,6,9,3,8,9,10,11,8,6,7,11,9,7,8,1,7,10,8,7,11,8,7,9,8,7,5,10,7,9,6,11,5,7,11,5,7,10,9,7,11,6,4,11&reel_set26=5,8,7,10,4,7,1,9,6,1,9,7,6,9,11,7,9,8,11,9,5,7,11,5,10,9,8,11,9,8,3,4,11,10,7,5,10,11,8,7,11,9,7,11,8,7,11,8,7,3,8,7,9,1,8,9,5,8,7,3,9,8,7,9,8,5,9,4,11,9,8,5,9,11,3,8,10,5,8,10,9,8,4,7,9,10,6,7,10,6,7,1,5,7,8,11,10,5,8,10,9,11~9,10,7,8,4,10,8,7,11,9,7,11,4,7,8,6,11,9,6,10,9,6,11,10,7,8,9,6,1,10,6,7,10,8,4,11,8,9,6,8,5,10,8,11,9,10,8,11,9,10,5,9,11,8,7,11,8,10,11,7,10,8,11,10,6,11,10,9,11,10,8,6,3,10,11,6,10,8,11,1,10,8,11,4,10,11,6,10,8,9,11,8,3,9,8,11,4,8,1,11,7,10~9,11,3,7,9,10,8,6,7,11,6,10,11,7,9,11,10,9,11,4,9,11,3,9,11,10,9,11,3,7,11,6,1,11,7,10,11,1,10,11,5,7,11,9,7,11,4,9,11,7,10,11,9,4,5,10,7,11,5,7,10,8,7,10,4,3,7,11,9,1,7,11,10,7,11,10,9,6,10,4,5,10,7,4,10,6,8,10,4,5,10,7,6,4,11,9,7,11,9~6,4,7,9,4,5,9,11,6,8,4,9,10,4,7,5,8,9,1,8,7,5,11,9,5,11,7,8,4,7,11,5,1,10,11,9,10,8,7,11,9,7,11,9,7,8,5,7,8,11,3,9,10,6,9,4,11,1,9,10,3,7,4,11,8,10,1,8,9,7,4,9,10,11,8,9,11,6,10,8,3,5,7,1,11,7,5,9,6,8,9,5,10,9,8,6,10~4,8,10,11,4,8,7,4,11,10,6,8,10,11,5,10,9,11,5,9,1,7,11,1,10,8,5,10,9,11,5,6,9,7,8,3,9,11,7,9,5,7,8,5,4,11,3,4,10,6,9,10,6,8,1,6,9,3,8,9,10,11,8,6,7,11,9,7,8,1,7,10,8,7,11,8,7,9,8,7,5,8,7,9,6,11,5,7,11,5,7,10,9,7,11,6,4,11&balance='. $Balance .'&cfgs=6129&ver=2&index=1&balance_cash='. $Balance .'&reel_set_size=27&def_sb=5,3,4,6,7&def_sa=11,12,10,8,9&reel_set='.$currentReelSet.$strOtherResponse.'&balance_bonus=0.00&na='.$spinType.'&scatters=&gmb=0,0,0&rt=d&stime=1656427039096&sa=11,12,10,8,9&sb=5,3,4,6,7&reel_set10=3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7&sc='. implode(',', $slotSettings->Bet) .'&defc=200.00&reel_set11=3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7&reel_set12=1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10&purInit_e=1&reel_set13=1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7&sh=3&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&reel_set18=5,8,7,10,4,7,1,9,6,1,9,7,6,9,5,7,9,8,7,9,5,7,9,5,10,9,8,11,9,8,3,4,8,10,7,5,10,11,8,7,11,9,7,11,8,7,11,8,7,3,8,7,9,1,8,9,5,8,7,3,9,8,7,9,8,5,9,4,11,9,8,5,9,11,3,8,10,5,8,10,9,8,4,7,9,10,6,7,10,6,7,1,5,7,8,11,10,5,8,10,9,11~9,10,7,8,4,10,8,7,11,9,7,11,3,7,8,6,7,9,6,10,9,6,11,10,7,8,9,6,1,10,6,7,10,8,4,11,8,9,6,8,5,10,8,11,9,10,8,11,9,10,5,9,11,8,7,11,8,10,11,7,10,8,11,10,6,11,10,9,11,10,8,6,3,10,11,6,10,8,11,1,10,8,11,4,10,11,6,10,8,9,11,8,3,9,8,11,4,8,1,11,7,10~9,11,3,7,9,10,8,6,7,11,3,10,11,7,9,11,10,9,11,4,9,11,3,9,11,10,9,11,3,7,11,6,1,11,7,10,11,1,10,11,5,7,11,9,7,11,4,9,11,7,10,11,9,4,5,10,7,11,5,7,10,8,7,10,4,3,7,11,9,1,7,11,10,7,11,10,9,6,10,4,5,10,7,4,10,6,8,10,4,5,10,7,6,4,11,9,7,11,9~6,4,7,9,4,5,9,11,6,8,4,9,5,4,7,3,8,9,1,8,7,5,11,9,5,11,7,8,4,7,11,5,1,10,11,9,10,8,7,11,9,7,11,9,7,8,5,7,8,11,3,9,10,6,9,4,11,1,9,10,3,7,4,11,8,10,1,8,9,7,4,9,10,11,8,9,11,6,10,8,3,5,7,1,11,7,5,9,6,8,9,5,10,9,8,6,10~4,8,10,3,4,8,7,4,11,10,3,8,10,9,5,10,9,11,5,9,1,7,11,1,10,8,5,10,9,8,5,6,9,7,8,3,9,11,7,9,5,7,8,5,4,11,3,4,10,6,9,10,6,8,1,6,9,3,8,9,10,11,8,6,7,11,9,7,8,1,7,10,8,7,11,8,7,9,8,7,5,8,7,9,6,11,5,7,11,5,7,10,9,7,11,6,4,11&reel_set19=5,8,7,10,9,7,1,9,6,1,9,7,4,9,5,7,9,4,7,9,5,7,4,5,10,9,8,11,9,8,3,4,8,10,7,5,10,11,8,7,11,9,7,11,8,7,11,8,7,3,8,7,9,1,8,9,5,8,7,3,9,8,7,9,8,5,9,4,11,9,8,5,9,11,3,8,10,5,8,10,9,8,4,7,9,10,6,7,10,6,7,1,5,7,8,11,10,5,8,10,9,11~9,10,7,8,4,10,8,7,11,9,7,11,9,7,8,6,7,9,6,10,9,6,4,10,7,8,9,6,1,10,6,7,10,8,4,11,8,9,6,8,5,10,8,11,9,10,8,11,9,10,5,9,11,8,7,11,8,10,11,7,10,8,11,10,6,11,10,9,11,10,8,6,3,10,11,6,10,8,11,1,10,8,11,4,10,11,6,10,8,9,11,8,3,9,8,11,4,8,1,11,7,10~9,11,3,7,9,10,8,6,7,11,6,10,11,7,4,11,10,9,11,4,9,11,3,9,11,10,9,11,3,7,11,6,1,11,7,10,11,1,10,11,5,7,11,9,7,11,4,9,11,7,10,11,9,4,5,10,7,11,5,7,10,8,7,10,4,3,7,11,9,1,7,11,10,7,11,10,9,6,10,4,5,10,7,4,10,6,8,10,4,5,10,7,6,4,11,9,7,11,9~6,4,7,9,4,5,9,11,6,8,4,9,5,4,7,5,8,9,1,8,7,5,11,4,5,11,7,8,4,7,11,5,1,10,11,9,10,8,7,11,9,7,11,9,7,8,5,7,8,11,3,9,10,6,9,4,11,1,9,10,3,7,4,11,8,10,1,8,9,7,4,9,10,11,8,9,11,6,10,8,3,5,7,1,11,7,5,9,6,8,9,5,10,9,8,6,10~4,8,10,3,4,8,7,9,11,10,6,8,10,9,5,10,9,11,5,9,1,7,11,1,10,8,4,10,9,8,5,6,9,7,4,3,9,11,7,9,5,7,8,5,4,11,3,4,10,6,9,10,6,8,1,6,9,3,8,9,10,11,8,6,7,11,9,7,8,1,7,10,8,7,11,8,7,9,8,7,5,8,7,9,6,11,5,7,11,5,7,10,9,7,11,6,4,11&counter=2&reel_set14=1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;5000,1000,100,10,0;2000,400,40,5,0;750,100,30,5,0;750,100,30,5,0;150,40,5,0,0;150,40,5,0,0;100,25,5,0,0;100,25,5,0,0;100,25,5,0,0&l=10&reel_set15=1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7&reel_set16=3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7&reel_set17=1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7&total_bet_max='.$slotSettings->game->rezerv.'&reel_set21=5,8,7,10,4,7,1,9,6,1,9,7,6,9,5,7,9,8,6,9,5,7,9,5,6,9,8,11,9,8,3,4,8,10,7,5,10,11,8,7,11,9,7,11,8,7,11,8,7,3,8,7,9,1,8,9,5,8,7,3,9,8,7,9,8,5,9,4,11,9,8,5,9,11,3,8,10,5,8,10,9,8,4,7,9,10,6,7,10,6,7,1,5,7,8,11,10,5,8,10,9,11~9,10,7,8,4,10,8,7,11,9,7,11,4,7,8,6,7,9,6,10,9,6,11,10,7,8,9,6,1,10,6,7,10,8,4,11,8,9,6,8,5,10,8,11,9,10,8,11,9,10,5,9,11,8,7,11,8,10,11,7,10,8,11,10,6,11,10,9,11,10,8,6,3,10,11,6,10,8,11,1,10,8,11,4,10,11,6,10,8,9,11,8,3,9,8,11,4,8,1,11,7,10~9,11,3,6,9,10,8,6,7,11,6,10,11,7,9,11,10,6,11,4,9,11,3,6,11,10,9,11,3,7,11,6,1,11,7,10,11,1,10,11,5,7,11,9,7,11,4,9,11,7,10,11,9,4,5,10,7,11,5,7,10,8,7,10,4,3,7,11,9,1,7,11,10,7,11,10,9,6,10,4,5,10,7,4,10,6,8,10,4,5,10,7,6,4,11,9,7,11,9~6,4,7,9,4,5,9,11,6,8,4,9,5,4,7,5,8,9,1,8,7,5,11,9,5,11,7,8,4,7,11,5,1,10,11,9,10,8,7,11,9,7,11,9,7,8,5,7,8,11,3,9,10,6,9,4,11,1,9,10,3,7,4,11,8,10,1,8,9,7,4,9,10,11,8,9,11,6,10,8,3,5,7,1,11,7,5,9,6,8,9,5,10,9,8,6,10~4,8,10,3,4,8,7,4,11,10,6,8,10,9,5,6,9,11,5,9,1,7,11,1,10,8,5,6,9,8,5,6,9,7,8,3,9,11,7,9,5,7,8,5,4,11,3,4,10,6,9,10,6,8,1,6,9,3,8,9,10,11,8,6,7,11,9,7,8,1,7,10,8,7,11,8,7,9,8,7,5,8,7,9,6,11,5,7,11,5,7,10,9,7,11,6,4,11&reel_set22=5,8,7,10,4,7,1,9,6,1,9,7,6,9,5,7,9,8,7,9,5,7,9,5,10,7,8,11,9,7,3,4,8,10,7,5,10,11,8,7,11,9,7,11,8,7,11,8,7,3,8,7,9,1,8,9,5,8,7,3,9,8,7,9,8,5,9,4,11,9,8,5,9,11,3,8,10,5,8,10,9,8,4,7,9,10,6,7,10,6,7,1,5,7,8,11,10,5,8,10,9,11~9,10,7,8,4,10,8,7,11,9,7,11,4,7,8,6,7,9,6,10,9,6,11,10,7,8,9,6,1,10,6,7,10,8,4,11,8,9,6,8,5,10,8,11,9,10,8,11,9,10,5,9,11,8,7,11,8,10,11,7,10,8,11,10,6,11,10,9,11,10,8,6,3,10,11,6,10,8,11,1,10,8,11,4,10,11,6,10,8,9,11,8,3,9,8,11,4,8,1,11,7,10~9,11,3,7,9,10,8,6,3,11,6,10,11,7,9,11,10,9,11,7,9,11,3,7,11,10,9,11,3,7,11,6,1,11,7,10,11,1,10,11,5,7,11,9,7,11,4,9,11,7,10,11,9,4,5,10,7,11,5,7,10,8,7,10,4,3,7,11,9,1,7,11,10,7,11,10,9,6,10,4,5,10,7,4,10,6,8,10,4,5,10,7,6,4,11,9,7,11,9~6,4,7,9,4,5,9,11,7,8,4,9,5,4,7,5,8,9,1,8,7,5,11,9,5,11,7,8,4,7,11,5,1,10,11,9,10,8,7,11,9,7,11,9,7,8,5,7,8,11,3,9,10,6,9,4,11,1,9,10,3,7,4,11,8,10,1,8,9,7,4,9,10,11,8,9,11,6,10,8,3,5,7,1,11,7,5,9,6,8,9,5,10,9,8,6,10~4,8,10,3,4,8,7,4,11,10,7,8,10,9,5,3,9,11,5,9,1,7,11,1,10,8,5,10,7,8,5,6,9,7,8,3,9,11,7,9,5,7,8,5,4,11,3,4,10,6,7,10,6,8,1,6,9,3,8,9,10,11,8,6,7,11,9,7,8,1,7,10,8,7,11,8,7,9,8,7,5,8,7,9,6,11,5,7,11,5,7,10,9,7,11,6,4,11&reel_set0=9,5,11,7,8,5,1,7,11,8,10,5,11,4,10,5,9,3,4,6,10,8,1,10,6,11,9,10,3,8~6,9,8,7,10,5,3,7,4,6,8,5,1,4,9,10,11,3,1,6,4,7,1,11,10,5,11,6,3,1~7,9,1,10,11,5,9,3,4,6,7,5,9,10,11,4,8,1,9,6,5,3,4,6,9,1,6,7,5,4~11,6,3,10,4,6,1,7,4,1,9,11,10,5,1,9,11,5,7,8,4,9,1,4,5,10,7,5,3,7~6,4,9,5,8,11,5,10,7,9,3,1,11,7,8,9,11,3,7,6,11,3,9,4,3,5,11,7,1,5&reel_set23=5,8,7,10,4,7,1,9,6,1,9,7,6,9,5,7,9,8,7,9,5,8,9,5,10,9,8,11,9,8,3,4,8,10,7,5,10,11,8,7,11,9,7,11,8,7,11,8,7,3,8,7,9,1,8,9,5,8,7,3,9,8,7,9,8,5,9,4,11,9,8,5,9,11,3,8,10,5,8,10,9,8,4,7,9,8,6,7,10,6,7,1,5,7,8,11,10,5,8,10,9,11~9,10,7,8,4,10,8,7,11,9,7,11,4,7,8,6,7,9,6,10,9,6,11,10,7,8,9,6,1,10,6,7,10,8,4,11,8,9,6,8,5,10,8,11,9,10,8,11,9,10,5,9,11,8,7,11,8,10,11,7,10,8,11,10,6,11,10,9,11,10,8,6,3,10,11,6,10,8,11,1,10,8,11,4,10,11,6,10,8,9,11,8,3,9,8,11,4,8,1,11,7,10~9,11,3,7,9,10,3,6,7,11,6,8,11,7,9,11,10,9,11,4,9,8,3,9,11,10,9,11,3,7,11,6,1,11,7,10,11,1,10,11,5,7,11,9,7,11,4,9,11,7,10,8,9,4,5,10,7,11,5,7,10,8,7,10,4,3,7,11,9,1,7,11,10,7,11,10,9,6,10,4,5,10,7,4,10,6,8,10,4,5,10,7,6,4,11,9,7,11,9,7~6,4,7,9,4,5,9,11,6,8,4,9,5,4,7,5,8,9,1,8,7,5,11,9,5,11,7,8,4,7,11,5,1,10,11,9,10,8,7,11,9,7,11,9,7,8,5,7,8,11,3,9,10,6,9,4,11,1,9,10,3,7,4,11,8,10,1,8,9,7,4,9,10,11,8,9,11,6,10,8,3,5,7,1,11,7,5,9,6,8,9,5,10,9,8,6,10~4,8,10,3,4,8,7,4,11,10,6,8,10,9,5,3,9,11,5,9,1,7,11,1,10,8,5,10,9,8,5,6,9,7,8,3,9,11,7,9,5,7,8,5,4,11,3,8,10,6,9,10,6,8,1,6,9,3,8,9,10,11,8,6,7,11,9,7,8,1,7,10,8,7,11,8,7,9,8,7,5,8,7,9,6,11,5,7,11,5,7,10,9,7,11,6,4,11&s='.$lastReelStr.'&reel_set24=5,8,7,10,4,7,1,9,6,1,9,7,6,9,5,7,9,8,7,9,5,7,9,5,10,9,8,11,9,8,3,4,8,9,7,5,10,9,8,7,11,9,7,11,8,7,11,8,7,3,8,7,9,1,8,9,5,8,7,3,9,8,7,9,8,5,9,4,11,9,8,5,9,11,3,8,10,5,8,10,9,8,4,7,9,10,6,7,10,6,7,1,5,7,8,11,10,5,8,10,9,11~9,10,7,8,4,10,8,7,11,9,7,11,4,7,8,6,7,9,6,10,9,6,11,10,7,8,9,6,1,10,6,7,9,8,4,11,8,9,6,8,5,10,8,11,9,10,8,11,9,10,5,9,11,8,7,11,8,9,11,7,10,8,11,10,6,11,10,9,11,10,8,6,3,10,11,6,10,8,11,1,10,8,11,4,10,11,6,10,8,9,11,8,3,9,8,11,4,8,1,11,7,10~9,11,3,7,9,10,8,6,7,11,6,10,11,7,9,11,10,9,11,4,9,11,3,9,11,10,9,11,3,7,11,6,1,11,7,10,11,1,10,11,5,7,11,9,7,11,4,9,11,7,10,11,9,4,5,10,7,11,5,7,10,8,9,10,4,3,7,11,9,1,7,11,10,7,11,10,9,6,10,4,5,10,7,4,10,6,8,10,4,5,10,7,6,4,11,9,7,11,9~6,4,7,9,4,5,9,11,6,8,4,9,5,4,7,5,8,9,1,8,7,5,11,9,5,11,7,8,4,7,11,5,1,10,11,9,10,8,7,11,9,7,11,9,7,8,5,7,8,11,3,9,10,6,9,4,11,1,9,10,3,7,4,11,8,10,1,8,9,7,4,9,10,11,8,9,11,6,10,8,3,5,7,1,11,7,5,9,6,8,9,5,10,9,8,6,10~4,8,10,3,9,8,7,4,11,9,6,8,10,9,5,10,9,11,5,9,1,7,11,1,10,8,5,10,9,8,5,6,9,7,8,3,9,11,7,9,5,7,8,5,4,11,3,4,10,6,9,10,6,8,1,6,9,3,8,9,10,11,9,6,7,11,9,7,8,1,7,10,8,7,11,8,7,9,8,7,5,8,7,9,6,11,5,7,11,5,7,10,9,7,11,6,4,11&reel_set2=1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10&reel_set1=10,5,7,10,8,9,1,7,8,11,10,9,6,4,10,11,1,4,3,9,4,1,6,8,10,11,7,1,3,8~6,9,5,1,10,8,3,7,6,4,8,9,3,4,8,3,11,9,8,6,11,3,1,7,3,5,7,10,3,7~3,9,6,10,11,6,1,3,10,4,7,6,8,10,7,3,8,11,5,6,10,3,4,5,9,1,10,7,5,1~5,6,7,10,4,3,6,7,11,10,9,11,3,5,8,4,11,1,3,8,5,9,1,7,11,10,9,11,3,1~9,4,1,7,8,9,6,10,1,5,3,8,5,7,6,1,11,5,3,6,11,3,9,6,10,5,3,4,1,11&reel_set4=1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7&reel_set3=1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10&reel_set20=5,8,7,5,4,7,1,9,6,1,9,7,6,9,5,7,9,8,7,9,5,7,9,5,10,9,8,11,9,8,3,4,8,10,7,5,10,11,8,7,11,9,7,11,8,7,11,8,7,3,8,7,9,1,8,9,5,8,7,3,9,8,7,9,8,5,9,4,11,9,8,5,9,11,3,8,10,5,8,10,9,8,4,7,9,10,6,7,10,6,7,1,5,7,8,11,10,5,8,10,9,11~9,10,7,8,4,10,8,7,5,9,7,11,4,7,8,5,7,9,6,10,9,6,11,10,7,8,9,6,1,10,6,7,10,8,4,11,8,9,6,8,5,10,8,11,9,10,8,11,9,10,5,9,11,8,7,11,8,10,11,7,10,8,11,10,6,11,10,9,11,10,8,6,3,10,11,6,10,8,11,1,10,8,11,4,10,11,6,10,8,9,11,8,3,9,8,11,4,8,1,11,7,10~9,11,3,7,9,10,8,6,7,9,5,10,11,7,9,11,10,9,11,4,9,11,5,9,11,10,9,5,3,7,11,6,1,11,7,10,11,1,10,11,5,7,11,9,7,11,4,9,11,7,10,11,9,4,5,10,7,11,5,7,10,8,7,10,4,3,7,11,9,1,7,11,10,7,11,10,9,6,10,4,5,10,7,4,10,6,8,10,4,5,10,7,6,4,11,9,7,11,9,7~6,4,7,9,4,5,9,11,6,8,4,9,5,4,7,5,8,9,1,8,7,5,11,9,5,11,7,8,4,7,11,5,1,10,11,9,10,8,7,11,9,7,11,9,7,8,5,7,8,11,3,9,10,6,9,4,11,1,9,10,3,7,4,11,8,10,1,8,9,7,4,9,10,11,8,9,11,6,10,8,3,5,7,1,11,7,5,9,6,8,9,5,10,9,8,6,10~4,8,10,3,4,5,7,4,11,5,6,8,10,9,5,10,9,11,5,9,1,7,11,1,10,8,5,10,9,8,5,6,9,7,8,3,9,11,7,9,5,7,8,5,4,11,3,4,10,6,9,10,6,8,1,6,9,3,8,9,10,11,8,6,7,11,9,7,8,1,7,10,8,7,11,8,7,9,8,7,5,8,7,9,6,11,5,7,11,5,7,10,9,7,11,6,4,11&purInit=[{type:"fs",bet:1000,fs_count:10}]&reel_set6=1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7&reel_set5=1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10&reel_set8=3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10&reel_set7=1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7&reel_set9=3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7~3,7,9,11,10,4,8,10,7,11,5,8,9,6,7,11,8,10,9,7,11,8,9,10~1,5,7,1,8,6,1,9,3,1,9,11,1,4,10,1,11,8,1,10,7,1,8,10,1,7&total_bet_min=20.00';
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
                $lines = 10;      
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
                $pur = -1;
                if(isset($slotEvent['pur'])){
                    $pur = $slotEvent['pur'];
                }
                $slotEvent['slotBet'] = $slotEvent['c'];
                $slotEvent['slotLines'] = 10;
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
                if($pur >= 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $betline * $slotSettings->GetPurMul($pur);
                }
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusSymbol', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'MysteryScatter', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.1f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '6074458' . substr($roundstr, 4, 10);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $leftFreeGames = 0;

                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []);
                }
                
                $wild = '1';
                $scatter = '1';
                $Balance = $slotSettings->GetBalance();
                
                $totalWin = 0;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame')];
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $pur, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $lastReel = explode(',', $stack[0]['reel']);
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
                if($slotEvent['slotEvent'] == 'freespin'){
                    $me_bonusWin = $slotSettings->Paytable[$bonusSymbol][$me_bonusCount] * $betline * 10;
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
                    $muls = [0, 0, 0, 2, 20, 200];
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
                if( $scatterCount >= 3 ) 
                {
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0 ) 
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 10);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                    }
                    else
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 10);
                    }
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
                    $_bonusMuls = [1, 2, 3, 10];
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
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                    }
                    if($scatterCount >= 3 ){
                        $strOtherResponse = $strOtherResponse . '&fsmore=10';
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
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $stack);
                        $slotSettings->SetGameData($slotSettings->slotId . 'MysteryScatter', 1);
                        
                        if($pur >= 0){
                            $strOtherResponse = $strOtherResponse . '&purtr=1&puri=' . $pur;
                        }
                    }
                }
                
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .$strWinLine .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&c='.$betline.'&sver=5&reel_set='.$currentReelSet.'&counter='. ((int)$slotEvent['counter'] + 1) .'&l=10&s='.$strLastReel.'&w='.$totalWin;
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"MysteryScatter":' . $slotSettings->GetGameData($slotSettings->slotId . 'MysteryScatter') . ',"BonusSymbol":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusSymbol') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($slotEvent['slotEvent'] == 'freespin' && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * 100;
                }
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if( $scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin') 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                }

            }else if($slotEvent['slotEvent'] == 'doMysteryScatter'){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lastReel = $lastEvent->serverResponse->LastReel;
                $lines = 10;
                $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                $Balance = $slotSettings->GetBalance();
                $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks'); 
                $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame')];
                if($stack == null){
                    $response = 'unlogged';
                    exit( $response );
                }
                $bonusSymbol = $stack['ms'];
                $slotSettings->SetGameData($slotSettings->slotId . 'MysteryScatter', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusSymbol', $bonusSymbol);

                $response = 'fsmul=1&balance='.$Balance.'&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&ms='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusSymbol') . '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&reel_set=0&balance_bonus=0.00&na=s&fswin=0.00&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&fs=1&fsres=0.00&sver=5&counter='. ((int)$slotEvent['counter'] + 1);
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                    $response = $response .'&purtr=1&puri=0';
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"MysteryScatter":' . $slotSettings->GetGameData($slotSettings->slotId . 'MysteryScatter') . ',"BonusSymbol":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusSymbol') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $slotSettings->SaveLogReport($_GameLog, $betline * $lines, $lines, 0, $slotEvent['slotEvent'], false);
            }
            if($slotEvent['action'] == 'doSpin' || $slotEvent['action'] == 'doCollect' || $slotEvent['action'] == 'doCollectBonus' || $slotEvent['action'] == 'doMysteryScatter'){
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

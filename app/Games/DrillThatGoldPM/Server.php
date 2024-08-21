<?php 
namespace VanguardLTE\Games\DrillThatGoldPM
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
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [4,8,3,3,7,3,10,12,6,10,6,13,1,5,13]);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Bl', 0);
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
                    if (isset($lastEvent->serverResponse->Bl)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'Bl', $lastEvent->serverResponse->Bl); //ReplayLog
                    }
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
                if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                {
                    $strOtherResponse = '&fs=' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&w=0.00&fsmul=1';
                    if($lastEvent->serverResponse->Stf != ''){
                        $strOtherResponse = $strOtherResponse . '&stf=' . $lastEvent->serverResponse->Stf;
                    }
                    $currentReelSet = 1;
                }
                
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));

                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=7,13,10,6,10,5,11,8,5,13,6,13,5,8,11&reel_set25=13,6,11,12,7~9,8,10~9,8,5,10~6,11,12,7~12,11,7,6,5,7,11,10,6,9,13,12,9,8,7,10,12,5,13,9,11,8,5,11,13,12,9,13,10&reel_set26=6,11,7,13,11,12,6,12~9,8,5,10~4,10,9,4,9,10,9,10,8,10,9,8,10,8,9,4,4,4,10,8,5,8,9,5,4,10,9,10,5,10,5,9,5,10~11,12,6,4,12,7,6,12,4,4,4,11,12,11,12,4,11,7,11,6,11~9,12,10,7,11,12,5,9,8,6,5,11,13,7,12,8,10,13,5,7,9,12,13,10&reel_set27=7,12,11,6,12,11,6,13~9,8,5,10~9,8,10~6,11,12,7~11,13,5,9,12,10,9,11,7,6,11,9,5,12,10,8,13,6,10,12,7,10,11,12,6,12,13,9,10,7,12,5,8,7,11,13,8&balance='. $Balance .'&cfgs=5288&ver=3&index=1&balance_cash='. $Balance .'&def_sb=7,12,7,3,11&reel_set_size=28&def_sa=1,10,9,1,10&reel_set='.$currentReelSet.$strOtherResponse.'&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"4366812",max_rnd_win:"5000",max_rnd_win_a:"4000",max_rnd_hr_a:"4366812"}}&wl_i=tbm~5000;tbm_a~4000&bl='. $slotSettings->GetGameData($slotSettings->slotId . 'Bl') .'&stime=1657294576395&sa=1,10,9,1,10&sb=7,12,7,3,11&reel_set10=13,12,8,11,10,11,5,6,7,10,8,11,9,12,5,8,10,13,12,9,13,7,6,11,13,12,10,7,12,13,12~4,4,10,4,6,12,4,4,12,13,4,4,10,9,4,4,4,11,10,9,1,13,4,7,11,12,4,4,13,11,4,9,4,10~9,11,12,7,11,6,8,13,10,12,8,9,13,12,13,7,12,11,8,6,11,12,5,13,3,13,10,5,10~10,6,7,11,12,11,13,9,13,6,9,10,8,11,12,13,7,12,3,11,8,5,9,10,8,10,11,12,7,5,12,6~12,7,5,8,13,10,8,12,11,10,6,10,13,9,12,9,7,8,13,11,12,6,7,11,13&sc='. implode(',', $slotSettings->Bet) .'&defc=50.00&reel_set11=11,8,12,11,12,6,13,8,10,6,10,13,9,12,11,13,9,8,7,13,12,10,8,10,11,12,5,11,7,10,9,7,8,11,12,6,13,7,13,5~12,9,6,12,8,11,3,13,10,12,10,13,8,12,13,8,10,6,10,9,8,13,6,11,12,10,7,13,11,13,11,6,5,9,5,12,9,11,12,10,13,12,7,5,11,12,6,7,5,9,7,9,13,11,12,10,9,10,7,9,13,11,8,6,7,5~13,7,4,9,10,4,6,13,10,4,4,12,4,4,4,10,4,12,4,4,11,4,4,11,12,9,11,5,10,4,4~6,12,11,7,6,7,10,12,10,12,13,9,12,9,13,5,9,8,13,7,3,11,13,5,13,8,5,10,13,11,8,10,6,11~13,12,7,12,11,5,11,13,6,12,13,12,9,8,9,7,11,8,10,7,10,13,10,8,6&reel_set12=9,5,8,13,12,10,9,10,13,7,11,10,7,13,6,12,11,12,8,7,5,11,12,10,11,8,13,8,7,6~6,13,10,13,9,7,8,12,5,6,9,3,6,8,13,10,5,10,12,8,11,13,5,13,9,10,9,7,6,13,7,10,13,12,11,12,11,8,10,7,9,12,6,9,12,11,8,7,8,12,7,11,10,5,10,13,11,9,12~10,13,10,4,13,4,4,11,9,4,4,4,12,9,12,4,4,7,4,6,11,4,5,10~12,13,7,10,12,13,9,6,11,12,7,3,7,13,11,12,5,13,5,6,9,11,8,10,9,8,9,10,11,8,12,13,5,13,10~4,4,12,11,4,4,4,5,4,10,11,9,6,13,4,10,7&reel_set13=10,9,13,8,6,13,12,8,7,10,7,12,13,10,13,6,11,10,11,8,7,13,12,7,9,8,13,8,13,12,9,7,11,5,12,11,5,12,11,10,6~8,7,10,9,12,6,13,5,9,7,10,11,8,10,11,3,12,13,12,13,9,13,11,13,6,11,12,10,13,6,11,10,12,6,9,12,7,5,8,13,5,8,10,6,8,11,13,11,9,8,12,11,7,12,7,10,13,11,9,5,9,11,10~13,7,6,12,8,11,13,11,5,10,11,8,12,7,6,7,12,10,5,10,9,12,6,13,9,10,9,13,11,12,8,7,11,3,13,12,8~10,4,13,4,12,4,13,10,4,5,4,9,4,4,4,11,4,10,4,4,12,9,12,4,4,11,6,7~7,11,12,4,10,13,4,4,4,10,9,6,4,4,5,4,4,12,4&sh=3&wilds=2~250,100,50,0,0~1,1,1,1,1;3~250,100,50,0,0~1,1,1,1,1&bonuses=0&st=rect&c='.$bet.'&sw=5&sver=5&bls=20,25&reel_set18=10,13,10,11,7,11,13,12,7,12,7,11,8,10,9,8,12,13,6,13,12,10,6,8,5,6,7,9,11,12,13,8,5,11,13,7,12~4,4,10,13,4,9,4,7,4,13,4,12,10,4,4,4,9,4,4,11,4,10,4,4,12,11,4,4,12,4,11~5,13,9,6,13,10,12,8,12,13,12,13,12,11,9,10,13,6,7,13,9,6,12,10,5,11,8,7,11,7,12,10,11,8,11,8~12,10,4,11,4,4,9,4,4,10,11,4,4,11,4,4,10,4,10,4,4,4,9,4,4,13,4,4,12,11,4,13,4,4,12,4,4,13,4,10,4,4,12,4,4~9,4,4,12,4,4,12,4,13,4,4,10,4,4,9,4,4,4,10,4,12,10,4,4,13,4,13,4,11,4,7,4,4,11,4,4&reel_set19=13,9,6,12,7,9,11,13,11,12,11,13,10,8,13,6,10,12,11,12,10,5,8,13,7,12,7,8~12,4,4,13,11,4,4,10,4,4,9,4,4,4,7,4,12,4,13,4,4,10,4,4,10,11~13,4,10,4,4,7,4,12,4,4,12,4,9,10,4,4,10,4,4,12,4,4,4,10,4,11,13,4,4,11,4,11,4,10,4,4,12,4,4,11,4,4,13,4~12,4,4,13,4,4,4,10,9,4,11,4,4,13,11,4,4,4,10,4,4,4,12,4,9,4,12,4,10,13,12,10,4,4,4,11,4~12,4,10,4,4,11,4,12,4,4,10,4,10,4,4,9,4,4,4,12,4,10,4,4,13,4,11,4,7,4,4,9,4,4,13,11,4&counter=2&reel_set14=8,5,12,7,9,7,12,13,11,13,12,13,6,10,8,11,10,11,7,13,8,13,12,9,12,8,13,12,10,13,8,11,12,11,7,10,7,12,5,6,11,10,13~13,6,5,12,6,9,13,10,8,12,11,9,6,12,8,13,9,12,10,13,8,11,13,9,11,10,3,10,8,11,13,7,13,11,8,9,7,10,7,6,12,7,13,9,7,13,6,11,5,12,11,8,9,12,6,13,10,5,13,11,7,10,12,9,5,7,9,10,12,8,7,5,11,13,12,6,13,9,10,12,11~13,12,7,4,12,4,12,4,11,4,5,4,10,12,8,4,4,4,11,6,11,10,9,10,12,4,4,9,7,9,8,10,4~11,4,4,8,4,6,4,10,9,7,10,9,7,12,10,13,4,4,4,11,9,12,13,4,11,4,13,11,12,4,11,12,4,9,8,4,12,5~10,12,7,13,7,13,5,13,11,7,8,12,10,13,10,12,8,12,11,9,7,5,13,6,9,8,12,11,8,6,11,12&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;250,100,50,0,0;140,80,40,0,0;120,60,30,0,0;100,50,25,0,0;80,40,20,0,0;50,25,10,0,0;40,20,10,0,0;30,20,10,0,0;25,10,5,0,0;20,10,5,0,0&l=20&reel_set15=8,1,12,10,13,7,13,9,10,12,8,12,8,13,7,10,13,12,4,11,13,4,4,4,13,10,8,5,8,6,12,11,12,6,4,11,9,13,12,11,12,7,13,10,9,6,5,7,11~12,9,1,10,11,13,4,11,5,8,5,13,10,12,11,8,9,13,11,13,8,4,4,4,6,11,9,6,12,4,7,13,10,12,13,4,10,5,10,7,9,6,10,9,12,8,6,7~12,13,10,8,13,5,12,11,8,4,5,13,11,10,12,7,13,4,4,4,13,8,13,11,4,12,9,7,6,11,1,8,6,12,10,12,7,10,7~13,5,8,13,8,4,13,9,4,6,10,13,5,11,7,12,10,13,9,4,4,4,11,9,12,10,12,1,13,7,10,5,12,11,8,7,10,6,4,6,11,9,12~7,11,12,7,6,4,13,11,12,7,5,11,5,4,4,4,6,10,13,11,8,13,8,10,1,9,12,10,9,12,8,4,13&reel_set16=11,7,13,7,8,7,11,13,10,12,13,7,1,9,13,8,10,5,6,8,11,13,10,12,7,9,5,8,11,6,12,6,10,12,13,8,12,9~7,11,7,12,11,9,11,7,3,12,8,13,9,7,9,7,10,7,10,12,10,13,12,13,9,10,6,11,6,9,13,6,11,10,11,10,13,8,11,13,8,5,1,9,8,13,6,5,11,5,10,11,10,12,13,11,12,1,6,8,6,9,10,13,5,12,9,13,12,13,10,7,10,13,8,5,12~11,8,7,1,10,7,6,13,12,9,13,12,8,11,10,7,11,13,8,5,13,12,9,12,13,10,12,8,13,6,9,12,8,10,12,10,5,11,12,11,5,12,6,12,13,7,11,9,7,10,12,13,6,10,3,7,8,13,1,13,7,11~8,5,6,1,13,11,12,3,12,7,10,5,12,7,9,13,9,13,11,8,12,9,8,6,10,13,5,11,10,7,9,12,13,11,7,6,10,6,13,10,11~11,9,8,10,11,10,5,1,12,8,13,6,11,7,8,9,8,10,11,9,12,7,8,12,11,13,7,12,13,6,1,13,12,7,12,13,12,13,6,7,10,13&reel_set17=10,4,4,7,4,11,4,9,4,4,13,4,10,4,4,10,4,4,4,11,4,4,12,4,13,11,4,11,4,4,12,4,4,10,12,4,4,12,4,9,4~4,4,11,4,11,4,13,4,12,4,10,4,7,12,4,10,4,4,4,11,4,4,10,4,4,12,10,4,4,9,4,4,13,4,4,9,13~11,4,4,9,4,4,11,4,13,4,12,4,4,4,12,4,10,4,4,10,12,4,11,4,13,4,9,4,4~4,4,9,4,4,13,4,10,4,4,10,4,4,4,12,10,4,13,4,4,11,4,4,12,4,11~4,4,4,10,4,9,12,4,4,11,4,4,13,4,13,4,9,4,13,4,11,10,4,4,4,12,4,11,12,4,4,4,7,12,4,4,4,10,4,4,10,11,4,10,4,4&reel_set21=8,11,12,10,12,13,11,7,10,9,6,13,10,13,5,12,8,13,9,12,11,5,12,7,6,7,8~5,12,6,7,8,12,13,9,8,10,13,6,7,11,9,12,10,5,9,11,13,6,11,8,11,13,10~13,4,7,4,4,11,4,4,10,4,10,4,4,13,4,4,4,9,4,4,11,12,4,12,4,4,9,4,4,12,11,4,4,10,4~4,10,4,4,4,10,11,12,11,10,4,11,4,13,10,4,4,4,13,4,4,4,13,11,4,4,4,9,10,4,4,12,4,4,12,4,12,4,4,9,4,4,4~4,11,4,12,4,4,10,4,4,10,4,4,12,4,12,10,4,11,4,13,4,4,4,12,4,4,9,4,11,4,4,13,4,4,13,4,7,4,4,11,10,4,9,10&reel_set22=10,5,9,5,8,9,10,9,8,10,9,8,4,4,4,5,10,9,10,5,9,10,5,4,8,10,4,10~13,6,11,12,7~9,8,5,10~11,12,11,7,11,7,6,12,4,4,4,11,4,12,4,11,6,7,12,4,6,12~7,13,11,13,11,6,9,10,12,7,9,8,10,12,13,10,5,12,6,11,10,12,5,8,13,9,11,7,12,5&reel_set0=11,4,13,4,13,9,5,8,4,4,7,12,4,7,11,7,13,11,13,8,4,4,4,9,12,9,13,10,8,13,4,12,11,1,13,6,4,11,4,4,5,10,12,13,11,4~12,4,5,13,8,12,5,13,10,8,6,13,4,12,4,8,7,10,12,9,13,8,1,4,13,10,11,9,5,7,4,10,13,11,5,4,11,12,7,13,9,13,10,9,11,13,11,12,5,4,8,4,12,5,13,11,13,11,10,12,4,13,12,13,4,4,10,7,4,4,12,4,9,5,4,7,8,9,11,4,12,4,4,11,13,4,4,4,6,7,11,13,10,4,9,13,8,11,9,13,7,4,4,13,10,4,12,4,7,13,11,4,4,10,7,13,10,4,4,9,10,8,13,1,4,13,12,7,8,12,8,13,11,7,4,11,10,13,11,9,13,5,12,7,13,10,12,4,11,13,9,13,4,13,4,12,3,13,11,10,12,5,11,13,5,4,4,3,11,9,11,10,13,4,9~4,5,9,12,8,12,5,10,4,4,8,4,5,9,12,11,9,4,9,5,13,9,7,13,4,6,13,12,10,4,7,11,7,12,8,11,8,4,1,12,11,4,10,7,4,10,4,4,11,7,4,4,4,11,9,7,13,10,5,6,4,8,3,9,12,13,11,6,4,9,1,12,13,11,12,4,13,8,3,7,4,12,4,12,11,8,4,9,5,7,11,8,5,11,10,7,11,13,7,6~7,10,9,8,11,12,7,12,4,7,8,10,5,11,4,4,4,1,5,11,6,11,4,4,13,8,13,9,12,6,5,4,3,13,6~6,4,12,4,5,9,7,11,12,10,9,4,5,7,5,11,13,4,13,4,4,4,12,13,6,10,11,1,13,11,5,4,12,7,9,10,12,7,11,4,4,10,9,5&reel_set23=9,8,10~13,6,11,12,7~9,8,5,10~6,11,12,7~10,8,13,11,13,7,9,5,13,6,11,7,9,12,8,12,9,12,10,5,6,12&s='.$lastReelStr.'&reel_set24=13,6,11,12,7~5,10,9,4,10,9,8,10,9,5,4,8,4,4,4,10,8,9,10,5,10,9,4,9,5,9,5~9,8,5,10~7,12,6,11,12,4,11,4,4,4,7,12,6,7,4,11,12,11,6,11~8,10,12,6,8,10,12,11,5,11,12,5,9,12,6,10,12,5,13,7,13,7,11,12,9,8,11,6,7,10,13,9,11,9,13,10,7&reel_set2=9,10,13,12,7,4,9,7,5,12,10,9,8,9,7,12,8,6,9,8,6,12,5,13,4,11,1,4,4,5,10,6,11,12~7,5,12,10,12,7,13,12,8,1,9,5,4,9,4,12,5,12,9,7,10,5,6,12,9,5,12,6,12,13,10,11,12,7,8,11,4,3,7,8,9,12,4,5,13,7,4,12,10,8,9,4,7,12,4,10,11,12,9,6,8,9,11,7,9,4,6,13,8,1,9~5,8,12,7,12,10,12,11,5,3,9,10,12,13,5,4,9,8,7,9,10,9,10,6,7,12,9,7,12,9,8,11,9,10,9,7,13,12,6,4,8,5,9,7,13,6,9,7,11,12,4,6,5,4,11,12,7,1,6,12,4,1,4,11~12,4,6,9,12,5,8,10,4,6,5,6,13,1,9,13,8,9,10,9,10,4,12,7,12,11,7,8,9,11,12,7,12,3,8,7,5,9,4,10,8,1,10,5,13,11,5,9,12,8,7,4,6,8,10,7,9,4,12,11,12,13,11,10,6,9,7,4,7,12,9,10,8,11,9~4,4,8,9,11,9,12,7,9,12,4,6,13,6,7,9,5,11,12,10,13,1,10,8,11,7,12,5,8,9&reel_set1=9,8,10,11,6,5,11,8,9,6,5,12,11,6,8,10,8,12,10,6,10,6,9,7,5,6,10,13,7,8,10,5,13,6,9,11,10,5,7,11,10,11,13,11,7,9,1,11,6,10~11,6,1,6,8,10,11,5,13,6,13,8,6,8,13,11,6,8,9,13,6,13,11,12,7,5,12,10,8,5,10,6,9,10,11,10,7,8,10,12,11,8,12,11,9,7,6,7,11,5,6,11,9,12,11,10,9,7,11,10,7,9,11,6,9,10,11,10,5,11,9,7,12,10,11,8,10,11,8,11,6,9,11,13,5,7,8,6,10,1,10,5,10,8,3,6,5,11,3,10~10,8,6,11,12,11,5,11,10,9,5,9,13,11,10,11,7,11,8,9,12,11,9,10,9,1,8,5,8,10,6,9,6,10,11,13,6,7,10,7,10,3,6,10,6,7,6,11,13,5,7,11,1,13,8,7,8,10,9,7,5,8,11,6,10,11,12,5~5,9,10,8,7,5,7,11,7,6,11,8,5,12,5,12,7,9,10,9,1,10,5,11,7,13,9,6,11,10,13,10,11,3,13,12,8,9,10,6,8,6,10,7,6,8,10,13,8,10,12,11,6,1,7,10,7,11,13,11,10,12,11,9,10~6,13,10,8,10,8,9,13,11,9,5,12,11,7,10,1,6,11,5,12,11,9,7,10,6,8,12&reel_set4=9,8,10~13,6,11,12,7~9,8,5,10~6,11,12,7~10,8,13,11,13,7,9,5,13,6,11,7,9,12,8,12,9,12,10,5,6,12&reel_set3=10,5,9,5,8,9,10,9,8,10,9,8,4,4,4,5,10,9,10,5,9,10,5,4,8,10,4,10~13,6,11,12,7~9,8,5,10~11,12,11,7,11,7,6,12,4,4,4,11,4,12,4,11,6,7,12,4,6,12~7,13,11,13,11,6,9,10,12,7,9,8,10,12,13,10,5,12,6,11,10,12,5,8,13,9,11,7,12,5&reel_set20=4,4,9,4,12,4,4,10,13,4,4,7,4,4,4,11,10,4,4,6,10,4,4,11,4,13,4,12~12,9,12,7,6,5,11,10,13,9,8,9,8,6,8,13,11,6,13,7,9,10,12,10,12,11,7,10,11,13,12,10,13,12,6,13,11,9,8,13,10,5~11,4,4,10,4,4,10,4,12,4,12,4,9,4,4,11,4,4,12,4,4,11,4,4,4,13,4,7,9,4,10,4,4,11,4,13,4,13,4,4,12,4,4,10,4,4,10~4,4,11,4,4,9,4,11,4,13,12,4,4,4,11,4,10,13,9,4,12,13,4,4,10,4,4,4~10,4,4,4,13,12,4,13,11,4,4,9,10,4,4,11,9,4,4,4,11,12,4,4,4,7,11,4,4,4,10,13,12,4,10,12,4,10&reel_set6=13,6,11,12,7~9,8,10~9,8,5,10~6,11,12,7~12,11,7,6,5,7,11,10,6,9,13,12,9,8,7,10,12,5,13,9,11,8,5,11,13,12,9,13,10&reel_set5=13,6,11,12,7~5,10,9,4,10,9,8,10,9,5,4,8,4,4,4,10,8,9,10,5,10,9,4,9,5,9,5~9,8,5,10~7,12,6,11,12,4,11,4,4,4,7,12,6,7,4,11,12,11,6,11~8,10,12,6,8,10,12,11,5,11,12,5,9,12,6,10,12,5,13,7,13,7,11,12,9,8,11,6,7,10,13,9,11,9,13,10,7&reel_set8=7,12,11,6,12,11,6,13~9,8,5,10~9,8,10~6,11,12,7~11,13,5,9,12,10,9,11,7,6,11,9,5,12,10,8,13,6,10,12,7,10,11,12,6,12,13,9,10,7,12,5,8,7,11,13,8&reel_set7=6,11,7,13,11,12,6,12~9,8,5,10~4,10,9,4,9,10,9,10,8,10,9,8,10,8,9,4,4,4,10,8,5,8,9,5,4,10,9,10,5,10,5,9,5,10~11,12,6,4,12,7,6,12,4,4,4,11,12,11,12,4,11,7,11,6,11~9,12,10,7,11,12,5,9,8,6,5,11,13,7,12,8,10,13,5,7,9,12,13,10&reel_set9=10,4,12,13,5,4,10,4,11,10,4,4,4,9,4,12,4,4,6,11,4,13,11,12,4,9,4~11,6,10,11,8,6,9,13,12,5,13,9,11,8,10,9,13,12,5,10,12,6,5,9,13,12,9,11,13,7,11,13,12,13,5,12,6,10,12,10,13,9,10,11,6,10,7,8,13,10,7,13,9,7,8,9,11,8,6,11,10,7,12,7,11,6,10,8,3,7,13,5,11,13,12,8~11,13,6,11,10,7,12,13,7,12,10,5,13,3,11,9,12,8,12,6,8,10,9,7,5,7,6,7,12,7,11,10,12,13,11,8,12,9,13,11,7,8,13,10,12,7,8,13,8,11~12,7,8,7,12,3,11,9,12,13,6,8,6,10,7,8,12,9,13,9,11,10,13,10,13,11,5,10,9,5,6~10,8,11,5,9,7,11,13,7,13,8,12,13,5,13,6,10,12,13,9,7,12,11,8';
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
                $bl = $slotEvent['bl'];
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
                
                $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $betline * $lines, $lines, $bl);
                $winType = $_spinSettings[0];
                $_winAvaliableMoney = $_spinSettings[1];
                $allBet = $betline * $lines;
                if($bl == 1){
                    $allBet = $betline * 25;
                }
                // $winType = 'bonus';

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
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bl', $bl);
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
                $scatter = '1';
                $Balance = $slotSettings->GetBalance();
                $totalWin = 0;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $str_stf = '';
                $str_apv = '';
                $initReel = [];
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 1];
                    $lastReel = explode(',', $stack['reel']);
                    if($stack['is'] != ''){
                        $initReel = explode(',', $stack['is']);
                    }
                    $currentReelSet = $stack['reel_set'];
                    $str_stf = $stack['stf'];
                    $str_apv = $stack['apv'];
                    $g = $stack['g'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines, $bl);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $lastReel = explode(',', $stack[0]['reel']);
                    if($stack[0]['is'] != ''){
                        $initReel = explode(',', $stack[0]['is']);
                    }
                    $currentReelSet = $stack[0]['reel_set'];
                    $str_stf = $stack[0]['stf'];
                    $str_apv = $stack[0]['apv'];
                    $g = $stack[0]['g'];
                }
                $reels = [];
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;
                $goldWin = 0;

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
                            if($j == 4){
                                $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                $totalWin += $lineWins[$k];
                                $_obf_winCount++;
                                $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                    $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                }
                            }
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
                $arr_apv = [];
                $arr_apwa = [];
                $arr_apt = [];
                if($str_apv != ''){
                    $arr_apv = explode(',', $str_apv);
                    for($k = 0; $k < count($arr_apv); $k++){
                        $arr_apwa[$k] = $arr_apv[$k] * $betline;
                        $arr_apt[$k] = 'ma';
                        $goldWin = $goldWin + $arr_apwa[$k];
                    }
                }
                $freeSpinNum = 0;
                if($scatterCount >= 3 || ($scatterCount >= 2 && $slotEvent['slotEvent'] == 'freespin')){
                    $freeSpinNums = [0,0,4,6,8,10];
                    $freeSpinNum = $freeSpinNums[$scatterCount];
                }
                $totalWin = $totalWin + $goldWin; 
                
                $spinType = 's';
                if( $totalWin > 0) 
                {
                    $spinType = 'c';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }
                $_obf_totalWin = $totalWin;
                if( $freeSpinNum > 0 ) 
                {
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $freeSpinNum);
                    }else{
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freeSpinNum);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
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
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                    {
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fsend_total=1&fswin_total='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin').'&fsmul_total=1&fsres_total='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 'c';
                    }
                    else
                    {
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 's';
                    }
                    if($freeSpinNum > 0){
                        $strOtherResponse = $strOtherResponse  .'&fsmore='. $freeSpinNum;
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                    if($scatterCount >= 3 ){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse = '&fsmul=1&fsmax='. $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames')  .'&fswin=0.00&fs=1&fsres=0.00';
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $stack);
                    }
                }
                if($goldWin > 0){
                    $strOtherResponse = $strOtherResponse . '&apwa='. implode(',', $arr_apwa) .'&apt='. implode(',', $arr_apt) .'&apv=' . $str_apv;
                }
                if(count($initReel) > 0){
                    $strOtherResponse = $strOtherResponse . '&is=' . implode(',', $initReel);
                }
                if($g != null){
                    $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($g));
                }
                if($str_stf != ''){
                    $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                }
                if($slotSettings->GetGameData($slotSettings->slotId . 'Bl') == 0){
                    $strOtherResponse = $strOtherResponse . '&l=20';
                }else{
                    $strOtherResponse = $strOtherResponse . '&l=25';
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .$strWinLine .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&bl='.$slotSettings->GetGameData($slotSettings->slotId . 'Bl').'&sh=3&c='.$betline.'&sver=5&reel_set='.$currentReelSet.'&counter='. ((int)$slotEvent['counter'] + 1) .'&s='.$strLastReel .'&w='. $totalWin;
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Bl":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bl') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"Stf":"' . $str_stf . '","FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                    
                $allBet = $betline * $lines;
                if($slotSettings->GetGameData($slotSettings->slotId . 'Bl') == 1){
                    $allBet = $betline * 25;
                }
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

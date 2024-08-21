<?php 
namespace VanguardLTE\Games\NorthGuardiansPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'LastFsmore', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 50);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [3,8,10,5,9,4,13,1,1,12,5,8,11,6,11,3,9,4,2,8,4,10,3,3,11]);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $str_mo = '';
                $strOtherResponse = '';
                $arr_g = null;
                
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', $lastEvent->serverResponse->BonusState);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);
                    if (isset($lastEvent->serverResponse->ReplayGameLogs)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', json_decode(json_encode($lastEvent->serverResponse->ReplayGameLogs), true)); //ReplayLog
                    }
                    if (isset($lastEvent->serverResponse->LastFsmore)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'LastFsmore', $lastEvent->serverResponse->LastFsmore); //ReplayLog
                    }
                    if (isset($lastEvent->serverResponse->FreeStacks)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', json_decode(json_encode($lastEvent->serverResponse->FreeStacks), true)); // FreeStack
                    }
                    $bet = $lastEvent->serverResponse->bet;
                    if (isset($lastEvent->serverResponse->Str_g) && $lastEvent->serverResponse->Str_g != ''){
                        $str_g = json_encode($lastEvent->serverResponse->Str_g);
                        $arr_g = json_decode($str_g, true);
                        // $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', $str_g);
                    }
                }
                else
                {
                    $bet = '20.00';
                }
                $currentReelSet = 0;
                $spinType = 's';
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    $strOtherResponse = $strOtherResponse . '&fs=' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&w=0.00&fsmul=1';
                    $currentReelSet = 1;
                }
                if($arr_g != null && $arr_g['bg']['end'] == 0){
                    $spinType = 'b';
                    $strOtherResponse = $strOtherResponse . '&bw=1&g=' . preg_replace('/"(\w+)":/i', '\1:', $str_g);
                    if (isset($lastEvent->serverResponse->Stf) && $lastEvent->serverResponse->Stf != ''){
                        $strOtherResponse = $strOtherResponse . '&stf=' . $lastEvent->serverResponse->Stf;
                    }
                    if (isset($lastEvent->serverResponse->Trail) && $lastEvent->serverResponse->Trail != ''){
                        $strOtherResponse = $strOtherResponse . '&trail=' . $lastEvent->serverResponse->Trail;
                    }
                    if (isset($lastEvent->serverResponse->InitReel) && $lastEvent->serverResponse->InitReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $lastEvent->serverResponse->InitReel;
                    }
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));

                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=3,8,10,5,9,4,13,1,1,12,5,8,11,6,11,3,9,4,2,8,4,10,3,3,11&balance='. $Balance .'&cfgs=5428&ver=3&index=1&balance_cash='. $Balance .'&def_sb=3,12,12,6,10&reel_set_size=9&def_sa=3,11,13,6,9&reel_set='.$currentReelSet.$strOtherResponse.'&balance_bonus=0.00&na='. $spinType .'&scatters=1~100,10,2,0,0~0,0,0,0,0~1,1,1,1,1&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"1000000000",max_rnd_win:"5000"}}&wl_i=tbm~5000&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=3,11,13,6,9&sb=3,12,12,6,10&sc='. implode(',', $slotSettings->Bet) .'&defc=20.00&purInit_e=1&sh=5&wilds=2~750,200,30,0,0~1,1,1,1,1&bonuses=0&st=rect&c='.$bet.'&sw=5&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;500,150,26,0,0;300,120,20,0,0;180,80,12,0,0;120,40,10,0,0;80,20,8,0,0;60,12,6,0,0;40,10,4,0,0;40,10,4,0,0;24,6,2,0,0;24,6,2,0,0;24,6,2,0,0&l=50&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=12,12,11,10,9,2,6,6,6,13,3,13,4,5,9,8,9,9,9,9,3,9,8,7,3,8,7,7,7,4,6,7,10,11,10,11,3,3,3,13,2,13,3,4,12,12,11,11,11,13,5,5,4,9,12,8,8,8,10,7,10,7,5,4,11,6,12,12,12,6,5,13,4,3,6,8,2,2,2,2,1,10,7,11,12,11,2,13,13,13,13,7,9,12,2,12,8,4,4,4,4,3,10,8,12,13,8,11,5,5,5,5,10,9,9,13,6,9,10,10,10,6,11,11,9,13,11,5,3,7~3,9,7,8,2,7,13,13,13,13,5,11,6,3,10,3,7,9,9,9,5,5,9,12,8,12,13,10,5,5,5,8,6,13,11,4,5,9,1,12,7,7,7,13,3,12,10,12,11,6,2,3,3,3,10,7,11,13,9,10,12,13,11,11,11,1,10,11,6,13,12,4,5,8,8,8,7,5,3,13,12,10,2,9,11,12,12,12,13,4,12,7,13,10,11,8,4,4,4,12,11,3,8,12,2,8,10,10,10,4,9,4,10,7,4,5,12,9,13~8,10,7,2,6,4,9,9,9,7,9,6,13,5,10,8,7,7,7,9,3,4,13,12,11,5,5,11,11,11,11,9,12,6,13,6,8,6,7,6,6,6,11,12,4,8,8,3,13,13,13,13,12,11,10,9,8,9,13,13,4,4,4,4,11,11,10,10,2,10,9,10,10,10,9,10,3,2,4,8,7,7,2,2,2,9,11,12,12,3,9,3,3,3,11,4,5,13,2,12,10,8,12,12,12,13,9,5,10,1,8,9,3,8,8,8,8,11,10,3,11,13,2,5,10,12~2,9,7,6,11,6,9,9,9,10,12,9,13,2,2,4,6,3,3,3,10,13,11,8,13,11,2,13,13,13,7,2,4,11,13,10,12,8,11,11,11,8,7,10,5,5,9,8,5,5,5,11,2,13,10,5,4,9,2,10,10,10,3,12,3,13,10,7,13,10,6,6,6,10,13,10,11,1,8,9,7,12,12,12,12,2,9,10,12,9,5,12,12,7,7,7,13,3,13,6,6,5,4,7,8,8,8,11,8,11,9,11,8,7,12,2,2,2,10,12,11,5,9,13,3,10,12~5,13,1,8,4,2,5,9,9,9,9,11,2,9,11,11,8,6,10,13,2,2,2,6,12,2,9,12,8,8,9,11,13,13,13,9,13,13,3,11,5,11,12,12,12,8,5,7,13,6,13,12,4,11,11,11,4,10,12,3,12,10,5,7,4,8,8,8,9,9,12,2,11,12,11,13,7,7,7,12,11,9,8,11,6,11,6,3,10,10,10,10,11,9,13,3,10,2,10,6,6,6,9,11,3,10,3,6,7,7,3,3,3,12,8,10,7,12,4,1,8,12,5,5,5,4,12,7,13,8,10,7,5,1,4,4,4,10,2,9,5,4,10,4,13,9,9&s='.$lastReelStr.'&reel_set2=6,12,3,6,5,1,7,9,9,9,12,1,8,1,12,5,11,1,10,13,13,13,13,4,1,9,1,5,4,10,11,5,5,5,5,10,13,1,7,9,9,3,11,3,3,3,11,1,7,3,6,4,4,3,12,12,12,8,1,11,13,11,12,1,11,11,11,9,6,13,9,7,1,5,13,1,4,4,4,1,7,1,12,7,10,9,11,13,7,7,7,7,10,10,13,1,8,13,5,13,8,6,6,6,13,12,4,9,13,3,13,13,8,1~7,9,8,12,13,10,6,12,13,7,13,5,12,13,11,5,12,10,7,13,13,13,9,8,6,13,3,13,12,4,3,12,5,12,5,9,1,13,3,7,4,13,11,9,9,9,13,12,12,5,9,13,10,13,8,5,6,12,9,10,12,4,5,6,13,4,11,11,11,6,12,12,5,9,4,5,13,3,12,10,3,8,12,13,3,4,13,4,6,5,5,5,5,13,9,10,12,7,13,1,13,9,11,13,3,11,7,11,3,10,13,3,4,11,12,12,12,12,9,12,4,12,5,10,1,6,12,12,4,10,12,13,11,6,11,13,7,5,11,7,7,7,11,8,4,6,13,4,10,13,11,7,12,9,8,9,10,10,5,5,13,3,10,10,10,10,5,4,3,13,13,12,9,12,11,8,10,7,3,3,11,11,5,12,12,9,6,4,4,4,10,13,3,3,8,12,12,11,12,8,7,12,4,8,10,5,10,3,8,10,12,3,3,3,7,9,12,13,13,8,4,13,13,7,12,4,7,3,12,13,9,5,11,10,11,8,8,8,7,13,4,7,12,12,8,11,9,13,5,11,4,12,10,13,12,10,12,12,7,6,6,6,6,8,11,13,13,3,9,7,6,13,8,12,8,13,5,5,10,9,11,9,13,7,9~7,10,10,11,3,1,8,5,5,5,6,7,12,11,1,9,9,1,9,9,9,13,1,8,1,13,11,12,7,4,3,3,3,5,1,8,11,7,1,3,12,8,8,8,1,4,11,11,9,11,3,10,1,4,4,4,5,1,9,5,13,9,13,13,13,10,4,1,9,8,13,11,11,7,7,7,1,8,11,10,9,6,10,3,4,11,11,11,11,9,8,4,6,7,1,5,10,10,10,10,12,3,1,10,10,8,4,12,1~10,11,8,10,13,4,11,9,11,9,5,10,5,6,3,12,7,10,8,13,10,9,11,1,13,3,3,6,8,11,7,10,7,9,9,9,9,11,10,11,5,8,11,11,7,12,12,8,9,5,5,10,12,13,10,1,10,7,8,11,11,10,13,13,9,13,11,10,5,13,13,5,3,3,3,10,6,3,13,10,13,8,11,13,10,3,10,5,11,9,8,10,12,3,11,3,11,10,9,9,11,10,10,5,10,9,12,7,12,11,11,11,11,4,13,3,6,13,5,6,7,6,10,7,10,7,3,13,11,3,10,11,10,8,7,13,3,10,12,9,10,11,12,13,9,11,6,5,5,5,5,8,9,3,7,12,13,13,10,9,6,10,9,13,11,13,11,12,3,10,12,10,6,13,9,12,6,11,7,13,8,10,6,10,10,6,6,6,6,5,9,13,10,10,4,10,13,11,11,6,10,12,11,4,11,4,3,10,10,7,4,8,9,11,11,5,12,13,5,7,9,8,12,12,12,12,12,8,10,3,6,12,5,10,13,13,4,5,10,5,4,8,7,13,10,8,13,7,10,10,13,8,11,13,11,10,9,8,10,7,9,8,7,7,7,6,11,13,1,4,5,5,10,12,8,13,10,12,10,11,7,4,6,11,7,7,4,12,13,11,9,10,4,10,10,12,4,10,10,10,10,10,10,13,13,6,3,10,13,12,4,12,13,10,4,8,9,9,10,10,5,7,12,12,9,12,10,9,13,10,13,10,13,11,7,9,9,10,13,13,13,13,9,5,9,10,12,13,8,10,3,11,3,13,1,10,6,9,5,10,4,11,7,3,11,13,10,12,11,11,7,4,10,11,10,13,8,8,8,8,7,7,9,11,12,13,9,10,9,10,10,6,8,10,12,12,3,3,6,13,9,12,10,11,7,10,8,9,8,11,10,8,8,6,10,4,4,4,4,9,11,10,8,7,5,7,5,11,10,3,11,5,8,6,13,10,11,12,10,13,7,10,12,1,8,13,5,11,10,12,12,6,12,9,9~8,13,1,13,4,9,10,9,4,1,11,10,10,10,8,4,1,11,9,13,12,3,3,1,11,5,12,12,13,13,13,13,10,1,10,10,13,12,13,1,8,11,5,1,13,8,5,5,5,9,9,13,10,12,1,4,5,1,5,12,6,13,12,12,12,12,13,9,13,1,8,10,10,1,11,6,8,1,7,3,3,3,12,12,3,6,1,11,7,8,4,13,3,12,5,8,8,8,6,1,12,1,13,11,1,12,10,5,4,10,9,9,9,13,8,1,10,9,11,1,6,12,9,7,6,13,11,11,11,1,6,13,9,1,6,9,11,3,10,1,8,13,8,4,4,4,4,1,7,5,7,4,13,10,12,11,11,4,10,13,1,6,6,6,11,1,9,7,11,4,10,8,1,13,7,1,9,4,1&reel_set1=11,12,13,11,10,13,12,3,13,13,4,12,7,8,9,9,9,7,10,13,13,10,10,4,3,8,7,12,9,6,10,5,11,8,7,7,7,11,8,5,4,9,11,10,3,13,3,10,12,8,11,9,5,10,13,13,13,7,2,9,8,7,7,2,9,3,12,9,5,5,10,11,10,11,11,11,13,13,10,4,11,10,12,9,11,13,3,13,6,12,8,10,4,8,8,8,12,8,7,10,10,12,13,5,6,5,11,5,6,13,11,12,3,12,12,12,11,3,11,8,13,13,3,12,9,13,6,7,9,5,9,4,5,10,10,10,7,13,11,5,6,9,4,9,11,10,11,12,8,11,9,8,6~5,7,10,11,8,12,12,7,7,6,10,13,13,13,12,9,11,7,4,10,10,8,10,10,13,4,4,4,5,8,6,5,5,6,4,10,2,12,13,10,9,9,9,11,11,13,12,4,9,12,13,8,8,2,9,11,11,11,13,4,9,13,3,7,11,11,10,10,3,12,11,7,7,7,13,8,12,5,12,10,3,9,6,5,11,13,10,10,10,12,3,10,13,4,13,13,3,5,3,12,8,8,8,13,10,3,12,6,12,13,7,11,8,11,7,12,12,12,7,13,11,9,4,12,10,13,11,13,9,12,4,4~7,10,10,9,11,13,6,12,13,8,9,9,9,13,10,12,12,13,12,13,11,13,5,2,11,7,7,7,10,10,5,10,3,9,8,9,4,3,13,11,13,13,13,2,10,3,11,9,4,11,11,13,8,10,12,10,10,10,12,10,6,8,12,11,9,7,3,6,13,4,11,11,11,10,9,3,10,9,11,7,9,6,6,4,8,3,3,3,9,5,12,7,3,10,10,7,9,4,12,12,12,6,5,10,13,11,9,4,12,9,8,7,9,8,8,8,13,12,5,9,7,13,12,13,8,8,11,5,13~12,10,12,10,5,3,13,9,9,11,4,2,9,9,9,3,10,13,6,7,10,7,11,12,5,13,10,3,3,3,6,3,5,8,12,5,4,3,6,9,7,10,12,13,13,13,11,11,5,12,12,8,13,13,9,10,13,7,4,10,11,11,11,12,9,8,10,9,9,12,4,9,7,8,8,7,8,12,12,12,13,11,11,5,4,13,3,6,13,11,11,5,12,7,7,7,12,8,9,9,11,10,12,6,11,5,11,6,10,7,8,8,8,10,9,10,12,8,11,10,13,6,11,6,6,11,6,6,6,9,7,13,11,7,12,11,9,5,8,13,13,10,13,10,10,10,9,4,13,9,8,11,10,13,9,2,7,10,6,13,3~9,6,11,5,8,6,9,13,7,8,10,12,11,9,9,9,5,13,6,10,4,12,9,8,9,13,4,8,9,10,13,13,13,11,12,11,12,11,11,12,10,9,12,6,11,10,7,11,11,11,7,11,8,4,10,10,7,2,4,5,13,3,12,11,8,8,8,5,10,5,3,13,11,10,10,12,9,5,6,10,5,11,12,12,12,6,13,8,9,10,12,3,3,13,13,11,9,9,4,7,7,7,5,12,13,13,5,7,11,10,7,12,4,11,8,10,10,10,13,3,10,3,12,12,9,10,12,8,3,8,12,9,6,6,6,12,9,5,13,11,8,9,2,7,13,8,6,6,4,13,8&reel_set4=12,7,9,7,12,13,10,5,10,4,4,6,11,11,11,11,12,11,12,13,13,9,3,11,10,7,10,7,6,9,9,9,4,12,13,9,6,10,10,3,12,11,13,11,5,7,7,7,9,3,12,3,13,12,5,8,7,13,5,9,4,11,13,13,13,5,8,13,6,7,12,8,3,6,12,4,8,8,8,13,8,12,10,2,8,7,13,10,4,5,9,13,12,12,12,8,10,11,5,10,11,10,12,13,7,11,10,4,10,10,10,10,2,10,10,11,7,8,9,8,5,13,11,11,12,11,13~12,13,13,9,6,11,12,12,12,10,10,11,13,13,6,13,3,9,9,9,7,10,9,3,12,11,12,10,11,11,11,11,12,13,7,7,4,8,11,6,10,13,13,13,6,13,12,4,9,12,12,5,7,7,7,11,5,5,8,12,8,12,13,8,8,8,10,8,12,4,3,11,3,13,10,10,10,11,13,4,13,7,10,5,10,2,11~11,8,5,3,13,9,12,11,9,8,13,7,7,10,9,9,9,11,10,4,8,6,4,10,6,12,8,13,10,7,9,5,7,7,7,12,6,3,11,12,9,3,9,12,12,10,7,10,13,11,13,13,13,11,13,9,12,2,9,11,9,10,10,9,6,10,12,11,5,10,10,10,7,5,7,10,12,9,13,5,2,10,9,10,13,8,10,12,12,12,8,13,6,6,13,3,9,9,4,8,10,13,13,12,11,8,11,11,11,12,11,7,5,5,13,13,10,9,5,12,10,7,9,8,8,8,10,13,6,11,13,7,5,9,13,13,9,4,9,4,13,12,10~13,7,13,11,6,12,6,10,10,13,12,4,8,10,5,5,9,7,11,11,11,9,12,4,10,13,9,9,13,11,10,5,12,3,5,8,7,12,13,11,13,13,13,3,7,10,2,12,6,10,9,13,7,8,3,8,11,9,4,11,13,13,9,9,9,12,10,8,11,9,2,11,7,9,4,13,10,4,12,6,6,8,10,7,7,7,3,10,13,5,12,13,4,11,9,11,9,10,5,11,10,12,13,4,7,8,8,8,13,9,7,6,6,8,9,10,6,13,10,13,5,13,9,5,9,12,12,10,10,10,13,9,13,9,10,13,7,5,4,7,10,5,13,12,10,12,7,6,8,12,12,12,13,11,6,7,10,5,13,11,7,5,10,12,13,12,9,11,9,9,5,10,9~7,8,7,4,10,13,13,2,6,12,11,12,11,11,11,5,3,8,11,6,6,9,3,9,3,10,11,12,9,9,9,8,12,13,12,7,13,11,10,13,2,13,10,12,8,7,7,7,5,12,7,9,8,3,12,4,9,13,7,4,10,13,13,13,11,5,11,12,9,11,5,10,12,7,6,11,10,10,10,9,11,10,8,4,13,10,9,11,12,5,13,12,13,12,12,12,6,8,5,4,4,11,11,9,13,5,11,9,13,8,8,8,13,8,9,9,12,13,12,8,8,13,8,11,9,5,7&purInit=[{bet:5000,type:"default"}]&reel_set3=7,7,4,12,8,10,13,7,10,11,8,11,11,11,3,13,4,12,10,12,13,7,4,10,11,9,9,9,6,13,10,12,12,13,5,5,9,11,4,13,9,7,7,7,11,13,3,12,6,9,6,13,13,10,11,13,13,13,7,8,5,5,7,10,10,11,12,13,7,2,5,12,12,12,9,13,10,3,12,7,9,4,10,13,12,11,6,6,6,8,6,8,6,11,10,10,2,9,13,4,10,12,10,10,10,10,8,11,13,4,6,10,5,3,7,12,11,7,8,8,8,11,9,10,12,6,13,13,12,13,3,13,13,8,9~5,9,3,12,13,7,12,13,12,10,3,13,12,12,12,10,8,5,13,7,12,10,8,12,13,13,11,12,8,9,9,9,4,5,7,10,2,6,8,13,10,2,11,12,5,4,11,11,11,12,11,10,6,10,6,7,13,7,4,10,7,9,6,13,13,13,11,13,3,6,12,3,9,13,12,13,7,4,11,11,7,7,7,8,13,10,3,12,10,6,12,12,8,10,11,11,9,8,8,8,5,11,10,4,6,12,10,12,11,3,11,5,4,3,10,10,10,13,5,12,12,13,10,11,9,13,13,8,12,11,13,4~10,10,7,13,5,11,5,7,7,9,9,9,4,9,6,8,8,10,3,13,11,9,7,7,7,9,5,13,6,13,12,13,3,10,13,13,13,8,13,2,9,6,9,4,10,12,4,11,11,11,11,9,9,3,10,13,11,11,10,13,4,10,10,10,12,12,10,12,13,5,9,5,12,10,12,12,12,9,5,10,7,10,8,13,7,7,13,8,8,8,11,6,11,11,3,13,11,8,9,7,9,9~9,13,5,11,9,5,4,13,10,12,8,10,7,12,11,11,11,3,4,10,5,8,6,13,12,4,12,10,6,5,10,7,13,13,13,5,13,9,11,10,12,9,9,13,9,2,10,5,3,7,9,9,9,10,7,10,8,13,9,13,11,11,9,4,5,6,10,11,6,7,7,7,8,13,3,8,10,11,12,9,12,13,9,8,11,12,8,8,8,13,4,9,13,7,6,9,12,13,11,7,11,13,10,13,10,10,10,6,13,5,9,9,7,13,7,13,11,9,12,13,10,5,10,12,12,12,9,5,4,4,12,9,13,6,2,10,7,9,10,8,11,13,5~5,9,11,11,5,10,8,3,8,12,13,11,11,12,7,11,11,11,11,4,13,5,5,13,13,6,11,9,11,4,10,9,11,12,10,9,9,9,5,12,9,13,12,10,11,7,6,11,11,13,10,3,12,8,13,7,7,7,11,8,9,5,11,8,7,12,12,8,13,8,11,13,4,7,13,13,13,6,3,4,10,8,8,6,13,8,4,13,12,12,13,7,5,8,8,8,10,8,11,2,13,13,6,9,2,7,11,4,5,13,13,12,8,12,12,12,7,4,9,13,5,9,8,10,6,13,3,9,12,13,7,10,3,10,10,10,8,9,12,12,11,9,11,11,6,12,13,5,9,9,11,10,12,7&reel_set6=11,8,11,13,7,6,10,9,13,9,9,9,11,11,6,13,12,4,13,10,8,9,12,7,7,7,10,4,8,12,3,9,11,12,4,12,10,10,10,10,11,10,11,13,5,13,12,8,9,13,11,12,12,12,10,5,9,13,12,10,12,6,11,12,11,11,11,11,10,12,10,2,13,8,13,6,7,7,9,13,13,13,7,11,5,10,10,8,6,9,5,7,8,8,8,9,11,3,12,10,8,11,6,9,7,13,12~11,12,5,13,12,9,12,8,10,6,3,7,10,12,12,12,2,5,13,11,10,3,13,12,10,13,11,13,3,10,13,9,9,9,13,12,11,11,12,10,7,10,9,11,10,7,10,13,11,11,11,11,5,7,12,11,13,10,8,13,9,6,13,6,9,13,11,10,10,10,7,12,12,13,8,12,10,11,11,12,6,12,9,4,11,13,13,13,4,12,4,12,12,5,11,12,6,8,6,10,13,5,13,7,7,7,8,9,8,11,5,9,13,8,10,7,13,9,10,9,4,8,8,8,10,11,6,11,13,2,12,11,8,9,7,6,8,12,7,8~10,11,10,9,11,5,12,9,9,9,8,7,9,3,10,11,8,6,6,7,7,7,12,13,11,13,5,4,12,13,10,13,13,13,7,10,13,9,5,9,13,13,8,12,12,12,11,8,6,8,11,12,6,10,11,11,11,9,7,12,7,9,4,12,10,13,10,10,10,12,13,5,7,11,9,10,10,11,8,8,8,9,4,13,13,3,9,6,12,2,12~6,13,13,12,12,11,13,12,10,9,10,5,13,8,11,11,11,12,10,12,11,6,10,12,11,13,13,10,9,13,9,5,13,13,13,11,5,6,10,11,10,13,10,9,5,12,11,7,11,12,9,9,9,4,6,12,7,12,13,8,8,7,12,13,13,9,7,10,10,10,11,12,10,9,5,10,12,3,13,10,11,9,3,11,8,12,12,12,9,7,12,12,6,10,2,13,13,10,7,11,9,6,7,8,8,8,9,6,11,12,10,4,11,10,9,8,9,9,10,8,10,7,7,7,9,13,9,11,11,8,11,13,9,4,7,8,7,13,2,10,10~13,11,13,10,8,7,9,5,10,8,13,12,6,11,11,11,6,4,13,11,7,9,12,12,11,12,13,11,13,10,9,9,9,8,7,10,11,9,7,6,7,9,11,7,12,13,13,7,7,7,6,11,10,2,5,9,13,13,2,10,11,11,13,8,13,13,13,13,9,9,4,8,6,3,10,11,8,12,13,9,11,10,10,10,9,10,9,7,13,8,6,8,8,12,13,8,12,12,12,3,5,4,9,8,10,13,7,5,12,12,8,12,8,8,8,11,12,5,7,11,5,4,10,12,11,13,3,13,9,10,12&reel_set5=12,8,7,13,10,5,2,11,13,13,8,3,13,12,11,11,11,11,10,11,6,6,3,13,8,4,3,10,5,5,8,10,9,10,9,9,9,11,13,5,9,12,5,6,9,13,12,13,11,13,11,4,10,7,7,7,10,12,10,11,3,12,6,11,8,9,11,10,13,5,3,7,13,13,13,12,4,7,7,13,3,9,10,7,10,12,12,10,6,11,10,8,8,8,13,13,7,12,10,7,9,8,2,9,13,7,11,13,7,13,12,12,12,8,10,6,5,7,10,8,11,12,13,11,5,8,11,7,11,10,10,10,10,4,12,4,11,4,12,7,8,8,13,4,12,7,4,12,10,9~4,11,7,6,12,5,6,4,5,3,12,12,12,9,12,3,7,8,10,5,3,9,11,12,7,9,9,9,4,11,5,10,13,11,3,13,11,10,11,11,11,11,6,9,12,8,4,13,8,12,5,11,13,8,13,13,13,10,13,10,12,11,11,8,10,13,5,12,10,7,7,7,10,13,13,12,11,6,12,6,10,3,13,8,8,8,6,3,12,13,13,9,12,10,5,13,10,12,10,10,10,8,4,2,7,13,12,7,12,8,13,10,13,12~9,13,10,3,12,9,10,13,5,9,7,11,6,3,13,9,9,9,10,9,9,12,12,13,8,9,11,2,13,11,13,5,7,13,7,7,7,10,13,11,13,7,11,10,9,11,10,13,9,10,3,9,9,13,13,13,7,9,13,9,12,13,11,6,7,10,8,10,9,8,6,8,10,10,10,6,10,4,9,9,8,7,4,4,6,12,5,10,8,9,10,12,12,12,11,4,8,5,12,8,10,12,4,6,4,13,13,10,7,13,11,11,11,10,13,13,3,6,9,12,10,7,6,10,6,7,11,13,8,12,8,8,8,7,8,9,3,9,13,5,12,11,11,13,2,6,9,11,5,13,11~9,10,9,11,9,12,9,5,13,9,5,9,11,11,11,2,9,7,7,12,13,9,4,3,3,10,4,7,7,13,13,13,12,7,11,12,11,8,11,10,9,9,10,12,9,5,9,9,9,13,9,10,6,9,5,8,4,9,13,8,4,6,7,7,7,13,11,5,3,13,13,8,9,12,10,11,13,10,8,8,8,7,6,4,12,4,12,10,13,7,8,10,12,13,11,10,10,10,13,5,10,6,11,13,12,13,6,2,5,5,7,10,12,12,12,13,7,12,13,10,12,13,10,11,10,13,6,5,13,8~12,12,9,12,8,13,13,5,8,6,11,11,11,9,6,12,13,10,7,13,7,8,4,8,11,9,9,9,5,13,13,11,10,11,10,9,7,11,9,7,7,7,8,4,8,8,11,8,4,10,12,11,3,4,13,13,13,5,12,6,12,13,6,9,7,5,10,12,10,10,10,7,11,9,13,11,7,9,13,8,12,10,12,12,12,8,13,12,4,2,11,9,11,13,9,13,5,8,8,8,12,3,9,12,13,11,5,5,10,11,3,6,9&reel_set8=13,1,4,8,12,8,6,6,4,1,13,10,10,10,10,6,6,10,10,12,10,1,6,8,13,13,4,8,8,8,12,1,12,13,4,12,1,12,8,6,1,4,4,4,4,10,10,6,8,10,10,1,13,6,10,8,13,12,12,12,12,1,4,8,8,12,12,10,10,13,1,13,13,13,13,4,10,12,8,1,12,4,8,1,8,4,1,6,6,6,1,8,1,12,4,10,1,12,13,10,4,12,4~12,10,10,6,6,1,8,5,5,5,11,1,9,1,12,11,12,5,10,12,12,12,12,9,5,12,6,12,10,4,1,12,8,8,8,5,1,10,12,1,12,12,4,11,1,4,4,4,10,12,7,8,1,9,7,7,8,11,11,11,12,9,12,7,4,3,7,11,1,8,10,10,10,12,1,12,4,1,3,5,10,12,9,9,9,9,3,1,11,3,12,9,3,1,12,1,4~9,7,9,1,9,5,11,1,3,13,9,5,5,11,11,13,3,7,7,7,7,11,13,1,9,9,13,11,13,3,9,5,1,5,1,3,1,11,13,11,11,11,11,7,3,1,5,9,5,11,11,1,3,9,5,11,7,5,9,11,7,9,9,9,9,1,7,7,1,13,11,1,11,5,11,1,7,9,3,9,1,11,9,13,13,13,13,7,9,13,1,3,9,3,7,1,9,3,9,1,7,9,9,7,9,3,3,3,11,7,3,5,9,7,1,7,3,1,9,1,9,11,1,13,1,7,1,11~3,5,11,2,4,2,13,6,7,11,7,9,9,9,11,10,5,13,3,13,6,11,11,10,1,13,3,3,3,11,3,4,5,2,7,7,12,10,12,10,4,13,13,13,2,11,3,12,13,9,10,12,5,2,9,2,5,11,11,11,11,5,10,13,2,9,9,4,3,5,13,12,11,5,5,5,10,11,13,11,8,9,2,8,12,4,6,8,10,10,10,9,13,13,11,11,6,8,7,9,2,13,6,6,6,7,9,5,13,2,13,10,11,8,13,13,12,7,12,12,12,12,11,12,12,11,9,10,7,1,13,10,9,11,7,7,7,8,10,11,12,5,9,8,6,8,10,9,10,8,8,8,9,4,6,2,8,8,10,11,6,3,7,2,2,2,13,7,10,8,10,12,13,12,9,12,6,7,12,10~13,13,5,9,6,10,11,8,6,5,7,5,9,9,9,9,12,7,2,8,12,8,7,2,7,7,11,9,7,2,2,2,5,4,8,11,11,10,2,5,9,11,10,11,10,13,13,13,11,3,12,5,4,9,8,11,11,3,9,12,13,12,12,12,8,13,8,13,3,2,6,8,1,4,8,8,4,9,11,11,11,11,13,10,4,2,12,10,10,12,11,13,4,10,8,8,8,6,10,13,13,9,5,13,9,2,9,3,8,13,7,7,7,13,11,9,12,10,4,13,7,6,2,6,3,11,10,10,10,10,5,11,12,4,9,4,7,2,2,10,11,12,12,3,6,6,6,9,2,1,7,4,6,2,12,8,4,13,12,9,3,3,3,9,5,12,10,7,4,6,9,13,12,12,3,11,5,5,5,12,3,6,11,5,5,9,7,9,8,6,10,13,4,4,4,12,9,11,10,11,12,11,3,6,10,11,10,8,10,10&reel_set7=11,5,10,10,9,8,5,8,13,7,9,13,8,13,11,11,11,12,13,10,11,7,11,9,12,7,7,8,8,7,6,7,10,9,9,9,10,4,13,13,12,9,8,6,12,11,5,10,10,9,8,12,13,13,13,11,11,3,12,8,9,13,3,6,6,11,9,13,13,8,6,12,12,12,13,12,9,4,12,13,4,13,7,5,11,10,4,13,9,8,8,8,5,12,12,10,12,12,11,9,4,9,13,10,12,10,5,11,7,7,7,11,6,7,9,12,4,10,13,11,11,13,6,12,11,12,10,10,10,6,7,8,9,8,12,11,5,3,11,13,11,13,8,13,13,12~13,10,5,8,12,12,11,12,12,12,7,9,12,5,4,13,8,7,9,9,9,12,10,11,3,7,6,8,12,4,10,11,11,11,11,13,12,11,9,6,9,12,9,6,13,13,13,12,5,12,13,9,13,3,6,10,10,10,11,7,10,6,10,11,10,10,8,10,7,7,7,8,11,10,10,5,12,10,11,8,8,8,6,7,3,12,11,13,12,9,4,6,6,6,13,4,13,11,7,13,5,13,13,5,8~13,13,9,10,12,13,11,6,10,7,9,6,9,9,9,3,7,4,6,10,11,12,7,11,9,13,6,4,7,7,7,10,6,10,13,5,8,5,12,13,4,12,8,7,13,13,13,10,9,9,10,10,9,7,11,10,12,13,5,13,11,11,11,8,13,11,6,12,12,13,8,9,8,7,5,8,6,6,6,11,3,10,10,8,13,9,13,10,8,3,7,9,10,10,10,8,11,13,9,9,13,12,9,4,7,7,13,9,12,12,12,9,7,5,9,6,6,9,10,8,13,11,9,10,8,8,8,11,6,8,9,10,12,5,7,11,10,13,9,11,9~13,10,11,11,9,12,6,6,5,7,9,9,6,12,10,13,9,11,11,11,10,5,4,11,10,12,5,12,13,7,12,8,10,5,3,12,6,8,13,13,13,12,8,10,12,13,6,6,10,11,5,10,10,13,11,7,13,8,10,9,9,9,13,9,6,13,7,13,7,10,9,12,9,9,13,8,10,12,9,7,7,7,11,12,13,13,4,10,12,5,9,10,9,6,6,7,8,5,13,7,10,10,10,11,13,10,13,8,9,6,7,13,13,11,6,6,12,5,6,7,6,12,12,12,11,10,12,7,10,13,8,10,12,4,3,9,13,9,9,10,8,12,8,8,8,9,11,9,9,13,13,7,11,8,8,7,9,11,10,10,13,12,9,13~13,7,12,13,12,8,8,10,12,11,6,9,11,11,11,4,8,8,7,9,5,13,12,12,9,10,9,10,11,13,9,9,9,13,9,7,3,10,11,11,13,8,8,11,12,8,7,7,7,11,9,6,5,11,8,11,3,12,11,7,12,7,10,8,13,13,13,12,10,13,10,11,12,10,6,7,6,10,7,13,8,10,10,10,9,13,9,11,10,12,13,12,11,5,11,8,4,6,4,12,12,12,6,12,11,4,12,5,7,11,13,8,13,13,10,12,8,8,8,9,13,9,13,12,7,7,13,11,9,13,10,5,9,8,5&total_bet_min=4.00';
            }
            else if( $slotEvent['slotEvent'] == 'doCollect' || $slotEvent['slotEvent'] == 'doCollectBonus') 
            {
                $Balance = $slotSettings->GetBalance();
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);    
                $response = 'balance=' . $Balance . '&index=' . $slotEvent['index'] . '&balance_cash=' . $Balance . '&balance_bonus=0.00&na=s&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sver=5&counter=' . ((int)$slotEvent['counter'] + 1);
                
                //------------ ReplayLog ---------------                
                $lastEvent = $slotSettings->GetHistory();
                if($lastEvent != NULL){
                    $betline = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $betline = $slotSettings->Bet[0];
                }
                $lines = 50;      
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
            else if( $slotEvent['slotEvent'] == 'doSpin' || $slotEvent['slotEvent'] == 'doBonus' ) 
            {
                $lastEvent = $slotSettings->GetHistory();
                $linesId = [];
                $linesId[0] = [1, 1, 1, 1, 1];
                $linesId[1] = [2, 2, 2, 2, 2];
                $linesId[2] = [3, 3, 3, 3, 3];
                $linesId[3] = [4, 4, 4, 4, 4];
                $linesId[4] = [5, 5, 5, 5, 5];
                $linesId[5] = [1, 2, 1, 2, 1];
                $linesId[6] = [2, 3, 2, 3, 2];
                $linesId[7] = [3, 4, 3, 4, 3];
                $linesId[8] = [4, 5, 4, 5, 4];
                $linesId[9] = [2, 1, 2, 1, 2];
                $linesId[10] = [3, 2, 3, 2, 3];
                $linesId[11] = [4, 3, 4, 3, 4];
                $linesId[12] = [5, 4, 5, 4, 5];
                $linesId[13] = [1, 1, 2, 1, 1];
                $linesId[14] = [2, 2, 3, 2, 2];
                $linesId[15] = [3, 3, 4, 3, 3];
                $linesId[16] = [4, 4, 5, 4, 4];
                $linesId[17] = [2, 2, 1, 2, 2];
                $linesId[18] = [3, 3, 2, 3, 3];
                $linesId[19] = [4, 4, 3, 4, 4];
                $linesId[20] = [5, 5, 4, 5, 5];
                $linesId[21] = [1, 2, 3, 2, 1];
                $linesId[22] = [2, 3, 4, 3, 2];
                $linesId[23] = [3, 4, 5, 4, 3];
                $linesId[24] = [3, 2, 1, 2, 3];
                $linesId[25] = [4, 3, 2, 3, 4];
                $linesId[26] = [5, 4, 3, 4, 5];
                $linesId[27] = [1, 2, 3, 4, 5];
                $linesId[28] = [5, 4, 3, 2, 1];
                $linesId[29] = [1, 3, 1, 3, 1];
                $linesId[30] = [2, 4, 2, 4, 2];
                $linesId[31] = [3, 5, 3, 5, 3];
                $linesId[32] = [3, 1, 3, 1, 3];
                $linesId[33] = [4, 2, 4, 2, 4];
                $linesId[34] = [5, 3, 5, 3, 5];
                $linesId[35] = [1, 2, 2, 2, 1];
                $linesId[36] = [2, 3, 3, 3, 2];
                $linesId[37] = [3, 4, 4, 4, 3];
                $linesId[38] = [4, 5, 5, 5, 4];
                $linesId[39] = [2, 1, 1, 1, 2];
                $linesId[40] = [3, 2, 2, 2, 3];
                $linesId[41] = [4, 3, 3, 3, 4];
                $linesId[42] = [5, 4, 4, 4, 5];
                $linesId[43] = [1, 2, 2, 2, 3];
                $linesId[44] = [2, 3, 3, 3, 4];
                $linesId[45] = [3, 4, 4, 4, 5];
                $linesId[46] = [3, 2, 2, 2, 1];
                $linesId[47] = [4, 3, 3, 3, 2];
                $linesId[48] = [5, 4, 4, 4, 3];
                $linesId[49] = [1, 5, 1, 5, 1];
                $pur = -1;
                if(isset($slotEvent['pur'])){
                    $pur = $slotEvent['pur'];
                }
                if($slotEvent['slotEvent'] == 'doBonus'){
                    $slotEvent['slotBet'] = $lastEvent->serverResponse->bet ?? 0;
                }else{
                    $slotEvent['slotBet'] = $slotEvent['c'];
                }
                $slotEvent['slotLines'] = 50;
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
                    $allBet = $betline * $lines * 100;
                }
                $freeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'freespin' || $slotEvent['slotEvent'] == 'doBonus'){
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastFsmore', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur);
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
                $scatter = '1';
                $Balance = $slotSettings->GetBalance();
                
                $totalWin = 0;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $str_initReel = '';
                $arr_g = null;
                $str_trail = '';
                $str_stf = '';
                $fsmore = 0;
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $str_initReel = $stack['initReel'];
                    $arr_g = $stack['g'];
                    $str_trail = $stack['trail'];
                    $str_stf = $stack['stf'];
                    $fsmore = $stack['fsmore'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $pur, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $lastReel = explode(',', $stack[0]['reel']);
                    $currentReelSet = $stack[0]['reel_set'];
                    $arr_g = $stack[0]['g'];
                    $str_initReel = $stack[0]['initReel'];
                    $str_trail = $stack[0]['trail'];
                    $str_stf = $stack[0]['stf'];
                    $fsmore = $stack[0]['fsmore'];
                }
                $reels = [];
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;
                for($i = 0; $i < 5; $i++){
                    $reels[$i] = [];
                    $wildReels[$i] = [];
                    for($j = 0; $j < 5; $j++){
                        $reels[$i][$j] = $lastReel[$j * 5 + $i];
                        if($lastReel[$j * 5 + $i] == $scatter){
                            $scatterCount++;
                            $scatterPoses[] = $j * 5 + $i;   
                        }
                    }
                }
                $isCalcWinLine = false;
                if($arr_g == null || $arr_g['bg']['end'] == 1){
                    if($slotEvent['slotEvent'] == 'freespin'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    }
                    $isCalcWinLine = true;
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
                        $mul = 0;
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
                                }else if($j >= 2 && $ele == $wild){
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
                    if($scatterCount >= 3){
                        $scatterMuls = [0,0,0,2,10,100];
                        $scatterWin = $betline * $lines * $scatterMuls[$scatterCount];
                        $totalWin = $totalWin + $scatterWin;
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
                if( $isCalcWinLine == true && $scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin') 
                {
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0 ) 
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 5);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                    }
                }
                if($fsmore > 0 && $isCalcWinLine == false && $slotEvent['slotEvent'] == 'freespin'){
                    if($fsmore < 3){
                        $diff_more = $fsmore;
                        $slotSettings->SetGameData($slotSettings->slotId . 'LastFsmore', $slotSettings->GetGameData($slotSettings->slotId . 'LastFsmore') + $fsmore);
                    }else{
                        $diff_more = $fsmore - $slotSettings->GetGameData($slotSettings->slotId . 'LastFsmore');
                        $slotSettings->SetGameData($slotSettings->slotId . 'LastFsmore', $fsmore);
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $diff_more);
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
                    $isEnd = false;
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                    {
                        $spinType = 'cb';
                        $isEnd = true;
                        $strOtherResponse = '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsend_total=1&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                    else
                    {
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        if($isCalcWinLine == true){
                            $spinType = 's';
                        }else{
                            $spinType = 'b';
                        }
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0 && $isCalcWinLine == false && $slotEvent['action'] == 'doSpin'){
                        $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                    }
                    if($fsmore > 0){
                        $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);                    
                    if($isCalcWinLine == false){
                        $spinType = 'b';
                    }else{
                        if($arr_g != null && $totalWin > 0){
                            $spinType = 'cb';
                        }
                    }
                    if($isCalcWinLine== true && $scatterCount >= 3 ){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse = '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fs=1&fsres=0.00&psym=1~' . $scatterWin . '~' . implode(',', $scatterPoses);                      
                        if($pur >= 0){
                            $strOtherResponse = $strOtherResponse . '&purtr=1&puri=' . $pur;
                        }
                    }
                    if($isGeneratedFreeStack == false){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $stack);  
                    }
                }
                if($arr_g != null){
                    $arr_g['bg']['rw'] ='' . $totalWin;
                    $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($arr_g));
                    if($isCalcWinLine == false){
                        $isState = false;
                    }
                }
                if($str_trail != ''){
                    $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                }
                if($str_stf != ''){
                    $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($isCalcWinLine == false && $slotEvent['action'] == 'doSpin'){
                    $strOtherResponse = $strOtherResponse . '&bw=1';
                }
                if($currentReelSet >= 0){
                    $strOtherResponse = $strOtherResponse . '&reel_set=' . $currentReelSet;
                }
                if($arr_g == null || $arr_g['bg']['end'] == 0  && $slotEvent['action'] == 'doSpin'){
                    $strOtherResponse = $strOtherResponse . '&l=50&c=' . $betline;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .$strWinLine .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&st=rect&sw=5&sh=5&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&s='.$strLastReel;
                if($arr_g == null || $arr_g['bg']['end'] == 0 && $slotEvent['action'] == 'doSpin'){
                    $response = $response .'&w='.$totalWin;
                }
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

                $str_g = "";
                if($arr_g != null){
                    $str_g =',"Str_g":' . json_encode($arr_g);
                }
                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"LastFsmore":' . $slotSettings->GetGameData($slotSettings->slotId . 'LastFsmore') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'). ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')  . $str_g . ',"Trail":"' . $str_trail . '","Stf":"' . $str_stf . '","InitReel":"' . $str_initReel  . '","BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($slotEvent['slotEvent'] == 'freespin' && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * 100;
                }
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if($isCalcWinLine == true && $scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin') 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                }

            }
            if($slotEvent['action'] == 'doSpin' || $slotEvent['action'] == 'doCollect' || $slotEvent['action'] == 'doBonus' || $slotEvent['action'] == 'doCollectBonus'){
                $this->saveGameLog($slotEvent, $response, $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'), $slotSettings);
            }
            try{
                $slotSettings->SaveGameData();
                \DB::commit();
            }catch (\Exception $e) {
                $slotSettings->InternalError('RiseofSamurai3DBCommit : ' . $e);
                \DB::rollBack();
                $response = 'unlogged';
            }
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

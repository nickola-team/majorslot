<?php 
namespace VanguardLTE\Games\MysticChiefPM
{
    include('CheckReels.php');
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
            $credits = $userId == 1 ? $request->action === 'doInit' ? 5000 : $user->balance : null;
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
            if( $slotEvent['slotEvent'] == 'update' ) 
            {
                $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"' . $slotSettings->GetBalance() . '"}';
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
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [13,4,8,9,13,5,10,3,6,7,4,8,10,7,6,12,8,9,11,4]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'WildReels', []);
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
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
                    $bet = '100.00';
                }
                $currentReelSet = 0;
                $spinType = 's';
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 )
                {

                    $_obf_StrResponse = '&fs=' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&w=0.00&fsmul=1&is='. $lastReelStr;
                    $currentReelSet = 6;
                }
                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=13,4,8,9,13,5,10,3,6,7,4,8,10,7,6,12,8,9,11,4&balance='. $Balance .'&nas=13&cfgs=1&ver=2&index=1&balance_cash='. $Balance .'&def_sb=4,12,4,7,8&reel_set_size=12&def_sa=10,3,5,3,7&reel_set='.$currentReelSet.$_obf_StrResponse.'&balance_bonus=0.00&na='. $spinType.'&scatters=1~0,0,0,0,0~8,8,8,0,0~1,1,1,1,1,1&gmb=0,0,0&rt=d&gameInfo={rtps:{regular:"96.55"},props:{max_rnd_sim:"1",max_rnd_hr:"1000612",max_rnd_win:"5000"}}&wl_i=tbm~5000&stime=' . floor(microtime(true) * 1000) .'&sa=10,3,5,3,7&sb=4,12,4,7,8&reel_set10=12,10,3,11,9,8,5,7,4,6,4,8,9,8,10,8,10,3,10,3,5,7,9,3,9,8,10,7,8,7,8,10,4,8,4,6,4,10,8,6,4,6,8,10,9,7,9,8,5,10,3,10,7,4,5,9,3,5,8,4,8,4,9,3,10,4,9,10,4,8,4,3,4,7,4,6,4,8,3,8,4,8,4,3,6,10,3,10,8,4,8,4,8,9,6,10,8,4,10,11,3,4,8,3,4,6,8,3,8,4,8,9,4,3,8,9,8,9,8,4,8,4,3,4,9,8,4,10,6,4,9,10,9,8,10,4,10,8,11,3,4,8,9,8,3,4,10,6,10,4,11,8,3,10,9,8,3,9,11,4,8,10,6,10,6,3,6,8,4~7,4,10,12,11,9,6,8,3,5,8,11,3,11,5,3,11,6,9,11,5,11,4,9,10,11,4,11,5,3,10,11,12,9,10,6,3,5,4,3,8,9,11,8,11,6,5,4,5,11,12,6,5,11,10,6,3,12,9,10,5,11,3,11,5,11,9,8,11,5,3,11,10,9,11,9,10,8,11,6,3,10,6,10,6,11,6,4,3,6,12,3,11,9,6,12,4,9,8,9,10,9,3,9,4,9,12,3,9,10,11,4,10,11,9,4,11,4,3,11,9,4,9,10,4,9,8,12,8,5,3,9,5,8,9,4,9~8,3,4,10,12,9,11,6,5,7,4~11,5,9,3,12,10,4,8,7,6,9,12,4,12,9,8,4,3,4,8,4,12,4,12,4,12,9,4,6,4,8,10,4,12,9,12,4,9,6,4,5,12,7,8~10,9,8,4,5,7,6,11,3,12,6,11,6,3,11&sc='. implode(',', $slotSettings->Bet) .'&defc=100.00&reel_set11=11,7,4,9,8,5,10,3,12,6,9,6,9,10,9,10,6,8,7,10,12,9,4,9,8,10,8,10,9,10,9,12,9,10,8,6,10,8,6,10,8,6,10,9,8,10,6,10,9,10,4,9,6,8,5,10,9,10,12,6,9,4,10,6,10,4,8,9,6,12,9,6,4,10,9,10,12,8,10,9,7~11,5,4,10,12,6,9,7,3,8,7,6,4,12,9,7,10,7,4,3,7,5,7,12,9,7,9,7,12,5,9,6,7,12,8,4,9,7,9,4,9,7,9,7,3,4,3,4,3,4,7,8,12,4,9,6,9,4,9,7,9,10,12,9,3,4,12,9,12,3,5,4,9,12,4,9,12,3,4,8,9,5,4,9,12,7,6,9,12,5,7,3,9,6,5,9,4,9,4,7,3,4,7,9,4,7,9,6,9,4,9,7,3,9,7,3,4,7,8,12,7,3,4,7,9,3,7,8,9,4,8,9,7,9,4,9,5,4,6,7,9,7,3,9,6,4,7,4,3,4,9,10,3,7,12,7,9~5,3,10,7,8,9,11,4,6,12,4,9,4,9,11,9,4,6,4,11,4,9,4,7,4,6,11,4,9,7,9,3,9,11,7,4,9,4,11,4,6,9,8,4,11,8,4,11,8,6,9,4,11,4,9,11,6,11,4,6,9,4,9,11,7,4,9,11,4,8,7,11,8,11,7,9,4,6,4,9,4,3,8,3,8,4,11,8,4,11,4,8,4,3,11,8,11,4,11,8,6,11,6,4,8,4,11,9,4,11,8,12,11,9,8,9,11,9,3,4,9,8,11,4,11,9,11,9,6,11~8,12,7,3,6,10,4,9,11,5,12,4,7,12,3,4~12,9,6,3,8,7,5,10,4,11,10,9,11,10,11,3,10,11,4,7,3&sh=4&wilds=2~0,0,0,0,0~1,1,1,1,1;14~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;100,20,5,0,0;60,12,4,0,0;50,12,4,0,0;40,10,3,0,0;30,10,3,0,0;25,7,2,0,0;25,7,2,0,0;20,5,1,0,0;20,5,1,0,0;20,5,1,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0&l=20&rtp=96.55&reel_set0=11,7,8,15,10,5,9,6,4,3,12,15,12,8,15,12,3,6,3,6,4,15,6,15,10,15,3,8,15,4,15,3,4,8,6,8,6,12,4,15,12,15,8,15,5,8,6,3,8,6,3,8,15,6,15,4,3,8,3,8,5,8,4,15,8,12,3,6,8,12,3,6,5,8,15,5,12,8,6,3,6,8,15,6,15,8,6,4,5,6,8,4,6,12,8,6,9,6,8,15,3,5,6,4,6,15,4,15,8,3,6,8,15,4,15,8,6,10,5~9,3,12,7,8,1,4,6,10,5,11,7,5,12,8,10,8,10,8,7,8,11,1,12,8,3,12,1,11,8,3,12,10,7,12,8,12,8,10,12,7,8,12,8,12,7,12,8,12,3,12,3,7,8,4,10,11,12,6,12,8,11,8,7,12,5,12,8,10,7,11,5,6,11,4,12,8,1,8,12,7,1,8,3,8,1,7,12,6,8,10,12,7,10,11,12,1,6,1,3,1,6,8,6,11,12,10,5,10,12,7,6,8,7~8,1,4,12,7,9,3,6,5,11,10,9,4,9,11,9,11,9,10,9,1,6,11,9,11,4,10,9,12,7,9,4,9,12,10,9,4,9,12,9,12,10,9,4,9,10,12,4,11,1,4,9,4,9,10,11,10,4,10,4,9,10,1,6,10,9,11,9,10,11,1,4,10,11,9,11,4,11,9,11,9,4,9,4,9,11,9,4,1,4,10,9,11,9,11,5,12,1,12~12,7,11,8,6,9,3,5,10,1,4,7,6,7,1,9,3,6,7,3,1,7,11,4,3,7,6,3,1,6,7,3,6,3,9,1,11,3,6,3,10,4,6,8,6,8,7,9,11,10,5,7,10,6,4,3,4,3,6,3,7,6,10,4,3,11,6,4,10,11,8,4,11,1,3,11,6,11,9,4,10,3,9,4,6,10,7,3,7,6,7,10,9,6,7,3~5,3,9,12,7,6,15,10,11,8,4,11,7,11,10,4,10,15,6,11,9,4,11,6,9,3,6,11,10,15,11,15,10,9,15,4,3,9,15,4,10,4,9,11,10,11,10,15,6,4,11,6,3,9,10,4,9&s='.$lastReelStr.'&reel_set2=7,10,8,15,12,3,11,9,5,4,6,11,3,15,4,8,11,3,5,11,8,4,15~7,5,9,1,3,11,5,1,9,1,3,1,11,5,11,5,11,5,3,5,3,9,3~4,12,1,8,6,10,8,6,8,6,8,6,8,6,12,6,8,12,8,6,8,12,6,12,6,12,6,1,12,8,1,8,6,1,6,1,12,6,1,8,6,12,8,1,8,1,8,6,1,6,8,12,1,8,12,1,8,6,12,8,1,8,6,8,12,8,6,1,6,8,12,6,1,12,1,6,8,1,8,1,12,6,1,6,12,6,12,1,6,8,12,6,12,6,8,10,8,6,12,6,8,6,1,12,6,8,6,12,1,12,8,6,8,12,6,8,1,8,12,6,12,8,6,1,6,12,6,8,12,8,6,8,1,8,1,6,8,12,1,6,8,6,10,1,6,8,1,8,1,6,8,12,6,8,6,12,6,8,10,1,8,12,6,8,1,6,8,1,12,1,6,12,8,1,12,6,8,1,6,1,6,8,1,6,1,8,6,1,6~1,12,3,6,10,11,7,8,4,9,5,3,10,12,11,6,3,7,12,6,8,6,12,11,10,11,3,12,3,8,12,11,10,3,8,11,3,12,6,8,11,7,3,11,10,5,11,10,6,12,3,11,7,3,12,8,10,5,9,11,8,3,11,3,9,3,10,12,10,6,11,8,3,11,9,11,12~8,9,10,4,7,12,5,3,6,11,3,11,10,12&t=243&reel_set1=3,11,7,4,10,5,6,15,12,9,8,4,10,11,12,11,8,4,7,5,10,9,11,6,4,10,11,9,7,5,8,11,4,11,4,10,6,10,6,11,8,11,10,5,9,6,4,11,5,6,8,4,9,6,7,5,6,9,8,4,9,5,12,4,11,7,4,5,10,4,6,11,10,11,9,15,8,4,11,10,5,4,9,6,11,6,10,4,7,10,11,10,11,6,5,9,4,6,4,7,11,4,11,7,10,6,11,5,10,11,5,10,11,5,15,11,10,6,11,10,8,6,5,12,6,11,6,5,11,8,11,15,10,9,11,8,11,10,11,5,11,6,4,5,7,6,4,6,5,6,9,11,6,4,11,5,11,6,7~6,8,10,1,4,12,8,12,8,10,1,12,8,1,8,1,10,1,8,1,10,12,8,1,8,12,8,10,1,12,10,8,1,10,8,12,10,1,12,10,12,8,1,10,1,12,1,10,12,8,10,1,12,10,1,10,12,1,12,10,12,10,1,12,1,12,10,1,10,1,8,10,12,1,8,4,10,1,12,1,10,1,10,1,12,1,4,12,1,12~7,9,5,1,11,3,11,5,9,5,3,5,9,11,9,3,11,9,5,9,3,5,9,11,3,5,1,3,9,11,9,11,3,11,9,3,9,1,11,3,11,9,5,9,3,11,3,11,9,3,9,5,3,5,3,11,9,3,9,11,3,5,9,11,9,3,9,3,5,3,5,3,11,9,3,9,3,9,3,1,3,11,3,11,5,9,3,11,3,9,11,3,11,9,5,1,9,11,9,5,3,9,5,3,9,1,11,9,3,9,5,11,9,11,5,3,9,11,9,3,9,3,11,5,3,5,11,3,5,3,11,3,11,5,3,9,3,9,3,9,3,5,3,9,11,9,5,3,9,1,3,9,3,9,5,1,11,9,5,3,5,11,1,5,11,5,11,1,3,5,9,1,5,3,9,11,5,9,11,3,1,5,11,9,3,5,11,3,9,11,3,9,3,1,5,9,5,11,3,9,1,3,5,11,3,5,3,5,11,9,3,11,3,5,11~9,5,3,10,12,6,1,4,11,7,8,3,10,4,6,1,3,4,12,1,3,11,6,7,3,6,12,7,6,1,3,12,1,6,7,3,6,12,6,7,6,12,6,3,1,12,1,7,1,12,3,4,3,7,6,3,1,7,10,4,6,3,1,4,1,4,12,1,6,7,12,6,1,12,6,1,4,1,6,3,12,7,1,7,1,7,3,11,12,3,12,1,7,1,6,1,7,4,8,4~3,8,5,9,6,11,12,10,7,4,12,7,5,10,5,10,8,9,8,10,6,8,10,8,10,5,8,10,6,10,6,7,10,9,12,10,7,10,12,6,7,9,5,8,10,12,10,8,7,4,9,12,6,8,10,7,12,9,10,8,5,10,9,12,10,6,10,8,7,4,10,12,10,6,12,5,11,8,5,6,10,6,7,10,8,12,5,10,12,5,7,5,10,8,12,10,8,12,9,7,10,8,9,8,9,5,8,9,10,9,10,5,12,5,8,5,8,7,10,5,10,8,12,8,12,10,6,8,6,5,10,8,10,7,10,5,8,10,7,9,10,5,10&reel_set4=9,5,4,8,10,15,11,6,3,7,12,3,7,15,7,10,7,15,12,15,11,3,5,10,12,15,12,4,15,7,4,3,4,7,12,7,4,5,12,7,5,11,12,7,11,15,7,5,7,5,7,5,3,7,4,8,4,5,3,4,5,7,12,5,12,8,7,12,15,5,4,7,3,7,12,7,15,5,6,8,7,15,5,7,8,15,7,11,7,4,7,4,11,5,7,12,4,7,12,15,5,12,6,7,5,8,4,11,15,12,8,4,15,4,3,7,15,4,15,5,15,7,8,11,12,4,12,15,8,7,5,7,5,7,5,12,10,8,12,15,7,15,12,11,8,7,11,12,4,5,15,5,7,8,12,5,7,4,15,6,5,12,4,15,12,5,12,8,4,5,12,3,12,8,7,5,12,8,12,5,15~4,11,5,10,6,8,12,9,7,3,9,3,12,8,5,3,6,3,9,12,9,10,9,5,12,8,9,8,3,6,8,12,8,9,12,9,6,8,9,6,8,9,12,6,8,9,12,6,8,9,10,6,8,3,12,8,11,6,10,5,8,12,8,10,5,11,10,12,10,9,6,10,3,7,10,11,6,12,9,6,10,6,8,6,10,9,11,12,10,6,3,7,9,8,11,8,9,8,12,8,6,11,8,9,8,11,8,12,10,11,9,8,6,8,9,10,11,8,11,9,11,8,9,8,9,8,9,10,8,12,6,8,10,8,11,6,12,7,9,11,8,12,11,8,12,3,10,5,10,12,3,11,10,6,8,3,12,8,10,6,3,6,5,8,9,11,9,12,6,3,8,6,12,11,7,8,12,11,7,8,10,9,6,8,12,10,8,6,8,6,5,11,12,8,6,10,8,9,10,8,12,3,7,10,5,11,10,3,8,10,6,12,3,8,3,6,8,6,8,11,10,12,6,12,10,12,6,10,3,8,10,8,6,12,7,10,3,10,12,9,10,9,11,10~5,12,6,3,10,11,9,4,7,8,10,6,10,6,10,6,11,10,6,11,6,3,9,10,3,10,11,6,3,10,3,11,10,11,12,10,11,9,3,10,3,9,12,11,6,10,11,6,11,10,6,10,9,3,10,12,6,10,3,6,3,9,12,10,7,4,10,6,3,12,10,3,11,12,11,9,12,11,3,11,3,10,12,6,9,4,10,12,9,11,12,3,10,9,12,10,6,12,8,3,6,10,9,6,3,12,3,11,9~12,3,5,11,6,8,9,4,7,10,6,10,8,4,6,10,4,10,7,10,6,11,6,4,6,7,8,4,6,4,6,8,6,4,8,6,4,6,4,6,7,4,10,8,10,6,4,5,9,4,6,9,10,4,10,6,4,7,10,6,4,11,6,10,6,10,6,8,6,10,6,10,6,8,6,4,6,10,4,6,8,9,6,7,6,4,7,6,10,4,7,6,8,4,6,7,8,4,6,4,6,7,9,4,6,7,8,6,9,4,8,9,6,8,4,5,9,6,9,10,4,9,6,4,5,6,10,6,10,7,4,8,9,5,4,6,9,6,4,10,4,8,5,6,9,10,6,8,11,10,4,6,4,6,9,6,8,7,4,8,10,4,5,4,7,4,9,10,6,10,6,4,6,4,9~5,6,11,9,8,3,12,15,7,4,10,15,6,3,4,8,3,8,3,12,10,12,7,4,12,15,12,10,12,8,3,4,7,4,3,12,3,7,15,3,10,6,10,12,3,6,15,4,7,3,12,3,7,6,9,3,8,4,8,7,12,15,3,12,15,7,12,3,4,9,15,6,8,6,9,15,4,8,12,7,6,3,12,3,12,15,12,8,3,12,3,12,3,9,4,3,8,7,15,7,6,8,3,11,3,12,8,6,12,3,11,8,15,9,3,12,7,15,3,8,3,15,3,12,7,11,7,12,9,8,6,4,7,10,3,4,9,15,3,9,7,15,4,6,15,11,3,15,6,8,3,8,7,12,4,9,10,6,3,12,4,7,15,6,3,7,6,9,7,11,8,10,12,15,4,8,12,8,7,12,3,8,15,4,8,3,6,3,12,9,10,9,8,7,8,3,7,15,4,11,15,7,12&reel_set3=3,6,12,5,10,8,7,4,11,9,15,6,8,6,7,6,4,10,5,12,15,8,5,8,5,10,15,5,8,12,5,8,9,5,7,10,5,12~7,9,1,11,10,4,12,3,6,8,5,11,12,1,3,11,12,4,12,8,12,4,12,5,9,12,9,12,8,12,3,1,5,12,6,12,3,9,12,3,9,12,6,12,3,12,3,12,3,9,3,5,12,1,4,12,5,12,1,3,12,6,10,4,12,3,8,9,12,10,8,1,10,1,3,9,12,4,6,8,12,6,8,9,5,3,5,12,1,9,8,5,4,5,12,5~4,7,10,6,9,5,12,3,8,11,1,3,11,3,7,3,1,7~9,7,6,11,5,10,3,8,12,4,1,6,1,12,7,5,8,10,4,5,1,11,7,11,10,5,10,4,11,5,4,1,5,8,12,6,5,1,11,6,11,10,5,1,11,4,6,1,8,1,5,8,1,10,1,11,5,1,4,7,5,4,1,11,6,5,10,4,1,4,1,10,11,1,12,11,5,8,5,8,5,8,1,11,1,6,5,11,5,10,5,6,7,4,11,12,4,7,10,5,10,1,8,6,5,1,6,5,1,12,10,11,1,5,11,1,8,5,1,7,1,5,6,5,6,12,6,8,5,6,1,8,5,10,5,4,5,10,5,10,8,1,6,7,4,11,5,12,8,11,1,8,4,5,8,5,1,5,8,11,8,5,1,4,1,11,1,5,12,8,11,1,7,4,6,10,5,11,1,5,6,11,6,7,1,11,1,7,5,1,11,1,5,12,4,6,5,11,8,5,7,1,7,4,5,1,5,10,5,11~4,7,15,12,11,3,6,10,9,5,8,12,5,8,5,3,6,3,12,6,12,9,12,3,6,3,12,3,12,3,9,3,12,6,12,3,9,10,3,6,12,6,8,3,6,8,12,9,3,6,12,8,3,8,6,3,12,3,9,6,3,9,12,9,6&reel_set6=11,15,12,8,9,10,4,6,5,3,7,15,8,6,10,15,12,4,6,3,8,15,3,5,6,4,3,15,7,8,15,12,3,8,6,12,6,4,15,5,6,5,3,6,15,3,6,4,6,15,8,15,8,15,5,15,8,4,6,3,8~3,11,5,7,9,5,9,5,9,11,9,5,9,11,9,5,9,11,9,5,11,5,9,5,9,11,5,9,5,9,11,5,9,11,5,11,5,9,5,9,5,9,11,7,5,7,11,5,9,11,9,5,9,5,7,5,11,5,9,11,5,9,11,9,11,9,5,9,5,9,11,9,11,5,9,5,9,11,9,11,9,11,5,9,5,9,11,9,5,11,5,9,11,9,11,9,11,5,7,11,9,11,5,11,9,5,9,5,11,9,11,5,9,7,9,5,11,9,5,11,7,5,9,11,9,11,5,11,5,9,11,5,11,9,11,9,11,5,7,9,11,5~4,10,6,12,8,10,8,10,12,10,6,8,10,8,10,8,6,10,8,10,6,8,6,10,8,6,10,8,6,8,6,10,8,10,6,8,10,6,10,12,10,6,12,8,10,8,10~6,5,3,11,4,7,8,12,10,9,8,5,7,3,12~5,4,7,9,10,11,8,12,6,3,6,4,8,4,8,3,10,6,10,6,3,12,10,6,10,3,4,6,12,10,6,10,12,4,6,8,3,4,10,12,6,10,3,10,6,3,12&reel_set5=9,10,3,8,4,11,15,5,12,6,7,5,7,15,10,15,5,10,5,12,8,6,10,11,12,8,10,5,6,10,5,12,8,10,5,15,5,15,12,10,8,5,6,5,15,12,7,15,11,15,8,15,6,5,15,8,11,12,11,12,15,12,15,8,3,12,10,11,5,8,15,5,6,5,10,8,10,15,5,10,5,12,8,12,8,6~6,12,8,4,10,8,4,8,10,4,10,4,8,4,12,8,4,12,8,10,4,8,4,8,4,12,4,8,12,8,4,8,10,4,8,4,8,4,8,4,12,8,4,12,8,12,4,8~5,3,7,11,9,7,9,7,3,11,3,9,3,7,3,7,9,3,7,9,3,7,3,7,3,9,7,3,11,9,3,9,11,7,3,7,9,7,3,7,3,7,11,3,9,7,9,7,9,3,7,9,7,9,3,9,7,9,7,3,7,3,7,9,7,9,7,9,3,7,9,7,9,3,7,9,7,9,7,3,9,3,9,3,7,3,7,9,3,9,7,9,7,9,11,9,7,3,9,7,3,9,7,3,9,3,9,11,3,9,3,9,7,9,7,9,3,7,9,7,11,7,9,7,9,7,3,9,3,9,7,9,7,3,7,9,11,3,7,3,9,7,9,7,3,7,3,7,3,7,3,9,3,7,9,3,9,7,9~9,3,6,5,4,12,7,11,8,10,4,6,5,3,6,11,6,3,6,11,8,4,8,11,7,4,6,7,11,6,8,6,4,8,3,8,4,3,11,6,4,11,3,8,3,8,7,11,3,7,4,3,6,4,3,6,3,11,8,11,6,4,11,8,3,11,3,8,4,3,5,11,6,7,6,4,7,6,10,6,3,4,3,11,7,4,11,3,8,12,8,11,4,3,4,11,3,11,4,8,11,3,4,11,3,8,11,4,3,4,3,11,6,3,11,4,11,3,4,3,8,6,3,8,7,3,8,3,6,4,3,11,4,11,4,10,4,8,3,8,4,6,4,3,4,11,8,4,8,11,7,3,11,4,7,4,11,8,10,3,5,7,6,11,6,11,3,4,6,3,4,3,6,8,4,5,11,4,8,4,7,8,11,3,4,3,6,3,6,4,3,11,3,6,4,3,8,6,7,4,8,11,6,10,3,11,4,8,4,6,3,6,3,11,4~10,8,4,6,11,12,5,7,3,9,7,9,5,4,5,12,11,5,4,12,5,6,4,12,11,12,9,4,5,12,5,4,12,4,11,12,9,12,4,11,12,11,12,7,4,12,4,9,12,5,11,6,11,7,5,4,11,9,11,12,3,5,12,4,12,11,9,3,12,6,12,11,5,9,6,12,9,6,9,4,12,11,9,12,5,12,5,9,5,12,11,12,7,5,11,5,6,9,4,12,11,5,4,12,9,12,11,12,9,6,12,6,12,9,12,9,12,4,12,11,5,4,7,5,11,5&reel_set8=11,9,15,6,7,8,12,10,3,4,5,12,5,6~3,12,4,6,9,11,7,8,5,10,11,8,11,4,10,12,7,10,4,7,6,12,10,12,5,12,6,7,12,4,6,7,8,10,7,4,7,12,4,10,11,7,5,4,7,4,12,7,4,10,4,7,10,4,12,8,5,10,7,8,4,11,6,11,4,7,4,10,12,6,12,4,12,4,10,11,5,4,7,10,7,10,5,9,11,7,11,9,12,7,11,4,7,11,6,5,4,11,7,5,7,10,12,10,11,5,10,4,11,6,7,12,11,12,11,10,12,8,7,6,10,5,10,7,10,11,12,5~6,3,9,5,7,12,8,11,10,4,10,3,11,10,3,9,5,9,11,3,10,11,9,11,5,3,5,3,5,3,8,10,11,12,11,10,3,5,3,11,3,8,11,5,11,8,10,8,10,9,5,12,8,7,11,8,9,8,5,11,3,11,5,11,4,5,8,3,11,3,9,5,10,7,12,10,11,3,5,3,8,11,10,11,12,7,9,5,9,10,9,8,5,3,11,3,8,10,8,3,9,7,11,8,10,11,7,5,12~11,6,9,3,8,7,12,4,5,10,12,9,8,4,3,7,12,3,12,5,3,8,6,12,3,6,5,6,3,12,9,5,6,12,5,3,4,3,4,5,12,6,12,5,12,3,4,7,12,5,4,12,5,6,5,10~3,15,7,8,4,9,11,6,12,10,5,15,4,12,9,4,5,11,4,7,8,9,15,10,15,12,5,7,11,10,7,4,8,6,9,5,11,7,9,7,4,12,11,7,11,10,7,15,7,9,4,5,7,5,7,5,6,5,6,8,15,9,12,15,6,4,5,9,5,11,5,7,5,7,5,4,8,7,9,15,4,15,9,12,4,7,11,9,7,6,7,4,12,5,4,7,15,7,6,7,10,7,12,6,10,5,15,4,15,4,7,11,12,6,4,5,4,8,7,11,5,11,6,5,10,12,7,5,11,4,5,9,8,5,15,5,15,7,11,7,9,5,15,10,5,12,10,5,9,11,8,5,7,8,12,11,7,4,5,10,7,5,10,4,6,8,15,5,10,11,12,8,5,7,5,8,12,6,9,11,10,15,5,10,5,11,9,8,4,6,5,15,8,12,6,4,5,10,7,9,8,12,7,9,8,7,11,10,4,11,9,4,11,9,10,6,12,7,4,6,10,4,9,6,7,11,4,8,7,15,4,12,10,11,10,8,11,9,4,5,15,9,4,11,15&reel_set7=10,12,15,8,4,6,9,5,3,11,7,6,9,3,7,4,15,7,9,7,9,4,7,8,12,6,12,6,7,15,9,11,7,8,9,7,15,9,12,9,7,4,12,15,8,4,6,9,6,8,3,7,9,4,7,4,7,9,6,9,6,12,8,6,9,8,4,6,9,6,3,8,15,7,12,9,7,8,9,8,4,12,7,9,5,12,7,9,7,4,9,7,8,6,4,9,8,7,4,15,8,7,3,4,6,12,8,7,12,7,11,4,7,9,3,7,4,9,7,3,6,12,6,7,5,4,9,3,9,8,9,3,8,4,6,7,6,3,4,9,6,4,3,8,4,15,4,7,6,3,8,7,4,9,8,3,9,6,4,9,3,9,3,7,8,9,4,7,12,4,8,6~12,10,8,6,9,11,5,4,3,7,11,6,11,4,11,8,10,3,6,3,11,9,11,4,3,11,3,9,3,7,6,11,4,3,4,5,11,3,4,11,3,6,11,9,11,9,11,6,11,4,9,11,4,11,6,11,3,11,4,10,11,3,4,10,11,4,3,6,11,4,5,8,11,5,11,10,4,3,11,6,3,4,11,9,8,11,10,11,4,3,6,9,3,9,11,3,11,6,11,6,11,4,10,4,3,11,6,11,9,11,9,6,11,3,10,8,6,9,5,3,11,10,3,6,11,6,4,11,7,6,11,10,4,6,3,11,9,11,3,10,4,9,11,3,4,7,11,6,9,3,11,3,11,3,6,11,3,7,6,4,6,4,7,4,6,8,4,7,3,7,3,10,11,4,9,4,9,6,11~5,9,11,4,10,3,6,12,7,8,3,9,3,11,9,3,7,10,9,7,10,3,10,3,8,10,3,7,9,11,3,6,11,3,10,3,8,9,10,9,7,6,9,3,9,12,7,12,11,9,12,10,3,11,3,8,3,11,3,7,9,12,11,7,3,10,8,11,9,3,9,12,9,3,11,3,8,9,11,10,3,11,10,3,9,3,10,9,10,7,3,10,11,9,3,11,9,10,3,11,8,7,3,4,9,7,10,3,9,7,11,9,12,11,3,7,10,3,12,10,8,9,11,10,11,10,7,9,11,3~3,9,8,11,10,12,4,5,7,6,5,8,11,5,6,11,12,6,8,5,10,9,6,9,6,5,6,9,7,12,11,6,9,8,9,12,9,11,4,12,6,11,12,6,5,8,5,8,4,8,10,7,11,8,12,6,9,12,6,5,12,11,12,5,6,9,8,12,6,7,12,8,11,9,12,4,6,12,7,11,7,6,7,12,6,7,6,8,7,5,11,10,4,7,5,9,12,11,7,11,12,5,11,9,11,6,5,8,5,6,7,9,6,12,7,6,11,6,11,5,11,8,12,5,7,10,5,7,6,12,7,5,7,4,12,5,6,4,9,7,12,4,8,12,7,12,6,8,6,12,10,12,8,5,12,5,8,4,5,11,9,11,6,9,12,4,11,5,6,12,8,11,5,11,4,12,4,12,11,12,5,8,5,6,11,10,8,12,7,5,4,11,8,6,11,9,11,12,10,12,9,7,6,7,12,7,5,7,5,9,5,8,11,12,11,5,12,4,8,9,6,10,11,5,6,9,12,7,12,7,6,4,9,6,11,12,11,5,7,10,12,5,12,8,7,12,7,4,8,12,11,7,12~4,3,8,9,15,5,10,7,11,12,6,3,7,11,10,15,7,5,15,3,5,7,6,5,15,3,8,15,3,11,8,5,3,11,15,5,7,6,5,11,8,11,8,15,11,3,7,15,5,15,3,5,15,10,5,7,15,10,11,6,8,11,7,8,15,8,15,5,11,6,7,5,8,11,7,5,15,11,15,5,3,11,10,15,3,5,3,15,5,7,5,15,5,8,6,3,15,11,3,10,11,15,11,7,5,7,5,11,5,11,3,5,11,15,5,15,6&reel_set9=6,5,4,9,11,12,3,8,7,10,9,10,5,10,9,7,5,10,9,10,7,9,7,5,10,12,5,7,10,4,5,9,7,10,7,9,7,10,11,7,10,11,8,9,5,7,11,5,9,5,9,7,5,9,7,5,9,11,10,5,11,9,7,10,9,7,10,5,10,7,10,9,5,9,4,9,10,9,5,7,12,7,10,11,4,12,5,11,7,9,10,5,7,10,5,9,11,12,10,9,12,11,5,7,9,10,9,7,11,9,10,11,4,10,3,9,10,7,10,9,10,5,9,7,10,9,11,10,7,10,11,10,7,9,5,9,7,5,7,9,5,9,7,9,7,10,7,5,9,3,7,11,5,9,10,9,11,5,9,7,9,10,9,5,11,9,10,9~10,6,5,12,4,8,9,11,7,3,12,11,9,11,5,9,3,5,9,4,3,12,11,6,11,5,9,3,6,12,9,11,3,9,8,7,9,8,9,3,5,3,11,12,6,9,5,6,9,5,8,12,3,5,6,8,11,3,9,12,5,11,3,8,5,6,8,3,5,8,12,3,6,3,8,12,7,6,9,5,3,4,12,3,8,12,9,3,11,6,3,6,11,3,9,5,3,6,8,6,5,12,6,3,6,11,8,9,3,5,3,5~8,11,10,3,6,4,12,7,9,5,10~5,8,9,4,6,7,10,11,3,12,3,6,3,7,6,9,11,6,12,6,8,12,6,11,6,7,12,6,3,7,6,3,12,9,6,12,6,7,6,11,7,4,6,7,9,8,6,10,4,6,9,6,8,9,11,6,7,6,9,11,6,9,4,6,8,6,4,6,10,12,7,4,9,8,3,6,9,6,4,3,4,3,4,6,11,10,8,3,4,12,4,8,9,3,11,10,9,8,6,12~9,11,3,12,7,4,5,8,6,10,5,7,12,8,7,10,3,7,3,7,10,6,8,12,11,7,3,8,3,10,7,3,4,7,11,7,8,7,10,7,10,4,8,7,4,7,5,7,10,5,4,7,10,3,6,7,12,3,7,3,7,3,7,11,7,5,11,10,5,11,7,11,7';
            }
            else if( $slotEvent['slotEvent'] == 'doCollect' || $slotEvent['slotEvent'] == 'doCollectBonus') 
            {
                $Balance = $slotSettings->GetBalance();
                $response = 'balance=' . $Balance . '&index=' . $slotEvent['index'] . '&balance_cash=' . $Balance . '&balance_bonus=0.00&na=s&stime=' . floor(microtime(true) * 1000) . '&na=s&sver=5&counter=' . ((int)$slotEvent['counter'] + 1);
                
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
                $allbet = $betline * $lines;
                $totalWin = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if($replayLog && count($replayLog) && $totalWin > $allbet){
                    $current_replayLog["cr"] = $paramData;
                    $current_replayLog["sr"] = $response;
                    array_push($replayLog, $current_replayLog);

                    \VanguardLTE\Jobs\UpdateReplay::dispatch([
                        'user_id' => $userId,
                        'game_id' => $slotSettings->game->original_id,
                        'bet' => $allbet,
                        'brand_id' => config('app.stylename'),
                        'base_bet' => $allbet,
                        'win' => $totalWin,
                        'rtp' => $totalWin / $allbet,
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
                        $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"invalid balance"}';
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
                $freeStacks = []; // free stacks
                $isGeneratedFreeStack = false;
                $isForceWin = false;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    $bonusMpl = $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl');
                    $leftFreeGames = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') - $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame'); 
                    
                    // free stacks
                    // if($slotSettings->happyhouruser){
                        $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                        if(count($freeStacks) >= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames')){
                            $isGeneratedFreeStack = true;
                        }
                    // }
                    if($leftFreeGames <= mt_rand(0 , 1) && $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') == 0){
                        $winType = 'win';
                        $_winAvaliableMoney = $slotSettings->GetBank($slotEvent['slotEvent']);
                        $isForceWin = true;
                    }
                }
                else
                {
                    $slotEvent['slotEvent'] = 'bet';
                    $slotSettings->SetBalance(-1 * ($betline * $lines), $slotEvent['slotEvent']);
                    $_sum = ($betline * $lines) / 100 * $slotSettings->GetPercent();
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $_sum, $slotEvent['slotEvent']);
                    $bonusMpl = 1;
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '275' . substr($roundstr, 4, 7);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $slotSettings->SetGameData($slotSettings->slotId . 'WildReels', []);
                    $leftFreeGames = 0;

                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'RegularSpinCount') + 1);
                }
                
                $Balance = $slotSettings->GetBalance();
                if( $slotEvent['slotEvent'] == 'bet' ) 
                {
                    $slotSettings->UpdateJackpots($betline * $lines);
                    $currentReelSet = 0;
                }else{
                    $currentReelSet = 4;
                }
                for( $i = 0; $i <= 2000; $i++ ) 
                {
                    $totalWin = 0;
                    $this->winLines = [];
                    $lineWins = [];
                    $lineWinNum = [];
                    $wild = '2';
                    $scatter = '1';
                    $bonus = '15';
                    $_obf_winCount = 0;
                    $strWinLine = '';
                    $isSameWildReel = false;
                    // freeStack
                    if($isGeneratedFreeStack == true){
                        $rw_poses = [];
                        $freeStack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 2];
                        $reels = $freeStack['Reel'];
                        $initReels = $freeStack['initReel'];
                        for($r = 0; $r < 5; $r++){
                            $initReels['reel' . ($r+1)][-1] = mt_rand(7, 12);
                            $initReels['reel' . ($r+1)][4] = mt_rand(6, 12);
                        }
                        $bonusCount = 0;
                        for($r = 1; $r <= 5; $r++){
                            for($k = 0; $k < 4; $k++){
                                if($initReels['reel'.$r][$k] == $bonus){
                                    $bonusCount++;
                                    break;
                                }
                            }
                        }
                        $wildReels = $freeStack['WildReels'];
                        $wildMuls = $freeStack['WildMuls'];
                        $wildReels = $freeStack['WildReels'];
                        $str_ep = $freeStack['Str_ep'];                          
                        $isChangedWild = true;
                    }else{
                        $initReels = $slotSettings->GetReelStrips($winType, $slotEvent['slotEvent'], $currentReelSet);
                        if(mt_rand(0, 100) <= 95){
                            $initReels = $slotSettings->CheckDuplicationSymbol($initReels);
                        }
                        $reels = [];
                        if($winType == 'bonus'){
                            $wildReels = [];
                        }else{
                            $wildReels = $slotSettings->GetWildReels($slotEvent['slotEvent']);
                        }
                        $bonusCount = 0;
                        for($r = 1; $r <= 5; $r++){
                            for($k = 0; $k < 4; $k++){
                                if($initReels['reel'.$r][$k] == $bonus){
                                    $bonusCount++;
                                    break;
                                }
                            }
                        }
                        $isChangedWild = false;
                        $wildMuls = [1,1,1,1,1];
                        $rw_poses = [];
                        $str_ep = '';
                        if($bonusCount < 2){
                            $arr_ep_initwilds = [];
                            $arr_ep_wilds = [];
                            if($slotEvent['slotEvent'] == 'freespin'){
                                for($r = 0; $r < count($wildReels); $r++){
                                    $row = mt_rand(0, 3);
                                    $initReels['reel' . ($wildReels[$r] + 1)][$row] = 14;
                                    $wildMuls[$wildReels[$r]] = $slotSettings->getWildMul();
                                    array_push($arr_ep_initwilds, $row * 5 + $wildReels[$r]);
                                }
                            }else{
                                for($r = 0; $r < count($wildReels); $r++){                                
                                    if($winType == 'none'){
                                        $initReels['reel' . ($wildReels[$r] + 1)][mt_rand(0, 3)] = 2;
                                    }else{
                                        $row = mt_rand(0, 3);
                                        $initReels['reel' . ($wildReels[$r] + 1)][$row] = 14;
                                        $wildMuls[$wildReels[$r]] = $slotSettings->getWildMul();
                                        array_push($arr_ep_initwilds, $row * 5 + $wildReels[$r]);
                                    }                                
                                }
                            }
                            for($r = 1; $r <= 5; $r++){
                                $reels['reel'.$r] = [];
                                $isWild = false;
                                for($k = 0; $k < 4; $k++){
                                    $reels['reel'.$r][$k] = $initReels['reel'.$r][$k];
                                    if($reels['reel'.$r][$k] == 14){
                                        $isWild = true;
                                    }
                                }
                                if($isWild == true){                                    
                                    $isChangedWild = true;
                                    for($k = 0; $k < 4; $k++){
                                        $reels['reel'.$r][$k] = 14;
                                        array_push($arr_ep_wilds, $k * 5 + $r - 1);
                                    }   
                                }
                            }
                            if(count($arr_ep_initwilds) > 0){
                                $str_ep = '&ep=14~' . implode(',', $arr_ep_initwilds) . '~' . implode(',', $arr_ep_wilds);
                            }
                        }else{
                            $poses = $slotSettings->GetBonusWild();
                            for($r = 1; $r <= 5; $r++){
                                $reels['reel'.$r] = [];
                                for($k = 0; $k < 4; $k++){
                                    $reels['reel'.$r][$k] = $initReels['reel'.$r][$k];
                                }
                            }
                            for($r = 0; $r < count($poses); $r++){
                                $col = $poses[$r] % 3;
                                $row = floor($poses[$r] / 3);
                                $reels['reel'.(($col + 2))][$row] = 2;
                                array_push($rw_poses, $initReels['reel'.(($col + 2))][$row] . '~2~' . ($row * 5 + $col + 1));
                            }
                        }
                    }
                    for($r = 0; $r < 4; $r++){
                        if($reels['reel1'][$r] < 13){
                            $this->findZokbos($reels, $wildMuls, 0, $reels['reel1'][$r], 1, '~'.($r * 5));
                        }                        
                    }
                    for($r = 0; $r < count($this->winLines); $r++){
                        $winLine = $this->winLines[$r];
                        $bonusMpl = 1;
                        if($winLine['Mul'] > 0){
                            $bonusMpl = $winLine['Mul'];
                        }
                        $winLineMoney = $slotSettings->Paytable[$winLine['FirstSymbol']][$winLine['RepeatCount']] * $betline * $bonusMpl;
                        $strWinLine = $strWinLine . '&l'. $r.'='.$r.'~'.$winLineMoney . $winLine['StrLineWin'];
                        $totalWin += $winLineMoney;
                    }
                    $oldWildReels = $slotSettings->GetGameData($slotSettings->slotId . 'WildReels');
                    if($slotEvent['slotEvent'] == 'freespin' && count($oldWildReels) == 1){
                        for($r = 0; $r < count($wildReels); $r++){
                            if($wildReels[$r] == $oldWildReels[0]){
                                $isSameWildReel = true;
                                break;
                            }
                        }
                    }
                    $scattersCount = 0;
                    for( $r = 1; $r <= 5; $r++ ) 
                    {
                        for( $k = 0; $k <= 3; $k++ ) 
                        {
                            if( $reels['reel' . $r][$k] == $scatter ) 
                            {
                                $scattersCount++;
                            }
                        }
                    }
                    $freeSpinNum = 0;
                    if( $scattersCount >= 3) {
                        $freeSpinNum = 8;
                    }else if($slotEvent['slotEvent'] == 'freespin' && $bonusCount == 1){
                        $freeSpinNum = 1;
                    }
                    // if($moonCount >= 6){
                    //     break;
                    // }
                    if( $i >= 1000 ) 
                    {
                        $winType = 'none';
                    }
                    if( $i > 1500 ) 
                    {
                        // $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"Bad Reel Strip"}';
                        // exit( $response );
                        break;
                    }
                    if( $scattersCount >= 3 && ($winType != 'bonus' || $bonusCount >= 2)) 
                    {
                    }else if($isGeneratedFreeStack == true){
                        break;  //freestack
                    }
                    else if($scattersCount == 2 && mt_rand(0, 100) < 50){

                    }
                    else if($bonusCount >= 2 && $slotEvent['slotEvent'] == 'freespin'){

                    }
                    else if($isForceWin == true && $totalWin > 0 && $totalWin < $betline * $lines * 10){
                        break;   // win by force when winmoney is 0 in freespin
                    }
                    else if($winType == 'bonus' && $slotSettings->GetGameData($slotSettings->slotId . 'RegularSpinCount') > 450){
                        break;  // give freespin per 450spins over
                    }
                    else if($isSameWildReel == true && mt_rand(0, 100) < 95){
                        
                    }
                    else if( $totalWin <= $_winAvaliableMoney && $winType == 'bonus' ) 
                    {
                        $_obf_0D163F390C080D0831380D161E12270D0225132B261501 = $slotSettings->GetBank('bonus');
                        if( $_obf_0D163F390C080D0831380D161E12270D0225132B261501 < $_winAvaliableMoney ) 
                        {
                            $_winAvaliableMoney = $_obf_0D163F390C080D0831380D161E12270D0225132B261501;
                        }
                        else
                        {
                            break;
                        }
                    }
                    else if( $totalWin > 0 && $totalWin <= $_winAvaliableMoney && $winType == 'win' ) 
                    {
                        $_obf_0D163F390C080D0831380D161E12270D0225132B261501 = $slotSettings->GetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''));
                        if( $_obf_0D163F390C080D0831380D161E12270D0225132B261501 < $_winAvaliableMoney ) 
                        {
                            $_winAvaliableMoney = $_obf_0D163F390C080D0831380D161E12270D0225132B261501;
                        }
                        else
                        {
                            if(($slotEvent['slotEvent'] != 'freespin' && $totalWin < $betline * $lines * 20) || $slotEvent['slotEvent'] == 'freespin'){  // Max Win
                                break;
                            }
                        }
                    }
                    else if( $totalWin == 0 && $winType == 'none' ) 
                    {
                        break;
                    }
                }
                $spinType = 's';
                $isEndRespin = false;
                if( $totalWin > 0) 
                {
                    $spinType = 'c';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }
                $_obf_totalWin = $totalWin;
                if( $freeSpinNum > 0) 
                {
                    
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0 ) 
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freeSpinNum);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                    }
                    else
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $freeSpinNum);
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                }
                $lastTempReel = [];
                for($k = 0; $k < 4; $k++){
                    for($j = 1; $j <= 5; $j++){
                        $lastReel[($j - 1) + $k * 5] = $reels['reel'.$j][$k];
                        $lastTempReel[($j - 1) + $k * 5] = $initReels['reel'.$j][$k];
                    }
                }
                $strLastReel = implode(',', $lastReel);
                $strLastTempReel = implode(',', $lastTempReel);
                $strReelSa = $initReels['reel1'][4].','.$initReels['reel2'][4].','.$initReels['reel3'][4].','.$initReels['reel4'][4].','.$initReels['reel5'][4];
                $strReelSb = $initReels['reel1'][-1].','.$initReels['reel2'][-1].','.$initReels['reel3'][-1].','.$initReels['reel4'][-1].','.$initReels['reel5'][-1];
               
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                
                $strOtherResponse = '';
                if($isChangedWild == true){
                    $trails = [];
                    $totalMul = 0;
                    for($r = 0; $r < count($wildReels); $r++){
                        array_push($trails, 'reel' . ($wildReels[$r] + 1) . '_mul~' . $wildMuls[$wildReels[$r]]);
                        $totalMul = $totalMul + $wildMuls[$wildReels[$r]];
                    }
                    $strOtherResponse = '&trail='. implode(';', $trails) . '&wmt=pr&wmv=' . $totalMul.'&gwm=' . $totalMul;
                }
                
                $slotSettings->SetGameData($slotSettings->slotId . 'WildReels', $wildReels);
                if(count($rw_poses) > 0){
                    $strOtherResponse = $strOtherResponse . '&stf=rw:' . implode(';', $rw_poses);
                }
                if($isChangedWild == true || count($rw_poses) > 0){
                    $strOtherResponse = $strOtherResponse . '&is=' . $strLastTempReel;
                }
                $strOtherResponse = $strOtherResponse . $str_ep;
                $isState = true;
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $currentReelSet = 4;
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $spinType = 's';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $isEnd = false;
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
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
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                    if($bonusCount == 2){
                        $currentReelSet = 0;
                    }else{
                        $currentReelSet = 3;
                    }
                    if($scattersCount >=3 ){
                        $isState = false;
                        $currentReelSet = 0;
                        $spinType = 's';
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1';
                        // FreeStack
                        if($slotSettings->IsAvailableFreeStack() || $slotSettings->happyhouruser){
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $slotSettings->GetFreeStack($betline));
                        }
                    }
                }
                $response = $response . '&w='.$totalWin;
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance.'&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType.$strWinLine.'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=4&c='.$betline.'&sver=5&reel_set='.$currentReelSet.'&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&s='.$strLastReel.'&w='.$totalWin;
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID').',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . 
                    ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $slotSettings->SaveLogReport($_GameLog, $betline * $lines, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if( $scattersCount >= 3 && $slotEvent['slotEvent'] != 'freespin') 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
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
        public function findZokbos($reels, $tempWildReels, $mul, $firstSymbol, $repeatCount, $strLineWin){
            $wild = '2';
            $bPathEnded = true;
            if($repeatCount < 5){
                for($r = 0; $r < 4; $r++){
                    if($firstSymbol == $reels['reel'.($repeatCount + 1)][$r] || $reels['reel'.($repeatCount + 1)][$r] == $wild || $reels['reel'.($repeatCount + 1)][$r] == 14){
                        $addMul = 0;
                        if($reels['reel'.($repeatCount + 1)][$r] == 14 && $tempWildReels[$repeatCount] > 1){
                            $addMul = $tempWildReels[$repeatCount];
                        }
                        $this->findZokbos($reels, $tempWildReels, $mul + $addMul, $firstSymbol, $repeatCount + 1, $strLineWin . '~' . ($repeatCount + $r * 5));
                        $bPathEnded = false;
                    }
                }
            }
            if($bPathEnded == true){
                if($repeatCount >= 3){
                    $winLine = [];
                    $winLine['FirstSymbol'] = $firstSymbol;
                    $winLine['Mul'] = $mul;
                    $winLine['RepeatCount'] = $repeatCount;
                    $winLine['StrLineWin'] = $strLineWin;
                    array_push($this->winLines, $winLine);
                }
            }
        }
    }

}

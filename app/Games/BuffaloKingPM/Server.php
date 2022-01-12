<?php 
namespace VanguardLTE\Games\BuffaloKingPM
{
    include('CheckReels.php');
    class Server
    {
        public $winLines = [];
        public function get($request, $game, $userId) // changed by game developer 
        {
            /*if( config('LicenseDK.APL_INCLUDE_KEY_CONFIG') != 'wi9qydosuimsnls5zoe5q298evkhim0ughx1w16qybs2fhlcpn' ) 
            {
                return false;
            }
            if( md5_file(base_path() . '/app/Lib/LicenseDK.php') != '3c5aece202a4218a19ec8c209817a74e' ) 
            {
                return false;
            }
            if( md5_file(base_path() . '/config/LicenseDK.php') != '951a0e23768db0531ff539d246cb99cd' ) 
            {
                return false;
            }
            $checked = new \VanguardLTE\Lib\LicenseDK();
            $license_notifications_array = $checked->aplVerifyLicenseDK(null, 0);
            if( $license_notifications_array['notification_case'] != 'notification_license_ok' ) 
            {
                $response = '{"responseEvent":"error","responseType":"error","serverResponse":"Error LicenseDK"}';
                exit( $response );
            }*/
            $response = '';
            \DB::beginTransaction();
            // $userId = \Auth::id();// changed by game developer
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
                $Balance = $slotSettings->GetBalance();
                $response = 'balance=' . $Balance . '&balance_cash=' . $Balance . '&balance_bonus=0.00&na=s&stime=' . floor(microtime(true) * 1000);
            }else if( $slotEvent['slotEvent'] == 'doInit' ) 
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 40);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [9,3,11,6,6,11,5,9,11,4,6,12,4,9,9,6,8,11,12,9,9,1,3,13]);
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', 0);
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
                    $bet = $lastEvent->serverResponse->bet;
                    if (isset($lastEvent->serverResponse->ReplayGameLogs)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', json_decode(json_encode($lastEvent->serverResponse->ReplayGameLogs), true)); //ReplayLog
                    }
                    if (isset($lastEvent->serverResponse->RoundID)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    }
                    if (isset($lastEvent->serverResponse->BuyFreeSpin)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);
                    }
                    if (isset($lastEvent->serverResponse->FreeStacks)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', json_decode(json_encode($lastEvent->serverResponse->FreeStacks), true)); // FreeStack
                    }
                }
                else
                {
                    $bet = '50.00';
                }
                $currentReelSet = 0;
                $spinType = 's';
                
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 )
                {
                    $currentReelSet = rand(3, 8);

                    $_obf_StrResponse = '&fs=' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&w=0.00&fsmul=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl');
                }else{
                    $_obf_StrResponse = '';
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                $Balance = $slotSettings->GetBalance();
                
                $response = 'def_s=9,3,11,6,6,11,5,9,11,4,6,12,4,9,9,6,8,11,12,9,9,1,3,13&balance='. $Balance .'&cfgs=1&ver=2&index=1&balance_cash='.$Balance.'&reel_set_size=9&def_sb=3,4,7,6,8,4&def_sa=8,7,5,3,7,5&reel_set='.$currentReelSet.
                    '&balance_bonus=0.00&na='.$spinType.'&scatters=1~0,0,0,0,0,0~100,25,15,8,0,0~1,1,1,1,1,1&gmb=0,0,0&rt=d&stime=' . floor(microtime(true) * 1000) .'&sa=8,7,5,3,7,5&sb=3,4,7,6,8,4&sc='. implode(',', $slotSettings->Bet) .'&defc='.$bet.'&sh=4&wilds=2~0,0,0,0,0,0~1,1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.$_obf_StrResponse.
                    '&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;300,250,200,80,0,0;250,200,100,60,0,0;250,200,100,60,0,0;200,120,80,40,0,0;200,120,80,40,0,0;120,80,40,20,0,0;120,80,40,20,0,0;100,60,30,10,0,0;100,60,30,10,0,0;80,40,20,10,0,0;80,40,20,10,0,0&l=40&rtp=96.06&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=3,3,3,3,8,7,10,9,9,4,11,8,8,10,6,9,1,11,11,5,5,10,12,10,11,6,6,6,4,13,13,7,7,7,12,10,6,13,4,11,11,10,12,5,13,1,12,12,3,8,11,11,10,6,6,9,9,5,5,5,10,10,12,7,7,13,13,11,4,4,4,10,10,9,7,13,13,5,9,8,12,12~12,12,8,9,5,13,13,7,9,10,10,4,4,4,11,13,13,7,7,12,10,10,12,12,6,6,6,13,13,7,11,7,5,8,13,6,9,12,3,3,11,8,8,7,7,7,13,13,5,5,5,6,12,12,4,4,8,8,11,10,7,9,9,10,5,5,11,11,1,9,6,10,8,2,11,4,9,9,7,8,8,3,3,3,3~3,3,3,3,8,8,7,9,9,4,11,6,8,10,6,9,1,11,11,5,5,10,6,6,6,12,10,11,4,13,13,7,7,7,12,10,6,2,4,11,11,10,12,5,13,1,12,12,3,8,11,11,10,6,6,9,9,5,5,5,10,10,12,7,7,13,13,11,4,4,4,10,10,9,7,13,13,5,9,8,12,12~12,12,8,9,5,13,13,7,9,10,10,4,4,4,11,13,13,7,7,12,10,10,12,12,6,6,6,13,13,7,11,7,5,8,13,6,9,12,3,3,11,8,8,13,13,7,7,7,5,5,5,6,12,12,4,4,8,8,11,10,7,9,9,10,5,5,11,11,1,9,6,10,8,2,11,4,9,9,7,8,8,3,3,3,3~3,3,3,3,8,8,7,9,9,4,11,6,8,10,6,9,1,11,11,5,5,10,12,10,11,4,13,13,7,7,7,12,10,6,2,4,11,11,10,6,6,6,12,5,13,1,12,12,3,8,11,11,10,6,6,9,9,5,5,5,10,10,12,7,7,13,13,11,4,4,4,10,10,9,7,13,13,5,9,8,12,12~12,12,8,9,5,13,13,7,9,10,10,4,4,4,11,5,5,5,13,13,7,7,12,10,10,12,12,6,6,6,13,13,7,11,7,5,8,13,6,9,12,3,3,11,7,7,7,8,8,13,13,6,12,12,4,4,8,8,11,10,7,9,9,10,5,5,11,11,1,9,6,10,8,2,11,4,9,9,7,8,8,3,3,3,3&s='.$lastReelStr.
                    '&purInit=[{type:"fs",bet:3000}]&reel_set2=12,12,7,9,5,13,13,7,9,12,3,3,3,3,13,13,13,7,7,12,10,10,5,5,5,9,9,9,7,7,7,9,9,13,13,7,12,7,5,10,13,9,9,12,3,3,10,9,9,13,13,7,12,12,5,5,10,10,10,12,7,9,9,10,5,5,12,12,1,10,7,3,3,3,13,5,9,9,7,10,10,3,3,3,3~5,5,5,8,8,6,6,9,9,11,11,8,8,12,6,9,1,11,11,5,5,9,9,11,12,12,5,8,8,4,9,9,6,6,6,12,12,12,4,4,4,11,11,5,12,8,8,1,9,9,5,8,11,11,12,6,6,9,9,5,5,5,8,8,12,5,5,11,11,11,4,4,4,9,9,9,4,11,11,11,9,8,12,12~3,3,3,3,8,7,10,10,4,11,3,3,3,10,6,13,1,11,11,11,3,3,10,8,8,7,10,11,8,8,4,13,13,7,7,7,13,13,6,10,3,3,3,10,4,4,13,1,11,11,3,8,8,8,10,7,7,7,13,13,6,6,6,10,8,7,7,13,13,11,4,4,4,10,10,10,3,3,3,11,6,8,8,8~12,12,8,9,5,13,13,7,9,10,10,4,4,4,11,13,13,7,7,12,10,10,5,5,5,9,9,6,6,6,6,13,13,7,11,7,5,8,13,6,9,12,7,7,7,3,3,3,3,8,8,11,6,12,12,4,4,8,8,11,10,7,9,9,10,5,5,11,11,1,9,6,10,8,2,11,4,9,9,7,8,8,3,3,3,3~3,3,3,3,8,8,7,9,9,4,11,2,8,10,6,9,1,11,11,5,5,10,9,9,6,6,6,7,10,11,8,8,4,13,13,7,7,7,12,10,6,6,4,11,11,10,12,5,13,1,12,12,3,3,3,3,8,6,6,9,9,5,5,5,10,10,12,7,7,13,13,11,4,4,4,10,10,9,7,13,13,5,9,8,12,12~12,12,8,9,5,13,13,7,9,10,10,4,4,4,11,13,13,7,7,12,10,10,5,5,5,9,9,6,6,6,6,13,13,7,11,7,5,8,13,6,9,12,7,7,7,3,3,3,3,8,8,11,6,12,12,4,4,8,8,11,10,7,9,9,10,5,5,11,11,1,9,6,10,8,2,11,4,9,9,7,8,8,3,3,3,3&t=243&reel_set1=3,3,3,3,8,7,10,10,4,11,3,3,3,10,6,13,1,11,11,3,3,10,8,8,7,10,11,8,6,11,11,11,10,4,13,13,7,7,7,13,13,6,10,3,3,3,10,4,4,13,1,11,11,3,8,8,8,10,7,7,7,13,13,6,6,6,10,8,7,7,13,13,11,4,4,4,10,10,10,3,3,3,11,6,8,8,8~12,12,7,9,5,13,13,7,9,12,3,3,3,3,13,13,13,7,7,12,10,10,5,5,5,9,9,7,10,10,12,12,9,9,9,13,13,7,12,7,5,10,13,9,9,7,7,7,12,3,3,10,9,9,13,13,7,12,12,5,5,10,10,10,12,7,9,9,10,5,5,12,12,1,10,7,3,3,3,13,5,9,9,7,10,10,3,3,3,3~5,5,5,8,8,6,6,9,9,11,11,8,8,12,6,9,1,11,11,5,5,9,9,11,12,12,5,8,6,12,12,12,11,4,9,9,6,6,6,12,12,4,4,4,11,11,5,12,8,8,1,9,9,5,8,11,11,12,6,6,9,9,5,5,5,8,8,12,5,5,11,11,11,4,4,4,9,9,9,4,11,11,11,9,8,12,12~12,12,8,9,5,13,13,7,9,10,10,4,4,4,11,13,13,7,7,12,10,10,5,5,5,9,9,6,11,3,3,10,6,6,6,13,13,7,11,7,5,8,13,6,9,12,3,3,3,3,7,7,7,8,8,11,6,12,12,4,4,8,8,11,10,7,9,9,10,5,5,11,11,1,9,6,10,8,2,11,4,9,9,7,8,8,3,3,3,3~3,3,3,3,8,8,7,9,9,4,11,2,8,10,6,9,1,11,11,5,5,6,6,6,10,9,9,7,10,11,8,6,10,3,3,11,4,13,13,7,7,7,12,10,6,6,4,11,11,10,12,5,13,1,12,12,3,3,3,3,8,6,6,9,9,5,5,5,10,10,12,7,7,13,13,11,4,4,4,10,10,9,7,13,13,5,9,8,12,12~12,12,8,9,5,13,13,7,9,10,10,4,4,4,11,13,13,7,7,12,10,10,5,5,5,9,9,6,11,3,3,10,6,6,6,13,13,7,11,7,5,8,13,6,9,12,3,3,3,3,8,8,7,7,7,11,6,12,12,4,4,8,8,11,10,7,9,9,10,5,5,11,11,1,9,6,10,8,2,11,4,9,9,7,8,8,3,3,3,3&reel_set4=3,3,3,3,10,10,12,6,6,11,11,5,8,8,3,10,10,5,5,5,12,12,1,8,11,6,6,6,11,10,1,8,11,5,12,12,3,3,3,11,11,10,10,10,5,5,8,8,8,11,11,6,6,6,10,12,12~9,9,7,7,7,13,13,4,10,10,9,4,4,4,10,10,12,7,13,13,3,12,12,4,9,9,4,9,13,13,7,7,7,13,13,1,12,12,3,10,10,10,13,12,4,9,9,3,12,12,13,13,3,3,3,3~6,6,6,13,13,5,8,6,6,11,4,4,9,7,7,7,8,6,9,9,5,13,13,1,8,7,9,4,4,4,11,9,1,9,8,7,7,11,4,13,13,7,7,8,8,5,5,5,9,8,8,4,11,11,7,7~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,13,13,3,3,12,12,12,7,8,9,9,4,13,13,6,6,6,11,4,4,4,4,8,1,12,12,5,10,10,3,3,3,11,11,11,6,6,12,13,13,3,3,3,3~3,3,3,3,13,13,12,6,6,11,11,11,3,3,3,10,10,5,12,12,1,8,11,6,6,6,4,4,4,4,10,1,9,8,7,12,12,12,4,3,3,13,13,10,10,5,5,5,9,8,8,2,11,11,7,7,7,9,9~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,13,13,3,3,12,12,12,7,8,9,9,4,13,13,6,6,6,11,8,1,4,4,4,4,12,12,5,10,10,3,3,3,11,11,11,6,6,12,13,13,3,3,3,3&reel_set3=3,3,3,3,13,13,12,6,6,11,11,4,9,8,3,10,10,5,12,12,1,8,11,4,4,4,6,6,6,13,12,12,3,13,13,7,12,10,10,5,5,5,9,8,8,11,11,7,7,7,9,9,13~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,12,7,6,6,6,13,13,3,12,12,4,4,4,7,8,9,1,6,11,8,1,12,12,5,10,10,3,8,9,4,11,11,6,6,12,13,13,3,3,3,3~3,3,3,3,13,13,12,6,6,11,11,4,9,8,3,10,10,5,12,12,1,8,11,6,6,6,4,4,4,13,7,12,12,3,13,13,7,12,10,10,5,5,5,9,8,8,2,11,11,7,7,7,9,9~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,12,7,6,6,6,13,13,3,4,4,4,12,12,7,8,9,1,6,11,8,1,12,12,5,10,10,3,8,9,4,11,11,6,6,12,13,13,3,3,3,3~3,3,3,3,13,13,12,6,6,11,11,4,9,8,3,10,10,5,12,12,1,8,11,6,6,6,13,7,4,4,4,12,12,3,13,13,7,12,10,10,5,5,5,9,8,8,2,11,11,7,7,7,9,9~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,12,7,6,6,6,13,13,3,12,12,7,4,4,4,8,9,1,6,11,8,1,12,12,5,10,10,3,8,9,4,11,11,6,6,12,13,13,3,3,3,3&reel_set6=3,3,3,3,13,13,12,6,6,11,11,4,9,8,3,10,10,5,12,12,1,10,4,4,4,6,6,6,11,10,1,9,8,7,12,12,3,13,13,7,12,10,10,5,5,5,9,8,8,11,11,7,7,7,9,9,13~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,12,7,13,13,3,8,2,10,10,9,4,4,4,13,13,6,6,6,11,8,7,12,12,5,10,10,3,8,9,4,11,11,6,6,12,13,13,3,3,3,3~3,3,3,3,13,13,12,6,6,11,11,4,9,8,3,10,10,5,12,12,2,5,10,6,6,6,4,4,4,11,10,7,9,8,7,12,12,6,3,13,13,7,12,10,10,5,5,5,9,8,8,2,11,11,7,7,7,9,9~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,12,7,13,13,3,8,2,10,10,9,4,4,4,13,13,6,6,6,11,8,2,12,12,5,10,10,3,8,9,4,11,11,6,6,12,13,13,3,3,3,3~3,3,3,3,13,13,12,6,6,11,11,4,9,8,3,10,10,5,12,12,2,6,6,6,5,10,4,4,4,11,10,7,6,9,8,7,12,12,3,13,13,7,12,10,10,5,5,5,9,8,8,2,11,11,7,7,7,9,9~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,12,7,13,13,3,8,2,10,10,9,4,4,4,13,13,6,6,6,11,8,2,12,12,5,10,10,3,8,9,4,11,11,6,6,12,13,13,3,3,3,3&reel_set5=3,3,3,3,10,10,12,6,6,11,11,5,8,8,3,10,10,5,12,12,1,8,11,6,6,5,5,11,10,1,8,11,5,12,12,3,3,3,11,11,10,10,10,5,5,5,8,8,8,11,11,6,6,6,10,12,12~6,6,6,13,13,5,8,6,6,11,4,4,9,7,7,7,8,6,9,9,5,13,13,1,8,11,5,9,4,4,4,11,9,1,9,8,7,7,11,4,13,13,7,7,8,8,5,5,5,9,8,8,4,11,11,7,7~9,9,7,7,7,13,13,3,3,10,10,9,4,4,10,10,12,7,13,13,3,12,12,7,9,10,4,4,4,9,13,13,7,7,7,13,13,1,12,12,3,10,10,10,13,12,4,9,9,7,12,12,13,13,3,3,3,3~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,13,13,3,3,12,12,12,7,8,10,10,9,4,13,13,4,4,4,4,6,6,6,11,8,1,12,12,5,10,10,3,3,3,11,11,11,6,6,12,13,13,3,3,3,3~3,3,3,3,13,13,12,6,6,11,11,11,3,3,3,10,10,5,12,12,1,8,11,6,6,6,4,4,4,4,10,1,9,8,7,12,12,12,4,3,3,13,13,10,10,5,5,5,9,8,8,2,11,11,7,7,7,9,9~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,13,13,3,3,12,12,12,7,8,10,10,9,4,13,13,6,6,6,11,8,1,4,4,4,4,12,12,5,10,10,3,3,3,11,11,11,6,6,12,13,13,3,3,3,3&reel_set8=3,3,3,3,13,13,12,6,6,11,11,4,9,8,3,10,10,5,12,12,7,8,4,4,4,11,10,6,9,8,7,12,12,3,6,6,6,13,13,7,12,10,10,5,5,5,9,8,8,11,11,7,7,7,9,9,13~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,12,7,13,13,3,12,2,10,10,9,4,4,4,13,13,6,6,6,11,8,2,12,12,5,10,10,3,8,9,4,11,11,6,6,12,13,13,3,3,3,3~3,3,3,3,13,13,12,6,6,11,11,4,9,8,3,10,10,5,12,12,2,8,10,4,4,4,11,6,6,6,10,7,9,8,7,12,12,3,13,13,7,12,10,10,5,5,5,9,8,8,2,11,11,7,7,7,9,9~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,12,7,13,13,7,12,2,10,10,9,4,4,4,13,13,6,6,6,11,8,1,12,12,4,10,10,5,8,9,2,11,11,6,6,12,13,13,3,3,3,3~3,3,3,3,13,13,12,6,6,11,11,4,9,8,3,10,10,5,12,12,2,8,10,4,4,4,11,6,6,6,10,7,9,8,7,12,12,3,13,13,7,12,10,10,5,5,5,9,8,8,2,11,11,7,7,7,9,9~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,12,7,13,13,3,12,2,10,10,9,4,4,4,13,13,6,6,6,11,8,2,12,12,5,10,10,3,8,9,4,11,11,6,6,12,13,13,3,3,3,3&reel_set7=3,3,3,3,13,13,12,6,6,11,11,4,9,8,3,10,10,5,12,12,7,8,11,6,6,6,4,4,4,11,10,6,9,8,7,12,12,3,13,13,7,12,10,10,5,5,5,9,8,8,11,11,7,7,7,9,9,13~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,12,7,13,13,7,12,12,3,3,9,10,10,9,4,4,4,13,13,6,6,6,11,8,1,12,12,4,10,10,5,8,9,2,11,11,6,6,12,13,13,3,3,3,3~3,3,3,3,13,13,12,6,6,11,11,4,9,8,3,10,10,5,12,12,2,8,11,6,6,6,4,4,4,11,10,7,9,8,7,12,12,3,13,13,7,12,10,10,5,5,5,9,8,8,2,11,11,7,7,7,9,9~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,12,7,13,13,3,12,12,7,8,9,10,10,9,4,4,4,13,13,6,6,6,11,8,2,12,12,5,10,10,3,8,9,4,11,11,6,6,12,13,13,3,3,3,3~3,3,3,3,13,13,12,6,6,11,11,4,9,8,3,10,10,5,12,12,2,8,11,6,6,6,4,4,4,11,10,7,9,8,7,12,12,3,13,13,7,12,10,10,5,5,5,9,8,8,2,11,11,7,7,7,9,9~9,9,7,7,7,11,11,2,8,8,9,5,5,5,10,10,12,7,13,13,3,12,12,7,8,9,10,10,9,4,4,4,13,13,6,6,6,11,8,2,12,12,5,10,10,3,8,9,4,11,11,6,6,12,13,13,3,3,3,3';
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
                $lines = 40;      
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
                $linesId = [];
                $linesId[0] = [
                    2, 
                    2, 
                    2, 
                    2, 
                    2
                ];
                $linesId[1] = [
                    1, 
                    1, 
                    1, 
                    1, 
                    1
                ];
                $linesId[2] = [
                    3, 
                    3, 
                    3, 
                    3, 
                    3
                ];
                $linesId[3] = [
                    1, 
                    2, 
                    3, 
                    2, 
                    1
                ];
                $linesId[4] = [
                    3, 
                    2, 
                    1, 
                    2, 
                    3
                ];
                $linesId[5] = [
                    2, 
                    1, 
                    1, 
                    1, 
                    2
                ];
                $linesId[6] = [
                    2, 
                    3, 
                    3, 
                    3, 
                    2
                ];
                $linesId[7] = [
                    1, 
                    1, 
                    2, 
                    3, 
                    3
                ];
                $linesId[8] = [
                    3, 
                    3, 
                    2, 
                    1, 
                    1
                ];
                $linesId[9] = [
                    2, 
                    3, 
                    2, 
                    1, 
                    2
                ];
                $linesId[10] = [
                    2, 
                    1, 
                    2, 
                    3, 
                    2
                ];
                $linesId[11] = [
                    1, 
                    2, 
                    2, 
                    2, 
                    1
                ];
                $linesId[12] = [
                    3, 
                    2, 
                    2, 
                    2, 
                    3
                ];
                $linesId[13] = [
                    1, 
                    2, 
                    1, 
                    2, 
                    1
                ];
                $linesId[14] = [
                    3, 
                    2, 
                    3, 
                    2, 
                    3
                ];
                $linesId[15] = [
                    2, 
                    2, 
                    1, 
                    2, 
                    2
                ];
                $linesId[16] = [
                    2, 
                    2, 
                    3, 
                    2, 
                    2
                ];
                $linesId[17] = [
                    1, 
                    1, 
                    3, 
                    1, 
                    1
                ];
                $linesId[18] = [
                    3, 
                    3, 
                    1, 
                    3, 
                    3
                ];
                $linesId[19] = [
                    1, 
                    3, 
                    3, 
                    3, 
                    1
                ];
                $slotEvent['slotBet'] = $slotEvent['c'];
                $slotEvent['slotLines'] = 40;
                $isBuyFreespin = -1;
                if(isset($slotEvent['pur'])){
                    $isBuyFreespin = $slotEvent['pur'];
                }
                
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
                
                $_wildValue = [];
                $_wildPos = [];
                $_wildReelValue = [];
                $reelSet_Num = 0;
                $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $betline * $lines, $lines);
                $winType = $_spinSettings[0];
                $_winAvaliableMoney = $_spinSettings[1];
                $allBet = $betline * $lines;
                if($isBuyFreespin == 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $betline * 3000;
                }
                $isGeneratedFreeStack = false;
                $freeStacks = []; // free stacks
                $isForceWin = false;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    $bonusMpl = $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl');
                    $_wildValue = $slotSettings->GetGameData($slotSettings->slotId . 'WildValues');
                    $_wildPos = $slotSettings->GetGameData($slotSettings->slotId . 'WildPos');
                    $reelSet_Num = rand(3, 8);
                    $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                    if(count($freeStacks) >= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames')){
                        $isGeneratedFreeStack = true;
                    }
                    $leftFreeGames = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') - $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');    
                    if($leftFreeGames <= mt_rand(0 , 1) && $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') == 0){
                        $winType = 'win';
                        $_winAvaliableMoney = $slotSettings->GetBank($slotEvent['slotEvent']);
                        $isForceWin = true;
                    }
                }
                else
                {
                    $reelSet_Num = 0; // rand(0, 2);
                    $slotEvent['slotEvent'] = 'bet';
                    $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                    if($isBuyFreespin == 0){
                        $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $allBet, $slotEvent['slotEvent'], $isBuyFreespin);
                        $winType = 'bonus';
                        $_winAvaliableMoney = $slotSettings->GetBank('bonus');
                    }else{
                        $_sum = $allBet / 100 * $slotSettings->GetPercent();
                        $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $_sum, $slotEvent['slotEvent'], $isBuyFreespin);
                    }
                    $bonusMpl = 1;
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $isBuyFreespin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '275' . substr($roundstr, 4, 7);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'RegularSpinCount') + 1);
                    $leftFreeGames = 0;
                }
                $Balance = $slotSettings->GetBalance();
                if( $slotEvent['slotEvent'] == 'bet' ) 
                {
                    $slotSettings->UpdateJackpots($betline * $lines);
                }
                for( $i = 0; $i <= 2000; $i++ ) 
                {
                    $totalWin = 0;
                    $this->winLines = [];
                    $wild = '2';
                    $scatter = '1';
                    $_obf_winCount = 0;
                    $strWinLine = '';
                    $winLineMuls = [];
                    $winLineMulNums = [];
                    $tempWildReels = [];
                    if($isGeneratedFreeStack == true){
                        $freeStack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 2];
                        $reels = $freeStack['Reel'];
                        $tempWildReels = $freeStack['TempWildReels'];
                    }else{
                        $reels = $slotSettings->GetReelStrips($winType, $slotEvent['slotEvent'], $reelSet_Num);
                        for($r = 0; $r < 6; $r++){
                            $tempWildReels[$r] = [];
                            for( $k = 0; $k < 4; $k++ ) 
                            {
                                if( $reels['reel' . ($r+1)][$k] == $wild) 
                                {                                
                                    if($slotEvent['slotEvent'] == 'freespin'){
                                        $tempWildReels[$r][$k] = $slotSettings->CheckMultiWild();
                                    }else{
                                        $tempWildReels[$r][$k] = 0;    
                                    }
                                }else{
                                    $tempWildReels[$r][$k] = 0;
                                }
                            }
                        }
                    }
                    for($r = 0; $r < 4; $r++){
                        if($reels['reel1'][$r] != $scatter){
                            $this->findZokbos($reels, $tempWildReels, 0, $reels['reel1'][$r], 1, '~'.($r * 6));
                        }                        
                    }
                    for($r = 0; $r < count($this->winLines); $r++){
                        $winLine = $this->winLines[$r];
                        $winLineMoney = $slotSettings->Paytable[$winLine['FirstSymbol']][$winLine['RepeatCount']] * $betline;
                        if($winLine['Mul'] > 0){
                            $winLineMoney = $winLineMoney * $winLine['Mul'];
                            array_push($winLineMuls, $winLine['Mul']) ;
                            array_push($winLineMulNums, $r);
                        }
                        $strWinLine = $strWinLine . '&l'. $r.'='.$r.'~'.$winLineMoney . $winLine['StrLineWin'];
                        $totalWin += $winLineMoney;
                    }                
                    $freeSpinNum = 0;

                    $_obf_scatterposes = [];
                    $_obf_wildposes = [];
                    $_obf_wildValues = [];
                    $scattersCount = 0;
                    $scattersWin = 0;
                    $isWild = false;
                    for( $r = 1; $r <= 6; $r++ ) 
                    {
                        for( $k = 0; $k <= 3; $k++ ) 
                        {
                            if( $reels['reel' . $r][$k] == $scatter ) 
                            {
                                $scattersCount++;
                                array_push($_obf_scatterposes, $k * 6 + $r - 1);
                            }else if( $reels['reel' . $r][$k] == $wild ) 
                            {
                                $isWild = true;
                                array_push($_obf_wildposes, $k * 6 + $r - 1);
                                array_push($_obf_wildValues, $tempWildReels[$r - 1][$k]);
                            }
                        }
                    }
                    if($slotEvent['slotEvent'] == 'freespin'){
                        $freeSpinNum = $slotSettings->freespinCount[1][$scattersCount];
                    }else{
                        $freeSpinNum = $slotSettings->freespinCount[0][$scattersCount];
                    }
                    $totalWin = $totalWin + $scattersWin;
                    // if($freeSpinNum > 0){
                    //     break;
                    // }
                    if( $i >= 1000 ) 
                    {
                        $winType = 'none';
                    }
                    // if( $slotSettings->increaseRTP && $winType == 'win' && $totalWin < ($lines * $betline * rand(2, 5)) ) 
                    // {
                    // }
                    // else if( !$slotSettings->increaseRTP && $winType == 'win' && $lines * $betline < $totalWin ) 
                    // if( !$slotSettings->increaseRTP && $winType == 'win' && $lines * $betline < $totalWin ) 
                    // {
                    // }
                    // else
                    // {
                        if( $i > 1500 ) 
                        {
                            // $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"Bad Reel Strip"}';
                            // exit( $response );
                            break;
                        }
                        if( $scattersCount >= 3 && $winType != 'bonus' ) 
                        {
                        }
                        else if($isBuyFreespin == 0 && $scattersCount < 3){

                        }
                        else if($scattersCount == 6)
                        {
                        }
                        else if($isGeneratedFreeStack == true){
                            break;  //freestack
                        }
                        else if($isForceWin == true && $totalWin > 0 && $totalWin < $betline * $lines * 10){
                            break;   // win by force when winmoney is 0 in freespin
                        }
                        else if($winType == 'bonus' && $slotSettings->GetGameData($slotSettings->slotId . 'RegularSpinCount') > 450){
                            break;  // give freespin per 450spins over
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
                                break;
                            }
                        }
                        else if( $totalWin == 0 && $winType == 'none' ) 
                        {
                            break;
                        }
                    // }
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
                if( $freeSpinNum > 0 ) 
                {
                    
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0 ) 
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freeSpinNum);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                        if($slotSettings->IsAvailableFreeStack() || $slotSettings->happyhouruser){
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $slotSettings->GetFreeStack($betline, $scattersCount - 3));
                        }
                    }
                    else
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $freeSpinNum);
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                }
                $lastTempReel = [];
                for($k = 0; $k < 4; $k++){
                    for($j = 1; $j <= 6; $j++){
                        $lastReel[($j - 1) + $k * 6] = $reels['reel'.$j][$k];
                    }
                }
                $strLastReel = implode(',', $lastReel);
                $strReelSa = $reels['reel1'][4].','.$reels['reel2'][4].','.$reels['reel3'][4].','.$reels['reel4'][4].','.$reels['reel5'][4].','.$reels['reel6'][4];
                $strReelSb = $reels['reel1'][-1].','.$reels['reel2'][-1].','.$reels['reel3'][-1].','.$reels['reel4'][-1].','.$reels['reel5'][-1].','.$reels['reel6'][-1];
               
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);

                $isState = true;
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $spinType = 's';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $isEnd = false;
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                    {
                        $isEnd = true;
                        $spinType = 'c&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                    else
                    {
                        $spinType = 's&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $isState = false;
                    }
                    
                    if($isWild){
                        $spinType = $spinType . '&slm_mp=' . implode(',', $_obf_wildposes) . '&slm_mv=' . implode(',', $_obf_wildValues);
                    }
                    if(count($winLineMuls) > 0){
                        $spinType = $spinType . '&slm_lmi='. implode(',', $winLineMulNums) .'&slm_lmv=' . implode(',', $winLineMuls);
                    }
                    
                    $response = 'tw='. $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&balance='.$Balance.'&index='. $slotEvent['index'] . '&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType.'&reel_set='. $reelSet_Num.
                        $strWinLine .'&stime=' . floor(microtime(true) * 1000).'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=4'.
                        '&c='.$betline.'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=40&s='.$strLastReel.'&w='.$totalWin;
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                    $n_reel_set = $reelSet_Num;
                    if($freeSpinNum > 0 ){
                        $spinType = 's';

                        $n_reel_set = $n_reel_set.'&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fswin=0.00&fs=1'; //&psym=1~' . $scattersWin.'~' . $_obf_scatterposes[0] .'~' . $_obf_scatterposes[1] .'~' . $_obf_scatterposes[2];
                        $isState = false;
                    }
                    if($isBuyFreespin == 0){
                        $n_reel_set = $n_reel_set . '&purtr=1';
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                        $n_reel_set = $n_reel_set . '&puri=0';
                    }
                    $response = 'tw='.$totalWin .'&balance='.$Balance.'&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType.$strWinLine.'&stime=' . floor(microtime(true) * 1000) .
                        '&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=4&c='.$betline.'&sver=5&reel_set='.$n_reel_set.'&counter='. ((int)$slotEvent['counter'] + 1) .'&l=40&s='.$strLastReel.'&w='.$totalWin;
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


                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').  ',"BuyFreeSpin":'.$slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID').',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . 
                    ',"Balance":' . $Balance . ',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $totalWin . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"winLines":[],"Jackpots":""' . ',"ReplayGameLogs":'.json_encode($replayLog).
                    ',"LastReel":'.json_encode($lastReel).'}}';
                if($slotEvent['slotEvent'] == 'freespin' && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                    $allBet = $betline * 3000;
                }
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if( $slotEvent['slotEvent'] != 'freespin' && $scattersCount >= 3) 
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
            if($repeatCount < 6){
                for($r = 0; $r < 4; $r++){
                    if($firstSymbol == $reels['reel'.($repeatCount + 1)][$r] || $reels['reel'.($repeatCount + 1)][$r] == $wild){
                        if($reels['reel'.($repeatCount + 1)][$r] == $wild){
                            $mul = $mul + $tempWildReels[$repeatCount][$r];
                        }
                        $this->findZokbos($reels, $tempWildReels, $mul, $firstSymbol, $repeatCount + 1, $strLineWin . '~' . ($repeatCount + $r * 6));
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

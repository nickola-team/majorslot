<?php 
namespace VanguardLTE\Games\FruitPartyPM
{
    include('CheckReels.php');
    class Server
    {
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
                if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                }
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);                
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [5,9,3,7,7,9,9,9,3,6,6,5,6,9,9,3,7,7,9,9,3,3,6,6,5,6,4,3,3,7,7,9,9,4,6,6,6,5,6,4,5,7,7,7,9,9,4,3,6]);
                $slotSettings->setGameData($slotSettings->slotId . 'BinaryReel', [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]);
                $strtmp = '';
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $lastEvent->serverResponse->tumbWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', $lastEvent->serverResponse->TumbState);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BinaryReel', $lastEvent->serverResponse->BinaryReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);
                    $bet = $lastEvent->serverResponse->bet;
                    $strtmp = $lastEvent->serverResponse->strTmb;
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    if (isset($lastEvent->serverResponse->ReplayGameLogs)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', json_decode(json_encode($lastEvent->serverResponse->ReplayGameLogs), true)); //ReplayLog
                    }
                    if (isset($lastEvent->serverResponse->FreeStacks)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', json_decode(json_encode($lastEvent->serverResponse->FreeStacks), true)); // FreeStack
                    }
                }
                else
                {
                    $bet = 100;
                }
                $currentReelSet = 0;
                $spinType = 's';
                
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') < $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') ) 
                {                    
                    $_obf_StrResponse = '&fs=' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&w=0.00&fsmul=1';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance') - $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                }else{
                    $Balance = $slotSettings->GetBalance();
                }
                if( $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') > 0){
                    $_obf_StrResponse = $_obf_StrResponse.$strtmp.'&rs=t&tmb_win='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbWin').'&rs_p='.($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') - 1).'&rs_c=1&rs_m=1';
                }
                // rezerv => 500,000.00
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                $response = 'def_s=5,9,3,7,7,9,9,9,3,6,6,5,6,9,9,3,7,7,9,9,3,3,6,6,5,6,4,3,3,7,7,9,9,4,6,6,6,5,6,4,5,7,7,7,9,9,4,3,6&balance='. $Balance .'&cfgs=1&ver=2&index=1&balance_cash='. $Balance .'&def_sb=6,5,6,4,5,9,7&reel_set_size=5&def_sa=4,9,3,6,6,5,5&reel_set='.$currentReelSet.'&balance_bonus=0.00&na='.$spinType.'&scatters=1~0,0,0,0,0,0,0~10,10,10,10,10,0,0~1,1,1,1,1,1,1&gmb=0,0,0&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"88817",max_rnd_win:"5000"}}&wl_i=tbm~5000&stime=1631107107339&sa=4,9,3,6,6,5,5&sb=6,5,6,4,5,9,7&sc='. implode(',', $slotSettings->Bet) .'&defc=100.00&sh=7&wilds=2~0,0,0,0,0,0,0~1,1,1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.$_obf_StrResponse.'&sver=5&counter=2&paytable=0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,1400,700,300,150,100,50,40,35,30,20,0,0,0,0;2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,1200,600,250,120,80,40,30,25,20,15,0,0,0,0;1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1800,1000,500,200,90,60,30,25,20,15,10,0,0,0,0;1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,1600,800,400,100,60,40,25,20,15,10,8,0,0,0,0;1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,1200,600,300,70,50,30,20,15,10,8,6,0,0,0,0;800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,800,400,200,60,40,25,15,10,8,6,5,0,0,0,0;400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,400,200,100,50,30,20,10,8,6,5,4,0,0,0,0&l=20&rtp=96.47&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=9,9,3,3,6,7,6,7,5,1,6,9,4,4,5,3,9,8,8,4,6,9,9,5,5,8,8,7,7,9,6,8,7,5,5,9,8,7,9,6,4,6,9,8,7,6,6,9,9,8,6,9,8,9,4,9,9,7,6,9,9,8,3,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,8,4,5,3,7,8,8,7,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,8,9,8,8,4,7,9,7,6,9,9,8,5,3,7,7,8,6,9,7,6,8,5,5,4,5,3,7,7,8,4,5,7,4,8,8,6,6,6~9,9,3,3,6,7,6,7,5,9,6,9,4,4,5,3,1,8,8,4,6,9,9,5,5,8,8,7,7,9,6,8,7,5,5,9,8,7,9,6,4,6,9,8,7,6,6,9,9,8,6,9,8,9,4,9,9,7,6,9,9,8,3,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,8,4,5,3,7,8,8,7,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,8,9,8,8,4,7,9,7,6,9,9,8,5,3,7,7,8,6,9,7,6,8,5,5,4,5,3,7,7,8,4,5,7,4,8,8,6,6,6~9,9,3,3,6,7,6,7,5,9,6,9,4,4,5,3,9,1,8,4,6,9,9,5,5,8,8,7,7,9,6,8,7,5,5,9,8,7,9,6,4,6,9,8,7,6,6,9,9,8,6,9,8,9,4,9,9,7,6,9,9,8,3,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,8,4,5,3,7,8,8,7,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,8,3,3,8,9,8,8,4,7,9,7,6,9,9,8,5,3,7,7,8,6,9,7,6,8,5,5,4,5,3,7,7,8,4,5,7,4,8,8,6,6,6~9,9,3,3,6,7,6,7,5,9,6,9,4,4,5,3,9,8,8,4,6,9,1,5,5,8,8,7,7,9,6,8,7,5,5,9,8,7,9,6,4,6,9,8,7,6,6,9,9,8,6,9,8,9,4,9,9,7,6,9,9,8,3,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,8,4,5,3,7,8,8,7,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,8,9,8,8,4,7,9,7,6,9,9,8,5,3,7,7,8,6,9,7,6,8,5,5,4,5,3,7,7,8,4,5,7,4,8,8,6,6,6~9,9,3,3,6,7,6,7,5,9,6,9,4,4,5,3,1,8,8,4,6,9,9,5,5,8,8,7,7,9,6,8,7,5,5,9,8,7,9,6,4,6,9,8,7,6,6,9,9,8,6,9,8,9,4,9,9,7,6,9,9,8,3,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,8,4,5,3,7,8,8,7,8,4,5,7,1,8,8,5,4,4,3,9,9,7,8,9,3,3,8,9,8,8,4,7,9,7,6,9,9,8,5,3,7,7,8,6,9,7,6,8,5,5,4,5,3,7,7,8,4,5,7,4,8,8~9,9,3,3,6,7,6,7,5,9,6,9,4,4,5,3,9,8,8,1,6,9,9,5,5,8,8,7,7,9,6,8,7,5,5,9,8,7,9,6,4,6,9,8,7,6,6,9,9,8,6,9,8,9,4,9,9,7,6,9,9,8,3,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,8,4,5,3,7,8,8,7,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,8,9,8,8,4,7,9,7,6,9,9,8,5,3,7,7,8,6,9,7,6,8,5,5,4,5,3,7,7,8,4,5,7,4,8,8~9,9,3,3,6,7,6,7,5,9,5,9,4,4,5,3,1,8,8,4,6,9,9,5,5,8,8,7,7,9,6,8,7,5,5,9,8,7,9,6,4,6,9,8,7,6,6,9,9,8,6,9,8,9,4,9,9,7,6,9,9,8,3,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,8,4,5,3,7,8,8,7,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,8,9,8,8,4,7,9,7,6,9,9,8,5,3,7,7,8,6,9,7,6,8,5,5,4,5,3,7,7,8,4,5,7,4,8,8&s='.$lastReelStr.'&reel_set2=3,7,6,5,8,9,4,5,6,8,4,3,7,9,3,7,8,5,6,9,4,5,6,8,7,3,4,9,3,7,6,5,8,9,4,5,6,8,4,3,7,9,3,7~3,7,6,5,8,9,4,5,6,8,4,3,7,9,3,7,8,5,6,9,4,5,6,8,7,3,4,9,3,7,6,5,8,9,4,5,6,8,4,3,7,9,3,7~3,7,6,5,8,9,4,5,6,8,4,3,7,9,3,7,8,5,6,9,4,5,6,8,7,3,4,9,3,7,6,5,8,9,4,5,6,8,4,3,7,9,3,7~3,7,6,5,8,9,4,5,6,8,4,3,7,9,3,7,8,5,6,9,4,5,6,8,7,3,4,9,3,7,6,5,8,9,4,5,6,8,4,3,7,9,3,7~3,7,6,5,8,9,4,5,6,8,4,3,7,9,3,7,8,5,6,9,4,5,6,8,7,3,4,9,3,7,6,5,8,9,4,5,6,8,4,3,7,9,3,7~3,7,6,5,8,9,4,5,6,8,4,3,7,9,3,7,8,5,6,9,4,5,6,8,7,3,4,9,3,7,6,5,8,9,4,5,6,8,4,3,7,9,3,7~3,7,6,5,8,9,4,5,6,8,4,3,7,9,3,7,8,5,6,9,4,5,6,8,7,3,4,9,3,7,6,5,8,9,4,5,6,8,4,3,7,9,3,7&t=stack&reel_set1=9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,9,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,4,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,9,8,8,4,7,7,7,6,9,9,8,3,7,7,8,6,6,9,7,6,5,5,5,7,6,6,6,8,4,4,4~9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,9,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,4,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,9,8,8,4,7,7,7,6,9,9,8,3,7,7,8,6,6,9,7,6,5,5,5,7,6,6,6,8,4,4,4~9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,9,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,4,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,9,8,8,4,7,7,7,6,9,9,8,3,7,7,8,6,6,9,7,6,5,5,5,7,6,6,6,8,4,4,4~9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,9,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,4,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,9,8,8,4,7,7,7,6,9,9,8,3,7,7,8,6,6,9,7,6,5,5,5,7,6,6,6,8,4,4,4~9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,9,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,5,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,9,8,8,4,7,7,7,6,9,9,8,3,7,7,8,6,6,9,7,6,5,5,5,7,6,6,6,8,4,4,4~9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,9,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,5,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,3,8,8,4,7,7,7,6,9,9,8,3,7,7,8,6,6,9,7,6,5,5,5,7,6,6,6,8,4,4,4~9,9,3,3,6,7,6,7,5,9,5,6,9,4,3,3,9,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,5,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,3,8,8,4,7,7,7,6,9,9,8,3,7,7,8,6,6,9,7,6,5,5,5,7,6,6,6,8,4,4,4&reel_set4=9,9,3,3,6,7,6,7,5,1,6,6,9,4,3,3,9,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,4,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,9,8,8,4,7,7,7,6,9,9,8,5,3,7,7,8,6,9,7,6,5,5,5,9,6,6,6~9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,1,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,4,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,9,8,8,4,7,7,7,6,9,9,8,5,3,7,7,8,6,9,7,6,5,5,5,9,6,6,6~9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,9,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,4,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,1,3,3,3,9,8,8,4,7,7,7,6,9,9,8,5,3,7,7,8,6,9,7,6,5,5,5,9,6,6,6~9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,9,8,8,4,6,9,1,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,4,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,9,8,8,4,7,7,7,6,9,9,8,5,3,7,7,8,6,9,7,6,5,5,5,9,6,6,6~9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,1,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,5,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,9,8,8,4,7,7,7,6,9,9,8,5,3,7,7,8,6,9,7,6,5,5,5,9,6,6,6,5,4,4,4~9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,9,8,8,1,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,5,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,3,8,8,4,7,7,7,6,9,9,8,5,3,7,7,8,6,9,7,6,5,5,5,9,6,6,6,5,4,4,4~9,9,3,3,6,7,6,7,5,9,5,6,9,4,3,3,1,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,5,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,3,8,8,4,7,7,7,6,9,9,8,5,3,7,7,8,6,9,7,6,5,5,5,9,6,6,6,5,4,4,4&purInit=[{type:"fs",bet:2000,fs_count:10}]&reel_set3=9,9,3,3,6,7,6,7,5,1,6,6,9,4,3,3,9,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,4,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,9,8,8,4,7,7,7,6,9,9,8,5,3,7,7,8,6,9,7,6,5,5,5,7,6,6,6~9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,1,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,4,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,9,8,8,4,7,7,7,6,9,9,8,5,3,7,7,8,6,9,7,6,5,5,5,7,6,6,6~9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,9,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,4,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,1,3,3,3,9,8,8,4,7,7,7,6,9,9,8,5,3,7,7,8,6,9,7,6,5,5,5,7,6,6,6~9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,9,8,8,4,6,9,1,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,4,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,9,8,8,4,7,7,7,6,9,9,8,5,3,7,7,8,6,9,7,6,5,5,5,7,6,6,6~9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,1,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,5,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,9,8,8,4,7,7,7,6,9,9,8,5,3,7,7,8,6,9,7,6,5,5,5,7,6,6,6,5,4,4,4~9,9,3,3,6,7,6,7,5,9,6,6,9,4,3,3,9,8,8,1,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,5,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,3,8,8,4,7,7,7,6,9,9,8,5,3,7,7,8,6,9,7,6,5,5,5,7,6,6,6,5,4,4,4~9,9,3,3,6,7,6,7,5,9,5,6,9,4,3,3,1,8,8,4,6,9,9,5,5,8,8,7,7,6,6,8,7,5,5,9,8,7,9,6,4,9,9,8,7,6,6,9,9,8,6,9,8,4,9,9,9,7,6,9,9,8,5,5,5,3,3,7,7,8,6,5,7,6,8,3,5,4,4,5,5,3,7,8,8,8,8,4,5,7,4,8,8,5,4,4,3,9,9,7,8,9,3,3,3,3,8,8,4,7,7,7,6,9,9,8,5,3,7,7,8,6,9,7,6,5,5,5,7,6,6,6,5,4,4,4&total_bet_min=10.00';
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
                $totalWin = $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
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
                $lines = $slotEvent['slotLines'];
                $betline = $slotEvent['slotBet'];
                $isbuyfreespin = -1;
                if(isset($slotEvent['pur'])){
                    $isbuyfreespin = $slotEvent['pur'];
                }
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1  && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    $slotEvent['slotEvent'] = 'freespin';
                }
                if( $slotEvent['slotEvent'] == 'doSpin' || $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    if( $lines <= 0 || $betline <= 0.0001 ) 
                    {
                        $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"invalid bet state"}';
                        exit( $response );
                    }
                    if( $slotEvent['slotEvent'] == 'doSpin' && $slotSettings->GetBalance() < ($lines * $betline)  && $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') > 0) 
                    {
                        $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"invalid balance"}';
                        exit( $response );
                    }
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 < $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotEvent['slotEvent'] == 'freespin' ) 
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
                
                $reelSet_Num = 0;
                $allBet = $betline * $lines;
               
                if($isbuyfreespin == 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $betline * 2000;
                }else{
                    $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $allBet, $lines);
                    $winType = $_spinSettings[0];
                    $_winAvaliableMoney = $_spinSettings[1];
                }
                $isTumb = false;
                $lastReel = null;
                $isGeneratedFreeStack = false;
                $freeStacks = []; // free stacks
                $isForceWin = false;
                if($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') > 0){
                    $isTumb = true;
                    $reelsAndPoses = $slotSettings->GetLastReel($slotSettings->GetGameData($slotSettings->slotId . 'LastReel'), $slotSettings->GetGameData($slotSettings->slotId . 'BinaryReel'), $slotSettings->GetGameData($slotSettings->slotId . 'BonusMplPos'));
                    $lastReel = $reelsAndPoses[0];
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMplPos', $reelsAndPoses[1]);
                    if($slotEvent['slotEvent'] == 'freespin'){
                        $reelSet_Num = 1;
                    }
                }else{
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMplPos', []);
                    if($slotEvent['slotEvent'] == 'freespin'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                        $leftFreeGames = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') - $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');    
                        $reelSet_Num = 1;
                        if($winType == 'none'){
                            $reelSet_Num = 3;
                        }

                        if($leftFreeGames <= mt_rand(0 , 1) && $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') == 0){
                            $winType = 'win';
                            $_winAvaliableMoney = $slotSettings->GetBank($slotEvent['slotEvent']);
                            $isForceWin = true;
                        }
                    }else
                    {
                        $reelSet_Num = 0; // rand(0, 2);
                        $slotEvent['slotEvent'] = 'bet';
                        $slotSettings->SetBalance(-1 * ($allBet), $slotEvent['slotEvent']);
                        $_sum = ($allBet) / 100 * $slotSettings->GetPercent();
                        if($isbuyfreespin == 0){                            
                            $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $_sum, $slotEvent['slotEvent'], true);
                            $winType = 'bonus';
                            $_winAvaliableMoney = $slotSettings->GetBank('bonus');
                        }else{
                            $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $_sum, $slotEvent['slotEvent']);
                        }
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                        
                        $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $isbuyfreespin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                        $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                        $roundstr = sprintf('%.4f', microtime(TRUE));
                        $roundstr = str_replace('.', '', $roundstr);
                        $roundstr = '275' . substr($roundstr, 4, 7);
                        $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                        $leftFreeGames = 0;

                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []);
                        $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'RegularSpinCount') + 1);
                    }
                }
                $Balance = $slotSettings->GetBalance();
                if( $slotEvent['slotEvent'] == 'bet' ) 
                {
                    $slotSettings->UpdateJackpots($allBet);
                }
                $defaultScatterCount = 0;
                if($winType == 'bonus'){                    
                    $defaultScatterCount = $slotSettings->GenerateFreeSpinCount($slotEvent);
                    if($slotEvent['slotEvent'] == 'freespin' && mt_rand(0, 100) < 80){
                        $defaultScatterCount = 3;
                    }
                }
                // $winType = 'win';
                // $_winAvaliableMoney = $slotSettings->GetBank('');
                $isBonusMul = false;
                if($winType == 'win'){
                    $isBonusMul = $slotSettings->IsBonusMul($slotEvent['slotEvent']);
                }
                for( $i = 0; $i <= 2000; $i++ ) 
                {
                    $totalWin = 0;
                    $this->winLines = [];
                    $this->strCheckSymbol = '';
                    $this->repeatCount = 0;
                    $this->strWinLinePos = '';
                    $wild = '2';
                    $scatter = '1';
                    $_obf_winCount = 0;
                    $strWinLine = '';
                    $freeMul = 1;
                    $winLineMulNums = [];                    
                    $bonusMul = 0;
                    if($isGeneratedFreeStack == true){
                        //freestack
                    }else{
                        if($isTumb == true && $lastReel != null){
                            $reels = $slotSettings->GetTumbReelStrips($lastReel, $reelSet_Num);
                        }else{
                            if($isbuyfreespin == 0 && mt_rand(0, 100) < 10){
                                $reels = $slotSettings->GetBuyFreeSpinReelStrips($defaultScatterCount);
                            }else{
                                $reels = $slotSettings->GetReelStrips($winType, $slotEvent['slotEvent'], $reelSet_Num, $defaultScatterCount);
                            }
                        }
                    }
                    $scattersCount = 0;
                    for($k = 0; $k < 7; $k++){
                        for( $j = 0; $j < 7; $j++ ) 
                        {
                            if($reels['reel' . ($j + 1)][$k] == $scatter){
                                $scattersCount++;
                            }else if(strpos($this->strCheckSymbol, $j . '-' . $k) == false){
                                $this->repeatCount = 1;
                                $this->strWinLinePos = ($k * 7 + $j);
                                $this->strCheckSymbol = $this->strCheckSymbol . ';' . $j . '-' . $k;
                                $this->findZokbos($reels, $j, $k, $reels['reel' . ($j + 1)][$k]);

                                if($this->repeatCount >= 5){
                                    $winLine = [];
                                    $winLine['FirstSymbol'] = $reels['reel' . ($j + 1)][$k];
                                    $winLine['RepeatCount'] = $this->repeatCount;
                                    $winLine['StrLineWin'] = $this->strWinLinePos;
                                    array_push($this->winLines, $winLine);
                                }
                            }
                        }   
                    }
                    $binaryReel = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                    $bonusMulReel = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                    $bonusMulCount = 0;
                    $isNewTumb = false;
                    $arr_slm = [];
                    for($r = 0; $r < count($this->winLines); $r++){
                        $winLine = $this->winLines[$r];
                        $arr_symbols = explode('~', $winLine['StrLineWin']);
                        $line_mul = 1;
                        for($k = 0; $k < count($arr_symbols); $k++){
                            $binaryReel[$arr_symbols[$k]] = $reels['reel' . ($arr_symbols[$k] % 7 + 1)][floor($arr_symbols[$k] / 7)];
                            if($isBonusMul == true && $bonusMulReel[$arr_symbols[$k]] == 0 && mt_rand(0, 100) < 20){
                                if(mt_rand(0, 100) < 97){
                                    $bonusMulReel[$arr_symbols[$k]] = 2;
                                }else{
                                    $bonusMulReel[$arr_symbols[$k]] = 4;
                                }
                                $bonusMulCount++;
                                $line_mul = $line_mul * $bonusMulReel[$arr_symbols[$k]];
                            }
                        }
                        $winLineMoney = $slotSettings->Paytable[$winLine['FirstSymbol']][$winLine['RepeatCount']] * $betline ;
                        if($line_mul > 1 && $winLineMoney > 0){
                            $arr_slm[$r] = $line_mul;
                            $winLineMoney = $winLineMoney * $line_mul;
                        }else{
                            $arr_slm[$r] = 0;
                        }
                        if($winLineMoney > 0){                            
                            $isNewTumb = true;
                            $strWinLine = $strWinLine . '&l'. $r.'='.$r.'~'.$winLineMoney . '~' . $winLine['StrLineWin'];
                            $totalWin += $winLineMoney;
                        }
                    }            
                    $freeSpinNum = $slotSettings->freespinCounts[$scattersCount];
                    
                    
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
                        if( $scattersCount >= 3 && ($winType != 'bonus'  || $scattersCount != $defaultScatterCount)) 
                        {
                            if($winType == 'none' &&  $totalWin <= 0 && $i > 500){
                                break;
                            }
                        }
                        else if($scattersCount > 6){
                            
                        }
                        else if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') >= 15 && $freeSpinNum > 0){
                        
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
                        else if( $totalWin * $bonusMul <= $_winAvaliableMoney && $winType == 'bonus' ) 
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
                        else if( $totalWin > 0 && ($totalWin + $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin')) * $bonusMul <= $_winAvaliableMoney && $winType == 'win' )
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
                    // }
                }
                $spinType = 's';
                $isEndRespin = false;
                $_obf_totalWin = $totalWin;
                if( $totalWin > 0) 
                {
                    $spinType = 'c';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }
                for($k = 0; $k < 7; $k++){
                    for($j = 1; $j <= 7; $j++){
                        $lastReel[($j - 1) + $k * 7] = $reels['reel'.$j][$k];
                    }
                }
                
                $strLastReel = implode(',', $lastReel);
                $strReelSa = $reels['reel1'][7].','.$reels['reel2'][7].','.$reels['reel3'][7].','.$reels['reel4'][7].','.$reels['reel5'][7].','.$reels['reel6'][7].','.$reels['reel7'][7];
                $strReelSb = $reels['reel1'][-1].','.$reels['reel2'][-1].','.$reels['reel3'][-1].','.$reels['reel4'][-1].','.$reels['reel5'][-1].','.$reels['reel6'][-1].','.$reels['reel7'][-1];               
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'BinaryReel', $binaryReel);
                $isState = true;
                if($isNewTumb == true){
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') + 1);
                    $isState = false;
                }else{
                    if( $freeSpinNum > 0 ) 
                    {
                        
                        if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0 ) 
                        {
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freeSpinNum);
                            $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                        }
                        else
                        {
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $freeSpinNum);
                        }
                    }
                }
                $otherResponse = '';
                $n_reel_set = $reelSet_Num;
                $isEnd = false;
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') + $totalWin);
                    $spinType = 's';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 && $isNewTumb == false ) 
                    {
                        $isEnd = true;
                        $spinType = 'c';
                        $otherResponse = '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                    else
                    {
                        $isState = false;
                        $otherResponse = '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') + $totalWin);
                    if($freeSpinNum > 0 ){
                        $spinType = 's';
                        $isState = false;
                        $otherResponse = '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fswin=0.00&fs=1&fsres=0.00';
                    }
                }

                if($isbuyfreespin == 0){
                    $otherResponse = $otherResponse.'&puri=0&purtr=1';
                }
                $strtmp = '';
                if($isNewTumb == true){
                    $spinType = 's';
                    $firstsymbolstr = '';
                    for($r = 0; $r < 49; $r++){
                        if($binaryReel[$r] > 0){
                            if($firstsymbolstr == ''){
                                $firstsymbolstr = $binaryReel[$r];
                                $strtmp = '&tmb='.$r;
                            }else{
                                $strtmp = $strtmp . ',' . $binaryReel[$r] . '~' . $r;
                            }
                        }
                    }
                    $strtmp = $strtmp . ',' . $firstsymbolstr;
                    $otherResponse = $otherResponse.$strtmp.'&rs=mc&tmb_win='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbWin').'&rs_p='.($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') - 1).'&rs_c=1&rs_m=1';
                }else{
                    if($isTumb == true){
                        if($slotEvent['slotEvent'] != 'freespin'){
                            $spinType = 'c';
                        }
                        $otherResponse = $otherResponse.'&tmb_res='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbWin').'&rs_t='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbleState').'&tmb_win='.($slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') - $totalWin);
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', 0);
                } 
                if($bonusMulCount > 0){
                    $arr_lmi = [];
                    $arr_lmv = [];
                    $arr_mp = [];
                    $arr_mv = [];
                    for($i = 0; $i < count($arr_slm); $i++){
                        if($arr_slm[$i] > 0){
                            array_push($arr_lmi, $i);
                            array_push($arr_lmv, $arr_slm[$i]);
                        }
                    }
                    for($i = 0; $i < 49; $i++){
                        if($bonusMulReel[$i] > 0){
                            array_push($arr_mp, $i);
                            array_push($arr_mv, $bonusMulReel[$i]);
                        }
                    }
                    $otherResponse = $otherResponse.'&slm_lmi='.implode(',', $arr_lmi).'&slm_mp='.implode(',', $arr_mp).'&slm_lmv='.implode(',', $arr_lmv).'&slm_mv=' . implode(',', $arr_mv);
                }
                // if($isEnd == true){
                //     $otherResponse = $otherResponse.'&w='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                // }else{
                //     $otherResponse = $otherResponse.'&w='.$_obf_totalWin;
                // }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .'&balance='.$Balance.'&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType.$strWinLine.'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=7&c='.$betline.'&sver=5&reel_set='.$n_reel_set.$otherResponse.'&counter='. ((int)$slotEvent['counter'] + 1).'&w='.$_obf_totalWin .'&l=20&s='.$strLastReel;


                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) && $isNewTumb == false) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }

                if($isNewTumb == false){
                    if( $slotEvent['slotEvent'] != 'freespin' && $scattersCount >= 3) 
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin'));
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

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"TumbState":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') . ',"Balance":' . $Balance . ',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $totalWin . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .   ',"freeBalance":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance') .  ',"tumbWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') . ',"winLines":[],"Jackpots":""' .  ',"BuyFreeSpin":'.$slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"ReplayGameLogs":'.json_encode($replayLog) . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID').',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')). ',"strTmb":"'.$strtmp.  '","LastReel":'.json_encode($lastReel). ',"BinaryReel":'.json_encode($binaryReel).'}}';

                if($slotEvent['slotEvent'] == 'freespin' && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                    $allBet = $betline * 2000;
                }
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin'), $slotEvent['slotEvent'], $isState);
            }
            if($slotEvent['action'] == 'doSpin' || $slotEvent['action'] == 'doCollect' || $slotEvent['action'] == 'doCollectBonus'){
                $this->saveGameLog($slotEvent, $response, $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'), $slotSettings);
            }
            $slotSettings->SaveGameData();
            \DB::commit();
            return $response;
        }
        public function findZokbos($reels, $i, $j, $firstSymbol){
            $scatter = '2';
            $bPathEnded = true;
            if($i < 6 && $firstSymbol == $reels['reel' . ($i + 2)][$j] && strpos($this->strCheckSymbol, ($i + 1) . '-' . $j) == false){
                $this->repeatCount++;
                $this->strWinLinePos = $this->strWinLinePos . '~'.($j * 7 + $i + 1);
                $this->strCheckSymbol = $this->strCheckSymbol . ';' . ($i + 1) . '-' . $j;
                $this->findZokbos($reels, $i + 1, $j, $firstSymbol);
                $bPathEnded = false;
            }

            if($j < 6 && $firstSymbol == $reels['reel' . ($i + 1)][$j + 1] && strpos($this->strCheckSymbol, $i . '-' . ($j + 1)) == false){
                $this->repeatCount++;
                $this->strWinLinePos = $this->strWinLinePos . '~'.(($j + 1) * 7 + $i);
                $this->strCheckSymbol = $this->strCheckSymbol . ';' . $i . '-' . ($j + 1);
                $this->findZokbos($reels, $i, $j + 1, $firstSymbol);
                $bPathEnded = false;
            }

            if($i > 0 && $firstSymbol == $reels['reel' . $i][$j] && strpos($this->strCheckSymbol, ($i - 1) . '-' . $j) == false){
                $this->repeatCount++;
                $this->strWinLinePos = $this->strWinLinePos . '~'.($j * 7 + $i - 1);
                $this->strCheckSymbol = $this->strCheckSymbol . ';' . ($i - 1) . '-' . $j;
                $this->findZokbos($reels, $i - 1, $j, $firstSymbol);
                $bPathEnded = false;
            }

            if($j > 0 && $firstSymbol == $reels['reel' . ($i + 1)][$j - 1] && strpos($this->strCheckSymbol, $i . '-' . ($j - 1)) == false){
                $this->repeatCount++;
                $this->strWinLinePos = $this->strWinLinePos . '~'.(($j - 1) * 7 + $i);
                $this->strCheckSymbol = $this->strCheckSymbol . ';' . $i . '-' . ($j - 1);
                $this->findZokbos($reels, $i, $j - 1, $firstSymbol);
                $bPathEnded = false;
            }

            // if($bPathEnded == true){
            //     if($this->repeatCount >= 5){
            //         $winLine = [];
            //         $winLine['FirstSymbol'] = $firstSymbol;
            //         $winLine['RepeatCount'] = $this->repeatCount;
            //         $winLine['StrLineWin'] = $this->strWinLinePos;
            //         array_push($this->winLines, $winLine);
            //     }
            // }
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

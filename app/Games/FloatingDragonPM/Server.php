<?php 
namespace VanguardLTE\Games\FloatingDragonPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'HoldMoneyValue', [0, 0, 0]);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeMore', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 10);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [5,6,9,6,6,8,11,8,9,9,6,9,12,6,6]);
                $slotSettings->setGameData($slotSettings->slotId . 'RoundID', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FinalWildCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'DefaultWildMaskCounts', [0,0,0,0,0,0,0,0,0,0]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $_moneyValue = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', $lastEvent->serverResponse->totalRespinGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', $lastEvent->serverResponse->currentRespinGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'HoldMoneyValue', $lastEvent->serverResponse->HoldMoneyValues);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeMore', $lastEvent->serverResponse->FreeMore);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', $lastEvent->serverResponse->BonusState);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $_moneyValue = $lastEvent->serverResponse->MoneyValues;
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->setGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    if (isset($lastEvent->serverResponse->ReplayGameLogs)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', json_decode(json_encode($lastEvent->serverResponse->ReplayGameLogs), true)); //ReplayLog
                    }
                    if(isset($lastEvent->serverResponse->FinalWildCount)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FinalWildCount', $lastEvent->serverResponse->FinalWildCount);
                    }
                    $bet = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $bet = 100.00;
                }
                $_holdmoneyValue = $slotSettings->GetGameData($slotSettings->slotId . 'HoldMoneyValue');
                $currentReelSet = 0;
                $spinType = 's';
                if(( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') < $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') && $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') > 0 )){
                    $spinType = 'b';
                    $_obf_StrResponse = 'bgid=0&end=0&rsb_rt=0&lifes='.($slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') - $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame')) .'&bgt=48&rw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin').'&wp='.($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') / $bet).'&coef='.$bet;
                    $currentReelSet = 1;
                }else if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    $_obf_StrResponse = '&fs=' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&w=0.00&fsmul=1';
                    $currentReelSet = 3;
                    $_bonusMuls = [1, 2, 3, 10];
                    $_obf_StrResponse = $_obf_StrResponse . '&accm=cp;cp~mp&acci=0;1&accv='. $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') .';'. $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') .'~4';
                }
                $isMoney = false;
                $CurrentMoneyText = [];
                for($i = 0; $i < count($_moneyValue); $i++){
                    if($_moneyValue[$i] >= 20){
                        $CurrentMoneyText[$i] = 'v';
                        $isMoney = true;
                    }else if($_moneyValue[$i] > 0){
                        $CurrentMoneyText[$i] = 'mma';
                    }else{
                        $CurrentMoneyText[$i] = 'r';
                    }
                }
                for($i = 0; $i < 3; $i++){
                    if($_holdmoneyValue[$i] > 0){
                        $_moneyValue[$i * 5 + 2] = $_holdmoneyValue[$i];
                        $CurrentMoneyText[$i * 5 + 2] = 'c';
                        $isMoney = true;
                    }
                }

                if($isMoney == true){
                    $_obf_StrResponse = $_obf_StrResponse . '&mo=' . implode(',', $_moneyValue) . '&mo_t=' . implode(',', $CurrentMoneyText);
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                $Balance = $slotSettings->GetBalance();
                
                // $response = 'def_s=5,6,9,6,6,8,11,8,9,9,6,9,12,6,6&balance='. $Balance .'&cfgs=1&ver=2&index=1&balance_cash='. $Balance .'&def_sb=10,7,7,8,11&reel_set_size=4&def_sa=11,10,7,10,4&reel_set='.$currentReelSet.$_obf_StrResponse.'&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0~20,15,10,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"3880481",max_rnd_win:"2100"}}&wl_i=tbm~2100&stime=1629077890101&sa=11,10,7,10,4&sb=10,7,7,8,11&sc='. implode(',', $slotSettings->Bet) .'&defc=100.00&sh=3&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;2000,200,50,5,0;1000,150,30,0,0;500,100,20,0,0;500,100,20,0,0;200,50,10,0,0;100,25,5,0,0;100,25,5,0,0;100,25,5,0,0;100,25,5,0,0;100,25,5,0,0&l=10&rtp=96.71&reel_set0=6,7,8,10,10,5,6,12,7,11,6,5,10,6,1,5,8,7,7,12,4,10,5,8,4,10,3,12,8,10,4,8,9,9,12,10,9,4,3,10,5,8,12,3,6,8,12,6,4,10,11,12,8,7,7,7,7,7~4,3,9,7,11,3,8,6,3,9,7,11,5,10,6,3,1,9,7,7,12,3,4,11,3,6,8,9,5,11,9,6,11,4,9,3,5,4,11,6,3,5,11,9,12,9,10,4,3,11,5,9,9,8,7,7,7,7,7~12,3,3,6,7,11,3,5,7,10,4,3,5,10,9,4,5,1,6,7,7,12,9,5,4,3,8,5,6,11,4,6,5,10,3,6,4,3,8,7,7,7,7,7~5,7,6,4,12,5,4,8,7,6,4,11,3,9,7,7,10,1,6,3,5,3,7,7,7,7,7~4,6,7,11,11,4,8,8,7,5,3,12,1,7,7,10,8,6,9,7,7,7,7,7&s='.$lastReelStr.'&accInit=[{id:0,mask:"cp"},{id:1,mask:"cp;mp"}]&reel_set2=6,7,8,10,10,5,6,12,7,11,6,5,10,6,1,5,8,7,7,12,4,10,5,8,4,10,3,12,8,10,4,8,9,9,12,10,9,4,3,10,5,8,12,3,6,8,12,6,4,10,11,12,8,7,7,7,7,7~4,3,9,7,11,3,8,6,3,9,7,11,5,10,6,3,1,9,7,7,12,3,4,11,3,6,8,9,5,11,9,6,11,4,9,3,5,4,11,6,3,5,11,9,12,9,10,4,3,11,5,9,9,8,7,7,7,7,7~12,3,3,6,7,11,3,5,7,10,4,3,5,10,9,4,5,1,6,7,7,12,9,5,4,3,8,5,6,11,4,6,5,10,3,6,4,3,8,7,7,7,7,7~5,7,6,4,12,5,4,8,7,6,4,11,3,9,7,7,10,1,6,3,5,3,7,7,7,7,7~4,6,7,11,11,4,8,8,7,5,3,12,1,7,7,10,8,6,9,7,7,7,7,7&reel_set1=6,7,8,10,10,5,6,12,7,11,6,5,10,6,1,5,8,7,7,12,4,10,5,8,4,10,3,12,8,10,4,8,9,9,12,10,9,4,3,10,5,8,12,3,6,8,12,6,4,10,11,12,8,7,7,7,7,7~4,3,9,7,11,3,8,6,3,9,7,11,5,10,6,3,1,9,7,7,12,3,4,11,3,6,8,9,5,11,9,6,11,4,9,3,5,4,11,6,3,5,11,9,12,9,10,4,3,11,5,9,9,8,7,7,7,7,7~12,3,3,6,7,11,3,5,7,10,4,3,5,10,9,4,5,1,6,7,7,12,9,5,4,3,8,5,6,11,4,6,5,10,3,6,4,3,8,7,7,7,7,7~5,7,6,4,12,5,4,8,7,6,4,11,3,9,7,7,10,1,6,3,5,3,7,7,7,7,7~4,6,7,11,11,4,8,8,7,5,3,12,1,7,7,10,8,6,9,7,7,7,7,7&reel_set3=6,7,8,10,2,5,6,12,7,11,6,5,10,6,5,8,7,7,12,4,10,5,8,4,10,3,12,8,10,4,8,9,2,12,10,9,4,3,10,5,8,12,3,6,8,12,6,4,10,11,12,8,7,7,7,7,7~4,3,9,7,11,3,8,6,3,9,7,11,5,10,6,3,9,7,7,12,3,4,11,3,6,2,9,5,11,9,6,11,4,9,3,5,4,11,6,3,5,11,2,12,9,10,4,3,11,5,2,9,8,7,7,7,7,7~12,2,3,6,7,11,3,5,7,10,4,3,5,2,9,4,5,6,7,7,12,9,5,4,3,8,5,6,11,4,6,2,10,3,6,4,3,8,7,7,7,7,7~5,7,6,2,12,5,2,8,7,6,4,11,3,9,7,7,10,6,3,5,2,7,7,7,7,7~4,6,7,11,2,4,8,2,7,5,3,12,7,7,10,2,6,9,7,7,7,7,7';
                $response = 'def_s=9,6,11,5,10,7,6,10,9,9,11,7,5,6,12&balance='. $Balance .'&cfgs=1&ver=2&index=1&balance_cash='. $Balance .'&def_sb=7,8,8,9,5&reel_set_size=5&def_sa=12,9,8,9,5&reel_set='.$currentReelSet.$_obf_StrResponse.'&bonusInit=[{bgid:0,bgt:48,mo_s:"14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,14,15,15,15,15,15",mo_v:"10,20,30,40,50,60,70,80,90,100,110,120,140,160,180,200,1000,2000,3000,4000,49930"}]&balance_bonus=0.00&na='. $spinType .'&scatters=1~0,0,0,0,0~20,15,10,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&gameInfo={rtps:{regular:"96.71"},props:{max_rnd_sim:"1",max_rnd_hr:"608273",max_rnd_win:"5000"}}&wl_i=tbm~5000&stime=1630941943429&sa=12,9,8,9,5&sb=7,8,8,9,5&sc='. implode(',', $slotSettings->Bet) .'&defc=100.00&sh=3&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;2000,200,50,5,0;1000,150,30,0,0;500,100,20,0,0;500,100,20,0,0;200,50,10,0,0;100,25,5,0,0;100,25,5,0,0;100,25,5,0,0;100,25,5,0,0;100,25,5,0,0;0,0,0,0,0;0,0,0,0,0&l=10&rtp=96.71&reel_set0=13,7,8,9,11,1,6,7,13,8,12,7,6,11,7,6,9,8,8,13,5,11,6,9,5,11,4,13,9,11,5,9,10,1,13,11,10,5,4,11,6,9,13,4,7,9,13,7,5,11,12,13,9,8,8,8,8,8~7,5,4,10,8,12,4,9,7,4,10,8,12,6,11,7,4,10,8,8,13,4,5,12,4,7,1,10,6,12,10,7,12,5,10,4,6,5,12,7,4,6,12,1,13,10,11,5,4,12,6,1,10,9,8,8,8,8,8~7,13,1,4,7,8,0,0,0,12,4,6,8,11,5,4,6,1,10,5,6,7,8,8,13,0,10,6,5,4,9,6,7,12,5,7,1,11,4,7,5,4,9,8,8,8,8,8~5,6,8,7,1,13,6,1,9,8,7,5,12,4,10,8,8,11,7,4,6,1,8,8,8,8,8~6,5,7,8,12,1,5,9,8,6,4,13,1,8,8,11,1,7,10,8,8,8,8,8&s='.$lastReelStr.'&accInit=[{id:0,mask:"cp"},{id:1,mask:"cp;mp"}]&reel_set2=13,7,8,9,11,1,6,7,13,8,12,7,6,11,7,6,9,8,8,13,5,11,6,9,5,11,4,13,9,11,5,9,10,1,13,11,10,5,4,11,6,9,13,4,7,9,13,7,5,11,12,13,9,8,8,8,8,8~7,5,4,10,8,12,4,9,7,4,10,8,12,6,11,7,4,10,8,8,13,4,5,12,4,7,1,10,6,12,10,7,12,5,10,4,6,5,12,7,4,6,12,1,13,10,11,5,4,12,6,1,10,9,8,8,8,8,8~7,13,1,4,7,8,0,0,0,12,4,6,8,11,5,4,6,1,10,5,6,7,8,8,13,0,10,6,5,4,9,6,7,12,5,7,1,11,4,7,5,4,9,8,8,8,8,8~5,6,8,7,1,13,6,1,9,8,7,5,12,4,10,8,8,11,7,4,6,1,8,8,8,8,8~6,5,7,8,12,1,5,9,8,6,4,13,1,8,8,11,1,7,10,8,8,8,8,8&reel_set1=13,7,8,9,11,1,6,7,13,8,12,7,6,11,7,6,9,8,8,13,5,11,6,9,5,11,4,13,9,11,5,9,10,1,13,11,10,5,4,11,6,9,13,4,7,9,13,7,5,11,12,13,9,8,8,8,8,8~7,5,4,10,8,12,4,9,7,4,10,8,12,6,11,7,4,10,8,8,13,4,5,12,4,7,1,10,6,12,10,7,12,5,10,4,6,5,12,7,4,6,12,1,13,10,11,5,4,12,6,1,10,9,8,8,8,8,8~7,13,1,4,7,8,0,0,0,12,4,6,8,11,5,4,6,1,10,5,6,7,8,8,13,0,10,6,5,4,9,6,7,12,5,7,1,11,4,7,5,4,9,8,8,8,8,8~5,6,8,7,1,13,6,1,9,8,7,5,12,4,10,8,8,11,7,4,6,1,8,8,8,8,8~6,5,7,8,12,1,5,9,8,6,4,13,1,8,8,11,1,7,10,8,8,8,8,8&reel_set4=13,7,8,9,11,2,6,7,13,8,12,7,6,11,7,6,9,8,8,13,5,11,6,9,5,11,4,13,9,11,5,9,10,2,13,11,10,5,4,11,6,9,13,4,7,9,13,7,5,11,12,13,9,8,8,8,8,8~7,5,4,10,8,12,4,9,7,4,10,8,12,6,11,7,4,10,8,8,13,4,5,12,4,7,2,10,6,12,10,7,12,5,10,4,6,5,12,7,4,6,12,2,13,10,11,5,4,12,6,2,10,9,8,8,8,8,8~7,13,2,4,7,8,12,4,6,8,11,5,4,6,2,10,5,6,7,8,8,13,10,6,5,4,9,6,7,12,5,7,2,11,4,7,5,4,9,8,8,8,8,8~5,6,8,7,2,13,6,2,9,8,7,5,12,4,10,8,8,11,7,4,6,2,8,8,8,8,8~6,5,7,8,12,2,5,9,2,8,6,4,13,8,8,11,2,7,10,8,8,8,8,8&reel_set3=13,7,8,9,11,1,6,7,13,8,12,7,6,11,7,6,9,8,8,13,5,11,6,9,5,11,4,13,9,11,5,9,10,1,13,11,10,5,4,11,6,9,13,4,7,9,13,7,5,11,12,13,9,8,8,8,8,8~7,5,4,10,8,12,4,9,7,4,10,8,12,6,11,7,4,10,8,8,13,4,5,12,4,7,1,10,6,12,10,7,12,5,10,4,6,5,12,7,4,6,12,1,13,10,11,5,4,12,6,1,10,9,8,8,8,8,8~7,13,1,4,7,8,0,0,0,12,4,6,8,11,5,4,6,1,10,5,6,7,8,8,13,0,10,6,5,4,9,6,7,12,5,7,1,11,4,7,5,4,9,8,8,8,8,8~5,6,8,7,1,13,6,1,9,8,7,5,12,4,10,8,8,11,7,4,6,1,8,8,8,8,8~6,5,7,8,12,1,5,9,8,6,4,13,1,8,8,11,1,7,10,8,8,8,8,8';
            }
            else if( $slotEvent['slotEvent'] == 'doCollect' || $slotEvent['slotEvent'] == 'doCollectBonus') 
            {
                $Balance = $slotSettings->GetBalance();
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);    
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
                $lines = 10;      
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
                $linesId[0] = [2, 2, 2, 2, 2];
                $linesId[1] = [1, 1, 1, 1, 1];
                $linesId[2] = [3, 3, 3, 3, 3];
                $linesId[3] = [2, 1, 1, 1, 2];
                $linesId[4] = [2, 3, 3, 3, 2];
                $linesId[5] = [3, 2, 1, 2, 3];
                $linesId[6] = [1, 2, 3, 2, 1];
                $linesId[7] = [3, 3, 2, 1, 1];
                $linesId[8] = [1, 1, 2, 3, 3];
                $linesId[9] = [3, 2, 2, 2, 1];
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
                if($slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    $bonusMpl = $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl');
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'HoldMoneyValue', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeMore', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FinalWildCount', 0);
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '275' . substr($roundstr, 4, 7);
                    $slotSettings->setGameData($slotSettings->slotId . 'RoundID', $roundstr); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                }
                $Balance = $slotSettings->GetBalance();
                if( $slotEvent['slotEvent'] == 'bet' ) 
                {
                    $slotSettings->UpdateJackpots($betline * $lines);
                }
                // if($winType != 'bonus'){
                //     $winType = 'win';
                //     $_winAvaliableMoney = $slotSettings->GetBank('');
                // }
                if($winType == 'bonus'){
                    if($slotEvent['slotEvent'] == 'bet'){
                        
                    }else{
                        $winType = 'win';
                    }
                }
                $isGenerateRespinSpin = false;
                $defalultScatterCount = 0;
                if($winType == 'bonus'){
                    if($slotEvent['slotEvent'] != 'freespin' && mt_rand(0, 100) < 50){
                        $isGenerateRespinSpin = true;
                    }else if($slotEvent['slotEvent'] == 'freespin'){
                        $winType = 'win';
                    }else{
                        $defalultScatterCount = $slotSettings->GenerateFreeSpinCount($slotEvent['slotEvent']);
                    }
                }
                for( $i = 0; $i <= 2000; $i++ ) 
                {
                    $totalWin = 0;
                    $_moneyValue = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]; 
                    $_holdmoneyValue = [0, 0, 0]; 
                    $lineWins = [];
                    $lineWinNum = [];
                    $wild = '2';
                    $scatter = '1';
                    $moneysymbol = '8';
                    $holdMoneysymbol = '0';
                    $_obf_winCount = 0;
                    $strWinLine = '';
                    $reels = $slotSettings->GetReelStrips($winType, $slotEvent['slotEvent'], $isGenerateRespinSpin, $defalultScatterCount);
                    
                    $_lineWinNumber = 1;
                    for( $k = 0; $k < $lines; $k++ ) 
                    {
                        $_lineWin = '';
                        $firstEle = $reels['reel1'][$linesId[$k][0] - 1];
                        $lineWinNum[$k] = 1;
                        $lineWins[$k] = 0;
                        for($j = 1; $j < 5; $j++){
                            $ele = $reels['reel'. ($j + 1)][$linesId[$k][$j] - 1];
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
                    
                    
                    $_obf_wildposes = [];
                    $scattersCount = 0;
                    $scattersWin = 0;
                    $holdMoneyCount = 0;
                    $holdMoneyTotalWin = 0;
                    $isMoney = false;
                    $isHoldMoney = false;
                    $_obf_0D33120B1B18292D30293B191C3D383E3D2D0C195B2101 = '';
                    for( $r = 1; $r <= 5; $r++ ) 
                    {
                        for( $k = 0; $k <= 2; $k++ ) 
                        {
                            if( $reels['reel' . $r][$k] == $scatter ) 
                            {
                                $scattersCount++;
                            }else if($reels['reel' . $r][$k] == $wild){                                
                                array_push($_obf_wildposes, $k * 5 + $r - 1);
                            }else if($r == 3 && $reels['reel' . $r][$k] == $holdMoneysymbol){
                                $holdMoneyCount++;
                                $isHoldMoney = true;
                                $_holdmoneyValue[$k] = $slotSettings->GetHoldMoneyWin();
                                $holdMoneyTotalWin = $holdMoneyTotalWin + $_holdmoneyValue[$k]  * $betline;
                            }
                        }
                    }
                    for($r = 0; $r <= 2; $r++){
                        for( $k = 0; $k < 5; $k++ ) 
                        {
                            if( $reels['reel' . ($k+1)][$r] == $moneysymbol) 
                            {
                                $_moneyValue[$r * 5 + $k] = $slotSettings->GetMoneyWin();
                                $isMoney = true;
                            }else{
                                if($slotEvent['slotEvent'] == 'freespin' && $reels['reel' . ($k+1)][$r] == $wild){
                                    $_moneyValue[$r * 5 + $k] = $bonusMpl;
                                }else{
                                    $_moneyValue[$r * 5 + $k] = 0;
                                }
                            }
                        }
                    }
                    $moneyTotalWin = 0;
                    $freeSpinNum = 0;
                    $freeWildCount = 0;
                    if($slotEvent['slotEvent'] == 'freespin'){
                        if(count($_obf_wildposes) > 0){
                            $isMoney = true;
                            for($r = 0; $r < count($_moneyValue); $r++){
                                if($_moneyValue[$r] > 10){
                                    $moneyTotalWin = $moneyTotalWin + $_moneyValue[$r] * $betline * count($_obf_wildposes) * $bonusMpl;
                                }
                            }
                        }
                        $freeWildCount = $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') + count($_obf_wildposes);
                        $diffCount = floor($freeWildCount / 4) - floor($slotSettings->GetGameData($slotSettings->slotId . 'BonusState') / 4);
                        if($diffCount >= 1){
                            $freeSpinNum = 10 * $diffCount;
                        }
                    }else if($scattersCount >= 3){
                        $freeNums = [0,0,0,10,15,20];
                        $freeSpinNum = $freeNums[$scattersCount];
                    }
                    $totalWin = $totalWin + $moneyTotalWin; 
                    // if($isJackpot == true){
                    //     break;
                    // }
                    if($holdMoneyCount < 3){
                        $holdMoneyTotalWin = 0;
                    }else{
                        for( $k = 0; $k <= 2; $k++ ) 
                        {
                            if($reels['reel3'][$k] == $holdMoneysymbol){
                                $reels['reel3'][$k] = 14;
                            }
                        }
                    }
                    if( $i > 1000 ) 
                    {
                        $winType = 'none';
                    }
                    if( $i > 2000 ) 
                    {
                        $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"Bad Reel Strip"}';
                        exit( $response );
                    }
                    if( $scattersCount >= 3  && ($winType != 'bonus' || $scattersCount != $defalultScatterCount)) 
                    {
                    }
                    else if($scattersCount >= 3 && $holdMoneyCount >= 3){

                    }
                    else if($holdMoneyCount >= 3 && ($winType != 'bonus' || $slotEvent['slotEvent'] == 'freespin')){

                    }
                    else if($slotEvent['slotEvent'] == 'freespin' && (count($_obf_wildposes) > 2 || (count($_obf_wildposes) == 2 && mt_rand(0, 100) < 95) || $freeWildCount > $slotSettings->GetGameData($slotSettings->slotId . 'FinalWildCount') || $scattersCount > 0)){

                    }
                    else if( $totalWin + $holdMoneyTotalWin * 3 <= $_winAvaliableMoney && $winType == 'bonus' ) 
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
                    else if( $totalWin + $holdMoneyTotalWin * 3 > 0 && $totalWin + $holdMoneyTotalWin * 3 <= $_winAvaliableMoney && $winType == 'win' ) 
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
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeMore', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FinalWildCount', $slotSettings->GetFreeWildCount());
                    }
                    else
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeMore', $slotSettings->GetGameData($slotSettings->slotId . 'FreeMore') + $freeSpinNum);
                    }
                }
                if( $holdMoneyCount >= 3) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', 3);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 0);
                    $slotSettings->GetGameData($slotSettings->slotId . 'RespinLevel', 0);
                }

                $slotSettings->SetGameData($slotSettings->slotId . 'HoldMoneyValue', $_holdmoneyValue);

                for($k = 0; $k < 3; $k++){
                    for($j = 1; $j <= 5; $j++){
                        $lastReel[($j - 1) + $k * 5] = $reels['reel'.$j][$k];
                    }
                }
                $strLastReel = implode(',', $lastReel);
                $strReelSa = $reels['reel1'][3].','.$reels['reel2'][3].','.$reels['reel3'][3].','.$reels['reel4'][3].','.$reels['reel5'][3];
                $strReelSb = $reels['reel1'][-1].','.$reels['reel2'][-1].','.$reels['reel3'][-1].','.$reels['reel4'][-1].','.$reels['reel5'][-1];
                $strFreeSpinResponse = '';
                $isEnd = false;
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', $freeWildCount);
                    $spinType = 's';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $_bonusMuls = [1, 2, 3, 10];
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                    {
                        if($slotSettings->GetGameData($slotSettings->slotId . 'FreeMore') > 0){
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeMore', $slotSettings->GetGameData($slotSettings->slotId . 'FreeMore') - 10);
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 10);
                            $strFreeSpinResponse = '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin').'&reel_set=3&fsmore=10';

                            $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $_bonusMuls[floor($freeWildCount / 4)]);
                        }else{
                            $spinType = 'c';
                            $isEnd = true;
                            $strFreeSpinResponse = '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin').'&reel_set=3';
                        }
                    }
                    else
                    {
                        $strFreeSpinResponse = '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin').'&reel_set=3';
                    }
                    $strFreeSpinResponse = $strFreeSpinResponse . '&accm=cp;cp~mp&acci=0;1&accv='. $freeWildCount .';'. $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') .'~4';
                }else
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                    if($scattersCount >=3 ){
                        $spinType = 's';
                        $strFreeSpinResponse = '&reel_set=0&fsmul=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=0.00&fs=1&fsres=0.00';
                    }else if($holdMoneyCount >= 3){
                        $spinType = 'b';
                        $strFreeSpinResponse = '&rsb_rt=0&bgid=0&coef='.$betline.'&reel_set=1&rw='.$holdMoneyTotalWin.'&bgt=48&lifes=3&bw=1&end=0';
                    }else{
                        $strFreeSpinResponse = '&reel_set=0';
                    }
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $strMoneySymbolResponse = '';
                if($isMoney || $isHoldMoney){                    
                    $CurrentMoneyText = [];
                    for($i = 0; $i < count($_moneyValue); $i++){
                        if($_moneyValue[$i] >= 20){
                            $CurrentMoneyText[$i] = 'v';
                        }else if($_moneyValue[$i] > 0){
                            $CurrentMoneyText[$i] = 'mma';
                        }else{
                            $CurrentMoneyText[$i] = 'r';
                        }
                    }
                    for($i = 0; $i < 3; $i++){
                        if($_holdmoneyValue[$i] > 0){
                            $_moneyValue[$i * 5 + 2] = $_holdmoneyValue[$i];
                            $CurrentMoneyText[$i * 5 + 2] = 'c';
                        }
                    }
                    $strMoneySymbolResponse = '&mo=' . implode(',', $_moneyValue);
                    $strMoneySymbolResponse = $strMoneySymbolResponse . '&mo_t=' . implode(',', $CurrentMoneyText);
                    if($moneyTotalWin > 0){
                        $strMoneySymbolResponse = $strMoneySymbolResponse . '&mo_tv=' . ($moneyTotalWin / $betline) . '&mo_c=1&mo_tw=' . $moneyTotalWin;
                        $percent = mt_rand(0, 100);
                        $changeSymbol = 0;
                        if($percent >= 80){
                            $changeSymbol = 8;
                        }else if($percent == 101){
                            $changeSymbol = 2;
                        }
                        if($changeSymbol > 0){
                            $initReel = [];
                            $srfs = [];
                            for($k = 0; $k < 15; $k++){
                                if($lastReel[$k] == $changeSymbol){
                                    $rand_symbol = mt_rand(3, 12);
                                    if($rand_symbol == $moneysymbol){
                                        $rand_symbol = mt_rand(8, 12);
                                    }
                                    $initReel[$k] = $rand_symbol;
                                    array_push($srfs, $rand_symbol . '~' . $changeSymbol . '~' . $k);
                                }else{
                                    $initReel[$k] = $lastReel[$k];
                                }
                            }
                            $strMoneySymbolResponse = $strMoneySymbolResponse . '&is=' . implode(',', $initReel) . '&srf=' . implode(';', $srfs);
                        }
                    }
                }
                $isState = true;
                if($isEnd == true){
                    $strFreeSpinResponse = $strFreeSpinResponse.'&w='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                }else{
                    $strFreeSpinResponse = $strFreeSpinResponse.'&w='.$totalWin;
                    if($slotEvent['slotEvent'] == 'freespin'){
                        $isState = false;
                    }
                }
                if($scattersCount >= 3 || $holdMoneyCount >= 3){
                    $isState = false;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin').'&balance='.$Balance.'&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType.$strWinLine. $strFreeSpinResponse. $strMoneySymbolResponse.'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&c='.$betline.'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=10&s='.$strLastReel;
                


                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)) 
                {
                    // $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if($holdMoneyCount >= 3){
                    $slotSettings->SetBalance($holdMoneyTotalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $holdMoneyTotalWin);
                    $totalWin = $totalWin + $holdMoneyTotalWin;
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame')  . ',"totalRespinGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') . ',"currentRespinGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') . ',"RespinLevel":' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinLevel') . ',"FreeMore":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeMore') . ',"MoneyValues":' . json_encode($_moneyValue)  . ',"HoldMoneyValues":' . json_encode($_holdmoneyValue)  . ',"Balance":' . $Balance . ',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"FinalWildCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'FinalWildCount') . ',"ReplayGameLogs":'.json_encode($replayLog) . ',"winLines":[],"Jackpots":""' . 
                    ',"LastReel":'.json_encode($lastReel).'}}';
                $slotSettings->SaveLogReport($_GameLog, $betline * $lines, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if( ($scattersCount >= 3 && $slotEvent['slotEvent']!='freespin') || $holdMoneyCount >= 3) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                }
            }
            else if( $slotEvent['slotEvent'] == 'doBonus' ){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lines = 10;
                $holdmoneysymbol = '14';
                $isMoreRespin = false;
               
                if(( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') < $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') && $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') > 0 )) 
                {
                    $slotEvent['slotEvent'] = 'respin';
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'RespinLevel', $slotSettings->GetGameData($slotSettings->slotId . 'RespinLevel') + 1);
                $Balance = $slotSettings->GetBalance();
                // $_obf_winType = rand(1, $slotSettings->GetGambleSettings());
                $_obf_winType = rand(0, 1);
                if($slotSettings->GetGameData($slotSettings->slotId . 'RespinLevel') > 10){
                    if($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') < $betline * $lines * 20){
                        $_obf_winType = 1;
                    }else if(mt_rand(0, 100) > 5){
                        $_obf_winType = 0;
                    } 
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') + 1);
                for($i = 0; $i < 2000; $i++){
                    $moneyTotalWin = 0;
                    $moneyChangedWin = false;
                    $moneyCount = 0;
                    $lastReel = $slotSettings->GetGameData($slotSettings->slotId . 'LastReel');
                    $_holdmoneyValue = $slotSettings->GetGameData($slotSettings->slotId . 'HoldMoneyValue');
                    $_moneyValue = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                    for($k = 0; $k < 3; $k++){
                        if($_holdmoneyValue[$k] > 0){
                            $_moneyValue[$k * 5 + 2] = $_holdmoneyValue[$k];
                        }
                    }
                    for($k = 0; $k < count($lastReel); $k++){
                        if($lastReel[$k] < $holdmoneysymbol){
                            if(rand(0, 100) < $slotSettings->base_money_chance && $_obf_winType == 1){
                                if(mt_rand(0, 100) < 80){
                                    $lastReel[$k] = 14;
                                }else{
                                    $lastReel[$k] = 14;
                                }
                            }else{
                                $lastReel[$k] = 3;
                            }
                        }
                        if($_moneyValue[$k] == 0 && $lastReel[$k] >= $holdmoneysymbol){
                            $_moneyValue[$k] = $slotSettings->GetHoldMoneyWin();
                            $moneyChangedWin = true;
                        }
                        if($_moneyValue[$k] > 0){
                            $moneyTotalWin = $moneyTotalWin + $_moneyValue[$k] * $betline;
                            $moneyCount++;
                        }
                    }
                    if( $_obf_winType== 0 && $slotEvent['slotEvent'] == 'respin' &&  $moneyChangedWin == false){
                        break;
                    }
                    else if($slotSettings->GetGameData($slotSettings->slotId . 'RespinLevel') > 10 && $slotSettings->GetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : '')) < $moneyTotalWin && $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $moneyTotalWin <= $betline * $lines * 40){
                        break;
                    }
                    else if( $slotSettings->GetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : '')) > $moneyTotalWin && $moneyCount < 7 ) 
                    {
                        break;
                    }
                    else if($i > 200){
                        $_obf_winType = 0;
                    }
                }
                $totalWin = 0;
                $isEndRespin = false;
                if($moneyChangedWin == true && $moneyTotalWin > 0){
                    $totalWin = $moneyTotalWin;
                    $moneyTotalWin = 0;
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 0);
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                if($slotSettings->GetGameData($slotSettings->slotId . 'RespinGames')<= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') && $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') > 0){
                    if($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') < $betline * $lines * 20){
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 0);
                    }else{
                        $isEndRespin = true;
                    }
                }
                $strLastReel = implode(',', $lastReel);
                $strCurrentMoneyValue = implode(',', $_moneyValue);
                $CurrentMoneyText = [];
                for($i = 0; $i < count($lastReel); $i++){
                    if($lastReel[$i] == 14){
                        $CurrentMoneyText[$i] = 'c';
                    }else{
                        $CurrentMoneyText[$i] = 'r';
                    }
                }
                $strMoneyText = implode(',', $CurrentMoneyText);

                
                $spinType = 'b';
                $strOtherResponse = '';
                $isState = false;
                if($isEndRespin == true){
                    $spinType = 'cb';
                    $strOtherResponse = '&tw='. $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') .'&end=1';
                    $isState = true;
                }else{
                    $strOtherResponse = '&end=0';
                }
                if($totalWin > 0){
                    $strOtherResponse = $strOtherResponse . '&rsb_rt=1';
                }else{
                    $strOtherResponse = $strOtherResponse . '&rsb_rt=0';
                }
                $response = 'bgid=0&lifes='.($slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') - $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame')).'&balance='.$Balance.'&mo='.$strCurrentMoneyValue.'&mo_t='.$strMoneyText.'&index='. $slotEvent['index'] . '&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType.'&stime=' . floor(microtime(true) * 1000) .'&bgt=48'.$strOtherResponse.'&sver=5&rw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin').'&wp='.($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') / $betline).'&coef='.$betline.'&counter='. ((int)$slotEvent['counter'] + 1) .'&s='.$strLastReel.'';
                
                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame')  . ',"totalRespinGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') . ',"currentRespinGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') . ',"RespinLevel":' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinLevel') . ',"FreeMore":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeMore') . ',"MoneyValues":' . json_encode($_holdmoneyValue)  . ',"HoldMoneyValues":' . json_encode($_holdmoneyValue)  . ',"Balance":' . $Balance . ',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"FinalWildCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'FinalWildCount') . ',"ReplayGameLogs":'.json_encode($replayLog) . ',"winLines":[],"Jackpots":""' . 
                    ',"LastReel":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'LastReel')).'}}';

                $slotSettings->SaveLogReport($_GameLog, $betline * $lines, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                    
                if($isEndRespin == true) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
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

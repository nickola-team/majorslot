<?php 
namespace VanguardLTE\Games\CandyVillagePM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', []);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMplPos', []);
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'DoubleChance', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [3,8,4,8,1,10,6,10,5,7,8,9,6,9,8,7,4,5,3,4,3,8,4,8,1,10,6,10,5,7]);
                $slotSettings->setGameData($slotSettings->slotId . 'BinaryReel', [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalRepeatFreeCount', 0);
                $strtmp = '';
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $lastEvent->serverResponse->tumbWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMul);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMplPos', $lastEvent->serverResponse->BonusMulPos);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', $lastEvent->serverResponse->TumbState);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BinaryReel', $lastEvent->serverResponse->BinaryReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'DoubleChance', $lastEvent->serverResponse->DoubleChance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);                    
                    if (isset($lastEvent->serverResponse->RoundID)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    }
                    if (isset($lastEvent->serverResponse->TotalRepeatFreeCount)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalRepeatFreeCount', $lastEvent->serverResponse->TotalRepeatFreeCount);
                    }
                    $bet = $lastEvent->serverResponse->bet;
                    $strtmp = $lastEvent->serverResponse->strTmb;
                    if (isset($lastEvent->serverResponse->ReplayGameLogs)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', json_decode(json_encode($lastEvent->serverResponse->ReplayGameLogs), true)); //ReplayLog
                    }
                    if (isset($lastEvent->serverResponse->FreeStacks)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', json_decode(json_encode($lastEvent->serverResponse->FreeStacks), true)); // FreeStack
                    }
                }
                else
                {
                    $bet = '100';
                }
                $currentReelSet = 0;
                $spinType = 's';
                $bonusMul = 0;
                $bonusMuls = $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl');
                $bonusMulPoses = $slotSettings->GetGameData($slotSettings->slotId . 'BonusMplPos');
                $arr_bonusmuls = [];
                for($r = 0; $r < count($bonusMuls); $r++){
                    $bonusMul = $bonusMul + $bonusMuls[$r];
                    array_push($arr_bonusmuls,'12~' . $bonusMulPoses[$r] . '~' . $bonusMuls[$r]);
                }
                if($bonusMul == 0){
                    $bonusMul = 1;
                }else{
                    $currentReelSet = 1;
                }
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') < $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') ) 
                {                    
                    $_obf_StrResponse = '&fs=' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&w=0.00&fsmul=1';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance') - $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                }else{
                    $Balance = $slotSettings->GetBalance();
                }
                $_obf_StrResponse = $_obf_StrResponse . '&prg='.$bonusMul;
                if(count($arr_bonusmuls) > 0){
                    $_obf_StrResponse = $_obf_StrResponse . '&rmul=' . implode(';', $arr_bonusmuls);
                }
                if( $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') > 0){
                    $_obf_StrResponse = $_obf_StrResponse.$strtmp.'&rs=t&tmb_win='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbWin').'&rs_p='.($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') - 1).'&rs_c=1&rs_m=1';
                }

                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                $response = 'def_s=3,8,4,8,1,10,6,10,5,7,8,9,6,9,8,7,4,5,3,4,3,8,4,8,1,10,6,10,5,7&prg_m=wm&balance='. $Balance .'&cfgs=1&ver=2&index=1&balance_cash='. $Balance .'&reel_set_size=5&def_sb=5,10,11,8,1,7&prm=12~2,3,4,5,6,8,10,12,15,20,25,50,100;12~2,3,4,5,6,8,10,12,15,20,25,50,100;12~2,3,4,5,6,8,10,12,15,20,25,50,100&def_sa=8,3,4,3,11,3&reel_set='.$currentReelSet.'&prg_cfg_m=wm&balance_bonus=0.00&na='.$spinType.'&scatters=1~2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,2000,100,60,0,0,0~10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,0,0,0~1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1&gmb=0,0,0&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"76335877",max_rnd_win:"3000"}}&bl='.$slotSettings->GetGameData($slotSettings->slotId . 'DoubleChance').'&stime=' . floor(microtime(true) * 1000) .'&sa=8,3,4,3,11,3&sb=5,10,11,8,1,7&sc='. implode(',', $slotSettings->Bet) .'&defc=0.10&prg_cfg=1&sh=5&fspps=2000~10~0&wilds=2~0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0~1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.$_obf_StrResponse.'&sver=5&bls=20,25&counter=2&paytable=0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,500,500,200,200,0,0,0,0,0,0,0;500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,200,200,50,50,0,0,0,0,0,0,0;300,300,300,300,300,300,300,300,300,300,300,300,300,300,300,300,300,300,300,100,100,40,40,0,0,0,0,0,0,0;240,240,240,240,240,240,240,240,240,240,240,240,240,240,240,240,240,240,240,40,40,30,30,0,0,0,0,0,0,0;200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,30,30,20,20,0,0,0,0,0,0,0;160,160,160,160,160,160,160,160,160,160,160,160,160,160,160,160,160,160,160,24,24,16,16,0,0,0,0,0,0,0;100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,20,20,10,10,0,0,0,0,0,0,0;80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,18,18,8,8,0,0,0,0,0,0,0;40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,15,15,5,5,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0&l=20&fspps_mask=bet~fs_count~bet_level&rtp=96.48,96.49,96.51&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=4,11,4,4,9,9,11,8,8,10,10,5,5,8,8,11,4,7,9,9,10,10,4,4,1,3,3,11,5,5,4,7,11,6,6,9,9,10,10,9,5,4,6,8,8,10,4,10,8,11,6,6,10,10,10,8,8,10,10,4,7,7,7~5,5,8,8,11,6,6,10,10,9,9,4,4,1,3,3,11,5,5,4,7,10,10,9,5,4,6,10,10,8,8,11,4,7,9,5,4,6,8,8,10,10,9,9,11,4,4,9,9,11,1,8,8,10,10,4,7,11,11,11~11,11,4,4,7,9,9,10,10,4,4,11,6,6,4,4,9,9,11,8,8,10,10,1,8,8,10,4,10,8,11,6,6,4,7,7,7,5,5,8,8,11,10,10,9,5,4,6,3,3,8,8,5,5,4,7,8,8,10,10,3,3,3~10,4,8,8,10,4,10,8,11,5,5,10,10,9,5,4,6,10,10,4,4,7,11,6,6,9,9,1,3,3,11,3,3,6,6,10,10,10,8,8,11,5,5,4,7,9,9,10,4,10,8,11,4,4,9,9,11,5,5,6,6,11,11,11,6~10,10,4,4,9,9,8,8,4,4,9,9,5,5,8,8,11,4,7,9,9,3,3,11,1,10,10,9,9,5,5,4,7,7,7,10,10,9,9,11,9,5,4,6,8,8,11,6,6,8,8,10,4,10,8,11,6,6,5,5,9,9,9~9,9,8,8,10,4,10,8,11,4,7,9,9,10,10,9,5,4,6,11,9,9,10,10,4,4,4,3,3,9,9,5,5,8,8,11,6,6,4,4,9,9,8,8,6,6,1,11,5,5,4,7,8,8,10,4,10,8,11,6,6,4,4,4,7,7&s='.$lastReelStr.'&reel_set2=4,11,4,4,9,9,11,8,8,10,10,5,5,8,8,11,4,7,9,9,10,10,4,4,1,3,3,11,5,5,4,7,11,6,6,9,9,10,10,9,5,4,6,8,8,10,4,10,8,11,6,6,10,10,10,8,8,10,10,4,7,7,7,6~7,5,5,8,8,11,6,6,10,10,9,9,4,4,1,3,3,11,5,5,4,7,10,10,9,5,4,6,10,10,8,8,11,4,7,9,5,4,6,8,8,10,10,9,9,11,4,4,9,9,11,1,8,8,10,10,4,7,11,11,11~4,11,4,4,7,9,9,10,10,4,4,11,6,6,4,4,9,9,11,8,8,10,10,1,8,8,10,4,10,8,11,6,6,4,7,7,7,5,5,8,8,11,10,10,9,5,4,6,3,3,8,8,5,5,4,7,8,8,10,10,3,3,3,1,6~8,8,10,4,10,8,11,5,5,10,10,9,5,4,6,10,10,4,4,7,11,6,6,9,9,1,3,3,11,3,3,6,6,10,10,10,8,8,11,5,5,4,7,9,9,10,4,10,8,11,4,4,9,9,11,5,5,6,6,9,9,9~4,10,10,4,4,9,9,8,8,4,4,9,9,5,5,8,8,11,4,7,9,9,3,3,11,1,10,10,9,9,5,5,4,7,7,7,10,10,9,9,11,9,5,4,6,8,8,11,6,6,8,8,10,4,10,8,11,6,6,5,5,1~9,9,8,8,10,4,10,8,11,4,7,9,9,10,10,9,5,4,6,11,9,9,10,10,4,4,4,3,3,9,9,5,5,8,8,11,6,6,4,4,9,9,8,8,6,6,1,11,5,5,4,7,8,8,10,4,10,8,11,6,6,4,4,4,6&t=symbol_count&reel_set1=4,11,4,4,9,9,8,8,11,10,10,5,5,8,8,11,4,7,9,9,10,10,4,4,1,3,3,11,5,5,4,7,9,5,4,6,11,10,10,9,5,4,6,8,8,10,4,10,8,11,6,6,10,10,10,8,8,12~5,5,8,8,11,6,6,10,10,9,9,4,4,12,3,3,11,5,5,10,10,4,7,9,5,4,6,10,10,8,8,11,4,7,9,5,4,6,8,8,1,11,9,9,10,10,4,4,9,9,11,8,8,10,10,12,6~11,11,4,4,7,9,9,10,10,4,4,3,3,6,6,4,4,9,9,10,10,8,8,11,1,8,8,10,4,10,8,11,6,6,4,7,7,7,5,5,8,8,11,10,10,9,5,4,6,8,8,3,3,5,5,4,7,8,8,12~4,8,8,10,4,10,8,11,5,5,3,3,9,5,4,6,10,10,4,4,7,11,6,6,9,9,1,10,4,10,8,11,3,3,6,6,10,10,10,8,8,11,5,5,4,7,9,9,11,10,10,4,4,9,9,11,5,5,12,6~9,10,10,4,4,9,9,8,8,4,4,5,5,9,9,8,8,11,4,7,9,9,3,3,11,1,10,10,9,9,5,5,4,7,7,7,9,9,10,4,10,8,11,6,6,8,8,9,9,11,6,6,8,8,10,4,10,8,11,6,6,12~9,9,8,8,10,10,5,5,4,7,11,10,10,9,9,1,6,6,11,9,9,10,10,4,4,12,3,3,9,9,5,5,8,8,11,6,6,4,4,9,9,8,8,6,6,11,9,9,4,7,8,8,10,4,10,8,11,12&reel_set4=4,11,4,4,9,9,11,8,8,10,10,5,5,8,8,11,4,7,9,9,10,10,4,4,3,3,11,5,5,4,7,11,6,6,9,9,10,10,9,5,4,6,8,8,10,4,10,8,11,6,6,10,10,10,8,8,10,10,4,7,7,7~5,5,8,8,11,6,6,10,10,9,9,4,4,4,3,3,11,5,5,4,7,10,10,9,5,4,6,10,10,8,8,11,4,7,9,5,4,6,8,8,8,10,10,9,9,11,4,4,9,9,11,8,8,10,10,4,7,11,11,11~11,11,4,4,7,9,9,10,10,4,4,11,6,6,4,4,9,9,11,8,8,10,10,8,8,10,4,10,8,11,6,6,4,7,7,7,5,5,8,8,11,10,10,10,9,5,4,6,3,3,8,8,5,5,4,7,8,8,10,10,3,3,3~10,4,8,8,10,4,10,8,11,5,5,10,10,9,5,4,6,10,10,4,4,7,11,6,6,9,9,3,3,11,3,3,6,6,10,10,10,8,8,11,5,5,4,7,9,9,9,10,4,10,8,11,4,4,9,9,11,5,5,6,6,11,11,11,6~10,10,4,4,9,9,8,8,4,4,9,9,5,5,8,8,11,4,7,9,9,3,3,11,10,10,9,9,5,5,4,7,7,7,10,10,9,9,11,11,11,9,5,4,6,8,8,11,6,6,8,8,10,4,10,8,11,6,6,5,5,9,9,9~9,9,8,8,10,4,10,8,11,4,7,9,9,10,10,9,5,4,6,11,9,9,10,10,4,4,4,3,3,9,9,5,5,8,8,11,6,6,4,4,9,9,8,8,6,6,6,11,5,5,4,7,8,8,10,4,10,8,11,6,6,4,4,4,7,7&reel_set3=11,11,4,4,9,9,8,8,11,10,10,5,5,8,8,11,4,7,9,9,10,10,4,4,1,3,3,11,5,5,4,7,9,5,4,6,11,10,10,9,5,4,6,8,8,10,4,10,8,11,6,6,10,10,10,8,8,12,6~4,5,5,8,8,11,6,6,10,10,9,9,4,4,12,3,3,11,5,5,10,10,4,7,9,5,4,6,10,10,8,8,11,4,7,9,5,4,6,8,8,1,11,9,9,10,10,4,4,9,9,11,8,8,10,10,12~11,11,4,4,7,9,9,10,10,4,4,3,3,6,6,4,4,9,9,10,10,8,8,11,1,8,8,10,4,10,8,11,6,6,4,7,7,7,5,5,8,8,11,10,10,9,5,4,6,8,8,3,3,5,5,4,7,8,8,12,6~4,8,8,10,4,10,8,11,5,5,3,3,9,5,4,6,10,10,4,4,7,11,6,6,9,9,1,10,4,10,8,11,3,3,6,6,10,10,10,8,8,11,5,5,4,7,9,9,11,10,10,4,4,9,9,11,5,5,12,6~10,10,4,4,9,9,8,8,4,4,5,5,9,9,8,8,11,4,7,9,9,3,3,11,1,10,10,9,9,5,5,4,7,7,7,9,9,10,4,10,8,11,6,6,8,8,9,9,11,6,6,8,8,10,4,10,8,11,6,6,12~6,9,9,8,8,10,10,5,5,4,7,11,10,10,9,9,1,6,6,11,9,9,10,10,4,4,12,3,3,9,9,5,5,8,8,11,6,6,4,4,9,9,8,8,6,6,11,9,9,4,7,8,8,10,4,10,8,11,12,6&total_bet_min=200.00';
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
                if(isset($slotEvent['fsp'])){
                    $isbuyfreespin = $slotEvent['fsp'];
                }
                $isdoublechance = $slotEvent['bl'];
                $slotSettings->SetGameData($slotSettings->slotId . 'DoubleChance', $isdoublechance);
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
                    if($isdoublechance == 1 && $isbuyfreespin == 0){
                        $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"invalid bonus"}';
                        exit( $response );
                    }
                    if( $slotEvent['slotEvent'] == 'doSpin' && $slotSettings->GetBalance() < ($lines * $betline) && $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') == 0 ) 
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
                if($isdoublechance == 1){
                    $allBet = $betline * 25;
                    $reelSet_Num = 2;
                }
                if($isbuyfreespin == 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $betline * 2000;
                }else{
                    $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $allBet, $lines, $isdoublechance);
                    $winType = $_spinSettings[0];
                    $_winAvaliableMoney = $_spinSettings[1];
                }
                $isTumb = false;
                $lastReel = null;
                $isGeneratedFreeStack = false;
                $freeStacks = []; // free stacks
                $isForceWin = false;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalRepeatFreeCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalRepeatFreeCount') + 1);
                    $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                    if(count($freeStacks) >= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames')){
                        $isGeneratedFreeStack = true;
                    }
                }
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
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                        $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $isbuyfreespin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                        $roundstr = sprintf('%.4f', microtime(TRUE));
                        $roundstr = str_replace('.', '', $roundstr);
                        $roundstr = '275' . substr($roundstr, 4, 7);
                        $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []);
                        $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'RegularSpinCount') + 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalRepeatFreeCount', 0);
                        $leftFreeGames = 0;
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
                for( $i = 0; $i <= 2000; $i++ ) 
                {
                    $totalWin = 0;
                    $winLines = [];
                    $wild = '2';
                    $scatter = '1';
                    $bonus = '12';
                    $_obf_winCount = 0;
                    $strWinLine = '';
                    $freeMul = 1;
                    $winLineMulNums = [];                    
                    $bonusMuls = $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl');
                    $bonusMulPoses = $slotSettings->GetGameData($slotSettings->slotId . 'BonusMplPos');
                    $bonusMul = 0;
                    if($isGeneratedFreeStack == true){
                        //freestack
                        $freeStack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalRepeatFreeCount') - 1];
                        $reels = $freeStack['Reel'];
                        $bonusMuls = $freeStack['BonusMuls'];
                        $bonusMulPoses = $freeStack['BonusMulPoses'];
                    }else{
                        if($isTumb == true && $lastReel != null){
                            $reels = $slotSettings->GetTumbReelStrips($lastReel, $reelSet_Num);
                        }else{
                            // if($isbuyfreespin == 0 && mt_rand(0, 100) < 50){
                            //     $reels = $slotSettings->GetBuyFreeSpinReelStrips($defaultScatterCount);
                            // }else{
                                $reels = $slotSettings->GetReelStrips($winType, $slotEvent['slotEvent'], $reelSet_Num, $defaultScatterCount);
                            // }
                        }
                    }
                    for($j = 1; $j < 13; $j++){
                        $arr_symbols = [];
                        for( $k = 0; $k < 5; $k++ ) 
                        {
                            for($r = 0; $r < 6; $r++){ 
                                if( $reels['reel' . ($r+1)][$k] == $j) 
                                {                                
                                    array_push($arr_symbols, $r + $k * 6);
                                }
                            }
                        }                      
                        $winLines[$j] = $arr_symbols;
                    }
                    $binaryReel = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                    $isNewTumb = false;
                    $winlineindex = 0;
                    for($r = 3; $r < 12; $r++){
                        $winLine = $winLines[$r];
                        $winLineMoney = $slotSettings->Paytable[$r][count($winLine)] * $betline;
                        if($winLineMoney > 0){
                            $isNewTumb = true;
                            $strWinLine = $strWinLine . '&l'. $winlineindex.'='.$winlineindex.'~'.$winLineMoney . '~' . implode($winLine, '~');
                            for($k = 0; $k < count($winLine); $k++){
                                $binaryReel[$winLine[$k]] = $r;
                            }
                            $winlineindex++;
                            $totalWin += $winLineMoney;
                        }
                    }                
                    $freeSpinNum = 0;
                    $scattersCount = count($winLines[$scatter]);
                    if($slotEvent['slotEvent'] == 'freespin'){
                        if($scattersCount >= 3){
                            $freeSpinNum = $slotSettings->freespinCount/2;
                        }
                    }else{
                        if($scattersCount >= 4){
                            $freeSpinNum = $slotSettings->freespinCount;
                        }
                    }
                    $scattersWin = $slotSettings->Paytable[$scatter][$scattersCount] * $betline;
                    $totalWin = $totalWin + $scattersWin;
                    
                    if($isGeneratedFreeStack == false){
                        for($r = 0; $r < count($winLines[$bonus]); $r++){
                            $isExit = false;
                            for($k = 0; $k < count($bonusMulPoses);$k++){
                                if($winLines[$bonus][$r] == $bonusMulPoses[$k]){
                                    $isExit = true;
                                    break;
                                }
                            }
                            if($isExit == false){
                                array_push($bonusMuls, $slotSettings->GetBonusMul($isTumb, $winType));
                                array_push($bonusMulPoses, $winLines[$bonus][$r]);
                            }
                        }
                    }
                    $isdoubleScatter = false;
                    for($r = 0; $r < 6; $r++){ 
                        $lineScatters = 0;
                        for( $k = 0; $k < 5; $k++ ) 
                        {
                            if( $reels['reel' . ($r+1)][$k] == $scatter) 
                            {                                
                                $lineScatters++;
                            }
                        }                      
                        if($lineScatters >= 2){
                            $isdoubleScatter = true;
                            break;
                        }
                    }
                    
                    for($k = 0; $k < count($bonusMuls);$k++){
                        $bonusMul = $bonusMul + $bonusMuls[$k];
                    }
                    if($bonusMul == 0){
                        $bonusMul = 1;
                    }
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
                        if( ($scattersCount >= 4 || ($scattersCount >= 3 && $slotEvent['slotEvent'] == 'freespin')) && ($winType != 'bonus'  || $scattersCount != $defaultScatterCount)) 
                        {
                            if($winType == 'none' && ($totalWin - $scattersWin) == 0 && $i > 500){
                                break;
                            }
                        }
                        else if($scattersCount >= 5){
                            
                        }
                        else if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') >= 15 && $freeSpinNum > 0){
                        
                        }
                        else if($isbuyfreespin == 0 || ($totalWin * $bonusMul <= $_winAvaliableMoney && $winType == 'bonus')) 
                        {
                            break;
                        }else if($isdoubleScatter == true){

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
                        else if( $totalWin > 0 && ($totalWin + $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin')) * $bonusMul <= $_winAvaliableMoney && $winType == 'win' )
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
                $_obf_totalWin = $totalWin;
                if( $totalWin > 0) 
                {
                    $spinType = 'c';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }else if($bonusMul > 1 && $slotEvent['slotEvent'] == 'freespin'){
                    $totalWin = $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') * ($bonusMul - 1);
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }
                for($k = 0; $k < 5; $k++){
                    for($j = 1; $j <= 6; $j++){
                        $lastReel[($j - 1) + $k * 6] = $reels['reel'.$j][$k];
                    }
                }
                
                $strLastReel = implode(',', $lastReel);
                $strReelSa = $reels['reel1'][5].','.$reels['reel2'][5].','.$reels['reel3'][5].','.$reels['reel4'][5].','.$reels['reel5'][5].','.$reels['reel6'][5];
                $strReelSb = $reels['reel1'][-1].','.$reels['reel2'][-1].','.$reels['reel3'][-1].','.$reels['reel4'][-1].','.$reels['reel5'][-1].','.$reels['reel6'][-1];               
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'BinaryReel', $binaryReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $bonusMuls);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMplPos', $bonusMulPoses);
                $isState = true;
                if($isNewTumb == true){
                    $totalWin = $totalWin - $scattersWin;
                    $scattersWin = 0;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') + 1);
                    $isState = false;
                }else{
                    if( $freeSpinNum > 0 ) 
                    {
                        
                        if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0 ) 
                        {
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freeSpinNum);
                            $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalRepeatFreeCount', 0);
                            // FreeStack
                            if($slotSettings->IsAvailableFreeStack() || $slotSettings->happyhouruser){
    
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $slotSettings->GetFreeStack($betline, $scattersCount - 4));
                            }
                        }
                        else
                        {
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $freeSpinNum);
                        }
                        $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
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
                        $otherResponse = '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul_total=1&prg='.$bonusMul.'&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                    else
                    {
                        $otherResponse = '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&prg='.$bonusMul.'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $isState = false;
                    }

                    $arr_bonusmuls = [];
                    for($r = 0; $r < count($bonusMuls); $r++){
                        array_push($arr_bonusmuls,'12~' . $bonusMulPoses[$r] . '~' . $bonusMuls[$r]);
                    }
                    if(count($arr_bonusmuls) > 0){
                        $otherResponse = $otherResponse . '&rmul=' . implode(';', $arr_bonusmuls);
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
                        $otherResponse = '&fsmul=1&prg=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fswin=0.00&fs=1&fsres=0.00&psym=1~' . $scattersWin.'~' . implode($winLines[$scatter], ',');
                    }else{
                        $otherResponse = '&prg=1';
                    }
                }

                if($isbuyfreespin == 0){
                    $otherResponse = $otherResponse.'&fs_bought=10';
                }
                $strtmp = '';
                if($isNewTumb == true){
                    $spinType = 's';
                    $firstsymbolstr = '';
                    for($r = 0; $r < 30; $r++){
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
                    $otherResponse = $otherResponse.$strtmp.'&rs=t&tmb_win='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbWin').'&rs_p='.($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') - 1).'&rs_c=1&rs_m=1';
                }else{
                    if($isTumb == true){
                        if($slotEvent['slotEvent'] != 'freespin' && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0){
                            $spinType = 'c';
                        }
                        $otherResponse = $otherResponse.'&tmb_res='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbWin').'&rs_t='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbleState').'&tmb_win='.($slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') - $totalWin);
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', 0);
                } 
                if($isTumb == true){
                    $otherResponse = $otherResponse.'&rs_win='.$_obf_totalWin;
                }
                // if($isEnd == true){
                //     $otherResponse = $otherResponse.'&w='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                // }else{
                //     $otherResponse = $otherResponse.'&w='.$_obf_totalWin;
                // }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .'&prg_m=wm&balance='.$Balance.'&bl='.$isdoublechance.'&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType.$strWinLine.'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=5&c='.$betline.'&sver=5&reel_set='.$n_reel_set.$otherResponse.'&counter='. ((int)$slotEvent['counter'] + 1).'&w='.$_obf_totalWin .'&l=20&s='.$strLastReel;


                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) && $isNewTumb == false) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
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

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"TumbState":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') . ',"Balance":' . $Balance . ',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $totalWin . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .   ',"freeBalance":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance') .  ',"ReplayGameLogs":'.json_encode($replayLog).',"tumbWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') . ',"winLines":[],"Jackpots":""' . ',"BonusMul":'.json_encode($bonusMuls).  ',"BonusMulPos":'.json_encode($bonusMulPoses). ',"DoubleChance":'.$slotSettings->GetGameData($slotSettings->slotId . 'DoubleChance').  ',"BuyFreeSpin":'.$slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin').  ',"TotalRepeatFreeCount":'.$slotSettings->GetGameData($slotSettings->slotId . 'TotalRepeatFreeCount') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID').',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"strTmb":"'.$strtmp.  '","LastReel":'.json_encode($lastReel). ',"BinaryReel":'.json_encode($binaryReel).'}}';
                if($slotEvent['slotEvent'] == 'freespin' && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                    $allBet = $betline * 2000;
                }
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin'), $slotEvent['slotEvent'], $isState);
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

<?php 
namespace VanguardLTE\Games\_5LionsGoldPM
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
                // if(isset($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame')) && isset($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames'))){
                    if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                        $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    }
                // }
                $response = 'balance=' . $Balance . '&balance_cash=' . $Balance . '&balance_bonus=0.00&na=s&stime=' . floor(microtime(true) * 1000);
                exit( $response );
            }
            if( $slotEvent['slotEvent'] == 'doInit' ) 
            { 
                // $lastEvent = $slotSettings->GetHistory();
                // $_obf_StrResponse = '';
                // $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                // $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                // $slotSettings->SetGameData($slotSettings->slotId . 'FreeSpinType', 0);
                // $slotSettings->SetGameData($slotSettings->slotId . 'FreeOPT', 0);
                // $slotSettings->SetGameData($slotSettings->slotId . 'WildStep', 0);
                // $slotSettings->SetGameData($slotSettings->slotId . 'TotalScatters', 0);
                // $slotSettings->SetGameData($slotSettings->slotId . 'InitScatters', 0);
                // $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                // $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                // $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                // $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                // $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1); 
                // $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                // $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [3,4,5,6,7,8,9,10,2,11,7,6,1,4,3]);
                // $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                // $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
                // $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', 0);
                // $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                // $slotSettings->SetGameData($slotSettings->slotId . 'scatterUpDownSameMaskCounts', [0,0,0,0,0,0,0,0,0,0]);
                // if( $lastEvent != 'NULL' ) 
                // {
                //     $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                //     $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                //     $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                //     $slotSettings->SetGameData($slotSettings->slotId . 'FreeSpinType', $lastEvent->serverResponse->FreeSpinType);
                //     $slotSettings->SetGameData($slotSettings->slotId . 'FreeOPT', $lastEvent->serverResponse->FreeOPT);
                //     $slotSettings->SetGameData($slotSettings->slotId . 'WildStep', $lastEvent->serverResponse->WildStep);
                //     $slotSettings->SetGameData($slotSettings->slotId . 'TotalScatters', $lastEvent->serverResponse->TotalScatters);
                //     $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);
                //     $slotSettings->SetGameData($slotSettings->slotId . 'InitScatters', $lastEvent->serverResponse->InitScatters);
                //     $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                //     $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                //     $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                //     $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                //     $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                //     if (isset($lastEvent->serverResponse->ReplayGameLogs)){
                //         $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', json_decode(json_encode($lastEvent->serverResponse->ReplayGameLogs), true)); //ReplayLog
                //     }
                //     if (isset($lastEvent->serverResponse->FreeStacks)){
                //         $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', json_decode(json_encode($lastEvent->serverResponse->FreeStacks), true)); // FreeStack
                //     }
                //     $bet = $lastEvent->serverResponse->bet;
                // }
                // else
                // {
                //     $bet = 100;
                // }
                // $currentReelSet = 0;
                // $spinType = 's';
                // $freeOPT = $slotSettings->GetGameData($slotSettings->slotId . 'FreeOPT');
                // if($freeOPT > 0){
                //     $spinType = 'fso';
                //     $_obf_StrResponse = '&fs_opt_mask=fs,m,msk&accm=cp~tp~lvl~sc&acci=0&fs_opt=5,1,0~5,1,0&accv='.$freeOPT.'~5~1~'.$freeOPT;
                // }
                // else if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                //     $strAccv = '&acci=0&accm=cp~tp~lvl~sc&accv='.($slotSettings->GetGameData($slotSettings->slotId . 'TotalScatters') % 5).'~5~'.(floor($slotSettings->GetGameData($slotSettings->slotId . 'TotalScatters') / 5) + 1).'~' .($slotSettings->GetGameData($slotSettings->slotId . 'TotalScatters') % 5);

                //     $_obf_StrResponse = '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .'&fsopt_i=' . ($slotSettings->GetGameData($slotSettings->slotId . 'FreeSpinType') - 1) . $strAccv;
                //     if($slotSettings->GetGameData($slotSettings->slotId . 'FreeSpinType') == 1){
                //         $currentReelSet = 6;
                //     }else if($slotSettings->GetGameData($slotSettings->slotId . 'FreeSpinType') == 2){
                //         $currentReelSet = 5;
                //     }

                //     if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                //         $_obf_StrResponse = $_obf_StrResponse.'&puri=0&purtr=1';
                //     }
                // }
                
                // $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                // $Balance = $slotSettings->GetBalance();

                // $response = 'def_s=3,4,5,6,7,8,9,10,2,11,7,6,1,4,3&balance='. $Balance . $_obf_StrResponse .'&cfgs=6707&ver=2&index=1&balance_cash='. $Balance .'&def_sb=1,2,3,4,5&reel_set_size=10&def_sa=1,2,3,4,5&reel_set='. $currentReelSet .'&balance_bonus=0.00&na='.$spinType.'&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&gameInfo={rtps:{purchase:"96.47",regular:"96.47"},props:{max_rnd_sim:"1",max_rnd_hr:"836890",max_rnd_win:"5000"}}&wl_i=tbm~5000&stime=' . floor(microtime(true) * 1000) .'&sa=1,2,3,4,5&sb=1,2,3,4,5&sc='. implode(',', $slotSettings->Bet) .'&defc=100.00&sh=3&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;600,100,50,0,0;300,50,25,0,0;200,40,20,0,0;150,25,12,0,0;100,20,10,0,0;60,12,6,0,0;60,12,6,0,0;50,10,5,0,0;50,10,5,0,0&l=20&rtp=96.47&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=5,8,10,7,11,6,9,11,4,5,5,3,10,10,9,6,4,6,8,9,10,1,11,9,10,8,8,7,7,9,8,3,11,3~7,5,4,1,10,2,7,10,4,8,6,11,9,7,10,3,8,11,9,5,4,9,10,11,9,11,3,4,9,5,6,8,11,11,6,2,8,4,11,8,10,1,5,7,6,9~10,7,9,4,3,11,3,6,2,11,7,5,9,10,1,9,4,7,2,5,11,11,8,10,3,2,10,6,9,9,8,1,5,5,9,10,2,10,11,4,4,7,8,9,5,8,8,4,8,7~10,3,8,2,2,9,10,3,5,4,1,8,9,4,6,9,3,9,11,7,10,4,6,11,5,11,8~9,10,6,9,11,9,4,7,9,4,8,6,4,1,10,7,5,6,3,3,8,11,5,9,8,11,10,3,8,10,5,3,8&s='.$lastReelStr.'&accInit=[{id:0,mask:"cp;tp;lvl;sc;cl"}]&reel_set2=5,10,9,3,7,4,11,8,7,10,8,6,8,4,10,8,7,3,6,9,6,8,9,5,5,4,9,10,10,8,4,3,4,9,7,11,10,10,9,11,5,11,3,9,11~6,7,9,4,3,2,8,8,9,7,9,5,8,2,11,11,7,10,9,6,6,5,11,10,11,4,11,3,4,11,9,2,10,3,5~9,2,9,11,8,2,10,10,2,11,8~10,7,6,3,5,3,10,11,4,3,9,3,3,9,6,9,10,9,8,11,8,3,11,5,2,5,11,2,8,6,9,5,4,8,7,10,11,9,7,9,5,4,11,8,8,4,10~8,5,10,8,9,9,4,8,9,11,9,3,10,4,11,7,8,5,11,6,7,3,9,4,11,10,6,3,5,6,6,4,10,3,9,8,5,5,10,7,8,7,10,11&reel_set1=10,9,7,7,8,10,11,8,4,5,11,11,9,4,3,8,6,10,10,3,8,10,7,11,8,5,3,5,10,9,4,4,5,9,6,6,3,9,4,9,11,8,7~11,2,2,11,11,8,10,2,9,8,10,9,2,10~7,11,9,9,8,10,9,4,11,5,9,5,4,10,4,3,9,9,10,11,3,6,6,5,8,6,7,4,8,8,3,6,7,7,5,4,8,5,10,10,8,5,10,2,3,2,2,11~4,10,3,10,4,7,9,11,9,3,11,10,8,7,4,5,8,9,5,8,6,2,9,3,5,11,3,8,6,2,11~11,8,10,5,5,3,10,9,5,8,7,5,4,6,9,11,4,6,10,8,5,9,8,3,9,8,11,10,3,9,10,11,4,6,8,11,7,7,9,6,3,7,4&reel_set4=9,8,8,11,5,11,6,10,3,11,1,3,5,10,4,7,9,9,4,8,4,10,7~8,9,11,7,10,2,6,11,5,10,10,11,3,11,5,3,2,7,9,9,2,8,11,6,9,8,2,6,11,4,6,3,5,10,4,2,8,11,5,1,9,9~1,2,2,3,4,4,5,5,6,7,8,8,9,9,10,10,11,11~7,1,6,7,10,1,3,9,11,5,2,3,11,3,9,5,8,9,8,11,11,2,8,9,4,2,10,8,4,6,10~10,8,9,8,1,3,4,7,10,10,7,9,9,10,6,10,11,9,6,5,8,4,3,9,4,8,6,3,5,5,7,11&purInit=[{type:"fs",bet:1500}]&reel_set3=10,6,8,5,7,9,11,3,6,3,9,10,4,10,4,11,7,8,9,8,5~10,4,3,8,6,2,11,5,3,6,7,11,9,4,7,10,8,5,11,2,9,9~2,8,7,6,5,9,4,9,8,10,3,2,11,8,9,10,10,11,3,6,6,2,10,7,4,11,5,5,3,5,8,9,4~9,11,9,2,8,2,10,11,10,8,2~7,8,3,9,6,9,8,3,4,9,11,11,5,11,6,4,8,7,6,3,11,7,9,5,8,5,8,4,10,8,8,7,3,8,9,10,7,10,4,8,10,3,6,5,9,10,9,4,11,10,7,5,10,11,11,9,4,6,3,10,9,5,6,5&reel_set6=8,10,8,10,9,5,11,4,3,7,7,3,11,11,8,6,5,9,9,7,10,6,11,10,10,9,8,4,3,9,4,5,4,1~10,11,8,9,10,5,10,9,2,2,11,9,6,3,9,3,6,11,7,8,9,11,5,4,8,11,2,6,5,11,7,5,4,1,2,8,11,10,9,2,6,4~4,2,3,4,9,9,8,4,9,3,5,2,5,8,7,7,6,10,11,2,1,6,10,5,8,8,9,10,11,10,11~9,3,3,4,10,8,2,11,7,11,2,9,8,9,1,6,5,10~9,11,7,9,3,5,7,10,8,10,8,9,6,11,6,10,3,5,8,4,8,5,6,11,9,1,3,4&reel_set5=6,11,8,5,3,8,6,4,9,10,5,8,5,8,10,8,9,5,8,11,4,9,9,10,10,3,10,1,9,3,10,9,4,9,7,6,7,4,3,11,7,10,11~1,9,11,5,9,7,5,4,2,2,3,6,11,2,8,11,10,7,11,10,8,5,5,3,2,4,3,9,3,4,10,6,8,9,10,8,11~9,7,5,1,4,8,11,10,6,4,10,8,3,1,8,2,3,4,5,10,11,8,7,9,11,9,7,3,9,5,6,5,9,2,10,11,10,5,2,6,2,8,4,11~1,2,2,3,3,4,5,6,7,8,8,9,9,10,10,11,11~1,3,3,4,5,6,7,8,8,9,9,10,11,11&reel_set8=5,4,11,8,10,5,6,7,1,7,4,10,9,10,8,7,9,9,3,3,8,10,9,5,5,8,11,11,7,9,10,9,10,9,10,4,3,4,4,8,11,11,8,6,3~9,2,11,5,6,1,10,11,3,11,2,5,6,8,4,2,7,4,9,11,10,9,8~10,11,11,7,3,5,11,9,10,4,9,4,11,5,9,5,1,7,5,4,8,10,10,9,9,6,2,8,3,1,10,4,8,5,8,6,2,3,7,6,2,8,2~2,3,8,11,9,5,5,6,10,8,3,9,7,4,11,1,2,4,10,11,6,8,9~1,10,8,11,9,4,4,3,10,8,9,3,8,10,6,7,3,6,11,6,8,10,3,6,7,10,5,1,5,7,9,8,9,3,8,11,5,9,5,9,8,9,3,11,5,4,7,6,11,10,4&reel_set7=9,7,8,4,11,5,3,1,9,8,3,6,10,10,4,9,8~8,4,2,8,11,6,9,3,9,9,11,4,10,2,2,3,5,9,4,10,6,9,11,11,9,10,6,7,8,5,3,1,5,2,8,7,11,6,11,2,10~2,11,8,9,5,10,11,11,7,2,9,10,3,4,1,6,8,2,8,9,6,10,9,3,5,4,6,7,5,4,5,3,10,5~1,2,2,3,3,4,5,6,7,8,8,9,9,10,10,11,11~9,4,7,11,5,5,8,11,7,4,5,6,11,9,7,9,8,8,5,6,10,10,6,4,8,1,9,10,11,3,9,8,4,6,1,10,7,8,8,11,3,3,9,10,3,10,8,6,8,5,10,9,3,3&reel_set9=8,9,5,10,9,10,8,4,11,1,6,10,4,11,7,5,3,9,3~9,9,10,8,9,8,10,11,5,9,2,4,11,6,1,3,2,6,11,11,5,2,7~4,6,6,10,2,10,9,9,5,9,3,11,3,2,11,7,7,2,5,9,4,8,2,1,6,4,11,1,10,10,8,4,3,5,4,3,2,10,8,8,9,5,10,11,11,6,7,9,9,5,8,8,6,11~4,2,7,2,10,6,9,3,8,11,1,5,11,6,5,8,3,10,9,11,9,10,2~3,7,8,4,5,8,9,1,5,5,8,7,11,11,9,10,9,3,6,6,10,7,8,8,3,11,9,9,8,3,6,10,6,9,1,11,4,8,5,10,11,4,10,9,9,10,5,10,11,4,3,10,4,7,6&total_bet_min=10.00';
                $response = 'def_s=4,3,7,7,4,11,5,6,7,11,10,5,9,8,8&balance=385,410.00&cfgs=3001&ver=2&index=1&balance_cash=385,410.00&reel_set_size=8&def_sb=7,11,8,10,6&def_sa=6,3,5,8,11&bonusInit=[{bgid:0,bgt:33,bg_i:"15,150,2000",bg_i_mask:"wp,wp,wp"},{bgid:2,bgt:18,bg_i:"2000,150,15",bg_i_mask:"pw,pw,pw"}]&balance_bonus=0.00&wrlm_sets=2~0~2,3,5~1~3,5,8~2~5,8,10~3~8,10,15~4~10,15,30~5~15,30,40&na=s&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&fs_aw=t;n&gmb=0,0,0&rt=d&base_aw=t;n&stime=1654141669961&sa=6,3,5,8,11&sb=7,11,8,10,6&sc=10.00,20.00,50.00,100.00,250.00,500.00,1000.00,3000.00,5000.00&defc=100.00&sh=3&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c=100.00&sver=5&n_reel_set=0&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;126,45,15,2,0;81,30,12,0,0;81,30,12,0,0;63,24,9,0,0;63,24,9,0,0;36,12,4,0,0;36,12,4,0,0;27,9,3,0,0;27,9,3,0,0;18,6,2,0,0;18,6,2,0,0&l=18&reel_set0=9,8,10,5,10,13,5,7,13,8,12,5,4,7,7,3,4,9,9,8,5,5,12,3,3,6,6,4,4,11,11,13,4,8,8,13,3,9,7,11,13,7,6,11,7,12,9,13,13,3,8,6,12,13,4,11,7,8,11,10,10,3,6,12,10,12,5,6,11,10,6,10,13,9,10,7,12,8,12,11,9,11,8,9,13,12,5,9,6,10,4~4,6,9,9,1,11,11,13,2,7,8,8,12,6,10,2,3,13,7,11,9,3,9,12,8,6,5,5,3,7,11,13,8,1,5,8,9,4,6,10,4,4,13,13,1,5,10,10,3,6,9,11,1,10,6,4,12,2,6,6,4,9,13,8,1,3,10,12,12,11,5,3,12,13,2,4,12,7,7,10,13,9,2,11,5,5,9,8,1,6,7,11,2,10,12,10,8,1,7,4,13,12,7,8~4,12,9,13,11,6,2,7,4,4,5,8,3,12,9,9,1,8,11,8,10,11,2,12,4,3,10,11,12,5,1,8,6,4,13,13,13,11,11,2,6,8,3,7,10,5,5,3,10,7,6,6,4,1,3,6,7,10,9,13,1,12,5,12,12,2,8,8,5,12,11,9,2,6,7,13,13,4,6,2,7,13,12,3,6,7,7,10,10,8,1,5,10,8,7,13,9,9,13,11,1,9,9,10,11~13,6,13,7,7,10,6,1,8,9,9,6,10,2,12,13,10,10,8,11,2,12,6,5,13,7,2,5,9,5,4,1,11,11,11,12,12,4,7,4,6,8,1,9,13,13,12,6,3,10,5,10,7,3,8,4,9,3,4,6,6,12,1,8,8,12,7,3,10,10,9,11,2,12,10,5,9,2,13,11,8,11,11,2,13,9,5,5,3,12,9,8,11,3,7,4,4,13,6,7,13,8,12,4,5,7,11~13,8,10,13,5,13,5,5,9,11,10,6,13,6,10,10,11,9,9,13,13,11,6,3,8,10,7,7,5,3,12,7,10,4,12,12,11,8,11,13,12,4,6,8,11,6,6,3,8,12,7,13,4,6,9,3,12,4,4,9,12,11,11,7,8,8,10,5,4,12,3,13,7,11,6,9,8,7,3,9,10,5,9,7,4,12,10,9,8&s=4,3,7,7,4,11,5,6,7,11,10,5,9,8,8&reel_set2=9,8,10,5,10,13,5,7,7,13,8,12,12,5,4,7,3,4,9,8,5,12,3,6,4,11,13,4,8,13,3,9,7,11,13,7,6,11,7,12,9,13,3,8,8,6,12,13,13,4,11,7,8,11,10,3,6,12,10,12,5,5,6,6,11,10,6,10,13,9,10,7,12,8,12,11,9,11,8,9,13,12,5,9,6,10,4~4,6,9,1,11,11,13,2,7,8,12,6,10,2,3,13,7,11,9,3,9,12,8,6,6,5,5,3,3,7,11,13,8,1,5,8,9,4,6,10,4,13,13,1,5,10,3,6,9,9,11,1,10,6,4,12,2,6,6,4,9,13,8,1,3,10,12,12,11,5,3,12,13,13,13,2,4,12,7,7,10,13,9,2,11,5,5,9,8,1,6,7,11,2,10,12,10,8,1,7,4,13,12,7,8~4,12,9,13,11,6,2,7,4,5,8,3,12,9,1,8,11,8,10,11,2,12,4,3,10,11,12,5,1,8,6,4,13,11,11,2,6,8,3,7,10,5,5,3,10,10,7,6,4,1,3,6,7,10,9,13,1,12,5,12,2,8,5,12,11,9,2,6,7,13,13,4,6,6,2,7,13,12,3,6,7,10,8,1,5,10,8,7,7,13,9,9,13,11,1,9,9,10,11~13,6,13,7,10,6,1,8,9,6,10,2,12,13,10,8,11,2,12,6,5,13,7,2,5,9,5,4,1,11,12,12,4,7,4,6,8,8,1,9,13,13,6,3,10,5,10,7,3,8,4,9,3,4,6,12,1,8,12,7,7,3,10,10,9,9,11,2,12,10,5,9,2,13,11,8,11,2,13,9,5,3,12,9,8,11,3,7,4,4,13,6,7,13,8,12,4,5,7,11~13,8,10,13,5,13,5,5,9,11,10,6,13,6,10,11,9,13,11,11,6,3,8,10,10,7,5,3,12,7,10,4,4,12,11,8,11,13,12,4,6,8,11,6,6,6,3,8,12,7,7,13,4,6,9,3,12,4,9,9,12,11,7,8,10,5,4,12,3,13,7,11,6,9,8,7,3,9,10,5,9,7,4,12,10,9,8&t=243&reel_set1=9,8,10,5,10,13,5,7,7,13,8,12,12,5,4,7,3,4,9,8,5,12,3,6,4,11,13,4,8,13,3,9,7,11,13,7,6,11,7,12,9,13,3,8,8,6,12,13,13,4,11,7,8,11,10,3,6,12,10,12,5,5,6,6,11,10,6,10,13,9,10,7,12,8,12,11,9,11,8,9,13,12,5,9,6,10,4~4,6,9,1,11,11,13,2,7,8,12,6,10,2,3,13,7,11,9,3,9,12,8,6,6,5,5,3,3,7,11,13,8,1,5,8,9,4,6,10,4,13,13,1,5,10,3,6,9,9,11,1,10,6,4,12,2,6,6,4,9,13,8,1,3,10,12,12,11,5,3,12,13,13,13,2,4,12,7,7,10,13,9,2,11,5,5,9,8,1,6,7,11,2,10,12,10,8,1,7,4,13,12,7,8~4,12,9,13,11,6,2,7,4,5,8,3,12,9,1,8,11,8,10,11,2,12,4,3,10,11,12,5,1,8,6,4,13,11,11,2,6,8,3,7,10,5,5,3,10,10,7,6,4,1,3,6,7,10,9,13,1,12,5,12,2,8,5,12,11,9,2,6,7,13,13,4,6,6,2,7,13,12,3,6,7,10,8,1,5,10,8,7,7,13,9,9,13,11,1,9,9,10,11~13,6,13,7,10,6,1,8,9,6,10,2,12,13,10,8,11,2,12,6,5,13,7,2,5,9,5,4,1,11,12,12,4,7,4,6,8,8,1,9,13,13,6,3,10,5,10,7,3,8,4,9,3,4,6,12,1,8,12,7,7,3,10,10,9,9,11,2,12,10,5,9,2,13,11,8,11,2,13,9,5,3,12,9,8,11,3,7,4,4,13,6,7,13,8,12,4,5,7,11~13,8,10,13,5,13,5,5,9,11,10,6,13,6,10,11,9,13,11,11,6,3,8,10,10,7,5,3,12,7,10,4,4,12,11,8,11,13,12,4,6,8,11,6,6,6,3,8,12,7,7,13,4,6,9,3,12,4,9,9,12,11,7,8,10,5,4,12,3,13,7,11,6,9,8,7,3,9,10,5,9,7,4,12,10,9,8&reel_set4=9,8,10,5,10,13,5,7,7,13,8,12,12,5,4,7,3,4,9,8,5,12,3,6,4,11,13,4,8,13,3,9,7,11,13,7,6,11,7,12,9,13,3,8,8,6,12,13,13,4,11,7,8,11,10,3,6,12,10,12,5,5,6,6,11,10,6,10,13,9,10,7,12,8,12,11,9,11,8,9,13,12,5,9,6,10,4~4,6,9,1,11,11,13,2,7,8,12,6,10,2,3,13,7,11,9,3,9,12,8,6,6,5,5,3,3,7,11,13,8,1,5,8,9,4,6,10,4,13,13,1,5,10,3,6,9,9,11,1,10,6,4,12,2,6,6,4,9,13,8,1,3,10,12,12,11,5,3,12,13,13,2,4,12,7,7,10,13,9,2,11,5,5,9,8,1,6,7,11,2,10,12,10,8,1,7,4,13,12,7,8~4,12,9,13,11,6,2,7,4,5,8,3,12,9,1,8,11,8,10,11,2,12,4,3,10,11,12,5,1,8,6,4,13,11,11,2,6,8,3,7,10,5,5,3,10,10,7,6,4,1,3,6,7,10,9,13,1,12,5,12,2,8,5,12,11,9,2,6,7,13,13,4,6,6,2,7,13,12,3,6,7,10,8,1,5,10,8,7,7,13,9,9,13,11,1,9,9,10,11~13,6,13,7,10,6,1,8,9,6,10,2,12,13,10,8,11,2,12,6,5,13,7,2,5,9,5,4,1,11,12,12,4,7,4,6,8,8,1,9,13,13,6,3,10,5,10,7,3,8,4,9,3,4,6,12,1,8,12,7,7,3,10,10,9,9,11,2,12,10,5,9,2,13,11,8,11,2,13,9,5,3,12,9,8,11,3,7,4,4,13,6,7,13,8,12,4,5,7,11~13,8,10,13,5,13,5,5,9,11,10,6,13,6,10,11,9,13,11,11,6,3,8,10,10,7,5,3,12,7,10,4,4,12,11,8,11,13,12,4,6,8,11,6,6,3,8,12,7,7,13,4,6,9,3,12,4,9,9,12,11,7,8,10,5,4,12,3,13,7,11,6,9,8,7,3,9,10,5,9,7,4,12,10,9,8&reel_set3=9,8,10,5,10,13,5,7,7,13,8,12,12,5,4,7,3,4,9,8,5,12,3,6,4,11,13,4,8,13,3,9,7,11,13,7,6,11,7,12,9,13,3,8,8,6,12,13,13,4,11,7,8,11,10,3,6,12,10,12,5,5,6,6,11,10,6,10,13,9,10,7,12,8,12,11,9,11,8,9,13,12,5,9,6,10,4~4,6,9,1,11,11,13,2,7,8,12,6,10,2,3,13,7,11,9,3,9,12,8,6,6,5,5,3,3,7,11,13,8,1,5,8,9,4,6,10,4,13,13,1,5,10,3,6,9,9,11,1,10,6,4,12,2,6,6,4,9,13,8,1,3,10,12,12,11,5,3,12,13,13,13,2,4,12,7,7,10,13,9,2,11,5,5,9,8,1,6,7,11,2,10,12,10,8,1,7,4,13,12,7,8~4,12,9,13,11,6,2,7,4,5,8,3,12,9,1,8,11,8,10,11,2,12,4,3,10,11,12,5,1,8,6,4,13,11,11,2,6,8,3,7,10,5,5,3,10,10,7,6,4,1,3,6,7,10,9,13,1,12,5,12,2,8,5,12,11,9,2,6,7,13,13,4,6,6,2,7,13,12,3,6,7,10,8,1,5,10,8,7,7,13,9,9,13,11,1,9,9,10,11~13,6,13,7,10,6,1,8,9,6,10,2,12,13,10,8,11,2,12,6,5,13,7,2,5,9,5,4,1,11,12,12,4,7,4,6,8,8,1,9,13,13,6,3,10,5,10,7,3,8,4,9,3,4,6,12,1,8,12,7,7,3,10,10,9,9,11,2,12,10,5,9,2,13,11,8,11,2,13,9,5,3,12,9,8,11,3,7,4,4,13,6,7,13,8,12,4,5,7,11~13,8,10,13,5,13,5,5,9,11,10,6,13,6,10,11,9,13,11,11,6,3,8,10,10,7,5,3,12,7,10,4,4,12,11,8,11,13,12,4,6,8,11,6,6,6,3,8,12,7,7,13,4,6,9,3,12,4,9,9,12,11,7,8,10,5,4,12,3,13,7,11,6,9,8,7,3,9,10,5,9,7,4,12,10,9,8&reel_set6=9,8,10,5,10,13,5,7,7,13,8,12,12,5,4,7,3,4,9,8,5,12,3,6,4,11,13,4,8,13,3,9,7,11,13,7,6,11,7,12,9,13,3,8,8,6,12,13,13,4,11,7,8,11,10,3,6,12,10,12,5,5,6,6,11,10,6,10,13,9,10,7,12,8,12,11,9,11,8,9,13,12,5,9,6,10,4~4,6,9,1,11,11,13,2,7,8,12,6,10,2,3,13,7,11,9,3,9,12,8,6,6,5,5,3,3,7,11,13,8,1,5,8,9,4,6,10,4,13,13,1,5,10,3,6,9,9,11,1,10,6,4,12,2,6,6,4,9,13,8,1,3,10,12,12,11,5,3,12,13,13,2,4,12,7,7,10,13,9,2,11,5,5,9,8,1,6,7,11,2,10,12,10,8,1,7,4,13,12,7,8~4,12,9,13,11,6,2,7,4,5,8,3,12,9,1,8,11,8,10,11,2,12,4,3,10,11,12,5,1,8,6,4,13,11,11,2,6,8,3,7,10,5,5,3,10,10,7,6,4,1,3,6,7,10,9,13,1,12,5,12,2,8,5,12,11,9,2,6,7,13,13,4,6,6,2,7,13,12,3,6,7,10,8,1,5,10,8,7,7,13,9,9,13,11,1,9,9,10,11~13,6,13,7,10,6,1,8,9,6,10,2,12,13,10,8,11,2,12,6,5,13,7,2,5,9,5,4,1,11,12,12,4,7,4,6,8,8,1,9,13,13,6,3,10,5,10,7,3,8,4,9,3,4,6,12,1,8,12,7,7,3,10,10,9,9,11,2,12,10,5,9,2,13,11,8,11,2,13,9,5,3,12,9,8,11,3,7,4,4,13,6,7,13,8,12,4,5,7,11~13,8,10,13,5,13,5,5,9,11,10,6,13,6,10,11,9,13,11,11,6,3,8,10,10,7,5,3,12,7,10,4,4,12,11,8,11,13,12,4,6,8,11,6,6,3,8,12,7,7,13,4,6,9,3,12,4,9,9,12,11,7,8,10,5,4,12,3,13,7,11,6,9,8,7,3,9,10,5,9,7,4,12,10,9,8&reel_set5=9,8,10,5,10,13,5,7,7,13,8,12,12,5,4,7,3,4,9,8,5,12,3,6,4,11,13,4,8,13,3,9,7,11,13,7,6,11,7,12,9,13,3,8,8,6,12,13,13,4,11,7,8,11,10,3,6,12,10,12,5,5,6,6,11,10,6,10,13,9,10,7,12,8,12,11,9,11,8,9,13,12,5,9,6,10,4~4,6,9,1,11,11,13,2,7,8,12,6,10,2,3,13,7,11,9,3,9,12,8,6,6,5,5,3,3,7,11,13,8,1,5,8,9,4,6,10,4,13,13,1,5,10,3,6,9,9,11,1,10,6,4,12,2,6,6,4,9,13,8,1,3,10,12,12,11,5,3,12,13,13,2,4,12,7,7,10,13,9,2,11,5,5,9,8,1,6,7,11,2,10,12,10,8,1,7,4,13,12,7,8~4,12,9,13,11,6,2,7,4,5,8,3,12,9,1,8,11,8,10,11,2,12,4,3,10,11,12,5,1,8,6,4,13,11,11,2,6,8,3,7,10,5,5,3,10,10,7,6,4,1,3,6,7,10,9,13,1,12,5,12,2,8,5,12,11,9,2,6,7,13,13,4,6,6,2,7,13,12,3,6,7,10,8,1,5,10,8,7,7,13,9,9,13,11,1,9,9,10,11~13,6,13,7,10,6,1,8,9,6,10,2,12,13,10,8,11,2,12,6,5,13,7,2,5,9,5,4,1,11,12,12,4,7,4,6,8,8,1,9,13,13,6,3,10,5,10,7,3,8,4,9,3,4,6,12,1,8,12,7,7,3,10,10,9,9,11,2,12,10,5,9,2,13,11,8,11,2,13,9,5,3,12,9,8,11,3,7,4,4,13,6,7,13,8,12,4,5,7,11~13,8,10,13,5,13,5,5,9,11,10,6,13,6,10,11,9,13,11,11,6,3,8,10,10,7,5,3,12,7,10,4,4,12,11,8,11,13,12,4,6,8,11,6,6,3,8,12,7,7,13,4,6,9,3,12,4,9,9,12,11,7,8,10,5,4,12,3,13,7,11,6,9,8,7,3,9,10,5,9,7,4,12,10,9,8&reel_set7=9,8,10,5,10,13,5,7,7,13,8,12,12,5,4,7,3,4,9,8,5,12,3,6,4,11,13,4,8,13,3,9,7,11,13,7,6,11,7,12,9,13,3,8,8,6,12,13,13,4,11,7,8,11,10,3,6,12,10,12,5,5,6,6,11,10,6,10,13,9,10,7,12,8,12,11,9,11,8,9,13,12,5,9,6,10,4~4,6,9,1,11,11,13,2,7,8,12,6,10,2,3,13,7,11,9,3,9,12,8,6,6,5,5,3,3,7,11,13,8,1,5,8,9,4,6,10,4,13,13,1,5,10,3,6,9,9,11,1,10,6,4,12,2,6,6,4,9,13,8,1,3,10,12,12,11,5,3,12,13,13,2,4,12,7,7,10,13,9,2,11,5,5,9,8,1,6,7,11,2,10,12,10,8,1,7,4,13,12,7,8~4,12,9,13,11,6,2,7,4,5,8,3,12,9,1,8,11,8,10,11,2,12,4,3,10,11,12,5,1,8,6,4,13,11,11,2,6,8,3,7,10,5,5,3,10,10,7,6,4,1,3,6,7,10,9,13,1,12,5,12,2,8,5,12,11,9,2,6,7,13,13,4,6,6,2,7,13,12,3,6,7,10,8,1,5,10,8,7,7,13,9,9,13,11,1,9,9,10,11~13,6,13,7,10,6,1,8,9,6,10,2,12,13,10,8,11,2,12,6,5,13,7,2,5,9,5,4,1,11,12,12,4,7,4,6,8,8,1,9,13,13,6,3,10,5,10,7,3,8,4,9,3,4,6,12,1,8,12,7,7,3,10,10,9,9,11,2,12,10,5,9,2,13,11,8,11,2,13,9,5,3,12,9,8,11,3,7,4,4,13,6,7,13,8,12,4,5,7,11~13,8,10,13,5,13,5,5,9,11,10,6,13,6,10,11,9,13,11,11,6,3,8,10,10,7,5,3,12,7,10,4,4,12,11,8,11,13,12,4,6,8,11,6,6,3,8,12,7,7,13,4,6,9,3,12,4,9,9,12,11,7,8,10,5,4,12,3,13,7,11,6,9,8,7,3,9,10,5,9,7,4,12,10,9,8&awt=rsf';
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
                $linesId = [];
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
                $allBet = $betline * $lines;
                $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $allBet, $lines);
                $winType = $_spinSettings[0];
                $_winAvaliableMoney = $_spinSettings[1];
                $freeStacks = []; // free stacks
                $isGeneratedFreeStack = false;
                $isForceWin = false;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $bonusMpl = $slotSettings->GetFreeMul($slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl'));
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
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
                    $slotSettings->SetBalance(-1 * ($allBet), $slotEvent['slotEvent']);
                    $_sum = ($allBet) / 100 * $slotSettings->GetPercent();
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $_sum, $slotEvent['slotEvent']);
                    $bonusMpl = 1;
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', [1,1,1]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeSpinType', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeOPT', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'JackpotWinTypes', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'JackpotWins', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'JackpotStatus', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Level', 0);
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '275' . substr($roundstr, 4, 7);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $leftFreeGames = 0;

                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'RegularSpinCount') + 1);
                }
                $Balance = $slotSettings->GetBalance();
                if( $slotEvent['slotEvent'] == 'bet' ) 
                {
                    $slotSettings->UpdateJackpots($allBet);
                }
                $currentReelSet = 0;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $currentReelSet = $slotSettings->GetGameData($slotSettings->slotId . 'FreeSpinType') + 1;
                }
                $defaultScatterCount = 0;
                $isJackpot = false;
                if($winType == 'bonus'){
                    if($slotEvent['slotEvent'] == 'freespin'){
                        $isJackpot = true;
                    }else{
                        if(mt_rand(0, 100) < 95){
                            $defaultScatterCount = $slotSettings->GenerateFreeSpinCount($slotEvent['slotEvent']);
                        }else{
                            $isJackpot = true;
                        }
                    }
                }
                for( $i = 0; $i <= 2000; $i++ ) 
                {
                    $totalWin = 0;
                    $this->winLines = [];
                    $lineWins = [];
                    $lineWinNum = [];
                    $wild = '2';
                    $scatter = '1';
                    $_obf_winCount = 0;
                    $strWinLine = '';
                    // freeStack
                    if($isGeneratedFreeStack == true){
                        $freeStack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 2];
                        $reels = $freeStack['Reel'];
                        $bonusMpl = $freeStack['BonusMpl'];
                    }else{
                        $reels = $slotSettings->GetReelStrips($winType, $slotEvent['slotEvent'], $currentReelSet, $defaultScatterCount, $isJackpot);
                        // $reels = $slotSettings->CheckDuplicationSymbol($reels);
                    }
                    for($r = 0; $r < 3; $r++){
                        if($reels['reel1'][$r] != $scatter){
                            $this->findZokbos($reels, $reels['reel1'][$r], 1, '~'.($r * 5));
                        }                        
                    }
                    for($r = 0; $r < count($this->winLines); $r++){
                        $winLine = $this->winLines[$r];
                        $winLineMoney = $slotSettings->Paytable[$winLine['FirstSymbol']][$winLine['RepeatCount']] * $betline * $bonusMpl;
                        if($winLineMoney > 0){       
                            $strWinLine = $strWinLine . '&l'. $r.'='.$r.'~'.$winLineMoney . $winLine['StrLineWin'];
                            $totalWin += $winLineMoney;
                        }
                    } 
                    if($winType == 'win' && $totalWin == 0 && $slotEvent['slotEvent'] != 'freespin' && $isJackpot == false){
                        $arr_gri = $slotSettings->GetGri('win');
                    }else{
                        $arr_gri = $slotSettings->GetGri('none');
                    }
                    
                    $jackpotWin = 0;
                    if(count($arr_gri) >= 3){
                        $jackpotMuls = [15,150,20000];
                        $jackpotWin = $betline * $lines * $jackpotMuls[count($arr_gri) - 3];
                        $totalWin = $totalWin + $jackpotWin;
                    }
                    
                    $scattersCount = 0;
                    $wildposes = [];
                    for( $r = 1; $r <= 5; $r++ ) 
                    {
                        for( $k = 0; $k <= 2; $k++ ) 
                        {
                            if( $reels['reel' . $r][$k] == $scatter ) 
                            {
                                $scattersCount++;
                            }
                            if( $reels['reel' . $r][$k] == $wild ) 
                            {
                                $wildposes[] = $k * 5 + $r - 1;
                            }
                        }
                    }
                    
                    if( $i > 1000 ) 
                    {
                        $winType = 'none';
                    }
                    if( $i > 1500 ) 
                    {
                       break;
                    }
                    if($scattersCount >= 3 && ($winType != 'bonus' || $defaultScatterCount != $scattersCount || $slotEvent['slotEvent'] == 'freespin')){

                    }
                    else if($winType == 'bonus' && ($isJackpot == false && $scattersCount < 3)){
                        
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
                        $_bonusBankMoney = $slotSettings->GetBank('bonus');
                        if( $_bonusBankMoney < $_winAvaliableMoney ) 
                        {
                            $_winAvaliableMoney = $_bonusBankMoney;
                        }
                        else
                        {
                            if($isJackpot == true){
                                if($betline * $lines * 15 < $_bonusBankMoney){
                                    break;
                                }
                            }else{
                                break;
                            }
                        }
                    }
                    else if( $totalWin > 0 && $totalWin <= $_winAvaliableMoney && $winType == 'win' ) 
                    {
                        $_bankMoney = $slotSettings->GetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''));
                        if( $_bankMoney < $_winAvaliableMoney ) 
                        {
                            $_winAvaliableMoney = $_bankMoney;
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
                // if($freeSpinNums > 0 && $slotEvent['slotEvent'] == 'freespin') 
                // {
                //     $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $freeSpinNums);
                    
                // }
                
                for($k = 0; $k < 3; $k++){
                    for($j = 1; $j <= 5; $j++){
                        $lastReel[($j - 1) + $k * 5] = $reels['reel'.$j][$k];
                    }
                }
                $strLastReel = implode(',', $lastReel);
                $strReelSa = $reels['reel1'][3].','.$reels['reel2'][3].','.$reels['reel3'][3].','.$reels['reel4'][3].','.$reels['reel5'][3];
                $strReelSb = $reels['reel1'][-1].','.$reels['reel2'][-1].','.$reels['reel3'][-1].','.$reels['reel4'][-1].','.$reels['reel5'][-1];                
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $isState = true;
                $otherResponse = '';
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    
                    $spinType = 's';
                    $isEnd = false;
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                    {
                        $isEnd = true;
                        $otherResponse = '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsc_win_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsend_total=1&fsmul_total=1&fsc_mul_total=1&fsc_res_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 'c';
                    }
                    else
                    {
                        $isState = false;
                        $otherResponse = '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                    $otherResponse = $otherResponse . '&wrlm_cs=2~' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeSpinType') . '&fsopt_i=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeSpinType') . '&wrlm_res=2~' . $bonusMpl;
                    if($totalWin > 0 && count($wildposes) > 0){
                        $otherResponse = $otherResponse . '~' . implode(',', $wildposes);
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                    $otherResponse = '';
                    if($scattersCount >=3 ){
                        $spinType = 'fso';
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeOPT', $scattersCount);
                        $otherResponse = '&fs_opt_mask=fs,m,ts,rm&fs_opt=25,1,2,2;3;5~20,1,2,3;5;8~15,1,2,5;8;10~13,1,2,8;10;15~10,1,2,10;15;30~6,1,2,15;30;40~-1,-1,2,-1';
                        $isState = false;
                    }
                }
                if($jackpotWin > 0){
                    $otherResponse = $otherResponse . '&coef='. $allBet .'&rw='. $jackpotWin .'&wp='. floor($jackpotWin / $allBet) .'&bw=1&end=1';
                }
                if(count($arr_gri) > 0){
                    $otherResponse = $otherResponse . '&gri=' . implode(',', $arr_gri);
                }
                $aw = 1;
                if($isJackpot == true){
                    $spinType = 'b';
                    $isState = false;
                    if($slotEvent['slotEvent'] == 'freespin'){
                        $otherResponse = $otherResponse . '&bgid=1&wins=1,1&coef='. $allBet .'&level=1&status=1,0&rw=' . $jackpotWin . '&bgt=21&lifes=0&wins_mask=stp,fss&end=1';
                        $slotSettings->SetGameData($slotSettings->slotId . 'Level', 1);
                    }else{
                        $otherResponse = $otherResponse . '&bgid=1&wins=0,0&coef='. $allBet .'&level=0&status=0,0&rw=' . $jackpotWin . '&bgt=21&lifes=1&bw=1&wins_mask=h,h&end=0';
                        $slotSettings->SetGameData($slotSettings->slotId . 'Level', 0);
                        $aw = 0;
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 21);
                }

                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin').'&balance='.$Balance.'&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&n_reel_set='. $currentReelSet .'&balance_bonus=0.00&na='.$spinType . $otherResponse . $strWinLine.'&aw='. $aw .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&c='.$betline.'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&s='.$strLastReel.'&w='.($totalWin - $jackpotWin).'&awt=rsf';
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)) 
                {
                    // $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
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

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":"' . 
                    implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl')) . '","lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"FreeSpinType":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeSpinType') . ',"FreeOPT":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeOPT')  . ',"Balance":' . $Balance . ',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"ReplayGameLogs":'.json_encode($replayLog) . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"JackpotWinTypes":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'JackpotWinTypes')) . '","JackpotWins":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'JackpotWins')) . '","JackpotStatus":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'JackpotStatus')) . '","Level":' . $slotSettings->GetGameData($slotSettings->slotId . 'Level') .',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . 
                    ',"LastReel":'.json_encode($lastReel).'}}';
                
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if( $scattersCount >= 3 ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                }
            }
            else if( $slotEvent['slotEvent'] == 'doFSOption' ){
                $scattersCount = $slotSettings->GetGameData($slotSettings->slotId . 'FreeOPT');
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lines = 20;
                $Balance = $slotSettings->GetBalance();
                $ind = $slotEvent['ind'];
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeSpinType', $ind);
                
                $freeData = $slotSettings->GetFreeData($ind);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $freeData[1]);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freeData[0]);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                // if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0 ) 
                // {
                    
                //     // FreeStack
                //     if($slotSettings->IsAvailableFreeStack() || $slotSettings->happyhouruser){

                //         $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $slotSettings->GetFreeStack($betline, $ind * 3 + $scattersCount - 3));
                //     }
                // }
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $response = 'fsmul=1&fs_opt_mask=fs,m,ts,rm&balance='.$Balance.'&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na=s&fswin=0.00&aw=1&stime=' . floor(microtime(true) * 1000) . '&fs=1&fs_opt=25,1,2,2;3;5~20,1,2,3;5;8~15,1,2,5;8;10~13,1,2,8;10;15~10,1,2,10;15;30~6,1,2,15;30;40~13,1,2,2;3;5&fsres=0.00&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&n_reel_set='.($ind + 1).'&fsopt_i=' . $ind . '&awt=rsf';
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeOPT', 0);
                 //------------ ReplayLog ---------------
                 $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                 if (!$replayLog) $replayLog = [];
                 $current_replayLog["cr"] = $paramData;
                 $current_replayLog["sr"] = $response;
                 array_push($replayLog, $current_replayLog);
                 $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                 //------------ *** ---------------
                $totalWin = 0;
                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":"' . 
                    implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl')) . '","lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"FreeSpinType":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeSpinType') . ',"FreeOPT":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeOPT') . ',"Balance":' . $Balance . ',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"ReplayGameLogs":'.json_encode($replayLog) . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"JackpotWinTypes":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'JackpotWinTypes')) . '","JackpotWins":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'JackpotWins')) . '","JackpotStatus":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'JackpotStatus')) . '","Level":' . $slotSettings->GetGameData($slotSettings->slotId . 'Level').',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'LastReel')).'}}';
                $slotSettings->SaveLogReport($_GameLog, $betline * $lines, $lines, 0, $slotEvent['slotEvent'], false);
            }else if( $slotEvent['slotEvent'] == 'doBonus' ){
                $scattersCount = $slotSettings->GetGameData($slotSettings->slotId . 'FreeOPT');
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lines = 20;
                $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                if($slotSettings->GetGameData($slotSettings->slotId . 'Bgt') == 21){
                    if($slotSettings->GetGameData($slotSettings->slotId . 'Level') == 0){
                        $isJackpot = true;
                        if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0 && mt_rand(0, 100) < 30){
                            $isJackpot = false;
                        }
                        if($isJackpot == false){
                            $otherResponse = '&fs_opt_mask=fs,m,ts,rm&na=fso&fs_opt=25,1,2,2;3;5~20,1,2,3;5;8~15,1,2,5;8;10~13,1,2,8;10;15~10,1,2,10;15;30~6,1,2,15;30;40~-1,-1,2,-1&wins_mask=fss,stp';
                            $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeOPT', 1);
                        }else{
                            $otherResponse = '&na=b&wins_mask=stp,fss';
                        }
                        $slotSettings->SetGameData($slotSettings->slotId . 'Level', 1);
                        $response = 'tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&bgid=1&balance='. $Balance .'&wins=1,1&coef=' . ($betline * $lines) . '&level=1&index='.$slotEvent['index'].'&balance_cash='. $Balance .'&balance_bonus=0.00&status=1,0&rw=0.00&stime=' . floor(microtime(true) * 1000) . '&bgt=21&lifes=0&wp=0&end=1&sver=5&counter='. ((int)$slotEvent['counter'] + 1);
                    }else{
                        $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 18);
                        $slotSettings->SetGameData($slotSettings->slotId . 'Level', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'JackpotWinTypes', $slotSettings->GetJackpotWins());
                        $slotSettings->SetGameData($slotSettings->slotId . 'JackpotWins', [0,0,0,0,0,0,0,0,0]);
                        $slotSettings->SetGameData($slotSettings->slotId . 'JackpotStatus', [0,0,0,0,0,0,0,0,0]);
                        $response = 'bgid=2&balance='. $Balance .'&wins=0,0,0,0,0,0,0,0,0&coef=' . ($betline * $lines) . '&level=0&index='.$slotEvent['index'].'&balance_cash='. $Balance .'&balance_bonus=0.00&na=b&status=0,0,0,0,0,0,0,0,0&bg_i=2000,150,15&rw=0.00&stime=' . floor(microtime(true) * 1000) . '&bgt=18&lifes=1&wins_mask=h,h,h,h,h,h,h,h,h&wp=0&end=0&sver=5&bg_i_mask=pw,pw,pw&counter='. ((int)$slotEvent['counter'] + 1);
                    }
                }else{

                }
                 //------------ ReplayLog ---------------
                 $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                 if (!$replayLog) $replayLog = [];
                 $current_replayLog["cr"] = $paramData;
                 $current_replayLog["sr"] = $response;
                 array_push($replayLog, $current_replayLog);
                 $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                 //------------ *** ---------------
                $totalWin = 0;
                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":"' . 
                    implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl')) . '","lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"FreeSpinType":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeSpinType') . ',"FreeOPT":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeOPT') . ',"Balance":' . $Balance . ',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"ReplayGameLogs":'.json_encode($replayLog) . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"JackpotWinTypes":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'JackpotWinTypes')) . '","JackpotWins":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'JackpotWins')) . '","JackpotStatus":"' . implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'JackpotStatus')) . '","Level":' . $slotSettings->GetGameData($slotSettings->slotId . 'Level').',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'LastReel')).'}}';
                $slotSettings->SaveLogReport($_GameLog, $betline * $lines, $lines, 0, $slotEvent['slotEvent'], false);
            }
            if($slotEvent['action'] == 'doSpin' || $slotEvent['action'] == 'doCollect' || $slotEvent['action'] == 'doBonus' || $slotEvent['action'] == 'doCollectBonus' || $slotEvent['action'] == 'doFSOption'){
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
        public function findZokbos($reels, $firstSymbol, $repeatCount, $strLineWin){
            $wild = '2';
            $bPathEnded = true;
            if($repeatCount < 5){
                for($r = 0; $r < 3; $r++){
                    if($firstSymbol == $reels['reel'.($repeatCount + 1)][$r] || $reels['reel'.($repeatCount + 1)][$r] == $wild){
                        $this->findZokbos($reels, $firstSymbol, $repeatCount + 1, $strLineWin . '~' . ($repeatCount + $r * 5));
                        $bPathEnded = false;
                    }
                }
            }
            if($bPathEnded == true){
                if($repeatCount >= 3 || ($firstSymbol == 3 && $repeatCount == 2)){
                    $winLine = [];
                    $winLine['FirstSymbol'] = $firstSymbol;
                    $winLine['RepeatCount'] = $repeatCount;
                    $winLine['StrLineWin'] = $strLineWin;
                    array_push($this->winLines, $winLine);
                }
            }
        }
    }

}

<?php 
namespace VanguardLTE\Games\GreatRhinoDeluxePM
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
                $response = 'balance=' . $Balance . '&balance_cash=' . $Balance . '&balance_bonus=0.00&na=s&stime=' . floor(microtime(true) * 1000);
                exit( $response );
            }
            
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
            $linesId[20] = [
                3, 
                1, 
                1, 
                1, 
                3
            ];
            $linesId[21] = [
                2, 
                3, 
                1, 
                3, 
                2
            ];
            $linesId[22] = [
                2, 
                1, 
                3, 
                1, 
                2
            ];
            $linesId[23] = [
                1, 
                3, 
                1, 
                3, 
                1
            ];
            $linesId[24] = [
                3, 
                1, 
                3, 
                1, 
                3
            ];
            if( $slotEvent['slotEvent'] == 'doInit' ) 
            { 
                $lastEvent = $slotSettings->GetHistory();
                $_obf_StrResponse = '';
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [6,9,8,4,7,7,3,4,11,11,10,11,6,5,10]);
                $slotSettings->SetGameData($slotSettings->slotId . 'RespinSymbolPoses', [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]);
                $slotSettings->SetGameData($slotSettings->slotId . 'RespinMuls', [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]);
                $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', $lastEvent->serverResponse->totalRespinGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', $lastEvent->serverResponse->currentRespinGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinSymbolPoses', $lastEvent->serverResponse->RespinSymbolPoses);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinMuls', $lastEvent->serverResponse->RespinMuls);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', $lastEvent->serverResponse->Bgt);
                    $bet = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $bet = $slotSettings->Bet[0];
                }
                $currentReelSet = 0;
                $spinType = 's';
                $bgt = $slotSettings->GetGameData($slotSettings->slotId . 'Bgt');
                $respinSymbolPoses = $slotSettings->GetGameData($slotSettings->slotId . 'RespinSymbolPoses');
                $respinMuls = $slotSettings->GetGameData($slotSettings->slotId . 'RespinMuls');
                $acc_count = 0;
                $arr_accv = [];
                for($k = 0; $k < count($respinSymbolPoses); $k++){
                    if($respinSymbolPoses[$k] > 0){
                        array_push($arr_accv, $k);
                        $acc_count++;
                    }
                }
                if($bgt == 26){
                    $spinType = 'b';     
                    $currentReelSet = 1;               
                    $_obf_StrResponse = '&fs_total=10&fswin_total='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin').'&fsmul_total=1&fsres_total='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin').'&rsb_m='.$slotSettings->GetGameData($slotSettings->slotId . 'RespinGames').'&rsb_c='.$slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame').'&accm=cp~tp~s~sp&acci=0&bgid=0&fsend_total=1&bgt=26&bw=1&end=0&bpw=0.00';
                    if($acc_count > 0){
                        $_obf_StrResponse = $_obf_StrResponse . '&accv=' .($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 1).'~' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '~3~' . implode(',', $arr_accv);
                    }
                        
                }else if($bgt == 47){
                    $spinType = 'b';      
                    $arr_bgmps = [];
                    $arr_bgmvs = [];
                    for($k = 0; $k < 15; $k++){
                        if($respinMuls[$k] > 0){
                            array_push($arr_bgmps, $k);
                            array_push($arr_bgmvs, $respinMuls[$k]);
                        }
                    }              
                    $_obf_StrResponse = '&rsb_m='.$slotSettings->GetGameData($slotSettings->slotId . 'RespinGames').'&rsb_c='.$slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame').'&bgid=1&bgt=47&bw=1&end=0&bpw=0.00';
                    if(count($arr_bgmps) > 0){
                        $_obf_StrResponse = $_obf_StrResponse .'&bg_mp='. implode(',', $arr_bgmps) .'&bg_mv='. implode(',', $arr_bgmvs);
                    }
                }else if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    $_obf_StrResponse = '&fs=' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&w=0.00&fsmul=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . '&acci=0';
                    if($acc_count > 0){
                        $_obf_StrResponse = $_obf_StrResponse . '&accv=' .($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 1).'~' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '~3~' . implode(',', $arr_accv) . '&accm=cp~tp~s~sp';
                    }else{
                        $_obf_StrResponse = $_obf_StrResponse . '&accv=' .($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 1).'~' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&accm=cp~tp';
                    }
                    $currentReelSet = 1;
                }else if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') < $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames')){
                    $currentReelSet = 0;
                    $spinType = 'b';
                    
                    $_obf_StrResponse = '&bgid=0&rsb_m='.$slotSettings->GetGameData($slotSettings->slotId . 'RespinGames').'&rsb_c='.$slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame').
                        '&bgt=26&end=0&bpw=0.00';
                }
                $lastReel = $slotSettings->GetGameData($slotSettings->slotId . 'LastReel');
                if($spinType != 'b'){
                    for($k = 0; $k < count($lastReel); $k++){
                        if($lastReel[$k] == 13){
                            $lastReel[$k] = rand(4, 10);
                        }
                    }
                }
                $lastReelStr = implode(',', $lastReel);
                $Balance = $slotSettings->GetBalance();
                
                // $response = 'def_s=6,9,8,4,7,7,3,4,11,11,10,11,6,5,10&balance='. $Balance .'&cfgs=1&ver=2&index=1&balance_cash='.$Balance.
                //     '&reel_set_size=2&def_sb=8,4,2,8,7&def_sa=5,7,3,3,3&bonusInit=[{bgid:0,bgt:26,bg_i:"375,500",bg_i_mask:"psw,psw"}]&balance_bonus=0.00&na='. $spinType.'&scatters=1~0,0,2,0,0~0,0,10,0,0~1,1,1,1,1&gmb=0,0,0&bg_i=375,500&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"39370078",max_rnd_win:"800"}}&stime=' . floor(microtime(true) * 1000) .
                //     '&sa=5,7,3,3,3&sb=8,4,2,8,7&sc='. implode(',', $slotSettings->Bet) .'&defc=0.01&sh=3&wilds=2~500,150,50,4,0~1,1,1,1,1;14~500,150,50,4,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.
                //     '&sver=5&reel_set='.$currentReelSet.$_obf_StrResponse.'&bg_i_mask=psw,psw&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;400,150,50,2,0;200,50,15,0,0;150,40,10,0,0;100,30,10,0,0;50,25,10,0,0;25,10,5,0,0;20,10,5,0,0;20,10,5,0,0;20,10,5,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0&l='.$slotSettings->GetGameData($slotSettings->slotId . 'Lines') .'&rtp=96.53&reel_set0=5,7,3,3,3,11,11,4,9,2,2,10,7,8,9,4,10,5,9,11,10,7,5,6,8,3,10,10,9~11,8,10,3,3,3,11,9,11,4,7,4,2,2,7,10,10,11,1,8,6,8,11,3,5,6,3~4,10,3,3,3,11,9,10,6,7,11,7,2,2,8,4,8,3,6,9,10,9,1,5,2,2,3,5,9,2,2,8,8~8,7,7,9,9,9,2,2,11,10,1,9,6,5,4,5,8,8,10,4,3,3,3,11,11,6,2,2,11~8,4,2,2,8,7,6,3,3,3,11,10,8,11,4,10,11,9,3,10,5,10,9,6,9,9,7,5,7&s='.$lastReelStr.'&reel_set1=9,10,10,11,2,2,9,10,14,14,9,11,8,5,9,5,11,9,7,6,7,14,14,7,10,4~8,8,14,14,11,11,5,4,6,14,14,11,5,7,10,10,6,11,10,4,9,2,2,9~8,2,2,14,14,10,7,9,11,8,10,9,14,14,9,7,4,6,6,5,7,10~2,2,14,14,11,6,7,4,5,10,14,14,6,11,9,8,7,8,9,8,4,6,10~11,14,14,7,9,11,7,5,5,4,9,9,10,6,2,2,14,14,7,11,8,6,8,10,9';
                $response = 'def_s=8,6,9,6,10,10,7,6,9,11,5,11,5,5,4&balance='. $Balance .'&cfgs=1&ver=2&index=1&balance_cash='. $Balance .'&reel_set_size=2&def_sb=11,2,10,9,9&def_sa=8,1,1,7,8&reel_set='.$currentReelSet.$_obf_StrResponse.'&bonusInit=[{bgid:0,bgt:26,bg_i:"375,500",bg_i_mask:"psw,psw"},{bgid:1,bgt:47,bg_i:"375,500",bg_i_mask:"psw,psw"}]&balance_bonus=0.00&na='. $spinType.'&scatters=1~0,0,2,0,0~0,0,10,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"140845070",max_rnd_win:"1000"}}&stime=' . floor(microtime(true) * 1000) .'&sa=8,1,1,7,8&sb=11,2,10,9,9&sc='. implode(',', $slotSettings->Bet) .'&defc=0.10&sh=3&wilds=2~500,150,50,4,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;400,150,50,2,0;200,50,15,0,0;150,40,10,0,0;100,30,10,0,0;50,25,10,0,0;25,10,5,0,0;20,10,5,0,0;20,10,5,0,0;20,10,5,0,0;0,0,0,0,0&l=20&rtp=96.54&reel_set0=10,9,3,3,11,9,7,5,8,10,2,2,11,9,6,8,10,5,7,11,9,4,5,10,9,6,11,10,3,3,11,4,7,9,11,5,10,9,7,6,8,9,2,2,10,11,5,10,9,4,5,10,9,7,11,10,3,3,3,11~10,11,4,7,8,6,7,11,1,10,9,2,2,10,11,4,5,8,7,6,10,11,1,8,7,4,11,3,3,3,10,11,4,6,8,7,5,11,1,9,11,6,4,11,8,3,3,3,11,6,5,8,11,1~4,7,8,9,6,5,10,8,1,11,6,7,10,2,2,2,8,3,3,3,9,10,1,8,6,5,9,7,4,8,10,6,7,10,8,6,9,10,5,4,9,1,8,10,7,6,8,11,5,4,8,7,6,9,3,3,3,8~2,2,2,8,7,6,9,5,7,8,11,1,9,8,6,7,11,5,4,8,3,3,11,8,1,9,11,7,6,8,10,5,9,11,5,7,9,8,4,5,11,9,8,11,5,6,10,4,7,11,8,3,3,3,9,11~7,8,9,4,6,10,9,5,8,11,7,6,9,8,7,10,11,4,10,9,3,3,3,8,9,7,4,10,8,7,11,9,6,10,7,11,9,2,2,8,11,7,5,9,10,7,11,9,6,7,10,9,4,8,9&s='.$lastReelStr.'&accInit=[{id:0,mask:"cp;tp;s;sp"}]&reel_set1=10,9,3,3,11,9,7,5,8,10,8,4,11,9,6,8,10,5,2,2,9,4,5,10,9,6,11,10,6,5,11,4,7,9,11,5,10,9,7,6,8,9,4,7,10,11,5,10,9,4,5,10,9,7,11,10,3,3,3,11~10,11,4,7,8,6,7,11,9,8,9,4,10,7,11,4,5,8,7,6,10,11,6,8,7,4,11,3,3,2,2,2,4,6,8,7,5,11,10,9,11,6,4,11,8,3,3,3,11,6,5,8,11,8~4,7,8,9,6,5,10,8,10,11,6,7,10,2,2,9,8,3,3,3,9,10,7,8,6,5,9,7,4,8,10,6,7,10,2,2,9,10,5,4,9,7,8,10,7,6,8,2,2,5,8,7,6,9,4,3,5,8~2,2,11,8,7,6,9,5,7,8,11,8,9,8,6,7,11,5,4,6,2,2,11,8,5,9,11,7,6,8,10,5,9,11,5,7,9,2,2,2,11,9,8,11,5,6,10,4,7,2,8,3,3,3,9,11~7,8,9,4,6,10,9,5,8,11,7,6,9,8,2,2,11,4,10,9,3,3,5,8,9,7,4,10,8,7,11,9,6,10,7,11,9,2,2,2,11,7,5,9,10,7,11,9,6,7,10,9,4,8,9';
            }
            else if( $slotEvent['slotEvent'] == 'doCollect' || $slotEvent['slotEvent'] == 'doCollectBonus') 
            {
                $Balance = $slotSettings->GetBalance();
                $response = 'balance=' . $Balance . '&index=' . $slotEvent['index'] . '&balance_cash=' . $Balance . '&balance_bonus=0.00&na=s&stime=' . floor(microtime(true) * 1000) . '&na=s&sver=5&counter=' . ((int)$slotEvent['counter'] + 1);
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
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') < $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') && $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') > 0 ) 
                {
                    $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"invalid bonus state"}';
                    exit( $response );
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
                    // if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') < $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotEvent['slotEvent'] == 'freespin' ) 
                    // {
                    //     $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"invalid bonus state"}';
                    //     exit( $response );
                    // }
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
                    if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') == 1){                        
                        $slotSettings->SetGameData($slotSettings->slotId . 'RespinSymbolPoses', [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]);
                    }
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinSymbolPoses', [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]);
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'RespinMuls', [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]);
                $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                $Balance = $slotSettings->GetBalance();
                if( $slotEvent['slotEvent'] != 'bet' ) 
                {
                    $slotSettings->UpdateJackpots($betline * $lines);
                }
                $bonusType = 0; // 0 : normal, 1 : freespin, 2 : respin
                $defaultRespinCount = 0;
                if($winType == 'bonus'){
                    if(rand(0, 100) < 50){
                        $bonusType = 1;
                    }else{
                        $bonusType = 2;
                        if(rand(0, 100) < 98){
                            $defaultRespinCount = 2;
                        }else{
                            $defaultRespinCount = 3;
                        }
                    }
                }
                for( $i = 0; $i <= 2000; $i++ ) 
                {
                    $totalWin = 0;
                    $lineWins = [];
                    $lineWinNum = [];
                    $wild = '2';
                    $scatter = '1';
                    $_obf_winCount = 0;
                    $strWinLine = '';
                    $reels = $slotSettings->GetReelStrips($winType, $slotEvent['slotEvent'], $bonusType); 
                    if($bonusType == 2){
                        $respinReelPoses = $slotSettings->GetRandomNumber(1, 5, $defaultRespinCount);
                        for($r = 0; $r < count($respinReelPoses); $r++){
                            for($k = 0; $k < 3; $k++){
                                $reels['reel' . $respinReelPoses[$r]][$k] = 3;
                            }
                        }
                    }
                    $respinSymbolPoses = $slotSettings->GetGameData($slotSettings->slotId . 'RespinSymbolPoses');
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
                                    if($lineWins[$k] > 0){
                                        $totalWin += $lineWins[$k];
                                        $_obf_winCount++;
                                        $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                        for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                            $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                        }
                                    }
                                }
                            }else{
                                if($slotSettings->Paytable[$firstEle][$lineWinNum[$k]] > 0){
                                    $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                    if($lineWins[$k] > 0){
                                        $totalWin += $lineWins[$k];
                                        $_obf_winCount++;
                                        $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                        for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                            $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                        }   
                                    }

                                }else{
                                    $lineWinNum[$k] = 0;
                                }
                                break;
                            }
                        }
                    }
                    
                    
                    
                    $_obf_scatterposes = [];
                    $scattersCount = 0;
                    $scattersWin = 0;
                    $respinCount = 0;
                    $_obf_respinposes = [];
                    for( $r = 1; $r <= 5; $r++ ) 
                    {
                        for( $k = 0; $k <= 2; $k++ ) 
                        {
                            if( $reels['reel' . $r][$k] == $scatter ) 
                            {
                                $scattersCount++;
                                array_push($_obf_scatterposes, $k * 5 + $r - 1);
                            }
                            if( $reels['reel' . $r][$k] == 3 ) {
                                $respinSymbolPoses[$k * 5 + $r - 1] = 1;
                            }
                        }
                        if($reels['reel' . $r][0] == '3' && $reels['reel' . $r][1] == '3' && $reels['reel' . $r][2] == '3'){
                            $respinCount++;
                            array_push($_obf_respinposes, $r);
                        }
                    }
                    $acc_count = 0;
                    $arr_accv = [];
                    for($k = 0; $k < count($respinSymbolPoses); $k++){
                        if($respinSymbolPoses[$k] > 0){
                            array_push($arr_accv, $k);
                            $acc_count++;
                        }
                    }
                    if($scattersCount >= 3){
                        $scattersWin = 2 * $lines * $betline;
                    }
                    $totalWin = $totalWin + $scattersWin;
                    // if($respinCount >= 2 && $slotEvent['slotEvent'] != 'freespin'){
                    //     break;
                    // }
                    if( $i > 1000 ) 
                    {
                        $winType = 'none';
                    }
                    // if( $slotSettings->increaseRTP && $winType == 'win' && $totalWin < ($lines * $betline * rand(2, 5)) ) 
                    // {
                    // }
                    // else if( !$slotSettings->increaseRTP && $winType == 'win' && $lines * $betline < $totalWin ) 
                    // {
                    // }
                    // else
                    // {
                        if( $i > 1500 ) 
                        {
                            if($acc_count < 13 && $slotEvent['slotEvent'] == 'freespin'){
                                break;
                            }
                        }
                        if( $scattersCount >= 3 && $winType != 'bonus' ) 
                        {
                        }
                        else if( $respinCount >= 2 && ($winType != 'bonus' || $slotEvent['slotEvent'] == 'freespin' || $respinCount != $defaultRespinCount)) 
                        {
                        }
                        else if($acc_count > 10 && $slotEvent['slotEvent'] == 'freespin'){

                        }
                        else if( $totalWin <= $_winAvaliableMoney && $winType == 'bonus' ) 
                        {
                            $_obf_CurrentAvaliableMoney = $slotSettings->GetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''));
                            if( $_obf_CurrentAvaliableMoney < $_winAvaliableMoney ) 
                            {
                                $_winAvaliableMoney = $_obf_CurrentAvaliableMoney;
                            }
                            else
                            {
                                break;
                            }
                        }
                        else if( $totalWin > 0 && $totalWin <= $_winAvaliableMoney && $winType == 'win' ) 
                        {
                            $_obf_CurrentAvaliableMoney = $slotSettings->GetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''));
                            if( $_obf_CurrentAvaliableMoney < $_winAvaliableMoney ) 
                            {
                                $_winAvaliableMoney = $_obf_CurrentAvaliableMoney;
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
                if( $respinCount >= 2 && $slotEvent['slotEvent'] != 'respin') 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', $slotSettings->slotRespinCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 0);
                }
                if( $totalWin > 0) 
                {
                    $spinType = 'c';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }
                $_obf_totalWin = $totalWin;
                if( $scattersCount >= 3 ) 
                {
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0 ) 
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->slotFreeCount);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                    }
                    else
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $slotSettings->slotFreeCount);
                    }
                }
                
                for($k = 0; $k < 3; $k++){
                    for($j = 1; $j <= 5; $j++){
                        $lastReel[($j - 1) + $k * 5] = $reels['reel'.$j][$k];
                    }
                }
                $strLastReel = implode(',', $lastReel);
                $strReelSa = $reels['reel1'][3].','.$reels['reel2'][3].','.$reels['reel3'][3].','.$reels['reel4'][3].','.$reels['reel5'][3];
                $strReelSb = $reels['reel1'][-1].','.$reels['reel2'][-1].','.$reels['reel3'][-1].','.$reels['reel4'][-1].','.$reels['reel5'][-1];
                
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $strFreeResponse = '';
                $spinType = 's';
                $isEnd = false;
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinSymbolPoses', $respinSymbolPoses);
                    
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                    {
                        $spinType = 'c';
                        $isEnd = true;
                        $n_reel_set = '&reel_set=0';
                        $strFreeResponse = '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        if($acc_count > 0){
                            $spinType = 'b';
                            $strFreeResponse = $strFreeResponse . '&bgid=0&rsb_m=1&rsb_c=0&fsend_total=1&bgt=26&bw=1&end=0&bpw=0.00';
                            $n_reel_set = '&reel_set=1';
                            $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 26);
                            $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', 1);
                            $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 0);
                        }
                    }
                    else
                    {
                        $n_reel_set = '&reel_set=1';
                        $strFreeResponse = '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                    $strFreeResponse = $strFreeResponse . '&acci=0&accv='.($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 1).'~' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames');
                    if($acc_count > 0){
                        $strFreeResponse = $strFreeResponse . '~3~' . implode(',', $arr_accv) . '&accm=cp~tp~s~sp';
                    }else{
                        $strFreeResponse = $strFreeResponse . '&accm=cp~tp';
                    }
                }else
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                    $n_reel_set = '&reel_set=0';
                    if($scattersCount >=3 ){
                        $spinType = 's';
                        $strFreeResponse = '&fsmul=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=0.00&fs=1&fsres=0.00&psym=1~'.$scattersWin.'~'.implode(',', $_obf_scatterposes);
                    }
                }
                $strRespinResponse = '';
                if($respinCount >= 2){
                    $spinType = 'b';
                    $strRespinResponse = '&bgid=1&rsb_m='.$slotSettings->GetGameData($slotSettings->slotId . 'RespinGames').'&rsb_c='.$slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame').'&bgt=47&end=0&bw=1&bpw=0';
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 47);
                }
                if($isEnd == true){
                    $strFreeResponse = $strFreeResponse.'&w='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                }else{
                    $strFreeResponse = $strFreeResponse.'&w='.$totalWin;
                }

                $response = 'tw='. $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') .'&balance='.$Balance.'&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType.$strFreeResponse.$strRespinResponse.$strWinLine.'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&c='.$betline.'&sver=5'.$n_reel_set.'&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&s='.$strLastReel;

                // if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)) 
                // {
                //     $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                //     $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                //     $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', 0);
                //     $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 0);
                // }
                if($slotEvent['slotEvent'] != 'freespin' && $respinCount < 2){
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 0);
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'RespinSymbolPoses', $respinSymbolPoses);
                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"totalRespinGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') . ',"currentRespinGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') . ',"Balance":' . $Balance  . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt')  . ',"RespinSymbolPoses":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'RespinSymbolPoses'))  . ',"RespinMuls":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'RespinMuls')) . ',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":'. $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') .',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"winLines":[],"Jackpots":"","LastReel":'.json_encode($lastReel).'}}';
                $slotSettings->SaveLogReport($_GameLog, $betline * $lines, $lines, $_obf_totalWin, $slotEvent['slotEvent']);
                
                if( $scattersCount >= 3 || ($slotEvent['slotEvent']!='respin' && $respinCount >= 2) ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                }
            }
            else if( $slotEvent['slotEvent'] == 'doBonus' ){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lines = 20;
                // $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', 1);
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') < $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') && $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') > 0 ) 
                {
                    $slotEvent['slotEvent'] = 'respin';
                }else{
                    $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"invalid bonus state"}';
                    exit( $response );
                    // $test=1;
                }
                $bgt = $slotSettings->GetGameData($slotSettings->slotId . 'Bgt');
                $respinSymbolPoses = $slotSettings->GetGameData($slotSettings->slotId . 'RespinSymbolPoses');
                $respinMuls = $slotSettings->GetGameData($slotSettings->slotId . 'RespinMuls');
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') + 1);
                $bonusMpl = $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl');
                $Balance = $slotSettings->GetBalance();
                $lastReel = $slotSettings->GetGameData($slotSettings->slotId . 'LastReel');
                $tempReels = [];
                for($i = 0; $i < 5; $i++){
                    $tempReels[$i] = [];
                    for($j = 0; $j < 3; $j++){
                        $tempReels[$i][$j] = $lastReel[$j * 5 + $i];
                    }
                }
                $_obf_winType = rand(0, 1);
                $rhinoSymbol = 3;
                for($i = 0; $i < 2000; $i++){
                    $respinChanged = false;
                    $totalWin = 0;
                    $rhinoCount = 0;
                    $reels = [];
                    $strWinLine = '';
                    $acc_count = 0;
                    $arr_accv = [];
                    if($bgt == 47){
                        for($r = 0; $r < 5; $r++){
                            $reels[$r] = [];
                            for($j = 0; $j < 3; $j++){
                                if($tempReels[$r][$j] == $rhinoSymbol){
                                    $reels[$r][$j] = $tempReels[$r][$j];
                                    $rhinoCount++;
                                }else{
                                    if(rand(0, 100) < $slotSettings->base_rhino_chance && $_obf_winType == 1){
                                        $reels[$r][$j] = $rhinoSymbol;
                                        $rhinoCount++;
                                        $respinChanged = true;
                                        if(rand(0, 100) < 10){
                                            $respinMuls[$j * 5 + $r] = 2;
                                        }
                                    }else{
                                        $reels[$r][$j] = 12;//$tempReels[$r][$j];
                                    }
                                }
                            }
                        }
                    }else{
                        $reels=[[12,12,12],[12,12,12],[12,12,12],[12,12,12],[12,12,12]];
                        $isMake = false;
                        $mainX = -1;
                        $mainY = 0;
                        if($i > 1000){
                            $isMake = true;
                            $mainX = 0;
                            $mainY = mt_rand(0, 2);
                        }
                        for($k = 0; $k < count($respinSymbolPoses); $k++){
                            if($respinSymbolPoses[$k] != 0){
                                array_push($arr_accv, $k);
                                $acc_count++;
                                if($isMake == true && $mainX < 3){
                                    if($reels[$mainX][$mainY] == 12){
                                        $reels[$mainX][$mainY] = 3;
                                        $mainX++;
                                    }
                                }else{
                                    while(true){
                                        $posX = rand(0, 4);
                                        $posY = rand(0, 2);
                                        if($reels[$posX][$posY] == 12){
                                            $reels[$posX][$posY] = 3;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $arr_bglmvs = [];
                    $arr_bglmis = [];
                    if($rhinoCount == 14){
                        $totalWin = $lines * $betline * 375;
                    }else if ($rhinoCount == 15){
                        $totalWin = $lines * $betline * 500;
                    }else{
                        $_obf_winCount = 0;
                        for( $k = 0; $k < $lines; $k++ ) 
                        {
                            $_lineWin = '';
                            $firstEle = $reels[0][$linesId[$k][0] - 1];
                            $lineWinNum[$k] = 1;
                            $lineWins[$k] = 0;
                            $mul = $respinMuls[($linesId[$k][0] - 1) * 5];
                            if($mul == 0){
                                $mul = 1;
                            }
                            for($j = 1; $j < 5; $j++){
                                $ele = $reels[$j][$linesId[$k][$j] - 1];
                                if($respinMuls[($linesId[$k][0] - 1) * 5 + $j] > 0){
                                    $mul = $mul * $respinMuls[($linesId[$k][0] - 1) * 5 + $j];
                                }
                                if($ele == $firstEle){
                                    $lineWinNum[$k] = $lineWinNum[$k] + 1;
                                    if($j == 4){
                                        $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline * $mul;
                                        if($lineWins[$k] > 0){
                                            $totalWin += $lineWins[$k];
                                            $_obf_winCount++;
                                            $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                            for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                                $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                            }
                                            if($mul > 1){
                                                array_push($arr_bglmvs, $mul);
                                                array_push($arr_bglmis, $k);
                                            }
                                        }
                                    }
                                }else{
                                    if($slotSettings->Paytable[$firstEle][$lineWinNum[$k]] > 0){
                                        $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline * $mul;
                                        if($lineWins[$k] > 0){
                                            $totalWin += $lineWins[$k];
                                            $_obf_winCount++;
                                            $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                            for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                                $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                            }   
                                            if($mul > 1){
                                                array_push($arr_bglmvs, $mul);
                                                array_push($arr_bglmis, $k);
                                            }
                                        }
                                    }else{
                                        $lineWinNum[$k] = 0;
                                    }
                                    break;
                                }
                            }
                        }
                    }
                    $winLineCount = 0;
                    for($k = 0; $k < 5; $k++){
                        if($reels[$k][0] == 3 || $reels[$k][1] == 3 || $reels[$k][2] == 3){
                            $winLineCount++;
                        }else{
                            break;
                        }
                    }
                    if($i > 500){
                        $_obf_winType = 0;
                    }
                    if($rhinoCount > 12){
                        $_obf_winType = 0;
                    }else if($rhinoCount > 13 || ($slotSettings->GetGameData($slotSettings->slotId . 'RespinGames')<= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') && $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') > 0)){
                        if($totalWin > 0 && $winLineCount >= 3){ //$slotSettings->GetBank('') > $totalWin
                            break;
                        }else{
                            $_obf_winType = 1;
                        }
                    }else if( $_obf_winType== 0 && $slotEvent['slotEvent'] == 'respin' &&  $respinChanged == false){
                        break;
                    }else if( $slotSettings->GetBank('') > $totalWin ) 
                    {
                        break;
                    }
                }
                
                
                $isEndRespin = false;
                if($respinChanged == true && $rhinoCount < 14){
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') + $slotSettings->slotRespinCount);
                }else{
                    if($rhinoCount > 13 || ($slotSettings->GetGameData($slotSettings->slotId . 'RespinGames')<= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') && $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') > 0)){
                        $isEndRespin = true;
                    }
                }
                for($k = 0; $k < 3; $k++){
                    for($j = 0; $j < 5; $j++){
                        $lastReel[$j + $k * 5] = $reels[$j][$k];
                    }
                }
                $strLastReel = implode(',', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                if($isEndRespin == true){
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                    if($bgt == 47){
                        $spinType = 'cb';
                    }else{
                        $spinType ='c';
                    }
                    $spinType = $spinType . '&tw='. $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strWinLine .'&rw='.$totalWin.'&end=1&bpw=0&coef=' . $betline;
                    if(count($arr_bglmis) > 0){
                        $spinType = $spinType . '&bg_lmv=' . implode(',', $arr_bglmvs) . '&bg_lmi=' . implode(',', $arr_bglmis);
                    }
                }else{
                    $spinType = 'b&end=0&bpw=' . $totalWin;
                }
                if($bgt == 47){
                    $spinType = $spinType . '&bgid=1';
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinMuls', $respinMuls);
                }else{
                    
                    $spinType = $spinType . '&bgid=0&acci=0&accm=cp~tp~s~sp&accv=10~10';
                    if($acc_count > 0){
                        $spinType = $spinType . '~3~' . implode(',', $arr_accv);
                    }
                }
                $arr_bgmps = [];
                $arr_bgmvs = [];
                for($k = 0; $k < 15; $k++){
                    if($respinMuls[$k] > 0){
                        array_push($arr_bgmps, $k);
                        array_push($arr_bgmvs, $respinMuls[$k]);
                    }
                }
                if(count($arr_bgmps) > 0){
                    $spinType = $spinType . '&bg_mp='. implode(',', $arr_bgmps) .'&bg_mv='. implode(',', $arr_bgmvs);
                }
                $response = 'rsb_m='.$slotSettings->GetGameData($slotSettings->slotId . 'RespinGames').'&balance='.$Balance.'&rsb_c='.$slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame').'&index='. $slotEvent['index'] . 
                '&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType.'&stime=' . floor(microtime(true) * 1000) .'&bgt='.$bgt.'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&s='.$strLastReel;
                if($isEndRespin == true) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                    if( $totalWin > 0) 
                    {
                        $slotSettings->SetBalance($totalWin);
                        $slotSettings->SetBank('', -1 * $totalWin);
                    }
                }else{
                    $totalWin = 0;
                }
                

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"totalRespinGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') . ',"currentRespinGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') . ',"Balance":' . $Balance  . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt')  . ',"RespinSymbolPoses":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'RespinSymbolPoses'))  . ',"RespinMuls":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'RespinMuls')) . ',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":'. $totalWin .',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"winLines":[],"Jackpots":"","LastReel":'.json_encode($lastReel).'}}';
                if($isEndRespin == true){
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 0);
                }
                $slotSettings->SaveLogReport($_GameLog, $betline * $lines, $lines, $totalWin, $slotEvent['slotEvent']);
            }
            $slotSettings->SaveGameData();
            \DB::commit();
            return $response;
        }
    }

}

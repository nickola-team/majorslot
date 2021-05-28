<?php 
namespace VanguardLTE\Games\BuffaloKingMegawaysPM
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
            $isBuyFreespin = -1;// 프리스핀 사면 0 or -1
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
                $otherResponse = '';
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);                
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'DoubleChance', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusTumbMpl', 1);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusTumbMpls', []);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusTumbMplPos', []);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [113,11,9,11,7,13,12,11,5,7,12,12,8,7,5,4,12,12,8,9,5,4,12,12,8,9,12,9,6,7,6,5,11,10,13,8,12,9,13,10,13,13,13,3,13,13,13,13]);
                $slotSettings->setGameData($slotSettings->slotId . 'BinaryReel', [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReelSymbolCount', [0, 0, 0, 0, 0, 0]);
                $strTopTmb = '';
                $strMainTmb = '';
                $strTopTmb = '';
                $strMainTmb = '';
                $strTopRmul = '';
                $strMainRmul = '';
                $strWinLine = '';
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'DoubleChance', $lastEvent->serverResponse->DoubleChance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReelSymbolCount', $lastEvent->serverResponse->ReelSymbolCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusTumbMpl', $lastEvent->serverResponse->BonusTumbMpl);
                    if($lastEvent->serverResponse->BonusTumbMpls != ""){
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusTumbMpls', explode(',', $lastEvent->serverResponse->BonusTumbMpls));
                    }
                    if($lastEvent->serverResponse->BonusTumbMplPos != ""){
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusTumbMplPos', explode(',', $lastEvent->serverResponse->BonusTumbMplPos));
                    }
                    $bet = $lastEvent->serverResponse->bet;
                    $strTopTmb = str_replace('|', '"', $lastEvent->serverResponse->strTopTmb);
                    $strMainTmb = str_replace('|', '"', $lastEvent->serverResponse->strMainTmb);    
                    $strTopRmul = str_replace('|', '"', $lastEvent->serverResponse->strTopRmul);
                    $strMainRmul = str_replace('|', '"', $lastEvent->serverResponse->strMainRmul);                   
                    $strWinLine = $lastEvent->serverResponse->winLines;
                }
                else
                {
                    $bet = $slotSettings->Bet[0];
                }
                $currentReelSet = 4;
                $spinType = 's';
                $bonusMul = $slotSettings->GetGameData($slotSettings->slotId . 'BonusTumbMpl');
                $bonusTumbMuls = $slotSettings->GetGameData($slotSettings->slotId . 'BonusTumbMpls');
                $bonusTumbPoses = $slotSettings->GetGameData($slotSettings->slotId . 'BonusTumbMplPos');
                $binaryReel = $slotSettings->GetGameData($slotSettings->slotId . 'BinaryReel');
                for($i = 0; $i < count($bonusTumbPoses); $i++){
                    if($binaryReel[$bonusTumbPoses[$i]] == 2){  
                        $bonusMul = $bonusMul / $bonusTumbMuls[$i];
                    }
                }
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {    
                    $otherResponse = '&fs=' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&w=0.00&fsmul=1';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $currentReelSet = 6;
                    $otherResponse = $otherResponse . '&accm=cp&acci=0&accv=' . $bonusMul;
                    if($bonusMul > 1){
                        $otherResponse = $otherResponse . '&apv=' . $bonusMul;
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                        $otherResponse = $otherResponse . '&puri=0';
                    }
                }else{
                    $Balance = $slotSettings->GetBalance();
                }
                if( $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') > 0){
                    $otherResponse = $otherResponse.'&rs=mc&tmb_win='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbWin').'&rs_p='.($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') - 1).'&rs_c=1&rs_m=1';
                }
                $lastReel = $slotSettings->GetGameData($slotSettings->slotId . 'LastReel');
                $lastReelStr = implode(',', $lastReel);
                $topLastReel = [];
                $mainLastReel = [];
                for($k = 0; $k < 48; $k++){
                    if($k > 0 && $k < 5){
                        $topLastReel[$k -1] = $lastReel[$k];
                    }else if($k >= 6){
                        array_push($mainLastReel, $lastReel[$k]);
                    }
                }
                $response = 'def_s=13,11,9,11,7,13,12,11,5,7,12,12,8,7,5,4,12,12,8,9,5,4,12,12,8,9,12,9,6,7,6,5,11,10,13,8,12,9,13,10,13,13,13,3,13,13,13,13&balance='. $Balance . $strWinLine .'&nas=13&cfgs=1&ver=2&index=1&balance_cash='. $Balance .'&reel_set_size=14&balance_bonus=0.00&na='.$spinType.'&scatters=1~100,20,5,0,0,0~0,0,0,0,0,0~1,1,1,1,1,1&gmb=0,0,0&rt=d&gameInfo={rtps:{ante:"96.49",purchase:"96.50",regular:"96.52"},props:{max_rnd_sim:"1",max_rnd_hr:"1033057",max_rnd_win:"5000",max_rnd_win_a:"4000"}}&wl_i=tbm~5000;tbm_a~4000&bl='.$slotSettings->GetGameData($slotSettings->slotId . 'DoubleChance').'&stime=' . floor(microtime(true) * 1000) .'&reel_set10=9,11,5,3,7,11,5,11,7,5,7,3,5,11,7,11,3,11,5,7,5,11,7,5,11,7,11,7,5,11,5,11,5,11,7,11,7,11,5,7,11,5,11,5,7,11,5,7,5,7,11,5,11,7,11,7,5,11,7~12,12,12,4,12,8,10,6,8,8,8,6,6,6,4,8,4,10,8,6,8,6,10,4,8,6,8,10,6,8,6,8,6,8,4,6,8,10,6,8,6,8~8,6,6,6,3,5,12,12,12,10,6,5,5,5,12,7,11,9,9,9,9,8,8,8,11,11,11,6,9,12,9,12,7,12,11,6,12,6,5,12,3,12,9,6,12,3,11,12,6,9~6,6,6,5,11,9,7,10,4,3,12,12,12,8,6,8,8,8,12,9,9,9,10,10,10,5,5,5,4,4,4,12,10,11,12,10,12~11,6,6,6,6,8,3,5,5,5,12,4,7,9,9,9,9,10,7,7,7,5,12,12,12,10,6,7,12,6,7,5,4,7,5,3,6,5,7,12,5,10,7,5,7,10,6,4,3,7,10,6,3,6,4,7~11,8,9,4,12,10,10,10,3,10,7,7,7,7,6,12,12,12,5,9,9,9,12,10,9,12,7,10,9,7,4,9&sc='. implode(',', $slotSettings->Bet) .'&defc=100.00&reel_set11=4,6,8,4,10,6,4,6,8,4,8,4,6,8,6,8,6,8,6,4,8,6,4,8,6,4,8,6,4,6,8,6,8,6,8,6,10,8,6,12,4,12,12,12&reel_set12=5,5,5,3,5,11,11,11,1,7,7,7,9,8,6,10,10,10,7,6,6,6,11,12,10,4,3,3,3,9,9,9,8,8,8,12,12,12,4,4,4,12,4,6,4,12,9,4,9,11,4,6,4,12,4,12,3,7,4,7,6,4,11,4,12,10,6,3,4,8,4,10,12,4,12,4,6,11,7,10,6,10,4,11,12,4,7,8,4,8,1,11,4,1,6,4,6,9,8,7,12,3,4,10,3,10,4,11,9,8,7,12,6,11,3,9,1,4,12,9,10,4,9,10,9,4,11,4,11,12,6,4,9,4,9,6,11,10,7,10,4,6,1,7,10,3,4,1,4,11,4,3,9,6,10,4,7,4,10,3,12,4,8,11,3,9,8,10,6,3,6,4,8,11,9,4,9,4,8,9,4,8,6,4,6,1,12,7,1,7,4,11,3,8,10,9,6,4,3,10,11,4,8,9,11,8,6,7,11,7,10,4,6,4,12,6,8,6,1,6,11,4,6,10,3,6,11,4,1,12,9,6,8,7,3,6,3,10,3,7,12,9,3,11,6,11,8,4,3,4,8,4,3,7,9,7,6,4,12,11,6,4,9,7,11,4~6,3,4,10,6,6,6,9,8,10,10,10,5,11,9,9,9,12,7,11,11,11,1,4,4,4,5,5,5,7,7,7,3,3,3,12,12,12,8,8,8,10,7,9,8,10,9,5,10,9,4,8,10,7,9,10,3,8,11,7,10,11,10,9,11,8,5,10,1,11,8,3,11,10,3,8,1,7,8,3,7,10,9,8,5,7,3,8,1,8,10,1,10,11,8,10,5,8,7,4,8,5,8,11,3,9,5,11,9,7,11,8,10,11,8,5,8,11,9,10,4,10,11,5,8,11,5,8,1,3,8,11,5,8,10,8,7,10,1,10,8,4,8,7,10,11,5,4,5,8,7,5,9,3,1,10,8,5,9,12,5,9,12,8,7,4,5,11,8,12,11,7,9,12,8,7,5,4,3,11,5,3,5,11,3,7,5,4,10,8,11,9,3,4,8,11,5,7,8,10,7,5,9,8,11,8,4,11,10,8,4,1,7,3,7,5,10,1,11,8,11~1,3,11,9,6,10,10,10,12,8,7,7,7,5,6,6,6,10,11,11,11,7,4,8,8,8,4,4,4,12,12,12,5,5,5,9,9,9,3,3,3,8,9,7,4,10,5,4,9,10,9,6,3,8,6,5,8,4,5,4,3,11,6,5,9,8,11,5,7,3,7,6,10,9,3,7,3,6,11,3,8,3,10,8,9,8,6,5,3,10,11,6,7,9,4,8,6,5,7,6,7,6,3,7,10,7,10,6,12,6,10,3,7,6,3,6,3,6,9,6,7,6,9,7,4,5,6,9,10,11,3,10,6,5,4,10,7,6,12,6,4,10,7,3,10,9,6,3,6,3,11,10,11,6,5,12,3,5,10,12,9,12,6,3,5,4,9,10,8,9,11,12,4,10,9,12,9,5,10,9,5,4,9,12,10,12,9,6,3,10,6,3,11,4,10,6~4,12,5,4,4,4,8,9,8,8,8,7,1,6,3,3,3,11,7,7,7,10,6,6,6,3,11,11,11,12,12,12,5,5,5,9,9,9,10,10,10,5,6,1,7,5,11,8,3,11,5,7,11,1,3,10,7,11,12,9,12,6,7,11,6,9,1,9,10,11,3,11,7,11,1,11,1,12,7,12,1,11,6,9,12,7,5,6,11,7,11,8,9,1,11,9,10,3,1,12,6,9,1,12~11,12,12,12,3,9,10,12,8,5,5,5,5,1,11,11,11,4,3,3,3,6,7,10,10,10,7,7,7,4,4,4,9,9,9,8,8,8,6,6,6,4,6,3,6,4,9,3,6,3,12,9,3,9,4,6,8,12,8,3,6,12,4,3,12,6,4,7,8,4,9,12,8,3,10,3,12,5,6,5,10,8,10,3,9,10,3,5,8,9,4,10,8,3,4,8,5,4,12,4,3,1,5,9,3,4,5,8,10,3,10,12,6,8,10,9,5,6,3,10,7,12,3,4,3,4,7,4,6,12,3,9,12,6,7,3,9,6,3,9,8,10,9,7,8,7,3,8,7,5,4,8,9,3,4,10,3,4,10,12,1,7,12,3,4,7,4,3,5,6,5,10,6,12,9,4,6,3,8,3,8,12,10,9,10,9,5,10,9,3,6,3,5,3,8,3,4,10,9,3,10,4,5,6,4,5,6,7,3,5,3,8,3,4,7,3,9,10,8,10,7,5,10,4,6,3,12,8,9,10,7,4,12,10,6,5,12,3,5,6,5,8,3,6,10,6,10,6,10,3,5,12,1,6,3,10,12,5,4~6,6,6,1,11,7,6,3,12,5,3,3,3,8,9,9,9,10,9,4,4,4,4,8,8,8,7,7,7,11,11,11,5,5,5,10,10,10,12,12,12,11,7,4,10,3,4,11,7,3,11,9,7,1,9,10,5,12,4,8,9,7,10,3,7,4,1,4,3,4,7,5,3,4,8,5,8,10,9,7,5,11,9,1,9,5,10,11,7,9,11,12,10,3,7,1,10,4,7,11,10,9,3,7,9,5,4,11,3,8,11,7,5,11,12,5,4,7,5,4,3,5,10,5,10,8,4,10,3,12,3,7,3,4,5,11,8,1,9,10,12,8,9,11,12,10,5,7,8,11,3,11,4,7,11,5,9,5,9,8,9,8,11,7,4,8,11,10,9,7,10,11,3,5,8,4,9,7,8,9,4,10,8,11,4,1,8,4,10,12,8,11,4,5,11,5,11,5,8,4,12,7,1,7,9,3,8,9,4,7,4,3,4,12,9,5,11,7,4,1,3,7,11,5,3,11,10,3,8,11,12,11,8,11,4,11,10,11,10,3,9,7,9,11,9,4,7,11,9,7,10,11,4,9,12,7,5,4,3,9,11,5,4,10,11,4,12,5,9,10,9,11,7,11,8,9,12,3,11,4,5,4,3,4,8,9,3,7,5,4,8,7,8,10,11,7,3,11,7,9,11,10,11,7,11,12,4,12,5,10,8,4,3,7,12,7&reel_set13=4,8,9,1,6,9,11,9,1,3,10,1,8,11,6,9,1,4,8,7,3,6,7,1,9,3,11,9,8,1,3,6,1,6,11,3,1,6,11,3,6,1,4,1,9,10,6,12,8,7,11,3,5&sh=8&wilds=2~0,0,0,0,0,0~1,1,1,1,1,1&bonuses=0&fsbonus=&st=rect&c='.$bet.$otherResponse.'&sw=6&sver=5&g={reg:{def_s:"12,11,5,7,12,12,8,7,5,4,12,12,8,9,5,4,12,12,8,9,12,9,6,7,6,5,11,10,13,8,12,9,13,10,13,13,13,3,13,13,13,13",def_sa:"10,9,10,6,12,3",def_sb:"12,3,3,11,10,10",prm:"2~2,3,5;2~2,3,5;2~2,3,5",reel_set:"'.($currentReelSet * 2).'"'. $strMainRmul .',s:"'. implode(',', $mainLastReel) .'",sa:"10,9,10,6,12,3",sb:"12,3,3,11,10,10",sh:"7",st:"rect",sw:"6"'. $strMainTmb .'},top:{def_s:"7,11,9,11",def_sa:"3",def_sb:"7",prm:"2~2,3,5;2~2,3,5;2~2,3,5",reel_set:"'.($currentReelSet * 2 + 1).'"'. $strTopRmul .',s:"'. implode(',', $topLastReel) .'",sa:"3",sb:"7",sh:"4",st:"rect",sw:"1"'. $strTopTmb .'}}&bls=20,25&counter=2&paytable=0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;400,200,80,40,20,0;125,50,25,15,0,0;75,25,15,10,0,0;30,15,10,6,0,0;30,15,10,6,0,0;20,10,6,4,0,0;20,10,6,4,0,0;15,8,4,2,0,0;12,8,4,2,0,0;12,8,4,2,0,0;0,0,0,0,0,0&l=20&rtp=96.52&total_bet_max=10,000,000.00&reel_set0=6,6,6,12,12,12,12,8,6,1,10,4,8,8,8,12,4,12,8,4,8,12,8,12,8,10,8,12,8,10,8,10,8,12,4,8,10,12,10,8,10,4,8,4,8,12,8,4,8,12,4,8,10,12,10,8~3,9,5,1,11,7,11,1,9,7,11,9,11,1,9,11,1,11,5,1,11,1,7,11,1,7,1,7,5,11,1,11,9,1,11,9,11,9,7,11,1,5,1,11,1,11,1,9,11,1,7,1,5,7,1,11,1,9~3,9,6,1,12,10,11,5,8,7,11,11,11,6,6,6,12,12,12,5,5,5,9,9,9,8,8,8,6,9,11,9,1,9,6,5,8,11,12,11,5,9,5,1,6,11,9,8,6,11,6,8,12,6,12,6,11,1,5~10,10,10,1,8,8,8,5,4,4,4,7,12,8,5,5,5,6,9,9,9,4,9,3,10,11,6,6,6,12,12,12,5,6,7,11,6~12,8,10,4,1,6,9,9,9,11,5,7,7,7,7,3,9,5,5,5,6,6,6,12,12,12,9,5,10,9,5,8,4,10,7,11,5,11,5,7,6,3,1,11,3,6,3,7,11,9,1,6,5,3,7,6,3,10~9,12,12,12,1,10,10,10,7,12,9,9,9,5,6,11,3,7,7,7,4,10,8,12,5,10,7,8,11,10,5,7,10,5,10,12,11,12,10,12,1,7,11,1,7,10,1,10,7,1,12,10,11,12,10,1&s='.$lastReelStr.'&accInit=[{id:0,mask:"cp;mp"}]&reel_set2=11,1,5,7,9,3,1,9,1,9,1,9,1,9,3,9,1,5,3,1,7,1,9,1,3,9,1,9,7,1,7,9,1,5,1,9,7,9,7,1,9,3,9,1,7,1,9,1,5,9,1,7,9,5,3~6,1,12,12,12,4,6,6,6,12,8,10,8,8,8,12,8,12,10,12,1,12,8,12,8,12,10,8,4,1,8,12,1,8,12,8,1,8,4,1,12,1,12,8,12,8,12,8,12,8,4,12,1,8,12,1,8,1,4,8~5,5,5,12,12,12,12,7,3,11,11,11,6,6,6,6,8,5,8,8,8,9,10,1,11,9,9,9,12,11,10,8,12,11,8,9,3,8,3,8,1,10,8,11,12,8,6,8,11,9,6,8,9,11,12,8,6,8,6,8,10,8,6,12~1,9,8,8,8,3,4,4,4,10,4,6,6,6,12,7,10,10,10,8,5,11,6,9,9,9,12,12,12,5,5,5,12,9,5,9,8,9,4,12,5,9,8,10,9,6,12,4,5,9,4,10,4,8,10,12,8,9,12,6,9,10,9,12,8~10,7,9,5,12,12,12,8,6,6,6,1,5,5,5,12,9,9,9,6,11,4,3,7,7,7,11,9,7,9,5,4,5,6,5,7,9,7,12,7,12,11,4,12,4,7,12,6,7,12,5,12,7,5~10,10,10,1,10,12,8,5,7,7,7,11,7,4,9,12,12,12,3,6,9,9,9,4,7,5,7,3,7,9,12,9,4,1,7,9,12,9,7,4,7,4,12,6,12,3,4,12,9,12,8,4,12,3,9,3,7,12,11,3,11&t=243&reel_set1=5,11,9,11,7,9,11,5,11,7,11,7,1,9,5,11,5,7,1,7,5,11,9,11,7,11,5,11,7,11,5,11,5,11,9,11,11,11,11,5,1,7,3&reel_set4=8,8,8,4,12,1,12,12,12,8,6,6,6,6,10,6,12,4,10,6,10,1,6,12,6,4,12,4,12,4,12,1,4,12,6,4,10,12,4,10,6,4,6,10,12,6,12,10,6,12,10,4,12,6,10,4,12,4~7,5,3,1,9,11,9,11,5,1,5,1,11,1,5,1,9,5,1,5,3,9,3,1,3,11,1,5,9,11,3,5,1,3,5,11,3,1,3,5,3,1,3,9~8,12,1,10,8,8,8,6,12,12,12,9,9,9,9,7,6,6,6,5,3,11,11,11,11,5,5,5,12,11,12,10,12,11,5,9,5,9,12,10,6,9,5,11,6,9,12,5~4,3,5,5,5,11,6,6,6,10,8,8,8,7,6,12,1,5,9,9,9,9,8,12,12,12,4,4,4,10,10,10,9,7,8,12,5,7,6,3,12,9,6,12,8,3,6,12~12,5,5,5,5,6,12,12,12,3,9,8,6,6,6,4,1,9,9,9,10,11,7,7,7,7,6,7,5,6,9,7,9,6,8,5,7,6,5,8,6,7,4,9,7,3,5,7,8,4,6,7,9,3,6,4,11~3,9,5,10,10,10,11,8,9,9,9,12,4,10,7,7,7,6,7,1,12,12,12,9&purInit=[{type:"fsbl",bet:2000,bet_level:0}]&reel_set3=12,1,12,4,12,4,12,1,10,4,12,4,12,10,1,12,10,1,10,12,10,4,8,1,12,12,12,12,6&reel_set6=5,11,7,1,3,9,7,11,3,11,1,7,3,7,11,7,1,7,1,7,1,7,1,7,11,7,1,11,7,3,7,1,7,9,7,11,7,1,3,7,1,9,3,7,1,7,11,1,7,1,9,1,9,7,3~10,12,6,8,8,8,1,4,8,6,6,6,12,12,12,8,12,6,8,6,12,6,12,8,12,8,6,4,12,8,12,6,1,8,6,8,12,8,12,8,6,12,1~9,10,6,11,3,5,12,12,12,7,11,11,11,8,5,5,5,1,12,6,6,6,9,9,9,8,8,8,7,6,11,8,6,8,5,11,5,11,8,5,7,11,5,12,5,3,12,7,5,12,8,5~5,5,5,3,11,12,12,12,5,4,4,4,10,6,9,8,9,9,9,12,10,10,10,7,1,8,8,8,4,6,6,6,12,10,12,8,12,6,12,6,12,8,9,10,6,8,10,9,10,9,6,7,12,7,9,6,9,8,10,4,9,12,6~7,7,7,8,9,7,11,5,5,5,12,9,9,9,4,5,6,12,12,12,1,10,3,6,6,6,9,5,4,6,5,8,5,12,5,12,5,9~8,11,4,7,12,6,10,10,10,10,5,1,9,7,7,7,3,9,9,9,12,12,12,7,10,12,7,10,12,1,6,1,7,9,6,12,9,10,9,1,7,9,6,9,10,1,7&reel_set5=3,5,3,9,1,3,1,3,5,3,1,11,9,5,7,11,11,11&reel_set8=11,3,4,4,4,8,9,7,6,8,8,8,4,5,5,5,1,7,7,7,5,12,10,3,3,3,6,6,6,10,10,10,9,9,9,11,11,11,12,12,12,8,9,12,5,3,6,5,3,9,5,1,6,4,7,10,8,5,6,7,9,3,9,3,8,10,9,4,6,7,6,12,9,3,12,7,1,8,10,3~11,11,11,9,10,10,10,6,4,4,4,12,9,9,9,4,11,5,1,7,2,8,8,8,8,10,3,3,3,3,5,5,5,12,12,12,6,6,6,7,7,7,8,10,5,10,6,12,5,9,10,8,10,4,3,7,8,5,3,6,10,5,6,12,6,10,6,12,4,12,10,4,10,1,12,8,10,3,1,12,10,7,10,8,4,3,10,3,12,7,10,2,7,12,3,10,12,3,10,9,7,9,10,12,10,12,10,6,7,5,3,9,7,3,7,6,10,12,8,10,9,3,12,4,10,12,6,12,6,9,4,5,3,6,12,10,7,8,12,7,10,12,10,12,5,4,12,2,6,10,8,12,8,10,9,6,4,10,12,10,7,4,10,3,12,8,6,2,7,12,3,12,10,7,3,10,4,3,12,7,10,8,5,3,7,8,7,9,2,12,7,3,5,10,3,12,3,5,10,12,5,10,3,7,3,6,7,3,7,12,7,6,3,10,3,8,10,2,6,7,3,12,10,7,2,1,5,4,12,9,5,12,6,7,10,12,7,6,12,5,6,1,6,9,12,4,3,9,10,6,5,10,4,3,12,10,12,6,5,12,9,12,10,6,4,9,7,3,8,5,6,7,10,6,7,8,12,4,7,6,8,4,9,12,9,8,6,5,12,10,7,10~4,4,4,10,5,5,5,8,5,6,10,10,10,1,9,7,11,12,8,8,8,2,7,7,7,4,3,6,6,6,11,11,11,12,12,12,9,9,9,3,3,3,11,10,9,10,1,5,12,9,6,8,7,3,6,8,5,9,12,9,5,9,7,12,5,2,11,8,5,10,9,5,11,3,5,3,9,3,9,6,10,12,10,7,1,11,5,6,3,12,6,5,7,8,10,7,3,9,6,12,1,5,10,12,11,5,11,10,7,9,3,9,6,3,9,7,1,10,5,7,6,3,8,3,10,6,8,12,9,11,7,3,6,3,9,8,9,3,5,3,2,11,3,8,10,7,11,8,2,11,10,3,5,3,1,3,12,8,9,8,5,9,3,12,3,11,10,3,7,6,7,11,6,10,5,7,10,3,6,3,5,3,11,3,8,9,3,12,11,5,9,7,1,12,11,10,9,6,5,8,7,10,2,6,9,10,7,10,12,10,3,5,10,3,11,3,9,7,12,10,12,10,8,9,10,3,10,2,5,6,5,9,11,7,5,9,12,9,7,5,8,3,2,9,10,3,10,12,11,5,3,9,7,2,3,7,5,12,2,9,5,10,5,2,7,3,10,7,5,6,3,10,6,11,10,9,12,7,8,11,3,8,9,3,9,7,1,11,7,10,3,5,6,5,3,9,5,10,2,3,8,10,7,10,8,10,8,11,5,10,12,10,9,3,2,9,7,9,3,12,6,10~4,3,3,3,5,10,9,9,9,8,11,12,12,12,12,3,7,11,11,11,9,6,6,6,2,1,10,10,10,6,7,7,7,8,8,8,5,5,5,4,4,4,3,11,7,6,5,8,5,10,3,6,12,5,12,5,9,8,3,7,5,8,3,5,9,7,6,8,11,9,6,8,6,7,5,7,3,10,9,8,7,3,10,9,5,7,5,6,7,3,11,3,9,8,12,6,7,8,7,3,5,12,5,7,8,3,7,3,7,3,7,12,3,9,7,5,7,8,7,11,8,7,10,7,12,3,6,7,5,7,3,7,5,8,3,9,8,12,8,3,6,8,6,9,7,6,8,9,3,6,3,8,5,7,3,9,2,11,2,9,8,5,7,5,8,3,9,3,5,6,8,7,11,7,3,7,8,1,7,9,8,3,6,8,9,5,3,6,9,7,9,7,6,3,5,11,5,10,7,5,3,11,12,7,8,7,9,7,8,9,3,8,11,5~3,10,4,12,12,12,8,10,10,10,1,11,11,11,11,7,5,9,9,9,12,9,2,6,3,3,3,4,4,4,6,6,6,5,5,5,8,8,8,7,7,7,5,7,10,11,10,6,8,7,6,8,5,4,11,6~12,12,12,10,6,9,3,7,4,5,1,9,9,9,11,8,12,4,4,4,6,6,6,8,8,8,5,5,5,10,10,10,7,7,7,3,3,3,11,11,11,6,10,4,6,4,11,10,11,7,8,3,6,7,9,5,4,6,7,11,9,6,3,11,6,4,9,8,7,10,7,3,11,3,5,6,7,9,11,7,3,6,8,6,4,9,7,10,7,4,11,10,9,10,4,10,6,11,6,11,7,11,3,6,11,9,7,9,10,6,10,9,3,10,7,6,4,10,7,10,11,6,8,6,5,10,4,10,6,10,6,9,3,8,10,8,10,9,6,9,10,6,4,3,11,8,4,6,4,6,4,7,6&reel_set7=8,10,6,10,6,8,6,1,4,8,10,12,12,12,12&reel_set9=3,8,9,12,4,8,12,8,9,12,2,7,8,1,5,9,4,3,11,6,10&total_bet_min=10.00';
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
                $lines = $slotEvent['slotLines'];
                $betline = $slotEvent['slotBet'];
                if(isset($slotEvent['pur'])){
                    $isBuyFreespin = $slotEvent['pur'];
                }
                $isdoublechance = $slotEvent['bl'];// 더블기능 1 or 0
                $slotSettings->SetGameData($slotSettings->slotId . 'DoubleChance', $isdoublechance);  
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $isBuyFreespin);  
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
                    if($isdoublechance == 1 && $isBuyFreespin == 0){
                        $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"invalid bonus"}';
                        exit( $response );
                    }
                    if( $slotEvent['slotEvent'] == 'doSpin' && $slotSettings->GetBalance() < ($lines * $betline) ) 
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
                
                $reelSet_Num = 4;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $reelSet_Num = 6;
                }
                $allBet = $betline * $lines;
                if($isdoublechance == 1){
                    $allBet = $betline * 25;
                }
                if($isBuyFreespin == 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $betline * 2000;
                    $winType = 'bonus';
                    $_winAvaliableMoney = $slotSettings->GetBank('bonus');
                }else{
                    $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $allBet, $lines, $isdoublechance);
                    $winType = $_spinSettings[0];
                    $_winAvaliableMoney = $_spinSettings[1];
                }
                $isTumb = false;
                $lastReel = null;
                if($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') > 0){
                    $isTumb = true;
                    $arr_lastReel = $slotSettings->GetLastReel($slotSettings->GetGameData($slotSettings->slotId . 'LastReel'), $slotSettings->GetGameData($slotSettings->slotId . 'BinaryReel'), $slotSettings->GetGameData($slotSettings->slotId . 'BonusTumbMplPos'), $slotSettings->GetGameData($slotSettings->slotId . 'BonusTumbMpls')); // 텀브스핀일때 이전 릴배치도에서 당첨되지않은 심볼 배열, 와일드 위치, 와일드 배당값을 결정
                    $lastReel = $arr_lastReel[0];
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusTumbMplPos', $arr_lastReel[1]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusTumbMpls', $arr_lastReel[2]);
                }else{
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusTumbMpl', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusTumbMpls', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusTumbMplPos', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReelSymbolCount', [0, 0, 0, 0, 0, 0]);
                    if($slotEvent['slotEvent'] == 'freespin'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                        $leftFreeGames = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') - $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');    
                    }else
                    {
                        $slotEvent['slotEvent'] = 'bet';
                        $slotSettings->SetBalance(-1 * ($allBet), $slotEvent['slotEvent']);
                        if($isBuyFreespin == 0){
                            $_sum = $allBet;
                        }else{
                            $_sum = $allBet / 100 * $slotSettings->GetPercent();
                        }
                        $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $_sum, $slotEvent['slotEvent'], $isBuyFreespin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
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
                    $defaultScatterCount = $slotSettings->GenerateScatterCount($slotEvent);  // 생성되어야할 Scatter개수 결정
                }
                
                for( $i = 0; $i <= 2000; $i++ ) 
                {
                    $totalWin = 0;
                    $this->winLines = [];
                    $wild = '2';
                    $scatter = '1';
                    $_obf_winCount = 0;
                    $strWinLine = '';
                    $winLineMulNums = [];      
                    $bonusMul = $slotSettings->GetGameData($slotSettings->slotId . 'BonusTumbMpl');
                    $bonusMuls = $slotSettings->GetGameData($slotSettings->slotId . 'BonusTumbMpls');
                    $bonusMulPoses = $slotSettings->GetGameData($slotSettings->slotId . 'BonusTumbMplPos');             
                    $reelSymbolCount = $slotSettings->GetGameData($slotSettings->slotId . 'ReelSymbolCount');
                    $reels = $slotSettings->GetReelStrips($winType, $slotEvent['slotEvent'], $reelSet_Num, $defaultScatterCount, $isTumb, $reelSymbolCount);
                    if($slotEvent['slotEvent'] == 'freespin' || $isTumb == true){
                        for( $k = 0; $k < 8; $k++ ){
                            $isNewWild = false; // 릴에 와일드가 이미 생성되었으면 true or false
                            for($r = 0; $r < 6; $r++)
                            {
                                if($lastReel != null && $lastReel[$r][$k] > -1 && $lastReel[$r][$k] < 13) 
                                {                                
                                    $reels['reel' . ($r+1)][$k] = $lastReel[$r][$k]; // 텀브스핀일때 이전심볼배열을 새배열에 추가
                                }else{
                                    if($slotEvent['slotEvent'] == 'freespin' && $r > 0 && $r < 5 && $reels['reel' . ($r+1)][$k] < 13 && rand(0, 100) < 3 && $isNewWild == false){  // 프리스핀에서 와일드 생성및 배당값 설정
                                        $reels['reel' . ($r+1)][$k] = $wild;
                                        array_push($bonusMuls, $slotSettings->GetMultiValue());
                                        if($k == 0){
                                            array_push($bonusMulPoses, 5 - $r);
                                        }else{                                            
                                            array_push($bonusMulPoses, $k * 6 + $r);
                                        }
                                        $isNewWild == true;
                                    }
                                }                                
                            }
                        }
                    }
                    
                    $binaryReel = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                    $isNewTumb = false;
                    for($r = 0; $r < 8; $r++){
                        if($reels['reel1'][$r] != $scatter && $reels['reel1'][$r] != 13){
                            $this->findZokbos($reels, $reels['reel1'][$r], 1, '~'.($r * 6));
                        }                        
                    }
                    for($r = 0; $r < count($this->winLines); $r++){
                        $winLine = $this->winLines[$r];
                        $winLineMoney = $slotSettings->Paytable[$winLine['FirstSymbol']][$winLine['RepeatCount']] * $betline;
                        if($winLineMoney > 0){                            
                            $isNewTumb = true;
                            $strWinLine = $strWinLine . '&l'. $r.'='.$r.'~'.$winLineMoney . $winLine['StrLineWin'];
                            $totalWin += $winLineMoney;
                            for($k = 0; $k < $winLine['RepeatCount']; $k++){
                                for($j = 0; $j < 8; $j++){
                                    if($reels['reel'.($k + 1)][$j] == $winLine['FirstSymbol'] || $reels['reel'.($k + 1)][$j] == 2){ // 당첨된 심볼들은 심볼값 or 0
                                        if($j == 0){
                                            $binaryReel[5 - $k] = $reels['reel'.($k + 1)][$j]; // Top에서는 릴배치도가 반대로 되어있음
                                        }else{
                                            $binaryReel[$j * 6 + $k] = $reels['reel'.($k + 1)][$j];
                                        }
                                    }
                                }
                            }
                        }
                    } 
                    // 프리스핀일때 당첨된 와일드 멀티값 얻기
                    if($slotEvent['slotEvent'] == 'freespin'){
                        for($k = 0; $k < count($bonusMulPoses); $k++){
                            if($binaryReel[$bonusMulPoses[$k]] == 2 || ($isTumb == true && $isNewTumb == false)){     // 텀브스핀이 끝날때 당첨되지않은 와일드 멀티값도 추가함
                                $bonusMul = $bonusMul * $bonusMuls[$k];
                            }
                        }
                    }
                    $scattersCount = 0;
                    $_obf_scatterposes = [];
                    for($r = 0; $r < 6; $r++){
                        for( $k = 0; $k < 8; $k++ ) 
                        {
                            if( $reels['reel' . ($r+1)][$k] == $scatter ) 
                            {                                
                                $scattersCount++;
                                if($k == 0){
                                    array_push($_obf_scatterposes, 4 - $r);
                                }else{
                                    array_push($_obf_scatterposes, $k * 6 + $r);
                                }
                            }
                        }
                    }

                    $freeSpinNum = 0;
                    $scatterWin = 0;
                    if($slotEvent['slotEvent'] == 'freespin'){
                        if($scattersCount >= 3){
                            $freeSpinNum = 5;
                        }
                    }else{
                        if($scattersCount >= 4){
                            $scatterMul = $slotSettings->Paytable[$scatter][$scattersCount];
                            $scatterWin = $scatterMul * $betline * $lines;
                            $freeSpinNum = $slotSettings->GetFreeSpin($scattersCount);
                        }
                    }
                    if($isNewTumb == false){
                        $totalWin = $totalWin + $scatterWin;
                    }
                    if( $i >= 1000 ) 
                    {
                        $winType = 'none';
                    }
                    if( $i > 1500 ) 
                    {
                        /*$response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"Bad Reel Strip"}';
                        exit( $response ); */
                        break;
                    }
                    if( !$isTumb && $scattersCount >= 4 && ( $winType != 'bonus' || $scattersCount != $defaultScatterCount)) 
                    {

                    }
                    else if( $slotEvent['slotEvent'] == 'freespin' && $freeSpinNum > 0 && rand(0, 100) < 90 ){
                        // 프리스핀에서 프리스핀당첨 확률 10%
                    }
                    else if( ($totalWin + $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin')) * $bonusMul <= $_winAvaliableMoney && $winType == 'bonus' && $scattersCount >= 4 ) 
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
                }else if($bonusMul > 1 && $slotEvent['slotEvent'] == 'freespin'){
                    // 텀브스핀이 끝날때 누적된 배당값을 총당첨금에 적용
                    $totalWin = $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') * ($bonusMul - 1);
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }
                $topLastReel = [];
                $mainLastReel = [];
                $strTopWildMuls = [];
                $strMailWildMuls = [];
                for($k = 0; $k < count($bonusMulPoses); $k++){
                    if($bonusMulPoses[$k] < 6){
                        array_push($strTopWildMuls, '2~'.($bonusMulPoses[$k] - 1).'~'.$bonusMuls[$k]); 
                    }else{
                        array_push($strMailWildMuls, '2~'.($bonusMulPoses[$k] - 6).'~'.$bonusMuls[$k]);
                    }
                }
                $reelSymbolCount = [0,0,0,0,0,0];
                for($k = 0; $k < 8; $k++){
                    for($j = 1; $j <= 6; $j++){
                        if($k == 0){
                            $lastReel[$j - 1] = $reels['reel'.(7 - $j)][$k];
                            if($j > 1 && $j < 6){
                                array_push($topLastReel, $reels['reel'.(7 - $j)][$k]);
                            }
                        }else{
                            $lastReel[($j - 1) + $k * 6] = $reels['reel'.$j][$k];
                            array_push($mainLastReel, $reels['reel'.$j][$k]);
                            if($reels['reel'.$j][$k] == 13 && $reelSymbolCount[$j - 1] == 0){
                                $reelSymbolCount[$j - 1] = $k - 1;
                            }
                        }
                    }
                }
                for($k = 0; $k < 6; $k++){
                    if($reelSymbolCount[$k] == 0){
                        $reelSymbolCount[$k] = 7;
                    }
                }

                if($isTumb == false){
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReelSymbolCount', $reelSymbolCount);
                }
                $strLastReel = implode(',', $lastReel);
                $strReelSa = $reels['reel1'][8].','.$reels['reel2'][8].','.$reels['reel3'][8].','.$reels['reel4'][8].','.$reels['reel5'][8].','.$reels['reel6'][8];
                $strReelSb = $reels['reel1'][-1].','.$reels['reel2'][-1].','.$reels['reel3'][-1].','.$reels['reel4'][-1].','.$reels['reel5'][-1].','.$reels['reel6'][-1];               
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'BinaryReel', $binaryReel);
                if($isNewTumb == true){
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') + 1); // 텀브스핀이 회수 증가                
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') + $totalWin);
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
                $strTopRmul = '';
                $strMainRmul = '';
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $spinType = 's';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 && $isNewTumb == false ) 
                    {
                        $isEnd = true;
                        $spinType = 'c';
                        $otherResponse = '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&fsmul_total=1&fsend_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    }
                    else
                    {
                        $otherResponse = '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    }
                    $otherResponse = $otherResponse . '&accm=cp&acci=0&accv=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusTumbMpl');
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BonusTumbMpl') > 1){
                        $otherResponse = $otherResponse . '&apv=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusTumbMpl');
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusTumbMpl', $bonusMul);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusTumbMpls', $bonusMuls);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusTumbMplPos', $bonusMulPoses);
                    if(count($strTopWildMuls) > 0){
                        $strTopRmul = ',rmul:"'.implode(';', $strTopWildMuls).'"'; // Top릴에서 와일드 위치및배당값 
                    }
                    if(count($strMailWildMuls) > 0){
                        $strMainRmul = ',rmul:"'.implode(';', $strMailWildMuls).'"'; // Main릴에서 와일드 위치및배당값
                    }
                }else
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    if($scattersCount >=4 && $isNewTumb == false){
                        $spinType = 's';
                        $otherResponse = $otherResponse . '&fs=1&fsmul=1&fsmax='.$freeSpinNum.'&fswin=0.00&fsres=0.00&psym=1~'. $scatterWin .'~' . implode(',', $_obf_scatterposes); // 프리스핀입장할때 Scatter위치
                    }
                    if($isBuyFreespin == 0){
                        $otherResponse = $otherResponse . '&purtr=1';
                    }
                }
                $strTopTmb = '';
                $strMainTmb = '';
                if($isNewTumb == true){
                    $spinType = 's';
                    $firstsymbolstr = '';
                    for($r = 6; $r < 48; $r++){
                        if($binaryReel[$r] > 0){
                            if($firstsymbolstr == ''){
                                $firstsymbolstr = $binaryReel[$r];
                                $strMainTmb = ',tmb:"'.($r - 6);
                            }else{
                                $strMainTmb = $strMainTmb . ',' . $binaryReel[$r] . '~' . ($r - 6);
                            }
                        }
                    }
                    if($strMainTmb != ''){
                        $strMainTmb = $strMainTmb . ',' . $firstsymbolstr . '"';
                    }
                    $firstTopSymbolStr = '';
                    for($r = 1; $r < 5; $r++){
                        if($binaryReel[$r] > 0){
                            if($firstTopSymbolStr == ''){
                                $firstTopSymbolStr = $binaryReel[$r];
                                $strTopTmb = ',tmb:"'.($r - 1);
                            }else{
                                $strTopTmb = $strTopTmb . ',' . $binaryReel[$r] . '~' . ($r - 1);
                            }
                        }
                    }
                    if($strTopTmb != ''){
                        $strTopTmb = $strTopTmb . ',' . $firstTopSymbolStr . '"';
                    }
                    $otherResponse = $otherResponse.'&rs=mc&tmb_win='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbWin').'&rs_p='.($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') - 1).'&rs_c=1&rs_m=1';
                }else{
                    if($isTumb == true){
                        if($slotEvent['slotEvent'] != 'freespin' && $scattersCount < 4){
                            $spinType = 'c';
                        }
                        $otherResponse = $otherResponse.'&tmb_res='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbWin').'&rs_t='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbleState').'&tmb_win='.($slotSettings->GetGameData($slotSettings->slotId . 'TumbWin'));
                        if($slotEvent['slotEvent'] == 'freespin' && $totalWin > 0){
                            $otherResponse = $otherResponse.'&apwa='.$totalWin.'&apt=tumbling_win_mul';
                        }
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', 0);
                } 
                
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                    $otherResponse = $otherResponse . '&puri=0';
                }
                if($isEnd == true){
                    $otherResponse = $otherResponse.'&w='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }else{
                    $otherResponse = $otherResponse.'&w='.$totalWin;
                }
                
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .'&balance='.$Balance.'&bl='.$isdoublechance.'&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType.$strWinLine.'&stime=' . floor(microtime(true) * 1000) .'&sh=8&c='.$betline.'&st=rect&sw=6&sver=5'.$otherResponse.'&g={reg:{reel_set:"'. ($reelSet_Num * 2) .'"'. $strMainRmul .',s:"'. implode(',', $mainLastReel) .'",sa:"'.$strReelSa.'",sb:"'.$strReelSb.'",sh:"7",st:"rect",sw:"6"'. $strMainTmb .'},top:{reel_set:"'. ($reelSet_Num * 2 + 1) .'"'. $strTopRmul .',s:"'. implode(',', $topLastReel) .'",sa:"'.rand(6, 12).'",sb:"'.rand(6, 12).'",sh:"4",st:"rect",sw:"1"'. $strTopTmb .'}}&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&s='.$strLastReel;

                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) && $isNewTumb == false) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }

                if($isNewTumb == false){
                    if( $slotEvent['slotEvent'] != 'freespin' && $scattersCount >= 4) 
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                    }
                }
                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusTumbMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusTumbMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame'). ',"TumbState":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') . ',"Balance":' . $Balance . ',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $totalWin . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .   ',"freeBalance":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance') .  ',"tumbWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') . ',"winLines":"'.$strWinLine.'","Jackpots":""' . ',"DoubleChance":'.$slotSettings->GetGameData($slotSettings->slotId . 'DoubleChance') .  ',"BuyFreeSpin":'.$slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"strTopTmb":"'.str_replace('"', '|', $strTopTmb) . '","strMainTmb":"'.str_replace('"', '|', $strMainTmb) .  '","strTopRmul":"'.str_replace('"', '|', $strTopRmul) . '","strMainRmul":"'.str_replace('"', '|', $strMainRmul) . '","LastReel":'.json_encode($lastReel) . ',"BonusTumbMpls":"'.implode(',',$slotSettings->GetGameData($slotSettings->slotId . 'BonusTumbMpls')). '","BonusTumbMplPos":"'.implode(',',$slotSettings->GetGameData($slotSettings->slotId . 'BonusTumbMplPos')). '","BinaryReel":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'BinaryReel')). ',"ReelSymbolCount":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'ReelSymbolCount')).'}}';
                
                    $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $totalWin, $slotEvent['slotEvent']);
            }
            $slotSettings->SaveGameData();
            \DB::commit();
            return $response;
        }
        public function findZokbos($reels, $firstSymbol, $repeatCount, $strLineWin){
            $wild = '2';
            $bPathEnded = true;
            if($repeatCount < 6){
                for($r = 0; $r < 8; $r++){
                    if($firstSymbol == $reels['reel'.($repeatCount + 1)][$r] || $reels['reel'.($repeatCount + 1)][$r] == $wild){
                        $this->findZokbos($reels, $firstSymbol, $repeatCount + 1, $strLineWin . '~' . ($repeatCount + $r * 6));
                        $bPathEnded = false;
                    }
                }
            }
            if($bPathEnded == true){
                if($repeatCount >= 3){
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

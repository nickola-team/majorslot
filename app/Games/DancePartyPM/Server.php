<?php 
namespace VanguardLTE\Games\DancePartyPM
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
            	$userId = 7;
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
                $slotSettings->SetGameData($slotSettings->slotId . 'IncMplValue', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [5,3,4,3,7,6,2,4,9,2,5,3,4,3,6]);
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'IncMplValue', $lastEvent->serverResponse->IncMplValue);
                    $bet = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $bet = $slotSettings->Bet[0];
                }
                $currentReelSet = 0;
                $spinType = 's';
                
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') < $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') ) 
                {
                    $currentReelSet = 1;

                    $_obf_StrResponse = '&apwa=0.00&apt=fs_inc_mul&apv='.$slotSettings->GetGameData($slotSettings->slotId . 'IncMplValue').'&fs=' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fslim=60&w=0.00&fsmul=1';
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') > 1){
                        $_obf_StrResponse = $_obf_StrResponse . '&gwm=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl');
                    }
                }else{
                    $_obf_StrResponse = '';
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                $Balance = $slotSettings->GetBalance();
                
                // $response = 'def_s=9,3,11,6,6,11,5,9,11,4,6,12,4,9,9,6,8,11,12,9,9,1,3,13&balance='. $Balance .'&cfgs=1&ver=2&index=1&balance_cash='.$Balance.'&reel_set_size=9&def_sb=3,4,7,6,8,4&def_sa=8,7,5,3,7,5&reel_set='.$currentReelSet.
                //     '&balance_bonus=0.00&na='.$spinType.'&scatters=1~0,0,0,0,0,0~100,25,15,8,0,0~1,1,1,1,1,1&gmb=0,0,0&rt=d&stime=' . floor(microtime(true) * 1000) .'&sa=8,7,5,3,7,5&sb=3,4,7,6,8,4&sc='. implode(',', $slotSettings->Bet) .'&defc='.$bet.'&sh=4&wilds=2~0,0,0,0,0,0~1,1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.$_obf_StrResponse.
                //     '&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;300,250,200,80,0,0;250,200,100,60,0,0;250,200,100,60,0,0;200,120,80,40,0,0;200,120,80,40,0,0;120,80,40,20,0,0;120,80,40,20,0,0;100,60,30,10,0,0;100,60,30,10,0,0;80,40,20,10,0,0;80,40,20,10,0,0&l=40&rtp=96.06&reel_set0=3,3,3,3,8,7,10,9,9,4,11,8,8,10,6,9,1,11,11,5,5,10,12,10,11,6,6,6,4,13,13,7,7,7,12,10,6,13,4,11,11,10,12,5,13,1,12,12,3,8,11,11,10,6,6,9,9,5,5,5,10,10,12,7,7,13,13,11,4,4,4,10,10,9,7,13,13,5,9,8,12,12~12,12,8,9,5,13,13,7,9,10,10,4,4,4,11,13,13,7,7,12,10,10,12,12,6,6,6,13,13,7,11,7,5,8,13,6,9,12,3,3,11,8,8,7,7,7,13,13,5,5,5,6,12,12,4,4,8,8,11,10,7,9,9,10,5,5,11,11,1,9,6,10,8,2,11,4,9,9,7,8,8,3,3,3,3~3,3,3,3,8,8,7,9,9,4,11,6,8,10,6,9,1,11,11,5,5,10,6,6,6,12,10,11,4,13,13,7,7,7,12,10,6,2,4,11,11,10,12,5,13,1,12,12,3,8,11,11,10,6,6,9,9,5,5,5,10,10,12,7,7,13,13,11,4,4,4,10,10,9,7,13,13,5,9,8,12,12~12,12,8,9,5,13,13,7,9,10,10,4,4,4,11,13,13,7,7,12,10,10,12,12,6,6,6,13,13,7,11,7,5,8,13,6,9,12,3,3,11,8,8,13,13,7,7,7,5,5,5,6,12,12,4,4,8,8,11,10,7,9,9,10,5,5,11,11,1,9,6,10,8,2,11,4,9,9,7,8,8,3,3,3,3~3,3,3,3,8,8,7,9,9,4,11,6,8,10,6,9,1,11,11,5,5,10,12,10,11,4,13,13,7,7,7,12,10,6,2,4,11,11,10,6,6,6,12,5,13,1,12,12,3,8,11,11,10,6,6,9,9,5,5,5,10,10,12,7,7,13,13,11,4,4,4,10,10,9,7,13,13,5,9,8,12,12~12,12,8,9,5,13,13,7,9,10,10,4,4,4,11,5,5,5,13,13,7,7,12,10,10,12,12,6,6,6,13,13,7,11,7,5,8,13,6,9,12,3,3,11,7,7,7,8,8,13,13,6,12,12,4,4,8,8,11,10,7,9,9,10,5,5,11,11,1,9,6,10,8,2,11,4,9,9,7,8,8,3,3,3,3&s='.$lastReelStr.';
                $response = 'tw=0.00&def_s=5,3,4,3,7,6,2,4,9,2,5,3,4,3,6&balance='. $Balance .'&action=doSpin&cfgs=4948&ver=2&index=1&balance_cash='. $Balance .'&reel_set_size=2&def_sb=6,5,9,7,10&def_sa=10,4,7,11,9&reel_set='.$currentReelSet.'&balance_bonus=0.00&na='.$spinType.'&scatters=1~0,0,0,0,0,0,0,0,0,0,0,0,0,0,0~0,0,0,0,0,0,0,0,15,15,15,0,0,0,0~1,1,1,1,1,1,1,1,1,1,1,1,1,1,1&gmb=0,0,0&rt=d&stime=' . floor(microtime(true) * 1000) .'&sa=8,9,6,6,8&sb=7,8,1,2,5&sc='. implode(',', $slotSettings->Bet) .'&defc=100.00&sh=3&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.$_obf_StrResponse.'&sver=5&counter=2&l=20&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;150,60,30,0,0;120,60,20,0,0;100,40,16,0,0;80,30,10,0,0;50,10,6,0,0;50,10,6,0,0;40,8,4,0,0;40,8,4,0,0&rtp=96.50&reel_set0=3,3,3,9,9,9,1,4,4,4,7,7,7,1,7,5,5,5,8,8,8,6,9,8,6,10,10,10,10,10,1,9,9,5,3,4,6,6,6~9,9,9,4,4,4,5,5,5,4,3,3,3,10,10,10,10,7,7,7,8,8,8,6,6,6,5,1,2,2,2,9,7,8,7,6,9,1,5,5,5~7,7,7,1,2,2,2,9,9,9,10,10,10,2,8,8,8,5,9,3,3,3,3,6,6,6,10,7,4,4,4,1,5,8,5,5,5~4,4,4,6,6,6,8,8,8,3,3,3,10,10,10,3,7,7,7,10,8,5,5,5,7,9,9,9,7,10,6,8,1,2,2,2,9~1,1,1,2,2,2,10,10,10,10,3,3,3,4,4,4,1,3,5,5,5,6,6,6,3,9,9,9,2,4,6,4,7,7,1,8,8,8,7,5,7,7,7&s='.$lastReelStr.'&t=243&reel_set1=6,6,6,4,4,4,9,9,9,9,8,8,8,10,10,10,9,8,8,10,7,7,7,1,10,7,3,3,3,5,5,5,4,3,7,1~6,6,6,6,4,4,4,5,5,5,6,8,10,10,10,1,2,7,7,7,4,3,3,3,4,3,1,5,9,9,9,3,8,8,8~10,10,10,9,9,9,7,7,7,6,8,8,8,3,9,1,5,8,4,3,5,2,10,6,4,7,7,9~4,4,4,9,9,9,10,10,10,7,7,7,6,6,6,6,3,3,3,2,8,8,8,1,5,5,5,5,10,3,9~7,7,7,1,10,10,10,10,2,7,9,9,9,6,6,6,9,8,8,8,4,4,4,7,3,3,3,6,5,5,5,6&w=0.00';
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
                $lines = $slotEvent['slotLines'];
                $betline = $slotEvent['slotBet'];
                if( $slotEvent['slotEvent'] == 'doSpin' || $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    if( $lines <= 0 || $betline <= 0.0001 ) 
                    {
                        $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"invalid bet state"}';
                        exit( $response );
                    }
                    if( $slotSettings->GetBalance() < ($lines * $betline) ) 
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
                $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $betline * $lines, $lines);
                $winType = $_spinSettings[0];
                $_winAvaliableMoney = $_spinSettings[1];
                if($slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    $bonusMpl = $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') + $slotSettings->GetGameData($slotSettings->slotId . 'IncMplValue');
                    if($bonusMpl > 30){
                        $bonusMpl = 30;
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $bonusMpl);
                    $leftFreeGames = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') - $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'IncMplValue', 0);
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
                    $reels = $slotSettings->GetReelStrips($winType, $slotEvent['slotEvent']);
                    for($r = 0; $r < 3; $r++){
                        if($reels['reel1'][$r] != $scatter){
                            $this->findZokbos($reels, $bonusMpl, $reels['reel1'][$r], 1, '~'.($r * 5));
                        }                        
                    }
                    for($r = 0; $r < count($this->winLines); $r++){
                        $winLine = $this->winLines[$r];
                        $winLineMoney = $slotSettings->Paytable[$winLine['FirstSymbol']][$winLine['RepeatCount']] * $betline * $bonusMpl;
                        $strWinLine = $strWinLine . '&l'. $r.'='.$r.'~'.$winLineMoney . $winLine['StrLineWin'];
                        $totalWin += $winLineMoney;
                    }                
                    $freeSpinNum = 0;

                    $_obf_scatterposes = [];
                    $scattersCount = 0;
                    for( $r = 1; $r <= 5; $r++ ) 
                    {
                        for( $k = 0; $k < 3; $k++ ) 
                        {
                            if( $reels['reel' . $r][$k] == $scatter ) 
                            {
                                $scattersCount++;
                                array_push($_obf_scatterposes, $k * 5 + $r - 1);
                            }
                        }
                    }
                    if($scattersCount >= 5){
                        $freeSpinNum = 15;
                    }
                    if( $i >= 1000 ) 
                    {
                        $winType = 'none';
                    }
                    if( $i > 1500 ) 
                    {
                        break;
                    }
                    if( $scattersCount >= 5 && ($winType != 'bonus' || $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') >= 60) ) 
                    {
                    }
                    else if( $totalWin <= $_winAvaliableMoney && $winType == 'bonus' ) 
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
                }
                $spinType = 's';
                $isEndRespin = false;
                $isEnd = false;
                if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    $isEnd = true;
                    $limitWin = $betline * $lines * 10;
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin < $limitWin){
                        $totalWin = $limitWin - $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                }
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
                    }
                    else
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $freeSpinNum);
                    }
                }
                $lastTempReel = [];
                for($k = 0; $k < 3; $k++){
                    for($j = 1; $j <= 5; $j++){
                        $lastReel[($j - 1) + $k * 5] = $reels['reel'.$j][$k];
                    }
                }
                $strLastReel = implode(',', $lastReel);
                $strReelSa = $reels['reel1'][3].','.$reels['reel2'][3].','.$reels['reel3'][3].','.$reels['reel4'][3].','.$reels['reel5'][3];
                $strReelSb = $reels['reel1'][-1].','.$reels['reel2'][-1].','.$reels['reel3'][-1].','.$reels['reel4'][-1].','.$reels['reel5'][-1];
               
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $reelSet_Num = 0;
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $spinType = 's';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    if( $isEnd == true ) 
                    {
                        $spinType = 'c&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&apwa=0.00&fsmul_total=1&apt=fs_inc_mul&apv=1&fslim=60&gwm='.$bonusMpl.'&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                    }
                    else
                    {
                        $spinType = 's&fsmul=1&apwa=0.00&apt=fs_inc_mul&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&apv='.$slotSettings->GetGameData($slotSettings->slotId . 'IncMplValue').'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fslim=60&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        if($bonusMpl > 1){
                            $spinType = $spinType . '&gwm='.$bonusMpl;
                        }
                        $reelSet_Num = 1;
                    }
                    if($freeSpinNum > 0){
                        $spinType = $spinType . '&fsmore=' . $freeSpinNum;
                    }
                    $response = 'tw='. $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&balance='.$Balance.'&index='. $slotEvent['index'] . '&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType.'&reel_set='. $reelSet_Num.
                        $strWinLine .'&stime=' . floor(microtime(true) * 1000).'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3'.
                        '&c='.$betline.'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&s='.$strLastReel.'&w='.$totalWin;
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                    $n_reel_set = $reelSet_Num;
                    if($freeSpinNum > 0 ){
                        $spinType = 's';
                        $incmuls = [1,2,3];                        
                        $slotSettings->SetGameData($slotSettings->slotId . 'IncMplValue', $incmuls[$scattersCount - 5]);
                        $n_reel_set = $n_reel_set.'&fsmul=1&apwa=0.00&apt=fs_inc_mul&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&apv='.$incmuls[$scattersCount - 5].'&fswin=0.00&fs=1&fslim=60'; 
                    }

                    $response = 'tw='.$totalWin .'&balance='.$Balance.'&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType.$strWinLine.'&stime=' . floor(microtime(true) * 1000) .
                        '&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&c='.$betline.'&sver=5&n_reel_set='.$n_reel_set.'&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&s='.$strLastReel.'&w='.$totalWin;
                }


                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines. ',"IncMplValue":' . $slotSettings->GetGameData($slotSettings->slotId . 'IncMplValue') . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $totalWin . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';
                $slotSettings->SaveLogReport($_GameLog, $betline * $lines, $lines, $_obf_totalWin, $slotEvent['slotEvent']);
                
                if( $slotEvent['slotEvent'] != 'freespin' && $scattersCount >= 3) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                }
            }
            $slotSettings->SaveGameData();
            \DB::commit();
            return $response;
        }
        public function findZokbos($reels, $mul, $firstSymbol, $repeatCount, $strLineWin){
            $wild = '2';
            $bPathEnded = true;
            if($repeatCount < 5){
                for($r = 0; $r < 3; $r++){
                    if($firstSymbol == $reels['reel'.($repeatCount + 1)][$r] || $reels['reel'.($repeatCount + 1)][$r] == $wild){
                        $this->findZokbos($reels, $mul, $firstSymbol, $repeatCount + 1, $strLineWin . '~' . ($repeatCount + $r * 5));
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

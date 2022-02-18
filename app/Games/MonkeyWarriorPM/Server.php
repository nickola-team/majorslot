<?php 
namespace VanguardLTE\Games\MonkeyWarriorPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'MoneyValue', [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());                
                $slotSettings->SetGameData($slotSettings->slotId . 'IsMoreRespin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 25);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [10,3,6,8,9,9,7,10,3,5,4,5,8,4,10]);
                $slotSettings->SetGameData($slotSettings->slotId . 'DefaultMaskMoneyCount', [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]);
                $slotSettings->SetGameData($slotSettings->slotId . 'DefaultMaskBonusType', [0,0,0,0,0,0,0,0,0,0,0,0,0,0]);                
                $slotSettings->SetGameData($slotSettings->slotId . 'DefaultMaskBoxMaxCount', [0,0,0,0,0,0,0,0,0,0,0,0,0,0]);  
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);           
                $slotSettings->SetGameData($slotSettings->slotId . 'BoxMaxCount', 0);  
                $slotSettings->SetGameData($slotSettings->slotId . 'BoxCurrentCount', 0);
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', $lastEvent->serverResponse->totalRespinGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', $lastEvent->serverResponse->currentRespinGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'MoneyValue', $lastEvent->serverResponse->MoneyValues);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);          
                    $slotSettings->SetGameData($slotSettings->slotId . 'IsMoreRespin', $lastEvent->serverResponse->IsMoreRespin);
                    if (isset($lastEvent->serverResponse->FreeStacks)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', json_decode(json_encode($lastEvent->serverResponse->FreeStacks), true)); // FreeStack
                    }
                    if (isset($lastEvent->serverResponse->RoundID)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    }
                    if (isset($lastEvent->serverResponse->BoxMaxCount)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'BoxMaxCount', $lastEvent->serverResponse->BoxMaxCount);
                    }
                    if (isset($lastEvent->serverResponse->BoxCurrentCount)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'BoxCurrentCount', $lastEvent->serverResponse->BoxCurrentCount);
                    }
                    $bet = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $bet = 100;
                }
                $currentReelSet = 0;
                $spinType = 's';
                $currentMoneyValue = $slotSettings->GetGameData($slotSettings->slotId . 'MoneyValue');
                $strCurrentMoneyValue = implode(',', $currentMoneyValue);
                $CurrentMoonText = [];
                $sum = 0;
                for($i = 0; $i < count($currentMoneyValue); $i++){
                    if($currentMoneyValue[$i] > 0){
                        $CurrentMoonText[$i] = 'v';
                        $sum = $sum + $currentMoneyValue[$i];
                    }else{
                        $CurrentMoonText[$i] = 'r';
                    }
                }
                $strMoonText = implode(',', $CurrentMoonText);
                $_obf_StrResponse = '';
                if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') < $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') || ($slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') >= $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') && $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') > 0 && $slotSettings->GetGameData($slotSettings->slotId . 'IsMoreRespin') == 1)){
                    $currentReelSet = 0;
                    $spinType = 'b';
                    
                    $_obf_StrResponse = '&rsb_s=11,12&bgid=0&rsb_m='.$slotSettings->GetGameData($slotSettings->slotId . 'RespinGames').'&rsb_c='.$slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame').'&bgt=37&end=0&bpw=' . $sum * $bet.'&rsb_more=0';
                    if($slotSettings->GetGameData($slotSettings->slotId . 'IsMoreRespin') == 1){
                        $_obf_StrResponse = $_obf_StrResponse . '&wins=0,0,0&status=0,0,0&wins_mask=h,h,h';
                    }
                }else if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') < $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') ) 
                {
                    $_obf_StrResponse = '&fs=' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&tw=' .$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&w=0.00&fsmul=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . '';
                    $currentReelSet = 1;
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                $Balance = $slotSettings->GetBalance();
                
                $response = 'def_s=10,3,6,8,9,9,7,10,3,5,4,5,8,4,10&balance='. $Balance .'&cfgs=2998&ver=2&mo_s=11&index=1&balance_cash='. $Balance .'&reel_set_size=2&def_sb=5,9,3,10,3&mo_v=25,50,75,100,125,150,175,200,250,350,400,450,500,600,750,1250,2500,5000&def_sa=4,9,7,9,10&mo_jp=750;1250;2500;5000&balance_bonus=0.00&na='. $spinType.'&scatters=1~0,0,2,0,0~8,8,8,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&mo_jp_mask=jp4;jp3;jp2;jp1&stime=' . floor(microtime(true) * 1000) .'&sa=4,9,7,9,10&sb=5,9,3,10,3&sc='. implode(',', $slotSettings->Bet) .'&defc=100.00&sh=3&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&n_reel_set='.$currentReelSet.$_obf_StrResponse.'&mo='.$strCurrentMoneyValue.'&mo_t='.$strMoonText.'&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;500,50,25,0,0;300,40,25,0,0;200,35,20,0,0;150,30,20,0,0;50,20,10,0,0;50,20,10,0,0;50,15,10,0,0;50,15,10,0,0;0,0,0,0,0;0,0,0,0,0&l=25&rtp=95.50&reel_set0=6,4,10,9,4,5,8,7,6,9,7,11,10,11,11,9,10,6,9,5,3,10,7,4,10,5,8,6,10,9~2,9,3,7,5,9,6,5,8,9,1,10,8,5,7,4,8,11,11,11,5,9,6,10,8,1,9,7,5,8,6,9,10~2,7,6,10,8,3,7,9,1,10,4,8,6,9,11,11,11,8,6,3,9,5,8,1,10,8,3,7,9,4~2,9,8,3,4,10,8,4,9,7,1,10,1,7,4,9,7,11,11,11,10,9,6,7,4,5,8,1,9,3,10,4,8,7,3,9,7,5,10,9,3,7,4,10,6,4,7,8~2,10,9,5,10,3,9,5,8,3,4,7,5,4,10,8,5,10,4,6,7,3,8,10,11,11,11,8,4,9&s='.$lastReelStr.'&t=243&reel_set1=3,5,11,11,5,5,4,4,5,5,6,6,4,4,5,5,6,6,11,11,6,6,4,4,5,5,6,6,6,4~5,6,3,1,5,6,4,5,1,5,5,11,11,6,6,5,5,5,1,6,3,3,4,11,11,11,5,6,6,6,6,5,5,6~6,6,3,1,3,3,4,4,4,3,3,4,4,3,11,11,3,6,6,6,5,3,3,5,11,11,11,3,3,6,6,6,6~3,4,3,4,4,4,4,1,5,3,3,3,11,11,11,4,4,1,3,4,4,3,3,4,4,3,3,6,6,1,6,5~4,3,3,4,4,5,5,3,3,11,11,11,3,3,4,4,5,5,4,4,3,3,4,4,5,5,6,6,11,11,4,4';
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
                $slotEvent['slotLines'] = 25;
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
                $isGeneratedFreeStack = false;
                $freeStacks = []; // free stacks
                $isForceWin = false;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    $bonusMpl = $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl');
                    $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                    if(count($freeStacks) >= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames')){
                        $isGeneratedFreeStack = true;
                    }
                    $leftFreeGames = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') - $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame'); 
                    if($leftFreeGames <= mt_rand(0 , 1) && $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') == 0){
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '275' . substr($roundstr, 4, 7);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'RegularSpinCount') + 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BoxMaxCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BoxCurrentCount', 0);
                    $leftFreeGames = 0;
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'IsMoreRespin', 0);
                $Balance = $slotSettings->GetBalance();
                if( $slotEvent['slotEvent'] == 'bet' ) 
                {
                    $slotSettings->UpdateJackpots($betline * $lines);
                }
                $initMoneyCounts = [];
                $defaultMoneyCount = 0;
                $isrespin = false;
                if($winType == 'bonus'){
                    if($slotSettings->GetBonusType() == 2 && $slotEvent['slotEvent'] != 'freespin'){                        
                        $initMoneyCounts = $slotSettings->GetMoneyCount();
                        $isrespin = true;
                        for($i = 0; $i < count($initMoneyCounts); $i++){
                            $defaultMoneyCount = $defaultMoneyCount + $initMoneyCounts[$i];
                        }
                    }
                }
                for( $i = 0; $i <= 2000; $i++ ) 
                {
                    $_moneyValue = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                    $totalWin = 0;
                    $wild = '2';
                    $scatter = '1';
                    $moneysymbol = '11';
                    $this->winLines = [];
                    $_obf_winCount = 0;
                    $strWinLine = '';
                    $winLineMuls = [];
                    $winLineMulNums = [];
                    if($isGeneratedFreeStack == true){
                        //freestack
                        $freeStack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') - 2];
                        $reels = $freeStack['Reel'];
                    }else{
                        $reels = $slotSettings->GetReelStrips($winType, $slotEvent['slotEvent'], $isrespin, $initMoneyCounts);
                    }
                    for($r = 0; $r < 3; $r++){
                        if($reels['reel1'][$r] != $scatter){
                            $this->findZokbos($reels, $reels['reel1'][$r], 1, '~'.($r * 5));
                        }                        
                    }
                    $fiveSymbol = 0;
                    for($r = 0; $r < count($this->winLines); $r++){
                        $winLine = $this->winLines[$r];
                        $winLineMoney = $slotSettings->Paytable[$winLine['FirstSymbol']][$winLine['RepeatCount']] * $betline * $bonusMpl;
                        if($winLineMoney > 0){
                            $strWinLine = $strWinLine . '&l'. $r.'='.$r.'~'.$winLineMoney . $winLine['StrLineWin'];
                            $totalWin += $winLineMoney;
                        }
                        if($winLine['RepeatCount'] == 5 && $winLine['FirstSymbol'] <= 5 && $fiveSymbol == 0){
                            $fiveSymbol = $winLine['FirstSymbol'];
                        }
                    }      
                    
                    $_obf_scatterposes = [];
                    $scattersCount = 0;
                    $moneyCount = 0;
                    $scattersWin = 0;
                    $moneyTotalWin = 0;
                    $moneyChangedWin = false;
                    $_obf_0D33120B1B18292D30293B191C3D383E3D2D0C195B2101 = '';
                    for( $r = 1; $r <= 5; $r++ ) 
                    {
                        for( $k = 0; $k <= 2; $k++ ) 
                        {
                            if( $reels['reel' . $r][$k] == $scatter ) 
                            {
                                $scattersCount++;
                                array_push($_obf_scatterposes, $k * 5 + $r - 1);
                            }
                            if( $reels['reel' . $r][$k] == $moneysymbol ) 
                            {
                                $moneyCount++;
                            }
                        }
                    }
                    if($scattersCount >= 3){
                        $scattersWin = $betline * $lines * 2;
                    }
                    $totalWin = $totalWin + $scattersWin;
                    for($r = 0; $r <= 2; $r++){
                        for( $k = 0; $k < 5; $k++ ) 
                        {
                            if( $reels['reel' . ($k+1)][$r] == $moneysymbol) 
                            {
                                if($_moneyValue[$r * 5 + $k] == 0){
                                    $_moneyValue[$r * 5 + $k] = $slotSettings->GetMoneyWin();
                                    $moneyChangedWin = true;
                                }
                                $moneyTotalWin = $moneyTotalWin + $_moneyValue[$r * 5 + $k] * $betline;
                            }
                        }
                    }
                    if($moneyCount < 6){
                        $moneyTotalWin = 0;
                    }
                    if( $i > 1000 ) 
                    {
                        $winType = 'none';
                    }
                    if( $slotSettings->increaseRTP && $winType == 'win' && $totalWin < ($lines * $betline * rand(2, 5)) ) 
                    {
                    }
                    else if( !$slotSettings->increaseRTP && $winType == 'win' && $lines * $betline < $totalWin ) 
                    {
                    }
                    else
                    {
                        if( $i > 1500 ) 
                        {
                            $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"Bad Reel Strip"}';
                            exit( $response );
                        }
                        if( $scattersCount >= 3 && $winType != 'bonus' ) 
                        {
                        }else if( $moneyCount >= 6 && ($winType != 'bonus' || $moneyCount != $defaultMoneyCount )) 
                        {
                        }else if( $moneyCount >= 6 && $scattersCount >= 3 ) 
                        {
                        }
                        else if($isGeneratedFreeStack == true){
                            break;  //freestack
                        }
                        else if($isForceWin == true && $totalWin > 0 && $totalWin < $betline * $lines * 20){
                            break;   // win by force when winmoney is 0 in freespin
                        }
                        else if($winType == 'bonus' && $scattersCount >= 3 && $slotSettings->GetGameData($slotSettings->slotId . 'RegularSpinCount') > 450){
                            break;  // give freespin per 450spins over
                        }
                        else if($fiveSymbol > 0 && $fiveSymbol <= 5 && mt_rand(0, 100) < 90){
                            $test_str = '';
                        }
                        else if( $moneyTotalWin + $totalWin <= $_winAvaliableMoney && $winType == 'bonus' ) 
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
                        else if( $moneyTotalWin + $totalWin > 0 && $moneyTotalWin + $totalWin <= $_winAvaliableMoney && $winType == 'win' ) 
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
                if( $scattersCount >= 3 ) 
                {
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0 ) 
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->slotFreeCount);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                        // FreeStack
                        if($slotSettings->IsAvailableFreeStack() || $slotSettings->happyhouruser){
    
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $slotSettings->GetFreeStack($betline, 0));
                        }
                    }
                    else
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $slotSettings->slotFreeCount);
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                }
                if( $moneyCount >= 6 && $slotEvent['slotEvent'] != 'respin') 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', $slotSettings->slotRespinCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BoxMaxCount', $slotSettings->GetBoxMaxCount());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BoxCurrentCount', 0);
                }
                
                for($k = 0; $k < 3; $k++){
                    for($j = 1; $j <= 5; $j++){
                        $lastReel[($j - 1) + $k * 5] = $reels['reel'.$j][$k];
                    }
                }
                $strLastReel = implode(',', $lastReel);
                $strReelSa = $reels['reel1'][3].','.$reels['reel2'][3].','.$reels['reel3'][3].','.$reels['reel4'][3].','.$reels['reel5'][3];
                $strReelSb = $reels['reel1'][-1].','.$reels['reel2'][-1].','.$reels['reel3'][-1].','.$reels['reel4'][-1].','.$reels['reel5'][-1];
                $strCurrentMoneyValue = implode(',', $_moneyValue);
                $CurrentMoonText = [];
                for($i = 0; $i < count($_moneyValue); $i++){
                    if($_moneyValue[$i] > 0){
                        $CurrentMoonText[$i] = 'v';
                    }else{
                        $CurrentMoonText[$i] = 'r';
                    }
                }
                $strMoonText = implode(',', $CurrentMoonText);
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'MoneyValue', $_moneyValue);
                $strOtherResponse = "";
                $isState = true;
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $spinType = 's';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                    {
                        $spinType = 'c';
                        $strOtherResponse = '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin').'&n_reel_set=0&w='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    }
                    else
                    {
                        $isState = false;
                        $strOtherResponse = '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin').'&n_reel_set=1&w='.$totalWin;
                    }
                    // $response = 'tw='. $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .'&balance='.$Balance.'&mo='.$strCurrentMoneyValue.'&mo_t='.$strMoonText. '&index='. $slotEvent['index'] . '&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType.
                    //     $strWinLine .'&stime=' . floor(microtime(true) * 1000).'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3'.
                    //     '&c='.$betline.'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=25&s='.$strLastReel.'&w='.$totalWin.'';
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                    $n_reel_set = '';
                    if($scattersCount >=3 ){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse = '&n_reel_set=1&fsmul=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&w='.$totalWin . '&fswin=0.00&fs=1&fsres=0.00&psym=1~'.$scattersWin.'~'.implode(',', $_obf_scatterposes);
                    }else{
                        $strOtherResponse = '&n_reel_set=0&w='.$totalWin;
                    }
                }
                if($moneyCount >= 6){
                    $isState = false;
                    $spinType = 'b';
                    $strOtherResponse = $strOtherResponse . '&rsb_s=11,12&bgid=0&rsb_m='.$slotSettings->GetGameData($slotSettings->slotId . 'RespinGames').'&rsb_c='.$slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame').'&rsb_more=0&bgt=37&bw=1&bpw='.$moneyTotalWin.'';
                }
                if($moneyCount > 0){
                    $strOtherResponse = $strOtherResponse . '&mo='.$strCurrentMoneyValue.'&mo_t='.$strMoonText;
                }

                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin').'&balance='.$Balance.'&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType.$strWinLine.'&stime=' . floor(microtime(true) * 1000) .
                '&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&c='.$betline.'&sver=5'.$strOtherResponse.'&counter='. ((int)$slotEvent['counter'] + 1) .'&l=25&s='.$strLastReel;

                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)) 
                {
                    if($moneyCount < 6){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                        // $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    }
                }
                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"totalRespinGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') . ',"currentRespinGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') . ',"Balance":' . $Balance . ',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $totalWin . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')  . ',"IsMoreRespin":' . $slotSettings->GetGameData($slotSettings->slotId . 'IsMoreRespin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID').',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"BoxMaxCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'BoxMaxCount') . ',"BoxCurrentCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'BoxCurrentCount') . ',"winLines":[],"Jackpots":""' . ',"MoneyValues":'.json_encode($_moneyValue).',"LastReel":'.json_encode($lastReel).'}}';
                $slotSettings->SaveLogReport($_GameLog, $betline * $lines, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin'), $slotEvent['slotEvent'], $isState);
                if( ($scattersCount >= 3 || $moneyCount >= 6) && $slotEvent['slotEvent']!='freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                }
            }
            else if( $slotEvent['slotEvent'] == 'doBonus' ){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lines = 25;
                $moneysymbol = '11';
                $isFreeSpin = false;
                $isMoreRespin = false;
                if($slotSettings->GetGameData($slotSettings->slotId . 'IsMoreRespin') == 1){
                    $isMoreRespin = true;
                    $slotSettings->SetGameData($slotSettings->slotId . 'IsMoreRespin', 0);
                }
                if(( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') < $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') && $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') > 0 ) || $isMoreRespin == true) 
                {
                    $slotEvent['slotEvent'] = 'respin';
                }
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    $isFreeSpin = true;
                }

                $bonusMpl = $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl');
                $Balance = $slotSettings->GetBalance();
                // $_obf_winType = rand(1, $slotSettings->GetGambleSettings());
                $_obf_winType = rand(0, 1);
                $moreRespin = 0;
                $status = [0,0,0];
                $wins = [0,0,0];
                $wins_mask = ['h', 'h', 'h'];
                if($isMoreRespin == true){
                    $_obf_winType = 0;
                    $moreRespin = $slotSettings->GetMoreRespin();
                    if(isset($slotEvent['ind'])){
                        $selectedItem = $slotEvent['ind'];
                    }else{
                        $selectedItem = 0;
                    }
                    $status[$selectedItem] = 1;
                    $wins[$selectedItem] = $moreRespin;
                    $wins_mask[$selectedItem] = 'rs';
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') + $moreRespin);
                }else{                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') + 1);
                }
                for($i = 0; $i < 2000; $i++){
                    $moneyTotalWin = 0;
                    $moneyChangedWin = false;
                    $moneyCount = 0;
                    $lastReel = $slotSettings->GetGameData($slotSettings->slotId . 'LastReel');
                    $_moneyValue = $slotSettings->GetGameData($slotSettings->slotId . 'MoneyValue');
                    for($k = 0; $k < count($lastReel); $k++){
                        if($lastReel[$k] != $moneysymbol){
                            if(rand(0, 100) < $slotSettings->base_money_chance && $_obf_winType == 1){
                                $lastReel[$k] = $moneysymbol;
                            }
                        }
                        if($_moneyValue[$k] == 0 && $lastReel[$k] == $moneysymbol){
                            $_moneyValue[$k] = $slotSettings->GetMoneyWin();
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
                    else if( $slotSettings->GetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : '')) > $moneyTotalWin && $moneyCount < 13 ) 
                    {
                        break;
                    }
                    else if($i > 500){
                        $_obf_winType = 0;
                    }
                }
                if( $isMoreRespin == false && $slotSettings->GetGameData($slotSettings->slotId . 'BoxCurrentCount') < $slotSettings->GetGameData($slotSettings->slotId . 'BoxMaxCount') && $slotSettings->IsBox() == true){
                    $slotSettings->SetGameData($slotSettings->slotId . 'BoxCurrentCount', $slotSettings->GetGameData($slotSettings->slotId . 'BoxCurrentCount') + 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'IsMoreRespin', 1);
                }
                
                $isEndRespin = false;
                $totalWin = 0;
                if($moneyCount==15 || ($slotSettings->GetGameData($slotSettings->slotId . 'RespinGames')<= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') && $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') > 0 && $slotSettings->GetGameData($slotSettings->slotId . 'IsMoreRespin') == 0)){
                    $isEndRespin = true;
                    $totalWin = $moneyTotalWin;
                    $moneyTotalWin = 0;
                }
                $strLastReel = implode(',', $lastReel);
                $strCurrentMoneyValue = implode(',', $_moneyValue);
                $CurrentMoonText = [];
                for($i = 0; $i < count($_moneyValue); $i++){
                    if($_moneyValue[$i] > 0){
                        $CurrentMoonText[$i] = 'v';
                    }else{
                        $CurrentMoonText[$i] = 'r';
                    }
                }
                $strMoonText = implode(',', $CurrentMoonText);

                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'MoneyValue', $_moneyValue);
                $spinType = 'b';
                $strOtherResponse = '';
                $isState = false;
                if($isEndRespin == true){
                    if($isFreeSpin == true){
                        $spinType = 's';
                        $moneyTotalWin = 0;
                    }else if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)) {
                        $spinType = 's';
                        $moneyTotalWin = 0;
                    }else{
                        $spinType = 'cb';
                        $isState = true;
                    }
                    $strOtherResponse = '&tw='. $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .'&rw='.$totalWin.'&end=1';
                }else{
                    $strOtherResponse = '&end=0';
                }
                if($isMoreRespin == true || $slotSettings->GetGameData($slotSettings->slotId . 'IsMoreRespin') == 1){
                    $strOtherResponse = $strOtherResponse . '&wins='. implode(',', $wins) .'&status='. implode(',', $status) .'&wins_mask='. implode(',', $wins_mask);
                }
                $response = 'rsb_s=11,12&bgid=0&rsb_m='.$slotSettings->GetGameData($slotSettings->slotId . 'RespinGames').'&balance='.$Balance.'&rsb_c='.$slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame').'&mo='.$strCurrentMoneyValue.'&mo_t='.$strMoonText.'&index='. $slotEvent['index'] . '&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType.'&rsb_more='.$moreRespin.'&stime=' . floor(microtime(true) * 1000) .'&bgt=37'.$strOtherResponse.'&sver=5&bpw='.$moneyTotalWin.'&counter='. ((int)$slotEvent['counter'] + 1) .'&s='.$strLastReel.'';
                    
                if($isEndRespin == true) 
                {
                    if( $totalWin > 0) 
                    {
                        $slotSettings->SetBalance($totalWin);
                        $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                    }
                    if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) || $isFreeSpin == false) 
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                        // $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    }else if($isFreeSpin == true){
                        $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 0);
                    }
                }
                

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"totalRespinGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') . ',"currentRespinGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') . ',"Balance":' . $Balance . ',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $totalWin . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')   . ',"IsMoreRespin":' . $slotSettings->GetGameData($slotSettings->slotId . 'IsMoreRespin'). ',"winLines":[],"Jackpots":""' . 
                    ',"MoneyValues":'.json_encode($_moneyValue) . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID').',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"BoxMaxCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'BoxMaxCount') . ',"BoxCurrentCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'BoxCurrentCount') .',"LastReel":'.json_encode($lastReel).'}}';
                $slotSettings->SaveLogReport($_GameLog, $betline * $lines, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin'), $slotEvent['slotEvent'], $isState);
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

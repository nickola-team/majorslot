<?php 
namespace VanguardLTE\Games\HottoBurnHoldandSpinPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'MoneyValue', [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());                
                $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->SetGameData($slotSettings->slotId . 'WheelLevel', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWheelValue', 0);                    
                $slotSettings->SetGameData($slotSettings->slotId . 'NewWheelValue', [0]);
                $slotSettings->SetGameData($slotSettings->slotId . 'OldWheelValue', [0]);
                $slotSettings->SetGameData($slotSettings->slotId . 'WheelIndex', -1);     
                $slotSettings->SetGameData($slotSettings->slotId . 'FinalMoneyCount', 12);     
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', [9,10,3,7,5,7,10,6,3,9,5,4,6,9,7]);
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'WildMaskCounts', [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]);
                $slotSettings->SetGameData($slotSettings->slotId . 'MoneyMaskIndexes', [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]);
                $slotSettings->SetGameData($slotSettings->slotId . 'MoneyIndexes', [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,2]); 
                $slotSettings->SetGameData($slotSettings->slotId . 'DefaultMaskMoneyCount', [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]);    
                $slotSettings->SetGameData($slotSettings->slotId . 'MoneyLoopCount', 0);    
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', $lastEvent->serverResponse->totalRespinGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', $lastEvent->serverResponse->currentRespinGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'MoneyValue', $lastEvent->serverResponse->MoneyValues);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);          
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', $lastEvent->serverResponse->Bgt);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    if($lastEvent->serverResponse->Bgt == 50){
                        $slotSettings->SetGameData($slotSettings->slotId . 'WheelLevel', $lastEvent->serverResponse->WheelLevel);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWheelValue', $lastEvent->serverResponse->TotalWheelValue);                    
                        $slotSettings->SetGameData($slotSettings->slotId . 'NewWheelValue', $lastEvent->serverResponse->NewWheelValue);
                        $slotSettings->SetGameData($slotSettings->slotId . 'OldWheelValue', $lastEvent->serverResponse->OldWheelValue);
                        $slotSettings->SetGameData($slotSettings->slotId . 'WheelIndex', $lastEvent->serverResponse->WheelIndex);     
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
                $CurrentMoneyText = [];
                $sum = 0;
                $moneyPoses = [];
                for($i = 0; $i < count($currentMoneyValue); $i++){
                    if($currentMoneyValue[$i] > 0){
                        array_push($moneyPoses, $i);
                        $CurrentMoneyText[$i] = 'v';
                        $sum = $sum + $currentMoneyValue[$i];
                    }else{
                        $CurrentMoneyText[$i] = 'r';
                    }
                }
                $strMoneyText = implode(',', $CurrentMoneyText);
                $_obf_StrResponse = '';
                if($slotSettings->GetGameData($slotSettings->slotId . 'Bgt') == 50){
                    $spinType = 'b';
                    $newWheelValue = $slotSettings->GetGameData($slotSettings->slotId . 'NewWheelValue');
                    $new_wheel_txtes = [];
                    for($i = 0; $i < count($newWheelValue); $i++){
                        if($newWheelValue[$i] > 0){
                            array_push($new_wheel_txtes, 'w');
                        }else{
                            array_push($new_wheel_txtes, 'go');
                        }
                    }
                    $wheel_win = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWheelValue') * $bet * $slotSettings->GetGameData($slotSettings->slotId . 'Lines');
                    $_obf_StrResponse = '&tw='.($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') - $wheel_win).'&pw=0&mo_tv='.$sum.'&bmw=0.00&bgid=0&mo_c=1&bgt=50&lifes=1&bw=1&wp='. $slotSettings->GetGameData($slotSettings->slotId . 'TotalWheelValue') .'&rw='. $wheel_win . '&end=0&g={nwi:{whm:"'. implode(',', $new_wheel_txtes) .'",whw:"'. implode(',', $newWheelValue) .'"}}';
                    if($slotSettings->GetGameData($slotSettings->slotId . 'WheelIndex') >= 0){
                        $oldWheelValue = $slotSettings->GetGameData($slotSettings->slotId . 'OldWheelValue');
                        $old_wheel_txtes = [];
                        for($i = 0; $i < count($oldWheelValue); $i++){
                            if($oldWheelValue[$i] > 0){
                                array_push($old_wheel_txtes, 'w');
                            }else{
                                array_push($old_wheel_txtes, 'go');
                            }
                        }
                        $_obf_StrResponse = $_obf_StrResponse . '&whi='. $slotSettings->GetGameData($slotSettings->slotId . 'WheelIndex') .'&whm=' . implode(',', $old_wheel_txtes) . '&whw=' . implode(',', $oldWheelValue);
                    }
                }else if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') < $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') && $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') > 0){
                    $currentReelSet = 0;
                    $spinType = 'b';
                    
                    $_obf_StrResponse = '&bgid=1&lifes='.($slotSettings->GetGameData($slotSettings->slotId . 'RespinGames')-$slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame')).'&bgt='.$slotSettings->GetGameData($slotSettings->slotId . 'Bgt').'&end=0&rw='.($sum * $bet).'&bpw=' . ($sum * $bet) .'&wp=' . $sum;
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                $Balance = $slotSettings->GetBalance();
                
                // $response = 'def_s=10,3,6,8,9,9,7,10,3,5,4,5,8,4,10&balance='. $Balance .'&cfgs=2998&ver=2&mo_s=11&index=1&balance_cash='. $Balance .'&reel_set_size=2&def_sb=5,9,3,10,3&mo_v=25,50,75,100,125,150,175,200,250,350,400,450,500,600,750,1250,2500,5000&def_sa=4,9,7,9,10&mo_jp=750;1250;2500;5000&balance_bonus=0.00&na='. $spinType.'&scatters=1~0,0,2,0,0~8,8,8,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&mo_jp_mask=jp4;jp3;jp2;jp1&stime=' . floor(microtime(true) * 1000) .'&sa=4,9,7,9,10&sb=5,9,3,10,3&sc='. implode(',', $slotSettings->Bet) .'&defc=100.00&sh=3&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&n_reel_set='.$currentReelSet.$_obf_StrResponse.'&mo='.$strCurrentMoneyValue.'&mo_t='.$strMoneyText.'&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;500,50,25,0,0;300,40,25,0,0;200,35,20,0,0;150,30,20,0,0;50,20,10,0,0;50,20,10,0,0;50,15,10,0,0;50,15,10,0,0;0,0,0,0,0;0,0,0,0,0&l=25&rtp=95.50&reel_set0=6,4,10,9,4,5,8,7,6,9,7,11,10,11,11,9,10,6,9,5,3,10,7,4,10,5,8,6,10,9~2,9,3,7,5,9,6,5,8,9,1,10,8,5,7,4,8,11,11,11,5,9,6,10,8,1,9,7,5,8,6,9,10~2,7,6,10,8,3,7,9,1,10,4,8,6,9,11,11,11,8,6,3,9,5,8,1,10,8,3,7,9,4~2,9,8,3,4,10,8,4,9,7,1,10,1,7,4,9,7,11,11,11,10,9,6,7,4,5,8,1,9,3,10,4,8,7,3,9,7,5,10,9,3,7,4,10,6,4,7,8~2,10,9,5,10,3,9,5,8,3,4,7,5,4,10,8,5,10,4,6,7,3,8,10,11,11,11,8,4,9&s='.$lastReelStr.'&t=243&reel_set1=3,5,11,11,5,5,4,4,5,5,6,6,4,4,5,5,6,6,11,11,6,6,4,4,5,5,6,6,6,4~5,6,3,1,5,6,4,5,1,5,5,11,11,6,6,5,5,5,1,6,3,3,4,11,11,11,5,6,6,6,6,5,5,6~6,6,3,1,3,3,4,4,4,3,3,4,4,3,11,11,3,6,6,6,5,3,3,5,11,11,11,3,3,6,6,6,6~3,4,3,4,4,4,4,1,5,3,3,3,11,11,11,4,4,1,3,4,4,3,3,4,4,3,3,6,6,1,6,5~4,3,3,4,4,5,5,3,3,11,11,11,3,3,4,4,5,5,4,4,3,3,4,4,5,5,6,6,11,11,4,4';
                $response = 'def_s=9,10,3,7,5,7,10,6,3,9,5,4,6,9,7&balance='. $Balance .'&cfgs=1&ver=2&mo_s=11&index=1&balance_cash='. $Balance .'&def_sb=8,7,6,10,10&mo_v=20,40,60,80,100,120,140,160,180,200,220,240,260,280,300,320,340,360,380,400,2000,4000,6000,8000,10000,20000&reel_set_size=2&def_sa=8,10,3,10,5&reel_set='.$currentReelSet.$_obf_StrResponse.'&mo='.$strCurrentMoneyValue.'&mo_t='.$strMoneyText.'&bonusInit=[{bgid:1,bgt:51,mo_s:"13,13,13,13,13,13,13,13,13,14,14,14,14,14,14,14,14,14,14,14,15,15,15,15,15,15",mo_v:"20,40,60,80,100,120,140,160,180,200,220,240,260,280,300,320,340,360,380,400,2000,4000,6000,8000,10000,20000"}]&balance_bonus=0.00&na='. $spinType.'&scatters=1~100,20,1,0,0~0,0,0,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"2000000",max_rnd_win:"20000"}}&wl_i=tbm~20000&stime=' . floor(microtime(true) * 1000) .'&sa=8,10,3,10,5&sb=8,7,6,10,10&sc='. implode(',', $slotSettings->Bet) .'&defc=0.10&sh=3&wilds=2~5000,1000,100,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;5000,1000,100,0,0;500,200,50,0,0;200,50,20,0,0;200,50,20,0,0;200,50,20,0,0;50,10,2,0,0;50,10,2,1,0;50,10,2,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0&l=20&rtp=96.70&reel_set0=6,9,9,9,9,8,7,7,7,10,3,10,10,10,5,11,7,1,3,3,3,4,6,6,6,8,8,8,11,11,11,8,11,9,4,9,10,3,10,3,9,10,3,1,3,5,3,4,7,4,3,9,4,9,3,10,11,9,3,9,3,7,8,9,3,4,10,4,11,4,3,8,4,9,4,7,3,10,3,9,10,11,4,3,8,3,8,3,10,4,9,3,4,3,1,9,3,10,9,3,10,3,10,7,9,8,10,8,9,3,10,9,8,3,7,10,7,9~10,7,7,7,7,6,8,11,11,11,9,5,5,5,3,1,10,10,10,11,8,8,8,5,4,9,9,9,5,7,5,4,9,3,8,3,9,5,11,3,9,4,7,8,11,6,9,8,3,7,5,6,11,7,9,11,7,11,9,5,7,9,6,7,4,3,9,3,5,3,8,3,11,6,5,8,11,6,7,9,11,8,11,9,5,11,3,9,11,8,7,5,4,7,3,8,9,11,4,11,8,9,8,5,9,5,3,4,11,3,9,11,5,11,8,7,5,3,11,3,9,5,8,9,11,9,5,11,8,11~8,8,8,4,8,10,9,4,4,4,11,6,6,6,6,7,10,10,10,1,7,7,7,3,5,9,9,9,3,3,3,11,11,11,4,9~7,9,8,7,7,7,3,5,4,9,9,9,1,5,5,5,11,6,3,3,3,10,8,8,8,10,10,10,11,11,11,10,9,10,5,9,3,4,9,10,11,9,11,4,11,5,10,9,11,3,5,4,9,5,10,9,3,10,9,5,1,10,9,11,9,5,10,5,3,9,3,11,4,3,10,1,8,11,9,5,8,5,11,9,10,8,9,10,11,10,3,4,11,9,10,5,9,8,10,11,1,3,10,9,3,8,9,11,10,9,11,4,5~5,8,6,10,10,10,11,5,5,5,4,9,11,11,11,7,3,4,4,4,10,1,8,8,8,6,6,6,10,11,10,4,1,6,10,4,8,10,6,4,10,6,7,11,10,4&s='.$lastReelStr.'&reel_set1=6,6,6,1,5,3,9,8,7,4,6,3,5,10,4,9,7,9,5,4~9,3,10,10,10,6,8,1,6,4,8,8,8,10,7,5,6,3,9,8,10,8,7,10,1,5,3,10,3,10~6,6,6,1,8,8,8,8,4,7,10,9,5,10,9,6,7,10,3,8,9,7,3,8,5,6,8,9,8,5,8,9,4,8,1,9,3,5,8,1,9,4,9,10,5,8,7,5,8~10,5,9,6,8,8,8,8,5,1,3,8,9,7,10,4,8,1,8,3,5,8,4,6~10,3,7,5,1,8,3,9,8,8,8,5,4,6,10,7,1,6,8,3,5,8,5,8,6,4,5,1,8,1,3,4,3,6,8,1,3,8,6,4,1,7,1,9,5,4,8,6,3,4';
            }
            else if( $slotEvent['slotEvent'] == 'doCollect' || $slotEvent['slotEvent'] == 'doCollectBonus') 
            {
                $Balance = $slotSettings->GetBalance();
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);    
                $response = 'balance=' . $Balance . '&index=' . $slotEvent['index'] . '&balance_cash=' . $Balance . '&balance_bonus=0.00&na=s&stime=' . floor(microtime(true) * 1000) . '&na=s&sver=5&counter=' . ((int)$slotEvent['counter'] + 1);
            }
            else if( $slotEvent['slotEvent'] == 'doSpin' ) 
            {
                
                $lastEvent = $slotSettings->GetHistory();
                $slotEvent['slotBet'] = $slotEvent['c'];
                $slotEvent['slotLines'] = 20;
                $linesId = [];
                $linesId[0] = [2,2,2,2,2];
                $linesId[1] = [1,1,1,1,1];
                $linesId[2] = [3,3,3,3,3];
                $linesId[3] = [1,2,3,2,1];
                $linesId[4] = [3,2,1,2,3];
                $linesId[5] = [1,1,2,3,3];
                $linesId[6] = [3,3,2,1,1];
                $linesId[7] = [2,1,1,1,2];
                $linesId[8] = [2,3,3,3,2];
                $linesId[9] = [1,2,2,2,1];
                $linesId[10] = [3,2,2,2,3];
                $linesId[11] = [2,1,2,3,2];
                $linesId[12] = [2,3,2,1,2];
                $linesId[13] = [1,3,1,3,1];
                $linesId[14] = [3,1,3,1,3];
                $linesId[15] = [1,2,1,2,1];
                $linesId[16] = [3,2,3,2,3];
                $linesId[17] = [1,3,3,3,3];
                $linesId[18] = [3,1,1,1,1];
                $linesId[19] = [2,2,1,2,2];
                
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'MoneyValue', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'WheelLevel', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWheelValue', 0);                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'NewWheelValue', [0]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'OldWheelValue', [0]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'WheelIndex', -1); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FinalMoneyCount', 12);       
                    
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '275' . substr($roundstr, 4, 7);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);    // Round ID Generation
                }
                $Balance = $slotSettings->GetBalance();
                if( $slotEvent['slotEvent'] == 'bet' ) 
                {
                    $slotSettings->UpdateJackpots($betline * $lines);
                }
                $isWild = false;
                if(mt_rand(0, 100) < 2 && $winType == 'win'){
                    $isWild = true;
                }

                $initMoneyCounts = [];
                $defaultMoneyCount = 0;
                if($winType == 'bonus'){
                    $initMoneyCounts = $slotSettings->GetMoneyCount();
                    for($i = 0; $i < count($initMoneyCounts); $i++){
                        $defaultMoneyCount = $defaultMoneyCount + $initMoneyCounts[$i];
                    }
                }
                for( $i = 0; $i <= 2000; $i++ ) 
                {
                    $_moneyValue = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                    $totalWin = 0;
                    $lineWins = [];
                    $lineWinNum = [];
                    $wild = '2';
                    $scatter = '1';
                    $moneysymbol = '11';
                    $_obf_winCount = 0;
                    $strWinLine = '';
                    $winSymbols = [];
                    $reels = [];
                    $initreels = $slotSettings->GetReelStrips($winType, $slotEvent['slotEvent'], $initMoneyCounts);
                    for($k = 1; $k <= 5; $k++){
                        for($j = 0; $j < 3; $j++){
                            if($initreels['reel' . $k][$j] == 11){
                                $moneyIndex = $slotSettings->GetMoneyIndex($slotEvent['slotEvent']);
                                // if($slotSettings->GetGameData($slotSettings->slotId . 'MoneyLoopCount') % 10 > 0 && $moneyIndex >= 9){
                                //     $moneyIndex = mt_rand(0, 8);
                                // }
                                $initreels['reel' . $k][$j] = $slotSettings->money_respin[1][$moneyIndex];
                                $_moneyValue[$k - 1 + $j * 5] = $slotSettings->money_respin[2][$moneyIndex];
                            }
                        }
                    }
                    $wildPoses = [-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1];
                    if($isWild){
                        $wildCount = $slotSettings->GetWildCount();
                        $extendWildCount = 0;
                        if($wildCount > 4){
                            $extendWildCount = $wildCount - 4;
                            $wildCount = 4;
                        }
                        $wildReels = $slotSettings->GetRandomNumber(1, 5, $wildCount);
                        for($k = 0; $k < $wildCount; $k++){
                            $column = $wildReels[$k];
                            $wildRows = [];
                            if($extendWildCount == 1){
                                $extendWildCount = $extendWildCount - 1;
                                $wildRows = $slotSettings->GetRandomNumber(1, 3, 2);
                            }else if($extendWildCount >= 2){
                                $extendWildCount = $extendWildCount - 2;
                                $wildRows = $slotSettings->GetRandomNumber(1, 3, 3);
                            }else{
                                array_push($wildRows, mt_rand(1, 3));
                            }
                            for($r = 0; $r < count($wildRows); $r++){
                                $wildPoses[($wildRows[$r] - 1) * 5 + $column - 1] = $initreels['reel' . $column][$wildRows[$r] - 1];
                            }
                        }
                    }
                    
                    for($k = 1; $k <= 5; $k++){
                        $reels['reel'.$k] = [];
                        for($j=0; $j < 3; $j++){
                            if($wildPoses[$k - 1 + $j * 5] > -1){
                                $reels['reel'.$k][$j] = 2;
                                $_moneyValue[$k - 1 + $j * 5] = 0;
                            }else{
                                $reels['reel'.$k][$j] = $initreels['reel'.$k][$j];
                            }
                        }
                    }


                    for( $k = 0; $k < $lines; $k++ ) 
                    {
                        $_lineWin = '';
                        $firstEle = $reels['reel1'][$linesId[$k][0] - 1];
                        $lineWinNum[$k] = 1;
                        $mul = 1;
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
                                        array_push($winSymbols, $firstEle);
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
                                        array_push($winSymbols, $firstEle);
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
                    $moneyCount = 0;
                    $highMoneyCount = 0;
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
                            if( $reels['reel' . $r][$k] >= $moneysymbol ) 
                            {
                                $moneyCount++;
                                if($reels['reel' . $r][$k] >= 14){
                                    $highMoneyCount++;
                                }
                                $moneyTotalWin = $moneyTotalWin + $_moneyValue[$k * 5 + $r - 1] * $betline;
                            }
                        }
                    }
                    if($scattersCount >= 3){
                        $scatterPaytable = [0,0,0,1,20,100];
                        $scattersWin = $betline * $lines * $scatterPaytable[$scattersCount];
                    }
                    $totalWin = $totalWin + $scattersWin;
                    
                    if($moneyCount < 5){
                        $moneyTotalWin = 0;
                    }
                    if( $i > 1000 ) 
                    {
                        $winType = 'none';
                        $isWild = false;
                    }
                    if( $i > 1500 ) 
                    {
                        $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"Bad Reel Strip"}';
                        exit( $response );
                    }
                    if($isWild == true && $moneyCount > 0){

                    }else if($winType == 'bonus' && $moneyCount != $defaultMoneyCount){

                    }
                    else if($highMoneyCount > 1){

                    }else if( $moneyCount >= 5 && ($winType != 'bonus' || $isWild == true )) 
                    {
                    }else if( $winType == 'bonus' && $moneyCount < 5 ) 
                    {
                    }
                    else if( $moneyTotalWin + $totalWin <= $_winAvaliableMoney && $winType == 'bonus' ) 
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
                $spinType = 's';
                $isEndRespin = false;
                if( $totalWin > 0) 
                {
                    $spinType = 'c';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }
                $_obf_totalWin = $totalWin;
                
                $initReel = [];
                for($k = 0; $k < 3; $k++){
                    for($j = 1; $j <= 5; $j++){
                        $lastReel[($j - 1) + $k * 5] = $reels['reel'.$j][$k];
                        $initReel[($j - 1) + $k * 5] = $initreels['reel'.$j][$k];
                    }
                }
                $strSrf = '';
                if( $moneyCount >= 5) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', $slotSettings->slotRespinCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 51);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FinalMoneyCount', $slotSettings->GetFinalMoneyCount());     
                    $strMoneyPos = [];
                    for($k = 1; $k < 11; $k++){
                        $poses = [];
                        for($j = 0; $j < 15; $j++){
                            if($lastReel[$j] == $k){
                                $lastReel[$j] = 12;
                                array_push($poses, $j);
                            }
                        }
                        if(count($poses) > 0){
                            array_push($strMoneyPos, $k . '~12~' . implode(',', $poses));
                        }
                    }
                    $strSrf = '&srf=' . implode(';', $strMoneyPos);
                }
                if($isWild == true){
                    $strWildPos = [];
                    for($k = 1; $k <= 15; $k++){
                        $poses = [];
                        for($j = 0; $j < 15; $j++){
                            if($wildPoses[$j] == $k){
                                array_push($poses, $j);
                            }
                        }
                        if(count($poses) > 0){
                            array_push($strWildPos, $k . '~2~' . implode(',', $poses));
                        }
                    }
                    $strSrf = '&srf=' . implode(';', $strWildPos);
                }
                $strLastReel = implode(',', $lastReel);
                $strInitReel = implode(',', $initReel);
                $strReelSa = $initreels['reel1'][3].','.$initreels['reel2'][3].','.$initreels['reel3'][3].','.$initreels['reel4'][3].','.$initreels['reel5'][3];
                $strReelSb = $initreels['reel1'][-1].','.$initreels['reel2'][-1].','.$initreels['reel3'][-1].','.$initreels['reel4'][-1].','.$initreels['reel5'][-1];
                $strCurrentMoneyValue = implode(',', $_moneyValue);
                $CurrentMoneyText = [];
                for($i = 0; $i < count($_moneyValue); $i++){
                    if($_moneyValue[$i] > 0){
                        $CurrentMoneyText[$i] = 'v';
                    }else{
                        $CurrentMoneyText[$i] = 'r';
                    }
                }
                $strMoneyText = implode(',', $CurrentMoneyText);
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'MoneyValue', $_moneyValue);
                $strOtherResponse = "";
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                $n_reel_set = '';
                $strOtherResponse = '&w='.$totalWin . $strSrf;
                if($moneyCount >= 5){
                    $spinType = 'b';
                    $strOtherResponse = $strOtherResponse . '&bgid=1&coef='.$betline.'&is='.$strInitReel.'&rw='.$moneyTotalWin.'&bgt=51&lifes=4&wp='.($moneyTotalWin / $betline).'&end=0&bw=1&bpw='.$moneyTotalWin.'';

                }
                if($moneyCount > 0){
                    $strOtherResponse = $strOtherResponse . '&mo='.$strCurrentMoneyValue.'&mo_t='.$strMoneyText;
                }
                if($scattersWin > 0){
                    $strOtherResponse = $strOtherResponse . '&psym=1~' . $scattersWin.'~' . $_obf_scatterposes[0] .',' . $_obf_scatterposes[1] .',' . $_obf_scatterposes[2];
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin').'&balance='.$Balance.'&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&reel_set=0&na='.$spinType.$strWinLine.'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&c='.$betline.'&sver=5'.$strOtherResponse.'&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&s='.$strLastReel;

                $isState = true;
                if( $moneyCount >= 5 ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $isState = false;
                }
                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"totalRespinGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') . ',"currentRespinGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') . ',"Balance":' . $Balance . ',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"winLines":[],"Jackpots":""' . ',"MoneyValues":'.json_encode($_moneyValue).',"LastReel":'.json_encode($lastReel).'}}';
                $slotSettings->SaveLogReport($_GameLog, $betline * $lines, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
            }
            else if( $slotEvent['slotEvent'] == 'doBonus' ){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lines = 20;
                $moneysymbol = 13;
                if(( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') < $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') && $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') > 0 )) 
                {
                    $slotEvent['slotEvent'] = 'respin';
                }
                $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                // $_obf_winType = rand(1, $slotSettings->GetGambleSettings());
                $_obf_winType = rand(0, 1);
                // $_obf_winType = 1;
                if($slotSettings->GetGameData($slotSettings->slotId . 'Bgt') == 50){
                    $lastReel = $slotSettings->GetGameData($slotSettings->slotId . 'LastReel');
                    $_moneyValue = $slotSettings->GetGameData($slotSettings->slotId . 'MoneyValue');
                    $newWheelValue = $slotSettings->GetWheelValue();
                    // for($i = 0; $i < 10; $i++){
                    //     array_push($newWheelValue, $slotSettings->GetWheelValue());
                    // }
                    $oldWheelValue = [0,0,0,0,0,0,0,0,0,0];
                    $moneyCount =0;
                    $totalWin = 0;
                    if($slotSettings->GetGameData($slotSettings->slotId . 'WheelLevel') > 0){
                        $oldWheelValue = $slotSettings->GetGameData($slotSettings->slotId . 'NewWheelValue');
                        for($i = 0; $i < 2000; $i++){
                            $wheel_index = -1;
                            $wheel_win_poses = [];
                            $wheel_none_poses = [];
                            for($k = 0; $k < count($oldWheelValue); $k++){
                                if($oldWheelValue[$k] == 0){
                                    array_push($wheel_none_poses, $k);
                                }else{
                                    array_push($wheel_win_poses, $k);
                                }
                            }
                            if(count($wheel_none_poses) == 0 && $_obf_winType == 0){
                                $_obf_winType = 1;
                            }
                            if($_obf_winType == 1){
                                $wheel_index = $wheel_win_poses[mt_rand(0, count($wheel_win_poses) - 1)];
                                $empty_wheelCount = mt_rand(2, 3);
                                for($k = 0; $k < $empty_wheelCount; $k++){
                                    while(true){
                                        $empty_index = mt_rand(1, 8);
                                        if($newWheelValue[$empty_index] > 0 && $newWheelValue[$empty_index + 1] > 0 && $newWheelValue[$empty_index - 1] > 0){
                                            $newWheelValue[$empty_index] = 0;
                                            break;
                                        }
                                    }
                                }
                            }else{
                                $wheel_index = $wheel_none_poses[mt_rand(0, count($wheel_none_poses) - 1)];
                                $newWheelValue = [0];
                            }
                            $totalWin = $oldWheelValue[$wheel_index] * $betline * $lines;
                            if($totalWin == 0 && $_obf_winType == 0){
                                break;
                            }else if($totalWin > 0 && $_obf_winType == 1 && $slotSettings->GetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : '')) > $totalWin){
                                break;
                            }else if($i > 500){
                                $_obf_winType = 0;
                            }else if($i > 1000){
                                break;
                            }
                        }
                    }else{
                        $empty_wheelCount = mt_rand(2, 3);
                        for($k = 0; $k < $empty_wheelCount; $k++){
                            while(true){
                                $empty_index = mt_rand(1, 8);
                                if($newWheelValue[$empty_index] > 0 && $newWheelValue[$empty_index + 1] > 0 && $newWheelValue[$empty_index - 1] > 0){
                                    $newWheelValue[$empty_index] = 0;
                                    break;
                                }
                            }
                        }
                    }
                    $strOtherResponse = '';
                    if( $totalWin > 0) 
                    {
                        $slotSettings->SetBalance($totalWin);
                        $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    }
                    $isEndRespin = false;
                    if($slotSettings->GetGameData($slotSettings->slotId . 'WheelLevel') > 0){
                        $wheel_txtes = [];
                        for($i = 0; $i < count($oldWheelValue); $i++){
                            if($oldWheelValue[$i] > 0){
                                array_push($wheel_txtes, 'w');
                            }else{
                                array_push($wheel_txtes, 'go');
                            }
                        }
                        $strOtherResponse = '&whi='. $wheel_index .'&whm=' . implode(',', $wheel_txtes) . '&whw=' . implode(',', $oldWheelValue);
                        if($totalWin == 0){
                            $isEndRespin = true;
                        }
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWheelValue', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWheelValue') + $oldWheelValue[$wheel_index]);
                        $slotSettings->SetGameData($slotSettings->slotId . 'WheelIndex', $wheel_index);
                    }else{
                        $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWheelValue', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'WheelIndex', -1);
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'NewWheelValue', $newWheelValue);
                    $slotSettings->SetGameData($slotSettings->slotId . 'OldWheelValue', $oldWheelValue);
                    $spinType = 'b';
                    if($isEndRespin == true){
                        $spinType = 'cb';
                        $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&lifes=0&end=1';
                    }else{
                        $strOtherResponse = $strOtherResponse . '&lifes=1&end=0';
                    }

                    $new_wheel_txtes = [];
                    for($i = 0; $i < count($newWheelValue); $i++){
                        if($newWheelValue[$i] > 0){
                            array_push($new_wheel_txtes, 'w');
                        }else{
                            array_push($new_wheel_txtes, 'go');
                        }
                    }
                    $strOtherResponse = $strOtherResponse . '&g={nwi:{whm:"'. implode(',', $new_wheel_txtes) .'",whw:"'. implode(',', $newWheelValue) .'"}}';

                    $response = 'bmw=0.00&bgid=0&balance='.$Balance.'&coef='.($betline * $lines).'&level='.$slotSettings->GetGameData($slotSettings->slotId . 'WheelLevel').'&index='. $slotEvent['index'] . '&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType . $strOtherResponse .'&stime=' . floor(microtime(true) * 1000) .'&rw='. ($slotSettings->GetGameData($slotSettings->slotId . 'TotalWheelValue') * $betline * $lines) .'&bgt=' .$slotSettings->GetGameData($slotSettings->slotId . 'Bgt').'&wp='. $slotSettings->GetGameData($slotSettings->slotId . 'TotalWheelValue') .'&sver=5&counter='. ((int)$slotEvent['counter'] + 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'WheelLevel', $slotSettings->GetGameData($slotSettings->slotId . 'WheelLevel') + 1);
                }else{                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') + 1);
                    $finalMoneyCount = $slotSettings->GetGameData($slotSettings->slotId . 'FinalMoneyCount');
                    $leftRespin = $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') - $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame');
                    $isOverMoney = false;
                    for($i = 0; $i < 2000; $i++){
                        $moneyTotalWin = 0;
                        $moneyChangedWin = false;
                        $moneyCount = 0;
                        $diamondCount = 0;
                        $yellowMoneyCount = 0;
                        $lastReel = $slotSettings->GetGameData($slotSettings->slotId . 'LastReel');
                        $initMoneyCount = 0;
                        for($k = 0; $k < count($lastReel); $k++){
                            if($lastReel[$k] >= $moneysymbol){
                                $initMoneyCount++;
                            }
                        }
                        $_moneyValue = $slotSettings->GetGameData($slotSettings->slotId . 'MoneyValue');
                        $limitCount = mt_rand(2, 5);
                        for($k = 0; $k < count($lastReel); $k++){
                            if($lastReel[$k] < $moneysymbol){
                                $money_Chance = $slotSettings->base_money_chance;
                                if($finalMoneyCount - $initMoneyCount > mt_rand(2, 3)){
                                    $money_Chance = 50;
                                }
                                if(mt_rand(0, 100) < $money_Chance && $_obf_winType == 1 && $limitCount > 0){
                                    if($isOverMoney == true){
                                        $moneyIndex = 0;
                                    }else{
                                        $moneyIndex = $slotSettings->GetMoneyIndex($slotEvent['slotEvent']);
                                    }
                                    $lastReel[$k] = $slotSettings->money_respin[1][$moneyIndex];
                                    $_moneyValue[$k] = $slotSettings->money_respin[2][$moneyIndex];
                                    $moneyChangedWin = true;
                                    $limitCount--;
                                }
                            }
                            if($lastReel[$k] == 15){
                                $diamondCount++;
                            }else if($lastReel[$k] == 14){
                                $yellowMoneyCount++;
                            }
                            if($_moneyValue[$k] > 0){
                                $moneyTotalWin = $moneyTotalWin + $_moneyValue[$k] * $betline;
                                $moneyCount++;
                            }
                        }
                        if($i > 600){
                            break;
                        }
                        if($diamondCount > 1){

                        }else if($yellowMoneyCount > 2){

                        }else if($slotSettings->GetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : '')) < $moneyTotalWin && $isOverMoney == false){
                            $isOverMoney = true;
                            $slotSettings->SetGameData($slotSettings->slotId . 'FinalMoneyCount', 12);
                            $finalMoneyCount = 12;
                        }else if($_obf_winType == 0 && $finalMoneyCount > $moneyCount && $leftRespin == 0){
                            $_obf_winType = 1;
                        }
                        else if( $_obf_winType== 0 && $slotEvent['slotEvent'] == 'respin' &&  $moneyChangedWin == false){
                            break;
                        }
                        else if($_obf_winType == 1 && $moneyChangedWin == false && $finalMoneyCount > $moneyCount){

                        }else if($moneyChangedWin == true && $finalMoneyCount < $moneyCount){

                        }
                        else if($isOverMoney == true || $slotSettings->GetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : '')) > $moneyTotalWin) 
                        {
                            break;
                        }
                        else if($i > 500){
                            $_obf_winType = 0;
                        }
                    }
                    
                    $isEndRespin = false;
                    $totalWin = 0;
                    if($moneyChangedWin == true){
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 0);
                    }
                    if(($slotSettings->GetGameData($slotSettings->slotId . 'RespinGames')<= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') && $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') > 0)  || $moneyCount == 15){
                        $isEndRespin = true;
                        $totalWin = $moneyTotalWin;
                        $moneyTotalWin = 0;
                    }
                    $strLastReel = implode(',', $lastReel);
                    $strCurrentMoneyValue = implode(',', $_moneyValue);
                    $CurrentMoneyText = [];
                    for($i = 0; $i < count($_moneyValue); $i++){
                        if($_moneyValue[$i] > 0){
                            $CurrentMoneyText[$i] = 'v';
                        }else{
                            $CurrentMoneyText[$i] = 'r';
                        }
                    }
                    $strMoneyText = implode(',', $CurrentMoneyText);
    
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'MoneyValue', $_moneyValue);
                    $spinType = 'b';
                    $strOtherResponse = '';
                    if($isEndRespin == true){
                        if($moneyCount < 15){
                            $spinType = 'cb';
                        }else{
                            $spinType = 'b';
                            $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 50);
                            $slotSettings->SetGameData($slotSettings->slotId . 'WheelLevel', 0);
                        }
                        if( $totalWin > 0) 
                        {
                            $slotSettings->SetBalance($totalWin);
                            $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                        }
                        $strOtherResponse = '&tw='. $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') .'&end=1&rw=' . $totalWin;
                    }else{
                        $strOtherResponse = '&end=0&bpw=' . $moneyTotalWin . '&rw=' . $moneyTotalWin;
                    }
    
                    $response = 'bgid=1&balance='.$Balance.'&coef='.$betline.'&mo='.$strCurrentMoneyValue.'&mo_t='.$strMoneyText.'&index='. $slotEvent['index'] . '&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType.'&stime=' . floor(microtime(true) * 1000) .'&lifes='. ($slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') - $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame')) .'&bgt=51'.'&wp='.($moneyTotalWin / $betline).$strOtherResponse.'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&s='.$strLastReel.'';
                }
                
                $isState = false;
                if($isEndRespin == true && $moneyCount < 15) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'NewWheelValue', [0]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'OldWheelValue', [0]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWheelValue', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'WheelLevel', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                    $isState = true;
                }

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt')  . ',"totalRespinGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') . ',"currentRespinGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') . ',"Balance":' . $Balance . ',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"WheelIndex":' . $slotSettings->GetGameData($slotSettings->slotId . 'WheelIndex'). ',"WheelLevel":' . $slotSettings->GetGameData($slotSettings->slotId . 'WheelLevel'). ',"TotalWheelValue":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWheelValue') . ',"winLines":[],"Jackpots":""' . ',"OldWheelValue":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'OldWheelValue'))  . ',"NewWheelValue":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'NewWheelValue')) . ',"MoneyValues":'.json_encode($_moneyValue).',"LastReel":'.json_encode($lastReel).'}}';
                $slotSettings->SaveLogReport($_GameLog, $betline * $lines, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);

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

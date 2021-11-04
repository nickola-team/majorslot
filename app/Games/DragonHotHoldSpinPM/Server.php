<?php 
namespace VanguardLTE\Games\DragonHotHoldSpinPM
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
            	exit('unlogged');
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
            if($slotEvent['slotEvent'] == 'doSpin'){
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') < $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') > 0 ) 
                {
                    $slotEvent['slotEvent'] = 'doBonus';
                }
            }
            if( $slotEvent['slotEvent'] == 'doInit' ) 
            { 
                $lastEvent = $slotSettings->GetHistory();
                $_obf_StrResponse = '';
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'RespinLevel', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWheelValue', 0); 
                $slotSettings->SetGameData($slotSettings->slotId . 'WheelIndex', -1);                      
                $slotSettings->SetGameData($slotSettings->slotId . 'NewWheelValue', [0]);
                $slotSettings->SetGameData($slotSettings->slotId . 'OldWheelValue', [0]);
                $slotSettings->SetGameData($slotSettings->slotId . 'MoneyValue', [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());                
                $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 5);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [9,10,3,7,5,7,10,6,3,9,5,4,6,9,7]);
                $slotSettings->setGameData($slotSettings->slotId . 'RoundID', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'WildMaskCounts', [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]);
                $slotSettings->SetGameData($slotSettings->slotId . 'MoneyMaskIndexes', [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]);
                $slotSettings->SetGameData($slotSettings->slotId . 'MoneyIndexes', [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,2]);  
                $slotSettings->SetGameData($slotSettings->slotId . 'DefaultMaskMoneyCount', [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]);    
                $slotSettings->SetGameData($slotSettings->slotId . 'MoneyLoopCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FinalMoneyCount', 12);   
                $slotSettings->SetGameData($slotSettings->slotId . 'FinalMoneyReels', [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]); 
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', $lastEvent->serverResponse->totalRespinGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', $lastEvent->serverResponse->currentRespinGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinLevel', $lastEvent->serverResponse->RespinLevel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWheelValue', $lastEvent->serverResponse->TotalWheelValue);                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'WheelIndex', $lastEvent->serverResponse->WheelIndex);
                    $slotSettings->SetGameData($slotSettings->slotId . 'NewWheelValue', $lastEvent->serverResponse->NewWheelValue);
                    $slotSettings->SetGameData($slotSettings->slotId . 'OldWheelValue', $lastEvent->serverResponse->OldWheelValue);
                    $slotSettings->SetGameData($slotSettings->slotId . 'MoneyValue', $lastEvent->serverResponse->MoneyValues);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);          
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', $lastEvent->serverResponse->Bgt);
                    $slotSettings->setGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    if (isset($lastEvent->serverResponse->ReplayGameLogs)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', json_decode(json_encode($lastEvent->serverResponse->ReplayGameLogs), true)); //ReplayLog
                    }
                    if (isset($lastEvent->serverResponse->FinalMoneyReels)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FinalMoneyReels', $lastEvent->serverResponse->FinalMoneyReels); 
                    }
                    $bet = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $bet = 400;
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
                if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') < $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') > 0){
                    $currentReelSet = 2;
                    $spinType = 's';
                    for($r = 0; $r < count($moneyPoses); $r++){
                        if($r == 0){
                            $strSty = $moneyPoses[$r].','.$moneyPoses[$r];
                        }else{
                            $strSty = $strSty . '~' . $moneyPoses[$r].','.$moneyPoses[$r];
                        }
                    }
                    $strSty =  '&sty=' . $strSty;
                    $_obf_StrResponse = '&tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin').'&pw='.($sum * $bet).$strSty.'&rs=mc&rs_p='.$slotSettings->GetGameData($slotSettings->slotId . 'RespinLevel').'&rs_m='.$slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') .'&rs_c='.$slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame').'&rw='.($sum * $bet);
                }else if($slotSettings->GetGameData($slotSettings->slotId . 'Bgt') == 50){
                    $spinType = 'b';
                    for($r = 0; $r < count($moneyPoses); $r++){
                        if($r == 0){
                            $strSty = $moneyPoses[$r].',-1';
                        }else{
                            $strSty = $strSty . '~' . $moneyPoses[$r].',-1';
                        }
                    }
                    $strSty =  '&sty=' . $strSty;
                    $newWheelValue = $slotSettings->GetGameData($slotSettings->slotId . 'NewWheelValue');
                    $new_wheel_txtes = [];
                    for($i = 0; $i < count($newWheelValue); $i++){
                        if($newWheelValue[$i] > 0){
                            array_push($new_wheel_txtes, 'w');
                        }else{
                            array_push($new_wheel_txtes, 'go');
                        }
                    }
                    $_obf_StrResponse = '&tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin').'&pw=0&mo_tv='.$sum.'&bmw=0.00&bgid=0&mo_c=1&bgt=50&lifes=1&bw=1&wp='. $slotSettings->GetGameData($slotSettings->slotId . 'TotalWheelValue') .'&rw='. ($slotSettings->GetGameData($slotSettings->slotId . 'TotalWheelValue') * $bet * $slotSettings->GetGameData($slotSettings->slotId . 'Lines')) . '&end=0'.$strSty. '&g={nwi:{whm:"'. implode(',', $new_wheel_txtes) .'",whw:"'. implode(',', $newWheelValue) .'"}}';
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
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                $Balance = $slotSettings->GetBalance();
                
                $response = 'def_s=9,10,3,7,5,7,10,6,3,9,5,4,6,9,7&balance='. $Balance .'&cfgs=1&ver=2&mo_s=13;14;15&index=1&balance_cash='. $Balance .'&def_sb=8,7,6,10,10&mo_v=5,10,15,20,25,30,35,40,45;50,55,60,65,70,75,80,85,90,95,100;500,1000,1500,2000,2500,5000&reel_set_size=3&def_sa=8,10,3,10,5&reel_set='.$currentReelSet.$_obf_StrResponse.'&mo='.$strCurrentMoneyValue.'&mo_t='.$strMoneyText.'&balance_bonus=0.00&na='. $spinType.'&scatters=1~100,20,1,0,0~0,0,0,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&gameInfo={rtps:{regular:"96.70"},props:{max_rnd_sim:"1",max_rnd_hr:"2000000",max_rnd_win:"20000"}}&wl_i=tbm~20000&stime=1633353220227&sa=8,10,3,10,5&sb=8,7,6,10,10&sc='. implode(',', $slotSettings->Bet) .'&defc=400.00&sh=3&wilds=2~5000,1000,100,1,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;5000,1000,100,0,0;500,200,50,0,0;200,50,20,0,0;200,50,20,0,0;200,50,20,0,0;50,10,2,0,0;50,10,2,1,0;50,10,2,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0&l=5&reel_set0=6,9,9,9,9,8,7,7,7,10,3,10,10,10,5,13,7,1,3,3,3,4,6,6,6,8,8,8,13,13,13,8,14,9,4,9,10,3,10,3,9,10,3,1,3,5,3,4,7,4,3,9,4,9,3,10,13,9,3,9,3,7,8,9,3,4,10,4,14,4,3,8,4,9,4,7,3,10,3,9,10,15,4,3,8,3,8,3,10,4,9,3,4,3,1,9,3,10,9,3,10,3,10,7,9,8,10,8,9,3,10,9,8,3,7,10,7,9~10,7,7,7,7,6,8,13,13,14,9,5,5,5,3,1,10,10,10,13,8,8,8,5,4,9,9,9,5,7,5,4,9,3,8,3,9,5,13,3,9,4,7,8,13,6,9,8,3,7,5,6,13,7,9,13,7,14,9,5,7,9,6,7,4,3,9,3,5,3,8,3,15,6,5,8,13,6,7,9,13,8,13,9,5,13,3,9,13,8,7,5,4,7,3,8,9,13,4,13,8,9,8,5,9,5,3,4,13,3,9,13,5,13,8,7,5,3,13,3,9,5,8,9,13,9,5,13,8,13~8,8,8,4,8,10,9,4,4,4,13,6,6,6,6,7,10,10,10,1,7,7,7,3,5,9,9,9,3,3,3,15,13,14,4,9~7,9,8,7,7,7,3,5,4,9,9,9,1,5,5,5,13,6,3,3,3,10,8,8,8,10,10,10,15,13,13,10,9,10,5,9,3,4,9,10,13,9,13,4,14,5,10,9,13,3,5,4,9,5,10,9,3,10,9,5,1,10,9,13,9,5,10,5,3,9,3,13,4,3,10,1,8,13,9,5,8,5,13,9,10,8,9,10,13,10,3,4,13,9,10,5,9,8,10,14,1,3,10,9,3,8,9,13,10,9,13,4,5~5,8,6,10,10,10,14,5,5,5,4,9,13,13,13,7,3,4,4,4,10,1,8,8,8,6,6,6,10,13,10,4,1,6,10,4,8,10,6,4,10,6,7,15,10,4&s='.$lastReelStr.'&reel_set2=12,12,12,12,12,15,13,13,12,13,13,13,12,13,12,12,14~12,12,13,12,12,12,12,12,13,12,12,12,13,12,13,13,13,14,15,12,12,12,13,13,13,12,12,13,12,13,13,12,14,15,14,12~13,13,12,15,12,12,12,12,13,13,12,14,13,13,13,12,12,13,12,14,12,12~12,12,12,12,12,13,14,12,12,13,12,12,13,13,13,13,12,12,15~15,12,13,12,13,15,12,12,12,12,12,12,13,13,12,12,13,12,13,12,13,14,12,13,13,13,12,13,14,12,13,14,12,12,12,12,12,12&reel_set1=6,6,6,1,5,3,9,8,7,4,6,3,5,10,4,9,7,9,5,4~9,3,10,10,10,6,8,1,6,4,8,8,8,10,7,5,6,3,9,8,10,8,7,10,1,5,3,10,3,10~6,6,6,1,8,8,8,8,4,7,10,9,5,10,9,6,7,10,3,8,9,7,3,8,5,6,8,9,8,5,8,9,4,8,1,9,3,5,8,1,9,4,9,10,5,8,7,5,8~10,5,9,6,8,8,8,8,5,1,3,8,9,7,10,4,8,1,8,3,5,8,4,6~10,3,7,5,1,8,3,9,8,8,8,5,4,6,10,7,1,6,8,3,5,8,5,8,6,4,5,1,8,1,3,4,3,6,8,1,3,8,6,4,1,7,1,9,5,4,8,6,3,4';
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
                $slotEvent['slotBet'] = $slotEvent['c'];
                $slotEvent['slotLines'] = 5;
                $linesId = [];
                $linesId[0] = [2,2,2,2,2];
                $linesId[1] = [1,1,1,1,1];
                $linesId[2] = [3,3,3,3,3];
                $linesId[3] = [1,2,3,2,1];
                $linesId[4] = [3,2,1,2,3];
                
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') < $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') > 0 ) 
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinLevel', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWheelValue', 0);                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'NewWheelValue', [0]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'OldWheelValue', [0]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'WheelIndex', -1);       
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'MoneyValue', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '275' . substr($roundstr, 4, 7);
                    $slotSettings->setGameData($slotSettings->slotId . 'RoundID', $roundstr);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FinalMoneyCount', 12); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FinalMoneyReels', [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]);
                }
                $Balance = $slotSettings->GetBalance();
                if( $slotEvent['slotEvent'] == 'bet' ) 
                {
                    $slotSettings->UpdateJackpots($betline * $lines);
                }
                $isWild = false;
                // $winType = 'win';
                // $_winAvaliableMoney = $slotSettings->GetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''));
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
                    $moneysymbol = '13';
                    $_obf_winCount = 0;
                    $strWinLine = '';
                    $winSymbols = [];
                    $reels = [];
                    $initreels = $slotSettings->GetReelStrips($winType, $slotEvent['slotEvent'], $initMoneyCounts);
                    for($k = 1; $k <= 5; $k++){
                        for($j = 0; $j < 3; $j++){
                            if($initreels['reel' . $k][$j] >= $moneysymbol){
                                $moneyIndex = $slotSettings->GetMoneyIndex($slotEvent['slotEvent']);
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

                    $isGoldWin = false;
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
                                        if($firstEle == 3){
                                            $isGoldWin = true;
                                        }
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
                                        if($firstEle == 3){
                                            $isGoldWin = true;
                                        }
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

                    }else if($isGoldWin == true && mt_rand(0, 100) < 70){

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
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 51);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FinalMoneyCount', $slotSettings->GetFinalMoneyCount());
                    $slotSettings->SetGameData($slotSettings->slotId . 'FinalMoneyReels', $slotSettings->GetFinalMoneyReels($lastReel, $slotSettings->GetGameData($slotSettings->slotId . 'FinalMoneyCount')));
                    $strMoneyPos = [];
                    for($k = 1; $k < $moneysymbol; $k++){
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
                    for($k = 0; $k < 15; $k++){
                        if($initReel[$k] >= $moneysymbol){
                            $initReel[$k] = 11;
                        }
                    }
                    $strSrf = '&srf=' . implode(';', $strMoneyPos);

                    $strSty = '';
                    $moneyPoses = [];
                    for($j = 0; $j < 15; $j++){
                        if($lastReel[$j] >= $moneysymbol){
                            array_push($moneyPoses, $j);
                        }
                    }
                    for($r = 0; $r < count($moneyPoses); $r++){
                        if($r == 0){
                            $strSty = $moneyPoses[$r].','.$moneyPoses[$r];
                        }else{
                            $strSty = $strSty . '~' . $moneyPoses[$r].','.$moneyPoses[$r];
                        }
                    }
                    $strSrf =  '&sty=' . $strSty;
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
                    $strReelSa = '9,1,8,7,8';
                    $strReelSb = '7,13,4,10,9';
                    $spinType = 's';
                    $strOtherResponse = $strOtherResponse . '&rs=mc&rs_p=0&is='.$strInitReel.'&pw='.$moneyTotalWin.'&rs_c=1&rs_m=4';
                }else if($isWild == true){
                    $strOtherResponse = $strOtherResponse . '&is='.$strInitReel;
                }
                if($moneyCount > 0){
                    $strOtherResponse = $strOtherResponse . '&mo='.$strCurrentMoneyValue.'&mo_t='.$strMoneyText;
                }
                if($scattersWin > 0){
                    $strOtherResponse = $strOtherResponse . '&psym=1~' . $scattersWin.'~' . $_obf_scatterposes[0] .',' . $_obf_scatterposes[1] .',' . $_obf_scatterposes[2];
                }
                if($isWild == true){
                    $reel_set = 1;
                }else{
                    $reel_set = 0;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin').'&balance='.$Balance.'&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&reel_set='.$reel_set.'&na='.$spinType.$strWinLine.'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&c='.$betline.'&sver=5'.$strOtherResponse.'&counter='. ((int)$slotEvent['counter'] + 1) .'&l=5&s='.$strLastReel;

                $isState = true;
                if( $moneyCount >= 5 ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $isState = false;
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt')  . ',"totalRespinGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') . ',"currentRespinGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') . ',"WheelIndex":' . $slotSettings->GetGameData($slotSettings->slotId . 'WheelIndex') . ',"RespinLevel":' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinLevel'). ',"TotalWheelValue":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWheelValue') . ',"Balance":' . $Balance . ',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')  . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"ReplayGameLogs":'.json_encode($replayLog). ',"winLines":[],"Jackpots":""' . ',"OldWheelValue":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'OldWheelValue'))  . ',"NewWheelValue":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'NewWheelValue')) . ',"MoneyValues":'.json_encode($_moneyValue) . ',"FinalMoneyReels":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FinalMoneyReels')).',"LastReel":'.json_encode($lastReel).'}}';
                $slotSettings->SaveLogReport($_GameLog, $betline * $lines, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
            }
            else if( $slotEvent['slotEvent'] == 'doBonus' ){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lines = 5;
                $moneysymbol = 13;
                if(( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') < $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') && $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') > 0) || $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') == 50) 
                {
                    $slotEvent['slotEvent'] = 'respin';
                }
                $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                // $_obf_winType = rand(1, $slotSettings->GetGambleSettings());
                $_obf_winType = rand(0, 1);
                if($slotSettings->GetGameData($slotSettings->slotId . 'Bgt') == 51){
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') + 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinLevel', $slotSettings->GetGameData($slotSettings->slotId . 'RespinLevel') + 1);
                    $finalMoneyCount = $slotSettings->GetGameData($slotSettings->slotId . 'FinalMoneyCount');
                    $finalMoneyReels = $slotSettings->GetGameData($slotSettings->slotId . 'FinalMoneyReels');
                    $leftRespin = $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') - $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') + 1;
                    $isOverMoney = false;
                    for($i = 0; $i < 2000; $i++){
                        $moneyTotalWin = 0;
                        $moneyChangedWin = false;
                        $moneyCount = 0;
                        $diamondCount = 0;
                        $lastReel = $slotSettings->GetGameData($slotSettings->slotId . 'LastReel');
                        $initMoneyCount = 0;
                        for($k = 0; $k < count($lastReel); $k++){
                            if($lastReel[$k] >= $moneysymbol){
                                $initMoneyCount++;
                            }
                        }
                        if($finalMoneyCount <= $initMoneyCount){
                            $_obf_winType = 0;
                        }
                        $_moneyValue = $slotSettings->GetGameData($slotSettings->slotId . 'MoneyValue');
                        $limitCount = mt_rand(2, 4);
                        for($k = 0; $k < count($lastReel); $k++){
                            if($lastReel[$k] < $moneysymbol){
                                if($finalMoneyCount - $initMoneyCount <= $limitCount){
                                    $limitCount = 1;
                                }
                                if($finalMoneyReels[$k] == 1 && $_obf_winType == 1 && $limitCount > 0){
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
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 1);
                    }
                    if($moneyCount == 15){
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 4);
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') + 1<= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') && $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') > 0){
                        $isEndRespin = true;
                        $totalWin = $moneyTotalWin;
                        $moneyTotalWin = 0;
                    }
                    

                    $strLastReel = implode(',', $lastReel);
                    $strCurrentMoneyValue = implode(',', $_moneyValue);
                    $CurrentMoneyText = [];
                    $totalMoneyValue = 0;
                    for($i = 0; $i < count($_moneyValue); $i++){
                        if($_moneyValue[$i] > 0){
                            $CurrentMoneyText[$i] = 'v';
                            $totalMoneyValue = $totalMoneyValue + $_moneyValue[$i];
                        }else{
                            $CurrentMoneyText[$i] = 'r';
                        }
                    }
                    $strMoneyText = implode(',', $CurrentMoneyText);

                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'MoneyValue', $_moneyValue);
                    $spinType = 's';
                    $strOtherResponse = '';
                    if($moneyCount == 15){
                        $strOtherResponse = '&bw=1';
                        $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 50);
                        $slotSettings->SetGameData($slotSettings->slotId . 'RespinLevel', 0);
                    }
                    if($isEndRespin == true){
                        $spinType = 'c';
                        if( $totalWin > 0) 
                        {
                            $slotSettings->SetBalance($totalWin);
                            $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                        }
                        $strOtherResponse =  $strOtherResponse. '&mo_tv=' . $totalMoneyValue .'&mo_c=1&mo_tw='.$totalWin.'&w=' . $totalWin;
                        if($moneyCount < 15){
                            $strOtherResponse = $strOtherResponse . '&rs_t=' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinLevel');
                        }else{
                            $spinType = 'b';
                        }
                    }else{
                        $strOtherResponse =  $strOtherResponse . '&w=0.00&rs_p=' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinLevel') . '&rs_c='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') .'&rs_m=' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames');
                        if($moneyChangedWin == true){
                            $strOtherResponse = $strOtherResponse . '&rs=mc';
                        }
                    }

                    $strSty = '&sty=';
                    $moneyPoses = [];
                    for($j = 0; $j < 15; $j++){
                        if($lastReel[$j] >= $moneysymbol){
                            array_push($moneyPoses, $j);
                        }
                    }
                    for($r = 0; $r < count($moneyPoses); $r++){                        
                        $endPos = $moneyPoses[$r];
                        if($isEndRespin == true){
                            $endPos = -1;
                        }
                        if($r == 0){
                            $strSty = $strSty . $moneyPoses[$r].','.$endPos;
                        }else{
                            $strSty = $strSty . '~' . $moneyPoses[$r].','.$endPos;
                        }
                    }
                    if($isEndRespin == true && $moneyCount == 15){
                        $isEndRespin = false;
                    }
                    $strReelSa = '9,1,8,7,8';
                    $strReelSb = '7,13,4,10,9';
                    $response = 'tw='. $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') .'&pw=' . $moneyTotalWin . $strOtherResponse . '&balance='.$Balance.'&mo='.$strCurrentMoneyValue.'&mo_t='.$strMoneyText.'&index='. $slotEvent['index'] . '&balance_cash='.$Balance.'&reel_set=2&balance_bonus=0.00&na='.$spinType.$strSty.'&stime=' . floor(microtime(true) * 1000) .'&sh=3&c='.$betline.'&sa='. $strReelSa .'&sb='. $strReelSb .'&sver=5&l=5&counter='. ((int)$slotEvent['counter'] + 1) .'&s='.$strLastReel;
                }else{
                    $lastReel = $slotSettings->GetGameData($slotSettings->slotId . 'LastReel');
                    $_moneyValue = $slotSettings->GetGameData($slotSettings->slotId . 'MoneyValue');
                    $newWheelValue = $slotSettings->GetWheelValue();
                    $oldWheelValue = [0,0,0,0,0,0,0,0,0,0];
                    $totalWin = 0;
                    if($slotSettings->GetGameData($slotSettings->slotId . 'RespinLevel') > 0){
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
                                $emptyReel = $slotSettings->GetRandomNumber(1, 8, 8);
                                for($k = 0; $k < $empty_wheelCount; $k++){
                                    for($j = 0; $j < 8; $j++){
                                        $empty_index = $emptyReel[$j];
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
                            }else if($i > 600){
                                break;
                            }
                        }
                    }else{
                        $empty_wheelCount = mt_rand(2, 3);
                        $emptyReel = $slotSettings->GetRandomNumber(1, 8, 8);
                        for($k = 0; $k < $empty_wheelCount; $k++){
                            for($j = 0; $j < 8; $j++){
                                $empty_index = $emptyReel[$j];
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
                    if($slotSettings->GetGameData($slotSettings->slotId . 'RespinLevel') > 0){
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

                    $response = 'bmw=0.00&bgid=0&balance='.$Balance.'&coef='.($betline * $lines).'&level='.$slotSettings->GetGameData($slotSettings->slotId . 'RespinLevel').'&index='. $slotEvent['index'] . '&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType . $strOtherResponse .'&stime=' . floor(microtime(true) * 1000) .'&rw='. ($slotSettings->GetGameData($slotSettings->slotId . 'TotalWheelValue') * $betline * $lines) .'&bgt=' .$slotSettings->GetGameData($slotSettings->slotId . 'Bgt').'&wp='. $slotSettings->GetGameData($slotSettings->slotId . 'TotalWheelValue') .'&sver=5&counter='. ((int)$slotEvent['counter'] + 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinLevel', $slotSettings->GetGameData($slotSettings->slotId . 'RespinLevel') + 1);
                }
                // $response = '&bgid=1&balance='.$Balance.'&coef='.$betline.'&mo='.$strCurrentMoneyValue.'&mo_t='.$strMoneyText.'&index='. $slotEvent['index'] . '&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType.'&stime=' . floor(microtime(true) * 1000) .'&lifes='. ($slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') - $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame')) .'&bgt=' .$slotSettings->GetGameData($slotSettings->slotId . 'Bgt').$strOtherResponse.'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&s='.$strLastReel.'';
                $isState = false;
                if($isEndRespin == true) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'NewWheelValue', [0]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'OldWheelValue', [0]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWheelValue', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinLevel', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                    $isState = true;
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt')  . ',"totalRespinGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') . ',"currentRespinGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') . ',"WheelIndex":' . $slotSettings->GetGameData($slotSettings->slotId . 'WheelIndex') . ',"RespinLevel":' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinLevel'). ',"TotalWheelValue":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWheelValue') . ',"Balance":' . $Balance . ',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')  . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"ReplayGameLogs":'.json_encode($replayLog). ',"winLines":[],"Jackpots":""' . ',"OldWheelValue":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'OldWheelValue'))  . ',"NewWheelValue":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'NewWheelValue')) . ',"MoneyValues":'.json_encode($_moneyValue) . ',"FinalMoneyReels":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FinalMoneyReels')).',"LastReel":'.json_encode($lastReel).'}}';
                
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

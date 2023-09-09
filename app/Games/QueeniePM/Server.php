<?php 
namespace VanguardLTE\Games\QueeniePM
{
    class Server
    {
        public $winLines = [];
        public function get($request, $game, $userId) // changed by game developer
        {
            $response = '';
            \DB::beginTransaction();
            if( $userId == null ) 
            {
            	$response = 'unlogged';
                exit( $response );
            }
            $user = \VanguardLTE\User::lockForUpdate()->find($userId);
            $credits = null;
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

            if($slotEvent['slotEvent'] == 'doSpin' && isset($slotEvent['c'])) 
            { 
               $slotSettings->SetGameData($slotSettings->slotId . 'Bet', $slotEvent['c']); 
            } 
            $slotSettings->SetBet(); 


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
                $slotSettings->SetGameData($slotSettings->slotId . 'Level', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 25);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [4,8,13,7,10,6,13,5,5,9,7,9,13,4,12]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'Wins', [0,0,0,0,0,0,0,0,0,0,0,0]);
                $slotSettings->SetGameData($slotSettings->slotId . 'Status', [0,0,0,0,0,0,0,0,0,0,0,0]);
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $strOtherResponse = '';
                $currentReelSet = 0;
                $stack = null;
                if($lastEvent == 'NULL'){
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '561' . substr($roundstr, 4, 10);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);
                }
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Level', $lastEvent->serverResponse->Level);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', $lastEvent->serverResponse->Bgt);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Wins', json_decode(json_encode($lastEvent->serverResponse->Wins), true));
                    $slotSettings->SetGameData($slotSettings->slotId . 'Status', json_decode(json_encode($lastEvent->serverResponse->Status), true));
                    if (isset($lastEvent->serverResponse->ReplayGameLogs)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', json_decode(json_encode($lastEvent->serverResponse->ReplayGameLogs), true)); //ReplayLog
                    }
                    if (isset($lastEvent->serverResponse->FreeStacks)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', json_decode(json_encode($lastEvent->serverResponse->FreeStacks), true)); // FreeStack
                        $FreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                        $stack = $FreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') -1];
                    }
                    $bet = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $bet = '40.00';
                }
                $spinType = 's';
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if(isset($stack)){
                    $str_initReel = $stack['initReel'];
                    $currentReelSet = $stack['reel_set'];
                    $str_trail = $stack['trail'];
                    $whi = $stack['whi'];
                    $str_whm = $stack['whm'];
                    $str_whw = $stack['whw'];
                    $str_stf = $stack['stf'];
                    $str_wmt = $stack['wmt'];
                    $wmv = $stack['wmv'];
                    $bw = $stack['bw'];
                    $wi = $stack['wi'];
                    $wp = $stack['wp'];
                    $wins = $slotSettings->GetGameData($slotSettings->slotId . 'Wins');
                    $status = $slotSettings->GetGameData($slotSettings->slotId . 'Status');
                    $wins_mask = [];
                    for($k = 0; $k < count($wins); $k++){
                        if($wins[$k] == 0){
                            $wins_mask[$k] = 'h';
                        }else{
                            $wins_mask[$k] = 'jp' . $wins[$k];
                        }
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                    {
                        if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                        {
                            $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsend_total=1&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        }
                        else
                        {
                            $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        }
                        if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                            $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                        }
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'Bgt') == 50 || $wp > 0){
                        $strOtherResponse = $strOtherResponse . '&bgid=0&rw=0&status='. implode(',', $status) .'&wins='. implode(',', $wins).'&wins_mask='. implode(',', $wins_mask).'&bgt='. $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') .'&coef='. ($bet * 25);
                        if($slotSettings->GetGameData($slotSettings->slotId . 'Level') >= 0){
                            $strOtherResponse = $strOtherResponse  .'&level='. $slotSettings->GetGameData($slotSettings->slotId . 'Level');
                        }
                        if($wp > 0){
                            $strOtherResponse = $strOtherResponse . '&lifes=0&end=1';
                        }else{
                            $strOtherResponse = $strOtherResponse . '&lifes=1&end=0';
                        }
                        if($wp > -1){
                            $strOtherResponse = $strOtherResponse . '&wp=' . $wp;
                            if($wp > 0){   
                            }else{
                                $spinType = 'b';
                            }
                        }else{
                            $spinType = 'b';
                        }
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') > 0){                                                     
                        $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                    }
                    if($str_trail != ''){
                        $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                    }
                    if($whi >= 0){
                        $strOtherResponse = $strOtherResponse . '&whi=' . $whi;
                    }
                    if($str_whm != ''){
                        $strOtherResponse = $strOtherResponse . '&whm=' . $str_whm;
                    }
                    if($str_whw != ''){
                        $strOtherResponse = $strOtherResponse . '&whw=' . $str_whw;
                    }
                    if($str_stf != ''){
                        $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                    }
                    if($str_wmt != ''){
                        $strOtherResponse = $strOtherResponse . '&wmt=' . $str_wmt;
                    }
                    if($wmv >= 0){
                        $strOtherResponse = $strOtherResponse . '&wmv=' . $wmv;
                    }
                    if($bw >= 0){
                        $strOtherResponse = $strOtherResponse . '&bw=' . $bw;
                    }
                }else{
                    $strOtherResponse = $strOtherResponse . '&whi=0&whm=fs,jp,mp,ew,cr,mp,jp,fs,fs,cr,ew,mp,mp,ew,cr,ew,mp,cr,ew,cr&whw=50,0,5,4,1000,20,0,20,10,2000,7,3,100,5,2500,4,50,200,3,750';
                }
                // if($currentReelSet >= 0){
                //     $strOtherResponse = $strOtherResponse . '&reel_set='. $currentReelSet;
                // }
                
                $Balance = $slotSettings->GetBalance();
                // $response = 'def_s=7,5,4,3,6,7,9,4,10,8,6,6,4,9,8&balance='. $Balance .'&cfgs=5283&accm=cp&ver=2&acci=0&index=1&balance_cash='. $Balance .'&def_sb=3,8,4,7,10&reel_set_size=7&def_sa=7,4,6,10,10&reel_set='.$currentReelSet.$strOtherResponse.'&balance_bonus=0.00&na='. $spinType.'&accv='. $slotSettings->GetGameData($slotSettings->slotId . 'TotalMoneyValue') .'&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"5907995",max_rnd_win:"4500"}}&wl_i=tbm~4500&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa=7,4,6,10,10&sb=3,8,4,7,10&sc='. implode(',', $slotSettings->Bet) .'&defc=100.00&purInit_e=1,1,1,1,1,1,1,1,1,1,1,1&sh=3&wilds=2~300,100,40,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;300,100,40,0,0;300,100,40,0,0;150,50,25,0,0;120,40,20,0,0;90,30,15,0,0;60,20,10,0,0;36,12,6,0,0;36,12,6,0,0;30,10,5,0,0;30,10,5,0,0;0,0,0,0,0&l=20&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=12,12,12,4,11,7,8,5,11,4,8,5,10,7,11,6,5,3,6,2,2,2,7,11,9~2,2,2,8,5,9,4,8,10,6,9,5,6,8,4,5,9,7,4,9,6,10,9,8,6,10,5,12,12,12,9,3,7,10,8,3,10,9,11~5,6,9,8,2,2,2,10,4,7,10,3,11,8,7,10,11,7,8,3,7,12,12,12~12,12,12,3,6,9,5,11,10,7,8,6,11,4,9,8,6,4,8,7,9,11,7,6,8,10,5,9,6,8,11,7,9,3,5,8,7,11,10,9,4,6,9,7,4,8,3,9,7,11,8,10,5,9,4,7,8,5,2,2,2~12,12,12,12,11,6,10,7,6,10,4,9,7,8,10,6,4,5,11,10,7,4,10,11,6,8,5,6,2,2,2,8,3&s='.$lastReelStr.'&accInit=[{id:0,mask:"cp;mp"}]&reel_set2=2,2,2,2,11,10,5,9,8,7,10,5,7,8,11,4,5,11,10,8,5,9,6,7,4,9,5,7,10,6,11,5,4,8,10,3,7,11,12,12,12~12,12,12,5,8,3,4,7,9,8,5,9,4,6,3,7,8,6,5,7,9,6,8,4,5,11,6,7,2,2,2,2,4,8,5,9,6,10~2,2,2,2,6,9,3,11,7,6,10,7,11,8,6,11,5,10,4,11,5,10,6,11,12,12,12~2,2,2,2,4,3,5,6,4,9,8,4,3,8,6,10,4,6,9,11,6,9,4,3,8,7,6,5,9,6,7,3,8,4,9,6,7,9,4,11,8,3,5,9,8,5,6,9,4,8,5,3,9,6,11,5,6,8,4,9,8,5,3,4,10,5,9,8,6,4,5,11,10,12,12,12~12,12,12,5,4,7,11,4,5,7,8,5,9,6,7,10,11,7,10,4,9,11,7,3,10,4,5,11,10,7,4,11,8,6,10,5,6,8,3,4,2,2,2,2&reel_set1=2,2,2,11,7,9,11,6,5,11,9,8,7,11,5,8,3,5,11,9,5,6,7,9,5,7,9,6,3,12,12,12,6,9,7,11,9,6,11,3,6,11,9,7,11,6,9,11,6,5,4,6,9,3,10~2,2,2,10,9,5,8,7,6,9,10,3,7,4,11,5,9,8,7,9,6,8,5,6,10,5,8,10,9,6,4,9,6,10,5,6,12,12,12~12,12,12,10,7,3,10,7,8,9,3,11,8,7,4,11,7,10,8,4,7,10,3,11,5,8,10,6,9,8,10,2,2,2~12,12,12,3,6,9,5,11,10,7,8,6,11,4,9,8,6,4,8,7,9,11,7,6,8,10,5,9,2,2,2~2,2,2,7,11,10,7,3,11,5,3,10,9,7,5,9,8,7,4,12,12,12,9,7,4,8,10,4,5,11,10,7,4,10,11,6&reel_set4=2,2,2,2,10,5,7,8,11,4,5,11,10,8,5,9,6,7,4,9,5,7,10,6,11,5,4,8,10,3,7,11,12,12,12~12,12,12,6,9,4,6,7,8,5,4,3,8,4,9,3,11,6,5,8,3,4,7,9,8,5,9,4,6,3,7,8,6,5,7,9,6,8,4,5,11,6,7,4,8,5,9,6,10,4,9,8,6,11,5,6,3,5,10,2,2,2,2~12,12,12,9,10,7,11,6,8,9,10,8,6,10,4,5,11,7,6,11,7,6,11,4,5,7,6,9,7,6,8,10,6,7,10,6,11,9,6,2,2,2,2,7,6,9,8,6,10,3~2,2,2,2,6,5,9,6,7,3,8,4,9,6,7,9,4,11,8,3,5,9,8,5,6,9,12,12,12,4,8,5,3,9,6,11,5,6,8,4,9,8,5,3,4,10~12,12,12,11,4,5,11,10,3,7,5,3,10,9,7,10,4,5,8,7,10,4,3,7,11,10,7,4,10,11,6,5,8,3,10,4,11,7,3,5,10,3,11,4,9,7,4,11,5,10,7,11,3,7,4,5,11,9,5,4,7,11,4,5,7,8,5,2,2,2,2&purInit=[{type:"fs",bet:2000,fs_count:9},{type:"fs",bet:2200,fs_count:9},{type:"fs",bet:2400,fs_count:9},{type:"fs",bet:2600,fs_count:9},{type:"fs",bet:2800,fs_count:9},{type:"fs",bet:3000,fs_count:9},{type:"fs",bet:3200,fs_count:9},{type:"fs",bet:3400,fs_count:9},{type:"fs",bet:3600,fs_count:9},{type:"fs",bet:3800,fs_count:9},{type:"fs",bet:4000,fs_count:9},{type:"r",bet:3000}]&reel_set3=2,2,2,2,4,8,10,3,7,6,11,9,4,8,11,10,3,11,9,7,11,8,5,9,7,5,4,9,3,6,10,7,4,10,7,11,8,3,9,7,4,11,10,5,9,8,7,10,5,7,8,11,4,5,11,10,8,5,9,7,4,9,5,7,10,6,11,5,4,8,10,3,7,11,12,12,12,12~11,6,10,5,8,3,4,5,9,4,6,3,9,6,8,9,4,3,2,2,2,2,9,6,5,8,3,4,7,9,12,12,12,12~12,12,12,12,7,10,11,6,9,8,6,7,10,6,5,11,6,10,7,8,11,3,10,7,11,10,6,11,10,7,6,11,10,7,9,2,2,2,2,3,7,11,6,7,5,8,7,6,9,7,6,8,4~2,2,2,2,6,3,5,6,8,4,3,5,6,4,9,8,4,3,8,6,10,4,7,6,9,11,6,9,4,3,8,6,5,12,12,12,12~8,7,10,4,2,2,2,2,7,11,6,3,10,4,11,7,3,5,10,3,11,4,9,7,12,12,12,12&reel_set6=11,5,8,3,7,8,3,10~6,9,10,5,4,9,8~9,3,7,6,11,3,4~5,9,3,4,8,6,10,7,6,9,11~10,5,6,8,11,7,4,5,3,11,5,6,8,4,9&reel_set5=9,7,10,8,5,2,2,2,2,4,6,10,7,12,12,12,12,11,5,10,4,6,3~7,12,12,12,12,4,8,5,9,6,10,4,12,12,12,12,9,8,6,11,12,12,12,12,5,6,3,5,10,2,2,2,2~4,5,7,6,2,2,2,2,9,7,6,8,12,12,12,12,10,6,7,10,2,2,2,2,6,11,9,6,12,12,12,12,7,6,9,8,6,12,12,12,12,10,3~11,2,2,2,2,10,3,8,6,12,12,12,12,4,7,6,5,9~8,3,4,12,12,12,12,7,6,9,5,2,2,2,2,3,10,6,3,11&total_bet_min=10.00';
                $response='def_s=4,8,13,7,10,6,13,5,5,9,7,9,13,4,12&balance='. $Balance .'&cfgs=5130&ver=2&index=1&balance_cash='. $Balance .'&def_sb=3,8,8,4,11&reel_set_size=2&def_sa=5,10,8,3,10&reel_set=0'.$strOtherResponse.'&balance_bonus=0.00&na='. $spinType.'&scatters=1~0,0,0,0,0~0,0,0,0,0~0,0,0,0,0&gmb=0,0,0&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"13679891",jp1:"4000",max_rnd_win:"4200",jp3:"50",jp2:"200",jp4:"20"}}&wl_i=tbm~4200&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa=5,10,8,3,10&sb=3,8,8,4,11&sc='. implode(',', $slotSettings->Bet) .'&defc=40.00&purInit_e=1&sh=3&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&st=rect&c='.$bet.'&sw=5&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;250,100,25,0,0;100,30,15,0,0;75,20,10,0,0;50,15,5,0,0;50,15,5,0,0;25,10,2,0,0;25,10,2,0,0;25,10,2,0,0;25,10,2,0,0;25,10,2,0,0;25,10,2,0,0&l=25&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=7,6,4,7,3,3,5,6,4,6,5,6,4,7,7,11,6,6,5,7,7,7,6,6,7,3,9,5,4,5,7,7,6,4,11,4,5,11,8,3,4,7,6,6,6,9,8,12,9,10,11,5,7,3,13,10,11,8,13,5,10,5,5,3,7,7,5~5,7,10,8,4,8,6,4,4,4,7,5,8,7,5,13,9,7,7,7,3,4,7,6,5,12,5,5,3,5,5,5,6,11,6,7,4,9,6,3,3,3,2,7,6,4,13,9,12,6,6,6,5,7,5,3,9,3,12,6,13,7~5,5,3,13,7,7,13,8,5,13,6,7,7,7,2,3,5,12,7,4,10,5,6,10,3,6,6,6,10,9,5,11,13,11,6,11,7,6,6,4,4,5,5,5,6,5,3,4,5,8,12,3,6,7,4,4,4,7,2,10,8,10,6,5,6,7,5,6,3,3,3,6,10,4,7,7,8,5,12,4,5,12,4,7,3~10,13,12,9,2,11,7,12,6,13,4,9,7,11,13,10,6,6,11,7,7,7,8,9,5,8,13,12,10,9,3,13,10,3,3,4,9,8,4,6,10,12,3,3,3,5,7,4,3,7,4,8,2,11,10,9,8,10,7,3,5,11,12,6,4,8~12,3,9,6,5,7,11,12,3,4,11,10,6,13,3,4,8,3,11,13,9,12,4,13,9,7,6,13,10,3,8,10,7,12,4,5,11,8,6&s='.$lastReelStr.'&t=243&reel_set1=4,11,6,4,9,6,8,3,7,5,4,9,13,7,7,5,11,5,11,6,5,7,7,7,6,5,10,4,10,7,7,4,8,12,6,5,6,10,8,3,7,6,6,4,7,6,3,5,6,6,6,11,5,4,6,4,5,7,9,7,5,6,7,3,5,5,10,7,3,7,11,8,3,7,3,5~11,4,2,3,5,3,3,4,4,4,12,7,5,6,3,6,6,5,8,7,7,7,10,13,7,3,9,13,12,5,5,5,7,4,9,12,9,7,4,6,5,3,3,3,13,5,4,7,5,8,7,6,6,6,7,6,9,7,5,6,8,4,12,4~9,12,8,7,7,6,5,4,12,5,7,7,7,5,11,5,4,10,3,2,5,6,7,12,6,6,6,5,4,8,4,6,3,6,13,7,4,5,5,5,4,7,7,10,12,13,5,7,10,3,13,4,4,4,10,3,4,3,6,10,6,7,6,3,2,3,3,3,8,11,5,6,8,7,4,13,5,5,6,7~12,9,8,9,5,4,8,13,11,8,3,3,11,9,4,6,9,10,7,7,7,6,12,3,12,9,6,6,13,6,13,7,7,2,10,12,7,4,10,11,8,3,3,3,7,9,7,5,13,5,11,10,3,12,8,4,13,11,10,4,13,3,4,3~12,3,8,6,12,10,13,10,6,10,8,6,5,11,5,3,13,7,13,11,6,7,4,3,5,10,6,7,9,6,12,7,8,9,12,3,11,8,9,10,8,4,13,11,7,3,10,9,12,9,11,12,3,4,11,4,9,4,4&purInit=[{type:"d",bet:2500}]&total_bet_min=8.00';
            }
            else if( $slotEvent['slotEvent'] == 'doCollect' || $slotEvent['slotEvent'] == 'doCollectBonus') 
            {
                $Balance = $slotSettings->GetBalance();
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);    
                $response = 'balance=' . $Balance . '&index=' . $slotEvent['index'] . '&balance_cash=' . $Balance . '&balance_bonus=0.00&na=s&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&na=s&sver=5&counter=' . ((int)$slotEvent['counter'] + 1);
                
                //------------ ReplayLog ---------------                
                $lastEvent = $slotSettings->GetHistory();
                if($lastEvent != NULL){
                    $betline = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $betline = $slotSettings->Bet[0];
                }
                $lines = 25;      
                $allBet = $betline * $lines;
                $totalWin = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if($replayLog && count($replayLog) && $totalWin >= ($allBet * 10)){
                    $current_replayLog["cr"] = $paramData;
                    $current_replayLog["sr"] = $response;
                    array_push($replayLog, $current_replayLog);

                    \VanguardLTE\Jobs\UpdateReplay::dispatch([
                        'user_id' => $userId,
                        'game_id' => $slotSettings->game->original_id,
                        'bet' => $allBet,
                        'brand_id' => config('app.stylename'),
                        'base_bet' => $allBet,
                        'win' => $totalWin,
                        'rtp' => $totalWin / $allBet,
                        'game_logs' => urlencode(json_encode($replayLog))
                    ]);
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []);
                //------------ *** ---------------
            }
            else if( $slotEvent['slotEvent'] == 'doSpin' ) 
            {
                $lastEvent = $slotSettings->GetHistory();
                $pur = -1;
                if(isset($slotEvent['pur'])){
                    $pur = $slotEvent['pur'];
                }
                $slotEvent['slotBet'] = $slotEvent['c'];
                $slotEvent['slotLines'] = 25;
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
                        $balance_cash = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                        if(!isset($balance_cash)){
                            $balance_cash = $slotSettings->GetBalance();
                        }
                        $response = 'nomoney=1&balance='. $balance_cash .'&error_type=i&index='.$slotEvent['index'].'&balance_cash='. $balance_cash .'&balance_bonus=0.00&na=s&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&ext_code=SystemError&sver=5&counter='. ((int)$slotEvent['counter'] + 1);
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

                // $winType = 'win';

                $allBet = $betline * $lines;
                if($pur >= 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $betline * $lines * 100;
                }
                $freeStacks = []; 
                $isGeneratedFreeStack = false;
                $slotSettings->SetGameData($slotSettings->slotId . 'Wins', [0,0,0,0,0,0,0,0,0,0,0,0]);
                $slotSettings->SetGameData($slotSettings->slotId . 'Status', [0,0,0,0,0,0,0,0,0,0,0,0]);
                $slotSettings->SetGameData($slotSettings->slotId . 'Level', -1);
                if($slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    $bonusMpl = $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl');
                    $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                    $isGeneratedFreeStack = true;
                }
                else
                {
                    $slotEvent['slotEvent'] = 'bet';
                    $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                    $_sum = $allBet / 100 * $slotSettings->GetPercent();
                    if($pur >= 0){                            
                        $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $_sum, $slotEvent['slotEvent'], true);
                        $winType = 'bonus';
                        $_winAvaliableMoney = $slotSettings->GetBank('bonus');
                    }else{
                        $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $_sum, $slotEvent['slotEvent']);
                    }
                    $bonusMpl = 1;
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '561' . substr($roundstr, 4, 10);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $leftFreeGames = 0;

                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []);
                }
                
                $wild = '2';
                $scatter = '1';
                $Balance = $slotSettings->GetBalance();
                $totalWin = 0;
                $lineWins = [];
                $lineWinNum = [];
                $wildValues = [];
                $wildPoses = [];
                $strWinLine = '';
                $winLineCount = 0;
                $str_initReel = '';
                $str_trail = '';
                $whi = 0;
                $str_whm = '';
                $str_whw = '';
                $fsmore = 0;
                $str_stf = '';
                $str_wmt = '';
                $wmv = '';
                $bw = -1;
                $wi = -1;
                $subScatterReel = null;
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    
                    $str_initReel = $stack['initReel'];
                    $currentReelSet = $stack['reel_set'];
                    $str_trail = $stack['trail'];
                    $whi = $stack['whi'];
                    $str_whm = $stack['whm'];
                    $str_whw = $stack['whw'];
                    $fsmore = $stack['fsmore'];
                    $str_stf = $stack['stf'];
                    $str_wmt = $stack['wmt'];
                    $wmv = $stack['wmv'];
                    $bw = $stack['bw'];
                    $wi = $stack['wi'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $pur, $betline * $lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $lastReel = explode(',', $stack[0]['reel']);
                    $str_initReel = $stack[0]['initReel'];
                    $currentReelSet = $stack[0]['reel_set'];
                    $str_trail = $stack[0]['trail'];
                    $whi = $stack[0]['whi'];
                    $str_whm = $stack[0]['whm'];
                    $str_whw = $stack[0]['whw'];
                    $fsmore = $stack[0]['fsmore'];
                    $str_stf = $stack[0]['stf'];
                    $str_wmt = $stack[0]['wmt'];
                    $wmv = $stack[0]['wmv'];
                    $bw = $stack[0]['bw'];
                    $wi = $stack[0]['wi'];
                }


                $reels = [];
                for($i = 0; $i < 5; $i++){
                    $reels[$i] = [];
                    for($j = 0; $j < 3; $j++){
                        $reels[$i][$j] = $lastReel[$j * 5 + $i];
                    }
                }
                $tmp_whm = '';
                $tmp_whw = -1;
                if($str_trail != '' && explode('~', $str_trail)[0] == 'wh_win'){
                    $tmp_whm = explode(',', $str_whm)[$whi];
                    $tmp_whw = explode(',', $str_whw)[$whi];
                }
                for($r = 0; $r < 3; $r++){
                    if($reels[0][$r] != $scatter){
                        $this->findZokbos($reels, $reels[0][$r], 1, [($r * 5)]);
                    }                        
                }
                $uniqueFirstSymbols = array_unique(
                    array_map(function ($line) {return $line['FirstSymbol'];}, $this->winLines)
                );
                $gwm = 1;
                $apv = 0;
                if($tmp_whm == 'mp'){
                    $gwm = $tmp_whw;
                }else if($tmp_whm == 'cr'){
                    $apv = $tmp_whw;
                    $totalWin = $apv * $betline;
                }else if($tmp_whm == 'fs'){
                    $fsmore = $tmp_whw;
                }
                $wlc_vs = [];
                foreach ($uniqueFirstSymbols as $idx => $symbol) {
                    $dupLines = array_filter($this->winLines, function($line) use ($symbol) {return $line['FirstSymbol'] === $symbol;});
                    $uniqueMulSymbols = array_unique(
                        array_map(function ($line) {return $line['Mul'];}, $dupLines)
                    );
                    foreach($uniqueMulSymbols as $mul){
                        $dupMulLines = array_filter($dupLines, function($line) use ($mul) {return $line['Mul'] === $mul;});
                        // 윈라인 심볼위치배열
                        $symbols = array_unique(array_flatten(array_map(function($line) {return $line['Positions'];}, $dupMulLines)));
                        $strSymbols = implode(',', $symbols);
                        // 윈라인 응답
                        $dupCount = count($dupMulLines);
                        if($dupCount > 0){
                            $firstLineKey = array_key_first($dupMulLines);
                            $winLine = $dupMulLines[$firstLineKey];
                            $winLineMoney = $slotSettings->Paytable[$symbol][$winLine['RepeatCount']] * $betline * $gwm * $dupCount;
                            $totalWin += $winLineMoney;
                            $strWinLine = "{$symbol}~{$winLineMoney}~{$dupCount}~{$winLine['RepeatCount']}~{$strSymbols}~l" . ($gwm > 1 ? "~" . $gwm : "");
                            $wlc_vs[] = $strWinLine;
                        }
                    }
                }
                $spinType = 's';
                if( $totalWin > 0) 
                {
                    $spinType = 'c';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }
                $_obf_totalWin = $totalWin;
                if( $fsmore > 0) 
                {
                    if($slotEvent['slotEvent'] != 'freespin'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $fsmore);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                    }else{
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                    }
                }
                
                $strLastReel = implode(',', $lastReel);
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 5; $i++){
                    $reelA[$i] = mt_rand(7, 10);
                    $reelB[$i] = mt_rand(7, 10);
                }
                $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
               
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $strOtherResponse = '';
                $isState = true;
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $spinType = 's';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $isEnd = false;
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 && $tmp_whm != 'jp') 
                    {
                        $isEnd = true;
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsend_total=1&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 'c';
                    }
                    else
                    {
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 's';
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                    }
                    if($fsmore > 0){
                        $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                    if($fsmore > 0 ){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1';
                        
                        if($pur >= 0){
                            $strOtherResponse = $strOtherResponse . '&purtr=1&puri=' . $pur;
                        }
                    }
                }
                if($tmp_whm == 'cr'){
                    $strOtherResponse = $strOtherResponse . '&apwa=' . ($apv * $betline) . '&apt=ma&apv=' . $apv;
                }else if($tmp_whm == 'mp'){
                    $strOtherResponse = $strOtherResponse . '&gwm='. $tmp_whw;
                }else if($tmp_whm == 'jp'){
                    $spinType = 'b';
                    $isState = false;
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 50);
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($whi >= 0){
                    $strOtherResponse = $strOtherResponse . '&whi=' . $whi;
                }
                if($str_whm != ''){
                    $strOtherResponse = $strOtherResponse . '&whm=' . $str_whm;
                }
                if($str_whw != ''){
                    $strOtherResponse = $strOtherResponse . '&whw=' . $str_whw;
                }
                if($str_trail != ''){
                    $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                }
                if($str_stf != ''){
                    $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                }
                if($str_wmt != ''){
                    $strOtherResponse = $strOtherResponse . '&wmt=' . $str_wmt;
                }
                if($wmv >= 0){
                    $strOtherResponse = $strOtherResponse . '&wmv=' . $wmv;
                }
                if($bw >= 0){
                    $strOtherResponse = $strOtherResponse . '&bw=' . $bw;
                }
                if($wi >= 0){
                    $strOtherResponse = $strOtherResponse . '&wi=' . $wi;
                }
                if(count($wlc_vs) > 0){
                    $strOtherResponse = $strOtherResponse . '&wlc_v=' . implode(';', $wlc_vs);
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&c='.$betline.'&sw=5&sver=5&reel_set='.$currentReelSet.'&counter='. ((int)$slotEvent['counter'] + 1) .'&l=25&s='.$strLastReel.'&w='.$totalWin;
                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 && $tmp_whm != 'jp') 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
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

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"Level":' . $slotSettings->GetGameData($slotSettings->slotId . 'Level') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"Wins":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'Wins')) . ',"Status":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'Status')) .',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($slotEvent['slotEvent'] == 'freespin' && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * 100;
                }
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if( $fsmore > 0 && $slotEvent['slotEvent'] != 'freespin') 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                }
            }else if( $slotEvent['slotEvent'] == 'doBonus' ){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lines = 25;
                $bgt = $slotSettings->GetGameData($slotSettings->slotId . 'Bgt');
                $level = $slotSettings->GetGameData($slotSettings->slotId . 'Level');
                $new_wins = $slotSettings->GetGameData($slotSettings->slotId . 'Wins');
                $new_status = $slotSettings->GetGameData($slotSettings->slotId . 'Status');
                $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');

                $ind = -1;
                if(isset($slotEvent['ind'])){
                    $ind = $slotEvent['ind'];
                }
                $level++;
                $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                $lastReel = explode(',', $stack['reel']);                  
                $str_initReel = $stack['initReel'];
                $currentReelSet = $stack['reel_set'];
                $str_trail = $stack['trail'];
                $whi = $stack['whi'];
                $str_whm = $stack['whm'];
                $str_whw = $stack['whw'];
                $str_stf = $stack['stf'];
                $str_wmt = $stack['wmt'];
                $wmv = $stack['wmv'];
                $bw = $stack['bw'];
                $wi = $stack['wi'];
                $wp = $stack['wp'];
                $wins = explode(',', $stack['wins']);
                $status = explode(',', $stack['status']);
                $wins_mask = explode(',', $stack['wins_mask']);
                $isState = false;
                $strOtherResponse = '';

                $new_wins_mask = [];
                for($k = 0; $k < count($new_wins); $k++){
                    if($new_wins[$k] == 0){
                        $new_wins_mask[$k] = 'h';
                    }else{
                        $new_wins_mask[$k] = 'jp' . $new_wins[$k];
                    }
                }
                if($ind >= 0 && $level > 0){
                    $new_wins[$ind] = $wins[$wi];
                    $new_wins_mask[$ind] = $wins_mask[$wi];
                    $new_status[$ind] = $status[$wi];
                }
                $totalWin = 0;
                $coef = $betline * $lines;
                if($wp > 0){
                    $totalWin = $wp * $coef;
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                }
                $spinType = 'b';
                if( $totalWin > 0) 
                {
                    $spinType = 'cb';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                {
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                    {
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsend_total=1&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        if($totalWin > 0){
                            $spinType = 'cb';
                            $isState = true;
                        }
                    }
                    else
                    {
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        if($totalWin > 0){
                            $spinType = 's';
                        }
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                    }
                }else
                {
                    if($totalWin > 0){
                        $isState = true;
                    }
                }
                if($totalWin > 0){
                    $strOtherResponse = $strOtherResponse . '&lifes=0&end=1&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }else{
                    $strOtherResponse = $strOtherResponse . '&lifes=1&end=0';
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'Level', $level);
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'Wins', $new_wins);
                $slotSettings->SetGameData($slotSettings->slotId . 'Status', $new_status);
                if($ind > -1){
                    $strOtherResponse = $strOtherResponse . '&wi=' . $ind . '&ch=ind~' . $ind;
                }
                if($wp > -1){
                    $strOtherResponse = $strOtherResponse . '&wp=' . $wp;
                }
                $response = 'bgid=0&trail='. $str_trail . $strOtherResponse .'&balance='. $Balance .'&wins='. implode(',', $new_wins) .'&coef='. $coef .'&level='. $level .'&index='.$slotEvent['index'].'&balance_cash='. $Balance .'&balance_bonus=0.00&na='. $spinType .'&whi='. $whi .'&whm='. $str_whm .'&status='. implode(',', $new_status) .'&rw='. $totalWin .'&whw='. $str_whw .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&bgt='. $bgt .'&sa=4,6,7,6,12&sb=3,12,4,9,10&sh=3&wins_mask='. implode(',', $new_wins_mask) .'&st=rect&sw=5&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&s=' . implode(',', $lastReel);

                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"Level":' . $slotSettings->GetGameData($slotSettings->slotId . 'Level') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"Wins":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'Wins')) . ',"Status":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'Status')) .',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * 100;
                }
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
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
        public function findZokbos($reels, $firstSymbol, $repeatCount, $positions){
            $wild = '2';
            $bPathEnded = true;
            if($repeatCount < 5){
                for($r = 0; $r < 3; $r++){
                    if($firstSymbol == $reels[$repeatCount][$r] || $reels[$repeatCount][$r] == $wild){
                        $this->findZokbos($reels, $firstSymbol, $repeatCount + 1, array_merge($positions, [($repeatCount + $r * 5)]));
                        $bPathEnded = false;
                    }
                }
            }
            if($bPathEnded == true){
                if($repeatCount >= 3){
                    $winLine = [];
                    $winLine['FirstSymbol'] = $firstSymbol;
                    $winLine['RepeatCount'] = $repeatCount;
                    $winLine['Positions'] = $positions;
                    $winLine['Mul'] = 1;
                    array_push($this->winLines, $winLine);
                }
            }
        }
    }
}

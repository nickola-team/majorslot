<?php 
namespace VanguardLTE\Games\TicTacTakePM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 10);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [6,8,8,4,7,3,9,6,6,8,4,8,9,5,7]);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $strOtherResponse = '';
                $stack = null;
                
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $lastEvent->serverResponse->CurrentRespin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', $lastEvent->serverResponse->BonusState);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);
                    if (isset($lastEvent->serverResponse->ReplayGameLogs)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', json_decode(json_encode($lastEvent->serverResponse->ReplayGameLogs), true)); //ReplayLog
                    }
                    if (isset($lastEvent->serverResponse->FreeStacks)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', json_decode(json_encode($lastEvent->serverResponse->FreeStacks), true)); // FreeStack
                        $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                        if($slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') > 0){
                            $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') - 1];
                        }
                    }
                    $bet = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $bet = '100.00';
                }
                $currentReelSet = 0;
                $spinType = 's';
                if(isset($stack)){                    
                    $currentReelSet = $stack['reel_set'];
                    $str_initReel = $stack['initReel'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $str_trail = $stack['trail'];
                    $str_stf = $stack['stf'];
                    $str_sts = $stack['sts'];
                    $str_sty = $stack['sty'];
                    $rs_more = $stack['rs_more'];
                    if($str_trail != ''){
                        $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                    }
                    if($str_stf != ''){
                        $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                    }
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($str_sts != ''){
                        $strOtherResponse = $strOtherResponse . '&sts=' . $str_sts;
                    }
                    if($str_sty != ''){
                        $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                    }
                    if($currentReelSet >= 0){
                        $strOtherResponse = $strOtherResponse . '&reel_set=' . $currentReelSet;
                    }
                    if($rs_p >= 0){
                        $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p;
                    }
                    if($rs_c > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_c=' . $rs_c;
                    }
                    if($rs_m > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_m=' . $rs_m;
                    }
                    if($rs_t > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t;
                    }
                    if($rs_more > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_more=' . $rs_more;
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') > 0){
                        $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    }
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));

                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=6,8,8,4,7,3,9,6,6,8,4,8,9,5,7&balance='. $Balance .'&cfgs=5134&ver=2&index=1&balance_cash='. $Balance .'&def_sb=5,9,3,5,8&reel_set_size=9&def_sa=5,10,9,3,8&reel_set='.$currentReelSet.$strOtherResponse.'&balance_bonus=0.00&na=s&scatters=&gmb=0,0,0&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"2163331",max_rnd_win:"2200"}}&wl_i=tbm~2200&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=5,10,9,3,8&sb=5,9,3,5,8&sc='. implode(',', $slotSettings->Bet) .'&defc=100.00&purInit_e=1&sh=3&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=&fsbonus=&st=rect&c='.$bet.'&sw=5&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;1000,100,25,0,0;500,75,15,0,0;250,50,10,0,0;50,12,5,0,0;50,12,5,0,0;50,12,5,0,0;0,0,0,0,0;0,0,0,0,0&l=10&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=6,8,4,8,3,6,6,4,8,8,8,6,6,8,4,8,8,8,6,8,8,5,8,8,7,6,4,4,8,8,6,6,6,8,8,6,8,3,6,8,6,4,6,8,4,7,8,8,6,5,6,6,8,7,8,8,8,4,4,8,8,4,8,8,8,6,6,8,6,4,3,8,8,6,8,8,8,7,5,4,8,7,6,8,4,8,4,6,6,8,6,8,8,8,7,6~9,5,7,5,5,6,7,9,5,5,7,9,10,5,7,7,4,7,7,7,7,7,5,7,7,10,5,3,5,7,7,7,5,9,7,7,7,5,7,9,9,9,5,7,7,5,7,7,9,6,7,7,7,7,10,5,10,7,5,10,10,10,7,7,3,7,10,8,9,7,7,5,5,10,8,7,7,5,5,10,7,5,5,5,7,7,7,5,7,7,5,5,5,9,5,7,5,7,7,7,8,5,9,5~6,10,6,8,8,10,6,6,10,10,6,10,6,6,6,8,9,8,6,3,8,6,8,6,8,8,6,6,8,9,9,9,6,6,7,6,9,6,6,8,8,6,8,5,6,5,10,10,10,9,8,7,6,4,10,6,6,8,4,8,6,4,4,8,8,8,6,8,8,6,8,6,9,8,9,8,6,3,9,6~5,7,7,5,5,7,8,7,7,9,7,9,7,5,10,7,7,7,7,7,5,9,8,9,9,7,7,5,7,5,7,7,10,7,5,10,9,9,9,7,10,10,7,4,7,7,5,7,7,5,7,5,10,7,9,7,10,10,10,5,7,5,7,7,5,7,7,6,7,5,7,5,5,9,5,7,5,5,5,8,5,6,7,5,7,5,7,7,3,10,7,5,7,3,5,5,6~8,5,8,4,8,8,3,8,8,6,8,6,8,4,8,8,7,6,6,6,8,7,4,4,6,4,8,8,6,8,4,6,8,3,6,8,6,6,8,8,8,6,8,6,8,8,6,6,8,8,4,8,6,7,6,4,5,8,8,7&s='.$lastReelStr.'&reel_set2=3,5,4,8,7,5,4,7,6,7,5,5,3,6,7~4,8,9,8,6,10,10,10,5,10,8,4,9,9,9,3,9,10,9,7,10,4~10,9,9,5,10,10,10,3,8,4,6,3,9,9,9,10,9,5,7,4,10~9,7,9,8,10,10,10,3,10,4,10,9,6,9,9,9,4,10,8,5,9,9,10,10~3,5,6,7,5,7,8,4,5,4,6,5,7&reel_set1=7,5,5,7,7,5,5,7,7,5,6,7,5,5,7,7,7,5,7,8,3,7,7,7,7,7,8,4,7,5,4,5,5,7,5,7,3,7,7,7,5,4,7,6,5,5,5,7,5,5,5,7,3,7,7,7,7,7,5,7,7,7,7,7,5,5,5,8,6~3,6,8,8,10,8,8,6,6,8,8,9,6,8,4,9,10,4,6,6,5,6,8,8,10,8,9,9,9,8,6,4,7,8,8,7,6,6,8,6,8,5,8,8,9,6,3,4,4,8,10,8,4,8,10,10,6,10,10,10,3,9,6,8,8,4,8,8,6,8,8,4,9,8,8,4,5,9,7,8,6,10,4,8,9,8,4,8,4~5,5,7,5,4,4,7,9,6,10,6,6,5,5,5,4,10,8,5,8,7,7,6,5,7,5,5,9,6,9,9,9,5,10,7,4,9,6,7,3,9,7,9,7,5,5,10,10,10,7,8,5,3,7,6,7,6,5,5,7,5,5,6,7,7,7,7,5,10,8,10,10,7,7,5,6,7,7,6,9,7~4,8,6,8,7,8,6,9,8,6,8,6,10,8,3,6,8,8,6,9,8,4,8,10,9,9,9,8,9,10,4,5,8,8,6,6,3,8,4,6,6,8,7,4,5,8,6,6,4,5,4,7,10,10,10,8,8,4,8,8,3,8,8,4,4,8,8,9,10,9,8,10,10,8,8,6,8,6,8,8,6,4~7,7,5,7,7,5,6,3,7,5,7,5,7,7,5,7,7,5,5,7,7,7,7,7,6,7,5,7,7,5,7,7,5,7,8,5,7,5,7,5,7,5,7,8,5,5,5,7,7,5,7,7,5,7,8,7,3,7,5,7,5,5,7,5,5,4,7,5&reel_set4=5,6,5,7,5,5,5,6,7,6,7,7,7,7,3,7,6,7,7,5,3,3,3,7,7,5,3,6,3,4~4,8,6,8,8,6,4,6,4,8,8,4,6,8,4,8,8,6,8,8,6,8,8,8,9,8,9,4,8,8,6,9,8,8,6,4,8,8,4,8,4,8,8,4,4,6~7,8,7,5,7,8,7,3,7,7,7,7,8,7,10,8,7,8,5,7,8,7,5,5,5,8,7,8,7,10,5,8,10,7,3,3,3,5,7,3,7,7,3,8,5,7,7,8~8,6,4,6,8,6,4,8,8,4,8,8,6,4,8,4,6,8,8,6,10,4,8,8,6,6,4,9,8,8,4,4,8,9,8,10,8~7,3,6,5,7,6,7,5,7,5,5,5,7,5,6,7,7,6,6,7,6,7,7,7,7,3,7,7,6,7,7,6,5,4,7,3,3,3,7,3,7,7,5,3,5,5,7,6,5&purInit=[{type:"d",bet:1000}]&reel_set3=5,7,7,3,7,5,5,5,4,6,7,7,5,7,5,7,7,7,7,6,7,3,6,5,5,7,3,3,3,7,6,7,7,3,6,3,6~8,4,9,8,6,6,4,8,4,8,8,4,8,8,10,8,4,6,8,4,6,6,9,8,9,8,4,8,8,4,8,4,6,8,10,4,4,8,6,8,8,6,6,8~5,8,7,5,3,8,7,7,7,7,3,7,10,5,7,3,7,8,8,7,5,5,5,7,8,7,8,7,7,8,8,7,3,3,3,8,5,10,7,8,7,10,5,8,7,7~6,8,4,8,9,8,4,6,8,6,4,6,6,8,8,8,4,8,8,6,8,8,9,4,8,8,4,8,8,4,8,8~6,7,7,5,5,5,6,7,7,5,5,7,7,7,7,6,7,5,3,6,7,3,3,3,4,7,7,6,3,3,7&reel_set6=8,4,3,3,8,3,3,3,4,4,3,6,8,4,8,4,4,4,5,3,7,3,5,8,3,5,5,5,8,4,6,8,6,5,8,8,8,5,7,6,5,4,8,7,6~5,7,7,3,7,10,5,5,5,8,6,9,7,5,3,9,4,4,4,7,7,8,5,7,10,3,3,3,8,4,10,4,10,9,7,5~6,6,5,8,3,5,5,5,4,6,8,10,6,4,4,4,9,8,6,10,8,3,3,3,6,6,4,4,8,6,6,6,8,8,9,6,4,9,8,8,8,3,8,6,10,9,9,6,10~7,8,3,10,7,5,5,5,8,7,4,7,5,4,4,4,9,5,10,9,3,5,3,3,3,7,8,6,5,9,10,7~8,4,8,8,3,3,3,8,5,7,7,5,5,8,4,4,4,8,4,4,3,6,6,5,5,5,8,5,6,3,4,6,3,8,8,8,5,7,4,3,6,3,8&reel_set5=4,7,4,8,4,7,6,4,4,4,8,6,8,4,6,8,8,3,8,8,8,6,5,8,5,8,6,6,5,5,5,8,8,4,8,8,6,8,8,3,3,3,6,8,6,8,7,8,8,5,6,6,6,7,6,8,8,3,8,5,6,7,7,7,8,6,8,7,6,6,4,8,8~4,3,10,7,10,3,3,8,7,7,5,5,5,10,8,9,7,9,10,8,7,7,9,7,4,4,4,7,5,5,6,10,9,10,7,10,4,7,8,3,3,3,6,10,8,9,5,7,8,9,7,7,9,9,9,7,5,7,10,9,7,8,10,7,9,10,10,10,5,7,3,7,10,10,8,10,7,7,9,7~4,3,6,9,5,5,5,8,10,9,9,10,9,4,4,4,9,6,9,8,6,10,3,3,3,6,6,4,6,10,6,6,6,6,10,8,3,8,8,8,8,5,8,6,10,6,10,9,9,9,10,5,6,7,8,10,10,10,6,5,8,4,6,8,6~10,8,6,9,10,7,7,3,9,8,7,5,5,5,9,10,3,7,7,9,7,7,5,9,3,9,4,4,4,7,5,10,4,7,10,7,7,3,10,10,7,7,3,3,3,10,10,7,7,8,10,9,9,7,5,9,10,9,9,9,8,9,8,7,10,7,7,9,8,7,10,5,10,10,10,4,10,10,7,8,7,7,6,5,9,4,5,7,8~8,6,8,8,7,6,4,4,4,7,8,6,3,6,4,8,8,8,6,8,8,6,5,4,8,5,5,5,7,8,8,6,8,5,8,8,3,3,3,6,8,8,5,8,4,8,6,6,6,4,8,4,4,8,6,8,7,7,7,3,6,5,8,8,6,6,7,7&reel_set8=3,4,5,5,3,3,5,8,4,3,4,3,8,3,3,3,4,3,4,5,7,4,6,8,4,8,3,4,3,7,6~5,5,7,3,4,3,5,5,5,6,8,7,7,4,3,6,8,6,4,4,4,5,6,7,7,5,7,8,7,8,3,3,3,7,4,5,4,7,7,3,3,8,3~5,4,8,4,5,5,5,6,5,3,3,6,6,4,4,4,6,4,8,3,4,3,3,3,6,4,6,4,8,6,6,6,8,6,5,4,8,8,8,4,6,6,8,4,3~3,4,7,3,8,7,4,7,5,5,5,3,3,8,5,3,3,6,4,4,8,4,4,4,5,7,7,4,3,4,3,5,7,3,3,3,5,5,7,5,3,7,7,4,8,4,7~5,3,5,5,8,4,3,4,6,8,4,8,3,8,3,3,3,4,3,8,7,4,3,4,5,3,7,6,4,3,5,3,4&reel_set7=6,8,7,6,7,6,8,7,8,8,8,6,8,6,7,6,7,7,8,8,7,7,7,7,6,7,6,7,7,6,6,7,6,6,6,7,6,6,7,8,7,8,6,7,8,7~6,8,5,8,5,5,5,4,5,6,8,5,8,4,4,4,8,4,6,8,8,4,8,8,8,8,6,8,4,8,8,5~5,7,7,5,7,3,7,7,7,7,7,7,5,7,5,3,7,7,5,3,5,5,5,3,7,5,3,5,7,5,5,4,3,3,3,3,7,7,4,5,7,7,4,7,4,4,4,7,7,3,4,7,3,5,7,7~5,8,8,4,8,8,4,5,5,5,6,6,8,8,5,8,6,8,4,4,4,6,8,8,4,8,5,8,8,8,8,4,8,5,5,6,8,6,4,8~7,6,7,7,8,8,8,6,7,7,6,6,8,6,8,7,7,7,7,6,7,6,8,8,7,7,6,6,6,7,7,6,7,7,6,7,8,6&total_bet_min=20.00';
            }
            else if( $slotEvent['slotEvent'] == 'doCollect' || $slotEvent['slotEvent'] == 'doCollectBonus') 
            {
                $Balance = $slotSettings->GetBalance();
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);    
                $response = 'balance=' . $Balance . '&index=' . $slotEvent['index'] . '&balance_cash=' . $Balance . '&balance_bonus=0.00&na=s&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sver=5&counter=' . ((int)$slotEvent['counter'] + 1);
                
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
            else if( $slotEvent['slotEvent'] == 'doSpin' || $slotEvent['slotEvent'] == 'doBonus' ) 
            {
                $lastEvent = $slotSettings->GetHistory();
                $linesId = [];
                $linesId[0] = [1, 1, 1, 1, 1];
                $linesId[1] = [2, 2, 2, 2, 2];
                $linesId[2] = [3, 3, 3, 3, 3];
                $linesId[3] = [1, 2, 3, 2, 1];
                $linesId[4] = [3, 2, 1, 2, 3];
                $linesId[5] = [1, 1, 2, 3, 3];
                $linesId[6] = [3, 3, 2, 1, 1];
                $linesId[7] = [2, 1, 1, 1, 2];
                $linesId[8] = [2, 3, 3, 3, 2];
                $linesId[9] = [1, 2, 2, 2, 1];
                $pur = -1;
                if(isset($slotEvent['pur'])){
                    $pur = $slotEvent['pur'];
                }
                $slotEvent['slotBet'] = $slotEvent['c'];
                $slotEvent['slotLines'] = 10;
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') >= 0) 
                {
                    $slotEvent['slotEvent'] = 'respin';
                }
                $lines = $slotEvent['slotLines'];
                $betline = $slotEvent['slotBet'];
                if( $slotEvent['slotEvent'] == 'doSpin' || $slotEvent['slotEvent'] == 'respin' ) 
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
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') < $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotEvent['slotEvent'] == 'respin' ) 
                    {
                        $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"invalid bonus state"}';
                        exit( $response );
                    }
                    if($slotEvent['slotEvent'] == 'respin'){
                        if ($lastEvent->serverResponse->bet != $betline){
                            $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"invalid Bets"}';
                        exit( $response );
                        }
                    }
                }
                
                $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $betline * $lines, $lines);
                $winType = $_spinSettings[0];
                $_winAvaliableMoney = $_spinSettings[1];
                $allBet = $betline * $lines;
                if($pur >= 0 && $slotEvent['slotEvent'] != 'respin'){
                    $allBet = $betline * $lines * 100;
                }
                $freeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'respin'){
                    // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '4174458' . substr($roundstr, 4, 10);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $leftFreeGames = 0;

                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []);
                }
                
                $wild = '2';
                $scatter = '1';
                $Balance = $slotSettings->GetBalance();
                
                $totalWin = 0;
                $winLineCount = 0;
                $str_initReel = '';
                $rs_p = -1;
                $rs_c = -1;
                $rs_m = -1;
                $rs_t = -1;
                $str_trail = '';
                $str_stf = '';
                $str_sts = '';
                $str_sty = '';
                $rs_more = 0;
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $str_initReel = $stack['initReel'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $str_trail = $stack['trail'];
                    $str_stf = $stack['stf'];
                    $str_sts = $stack['sts'];
                    $str_sty = $stack['sty'];
                    $rs_more = $stack['rs_more'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $pur, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $stack);  
                    $lastReel = explode(',', $stack[0]['reel']);
                    $currentReelSet = $stack[0]['reel_set'];
                    $str_initReel = $stack[0]['initReel'];
                    $rs_p = $stack[0]['rs_p'];
                    $rs_c = $stack[0]['rs_c'];
                    $rs_m = $stack[0]['rs_m'];
                    $rs_t = $stack[0]['rs_t'];
                    $str_trail = $stack[0]['trail'];
                    $str_stf = $stack[0]['stf'];
                    $str_sts = $stack[0]['sts'];
                    $str_sty = $stack[0]['sty'];
                    $rs_more = $stack[0]['rs_more'];
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                $reels = [];
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;
                for($i = 0; $i < 5; $i++){
                    $reels[$i] = [];
                    $wildReels[$i] = [];
                    for($j = 0; $j < 3; $j++){
                        $reels[$i][$j] = $lastReel[$j * 5 + $i];
                    }
                }
                $strWinLine = '';
                for($i = 0; $i < 2; $i++){
                    $def_index = 0;
                    if($i == 1){
                        $def_index = 4;
                    }
                    $lineWins = [];
                    $lineWinNum = [];
                    $_lineWinNumber = 1;
                    $_obf_winCount = 0;
                    for( $k = 0; $k < $lines; $k++ ) 
                    {
                        $_lineWin = '';
                        $firstEle = $reels[$def_index - 0][$linesId[$k][$def_index - 0] - 1];
                        $lineWinNum[$k] = 1;
                        $lineWins[$k] = 0;
                        $wildWin = 0;
                        $wildWinNum = 1;
                        $mul = 0; 
                        for($j = 1; $j < 5; $j++){
                            $ele = $reels[abs($def_index - $j)][$linesId[$k][abs($def_index - $j)] - 1];
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
                                        $strWinLine = $strWinLine . '~' . (($linesId[$k][abs($def_index - $kk)] - 1) * 5 + abs($def_index - $kk));
                                    }
                                }
                            }else{
                                if($slotSettings->Paytable[$firstEle][$lineWinNum[$k]] > 0){
                                    $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                    $totalWin += $lineWins[$k];
                                    $_obf_winCount++;
                                    $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                    for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                        $strWinLine = $strWinLine . '~' . (($linesId[$k][abs($def_index - $kk)] - 1) * 5 + abs($def_index - $kk));
                                    }   
    
                                }else{
                                    $lineWinNum[$k] = 0;
                                }
                                break;
                            }
                        }
                    }
                }
                
                $spinType = 's';
                if( $totalWin > 0) 
                {
                    $spinType = 'c';
                    $slotSettings->SetBalance($totalWin);
                    if($winType == 'bonus' || $rs_p >= 0){
                        $slotSettings->SetBank('bonus', -1 * $totalWin);
                    }else{
                        $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                    }
                }
                $_obf_totalWin = $totalWin;
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $rs_p);

                $strLastReel = implode(',', $lastReel);
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 5; $i++){
                    $reelA[$i] = mt_rand(8, 10);
                    $reelB[$i] = mt_rand(8, 10);
                }
                $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
               
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                
                $strOtherResponse = '';
                $isState = true;
                $isEnd = true;
                if( $slotEvent['slotEvent'] == 'respin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $isEnd = false;
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    
                    if( $rs_t > 0 ) 
                    {
                        $spinType = 'c';
                        $isEnd = true;
                    }
                    else
                    {
                        $isState = false;
                        $spinType = 's';
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin); 
                    if($rs_p >= 0 ){
                        $isState = false;
                        $spinType = 's';
                        if($pur >= 0){
                            $strOtherResponse = $strOtherResponse . '&purtr=1&puri=' . $pur;
                        }
                    }
                }
                
                if($str_trail != ''){
                    $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                }
                if($str_stf != ''){
                    $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($str_sts != ''){
                    $strOtherResponse = $strOtherResponse . '&sts=' . $str_sts;
                }
                if($str_sty != ''){
                    $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                }
                if($currentReelSet >= 0){
                    $strOtherResponse = $strOtherResponse . '&reel_set=' . $currentReelSet;
                }
                if($rs_p >= 0){
                    $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p;
                }
                if($rs_c > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_c=' . $rs_c;
                }
                if($rs_m > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_m=' . $rs_m;
                }
                if($rs_t > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t;
                }
                if($rs_more > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_more=' . $rs_more;
                }
                
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&ls=0&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .$strWinLine .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&st=rect&l=10&sw=5&sh=3&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&s='.$strLastReel.'&w='.$totalWin;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)) 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"CurrentRespin":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'). ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($slotEvent['slotEvent'] == 'respin' && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * 100;
                }
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if($rs_p == 0 && $slotEvent['slotEvent'] != 'respin') 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                }

            }
            if($slotEvent['action'] == 'doSpin' || $slotEvent['action'] == 'doCollect' || $slotEvent['action'] == 'doBonus' || $slotEvent['action'] == 'doCollectBonus'){
                $this->saveGameLog($slotEvent, $response, $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'), $slotSettings);
            }
            try{
                $slotSettings->SaveGameData();
                \DB::commit();
            }catch (\Exception $e) {
                $slotSettings->InternalError('TicTacTakeDBCommit : ' . $e);
                \DB::rollBack();
                $response = 'unlogged';
            }
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

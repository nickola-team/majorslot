<?php 
namespace VanguardLTE\Games\SantasWonderlandPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [3,4,5,6,7,8,7,6,8,7,6,5,4,3,4,5,3,4,5,6,7,8,7,6,8,7,6,5,4,3,4,5,3,4,5,6,7,8,7,6,8,7,6,5,4,3,4,5,3,4,5,6,7,8,7,6,8,7,6,5,4,3,4,5]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $strOtherResponse = '';
                $currentReelSet = 0;
                $stack = null;
                
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $lastEvent->serverResponse->TumbWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', $lastEvent->serverResponse->TumbleState);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);
                    if (isset($lastEvent->serverResponse->ReplayGameLogs)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', json_decode(json_encode($lastEvent->serverResponse->ReplayGameLogs), true)); //ReplayLog
                    }
                    if (isset($lastEvent->serverResponse->TumbAndFreeStacks)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', json_decode(json_encode($lastEvent->serverResponse->TumbAndFreeStacks), true)); // FreeStack

                        $FreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                        $stack = $FreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') -1];
                    }
                    $bet = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $bet = '50.00';
                }
                $spinType = 's';
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') > 0){
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }
                if(isset($stack)){
                    $str_initReel = $stack['initReel'];
                    $str_tmb = $stack['tmb'];
                    $str_trail = $stack['trail'];
                    $str_ds = $stack['ds'];
                    $str_dsa = $stack['dsa'];
                    $str_dsam = $stack['dsam'];
                    $str_accm = $stack['accm'];
                    $acci = $stack['acci'];
                    $str_accv = $stack['accv'];
                    $str_rs = $stack['rs'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $rs_more = $stack['rs_more'];
                    $wmv = $stack['wmv'];
                    $msr = $stack['msr'];
                    $str_sty = $stack['sty'];
                    $str_rwd = $stack['rwd'];
                    $currentReelSet = $stack['reel_set'];
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                    }
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($str_trail != ''){
                        $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                    }
                    if($str_tmb != ''){
                        $strOtherResponse = $strOtherResponse . '&tmb=' . $str_tmb;
                    }
                    if($str_ds != ''){
                        $strOtherResponse = $strOtherResponse . '&ds=' . $str_ds;
                    }
                    if($str_dsa != ''){
                        $strOtherResponse = $strOtherResponse . '&dsa=' . $str_dsa;
                    }
                    if($str_dsam != ''){
                        $strOtherResponse = $strOtherResponse . '&dsam=' . $str_dsam;
                    }
                    if($str_accm != ''){
                        $strOtherResponse = $strOtherResponse . '&accm=' . $str_accm;
                    }
                    if($str_accv != ''){
                        $strOtherResponse = $strOtherResponse . '&accv=' . $str_accv;
                    }
                    if($acci >= 0){
                        $strOtherResponse = $strOtherResponse . '&acci=' . $acci;
                    }
                    if($str_rs != ''){
                        $strOtherResponse = $strOtherResponse . '&rs=' . $str_rs;
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
                    if($wmv > 0){
                        $strOtherResponse = $strOtherResponse . '&wmt=pr&wmv=' . $wmv;
                        if($wmv > 1){
                            $strOtherResponse = $strOtherResponse . '&gwm=' . $wmv;
                        }
                    }
                    if($msr > 0){
                        $strOtherResponse = $strOtherResponse . '&msr=' . $msr;
                    }
                    if($msr != ''){
                        $strOtherResponse = $strOtherResponse . '&msr=' . $msr;
                    }
                    if($str_sty != ''){
                        $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                    }
                    if($str_rwd != ''){
                        $strOtherResponse = $strOtherResponse . '&rwd=' . $str_rwd;
                    }
                }else{
                    $strOtherResponse = $strOtherResponse . '&accm=cp~tp~lvl~r&acci=0&accv=0~140~0~0';
                }
                $Balance = $slotSettings->GetBalance();    
                $response = 'msi=16~17&def_s=3,4,5,6,7,8,7,6,8,7,6,5,4,3,4,5,3,4,5,6,7,8,7,6,8,7,6,5,4,3,4,5,3,4,5,6,7,8,7,6,8,7,6,5,4,3,4,5,3,4,5,6,7,8,7,6,8,7,6,5,4,3,4,5&balance='. $Balance .'&cfgs=5155&ver=2&index=1&balance_cash='. $Balance .'&def_sb=8,7,6,5,4,3,4,5&reel_set_size=2&def_sa=3,4,5,6,7,8,7,6&reel_set='. $currentReelSet .'&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0,0,0,0~0,0,0,0,0,0,0,0~1,1,1,1,1,1,1,1&gmb=0,0,0&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"250000000",max_rnd_win:"7500"}}&wl_i=tbm~7500&stime='. floor(microtime(true) * 1000) . '&sa=3,4,5,6,7,8,7,6&sb=8,7,6,5,4,3,4,5&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=50.00&purInit_e=1&sh=8&wilds=2~0,0,0,0,0,0,0,0~1,1,1,1,1,1,1,1&bonuses=0&fsbonus=&c='. $bet .'&sver=5&counter=2&paytable=0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,10000,2000,2000,2000,2000,2000,500,500,500,500,500,250,250,250,125,125,125,50,25,15,10,0,0,0,0;5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,5000,1000,1000,1000,1000,1000,250,250,250,250,250,125,125,125,75,75,75,25,12,7,5,0,0,0,0;3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,3000,500,500,500,500,500,150,150,150,150,150,75,75,75,37,37,37,12,7,5,3,0,0,0,0;1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,1500,250,250,250,250,250,100,100,100,100,100,50,50,50,25,25,25,7,4,3,2,0,0,0,0;1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,200,200,200,200,200,75,75,75,75,75,35,35,35,20,20,20,6,4,3,2,0,0,0,0;750,750,750,750,750,750,750,750,750,750,750,750,750,750,750,750,750,750,750,750,750,750,750,750,750,750,750,750,750,750,750,750,750,750,750,750,750,750,750,750,150,150,150,150,150,50,50,50,50,50,25,25,25,15,15,15,5,4,3,2,0,0,0,0;500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,100,100,100,100,100,37,37,37,37,37,20,20,20,10,10,10,4,3,2,1,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0;0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0&l=20&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=8,9,7,9,9,8,8,9,7,9,9,8,4,8,9,9,3,7,7,4,7,5,3,7,8,4,7,5,5,9,8,8,7,4,4,5,6,6,4,6,6,7,3,7,8,5,6,7,8,5,8,8,6,7,7,6,8,3,9,4,4,5,5,5,8,4,7,6,7,4,9,5,9,7,8,7,7,8,7,3,5,7,8,3,9,4,5,3,8,7,8,9,3,3,4,8,4,9,4,3,9~9,6,6,5,5,8,6,8,8,7,8,5,8,3,8,3,6,6,8,8,7,6,8,4,5,6,9,6,9,6,8,8,6,8,6,6,8,4,8,8,5,5,7,5,8,5,7,3,5,8,5,8,5,3,5,3,7,8,9,6,6,8,4,6,6,8,3,6,3,3,5,3,5,8,3,6,7,7,5,7,7,7,8,9,6,6,3,5,8,3,8,4,6,4,9,9,4,4,6~6,7,9,7,9,4,7,9,4,4,4,8,4,7,6,8,6,7,7,7,7,6,6,8,9,9,9,5,5,9,7,9,4,7,9,6,8,9,9,7,9,4,5,4,3,3,9,6,6,7,4,4,9,3,9,5,7,6,4,7,9,7,7,4,7,8,8,7,9,4,7,7,9,9,4,4,6,9,9,7,4,9,3,8,7,5,8,4,9,5,9,4,7,9,7,8,8,3,9,6,7,5,7~8,7,8,8,5,8,6,6,5,6,6,8,6,5,8,5,8,3,8,9,5,6,8,8,6,6,7,7,3,6,8,5,9,6,3,8,9,6,8,8,6,6,5,6,6,5,3,8,6,3,8,3,6,5,8,7,8,4,4,8,3,9,9,7,6,5,5,4,8,4,7,8,8,3,6,6,4,5,5,8,4,6,3,6,3,3,9,7,5,8,3,8,5,7,7,7,3,5,9~7,4,8,7,8,9,9,8,3,8,8,5,4,8,8,6,6,8,4,8,7,9,9,3,9,5,7,9,8,4,3,9,8,4,7,8,7,4,3,4,9,5,3,7,7,3,7,3,3,7,6,4,3,7,6,7,5,5,5,8,9,4,4,7,7,5,9,5,8,9,7,7,5,5,7,8,5,7,9,9,8,9,6,5,8,3,7,8,4,5,7,9,6,6,8,8,6,7,4,4,9,3,8~7,7,9,4,7,9,7,9,7,7,7,7,5,5,9,7,8,4,7,6,9,9,6,6,5,4,9,7,3,6,6,7,4,4,7,8,8,3,6,9,7,9,4,4,4,8,8,9,4,5,9,5,4,4,9,6,6,4,5,7,8,7,7,4,7,8,7,4,7,6,8,7,6,9,6,9,9,3,3,7,8,9,5,6,9,4,5,6,7,9,4,9,9,7,8,9,4,7,9,9,9,4,3,9~3,6,8,6,6,5,6,8,3,8,5,7,7,6,6,5,7,8,6,4,7,7,7,5,8,8,3,6,6,7,8,5,5,4,3,9,8,5,8,8,3,8,4,3,8,8,4,3,6,6,4,6,5,9,8,8,6,6,8,7,4,4,5,8,6,5,9,8,6,6,3,5,3,9,9,6,8,5,8,6,7,9,7,3,3,8,5,8,6,5,8,3,6,8,3,3,6,6,9,8,6~3,9,5,3,8,3,8,6,7,8,4,8,7,8,6,8,7,8,8,7,5,6,6,7,9,8,8,6,9,9,7,7,9,9,7,3,3,7,4,9,8,4,4,9,7,8,9,7,4,3,8,5,7,7,6,6,9,7,9,6,8,7,3,8,4,5,3,5,8,5,5,4,9,8,7,4,8,7,7,4,8,7,4,5,9,5,5,5,4,3,5,3,9,7,9,9,3,8,8,4,4&s='.$lastReelStr.'&accInit=[{id:0,mask:"cp;tp;lvl;r"},{id:1,mask:"cp;tp;lvl;r"}]&t=stack&reel_set1=7,8,8,9,8,3,8,4,8,3,6,6,7,9,7,4,9,8,9,7,4,9,4,4,5,5,9,5,4,8,7,8,8,9,7,7,9,9,8,9,7,7,5,4,7,9,5,8,7,3,5,6,7,7,8,7,6,4,7,4,7,8,5,5,5,3,8,3,7,9,6,5,7,3,7,9,9,8,4,4,3,8,9,9,8,7,6,3,3,4,5,7,5,8,8,3,4,6,6,8~9,7,9,7,9,6,7,4,9,5,9,9,9,7,9,5,5,7,5,7,9,9,6,9,8,4,7,5,6,3,7,9,9,6,6,9,9,4,9,7,4,6,4,8,9,7,9,8,4,4,9,7,5,9,8,8,6,6,4,4,7,5,4,6,4,4,4,8,7,7,4,7,9,7,4,7,8,6,3,6,6,4,9,3,5,7,6,4,9,3,3,9,8,8,7,7,7,7,9,7,7,9,7,8,4~5,6,7,6,6,8,6,6,5,6,8,3,8,3,8,9,8,4,8,8,3,6,3,4,3,4,4,5,8,5,8,3,7,8,8,3,8,5,7,8,3,5,4,6,6,8,8,3,5,3,3,4,8,8,6,6,5,6,6,9,3,9,8,6,8,6,5,8,9,9,6,6,5,6,6,7,7,7,8,9,5,6,8,7,6,7,5,6,7,8,7,7,6,9,8,5,5,6,8~8,6,3,7,5,5,7,9,7,5,7,8,5,5,5,4,4,3,3,7,7,5,7,5,7,4,6,6,3,7,8,8,7,5,3,8,5,4,4,8,8,4,9,8,4,3,4,8,6,9,3,9,8,8,3,8,4,9,7,8,9,9,7,6,4,7,9,5,8,9,9,7,7,8,7,8,5,8,9,3,9,9,7,9,4,8,9,8,4,3,4,6,6,7,8,4,6,8,9~4,4,9,4,9,9,7,9,4,9,7,7,9,4,9,6,7,6,7,6,9,7,9,9,9,6,7,4,8,9,7,6,6,3,8,5,9,7,7,7,7,4,9,7,4,6,6,8,7,5,7,8,7,7,4,4,4,7,6,9,6,6,5,9,9,8,8,9,4,4,8,8,7,4,9,9,7,8,7,3,7,6,7,9,6,9,5,3,3,9,4,9,4,3,5,5,8,7,9,4,5,9,5,4,7~5,6,8,6,6,5,8,8,5,5,8,6,6,8,6,8,3,6,6,8,9,8,5,7,8,8,6,3,5,8,8,9,4,3,9,9,6,6,7,7,7,6,5,7,5,5,8,5,8,9,8,4,5,3,6,6,8,6,4,7,7,3,3,4,4,5,3,8,5,4,6,6,8,7,3,7,9,4,6,8,5,8,5,6,8,6,8,7,6,8,8,3,6,9,3,5,3,8,3,6,6,7~6,6,5,8,9,9,3,7,9,8,4,4,5,4,9,8,8,4,5,9,8,7,8,7,4,9,4,8,9,7,8,3,3,8,7,5,6,3,7,4,5,8,9,7,3,8,7,7,8,3,7,7,8,7,9,4,5,5,8,9,5,5,5,9,9,8,8,3,7,7,3,5,7,5,7,6,3,7,9,9,4,9,4,5,6,3,6,6,4,4,8,3,7,8,8,9,7,8,6,7,4~4,7,4,4,7,4,9,7,8,9,7,9,8,9,7,9,9,8,9,4,9,6,6,9,8,8,4,9,8,6,5,6,7,3,4,7,6,8,8,7,7,7,7,6,5,4,4,9,9,6,6,7,9,3,9,4,5,7,8,3,4,4,4,7,3,3,7,9,4,9,7,6,7,7,9,4,5,5,9,7,6,9,7,7,4,9,9,9,6,4,7,4,5,7,9,9,7,5,7,8,9&purInit=[{type:"d",bet:2000}]&total_bet_min=10.00';
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
                $lines = 20;      
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
                $slotEvent['slotLines'] = 20;
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') < $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    $slotEvent['slotEvent'] = 'freespin';
                }
                $isTumb = false;
                if($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') >= 0){
                    $isTumb = true;
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
                    if( $slotEvent['slotEvent'] == 'doSpin' && $slotSettings->GetBalance() < ($lines * $betline)  && $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') < 0) 
                    {
                        $balance_cash = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                        if(!isset($balance_cash)){
                            $balance_cash = $slotSettings->GetBalance();
                        }
                        $response = 'nomoney=1&balance='. $balance_cash .'&error_type=i&index='.$slotEvent['index'].'&balance_cash='. $balance_cash .'&balance_bonus=0.00&na=s&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&ext_code=SystemError&sver=5&counter='. ((int)$slotEvent['counter'] + 1);
                        exit( $response );
                    }
                    if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1  < $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame')) && $slotEvent['slotEvent'] == 'freespin' ) 
                    {
                        if($isTumb == false){
                            $response = '{"responseEvent":"error","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":"invalid bonus state"}';
                            exit( $response );
                        }
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
                if($winType == 'bonus'){
                    $winType = 'win';
                }
                // $winType = 'win';

                $allBet = $betline * $lines;
                if($pur >= 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $betline * $lines * 100;
                }
                $tumbAndFreeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'freespin' || $isTumb == true){
                    $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                    if($isTumb == false){
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', -1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                    }
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.1f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '6074458' . substr($roundstr, 4, 10);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $leftFreeGames = 0;

                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []);
                }
                
                $wild = '2';
                $scatter = '1';
                $Balance = $slotSettings->GetBalance();
                $totalWin = 0;
                $this->strCheckSymbol = '';
                $this->wildMul = 0;
                $this->winLines = [];
                $bonusMpl = 1;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $str_initReel = '';
                $str_tmb = '';
                $str_trail = '';
                $str_ds = '';
                $str_dsa = '';
                $str_dsam = '';
                $str_accm = '';
                $acci = 0;
                $str_accv = '';
                $str_rs = '';
                $str_rwd = '';
                $str_sty = '';
                $rs_p = -1;
                $rs_c = 0;
                $rs_m = 0;
                $rs_t = 0;
                $rs_more = 0;
                $msr = 0;
                $wmv = 0;
                $currentReelSet = 0;
                $subScatterReel = null;
                if($slotEvent['slotEvent'] == 'freespin' || $isTumb == true){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_initReel = $stack['initReel'];
                    $str_tmb = $stack['tmb'];
                    $str_trail = $stack['trail'];
                    $str_ds = $stack['ds'];
                    $str_dsa = $stack['dsa'];
                    $str_dsam = $stack['dsam'];
                    $str_accm = $stack['accm'];
                    $acci = $stack['acci'];
                    $str_accv = $stack['accv'];
                    $str_rs = $stack['rs'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $rs_more = $stack['rs_more'];
                    $wmv = $stack['wmv'];
                    $msr = $stack['msr'];
                    $str_rwd = $stack['rwd'];
                    $str_sty = $stack['sty'];
                    $currentReelSet = $stack['reel_set'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $pur, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $lastReel = explode(',', $stack[0]['reel']);
                    $str_initReel = $stack[0]['initReel'];
                    $str_tmb = $stack[0]['tmb'];
                    $str_trail = $stack[0]['trail'];
                    $str_ds = $stack[0]['ds'];
                    $str_dsa = $stack[0]['dsa'];
                    $str_dsam = $stack[0]['dsam'];
                    $str_accm = $stack[0]['accm'];
                    $acci = $stack[0]['acci'];
                    $str_accv = $stack[0]['accv'];
                    $str_rs = $stack[0]['rs'];
                    $rs_p = $stack[0]['rs_p'];
                    $rs_c = $stack[0]['rs_c'];
                    $rs_m = $stack[0]['rs_m'];
                    $rs_t = $stack[0]['rs_t'];
                    $rs_more = $stack[0]['rs_more'];
                    $wmv = $stack[0]['wmv'];
                    $msr = $stack[0]['msr'];
                    $str_sty = $stack[0]['sty'];
                    $str_rwd = $stack[0]['rwd'];
                    $currentReelSet = $stack[0]['reel_set'];
                }

                $reels = [];
                for($i = 0; $i < 8; $i++){
                    $reels[$i] = [];
                    for($j = 0; $j < 8; $j++){
                        $reels[$i][$j] = $lastReel[$j * 8 + $i];
                    }
                }

                for($k = 0; $k < 8; $k++){
                    for( $j = 0; $j < 8; $j++ ) 
                    {
                        if(strpos($this->strCheckSymbol, '<' . $j . '-' . $k.'>') == false){
                            $this->repeatCount = 1;
                            $this->strWinLinePos = ($k * 8 + $j);
                            $this->strCheckSymbol = $this->strCheckSymbol . ';<' . $j . '-' . $k.'>';
                            $this->findZokbos($reels, $j, $k, $reels[$j][$k]);

                            if($this->repeatCount >= 5){
                                $winLine = [];
                                $winLine['FirstSymbol'] = $reels[$j][$k];
                                $winLine['RepeatCount'] = $this->repeatCount;
                                $winLine['StrLineWin'] = $this->strWinLinePos;
                                array_push($this->winLines, $winLine);
                            }
                        }
                    }   
                }
                for($r = 0; $r < count($this->winLines); $r++){
                    $winLine = $this->winLines[$r];
                    $arr_symbols = explode('~', $winLine['StrLineWin']);
                    $winLineMoney = $slotSettings->Paytable[$winLine['FirstSymbol']][$winLine['RepeatCount']] * $betline;
                    if($wmv > 1){
                        $winLineMoney = $winLineMoney * $wmv;
                    }
                    if($winLineMoney > 0){   
                        $strWinLine = $strWinLine . '&l'. $r.'='.$r.'~'.$winLineMoney . '~' . $winLine['StrLineWin'];
                        $totalWin += $winLineMoney;
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
                $isState = true;                         
                $isNewTumb = false;
                if($rs_p >= 0){
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', $rs_p);
                    $isState = false;        
                    $isNewTumb = true;
                    $spinType = 's';
                }else{
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', -1);
                }

                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 8; $i++){
                    $reelA[$i] = mt_rand(4, 8);
                    $reelB[$i] = mt_rand(4, 8);
                }
                $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
                $strLastReel = implode(',', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $strOtherResponse = '';
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') + $totalWin);
                
                if( $rs_t > 0 && $isTumb == true ) 
                {
                    $spinType = 'c';
                    if($slotEvent['slotEvent'] == 'freespin'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    }
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                }else if($rs_p >= 0){
                    $spinType = 's';
                    if($rs_p == 0){                        
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    }
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    if($acci == 1 && $slotEvent['slotEvent'] != 'freespin'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 1);
                    }
                    $isState = false;
                }
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                }
                
                
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($str_trail != ''){
                    $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                }
                if($str_tmb != ''){
                    $strOtherResponse = $strOtherResponse . '&tmb=' . $str_tmb;
                }
                if($str_ds != ''){
                    $strOtherResponse = $strOtherResponse . '&ds=' . $str_ds;
                }
                if($str_dsa != ''){
                    $strOtherResponse = $strOtherResponse . '&dsa=' . $str_dsa;
                }
                if($str_dsam != ''){
                    $strOtherResponse = $strOtherResponse . '&dsam=' . $str_dsam;
                }
                if($str_accm != ''){
                    $strOtherResponse = $strOtherResponse . '&accm=' . $str_accm;
                }
                if($str_accv != ''){
                    $strOtherResponse = $strOtherResponse . '&accv=' . $str_accv;
                }
                if($acci >= 0){
                    $strOtherResponse = $strOtherResponse . '&acci=' . $acci;
                }
                if($str_rs != ''){
                    $strOtherResponse = $strOtherResponse . '&rs=' . $str_rs;
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
                if($wmv > 0){
                    $strOtherResponse = $strOtherResponse . '&wmt=pr&wmv=' . $wmv;
                    if($wmv > 1){
                        $strOtherResponse = $strOtherResponse . '&gwm=' . $wmv;
                    }
                }
                if($msr != ''){
                    $strOtherResponse = $strOtherResponse . '&msr=' . $msr;
                }
                if($str_sty != ''){
                    $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                }
                if($str_rwd != ''){
                    $strOtherResponse = $strOtherResponse . '&rwd=' . $str_rwd;
                }



                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse . $strWinLine .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&reel_set='. $currentReelSet .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=8&st=rect&c='.$betline.'&sw=8&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&w='.$totalWin.'&s=' . $strLastReel;


                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0  && $rs_t > 0) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if( $slotEvent['slotEvent'] != 'freespin' && $acci > 0) 
                {
                    // $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin'));
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"TumbWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"TumbleState":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState')  . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($slotEvent['slotEvent'] == 'freespin' && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $betline * $lines * 100;
                }
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);

            }
            if($slotEvent['action'] == 'doSpin' || $slotEvent['action'] == 'doFSOption' || $slotEvent['action'] == 'doCollect' || $slotEvent['action'] == 'doCollectBonus' || $slotEvent['action'] == 'doBonus'){                
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
        
        public function findZokbos($reels, $i, $j, $firstSymbol){
            $wild = '2';
            $bPathEnded = true;
            if($firstSymbol == $wild){
                return;
            }
            if($i < 7 && ($firstSymbol == $reels[$i + 1][$j] || $reels[$i + 1][$j] == $wild) && (strpos($this->strCheckSymbol, '<'.($i + 1) . '-' . $j.'>') == false || $reels[$i + 1][$j] == $wild)){
                $strCoord = '<'.($i + 1) . '-' . $j.'>';
                $bChecked = false;
                if($reels[$i + 1][$j] == $wild){
                    $strCoord = '<'.$firstSymbol.'-'.($i + 1) . '-' . $j.'>';
                    if(strpos($this->strCheckSymbol, $strCoord)){
                        $bChecked = true;
                    }
                }
                if(!$bChecked){
                    $this->repeatCount++;
                    $this->strWinLinePos = $this->strWinLinePos . '~'.($j * 8 + $i + 1);
                    $this->strCheckSymbol = $this->strCheckSymbol . ';' . $strCoord;
                    $this->findZokbos($reels, $i + 1, $j, $firstSymbol);
                    $bPathEnded = false;
                }
                
            }

            if($j < 7 && ($firstSymbol == $reels[$i][$j + 1] || $reels[$i][$j + 1] == $wild ) && (strpos($this->strCheckSymbol, '<'.$i . '-' . ($j + 1).'>') == false || $reels[$i][$j + 1] == $wild )){
                $strCoord = '<'.$i . '-' . ($j + 1).'>';
                $bChecked = false;
                if($reels[$i][$j + 1] == $wild ){
                    $strCoord = '<'.$firstSymbol.'-'.$i . '-' . ($j + 1).'>';
                    if(strpos($this->strCheckSymbol, $strCoord)){
                        $bChecked = true;
                    }
                }
                if(!$bChecked){
                    $this->repeatCount++;
                    $this->strWinLinePos = $this->strWinLinePos . '~'.(($j + 1) * 8 + $i);
                    $this->strCheckSymbol = $this->strCheckSymbol . ';' . $strCoord;
                    $this->findZokbos($reels, $i, $j + 1, $firstSymbol);
                    $bPathEnded = false;
                }
                
            }

            if($i > 0 && ($firstSymbol == $reels[$i - 1][$j] || $reels[$i - 1][$j] == $wild ) && (strpos($this->strCheckSymbol, '<'.($i - 1) . '-' . $j.'>') == false|| $reels[$i - 1][$j] == $wild )){
                $strCoord = '<'.($i - 1) . '-' . $j.'>';
                $bChecked = false;
                if($reels[$i - 1][$j] == $wild){
                    $strCoord = '<'.$firstSymbol.'-'.($i - 1) . '-' . $j.'>';
                    if(strpos($this->strCheckSymbol, $strCoord)){
                        $bChecked = true;
                    }
                }
                if(!$bChecked){
                    $this->repeatCount++;
                    $this->strWinLinePos = $this->strWinLinePos . '~'.($j * 8 + $i - 1);
                    $this->strCheckSymbol = $this->strCheckSymbol . ';' . $strCoord;
                    $this->findZokbos($reels, $i - 1, $j, $firstSymbol);
                    $bPathEnded = false;
                }
                
            }

            if($j > 0 && ($firstSymbol == $reels[$i][$j - 1] || $reels[$i][$j - 1] == $wild) && (strpos($this->strCheckSymbol, '<'.$i . '-' . ($j - 1).'>') == false || $reels[$i][$j - 1] == $wild)){
                $strCoord =  '<'.$i . '-' . ($j - 1).'>';
                $bChecked = false;
                if($reels[$i][$j - 1] == $wild){
                    $strCoord = '<'.$firstSymbol.'-'.$i . '-' . ($j - 1).'>';
                    if(strpos($this->strCheckSymbol, $strCoord)){
                        $bChecked = true;
                    }
                }
                if(!$bChecked){
                    $this->repeatCount++;
                    $this->strWinLinePos = $this->strWinLinePos . '~'.(($j - 1) * 8 + $i);
                    $this->strCheckSymbol = $this->strCheckSymbol . ';' .$strCoord;
                    $this->findZokbos($reels, $i, $j - 1, $firstSymbol);
                    $bPathEnded = false;
                }
            }
        }
    }
}

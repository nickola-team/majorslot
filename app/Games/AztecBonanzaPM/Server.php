<?php 
namespace VanguardLTE\Games\AztecBonanzaPM
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
            $oldBetLine = 0.1;

            if( $slotEvent['slotEvent'] == 'doInit' ) 
            { 
                $lastEvent = $slotSettings->GetHistory();
                $_obf_StrResponse = '';
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 30);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [12,12,8,12,12,12,3,4,8,12,7,5,4,3,3,7,5,3,3,3,12,9,6,9,12,12,12,6,12,12]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $strOtherResponse = '';
                $currentReelSet = 0;
                $stack = null;
                $strWinLine = '';
                $winMoney = 0;
                
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
                    if (isset($lastEvent->serverResponse->ReplayGameLogs)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', json_decode(json_encode($lastEvent->serverResponse->ReplayGameLogs), true)); //ReplayLog
                    }
                    if (isset($lastEvent->serverResponse->TumbAndFreeStacks)){
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', json_decode(json_encode($lastEvent->serverResponse->TumbAndFreeStacks), true)); // FreeStack

                        $FreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                        if($slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') > 0){
                            $stack = $FreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') -1];
                        }
                    }
                    $bet = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $bet = '30.00';
                }
                $spinType = 's';
                $fsmore = 0;
                $rs_more = 0;
                $rs_p = -1;
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if(isset($stack)){
                    $str_initReel = $stack['initReel'];
                    $str_ds = $stack['ds'];
                    $str_dsa = $stack['dsa'];
                    $str_dsam = $stack['dsam'];
                    $str_tmb = $stack['tmb'];
                    $str_prg_m = $stack['prg_m'];
                    $str_prg = $stack['prg'];
                    $str_trail = $stack['trail'];
                    $str_rs = $stack['rs'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $rs_more = $stack['rs_more'];
                    $fsmore = $stack['fsmore'];
                    $str_msr = $stack['msr'];
                    $str_srf = $stack['srf'];
                    $fsmax = $stack['fsmax'];
                    $currentReelSet = $stack['reel_set'];

                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    if($rs_p >= 0){
                        $strOtherResponse = $strOtherResponse . '&rs='. $str_rs .'&rs_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') . '&rs_p=' . $rs_p . '&rs_c='. $rs_c .'&rs_m=' . $rs_m;
                    }
                    else if($rs_t > 0){
                        $strOtherResponse = $strOtherResponse.'&rs_win='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbWin').'&rs_t='.$rs_t.'&tmb_win='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbWin');
                    }
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($str_ds != ''){
                        $strOtherResponse = $strOtherResponse . '&ds=' . $str_ds . '&dsa=' . $str_dsa . '&dsam=' . $str_dsam;
                    }
                    if($str_tmb != ''){
                        $strOtherResponse = $strOtherResponse . '&tmb=' . $str_tmb;
                    }
                    if($str_prg != ''){
                        $strOtherResponse = $strOtherResponse . '&prg_m='. $str_prg_m .'&prg=' . $str_prg;
                    }
                    if($str_trail != ''){
                        $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                    }
                    if($rs_more > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_more=' . $rs_more;
                    }
                    if($str_msr != ''){
                        $strOtherResponse = $strOtherResponse . '&msr=' . $str_msr;
                    }
                    if($str_srf != ''){
                        $strOtherResponse = $strOtherResponse . '&srf=' . $str_srf;
                    }
                }
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 )
                {
                    $fs = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                    if($rs_p >= 0){
                        $fs -= 1;
                    }
                    $strOtherResponse = $strOtherResponse . '&fs=' . $fs . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsmul=1';
                    if($fsmore > 0){
                        $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                    }
                }
                $Balance = $slotSettings->GetBalance();  
                $response = 'msi=11&def_s=12,12,8,12,12,12,3,4,8,12,7,5,4,3,3,7,5,3,3,3,12,9,6,9,12,12,12,6,12,12&balance='. $Balance .'&cfgs=2773&nas=12&ver=2&index=1&balance_cash='. $Balance .'&reel_set_size=13&def_sb=5,9,3,10,3&def_sa=4,9,7,9,10&reel_set=0&prg_cfg_m=cp&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime='. floor(microtime(true) * 1000) .'&sa=4,9,7,9,10&sb=5,9,3,10,3&reel_set10=11,11,4,4,4,7,7,7,6,6,10,10,10,3,3,6,6,6,11,11,5,5,8,8,7,7,11,11,7,7,7,11,11,5,5,5,9,9~9,9,6,6,9,9,9,3,3,7,7,11,11,10,10,10,8,8,11,11,9,9,9,5,5,4,4,4,6,6,6,11,11,5,5,5,11,11~6,6,5,5,5,10,10,10,8,8,11,11,7,7,7,9,9,11,11,5,5,11,11,10,10,10,3,3,4,4,4,11,11,6,6,6,10,10~11,11,5,5,5,6,6,9,9,6,6,6,3,3,5,5,10,10,7,7,3,7,7,7,4,4,4,10,10,10,8,8,11,11,9,9,4,4~9,9,10,10,10,4,4,6,6,6,4,4,4,5,5,11,11,6,6,5,5,5,8,8,7,7,9,9,9,3,3,10,10,7,7,7&prg_cfg=1&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=10.00&reel_set11=3,3,7,7,11,11,7,7,7,6,6,11,11,4,4,4,5,5,6,6,6,11,11,9,9,11,11,10,10,10,8,8,7,7,7,5,5,5~4,4,4,11,11,8,8,8,3,3,7,7,5,5,11,11,9,9,10,10,10,11,11,6,6,11,11,6,6,6,8,8,5,5,5,8,8,8~6,6,6,11,11,3,3,11,11,10,10,10,5,5,10,10,10,5,5,5,7,7,7,4,4,4,10,10,6,6,9,9,11,11,8,8,11,11~3,3,11,11,4,4,10,10,9,9,11,11,4,4,4,5,5,5,7,7,3,3,3,8,8,7,7,7,6,6,6,6,8,8,6,6,10,10,10,5,5~8,8,3,3,3,4,4,3,3,8,8,8,10,10,10,6,6,9,9,4,4,4,10,10,5,5,5,11,11,7,7,6,6,6,7,7,7,8,8,5,5&reel_set12=7,7,10,3,3,5,5,11,11,11,6,6,6,7,7,11,11,8,8,6,6,5,5,5,9,9,9,7,7,7,11,11,4,4,4,11,11~6,6,6,11,11,10,10,11,11,11,8,8,8,3,3,8,8,8,5,5,4,4,4,11,11,6,6,7,7,8,8,5,5,5,11,11,9,9,9~3,3,11,11,9,9,9,4,4,4,10,10,11,11,8,8,9,9,9,7,7,7,6,6,6,11,11,5,5,9,9,6,6,11,11,5,5,5~11,11,3,3,3,6,6,6,7,7,5,5,9,9,9,4,4,7,7,7,9,9,5,5,5,4,4,4,3,3,6,6,11,11,8,8~9,9,6,6,6,5,5,5,11,11,3,3,3,7,7,10,10,8,8,8,6,6,9,9,9,7,7,7,4,4,4,3,3,8,8,4,4,8,8,5,5&sh=6&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='. $bet .'&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;75,30,15,0,0;30,15,10,0,0;30,15,10,0,0;15,8,5,0,0;15,8,5,0,0;10,5,3,0,0;10,5,3,0,0;10,5,3,0,0;0,0,0,0,0;0,0,0,0,0&l=30&reel_set0=7,7,10,10,4,10,10,7,7,10,6,5,7,7,9,9,3,8,8,6,6,10,10~9,9,9,6,9,9,3,3,6,5,5,7,8,8,9,9,8,4,10,10,7,5,5~6,4,4,8,8,4,4,8,9,8,8,6,6,6,9,9,6,6,3,7,7,10,10,10,6,6,7,10,8,8,5,4~6,6,3,3,9,9,4,4,8,8,4,4,7,7,4,4,4,6,6,7,7,8,8,5,5,10,10,6,6,8,8,3,3,9,9,10,10,5,5,8,8,5,5,10,10,6,6,7,7,10,10,6,6,10,10,3,3,4,4,5,5,8,8,9,9,7,7,9,9,10,10,9,9,3,3,4,4,7,7,3,3~3,3,7,7,6,6,5,5,8,8,6,6,9,9,8,8,10,10,7,7,3,3,10,10,5,5,4,4,8,8,4,4,5,5,9,9,10,10,6,6,8,8,7,7,6,6,10,10,9,9,4,4,4,3,3,5,5,10,10,3,3,7,7,8,8,10,10,7,7,9,9,3,3,6,6,4,4,9,9&s='.$lastReelStr.'&reel_set2=6,6,9,6,6,6,4,6,5,8,8,3,4,4,10,10,10,9,9,8,8,7,7,10,4,4,8,7~7,7,10,10,7,7,6,10,4,6,6,4,8,8,5,3,10,10,9,9,5~8,7,5,5,9,9,4,3,3,5,5,9,9,9,10,10,7,9,9,8,8,4,3,3,6~4,4,9,9,6,6,10,10,6,6,3,3,10,10,4,4,8,8,6,6,9,9,10,10,5,5,6,6,5,5,6,6,3,3,9,9,10,10,7,7,10,10,8,8,7,7,5,5,4,4,4,8,8,6,6,3,3,9,9,3,3,4,4,7,7,3,3,4,4,5,5,7,7,10,10,8,8,7,7,8,8,9,9~10,10,7,7,4,4,8,8,9,9,8,8,5,5,7,7,3,3,6,6,9,9,10,10,8,8,5,5,6,6,4,4,5,5,7,7,8,8,4,4,7,7,6,6,8,8,9,9,3,3,9,9,10,10,9,9,10,10,3,3,6,6,3,3,5,5,10,10,7,7,4,4,7,7,9,9,3,3,6,6,4,4,4,10,10,8,8&t=243&reel_set1=4,5,5,9,9,3,3,6,8,8,5,5,8,3,3,7,9,9,9,4,10,10,9,9~9,9,5,4,4,7,10,10,10,6,6,6,8,8,4,8,8,6,6,3,8,5,6,7,7,9,10,6,6~5,3,7,7,6,10,10,9,9,6,6,10,4,8,8,5,4,10,10,7,7~4,4,10,10,7,7,3,3,7,7,3,3,6,6,7,7,9,9,8,8,7,7,8,8,10,10,8,8,10,10,9,9,5,5,4,4,6,6,4,4,6,6,7,7,9,9,5,5,10,10,8,8,9,9,8,8,6,6,4,4,3,3,6,6,9,9,6,6,10,10,9,9,10,10,7,7,3,3,5,5,4,4,4,3,3,5,5~10,10,3,3,8,8,7,7,3,3,7,7,8,8,9,9,7,7,6,6,4,4,7,7,10,10,3,3,9,9,4,4,4,6,6,9,9,4,4,5,5,4,4,8,8,10,10,6,6,10,10,5,5,6,6,10,10,8,8,3,3,6,6,9,9,7,7,9,9,3,3,5,5,6,6,8,8,10,10,9,9,5,5,7,7&reel_set4=5,5,5,8,8,4,4,4,8,8,8,10,10,10,10,6,6,6,5,5,8,8,8,7,7,7,3,3,3,9,9,9,9~4,4,10,10,10,6,6,6,10,10,10,8,8,8,8,4,4,4,10,10,5,5,5,9,9,9,9,3,3,3,7,7,7~7,7,7,9,9,4,4,4,9,9,9,10,10,10,10,3,3,3,5,5,5,7,7,6,6,6,8,8,8,8~6,6,6,3,3,3,7,7,7,10,10,10,8,8,8,5,5,5,9,9,9,4,4,4,5,5,3,3,3~4,4,10,10,10,9,9,9,8,8,8,5,5,5,7,7,7,4,4,4,3,3,3,6,6,6&reel_set3=6,6,7,7,6,6,9,9,7,7,6,6,9,9,7,7,10,10,4,4,9,9,4,4,7,7,6,6,9,9,7,7,8,8,4,4,6,6,3,3,5,5,9,9,8,8,10,10~9,9,3,3,9,9,4,4,6,6,7,7,9,9,10,10,6,6,7,7,5,5,9,9,8,8,6,6,4,4,9,9,10,10,6,6,7,7,4,4,7,7~8,8,3,3,8,8,8,10,10,9,8,8,5,5,4,4,10,10,6,6,9,9,9,5,5,7,7,10,10~8,8,7,7,8,8,9,9,4,4,9,9,8,8,10,10,6,6,8,8,5,5,3,3,10,10,7,7~5,5,8,8,6,6,4,4,6,6,3,3,6,6,9,9,8,8,3,3,10,10,9,9,8,8,7,7,10,10,7,7,8,8&reel_set6=11,11,8,8,8,11,11,9,9,6,6,3,11,11,8,8,7,7,7,5,5,5,7,7,4,4,10,10,10,8,8,8,11,11,11,6,6,6~11,11,9,9,8,8,11,11,10,10,10,11,11,4,4,7,7,6,6,6,11,11,11,5,5,5,3,3,9,9,9,6,6,11,11,9,9,9~7,7,7,11,11,5,5,11,11,11,6,6,8,8,8,10,10,10,7,7,10,10,6,6,6,9,9,10,10,10,3,3,11,11,4,4,11,11~10,10,10,11,11,5,5,9,9,6,6,6,3,3,8,8,8,10,10,7,7,4,4,3,3,3,9,9,7,7,7,8,8,6,6,5,5,5~3,3,3,6,6,9,9,5,5,5,9,9,7,7,9,9,9,6,6,6,7,7,7,10,10,10,8,8,10,10,8,8,8,5,5,3,3,4,4,11,11&reel_set5=8,8,8,11,11,7,7,3,3,6,6,6,7,7,7,10,10,10,11,11,8,8,11,11,6,6,4,4,8,8,8,9,9,11,11,5,5,5~5,5,5,8,8,11,11,7,7,7,6,6,7,7,4,4,11,11,9,9,11,11,6,6,6,10,10,10,3,3,9,9,9,11,11,9,9,9~11,11,6,6,6,11,11,10,10,6,6,10,10,10,7,7,3,3,11,11,5,5,5,8,8,8,7,7,7,11,11,4,4,10,10,10,9,9~9,9,10,10,8,8,8,7,7,7,8,8,6,6,5,5,5,9,9,3,3,10,10,10,7,7,4,4,11,11,4,4,4,6,6,6,5,5~4,4,4,7,7,7,5,5,5,10,10,10,6,6,6,9,9,5,5,7,7,9,9,11,11,8,8,8,4,4,10,10,3,3,8,8,9,9,9,6,6&reel_set8=8,8,8,7,7,11,11,6,6,5,5,11,11,11,4,4,8,8,11,11,3,3,10,10,10,8,8,8,9,9,5,5,5,11,11~9,9,7,7,7,11,11,8,8,4,4,6,6,9,9,9,11,11,9,9,9,10,10,10,11,11,3,3,5,5,11,11,11,5,5,5,9,9,7,7~10,10,11,11,7,7,7,6,6,4,4,4,10,10,10,5,5,11,11,8,8,8,11,11,11,7,7,10,10,10,3,3,11,11,9,9,11,11~4,4,11,11,8,8,8,9,9,6,6,9,9,7,7,7,3,3,10,10,10,4,4,4,5,5,5,8,8,3,3,3,5,5,7,7,10,10~10,10,10,9,9,4,4,4,3,3,3,5,5,3,3,8,8,9,9,9,7,7,7,5,5,5,7,7,11,11,9,9,6,6,4,4,10,10,8,8,8&reel_set7=8,8,11,11,10,10,10,11,11,6,6,3,3,9,9,11,11,11,7,7,7,5,5,8,8,8,4,4,4,6,6,6,11,11,8,8,8,7,7~9,9,9,11,11,9,9,9,11,11,11,10,10,10,7,7,7,6,6,5,5,9,9,3,3,11,11,9,9,7,7,8,8,11,11,4,4,4~5,5,4,4,4,7,7,7,11,11,3,3,11,11,6,6,11,11,7,7,10,10,10,9,9,11,11,10,10,10,6,6,6,8,8,8,10,10~9,9,4,4,4,6,6,6,11,11,6,6,5,5,7,7,7,10,10,3,3,10,10,10,8,8,8,4,4,8,8,9,9,7,7,3,3,3~9,9,9,3,3,3,11,11,4,4,4,10,10,5,5,3,3,10,10,10,8,8,8,7,7,7,8,8,9,9,6,6,6,7,7,9,9,6,6,4,4&reel_set9=11,11,9,9,11,11,5,5,5,11,11,11,8,8,4,4,6,6,10,10,10,7,7,8,8,8,3,3,5,5,11,11,8,8,8,6,6,6~5,5,5,9,9,9,8,8,9,9,9,10,10,10,3,6,6,11,11,11,6,6,6,9,9,11,11,7,7,4,4,4,11,11,5,5,11,11,9,9~6,6,6,11,11,11,7,11,11,9,9,10,10,10,4,4,4,3,3,5,5,8,8,8,11,11,10,10,10,5,5,5,6,6,10,10,11,11~4,4,8,8,8,7,7,8,3,3,3,9,9,4,4,4,3,3,10,10,5,5,5,6,6,9,9,11,11,11,10,10,10,11,11,6,6,6,5,5~6,6,9,9,6,6,6,10,10,5,5,5,3,3,3,4,4,3,3,5,5,11,11,11,8,9,9,9,10,10,10,4,4,4,9,9,7,7,8,8,8';
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
                $lines = 30;      
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
                $slotEvent['slotBet'] = $slotEvent['c'];
                $slotEvent['slotLines'] = 30;
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    $slotEvent['slotEvent'] = 'freespin';
                }
                $isTumb = false;
                if($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') > 0){
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
                    if( $slotEvent['slotEvent'] == 'doSpin' && $slotSettings->GetBalance() < ($lines * $betline)  && $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') == 0) 
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

                // $winType = 'bonus';

                $allBet = $betline * $lines;
                $tumbAndFreeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'freespin' || $isTumb == true){
                    if($slotEvent['slotEvent'] == 'freespin' && $isTumb == false){
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                    }
                    $leftFreeGames = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') - $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame'); 
                    $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                }
                else
                {
                    $slotEvent['slotEvent'] = 'bet';
                    $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                    $_sum = $allBet / 100 * $slotSettings->GetPercent();
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $_sum, $slotEvent['slotEvent']);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
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
                $empty = '12';
                $Balance = $slotSettings->GetBalance();
                $totalWin = 0;
                $bonusMpl = 1;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $str_initReel = '';
                $str_ds = '';
                $str_dsa = '';
                $str_dsam = '';
                $str_tmb = '';
                $str_prg_m = '';
                $str_prg = '';
                $str_trail = '';
                $str_rs = '';
                $rs_p = -1;
                $rs_c = 0;
                $rs_m = 0;
                $rs_t = 0;
                $rs_more = 0;
                $fsmore = 0;
                $str_msr = '';
                $str_srf = '';
                $fsmax = 0;
                $currentReelSet = 0;
                if($slotEvent['slotEvent'] == 'freespin' || $isTumb == true){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_initReel = $stack['initReel'];
                    $str_ds = $stack['ds'];
                    $str_dsa = $stack['dsa'];
                    $str_dsam = $stack['dsam'];
                    $str_tmb = $stack['tmb'];
                    $str_prg_m = $stack['prg_m'];
                    $str_prg = $stack['prg'];
                    $str_trail = $stack['trail'];
                    $str_rs = $stack['rs'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $rs_more = $stack['rs_more'];
                    $fsmore = $stack['fsmore'];
                    $str_msr = $stack['msr'];
                    $str_srf = $stack['srf'];
                    $fsmax = $stack['fsmax'];
                    $currentReelSet = $stack['reel_set'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $lastReel = explode(',', $stack[0]['reel']);
                    $str_initReel = $stack[0]['initReel'];
                    $str_ds = $stack[0]['ds'];
                    $str_dsa = $stack[0]['dsa'];
                    $str_dsam = $stack[0]['dsam'];
                    $str_tmb = $stack[0]['tmb'];
                    $str_prg_m = $stack[0]['prg_m'];
                    $str_prg = $stack[0]['prg'];
                    $str_trail = $stack[0]['trail'];
                    $str_rs = $stack[0]['rs'];
                    $rs_p = $stack[0]['rs_p'];
                    $rs_c = $stack[0]['rs_c'];
                    $rs_m = $stack[0]['rs_m'];
                    $rs_t = $stack[0]['rs_t'];
                    $rs_more = $stack[0]['rs_more'];
                    $fsmore = $stack[0]['fsmore'];
                    $str_msr = $stack[0]['msr'];
                    $str_srf = $stack[0]['srf'];
                    $fsmax = $stack[0]['fsmax'];
                    $currentReelSet = $stack[0]['reel_set'];
                }

                $reels = [];
                for($i = 0; $i < 5; $i++){
                    $reels[$i] = [];
                    for($j = 0; $j < 6; $j++){
                        $reels[$i][$j] = $lastReel[$j * 5 + $i];
                    }
                }

                $_lineWinNumber = 1;
                $_obf_winCount = 0;
                $isNewTumb = false;
                for($r = 0; $r < 6; $r++){
                    if($reels[0][$r] != $scatter){
                        $this->findZokbos($reels, $reels[0][$r], 1, '~'.($r * 5));
                    }                        
                }
                for($r = 0; $r < count($this->winLines); $r++){
                    $winLine = $this->winLines[$r];
                    $winLineMoney = $slotSettings->Paytable[$winLine['FirstSymbol']][$winLine['RepeatCount']] * $betline;
                    if($winLineMoney > 0){
                        $strWinLine = $strWinLine . '&l'. $r.'='.$r.'~'.$winLineMoney . $winLine['StrLineWin'];
                        $totalWin += $winLineMoney;
                    }
                }
                if($rs_p >= 0){
                    $isNewTumb = true;
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
                if($isNewTumb == true){
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') + 1);
                    $isState = false;
                    $spinType = 's';
                }else{
                    if($fsmax > 0 && $slotEvent['slotEvent'] != 'freespin'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $fsmax);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                    }
                    if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                    }
                }
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 5; $i++){
                    $reelA[$i] = mt_rand(4, 8);
                    $reelB[$i] = mt_rand(4, 8);
                }
                $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
                $strLastReel = implode(',', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $strOtherResponse = '';
                
                if($rs_p >= 0){
                    $isState = false;
                    $strOtherResponse = $strOtherResponse . '&rs='. $str_rs .'&rs_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') . '&rs_p=' . $rs_p . '&rs_c='. $rs_c .'&rs_m=' . $rs_m;
                }
                else if($rs_t > 0){
                    if($slotEvent['slotEvent'] != 'freespin'){
                        $spinType = 'c';
                        $isState = true;
                    }
                    $strOtherResponse = $strOtherResponse.'&rs_win='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbWin').'&rs_t='.$rs_t.'&tmb_win='.($slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') - $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', 0);
                }

                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') + $totalWin);
                    $spinType = 's';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $isEnd = false;
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                    {
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        if($isNewTumb == false){
                            $spinType = 'c';
                            $isEnd = true;
                            $strOtherResponse = $strOtherResponse . '&fsend_total=1';
                        }else{
                            $strOtherResponse = $strOtherResponse . '&fsend_total=0';
                            $isState = false;
                            $spinType = 's';
                        }
                    }
                    else
                    {
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 's';
                    }
                    if($fsmore > 0){
                        $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') + $totalWin);

                    if($isNewTumb == false){
                        if($fsmax > 0){
                            $isState = false;
                            $spinType = 's';
                            $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fswin=0.00&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fsres=0.00';
                        }
                    }
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($str_ds != ''){
                    $strOtherResponse = $strOtherResponse . '&ds=' . $str_ds . '&dsa=' . $str_dsa . '&dsam=' . $str_dsam;
                }
                if($str_tmb != ''){
                    $strOtherResponse = $strOtherResponse . '&tmb=' . $str_tmb;
                }
                if($str_prg != ''){
                    $strOtherResponse = $strOtherResponse . '&prg_m='. $str_prg_m .'&prg=' . $str_prg;
                }
                if($str_trail != ''){
                    $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                }
                if($rs_more > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_more=' . $rs_more;
                }
                if($str_msr != ''){
                    $strOtherResponse = $strOtherResponse . '&msr=' . $str_msr;
                }
                if($str_srf != ''){
                    $strOtherResponse = $strOtherResponse . '&srf=' . $str_srf;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse . $strWinLine .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&reel_set='. $currentReelSet .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=6&st=rect&c='.$betline .'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&w='.$totalWin.'&s=' . $strLastReel;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)  && $isNewTumb == false) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if($isNewTumb == false){
                    if( $slotEvent['slotEvent'] != 'freespin' && $fsmax > 0) 
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin'));
                    }
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"TumbWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"TumbleState":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState')  . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
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

        public function findZokbos($reels, $firstSymbol, $repeatCount, $strLineWin){
            $wild = '2';
            $bPathEnded = true;
            if($repeatCount < 5){
                for($r = 0; $r < 6; $r++){
                    if($firstSymbol == $reels[$repeatCount][$r] || $reels[$repeatCount][$r] == $wild){
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

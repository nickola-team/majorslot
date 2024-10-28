<?php 
namespace VanguardLTE\Games\LuckyLightningPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'RespinWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 25);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [14,14,14,14,14,14,11,9,7,3,11,14,8,4,9,9,7,14,3,7,4,10,11,14]);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinWin', $lastEvent->serverResponse->respinWin);
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
                        $FreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                        if($slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') > 0){
                            $stack = $FreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') -1];
                        }
                    }
                    $bet = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $bet = '40.00';
                }
                $currentReelSet = 0;
                $spinType = 's';
                if($stack != null){
                    $str_InitReel = $stack['is'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $str_mo_wpos = $stack['mo_wpos'];
                    $mo_tv = $stack['mo_tv'];
                    $currentReelSet = $stack['reel_set'];
                    $str_srf = $stack['srf'];
                    $str_sty = $stack['sty'];
                    $rs_p = $stack['rs_p'];
                    $rs_t = $stack['rs_t'];
                    $str_ds = $stack['ds'];
                    $str_dsa = $stack['dsa'];
                    $str_dsam = $stack['dsam'];
                    $fsmore = $stack['fsmore'];

                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=0';
                    }
                    if($mo_tv > 0){
                        $strOtherResponse = $strOtherResponse . '&mo_tv='. $mo_tv .'&mo_c=1&mo_tw=' . ($mo_tv * $bet);
                    }
                    if($str_mo != ''){
                        $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                    }
                    if($str_mo_wpos != ''){
                        $strOtherResponse = $strOtherResponse . '&mo_wpos=' . $str_mo_wpos;
                    }
                    if($str_InitReel){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_InitReel;
                    }
                    if($str_srf != ''){
                        $strOtherResponse = $strOtherResponse . '&srf=' . $str_srf;
                    }
                    if($str_sty != ''){
                        $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                    }
                    if($currentReelSet >= 0){
                        $strOtherResponse = $strOtherResponse . '&reel_set='.$currentReelSet;
                    }
                    if($rs_p >= 0){
                        $strOtherResponse = $strOtherResponse . '&rs=mc&rs_p='. $rs_p .'&rs_c=1&rs_m=1';
                    }
                    if($rs_t > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t;
                    }             
                    if($str_ds != ''){
                        $strOtherResponse = $strOtherResponse . '&ds=' . $str_ds . '&dsa=' . $str_dsa . '&dsam=' . $str_dsam;
                    }
                    $strOtherResponse = $strOtherResponse  . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }
                if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)
                {
                    $strOtherResponse = $strOtherResponse . '&fs=' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&w=0.00&fsmul=1';
                }
                
                
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));

                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=14,14,14,14,14,14,11,9,7,3,11,14,8,4,9,9,7,14,3,7,4,10,11,14&reel_set69=14~14~5,6,20,21,20,7,20,20,20,9,12,3,9,8,20,20,21,20,8,12,4,10,11,8,10,11,20,9~3,8,20,20,20,20,11,9,21,10,9,20,12,6,20,4,7,5,10,20,8,12,11,20~8,8,10,10,5,20,4,21,7,11,11,12,12,21,20,20,6,20,20,3,9,9~14&balance='. $Balance .'&nas=14&mo_s=20;21;22;23&index=1&mo_v=25,50,75,100,125,150,200,250,500,1000,1250,2500,5000,10000,12500;1250;6250;250000&reel_set_size=70&reel_set='.$currentReelSet.$strOtherResponse.'&mo_jp=1250;6250;250000&balance_bonus=0.00&scatters=1~0,100,10,2,0,0~0,50,15,10,0,0~1,1,1,1,1,1&gmb=0,0,0&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"6544502",max_rnd_win:"10100"}}&wl_i=tbm~10100&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&reel_set47=14~14~7,12,4,10,3,20,21,20,8,5,10,7,20,20,20,11,9,8,20,11,12,6,9~8,10,3,7,11,21,10,9,6,20,12,20,12,9,4,8,20,7,9,21,6,10,20,11,12,11,8,20,20,21,5,5,3~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,20,3,3,9,9~14&reel_set48=14~14~7,12,4,10,3,20,21,20,8,5,10,7,20,20,20,11,9,8,20,11,12,6,9~8,10,3,7,11,21,10,9,6,20,12,20,12,9,4,8,20,7,9,21,6,10,20,11,12,11,8,20,20,21,5,5,3~8,8,10,10,5,20,4,21,7,7,11,11,12,12,6,20,3,9,9~14&sa=10,10,11,9,9,15&reel_set49=14~14~7,12,4,10,3,20,21,20,8,5,10,7,20,20,20,11,9,8,20,11,12,6,9~8,10,3,7,11,21,10,9,6,20,12,20,12,9,4,8,20,7,9,21,6,10,20,11,12,11,8,20,20,21,5,5,3~8,8,10,10,5,20,4,21,7,11,11,12,12,21,20,20,6,20,20,3,9,9~14&sb=3,11,10,9,3,16&reel_set54=14~14~5,6,20,21,20,7,20,20,20,9,12,3,9,8,20,20,21,20,8,12,4,10,11,8,10,11,20,9~8,8,10,10,5,20,4,20,7,11,11,6,12,12,21,3,9,9~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,6,20,3,3,9,9~14&sc='. implode(',', $slotSettings->Bet) .'&reel_set55=14~14~5,6,20,21,20,7,20,20,20,9,12,3,9,8,20,20,21,20,8,12,4,10,11,8,10,11,20,9~8,8,10,10,5,20,4,20,7,11,11,6,12,12,21,3,9,9~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,20,3,3,9,9~14&reel_set56=14~14~5,6,20,21,20,7,20,20,20,9,12,3,9,8,20,20,21,20,8,12,4,10,11,8,10,11,20,9~8,8,10,10,5,20,4,20,7,11,11,6,12,12,21,3,9,9~8,8,10,10,5,20,4,21,7,7,11,11,12,12,6,20,3,9,9~14&purInit_e=1&reel_set57=14~14~5,6,20,21,20,7,20,20,20,9,12,3,9,8,20,20,21,20,8,12,4,10,11,8,10,11,20,9~8,8,10,10,5,20,4,20,7,11,11,6,12,12,21,3,9,9~8,8,10,10,5,20,4,21,7,11,11,12,12,21,20,20,6,20,20,3,9,9~14&reel_set50=14~14~7,12,4,10,3,20,21,20,8,5,10,7,20,20,20,11,9,8,20,11,12,6,9~3,8,20,20,20,20,11,9,21,10,9,20,12,6,20,4,7,5,10,20,8,12,11,20~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,6,20,3,3,9,9~14&sh=4&reel_set51=14~14~7,12,4,10,3,20,21,20,8,5,10,7,20,20,20,11,9,8,20,11,12,6,9~3,8,20,20,20,20,11,9,21,10,9,20,12,6,20,4,7,5,10,20,8,12,11,20~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,20,3,3,9,9~14&reel_set52=14~14~7,12,4,10,3,20,21,20,8,5,10,7,20,20,20,11,9,8,20,11,12,6,9~3,8,20,20,20,20,11,9,21,10,9,20,12,6,20,4,7,5,10,20,8,12,11,20~8,8,10,10,5,20,4,21,7,7,11,11,12,12,6,20,3,9,9~14&reel_set53=14~14~7,12,4,10,3,20,21,20,8,5,10,7,20,20,20,11,9,8,20,11,12,6,9~3,8,20,20,20,20,11,9,21,10,9,20,12,6,20,4,7,5,10,20,8,12,11,20~8,8,10,10,5,20,4,21,7,11,11,12,12,21,20,20,6,20,20,3,9,9~14&wilds=2~0,0,0,0,0,0~1,1,1,1,1,1&c='.$bet.'&sver=5&reel_set58=14~14~5,6,20,21,20,7,20,20,20,9,12,3,9,8,20,20,21,20,8,12,4,10,11,8,10,11,20,9~12,12,10,4,8,12,21,9,8,11,6,5,3,11,8,20,9,11,10,20,7,20,9~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,6,20,3,3,9,9~14&l=25&reel_set59=14~14~5,6,20,21,20,7,20,20,20,9,12,3,9,8,20,20,21,20,8,12,4,10,11,8,10,11,20,9~12,12,10,4,8,12,21,9,8,11,6,5,3,11,8,20,9,11,10,20,7,20,9~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,20,3,3,9,9~14&reel_set65=14~14~5,6,20,21,20,7,20,20,20,9,12,3,9,8,20,20,21,20,8,12,4,10,11,8,10,11,20,9~8,10,3,7,11,21,10,9,6,20,12,20,12,9,4,8,20,7,9,21,6,10,20,11,12,11,8,20,20,21,5,5,3~8,8,10,10,5,20,4,21,7,11,11,12,12,21,20,20,6,20,20,3,9,9~14&reel_set66=14~14~5,6,20,21,20,7,20,20,20,9,12,3,9,8,20,20,21,20,8,12,4,10,11,8,10,11,20,9~3,8,20,20,20,20,11,9,21,10,9,20,12,6,20,4,7,5,10,20,8,12,11,20~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,6,20,3,3,9,9~14&reel_set67=14~14~5,6,20,21,20,7,20,20,20,9,12,3,9,8,20,20,21,20,8,12,4,10,11,8,10,11,20,9~3,8,20,20,20,20,11,9,21,10,9,20,12,6,20,4,7,5,10,20,8,12,11,20~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,20,3,3,9,9~14&s='.$lastReelStr.'&reel_set68=14~14~5,6,20,21,20,7,20,20,20,9,12,3,9,8,20,20,21,20,8,12,4,10,11,8,10,11,20,9~3,8,20,20,20,20,11,9,21,10,9,20,12,6,20,4,7,5,10,20,8,12,11,20~8,8,10,10,5,20,4,21,7,7,11,11,12,12,6,20,3,9,9~14&reel_set61=14~14~5,6,20,21,20,7,20,20,20,9,12,3,9,8,20,20,21,20,8,12,4,10,11,8,10,11,20,9~12,12,10,4,8,12,21,9,8,11,6,5,3,11,8,20,9,11,10,20,7,20,9~8,8,10,10,5,20,4,21,7,11,11,12,12,21,20,20,6,20,20,3,9,9~14&t=243&reel_set62=14~14~5,6,20,21,20,7,20,20,20,9,12,3,9,8,20,20,21,20,8,12,4,10,11,8,10,11,20,9~8,10,3,7,11,21,10,9,6,20,12,20,12,9,4,8,20,7,9,21,6,10,20,11,12,11,8,20,20,21,5,5,3~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,6,20,3,3,9,9~14&reel_set63=14~14~5,6,20,21,20,7,20,20,20,9,12,3,9,8,20,20,21,20,8,12,4,10,11,8,10,11,20,9~8,10,3,7,11,21,10,9,6,20,12,20,12,9,4,8,20,7,9,21,6,10,20,11,12,11,8,20,20,21,5,5,3~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,20,3,3,9,9~14&purInit=[{type:"fs",bet:2500}]&reel_set64=14~14~5,6,20,21,20,7,20,20,20,9,12,3,9,8,20,20,21,20,8,12,4,10,11,8,10,11,20,9~8,10,3,7,11,21,10,9,6,20,12,20,12,9,4,8,20,7,9,21,6,10,20,11,12,11,8,20,20,21,5,5,3~8,8,10,10,5,20,4,21,7,7,11,11,12,12,6,20,3,9,9~14&reel_set60=14~14~5,6,20,21,20,7,20,20,20,9,12,3,9,8,20,20,21,20,8,12,4,10,11,8,10,11,20,9~12,12,10,4,8,12,21,9,8,11,6,5,3,11,8,20,9,11,10,20,7,20,9~8,8,10,10,5,20,4,21,7,7,11,11,12,12,6,20,3,9,9~14&reel_set29=14~14~8,8,10,10,5,20,20,4,7,20,20,20,11,11,6,12,12,21,3,9,9~12,12,10,4,8,12,21,9,8,11,6,5,3,11,8,20,9,11,10,20,7,20,9~8,8,10,10,5,20,4,21,7,11,11,12,12,21,20,20,6,20,20,3,9,9~14&reel_set25=14~14~8,8,10,10,5,20,20,4,7,20,20,20,11,11,6,12,12,21,3,9,9~8,8,10,10,5,20,4,20,7,11,11,6,12,12,21,3,9,9~8,8,10,10,5,20,4,21,7,11,11,12,12,21,20,20,6,20,20,3,9,9~14&reel_set26=14~14~8,8,10,10,5,20,20,4,7,20,20,20,11,11,6,12,12,21,3,9,9~12,12,10,4,8,12,21,9,8,11,6,5,3,11,8,20,9,11,10,20,7,20,9~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,6,20,3,3,9,9~14&reel_set27=14~14~8,8,10,10,5,20,20,4,7,20,20,20,11,11,6,12,12,21,3,9,9~12,12,10,4,8,12,21,9,8,11,6,5,3,11,8,20,9,11,10,20,7,20,9~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,20,3,3,9,9~14&reel_set28=14~14~8,8,10,10,5,20,20,4,7,20,20,20,11,11,6,12,12,21,3,9,9~12,12,10,4,8,12,21,9,8,11,6,5,3,11,8,20,9,11,10,20,7,20,9~8,8,10,10,5,20,4,21,7,7,11,11,12,12,6,20,3,9,9~14&reel_set32=14~14~8,8,10,10,5,20,20,4,7,20,20,20,11,11,6,12,12,21,3,9,9~8,10,3,7,11,21,10,9,6,20,12,20,12,9,4,8,20,7,9,21,6,10,20,11,12,11,8,20,20,21,5,5,3~8,8,10,10,5,20,4,21,7,7,11,11,12,12,6,20,3,9,9~14&reel_set33=14~14~8,8,10,10,5,20,20,4,7,20,20,20,11,11,6,12,12,21,3,9,9~8,10,3,7,11,21,10,9,6,20,12,20,12,9,4,8,20,7,9,21,6,10,20,11,12,11,8,20,20,21,5,5,3~8,8,10,10,5,20,4,21,7,11,11,12,12,21,20,20,6,20,20,3,9,9~14&reel_set34=14~14~8,8,10,10,5,20,20,4,7,20,20,20,11,11,6,12,12,21,3,9,9~3,8,20,20,20,20,11,9,21,10,9,20,12,6,20,4,7,5,10,20,8,12,11,20~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,6,20,3,3,9,9~14&reel_set35=14~14~8,8,10,10,5,20,20,4,7,20,20,20,11,11,6,12,12,21,3,9,9~3,8,20,20,20,20,11,9,21,10,9,20,12,6,20,4,7,5,10,20,8,12,11,20~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,20,3,3,9,9~14&reel_set30=14~14~8,8,10,10,5,20,20,4,7,20,20,20,11,11,6,12,12,21,3,9,9~8,10,3,7,11,21,10,9,6,20,12,20,12,9,4,8,20,7,9,21,6,10,20,11,12,11,8,20,20,21,5,5,3~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,6,20,3,3,9,9~14&reel_set31=14~14~8,8,10,10,5,20,20,4,7,20,20,20,11,11,6,12,12,21,3,9,9~8,10,3,7,11,21,10,9,6,20,12,20,12,9,4,8,20,7,9,21,6,10,20,11,12,11,8,20,20,21,5,5,3~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,20,3,3,9,9~14&cfgs=4468&ver=2&balance_cash='. $Balance .'&def_sb=3,11,10,9,3,16&def_sa=10,10,11,9,9,15&reel_set36=14~14~8,8,10,10,5,20,20,4,7,20,20,20,11,11,6,12,12,21,3,9,9~3,8,20,20,20,20,11,9,21,10,9,20,12,6,20,4,7,5,10,20,8,12,11,20~8,8,10,10,5,20,4,21,7,7,11,11,12,12,6,20,3,9,9~14&reel_set37=14~14~8,8,10,10,5,20,20,4,7,20,20,20,11,11,6,12,12,21,3,9,9~3,8,20,20,20,20,11,9,21,10,9,20,12,6,20,4,7,5,10,20,8,12,11,20~8,8,10,10,5,20,4,21,7,11,11,12,12,21,20,20,6,20,20,3,9,9~14&reel_set38=14~14~7,12,4,10,3,20,21,20,8,5,10,7,20,20,20,11,9,8,20,11,12,6,9~8,8,10,10,5,20,4,20,7,11,11,6,12,12,21,3,9,9~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,6,20,3,3,9,9~14&reel_set39=14~14~7,12,4,10,3,20,21,20,8,5,10,7,20,20,20,11,9,8,20,11,12,6,9~8,8,10,10,5,20,4,20,7,11,11,6,12,12,21,3,9,9~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,20,3,3,9,9~14&reel_set43=14~14~7,12,4,10,3,20,21,20,8,5,10,7,20,20,20,11,9,8,20,11,12,6,9~12,12,10,4,8,12,21,9,8,11,6,5,3,11,8,20,9,11,10,20,7,20,9~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,20,3,3,9,9~14&reel_set44=14~14~7,12,4,10,3,20,21,20,8,5,10,7,20,20,20,11,9,8,20,11,12,6,9~12,12,10,4,8,12,21,9,8,11,6,5,3,11,8,20,9,11,10,20,7,20,9~8,8,10,10,5,20,4,21,7,7,11,11,12,12,6,20,3,9,9~14&reel_set45=14~14~7,12,4,10,3,20,21,20,8,5,10,7,20,20,20,11,9,8,20,11,12,6,9~12,12,10,4,8,12,21,9,8,11,6,5,3,11,8,20,9,11,10,20,7,20,9~8,8,10,10,5,20,4,21,7,11,11,12,12,21,20,20,6,20,20,3,9,9~14&reel_set46=14~14~7,12,4,10,3,20,21,20,8,5,10,7,20,20,20,11,9,8,20,11,12,6,9~8,10,3,7,11,21,10,9,6,20,12,20,12,9,4,8,20,7,9,21,6,10,20,11,12,11,8,20,20,21,5,5,3~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,6,20,3,3,9,9~14&na=s&reel_set40=14~14~7,12,4,10,3,20,21,20,8,5,10,7,20,20,20,11,9,8,20,11,12,6,9~8,8,10,10,5,20,4,20,7,11,11,6,12,12,21,3,9,9~8,8,10,10,5,20,4,21,7,7,11,11,12,12,6,20,3,9,9~14&reel_set41=14~14~7,12,4,10,3,20,21,20,8,5,10,7,20,20,20,11,9,8,20,11,12,6,9~8,8,10,10,5,20,4,20,7,11,11,6,12,12,21,3,9,9~8,8,10,10,5,20,4,21,7,11,11,12,12,21,20,20,6,20,20,3,9,9~14&reel_set42=14~14~7,12,4,10,3,20,21,20,8,5,10,7,20,20,20,11,9,8,20,11,12,6,9~12,12,10,4,8,12,21,9,8,11,6,5,3,11,8,20,9,11,10,20,7,20,9~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,6,20,3,3,9,9~14&mo_jp_mask=jp3;jp2;jp1&reel_set10=14~14~20,10,8,4,12,12,11,6,20,8,20,20,20,3,9,12,10,9,8,9,20,10,7,5,11,11~12,12,10,4,8,12,21,9,8,11,6,5,3,11,8,20,9,11,10,20,7,20,9~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,6,20,3,3,9,9~14&defc=100.00&reel_set11=14~14~20,10,8,4,12,12,11,6,20,8,20,20,20,3,9,12,10,9,8,9,20,10,7,5,11,11~12,12,10,4,8,12,21,9,8,11,6,5,3,11,8,20,9,11,10,20,7,20,9~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,20,3,3,9,9~14&reel_set12=14~14~20,10,8,4,12,12,11,6,20,8,20,20,20,3,9,12,10,9,8,9,20,10,7,5,11,11~12,12,10,4,8,12,21,9,8,11,6,5,3,11,8,20,9,11,10,20,7,20,9~8,8,10,10,5,20,4,21,7,7,11,11,12,12,6,20,3,9,9~14&reel_set13=14~14~20,10,8,4,12,12,11,6,20,8,20,20,20,3,9,12,10,9,8,9,20,10,7,5,11,11~12,12,10,4,8,12,21,9,8,11,6,5,3,11,8,20,9,11,10,20,7,20,9~8,8,10,10,5,20,4,21,7,11,11,12,12,21,20,20,6,20,20,3,9,9~14&bonuses=0&fsbonus=&reel_set18=14~14~20,10,8,4,12,12,11,6,20,8,20,20,20,3,9,12,10,9,8,9,20,10,7,5,11,11~3,8,20,20,20,20,11,9,21,10,9,20,12,6,20,4,7,5,10,20,8,12,11,20~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,6,20,3,3,9,9~14&reel_set19=14~14~20,10,8,4,12,12,11,6,20,8,20,20,20,3,9,12,10,9,8,9,20,10,7,5,11,11~3,8,20,20,20,20,11,9,21,10,9,20,12,6,20,4,7,5,10,20,8,12,11,20~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,20,3,3,9,9~14&counter=2&reel_set14=14~14~20,10,8,4,12,12,11,6,20,8,20,20,20,3,9,12,10,9,8,9,20,10,7,5,11,11~8,10,3,7,11,21,10,9,6,20,12,20,12,9,4,8,20,7,9,21,6,10,20,11,12,11,8,20,20,21,5,5,3~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,6,20,3,3,9,9~14&paytable=0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,250,125,25,0,0;0,150,50,20,0,0;0,100,25,10,0,0;0,50,25,10,0,0;0,50,25,10,0,0;0,25,10,5,0,0;0,25,10,5,0,0;0,25,10,5,0,0;0,25,10,5,0,0;0,25,10,5,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0&reel_set15=14~14~20,10,8,4,12,12,11,6,20,8,20,20,20,3,9,12,10,9,8,9,20,10,7,5,11,11~8,10,3,7,11,21,10,9,6,20,12,20,12,9,4,8,20,7,9,21,6,10,20,11,12,11,8,20,20,21,5,5,3~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,20,3,3,9,9~14&reel_set16=14~14~20,10,8,4,12,12,11,6,20,8,20,20,20,3,9,12,10,9,8,9,20,10,7,5,11,11~8,10,3,7,11,21,10,9,6,20,12,20,12,9,4,8,20,7,9,21,6,10,20,11,12,11,8,20,20,21,5,5,3~8,8,10,10,5,20,4,21,7,7,11,11,12,12,6,20,3,9,9~14&reel_set17=14~14~20,10,8,4,12,12,11,6,20,8,20,20,20,3,9,12,10,9,8,9,20,10,7,5,11,11~8,10,3,7,11,21,10,9,6,20,12,20,12,9,4,8,20,7,9,21,6,10,20,11,12,11,8,20,20,21,5,5,3~8,8,10,10,5,20,4,21,7,11,11,12,12,21,20,20,6,20,20,3,9,9~14&total_bet_max='.$slotSettings->game->rezerv.'&reel_set21=14~14~20,10,8,4,12,12,11,6,20,8,20,20,20,3,9,12,10,9,8,9,20,10,7,5,11,11~3,8,20,20,20,20,11,9,21,10,9,20,12,6,20,4,7,5,10,20,8,12,11,20~8,8,10,10,5,20,4,21,7,11,11,12,12,21,20,20,6,20,20,3,9,9~14&reel_set22=14~14~8,8,10,10,5,20,20,4,7,20,20,20,11,11,6,12,12,21,3,9,9~8,8,10,10,5,20,4,20,7,11,11,6,12,12,21,3,9,9~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,6,20,3,3,9,9~14&reel_set0=10,7,7,7,7,5,3,3,3,13,12,6,6,6,4,6,11,9,2,13,13,13,8,4,4,4,3,1,13,9,6,13,3~4,13,13,13,3,3,3,3,12,7,6,6,6,9,13,6,7,7,7,1,5,11,10,2,8,12,7,12,3,12,7~3,3,3,7,13,13,13,12,3,6,4,6,6,6,11,20,20,20,20,9,1,10,13,5,8,6,8,20,13,7,6,5,20,12,20,12,20,12,20,13,12,13,11,13,12,20,6,20,7,20,11,20,5,10,13,20,13,12,7,9,6,12,5,8,13,10,11,20,12,7,6,13,8,20,13,12,20,6,7,5,12,5,6~3,13,13,13,7,20,12,12,12,6,20,20,20,5,1,13,4,20,8,11,9,12,10,6,7,6,12,10,7,10,20,7,13,9,13,20,6,5,20,13,7,9,4,10,12,6,12,4,13,9,13,12,5,21,12,4,6,20,12,6,7,4,20,9,6,13,8,6,13,12,6,11,20,9,13,20,10,12,20,13,20,13,20,12,7,20,13,9,20,13,6,13,20,12,20,13,20,6,13,6,20,13,12,13,6,20,13,12,7,12,20,12,13,20,10,13~6,6,6,11,4,12,6,20,7,7,7,1,20,5,5,5,3,10,9,13,13,13,7,5,4,4,4,8,13,20,21,20,7,5,7,5,20,1,7,20,5,13,7,4,1,5,7,4,7,1,20,1,11,13,20,13,20,4,7,5,7,4,8,7,3,13,5,1,4,7,20,3,1,7,20,3,1,20,7,4,7,5,4,20,21,1,5,11,1,7~20,20,21,22,20&reel_set23=14~14~8,8,10,10,5,20,20,4,7,20,20,20,11,11,6,12,12,21,3,9,9~8,8,10,10,5,20,4,20,7,11,11,6,12,12,21,3,9,9~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,20,3,3,9,9~14&reel_set24=14~14~8,8,10,10,5,20,20,4,7,20,20,20,11,11,6,12,12,21,3,9,9~8,8,10,10,5,20,4,20,7,11,11,6,12,12,21,3,9,9~8,8,10,10,5,20,4,21,7,7,11,11,12,12,6,20,3,9,9~14&reel_set2=5,12,4,4,4,2,1,11,6,6,6,10,3,3,3,8,13,13,13,7,13,6,9,4,3,13,4,8,3,6,12,3,13,4,11,3,10,4,1,3,4,6,4,11,13,9,8,4,6,4,1,4,3,4,9,2,3,4,8,9,4,7,6,3,8,3,10,4,3,4,3,6,12,4,8,6,4,8,3,9,8,2,4,8,9,4,7,3,8,4,3,12,10,12,11,4,7,2,3~10,3,3,3,13,12,13,13,13,6,2,11,4,1,7,9,8,3,5,7,3,2,12,13,12,3,1,8,13,6,13,3,13,12,7,8,13,7,6,8,13,3,8,11,3,8,13,3,12,13~13,3,11,20,6,3,3,3,10,8,6,6,6,20,12,20,20,20,9,13,13,13,1,5,4,7,4,11,9,20,9,12,3,20,3,7,11,10,20,20,9,3,12,5,7,6,21,3,20,6,20,3,11,9,20,3,20,9,20,7,3,6,5,9,20,3,6,3,9,3,7,12,11,20,8,7,3,12,6,4,9,7,8,20,9,3,8,20,11,3,7,11,20,3,20,20,7,3,7,8,20,3,11,10,20,6,20,8,6,7~13,13,13,8,12,12,12,1,12,7,4,20,13,5,20,20,3,11,6,9,10,8,12,11,8,11,4,1,3,12,4,20,4,1,12,3,20,11,1,5,20,5,21,12,7,3,5,6,1,3,5,20,12,20,5,12,8,20,4,20,3,20,12,20,12,1,12,3,20,20,12,20,5,4,20,12,20,12,3,20,12,3,11,12,10,12,3,5,1,5,3,6,20,7,12,7,12,4,12,3,11,1,20,12,20,5,9,8,1,20,7,20,11,1,3,5~13,13,13,7,12,20,6,6,6,20,7,7,7,20,6,4,8,9,5,5,5,10,11,1,3,4,4,4,5,13,7,6,22,4,5,4,5,9,4,7,4,5,9,6,8,12,7,6,5,7,20,7,21,4,20,1,20,5,4,3,5,6,21,4,8,4,20,4,5,4,7,4,12,4,5,12,20,5,12,7~21,20,20,20,20,21,23,22,20,20,20,21,20,20,21,20,20,22,20,20,21,23,20,22,20&reel_set1=6,6,6,12,3,3,3,5,11,4,4,4,8,7,7,7,1,13,13,13,2,6,4,13,9,10,7,3,7,4,7,12,4,3,7,12,3,13,4,3,4,3,5,3,1,3,5,7,4,3,7,11,3,9,7,1,8,1,4,3,4,1,3,11,9,4,3,8,7,1,5,4,3,5,3,5,4,3,1,8,12,9,12,11,3,9,12,7,5,1,12,1,9~13,13,13,8,7,7,7,12,13,10,6,6,6,7,3,3,3,2,9,4,5,6,1,11,3,5,3,9,3,6,9,3,6,11~20,3,6,6,6,12,13,13,13,9,3,3,3,1,20,20,21,4,6,10,11,8,5,13,7,3,11,6,1,6,3,7,3,1,10,9,6,1,12,6,1,13,3,13,3,1,13,6,11,13,7,6,1,6,13,6,3,10,1,13,6,4,1,5,9,13,6,11,3,6~20,22,20,4,12,12,12,12,13,13,13,5,21,1,8,9,7,6,10,11,3,13,7,5,3,4,13,8,4,8,13,8,12,8,12,13,6,12,3,12,13,1,12,7,13,7,13,8,11,12,13,9,7,13,12,4,3,4,6,4,11,7,4,8,13,11,4,8,11,13,11,4,8,12,6,4,13,11,12,10,13,4,13,3,12,7,9,6,4,8,13,11,3,9,11,13,7,4,12,13,12,4,6,9,4,12,6,8,4,11,3,12,1,10,4,8,6,7,8,5,13,12,4,3,12,8,6,12,11,12,6,9,12,4,13,6,7,9,11,13,8,13,3,11,12,8,7,12,11,13,8,12,4,12,13,6,12,13,7,1,3,4,13,5,4,8,13~7,7,7,12,6,6,6,23,8,5,5,5,10,6,13,13,13,11,4,1,3,13,22,20,20,9,5,4,4,4,7,21,3,6,5,3,5,10,11,5,20,11,3,20,13,1,4,13,5,3,21,4,6,3,6,3,6,10,6,4,10,13,10,1,10,20,10,3,10,6,3,11,9~23,20,22,21,20,21,20,20&reel_set4=4,4,4,6,6,6,6,9,3,13,13,13,4,13,12,10,3,3,3,11,7,2,8,5,1,6,13,6,12,6,3,6,3,6,3,13,6,12,3,1,5,3,5,11,6,3,12,3,1,12,3,6,2,6,3,6,13,6,12,3,5,13,3,6,3,6,13,6,13,6,12,5,1,11,13,12,13,6,12,6,11,6,3,6,11,6,3,13,6,9,2,6,3,6,12,11,6,13,5,2,6,13,3~3,3,3,2,13,13,13,3,13,10,9,1,4,12,8,7,6,11,5,8,6,13,5,4,13,1,13,12,8,12,1,12,13,7,13,1,7,4,2,13,8,5,7,13,1,7,13,4,11,7,12,8,11,12,7,13,6,8,7,11,1,13,1,8,11,6,8,13,1,8,1,6,13,1,4,1,2,13,6,8~8,6,6,6,7,3,10,11,5,1,12,20,20,20,20,20,9,13,13,13,13,6,3,3,3,4,5,6,5,4,20,13,3,6,20,6,20,21,3,20,4,12,10,13,6,3,5,20,6,4,20,3,11,3,12,20,6,20,13,4,3,6,9,20,5,12,5,20,5,6,20,5,20,12,6,4,5,3,20,3,20,3,6,3,6,3,20,6,3,11~12,12,12,6,12,13,13,13,20,13,4,11,20,10,3,7,9,8,20,5,1,13,4,6,20,4,3,20,13,20,13,21,4,6,11,4,6,11,3,13,7,4,11,20,20,10,13,7,20,6,13,4,8,20,13,11,13,10,20,6,10,8,13,20,10,6,4~6,10,6,6,6,12,8,4,4,4,1,13,13,13,4,7,7,7,3,13,9,7,11,20,5,5,5,5,20,20,7,13,20,13,7,20,22,1,13,7,12,13,7,3,7,10,7,4,8,3,7,21,4,13,7,9,4,5,4,9,10,11,21,4,13,7,13,20~23,20,20,22,21,20,20,21,20,20,21,20,20,20,20,20,20,20,20,21,21,20,22,20,20,21,20,20,20,20,20,20,20,22,20,21,20,20,20&reel_set3=10,6,6,6,3,1,13,13,13,7,8,2,3,3,3,11,4,4,4,4,5,6,9,13,12,4,6,3,12,3,12,4,6,12,3,8,12,13,3,13,12,8,6,12,3,9,4,3,6,13,6,3,8,12,5,13,4~10,8,4,3,7,6,9,11,1,12,5,13,13,13,2,3,3,3,13,12,13,3,9,13,4,7,3,4~3,3,3,8,4,9,11,13,13,13,13,3,1,6,6,6,12,20,20,21,5,7,10,20,6,4,13,6,20,6,20,9,20,9,21,13,8,9,20,13,7,20,8,7,6,13,6,7,6,7,13,9,4,20,6,13,11,20,9,6,13,8,12,8,6,13,8,13,11,20,8,13,12,4,6,8,7,4,13,12,6,20,12,21,7,8,10,7,8,13,20,5,20,6,4,7,12,20,6,5,12,6,20,7,5,13,4,13,20,7,20,9,8,4,8,9,20,6,20~12,12,12,11,5,20,22,20,3,13,13,13,1,8,21,10,13,9,4,7,12,6,5,9,13,8,13,1~13,20,1,8,23,20,20,9,4,4,4,4,12,5,5,5,5,7,7,7,11,13,13,13,3,6,6,6,6,7,10,21,3,6,22,4,20,5,11,5,12,10,12,5,20,5,4,11,7,20,7,11,5,6,12,7,10,20,5,12,20,4,21,5,11,20,10,4,20,7,12,3,11,10,4,12,7,12,5,7,10,6,4,12,7,20,5,7,5,7,12,6,10,20,10,4,5,10,20,10,12,11,8,4,10,5,8,22,6,8,12,5,10,6,4,12,6,20,12,11,10,20,5,7,4,11,5,10,6,11,7,10,7,12,6,12,6,11,5,12,1,12,20,3,12~20,22,21,20,20,23,20,20&reel_set20=14~14~20,10,8,4,12,12,11,6,20,8,20,20,20,3,9,12,10,9,8,9,20,10,7,5,11,11~3,8,20,20,20,20,11,9,21,10,9,20,12,6,20,4,7,5,10,20,8,12,11,20~8,8,10,10,5,20,4,21,7,7,11,11,12,12,6,20,3,9,9~14&reel_set6=14~14~20,10,8,4,12,12,11,6,20,8,20,20,20,3,9,12,10,9,8,9,20,10,7,5,11,11~8,8,10,10,5,20,4,20,7,11,11,6,12,12,21,3,9,9~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,6,20,3,3,9,9~14&reel_set5=12,4,4,4,4,3,3,3,10,11,13,6,13,13,13,7,6,6,6,2,1,9,3,5,8,6,13,4,6,3,1~3,9,3,3,3,2,7,13,13,13,8,13,5,11,12,1,10,4,6,12,5,8,12,6,11,8,13,8,4,13,8,2,6,13,12,8,13,12,9,8,11,6,11,8,12,11,10,12,8,4,1,12,11,6,13,8,11,13,8,12~8,13,4,20,20,21,9,6,6,6,11,6,5,1,20,13,13,13,10,7,12,3,3,3,3,9,20,13,11,13,12,20,11,12,11,3,11,20,13,20,12,11,3,13,3,6,3,21,12,3,20,6,5,6,11,3,20,1,13,6,20,12,3,6,3,11,13,3,20,12,6,3,20,12,13,3,11,6,3,13,20,6,13,11,20,5,1,20,3,20,3,20,6,13,11,1,6,10,6,11,3,11,6,3,6,13,3,12,6,11,20,11,6,11,13,20,6,11,21,11~12,8,1,6,11,10,3,5,12,12,12,9,13,20,22,20,4,7,21,13,13,13,5,6,13,3,13,11,8,6,20,1,20,13,4,3,20,11,8,5,6,13,21,9,1,20,13,20,9,4,3,20,11,20,13,21,5,11,9,20,13,20,11,13,8,7,8,6,13,11,3,11,8,9,20,9,7,8,10,22,8,20,9,5,13,6,20,8,9,13,3,13,7,3,4,8,4,13,3,7,11,6,11,21,13,6,13,6,20,13,3~1,3,5,5,5,4,7,13,8,20,22,20,9,12,23,10,5,6,11,13,13,13,7,7,7,4,4,4,6,6,6,21,6,12,20,4,10,7,6,4,5,3,9,20,7,4,21,7,20,10,20,4,22,3,4,20,13,3,13,7,4,13,5,20,10,8,10,4,10,4,13,4,6,5,13,12,13,10,5~22,20,20,23,21,20,20,21&reel_set8=14~14~20,10,8,4,12,12,11,6,20,8,20,20,20,3,9,12,10,9,8,9,20,10,7,5,11,11~8,8,10,10,5,20,4,20,7,11,11,6,12,12,21,3,9,9~8,8,10,10,5,20,4,21,7,7,11,11,12,12,6,20,3,9,9~14&reel_set7=14~14~20,10,8,4,12,12,11,6,20,8,20,20,20,3,9,12,10,9,8,9,20,10,7,5,11,11~8,8,10,10,5,20,4,20,7,11,11,6,12,12,21,3,9,9~8,8,10,10,5,5,20,4,4,21,7,7,11,11,12,12,6,20,3,3,9,9~14&reel_set9=14~14~20,10,8,4,12,12,11,6,20,8,20,20,20,3,9,12,10,9,8,9,20,10,7,5,11,11~8,8,10,10,5,20,4,20,7,11,11,6,12,12,21,3,9,9~8,8,10,10,5,20,4,21,7,11,11,12,12,21,20,20,6,20,20,3,9,9~14&total_bet_min=10.00';
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
                $isRespin = false;
                if($slotSettings->GetGameData($slotSettings->slotId . 'BonusState') >= 0){
                    $isRespin = true;
                }
                if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
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
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') < $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotEvent['slotEvent'] == 'freespin' && $isRespin == false ) 
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
                
                // $winType = 'bonus';

                $allBet = $betline * $lines;
                if($pur >= 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $allBet * 100;
                }
                $freeStacks = []; 
                $isGeneratedFreeStack = false;
                $bonusSymbol = 0;
                if($slotEvent['slotEvent'] == 'freespin' || $isRespin == true){
                    if($isRespin == false){
                        $slotSettings->SetGameData($slotSettings->slotId . 'RespinWin', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    }
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '6074458' . substr($roundstr, 4, 10);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $leftFreeGames = 0;

                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []);
                }
                
                $wild = '2';
                $scatter = '1';
                $empty = '14';
                $Balance = $slotSettings->GetBalance();
                $totalWin = 0;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $str_InitReel = '';
                $str_mo = '';
                $str_mo_t = '';
                $mo_tv = 0;
                $str_mo_wpos = '';
                $str_srf = '';
                $str_sty = '';
                $rs_p = -1;
                $rs_t = 0;
                $fsmore = 0;
                $str_ds = '';
                $str_dsa = '';
                $str_dsam = '';
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_InitReel = $stack['is'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $str_mo_wpos = $stack['mo_wpos'];
                    $mo_tv = $stack['mo_tv'];
                    $currentReelSet = $stack['reel_set'];
                    $str_srf = $stack['srf'];
                    $str_sty = $stack['sty'];
                    $rs_p = $stack['rs_p'];
                    $rs_t = $stack['rs_t'];
                    $str_ds = $stack['ds'];
                    $str_dsa = $stack['dsa'];
                    $str_dsam = $stack['dsam'];
                    $fsmore = $stack['fsmore'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines, $pur);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $lastReel = explode(',', $stack[0]['reel']);
                    $str_InitReel = $stack[0]['is'];
                    $str_mo = $stack[0]['mo'];
                    $str_mo_t = $stack[0]['mo_t'];
                    $str_mo_wpos = $stack[0]['mo_wpos'];
                    $mo_tv = $stack[0]['mo_tv'];
                    $currentReelSet = $stack[0]['reel_set'];
                    $str_srf = $stack[0]['srf'];
                    $str_sty = $stack[0]['sty'];
                    $rs_p = $stack[0]['rs_p'];
                    $rs_t = $stack[0]['rs_t'];
                    $str_ds = $stack[0]['ds'];
                    $str_dsa = $stack[0]['dsa'];
                    $str_dsam = $stack[0]['dsam'];
                    $fsmore = $stack[0]['fsmore'];
                }
                $reels = [];
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;
                $moneyWin = 0;
                $respinCollectCount = 0;
                for($i = 0; $i < 6; $i++){
                    $reels[$i] = [];
                    for($j = 0; $j < 4; $j++){
                        $reels[$i][$j] = $lastReel[$j * 6 + $i];
                        if($lastReel[$j * 6 + $i] == $scatter){
                            $scatterCount++;
                            $scatterPoses[] = $j * 6 + $i;   
                        }
                    }
                }
                if($rs_p <= 0 && $rs_t <= 0){
                    for($r = 0; $r < 4; $r++){
                        if($reels[0][$r] != $scatter && $reels[0][$r] != 14){
                            $this->findZokbos($reels, $reels[0][$r], 1, '~'.($r * 6));
                        }                        
                    }
                    $line_index = 0;
                    for($r = 0; $r < count($this->winLines); $r++){
                        $winLine = $this->winLines[$r];
                        $winLineMoney = $slotSettings->Paytable[$winLine['FirstSymbol']][$winLine['RepeatCount']] * $betline;
                        if($winLineMoney > 0){   
                            $strWinLine = $strWinLine . '&l'. $line_index.'='.$line_index.'~'.$winLineMoney . $winLine['StrLineWin'];
                            $totalWin += $winLineMoney;
                            $line_index++;
                        }
                    }
                }

                if($mo_tv > 0){
                    $moneyWin = $mo_tv * $betline;
                }
                if($scatterCount >= 3){
                    $muls = [0,0,0,2,10,100];
                    $scatterWin = $betline * $lines * $muls[$scatterCount];
                }
                $totalWin = $totalWin + $moneyWin + $scatterWin; 
                
                $spinType = 's';
                if( $totalWin > 0) 
                {
                    $spinType = 'c';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }
                $_obf_totalWin = $totalWin;
                if($rs_p < 0){
                    if( $scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin') 
                    {
                        $freeSpins = [0,0,0,10,15,50];
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freeSpins[$scatterCount]);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                    }else if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                    }
                }

                $strLastReel = implode(',', $lastReel);
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 6; $i++){
                    $reelA[$i] = mt_rand(8, 10);
                    $reelB[$i] = mt_rand(8, 10);
                }
                $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
               
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', $rs_p);
                $strOtherResponse = '';
                $isState = true;
                $isEnd = true;
                if($rs_p >= 0){
                    $isRespin = true;
                    $spinType = 's';
                    $isState = false;
                }else if($rs_t > 0){
                    $isRespin = false;
                }
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinWin', $slotSettings->GetGameData($slotSettings->slotId . 'RespinWin') + $totalWin);
                    $spinType = 's';
                    $isEnd = false;
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                    {
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin').'&fsmul_total=1&fsres_total='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        if($isRespin == false){
                            $spinType = 'c';
                            $strOtherResponse = $strOtherResponse . '&fsend_total=1';
                        }else{
                            $strOtherResponse = $strOtherResponse . '&fsend_total=0';
                            $isState = false;
                        }
                    }
                    else
                    {
                        $isState = false;
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsres='.$slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        $spinType = 's';
                    }
                    if($fsmore > 0){
                        $strOtherResponse = $strOtherResponse  .'&fsmore='. $fsmore;
                    }
                }else
                {
                    if($rs_p >= 0 || $rs_t > 0){
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'RespinWin', $slotSettings->GetGameData($slotSettings->slotId . 'RespinWin') + $totalWin);
                    }else{
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'RespinWin', $totalWin);
                    }
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    if($scatterCount >= 3 && $isRespin == false){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse =$strOtherResponse . '&fsmul=1&fsmax='. $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames')  .'&fswin=0.00&fs=1&fsres=0.00&psym=1~'. $scatterWin .'~' . implode(',', $scatterPoses);
                        if($pur >= 0){
                            $strOtherResponse = $strOtherResponse . '&purtr=1';
                        }
                    }
                }
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $strOtherResponse = $strOtherResponse . '&puri=0';
                }
                if($mo_tv > 0){
                    $strOtherResponse = $strOtherResponse . '&mo_tv='. $mo_tv .'&mo_c=1&mo_tw=' . $moneyWin;
                }
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                }
                if($str_mo_wpos != ''){
                    $strOtherResponse = $strOtherResponse . '&mo_wpos=' . $str_mo_wpos;
                }
                if($str_InitReel){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_InitReel;
                }
                if($str_srf != ''){
                    $strOtherResponse = $strOtherResponse . '&srf=' . $str_srf;
                }
                if($str_sty != ''){
                    $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                }
                if($currentReelSet >= 0){
                    $strOtherResponse = $strOtherResponse . '&reel_set='.$currentReelSet;
                }
                if($rs_p >= 0){
                    $strOtherResponse = $strOtherResponse . '&rs=mc&rs_p='. $rs_p .'&rs_c=1&rs_m=1';
                }
                if($rs_t > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t;
                }                
                if($str_ds != ''){
                    $strOtherResponse = $strOtherResponse . '&ds=' . $str_ds . '&dsa=' . $str_dsa . '&dsam=' . $str_dsam;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .$strWinLine .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=4&c='.$betline.'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=25&s='.$strLastReel .'&w='. $totalWin;
                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 && $isRespin == false) 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"respinWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($slotEvent['slotEvent'] == 'freespin' && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * 100;
                }
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if( $scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin') 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                }
            }
            if($slotEvent['action'] == 'doSpin' || $slotEvent['action'] == 'doCollect' || $slotEvent['action'] == 'doCollectBonus' || $slotEvent['action'] == 'doMysteryScatter' || $slotEvent['action'] == 'doBonus'){
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
            if($repeatCount < 6){
                for($r = 0; $r < 4; $r++){
                    if($firstSymbol == $wild && $firstSymbol != 1 && $firstSymbol != 14){
                        $subFirstSymbol = $reels[$repeatCount][$r];
                        $this->findZokbos($reels, $subFirstSymbol, $repeatCount + 1, $strLineWin . '~' . ($repeatCount + $r * 6));
                        $bPathEnded = false;
                    }else if($firstSymbol == $reels[$repeatCount][$r] || $reels[$repeatCount][$r] == $wild){
                        $this->findZokbos($reels, $firstSymbol, $repeatCount + 1, $strLineWin . '~' . ($repeatCount + $r * 6));
                        $bPathEnded = false;
                    }
                }
            }
            if($bPathEnded == true){
                if($repeatCount >= 3 || ($firstSymbol == 3 && $repeatCount >= 2)){
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

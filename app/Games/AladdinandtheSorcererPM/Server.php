<?php 
namespace VanguardLTE\Games\AladdinandtheSorcererPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [12,6,9,9,7,9,8,7,12,12,6,7,12,7,8]);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
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
                    $bet = '50.00';
                }
                $currentReelSet = 0;
                $spinType = 's';
                if(isset($stack)){                    
                    $currentReelSet = $stack['reel_set'];
                    $str_initReel = $stack['init_reel'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $rs_more = $stack['rs_more'];
                    $str_rwd = $stack['rwd'];
                    $str_prg_m = $stack['prg_m'];
                    $str_prg = $stack['prg'];
                    $str_sty = $stack['sty'];
                    $str_slm_lmi = $stack['slm_lmi'];
                    $str_slm_lmv = $stack['slm_lmv'];
                    $str_slm_mp = $stack['slm_mp'];
                    $str_slm_mv = $stack['slm_mv'];
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($str_rwd != ''){
                        $strOtherResponse = $strOtherResponse . '&rwd=' . $str_rwd;
                    }
                    if($str_prg != ''){
                        $strOtherResponse = $strOtherResponse . '&prg=' . $str_prg;
                    }
                    if($str_sty != ''){
                        $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                    }
                    if($str_slm_lmv != ''){
                        $strOtherResponse = $strOtherResponse . '&slm_lmi=' . $str_slm_lmi . '&slm_lmv=' . $str_slm_lmv;
                    }                
                    if($str_slm_mv != ''){
                        $strOtherResponse = $strOtherResponse . '&slm_mp=' . $str_slm_mp . '&slm_mv=' . $str_slm_mv;
                    }
                    if($currentReelSet >= 0){
                        $strOtherResponse = $strOtherResponse . '&reel_set=' . $currentReelSet;
                    }
                    if($rs_p >= 0){
                        $strOtherResponse = $strOtherResponse . '&rs=mc&rs_p=' . $rs_p;
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
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));

                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=12,6,9,9,7,9,8,7,12,12,6,7,12,7,8&balance='. $Balance .'&cfgs=2563&ver=2&index=1&balance_cash='. $Balance .'&reel_set_size=4&def_sb=11,11,10,11,3&def_sa=7,12,11,5,10&reel_set='.$currentReelSet.$strOtherResponse.'&prg_cfg_m=s0,cp0,tp0,s1,cp1,tp1&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=7,12,11,5,10&sb=11,11,10,11,3&prg_cfg=13,3,0,14,3,0&sc='. implode(',', $slotSettings->Bet) .'&defc=50.00&sh=3&wilds=2~1000,200,75,0,0~1,1,1,1,1;13~0,0,0,0,0~1,1,1,1,1;14~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;1000,200,75,0,0;400,100,40,0,0;250,75,30,0,0;200,60,25,0,0;150,40,20,0,0;125,30,15,0,0;100,25,10,0,0;75,20,7,0,0;50,15,5,0,0;40,10,3,0,0;0,0,0,0,0;0,0,0,0,0&l=20&reel_set0=3,10,11,7,12,9,6,11,12,7,10,8,12,11,7,12,9,10,7,9,11,5,12,8,7,10,11,6,9,10,5,9,12,3,10,11,5,8,9,7,10,11,7,10,9,6,10,12,7,10,8,3,11,10,6,9,10,7,9,10,5,12,8,7,10,11,6,9,12,5,9,10,4,10,12,13,11,8,5,10,8,7,12,9,6,11,12,7,10,8,3,10,8,6,9,10,7,9,11,5,12,8,7,10,8,6,9,8,5,9,12,7,8,10,5,9,8,6,10,11,4,8,9,6,11,12,7,10,8,7,11,8,6,9,10,7,9,11,5,12,8,7,10,11,6,9,8,5,9,8,6,9,8,5,10,12,7,10,11,4,12,9,6,11,12,7,10,8,3,11,8,6,9,10,7,9,8,5,12,8,7,10,11,6,9,8,5,9,12,6,11,9,13,10,8,7,10,8,4,12,9,6,10,12,7,10,8,7,10,8,6,9,10,7,9,11,5,12,8,7,10,11,6,9,8,5,9,12,7,10,11,6,8,12,7,10,8,4,12,9,6,11,12,7,10,8,3,11,8,6,9,10,7,9,11,5,12,8,7,10,11,6,9,8,7,9,12,4,11,8,6,12,8,4,10,11,4,12,9,6,11,12,7,10,8,3,11,8,6,9,10,7,9,11,5,12,8,7,10,11,6,9,8,5,9,12,7,8,10,5,9,12,10,11,4,12,8,6,11,12,7,10,8,7,11,8,7,10,8,3,11,8,6,9,10,7,9,11,5,12,8,7,10,11,6,9,8,5,9,12,7,8,10,5~3,10,11,4,12,8,5,7,12,6,8,7,11,2,9,6,10,12,5,8,12,4,11,9,3,10,8,6,12,7,8,11,4,12,10,6,9,2,6,10,9,4,11,9,5,11,10,6,12,9,6,12,4,11,2,12,7,11,12,3,10,11,5,8,11,7,12,9,6,10,11~4,11,10,5,12,8,7,9,5,8,3,10,7,11,9,7,12,10,5,8,11,3,12,9,7,11,12,7,11,10,5,12,6,4,12,9,2,12,6,11,10,5,8,11,6,10,9,7,8,2,5,11,12,7,10,12,4,9,7,11,6,8,12,3,10,8,4,9,11,6,12,7,11,5,8,6,12,9~5,12,11,6,12,10,3,11,8,4,12,9,6,11,10,7,12,11,5,9,12,2,11,10,6,8,12,3,11,10,7,12,9,2,10,11,4,8,12,7,9,11,5,12,10,6,12,8,7,9,2,10,5,11,7,12,9,3,11,8,6,10,12,4,11,10~3,12,10,6,9,8,7,10,11,5,8,12,6,10,9,4,11,12,7,9,12,5,11,10,7,12,8,3,9,10,6,11,9,6,8,10,7,9,12,6,10,11,5,8,11,4,12,10,6,9,8,7,10,11,6,8,12,6,10,9,4,11,12,3,9,12,5,11,10,7,12,8,3,9,10,7,12,9,6,8,11,7,9,12,6,10,11,5,8,11,4,10,11,14,12,8,6,9,12,10,6,9,8,7,10,11,5,8,12,6,10,9,4,11,12,7,9,12,5,11,10,7,12,8,3,9,10,7,11,9,6,8,11,7,9,12,6,10,11,5,7,11,4,12,10,6,9,8,7,10,11,5,8,12,6,10,9,4,11,12,7,9,12,5,11,10,7,12,8,3,9,10,7,11,9,6,8,11,7,9,12,6,10,11,5,8,11,4,12,10,4,8,9,5,12,10,6,9,8,7,10,11,5,8,12,6,10,9,6,11,12,7,6,12,5,11,10,7,12,8,3,9,10,7,11,9,6,8,11,7,9,12,6,10,11,5,8,11,4,12,10,6,9,8,7,10,11,5,8,12,6,10,9,4,11,12,7,9,12,5,11,10,7,12,8,3,6,10,7,11,9,6,8,11,7,9,12,6,10,11,5,8,11,4,12,10,6,9,8,7,10,11,5,8,12,6,10,9,6,11,12,7,6,12,5,11,10,7,12,8,3,9,10,7,11,9,6,8,11,7,9,12,6,10,11,5,8,11,4,12,10,6,9,8,7,10,11,5,8,12,6,10,9,4,11,12,7,9,12,5,11,10,7,12,8,3,9,10,7,11,9,6,8,11,7,9,12,6,10,11,5,8,11,4,12,10,6,9,8,14,10,7,5,8,12,6,10,9,4,10,12,7,9,12,5,11,10,7,12,8,3,9,10,7,11,9,6,8,11,7,9,12,6,10,11,5,8,11,4,12,10,6,9,8,7,10,11,5,7,12,6,8,9,4,10,12,7,9,12,5,11,8,7,12,8,3,9,10,7,11,9,6,8,11,7,9,12,6,10,10,5,8,11,4,12,8,6,10,12,6,9,8,3,9,10,7,11,9,6,8,11,7,9,12,6,10,10,5,8,11,4,12,8,6,10,12,6,8,9,4&s='.$lastReelStr.'&reel_set2=7,10,3,8,11,5,13,4,12,6,9,8,10,5,13,11,8,9,4,12,7,10,13,11,8,9,6,11,8,7,12,3,10,13,8,6,10,12,5,9,8,11,7,10,6,8,13,12,7,9,11,4,10,8,12,9,6,11,8,12,3,10,8,4,11,7,9,12,5,10,11,6,8,13,9,11,8,10,7,9,11,10,5,12,8,11,6,12,9,10,7,11,10,4~3,10,11,4,12,8,5,9,12,6,8,7,11,9,6,10,12,5,8,12,4,11,9,3,10,8,6,12,7,11,4,10,6,12,8,7,12,6,10,9,4,11,12,5,11,10,6,12,9,6,12,4,11,12,7,11,9,3,10,12,5,8,11,7,12,9,6,10,11,7~4,11,10,5,12,8,7,9,5,8,10,6,11,9,7,12,10,5,8,11,3,12,9,7,11,12,7,11,10,5,8,4,9,12,6,11,9,7,12,5,8,11,3,10,9,6,12,7,8,11,5,10,7,12,4,9,7,11,6,8,12,3,10,8,4,9,11,6,12,7,11,5,8,6,12~5,12,11,6,12,10,7,11,8,4,12,9,6,11,10,7,12,11,5,9,12,7,11,10,6,8,12,3,11,10,7,12,9,6,10,11,4,8,12,7,9,11,5,12,10,6,12,8,7,9,10,5,11,7,12,9,3,11,8,6,10,12,4,11,10~7,10,5,8,11,3,14,4,12,6,9,8,10,5,14,11,8,9,4,12,7,10,14,11,8,9,6,10,8,7,12,3,11,14,10,6,8,12,5,9,8,11,7,10,6,8,14,12,7,9,11,4,10,8,10,9,6,11,9,12,3,10,8,14,11,7,9,12,5,10,11,6,8,12,14,11,8,10,7,9,14,12,5,11,8,10,6,12,9&reel_set1=3,10,11,7,12,9,6,11,12,7,10,8,12,11,7,12,9,10,7,9,11,5,12,8,7,10,11,6,9,10,5,9,12,3,10,11,5,8,9,7,10,11,7,10,9,6,10,12,7,10,8,3,11,10,6,9,10,7,9,10,5,12,8,7,10,11,6,9,12,5,9,10,4,10,12,13,11,8,5,10,8,7,12,9,6,11,12,7,10,8,3,10,8,6,9,10,7,9,11,5,12,8,7,10,8,6,9,8,5,9,12,7,8,10,5,9,8,6,10,11,4,8,9,6,11,12,7,10,8,7,11,8,6,9,10,7,9,11,5,12,8,7,10,11,6,9,8,5,9,8,6,9,8,5,10,12,7,10,11,4,12,9,6,11,12,7,10,8,3,11,8,6,9,10,7,9,8,5,12,8,7,10,11,6,9,8,5,9,12,6,11,9,13,10,8,7,10,8,4,12,9,6,10,12,7,10,8,7,10,8,6,9,10,7,9,11,5,12,8,7,10,11,6,9,8,5,9,12,7,10,11,6,8,12,7,10,8,4,12,9,6,11,12,7,10,8,3,11,8,6,9,10,7,9,11,5,12,8,7,10,11,6,9,8,7,9,12,4,11,8,6,12,8,4,10,11,4,12,9,6,11,12,7,10,8,3,11,8,6,9,10,7,9,11,5,12,8,7,10,11,6,9,8,5,9,12,7,8,10,5,9,12,10,11,4,12,8,6,11,12,7,10,8,7,11,8,7,10,8,3,11,8,6,9,10,7,9,11,5,12,8,7,10,11,6,9,8,5,9,12,7,8,10,5~3,10,11,4,12,8,5,7,12,6,8,7,11,2,9,6,10,12,5,8,12,4,11,9,3,10,8,6,12,7,8,11,4,12,10,6,9,2,6,10,9,4,11,9,5,11,10,6,12,9,6,12,4,11,2,12,7,11,12,3,10,11,5,8,11,7,12,9,6,10,11~4,11,10,5,12,8,7,9,5,8,3,10,7,11,9,7,12,10,5,8,11,3,12,9,7,11,12,7,11,10,5,12,6,4,12,9,2,12,6,11,10,5,8,11,6,10,9,7,8,2,5,11,12,7,10,12,4,9,7,11,6,8,12,3,10,8,4,9,11,6,12,7,11,5,8,6,12,9~5,12,11,6,12,10,3,11,8,4,12,9,6,11,10,7,12,11,5,9,12,2,11,10,6,8,12,3,11,10,7,12,9,2,10,11,4,8,12,7,9,11,5,12,10,6,12,8,7,9,2,10,5,11,7,12,9,3,11,8,6,10,12,4,11,10~3,11,10,7,8,5,12,14,7,11,10,4,12,9,6,11,12,14,3,11,12,7,10,11,3,10,12,6,14,10,7,9,11,7,8,14,4,8,10,6,9,10,5,9,10,7,8,12,14,7,9,7,10,14,3,12,4,11,6,14,8,4,12,9,5,10,11,6,14,10,9,3,4,10,7,14,12,4,8,11,5,9,14,7,12,10,6,14,8,3,12,14,11,5,10,11,9,6,12,8,6,14,8,4,12,9,7,8,11,3,10,14,9,5,8,14,6,12,9,7,11,14,7,10,9,3,12,14,6,11,5,8,4,12,14,5,9,11,10,8,6,12,9,7,14,11,10,7,12,9,6,8,12,10,4,14,11,8,5,9,12,10,6,9,11,3,8,9,7,14,11,8,5,12,9,7,10,8,6,11,9,4,12,9,7,14,8,6,10,11,8,5,9,11,7,10,14,8,4,10,8,6,11,9,12,5,8,11,14,3,12,9,7,11,9,5,10,8,7,9,11,6,12,10,4,14,11,8,5,10,12,9,6,14,8,4,12,10,7,11,14,5,12,11,9,3,10,14,7,12,10,6,9,11,14,7,8,5,14,10,3,11,9,5,12,8,4,14,9,6,10,11,7,12,8&reel_set3=3,12,3,11,10,12,7,8,12,10,11,12,7,8,12,11,9,12,7,10,11,12,8,11,12,10,11,12,6,9,8,5,12,10,7,11,12,6,8,10,4,12,9,5,10,12,8,6,12,8,9,10,12,11,6,12,8,5,12,10,7,11,12,7,10,8,6,12,9,11,10,12,5,10,11,7,12,9,11~12,8,9,6,12,11,9,12,11,9,7,8,12,11,10,12,6,11,12,3,9,11,7,12,8,5,12,11,9,12,11,10,6,12,11,5,12,9,7,11,12,9,11,12,7,11,12,9,11,12,8,11,6,9,11,5,12,9,7,12,10,11,12,9,11,12,6,9,12,7,8,12,5,11,9,7,12,10,4,9,11,7,12,11,9,6,12,11,7,12,10,4~12,7,8,12,11,5,12,11,6,12,11,5,12,10,11,12,5,9,12,7,8,12,4,10,11,6,10,11,12,10,11,12,8,11,12,10,11,12,6,11,12,5,9,11,12,4,11,8,12,11,10,12,11,8,12,11,7,12,9,6,12,11,10,12,11,8,12,9,7,11,12,3,11,8,12,6,10,12,8,5,9,12,10,7,12,8,9,11,12,10,8,7,12,10,6~11,8,9,11,6,12,11,9,12,11,7,8,11,5,10,11,6,12,11,3,9,11,7,9,11,5,8,12,11,9,12,11,6,10,11,5,9,11,7,12,11,4,9,11,8,7,9,11,4,12,11,8,6,11,9,5,11,9,7,11,10,12,11,9,12,11,6,9,11,7,8,11,5,12,9,7,11,10,12,9,11,7,12,11,9,6,12,11,7,10,11,12,9~7,12,8,5,10,12,11,3,8,3,10,12,7,8,12,5,9,12,7,10,12,5,8,11,12,10,11,12,6,9,8,5,12,10,7,11,12,6,8,10,4,12,9,5,10,12,8,6,12,11,9,10,12,11,6,12,8,5,12,10,7,11,12,7,10,8,6,12,9,11,10,12,5,10,11,7,12,9,11';
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
            else if( $slotEvent['slotEvent'] == 'doSpin' || $slotEvent['slotEvent'] == 'doBonus' ) 
            {
                $lastEvent = $slotSettings->GetHistory();
                $linesId = [];
                $linesId[0] = [1,1,1,1,1];
                $linesId[1] = [2,2,2,2,2];
                $linesId[2] = [3,3,3,3,3];
                $linesId[3] = [1,1,2,3,3];
                $linesId[4] = [3,3,2,1,1];
                $linesId[5] = [3,2,1,2,3];
                $linesId[6] = [1,2,3,2,1];
                $linesId[7] = [3,2,2,2,3];
                $linesId[8] = [2,1,1,1,2];
                $linesId[9] = [1,2,2,2,1];                
                $linesId[10] = [2,3,3,3,2];
                $linesId[11] = [2,2,3,2,2];
                $linesId[12] = [2,2,1,2,2];
                $linesId[13] = [2,3,2,3,2];
                $linesId[14] = [2,1,2,1,2];
                $linesId[15] = [1,2,1,2,1];
                $linesId[16] = [3,2,3,2,3];
                $linesId[17] = [3,1,2,1,3];
                $linesId[18] = [1,3,1,3,1];
                $linesId[19] = [3,1,3,1,3];

                $slotEvent['slotBet'] = $slotEvent['c'];
                $slotEvent['slotLines'] = 20;
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
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $_sum, $slotEvent['slotEvent']);
                    $bonusMpl = 1;
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.1f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '628' . substr($roundstr, 3, 8). '023';
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
                $strWinLine = '';
                $rs_p = -1;
                $rs_c = -1;
                $rs_m = -1;
                $rs_t = -1;
                $rs_more = 0;
                $str_rwd = '';
                $str_prg_m = '';
                $str_prg = '';
                $str_sty = '';
                $str_slm_lmi = '';
                $str_slm_lmv = '';
                $arr_slm_mp = [];
                $arr_slm_mv = [];
                $rs_more = 0;
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $str_initReel = $stack['init_reel'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $rs_more = $stack['rs_more'];
                    $str_rwd = $stack['rwd'];
                    $str_prg_m = $stack['prg_m'];
                    $str_prg = $stack['prg'];
                    $str_sty = $stack['sty'];
                    $str_slm_lmi = $stack['slm_lmi'];
                    $str_slm_lmv = $stack['slm_lmv'];
                    if($stack['slm_mp'] != ''){
                        $arr_slm_mp = explode(',', $stack['slm_mp']);
                    }
                    if($stack['slm_mv'] != ''){
                        $arr_slm_mv = explode(',', $stack['slm_mv']);
                    }
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $stack);  
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $lastReel = explode(',', $stack[0]['reel']);
                    $currentReelSet = $stack[0]['reel_set'];
                    $str_initReel = $stack[0]['init_reel'];
                    $rs_p = $stack[0]['rs_p'];
                    $rs_c = $stack[0]['rs_c'];
                    $rs_m = $stack[0]['rs_m'];
                    $rs_t = $stack[0]['rs_t'];
                    $rs_more = $stack[0]['rs_more'];
                    $str_rwd = $stack[0]['rwd'];
                    $str_prg_m = $stack[0]['prg_m'];
                    $str_prg = $stack[0]['prg'];
                    $str_sty = $stack[0]['sty'];
                    $str_slm_lmi = $stack[0]['slm_lmi'];
                    $str_slm_lmv = $stack[0]['slm_lmv'];
                    if($stack[0]['slm_mp'] != ''){
                        $arr_slm_mp = explode(',', $stack[0]['slm_mp']);
                    }
                    if($stack[0]['slm_mv'] != ''){
                        $arr_slm_mv = explode(',', $stack[0]['slm_mv']);
                    }
                }
                $reels = [];
                $wildReel = [];
                $scatterCount = 0;
                for($i = 0; $i < 5; $i++){
                    $reels[$i] = [];
                    $wildReel[$i] = [];
                    for($j = 0; $j < 3; $j++){
                        $reels[$i][$j] = $lastReel[$j * 5 + $i];
                        $wildReel[$i][$j] = 0;
                    }
                }
                if(count($arr_slm_mp) > 0){
                    for($i = 0; $i < count($arr_slm_mp); $i++){
                        $wildReel[$arr_slm_mp[$i] % 5][floor($arr_slm_mp[$i] / 5)] = $arr_slm_mv[$i];
                    }
                }
                $_lineWinNumber = 1;
                $_obf_winCount = 0;
                for( $k = 0; $k < $lines; $k++ ) 
                {
                    $_lineWin = '';
                    $firstEle = $reels[0][$linesId[$k][0] - 1];
                    $lineWinNum[$k] = 1;
                    $lineWins[$k] = 0;
                    $wildWin = 0;
                    $wildWinNum = 1;
                    $mul = 0;
                    if($firstEle == $wild){
                        if($wildReel[0][$linesId[$k][0] - 1] > 0){
                            $mul = $wildReel[0][$linesId[$k][0] - 1];
                        }
                    }
                    for($j = 1; $j < 5; $j++){
                        $ele = $reels[$j][$linesId[$k][$j] - 1];                        
                        if($ele == $wild && $wildReel[$j][$linesId[$k][$j] - 1] > 0){
                            $mul += $wildReel[$j][$linesId[$k][$j] - 1];
                        }
                        if($firstEle == $wild || $firstEle >= 13){
                            $firstEle = $ele;
                            $lineWinNum[$k] = $lineWinNum[$k] + 1;
                            if($j == 4){
                                $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                if($mul > 0){
                                    $lineWins[$k] = $lineWins[$k] * $mul;
                                }
                                $totalWin += $lineWins[$k];
                                $_obf_winCount++;
                                $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                    $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                }
                            }else if($j >= 2 && $ele == $wild){
                                $wildWin = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                if($mul > 0){
                                    $wildWin = $wildWin * $mul;
                                }
                                $wildWinNum = $lineWinNum[$k];
                            }
                        }else if($ele == $firstEle || $ele == $wild || $ele >= 13){
                            $lineWinNum[$k] = $lineWinNum[$k] + 1;
                            if($j == 4){
                                $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                if($mul > 0){
                                    $lineWins[$k] = $lineWins[$k] * $mul;
                                }
                                if($lineWins[$k] < $wildWin){
                                    $lineWins[$k] = $wildWin;
                                    $lineWinNum[$k] = $wildWinNum;
                                }
                                $totalWin += $lineWins[$k];
                                $_obf_winCount++;
                                $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                    $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                }
                            }
                        }else{
                            if($slotSettings->Paytable[$firstEle][$lineWinNum[$k]] > 0){
                                $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                if($mul > 0){
                                    $lineWins[$k] = $lineWins[$k] * $mul;
                                }
                                if($lineWins[$k] < $wildWin){
                                    $lineWins[$k] = $wildWin;
                                    $lineWinNum[$k] = $wildWinNum;
                                }
                                $totalWin += $lineWins[$k];
                                $_obf_winCount++;
                                $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                    $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                }   

                            }else{
                                $lineWinNum[$k] = 0;
                            }
                            break;
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
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $rs_p);

                $strLastReel = implode(',', $lastReel);
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 5; $i++){
                    $reelA[$i] = mt_rand(5, 12);
                    $reelB[$i] = mt_rand(5, 12);
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
                    $strOtherResponse = $strOtherResponse . '&rw=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
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
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin); 
                    if($rs_p >= 0 ){
                        $isState = false;
                        $spinType = 's';
                    }
                }
                
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($str_rwd != ''){
                    $strOtherResponse = $strOtherResponse . '&rwd=' . $str_rwd;
                }
                if($str_prg != ''){
                    $strOtherResponse = $strOtherResponse . '&prg=' . $str_prg;
                }
                if($str_sty != ''){
                    $strOtherResponse = $strOtherResponse . '&sty=' . $str_sty;
                }
                if($str_slm_lmv != ''){
                    $strOtherResponse = $strOtherResponse . '&slm_lmi=' . $str_slm_lmi . '&slm_lmv=' . $str_slm_lmv;
                }                
                if(count($arr_slm_mv) > 0){
                    $strOtherResponse = $strOtherResponse . '&slm_mp=' . implode(',', $arr_slm_mp) . '&slm_mv=' . implode(',', $arr_slm_mv);
                }
                if($currentReelSet >= 0){
                    $strOtherResponse = $strOtherResponse . '&reel_set=' . $currentReelSet;
                }
                if($rs_p >= 0){
                    $strOtherResponse = $strOtherResponse . '&rs=mc&rs_p=' . $rs_p;
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
                
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse . $strWinLine .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&l=20&sh=3&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&s='.$strLastReel.'&w='.$totalWin;
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"CurrentRespin":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'). ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
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

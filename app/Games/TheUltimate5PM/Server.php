<?php 
namespace VanguardLTE\Games\TheUltimate5PM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [2,11,6,2,7,1,7,8,3,10,4,10,3,1,7]);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', $lastEvent->serverResponse->RespinGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', $lastEvent->serverResponse->CurrentRespinGame);
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
                        $FreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                        $stack = $FreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') -1];
                    }
                    $bet = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $bet = '50.00';
                }
                $spinType = 's';
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    $strOtherResponse = $strOtherResponse . '&fs=' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&w=0.00&fsmul=1';
                    $currentReelSet = 1;
                }
                if(isset($stack)){
                    $currentReelSet = $stack['reel_set'];
                    $str_initReel = $stack['initReel'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $str_ds = $stack['ds'];
                    $str_stf = $stack['stf'];
                    $str_mo_wpos = $stack['mo_wpos'];
                    $accv = $stack['accv'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $str_rs = $stack['rs'];
                    $mo_c = $stack['mo_c'];
                    $mo_tv = $stack['mo_tv'];
                    $fsmore = $stack['fsmore'];

                    if($str_mo != ''){
                        $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                    }
                    if($str_stf != ''){
                        $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                    }
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($str_rs != ''){
                        $strOtherResponse = $strOtherResponse . '&rs=' . $str_rs;
                    }
                    if($mo_c >= 0){
                        $strOtherResponse = $strOtherResponse . '&mo_c=' . $mo_c;
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
                    if($accv >= 0){
                        $strOtherResponse = $strOtherResponse . '&accv=' . $accv;
                    }
                    if($mo_tv >= 0){
                        $strOtherResponse = $strOtherResponse . '&mo_tw='.($mo_tv * $bet).'&mo_tv=' . $mo_tv . '&w=' . ($mo_tv * $bet);
                    }
                    if($currentReelSet >= 0){
                        $strOtherResponse = $strOtherResponse . '&reel_set=' . $currentReelSet;
                    }
                    if($str_ds != ''){
                        $strOtherResponse = $strOtherResponse . '&ds='.$str_ds.'&dsa=1&dsam=v';
                    }

                    if($rs_c > 0 || $rs_t > 0){
                        $strOtherResponse = $strOtherResponse . '&accm=cp&acci=1&pw='. ($mo_tv * $bet);
                        if($str_mo_wpos != ''){
                            $strOtherResponse = $strOtherResponse . '&mo_wpos=' . $str_mo_wpos;
                        }
                        $strOtherResponse = $strOtherResponse . '&rs_p=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState');
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') > 0){
                        $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    }                    
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));

                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=2,11,6,2,7,1,7,8,3,10,4,10,3,1,7&balance='. $Balance .'&cfgs=5088&ver=2&mo_s=0&index=1&balance_cash='. $Balance .'&mo_v=1,2,3,4,5,6,7,8,10,25,50,75,100,500&def_sb=4,10,7,5,9&reel_set_size=14&def_sa=3,8,5,0,12&reel_set='.$currentReelSet.$strOtherResponse.'&balance_bonus=0.00&na='. $spinType .'&scatters=1~50,25,5,0,0~12,10,8,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"2998865",max_rnd_win:"5000"}}&wl_i=tbm~5000&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=3,8,5,0,12&sb=4,10,7,5,9&reel_set10=9,7,5,6,9,4,8,10,6,8,10,5,7,3,3,3,3,10,9,8,5,10,3,7,6,5,6,6,7,4,3,4,6,6,6,3,8,6,7,6,7,3,5,9,3,9,5,8,9,10,4~4,10,8,4,3,8,9,3,8,5,10,4,9,7,10,9,5,8,3,10,6,9,8,9,8,5,6,9,3,3,3,3,4,6,10,3,4,4,7,7,10,5,5,10,5,3,9,8,7,6,5,3,4,7,3,3,6,6,7,6,5~9,4,7,10,7,4,5,6,3,10,6,9,6,8,9,7,7,3,3,3,3,10,3,5,8,5,9,7,7,3,4,4,10,9,9,10,3,4,4,3,7,7,7,3,7,6,10,9,4,8,10,9,8,4,10,9,7,7,3,9,7,3,6,6,6,5,5,4,4,3,8,5,10,5,10,4,8,6,10,3,6,4,7,6,6~7,4,3,9,8,10,7,9,4,5,9,10,9,7,3,10,8,10,9,8,7,3,5,9,8,9,6,3,8,4,5,9,3,3,3,3,4,10,6,5,7,9,3,9,3,10,8,4,10,9,7,3,6,7,3,6,5,3,6,8,6,4,8,10,5,9,10,8,6~8,7,9,6,8,9,10,5,8,3,7,8,3,4,5,3,5,3,5,8,3,9,3,7,10,6,4,10,5,3,7,9,10,4,10,3,3,3,3,6,9,3,8,10,9,10,8,4,6,7,9,6,10,7,6,4,6,5,6,5,7,7,9,10,9,6,7,7,6,10,3,4,8,10&sc='. implode(',', $slotSettings->Bet) .'&defc=50.00&reel_set11=3,4,3,8,5,7,9,5,9,4,6,9,5,4,7,4,7,4,4,5,7,6,7,3,3,3,3,6,6,3,7,9,8,6,5,9,7,6,8,7,4,9,8,9,3,8,6,3,8,6,9,6,6,6,7,8,7,9,7,6,3,5,8,3,7,6,6,5,9,6,9,3,4,9,8,4,5,7,8~9,4,5,9,7,7,6,5,9,5,6,8,7,9,8,5,6,5,4,4,3,5,3,9,3,6,5,3,4,9,6,8,7,9,6,3,3,3,3,5,7,9,5,6,5,5,9,8,4,8,3,9,5,7,7,3,8,4,3,6,4,8,6,6,9,4,4,3,8,6,7,8,3,5,9~5,9,7,9,7,3,8,9,7,4,9,3,3,3,3,4,3,4,7,3,3,4,4,8,6,6,8,7,7,7,7,9,6,3,6,3,6,9,8,6,3,5,4,5,5,5,8,9,9,7,4,9,3,5,7,5,8,9,6,6,6,5,3,7,4,5,8,7,4,3,5,7,4,7,9~7,3,6,7,6,8,4,3,4,3,7,6,9,4,5,8,7,9,8,7,5,3,3,3,3,8,6,5,6,9,7,6,9,4,8,5,9,8,3,5,6,9,3,3,8,7,9,3~4,6,9,6,3,8,7,7,6,5,4,6,8,4,5,8,7,4,3,7,5,6,8,3,8,9,7,8,7,9,8,5,9,6,3,3,3,3,9,7,5,3,7,8,3,7,5,8,6,8,4,8,3,5,9,7,9,6,8,7,3,9,6,5,3,3,6,8,7,9,7,7,4,5,9&reel_set12=7,7,4,7,8,6,6,5,4,3,3,3,3,6,7,3,3,4,6,8,5,8,6,4,6,6,6,4,3,7,6,6,8,6,8,3,7,4,4,4,7,6,4,4,3,5,7,5,5,7,8~3,7,6,8,3,6,4,3,8,6,5,4,6,3,3,3,3,7,5,6,6,3,7,7,8,4,3,5,8,5,6,6,6,8,5,4,8,5,4,5,5,4,7,6,5,6,4~4,7,8,6,3,4,4,3,3,3,3,8,6,7,3,8,4,3,5,3,6,6,6,5,5,8,5,3,7,3,5,5,5,7,6,5,4,6,7,4,3,7,7,7,7,5,7,4,4,5,7,7,8,6,4~4,7,3,6,4,3,7,5,6,4,6,3,4,8,8,5,6,5,8,5,6,5,8,6,3,6,3,5,3,7,3,3,3,3,8,5,3,8,8,7,5,6,3,6,4,7,3,7,3,8,5,7,4,8,3,8,6,5,7,8,7,8,7,5,3~8,7,8,6,5,8,6,5,7,6,8,7,5,7,4,7,5,7,8,6,3,4,3,3,3,3,7,5,6,5,6,8,7,8,7,3,4,7,7,3,4,7,7,8,6,8,3,4,3,8,5&reel_set13=3,3,4,6,6,4,6,6,4,7,3,3,3,3,7,5,6,4,3,6,5,7,6,4,4,5,6,6,6,5,6,3,5,7,3,4,3,7,7,4,7,4,4,4,5,7,7,5,6,7,6,4,6,7,5,7,4,6~7,4,5,4,5,5,7,5,3,5,6,3,4,5,5,5,4,5,6,7,6,3,3,5,3,6,3,6,6,3,3,3,3,4,6,4,5,6,5,4,4,6,5,4,5,7,6,5,6,6,6,7,3,4,7,4,6,6,7,7,3,5,6,7,3,6,5~4,5,3,5,4,4,5,5,4,3,3,3,3,4,4,7,4,7,5,7,7,5,5,6,6,6,3,5,7,7,3,4,7,6,3,6,5,5,5,7,7,3,4,3,6,6,3,4,7,4,7,7,7,7,3,3,7,6,7,4,5,5,7,6,6,7~7,4,3,6,7,6,7,3,3,5,3,7,6,7,5,3,5,6,7,6,5,4,6,7,3,4,6,5,4,3,7,3,3,3,3,5,7,6,5,3,6,7,6,5,6,5,7,6,5,4,5,4,3,7,5,6,3,3,7,4,5,7,6,4,7,3,3~7,7,5,6,4,6,5,3,7,3,7,7,5,6,5,7,5,7,3,3,3,3,6,7,7,4,7,4,7,4,7,7,3,7,7,6,6,7,3,5,6,7,7,7,7,4,3,6,6,5,3,7,5,4,5,3,7,3,7,7,5,5,3,5&sh=3&wilds=2~400,100,50,0,0~1,1,1,1,1&bonuses=0&fsbonus=&c='.$bet.'&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;400,100,50,0,0;250,80,40,0,0;200,60,30,0,0;100,50,25,0,0;80,40,20,0,0;40,20,10,0,0;40,20,10,0,0;20,10,5,0,0;20,10,5,0,0;20,10,5,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0&l=20&reel_set0=4,12,9,11,10,3,7,8,3,7,12,6,8,3,1,6,9,3,12,3,5,9,3,3,3,3,4,11,2,12,11,10,9,4,8,10,11,10,6,5,2,9,4,5,8,10,5,7,12,8,4~5,7,5,11,8,9,6,10,9,11,7,4,12,4,10,3,12,8,10,3,11,6,10,2,7,3,0,10,8,4,5,11,12,2,0,3,9,6,3,3,3,3,4,3,6,10,10,7,3,11,8,4,8,1,3,7,6,10,11,0,10,7,9,0,3,12,5,12,5,9,4,8,7,2,9,7,5,12,11,6~4,5,10,3,3,11,8,6,5,10,11,8,10,12,6,1,5,7,0,11,9,4,7,3,3,3,3,9,10,6,9,4,12,6,11,3,0,3,8,12,12,5,0,7,7,2,9,2,4,8,3,11,12~12,11,2,6,10,5,10,6,8,12,9,8,11,9,11,3,7,0,2,11,7,11,6,1,12,10,3,7,3,3,3,3,9,6,4,8,11,2,5,8,4,9,10,9,2,3,11,6,9,4,3,0,10,7,8,12,10,8,0,3,8,12,5~12,9,10,9,4,3,7,10,5,4,10,4,8,1,11,11,6,10,12,3,9,11,5,12,9,3,5,8,11,9,12,5,12,8,5,2,8,3,3,3,3,7,3,2,8,11,3,10,4,10,12,6,5,7,12,10,9,12,11,11,9,8,8,6,9,7,3,6,10,8,7,6,10,4,10,11,11,9,9,8,7,9&s='.$lastReelStr.'&accInit=[{id:0,mask:"cp;mp"},{id:1,mask:"cp;mp"}]&reel_set2=4,8,6,7,11,6,9,9,8,11,6,11,10,6,9,4,7,9,8,10,6,9,4,2,4,4,4,4,9,8,12,8,4,5,1,9,6,4,5,5,7,8,12,4,9,10,4,5,4,12,8,12,11,7,8~9,12,10,12,4,10,10,0,10,5,4,0,12,2,11,4,10,12,7,4,4,6,11,11,4,4,4,4,12,12,7,4,5,10,4,9,5,11,8,10,5,10,6,8,7,5,12,9,11,2,4,10,12,12,12,10,5,8,11,11,9,11,11,6,0,9,5,8,9,12,1,5,4,8,0,6,4,7,12,6,11~7,7,12,6,12,0,10,9,11,2,10,12,5,5,11,6,2,9,7,11,5,0,9,0,5,9,7,6,12,6,6,0,5,8,6,11,8,1,4,7,4,10,8,11,4,12,10,8,2,7,11,4,5,12,10,11,10,9,4,10,9,8,5,0,12,4,1,10,6~12,4,10,7,2,5,8,4,9,12,4,11,10,0,6,11,8,10,6,5,4,4,11,5,9,4,0,7,2,1,11,6,11,10,9,4,4,4,4,0,2,8,8,7,9,4,7,5,9,6,9,12,1,8,4,9,10,6,10,8,6,2,5,8,9,12,10,2,12,11,8,8,1,10,7,10,12,11~12,5,2,7,10,4,6,9,4,1,11,11,9,4,8,10,4,12,9,7,12,6,11,11,10,5,8,4,6,10,4,4,4,4,9,11,4,12,10,9,10,9,8,12,6,8,1,11,6,7,8,2,8,4,9,7,2,12,5,10,7,11,2,9,4,10,4,5&reel_set1=11,12,7,11,6,9,7,3,5,12,10,11,6,8,3,9,6,8,12,7,11,4,2,10,12,3,11,4,10,10,6,3,3,3,3,10,3,5,7,10,8,9,3,9,12,8,12,5,2,10,3,8,1,7,11,8,7,6,3,6,5,7,12,9,10,9,6,12,4,9,4~7,3,7,0,8,12,4,10,2,10,1,11,3,6,0,10,12,3,2,5,9,11,6,3,3,3,3,12,5,8,10,4,9,4,9,5,11,7,11,3,9,11,4,5,8,10,12,3,9,11,8,10,5,4~10,9,8,12,9,12,9,3,5,9,4,11,7,5,11,10,11,12,2,0,12,9,12,11,3,6,0,3,3,11,6,8,3,3,3,3,6,12,4,7,8,6,6,3,7,6,8,10,5,4,4,2,10,11,0,4,11,0,12,5,5,7,7,3,3,1,4,7,0,11,2,3,12,10,5~6,9,0,1,3,10,8,2,8,3,5,9,7,9,12,10,4,3,10,7,5,11,8,10,5,9,10,3,3,3,3,4,12,9,11,12,11,9,11,6,5,6,12,2,8,0,3,7,4,0,3,10,8,11,2,11,12,7,9,6,10,8~6,10,10,5,11,8,3,4,8,11,1,11,5,8,6,2,9,11,9,8,5,2,10,3,4,12,3,3,3,3,8,11,6,10,12,10,9,4,12,7,3,7,3,5,9,7,9,9,10,10,12,11,9,7,12,8&reel_set4=6,12,6,9,6,10,4,4,7,4,3,3,12,6,11,2,9,7,10,1,12,6,12,3,4,6,6,3,9,11,8,7,6,8,11,4,7,6,3,3,3,3,12,12,3,10,12,6,3,9,8,12,11,6,3,10,11,7,6,10,10,6,11,8,10,9,12,12,3,9,2,10,10,8,6,10,7,10,10,7,12,12,9~4,3,10,0,2,12,10,11,0,4,9,7,9,6,8,3,6,9,6,6,9,10,6,3,10,11,12,6,0,6,9,3,9,7,3,3,3,3,11,0,11,12,11,12,8,6,8,4,9,8,8,6,7,8,2,6,1,8,4,6,9,8,4,4,11,12,6,11,11,8,10,11,9,7~3,12,0,11,8,7,11,4,11,7,11,9,6,10,12,6,8,3,10,8,11,0,6,11,9,7,4,11,2,3,3,3,3,10,10,12,12,1,0,9,1,8,9,0,4,10,7,8,7,2,7,4,12,3,6,10,12,9,4,3,10,4,6~7,6,3,0,6,10,8,9,11,7,10,6,12,9,6,10,11,0,7,9,3,3,0,2,6,0,2,3,2,3,3,3,3,10,9,8,10,10,12,10,8,1,6,8,6,11,6,10,12,2,4,3,8,6,11,12,8,9,7,6,11,1,11~3,9,9,6,11,9,8,7,9,3,8,3,11,12,2,6,2,10,12,10,4,7,1,9,4,9,1,12,3,3,3,3,11,7,6,12,2,10,6,11,11,8,6,11,6,8,7,8,9,10,12,6,4,12,8,10,7,8,10,10,4,3,6&reel_set3=10,10,11,10,1,8,6,7,5,7,10,8,10,6,3,7,5,3,6,5,12,5,11,6,3,11,3,3,3,3,9,10,5,11,8,2,9,10,10,2,7,12,5,11,9,6,3,6,11,9,8,12,3,11,9,5,5,6,3~0,12,6,10,9,6,5,5,6,5,5,12,7,9,12,12,9,10,11,7,8,10,6,12,3,0,8,10,8,12,0,8,7,3,3,3,3,9,7,8,5,6,8,12,5,9,11,3,5,8,11,9,0,5,3,9,12,5,5,8,6,5,5,8,9,2,11,3,6,11,12,9,1,2~0,7,9,11,8,3,0,11,6,6,5,3,7,11,8,12,6,5,12,9,10,8,3,3,3,3,0,8,5,12,10,10,2,7,11,7,10,5,6,11,6,1,10,7,11,12,3,9,12,2,10,3~0,2,5,10,11,5,6,5,6,8,1,7,8,7,12,11,7,9,8,0,10,8,12,5,6,10,3,3,3,3,2,3,9,11,3,9,3,0,12,3,5,12,1,10,11,10,8,9,11,6,2,9,5,8,10,7,9,11~2,8,6,2,1,7,9,10,12,9,11,5,6,12,5,11,7,3,1,9,11,10,9,12,3,3,3,3,12,10,9,5,6,11,3,5,8,3,5,8,12,10,8,10,7,3,8,5,2,9,10,11,6&reel_set6=9,3,3,6,3,5,9,5,5,3,6,6,9,4,12,3,2,8,10,6,6,4,3,3,3,3,6,5,8,11,10,11,8,12,1,9,9,4,4,9,9,10,11,9,8,11,12,12,8,8~10,9,6,12,2,12,9,4,8,0,5,10,3,12,12,5,11,3,4,3,12,12,3,3,3,3,8,4,5,6,10,11,0,5,6,11,9,6,11,10,11,0,8,10,10,4,5,1,4,11~1,6,9,11,4,4,11,5,3,0,5,11,12,6,9,6,9,1,0,12,10,8,10,11,4,5,0,2,3,3,3,3,8,11,3,10,4,9,10,6,6,8,3,8,4,5,11,5,12,10,9,10,4,12,0,12,12,5,3,2~10,0,12,1,8,11,6,8,5,2,6,9,5,8,5,4,10,2,11,6,3,0,11,9,12,10,6,8,8,3,9,3,3,3,3,6,4,11,3,10,8,2,4,12,3,3,10,5,4,11,12,8,2,8,5,12,10,0,5,1,6,3,10,9,11,9~9,8,5,10,8,3,3,11,6,3,3,12,10,8,4,8,5,12,9,4,12,10,11,3,3,3,3,4,12,11,1,10,10,9,6,4,2,6,9,11,8,12,3,6,5,10,9,2,9,11,8&reel_set5=12,9,5,7,11,7,3,5,4,11,5,7,3,3,12,8,12,11,8,7,11,7,4,11,8,11,10,3,3,3,3,12,9,4,7,4,10,7,1,3,8,7,9,2,12,5,11,3,7,11,9,10,12,8,12,2,3,7,7,12,10~10,7,3,12,7,0,9,11,5,7,10,4,9,8,5,8,1,9,11,8,10,10,12,0,10,12,3,3,3,3,7,3,8,10,7,8,11,4,9,3,4,2,5,12,9,9,11,4,2,10,4,7,5,7,8,4,9,8~3,9,8,5,4,10,0,9,7,3,3,12,10,2,10,1,12,3,12,3,7,7,5,5,4,9,11,9,1,3,3,3,3,0,5,12,0,7,8,11,4,11,10,11,10,12,4,2,5,7,11,4,11,10,4,12,0,10,8,9,5,8~9,7,8,5,10,12,8,10,9,5,10,9,12,7,11,1,11,2,10,9,3,3,3,3,11,8,1,8,7,4,7,5,10,9,0,7,0,7,3,11,0,7,8,4,11,2~9,10,4,2,7,5,12,7,3,10,8,11,9,5,7,9,10,7,10,3,8,12,3,4,12,9,7,10,11,7,10,11,2,5,11,3,3,3,3,11,9,8,12,2,8,9,5,1,12,8,12,1,3,4,11,7,9,12,7,9,4,11,2,5,11,9,8,7,8,10,8,7,10,7,12,3&reel_set8=7,10,12,7,10,9,6,9,6,8,7,9,11,9,11,5,8,12,5,6,7,10,9,12,4,9,7,3,12,7,12,11,3,3,3,3,4,11,6,11,5,3,10,7,3,3,6,4,3,6,8,5,11,8,12,8,3,8,4,6,10,12,3,7,8,3,9,5,8,11,10,4~11,9,8,9,10,11,4,5,12,3,4,3,12,11,10,9,7,6,10,8,5,12,5,7,9,4,3,12,11,3,3,3,3,5,7,4,12,6,3,11,9,6,10,12,7,10,11,9,5,12,9,8,6,5,11,3,4,8,10,3,6,3,5,8~4,3,3,11,10,6,9,4,9,10,12,4,11,8,7,8,3,10,6,8,3,3,12,11,3,3,3,3,5,12,11,3,9,5,5,11,4,6,10,8,12,5,10,12,7,4,5,8,9,6,11,7,9,7~8,10,11,9,5,3,3,5,12,9,4,6,7,3,8,5,6,5,4,7,8,10,7,9,6,12,9,7,8,3,9,11,8,9,3,5,8,3,3,3,3,11,12,3,7,11,3,12,8,7,8,11,10,6,3,4,12,10,6,7,11,12,10,6,5,12,6,11,8,11,3,7,12,10,9,10,4,9,6~6,3,11,5,11,3,4,3,12,10,6,9,7,3,9,7,4,3,6,8,7,12,3,8,10,9,11,7,11,12,10,4,5,10,5,10,3,3,3,3,7,12,8,9,12,9,3,7,12,8,9,12,9,6,10,11,10,8,6,9,6,8,10,5,10,12,8,11,5,3,4,8,5,11,12,11,8,4,7,9&reel_set7=10,11,11,3,3,12,3,12,3,9,8,9,3,3,12,3,8,3,9,3,8,10,3,3,10,8,3,1,3,3,3,3,9,10,8,9,12,3,3,12,3,11,3,3,9,12,9,3,11,3,8,11,3,3,10,3,8,3,3,2,3,3,10~12,11,3,10,10,9,9,3,3,11,3,8,3,3,2,3,10,0,3,3,11,0,3,3,3,3,3,9,3,3,10,12,11,3,9,3,11,3,12,3,3,8,3,10,0,12,8,3,8,9,3,1,3~3,12,0,8,11,3,3,1,3,8,3,10,3,11,3,12,12,1,3,11,3,3,12,3,3,2,3,3,3,3,9,10,3,3,10,11,8,3,9,11,0,3,10,8,2,12,3,9,9,3,11,0,8,3,12,3,9,9,10,0~3,3,3,3~6,4,6,12,7,1,8,12,9,7,1,5,6,8,4,7,5,10,12,11,8,11,5,6,12,11,4,10,9,5,9,8,9,12,11,7,8,9,8,10,5,6,7,10,10,9,11,10,4&reel_set9=3,8,7,7,6,9,11,9,5,6,11,3,5,3,7,6,9,10,4,11,9,7,6,10,8,7,10,8,5,3,11,10,9,10,6,10,11,3,3,3,3,7,7,3,11,6,8,9,5,4,9,11,7,9,6,4,8,3,5,6,3,5,6,10,11,4,10,8,5,3,4,7,4,11,9,11,6,8,4,9,10,7~5,11,9,5,5,6,4,8,6,11,6,3,4,7,9,10,4,8,10,8,10,5,5,4,7,3,7,3,5,10,9,11,10,5,11,3,4,3,3,3,3,9,8,4,7,11,7,4,11,7,8,9,7,9,6,11,9,3,10,11,6,5,5,3,8,3,10,6,10,4,3,11,9,3,6,11,9,5,4,8,9~3,6,11,4,3,11,9,4,9,10,3,7,10,8,3,3,3,3,11,5,5,11,3,4,4,7,4,10,8,3,9,6,10,6,6,6,10,4,5,5,9,8,7,3,11,10,11,9,7,8,7,6~8,7,5,11,9,4,6,10,9,5,8,9,6,5,9,10,6,3,10,8,3,7,3,6,7,10,6,7,3,3,3,3,5,6,5,11,8,10,4,3,10,11,7,11,9,6,9,11,3,11,8,11,10,3,8,3,10,11,4,9,7~8,5,7,8,11,4,8,6,3,7,5,9,11,9,10,9,11,3,10,5,3,7,8,5,3,7,3,3,3,3,6,4,11,8,10,5,10,8,10,4,3,6,8,9,6,11,3,4,6,10,7,9,7,11,9,8';
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
            else if( $slotEvent['slotEvent'] == 'doSpin') 
            {
                $lastEvent = $slotSettings->GetHistory();
                $linesId = [];
                $linesId[0] = [2, 2, 2, 2, 2];
                $linesId[1] = [1, 1, 1, 1, 1];
                $linesId[2] = [3, 3, 3, 3, 3];
                $linesId[3] = [1, 2, 3, 2, 1];
                $linesId[4] = [3, 2, 1, 2, 3];
                $linesId[5] = [2, 1, 1, 1, 2];
                $linesId[6] = [2, 3, 3, 3, 2];
                $linesId[7] = [1, 1, 2, 3, 3];
                $linesId[8] = [3, 3, 2, 1, 1];
                $linesId[9] = [2, 3, 2, 1, 2];
                $linesId[10] = [2, 1, 2, 3, 2];
                $linesId[11] = [1, 2, 2, 2, 1];
                $linesId[12] = [3, 2, 2, 2, 3];
                $linesId[13] = [1, 2, 1, 2, 1];
                $linesId[14] = [3, 2, 3, 2, 3];
                $linesId[15] = [2, 2, 1, 2, 2];
                $linesId[16] = [2, 2, 3, 2, 2];
                $linesId[17] = [1, 1, 3, 1, 1];
                $linesId[18] = [3, 3, 1, 3, 3];
                $linesId[19] = [1, 3, 3, 3, 1];
                if($slotEvent['slotEvent'] == 'doBonus'){
                    $slotEvent['slotBet'] = $lastEvent->serverResponse->bet ?? 0;
                }else{
                    $slotEvent['slotBet'] = $slotEvent['c'];
                }
                $slotEvent['slotLines'] = 20;
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                {
                    $slotEvent['slotEvent'] = 'freespin';
                }
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') && $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') > 0 ) 
                {
                    $slotEvent['slotEvent'] = 'respin';
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
                $freeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'freespin' || $slotEvent['slotEvent'] == 'respin'){
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.1f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '6074458' . substr($roundstr, 4, 10);
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
                $strWinLine = '';
                $winLineCount = 0;
                $str_initReel = '';
                $initReel = [];
                $str_mo = '';
                $str_mo_t = '';
                $str_ds = '';
                $str_stf = '';
                $str_mo_wpos = '';
                $fsmore = 0;
                $accv = -1;
                $rs_c = -1;
                $rs_m = -1;
                $rs_t = -1;
                $str_rs = '';
                $mo_c = -1;
                $mo_tv = 0;
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $str_initReel = $stack['initReel'];
                    $initReel = explode(',', $stack['initReel']);
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $str_ds = $stack['ds'];
                    $str_stf = $stack['stf'];
                    $str_mo_wpos = $stack['mo_wpos'];
                    $accv = $stack['accv'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $str_rs = $stack['rs'];
                    $mo_c = $stack['mo_c'];
                    $mo_tv = $stack['mo_tv'];
                    $fsmore = $stack['fsmore'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $lastReel = explode(',', $stack[0]['reel']);
                    $initReel = explode(',', $stack[0]['initReel']);
                    $currentReelSet = $stack[0]['reel_set'];
                    $str_initReel = $stack[0]['initReel'];
                    $str_mo = $stack[0]['mo'];
                    $str_mo_t = $stack[0]['mo_t'];
                    $str_ds = $stack[0]['ds'];
                    $str_stf = $stack[0]['stf'];
                    $str_mo_wpos = $stack[0]['mo_wpos'];
                    $accv = $stack[0]['accv'];
                    $rs_c = $stack[0]['rs_c'];
                    $rs_m = $stack[0]['rs_m'];
                    $rs_t = $stack[0]['rs_t'];
                    $str_rs = $stack[0]['rs'];
                    $mo_c = $stack[0]['mo_c'];
                    $mo_tv = $stack[0]['mo_tv'];
                    $fsmore = $stack[0]['fsmore'];
                }
                $reels = [];
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;
                for($i = 0; $i < 5; $i++){
                    $reels[$i] = [];
                    for($j = 0; $j < 3; $j++){
                        if($slotEvent['slotEvent'] != 'respin' && $rs_c > 0){
                            $reels[$i][$j] = $initReel[$j * 5 + $i];
                        }else{
                            $reels[$i][$j] = $lastReel[$j * 5 + $i];
                        }
                        if($reels[$i][$j] == $scatter){
                            $scatterCount++;
                            $scatterPoses[] = $j * 5 + $i;   
                        }
                    }
                }
                $isCalcWinLine = false;
                if($slotEvent['slotEvent'] != 'respin'){
                    if($slotEvent['slotEvent'] == 'freespin'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    }
                    $isCalcWinLine = true;
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
                        for($j = 1; $j < 5; $j++){
                            $ele = $reels[$j][$linesId[$k][$j] - 1];
                            if($firstEle == $wild){
                                $firstEle = $ele;
                                $lineWinNum[$k] = $lineWinNum[$k] + 1;
                                if($j == 4){
                                    $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                    $totalWin += $lineWins[$k];
                                    $_obf_winCount++;
                                    $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                    for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                        $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                    }
                                }else if($j >= 2 && $ele == $wild){
                                    $wildWin = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                    $wildWinNum = $lineWinNum[$k];
                                }
                            }else if($ele == $firstEle || $ele == $wild){
                                $lineWinNum[$k] = $lineWinNum[$k] + 1;
                                if($j == 4){
                                    $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
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
                    if($scatterCount >= 3){
                        $scatterMuls = [0,0,0,5,25,50];
                        $scatterWin = $betline * $lines * $scatterMuls[$scatterCount];
                        $totalWin = $totalWin + $scatterWin;
                    }
                }else if($rs_t > 0){
                    $totalWin = $totalWin + $mo_tv * $betline;
                }
                if($rs_m > 0 || $rs_t > 0){
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') + 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', $rs_m);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', $rs_c);
                }
                
                $spinType = 's';
                if( $totalWin > 0) 
                {
                    $spinType = 'c';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }
                $_obf_totalWin = $totalWin;
                if( $isCalcWinLine == true && $scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin') 
                {
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0 ) 
                    {
                        $freeNums = [0,0,0,8,10,12];
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freeNums[$scatterCount]);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                    }
                }
                if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                }

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
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $isEnd = false;
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
                    {
                        $spinType = 'c';
                        $isEnd = true;
                        $strOtherResponse = '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&fsend_total=1&fsmul_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
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
                    if($str_ds != ''){
                        $strOtherResponse = $strOtherResponse . '&ds='.$str_ds.'&dsa=1&dsam=v';
                    }
                    $strOtherResponse = $strOtherResponse . '&acci=0&accm=cp';
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);                    
                    if($isCalcWinLine == false){
                        $spinType = 's';
                    }
                    if($isCalcWinLine== true && $scatterCount >= 3 ){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse = '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fs=1&fsres=0.00&psym=1~' . $scatterWin . '~' . implode(',', $scatterPoses); 
                    } 
                }
                if($rs_c > 0 || $rs_t > 0){
                    $strOtherResponse = $strOtherResponse . '&accm=cp&acci=1';
                    if($rs_c > 0){
                        $isState = false;
                        $arr_mo = explode(',', $str_mo);
                        $pw = 0;
                        for($k = 0; $k < 15; $k++){
                            $pw += $arr_mo[$k] * $betline * $lines;
                        }
                        $strOtherResponse = $strOtherResponse . '&pw=' . $pw;
                    }else{
                        $strOtherResponse = $strOtherResponse . '&mo_tv=' . $mo_tv;
                    }
                    if($str_mo_wpos != ''){
                        $strOtherResponse = $strOtherResponse . '&mo_wpos=' . $str_mo_wpos;
                    }
                    $strOtherResponse = $strOtherResponse . '&rs_p=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState');
                }
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                }
                if($str_stf != ''){
                    $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($mo_c >= 0){
                    $strOtherResponse = $strOtherResponse . '&mo_c=' . $mo_c;
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
                if($str_rs != ''){
                    $strOtherResponse = $strOtherResponse . '&rs=' . $str_rs;
                }
                if($accv >= 0){
                    $strOtherResponse = $strOtherResponse . '&accv=' . $accv;
                }
                if($mo_tv >= 0){
                    $strOtherResponse = $strOtherResponse . '&mo_tw='.($mo_tv * $betline).'&mo_tv=' . $mo_tv;
                }
                if($currentReelSet >= 0){
                    $strOtherResponse = $strOtherResponse . '&reel_set=' . $currentReelSet;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .$strWinLine .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb . '&l=20&c=' . $betline.'&st=rect&sw=5&sh=3&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&s='.$strLastReel .'&w='.$totalWin;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) && ($rs_t > 0 || $rs_c < 0)) 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"RespinGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') . ',"CurrentRespinGame":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'). ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount'). ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if(($isCalcWinLine == true && $scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin') || ($slotEvent['slotEvent'] != 'respin' && $rs_c > 0 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0)) 
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
                $slotSettings->InternalError('TheUltimate5DBCommit : ' . $e);
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

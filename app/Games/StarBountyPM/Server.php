<?php 
namespace VanguardLTE\Games\StarBountyPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [4,8,4,4,4,4,5,9,5,5,5,5,6,10,6,6,6,6,7,11,7,7,7,7]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);
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
                    $bet = '50.00';
                }
                $spinType = 's';
                $fsmore = 0;
                $rs_more = 0;
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if(isset($stack)){
                    $str_initReel = $stack['initReel'];
                    $str_tmb = $stack['tmb'];
                    $rs_more = $stack['rs_more'];
                    $str_wlc_v = $stack['wlc_v'];
                    $fsmore = $stack['fsmore'];
                    $str_accv = $stack['accv'];
                    $str_wlm_v = $stack['wlm_v'];
                    $str_wlm_p = $stack['wlm_p'];
                    $str_rwd = $stack['rwd'];
                    $str_srf = $stack['srf'];
                    $str_rs = $stack['rs'];
                    $currentReelSet = $stack['reel_set'];
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=0';
                    }
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($str_tmb != ''){
                        $strOtherResponse = $strOtherResponse . '&tmb=' . $str_tmb;
                    }
                    if($str_accv != ''){
                        $strOtherResponse = $strOtherResponse . '&accm=cp&acci=0&accv=' . $str_accv;
                    }
                    if($str_rwd != ''){
                        $strOtherResponse = $strOtherResponse . '&rwd=' . $str_rwd;
                    }
                    if($str_wlm_v != ''){
                        $strOtherResponse = $strOtherResponse . '&wlm_v='. $str_wlm_v .'&wlm_p=' . $str_wlm_p;
                    }
                    if($str_srf != ''){
                        $strOtherResponse = $strOtherResponse . '&srf=' . $str_srf;
                    }
                    $wlc_vs = [];
                    if($str_wlc_v != ''){
                        $old_wlc_vs = explode(';', $str_wlc_v);
                        foreach($old_wlc_vs as $index=>$wlc){
                            $arr_wlc = explode('~', $wlc);
                            if(isset($arr_wlc[1])){
                                $arr_wlc[1] = str_replace(',', '', $arr_wlc[1]) / $oldBetLine * $bet;
                            }
                            $wlc_vs[] = implode('~', $arr_wlc);
                        }
                        if(count($wlc_vs) > 0){
                            $strOtherResponse = $strOtherResponse . '&wlc_v=' . implode(';', $wlc_vs);
                        }
                    }
                }
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 )
                {
                    $fs = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                    $strOtherResponse = $strOtherResponse . '&fs=' . $fs . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&fsmul=1';
                    if($fsmore > 0){
                        $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                    }
                }
                    
                if($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') > 0){
                    $rs_c = 1;
                    $rs_m = 1;
                    if($rs_more > 0){
                        $rs_c = $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState');
                        $rs_m = $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState');
                    }
                    $strOtherResponse = $strOtherResponse . '&rs=mc&rs_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') . '&rs_p=' . ($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') - 1) . '&rs_c='. $rs_c .'&rs_m=' . $rs_m;   
                }
                $Balance = $slotSettings->GetBalance();  
                $response = 'def_s=4,8,4,4,4,4,5,9,5,5,5,5,6,10,6,6,6,6,7,11,7,7,7,7&balance='. $Balance .'&cfgs=3445&ver=2&index=1&balance_cash='. $Balance .'&def_sb=15,14,12,5,6,11&reel_set_size=12&def_sa=12,13,12,14,15,11&reel_set=0&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0~24,23,22,21,20,19,18,17,16,15,14,13,12,11,10,9,8,7,6,5,4,3,0,0~1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1&gmb=0,0,0&rt=d&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime='. floor(microtime(true) * 1000) .'&sa=12,13,12,14,15,11&sb=15,14,12,5,6,11&reel_set10=12,9,15,12,13,6,4,11,10,14,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,14,8,11,9,5,4~12,9,15,12,13,6,4,11,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,14,8,11,9,5,4~12,9,15,12,13,6,4,11,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,14,8,11,9,5,4&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=100.00&reel_set11=12,9,15,12,13,6,4,11,10,14,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,14,8,11,9,5,4~12,9,15,12,13,6,4,11,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,14,8,11,9,5,4~12,9,15,12,13,6,4,11,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,14,8,11,9,5,4&purInit_e=1&sh=4&wilds=2~0,0,0,0,0,0~1,1,1,1,1,1;3~0,0,0,0,0,0~1,1,1,1,1,1;17~0,0,0,0,0,0~1,1,1,1,1,1&bonuses=0&fsbonus=&c='. $bet .'&sver=5&counter=2&paytable=0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;300,150,40,20,0,0;200,60,24,16,0,0;100,40,20,12,0,0;100,40,20,12,0,0;60,30,12,8,0,0;60,30,12,8,0,0;40,16,6,4,0,0;40,16,6,4,0,0;30,10,4,2,0,0;30,10,4,2,0,0;20,8,2,1,0,0;20,8,2,1,0,0;0,0,0,0,0,0;0,0,0,0,0,0&l=20&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=12,9,15,12,13,6,4,11,10,14,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4&s='.$lastReelStr.'&accInit=[{id:0,mask:"cp;mp"}]&reel_set2=12,9,15,12,13,6,4,11,10,14,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4&t=243&reel_set1=12,9,15,12,13,6,4,11,10,14,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4&reel_set4=12,9,15,12,13,6,4,11,10,14,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4&purInit=[{type:"fs",bet:1500}]&reel_set3=12,9,15,12,13,6,4,11,10,14,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4&reel_set6=12,9,15,12,13,6,4,11,10,14,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4&reel_set5=12,9,15,12,13,6,4,11,10,14,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4&reel_set8=12,9,15,12,13,6,4,11,10,14,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4&reel_set7=12,9,15,12,13,6,4,11,10,14,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4~12,9,15,12,13,6,4,11,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,1,14,8,11,9,5,4&reel_set9=12,9,15,12,13,6,4,11,10,14,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,14,8,11,9,5,4~12,9,15,12,13,6,4,11,3,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,14,8,11,9,5,4~12,9,15,12,13,6,4,11,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,14,8,11,9,5,4~12,9,15,12,13,6,4,11,10,14,2,6,15,6,15,7,13,12,5,14,11,5,13,8,4,10,7,9,7,10,8,14,8,11,9,5,4&total_bet_min=10.00';
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
                if($pur >= 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $betline * $lines * 75;
                }
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '56671' . substr($roundstr, 4, 9);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $leftFreeGames = 0;

                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []);
                }
                
                $wild = '2';
                $scatter = '1';
                $empty = '14';
                $Balance = $slotSettings->GetBalance();
                $totalWin = 0;
                $bonusMpl = 1;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $str_initReel = '';
                $str_tmb = '';
                $rs_more = 0;
                $str_wlc_v = '';
                $currentReelSet = 0;
                $fsmore = 0;
                $str_accv = '';
                $str_wlm_v = '';
                $str_wlm_p = '';
                $str_rwd = '';
                $str_srf = '';
                $str_rs = '';
                $subScatterReel = null;
                if($slotEvent['slotEvent'] == 'freespin' || $isTumb == true){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_initReel = $stack['initReel'];
                    $str_tmb = $stack['tmb'];
                    $rs_more = $stack['rs_more'];
                    $str_wlc_v = $stack['wlc_v'];
                    $fsmore = $stack['fsmore'];
                    $str_accv = $stack['accv'];
                    $str_wlm_v = $stack['wlm_v'];
                    $str_wlm_p = $stack['wlm_p'];
                    $str_rwd = $stack['rwd'];
                    $str_srf = $stack['srf'];
                    $str_rs = $stack['rs'];
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
                    $str_tmb = $stack[0]['tmb'];
                    $rs_more = $stack[0]['rs_more'];
                    $str_wlc_v = $stack[0]['wlc_v'];
                    $fsmore = $stack[0]['fsmore'];
                    $str_accv = $stack[0]['accv'];
                    $str_wlm_v = $stack[0]['wlm_v'];
                    $str_wlm_p = $stack[0]['wlm_p'];
                    $str_rwd = $stack[0]['rwd'];
                    $str_srf = $stack[0]['srf'];
                    $str_rs = $stack[0]['rs'];
                    $currentReelSet = $stack[0]['reel_set'];
                }

                $reels = [];
                $wildReel = [];
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;
                for($i = 0; $i < 24; $i++){
                    if($lastReel[$i] == $scatter){
                        $scatterCount++;
                        $scatterPoses[] = $i;
                    }
                }

                $_lineWinNumber = 1;
                $_obf_winCount = 0;
                $isNewTumb = false;
                $wlc_vs = [];
                if($str_wlc_v != ''){
                    $old_wlc_vs = explode(';', $str_wlc_v);
                    foreach($old_wlc_vs as $index=>$wlc){
                        $arr_wlc = explode('~', $wlc);
                        if(isset($arr_wlc[1])){
                            $arr_wlc[1] = str_replace(',', '', $arr_wlc[1]) / $oldBetLine * $betline;
                            $totalWin = $totalWin + $arr_wlc[1];
                            $isNewTumb = true;
                        }
                        $wlc_vs[] = implode('~', $arr_wlc);
                    }
                }
                if($str_rs == 'mc'){
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
                    if($scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $scatterCount);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                    }
                    if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                    }
                }
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 6; $i++){
                    $reelA[$i] = mt_rand(4, 8);
                    $reelB[$i] = mt_rand(4, 8);
                }
                $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
                $strLastReel = implode(',', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $strOtherResponse = '';
                
                if($isNewTumb == true){
                    $isState = false;
                    $rs_c = 1;
                    $rs_m = 1;
                    if($rs_more > 0){
                        $rs_c = $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState');
                        $rs_m = $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState');
                    }
                    $strOtherResponse = $strOtherResponse . '&rs=mc&rs_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') . '&rs_p=' . ($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') - 1) . '&rs_c='. $rs_c .'&rs_m=' . $rs_m;
                }
                else if($isTumb == true){
                    if($slotEvent['slotEvent'] != 'freespin'){
                        $spinType = 'c';
                        $isState = true;
                    }
                    $strOtherResponse = $strOtherResponse.'&rs_win='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbWin').'&rs_t='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbleState').'&tmb_win='.($slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') - $totalWin);
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
                        if($scatterCount >=3){
                            $isState = false;
                            $spinType = 's';
                            $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fswin=0.00&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fsres=0.00';
                        }
                    }
                }
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $strOtherResponse = $strOtherResponse . '&puri=0';
                }
                if($pur > -1){
                    $strOtherResponse = $strOtherResponse . '&purtr=1';
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($str_tmb != ''){
                    $strOtherResponse = $strOtherResponse . '&tmb=' . $str_tmb;
                }
                if($str_accv != ''){
                    $strOtherResponse = $strOtherResponse . '&accm=cp&acci=0&accv=' . $str_accv;
                }
                if($str_rwd != ''){
                    $strOtherResponse = $strOtherResponse . '&rwd=' . $str_rwd;
                }
                if($str_wlm_v != ''){
                    $strOtherResponse = $strOtherResponse . '&wlm_v='. $str_wlm_v .'&wlm_p=' . $str_wlm_p;
                }
                if($str_srf != ''){
                    $strOtherResponse = $strOtherResponse . '&srf=' . $str_srf;
                }
                if(count($wlc_vs) > 0){
                    $strOtherResponse = $strOtherResponse . '&wlc_v=' . implode(';', $wlc_vs);
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&reel_set='. $currentReelSet .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=4&st=rect&c='.$betline .'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&w='.$totalWin.'&s=' . $strLastReel;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)  && $isNewTumb == false) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if($isNewTumb == false){
                    if( $slotEvent['slotEvent'] != 'freespin' && $scatterCount >= 3) 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"TumbWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin')  . ',"TumbleState":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState')  . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($slotEvent['slotEvent'] == 'freespin' && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * 75;
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
        
        public function findZokbos($reels, $firstSymbol, $repeatCount, $positions, $reelndex){
            $wild = '2';
            $bPathEnded = true;
            if($reelndex < 6){
                for($r = 0; $r < 7; $r++){
                    if($firstSymbol == $reels[$reelndex][$r] || $reels[$reelndex][$r] == $wild){
                        $this->findZokbos($reels, $firstSymbol, $repeatCount + 1, array_merge($positions, [($reelndex + $r * 6)]), $reelndex + 1);
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

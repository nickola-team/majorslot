<?php 
namespace VanguardLTE\Games\CurseoftheWerewolfMegawaysPM
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
            $oldBetLine = 0.2;

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
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 10);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [10,6,11,6,9,3,5,6,7,11,9,3,4,9,7,11,4,11,4,9,11,5,15,11,15,12,11,5,15,7,15,15,10,15,15,5]);
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
                    $bet = '100.00';
                }
                $spinType = 's';
                $fsmore = 0;
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if(isset($stack)){
                    $str_trail = $stack['trail'];
                    $str_accm = $stack['accm'];
                    $str_accv = $stack['accv'];
                    $str_wlc_v = $stack['wlc_v'];
                    $str_aam = $stack['aam'];
                    $str_aav = $stack['aav'];
                    $fsmore = $stack['fsmore'];
                    $currentReelSet = $stack['reel_set'];
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=0';
                    }
                    if($str_trail != ''){
                        $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                    }
                    if($str_accv != ''){
                        $strOtherResponse = $strOtherResponse . '&accm='. $str_accm .'&acci=0&accv=' . $str_accv;
                    }
                    if($str_aav != ''){
                        $strOtherResponse = $strOtherResponse . '&aam='. $str_aam .'&aav=' . $str_aav;
                    }
                    $wlc_vs = [];
                    if($str_wlc_v != ''){
                        $old_wlc_vs = explode(';', $str_wlc_v);
                        foreach($old_wlc_vs as $index=>$wlc){
                            $arr_wlc = explode('~', $wlc);
                            if(isset($arr_wlc[1])){
                                $arr_wlc[1] = $arr_wlc[1] / $oldBetLine * $bet;
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
                    
                $Balance = $slotSettings->GetBalance();  
                $response = '10,6,11,6,9,3,5,6,7,11,9,3,4,9,7,11,4,11,4,9,11,5,15,11,15,12,11,5,15,7,15,15,10,15,15,5&balance='. $Balance .'&nas=15&cfgs=3324&ver=2&index=1&balance_cash='. $Balance .'&def_sb=5,4,10,9,8,10&reel_set_size=9&def_sa=10,10,11,6,2,9&reel_set='. $currentReelSet .'&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0,0~15,12,10,8,0,0~1,1,1,1,1,1&gmb=0,0,0&rt=d&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime='. floor(microtime(true) * 1000) .'&sa=10,10,11,6,2,9&sb=5,4,10,9,8,10&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=200.00&purInit_e=1&sh=6&wilds=2~0,0,0,0,0,0~1,1,1,1,1,1&bonuses=0&fsbonus=&c='. $bet .'&sver=5&counter=2&paytable=0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;100,50,25,10,5,0;50,25,10,5,0,0;25,10,5,3,0,0;20,8,4,3,0,0;15,6,3,2,0,0;10,5,3,2,0,0;10,5,3,2,0,0;5,3,2,1,0,0;5,3,2,1,0,0;5,3,2,1,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0&l=10&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=3,10,10,1,8,8,8,9,9,11,7,13,12,12,6,6,5,5,9,9,9,3,3,11,11,4,4,7,7,12,12,12,13,13,6,8,4,5,10,10,10,8,8,12,9,10,13,13,13,11,11,11~3,10,10,1,8,8,8,9,9,11,7,13,12,12,6,6,2,5,5,9,9,9,3,3,11,11,4,4,7,7,12,12,12,13,13,6,8,4,5,10,10,10,8,8,12,9,10,13,13,13,11,11~3,10,10,1,8,8,9,9,11,7,13,12,12,6,6,2,5,5,9,9,9,3,3,11,11,4,4,7,7,12,12,13,13,6,8,4,5,10,10,10,8,8,12,9,10,13,13,13,11,11,11~3,10,10,1,8,8,8,9,9,11,7,13,12,12,6,6,2,5,5,9,9,3,3,11,11,4,4,7,7,12,12,12,13,13,6,8,4,5,10,10,8,8,12,9,10,13,13,13,11,11~3,10,10,1,8,8,9,9,11,7,13,12,12,6,6,2,5,5,9,9,9,3,3,11,11,4,4,7,7,12,12,13,13,6,8,4,5,10,10,10,8,8,12,9,10,13,13,13,11,11~3,10,10,1,8,8,9,9,11,7,13,12,12,6,6,2,5,5,9,9,3,3,11,11,4,4,7,7,12,12,12,13,13,6,8,4,5,10,10,10,8,8,12,9,10,13,13,13,11,11&s='.$lastReelStr.'&accInit=[{id:0,mask:"cp;tp;lvl;sc;cl"}]&reel_set2=3,10,10,8,8,8,9,9,11,7,13,12,12,6,6,5,5,9,9,9,3,3,11,11,4,4,7,7,12,12,12,13,13,6,8,4,5,10,10,10,8,8,12,9,10,13,13,13,11,11,11~3,10,10,8,8,8,9,9,11,7,13,12,12,6,6,5,5,9,9,9,3,3,11,11,4,4,7,7,12,12,12,13,13,6,8,4,5,10,10,10,8,8,12,9,10,13,13,13,11,11,11~3,10,10,8,8,8,9,9,11,7,13,12,12,6,6,5,5,9,9,9,3,3,11,11,4,4,7,7,12,12,12,13,13,6,8,4,5,10,10,10,8,8,12,9,10,13,13,13,11,11,11~3,10,10,8,8,8,9,9,11,7,13,12,12,6,6,5,5,9,9,9,3,3,11,11,4,4,7,7,12,12,12,13,13,6,8,4,5,10,10,10,8,8,12,9,10,13,13,13,11,11,11~3,10,10,8,8,8,9,9,11,7,13,12,12,6,6,5,5,9,9,9,3,3,11,11,4,4,7,7,12,12,12,13,13,6,8,4,5,10,10,10,8,8,12,9,10,13,13,13,11,11,11~3,10,10,8,8,8,9,9,11,7,13,12,12,6,6,5,5,9,9,9,3,3,11,11,4,4,7,7,12,12,12,13,13,6,8,4,5,10,10,10,8,8,12,9,10,13,13,13,11,11,11&t=243&reel_set1=3,10,10,1,8,8,8,9,9,7,7,7,13,12,12,6,6,5,5,9,9,9,3,3,11,11,4,4,4,4,7,7,12,12,12,12,13,13,6,6,6,6,5,5,5,5,10,10,10,8,8,13,13,13,11,11,11,11~3,10,10,1,8,8,8,9,9,7,7,13,12,12,6,6,2,5,5,9,9,9,3,3,11,11,4,4,4,4,7,7,12,12,12,12,13,13,6,6,5,5,5,5,10,10,10,8,8,13,13,13,11,11~3,10,10,1,8,8,8,9,9,7,7,7,7,13,12,12,6,6,2,5,5,9,9,9,3,3,11,11,4,4,4,4,7,7,12,12,13,13,6,6,6,5,5,5,5,10,10,10,8,8,13,13,13,11,11,11~3,10,10,1,8,8,8,9,7,7,7,13,12,12,6,6,2,5,5,9,9,3,3,11,11,4,4,4,4,7,7,12,12,12,12,13,13,6,6,5,5,5,5,10,10,10,8,8,13,13,13,11,11,11~3,10,10,1,8,8,9,9,7,7,7,7,13,12,12,6,6,2,5,5,9,9,9,3,3,11,11,4,4,4,4,7,7,12,12,12,12,13,13,6,6,5,5,5,5,10,10,10,8,8,13,13,13,11,11~3,10,10,1,8,8,8,9,9,7,7,7,7,13,12,12,6,6,2,5,5,9,9,9,3,3,11,11,4,4,4,4,7,7,12,12,12,12,13,13,6,6,6,5,5,5,5,10,10,10,8,8,13,13,13,11,11&reel_set4=3,10,10,1,8,8,8,9,9,11,12,12,3,3,6,6,5,5,9,9,9,3,3,11,11,4,4,3,3,12,12,6,3,3,8,4,5,10,10,3,3,8,8,12,9,10,11,11~3,10,10,1,8,8,8,9,9,11,12,12,3,3,6,6,2,5,5,9,9,9,3,3,11,11,4,4,12,12,12,6,3,3,8,4,5,10,10,10,3,3,8,8,12,9,10,11,11,11~3,10,10,8,8,9,9,11,12,12,3,3,6,6,2,5,5,9,9,3,3,11,11,4,4,12,12,6,8,4,5,3,3,10,10,10,3,3,8,8,12,3,3,9,10,11,11~3,3,10,10,1,8,8,8,9,9,11,12,12,3,3,6,6,2,5,5,9,9,3,3,11,11,4,4,4,3,3,12,12,12,6,8,4,4,5,10,10,3,3,8,8,12,9,10,11,11,11~3,3,10,10,8,8,8,9,9,11,12,12,3,3,6,6,2,5,5,9,9,9,3,3,3,11,11,4,4,3,3,12,12,6,8,4,5,10,10,10,3,3,8,8,12,9,10,11,11~3,10,10,1,8,8,8,9,9,11,12,12,3,3,6,6,2,5,5,9,9,3,11,11,4,4,4,3,12,12,6,8,4,4,5,10,10,10,3,3,8,8,12,9,10,11,11&purInit=[{type:"fs",bet:1000}]&reel_set3=3,10,10,1,8,8,8,3,3,9,9,11,7,12,12,3,3,6,6,5,5,9,9,9,3,3,11,11,4,4,7,7,3,3,3,3,12,12,12,3,3,6,8,4,5,10,10,3,3,8,8,12,9,10,11,11~3,10,10,8,8,8,3,3,9,9,11,7,12,12,3,3,6,6,2,5,5,9,9,9,3,3,11,11,4,4,7,7,12,12,12,6,8,4,5,3,3,10,10,10,3,3,8,8,12,9,10,11,11,11~3,10,10,1,8,8,3,3,9,9,11,7,12,12,3,3,6,6,2,5,5,9,9,9,3,3,3,11,11,4,4,7,7,12,12,6,8,4,5,3,3,10,10,10,3,3,8,8,12,9,10,11,11~3,3,10,10,8,8,8,3,3,9,9,11,7,12,12,3,3,6,6,2,5,5,9,9,3,3,11,11,4,4,4,7,7,12,12,12,6,8,4,4,3,3,5,10,10,3,3,8,8,12,9,10,11,11,11~3,3,10,10,1,8,8,3,3,9,9,11,7,12,12,3,3,6,6,2,5,5,9,9,9,3,3,3,3,11,11,4,4,7,7,12,12,6,8,4,3,3,5,10,10,10,3,3,8,8,12,9,10,11,11~3,10,10,1,8,8,3,3,9,9,11,7,12,12,3,3,6,6,2,5,5,9,9,3,3,11,11,4,4,4,7,7,3,3,3,3,12,12,6,8,3,3,4,4,5,10,10,10,3,3,8,8,12,9,10,11,11&reel_set6=3,10,10,1,8,8,8,3,3,9,9,11,12,9,9,9,3,3,11,11,4,4,12,8,3,3,4,10,10,3,3,8,8,12,9,10,3,3,11,11,11~3,10,10,1,8,8,8,3,3,9,9,11,12,12,2,9,9,3,3,11,11,4,4,12,12,8,4,10,10,10,3,3,8,8,12,9,10,3,3,11,11,11~3,10,10,1,8,8,8,3,3,9,9,11,12,12,2,9,9,9,3,3,11,11,4,4,12,12,8,4,10,10,10,3,3,8,8,12,9,10,3,3,11,11~3,3,10,10,1,8,8,8,3,3,9,9,11,12,12,2,9,9,3,3,11,11,4,4,4,12,12,8,4,4,10,10,3,3,8,8,12,9,10,3,3,11,11,11~3,3,10,10,1,8,8,8,3,3,9,9,12,12,12,2,9,9,9,3,3,4,4,12,12,8,4,10,10,10,3,3,8,8,12,9,3,3,10~3,10,10,1,8,8,3,3,9,9,9,4,11,12,12,12,2,9,9,3,3,11,11,11,4,4,3,3,12,12,8,4,4,10,10,10,3,3,8,8,12,9,10,3,3,11,11&reel_set5=3,10,10,8,8,8,3,3,9,9,11,12,12,5,5,9,9,9,3,3,11,11,4,4,8,3,3,12,5,12,3,3,8,4,5,10,10,3,3,8,8,12,9,10,11,11~3,10,10,8,8,8,3,3,9,9,11,12,12,2,5,5,9,9,3,3,11,11,4,4,12,12,12,8,4,3,3,5,10,10,10,3,3,8,8,12,9,10,11,11~3,10,10,1,8,8,3,3,9,9,11,12,12,2,5,5,9,9,9,3,3,11,11,4,4,12,12,8,4,5,3,3,10,10,10,3,3,8,8,12,9,10,11,11~3,3,10,10,1,8,8,8,3,3,9,9,11,12,12,2,5,5,9,9,9,3,3,11,11,4,4,4,12,12,12,3,3,8,4,4,5,10,10,3,3,8,8,12,9,10,11,11,11~3,3,10,10,1,8,8,8,3,3,9,9,11,12,12,2,5,5,9,9,9,3,3,11,11,4,4,12,12,8,8,3,3,4,5,10,10,10,3,3,8,8,12,9,10,11,11~3,10,10,1,8,8,3,3,9,9,9,11,12,12,2,5,5,9,9,3,3,11,11,11,4,4,4,3,3,12,12,8,4,4,3,3,5,10,10,10,3,3,8,8,12,9,10,11,11&reel_set8=3,10,10,1,8,8,8,9,9,11,7,12,12,6,6,5,5,9,9,9,3,3,11,11,4,4,7,7,12,12,12,6,8,4,5,10,10,10,8,8,12,9,10,11,11,11~3,10,10,1,8,8,8,9,9,11,7,12,12,6,6,2,5,5,9,9,9,3,3,11,11,4,4,7,7,12,12,12,6,8,4,5,10,10,10,8,8,12,9,10,11,11,11~3,10,10,1,8,8,8,9,9,11,7,12,12,6,6,2,5,5,9,9,9,3,3,11,11,4,4,7,7,12,12,12,6,8,4,5,10,10,10,8,8,12,9,10,11,11,11~3,10,10,1,8,8,8,9,9,11,7,12,12,6,6,2,5,5,9,9,9,3,3,11,11,4,4,7,7,12,12,12,6,8,4,5,10,10,10,8,8,12,9,10,11,11,11~3,10,10,1,8,8,8,9,9,11,7,12,12,6,6,2,5,5,9,9,9,3,3,11,11,4,4,7,7,12,12,12,6,8,4,5,10,10,10,8,8,12,9,10,11,11,11~3,10,10,1,8,8,8,9,9,11,7,12,12,6,6,2,5,5,9,9,9,3,3,11,11,4,4,7,7,12,12,12,6,8,4,5,10,10,10,8,8,12,9,10,11,11,11&reel_set7=3,10,10,1,8,8,8,3,3,9,9,11,12,9,9,9,3,3,11,11,3,3,3,12,3,12,8,10,10,10,3,3,8,8,12,9,10,3,3,11,11~3,10,10,1,8,8,8,3,3,9,9,11,3,3,12,12,2,9,9,3,3,11,11,12,12,12,8,10,10,10,3,3,8,8,12,9,10,3,3,11,11,11~3,10,10,1,8,8,8,3,3,9,9,11,3,3,12,12,2,9,9,3,3,11,11,12,12,12,8,10,10,10,3,3,8,8,12,9,10,3,3,11,11~3,3,10,10,1,8,8,8,3,3,9,9,11,3,3,12,12,2,9,9,3,3,3,11,11,12,12,8,10,10,3,3,8,8,12,9,10,3,3,11,11,11~3,3,10,10,1,8,8,3,3,9,9,11,3,3,12,12,2,9,9,9,3,3,3,3,11,11,12,12,8,10,10,10,3,3,8,8,12,9,10,3,3,11,11~3,10,10,1,8,8,3,3,9,9,11,3,3,12,12,2,9,9,3,3,11,11,3,3,3,3,12,12,8,10,10,10,3,3,8,8,12,9,10,3,3,11,11&total_bet_min=10.00';
            }
            else if( $slotEvent['slotEvent'] == 'doCollect' || $slotEvent['slotEvent'] == 'doCollectBonus') 
            {
                $Balance = $slotSettings->GetBalance();
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);    
                $response = 'balance=' . $Balance . '&index=' . $slotEvent['index'] . '&balance_cash=' . $Balance . '&balance_bonus=0.00&na=s&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&na=s&sver=5&counter=' . ((int)$slotEvent['counter'] + 1);
                
                //------------ ReplayLog ---------------                
                $lastEvent = $slotSettings->GetHistory();
                if($lastEvent != 'NULL'){
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
            else if( $slotEvent['slotEvent'] == 'doSpin' ) 
            {
                $lastEvent = $slotSettings->GetHistory();
                $pur = -1;
                if(isset($slotEvent['pur'])){
                    $pur = $slotEvent['pur'];
                }
                $slotEvent['slotBet'] = $slotEvent['c'];
                $slotEvent['slotLines'] = 10;
                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
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
                    $allBet = $betline * $lines * 100;
                }
                $tumbAndFreeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
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
                    $roundstr = sprintf('%.1f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '628' . substr($roundstr, 3, 8). '023';
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $leftFreeGames = 0;

                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []);
                }
                
                $wild = '2';
                $scatter = '1';
                $Balance = $slotSettings->GetBalance();
                $totalWin = 0;
                $bonusMpl = 1;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $str_trail = '';
                $str_wlc_v = '';
                $str_accm = '';
                $str_accv = '';
                $str_aam = '';
                $str_aav = '';
                $currentReelSet = 0;
                $fsmore = 0;
                $subScatterReel = null;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_trail = $stack['trail'];
                    $str_accm = $stack['accm'];
                    $str_accv = $stack['accv'];
                    $str_wlc_v = $stack['wlc_v'];
                    $str_aam = $stack['aam'];
                    $str_aav = $stack['aav'];
                    $fsmore = $stack['fsmore'];
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
                    $str_trail = $stack[0]['trail'];
                    $str_accm = $stack[0]['accm'];
                    $str_accv = $stack[0]['accv'];
                    $str_wlc_v = $stack[0]['wlc_v'];
                    $str_aam = $stack[0]['aam'];
                    $str_aav = $stack[0]['aav'];
                    $fsmore = $stack[0]['fsmore'];
                    $currentReelSet = $stack[0]['reel_set'];
                }

                $reels = [];
                $wildReel = [];
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;
                for($i = 0; $i < 36; $i++){
                    if($lastReel[$i] == $scatter){
                        $scatterCount++;
                        $scatterPoses[] = $i;
                    }
                }

                $_lineWinNumber = 1;
                $_obf_winCount = 0;
                $wlc_vs = [];
                if($str_wlc_v != ''){
                    $old_wlc_vs = explode(';', $str_wlc_v);
                    foreach($old_wlc_vs as $index=>$wlc){
                        $arr_wlc = explode('~', $wlc);
                        if(isset($arr_wlc[1])){
                            $arr_wlc[1] = $arr_wlc[1] / $oldBetLine * $betline;
                            $totalWin = $totalWin + $arr_wlc[1];
                        }
                        $wlc_vs[] = implode('~', $arr_wlc);
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
                if($scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin'){
                    $freeSpins = [0,0,0,8,10,12,15];
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freeSpins[$scatterCount]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                }
                if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
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
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $spinType = 's';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $isEnd = false;
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                    {
                        $strOtherResponse = $strOtherResponse . '&fs_total='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin_total=' . ($slotSettings->GetGameData($slotSettings->slotId . 'BonusWin')) . '&fsmul_total=1&fsend_total=1&fsres_total=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin');
                        if($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') > 0){
                            $spinType = 'c';
                        }
                        $isEnd = true;
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

                    if($scatterCount >=3){
                        $isState = false;
                        $spinType = 's';
                        $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') .'&fswin=0.00&fs='. $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame').'&fsres=0.00';
                    }
                }
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $strOtherResponse = $strOtherResponse . '&puri=0';
                }
                if($pur > -1){
                    $strOtherResponse = $strOtherResponse . '&purtr=1';
                }
                if($str_trail != ''){
                    $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                }
                if($str_accv != ''){
                    $strOtherResponse = $strOtherResponse . '&accm='. $str_accm .'&acci=0&accv=' . $str_accv;
                }
                if($str_aav != ''){
                    $strOtherResponse = $strOtherResponse . '&aam='. $str_aam .'&aav=' . $str_aav;
                }
                if(count($wlc_vs) > 0){
                    $strOtherResponse = $strOtherResponse . '&wlc_v=' . implode(';', $wlc_vs);
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&reel_set='. $currentReelSet .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=6&st=rect&c='.$betline .'&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=10&w='.$totalWin.'&s=' . $strLastReel;
                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if( $slotEvent['slotEvent'] != 'freespin' && $scatterCount >= 3) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"TumbWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin')  . ',"TumbleState":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState')  . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($slotEvent['slotEvent'] == 'freespin' && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * 100;
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

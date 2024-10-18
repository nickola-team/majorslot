<?php 
namespace VanguardLTE\Games\YumYumPowerwaysPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Level', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'Status', [0,0,0,0,0]);
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [14,11,8,8,12,14,8,3,5,10,9,10,11,5,4,6,5,10,11,9,4,5,7,11,6,7,10,8,6,11,14,13,13,11,11,14]);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', $lastEvent->serverResponse->Bgt);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Level', $lastEvent->serverResponse->Level);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Status', $lastEvent->serverResponse->Status);
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
                $wmv = 0;
                $fsmore = 0;
                $g = null;
                $str_stf = '';
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if(isset($stack)){
                    $str_stf = $stack['stf'];
                    $fsmore = $stack['fsmore'];
                    $wmv = $stack['wmv'];
                    $g = $stack['g'];
                    $currentReelSet = $stack['reel_set'];
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    if($str_stf != ''){
                        $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                    }
                    if($g != null){
                        $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($g));
                    }
                }else{
                    $strOtherResponse = $strOtherResponse . '&g={s0:{def_s:"12,8,8,11",def_sa:"14",def_sb:"14",reel_set:"0",s:"12,8,8,11",sa:"14",sb:"14",sh:"4",st:"rect",sw:"1"},s1:{def_s:"8,11,11,6",def_sa:"14",def_sb:"14",reel_set:"1",s:"8,11,11,6",sa:"14",sb:"14",sh:"4",st:"rect",sw:"1"},s2:{def_s:"13,13,11,11",def_sa:"14",def_sb:"14",reel_set:"2",s:"13,13,11,11",sa:"14",sb:"14",sh:"4",st:"rect",sw:"1"},s3:{def_s:"11,11,10,10",def_sa:"14",def_sb:"14",reel_set:"3",s:"11,11,10,10",sa:"14",sb:"14",sh:"4",st:"rect",sw:"1"},s4:{def_s:"3,5,10,9,5,4,6,5,9,4,5,7,7,10,8,6",def_sa:"14,14,14,14",def_sb:"14,14,14,14",s:"3,5,10,9,5,4,6,5,9,4,5,7,7,10,8,6",sa:"14,14,14,14",sb:"14,14,14,14",sh:"4",st:"rect",sw:"4"}}';
                }
                if($slotSettings->GetGameData($slotSettings->slotId . 'Bgt') == 35){
                    $spinType = 'b';
                    $strOtherResponse = $strOtherResponse . '&bgid=0&wins=4,6,8,10,12&level='. $slotSettings->GetGameData($slotSettings->slotId . 'Level') .'&coef='. ($bet * 20) .'&index='.$slotEvent['index'].'&status='. implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'Status')) .'&rw=0&bgt=35&wins_mask=nff,nff,nff,nff,nff&wp=0&lifes=1&end=0';
                }
                else if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') == 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                    $strOtherResponse = $strOtherResponse . '&bgid=0&wins=4,6,8,10,12&level=1&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1&lifes=0&end=1&wp=0&rw=0.00&status='. implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'Status'));
                }
                else if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 )
                {
                    $fs = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                    $strOtherResponse = $strOtherResponse . '&fs=' . $fs . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&fsmul=1';
                    if($fsmore > 0){
                        $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                    }
                }
                    
                if($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') > 0){
                    $strOtherResponse = $strOtherResponse.'&rs=mc&rs_win='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbWin').'&rs_p='.($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') - 1).'&rs_c=1&rs_m=1';      
                }
                $Balance = $slotSettings->GetBalance();    
                $response = 'def_s=14,11,8,8,12,14,8,3,5,10,9,10,11,5,4,6,5,10,11,9,4,5,7,11,6,7,10,8,6,11,14,13,13,11,11,14&balance='. $Balance .'&cfgs=4748&ver=2&index=1&balance_cash='. $Balance .'&reel_set_size=17&reel_set=12&balance_bonus=0.00&na='. $spinType .'&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&gameInfo={props:{gamble_reg_8_10:"44.46%",max_rnd_sim:"0",gamble_pur_6_8:"42.93%",gamble_pur_10_12:"45.50%",max_rnd_hr:"196072742",gamble_reg_4_6:"50.14%",max_rnd_win:"5000",gamble_pur_4_6:"50.35%",gamble_reg_6_8:"42.88%",gamble_pur_8_10:"44.49%",gamble_reg_10_12:"45.47%"}}&wl_i=tbm~5000&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime='. floor(microtime(true) * 1000) .'&reel_set10=12,13,3,3,3,6,6,8,13,8,8,8,11,10,3,7,9,9,9,9,10,6,11,7,7,7,8,13,12,10,10,10,10,11,11,7,11,11,11,8,12,11,12,12,12,12,8,5,10,7,6,6,6,8,13,5,13,13,13,9,6,4,9,5,5,5,7,13,4,5,4,4,4,4,9,9,1,12&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=100.00&reel_set11=10,1,1,5,8,13,13,12,8,3,8,11,1,7,1,8,8,8,1,13,1,1,5,13,11,1,10,11,11,12,1,5,11,13,1,5,5,5,4,9,6,13,9,6,1,7,11,6,4,5,12,1,1,7,9,4,4,4,13,12,10,1,10,6,9,3,7,8,1,10,7,1,8,12,12,11&reel_set12=11,3,3,3,6,7,6,8,8,8,4,13,10,9,9,9,8,3,12,7,7,7,7,6,10,10,10,4,10,11,11,11,11,11,9,10,12,12,12,13,5,5,6,6,6,1,12,13,13,13,12,11,8,5,5,5,13,7,12,4,4,4,9,9,8,13~10,3,3,3,4,6,8,8,8,9,8,9,9,9,8,7,7,7,7,5,10,10,10,10,7,12,11,11,11,11,12,11,12,12,12,12,5,6,6,6,11,3,13,13,13,6,13,5,5,5,1,13,4,4,4,13,8,4,9~9,3,3,3,13,11,5,8,8,8,12,10,6,9,9,9,9,9,1,7,7,7,5,8,10,10,10,7,11,4,11,11,11,12,11,7,12,12,12,8,3,12,6,6,6,13,8,13,13,13,12,10,10,5,5,5,6,13,11,4,4,4,13,6,7,4~11,11,4,8,6,4,3,3,3,11,4,5,10,1,10,9,7,8,8,8,12,9,11,12,9,7,11,8,9,9,9,11,12,8,13,7,13,13,3,7,7,7,11,8,13,13,10,13,6,10,10,10,10,8,13,6,13,11,6,11,7,11,11,11,12,7,11,9,10,5,3,8,12,12,12,12,7,9,5,7,12,6,12,6,6,6,8,5,10,12,13,9,9,12,13,13,13,6,13,8,9,7,6,8,8,5,5,5,6,5,10,7,4,13,13,9,4,4,4,12,4,11,10,11,10,5,9,1&purInit_e=1&reel_set13=7,7,12,6,8,13,6,8,13,8,3,3,3,12,11,9,12,6,11,9,9,13,5,12,10,8,8,8,6,10,7,4,10,3,8,7,6,11,12,4,9,9,9,12,7,4,8,13,13,10,9,12,10,13,7,7,7,7,4,11,4,12,13,13,9,5,11,11,9,10,10,10,5,3,5,13,13,6,12,6,8,7,9,10,11,11,11,12,11,8,8,9,11,7,1,8,5,7,6,12,12,12,3,8,11,10,11,11,12,9,7,9,10,12,6,6,6,13,12,13,8,10,11,5,10,7,9,13,13,13,13,5,11,6,8,11,12,5,10,13,11,1,8,5,5,5,7,4,10,9,13,13,10,11,8,12,6,11,4,4,4,12,10,6,9,7,12,12,5,12,6,8,13,10&sh=6&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&st=rect&c='. $bet .'&sw=6&sver=5&counter=2&reel_set14=10,13,9,12,10,13,12,4,6,3,9,5,5,4,6,10,13,7,3,3,3,11,6,4,12,12,11,11,3,8,5,13,8,8,11,11,13,10,9,10,8,8,8,13,9,12,10,7,12,12,10,9,8,5,6,11,6,13,10,10,6,5,9,9,9,11,11,9,8,5,6,10,11,9,13,12,9,11,13,7,7,12,11,7,7,7,7,7,4,7,11,7,4,9,13,8,9,7,7,13,8,9,10,8,13,11,13,10,10,10,9,12,12,10,13,8,12,9,12,11,7,11,5,9,6,7,12,13,8,11,11,11,10,7,8,12,5,12,11,6,11,3,1,6,13,9,7,10,9,13,8,12,12,12,6,5,9,8,9,8,10,10,9,8,12,12,6,10,6,7,6,7,9,5,6,6,6,7,13,11,12,10,4,7,13,13,6,4,7,12,5,13,11,13,12,6,13,13,13,12,3,11,8,8,6,5,11,12,5,12,9,13,1,10,4,5,12,11,5,5,5,4,6,12,10,13,8,10,11,10,10,9,11,7,9,7,11,1,6,12,4,4,4,3,7,8,11,8,13,7,13,4,8,13,12,12,9,10,11,11,7,12,12,13&paytable=0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;30,15,10,0,0,0;18,12,8,0,0,0;12,9,6,0,0,0;10,7,4,0,0,0;8,5,3,0,0,0;5,3,2,0,0,0;5,3,2,0,0,0;5,3,2,0,0,0;4,2,1,0,0,0;4,2,1,0,0,0;4,2,1,0,0,0;0,0,0,0,0,0&l=20&reel_set15=13,5,12,4,8,3,3,3,6,5,7,9,9,7,10,8,8,8,10,6,6,8,1,11,10,9,9,9,9,5,10,8,10,12,12,7,7,7,8,8,10,6,7,7,4,10,10,10,4,9,13,8,11,11,13,11,11,11,11,9,13,5,8,12,13,12,12,12,9,12,11,9,6,4,6,6,6,6,13,10,13,11,13,11,7,13,13,13,3,3,13,12,12,8,12,5,5,5,12,11,7,5,9,7,9,4,4,4,6,11,10,8,7,13,12,11&reel_set16=13,10,11,13,8,9,1,6,7,11,8,13,3,12,5,7,12,13,9,10,9,3,3,3,9,12,5,12,7,9,13,12,5,13,6,11,11,12,13,8,12,13,10,6,7,11,8,8,8,7,10,8,12,5,4,11,13,8,12,7,10,10,11,13,10,13,3,9,12,8,10,9,9,9,13,7,6,9,3,6,12,10,11,13,8,8,10,7,9,4,9,6,1,9,4,6,7,7,7,8,6,13,8,12,10,5,5,10,5,12,4,8,11,12,11,6,5,13,11,11,13,10,10,10,4,9,10,11,11,13,4,5,6,13,6,13,9,7,9,9,1,6,8,11,12,9,11,11,11,12,13,5,7,11,5,11,5,8,11,11,12,10,9,9,13,13,12,13,3,13,7,12,12,12,10,12,6,4,5,8,9,6,4,7,12,11,12,12,11,13,12,12,8,7,12,13,6,6,6,7,8,10,6,10,6,6,10,11,10,8,13,5,12,11,8,5,6,4,13,6,9,13,13,13,8,10,9,12,7,10,11,5,4,7,8,9,13,11,4,12,9,7,10,11,8,11,5,5,5,9,3,6,7,4,7,11,7,11,6,11,6,7,11,13,10,11,13,12,12,4,8,4,4,4,4,13,8,12,7,13,8,10,7,9,5,10,9,9,3,11,10,10,8,9,12,12,7,7&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=5,3,3,3,7,10,9,8,8,8,8,11,12,9,9,9,13,13,6,7,7,7,10,7,10,10,10,5,13,13,11,11,11,4,11,12,12,12,12,3,4,12,6,6,6,6,8,13,13,13,6,8,11,5,5,5,12,1,9,4,4,4,9,10,11,7&s='.$lastReelStr.'&reel_set2=11,3,3,3,7,7,8,8,8,11,8,11,9,9,9,10,13,7,7,7,4,13,9,10,10,10,1,13,11,11,11,12,6,12,12,12,7,5,8,6,6,6,10,10,13,13,13,4,12,3,5,5,5,6,8,4,4,4,5,12,9,9&t=243&reel_set1=11,12,3,3,3,6,3,5,11,8,8,8,10,13,8,12,9,9,9,7,1,4,7,7,7,4,5,7,7,10,10,10,12,9,5,6,11,11,11,11,7,13,12,12,12,10,6,10,8,6,6,6,11,12,6,10,13,13,13,12,13,13,5,5,5,9,8,9,11,4,4,4,12,9,8,13,10&reel_set4=8,11,6,12,13,11,3,3,3,6,4,11,10,4,7,5,8,8,8,11,10,5,13,13,11,12,8,9,9,9,9,10,13,11,1,7,3,7,7,7,8,5,9,11,10,5,12,8,10,10,10,12,3,4,10,13,9,8,11,11,11,4,13,9,7,6,7,9,12,12,12,8,10,12,10,12,7,8,11,6,6,6,4,6,13,5,12,12,9,13,13,13,9,10,6,12,11,8,8,6,5,5,5,13,10,7,13,12,5,11,4,4,4,6,13,6,9,7,12,7,13,11&purInit=[{type:"d",bet:2000}]&reel_set3=6,7,13,10,4,11,5,6,7,13,3,3,3,11,12,5,12,7,7,5,5,10,13,12,8,8,8,7,13,9,7,3,12,13,9,10,6,7,9,9,9,5,11,9,10,9,12,10,13,13,11,8,10,7,7,7,4,10,8,12,13,9,5,6,11,4,7,10,10,10,5,8,11,11,13,13,12,10,13,4,13,11,11,11,12,1,7,1,13,10,9,8,12,8,6,12,12,12,11,7,5,8,9,8,12,11,4,11,12,6,6,6,6,10,13,11,8,10,11,9,13,9,12,5,13,13,13,6,7,6,4,6,9,13,3,9,13,11,5,5,5,10,9,8,6,10,11,1,7,4,3,8,4,4,4,6,12,12,9,11,10,8,12,11,12,11,12,7&reel_set6=4,9,10,13,11,10,12,3,3,3,7,8,11,5,9,11,12,13,8,8,8,11,13,9,6,6,8,7,12,9,9,9,12,11,10,4,11,5,12,10,7,7,7,4,13,13,6,8,13,12,5,10,10,10,9,11,13,10,5,11,9,8,11,11,11,13,6,12,9,11,6,3,13,12,12,12,9,5,4,9,11,10,4,8,6,6,6,12,8,9,7,3,13,8,7,13,13,13,12,8,13,6,5,10,12,8,5,5,5,11,1,7,6,10,13,7,9,4,4,4,7,8,12,10,6,7,12,7,11&reel_set5=12,5,5,12,13,10,8,10,8,13,6,9,13,12,7,11,4,10,6,3,3,3,3,13,9,12,12,13,9,13,8,12,8,9,10,4,11,6,10,7,8,10,8,8,8,11,12,4,7,12,9,6,7,13,8,11,5,6,11,7,12,6,6,12,9,9,9,9,6,13,8,7,11,13,11,9,8,3,9,12,7,3,12,7,8,5,11,5,12,7,7,7,11,4,5,10,13,7,11,13,8,8,13,5,12,9,4,12,10,1,8,10,10,10,10,10,8,12,10,11,1,7,5,12,11,11,12,9,6,13,7,13,12,7,8,11,11,11,13,11,12,7,13,6,10,8,6,11,6,4,6,9,8,13,13,9,5,13,12,12,12,4,12,4,10,5,10,10,9,11,9,13,13,12,6,9,10,8,4,6,12,11,6,6,6,8,7,7,12,9,5,6,11,13,8,10,7,13,9,11,9,6,8,9,7,13,13,13,12,6,9,5,7,10,3,10,9,4,12,7,10,10,4,13,13,11,11,9,5,5,5,4,12,10,11,11,10,11,13,12,6,5,11,5,3,8,1,7,12,13,11,4,4,4,7,13,13,6,10,9,7,13,4,11,9,11,7,10,12,9,7,7,11,8,5,11&reel_set8=1,13,12,11,11,6,7,10,1,11,1,8,6,10,8,1,12,1,8,13,12,11,1,10,13,3,1,3,8,8,8,1,10,13,9,1,5,10,5,7,7,13,11,1,11,6,9,4,8,1,4,7,1,5,8,1,1,3,1,13,5,5,5,9,9,11,11,6,10,7,1,12,1,10,1,1,4,6,1,13,12,8,9,1,5,11,1,1,6,12,1,8,4,4,4,5,7,7,11,12,1,12,12,13,9,5,4,1,1,8,10,1,12,1,13,1,12,13,9,13,8,7,7,10,13,11&reel_set7=13,7,12,3,7,11,9,12,10,11,13,3,3,3,9,11,3,13,13,5,4,7,10,7,6,5,12,8,8,8,13,7,13,7,7,5,12,10,11,11,8,8,4,9,9,9,5,7,4,5,12,11,11,7,9,1,6,10,5,7,7,7,12,8,10,5,11,4,12,9,9,6,12,7,9,10,10,10,8,9,13,9,10,13,13,12,10,5,12,11,11,11,11,11,9,6,13,11,5,6,7,9,8,11,8,5,12,12,12,6,4,12,13,12,13,11,10,6,11,13,9,8,6,6,6,7,11,8,10,10,8,4,10,12,13,9,11,6,13,13,13,12,12,10,12,8,13,11,8,1,4,6,6,12,5,5,5,3,8,12,11,7,10,13,8,4,9,10,3,13,4,4,4,6,8,6,9,7,10,9,6,9,10,13,11,13,12&reel_set9=1,13,10,7,3,10,1,11,1,12,9,12,8,13,1,13,1,1,7,7,9,11,12,5,11,10,1,10,8,12,4,13,8,8,8,6,7,13,11,10,1,12,5,1,7,13,1,10,11,11,3,5,8,9,11,1,6,1,8,12,12,1,6,1,13,3,9,1,11,5,5,5,1,1,13,1,8,1,1,4,13,9,13,4,13,5,4,12,1,4,9,1,11,9,1,13,12,7,11,11,6,1,1,10,12,1,4,4,4,1,5,1,8,5,7,8,6,8,11,8,1,7,1,12,10,6,1,1,9,1,13,9,10,6,11,7,1,7,12,5,10,8,12,13&total_bet_min=10.00';

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
                    $allBet = $betline * $lines * 100;
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Level', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Status', [0,0,0,0,0]);
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
                    $roundstr = '6074458' . substr($roundstr, 4, 10);
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
                $str_stf = '';
                $str_wlc_v = '';
                $currentReelSet = 0;
                $fsmore = 0;
                $wmv = 0;
                $g = null;
                $subScatterReel = null;
                if($slotEvent['slotEvent'] == 'freespin' || $isTumb == true){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_stf = $stack['stf'];
                    $str_wlc_v = $stack['wlc_v'];
                    $fsmore = $stack['fsmore'];
                    $wmv = $stack['wmv'];
                    $g = $stack['g'];
                    $currentReelSet = $stack['reel_set'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, 0, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $lastReel = explode(',', $stack[0]['reel']);
                    $str_stf = $stack[0]['stf'];
                    $str_wlc_v = $stack[0]['wlc_v'];
                    $fsmore = $stack[0]['fsmore'];
                    $wmv = $stack[0]['wmv'];
                    $g = $stack[0]['g'];
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
                $isNewTumb = false;
                $oldBetLine = 0.1;
                $wlc_vs = [];
                if($str_wlc_v != ''){
                    $old_wlc_vs = explode(';', $str_wlc_v);
                    foreach($old_wlc_vs as $index=>$wlc){
                        $arr_wlc = explode('~', $wlc);
                        if(isset($arr_wlc[1])){
                            $arr_wlc[1] = $arr_wlc[1] / $oldBetLine * $betline;
                            $totalWin = $totalWin + $arr_wlc[1];
                            $isNewTumb = true;
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
                if($isNewTumb == true){
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') + 1);
                    $isState = false;
                    $spinType = 's';
                }else{
                    if($scatterCount >= 6 && $slotEvent['slotEvent'] != 'freespin'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 12);
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
                    $strOtherResponse = $strOtherResponse . '&rs=mc&rs_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') . '&rs_p=' . ($slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') - 1) . '&rs_c=1&rs_m=1';
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
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
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
                        if($scatterCount >= 6){
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 12);
                            $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                            $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                            
                            $isState = false;
                            $stack = $slotSettings->GetReelStrips('bonus', 12, $betline * $lines);
                            if($stack == null){
                                $response = 'unlogged';
                                exit( $response );
                            }
                            $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                            $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1';
                        }else if($scatterCount >=3){
                            $isState = false;
                            $spinType = 'b';
                            $status = [0,0,0,0,0];
                            $status[$scatterCount - 2] = 1;
                            $slotSettings->SetGameData($slotSettings->slotId . 'Status', $status);
                            $strOtherResponse = $strOtherResponse . '&bgid=1&wins==4,6,8,10,12&coef='.($betline * $lines).'&level=0&status='. implode(',', $status) .'&bgt=35&lifes=1&bw=1&wins_mask=nff,nff,nff,nff,nff&wp=0&end=0';
                            $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 35);
                        }
                        if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                            $strOtherResponse = $strOtherResponse . '&purtr=1&puri=' . $pur;
                        }
                    }
                }
                if($str_stf != ''){
                    $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                }
                if($wmv > 0){
                    $strOtherResponse = $strOtherResponse . '&wmt=pr&wmv=' . $wmv;
                    if($wmv > 1){
                        $strOtherResponse = $strOtherResponse . '&gwm=' . $wmv;
                    }
                }
                if($g != null){
                    $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($g));
                }
                if(count($wlc_vs) > 0){
                    $strOtherResponse = $strOtherResponse . '&wlc_v=' . implode(';', $wlc_vs);
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&reel_set='. $currentReelSet .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=6&st=rect&c='.$betline .'&sw=6&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&w='.$totalWin.'&s=' . $strLastReel;
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"Level":' . $slotSettings->GetGameData($slotSettings->slotId . 'Level') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"TumbWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"Status":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'Status')) . ',"TumbleState":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState')  . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($slotEvent['slotEvent'] == 'freespin' && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * 100;
                }
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
            }else if( $slotEvent['slotEvent'] == 'doBonus' ){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lines = 20;
                $lastReel = $lastEvent->serverResponse->LastReel; 
                $Balance =  $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                $bgt = $slotSettings->GetGameData($slotSettings->slotId . 'Bgt');
                if($bgt == 0){
                    $response = 'unlogged';
                    exit( $response );
                }
                $level = $slotSettings->GetGameData($slotSettings->slotId . 'Level');
                $status = $slotSettings->GetGameData($slotSettings->slotId . 'Status');
                $freeSpins = [4,6,8,10,12];
                $fsmax = 0;
                $currentIndex = -1;
                for($k = 0; $k < 5; $k++){
                    if($status[$k] == 1){
                        $fsmax = $freeSpins[$k];
                        $currentIndex = $k;
                        break;
                    }
                }
                $ind = -1;
                if(isset($slotEvent['ind'])){
                    $ind = $slotEvent['ind'];
                }
                $isEnd= false;
                $level = 0;
                if($ind == 1){
                    $isWinChance = $slotSettings->BonusWinChance($currentIndex);
                    if($isWinChance == true){
                        $currentIndex++;
                        $level = 1;
                    }else{
                        $currentIndex--;
                    }
                    if($currentIndex == -1){
                        $fsmax = 0;
                    }else{
                        $fsmax = $freeSpins[$currentIndex];
                    }
                }
                if($fsmax == 0){
                    $status = [1,0,0,0,0];
                }else{
                    $status = [0,0,0,0,0];
                    for($k = 0; $k < 5; $k++){
                        if($freeSpins[$k] == $fsmax){
                            $status[$k] = 1;
                            break;
                        }
                    }
                }

                $isState = false;
                $strOtherResponse = '';
                $spinType = 's';
                $coef = $betline * $lines;
                if($fsmax == 0 || $fsmax == 12 || $ind == 0){
                    $level = 1;
                    $isEnd= true;
                    if($fsmax > 0){                        
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $fsmax);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                        
                        $stack = $slotSettings->GetReelStrips('bonus', $fsmax, $betline * $lines);
                        if($stack == null){
                            $response = 'unlogged';
                            exit( $response );
                        }
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    }else{
                        $isState = true;
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                }else{
                    $spinType = 'b';
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'Level', $level);
                $slotSettings->SetGameData($slotSettings->slotId . 'Status', $status);
                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
                {
                    $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1';
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin');
                    }
                }
                if($isEnd == true){
                    $strOtherResponse = $strOtherResponse . '&end=1';
                    if($fsmax == 0){
                        $strOtherResponse = $strOtherResponse . '&lifes=0';
                    }else{
                        $strOtherResponse = $strOtherResponse . '&lifes=1';
                    }
                }else{
                    $strOtherResponse = $strOtherResponse . '&lifes=1&end=0';
                }
               
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&bgid=0&wins=4,6,8,10,12&level='. $level . $strOtherResponse .'&balance='. $Balance .'&coef='. $coef .'&level='. $level .'&index='.$slotEvent['index'].'&balance_cash='. $Balance .'&balance_bonus=0.00&na='. $spinType .'&status='. implode(',', $status) .'&rw=0&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&bgt=35&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&wins_mask=nff,nff,nff,nff,nff&wp=0';

                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"Level":' . $slotSettings->GetGameData($slotSettings->slotId . 'Level') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"TumbWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"Status":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'Status')) . ',"TumbleState":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState')  . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * 100;
                }
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), 'WheelBonus', $isState);
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

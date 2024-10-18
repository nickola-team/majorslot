<?php 
namespace VanguardLTE\Games\LuckyFishingMegawaysPM
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
            $original_bet = 0.1;
            if( $slotEvent['slotEvent'] == 'doInit' ) 
            { 
                $lastEvent = $slotSettings->GetHistory();
                $_obf_StrResponse = '';
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Level', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'Status', [0,0,0,0]);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [19,8,7,10,12,19,10,7,3,9,5,12,11,9,1,11,12,5,11,9,8,5,12,6,11,9,10,5,19,9,11,9,19,19,19,9,19,9,19,19,19,9,19,19,19,19,19,12]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', '');
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
                $strOtherResponse = '';
                $currentReelSet = 0;
                $stack = null;
                $strWinLine = '';
                $spinType = 's';
                
                if( $lastEvent != 'NULL' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $lastEvent->serverResponse->TumbWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', $lastEvent->serverResponse->TumbleState);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', $lastEvent->serverResponse->Bgt);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Level', $lastEvent->serverResponse->Level);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Status', $lastEvent->serverResponse->Status);
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
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                $fsmore = 0;
                $rs_p = -1;
                if(isset($stack)){
                    $str_rs = $stack['rs'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $wmv = $stack['wmv'];
                    $str_accv = $stack['accv'];
                    $fsmore = $stack['fsmore'];
                    $strWinLine = $stack['win_line'];
                    $currentReelSet = $stack['reel_set'];
                    $arr_g = null;
                    if($stack['g'] != ''){
                        $arr_g = $stack['g'];
                    }
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    if($rs_p >= 0){
                        $strOtherResponse = $strOtherResponse . '&rs=mc&tmb_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') . '&rs_p=' . $rs_p . '&rs_c='. $rs_c .'&rs_m=' . $rs_m;
                    }
                    else if($rs_t > 0){
                        $strOtherResponse = $strOtherResponse.'&tmb_res='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbWin').'&rs_t='.$rs_t.'&tmb_win='.($slotSettings->GetGameData($slotSettings->slotId . 'TumbWin'));
                    }
                    if($str_accv != ''){
                        $strOtherResponse = $strOtherResponse . '&accm=cp~mp&acci=0&accv=' . $str_accv;
                    }
                    if($arr_g != null){
                        $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($arr_g));
                    }
                    if($wmv > 0){
                        $strOtherResponse = $strOtherResponse . '&wmt=pr&wmv=' . $wmv;
                        if($wmv > 1){
                            $strOtherResponse = $strOtherResponse . '&gwm=' . $wmv;
                        }
                    }
                    if($strWinLine != ''){
                        $arr_lines = explode('&', $strWinLine);
                        for($k = 0; $k < count($arr_lines); $k++){
                            $arr_sub_lines = explode('~', $arr_lines[$k]);
                            $arr_sub_lines[1] = str_replace(',', '', $arr_sub_lines[1]) / $original_bet * $bet;
                            $arr_lines[$k] = implode('~', $arr_sub_lines);
                        }
                        $strWinLine = implode('&', $arr_lines);
                        $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                    }
                }else{
                    $strOtherResponse = $strOtherResponse . '&accm=cp~mp&acci=0&accv=0~2&g={reg:{def_s:"10,7,3,9,5,12,11,9,1,11,12,5,11,9,8,5,12,6,11,9,10,5,19,9,11,9,19,19,19,9,19,9,19,19,19,9,19,19,19,19,19,12",def_sa:"10,5,5,8,6,4",def_sb:"1,9,10,5,11,12",reel_set:"1",s:"10,7,3,9,5,12,11,9,1,11,12,5,11,9,8,5,12,6,11,9,10,5,19,9,11,9,19,19,19,9,19,9,19,19,19,9,19,19,19,19,19,12",sa:"10,5,5,8,6,4",sb:"1,9,10,5,11,12",sh:"7",st:"rect",sw:"6"},top:{def_s:"12,10,7,8",def_sa:"10",def_sb:"8",reel_set:"0",s:"12,10,7,8",sa:"10",sb:"8",sh:"4",st:"rect",sw:"1"}}';
                }
                if($slotSettings->GetGameData($slotSettings->slotId . 'Bgt') == 35){
                    $spinType = 'b';
                    $strOtherResponse = $strOtherResponse . '&bgid=0&wins=10,14,18,22&level='. $slotSettings->GetGameData($slotSettings->slotId . 'Level') .'&coef='. ($bet * 20) .'&index='.$slotEvent['index'].'&status='. implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'Status')) .'&rw=0&bgt=35&wins_mask=nff,nff,nff,nff&wp=0&lifes=1&end=0';
                }
                else if($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') == 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                    $strOtherResponse = $strOtherResponse . '&bgid=0&wins=10,14,18,22&level=1&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1&lifes=0&end=1&wp=0&rw=0.00&status='. implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'Status'));
                }
                else if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 )
                {
                    $fs = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                    if($rs_p >= 0){
                        $fs = $fs - 1;
                    }
                    $strOtherResponse = $strOtherResponse . '&fs=' . $fs . '&fsmax=' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . '&fswin=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') .  '&fsres=' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&fsmul=1';
                    if($fsmore > 0){
                        $strOtherResponse = $strOtherResponse . '&fsmore=' . $fsmore;
                    }
                }
                    
                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=19,8,7,10,12,19,10,7,3,9,5,12,11,9,1,11,12,5,11,9,8,5,12,6,11,9,10,5,19,9,11,9,19,19,19,9,19,9,19,19,19,9,19,19,19,19,19,12&balance='. $Balance .'&nas=19&cfgs=4081&ver=2&index=1&balance_cash='. $Balance .'&reel_set_size=10&balance_bonus=0.00&na='. $spinType .'&scatters=1~0,0,0,0,0,0~0,0,0,0,0,0~1,1,1,1,1,1&gmb=0,0,0&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"452693",max_rnd_win:"5000",gamble_lvl_2:"68.28%",gamble_lvl_3:"74.14%",gamble_lvl_1:"60.38%"}}&wl_i=tbm~5000&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=50.00&purInit_e=1&sh=8&wilds=2~0,0,0,0,0,0~1,1,1,1,1,1&bonuses=0&fsbonus=&st=rect&c='.$bet.'&sw=6&sver=5&counter=2&paytable=0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;400,200,100,20,10,0;100,50,40,10,0,0;50,30,20,10,0,0;40,15,10,5,0,0;30,12,8,4,0,0;25,10,8,4,0,0;20,10,5,3,0,0;18,10,5,3,0,0;16,8,4,2,0,0;12,8,4,2,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0;0,0,0,0,0,0&l=20&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=6,2,11,12,9,11,10,5,12,5,9,12,4,9,5,7,4,12,4,17,11,14,12,8,2,7,7,12,11,3,8,12,12,5,5,12,9,10,7,9,5,14,16,17,12,10,2,5,17,9,10,4,11,12,9,14,8,2,4,9,9,2,2,8,9,5,7,9,12,9,10,7,12,12,4,17,12,5,5,3,7,12,10,4,11,10,10,7,3,12,7,5,2,7,6,5,18,3,3,4,8,9,9,9,12,12,9,9,12,10,9,9,12,7,5,7,2,9,5,10,9,5,4,10,9,4,9,17,2,7,12,10,12,5,9,10,9,15,12,7,4,4,8,9,7,9,17,3,16,8,4,9,8,17,2,10,5,3,17,2,10,9,9,4,12,12,10,10,18,8,7,12,10,3,9,7,17,7,11,17,2,4,2,4,17,2,12,7,6,3,14,10,9,4,6,12,18,3,9,7,7,10,2,6,12,3,10,10,10,4,8,9,6,16,12,7,3,2,9,6,11,7,4,5,5,12,11,10,4,5,4,9,9,2,15,12,3,4,2,10,10,12,9,2,5,12,7,3,3,9,4,2,3,5,17,2,4,9,4,7,17,9,8,9,12,7,10,12,15,8,7,10,9,3,15,6,7,2,18,17,3,9,5,17,12,4,2,9,12,9,9,3,5,10,12,10,2,4,17,6,2,10,5,16,12,11,8,6,7,7,4,7&s='.$lastReelStr.'&accInit=[{id:0,mask:"cp;mp"}]&reel_set2=2,2,11,3,10,7,8,12,18,5,2,6,12,12,4,11,10,7,2,17,11,10,2,12,4,2,8,16,15,7,10,11,2,11,5,12,9,10,2,8,11,9,6,6,10,10,10,12,12,14,8,6,11,18,10,2,3,2,4,4,7,12,6,8,11,10,7,10,2,13,10,10,14,11,10,2,8,7,6,5,16,8,11,8,10,11,3,4,12,9,11,9,9,9,9,10,9,9,3,17,11,10,8,4,18,9,7,18,7,7,3,11,3,8,9,8,10,12,10,3,2,15,9,10,10,7,13,10,2,11,7,11,11,5,9,12,8,7,9,9,11&t=243&reel_set1=11,11,4,5,10,1,5,11,10,11,10,9,7,5,1,9,8,6,10,5,5,5,12,11,5,10,5,6,5,4,6,4,11,7,11,4,4,9,5,3,11,3,11,10,10,10,10,1,9,8,9,5,5,10,4,7,10,7,4,11,12,5,10,3,5,5,4,11,11,11,10,12,5,11,6,12,11,1,10,5,12,1,4,5,11,7,4,10,1,1,10,4,4,4,11,4,11,5,10,9,8,7,4,4,11,5,5,10,4,10,5,4,7,11,5,8~12,8,9,12,5,12,11,9,12,4,9,6,10,5,9,7,7,9,9,9,1,12,9,9,8,1,5,12,8,10,7,8,6,10,9,10,7,8,8,8,8,10,7,3,12,12,7,7,12,5,7,12,7,9,8,4,4,11,7,7,7,7,7,1,3,12,12,9,5,7,7,9,12,4,4,9,7,9,9,6,12,12,12,12,11,7,7,5,1,9,4,11,8,12,6,11,9,9,8,6,7,8,7~6,6,10,10,9,8,7,8,9,6,4,4,8,6,1,7,12,5,8,5,10,11,11,4,12,8,7,11,10,10,7,6,9,11,8,1,6,12,8,8,12,7,9,11,8,10,7,4,11,3,5,6,6,6,1,1,9,5,4,12,4,5,7,4,10,6,8,7,3,12,4,6,4,5,9,12,5,10,6,4,6,10,6,12,8,6,6,4,10,4,4,5,11,7,3,12,5,9,4,10,6,7,10,4,6,1,4,12~9,4,12,12,11,5,7,4,10,3,10,3,5,10,5,9,5,11,6,9,5,6,3,10,10,10,5,6,4,1,5,3,10,5,6,9,8,10,5,12,5,9,4,9,8,6,5,5,11,8,12,5,5,5,6,6,4,7,9,10,5,10,5,10,10,11,4,8,6,7,6,10,6,3,6,10,3,3,6,6,6,10,5,5,10,7,9,4,5,1,6,5,7,5,7,5,7,10,5,8,7,9,8,9,9,5,9,9,9,5,6,6,8,1,6,9,12,10,10,12,4,10,8,5,5,7,1,12,3,8,6,5,9,12,5~10,5,11,1,11,10,4,8,11,9,8,6,6,12,6,6,5,3,6,5,7,9,12,7,11,3,8,1,6,10,6,9,11,1,6,5,4,11,12,9,4,9,12,4,11,6,9,11,5,11,6,5,8,7,9,11,4,1,10,6,6,6,6,12,5,11,5,6,12,11,12,8,9,6,11,10,6,8,1,5,7,7,9,5,9,7,12,5,10,6,6,4,11,10,10,5,9,9,7,11,6,1,6,6,12,4,12,9,9,6,1,12,7,11,4,1,5,8,12,3,12,5,6,9~8,4,7,9,5,9,5,9,4,4,10,10,9,10,5,10,12,4,9,12,11,7,11,12,12,8,5,10,1,10,7,9,9,9,4,11,10,4,5,8,3,11,9,12,9,5,9,11,8,4,12,5,5,7,12,1,12,12,11,6,11,7,5,11,9,5,12,12,12,10,10,9,9,6,6,12,11,8,11,4,9,3,1,4,6,7,6,9,9,4,11,12,5,9,1,6,7,4,1,12,9,10&reel_set4=10,10,3,12,13,7,12,10,4,3,11,11,8,16,9,12,3,6,2,15,11,8,18,2,11,9,18,12,2,18,3,10,11,18,12,11,7,10,10,18,3,8,9,2,6,17,10,10,9,8,12,12,18,10,18,5,8,11,14,7,10,17,18,8,12,7,4,13,8,7,2,11,10,2,7,5,11,8,10,6,8,9,9,11,7,7,12,5,12,11,9,10,12,12,11,10,7,3,10,10,10,9,11,6,7,8,7,7,14,11,6,11,8,11,10,11,10,12,11,10,10,11,10,12,11,11,4,12,3,2,16,16,2,11,3,11,3,10,5,5,18,10,4,4,3,8,9,10,6,8,18,9,8,2,8,7,8,9,9,13,12,7,9,10,4,7,11,8,11,9,18,11,5,8,16,8,10,6,14,4,3,7,11,3,8,5,10,13,11,10,10,12,4,11,6,4,18,10,7,2,11,9,9,9,11,2,11,8,15,10,18,10,11,2,9,15,4,11,12,5,6,11,9,10,10,9,4,17,7,18,11,14,2,11,8,7,9,7,9,9,12,12,10,3,10,18,5,7,2,9,8,17,11,8,10,2,12,3,7,4,10,11,7,18,11,9,8,8,7,11,11,15,3,11,5,8,9,10,7,10,7,7,6,2,11,7,9,9,10,11,7,3,12,10,10,11,8,9,10,7,9,7,10,4,9&purInit=[{type:"fsbl",bet:2000,bet_level:0}]&reel_set3=10,8,9,9,8,5,12,12,4,12,5,7,8,12,8,5,5,11,9,9,9,9,12,8,5,5,12,12,5,12,9,4,5,9,12,12,8,9,9,8,5,5,5,5,8,12,10,9,8,10,3,7,8,6,5,9,12,8,6,5,8,4,8,8,8,10,9,9,3,10,5,8,5,12,6,7,12,12,8,9,5,7,8,6,12,12,12,9,9,11,7,8,9,7,8,12,12,7,12,11,8,8,9,12,8,5,5,4~9,10,6,10,11,11,6,7,6,8,7,10,10,10,11,4,11,12,7,4,10,7,7,3,4,11,10,4,4,4,9,5,11,5,10,6,12,7,7,8,8,4,7,7,7,7,7,9,12,5,3,7,10,10,8,11,9,11,12,11,11,11,10,11,10,11,7,7,4,10,11,7,10,8,7,11~10,7,6,11,9,5,6,9,12,11,11,9,11,6,4,12,4,8,7,9,12,5,5,4,7,6,4,4,10,8,6,10,12,8,6,6,6,6,6,8,6,9,4,7,9,8,5,9,6,8,11,11,8,8,4,8,9,6,12,7,9,11,4,3,7,6,6,8,10,4,12,12,8~4,12,9,4,9,3,10,3,9,10,10,10,6,12,9,12,11,3,8,12,10,9,12,12,12,12,6,8,7,3,12,12,4,6,7,9,9,9,6,10,9,12,6,11,10,7,5,6,8,12~12,6,10,10,8,3,6,6,12,9,5,11,12,7,11,8,6,4,6,10,5,5,6,10,12,7,12,10,11,8,12,8,10,10,5,11,12,8,3,3,5,6,5,10,9,9,4,12,10,6,12,5,9,8,9,5,7,8,9,6,5,8,6,10,6,11,5,11,7,7,12,10,10,8,10,6,5,12,5,4,7,8,6,4,5,6,12,10,11,8,6,6,5,12,4,8,11,8,5,10,6,11,6,4,12,6,5,11,10,4,10,5,3,6,5,6,6,5,11,11,4,5,11,11,9,9,3,12,6,12,11,11,5,10,6,7,12,10,6,11,11,9,7,12,9,9,12,6,6,5,6,11~4,11,8,8,5,7,9,10,10,10,10,9,8,12,6,3,12,7,9,10,11,11,11,12,10,10,11,5,11,6,11,5,4&reel_set6=12,12,8,10,5,1,12,11,4,8,15,16,10,3,12,7,8,12,9,8,9,3,11,11,10,5,14,9,2,3,7,3,5,10,10,17,7,11,8,12,2,11,10,12,12,12,3,10,7,10,10,11,3,4,9,2,12,10,11,10,7,11,12,7,9,11,7,10,12,11,11,10,8,7,9,12,11,7,5,11,12,14,7,13,10,9,2,13,3,8,5,11,11,11,10,13,3,12,6,10,12,7,4,11,8,12,6,4,11,7,9,12,5,9,12,11,13,9,9,7,9,6,10,2,8,12,7,10,8,8,10,12,9,10,11,2,11,12,1,1,1,1,15,16,8,12,11,17,4,11,9,8,11,7,9,12,3,13,13,11,12,11,10,8,10,12,11,9,6,6,8,11,10,11,7,2,7,7,6,12,11,8,2,3,10,10,4&reel_set5=4,12,9,5,10,6,8,6,8,6,6,12,11,8,11,12,5,9,9,9,9,8,8,10,12,9,7,10,9,8,12,8,8,9,12,12,7,9,8,12,8,12,12,12,12,12,8,4,12,8,6,4,10,11,9,12,9,8,7,6,12,8,12,6,8,8,8,3,6,4,11,8,9,8,8,9,6,12,6,5,7,9,10,9,11,6,5,8~7,12,12,4,9,8,9,12,12,10,9,8,8,11,9,4,8,8,10,9,12,6,8,10,12,5,10,7,8,5,11,11,12,12,6,3,6,9,8,12,8,3,6,8,3,9,12,5,6,12,8,7,8,9,9,6,9,8~8,7,5,4,12,12,5,10,7,12,10,11,6,11,11,10,10,12,5,12,12,8,10,6,7,9,4,12,10,4,11,7,9,10,10,8,12,10,11,9,11,6,10,10,3,9,6,11,4,9,11,8,10,9,12,7,7,7,10,9,10,12,11,5,8,9,3,8,7,7,9,7,6,8,9,9,8,4,9,8,6,11,7,7,8,9,4,10,4,7,5,7,12,4,7,8,10,5,12,6,4,7,6,9,6,7,11,8,10,11,9,7,9,10,4,6~11,8,7,9,7,5,3,9,5,3,12,9,8,4,8,9,12,3,4,12,8,7,7,6,9,11,12,9,12,3,12,9,7,9,7,12,3,12,9,5,5,5,9,10,4,12,9,8,12,4,7,5,12,7,12,7,10,7,5,8,12,11,12,12,8,5,12,3,10,12,11,4,9,3,12,12,9,12,9,7,5,12,12,12,12,12,11,7,11,8,7,7,12,10,8,7,9,7,9,12,3,5,7,7,9,12,4,5,10,12,4,10,7,7,10,7,9,12,12,9,7,3,12,3,4,9,9,9,5,8,5,8,3,3,12,12,10,9,5,3,3,12,5,9,12,11,11,9,3,12,7,7,5,4,8,5,12,11,12,10,7,6,9,10,5,9,4,12,9,12~11,4,6,6,11,9,7,6,7,6,9,5,4,10,10,5,7,8,5,11,12,12,8,12,3,7~11,7,11,6,11,9,6,10,5,11,7,5,11,10,8,11,5,10,9,5,10,6,10,9,12,10,11,6,6,5,8,9,11,3,12,5,4,10,5,9,8,11,5,4,9,10,8,12,11,11,12,11,9,6,11,5,5,5,12,6,5,12,4,9,9,10,10,11,10,11,4,5,6,8,8,5,6,3,11,7,10,11,10,11,5,9,8,5,9,9,6,11,10,12,11,7,5,3,4,6,10,11,8,10,6,8,7,5,9,5,11,5,6,9,11,11,11,8,5,6,9,9,10,12,7,5,8,12,12,4,5,12,9,12,5,8,5,7,4,6,7,7,9,6,12,5,11,10,12,4,10,12,6,10,5,12,5,12,11,4,7,11,11,3,12,5,12,12,10,7,6,12,5,5,6&reel_set8=10,4,3,10,11,12,11,13,7,8,11,12,3,9,9,10,10,11,12,12,10,16,13,3,3,7,12,15,13,10,6,10,16,3,11,7,3,3,2,3,9,12,10,1,9,7,15,12,12,12,12,12,6,4,13,7,5,6,13,11,11,8,7,11,13,15,12,7,4,11,9,14,11,10,15,8,2,12,10,15,10,13,11,16,2,7,4,7,9,4,4,11,2,9,10,8,9,15,11,12,11,12,8,11,11,11,8,5,9,9,6,11,5,8,9,7,6,16,5,2,8,9,11,9,10,14,2,8,12,10,12,7,1,10,10,7,10,7,11,13,14,17,12,12,10,11,13,8,14,7,10,16,7,17,12,10,3,1,1,1,10,5,5,11,7,11,11,8,17,11,8,11,13,17,13,14,12,2,10,9,11,13,3,13,17,7,11,10,5,14,16,8,14,12,16,1,17,9,8,17,4,9,10,11,8,3,9,15,10,2,2,11&reel_set7=8,9,8,10,11,10,8,12,5,9,4,12,12,6,10,9,9,10,12,10,9,6,9,6,7,6,9,8,11,12,8,11,8,9,6,8,12,8,8,6,12,9,9,9,3,8,9,6,11,4,8,5,4,6,8,9,12,8,3,12,8,11,12,12,5,9,8,12,9,7,6,6,9,6,10,12,12,8,9,7,9,6,8,6,11,8,12,12,12,12,9,8,9,11,8,12,9,12,12,8,11,8,6,9,4,8,7,12,10,12,10,12,12,8,12,10,5,6,4,5,4,6,7,9,4,8,8,6,6,12,12,8,8,8,9,12,9,8,12,8,8,5,8,8,6,8,9,12,11,8,6,7,7,9,12,8,8,12,8,11,5,6,12,8,10,6,6,8,12,12,11,10,6,5,9,9,5~5,9,10,8,8,6,12,6,8,8,10,5,12,4,9,11,12,8,12,12,5,8,9,9,11,9,6,8,6,9,12,8,7,7,8,8,3,9,9,4,10,3,11,12,12,10~4,10,9,6,8,8,9,9,6,6,10,10,7,9,7,7,12,11,8,11,11,12,9,10,10,4,7,10,8,3,9,7,5,12,7,8,11,9,11,11,9,5,4,10,10,7,11,10,12,11,10,8,9,10,8,6,10,4,5,7,9,9,4,11,8,4,11,5,4,7,7,7,8,11,6,9,9,4,4,12,6,12,5,9,7,9,3,8,12,10,12,9,6,9,6,6,7,9,7,10,7,7,4,6,12,5,12,9,11,10,10,12,7,10,12,7,5,10,12,8,6,7,11,11,4,8,8,12,7,11,9,10,12,5,8,10,4,9,10,8,6,7,10,7~4,4,12,5,7,5,11,7,9,3,7,12,7,12,12,9,5,10,12,12,7,7,6,8,9,7,5,9,9,12,12,3,7,7,3,11,12,4,11,6,7,5,5,5,7,4,10,12,3,9,4,4,11,12,12,9,8,12,9,9,12,3,12,10,8,12,7,7,12,3,11,7,9,8,9,3,9,7,12,10,9,10,7,4,9,5,12,12,12,12,10,12,8,3,12,9,10,12,4,7,3,7,12,12,9,5,5,11,5,7,3,9,12,9,10,7,7,5,9,5,3,3,12,9,8,12,12,7,3,5,12,9,9,9,8,7,12,3,11,4,7,12,5,12,9,12,7,8,3,3,5,11,5,12,12,9,8,9,8,10,10,12,7,9,12,10,5,8,9,9,4,8,11,12,4,12,5~6,5,7,8,9,12,11,12,5,6,7,11,11,6,5,6,7,8,7,9,5,10,11,5,3,6,6,11,10,8,9,12,8,4,6,7,6,3,11,8,7,7,9,7,7,10,11,4,5,9,5,12,11,5,12,7,11,12,10,10,11,6,4,12,6,7,11,10,5,9,7,7,5,5,7,10,12,7,12,7,5,12,5,4,4,11,11,12,9,6,10,11,7,8,8,5,12,8,7,12,12,11,7,12,3,12,5,4,8,7,11,6,5,6,6,11,6,3,12,12,9,5,3,7,5,9,6,3,11,6,9,7,7,12,8,4,7,10,9,6,6,7,4,6,8,11,5,7,5,8,5,5,7,7,11,7,6,12,6,12,8,8,6,6,5,4,9,11~12,9,11,8,5,7,5,5,6,9,12,4,11,10,6,9,7,6,10,10,6,11,10,8,4,9,9,6,10,10,7,12,9,11,9,5,10,9,11,5,12,5,4,5,11,8,5,10,12,8,9,7,7,11,4,5,5,5,11,11,12,12,5,11,11,3,6,8,12,5,10,5,10,7,5,6,12,11,11,6,7,12,10,8,8,12,4,11,5,10,5,11,11,12,5,10,3,9,12,6,6,11,8,6,5,3,11,5,11,12,6,5,11,10,11,11,11,7,7,6,5,5,6,5,8,10,5,8,12,12,11,11,10,10,12,9,4,9,6,8,11,5,5,6,9,7,10,12,11,4,5,9,5,6,3,4,11,6,5,10,11,4,9,12,5,10,8,7,12,12,9,10,6,9&reel_set9=8,9,5,12,5,12,7,9,7,9,9,9,9,5,11,9,7,12,7,5,7,8,6,12,12,12,12,9,7,12,4,11,12,5,5,3,6,7,7,7,7,10,10,5,4,10,12,7,9,9,12,7~12,7,5,9,7,8,6,3,9,5,8,12,10,12,10,3,7,7,6,12,9,7,7,9,9,5,9,7,7,12,11,7,12,12,6,12,12,5,7,9,12,12,7,10,9,12,6,3,7,9,11,7,8,6,7,9,3,7,5,10,5,12,11,4,12,9,11,12,5,9,7,7,12,9,4,10,8,7,11,10,7,10,9,4,9,5,7~10,8,8,12,9,11,5,4,10,10,6,10,11,7,10,8,4,8,9,10,5,4,8,11,8,9,11,9,7,6,11,8,8,8,8,12,7,12,9,4,8,5,12,9,11,9,4,11,9,10,10,7,4,12,10,8,6,8,12,9,4,4,6,3,5,10,8,7~9,9,10,11,4,8,6,3,12,8,9,7,10,8,10,12,12,8,8,4,12,12,6,11,4,8,4,12,9,11,4,6,8,4,12,12,10,6,6,6,8,9,12,12,3,12,8,8,11,8,9,3,6,8,10,3,3,12,8,9,12,7,6,3,8,9,8,4,9,7,12,9,12,10,3,9,12,3,12,12,12,8,7,7,12,9,12,3,8,3,12,9,7,7,12,8,7,3,9,6,11,12,12,6,12,4,8,9,12,12,3,9,9,10,10,6,10,12,9,9,9,9,12,8,6,5,7,3,8,6,9,6,11,12,9,5,8,12,3,7,12,12,9,12,12,7,9,6,9,6,12,6,8,9,11,4,8,12,12,11,10,4~7,10,11,5,9,6,8,5,12,3,8,8,4,12,7,11,6~11,4,7,11,12,3,5,6,12,6,6,6,12,8,11,6,4,12,10,9,10,6,11,11,11,6,7,5,9,8,11,10,10,9,6,5&total_bet_min=10.00';
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
                    if( $slotEvent['slotEvent'] == 'doSpin' && $slotSettings->GetBalance() < ($lines * $betline)  && $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState') == -1) 
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
                // $winType = 'win';
                $allBet = $betline * $lines;
                if($pur >= 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $allBet * 100;
                }
                $tumbAndFreeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'freespin' || $isTumb == true){
                    if($slotEvent['slotEvent'] == 'freespin' && $isTumb == false){
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    }
                    $leftFreeGames = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') - $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame'); 
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Level', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Status', [0,0,0,0]);
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
                $empty = '19';
                $Balance = $slotSettings->GetBalance();
                $totalWin = 0;
                $this->winLines = [];
                $bonusMpl = 1;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $arr_g = null;
                $wmv = 0;
                $str_rs = '';
                $rs_p = -1;
                $rs_m = 0;
                $rs_c = 0;
                $rs_t = 0;
                $str_accv = 0;
                $fsmore = 0;
                $subScatterReel = null;
                if($slotEvent['slotEvent'] == 'freespin' || $isTumb == true){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    if($slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') == 1 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 22){
                        $symCount = 0;
                        for($i = 0; $i < 48; $i++){
                            if($lastReel[$i] == 1){
                                $symCount++;
                            }
                        }
                        if($symCount >= 7){
                            $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            $lastReel = explode(',', $stack['reel']);
                        }
                    }
                    if($stack['g'] != ''){
                        $arr_g = $stack['g'];
                    }
                    $currentReelSet = $stack['reel_set'];
                    $str_rs = $stack['rs'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $wmv = $stack['wmv'];
                    $str_accv = $stack['accv'];
                    $fsmore = $stack['fsmore'];
                    $strWinLine = $stack['win_line'];
                    if($stack['g'] != ''){
                        $arr_g = $stack['g'];
                    }
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, 0, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $lastReel = explode(',', $stack[0]['reel']);
                    if($stack[0]['g'] != ''){
                        $arr_g = $stack[0]['g'];
                    }
                    $currentReelSet = $stack[0]['reel_set'];
                    $str_rs = $stack[0]['rs'];
                    $rs_p = $stack[0]['rs_p'];
                    $rs_c = $stack[0]['rs_c'];
                    $rs_m = $stack[0]['rs_m'];
                    $rs_t = $stack[0]['rs_t'];
                    $wmv = $stack[0]['wmv'];
                    $str_accv = $stack[0]['accv'];
                    $fsmore = $stack[0]['fsmore'];
                    $strWinLine = $stack[0]['win_line'];
                    if($stack[0]['g'] != ''){
                        $arr_g = $stack[0]['g'];
                    }
                }
                $reels = [];
                $scatterCount = 0;
                $scatterPoses = [];
                $scatterWin = 0;
                for($i = 0; $i < 48; $i++){
                    if($lastReel[$i] == $scatter){
                        $scatterCount++;
                        $scatterPoses[] = $i;
                    }
                }
                
                if($strWinLine != ''){
                    $arr_lines = explode('&', $strWinLine);
                    for($k = 0; $k < count($arr_lines); $k++){
                        $arr_sub_lines = explode('~', $arr_lines[$k]);
                        $arr_sub_lines[1] = str_replace(',', '', $arr_sub_lines[1]) / $original_bet * $betline;
                        $totalWin = $totalWin + $arr_sub_lines[1];
                        $arr_lines[$k] = implode('~', $arr_sub_lines);
                    }
                    $strWinLine = implode('&', $arr_lines);
                } 

                $spinType = 's';
                $isNewTumb = false;
                if($rs_p >= 0){
                    $isNewTumb = true;
                }
                if( $totalWin > 0) 
                {
                    $spinType = 'c';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }
                $_obf_totalWin = $totalWin;
                $isState = true;
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbleState', $rs_p);
                if($isNewTumb == true){
                    $isState = false;
                    $spinType = 's';
                }else{
                    if($scatterCount >= 7 && $slotEvent['slotEvent'] != 'freespin'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 22);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                    }
                    if($fsmore > 0 && $slotEvent['slotEvent'] == 'freespin'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $fsmore);
                    }
                }
                $strLastReel = implode(',', $lastReel);
                $lastLogReel = [];
                for($k = 0; $k < count($lastReel); $k++){
                    if($k < 6){
                        $lastLogReel[6-$k-1] = $lastReel[$k];
                    }else{
                        $lastLogReel[$k] = $lastReel[$k];
                    }
                }
                $strLastLogReel = implode(',', $lastLogReel);

                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $strOtherResponse = '';
                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') + $totalWin);
                    $spinType = 's';
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $isEnd = false;
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 && $isNewTumb == false) 
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') + $totalWin);
                    if($isNewTumb == false){
                        if($scatterCount >= 7){
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 22);
                            $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 1);
                            $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 1);
                            
                            $isState = false;
                            $stack = $slotSettings->GetReelStrips('bonus', 22, $betline * $lines);
                            if($stack == null){
                                $response = 'unlogged';
                                exit( $response );
                            }
                            $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                            $strOtherResponse = $strOtherResponse . '&fsmul=1&fsmax='.$slotSettings->GetGameData($slotSettings->slotId . 'FreeGames').'&fswin=0.00&fsres=0.00&fs=1';
                        }else if($scatterCount >=4){
                            $isState = false;
                            $spinType = 'b';
                            $status = [0,0,0,0];
                            $status[$scatterCount - 4] = 1;
                            $slotSettings->SetGameData($slotSettings->slotId . 'Status', $status);
                            $strOtherResponse = $strOtherResponse . '&bgid=0&wins=10,14,18,22&coef='.($betline * $lines).'&level=0&status='. implode(',', $status) .'&bgt=35&lifes=1&bw=1&wins_mask=nff,nff,nff,nff&wp=0&end=0';
                            $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 35);
                        }else{
                            if($slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') > 0){
                                $spinType = 'c';
                            }
                        }
                        if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                            $strOtherResponse = $strOtherResponse . '&purtr=1&puri=' . $pur;
                        }
                    }
                }
                if($rs_p >= 0){
                    $strOtherResponse = $strOtherResponse . '&rs=mc&tmb_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') . '&rs_p=' . $rs_p . '&rs_c='. $rs_c .'&rs_m=' . $rs_m;
                }
                else if($rs_t > 0){
                    $strOtherResponse = $strOtherResponse.'&tmb_res='.$slotSettings->GetGameData($slotSettings->slotId . 'TumbWin').'&rs_t='.$rs_t.'&tmb_win='.($slotSettings->GetGameData($slotSettings->slotId . 'TumbWin'));
                }
                if($str_accv != ''){
                    $strOtherResponse = $strOtherResponse . '&accm=cp~mp&acci=0&accv=' . $str_accv;
                }
                if($arr_g != null){
                    $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($arr_g));
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                }
                if($wmv > 0){
                    $strOtherResponse = $strOtherResponse . '&wmt=pr&wmv=' . $wmv;
                    if($wmv > 1){
                        $strOtherResponse = $strOtherResponse . '&gwm=' . $wmv;
                    }
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse  .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sh=8&st=rect&c='.$betline.'&sw=6&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&w='.$totalWin.'&s=';
                $response_log = $response.$strLastLogReel;
                $response = $response . $strLastReel;
                if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0)  && $isNewTumb == false) 
                {
                    //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                }
                if($isNewTumb == false){
                    if( $slotEvent['slotEvent'] != 'freespin' && $scatterCount >= 4) 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"TumbWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID')  . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"TumbleState":' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbleState')  . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"Level":' . $slotSettings->GetGameData($slotSettings->slotId . 'Level')  . ',"Status":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'Status')) .',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
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
                if($bgt != 35){
                    $response = 'unlogged';
                    exit( $response );
                }
                $level = $slotSettings->GetGameData($slotSettings->slotId . 'Level');
                $status = $slotSettings->GetGameData($slotSettings->slotId . 'Status');
                $freeSpins = [10,14,18,22];
                $fsmax = 0;
                $currentIndex = -1;
                for($k = 0; $k < 4; $k++){
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
                        $currentIndex = -1;
                    }
                    if($currentIndex == -1){
                        $fsmax = 0;
                    }else{
                        $fsmax = $freeSpins[$currentIndex];
                    }
                }
                if($fsmax == 0){
                    $status = [1,0,0,0];
                }else{
                    $status = [0,0,0,0];
                    for($k = 0; $k < 4; $k++){
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
                if($fsmax == 0 || $fsmax == 22 || $ind == 0){
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
               
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . '&bgid=0&wins=10,14,18,22&level='. $level . $strOtherResponse .'&balance='. $Balance .'&coef='. $coef .'&level='. $level .'&index='.$slotEvent['index'].'&balance_cash='. $Balance .'&balance_bonus=0.00&na='. $spinType .'&status='. implode(',', $status) .'&rw=0&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&bgt=35&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&wins_mask=nff,nff,nff,nff&wp=0';

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
                if($slotEvent['action'] == 'doSpin'){
                    $this->saveGameLog($slotEvent, $response_log, $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'), $slotSettings);
                }else{
                    $this->saveGameLog($slotEvent, $response, $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'), $slotSettings);
                }
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
                for($r = 0; $r < 8; $r++){
                    if($firstSymbol == $reels[$repeatCount][$r] || $reels[$repeatCount][$r] == $wild){
                        $this->findZokbos($reels, $firstSymbol, $repeatCount + 1, $strLineWin . '~' . ($repeatCount + $r * 6));
                        $bPathEnded = false;
                    }
                }
            }
            if($bPathEnded == true){
                if($repeatCount >= 3 || ($firstSymbol == 3 && $repeatCount == 2)){
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

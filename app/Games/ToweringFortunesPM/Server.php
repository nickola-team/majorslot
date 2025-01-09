<?php 
namespace VanguardLTE\Games\ToweringFortunesPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [5,9,10,6,10,4,10,9,5,9,6,8,10,6,11]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $lastEvent->serverResponse->CurrentRespin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
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
                        $stack = $FreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') -1];
                    }
                    $bet = $lastEvent->serverResponse->bet;
                }
                else
                {
                    $bet = '50.00';
                }
                $spinType = 's';
                $arr_g = null;
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if(isset($stack)){
                    if($stack['reel_set'] > -1){
                        $currentReelSet = $stack['reel_set'];
                    }
                    $str_initReel = $stack['is'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $str_trail = $stack['trail'];
                    $str_stf = $stack['stf'];
                    $arr_g = $stack['g'];
                    $pw = $stack['pw'];
                    $rs_more = $stack['rs_more'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $strWinLine = $stack['win_line'];
                    
                    if($rs_p >= 0){
                        $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p . '&rs_c=' . $rs_c . '&rs_m=' . $rs_m;
                        if($rs_p > 0){
                            $strOtherResponse = $strOtherResponse . '&rs_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin');
                        }
                    }
                    if($rs_t > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t . '&rs_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin');
                    }if($rs_more > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_more=' . $rs_more;
                    }
                    
                    if($str_initReel != ''){
                        $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                    }
                    if($rs_more > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_more=' . $rs_more;
                    }
                    if($str_stf != ''){
                        $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                    }
                    if($str_trail != ''){
                        $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                    }
                    if($pw > 0){
                        $strOtherResponse = $strOtherResponse . '&pw=' . (str_replace(',', '', $pw) / $original_bet * $bet);
                    }
                    if($str_mo != ''){
                        $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                    }
                    if($arr_g != null){
                        if($arr_g != null && isset($arr_g['hs'])){
                            $moneyWin = 0;
                            if(isset($arr_g['hs']['mo_tv'])){
                                $moneyWin = $arr_g['hs']['mo_tv'] * $bet;
                                if(isset($arr_g['hs']['mo_m']) && $arr_g['hs']['mo_m'] > 1){
                                    $moneyWin = $moneyWin * $arr_g['hs']['mo_m'];
                                }
                                $arr_g['hs']['mo_tw'] = '' . $moneyWin;
                            }
                        }
                        $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($arr_g));
                    }else{
                        $strOtherResponse = $strOtherResponse . '&g={hs:{def_s:"13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13",def_sa:"13,13,13,13,13",def_sb:"13,13,13,13,13",s:"13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13",sa:"13,13,13,13,13",sb:"13,13,13,13,13",sh:"11",st:"rect",sw:"5"}}';
                    }
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
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
                    $strOtherResponse = $strOtherResponse . '&g={hs:{def_s:"13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13",def_sa:"13,13,13,13,13",def_sb:"13,13,13,13,13",s:"13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,13",sa:"13,13,13,13,13",sb:"13,13,13,13,13",sh:"11",st:"rect",sw:"5"}}';
                }
                
                $Balance = $slotSettings->GetBalance();  
                $response = 'def_s=5,9,10,6,10,4,10,9,5,9,6,8,10,6,11&balance='. $Balance .'&cfgs=6662&ver=3&mo_s=1&index=1&balance_cash='. $Balance .'&mo_v=1,2,3,4,5,6,7,8,10,12,15,20,30,50&def_sb=6,10,11,7,9&reel_set_size=10&def_sa=6,9,10,6,11&reel_set='. $currentReelSet .'&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"100000000",max_rnd_win:"6422"}}&wl_i=tbm~6422&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime='. floor(microtime(true) * 1000) .'&sa=6,9,10,6,11&sb=6,10,11,7,9&sc='. implode(',', $slotSettings->Bet) . $strOtherResponse .'&defc=50.00&sh=3&wilds=2~5000,1000,100,0,0~1,1,1,1,1&bonuses=0&st=rect&c='. $bet .'&sw=5&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;50,20,5,0,0;50,20,5,0,0;50,20,5,0,0;100,30,10,0,0;150,40,10,0,0;200,50,10,0,0;400,100,30,0,0;750,100,30,0,0;1000,400,40,0,0;0,0,0,0,0;0,0,0,0,0&l=20&reel_set0=8,4,2,10,3,3,11,3,9,7,3,6,3,11,10,3,3,7,2,3,5,4,10,3,10,8,6,9,11,4,11,7,5,3,6,7,11,6,3,7,4,6,3,3,4,8,3,11,7,11,3,4,3,6,3,8,6,5,3,7,2,11,9,7,4,5,6,5,8,6,11,3,3,5,3~7,5,8,7,3,6,1,6,1,10,5,7,5,1,10,3,7,6,5,4,6,4,3,7,1,3,1,5,1,3,5,3,8,5,3,8,4,8,3,8,7,3,6,5,10,3,10,6,10,5,9,5,2,3,5,6,10,5,6,2,5,3,7,6,3,2~4,5,9,1,9,5,9,7,6,5,4,11,5,7,9,11,5,9,1,5,7,10,2,6,1,9,1,4,3,10,4,1,5,3,11,1,7,2,7,6,8,6,3,6,10,5,6,11,5,10~3,5,3,8,3,5,3,2,3,5,5,6,5,5,7,4,3,6,5,3,3,5,3,3,5,4,5,3,11,2,8,6,3,4,5,6,3,3,3,7,2,5,4,5,3,7,5,11,3,9,3,5,5,8,7,6,10,5,5,3,8,3,5,4,3,8,3,5,9,6,5,3,5,7,3,8,5,4,3~9,4,7,10,5,3,8,5,3,4,11,2,8,4,7,4,9,2,3,6,3,5,5,8,9,5,10,5,5,3,7,10,5,5,3,8,5,7,5,5,7,5,5,3,8,7,3,4,5,5,6,3,8,3,7,11,9,3&s='.$lastReelStr.'&reel_set2=7,10,6,8,6,10,6,10,7,2,6,5,9,1,6,4,1,8,5,8,4,6,5,6,9,5,1,7,6,4,10,5,10,3,10,7,5,6,5,7,1,3,1,5,2,5,1,5,7,6,5,7,4,6,9,6,5,2,10,7,10,1,9,8,5,1,6,4,6,9~6,9,6,6,7,8,4,7,4,6,10,6,8,11,10,6,6,10,8,10,11,3,4,11,10,7,6,11,8,6,8,3,3,4,10,7,11,3,10,8,4,6,7,4,7,2,3,4,6,4,6,2,11,7,3,4,8,3,2,10,4,6,6,8,10,8,3,3,10,3,4,11,6,3,3,6~6,5,6,3,10,6,11,7,5,6,10,7,5,11,5,8,6,3,5,3,5,9,10,5,3,5,6,3,8,10,6,7,5,2,3,6,2,6,5,10,6,3,7,8,5,7,8,2,3,8,10,6,5,3,6,8,6,7,3,7,6,4,6,5,4,7,6,11,9,11,3,7~11,6,7,6,5,5,8,4,5,5,6,7,11,9,8,6,3,5,6,2,8,2,6,5,2,1,6,4,9,8,1,7,3,11,6,7,5,7,1,5,3,1,5,6,4,9,7,7,11,4,1,5,11,9,6,10,3~3,8,5,8,5,3,4,7,10,4,8,9,4,9,8,5,3,2,5,7,3,7,4,8,3,8,3,5,7,8,7,10,3,5,9,5,8,7,9,3,5,8,9,11,4,9,4,9,3,5,7,11,3,7,9,5,4,7,8,4&reel_set1=6,7,10,6,4,6,11,8,5,4,11,9,4,5,9,4,5,7,6,9,7,4,6,8,6,4,6,5,9,4,3,9,8,7,6,9,4,9,5,6,7,6,4,6,7,4,11,7,3,10,5,4,7,5,6,7,8,7,4,6,11,6,9,4,8,4,8,7,6,8,6~8,4,8,11,6,10,4,3,1,3,4,7,3,4,3,3,2,10,8,5,4,7,5,7,3,10,4,4,8,1,3,7,4,4,3,10,8,9,4,8~9,5,4,5,9,6,7,4,8,5,8,4,5,5,6,7,3,9,7,3,5,4,9,7,4,5,9,7,6,4,2,4,11,8,6,10,6,8,11,5,6,4,2,8,7,3,10,4,3,9,11,9,4,3,8,7,8,10,4~1,5,3,4,3,4,7,8,1,5,4,8,4,5,7,5,4,4,7,4,2,7,4,11,6,1,4,7,4,10,4,5,8,5,1,8,4,10,3,8,10,4,3,4,1,4,5,6,5,4,1,4,8,3,4,11,9,2,7,10,2,8,4,8,7,3,9,8,10,8,3,9,5~5,4,8,5,4,9,11,4,3,10,4,4,10,6,4,3,7,4,3,4,4,7,5,2,6,3,8,9,3,10,8,5,7,9,7,6,8,5,4,4,5,4,10,11,9,8,3,4&reel_set4=12,12,4,9,12,7,12,12,7,4,12,12,10,3,4,12,12,2,3,12,12,7,12,2,3,7,12,12,12,6,12,9,12,4,12,3,12,12,8,5,12,12,2,12,12,9,6,8,12,12,11,4,10,12,12,8,5~12,3,11,3,10,1,12,10,9,12,3,9,12,12,11,7,12,3,12,12,9,3,5,12,7,12,12,6,12,12,8,5,12,4,12,7,2,12,3,12,4,6,12,2,12,7,9,12,12,1,12,10,1,12,12,3,12,12,2,7,2,12~8,12,4,3,5,12,11,12,9,12,10,12,12,3,12,2,6,7,4,3,12,12,12,12,2,12,2,12,12,5,12,12,4,12,7,10,8,6,12,12,11,12,12,10~5,6,11,12,9,12,12,5,12,12,3,12,4,12,12,6,3,10,12,12,2,12,9,12,12,12,7,12,12,3,8,3,12,3,11,5,9,11,12,11,12,12,5,2,12,12,8,12,12,4,12~4,12,12,10,6,11,12,12,3,12,2,5,3,1,6,12,12,6,12,3,12,3,12,9,4,5,3,12,10,12,10,12,12,11,12,1,2,11,12,4,12,7,3,5,12,12,9,4,2,12,7,12,11,1,10,12,6,9,12,12&reel_set3=8,4,6,5,9,7,2,5,8,5,6,5,6,9,1,7,8,3,5,3,9,5,8,7,4,2,1,9,7,9,6,11,6,3,7,6,9,7,8,9~6,8,5,4,5,7,5,10,9,4,5,7,10,5,9,11,5,6,8,7,9,5,10,9,6,3,10,9,7,10,11,8,2,9,10,6,10,11,8,11,7,9,10,9,2,7,11~10,5,9,10,4,10,9,7,6,9,10,8,7,8,10,6,8,7,8,6,9,11,9,4,9,8,6,9,6,7,9,6,5,7,3,7,10,6,10,6,10,4,7,10,6,7,9,6,4,9,3,7,3,7~10,7,7,3,4,10,8,7,3,3,7,4,8,4,10,7,6,8,5,7,10,7,3,7,4,7,5,7,5,3,3,4,3,8,3,6,5,7,6,3,3,10,3,4,5,3,4,8,7,10,9,3,3,7,5,7,4,7,4,6,8,3,5,6,5,4,7,4,2,3,5,3,5,10,4~4,8,11,10,3,10,8,7,8,1,5,10,9,5,3,8,5,1,4,3,3,4,5,3,8,3,5,9,1,3,3,1,8,2,5,3,5,2,4,5,4,8,2,6,5,6,8,5,3,9,1,11,8,1,3,5,1,3,4,8,4,3,5,3&reel_set6=8,12,12,10,12,8,2,6,12,6,11,12,12,8,12,12,2,12,12,7,3,2,12,12,5,12,6,12,12,12,12,10,8,12,4,12,12,9,11,12,4,5,12,4,12,12,2,12,12,10,11,12,7,12,7,12,12,6,12~12,3,5,12,3,12,12,9,12,11,2,12,12,8,12,8,3,12,2,6,12,5,12,4,3,12,12,12,12,2,12,7,12,12,11,12,12,3,2,12,7,12,12,9,12,12,4,6,12,12,5,12,12,7,12,12,5,12~11,3,4,12,12,2,12,12,1,12,6,5,12,12,1,12,12,12,12,8,12,12,1,12,3,12,3,8,12,12,5,12,12,2,12,7,12~3,7,1,4,6,2,4,2,4,11,1,8,1,6,8,11,10,1,5,4,3,9,11,8,7,1,5,3,10,5,7,4,3,6,11,8,5,3,7,5,9,3,6,9,7,5,1,9,4,2~7,2,5,3,8,9,4,7,10,4,7,5,4,6,3,9,3,10,2,10,2,6,4,5,11,5,11,6,12,8,3,10,8,5,8,3,11,6&reel_set5=12,1,6,4,12,12,1,12,12,2,3,12,7,12,4,3,12,12,7,6,5,12,12,8,12,3,12,12,12,8,12,6,12,5,12,12,8,7,5,12,12,8,3,12,12,1,12,1,6,12,7,12,2,8,12,12,4,12~12,7,12,3,12,5,2,12,9,12,8,9,6,12,12,3,12,5,8,12,12,3,12,12,10,12,12,3,11,3,12,12,12,5,12,2,7,12,11,3,12,12,7,12,12,3,12,11,5,12,4,11,9,12,4,12,7,12,12,10,12,5,12~12,8,12,12,5,12,8,12,5,3,2,11,12,5,12,12,4,11,12,9,12,4,6,10,12,5,8,7,12,12,12,8,12,12,4,12,5,10,12,12,7,12,2,12,6,12,3,5,7,3,12,12,3,12,12,3,11,12,3,12~3,8,3,1,12,5,12,9,2,12,5,12,12,9,5,12,11,12,5,12,12,12,11,12,12,1,12,12,8,4,2,12,12,9,12,1,12,11,12,8,4~6,3,9,8,4,3,4,2,10,9,8,5,11,5,6,9,2,8,3,4,2,5,9,5,7,4,7,11,7,12,5,6,9,8,11,3,6,3,8,4,6,7,3,4,10,6,8,3,11,6,5,10,5,8,3&reel_set8=11,3,6,4,12,12,9,12,9,12,12,8,7,12,5,4,12,12,12,1,12,7,12,12,5,12,3,6,3,12,1,12,8,12,2~8,12,3,12,4,12,12,6,12,7,4,5,2,12,11,12,4,12,8,12,9,12,5,9,4,12,12,12,9,12,12,9,5,6,9,12,12,11,3,9,12,12,3,12,12,2,12,8,12,3,7,12,3,5,7,12,3,12~11,6,8,4,9,6,4,6,11,9,12,10,7,11,5,10,4,10,3,6,12,7,11,10,6,6,8,6,3,7,11,3,9,5,5,10,11,3,5,6,6,7,2,3~6,12,12,6,3,7,12,12,6,7,12,8,12,3,12,9,3,12,9,11,12,5,12,12,2,10,12,5,12,11,3,7,5,12,5,12,3,12,12,7,9,12,6,12,7,12,12,5,12,6,12,12,9,12,12,2,12,4,5,4,12,12,3~2,6,5,8,1,12,5,6,8,11,3,9,8,6,3,9,8,2,7,5,1,10,3,4,3,1,6,7,12,9,11,8,4,11,6,3,1,5,3,4,5,3,8,12,7,5,9,12,5,1,10,3,1,3,4,6,11,8,12,11,1,8&reel_set7=12,4,7,12,12,5,12,12,8,3,12,12,3,4,12,4,12,5,12,12,3,12,12,12,5,12,11,12,6,9,12,12,3,12,6,12,11,4,12,3,5,12,2,12,12,7,9,12~5,4,7,12,12,9,12,7,12,12,3,8,12,1,11,1,12,3,11,12,2,12,12,6,1,12,3,12,9,12,3,12,12,7,2,12,12,5,12,1,7,12,12,5,3,12,12,6,12~5,12,3,7,12,12,5,12,2,12,12,11,12,12,10,12,7,12,11,12,5,2,6,12,12,4,12,12,4,12,12,3,12,12,8,12,12,5,6,3,12,12,8,12,4,9,12,6,12,12,3,12,9,12,12,7,12~5,1,6,4,5,9,5,1,2,1,3,11,6,7,10,1,6,7,4,6,7,10,8,12,6,12,11,10,7,10,1,9,6,11,3,11,8,4~9,3,12,12,12,12,4,9,12,10,9,12,12,12,12,9,12,12,12,5,4,12,12,3,6,5,12,12,12,12,8,12,12,2,12,12,8,12,5,3,8,12,12,5,12,12,11,12,6,11,12,3,12,12,8,5,10,8,2,12&reel_set9=5,7,5,3,5,5,3,4,6,5,3,7,3,4,5,5,6,6,7,5,6,4,3,4,6,7,5,3,7,4,3,6,6,4,3,5,7,6,4,7,4,7,4,7~8,8,9,8,10,11,10,10,9,9,10,9,11,8,10,11,9,8,8,11,8,9,10,8,8,9,9,8,11,10,9,10,10,8,10,9,10,8,9,10,11,8,11,10,9,8,10,8,8,10,8,11,8,9,11,8,9,9,8,10,10~2,3,9,2,8,3,9,3,7,5,3,11,9,7,5,8,5,4,7,8,11,8,6,2,2,2,2,7,5,9,10,5,10,2,8,9,3,6,5,4,6,4,2,5,10,8,3,7,6,11,10,9,7,6,8,11~9,11,2,8,3,6,9,5,10,7,5,7,3,8,4,2,10,4,5,2,2,2,8,7,10,3,8,7,3,5,8,3,9,4,11,6,11,6,9,5,8~3,6,2,5,8,6,3,8,7,10,7,5,9,4,10,11,8,11,3,8,4,6,2,2,2,9,5,4,5,7,5,9,7,11,7,3,9,8,6,4,5,3,8,9,5,10,3,2,6';

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
                $slotEvent['slotBet'] = $slotEvent['c'];
                $slotEvent['slotLines'] = 20;
                if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
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
                    if( $slotEvent['slotEvent'] == 'doSpin' && $slotSettings->GetBalance() < ($lines * $betline)  && $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') < 0) 
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
                if($slotEvent['slotEvent'] == 'freespin' || $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') >= 0){
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
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
                $bonusMpl = 1;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $str_initReel = '';
                $str_mo = '';
                $str_mo_t = '';
                $reels = [];
                $fsmore = 0;
                $str_trail = '';
                $str_stf = '';
                $rs_more = 0;
                $rs_p = -1;
                $rs_c = 0;
                $rs_m = 0;
                $rs_t = 0;
                $pw = 0;
                $arr_g = null;
                if($slotEvent['slotEvent'] == 'freespin' || $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') >= 0){
                    $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $str_initReel = $stack['is'];
                    $str_mo = $stack['mo'];
                    $str_mo_t = $stack['mo_t'];
                    $str_trail = $stack['trail'];
                    $str_stf = $stack['stf'];
                    if($stack['g'] != ''){
                        $arr_g = $stack['g'];
                    }
                    $pw = $stack['pw'];
                    $rs_more = $stack['rs_more'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $strWinLine = $stack['win_line'];
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $lastReel = explode(',', $stack[0]['reel']);
                    $currentReelSet = $stack[0]['reel_set'];
                    $str_initReel = $stack[0]['is'];
                    $str_mo = $stack[0]['mo'];
                    $str_mo_t = $stack[0]['mo_t'];
                    $str_trail = $stack[0]['trail'];
                    $str_stf = $stack[0]['stf'];
                    if($stack[0]['g'] != ''){
                        $arr_g = $stack[0]['g'];
                    }
                    $pw = $stack[0]['pw'];
                    $rs_more = $stack[0]['rs_more'];
                    $rs_p = $stack[0]['rs_p'];
                    $rs_c = $stack[0]['rs_c'];
                    $rs_m = $stack[0]['rs_m'];
                    $rs_t = $stack[0]['rs_t'];
                    $strWinLine = $stack[0]['win_line'];
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
                if($arr_g != null && isset($arr_g['hs'])){
                    $moneyWin = 0;
                    if(isset($arr_g['hs']['mo_tv'])){
                        $moneyWin = $arr_g['hs']['mo_tv'] * $betline;
                        if(isset($arr_g['hs']['mo_m']) && $arr_g['hs']['mo_m'] > 1){
                            $moneyWin = $moneyWin * $arr_g['hs']['mo_m'];
                        }
                        $totalWin = $totalWin + $moneyWin;
                        $arr_g['hs']['mo_tw'] = '' . $moneyWin;
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
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 6; $i++){
                    $reelA[$i] = mt_rand(5, 10);
                    $reelB[$i] = mt_rand(5, 10);
                }
                $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
                $strLastReel = implode(',', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $rs_p);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin') + $totalWin);
                $strOtherResponse = '';
                if($rs_p >= 0){
                    $strOtherResponse = $strOtherResponse . '&rs_p=' . $rs_p . '&rs_c=' . $rs_c . '&rs_m=' . $rs_m;
                    if($rs_p == 0){
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbWin', 0);
                    }else{
                        $strOtherResponse = $strOtherResponse . '&rs_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin');
                    }
                    $spinType = 's';
                    $isState = false;
                }
                if($rs_t > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t . '&rs_win=' . $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin');
                    $spinType = 'c';
                    if($slotEvent['slotEvent'] != 'freespin'){
                        $slotEvent['slotEvent'] = 'doRespin';
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                }
                if($str_initReel != ''){
                    $strOtherResponse = $strOtherResponse . '&is=' . $str_initReel;
                }
                if($rs_more > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_more=' . $rs_more;
                }
                if($str_stf != ''){
                    $strOtherResponse = $strOtherResponse . '&stf=' . $str_stf;
                }
                if($str_trail != ''){
                    $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                }
                if($pw > 0){
                    $strOtherResponse = $strOtherResponse . '&pw=' . (str_replace(',', '', $pw) / $original_bet * $betline);
                }
                if($str_mo != ''){
                    $strOtherResponse = $strOtherResponse . '&mo=' . $str_mo . '&mo_t=' . $str_mo_t;
                }
                if($arr_g != null){
                    $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($arr_g));
                }
                if($strWinLine != ''){
                    $strOtherResponse = $strOtherResponse . '&' . $strWinLine;
                }
                if($currentReelSet > -1){
                    $strOtherResponse = $strOtherResponse . '&reel_set='. $currentReelSet;
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse  .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance .'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&st=rect&c='.$betline.'&sw=5&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&w='.$totalWin.'&s=' . $strLastReel;
                // if( ($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) && $rs_p < 0) 
                // {
                //     //$slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                //     $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0); 
                //     $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                //     // $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                // }
                if( $slotEvent['slotEvent'] != 'freespin' && $rs_p == 0) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    // $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TumbWin'));
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"CurrentRespin":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"TumbAndFreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
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
    }
}

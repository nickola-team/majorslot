<?php 
namespace VanguardLTE\Games\EmptytheBankPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [11,7,4,7,9,3,11,4,10,10,3,4,9,3,3,3,4,11,11,3]);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', $lastEvent->serverResponse->BonusState);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $lastEvent->serverResponse->BuyFreeSpin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
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
                    $bet = '50.00';
                }
                $spinType = 's';
                if($stack != null){
                    $str_trail = $stack['trail'];
                    $str_rs = $stack['rs'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $pw = str_replace(',', '', $stack['pw']);
                    $currentReelSet = $stack['reel_set'];
                    $arr_g = null;
                    if($stack['g'] != ''){
                        $arr_g = $stack['g'];
                        foreach($arr_g as $key => $item){
                            if(isset($item['mo_tv'])){
                                $mo_tw = $arr_g[$key]['mo_tv'] * $bet;
                                $arr_g[$key]['mo_tw'] = $mo_tw;
                            }
                        }
                    }
                    if($str_trail != ''){
                        $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
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
                    if($pw > 0){
                        $strOtherResponse = $strOtherResponse . '&pw=' . $pw;
                    }
                    if($arr_g != null){
                        $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($arr_g));
                    }
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }else{
                    $strOtherResponse = $strOtherResponse . '&g={s1:{def_s:"13,13,13,13,13,13,13,13,13,13,13,13,13,13,13,12,12,12,12,12,13,13,12,12,12,12,12,13,13,12,12,12,12,12,13,13,12,12,12,12,12,13",def_sa:"12,12,12,12,12,12,12",def_sb:"12,12,12,12,12,12,12",reel_set:"12",sh:"6",st:"rect",sw:"7"}}';
                }
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
   
                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=11,7,4,7,9,3,11,4,10,10,3,4,9,3,3,3,4,11,11,3&balance='. $Balance .'&screenOrchInit={type:"mini_slots"}&cfgs=4549&ver=2&mo_s=16;17;18;19;20;21;14;15&index=1&balance_cash='. $Balance . $strOtherResponse .'&mo_v=20;20;20;20;20,40;20;20,40,60,80,100,120,140,160,200;20&def_sb=3,9,6,6,3&reel_set_size=13&def_sa=9,8,11,11,6&reel_set='. $currentReelSet .'&balance_bonus=0.00&na=s&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"3964025",max_rnd_win:"10000",max_rnd_win_a:"8000"}}&wl_i=tbm~10000;tbm_a~8000&bl=0&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&sa=9,8,11,11,6&sb=3,9,6,6,3&reel_set10=5,5,2,2,2,2,2,2,5,5,5,2,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,2,2,2,2,5,5,5,5,5,2,5,5,5,2,2,5,2,2,5~2,2,5,5,5,5,5,2,5,5,2,5,2,2,2,5,2,5,5,2,5,5~5,5,5,5,2,2,2,5,2~3,7,10,11,6,4,5,5,11,2,5,9,9,8,5,10,8,11~8,4,10,10,8,5,10,5,9,8,9,6,3,5,11,2,7,5&sc='. implode(',', $slotSettings->Bet) .'&defc=100.00&reel_set11=9,10,11,10,7,5,3,8,4,8,5,2,6,5,9,11~5,10,5,5,8,7,9,5,10,6,2,10,3,8,4,9,8,11~5,5,5,2,5,5,5,5,5,5,2,5,5,5,5,5,2,5,5,2,5,5,5,5,2,2,5,5,2,2,2,2,5,5,5,5,5,2,2,5,2,2,2,5,2,5,5~5,5,2,5,5,5,5,2,5,2,2,2,2,5,5,2,5,5,2~5,5,5,5,5,5,5,5,2,5,5,2,2,2,2,2,5,2,2,5,5,5&reel_set12=12,12,12,12,12,14,12,12,12,22,12,12,12,12,12,12,12,12,12,12,12,14,12~12,12,12,12,12,14,12,12,12,12,12,12,12,15,12,12,12,12,12,12,14,12,16,12,12,12,12,12,12,17,12,12,12,12,12,12,18,12,12,12,12,12,12,19,12,12,12,12,12,12,20,12,12,12,12,12,12,21,12,12,12,12,12,12,22,12,12,12,12,12,12~12,12,12,12,12,14,12,12,12,12,12,12,12,15,12,12,12,12,12,12,14,12,16,12,12,12,12,12,12,17,12,12,12,12,12,12,18,12,12,12,12,12,12,19,12,12,12,12,12,12,20,12,12,12,12,12,12,21,12,12,12,12,12,12,22,12,12,12,12,12,12~12,12,12,12,12,14,12,12,12,12,12,12,12,15,12,12,12,12,12,12,14,12,16,12,12,12,12,12,12,17,12,12,12,12,12,12,18,12,12,12,12,12,12,19,12,12,12,12,12,12,20,12,12,12,12,12,12,21,12,12,12,12,12,12,22,12,12,12,12,12,12~12,12,12,12,12,14,12,12,12,12,12,12,12,15,12,12,12,12,12,12,14,12,16,12,12,12,12,12,12,17,12,12,12,12,12,12,18,12,12,12,12,12,12,19,12,12,12,12,12,12,20,12,12,12,12,12,12,21,12,12,12,12,12,12,22,12,12,12,12,12,12~12,12,12,12,12,14,12,12,12,12,12,12,12,15,12,12,12,12,12,12,14,12,16,12,12,12,12,12,12,17,12,12,12,12,12,12,18,12,12,12,12,12,12,19,12,12,12,12,12,12,20,12,12,12,12,12,12,21,12,12,12,12,12,12,22,12,12,12,12,12,12~12,12,12,12,12,14,12,12,12,22,12,12,12,12,12,12,12,12,12,12,12,14,12&purInit_e=1&sh=4&wilds=2~500,200,50,5,0~1,1,1,1,1&bonuses=0&fsbonus=&st=rect&c='.$bet.'&sw=5&sver=5&bls=20,25&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;400,100,50,5,0;200,100,40,0,0;100,80,40,0,0;100,60,20,0,0;80,60,20,0,0;60,20,10,0,0;60,20,10,0,0;40,10,5,0,0;40,10,5,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0&l=20&total_bet_max='.$slotSettings->game->rezerv.'&reel_set0=9,1,11,10,4,5,5,7,8,8,9,3,10,3,8,8,11,10,6,11,7,3,4,3,3,3,10,7,2,9,4,10,4,10,8,11,9,6,6,9,11,11,9,5,8,10,5,6,9,3,2~3,10,11,8,11,6,6,2,7,8,10,11,3,5,3,9,10,2,3,5,3,3,3,8,9,7,10,3,11,5,4,9,8,10,4,7,6,4,9,11,5,11,9,9,8~10,5,11,3,4,8,10,6,4,6,3,3,3,3,11,1,9,8,9,5,7,2,10,7,11~8,4,11,9,11,9,8,5,3,10,3,3,3,10,8,11,9,6,4,7,6,10,3,7,2~6,8,7,3,9,6,10,7,1,10,11,6,5,9,8,11,5,8,3,3,3,10,11,4,1,6,2,10,8,9,11,10,9,3,8,7,9,11,8,11,5,4&s='.$lastReelStr.'&reel_set2=9,1,11,10,8,10,8,10,11,8,11,1,1,9,10,1~4,4,4,5,5,5,5,3,7,7,7,6,6,6,6,3,3,3,3,7,3,4~1,8,10,9,8,11,10,10,8,11,9,11,9,1,1,8,11,1~4,4,4,3,5,5,5,4,7,7,7,3,6,6,6,5,3,3,3,6,3,7~11,10,11,1,1,8,10,8,9,1,9&reel_set1=11,11,4,4,11,10,9,8,10,4,3,3,3,3,6,10,11,8,6,10,10,9,8,9,4,10,10,10,8,9,3,6,3,9,3,8,11,5,5,7,7,7,7,2,5,8,7,9,9,10,10,8,8,9,5,5,5,2,9,5,11,6,10,10,7,7,5,3,7,4,4,4,1,5,8,1,5,11,10,5,8,10,11,11,6,6,6,4,9,4,9,9,10,11,4,5,8,6,9,7~2,9,6,2,11,11,11,11,10,7,3,10,8,11,4,4,4,4,5,10,5,11,4,6,11,5,5,5,10,6,6,9,9,3,9,7,7,7,9,8,9,8,10,10,6,9,9,9,5,5,11,11,5,4,9,3,3,3,3,8,11,9,7,10,8,6,6,6,4,4,6,11,7,7,8,11~4,11,1,6,7,8,3,10,7,6,8,8,8,5,9,10,8,3,1,8,4,10,8,10,7,3,3,3,10,10,2,9,11,8,10,8,10,10,2,9,10,10,10,10,4,7,6,6,10,7,4,6,5,6,11,6,6,6,9,4,11,11,6,9,1,7,10,7,2,3,9,9,9,11,10,4,4,8,3,11,10,10,5,4,10,7,7,7,7,5,8,11,3,11,10,9,5,6,9,8,10~5,2,2,10,3,8,11,7,7,7,10,6,10,4,5,3,9,9,6,6,6,4,8,8,7,9,6,10,2,10,10,10,3,10,10,8,3,4,8,8,8,8,8,6,9,8,10,3,11,6,10,3,3,3,3,9,7,5,6,3,9,7,10,4,4,4,6,4,11,3,6,10,7,11,11,11,11,9,8,7,8,11,8,9,4,9,9,9,11,10,11,8,11,4,11,9,10,4~5,6,1,8,10,11,4,8,10,11,10,9,5,10,6,7,8,8,11,1,3,3,3,11,6,5,3,9,4,4,8,1,10,8,10,9,1,6,9,5,3,8,7,4,5,5,5,5,11,7,9,9,6,6,4,6,8,2,11,10,8,9,2,9,8,6,11,8,4,4,4,9,9,10,3,10,6,3,8,11,8,8,9,11,8,7,11,5,10,9,4,9,7,7,7,4,11,8,5,6,2,5,10,5,11,6,1,5,8,8,7,7,11,11,9,4,6,6,6,9,11,8,1,9,11,8,4,8,5,8,11,10,9,8,7,7,10,8,6,10,10,4&reel_set4=2,3,3,3,3,3,2,2,2,2,3,3~3,2,2,3,3,3,3,3,3,3,3,3,2,3,3,3,2,3,2,2,2,3,2,3,3,2,3,2,2,2~2,3,3,3,2,2,3,2,3,3,3,3,3,3,3,3,3,3,2,2,3,2,3,2,3,2,2,3,2,2,2,2,3,3,3,3,3,3,3,3,3,3,2,3,2,3,3~10,9,3,8,2,5,10,4,6,9,7,11,8,3,3,11~10,3,7,2,4,3,5,8,3,11,10,8,8,9,6,9,10,3&purInit=[{type:"fsbl",bet:1600,bet_level:0}]&reel_set3=8,7,10,10,9,6,8,3,5,11,4,9,2,3,3,11~3,3,3,3,3,2,2,2,2,3,2,3,3~2,2,3,3,2,2,3,3,3,3,3,3,2,3,3,3,3,2,3,2,2,2,2,3,2,3,3,3,2,2,3,3~3,2,2,2,3,3,2,3,3,3,2,3,3,3,3,3,3,3,3,2,3,3,3,3,3,2,3,2,2,2,3,3,3,3,3,3,2,3,2,3,2,2,2,3~8,2,6,3,9,3,7,3,8,10,8,10,4,11,3,5,10,9&reel_set6=3,6,4,4,10,2,11,4,8,9,5,8,7,10,9,11,4,11~4,4,4,4,2,4,4,4,4,4,4,2,2,2,2,2,2,4,4,4,2,4,4,2,4~4,4,4,4,4,2,2,2,2,4,2~4,4,4,4,2,4,2,2,2,4,4,4,2~2,9,4,8,10,6,5,4,7,9,8,10,8,4,3,4,10,11&reel_set5=3,11,6,10,11,8,9,5,7,9,10,2,3,3,8,11,3,4~2,3,4,10,6,3,8,8,7,11,9,3,10,9,5~2,3,3,3,3,3,2,3,2,2,2,2,3,3,3,3~3,3,2,3,2,3,3,2,3,2,3,3,3,3,3,3,3,3,3,3,3,3,3,2,3,2,2,2,2,3,3,3,3,3,2,2,2,2,2,2,3,2~3,3,3,2,2,3,3,3,2,2,3,3,3,3,3,3,3,3,2,3,3,2,2,3,2,2,2,2,2,3,3,2,3,3,3,3,3,3,3,3,2&reel_set8=3,5,11,9,9,8,6,10,10,4,7,4,8,11,2,4,4,11~2,8,10,8,10,11,7,10,6,8,4,3,9,4,4,5,9~4,2,4,4,4,4,2,4,4,4,4,4,4,2,4,4,4,4,4,2,2,2,2,2,2,2,4,4,4,2,4,2,4,4,4~2,2,4,4,4,4,4,4,4,4,2,2,2,4,2,2,2,4,2,4,4,4,2,4,4~2,4,4,4,4,2,4,2,2,2,4,2,4,4&reel_set7=4,4,2,4,4,2,4,4,4,2,4,4,4,4,4,4,2,2,2,2,2,4,2,4,4,2,4~4,4,4,2,4,4,4,4,4,4,4,4,4,4,2,2,2,2,2,2,4,2,4,4,2,4,2,2~4,4,4,4,2,2,2,4,2,4~7,6,11,9,5,4,2,4,10,8,10,3,4,8,11,9~8,10,3,4,8,6,10,2,10,8,7,4,9,4,11,5,9,4&reel_set9=6,4,10,2,5,3,7,11,5,9,11,5,9,11,5,8,8,10~5,2,5,5,5,5,5,5,5,5,5,5,2,2,5,2,2,5,5,2,5,2,2,2,5,5,2,5,2,5,5,2,5,5,5~5,5,5,2,5,5,5,5,5,5,5,5,5,5,5,5,5,2,5,5,5,5,2,2,2,2,5,5,2,2,2,2,2,5,5,2,2,2,5,2,2,5,2,5,2,5,5~5,5,5,5,5,2,5,2,5,2,2,2,5,2,5,5,2~10,6,5,4,5,9,9,5,10,8,8,11,3,2,7&total_bet_min=10.00';
            }
            else if( $slotEvent['slotEvent'] == 'doCollect' || $slotEvent['slotEvent'] == 'doCollectBonus') 
            {
                $Balance = $slotSettings->GetBalance();
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);    
                $response = 'balance=' . $Balance . '&index=' . $slotEvent['index'] . '&balance_cash=' . $Balance . '&balance_bonus=0.00&na=s&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) . '&na=s&sver=5&counter=' . ((int)$slotEvent['counter'] + 1);
                
                //------------ ReplayLog ---------------                
                $lastEvent = $slotSettings->GetHistory();
                if($lastEvent  != 'NULL'){
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
                $linesId = [];
                $linesId[0] = [1, 1, 1, 1, 1];
                $linesId[1] = [4, 4, 4, 4, 4];
                $linesId[2] = [2, 2, 2, 2, 2];
                $linesId[3] = [3, 3, 3, 3, 3];
                $linesId[4] = [1, 2, 3, 2, 1];
                $linesId[5] = [4, 3, 2, 3, 4]; 
                $linesId[6] = [3, 2, 1, 2, 3]; 
                $linesId[7] = [2, 3, 4, 3, 2];
                $linesId[8] = [1, 2, 1, 2, 1];
                $linesId[9] = [4, 3, 4, 3, 4];
                $linesId[10] = [2, 1, 2, 1, 2];
                $linesId[11] = [3, 4, 3, 4, 3];
                $linesId[12] = [2, 3, 2, 3, 2];
                $linesId[13] = [3, 2, 3, 2, 3];
                $linesId[14] = [1, 2, 2, 2, 1];
                $linesId[15] = [4, 3, 3, 3, 4];
                $linesId[16] = [2, 1, 1, 1, 2];
                $linesId[17] = [3, 4, 4, 4, 3];
                $linesId[18] = [2, 3, 3, 3, 2];
                $linesId[19] = [3, 2, 2, 2, 3];                
                
                $pur = -1;
                if(isset($slotEvent['pur'])){
                    $pur = $slotEvent['pur'];
                }
                $slotEvent['slotBet'] = $slotEvent['c'];
                $bl = $slotEvent['bl'];
                $slotEvent['slotLines'] = 20;

                if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 ) 
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
                    if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames')+1 < $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotEvent['slotEvent'] == 'freespin' ) 
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
                
                $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $betline * $lines, $lines, $bl);
                $winType = $_spinSettings[0];
                $_winAvaliableMoney = $_spinSettings[1];

                // $winType = 'bonus';

                $allBet = $betline * $lines;
                if($pur >= 0 && $slotEvent['slotEvent'] != 'freespin'){
                    $allBet = $allBet * 80;
                }else if($bl > 0){
                    $allBet = $betline * 25;
                }
                $freeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'freespin'){
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') + 1);
                    $bonusMpl = $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl');
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusState', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.1f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '628' . substr($roundstr, 3, 8). '023';
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $leftFreeGames = 0;
                }
                
                $wild = '2';
                $scatter = '1';
                $Balance = $slotSettings->GetBalance();
                
                $totalWin = 0;
                $lineWins = [];
                $lineWinNum = [];
                $strWinLine = '';
                $winLineCount = 0;
                $arr_g = null;
                $str_trail = '';
                $str_rs = '';
                $rs_p = -1;
                $rs_c = 0;
                $rs_m = 0;
                $rs_t = 0;
                $pw = 0;
                $currentReelSet = 0;
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $str_trail = $stack['trail'];
                    $str_rs = $stack['rs'];
                    $rs_p = $stack['rs_p'];
                    $rs_c = $stack['rs_c'];
                    $rs_m = $stack['rs_m'];
                    $rs_t = $stack['rs_t'];
                    $pw = str_replace(',', '', $stack['pw']);
                    $currentReelSet = $stack['reel_set'];
                    if($stack['g'] != ''){
                        $arr_g = $stack['g'];
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
                    $str_trail = $stack[0]['trail'];
                    $str_rs = $stack[0]['rs'];
                    $rs_p = $stack[0]['rs_p'];
                    $rs_c = $stack[0]['rs_c'];
                    $rs_m = $stack[0]['rs_m'];
                    $rs_t = $stack[0]['rs_t'];
                    $pw = $stack[0]['pw'];
                    $currentReelSet = $stack[0]['reel_set'];
                    if($stack[0]['g'] != ''){
                        $arr_g = $stack[0]['g'];
                    }
                }
                $reels = [];
                $scatterCount = 0;
                if($slotEvent['slotEvent'] == 'freespin'){
                    foreach($arr_g as $key => $item){
                        if(isset($item['mo_tv'])){
                            $mo_tw = $arr_g[$key]['mo_tv'] * $betline;
                            $arr_g[$key]['mo_tw'] = $mo_tw;
                            if($rs_t > 0){
                                $totalWin += $mo_tw;
                            }
                        }
                    }
                }else{
                    for($i = 0; $i < 5; $i++){
                        $reels[$i] = [];
                        for($j = 0; $j < 4; $j++){
                            $reels[$i][$j] = $lastReel[$j * 5 + $i];
                            if($lastReel[$j * 5 + $i] == 1){
                                $scatterCount++;   
                            }
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
                        for($j = 1; $j < 5; $j++){
                            $ele = $reels[$j][$linesId[$k][$j] - 1];
                            if($firstEle == $wild){
                                $firstEle = $ele;
                                $lineWinNum[$k] = $lineWinNum[$k] + 1;
                            }else if($ele == $firstEle || $ele == $wild){
                                $lineWinNum[$k] = $lineWinNum[$k] + 1;
                                if($j == 4){
                                    $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                    if($lineWins[$k] > 0){
                                        $totalWin += $lineWins[$k];
                                        $_obf_winCount++;
                                        $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                        for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                            $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                        }
                                    }
                                }
                            }else{
                                if($slotSettings->Paytable[$firstEle][$lineWinNum[$k]] > 0){
                                    $lineWins[$k] = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                    if($lineWins[$k] > 0){
                                        $totalWin += $lineWins[$k];
                                        $_obf_winCount++;
                                        $strWinLine = $strWinLine . '&l'. ($_obf_winCount - 1).'='.$k.'~'.$lineWins[$k];
                                        for($kk = 0; $kk < $lineWinNum[$k]; $kk++){
                                            $strWinLine = $strWinLine . '~' . (($linesId[$k][$kk] - 1) * 5 + $kk);
                                        }   
                                    }
    
                                }else{
                                    $lineWinNum[$k] = 0;
                                }
                                break;
                            }
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
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $rs_m);
                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $rs_c);
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
                $isState = true;
                $isEnd = true;

                if( $slotEvent['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $spinType = 's';
                    $isEnd = false;
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');                    
                    if($rs_t > 0) 
                    {
                        $spinType = 'c';
                        $isEnd = true;
                        $isState = true;
                    }
                    else
                    {
                        $isState = false;
                        $spinType = 's';
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                        $strOtherResponse = $strOtherResponse . '&puri=0';
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                    if($scatterCount >= 3 ){
                        $isState = false;
                        $spinType = 's';
                        if($pur >= 0){
                            $strOtherResponse = $strOtherResponse . '&purtr=1&puri=0';
                        }
                    }
                }
                if($str_trail != ''){
                    $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                }
                if($str_rs != ''){
                    $strOtherResponse = $strOtherResponse . '&rs=' . $str_rs;
                }
                if($rs_p >= 0){
                    $strOtherResponse = $strOtherResponse . '&sn_mult=1&sn_i=emptyTheBank&sn_pd=0&rs_p=' . $rs_p;
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
                if($pw > 0){
                    $strOtherResponse = $strOtherResponse . '&pw=' . ($pw / 0.1 * $betline);
                }
                if($arr_g != null){
                    $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($arr_g));
                }
                
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse .'&ls=0&balance='.$Balance. '&reel_set='. $currentReelSet .'&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .$strWinLine .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&bl='. $bl .'&sh=4&c='.$betline.'&sw=5&sver=5&counter='. ((int)$slotEvent['counter'] + 1) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&l=20&st=rect&s='.$strLastReel .'&w='.$totalWin;
                
                if($slotEvent['slotEvent'] == 'freespin' && $rs_t > 0) 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"BonusState":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusState') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"BuyFreeSpin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                if($bl > 0){
                    $allBet = $betline * 25;
                }
                if($slotEvent['slotEvent'] == 'freespin' && $isState == true && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $allBet = $allBet * 80;
                }
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if( $scatterCount >= 3 && $slotEvent['slotEvent'] != 'freespin') 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                }

            }
            if($slotEvent['action'] == 'doSpin' || $slotEvent['action'] == 'doCollect' || $slotEvent['action'] == 'doCollectBonus'){
                $this->saveGameLog($slotEvent, $response, $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'), $slotSettings);
            }
            try{
                $slotSettings->SaveGameData();
                \DB::commit();
            }catch (\Exception $e) {
                $slotSettings->InternalError('BountyGoldCommit : ' . $e);
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

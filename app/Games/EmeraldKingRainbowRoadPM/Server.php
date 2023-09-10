<?php 
namespace VanguardLTE\Games\EmeraldKingRainbowRoadPM
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
                $slotSettings->SetGameData($slotSettings->slotId . 'RespinCount', -1);                    
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusType', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Ind', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'Level', -1);
                $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 20);
                $slotSettings->setGameData($slotSettings->slotId . 'LastReel', [3,4,5,6,7,3,4,5,6,7,3,4,5,6,7]);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []); //FreeStacks
                $slotSettings->SetGameData($slotSettings->slotId . 'Wins', [0,0,0]);
                $slotSettings->SetGameData($slotSettings->slotId . 'Status', [0,0,0]);
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
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinCount', $lastEvent->serverResponse->RespinCount);                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusType', $lastEvent->serverResponse->BonusType);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Ind', $lastEvent->serverResponse->Ind);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Level', $lastEvent->serverResponse->Level);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', $lastEvent->serverResponse->Bgt);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $lastEvent->serverResponse->TotalSpinCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lastEvent->serverResponse->lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', $lastEvent->serverResponse->BonusMpl);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastEvent->serverResponse->LastReel);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $lastEvent->serverResponse->RoundID);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Wins', json_decode(json_encode($lastEvent->serverResponse->Wins), true));
                    $slotSettings->SetGameData($slotSettings->slotId . 'Status', json_decode(json_encode($lastEvent->serverResponse->Status), true));
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
                    $bet = '100.00';
                }
                $spinType = 's';
                $lastReelStr = implode(',', $slotSettings->GetGameData($slotSettings->slotId . 'LastReel'));
                if(isset($stack)){
                    if($stack['reel_set'] >= 0){
                        $currentReelSet = $stack['reel_set'];
                    }
                    $str_trail = $stack['trail'];
                    $ls = $stack['ls'];
                    $str_accm = $stack['accm'];
                    $str_accv = $stack['accv'];
                    $rs_p = $stack['rs_p'];
                    $rs_t = $stack['rs_t'];
                    $bmw = $stack['bmw'];
                    $bw = $stack['bw'];
                    $level = $stack['level'];
                    $wp = $stack['wp'];
                    $lifes = $stack['lifes'];
                    $end = $stack['end'];
                    $g = null;
                    if($stack['g'] != ''){
                        $g = $stack['g'];
                    }

                    $wins = $slotSettings->GetGameData($slotSettings->slotId . 'Wins');
                    $status = $slotSettings->GetGameData($slotSettings->slotId . 'Status');
                    $wins_mask = [];
                    for($k = 0; $k < count($wins); $k++){
                        if($wins[$k] == 0){
                            $wins_mask[$k] = 'h';
                        }else{
                            $wins_mask[$k] = 'ma';
                        }
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BonusType') == 1 && $g != null){
                        $g['eb']['status'] = implode(',', $status);
                        $g['eb']['wins'] = implode(',', $wins);
                        $g['eb']['wins_mask'] = implode(',', $wins_mask);
                        $g['eb']['ch'] = 'ind~' . $slotSettings->GetGameData($slotSettings->slotId . 'Ind');
                        $g['eb']['wi'] = '' . $slotSettings->GetGameData($slotSettings->slotId . 'Ind');
                    }
                    if($g != null){
                        if($rs_p > 0 || $rs_t > 0){
                            foreach($g as $key => $item){
                                if(isset($item['l0'])){
                                    $arr_wins = explode('~', $item['l0']);
                                    if(count($arr_wins) > 0){
                                        $sub_win = str_replace(',', '', $arr_wins[1]) / $original_bet * $bet;
                                        $arr_wins[1] = $sub_win;
                                        $g[$key]['l0'] = implode('~', $arr_wins);
                                    }
                                }
                            }
                        }
                        if(!isset($g['mss'])){
                            $g['mss'] = [
                                    'screenOrchInit' => '{type:"mini_slots",layout_h:1,layout_w:5}'
                                ];
                        }
                        for($i = 1; $i <= 5; $i++){
                            if(!isset($g['ms0' . $i])){
                                $g['ms0' . $i] = [
                                    'def_s' => '14,14,14',
                                    'sh' => '1',
                                    'st' => 'rect',
                                    'sw' => '3'
                                ];
                            }
                        }
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'Bgt') == 50 && $level >= 0){
                        if($bmw > 0){
                            $bmw = $bmw / $original_bet * $bet;                        
                        }
                        if($end < 1){
                            if($bmw > 0){
                                $strOtherResponse = $strOtherResponse . '&rw=' . $bmw;
                            }else{
                                $strOtherResponse = $strOtherResponse . '&rw=0';
                            }                    
                        }else{
                            $strOtherResponse = $strOtherResponse . '&rw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                        }
                        if($lifes >= 0){
                            $strOtherResponse = $strOtherResponse . '&lifes=' . $lifes;
                        }
                        if($bmw >= 0){
                            $strOtherResponse = $strOtherResponse . '&bmw=' . $bmw;
                        }
                        if($slotSettings->GetGameData($slotSettings->slotId . 'BonusType') == 1){
                            $strOtherResponse = $strOtherResponse . '&bgid=0';
                        }else{
                            $strOtherResponse = $strOtherResponse . '&bgid=1';
                        }
                        if($end == 1){
                            $spinType = 's';
                        }else{
                            $spinType = 'b';
                        }
                        $strOtherResponse = $strOtherResponse . '&end=' . $end . '&bgt='. $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') .'&coef='. ($bet * 20) . '&wp='. $wp .'&level=' . $level;
                        $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($g));
                        
                    }else{
                        if($rs_p >= 0){
                            $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($g));
                        }else{
                            $strOtherResponse = $strOtherResponse . '&g={ms01:{def_s:"14,14,14",sh:"1",st:"rect",sw:"3"},ms02:{def_s:"14,14,14",sh:"1",st:"rect",sw:"3"},ms03:{def_s:"14,14,14",sh:"1",st:"rect",sw:"3"},ms04:{def_s:"14,14,14",sh:"1",st:"rect",sw:"3"},ms05:{def_s:"14,14,14",sh:"1",st:"rect",sw:"3"},mss:{screenOrchInit:"{type:\"mini_slots\",layout_h:1,layout_w:5}"}}';
                        }
                    }
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                    
                    if($str_trail != ''){
                        $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                    }
                    if($str_accv != ''){
                        $strOtherResponse = $strOtherResponse . '&acci=0&accv=' . $str_accv . '&accm=' . $str_accm;
                    }
                    if($rs_p >= 0){
                        $strOtherResponse = $strOtherResponse . '&rs=mc&rs_p='. $rs_p .'&rs_c='. ($rs_p + 1) .'&rs_m=' . ($rs_p + 1);
                    }
                    if($rs_t > 0){
                        $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t;
                    }
                    if($bw > 0){
                        $strOtherResponse = $strOtherResponse . '&bw=' . $bw;
                    }
                    if($ls >= 0){
                        $strOtherResponse = $strOtherResponse . '&ls=' . $ls;
                    }
                }else{
                    $strOtherResponse = $strOtherResponse . '&accm=cp~tp~lvl~sc&acci=0&accv=0~16~0~0&g={ms01:{def_s:"14,14,14",sh:"1",st:"rect",sw:"3"},ms02:{def_s:"14,14,14",sh:"1",st:"rect",sw:"3"},ms03:{def_s:"14,14,14",sh:"1",st:"rect",sw:"3"},ms04:{def_s:"14,14,14",sh:"1",st:"rect",sw:"3"},ms05:{def_s:"14,14,14",sh:"1",st:"rect",sw:"3"},mss:{screenOrchInit:"{type:\"mini_slots\",layout_h:1,layout_w:5}"}}';
                }
                // if($currentReelSet >= 0){
                //     $strOtherResponse = $strOtherResponse . '&reel_set='. $currentReelSet;
                // }
                
                $Balance = $slotSettings->GetBalance();
                $response = 'def_s=3,4,5,6,7,3,4,5,6,7,3,4,5,6,7&balance='. $Balance . $strOtherResponse .'&cfgs=3888&ver=2&index=1&balance_cash='. $Balance .'&def_sb=5,7,7,8,2&reel_set_size=12&def_sa=8,3,4,3,3&reel_set='. $currentReelSet .'&balance_bonus=0.00&na='. $spinType.'&scatters=1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1&gmb=0,0,0&rt=d&gameInfo={props:{max_rnd_sim:"1",max_rnd_hr:"402928",max_rnd_win:"20000"}}&wl_i=tbm~20000&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa=8,3,4,3,3&sb=5,7,7,8,2&reel_set10=7,5,3,3,3,10,3,10,3,5,3,10,5,3,10,3,5,3,10,3~8,6,9,3,3,3,3,4,9,3,9,3,9,3,9,3,9~3,3,3,3,6,4,5,7,9,8,5,8,4,9,8,9,4,5,9,8,5,4,8,4,8,4,9,8,9,4,8,4~3,3,3,3,7,5,6,8,4,10,6,7,10,5,6,10,6,5,6~3,3,3,5,7,6,8,3,4,6,8&sc='. implode(',', $slotSettings->Bet) .'&defc=100.00&reel_set11=9,3,8,4,3,3,3,6,3,4,3,4,3,4,3,6,3,4,3,4,3,4,8~4,3,3,3,3,6,8,5,7,9,3,7,3,7,9,3,6,3,9~7,10,5,3,3,3,3,5~3,3,3,7,5,6,3,8,10,4,7,5,10,5,7,5,4,6,4~3,3,3,7,5,8,3,4,6,4,6,8,5,8,5,4,6,8,6,4,6,8&sh=3&wilds=2~0,0,0,0,0~1,1,1,1,1&bonuses=0&fsbonus=&st=rect&c='.$bet.'&sw=5&sver=5&counter=2&paytable=0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;500,300,100,0,0;300,100,20,0,0;300,100,20,0,0;200,20,10,0,0;200,20,10,0,0;200,20,10,0,0;0,0,0,0,0;0,0,0,0,0;300,0,0;200,0,0;100,0,0;0,0,0&l=20&reel_set0=9,4,3,3,3,7,6,3,10,5,8,2,7,6,3,7,2,5,3,2,7,6,7,3,7,4,5,7,3,7,3,6,7,3,4,7,3,2,4,8,5,4,3,4,2~8,4,6,7,3,9,3,3,3,2,5,10,2,2,2,3,6,10,3,2,3,10,5,10,3,10,2,3,6,3,4,3,6,2,5,10,3,9,7,4,5,3,9,6,9,10,3,10,5,2,10,3,10,7,2,3,10,6,5,3,10,6,10,3,10,3,5,10,2,10,4,2,10,3,9~3,3,3,9,10,5,4,3,7,6,4,7,10,9,6,7,6,9,4,9,7,6,9,6,9,10,9,4,10,7,9,6,9,4,10,4,7,6,10,6,7,4,6,7~10,3,3,3,3,9,8,4,5,2,6,7,3,7,6,3,7,2,8,7,6,8,7,3,5,3,4,3,9,6,8,3,8,6,8,9,8,7,3,9,6,3,7,9,4,5,9,5,4,7,2,8,7,8,3~10,3,3,3,5,7,4,9,3,8,6,2,3,4,3,8,3,7,8,9,7,8,3,4,9,3,4,8,7,8,3,4,2,3,8,4,3&s='.$lastReelStr.'&accInit=[{id:0,mask:"cp;tp;lvl;sc;cl"}]&reel_set2=10,7,3,3,3,4,2,6,3,5,8,9,3,2,7,4,5,3,9,4,3,9,8,3,7,9,7,8,5,3,4,3,6,7,3,7,8,4,8,7,8,5,3,7,6,4,3,8,4,6,7,4,9,8,7,8,7,8,3,7,3,4,7,3,6,7,8,4,6,8,6,8,3,4,7,4,3,4,6,8,3,8,9,3,8,7,4,8,3,4,8,3,8,3,4,3,4,3,2,7,8,6,4,5,4,8,9,3,6,7,5,3,7,8,4,8,3~3,3,3,5,4,9,6,10,7,3,6,7,5,6,7,6,7,4,6,5,10,7,6,5,6,10,4,5,7,4,10,6,7,10,4,10,6,7,9,7,10,5,7,10,7,10,9,10,4,6,10,9,5,6,5,10,5,10,9,6,4,6,5,10,7,6,5,6,5,10,4,6,7,6,4,6,10,6~3,3,3,6,2,2,2,10,3,9,2,8,7,5,4,8,6,8,2,5,2,8,2,9,6,4,5,2,9,4,5,8,4,8,9,2,10,8,9,2,8,2,8,6,4,6,8,5,10,9,2,9,6,7,5,9,8,5,2,5,9,8,9,5,9,2,6,9,8,5,8,6,9,2,10,9,8,9,8,2,4,5,9,8,9,10,5,2,10,8,9,8,10,9,6,9,5,4,9,8,9,2,6,8,5,2,8,2,9,5,2,9,5,2,9,8,2,8,2,4,8,4,9,8,4,2,8,9,8,2,5,9,2~2,3,3,3,6,10,7,8,4,9,5,3,6,10,6,3,5,10,5,3,6,7,3,6,10,9,7,3,6,10,5,3,6,5,9,4,3,6,10,9,10,9,3,9,7,3,10,3,10,9,10,6,5,6,9,7,9,6,9,6,4,9,10,6,5,10,6,3~9,10,3,3,3,3,5,7,4,8,6,2,6,10,8,4,6,8,3,6,3,8,3,8,7,3,8,3,4,3,8,10,3,8,6,3,8,6,3,8,3,4,7,3,6,3,7,3,7,3,8,4,6,8,4,8,3,8,7,3,4,6,4,2,4,7,8,3,2,8,4,8,5,3,4,5,3,4,3,10,3&reel_set1=2,2,2,6,8,3,3,3,9,4,10,2,7,3,5,3,10,6,9,8,3,6,10,6,9,3,8,4,9,3,9,4,6,3,10,9,6,3,5,3,4,10,4,9,3,10,3,9,3,10,8,3,9,6,8,3,5,3,8,6,10,3,6,10,8,10,3,5,8,9,5,7,5,9,3,5,3,7,3,4,3,6,3,5,6,9,6,9,10,3,4,3,5,9,8,10,7,6,4,10,8,6,3,9~3,7,3,3,3,8,4,6,9,5,10,2,5~3,3,3,10,4,7,6,5,3,9,10,7,10,5,4,7,10,5,10,7,10,4,10,7,5,7,5,7,5,4,10,7,4,6,7,10,5,10,5,10,4,5,4,7,10,6,5,7,4,10,4,7,5,7,5,10,5,10,4,5,7,4,7,10,5,10,5,10,4,10,5,7~8,9,10,7,4,6,3,5,3,3,3,2,6,7,3,4,7,2,6,3,4,6,10,7,5,6,4,2,3,4,3,6,7,2,3,6,3,9,7,3,2,3,7,2,6,2,6,2,3,4,3,6,3,7,6,3,4,6,3,2,7,3,7,9,4,3,10,3,4,7,2,7,10,7,6,3~3,3,3,4,7,8,5,9,2,6,3,10,9,7,8,7,9,10,7,6,2,9,7,4,10,9,10,7,2,4,9,2,7,9,10,9,4,7,4,6,2,8,7,6,7,2,4,7,2,4,7,9,5,9,7,2,10,2,8,2,7,4,9,2,9,6,2,10,9,8,2,4,7,2,4,8,10,2,9,2,8,9,10,9,8,4,10,7,9,2,9,4,9,7,9,2,9,4,10,4,10,4,2,9,2,4,9,7,2,6,7,6,10,8,7,6,2&reel_set4=3,3,3,6,9,4,3,8,5,10,4,5,4,8,4,8,4,10,8,4,8,4,10,9,10,8,9,4,10,8,4,10,5,4,8,10,9,10,4,8,4,10,4,10,4,10,4,8,10,4,10,4,9,4,10,4,8,4,10,6,4,8,9,10,4,6,10~3,3,3,2,9,8,4,5,7,3,10,4,8,4,7,8,7,8,9,10,4,8,7,4,10,2,4,5,7,4,7,8,4,5,4,8,7,4,10~9,3,3,3,2,6,3,5,8,4,7,3,6,3,6,2,7,3,6,2,6,8~3,3,3,7,6,10,9,2,5,8,4,3,8,5,10,8,10,7,9,8,9,10,2,5,9,5,6,8,9,6,5~3,3,3,6,9,2,10,5,8,3,7,4,8,10,8,2,4,6,8,4,2,5,9,2,10,6,9,4,6,2,8,7,2,6,8,9,4,10,2,4,2,5,8,6,5,2,8,2,5,8,2,4,5,2,7,5,2,5,2,5,4,6,5,4,8,10&reel_set3=3,3,3,8,10,2,3,7,4,5,9,8,10,5,4,10,8,10,8,7,8,5,8,4,8,4,8,7,8,4,5,8,9,5,7,5,8,4,8,4,9,5,9,7,4,7,4,8,7,8,2,4,5,7,9~4,6,9,10,3,3,3,5,8,3,6,3,9,3,10,8,3,6,3,10,6,3,9,3,8,6,9,8,3,10,8,9,10,6,8,3,8,10,9,10,3,9,3,6,3,10,8,9,3,6,9,6,3,6,3~8,3,3,3,9,7,5,6,3,2,4,6,3,7,9,3,7,6,3,9,3,9,3,7,6,3,5,7,3,7,3,7,3,4,7,3,7,3,5,7,3,7,3,7,9,5,7,9,3,7,3,7,3,5,9,3,7,3,7,3,5~4,3,3,3,2,3,6,8,9,7,10,5,7,6,7,9,2,7,5,6,7,3,7,5,9,3,8,7,6,8,6,2,3,7,10,3,7,6,3,10,7,8,2,7,3,7,8,5,7,3,7,2,7,3,7,3,7,2,3,6,2,7,2,3,7,3,2,3,6,3,8,2,9,2,7,3,8,7,6,3,8,7,2,9,7,8,3,8,3,8,3,7,10,7,10,7,2,10,6~3,3,3,7,10,9,5,2,8,4,3,6,10,7,10,7,2,5,10,7,9,8,10,8,10,4,10,4,6,7,6,8,2,7,10,8,4,8,7,10,8,7,5,4,10,8,10,8,4,8,6,10,8,10,8,10,8,5,10,8,10,7,8,10,8,7,8,10,8,7,4,8,10,7,10,4,10,4,5,10,7,2,7,10,2,8,4,10,4&reel_set6=5,10,7,9,2,6,4,10,7,4,9,7,2,7,10,2,7,10,2,10,7,10,2,10,7,9,7,9,10,7,10,2,7,10,2,7,4,10,7,2,10,7,10,7~6,8,3,4,10,3,4,8,4,8,3,8,4,3,4,8,3,4,8,3,8,3,8,3,8,10,3,8,4,8,4,3,10,3,8,4,3,8,3,8,3,8,3,8,4,3,8,4,8,3,4,10,8,4,3,4,8,3,8,3,8,3,8,4,3,8,3,4,10,3,4,8,3,4,8,3,8,3,8,4,8,4,8,3,4,10,4,3~3,5,9,7,8,3,3,3,7,8,9,8,7,8,7,8,7,8,7,5,8,7,8,7,9,8,9,7,8,7,8,9,8,5,8,5,8,7,8,9,8,7,8,7,5,8,5,8,7,8,7,8,9,5,7,8,7,5,7,8,7,9,7,8,7,8,7,8,7,9,7,9~2,2,2,10,8,6,3,4,5,2,7,10,4,5,10,3,10,5,6,7,4,7,8,7,3,7,5,6,4,3,5,7,4,10,4,5,7,3,10,3,5,7,3,10,7,5,7,6,7,5,3,8,10,7,5,8,3,7,10,7,10,8,7,5,4,10,6,3,5,6,5,7,4,6,5,7,3,10,3,7,8,5,10,7,4,3,5~2,2,2,6,4,7,5,2,3,8,10,3,3,3,4,10,3,7&reel_set5=3,3,3,2,4,7,3,5,10,9,8,4,5,7,8,9,8,5,7,4,10,4,5~3,3,3,9,6,5,4,2,3,8,7,2,8,7,9,8,4,7,9,4,8,9,7,9,8,9,8,2,8,4,7,9,4,8,9,4,9,8,4,7,9,7,8,2,9,8,9,7,4,7,4,2,7,8,9,4,9,4,8,7,4,9,8,5,7,4,8,7,4,8,4,9,7,2,5,4,9,4,9,8,7,8,9,8,4~3,3,3,4,3,8,6,5,10,9,10,4,6,4,8,10,8,10,8,10,4,10,4,8,4,10,4,10,4,10,8,10,4,8,4,6,10,4,10,6,10,6,8,4,10,4,6,5,10,6,10,8,6,4,8,10,4,8,4,6,10,6,8,4,6,10,4,6,8,10,8,4,10,8,6,4,8,4,6,10,4~2,3,3,3,10,9,3,8,5,6,4,7,3,5,3,9,5,7,8,3,7~3,8,7,3,3,3,2,6,10,4,5,9,7,9,4,9,5,7,4,7,10,5,4,10,8,4,5,10,8,4,6,9,5,4,5,8,5,8,4,6,7,10,4,7,9,7,10,7,5,4,5,4,5,4,10,6,7,9,5,7,5&reel_set8=10,2,6,9,5,7,4,5,7,9,2,9,5,9,7,2,5,9,5,6,2,9,7,2,5,2,5,2,4,5,2,9,5,9,7,9,7,2,9,2,5,2,9,2,9,5,2,5,7,2,7,2,7,9,2,9,2,7,5,9,2,9,7,5,9,5,2,9~3,3,3,9,7,5,3,8,5,7,5,8,5,8,5,8,5,8,5,9,5~4,3,10,6,8,10,3,10,3,10,8,10,8,3,8,10,8,3,8~2,4,2,2,2,5,10,6,3,7,8,3,6,3,5,3,8,3,6,3,6,3,6,3,6,3,6,5,8,3,10,3,5,6,3,6,3,8,3,7,5,8,7,6,8,6,8,10,6,7,6,5,3,6,3,6,3,5,7,6,4,7,5,7,6,3,6,5,6,5,3,4,8,5,3,6,5,3,6,3,6,7,6,3,6,10,8,6,3,6,5,3,6,7,6,3,8,3,7~2,2,2,5,2,7,3,3,3,4,10,3,8,6,5,4,6,4,3,5,8,5,7,8,3,4,6,8,7,6,5,7,5,3,5,8,5,7,8,6,5,6,5,8,5,7,6,8,6,5,7,5,6,7,5,3,8,5,6,7,8,5,6,8,6,5,4,3,5,10,7,8,5,6,3,4&reel_set7=10,3,4,6,8,6~6,2,7,5,9,10,4,10,7,5,9,2,10,2,7,10,7,9,7,2,7,5,2,7,9,7,2,7,10,9,5,2,7,10,2,7,2,5,2,7,4,7,9,10,7,4,2,7,9,2,7,9,2,4,10,7,10,2,10,2,10,2,9,7,10,7,10,9~7,3,3,3,3,9,8,5,9~2,2,2,5,4,6,3,8,2,10,7,6,8,4,8,7,8,7,8,10,4,7,10,5,10,7,8,10,6,7,4,7,8,10,8,5~2,2,2,5,4,3,3,3,10,3,2,7,6,8,4,7,3,6,3,5,3,5,4,7,6,5,8,5,4,3,5,6,3,5,4,5,3,4,5,3,4,5,8,3,7,3,5,7,3,4,6,3,4,8,5,4,6,4,5,3,5,4,7,6,3,8,7,6,4&reel_set9=3,3,3,8,6,9,3,4,9,6,9,4,9,4,6,4,8,4,8,4,6,9,8~5,7,3,3,3,10,3,10,3,7,10,3,10,3,10,3,10,3,10,3,10,3~3,3,3,4,5,3,9,6,7,8,5,6,9,5~4,10,8,3,3,3,3,7,5,6,10,3,7,3,5,7,10,7,5,7,5,3,7,3,7,5,7~7,3,3,3,5,4,6,3,8,4,3,6,4,3,6,3,6,4,3,4,6,3';
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
                $linesId = [];
                $linesId[0] = [2, 2, 2, 2, 2];
                $linesId[1] = [1, 1, 1, 1, 1];
                $linesId[2] = [3, 3, 3, 3, 3];
                $linesId[3] = [1, 2, 3, 2, 1];
                $linesId[4] = [3, 2, 1, 2, 3];
                $linesId[5] = [2, 1, 2, 3, 2];                
                $linesId[6] = [1, 1, 2, 3, 3];
                $linesId[7] = [3, 3, 2, 1, 1];
                $linesId[8] = [1, 2, 2, 2, 3];
                $linesId[9] = [3, 2, 2, 2, 1];
                $linesId[10] = [2, 3, 2, 1, 2];
                $linesId[11] = [2, 1, 1, 2, 3];
                $linesId[12] = [2, 2, 1, 2, 3];
                $linesId[13] = [2, 2, 3, 2, 1];
                $linesId[14] = [1, 1, 1, 2, 3];
                $linesId[15] = [1, 1, 2, 3, 2];                
                $linesId[16] = [3, 3, 2, 1, 2];
                $linesId[17] = [2, 1, 2, 3, 3];
                $linesId[18] = [2, 3, 2, 1, 1];
                $linesId[19] = [2, 3, 3, 2, 1];
                $slotEvent['slotBet'] = $slotEvent['c'];
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

                // $winType = 'bonus';

                $allBet = $betline * $lines;
                $freeStacks = []; 
                $isGeneratedFreeStack = false;
                if($slotEvent['slotEvent'] == 'freespin' || $slotSettings->GetGameData($slotSettings->slotId . 'RespinCount') >= 0){
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
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $_sum, $slotEvent['slotEvent']);
                    $bonusMpl = 1;
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinCount', -1);                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusType', 0);               
                    $slotSettings->SetGameData($slotSettings->slotId . 'Ind', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $slotSettings->GetBalance());
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMpl', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Level', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Wins', [0,0,0,0,0]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'Status', [0,0,0,0,0]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '561' . substr($roundstr, 4, 10);
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
                $wildValues = [];
                $wildPoses = [];
                $strWinLine = '';
                $winLineCount = 0;
                $str_trail = '';
                $ls = 0;
                $str_accm = '';
                $str_accv = '';
                $rs_p = -1;
                $rs_t = 0;
                $bmw = -1;
                $bw = -1;
                $level = -1;
                $wp = 0;
                $lifes = -1;
                $end = -1;
                $g = null;
                $subScatterReel = null;
                if($isGeneratedFreeStack == true){
                    $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                    $lastReel = explode(',', $stack['reel']);
                    $currentReelSet = $stack['reel_set'];
                    $str_trail = $stack['trail'];
                    $ls = $stack['ls'];
                    $str_accm = $stack['accm'];
                    $str_accv = $stack['accv'];
                    $rs_p = $stack['rs_p'];
                    $rs_t = $stack['rs_t'];
                    $bmw = $stack['bmw'];
                    $bw = $stack['bw'];
                    $level = $stack['level'];
                    $wp = $stack['wp'];
                    $lifes = $stack['lifes'];
                    $end = $stack['end'];
                    if($stack['g'] != ''){
                        $g = $stack['g'];
                    }
                }else{
                    $stack = $slotSettings->GetReelStrips($winType, $betline * $lines);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $stack);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    if($stack == null){
                        $response = 'unlogged';
                        exit( $response );
                    }
                    $lastReel = explode(',', $stack[0]['reel']);
                    $currentReelSet = $stack[0]['reel_set'];
                    $str_trail = $stack[0]['trail'];
                    $ls = $stack[0]['ls'];
                    $str_accm = $stack[0]['accm'];
                    $str_accv = $stack[0]['accv'];
                    $rs_p = $stack[0]['rs_p'];
                    $rs_t = $stack[0]['rs_t'];
                    $bmw = $stack[0]['bmw'];
                    $bw = $stack[0]['bw'];
                    $level = $stack[0]['level'];
                    $wp = $stack[0]['wp'];
                    $lifes = $stack[0]['lifes'];
                    $end = $stack[0]['end'];
                    if($stack[0]['g'] != ''){
                        $g = $stack[0]['g'];
                    }
                }


                $reels = [];
                $rainbowCount = 0;
                $emeraldCount = 0;
                for($i = 0; $i < 5; $i++){
                    $reels[$i] = [];
                    for($j = 0; $j < 3; $j++){
                        $reels[$i][$j] = $lastReel[$j * 5 + $i];
                        if($reels[$i][$j] == 9){
                            $emeraldCount++;
                        }else if($reels[$i][$j] == 10){
                            $rainbowCount++;
                        }
                    }
                }
                if($rs_p > 0 || $rs_t > 0){
                    foreach($g as $key => $item){
                        if(isset($item['l0'])){
                            $arr_wins = explode('~', $item['l0']);
                            if(count($arr_wins) > 0){
                                $sub_win = str_replace(',', '', $arr_wins[1]) / $original_bet * $betline;
                                $arr_wins[1] = $sub_win;
                                $totalWin = $totalWin + $sub_win;
                                $g[$key]['l0'] = implode('~', $arr_wins);
                            }
                        }
                    }
                }else{
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
                }
                $spinType = 's';
                if( $totalWin > 0) 
                {
                    $spinType = 'c';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }
                $_obf_totalWin = $totalWin;
                
                $strLastReel = implode(',', $lastReel);
                $reelA = [];
                $reelB = [];
                for($i = 0; $i < 5; $i++){
                    $reelA[$i] = mt_rand(7, 10);
                    $reelB[$i] = mt_rand(7, 10);
                }
                $strReelSa = implode(',', $reelA); // '7,4,6,10,10';
                $strReelSb = implode(',', $reelB); // '3,8,4,7,10';
               
                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                $strOtherResponse = '';
                $isState = true;
                
                $slotSettings->SetGameData($slotSettings->slotId . 'RespinCount', $rs_p);    
                if( $rs_p >= 0 || $rs_t > 0) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                    $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                    if($rs_t > 0){
                        $spinType = 'c';
                    }else{
                        $isState = false;
                        $spinType = 's';
                    }
                }else
                {
                    // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                }
                if($rainbowCount >= 3 || $emeraldCount >= 3){
                    $spinType = 'b';
                    $isState = false;
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 50);
                    if($rainbowCount >= 3){
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusType', 2);
                    }else if($emeraldCount >= 3){
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusType', 1);
                    }
                }
                if($str_trail != ''){
                    $strOtherResponse = $strOtherResponse . '&trail=' . $str_trail;
                }
                if($str_accv != ''){
                    $strOtherResponse = $strOtherResponse . '&acci=0&accv=' . $str_accv . '&accm=' . $str_accm;
                }
                if($rs_p >= 0){
                    $strOtherResponse = $strOtherResponse . '&rs=mc&rs_p='. $rs_p .'&rs_c='. ($rs_p + 1) .'&rs_m=' . ($rs_p + 1);
                }
                if($rs_t > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t;
                }
                if($bw > 0){
                    $strOtherResponse = $strOtherResponse . '&bw=' . $bw;
                }
                if($g != null){
                    $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($g));
                }
                $response = 'tw='.$slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . $strOtherResponse . $strWinLine .'&ls='. $ls .'&balance='.$Balance. '&index='.$slotEvent['index'].'&balance_cash='.$Balance.'&balance_bonus=0.00&na='.$spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&sa='.$strReelSa.'&sb='.$strReelSb.'&sh=3&c='.$betline.'&sw=5&sver=5&reel_set='.$currentReelSet.'&counter='. ((int)$slotEvent['counter'] + 1) .'&l=20&s='.$strLastReel.'&w='.$totalWin;
                if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 1 <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0) 
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
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"RespinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinCount') . ',"BonusType":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusType') . ',"Ind":' . $slotSettings->GetGameData($slotSettings->slotId . 'Ind') . ',"Level":' . $slotSettings->GetGameData($slotSettings->slotId . 'Level') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"Wins":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'Wins')) . ',"Status":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'Status')) .',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                
                if( $rs_p == 0 || $rainbowCount >= 3 || $emeraldCount >= 3) 
                {
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                }
            }else if( $slotEvent['slotEvent'] == 'doBonus' ){
                $lastEvent = $slotSettings->GetHistory();
                $betline = $lastEvent->serverResponse->bet;
                $lastReel = $lastEvent->serverResponse->LastReel;
                $lines = 20;
                $ind = -1;
                if(isset($slotEvent['ind'])){
                    $ind = $slotEvent['ind'];
                }

                $bgt = $slotSettings->GetGameData($slotSettings->slotId . 'Bgt');
                $bonusType = $slotSettings->GetGameData($slotSettings->slotId . 'BonusType');
                $Balance = $slotSettings->GetGameData($slotSettings->slotId . 'FreeBalance');
                $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks');
                $stack = $freeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                               
                $str_trail = $stack['trail'];
                $ls = $stack['ls'];
                $str_accm = $stack['accm'];
                $str_accv = $stack['accv'];
                $rs_p = $stack['rs_p'];
                $rs_t = $stack['rs_t'];
                $bmw = $stack['bmw'];
                $bw = $stack['bw'];
                $level = $stack['level'];
                $wp = $stack['wp'];
                $lifes = $stack['lifes'];
                $end = $stack['end'];
                $g = null;
                if($stack['g'] != ''){
                    $g = $stack['g'];
                }
                if($g != null){
                    if($slotSettings->GetGameData($slotSettings->slotId . 'BonusType') == 1){
                        if($ind >= 0){
                            $new_wins = $slotSettings->GetGameData($slotSettings->slotId . 'Wins');
                            $new_status = $slotSettings->GetGameData($slotSettings->slotId . 'Status');
    
                            $wins = explode(',', $g['eb']['wins']);
                            $status = explode(',', $g['eb']['status']);
                            $wins_mask = explode(',', $g['eb']['wins_mask']);
                            $wi = $g['eb']['wi'];
                            $new_wins_mask = [];
                            for($k = 0; $k < count($new_wins); $k++){
                                if($new_wins[$k] == 0){
                                    $new_wins_mask[$k] = 'h';
                                }else{
                                    $new_wins_mask[$k] = 'ma';
                                }
                            }
                            if($ind >= 0 && $level > 0){
                                $new_wins[$ind] = $wins[$wi];
                                $new_wins_mask[$ind] = $wins_mask[$wi];
                                $new_status[$ind] = $status[$wi];
                            }
                            $g['eb']['ch'] = 'ind~' . $ind;
                            $g['eb']['status'] = implode(',', $new_status);
                            $g['eb']['wins'] = implode(',', $new_wins);
                            $g['eb']['wins_mask'] = implode(',', $new_wins_mask);
                            $g['eb']['wi'] = $ind;
                        }
                        $slotSettings->SetGameData($slotSettings->slotId . 'Wins', explode(',', $g['eb']['wins']));
                        $slotSettings->SetGameData($slotSettings->slotId . 'Status', explode(',', $g['eb']['status']));
                    }else{
                        if($rs_p > 0 || $rs_t > 0){
                            foreach($g as $key => $item){
                                if(isset($item['l0'])){
                                    $arr_wins = explode('~', $item['l0']);
                                    if(count($arr_wins) > 0){
                                        $sub_win = $arr_wins[1] / $original_bet * $betline;
                                        $arr_wins[1] = $sub_win;
                                        $g[$key]['l0'] = implode('~', $arr_wins);
                                    }
                                }
                            }
                        }
                    }
                }

                $slotSettings->SetGameData($slotSettings->slotId . 'Level', $level);
                $slotSettings->SetGameData($slotSettings->slotId . 'Ind', $ind);
                
                $totalWin = 0;
                $coef = $betline * $lines;
                if($bmw > 0){
                    $bmw = $bmw / $original_bet * $betline;                        
                }
                $isState = false;
                if($end == 1){
                    if($wp > 0){
                        $totalWin = $wp * $coef;
                    }
                    if($bmw > 0){
                        $totalWin += $bmw;
                    }
                    $isState = true;
                }
                $spinType = 'b';
                if( $totalWin > 0) 
                {
                    $spinType = 'cb';
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                $strOtherResponse = '';
                if($totalWin > 0){
                    $strOtherResponse = $strOtherResponse . '&tw=' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                }

                $slotSettings->SetGameData($slotSettings->slotId . 'LastReel', $lastReel);
                if($end < 1){
                    if($bmw > 0){
                        $strOtherResponse = $strOtherResponse . '&rw=' . $bmw;
                    }else{
                        $strOtherResponse = $strOtherResponse . '&rw=0';
                    }       
                    $strOtherResponse = $strOtherResponse . '&end=0';
                }else{
                    $strOtherResponse = $strOtherResponse . '&end='. $end .'&rw=' . $totalWin;                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'Bgt', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusType', 0);
                    if($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') == 0){
                        $spinType = 's';
                    }
                }
                if($lifes >= 0){
                    $strOtherResponse = $strOtherResponse . '&lifes=' . $lifes;
                }
                if($bmw >= 0){
                    $strOtherResponse = $strOtherResponse . '&bmw=' . $bmw;
                }
                if($bonusType == 1){
                    $strOtherResponse = $strOtherResponse . '&bgid=0';
                }else{
                    $strOtherResponse = $strOtherResponse . '&bgid=1';
                }
                if($rs_p >= 0){
                    $strOtherResponse = $strOtherResponse . '&rs=mc&rs_p='. $rs_p .'&rs_c='. ($rs_p + 1) .'&rs_m=' . ($rs_p + 1);
                }
                if($rs_t > 0){
                    $strOtherResponse = $strOtherResponse . '&rs_t=' . $rs_t;
                }
                if($g != null){
                    $strOtherResponse = $strOtherResponse . '&g=' . preg_replace('/"(\w+)":/i', '\1:', json_encode($g));
                }
                
                $response = 'ls=1' . $strOtherResponse .'&balance='. $Balance .'&coef='. $coef .'&level='. $level .'&index='.$slotEvent['index'].'&balance_cash='. $Balance .'&balance_bonus=0.00&na='. $spinType .'&rid='. $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') .'&stime=' . floor(microtime(true) * 1000) .'&bgt=50&wp='. $wp .'&sver=5&counter='. ((int)$slotEvent['counter'] + 1);

                //------------ ReplayLog ---------------
                $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
                if (!$replayLog) $replayLog = [];
                $current_replayLog["cr"] = $paramData;
                $current_replayLog["sr"] = $response;
                array_push($replayLog, $current_replayLog);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);
                //------------ *** ---------------

                $_GameLog = '{"responseEvent":"spin","responseType":"' . $slotEvent['slotEvent'] . '","serverResponse":{"BonusMpl":' . 
                    $slotSettings->GetGameData($slotSettings->slotId . 'BonusMpl') . ',"lines":' . $lines . ',"bet":' . $betline . ',"totalFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') . ',"Balance":' . $Balance . ',"ReplayGameLogs":'.json_encode($replayLog).',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') . ',"bonusWin":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') . ',"RoundID":' . $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') . ',"TotalSpinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') . ',"RespinCount":' . $slotSettings->GetGameData($slotSettings->slotId . 'RespinCount') . ',"BonusType":' . $slotSettings->GetGameData($slotSettings->slotId . 'BonusType') . ',"Ind":' . $slotSettings->GetGameData($slotSettings->slotId . 'Ind')  . ',"Level":' . $slotSettings->GetGameData($slotSettings->slotId . 'Level') . ',"Bgt":' . $slotSettings->GetGameData($slotSettings->slotId . 'Bgt') . ',"Wins":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'Wins')) . ',"Status":' . json_encode($slotSettings->GetGameData($slotSettings->slotId . 'Status')) .',"FreeStacks":'.json_encode($slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')) . ',"winLines":[],"Jackpots":""' . ',"LastReel":'.json_encode($lastReel).'}}';//ReplayLog, FreeStack
                $allBet = $betline * $lines;
                $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
            }


            
            if($slotEvent['action'] == 'doSpin' || $slotEvent['action'] == 'doCollect' || $slotEvent['action'] == 'doCollectBonus' || $slotEvent['action'] == 'doBonus'){
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
        public function findZokbos($reels, $firstSymbol, $repeatCount, $positions){
            $wild = '2';
            $bPathEnded = true;
            if($repeatCount < 5){
                for($r = 0; $r < 3; $r++){
                    if($firstSymbol == $reels[$repeatCount][$r] || $reels[$repeatCount][$r] == $wild){
                        $this->findZokbos($reels, $firstSymbol, $repeatCount + 1, array_merge($positions, [($repeatCount + $r * 5)]));
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

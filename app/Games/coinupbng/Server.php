<?php 
namespace VanguardLTE\Games\coinupbng
{
    class Server
    {
        public function get($request, $game, $userId) // changed by game developer
        {
            /*if( config('LicenseDK.APL_INCLUDE_KEY_CONFIG') != 'wi9qydosuimsnls5zoe5q298evkhim0ughx1w16qybs2fhlcpn' ) 
            {
                return false;
            }
            if( md5_file(base_path() . '/app/Lib/LicenseDK.php') != '3c5aece202a4218a19ec8c209817a74e' ) 
            {
                return false;
            }
            if( md5_file(base_path() . '/config/LicenseDK.php') != '951a0e23768db0531ff539d246cb99cd' ) 
            {
                return false;
            }
            $checked = new \VanguardLTE\Lib\LicenseDK();
            $license_notifications_array = $checked->aplVerifyLicenseDK(null, 0);
            if( $license_notifications_array['notification_case'] != 'notification_license_ok' ) 
            {
                $response = '{"responseEvent":"error","responseType":"error","serverResponse":"Error LicenseDK"}';
                exit( $response );
            }*/
            $response = '';
            \DB::beginTransaction();
            // $userId = \Auth::id();// changed by game developer
            if( $userId == null ) 
            {
            	$response = 'unlogged';
                exit( $response );
            }
            $user = \VanguardLTE\User::lockForUpdate()->find($userId);
            $credits = null;
            $slotSettings = new SlotSettings($game, $userId, $credits);
            $paramData = trim(file_get_contents('php://input'));
            // $_obf_params = explode('&', $paramData);
            $slotEvent = json_decode($paramData, true);

            
            $LASTSPIN = $slotSettings->GetHistory();
            $BALANCE = $slotSettings->GetBalance();
            $DENOMINATOR = 100;
            $LINES = 1;
            
            $WILD = 12;
            $SCATTER = 13;
            $mul = 0.1;
            $Counter = $slotSettings->GetGameData($slotSettings->slotId . 'Counter') ?? 0;           
            $slotSettings->SetBet();
            if($slotEvent['command'] == 'login'){
                $slotSettings->SetGameData($slotSettings->slotId . 'BalanceVersion', $BALANCE * $DENOMINATOR);
                $user = [
                    'balance' => $BALANCE * $DENOMINATOR,
                    'balance_version' => $slotSettings->GetGameData($slotSettings->slotId . 'BalanceVersion'),
                    'currency' => 'KRW',
                    'huid' => '969:major:17390:rKRW',
                    'show_balance' => true
                ];
                $objRes = [
                    'command' => $slotEvent['command'],
                    'modes' => ['auto', 'play', 'freebet'],
                    'request_id' => $slotEvent['request_id'],
                    'session_id' => '68939e9a5d134e78bfd9993d4a2cc34e',
                    'status' => [
                        'code' => 'ok'
                    ],
                    'user' => $user
                ];
                $Counter = 0;
            }else if($slotEvent['command'] == 'start'){
                $Counter++;
                $bets = [];
                for($k = 0; $k < count($slotSettings->Bet); $k++){
                    array_push($bets, $slotSettings->Bet[$k] * $DENOMINATOR);
                }
                $_obf_StrResponse = '';
                
                $objRes = [
                    'command' => $slotEvent['command'],
                    'context' => [
                        'achievements' => ['level'=> 0, 'level_percent'=> 0, 'number'=> 0, 'total_percent'=> 0],
                        'actions' => ['spin', 'buy_spin'],
                        'current' => 'spins',
                        'last_action' => 'init',
                        'last_args' => [
                        ],
                        'last_win' => ($slotSettings->GetGameData($slotSettings->slotId . 'LastWin') ?? 0) * $DENOMINATOR,
                        'math_version' => 'a',
                        'round_finished' => true,
                        'spins' => [
                            'bet_per_line' => $bets[9],
                            'board' => [[1,0,0],[0,1,0],[0,0,1]],
                            'bs' => [["position"=>0,"reel"=>0,"type"=>"regular","value"=>50 * $bets[9]],["position"=>1,"reel"=>1,"type"=>"regular","value"=>4 * $bets[9]],["position"=>2,"reel"=>2,"type"=>"regular","value"=>10 * $bets[9]]],
                            'bs_count' => 3,
                            'lines' => $LINES,
                            'round_bet' => $bets[9],
                            'round_win' => 0,
                            'total_win' => 0,
                        ],
                        'version' => 1
                    ],
                    'modes' => ['auto', 'play', 'freebet'],
                    'origin_data' => [
                        'data' => [
                            'quick_spin' => false
                        ],
                        'quick_spin' => false
                    ],
                    'request_id' => $slotEvent['request_id'],
                    'session_id' => '68939e9a5d134e78bfd9993d4a2cc34e',
                    'settings' => json_decode('{"bet_factor": [1], "bets": ['. implode(',', $bets) .'], "big_win":[30,60,100],"bs_values_reels":{"1":56,"2":56,"3":40,"4":24,"5":60,"10":15,"20":10,"50":5},"cols":3,"currency_format":{"currency_style":"symbol","denominator":100,"style":"money"},"freespins_buying_price":{"1":40,"2":75},"infinity_values":[5],"jackpots":{"10":"mini","20":"minor","50":"major","500":"grand"},"lines":[1],"mystery_values":[1,2,3,4,10,20,50],"paylines":[[1,1,1]],"rows":3,"symbols":[{"id":1,"name":"bonus","type":"scat"}],"symbols_scat":[1]}'),
                    'status' => [
                        'code' => 'OK'
                    ],
                    'user' => [
                        'balance' => $BALANCE * 100,
                        'balance_version' => $slotSettings->GetGameData($slotSettings->slotId . 'BalanceVersion'),
                        'currency' => 'KRW',
                        'huid' => '969:major:17390:rKRW',
                        'show_balance' => true
                    ]
                ];
                if( $LASTSPIN !== NULL ) {
                    if(isset($LASTSPIN->context)){
                        // if(isset($LASTSPIN->context->spins)){
                        //     $LASTSPIN->context->spins->board = $LASTSPIN->context->spins->feature_board;
                        // }else if(isset($LASTSPIN->context->freespins)){
                        //     $LASTSPIN->context->freespins->board = $LASTSPIN->context->freespins->feature_board;
                        // }
                        $objRes['context'] = $LASTSPIN->context;
                    }
                }
            }else if($slotEvent['command'] == 'sync'){
                $objRes = [
                    'command' => $slotEvent['command'],
                    'modes' => ['auto', 'play'],
                    'request_id' => $slotEvent['request_id'],
                    'session_id' => '68939e9a5d134e78bfd9993d4a2cc34e',
                    'status' => [
                        'code' => 'OK'
                    ],
                    'user' => [
                        'balance' => $BALANCE * $DENOMINATOR,
                        'balance_version' => $slotSettings->GetGameData($slotSettings->slotId . 'BalanceVersion'),
                        'currency' => 'KRW',
                        'huid' => '969:major:17390:rKRW',
                        'show_balance' => true
                    ]
                ];
            }else if($slotEvent['command'] == 'play'){
                $beforeBalance = $BALANCE;
                $Counter++;

                $action = $slotEvent['action'];
                // $set_denominator = $slotEvent['set_denominator'];
                $quick_spin = $slotEvent['quick_spin'];
                $sound = $slotEvent['sound'];
                $autogame = $slotEvent['autogame'];
                $mobile = $slotEvent['mobile'];
                $portrait = $slotEvent['portrait'];
                // $prev_client_command_time = $slotEvent['prev_client_command_time'];
                $selectedMode = '';
                $totalWin = 0;
                if($action['name'] == 'spin' || $action['name'] == 'buy_spin'){
                    $betline = $action['params']['bet_per_line'] / $DENOMINATOR;
                    if($action['name'] == 'buy_spin'){
                        $selectedMode = $action['params']['selected_mode'];
                    }
                    $slotEvent['slotEvent'] = 'bet';
                }else if($action['name'] == 'freespin_init' || $action['name'] == 'freespin' || $action['name'] == 'freespin_stop'){
                    $betline = $slotSettings->GetGameData($slotSettings->slotId . 'Bet') ?? $slotSettings->Bet[9];
                    $slotEvent['slotEvent'] = 'freespin';
                }else if($action['name'] == 'bonus_init' || $action['name'] == 'respin' || $action['name'] == 'bonus_spins_stop' || $action['name'] == 'bonus_freespins_stop'){
                    $betline = $slotSettings->GetGameData($slotSettings->slotId . 'Bet') ?? $slotSettings->Bet[9];
                    $slotEvent['slotEvent'] = 'respin';
                }else{
                    // throw error
                    return '';
                }

                $currentFreeGames = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') ?? 0;
                $totalFreeGames = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') ?? 0;
                $currentRespin = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') ?? 0;
                $totalSpinCount = $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') ?? 0;
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks') ?? [];
                $moneyCount = $slotSettings->GetGameData($slotSettings->slotId . 'MoneyCount') ?? 0;
                $stack = null;
                $isState = false;
                $is_extra_feature = false;
                if($action['name'] == 'freespin_init' || $action['name'] == 'freespin_stop' || $action['name'] == 'bonus_init' || $action['name'] == 'bonus_spins_stop' || $action['name'] == 'bonus_freespins_stop'){
                    if(count($tumbAndFreeStacks) > 0 && isset($tumbAndFreeStacks[$totalSpinCount])){
                        $stack = $tumbAndFreeStacks[$totalSpinCount];
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $totalSpinCount + 1);
                    }else{
                        return '';
                    }   
                    if($action['name'] == 'bonus_spins_stop' || $action['name'] == 'bonus_freespins_stop'){
                        $moneyCount = 0;
                        $slotSettings->SetGameData($slotSettings->slotId . 'MoneyCount', $moneyCount);
                    }
                    $Counter = 0;
                    $spin_types = ['spins', 'freespins', 'bonus'];
                    for($k = 0; $k < 3; $k++){
                        $spin_type = $spin_types[$k];
                        if($stack[$spin_type] != ''){
                            if($stack[$spin_type] != ''){
                                $stack[$spin_type]['bet_per_line'] = $betline * $DENOMINATOR;
                                $stack[$spin_type]['round_bet'] = $betline * $DENOMINATOR * $LINES;
                                if(isset($stack[$spin_type]['round_win'])){
                                    $stack[$spin_type]['round_win'] = str_replace(',', '', $stack[$spin_type]['round_win'])* $mul * $betline * $DENOMINATOR;
                                }
                                if(isset($stack[$spin_type]['total_win'])){
                                    $stack[$spin_type]['total_win'] = str_replace(',', '', $stack[$spin_type]['total_win'])* $mul * $betline * $DENOMINATOR;
                                }
                                //$stack[$spin_type]['bac'] = $currentHill;
                                if(isset($stack[$spin_type]['bs'])){
                                    for($i = 0; $i < count($stack[$spin_type]['bs']); $i++){
                                        if(is_numeric(str_replace(',', '', $stack[$spin_type]['bs'][$i]['value']))){
                                            $stack[$spin_type]['bs'][$i]['value'] = str_replace(',', '', $stack[$spin_type]['bs'][$i]['value'])* $mul * $betline * $DENOMINATOR;
                                        }
                                    }
                                }
                                if(isset($stack[$spin_type]['bs_new'])){
                                    for($i = 0; $i < count($stack[$spin_type]['bs_new']); $i++){
                                        if(is_numeric(str_replace(',', '', $stack[$spin_type]['bs_new'][$i]['value']))){
                                            $stack[$spin_type]['bs_new'][$i]['value'] = str_replace(',', '', $stack[$spin_type]['bs_new'][$i]['value'])* $mul * $betline * $DENOMINATOR;
                                        }
                                    }
                                }
                                if(isset($stack[$spin_type]['new_collectors'])){
                                    for($i = 0; $i < count($stack[$spin_type]['new_collectors']); $i++){
                                        if(is_numeric(str_replace(',', '', $stack[$spin_type]['new_collectors'][$i]['value']))){
                                            $stack[$spin_type]['new_collectors'][$i]['value'] = str_replace(',', '', $stack[$spin_type]['new_collectors'][$i]['value'])* $mul * $betline * $DENOMINATOR;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $objRes = [
                        'command' => $slotEvent['command'],
                        'context' => [
                            'achievements' => $slotSettings->GetAchievements($moneyCount),
                            'last_action' => $action['name'],
                            'last_args' => [],
                            'last_win' => ($slotSettings->GetGameData($slotSettings->slotId . 'LastWin') ?? 0) * $DENOMINATOR,
                            'math_version' => 'a',
                            'round_finished' => false,
                            'version' => 2
                        ],
                        'modes' => ['auto', 'play'],
                        'origin_data' => [
                            'autogame' => $autogame,
                            'data' => [
                                'quick_spin' => $quick_spin
                            ],
                            'feature' => true,
                            'mobile' => $mobile,
                            'portrait' => $portrait,
                            'quick_spin' => $quick_spin,
                            'sound' => $sound
                        ],
                        'request_id' => $slotEvent['request_id'],
                        'session_id' => '68939e9a5d134e78bfd9993d4a2cc34e',
                        'status' => [
                            'code' => 'OK'
                        ],
                        'user' => [
                            'balance' => $BALANCE * 100,
                            'balance_version' => $slotSettings->GetGameData($slotSettings->slotId . 'BalanceVersion'),
                            'currency' => 'KRW',
                            'huid' => '969:major:17390:rKRW',
                            'show_balance' => true
                        ]
                    ];
                    if($stack['bonus'] != ''){
                        $objRes['context']['bonus'] = $stack['bonus'];
                    }
                    if($stack['freespins'] != ''){
                        $objRes['context']['freespins'] = $stack['freespins'];
                    }
                    if($stack['spins'] != ''){
                        $objRes['context']['spins'] = $stack['spins'];
                    }
                    if($action['name'] == 'freespin_init'){
                        $objRes['context']['actions'] = ['freespin'];
                        $objRes['context']['current'] = 'freespins';
                    }else if($action['name'] == 'freespin_stop'){
                        $objRes['context']['actions'] = ['spin'];
                        $objRes['context']['current'] = 'spins';
                    }else if($action['name'] == 'bonus_init'){
                        $objRes['context']['actions'] = ['respin'];
                        $objRes['context']['current'] = 'bonus';
                    }else if($action['name'] == 'bonus_spins_stop' || $action['name'] == 'bonus_freespins_stop'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', 0);
                        if($totalFreeGames <= 0){
                            $objRes['context']['actions'] = ['spin'];
                            $objRes['context']['current'] = 'spins';
                        }else if($totalFreeGames - $currentFreeGames <= 0){
                            $objRes['context']['actions'] = ['freespin_stop'];
                            $objRes['context']['current'] = 'freespins';
                        }else{
                            $objRes['context']['actions'] = ['freespin'];
                            $objRes['context']['current'] = 'freespins';
                        }
                    }
                }else{
                    if($slotEvent['slotEvent'] == 'bet' && $betline * $LINES > $BALANCE){
                        // throw error
                        $objRes = [
                            'command' => $slotEvent['command'],
                            'context' => [
                                'actions' => ['spin', 'buy_spin'],
                                'current' => 'spins',
                                'last_action' => 'init',
                                'last_args' => [
                                ],
                                'last_win' => ($slotSettings->GetGameData($slotSettings->slotId . 'LastWin') ?? 0) * $DENOMINATOR,
                                'math_version' => 'a',
                                'round_finished' => true,
                                'spins' => [
                                    'bet_per_line' => $betline * $DENOMINATOR,
                                    'board' => [[1,0,0],[0,1,0],[0,0,1]],
                                    'bs' => [["position"=>0,"reel"=>0,"type"=>"regular","value"=>50 * $betline * $DENOMINATOR],["position"=>1,"reel"=>1,"type"=>"regular","value"=>4 * $betline * $DENOMINATOR],["position"=>2,"reel"=>2,"type"=>"regular","value"=>10 * $betline * $DENOMINATOR]],
                                    'bs_count' => 3,
                                    'lines' => $LINES,
                                    'round_bet' => $betline * $DENOMINATOR,
                                    'round_win' => 0,
                                    'total_win' => 0,
                                ],
                                'version' => 1
                            ],
                            'modes' => ['auto', 'play', 'freebet'],
                            'origin_data' => [
                                'data' => [
                                    'quick_spin' => false
                                ],
                                'quick_spin' => false
                            ],
                            'request_id' => $slotEvent['request_id'],
                            'session_id' => '68939e9a5d134e78bfd9993d4a2cc34e',
                            'status' => [
                                'code' => 'FUNDS_EXCEED',
                                'type' => 'exceed'
                            ],
                            'user' => [
                                'balance' => $BALANCE * 100,
                                'balance_version' => $slotSettings->GetGameData($slotSettings->slotId . 'BalanceVersion'),
                                'currency' => 'KRW',
                                'huid' => '969:major:17390:rKRW',
                                'show_balance' => true
                            ]
                            ];
                        if( $LASTSPIN !== NULL ) {
                            if(isset($LASTSPIN->context)){
                                $objRes['context'] = $LASTSPIN->context;
                            }
                        }
                        return json_encode($objRes);
                    }else if($slotEvent['slotEvent'] == 'freespin'){
                        if($totalFreeGames <= 0 || ($currentFreeGames >= $totalFreeGames)) 
                        {
                            // throw error
                            return '';
                        }
                        $currentFreeGames++;
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $currentFreeGames);
                    }else if($slotEvent['slotEvent'] == 'respin'){
                        if($currentRespin <= 0) 
                        {
                            // throw error
                            return '';
                        }
                    }
                    $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $betline * $LINES, $LINES);
                    $winType = $_spinSettings[0];
                    $_winAvaliableMoney = $_spinSettings[1];
                    if($winType == 'win'){
                        $winType = 'none'; 
                    }
                    // $winType = 'win';
                    // $_winAvaliableMoney = 1000;
                    $isDoubleScatter = false;
                    if($slotEvent['slotEvent'] != 'freespin' && $slotEvent['slotEvent'] != 'respin'){
                        $pur = -1;
                        $allBet = $betline * $LINES;                        
                        $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreespin', -1);
                        if($action['name'] == 'buy_spin'){
                            if($selectedMode == '1'){
                                $pur = 0;
                                $allBet = $allBet * 40;
                            }else if($selectedMode == '2'){
                                $pur = 1;
                                $allBet = $allBet * 75;
                            }
                            
                            $isBuyFreeSpin = true;
                            $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreespin', $pur);
                            $winType = 'bonus';
                        }
                        $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                        $_sum = $allBet / 100 * $slotSettings->GetPercent();
                        $slotSettings->SetGameData($slotSettings->slotId . 'Bet', $betline);  
                        $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $LINES);             
                        $slotSettings->SetBet();
                        $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $_sum, $slotEvent['slotEvent']);
                        $bonusMpl = 1;
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);

                        $tumbAndFreeStacks = $slotSettings->GetReelStrips($winType, $allBet,$pur);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $tumbAndFreeStacks);

                        $totalSpinCount = 0;
                        
                        $roundstr = sprintf('%.4f', microtime(TRUE));
                        $roundstr = str_replace('.', '', $roundstr);
                        $roundstr = '715' . substr($roundstr, 4, 10) . '001';
                        $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);
                    }
                    if(count($tumbAndFreeStacks) > 0 && isset($tumbAndFreeStacks[$totalSpinCount])){
                        $stack = $tumbAndFreeStacks[$totalSpinCount];
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $totalSpinCount + 1);
                    }else{
                        return '';
                    }
                    $freeSpinNum = 0;
                    $spin_types = ['spins', 'freespins', 'bonus'];
                    $totalWin = 0;
                    //$moneyCount = 0;
                    for($k = 0; $k < 3; $k++){
                        $spin_type = $spin_types[$k];
                        if($stack[$spin_type] != ''){
                            $stack[$spin_type]['bet_per_line'] = $betline * $DENOMINATOR;
                            $stack[$spin_type]['round_bet'] = $betline * $DENOMINATOR * $LINES;
                            $totalWin = 0;
                            if(isset($stack[$spin_type]['round_win'])){
                                $totalWin = $stack[$spin_type]['round_win']* $mul * $betline;
                                $stack[$spin_type]['round_win'] = str_replace(',', '', $stack[$spin_type]['round_win'])* $mul * $betline * $DENOMINATOR;
                            }
                            if(isset($stack[$spin_type]['total_win'])){
                                $stack[$spin_type]['total_win'] = str_replace(',', '', $stack[$spin_type]['total_win'])* $mul * $betline * $DENOMINATOR;
                            }
                            if($slotEvent['slotEvent'] != 'respin' && $spin_types[$k] != 'bonus' && isset($stack[$spin_type]['board'])){
                                //$moneyCount = 0;
                                for($i = 0; $i < count($stack[$spin_type]['bs']); $i++){
                                    if($stack[$spin_type]['bs'][$i]['position'] == 1 && $stack[$spin_type]['bs'][$i]['type'] == 'regular'){
                                        $moneyCount++;
                                    }
                                }
                            }   
                            if(isset($stack[$spin_type]['bs'])){
                                for($i = 0; $i < count($stack[$spin_type]['bs']); $i++){
                                    if(is_numeric(str_replace(',', '', $stack[$spin_type]['bs'][$i]['value']))){
                                        $stack[$spin_type]['bs'][$i]['value'] = str_replace(',', '', $stack[$spin_type]['bs'][$i]['value'])* $mul * $betline * $DENOMINATOR;
                                    }
                                }
                            }
                            if(isset($stack[$spin_type]['bs_new'])){
                                for($i = 0; $i < count($stack[$spin_type]['bs_new']); $i++){
                                    if(is_numeric(str_replace(',', '', $stack[$spin_type]['bs_new'][$i]['value']))){
                                        $stack[$spin_type]['bs_new'][$i]['value'] = str_replace(',', '', $stack[$spin_type]['bs_new'][$i]['value'])* $mul * $betline * $DENOMINATOR;
                                    }
                                }
                            }
                            if(isset($stack[$spin_type]['new_collectors'])){
                                for($i = 0; $i < count($stack[$spin_type]['new_collectors']); $i++){
                                    if(is_numeric(str_replace(',', '', $stack[$spin_type]['new_collectors'][$i]['value']))){
                                        $stack[$spin_type]['new_collectors'][$i]['value'] = str_replace(',', '', $stack[$spin_type]['new_collectors'][$i]['value'])* $mul * $betline * $DENOMINATOR;
                                    }
                                }
                            }
                            if($spin_type != 'bonus'){
                                if(isset($stack[$spin_type]['winlines']) && count($stack[$spin_type]['winlines']) > 0){
                                    foreach( $stack[$spin_type]['winlines'] as $index => $value ){
                                        if(isset($value['amount']) && $value['amount'] > 0){
                                            $value['amount'] = str_replace(',', '', $value['amount'])* $mul * $betline * $DENOMINATOR;
                                            $stack[$spin_type]['winlines'][$index] = $value;
                                        }
                                    }
                                }
                                if(isset($stack[$spin_type]['winscatters']) && count($stack[$spin_type]['winscatters']) > 0){
                                    foreach( $stack[$spin_type]['winscatters'] as $index => $value ){
                                        if(isset($value['freespins'])){
                                            if($slotEvent['slotEvent'] == 'bet' && $spin_type == 'spins'){
                                                $freeSpinNum = $value['freespins'];
                                            }else if($slotEvent['slotEvent'] == 'freespin' && $spin_type == 'freespins'){
                                                $freeSpinNum = $value['freespins'];
                                            }   
                                        }  
                                        if(isset($value['amount']) && $value['amount'] > 0){
                                            $value['amount'] = str_replace(',', '', $value['amount'])* $mul * $betline * $DENOMINATOR;
                                        }
                                        $stack[$spin_type]['winscatters'][$index] = $value;
                                    }
                                }
                            }
                        }
                    }
                    if($moneyCount > 0){
                        $slotSettings->SetGameData($slotSettings->slotId . 'MoneyCount', $moneyCount);
                        
                    }
                    //$slotSettings->SetGameData($slotSettings->slotId . 'Hill', $currentHill);
                    if( $totalWin > 0) 
                    {
                        $spinType = 'c';
                        $slotSettings->SetBalance($totalWin);
                        $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'LastWin', $totalWin);
                    }
                    $BALANCE = $slotSettings->GetBalance();
                    if( $freeSpinNum > 0 ) 
                    {
                        
                        if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0 ) 
                        {
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freeSpinNum);
                            $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                        }
                        else
                        {
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $freeSpinNum);
                        }
                    }else if($stack['actions'] && $stack['actions'][0] == 'bonus_init'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', 3);
                    }
                    $objRes = [
                        'command' => $slotEvent['command'],
                        'context' => [
                            'achievements' => $slotSettings->GetAchievements($moneyCount),
                            'actions' => ['spin','buy_spin'],
                            'current' => 'spins',
                            'last_args' => [
                                'bet_per_line' => $betline * $DENOMINATOR,
                                'lines' => 1
                            ],
                            'last_win' => ($slotSettings->GetGameData($slotSettings->slotId . 'LastWin') ?? 0) * $DENOMINATOR,
                            'math_version' => 'a',
                            'round_finished' => true,
                            'version' => 2
                        ],
                        'modes' => ['auto', 'play', 'freebet'],
                        'origin_data' => [
                            'autogame' => $autogame,
                            'data' => [
                                'quick_spin' => $quick_spin
                            ],
                            'feature' => true,
                            'mobile' => $mobile,
                            'portrait' => $portrait,
                            'quick_spin' => $quick_spin,
                            'sound' => $sound
                        ],
                        'request_id' => $slotEvent['request_id'],
                        'session_id' => '68939e9a5d134e78bfd9993d4a2cc34e',
                        'status' => [
                            'code' => 'OK'
                        ],
                        'user' => [
                            'balance' => $BALANCE * 100,
                            'balance_version' => $slotSettings->GetGameData($slotSettings->slotId . 'BalanceVersion'),
                            'currency' => 'KRW',
                            'huid' => '969:major:17390:rKRW',
                            'show_balance' => true
                        ]
                    ];
                    $isEndFreeSpin = false;
                    $isState = true;
                    // if($stack['actions'][0] == 'bonus_init' && ((isset($stack['freespins']['bac_win']) && $stack['freespins']['bac_win']== true) || (isset($stack['spins']['bac_win']) && $stack['spins']['bac_win']== true))){
                    //     $slotSettings->SetGameData($slotSettings->slotId . 'Hill', [1, 0]);
                    // }
                    if($stack['bonus'] != ''){
                        $objRes['context']['bonus'] = $stack['bonus'];
                        //$objRes['context']['bonus']['bac'] = $currentHill;
                    }
                    if($stack['freespins'] != ''){
                        $objRes['context']['freespins'] = $stack['freespins'];
                        //$objRes['context']['freespins']['bac'] = $currentHill;
                    }
                    if($stack['spins'] != ''){
                        $objRes['context']['spins'] = $stack['spins'];
                        //$objRes['context']['spins']['bac'] = $currentHill;
                    }
                    $objRes['context']['actions'] = $stack['actions'];
                    if( $slotEvent['slotEvent'] == 'freespin' || $slotEvent['slotEvent'] == 'respin') 
                    {
                        $isState = false;
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                        
                        if($slotEvent['slotEvent'] == 'respin'){
                            $rounds_left = $stack['bonus']['rounds_left'];
                        }else{
                            $rounds_left = $stack['freespins']['rounds_left'];
                        }
                        if($slotEvent['slotEvent'] == 'freespin'){
                            $objRes['context']['current'] = 'freespins';
                            $objRes['context']['round_finished'] = false;
                            $objRes['context']['last_args'] = [];  
                            if($rounds_left == 0) 
                            {
                                $isEndFreeSpin = true;
                                $isState = true;
                            }
                        }else{
                            $objRes['context']['current'] = 'bonus';
                            $objRes['context']['round_finished'] = false;
                            $objRes['context']['last_args'] = [];
                            if($rounds_left == 0) 
                            {
                                if($totalFreeGames <= 0 || $totalFreeGames == $currentFreeGames){
                                    $isEndFreeSpin = true;
                                    $isState = true;
                                }
                            }
                        }
                    }else
                    {
                        // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                        
                        if($stack['actions'][0] == 'freespin_init' || $stack['actions'][0] == 'bonus_init'){
                            $objRes['context']['round_finished'] = false;
                            $isState = false;
                        }
                    }
                    if($isState == true && ($slotEvent['slotEvent'] == 'freespin' || $slotEvent['slotEvent'] == 'respin')){
                        if($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') > 0){
                            $slotSettings->SetGameData($slotSettings->slotId . 'LastWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'));
                            $objRes['last_win'] = ($slotSettings->GetGameData($slotSettings->slotId . 'LastWin') ?? 0) * $DENOMINATOR;
                        }
                    }
                }
                $objRes['context']['last_action'] = $action['name'];
                $allBet = $betline * $LINES;
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreespin') == 1){{
                    $allBet = $allBet * 75;
                }}else if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreespin') == 0){
                    $allBet = $allBet * 40;
                }
                $slotSettings->SaveLogReport(json_encode($objRes), $betline * $LINES, $LINES, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                if($action['name'] == 'spin' || $action['name'] == 'freespin' || $action['name'] == 'buy_spin' || $action['name'] == 'freespin_stop' || $action['name'] == 'respin' || $action['name'] == 'bonus_spins_stop' || $action['name'] == 'bonus_freespins_stop'){
                    if($action['name'] == 'spin'){
                        $this->SaveBNGLogParse($beforeBalance, $BALANCE, $allBet, $totalWin, $objRes, $slotSettings);
                    }else if($action['name'] == 'buy_spin'){
                        $this->SaveBNGLogParse($beforeBalance, $BALANCE, $allBet, $totalWin, $objRes, $slotSettings);
                    }else{
                        $this->SaveBNGLogParse($beforeBalance, $BALANCE, NULL, $totalWin, $objRes, $slotSettings);
                    }
                }
            }
            $slotSettings->SetGameData($slotSettings->slotId . 'Counter', $Counter);
            $slotSettings->SaveGameData();
            \DB::commit();
            return json_encode($objRes);
        }
        public function SaveBNGLogParse($beforeBalance, $currentBalance, $allBet, $win, $objRes, $slotSettings){
            
            $profit = ($allBet ?? 0) - $win;
            $context = $objRes['context'];
            if(isset($context['bonus'])){
                $spins = $context['bonus'];
                $state = 'bonus';
            }else if(isset($context['freespins'])){
                $spins = $context['freespins'];
                $state = 'freespins';
            }else if(isset($context['spins'])){
                $spins = $context['spins'];
                $state = 'spins';
            }else{
                // throw error
                return;
            }
            $round_finished = $context['round_finished'];
            $log = [
                'context' => $spins,
                'last_action' => $context['last_action'],
                'math_version' => $context['math_version'],
                'round_bet' => $spins['round_bet'],
                'round_finished' => $round_finished,
                'round_started' => false,
                'round_win' => $spins['round_win'],
                "state"=> $state,
                "version" => $context['version'],
            ];
            $data = [
                'type' => 'COMMIT',
                'transaction_id' => str_random(33),
                'player_id' => $slotSettings->playerId,
                'brand' => 'major',
                'tag' => '',
                'game_id' => $slotSettings->game->original_id,
                'game_name' => $slotSettings->game->name,
                'currency' => 'KRW',
                'mode' => 'REAL',
                'bet' => $allBet,
                'win' => $win,
                'profit' => $profit,
                'outcome' => -1 * $profit,
                'round_id' => $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') ?? 0,
                'round_started' => false,
                'round_finished' => $round_finished,
                'balance_before' => $beforeBalance,
                'balance_after' => $currentBalance,
                'status' => 'OK',
                'exceed_code' => '',
                'exceed_message' => '',
                'is_bonus' => false,
                'c_at' => date('Y-m-d H:i:s'),
                'log' => json_encode($log)
            ];
            $slotSettings->SaveBNGLogReport($data);
        }
    }

}

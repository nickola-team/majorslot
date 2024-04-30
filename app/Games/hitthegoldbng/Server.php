<?php 
namespace VanguardLTE\Games\hitthegoldbng
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
            $LINES = 25;
            
            $WILD = 12;
            $SCATTER = 13;

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
                        'actions' => ['spin'],
                        'current' => 'spins',
                        'last_args' => [
                            'bet_per_line' => $bets[6],
                            'lines' => $LINES,
                        ],
                        'last_win' => ($slotSettings->GetGameData($slotSettings->slotId . 'LastWin') ?? 0) * $DENOMINATOR,
                        'math_version' => 'a',
                        'round_finished' => true,
                        'spins' => [
                            'bet_per_line' => $bets[6],
                            'board' => [[11, 11, 4], [1, 8, 8], [9, 9, 9], [9, 9, 9], [11, 11, 11]],
                            'bs_v' => [[10 * $bets[6] * $LINES, "major", 0], [0, 0, 0], [0, 0, 0], [0, 0, 0], ["minor", 5 * $bets[6] * $LINES, 3 * $bets[6] * $LINES]],
                            'bs_values' => [[10, 150, 0], [0, 0, 0], [0, 0, 0], [0, 0, 0], [50, 5, 3]],
                            'hill' => [2, 0],
                            'lines' => $LINES,
                            'round_bet' => $bets[6] * $LINES,
                            'round_win' => 0,
                            'total_win' => 0,
                        ],
                        'version' => 2
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
                    'settings' => json_decode('{"authenticity_link": "/booongo/verify?game_id='. $slotSettings->game->label .'","bet_factor": [25], "bets": ['. implode(',', $bets) .'], "big_win": [30, 50, 70], "bonus_symbols": [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 14, 16, "mini", "minor", "major"], "cols": 5, "currency_format": {"currency_style": "symbol", "denominator": 100, "style": "money"}, "fs_retrigger": 8, "jackpots": {"grand": 2000, "major": 150, "mini": 20, "minor": 50}, "lines": [25], "paylines": [[1, 1, 1, 1, 1], [0, 0, 0, 0, 0], [2, 2, 2, 2, 2], [0, 1, 2, 1, 0], [2, 1, 0, 1, 2], [1, 0, 0, 0, 1], [1, 2, 2, 2, 1], [0, 0, 1, 2, 2], [2, 2, 1, 0, 0], [1, 2, 1, 0, 1], [1, 0, 1, 2, 1], [0, 1, 1, 1, 0], [2, 1, 1, 1, 2], [0, 1, 0, 1, 0], [2, 1, 2, 1, 2], [1, 1, 0, 1, 1], [1, 1, 2, 1, 1], [0, 0, 2, 0, 0], [2, 2, 0, 2, 2], [0, 2, 2, 2, 0], [2, 0, 0, 0, 2], [1, 2, 0, 2, 1], [1, 0, 2, 0, 1], [0, 2, 0, 2, 0], [2, 0, 2, 0, 2]], "paytable": {"1": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 10, "occurrences": 4, "type": "lb"}, {"multiplier": 50, "occurrences": 5, "type": "lb"}], "2": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 10, "occurrences": 4, "type": "lb"}, {"multiplier": 50, "occurrences": 5, "type": "lb"}], "3": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 10, "occurrences": 4, "type": "lb"}, {"multiplier": 50, "occurrences": 5, "type": "lb"}], "4": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 10, "occurrences": 4, "type": "lb"}, {"multiplier": 50, "occurrences": 5, "type": "lb"}], "5": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 25, "occurrences": 4, "type": "lb"}, {"multiplier": 100, "occurrences": 5, "type": "lb"}], "6": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 30, "occurrences": 4, "type": "lb"}, {"multiplier": 150, "occurrences": 5, "type": "lb"}], "7": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 40, "occurrences": 4, "type": "lb"}, {"multiplier": 200, "occurrences": 5, "type": "lb"}], "8": [{"multiplier": 15, "occurrences": 3, "type": "lb"}, {"multiplier": 50, "occurrences": 4, "type": "lb"}, {"multiplier": 250, "occurrences": 5, "type": "lb"}], "9": [{"multiplier": 20, "occurrences": 3, "type": "lb"}, {"multiplier": 60, "occurrences": 4, "type": "lb"}, {"multiplier": 300, "occurrences": 5, "type": "lb"}], "10": [{"freespins": 8, "multiplier": 1, "occurrences": 3, "trigger": "freespins", "type": "tb"}]}, "reelsamples": {"freespins": [[5, 6, 7, 8, 9, 11], [5, 6, 7, 8, 9, 10, 11], [5, 6, 7, 8, 9, 10, 11], [5, 6, 7, 8, 9, 10, 11], [5, 6, 7, 8, 9, 11]], "last_freespins": [[5, 6, 7, 8, 9], [5, 6, 7, 8, 9, 10], [5, 6, 7, 8, 9, 10], [5, 6, 7, 8, 9, 10], [5, 6, 7, 8, 9]], "spins": [[1, 2, 3, 4, 5, 6, 7, 8, 9, 11], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], [1, 2, 3, 4, 5, 6, 7, 8, 9, 11]]}, "respins_granted": 3, "rows": 3, "symbols": [{"id": 1, "name": "el_01", "type": "line"}, {"id": 2, "name": "el_02", "type": "line"}, {"id": 3, "name": "el_03", "type": "line"}, {"id": 4, "name": "el_04", "type": "line"}, {"id": 5, "name": "el_05", "type": "line"}, {"id": 6, "name": "el_06", "type": "line"}, {"id": 7, "name": "el_07", "type": "line"}, {"id": 8, "name": "el_08", "type": "line"}, {"id": 9, "name": "el_wild", "type": "wild"}, {"id": 10, "name": "el_scatter", "type": "scat"}, {"id": 11, "name": "el_bonus_1", "type": "scat"}, {"id": 12, "name": "el_bonus_2", "type": "scat"}], "symbols_line": [1, 2, 3, 4, 5, 6, 7, 8], "symbols_scat": [10, 11, 12], "symbols_wild": [9]}'),
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
                
                $totalWin = 0;
                if($action['name'] == 'spin'){
                    $betline = $action['params']['bet_per_line'] / $DENOMINATOR;
                    $slotEvent['slotEvent'] = 'bet';
                }else if($action['name'] == 'freespin_init' || $action['name'] == 'freespin' || $action['name'] == 'freespin_stop'){
                    $betline = $slotSettings->GetGameData($slotSettings->slotId . 'Bet') ?? $slotSettings->Bet[6];
                    $slotEvent['slotEvent'] = 'freespin';
                }else if($action['name'] == 'bonus_init' || $action['name'] == 'respin' || $action['name'] == 'bonus_spins_stop' || $action['name'] == 'bonus_freespins_stop'){
                    $betline = $slotSettings->GetGameData($slotSettings->slotId . 'Bet') ?? $slotSettings->Bet[6];
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
                $currentHill = $slotSettings->GetGameData($slotSettings->slotId . 'Hill') ?? [2, 0];
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
                    
                    if($action['name'] == 'bonus_init'){
                        $currentHill = [1, 0];
                        $slotSettings->SetGameData($slotSettings->slotId . 'Hill', $currentHill);
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
                                    $stack[$spin_type]['round_win'] = str_replace(',', '', $stack[$spin_type]['round_win']) * $betline * $DENOMINATOR;
                                }
                                if(isset($stack[$spin_type]['total_win'])){
                                    $stack[$spin_type]['total_win'] = str_replace(',', '', $stack[$spin_type]['total_win']) * $betline * $DENOMINATOR;
                                }
                                $stack[$spin_type]['hill'] = $currentHill;
                                if(isset($stack[$spin_type]['new_bs']) && count($stack[$spin_type]['new_bs']) > 0){
                                    foreach( $stack[$spin_type]['new_bs'] as $index => $value ){
                                        if(isset($value['value']) && $value['value'] > 0){
                                            $value['value'] = str_replace(',', '', $value['value']) * $betline * $DENOMINATOR;
                                            $stack[$spin_type]['new_bs'][$index] = $value;
                                        }
                                    }
                                }
                                if(isset($stack[$spin_type]['bs_v'])){
                                    for($i = 0; $i < count($stack[$spin_type]['bs_v']); $i++){
                                        for($j = 0; $j < 3; $j++){
                                            if(is_numeric(str_replace(',', '', $stack[$spin_type]['bs_v'][$i][$j]))){
                                                $stack[$spin_type]['bs_v'][$i][$j] = str_replace(',', '', $stack[$spin_type]['bs_v'][$i][$j]) * $betline * $DENOMINATOR;
                                            }
                                            // if(is_numeric(str_replace(',', '', $stack[$spin_type]['bs_values'][$i][$j]))){
                                            //     $stack[$spin_type]['bs_values'][$i][$j] = str_replace(',', '', $stack[$spin_type]['bs_values'][$i][$j]) * $betline * $DENOMINATOR;
                                            // }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $objRes = [
                        'command' => $slotEvent['command'],
                        'context' => [
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
                                'actions' => ['spin'],
                                'current' => 'spins',
                                'last_args' => [],
                                'last_win' => ($slotSettings->GetGameData($slotSettings->slotId . 'LastWin') ?? 0) * $DENOMINATOR,
                                'math_version' => 'a',
                                'round_finished' => true,
                                'spins' => [
                                    'bet_per_line' => $betline * $DENOMINATOR,
                                    'board' => [[11, 11, 4], [1, 8, 8], [9, 9, 9], [9, 9, 9], [11, 11, 11]],
                                    'bs_v' => [[10 * $betline * $DENOMINATOR * $LINES, "major", 0], [0, 0, 0], [0, 0, 0], [0, 0, 0], ["minor", 5 * $betline * $DENOMINATOR * $LINES, 3 * $betline * $DENOMINATOR * $LINES]],
                                    'bs_values' => [[10, 150, 0], [0, 0, 0], [0, 0, 0], [0, 0, 0], [50, 5, 3]],
                                    'hill' => [2, 0],
                                    'lines' => $LINES,
                                    'round_bet' => $betline * $LINES * $DENOMINATOR,
                                    'round_win' => 0,
                                    'total_win' => 0,
                                ],
                                'version' => 2
                            ],
                            'modes' => ['auto', 'play'],
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
                    // $winType = 'win';
                    // $_winAvaliableMoney = 1000;
                    $isDoubleScatter = false;
                    if($slotEvent['slotEvent'] != 'freespin' && $slotEvent['slotEvent'] != 'respin'){
                        $slotSettings->SetBalance(-1 * ($betline * $LINES), $slotEvent['slotEvent']);
                        $_sum = ($betline * $LINES) / 100 * $slotSettings->GetPercent();
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

                        $tumbAndFreeStacks = $slotSettings->GetReelStrips($winType, ($betline * $LINES));
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $tumbAndFreeStacks);

                        $totalSpinCount = 0;
                        
                        $roundstr = sprintf('%.4f', microtime(TRUE));
                        $roundstr = str_replace('.', '', $roundstr);
                        $roundstr = '513' . substr($roundstr, 4, 10) . '001';
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
                    $moneyCount = 0;
                    for($k = 0; $k < 3; $k++){
                        $spin_type = $spin_types[$k];
                        if($stack[$spin_type] != ''){
                            $stack[$spin_type]['bet_per_line'] = $betline * $DENOMINATOR;
                            $stack[$spin_type]['round_bet'] = $betline * $DENOMINATOR * $LINES;
                            $totalWin = 0;
                            if(isset($stack[$spin_type]['round_win'])){
                                $totalWin = $stack[$spin_type]['round_win'] * $betline;
                                $stack[$spin_type]['round_win'] = str_replace(',', '', $stack[$spin_type]['round_win']) * $betline * $DENOMINATOR;
                            }
                            if(isset($stack[$spin_type]['total_win'])){
                                $stack[$spin_type]['total_win'] = str_replace(',', '', $stack[$spin_type]['total_win']) * $betline * $DENOMINATOR;
                            }
                            if(isset($stack[$spin_type]['new_bs']) && count($stack[$spin_type]['new_bs']) > 0){
                                foreach( $stack[$spin_type]['new_bs'] as $index => $value ){
                                    if(isset($value['value']) && $value['value'] > 0){
                                        $value['value'] = str_replace(',', '', $value['value']) * $betline * $DENOMINATOR;
                                        $stack[$spin_type]['new_bs'][$index] = $value;
                                    }
                                }
                            }  
                            if($slotEvent['slotEvent'] != 'respin' && $spin_types[$k] != 'bonus' && isset($stack[$spin_type]['board'])){
                                $moneyCount = 0;
                                for($i = 0; $i < 5; $i++){
                                    for($j = 0; $j < 3; $j++){
                                        if($stack[$spin_type]['board'][$i][$j] == 11){
                                            $moneyCount++;
                                        }
                                    }
                                }
                            }   
                            if(isset($stack[$spin_type]['bs_v'])){
                                for($i = 0; $i < count($stack[$spin_type]['bs_v']); $i++){
                                    for($j = 0; $j < 3; $j++){
                                        if(is_numeric(str_replace(',', '', $stack[$spin_type]['bs_v'][$i][$j]))){
                                            $stack[$spin_type]['bs_v'][$i][$j] = str_replace(',', '', $stack[$spin_type]['bs_v'][$i][$j]) * $betline * $DENOMINATOR;
                                        }
                                        if(isset($stack[$spin_type]['old_bs_v'])){
                                            if(is_numeric(str_replace(',', '', $stack[$spin_type]['old_bs_v'][$i][$j]))){
                                                $stack[$spin_type]['old_bs_v'][$i][$j] = str_replace(',', '', $stack[$spin_type]['old_bs_v'][$i][$j]) * $betline * $DENOMINATOR;
                                            }
                                        }
                                    }
                                }
                            }
                            if($spin_type != 'bonus'){
                                if(isset($stack[$spin_type]['winlines']) && count($stack[$spin_type]['winlines']) > 0){
                                    foreach( $stack[$spin_type]['winlines'] as $index => $value ){
                                        if(isset($value['amount']) && $value['amount'] > 0){
                                            $value['amount'] = str_replace(',', '', $value['amount']) * $betline * $DENOMINATOR;
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
                                            $value['amount'] = str_replace(',', '', $value['amount']) * $betline * $DENOMINATOR;
                                        }
                                        $stack[$spin_type]['winscatters'][$index] = $value;
                                    }
                                }
                            }
                        }
                    }
                    if($moneyCount > 0){
                        $currentHill[1]++;
                        if($currentHill[1] >= 10){
                            if($currentHill[0] < 5){
                                $currentHill[0]++;
                                $currentHill[1] = 0;
                            }else{
                                $currentHill[0] = 5;
                                $currentHill[1] = 9;
                            }
                        }
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'Hill', $currentHill);
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
                            'actions' => ['spin'],
                            'current' => 'spins',
                            'last_args' => [
                                'bet_per_line' => $betline * $DENOMINATOR,
                                'lines' => $LINES
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
                    if($stack['bonus'] != ''){
                        $objRes['context']['bonus'] = $stack['bonus'];
                        $objRes['context']['bonus']['hill'] = $currentHill;
                    }
                    if($stack['freespins'] != ''){
                        $objRes['context']['freespins'] = $stack['freespins'];
                        $objRes['context']['freespins']['hill'] = $currentHill;
                    }
                    if($stack['spins'] != ''){
                        $objRes['context']['spins'] = $stack['spins'];
                        $objRes['context']['spins']['hill'] = $currentHill;
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
                $slotSettings->SaveLogReport(json_encode($objRes), $betline * $LINES, $LINES, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                if($action['name'] == 'spin' || $action['name'] == 'freespin' || $action['name'] == 'freespin_stop' || $action['name'] == 'respin' || $action['name'] == 'bonus_spins_stop' || $action['name'] == 'bonus_freespins_stop'){
                    if($action['name'] == 'spin'){
                        $this->SaveBNGLogParse($beforeBalance, $BALANCE, $betline * $LINES, $totalWin, $objRes, $slotSettings);
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

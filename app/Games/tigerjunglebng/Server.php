<?php 
namespace VanguardLTE\Games\tigerjunglebng
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
                            'bet_per_line' => $bets[5],
                            'lines' => $LINES,
                        ],
                        'last_win' => ($slotSettings->GetGameData($slotSettings->slotId . 'LastWin') ?? 0) * $DENOMINATOR,
                        'math_version' => 'a',
                        'round_finished' => true,
                        'spins' => [
                            'bet_per_line' => $bets[5],
                            'board' => [[11, 2, 7], [3, 10, 3], [9, 9, 9], [1, 9, 9], [11, 11, 11]],
                            'bs_v' => [["mini", 0, 0], [0, 0, 0], [0, 0, 0], [0, 0, 0], [7500 * $DENOMINATOR, "major", 17500 * $DENOMINATOR]],
                            'bs_values' => [[20 * $bets[5] * $DENOMINATOR, 0, 0], [0, 0, 0], [0, 0, 0], [0, 0, 0], [3 * $bets[5] * $DENOMINATOR, 150 * $bets[5] * $DENOMINATOR, 7 * $bets[5] * $DENOMINATOR]],
                            'hill' => [0, 0],
                            'lines' => $LINES,
                            'round_bet' => $bets[5] * $LINES,
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
                    'settings' => json_decode('{"authenticity_link": "/booongo/verify?game_id='. $slotSettings->game->label .'","bet_factor": [25], "bets": ['. implode(',', $bets) .'], "big_win": [30, 50, 70], "bonus_symbols": [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, "mini", "minor", "major"], "cols": 5, "currency_format": {"currency_style": "symbol", "denominator": 100, "style": "money"}, "fs_retrigger": 5, "hill_spins_count": {"0": 3, "1": 7, "2": 15, "3": 25, "4": 25}, "jackpots": {"grand": 5000, "major": 150, "mini": 20, "minor": 50}, "lines": [25], "paylines": [[1, 1, 1, 1, 1], [0, 0, 0, 0, 0], [2, 2, 2, 2, 2], [0, 1, 2, 1, 0], [2, 1, 0, 1, 2], [1, 0, 0, 0, 1], [1, 2, 2, 2, 1], [0, 0, 1, 2, 2], [2, 2, 1, 0, 0], [1, 2, 1, 0, 1], [1, 0, 1, 2, 1], [0, 1, 1, 1, 0], [2, 1, 1, 1, 2], [0, 1, 0, 1, 0], [2, 1, 2, 1, 2], [1, 1, 0, 1, 1], [1, 1, 2, 1, 1], [0, 0, 2, 0, 0], [2, 2, 0, 2, 2], [0, 2, 2, 2, 0], [2, 0, 0, 0, 2], [1, 2, 0, 2, 1], [1, 0, 2, 0, 1], [0, 2, 0, 2, 0], [2, 0, 2, 0, 2]], "paytable": {"1": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 10, "occurrences": 4, "type": "lb"}, {"multiplier": 50, "occurrences": 5, "type": "lb"}], "2": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 10, "occurrences": 4, "type": "lb"}, {"multiplier": 50, "occurrences": 5, "type": "lb"}], "3": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 10, "occurrences": 4, "type": "lb"}, {"multiplier": 50, "occurrences": 5, "type": "lb"}], "4": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 10, "occurrences": 4, "type": "lb"}, {"multiplier": 50, "occurrences": 5, "type": "lb"}], "5": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 20, "occurrences": 4, "type": "lb"}, {"multiplier": 100, "occurrences": 5, "type": "lb"}], "6": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 30, "occurrences": 4, "type": "lb"}, {"multiplier": 150, "occurrences": 5, "type": "lb"}], "7": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 40, "occurrences": 4, "type": "lb"}, {"multiplier": 200, "occurrences": 5, "type": "lb"}], "8": [{"multiplier": 15, "occurrences": 3, "type": "lb"}, {"multiplier": 50, "occurrences": 4, "type": "lb"}, {"multiplier": 250, "occurrences": 5, "type": "lb"}], "9": [{"multiplier": 20, "occurrences": 3, "type": "lb"}, {"multiplier": 60, "occurrences": 4, "type": "lb"}, {"multiplier": 300, "occurrences": 5, "type": "lb"}], "10": [{"freespins": 8, "multiplier": 1, "occurrences": 3, "trigger": "freespins", "type": "tb"}, {"freespins": 8, "multiplier": 10, "occurrences": 4, "trigger": "freespins", "type": "tb"}, {"freespins": 8, "multiplier": 100, "occurrences": 5, "trigger": "freespins", "type": "tb"}]}, "reelsamples": {"freespins": [[1, 2, 3, 4, 5, 6, 7, 8, 9], [1, 2, 3, 4, 5, 6, 7, 8, 9], [1, 2, 3, 4, 5, 6, 7, 8, 9], [1, 2, 3, 4, 5, 6, 7, 8, 9], [1, 2, 3, 4, 5, 6, 7, 8, 9]], "spins": [[1, 2, 3, 4, 5, 6, 7, 8, 11, 10, 9], [1, 2, 3, 4, 5, 6, 7, 8, 11, 10, 9], [1, 2, 3, 4, 5, 6, 7, 8, 11, 10, 9], [1, 2, 3, 4, 5, 6, 7, 8, 11, 10, 9], [1, 2, 3, 4, 5, 6, 7, 8, 11, 10, 9]]}, "respins_granted": 3, "rows": 3, "small_win": 5, "spec_sym_bagvalue": {"1": [9], "2": [9, 9], "3": [9, 9, 9], "4": [11], "5": [11, 11], "6": [11, 11, 11], "7": [10]}, "symbols": [{"id": 1, "name": "el_01", "type": "line"}, {"id": 2, "name": "el_02", "type": "line"}, {"id": 3, "name": "el_03", "type": "line"}, {"id": 4, "name": "el_04", "type": "line"}, {"id": 5, "name": "el_05", "type": "line"}, {"id": 6, "name": "el_06", "type": "line"}, {"id": 7, "name": "el_07", "type": "line"}, {"id": 8, "name": "el_08", "type": "line"}, {"id": 9, "name": "el_wild", "type": "wild"}, {"id": 10, "name": "el_scatter", "type": "scat"}, {"id": 11, "name": "el_bonus", "type": "scat"}, {"id": 12, "name": "el_collect", "type": "scat"}], "symbols_line": [1, 2, 3, 4, 5, 6, 7, 8], "symbols_scat": [10, 11, 12], "symbols_wild": [9], "version": "a"}'),
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
                    $betline = $slotSettings->GetGameData($slotSettings->slotId . 'Bet') ?? $slotSettings->Bet[5];
                    $slotEvent['slotEvent'] = 'freespin';
                }else if($action['name'] == 'bonus_init' || $action['name'] == 'respin' || $action['name'] == 'bonus_spins_stop'){
                    $betline = $slotSettings->GetGameData($slotSettings->slotId . 'Bet') ?? $slotSettings->Bet[5];
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
                $currentHill = $slotSettings->GetGameData($slotSettings->slotId . 'Hill') ?? [0, 0];
                $stack = null;
                $isState = false;
                $is_extra_feature = false;
                if($action['name'] == 'freespin_init' || $action['name'] == 'freespin_stop' || $action['name'] == 'bonus_init' || $action['name'] == 'bonus_spins_stop' || $action['name'] == 'bonus_spins_stop' || $action['name'] == 'bonus_freespins_stop'){
                    if(count($tumbAndFreeStacks) > 0 && isset($tumbAndFreeStacks[$totalSpinCount])){
                        $stack = $tumbAndFreeStacks[$totalSpinCount];
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $totalSpinCount + 1);
                    }else{
                        return '';
                    }
                    $Counter = 0;
                    $stack['spins']['bet_per_line'] = $betline * $DENOMINATOR;
                    $stack['spins']['round_bet'] = $betline * $DENOMINATOR * $LINES;
                    $stack['spins']['round_win'] = str_replace(',', '', $stack['spins']['round_win']) * $betline * $DENOMINATOR;
                    $stack['spins']['hill'] = $currentHill;
                    if(isset($stack['spins']['total_win'])){
                        $stack['spins']['total_win'] = str_replace(',', '', $stack['spins']['total_win']) * $betline * $DENOMINATOR;
                    }
                    if($stack['spins']['bs_v'] && $stack['spins']['bs_values']){
                        for($i = 0; $i < 5; $i++){
                            for($j = 0; $j < 3; $j++){
                                if(is_numeric(str_replace(',', '', $stack['spins']['bs_v'][$i][$j]))){
                                    $stack['spins']['bs_v'][$i][$j] = str_replace(',', '', $stack['spins']['bs_v'][$i][$j]) * $betline * $DENOMINATOR;
                                }
                                if(is_numeric(str_replace(',', '', $stack['spins']['bs_values'][$i][$j]))){
                                    $stack['spins']['bs_values'][$i][$j] = str_replace(',', '', $stack['spins']['bs_values'][$i][$j]) * $betline * $DENOMINATOR;
                                }
                            }
                        }
                    }
                    if($stack['bonus'] != ''){
                        $stack['bonus']['bet_per_line'] = $betline * $DENOMINATOR;
                        $stack['bonus']['round_bet'] = $betline * $DENOMINATOR * $LINES;
                        $stack['bonus']['round_win'] = str_replace(',', '', $stack['bonus']['round_win']) * $betline * $DENOMINATOR;
                        $stack['bonus']['total_win'] = str_replace(',', '', $stack['bonus']['total_win']) * $betline * $DENOMINATOR;
                        $stack['bonus']['hill'] = $currentHill;
                        if($stack['bonus']['bs_v'] && $stack['bonus']['bs_values']){
                            for($i = 0; $i < 5; $i++){
                                for($j = 0; $j < 3; $j++){                                    
                                    if(is_numeric(str_replace(',', '', $stack['bonus']['bs_v'][$i][$j]))){
                                        $stack['bonus']['bs_v'][$i][$j] = str_replace(',', '', $stack['bonus']['bs_v'][$i][$j]) * $betline * $DENOMINATOR;
                                    }
                                    if(is_numeric(str_replace(',', '', $stack['bonus']['bs_values'][$i][$j]))){
                                        $stack['bonus']['bs_values'][$i][$j] = str_replace(',', '', $stack['bonus']['bs_values'][$i][$j]) * $betline * $DENOMINATOR;
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
                    if($action['name'] == 'freespin_init'){
                        $objRes['context']['actions'] = ['freespin'];
                        $objRes['context']['current'] = 'freespins';
                        $objRes['context']['freespins'] = $stack['spins'];
                    }else if($action['name'] == 'freespin_stop'){
                        $objRes['context']['actions'] = ['spin'];
                        $objRes['context']['current'] = 'spins';
                        $objRes['context']['spins'] = $stack['spins'];
                    }else if($action['name'] == 'bonus_init'){
                        $objRes['context']['actions'] = ['respin'];
                        $objRes['context']['current'] = 'bonus';
                        $objRes['context']['bonus'] = $stack['bonus'];
                        if($totalFreeGames > 0){
                            $objRes['context']['freespins'] = $stack['spins'];
                        }else{
                            $objRes['context']['spins'] = $stack['spins'];
                        }
                    }else if($action['name'] == 'bonus_spins_stop'){
                        if($totalFreeGames <= 0){
                            $objRes['context']['actions'] = ['spin'];
                            $objRes['context']['current'] = 'spins';
                            $objRes['context']['spins'] = $stack['spins'];
                        }else if($totalFreeGames - $currentFreeGames <= 0){
                            $objRes['context']['actions'] = ['freespin_stop'];
                            $objRes['context']['current'] = 'freespins';
                            $objRes['context']['freespins'] = $stack['spins'];
                        }else{
                            $objRes['context']['actions'] = ['freespin'];
                            $objRes['context']['current'] = 'freespins';
                            $objRes['context']['freespins'] = $stack['spins'];
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
                                    'board' => [[11, 2, 7], [3, 10, 3], [9, 9, 9], [1, 9, 9], [11, 11, 11]],
                                    'bs_v' => [["mini", 0, 0], [0, 0, 0], [0, 0, 0], [0, 0, 0], [7500 * $DENOMINATOR, "major", 17500 * $DENOMINATOR]],
                                    'bs_values' => [[20 * $betline * $DENOMINATOR, 0, 0], [0, 0, 0], [0, 0, 0], [0, 0, 0], [3 * $betline * $DENOMINATOR, 150 * $betline * $DENOMINATOR, 7 * $betline * $DENOMINATOR]],
                                    'hill' => [0, 0],
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

                    $stack['spins']['bet_per_line'] = $betline * $DENOMINATOR;
                    $stack['spins']['round_bet'] = $betline * $DENOMINATOR * $LINES;
                    $totalWin = $stack['spins']['round_win'] * $betline;
                    $stack['spins']['round_win'] = str_replace(',', '', $stack['spins']['round_win']) * $betline * $DENOMINATOR;
                    if(isset($stack['spins']['total_win'])){
                        $stack['spins']['total_win'] = str_replace(',', '', $stack['spins']['total_win']) * $betline * $DENOMINATOR;                    
                    }
                    $board = $stack['spins']['board'];
                    if($stack['spins']['bs_v'] && $stack['spins']['bs_values']){
                        for($i = 0; $i < 5; $i++){
                            for($j = 0; $j < 3; $j++){
                                if(is_numeric(str_replace(',', '', $stack['spins']['bs_v'][$i][$j]))){
                                    $stack['spins']['bs_v'][$i][$j] = str_replace(',', '', $stack['spins']['bs_v'][$i][$j]) * $betline * $DENOMINATOR;
                                }
                                if(is_numeric(str_replace(',', '', $stack['spins']['bs_values'][$i][$j]))){
                                    $stack['spins']['bs_values'][$i][$j] = str_replace(',', '', $stack['spins']['bs_values'][$i][$j]) * $betline * $DENOMINATOR;
                                }
                            }
                        }
                    }
                    if($stack['bonus'] != ''){
                        $stack['bonus']['bet_per_line'] = $betline * $DENOMINATOR;
                        $stack['bonus']['round_bet'] = $betline * $DENOMINATOR * $LINES;
                        $totalWin = $stack['bonus']['round_win'] * $betline;
                        $stack['bonus']['round_win'] = str_replace(',', '', $stack['bonus']['round_win']) * $betline * $DENOMINATOR;
                        $stack['bonus']['total_win'] = str_replace(',', '', $stack['bonus']['total_win']) * $betline * $DENOMINATOR;
                        if($stack['bonus']['bs_v'] && $stack['bonus']['bs_values']){
                            for($i = 0; $i < 5; $i++){
                                for($j = 0; $j < 3; $j++){
                                    if(is_numeric(str_replace(',', '', $stack['bonus']['bs_v'][$i][$j]))){
                                        $stack['bonus']['bs_v'][$i][$j] = str_replace(',', '', $stack['bonus']['bs_v'][$i][$j]) * $betline * $DENOMINATOR;
                                    }
                                    if(is_numeric(str_replace(',', '', $stack['bonus']['bs_values'][$i][$j]))){
                                        $stack['bonus']['bs_values'][$i][$j] = str_replace(',', '', $stack['bonus']['bs_values'][$i][$j]) * $betline * $DENOMINATOR;
                                    }
                                }
                            }
                        }
                        $board = $stack['bonus']['board'];
                    }

                    $scattersCount = 0;
                    $moneyCount = 0;
                    for($i = 0; $i < 5; $i++){
                        for($j = 0; $j < 3; $j++){
                            if($board[$i][$j] == 11){
                                $moneyCount++;
                            }else if($board[$i][$j] == 10){
                                $scattersCount++;
                            }
                        }
                    }
                    $freeSpinNum = 0;
                    $respinNum = 0;
                    if($slotEvent['slotEvent'] != 'respin'){
                        if(isset($stack['spins']['winlines']) && count($stack['spins']['winlines']) > 0){
                            foreach( $stack['spins']['winlines'] as $index => $value ){
                                if(isset($value['amount']) && $value['amount'] > 0){
                                    $value['amount'] = str_replace(',', '', $value['amount']) * $betline * $DENOMINATOR;
                                    $stack['spins']['winlines'][$index] = $value;
                                }
                            }
                        }
                        if(isset($stack['spins']['winscatters']) && count($stack['spins']['winscatters']) > 0){
                            foreach( $stack['spins']['winscatters'] as $index => $value ){
                                if(isset($value['freespins'])){
                                    $freeSpinNum = $value['freespins'];
                                }
                                if(isset($value['amount']) && $value['amount'] > 0){
                                    $value['amount'] = str_replace(',', '', $value['amount']) * $betline * $DENOMINATOR;
                                }
                                $stack['spins']['winscatters'][$index] = $value;
                            }
                        }
                        if($slotEvent['slotEvent'] != 'respin' && $moneyCount >= 6){
                            $respinNum = 3;
                        }
                    }
                    
                    if($moneyCount > 0 && $stack['bonus'] == ''){
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
                    }else if($respinNum > 0){
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', $respinNum);
                        $slotSettings->SetGameData($slotSettings->slotId . 'Hill', [0, 0]);
                    }
                    $stack['spins']['hill'] = $currentHill;
                    if($stack['bonus'] != ''){
                        $stack['bonus']['hill'] = $currentHill;
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
                    if( $slotEvent['slotEvent'] == 'freespin' || $slotEvent['slotEvent'] == 'respin') 
                    {
                        $isState = false;
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                        
                        if($slotEvent['slotEvent'] == 'respin'){
                            $rounds_left = $stack['bonus']['rounds_left'];
                        }else{
                            $rounds_left = $stack['spins']['rounds_left'];
                        }
                        if($slotEvent['slotEvent'] == 'freespin'){
                            $objRes['context']['current'] = 'freespins';
                            $objRes['context']['round_finished'] = false;
                            $objRes['context']['last_args'] = [];
                            $objRes['context']['freespins'] = $stack['spins'];                            
                            if($respinNum > 0){
                                $objRes['context']['actions'] = ['bonus_init'];
                            }
                            else if($rounds_left == 0) 
                            {
                                $isEndFreeSpin = true;
                                $objRes['context']['actions'] = ['freespin_stop'];
                                $isState = true;
                            }
                            else
                            {
                                $objRes['context']['actions'] = ['freespin'];
                            }
                        }else{
                            $objRes['context']['current'] = 'bonus';
                            $objRes['context']['round_finished'] = false;
                            $objRes['context']['last_args'] = [];
                            if($totalFreeGames > 0){
                                $objRes['context']['freespins'] = $stack['spins'];
                            }else{
                                $objRes['context']['spins'] = $stack['spins'];
                            }
                            $objRes['context']['bonus'] = $stack['bonus'];
                            if($rounds_left == 0) 
                            {
                                $objRes['context']['actions'] = ['bonus_spins_stop'];
                                if($totalFreeGames <= 0 || $totalFreeGames == $currentFreeGames){
                                    $isEndFreeSpin = true;
                                    $isState = true;
                                }
                            }
                            else
                            {
                                $objRes['context']['actions'] = ['respin'];
                            }
                        }
                    }else
                    {
                        // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                        
                        $objRes['context']['spins'] = $stack['spins'];
                        if($freeSpinNum > 0 ){
                            $objRes['context']['actions'] = ['freespin_init'];
                            $objRes['context']['round_finished'] = false;
                            $isState = false;
                        }else if($respinNum > 0){
                            $objRes['context']['actions'] = ['bonus_init'];
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
                if($action['name'] == 'spin' || $action['name'] == 'freespin' || $action['name'] == 'freespin_stop' || $action['name'] == 'respin' || $action['name'] == 'bonus_spins_stop'){
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
                'game_name' => 'tigerjunglebng',
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

<?php 
namespace VanguardLTE\Games\bookofsunchoicebng
{
    include('CheckReels.php');
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
            $LINES = 10;
            
            $WILD = 10;
            $SCATTER = 10;
            $MONEY = 11;

            $Counter = $slotSettings->GetGameData($slotSettings->slotId . 'Counter') ?? 0;           
            $slotSettings->SetBet();
            if($slotEvent['command'] == 'login'){
                $slotSettings->SetGameData($slotSettings->slotId . 'BalanceVersion', $BALANCE * $DENOMINATOR);
                $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);
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
                $initbetindex = 4;
                $objRes = [
                    'command' => $slotEvent['command'],
                    'context' => [
                        'actions' => ['spin', 'buy_spin'],
                        'current' => 'spins',
                        'last_args' => [
                            'bet_per_line' => $bets[$initbetindex],
                            'lines' => $LINES,
                        ],
                        'last_win' => 0,
                        'math_version' => 'a',
                        'round_finished' => true,
                        'spins' => [
                            'bet_per_line' => $bets[$initbetindex],
                            'board' => [[1, 5, 10], [3, 4, 9], [1, 10, 2], [9, 5, 1], [10, 2, 3]],
                            'lines' => $LINES,
                            'round_bet' => $bets[$initbetindex] * $LINES,
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
                    'settings' => json_decode('{"bet_factor": [25], "bets": ['. implode(',', $bets) .'], "big_win": [30, 50, 70], "bonus_symbol_decrement": 38, "bonus_symbol_increment": 30, "bonus_symbols": [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 14, 16, "mini", "minor", "major"], "bonus_type_chance": [576, 412], "bonus_types_bagchance_1": [96, 30], "bonus_types_bagchance_2": [300, 0], "cols": 5, "currency_format": {"currency_style": "symbol", "denominator": 100, "style": "money"}, "fs_retrigger": 8, "jackpots": {"grand": 2000, "major": 150, "mini": 20, "minor": 50}, "lines": [25], "mystery_bagchance": [7, 2, 1], "mystery_bagvalue": [20, 50, 150], "mystery_symbol_decrement": 10, "paylines": [[1, 1, 1, 1, 1], [0, 0, 0, 0, 0], [2, 2, 2, 2, 2], [0, 1, 2, 1, 0], [2, 1, 0, 1, 2], [1, 0, 0, 0, 1], [1, 2, 2, 2, 1], [0, 0, 1, 2, 2], [2, 2, 1, 0, 0], [1, 2, 1, 0, 1], [1, 0, 1, 2, 1], [0, 1, 1, 1, 0], [2, 1, 1, 1, 2], [0, 1, 0, 1, 0], [2, 1, 2, 1, 2], [1, 1, 0, 1, 1], [1, 1, 2, 1, 1], [0, 0, 2, 0, 0], [2, 2, 0, 2, 2], [0, 2, 2, 2, 0], [2, 0, 0, 0, 2], [1, 2, 0, 2, 1], [1, 0, 2, 0, 1], [0, 2, 0, 2, 0], [2, 0, 2, 0, 2]], "paytable": {"1": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 10, "occurrences": 4, "type": "lb"}, {"multiplier": 50, "occurrences": 5, "type": "lb"}], "2": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 10, "occurrences": 4, "type": "lb"}, {"multiplier": 50, "occurrences": 5, "type": "lb"}], "3": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 10, "occurrences": 4, "type": "lb"}, {"multiplier": 50, "occurrences": 5, "type": "lb"}], "4": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 10, "occurrences": 4, "type": "lb"}, {"multiplier": 50, "occurrences": 5, "type": "lb"}], "5": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 25, "occurrences": 4, "type": "lb"}, {"multiplier": 100, "occurrences": 5, "type": "lb"}], "6": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 30, "occurrences": 4, "type": "lb"}, {"multiplier": 150, "occurrences": 5, "type": "lb"}], "7": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 40, "occurrences": 4, "type": "lb"}, {"multiplier": 200, "occurrences": 5, "type": "lb"}], "8": [{"multiplier": 15, "occurrences": 3, "type": "lb"}, {"multiplier": 50, "occurrences": 4, "type": "lb"}, {"multiplier": 250, "occurrences": 5, "type": "lb"}], "9": [{"multiplier": 20, "occurrences": 3, "type": "lb"}, {"multiplier": 60, "occurrences": 4, "type": "lb"}, {"multiplier": 300, "occurrences": 5, "type": "lb"}], "10": [{"freespins": 8, "multiplier": 1, "occurrences": 3, "trigger": "freespins", "type": "tb"}]}, "reelsamples": {"freespins": [[5, 6, 7, 8, 9, 11], [5, 6, 7, 8, 9, 10, 11], [5, 6, 7, 8, 9, 10, 11], [5, 6, 7, 8, 9, 10, 11], [5, 6, 7, 8, 9, 11]], "last_freespins": [[5, 6, 7, 8, 9], [5, 6, 7, 8, 9, 10], [5, 6, 7, 8, 9, 10], [5, 6, 7, 8, 9, 10], [5, 6, 7, 8, 9]], "spins": [[1, 2, 3, 4, 5, 6, 7, 8, 9, 11], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], [1, 2, 3, 4, 5, 6, 7, 8, 9, 11]]}, "respins_granted": 3, "rows": 3, "symbols": [{"id": 1, "name": "el_01", "type": "line"}, {"id": 2, "name": "el_02", "type": "line"}, {"id": 3, "name": "el_03", "type": "line"}, {"id": 4, "name": "el_04", "type": "line"}, {"id": 5, "name": "el_05", "type": "line"}, {"id": 6, "name": "el_06", "type": "line"}, {"id": 7, "name": "el_07", "type": "line"}, {"id": 8, "name": "el_08", "type": "line"}, {"id": 9, "name": "el_wild", "type": "wild"}, {"id": 10, "name": "el_scatter", "type": "scat"}, {"id": 11, "name": "el_bonus_1", "type": "scat"}, {"id": 12, "name": "el_bonus_2", "type": "scat"}], "symbols_line": [1, 2, 3, 4, 5, 6, 7, 8], "symbols_scat": [10, 11, 12], "symbols_wild": [9], "transitions": [{"act": {"bet": false, "cheat": false, "name": "init", "win": false}, "dst": "spins", "src": "none"}, {"act": {"args": ["bet_per_line", "lines"], "bet": true, "cheat": true, "name": "spin", "win": true}, "dst": "spins", "src": "spins"}, {"act": {"bet": false, "cheat": false, "name": "freespin_init", "win": false}, "dst": "freespins", "src": "spins"}, {"act": {"bet": false, "cheat": true, "name": "freespin", "win": true}, "dst": "freespins", "src": "freespins"}, {"act": {"bet": false, "cheat": false, "name": "freespin_stop", "win": false}, "dst": "spins", "src": "freespins"}, {"act": {"bet": false, "cheat": true, "name": "bonus_init", "win": false}, "dst": "bonus", "src": "freespins"}, {"act": {"bet": false, "cheat": true, "name": "bonus_init", "win": false}, "dst": "bonus", "src": "spins"}, {"act": {"bet": false, "cheat": true, "name": "respin", "win": true}, "dst": "bonus", "src": "bonus"}, {"act": {"bet": false, "cheat": false, "name": "bonus_spins_stop", "win": false}, "dst": "spins", "src": "bonus"}, {"act": {"bet": false, "cheat": false, "name": "bonus_freespins_stop", "win": false}, "dst": "freespins", "src": "bonus"}], "version": "a"}'),
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
                        $objRes['context'] = $LASTSPIN->context;
                    }
                }
            }else if($slotEvent['command'] == 'sync'){
                $objRes = [
                    'command' => $slotEvent['command'],
                    'modes' => ['auto', 'play', 'freebet'],
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
                $linesId = [];
                $linesId[0] = [2, 2, 2, 2, 2];
                $linesId[1] = [1, 1, 1, 1, 1];
                $linesId[2] = [3, 3, 3, 3, 3];
                $linesId[3] = [1, 2, 3, 2, 1];
                $linesId[4] = [3, 2, 1, 2, 3];
                $linesId[5] = [2, 1, 1, 1, 2];
                $linesId[6] = [2, 3, 3, 3, 2];
                $linesId[7] = [1, 1, 2, 3, 3];
                $linesId[8] = [3, 3, 2, 1, 1];
                $linesId[9] = [2, 3, 2, 1, 2];
                $linesId[10] = [2, 1, 2, 3, 2];
                $linesId[11] = [1, 2, 2, 2, 1];
                $linesId[12] = [3, 2, 2, 2, 3];
                $linesId[13] = [1, 2, 1, 2, 1];
                $linesId[14] = [3, 2, 3, 2, 3];
                $linesId[15] = [2, 2, 1, 2, 2];
                $linesId[16] = [2, 2, 3, 2, 2];
                $linesId[17] = [1, 1, 3, 1, 1];
                $linesId[18] = [3, 3, 1, 3, 3];
                $linesId[19] = [1, 3, 3, 3, 1];
                $linesId[20] = [3, 1, 1, 1, 3];
                $linesId[21] = [2, 3, 1, 3, 2];
                $linesId[22] = [2, 1, 3, 1, 2];
                $linesId[23] = [1, 3, 1, 3, 1];
                $linesId[24] = [3, 1, 3, 1, 3];

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
                $currentRespinGames = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespinGame') ?? 0;
                $totalRespinGames = $slotSettings->GetGameData($slotSettings->slotId . 'RespinGames') ?? 0;
                $currentHill = $slotSettings->GetGameData($slotSettings->slotId . 'Hill') ?? [0, 0];
                $bonus_types = $slotSettings->GetGameData($slotSettings->slotId . 'BonusTypes') ?? [0, 0];

                $isState = false;
                if($action['name'] == 'freespin_init'){
                    $Counter = 0;
                    $objRes = [
                        'command' => $slotEvent['command'],
                        'context' => [
                            'actions' => ['freespin'],
                            'current' => 'freespins',
                            'freespins' => [
                                'bet_per_line' => $betline * $DENOMINATOR,
                                'board' => $LASTSPIN->context->spins->board,
                                'bs_v' => $LASTSPIN->context->spins->bs_v,
                                'bs_values' => $LASTSPIN->context->spins->bs_values,
                                'hill' => $LASTSPIN->context->spins->hill,
                                'lines' => $LINES,
                                'round_bet' => $betline * 100 * $LINES,
                                'round_win' => $LASTSPIN->context->spins->round_win,
                                'rounds_granted' => $totalFreeGames,
                                'rounds_left' => $totalFreeGames - $currentFreeGames,
                                'rounds_lefts' => $totalFreeGames - $currentFreeGames,
                                'total_win' => $LASTSPIN->context->spins->total_win,
                            ],
                            'last_action' => 'freespin_init',
                            'last_args' => [],
                            'last_win' => ($slotSettings->GetGameData($slotSettings->slotId . 'LastWin') ?? 0) * $DENOMINATOR,
                            'math_version' => 'a',
                            'round_finished' => false,
                            'version' => 1
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
                }else if($action['name'] == 'freespin_stop'){
                    $Counter = 0;
                    $objRes = [
                        'command' => $slotEvent['command'],
                        'context' => [
                            'actions' => ['spin'],
                            'current' => 'spins',
                            'last_action' => 'freespin_stop',
                            'last_args' => [],
                            'last_win' => ($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') ?? 0)* $DENOMINATOR,
                            'math_version' => 'a',
                            'round_finished' => true,
                            'spins' => [
                                'bet_per_line' => $betline * $DENOMINATOR,
                                'board' => $LASTSPIN->context->freespins->board,
                                'bs_v' => $LASTSPIN->context->freespins->bs_v,
                                'bs_values' => $LASTSPIN->context->freespins->bs_values,
                                'hill' => $LASTSPIN->context->freespins->hill,
                                'lines' => $LINES,
                                'portal_level' => $LASTSPIN->context->freespins->portal_level,
                                'round_bet' => $betline * 100 * $LINES,
                                'round_win' => 0,
                                'total_win' => $LASTSPIN->context->freespins->total_win,

                            ],
                            'version' => 1
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
                }else if($action['name'] == 'bonus_init'){
                    $Counter = 0;
                    $highMoneySymbolCount = $slotSettings->GetHighMoneySymbolCount();
                    if($highMoneySymbolCount > 0){
                        $bonus_types = [300, 30];
                    }else{
                        $bonus_types = [300, 0];
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusTypes', $bonus_types);
                    $slotSettings->SetGameData($slotSettings->slotId . 'HighMoneySymbolCount', $highMoneySymbolCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'MaxMoneyCount', $slotSettings->GetMaxMoneyCount());
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentHighMoneySymbolCount', 0);
                    $board = json_decode(json_encode($LASTSPIN->context->spins->board), true);
                    $bs_count = 0;
                    for($r = 0; $r < 5; $r++){
                        for($k = 0; $k < 3; $k++){
                            if($board[$r][$k] >= $MONEY){
                                $bs_count++;
                            }
                        }
                    }
                    $objRes = [
                        'command' => $slotEvent['command'],
                        'context' => [
                            'actions' => ['respin'],
                            'current' => 'bonus',
                            'bonus' => [
                                'back_to' => $totalFreeGames > 0 ? 'freespins' : 'spins',
                                'bet_per_line' => $betline * $DENOMINATOR,
                                'board' => $totalFreeGames > 0 ? $LASTSPIN->context->freespins->board : $LASTSPIN->context->spins->board,
                                'bonus_types' => $bonus_types,
                                'bs_count' => $bs_count,
                                'bs_v' => $totalFreeGames > 0 ? $LASTSPIN->context->freespins->bs_v : $LASTSPIN->context->spins->bs_v,
                                'bs_values' => $totalFreeGames > 0 ? $LASTSPIN->context->freespins->bs_values : $LASTSPIN->context->spins->bs_values,
                                'hill' => $totalFreeGames > 0 ? $LASTSPIN->context->freespins->hill : $LASTSPIN->context->spins->hill,
                                'last_respin' => false,
                                'lines' => $LINES,
                                'round_bet' => $betline * 100 * $LINES,
                                'round_win' => $totalFreeGames > 0 ? $LASTSPIN->context->freespins->round_win : $LASTSPIN->context->spins->round_win,
                                'rounds_granted' => $totalRespinGames,
                                'rounds_left' => $totalRespinGames - $currentRespinGames,
                                'rounds_lefts' => $totalRespinGames - $currentRespinGames,
                                'total_win' => $totalFreeGames > 0 ? $LASTSPIN->context->freespins->total_win : $LASTSPIN->context->spins->total_win,
                                'triger_board' => $totalFreeGames > 0 ? $LASTSPIN->context->freespins->board : $LASTSPIN->context->spins->board,
                            ],
                            'last_action' => 'bonus_init',
                            'last_args' => [],
                            'last_win' => ($slotSettings->GetGameData($slotSettings->slotId . 'LastWin') ?? 0) * $DENOMINATOR,
                            'math_version' => 'a',
                            'round_finished' => false,
                            'version' => 1
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
                    if($totalFreeGames > 0){
                        $objRes['context']['freespins'] = $LASTSPIN->context->freespins;
                    }else{
                        $objRes['context']['spins'] = $LASTSPIN->context->spins;
                    }
                }else if($action['name'] == 'bonus_spins_stop'){
                    $Counter = 0;
                    $slotSettings->SetGameData($slotSettings->slotId . 'Hill', [0, 0]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'MaxMoneyCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'HighMoneySymbolCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentHighMoneySymbolCount', 0);
                    $objRes = [
                        'command' => $slotEvent['command'],
                        'context' => [
                            'actions' => [$totalFreeGames > 0 ? 'freespin' : 'spin'],
                            'current' => $totalFreeGames > 0 ? 'freespins' : 'spins',
                            'last_action' => $totalFreeGames > 0 ? 'bonus_freespins_stop' : 'bonus_spins_stop',
                            'last_args' => [],
                            'last_win' => ($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') ?? 0)* $DENOMINATOR,
                            'math_version' => 'a',
                            'round_finished' => $totalFreeGames > 0 ? false : true,
                            'version' => 1
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
                    if($totalFreeGames > 0){
                        $objRes['context']['freespins'] = json_decode(json_encode($LASTSPIN->context->bonus), true);
                        $objRes['context']['freespins']['total_win'] = ($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') ?? 0)* $DENOMINATOR;
                    }else{
                        $objRes['context']['spins'] = json_decode(json_encode($LASTSPIN->context->bonus), true);
                        $objRes['context']['spins']['total_win'] = ($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') ?? 0)* $DENOMINATOR;
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusTypes', [0, 0]);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', 0);
                }else if($action['name'] == 'respin'){
                    $currentRespinGames++;
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', $currentRespinGames);
                    $maxMoneyCount = $slotSettings->GetGameData($slotSettings->slotId . 'MaxMoneyCount') ?? 8;                    
                    $highMoneySymbolCount = $slotSettings->GetGameData($slotSettings->slotId . 'HighMoneySymbolCount') ?? 0;     
                    $winType = mt_rand(0, 1);
                    $overBank = false;
                    for( $i = 0; $i <= 2000; $i++ ) {
                        $moneyTotalWin = 0;
                        $moneyChangedWin = false;
                        $moneyCount = 0;
                        $reels = json_decode(json_encode($LASTSPIN->context->bonus->board), true);
                        $moneyValues = $slotSettings->GetGameData($slotSettings->slotId . 'MoneyValue') ?? [];      
                        $currentHighMoneySymbolCount = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentHighMoneySymbolCount') ?? 0;                  
                        $newMoneyPoses = [];
                        $mystery_poses = [];
                        $moneyPoses = [];
                        $mystery_values = [];
                        for($r = 0; $r < 5; $r++){
                            if(!isset($moneyValues[$r])){
                                $moneyValues[$r] = [];
                            }
                            for($k = 0; $k < 3; $k++){
                                if($reels[$r][$k] < $MONEY){
                                    if(mt_rand(0, 100) < 14 && $winType == 1){
                                        $moneyChangedWin = true;
                                        if($currentHighMoneySymbolCount < $highMoneySymbolCount && mt_rand(0, 100) < 50){
                                            $reels[$r][$k] = 12;
                                            $currentHighMoneySymbolCount++;
                                        }else{
                                            $reels[$r][$k] = $MONEY;
                                        }
                                        array_push($newMoneyPoses, [$r, $k]);
                                    }
                                }else if($reels[$r][$k] == $MONEY){
                                    array_push($moneyPoses, [$r, $k]);
                                }
                                
                                if($reels[$r][$k] == $MONEY && $moneyValues[$r][$k] == 0){
                                    $moneyValues[$r][$k] = $slotSettings->getMoneyValue('bonus', $overBank);
                                }else if($reels[$r][$k] == 12 && $moneyValues[$r][$k] == 0){
                                    $moneyValues[$r][$k] = $slotSettings->GetHighMoneyValue();
                                }
                                if($reels[$r][$k] == 12){
                                    array_push($mystery_poses, [$r, $k]);
                                    array_push($mystery_values, $moneyValues[$r][$k]);
                                }
                                if($moneyValues[$r][$k] > 0){
                                    $moneyTotalWin = $moneyTotalWin + $moneyValues[$r][$k] * $betline * $LINES;
                                    $moneyCount++;
                                }
                            }
                        }
                        if($totalRespinGames - $currentRespinGames < 1 && $moneyCount < $maxMoneyCount && $winType == 0){
                            $winType = 1;
                        }else if( $winType== 0 && $moneyChangedWin == false){
                            break;
                        }
                        else if( $moneyChangedWin == true && $moneyCount <= $maxMoneyCount ) 
                        {
                            if($overBank == false && $slotSettings->GetBank('bonus') < $moneyTotalWin){
                                $overBank = true;
                            }else{
                                break;
                            }
                        }
                        else if($i > 500){
                            $winType = 0;
                        }
                    }
                    if($moneyChangedWin == true){
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 0);
                        $currentRespinGames = 0;

                        for($k = 0; $k < count($newMoneyPoses); $k++){
                            if($reels[$newMoneyPoses[$k][0]][$newMoneyPoses[$k][1]] == $MONEY){
                                $bonus_types[0] = $bonus_types[0] - 38;
                            }else if($reels[$newMoneyPoses[$k][0]][$newMoneyPoses[$k][1]] == 12){
                                $bonus_types[0] = $bonus_types[0] + 30;
                                $bonus_types[1] = $bonus_types[1] - 10;
                            }
                        }
                        if($bonus_types[0] < 0){
                            $bonus_types[0] += 38;
                        }
                        if($bonus_types[1] < 0){
                            $bonus_types[1] += 10;
                        }
                    }
                    $totalWin = 0;
                    $isEndRespin = false;
                    if($moneyCount == 15 || ($totalRespinGames > 0 && $totalRespinGames <= $currentRespinGames)){
                        $isEndRespin = true;
                        $totalWin = $moneyTotalWin;
                        $moneyTotalWin = 0;
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'MoneyValue', $moneyValues);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusTypes', $bonus_types);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentHighMoneySymbolCount', $currentHighMoneySymbolCount);
                    $bs_v = [];
                    $bs_values = [];

                    for($r = 0; $r < 5; $r++){
                        $bs_v[$r] = [];
                        $bs_values[$r] = [];
                        for($k = 0; $k < 3; $k++){
                            if($reels[$r][$k] == $MONEY){
                                $bs_v[$r][$k] = $betline * $LINES * $moneyValues[$r][$k] * $DENOMINATOR;
                                $bs_values[$r][$k] = $moneyValues[$r][$k];
                            }else if($reels[$r][$k] == 12 && $isEndRespin == true){
                                $reels[$r][$k] == $MONEY;
                                array_push($moneyPoses, [$r, $k]);
                                $bs_values[$r][$k] = $moneyValues[$r][$k];
                                if( $moneyValues[$r][$k] == 20 ) 
                                {
                                    $bs_v[$r][$k] = "mini";
                                }
                                else if( $moneyValues[$r][$k] == 50 ) 
                                {
                                    $bs_v[$r][$k] = "minor";
                                }
                                else if( $moneyValues[$r][$k] == 150 ) 
                                {
                                    $bs_v[$r][$k] = "major";
                                }
                            }else{
                                $bs_v[$r][$k] = 0;
                                $bs_values[$r][$k] = 0;
                            }
                        }
                    }
                    if($isEndRespin == true){
                        if( $totalWin > 0) 
                        {
                            $slotSettings->SetBalance($totalWin);
                            $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                            $slotSettings->SetGameData($slotSettings->slotId . 'LastWin', $totalWin);
                            $BALANCE = $slotSettings->GetBalance();
                        }
                        $isState = true;
                        if($totalFreeGames == 0){
                            $isState = false;
                        }
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                        
                    }
                    $objRes = [
                        'command' => $slotEvent['command'],
                        'context' => [
                            'actions' => ['respin'],
                            'current' => 'bonus',
                            'bonus' => [
                                'back_to' => $totalFreeGames > 0 ? 'freespins' : 'spins',
                                'bet_per_line' => $betline * $DENOMINATOR,
                                'board' => $reels,
                                'bonus_types' => $bonus_types,
                                'bs_count' => $moneyCount,
                                'bs_pos' => $moneyPoses,
                                'bs_v' => $bs_v,
                                'bs_values' => $bs_values,
                                'hill' => $currentHill,
                                'last_respin' => $isEndRespin == true ? true : false,
                                'lines' => $LINES,
                                'round_bet' => $betline * 100 * $LINES,
                                'round_win' => $totalWin* $DENOMINATOR,
                                'rounds_granted' => $totalRespinGames,
                                'rounds_left' => $totalRespinGames - $currentRespinGames,
                                'rounds_lefts' => $totalRespinGames - $currentRespinGames,
                                'total_win' => $totalWin* $DENOMINATOR,
                                'triger_board' => $LASTSPIN->context->bonus->triger_board,
                            ],
                            'last_action' => 'respin',
                            'last_args' => [],
                            'last_win' => ($slotSettings->GetGameData($slotSettings->slotId . 'LastWin') ?? 0) * $DENOMINATOR,
                            'math_version' => 'a',
                            'round_finished' => false,
                            'version' => 1
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
                    if($moneyChangedWin){
                        $objRes['context']['bonus']['new_bs'] = $newMoneyPoses;
                    }
                    if($isEndRespin == true){
                        $objRes['context']['actions'] = ['bonus_spins_stop'];
                    }
                    if(count($mystery_poses)){
                        $objRes['context']['bonus']['mystery_pos'] = $mystery_poses;
                        if($isEndRespin == false){
                            $objRes['context']['bonus']['mystery_values'] = $mystery_values;
                        }
                    }
                    if($totalFreeGames > 0){
                        $objRes['context']['freespins'] = json_decode(json_encode($LASTSPIN->context->freespins), true);
                    }else{
                        $objRes['context']['spins'] = json_decode(json_encode($LASTSPIN->context->spins), true);
                    }

                }else{
                    if($slotEvent['slotEvent'] == 'bet' && $betline < $slotSettings->Bet[0]){
                        // throw error
                        return '';
                    }else if($slotEvent['slotEvent'] == 'freespin'){
                        if($totalFreeGames <= 0 || ($currentFreeGames >= $totalFreeGames)) 
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
                    if($slotEvent['slotEvent'] == 'freespin'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $currentFreeGames + 1);
                    }
                    else
                    {
                        $slotSettings->SetBalance(-1 * ($betline * $LINES), $slotEvent['slotEvent']);
                        $_sum = ($betline * $LINES) / 100 * $slotSettings->GetPercent();
                        $slotSettings->SetGameData($slotSettings->slotId . 'Bet', $betline);  
                        $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $LINES);             
                        $slotSettings->SetBet();
                        $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $_sum, $slotEvent['slotEvent']);
                        $bonusMpl = 1;
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                        
                        $roundstr = sprintf('%.4f', microtime(TRUE));
                        $roundstr = str_replace('.', '', $roundstr);
                        $roundstr = '513' . substr($roundstr, 4, 10) . '001';
                        $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);
                    }
                    $isrespin = false;
                    $initMoneyCounts = [];
                    $defaultMoneyCount = 0;
                    if($winType == 'bonus'){
                        if($slotSettings->GetBonusType($currentHill) == 2 && $slotEvent['slotEvent'] != 'freespin'){                        
                            $initMoneyCounts = $slotSettings->GetMoneyCount();
                            $isrespin = true;
                            for($i = 0; $i < count($initMoneyCounts); $i++){
                                $defaultMoneyCount = $defaultMoneyCount + $initMoneyCounts[$i];
                            }
                        }
                    }
                    for( $i = 0; $i <= 2000; $i++ ) 
                    {
                        $totalWin = 0;
                        $lineWins = [];
                        $lineWinNum = [];
                        $strWinLine = '';
                        $reels = $slotSettings->GetReelStrips($winType, $slotEvent['slotEvent'], $isrespin, $initMoneyCounts);
                        for( $k = 0; $k < $LINES; $k++ ) 
                        {
                            $positions = [];
                            $firstEle = $reels[0][$linesId[$k][0] - 1];
                            if($firstEle == $MONEY){
                                continue;
                            }
                            array_push($positions, [0, $linesId[$k][0] - 1]);
                            $lineWinNum[$k] = 1;
                            for($j = 1; $j < 5; $j++){
                                $ele = $reels[$j][$linesId[$k][$j] - 1];
                                if($firstEle == $WILD){
                                    $firstEle = $ele;
                                    $lineWinNum[$k] = $lineWinNum[$k] + 1;
                                    array_push($positions, [$j, $linesId[$k][$j] - 1]);
                                }else if($ele == $firstEle || $ele == $WILD){
                                    $lineWinNum[$k] = $lineWinNum[$k] + 1;
                                    array_push($positions, [$j, $linesId[$k][$j] - 1]);
                                    if($j == 4){
                                        $winmoney = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                        $totalWin += $winmoney;
                                        $lineWin = [
                                            'amount' => $winmoney * $DENOMINATOR,
                                            'line' => $k + 1,
                                            'occurrences' => $lineWinNum[$k],
                                            'positions' => $positions,
                                            'symbol' => $firstEle,
                                            'type' => 'lb'
                                        ];
                                        array_push($lineWins, $lineWin);
                                    }
                                }else{
                                    if($slotSettings->Paytable[$firstEle][$lineWinNum[$k]] > 0){
                                        $winmoney = $slotSettings->Paytable[$firstEle][$lineWinNum[$k]] * $betline;
                                        $totalWin += $winmoney;
                                        $lineWin = [
                                            'amount' => $winmoney * $DENOMINATOR,
                                            'line' => $k + 1,
                                            'occurrences' => $lineWinNum[$k],
                                            'positions' => $positions,
                                            'symbol' => $firstEle,
                                            'type' => 'lb'
                                        ];
                                        array_push($lineWins, $lineWin);
                                    }else{
                                        $lineWinNum[$k] = 0;
                                    }
                                    break;
                                }
                            }
                        }
                                        
                        $freeSpinNum = 0;    
                        $scatterposes = [];
                        $scattersCount = 0;
                        $scattersWin = 0;
                        $moneyCount = 0;
                        $moneyPoses = [];
                        $moneyValues = [];
                        $moneyTotalWin = 0;
                        for( $r = 0; $r < 5; $r++ ) 
                        {
                            $moneyValues[$r] = [];
                            for( $k = 0; $k <= 2; $k++ ) 
                            {
                                if( $reels[$r][$k] == $SCATTER ) 
                                {
                                    $scattersCount++;
                                    array_push($scatterposes, [$r, $k]);
                                }
                                if( $reels[$r][$k] == $MONEY ) 
                                {
                                    $moneyCount++;
                                    $moneyValues[$r][$k] = $slotSettings->getMoneyValue($winType);
                                    $moneyTotalWin += ($betline * $LINES) * $moneyValues[$r][$k];
                                }else{
                                    $moneyValues[$r][$k] = 0;
                                }
                            }
                        }
                        $winscatters = [];
                        if($scattersCount >= 3){
                            $winscatters[0] = [
                                'amount' => 0,
                                'freespins' => 8,
                                'occurrences' => $scattersCount,
                                'positions' => $scatterposes,
                                'symbol' => $SCATTER,
                                'trigger' => 'freespins',
                                'type' => 'tb'
                            ];
                        }
                        if( $i >= 1000 ) 
                        {
                            $winType = 'none';
                        }
                        if( $i > 1500 ) 
                        {
                            break;
                        }
                        if( ($scattersCount >= 3 || $moneyCount >= 6) && $winType != 'bonus' ){
                            
                        }
                        else if($winType == 'bonus' && ($scattersCount < 3 && $moneyCount < 6)){

                        }
                        else if( $totalWin <= $_winAvaliableMoney && $winType == 'bonus' ) 
                        {
                            $_currentBank = $slotSettings->GetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''));
                            if( $_currentBank < $_winAvaliableMoney ) 
                            {
                                $_winAvaliableMoney = $_currentBank;
                            }
                            else
                            {
                                if($moneyCount >= 6){
                                    if($betline * $LINES * 20 < $slotSettings->GetBank('bonus')){
                                        break;
                                    }else{

                                    }
                                }else{
                                    break;
                                }
                            }
                        }
                        else if( $totalWin > 0 && $totalWin <= $_winAvaliableMoney && $winType == 'win' ) 
                        {
                            $_currentBank = $slotSettings->GetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''));
                            if( $_currentBank < $_winAvaliableMoney ) 
                            {
                                $_winAvaliableMoney = $_currentBank;
                            }
                            else
                            {
                                break;
                            }
                        }
                        else if( $totalWin == 0 && $winType == 'none' ) 
                        {
                            break;
                        }
                    }
                    if( $totalWin > 0) 
                    {
                        $spinType = 'c';
                        $slotSettings->SetBalance($totalWin);
                        $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), -1 * $totalWin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'LastWin', $totalWin);
                    }
                    $BALANCE = $slotSettings->GetBalance();
                    if( $scattersCount >= 3 ) 
                    {
                        
                        if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') == 0 ) 
                        {
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 8);
                            $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                        }
                        else
                        {
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 8);
                        }
                    }
                    $bs_v = [];
                    for( $r = 0; $r < 5; $r++ ) 
                    {
                        $bs_v[$r] = [];
                        for( $k = 0; $k <= 2; $k++ ) 
                        {
                            if( $moneyValues[$r][$k] == 20 ) 
                            {
                                $bs_v[$r][$k] = "mini";
                            }
                            else if( $moneyValues[$r][$k] == 50 ) 
                            {
                                $bs_v[$r][$k] = "minor";
                            }
                            else if( $moneyValues[$r][$k] == 150 ) 
                            {
                                $bs_v[$r][$k] = "major";
                            }else{
                                $bs_v[$r][$k] = $betline * $LINES * $moneyValues[$r][$k] * $DENOMINATOR;
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
                    $isState = true;
                    $slotSettings->SetGameData($slotSettings->slotId . 'MoneyValue', $moneyValues);
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
                            'version' => 1
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
                    if( $slotEvent['slotEvent'] == 'freespin' ) 
                    {
                        $Counter = 0;
                        $isState = false;
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                        $leftFreeGames = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') - $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                        $objRes['context']['current'] = 'freespins';
                        $objRes['context']['round_finished'] = false;
                        $objRes['context']['last_args'] = [];
                        $objRes['context']['freespins'] = [
                            'bet_per_line' => $betline * $DENOMINATOR,
                            'board' => $reels,
                            'bs_v' => $bs_v,
                            'bs_values' => $moneyValues,
                            'hill' => $currentHill,
                            'lines' => $LINES,
                            'portal_level' => 5,
                            'round_bet' => $betline * $DENOMINATOR * $LINES,
                            'round_win' => $totalWin * $DENOMINATOR,
                            'rounds_granted' => $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames'),
                            'rounds_left' => $leftFreeGames,
                            'rounds_lefts' => $leftFreeGames,
                            'total_win' => $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') * $DENOMINATOR
                        ];
                        if($leftFreeGames == 0) 
                        {
                            $isEndFreeSpin = true;
                            $objRes['context']['actions'] = ['freespin_stop'];
                            $isState = true;
                        }
                        else
                        {
                            $objRes['context']['actions'] = ['freespin'];
                        }
                        if(count($lineWins) > 0){
                            $objRes['context']['freespins']['winlines'] = $lineWins;
                        }
                        if($scattersCount >=3 ){
                            $objRes['context']['freespins']['winscatters'] = $winscatters;
                        }
                    }else
                    {
                        // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                        
                        $objRes['context']['spins'] = [
                            'bet_per_line' => $betline * $DENOMINATOR,
                            'board' => $reels,
                            'bs_v' => $bs_v,
                            'bs_values' => $moneyValues,
                            'hill' => $currentHill,
                            'lines' => $LINES,
                            'round_bet' => $betline * $DENOMINATOR * $LINES,
                            'round_win' => $totalWin * $DENOMINATOR,
                            'total_win' => $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') * $DENOMINATOR
                        ];
                        if(count($lineWins) > 0){
                            $objRes['context']['spins']['winlines'] = $lineWins;
                        }
                        if($scattersCount >=3 ){
                            $objRes['context']['actions'] = ['freespin_init'];
                            $objRes['context']['round_finished'] = false;
                            $objRes['context']['spins']['winscatters'] = $winscatters;
                            $isState = false;
                        }else if($moneyCount >= 6){
                            $objRes['context']['actions'] = ['bonus_init'];
                            $objRes['context']['round_finished'] = false;
                            $slotSettings->SetGameData($slotSettings->slotId . 'RespinGames', 3);
                            $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespinGame', 0);
                            $isState = false;
                        }else{
                            $isState = true;
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
            if($context['current'] == 'spins'){
                $spins = $context['spins'];
                $state = 'spins';
            }else if($context['current'] == 'freespins'){
                $spins = $context['freespins'];
                $state = 'freespins';
            }else if($context['current'] == 'respin'){
                $spins = $context['respin'];
                $state = 'respin';
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
                'game_name' => 'hitthegoldbng',
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

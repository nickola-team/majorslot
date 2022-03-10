<?php 
namespace VanguardLTE\Games\scarabrichesbng
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
            $LINES = 25;
            
            $WILD = 12;
            $SCATTER = 13;

            $Counter = $slotSettings->GetGameData($slotSettings->slotId . 'Counter') ?? 0;
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
                        'last_win' => 0,
                        'math_version' => 'a',
                        'round_finished' => true,
                        'spins' => [
                            'bet_per_line' => $bets[5],
                            'board' => [[3, 13, 2], [2, 12, 6], [5, 13, 2], [2, 12, 4], [3, 13, 2]],
                            'counter' => $Counter,
                            'feature_board' => [[3, 13, 2], [2, 12, 6], [5, 13, 2], [2, 12, 4], [3, 13, 2]],
                            'is_extra_feature' => false,
                            'lines' => $LINES,
                            'portal_level' => 0,
                            'round_bet' => $bets[5] * $LINES,
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
                    'settings' => json_decode('{"bet_factor": [25], "bets": ['. implode(',', $bets) .'], "big_win": [25, 50, 100], "client_scarab_prob": 20, "cols": 5, "currency_format": {"currency_style": "symbol", "denominator": 100, "style": "money"}, "lines": [25], "paylines": [[1, 1, 1, 1, 1], [0, 0, 0, 0, 0], [2, 2, 2, 2, 2], [0, 1, 2, 1, 0], [2, 1, 0, 1, 2], [1, 2, 2, 2, 1], [1, 0, 0, 0, 1], [2, 2, 1, 2, 2], [0, 0, 1, 0, 0], [1, 1, 2, 1, 1], [1, 1, 0, 1, 1], [0, 2, 0, 2, 0], [2, 0, 2, 0, 2], [0, 1, 0, 1, 0], [2, 1, 2, 1, 2], [1, 0, 1, 0, 1], [1, 2, 1, 2, 1], [0, 1, 1, 1, 0], [2, 1, 1, 1, 2], [0, 2, 2, 2, 0], [2, 0, 0, 0, 2], [2, 0, 1, 0, 2], [0, 2, 1, 2, 0], [1, 0, 2, 0, 1], [1, 2, 0, 2, 1]], "paytable": {"2": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 20, "occurrences": 4, "type": "lb"}, {"multiplier": 80, "occurrences": 5, "type": "lb"}], "3": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 20, "occurrences": 4, "type": "lb"}, {"multiplier": 80, "occurrences": 5, "type": "lb"}], "4": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 20, "occurrences": 4, "type": "lb"}, {"multiplier": 80, "occurrences": 5, "type": "lb"}], "5": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 20, "occurrences": 4, "type": "lb"}, {"multiplier": 80, "occurrences": 5, "type": "lb"}], "6": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 20, "occurrences": 4, "type": "lb"}, {"multiplier": 80, "occurrences": 5, "type": "lb"}], "7": [{"multiplier": 10, "occurrences": 3, "type": "lb"}, {"multiplier": 30, "occurrences": 4, "type": "lb"}, {"multiplier": 150, "occurrences": 5, "type": "lb"}], "8": [{"multiplier": 10, "occurrences": 3, "type": "lb"}, {"multiplier": 30, "occurrences": 4, "type": "lb"}, {"multiplier": 150, "occurrences": 5, "type": "lb"}], "9": [{"multiplier": 15, "occurrences": 3, "type": "lb"}, {"multiplier": 40, "occurrences": 4, "type": "lb"}, {"multiplier": 200, "occurrences": 5, "type": "lb"}], "10": [{"multiplier": 15, "occurrences": 3, "type": "lb"}, {"multiplier": 40, "occurrences": 4, "type": "lb"}, {"multiplier": 200, "occurrences": 5, "type": "lb"}], "11": [{"multiplier": 20, "occurrences": 3, "type": "lb"}, {"multiplier": 60, "occurrences": 4, "type": "lb"}, {"multiplier": 300, "occurrences": 5, "type": "lb"}], "12": [{"multiplier": 25, "occurrences": 3, "type": "lb"}, {"multiplier": 100, "occurrences": 4, "type": "lb"}, {"multiplier": 400, "occurrences": 5, "type": "lb"}], "13": [{"freespins": 10, "multiplier": 0, "occurrences": 3, "trigger": "freespins", "type": "tb"}]}, "reelsamples": {"freespins": [[2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 13], [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13], [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]], "spins": [[2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 13], [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13], [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]]}, "rows": 3, "symbols": [{"id": 2, "name": "el_10", "type": "line"}, {"id": 3, "name": "el_J", "type": "line"}, {"id": 4, "name": "el_Q", "type": "line"}, {"id": 5, "name": "el_K", "type": "line"}, {"id": 6, "name": "el_A", "type": "line"}, {"id": 7, "name": "el_cat", "type": "line"}, {"id": 8, "name": "el_dog", "type": "line"}, {"id": 9, "name": "el_horus", "type": "line"}, {"id": 10, "name": "el_nefertiti", "type": "line"}, {"id": 11, "name": "el_pharaoh", "type": "line"}, {"id": 12, "name": "el_wild", "type": "wild"}, {"id": 13, "name": "el_scatter", "type": "scat"}, {"id": 14, "name": "hidden", "type": "hide"}], "symbols_hide": [14], "symbols_line": [2, 3, 4, 5, 6, 7, 8, 9, 10, 11], "symbols_scat": [13], "symbols_wild": [12], "transitions": [{"act": {"bet": false, "cheat": false, "name": "init", "win": false}, "dst": "spins", "src": "none"}, {"act": {"args": ["bet_per_line", "lines"], "bet": true, "cheat": true, "name": "spin", "win": true}, "dst": "spins", "src": "spins"}, {"act": {"bet": false, "cheat": false, "name": "freespin_init", "win": false}, "dst": "freespins", "src": "spins"}, {"act": {"bet": false, "cheat": true, "name": "freespin", "win": true}, "dst": "freespins", "src": "freespins"}, {"act": {"bet": false, "cheat": false, "name": "freespin_stop", "win": false}, "dst": "spins", "src": "freespins"}], "version": "a"}'),
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
                        if(isset($LASTSPIN->context->spins)){
                            $LASTSPIN->context->spins->board = $LASTSPIN->context->spins->feature_board;
                        }else if(isset($LASTSPIN->context->freespins)){
                            $LASTSPIN->context->freespins->board = $LASTSPIN->context->freespins->feature_board;
                        }
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
                $linesId[5] = [2, 3, 3, 3, 2];
                $linesId[6] = [2, 1, 1, 1, 2];
                $linesId[7] = [3, 3, 2, 3, 3];
                $linesId[8] = [1, 1, 2, 1, 1];
                $linesId[9] = [2, 2, 3, 2, 2];
                $linesId[10] = [2, 2, 1, 2, 2];
                $linesId[11] = [1, 3, 1, 3, 1];
                $linesId[12] = [3, 1, 3, 1, 3];
                $linesId[13] = [1, 2, 1, 2, 1];
                $linesId[14] = [3, 2, 3, 2, 3];
                $linesId[15] = [2, 1, 2, 1, 2];
                $linesId[16] = [2, 3, 2, 3, 2];
                $linesId[17] = [1, 2, 2, 2, 1];
                $linesId[18] = [3, 2, 2, 2, 3];
                $linesId[19] = [1, 3, 3, 3, 1];
                $linesId[20] = [3, 1, 1, 1, 3];
                $linesId[21] = [3, 1, 2, 1, 3];
                $linesId[22] = [1, 3, 2, 3, 1];
                $linesId[23] = [2, 1, 3, 1, 2];
                $linesId[24] = [2, 3, 1, 3, 2];

                $action = $slotEvent['action'];
                $set_denominator = $slotEvent['set_denominator'];
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
                }else{
                    // throw error
                    return '';
                }

                $currentFreeGames = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') ?? 0;
                $totalFreeGames = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') ?? 0;

                $isState = false;
                $is_extra_feature = false;
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
                                'counter' => $Counter,
                                'feature_board' => $LASTSPIN->context->spins->feature_board,
                                'is_extra_feature' => $is_extra_feature,
                                'is_extra_feature_seen' => false,
                                'lines' => $LINES,
                                'portal_level' => $LASTSPIN->context->spins->portal_level,
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
                                'board' => $LASTSPIN->context->freespins->feature_board,
                                'counter' => $Counter,
                                'feature_board' => $LASTSPIN->context->freespins->feature_board,
                                'is_extra_feature' => $is_extra_feature,
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
                        $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $_sum, $slotEvent['slotEvent']);
                        $bonusMpl = 1;
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'Bet', $betline);
                        $roundstr = sprintf('%.4f', microtime(TRUE));
                        $roundstr = str_replace('.', '', $roundstr);
                        $roundstr = '273' . substr($roundstr, 4, 10) . '001';
                        $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);
                    }
                    $wildScarabCount = 0;
                    if($winType == 'win' && $slotSettings->IsAvaliableScarabs($slotEvent['slotEvent'])){
                        $is_extra_feature = true;
                        $wildScarabCount = $slotSettings->GetWildScarabCount();
                    }
                    for( $i = 0; $i <= 2000; $i++ ) 
                    {
                        $totalWin = 0;
                        $lineWins = [];
                        $lineWinNum = [];
                        $strWinLine = '';
                        if($i > 1000){
                            $is_extra_feature = false;
                            $wildScarabCount = 0;
                        }
                        $initReels = $slotSettings->GetReelStrips($winType, $slotEvent['slotEvent']);
                        $reels = $slotSettings->GetWildScarabReels($initReels, $wildScarabCount);
                        $_lineWinNumber = 1;
                        for( $k = 0; $k < $LINES; $k++ ) 
                        {
                            $positions = [];
                            $firstEle = $reels[0][$linesId[$k][0] - 1];
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
                        $wildCount = 0;
                        $wildReelCount = 0;
                        $wildPoses = [];
                        $points = 0;
                        for( $r = 0; $r < 5; $r++ ) 
                        {
                            $wildReel = false;
                            for( $k = 0; $k <= 2; $k++ ) 
                            {
                                if( $reels[$r][$k] == $SCATTER ) 
                                {
                                    $scattersCount++;
                                    array_push($scatterposes, [$r, $k]);
                                }else if( $reels[$r][$k] == $WILD ) 
                                {
                                    $wildCount++;
                                    array_push($wildPoses, [$r, $k]);
                                    if($wildReel == false){
                                        $wildReelCount++;
                                        $wildReel = true;
                                    }
                                }
                            }
                        }
                        $winscatters = [];
                        if($scattersCount >= 3){
                            $winscatters[0] = [
                                'amount' => 0,
                                'freespins' => 10,
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
                        if( $scattersCount >= 3 && $winType != 'bonus' ){
                            
                        }
                        else if($winType == 'bonus' && $scattersCount < 3){

                        }
                        else if($wildScarabCount > 0 && $wildScarabCount != $wildCount){

                        }else if($wildScarabCount == 0 && $wildReelCount > 1){
                            $str = "";
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
                                break;
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
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 10);
                            $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                        }
                        else
                        {
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + 10);
                        }
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
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                        $leftFreeGames = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') - $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame');
                        $objRes['context']['current'] = 'freespins';
                        $objRes['context']['round_finished'] = false;
                        $objRes['context']['last_args'] = [];
                        $objRes['context']['freespins'] = [
                            'bet_per_line' => $betline * $DENOMINATOR,
                            'board' => $initReels,
                            'counter' => $Counter,
                            'feature_board' => $reels,
                            'is_extra_feature' => $is_extra_feature,
                            'is_extra_feature_seen' => true,
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
                        if($wildScarabCount > 0){
                            $objRes['context']['freespins']['wild_pos'] = $wildPoses;
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
                            'board' => $initReels,
                            'counter' => $Counter,
                            'feature_board' => $reels,
                            'is_extra_feature' => $is_extra_feature,
                            'is_extra_feature_seen' => false,
                            'lines' => $LINES,
                            'portal_level' => 0,
                            'round_bet' => $betline * $DENOMINATOR * $LINES,
                            'round_win' => $totalWin * $DENOMINATOR,
                            'total_win' => $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') * $DENOMINATOR
                        ];
                        if(count($lineWins) > 0){
                            $objRes['context']['spins']['winlines'] = $lineWins;
                        }
                        if($wildScarabCount > 0){
                            $objRes['context']['spins']['wild_pos'] = $wildPoses;
                        }
                        if($scattersCount >=3 ){
                            $objRes['context']['actions'] = ['freespin_init'];
                            $objRes['context']['round_finished'] = false;
                            $objRes['context']['spins']['winscatters'] = $winscatters;
                        }else{
                            $isState = true;
                        }
                    }
                }
                $objRes['context']['last_action'] = $action['name'];

                $slotSettings->SaveLogReport(json_encode($objRes), $betline * $LINES, $LINES, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                if($action['name'] == 'spin' || $action['name'] == 'freespin' || $action['name'] == 'freespin_stop'){
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
                'game_id' => 168,
                'game_name' => 'scarab_riches_bng',
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

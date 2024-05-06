<?php 
namespace VanguardLTE\Games\stickypiggybng
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
            $LINES = 20;
            
            $WILD = 12;
            $SCATTER = 13;
            $BUYMULS = [100, 200, 400];
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
                        'actions' => ['spin', 'buy_spin'],
                        'current' => 'spins',
                        'last_action' => 'init',
                        'last_args' => [
                            'bet_per_line' => $bets[9],
                            'lines' => $LINES,
                        ],
                        'last_win' => ($slotSettings->GetGameData($slotSettings->slotId . 'LastWin') ?? 0) * $DENOMINATOR,
                        'math_version' => 'a',
                        'round_finished' => true,
                        'spins' => [
                            'bet_per_line' => $bets[9],
                            'board' => [[3, 13, 7], [11, 2, 12], [2, 12, 5], [12, 2, 10], [6, 13, 3]],
                            'lines' => $LINES,
                            'round_bet' => $bets[9] * $LINES,
                            'round_win' => 0,
                            'total_win' => 0,
                            'wild_mps' => [[1, 1, 1], [1, 1, 3], [1, 3, 1], [3, 1, 1], [1, 1, 1]]
                        ],
                        'version' => 2
                    ],
                    'modes' => ['auto', 'play', "freebet"],
                    'origin_data' => [
                        'data' => [
                            'quick_spin' => false
                        ],
                        'quick_spin' => false
                    ],
                    'request_id' => $slotEvent['request_id'],
                    'session_id' => '68939e9a5d134e78bfd9993d4a2cc34e',
                    'settings' => json_decode('{"authenticity_link": "/booongo/verify?game_id='. $slotSettings->game->label .'","Avg_fg_win_by_num_fs": {"9": 34.51, "10": 45.46, "11": 58.32, "12": 73.52, "13": 90.77, "14": 110.57, "15": 132.71, "16": 157.77, "17": 185.16, "18": 215.61, "19": 248.7, "20": 285.07, "21": 324.49, "22": 366.32, "23": 412.16, "24": 460.63, "25": 512.34, "26": 566.6, "27": 624.39, "28": 685.17, "29": 749.41, "30": 816.72, "31": 888.15, "32": 962.23, "33": 1038.46, "34": 1118.9, "35": 1202.06, "36": 1287.43, "37": 1376.58, "38": 1468.81, "39": 1564.83, "40": 1662.26, "41": 1763.48, "42": 1867.42, "43": 1974.1, "44": 2085.3, "45": 2196.14}, "add_cells": {"1": {"1": 0.7142857143, "2": 0.1904761905, "3": 0.0952380952}, "2": {"2": 0.5102040816, "3": 0.2721088435, "4": 0.1723356009, "5": 0.0362811792, "6": 0.0090702948}, "3": {"3": 0.3644314869, "4": 0.2915451895, "5": 0.2235179786, "6": 0.0846560847, "7": 0.0298023971, "8": 0.0051830256, "9": 0.0008638376}}, "bet_factor": [20], "bets": ['. implode(',', $bets) .'], "big_win": [20, 30, 60, 100, 150], "cells": 15, "cols": 5, "currency_format": {"currency_style": "symbol", "denominator": 100, "style": "money"}, "freespins_buying_price": {"3": 100, "4": 200, "5": 400}, "gamble_source": [1000000, 21, 21, 21], "init_board_freespins": [[11, 11, 11], [9, 7, 10], [2, 10, 5], [10, 6, 8], [11, 11, 11]], "init_wild_mps": [[1, 1, 1], [1, 1, 3], [1, 3, 1], [3, 1, 1], [1, 1, 1]], "line_symbols": [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], "lines": [20], "pattern_bf": {"0": [0, 1, 2, 3, 4], "1": [0, 1, 3, 4, 2], "2": [0, 1, 4, 2, 3], "3": [0, 2, 3, 4, 1], "4": [0, 2, 4, 1, 3], "5": [0, 3, 4, 2, 1], "6": [1, 2, 3, 0, 4], "7": [1, 2, 4, 3, 0], "8": [1, 3, 4, 0, 2], "9": [2, 3, 4, 1, 0]}, "paylines": [[1, 1, 1, 1, 1], [0, 0, 0, 0, 0], [2, 2, 2, 2, 2], [0, 1, 2, 1, 0], [2, 1, 0, 1, 2], [1, 0, 0, 0, 1], [1, 2, 2, 2, 1], [0, 0, 1, 2, 2], [2, 2, 1, 0, 0], [1, 2, 1, 0, 1], [1, 0, 1, 2, 1], [0, 1, 1, 1, 0], [2, 1, 1, 1, 2], [0, 1, 0, 1, 0], [2, 1, 2, 1, 2], [1, 1, 0, 1, 1], [1, 1, 2, 1, 1], [0, 0, 2, 0, 0], [2, 2, 0, 2, 2], [0, 2, 2, 2, 0]], "paytable": {"1": [{"multiplier": 2, "occurrences": 3, "type": "lb"}, {"multiplier": 5, "occurrences": 4, "type": "lb"}, {"multiplier": 25, "occurrences": 5, "type": "lb"}], "2": [{"multiplier": 2, "occurrences": 3, "type": "lb"}, {"multiplier": 5, "occurrences": 4, "type": "lb"}, {"multiplier": 25, "occurrences": 5, "type": "lb"}], "3": [{"multiplier": 2, "occurrences": 3, "type": "lb"}, {"multiplier": 5, "occurrences": 4, "type": "lb"}, {"multiplier": 25, "occurrences": 5, "type": "lb"}], "4": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 10, "occurrences": 4, "type": "lb"}, {"multiplier": 50, "occurrences": 5, "type": "lb"}], "5": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 10, "occurrences": 4, "type": "lb"}, {"multiplier": 50, "occurrences": 5, "type": "lb"}], "6": [{"multiplier": 8, "occurrences": 3, "type": "lb"}, {"multiplier": 20, "occurrences": 4, "type": "lb"}, {"multiplier": 100, "occurrences": 5, "type": "lb"}], "7": [{"multiplier": 12, "occurrences": 3, "type": "lb"}, {"multiplier": 25, "occurrences": 4, "type": "lb"}, {"multiplier": 150, "occurrences": 5, "type": "lb"}], "8": [{"multiplier": 20, "occurrences": 3, "type": "lb"}, {"multiplier": 40, "occurrences": 4, "type": "lb"}, {"multiplier": 200, "occurrences": 5, "type": "lb"}], "9": [{"multiplier": 25, "occurrences": 3, "type": "lb"}, {"multiplier": 60, "occurrences": 4, "type": "lb"}, {"multiplier": 300, "occurrences": 5, "type": "lb"}], "10": [{"multiplier": 35, "occurrences": 3, "type": "lb"}, {"multiplier": 100, "occurrences": 4, "type": "lb"}, {"multiplier": 500, "occurrences": 5, "type": "lb"}], "11": [{"multiplier": 50, "occurrences": 3, "type": "lb"}, {"multiplier": 150, "occurrences": 4, "type": "lb"}, {"multiplier": 750, "occurrences": 5, "type": "lb"}], "13": [{"freespins": 9, "multiplier": 1, "occurrences": 3, "trigger": "freespins", "type": "tb"}, {"freespins": 12, "multiplier": 10, "occurrences": 4, "trigger": "freespins", "type": "tb"}, {"freespins": 15, "multiplier": 50, "occurrences": 5, "trigger": "freespins", "type": "tb"}]}, "pick_cells_max": 3, "pick_src": 14, "pick_wage": [0, 0, 15, 19, 21], "pick_win_accuracy": 6, "pick_win_source": 1000000, "reelsamples": {"bf_3": [[1, 2, 3, 4, 5, 6, 7, 13], [1, 2, 3, 4, 5, 6, 7, 13], [1, 2, 3, 4, 5, 6, 7, 13], [1, 2, 3, 4, 5, 6, 8, 10], [1, 2, 3, 4, 5, 7, 9, 11]], "bf_4": [[1, 2, 3, 4, 5, 6, 7, 13], [1, 2, 3, 4, 5, 6, 7, 13], [1, 2, 3, 4, 5, 6, 7, 13], [1, 2, 3, 4, 5, 6, 7, 13], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]], "bf_5": [[1, 2, 3, 4, 5, 6, 7, 13], [1, 2, 3, 4, 5, 6, 7, 13], [1, 2, 3, 4, 5, 6, 7, 13], [1, 2, 3, 4, 5, 6, 7, 13], [1, 2, 3, 4, 5, 6, 7, 13]], "f": [[1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]], "f_paid_3": [[1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]], "f_paid_4": [[1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]], "f_paid_5": [[1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]], "m_1": [[1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 13], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 13]], "m_2": [[1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 13], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 13]]}, "rows": 3, "scat_id": 13, "symbols": [{"id": 1, "name": "10", "type": "line"}, {"id": 2, "name": "J", "type": "line"}, {"id": 3, "name": "Q", "type": "line"}, {"id": 4, "name": "K", "type": "line"}, {"id": 5, "name": "A", "type": "line"}, {"id": 6, "name": "keys", "type": "line"}, {"id": 7, "name": "newspaper", "type": "line"}, {"id": 8, "name": "thief_blue", "type": "line"}, {"id": 9, "name": "thief_green", "type": "line"}, {"id": 10, "name": "lady", "type": "line"}, {"id": 11, "name": "banker", "type": "line"}, {"id": 12, "name": "wild", "type": "wild"}, {"id": 13, "name": "scatter", "type": "scat"}], "symbols_line": [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], "symbols_scat": [13], "symbols_wild": [12], "version": "a", "wild_id": 12, "wild_mp_3_wage3_f": 345, "wild_mp_3_wage3_m": 400, "wild_mp_f_src": 5, "wild_mp_m_src": 11}'),
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
                    'modes' => ['auto', 'play', "freebet"],
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
                if($action['name'] == 'spin' || $action['name'] == 'buy_spin'){
                    $betline = $action['params']['bet_per_line'] / $DENOMINATOR;
                    $slotEvent['slotEvent'] = 'bet';
                }else if($action['name'] == 'freespin_init' || $action['name'] == 'freespin_pick' || $action['name'] == 'freespin' || $action['name'] == 'freespin_stop' || $action['name'] == 'freespin_gamble'){
                    $betline = $slotSettings->GetGameData($slotSettings->slotId . 'Bet') ?? $slotSettings->Bet[9];
                    $slotEvent['slotEvent'] = 'freespin';
                }else{
                    // throw error
                    return '';
                }

                $currentFreeGames = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') ?? 0;
                $totalFreeGames = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') ?? 0;
                $totalSpinCount = $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') ?? 0;
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks') ?? [];
                $stack = null;
                $isState = false;
                $is_extra_feature = false;
                if($action['name'] == 'freespin_init' || $action['name'] == 'freespin_stop'){
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
                        'modes' => ['auto', 'play', "freebet"],
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
                    if($action['name'] == 'freespin_stop' && $slotSettings->GetGameData($slotSettings->slotId . 'IsLoose') == 1){
                        $slotSettings->SetGameData($slotSettings->slotId . 'IsLoose', 0);
                        $context = json_decode(json_encode($LASTSPIN->context), true);
                        unset($context['freespins']);
                        $context['actions'] = ['spin', 'buy_spin'];
                        $context['current'] = 'spins';
                        $context['round_finished'] = true;
                        $objRes['context'] = $context;
                    }else{

                        if(count($tumbAndFreeStacks) > 0 && isset($tumbAndFreeStacks[$totalSpinCount])){
                            $stack = $tumbAndFreeStacks[$totalSpinCount];
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $totalSpinCount + 1);
                        }else{
                            return '';
                        }
                        $Counter = 0;
                        // $stack['spins']['bet_per_line'] = $betline * $DENOMINATOR;
                        $spin_types = ['spins', 'freespins'];
                        for($k = 0; $k < 2; $k++){
                            $spin_type = $spin_types[$k];
                            if($stack[$spin_type] != ''){
                                $stack[$spin_type]['bet_per_line'] = $betline * $DENOMINATOR;
                                $stack[$spin_type]['round_bet'] = $betline * $DENOMINATOR * $LINES;
                                if(isset($stack[$spin_type]['round_win'])){
                                    $stack[$spin_type]['round_win'] = str_replace(',', '', $stack[$spin_type]['round_win']) * $betline * $DENOMINATOR;
                                }
                                if(isset($stack[$spin_type]['total_win'])){
                                    $stack[$spin_type]['total_win'] = str_replace(',', '', $stack[$spin_type]['total_win']) * $betline * $DENOMINATOR;
                                }
                            }
                        }
                        if($stack['freespins'] != ''){
                            $objRes['context']['freespins'] = $stack['freespins'];
                        }
                        if($stack['spins'] != ''){
                            $objRes['context']['spins'] = $stack['spins'];
                        }
                        if($action['name'] == 'freespin_init'){
                            if($stack['freespins']['num_scats_bf'] == 5){
                                $freespinNum = $stack['freespins']['gamble']['freespins'];
                                $tumbAndFreeStacks = $slotSettings->GetReelStrips('bonus', ($betline * $LINES), $freespinNum);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $tumbAndFreeStacks);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespinNum);
                                $objRes['context']['actions'] = ['freespin'];
                            }else{
                                $objRes['context']['actions'] = ['freespin_gamble'];
                            }
                            $objRes['context']['current'] = 'freespins';
                        }else if($action['name'] == 'freespin_stop'){
                            $objRes['context']['actions'] = ['spin', 'buy_spin'];
                            $objRes['context']['current'] = 'spins';
                        }
                    }
                }else if($action['name'] == 'freespin_gamble'){
                    $objRes = [
                        'command' => $slotEvent['command'],
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
                    $context = json_decode(json_encode($LASTSPIN->context), true);
                    $select_mode = $action['params']['selected_mode'];
                    $freespinNum = $context['freespins']['gamble']['freespins'];
                    if($select_mode == 0){
                        $tumbAndFreeStacks = $slotSettings->GetReelStrips('bonus', ($betline * $LINES), $freespinNum);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $tumbAndFreeStacks);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);

                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespinNum);
                        $context['freespins']['gamble']['history'] = [
                            'result' => 'refuse',
                            'selected_mode' => 0
                        ];
                        if(isset($context['freespins']['gamble']['variants'])){
                            $context['freespins']['gamble']['history']['variants'] = $context['freespins']['gamble']['variants'];
                            unset($context['freespins']['gamble']['variants']);
                        }
                        $context['freespins']['rounds_granted'] = $freespinNum;
                        $context['freespins']['rounds_left'] = $freespinNum;
                        $context['freespins']['rounds_lefts'] = $freespinNum;
                        $context['actions'] = ['freespin'];
                        $context['current'] = 'freespins';
                        $context['last_action'] = 'freespin_gamble';
                    }else{
                        $percent = 90;
                        if(isset($context['freespins']['gamble']['free_cells']) && count($context['freespins']['gamble']['free_cells']) < $select_mode){
                            return '';
                        }
                        if(isset($context['freespins']) && isset($context['freespins']['gamble']) && isset($context['freespins']['gamble']['variants']) && count($context['freespins']['gamble']['variants']) > 0){
                            $percent = $context['freespins']['gamble']['variants'][$select_mode] * 100;
                        }
                        $winChance = $slotSettings->IsWinChance($percent, $freespinNum);
                        if($winChance == true){
                            $gameble = $context['freespins']['gamble'];
                            
                            $freemores = $slotSettings->moreFreeCount($freespinNum, $select_mode);
                            $freemore = 0;
                            for($k = 0; $k < $select_mode; $k++){
                                $freemore += $freemores[$k];
                                $gameble['picks'][$gameble['free_cells'][$k][0]][$gameble['free_cells'][$k][1]] = $freemores[$k];
                            }
                            $freespinNum += $freemore;
                            $gameble['freespins'] = $freespinNum;
                            $gameble['history'] = [
                                'freespins_added' => $freemore,
                                'result' => 'win',
                                'selected_mode' => $select_mode,
                                'variants' => $gameble['variants']
                            ];
                            array_splice($gameble['free_cells'], 0, $select_mode); // 첫번째 배열요소 삭제하고 키를 다시 색인
                            foreach($gameble['variants'] as $key => $val){
                                $gameble['variants'][$key] = $val + $val * 0.025;
                            }
                            if(count($gameble['free_cells']) < 3){
                                for($k = 0; $k < $select_mode; $k++ ){
                                    unset($gameble['variants'][count($gameble['variants'])]);
                                }
                            }
                            if(count($gameble['free_cells']) == 0){
                                unset($gameble['free_cells']); //배열요소를 삭제한후 키를 다시 색인하지 않음
                                $tumbAndFreeStacks = $slotSettings->GetReelStrips('bonus', ($betline * $LINES), $freespinNum);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $tumbAndFreeStacks);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                $context['actions'] = ['freespin'];
                                $context['current'] = 'freespins';
                                $context['last_action'] = 'freespin_gamble';
                                $context['freespins']['rounds_granted'] = $freespinNum;
                                $context['freespins']['rounds_left'] = $freespinNum;
                                $context['freespins']['rounds_lefts'] = $freespinNum;
                            }else{
                                $context['actions'] = ['freespin_gamble'];
                                $context['current'] = 'freespins';
                                $context['last_action'] = 'freespin_gamble';
                            }
                            $context['freespins']['gamble'] = $gameble;
                        }else{
                            $isState = true;
                            $freespinNum = 0;
                            $context['freespins']['gamble']['freespins'] = $freespinNum;
                            $context['freespins']['gamble']['history'] = [
                                'result' => 'loose',
                                'selected_mode' => 1,
                                'variants' => $context['freespins']['gamble']['variants']
                            ];
                            unset($context['freespins']['gamble']['variants']);
                            $context['actions'] = ['freespin_stop'];
                            $context['current'] = 'freespins';
                            $context['last_action'] = 'freespin_gamble';
                            $slotSettings->SetGameData($slotSettings->slotId . 'IsLoose', 1);
                        }      
                        $context['last_args'] = [
                            'selected_mode' => $select_mode
                        ];
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespinNum);            
                    }    
                    $objRes['context'] = $context;                
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
                                    'board' => [[3, 13, 7], [11, 2, 12], [2, 12, 5], [12, 2, 10], [6, 13, 3]],
                                    'lines' => $LINES,
                                    'round_bet' => $betline * $DENOMINATOR * $LINES,
                                    'round_win' => 0,
                                    'total_win' => 0,
                                    'wild_mps' => [[1, 1, 1], [1, 1, 3], [1, 3, 1], [3, 1, 1], [1, 1, 1]]
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
                    }
                    $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $betline * $LINES, $LINES);
                    $winType = $_spinSettings[0];
                    $_winAvaliableMoney = $_spinSettings[1];
                    // $winType = 'win';
                    // $_winAvaliableMoney = 1000;
                    $isBuyFreeSpin = false;
                    
                    if($slotEvent['slotEvent'] != 'freespin' && $slotEvent['slotEvent'] != 'respin'){
                        $allBet = $betline * $LINES;                        
                        $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreespin', 0);
                        $buyType = 0;
                        if($winType == 'bonus'){
                            $buyType = 3;
                        }
                        if($action['name'] == 'buy_spin'){
                            $buyType = $action['params']['num_scats_bf'];
                            $allBet = $allBet * $BUYMULS[$buyType - 3];
                            $isBuyFreeSpin = true;
                            $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreespin', $buyType);
                            $winType = 'bonus';
                        }
                        $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                        $_sum = $allBet / 100 * $slotSettings->GetPercent();
                        $slotSettings->SetGameData($slotSettings->slotId . 'Bet', $betline);  
                        $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $LINES);             
                        $slotSettings->SetBet();
                        $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $_sum, $slotEvent['slotEvent'], $isBuyFreeSpin, $buyType);
                        $bonusMpl = 1;
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                        $slotSettings->SetGameData($slotSettings->slotId . 'IsLoose', 0);

                        $tumbAndFreeStacks = $slotSettings->GetReelStrips($winType, ($betline * $LINES), $buyType);
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
                    $spin_types = ['spins', 'freespins'];
                    $totalWin = 0;
                    for($k = 0; $k < 2; $k++){
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
                    }
                    $objRes = [
                        'command' => $slotEvent['command'],
                        'context' => [
                            'actions' => ['spin', 'buy_spin'],
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
                    if($stack['freespins'] != ''){
                        $objRes['context']['freespins'] = $stack['freespins'];
                    }
                    if($stack['spins'] != ''){
                        $objRes['context']['spins'] = $stack['spins'];
                    }
                    if( $slotEvent['slotEvent'] == 'freespin') 
                    {
                        $isState = false;
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);                        
                        $rounds_left = $stack['freespins']['rounds_left'];
                        $objRes['context']['current'] = 'freespins';
                        $objRes['context']['round_finished'] = false;
                        $objRes['context']['last_args'] = []; 
                        if($rounds_left == 0) 
                        {
                            $isEndFreeSpin = true;
                            $objRes['context']['actions'] = ['freespin_stop'];
                            $isState = true;
                        }
                        else
                        {
                            $objRes['context']['actions'] = ['freespin'];
                        }
                    }else
                    {
                        // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                        if($freeSpinNum > 0 ){
                            $objRes['context']['actions'] = ['freespin_init'];
                            $objRes['context']['round_finished'] = false;
                            $isState = false;
                        }
                    }
                    if($isState == true && $slotEvent['slotEvent'] == 'freespin'){
                        if($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') > 0){
                            $slotSettings->SetGameData($slotSettings->slotId . 'LastWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'));
                            $objRes['last_win'] = ($slotSettings->GetGameData($slotSettings->slotId . 'LastWin') ?? 0) * $DENOMINATOR;
                        }
                    }
                }
                $objRes['context']['last_action'] = $action['name'];
                $allBet = $betline * $LINES;
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreespin') > 0){{
                    $allBet = $allBet * $BUYMULS[$slotSettings->GetGameData($slotSettings->slotId . 'BuyFreespin') - 3];
                }}
                $slotSettings->SaveLogReport(json_encode($objRes), $allBet, $LINES, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                if($action['name'] == 'spin' || $action['name'] == 'freespin' || $action['name'] == 'buy_spin' || $action['name'] == 'freespin_pick'){
                    if($action['name'] == 'spin'){
                        $this->SaveBNGLogParse($beforeBalance, $BALANCE, $betline * $LINES, $totalWin, $objRes, $slotSettings);
                    }else if($action['name'] == 'buy_spin'){
                        $this->SaveBNGLogParse($beforeBalance, $BALANCE, $betline * $LINES * $BUYMULS[$slotSettings->GetGameData($slotSettings->slotId . 'BuyFreespin') - 3], $totalWin, $objRes, $slotSettings);
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
            if(isset($context['freespins'])){
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

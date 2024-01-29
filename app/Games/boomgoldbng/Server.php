<?php 
namespace VanguardLTE\Games\boomgoldbng
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
                            'board' => [[2, 4, 4, 8, 8], [5, 11, 8, 8, 8], [7, 7, 3, 11, 3], [2, 2, 11, 1, 1], [3, 11, 3, 9, 9], [4, 4, 9, 9, 9]],
                            'last_fs_was_avalanche' => false,
                            'lines' => $LINES,
                            'mp_count' => 3,
                            'mp_types' => [0, 1, 1, 0],
                            'mp_values' => [5, 25, 6, 100],
                            'mps' => [[1, 1], [2, 3], [3, 2], [4, 1]],
                            'round_bet' => $bets[9] * $LINES,
                            'round_win' => 0,
                            'row_b' => [2, 5, 1, 2, 3, 4],
                            'total_win' => 0,
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
                    'settings' => json_decode('{"bet_factor": [20], "bets": ['. implode(',', $bets) .'], "big_win": [20, 30, 60, 100, 150], "cells": 30, "cols": 6, "currency_format": {"currency_style": "symbol", "denominator": 100, "style": "money"}, "freespins_buying_price": 100, "freespins_source": [64, 64, 64, 64, 64, 64, 63, 63, 63, 63, 63, 63, 1000, 100000, 100000, 100000, 100000, 100000, 100000, 100000, 100000, 100000, 100000, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100], "fs_retrigger": 5, "init_mp_count": 3, "init_mp_types": [0, 1, 1, 0], "init_mp_values": [5, 25, 6, 100], "init_mps": [[1, 1], [2, 3], [3, 2], [4, 1]], "init_row_b": [2, 5, 1, 2, 3, 4], "lines": [20], "mp_correct_sum": 60, "mp_correct_value_f": 6, "mp_correct_value_m": 10, "mp_id": 11, "mp_type_f_src": 23, "mp_type_m_src": 29, "mp_values": [2, 3, 4, 5, 6, 8, 10, 12, 15, 20, 25, 30, 40, 50, 60, 80, 100, 120, 150, 200, 250, 300, 350, 400, 500], "mp_values_f_src": 13, "mp_values_m_src": 19, "mp_values_wages_f": [0, 48500, 78860, 87908, 95188, 96930, 98187, 98932, 99286, 99580, 99720, 99820, 99865, 99900, 99925, 99945, 99965, 99985, 99989, 99992, 99994, 99996, 99997, 99998, 99999, 100000], "mp_values_wages_m": [0, 1000, 2115, 3565, 5370, 6800, 7860, 8860, 9400, 9575, 9695, 9775, 9825, 9855, 9885, 9905, 9925, 9945, 9955, 9965, 9973, 9980, 9985, 9990, 9995, 10000], "number_of_mps_max": 10, "pattern_bf": {"0": [2, 5, 4, 1, 3, 0], "1": [2, 0, 5, 3, 4, 1], "2": [1, 4, 2, 5, 0, 3], "3": [0, 3, 1, 4, 5, 2], "4": [0, 1, 3, 2, 4, 5], "5": [1, 5, 3, 0, 2, 4], "6": [5, 3, 4, 1, 2, 0], "7": [5, 2, 0, 3, 1, 4], "8": [4, 0, 2, 5, 3, 1], "9": [3, 4, 1, 0, 5, 2]}, "paytable": {"1": [{"multiplier": 5, "occurrences": 8, "type": "lb"}, {"multiplier": 15, "occurrences": 10, "type": "lb"}, {"multiplier": 40, "occurrences": 12, "type": "lb"}], "2": [{"multiplier": 8, "occurrences": 8, "type": "lb"}, {"multiplier": 18, "occurrences": 10, "type": "lb"}, {"multiplier": 80, "occurrences": 12, "type": "lb"}], "3": [{"multiplier": 10, "occurrences": 8, "type": "lb"}, {"multiplier": 20, "occurrences": 10, "type": "lb"}, {"multiplier": 100, "occurrences": 12, "type": "lb"}], "4": [{"multiplier": 16, "occurrences": 8, "type": "lb"}, {"multiplier": 24, "occurrences": 10, "type": "lb"}, {"multiplier": 160, "occurrences": 12, "type": "lb"}], "5": [{"multiplier": 20, "occurrences": 8, "type": "lb"}, {"multiplier": 30, "occurrences": 10, "type": "lb"}, {"multiplier": 200, "occurrences": 12, "type": "lb"}], "6": [{"multiplier": 30, "occurrences": 8, "type": "lb"}, {"multiplier": 40, "occurrences": 10, "type": "lb"}, {"multiplier": 240, "occurrences": 12, "type": "lb"}], "7": [{"multiplier": 40, "occurrences": 8, "type": "lb"}, {"multiplier": 100, "occurrences": 10, "type": "lb"}, {"multiplier": 300, "occurrences": 12, "type": "lb"}], "8": [{"multiplier": 50, "occurrences": 8, "type": "lb"}, {"multiplier": 200, "occurrences": 10, "type": "lb"}, {"multiplier": 500, "occurrences": 12, "type": "lb"}], "9": [{"multiplier": 200, "occurrences": 8, "type": "lb"}, {"multiplier": 500, "occurrences": 10, "type": "lb"}, {"multiplier": 1000, "occurrences": 12, "type": "lb"}], "10": [{"freespins": 15, "multiplier": 3, "occurrences": 4, "trigger": "freespins", "type": "tb"}, {"freespins": 15, "multiplier": 5, "occurrences": 5, "trigger": "freespins", "type": "tb"}, {"freespins": 15, "multiplier": 100, "occurrences": 6, "trigger": "freespins", "type": "tb"}]}, "reelsamples": {"bf": [[1, 2, 3, 4, 5, 7, 8, 9, 10], [1, 2, 3, 4, 5, 6, 7, 8, 10], [1, 2, 3, 4, 5, 6, 7, 9, 10], [1, 2, 3, 4, 5, 6, 8, 9, 10], [1, 2, 3, 4, 5, 6, 7, 8, 9], [1, 2, 3, 4, 5, 6, 7, 8, 9]], "f_1": [[1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]], "f_2": [[1, 2, 3, 4, 5, 6, 7, 8, 9, 11], [1, 2, 3, 4, 5, 6, 7, 8, 9, 11], [1, 2, 3, 4, 5, 6, 7, 8, 9, 11], [1, 2, 3, 4, 5, 6, 7, 8, 9, 11], [1, 2, 3, 4, 5, 6, 7, 8, 9, 11], [1, 2, 3, 4, 5, 6, 7, 8, 9, 11]], "m_1": [[1, 2, 3, 4, 5, 6, 7, 8, 9, 10], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]], "m_2": [[1, 2, 3, 4, 5, 6, 7, 8, 9, 10], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]], "m_3": [[1, 2, 3, 4, 5, 6, 7, 8, 9, 11], [1, 2, 3, 4, 5, 6, 7, 8, 9, 11], [1, 2, 3, 4, 5, 6, 7, 8, 9, 11], [1, 2, 3, 4, 5, 6, 7, 8, 9, 11], [1, 2, 3, 4, 5, 6, 7, 8, 9, 11], [1, 2, 3, 4, 5, 6, 7, 8, 9, 11]]}, "reelset_f_src": 12, "reelset_m_src": 18, "reelset_wage_bf": [0, 474, 1000], "reelset_wage_f": [0, 474, 1000], "reelset_wage_m": [0, 260, 932, 1000], "rows": 5, "scat_amount_to_trigger_additonal_fs": 3, "scat_id": 10, "scat_to_trigger_buy_spin": 4, "spins_source": [63, 63, 63, 63, 63, 63, 64, 63, 64, 64, 64, 63, 63, 63, 63, 63, 63, 63, 1000, 10000, 10000, 10000, 10000, 10000, 10000, 10000, 10000, 10000, 10000, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100], "symbols": [{"id": 1, "name": "violet_gem", "type": "line"}, {"id": 2, "name": "blue_gem", "type": "line"}, {"id": 3, "name": "green_gem", "type": "line"}, {"id": 4, "name": "red_gem", "type": "line"}, {"id": 5, "name": "yellow_gem", "type": "line"}, {"id": 6, "name": "pickaxe", "type": "line"}, {"id": 7, "name": "lamp", "type": "line"}, {"id": 8, "name": "trolley", "type": "line"}, {"id": 9, "name": "donkey", "type": "line"}, {"id": 10, "name": "scatter", "type": "scat"}, {"id": 11, "name": "multiplier", "type": "scat"}], "symbols_line": [1, 2, 3, 4, 5, 6, 7, 8, 9], "symbols_scat": [10, 11], "transitions": [{"act": {"bet": false, "cheat": false, "name": "init", "win": false}, "dst": "spins", "src": "none"}, {"act": {"args": ["bet_per_line", "lines"], "bet": true, "cheat": true, "name": "spin", "win": true}, "dst": "spins", "src": "spins"}, {"act": {"bet": false, "cheat": true, "name": "freespin_init", "win": false}, "dst": "freespins", "src": "spins"}, {"act": {"bet": false, "cheat": true, "name": "freespin", "win": true}, "dst": "freespins", "src": "freespins"}, {"act": {"bet": false, "cheat": false, "name": "freespin_stop", "win": false}, "dst": "spins", "src": "freespins"}, {"act": {"bet": false, "cheat": false, "name": "respin", "win": true}, "dst": "spins", "src": "spins"}, {"act": {"args": ["bet_per_line", "lines"], "bet": true, "cheat": true, "name": "buy_spin", "win": true}, "dst": "spins", "src": "spins"}, {"act": {"bet": false, "cheat": false, "name": "respin", "win": true}, "dst": "freespins", "src": "freespins"}], "update_mp": {"2": 3, "3": 4, "4": 5, "5": 6, "6": 8, "8": 10, "10": 12, "12": 15, "15": 20, "20": 25, "25": 30, "30": 40, "40": 50, "50": 60, "60": 80, "80": 100, "100": 120, "120": 150, "150": 200, "200": 250, "250": 300, "300": 350, "350": 400, "400": 500, "500": 500}, "update_mp_wage2_f": [24, 24, 24, 24, 24, 24, 24, 24, 22, 21, 20, 18, 17, 16, 14, 12, 10, 0, 0, 0, 0, 0, 0, 0, 0], "update_mp_wage2_m": [30, 30, 30, 30, 30, 30, 30, 30, 28, 27, 26, 24, 23, 22, 20, 18, 15, 0, 0, 0, 0, 0, 0, 0, 0], "win_max_m_tb": 10000}'),
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
                
                $currentFreeGames = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') ?? 0;
                $currentRespin = $slotSettings->GetGameData($slotSettings->slotId . 'CurrentRespin') ?? 0;
                $totalFreeGames = $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') ?? 0;
                $totalSpinCount = $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') ?? 0;
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks') ?? [];

                $totalWin = 0;
                if($action['name'] == 'spin' || $action['name'] == 'buy_spin'){
                    $betline = $action['params']['bet_per_line'] / $DENOMINATOR;
                    $slotEvent['slotEvent'] = 'bet';
                }else if($action['name'] == 'freespin_init' || $action['name'] == 'freespin' || $action['name'] == 'freespin_stop'){
                    $betline = $slotSettings->GetGameData($slotSettings->slotId . 'Bet') ?? $slotSettings->Bet[9];
                    $slotEvent['slotEvent'] = 'freespin';
                }else if($action['name'] == 'respin'){
                    $betline = $slotSettings->GetGameData($slotSettings->slotId . 'Bet') ?? $slotSettings->Bet[9];
                    $slotEvent['slotEvent'] = 'bet';
                    if($totalFreeGames > 0 && $currentFreeGames > 0){
                        $slotEvent['slotEvent'] = 'freespin';
                    }
                }else{
                    // throw error
                    return '';
                }

                $stack = null;
                $isState = false;
                $is_extra_feature = false;
                if($action['name'] == 'freespin_init' || $action['name'] == 'freespin_stop'){
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
                            if(isset($stack[$spin_type]['fs_win'])){
                                $stack[$spin_type]['fs_win'] = str_replace(',', '', $stack[$spin_type]['fs_win']) * $betline * $DENOMINATOR;
                            }
                            if(isset($stack[$spin_type]['avalanche'])){
                                for($i = 0; $i < count($stack[$spin_type]['avalanche']); $i++){
                                    if(isset($stack[$spin_type]['avalanche'][$i]['round_win'])){
                                        $stack[$spin_type]['avalanche'][$i]['round_win'] = $stack[$spin_type]['avalanche'][$i]['round_win'] * $betline * $DENOMINATOR;
                                    }
                                    if(isset($stack[$spin_type]['avalanche'][$i]['win'])){
                                        $stack[$spin_type]['avalanche'][$i]['win'] = $stack[$spin_type]['avalanche'][$i]['win'] * $betline * $DENOMINATOR;
                                    }
                                    if(isset($stack[$spin_type]['avalanche'][$i]['round_win_without_mult'])){
                                        $stack[$spin_type]['avalanche'][$i]['round_win_without_mult'] = $stack[$spin_type]['avalanche'][$i]['round_win_without_mult'] * $betline * $DENOMINATOR;
                                    }
                                    if(isset($stack[$spin_type]['avalanche'][$i]['winlines']) && count($stack[$spin_type]['avalanche'][$i]['winlines']) > 0){
                                        foreach( $stack[$spin_type]['avalanche'][$i]['winlines'] as $index => $value ){
                                            if(isset($value['amount']) && $value['amount'] > 0){
                                                $value['amount'] = str_replace(',', '', $value['amount']) * $betline * $DENOMINATOR;
                                                $stack[$spin_type]['avalanche'][$i]['winlines'][$index] = $value;
                                            }
                                        }
                                    }
                                    if(isset($stack[$spin_type]['avalanche'][$i]['round_win_without_mp'])){
                                        $stack[$spin_type]['avalanche'][$i]['round_win_without_mp'] = $stack[$spin_type]['avalanche'][$i]['round_win_without_mp'] * $betline * $DENOMINATOR;
                                    }
                                    if(isset($stack[$spin_type]['avalanche'][$i]['trg_spin_winlines']) && count($stack[$spin_type]['avalanche'][$i]['trg_spin_winlines']) > 0){
                                        foreach( $stack[$spin_type]['avalanche'][$i]['trg_spin_winlines'] as $index => $value ){
                                            if(isset($value['amount']) && $value['amount'] > 0){
                                                $value['amount'] = str_replace(',', '', $value['amount']) * $betline * $DENOMINATOR;
                                                $stack[$spin_type]['avalanche'][$i]['trg_spin_winlines'][$index] = $value;
                                            }
                                        }
                                    }
                                    if(isset($stack[$spin_type]['avalanche'][$i]['winscatters']) && count($stack[$spin_type]['avalanche'][$i]['winscatters']) > 0){
                                        foreach( $stack[$spin_type]['avalanche'][$i]['winscatters'] as $index => $value ){
                                            if(isset($value['amount']) && $value['amount'] > 0){
                                                $value['amount'] = str_replace(',', '', $value['amount']) * $betline * $DENOMINATOR;
                                            }
                                            $stack[$spin_type]['avalanche'][$i]['winscatters'][$index] = $value;
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
                        $objRes['context']['actions'] = ['spin', 'buy_spin'];
                        $objRes['context']['current'] = 'spins';
                    }
                }else{
                    if($slotEvent['slotEvent'] == 'bet' && $betline * $LINES > $BALANCE){
                        // throw error
                        $objRes = [
                            'command' => $slotEvent['command'],
                            'context' => [
                                'actions' => ['spin', 'buy_spin'],
                                'current' => 'spins',
                                'last_args' => [],
                                'last_win' => ($slotSettings->GetGameData($slotSettings->slotId . 'LastWin') ?? 0) * $DENOMINATOR,
                                'math_version' => 'a',
                                'round_finished' => true,
                                'spins' => [
                                    'bet_per_line' => $betline * $DENOMINATOR,
                                    'board' => [[2, 4, 4, 8, 8], [5, 11, 8, 8, 8], [7, 7, 3, 11, 3], [2, 2, 11, 1, 1], [3, 11, 3, 9, 9], [4, 4, 9, 9, 9]],
                                    'last_fs_was_avalanche' => false,
                                    'lines' => $LINES,
                                    'mp_count' => 3,
                                    'mp_types' => [0, 1, 1, 0],
                                    'mp_values' => [5, 25, 6, 100],
                                    'mps' => [[1, 1], [2, 3], [3, 2], [4, 1]],
                                    'round_bet' => $betline * $DENOMINATOR * $LINES,
                                    'round_win' => 0,
                                    'row_b' => [2, 5, 1, 2, 3, 4],
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
                        if($action['name'] != 'respin'){
                            if($totalFreeGames <= 0 || ($currentFreeGames >= $totalFreeGames)) 
                            {
                                // throw error
                                return '';
                            }
                            $currentFreeGames++;
                            $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $currentFreeGames);
                        }
                    }
                    $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $betline * $LINES, $LINES);
                    $winType = $_spinSettings[0];
                    $_winAvaliableMoney = $_spinSettings[1];
                    // $winType = 'win';
                    // $_winAvaliableMoney = 1000;
                    $isBuyFreeSpin = false;
                    if($slotEvent['slotEvent'] != 'freespin' && $action['name'] != 'respin'){
                        $allBet = $betline * $LINES;                        
                        $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreespin', 0);
                        if($action['name'] == 'buy_spin'){
                            $allBet = $allBet * 100;
                            $isBuyFreeSpin = true;
                            $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreespin', 1);
                            $winType = 'bonus';
                        }
                        $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                        $_sum = $allBet / 100 * $slotSettings->GetPercent();
                        $slotSettings->SetGameData($slotSettings->slotId . 'Bet', $betline);  
                        $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $LINES);             
                        $slotSettings->SetBet();
                        $slotSettings->SetBank((isset($slotEvent['slotEvent']) ? $slotEvent['slotEvent'] : ''), $_sum, $slotEvent['slotEvent'], $isBuyFreeSpin);
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
                            if(isset($stack[$spin_type]['win'])){
                                $stack[$spin_type]['win'] = str_replace(',', '', $stack[$spin_type]['win']) * $betline * $DENOMINATOR;
                            }
                            if(isset($stack[$spin_type]['fs_win'])){
                                $stack[$spin_type]['fs_win'] = str_replace(',', '', $stack[$spin_type]['fs_win']) * $betline * $DENOMINATOR;
                            }
                            if(isset($stack[$spin_type]['round_win_without_mult'])){
                                $stack[$spin_type]['round_win_without_mult'] = str_replace(',', '', $stack[$spin_type]['round_win_without_mult']) * $betline * $DENOMINATOR;
                            }
                            if(isset($stack[$spin_type]['round_win_without_mp'])){
                                $stack[$spin_type]['round_win_without_mp'] = str_replace(',', '', $stack[$spin_type]['round_win_without_mp']) * $betline * $DENOMINATOR;
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
                                if(isset($stack[$spin_type]['trg_spin_winlines']) && count($stack[$spin_type]['trg_spin_winlines']) > 0){
                                    foreach( $stack[$spin_type]['trg_spin_winlines'] as $index => $value ){
                                        if(isset($value['amount']) && $value['amount'] > 0){
                                            $value['amount'] = str_replace(',', '', $value['amount']) * $betline * $DENOMINATOR;
                                            $stack[$spin_type]['trg_spin_winlines'][$index] = $value;
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
                                if(isset($stack[$spin_type]['avalanche'])){
                                    for($i = 0; $i < count($stack[$spin_type]['avalanche']); $i++){
                                        if(isset($stack[$spin_type]['avalanche'][$i]['round_win'])){
                                            $stack[$spin_type]['avalanche'][$i]['round_win'] = $stack[$spin_type]['avalanche'][$i]['round_win'] * $betline * $DENOMINATOR;
                                        }
                                        if(isset($stack[$spin_type]['avalanche'][$i]['win'])){
                                            $stack[$spin_type]['avalanche'][$i]['win'] = $stack[$spin_type]['avalanche'][$i]['win'] * $betline * $DENOMINATOR;
                                        }
                                        if(isset($stack[$spin_type]['avalanche'][$i]['round_win_without_mult'])){
                                            $stack[$spin_type]['avalanche'][$i]['round_win_without_mult'] = $stack[$spin_type]['avalanche'][$i]['round_win_without_mult'] * $betline * $DENOMINATOR;
                                        }
                                        
                                        if(isset($stack[$spin_type]['avalanche'][$i]['round_win_without_mp'])){
                                            $stack[$spin_type]['avalanche'][$i]['round_win_without_mp'] = $stack[$spin_type]['avalanche'][$i]['round_win_without_mp'] * $betline * $DENOMINATOR;
                                        }
                                        if(isset($stack[$spin_type]['avalanche'][$i]['trg_spin_winlines']) && count($stack[$spin_type]['avalanche'][$i]['trg_spin_winlines']) > 0){
                                            foreach( $stack[$spin_type]['avalanche'][$i]['trg_spin_winlines'] as $index => $value ){
                                                if(isset($value['amount']) && $value['amount'] > 0){
                                                    $value['amount'] = str_replace(',', '', $value['amount']) * $betline * $DENOMINATOR;
                                                    $stack[$spin_type]['avalanche'][$i]['trg_spin_winlines'][$index] = $value;
                                                }
                                            }
                                        }
                                        if(isset($stack[$spin_type]['avalanche'][$i]['winlines']) && count($stack[$spin_type]['avalanche'][$i]['winlines']) > 0){
                                            foreach( $stack[$spin_type]['avalanche'][$i]['winlines'] as $index => $value ){
                                                if(isset($value['amount']) && $value['amount'] > 0){
                                                    $value['amount'] = str_replace(',', '', $value['amount']) * $betline * $DENOMINATOR;
                                                    $stack[$spin_type]['avalanche'][$i]['winlines'][$index] = $value;
                                                }
                                            }
                                        }
                                        if(isset($stack[$spin_type]['avalanche'][$i]['winscatters']) && count($stack[$spin_type]['avalanche'][$i]['winscatters']) > 0){
                                            foreach( $stack[$spin_type]['avalanche'][$i]['winscatters'] as $index => $value ){
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
                                                $stack[$spin_type]['avalanche'][$i]['winscatters'][$index] = $value;
                                            }
                                        }
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
                    $objRes['context']['actions'] = $stack['actions'];
                    if( $slotEvent['slotEvent'] == 'freespin') 
                    {
                        $isState = false;
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);                        
                        $objRes['context']['current'] = 'freespins';
                        $objRes['context']['round_finished'] = false;
                        $objRes['context']['last_args'] = []; 
                        if($stack['actions'][0] == 'freespin_stop') 
                        {
                            $isEndFreeSpin = true;
                            $isState = true;
                        }
                    }else
                    {
                        // $_obf_0D5C3B1F210914123C222630290E271410213E320B0A11 = $totalWin;
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $totalWin);
                        if($freeSpinNum > 0 ){
                            $objRes['context']['round_finished'] = false;
                            $isState = false;
                        }
                    }
                    if($stack['actions'][0] == 'respin'){
                        $isState = false;
                        $objRes['context']['round_finished'] = false;
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
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreespin') == 1){{
                    $allBet = $allBet * 100;
                }}
                $slotSettings->SaveLogReport(json_encode($objRes), $allBet, $LINES, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                if($action['name'] == 'spin' || $action['name'] == 'respin' || $action['name'] == 'freespin' || $action['name'] == 'buy_spin' || $action['name'] == 'freespin_pick'){
                    if($action['name'] == 'spin'){
                        $this->SaveBNGLogParse($beforeBalance, $BALANCE, $betline * $LINES, $totalWin, $objRes, $slotSettings);
                    }else if($action['name'] == 'buy_spin'){
                        $this->SaveBNGLogParse($beforeBalance, $BALANCE, $betline * $LINES * 100, $totalWin, $objRes, $slotSettings);
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

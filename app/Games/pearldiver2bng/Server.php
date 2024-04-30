<?php 
namespace VanguardLTE\Games\pearldiver2bng
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
            $LINES = 10;
            
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
                        'last_args' => [
                            'bet_per_line' => $bets[8],
                            'lines' => $LINES,
                        ],
                        'last_win' => ($slotSettings->GetGameData($slotSettings->slotId . 'LastWin') ?? 0) * $DENOMINATOR,
                        'math_version' => 'a',
                        'round_finished' => true,
                        'spins' => [
                            'bet_per_line' => $bets[8],
                            'board' => [[5, 14, 2], [7, 9, 7], [12, 4, 13], [6, 8, 6], [3, 14, 5]],
                            'lines' => $LINES,
                            'round_bet' => $bets[8] * $LINES,
                            'round_win' => 0,
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
                    'settings' => json_decode('{"authenticity_link": "/booongo/verify?game_id='. $slotSettings->game->original_id .'", "Bet_Multiplier": 10, "bet_factor": [10], "bets": ['. implode(',', $bets) .'], "big_win": [20, 30, 50], "cells": 15, "cols": 5, "currency_format": {"currency_style": "symbol", "denominator": 100, "style": "money"}, "freespins_buying_price": 80, "lines": [10], "mystery_grand_value": 1000, "mystery_jackpots": [20, 50, 100, 1000], "paylines": [[1, 1, 1, 1, 1], [0, 0, 0, 0, 0], [2, 2, 2, 2, 2], [0, 1, 2, 1, 0], [2, 1, 0, 1, 2], [1, 0, 0, 0, 1], [1, 2, 2, 2, 1], [0, 0, 1, 2, 2], [2, 2, 1, 0, 0], [1, 2, 1, 0, 1]], "paytable": {"1": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 25, "occurrences": 4, "type": "lb"}, {"multiplier": 75, "occurrences": 5, "type": "lb"}], "2": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 25, "occurrences": 4, "type": "lb"}, {"multiplier": 75, "occurrences": 5, "type": "lb"}], "3": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 25, "occurrences": 4, "type": "lb"}, {"multiplier": 75, "occurrences": 5, "type": "lb"}], "4": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 30, "occurrences": 4, "type": "lb"}, {"multiplier": 100, "occurrences": 5, "type": "lb"}], "5": [{"multiplier": 5, "occurrences": 3, "type": "lb"}, {"multiplier": 30, "occurrences": 4, "type": "lb"}, {"multiplier": 100, "occurrences": 5, "type": "lb"}], "6": [{"multiplier": 10, "occurrences": 3, "type": "lb"}, {"multiplier": 50, "occurrences": 4, "type": "lb"}, {"multiplier": 200, "occurrences": 5, "type": "lb"}], "7": [{"multiplier": 10, "occurrences": 3, "type": "lb"}, {"multiplier": 50, "occurrences": 4, "type": "lb"}, {"multiplier": 200, "occurrences": 5, "type": "lb"}], "8": [{"multiplier": 10, "occurrences": 3, "type": "lb"}, {"multiplier": 50, "occurrences": 4, "type": "lb"}, {"multiplier": 200, "occurrences": 5, "type": "lb"}], "9": [{"multiplier": 10, "occurrences": 3, "type": "lb"}, {"multiplier": 50, "occurrences": 4, "type": "lb"}, {"multiplier": 200, "occurrences": 5, "type": "lb"}], "10": [{"multiplier": 20, "occurrences": 3, "type": "lb"}, {"multiplier": 100, "occurrences": 4, "type": "lb"}, {"multiplier": 500, "occurrences": 5, "type": "lb"}], "11": [{"multiplier": 20, "occurrences": 3, "type": "lb"}, {"multiplier": 100, "occurrences": 4, "type": "lb"}, {"multiplier": 500, "occurrences": 5, "type": "lb"}], "12": [{"multiplier": 30, "occurrences": 3, "type": "lb"}, {"multiplier": 150, "occurrences": 4, "type": "lb"}, {"multiplier": 1000, "occurrences": 5, "type": "lb"}], "13": [{"multiplier": 50, "occurrences": 3, "type": "lb"}, {"multiplier": 200, "occurrences": 4, "type": "lb"}, {"multiplier": 2000, "occurrences": 5, "type": "lb"}], "14": [{"freespins": 5, "multiplier": 0, "occurrences": 2, "trigger": "freespins", "type": "tb"}, {"freespins": 10, "multiplier": 1, "occurrences": 3, "trigger": "freespins", "type": "tb"}, {"freespins": 15, "multiplier": 10, "occurrences": 4, "trigger": "freespins", "type": "tb"}, {"freespins": 20, "multiplier": 100, "occurrences": 5, "trigger": "freespins", "type": "tb"}]}, "pearls": {"6": 2, "7": 5, "8": 10, "9": 15}, "pearls_to_bonus": 3, "reelsamples": {"bf": [[1, 2, 3, 4, 5, 14], [1, 2, 3, 4, 5, 14], [1, 2, 3, 4, 5, 14], [1, 2, 3, 4, 5, 10, 11, 12], [1, 2, 3, 4, 5, 10, 11, 12, 13]], "f_0": [[1, 2, 3, 4, 5, 6, 7, 9, 10, 11, 12, 13, 14, 15], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15], [1, 2, 3, 4, 5, 6, 7, 9, 10, 11, 12, 13, 14, 15], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15], [1, 2, 3, 4, 5, 6, 7, 9, 10, 11, 12, 13, 14, 15]], "f_1": [[1, 2, 3, 4, 5, 6, 7, 10, 11, 12, 13, 14], [1, 2, 3, 4, 5, 6, 7, 8, 10, 11, 12, 13], [1, 2, 3, 4, 5, 6, 7, 8, 10, 11, 12, 13, 15], [1, 2, 3, 4, 6, 7, 9, 10, 11, 12, 13, 15], [1, 2, 3, 4, 5, 6, 7, 8, 10, 11, 12, 13, 15]], "f_2": [[1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 15], [1, 3, 4, 5, 6, 7, 10, 11, 12, 13, 15], [1, 3, 4, 5, 6, 7, 9, 11, 12, 13, 15], [1, 3, 4, 5, 6, 7, 8, 10, 11, 12, 13, 14, 15], [1, 3, 4, 5, 6, 7, 8, 10, 11, 12, 13, 14, 15]], "f_3": [[1, 2, 3, 4, 5, 6, 7, 10, 11, 12, 13, 15], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15], [1, 2, 3, 4, 5, 6, 7, 8, 10, 11, 12, 13, 14, 15], [1, 2, 3, 4, 5, 6, 7, 10, 11, 12, 13, 15], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15]], "f_4": [[1, 2, 3, 4, 5, 6, 7, 9, 10, 11, 12, 13, 15], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15], [1, 2, 3, 4, 5, 6, 7, 9, 10, 11, 12, 13, 14, 15], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15], [1, 2, 3, 4, 5, 6, 7, 8, 10, 11, 12, 13, 15]], "fg": [[1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 14], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 14], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 14], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 14], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 14]], "m_0": [[1, 2, 3, 4, 5, 6, 7, 8, 10, 11, 12, 13, 14], [1, 2, 3, 4, 5, 6, 7, 9, 10, 11, 12, 13, 14], [1, 2, 3, 4, 5, 6, 7, 8, 10, 11, 12, 13, 14], [1, 2, 3, 4, 5, 6, 7, 8, 10, 11, 12, 13, 14], [1, 2, 3, 4, 5, 6, 9, 10, 11, 12, 13, 14]], "m_1": [[1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14], [1, 2, 3, 4, 5, 7, 9, 10, 11, 12, 13], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14], [1, 2, 3, 4, 5, 6, 8, 10, 11, 12, 13, 14], [1, 2, 3, 4, 5, 6, 8, 10, 11, 12, 13, 14]], "m_2": [[1, 2, 3, 4, 5, 6, 9, 10, 11, 12, 13, 14], [1, 2, 3, 4, 5, 6, 7, 10, 11, 12, 13, 14], [1, 2, 3, 4, 5, 7, 9, 10, 11, 12, 13], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14], [1, 2, 3, 4, 5, 6, 8, 10, 11, 12, 13, 14]], "m_3": [[1, 2, 3, 4, 5, 6, 7, 10, 11, 12, 13], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14], [1, 2, 3, 4, 5, 6, 8, 10, 11, 12, 13, 14], [1, 2, 3, 4, 5, 7, 8, 10, 11, 12, 13, 14], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]], "r_0": [[1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 15], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 15], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15]], "r_1": [[1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 15], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 15], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 15]]}, "rows": 3, "symbols": [{"id": 1, "name": "10", "type": "line"}, {"id": 2, "name": "J", "type": "line"}, {"id": 3, "name": "Q", "type": "line"}, {"id": 4, "name": "K", "type": "line"}, {"id": 5, "name": "A", "type": "line"}, {"id": 6, "name": "pearl1", "type": "line"}, {"id": 7, "name": "pearl2", "type": "line"}, {"id": 8, "name": "pearl3", "type": "line"}, {"id": 9, "name": "pearl4", "type": "line"}, {"id": 10, "name": "mask", "type": "line"}, {"id": 11, "name": "slippers", "type": "line"}, {"id": 12, "name": "turtle", "type": "line"}, {"id": 13, "name": "dolphin", "type": "line"}, {"id": 14, "name": "w_scat", "type": "scat"}, {"id": 15, "name": "mystery", "type": "mystery"}, {"id": 21, "name": "wild_pass", "type": "wild"}], "symbols_line": [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13], "symbols_mystery": [15], "symbols_scat": [14], "symbols_wild": [21], "version": "a"}'),
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
                }else if($action['name'] == 'freespin_init' || $action['name'] == 'freespin' || $action['name'] == 'freespin_stop'){
                    $betline = $slotSettings->GetGameData($slotSettings->slotId . 'Bet') ?? $slotSettings->Bet[8];
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
                    if(isset($stack['spins']['total_win'])){
                        $stack['spins']['total_win'] = str_replace(',', '', $stack['spins']['total_win']) * $betline * $DENOMINATOR;
                    }
                    if($stack['freespins'] != ''){
                        $stack['freespins']['bet_per_line'] = $betline * $DENOMINATOR;
                        $stack['freespins']['round_bet'] = $betline * $DENOMINATOR * $LINES;
                        $stack['freespins']['round_win'] = str_replace(',', '', $stack['freespins']['round_win']) * $betline * $DENOMINATOR;
                        $stack['freespins']['total_win'] = str_replace(',', '', $stack['freespins']['total_win']) * $betline * $DENOMINATOR;
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
                    if($action['name'] == 'freespin_init'){
                        $objRes['context']['actions'] = ['freespin'];
                        $objRes['context']['current'] = 'freespins';
                        $objRes['context']['freespins'] = $stack['freespins'];
                        $objRes['context']['spins'] = $stack['spins'];
                    }else if($action['name'] == 'freespin_stop'){
                        $objRes['context']['actions'] = ['spin', 'buy_spin'];
                        $objRes['context']['current'] = 'spins';
                        $objRes['context']['spins'] = $stack['spins'];
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
                                    'board' => [[5, 14, 2], [7, 9, 7], [12, 4, 13], [6, 8, 6], [3, 14, 5]],
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
                        if($action['name'] == 'buy_spin'){
                            $allBet = $allBet * 80;
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

                    $stack['spins']['bet_per_line'] = $betline * $DENOMINATOR;
                    $stack['spins']['round_bet'] = $betline * $DENOMINATOR * $LINES;
                    $totalWin = $stack['spins']['round_win'] * $betline;
                    $stack['spins']['round_win'] = str_replace(',', '', $stack['spins']['round_win']) * $betline * $DENOMINATOR;
                    if(isset($stack['spins']['total_win'])){
                        $stack['spins']['total_win'] = str_replace(',', '', $stack['spins']['total_win']) * $betline * $DENOMINATOR;                    
                    }

                    $freeSpinNum = 0;
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
                    if($stack['freespins'] != ''){
                        $stack['freespins']['bet_per_line'] = $betline * $DENOMINATOR;
                        $stack['freespins']['round_bet'] = $betline * $DENOMINATOR * $LINES;
                        $totalWin = $stack['freespins']['round_win'] * $betline;
                        $stack['freespins']['round_win'] = str_replace(',', '', $stack['freespins']['round_win']) * $betline * $DENOMINATOR;
                        $stack['freespins']['total_win'] = str_replace(',', '', $stack['freespins']['total_win']) * $betline * $DENOMINATOR;
                        if(isset($stack['freespins']['diver_feature'])){
                            if(isset($stack['freespins']['diver_feature']['divers'])){
                                for($i = 0; $i < count($stack['freespins']['diver_feature']['divers']); $i++){
                                    $stack['freespins']['diver_feature']['divers'][$i]['diver_total_win'] = $stack['freespins']['diver_feature']['divers'][$i]['diver_total_win'] * $betline * $DENOMINATOR;
                                }
                            }
                            if(isset($stack['freespins']['diver_feature']['total_win'])){
                                $stack['freespins']['diver_feature']['total_win'] = $stack['freespins']['diver_feature']['total_win'] * $betline * $DENOMINATOR;
                            }
                        }

                        if(isset($stack['freespins']['winlines']) && count($stack['freespins']['winlines']) > 0){
                            foreach( $stack['freespins']['winlines'] as $index => $value ){
                                if(isset($value['amount']) && $value['amount'] > 0){
                                    $value['amount'] = str_replace(',', '', $value['amount']) * $betline * $DENOMINATOR;
                                    $stack['freespins']['winlines'][$index] = $value;
                                }
                            }
                        }
                        if(isset($stack['freespins']['winscatters']) && count($stack['freespins']['winscatters']) > 0){
                            foreach( $stack['freespins']['winscatters'] as $index => $value ){
                                if(isset($value['amount']) && $value['amount'] > 0){
                                    if(isset($value['freespins'])){
                                        $freeSpinNum = $value['freespins'];
                                    }
                                    if(isset($value['amount'])){
                                        $value['amount'] = str_replace(',', '', $value['amount']) * $betline * $DENOMINATOR;
                                    }
                                    $stack['freespins']['winscatters'][$index] = $value;
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
                    if( $slotEvent['slotEvent'] == 'freespin') 
                    {
                        $isState = false;
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);                        
                        $rounds_left = $stack['freespins']['rounds_left'];
                        $objRes['context']['current'] = 'freespins';
                        $objRes['context']['round_finished'] = false;
                        $objRes['context']['last_args'] = [];
                        $objRes['context']['freespins'] = $stack['freespins'];                            
                        $objRes['context']['spins'] = $stack['spins'];   
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
                        
                        $objRes['context']['spins'] = $stack['spins'];
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
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreespin') == 1){{
                    $allBet = $allBet * 80;
                }}
                $slotSettings->SaveLogReport(json_encode($objRes), $allBet, $LINES, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                if($action['name'] == 'spin' || $action['name'] == 'freespin' || $action['name'] == 'buy_spin'){
                    if($action['name'] == 'spin'){
                        $this->SaveBNGLogParse($beforeBalance, $BALANCE, $betline * $LINES, $totalWin, $objRes, $slotSettings);
                    }else if($action['name'] == 'buy_spin'){
                        $this->SaveBNGLogParse($beforeBalance, $BALANCE, $betline * $LINES * 80, $totalWin, $objRes, $slotSettings);
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
                'game_name' => 'pearldiver2bng',
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

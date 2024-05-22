<?php 
namespace VanguardLTE\Games\fishingbearbng
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
                        'achievements' => ['left_level'=>0,'left_level_percent'=>0,'left_number'=>0,'left_total_percent'=>0,'right_level'=>0,'right_level_percent'=>0,'right_number'=>0,'right_total_percent'=>0],
                        'actions' => ['spin', 'buy_spin'],
                        'current' => 'spins',
                        'last_action' => 'init',
                        'last_args' => [
                        ],
                        'round_finished' => true,
                        'spins' => [
                            'bet_per_line' => $bets[9],
                            'board' => [[3,12,3],[10,10,12],[4,11,10],[10,10,12],[5,12,5]],
                            'bs' => [["position"=>1,"reel"=>0,"value"=> 50 * $bets[9] * $LINES],["position"=>2,"reel"=>1,"value"=> 1000 * $bets[9] * $LINES],["position"=>2,"reel"=>3,"value"=>20 * $bets[9] * $LINES],["position"=>1,"reel"=>4,"value"=> 5 * $bets[9] * $LINES]],
                            'bs_count' => 4,
                            "is_init_board"=>true,
                            "last_bet" => $bets[9],
                            'lines' => 25,
                            "reelset_number"=>0,
                            'round_bet' => $LINES * $bets[9],
                            'round_win' => 0,
                            'total_win' => 0,
                        ],
                        'version' => 1
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
                    'settings' => json_decode('{"authenticity_link": "/booongo/verify?game_id='. $slotSettings->game->label .'","bet_factor":[10],"bets":[2500,5000,7500,10000,12500,15000,25000,30000,40000,50000,75000,100000,125000,150000,200000,250000,300000,500000,600000,750000,1000000],"big_win":[15,20,30,50,70],"bs_values_reels":{"1":150,"2":225,"3":255,"4":275,"5":200,"10":175,"20":150,"50":125,"1000":30},"cols":5,"currency_format":{"currency_style":"symbol","denominator":100,"style":"money"},"freespins_buying_price":75,"jackpots":{"10":"mini","20":"minor","50":"major","1000":"grand"},"lines":[25],"paylines":[[1,1,1,1,1],[0,0,0,0,0],[2,2,2,2,2],[0,1,2,1,0],[2,1,0,1,2],[1,0,0,0,1],[1,2,2,2,1],[0,0,1,2,2],[2,2,1,0,0],[1,2,1,0,1],[1,0,1,2,1],[0,1,1,1,0],[2,1,1,1,2],[0,1,0,1,0],[2,1,2,1,2],[1,1,0,1,1],[1,1,2,1,1],[0,0,2,0,0],[2,2,0,2,2],[0,2,2,2,0],[2,0,0,0,2],[1,2,0,2,1],[1,0,2,0,1],[0,2,0,2,0],[2,0,2,0,2]],"paytable":{"1":[{"multiplier":1,"occurrences":3,"type":"lb"},{"multiplier":2,"occurrences":4,"type":"lb"},{"multiplier":8,"occurrences":5,"type":"lb"}],"2":[{"multiplier":1,"occurrences":3,"type":"lb"},{"multiplier":2,"occurrences":4,"type":"lb"},{"multiplier":8,"occurrences":5,"type":"lb"}],"3":[{"multiplier":1,"occurrences":3,"type":"lb"},{"multiplier":2,"occurrences":4,"type":"lb"},{"multiplier":8,"occurrences":5,"type":"lb"}],"4":[{"multiplier":1,"occurrences":3,"type":"lb"},{"multiplier":2,"occurrences":4,"type":"lb"},{"multiplier":8,"occurrences":5,"type":"lb"}],"5":[{"multiplier":2,"occurrences":3,"type":"lb"},{"multiplier":3,"occurrences":4,"type":"lb"},{"multiplier":10,"occurrences":5,"type":"lb"}],"6":[{"multiplier":2,"occurrences":3,"type":"lb"},{"multiplier":3,"occurrences":4,"type":"lb"},{"multiplier":10,"occurrences":5,"type":"lb"}],"7":[{"multiplier":3,"occurrences":3,"type":"lb"},{"multiplier":4,"occurrences":4,"type":"lb"},{"multiplier":15,"occurrences":5,"type":"lb"}],"8":[{"multiplier":3,"occurrences":3,"type":"lb"},{"multiplier":5,"occurrences":4,"type":"lb"},{"multiplier":20,"occurrences":5,"type":"lb"}],"9":[{"multiplier":3,"occurrences":3,"type":"lb"},{"multiplier":6,"occurrences":4,"type":"lb"},{"multiplier":25,"occurrences":5,"type":"lb"}],"10":[{"multiplier":6,"occurrences":3,"type":"lb"},{"multiplier":10,"occurrences":4,"type":"lb"},{"multiplier":30,"occurrences":5,"type":"lb"}]},"reelsamples":{"freespins_0":[[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6]],"freespins_1":[[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6,12]],"freespins_10":[[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6,12]],"freespins_11":[[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6,12]],"freespins_12":[[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6,12]],"freespins_2":[[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6,12]],"freespins_3":[[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6,12]],"freespins_4":[[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6,12]],"freespins_5":[[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6,12]],"freespins_6":[[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6,12]],"freespins_7":[[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6,12]],"freespins_8":[[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6,12]],"freespins_9":[[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6,12]],"spins_0":[[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6,11]],"spins_1":[[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6,11]],"spins_10":[[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6,11]],"spins_11":[[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6,11]],"spins_12":[[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6,11]],"spins_13":[[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6,11]],"spins_14":[[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6,11]],"spins_15":[[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6,11]],"spins_16":[[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6,11]],"spins_17":[[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6,11]],"spins_18":[[],[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6,11]],"spins_19":[[1,2,3,4,5,6],[],[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6,11]],"spins_2":[[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6,11]],"spins_20":[[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6,11]],"spins_21":[[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6],[1,2,3,4,5,6,11]],"spins_22":[[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6,11]],"spins_3":[[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6,11]],"spins_4":[[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6,11]],"spins_5":[[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6,11]],"spins_6":[[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6,11]],"spins_7":[[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6,11]],"spins_8":[[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6,11]],"spins_9":[[1,2,3,4,5,6],[1,2,3,4,5,6],[],[1,2,3,4,5,6],[1,2,3,4,5,6],[1,2,3,4,5,6,11]]},"rows":3,"symbols":[{"id":1,"name":"9","type":"line"},{"id":2,"name":"10","type":"line"},{"id":3,"name":"J","type":"line"},{"id":4,"name":"Q","type":"line"},{"id":5,"name":"K","type":"line"},{"id":6,"name":"A","type":"line"},{"id":7,"name":"coil","type":"line"},{"id":8,"name":"boots","type":"line"},{"id":9,"name":"equipment","type":"line"},{"id":10,"name":"boat","type":"line"},{"id":11,"name":"wild","type":"wild"},{"id":12,"name":"scatter","type":"scat"},{"id":13,"name":"hidden","type":"hide"}],"symbols_line":[1,2,3,4,5,6,7,8,9,10],"symbols_scat":[12],"symbols_wild":[11]}'),
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
                $archievement = null;
                // $prev_client_command_time = $slotEvent['prev_client_command_time'];
                
                $totalWin = 0;
                if($action['name'] == 'spin' || $action['name'] == 'buy_spin'){
                    $betline = $action['params']['bet_per_line'] / $DENOMINATOR;
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
                $currentHill = $slotSettings->GetGameData($slotSettings->slotId . 'Hill') ?? [[0,0,0,0],[0,0,0,0]];
                $hillSide = $slotSettings->GetGameData($slotSettings->slotId . 'HillSide') ?? [0, 0, 0];
                $round_left_free = $slotSettings->GetGameData($slotSettings->slotId . 'RoundLeft') ?? 0;
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
                    $Counter = 0;
                    $archievement = [
                        "left_level" => $currentHill[0][0],
                        "left_level_percent" => $currentHill[0][1],
                        "left_number" => $currentHill[0][2],
                        "left_total_percent" => $currentHill[0][3],
                        "right_level" => $currentHill[1][0],
                        "right_level_percent" => $currentHill[1][1],
                        "right_number" => $currentHill[1][2],
                        "right_total_percent" => $currentHill[1][3],
                    ];
                    $spin_types = ['spins', 'freespins', 'bonus'];                    
                    for($k = 0; $k < 3; $k++){
                        $spin_type = $spin_types[$k];
                        if($stack[$spin_type] != ''){
                            $stack[$spin_type]['bet_per_line'] = $betline * $DENOMINATOR;
                            $stack[$spin_type]['round_bet'] = $betline * $DENOMINATOR * $LINES;
                            if(isset($stack[$spin_type]['last_bet'])){
                                $stack[$spin_type]['last_bet'] = $betline * $DENOMINATOR;
                            }
                            if(isset($stack[$spin_type]['round_win'])){
                                $stack[$spin_type]['round_win'] = str_replace(',', '', $stack[$spin_type]['round_win']) * $betline * $DENOMINATOR;
                            }
                            if(isset($stack[$spin_type]['total_win'])){
                                $stack[$spin_type]['total_win'] = str_replace(',', '', $stack[$spin_type]['total_win']) * $betline * $DENOMINATOR;
                            }
                            if(isset($stack[$spin_type]['total_win_state'])){
                                $stack[$spin_type]['total_win_state'] = str_replace(',', '', $stack[$spin_type]['total_win_state']) * $betline * $DENOMINATOR;
                            }   
                            if(isset($stack[$spin_type]['last_bs']) && count($stack[$spin_type]['last_bs']) > 0){
                                foreach( $stack[$spin_type]['last_bs'] as $index => $value ){
                                    if(isset($value['value']) && $value['value'] > 0){
                                        $value['value'] = str_replace(',', '', $value['value']) * $betline * $DENOMINATOR;
                                        $stack[$spin_type]['last_bs'][$index] = $value;
                                    }
                                }
                            }
                            if(isset($stack[$spin_type]['last_bs_v'])){
                                for($i = 0; $i < count($stack[$spin_type]['last_bs_v']); $i++){
                                    for($j = 0; $j < 3; $j++){
                                        if(is_numeric(str_replace(',', '', $stack[$spin_type]['last_bs_v'][$i][$j]))){
                                            $stack[$spin_type]['last_bs_v'][$i][$j] = str_replace(',', '', $stack[$spin_type]['last_bs_v'][$i][$j]) * $betline * $DENOMINATOR;
                                        }
                                    }
                                }
                            }
                            if($spin_type == 'spins'){
                                if(isset($stack[$spin_type]['bs']) && count($stack[$spin_type]['bs']) > 0){
                                    foreach( $stack[$spin_type]['bs'] as $index => $value ){
                                        if(isset($value['value']) && $value['value'] > 0){
                                            $value['value'] = str_replace(',', '', $value['value']) * $betline * $DENOMINATOR;
                                            $stack[$spin_type]['bs'][$index] = $value;
                                        }
                                    }
                                }

                                if(isset($stack[$spin_type]['rewind_bs']) && count($stack[$spin_type]['rewind_bs']) > 0){
                                    foreach( $stack[$spin_type]['rewind_bs'] as $index => $value ){
                                        if(isset($value['value']) && $value['value'] > 0){
                                            $value['value'] = str_replace(',', '', $value['value']) * $betline * $DENOMINATOR;
                                            $stack[$spin_type]['rewind_bs'][$index] = $value;
                                        }
                                    }
                                }
                            }else{                                      
                                if(isset($stack[$spin_type]['bs']) && count($stack[$spin_type]['bs']) > 0){
                                    foreach( $stack[$spin_type]['bs'] as $index => $value ){
                                        if(isset($value['value']) && $value['value'] > 0){
                                            $value['value'] = str_replace(',', '', $value['value']) * $betline * $DENOMINATOR;
                                            $stack[$spin_type]['bs'][$index] = $value;
                                        }
                                    }
                                }
                                if(isset($stack[$spin_type]['bs_v'])){
                                    for($i = 0; $i < count($stack[$spin_type]['bs_v']); $i++){
                                        for($j = 0; $j < 3; $j++){
                                            if(is_numeric(str_replace(',', '', $stack[$spin_type]['bs_v'][$i][$j]))){
                                                $stack[$spin_type]['bs_v'][$i][$j] = str_replace(',', '', $stack[$spin_type]['bs_v'][$i][$j]) * $betline * $DENOMINATOR;
                                            }
                                        }
                                    }
                                }

                                if(isset($stack[$spin_type]['sticky_bs'])){
                                    foreach( $stack[$spin_type]['sticky_bs'] as $index => $value ){
                                        if(isset($value['value']) && $value['value'] > 0){
                                            $value['value'] = str_replace(',', '', $value['value']) * $betline * $DENOMINATOR;
                                            $stack[$spin_type]['sticky_bs'][$index] = $value;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $objRes = [
                        'command' => $slotEvent['command'],
                        'context' => [
                            'achievements' => $archievement,
                            'last_action' => $action['name'],
                            'last_args' => [],
                            'last_win' => ($slotSettings->GetGameData($slotSettings->slotId . 'LastWin') ?? 0) * $DENOMINATOR,
                            'math_version' => 'a',
                            'round_finished' => false,
                            'version' => 1
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
                        $objRes['context']['actions'] = ['spin', 'buy_spin'];
                        $objRes['context']['current'] = 'spins';
                    }else if($action['name'] == 'bonus_init'){
                        $objRes['context']['actions'] = ['respin'];
                        $objRes['context']['current'] = 'bonus';
                    }else if($action['name'] == 'bonus_spins_stop' || $action['name'] == 'bonus_freespins_stop'){
                        $slotSettings->SetGameData($slotSettings->slotId . 'CurrentRespin', 0);
                        if($totalFreeGames <= 0){
                            $objRes['context']['actions'] = ['spin', 'buy_spin'];
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
                                'achievements' => ['left_level'=>0,'left_level_percent'=>0,'left_number'=>0,'left_total_percent'=>0,'right_level'=>0,'right_level_percent'=>0,'right_number'=>0,'right_total_percent'=>0],
                                'actions' => ['spin', 'buy_spin'],
                                'current' => 'spins',
                                'last_action' => 'init',
                                'last_args' => [
                                ],
                                'round_finished' => true,
                                'spins' => [
                                    'bet_per_line' => $betline,
                                    'board' => [[3,12,3],[10,10,12],[4,11,10],[10,10,12],[5,12,5]],
                                    'bs' => [["position"=>1,"reel"=>0,"value"=> 50 * $betline * $LINES],["position"=>2,"reel"=>1,"value"=> 1000 * $betline * $LINES],["position"=>2,"reel"=>3,"value"=>20 * $betline * $LINES],["position"=>1,"reel"=>4,"value"=> 5 * $betline * $LINES]],
                                    'bs_count' => 4,
                                    "is_init_board"=>true,
                                    "last_bet" => $betline,
                                    'lines' => 25,
                                    "reelset_number"=>0,
                                    'round_bet' => $LINES * $betline,
                                    'round_win' => 0,
                                    'total_win' => 0,
                                ],
                                'version' => 1
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
                    $isBuyFreeSpin = false;
                    $pur = -1;
                    if($slotEvent['slotEvent'] != 'freespin' && $slotEvent['slotEvent'] != 'respin'){
                        $allBet = $betline * $LINES;                        
                        $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreespin', 0);
                        if($action['name'] == 'buy_spin'){
                            $allBet = $allBet * 75;
                            $isBuyFreeSpin = true;
                            $pur = 0;
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

                        $tumbAndFreeStacks = $slotSettings->GetReelStrips($winType, ($betline * $LINES), $pur);
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
                    $moneyCounts = [0,0,0];
                    
                    for($k = 0; $k < 3; $k++){
                        $spin_type = $spin_types[$k];
                        if($stack[$spin_type] != ''){
                            $stack[$spin_type]['bet_per_line'] = $betline * $DENOMINATOR;
                            $stack[$spin_type]['round_bet'] = $betline * $DENOMINATOR * $LINES;
                            if(isset($stack[$spin_type]['last_bet'])){
                                $stack[$spin_type]['last_bet'] = $betline * $DENOMINATOR;
                            }
                            $totalWin = 0;
                            if(isset($stack[$spin_type]['round_win'])){
                                $totalWin = $stack[$spin_type]['round_win'] * $betline;
                                $stack[$spin_type]['round_win'] = str_replace(',', '', $stack[$spin_type]['round_win']) * $betline * $DENOMINATOR;
                            }
                            if(isset($stack[$spin_type]['total_win'])){
                                $stack[$spin_type]['total_win'] = str_replace(',', '', $stack[$spin_type]['total_win']) * $betline * $DENOMINATOR;
                            }
                            if(isset($stack[$spin_type]['total_win_state'])){
                                $stack[$spin_type]['total_win_state'] = str_replace(',', '', $stack[$spin_type]['total_win_state']) * $betline * $DENOMINATOR;
                            }       
                            if(isset($stack[$spin_type]['bs']) && count($stack[$spin_type]['bs']) > 0){
                                foreach( $stack[$spin_type]['bs'] as $index => $value ){
                                    if(isset($value['value']) && $value['value'] > 0){
                                        $value['value'] = str_replace(',', '', $value['value']) * $betline * $DENOMINATOR;
                                        $stack[$spin_type]['bs'][$index] = $value;
                                    }
                                }
                            }
                            if(isset($stack[$spin_type]['bs_2']) && count($stack[$spin_type]['bs_2']) > 0){
                                foreach( $stack[$spin_type]['bs_2'] as $index => $value ){
                                    if(isset($value['value']) && $value['value'] > 0){
                                        $value['value'] = str_replace(',', '', $value['value']) * $betline * $DENOMINATOR;
                                        $stack[$spin_type]['bs_2'][$index] = $value;
                                    }
                                }
                            }
                            if(isset($stack[$spin_type]['last_bs']) && count($stack[$spin_type]['last_bs']) > 0){
                                foreach( $stack[$spin_type]['last_bs'] as $index => $value ){
                                    if(isset($value['value']) && $value['value'] > 0){
                                        $value['value'] = str_replace(',', '', $value['value']) * $betline * $DENOMINATOR;
                                        $stack[$spin_type]['last_bs'][$index] = $value;
                                    }
                                }
                            }
                            if(isset($stack[$spin_type]['bs_v'])){
                                for($i = 0; $i < count($stack[$spin_type]['bs_v']); $i++){
                                    for($j = 0; $j < 3; $j++){
                                        if(is_numeric(str_replace(',', '', $stack[$spin_type]['bs_v'][$i][$j]))){
                                            $stack[$spin_type]['bs_v'][$i][$j] = str_replace(',', '', $stack[$spin_type]['bs_v'][$i][$j]) * $betline * $DENOMINATOR;
                                        }
                                    }
                                }
                            }
                            if(isset($stack[$spin_type]['bs_v_2'])){
                                for($i = 0; $i < count($stack[$spin_type]['bs_v_2']); $i++){
                                    for($j = 0; $j < 3; $j++){
                                        if(is_numeric(str_replace(',', '', $stack[$spin_type]['bs_v_2'][$i][$j]))){
                                            $stack[$spin_type]['bs_v_2'][$i][$j] = str_replace(',', '', $stack[$spin_type]['bs_v_2'][$i][$j]) * $betline * $DENOMINATOR;
                                        }
                                    }
                                }
                            }
                            if(isset($stack[$spin_type]['last_bs_v'])){
                                for($i = 0; $i < count($stack[$spin_type]['last_bs_v']); $i++){
                                    for($j = 0; $j < 3; $j++){
                                        if(is_numeric(str_replace(',', '', $stack[$spin_type]['last_bs_v'][$i][$j]))){
                                            $stack[$spin_type]['last_bs_v'][$i][$j] = str_replace(',', '', $stack[$spin_type]['last_bs_v'][$i][$j]) * $betline * $DENOMINATOR;
                                        }
                                    }
                                }
                            }

                            if(isset($stack[$spin_type]['rewind_bs']) && count($stack[$spin_type]['rewind_bs']) > 0){
                                foreach( $stack[$spin_type]['rewind_bs'] as $index => $value ){
                                    if(isset($value['value']) && $value['value'] > 0){
                                        $value['value'] = str_replace(',', '', $value['value']) * $betline * $DENOMINATOR;
                                        $stack[$spin_type]['rewind_bs'][$index] = $value;
                                    }
                                }
                            }

                            if(isset($stack[$spin_type]['sticky_bs'])){
                                foreach( $stack[$spin_type]['sticky_bs'] as $index => $value ){
                                    if(isset($value['value']) && $value['value'] > 0){
                                        $value['value'] = str_replace(',', '', $value['value']) * $betline * $DENOMINATOR;
                                        $stack[$spin_type]['sticky_bs'][$index] = $value;
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
                                        
                                        if(isset($value['fake_collect'])){
                                            $moneyCounts = [0,0,0];
                                            if($value['fake_collect'] == 'left'){
                                                $moneyCounts[0] = 1;
                                            }else if($value['fake_collect'] == 'right'){
                                                $moneyCounts[1] = 1;
                                            }else if($value['fake_collect'] == 'both'){
                                                $moneyCounts[2] = 1;
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
                    for($k = 0; $k < 2; $k++){
                        if($moneyCounts[$k] > 0){
                            if($currentHill[$k][0] == 0){
                                $currentHill[$k][0]++;
                                $currentHill[$k][1] = 0;
                            }else if($currentHill[$k][0] < 4){
                                $tempValue = 0;
                                if($currentHill[$k][0] == 1){
                                    $tempValue = 0.25;
                                }else if($currentHill[$k][0] == 2){
                                    $tempValue = 0.2;
                                }else{
                                    $tempValue = 1/3;
                                }
                                $currentHill[$k][1] += $tempValue;
                                if($currentHill[$k][1] >= 0.9){
                                    $currentHill[$k][0]++;
                                    $currentHill[$k][1] = 0;
                                }
                            }else if($currentHill[$k][0] == 4){
                                $tempValue = 1/3;
                                $currentHill[$k][1] += $tempValue;
                                if($currentHill[$k][1] >= 0.66){
                                    $currentHill[$k][0]++;
                                    $currentHill[$k][1] = 0.8333333333333334;
                                }
                            }else if($currentHill[$k][0] == 5){
                                $currentHill[$k][0] = 5;
                                $currentHill[$k][1] = 0.8333333333333334;
                            }
                            if($currentHill[$k][2] < 16){
                                $currentHill[$k][2]++;
                                $currentHill[$k][3] += 0.0476;
                            }else{
                                $currentHill[$k][2] = 16;
                                $currentHill[$k][3] = 0.762;
                            }
                            if($currentHill[$k][0] == 6){
                                $currentHill[$k][0] = 1;
                                $currentHill[$k][1] = 0.0;
                                $currentHill[$k][2] = 1;
                                $currentHill[$k][3] = 0.25;
                            }
                        }
                    }
                    if($moneyCounts[2] == 1){
                        for($k = 0; $k < 2; $k++){
                            if($currentHill[$k][0] == 0){
                                $currentHill[$k][0]++;
                                $currentHill[$k][1] = 0;
                            }else if($currentHill[$k][0] < 4){
                                $tempValue = 0;
                                if($currentHill[$k][0] == 1){
                                    $tempValue = 0.25;
                                }else if($currentHill[$k][0] == 2){
                                    $tempValue = 0.2;
                                }else{
                                    $tempValue = 1/3;
                                }
                                $currentHill[$k][1] += $tempValue;
                                if($currentHill[$k][1] >= 0.9){
                                    $currentHill[$k][0]++;
                                    $currentHill[$k][1] = 0;
                                }
                            }else if($currentHill[$k][0] == 4){
                                $tempValue = 1/3;
                                $currentHill[$k][1] += $tempValue;
                                if($currentHill[$k][1] >= 0.66){
                                    $currentHill[$k][0]++;
                                    $currentHill[$k][1] = 0.8333333333333334;
                                }
                            }else if($currentHill[$k][0] == 5){
                                $currentHill[$k][0] = 5;
                                $currentHill[$k][1] = 0.8333333333333334;
                            }
                            if($currentHill[$k][2] < 16){
                                $currentHill[$k][2]++;
                                $currentHill[$k][3] += 0.0476;
                            }else{
                                $currentHill[$k][2] = 16;
                                $currentHill[$k][3] = 0.762;
                            }
                            if($currentHill[$k][0] == 6){
                                $currentHill[$k][0] = 1;
                                $currentHill[$k][1] = 0.0;
                                $currentHill[$k][2] = 1;
                                $currentHill[$k][3] = 0.25;
                            }
                        }
                    }

                    $slotSettings->SetGameData($slotSettings->slotId . 'Hill', $currentHill);
                    $slotSettings->SetGameData($slotSettings->slotId . 'HillSide', $moneyCounts);
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
                    $archievement = [
                        "left_level" => $currentHill[0][0],
                        "left_level_percent" => $currentHill[0][1],
                        "left_number" => $currentHill[0][2],
                        "left_total_percent" => $currentHill[0][3],
                        "right_level" => $currentHill[1][0],
                        "right_level_percent" => $currentHill[1][1],
                        "right_number" => $currentHill[1][2],
                        "right_total_percent" => $currentHill[1][3],
                    ];
                    $objRes = [
                        'command' => $slotEvent['command'],
                        'context' => [
                            'achievements' => $archievement,
                            'actions' => ['spin', 'buy_spin'],
                            'current' => 'spins',
                            'last_args' => [
                                'bet_per_line' => $betline * $DENOMINATOR,
                                'lines' => 25
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
                    $isState = true;                    
                    if($stack['bonus'] != ''){
                        $objRes['context']['bonus'] = $stack['bonus'];
                        $objRes['context']['bonus']['bac'] = $currentHill;
                    }
                    if($stack['freespins'] != ''){
                        $objRes['context']['freespins'] = $stack['freespins'];
                        $objRes['context']['freespins']['bac'] = $currentHill;
                    }
                    if($stack['spins'] != ''){
                        $objRes['context']['spins'] = $stack['spins'];
                        $objRes['context']['spins']['bac'] = $currentHill;
                    }
                    $objRes['context']['actions'] = $stack['actions'];
                    if( $slotEvent['slotEvent'] == 'freespin' || $slotEvent['slotEvent'] == 'respin') 
                    {
                        $isState = false;
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $slotSettings->GetGameData($slotSettings->slotId . 'BonusWin') + $totalWin);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
                        
                        if($slotEvent['slotEvent'] == 'respin'){
                            $rounds_left = $stack['bonus']['rounds_left'];
                            if($rounds_left == 0 && isset($stack['bonus']['rounds_left_2'])){
                                $rounds_left = $stack['bonus']['rounds_left_2'];
                            }
                        }else{
                            $round_left_free = $slotSettings->GetGameData($slotSettings->slotId . 'RoundLeft');
                            
                            $rounds_left = $stack['freespins']['rounds_left'];
                            $slotSettings->SetGameData($slotSettings->slotId . 'RoundLeft',$rounds_left);
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
                            $hillSide = $slotSettings->GetGameData($slotSettings->slotId . 'HillSide');
                            if($hillSide[0] == 1){
                                $objRes['context']['achievements'] = [
                                    "left_level" => 6,
                                    "left_level_percent" => 1.0,
                                    "left_number" => $currentHill[0][2],
                                    "left_total_percent" => 1.0,
                                    "right_level" => $currentHill[1][0],
                                    "right_level_percent" => $currentHill[1][1],
                                    "right_number" => $currentHill[1][2],
                                    "right_total_percent" => $currentHill[1][3],
                                ];
                                $currentHill[0][0] = 0;
                                $currentHill[0][1] = 0;
                                $currentHill[0][2] = 0;
                                $currentHill[0][3] = 0;
                            }else if($hillSide[1] == 1){
                                $objRes['context']['achievements'] = [
                                    "left_level" => $currentHill[0][0],
                                    "left_level_percent" => $currentHill[0][1],
                                    "left_number" => $currentHill[0][2],
                                    "left_total_percent" => $currentHill[0][3],
                                    "right_level" => 6,
                                    "right_level_percent" => 1.0,
                                    "right_number" => $currentHill[1][2],
                                    "right_total_percent" => 1.0,
                                ];
                                $currentHill[1][0] = 0;
                                $currentHill[1][1] = 0;
                                $currentHill[1][2] = 0;
                                $currentHill[1][3] = 0;
                            }else{
                                $objRes['context']['achievements'] = [
                                    "left_level" => 6,
                                    "left_level_percent" => 1.0,
                                    "left_number" => $currentHill[0][2],
                                    "left_total_percent" => 1.0,
                                    "right_level" => 6,
                                    "right_level_percent" => 1.0,
                                    "right_number" => $currentHill[1][2],
                                    "right_total_percent" => 1.0,
                                ];
                                $currentHill[0][0] = 0;
                                $currentHill[0][1] = 0;
                                $currentHill[0][2] = 0;
                                $currentHill[0][3] = 0;
                                $currentHill[1][0] = 0;
                                $currentHill[1][1] = 0;
                                $currentHill[1][2] = 0;
                                $currentHill[1][3] = 0;
                            }
                            $slotSettings->SetGameData($slotSettings->slotId . 'Hill', $currentHill);
                        }
                    }
                    if($isState == true && ($slotEvent['slotEvent'] == 'freespin' || $slotEvent['slotEvent'] == 'respin')){
                        if($slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') > 0){
                            $slotSettings->SetGameData($slotSettings->slotId . 'LastWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'));
                            $objRes['last_win'] = ($slotSettings->GetGameData($slotSettings->slotId . 'LastWin') ?? 0) * $DENOMINATOR;
                        }
                    }
                }
                $allBet = $betline * $LINES;
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreespin') == 1){{
                    $allBet = $allBet * 75;
                }}
                $objRes['context']['last_action'] = $action['name'];
                if($action['name'] == 'freespin_init'){
                    
                }
                $slotSettings->SaveLogReport(json_encode($objRes), $allBet, $LINES, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                if($action['name'] == 'spin' || $action['name'] == 'buy_spin' || $action['name'] == 'freespin' || $action['name'] == 'freespin_stop' || $action['name'] == 'respin' || $action['name'] == 'bonus_spins_stop' || $action['name'] == 'bonus_freespins_stop'){
                    if($action['name'] == 'spin'){
                        $this->SaveBNGLogParse($beforeBalance, $BALANCE, $betline * $LINES, $totalWin, $objRes, $slotSettings);
                    }else if($action['name'] == 'buy_spin'){
                        $this->SaveBNGLogParse($beforeBalance, $BALANCE, $betline * $LINES * 75, $totalWin, $objRes, $slotSettings);
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
                'round_win' => $spins['round_win'] ?? 0,
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

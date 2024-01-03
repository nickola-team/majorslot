<?php 
namespace VanguardLTE\Games\_3hotchilliesbng
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
            $LINES = 50;
            
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
                            'lines' => 25,
                        ],
                        'last_win' => ($slotSettings->GetGameData($slotSettings->slotId . 'LastWin') ?? 0) * $DENOMINATOR,
                        'math_version' => 'a',
                        'round_finished' => true,
                        'spins' => [
                            'bac' => [
                                '15' => [0, 0],
                                '16' => [0, 0],
                                '17' => [0, 0]
                            ],
                            'bet_per_line' => $bets[9],
                            'board' => [[8,15,7],[2,12,12],[11,17,4],[2,12,12],[10,16,6]],
                            'bs' => [['position'=>1,'reel'=>0,'value'=>2500000],['position'=>1,'reel'=>2,'value'=>15000000],['position'=>1,'reel'=>4,'value'=>5000000]],
                            'bs_count' => 3,
                            'bs_v' => [[0,2500000,0],[0,0,0],[0,'minor',0],[0,0,0],[0,5000000,0]],
                            'bs_values' => [[0,5,0],[0,0,0],[0,30,0],[0,0,0],[0,10,0]],
                            'for_shifter' => ['bf'=>[[13,2,1,13,3,4],[13,2,4,13,3,5],[13,2,3,13,1,4],[6,2,4,8,3,5],[7,2,3,9,1,4]],'spins'=>[[14,14,14,14,14,14,14,14,5,6,9,13,7,8,9,10,11,4,7,1,13,2,6,8,9,3,5,7,5,9,6,13,1,4,10,4,8,9,6,1,13,1,6,9,10,5,1,8,1,8,10,6,13,6,9,10,5,8,4,5],[2,3,14,14,14,14,14,14,14,6,9,8,6,5,4,13,2,9,2,8,7,6,9,6,2,13,1,5,7,2,11,13,5,7,5,10,9,12,12,12,12,12,12,12,12,2,3,8,3,10,9,13,10,6,7,2,3,9,4,7],[8,3,11,2,8,1,4,14,14,14,14,14,14,14,14,5,9,6,9,4,13,7,9,2,10,3,13,6,8,4,3,12,12,12,12,12,12,12,12,1,11,1,8,6,9,7,4,1,8,5,13,4,8,2,10,7,3,9,3,7],[1,7,3,1,14,14,14,14,14,14,14,3,9,1,9,2,8,13,6,9,7,4,10,4,12,12,12,12,12,12,12,12,5,6,8,6,5,11,1,13,10,4,5,9,5,10,8,2,3,13,2,3,10,7,4,8],[4,3,13,5,2,7,6,10,13,2,7,5,11,4,7,1,8,6,13,6,8,3,10,4,8,7,6,14,14,14,14,14,2,5,8,6,10,1,9,13,11,6,9,2,7,6,8,9,5,7,12,12,12,12,12,12,12,12,4,1]]],
                            'for_shifter_bg_sets' => ['Double'=>['backtrack_table'=>['1'=>[[1,0,0,0],[0,1,0,0],[0,1,1,0],[0,1,1,1],[0,1,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,1,1,1],[0,1,1,1],[0,1,1,1]],'2'=>[[1,0,0,0],[1,1,0,0],[1,1,1,0],[1,1,1,1],[1,1,1,1],[1,0,0,0],[1,1,1,0],[1,1,1,1],[1,0,0,0],[1,1,1,0],[1,1,1,1],[1,0,0,0],[1,1,1,0],[1,1,1,1],[1,0,0,0]],'3'=>[[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,0,0],[1,1,1,1],[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,1,1],[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,1,1]]],'bs_values'=>['1'=>950,'10'=>20,'100'=>1,'15'=>125,'2'=>900,'3'=>600,'30'=>55,'4'=>100,'5'=>150,'8'=>5],'init'=>['1'=>['1'=>0,'10'=>350,'11'=>650,'12'=>400,'13'=>250,'14'=>50,'15'=>1,'2'=>0,'3'=>0,'4'=>1000,'5'=>1200,'6'=>850,'7'=>700,'8'=>400,'9'=>150],'2'=>['10'=>350,'11'=>650,'12'=>400,'13'=>250,'14'=>50,'15'=>1,'2'=>0,'3'=>0,'4'=>1000,'5'=>1200,'6'=>850,'7'=>700,'8'=>400,'9'=>150],'3'=>['10'=>350,'11'=>650,'12'=>400,'13'=>250,'14'=>50,'15'=>1,'3'=>0,'4'=>1000,'5'=>1200,'6'=>850,'7'=>700,'8'=>400,'9'=>150],'4'=>['10'=>350,'11'=>650,'12'=>400,'13'=>250,'14'=>50,'15'=>1,'4'=>1000,'5'=>1200,'6'=>850,'7'=>700,'8'=>400,'9'=>150],'5'=>['10'=>350,'11'=>650,'12'=>400,'13'=>250,'14'=>50,'15'=>1,'5'=>1200,'6'=>850,'7'=>700,'8'=>400,'9'=>150]]],'DoubleUltra'=>['backtrack_table'=>['1'=>[[1,0,0,0],[0,1,0,0],[0,1,1,0],[0,1,1,1],[0,1,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,1,1,1],[0,1,1,1],[0,1,1,1]],'2'=>[[1,0,0,0],[1,1,0,0],[1,1,1,0],[1,1,1,1],[1,1,1,1],[1,0,0,0],[1,1,1,0],[1,1,1,1],[1,0,0,0],[1,1,1,0],[1,1,1,1],[1,0,0,0],[1,1,1,0],[1,1,1,1],[1,0,0,0]],'3'=>[[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,0,0],[1,1,1,1],[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,1,1],[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,1,1]]],'bs_values'=>['1'=>1325,'10'=>15,'100'=>1,'15'=>75,'2'=>600,'3'=>235,'30'=>20,'4'=>100,'5'=>65,'8'=>15],'init'=>['1'=>['1'=>0,'10'=>350,'11'=>650,'12'=>400,'13'=>250,'14'=>50,'15'=>1,'2'=>0,'3'=>0,'4'=>1000,'5'=>1200,'6'=>850,'7'=>700,'8'=>400,'9'=>150],'2'=>['10'=>350,'11'=>650,'12'=>400,'13'=>250,'14'=>50,'15'=>1,'2'=>0,'3'=>0,'4'=>1000,'5'=>1200,'6'=>850,'7'=>700,'8'=>400,'9'=>150],'3'=>['10'=>350,'11'=>650,'12'=>400,'13'=>250,'14'=>50,'15'=>1,'3'=>0,'4'=>1000,'5'=>1200,'6'=>850,'7'=>700,'8'=>400,'9'=>150],'4'=>['10'=>350,'11'=>650,'12'=>400,'13'=>250,'14'=>50,'15'=>1,'4'=>1000,'5'=>1200,'6'=>850,'7'=>700,'8'=>400,'9'=>150],'5'=>['10'=>350,'11'=>650,'12'=>400,'13'=>250,'14'=>50,'15'=>1,'5'=>1200,'6'=>850,'7'=>700,'8'=>400,'9'=>150]]],'Extra'=>['backtrack_table'=>['1'=>[[1,0,0,0],[0,1,0,0],[0,1,1,0],[0,1,1,1],[0,1,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,1,1,1],[0,1,1,1],[0,1,1,1]],'2'=>[[1,0,0,0],[1,1,0,0],[1,1,1,0],[1,1,1,1],[1,1,1,1],[1,0,0,0],[1,1,1,0],[1,1,1,1],[1,0,0,0],[1,1,1,0],[1,1,1,1],[1,0,0,0],[1,1,1,0],[1,1,1,1],[1,0,0,0]],'3'=>[[1,0,0,0],[1,1,0,0],[1,1,1,0],[1,1,1,1],[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,1,0],[1,1,1,1],[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,1,0],[1,1,1,1],[1,1,1,1]],'4'=>[[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,0,0],[1,1,1,1],[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,1,1],[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,1,1]]],'bs_values'=>['1'=>1100,'10'=>5,'100'=>1,'15'=>150,'2'=>700,'3'=>150,'30'=>50,'4'=>80,'5'=>200,'8'=>35],'init'=>['1'=>['1'=>0,'10'=>1250,'11'=>1200,'12'=>900,'13'=>700,'14'=>100,'15'=>1,'2'=>0,'3'=>0,'4'=>50,'5'=>200,'6'=>700,'7'=>800,'8'=>1100,'9'=>1200],'2'=>['10'=>1250,'11'=>1200,'12'=>900,'13'=>700,'14'=>100,'15'=>1,'2'=>0,'3'=>0,'4'=>50,'5'=>200,'6'=>700,'7'=>800,'8'=>1100,'9'=>1200],'3'=>['10'=>1250,'11'=>1200,'12'=>900,'13'=>700,'14'=>100,'15'=>1,'3'=>0,'4'=>50,'5'=>200,'6'=>700,'7'=>800,'8'=>1100,'9'=>1200],'4'=>['10'=>1250,'11'=>1200,'12'=>900,'13'=>700,'14'=>100,'15'=>1,'4'=>50,'5'=>200,'6'=>700,'7'=>800,'8'=>1100,'9'=>1200],'5'=>['10'=>1250,'11'=>1200,'12'=>900,'13'=>700,'14'=>100,'15'=>1,'5'=>200,'6'=>700,'7'=>800,'8'=>1100,'9'=>1200]]],'ExtraDouble'=>['backtrack_table'=>['1'=>[[1,0,0,0],[0,1,0,0],[0,1,1,0],[0,1,1,1],[0,1,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,1,1,1],[0,1,1,1],[0,1,1,1]],'2'=>[[1,0,0,0],[1,1,0,0],[1,1,1,0],[1,1,1,1],[1,1,1,1],[1,0,0,0],[1,1,1,0],[1,1,1,1],[1,0,0,0],[1,1,1,0],[1,1,1,1],[1,0,0,0],[1,1,1,0],[1,1,1,1],[1,0,0,0]],'3'=>[[1,0,0,0],[1,1,0,0],[1,1,1,0],[1,1,1,1],[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,1,0],[1,1,1,1],[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,1,0],[1,1,1,1],[1,1,1,1]],'4'=>[[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,0,0],[1,1,1,1],[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,1,1],[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,1,1]]],'bs_values'=>['1'=>1150,'10'=>5,'100'=>1,'15'=>145,'2'=>900,'3'=>600,'30'=>50,'4'=>100,'5'=>355,'8'=>5],'init'=>['1'=>['1'=>0,'10'=>1100,'11'=>1150,'12'=>1250,'13'=>1000,'14'=>500,'15'=>1,'2'=>0,'3'=>0,'4'=>1000,'5'=>1100,'6'=>750,'7'=>500,'8'=>350,'9'=>600],'2'=>['10'=>1100,'11'=>1150,'12'=>1250,'13'=>1000,'14'=>500,'15'=>1,'2'=>0,'3'=>0,'4'=>1000,'5'=>1100,'6'=>750,'7'=>500,'8'=>350,'9'=>600],'3'=>['10'=>1100,'11'=>1150,'12'=>1250,'13'=>1000,'14'=>500,'15'=>1,'3'=>0,'4'=>1000,'5'=>1100,'6'=>750,'7'=>500,'8'=>350,'9'=>600],'4'=>['10'=>1100,'11'=>1150,'12'=>1250,'13'=>1000,'14'=>500,'15'=>1,'4'=>1000,'5'=>1100,'6'=>750,'7'=>500,'8'=>350,'9'=>600],'5'=>['10'=>1100,'11'=>1150,'12'=>1250,'13'=>1000,'14'=>500,'15'=>1,'5'=>1100,'6'=>750,'7'=>500,'8'=>350,'9'=>600]]],'ExtraDoubleUltra'=>['backtrack_table'=>['1'=>[[1,0,0,0],[0,1,0,0],[0,1,1,0],[0,1,1,1],[0,1,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,1,1,1],[0,1,1,1],[0,1,1,1]],'2'=>[[1,0,0,0],[1,1,0,0],[1,1,1,0],[1,1,1,1],[1,1,1,1],[1,0,0,0],[1,1,1,0],[1,1,1,1],[1,0,0,0],[1,1,1,0],[1,1,1,1],[1,0,0,0],[1,1,1,0],[1,1,1,1],[1,0,0,0]],'3'=>[[1,0,0,0],[1,1,0,0],[1,1,1,0],[1,1,1,1],[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,1,0],[1,1,1,1],[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,1,0],[1,1,1,1],[1,1,1,1]],'4'=>[[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,0,0],[1,1,1,1],[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,1,1],[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,1,1]]],'bs_values'=>['1'=>1175,'10'=>30,'100'=>2,'15'=>135,'2'=>700,'3'=>250,'30'=>50,'4'=>100,'5'=>500,'8'=>5],'init'=>['1'=>['1'=>0,'10'=>1100,'11'=>1150,'12'=>1250,'13'=>1000,'14'=>500,'15'=>1,'2'=>0,'3'=>0,'4'=>1000,'5'=>1100,'6'=>750,'7'=>500,'8'=>350,'9'=>600],'2'=>['10'=>1100,'11'=>1150,'12'=>1250,'13'=>1000,'14'=>500,'15'=>1,'2'=>0,'3'=>0,'4'=>1000,'5'=>1100,'6'=>750,'7'=>500,'8'=>350,'9'=>600],'3'=>['10'=>1100,'11'=>1150,'12'=>1250,'13'=>1000,'14'=>500,'15'=>1,'3'=>0,'4'=>1000,'5'=>1100,'6'=>750,'7'=>500,'8'=>350,'9'=>600],'4'=>['10'=>1100,'11'=>1150,'12'=>1250,'13'=>1000,'14'=>500,'15'=>1,'4'=>1000,'5'=>1100,'6'=>750,'7'=>500,'8'=>350,'9'=>600],'5'=>['10'=>1100,'11'=>1150,'12'=>1250,'13'=>1000,'14'=>500,'15'=>1,'5'=>1100,'6'=>750,'7'=>500,'8'=>350,'9'=>600]]],'ExtraUltra'=>['backtrack_table'=>['1'=>[[1,0,0,0],[0,1,0,0],[0,1,1,0],[0,1,1,1],[0,1,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,1,1,1],[0,1,1,1],[0,1,1,1]],'2'=>[[1,0,0,0],[1,1,0,0],[1,1,1,0],[1,1,1,1],[1,1,1,1],[1,0,0,0],[1,1,1,0],[1,1,1,1],[1,0,0,0],[1,1,1,0],[1,1,1,1],[1,0,0,0],[1,1,1,0],[1,1,1,1],[1,0,0,0]],'3'=>[[1,0,0,0],[1,1,0,0],[1,1,1,0],[1,1,1,1],[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,1,0],[1,1,1,1],[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,1,0],[1,1,1,1],[1,1,1,1]],'4'=>[[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,0,0],[1,1,1,1],[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,1,1],[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,1,1]]],'bs_values'=>['1'=>1175,'10'=>80,'100'=>6,'15'=>150,'2'=>900,'3'=>600,'30'=>55,'4'=>250,'5'=>250,'8'=>10],'init'=>['1'=>['1'=>0,'10'=>1100,'11'=>900,'12'=>800,'13'=>700,'14'=>250,'15'=>1,'2'=>0,'3'=>0,'4'=>100,'5'=>400,'6'=>700,'7'=>1000,'8'=>1100,'9'=>1200],'2'=>['10'=>1100,'11'=>900,'12'=>800,'13'=>700,'14'=>250,'15'=>1,'2'=>0,'3'=>0,'4'=>100,'5'=>400,'6'=>700,'7'=>1000,'8'=>1100,'9'=>1200],'3'=>['10'=>1100,'11'=>900,'12'=>800,'13'=>700,'14'=>250,'15'=>1,'3'=>0,'4'=>100,'5'=>400,'6'=>700,'7'=>1000,'8'=>1100,'9'=>1200],'4'=>['10'=>1100,'11'=>900,'12'=>800,'13'=>700,'14'=>250,'15'=>1,'4'=>100,'5'=>400,'6'=>700,'7'=>1000,'8'=>1100,'9'=>1200],'5'=>['10'=>1100,'11'=>900,'12'=>800,'13'=>700,'14'=>250,'15'=>1,'5'=>400,'6'=>700,'7'=>1000,'8'=>1100,'9'=>1200]]],'Main'=>['bs_values'=>['1'=>360,'10'=>40,'100'=>7,'15'=>188,'2'=>320,'3'=>200,'30'=>120,'4'=>40,'5'=>40,'8'=>40]],'Ultra'=>['backtrack_table'=>['1'=>[[1,0,0,0],[0,1,0,0],[0,1,1,0],[0,1,1,1],[0,1,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,0,1,1],[0,1,1,1],[0,1,1,1],[0,1,1,1]],'2'=>[[1,0,0,0],[1,1,0,0],[1,1,1,0],[1,1,1,1],[1,1,1,1],[1,0,0,0],[1,1,1,0],[1,1,1,1],[1,0,0,0],[1,1,1,0],[1,1,1,1],[1,0,0,0],[1,1,1,0],[1,1,1,1],[1,0,0,0]],'3'=>[[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,0,0],[1,1,1,1],[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,1,1],[1,0,0,0],[1,1,0,0],[1,0,0,0],[1,1,1,1]]],'bs_values'=>['1'=>1300,'10'=>55,'100'=>1,'15'=>80,'2'=>600,'3'=>220,'30'=>30,'4'=>55,'5'=>110,'8'=>15],'init'=>['1'=>['1'=>0,'10'=>350,'11'=>100,'12'=>10,'13'=>5,'14'=>1,'15'=>1,'2'=>0,'3'=>0,'4'=>500,'5'=>800,'6'=>1000,'7'=>1200,'8'=>1100,'9'=>900],'2'=>['10'=>350,'11'=>100,'12'=>10,'13'=>5,'14'=>1,'15'=>1,'2'=>0,'3'=>0,'4'=>500,'5'=>800,'6'=>1000,'7'=>1200,'8'=>1100,'9'=>900],'3'=>['10'=>350,'11'=>100,'12'=>10,'13'=>5,'14'=>1,'15'=>1,'3'=>0,'4'=>500,'5'=>800,'6'=>1000,'7'=>1200,'8'=>1100,'9'=>900],'4'=>['10'=>350,'11'=>100,'12'=>10,'13'=>5,'14'=>1,'15'=>1,'4'=>500,'5'=>800,'6'=>1000,'7'=>1200,'8'=>1100,'9'=>900],'5'=>['10'=>350,'11'=>100,'12'=>10,'13'=>5,'14'=>1,'15'=>1,'5'=>800,'6'=>1000,'7'=>1200,'8'=>1100,'9'=>900]]]],
                            'for_shifter_uf_multipliers' => ['2'=>18198,'3'=>10110,'5'=>1011],
                            'for_shifter_uf_multipliers_jpt' => ['2'=>29000,'3'=>290,'5'=>29],
                            'lines' => 25,
                            'round_bet' => $bets[9] * $LINES,
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
                    'settings' => json_decode('{"bet_factor":[50],"bets":['. implode(',', $bets) .'],"big_win":[15,20,30,50,70],"bs_values_reels":{"1":360,"2":320,"3":200,"4":40,"5":40,"8":40,"10":40,"15":188,"30":120,"100":7},"cols":5,"currency_format":{"currency_style":"symbol","denominator":100,"style":"money"},"freespins_buying_price":70,"jackpots":{"15":"mini","30":"minor","100":"major","1000":"grand"},"lines":[25],"paylines":[[1,1,1,1,1],[0,0,0,0,0],[2,2,2,2,2],[0,1,2,1,0],[2,1,0,1,2],[1,0,0,0,1],[1,2,2,2,1],[0,0,1,2,2],[2,2,1,0,0],[1,2,1,0,1],[1,0,1,2,1],[0,1,1,1,0],[2,1,1,1,2],[0,1,0,1,0],[2,1,2,1,2],[1,1,0,1,1],[1,1,2,1,1],[0,0,2,0,0],[2,2,0,2,2],[0,2,2,2,0],[2,0,0,0,2],[1,2,0,2,1],[1,0,2,0,1],[0,2,0,2,0],[2,0,2,0,2]],"paytable":{"1":[{"multiplier":5,"occurrences":3,"type":"lb"},{"multiplier":10,"occurrences":4,"type":"lb"},{"multiplier":50,"occurrences":5,"type":"lb"}],"2":[{"multiplier":5,"occurrences":3,"type":"lb"},{"multiplier":10,"occurrences":4,"type":"lb"},{"multiplier":50,"occurrences":5,"type":"lb"}],"3":[{"multiplier":5,"occurrences":3,"type":"lb"},{"multiplier":10,"occurrences":4,"type":"lb"},{"multiplier":50,"occurrences":5,"type":"lb"}],"4":[{"multiplier":5,"occurrences":3,"type":"lb"},{"multiplier":10,"occurrences":4,"type":"lb"},{"multiplier":50,"occurrences":5,"type":"lb"}],"5":[{"multiplier":5,"occurrences":3,"type":"lb"},{"multiplier":10,"occurrences":4,"type":"lb"},{"multiplier":50,"occurrences":5,"type":"lb"}],"6":[{"multiplier":10,"occurrences":3,"type":"lb"},{"multiplier":20,"occurrences":4,"type":"lb"},{"multiplier":75,"occurrences":5,"type":"lb"}],"7":[{"multiplier":10,"occurrences":3,"type":"lb"},{"multiplier":20,"occurrences":4,"type":"lb"},{"multiplier":75,"occurrences":5,"type":"lb"}],"8":[{"multiplier":15,"occurrences":3,"type":"lb"},{"multiplier":40,"occurrences":4,"type":"lb"},{"multiplier":100,"occurrences":5,"type":"lb"}],"9":[{"multiplier":15,"occurrences":3,"type":"lb"},{"multiplier":40,"occurrences":4,"type":"lb"},{"multiplier":100,"occurrences":5,"type":"lb"}],"10":[{"multiplier":15,"occurrences":3,"type":"lb"},{"multiplier":40,"occurrences":4,"type":"lb"},{"multiplier":100,"occurrences":5,"type":"lb"}],"11":[{"multiplier":5,"occurrences":2,"type":"lb"},{"multiplier":25,"occurrences":3,"type":"lb"},{"multiplier":100,"occurrences":4,"type":"lb"},{"multiplier":300,"occurrences":5,"type":"lb"}],"12":[{"multiplier":5,"occurrences":2,"type":"lb"},{"multiplier":25,"occurrences":3,"type":"lb"},{"multiplier":100,"occurrences":4,"type":"lb"},{"multiplier":300,"occurrences":5,"type":"lb"}]},"reelsamples":{"bf":[[1,2,3,4,13],[2,3,4,5,13],[1,2,3,4,13],[2,3,4,5,6,8],[1,2,3,4,7,9]],"spins":[[1,2,3,4,5,6,7,8,9,10,11,13],[1,2,3,4,5,6,7,8,9,10,11,12,13],[1,2,3,4,5,6,7,8,9,10,11,12,13],[1,2,3,4,5,6,7,8,9,10,11,12,13],[1,2,3,4,5,6,7,8,9,10,11,12,13]]},"respins_granted":3,"rows":3,"symbols":[{"id":1,"name":"10","type":"line"},{"id":2,"name":"J","type":"line"},{"id":3,"name":"Q","type":"line"},{"id":4,"name":"K","type":"line"},{"id":5,"name":"A","type":"line"},{"id":6,"name":"L1","type":"line"},{"id":7,"name":"L2","type":"line"},{"id":8,"name":"M1","type":"line"},{"id":9,"name":"M2","type":"line"},{"id":10,"name":"M3","type":"line"},{"id":11,"name":"H1","type":"line"},{"id":12,"name":"wild","type":"wild"},{"id":13,"name":"bonus","type":"scat"},{"id":14,"name":"hidden","type":"hide"},{"id":15,"name":"el_bonus_progress_1","type":"scat"},{"id":16,"name":"el_bonus_progress_2","type":"scat"},{"id":17,"name":"el_bonus_progress_3","type":"scat"}],"symbols_line":[1,2,3,4,5,6,7,8,9,10,11],"symbols_scat":[13,15,16,17],"symbols_wild":[12],"transitions":[{"act":{"bet":false,"cheat":false,"name":"init","win":false},"dst":"spins","src":"none"},{"act":{"args":["bet_per_line","lines"],"bet":true,"cheat":true,"name":"spin","win":true},"dst":"spins","src":"spins"},{"act":{"bet":false,"cheat":true,"name":"bonus_init","win":false},"dst":"bonus","src":"spins"},{"act":{"args":["bet_per_line","lines"],"bet":true,"cheat":true,"name":"buy_spin","win":true},"dst":"spins","src":"spins"},{"act":{"bet":false,"cheat":true,"name":"respin","win":true},"dst":"bonus","src":"bonus"},{"act":{"bet":false,"cheat":false,"name":"bonus_spins_stop","win":false},"dst":"spins","src":"bonus"}]}'),
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
                $currentHill = $slotSettings->GetGameData($slotSettings->slotId . 'Hill') ?? ['15' => [0, 0],'16' => [0, 0],'17' => [0, 0]];
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
                            $stack[$spin_type]['bac'] = $currentHill;
                            // if(isset($stack[$spin_type]['bs']) && count($stack[$spin_type]['bs']) > 0){
                            //     foreach( $stack[$spin_type]['bs'] as $index => $value ){
                            //         if(isset($value['value']) && $value['value'] > 0){
                            //             if(isset($stack[$spin_type]['bs_v']) && isset($stack[$spin_type]['bs_v'][$index]))
                            //             $value['value'] = str_replace(',', '', $value['value']) * $betline * $DENOMINATOR;
                            //             $stack[$spin_type]['bs'][$index] = $value;
                            //         }
                            //     }
                            // }
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
                                $arr_bs = [];
                                if(isset($stack[$spin_type]['bs_v'])){
                                    for($i = 0; $i < count($stack[$spin_type]['bs_v']); $i++){
                                        for($j = 0; $j < 3; $j++){
                                            if(is_numeric(str_replace(',', '', $stack[$spin_type]['bs_v'][$i][$j]))){
                                                if($stack[$spin_type]['bs_v'][$i][$j] > 0){
                                                    $stack[$spin_type]['bs_v'][$i][$j] = str_replace(',', '', $stack[$spin_type]['bs_v'][$i][$j]) * $betline * $DENOMINATOR;
                                                    $arr_bs[]= [
                                                        'position' => $j,
                                                        'reel' => $i,
                                                        'value' => $stack[$spin_type]['bs_v'][$i][$j]
                                                    ];
                                                }
                                            }else{
                                                if($stack[$spin_type]['bs_v'][$i][$j] != ''){
                                                    $arr_jackpot = ['mini', 'minor', 'major', 'grand'];
                                                    $arr_jackpot_multi = [15, 30, 100, 1000];
                                                    for($kk = 0; $kk < 4; $kk++){
                                                        if($stack[$spin_type]['bs_v'][$i][$j] == $arr_jackpot[$kk]){
                                                            $arr_bs[]= [
                                                                'position' => $j,
                                                                'reel' => $i,
                                                                'value' => $arr_jackpot_multi[$kk] * $betline * $LINES * $DENOMINATOR
                                                            ];
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                $stack[$spin_type]['bs'] = $arr_bs;
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
                                'actions' => ['spin', 'buy_spin'],
                                'current' => 'spins',
                                'last_args' => [],
                                'last_win' => ($slotSettings->GetGameData($slotSettings->slotId . 'LastWin') ?? 0) * $DENOMINATOR,
                                'math_version' => 'a',
                                'round_finished' => true,
                                'spins' => [
                                    'bac' => [
                                        '15' => [0, 0],
                                        '16' => [0, 0],
                                        '17' => [0, 0]
                                    ],
                                    'board' => [[8,15,7],[2,12,12],[11,17,4],[2,12,12],[10,16,6]],
                                    'bs' => [['position'=>1,'reel'=>0,'value'=>2500000],['position'=>1,'reel'=>2,'value'=>15000000],['position'=>1,'reel'=>4,'value'=>5000000]],
                                    'bs_count' => 3,
                                    'bs_v' => [[0,2500000,0],[0,0,0],[0,'minor',0],[0,0,0],[0,5000000,0]],
                                    'bs_values' => [[0,5,0],[0,0,0],[0,30,0],[0,0,0],[0,10,0]],
                                    'lines' => 25,
                                    'round_bet' => $betline * $DENOMINATOR * $LINES,
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
                    $isBuyFreeSpin = false;
                    $pur = -1;
                    if($slotEvent['slotEvent'] != 'freespin' && $slotEvent['slotEvent'] != 'respin'){
                        $allBet = $betline * $LINES;                        
                        $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreespin', 0);
                        if($action['name'] == 'buy_spin'){
                            $allBet = $allBet * 70;
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
                            if($slotEvent['slotEvent'] != 'respin' && $spin_types[$k] != 'bonus' && isset($stack[$spin_type]['board'])){
                                $moneyCounts = [0,0,0];
                                for($i = 0; $i < 5; $i++){
                                    for($j = 0; $j < 3; $j++){
                                        if($stack[$spin_type]['board'][$i][$j] == 15){
                                            $moneyCounts[0]++;
                                        }else if($stack[$spin_type]['board'][$i][$j] == 16){
                                            $moneyCounts[1]++;
                                        }else if($stack[$spin_type]['board'][$i][$j] == 17){
                                            $moneyCounts[2]++;
                                        }
                                    }
                                }
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
                                        // if(isset($stack[$spin_type]['old_bs_v'])){
                                        //     if(is_numeric(str_replace(',', '', $stack[$spin_type]['old_bs_v'][$i][$j]))){
                                        //         $stack[$spin_type]['old_bs_v'][$i][$j] = str_replace(',', '', $stack[$spin_type]['old_bs_v'][$i][$j]) * $betline * $DENOMINATOR;
                                        //     }
                                        // }
                                    }
                                }
                            }
                            if(isset($stack[$spin_type]['bs_v_2'])){
                                for($i = 0; $i < count($stack[$spin_type]['bs_v_2']); $i++){
                                    for($j = 0; $j < 3; $j++){
                                        if(is_numeric(str_replace(',', '', $stack[$spin_type]['bs_v_2'][$i][$j]))){
                                            $stack[$spin_type]['bs_v_2'][$i][$j] = str_replace(',', '', $stack[$spin_type]['bs_v_2'][$i][$j]) * $betline * $DENOMINATOR;
                                        }
                                        // if(isset($stack[$spin_type]['old_bs_v_2'])){
                                        //     if(is_numeric(str_replace(',', '', $stack[$spin_type]['old_bs_v_2'][$i][$j]))){
                                        //         $stack[$spin_type]['old_bs_v_2'][$i][$j] = str_replace(',', '', $stack[$spin_type]['old_bs_v_2'][$i][$j]) * $betline * $DENOMINATOR;
                                        //     }
                                        // }
                                    }
                                }
                            }
                            if(isset($stack[$spin_type]['last_bs_v'])){
                                for($i = 0; $i < count($stack[$spin_type]['last_bs_v']); $i++){
                                    for($j = 0; $j < 3; $j++){
                                        if(is_numeric(str_replace(',', '', $stack[$spin_type]['last_bs_v'][$i][$j]))){
                                            $stack[$spin_type]['last_bs_v'][$i][$j] = str_replace(',', '', $stack[$spin_type]['last_bs_v'][$i][$j]) * $betline * $DENOMINATOR;
                                        }
                                        // if(isset($stack[$spin_type]['old_bs_v_2'])){
                                        //     if(is_numeric(str_replace(',', '', $stack[$spin_type]['old_bs_v_2'][$i][$j]))){
                                        //         $stack[$spin_type]['old_bs_v_2'][$i][$j] = str_replace(',', '', $stack[$spin_type]['old_bs_v_2'][$i][$j]) * $betline * $DENOMINATOR;
                                        //     }
                                        // }
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
                    $subHill0 = [4, 7, 9, 11, 13, 15];
                    $subHill1 = [1, 4, 8, 12, 17, 22];
                    for($k = 0; $k < 3; $k++){
                        if($moneyCounts[$k] > 0){
                            $currentHill['' . ($k + 15)][1] += $moneyCounts[$k];
                            if($currentHill['' . ($k + 15)][1] > 84){
                                $currentHill['' . ($k + 15)][1] = 84;
                                $currentHill['' . ($k + 15)][0] = 24;
                            }else if($currentHill['' . ($k + 15)][1] < $subHill1[5]){
                                for($i = 4; $i >= 0; $i--){
                                    if($currentHill['' . ($k + 15)][1] >= $subHill1[$i]){
                                        $currentHill['' . ($k + 15)][0] = $subHill0[$i];
                                    }
                                }
                            }else{
                                $diff = floor(($currentHill['' . ($k + 15)][1] - $subHill1[5]) / 6);
                                $currentHill['' . ($k + 15)][0] = 15 + $diff;
                                if($currentHill['' . ($k + 15)][0] > 24){
                                    $currentHill['' . ($k + 15)][0] = 24;
                                }
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
                            'actions' => ['spin', 'buy_spin'],
                            'current' => 'spins',
                            'last_args' => [
                                'bet_per_line' => $betline * $DENOMINATOR,
                                'lines' => 25
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
                    if($stack['actions'][0] == 'bonus_init' && $isBuyFreeSpin == false){
                        for($k = 0; $k < 3; $k++){
                            if($moneyCounts[$k] > 0){
                                $currentHill['' . ($k + 15)] = [0, 0];
                            }
                        }
                        $slotSettings->SetGameData($slotSettings->slotId . 'Hill', $currentHill);
                    }
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
                $allBet = $betline * $LINES;
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreespin') == 1){{
                    $allBet = $allBet * 70;
                }}
                $objRes['context']['last_action'] = $action['name'];
                $slotSettings->SaveLogReport(json_encode($objRes), $allBet, $LINES, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent['slotEvent'], $isState);
                if($action['name'] == 'spin' || $action['name'] == 'buy_spin' || $action['name'] == 'freespin' || $action['name'] == 'freespin_stop' || $action['name'] == 'respin' || $action['name'] == 'bonus_spins_stop' || $action['name'] == 'bonus_freespins_stop'){
                    if($action['name'] == 'spin'){
                        $this->SaveBNGLogParse($beforeBalance, $BALANCE, $betline * $LINES, $totalWin, $objRes, $slotSettings);
                    }else if($action['name'] == 'buy_spin'){
                        $this->SaveBNGLogParse($beforeBalance, $BALANCE, $betline * $LINES * 70, $totalWin, $objRes, $slotSettings);
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

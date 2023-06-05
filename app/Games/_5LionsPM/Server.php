<?php 
namespace VanguardLTE\Games\_5LionsPM
{
    use Illuminate\Support\Facades\Http;

    include('CheckReels.php');
    class Server
    {
        public function get($request, $game, $userId) // changed by game developer
        {
            $response = '';
            \DB::beginTransaction();
            // $userId = \Auth::id();// changed by game developer
            if( $userId == null ) 
            {
            	$response = 'unlogged';
                exit( $response );
            }
            $user = \VanguardLTE\User::lockForUpdate()->find($userId);
            $credits = $userId == 1 ? $request->action === 'doInit' ? 5000 : $user->balance : null;

            $slotSettings = new SlotSettings($game, $userId, $credits);
            $this->slotSettings = $slotSettings;
            $paramData = trim(file_get_contents('php://input'));
            $slotEvent = $request->all();
            if( !isset($slotEvent['action']) ) 
            {
                return '';
            }
            
            /* 전역상수 */
            $BALANCE = $slotSettings->GetBalance();
            $LASTSPIN = $slotSettings->GetHistory();
            // $LASTSPIN = null;

            $slotEvent['slotEvent'] = $slotEvent['action']; 

            if($slotEvent['slotEvent'] == 'doSpin' && isset($slotEvent['c'])) 
            { 
               $slotSettings->SetGameData($slotSettings->slotId . 'Bet', $slotEvent['c']);      
               $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 50);
            } 
            $slotSettings->SetBet(); 



            if( $slotEvent['slotEvent'] == 'doInit' ) 
            { 
                $objRes = $this->doInit($slotEvent, $slotSettings);
            }
            else if( $slotEvent['slotEvent'] == 'doSpin' ) 
            {
                $objRes = $this->doSpin($slotEvent, $slotSettings, $LASTSPIN);
            }
            else if ($slotEvent['slotEvent'] == 'doFSOption') {
                $objRes = $this->doFSOption($slotEvent, $slotSettings, $LASTSPIN);
            }
            else if( $slotEvent['slotEvent'] == 'doCollect') 
            {
                $objRes = $this->doCollect($slotEvent);
            }
            else if( $slotEvent['slotEvent'] == 'update' ) 
            {
                /* 리스핀일 경우 스핀 첫밸런스 로드 */
                if ((isset($LASTSPIN->na) && $LASTSPIN->na == 'fso') || isset($LASTSPIN->fsopt_i)) {
                    $BALANCE = $LASTSPIN->balance;
                }

                $objRes = [
                    'balance_bonus' => '0.00',
                    'balance' => $BALANCE,
                    'balance_cash' => $BALANCE,
                    'stime' => floor(microtime(true) * 1000),
                ];
            }
            $response = $this->toResponse($objRes);
            
            //------------ ReplayLog ---------------
            $replayLog = $slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs');
            if (!$replayLog) $replayLog = [];
            $current_replayLog["cr"] = $paramData;
            $current_replayLog["sr"] = $response;
            array_push($replayLog, $current_replayLog); 
            $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', $replayLog);

             if($replayLog && count($replayLog) && $slotEvent['slotEvent'] == 'doCollect'){
                $allBet = ($slotSettings->GetGameData($slotSettings->slotId . 'Bet') ?? 50) * 50;
                $totalWin = $this->slotSettings->GetGameData($this->slotSettings->slotId . 'TotalWin');
                if($totalWin >= ($allBet * 10)){
                    \VanguardLTE\Jobs\UpdateReplay::dispatch([
                        'user_id' => $userId,
                        'game_id' => $this->slotSettings->game->original_id,
                        'bet' => $allBet,
                        'brand_id' => config('app.stylename'),
                        'base_bet' => $allBet,
                        'win' => $totalWin,
                        'rtp' => $totalWin / $allBet,
                        'game_logs' => urlencode(json_encode($replayLog))
                    ]);
                }
                $this->slotSettings->SetGameData($this->slotSettings->slotId . 'ReplayGameLogs', []);
             }
             //------------ *** ---------------

            if($slotEvent['action'] == 'doSpin' || $slotEvent['action'] == 'doCollect' || $slotEvent['action'] == 'doCollectBonus' || $slotEvent['action'] == 'doFSOption'){
                $this->saveGameLog($slotEvent, $response, $slotSettings->GetGameData($slotSettings->slotId . 'RoundID'), $slotSettings);
            }
            $slotSettings->SaveGameData();
            \DB::commit();
            return $response;
        }

        public function doInit($slotEvent, $slotSettings) {
            $BALANCE = $slotSettings->GetBalance();
            $LASTSPIN = $slotSettings->GetHistory();
            if($slotSettings->GetGameData($slotSettings->slotId . 'ReplayGameLogs') == null){
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
            }

            $objRes = [
                'def_s' => '14,14,14,14,14,8,9,5,10,3,9,1,13,5,8,6,12,7,12,5',
                'balance' => $BALANCE,
                'cfgs' => '1',
                'nas' => '14',
                'ver' => '2',
                'index' => $slotEvent['index'],
                'balance_cash' => $BALANCE,
                'reel_set_size' => '2',
                'def_sb' => '6,7,7,9,7',
                'def_sa' => '3,5,4,13,9',
                'balance_bonus' => '0',
                'wrlm_sets' => '2~0~1,2,3,5,8,10,15,30,40~1~2,3,5~2~3,5,8~3~5,8,10~4~8,10,15~5~10,15,30~6~15,30,40',
                'na' => 's',
                'scatters' => '1~0,0,0,0,0~0,0,0,0,0~1,1,1,1,1',
                'gmb' => '0,0,0',
                'rt' => 'd',
                'gameInfo' => '{props:{max_rnd_sim:"1",max_rnd_hr:"47619047",max_rnd_win:"2000"}}',
                'stime' => floor(microtime(true) * 1000),
                'sa' => '3,5,4,13,9',
                'sb' => '6,7,7,9,7',
                'sc' => implode(',', $slotSettings->Bet),
                'defc' => 20.00,
                'sh' => '4',
                'wilds' => '2~0,0,0,0,0~1,1,1,1,1',
                'bonuses' => '0',
                'fsbonus' => '',
                'c' => '50.0',
                'sver' => '5',
                'n_reel_set' => '0',
                'counter' => ((int)$slotEvent['counter'] + 1),
                'paytable' => '0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;1000,100,50,0,0;800,100,35,0,0;800,100,30,0,0;300,50,20,0,0;300,35,15,0,0;200,30,10,0,0;200,20,10,0,0;100,15,10,0,0;100,15,10,0,0;100,15,5,0,0;100,10,5,0,0;0,0,0,0,0',
                'l' => '50',
                'rtp' => '96.5',
                'reel_set0' => '4,8,7,10,13,3,8,1,5,11,13,6,12,9,4,8,7,10,13,3,8,1,5,11,13,6,12,9~5,9,11,2,13,11,3,10,4,8,6,1,7,12,5,9,2,10,13,11,3,10,4,8,6,1,7,12~4,2,3,10,5,13,7,9,1,8,5,12,6,11,4,9,3,10,5,13,7,2,4,8,5,12,6,11~6,11,2,13,11,4,9,5,12,3,10,7,8,1,6,11,5,13,11,4,9,5,12,3,10,7,8,3~9,3,8,5,9,13,6,8,10,4,11,1,7,12,9,3,8,5,9,13,6,8,10,4,11,3,7,12',
                's' => '14,14,14,14,14,8,9,5,10,3,9,1,13,5,8,6,12,7,12,5',
                't' => '243',
                'reel_set1' => '4,8,7,10,13,3,8,1,5,11,13,6,12,9,4,8,7,10,13,3,8,1,5,11,13,6,12,9~5,9,11,2,13,11,3,10,4,8,6,1,7,12,5,9,2,10,13,11,3,10,4,8,6,1,7,12~4,2,3,10,5,13,7,9,1,8,5,12,6,11,4,9,3,10,5,13,7,2,4,8,5,12,6,11~6,11,2,13,11,4,9,5,12,3,10,7,8,1,6,11,5,13,11,4,9,5,12,3,10,7,8,3~9,3,8,5,9,13,6,8,10,4,11,1,7,12,9,3,8,5,9,13,6,8,10,4,11,3,7,12',
            ];

            /* 마지막스핀 결과 로드 */
            if( $LASTSPIN !== NULL ) {
                $objRes['c'] = $LASTSPIN->c ?? null;
                $objRes['tw'] = $LASTSPIN->tw ?? null;
                $objRes['w'] = $LASTSPIN->w ?? null;
                $objRes['sa'] = $LASTSPIN->sa ?? $objRes['sa'];
                $objRes['sb'] = $LASTSPIN->sb ?? $objRes['sb'];
                $objRes['s'] = $LASTSPIN->s ?? $objRes['s'];
                $objRes['na'] = $LASTSPIN->na ?? $objRes['na'];

                $objRes['wrlm_cs'] = $LASTSPIN->wrlm_cs ?? null;
                $objRes['wrlm_res'] = $LASTSPIN->wrlm_res ?? null;

                /* 윈라인 l0, l1, l2 ... */
                $jsonLASTSPIN = json_decode(json_encode($LASTSPIN), true);
                $winLines = array_filter($jsonLASTSPIN, function ($value, $key) {
                    return strlen($key) > 1 && str_starts_with($key, "l");
                }, ARRAY_FILTER_USE_BOTH);

                $objRes = array_merge($objRes, $winLines);

                /* 프리스핀 */
                $objRes['fsmul'] = $LASTSPIN->fsmul ?? null;
                $objRes['fsmax'] = $LASTSPIN->fsmax ?? null;
                $objRes['fswin'] = $LASTSPIN->fswin ?? null;
                $objRes['fs'] = $LASTSPIN->fs ?? null;
                $objRes['fsres'] = $LASTSPIN->fsres ?? null;
                $objRes['fsopt_i'] = $LASTSPIN->fsopt_i ?? null;            
                $objRes['psym'] = $LASTSPIN->psym ?? null;
                $objRes['fs_total'] = $LASTSPIN->fs_total ?? null;
                $objRes['fsc_total'] = $LASTSPIN->fsc_total ?? null;
                $objRes['fswin_total'] = $LASTSPIN->fswin_total ?? null;
                $objRes['fsc_win_total'] = $LASTSPIN->fsc_win_total ?? null;
                $objRes['fsmul_total'] = $LASTSPIN->fsmul_total ?? null;
                $objRes['fsc_mul_total'] = $LASTSPIN->fsc_mul_total ?? null;
                $objRes['fsres_total'] = $LASTSPIN->fsres_total ?? null;
                $objRes['fsc_res_total'] = $LASTSPIN->fsc_res_total ?? null;

                if ($LASTSPIN->action == 'doFSOption') {
                    $objRes['c'] = $LASTSPIN->start_with->c;
                    $objRes['tw'] = $LASTSPIN->start_with->tw;
                    $objRes['w'] = $LASTSPIN->start_with->w;
                    $objRes['sa'] = $LASTSPIN->start_with->sa;
                    $objRes['sb'] = $LASTSPIN->start_with->sb;
                    $objRes['s'] = $LASTSPIN->start_with->s;
                    $objRes['psym'] = $LASTSPIN->start_with->psym;
                }
                else {
                    $objRes['fs_opt_mask'] = $LASTSPIN->fs_opt_mask ?? null;
                    $objRes['fs_opt'] = $LASTSPIN->fs_opt ?? null;
                }

                $fsmax = $slotSettings->GetGameData($slotSettings->slotId . 'FSMax');
                if ($LASTSPIN->na == 'fso' || $fsmax > 0) {
                    $objRes['balance'] = $LASTSPIN->balance ?? $BALANCE;
                    $objRes['balance_cash'] = $LASTSPIN->balance_cash ?? $BALANCE;
                }
            }

            return $objRes;
        }

        public function doSpin($slotEvent, $slotSettings, $LASTSPIN) {
            $S_WILD = 2;
            $REELCOUNT = 5;
            $SYMBOLCOUNT = 4;

            $lines = 50/* $slotEvent['l'] */;       // 라인
            $bet = $slotEvent['c'];         // 베팅액

            /* 남은 프리스핀이 있을 경우 */
            $fsmax = $slotSettings->GetGameData($slotSettings->slotId . 'FSMax') ?? 0;
            $fs = $slotSettings->GetGameData($slotSettings->slotId . 'FSNext') ?? 0;
            $fsopt = $slotSettings->GetGameData($slotSettings->slotId . 'FSOpt') ?? 0;
            $fswildcount = $slotSettings->GetGameData($slotSettings->slotId . 'FSWildCount') ?? 0;
            
            /* 프리스핀 타입검사 */
            if ($LASTSPIN !== NULL && $fsmax > 0) {
                if (isset($LASTSPIN->fsopt_i)) {
                    $slotEvent['slotEvent'] = 'freespin';
                }
                else {
                    /* 세션과 게임로그사이 동기가 맞지않을경우 프리스핀 리셋 */
                    $slotSettings->SetGameData($slotSettings->slotId . 'FSMax', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FSNext', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FSOpt', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FSWildCount', 0);
                }
            }

            /* 스핀결과 결정 */
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $bet, $lines);

            $winType = $_spinSettings[0];                   // 보상방식
            $_winAvaliableMoney = $_spinSettings[1];        // 당첨금 한도

            /* Balance 업데이트 */
            $allBet = 0;
            if ($slotEvent['slotEvent'] === 'freespin') {
                /* 프리스핀시 보너스당첨은 없다 */
                if ($winType == 'bonus') {
                    $winType = 'none';
                }
            }
            else {
                $allBet = $bet * $lines;
                $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                
                $bankMoney = $allBet / 100 * $slotSettings->GetPercent();
                $slotSettings->SetBank(($slotEvent['slotEvent'] ?? ''), $bankMoney);
                $slotSettings->SetGameData($slotSettings->slotId . 'ReplayGameLogs', []); //ReplayLog
                $roundstr = sprintf('%.4f', microtime(TRUE));
                $roundstr = str_replace('.', '', $roundstr);
                $roundstr = '446' . substr($roundstr, 4, 10);
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
            }

            $BALANCE = $slotSettings->GetBalance();
            
            /* 스캐터 생성갯수  */
            $defaultScatterCount = $slotSettings->GenerateScatterCount($winType);  // 생성되어야할 Scatter갯수 결정

            /* WILD 생성갯수 */
            $defaultWildCount = $slotSettings->GenerateWildCount($winType, $slotEvent['slotEvent']);
            if ($slotEvent['slotEvent'] === 'freespin' && $fsmax <= $fs && $fswildcount == 0) {
                /* 프리스핀중 WILD심볼이 한번도 없었다면 */
                $defaultWildCount = 1;
            }

            /* 릴배치표 생성, 2천번 시행 */
            /*************************************************** */
            $overtry = false;           // 1500번이상 시행했을때 true

            for ($try=0; $try < 2000; $try++) { 
                $winMoney = 0;

                /* 릴배치표 생성 */
                if ($overtry) {
                    /* 더이상 자동릴생성은 하지 않고 최소당첨릴을 수동생성 */
                    $reels = $slotSettings->GetReelStrips($winType, $slotEvent['slotEvent'], 0, 0, null);
                }
                else {
                    $availableMultipliers = ($slotEvent['slotEvent'] === 'freespin' ? $fsopt['multipliers'] : null);
                    $reels = $slotSettings->GetReelStrips($winType, $slotEvent['slotEvent'], $defaultScatterCount, $defaultWildCount, $availableMultipliers);
                }

                /* 릴셋 유효성검사 */
                if (!$slotSettings->isValidReels($reels)) {
                    continue;
                }
                
                /* 윈라인 체크 */
                $winLines = $this->checkWinLines($reels, $slotSettings);
                
                /* 생성된 릴배치표 검사 */
                if ($overtry) {
                    break;
                }
                else if( $try > 1500 ) 
                {
                    $overtry = true;
                    continue;
                }
                else if ( $try >= 1000 ) 
                {
                    $winType = 'none';
                }

                if ($winType == 'none') {
                    if (count($winLines) == 0) {
                        break;
                    }
                }
                else if ($winType == 'win' && count($winLines) > 0) {
                    /* 스핀 당첨금 */
                    $winMoney = array_reduce($winLines, function($carry, $winLine) {
                        $carry += $winLine['Money']; 
                        return $carry;
                    }, 0) * $bet;

                    if ($winMoney >= $_winAvaliableMoney) {
                        continue;
                    }

                    break;
                }
                else if ($winType == 'bonus' && count($reels['scatterSymbols']) >= 3) {
                    if (count($winLines) == 0) {
                        break;
                    }
                }
            }

            $objRes = [
                'action' => 'doSpin',

                'tw' => $winMoney,
                'balance' => $BALANCE,
                'index' => $slotEvent['index'],
                'balance_cash' => $BALANCE,
                'balance_bonus' => '0',
                'na' => 's',
                'wrlm_cs' => '2~0',
                'stime' => floor(microtime(true) * 1000),
                'sa' => implode(",", $reels['symbolsAfter']),
                'sb' => implode(",", $reels['symbolsBefore']),
                'sh' => '4',
                'wrlm_res' => '2~1',
                'c' => $bet,
                'sver' => 5,
                'n_reel_set' => '0',
                'counter' => ((int)$slotEvent['counter'] + 1),
                's' => implode(",", $reels['flatSymbols']),
                'w' => $winMoney,
            ];
            $isState = true;
            if ($winMoney > 0) {
                $objRes['na'] = 'c';
                
                /* 윈라인 구성 */
                foreach ($winLines as $idx => $winLine) {
                    $winLineMoney = $winLine['Money'] * $bet;
                    
                    /* 윈라인 추가 */
                    $payLineId = 0;
                    $strLineSymbolPositions = implode("~", $winLine['Positions']);
                    $objRes["l${idx}"] = "${payLineId}~${winLineMoney}~${strLineSymbolPositions}";
                }

                /* WILD 심볼 */
                $strWilds = $this->stringifyWildSymbols($reels, $winLines);
                $objRes['wrlm_res'] = $strWilds;
            }

            if ($winType == 'bonus') {
                [$strScatters, $winMoney] = $this->stringifyScatterSymbols($reels, $bet, $lines);

                $objRes['tw'] = $winMoney;
                $objRes['w'] = $winMoney;
                $objRes['psym'] = $strScatters;

                $objRes['na'] = 'fso';
                $objRes['fs_opt_mask'] = 'fs,m,ts,rm';

                $fs_opt = $this->stringifyFSOptions($this->slotSettings->fsOpts);
                $objRes['fs_opt'] = $fs_opt;
                $isState = false;
            }
            else if ($slotEvent['slotEvent'] == 'freespin') {
                /* 프리스핀 시작밸런스 로드 */
                $BALANCE = $slotSettings->GetGameData($slotSettings->slotId . 'FSStartBalance');
                
                /* 프리스핀 당첨금 유지 */
                $objRes['tw'] = ($LASTSPIN->tw ?? $LASTSPIN->start_with->tw) + $winMoney;
                $objRes['balance'] = $BALANCE;
                $objRes['balance_cash'] = $BALANCE;
                $objRes['fsopt_i'] = $LASTSPIN->fsopt_i;

                if ($LASTSPIN->fsopt_i == 6) {
                    /* 미스터리 옵션인 경우 선택된 배당이 어느 옵션의것인가 결정 */
                    $multipliers = $fsopt['multipliers'];
                    $chosenOpts = array_filter($slotSettings->fsOpts, function($opt) use ($multipliers) {
                        $res = array_diff($opt['multipliers'], $multipliers);
                        return count($res) == 0;
                    });
                    $objRes['wrlm_cs'] = array_keys($chosenOpts)[0] + 1;
                }
                else {
                    /* 일반 옵션 */
                    $objRes['wrlm_cs'] = $LASTSPIN->fsopt_i + 1;
                }
                
                if ($fsmax > $fs) {
                    /* 프리스핀중에는 스핀타입이 항상 s */
                    $objRes['na'] = 's';
                    $objRes['fsmul'] = 1;
                    $objRes['fsmax'] = $fsmax;
                    $objRes['fs'] = $fs + 1;

                    /* 프리스핀 당첨금 */
                    $objRes['fswin'] = $objRes['tw'];
                    $objRes['fsres'] = $objRes['tw'];

                    $slotSettings->SetGameData($slotSettings->slotId . 'FSNext', $fs + 1);
                    
                    /* 프리스핀중 와일드 출현횟수 */
                    $fswildcount += $defaultWildCount;
                    $slotSettings->SetGameData($slotSettings->slotId . 'FSWildCount', $fswildcount);
                    $isState = false;
                }
                else if ($fsmax <= $fs) {
                    /* 프리스핀 완료 */
                    $objRes['na'] = $objRes['tw'] > 0 ? 'c' : 's';

                    $objRes['fs_total'] = $fsmax;
                    $objRes['fsc_total'] = $fsmax;
                    $objRes['fswin_total'] = $objRes['tw'];
                    $objRes['fsc_win_total'] = $objRes['tw'];
                    $objRes['fsmul_total'] = 1;
                    $objRes['fsc_mul_total'] = 1;
                    $objRes['fsres_total'] = $objRes['tw'];
                    $objRes['fsc_res_total'] = $objRes['tw'];

                    $slotSettings->SetGameData($slotSettings->slotId . 'FSMax', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FSNext', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FSOpt', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FSWildCount', 0);
                }
            }

            if($winMoney > 0) 
            {
                $slotSettings->SetBalance($winMoney);
                $slotSettings->SetBank($slotEvent['slotEvent'] ?? '', -1 * $winMoney);
            }

            $_GameLog = json_encode($objRes);
            if ($slotEvent['slotEvent'] == 'freespin' && $isState == true) {
                $allBet = $bet * $lines;
            }
            $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $objRes['tw']);
            
            $slotSettings->SaveLogReport($_GameLog, $allBet, $slotEvent['l'], $objRes['tw'], $slotEvent['slotEvent'], $isState);
            
            return $objRes;
        }

        public function doCollect($slotEvent) {
            $BALANCE = $this->slotSettings->GetBalance();

            $objRes = [
                'action' => 'doCollect',

                'balance' => $BALANCE,
                'index' => $slotEvent['index'],
                'balance_cash' => $BALANCE,
                'balance_bonus' => '0.00',
                'na' => 's',
                'stime' => floor(microtime(true) * 1000),
                'sver' => '5',
                'counter' => ((int)$slotEvent['counter'] + 1),
            ];
            return $objRes;
        }

        public function doFSOption($slotEvent, $slotSettings, $LASTSPIN) {
            $BALANCE = $LASTSPIN->balance;

            /* 프리스핀 옵션*/
            $fsopt_i = $slotEvent['ind'];

            $fs_opts = $this->slotSettings->fsOpts;
            if ($fsopt_i == 6) {
                /* 미스터리 옵션 랜덤 결정, 마지막 옵션 제외 */
                $rand_spin_opt = random_int(0, count($fs_opts) - 2);
                $rand_multipliers_opt = random_int(0, count($fs_opts) - 2);

                $fs_opts[6]['spin_count'] = $fs_opts[$rand_spin_opt]['spin_count'];
                $fs_opts[6]['multipliers'] = $fs_opts[$rand_multipliers_opt]['multipliers'];
            }
            else {
                /* 일반 옵션 */
                $fs_opts[6] = $fs_opts[$fsopt_i];
            }

            $str_fs_opt = $this->stringifyFSOptions($fs_opts);

            $fsmax = $fs_opts[$fsopt_i]['spin_count'];
            $fs = 1;

            $objRes = [
                'action' => 'doFSOption',

                'fsmul' => '1',
                'fs_opt_mask' => 'fs,m,ts,rm',
                'balance' => $BALANCE,
                'fsmax' => $fsmax,
                'index' => $slotEvent['index'],
                'balance_cash' => $BALANCE,
                'balance_bonus' => '0',
                'na' => 's',
                'fswin' => '0',
                'wrlm_cs' => '2~0',
                'stime' => floor(microtime(true) * 1000),
                'fs' => $fs,
                'fs_opt' => $str_fs_opt,
                'wrlm_res' => '2~1',
                'fsres' => '0',
                'sver' => '5',
                'n_reel_set' => '1',
                'counter' => ((int)$slotEvent['counter'] + 1),
                'fsopt_i' => $fsopt_i,
            ];

            /* 프리스핀 셋팅저장 */
            $slotSettings->SetGameData($slotSettings->slotId . 'FSMax', $fsmax);
            $slotSettings->SetGameData($slotSettings->slotId . 'FSNext', $fs);
            $slotSettings->SetGameData($slotSettings->slotId . 'FSOpt', $fs_opts[$fsopt_i]);
            $slotSettings->SetGameData($slotSettings->slotId . 'FSStartBalance', $BALANCE);

            $_GameLog = json_encode(array_merge($objRes, ['start_with' => $LASTSPIN]));
            $slotSettings->SaveLogReport($_GameLog, 0, 0, 0, 'freespin', false);

            return $objRes;
        }

        public function checkWinLines($reels) {
            $REELCOUNT = 5;
            $SYMBOLCOUNT = 4;
            $S_WILD = 2;
            $S_BLANK = 14;
            $S_SCATTER = 1;

            $winLines = [];

            for ($symbolId=0; $symbolId < $SYMBOLCOUNT; $symbolId++) { 
                $firstSymbol = $reels['symbols'][0][$symbolId];
                
                /* SCATTER, 빈 심볼이면 스킵 */
                if ($firstSymbol == $S_BLANK || $firstSymbol == $S_SCATTER) {
                    continue;
                }

                $winLines = $this->findZokbos($reels, $firstSymbol, 1, [$symbolId * $REELCOUNT], $winLines);
            }

            return $winLines;
        }
        
        public function findZokbos($reels, $firstSymbol, $repeatCount, $positions, $winLines){
            $S_WILD = 2;
            $REELCOUNT = 5;
            $SYMBOLCOUNT = 4;
            $bPathEnded = true;

            if($repeatCount < 5){
                for($r = 0; $r < $SYMBOLCOUNT; $r++){
                    if($firstSymbol == $reels['symbols'][$repeatCount][$r] || $reels['symbols'][$repeatCount][$r] == $S_WILD){
                        $winLines = $this->findZokbos($reels, $firstSymbol, $repeatCount + 1, array_merge($positions, [$repeatCount + $r * $REELCOUNT]), $winLines);
                        $bPathEnded = false;
                    }
                }
            }

            if($bPathEnded == true){
                if($repeatCount >= 3){
                    $winLine = [];
                    $winLine['FirstSymbol'] = $firstSymbol;
                    $winLine['RepeatCount'] = $repeatCount;
                    $winLine['Positions'] = $positions;

                    /* 와일드 배당검사 */
                    $wildSymbols = [];
                    foreach ($positions as $pos) {
                        if ($reels['flatSymbols'][$pos] == $S_WILD) {
                            array_push($wildSymbols, $pos);
                        }
                    }

                    $winLine['WildSymbols'] = $wildSymbols;
                    
                    /* 페이테이블 검사 */
                    $winLine['Money'] = $this->slotSettings->PayTable[$firstSymbol][$REELCOUNT - $repeatCount];

                    /* 윈라인에 와일드심볼이 있다면 배당계산 */
                    if (count($wildSymbols) > 0) {
                        $winLine['Money'] = $winLine['Money'] * $reels['wildMultiplier'];
                    }

                    array_push($winLines, $winLine);
                }
            }

            return $winLines;
        }

        public function stringifyScatterSymbols($reels, $bet, $lines) {
            $SYMBOLCOUNT = 5;
            $S_SCATTER = 1;

            $symbols = array_keys($reels['flatSymbols'], $S_SCATTER);
            $repeatCount = count($symbols);
            $winMoney = $this->slotSettings->PayTable[$S_SCATTER][$SYMBOLCOUNT - $repeatCount] * $bet * $lines;

            // '1~300.00~3,7,20,22'
            return ["${S_SCATTER}~${winMoney}~" . implode(",", $symbols), $winMoney];
        }

        public function stringifyWildSymbols($reels, $winLines) {
            $S_WILD = 2;
            $multiplier = $reels['wildMultiplier'];

            /* 윈라인에 속한 WILD 심볼배열 */
            $wildSymbols = [];
            foreach ($winLines as $winLine) {
                if (count($winLine['WildSymbols']) > 0) {
                    $wildSymbols = array_merge($wildSymbols, $winLine['WildSymbols']);
                }
            }

            if (count($wildSymbols) > 0) {
                $wildSymbols = array_unique($wildSymbols);
                ksort($wildSymbols);
                $strWildSymbols = implode(",", $wildSymbols);

                return "${S_WILD}~${multiplier}~${strWildSymbols}";
            }
            else {
                return "${S_WILD}~${multiplier}";
            }
        }

        public function stringifyFSOptions($fs_opts) {
            $fs_opts = array_map(function($opt) {
                $multipliers = implode(";", $opt['multipliers']);
                return "${opt['spin_count']},${opt['val1']},${opt['val2']},${multipliers}";
            }, $fs_opts);

            $res = implode("~", $fs_opts);
            return $res;
        }

        public function toResponse($obj) {
            $response = '';
            foreach ($obj as $key => $value) {
                if ($value !== null) {
                    $response = "{$response}&{$key}={$value}";
                }
            }

            /* remove double quotes around key for javascript */
            $response = preg_replace('/"(\w+)":/i', '\1:', $response);
            return trim($response, "&");
        }
        public function saveGameLog($slotEvent, $response_log, $roundId, $slotSettings){
            $game_log = [];
            $game_log['roundId'] = $roundId;
            $response_loges = explode('&', $response_log);
            $response = [];
            foreach( $response_loges as $param ) 
            {
                $_obf_arr = explode('=', $param);
                $response[$_obf_arr[0]] = $_obf_arr[1];
            }

            $request = [];
            foreach( $slotEvent as $index => $value ) 
            {
                if($index != 'slotEvent'){
                    $request[$index] = $value;
                }
            }
            $game_log['request'] = $request;
            $game_log['response'] = $response;
            $game_log['currency'] = 'KRW';
            $game_log['currencySymbol'] = '₩';
            $game_log['configHash'] = '02344a56ed9f75a6ddaab07eb01abc54';

            $str_gamelog = json_encode($game_log);
            $slotSettings->saveGameLog($str_gamelog, $roundId);
        }
    }
}

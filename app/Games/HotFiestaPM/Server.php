<?php 
namespace VanguardLTE\Games\HotFiestaPM
{
    use Illuminate\Support\Facades\Http;

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
            
            $slotEvent = $request->all();
            if( !isset($slotEvent['action']) ) 
            {
                return '';
            }
            
            /* 전역상수 */
            $BALANCE = $slotSettings->GetBalance();
            $LASTSPIN = $slotSettings->GetHistory();

            $slotEvent['slotEvent'] = $slotEvent['action']; 

            if($slotEvent['slotEvent'] == 'doSpin' && isset($slotEvent['c'])) 
            { 
               $slotSettings->SetGameData($slotSettings->slotId . 'Bet', $slotEvent['c']);     
               $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $slotEvent['l']);
            } 
            $slotSettings->SetBet(); 



            if( $slotEvent['slotEvent'] == 'doInit' ) 
            { 
                $objRes = $this->doInit($slotEvent, $slotSettings);
            }
            else if( $slotEvent['slotEvent'] == 'doSpin' ) 
            {
                $objRes = $this->doSpin($slotEvent, $slotSettings);
            }
            else if( $slotEvent['slotEvent'] == 'doCollect') 
            {
                $objRes = $this->doCollect($slotEvent);

                /* 라운드 등록 */
                $this->submitRound($slotSettings, $LASTSPIN, $objRes);
            }
            else if( $slotEvent['slotEvent'] == 'update' ) 
            {
                $objRes = [
                    'balance_bonus' => '0.00',
                    'balance' => $BALANCE,
                    'balance_cash' => $BALANCE,
                    'stime' => floor(microtime(true) * 1000),
                ];
            }

            $response = $this->toResponse($objRes);
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
            // $LASTSPIN = null;

            $objRes = [
                'def_s' => '7,9,13,6,6,9,10,6,12,12,11,5,4,9,10',
                'balance' => $BALANCE,
                'cfgs' => '1',
                'ver' => '2',
                'index' => $slotEvent['index'],
                'balance_cash' => $BALANCE,
                'def_sb' => '5,7,10,3,3',
                'reel_set_size' => '4',
                'def_sa' => '8,8,9,10,11',
                'reel_set' => '0',
                'balance_bonus' => '0.00',
                'na' => 's',
                'scatters' => '1~0,0,3,0,0~0,0,0,0,0~1,1,1,1,1',
                'gmb' => '0,0,0',
                'rt' => 'd',
                'gameInfo' => '{rtps:{ante:"96.53",purchase:"96.49",regular:"96.56"},props:{max_rnd_sim:"1",max_rnd_hr:"1000000",max_rnd_win:"5000",max_rnd_win_a:"5000"}}',
                'wl_i' => 'tbm~5000',
                'bl' => '0',
                'stime' => floor(microtime(true) * 1000),
                'sa' => '8,8,9,10,11',
                'sb' => '5,7,10,3,3',
                'sc' => implode(',', $slotSettings->Bet), // '1.00,2.00,3.00,4.00,5.00,8.00,10.00,20.00,30.00,40.00,50.00,75.00,100.00,200.00,300.00,400.00,500.00',
                'defc' => '80.00',
                'sh' => '3',
                'wilds' => '2~1000,250,50,0,0~1,1,1,1,1,1;14~1000,250,50,0,0~1,1,1,1,1,1;15~1000,250,50,0,0~1,1,1,1,1,1;16~1000,250,50,0,0~1,1,1,1,1,1',
                'bonuses' => '0',
                'fsbonus' => 'fsbonus=',
                'c' => '80.00',
                'sver' => '5',
                'bls' => '25,35',
                'counter' => '2',
                'paytable' => '0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;750,150,50,0,0;500,100,35,0,0;300,60,25,0,0;200,40,20,0,0;150,25,12,0,0;100,20,8,0,0;50,10,5,0,0;50,10,5,0,0;25,5,2,0,0;25,5,2,0,0;25,5,2,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0',
                'l' => '25',
                'rtp' => '96.50',
                'total_bet_max' => $slotSettings->game->rezerv,
                'reel_set0' => '8,7,12,9,3,5,6,10,13,11,4,2,1,6,11,9,1,9,3,9,7,9,4,5,6,1,9,4,13,6,12,7,13,4,11,7,5,9,7,5,13,4,9,13,4,9,12,4,12,11,6,7,1,7,6,12,11,9,12,3,11,4,7,9~3,13,8,7,6,2,10,9,11,5,4,12,9,6,9~1,3,13,12,2,11,7,10,9,5,4,6,8,13,8,6,5,8,5,6,8,13,10,5,10,13,6,10,7,10,8,5,13,2,5,10,3,10,8,7,13,2,7,8,11,10,5,10,12,5~6,3,12,7,13,9,11,5,8,2,4,10,12,8,12,8,13,4,8,11,4,9,12,4,12~10,5,12,9,6,7,1,2,8,11,13,3,4,11,1,11,7,1,11,5,1,7,4,1,7,11,8,11,6,7,1,11,1,6,7,9,6,13,6,7,1,5,11,7,11,1,9,12,11,8,9,13,3,13,11,7,5,7,11,1,9,8',
                's' => '7,9,13,6,6,9,10,6,12,12,11,5,4,9,10',
                'reel_set2' => '6,8,13,9,11,5,3,5,9,3,11,9,5,8,11,8,11,9,11,5,9,13,11,5,11,8,11,5,1~12,7,10,4,10~3,13,9,5,6,8,11,6,8,13,1~4,10,12,7,10~8,3,6,13,9,5,11,5,1',
                'reel_set1' => '3,3,3,8,3,13,6,12,11,5,7,9,4,10,13,10,5,8,12,7,12,9,4,13,11,9,11,13,9,11,4,12,7,13,5,9,11,7,13,8,12,4,11~11,13,7,8,3,12,9,5,6,4,10,5,10,7,10,4,3,10,8,10,4,12,10,12,9,10,12,5,10,4,10,4,3,13,10,7,10~5,8,9,3,12,6,13,4,10,7,11,8,12,6,7,6,11,12~8,9,12,11,3,13,10,6,7,5,4,3,7,5,3,9,3,4,5,3,13,6,9,3,4,5,6,9,5,13,9,7~12,6,13,8,5,3,7,10,3,3,3,9,11,4,8,4,3,8,13,3,4,11,13,3,13,11,5,13,5,11,10,3,10,7,8,6,5,11,3,5,11,5,3,5,8,5,3,13,7,5,6,11,10,5,3,4,13,11,4,8,7,10',
                'purInit' => '[{type:"fsbl",bet:2500,bet_level:0}]',
                'reel_set3' => '6,13,9,7,11,5,3,4,1,12,2,10,8,10,2,7,4,10,4,7,5,10,4,2,4,8,2,11,10,4,3,4,8,1,8,12,10,11,10,7,2,4,7,9,8,7,10,1,4,1,3,8,4,1,8,2,7,10,8,2,4,10,2,4,2,4~9,8,11,3,2,12,6,7,13,10,4,5,11,5,10,11,7,8,6,2,7,5,4,7,11,4,10,4,6~5,6,11,2,8,7,13,9,10,1,4,3,12,11,6,10,8,7,3,4,8,11,6,7,10,13,3,9,1,8,6,7,6,2,13,9,11,6,7,13,6,13,6,7,6,1,10,6,7,10,13,6,13,6,2,7,3,7,3,6,13,7,1,6,2,8,7,10,8,13,10,13,9~3,2,6,11,7,12,10,8,9,13,4,5,12,9,6,10,9,11,4,7,11,9,13,4,7,11,12,13,10,11,13,12,11,7,13,6,12,10,9,12,9,12,13,4,11,12,11,7,4,6,9,12,7,12,10,12,8,10,12,9,12,13,4,7,12,11,12,2,4,12~11,13,10,7,9,3,12,1,2,6,5,8,4,6',
                'total_bet_min' => '10.00',
            ];

            /* 마지막스핀 결과 로드 */
            if( $LASTSPIN !== NULL ) {
                $objRes['c'] = $LASTSPIN->c ?? null;
                $objRes['tw'] = $LASTSPIN->tw ?? null;
                $objRes['w'] = $LASTSPIN->w ?? null;
                $objRes['sa'] = $LASTSPIN->sa ?? null;
                $objRes['sb'] = $LASTSPIN->sb ?? null;
                $objRes['s'] = $LASTSPIN->s ?? $objRes['s'];
                $objRes['na'] = $LASTSPIN->na ?? $objRes['na'];
                $objRes['bl'] = $LASTSPIN->bl ?? $objRes['bl'];
                $objRes['reel_set'] = $LASTSPIN->reel_set ?? $objRes['reel_set'];
                $objRes['slm_mp'] = $LASTSPIN->slm_mp ?? null;
                $objRes['slm_mv'] = $LASTSPIN->slm_mv ?? null;
                $objRes['slm_lmi'] = $LASTSPIN->slm_lmi ?? null;
                $objRes['slm_lmv'] = $LASTSPIN->slm_lmv ?? null;
                
                /* 윈라인 l0, l1, l2 ... */
                $jsonLASTSPIN = json_decode(json_encode($LASTSPIN), true);
                $winLines = array_filter($jsonLASTSPIN, function ($value, $key) {
                    return strlen($key) > 1 && str_starts_with($key, "l");
                }, ARRAY_FILTER_USE_BOTH);

                $objRes = array_merge($objRes, $winLines);

                /* 프리스핀 구매 */
                $objRes['purtr'] = $LASTSPIN->purtr ?? null;
                $objRes['puri'] = $LASTSPIN->puri ?? null;
                
                /* 프리스핀 */
                $objRes['trail'] = $LASTSPIN->trail ?? null;
                $objRes['sty'] = $LASTSPIN->sty ?? null;

                $objRes['fsmul'] = $LASTSPIN->fsmul ?? null;
                $objRes['fsmax'] = $LASTSPIN->fsmax ?? null;
                $objRes['fs'] = $LASTSPIN->fs ?? null;
                $objRes['fswin'] = $LASTSPIN->fswin ?? null;
                $objRes['fsres'] = $LASTSPIN->fsres ?? null;
                
                $objRes['fs_total'] = $LASTSPIN->fs_total ?? null;
                $objRes['fsend_total'] = $LASTSPIN->fsend_total ?? null;
                $objRes['fsmul_total'] = $LASTSPIN->fsmul_total ?? null;
                $objRes['fsres_total'] = $LASTSPIN->fsres_total ?? null;
                $objRes['fswin_total'] = $LASTSPIN->fswin_total ?? null;

                /* 프리스핀 당첨금 */
                $fsmax = $slotSettings->GetGameData($slotSettings->slotId . 'FSMax');
                if ($fsmax > 0) {
                    $objRes['balance'] = $LASTSPIN->balance ?? $BALANCE;
                    $objRes['balance_cash'] = $LASTSPIN->balance_cash ?? $BALANCE;
                }
            }

            return $objRes;
        }

        public function doSpin($slotEvent, $slotSettings) {
            $LASTSPIN = $slotSettings->GetHistory();
            // $LASTSPIN = null;

            $S_SCATTER = 1;
            $S_WILD = 2;

            $lines = $slotEvent['l'];       // 라인
            $bet = $slotEvent['c'];         // 베팅액

            /* 더블벳 판정깃발 */
            $isDoubleBet = false;

            /* 프리스핀 구매 */
            if (isset($slotEvent['pur'])) {
                $slotEvent['slotEvent'] = 'buy_freespin';

                $winType = 'bonus';                   // 보상방식
                $_winAvaliableMoney = 0;        // 당첨금 한도
            }
            else {
                /* 더블벳 */
                if ($slotEvent['bl'] == 1) {
                    $isDoubleBet = true;

                    /* 더블벳인 경우 라인수 35개로 */
                    $lines = 35;
                }

                /* 남은 프리스핀이 있을 경우 */
                $fsmax = $slotSettings->GetGameData($slotSettings->slotId . 'FSMax') ?? 0;
                $fs = $slotSettings->GetGameData($slotSettings->slotId . 'FSNext') ?? 0;
                $fsStickyGen = $slotSettings->GetGameData($slotSettings->slotId . 'FSStickyGen') ?? [];       // WILD 생성셋, 스핀 인덱스: 멀티플라이어
                $fsStickySet = $slotSettings->GetGameData($slotSettings->slotId . 'FSNextStickySet') ?? [];     // 다음스핀 WILD 위치 맵
                $fsStickyMultiplierMap = $slotSettings->GetGameData($slotSettings->slotId . 'FSStickyMultiplierMap') ?? [];     // WILD 멀트플라이어 맵

                /* 이전 프리스핀검사, 새 스핀결과 결정 */
                if ($LASTSPIN !== NULL && $fsmax > 0) {
                    $slotEvent['slotEvent'] = 'freespin';
                }
                
                /* 스핀결과 결정 */
                $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $bet * $lines, $lines, $isDoubleBet);

                $winType = $_spinSettings[0];                   // 보상방식
                $_winAvaliableMoney = $_spinSettings[1];        // 당첨금 한도
            }

             /* Balance 업데이트 */
             if ($slotEvent['slotEvent'] === 'buy_freespin') {
                $allBet = $bet * $lines * 100;
                $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);

                /* 프리스핀 구매금액은 bonus에 충전 */
                $bankMoney = $allBet / 100 * $slotSettings->GetPercent();
                $slotSettings->SetBank(($slotEvent['slotEvent'] ?? ''), $bankMoney, 0);
                
                $roundstr = sprintf('%.4f', microtime(TRUE));
                $roundstr = str_replace('.', '', $roundstr);
                $roundstr = '275' . substr($roundstr, 4, 7);
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
            }
            else if ($slotEvent['slotEvent'] === 'freespin') {
                /* 프리스핀, 텀블스핀일때 베팅금 없음 */
                $allBet = 0;
            }
            else {
                $allBet = $bet * $lines;
                $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                
                $bankMoney = $allBet / 100 * $slotSettings->GetPercent();
                $slotSettings->SetBank(($slotEvent['slotEvent'] ?? ''), $bankMoney);
                
                $roundstr = sprintf('%.4f', microtime(TRUE));
                $roundstr = str_replace('.', '', $roundstr);
                $roundstr = '275' . substr($roundstr, 4, 7);
                $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
            }

            /* 벨런스 업데이트 */
            $BALANCE = $slotSettings->GetBalance();

            /* SCATTER 생성갯수  */
            $defaultScatterCount = $slotSettings->GenerateScatterCount($winType, $slotEvent['slotEvent']);  // 생성되어야할 Scatter갯수 결정;
            
            /* WILD 생성갯수 */
            if ($slotEvent['slotEvent'] == 'buy_freespin') {
                $defaultWildCount = 0;
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', true);
            }
            else if ($slotEvent['slotEvent'] == 'freespin') {
                if (isset($fsStickyGen[$fs - 1]) && $fsStickyGen[$fs - 1] > 0) {
                    $defaultWildCount = 1;
                }
                else {
                    $defaultWildCount = 0;
                }
            }
            else {
                $defaultWildCount = $slotSettings->GenerateWildCount($winType);                
                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', false);
            }

            /* 릴배치표 생성, 2천번 시행 */
            /*************************************************** */
            $overtry = false;           // 1500번이상 시행했을때 true

            for ($try=0; $try < 2000; $try++) { 
                $winMoney = 0;

                /* 릴배치표 생성 */
                $lastWildCollection = [];
                $curStickySet = [];
                $lastStickyTrans = [];
                if ($slotEvent['slotEvent'] == 'freespin' && isset($LASTSPIN->sty)) {
                    $lastWildCollection = $fsStickyMultiplierMap;
                    $lastStickyTrans = explode("~", $LASTSPIN->sty);
                    $curStickySet = $fsStickySet;
                }

                $reels = $slotSettings->GetReelStrips($winType, $slotEvent['slotEvent'], $defaultScatterCount, $defaultWildCount, $lastWildCollection, $lastStickyTrans, $curStickySet);
                
                /* 릴셋 유효성검사 */
                if (!$slotSettings->isValidReels($reels)) {
                    continue;
                }
                
                /* 윈라인 체크 */
                $winLines = $this->checkWinLines($reels, $slotSettings);

                /* 스캐터심볼 체크 */
                $scatterCount = $this->getScatterCount($reels);

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
                    $defaultScatterCount = 0;
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

                    break;
                }
                else if ($winType == 'bonus' && $scatterCount == 3) {
                    if (count($winLines) > 0) {
                        continue;
                    }
                    
                    break;
                }
            }

            $objRes = [
                'action' => 'doSpin',

                'tw' => 0,
                'ls' => 0,
                'balance' => $BALANCE,
                'index' => $slotEvent['index'],
                'balance_cash' => $BALANCE,
                'balance_bonus' => '0',
                'reel_set' => $reels['id'],
                'na' => 's',
                'bl' => $slotEvent['bl'],
                'stime' => floor(microtime(true) * 1000),
                'sa' => implode(",", $reels['symbolsAfter']),
                'sb' => implode(",", $reels['symbolsBefore']),
                'sh' => '3',
                'c' => $bet,
                'sver' => '5',
                'counter' => ((int)$slotEvent['counter'] + 1),
                'l' => $lines,
                's' => implode(",", $reels['flatSymbols']),
                'w' => $winMoney,
            ];

            /* 와일드 멀티플라이어 응답 */
            $wildSymbols = array_filter($reels['wildSymbols'], function ($value, $key) {
                return $value != 0;
            }, ARRAY_FILTER_USE_BOTH);

            if (count($wildSymbols) > 0) {
                /* WILD심볼 치환, 2,3,5 멀티플라이어일때 14, 15, 16으로 심볼치환 */
                $WILDS = [
                    2 => 14, 
                    3 => 15,
                    5 => 16
                ];

                $flatSymbols = $reels['flatSymbols'];
                foreach ($wildSymbols as $pos => $multiplier) {
                    $flatSymbols[$pos] = $WILDS[$multiplier];
                }

                $objRes['s'] = implode(",", $flatSymbols);

                /* WILD심볼 정보 */
                $objRes['slm_mp'] = implode(",", array_keys($wildSymbols));
                $objRes['slm_mv'] = implode(",", array_values($wildSymbols));
            }

            /* 최소당첨릴생성인 경우 당첨금 계산 */
            if ($overtry && count($winLines) > 0) {
                /* 스핀 당첨금 */
                $winMoney = array_reduce($winLines, function($carry, $winLine) {
                    $carry += $winLine['Money']; 
                    return $carry;
                }, 0) * $bet;
            }

            $isState = true;
            /*  */
            if ($winMoney > 0) {
                $lmi = [];
                $lmv = [];

                /* 윈라인 구성 */
                foreach ($winLines as $idx => $winLine) {
                    $payLineId = $winLine['PayLineId'];
                    $winLineMoney = $winLine['Money'] * $bet;
                    
                    /* 윈라인 추가 */
                    $strLineSymbolPositions = implode("~", $winLine['Positions']);
                    $objRes["l${idx}"] = "${payLineId}~${winLineMoney}~${strLineSymbolPositions}";

                    if ($winLine['Multiplier'] > 1) {
                        array_push($lmi, $payLineId);
                        array_push($lmv, $winLine['Multiplier']);
                    }
                }

                $objRes['na'] = 'c';
                $objRes['balance'] = $BALANCE;
                $objRes['balance_cash'] = $objRes['balance'];

                $objRes['tw'] = $winMoney;             
                $objRes['w'] = $winMoney;

                /* 윈라인에 포함된 WILD */
                if (count($lmi) > 0) {
                    $objRes['slm_lmi'] = implode(",", $lmi);
                    $objRes['slm_lmv'] = implode(",", $lmv);
                }
            }
       
            /* 프리스핀 구매, 보너스당첨 */
            if ($winType == 'bonus') { 
                /* 보너스 당첨금, 3배당 */
                $bonusWinMoney = 3 * $bet * $lines;

                $objRes['tw'] = $bonusWinMoney;
                $objRes['w'] = $bonusWinMoney;

                [$fsmax, $fsTrail] = $slotSettings->GenerateFreespinTrail();
                $objRes['trail'] = "fs_counts~" . implode(",", $fsTrail);

                $objRes['fsmul'] = 1;
                $objRes['fs'] = 1;
                $objRes['fsmax'] = $fsmax;
                $objRes['fswin'] = 0;
                $objRes['fsres'] = 0;

                $objRes['reel_set'] = 2;

                $strScatters = $this->stringifyScatterSymbols($reels, $bonusWinMoney);
                $objRes['psym'] = $strScatters;

                /* 프리스핀 구매 */
                if ($slotEvent['slotEvent'] === 'buy_freespin') {
                    $objRes['purtr'] = 1;
                    $objRes['puri'] = 0;
                }

                /* 프리스핀변수 리셋 */
                $slotSettings->SetGameData($slotSettings->slotId . 'FSMax', $fsmax);
                $slotSettings->SetGameData($slotSettings->slotId . 'FSNext', 1);
                $slotSettings->SetGameData($slotSettings->slotId . 'FSStartBalance', $BALANCE);

                /* WILD 멀티플라이어 배열 생성 */
                $fsStickyGen = $slotSettings->GenerateFSSticky($fsmax, $slotEvent['slotEvent'] === 'buy_freespin');
                $slotSettings->SetGameData($slotSettings->slotId . 'FSStickyGen', $fsStickyGen);
                $isState = false;
            }

            /* 프리스핀 중 */
            else if ($slotEvent['slotEvent'] === 'freespin') {
                /* 프리스핀 시작밸런스 로드 */
                $BALANCE = $slotSettings->GetGameData($slotSettings->slotId . 'FSStartBalance');

                /* 프리스핀 응답 빌드 */
                $objRes['reel_set'] = 1;

                $objRes['w'] = $winMoney;
                $objRes['tw'] = $LASTSPIN->tw + $winMoney;

                $objRes['balance'] = $BALANCE;
                $objRes['balance_cash'] = $BALANCE;

                /* 프리스핀 스티키 WILD 위치정보 */
                if (count($wildSymbols) > 0) {
                    /* 랜덤 스티키셋 */
                    $setId = array_rand($slotSettings->StickyPositionMap);
                    $stickySet = $slotSettings->StickyPositionMap[$setId];

                    /* 랜덤 시작위치 */
                    $offsetID = array_rand($stickySet['available_offset']);
                    $startPos = $stickySet['available_offset'][$offsetID];

                    /* 랜덤 시작위치 0일경우 확율조정 */
                    if ($startPos == 0 && random_int(1, 10) != 1) {
                        $offsetID = $offsetID + 1;
                        $startPos = $stickySet['available_offset'][$offsetID];
                    }

                    $limitedIndices = [];
                    $stickyTrans = [];
                    foreach ($wildSymbols as $pos => $multiplier) {
                        /* 스티키셋에서 랜덤위치 선택 */
                        while( in_array($randPosIndex = array_rand($stickySet['map']), $limitedIndices));
                        $newPos = $stickySet['map'][$randPosIndex] + $startPos;

                        array_push($stickyTrans, "${pos},${newPos}");
                        array_push($limitedIndices, $randPosIndex);
                    }

                    if (!empty($stickyTrans)) {
                        $objRes['sty'] = implode("~", $stickyTrans);
                    }

                    /* 스티키셋 저장 */
                    $stickySet['offset'] = $startPos;
                    $slotSettings->SetGameData($slotSettings->slotId . 'FSNextStickySet', $stickySet);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FSStickyMultiplierMap', $wildSymbols);
                }

                /* 프리스핀 구매 */
                if (isset($LASTSPIN->puri)) {
                    $objRes['puri'] = 0;
                }

                if ($fsmax > $fs) {
                    /* 프리스핀중에는 스핀타입이 항상 s */
                    $objRes['na'] = 's';
                    
                    $objRes['fsmul'] = 1;
                    $objRes['fsmax'] = $fsmax;
                    $objRes['fs'] = $fs + 1;
    
                    $objRes['fswin'] = $LASTSPIN->fswin + $winMoney;
                    $objRes['fsres'] = $LASTSPIN->fsres + $winMoney;

                    /* 프리스핀 카운터 */
                    $slotSettings->SetGameData($slotSettings->slotId . 'FSNext', $objRes['fs']);
                    $isState = false;
                }
                else if ($fsmax <= $fs) {
                    /* 프리스핀 완료 */
                    $objRes['na'] = 'c';

                    $objRes['fs_total'] = $fsmax;
                    $objRes['fsmul_total'] = 1;
                    $objRes['fsend_total'] = 1;
                    $objRes['fswin_total'] = $LASTSPIN->fswin + $winMoney;
                    $objRes['fsres_total'] = $LASTSPIN->fsres + $winMoney;

                    /* 프리스핀 리셋 */
                    $slotSettings->SetGameData($slotSettings->slotId . 'FSMax', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FSNext', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FSNextStickySet', []);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FSStickyMultiplierMap', []);
                }
            }

            /* 밸런스 업데이트 */
            if ($winType == 'bonus') { 
                $winMoney = $bonusWinMoney;
            }

            if ($winMoney > 0) {
                $slotSettings->SetBalance($winMoney);
                $slotSettings->SetBank($slotEvent['slotEvent'] ?? '', -1 * $winMoney);
            }

            /* 라운드 등록 */
            if ($winType == 'bonus' || $winType == 'win' || $slotEvent['slotEvent'] == 'freespin') {
                $roundLogs = $slotSettings->GetGameData($slotSettings->slotId . 'RNDLogs') ?? [];
                array_push($roundLogs, $objRes);

                $slotSettings->SetGameData($slotSettings->slotId . 'RNDLogs', $roundLogs);
            }
            
            $_GameLog = json_encode($objRes);

            if($fsmax > 0 && $isState == true && ($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') ?? false) == true){
                $allBet = $bet * $lines * 100;
            }else{
                $allBet = $bet * $lines;
            }
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

        public function checkWinLines($reels, $slotSettings) {
            $REELCOUNT = 5;
            $S_WILD = 2;

            $PayLines = $slotSettings->PayLines;
            $PayTable = $slotSettings->PayTable;

            $flatSymbols = $reels['flatSymbols'];
            $wildSymbols = $reels['wildSymbols'];

            $winLines = [];
            foreach ($PayLines as $payLineId => $payLine) {
                $sameSymbolsCount = 0;
                $multiplier = 0;

                /* 라인 검사 */
                foreach ($payLine as $idx => $pos) {
                    /* 와일드 멀티플라이어 체크 */
                    if ($flatSymbols[$pos] == $S_WILD) {
                        $multiplier += $wildSymbols[$pos];
                    }

                    /* 첫심볼은 등록 */
                    if ($idx == 0) {
                        $firstSymbolPos = $payLine[0];
                        $firstSymbol = $flatSymbols[$firstSymbolPos];
        
                        $sameSymbolsCount = 1;
                        continue;
                    }

                    /* 시작심볼이 WILD이면 다음번 심볼을 시작심볼로 등록 */
                    if ($firstSymbol == $S_WILD) {
                        $firstSymbol = $flatSymbols[$pos];
                    }

                    /* 같은 심볼, WILD인 경우 혹 프리스핀에서 15,16번 ( WILD로 본다 )심볼인 경우 */
                    if ($firstSymbol == $S_WILD || $flatSymbols[$pos] == $firstSymbol || $flatSymbols[$pos] == $S_WILD) {
                        $sameSymbolsCount += 1;
                    }
                    else {
                        break;
                    }
                }

                /* 같은 심볼갯수가 1개이면 스킵 */
                if ($sameSymbolsCount <= 1) {
                    continue;
                }

                /* 페이테이블 검사 */
                $multiplier = ($multiplier == 0 ? 1 : $multiplier);
                $lineMoney = $PayTable[$firstSymbol][$REELCOUNT - $sameSymbolsCount] * $multiplier;

                if ($lineMoney > 0) {
                    array_push($winLines, [
                        'FirstSymbol' => $firstSymbol,
                        'RepeatCount' => $sameSymbolsCount,
                        'PayLineId' => $payLineId,
                        'Money' => $lineMoney,
                        'Multiplier' => $multiplier,
                        'Positions' => array_slice($payLine, 0, $sameSymbolsCount)
                    ]);
                }
            }
            
            return $winLines;
        }

        public function getScatterCount($reels) {
            $S_SCATTER = 1;
            $REELCOUNT = 5;
            $scatterCount = 0;

            for ($reelId=0; $reelId < $REELCOUNT; $reelId++) { 
                $scatterCount += count(array_keys($reels['symbols'][$reelId], $S_SCATTER));
            }

            return $scatterCount;
        }

        public function stringifyScatterSymbols($reels, $winMoney) {
            $S_SCATTER = 1;
            $symbols = array_keys($reels['flatSymbols'], $S_SCATTER);
            
            // '1~300.00~3,7,20,22'
            return "${S_SCATTER}~${winMoney}~" . implode(",", $symbols);
        }

        public function submitRound($slotSettings, $LASTSPIN, $objRes) {
            /* 라운드 체크 */
            $roundLogs = $slotSettings->GetGameData($slotSettings->slotId . 'RNDLogs') ?? [];

            /* 라운드시작상태가 아니거나 라운드스핀갯수가 0이라면 스킵 */
            if (count($roundLogs) == 0) {
                return;
            }

            /* 환수율 체크 */
            $base_bet = $LASTSPIN->c * $LASTSPIN->l;
            $bet = $base_bet;       // 프리스핀구매일 경우  x100
            $win = $LASTSPIN->tw;
            $rtp = $win / $bet;

            /* 베팅금보다 작다면 스킵 */
            if ($rtp < 1) {
                /* 라운드 리셋 */
                $slotSettings->SetGameData($slotSettings->slotId . 'RNDLogs', []);

                return;
            }

            /* 리플레이라운드 빌드 */
            $replayLogs = [];

            foreach($roundLogs as $gameLog) {
                $cr = [
                    'symbol' => 'vs25hotfiesta',
                    'repeat' => 0,
                    'action' => $gameLog['action'],
                    'index' => $gameLog['index'],
                    'counter' => $gameLog['counter'],
                ];
                
                if ($gameLog['action'] == 'doSpin') {
                    $cr['c'] = $gameLog['c'];
                    $cr['l'] = $gameLog['l'];

                    /* 프리스핀 구입 */
                    if (isset($gameLog['purtr'])) {
                        $cr['pur'] = 0;
                    }
                }
                
                $cr = $this->toResponse($cr);
                $sr = $this->toResponse($gameLog);
                
                array_push($replayLogs, [
                    'cr' => $cr,
                    'sr' => $sr
                ]);
            }
            
            /* 라운드 마감 doCollect 이벤트 */
            $cr = $this->toResponse([
                'symbol' => 'vs25hotfiesta',
                'repeat' => 0,
                'action' => 'doCollect',
                'index' => $objRes['index'],
                'counter' => $objRes['counter'],
            ]);
            
            $sr = $this->toResponse($objRes);

            array_push($replayLogs, [
                'cr' => $cr,
                'sr' => $sr
            ]);

            /* 리플레이 등록 */
            $userId = $slotSettings->user->id;
            $gameId = $slotSettings->game->original_id;

            \VanguardLTE\Jobs\UpdateReplay::dispatch([
                'user_id' => $userId,
                'game_id' => $gameId,
                'bet' => $bet,
                'brand_id' => config('app.stylename'),
                'base_bet' => $base_bet,
                'win' => $win,
                'rtp' => $rtp,
                'game_logs' => urlencode(json_encode($replayLogs)),
            ]);


            /* 라운드 리셋 */
            $slotSettings->SetGameData($slotSettings->slotId . 'RNDLogs', []);
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

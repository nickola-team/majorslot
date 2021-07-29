<?php 
namespace VanguardLTE\Games\UltraHoldandSpinPM
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

            if( $slotEvent['slotEvent'] == 'doInit' ) 
            { 
                $objRes = $this->doInit($slotEvent, $slotSettings);
            }
            else if( $slotEvent['slotEvent'] == 'doSpin' ) 
            {
                $objRes = $this->doSpin($slotEvent, $slotSettings, $LASTSPIN);
            }
            else if( $slotEvent['slotEvent'] == 'doCollect') 
            {
                $objRes = $this->doCollect($slotEvent);
            }
            else if( $slotEvent['slotEvent'] == 'doBonus') {
                $objRes = $this->doBonus($slotEvent, $slotSettings, $LASTSPIN);
            }
            else if( $slotEvent['slotEvent'] == 'doCollectBonus') {
                $objRes = $this->doCollectBonus($slotEvent, $LASTSPIN);
            }
            else if( $slotEvent['slotEvent'] == 'update' ) 
            {
                /* 리스핀일 경우 스핀 첫밸런스 로드 */
                if (isset($LASTSPIN->rsb_rt)) {
                    $BALANCE = $LASTSPIN->balance;
                }

                $objRes = [
                    'balance_bonus' => '0.00',
                    'balance' => $BALANCE,
                    'balance_cash' => $BALANCE,
                    'stime' => floor(microtime(true) * 1000),
                ];
            }

            $slotSettings->SaveGameData();
            \DB::commit();
            return $this->toResponse($objRes);
        }

        public function doInit($slotEvent, $slotSettings) {
            $BALANCE = $slotSettings->GetBalance();
            $LASTSPIN = $slotSettings->GetHistory();

            $objRes = [
                'tw' => '0.00',
                'def_s' => '9,3,11,6,6,11,5,9,11',
                'balance' => $BALANCE,
                'action' => 'doSpin',
                'cfgs' => '5419',
                'ver' => '2',
                'index' => $slotEvent['index'],
                'balance_cash' => $BALANCE,
                'reel_set_size' => '2',
                'def_sb' => '3,4,7',
                'def_sa' => '8,7,5',
                'reel_set' => '1',
                'bonusInit' => '[{bgid:0,bgt:48,mo_s:"13,13,13,13,13,13,13,13,13,14,14,14,14,14,14,14,15,15,15,15,15",mo_v:"5,10,15,20,25,30,35,40,45,50,55,60,70,80,90,100,500,1000,1500,2000,2500"}]',
                'balance_bonus' => '0.00',
                'na' => 's',
                'scatters' => '1~0,0,0~0,0,0~1,1,1',
                'gmb' => '0,0,0',
                'rt' => 'd',
                'stime' => floor(microtime(true) * 1000),
                'sa' => '8,7,4',
                'sb' => '8,6,11',
                'sc' => '10.00,20.00,50.00,80.00,100.00,150.00,250.00,500.00,750.00,1000.00,2000.00,3000.00,5000.00,10000.00,15000.00,20000.00,25000.00,30000.00,40000.00,50000.00',
                'defc' => '250.00',
                'sh' => '3',
                'wilds' => '2~250,0,0~1,1,1',
                'bonuses' => '0',
                'fsbonus' => '',
                'c' => '10.00',
                'sver' => '5',
                'counter' => ((int)$slotEvent['counter'] + 1),
                'l' => '5',
                'paytable' => '0,0,0;0,0,0;0,0,0;250,0,0;150,0,0;100,0,0;80,0,0;80,0,0;20,0,0;20,0,0;20,0,0;20,0,0;0,0,0;0,0,0;0,0,0;0,0,0',
                'rtp' => '96.70',
                'reel_set0' => '9,9,9,6,10,10,10,11,5,9,8,3,8,8,8,2,11,11,11,10,7,4,5,2,8,11,6,8,10,6,8,7,8,11,8,10,6,10~5,3,6,0,0,0,11,11,7,6,6,6,6,3,4,9,10,9,9,9,2,3,6,0,0,0,11,11,8,10,10,10,11,11,11,8,8,8,4,11~8,8,8,5,3,10,9,9,9,4,11,11,11,11,7,6,8,10,10,10,2,9,10,11,5,10,9,5,11,10,11,5,10,9,10,11,10,11,9,5,6,5,7,5,9,10,3,5,10,5,11,10',
                's' => '8,7,11,10,8,11,8,8,9',
                'reel_set1' => '10,10,10,10,9,5,7,11,11,11,8,6,8,8,8,4,9,9,9,2,11,3,9,2,9,11,2,3,11,3,11,8,11,2,11,2,9,11,9,2,11,2,11,9,11,9,11,2,9,2,11,2,11,9,2,11,8,11,2,6,9,6,11,2,11,8,11,9,11,8,3,11,8,2,6,8,2,3,11,2,9,2,8,9,2,9,2,8,11,6,8,11,3,11,2,5,2,11,2,3,11,3,2,8,2,11,2,8,11,8,11,2,11,2,11,6~9,6,6,6,5,10,6,8,9,9,9,3,11,3,6,0,0,0,11,11,4,8,8,8,7,2,10,10,10,11,11,11,8,10,3,6,0,0,0,11,11,3,8,10,11,6,3,11,7,3,10,11,5,8,11,8,6,8,3,11,3,6,0,0,0,11,11,6,5,10,11,5,11,11,8,11,10,6,8,3,6,0,0,0,11,11,6,10,6,11,10,11,2,8,5,11,7,3,11~8,7,3,8,8,8,6,11,11,11,2,9,9,9,9,10,11,10,10,10,5,4,5,10,2,4,11,3,11,5',
                'w' => '0.00',
            ];

            /* 마지막스핀 결과 로드 */
            if( $LASTSPIN !== NULL ) {
                $objRes['c'] = $LASTSPIN->c ?? null;
                $objRes['tw'] = $LASTSPIN->tw ?? $objRes['tw'];
                $objRes['w'] = $LASTSPIN->w ?? $objRes['w'];
                $objRes['sa'] = $LASTSPIN->sa ?? $objRes['sa'];
                $objRes['sb'] = $LASTSPIN->sb ?? $objRes['sb'];
                $objRes['s'] = $LASTSPIN->s ?? $objRes['s'];
                $objRes['na'] = $LASTSPIN->na ?? $objRes['na'];
                $objRes['reel_set'] = $LASTSPIN->reel_set ?? $objRes['reel_set'];

                /* 윈라인 l0, l1, l2 ... */
                $jsonLASTSPIN = json_decode(json_encode($LASTSPIN), true);
                $winLines = array_filter($jsonLASTSPIN, function ($value, $key) {
                    return strlen($key) > 1 && str_starts_with($key, "l");
                }, ARRAY_FILTER_USE_BOTH);

                $objRes = array_merge($objRes, $winLines);

                /* 리스핀 셋팅 */
                $objRes['mo'] = $LASTSPIN->mo ?? null;
                $objRes['mo_t'] = $LASTSPIN->mo_t ?? null;
                $objRes['rsb_rt'] = $LASTSPIN->rsb_rt ?? null;
                $objRes['bgid'] = $LASTSPIN->bgid ?? null;
                $objRes['bgt'] = $LASTSPIN->bgt ?? null;
                $objRes['coef'] = $LASTSPIN->coef ?? null;
                $objRes['rw'] = $LASTSPIN->rw ?? null;
                $objRes['wp'] = $LASTSPIN->wp ?? null;
                $objRes['lifes'] = $LASTSPIN->lifes ?? null;
                $objRes['bw'] = $LASTSPIN->bw ?? null;
                $objRes['end'] = $LASTSPIN->end ?? null;
                $objRes['is'] = $LASTSPIN->is ?? null;
                $objRes['ep'] = $LASTSPIN->ep ?? null;

                /* 리스핀일경우 당첨금 */
                if (isset($objRes['rsb_rt'])) {
                    $objRes['bw'] = $LASTSPIN->bw ?? $LASTSPIN->start_with->bw;
                    $objRes['c'] = $LASTSPIN->c ?? $LASTSPIN->start_with->c;
                    $objRes['balance'] = $LASTSPIN->balance ?? $LASTSPIN->start_with->balance;
                    $objRes['balance_cash'] = $objRes['balance'];
                }
            }

            return $objRes;
        }

        public function doSpin($slotEvent, $slotSettings, $LASTSPIN) {
            $S_WILD = 2;
            $REELCOUNT = 3;
            $SYMBOLCOUNT = 3;

            $lines = $slotEvent['l'];       // 라인
            $bet = $slotEvent['c'];         // 베팅액

            /* 스핀결과 결정 */
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $bet * $lines, $lines);

            $winType = $_spinSettings[0];                   // 보상방식
            $_winAvaliableMoney = $_spinSettings[1];        // 당첨금 한도

            /* Balance 업데이트 */
            $allBet = $bet * $lines;
            $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
            
            $bankMoney = $allBet / 100 * $slotSettings->GetPercent();
            $slotSettings->SetBank(($slotEvent['slotEvent'] ?? ''), $bankMoney);

            /* 벨런스 업데이트 */
            $BALANCE = $slotSettings->GetBalance();

            /* 코인 생성갯수  */
            $proposedCoinCount = $slotSettings->GenerateCoinCount($winType);  // 생성되어야할 Coin갯수 결정

            /* WILD 생성갯수 */
            if ($proposedCoinCount >= 3) {
                $proposedWildCount = 0;    
            }
            else {
                $proposedWildCount = $slotSettings->GenerateWildCount($winType);
            }

            /* 릴배치표 생성, 2천번 시행 */
            /*************************************************** */
            $overtry = false;           // 1500번이상 시행했을때 true

            for ($try=0; $try < 2000; $try++) { 
                $winMoney = 0;

                /* 릴배치표 생성 */
                if ($overtry) {
                    /* 더이상 자동릴생성은 하지 않고 최소당첨릴을 수동생성 */
                    // $lastReels = $isTumble ? json_decode($LASTSPIN->g, true) : null;
                    // $reels = $slotSettings->GetReelStrips($winType, $slotEvent['slotEvent'], $lastReels, 0);
                }
                else {
                    $reels = $slotSettings->GetReelStrips($winType, $slotEvent['slotEvent'], $proposedCoinCount, $proposedWildCount);
                }
                
                /* 릴배치표 확장 */
                $reels = $this->GetExtendedReelStrips($reels);

                /* 윈라인 체크 */
                $winLines = $this->checkWinLines($reels, $slotSettings, $bet);

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
                else if ($winType == 'bonus' && count($reels['coinSymbols']) == 3) {
                    break;
                }
            }

            $objRes = [
                'action' => 'doSpin',

                'tw' => $winMoney,
                'balance' => $BALANCE,
                'index' => $slotEvent['index'],
                'balance_cash' => $BALANCE,
                'reel_set' => '0',
                'balance_bonus' => '0',
                'na' => 's',
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

            /* 코인심볼 체크 */
            $coinSymbols = $reels['coinSymbols'];
            if (count($coinSymbols) > 0) {
                $coinValues = array_fill(0, $REELCOUNT * $SYMBOLCOUNT, 0);
                $coinTypes = array_fill(0, $REELCOUNT * $SYMBOLCOUNT, 'r');

                $wp = 0;
                foreach ($coinSymbols as $pos => $coin) {
                    $pos = 1 + $pos * $REELCOUNT;
                    $coinValues[$pos] = $coin['value'];
                    $coinTypes[$pos] = $coin['type'];

                    $wp += $coin['value'];
                }

                $objRes["mo"] = implode(",", $coinValues);
                $objRes["mo_t"] = implode(",", $coinTypes);

                /* 리스핀 발동 */
                if ($winType == 'bonus' && count($coinSymbols) == 3) {

                    $objRes['na'] = 'b';
                    $objRes['rsb_rt'] = 0;
                    $objRes['bgid'] = 0;
                    $objRes['bgt'] = 48;

                    $objRes['coef'] = $bet;
                    $objRes['rw'] = $wp * $bet;
                    $objRes['wp'] = $wp;

                    $objRes['lifes'] = '4';
                    $objRes['bw'] = '1';
                    $objRes['end'] = '0';

                    /* 밸런스 업데이트 */
                    $slotSettings->SetBalance($objRes['rw']);
                    $slotSettings->SetBank($slotEvent['slotEvent'] ?? '', -1 * $objRes['rw']);

                    /* 리스핀 시작밸런스 유지 */
                    // $slotSettings->SetGameData($slotSettings->slotId . 'RSStartBalance', $BALANCE);

                    /* 리스핀 셋팅 */
                    $respinCount = $slotSettings->GenerateRespinCount();
                    $slotSettings->SetGameData($slotSettings->slotId . 'RSRoundCount', $respinCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RSCurrentRound', 0);
                }
            }
            
            if ($winMoney > 0) {
                $objRes['na'] = 'c';
                
                /* 윈라인 구성 */
                foreach ($winLines as $idx => $winLine) {
                    $winLineMoney = $winLine['Money'] * $bet;
                    
                    /* 윈라인 추가 */
                    $payLineId = $winLine['PayLineId'];
                    $strLineSymbolPositions = implode("~", $winLine['Positions']);
                    $objRes["l${idx}"] = "${payLineId}~${winLineMoney}~${strLineSymbolPositions}";
                }

                /* 확장릴 구성 */
                if (isset($reels['isExtended'])) {
                    /* 확장전 릴배치표 */
                    $objRes['is'] = implode(",", $reels['orgFlatSymbols']);

                    $strWildSymbols = implode(",", $reels['wildSymbols']);
                    $strExtendedWildSymbols = implode(",", $reels['extendedWildSymbols']);
                    
                    /* 와일드심볼 배열 */
                    $objRes['ep'] = "$S_WILD~$strWildSymbols~$strExtendedWildSymbols";
                }
            }
            else if (isset($reels['isExtended'])) {
                /* WILD 확장릴셋이 아닌경우 본래의 릴셋 복원 */
                $objRes['s'] = implode(",", $reels['orgFlatSymbols']);
            }

            if($winMoney > 0) 
            {
                $slotSettings->SetBalance($winMoney);
                $slotSettings->SetBank($slotEvent['slotEvent'] ?? '', -1 * $winMoney);
            }

            $_GameLog = json_encode($objRes);
            $slotSettings->SaveLogReport($_GameLog, $allBet, $slotEvent['l'], $winMoney, $slotEvent['slotEvent']);
            
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

        public function doBonus($slotEvent, $slotSettings, $LASTSPIN) {
            $maxRound = $slotSettings->GetGameData($slotSettings->slotId . 'RSRoundCount');
            $curRound = $slotSettings->GetGameData($slotSettings->slotId . 'RSCurrentRound');

            $isStartSpin = isset($LASTSPIN->bw);     // 리스핀 첫스핀
            $startSpin = $isStartSpin ? $LASTSPIN : $LASTSPIN->start_with;
            $bet = $startSpin->c;
            $lifes = $LASTSPIN->lifes;

            $isEnded = false;

            /* 리스핀 결과 결정 */
            $newCoinCount = $slotSettings->GetRespinSetting($curRound, $maxRound, $lifes);

            if ($newCoinCount > 0) {
                /* 리스핀 릴셋 생성 */
                $coins = $slotSettings->GetRespinReelStrips($startSpin, $newCoinCount);

                $coinValues = $coins['values'];
                $coinTypes = $coins['types'];
                $coinSymbols = $coins['symbols'];

                $winMoney = array_sum($coinValues);
                $totalWin = $LASTSPIN->wp + $winMoney;

                /* 다음 라운드 */
                $lifes = 4;
                $curRound ++;

                /* 밸런스 업데이트 */
                $slotSettings->SetBalance($winMoney * $bet);
                $slotSettings->SetBank($slotEvent['slotEvent'] ?? '', -1 * $winMoney * $bet);
            }
            else {
                $lifes --;
                
                if ($lifes == 0) {
                    /* 리스핀 완료 */
                    $isEnded = true;
                }
                else {
                }
                
                /* 당첨코인이 없는경우 */
                $coinValues = explode(",", $startSpin->mo);
                $coinTypes = explode(",", $startSpin->mo_t);
                $coinSymbols = array_map(function($symbol) { return ($symbol > 12 ? $symbol : 12); }, explode(",", $startSpin->s));

                $totalWin = $LASTSPIN->wp;
            }

            /* 응답 빌드 */
            $BALANCE = $LASTSPIN->balance;
            $spinType = ($isEnded == true ? 'cb' : 'b');

            $objRes = [
                'rsb_rt' => ($newCoinCount > 0 ? 1 : 0),
                'bgid' => '0',
                'balance' => $BALANCE,
                'mo' => implode(",", $coinValues),
                'coef' => $bet,
                'mo_t' => implode(",", $coinTypes),
                'index' => $slotEvent['index'],
                'balance_cash' => $BALANCE,
                'balance_bonus' => '0',
                'na' => $spinType,
                'rw' => $totalWin * $bet,
                'stime' => floor(microtime(true) * 1000),
                'bgt' => '48',
                'lifes' => $lifes,
                'wp' => $totalWin,
                'end' => $isEnded,
                'sver' => '5',
                'counter' => ((int)$slotEvent['counter'] + 1),
                's' => implode(",", $coinSymbols),
            ];

            if ($isEnded) {
                $objRes['tw'] = $LASTSPIN->rw;
            }

            /* 라운드 등록 */
            $slotSettings->SetGameData($slotSettings->slotId . 'RSCurrentRound', $curRound);
            
            $_GameLog = json_encode(array_merge($objRes, ['start_with' => $isStartSpin ? $LASTSPIN : $LASTSPIN->start_with]));
            $slotSettings->SaveLogReport($_GameLog, /* $allBet, $slotEvent['l'], $winMoney,*/ 0, 0, 0, $slotEvent['slotEvent']);

            return $objRes;
        }

        public function doCollectBonus($slotEvent, $LASTSPIN) {
            $BALANCE = $this->slotSettings->GetBalance();

            return [
                'balance' => $BALANCE,
                'coef' => 1,
                'index' => $slotEvent['index'],
                'balance_cash' => $BALANCE,
                'balance_bonus' => '0.00',
                'na' => 's',
                'rw' => $LASTSPIN->tw,
                'wp' => 0,
                'stime' => floor(microtime(true) * 1000),
                'sver' => '5',
                'counter' => ((int)$slotEvent['counter'] + 1),
            ];
        }

        public function checkWinLines($reels, $slotSettings) {
            $REELCOUNT = 3;
            $LINESYMBOLCOUNT = 3;
            $S_WILD = 2;

            $PayLines = $slotSettings->PayLines;
            $PayTable = $slotSettings->PayTable;

            $flatSymbols = $reels['flatSymbols'];

            $winLines = [];
            foreach ($PayLines as $payLineId => $payLine) {
                $sameSymbolsCount = 0;

                /* 라인 검사 */
                foreach ($payLine as $idx => $pos) {
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
                $lineMoney = $PayTable[$firstSymbol][$REELCOUNT - $sameSymbolsCount];

                if ($lineMoney > 0) {
                    array_push($winLines, [
                        'FirstSymbol' => $firstSymbol,
                        'RepeatCount' => $sameSymbolsCount,
                        'PayLineId' => $payLineId,
                        'Money' => $lineMoney,
                        'Positions' => array_slice($payLine, 0, $sameSymbolsCount)
                    ]);
                }
            }
            
            return $winLines;
        }

        public function GetExtendedReelStrips($reels) {
            $S_WILD = 2;
            $SYMBOLCOUNT = 3;
            $REELCOUNT = 3;

            /* 와일드심볼이 있는 릴 체크 */
            $wild_reel_ids = [];
            $wild_symbols = [];
            foreach ($reels['symbols'] as $reelId => $symbols) {
                $wild_pos_id = array_search($S_WILD, $symbols);
                if ($wild_pos_id !== false) {
                    array_push($wild_reel_ids, $reelId);
                    array_push($wild_symbols, $reelId + $wild_pos_id * $REELCOUNT);
                }
            }

            /* 릴확장 */
            if (count($wild_reel_ids) > 0) {
                $reels['isExtended'] = true;
                $reels['orgSymbols'] = $reels['symbols'];
                $reels['orgFlatSymbols'] = $reels['flatSymbols'];

                /* 릴 확장 */
                foreach ($wild_reel_ids as $reelId) {
                    $reels['symbols'][$reelId] = array_fill(0, $SYMBOLCOUNT, $S_WILD);
                }

                /* 평활화 */
                $flatSymbols = [];
                foreach ($reels['symbols'] as $reelId => $symbols) {
                    foreach ($symbols as $k => $symbol) {
                        $flatSymbols[$reelId + $k * $REELCOUNT] = $symbol;
                    }
                }
                ksort($flatSymbols);
                $reels['flatSymbols'] = $flatSymbols;

                /* 와일드 등록 */
                $reels['wildSymbols'] = $wild_symbols;

                /* 확장 와일드 등록 */
                $extended_wild_symbols = [];
                foreach ($flatSymbols as $pos => $symbol) {
                    if ($symbol == $S_WILD) {
                        array_push($extended_wild_symbols, $pos);
                    }
                }
                $reels['extendedWildSymbols'] = $extended_wild_symbols;
            }

            return $reels;
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
    }
}

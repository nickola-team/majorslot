<?php 
namespace VanguardLTE\Games\PirateGoldPM
{
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
            
            $slotEvent = $request->all();
            if( !isset($slotEvent['action']) ) 
            {
                return '';
            }
            
            /* 전역상수 */
            $WILD = 2;
            $BONUS = 1;
            $MONEY = 13;

            $BALANCE = $slotSettings->GetBalance();
            $LASTSPIN = $slotSettings->GetHistory();
            // $LASTSPIN = null;

            $slotEvent['slotEvent'] = $slotEvent['action'];

            if( $slotEvent['slotEvent'] == 'doInit' ) 
            { 
                $objRes = [
                    'def_s' => '6,7,4,2,8,4,3,5,6,7,8,5,7,3,4,4,3,5,6,7',
                    'balance' => $BALANCE,
                    'cfgs' => '4189',
                    'ver' => '2',
                    'mo_s' => '13',
                    'index' => $slotEvent['index'],
                    'balance_cash' => $BALANCE,
                    'reel_set_size' => '2',
                    'def_sb' => '3,4,7,6,8',
                    'mo_v' => '40,80,120,160,200,240,280,320,400,560,640,720,800,960,2000,8000',
                    'def_sa' => '8,7,5,3,7',
                    'mo_jp' => '2000;8000;40000',
                    'balance_bonus' => '0.00',
                    'na' => 's',
                    'scatters' => '1~0,0,1,0,0~10,10,10,0,0~1,1,1,1,1',
                    'gmb' => '0,0,0',
                    'bg_i' => '2,3,5,2,3,5',
                    'rt' => 'd',
                    'mo_jp_mask' => 'jp3;jp2;jp1',
                    'stime' => floor(microtime(true) * 1000),
                    'sa' => '8,7,5,3,7',
                    'sb' => '3,4,7,6,8',
                    'sc' => implode(',', $slotSettings->Bet),
                    'defc' => '25.00',
                    'sh' => '4',
                    'wilds' => '2~0,0,0,0,0~1,1,1,1,1;15~0,0,0,0,0~1,1,1,1,1;16~0,0,0,0,0~1,1,1,1,1',
                    'bonuses' => '0',
                    'fsbonus' => '',
                    'c' => '25.00',
                    'sver' => '5',
                    'n_reel_set' => '0',
                    'bg_i_mask' => 'bgm,bgm,bgm,fgm,fgm,fgm',
                    'counter' => ((int)$slotEvent['counter'] + 1),
                    'paytable' => '0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;500,100,20,2,0;500,100,20,2,0;300,50,15,2,0;300,50,15,2,0;200,40,10,2,0;200,40,10,2,0;75,25,5,0,0;75,25,5,0,0;50,15,5,0,0;50,15,5,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0;0,0,0,0,0',
                    'l' => '40',
                    'rtp' => '96.50',
                    'reel_set0' => '8,10,3,3,3,3,11,9,8,8,6,6,5,5,7,7,4,4,4,4,9,6,6,13,13,13,10,12,5,5,11,8,8,7,7~11,10,9,1,12,5,5,7,7,6,6,2,2,11,9,7,7,3,3,3,3,10,8,8,6,6,13,13,13,13,4,4,4,4,5,5,8,8~8,10,6,6,7,7,3,3,3,3,1,11,9,5,5,8,8,13,13,13,13,6,6,2,2,11,4,4,4,4,5,5,12,9,8,8,10,7,7~8,11,13,13,13,13,3,3,3,3,10,9,1,12,7,7,7,7,6,6,6,6,2,2,2,2,10,4,4,4,4,5,5,5,5,9,8,8,8,8,11~8,11,9,10,6,6,6,6,2,2,2,2,10,12,4,4,4,4,9,3,3,3,3,13,13,13,13,13,5,5,5,5,11,8,8,8,8,7,7,7,7',
                    's' => '6,7,4,2,8,4,3,5,6,7,8,5,7,3,4,4,3,5,6,7',
                    'reel_set1' => '8,10,3,3,3,3,11,9,8,8,6,6,5,5,7,7,4,4,4,4,9,6,6,13,13,13,10,12,5,5,11,8,8,7,7~11,10,9,1,12,5,5,7,7,6,6,2,2,11,15,15,15,15,9,7,7,16,16,16,16,10,8,8,6,6,13,13,13,13,5,5,8,8~8,10,16,16,16,16,6,6,7,7,1,11,9,15,15,15,15,5,5,8,8,13,13,13,13,6,6,2,2,11,5,5,12,9,8,8,10,7,7~8,11,13,13,13,13,10,16,16,16,16,9,1,12,7,7,7,7,6,6,6,6,15,15,15,15,2,2,2,2,10,5,5,5,5,9,8,8,8,8,11~8,11,9,10,6,6,6,6,2,2,2,2,10,16,16,16,16,12,9,13,13,13,13,13,5,5,5,5,11,15,15,15,15,8,8,8,8,7,7,7,7',
                ];

                /* 마지막스핀 결과 로드 */
                if( $LASTSPIN !== NULL ) {
                    
                    /* 머니심볼 */
                    $objRes['mo'] = $LASTSPIN->mo ?? null;
                    $objRes['mo_t'] = $LASTSPIN->mo_t ?? null;
                    
                    /* 럭키스핀일때 */
                    if (isset($LASTSPIN->rsb_s)) {
                        $objRes['c'] = $LASTSPIN->c ?? $LASTSPIN->start_with->c;
                        $objRes['tw'] = $LASTSPIN->tw ?? $LASTSPIN->start_with->tw;
                        $objRes['w'] = $LASTSPIN->w ?? $LASTSPIN->start_with->w;
                        $objRes['n_reel_set'] = $LASTSPIN->n_reel_set ?? $LASTSPIN->start_with->n_reel_set;
                        $objRes['sa'] = $LASTSPIN->sa ?? $LASTSPIN->start_with->sa;
                        $objRes['sb'] = $LASTSPIN->sb ?? $LASTSPIN->start_with->sb;
                        $objRes['s'] = $LASTSPIN->s ?? $LASTSPIN->start_with->s;

                        $objRes['balance'] = $LASTSPIN->balance ?? null;
                        $objRes['balance_cash'] = $LASTSPIN->balance_cash ?? null;

                        $objRes['rsb_s'] = $LASTSPIN->rsb_s ?? $LASTSPIN->start_with->rsb_s;
                        $objRes['rsb_rt'] = $LASTSPIN->rsb_rt ?? $LASTSPIN->start_with->rsb_rt;
                        $objRes['rsb_m'] = $LASTSPIN->rsb_m ?? $LASTSPIN->start_with->rsb_m;
                        $objRes['rsb_c'] = $LASTSPIN->rsb_c ?? $LASTSPIN->start_with->rsb_c;
                        $objRes['na'] = $LASTSPIN->na ?? $LASTSPIN->start_with->na;
                        $objRes['rsb_mu'] = $LASTSPIN->rsb_mu ?? $LASTSPIN->start_with->rsb_mu;
                        $objRes['e_aw'] = $LASTSPIN->e_aw ?? $LASTSPIN->start_with->e_aw;

                        $objRes['bw'] = $LASTSPIN->bw ?? $LASTSPIN->start_with->bw;         

                        /* 럭키스핀 당첨금 */
                        $moneySymbolValues = explode(",", $objRes['mo']);
                        $objRes['bpw'] = array_sum($moneySymbolValues) * $objRes['c'];
                    }
                    else {
                        $objRes['c'] = $LASTSPIN->c ?? null;
                        $objRes['tw'] = $LASTSPIN->tw ?? null;
                        $objRes['w'] = $LASTSPIN->w ?? null;
                        $objRes['n_reel_set'] = $LASTSPIN->n_reel_set ?? 0;
                        $objRes['sa'] = $LASTSPIN->sa ?? null;
                        $objRes['sb'] = $LASTSPIN->sb ?? null;
                        $objRes['s'] = $LASTSPIN->s ?? $objRes['s'];

                        /* 윈라인 l0, l1, l2 ... */
                        $jsonLASTSPIN = json_decode(json_encode($LASTSPIN), true);
                        $winLines = array_filter($jsonLASTSPIN, function ($value, $key) {
                            return strlen($key) > 1 && str_starts_with($key, "l");
                        }, ARRAY_FILTER_USE_BOTH);

                        $objRes = array_merge($objRes, $winLines);
                        
                        /* 프리스핀 */
                        $objRes['fsmul'] = $LASTSPIN->fsmul ?? null;
                        $objRes['fsmax'] = $LASTSPIN->fsmax ?? null;
                        $objRes['fs'] = $LASTSPIN->fs ?? null;
                        $objRes['fsres'] = $LASTSPIN->fsres ?? null;
                        $objRes['fswin'] = $LASTSPIN->fswin ?? null;

                        if ($objRes['fsmax'] != null && $objRes['fsmax'] > 0) {
                            $objRes['balance'] = $LASTSPIN->balance ?? null;
                            $objRes['balance_cash'] = $LASTSPIN->balance_cash ?? null;
                        }
                    }
                }
            }
            else if( $slotEvent['slotEvent'] == 'doSpin' ) 
            {
                $lines = $slotEvent['l'];       // 라인
                $bet = $slotEvent['c'];         // 베팅액

                $MoneyTable = $slotSettings->MoneyTable;
                
                /* 남은 프리스핀이 있을 경우 */
                $fsmax = $LASTSPIN->fsmax ?? null;
                $fs = $LASTSPIN->fs ?? null;
                
                /* 이전 프리스핀검사, 새 스핀결과 결정 */
                if ($fsmax !== NULL && $fsmax > 0) {
                    $slotEvent['slotEvent'] = 'freespin';
                }

                $_spinSettings = $slotSettings->GetSpinSettings($slotEvent['slotEvent'], $bet, $lines);

                $winType = $_spinSettings[0];                   // 보상방식
                $_winAvaliableMoney = $_spinSettings[1];        // 당첨금 한도

                // *** added by pine ***
                $isGeneratedFreeStack = false;
                $freeStacks = []; // free stacks
                $isForceWin = false;
                // *** - ***
                
                /* Balance 업데이트 */
                if ($slotEvent['slotEvent'] == 'freespin') {
                    $allBet = 0;
                }
                else {
                    $allBet = $bet * $lines;
                    $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                    
                    $bankMoney = $allBet / 100 * $slotSettings->GetPercent();
                    $slotSettings->SetBank(($slotEvent['slotEvent'] ?? ''), $bankMoney);
                }

                // *** added by pine ***
                if ($slotEvent['slotEvent'] === 'freespin') {
                    $freeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'FreeStacks')  ?? [];
                    if($freeStacks != 0 && count($freeStacks) >= $fsmax){
                        $isGeneratedFreeStack = true;
                    }
                    $leftFreeGames = $fsmax - $fs;
                    if($leftFreeGames <= mt_rand(1 , 2) && ($LASTSPIN->tw ?? 0) == 0){
                        $winType = 'win';
                        $_winAvaliableMoney = $slotSettings->GetBank($slotEvent['slotEvent']);
                        $isForceWin = true;
                    }    
                    $regularSpinCount = $slotSettings->GetGameData($slotSettings->slotId . 'RegularSpinCount') ?? 0;
                }else{
                    $roundstr = sprintf('%.4f', microtime(TRUE));
                    $roundstr = str_replace('.', '', $roundstr);
                    $roundstr = '275' . substr($roundstr, 4, 7);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RoundID', $roundstr);   // Round ID Generation
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', []);
                    $regularSpinCount = $slotSettings->GetGameData($slotSettings->slotId . 'RegularSpinCount') ?? 0 + 1;
                    $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', $regularSpinCount);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $leftFreeGames = 0;
                }
                // *** - ***

                $BALANCE = $slotSettings->GetBalance();

                /* 릴배치표 생성, 2천번 시행 */
                /*************************************************** */
                $overtry = false;           // 1500번이상 시행했을때 true

                for ($try=0; $try < 2000; $try++) { 
                    $winMoney = 0;
                    $luckyMoney = 0;
                    $bonusSymbols = [];
                    
                    /* 머니심볼 초기화 */
                    $moneySymbols = [];
                    $moneySymbolValues = array_fill(0, 20, 0);
                    $moneySymbolTypes = array_fill(0, 20, 'r');

                    // *** added by pine ***
                    if($isGeneratedFreeStack == true){
                        $freeStack = $freeStacks[$fs - 1];
                        $reels = $freeStack['Reel'];
                        $moneySymbolValues = $freeStack['MoneySymbolValues'];
                    }
                    else {
                    // *** - ***

                        /* 릴배치표 생성 */
                        if ($overtry) {
                            /* 더이상 자동릴생성은 하지 않고 최소당첨릴을 수동생성 */
                            $reels = $slotSettings->GetLimitedReelStrips($slotEvent['slotEvent']);
                        }
                        else {
                            $reels = $slotSettings->GetReelStrips($winType, $slotEvent['slotEvent']);
                        }
                    }
                    /* 릴셋 1차원배열 생성 */
                    $flattenSymbols = [];
                    foreach ($reels['symbols'] as $reelId => $symbols) {
                        foreach ($symbols as $k => $symbol) {
                            $flattenSymbols[$reelId + $k * 5] = $symbol;
                        }
                    }
                    ksort($flattenSymbols);

                    /* 윈라인 검사 */
                    $winLines = $this->checkWinLines($flattenSymbols, $slotSettings->PayLines, $slotSettings->PayTable, $bet, $slotEvent['slotEvent']);

                    /* 머니심볼, 보너스심볼 조사 */
                    $moneySymbols = array_filter($flattenSymbols, function ($value) use ($MONEY) { return $value == $MONEY; });
                    $bonusSymbols = array_filter($flattenSymbols, function ($value) use ($BONUS) { return $value == $BONUS; });

                    /* 머니심볼 머니배당 */
                    foreach ($moneySymbols as $pos => $symbol) {
                        // *** added & changed by pine ***
                        if($isGeneratedFreeStack == false){
                            $idx = array_rand($MoneyTable["standard"]);
                            $moneySymbolValues[$pos] = $MoneyTable["standard"][$idx];
                        }
                        // *** - ***
                        $moneySymbolTypes[$pos] = "v";
                    }

                    /* 릴셋 검사 */
                    if ($overtry) {
                        break;
                    }

                    if ($try > 1000) {
                        $winType = 'none';
                    }
                    if ($try > 1500 ){
                        $overtry = true;
                        continue;
                    }

                    // *** added by pine ***
                    if($isGeneratedFreeStack == true){
                        /* 스핀 당첨금 */
                        $winMoney = array_reduce($winLines, function($carry, $winLine) {
                            $carry += $winLine['Money']; 
                            return $carry;
                        }, 0);
                        break;  //freestack
                    }
                    // *** - ***

                    if ($winType != "bonus" && count($bonusSymbols) == 3) {
                        continue;
                    }
                    
                    if ($winType != "lucky" && count($moneySymbols) >= 8) {
                        continue;
                    }

                    if ($winType == 'none' && count($winLines) == 0) {
                        break;
                    }
                    else if ($winType == 'win' && count($winLines) > 0) {
                        /* 스핀 당첨금 */
                        $winMoney = array_reduce($winLines, function($carry, $winLine) {
                            $carry += $winLine['Money']; 
                            return $carry;
                        }, 0);

                        // *** added by pine ***
                        if($isForceWin == true && $winMoney > 0 && $winMoney < $bet * $lines * 10){
                            break;   // win by force when winmoney is 0 in freespin
                        }
                        // *** - ***
                        /* 당첨금이 한도이상일때 스킵 */
                        if ($winMoney >= $_winAvaliableMoney) {
                            continue;
                        }

                        break;
                    }
                    else if ($winType == "bonus" && count($bonusSymbols) == 3) {
                        /* 프리스핀 발동 */
                        break;
                    }
                    else if ($winType == "lucky" && count($moneySymbols) >= 8 && count($moneySymbols) < 12) {
                        /* 럭키스핀 첫 당첨금이 한도이상일때 스킵 */
                        $luckyMoney = $slotSettings->SumMoneySymbols($moneySymbolValues, $moneySymbolTypes, 0, true) * $bet;
                        if ($luckyMoney >= $_winAvaliableMoney ) {
                            continue;
                        }

                        /* Lucky Treasure 발동 */
                        break;
                    }
                    else {

                    }
                }

                /*********************************************** */
                /* 응답 빌드 */
                $spinType = $winMoney > 0 ? 'c' : 's';

                $objRes = [
                    'tw' => $winMoney,
                    'balance' => $BALANCE,
                    'index' => $slotEvent['index'],
                    'balance_cash' => $BALANCE,
                    'balance_bonus' => 0,
                    'na' => $spinType,
                    'stime' => floor(microtime(true) * 1000),
                    'sa' => implode(",", $reels['symbolsAfter']),
                    'sb' => implode(",", $reels['symbolsBefore']),
                    'sh' => 4,
                    'c' => $bet,
                    'sver' => 5,
                    'n_reel_set' => $reels['id'],
                    'counter' => ((int)$slotEvent['counter'] + 1),
                    'l' => $lines,
                    's' => implode(",", $flattenSymbols),
                    'w' => $winMoney,
                ];

                /* 윈라인 필드 */
                if ($winMoney > 0 && count($winLines) > 0) {
                    foreach ($winLines as $idx => $winLine) {
                        $payLineId = $winLine['PayLineId'];
                        $winLineMoney = $winLine['Money'];
                        $strLineSymbolPositions = implode("~", $winLine['Positions']);
                        $objRes["l${idx}"] = "${payLineId}~${winLineMoney}~${strLineSymbolPositions}";
                    }
                }

                /* 머니심볼 필드 */
                if (count($moneySymbols) > 0) {
                    $objRes["mo"] = implode(",", $moneySymbolValues);
                    $objRes["mo_t"] = implode(",", $moneySymbolTypes);

                    /* 럭키스핀 */
                    if (count($moneySymbols) >= 8) {
                        $objRes["na"] = 'b';        //  럭키스핀 타입
                        $objRes["rsb_s"] = '13,14';
                        $objRes["rsb_rt"] = '0';        
                        $objRes["rsb_m"] = '3';
                        $objRes["rsb_c"] = '0';
                        $objRes["rsb_mu"] = '0';
                        $objRes["bw"] = '1';
                        $objRes["e_aw"] = '0';

                        /* 럭키스핀 최대 머니심볼갯수 결정, 보관 */
                        $maxMoneySymbolsCount = random_int(count($moneySymbols) + 1, 16);
                        $slotSettings->SetGameData($slotSettings->slotId . 'LSMaxSymbols', $maxMoneySymbolsCount);
                        
                        // *** added by pine ***
                        $isState = false;
                        $slotSettings->SetGameData($slotSettings->slotId . 'FSStartBalance', $BALANCE);
                        // *** - ***
                    }
                }

                // *** added by pine ***
                $isState = true;
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $winMoney);
                // *** - ***

                /* 프리스핀 */
                if ($slotEvent['slotEvent'] == 'freespin') {
                    /* 프리스핀 시작밸런스 로드 */
                    $BALANCE = $slotSettings->GetGameData($slotSettings->slotId . 'FSStartBalance');

                    $objRes['balance'] = $BALANCE;
                    $objRes['balance_cash'] = $BALANCE;
                    $objRes['tw'] = ($LASTSPIN->tw ?? 0) + $winMoney;

                    if ($fsmax > $fs) {
                        /* 프리스핀중에는 스핀타입이 항상 s */
                        $objRes['na'] = 's';
    
                        $objRes["fsmul"] = 1;
                        $objRes["fsmax"] = $fsmax;
                        $objRes["fs"] = $fs + 1;
                        $objRes["fswin"] = $objRes['tw'];
                        $objRes["fsres"] = $objRes['tw'];
                        $objRes["n_reel_set"] = 1;
                        
                        // *** added by pine ***
                        $isState = false;
                        // *** - ***
                    }
                    else {
                        /* 프리스핀 완료 */
                        $objRes['na'] = $objRes['tw'] > 0 ? 'c' : 's';

                        $objRes['fs_total'] = $fsmax;
                        $objRes['fswin_total'] = $objRes['tw'];
                        $objRes['fsmul_total'] = 1;
                        $objRes['fsres_total'] = $objRes['tw'];
                        $objRes["n_reel_set"] = 0;
                    }

                    /* 프리스핀에서 또 프리스핀 발동 */
                    if (count($bonusSymbols) == 3) {
                        $objRes["fsmax"] = $fsmax + 10;
                    }
                }
                else if (count($bonusSymbols) == 3) {
                    /* 프리스핀 시작 */
                    $objRes["fsmul"] = 1;
                    $objRes["fsmax"] = 10;
                    $objRes["fswin"] = 0;
                    $objRes["fs"] = 1;
                    $objRes["fsres"] = 0;
                    // $objRes["psym"] = "1~400.00~13,16,17";
                    $objRes["n_reel_set"] = 1;

                    /* 프리스핀 시작밸런스 저장 */
                    $slotSettings->SetGameData($slotSettings->slotId . 'FSStartBalance', $BALANCE);
                    
                    // *** added by pine ***
                    $slotSettings->SetGameData($slotSettings->slotId . 'RegularSpinCount', 0);                    
                    $isState = false;
                    if($slotSettings->IsAvailableFreeStack() || $slotSettings->happyhouruser){
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeStacks', $slotSettings->GetFreeStack($bet, count($bonusSymbols) - 3));
                    }
                    // *** - ***
                }
                /*********************************************** */

                /* 밸런스 업데이트 */
                if( $winMoney > 0) 
                {
                    $slotSettings->SetBalance($winMoney);
                    $slotSettings->SetBank($slotEvent['slotEvent'] ?? '', -1 * $winMoney);
                }

                /* 러키스핀일때 밸런스 업데이트 */
                // *** changed by pine ***
                if ($luckyMoney > 0) {
                    $slotSettings->SetBalance($luckyMoney);
                    $slotSettings->SetBank('luckyspin', -1 * $luckyMoney);
                    
                    // *** added by pine ***
                    $bonusWin = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') ?? 0;
                    $bonusWin = $bonusWin + $luckyMoney;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $bonusWin);
                    // *** - ***
                }
                // *** - ***
                
                $_GameLog = json_encode($objRes);
                // *** added & changed by pine ***
                $slotSettings->SaveLogReport($_GameLog, $bet * $lines, $slotEvent['l'], $objRes['tw'], $slotEvent['slotEvent'], $isState);
                // *** - ***
            }
            else if( $slotEvent['slotEvent'] == 'doCollect') 
            {                
                // *** added & changed by pine ***
                $Balance = $slotSettings->GetBalance();
                $slotSettings->SetGameData($slotSettings->slotId . 'FSStartBalance', $Balance);
                // *** - ***

                $objRes = [
                    'balance' => $BALANCE,
                    'index' => $slotEvent['index'],
                    'balance_cash' => $BALANCE,
                    'balance_bonus' => '0.00',
                    'na' => 's',
                    'stime' => floor(microtime(true) * 1000),
                    'sver' => '5',
                    'counter' => ((int)$slotEvent['counter'] + 1),
                ];
            }
            else if( $slotEvent['slotEvent'] == 'doBonus' ) {       // Lucky Treasure spin
                /* 럭키스핀 시작밸런스 유지 */
                $BALANCE = $LASTSPIN->balance;
                
                $respinCount = $LASTSPIN->rsb_c ?? 0;
                $retriggerCount = $LASTSPIN->rsb_rt ?? 0;
                $lastMultiplier = $LASTSPIN->rsb_mu ?? 0;

                $lastSymbols = $LASTSPIN->s ?? '';
                $lastSymbolValues = $LASTSPIN->mo ?? '';
                $lastSymbolTypes = $LASTSPIN->mo_t ?? '';

                /* 리트리거에 의한 럭키스핀인가 */
                $isRetriggered = false;
                $isBonusEnded = false;

                /* 리트리거에 의한 새 럭키스핀 */
                if ($respinCount >= 3 && $retriggerCount >= 1) {
                    /* 리트리거 리셋 */
                    $retriggerCount = 0;
                    /* 리스핀 리셋 */
                    $respinCount = 0;
                    /*  */
                    $lastMultiplier = 0;
                    /*  */
                    $lastSymbols = $LASTSPIN->start_with->s;
                    $lastSymbolValues = $LASTSPIN->start_with->mo;
                    $lastSymbolTypes = $LASTSPIN->start_with->mo_t;

                    $isRetriggered = true;
                }

                $lastSymbols = explode(",", $lastSymbols);
                $lastSymbolValues = explode(",", $lastSymbolValues);
                $lastSymbolTypes = explode(",", $lastSymbolTypes);

                /* 이전 릴셋 정리, 멀티플라이어, 리트리거 심볼 삭제 */
                $lastReelSet = $slotSettings->ClearReelSet($lastSymbols, $lastSymbolValues, $lastSymbolTypes);

                /* 응답 빌드 */
                $winMoney = 0;
                $bet = $LASTSPIN->c ?? $LASTSPIN->start_with->c;

                $objRes = [
                    'rsb_s' => $LASTSPIN->rsb_s ?? null,
                    'rsb_rt' => $retriggerCount,
                    'rsb_m' => $LASTSPIN->rsb_m ?? null,
                    'balance' => $BALANCE,
                    'rsb_c' => $respinCount,
                    'mo' => implode(",", $lastReelSet['values']),
                    'mo_t' => implode(",", $lastReelSet['types']),
                    'index' => $slotEvent['index'],
                    'balance_cash' => $BALANCE,
                    'balance_bonus' => 0,
                    'na' => 'b',
                    'stime' => floor(microtime(true) * 1000),
                    'sver' => 5,
                    'counter' => ((int)$slotEvent['counter'] + 1),
                    'rsb_mu' => $lastMultiplier,
                    's' => implode(",", $lastReelSet['symbols']),
                    'e_aw' => $LASTSPIN->e_aw ?? null,
                ];

                // *** added by pine ***
                $isState = false;
                // *** - ***
                /* 리트리거에 의한 새 럭키스핀 */
                if ($isRetriggered) {
                    $winMoney = $slotSettings->SumMoneySymbols($lastReelSet['values'], $lastReelSet['types'], $lastMultiplier, false) * $bet;
                }
                else {
                    /* 럭키스핀 최대심볼갯수 */
                    $maxMoneySymbolsCount = $slotSettings->GetGameData($slotSettings->slotId . 'LSMaxSymbols');

                    /* 스핀결과 결정 */
                    $spinSetting = $slotSettings->GetLuckySpinSetting($lastReelSet, $maxMoneySymbolsCount, $respinCount);

                    /* 새 머니심볼 추가 */
                    $resetRespin = false;
                    $isGenerated = false;
                    if ($spinSetting === true) {
                        $flattenReelSet = $slotSettings->GenerateMoneySymbols($lastReelSet, $respinCount, $retriggerCount);

                        /* 뱅크머니 체크 */
                        $_obf_currentbank = $slotSettings->GetBank('bonus');

                        /* 당첨금, 현재 당첨금 - 이전 당첨금 = winMoney  */
                        $lastTotalWin = $slotSettings->SumMoneySymbols($lastReelSet['values'], $lastReelSet['types'], $lastMultiplier, false);
                        $curTotalWin = $slotSettings->SumMoneySymbols($flattenReelSet['values'], $flattenReelSet['types'], $lastMultiplier, true);
                    
                        $winMoney = ($curTotalWin - $lastTotalWin) * $bet;

                        if ($winMoney > $_obf_currentbank) {
                        }
                        else {
                            for ($i=0; $i < $flattenReelSet['count']; $i++) { 
                                $newMoneyType = $flattenReelSet['types'][$flattenReelSet['pos'][$i]];
                            
                                /* 생성된 심볼이 리트리거인 경우 */
                                if ($newMoneyType == 'rt') {
                                    $objRes['rsb_rt'] = 1;
                                }
                                /* 생성된 심볼이 멀티플라이어인 경우 */
                                else if ($newMoneyType == 'm') {
                                    $objRes['rsb_mu'] = $LASTSPIN->rsb_mu + $flattenReelSet['values'][$flattenReelSet['pos'][$i]];
                                }
                                else {
                                    /* 잭팟, 일반심볼이 있을때에만 당첨 */
                                    $resetRespin = true;
                                }
                            }

                            $isGenerated = true;
                        }
                    }
                    else {
                    }

                    $respinCount = $resetRespin ? 0 : $respinCount + 1;
                    $objRes['rsb_c'] = $respinCount;

                    if ($isGenerated) {
                        $objRes['mo'] = implode(",", $flattenReelSet['values']);
                        $objRes['mo_t'] = implode(",", $flattenReelSet['types']);
                        $objRes['s'] = implode(",", $flattenReelSet['symbols']);
                    }

                    /* 더이상 리스핀 없음, 럭키스핀 완료 */
                    if ($respinCount >= 3) {
                        $tw = $slotSettings->SumMoneySymbols($lastReelSet['values'], $lastReelSet['types'], $lastMultiplier, true) * $bet;


                        $objRes['rw'] = $tw;
                         // *** - ***

                        $objRes['na'] = 'cb';
                        $objRes['is'] = $LASTSPIN->start_with->s ?? null;

                        if ($retriggerCount == 1) {
                            $objRes['na'] = 'b';
                        }else{                            
                            // *** added by pine ***
                            $isState = true;
                            // *** - ***
                        }

                        $isBonusEnded = true;
                    }
                }
                /* 밸런스 업뎃 */
                if ($winMoney > 0) {
                    $slotSettings->SetBalance($winMoney);
                    $slotSettings->SetBank('luckyspin', -1 * $winMoney);
                    
                    // *** added & changed by pine ***
                    $bonusWin = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') ?? 0;
                    $bonusWin = $bonusWin + $winMoney;
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $bonusWin);
                }
                if ($isBonusEnded == true){                        
                    $objRes['tw'] = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') ?? 0;
                }
                // *** - ***

                /* 럭키스핀 시작응답을 유지, doInit에서 이용 */
                $isFirstDoBonus = isset($LASTSPIN->bw);     // 럭키스핀 첫스핀

                $_GameLog = json_encode(array_merge($objRes, ['start_with' => $isFirstDoBonus ? $LASTSPIN : $LASTSPIN->start_with]));
                
                // *** added & changed by pine ***
                $slotSettings->SaveLogReport($_GameLog, $bet * 40, 40, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') ?? 0, $slotEvent['slotEvent'], $isState);
                // *** - ***
            }
            else if( $slotEvent['slotEvent'] == 'doCollectBonus') {     // Luck Treasure spin collect
                     
                // *** added & changed by pine ***
                $Balance = $slotSettings->GetBalance();
                $slotSettings->SetGameData($slotSettings->slotId . 'FSStartBalance', $Balance);
                // *** - ***

                $objRes = [
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
            else if( $slotEvent['slotEvent'] == 'update' ) 
            {
                /* 프리스핀, 럭키스핀일 경우 스핀 첫밸런스 로드 */

                // *** added & changed by pine ***
                // if (isset($LASTSPIN->fsmax) || isset($LASTSPIN->rsb_s)) {
                //     $BALANCE = $LASTSPIN->balance;
                // }
                $BALANCE = $slotSettings->GetGameData($slotSettings->slotId . 'FSStartBalance') ?? $slotSettings->GetBalance();
                // *** - ***

                $objRes = [
                    'balance_bonus' => '0.00',
                    'balance' => $BALANCE,
                    'balance_cash' => $BALANCE,
                    'stime' => floor(microtime(true) * 1000),
                ];
            }
            // *** added by pine ***
            $response = $this->toResponse($objRes);
            if($slotEvent['action'] == 'doSpin' || $slotEvent['action'] == 'doCollect' || $slotEvent['action'] == 'doCollectBonus' || $slotEvent['action'] == 'doBonus'){
                $this->saveGameLog($slotEvent, $response, $slotSettings->GetGameData($slotSettings->slotId . 'RoundID') ?? 0, $slotSettings);
            }
            // *** - ***
            $slotSettings->SaveGameData();
            \DB::commit();
            return $response;
        }

        // *** added by pine ***
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
        // *** - ***

        public function checkWinLines($flattenSymbols, $PayLines, $PayTable, $bet, $spinType) {
            $WILD = 2;
            $fsWILD1 = 15;
            $fsWILD2 = 16;

            $REELCOUNT = 5;

            $winLines = [];

            foreach ($PayLines as $payLineId => $payLine) {
                $sameSymbolsCount = 0;

                /* 라인 검사 */
                foreach ($payLine as $idx => $pos) {
                    /* 첫심볼은 등록 */
                    if ($idx == 0) {
                        $firstSymbolPos = $payLine[0];
                        $firstSymbol = $flattenSymbols[$firstSymbolPos];
        
                        /* 머니심볼이면 break */
                        if ($firstSymbol == 13) {
                            break;
                        }

                        $sameSymbolsCount = 1;
                        continue;
                    }

                    /* 같은 심볼, WILD인 경우 혹 프리스핀에서 15,16번 ( WILD로 본다 )심볼인 경우 */
                    if ($flattenSymbols[$pos] == $firstSymbol || $flattenSymbols[$pos] == $WILD) {
                        $sameSymbolsCount += 1;
                    }
                    else if ($spinType == 'freespin' && ($flattenSymbols[$pos] == $fsWILD1 || $flattenSymbols[$pos] == $fsWILD2)) {
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
                        'Money' => $lineMoney * $bet,
                        'Positions' => array_slice($payLine, 0, $sameSymbolsCount)
                    ]);
                }
            }
            
            return $winLines;
        }

        public function toResponse($obj) {
            $response = '';
            foreach ($obj as $key => $value) {
                if ($value !== null) {
                    $response = "{$response}&{$key}={$value}";
                }
            }

            return trim($response, "&");
        }
    }
}

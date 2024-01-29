<?php 
namespace VanguardLTE\Games\SGTheKoiGateHBN
{
    use Carbon\Carbon;

    class Server
    {
        public function get($request, $game, $userId) // changed by game developer
        {
            $response = '';
            \DB::beginTransaction();

            if( $userId == null ) 
            {
            	$response = 'unlogged';
                exit( $response );
            }
            $user = \VanguardLTE\User::lockForUpdate()->find($userId);

            $slotEvent = json_decode(request()->getContent(), false);
            $ACTION = $slotEvent->game->action;

            $credits = $userId == 1 ? $ACTION === 'init' ? 5000 : $user->balance : null;

            $slotSettings = new SlotSettings($game, $userId, $credits);
            
            switch ($ACTION) {
                case 'init':
                    $objRes = $this->init($slotEvent, $slotSettings);
                    break;
                
                case 'game':
                    $objRes = $this->game($slotEvent, $slotSettings);
                    break;

                case 'balance':
                    $objRes = $this->balance($slotEvent, $slotSettings);
                    break;
    
                default:
                    break;
            }

            $slotSettings->SaveGameData();
            \DB::commit();

            return $this->toResponse($objRes);
        }

        public function init($slotEvent, $slotSettings) {
            $BALANCE = $slotSettings->GetBalance();
            $LASTSPIN = $slotSettings->GetHistory();

            $objRes = [
                'header' => [
                    'st' => Carbon::now()->format('Y-m-d\TH:i:s.u'),
                    'timing' => '151',
                    'player' => [
                        'brandid' => $slotEvent->header->player->brandid,
                        'gamesymbol' => '₩',
                        'curexp' => 0,
                        'displaybalance' => $BALANCE,
                        'realbalance' => $BALANCE,
                        'hasbonus' => false,
                        'ssotoken' => $slotEvent->header->player->ssotoken
                    ]
                ],
                'game' => [
                    'action' => "init",
                    'gameid' => "00000000-0000-0000-0000-000000000000",
                    'friendlyid' => 0,
                    'sessionid' => $slotEvent->game->sessionid,
                    'brandgameid' => $slotEvent->game->brandgameid,
                    'init' => [
                        'minstake' => 36.0,
                        'maxstake' => 90000.0,
                        'stakeincrement' => "2|5|10|25|50|100|250|500",
                        'defaultstake' => 100.0,
                        'coinsincrement' => "1|2|5|7|10",
                        'configid' => "8e7c049e-a38b-eb11-b566-00155db545d0",
                        'maxpaylimit' => 200000000.0,
                        'maxpaylimit_round' => true        
                    ],
                    'apiversion' => "5.1.8593.428",
                    'gameversion' => "5.1.1331.93",
                    'gamehash' => "0285e92ae36cdf9689bfcd133bc2edc6105cdd92",
                    'rngversion' => "5.1.4478.308",
                    'rnghash' => "eead3c403b3721bb62da82546e60c5fb52174559",
                    'jpversion' => "5.1.4326.325",
                    'jphash' => "4ccbcc6c2e2607c4c0b3bf17ce5eabe17a98ec01"
                ],
                'portmessage' => [
                    'reelid' => 330
                ],
                'grid' => $slotEvent->grid
            ];

            /* 이전 프리스핀 결과 로드 */
            $rsConfig = $slotSettings->GetGameData($slotSettings->slotId . 'RSConfig') ?? [];
            if( $LASTSPIN !== null && $LASTSPIN->portmessage->nextgamestate == 'freegame' && count($rsConfig) > 0) {
                $objRes['portmessage']['gssid'] = $LASTSPIN->portmessage->gssid;

                $resumegames = [];
                array_push($resumegames, [
                    'betcoin' => $rsConfig['bet'],
                    'betlevel' => $rsConfig['betLevel'],
                    'betlines' => $rsConfig['lines'],
                    'currfreegame' => $LASTSPIN->portmessage->currentfreegame ?? 0,
                    'friendlygameid' => $LASTSPIN->game->friendlyid,
                    'gameinstanceid' => '',
                    'gamemode' => 'freegame',
                    'numfreegames' => $LASTSPIN->portmessage->numfreegames ?? 0,
                    'totalwincash' => $LASTSPIN->portmessage->totalwincash,
                    'virtualreels' => $LASTSPIN->portmessage->virtualreels,
                ]);
                $objRes['portmessage']['resumegames'] = $resumegames;
            }

            return $objRes;
        }

        public function game($slotEvent, $slotSettings) {
            $S_SCATTER = 1;
            $REELCOUNT = 5;

            $portmessage = json_decode($slotEvent->portmessage);
            $gameMode = $portmessage->gameMode;

            /* 이전 스핀 */
            $LASTSPIN = $slotSettings->GetHistory();

            /* 남은 프리스핀이 있을 경우 */
            $rsMax = $slotSettings->GetGameData($slotSettings->slotId . 'RSCount') ?? 0;
            $rs = $slotSettings->GetGameData($slotSettings->slotId . 'RSCurrent') ?? 0;
            $rsConfig = $slotSettings->GetGameData($slotSettings->slotId . 'RSConfig') ?? [];

            if ($gameMode == 'base') {
                /* 베팅 * 베팅레벨 */
                $bet = $portmessage->coinValue * $portmessage->betLevel; 
                $lines = $portmessage->numLines;
            }
            else if ($gameMode == 'free') {
                $bet = $rsConfig['bet'] * 2;        // 프리스핀시 2배당 적용
                $lines = $rsConfig['lines'];
            }

            /* 스핀결과 결정 */
            $_spinSettings = $slotSettings->GetSpinSettings($gameMode, $bet * $lines, $lines);

            $winType = $_spinSettings[0];                   // 보상방식
            $_winAvaliableMoney = $_spinSettings[1];        // 당첨금 한도

            /* Balance 업데이트, 베팅금 수급 */
            if ($gameMode == 'free') {
                $allBet = 0;
            }
            else {
                $allBet = $bet * $lines;
                $slotSettings->SetBalance(-1 * $allBet, $gameMode);

                $bankMoney = $allBet / 100 * $slotSettings->GetPercent();
                $slotSettings->SetBank($gameMode, $bankMoney);
            }

            $BALANCE = $slotSettings->GetBalance();

            /* 생성할 SCATTER 심볼 갯수 */
            if ($gameMode == 'free' && $rs >= $rsMax - 1) {
                /* 프리스핀 완료스핀이면 */
                $proposedScatterCount = 0;
            }
            else {
                $proposedScatterCount = $slotSettings->GenerateScatterCount($winType, $gameMode);
            }

            /* 생성할 WILD 심볼 갯수 */
            $propsedWildCount = $slotSettings->GenerateWildCount($winType, $gameMode);

            /* 릴배치표 생성, 2천번 시행 */
            /*************************************************** */
            $overtry = false;
            for ($try=0; $try < 2000; $try++) { 
                $winMoney = 0;

                /* 릴배치표 생성 */
                if ($overtry) {
                    $reels = $slotSettings->GetReelStrips($winType, $gameMode, 0, 0);
                }
                else {
                    $reels = $slotSettings->GetReelStrips($winType, $gameMode, $proposedScatterCount, $propsedWildCount);
                }

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

                    $proposedScatterCount = 0;
                    $propsedWildCount = 0;
                }

                /* 릴셋 유효성 검사 */
                if ($slotSettings->isValidReels($reels) == false) {
                    continue;
                }
                
                if ($winType == 'none') {
                    if (count($winLines) == 0) {
                        break;
                    }
                }
                else if ($winType == 'win' && count($winLines) > 0) {
                    /* 스핀 당첨금 */
                    $winMoney = array_reduce($winLines, function($carry, $winLine) {
                        $carry += $winLine['wincash']; 
                        return $carry;
                    }, 0);

                    if ($winMoney >= $_winAvaliableMoney) {
                        continue;
                    }
                    if ($slotSettings->happyhouruser && $allBet > $winMoney)
                    {
                        continue;
                    }

                    break;
                }
                else if ($winType == 'bonus') {
                    /* 스캐터심볼이나 당첨이 없으면 스킵 */
                    if (count($reels['scatterSymbols']) == 0 || count($winLines) == 0) {
                        continue;
                    }

                    /* 스핀 당첨금 */
                    $winMoney = array_reduce($winLines, function($carry, $winLine) {
                        $carry += $winLine['wincash']; 
                        return $carry;
                    }, 0);

                    if ($winMoney >= $_winAvaliableMoney) {
                            continue;
                    }
                    if ($slotSettings->happyhouruser && $allBet > $winMoney)
                    {
                        continue;
                    }

                    break;
                }
            }

            $responseReels = [];
            for ($reelId=0; $reelId < $REELCOUNT; $reelId++) { 
                $symbols = array_map(function($symbol) { 
                    return [
                        'symbolid' => $symbol
                    ];
                }, $reels['symbols'][$reelId]);
                array_push($responseReels, $symbols);
            }
            
            /* 스캐터릴 확장, 스캐터심볼이 있고 당첨라인이 있을경우에만 */
            $expandingWilds = [];
            if (count($winLines) > 0 && count($reels['scatterSymbols']) > 0) {
                foreach($reels['scatterSymbols'] as $scatterSymbol) {
                    $reelindex = $scatterSymbol % $REELCOUNT;
                    $symbolindex = intdiv($scatterSymbol, $REELCOUNT);
                    array_push($expandingWilds, [
                        'symbolid' => $S_SCATTER, 'reelindex' => $reelindex, 'symbolindex' => $symbolindex
                    ]);
                }
            }
            
            /* 스핀응답 빌드 */
            $objRes = [
                'header' => [
                    'st' => Carbon::now()->format('Y-m-d\TH:i:s.u'),
                    'timing' => '222',
                    'player' => [
                        'brandid' => $slotEvent->header->player->brandid,
                        'gamesymbol' => '₩',
                        'curexp' => 0,
                        'displaybalance' => $BALANCE,
                        'realbalance' => $BALANCE,
                        'hasbonus' => false,
                        'ssotoken' => $slotEvent->header->player->ssotoken
                    ]
                ],
                'game' => [
                    'action' => 'game',
                    'gameid' => '',
                    'friendlyid' => 0,
                    'sessionid' => $slotEvent->game->sessionid,
                    'brandgameid' => $slotEvent->game->brandgameid,
                ],
                'portmessage' => [
                    'isgamedone' => true,
                    'wincash' => $winMoney,
                    'totalwinfreegames' => 0,
                    'nextgamestate' => 'main',
                    'nextgamestatetype' => 'spin',
                    'reels' => $responseReels,
                    'virtualreels' => $reels['extendedSymbols'],
                    'linewinscash' => 0,
                    'scatterwinscash' => 0,
                    'totalwincash' => $winMoney,
                    'expandingwilds' => $expandingWilds
                ],
                'grid' => $slotEvent->grid
            ];

            /* 윈라인 응답 */
            if($winMoney > 0) {
                foreach ($winLines as $winLine) {
                    foreach ($winLine['winningwindows'] as $winningwindow) {
                        $reelindex = $winningwindow['reelindex'];
                        $symbolindex = $winningwindow['symbolindex'];
                        $responseReels[$reelindex][$symbolindex] = [
                            'symbolid' => $responseReels[$reelindex][$symbolindex]['symbolid'],
                            'iswinner' => true,
                            'wintypes' => ['payline']
                        ];    
                    }
                }

                $objRes['portmessage']['reels'] = $responseReels;

                $objRes['portmessage']['linewins'] = $winLines;
                $objRes['portmessage']['wincash'] = $winMoney;
                $objRes['portmessage']['gssid'] = '';

                /* 밸런스 업데이트 */
                $objRes['header']['player']['displaybalance'] += $winMoney;
                $objRes['header']['player']['realbalance'] += $winMoney;
            }

            /* 프리스핀중 */
            if ($gameMode == 'free') {
                $rs += 1;
                $objRes['portmessage']['winfreegames'] = 0;
                $objRes['portmessage']['currentfreegame'] = $rs;
                $objRes['portmessage']['numfreegames'] = 0;
                $objRes['portmessage']['featureretriggered'] = false;
                $objRes['portmessage']['totalwincash'] = $LASTSPIN->portmessage->totalwincash + $winMoney;

                /* 프리스핀 완료 */
                if ($rs >= $rsMax) {
                    $objRes['portmessage']['isgamedone'] = true;
                    $objRes['portmessage']['nextgamestate'] = 'main';

                    /* 프리스핀 셋팅 리셋 */
                    $slotSettings->SetGameData($slotSettings->slotId . 'RSCount', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RSCurrent', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'RSConfig', []);
                }
                else {
                    $objRes['portmessage']['isgamedone'] = false;
                    $objRes['portmessage']['nextgamestate'] = 'freegame';

                    /* 프리스핀갯수 증가 */
                    $slotSettings->SetGameData($slotSettings->slotId . 'RSCurrent', $rs);
                }
            }
            /* 보너스 당첨 */
            else if ($winType == 'bonus') {
                $objRes['portmessage']['featuretriggered'] = true;
                $objRes['portmessage']['isgamedone'] = false;
                $objRes['portmessage']['nextgamestate'] = 'freegame';

                /* 프리스핀 셋팅 결정 */
                $rsMax = $slotSettings->GenerateRespinCount();
                $slotSettings->SetGameData($slotSettings->slotId . 'RSCount', $rsMax);
                $slotSettings->SetGameData($slotSettings->slotId . 'RSCurrent', 0);
                $slotSettings->SetGameData($slotSettings->slotId . 'RSConfig', ['bet' => $bet, 'lines' => $lines, 'betLevel' => $portmessage->betLevel]);
            }

            /* 밸런스 업데이트 */
            if($winMoney > 0) 
            {
                $slotSettings->SetBalance($winMoney);
                $slotSettings->SetBank($gameMode ?? '', -1 * $winMoney);
            }

            $_GameLog = json_encode($objRes);
            $slotSettings->SaveLogReport($_GameLog, $allBet, $lines, $winMoney, $gameMode);
            
            return $objRes;
        }

        public function balance($slotEvent, $slotSettings) {
            $BALANCE = $slotSettings->GetBalance();

            $objRes = [
                'header' => [
                    'st' => Carbon::now()->format('Y-m-d\TH:i:s.u'),
                    'timing' => '151',
                    'player' => [
                        'brandid' => $slotEvent->header->player->brandid,
                        'gamesymbol' => '₩',
                        'curexp' => 0,
                        'displaybalance' => $BALANCE,
                        'realbalance' => $BALANCE,
                        'hasbonus' => false,
                        'ssotoken' => $slotEvent->header->player->ssotoken
                    ]
                ],
            ];

            return $objRes;
        }

        public function checkWinLines($reels, $slotSettings, $bet) {
            $S_SCATTER = 1;
            $S_WILD = 2;
            $REELCOUNT = 5;
            $SYMBOLCOUNT = 3;
            $PayLines = $slotSettings->PayLines;
            $PayTable = $slotSettings->PayTable;

            $flatSymbols = $reels['flatSymbols'];
            $scatterSymbols = $reels['scatterSymbols'];

            /* 스캐터릴 확장 */
            foreach ($scatterSymbols as $scatterPos) {
                $reelindex = $scatterPos % $REELCOUNT;

                for ($i=0; $i < $SYMBOLCOUNT; $i++) { 
                    $pos = $i * $REELCOUNT + $reelindex;
                    $flatSymbols[$pos] = $S_SCATTER;    
                }
            }

            /* 윈라인 체크 */
            $winLines = [];
            foreach ($PayLines as $payLine) {
                $sameSymbolsCount = 0;

                $payLineId = key($payLine);
                $payLineSymbols = $payLine[$payLineId];

                /* 라인 검사 */
                foreach ($payLineSymbols as $idx => $pos) {
                    /* 첫심볼은 등록 */
                    if ($idx == 0) {
                        $firstSymbolPos = $payLineSymbols[0];
                        $symbolid = $flatSymbols[$firstSymbolPos];
        
                        $sameSymbolsCount = 1;
                        continue;
                    }

                    /* 같은 심볼, WILD인 경우 */
                    if ($symbolid == $S_WILD) {
                        $sameSymbolsCount += 1;
                        $symbolid = $flatSymbols[$pos];
                    }
                    else if ($flatSymbols[$pos] == $symbolid || $flatSymbols[$pos] == $S_WILD || $flatSymbols[$pos] == $S_SCATTER) {
                        $sameSymbolsCount += 1;
                    }
                    else {
                        break;
                    }
                }

                /* 페이테이블 검사 */
                $lineMoney = $PayTable[$symbolid][$REELCOUNT - $sameSymbolsCount];

                if ($lineMoney == 0) {
                    continue;
                }

                $winningwindows = [];
                for ($i=0; $i < $sameSymbolsCount; $i++) { 
                    $pos = $payLineSymbols[$i];
                    $reelindex = $pos % $REELCOUNT;
                    $symbolindex = intdiv($pos, $REELCOUNT);

                    array_push($winningwindows, [
                        'reelindex' => $reelindex,
                        'symbolindex' => $symbolindex
                    ]);
                }
                
                array_push($winLines, [
                    'symbolid' => $symbolid,
                    'paylineindex' => $payLineId,
                    'wincash' => $lineMoney * $bet,
                    'winningwindows' => $winningwindows,
                    'multiplier' => 1
                ]);
            }
            
            return $winLines;
        }

        public function toResponse($obj) {
            return json_encode($obj);
        }
    }
}

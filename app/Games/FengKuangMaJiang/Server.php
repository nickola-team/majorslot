<?php 
namespace VanguardLTE\Games\FengKuangMaJiang
{
    include('CheckReels.php');
    class Server
    {
        public function get($request, $game)
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
            $userId = \Auth::id();
            if( $userId == null ) 
            {
                $response = '{"responseEvent":"error","responseType":"","serverResponse":"invalid login"}';
                exit( $response );
            }
            $slotSettings = new SlotSettings($game, $userId);
            $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822 = json_decode(trim(file_get_contents('php://input')), true);
            $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] = $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['request'];
            if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'update' ) 
            {
                $response = '{"responseEvent":"error","responseType":"' . $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] . '","serverResponse":"' . $slotSettings->GetBalance() . '"}';
                exit( $response );
            }
            if( $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') <= $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0 && $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] != 'init' ) 
            {
                $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] = 'freespin';
            }
            if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'spin' || $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'freespin' || $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'respin' ) 
            {
                if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['lines'] <= 0 || $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['bet'] <= 0 ) 
                {
                    $response = '{"responseEvent":"error","responseType":"' . $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] . '","serverResponse":"invalid bet state"}';
                    exit( $response );
                }
                if( $slotSettings->GetBalance() < ($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['bet'] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['lines']) ) 
                {
                    $response = '{"responseEvent":"error","responseType":"' . $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] . '","serverResponse":"invalid balance"}';
                    exit( $response );
                }
                if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') < $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'freespin' ) 
                {
                    $response = '{"responseEvent":"error","responseType":"' . $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] . '","serverResponse":"invalid bonus state"}';
                    exit( $response );
                }
            }
            else if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'slotGamble' && $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') <= 0 ) 
            {
                $response = '{"responseEvent":"error","responseType":"' . $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] . '","serverResponse":"invalid gamble state"}';
                exit( $response );
            }
            if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'init' ) 
            {
                $slotSettings->SetGameData('FengKuangMaJiangBonusWin', 0);
                $slotSettings->SetGameData('FengKuangMaJiangFreeGames', 0);
                $slotSettings->SetGameData('FengKuangMaJiangCurrentFreeGame', 0);
                $slotSettings->SetGameData('FengKuangMaJiangTotalWin', 0);
                $slotSettings->SetGameData('FengKuangMaJiangStartBonusWin', 0);
                $slotSettings->SetGameData('FengKuangMaJiangFreeBalance', 0);
                $slotSettings->SetGameData('FengKuangMaJiangIsReSpin', 0);
                $slotSettings->SetGameData('FengKuangMaJiangReSpinCount', 0);
                $lastEvent = $slotSettings->GetHistory();
                if( $lastEvent != 'NULL' ) 
                {
                    if( isset($lastEvent->serverResponse->bonusWin) ) 
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    }
                    else
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->totalWin);
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'StartBonusWin', $lastEvent->serverResponse->StartBonusWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $lastEvent->serverResponse->Balance);
                    $lastEvent->serverResponse->reelsSymbols->reel1 = (array)$lastEvent->serverResponse->reelsSymbols->reel1;
                    $lastEvent->serverResponse->reelsSymbols->reel2 = (array)$lastEvent->serverResponse->reelsSymbols->reel2;
                    $lastEvent->serverResponse->reelsSymbols->reel3 = (array)$lastEvent->serverResponse->reelsSymbols->reel3;
                    $lastEvent->serverResponse->reelsSymbols->reel4 = (array)$lastEvent->serverResponse->reelsSymbols->reel4;
                    $lastEvent->serverResponse->reelsSymbols->reel5 = (array)$lastEvent->serverResponse->reelsSymbols->reel5;
                    $_obf_0D1C1D210B1F1B33075C310724290F3C132E1405255B01 = implode(',', $lastEvent->serverResponse->reelsSymbols->rp);
                    $_obf_0D0E28151E1B1A1A2F130B1D37332436301F2822370311 = '[' . $lastEvent->serverResponse->reelsSymbols->reel1[0] . ',' . $lastEvent->serverResponse->reelsSymbols->reel2[0] . ',' . $lastEvent->serverResponse->reelsSymbols->reel3[0] . ',' . $lastEvent->serverResponse->reelsSymbols->reel4[0] . ',' . $lastEvent->serverResponse->reelsSymbols->reel5[0] . ']';
                    $_obf_0D0E28151E1B1A1A2F130B1D37332436301F2822370311 .= (',[' . $lastEvent->serverResponse->reelsSymbols->reel1[1] . ',' . $lastEvent->serverResponse->reelsSymbols->reel2[1] . ',' . $lastEvent->serverResponse->reelsSymbols->reel3[1] . ',' . $lastEvent->serverResponse->reelsSymbols->reel4[1] . ',' . $lastEvent->serverResponse->reelsSymbols->reel5[1] . ']');
                    $_obf_0D0E28151E1B1A1A2F130B1D37332436301F2822370311 .= (',[' . $lastEvent->serverResponse->reelsSymbols->reel1[2] . ',' . $lastEvent->serverResponse->reelsSymbols->reel2[2] . ',' . $lastEvent->serverResponse->reelsSymbols->reel3[2] . ',' . $lastEvent->serverResponse->reelsSymbols->reel4[2] . ',' . $lastEvent->serverResponse->reelsSymbols->reel5[2] . ']');
                    $bet = $lastEvent->serverResponse->bet;
                    $lastEvent->lastResponse->result->state->collapsingWin = 0;
                    $lastEvent->lastResponse->result->state->collapsingCount = 0;
                    $lastEvent->lastResponse->result->state->isReSpin = false;
                    $lastEvent->lastResponse->result->rewards = [];
                    $lastEvent->lastResponse->result->totalWin = 0;
                    $lastEvent->lastResponse->result->roundEnded = true;
                    $_obf_0D1F1D40101E063903251B0705382E3D24372E02282732 = ',"previousResult":' . json_encode($lastEvent->lastResponse->result);
                }
                else
                {
                    $_obf_0D1C1D210B1F1B33075C310724290F3C132E1405255B01 = implode(',', [
                        rand(0, count($slotSettings->reelStrip1) - 4), 
                        rand(0, count($slotSettings->reelStrip2) - 4), 
                        rand(0, count($slotSettings->reelStrip3) - 4), 
                        rand(0, count($slotSettings->reelStrip4) - 4), 
                        rand(0, count($slotSettings->reelStrip5) - 4)
                    ]);
                    $_obf_0D1427193624111E0F3F19161D3E05313F281B2B071E22 = rand(0, count($slotSettings->reelStrip1) - 4);
                    $_obf_0D40170234191D12013C08112D373F23141F21271C4022 = rand(0, count($slotSettings->reelStrip2) - 4);
                    $_obf_0D302D052E271A03052B5C2E3032091F292B160D0A5C32 = rand(0, count($slotSettings->reelStrip3) - 4);
                    $_obf_0D15172326073C2F213E5C1B5C3201270A032D23382322 = rand(0, count($slotSettings->reelStrip4) - 4);
                    $_obf_0D3D31062639082336285C2905020F2B063022132E0401 = rand(0, count($slotSettings->reelStrip5) - 4);
                    $_obf_0D2F0417270C151F0A092517350A5C24084024342E3711 = $slotSettings->reelStrip1[$_obf_0D1427193624111E0F3F19161D3E05313F281B2B071E22];
                    $_obf_0D1C350831253C0E053E0A0C14140F0B350816311B3701 = $slotSettings->reelStrip2[$_obf_0D40170234191D12013C08112D373F23141F21271C4022];
                    $_obf_0D3711263310365C0639400E142F04143C011B5B240322 = $slotSettings->reelStrip3[$_obf_0D302D052E271A03052B5C2E3032091F292B160D0A5C32];
                    $_obf_0D2A1A1E150D142B17291236053731323D0109193B1211 = $slotSettings->reelStrip4[$_obf_0D15172326073C2F213E5C1B5C3201270A032D23382322];
                    $_obf_0D103336361B0A5B3F10403C3E2D055C2E190F16351F11 = $slotSettings->reelStrip5[$_obf_0D3D31062639082336285C2905020F2B063022132E0401];
                    $_obf_0D0E28151E1B1A1A2F130B1D37332436301F2822370311 = '[' . $_obf_0D2F0417270C151F0A092517350A5C24084024342E3711 . ',' . $_obf_0D1C350831253C0E053E0A0C14140F0B350816311B3701 . ',' . $_obf_0D3711263310365C0639400E142F04143C011B5B240322 . ',' . $_obf_0D2A1A1E150D142B17291236053731323D0109193B1211 . ',' . $_obf_0D103336361B0A5B3F10403C3E2D055C2E190F16351F11 . ']';
                    $_obf_0D2F0417270C151F0A092517350A5C24084024342E3711 = $slotSettings->reelStrip1[$_obf_0D1427193624111E0F3F19161D3E05313F281B2B071E22 + 1];
                    $_obf_0D1C350831253C0E053E0A0C14140F0B350816311B3701 = $slotSettings->reelStrip2[$_obf_0D40170234191D12013C08112D373F23141F21271C4022 + 1];
                    $_obf_0D3711263310365C0639400E142F04143C011B5B240322 = $slotSettings->reelStrip3[$_obf_0D302D052E271A03052B5C2E3032091F292B160D0A5C32 + 1];
                    $_obf_0D2A1A1E150D142B17291236053731323D0109193B1211 = $slotSettings->reelStrip4[$_obf_0D15172326073C2F213E5C1B5C3201270A032D23382322 + 1];
                    $_obf_0D103336361B0A5B3F10403C3E2D055C2E190F16351F11 = $slotSettings->reelStrip5[$_obf_0D3D31062639082336285C2905020F2B063022132E0401 + 1];
                    $_obf_0D0E28151E1B1A1A2F130B1D37332436301F2822370311 .= (',[' . $_obf_0D2F0417270C151F0A092517350A5C24084024342E3711 . ',' . $_obf_0D1C350831253C0E053E0A0C14140F0B350816311B3701 . ',' . $_obf_0D3711263310365C0639400E142F04143C011B5B240322 . ',' . $_obf_0D2A1A1E150D142B17291236053731323D0109193B1211 . ',' . $_obf_0D103336361B0A5B3F10403C3E2D055C2E190F16351F11 . ']');
                    $_obf_0D2F0417270C151F0A092517350A5C24084024342E3711 = $slotSettings->reelStrip1[$_obf_0D1427193624111E0F3F19161D3E05313F281B2B071E22 + 2];
                    $_obf_0D1C350831253C0E053E0A0C14140F0B350816311B3701 = $slotSettings->reelStrip2[$_obf_0D40170234191D12013C08112D373F23141F21271C4022 + 2];
                    $_obf_0D3711263310365C0639400E142F04143C011B5B240322 = $slotSettings->reelStrip3[$_obf_0D302D052E271A03052B5C2E3032091F292B160D0A5C32 + 2];
                    $_obf_0D2A1A1E150D142B17291236053731323D0109193B1211 = $slotSettings->reelStrip4[$_obf_0D15172326073C2F213E5C1B5C3201270A032D23382322 + 2];
                    $_obf_0D103336361B0A5B3F10403C3E2D055C2E190F16351F11 = $slotSettings->reelStrip5[$_obf_0D3D31062639082336285C2905020F2B063022132E0401 + 2];
                    $_obf_0D0E28151E1B1A1A2F130B1D37332436301F2822370311 .= (',[' . $_obf_0D2F0417270C151F0A092517350A5C24084024342E3711 . ',' . $_obf_0D1C350831253C0E053E0A0C14140F0B350816311B3701 . ',' . $_obf_0D3711263310365C0639400E142F04143C011B5B240322 . ',' . $_obf_0D2A1A1E150D142B17291236053731323D0109193B1211 . ',' . $_obf_0D103336361B0A5B3F10403C3E2D055C2E190F16351F11 . ']');
                    $_obf_0D2F0417270C151F0A092517350A5C24084024342E3711 = $slotSettings->reelStrip1[$_obf_0D1427193624111E0F3F19161D3E05313F281B2B071E22 + 3];
                    $_obf_0D1C350831253C0E053E0A0C14140F0B350816311B3701 = $slotSettings->reelStrip2[$_obf_0D40170234191D12013C08112D373F23141F21271C4022 + 3];
                    $_obf_0D3711263310365C0639400E142F04143C011B5B240322 = $slotSettings->reelStrip3[$_obf_0D302D052E271A03052B5C2E3032091F292B160D0A5C32 + 3];
                    $_obf_0D2A1A1E150D142B17291236053731323D0109193B1211 = $slotSettings->reelStrip4[$_obf_0D15172326073C2F213E5C1B5C3201270A032D23382322 + 3];
                    $_obf_0D103336361B0A5B3F10403C3E2D055C2E190F16351F11 = $slotSettings->reelStrip5[$_obf_0D3D31062639082336285C2905020F2B063022132E0401 + 3];
                    $_obf_0D0E28151E1B1A1A2F130B1D37332436301F2822370311 .= (',[' . $_obf_0D2F0417270C151F0A092517350A5C24084024342E3711 . ',' . $_obf_0D1C350831253C0E053E0A0C14140F0B350816311B3701 . ',' . $_obf_0D3711263310365C0639400E142F04143C011B5B240322 . ',' . $_obf_0D2A1A1E150D142B17291236053731323D0109193B1211 . ',' . $_obf_0D103336361B0A5B3F10403C3E2D055C2E190F16351F11 . ']');
                    $bet = $slotSettings->Bet[0];
                    $_obf_0D1F1D40101E063903251B0705382E3D24372E02282732 = '';
                }
                $_obf_0D300C2F21350336261622142A322E0C270C0A1F2F0422 = json_encode($slotSettings);
                $Balance = $slotSettings->GetBalance();
                $lang = json_encode(\Lang::get('games.' . $game));
                $response = '{"gameSession":"","balance":{"currency":"' . $slotSettings->slotCurrency . '","amount":' . $Balance . ',"real":{"amount":' . $Balance . '},"bonus":{"amount":0}},"result":{"request":"init"' . $_obf_0D1F1D40101E063903251B0705382E3D24372E02282732 . ',"name":"Feng Kuang Ma Jiang","gameId":"sw_fkmj","settings":{"winMax":500000,"stakeAll":[' . implode(',', $slotSettings->Bet) . '],"stakeDef":' . $bet . ',"stakeMax":' . $slotSettings->Bet[count($slotSettings->Bet) - 1] . ',"stakeMin":' . $slotSettings->Bet[0] . ',"maxTotalStake":' . ($slotSettings->Bet[count($slotSettings->Bet) - 1] * 40) . ',"defaultCoin":1,"coins":[1],"currencyMultiplier":100},"slot":{"sets":{"main":{"reels":[[' . implode(',', $slotSettings->reelStrip1) . '],[' . implode(',', $slotSettings->reelStrip2) . '],[' . implode(',', $slotSettings->reelStrip3) . '],[' . implode(',', $slotSettings->reelStrip4) . '],[' . implode(',', $slotSettings->reelStrip5) . ']]},"freeSpins":{"reels":[[' . implode(',', $slotSettings->reelStripBonus1) . '],[' . implode(',', $slotSettings->reelStripBonus2) . '],[' . implode(',', $slotSettings->reelStripBonus3) . '],[' . implode(',', $slotSettings->reelStripBonus4) . '],[' . implode(',', $slotSettings->reelStripBonus5) . ']]}},"reels":{"set":"main","positions":[' . $_obf_0D1C1D210B1F1B33075C310724290F3C132E1405255B01 . '],"view":[' . $_obf_0D0E28151E1B1A1A2F130B1D37332436301F2822370311 . ']},"linesDefinition":{"fixedLinesCount":25},"paytable":{"stake":{"value":1,"multiplier":1,"payouts":[[0,0,100,250,5000],[0,0,75,150,300],[0,0,60,120,200],[0,0,30,80,150],[0,0,10,45,100],[0,0,10,35,90],[0,0,8,35,75],[0,0,6,25,60],[0,2,5,10,30],[0,0,0,0,0]]}},"lines":[[1,1,1,1,1],[0,0,0,0,0],[2,2,2,2,2],[0,1,2,1,0],[2,1,0,1,2],[0,0,1,2,2],[2,2,1,0,0],[1,0,1,2,1],[1,2,1,0,1],[1,0,0,0,0],[1,2,2,2,2],[0,1,1,1,1],[2,1,1,1,1],[0,1,0,1,0],[2,1,2,1,2],[1,1,0,1,1],[1,1,2,1,1],[0,0,2,0,0],[2,2,0,2,2],[0,2,0,2,0],[2,0,2,0,2],[1,0,2,2,2],[1,2,0,0,0],[0,2,2,2,1],[2,0,0,0,1]]},"stake":null,"version":"1.1.0"},"roundEnded":true}';
                $slotSettings->SetGameData('FengKuangMaJiangChangeMap', [
                    [
                        0, 
                        0, 
                        0
                    ], 
                    [
                        0, 
                        0, 
                        0
                    ], 
                    [
                        0, 
                        0, 
                        0
                    ], 
                    [
                        0, 
                        0, 
                        0
                    ], 
                    [
                        0, 
                        0, 
                        0
                    ]
                ]);
            }
            else if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'gamble5GetUserCards' ) 
            {
                $Balance = $slotSettings->GetBalance();
                $_obf_0D03381F212715073B0D165C28180E2C193C0A19283922 = rand(1, $slotSettings->GetGambleSettings());
                $_obf_0D25035C31183316381216122811401A1F2A17243E2B22 = $slotSettings->GetGameData('FengKuangMaJiangDealerCard');
                $totalWin = $slotSettings->GetGameData('FengKuangMaJiangTotalWin');
                $_obf_0D33130E1F150A28331B2F322B291E402639242D2B2D22 = 0;
                $gambleChoice = $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['gambleChoice'] - 2;
                $_obf_0D0E2C2E373C171C3B0B2905400E372723283E18043511 = '';
                $_obf_0D111701310A072F5C142524252B302A243F091F172411 = [
                    2, 
                    3, 
                    4, 
                    5, 
                    6, 
                    7, 
                    8, 
                    9, 
                    10, 
                    11, 
                    12, 
                    13, 
                    14
                ];
                $_obf_0D112B16351A0D0D02250E1F401526150C21152B143932 = [
                    'C', 
                    'S', 
                    'D', 
                    'H'
                ];
                $_obf_0D07380D0B2F2F240918081F3F2A042730295C091F2132 = [
                    '', 
                    '', 
                    '2', 
                    '3', 
                    '4', 
                    '5', 
                    '6', 
                    '7', 
                    '8', 
                    '9', 
                    '10', 
                    'J', 
                    'Q', 
                    'K', 
                    'A'
                ];
                $_obf_0D093F0332311B250D5C25022B1E403C26403B330F3511 = 0;
                $_obf_0D3310323F3F07041133133D263014342B230C260D1F11 = $totalWin;
                if( $slotSettings->GetBank((isset($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent']) ? $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] : '')) < ($totalWin * 2) ) 
                {
                    $_obf_0D03381F212715073B0D165C28180E2C193C0A19283922 = 0;
                }
                if( $_obf_0D03381F212715073B0D165C28180E2C193C0A19283922 == 1 ) 
                {
                    $_obf_0D093F0332311B250D5C25022B1E403C26403B330F3511 = rand($_obf_0D25035C31183316381216122811401A1F2A17243E2B22, 14);
                }
                else
                {
                    $_obf_0D093F0332311B250D5C25022B1E403C26403B330F3511 = rand(2, $_obf_0D25035C31183316381216122811401A1F2A17243E2B22);
                }
                if( $_obf_0D25035C31183316381216122811401A1F2A17243E2B22 < $_obf_0D093F0332311B250D5C25022B1E403C26403B330F3511 ) 
                {
                    $_obf_0D33130E1F150A28331B2F322B291E402639242D2B2D22 = $totalWin;
                    $totalWin = $totalWin * 2;
                    $_obf_0D0E2C2E373C171C3B0B2905400E372723283E18043511 = 'win';
                }
                else if( $_obf_0D093F0332311B250D5C25022B1E403C26403B330F3511 < $_obf_0D25035C31183316381216122811401A1F2A17243E2B22 ) 
                {
                    $_obf_0D33130E1F150A28331B2F322B291E402639242D2B2D22 = -1 * $totalWin;
                    $totalWin = 0;
                    $_obf_0D0E2C2E373C171C3B0B2905400E372723283E18043511 = 'lose';
                }
                else
                {
                    $_obf_0D33130E1F150A28331B2F322B291E402639242D2B2D22 = $totalWin;
                    $totalWin = $totalWin;
                    $_obf_0D0E2C2E373C171C3B0B2905400E372723283E18043511 = 'draw';
                }
                if( $_obf_0D33130E1F150A28331B2F322B291E402639242D2B2D22 != $totalWin ) 
                {
                    $slotSettings->SetBalance($_obf_0D33130E1F150A28331B2F322B291E402639242D2B2D22);
                    $slotSettings->SetBank((isset($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent']) ? $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] : ''), $_obf_0D33130E1F150A28331B2F322B291E402639242D2B2D22 * -1);
                }
                $_obf_0D14303C231C032D241D0B0B3536181C110C0D0A2B1932 = $slotSettings->GetBalance();
                $_obf_0D070A3C372F0137055B310C3816090324041017273C11 = [
                    rand(2, 14), 
                    rand(2, 14), 
                    rand(2, 14), 
                    rand(2, 14)
                ];
                $_obf_0D070A3C372F0137055B310C3816090324041017273C11[$gambleChoice] = $_obf_0D093F0332311B250D5C25022B1E403C26403B330F3511;
                for( $i = 0; $i < 4; $i++ ) 
                {
                    $_obf_0D070A3C372F0137055B310C3816090324041017273C11[$i] = '"' . $_obf_0D07380D0B2F2F240918081F3F2A042730295C091F2132[$_obf_0D070A3C372F0137055B310C3816090324041017273C11[$i]] . $_obf_0D112B16351A0D0D02250E1F401526150C21152B143932[rand(0, 3)] . '"';
                }
                $_obf_0D151B5C293D1C393D0F2F340F2A15032A122618191A32 = implode(',', $_obf_0D070A3C372F0137055B310C3816090324041017273C11);
                $slotSettings->SetGameData('FengKuangMaJiangTotalWin', $totalWin);
                $_obf_0D300C2F21350336261622142A322E0C270C0A1F2F0422 = '{"dealerCard":"' . $_obf_0D25035C31183316381216122811401A1F2A17243E2B22 . '","playerCards":[' . $_obf_0D151B5C293D1C393D0F2F340F2A15032A122618191A32 . '],"gambleState":"' . $_obf_0D0E2C2E373C171C3B0B2905400E372723283E18043511 . '","totalWin":' . $totalWin . ',"afterBalance":' . $_obf_0D14303C231C032D241D0B0B3536181C110C0D0A2B1932 . ',"Balance":' . $Balance . '}';
                $response = '{"responseEvent":"gambleResult","deb":' . $_obf_0D070A3C372F0137055B310C3816090324041017273C11[$gambleChoice] . ',"serverResponse":' . $_obf_0D300C2F21350336261622142A322E0C270C0A1F2F0422 . '}';
            }
            else if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'gamble5GetDealerCard' ) 
            {
                $_obf_0D111701310A072F5C142524252B302A243F091F172411 = [
                    2, 
                    3, 
                    4, 
                    5, 
                    6, 
                    7, 
                    8, 
                    9, 
                    10, 
                    11, 
                    12, 
                    13, 
                    14
                ];
                $_obf_0D07380D0B2F2F240918081F3F2A042730295C091F2132 = [
                    '', 
                    '', 
                    '2', 
                    '3', 
                    '4', 
                    '5', 
                    '6', 
                    '7', 
                    '8', 
                    '9', 
                    '10', 
                    'J', 
                    'Q', 
                    'K', 
                    'A'
                ];
                $_obf_0D112B16351A0D0D02250E1F401526150C21152B143932 = [
                    'C', 
                    'S', 
                    'D', 
                    'H'
                ];
                $_obf_0D1A28330223330201021115084008123B0F213C102922 = $_obf_0D111701310A072F5C142524252B302A243F091F172411[rand(0, 12)];
                $slotSettings->SetGameData('FengKuangMaJiangDealerCard', $_obf_0D1A28330223330201021115084008123B0F213C102922);
                $_obf_0D25035C31183316381216122811401A1F2A17243E2B22 = $_obf_0D07380D0B2F2F240918081F3F2A042730295C091F2132[$_obf_0D1A28330223330201021115084008123B0F213C102922] . $_obf_0D112B16351A0D0D02250E1F401526150C21152B143932[rand(0, 3)];
                $_obf_0D300C2F21350336261622142A322E0C270C0A1F2F0422 = '{"dealerCard":"' . $_obf_0D25035C31183316381216122811401A1F2A17243E2B22 . '"}';
                $response = '{"responseEvent":"gamble5DealerCard","serverResponse":' . $_obf_0D300C2F21350336261622142A322E0C270C0A1F2F0422 . '}';
            }
            else if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'slotGamble' ) 
            {
                $Balance = $slotSettings->GetBalance();
                $_obf_0D03381F212715073B0D165C28180E2C193C0A19283922 = rand(1, $slotSettings->GetGambleSettings());
                $_obf_0D25035C31183316381216122811401A1F2A17243E2B22 = '';
                $totalWin = $slotSettings->GetGameData('FengKuangMaJiangTotalWin');
                $slotSettings->SetGameData('FengKuangMaJiangBonusWin', $slotSettings->GetGameData('FengKuangMaJiangBonusWin') - $totalWin);
                $_obf_0D33130E1F150A28331B2F322B291E402639242D2B2D22 = 0;
                $_obf_0D3310323F3F07041133133D263014342B230C260D1F11 = $totalWin;
                if( $slotSettings->GetBank((isset($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent']) ? $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] : '')) < ($totalWin * 2) ) 
                {
                    $_obf_0D03381F212715073B0D165C28180E2C193C0A19283922 = 0;
                }
                if( $_obf_0D03381F212715073B0D165C28180E2C193C0A19283922 == 1 ) 
                {
                    $_obf_0D0E2C2E373C171C3B0B2905400E372723283E18043511 = 'win';
                    $_obf_0D33130E1F150A28331B2F322B291E402639242D2B2D22 = $totalWin;
                    $totalWin = $totalWin * 2;
                    if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['gambleChoice'] == 'red' ) 
                    {
                        $_obf_0D2B233F1B31290D2D35031A27300A032A11180B050301 = [
                            'D', 
                            'H'
                        ];
                        $_obf_0D25035C31183316381216122811401A1F2A17243E2B22 = $_obf_0D2B233F1B31290D2D35031A27300A032A11180B050301[rand(0, 1)];
                    }
                    else
                    {
                        $_obf_0D2B233F1B31290D2D35031A27300A032A11180B050301 = [
                            'C', 
                            'S'
                        ];
                        $_obf_0D25035C31183316381216122811401A1F2A17243E2B22 = $_obf_0D2B233F1B31290D2D35031A27300A032A11180B050301[rand(0, 1)];
                    }
                }
                else
                {
                    $_obf_0D0E2C2E373C171C3B0B2905400E372723283E18043511 = 'lose';
                    $_obf_0D33130E1F150A28331B2F322B291E402639242D2B2D22 = -1 * $totalWin;
                    $totalWin = 0;
                    if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['gambleChoice'] == 'red' ) 
                    {
                        $_obf_0D2B233F1B31290D2D35031A27300A032A11180B050301 = [
                            'C', 
                            'S'
                        ];
                        $_obf_0D25035C31183316381216122811401A1F2A17243E2B22 = $_obf_0D2B233F1B31290D2D35031A27300A032A11180B050301[rand(0, 1)];
                    }
                    else
                    {
                        $_obf_0D2B233F1B31290D2D35031A27300A032A11180B050301 = [
                            'D', 
                            'H'
                        ];
                        $_obf_0D25035C31183316381216122811401A1F2A17243E2B22 = $_obf_0D2B233F1B31290D2D35031A27300A032A11180B050301[rand(0, 1)];
                    }
                }
                $slotSettings->SetGameData('FengKuangMaJiangBonusWin', $slotSettings->GetGameData('FengKuangMaJiangBonusWin') + $totalWin);
                $slotSettings->SetGameData('FengKuangMaJiangTotalWin', $totalWin);
                $slotSettings->SetBalance($_obf_0D33130E1F150A28331B2F322B291E402639242D2B2D22);
                $slotSettings->SetBank((isset($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent']) ? $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] : ''), $_obf_0D33130E1F150A28331B2F322B291E402639242D2B2D22 * -1);
                $_obf_0D14303C231C032D241D0B0B3536181C110C0D0A2B1932 = $slotSettings->GetBalance();
                $_obf_0D300C2F21350336261622142A322E0C270C0A1F2F0422 = '{"bonusWin":' . $slotSettings->GetGameData('FengKuangMaJiangBonusWin') . ',"dealerCard":"' . $_obf_0D25035C31183316381216122811401A1F2A17243E2B22 . '","gambleState":"' . $_obf_0D0E2C2E373C171C3B0B2905400E372723283E18043511 . '","totalWin":' . $totalWin . ',"afterBalance":' . $_obf_0D14303C231C032D241D0B0B3536181C110C0D0A2B1932 . ',"Balance":' . $Balance . '}';
                $response = '{"responseEvent":"gambleResult","serverResponse":' . $_obf_0D300C2F21350336261622142A322E0C270C0A1F2F0422 . '}';
                $slotSettings->SaveLogReport($response, $_obf_0D3310323F3F07041133133D263014342B230C260D1F11, 1, $_obf_0D33130E1F150A28331B2F322B291E402639242D2B2D22, $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent']);
            }
            else if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'update' ) 
            {
                $response = '{"responseEvent":"error","responseType":"' . $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] . '","serverResponse":"' . $slotSettings->GetBalance() . '"}';
                exit( $response );
            }
            if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'spin' || $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'freespin' || $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'respin' ) 
            {
                $_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901 = [];
                $_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[1] = [
                    2, 
                    2, 
                    2, 
                    2, 
                    2
                ];
                $_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[2] = [
                    1, 
                    1, 
                    1, 
                    1, 
                    1
                ];
                $_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[3] = [
                    3, 
                    3, 
                    3, 
                    3, 
                    3
                ];
                $_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[4] = [
                    1, 
                    2, 
                    3, 
                    2, 
                    1
                ];
                $_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[5] = [
                    3, 
                    2, 
                    1, 
                    2, 
                    3
                ];
                $_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[6] = [
                    1, 
                    1, 
                    2, 
                    3, 
                    3
                ];
                $_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[7] = [
                    3, 
                    3, 
                    2, 
                    1, 
                    1
                ];
                $_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[8] = [
                    2, 
                    1, 
                    2, 
                    3, 
                    2
                ];
                $_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[9] = [
                    2, 
                    3, 
                    2, 
                    1, 
                    2
                ];
                $_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[10] = [
                    2, 
                    1, 
                    1, 
                    1, 
                    1
                ];
                $_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[11] = [
                    2, 
                    3, 
                    3, 
                    3, 
                    3
                ];
                $_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[12] = [
                    1, 
                    2, 
                    2, 
                    2, 
                    2
                ];
                $_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[13] = [
                    3, 
                    2, 
                    2, 
                    2, 
                    2
                ];
                $_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[14] = [
                    1, 
                    2, 
                    1, 
                    2, 
                    1
                ];
                $_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[15] = [
                    3, 
                    2, 
                    3, 
                    2, 
                    3
                ];
                $_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[16] = [
                    2, 
                    2, 
                    1, 
                    2, 
                    2
                ];
                $_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[17] = [
                    2, 
                    2, 
                    3, 
                    2, 
                    2
                ];
                $_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[18] = [
                    1, 
                    1, 
                    3, 
                    1, 
                    1
                ];
                $_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[19] = [
                    3, 
                    3, 
                    1, 
                    3, 
                    3
                ];
                $_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[20] = [
                    1, 
                    3, 
                    1, 
                    3, 
                    1
                ];
                $_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[21] = [
                    3, 
                    1, 
                    3, 
                    1, 
                    3
                ];
                $_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[22] = [
                    2, 
                    1, 
                    3, 
                    3, 
                    3
                ];
                $_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[23] = [
                    2, 
                    3, 
                    1, 
                    1, 
                    1
                ];
                $_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[24] = [
                    1, 
                    3, 
                    3, 
                    3, 
                    2
                ];
                $_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[25] = [
                    3, 
                    1, 
                    1, 
                    1, 
                    2
                ];
                $_obf_0D2433275B04240E5C2B30283B045C37031B063C342E22 = false;
                if( $slotSettings->GetGameData('FengKuangMaJiangCurrentFreeGame') <= $slotSettings->GetGameData('FengKuangMaJiangFreeGames') && $slotSettings->GetGameData('FengKuangMaJiangFreeGames') > 0 ) 
                {
                    $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] = 'freespin';
                    $_obf_0D2433275B04240E5C2B30283B045C37031B063C342E22 = true;
                }
                if( $slotSettings->GetGameData('FengKuangMaJiangIsReSpin') == 1 ) 
                {
                    $slotSettings->SetGameData('FengKuangMaJiangIsReSpin', 0);
                    $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] = 'respin';
                }
                $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] = $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['bet'];
                $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotLines'] = $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['lines'];
                $_obf_0D34351C331E352827231A38333E1A082713062B2B2732 = $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotLines'];
                $_obf_0D31103E3B3D1E1A27051D1540063B0528291C5C1A0D22 = $slotSettings->GetSpinSettings($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'], $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotLines'], $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotLines']);
                $winType = $_obf_0D31103E3B3D1E1A27051D1540063B0528291C5C1A0D22[0];
                $_obf_0D3B3C113639391705311B0F12323C3B3B250C1A142401 = $_obf_0D31103E3B3D1E1A27051D1540063B0528291C5C1A0D22[1];
                if( $_obf_0D2433275B04240E5C2B30283B045C37031B063C342E22 && $winType == 'bonus' ) 
                {
                    $winType = 'win';
                }
                if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] != 'freespin' && $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] != 'respin' ) 
                {
                    if( !isset($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent']) ) 
                    {
                        $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] = 'bet';
                    }
                    $_obf_0D1A310E2B25282C1A01072A06330C1A173E3437092622 = ($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotLines']) / 100 * $slotSettings->GetPercent();
                    $slotSettings->SetBank((isset($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent']) ? $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] : ''), $_obf_0D1A310E2B25282C1A01072A06330C1A173E3437092622, $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent']);
                    $slotSettings->UpdateJackpots($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotLines']);
                    $slotSettings->SetBalance(-1 * ($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotLines']), $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent']);
                    $_obf_0D3E1E10392C18192A17311514325C2E132A29390E2E22 = 1;
                    $slotSettings->SetGameData('FengKuangMaJiangIsReSpin', 0);
                    $slotSettings->SetGameData('FengKuangMaJiangBonusWin', 0);
                    $slotSettings->SetGameData('FengKuangMaJiangFreeGames', 0);
                    $slotSettings->SetGameData('FengKuangMaJiangCurrentFreeGame', 0);
                    $slotSettings->SetGameData('FengKuangMaJiangTotalWin', 0);
                    $slotSettings->SetGameData('FengKuangMaJiangFreeBalance', 0);
                    $slotSettings->SetGameData('FengKuangMaJiangFreeMpl', 1);
                }
                else if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] != 'respin' ) 
                {
                    $slotSettings->SetGameData('FengKuangMaJiangCurrentFreeGame', $slotSettings->GetGameData('FengKuangMaJiangCurrentFreeGame') + 1);
                    $_obf_0D3E1E10392C18192A17311514325C2E132A29390E2E22 = 1;
                    $slotSettings->SetGameData('FengKuangMaJiangFreeMpl', 1);
                }
                else if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'respin' ) 
                {
                    if( $slotSettings->GetGameData('FengKuangMaJiangFreeMpl') < 5 ) 
                    {
                        $slotSettings->SetGameData('FengKuangMaJiangFreeMpl', $slotSettings->GetGameData('FengKuangMaJiangFreeMpl') + 1);
                    }
                    if( $slotSettings->GetGameData('FengKuangMaJiangCurrentFreeGame') > 0 ) 
                    {
                        $_obf_0D3E1E10392C18192A17311514325C2E132A29390E2E22 = $slotSettings->GetGameData('FengKuangMaJiangFreeMpl');
                    }
                    else
                    {
                        $_obf_0D3E1E10392C18192A17311514325C2E132A29390E2E22 = 1;
                    }
                }
                $Balance = $slotSettings->GetBalance();
                $_obf_0D1F37031733101102293B011A05321D26345C2E1A5C01 = $slotSettings->GetGameData('FengKuangMaJiangChangeMap');
                $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11 = $slotSettings->GetGameData('FengKuangMaJiangReelsMap');
                if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'respin' ) 
                {
                    for( $r = 1; $r <= 5; $r++ ) 
                    {
                        if( $_obf_0D1F37031733101102293B011A05321D26345C2E1A5C01[$r - 1][2] < 0 && $_obf_0D1F37031733101102293B011A05321D26345C2E1A5C01[$r - 1][1] >= 0 && $_obf_0D1F37031733101102293B011A05321D26345C2E1A5C01[$r - 1][0] >= 0 ) 
                        {
                            $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][2] = $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][1];
                            $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][1] = $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][0];
                            $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][0] = 'EMPTY';
                        }
                        else if( $_obf_0D1F37031733101102293B011A05321D26345C2E1A5C01[$r - 1][2] >= 0 && $_obf_0D1F37031733101102293B011A05321D26345C2E1A5C01[$r - 1][1] < 0 && $_obf_0D1F37031733101102293B011A05321D26345C2E1A5C01[$r - 1][0] >= 0 ) 
                        {
                            $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][2] = $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][2];
                            $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][1] = $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][0];
                            $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][0] = 'EMPTY';
                        }
                        else if( $_obf_0D1F37031733101102293B011A05321D26345C2E1A5C01[$r - 1][2] >= 0 && $_obf_0D1F37031733101102293B011A05321D26345C2E1A5C01[$r - 1][1] >= 0 && $_obf_0D1F37031733101102293B011A05321D26345C2E1A5C01[$r - 1][0] < 0 ) 
                        {
                            $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][2] = $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][2];
                            $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][1] = $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][1];
                            $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][0] = 'EMPTY';
                        }
                        else if( $_obf_0D1F37031733101102293B011A05321D26345C2E1A5C01[$r - 1][2] < 0 && $_obf_0D1F37031733101102293B011A05321D26345C2E1A5C01[$r - 1][1] < 0 && $_obf_0D1F37031733101102293B011A05321D26345C2E1A5C01[$r - 1][0] >= 0 ) 
                        {
                            $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][2] = $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][0];
                            $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][1] = 'EMPTY';
                            $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][0] = 'EMPTY';
                        }
                        else if( $_obf_0D1F37031733101102293B011A05321D26345C2E1A5C01[$r - 1][2] >= 0 && $_obf_0D1F37031733101102293B011A05321D26345C2E1A5C01[$r - 1][1] < 0 && $_obf_0D1F37031733101102293B011A05321D26345C2E1A5C01[$r - 1][0] < 0 ) 
                        {
                            $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][2] = $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][2];
                            $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][1] = 'EMPTY';
                            $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][0] = 'EMPTY';
                        }
                        else if( $_obf_0D1F37031733101102293B011A05321D26345C2E1A5C01[$r - 1][2] < 0 && $_obf_0D1F37031733101102293B011A05321D26345C2E1A5C01[$r - 1][1] >= 0 && $_obf_0D1F37031733101102293B011A05321D26345C2E1A5C01[$r - 1][0] < 0 ) 
                        {
                            $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][2] = $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][1];
                            $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][1] = 'EMPTY';
                            $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][0] = 'EMPTY';
                        }
                        else if( $_obf_0D1F37031733101102293B011A05321D26345C2E1A5C01[$r - 1][2] < 0 && $_obf_0D1F37031733101102293B011A05321D26345C2E1A5C01[$r - 1][1] < 0 && $_obf_0D1F37031733101102293B011A05321D26345C2E1A5C01[$r - 1][0] < 0 ) 
                        {
                            $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][2] = 'EMPTY';
                            $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][1] = 'EMPTY';
                            $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][0] = 'EMPTY';
                        }
                    }
                }
                for( $i = 0; $i <= 2000; $i++ ) 
                {
                    $totalWin = 0;
                    $_obf_0D181C103526150D021B2C0E1A1F211F3F3E2A15363632 = [];
                    $_obf_0D1F171A1F35063716213837072F111B1E0D042E1B1A11 = [
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0, 
                        0
                    ];
                    $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22 = ['0'];
                    $_obf_0D2B2F2802280E223138132C0B310F3C0A2D3328275C22 = '13';
                    $_obf_0D2B2C361A1519230B37081F290E3430171007181F1522 = '12';
                    $_obf_0D37321A2B3F180F0E28173F375C093F0C320B29323011 = [
                        [], 
                        [], 
                        [], 
                        [], 
                        [], 
                        [], 
                        []
                    ];
                    $_obf_0D3C3915080E3D0C3F07400E0F0B05302614072F380B01 = [
                        [
                            0, 
                            0, 
                            0
                        ], 
                        [
                            0, 
                            0, 
                            0
                        ], 
                        [
                            0, 
                            0, 
                            0
                        ], 
                        [
                            0, 
                            0, 
                            0
                        ], 
                        [
                            0, 
                            0, 
                            0
                        ]
                    ];
                    if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'respin' ) 
                    {
                        $_obf_0D3C090E192F3D26100429351F02123B310C3504040132 = [];
                        $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['rp'] = [
                            1, 
                            1, 
                            1, 
                            1, 
                            1
                        ];
                        $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel1'] = [];
                        $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel2'] = [];
                        $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel3'] = [];
                        $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel4'] = [];
                        $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel5'] = [];
                        for( $r = 1; $r <= 5; $r++ ) 
                        {
                            for( $_obf_0D143F2A0C3528391A5B28151B03151A21260B26195C22 = 0; $_obf_0D143F2A0C3528391A5B28151B03151A21260B26195C22 <= 100; $_obf_0D143F2A0C3528391A5B28151B03151A21260B26195C22++ ) 
                            {
                                $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel' . $r] = [];
                                $_obf_0D37321A2B3F180F0E28173F375C093F0C320B29323011[$r] = [];
                                for( $i = 2; $i >= 0; $i-- ) 
                                {
                                    if( $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][$i] == 'EMPTY' ) 
                                    {
                                        $_obf_0D345B380A1A351C361F2131251923362F030812020522 = $slotSettings->SymbolGame[rand(0, count($slotSettings->SymbolGame) - 1)];
                                        $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel' . $r][$i] = $_obf_0D345B380A1A351C361F2131251923362F030812020522;
                                        $_obf_0D37321A2B3F180F0E28173F375C093F0C320B29323011[$r][] = $_obf_0D345B380A1A351C361F2131251923362F030812020522;
                                    }
                                    else
                                    {
                                        $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel' . $r][$i] = $_obf_0D2C235B0428363727080401172A3E0B0B1A282B2C1C11['reel' . $r][$i];
                                    }
                                }
                                if( $slotSettings->CheckDuplicateSym($_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel' . $r]) ) 
                                {
                                    break;
                                }
                            }
                        }
                    }
                    else
                    {
                        $_obf_0D3C090E192F3D26100429351F02123B310C3504040132 = $slotSettings->GetReelStrips($winType, $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent']);
                    }
                    for( $k = 0; $k < $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotLines']; $k++ ) 
                    {
                        $_obf_0D0207283039073919263232090A382F3D26101F0D1E11 = '';
                        for( $j = 0; $j < count($slotSettings->SymbolGame); $j++ ) 
                        {
                            $_obf_0D011C142C3C37263F351C4012170A074027083F321132 = $slotSettings->SymbolGame[$j];
                            if( $_obf_0D011C142C3C37263F351C4012170A074027083F321132 == $_obf_0D2B2F2802280E223138132C0B310F3C0A2D3328275C22 || !isset($slotSettings->Paytable[$_obf_0D011C142C3C37263F351C4012170A074027083F321132]) || !isset($slotSettings->Paytable[$_obf_0D011C142C3C37263F351C4012170A074027083F321132]) ) 
                            {
                            }
                            else
                            {
                                $s = [];
                                $s[0] = $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel1'][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][0] - 1];
                                $s[1] = $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel2'][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][1] - 1];
                                $s[2] = $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel3'][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][2] - 1];
                                $s[3] = $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel4'][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][3] - 1];
                                $s[4] = $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel5'][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][4] - 1];
                                if( $s[0] == $_obf_0D011C142C3C37263F351C4012170A074027083F321132 || in_array($s[0], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22) ) 
                                {
                                    $_obf_0D1016073B15193E060D2D0C262328020129171D232A32 = 1;
                                    $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01 = $slotSettings->Paytable[$_obf_0D011C142C3C37263F351C4012170A074027083F321132][1] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] * $_obf_0D1016073B15193E060D2D0C262328020129171D232A32 * $_obf_0D3E1E10392C18192A17311514325C2E132A29390E2E22;
                                    if( $_obf_0D1F171A1F35063716213837072F111B1E0D042E1B1A11[$k] < $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01 ) 
                                    {
                                        $_obf_0D1F171A1F35063716213837072F111B1E0D042E1B1A11[$k] = $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01;
                                        $_obf_0D0207283039073919263232090A382F3D26101F0D1E11 = '{"reward":"line","direction":"LEFT_TO_RIGHT","lineId":' . $k . ',"payout":' . $_obf_0D1F171A1F35063716213837072F111B1E0D042E1B1A11[$k] . ',"lineMultiplier":' . $_obf_0D1016073B15193E060D2D0C262328020129171D232A32 . ',"paytable":[' . $_obf_0D011C142C3C37263F351C4012170A074027083F321132 . ',0]}';
                                    }
                                }
                                if( ($s[0] == $_obf_0D011C142C3C37263F351C4012170A074027083F321132 || in_array($s[0], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22)) && ($s[1] == $_obf_0D011C142C3C37263F351C4012170A074027083F321132 || in_array($s[1], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22)) ) 
                                {
                                    $_obf_0D1016073B15193E060D2D0C262328020129171D232A32 = 1;
                                    if( in_array($s[0], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22) && in_array($s[1], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22) ) 
                                    {
                                        $_obf_0D1016073B15193E060D2D0C262328020129171D232A32 = 1;
                                    }
                                    else if( in_array($s[0], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22) || in_array($s[1], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22) ) 
                                    {
                                        $_obf_0D1016073B15193E060D2D0C262328020129171D232A32 = $slotSettings->slotWildMpl;
                                    }
                                    $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01 = $slotSettings->Paytable[$_obf_0D011C142C3C37263F351C4012170A074027083F321132][2] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] * $_obf_0D1016073B15193E060D2D0C262328020129171D232A32 * $_obf_0D3E1E10392C18192A17311514325C2E132A29390E2E22;
                                    if( $_obf_0D1F171A1F35063716213837072F111B1E0D042E1B1A11[$k] < $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01 ) 
                                    {
                                        $_obf_0D3C3915080E3D0C3F07400E0F0B05302614072F380B01[0][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][0] - 1] = -1;
                                        $_obf_0D3C3915080E3D0C3F07400E0F0B05302614072F380B01[1][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][1] - 1] = -1;
                                        $_obf_0D1F171A1F35063716213837072F111B1E0D042E1B1A11[$k] = $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01;
                                        $_obf_0D0207283039073919263232090A382F3D26101F0D1E11 = '{"reward":"line","direction":"LEFT_TO_RIGHT","lineId":' . $k . ',"payout":' . $_obf_0D1F171A1F35063716213837072F111B1E0D042E1B1A11[$k] . ',"lineMultiplier":' . $_obf_0D1016073B15193E060D2D0C262328020129171D232A32 . ',"paytable":[' . $_obf_0D011C142C3C37263F351C4012170A074027083F321132 . ',1]}';
                                    }
                                }
                                $s[0] = $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel1'][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][0] - 1];
                                $s[1] = $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel2'][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][1] - 1];
                                $s[2] = $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel3'][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][2] - 1];
                                $s[3] = $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel4'][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][3] - 1];
                                $s[4] = $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel5'][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][4] - 1];
                                if( ($s[0] == $_obf_0D011C142C3C37263F351C4012170A074027083F321132 || in_array($s[0], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22)) && ($s[1] == $_obf_0D011C142C3C37263F351C4012170A074027083F321132 || in_array($s[1], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22)) && ($s[2] == $_obf_0D011C142C3C37263F351C4012170A074027083F321132 || in_array($s[2], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22)) ) 
                                {
                                    $_obf_0D1016073B15193E060D2D0C262328020129171D232A32 = 1;
                                    if( in_array($s[0], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22) && in_array($s[1], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22) && in_array($s[2], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22) ) 
                                    {
                                        $_obf_0D1016073B15193E060D2D0C262328020129171D232A32 = 1;
                                    }
                                    else if( in_array($s[0], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22) || in_array($s[1], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22) || in_array($s[2], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22) ) 
                                    {
                                        $_obf_0D1016073B15193E060D2D0C262328020129171D232A32 = $slotSettings->slotWildMpl;
                                    }
                                    $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01 = $slotSettings->Paytable[$_obf_0D011C142C3C37263F351C4012170A074027083F321132][3] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] * $_obf_0D1016073B15193E060D2D0C262328020129171D232A32 * $_obf_0D3E1E10392C18192A17311514325C2E132A29390E2E22;
                                    if( $_obf_0D1F171A1F35063716213837072F111B1E0D042E1B1A11[$k] < $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01 ) 
                                    {
                                        $_obf_0D3C3915080E3D0C3F07400E0F0B05302614072F380B01[0][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][0] - 1] = -1;
                                        $_obf_0D3C3915080E3D0C3F07400E0F0B05302614072F380B01[1][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][1] - 1] = -1;
                                        $_obf_0D3C3915080E3D0C3F07400E0F0B05302614072F380B01[2][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][2] - 1] = -1;
                                        $_obf_0D1F171A1F35063716213837072F111B1E0D042E1B1A11[$k] = $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01;
                                        $_obf_0D0207283039073919263232090A382F3D26101F0D1E11 = '{"reward":"line","direction":"LEFT_TO_RIGHT","lineId":' . $k . ',"payout":' . $_obf_0D1F171A1F35063716213837072F111B1E0D042E1B1A11[$k] . ',"lineMultiplier":' . $_obf_0D1016073B15193E060D2D0C262328020129171D232A32 . ',"paytable":[' . $_obf_0D011C142C3C37263F351C4012170A074027083F321132 . ',2]}';
                                    }
                                }
                                $s[0] = $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel1'][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][0] - 1];
                                $s[1] = $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel2'][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][1] - 1];
                                $s[2] = $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel3'][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][2] - 1];
                                $s[3] = $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel4'][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][3] - 1];
                                $s[4] = $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel5'][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][4] - 1];
                                if( ($s[0] == $_obf_0D011C142C3C37263F351C4012170A074027083F321132 || in_array($s[0], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22)) && ($s[1] == $_obf_0D011C142C3C37263F351C4012170A074027083F321132 || in_array($s[1], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22)) && ($s[2] == $_obf_0D011C142C3C37263F351C4012170A074027083F321132 || in_array($s[2], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22)) && ($s[3] == $_obf_0D011C142C3C37263F351C4012170A074027083F321132 || in_array($s[3], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22)) ) 
                                {
                                    $_obf_0D1016073B15193E060D2D0C262328020129171D232A32 = 1;
                                    if( in_array($s[0], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22) && in_array($s[1], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22) && in_array($s[2], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22) && in_array($s[3], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22) ) 
                                    {
                                        $_obf_0D1016073B15193E060D2D0C262328020129171D232A32 = 1;
                                    }
                                    else if( in_array($s[0], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22) || in_array($s[1], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22) || in_array($s[2], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22) || in_array($s[3], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22) ) 
                                    {
                                        $_obf_0D1016073B15193E060D2D0C262328020129171D232A32 = $slotSettings->slotWildMpl;
                                    }
                                    $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01 = $slotSettings->Paytable[$_obf_0D011C142C3C37263F351C4012170A074027083F321132][4] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] * $_obf_0D1016073B15193E060D2D0C262328020129171D232A32 * $_obf_0D3E1E10392C18192A17311514325C2E132A29390E2E22;
                                    if( $_obf_0D1F171A1F35063716213837072F111B1E0D042E1B1A11[$k] < $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01 ) 
                                    {
                                        $_obf_0D3C3915080E3D0C3F07400E0F0B05302614072F380B01[0][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][0] - 1] = -1;
                                        $_obf_0D3C3915080E3D0C3F07400E0F0B05302614072F380B01[1][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][1] - 1] = -1;
                                        $_obf_0D3C3915080E3D0C3F07400E0F0B05302614072F380B01[2][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][2] - 1] = -1;
                                        $_obf_0D3C3915080E3D0C3F07400E0F0B05302614072F380B01[3][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][3] - 1] = -1;
                                        $_obf_0D1F171A1F35063716213837072F111B1E0D042E1B1A11[$k] = $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01;
                                        $_obf_0D0207283039073919263232090A382F3D26101F0D1E11 = '{"reward":"line","direction":"LEFT_TO_RIGHT","lineId":' . $k . ',"payout":' . $_obf_0D1F171A1F35063716213837072F111B1E0D042E1B1A11[$k] . ',"lineMultiplier":' . $_obf_0D1016073B15193E060D2D0C262328020129171D232A32 . ',"paytable":[' . $_obf_0D011C142C3C37263F351C4012170A074027083F321132 . ',3]}';
                                    }
                                }
                                $s[0] = $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel1'][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][0] - 1];
                                $s[1] = $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel2'][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][1] - 1];
                                $s[2] = $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel3'][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][2] - 1];
                                $s[3] = $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel4'][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][3] - 1];
                                $s[4] = $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel5'][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][4] - 1];
                                if( ($s[0] == $_obf_0D011C142C3C37263F351C4012170A074027083F321132 || in_array($s[0], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22)) && ($s[1] == $_obf_0D011C142C3C37263F351C4012170A074027083F321132 || in_array($s[1], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22)) && ($s[2] == $_obf_0D011C142C3C37263F351C4012170A074027083F321132 || in_array($s[2], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22)) && ($s[3] == $_obf_0D011C142C3C37263F351C4012170A074027083F321132 || in_array($s[3], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22)) && ($s[4] == $_obf_0D011C142C3C37263F351C4012170A074027083F321132 || in_array($s[4], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22)) ) 
                                {
                                    $_obf_0D1016073B15193E060D2D0C262328020129171D232A32 = 1;
                                    if( in_array($s[0], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22) && in_array($s[1], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22) && in_array($s[2], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22) && in_array($s[3], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22) && in_array($s[4], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22) ) 
                                    {
                                        $_obf_0D1016073B15193E060D2D0C262328020129171D232A32 = 1;
                                    }
                                    else if( in_array($s[0], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22) || in_array($s[1], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22) || in_array($s[2], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22) || in_array($s[3], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22) || in_array($s[4], $_obf_0D09150B2722395B0A39250839035C2C1C053B311C2B22) ) 
                                    {
                                        $_obf_0D1016073B15193E060D2D0C262328020129171D232A32 = $slotSettings->slotWildMpl;
                                    }
                                    $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01 = $slotSettings->Paytable[$_obf_0D011C142C3C37263F351C4012170A074027083F321132][5] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] * $_obf_0D1016073B15193E060D2D0C262328020129171D232A32 * $_obf_0D3E1E10392C18192A17311514325C2E132A29390E2E22;
                                    if( $_obf_0D1F171A1F35063716213837072F111B1E0D042E1B1A11[$k] < $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01 ) 
                                    {
                                        $_obf_0D3C3915080E3D0C3F07400E0F0B05302614072F380B01[0][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][0] - 1] = -1;
                                        $_obf_0D3C3915080E3D0C3F07400E0F0B05302614072F380B01[1][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][1] - 1] = -1;
                                        $_obf_0D3C3915080E3D0C3F07400E0F0B05302614072F380B01[2][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][2] - 1] = -1;
                                        $_obf_0D3C3915080E3D0C3F07400E0F0B05302614072F380B01[3][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][3] - 1] = -1;
                                        $_obf_0D3C3915080E3D0C3F07400E0F0B05302614072F380B01[4][$_obf_0D1F0E07322B28015B3101401931191F0119352A1D0901[$k + 1][4] - 1] = -1;
                                        $_obf_0D1F171A1F35063716213837072F111B1E0D042E1B1A11[$k] = $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01;
                                        $_obf_0D0207283039073919263232090A382F3D26101F0D1E11 = '{"reward":"line","direction":"LEFT_TO_RIGHT","lineId":' . $k . ',"payout":' . $_obf_0D1F171A1F35063716213837072F111B1E0D042E1B1A11[$k] . ',"lineMultiplier":' . $_obf_0D1016073B15193E060D2D0C262328020129171D232A32 . ',"paytable":[' . $_obf_0D011C142C3C37263F351C4012170A074027083F321132 . ',4]}';
                                    }
                                }
                            }
                        }
                        if( $_obf_0D1F171A1F35063716213837072F111B1E0D042E1B1A11[$k] > 0 && $_obf_0D0207283039073919263232090A382F3D26101F0D1E11 != '' ) 
                        {
                            array_push($_obf_0D181C103526150D021B2C0E1A1F211F3F3E2A15363632, $_obf_0D0207283039073919263232090A382F3D26101F0D1E11);
                            $totalWin += $_obf_0D1F171A1F35063716213837072F111B1E0D042E1B1A11[$k];
                        }
                    }
                    $_obf_0D10342528350D243D16293C2835061F263C1C39042811 = 0;
                    $_obf_0D2C1A321706030F3F1225352E343107190E393C131522 = 0;
                    $_obf_0D033835123E051D331E010A3C300C332C34021F052801 = '{';
                    $_obf_0D24263035301D1D2F05213C312414350B3B1317353E11 = '{';
                    $_obf_0D0B230B342E0C0727115B043F283E2137182D312A3D11 = 0;
                    $_obf_0D28301D333F2827165C2511011438180336175B1E2A01 = 0;
                    $_obf_0D5B0915301B403E0E18271D3C2A351E0D5C1521150E01 = [];
                    $_obf_0D3C2A02251E01100109275C17343C1E232E0640091A11 = [];
                    for( $r = 1; $r <= 5; $r++ ) 
                    {
                        for( $_obf_0D13090C3F3C3624123E1A2C091F31232304270B023B32 = 0; $_obf_0D13090C3F3C3624123E1A2C091F31232304270B023B32 <= 2; $_obf_0D13090C3F3C3624123E1A2C091F31232304270B023B32++ ) 
                        {
                            if( $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel' . $r][$_obf_0D13090C3F3C3624123E1A2C091F31232304270B023B32] == $_obf_0D2B2F2802280E223138132C0B310F3C0A2D3328275C22 ) 
                            {
                                $_obf_0D0B230B342E0C0727115B043F283E2137182D312A3D11++;
                                $_obf_0D033835123E051D331E010A3C300C332C34021F052801 .= ('"winReel' . $r . '":[' . $_obf_0D13090C3F3C3624123E1A2C091F31232304270B023B32 . ',"' . $_obf_0D2B2F2802280E223138132C0B310F3C0A2D3328275C22 . '"],');
                                $_obf_0D5B0915301B403E0E18271D3C2A351E0D5C1521150E01[] = '[' . $_obf_0D13090C3F3C3624123E1A2C091F31232304270B023B32 . ',' . ($r - 1) . ']';
                            }
                            if( $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel' . $r][$_obf_0D13090C3F3C3624123E1A2C091F31232304270B023B32] == $_obf_0D2B2C361A1519230B37081F290E3430171007181F1522 ) 
                            {
                                $_obf_0D28301D333F2827165C2511011438180336175B1E2A01++;
                                $_obf_0D24263035301D1D2F05213C312414350B3B1317353E11 .= ('"winReel' . $r . '":[' . $_obf_0D13090C3F3C3624123E1A2C091F31232304270B023B32 . ',"' . $_obf_0D2B2C361A1519230B37081F290E3430171007181F1522 . '"],');
                                $_obf_0D3C2A02251E01100109275C17343C1E232E0640091A11[] = '[' . $_obf_0D13090C3F3C3624123E1A2C091F31232304270B023B32 . ',' . ($r - 1) . ']';
                            }
                        }
                    }
                    $_obf_0D18322F1D343B3D031D301B09113719372C0736301A11 = '';
                    $_obf_0D10342528350D243D16293C2835061F263C1C39042811 = 0;
                    if( $_obf_0D0B230B342E0C0727115B043F283E2137182D312A3D11 >= 3 && $slotSettings->slotBonus ) 
                    {
                        $_obf_0D033835123E051D331E010A3C300C332C34021F052801 .= '"scattersType":"bonus",';
                        $_obf_0D18322F1D343B3D031D301B09113719372C0736301A11 = '{"id": "FKMJTriggerBonus", "triggerSymbols": [' . implode(',', $_obf_0D5B0915301B403E0E18271D3C2A351E0D5C1521150E01) . ']}';
                    }
                    else if( $_obf_0D10342528350D243D16293C2835061F263C1C39042811 > 0 ) 
                    {
                        $_obf_0D033835123E051D331E010A3C300C332C34021F052801 .= '"scattersType":"win",';
                    }
                    else
                    {
                        $_obf_0D033835123E051D331E010A3C300C332C34021F052801 .= '"scattersType":"none",';
                    }
                    $_obf_0D033835123E051D331E010A3C300C332C34021F052801 .= ('"scattersWin":' . $_obf_0D10342528350D243D16293C2835061F263C1C39042811 . '}');
                    $totalWin += $_obf_0D10342528350D243D16293C2835061F263C1C39042811;
                    $_obf_0D2C1A321706030F3F1225352E343107190E393C131522 = $slotSettings->Paytable[$_obf_0D2B2C361A1519230B37081F290E3430171007181F1522][$_obf_0D28301D333F2827165C2511011438180336175B1E2A01] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotLines'];
                    if( $_obf_0D2C1A321706030F3F1225352E343107190E393C131522 > 0 ) 
                    {
                        $_obf_0D24263035301D1D2F05213C312414350B3B1317353E11 .= '"scattersType":"win",';
                        $_obf_0D0207283039073919263232090A382F3D26101F0D1E11 = '{"reward":"scatter","payout":' . $_obf_0D2C1A321706030F3F1225352E343107190E393C131522 . ',"lineMultiplier":1,"positions":[' . implode(',', $_obf_0D3C2A02251E01100109275C17343C1E232E0640091A11) . '],"paytable":[9,' . ($_obf_0D28301D333F2827165C2511011438180336175B1E2A01 - 1) . ']}';
                        array_push($_obf_0D181C103526150D021B2C0E1A1F211F3F3E2A15363632, $_obf_0D0207283039073919263232090A382F3D26101F0D1E11);
                    }
                    else
                    {
                        $_obf_0D24263035301D1D2F05213C312414350B3B1317353E11 .= '"scattersType":"none",';
                    }
                    $_obf_0D24263035301D1D2F05213C312414350B3B1317353E11 .= ('"scattersWin":' . $_obf_0D2C1A321706030F3F1225352E343107190E393C131522 . '}');
                    $totalWin += $_obf_0D2C1A321706030F3F1225352E343107190E393C131522;
                    if( $i > 1000 ) 
                    {
                        $winType = 'none';
                    }
                    if( $i > 1500 ) 
                    {
                        $response = '{"responseEvent":"error","responseType":"' . json_encode($_obf_0D3C090E192F3D26100429351F02123B310C3504040132) . '|' . $_obf_0D0B230B342E0C0727115B043F283E2137182D312A3D11 . '|' . $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] . '","serverResponse":"Bad Reel Strip"}';
                        exit( $response );
                    }
                    if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] != 'respin' && $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] != 'freespin' ) 
                    {
                        if( $slotSettings->increaseRTP && $winType == 'win' && $totalWin < ($_obf_0D34351C331E352827231A38333E1A082713062B2B2732 * rand(2, 5)) ) 
                        {
                        }
                        else if( !$slotSettings->increaseRTP && $winType == 'win' && $_obf_0D34351C331E352827231A38333E1A082713062B2B2732 < $totalWin ) 
                        {
                        }
                    }
                    if( $_obf_0D0B230B342E0C0727115B043F283E2137182D312A3D11 >= 3 && $winType != 'bonus' || $_obf_0D0B230B342E0C0727115B043F283E2137182D312A3D11 > 5 ) 
                    {
                    }
                    else
                    {
                        if( $totalWin <= $_obf_0D3B3C113639391705311B0F12323C3B3B250C1A142401 && $winType == 'bonus' ) 
                        {
                            $_obf_0D163F390C080D0831380D161E12270D0225132B261501 = $slotSettings->GetBank((isset($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent']) ? $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] : ''));
                            if( $_obf_0D163F390C080D0831380D161E12270D0225132B261501 < $_obf_0D3B3C113639391705311B0F12323C3B3B250C1A142401 ) 
                            {
                                $_obf_0D3B3C113639391705311B0F12323C3B3B250C1A142401 = $_obf_0D163F390C080D0831380D161E12270D0225132B261501;
                            }
                            else
                            {
                                break;
                            }
                        }
                        else if( $totalWin > 0 && $totalWin <= $_obf_0D3B3C113639391705311B0F12323C3B3B250C1A142401 && $winType == 'win' ) 
                        {
                            $_obf_0D163F390C080D0831380D161E12270D0225132B261501 = $slotSettings->GetBank((isset($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent']) ? $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] : ''));
                            if( $_obf_0D163F390C080D0831380D161E12270D0225132B261501 < $_obf_0D3B3C113639391705311B0F12323C3B3B250C1A142401 ) 
                            {
                                $_obf_0D3B3C113639391705311B0F12323C3B3B250C1A142401 = $_obf_0D163F390C080D0831380D161E12270D0225132B261501;
                            }
                            else
                            {
                                break;
                            }
                        }
                        else if( $totalWin == 0 && $winType == 'none' ) 
                        {
                            break;
                        }
                    }
                    if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'respin' && $totalWin <= $slotSettings->GetBank((isset($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent']) ? $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] : '')) ) 
                    {
                        break;
                    }
                }
                if( $totalWin > 0 ) 
                {
                    $slotSettings->SetBalance($totalWin);
                    $slotSettings->SetBank((isset($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent']) ? $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] : ''), -1 * $totalWin);
                }
                $_obf_0D23292E1910310B2D0F382A090D063F2A132521111C32 = $totalWin;
                if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData('FengKuangMaJiangBonusWin', $slotSettings->GetGameData('FengKuangMaJiangBonusWin') + $totalWin);
                    $slotSettings->SetGameData('FengKuangMaJiangTotalWin', $totalWin);
                    $_obf_0D2C3832163F282A5C100B120D1405400B1F0A131C1201 = '"currentScene":"freeSpins","multiplier":1,"collapsingCount": 0,"collapsingWin":0,"isReSpin":false,"freeSpinsCount":' . ($slotSettings->GetGameData('FengKuangMaJiangFreeGames') - $slotSettings->GetGameData('FengKuangMaJiangCurrentFreeGame')) . ',"freeSpinsWin":' . $slotSettings->GetGameData('FengKuangMaJiangBonusWin') . ',"initialFreeSpinWin":' . $slotSettings->GetGameData('FengKuangMaJiangStartBonusWin') . ',"initialFreeSpinsCount":' . $slotSettings->slotFreeCount . ',"totalFreeSpinsCount":' . $slotSettings->GetGameData('FengKuangMaJiangFreeGames');
                    $_obf_0D041F24253F082C2819131524043726312E1536091F01 = '' . $_obf_0D18322F1D343B3D031D301B09113719372C0736301A11;
                    $roundEnded = 'false';
                    $_obf_0D08330E13262D0D2E171C29040406302D3014072B2922 = 'freeSpins';
                    $_obf_0D370E2C1D3D061C332C150811021D270B0A270D2D0632 = 'freeSpins';
                    if( $totalWin > 0 || $_obf_0D18322F1D343B3D031D301B09113719372C0736301A11 != '' ) 
                    {
                        $slotSettings->SetGameData('FengKuangMaJiangIsReSpin', 1);
                        $slotSettings->SetGameData('FengKuangMaJiangReSpinCount', 1);
                        $_obf_0D2C3832163F282A5C100B120D1405400B1F0A131C1201 = '"currentScene":"freeSpins","multiplier":1,"collapsingCount": ' . $slotSettings->GetGameData('FengKuangMaJiangReSpinCount') . ',"collapsingWin":' . $slotSettings->GetGameData('FengKuangMaJiangTotalWin') . ',"isReSpin":true,"freeSpinsCount":' . ($slotSettings->GetGameData('FengKuangMaJiangFreeGames') - $slotSettings->GetGameData('FengKuangMaJiangCurrentFreeGame') + 1) . ',"freeSpinsWin":' . $slotSettings->GetGameData('FengKuangMaJiangBonusWin') . ',"initialFreeSpinWin":' . $slotSettings->GetGameData('FengKuangMaJiangStartBonusWin') . ',"initialFreeSpinsCount":' . $slotSettings->slotFreeCount . ',"totalFreeSpinsCount":' . $slotSettings->GetGameData('FengKuangMaJiangFreeGames');
                        $roundEnded = 'false';
                    }
                    if( $slotSettings->GetGameData('FengKuangMaJiangFreeGames') <= $slotSettings->GetGameData('FengKuangMaJiangCurrentFreeGame') && $slotSettings->GetGameData('FengKuangMaJiangCurrentFreeGame') > 0 && $totalWin <= 0 ) 
                    {
                        $_obf_0D2C3832163F282A5C100B120D1405400B1F0A131C1201 = '"currentScene":"main","multiplier":1,"freeSpinsWin":' . $slotSettings->GetGameData('FengKuangMaJiangBonusWin') . ',"freeSpinsCount":0,"initialFreeSpinWin":' . $slotSettings->GetGameData('FengKuangMaJiangStartBonusWin') . ',"initialFreeSpinsCount":' . $slotSettings->slotFreeCount . ',"totalFreeSpinsCount":' . $slotSettings->GetGameData('FengKuangMaJiangFreeGames');
                        $_obf_0D041F24253F082C2819131524043726312E1536091F01 = '{"id":"freeSpinsEnd","reels":{"set":"main","view":[[6,11,1,4,6],[9,5,8,11,9],[3,9,11,4,4],[9,6,8,7,5]],"positions":[43,82,23,29,66]}}';
                        $roundEnded = 'true';
                        $_obf_0D08330E13262D0D2E171C29040406302D3014072B2922 = 'freeSpins';
                        $slotSettings->SetGameData('FengKuangMaJiangBonusWin', 0);
                        $slotSettings->SetGameData('FengKuangMaJiangFreeGames', 0);
                        $slotSettings->SetGameData('FengKuangMaJiangCurrentFreeGame', 0);
                        $slotSettings->SetGameData('FengKuangMaJiangTotalWin', 0);
                        $slotSettings->SetGameData('FengKuangMaJiangFreeBalance', 0);
                    }
                }
                else if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'respin' ) 
                {
                    $slotSettings->SetGameData('FengKuangMaJiangBonusWin', $slotSettings->GetGameData('FengKuangMaJiangBonusWin') + $totalWin);
                    $slotSettings->SetGameData('FengKuangMaJiangTotalWin', $slotSettings->GetGameData('FengKuangMaJiangTotalWin') + $totalWin);
                    if( $_obf_0D2433275B04240E5C2B30283B045C37031B063C342E22 ) 
                    {
                        $scene = 'freeSpins';
                        $_obf_0D1C231A0E031F31322210232613395C211818212F2122 = ',"freeSpinsCount":' . ($slotSettings->GetGameData('FengKuangMaJiangFreeGames') - $slotSettings->GetGameData('FengKuangMaJiangCurrentFreeGame')) . ',"freeSpinsWin":' . $slotSettings->GetGameData('FengKuangMaJiangBonusWin') . ',"initialFreeSpinWin":' . $slotSettings->GetGameData('FengKuangMaJiangStartBonusWin') . ',"initialFreeSpinsCount":' . $slotSettings->slotFreeCount . ',"totalFreeSpinsCount":' . $slotSettings->GetGameData('FengKuangMaJiangFreeGames');
                        $roundEnded = 'false';
                        $_obf_0D08093040090E063B5C26132F360D2F330E5C09261C22 = $_obf_0D3E1E10392C18192A17311514325C2E132A29390E2E22;
                    }
                    else
                    {
                        $scene = 'main';
                        $_obf_0D1C231A0E031F31322210232613395C211818212F2122 = '';
                        $roundEnded = 'true';
                        $_obf_0D08093040090E063B5C26132F360D2F330E5C09261C22 = 1;
                    }
                    $_obf_0D2C3832163F282A5C100B120D1405400B1F0A131C1201 = '"currentScene":"' . $scene . '","multiplier":' . $_obf_0D08093040090E063B5C26132F360D2F330E5C09261C22 . ',"collapsingCount": 0,"collapsingWin":' . $slotSettings->GetGameData('FengKuangMaJiangTotalWin') . ',"isReSpin":false' . $_obf_0D1C231A0E031F31322210232613395C211818212F2122;
                    $_obf_0D041F24253F082C2819131524043726312E1536091F01 = '' . $_obf_0D18322F1D343B3D031D301B09113719372C0736301A11;
                    $_obf_0D08330E13262D0D2E171C29040406302D3014072B2922 = $scene;
                    $_obf_0D370E2C1D3D061C332C150811021D270B0A270D2D0632 = $scene;
                    if( $slotSettings->GetGameData('FengKuangMaJiangFreeGames') <= $slotSettings->GetGameData('FengKuangMaJiangCurrentFreeGame') && $slotSettings->GetGameData('FengKuangMaJiangCurrentFreeGame') > 0 && $totalWin <= 0 ) 
                    {
                        $_obf_0D2C3832163F282A5C100B120D1405400B1F0A131C1201 = '"currentScene":"main","multiplier":1,"freeSpinsWin":' . $slotSettings->GetGameData('FengKuangMaJiangBonusWin') . ',"freeSpinsCount":0,"initialFreeSpinWin":' . $slotSettings->GetGameData('FengKuangMaJiangStartBonusWin') . ',"initialFreeSpinsCount":' . $slotSettings->slotFreeCount . ',"totalFreeSpinsCount":' . $slotSettings->GetGameData('FengKuangMaJiangFreeGames');
                        $_obf_0D041F24253F082C2819131524043726312E1536091F01 = '{"id":"freeSpinsEnd","reels":{"set":"main","view":[[6,11,1,4,6],[9,5,8,11,9],[3,9,11,4,4],[9,6,8,7,5]],"positions":[43,82,23,29,66]}}';
                        $roundEnded = 'true';
                        $_obf_0D08330E13262D0D2E171C29040406302D3014072B2922 = 'freeSpins';
                        $slotSettings->SetGameData('FengKuangMaJiangBonusWin', 0);
                        $slotSettings->SetGameData('FengKuangMaJiangFreeGames', 0);
                        $slotSettings->SetGameData('FengKuangMaJiangCurrentFreeGame', 0);
                        $slotSettings->SetGameData('FengKuangMaJiangTotalWin', 0);
                        $slotSettings->SetGameData('FengKuangMaJiangFreeBalance', 0);
                    }
                    if( $totalWin > 0 || $_obf_0D18322F1D343B3D031D301B09113719372C0736301A11 != '' ) 
                    {
                        $slotSettings->SetGameData('FengKuangMaJiangIsReSpin', 1);
                        $slotSettings->SetGameData('FengKuangMaJiangReSpinCount', $slotSettings->GetGameData('FengKuangMaJiangReSpinCount') + 1);
                        $_obf_0D2C3832163F282A5C100B120D1405400B1F0A131C1201 = '"currentScene":"' . $scene . '","multiplier":' . $_obf_0D08093040090E063B5C26132F360D2F330E5C09261C22 . ',"collapsingCount": ' . $slotSettings->GetGameData('FengKuangMaJiangReSpinCount') . ',"collapsingWin":' . $slotSettings->GetGameData('FengKuangMaJiangTotalWin') . ',"isReSpin":true' . $_obf_0D1C231A0E031F31322210232613395C211818212F2122;
                        $roundEnded = 'false';
                    }
                    else if( $slotSettings->GetGameData('FengKuangMaJiangWaitFreeGames') == 1 ) 
                    {
                        $slotSettings->SetGameData('FengKuangMaJiangWaitFreeGames', 0);
                        $_obf_0D1D19362A30301923032A231E39060715022A39300901 = $slotSettings->GetReelStrips('', $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent']);
                        $_obf_0D3D1D3712343430012B2C2F1426040C17071B250C2711 = '[' . $_obf_0D1D19362A30301923032A231E39060715022A39300901['reel1'][0] . ',' . $_obf_0D1D19362A30301923032A231E39060715022A39300901['reel2'][0] . ',' . $_obf_0D1D19362A30301923032A231E39060715022A39300901['reel3'][0] . ',' . $_obf_0D1D19362A30301923032A231E39060715022A39300901['reel4'][0] . ',' . $_obf_0D1D19362A30301923032A231E39060715022A39300901['reel5'][0] . '],[' . $_obf_0D1D19362A30301923032A231E39060715022A39300901['reel1'][1] . ',' . $_obf_0D1D19362A30301923032A231E39060715022A39300901['reel2'][1] . ',' . $_obf_0D1D19362A30301923032A231E39060715022A39300901['reel3'][1] . ',' . $_obf_0D1D19362A30301923032A231E39060715022A39300901['reel4'][1] . ',' . $_obf_0D1D19362A30301923032A231E39060715022A39300901['reel5'][1] . '],[' . $_obf_0D1D19362A30301923032A231E39060715022A39300901['reel1'][2] . ',' . $_obf_0D1D19362A30301923032A231E39060715022A39300901['reel2'][2] . ',' . $_obf_0D1D19362A30301923032A231E39060715022A39300901['reel3'][2] . ',' . $_obf_0D1D19362A30301923032A231E39060715022A39300901['reel4'][2] . ',' . $_obf_0D1D19362A30301923032A231E39060715022A39300901['reel5'][2] . ']';
                        $_obf_0D041F24253F082C2819131524043726312E1536091F01 = '{"id":"freeSpinsStart","amount":' . $slotSettings->slotFreeCount . ',"reels":{"view":[' . $_obf_0D3D1D3712343430012B2C2F1426040C17071B250C2711 . '],"set":"freeSpins","positions":[' . implode(',', $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['rp']) . ']},"triggeredSceneId":"freeSpins","triggerSymbols":[' . implode(',', $_obf_0D5B0915301B403E0E18271D3C2A351E0D5C1521150E01) . ']}';
                        $_obf_0D2C3832163F282A5C100B120D1405400B1F0A131C1201 = '"currentScene":"freeSpins","multiplier":1,"freeSpinsCount":' . ($slotSettings->GetGameData('FengKuangMaJiangFreeGames') - $slotSettings->GetGameData('FengKuangMaJiangCurrentFreeGame')) . ',"freeSpinsWin":' . $slotSettings->GetGameData('FengKuangMaJiangBonusWin') . ',"initialFreeSpinWin":' . $slotSettings->GetGameData('FengKuangMaJiangStartBonusWin') . ',"initialFreeSpinsCount":' . $slotSettings->slotFreeCount . ',"totalFreeSpinsCount":' . $slotSettings->GetGameData('FengKuangMaJiangFreeGames') . ',"collapsingCount": 0,"collapsingWin":' . $slotSettings->GetGameData('FengKuangMaJiangTotalWin') . ',"isReSpin":false';
                    }
                }
                else if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] != 'freespin' ) 
                {
                    $slotSettings->SetGameData('FengKuangMaJiangWaitFreeGames', 0);
                    $slotSettings->SetGameData('FengKuangMaJiangBonusWin', 0);
                    $slotSettings->SetGameData('FengKuangMaJiangTotalWin', $totalWin);
                    $_obf_0D2C3832163F282A5C100B120D1405400B1F0A131C1201 = '"currentScene":"main","multiplier":1,"collapsingCount": 0,"collapsingWin":0,"isReSpin":false';
                    $_obf_0D041F24253F082C2819131524043726312E1536091F01 = '' . $_obf_0D18322F1D343B3D031D301B09113719372C0736301A11;
                    $roundEnded = 'true';
                    $_obf_0D08330E13262D0D2E171C29040406302D3014072B2922 = 'main';
                    $_obf_0D370E2C1D3D061C332C150811021D270B0A270D2D0632 = 'main';
                    if( $totalWin > 0 || $_obf_0D18322F1D343B3D031D301B09113719372C0736301A11 != '' ) 
                    {
                        $slotSettings->SetGameData('FengKuangMaJiangIsReSpin', 1);
                        $slotSettings->SetGameData('FengKuangMaJiangReSpinCount', 1);
                        $_obf_0D2C3832163F282A5C100B120D1405400B1F0A131C1201 = '"currentScene":"main","multiplier":1,"collapsingCount": ' . $slotSettings->GetGameData('FengKuangMaJiangReSpinCount') . ',"collapsingWin":' . $slotSettings->GetGameData('FengKuangMaJiangTotalWin') . ',"isReSpin":true';
                        $roundEnded = 'false';
                    }
                }
                if( $_obf_0D0B230B342E0C0727115B043F283E2137182D312A3D11 >= 3 ) 
                {
                    if( $slotSettings->GetGameData('FengKuangMaJiangFreeGames') > 0 ) 
                    {
                        $slotSettings->SetGameData('FengKuangMaJiangFreeBalance', $Balance);
                        $slotSettings->SetGameData('FengKuangMaJiangBonusWin', $totalWin);
                        $slotSettings->SetGameData('FengKuangMaJiangFreeGames', $slotSettings->GetGameData('FengKuangMaJiangFreeGames') + $slotSettings->slotFreeCount);
                    }
                    else
                    {
                        $slotSettings->SetGameData('FengKuangMaJiangBonusWin', $slotSettings->GetGameData('FengKuangMaJiangBonusWin') + $totalWin);
                        $slotSettings->SetGameData('FengKuangMaJiangFreeGames', $slotSettings->slotFreeCount);
                    }
                    $slotSettings->SetGameData('FengKuangMaJiangWaitFreeGames', 1);
                }
                for( $r = 1; $r <= 5; $r++ ) 
                {
                    for( $_obf_0D13090C3F3C3624123E1A2C091F31232304270B023B32 = 0; $_obf_0D13090C3F3C3624123E1A2C091F31232304270B023B32 <= 2; $_obf_0D13090C3F3C3624123E1A2C091F31232304270B023B32++ ) 
                    {
                        if( $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel' . $r][$_obf_0D13090C3F3C3624123E1A2C091F31232304270B023B32] == $_obf_0D2B2F2802280E223138132C0B310F3C0A2D3328275C22 && $_obf_0D0B230B342E0C0727115B043F283E2137182D312A3D11 >= 3 ) 
                        {
                            $_obf_0D3C3915080E3D0C3F07400E0F0B05302614072F380B01[$r - 1][$_obf_0D13090C3F3C3624123E1A2C091F31232304270B023B32] = -1;
                        }
                        if( $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel' . $r][$_obf_0D13090C3F3C3624123E1A2C091F31232304270B023B32] == $_obf_0D2B2C361A1519230B37081F290E3430171007181F1522 && $_obf_0D28301D333F2827165C2511011438180336175B1E2A01 >= 2 ) 
                        {
                            $_obf_0D3C3915080E3D0C3F07400E0F0B05302614072F380B01[$r - 1][$_obf_0D13090C3F3C3624123E1A2C091F31232304270B023B32] = -1;
                        }
                    }
                }
                $_obf_0D265B3F1C3639042D1E30283727011A400E1626053832 = $slotSettings->GetGameData('FengKuangMaJiangReelsMap');
                $slotSettings->SetGameData('FengKuangMaJiangChangeMap', $_obf_0D3C3915080E3D0C3F07400E0F0B05302614072F380B01);
                $slotSettings->SetGameData('FengKuangMaJiangReelsMap', $_obf_0D3C090E192F3D26100429351F02123B310C3504040132);
                $_obf_0D140A1C122D065B2A1629031B280E272815082A0D2122 = '' . json_encode($_obf_0D3C090E192F3D26100429351F02123B310C3504040132) . '';
                $_obf_0D5B0C352E1F1E0317255C3C31103F3604031D091F3701 = '' . json_encode($_obf_0D37321A2B3F180F0E28173F375C093F0C320B29323011) . '';
                $_obf_0D1B370B073F123C3210300C0336351F3E072217172A22 = '' . json_encode($slotSettings->Jackpots) . '';
                $_obf_0D33120B1B18292D30293B191C3D383E3D2D0C195B2101 = implode(',', $_obf_0D181C103526150D021B2C0E1A1F211F3F3E2A15363632);
                $response = '{"gameSession":"","dbg":"' . $_obf_0D0B230B342E0C0727115B043F283E2137182D312A3D11 . '|' . $_obf_0D28301D333F2827165C2511011438180336175B1E2A01 . '|' . $slotSettings->GetGameData('FengKuangMaJiangFreeGames') . '","balance":{"currency":"USD","amount":' . $slotSettings->GetBalance() . ',"real":{"amount":' . $Balance . '},"bonus":{"amount":0}},"result":{"request":"spin","stake":{"lines":' . $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotLines'] . ',"bet":' . $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] . ',"coin":1},"totalBet":' . ($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotLines']) . ',"totalWin":' . $totalWin . ',"scene":"' . $_obf_0D08330E13262D0D2E171C29040406302D3014072B2922 . '","multiplier":1,"state":{' . $_obf_0D2C3832163F282A5C100B120D1405400B1F0A131C1201 . '},"reels":{"set":"' . $_obf_0D370E2C1D3D061C332C150811021D270B0A270D2D0632 . '","positions":[' . implode(',', $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['rp']) . '],"view":[[' . $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel1'][0] . ',' . $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel2'][0] . ',' . $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel3'][0] . ',' . $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel4'][0] . ',' . $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel5'][0] . '],[' . $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel1'][1] . ',' . $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel2'][1] . ',' . $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel3'][1] . ',' . $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel4'][1] . ',' . $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel5'][1] . '],[' . $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel1'][2] . ',' . $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel2'][2] . ',' . $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel3'][2] . ',' . $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel4'][2] . ',' . $_obf_0D3C090E192F3D26100429351F02123B310C3504040132['reel5'][2] . ']]},"rewards":[' . $_obf_0D33120B1B18292D30293B191C3D383E3D2D0C195B2101 . '],"events":[' . $_obf_0D041F24253F082C2819131524043726312E1536091F01 . '],"roundEnded":' . $roundEnded . ',"version":"1.0.2"},"requestId":1,"roundEnded":' . $roundEnded . '}';
                $_obf_0D2D350C1338352E3F0236115C1407341C0926053F2211 = '{"responseEvent":"spin","responseType":"' . $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] . '","lastResponse":' . $response . ',"serverResponse":{"WaitFreeGames":' . $slotSettings->GetGameData('FengKuangMaJiangWaitFreeGames') . ',"StartBonusWin":' . $slotSettings->GetGameData('FengKuangMaJiangStartBonusWin') . ',"lines":' . $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotLines'] . ',"bet":' . $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] . ',"totalFreeGames":' . $slotSettings->GetGameData('FengKuangMaJiangFreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData('FengKuangMaJiangCurrentFreeGame') . ',"Balance":' . $Balance . ',"afterBalance":' . $slotSettings->GetBalance() . ',"bonusWin":' . $slotSettings->GetGameData('FengKuangMaJiangBonusWin') . ',"totalWin":' . $totalWin . ',"winLines":[' . $_obf_0D33120B1B18292D30293B191C3D383E3D2D0C195B2101 . '],"bonusInfo":' . $_obf_0D033835123E051D331E010A3C300C332C34021F052801 . ',"Jackpots":' . $_obf_0D1B370B073F123C3210300C0336351F3E072217172A22 . ',"reelsSymbols":' . $_obf_0D140A1C122D065B2A1629031B280E272815082A0D2122 . '}}';
                $slotSettings->SaveLogReport($_obf_0D2D350C1338352E3F0236115C1407341C0926053F2211, $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'], $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotLines'], $_obf_0D23292E1910310B2D0F382A090D063F2A132521111C32, $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent']);
            }
            $slotSettings->SaveGameData();
            \DB::commit();
            return $response;
        }
    }

}

<?php 
namespace VanguardLTE\Games\AmazingStarsGTM
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
            if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'update' ) 
            {
                $response = '{"responseEvent":"error","responseType":"' . $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] . '","serverResponse":"' . $slotSettings->GetBalance() . '"}';
                exit( $response );
            }
            if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'bet' || $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'freespin' || $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'respin' ) 
            {
                if( !in_array($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotLines'], $slotSettings->gameLine) || !in_array($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'], $slotSettings->Bet) ) 
                {
                    $response = '{"responseEvent":"error","responseType":"' . $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] . '","serverResponse":"invalid bet state"}';
                    exit( $response );
                }
                if( $slotSettings->GetBalance() < ($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotLines'] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet']) && $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'bet' ) 
                {
                    $response = '{"responseEvent":"error","responseType":"' . $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] . '","serverResponse":"invalid balance"}';
                    exit( $response );
                }
                if( $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') <= $slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame') && $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'freespin' ) 
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
            if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'getSettings' ) 
            {
                $lastEvent = $slotSettings->GetHistory();
                if( $lastEvent != 'NULL' ) 
                {
                    if( isset($lastEvent->serverResponse->expSymbol) ) 
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'ExpSymbol', $lastEvent->serverResponse->expSymbol);
                    }
                    if( isset($lastEvent->serverResponse->bonusWin) ) 
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->bonusWin);
                    }
                    else
                    {
                        $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', $lastEvent->serverResponse->totalWin);
                    }
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $lastEvent->serverResponse->totalFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentFreeGame', $lastEvent->serverResponse->currentFreeGames);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $lastEvent->serverResponse->totalWin);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeBalance', $lastEvent->serverResponse->Balance);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ScatterWin', $lastEvent->serverResponse->scattersWinTmp);
                }
                $slotSettings->slotJackpot0 = $slotSettings->slotJackpot[0];
                $slotSettings->slotJackpot = $slotSettings->slotJackpot[1];
                $_obf_0D300C2F21350336261622142A322E0C270C0A1F2F0422 = json_encode($slotSettings);
                $lang = json_encode(\Lang::get('games.' . $game));
                $response = '{"responseEvent":"getSettings","slotLanguage":' . $lang . ',"serverResponse":' . $_obf_0D300C2F21350336261622142A322E0C270C0A1F2F0422 . '}';
            }
            else if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'gamble5GetUserCards' ) 
            {
                $Balance = $slotSettings->GetBalance();
                $_obf_0D03381F212715073B0D165C28180E2C193C0A19283922 = rand(1, $slotSettings->GetGambleSettings());
                $_obf_0D25035C31183316381216122811401A1F2A17243E2B22 = $slotSettings->GetGameData('AmazingStarsGTMDealerCard');
                $totalWin = $slotSettings->GetGameData('AmazingStarsGTMTotalWin');
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
                $slotSettings->SetGameData('AmazingStarsGTMTotalWin', $totalWin);
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
                $slotSettings->SetGameData('AmazingStarsGTMDealerCard', $_obf_0D1A28330223330201021115084008123B0F213C102922);
                $_obf_0D25035C31183316381216122811401A1F2A17243E2B22 = $_obf_0D07380D0B2F2F240918081F3F2A042730295C091F2132[$_obf_0D1A28330223330201021115084008123B0F213C102922] . $_obf_0D112B16351A0D0D02250E1F401526150C21152B143932[rand(0, 3)];
                $_obf_0D300C2F21350336261622142A322E0C270C0A1F2F0422 = '{"dealerCard":"' . $_obf_0D25035C31183316381216122811401A1F2A17243E2B22 . '"}';
                $response = '{"responseEvent":"gamble5DealerCard","serverResponse":' . $_obf_0D300C2F21350336261622142A322E0C270C0A1F2F0422 . '}';
            }
            else if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'slotGamble' ) 
            {
                $Balance = $slotSettings->GetBalance();
                $_obf_0D03381F212715073B0D165C28180E2C193C0A19283922 = rand(1, $slotSettings->GetGambleSettings());
                $_obf_0D25035C31183316381216122811401A1F2A17243E2B22 = '';
                $totalWin = $slotSettings->GetGameData('AmazingStarsGTMTotalWin');
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
                $slotSettings->SetGameData('AmazingStarsGTMTotalWin', $totalWin);
                $slotSettings->SetBalance($_obf_0D33130E1F150A28331B2F322B291E402639242D2B2D22);
                $slotSettings->SetBank((isset($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent']) ? $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] : ''), $_obf_0D33130E1F150A28331B2F322B291E402639242D2B2D22 * -1);
                $_obf_0D14303C231C032D241D0B0B3536181C110C0D0A2B1932 = $slotSettings->GetBalance();
                $_obf_0D300C2F21350336261622142A322E0C270C0A1F2F0422 = '{"dealerCard":"' . $_obf_0D25035C31183316381216122811401A1F2A17243E2B22 . '","gambleState":"' . $_obf_0D0E2C2E373C171C3B0B2905400E372723283E18043511 . '","totalWin":' . $totalWin . ',"afterBalance":' . $_obf_0D14303C231C032D241D0B0B3536181C110C0D0A2B1932 . ',"Balance":' . $Balance . '}';
                $response = '{"responseEvent":"gambleResult","serverResponse":' . $_obf_0D300C2F21350336261622142A322E0C270C0A1F2F0422 . '}';
                $slotSettings->SaveLogReport($response, $_obf_0D3310323F3F07041133133D263014342B230C260D1F11, 1, $_obf_0D33130E1F150A28331B2F322B291E402639242D2B2D22, $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent']);
            }
            else if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'bet' || $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'freespin' ) 
            {
                $linesId = [];
                $linesId[0] = [
                    2, 
                    2, 
                    2, 
                    2, 
                    2
                ];
                $linesId[1] = [
                    1, 
                    1, 
                    1, 
                    1, 
                    1
                ];
                $linesId[2] = [
                    3, 
                    3, 
                    3, 
                    3, 
                    3
                ];
                $linesId[3] = [
                    1, 
                    2, 
                    3, 
                    2, 
                    1
                ];
                $linesId[4] = [
                    3, 
                    2, 
                    1, 
                    2, 
                    3
                ];
                $linesId[5] = [
                    2, 
                    3, 
                    3, 
                    3, 
                    2
                ];
                $linesId[6] = [
                    2, 
                    1, 
                    1, 
                    1, 
                    2
                ];
                $linesId[7] = [
                    3, 
                    3, 
                    2, 
                    1, 
                    1
                ];
                $linesId[8] = [
                    1, 
                    1, 
                    2, 
                    3, 
                    3
                ];
                $linesId[9] = [
                    3, 
                    2, 
                    2, 
                    2, 
                    1
                ];
                $_obf_0D31103E3B3D1E1A27051D1540063B0528291C5C1A0D22 = $slotSettings->GetSpinSettings($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'], $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotLines'], $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotLines']);
                $winType = $_obf_0D31103E3B3D1E1A27051D1540063B0528291C5C1A0D22[0];
                $_obf_0D3B3C113639391705311B0F12323C3B3B250C1A142401 = $_obf_0D31103E3B3D1E1A27051D1540063B0528291C5C1A0D22[1];
                if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] != 'freespin' ) 
                {
                    if( !isset($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent']) ) 
                    {
                        $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] = 'bet';
                    }
                    $_obf_0D1A310E2B25282C1A01072A06330C1A173E3437092622 = ($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotLines']) / 100 * $slotSettings->GetPercent();
                    $slotSettings->SetBank((isset($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent']) ? $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] : ''), $_obf_0D1A310E2B25282C1A01072A06330C1A173E3437092622, $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent']);
                    $_obf_0D262A401E2428360E103910312E0E2A2D04280B345B11 = $slotSettings->UpdateJackpots($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotLines'], false);
                    $slotSettings->SetBalance(-1 * ($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotLines']), $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent']);
                    $bonusMpl = 1;
                    $slotSettings->SetGameData('AmazingStarsGTMBonusWin', 0);
                    $slotSettings->SetGameData('AmazingStarsGTMFreeGames', 0);
                    $slotSettings->SetGameData('AmazingStarsGTMCurrentFreeGame', 0);
                    $slotSettings->SetGameData('AmazingStarsGTMTotalWin', 0);
                    $slotSettings->SetGameData('AmazingStarsGTMFreeBalance', 0);
                    $slotSettings->SetGameData('AmazingStarsGTMScatterWin', 0);
                    $slotSettings->SetGameData('AmazingStarsGTMFreeStacked', [
                        'false', 
                        'false', 
                        'false', 
                        'false', 
                        'false', 
                        'false', 
                        'false', 
                        'false', 
                        'false', 
                        'false', 
                        'false', 
                        'false', 
                        'false', 
                        'false', 
                        'false', 
                        'false'
                    ]);
                    for( $ii = 0; $ii < 16; $ii++ ) 
                    {
                        $slotSettings->SetGameData('AmazingStarsGTMFreeStacked.' . $ii, 'false');
                    }
                }
                else
                {
                    $slotSettings->SetGameData('AmazingStarsGTMCurrentFreeGame', $slotSettings->GetGameData('AmazingStarsGTMCurrentFreeGame') + 1);
                    $bonusMpl = $slotSettings->slotFreeMpl;
                    if( $slotSettings->GetGameData('AmazingStarsGTMFreeGames') == $slotSettings->GetGameData('AmazingStarsGTMCurrentFreeGame') ) 
                    {
                        $_obf_0D262A401E2428360E103910312E0E2A2D04280B345B11 = $slotSettings->UpdateJackpots($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotLines'], true);
                    }
                }
                $Balance = $slotSettings->GetBalance();
                for( $i = 0; $i <= 2000; $i++ ) 
                {
                    $_obf_0D18345B0B3106341901142F1A051E33355C0F1A060E01 = false;
                    $totalWin = 0;
                    $lineWins = [];
                    $cWins = [
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
                    $_obf_0D18345B0B3106341901142F1A051E33355C0F1A060E01 = false;
                    $wild = ['NONE'];
                    $scatter = 'SCAT';
                    $reels = $slotSettings->GetReelStrips($winType, $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent']);
                    if( isset($_obf_0D262A401E2428360E103910312E0E2A2D04280B345B11) && $_obf_0D262A401E2428360E103910312E0E2A2D04280B345B11['isJackPay'] ) 
                    {
                        $_obf_0D37130A2C2C241025221C3437223C310B0D1226133032 = 1;
                        for( $_obf_0D141E31211D2540361A2E225B0B1C01271C5B0B1F2F11 = 1; $_obf_0D141E31211D2540361A2E225B0B1C01271C5B0B1F2F11 <= 5; $_obf_0D141E31211D2540361A2E225B0B1C01271C5B0B1F2F11++ ) 
                        {
                            if( $_obf_0D262A401E2428360E103910312E0E2A2D04280B345B11['isJackId'] == 0 ) 
                            {
                                $_obf_0D3C16222603400110303E28280C3129292D3931103B32 = $slotSettings->PutBonusToLine($_obf_0D141E31211D2540361A2E225B0B1C01271C5B0B1F2F11, $linesId[$_obf_0D37130A2C2C241025221C3437223C310B0D1226133032][$_obf_0D141E31211D2540361A2E225B0B1C01271C5B0B1F2F11 - 1], 'SCAT');
                                $reels['reel' . $_obf_0D141E31211D2540361A2E225B0B1C01271C5B0B1F2F11] = [
                                    'SCAT', 
                                    'SCAT', 
                                    'SCAT', 
                                    ''
                                ];
                                $reels['rp'][$_obf_0D141E31211D2540361A2E225B0B1C01271C5B0B1F2F11 - 1] = $_obf_0D3C16222603400110303E28280C3129292D3931103B32['rp'];
                                $winType = 'none';
                            }
                            else
                            {
                                $_obf_0D3C16222603400110303E28280C3129292D3931103B32 = $slotSettings->PutBonusToLine($_obf_0D141E31211D2540361A2E225B0B1C01271C5B0B1F2F11, $linesId[$_obf_0D37130A2C2C241025221C3437223C310B0D1226133032][$_obf_0D141E31211D2540361A2E225B0B1C01271C5B0B1F2F11 - 1], 'P_1');
                                $reels['reel' . $_obf_0D141E31211D2540361A2E225B0B1C01271C5B0B1F2F11] = $_obf_0D3C16222603400110303E28280C3129292D3931103B32['reel'];
                                $reels['rp'][$_obf_0D141E31211D2540361A2E225B0B1C01271C5B0B1F2F11 - 1] = $_obf_0D3C16222603400110303E28280C3129292D3931103B32['rp'];
                                $winType = 'none';
                            }
                        }
                    }
                    $_obf_0D0C353C231930293E1C32222A282208283E03311D0C01 = $reels;
                    if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'freespin' ) 
                    {
                        $_obf_0D250D0F38353928051A170C33210E211810162A181811 = 1;
                        $_obf_0D273D3536331C130E1334343C3E161A292A3B15190801 = $slotSettings->GetGameData('AmazingStarsGTMFreeStacked');
                        for( $ii = 1; $ii <= 5; $ii++ ) 
                        {
                            if( $_obf_0D273D3536331C130E1334343C3E161A292A3B15190801[$_obf_0D250D0F38353928051A170C33210E211810162A181811] == 'true' ) 
                            {
                                $reels['reel' . $ii][0] = 'SCAT';
                            }
                            $_obf_0D250D0F38353928051A170C33210E211810162A181811++;
                            if( $_obf_0D273D3536331C130E1334343C3E161A292A3B15190801[$_obf_0D250D0F38353928051A170C33210E211810162A181811] == 'true' ) 
                            {
                                $reels['reel' . $ii][1] = 'SCAT';
                            }
                            $_obf_0D250D0F38353928051A170C33210E211810162A181811++;
                            if( $_obf_0D273D3536331C130E1334343C3E161A292A3B15190801[$_obf_0D250D0F38353928051A170C33210E211810162A181811] == 'true' ) 
                            {
                                $reels['reel' . $ii][2] = 'SCAT';
                            }
                            $_obf_0D250D0F38353928051A170C33210E211810162A181811++;
                        }
                    }
                    for( $k = 0; $k < $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotLines']; $k++ ) 
                    {
                        $_obf_0D0207283039073919263232090A382F3D26101F0D1E11 = '';
                        for( $j = 0; $j < count($slotSettings->SymbolGame); $j++ ) 
                        {
                            $_obf_0D011C142C3C37263F351C4012170A074027083F321132 = $slotSettings->SymbolGame[$j];
                            if( $_obf_0D011C142C3C37263F351C4012170A074027083F321132 == $scatter || !isset($slotSettings->Paytable[$_obf_0D011C142C3C37263F351C4012170A074027083F321132]) || $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'freespin' ) 
                            {
                            }
                            else
                            {
                                $s = [];
                                $s[0] = $reels['reel1'][$linesId[$k][0] - 1];
                                $s[1] = $reels['reel2'][$linesId[$k][1] - 1];
                                $s[2] = $reels['reel3'][$linesId[$k][2] - 1];
                                $s[3] = $reels['reel4'][$linesId[$k][3] - 1];
                                $s[4] = $reels['reel5'][$linesId[$k][4] - 1];
                                if( $s[0] == $_obf_0D011C142C3C37263F351C4012170A074027083F321132 || in_array($s[0], $wild) ) 
                                {
                                    $mpl = 1;
                                    $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01 = $slotSettings->Paytable[$_obf_0D011C142C3C37263F351C4012170A074027083F321132][1] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] * $mpl * $bonusMpl;
                                    if( $cWins[$k] < $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01 ) 
                                    {
                                        $cWins[$k] = $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01;
                                        $_obf_0D0207283039073919263232090A382F3D26101F0D1E11 = '{"Count":1,"Line":' . $k . ',"Win":' . $cWins[$k] . ',"stepWin":' . ($cWins[$k] + $totalWin + $slotSettings->GetGameData('AmazingStarsGTMBonusWin')) . ',"winReel1":[' . ($linesId[$k][0] - 1) . ',"' . $s[0] . '"],"winReel2":["none","none"],"winReel3":["none","none"],"winReel4":["none","none"],"winReel5":["none","none"]}';
                                    }
                                }
                                if( ($s[0] == $_obf_0D011C142C3C37263F351C4012170A074027083F321132 || in_array($s[0], $wild)) && ($s[1] == $_obf_0D011C142C3C37263F351C4012170A074027083F321132 || in_array($s[1], $wild)) ) 
                                {
                                    $mpl = 1;
                                    if( in_array($s[0], $wild) && in_array($s[1], $wild) ) 
                                    {
                                        $mpl = 1;
                                    }
                                    else if( in_array($s[0], $wild) || in_array($s[1], $wild) ) 
                                    {
                                        $mpl = $slotSettings->slotWildMpl;
                                        for( $_obf_0D0C021818145B101222043F3C051C231B1C5B231A3522 = 0; $_obf_0D0C021818145B101222043F3C051C231B1C5B231A3522 < 2; $_obf_0D0C021818145B101222043F3C051C231B1C5B231A3522++ ) 
                                        {
                                            if( in_array($s[$_obf_0D0C021818145B101222043F3C051C231B1C5B231A3522], $wild) ) 
                                            {
                                                $s[$_obf_0D0C021818145B101222043F3C051C231B1C5B231A3522] = 'P_1';
                                            }
                                        }
                                    }
                                    $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01 = $slotSettings->Paytable[$_obf_0D011C142C3C37263F351C4012170A074027083F321132][2] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] * $mpl * $bonusMpl;
                                    if( $cWins[$k] < $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01 ) 
                                    {
                                        $cWins[$k] = $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01;
                                        $_obf_0D0207283039073919263232090A382F3D26101F0D1E11 = '{"Count":2,"Line":' . $k . ',"Win":' . $cWins[$k] . ',"stepWin":' . ($cWins[$k] + $totalWin + $slotSettings->GetGameData('AmazingStarsGTMBonusWin')) . ',"winReel1":[' . ($linesId[$k][0] - 1) . ',"' . $s[0] . '"],"winReel2":[' . ($linesId[$k][1] - 1) . ',"' . $s[1] . '"],"winReel3":["none","none"],"winReel4":["none","none"],"winReel5":["none","none"]}';
                                    }
                                }
                                $s[0] = $reels['reel1'][$linesId[$k][0] - 1];
                                $s[1] = $reels['reel2'][$linesId[$k][1] - 1];
                                $s[2] = $reels['reel3'][$linesId[$k][2] - 1];
                                $s[3] = $reels['reel4'][$linesId[$k][3] - 1];
                                $s[4] = $reels['reel5'][$linesId[$k][4] - 1];
                                if( ($s[0] == $_obf_0D011C142C3C37263F351C4012170A074027083F321132 || in_array($s[0], $wild)) && ($s[1] == $_obf_0D011C142C3C37263F351C4012170A074027083F321132 || in_array($s[1], $wild)) && ($s[2] == $_obf_0D011C142C3C37263F351C4012170A074027083F321132 || in_array($s[2], $wild)) ) 
                                {
                                    $mpl = 1;
                                    if( in_array($s[0], $wild) && in_array($s[1], $wild) && in_array($s[2], $wild) ) 
                                    {
                                        $mpl = 1;
                                    }
                                    else if( in_array($s[0], $wild) || in_array($s[1], $wild) || in_array($s[2], $wild) ) 
                                    {
                                        $mpl = $slotSettings->slotWildMpl;
                                        for( $_obf_0D0C021818145B101222043F3C051C231B1C5B231A3522 = 0; $_obf_0D0C021818145B101222043F3C051C231B1C5B231A3522 < 3; $_obf_0D0C021818145B101222043F3C051C231B1C5B231A3522++ ) 
                                        {
                                            if( in_array($s[$_obf_0D0C021818145B101222043F3C051C231B1C5B231A3522], $wild) ) 
                                            {
                                                $s[$_obf_0D0C021818145B101222043F3C051C231B1C5B231A3522] = 'P_1';
                                            }
                                        }
                                    }
                                    $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01 = $slotSettings->Paytable[$_obf_0D011C142C3C37263F351C4012170A074027083F321132][3] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] * $mpl * $bonusMpl;
                                    if( $cWins[$k] < $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01 ) 
                                    {
                                        $cWins[$k] = $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01;
                                        $_obf_0D0207283039073919263232090A382F3D26101F0D1E11 = '{"Count":3,"Line":' . $k . ',"Win":' . $cWins[$k] . ',"stepWin":' . ($cWins[$k] + $totalWin + $slotSettings->GetGameData('AmazingStarsGTMBonusWin')) . ',"winReel1":[' . ($linesId[$k][0] - 1) . ',"' . $s[0] . '"],"winReel2":[' . ($linesId[$k][1] - 1) . ',"' . $s[1] . '"],"winReel3":[' . ($linesId[$k][2] - 1) . ',"' . $s[2] . '"],"winReel4":["none","none"],"winReel5":["none","none"]}';
                                    }
                                }
                                $s[0] = $reels['reel1'][$linesId[$k][0] - 1];
                                $s[1] = $reels['reel2'][$linesId[$k][1] - 1];
                                $s[2] = $reels['reel3'][$linesId[$k][2] - 1];
                                $s[3] = $reels['reel4'][$linesId[$k][3] - 1];
                                $s[4] = $reels['reel5'][$linesId[$k][4] - 1];
                                if( ($s[0] == $_obf_0D011C142C3C37263F351C4012170A074027083F321132 || in_array($s[0], $wild)) && ($s[1] == $_obf_0D011C142C3C37263F351C4012170A074027083F321132 || in_array($s[1], $wild)) && ($s[2] == $_obf_0D011C142C3C37263F351C4012170A074027083F321132 || in_array($s[2], $wild)) && ($s[3] == $_obf_0D011C142C3C37263F351C4012170A074027083F321132 || in_array($s[3], $wild)) ) 
                                {
                                    $mpl = 1;
                                    if( in_array($s[0], $wild) && in_array($s[1], $wild) && in_array($s[2], $wild) && in_array($s[3], $wild) ) 
                                    {
                                        $mpl = 1;
                                    }
                                    else if( in_array($s[0], $wild) || in_array($s[1], $wild) || in_array($s[2], $wild) || in_array($s[3], $wild) ) 
                                    {
                                        $mpl = $slotSettings->slotWildMpl;
                                        for( $_obf_0D0C021818145B101222043F3C051C231B1C5B231A3522 = 0; $_obf_0D0C021818145B101222043F3C051C231B1C5B231A3522 < 4; $_obf_0D0C021818145B101222043F3C051C231B1C5B231A3522++ ) 
                                        {
                                            if( in_array($s[$_obf_0D0C021818145B101222043F3C051C231B1C5B231A3522], $wild) ) 
                                            {
                                                $s[$_obf_0D0C021818145B101222043F3C051C231B1C5B231A3522] = 'P_1';
                                            }
                                        }
                                    }
                                    $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01 = $slotSettings->Paytable[$_obf_0D011C142C3C37263F351C4012170A074027083F321132][4] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] * $mpl * $bonusMpl;
                                    if( $cWins[$k] < $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01 ) 
                                    {
                                        $cWins[$k] = $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01;
                                        $_obf_0D0207283039073919263232090A382F3D26101F0D1E11 = '{"Count":4,"Line":' . $k . ',"Win":' . $cWins[$k] . ',"stepWin":' . ($cWins[$k] + $totalWin + $slotSettings->GetGameData('AmazingStarsGTMBonusWin')) . ',"winReel1":[' . ($linesId[$k][0] - 1) . ',"' . $s[0] . '"],"winReel2":[' . ($linesId[$k][1] - 1) . ',"' . $s[1] . '"],"winReel3":[' . ($linesId[$k][2] - 1) . ',"' . $s[2] . '"],"winReel4":[' . ($linesId[$k][3] - 1) . ',"' . $s[3] . '"],"winReel5":["none","none"]}';
                                    }
                                }
                                $s[0] = $reels['reel1'][$linesId[$k][0] - 1];
                                $s[1] = $reels['reel2'][$linesId[$k][1] - 1];
                                $s[2] = $reels['reel3'][$linesId[$k][2] - 1];
                                $s[3] = $reels['reel4'][$linesId[$k][3] - 1];
                                $s[4] = $reels['reel5'][$linesId[$k][4] - 1];
                                if( ($s[0] == $_obf_0D011C142C3C37263F351C4012170A074027083F321132 || in_array($s[0], $wild)) && ($s[1] == $_obf_0D011C142C3C37263F351C4012170A074027083F321132 || in_array($s[1], $wild)) && ($s[2] == $_obf_0D011C142C3C37263F351C4012170A074027083F321132 || in_array($s[2], $wild)) && ($s[3] == $_obf_0D011C142C3C37263F351C4012170A074027083F321132 || in_array($s[3], $wild)) && ($s[4] == $_obf_0D011C142C3C37263F351C4012170A074027083F321132 || in_array($s[4], $wild)) ) 
                                {
                                    $mpl = 1;
                                    if( in_array($s[0], $wild) && in_array($s[1], $wild) && in_array($s[2], $wild) && in_array($s[3], $wild) && in_array($s[4], $wild) ) 
                                    {
                                        $mpl = 1;
                                    }
                                    else if( in_array($s[0], $wild) || in_array($s[1], $wild) || in_array($s[2], $wild) || in_array($s[3], $wild) || in_array($s[4], $wild) ) 
                                    {
                                        $mpl = $slotSettings->slotWildMpl;
                                        for( $_obf_0D0C021818145B101222043F3C051C231B1C5B231A3522 = 0; $_obf_0D0C021818145B101222043F3C051C231B1C5B231A3522 < 5; $_obf_0D0C021818145B101222043F3C051C231B1C5B231A3522++ ) 
                                        {
                                            if( in_array($s[$_obf_0D0C021818145B101222043F3C051C231B1C5B231A3522], $wild) ) 
                                            {
                                                $s[$_obf_0D0C021818145B101222043F3C051C231B1C5B231A3522] = 'P_1';
                                            }
                                        }
                                    }
                                    $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01 = $slotSettings->Paytable[$_obf_0D011C142C3C37263F351C4012170A074027083F321132][5] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] * $mpl * $bonusMpl;
                                    if( $_obf_0D011C142C3C37263F351C4012170A074027083F321132 == 'P_1' ) 
                                    {
                                        $_obf_0D18345B0B3106341901142F1A051E33355C0F1A060E01 = true;
                                    }
                                    if( $_obf_0D18345B0B3106341901142F1A051E33355C0F1A060E01 && !isset($slotSettings->Jackpots['jackPay']) ) 
                                    {
                                        $_obf_0D18345B0B3106341901142F1A051E33355C0F1A060E01 = false;
                                    }
                                    else
                                    {
                                        if( $cWins[$k] < $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01 && !$_obf_0D18345B0B3106341901142F1A051E33355C0F1A060E01 ) 
                                        {
                                            $cWins[$k] = $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01;
                                            $_obf_0D0207283039073919263232090A382F3D26101F0D1E11 = '{"Count":5,"Line":' . $k . ',"Win":' . $cWins[$k] . ',"stepWin":' . ($cWins[$k] + $totalWin + $slotSettings->GetGameData('AmazingStarsGTMBonusWin')) . ',"winReel1":[' . ($linesId[$k][0] - 1) . ',"' . $s[0] . '"],"winReel2":[' . ($linesId[$k][1] - 1) . ',"' . $s[1] . '"],"winReel3":[' . ($linesId[$k][2] - 1) . ',"' . $s[2] . '"],"winReel4":[' . ($linesId[$k][3] - 1) . ',"' . $s[3] . '"],"winReel5":[' . ($linesId[$k][4] - 1) . ',"' . $s[4] . '"]}';
                                        }
                                        if( $_obf_0D18345B0B3106341901142F1A051E33355C0F1A060E01 ) 
                                        {
                                            $cWins[$k] = $_obf_0D0D163F1706133D0A110219022A07303D371E1C0A0F01;
                                            $_obf_0D0207283039073919263232090A382F3D26101F0D1E11 = '{"Count":5,"Line":' . $k . ',"Win":"' . $slotSettings->Jackpots['jackPay'] . '","stepWin":' . ($cWins[$k] + $totalWin + $slotSettings->GetGameData('AmazingStarsGTMBonusWin')) . ',"winReel1":[' . ($linesId[$k][0] - 1) . ',"' . $s[0] . '"],"winReel2":[' . ($linesId[$k][1] - 1) . ',"' . $s[1] . '"],"winReel3":[' . ($linesId[$k][2] - 1) . ',"' . $s[2] . '"],"winReel4":[' . ($linesId[$k][3] - 1) . ',"' . $s[3] . '"],"winReel5":[' . ($linesId[$k][4] - 1) . ',"' . $s[4] . '"]}';
                                        }
                                    }
                                }
                            }
                        }
                        if( $cWins[$k] > 0 && $_obf_0D0207283039073919263232090A382F3D26101F0D1E11 != '' || $_obf_0D18345B0B3106341901142F1A051E33355C0F1A060E01 && $_obf_0D0207283039073919263232090A382F3D26101F0D1E11 != '' ) 
                        {
                            array_push($lineWins, $_obf_0D0207283039073919263232090A382F3D26101F0D1E11);
                            $totalWin += $cWins[$k];
                        }
                    }
                    $scattersWin = 0;
                    $scattersStr = '{';
                    $_obf_0D18290D3E363917042D072C372525041B0A1008392532 = '{';
                    $scattersCount = 0;
                    $_obf_0D281F055C33273F22311801070C192624231539232111 = false;
                    $_obf_0D2B213913373B3B270F1A261533331D252F06302F2C32 = 0;
                    $_obf_0D1E041A131F03072D0507010A2A1C133D02283C0F0801 = 0;
                    for( $r = 1; $r <= 5; $r++ ) 
                    {
                        for( $p = 0; $p <= 2; $p++ ) 
                        {
                            if( $reels['reel' . $r][$p] == $scatter ) 
                            {
                                $scattersCount++;
                                $scattersStr .= ('"winReel' . $r . '_' . $p . '":[' . $p . ',"' . $scatter . '"],');
                                $_obf_0D18290D3E363917042D072C372525041B0A1008392532 .= ('"winReel' . $r . '_' . $p . '":[' . $p . ',"' . $scatter . '"],');
                            }
                            if( $reels['reel' . $r][$p] == 'P_1' ) 
                            {
                                $_obf_0D2B213913373B3B270F1A261533331D252F06302F2C32++;
                            }
                            if( $reels['reel' . $r][$p] == 'SCAT' ) 
                            {
                                $_obf_0D1E041A131F03072D0507010A2A1C133D02283C0F0801++;
                            }
                        }
                    }
                    if( $_obf_0D1E041A131F03072D0507010A2A1C133D02283C0F0801 >= 15 ) 
                    {
                        $totalWin = 0;
                        $_obf_0D281F055C33273F22311801070C192624231539232111 = true;
                    }
                    $scattersWinTmp = 0;
                    if( $slotSettings->GetGameData('AmazingStarsGTMFreeGames') <= $slotSettings->GetGameData('AmazingStarsGTMCurrentFreeGame') && $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'freespin' ) 
                    {
                        $scattersWinTmp = $slotSettings->Paytable[$scatter][$scattersCount] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotLines'] - $slotSettings->GetGameData('AmazingStarsGTMScatterWin');
                        $_obf_0D163F390C080D0831380D161E12270D0225132B261501 = $slotSettings->GetBank((isset($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent']) ? $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] : ''));
                        $_obf_0D3B3C113639391705311B0F12323C3B3B250C1A142401 = $_obf_0D163F390C080D0831380D161E12270D0225132B261501;
                    }
                    else if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'freespin' ) 
                    {
                        $scattersWinTmp = $slotSettings->Paytable[$scatter][$scattersCount] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotLines'] - $slotSettings->GetGameData('AmazingStarsGTMScatterWin');
                    }
                    else if( $winType == 'bonus' ) 
                    {
                        $scattersWinTmp = $slotSettings->Paytable[$scatter][$scattersCount] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotLines'] - $slotSettings->GetGameData('AmazingStarsGTMScatterWin');
                    }
                    else
                    {
                        $scattersWin = 0;
                    }
                    if( $scattersCount >= 3 && $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] != 'freespin' ) 
                    {
                        $scattersStr .= '"scattersType":"bonus",';
                        $scattersWin = 0;
                    }
                    else if( $scattersWin > 0 ) 
                    {
                        $scattersStr .= '"scattersType":"win",';
                    }
                    else
                    {
                        $scattersStr .= '"scattersType":"none",';
                    }
                    $scattersStr .= ('"scattersWin":' . $scattersWin . '}');
                    $totalWin += $scattersWin;
                    if( $_obf_0D281F055C33273F22311801070C192624231539232111 && $_obf_0D18345B0B3106341901142F1A051E33355C0F1A060E01 ) 
                    {
                        $totalWin = 0;
                        $winType = 'none';
                    }
                    if( $_obf_0D281F055C33273F22311801070C192624231539232111 ) 
                    {
                        $totalWin = 0;
                        break;
                    }
                    if( $_obf_0D18345B0B3106341901142F1A051E33355C0F1A060E01 ) 
                    {
                        $totalWin = $slotSettings->Jackpots['jackPay'];
                        break;
                    }
                    if( $i > 1000 ) 
                    {
                        $winType = 'none';
                    }
                    if( $i > 1500 ) 
                    {
                        $response = '{"responseEvent":"error","responseType":"' . $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] . '","serverResponse":"Bad Reel Strip"}';
                        exit( $response );
                    }
                    if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] != 'freespin' ) 
                    {
                        if( $slotSettings->increaseRTP && $winType == 'win' && $totalWin < ($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotLines'] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] * rand(2, 5)) ) 
                        {
                        }
                        else if( !$slotSettings->increaseRTP && $winType == 'win' && $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotLines'] * $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] < $totalWin ) 
                        {
                        }
                    }
                    if( $_obf_0D1E041A131F03072D0507010A2A1C133D02283C0F0801 >= 15 && !$_obf_0D262A401E2428360E103910312E0E2A2D04280B345B11['isJackPay'] ) 
                    {
                    }
                    else if( $slotSettings->GetBank((isset($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent']) ? $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] : '')) < ($totalWin + $scattersWinTmp) && ($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'freespin' || $winType == 'bonus') ) 
                    {
                    }
                    else
                    {
                        if( $slotSettings->GetGameData('AmazingStarsGTMFreeGames') <= $slotSettings->GetGameData('AmazingStarsGTMCurrentFreeGame') && $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'freespin' ) 
                        {
                            $scattersWin = 0;
                            $totalWin = 0;
                            $_obf_0D163F390C080D0831380D161E12270D0225132B261501 = $slotSettings->GetBank((isset($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent']) ? $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] : ''));
                            $_obf_0D3B3C113639391705311B0F12323C3B3B250C1A142401 = $_obf_0D163F390C080D0831380D161E12270D0225132B261501;
                            if( $totalWin <= $_obf_0D3B3C113639391705311B0F12323C3B3B250C1A142401 ) 
                            {
                                break;
                            }
                        }
                        if( $scattersCount >= 3 && $winType != 'bonus' && $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] != 'freespin' ) 
                        {
                        }
                        else if( $totalWin <= $_obf_0D3B3C113639391705311B0F12323C3B3B250C1A142401 && $winType == 'bonus' ) 
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
                }
                if( $totalWin + $scattersWinTmp > 0 && !$_obf_0D18345B0B3106341901142F1A051E33355C0F1A060E01 && !$_obf_0D281F055C33273F22311801070C192624231539232111 ) 
                {
                    $slotSettings->SetBank((isset($_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent']) ? $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] : ''), -1 * ($totalWin + $scattersWinTmp));
                    if( $scattersWinTmp > 0 ) 
                    {
                        $slotSettings->SetGameData('AmazingStarsGTMScatterWin', $slotSettings->GetGameData('AmazingStarsGTMScatterWin') + $scattersWinTmp);
                    }
                }
                if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'freespin' && $slotSettings->GetGameData('AmazingStarsGTMFreeGames') <= $slotSettings->GetGameData('AmazingStarsGTMCurrentFreeGame') && $winType != 'bonus' && $slotSettings->GetGameData('AmazingStarsGTMTotalWin') + $totalWin + $slotSettings->GetGameData('AmazingStarsGTMScatterWin') > 0 && !$_obf_0D18345B0B3106341901142F1A051E33355C0F1A060E01 && !$_obf_0D281F055C33273F22311801070C192624231539232111 ) 
                {
                    $slotSettings->SetBalance($slotSettings->GetGameData('AmazingStarsGTMTotalWin') + $totalWin + $slotSettings->GetGameData('AmazingStarsGTMScatterWin'));
                }
                else if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] != 'freespin' && $winType != 'bonus' && $totalWin > 0 && !$_obf_0D18345B0B3106341901142F1A051E33355C0F1A060E01 && !$_obf_0D281F055C33273F22311801070C192624231539232111 ) 
                {
                    $slotSettings->SetBalance($totalWin);
                }
                $_obf_0D23292E1910310B2D0F382A090D063F2A132521111C32 = $totalWin;
                if( $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'freespin' ) 
                {
                    $slotSettings->SetGameData('AmazingStarsGTMBonusWin', $slotSettings->GetGameData('AmazingStarsGTMBonusWin') + $totalWin);
                    $slotSettings->SetGameData('AmazingStarsGTMTotalWin', $slotSettings->GetGameData('AmazingStarsGTMTotalWin') + $totalWin);
                    $totalWin = $slotSettings->GetGameData('AmazingStarsGTMBonusWin');
                    $Balance = $slotSettings->GetGameData('AmazingStarsGTMFreeBalance');
                    $_obf_0D250D0F38353928051A170C33210E211810162A181811 = 1;
                    $_obf_0D2B5C0919090D2B350E0D1934311A3F2539211E1C3301 = $slotSettings->GetGameData('AmazingStarsGTMFreeStacked');
                    for( $i = 1; $i <= 5; $i++ ) 
                    {
                        if( $reels['reel' . $i][0] == 'SCAT' ) 
                        {
                            $_obf_0D2B5C0919090D2B350E0D1934311A3F2539211E1C3301[$_obf_0D250D0F38353928051A170C33210E211810162A181811] = 'true';
                        }
                        $_obf_0D250D0F38353928051A170C33210E211810162A181811++;
                        if( $reels['reel' . $i][1] == 'SCAT' ) 
                        {
                            $_obf_0D2B5C0919090D2B350E0D1934311A3F2539211E1C3301[$_obf_0D250D0F38353928051A170C33210E211810162A181811] = 'true';
                        }
                        $_obf_0D250D0F38353928051A170C33210E211810162A181811++;
                        if( $reels['reel' . $i][2] == 'SCAT' ) 
                        {
                            $_obf_0D2B5C0919090D2B350E0D1934311A3F2539211E1C3301[$_obf_0D250D0F38353928051A170C33210E211810162A181811] = 'true';
                        }
                        $_obf_0D250D0F38353928051A170C33210E211810162A181811++;
                    }
                    $slotSettings->SetGameData('AmazingStarsGTMFreeStacked', $_obf_0D2B5C0919090D2B350E0D1934311A3F2539211E1C3301);
                }
                else
                {
                    $slotSettings->SetGameData('AmazingStarsGTMTotalWin', $totalWin);
                }
                if( $scattersCount >= 3 && $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] != 'freespin' ) 
                {
                    if( $slotSettings->GetGameData('AmazingStarsGTMFreeGames') > 0 ) 
                    {
                        $slotSettings->SetGameData('AmazingStarsGTMFreeBalance', $Balance);
                        $slotSettings->SetGameData('AmazingStarsGTMBonusWin', $totalWin);
                        $slotSettings->SetGameData('AmazingStarsGTMFreeGames', $slotSettings->GetGameData('AmazingStarsGTMFreeGames') + $slotSettings->slotFreeCount);
                    }
                    else
                    {
                        $slotSettings->SetGameData('AmazingStarsGTMFreeBalance', $Balance);
                        $slotSettings->SetGameData('AmazingStarsGTMBonusWin', $totalWin);
                        $slotSettings->SetGameData('AmazingStarsGTMFreeGames', $slotSettings->slotFreeCount);
                        $_obf_0D250D0F38353928051A170C33210E211810162A181811 = 1;
                        $_obf_0D2B5C0919090D2B350E0D1934311A3F2539211E1C3301 = $slotSettings->GetGameData('AmazingStarsGTMFreeStacked');
                        for( $i = 1; $i <= 5; $i++ ) 
                        {
                            if( $reels['reel' . $i][0] == 'SCAT' ) 
                            {
                                $_obf_0D2B5C0919090D2B350E0D1934311A3F2539211E1C3301[$_obf_0D250D0F38353928051A170C33210E211810162A181811] = 'true';
                            }
                            $_obf_0D250D0F38353928051A170C33210E211810162A181811++;
                            if( $reels['reel' . $i][1] == 'SCAT' ) 
                            {
                                $_obf_0D2B5C0919090D2B350E0D1934311A3F2539211E1C3301[$_obf_0D250D0F38353928051A170C33210E211810162A181811] = 'true';
                            }
                            $_obf_0D250D0F38353928051A170C33210E211810162A181811++;
                            if( $reels['reel' . $i][2] == 'SCAT' ) 
                            {
                                $_obf_0D2B5C0919090D2B350E0D1934311A3F2539211E1C3301[$_obf_0D250D0F38353928051A170C33210E211810162A181811] = 'true';
                            }
                            $_obf_0D250D0F38353928051A170C33210E211810162A181811++;
                        }
                        $slotSettings->SetGameData('AmazingStarsGTMFreeStacked', $_obf_0D2B5C0919090D2B350E0D1934311A3F2539211E1C3301);
                    }
                }
                $reels = $_obf_0D0C353C231930293E1C32222A282208283E03311D0C01;
                $_obf_0D140A1C122D065B2A1629031B280E272815082A0D2122 = '' . json_encode($reels) . '';
                $_obf_0D1B370B073F123C3210300C0336351F3E072217172A22 = '' . json_encode($slotSettings->Jackpots) . '';
                $_obf_0D33120B1B18292D30293B191C3D383E3D2D0C195B2101 = implode(',', $lineWins);
                $AmazingStarsGTMFreeStacked = [
                    '0', 
                    '0', 
                    '0', 
                    '0', 
                    '0', 
                    '0', 
                    '0', 
                    '0', 
                    '0', 
                    '0', 
                    '0', 
                    '0', 
                    '0', 
                    '0', 
                    '0', 
                    '0', 
                    '0'
                ];
                $_obf_0D06120936080F05012212350E1F0139151F061D1E1201 = $slotSettings->GetGameData('AmazingStarsGTMFreeStacked');
                for( $i = 0; $i <= 15; $i++ ) 
                {
                    if( $_obf_0D06120936080F05012212350E1F0139151F061D1E1201[$i] == 'true' ) 
                    {
                        $AmazingStarsGTMFreeStacked[$i] = '1';
                    }
                }
                if( $slotSettings->GetGameData('AmazingStarsGTMFreeGames') <= $slotSettings->GetGameData('AmazingStarsGTMCurrentFreeGame') && $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] == 'freespin' && !$_obf_0D18345B0B3106341901142F1A051E33355C0F1A060E01 && !$_obf_0D281F055C33273F22311801070C192624231539232111 ) 
                {
                    $totalWin += $slotSettings->GetGameData('AmazingStarsGTMScatterWin');
                    $_obf_0D23292E1910310B2D0F382A090D063F2A132521111C32 = $totalWin;
                    $_obf_0D18290D3E363917042D072C372525041B0A1008392532 .= '"scattersType":"win",';
                    $_obf_0D18290D3E363917042D072C372525041B0A1008392532 .= ('"scattersWin":' . $slotSettings->GetGameData('AmazingStarsGTMScatterWin') . '}');
                    $scattersStr = $_obf_0D18290D3E363917042D072C372525041B0A1008392532;
                }
                if( !isset($_obf_0D262A401E2428360E103910312E0E2A2D04280B345B11) ) 
                {
                    $_obf_0D262A401E2428360E103910312E0E2A2D04280B345B11 = [];
                }
                $response = '{"responseEvent":"spin","responseType":"' . $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent'] . '","serverResponse":{"$aws":[' . implode(',', $slotSettings->GetGameData('AmazingStarsGTMFreeStacked')) . '],"$jackState":' . json_encode($_obf_0D262A401E2428360E103910312E0E2A2D04280B345B11) . ',"scattersWinTmp":' . $slotSettings->GetGameData('AmazingStarsGTMScatterWin') . ',"slotLines":' . $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotLines'] . ',"slotBet":' . $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'] . ',"stackedWilds":[' . implode(',', $AmazingStarsGTMFreeStacked) . '],"slotJackpot0":' . $slotSettings->slotJackpot[0] . ',"slotJackpot":' . $slotSettings->slotJackpot[1] . ',"totalFreeGames":' . $slotSettings->GetGameData('AmazingStarsGTMFreeGames') . ',"currentFreeGames":' . $slotSettings->GetGameData('AmazingStarsGTMCurrentFreeGame') . ',"Balance":' . $Balance . ',"afterBalance":' . $slotSettings->GetBalance() . ',"totalWin":' . $totalWin . ',"winLines":[' . $_obf_0D33120B1B18292D30293B191C3D383E3D2D0C195B2101 . '],"bonusInfo":' . $scattersStr . ',"Jackpots":' . $_obf_0D1B370B073F123C3210300C0336351F3E072217172A22 . ',"reelsSymbols":' . $_obf_0D140A1C122D065B2A1629031B280E272815082A0D2122 . '}}';
                $slotSettings->SaveLogReport($response, $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'], $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotLines'], $_obf_0D23292E1910310B2D0F382A090D063F2A132521111C32, $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotEvent']);
                if( isset($slotSettings->Jackpots['jackPay']) ) 
                {
                    $slotSettings->SaveLogReport($response, $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotBet'], $_obf_0D221D1040101E0C18152D38350A220B2431190A3E1822['slotLines'], $slotSettings->Jackpots['jackPay'], 'JPG');
                }
            }
            $slotSettings->SaveGameData();
            \DB::commit();
            return $response;
        }
    }

}

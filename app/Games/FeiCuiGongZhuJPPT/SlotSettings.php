<?php 
namespace VanguardLTE\Games\FeiCuiGongZhuJPPT
{
    class SlotSettings
    {
        public $playerId = null;
        public $splitScreen = null;
        public $reelStrip1 = null;
        public $reelStrip2 = null;
        public $reelStrip3 = null;
        public $reelStrip4 = null;
        public $reelStrip5 = null;
        public $reelStrip6 = null;
        public $reelStripBonus1 = null;
        public $reelStripBonus2 = null;
        public $reelStripBonus3 = null;
        public $reelStripBonus4 = null;
        public $reelStripBonus5 = null;
        public $reelStripBonus6 = null;
        public $slotId = '';
        public $slotDBId = '';
        public $Line = null;
        public $scaleMode = null;
        public $numFloat = null;
        public $gameLine = null;
        public $Bet = null;
        public $isBonusStart = null;
        public $Balance = null;
        public $SymbolGame = null;
        public $GambleType = null;
        public $lastEvent = null;
        public $Jackpots = [];
        public $keyController = null;
        public $slotViewState = null;
        public $hideButtons = null;
        public $slotReelsConfig = null;
        public $slotFreeCount = null;
        public $slotFreeMpl = null;
        public $slotWildMpl = null;
        public $slotExitUrl = null;
        public $slotBonus = null;
        public $slotBonusType = null;
        public $slotScatterType = null;
        public $slotGamble = null;
        private $slotJackPercent = null;
        private $slotJackLimit = null;
        public $Paytable = [];
        public $slotSounds = [];
        public $jpgs = null;
        private $Bank = null;
        private $Percent = null;
        private $WinLine = null;
        private $WinGamble = null;
        private $Bonus = null;
        private $shop_id = null;
        public $licenseDK = null;
        public $currency = null;
        public $user = null;
        public $game = null;
        public $shop = null;
        public function __construct($sid, $playerId)
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
            $this->licenseDK = true;
            $checked = new \VanguardLTE\Lib\LicenseDK();
            $license_notifications_array = $checked->aplVerifyLicenseDK(null, 0);
            if( $license_notifications_array['notification_case'] != 'notification_license_ok' ) 
            {
                $this->licenseDK = false;
            }*/
            $this->slotId = $sid;
            $this->playerId = $playerId;
            $user = \VanguardLTE\User::lockForUpdate()->find($this->playerId);
            $this->user = $user;
            $this->shop_id = $user->shop_id;
            $game = \VanguardLTE\Game::where([
                'name' => $this->slotId, 
                'shop_id' => $this->shop_id
            ])->lockForUpdate()->first();
            $this->shop = \VanguardLTE\Shop::find($this->shop_id);
            $this->game = $game;
            $this->increaseRTP = rand(0, 1);
            $this->CurrentDenom = $this->game->denomination;
            $this->scaleMode = 0;
            $this->numFloat = 0;
            $this->Paytable['SYM_0'] = [
                0, 
                0, 
                10, 
                200, 
                2000, 
                10000
            ];
            $this->Paytable['SYM_1'] = [
                0, 
                0, 
                2, 
                40, 
                150, 
                500
            ];
            $this->Paytable['SYM_2'] = [
                0, 
                0, 
                2, 
                25, 
                100, 
                400
            ];
            $this->Paytable['SYM_3'] = [
                0, 
                0, 
                0, 
                20, 
                70, 
                200
            ];
            $this->Paytable['SYM_4'] = [
                0, 
                0, 
                0, 
                15, 
                50, 
                150
            ];
            $this->Paytable['SYM_5'] = [
                0, 
                0, 
                0, 
                10, 
                40, 
                125
            ];
            $this->Paytable['SYM_6'] = [
                0, 
                0, 
                0, 
                10, 
                30, 
                100
            ];
            $this->Paytable['SYM_7'] = [
                0, 
                0, 
                0, 
                5, 
                20, 
                75
            ];
            $this->Paytable['SYM_8'] = [
                0, 
                0, 
                0, 
                5, 
                15, 
                50
            ];
            $this->Paytable['SYM_9'] = [
                0, 
                0, 
                2, 
                5, 
                50, 
                500
            ];
            $this->Paytable['SYM_10'] = [
                0, 
                0, 
                0, 
                0, 
                0, 
                0
            ];
            $reel = new GameReel();
            foreach( [
                'reelStrip1', 
                'reelStrip2', 
                'reelStrip3', 
                'reelStrip4', 
                'reelStrip5', 
                'reelStrip6'
            ] as $reelStrip ) 
            {
                if( count($reel->reelsStrip[$reelStrip]) ) 
                {
                    $this->$reelStrip = $reel->reelsStrip[$reelStrip];
                }
            }
            $this->keyController = [
                '13' => 'uiButtonSpin,uiButtonSkip', 
                '49' => 'uiButtonInfo', 
                '50' => 'uiButtonCollect', 
                '51' => 'uiButtonExit2', 
                '52' => 'uiButtonLinesMinus', 
                '53' => 'uiButtonLinesPlus', 
                '54' => 'uiButtonBetMinus', 
                '55' => 'uiButtonBetPlus', 
                '56' => 'uiButtonGamble', 
                '57' => 'uiButtonRed', 
                '48' => 'uiButtonBlack', 
                '189' => 'uiButtonAuto', 
                '187' => 'uiButtonSpin'
            ];
            $this->slotReelsConfig = [
                [
                    425, 
                    142, 
                    3
                ], 
                [
                    669, 
                    142, 
                    3
                ], 
                [
                    913, 
                    142, 
                    3
                ], 
                [
                    1157, 
                    142, 
                    3
                ], 
                [
                    1401, 
                    142, 
                    3
                ]
            ];
            $this->slotBonusType = 1;
            $this->slotScatterType = 0;
            $this->splitScreen = false;
            $this->slotBonus = true;
            $this->slotGamble = true;
            $this->slotFastStop = 1;
            $this->slotExitUrl = '/';
            $this->slotWildMpl = 1;
            $this->GambleType = 1;
            $this->slotFreeCount = 10;
            $this->slotFreeMpl = 3;
            $this->slotViewState = ($game->slotViewState == '' ? 'Normal' : $game->slotViewState);
            $this->hideButtons = [];
            $this->jpgs = \VanguardLTE\JPG::where('shop_id', $this->shop_id)->lockForUpdate()->get();
            $this->slotJackPercent = [];
            $this->slotJackpot = [];
            for( $jp = 0; $jp < 4; $jp++ ) 
            {
                if( $this->jpgs[$jp]->balance != '' ) 
                {
                    $this->slotJackpot[$jp] = sprintf('%01.4f', $this->jpgs[$jp]->balance);
                    $this->slotJackpot[$jp] = substr($this->slotJackpot[$jp], 0, strlen($this->slotJackpot[$jp]) - 2);
                    $this->slotJackPercent[] = $this->jpgs[$jp]->percent;
                }
            }
            $this->Line = [
                1, 
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
                14, 
                15
            ];
            $this->gameLine = [
                1, 
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
                14, 
                15
            ];
            $this->Bet = explode(',', $game->bet);
            $this->Balance = $user->balance;
            $this->SymbolGame = [
                '0', 
                '1', 
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
                12
            ];
            $this->Bank = $game->get_gamebank();
            $this->Percent = $this->shop->percent;
            $this->WinGamble = $game->rezerv;
            $this->slotDBId = $game->id;
            $this->slotCurrency = $user->shop->currency;
            
            if( !isset($this->user->session) || strlen($this->user->session) <= 0 ) 
            {
                $this->user->session = serialize([]);
            }
            $this->gameData = unserialize($this->user->session);
            if( count($this->gameData) > 0 ) 
            {
                foreach( $this->gameData as $key => $vl ) 
                {
                    if( $vl['timelife'] <= time() ) 
                    {
                        unset($this->gameData[$key]);
                    }
                }
            }
        }
        public function SetGameData($key, $value)
        {
            $_obf_0D040604031A0C332A392C0F2E0C1018072E3C1C1B3C32 = 86400;
            $this->gameData[$key] = [
                'timelife' => time() + $_obf_0D040604031A0C332A392C0F2E0C1018072E3C1C1B3C32, 
                'payload' => $value
            ];
        }
        public function GetGameData($key)
        {
            if( isset($this->gameData[$key]) ) 
            {
                return $this->gameData[$key]['payload'];
            }
            else
            {
                return 0;
            }
        }
        public function FormatFloat($num)
        {
            $_obf_0D1D2F06402E3626070611230F2F2405170110213D0932 = explode('.', $num);
            if( isset($_obf_0D1D2F06402E3626070611230F2F2405170110213D0932[1]) ) 
            {
                if( strlen($_obf_0D1D2F06402E3626070611230F2F2405170110213D0932[1]) > 4 ) 
                {
                    return round($num * 100) / 100;
                }
                else if( strlen($_obf_0D1D2F06402E3626070611230F2F2405170110213D0932[1]) > 2 ) 
                {
                    return floor($num * 100) / 100;
                }
                else
                {
                    return $num;
                }
            }
            else
            {
                return $num;
            }
        }
        public function SaveGameData()
        {
            $this->user->session = serialize($this->gameData);
            $this->user->save();
            $this->user->refresh();
        }
        public function CheckBonusWin()
        {
            $_obf_0D250A3827310B5B0C0D121C1303111534020E0F181C01 = 0;
            $_obf_0D1003233B2728340337151E14011404193D37332A1301 = 0;
            foreach( $this->Paytable as $vl ) 
            {
                foreach( $vl as $_obf_0D0F161E053D31023119151106020D370C340240140611 ) 
                {
                    if( $_obf_0D0F161E053D31023119151106020D370C340240140611 > 0 ) 
                    {
                        $_obf_0D250A3827310B5B0C0D121C1303111534020E0F181C01++;
                        $_obf_0D1003233B2728340337151E14011404193D37332A1301 += $_obf_0D0F161E053D31023119151106020D370C340240140611;
                        break;
                    }
                }
            }
            return $_obf_0D1003233B2728340337151E14011404193D37332A1301 / $_obf_0D250A3827310B5B0C0D121C1303111534020E0F181C01;
        }
        public function HasGameData($key)
        {
            if( isset($this->gameData[$key]) ) 
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        public function GetHistory()
        {
            $history = \VanguardLTE\GameLog::whereRaw('game_id=? and user_id=? ORDER BY id DESC LIMIT 10', [
                $this->slotDBId, 
                $this->playerId
            ])->get();
            $this->lastEvent = 'NULL';
            foreach( $history as $log ) 
            {
                $_obf_0D1F212D1B1A262C271C2C331110342F041A3014182301 = json_decode($log->str);
                if( $_obf_0D1F212D1B1A262C271C2C331110342F041A3014182301->responseEvent != 'gambleResult' ) 
                {
                    $this->lastEvent = $log->str;
                    break;
                }
            }
            if( isset($_obf_0D1F212D1B1A262C271C2C331110342F041A3014182301) ) 
            {
                return $_obf_0D1F212D1B1A262C271C2C331110342F041A3014182301;
            }
            else
            {
                return 'NULL';
            }
        }
        public function ClearJackpot($jid)
        {
            $this->jpgs[$jid]->balance = sprintf('%01.4f', 0);
            $this->jpgs[$jid]->save();
        }
        public function UpdateJackpots($bet)
        {
            $count_balance = $this->GetCountBalanceUser();
            $_obf_0D34100C342A3F3B3C093E1E0D39270B301140212C2F32 = [];
            $game = $this->game;
            $isJackPay = 0;
            $isJackId = 0;
            for( $jp = 1; $jp <= 4; $jp++ ) 
            {
                if( $this->jpgs[$jp - 1]->balance != '' ) 
                {
                    $this->slotJackpot[$jp - 1] = number_format($this->jpgs[$jp - 1]->balance, 4, '.', '');
                    if( $count_balance == 0 ) 
                    {
                        $_obf_0D34100C342A3F3B3C093E1E0D39270B301140212C2F32[$jp - 1] = $this->slotJackpot[$jp - 1];
                    }
                    else if( $count_balance < $bet ) 
                    {
                        $_obf_0D34100C342A3F3B3C093E1E0D39270B301140212C2F32[$jp - 1] = number_format($count_balance / 100 * $this->slotJackPercent[$jp - 1] + $this->slotJackpot[$jp - 1], 4, '.', '');
                    }
                    else
                    {
                        $_obf_0D34100C342A3F3B3C093E1E0D39270B301140212C2F32[$jp - 1] = number_format($bet / 100 * $this->slotJackPercent[$jp - 1] + $this->slotJackpot[$jp - 1], 4, '.', '');
                    }
                    $_obf_0D3F2B3D2B0B393815013C281117220C3B141334393522 = $_obf_0D34100C342A3F3B3C093E1E0D39270B301140212C2F32[$jp - 1];
                    if( $this->jpgs[$jp - 1]->pay_sum <= $this->slotJackpot[$jp - 1] && !$isJackPay && $this->jpgs[$jp - 1]->pay_sum > 0 ) 
                    {
                        $isJackPay = 1;
                        $isJackId = $jp - 1;
                    }
                    $this->slotJackpot[$jp - 1] = sprintf('%01.2f', $_obf_0D34100C342A3F3B3C093E1E0D39270B301140212C2F32[$jp - 1]);
                    $this->jpgs[$jp - 1]->balance = sprintf('%01.4f', $_obf_0D3F2B3D2B0B393815013C281117220C3B141334393522);
                    $this->jpgs[$jp - 1]->save();
                }
            }
            $game->save();
            for( $jp = 0; $jp < 4; $jp++ ) 
            {
                if( $this->jpgs[$jp]->balance != '' && $this->jpgs[$jp]->balance < $this->jpgs[$jp]->start_balance ) 
                {
                    $summ = $this->jpgs[$jp]->start_balance - $this->jpgs[$jp]->balance;
                    if( $summ > 0 ) 
                    {
                        $game->add_jps(false, $jp, $summ);
                    }
                }
            }
            return [
                'isJackPay' => $isJackPay, 
                'isJackId' => $isJackId
            ];
        }
        public function GetBank($slotState = '')
        {
            if( $this->isBonusStart || $slotState == 'bonus' || $slotState == 'freespin' || $slotState == 'respin' ) 
            {
                $slotState = 'bonus';
            }
            else
            {
                $slotState = '';
            }
            $game = $this->game;
            $game->refresh();
            $this->Bank = $game->get_gamebank($slotState);
            return $this->Bank / $this->CurrentDenom;
        }
        public function GetPercent()
        {
            return $this->Percent;
        }
        public function GetCountBalanceUser()
        {
            $this->user->session = serialize($this->gameData);
            $this->user->save();
            $this->user->refresh();
            $this->gameData = unserialize($this->user->session);
            return $this->user->count_balance;
        }
        public function InternalError($errcode)
        {
            $_obf_0D280A1516183F171D1809285B36091811040F26391C11 = '';
            $_obf_0D280A1516183F171D1809285B36091811040F26391C11 .= "\n";
            $_obf_0D280A1516183F171D1809285B36091811040F26391C11 .= ('{"responseEvent":"error","responseType":"' . $errcode . '","serverResponse":"InternalError"}');
            $_obf_0D280A1516183F171D1809285B36091811040F26391C11 .= "\n";
            $_obf_0D280A1516183F171D1809285B36091811040F26391C11 .= ' ############################################### ';
            $_obf_0D280A1516183F171D1809285B36091811040F26391C11 .= "\n";
            $_obf_0D05022C0626351A3C1C5B0D0A2D1A1B0D061C05380332 = '';
            if( file_exists(storage_path('logs/') . $this->slotId . 'Internal.log') ) 
            {
                $_obf_0D05022C0626351A3C1C5B0D0A2D1A1B0D061C05380332 = file_get_contents(storage_path('logs/') . $this->slotId . 'Internal.log');
            }
            file_put_contents(storage_path('logs/') . $this->slotId . 'Internal.log', $_obf_0D05022C0626351A3C1C5B0D0A2D1A1B0D061C05380332 . $_obf_0D280A1516183F171D1809285B36091811040F26391C11);
            exit( '{"responseEvent":"error","responseType":"' . $errcode . '","serverResponse":"InternalError"}' );
        }
        public function SetBank($slotState = '', $sum, $slotEvent = '')
        {
            if( $this->isBonusStart || $slotState == 'bonus' || $slotState == 'freespin' || $slotState == 'respin' ) 
            {
                $slotState = 'bonus';
            }
            else
            {
                $slotState = '';
            }
            if( $this->GetBank($slotState) + $sum < 0 ) 
            {
                $this->InternalError('Bank_   ' . $sum . '  CurrentBank_ ' . $this->GetBank($slotState) . ' CurrentState_ ' . $slotState);
            }
            $sum = $sum * $this->CurrentDenom;
            $game = $this->game;
            $_obf_0D16300411093D18353F3F2A1D5C1D3E3D25372E3D0411 = 0;
            if( $sum > 0 && $slotEvent == 'bet' ) 
            {
                $this->toGameBanks = 0;
                $this->toSlotJackBanks = 0;
                $this->toSysJackBanks = 0;
                $this->betProfit = 0;
                $_obf_0D03242C3B012A362E1A1A28031B25022E0E073B353922 = $this->GetPercent();
                $_obf_0D111A0318183C030C5C320E1D02182D23063522273322 = 10;
                $count_balance = $this->GetCountBalanceUser();
                $_obf_0D16380F3724101637270127352E0C1A06122840150932 = $sum / $this->GetPercent() * 100;
                if( $count_balance < $_obf_0D16380F3724101637270127352E0C1A06122840150932 && $count_balance > 0 ) 
                {
                    $_obf_0D3B1F2B0C113B290D023E032B1D115B5C150109370B32 = $count_balance;
                    $_obf_0D1D235B2128121127373D391B112E3B281D023C5B3722 = $_obf_0D16380F3724101637270127352E0C1A06122840150932 - $_obf_0D3B1F2B0C113B290D023E032B1D115B5C150109370B32;
                    $_obf_0D1A310E2B25282C1A01072A06330C1A173E3437092622 = $_obf_0D3B1F2B0C113B290D023E032B1D115B5C150109370B32 / 100 * $this->GetPercent();
                    $sum = $_obf_0D1A310E2B25282C1A01072A06330C1A173E3437092622 + $_obf_0D1D235B2128121127373D391B112E3B281D023C5B3722;
                    $_obf_0D16300411093D18353F3F2A1D5C1D3E3D25372E3D0411 = $_obf_0D3B1F2B0C113B290D023E032B1D115B5C150109370B32 / 100 * $_obf_0D111A0318183C030C5C320E1D02182D23063522273322;
                }
                else if( $count_balance > 0 ) 
                {
                    $_obf_0D16300411093D18353F3F2A1D5C1D3E3D25372E3D0411 = $_obf_0D16380F3724101637270127352E0C1A06122840150932 / 100 * $_obf_0D111A0318183C030C5C320E1D02182D23063522273322;
                }
                for( $jp = 1; $jp <= 4; $jp++ ) 
                {
                    if( $this->jpgs[$jp - 1]->balance != '' ) 
                    {
                        if( $count_balance < $_obf_0D16380F3724101637270127352E0C1A06122840150932 && $count_balance > 0 ) 
                        {
                            $this->toSlotJackBanks += ($count_balance / 100 * $this->jpgs[$jp - 1]->percent);
                        }
                        else if( $count_balance > 0 ) 
                        {
                            $this->toSlotJackBanks += ($_obf_0D16380F3724101637270127352E0C1A06122840150932 / 100 * $this->jpgs[$jp - 1]->percent);
                        }
                    }
                }
                $this->toGameBanks = $sum;
                $this->betProfit = $_obf_0D16380F3724101637270127352E0C1A06122840150932 - $this->toGameBanks - $this->toSlotJackBanks - $this->toSysJackBanks;
            }
            if( $sum > 0 ) 
            {
                $this->toGameBanks = $sum;
            }
            if( $_obf_0D16300411093D18353F3F2A1D5C1D3E3D25372E3D0411 > 0 ) 
            {
                $sum -= $_obf_0D16300411093D18353F3F2A1D5C1D3E3D25372E3D0411;
                $game->set_gamebank($_obf_0D16300411093D18353F3F2A1D5C1D3E3D25372E3D0411, 'inc', 'bonus');
            }
            $game->set_gamebank($sum, 'inc', $slotState);
            $game->save();
            return $game;
        }
        public function SetBalance($sum, $slotEvent = '')
        {
            if( $this->GetBalance() + $sum < 0 ) 
            {
                $this->InternalError('Balance_   ' . $sum);
            }
            $sum = $sum * $this->CurrentDenom;
            $user = $this->user;
            if( $sum < 0 && $slotEvent == 'bet' ) 
            {
                $user->wager = $user->wager + $sum;
                $user->wager = $this->FormatFloat($user->wager);
                $user->count_balance = $user->count_balance + $sum;
                $user->count_balance = $this->FormatFloat($user->count_balance);
            }
            $user->balance = $user->balance + $sum;
            $user->balance = $this->FormatFloat($user->balance);
            $this->user->session = serialize($this->gameData);
            $this->user->save();
            $this->user->refresh();
            $this->gameData = unserialize($this->user->session);
            if( $user->balance == 0 ) 
            {
                $user->update([
                    'wager' => 0, 
                    'bonus' => 0
                ]);
            }
            if( $user->wager == 0 ) 
            {
                $user->update(['bonus' => 0]);
            }
            if( $user->wager < 0 ) 
            {
                $user->update([
                    'wager' => 0, 
                    'bonus' => 0
                ]);
            }
            if( $user->count_balance < 0 ) 
            {
                $user->update(['count_balance' => 0]);
            }
            return $user;
        }
        public function GetBalance()
        {
            $this->user->session = serialize($this->gameData);
            $this->user->save();
            $this->user->refresh();
            $this->gameData = unserialize($this->user->session);
            $user = $this->user;
            $this->Balance = $user->balance / $this->CurrentDenom;
            return $this->Balance;
        }
        public function SaveLogReport($spinSymbols, $bet, $lines, $win, $slotState)
        {
            $_obf_0D172C04372D1A2A3C2B33260B34083837222A08343211 = $this->slotId . ' ' . $slotState;
            if( $slotState == 'freespin' ) 
            {
                $_obf_0D172C04372D1A2A3C2B33260B34083837222A08343211 = $this->slotId . ' FG';
            }
            else if( $slotState == 'bet' ) 
            {
                $_obf_0D172C04372D1A2A3C2B33260B34083837222A08343211 = $this->slotId . '';
            }
            else if( $slotState == 'slotGamble' ) 
            {
                $_obf_0D172C04372D1A2A3C2B33260B34083837222A08343211 = $this->slotId . ' DG';
            }
            $game = $this->game;
            $game->increment('stat_in', $bet * $lines * $this->CurrentDenom);
            $game->increment('stat_out', $win * $this->CurrentDenom);
            if( !isset($this->betProfit) ) 
            {
                $this->betProfit = 0;
                $this->toGameBanks = 0;
                $this->toSlotJackBanks = 0;
                $this->toSysJackBanks = 0;
            }
            if( !isset($this->toGameBanks) ) 
            {
                $this->toGameBanks = 0;
            }
            $this->game->increment('bids');
            $this->game->refresh();
            \VanguardLTE\GameLog::create([
                'game_id' => $this->slotDBId, 
                'user_id' => $this->playerId, 
                'ip' => $_SERVER['REMOTE_ADDR'], 
                'str' => $spinSymbols, 
                'shop_id' => $this->shop_id
            ]);
            if( $_obf_0D172C04372D1A2A3C2B33260B34083837222A08343211 != ($this->slotId . ' jackpot') ) 
            {
                \VanguardLTE\StatGame::create([
                    'user_id' => $this->playerId, 
                    'balance' => $this->Balance * $this->CurrentDenom, 
                    'bet' => $bet * $lines * $this->CurrentDenom, 
                    'win' => $win * $this->CurrentDenom, 
                    'game' => $_obf_0D172C04372D1A2A3C2B33260B34083837222A08343211, 
                    'percent' => $this->toGameBanks, 
                    'percent_jps' => $this->toSysJackBanks, 
                    'percent_jpg' => $this->toSlotJackBanks, 
                    'profit' => $this->betProfit, 
                    'denomination' => $this->CurrentDenom, 
                    'shop_id' => $this->shop_id
                ]);
            }
        }
        public function GetSpinSettings($garantType = 'bet', $bet, $lines)
        {
            $_obf_0D1A192E211F3F0B162A3E2A101410120A5C1725025B22 = 10;
            switch( $lines ) 
            {
                case 10:
                    $_obf_0D1A192E211F3F0B162A3E2A101410120A5C1725025B22 = 10;
                    break;
                case 9:
                case 8:
                    $_obf_0D1A192E211F3F0B162A3E2A101410120A5C1725025B22 = 9;
                    break;
                case 7:
                case 6:
                    $_obf_0D1A192E211F3F0B162A3E2A101410120A5C1725025B22 = 7;
                    break;
                case 5:
                case 4:
                    $_obf_0D1A192E211F3F0B162A3E2A101410120A5C1725025B22 = 5;
                    break;
                case 3:
                case 2:
                    $_obf_0D1A192E211F3F0B162A3E2A101410120A5C1725025B22 = 3;
                    break;
                case 1:
                    $_obf_0D1A192E211F3F0B162A3E2A101410120A5C1725025B22 = 1;
                    break;
                default:
                    $_obf_0D1A192E211F3F0B162A3E2A101410120A5C1725025B22 = 10;
                    break;
            }
            if( $garantType != 'bet' ) 
            {
                $_obf_0D3C140A17381D2305073B293E5C232901062730391001 = '_bonus';
            }
            else
            {
                $_obf_0D3C140A17381D2305073B293E5C232901062730391001 = '';
            }
            $bonusWin = 0;
            $spinWin = 0;
            $game = $this->game;
            $_obf_0D331D3E332C132334050318121E19121A1515392E0B01 = $game->{'garant_win' . $_obf_0D3C140A17381D2305073B293E5C232901062730391001 . $_obf_0D1A192E211F3F0B162A3E2A101410120A5C1725025B22};
            $_obf_0D363F0A050A0E103B2D12271D2309312440072D250A01 = $game->{'garant_bonus' . $_obf_0D3C140A17381D2305073B293E5C232901062730391001 . $_obf_0D1A192E211F3F0B162A3E2A101410120A5C1725025B22};
            $_obf_0D2C3D3B244034312B3327351C2725302F062531213511 = $game->{'winbonus' . $_obf_0D3C140A17381D2305073B293E5C232901062730391001 . $_obf_0D1A192E211F3F0B162A3E2A101410120A5C1725025B22};
            $_obf_0D0A36071E073714332F351C5C36185C2527222F381911 = $game->{'winline' . $_obf_0D3C140A17381D2305073B293E5C232901062730391001 . $_obf_0D1A192E211F3F0B162A3E2A101410120A5C1725025B22};
            $_obf_0D331D3E332C132334050318121E19121A1515392E0B01++;
            $_obf_0D363F0A050A0E103B2D12271D2309312440072D250A01++;
            $return = [
                'none', 
                0
            ];
            if( $_obf_0D2C3D3B244034312B3327351C2725302F062531213511 <= $_obf_0D363F0A050A0E103B2D12271D2309312440072D250A01 ) 
            {
                $bonusWin = 1;
                $_obf_0D363F0A050A0E103B2D12271D2309312440072D250A01 = 0;
                $game->{'winbonus' . $_obf_0D3C140A17381D2305073B293E5C232901062730391001 . $_obf_0D1A192E211F3F0B162A3E2A101410120A5C1725025B22} = $this->getNewSpin($game, 0, 1, $lines, $garantType);
            }
            else if( $_obf_0D0A36071E073714332F351C5C36185C2527222F381911 <= $_obf_0D331D3E332C132334050318121E19121A1515392E0B01 ) 
            {
                $spinWin = 1;
                $_obf_0D331D3E332C132334050318121E19121A1515392E0B01 = 0;
                $game->{'winline' . $_obf_0D3C140A17381D2305073B293E5C232901062730391001 . $_obf_0D1A192E211F3F0B162A3E2A101410120A5C1725025B22} = $this->getNewSpin($game, 1, 0, $lines, $garantType);
            }
            $game->{'garant_win' . $_obf_0D3C140A17381D2305073B293E5C232901062730391001 . $_obf_0D1A192E211F3F0B162A3E2A101410120A5C1725025B22} = $_obf_0D331D3E332C132334050318121E19121A1515392E0B01;
            $game->{'garant_bonus' . $_obf_0D3C140A17381D2305073B293E5C232901062730391001 . $_obf_0D1A192E211F3F0B162A3E2A101410120A5C1725025B22} = $_obf_0D363F0A050A0E103B2D12271D2309312440072D250A01;
            $game->save();
            if( $bonusWin == 1 && $this->slotBonus ) 
            {
                $this->isBonusStart = true;
                $garantType = 'bonus';
                $_obf_0D5C330B390E2B18235C030D36342F03311A2118233032 = $this->GetBank($garantType);
                $return = [
                    'bonus', 
                    $_obf_0D5C330B390E2B18235C030D36342F03311A2118233032
                ];
                if( $_obf_0D5C330B390E2B18235C030D36342F03311A2118233032 < ($this->CheckBonusWin() * $bet) ) 
                {
                    $return = [
                        'none', 
                        0
                    ];
                }
            }
            else if( $spinWin == 1 || $bonusWin == 1 && !$this->slotBonus ) 
            {
                $_obf_0D5C330B390E2B18235C030D36342F03311A2118233032 = $this->GetBank($garantType);
                $return = [
                    'win', 
                    $_obf_0D5C330B390E2B18235C030D36342F03311A2118233032
                ];
            }
            if( $garantType == 'bet' && $this->GetBalance() <= (1 / $this->CurrentDenom) ) 
            {
                $_obf_0D1215151A36223F3D321A1D2E2D37075C041605215B01 = rand(1, 2);
                if( $_obf_0D1215151A36223F3D321A1D2E2D37075C041605215B01 == 1 ) 
                {
                    $_obf_0D5C330B390E2B18235C030D36342F03311A2118233032 = $this->GetBank('');
                    $return = [
                        'win', 
                        $_obf_0D5C330B390E2B18235C030D36342F03311A2118233032
                    ];
                }
            }
            return $return;
        }
        public function getNewSpin($game, $spinWin = 0, $bonusWin = 0, $lines, $garantType = 'bet')
        {
            $_obf_0D1A192E211F3F0B162A3E2A101410120A5C1725025B22 = 10;
            switch( $lines ) 
            {
                case 10:
                    $_obf_0D1A192E211F3F0B162A3E2A101410120A5C1725025B22 = 10;
                    break;
                case 9:
                case 8:
                    $_obf_0D1A192E211F3F0B162A3E2A101410120A5C1725025B22 = 9;
                    break;
                case 7:
                case 6:
                    $_obf_0D1A192E211F3F0B162A3E2A101410120A5C1725025B22 = 7;
                    break;
                case 5:
                case 4:
                    $_obf_0D1A192E211F3F0B162A3E2A101410120A5C1725025B22 = 5;
                    break;
                case 3:
                case 2:
                    $_obf_0D1A192E211F3F0B162A3E2A101410120A5C1725025B22 = 3;
                    break;
                case 1:
                    $_obf_0D1A192E211F3F0B162A3E2A101410120A5C1725025B22 = 1;
                    break;
                default:
                    $_obf_0D1A192E211F3F0B162A3E2A101410120A5C1725025B22 = 10;
                    break;
            }
            if( $garantType != 'bet' ) 
            {
                $_obf_0D3C140A17381D2305073B293E5C232901062730391001 = '_bonus';
            }
            else
            {
                $_obf_0D3C140A17381D2305073B293E5C232901062730391001 = '';
            }
            if( $spinWin ) 
            {
                $win = explode(',', $game->game_win->{'winline' . $_obf_0D3C140A17381D2305073B293E5C232901062730391001 . $_obf_0D1A192E211F3F0B162A3E2A101410120A5C1725025B22});
            }
            if( $bonusWin ) 
            {
                $win = explode(',', $game->game_win->{'winbonus' . $_obf_0D3C140A17381D2305073B293E5C232901062730391001 . $_obf_0D1A192E211F3F0B162A3E2A101410120A5C1725025B22});
            }
            $number = rand(0, count($win) - 1);
            return $win[$number];
        }
        public function GetRandomScatterPos($rp)
        {
            $_obf_0D27292617142A0F131C2A07024013320B242130093D01 = [];
            for( $i = 0; $i < count($rp); $i++ ) 
            {
                if( $rp[$i] == '9' ) 
                {
                    if( isset($rp[$i + 1]) && isset($rp[$i - 1]) ) 
                    {
                        array_push($_obf_0D27292617142A0F131C2A07024013320B242130093D01, $i);
                    }
                    if( isset($rp[$i - 1]) && isset($rp[$i - 2]) ) 
                    {
                        array_push($_obf_0D27292617142A0F131C2A07024013320B242130093D01, $i - 1);
                    }
                    if( isset($rp[$i + 1]) && isset($rp[$i + 2]) ) 
                    {
                        array_push($_obf_0D27292617142A0F131C2A07024013320B242130093D01, $i + 1);
                    }
                }
            }
            shuffle($_obf_0D27292617142A0F131C2A07024013320B242130093D01);
            if( !isset($_obf_0D27292617142A0F131C2A07024013320B242130093D01[0]) ) 
            {
                $_obf_0D27292617142A0F131C2A07024013320B242130093D01[0] = rand(2, count($rp) - 3);
            }
            return $_obf_0D27292617142A0F131C2A07024013320B242130093D01[0];
        }
        public function EggBonus($bet, $eggBonusNum)
        {
            $bank = $this->GetBank('bonus');
            $_obf_0D1327381D2D32213D2C2B5B0E075B0A1A053022361732 = [
                5, 
                5, 
                5, 
                5, 
                10, 
                10, 
                10, 
                10, 
                15, 
                15, 
                15, 
                15, 
                20, 
                20, 
                20, 
                25, 
                25, 
                25, 
                30, 
                30, 
                35, 
                35, 
                40, 
                40, 
                45, 
                45, 
                50, 
                50, 
                75, 
                75, 
                100, 
                100
            ];
            for( $j = 0; $j < 1500; $j++ ) 
            {
                $curValues = [
                    0, 
                    0, 
                    0, 
                    0, 
                    0, 
                    0
                ];
                $totalWin = 0;
                shuffle($_obf_0D1327381D2D32213D2C2B5B0E075B0A1A053022361732);
                for( $i = 0; $i < 6; $i++ ) 
                {
                    $curValues[$i] = $_obf_0D1327381D2D32213D2C2B5B0E075B0A1A053022361732[$i];
                    if( $i < $eggBonusNum ) 
                    {
                        $totalWin += ($_obf_0D1327381D2D32213D2C2B5B0E075B0A1A053022361732[$i] * $bet);
                    }
                }
                if( $totalWin <= $bank ) 
                {
                    $this->SetBank('bonus', -1 * $totalWin);
                    break;
                }
            }
            return [
                'totalWin' => $totalWin, 
                'curValues' => $curValues
            ];
        }
        public function GetGambleSettings()
        {
            $spinWin = rand(1, $this->WinGamble);
            return $spinWin;
        }
        public function PutBonusToLine($rp, $pos)
        {
            $_obf_0D27292617142A0F131C2A07024013320B242130093D01 = [];
            for( $i = 1; $i < (count($rp) - 1); $i++ ) 
            {
                if( $rp[$i] == '10' && $pos == 2 ) 
                {
                    array_push($_obf_0D27292617142A0F131C2A07024013320B242130093D01, $i);
                }
                if( $rp[$i - 1] == '10' && $pos == 1 ) 
                {
                    array_push($_obf_0D27292617142A0F131C2A07024013320B242130093D01, $i);
                }
                if( $rp[$i + 1] == '10' && $pos == 3 ) 
                {
                    array_push($_obf_0D27292617142A0F131C2A07024013320B242130093D01, $i);
                }
            }
            shuffle($_obf_0D27292617142A0F131C2A07024013320B242130093D01);
            shuffle($_obf_0D27292617142A0F131C2A07024013320B242130093D01);
            if( !isset($_obf_0D27292617142A0F131C2A07024013320B242130093D01[0]) ) 
            {
                $_obf_0D27292617142A0F131C2A07024013320B242130093D01[0] = rand(2, count($rp) - 3);
            }
            return $_obf_0D27292617142A0F131C2A07024013320B242130093D01[0];
        }
        public function GetReelStrips($winType, $slotEvent)
        {
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
                1, 
                1, 
                1, 
                2
            ];
            $linesId[6] = [
                2, 
                3, 
                3, 
                3, 
                2
            ];
            $linesId[7] = [
                1, 
                1, 
                2, 
                3, 
                3
            ];
            $linesId[8] = [
                3, 
                3, 
                2, 
                1, 
                1
            ];
            $linesId[9] = [
                2, 
                3, 
                2, 
                1, 
                2
            ];
            $linesId[10] = [
                2, 
                1, 
                2, 
                3, 
                2
            ];
            $linesId[11] = [
                1, 
                2, 
                2, 
                2, 
                1
            ];
            $linesId[12] = [
                3, 
                2, 
                2, 
                2, 
                3
            ];
            $linesId[13] = [
                1, 
                2, 
                1, 
                2, 
                1
            ];
            $linesId[14] = [
                3, 
                2, 
                3, 
                2, 
                3
            ];
            $linesId[15] = [
                2, 
                2, 
                1, 
                2, 
                2
            ];
            $linesId[16] = [
                2, 
                2, 
                3, 
                2, 
                2
            ];
            $linesId[17] = [
                1, 
                1, 
                3, 
                1, 
                1
            ];
            $linesId[18] = [
                3, 
                3, 
                1, 
                3, 
                3
            ];
            $linesId[19] = [
                1, 
                3, 
                3, 
                3, 
                1
            ];
            $randomBonus = [
                '10', 
                '9'
            ];
            shuffle($randomBonus);
            if( $slotEvent == 'freespin' ) 
            {
                $randomBonus = ['9'];
            }
            if( $slotEvent == 'freespin' ) 
            {
                $game = $this->game;
                $reel = new GameReel();
                $_obf_0D2704192E1921372A0E27012935072D34131C34103211 = $reel->reelsStripBonus;
                foreach( [
                    'reelStrip1', 
                    'reelStrip2', 
                    'reelStrip3', 
                    'reelStrip4', 
                    'reelStrip5', 
                    'reelStrip6'
                ] as $reelStrip ) 
                {
                    $_obf_0D251A4031061710313030232D1709093F373537373E01 = array_shift($_obf_0D2704192E1921372A0E27012935072D34131C34103211);
                    if( count($_obf_0D251A4031061710313030232D1709093F373537373E01) ) 
                    {
                        $this->$reelStrip = $_obf_0D251A4031061710313030232D1709093F373537373E01;
                    }
                }
            }
            $game = $this->game;
            if( $winType != 'bonus2' ) 
            {
                $_obf_0D101228330A351D340513401F3F211B060F0E1A191822 = [];
                foreach( [
                    'reelStrip1', 
                    'reelStrip2', 
                    'reelStrip3', 
                    'reelStrip4', 
                    'reelStrip5', 
                    'reelStrip6'
                ] as $index => $reelStrip ) 
                {
                    if( is_array($this->$reelStrip) && count($this->$reelStrip) > 0 ) 
                    {
                        $_obf_0D101228330A351D340513401F3F211B060F0E1A191822[$index + 1] = mt_rand(0, count($this->$reelStrip) - 3);
                    }
                }
            }
            else
            {
                $_obf_0D360F0E1F1A363935363D35280B0F3F053D272F233C11 = rand(3, 5);
                $_obf_0D340F1A341017095B37324005163D02312C041E262632 = [
                    1, 
                    2, 
                    3, 
                    4, 
                    5
                ];
                $_obf_0D15220A01301A2517063B0F1D0E040B280A1C01042622 = rand(0, 19);
                for( $i = 0; $i < count($_obf_0D340F1A341017095B37324005163D02312C041E262632); $i++ ) 
                {
                    if( $i < $_obf_0D360F0E1F1A363935363D35280B0F3F053D272F233C11 ) 
                    {
                        if( $randomBonus[0] == '10' ) 
                        {
                            $_obf_0D101228330A351D340513401F3F211B060F0E1A191822[$_obf_0D340F1A341017095B37324005163D02312C041E262632[$i]] = $this->PutBonusToLine($this->{'reelStrip' . $_obf_0D340F1A341017095B37324005163D02312C041E262632[$i]}, $linesId[$_obf_0D15220A01301A2517063B0F1D0E040B280A1C01042622][$i]);
                        }
                        else
                        {
                            $_obf_0D101228330A351D340513401F3F211B060F0E1A191822[$_obf_0D340F1A341017095B37324005163D02312C041E262632[$i]] = $this->GetRandomScatterPos($this->{'reelStrip' . $_obf_0D340F1A341017095B37324005163D02312C041E262632[$i]}, $randomBonus);
                        }
                    }
                    else
                    {
                        $_obf_0D101228330A351D340513401F3F211B060F0E1A191822[$_obf_0D340F1A341017095B37324005163D02312C041E262632[$i]] = rand(0, count($this->{'reelStrip' . $_obf_0D340F1A341017095B37324005163D02312C041E262632[$i]}) - 3);
                    }
                }
            }
            $reel = [
                'rp' => []
            ];
            foreach( $_obf_0D101228330A351D340513401F3F211B060F0E1A191822 as $index => $value ) 
            {
                $key = $this->{'reelStrip' . $index};
                $cnt = count($key);
                $key[-1] = $key[$cnt - 1];
                $key[$cnt] = $key[0];
                $reel['reel' . $index][0] = $key[$value - 1];
                $reel['reel' . $index][1] = $key[$value];
                $reel['reel' . $index][2] = $key[$value + 1];
                $reel['reel' . $index][3] = '';
                $reel['rp'][] = $value;
            }
            return $reel;
        }
    }

}

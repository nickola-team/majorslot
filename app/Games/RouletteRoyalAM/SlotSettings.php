<?php 
namespace VanguardLTE\Games\RouletteRoyalAM
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
        public $lastEvent = null;
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
        public $Paytable = [];
        public $slotSounds = [];
        private $jpgs = null;
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
           /* if( config('LicenseDK.APL_INCLUDE_KEY_CONFIG') != 'wi9qydosuimsnls5zoe5q298evkhim0ughx1w16qybs2fhlcpn' ) 
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
            $this->slotExitUrl = '/';
            $this->Bet = explode(',', $game->bet);
            $this->Bet = array_slice($this->Bet, 0, 5);
            $this->Balance = $user->balance;
            $this->Bank = $game->get_gamebank();
            $this->Percent = $this->shop->percent;
            $this->jpgs = \VanguardLTE\JPG::where('shop_id', $this->shop_id)->lockForUpdate()->get();
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
            $str0 = explode('.', $num);
            if( isset($str0[1]) ) 
            {
                if( strlen($str0[1]) > 4 ) 
                {
                    return round($num * 100) / 100;
                }
                else if( strlen($str0[1]) > 2 ) 
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
        public function UpdateJackpots($bet)
        {
            return null;
            $bet = $bet * $this->CurrentDenom;
            $count_balance = $this->GetCountBalanceUser();
            $_obf_0D0E13392A1E352D293108251212135B0D022529241422 = [];
            $_obf_0D052A14092A1117372103081A331C2C2622010A2D0C22 = 0;
            for( $i = 0; $i < count($this->jpgs); $i++ ) 
            {
                if( $count_balance == 0 ) 
                {
                    $_obf_0D0E13392A1E352D293108251212135B0D022529241422[$i] = $this->jpgs[$i]->balance;
                }
                else if( $count_balance < $bet ) 
                {
                    $_obf_0D0E13392A1E352D293108251212135B0D022529241422[$i] = $count_balance / 100 * $this->jpgs[$i]->percent + $this->jpgs[$i]->balance;
                }
                else
                {
                    $_obf_0D0E13392A1E352D293108251212135B0D022529241422[$i] = $bet / 100 * $this->jpgs[$i]->percent + $this->jpgs[$i]->balance;
                }
                if( $this->jpgs[$i]->pay_sum < $_obf_0D0E13392A1E352D293108251212135B0D022529241422[$i] && $this->jpgs[$i]->pay_sum > 0 ) 
                {
                    $_obf_0D052A14092A1117372103081A331C2C2622010A2D0C22 = $this->jpgs[$i]->pay_sum / $this->CurrentDenom;
                    $_obf_0D0E13392A1E352D293108251212135B0D022529241422[$i] = $_obf_0D0E13392A1E352D293108251212135B0D022529241422[$i] - $this->jpgs[$i]->pay_sum;
                    $this->SetBalance($this->jpgs[$i]->pay_sum / $this->CurrentDenom);
                    if( $this->jpgs[$i]->pay_sum > 0 ) 
                    {
                        \VanguardLTE\StatGame::create([
                            'user_id' => $this->playerId, 
                            'balance' => $this->Balance * $this->CurrentDenom, 
                            'bet' => 0, 
                            'win' => $this->jpgs[$i]->pay_sum, 
                            'game' => $this->game->name . ' JPG ' . $this->jpgs[$i]->id, 
                            'percent' => 0, 
                            'percent_jps' => 0, 
                            'percent_jpg' => 0, 
                            'profit' => 0, 
                            'shop_id' => $this->shop_id
                        ]);
                    }
                }
                $this->jpgs[$i]->update(['balance' => $_obf_0D0E13392A1E352D293108251212135B0D022529241422[$i]]);
                $this->jpgs[$i] = $this->jpgs[$i]->refresh();
                if( $this->jpgs[$i]->balance < $this->jpgs[$i]->start_balance ) 
                {
                    $summ = $this->jpgs[$i]->start_balance;
                    if( $summ > 0 ) 
                    {
                        $this->jpgs[$i]->add_jps(false, $summ);
                    }
                }
            }
            if( $_obf_0D052A14092A1117372103081A331C2C2622010A2D0C22 > 0 ) 
            {
                $_obf_0D052A14092A1117372103081A331C2C2622010A2D0C22 = sprintf('%01.2f', $_obf_0D052A14092A1117372103081A331C2C2622010A2D0C22);
                $this->Jackpots['jackPay'] = $_obf_0D052A14092A1117372103081A331C2C2622010A2D0C22;
            }
        }
        public function GetNumbersByField($fieldName)
        {
            $_obf_0D01193B0D082A27062E3701182A2616221B173E355B01 = $fieldName;
            $_obf_0D043C5B2D0125013C062E3F3404112B392227080C2D01 = $fieldName;
            $_obf_0D380F16031E210B362B151E2A162E340B091025083411 = [];
            if( is_numeric($fieldName) ) 
            {
                $_obf_0D043C5B2D0125013C062E3F3404112B392227080C2D01 = 'straight';
            }
            $_obf_0D0B381C311A273304403416101105320C2F2A1C102A01 = explode('/', $fieldName);
            if( isset($_obf_0D0B381C311A273304403416101105320C2F2A1C102A01[1]) ) 
            {
                $_obf_0D043C5B2D0125013C062E3F3404112B392227080C2D01 = 'column';
            }
            $_obf_0D0B381C311A273304403416101105320C2F2A1C102A01 = explode('-', $fieldName);
            if( isset($_obf_0D0B381C311A273304403416101105320C2F2A1C102A01[1]) ) 
            {
                $_obf_0D043C5B2D0125013C062E3F3404112B392227080C2D01 = 'twelve';
                if( $_obf_0D0B381C311A273304403416101105320C2F2A1C102A01[1] - $_obf_0D0B381C311A273304403416101105320C2F2A1C102A01[0] <= 2 ) 
                {
                    $_obf_0D043C5B2D0125013C062E3F3404112B392227080C2D01 = 'street';
                    $_obf_0D30252E0C331134325B5B352D25301704320404330211 = (int)$_obf_0D0B381C311A273304403416101105320C2F2A1C102A01[0];
                    $_obf_0D380F16031E210B362B151E2A162E340B091025083411 = [
                        $_obf_0D30252E0C331134325B5B352D25301704320404330211, 
                        $_obf_0D30252E0C331134325B5B352D25301704320404330211 + 1, 
                        $_obf_0D30252E0C331134325B5B352D25301704320404330211 + 2
                    ];
                }
                if( $_obf_0D0B381C311A273304403416101105320C2F2A1C102A01[1] - $_obf_0D0B381C311A273304403416101105320C2F2A1C102A01[0] > 2 && $_obf_0D0B381C311A273304403416101105320C2F2A1C102A01[1] - $_obf_0D0B381C311A273304403416101105320C2F2A1C102A01[0] <= 5 ) 
                {
                    $_obf_0D30252E0C331134325B5B352D25301704320404330211 = (int)$_obf_0D0B381C311A273304403416101105320C2F2A1C102A01[0];
                    $_obf_0D043C5B2D0125013C062E3F3404112B392227080C2D01 = 'line';
                    $_obf_0D380F16031E210B362B151E2A162E340B091025083411 = [
                        $_obf_0D30252E0C331134325B5B352D25301704320404330211, 
                        $_obf_0D30252E0C331134325B5B352D25301704320404330211 + 1, 
                        $_obf_0D30252E0C331134325B5B352D25301704320404330211 + 2, 
                        $_obf_0D30252E0C331134325B5B352D25301704320404330211 + 3, 
                        $_obf_0D30252E0C331134325B5B352D25301704320404330211 + 4, 
                        $_obf_0D30252E0C331134325B5B352D25301704320404330211 + 5
                    ];
                }
            }
            $_obf_0D0B381C311A273304403416101105320C2F2A1C102A01 = explode(',', $fieldName);
            if( isset($_obf_0D0B381C311A273304403416101105320C2F2A1C102A01[1]) ) 
            {
                $_obf_0D043C5B2D0125013C062E3F3404112B392227080C2D01 = 'split';
            }
            $_obf_0D0B381C311A273304403416101105320C2F2A1C102A01 = explode('.', $fieldName);
            if( isset($_obf_0D0B381C311A273304403416101105320C2F2A1C102A01[1]) ) 
            {
                $_obf_0D043C5B2D0125013C062E3F3404112B392227080C2D01 = 'corner';
                $_obf_0D380F16031E210B362B151E2A162E340B091025083411 = [
                    $_obf_0D0B381C311A273304403416101105320C2F2A1C102A01[0], 
                    $_obf_0D0B381C311A273304403416101105320C2F2A1C102A01[0] + 1, 
                    $_obf_0D0B381C311A273304403416101105320C2F2A1C102A01[1], 
                    $_obf_0D0B381C311A273304403416101105320C2F2A1C102A01[1] - 1
                ];
            }
            if( $_obf_0D043C5B2D0125013C062E3F3404112B392227080C2D01 == 'split' ) 
            {
                $_obf_0D380F16031E210B362B151E2A162E340B091025083411 = explode(',', $_obf_0D01193B0D082A27062E3701182A2616221B173E355B01);
            }
            if( $_obf_0D043C5B2D0125013C062E3F3404112B392227080C2D01 == 'straight' ) 
            {
                $_obf_0D380F16031E210B362B151E2A162E340B091025083411 = [$_obf_0D01193B0D082A27062E3701182A2616221B173E355B01];
            }
            if( $_obf_0D043C5B2D0125013C062E3F3404112B392227080C2D01 == 'column' ) 
            {
                if( $_obf_0D01193B0D082A27062E3701182A2616221B173E355B01 == '1/12' ) 
                {
                    $_obf_0D380F16031E210B362B151E2A162E340B091025083411 = [
                        1, 
                        4, 
                        7, 
                        10, 
                        13, 
                        16, 
                        19, 
                        22, 
                        25, 
                        28, 
                        31, 
                        34
                    ];
                }
                if( $_obf_0D01193B0D082A27062E3701182A2616221B173E355B01 == '2/12' ) 
                {
                    $_obf_0D380F16031E210B362B151E2A162E340B091025083411 = [
                        2, 
                        5, 
                        8, 
                        11, 
                        14, 
                        17, 
                        20, 
                        23, 
                        26, 
                        29, 
                        32, 
                        35
                    ];
                }
                if( $_obf_0D01193B0D082A27062E3701182A2616221B173E355B01 == '3/12' ) 
                {
                    $_obf_0D380F16031E210B362B151E2A162E340B091025083411 = [
                        3, 
                        6, 
                        9, 
                        12, 
                        15, 
                        18, 
                        21, 
                        24, 
                        27, 
                        30, 
                        33, 
                        36
                    ];
                }
            }
            if( $_obf_0D043C5B2D0125013C062E3F3404112B392227080C2D01 == 'twelve' ) 
            {
                if( $_obf_0D01193B0D082A27062E3701182A2616221B173E355B01 == '1-12' ) 
                {
                    $_obf_0D380F16031E210B362B151E2A162E340B091025083411 = [
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
                        12
                    ];
                }
                if( $_obf_0D01193B0D082A27062E3701182A2616221B173E355B01 == '13-24' ) 
                {
                    $_obf_0D380F16031E210B362B151E2A162E340B091025083411 = [
                        13, 
                        14, 
                        15, 
                        16, 
                        17, 
                        18, 
                        19, 
                        20, 
                        21, 
                        22, 
                        23, 
                        24
                    ];
                }
                if( $_obf_0D01193B0D082A27062E3701182A2616221B173E355B01 == '25-36' ) 
                {
                    $_obf_0D380F16031E210B362B151E2A162E340B091025083411 = [
                        25, 
                        26, 
                        27, 
                        28, 
                        29, 
                        30, 
                        31, 
                        32, 
                        33, 
                        34, 
                        35, 
                        36
                    ];
                }
                if( $_obf_0D01193B0D082A27062E3701182A2616221B173E355B01 == '1-18' ) 
                {
                    $_obf_0D043C5B2D0125013C062E3F3404112B392227080C2D01 = 'low';
                    $_obf_0D380F16031E210B362B151E2A162E340B091025083411 = [
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
                        15, 
                        16, 
                        17, 
                        18
                    ];
                }
                if( $_obf_0D01193B0D082A27062E3701182A2616221B173E355B01 == '19-36' ) 
                {
                    $_obf_0D043C5B2D0125013C062E3F3404112B392227080C2D01 = 'high';
                    $_obf_0D380F16031E210B362B151E2A162E340B091025083411 = [
                        13, 
                        14, 
                        15, 
                        16, 
                        17, 
                        18, 
                        19, 
                        20, 
                        21, 
                        22, 
                        23, 
                        24, 
                        25, 
                        26, 
                        27, 
                        28, 
                        29, 
                        30, 
                        31, 
                        32, 
                        33, 
                        34, 
                        35, 
                        36
                    ];
                }
            }
            if( $_obf_0D043C5B2D0125013C062E3F3404112B392227080C2D01 == 'even' ) 
            {
                $_obf_0D380F16031E210B362B151E2A162E340B091025083411 = [
                    2, 
                    4, 
                    6, 
                    8, 
                    10, 
                    12, 
                    14, 
                    16, 
                    18, 
                    20, 
                    22, 
                    24, 
                    26, 
                    28, 
                    30, 
                    32, 
                    34, 
                    36
                ];
            }
            if( $_obf_0D043C5B2D0125013C062E3F3404112B392227080C2D01 == 'odd' ) 
            {
                $_obf_0D380F16031E210B362B151E2A162E340B091025083411 = [
                    1, 
                    3, 
                    5, 
                    7, 
                    9, 
                    11, 
                    13, 
                    15, 
                    17, 
                    19, 
                    21, 
                    23, 
                    25, 
                    27, 
                    29, 
                    31, 
                    33, 
                    35
                ];
            }
            if( $_obf_0D043C5B2D0125013C062E3F3404112B392227080C2D01 == 'red' ) 
            {
                $_obf_0D380F16031E210B362B151E2A162E340B091025083411 = [
                    1, 
                    3, 
                    5, 
                    7, 
                    9, 
                    12, 
                    14, 
                    16, 
                    18, 
                    19, 
                    21, 
                    23, 
                    25, 
                    27, 
                    30, 
                    32, 
                    34, 
                    36
                ];
            }
            if( $_obf_0D043C5B2D0125013C062E3F3404112B392227080C2D01 == 'black' ) 
            {
                $_obf_0D380F16031E210B362B151E2A162E340B091025083411 = [
                    2, 
                    4, 
                    6, 
                    8, 
                    10, 
                    11, 
                    13, 
                    15, 
                    17, 
                    20, 
                    22, 
                    24, 
                    26, 
                    28, 
                    29, 
                    31, 
                    33, 
                    35
                ];
            }
            return [
                $_obf_0D043C5B2D0125013C062E3F3404112B392227080C2D01, 
                $_obf_0D380F16031E210B362B151E2A162E340B091025083411
            ];
        }
        public function HexFormat($num)
        {
            $str = strlen(dechex($num)) . dechex($num);
            return $str;
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
                $_obf_0D111A0318183C030C5C320E1D02182D23063522273322 = 0;
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
        public function GetGambleSettings()
        {
            $spinWin = rand(1, $this->WinGamble);
            return $spinWin;
        }
    }

}

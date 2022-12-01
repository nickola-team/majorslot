<?php 
namespace VanguardLTE\Games\HottoBurnHoldandSpinPM
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
        public $credits = null;
        public $free_spins_in_base = [];
        public $free_spins_in_free = [];
        public $bonus_spins_in_base = [];
        public $bonus_spins_in_bonus = [];
        public $money_respin = [];
        public $base_money_chance = null;
        public $happyhouruser = null;
        public $gamesession = null; // session table
        public function __construct($sid, $playerId, $credits = null)
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
            $this->credits = $credits;
            $user = \VanguardLTE\User::lockForUpdate()->find($this->playerId);
            $this->happyhouruser = \VanguardLTE\HappyHourUser::where([
                'user_id' => $user->id, 
                'status' => 1,
                'time' => date('G')
            ])->first();
            $user->balance = $credits != null ? $credits : $user->balance;
            $this->user = $user;
            $this->shop_id = $user->shop_id;
            $game = \VanguardLTE\Game::lockForUpdate()->where([
                'name' => $this->slotId, 
                'shop_id' => $this->shop_id
            ])->first();
            if (!$game)
            {
                exit('unlogged');
            }
            $this->shop = \VanguardLTE\Shop::find($this->shop_id);
            $this->game = $game;
            $this->increaseRTP = rand(0, 1);

            $this->free_spins_in_base = [0, 0, 0, 5, 0, 0];
            $this->free_spins_in_free = [0, 0, 0, 3, 0, 0];
            $this->bonus_spins_in_base = [0, 0, 0, 0, 0, 0, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3];
            $this->bonus_spins_in_bonus = [0, 0, 0, 0, 0, 0, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3];
            $this->money_respin = [
                [40, 40, 10, 8, 1, 1, 5, 5, 5,   80, 15, 5, 2, 2, 2, 2, 1, 1, 1, 1,   95, 5, 1, 1, 1, 1],
                [13,13,13,13,13,13,13,13,13,  14,14,14,14,14,14,14,14,14,14,14,  15,15,15,15,15,15],
                [20,40,60,80,100,120,140,160,180,  200,220,240,260,280,300,320,340,360,380,400,  2000,4000,6000,8000,10000,20000]
            ];
            $this->base_money_chance = 14;

            $this->CurrentDenom = $this->game->denomination;
            $this->scaleMode = 0;
            $this->numFloat = 0;
            $this->Paytable[1] = [0,0,0,0,0,0];
            $this->Paytable[2] = [0,0,0,0,0,0];
            $this->Paytable[3] = [0,0,0,100,1000,5000];
            $this->Paytable[4] = [0,0,0,50,200,500];
            $this->Paytable[5] = [0,0,0,20,50,200];
            $this->Paytable[6] = [0,0,0,20,50,200];
            $this->Paytable[7] = [0,0,0,20,50,200];
            $this->Paytable[8] = [0,0,0,2,10,50];
            $this->Paytable[9] = [0,0,1,2,10,50];
            $this->Paytable[10] = [0,0,0,2,10,50];
            $this->Paytable[11] = [0,0,0,0,0,0];
            $this->Paytable[12] = [0,0,0,0,0,0];
            $this->Paytable[13] = [0,0,0,0,0,0];
            $this->Paytable[14] = [0,0,0,0,0,0];
            $this->Paytable[15] = [0,0,0,0,0,0];
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
            foreach( [
                'reelStripBonus1', 
                'reelStripBonus2', 
                'reelStripBonus3', 
                'reelStripBonus4', 
                'reelStripBonus5', 
                'reelStripBonus6'
            ] as $reelStrip ) 
            {
                if( count($reel->reelsStripBonus[$reelStrip]) ) 
                {
                    $this->$reelStrip = $reel->reelsStripBonus[$reelStrip];
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
                    266, 
                    297, 
                    4
                ], 
                [
                    559, 
                    297, 
                    1
                ], 
                [
                    848, 
                    297, 
                    1
                ]
            ];
            $this->slotBonusType = 0;
            $this->slotScatterType = 0;
            $this->splitScreen = false;
            $this->slotBonus = true;
            $this->slotGamble = false;
            $this->slotFastStop = 1;
            $this->slotExitUrl = '/';
            $this->slotWildMpl = 1;
            $this->GambleType = 1;
            $this->slotFreeCount = 8;
            $this->slotRespinCount = 4;
            $this->slotFreeMpl = 1;
            $this->slotViewState = ($game->slotViewState == '' ? 'Normal' : $game->slotViewState);
            $this->hideButtons = [];
            $this->jpgs = \VanguardLTE\JPG::where('shop_id', $this->shop_id)->lockForUpdate()->get();
            $this->Line = [1];
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
                25
            ];
            $this->Bet = explode(',', $game->bet); //[10.00,20.00,30.00,40.00,50.00,100.00,200.00,300.00,400.00,500.00,750.00,1000.00,2000.00,3000.00,4000.00,5000.00,6000.00,7000.00,8000.00,9000.00,10000.00,12000.00]; 
            $this->Balance = $user->balance;
            $this->SymbolGame = [
                '1', 
                '2', 
                '3', 
                '4', 
                '5', 
                '6', 
                '7', 
                '8', 
                '9', 
                '10', 
                '11', 
                '12'
            ];
            $this->Bank = $game->get_gamebank();
            $this->Percent = $this->shop->percent;
            $this->WinGamble = $game->rezerv;
            $this->slotDBId = $game->id;
            $this->slotCurrency = $user->shop->currency;
            // if( $user->count_balance == 0 ) 
            // {
            //     $this->Percent = 100;
            //     $this->slotJackPercent = 0;
            //     $this->slotJackPercent0 = 0;
            // }
            // if( !isset($this->user->session) || strlen($this->user->session) <= 0 ) 
            // {
            //     $this->user->session = serialize([]);
            // }
            // $this->gameData = unserialize($this->user->session);
            // session table 
            $game_session = \VanguardLTE\GameSession::lockForUpdate()->where([
                'user_id' => $this->playerId, 
                'game_id' => $this->slotDBId
            ])->first();
            if( !isset($game_session) || strlen($game_session->session) <= 0 ) 
            {
                $this->gameData = [];
            }else{
                $this->gameData = json_decode($game_session->session, true);
                $this->gamesession = $game_session;
            }

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
            $expire = strtotime(date('Y-m-d 8:0:0', strtotime("+7 days +16 hours")));
            $this->gameData[$key] = [
                'timelife' => $expire, 
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
            // $this->user->session = serialize($this->gameData);
            // $this->user->session_json = json_encode($this->gameData);
            // $this->user->save();
            // $this->user->refresh();
            // session table 
            $game_session = $this->gamesession;
            if($game_session == null){
                $game_session = \VanguardLTE\GameSession::create([
                    'user_id' => $this->playerId, 
                    'game_id' => $this->slotDBId,
                    'session' => json_encode($this->gameData)
                ]);
            }else{
                $game_session->session = json_encode($this->gameData);
                $game_session->save();
                $game_session->refresh();
            }
        }
        public function CheckBonusWin()
        {
            return 0;
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
                $jsonLog = json_decode($log->str);
                if( $jsonLog->responseEvent != 'gambleResult' ) 
                {
                    $this->lastEvent = $log->str;
                    break;
                }
            }
            if( isset($jsonLog) ) 
            {
                return $jsonLog;
            }
            else
            {
                return 'NULL';
            }
        }
        public function ClearJackpot($jid)
        {
            $game = $this->game;
            $game->{'jp_' . ($jid + 1)} = sprintf('%01.4f', 0);
            $game->save();
        }
        public function UpdateJackpots($bet)
        {
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
        public function GetBank($slotState = '')
        {
            if ($this->happyhouruser)
            {
                $this->Bank = $this->happyhouruser->current_bank;
                return $this->Bank / $this->CurrentDenom;
            }
        if( $this->isBonusStart || $slotState == 'bonus' || $slotState == 'doBonus' || $slotState == 'freespin' || $slotState == 'respin' ) 


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
            // $this->user->session = serialize($this->gameData);
            // $this->user->save();
            // $this->user->refresh();
            // $this->gameData = unserialize($this->user->session);
            return $this->user->count_balance;
        }
        public function InternalError($errcode)
        {
            $_obf_strlog = '';
            $_obf_strlog .= "\n";
            $_obf_strlog .= date("Y-m-d H:i:s") . ' ';
            $_obf_strlog .= ('{"responseEvent":"error","responseType":"' . $errcode . '","serverResponse":"InternalError"}');
            $_obf_strlog .= "\n";
            $_obf_strlog .= ' ############################################### ';
            $_obf_strlog .= "\n";
            $_obf_strinternallog = '';
            if( file_exists(storage_path('logs/') . $this->slotId . 'Internal.log') ) 
            {
                $_obf_strinternallog = file_get_contents(storage_path('logs/') . $this->slotId . 'Internal.log');
            }
            file_put_contents(storage_path('logs/') . $this->slotId . 'Internal.log', $_obf_strinternallog . $_obf_strlog);
            // exit( '{"responseEvent":"error","responseType":"' . $errcode . '","serverResponse":"InternalError"}' );
        }
        public function SetBank($slotState = '', $sum, $slotEvent = '')
        {
        if( $this->isBonusStart || $slotState == 'bonus' || $slotState == 'doBonus' || $slotState == 'freespin' || $slotState == 'respin' ) 


            {
                $slotState = 'bonus';
            }
            else
            {
                $slotState = '';
            }
            $sum = $sum * $this->CurrentDenom;
            $game = $this->game;
            if( $this->GetBank($slotState) + $sum < 0 ) 
            {
                if($slotState == 'bonus'){
                    $diffMoney = $this->GetBank($slotState) + $sum;
                    if ($this->happyhouruser){
                        $this->happyhouruser->increment('over_bank', abs($diffMoney));
                    }
                    else {
                        $normalbank = $game->get_gamebank('');
                        if ($normalbank + $diffMoney < 0)
                        {
                            $this->InternalError('Bank_   ' . $sum . '  CurrentBank_ ' . $this->GetBank($slotState) . ' CurrentState_ ' . $slotState);
                        }
                        $game->set_gamebank($diffMoney, 'inc', '');
                    }
                    $sum = $sum - $diffMoney;
                }else{
                    if ($sum < 0){
                        $this->InternalError('Bank_   ' . $sum . '  CurrentBank_ ' . $this->GetBank($slotState) . ' CurrentState_ ' . $slotState);
                    }
                }
            }
            $_obf_bonus_systemmoney = 0;
            if( $sum > 0 && $slotEvent == 'bet' ) 
            {
                $this->toGameBanks = 0;
                $this->toSlotJackBanks = 0;
                $this->toSysJackBanks = 0;
                $this->betProfit = 0;
                $_obf_currentpercent = $this->GetPercent();
                $_obf_bonus_percent = 20;
                $count_balance = $this->GetCountBalanceUser();
                $_allBets = $sum / $this->GetPercent() * 100;
                // if( $count_balance < $_allBets && $count_balance > 0 ) 
                // {
                //     $_subCountBalance = $count_balance;
                //     $_obf_diff_money = $_allBets - $_subCountBalance;
                //     $_obf_subavaliable_balance = $_subCountBalance / 100 * $this->GetPercent();
                //     $sum = $_obf_subavaliable_balance + $_obf_diff_money;
                //     $_obf_bonus_systemmoney = $_subCountBalance / 100 * $_obf_bonus_percent;
                // }
                // else if( $count_balance > 0 ) 
                // {
                    $_obf_bonus_systemmoney = $_allBets / 100 * $_obf_bonus_percent;
                // }
                for( $i = 0; $i < count($this->jpgs); $i++ ) 
                {
                    if( $count_balance < $_allBets && $count_balance > 0 ) 
                    {
                        $this->toSysJackBanks += ($count_balance / 100 * $this->jpgs[$i]->percent);
                    }
                    else if( $count_balance > 0 ) 
                    {
                        $this->toSysJackBanks += ($_allBets / 100 * $this->jpgs[$i]->percent);
                    }
                }
                $this->toGameBanks = $sum;
                $this->betProfit = $_allBets - $this->toGameBanks - $this->toSlotJackBanks - $this->toSysJackBanks;
            }
            if( $sum > 0 ) 
            {
                $this->toGameBanks = $sum;
            }
            if ($this->happyhouruser)
            {
                $this->happyhouruser->increment('current_bank', $sum);
                $this->happyhouruser->save();
            }
            else{
                if( $_obf_bonus_systemmoney > 0 ) 
                {
                    $sum -= $_obf_bonus_systemmoney;
                    $game->set_gamebank($_obf_bonus_systemmoney, 'inc', 'bonus');
                }
                $game->set_gamebank($sum, 'inc', $slotState);
                $game->save();
            }
            
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
            // $this->user->session = serialize($this->gameData);
            // $this->user->save();
            // $this->user->refresh();
            // $this->gameData = unserialize($this->user->session);
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
            // $this->user->session = serialize($this->gameData);
            // $this->user->save();
            // $this->user->refresh();
            // $this->gameData = unserialize($this->user->session);
            $user = $this->user;
            $this->Balance = $user->balance / $this->CurrentDenom;
            return $this->Balance;
        }
        public function SaveLogReport($spinSymbols, $bet, $lines, $win, $slotState, $isState = true)
        {
            $_obf_slotstate = $this->slotId . ' ' . $slotState;
            if( $slotState == 'freespin' ) 
            {
                $_obf_slotstate = $this->slotId . ' Free';
            }
            else if( $slotState == 'bet' ) 
            {
                $_obf_slotstate = $this->slotId . '';
            }
            else if( $slotState == 'slotGamble' ) 
            {
                $_obf_slotstate = $this->slotId . ' DG';
            }
            $game = $this->game;
            $game->increment('stat_in', $bet * $this->CurrentDenom);
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
            if($isState == true){
                $roundstr = $this->GetGameData($this->slotId . 'RoundID');
                \VanguardLTE\StatGame::create([
                    'user_id' => $this->playerId, 
                    'balance' => $this->GetBalance() * $this->CurrentDenom, 
                    'bet' => $bet * $this->CurrentDenom, 
                    'win' => $win * $this->CurrentDenom, 
                    'game' => $_obf_slotstate, 
                    'percent' => $this->toGameBanks, 
                    'percent_jps' => $this->toSysJackBanks, 
                    'percent_jpg' => $this->toSlotJackBanks, 
                    'profit' => $this->betProfit, 
                    'denomination' => $this->CurrentDenom, 
                    'shop_id' => $this->shop_id,
                    'roundid' => $roundstr,
                ]);
            }
        }
        public function saveGameLog($strLog, $roundID){
            \VanguardLTE\PPGameLog::create([
                'game_id' => $this->slotDBId, 
                'user_id' => $this->playerId, 
                'str' => $strLog, 
                'shop_id' => $this->shop_id,
                'roundid' => $roundID
            ]);            
        }
        public function GetWildCount(){
            $wildMaskCounts = $this->GetGameData($this->slotId . 'WildMaskCounts');
            $wildCounts = [3,3,3,3,3,3,3,3,4,4,4,4,4,4,4,4,4,5,6,7];
            $count = 0;
            for($i = 0; $i < 20; $i++){
                if($wildMaskCounts[$i] == 1){
                    $count++;
                }
            }
            if($count == 20){
                $wildMaskCounts = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                $count = 0;
            }

            $wildcount = 0;
            if($count > 18){
                for($i = 0; $i < 20; $i++){
                    if($wildMaskCounts[$i] == 0){
                        $wildMaskCounts[$i] = 1;
                        $wildcount = $wildCounts[$i];
                    }
                }
            }else{
                while(true){
                    $count_index = mt_rand(0, 19);
                    if($wildMaskCounts[$count_index] == 0){
                        $wildMaskCounts[$count_index] = 1;
                        $wildcount = $wildCounts[$count_index];
                        break;
                    }
                }
            }
            $this->SetGameData($this->slotId . 'WildMaskCounts', $wildMaskCounts);
            return $wildcount;
        }
        public function GetMoneyCount(){
            $moneyCounts = [
                [3,2],[3,2],[3,2],[3,2],[3,2],[3,2],[3,2],[3,2],[3,2],[3,1,1],
                [3,3],[3,3],[3,3],[3,3],[3,3],[3,3],[3,3],[3,3],[3,3],[3,2,1],
                [3,3,1],[3,3,1],[3,3,1],[3,2,2],[3,2,2],[3,3,1],[3,3,1],[3,3,1],[3,2,2],[3,2,2]
            ];
            $moneyMaskCounts = $this->GetGameData($this->slotId . 'DefaultMaskMoneyCount');
            $count = 0;
            for($i = 0; $i < 30; $i++){
                if($moneyMaskCounts[$i] == 1){
                    $count++;
                }
            }
            if($count == 30){
                $this->SetGameData($this->slotId . 'DefaultMaskMoneyCount', [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]);    
                $moneyMaskCounts = $this->GetGameData($this->slotId . 'DefaultMaskMoneyCount');
            }
            
            $moneyIndex = 0;
            if($count >= 28){
                for($i = 0; $i < 30; $i++){
                    if($moneyMaskCounts[$i] == 0){
                        $moneyMaskCounts[$i] = 1;
                        $moneyIndex = $i;
                    }
                }
            }else{
                while(true){
                    $moneyIndex = mt_rand(0, 29);
                    if($moneyMaskCounts[$moneyIndex] == 0){
                        $moneyMaskCounts[$moneyIndex] = 1;
                        break;
                    }
                }
            }
            $this->SetGameData($this->slotId . 'DefaultMaskMoneyCount', $moneyMaskCounts);
            return $moneyCounts[$moneyIndex];
        }
        public function GetFinalMoneyCount(){
            $moneyCounts = [
                [10,20,40,30],
                [12,13,14,15]
            ];
            $percent = rand(0, 100);
            $sum = 0;
            for($i = 0; $i < count($moneyCounts[0]); $i++){
                $sum = $sum + $moneyCounts[0][$i];
                if($sum >= $percent){
                    return $moneyCounts[1][$i];
                }
            }
            return $moneyCounts[1][0];
        }
        public function GetMoneyIndex($slotEvent){            
            if($slotEvent != 'bet'){
                $lastIndex = 19;
            }else{                
                $lastIndex = 18;
            }
            $moneyMaskIndexes = $this->GetGameData($this->slotId . 'MoneyMaskIndexes');
            $moneyIndexes = $this->GetGameData($this->slotId . 'MoneyIndexes');

            $count = 0;
            for($i = 0; $i < 20; $i++){
                if($moneyMaskIndexes[$i] == 1){
                    $count++;
                }
            }
            if($count == 20){
                $moneyMaskIndexes = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                $loopCount = $this->GetGameData($this->slotId . 'MoneyLoopCount');         
                $loopCount++;
                if($loopCount >= 30){
                    $this->SetGameData($this->slotId . 'MoneyLoopCount', 0);         
                    $this->SetGameData($this->slotId . 'MoneyIndexes', [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,3]);  
                }else{
                    $this->SetGameData($this->slotId . 'MoneyLoopCount', $loopCount); 
                    $diffCount = 5;
                    // if($slotEvent != 'bet'){
                    //     $diffCount = 5;
                    // }
                    if($loopCount % $diffCount == 0){
                        $this->SetGameData($this->slotId . 'MoneyIndexes', [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,2]);          
                    }else{
                        $this->SetGameData($this->slotId . 'MoneyIndexes', [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1]);          
                    }
                }
                $count = 0;
            }

            $moneyIndex = 0;
            if($count > $lastIndex - 2){
                for($i = 0; $i < 20; $i++){
                    if($moneyMaskIndexes[$i] == 0){
                        $moneyMaskIndexes[$i] = 1;
                        $moneyIndex = $moneyIndexes[$i];
                    }
                }
            }else{
                while(true){
                    $count_index = mt_rand(0, $lastIndex);
                    if($moneyMaskIndexes[$count_index] == 0){
                        $moneyMaskIndexes[$count_index] = 1;
                        $moneyIndex = $moneyIndexes[$count_index];
                        break;
                    }
                }
            }

            $startIndex = 0;
            if($moneyIndex  == 3){
                $startIndex = 20;
            }else if($moneyIndex == 2){
                $startIndex = 9;
            }
            $this->SetGameData($this->slotId . 'MoneyMaskIndexes', $moneyMaskIndexes);
            $percent = rand(0, 100);
            $sum = 0;
            for($i = $startIndex; $i < count($this->money_respin[0]); $i++){
                $sum = $sum + $this->money_respin[0][$i];
                if($sum >= $percent){
                    return $i;
                }
            }
            return 0; 
        }
        public function GetWheelValue(){
            $init_wheel_values = [
                [35, 30, 20, 5, 4, 3, 2, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
                [10,20,30,40,50,60,70,80,90,100,200,300,400,500,600,700,800,900,1000,2000,5000]
            ];
            $lastWheelIndex = 5;
            $percent = mt_rand(0, 100);
            if($percent >= 95){                
                $lastWheelIndex = 8;
            }else if($percent >= 90){
                $lastWheelIndex = 7;
            }else if($percent >= 70){
                $lastWheelIndex = 6;
            }
            $wheel_indexes = $this->GetRandomNumber(1, $lastWheelIndex, 5);
            $wheel_values = [];
            for($i = 0; $i < 2; $i++){
                for($k = 0; $k < 5; $k++){
                    array_push($wheel_values, $init_wheel_values[1][$wheel_indexes[$k] - 1]);
                }
            }
            return $wheel_values;
        }
        public function GetSpinSettings($garantType = 'doSpin', $bet, $lines)
        {
            $_obf_linecount = 10;
            switch( $lines ) 
            {
                case 10:
                    $_obf_linecount = 10;
                    break;
                case 9:
                case 8:
                    $_obf_linecount = 9;
                    break;
                case 7:
                case 6:
                    $_obf_linecount = 7;
                    break;
                case 5:
                case 4:
                    $_obf_linecount = 5;
                    break;
                case 3:
                case 2:
                    $_obf_linecount = 3;
                    break;
                case 1:
                    $_obf_linecount = 1;
                    break;
                default:
                    $_obf_linecount = 10;
                    break;
            }
            if( $garantType != 'doSpin' ) 
            {
                $_obf_granttype = '_bonus';
            }
            else
            {
                $_obf_granttype = '';
            }
            $bonusWin = 0;
            $spinWin = 0;
            $game = $this->game;
            $_obf_grantwin_count = $game->{'garant_win' . $_obf_granttype . $_obf_linecount};
            $_obf_grantbonus_count = $game->{'garant_bonus' . $_obf_granttype . $_obf_linecount};
            $_obf_winbonus_count = $game->{'winbonus' . $_obf_granttype . $_obf_linecount};
            $_obf_winline_count = $game->{'winline' . $_obf_granttype . $_obf_linecount};
            $_obf_grantwin_count++;
            $_obf_grantbonus_count++;
            $return = [
                'none', 
                0
            ];
            if( $_obf_winbonus_count <= $_obf_grantbonus_count ) 
            {
                $bonusWin = 1;
                $_obf_grantbonus_count = 0;
                $game->{'winbonus' . $_obf_granttype . $_obf_linecount} = $this->getNewSpin($game, 0, 1, $lines, $garantType);
            }
            else if( $_obf_winline_count <= $_obf_grantwin_count ) 
            {
                $spinWin = 1;
                $_obf_grantwin_count = 0;
                $game->{'winline' . $_obf_granttype . $_obf_linecount} = $this->getNewSpin($game, 1, 0, $lines, $garantType);
            }
            $game->{'garant_win' . $_obf_granttype . $_obf_linecount} = $_obf_grantwin_count;
            $game->{'garant_bonus' . $_obf_granttype . $_obf_linecount} = $_obf_grantbonus_count;
            $game->save();
            // if ($this->happyhouruser)
            // {
            //     $bonus_spin = rand(1, 10);
            //     $spin_percent = 5;
            //     if ($garantType == 'freespin')
            //     {
            //         $spin_percent = 3;
            //     }
            //     $spinWin = ($bonus_spin < $spin_percent) ? 1 : 0;
            // }
            if( $bonusWin == 1 && $this->slotBonus ) 
            {
                $this->isBonusStart = true;
                $garantType = 'bonus';
                $_obf_currentbank = $this->GetBank($garantType);
                $return = [
                    'bonus', 
                    $_obf_currentbank
                ];
                if( $_obf_currentbank < ($this->CheckBonusWin() * $bet) ) 
                {
                    $return = [
                        'none', 
                        0
                    ];
                }
            }
            else if( $spinWin == 1 || $bonusWin == 1 && !$this->slotBonus ) 
            {
                $_obf_currentbank = $this->GetBank($garantType);
                $return = [
                    'win', 
                    $_obf_currentbank
                ];
            }
            if( $garantType == 'bet' && $this->GetBalance() <= (1 / $this->CurrentDenom) ) 
            {
                $_obf_rand = rand(1, 2);
                if( $_obf_rand == 1 ) 
                {
                    $_obf_currentbank = $this->GetBank('');
                    $return = [
                        'win', 
                        $_obf_currentbank
                    ];
                }
            }
            return $return;
        }
        public function getNewSpin($game, $spinWin = 0, $bonusWin = 0, $lines, $garantType = 'doSpin')
        {
            $_obf_linecount = 10;
            switch( $lines ) 
            {
                case 10:
                    $_obf_linecount = 10;
                    break;
                case 9:
                case 8:
                    $_obf_linecount = 9;
                    break;
                case 7:
                case 6:
                    $_obf_linecount = 7;
                    break;
                case 5:
                case 4:
                    $_obf_linecount = 5;
                    break;
                case 3:
                case 2:
                    $_obf_linecount = 3;
                    break;
                case 1:
                    $_obf_linecount = 1;
                    break;
                default:
                    $_obf_linecount = 10;
                    break;
            }
            if( $garantType != 'doSpin' ) 
            {
                $_obf_granttype = '_bonus';
            }
            else
            {
                $_obf_granttype = '';
            }
            if( $spinWin ) 
            {
                $win = explode(',', $game->game_win->{'winline' . $_obf_granttype . $_obf_linecount});
            }
            if( $bonusWin ) 
            {
                $win = explode(',', $game->game_win->{'winbonus' . $_obf_granttype . $_obf_linecount});
            }
            $number = rand(0, count($win) - 1);
            return $win[$number];
        }
        public function GetRandomScatterPos($rp, $scatterCount)
        {
            $_obf_scatterposes = [];
            for( $i = 0; $i < count($rp) - 3; $i++ ) 
            {
                if($scatterCount == 3 && $rp[$i] == '11' && $rp[$i + 1] == '11' && $rp[$i + 2] == '11') 
                {
                    array_push($_obf_scatterposes, $i);
                }else if($scatterCount == 2 && (($rp[$i] == '11' && $rp[$i + 1] == '11' && $rp[$i + 2] != '11') || ($rp[$i] != '11' && $rp[$i + 1] == '11' && $rp[$i + 2] == '11'))){
                    array_push($_obf_scatterposes, $i);
                }else if($scatterCount == 1 && (($rp[$i] == '11' && $rp[$i + 1] != '11' && $rp[$i + 2] != '11') || ($rp[$i] != '11' && $rp[$i + 1] == '11' && $rp[$i + 2] != '11') || ($rp[$i] != '11' && $rp[$i + 1] != '11' && $rp[$i + 2] == '11'))){
                    array_push($_obf_scatterposes, $i);
                }
            }
            shuffle($_obf_scatterposes);
            if( !isset($_obf_scatterposes[0]) ) 
            {
                $_obf_scatterposes[0] = rand(2, count($rp) - 3);
            }
            return $_obf_scatterposes[0];
        }
        public function GetGambleSettings()
        {
            $spinWin = rand(1, $this->WinGamble);
            return $spinWin;
        }
        public function SetBet() 
        { 
           if($this->GetGameData($this->slotId . 'Bet') == null) 
           { 
               $this->SetGameData($this->slotId . 'Bet', 0); 
           } 
           if($this->GetGameData($this->slotId . 'Lines') == null) 
           { 
               $this->SetGameData($this->slotId . 'Lines', 0); 
           } 
           $this->game->allBet = $this->GetGameData($this->slotId . 'Bet') * $this->GetGameData($this->slotId . 'Lines'); 
        } 


        public function GetReelStrips($winType, $slotEvent, $defaultMoneyCounts)
        {
            if( $winType != 'bonus' ) 
            {
                $_obf_reelStripCounts = [];
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
                        $_obf_reelStripCounts[$index + 1] = mt_rand(0, count($this->$reelStrip) - 3);
                    }
                }
            }
            else
            {
                $_obf_reelStripNumber = $this->GetRandomNumber(1, 5, 5);
                // $_obf_reelStripNumber = [
                //     1, 
                //     2, 
                //     3, 
                //     4, 
                //     5
                // ];
                shuffle($_obf_reelStripNumber);
                for( $i = 0; $i < count($_obf_reelStripNumber); $i++ ) 
                {
                    if( $i < count($defaultMoneyCounts) ) 
                    {
                        $_obf_reelStripCounts[$_obf_reelStripNumber[$i]] = $this->GetRandomScatterPos($this->{'reelStrip' . $_obf_reelStripNumber[$i]}, $defaultMoneyCounts[$i]);
                    }
                    else
                    {
                        $_obf_reelStripCounts[$_obf_reelStripNumber[$i]] = rand(0, count($this->{'reelStrip' . $_obf_reelStripNumber[$i]}) - 3);
                    }
                }
            }
            
            $reel = [
                'rp' => []
            ];
            foreach( $_obf_reelStripCounts as $index => $value ) 
            {
                $key = $this->{'reelStrip' . $index};
                if($slotEvent=='freespin'){
                    $key = $this->{'reelStripBonus' . $index};
                }
                $rc = count($key);
                $key[-1] = $key[$rc - 1];
                $key[$rc] = $key[0];
                $reel['reel' . $index][-1] = $key[$value - 1];
                $reel['reel' . $index][0] = $key[$value];
                $reel['reel' . $index][1] = $key[$value + 1];
                $reel['reel' . $index][2] = $key[$value + 2];
                $reel['reel' . $index][3] = $key[$value + 3];
                $reel['rp'][] = $value;
            }
            return $reel;
        }

        public function GetRandomNumber($num_first=0, $num_last=1, $get_cnt=4){
            $random = [];
            $tmp_random = [];
            $ino = 0;
            for($i=$num_first;$i<=$num_last;$i++) {
                $tmp_random[$ino] = $i;
                $ino++;
            }
            $tmp_cnt = count($tmp_random);
            $tmp_last = $tmp_cnt - 1;
            for($i=0;$i<$get_cnt;$i++) {
                $tmp_no=mt_rand(0,$tmp_last);
                $random[$i] = $tmp_random[$tmp_no];
                $tno = 0;
                for($j=0;$j<$tmp_cnt;$j++) {
                    if($random[$i] != $tmp_random[$j]) {
                        $tmp_random[$tno] = $tmp_random[$j];               
                        $tno++;
                    }
                }
                $tmp_cnt = $tno;
                $tmp_last = $tmp_cnt - 1;
            }
            return $random;
        }
    }

}

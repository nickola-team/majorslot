<?php 
namespace VanguardLTE\Games\DuoFuDuoCai88Fortune
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
        public $money_jackpot = [];
        public $base_moon_chance = null;
        public $happyhouruser = null;
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
            $user->balance = $credits != null ? $credits : $user->balance;
            $this->user = $user;
            $this->shop_id = $user->shop_id;
            $game = \VanguardLTE\Game::where([
                'name' => $this->slotId, 
                'shop_id' => $this->shop_id
            ])->lockForUpdate()->first();
            $this->happyhouruser = \VanguardLTE\HappyHourUser::where([
                'user_id' => $user->id, 
                'status' => 1,
                'time' => date('G')
            ])->first();
            $this->shop = \VanguardLTE\Shop::find($this->shop_id);
            $this->game = $game;
            $this->increaseRTP = rand(0, 1);

            $this->CurrentDenom = $this->game->denomination;
            $this->scaleMode = 0;
            $this->numFloat = 0;
            $this->Paytable[0]  = [0,0,0,0,0,0];
            $this->Paytable[1]  = [0,0,0,100,200,1000];
            $this->Paytable[2]  = [0,0,0,50,100,500];
            $this->Paytable[3]  = [0,0,0,40,80,400];
            $this->Paytable[4]  = [0,0,0,25,50,250];
            $this->Paytable[5]  = [0,0,0,10,20,100];
            $this->Paytable[6]  = [0,0,0,5,10,50];
            $this->Paytable[7]  = [0,0,0,5,10,50];
            $this->Paytable[8]  = [0,0,0,5,10,50];
            $this->Paytable[9]  = [0,0,0,5,10,50];
            $this->Paytable[10] = [0,0,0,5,10,50];
            $this->Paytable[11] = [0,0,0,5,10,50];
            $this->Paytable[12] = [0,0,0,0,0,0];
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
            $this->slotReelsConfig = [
                [
                    266, 
                    297, 
                    1
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
            $this->slotFreeCount = 6;
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
            $this->Bet = explode(',', $game->bet); //[0.01,0.02,0.05,0.10,0.25,0.50,1.00,3.00,5.00]; 
            $this->Balance = $user->balance;
            $this->SymbolGame = [
                '0',
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
            $diffIndex = 86400;
            $this->gameData[$key] = [
                'timelife' => time() + $diffIndex, 
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
            $ratioCount = 0;
            $totalPayRatio = 0;
            foreach( $this->Paytable as $vl ) 
            {
                foreach( $vl as $payRatio ) 
                {
                    if( $payRatio > 0 ) 
                    {
                        $ratioCount++;
                        $totalPayRatio += $payRatio;
                        break;
                    }
                }
            }
            return $totalPayRatio / $ratioCount;
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
            $type = -1;
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
                if( $this->jpgs[$i]->pay_sum_new < $_obf_0D0E13392A1E352D293108251212135B0D022529241422[$i] && $this->jpgs[$i]->pay_sum_new > 0 ) 
                {
                    $_obf_0D052A14092A1117372103081A331C2C2622010A2D0C22 = $this->jpgs[$i]->pay_sum_new / $this->CurrentDenom;
                    $_obf_0D0E13392A1E352D293108251212135B0D022529241422[$i] = $_obf_0D0E13392A1E352D293108251212135B0D022529241422[$i] - $this->jpgs[$i]->pay_sum_new;
                    $this->SetBalance($this->jpgs[$i]->pay_sum_new / $this->CurrentDenom);
                    $type = $i;

                    if( $this->jpgs[$i]->pay_sum_new > 0 ) 
                    {
                        \VanguardLTE\StatGame::create([
                            'user_id' => $this->playerId, 
                            'balance' => $this->Balance * $this->CurrentDenom, 
                            'bet' => 0, 
                            'win' => $this->jpgs[$i]->pay_sum_new, 
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
                $this->Jackpots['jackType'] = $type;
            }
        }
        public function getAdminJackpot(){
            $jackpots = [0, 0, 0, 0];
            $jackpots[0] = $this->jpgs[0]->balance;
            $jackpots[1] = $this->jpgs[2]->balance;
            $jackpots[2] = $this->jpgs[3]->balance;
            $jackpots[3] = $this->jpgs[4]->balance;
            return $jackpots;
        }
        public function getUpdateJackpot(){
            $diffs = [rand(40, 50), rand(30, 40), rand(10, 20), rand(0, 10)];
            $jackpots = [0, 0, 0, 0];
            for($i = 0; $i < 4; $i++){
                $jackpots[$i] = $this->jpgs[$i]->fake_balance + $diffs[$i];
                if($this->jpgs[$i]->pay_sum_new > 0 && $jackpots[$i] > $this->jpgs[$i]->pay_sum_new){
                    //$jackpots[$i] = $jpgs[$i]->start_balance;
                    $jackpots[$i] = $this->jpgs[$i]->balance;
                }
                $ret = $this->jpgs[$i]->update(['fake_balance' => $jackpots[$i]]);
                // $jpgs[$i]->save();
            }
            return $jackpots;
        }
        public function GetBank($slotState = '')
        {

            if ($this->happyhouruser)
            {
                $this->Bank = $this->happyhouruser->current_bank;
                return $this->Bank / $this->CurrentDenom;
            }

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
            $_obf_strlog = '';
            $_obf_strlog .= "\n";
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
            $_obf_bonus_systemmoney = 0;
            if( $sum > 0 && $slotEvent == 'bet' ) 
            {
                $this->toGameBanks = 0;
                $this->toSlotJackBanks = 0;
                $this->toSysJackBanks = 0;
                $this->betProfit = 0;
                $_obf_currentpercent = $this->GetPercent();
                $_obf_bonus_percent = 10;
                $count_balance = $this->GetCountBalanceUser();
                $_allBets = $sum / $this->GetPercent() * 100;
                if( $count_balance < $_allBets && $count_balance > 0 ) 
                {
                    $_subCountBalance = $count_balance;
                    $_obf_diff_money = $_allBets - $_subCountBalance;
                    $_obf_subavaliable_balance = $_subCountBalance / 100 * $this->GetPercent();
                    $sum = $_obf_subavaliable_balance + $_obf_diff_money;
                    $_obf_bonus_systemmoney = $_subCountBalance / 100 * $_obf_bonus_percent;
                }
                else if( $count_balance > 0 ) 
                {
                    $_obf_bonus_systemmoney = $_allBets / 100 * $_obf_bonus_percent;
                }
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
                // if current bank has small balance, finish this happy hour.
                /*if ($this->happyhouruser->current_bank + $sum < abs($sum))
                {
                    $this->happyhouruser->update(['status' => 2]);
                } */
                $this->happyhouruser->increment('current_bank', $sum);
                $this->happyhouruser->save();
            }
            else
            {
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
            $_obf_slotstate = $this->slotId . ' ' . $slotState;
            if( $slotState == 'freespin' ) 
            {
                $_obf_slotstate = $this->slotId . ' FG';
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
            \VanguardLTE\StatGame::create([
                'user_id' => $this->playerId, 
                'balance' => $this->Balance * $this->CurrentDenom, 
                'bet' => $bet * $this->CurrentDenom, 
                'win' => $win * $this->CurrentDenom, 
                'game' => $_obf_slotstate, 
                'percent' => $this->toGameBanks, 
                'percent_jps' => $this->toSysJackBanks, 
                'percent_jpg' => $this->toSlotJackBanks, 
                'profit' => $this->betProfit, 
                'denomination' => $this->CurrentDenom, 
                'shop_id' => $this->shop_id
            ]);
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
            if ($this->happyhouruser)
            {
                $bonus_spin = rand(1, 10);
                $bonus_percent = 5;
                $spin_percent = 8;
                if ($this->happyhouruser->current_bank <= $bet * 100)
                {
                    $bonus_percent = 3;
                }
                if ($bonus_spin <= $bonus_percent) {
                    $bonusWin = 1;
                    $spinWin = 0;
                }
                else if ($bonus_spin < $spin_percent)
                {
                    $bonusWin = 0;
                    $spinWin = 1;
                }
                else
                {
                    $bonusWin = 0;
                    $spinWin = 0;
                }
            }
            
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
        public function GetRandomScatterPos($rp)
        {
            $_obf_scatterposes = [];
            for( $i = 0; $i < count($rp); $i++ ) 
            {
                if( $rp[$i] == '12' ) 
                {
                    array_push($_obf_scatterposes, $i);
                    // if( $this->slotReelsConfig[0][2] == 4 ) 
                    // {
                    //     if( isset($rp[$i + 1]) && isset($rp[$i + 2]) && isset($rp[$i + 3]) ) 
                    //     {
                    //         array_push($_obf_scatterposes, $i);
                    //     }
                    //     if( isset($rp[$i - 1]) && isset($rp[$i + 1]) && isset($rp[$i + 2]) ) 
                    //     {
                    //         array_push($_obf_scatterposes, $i - 1);
                    //     }
                    //     if( isset($rp[$i - 2]) && isset($rp[$i - 1]) && isset($rp[$i + 1]) ) 
                    //     {
                    //         array_push($_obf_scatterposes, $i - 2);
                    //     }
                    //     if( isset($rp[$i - 3]) && isset($rp[$i - 2]) && isset($rp[$i - 1]) ) 
                    //     {
                    //         array_push($_obf_scatterposes, $i - 3);
                    //     }
                    // }
                    // else
                    // {
                    //     if( isset($rp[$i + 1]) && isset($rp[$i + 2]) ) 
                    //     {
                    //         array_push($_obf_scatterposes, $i);
                    //     }
                    //     if( isset($rp[$i - 1]) && isset($rp[$i + 1]) ) 
                    //     {
                    //         array_push($_obf_scatterposes, $i - 1);
                    //     }
                    //     if( isset($rp[$i - 2]) && isset($rp[$i - 1]) ) 
                    //     {
                    //         array_push($_obf_scatterposes, $i - 2);
                    //     }
                    // }
                }
            }
            shuffle($_obf_scatterposes);
            if( !isset($_obf_scatterposes[0]) ) 
            {
                $_obf_scatterposes[0] = rand(2, count($rp) - 3);
            }
            return $_obf_scatterposes[0];
        }
        public function GenerateFreeSpinCount($slotEvent){
            $freeSpins = [
                [70, 20, 10],
                [3, 4, 5]
            ];
            $percent = rand(0, 100);
            $sum = 0;
            for($i = 0; $i < count($freeSpins[0]); $i++){
                $sum = $sum + $freeSpins[0][$i];
                if($percent <= $sum){
                    return $freeSpins[1][$i];
                }
            }
            return $freeSpins[1][0];
        }
        public function getFreeSpinStack($freeSpinOdd){
            $freeStacks = \VanguardLTE\GameFreeSpinEF::where([
                'game_id' => $this->game->original_id
                ])->where('odd', '<=', $freeSpinOdd)->get();
            if(count($freeStacks) == 0){
                return [0, '[]'];
            }else{
                $freeStack = $freeStacks[rand(0, count($freeStacks) - 1)];
                return [$freeStack->odd, $freeStack->free_spin_stack];
            }
        }
        public function GetGambleSettings()
        {
            $spinWin = rand(1, $this->WinGamble);
            return $spinWin;
        }
        public function GetReelStrips($winType, $slotEvent)
        {
            $isScatter = false;
            if($slotEvent=='freespin'){
                if( $winType != 'bonus' ) 
                {
                    $_obf_reelStripCounts = [];
                    foreach( [
                        'reelStripBonus1', 
                        'reelStripBonus2', 
                        'reelStripBonus3', 
                        'reelStripBonus4', 
                        'reelStripBonus5', 
                        'reelStripBonus6'
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
                    $_obf_reelStripNumber = [
                        1, 
                        2, 
                        3, 
                        4, 
                        5
                    ];
                    for( $i = 0; $i < count($_obf_reelStripNumber); $i++ ) 
                    {
                        if( $i == 0 || $i == 2 || $i == 4 ) 
                        {
                            $_obf_reelStripCounts[$_obf_reelStripNumber[$i]] = $this->GetRandomScatterPos($this->{'reelStripBonus' . $_obf_reelStripNumber[$i]});
                        }
                        else
                        {
                            $_obf_reelStripCounts[$_obf_reelStripNumber[$i]] = rand(0, count($this->{'reelStripBonus' . $_obf_reelStripNumber[$i]}) - 3);
                        }
                    }
                }
            }else{
                if( $winType != 'bonus' ) 
                {
                    $_obf_reelStripCounts = [];
                    foreach( [
                        'reelStrip1', 
                        'reelStrip2', 
                        'reelStrip3', 
                        'reelStrip4', 
                        'reelStrip5'
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
                    $_obf_reelStripNumber = [
                        1, 
                        2, 
                        3, 
                        4, 
                        5
                    ];
                    $scattercount =  $this->GenerateFreeSpinCount($slotEvent);
                    // $scatterStripReelNumber = $this->GetRandomNumber(0, 4, $scattercount);
                    for( $i = 0; $i < count($_obf_reelStripNumber); $i++ ) 
                    {
                        if($i < $scattercount){
                            $_obf_reelStripCounts[$_obf_reelStripNumber[$i]] = $this->GetRandomScatterPos($this->{'reelStrip'. $_obf_reelStripNumber[$i]});
                            $isScatter = true;
                        }else{
                            $_obf_reelStripCounts[$_obf_reelStripNumber[$i]] = rand(0, count($this->{'reelStrip'. $_obf_reelStripNumber[$i]}) - 3);
                        }
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
                $reel['reel' . $index][3] = $key[$value + 3];
                if($isScatter == false){
                    $reel['reel' . $index][0] = $key[$value];
                    $reel['reel' . $index][1] = $key[$value + 1];
                    $reel['reel' . $index][2] = $key[$value + 2];
                }else{
                    $scatterPos = rand(0, 100);
                    if($scatterPos < 30){
                        $scatterPos = 0;
                    }else if($scatterPos < 60){
                        $scatterPos = 1;
                    }else if($scatterPos < 100){
                        $scatterPos = 2;
                    }
                    $reel['reel' . $index][0] = $key[abs($value - $scatterPos) % $rc];
                    $reel['reel' . $index][1] = $key[abs($value + (1 - $scatterPos)) % $rc];
                    $reel['reel' . $index][2] = $key[abs($value + (2 - $scatterPos)) % $rc];
                }
                $scatterPos = -1;
                $wildPos = -1;
                for($r = 0; $r < 3; $r++){
                    if($reel['reel' . $index][$r] == 12){
                        $scatterPos = $r;
                    }else if($reel['reel' . $index][$r] == 0){
                        $wildPos = $r;
                    }
                }
                if($scatterPos >= 0 && $wildPos >= 0){
                    if(rand(0, 100) < 50){
                        $reel['reel' . $index][$wildPos] = rand(8, 11);
                        $wildPos = -1;
                    }else{
                        $reel['reel' . $index][$scatterPos] = rand(8, 11);
                        $scatterPos = -1;
                    }
                }
                // if($index > 1 && $index < 5){
                //     $reel['reel' . $index][$scatterPos] = 0;
                // }

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

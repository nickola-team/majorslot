<?php 
namespace VanguardLTE\Games\PirateGoldPM
{
    class SlotSettings
    {
        public $playerId = null;
        public $splitScreen = null;
        public $reelStrip1_1 = null;
        public $reelStrip1_2 = null;
        public $reelStrip1_3 = null;
        public $reelStrip1_4 = null;
        public $reelStrip1_5 = null;
        public $reelStrip1_6 = null;

        public $reelStrip2_1 = null;
        public $reelStrip2_2 = null;
        public $reelStrip2_3 = null;
        public $reelStrip2_4 = null;
        public $reelStrip2_5 = null;
        public $reelStrip2_6 = null;

        public $slotId = '';
        public $slotDBId = '';
        public $Line = null;
        public $scaleMode = null;
        public $numFloat = null;
        // public $gameLine = null;
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
        public $PayTable = [];
        public $PayLines = [];
        public $MoneyTable = [];
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
        public $freespinCount = [];
        public $doubleWildChance = null;

        /* 프리스핀 관련 */
        public $freeSpinTable = [];
        public $reelsetMap = [];

        public $happyhouruser = null;

        public function __construct($sid, $playerId, $credits = null)
        {
            $this->slotId = $sid;
            $this->playerId = $playerId;
            $this->credits = $credits;
            $user = \VanguardLTE\User::lockForUpdate()->find($this->playerId);
            // $this->happyhouruser = \VanguardLTE\HappyHourUser::where([
            //     'user_id' => $user->id, 
            //     'status' => 1,
            //     'time' => date('G')
            // ])->first();

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

            // $this->doubleWildChance = 90;

            $this->CurrentDenom = $this->game->denomination;
            $this->scaleMode = 0;
            $this->numFloat = 0;

            $this->PayTable = [
                [0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0],
                [500, 100, 20, 2, 0],
                [500, 100, 20, 2, 0],
                [300, 50, 15, 2, 0],
                [300, 50, 15, 2, 0],
                [200, 40, 10, 2, 0],
                [200, 40, 10, 2, 0],
                [75, 25, 5, 0, 0],
                [75, 25, 5, 0, 0],
                [50, 15, 5, 0, 0],
                [50, 15, 5, 0, 0],
                [0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0],
            ];
            
            /* 40 lines */
            $this->PayLines = [
                [0, 1, 2, 3, 4],
                [15, 16, 17, 18, 19],
                [5, 6, 7, 8, 9],
                [10, 11, 12, 13, 14],
                [0, 6, 12, 8, 4],
                [15, 11, 7, 13, 19],
                [10, 6, 2, 8, 14],
                [5, 11, 17, 13, 9],
                [0, 6, 2, 8, 4],
                [15, 11, 17, 13, 19],
                [5, 1, 7, 3, 9],
                [10, 16, 12, 18, 14],
                [5, 11, 7, 13, 9],
                [10, 6, 12, 8, 14],
                [0, 6, 7, 8, 4],
                [15, 11, 12, 13, 19],
                [5, 1, 2, 3, 9],
                [10, 16, 17, 18, 14],
                [5, 11, 12, 13, 9],
                [10, 6, 7, 8, 14],
                [0, 1, 7, 3, 4],
                [15, 16, 12, 18, 19],
                [5, 6, 2, 8, 9],
                [10, 11, 17, 13, 14],
                [5, 6, 12, 8, 9],
                [10, 11, 7, 13, 14],
                [0, 1, 12, 3, 4],
                [15, 16, 7, 18, 19],
                [10, 11, 2, 13, 14],
                [5, 6, 17, 8, 9],
                [0, 11, 12, 13, 4],
                [15, 6, 7, 8, 19],
                [10, 1, 2, 3, 14],
                [5, 16, 17, 18, 9],
                [5, 1, 12, 3, 9],
                [10, 16, 7, 18, 14],
                [5, 11, 2, 13, 9],
                [10, 6, 17, 8, 14],
                [0, 11, 2, 13, 4],
                [15, 6, 17, 8, 19],
            ];

            $this->MoneyTable = [
                "standard" => [40, 80, 120, 160/* , 200, 240, 280, 320, 400, 560, 640, 720, 800, 960, 2000, 8000 */],
                "jackpot" => [2000, 8000, 40000],
                "jackpot_mask" => ["jp3", "jp2", "jp1"]
            ];

            // $this->freeSpinTable = [
            //     [0, 0, 0, 15, 18, 25, 30],    // Raining WILD
            //     [0, 0, 0, 7, 12, 15, 20],    // Sticky WILD 
            // ];

            $this->reelsetMap = [
                'freespin' => [1],
                'spin' => [0]
            ];

            $reel = new GameReel();
            foreach( array_keys($reel->reelsStrip) as $reelStrip ) 
            {
                if( count($reel->reelsStrip[$reelStrip]) ) 
                {
                    $this->$reelStrip = $reel->reelsStrip[$reelStrip];
                }
            }
            // $this->slotReelsConfig = [
            //     [
            //         266, 
            //         297, 
            //         1
            //     ], 
            //     [
            //         559, 
            //         297, 
            //         1
            //     ], 
            //     [
            //         848, 
            //         297, 
            //         1
            //     ]
            // ];
            $this->slotBonusType = 0;
            $this->slotScatterType = 0;
            $this->splitScreen = false;
            $this->slotBonus = true;
            $this->slotGamble = false;
            $this->slotFastStop = 1;
            $this->slotExitUrl = '/';
            $this->slotWildMpl = 1;
            $this->GambleType = 1;
            $this->slotFreeMpl = 1;
            $this->slotViewState = ($game->slotViewState == '' ? 'Normal' : $game->slotViewState);
            $this->hideButtons = [];
            $this->jpgs = \VanguardLTE\JPG::where('shop_id', $this->shop_id)->lockForUpdate()->get();
            // $this->Line = [1];
            // $this->gameLine = [
            //     1, 
            //     2, 
            //     3, 
            //     4, 
            //     5, 
            //     6, 
            //     7, 
            //     8, 
            //     9, 
            //     10, 
            //     11, 
            //     12, 
            //     13, 
            //     14, 
            //     15,
            //     16,
            //     17,
            //     18,
            //     19,
            //     20
            // ];
            $this->Bet = explode(',', $game->bet); //[0.01,0.02,0.05,0.10,0.25,0.50,1.00,3.00,5.00]; 
            $this->Balance = $user->balance;
            // $this->SymbolGame = [
            //     '1', 
            //     '2', 
            //     '3', 
            //     '4', 
            //     '5', 
            //     '6', 
            //     '7', 
            //     '8', 
            //     '9', 
            //     '10', 
            //     '11', 
            //     '12',
            //     '13'
            // ];
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
            foreach( $this->PayTable as $vl ) 
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
            $this->lastEvent = NULL;
            foreach( $history as $log ) 
            {
                $jsonLog = json_decode($log->str);
                $this->lastEvent = $jsonLog;
                break;
                // $jsonLog = json_decode($log->str);
                // if( $jsonLog->responseEvent != 'gambleResult' ) 
                // {
                //     $this->lastEvent = $log->str;
                //     break;
                // }
            }
            if( isset($jsonLog) ) 
            {
                return $jsonLog;
            }
            else
            {
                return NULL;
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
            // if ($this->happyhouruser)
            // {
            //     $this->Bank = $this->happyhouruser->current_bank;
            //     return $this->Bank / $this->CurrentDenom;
            // }

            if( $this->isBonusStart || $slotState == 'bonus' || $slotState == 'fsSticky' || $slotState == 'fsRaining' ) 
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
        public function SetBank(/* $slotState = '',  */$slotEvent = '', $sum, $isBuyFreespin = -1)
        {
            if( $this->isBonusStart || $slotEvent == 'bonus' || $slotEvent == 'freespin' || $slotEvent == 'luckyspin' ) 
            {
                $slotState = 'bonus';
            }
            else
            {
                $slotState = '';
            }
            $sum = $sum * $this->CurrentDenom;
            $game = $this->game;
            if($isBuyFreespin == 0){
                $game->set_gamebank($sum, 'inc', 'bonus');
                $game->save();
                return $game;
            }
            if( $this->GetBank($slotState) + $sum < 0 ) 
            {
                if($slotState == 'bonus'){
                    $diffMoney = $this->GetBank($slotState) + $sum;
                    // if ($this->happyhouruser){
                    //     $this->happyhouruser->increment('over_bank', abs($diffMoney));
                    // }
                    // else {
                        $normalbank = $game->get_gamebank('');
                        if ($normalbank + $diffMoney < 0)
                        {
                            $this->InternalError('Bank_   ' . $sum . '  CurrentBank_ ' . $this->GetBank($slotState) . ' CurrentState_ ' . $slotState);
                        }
                        $game->set_gamebank($diffMoney, 'inc', '');
                    // }
                    $sum = $sum - $diffMoney;
                }else{
                    if ($sum < 0){
                        $this->InternalError('Bank_   ' . $sum . '  CurrentBank_ ' . $this->GetBank($slotState) . ' CurrentState_ ' . $slotState);
                    }
                }
            }
            $_obf_bonus_systemmoney = 0;
            if( $sum > 0 && $slotEvent == 'doSpin' ) 
            {
                $this->toGameBanks = 0;
                $this->toSlotJackBanks = 0;
                $this->toSysJackBanks = 0;
                $this->betProfit = 0;
                $_obf_currentpercent = $this->GetPercent();
                $_obf_bonus_percent = 10;
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
            // if ($this->happyhouruser)
            // {
            //     $this->happyhouruser->increment('current_bank', $sum);
            //     $this->happyhouruser->save();
            // }
            // else
            // {
                if( $_obf_bonus_systemmoney > 0 ) 
                {
                    $sum -= $_obf_bonus_systemmoney;
                    $game->set_gamebank($_obf_bonus_systemmoney, 'inc', 'bonus');
                }
                $game->set_gamebank($sum, 'inc', $slotState);
                $game->save();
            // }
            return $game;
        }
        public function SetBalance($sum, $slotEvent = '')
        {
            if( $this->GetBalance() + $sum < 0 ) 
            {
                $this->InternalError('Balance_   ' . $sum);
                exit( '{"responseEvent":"error","responseType":"balane is low to add ' . $sum . '","serverResponse":"InternalError"}' );
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
            if( $slotState == 'fsStart' ) 
            {
                $_obf_slotstate = $this->slotId . '';
            }
            else if( $slotState == 'fsSticky' ) 
            {
                $_obf_slotstate = $this->slotId . ' FG';
            }
            else if( $slotState == 'fsRaining' ) 
            {
                $_obf_slotstate = $this->slotId . ' FG1';
            }
            else if( $slotState == 'doSpin' ) 
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
        public function GetSpinSettings($grantType = 'doSpin', $bet, $lines/* , $isdoublechance */)
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
            if( $grantType != 'doSpin')
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
                $game->{'winbonus' . $_obf_granttype . $_obf_linecount} = $this->getNewSpin($game, 0, 1, $lines, $grantType);
            }
            else if( $_obf_winline_count <= $_obf_grantwin_count ) 
            {
                $spinWin = 1;
                $_obf_grantwin_count = 0;
                $game->{'winline' . $_obf_granttype . $_obf_linecount} = $this->getNewSpin($game, 1, 0, $lines, $grantType);
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
                if ($grantType == 'freespin') {
                    /* 프리스핀중에는 프리스핀만 발동 */
                    $bonusType = 'bonus';
                }
                else {
                    /* 보너스당첨인 경우 프리스핀, 럭키스핀 둘중에 랜덤선택 */
                    $bonusType = (random_int(1, 9) % 2) == 0 ? 'bonus' : 'lucky';
                }
                
                $this->isBonusStart = true;
                $grantType = 'bonus';
                $_obf_currentbank = $this->GetBank($grantType);

                $return = [
                    $bonusType, 
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
                $_obf_currentbank = $this->GetBank($grantType);
                $return = [
                    'win', 
                    $_obf_currentbank
                ];
            }
            if( $grantType == 'bet' && $this->GetBalance() <= (1 / $this->CurrentDenom) ) 
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
        public function getNewSpin($game, $spinWin = 0, $bonusWin = 0, $lines, $grantType = 'doSpin')
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
            if( $grantType != 'doSpin' && $grantType != 'tumbspin' ) 
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
            $number = random_int(0, count($win) - 1);
            return $win[$number];
        }

        public function GetRandomMoneySymbolPos($symbols) {
            $MONEY = 13;
            $_obf_moneyposes = [];
            for( $i = 0; $i < count($symbols); $i++ ) 
            {
                if( $symbols[$i] == $MONEY ) 
                {
                    array_push($_obf_moneyposes, $i);
                }
            }
            shuffle($_obf_moneyposes);
            if( !isset($_obf_moneyposes[0]) ) 
            {
                $_obf_moneyposes[0] = random_int(2, count($symbols) - 3);
            }
            return $_obf_moneyposes[0];
        }

        public function GetRandomScatterPos($symbols)
        {
            $_obf_scatterposes = [];
            for( $i = 0; $i < count($symbols); $i++ ) 
            {
                if( $symbols[$i] == '1' ) 
                {
                    array_push($_obf_scatterposes, $i);
                }
            }
            shuffle($_obf_scatterposes);
            if( !isset($_obf_scatterposes[0]) ) 
            {
                $_obf_scatterposes[0] = random_int(2, count($symbols) - 3);
            }
            return $_obf_scatterposes[0];
        }

        public function GetGambleSettings()
        {
            $spinWin = random_int(1, $this->WinGamble);
            return $spinWin;
        }

        public function GetMultiValue($default = 1){ // 와일드 배당값 결정
            $mul = $default;
            $sum = random_int(0, 100);
            if($sum <= 50){
                $mul = $default;
            }else if($sum <= 80){
                $mul = 2;
            }else{
                $mul = 3;
            }
            return $mul;
        }
        public function GetFreeSpin($index){
            $freespins = [0, 0, 0, 0, 12, 17, 22];
            return $freespins[$index]; // 프리스핀갯수 
        }
        public function GenerateScatterCount(){ // Scatter수 생성 함수
            $freeSpins = [
                [75, 20, 4, 1],
                [3, 4, 5, 6]
            ];
            $percent = random_int(0, 100);
            $sum = 0;
            for($i = 0; $i < count($freeSpins[0]); $i++){
                $sum = $sum + $freeSpins[0][$i];
                if($percent <= $sum){
                    return $freeSpins[1][$i];
                }
            }
            return $freeSpins[1][0];  
        }
        
        public function GetSymbolCount($default = 2){   // 한개 릴의 심볼 갯수를 결정하는 함수
            $sum = random_int(0, 100);
            if($sum <= 40){
                $ret = 2;
            }else if($sum <= 60){
                $ret = 3;
            }else if($sum <= 70){
                $ret = 4;
            }else if($sum <= 85){
                $ret = 5;
            }else if($sum <= 95){
                $ret = 6;
            }else{
                $ret = 7;
            }

            return $ret < $default ? $default : $ret;
        }

        public function GetLastReel($lastReel, $lastBinaryReel, $bonusTumbPoses, $bonusTumbMpls){    // 텀브스핀일때 당첨된 심볼들을 제외한 릴배치도 얻기
            $reels = [[0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0],[0,0,0,0,0,0,0,0]];
            $newTumbPoses = [];
            $newTumbMuls = [];
            for($i = 0; $i < count($bonusTumbPoses); $i++){
                if($lastBinaryReel[$bonusTumbPoses[$i]] == 0){  // 당첨되지않은 와일드 멀티값얻기
                    array_push($newTumbPoses, $bonusTumbPoses[$i]); 
                    array_push($newTumbMuls, $bonusTumbMpls[$i]);
                }
            }
            for($i = 6; $i < 48; $i++){
                if($lastBinaryReel[$i] > 0){
                    $lastReel[$i] = -1;
                }
                $reels[floor($i % 6)][$i / 6] = $lastReel[$i];
            }
            for($i = 5; $i >= 0; $i--){
                if($lastBinaryReel[$i] > 0){
                    $lastReel[$i] = -1;
                }
                $reels[floor((5 - $i) % 6)][0] = $lastReel[$i];
            }
            for($i = 0; $i < 6; $i++){
                for($j = 7; $j >=2; $j--){
                    if($reels[$i][$j] == -1){
                        $k = 1;
                        while($j > $k){
                            if($reels[$i][$j -$k] != -1){
                                $reels[$i][$j] = $reels[$i][$j -$k];
                                $reels[$i][$j -$k] = -1;
                                for($bi = 0; $bi < count($newTumbPoses); $bi++){
                                    if($newTumbPoses[$bi] == ($j -$k) * 6 + $i){
                                        $newTumbPoses[$bi] = ($j) * 6 + $i;
                                    }
                                }
                                break;
                            }else{
                                $k++;
                            }
                        }                 
                    }
                }
            }
            for($i = 1; $i < 4; $i++){
                if($reels[$i][0] == -1){
                    $k = 1;
                    while($k + $i < 5){
                        if($reels[$i + $k][0] != -1){
                            $reels[$i][0] = $reels[$i + $k][0];
                            $reels[$i + $k][0] = -1;
                            for($bi = 0; $bi < count($newTumbPoses); $bi++){
                                if($newTumbPoses[$bi] == 5 - ($i + $k)){
                                    $newTumbPoses[$bi] = 5 - $i;
                                }
                            }
                            break;
                        }else{
                            $k++;
                        }
                    }
                }
            }
            return [$reels, $newTumbPoses, $newTumbMuls];   // 당첨되지않은 심볼배렬, 와일드 위치, 와일드 배당값
        }
        public function GetLimitedReelStrips($slotEvent, $lastWILDCollection) {
            $REELCOUNT = 5;

            /* 당첨금이 제일 작은 심볼중 하나 선택 */
            $startSymbols = [random_int(7, 12), random_int(7, 12)];     

            for ($reelId=1; $reelId <= $REELCOUNT; $reelId++) { 

                /* 스티키 프리스핀일때 릴의 최소 심볼갯수 계산 */
                if ($reelId == 1) {
                    $symbolCount = 2;
                }
                else if ($slotEvent === 'fsSticky' && count($lastWILDCollection) > 0) {
                    $minCount = 2;
                    foreach ($lastWILDCollection as $pos => $multiplier) {
                        $wildReelId = $pos % $REELCOUNT + 1;
                        if ($reelId == $wildReelId) {
                            $symbolsCount = intdiv($pos, $REELCOUNT) + 1;
                            $minCount = $minCount > $symbolsCount ? $minCount : $symbolsCount;
                        }
                    }
                    
                    $symbolCount = $this->GetSymbolCount($minCount);
                }
                else {
                    $symbolCount = random_int(2, 3);
                }

                $reel['reel' . $reelId][-1] = random_int(7, 12);

                for($k = 0; $k < $symbolCount; $k++){
                    if ($reelId == 1) {
                        $reel['reel' . $reelId][$k] = $startSymbols[$k];
                    }
                    else {
                        while (in_array(($symbol = random_int(4, 10)), $startSymbols));
                        $reel['reel' . $reelId][$k] = $symbol;
                    }
                }
                for($k = $symbolCount; $k < 7; $k++){
                    $reel['reel' . $reelId][$k] = 14;
                }

                while (in_array(($symbol = random_int(9, 12)), $startSymbols));
                $reel['reel' . $reelId][7] = $symbol;
            }
            $reel['id'] = 8;        // 랜덤
            return $reel;
        }

        public function GetReelStrips($winType, $slotEvent/* ,  $scatterCount, $lastWILDCollection */)
        {
            /* 5x4 릴셋 */
            $REELCOUNT = 5;
            $SYMBOLCOUNT = 4;
            $BONUS = 1;
            $basePosOfReels = [];

            /* 릴셋 찾기 */
            if ($winType == 'bonus') {
                $reelsetIds = $this->reelsetMap['freespin'];
            }
            else if (array_key_exists($slotEvent, $this->reelsetMap)) {
                $reelsetIds = $this->reelsetMap[$slotEvent];
            }
            else {
                $reelsetIds = $this->reelsetMap['spin'];
            }
            $reelsetId = $reelsetIds[array_rand($reelsetIds)];

            if ($winType == 'bonus')
            {
                /* 프리스핀일때 */
                for( $reelId = 0; $reelId < $REELCOUNT; $reelId++ ) 
                {
                    /* 2,3,4 릴에만 스캐터심볼 존재 */
                    if ($reelId == 0 || $reelId == $REELCOUNT - 1) {
                        $basePosOfReels[$reelId] = random_int(2, count($this->{"reelStrip{$reelsetId}_$reelId"}) - 3);
                    }
                    else {
                        $basePosOfReels[$reelId] = $this->GetRandomScatterPos($this->{"reelStrip{$reelsetId}_$reelId"});
                    }
                }
            }
            else if ($winType == 'lucky') {
                /* Lucky Treasure */

                /* 머니심볼을 배치할 2개의 랜덤릴 선택 */
                $moneySymbolReelIds = array_rand([0, 1, 2, 3, 4], 2);

                for( $reelId = 0; $reelId < $REELCOUNT; $reelId++ ) 
                {
                    if (array_search($reelId, array_values($moneySymbolReelIds)) !== false) {
                        $basePosOfReels[$reelId] = $this->GetRandomMoneySymbolPos($this->{"reelStrip{$reelsetId}_$reelId"});
                    }
                    else {
                        $basePosOfReels[$reelId] = random_int(2, count($this->{"reelStrip{$reelsetId}_$reelId"}) - 3);
                    }
                }
            }
            else  
            {
                /* 프리스핀도, Lucky Treasure 도 아닐때 */
                foreach([
                    'reelStrip'.$reelsetId.'_0', 
                    'reelStrip'.$reelsetId.'_1', 
                    'reelStrip'.$reelsetId.'_2', 
                    'reelStrip'.$reelsetId.'_3', 
                    'reelStrip'.$reelsetId.'_4', 
                ] as $index => $reelStrip ) 
                {
                    if( is_array($this->$reelStrip) && count($this->$reelStrip) > 0 ) 
                    {
                        $basePosOfReels[$index] = random_int(2, count($this->$reelStrip) - 3);
                    }
                }
            }
            
            $reel = [
                'symbols' => [],
                'symbolsAfter' => [],
                'symbolsBefore' => [],
                'rp' => []
            ];
            foreach( $basePosOfReels as $reelId => $basePos ) 
            {
                $orgReelSymbols = $this->{'reelStrip'.$reelsetId . '_' . $reelId};
                $orgReelSymbolsCount = count($orgReelSymbols);

                // Main릴배치도 생성
                array_push($reel['symbolsBefore'], random_int(7, 12));
                array_push($reel['symbolsAfter'], random_int(7, 12));

                /* basePos 중심으로 10개 심볼을 택하고 그중 4개의 랜덤 심볼 선택 */
                $startIdx = ($basePos - 3 < 0) ? 0 : ($basePos - 3);
                $length = ($basePos + 3 >= $orgReelSymbolsCount) ? ($orgReelSymbolsCount - $startIdx) : ($basePos + 3 - $startIdx);
                $slicedSymbols = array_slice($orgReelSymbols, $startIdx, $length);

                $randomSymbolIds = array_rand($slicedSymbols, $SYMBOLCOUNT);
                // shuffle($slicedSymbols);

                foreach ($randomSymbolIds as $idx => $symbolId) {
                    $reel['symbols'][$reelId][$idx] = $slicedSymbols[$symbolId];
                }

                /* 프리스핀 경우 보너스심볼 확인, 없으면 추가 */
                if ($winType == 'bonus' && $reelId != 0 && $reelId != 4) {
                    $bonusExists = array_search($BONUS, $reel['symbols'][$reelId]);
                    
                    if (!$bonusExists) {
                        $randId = random_int(0, 3);
                        $reel['symbols'][$reelId][$randId] = $BONUS;
                    }
                }

                $reel['rp'][] = $basePos;
            }
            $reel['id'] = $reelsetId;
            return $reel;
        }

        public function GetLuckySpinSetting($flattenSymbols, $moneySymbolValues, $moneySymbolTypes, $maxMoneySymbolsCount, $respinCount) {
            $MONEY = 13;
            $moneySymbols = array_filter($flattenSymbols, function ($symbol) use ($MONEY) { return $symbol == $MONEY; });

            /* 머니심볼갯수가 최대갯수이상이면  */
            if (count($moneySymbols) >= $maxMoneySymbolsCount) {
                return false;
            }

            /* 머니심볼이 더 있어야 하며 리스핀 2회이면 무조건 머니심볼 추가 */
            if ($respinCount == 2) {
                return true;
            }
            else {
                /* 리스핀 2회아래이면 랜덤결과 */
                return (random_int(1, 10) % 4 === 1) ? true : false;
            }
        }

        public function GenerateMoneySymbols($flattenSymbols, $moneySymbolValues, $moneySymbolTypes) {
            $MONEY = 13;
            
            /* 머니심볼이 아닌 심볼들 검사 */
            $nonMoneySymbols = array_filter($flattenSymbols, function ($symbol) use ($MONEY) { return $symbol != $MONEY; });
            $randSymbolPos = array_rand($nonMoneySymbols);
            
            /* 랜덤 머니심볼로 교체 */
            $flattenSymbols[$randSymbolPos] = $MONEY;

            $moneySymbolValueId = array_rand($this->MoneyTable["standard"]);
            $moneySymbolValues[$randSymbolPos] = $this->MoneyTable["standard"][$moneySymbolValueId];

            $moneySymbolTypes[$randSymbolPos] = "v";

            return [
                'symbols' => $flattenSymbols,
                'values' => $moneySymbolValues,
                'types' => $moneySymbolTypes
            ];
        }

        public function SumMoneySymbols($moneySymbolValues, $moneySymbolTypes) {
            /* 배율심볼 계산 추가 */
            
            $total = array_sum($moneySymbolValues);
            return $total;
        }

        public function GetRandomNumber($num_first=0, $num_last=1, $get_cnt=3){
            $random = [];
            $tmp_random = [];
            $ino = 0;
            for($i=$num_first;$i<$num_last;$i++) {
                $tmp_random[$ino] = $i;
                $ino++;
            }
            $tmp_cnt = count($tmp_random);
            $tmp_last = $tmp_cnt - 1;
            for($i=0;$i<$get_cnt;$i++) {
                $tmp_no=random_int(0,$tmp_last);
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

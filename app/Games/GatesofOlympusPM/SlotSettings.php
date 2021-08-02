<?php 
namespace VanguardLTE\Games\GatesofOlympusPM
{
    class SlotSettings
    {
        public $playerId = null;
        public $splitScreen = null;

        public $reelStrip0_0 = null;
        public $reelStrip0_1 = null;
        public $reelStrip0_2 = null;
        public $reelStrip0_3 = null;
        public $reelStrip0_4 = null;
        public $reelStrip0_5 = null;
        public $reelStrip1_0 = null;
        public $reelStrip1_1 = null;
        public $reelStrip1_2 = null;
        public $reelStrip1_3 = null;
        public $reelStrip1_4 = null;
        public $reelStrip1_5 = null;
        public $reelStrip2_0 = null;
        public $reelStrip2_1 = null;
        public $reelStrip2_2 = null;
        public $reelStrip2_3 = null;
        public $reelStrip2_4 = null;
        public $reelStrip2_5 = null;
        public $reelStrip3_0 = null;
        public $reelStrip3_1 = null;
        public $reelStrip3_2 = null;
        public $reelStrip3_3 = null;
        public $reelStrip3_4 = null;
        public $reelStrip3_5 = null;
        public $reelStrip4_0 = null;
        public $reelStrip4_1 = null;
        public $reelStrip4_2 = null;
        public $reelStrip4_3 = null;
        public $reelStrip4_4 = null;
        public $reelStrip4_5 = null;
        public $reelStrip5_0 = null;
        public $reelStrip5_1 = null;
        public $reelStrip5_2 = null;
        public $reelStrip5_3 = null;
        public $reelStrip5_4 = null;
        public $reelStrip5_5 = null;
        public $reelStrip6_0 = null;
        public $reelStrip6_1 = null;
        public $reelStrip6_2 = null;
        public $reelStrip6_3 = null;
        public $reelStrip6_4 = null;
        public $reelStrip6_5 = null;
        public $reelStrip7_0 = null;
        public $reelStrip7_1 = null;
        public $reelStrip7_2 = null;
        public $reelStrip7_3 = null;
        public $reelStrip7_4 = null;
        public $reelStrip7_5 = null;

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
        public $PayTable = [];
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
        public $reelPositionMap = [];

        public $happyhouruser = null;

        /* 프리스핀 관련 */
        public $freeSpinTable = [];
        public $reelsetMap = [];

        public function __construct($sid, $playerId, $credits = null)
        {
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

            $this->doubleWildChance = 90;

            $this->CurrentDenom = $this->game->denomination;
            $this->scaleMode = 0;
            $this->numFloat = 0;
            $this->PayTable = [
                [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,2000,100,60,0,0,0],
                [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
                [1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,1000,500,500,200,200,0,0,0,0,0,0,0],
                [500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,500,200,200,50,50,0,0,0,0,0,0,0],
                [300,300,300,300,300,300,300,300,300,300,300,300,300,300,300,300,300,300,300,100,100,40,40,0,0,0,0,0,0,0],
                [240,240,240,240,240,240,240,240,240,240,240,240,240,240,240,240,240,240,240,40,40,30,30,0,0,0,0,0,0,0],
                [200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,200,30,30,20,20,0,0,0,0,0,0,0],
                [160,160,160,160,160,160,160,160,160,160,160,160,160,160,160,160,160,160,160,24,24,16,16,0,0,0,0,0,0,0],
                [100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,100,20,20,10,10,0,0,0,0,0,0,0],
                [80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,80,18,18,8,8,0,0,0,0,0,0,0],
                [40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,15,15,5,5,0,0,0,0,0,0,0],
                [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
            ];

            $this->reelsetMap = [
                'spin' => [0, 1, 2],
                'buy_freespin' => [6],
                'freespin' => [3, 4],
            ];

            $this->reelPositionMap = [
                3 => [
                    [1, 2, 2],
                    [1, 3, 1],
                    [2, 1, 2],
                    [2, 2, 1],
                    [2, 3],
                    [3, 2]
                ],
                5 => [
                    [1, 1, 1, 1, 1],
                    [1, 1, 1, 2],
                    [1, 1, 2, 1],
                    [1, 2, 1, 1],
                    [1, 2, 2],
                    [2, 1, 1, 1],
                    [2, 1, 2],
                    [2, 2, 1],
                ]
            ];

            $reel = new GameReel();
            foreach( [
                'reelStrip0_0',
                'reelStrip0_1',
                'reelStrip0_2',
                'reelStrip0_3',
                'reelStrip0_4',
                'reelStrip0_5',

                'reelStrip1_0',
                'reelStrip1_1',
                'reelStrip1_2',
                'reelStrip1_3',
                'reelStrip1_4',
                'reelStrip1_5',

                'reelStrip2_0',
                'reelStrip2_1',
                'reelStrip2_2',
                'reelStrip2_3',
                'reelStrip2_4',
                'reelStrip2_5',

                'reelStrip3_0',
                'reelStrip3_1',
                'reelStrip3_2',
                'reelStrip3_3',
                'reelStrip3_4',
                'reelStrip3_5',

                'reelStrip4_0',
                'reelStrip4_1',
                'reelStrip4_2',
                'reelStrip4_3',
                'reelStrip4_4',
                'reelStrip4_5',

                'reelStrip5_0',
                'reelStrip5_1',
                'reelStrip5_2',
                'reelStrip5_3',
                'reelStrip5_4',
                'reelStrip5_5',

                'reelStrip6_0',
                'reelStrip6_1',
                'reelStrip6_2',
                'reelStrip6_3',
                'reelStrip6_4',
                'reelStrip6_5',

                'reelStrip7_0',
                'reelStrip7_1',
                'reelStrip7_2',
                'reelStrip7_3',
                'reelStrip7_4',
                'reelStrip7_5',
            ] as $reelStrip ) 
            {
                if( count($reel->reelsStrip[$reelStrip]) ) 
                {
                    $this->$reelStrip = $reel->reelsStrip[$reelStrip];
                }
            }
          
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
                20
            ];
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
                return null;
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
            if ($this->happyhouruser)
            {
                $this->Bank = $this->happyhouruser->current_bank;
                return $this->Bank / $this->CurrentDenom;
            }
            if( $this->isBonusStart || $slotState == 'bonus' || $slotState == 'freespin') 
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
            if( $this->isBonusStart || $slotEvent == 'bonus' || $slotEvent == 'freespin') 
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
            if ($this->happyhouruser)
            {
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
                exit( '{"responseEvent":"error","responseType":"balane is low to add ' . $sum . '","serverResponse":"InternalError"}' );
            }
            $sum = $sum * $this->CurrentDenom;
            $user = $this->user;
            if( $sum < 0 && $slotEvent == 'doSpin' ) 
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
            if( $slotState == 'doBonus' ) 
            {
                $_obf_slotstate = $this->slotId . ' Bonus';
            }
            else if( $slotState == 'doSpin' || $slotState == 'buy_freespin') 
            {
                $_obf_slotstate = $this->slotId . '';
            }
            else if( $slotState == 'freespin' ) 
            {
                $_obf_slotstate = $this->slotId . ' FG';
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
        public function GetSpinSettings($garantType = 'doSpin', $bet, $lines, $isDouble)
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
            if( $garantType != 'doSpin' /* &&  $garantType != 'tumblespin' */) 
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

            if ($isDouble) {
                $_obf_grantwin_count++;
                $_obf_grantbonus_count++;
            }
            
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
                $spin_percent = 5;

                $spinWin = ($bonus_spin < $spin_percent) ? 1 : 0;
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
            if( $garantType == 'doSpin' && $this->GetBalance() <= (1 / $this->CurrentDenom) ) 
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
            if( $garantType != 'doSpin' /* && $garantType != 'tumblespin' */ ) 
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

        public function GenerateScatterCount($winType, $slotEvent){ // Scatter수 생성 함수
            if($winType == 'bonus'){      
                if ($slotEvent == 'freespin') {
                    /* 프리스핀중에는 스캐터 3개이상일때 보너스당첨 */
                    $probabilityMap = [
                        3 => 90,
                        4 => 10
                    ];
                }
                else {
                    $probabilityMap = [
                        4 => 95,
                        5 => 4,
                        6 => 1
                    ];
                }
            }
            else {
                $probabilityMap = [
                    0 => 80,
                    1 => 15,
                    2 => 5
                ];
            }
            
            $sum = array_sum(array_values($probabilityMap));
            $randNum = random_int(1, $sum);

            $sum = 0;
            foreach ($probabilityMap as $key => $probability) {
                $sum += $probability;
                if ($randNum <= $sum) {
                    return $key;
                }
            }

            return 0;  
        }

        public function GenerateMultiplierCount($winType, $slotEvent, $isTumble) {
            if ($slotEvent == 'freespin') {
                if (!$isTumble && $winType == 'none') {
                    $probabilityMap = [
                        0 => 60,
                        1 => 20,
                        2 => 20,
                    ];
                }
                else {
                    $probabilityMap = [
                        0 => 80,
                        1 => 20
                    ];
                }
            }
            else {
                $probabilityMap = [
                    0 => 85,
                    1 => 15
                ];
            }

            $sum = array_sum(array_values($probabilityMap));
            $randNum = random_int(1, $sum);

            $sum = 0;
            foreach ($probabilityMap as $key => $probability) {
                $sum += $probability;
                if ($randNum <= $sum) {
                    return $key;
                }
            }

            return 0;  
        }

        public function GenerateMultiplier($slotEvent, $winType, $isTumble) {
            if ($slotEvent == 'freespin') {
                if ($winType != 'none' || $isTumble) {
                    $probabilityMap = [
                        2 => 40,
                        3 => 30,
                        4 => 20,
                        5 => 5,
                        6 => 3,
                        8 => 2,
                        10 => 0,
                        12 => 0,
                        15 => 0,
                        20 => 0,
                        25 => 0,
                        50 => 0,
                        100 => 0,
                    ];
                }
                else {
                    $probabilityMap = [
                        2 => 20,
                        3 => 20,
                        4 => 20,
                        5 => 10,
                        6 => 10,
                        8 => 10,
                        10 => 7,
                        12 => 7,
                        15 => 5,
                        20 => 3,
                        25 => 2,
                        50 => 1,
                        100 => 0,
                    ];
                }
            }
            else {
                $probabilityMap = [
                    2 => 15,
                    3 => 15,
                    4 => 15,
                    5 => 10,
                    6 => 10,
                    8 => 10,
                    10 => 5,
                    12 => 3,
                    15 => 3,
                    20 => 2,
                    25 => 2,
                    50 => 1,
                    100 => 0,
                ];
            }
            
            $sum = array_sum(array_values($probabilityMap));
            $randNum = random_int(1, $sum);

            $sum = 0;
            foreach ($probabilityMap as $key => $probability) {
                $sum += $probability;
                if ($randNum <= $sum) {
                    return $key;
                }
            }

            return 0;
        }

        public function GetLimitedReelStrips($slotEvent, $lastWILDCollection) {
            $REELCOUNT = 6;

            return null;
        }

        public function GetReelStrips($winType, $slotEvent, $lastReels, $proposedScatterCount, $lastMULTIPLIERCollection)
        {
            $REELCOUNT = 6;
            $SYMBOLCOUNT = 5;
            $S_REMOVED = -1;
            $S_BLANK = 0;
            $S_SCATTER = 1;
            $S_MULTIPLIER = 12;

            $isTumble = ($lastReels != null && isset($lastReels->tmb));
            /* 멀티플라이어 심볼배열 */
            $multiplierSymbols = [];

            /* 생성될 멀티플라이어심볼 갯수 */
            $multiplierCount = $this->GenerateMultiplierCount($winType, $slotEvent, $isTumble);
            
            /* 보너스당첨이 있을경우 멀티플라이어는 생성하지 않는다 */
            if ($proposedScatterCount >= 3) {
                $multiplierCount = 0;
            }

            $multiplierReelId = random_int(0, $REELCOUNT - 1);
            $multiplierPos = random_int(0, $SYMBOLCOUNT - 1);
            
            /* 텀블스핀인 경우 기본릴셋은 유지, 텀블심볼만 재생성, */
            if ($isTumble) {
                /* 릴셋 역구성 */
                $lastFlattenSymbols = explode(",", $lastReels->s);
                $newSymbols = [];

                foreach ($lastFlattenSymbols as $pos => $symbol) {
                    $reelPos = intdiv($pos, $REELCOUNT);
                    $reelId = $pos % $REELCOUNT;
                    $newSymbols[$reelId][$reelPos] = $symbol;
                }

                /* tumble 심볼 삭제, 기타심볼 떨구기 */
                $tumbleSymbols = explode("~", $lastReels->tmb);

                foreach ($tumbleSymbols as $value) {
                    [$pos, $symbol] = explode(",", $value);
                    $reelPos = intdiv($pos, $REELCOUNT);
                    $reelId = $pos % $REELCOUNT;
                    $newSymbols[$reelId][$reelPos] = $S_REMOVED;
                }

                /* 텀블멀티플라이어 심볼 유지, 재정열 */
                foreach ($lastMULTIPLIERCollection as $pos => $value) {
                    $reelPos = intdiv($pos, $REELCOUNT);
                    $reelId = $pos % $REELCOUNT;

                    $removedSymbolCount = 0;
                    for ($i=$reelPos; $i < $SYMBOLCOUNT; $i++) { 
                        if ($newSymbols[$reelId][$i] == $S_REMOVED) {
                            $removedSymbolCount ++;
                        }
                    }
                    
                    $multiplierSymbols[$pos + $removedSymbolCount * $REELCOUNT] = $value;
                }

                for ($reelId=0; $reelId < $REELCOUNT; $reelId++) { 
                    $remainingReelSymbols = array_diff($newSymbols[$reelId], [$S_REMOVED]);

                    /* 릴에 삭제된 텀블심볼이 없다면 스킵 */
                    $removedSymbolCount = count($newSymbols[$reelId]) - count($remainingReelSymbols);
                    if ($removedSymbolCount > 0) {
                        $multiplierPos = -1;

                        if ($multiplierReelId >= $reelId && $multiplierCount > 0) {
                            $multiplierPos = random_int(0, $removedSymbolCount - 1);
                        }

                        /* 삭제된 텀블심볼 갯수만큼 랜덤심볼 추가, 재정렬 */
                        for ($i=0; $i < $removedSymbolCount; $i++) { 
                            /* 멀티플라이어 생성 */
                            if ($multiplierPos == $i) {
                                $newSymbol = $S_MULTIPLIER;    

                                /* 텀블인 경우 멀티플라이어 심볼은 최대 1개까지만 생성 */
                                $multiplierCount = 0;
                            }
                            else {
                                $newSymbol = random_int(3, 11);
                                if (count(array_keys($remainingReelSymbols, $newSymbol)) >= 2) {
                                    $newSymbol = random_int(3, 11);
                                }
                            }
                            
                            array_unshift($remainingReelSymbols, $newSymbol);
                        }
                        
                        $newSymbols[$reelId] = array_values($remainingReelSymbols);    
                    }
                }

                $reels = [
                    'id' => $lastReels->reel_set,
                    'symbols' => $newSymbols,
                    'flattenSymbols' => [],
                    'symbolsAfter' => explode(",", $lastReels->sa),
                    'symbolsBefore' => explode(",", $lastReels->sb),
                    'multiplierSymbols' => [],
                ];
            }
            else {
                /* 릴배치표 생성 */
                $reels = [
                    'id' => /* $reelsetId */1,
                    'symbols' => [],
                    'flattenSymbols' => [],
                    'symbolsAfter' => [],
                    'symbolsBefore' => [],
                    'multiplierSymbols' => [],
                ];
    
                $reelsetHeight = array_rand($this->reelPositionMap);
                $position = $this->reelPositionMap[$reelsetHeight];

                for ($reelId=0; $reelId < $REELCOUNT; $reelId++) { 
                    $positionSetId = array_rand($this->reelPositionMap[$reelsetHeight]);
                    $positionSet = $this->reelPositionMap[$reelsetHeight][$positionSetId];

                    $uniqueSymbols = [];
                    $curPos = 0;
                    $reels['symbols'][$reelId] = array_fill(0, $SYMBOLCOUNT, $S_BLANK);

                    foreach ($positionSet as $count) {
                        while( in_array( ($newSymbol = random_int(3, 11)), $uniqueSymbols));

                        $newSymbols = array_fill($curPos, $count, $newSymbol);
                        $reels['symbols'][$reelId] = array_replace($reels['symbols'][$reelId], $newSymbols);
    
                        $curPos += $count;
    
                        array_push($uniqueSymbols, $newSymbol);
                    }

                    array_push($reels['symbolsBefore'], random_int(3, 11));
                    array_push($reels['symbolsAfter'], random_int(3, 11));
                }

                /* 멀티플라이어심볼 생성 */
                for ($i=0; $i < $multiplierCount; $i++) { 
                    $multiplierReelId = random_int(0, $REELCOUNT - 1);
                    $multiplierPos = random_int(0, $SYMBOLCOUNT - 1);
                    $reels['symbols'][$multiplierReelId][$multiplierPos] = $S_MULTIPLIER;
                }

                /* 부족한 SCATTER 심볼 생성 */
                if ($proposedScatterCount > 0) {
                    $uniqueSymbols = [];
                    for ($i=0; $i < $proposedScatterCount; $i++) { 
                        while( in_array( ($randReelId = random_int(0, $REELCOUNT - 1)), $uniqueSymbols));

                        /* 스캐터심볼을 넣을수 있는 위치배열 */
                        $availablePoses = array_where($reels['symbols'][$randReelId], function ($symbol, $key) {
                            return $symbol != 1;
                        });

                        if (count($availablePoses) === 0 ) {
                            $i--;
                            continue;
                        }

                        $randomPos = array_rand($availablePoses);         
                        $reels['symbols'][$randReelId][$randomPos] = $S_SCATTER;

                        array_push($uniqueSymbols, $randReelId);
                    }    
                }
            }

            /* 릴배치표 평활화, 멀티플라이어 체크 */
            $flattenSymbols = [];
            foreach ($reels['symbols'] as $reelId => $symbols) {
                foreach ($symbols as $k => $symbol) {
                    /* 멀티플라이어 체크 */
                    if ($symbol == $S_MULTIPLIER) {
                        $pos = $reelId + $k * $REELCOUNT;

                        if (!array_key_exists($pos, $multiplierSymbols)) {
                            $multiplierSymbols[$pos] = $this->GenerateMultiplier($slotEvent, $winType, $lastReels != null);
                        }
                    }

                    $flattenSymbols[$reelId + $k * $REELCOUNT] = $symbol;
                }
            }
            ksort($flattenSymbols);

            $reels['flattenSymbols'] = $flattenSymbols;
            $reels['multiplierSymbols'] = $multiplierSymbols;
            return $reels;
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

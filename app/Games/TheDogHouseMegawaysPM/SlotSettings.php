<?php 
namespace VanguardLTE\Games\TheDogHouseMegawaysPM
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

        public $reelStrip3_1 = null;
        public $reelStrip3_2 = null;
        public $reelStrip3_3 = null;
        public $reelStrip3_4 = null;
        public $reelStrip3_5 = null;
        public $reelStrip3_6 = null;

        public $reelStrip4_1 = null;
        public $reelStrip4_2 = null;
        public $reelStrip4_3 = null;
        public $reelStrip4_4 = null;
        public $reelStrip4_5 = null;
        public $reelStrip4_6 = null;

        public $reelStrip5_1 = null;
        public $reelStrip5_2 = null;
        public $reelStrip5_3 = null;
        public $reelStrip5_4 = null;
        public $reelStrip5_5 = null;
        public $reelStrip5_6 = null;

        public $reelStrip6_1 = null;
        public $reelStrip6_2 = null;
        public $reelStrip6_3 = null;
        public $reelStrip6_4 = null;
        public $reelStrip6_5 = null;
        public $reelStrip6_6 = null;

        public $reelStrip7_1 = null;
        public $reelStrip7_2 = null;
        public $reelStrip7_3 = null;
        public $reelStrip7_4 = null;
        public $reelStrip7_5 = null;
        public $reelStrip7_6 = null;
        
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
        public $freespinCount = [];
        public $doubleWildChance = null;
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
            $this->Paytable[1] = [0,0,0,0,0,0,0];        //  멀티플라이어 ??
            $this->Paytable[2] = [0,0,0,0,0,0,0];
            $this->Paytable[3] = [0,0,0,15,40,60,150];
            $this->Paytable[4] = [0,0,0,10,20,30,60];
            $this->Paytable[5] = [0,0,0,7,15,20,40];
            $this->Paytable[6] = [0,0,0,4,10,15,30];
            $this->Paytable[7] = [0,0,0,3,8,10,30];
            $this->Paytable[8] = [0,0,0,3,8,10,30];
            $this->Paytable[9] = [0,0,0,2,4,6,20];
            $this->Paytable[10] = [0,0,0,2,4,6,20];
            $this->Paytable[11] = [0,0,0,2,4,6,20];
            $this->Paytable[12] = [0,0,0,1,2,4,10];
            $this->Paytable[13] = [0,0,0,1,2,4,10];

            $this->freeSpinTable = [
                [0, 0, 0, 15, 18, 25, 30],    // Raining WILD
                [0, 0, 0, 7, 12, 15, 20],    // Sticky WILD 
            ];

            $this->reelsetMap = [
                'fsStart' => [0],
                'fsSticky' => [10],
                'fsRaining' => [9],
                'spin' => [0, 1, 3, 4, 5, 6, 7, 8 ]
            ];

            $reel = new GameReel();
            foreach( [
                'reelStrip0_1',
                'reelStrip0_2',
                'reelStrip0_3',
                'reelStrip0_4',
                'reelStrip0_5',
                'reelStrip0_6',

                'reelStrip1_1', 
                'reelStrip1_2', 
                'reelStrip1_3', 
                'reelStrip1_4', 
                'reelStrip1_5', 
                'reelStrip1_6', 

                'reelStrip2_1', 
                'reelStrip2_2', 
                'reelStrip2_3', 
                'reelStrip2_4', 
                'reelStrip2_5', 
                'reelStrip2_6', 

                'reelStrip3_1', 
                'reelStrip3_2', 
                'reelStrip3_3', 
                'reelStrip3_4', 
                'reelStrip3_5', 
                'reelStrip3_6', 

                'reelStrip4_1', 
                'reelStrip4_2', 
                'reelStrip4_3', 
                'reelStrip4_4', 
                'reelStrip4_5', 
                'reelStrip4_6', 

                'reelStrip5_1', 
                'reelStrip5_2', 
                'reelStrip5_3', 
                'reelStrip5_4', 
                'reelStrip5_5', 
                'reelStrip5_6', 

                'reelStrip6_1', 
                'reelStrip6_2', 
                'reelStrip6_3', 
                'reelStrip6_4', 
                'reelStrip6_5', 
                'reelStrip6_6', 

                'reelStrip7_1', 
                'reelStrip7_2', 
                'reelStrip7_3', 
                'reelStrip7_4', 
                'reelStrip7_5', 
                'reelStrip7_6', 

                'reelStrip8_1', 
                'reelStrip8_2', 
                'reelStrip8_3', 
                'reelStrip8_4', 
                'reelStrip8_5', 
                'reelStrip8_6', 

                'reelStrip9_1', 
                'reelStrip9_2', 
                'reelStrip9_3', 
                'reelStrip9_4', 
                'reelStrip9_5', 
                'reelStrip9_6', 

                'reelStrip10_1', 
                'reelStrip10_2', 
                'reelStrip10_3', 
                'reelStrip10_4', 
                'reelStrip10_5', 
                'reelStrip10_6', 
            ] as $reelStrip ) 
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
            if ($this->happyhouruser)
            {
                $this->Bank = $this->happyhouruser->current_bank;
                return $this->Bank / $this->CurrentDenom;
            }
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
            //exit( '{"responseEvent":"error","responseType":"' . $errcode . '","serverResponse":"InternalError"}' );
        }
        public function SetBank(/* $slotState = '',  */$slotEvent = '', $sum, $isBuyFreespin = -1)
        {
            if( $this->isBonusStart || $slotEvent == 'bonus' || $slotEvent == 'fsSticky' || $slotEvent == 'fsRaining' ) 
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
            if( $sum > 0 && ($slotEvent == 'doSpin' || $slotEvent == 'bet')) 
            {
                $this->toGameBanks = 0;
                $this->toSlotJackBanks = 0;
                $this->toSysJackBanks = 0;
                $this->betProfit = 0;
                $_obf_currentpercent = $this->GetPercent();
                $_obf_bonus_percent = 10;
                $count_balance = $this->GetCountBalanceUser();
                $_allBets = $sum / $this->GetPercent() * 100;
                /*if( $count_balance < $_allBets && $count_balance > 0 ) 
                {
                    $_subCountBalance = $count_balance;
                    $_obf_diff_money = $_allBets - $_subCountBalance;
                    $_obf_subavaliable_balance = $_subCountBalance / 100 * $this->GetPercent();
                    $sum = $_obf_subavaliable_balance + $_obf_diff_money;
                    $_obf_bonus_systemmoney = $_subCountBalance / 100 * $_obf_bonus_percent;
                }
                else if( $count_balance > 0 ) 
                {*/
                    $_obf_bonus_systemmoney = $_allBets / 100 * $_obf_bonus_percent;
                //}
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
        public function GetSpinSettings($garantType = 'doSpin', $bet, $lines/* , $isdoublechance */)
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
            if( $garantType != 'doSpin' &&  $garantType != 'tumbspin') 
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
            // if($isdoublechance == 1){
            //     $_obf_grantwin_count+=2; // 더블기능일때 2증가
            //     $_obf_grantbonus_count+=2;
            // }else{
                $_obf_grantwin_count++;
                $_obf_grantbonus_count++;
            // }
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
                if ($garantType == 'freespin')
                {
                    $spin_percent = 3;
                }
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
            if( $garantType != 'doSpin' && $garantType != 'tumbspin' ) 
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
            $REELCOUNT = 6;

            /* 당첨금이 제일 작은 심볼중 하나 선택 */
            $startSymbols = [random_int(7, 13), random_int(7, 13)];

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

                $reel['reel' . $reelId][-1] = random_int(7, 13);

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

                while (in_array(($symbol = random_int(9, 13)), $startSymbols));
                $reel['reel' . $reelId][7] = $symbol;
            }
            $reel['id'] = 8;        // 랜덤
            return $reel;
        }

        public function GetReelStrips($winType, $slotEvent,  $scatterCount, $lastWILDCollection)
        {
            $REELCOUNT = 6;
            $isScatter = false;
            $basePosOfReels = [];

            /* 릴세트 찾기 */
            if (array_key_exists($slotEvent, $this->reelsetMap)) {
                $reelsetIds = $this->reelsetMap[$slotEvent];
            }
            else {
                $reelsetIds = $this->reelsetMap['spin'];
            }
            $reelsetId = $reelsetIds[array_rand($reelsetIds)];

            if( $winType != 'bonus' ) 
            {
                /* 프리스핀이 아닐때 */
                foreach( [
                    'reelStrip'.$reelsetId.'_1', 
                    'reelStrip'.$reelsetId.'_2', 
                    'reelStrip'.$reelsetId.'_3', 
                    'reelStrip'.$reelsetId.'_4', 
                    'reelStrip'.$reelsetId.'_5', 
                    'reelStrip'.$reelsetId.'_6', 
                ] as $index => $reelStrip ) 
                {
                    if( is_array($this->$reelStrip) && count($this->$reelStrip) > 0 ) 
                    {
                        $basePosOfReels[$index + 1] = random_int(0, count($this->$reelStrip));
                    }
                }
            }
            else
            {
                /* 프리스핀일때 */
                $scatterReelIds = $this->GetRandomNumber(1, 7, $scatterCount);
                for( $reelId = 1; $reelId <= $REELCOUNT; $reelId++ ) 
                {
                    if (array_search($reelId, $scatterReelIds) !== false) {
                        $basePosOfReels[$reelId] = $this->GetRandomScatterPos($this->{"reelStrip{$reelsetId}_$reelId"});
                        $isScatter = true;
                    }
                    else {
                        $basePosOfReels[$reelId] = random_int(0, count($this->{"reelStrip{$reelsetId}_$reelId"}));
                    }
                }
            }
            
            $reel = [
                'rp' => []
            ];
            foreach( $basePosOfReels as $reelId => $basePos ) 
            {
                $orgReelSymbols = $this->{'reelStrip'.$reelsetId . '_' . $reelId};
                $orgReelSymbolsCount = count($orgReelSymbols);

                /* 왜? */
                $orgReelSymbols[-1] = $orgReelSymbols[$orgReelSymbolsCount - 1];
                $orgReelSymbols[$orgReelSymbolsCount] = $orgReelSymbols[0];

                // Main릴배치도 생성
                $reel['reel' . $reelId][-1] = random_int(7, 13);

                /* 스티키 프리스핀일때 릴의 최소 심볼갯수 계산 */
                if ($slotEvent === 'fsSticky' && count($lastWILDCollection) > 0) {
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
                    $symbolCount = $this->GetSymbolCount();
                }
                
                $diffNum = 1;
                if($isScatter == false){
                    $scatterPos = 0;
                }else{
                    $scatterPos = random_int(0, $symbolCount - 1);
                }                
                for($k = 0; $k < $symbolCount; $k++){
                    $reel['reel' . $reelId][$k] = $orgReelSymbols[abs($basePos + ($k - $scatterPos) * $diffNum) % $orgReelSymbolsCount];
                }
                for($k = $symbolCount; $k < 7; $k++){
                    $reel['reel' . $reelId][$k] = 14;
                }
            
                $scatterPos = -1;
                $wildPos = -1;
                for($r = 0; $r < 7; $r++){
                    if($reel['reel' . $reelId][$r] == 1){
                        // if($isTumb == true){
                        //     $reel['reel' . $reelId][$r + 1] = random_int(7, 12);
                        // }else 

                        /* 이미 스캐터심볼이 잇다면 지우기 */
                        if($scatterPos >= 0){
                            $reel['reel' . $reelId][$r] = random_int(7, 13);
                        }else{
                            $scatterPos = $r;
                        }
                    }else if($reel['reel' . $reelId][$r] == 2){
                        /* 이미 와일드심볼이 잇다면 지우기 */
                        if($wildPos >= 0){
                            $reel['reel' . $reelId][$r] = random_int(7, 13);
                        }else{
                            $wildPos = $r;
                        }
                    }
                }

                /* 스캐터, 와일드심볼 둘다 잇을때 와일드 지우기 ?? */
                if($scatterPos >= 0){
                    if($wildPos >= 0){                        
                        $reel['reel' . $reelId][$wildPos] = random_int(7, 13);
                    }
                    $reel['reel' . $reelId][-1] = random_int(7, 13);
                    $reel['reel' . $reelId][7] = random_int(7, 13);
                }else{
                    /* 스캐터심볼이 없을때 */
                    $reel['reel' . $reelId][7] = random_int(7, 13);
                }

                $reel['rp'][] = $basePos;
            }
            $reel['id'] = $reelsetId;
            return $reel;
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

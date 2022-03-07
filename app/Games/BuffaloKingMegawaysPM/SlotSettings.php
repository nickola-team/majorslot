<?php 
namespace VanguardLTE\Games\BuffaloKingMegawaysPM
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
        public $reelStrip1_7 = null;
        public $reelStrip2_1 = null;
        public $reelStrip2_2 = null;
        public $reelStrip2_3 = null;
        public $reelStrip2_4 = null;
        public $reelStrip2_5 = null;
        public $reelStrip2_6 = null;
        public $reelStrip2_7 = null;
        public $reelStrip3_1 = null;
        public $reelStrip3_2 = null;
        public $reelStrip3_3 = null;
        public $reelStrip3_4 = null;
        public $reelStrip3_5 = null;
        public $reelStrip3_6 = null;
        public $reelStrip3_7 = null;
        public $reelStrip4_1 = null;
        public $reelStrip4_2 = null;
        public $reelStrip4_3 = null;
        public $reelStrip4_4 = null;
        public $reelStrip4_5 = null;
        public $reelStrip4_6 = null;
        public $reelStrip4_7 = null;
        public $reelStrip5_1 = null;
        public $reelStrip5_2 = null;
        public $reelStrip5_3 = null;
        public $reelStrip5_4 = null;
        public $reelStrip5_5 = null;
        public $reelStrip5_6 = null;
        public $reelStrip5_7 = null;
        public $reelStrip6_1 = null;
        public $reelStrip6_2 = null;
        public $reelStrip6_3 = null;
        public $reelStrip6_4 = null;
        public $reelStrip6_5 = null;
        public $reelStrip6_6 = null;
        public $reelStrip6_7 = null;
        public $reelStrip7_1 = null;
        public $reelStrip7_2 = null;
        public $reelStrip7_3 = null;
        public $reelStrip7_4 = null;
        public $reelStrip7_5 = null;
        public $reelStrip7_6 = null;
        public $reelStrip7_7 = null;
        
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
        public function __construct($sid, $playerId, $credits = null)
        {
            $this->slotId = $sid;
            $this->playerId = $playerId;
            $this->credits = $credits;
            $user = \VanguardLTE\User::lockForUpdate()->find($this->playerId);
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
            $this->happyhouruser = \VanguardLTE\HappyHourUser::where([
                'user_id' => $user->id, 
                'status' => 1,
                'time' => date('G')
            ])->first();
            $this->shop = \VanguardLTE\Shop::find($this->shop_id);
            $this->game = $game;
            $this->increaseRTP = rand(0, 1);

            $this->doubleWildChance = 90;

            $this->CurrentDenom = $this->game->denomination;
            $this->scaleMode = 0;
            $this->numFloat = 0;
            $this->Paytable[1] = [0,0,0,0,5,20,100];
            $this->Paytable[2] = [0,0,0,0,0,0,0];
            $this->Paytable[3] = [0,0,20,40,80,200,400];
            $this->Paytable[4] = [0,0,0,15,25,50,125];
            $this->Paytable[5] = [0,0,0,10,15,25,75];
            $this->Paytable[6] = [0,0,0,6,10,15,30];
            $this->Paytable[7] = [0,0,0,6,10,15,30];
            $this->Paytable[8] = [0,0,0,4,6,10,20];
            $this->Paytable[9] = [0,0,0,4,6,10,20];
            $this->Paytable[10] = [0,0,0,2,4,8,15];
            $this->Paytable[11] = [0,0,0,2,4,8,12];
            $this->Paytable[12] = [0,0,0,2,4,8,12];
            $this->Paytable[13] = [0,0,0,0,0,0,0];
            $reel = new GameReel();
            foreach( [
                'reelStrip1_1', 
                'reelStrip1_2', 
                'reelStrip1_3', 
                'reelStrip1_4', 
                'reelStrip1_5', 
                'reelStrip1_6', 
                'reelStrip1_7',
                'reelStrip2_1', 
                'reelStrip2_2', 
                'reelStrip2_3', 
                'reelStrip2_4', 
                'reelStrip2_5', 
                'reelStrip2_6', 
                'reelStrip2_7',
                'reelStrip3_1', 
                'reelStrip3_2', 
                'reelStrip3_3', 
                'reelStrip3_4', 
                'reelStrip3_5', 
                'reelStrip3_6', 
                'reelStrip3_7',
                'reelStrip4_1', 
                'reelStrip4_2', 
                'reelStrip4_3', 
                'reelStrip4_4', 
                'reelStrip4_5', 
                'reelStrip4_6', 
                'reelStrip4_7',
                'reelStrip5_1', 
                'reelStrip5_2', 
                'reelStrip5_3', 
                'reelStrip5_4', 
                'reelStrip5_5', 
                'reelStrip5_6', 
                'reelStrip5_7',
                'reelStrip6_1', 
                'reelStrip6_2', 
                'reelStrip6_3', 
                'reelStrip6_4', 
                'reelStrip6_5', 
                'reelStrip6_6', 
                'reelStrip6_7',
                'reelStrip7_1', 
                'reelStrip7_2', 
                'reelStrip7_3', 
                'reelStrip7_4', 
                'reelStrip7_5', 
                'reelStrip7_6', 
                'reelStrip7_7'    // _1~_6 MainReelArray, _7 TopReelArray
            ] as $reelStrip ) 
            {
                if( count($reel->reelsStrip[$reelStrip]) ) 
                {
                    $this->$reelStrip = $reel->reelsStrip[$reelStrip];
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
                '12',
                '13'
            ];
            $this->Bank = $game->get_gamebank();
            $this->Percent = $this->shop->percent;
            $this->WinGamble = $game->rezerv;
            $this->slotDBId = $game->id;
            $this->slotCurrency = $user->shop->currency;
            // if( !isset($this->user->session) || strlen($this->user->session) <= 0 ) 
            // {
            //     $this->user->session = serialize([]);
            // }
            // $this->gameData = unserialize($this->user->session);
            if( !isset($this->user->session_json) || strlen($this->user->session_json) <= 0 ) 
            {
                $this->user->session_json = json_encode([]);
            }
            $this->gameData = json_decode($this->user->session_json, true);
            
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
        public function genfree(){
            $reel = new GameReel();
            $reel->generationFreeStacks($this, $this->game->original_id);
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
            $this->user->session_json = json_encode($this->gameData);
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
            $history = \VanguardLTE\GameLog::whereRaw('game_id=? and user_id=? ORDER BY id DESC LIMIT 1', [
                $this->slotDBId, 
                $this->playerId
            ])->get();
            $this->lastEvent = null;
            foreach( $history as $log ) 
            {
                $jsonLog = json_decode($log->str);
                if( $jsonLog && $jsonLog->responseEvent != 'gambleResult' ) 
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
                return null;
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
            //exit( '{"responseEvent":"error","responseType":"' . $errcode . '","serverResponse":"InternalError"}' );
        }
        public function SetBank($slotState = '', $sum, $slotEvent = '', $isBuyFreespin = -1)
        {
            if( $this->isBonusStart || $slotState == 'bonus' || $slotState == 'freespin' || $slotState == 'respin' ) 
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
                    else{
                        $normalbank = $game->get_gamebank('');
                        if ($normalbank + $diffMoney < 0)
                        {
                            $this->InternalError('Bank_   ' . $sum . '  CurrentBank_ ' . $this->GetBank($slotState) . ' CurrentState_ ' . $slotState);
                        }
                        $game->set_gamebank($diffMoney, 'inc', '');
                    }
                    $sum = $sum - $diffMoney;

                }else{
                    if ($sum < 0) {
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
            else {
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
            if ($bet == 0 && $win == 0)
            {
                return;   
            }
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
                    'roundid' => $roundstr
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
        public function GetFreeStack($betLine, $freespinType)
        {
            $winAvaliableMoney = $this->GetBank('bonus');
            $limitOdd = floor($winAvaliableMoney / $betLine / 3);
            if($limitOdd < 30){
                $limitOdd = 30;
            }else if($limitOdd > 100){
                $limitOdd = 100;
            }
            $freeStacks = \VanguardLTE\PPGameFreeStack::whereRaw('game_id=? and free_spin_type=? and odd <=? and id not in(select freestack_id from w_ppgame_freestack_log where user_id=?) ORDER BY odd DESC LIMIT 20', [
                $this->game->original_id, 
                $freespinType,
                $limitOdd,
                $this->playerId
            ])->get();
            if(count($freeStacks) > 0){
                $freeStack = $freeStacks[rand(0, count($freeStacks) - 1)];
            }else{
                \VanguardLTE\PPGameFreeStackLog::where([
                    'user_id' => $this->playerId,
                    'game_id' => $this->game->original_id
                    ])->where('odd', '<=', $limitOdd)->delete();
                $freeStacks = \VanguardLTE\PPGameFreeStack::whereRaw('game_id=? and free_spin_type=? and odd <=? and id not in(select freestack_id from w_ppgame_freestack_log where user_id=?) ORDER BY odd DESC LIMIT 20', [
                        $this->game->original_id, 
                        $freespinType,
                        $limitOdd,
                        $this->playerId
                    ])->get();
                if(count($freeStacks) > 0){
                    $freeStack = $freeStacks[rand(0, count($freeStacks) - 1)];    
                }else{
                    $freeStack = null;
                }
            }
            if($freeStack){
                \VanguardLTE\PPGameFreeStackLog::create([
                    'game_id' => $this->game->original_id, 
                    'user_id' => $this->playerId, 
                    'freestack_id' => $freeStack->id, 
                    'odd' => $freeStack->odd, 
                    'free_spin_count' => $freeStack->free_spin_count
            ]);
                return json_decode($freeStack->free_spin_stack, true);
            }else{
                return [];
            }
        }
        public function IsAvailableFreeStack(){
            $linecount = 5; // line num for free stack
            $game = $this->game;
            $grantfree_count = $game->{'garant_win' . $linecount};
            $free_count = $game->{'winline' . $linecount};
            $grantfree_count++;
            $isFreeStack = false;
            if( $free_count <= $grantfree_count ) 
            {
                $grantfree_count = 0;
                $isFreeStack = true;
                $game->{'winline' . $linecount} = $this->getNewSpin($game, 0, 1, $linecount, 'doSpin');
            }
            $game->{'garant_win' . $linecount} = $grantfree_count;
            $game->save();
            return $isFreeStack;
        }
        public function GetSpinSettings($garantType = 'doSpin', $bet, $lines, $isdoublechance)
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
            if($isdoublechance == 1){
                $_obf_grantwin_count++;
                $_obf_grantbonus_count+=2;
            }else{
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
                if( $_obf_currentbank < ($this->CheckBonusWin() * $bet) && $this->GetGameData($this->slotId . 'RegularSpinCount') < 450) 
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
            $number = rand(0, count($win) - 1);
            return $win[$number];
        }
        public function GetRandomScatterPos($rp)
        {
            $_obf_scatterposes = [];
            for( $i = 0; $i < count($rp); $i++ ) 
            {
                if( $rp[$i] == '1' ) 
                {
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
        public function GetMultiValue(){ // 프리스핀에서 와일드 배당값 결정
            $mul = 2;
            $sum = rand(0, 100);
            if($sum <= 88){
                $mul = 2;
            }else if($sum <= 98){
                $mul = 3;
            }else{
                $mul = 5;
            }
            return $mul;
        }
        public function GetFreeSpin($index){
            $freespins = [0, 0, 0, 0, 12, 17, 22];
            return $freespins[$index]; // 프리스핀개수 
        }
        public function GenerateScatterCount(){ // Scatter수 생성 함수
            $freeSpins = [
                [75, 20, 5],
                [4, 5, 6]
            ];
            $percent = rand(0, 80);
            $sum = 0;
            for($i = 0; $i < count($freeSpins[0]); $i++){
                $sum = $sum + $freeSpins[0][$i];
                if($percent <= $sum){
                    return $freeSpins[1][$i];
                }
            }
            return $freeSpins[1][0];  
        }
        public function GetSymbolCount(){   // 한개 릴의 심볼 개수를 결정하는 함수
            $sum = rand(0, 100);
            if($sum <= 20){
                return 2;
            }else if($sum <= 45){
                return 3;
            }else if($sum <= 70){
                return 4;
            }else if($sum <= 85){
                return 5;
            }else if($sum <= 95){
                return 6;
            }else{
                return 7;
            }
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
            return [$reels, $newTumbPoses, $newTumbMuls];   // 당첨되지않은 심볼배열, 와일드 위치, 와일드 배당값
        }
        public function GetReelStrips($winType, $slotEvent, $slotReelId, $scattercount, $isTumb = false, $lastTumbReelCount = [0, 0, 0, 0, 0, 0])
        {
            $slotReelId = $slotReelId + 1;
            $isScatter = false;
            if( $winType != 'bonus' ) 
            {
                $_obf_reelStripCounts = [];
                foreach( [
                    'reelStrip'.$slotReelId.'_1', 
                    'reelStrip'.$slotReelId.'_2', 
                    'reelStrip'.$slotReelId.'_3', 
                    'reelStrip'.$slotReelId.'_4', 
                    'reelStrip'.$slotReelId.'_5', 
                    'reelStrip'.$slotReelId.'_6', 
                    'reelStrip'.$slotReelId.'_7'
                ] as $index => $reelStrip ) 
                {
                    if( is_array($this->$reelStrip) && count($this->$reelStrip) > 0 ) 
                    {
                        $_obf_reelStripCounts[$index + 1] = mt_rand(0, count($this->$reelStrip));
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
                    5,
                    6,
                    7
                ];
                $scatterStripReelNumber = $this->GetRandomNumber(0, 6, $scattercount);
                for( $i = 0; $i < count($_obf_reelStripNumber); $i++ ) 
                {
                    $issame = false;
                    for($j = 0; $j < $scattercount; $j++){
                        if($i == $scatterStripReelNumber[$j]){
                            $issame = true;
                            break;
                        }
                    }
                    if($issame == true){
                        $_obf_reelStripCounts[$_obf_reelStripNumber[$i]] = $this->GetRandomScatterPos($this->{'reelStrip'.$slotReelId .'_'. $_obf_reelStripNumber[$i]});
                        $isScatter = true;
                    }else{
                        $_obf_reelStripCounts[$_obf_reelStripNumber[$i]] = rand(0, count($this->{'reelStrip'.$slotReelId .'_'. $_obf_reelStripNumber[$i]}));
                    }
                }
            }
            
            $reel = [
                'rp' => []
            ];
            foreach( $_obf_reelStripCounts as $index => $value ) 
            {
                $key = $this->{'reelStrip'.$slotReelId . '_' . $index};
                $rc = count($key);
                $key[-1] = $key[$rc - 1];
                $key[$rc] = $key[0];
                if($index == 7){    // Top 릴배치도 생성
                    $reel['reel1'][0] = 13;
                    $reel['reel2'][0] = $key[abs($value + 3) % $rc];
                    $reel['reel3'][0] = $key[abs($value + 2) % $rc];
                    $reel['reel4'][0] = $key[abs($value + 1) % $rc];
                    $reel['reel5'][0] = $key[abs($value) % $rc];
                    if($slotEvent != 'freespin'){
                        for($k = 2; $k < 6; $k++){
                            if($reel['reel' . $k][0] == 1){
                                $reel['reel' . $k][0] = rand(7, 12);
                            }
                        }
                    }
                    $reel['reel6'][0] = 13;
                }else{      // Main릴배치도 생성
                    $reel['reel' . $index][-1] = rand(7, 12);
                    if($isTumb == true){
                        $symbolCount = $lastTumbReelCount[$index - 1];  // 텀브스핀일때 릴심볼개수를 이전릴의심볼 개수로 리용 
                    }else{
                        $symbolCount = $this->GetSymbolCount();
                    }
                    $diffNum = 1;
                    if($isScatter == false){
                        $scatterPos = 0;
                    }else{
                        $scatterPos = rand(0, $symbolCount - 1);
                    }                
                    for($k = 0; $k < $symbolCount; $k++){
                        $reel['reel' . $index][$k + 1] = $key[abs($value + ($k - $scatterPos) * $diffNum) % $rc];
                    }
                    for($k = $symbolCount; $k < 7; $k++){
                        $reel['reel' . $index][$k + 1] = 13;
                    }
                
                    $scatterPos = -1;
                    $wildPos = -1;
                    for($r = 0; $r < 7; $r++){
                        if($reel['reel' . $index][$r + 1] == 1){
                            if($isTumb == true){
                                $reel['reel' . $index][$r + 1] = rand(7, 12);
                            }else if($scatterPos >= 0){
                                $reel['reel' . $index][$r + 1] = rand(7, 12);
                            }else{
                                $scatterPos = $r + 1;
                            }
                        }else if($reel['reel' . $index][$r + 1] == 2){
                            if($wildPos >= 0){
                                $reel['reel' . $index][$r + 1] = rand(7, 12);
                            }else{
                                $wildPos = $r + 1;
                            }
                            
                        }
                    }
                    if($scatterPos >= 0){
                        if($wildPos >= 0){                        
                            $reel['reel' . $index][$wildPos] = rand(7, 12);
                        }
                        $reel['reel' . $index][-1] = rand(7, 12);
                        $reel['reel' . $index][8] = rand(7, 12);
                    }else{
                        $reel['reel' . $index][8] = rand(7, 12);
                    }
                }
                $reel['rp'][] = $value;
            }
            return $reel;
        }

        public function GetRandomNumber($num_first=0, $num_last=1, $get_cnt=4){
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

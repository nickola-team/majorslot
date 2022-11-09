<?php 
namespace VanguardLTE\Games\SGTheKoiGateHBN
{
    class SlotSettings
    {
        public $playerId = null;
        public $splitScreen = null;

        public $reelStrip0_0 = null;
        public $reelStrip0_1 = null;
        public $reelStrip0_2 = null;
        public $reelStrip1_0 = null;
        public $reelStrip1_1 = null;
        public $reelStrip1_2 = null;
        
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
        public $PayLines = [];
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
        public $happy_symbol_list = [3,3,3,3,3,3,3,3,3,3,3,3,4,4,4,4,4,4,4,4,4,4,4,5,5,5,5,5,5,5,6,6,6,6,7,7,7,8,8,8,9,9,10,10,11,11];

        /* 프리스핀 관련 */
        public $reelPositionMap = [];

        public $gamesession = null; // session table
        public function __construct($sid, $playerId, $credits = null)
        {
            $this->slotId = $sid;
            $this->playerId = $playerId;
            $this->credits = $credits;
            $user = \VanguardLTE\User::lockForUpdate()->find($this->playerId);
            $this->happyhouruser = \VanguardLTE\HappyHourUser::where([
                'user_id' => $user->id, 
                'status' => 1,
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
                [0,0,0,0,0],
                [150,100,20,0,0],
                [150,100,20,0,0],   
                [120,100,20,0,0],   
                [60,30,10,0,0],   
                [40,20,7,0,0],   
                [30,12,5,0,0],   
                [25,10,4,0,0],   
                [20,7,3,0,0],   
                [20,7,3,0,0],   
                [15,5,2,0,0],   
                [15,5,2,0,0],   
            ];

            $this->PayLines = [
                [ 1 => [5, 6, 7, 8, 9]],            // 라인인덱스 => 라인심볼
                [ 2 => [0, 1, 2, 3, 4]],
                [ 3 => [10, 11, 12, 13, 14]],
                [ 4 => [0, 6, 12, 8, 4]],
                [ 5 => [10, 6, 2, 8, 14]],
                [ 6 => [5, 1, 2, 3, 9]],
                [ 7 => [5, 11, 12, 13, 9]],
                [ 8 => [0, 6, 2, 8, 4]],
                [ 9 => [10, 6, 12, 8, 14]],
                [ 10 => [5, 1, 7, 3, 9]],
                [ 11 => [5, 11, 7, 13, 9]],
                [ 12 => [10, 11, 7, 3, 4]],
                [ 13 => [0, 1, 7, 13, 14]],
                [ 14 => [10, 6, 7, 8, 14]],
                [ 15 => [0, 6, 7, 8, 4]],
                [ 16 => [0, 1, 7, 3, 4]],
                [ 17 => [10, 11, 7, 13, 14]],
                [ 18 => [5, 6, 2, 8, 9]],

                /* 역방향 당첨라인 */
                [ 1 => [9, 8, 7, 6, 5]],
                [ 2 => [4, 3, 2, 1, 0]],
                [ 3 => [14, 13, 12, 11, 10]],
                [ 4 => [4, 8, 12, 6, 0]],
                [ 5 => [14, 8, 2, 6, 10]],
                [ 6 => [9, 3, 2, 1, 5]],
                [ 7 => [9, 13, 12, 11, 5]],
                [ 8 => [4, 8, 2, 6, 0]],
                [ 9 => [14, 8, 12, 6, 10]],
                [ 10 => [9, 3, 7, 1, 5]],
                [ 11 => [9, 13, 7, 11, 5]],
                [ 12 => [4, 3, 7, 11, 10]],
                [ 13 => [14, 13, 7, 1, 0]],
                [ 14 => [14, 8, 7, 6, 10]],
                [ 15 => [4, 8, 7, 6, 0]],
                [ 16 => [4, 3, 7, 1, 0]],
                [ 17 => [14, 13, 7, 11, 10]],
                [ 18 => [9, 8, 2, 6, 5]]
            ];

            $this->reelPositionMap = [
                [3],
                [1, 2],
                [2, 1]
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
            return $totalPayRatio / $ratioCount / 3;
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
            if( /* $this->isBonusStart ||  */$slotState == 'bonus' || $slotState == 'freespin') 
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
        public function SetBank($slotEvent = '', $sum, $isBuyFreespin = -1)
        {
            if( /* $this->isBonusStart || */ $slotEvent == 'bonus' || $slotEvent == 'free') 
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
            if( $sum > 0 && $slotEvent == 'base' ) 
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
            if( $sum < 0 && $slotEvent == 'base' ) 
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
        public function SaveLogReport($spinSymbols, $bet, $lines, $win, $slotState)
        {
            $_obf_slotstate = $this->slotId . ' ' . $slotState;
            if( $slotState == 'free' ) 
            {
                $_obf_slotstate = $this->slotId . ' FG';
            }
            else if( $slotState == 'base') 
            {
                $_obf_slotstate = $this->slotId . '';
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
                'balance' => $this->GetBalance() * $this->CurrentDenom, 
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
        public function GetSpinSettings($garantType = 'base', $bet, $lines)
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
            if( $garantType != 'base') 
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
            //     if ($garantType == 'free')
            //     {
            //         $spin_percent = 3;
            //     }
            //     $spinWin = ($bonus_spin < $spin_percent) ? 1 : 0;
            // }
            if( $garantType != 'free' && $bonusWin == 1 && $this->slotBonus )   // 프리스핀중 보너스당첨 없음
            {
                // $this->isBonusStart = true;
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
            if( $garantType == 'base' && $this->GetBalance() <= (1 / $this->CurrentDenom) ) 
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
        public function getNewSpin($game, $spinWin = 0, $bonusWin = 0, $lines, $garantType = 'base')
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
            if( $garantType != 'base' /* && $garantType != 'tumblespin' */ ) 
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

        public function GetGambleSettings()
        {
            $spinWin = random_int(1, $this->WinGamble);
            return $spinWin;
        }

        public function GenerateScatterCount($winType, $slotEvent){ // Scatter수 생성 함수
            if($winType == 'bonus' || $slotEvent == 'free') {
                $probabilityMap = [
                    1 => 90,
                    2 => 10,
                    3 => 0
                ];
            }
            else {
                $probabilityMap = [
                    0 => 100,
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

        public function GenerateWildCount($winType, $slotEvent) {
            if($winType == 'bonus'){      
                $probabilityMap = [
                    0 => 100,
                ];
            }
            else if ($winType == 'win' && $slotEvent !== 'free') {
                $probabilityMap = [
                    0 => 5,
                    1 => 95,
                    2 => 10
                ];
            }
            else {
                if ($slotEvent == 'free') {
                    $probabilityMap = [
                        0 => 75,
                        1 => 20,
                        2 => 5
                    ];
                }
                else {
                    $probabilityMap = [
                        0 => 80,
                        1 => 18,
                        2 => 2
                    ];
                }
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
        public function GetRandWildReelId($limited_reels) {
            $probabilityMap = [
                0 => 2,
                1 => 3,
                2 => 90,
                3 => 3,
                4 => 2
            ];

            /* 금지된 릴에 확률 0 적용 */
            foreach ($limited_reels as $reelId) {
                $probabilityMap[$reelId] = 0;
            }

            $sum = array_sum(array_values($probabilityMap));
            $randNum = random_int(1, $sum);

            $sum = 0;
            $res = 0;       
            foreach ($probabilityMap as $key => $probability) {
                $sum += $probability;
                if ($randNum <= $sum) {
                    $res = $key;
                    break;
                }
            }

            return $res;
        }


        public function GenerateRespinCount() {
            $probabilityMap = [
                1 => 10,
                2 => 20,
                3 => 25,
                4 => 25,
                5 => 20
            ];
            
            $sum = array_sum(array_values($probabilityMap));
            $randNum = random_int(1, $sum);

            $sum = 0;
            foreach ($probabilityMap as $key => $probability) {
                $sum += $probability;
                if ($randNum <= $sum) {
                    return $key;
                }
            }

            return 1;  
        }

        public function GetReelStrips($winType, $slotEvent , $proposedScatterCount, $proposedWildCount )
        {
            $REELCOUNT = 5;
            $SYMBOLCOUNT = 3;
            $S_WILD = 2;
            $S_SCATTER = 1;
            $S_BLANK = 14;
           
            /* 릴배치표 생성 */
            $reels = [
                'symbols' => [],
                'extendedSymbols' => [],
                'flatSymbols' => [],
                'scatterSymbols' => []
            ];

            for ($reelId=0; $reelId < $REELCOUNT; $reelId++) { 
                $positionSetId = array_rand($this->reelPositionMap);
                $positionSet = $this->reelPositionMap[$positionSetId];

                $uniqueSymbols = [];
                $curPos = 0;
                // $prevSymbol = $S_BLANK;
                $reels['symbols'][$reelId] = array_fill(0, $SYMBOLCOUNT, $S_BLANK);

                /* 기본릴 생성 */
                foreach ($positionSet as $count) { 
                    if ($this->happyhouruser)
                    {
                        $doneFlag = $this->GetGameData('HappySymbolFlag');
                        if ($doneFlag == null)
                        {
                            $doneFlag = array_fill(0, count($this->happy_symbol_list) , 0);
                        }
                        else
                        {
                            $doneFlag = explode(',', $doneFlag);
                            if (count($doneFlag) <  count($this->happy_symbol_list))
                            {
                                $doneFlag = array_fill(0, count($this->happy_symbol_list) , 0);
                            }
                        }
                        if (array_sum($doneFlag) == count($this->happy_symbol_list))
                        {
                            $doneFlag = array_fill(0, count($this->happy_symbol_list) , 0);
                        }
                        $symbIdx = array_rand($this->happy_symbol_list);
                        $newSymbol = $this->happy_symbol_list[$symbIdx];
                        $try = 0;
                        while ((in_array($newSymbol ,$uniqueSymbols) || $doneFlag[$symbIdx] == 1) && $try < 200){
                            $symbIdx = array_rand($this->happy_symbol_list);
                            $newSymbol = $this->happy_symbol_list[$symbIdx];
                            $try = $try + 1;
                        }
                        if ($try >= 200)
                        {
                            $doneFlag = array_fill(0, count($this->happy_symbol_list) , 0);
                        }
                        else
                        {
                            $doneFlag[$symbIdx] = 1;
                        }
                        $this->SetGameData('HappySymbolFlag', implode(',', $doneFlag));
                    }
                    else
                    {
                        while( in_array( ($newSymbol = random_int(3, 11)), $uniqueSymbols));
                    }

                    $newSymbols = array_fill($curPos, $count, $newSymbol);
                    $reels['symbols'][$reelId] = array_replace($reels['symbols'][$reelId], $newSymbols);

                    $curPos += $count;
                    // $prevSymbol = $newSymbol;

                    array_push($uniqueSymbols, $newSymbol);
                }
            }

            /* SCATTER 심볼 생성 */
            $limited_reels = [];

            for ($i=0; $i < $proposedScatterCount; $i++) { 
                /* 2, 3, 4번 릴에만 존재 */
                while( in_array( ($rand_reel_id = random_int(1, $REELCOUNT - 2)), $limited_reels));

                /* 랜덤 위치 선택 */
                $pos = array_rand($reels['symbols'][$rand_reel_id]);
                $reels['symbols'][$rand_reel_id][$pos] = $S_SCATTER;
                array_push($limited_reels, $rand_reel_id);
            }

            /* WILD 심볼 생성, 스캐터심볼이 있는 릴 제외 */
            for ($i=0; $i < $proposedWildCount; $i++) { 
                /* 제한릴갯수가 최대릴갯수 이상이면 중지 */
                if (count($limited_reels) >= $REELCOUNT) {
                    break;
                }

                /* WILD 심볼이 위치할 랜덤릴 */
                $rand_reel_id = $this->GetRandWildReelId($limited_reels);

                $pos = array_rand($reels['symbols'][$rand_reel_id]);
                $reels['symbols'][$rand_reel_id][$pos] = $S_WILD;

                array_push($limited_reels, $rand_reel_id);
            }
            
            /* 확장릴 생성 */
            for ($reelId=0; $reelId < $REELCOUNT; $reelId++) { 
                $extendedSymbols = $reels['symbols'][$reelId];
                $beforeSymbol = random_int(3, 11);
                $afterSymbol = random_int(3, 11);
                array_unshift($extendedSymbols, $beforeSymbol, $beforeSymbol);
                array_push($extendedSymbols, $afterSymbol, $afterSymbol);
                
                $reels['extendedSymbols'][$reelId] = $extendedSymbols;
            }
            
            /* 릴배치표 평활화 */
            $flatSymbols = [];
            $flatScatterSymbols = [];
            foreach ($reels['symbols'] as $reelId => $symbols) {
                foreach ($symbols as $k => $symbol) {
                    $flatPos = $reelId + $k * $REELCOUNT;
                    $flatSymbols[$flatPos] = $symbol;
                    
                    if ($symbol == $S_SCATTER) {
                        array_push($flatScatterSymbols, $flatPos);
                    }
                }
            }
            ksort($flatSymbols);

            $reels['flatSymbols'] = $flatSymbols;
            $reels['scatterSymbols'] = $flatScatterSymbols;
            return $reels;
        }

        public function isHighLevelSymbol($symbol) {
            if ($symbol <= 7) {
                return true;
            }
            else {
                return false;
            }
        }

        public function isValidReels($reels) {
            $highLevelSymbolCount = array_reduce($reels['flatSymbols'], function($carry, $symbol) {
                if ($symbol > 2 && $symbol < 8) {
                    $carry += 1;
                }

                return $carry;
            });

            if ($highLevelSymbolCount > 8){
                return false;
            }

            return true;
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

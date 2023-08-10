<?php 
namespace VanguardLTE\Games\WildCelebrityBusMegawaysPM
{
    class SlotSettings
    {
        public $playerId = null;
        public $splitScreen = null;
        public $slotId = '';
        public $slotDBId = '';
        public $Line = null;
        public $scaleMode = null;
        public $numFloat = null;
        public $Bet = null;
        public $isBonusStart = null;
        public $Balance = null;
        public $GambleType = null;
        public $Jackpots = [];
        public $keyController = null;
        public $slotViewState = null;
        public $hideButtons = null;
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
        private $Bonus = null;
        private $shop_id = null;
        public $licenseDK = null;
        public $currency = null;
        public $user = null;
        public $game = null;
        public $shop = null;
        public $credits = null;
        public $happyhouruser = null;
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
                // 'time' => date('G')
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


            $this->CurrentDenom = $this->game->denomination;
            $this->scaleMode = 0;
            $this->numFloat = 0;
            $this->Paytable[1] = [0,0,0,0,0,0];
            $this->Paytable[2] = [0,0,0,0,0,0];
            $this->Paytable[3] = [0,0,6,60,240,2400];
            $this->Paytable[4] = [0,0,0,36,180,1200];
            $this->Paytable[5] = [0,0,0,24,120,600];
            $this->Paytable[6] = [0,0,0,24,120,600];
            $this->Paytable[7] = [0,0,0,12,60,0];
            $this->Paytable[8] = [0,0,0,6,30,120];
            $this->Paytable[9] = [0,0,0,6,30,120];
            $this->Paytable[10] = [0,0,0,6,30,120];
            $this->Paytable[11] = [0,0,0,6,30,120];
            $this->Paytable[12] = [0,0,0,6,30,120];
            $this->Paytable[13] = [0,0,0,0,0,0];
            $this->Paytable[14] = [0,0,0,0,0,0];
            $this->Paytable[15] = [0,0,0,0,0,0];
            $this->Paytable[16] = [0,0,0,0,0,0];
            $this->Paytable[17] = [0,0,0,0,0,0];
            $this->Paytable[18] = [0,0,0,0,0,0];
            $this->Paytable[19] = [0,0,0,0,0,0];
            $this->Paytable[20] = [0,0,0,0,0,0];
            $this->Paytable[21] = [0,0,0,0,0,0];
            $this->Paytable[22] = [0,0,0,0,0,0];
            $this->Paytable[23] = [0,0,0,0,0,0];
            $this->Paytable[24] = [0,0,0,0,0,0];
            $this->Paytable[25] = [0,0,0,0,0,0];
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
            $this->jpgs = [];
            $this->Line = [1];
            $this->Bet = explode(',', $game->bet); //[20.00,40.00,60.00,80.00,100.00,200.00,300.00,400.00,500.00,750.00,1000.00,1500.00,2500.00,5000.00,7500.00,10000.00]; 
            $this->Balance = $user->balance;
            $this->Bank = $game->get_gamebank();
            $this->Percent = $this->shop->percent;
            // $game->rezerv => 10,000,000.00
            $this->slotDBId = $game->id;
            $this->slotCurrency = $user->shop->currency;
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
            return $this->user->count_balance;
        }
        public function InternalError($errcode)
        {
            $strlog = '';
            $strlog .= "\n";
            $strlog .= date("Y-m-d H:i:s") . ' ';
            $strlog .= ('{"responseEvent":"error","responseType":"' . $errcode . '","serverResponse":"InternalError"}');
            $strlog .= "\n";
            $strlog .= ' ############################################### ';
            $strlog .= "\n";
            $strinternallog = '';
            if( file_exists(storage_path('logs/') . $this->slotId . 'Internal.log') ) 
            {
                $strinternallog = file_get_contents(storage_path('logs/') . $this->slotId . 'Internal.log');
            }
            file_put_contents(storage_path('logs/') . $this->slotId . 'Internal.log', $strinternallog . $strlog);
            //exit( '{"responseEvent":"error","responseType":"' . $errcode . '","serverResponse":"InternalError"}' );
        }
        public function SetBank($slotState = '', $sum, $slotEvent = '', $isFreeSpin = false)
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
            if($isFreeSpin == true){
                if ($this->happyhouruser)
                {
                    $this->happyhouruser->increment('current_bank', $sum);
                    $this->happyhouruser->save();
                    return $game;
                }
                $_allBets = $sum / $this->GetPercent() * 100;
                $normal_sum = $_allBets * 10 / 100;
                $game->set_gamebank($normal_sum, 'inc', '');
                $sum = $sum - $normal_sum;
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
            $bonus_systemmoney = 0;
            if( $sum > 0 && $slotEvent == 'bet') 
            {
                $this->toGameBanks = 0;
                $this->toSlotJackBanks = 0;
                $this->toSysJackBanks = 0;
                $this->betProfit = 0;
                $currentpercent = $this->GetPercent();
                $bonus_percent = $currentpercent / 3;
                $count_balance = $this->GetCountBalanceUser();
                $_allBets = $sum / $this->GetPercent() * 100;
                /*if( $count_balance < $_allBets && $count_balance > 0 ) 
                {
                    $_subCountBalance = $count_balance;
                    $diff_money = $_allBets - $_subCountBalance;
                    $subavaliable_balance = $_subCountBalance / 100 * $this->GetPercent();
                    $sum = $subavaliable_balance + $diff_money;
                    $bonus_systemmoney = $_subCountBalance / 100 * $bonus_percent;
                }
                else if( $count_balance > 0 ) 
                {*/
                    $bonus_systemmoney = $_allBets / 100 * $bonus_percent;
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
                if( $bonus_systemmoney > 0 ) 
                {
                    $sum -= $bonus_systemmoney;
                    $game->set_gamebank($bonus_systemmoney, 'inc', 'bonus');
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
            $user = $this->user;
            $this->Balance = $user->balance / $this->CurrentDenom;
            return $this->Balance;
        }
        public function SaveLogReport($spinSymbols, $bet, $lines, $win, $slotState, $isState = true)
        {
            $slotstate = $this->slotId . ' ' . $slotState;
            if( $slotState == 'freespin' ) 
            {
                $slotstate = $this->slotId . ' Free';
            }
            else if( $slotState == 'bet' ) 
            {
                $slotstate = $this->slotId . '';
            }
            else if( $slotState == 'slotGamble' ) 
            {
                $slotstate = $this->slotId . ' DG';
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
                    'game' => $slotstate, 
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
        public function GetFreeStack($betLine)
        {
            $winAvaliableMoney = $this->GetBank('bonus');
            $limitOdd = 35;
            if ($this->happyhouruser)
            {
                $limitOdd = floor($winAvaliableMoney / $betLine);
            }
            else
            {
                $limitOdd = floor($winAvaliableMoney / $betLine / 3);
                if($limitOdd < 35){
                    $limitOdd = 35;
                }else if($limitOdd > 100){
                    $limitOdd = 100;
                }
            }
            $freeStacks = \VanguardLTE\PPGameFreeStack::whereRaw('game_id=? and odd <=? and id not in(select freestack_id from w_ppgame_freestack_log where user_id=?) ORDER BY odd DESC LIMIT 20', [
                $this->game->original_id, 
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
                $freeStacks = \VanguardLTE\PPGameFreeStack::whereRaw('game_id=? and odd <=? and id not in(select freestack_id from w_ppgame_freestack_log where user_id=?) ORDER BY odd DESC LIMIT 20', [
                        $this->game->original_id, 
                        $limitOdd,
                        $this->playerId
                    ])->get();
                    if (count($freeStacks) > 0) {
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
        public function GetSpinSettings($garantType = 'doSpin', $bet, $lines, $isdoublechance = 0)
        {
            $linecount = 10;
            if( $garantType != 'doSpin' ) 
            {
                $granttype = '_bonus';
            }
            else
            {
                $granttype = '';
            }
            $bonusWin = 0;
            $spinWin = 0;
            $game = $this->game;
            $grantwin_count = $game->{'garant_win' . $granttype . $linecount};
            $grantbonus_count = $game->{'garant_bonus' . $granttype . $linecount};
            $winbonus_count = $game->{'winbonus' . $granttype . $linecount};
            $winline_count = $game->{'winline' . $granttype . $linecount};
            $grantwin_count++;
            if($isdoublechance == 1){
                $grantbonus_count++;
            }else{
                $grantbonus_count++;
            }
            $return = [
                'none', 
                0
            ];
            if( $winbonus_count <= $grantbonus_count ) 
            {
                $bonusWin = 1;
                $grantbonus_count = 0;
                $game->{'winbonus' . $granttype . $linecount} = $this->getNewSpin($game, 0, 1, $lines, $garantType);
            }
            else if( $winline_count <= $grantwin_count ) 
            {
                $spinWin = 1;
                $grantwin_count = 0;
                $game->{'winline' . $granttype . $linecount} = $this->getNewSpin($game, 1, 0, $lines, $garantType);
            }
            $game->{'garant_win' . $granttype . $linecount} = $grantwin_count;
            $game->{'garant_bonus' . $granttype . $linecount} = $grantbonus_count;
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
                $currentbank = $this->GetBank($garantType);
                $return = [
                    'bonus', 
                    $currentbank
                ];
                if( $currentbank < ($this->CheckBonusWin() * $bet) && $this->GetGameData($this->slotId . 'RegularSpinCount') < 450) 
                {
                    $return = [
                        'none', 
                        0
                    ];
                }
            }
            else if( $spinWin == 1 || $bonusWin == 1 && !$this->slotBonus ) 
            {
                $currentbank = $this->GetBank($garantType);
                $return = [
                    'win', 
                    $currentbank
                ];
                if( $currentbank < $bet / 2) 
                {
                    $return = [
                        'none', 
                        0
                    ];
                }
            }
            if( $garantType == 'bet' && $this->GetBalance() <= (1 / $this->CurrentDenom) ) 
            {
                $rand = rand(1, 2);
                if( $rand == 1 ) 
                {
                    $currentbank = $this->GetBank('');
                    $return = [
                        'win', 
                        $currentbank
                    ];
                }
            }
            return $return;
        }
        public function getNewSpin($game, $spinWin = 0, $bonusWin = 0, $lines, $garantType = 'doSpin')
        {
            $linecount = 10;
            if( $garantType != 'doSpin' ) 
            {
                $granttype = '_bonus';
            }
            else
            {
                $granttype = '';
            }
            if( $spinWin ) 
            {
                $win = explode(',', $game->game_win->{'winline' . $granttype . $linecount});
            }
            if( $bonusWin ) 
            {
                $win = explode(',', $game->game_win->{'winbonus' . $granttype . $linecount});
            }
            $number = rand(0, count($win) - 1);
            return $win[$number];
        }
        
        public function GetPurMul($pur)
        {
            $purmuls = [100];
            return $purmuls[$pur];
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


        public function GetReelStrips($winType, $bet, $pur = -1)
        {
            // if($winType == 'bonus'){
                // $stack = \VanguardLTE\PPGameStackModel\PPGameWildCelebrityBusMegawaysStack::where('id', 811)->first();
                // return json_decode($stack->spin_stack, true);
            // }
            $spintype = 0;
            if($winType == 'bonus'){
                $winAvaliableMoney = $this->GetBank('bonus');
            }else if($winType == 'win' || $pur == 0){
                $winAvaliableMoney = $this->GetBank('');
            }else{
                $winAvaliableMoney = 0;
            }
            $limitOdd = 0;
            if($winType != 'none'){
                $limitOdd = floor($winAvaliableMoney / $bet);
            }
            if($this->happyhouruser){
                $limitOdd = $this->GetBank('') / $bet;
                if($limitOdd > 10){
                    $winType = 'bonus';
                }else if($limitOdd > 1){
                    $winType = 'win';
                }
            }
            $isLowBank = false;
            $existIds = \VanguardLTE\PPGameFreeStackLog::where([
                'user_id' => $this->playerId,
                'game_id' => $this->game->original_id
                ])->pluck('freestack_id');
            while(true){
                if($pur == 0){
                    $stacks = \VanguardLTE\PPGameStackModel\PPGameWildCelebrityBusMegawaysStack::where('pur_level',0)->whereNotIn('id', $existIds);
                }else if($pur == 1){                        
                    $stacks = \VanguardLTE\PPGameStackModel\PPGameWildCelebrityBusMegawaysStack::where('pur_level', 1)->whereNotIn('id', $existIds);
                }
                if($winType == 'bonus'){
                    $stacks = \VanguardLTE\PPGameStackModel\PPGameWildCelebrityBusMegawaysStack::where('spin_type', 1)->whereNotIn('id', $existIds);
                }else{
                    if($pur != 0){
                        $stacks = \VanguardLTE\PPGameStackModel\PPGameWildCelebrityBusMegawaysStack::where('spin_type', 0)->whereNotIn('id', $existIds);
                    }
                }
                $index = mt_rand(0, 48000);
                if($winType == 'win'){
                    $stacks = $stacks->where('odd', '>', 0);
                    // $index = mt_rand(0, 85000);
                }
                if($isLowBank == true){
                    if($winType == 'bonus'){
                        $stacks = $stacks->where('odd', '<=', 15);    
                    }
                    $stacks = $stacks->orderby('odd', 'asc')->take(100)->get();
                }else{
                    if($bet > $this->game->special_limitmoney && $limitOdd > 10 && $this->game->garant_special_winbonus >= $this->game->special_winbonus){
                        $stacks = $stacks->where('odd', '<=', $limitOdd)->orderby('odd', 'desc')->take(100)->get();
                        $this->game->garant_special_winbonus = 0;
                        $win = explode(',', $this->game->game_win->special_winbonus);
                        $this->game->special_winbonus = $win[rand(0, count($win) - 1)];
                        $this->game->save();
                    }else{
                        if($winType == 'bonus'){
                            if($this->GetGameData($this->slotId . 'BuyFreeSpin') >= 0){
                                $miniOdd = $limitOdd / mt_rand(2,4);
                                if($miniOdd > 30){
                                    $miniOdd = 30;
                                }
                                $stacks = $stacks->where('odd', '>=', $miniOdd);
                            }
                            if ($this->happyhouruser)
                            {
                                $stacks = $stacks->where('odd', '<=', $limitOdd)->orderby('odd', 'desc')->take(3)->get();
                            }
                            else
                            {
                                $stacks = $stacks->where('odd', '<=', $limitOdd)->get();
                            }
                        }else{
                            $stacks = $stacks->where('odd', '<=', $limitOdd)->where('id', '>=', $index)->take(100)->get();
                        }
                    }
                }
                if(!isset($stacks) || count($stacks) == 0){
                    if($isLowBank == true){
                        $existIds = [0];
                    }
                    if($isLowBank == true){
                        $existIds = [0];
                    }
                    $isLowBank = true;
                }else{
                    break;
                }
            }
            $stack = $stacks[rand(0, count($stacks) - 1)];
            \VanguardLTE\PPGameFreeStackLog::create([
                'game_id' => $this->game->original_id, 
                'user_id' => $this->playerId, 
                'freestack_id' => $stack->id,
                'odd' => $stack->odd
            ]);
            return json_decode($stack->spin_stack, true);
        }
    }
}

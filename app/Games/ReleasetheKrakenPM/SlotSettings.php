<?php 
namespace VanguardLTE\Games\ReleasetheKrakenPM
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
        private $lockingWildTypes = [];
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
        public $respin_chance = null;
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

            $this->money_respin = [
                [30, 20, 15, 10, 5, 5, 2, 2, 2, 2, 2, 1, 1, 1, 1, 1],
                [40,80,120,160,200,240,280,320,400,560,640,720,800,960,2000,8000]
            ];
            $this->money_jackpot = [
                [980, 14, 3, 2, 1],
                [0, 1, 2, 3, 5]
            ];

            $this->respin_chance = 5;

            $this->CurrentDenom = $this->game->denomination;
            $this->scaleMode = 0;
            $this->numFloat = 0;
            $this->Paytable[0]  = [0,0,0,0,0,0];
            $this->Paytable[1]  = [0,0,0,0,0,0];
            $this->Paytable[2]  = [0,0,0,0,0,0];
            $this->Paytable[3]  = [0,0,0,50,200,500];
            $this->Paytable[4]  = [0,0,0,40,100,250];
            $this->Paytable[5]  = [0,0,0,40,80,200];
            $this->Paytable[6]  = [0,0,0,20,60,150];
            $this->Paytable[7]  = [0,0,0,20,60,100];
            $this->Paytable[8]  = [0,0,0,10,20,80];
            $this->Paytable[9]  = [0,0,0,10,20,80];
            $this->Paytable[10] = [0,0,0,5,10,40];
            $this->Paytable[11] = [0,0,0,5,10,40];
            $this->Paytable[12] = [0,0,0,0,0,0];
            $this->Paytable[13] = [0,0,0,0,0,0];
            $this->Paytable[14] = [0,0,0,0,0,0];
            $this->Paytable[15] = [0,0,0,0,0,0];
            $this->Paytable[16] = [0,0,0,0,0,0];
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
            $this->slotFreeCount = 10;
            $this->slotRespinCount = 3;
            $this->slotFreeMpl = 1;
            $this->slotViewState = ($game->slotViewState == '' ? 'Normal' : $game->slotViewState);
            $this->hideButtons = [];
            $this->jpgs = [];
            $this->Line = [1];
            
            $this->lockingWildTypes = ['c', 's', 'ws', 'mc', 'sc'];
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
            //exit( '{"responseEvent":"error","responseType":"' . $errcode . '","serverResponse":"InternalError"}' );
        }
        public function SetBank($slotState = '', $sum, $slotEvent = '', $isBuyFreeSpin = false)
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
            if($isBuyFreeSpin == true){
                $game->set_gamebank($sum, 'inc', 'bonus');
                $game->save();
                return $game;
            }
            if( $this->GetBank($slotState) + $sum < 0 ) 
            {
                if($slotState == 'bonus'){
                    $diffMoney = $this->GetBank($slotState) + $sum;
                    if ($this->happyhouruser){
                        $this->happyhouruser->increment('over_bank', abs($diffMoney)) ;
                    }
                    else {
                        $normalbank = $game->get_gamebank('');
                        if ($normalbank + $diffMoney < 0)
                        {
                            $this->InternalError('Bank_   ' . $sum . '  CurrentBank_ ' . $this->GetBank($slotState) . ' CurrentState_ ' . $slotState);
                        }
                        $game->set_gamebank($diffMoney, 'inc', '');
                        $sum = $sum - $diffMoney;
                    }
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
                $this->happyhouruser->increment('current_bank', $sum) ;
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
            else if( $slotState == 'doBonus' ) 
            {
                $_obf_slotstate = $this->slotId . ' Bonus';
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
        public function IsRespinState(){
            if(rand(0, 100) < $this->respin_chance){
                return true;
            }else{
                return false;
            }
        }
        public function GetMoneyWin(){
            $percent = rand(0, 100);
            $sum = 0;
            for($i = 0; $i < count($this->money_respin[0]); $i++){
                $sum = $sum + $this->money_respin[0][$i];
                if($sum >= $percent){
                    return $this->money_respin[1][$i];
                }
            }
            return 0; //$this->money_respin[0][count($this->money_respin[0]) - 1];
        }
        public function GetJackpotMoney(){
            $percent = rand(0, 1000);
            $sum = 0;
            for($i = 1; $i < count($this->money_jackpot[0]); $i++){
                $sum = $sum + $this->money_jackpot[0][$i];
                if($sum >= $percent){
                    return $this->money_jackpot[1][$i];
                }
            }
            return 0;
        }
        public function GetLWBCount(){
            $sum = rand(0, 100);
            if($sum <= 85){
                return 1;
            }else if($sum <= 95){
                return 2;
            }else{
                return 3;
            }
        }
        public function GetIWCount(){
            $sum = rand(0, 100);
            if($sum <= 60){
                return 2;
            }else{
                return 1;
            }
        }
        public function GenerateFreeSpinCount(){
            $freeSpinNums = [];
            $totalFreeNum = rand(7, 9);
            while($totalFreeNum > 0){
                $sum = rand(0, 100);
                if($sum <= 70){
                    $freeNum = 1;
                }else if($sum <= 90){
                    $freeNum = 2;
                }else{
                    $freeNum = 3;
                }
                if($totalFreeNum < $freeNum){
                    $freeNum = $totalFreeNum;
                }

                $totalFreeNum = $totalFreeNum - $freeNum;
                
                array_push($freeSpinNums, $freeNum);
            }
            return $freeSpinNums;
        }
        public function GetKrakenLockWild(){
            $count = 0;
            $result = [];
            shuffle($this->lockingWildTypes);
            for($k = 0; $k < 3; $k++){
                array_push($result, $this->lockingWildTypes[$k]);
            }
            return $result;
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
                if( $_obf_currentbank < ($this->CheckBonusWin() * $bet)   && $this->GetGameData($this->slotId . 'RegularSpinCount') < 450) 
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
                if( $_obf_currentbank < $bet / 2) 
                {
                    $return = [
                        'none', 
                        0
                    ];
                }
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
        public function GetRandomScatterPos($rp, $isBuyFreeSpin = false)
        {
            $_obf_scatterposes = [];
            for( $i = 0; $i < count($rp); $i++ ) 
            {
                if( $rp[$i] == '0' || $rp[$i] == '14' ) 
                {
                    array_push($_obf_scatterposes, $i);
                }else if($isBuyFreeSpin == false && $rp[$i] == '13'){
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
        public function GetNoneWinReels($winType, $slotEvent)
        {
            $reel = $this->GetReelStrips($winType, $slotEvent);
            $reel['reel1'][0] = mt_rand(10,11);
            $reel['reel1'][1] = mt_rand(10,11);
            $reel['reel1'][2] = mt_rand(10,11);
            $reel['reel1'][3] = mt_rand(10,11);

            $reel['reel2'][0] = mt_rand(5,9);
            $reel['reel2'][1] = mt_rand(5,9);
            $reel['reel2'][2] = mt_rand(5,9);
            $reel['reel2'][3] = mt_rand(5,9);

            $reel['reel3'][0] = mt_rand(5,9);
            $reel['reel3'][1] = mt_rand(5,9);
            $reel['reel3'][2] = mt_rand(5,9);
            $reel['reel3'][3] = mt_rand(5,9);

            $reel['reel4'][0] = mt_rand(5,9);
            $reel['reel4'][1] = mt_rand(5,9);
            $reel['reel4'][2] = mt_rand(5,9);
            $reel['reel4'][3] = mt_rand(5,9);

            $reel['reel5'][0] = mt_rand(5,9);
            $reel['reel5'][1] = mt_rand(5,9);
            $reel['reel5'][2] = mt_rand(5,9);
            $reel['reel5'][3] = mt_rand(5,9);
            
            return $reel;
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


        public function GetReelStrips($winType, $slotEvent, $isBuyFreeSpin = false)
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
                            $_obf_reelStripCounts[$index + 1] = mt_rand(0, count($this->$reelStrip));
                        }
                    }
                }
                else
                {
                    $isScatter = true;
                    $_obf_reelStripNumber = [
                        1, 
                        2, 
                        3, 
                        4, 
                        5
                    ];
                    for( $i = 0; $i < count($_obf_reelStripNumber); $i++ ) 
                    {
                        if( $i == 1 || $i == 2 || $i == 3 ) 
                        {
                            $_obf_reelStripCounts[$_obf_reelStripNumber[$i]] = $this->GetRandomScatterPos($this->{'reelStripBonus' . $_obf_reelStripNumber[$i]}, $isBuyFreeSpin);
                        }
                        else
                        {
                            $_obf_reelStripCounts[$_obf_reelStripNumber[$i]] = rand(0, count($this->{'reelStripBonus' . $_obf_reelStripNumber[$i]}));
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
                        'reelStrip5', 
                        'reelStrip6'
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
                    $isScatter = true;
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
                            $_obf_reelStripCounts[$_obf_reelStripNumber[$i]] = $this->GetRandomScatterPos($this->{'reelStrip' . $_obf_reelStripNumber[$i]}, $isBuyFreeSpin);
                        }
                        else
                        {
                            $_obf_reelStripCounts[$_obf_reelStripNumber[$i]] = rand(0, count($this->{'reelStrip' . $_obf_reelStripNumber[$i]}));
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
                if($isScatter = false){
                    $reel['reel' . $index][0] = $key[$value];
                    $reel['reel' . $index][1] = $key[($value + 1) % $rc];
                    $reel['reel' . $index][2] = $key[($value + 2) % $rc];
                    $reel['reel' . $index][3] = $key[($value + 3) % $rc];
                }else{
                    $diffNum = 1;
                    $scatterPos = rand(0, 3);
                    $reel['reel' . $index][0] = $key[abs($value - $scatterPos * $diffNum) % $rc];
                    $reel['reel' . $index][1] = $key[abs($value + (1 - $scatterPos) * $diffNum) % $rc];
                    $reel['reel' . $index][2] = $key[abs($value + (2 - $scatterPos) * $diffNum) % $rc];
                    $reel['reel' . $index][3] = $key[abs($value + (3 - $scatterPos) * $diffNum) % $rc];
                }
                $reel['reel' . $index][4] = $key[($value + 4) % $rc];
                $reel['rp'][] = $value;
            }
            return $reel;
        }
    }

}

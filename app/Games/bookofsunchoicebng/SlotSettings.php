<?php 
namespace VanguardLTE\Games\bookofsunchoicebng
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
        public $Bet = null;
        public $Balance = null;
        public $GambleType = null;
        public $Jackpots = [];
        public $slotViewState = null;
        public $hideButtons = null;
        public $slotFreeCount = null;
        public $slotFreeMpl = null;
        public $freespinCount = null;
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
            // $this->happyhouruser = \VanguardLTE\HappyHourUser::where([
            //     'user_id' => $user->id, 
            //     'status' => 1,
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


            $this->CurrentDenom = $this->game->denomination;
            $this->scaleMode = 0;
            $this->Paytable[1] = [0,0,0,5,10,50];
            $this->Paytable[2] = [0,0,0,5,10,50];
            $this->Paytable[3] = [0,0,0,5,10,50];
            $this->Paytable[4] = [0,0,0,5,10,50];
            $this->Paytable[5] = [0,0,0,5,25,100];
            $this->Paytable[6] = [0,0,0,5,30,150];
            $this->Paytable[7] = [0,0,0,5,40,200];
            $this->Paytable[8] = [0,0,0,15,50,200];
            $this->Paytable[9] = [0,0,0,20,60,300];
            $this->Paytable[10] = [0,0,0,25,0,0];
            $this->Paytable[11] = [0,0,0,0,0,0];
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
            $this->freespinCount = 10;
            $this->slotViewState = ($game->slotViewState == '' ? 'Normal' : $game->slotViewState);
            $this->hideButtons = [];
            $this->jpgs = [];
            $this->Line = [1];
            $this->Bet = explode(',', $game->bet); //[4, 8, 10, 16, 20, 40, 50, 80, 100, 160, 200, 400, 500, 800, 1000]; 
            $this->Balance = $user->balance;
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
                'timelife' => time() + $expire, 
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
                $this->lastEvent = $jsonLog;
                break;
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
            if( $slotState == 'bonus' || $slotState == 'freespin' || $slotState == 'respin' ) 
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
            if($slotState == 'bonus' || $slotState == 'freespin' || $slotState == 'respin' ) 
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
                        $this->happyhouruser->increment('over_bank', abs($diffMoney)) ;
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
            if( $sum > 0 && $slotEvent == 'bet') 
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
        public function SaveLogReport($spinSymbols, $bet, $lines, $win, $slotState, $isState)
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
            else if( $slotState == 'resipn' ) 
            {
                $_obf_slotstate = $this->slotId . ' RsBonus';
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
            $cat_id = 0;
            $cat = $this->game->categories->first();
            if ($cat)
            {
                $cat_id = $cat->category->original_id;
            }
            if($isState){
                \VanguardLTE\StatGame::create([
                    'user_id' => $this->playerId, 
                    'balance' => $this->GetBalance() * $this->CurrentDenom, 
                    'bet' => $bet * $this->CurrentDenom, 
                    'win' => $win * $this->CurrentDenom, 
                    'game' => $_obf_slotstate . '_bng', 
                    'percent' => $this->toGameBanks, 
                    'percent_jps' => $this->toSysJackBanks, 
                    'percent_jpg' => $this->toSlotJackBanks, 
                    'profit' => $this->betProfit, 
                    'denomination' => $this->CurrentDenom, 
                    'shop_id' => $this->shop_id,
                    'game_id' => $this->game->original_id,
                    'category_id' => $cat_id
                ]);
            }
        }
        public function SaveBNGLogReport($model){
            \VanguardLTE\BNGGameLog::create($model);
        }
        
        public function GetSpinSettings($garantType = 'bet', $bet, $lines)
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
            if( $garantType != 'bet' ) 
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
            if( $bonusWin == 1 && $this->slotBonus ) 
            {
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
        public function getNewSpin($game, $spinWin = 0, $bonusWin = 0, $lines, $garantType = 'bet')
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
            if( $garantType != 'bet' ) 
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
        public function GetRandomMoneyPos($rp, $moneyCount)
        {
            $moneyposes = [];
            for( $i = 0; $i < count($rp) - 3; $i++ ) 
            {
                if($moneyCount == 3 && $rp[$i] == '11' && $rp[$i + 1] == '11' && $rp[$i + 2] == '11') 
                {
                    array_push($moneyposes, $i);
                }else if($moneyCount == 2 && (($rp[$i] == '11' && $rp[$i + 1] == '11' && $rp[$i + 2] != '11') || ($rp[$i] != '11' && $rp[$i + 1] == '11' && $rp[$i + 2] == '11'))){
                    array_push($moneyposes, $i);
                }else if($moneyCount == 1 && (($rp[$i] == '11' && $rp[$i + 1] != '11' && $rp[$i + 2] != '11') || ($rp[$i] != '11' && $rp[$i + 1] == '11' && $rp[$i + 2] != '11') || ($rp[$i] != '11' && $rp[$i + 1] != '11' && $rp[$i + 2] == '11'))){
                    array_push($moneyposes, $i);
                }else if($moneyCount == 0 && $rp[$i] != '11' && $rp[$i + 1] != '11' && $rp[$i + 2] != '11'){
                    array_push($moneyposes, $i);
                }
            }
            shuffle($moneyposes);
            if( !isset($moneyposes[0]) ) 
            {
                $moneyposes[0] = rand(2, count($rp) - 3);
            }
            return $moneyposes[0];
        }
        public function GetRandomScatterPos($rp)
        {
            $_obf_scatterposes = [];
            for( $i = 0; $i < count($rp); $i++ ) 
            {
                if( $rp[$i] == '10' ) 
                {
                    if( isset($rp[$i + 1]) && isset($rp[$i + 2]) ) 
                    {
                        array_push($_obf_scatterposes, $i);
                    }
                    if( isset($rp[$i - 1]) && isset($rp[$i + 1]) ) 
                    {
                        array_push($_obf_scatterposes, $i - 1);
                    }
                    if( isset($rp[$i - 2]) && isset($rp[$i - 1]) ) 
                    {
                        array_push($_obf_scatterposes, $i - 2);
                    }
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
        public function GetHighMoneySymbolCount(){
            $moneyCounts = [0, 0, 0, 0, 0, 1, 1, 1, 2, 2];
            $moneyMaskCounts = $this->GetGameData($this->slotId . 'DefaultMaskHighMoneyCount');
            $count = 0;
            for($i = 0; $i < 10; $i++){
                if($moneyMaskCounts[$i] == 1){
                    $count++;
                }
            }
            if($count == 10){
                $this->SetGameData($this->slotId . 'DefaultMaskHighMoneyCount', [0,0,0,0,0,0,0,0,0,0]);    
                $moneyMaskCounts = $this->GetGameData($this->slotId . 'DefaultMaskHighMoneyCount');
                $count = 0;
            }
            
            $moneyIndex = 0;
            if($count > 8){
                for($i = 0; $i < 10; $i++){
                    if($moneyMaskCounts[$i] == 0){
                        $moneyMaskCounts[$i] = 1;
                        $moneyIndex = $i;
                    }
                }
            }else{
                while(true){
                    $moneyIndex = mt_rand(0, 9);
                    if($moneyMaskCounts[$moneyIndex] == 0){
                        $moneyMaskCounts[$moneyIndex] = 1;
                        break;
                    }
                }
            }
            $this->SetGameData($this->slotId . 'DefaultMaskHighMoneyCount', $moneyMaskCounts);
            return $moneyCounts[$moneyIndex];
        }
        public function GetHighMoneyValue(){
            $percent = rand(0, 100);   
            $money_respin = [
                [70, 25, 5],
                [20, 50, 150]
            ];
            $sum = $money_respin[0][0];
            for($i = 1; $i < count($money_respin[0]); $i++){
                if($sum > $percent){
                    return $money_respin[1][$i - 1];
                }
                $sum = $sum + $money_respin[0][$i];
            }
            return $money_respin[1][0];
        }
        public function GetMoneyValue($winType,$overBank = false){
            $money_respin = [
                [30, 20, 15, 10, 5, 5, 2, 2, 2, 2, 2, 1, 1, 1, 1, 1],
                [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 14, 16, 20, 50, 150]
            ];
            if($overBank == true){
                return $money_respin[1][0];
            }
            if($winType == 'bonus'){
                $percent = rand(0, 95);   
            }else{
                $percent = rand(0, 96);
            }
            $sum = $money_respin[0][0];
            for($i = 1; $i < count($money_respin[0]); $i++){
                if($sum > $percent){
                    return $money_respin[1][$i - 1];
                }
                $sum = $sum + $money_respin[0][$i];
            }
            return $money_respin[1][0];
        }
        public function GetMoneyCount(){
            $moneyCounts = [
                [3,3],[2,2,2],[3,2,1],
                [3,3,1],[3,2,2],[3,3,2]
            ];
            return $moneyCounts[mt_rand(0, 5)];
        }
        public function GetMaxMoneyCount(){
            return mt_rand(8, 13);
        }
        public function GetBonusType($currentHill){
            if($currentHill[0] * 10 + $currentHill[1] >= 55){
                return 2;
            }else{
                if(mt_rand(0, 100) < 60){
                    return 2;
                }else{
                    return 1;
                }
            }
        }
        public function GetNoSameReel(){
            $percent = mt_rand(0, 100);
            if($percent < 50){
                $reelCount = 0;
            }else if($percent < 90){
                $reelCount = 1;
            }else{
                $reelCount = 2;
            }
            $noSameReels = $this->GetRandomNumber(1, 5, $reelCount);
            return $noSameReels;
        }
        public function GetReelStrips($winType, $slotEvent, $isRespin, $defaultMoneyCounts)
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
                        if( $i == 1 || $i == 2 || $i == 3 ) 
                        {
                            $_obf_reelStripCounts[$_obf_reelStripNumber[$i]] = $this->GetRandomScatterPos($this->{'reelStripBonus' . $_obf_reelStripNumber[$i]});
                            $isScatter = true;
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
                    if($isRespin == true){
                        $respinReelNumber = $this->GetRandomNumber(1, 5, count($defaultMoneyCounts));
                        shuffle($respinReelNumber);
                        for( $i = 1; $i <= 5; $i++ ) 
                        {
                            $isSame = false;
                            $defaultMoneyCount = 0;
                            for($k = 0; $k < count($respinReelNumber); $k++){
                                if($respinReelNumber[$k] == $i){
                                    $isSame = true;
                                    $defaultMoneyCount = $defaultMoneyCounts[$k];
                                    break;
                                }
                            }
                            $_obf_reelStripCounts[$i] = $this->GetRandomMoneyPos($this->{'reelStrip' . $i}, $defaultMoneyCount);
                        }
                    }else{
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
                                $_obf_reelStripCounts[$_obf_reelStripNumber[$i]] = $this->GetRandomScatterPos($this->{'reelStrip' . $_obf_reelStripNumber[$i]});
                                $isScatter = true;
                            }
                            else
                            {
                                $_obf_reelStripCounts[$_obf_reelStripNumber[$i]] = rand(0, count($this->{'reelStrip' . $_obf_reelStripNumber[$i]}) - 3);
                            }
                        }
                    }
                }
            }
            $reel = [];
            $noSameReels = $this->GetNoSameReel();
            foreach( $_obf_reelStripCounts as $index => $value ) 
            {
                $key = $this->{'reelStrip' . $index};
                if($slotEvent=='freespin'){
                    $key = $this->{'reelStripBonus' . $index};
                }
                $rc = count($key);
                $key[-1] = $key[$rc - 1];
                $key[$rc] = $key[0];
                $reel_id = $index - 1;
                $diffNum = 1;
                $reel[$reel_id] = [];
                $reel[$reel_id][0] = intval($key[$value]);
                $reel[$reel_id][1] = intval($key[($value + $diffNum) % $rc]);
                $reel[$reel_id][2] = intval($key[($value + 2 * $diffNum) % $rc]);
                if($slotEvent=='freespin'){
                    if($reel[$reel_id][0] == $reel[$reel_id][1] && ($reel[$reel_id][0] < 9)){
                        $reel[$reel_id][1] = $this->GetNoDuplicationSymbol($reel[$reel_id][0], $reel[$reel_id][2]);
                    }
                    if($reel[$reel_id][0] == $reel[$reel_id][2] && ($reel[$reel_id][0] < 9)){
                        $reel[$reel_id][2] = $this->GetNoDuplicationSymbol($reel[$reel_id][0], $reel[$reel_id][1]);
                    }
                    if($reel[$reel_id][1] == $reel[$reel_id][2] && $reel[$reel_id][1] < 9){
                        $reel[$reel_id][2] = $this->GetNoDuplicationSymbol($reel[$reel_id][0], $reel[$reel_id][1]);
                    }
                }else{
                    $isSame = false;
                    for($k = 0; $k < count($noSameReels); $k++){
                        if($index == $noSameReels[$k]){
                            $isSame = true;
                            break;
                        }
                    }
                    if(mt_rand(0, 100) < 50){
                        $start_idx = 0;
                        $end_idx = 2;
                    }else{
                        $start_idx = 2;
                        $end_idx = 0;
                    }
                    if($isSame == false){
                        if(mt_rand(0, 100) < 10){
                            $defaultSymbol = $reel[$reel_id][$start_idx];
                            if($defaultSymbol > 9){
                                $defaultSymbol = $reel[$reel_id][$end_idx];
                            }
                            $reel[$reel_id][0] = $defaultSymbol;
                            $reel[$reel_id][1] = $defaultSymbol;
                            $reel[$reel_id][2] = $defaultSymbol;
                        }else{
                            if($reel[$reel_id][$start_idx] < 5 && $reel[$reel_id][1] < 5){
                                $reel[$reel_id][1] = $reel[$reel_id][$start_idx];
                                if($reel[$reel_id][$end_idx] < 5 && mt_rand(0, 100) < 20){
                                    $reel[$reel_id][$end_idx] = $reel[$reel_id][$start_idx];
                                }
                            }else if($reel[$reel_id][$start_idx] > 5 && $reel[$reel_id][1] > 5){
                                if($reel[$reel_id][1] < 10 && $reel[$reel_id][$start_idx] < 10){
                                    $reel[$reel_id][1] = $reel[$reel_id][$start_idx];
                                    if($reel[$reel_id][$end_idx] > 5 && mt_rand(0, 100) < 20){
                                        $reel[$reel_id][$end_idx] = $reel[$reel_id][$start_idx];
                                    }
                                }
                            }else{
                                if($reel[$reel_id][1] < 10 && $reel[$reel_id][$start_idx] < 10){
                                    if(mt_rand(0, 100) < 50){
                                        $reel[$reel_id][1] = $reel[$reel_id][$start_idx];
                                    }else{
                                        $reel[$reel_id][$start_idx] = $reel[$reel_id][1];
                                    }                        
                                }else if($reel[$reel_id][1] < 10 && $reel[$reel_id][$end_idx] < 10){
                                    if(mt_rand(0, 100) < 50){
                                        $reel[$reel_id][1] = $reel[$reel_id][$end_idx];
                                    }else{
                                        $reel[$reel_id][$end_idx] = $reel[$reel_id][1];
                                    }
                                }else if($reel[$reel_id][1] >= 10){
                                    if($reel[$reel_id][$end_idx] < 5 && $reel[$reel_id][$start_idx] < 5){
                                        if(mt_rand(0, 100) < 50){
                                            $reel[$reel_id][$end_idx] = mt_rand(5, 8);
                                        }else{
                                            $reel[$reel_id][$start_idx] = mt_rand(5, 8);
                                        }
                                    }else if($reel[$reel_id][$end_idx] == $reel[$reel_id][$start_idx]){
                                        if($reel[$reel_id][$end_idx] < 5){
                                            $reel[$reel_id][$end_idx] = mt_rand(5, 8);
                                        }else{
                                            $reel[$reel_id][$end_idx] = mt_rand(1, 4);
                                        }
                                    }
                                }
                            }
                        }
                    }                    
                }
            }
            return $reel;
        }

        public function GetRandomNumber($num_first=0, $num_last=1, $get_cnt=3){
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

        public function GetNoDuplicationSymbol($first, $second){
            while(true){
                $sym = rand(5, 8);
                if($sym != $first && $sym != $second){
                    return $sym;
                }
            }
        }
    }

}

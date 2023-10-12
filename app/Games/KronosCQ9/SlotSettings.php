<?php 
namespace VanguardLTE\Games\KronosCQ9
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
            $this->Paytable['W']  = [0,0,0,0,0,0];
            $this->Paytable['SC']  = [0,0,0,0,0,0];
            $this->Paytable['1']  = [0,0,0,40,120,400];
            $this->Paytable['2']  = [0,0,0,30,80,250];
            $this->Paytable['3']  = [0,0,0,20,50,150];
            $this->Paytable['4']  = [0,0,0,15,40,90];
            $this->Paytable['11']  = [0,0,0,8,20,75];
            $this->Paytable['12']  = [0,0,0,8,20,75];
            $this->Paytable['13'] = [0,0,0,7,20,75];
            $this->Paytable['14'] = [0,0,0,5,15,60];
            $this->Paytable['15'] = [0,0,0,5,15,60];
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
            $this->jpgs = [];
            $this->Line = [1];
            $this->Bet = explode(',', $game->bet); //[0.01,0.02,0.05,0.10,0.25,0.50,1.00,3.00,5.00]; 
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
        public function GetBank($slotState = '')
        {
            //------- Happy User -------//
            if ($this->happyhouruser)
            {
                $this->Bank = $this->happyhouruser->current_bank;
                return $this->Bank / $this->CurrentDenom;
            }
            //------- *** -------//
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
            // exit( '{"responseEvent":"error","responseType":"' . $errcode . '","serverResponse":"InternalError"}' );
        }
        public function SetBank($slotState = '', $sum, $slotEvent = '', $isFreeSpin = false)
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
                    //------- Happy User -------//
                    if ($this->happyhouruser){
                        $this->happyhouruser->increment('over_bank', abs($diffMoney));
                    }
                    else {                    
                    //------- *** -------//
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
            //------- Happy User -------//
            if ($this->happyhouruser)
            {
                $this->happyhouruser->increment('current_bank', $sum);
                $this->happyhouruser->save();
            }
            else
            {
            //------- *** -------//
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
        public function SaveLogReport($spinSymbols, $bet, $lines, $win, $slotState, $roundid, $isState=true)
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
                'shop_id' => $this->shop_id,
                'roundid' => $roundid
            ]);
            if($isState == true){
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
                    'roundid' => $roundid,
                ]);
            }
        }

        public function GetSpinSettings($garantType = 'bet', $bet, $lines)
        {
            $_obf_linecount = 10;
            
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

                if( $_obf_currentbank < 0) 
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
        public function getNewSpin($game, $spinWin = 0, $bonusWin = 0, $lines, $garantType = 'bet')
        {
            $_obf_linecount = 10;
            
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
        public function getPromotionData(){
            $promo_data = [];
            $cq_promos = \VanguardLTE\CQPromo::get();
            foreach($cq_promos as $promo){
                $data = [
                    "name" => $promo->name,
                    "promoid" => $promo->promoid,
                    "promourl" => $promo->promourl,
                    "imageurl" => $promo->imageurl,
                    "icon"  => [
                        "png" => $promo->icon_png,
                        "json" => $promo->icon_json
                    ],
                    "haslink" => $promo->icon_haslink == 1 ? true : false
                ];
                array_push($promo_data, $data);
            }
            return $promo_data;
        }
        public function getRecommendList(){
            return \VanguardLTE\Http\Controllers\Web\GameProviders\CQ9Controller::getRecommendList($this->shop_id, $this->slotDBId);
        }
        public function GetNewGameLink($game_label){
            $game = \VanguardLTE\Game::where(['view' => 1, 'shop_id' => $this->shop_id, 'label' => $game_label])->whereRaw('name like "%CQ9"')->first();
            if($game == null){
                return "";
            }else{
                $fakeparams = [
                    'jackpotid' => 0,
                    'exitGame' => 1,
                    'extra' => 0,
                    'mjckey' => uniqid('AUTH@') . uniqid('~style@'),
                    'game' => $game->name, //this is real param
                    'lobbyUrl' => 'js://window.close();',
                ];
                return route('frontend.game.startgame',$fakeparams);
            }
        }

        public function SetBet() 
        { 
           if($this->GetGameData($this->slotId . 'RealBet') == null) 
           { 
               $this->SetGameData($this->slotId . 'RealBet', 0); 
           } 
           if($this->GetGameData($this->slotId . 'Lines') == null) 
           { 
               $this->SetGameData($this->slotId . 'Lines', 0); 
           } 
           $this->game->allBet = $this->GetGameData($this->slotId . 'RealBet') * $this->GetGameData($this->slotId . 'Lines'); 
        } 

        public function GetReelStrips($winType, $bet)
        {
            // if($winType == 'bonus'){
                    // $stack = \VanguardLTE\CQ9GameStackModel\CQ9GameKronosStack::where('id', 37981)->first();
                    // return json_decode($stack->spin_stack, true);
            // }
            if($winType == 'bonus'){
                $winAvaliableMoney = $this->GetBank('bonus');
            }else if($winType == 'win'){
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
                }else{
                    $winType = 'none';
                }
            }
            $isLowBank = false;
            $existIds = \VanguardLTE\PPGameFreeStackLog::where([
                'user_id' => $this->playerId,
                'game_id' => $this->game->original_id
                ])->pluck('freestack_id');
            while(true){
                if($winType == 'bonus'){
                    $stacks = \VanguardLTE\CQ9GameStackModel\CQ9GameKronosStack::where('spin_type','>', 0)->whereNotIn('id', $existIds);
                }else{
                    $stacks = \VanguardLTE\CQ9GameStackModel\CQ9GameKronosStack::where('spin_type', 0)->whereNotIn('id', $existIds);
                }
                $index = 0;// mt_rand(0, 38000);
                if($winType == 'win'){
                    $stacks = $stacks->where('odd', '>', 0);
                    // $index = mt_rand(0, 65000);
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
                                // $stacks = $stacks->where('odd', '>=', $miniOdd);
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

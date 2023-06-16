<?php 
namespace VanguardLTE
{
    use Illuminate\Support\Facades\Log;
    class CategorySummary extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'category_summary';
        protected $fillable = [
            'user_id',
            'category_id', 
            'shop_id',
            'date',
            'totalbet',
            'totalwin',
            'totalcount',
            'total_deal',
            'total_mileage',
            'type',
            'updated_at',
            'total_ggr',
            'total_ggr_mileage'
        ];
        public $timestamps = false;
        public static function boot()
        {
            parent::boot();
        }

        public function user()
        {
            return $this->belongsTo('VanguardLTE\User', 'user_id');
        }
        public function category()
        {
            return $this->belongsTo('VanguardLTE\Category', 'category_id');
        }

        public static function adjustment($user_id, $from, $to)
        {
            $categories = \VanguardLTE\Category::where(['shop_id' => 0, 'site_id' => 0]);
            $categories = $categories->get();
            $adjustments = [];
            $user = \VanguardLTE\User::where('id', $user_id)->first();
            if (!$user)
            {
                return null;
            }

            foreach ($categories as $cat)
            {
                $adj = [
                    'user_id' => $user_id,
                    'category_id' => $cat->id,
                    'shop_id' => $user->shop_id,
                    'category' => $cat->title,
                    'totalbet' => 0,
                    'totalwin' => 0,
                    'totalcount' => 0,
                    'total_deal' => 0,
                    'total_mileage' => 0,
                    'total_ggr' => 0,
                    'total_ggr_mileage' => 0,
                    'games' => []
                ];
                if ($cat->provider != null)
                {
                    $games = null;
                    try {
                        $games = call_user_func('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($cat->provider) . 'Controller::getgamelist', $cat->href);
                    }
                    catch (\Exception $e)
                    {
                        Log::error('getgamelist at category adjustment error');
                    }
                    if ($games){
                        foreach ($games as $game)
                        {
                            $adj_game = [
                                'user_id' => $user_id,
                                'category_id' => $cat->id,
                                'game_id' => $game['gamecode'],
                                'shop_id' => $user->shop_id,
                                'name' => $game['name'],
                                'totalwin' => 0,
                                'totalbet' => 0,
                                'totalcount' => 0,
                                'total_deal' => 0,
                                'total_mileage' => 0,
                                'total_ggr' => 0,
                                'total_ggr_mileage' => 0,
                            ];
                            $adj['games'][] = $adj_game;
                        }
                    }
                }
                else
                {
                    $gameIds = \VanguardLTE\GameCategory::where('category_id', $cat->id)->pluck('game_id')->toArray();
                    $games = \VanguardLTE\Game::whereIn('id', $gameIds);
                    $games = $games->get();
                    foreach ($games as $game)
                    {
                        $adj_game = [
                            'user_id' => $user_id,
                            'category_id' => $cat->id,
                            'game_id' => $game->id,
                            'shop_id' => $user->shop_id,
                            'name' => $game->name,
                            'totalwin' => 0,
                            'totalbet' => 0,
                            'totalcount' => 0,
                            'total_deal' => 0,
                            'total_mileage' => 0,
                            'total_ggr' => 0,
                            'total_ggr_mileage' => 0,
                        ];
                        $adj['games'][] = $adj_game;
                    }
                }
                $adjustments[] = $adj;
            }

            $shop_ids = $user->availableShops();
            if (count($shop_ids) > 0) {
                $query = 'SELECT game, game_id, category_id, SUM(bet) as totalbet, SUM(win) as totalwin, COUNT(*) as betcount FROM w_stat_game WHERE shop_id in ('. implode(',',$shop_ids) .') AND date_time <="'.$to .'" AND date_time>="'. $from. '" GROUP BY game_id, category_id';
                $stat_games = \DB::select($query);
            }
            else
            {
                return;
            }
            if ($user->hasRole('admin'))
            {
                $query = 'SELECT "" as game, "" as game_id, "" as category_id, 0 as total_deal, 0 as total_mileage';
            }
            else if ($user->hasRole(['comaster','group']))
            {
                if (settings('enable_master_deal'))
                {
                    $masters = $user->childPartners();
                    if (count($masters) > 0){
                        $query = 'SELECT game, game_id, category_id, 0 as total_deal, SUM(deal_profit) as total_mileage,  0 as total_ggr, SUM(ggr_mileage) as total_ggr_mileage FROM w_deal_log WHERE type="partner" AND partner_id in (' . implode(',',$masters) . ') AND date_time <="'.$to .'" AND date_time>="'. $from. '" GROUP BY game_id';
                    }
                    else
                    {
                        $query = 'SELECT "" as game, "" as game_id, "" as category_id, 0 as total_deal, 0 as total_mileage,  0 as total_ggr, 0 as total_ggr_mileage';
                    }
                }
                else
                {
                    $query = 'SELECT "" as game, "" as game_id, "" as category_id, 0 as total_deal, 0 as total_mileage, 0 as total_ggr, 0 as total_ggr_mileage';
                }
            }
            else if ($user->hasRole('master'))
            {
                if (settings('enable_master_deal'))
                {
                    $query = 'SELECT game,game_id, category_id,  SUM(deal_profit) as total_deal, SUM(mileage) as total_mileage, SUM(ggr_profit) as total_ggr, SUM(ggr_mileage) as total_ggr_mileage FROM w_deal_log WHERE type="partner" AND partner_id =' . $user->id . ' AND date_time <="'.$to .'" AND date_time>="'. $from. '" GROUP BY game_id';

                }
                else
                {
                    $agents = $user->childPartners();
                    if (count($agents) > 0){
                        $query = 'SELECT game, game_id, category_id, 0 as total_deal, SUM(deal_profit) as total_mileage, 0 as total_ggr, SUM(ggr_mileage) as total_ggr_mileage FROM w_deal_log WHERE type="partner" AND partner_id in (' . implode(',',$agents) . ') AND date_time <="'.$to .'" AND date_time>="'. $from. '" GROUP BY game_id';
                    }
                    else
                    {
                        $query = 'SELECT "" as game, "" as game_id, "" as category_id, 0 as total_deal, 0 as total_mileage, 0 as total_ggr, 0 as total_ggr_mileage';
                    }
                }
            }
            else if($user->hasRole(['agent','distributor']))
            {
                $query = 'SELECT game, game_id, category_id, SUM(deal_profit) as total_deal, SUM(mileage) as total_mileage, SUM(ggr_profit) as total_ggr, SUM(ggr_mileage) as total_ggr_mileage FROM w_deal_log WHERE type="partner" AND partner_id =' . $user->id . ' AND date_time <="'.$to .'" AND date_time>="'. $from. '" GROUP BY game_id';
            }
            else if($user->hasRole('manager'))
            {
                $query = 'SELECT game, game_id, category_id, SUM(deal_profit) as total_deal, SUM(mileage) as total_mileage, SUM(ggr_profit) as total_ggr, SUM(ggr_mileage) as total_ggr_mileage FROM w_deal_log WHERE type="shop" AND shop_id =' . $user->shop_id . ' AND date_time <="'.$to .'" AND date_time>="'. $from. '" GROUP BY game_id';
            }
            $deal_logs = \DB::select($query);


            foreach($stat_games as $stat_game) {
                $bfound_game = false;
                foreach ($adjustments as $i => $adj)
                {
                    foreach ($adj['games'] as $j => $game)
                    {
                        // $real_gamename = explode(' ', $stat_game->game)[0];
                        // if (strpos($real_gamename, '_') !== false)
                        // {
                        //     $real_gamename = explode('_', $real_gamename)[0];
                        // }
                        // if($real_gamename == $game['name'])
                        if($stat_game->game_id == $game['game_id'] && $stat_game->category_id == $adj['category_id'])
                        {
                            $game['totalbet'] = $game['totalbet'] + $stat_game->totalbet;
                            $game['totalwin'] = $game['totalwin'] + $stat_game->totalwin;
                            $game['totalcount'] = $game['totalcount'] + $stat_game->betcount;

                            $adj['totalbet'] = $adj['totalbet'] + $stat_game->totalbet;
                            $adj['totalwin'] = $adj['totalwin'] + $stat_game->totalwin;
                            $adj['totalcount'] = $adj['totalcount'] + $stat_game->betcount;
                            $adj['games'][$j] = $game;
                            $bfound_game = true;
                            break;
                        }
                    }
                    if ($bfound_game){ 
                        $adjustments[$i] = $adj;
                        break; 
                    }
                }
            }

            foreach($deal_logs as $deal_log){
                $bfound_game = false;
                foreach ($adjustments as $i => $adj)
                {
                    foreach ($adj['games'] as $j => $game)
                    {
                        // $real_gamename = explode(' ', $deal_log->game)[0];
                        // if (strpos($real_gamename, '_') !== false)
                        // {
                        //     $real_gamename = explode('_', $real_gamename)[0];
                        // }
                        // if($real_gamename == $game['name'])
                        if($deal_log->game_id == $game['game_id'] && $deal_log->category_id == $adj['category_id'])
                        {
                            $game['total_deal'] = $game['total_deal'] + $deal_log->total_deal;
                            $game['total_mileage'] = $game['total_mileage'] + $deal_log->total_mileage;
                            $game['total_ggr'] = $game['total_ggr'] + $deal_log->total_ggr;
                            $game['total_ggr_mileage'] = $game['total_ggr_mileage'] + $deal_log->total_ggr_mileage;

                            $adj['total_deal'] = $adj['total_deal'] + $deal_log->total_deal;
                            $adj['total_mileage'] = $adj['total_mileage'] + $deal_log->total_mileage;
                            $adj['total_ggr'] = $adj['total_ggr'] + $deal_log->total_ggr;
                            $adj['total_ggr_mileage'] = $adj['total_ggr_mileage'] + $deal_log->total_ggr_mileage;

                            $bfound_game = true;
                            $adj['games'][$j] = $game;
                            break;
                        }
                    }
                    if ($bfound_game){
                        $adjustments[$i] = $adj;
                        break; 
                    }
                }
            }

            //remove total bet is zero
            $filtered_adjustment = [];

            foreach ($adjustments as $index => $adj)
            {
                if ($adj['totalbet'] > 0)
                {
                    $filtered_games = [];
                    foreach ($adj['games'] as $game)
                    {
                        if ($game['totalbet'] > 0)
                        {
                            $filtered_games[] = $game;
                        }
                    }
                    usort($filtered_games, function($element1, $element2)
                    {
                        return $element2['totalbet'] - $element1['totalbet'];
                    });

                    $adj['games'] = $filtered_games;
                    $filtered_adjustment[] = $adj;
                }
            }
            return $filtered_adjustment;
        }
        public static function summary_month($shop_id, $month=null)
        {
            return;
        }

        public static function summary($user_id, $day=null)
        {
            set_time_limit(0);
            $user = \VanguardLTE\User::where('id', $user_id)->first();
            if (!$user)
            {
                return null;
            }
            if (!$day)
            {
                $day = date("Y-m-d", strtotime("-1 days"));
            }
            
            $from =  $day . " 0:0:0";
            $to = $day . " 23:59:59";
            if(!$user->hasRole('manager')){
                //repeat child partners
                $childusers = $user->childPartners(); //하위 파트너들 먼저 정산
                foreach ($childusers as $c)
                {
                    CategorySummary::summary($c, $day);
                }
            }
            $adj_cats = CategorySummary::adjustment($user_id, $from, $to);
            $catsum = [];
            if ($user->hasRole(['admin','comaster']))
            {
                \VanguardLTE\GameSummary::where(['user_id'=> $user->id, 'date' => $day, 'type'=>'daily'])->delete();
            }
            foreach ($adj_cats as $adj)
            {
                if ($user->hasRole(['admin','comaster']))
                {
                    $games = [];
                    foreach ($adj['games'] as $adjgame)
                    {
                        $adjgame['type'] = 'daily';
                        $adjgame['date'] = $day;
                        $games[] = $adjgame;
                    }
                    \VanguardLTE\GameSummary::insert($games);
                }
                unset($adj['games']);
                unset($adj['category']);
                $adj['type'] = 'daily';
                $adj['date'] = $day;
                $catsum[] = $adj;
            }
            \VanguardLTE\CategorySummary::where(['user_id'=> $user->id, 'date' => $day, 'type'=>'daily'])->delete();
            \VanguardLTE\CategorySummary::insert($catsum);
        
        }

        public static function summary_today($user_id)
        {
            set_time_limit(0);
            $user = \VanguardLTE\User::where('id', $user_id)->first();
            if (!$user)
            {
                return null;
            }
          
            if(!$user->hasRole('manager')){
                    //repeat child partners
                    $childusers = $user->childPartners(); //하위 파트너들 먼저 정산
                    foreach ($childusers as $c)
                    {
                        CategorySummary::summary_today($c);
                    }
            }

            $day = date("Y-m-d");
            $todaysumm = \VanguardLTE\CategorySummary::where(['user_id'=> $user->id, 'date' => $day, 'type'=>'today'])->get();
            if (count($todaysumm) > 0)
            {
                $from = $todaysumm->first()->updated_at;
            }
            else
            {
                $from =  $day . " 0:0:0";
            }
            $to =  date("Y-m-d H:i:s");

            $adj_cats = CategorySummary::adjustment($user_id, $from, $to);
            if (count($adj_cats) > 0) {
                if (count($todaysumm) > 0){
                    $catsum = $todaysumm->toArray();
                }
                else
                {
                    $catsum = [];
                }
                foreach ($adj_cats as $adj)
                {
                    if ($user->hasRole(['admin','comaster']))
                    {
                        $todaygamesumm = \VanguardLTE\GameSummary::where(['user_id'=> $user->id, 'date' => $day, 'type'=>'today', 'category_id' => $adj['category_id']])->get();
                        if (count($todaygamesumm) > 0){
                            $gamesum = $todaygamesumm->toArray();
                        }
                        else
                        {
                            $gamesum = [];
                        }
                        foreach ($adj['games'] as $adjgame)
                        {
                            $bFound = false;
                            foreach ($gamesum as $i => $t_gamesum)
                            {
                                if ($adjgame['name'] == $t_gamesum['name'])
                                {
                                    $adjgame['totalbet'] = $adjgame['totalbet'] + $t_gamesum['totalbet'];
                                    $adjgame['totalwin'] = $adjgame['totalwin'] + $t_gamesum['totalwin'];
                                    $adjgame['totalcount'] = $adjgame['totalcount'] + $t_gamesum['totalcount'];
                                    $adjgame['total_deal'] = $adjgame['total_deal'] + $t_gamesum['total_deal'];
                                    $adjgame['total_mileage'] = $adjgame['total_mileage'] + $t_gamesum['total_mileage'];
                                    $adjgame['total_ggr'] = $adjgame['total_ggr'] + $t_gamesum['total_ggr'];
                                    $adjgame['total_ggr_mileage'] = $adjgame['total_ggr_mileage'] + $t_gamesum['total_ggr_mileage'];
                                    $gamesum[$i] = $adjgame;
                                    $bFound = true;
                                    break;
                                }
                            }
                            if (!$bFound)
                            {
                                $gamesum[] = $adjgame;
                            }
                        }
                        $new_games = [];
                        foreach ($gamesum as $update_game)
                        {
                            $new_games[] = [
                                'user_id' => $update_game['user_id'],
                                'type' => 'today',
                                'date' => $day,
                                'name' => $update_game['name'],
                                'updated_at' => $to,
                                'category_id' => $update_game['category_id'],
                                'game_id' => $update_game['game_id'],
                                'shop_id' => $update_game['shop_id'],
                                'totalbet' => $update_game['totalbet'],
                                'totalwin' => $update_game['totalwin'],
                                'totalcount' => $update_game['totalcount'],
                                'total_deal' => $update_game['total_deal'],
                                'total_mileage' => $update_game['total_mileage'],
                                'total_ggr' => $update_game['total_ggr'],
                                'total_ggr_mileage' => $update_game['total_ggr_mileage'],
                            ]; 
                        }

                        \VanguardLTE\GameSummary::where(['user_id'=> $user->id, 'date' => $day, 'type'=>'today', 'category_id' => $adj['category_id']])->delete();
                        \VanguardLTE\GameSummary::insert($new_games);
                    }

                    unset($adj['games']);
                    unset($adj['category']);

                    $adj['type'] = 'today';
                    $adj['date'] = $day;
                    $adj['updated_at'] = $to;
                    $bFound = false;
                    foreach ($catsum as $i => $t_sum)
                    {
                        if ($adj['category_id'] == $t_sum['category_id'])
                        {
                            $adj['totalbet'] = $adj['totalbet'] + $t_sum['totalbet'];
                            $adj['totalwin'] = $adj['totalwin'] + $t_sum['totalwin'];
                            $adj['totalcount'] = $adj['totalcount'] + $t_sum['totalcount'];
                            $adj['total_deal'] = $adj['total_deal'] + $t_sum['total_deal'];
                            $adj['total_mileage'] = $adj['total_mileage'] + $t_sum['total_mileage'];
                            $adj['total_ggr'] = $adj['total_ggr'] + $t_sum['total_ggr'];
                            $adj['total_ggr_mileage'] = $adj['total_ggr_mileage'] + $t_sum['total_ggr_mileage'];
                            $catsum[$i] = $adj;
                            $bFound = true;
                            break;
                        }
                    }
                    if (!$bFound)
                    {
                        $catsum[] = $adj;
                    }
                }
                $new_cat = [];
                foreach ($catsum as $update_cat)
                {
                    $new_cat[] = [
                         'user_id' => $update_cat['user_id'],
                         'type' => 'today',
                         'date' => $update_cat['date'],
                         'updated_at' => $to,
                         'category_id' => $update_cat['category_id'],
                         'shop_id' => $update_cat['shop_id'],
                         'totalbet' => $update_cat['totalbet'],
                         'totalwin' => $update_cat['totalwin'],
                         'totalcount' => $update_cat['totalcount'],
                         'total_deal' => $update_cat['total_deal'],
                         'total_mileage' => $update_cat['total_mileage'],
                         'total_ggr' => $update_cat['total_ggr'],
                         'total_ggr_mileage' => $update_cat['total_ggr_mileage'],
                     ]; 
                }

                \VanguardLTE\CategorySummary::where(['user_id'=> $user->id, 'date' => $day, 'type'=>'today'])->delete();
                \VanguardLTE\CategorySummary::insert($new_cat);
            }
        }
    }

}

<?php 
namespace VanguardLTE
{
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
            'total_deal',
            'total_mileage',
            'type',
            'updated_at'
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
            $categories = \VanguardLTE\Category::where('shop_id', 0);
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
                    'games' => []
                ];
                if ($cat->provider != null)
                {
                    $games = call_user_func('\\VanguardLTE\\Http\\Controllers\\Web\\GameProviders\\' . strtoupper($cat->provider) . 'Controller::getgamelist', $cat->href);
                    if ($games){
                        foreach ($games as $game)
                        {
                            $adj_game = [
                                'name' => $game['name'],
                                'totalwin' => 0,
                                'totalbet' => 0,
                                'totalcount' => 0,
                                'total_deal' => 0,
                                'total_mileage' => 0,
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
                            'id' => $game->id,
                            'name' => $game->name,
                            'totalwin' => 0,
                            'totalbet' => 0,
                            'totalcount' => 0,
                            'total_deal' => 0,
                            'total_mileage' => 0,
                        ];
                        $adj['games'][] = $adj_game;
                    }
                }
                $adjustments[] = $adj;
            }

            $shop_ids = $user->availableShops();
            if (count($shop_ids) > 0) {
                $query = 'SELECT game, SUM(bet) as totalbet, SUM(win) as totalwin, COUNT(*) as betcount FROM w_stat_game WHERE shop_id in ('. implode(',',$shop_ids) .') AND date_time <="'.$to .'" AND date_time>="'. $from. '" GROUP BY game';
                $stat_games = \DB::select($query);
            }
            else
            {
                return;
            }
            if ($user->hasRole('admin'))
            {
                $query = 'SELECT "" as game, 0 as total_deal, 0 as total_mileage';
            }
            else if ($user->hasRole('comaster'))
            {
                if (settings('enable_master_deal'))
                {
                    $masters = $user->childPartners();
                    if (count($masters) > 0){
                        $query = 'SELECT game, 0 as total_deal, SUM(deal_profit) as total_mileage FROM w_deal_log WHERE type="partner" AND partner_id in (' . implode(',',$masters) . ') AND date_time <="'.$to .'" AND date_time>="'. $from. '" GROUP BY game';
                    }
                    else
                    {
                        $query = 'SELECT "" as game, 0 as total_deal, 0 as total_mileage';
                    }
                }
                else
                {
                    $query = 'SELECT "" as game, 0 as total_deal, 0 as total_mileage';
                }
            }
            else if ($user->hasRole('master'))
            {
                if (settings('enable_master_deal'))
                {
                    $query = 'SELECT game, SUM(deal_profit) as total_deal, SUM(mileage) as total_mileage FROM w_deal_log WHERE type="partner" AND partner_id =' . $user->id . ' AND date_time <="'.$to .'" AND date_time>="'. $from. '" GROUP BY game';

                }
                else
                {
                    $agents = $user->childPartners();
                    if (count($agents) > 0){
                        $query = 'SELECT game, 0 as total_deal, SUM(deal_profit) as total_mileage FROM w_deal_log WHERE type="partner" AND partner_id in (' . implode(',',$agents) . ') AND date_time <="'.$to .'" AND date_time>="'. $from. '" GROUP BY game';
                    }
                    else
                    {
                        $query = 'SELECT "" as game, 0 as total_deal, 0 as total_mileage';
                    }
                }
            }
            else if($user->hasRole(['agent','distributor']))
            {
                $query = 'SELECT game, SUM(deal_profit) as total_deal, SUM(mileage) as total_mileage FROM w_deal_log WHERE type="partner" AND partner_id =' . $user->id . ' AND date_time <="'.$to .'" AND date_time>="'. $from. '" GROUP BY game';
            }
            else if($user->hasRole('manager'))
            {
                $query = 'SELECT game, SUM(deal_profit) as total_deal, SUM(mileage) as total_mileage FROM w_deal_log WHERE type="shop" AND shop_id =' . $user->shop_id . ' AND date_time <="'.$to .'" AND date_time>="'. $from. '" GROUP BY game';
            }
            $deal_logs = \DB::select($query);


            foreach($stat_games as $stat_game) {
                $bfound_game = false;
                foreach ($adjustments as $i => $adj)
                {
                    foreach ($adj['games'] as $j => $game)
                    {
                        $real_gamename = explode(' ', $stat_game->game)[0];
                        if (strpos($real_gamename, '_') !== false)
                        {
                            $real_gamename = explode('_', $real_gamename)[0];
                        }
                        if($real_gamename == $game['name'])
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
                        $real_gamename = explode(' ', $deal_log->game)[0];
                        if (strpos($real_gamename, '_') !== false)
                        {
                            $real_gamename = explode('_', $real_gamename)[0];
                        }
                        if($real_gamename == $game['name'])
                        {
                            $game['total_deal'] = $game['total_deal'] + $deal_log->total_deal;
                            $game['total_mileage'] = $game['total_mileage'] + $deal_log->total_mileage;

                            $adj['total_deal'] = $adj['total_deal'] + $deal_log->total_deal;
                            $adj['total_mileage'] = $adj['total_mileage'] + $deal_log->total_mileage;

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
            if($user->hasRole('manager')){
                $adj_cats = CategorySummary::adjustment($user_id, $from, $to);
                $catsum = [];
                foreach ($adj_cats as $adj)
                {
                    unset($adj['games']);
                    unset($adj['category']);
                    $adj['type'] = 'daily';
                    $adj['date'] = $day;
                    $catsum[] = $adj;
                }
                \VanguardLTE\CategorySummary::where(['user_id'=> $user->id, 'date' => $day, 'type'=>'daily'])->delete();
                \VanguardLTE\CategorySummary::insert($catsum);
            }
            else
            {
                //repeat child partners
                $childusers = $user->childPartners(); //하위 파트너들 먼저 정산
                foreach ($childusers as $c)
                {
                    CategorySummary::summary($c, $day);
                }
                $adj_cats = CategorySummary::adjustment($user_id, $from, $to);
                $catsum = [];
                foreach ($adj_cats as $adj)
                {
                    unset($adj['games']);
                    unset($adj['category']);
                    $adj['type'] = 'daily';
                    $adj['date'] = $day;
                    $catsum[] = $adj;
                }
                \VanguardLTE\CategorySummary::where(['user_id'=> $user->id, 'date' => $day, 'type'=>'daily'])->delete();
                \VanguardLTE\CategorySummary::insert($catsum);
            }
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
                    unset($adj['games']);
                    unset($adj['category']);

                    $adj['type'] = 'today';
                    $adj['date'] = $day;
                    $adj['updated_at'] = $to;
                    $bFound = false;
                    foreach ($catsum as $i => $t_sum)
                    {
                        unset($catsum[$i]['id']);
                        if ($adj['category_id'] == $t_sum['category_id'])
                        {
                            $adj['totalbet'] = $adj['totalbet'] + $t_sum['totalbet'];
                            $adj['totalwin'] = $adj['totalwin'] + $t_sum['totalwin'];
                            $adj['totalcount'] = $adj['totalcount'] + $t_sum['totalcount'];
                            $adj['total_deal'] = $adj['total_deal'] + $t_sum['total_deal'];
                            $adj['total_mileage'] = $adj['total_mileage'] + $t_sum['total_mileage'];
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
                \VanguardLTE\CategorySummary::where(['user_id'=> $user->id, 'date' => $day, 'type'=>'today'])->delete();
                \VanguardLTE\CategorySummary::insert($catsum);
            }
        }
    }

}

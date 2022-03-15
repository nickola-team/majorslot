<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend
{
    class GamesController extends \VanguardLTE\Http\Controllers\Controller
    {
        public function __construct()
        {
            $this->middleware('auth');
            $this->middleware('permission:access.admin.panel');
            $this->middleware('permission:games.manage');
            $this->middleware('shopzero');
        }
        public function index(\Illuminate\Http\Request $request)
        {
/*            $checked = new \VanguardLTE\Lib\LicenseDK();
            $license_notifications_array = $checked->aplVerifyLicenseDK(null, 0);
            if( $license_notifications_array['notification_case'] != 'notification_license_ok' ) 
            {
                return redirect()->route('frontend.page.error_license');
            }
            if( !$this->security() ) 
            {
                return redirect()->route('frontend.page.error_license');
            }*/
            $views = [
                '' => '모두', 
                '1' => '활성', 
                '0' => '비활성'
            ];
            $devices = [
                '2' => '모바일 + 데스크탑', 
                '0' => '모바일', 
                '1' => '데스크탑'
            ];
            $comasters = \VanguardLTE\User::where('role_id', 7)->pluck('username','id')->toArray();
            $categories = \VanguardLTE\Category::where([
                'parent' => 0, 
                'provider' => null,
                'shop_id' => 0,
                'site_id' => 0
            ])->get();

            $shops = auth()->user()->availableShops();
            if ($request->comaster == '' || $request->comaster == '0')
            {

            }
            else
            {
                $user = \VanguardLTE\User::where('id', $request->comaster)->first();
                if (!$user)
                {   
                    return redirect()->back()->withErrors(['총본사를 찾을수 없습니다.']);
                }
                $shops = $user->shops_array(true);
            }
            
            $games = \VanguardLTE\Game::select('games.*')->whereIn('shop_id', $shops)->orderBy('name', 'ASC');
            
            $savedCategory = ($request->session()->exists('games_category') ? explode(',', $request->session()->get('games_category')) : []);
            if( (count($request->all()) || isset($request->category)) && $request->session()->get('games_category') != $request->category ) 
            {
                if( isset($request->category) ) 
                {
                    $savedCategory = $request->category;
                    $request->session()->put('games_category', implode(',', $request->category));
                }
                else
                {
                    $savedCategory = [];
                    $request->session()->forget('games_category');
                }
            }
            if( isset($request->clear) ) 
            {
                $request->session()->forget('games_category');
            }
            if( $request->search != '' ) 
            {
                $search = $request->search;
                $games = $games->where(function($q) use ($search)
                {
                    $q->where('title', 'like', '%' . $search . '%')->orWhere('name', 'like', '%' . $search . '%');
                });
            }
            if( $request->view != '' ) 
            {
                $games = $games->where('view', $request->view);
            }
            if( $request->device != '' ) 
            {
                $games = $games->where('device', $request->device);
            }
            else
            {
                $games = $games->where('device', 2); //mobile + desktop
            }
            if( $savedCategory )
            {
                $allshop_cat = \VanguardLTE\Category::whereIn('original_id',$savedCategory)->whereIn('shop_id', $shops)->pluck('id')->toArray();
                $games = $games->join('game_categories', 'game_categories.game_id', '=', 'games.id');
                $games = $games->whereIn('game_categories.category_id', $allshop_cat);
            }
            $games = $games->groupBy('original_id');
            $games = $games->paginate(50);
            return view('backend.Default.games.list', compact('games', 'views',  'devices', 'categories', 'savedCategory', 'comasters'));
        }
        public function bank(\Illuminate\Http\Request $request)
        {
            $shops = auth()->user()->availableShops();
            $gamebank = \VanguardLTE\GameBank::whereIn('shop_id', $shops)->get();
            $masters = \VanguardLTE\User::where('role_id', 6)->pluck('id')->toArray();
            $bonusbank = \VanguardLTE\BonusBank::whereIn('master_id', $masters)
                            ->selectRaw('master_id, SUM(bank) as totalBank,count(master_id) as games')
                            ->groupby('master_id')->get();

            $minslot = \VanguardLTE\Settings::where('key', 'minslot')->first();
            $maxslot = \VanguardLTE\Settings::where('key', 'maxslot')->first();
            $minbonus = \VanguardLTE\Settings::where('key', 'minbonus')->first();
            $maxbonus = \VanguardLTE\Settings::where('key', 'maxbonus')->first();
            $reset_bank = \VanguardLTE\Settings::where('key', 'reset_bank')->first();
            return view('backend.Default.games.bank', compact('gamebank','bonusbank', 'minslot','maxslot','minbonus','maxbonus','reset_bank'));
        }

        public function bonusbank(\Illuminate\Http\Request $request)
        {
            $master_id = $request->id;
            $master = \VanguardLTE\User::where('id', $master_id)->first();
            $bonusbank = \VanguardLTE\BonusBank::where('master_id', $master_id)->get();
            
            return view('backend.Default.games.bonusbank', compact('master','bonusbank'));
        }
        public function gamebanks_setting(\Illuminate\Http\Request $request)
        {
            \VanguardLTE\Settings::updateOrCreate(['key' => 'minslot'], ['value' => $request->minslot]);
            \VanguardLTE\Settings::updateOrCreate(['key' => 'maxslot'], ['value' => $request->maxslot]);
            \VanguardLTE\Settings::updateOrCreate(['key' => 'minbonus'], ['value' => $request->minbonus]);
            \VanguardLTE\Settings::updateOrCreate(['key' => 'maxbonus'], ['value' => $request->maxbonus]);
            \VanguardLTE\Settings::updateOrCreate(['key' => 'reset_bank'], ['value' => $request->reset_bank]);
            return redirect()->back()->withSuccess('환수금 설정이 업데이트되었습니다.');
        }
        public function index_json(\Illuminate\Http\Request $request)
        {
            $games = \VanguardLTE\Game::select('games.*')->where('shop_id', auth()->user()->shop_id);
            if( $request->view != '' ) 
            {
                $games = $games->where('view', $request->view);
            }
            if( $request->device != '' ) 
            {
                $games = $games->whereIn('device', (array)$request->device);
            }
            if( $request->categories ) 
            {
                $categories = $request->categories;
                foreach( $categories as $cat ) 
                {
                    $inner = \VanguardLTE\Category::where([
                        'parent' => $cat, 
                        'shop_id' => auth()->user()->shop_id
                    ])->get();
                    if( $inner ) 
                    {
                        $categories = array_merge($categories, $inner->pluck('id')->toArray());
                    }
                }
                $games = $games->join('game_categories', 'game_categories.game_id', '=', 'games.id');
                $games = $games->whereIn('game_categories.category_id', (array)$categories);
            }
            return $games->groupBy('name')->get()->pluck('name')->toJson();
        }
        public function categories(\Illuminate\Http\Request $request)
        {
            $games = [];
            if( isset($request->ids) ) 
            {
                $ids = explode(',', $request->ids);
                if( count($ids) ) 
                {
                    $games = $ids;
                }
            }
            if( isset($request->games) ) 
            {
                $temp = explode("\n", $request->games);
                foreach( $temp as $item ) 
                {
                    $game = \VanguardLTE\Game::where([
                        'name' => trim($item), 
                        'shop_id' => auth()->user()->shop_id
                    ])->first();
                    if( $game ) 
                    {
                        $games[] = $game->id;
                    }
                }
            }
            if( !count($games) ) 
            {
                return redirect()->route(config('app.admurl').'.game.list')->withErrors([trans('app.games_not_selected')]);
            }
            $games = array_unique($games);
            if( $request->action == 'change_category' ) 
            {
                if( !$request->category || !count($request->category) ) 
                {
                    return redirect()->route(config('app.admurl').'.game.list')->withErrors([trans('app.categories_not_selected')]);
                }
                foreach( $games as $game_id ) 
                {
                    \VanguardLTE\GameCategory::where('game_id', $game_id)->delete();
                    foreach( $request->category as $category ) 
                    {
                        \VanguardLTE\GameCategory::create([
                            'game_id' => $game_id, 
                            'category_id' => $category
                        ]);
                    }
                }
            }
            if( $request->action == 'add_category' ) 
            {
                if( !$request->category || !count($request->category) ) 
                {
                    return redirect()->route(config('app.admurl').'.game.list')->withErrors([trans('app.categories_not_selected')]);
                }
                foreach( $games as $game_id ) 
                {
                    foreach( $request->category as $category ) 
                    {
                        $exist = \VanguardLTE\GameCategory::where([
                            'game_id' => $game_id, 
                            'category_id' => $category
                        ])->count();
                        if( !$exist ) 
                        {
                            \VanguardLTE\GameCategory::create([
                                'game_id' => $game_id, 
                                'category_id' => $category
                            ]);
                        }
                    }
                    $game = \VanguardLTE\Game::find($game_id);
                    event(new \VanguardLTE\Events\Game\GameEdited($game, true));
                }
            }
            if( $request->action == 'delete_games' && count($games) ) 
            {
                $gameArray = \VanguardLTE\Game::whereIn('id', $games)->get();
                foreach( $gameArray as $game ) 
                {
                    \VanguardLTE\Task::create([
                        'category' => 'game', 
                        'action' => 'delete', 
                        'item_id' => $game->id
                    ]);
                    event(new \VanguardLTE\Events\Game\DeleteGame($game));
                    \VanguardLTE\StatGame::where('game', $game->name)->delete();
                    \VanguardLTE\Game::where('id', $game->id)->delete();
                }
                return redirect()->route(config('app.admurl').'.game.list')->withSuccess(trans('app.games_deleted'));
            }
            if( $request->action == 'stay_games' && count($games) ) 
            {
                $count = \VanguardLTE\Game::whereNotIn('id', $games)->where('shop_id', auth()->user()->shop_id)->count();
                $pages = ceil($count / 100);
                for( $i = 0; $i < $pages; $i++ ) 
                {
                    $gameArray = \VanguardLTE\Game::whereNotIn('id', $games)->where('shop_id', auth()->user()->shop_id)->take(100)->get();
                    foreach( $gameArray as $game ) 
                    {
                        \VanguardLTE\Task::create([
                            'category' => 'game', 
                            'action' => 'delete', 
                            'item_id' => $game->id
                        ]);
                        event(new \VanguardLTE\Events\Game\DeleteGame($game));
                        \VanguardLTE\StatGame::where('game', $game->name)->delete();
                        \VanguardLTE\Game::where('id', $game->id)->delete();
                    }
                }
                return redirect()->route(config('app.admurl').'.game.list')->withSuccess(trans('app.games_deleted'));
            }
            if( $request->action == 'change_values' ) 
            {
                $array = [];
                $shop = \VanguardLTE\Shop::find(auth()->user()->shop_id);
                $ids = $games;
                $before = microtime(true);
                $data = $request->only([
                    'rezerv', 
                    'cask', 
                    'view', 
                    'bet', 
                    'scaleMode', 
                    'numFloat', 
                    'gamebank', 
                    'slotViewState', 
                    'ReelsMath', 
                    'winline1', 
                    'winline3', 
                    'winline5', 
                    'winline7', 
                    'winline9', 
                    'winline10', 
                    'winbonus1', 
                    'winbonus3', 
                    'winbonus5', 
                    'winbonus7', 
                    'winbonus9', 
                    'winbonus10', 
                    'garant_win1', 
                    'garant_win3', 
                    'garant_win5', 
                    'garant_win7', 
                    'garant_win9', 
                    'garant_win10', 
                    'garant_bonus1', 
                    'garant_bonus3', 
                    'garant_bonus5', 
                    'garant_bonus7', 
                    'garant_bonus9', 
                    'garant_bonus10'
                ]);
                foreach( $data as $key => $value ) 
                {
                    $value = trim($value);
                    if( strlen($value) ) 
                    {
                        $array[$key] = $value;
                    }
                }
                if( count($array) || isset($request->gamebank) ) 
                {
                    \VanguardLTE\Jobs\UpdateGames::dispatch('game', $ids, $array);
                    $text = '';
                    foreach( $array as $key => $change ) 
                    {
                        $text .= ($key . '=' . $change . ', ');
                    }
                    $text = str_replace('  ', ' ', $text);
                    $text = trim($text, ' ');
                    $text = trim($text, '/');
                    $text = trim($text, ',');
                    \VanguardLTE\Task::create([
                        'category' => 'event', 
                        'action' => 'GameEdited', 
                        'item_id' => implode(',', $ids), 
                        'user_id' => auth()->user()->id, 
                        'details' => $text, 
                        'ip_address' => $request->server('REMOTE_ADDR'), 
                        'user_agent' => substr((string)$request->header('User-Agent'), 0, 500)
                    ]);
                }
                $array = [];
                $data = $request->only([
                    'match_winline1', 
                    'match_winline3', 
                    'match_winline5', 
                    'match_winline7', 
                    'match_winline9', 
                    'match_winline10', 
                    'match_winbonus1', 
                    'match_winbonus3', 
                    'match_winbonus5', 
                    'match_winbonus7', 
                    'match_winbonus9', 
                    'match_winbonus10', 
                    'match_winline_bonus1', 
                    'match_winline_bonus3', 
                    'match_winline_bonus5', 
                    'match_winline_bonus7', 
                    'match_winline_bonus9', 
                    'match_winline_bonus10'
                ]);
                foreach( $data as $key => $value ) 
                {
                    $value = trim($value);
                    if( strlen($value) ) 
                    {
                        $key = str_replace('match_', '', $key);
                        $array[$key] = $value;
                    }
                }
                if( count($array) > 0 ) 
                {
                    \VanguardLTE\Jobs\UpdateGames::dispatch('game_win', $ids, $array);
                    $text = 'Match / ';
                    foreach( $array as $key => $change ) 
                    {
                        $text .= ($key . '=' . $change . ', ');
                    }
                    $text = str_replace('  ', ' ', $text);
                    $text = trim($text, ' ');
                    $text = trim($text, '/');
                    $text = trim($text, ',');
                    \VanguardLTE\Task::create([
                        'category' => 'event', 
                        'action' => 'GameEdited', 
                        'item_id' => implode(',', $ids), 
                        'user_id' => auth()->user()->id, 
                        'details' => $text, 
                        'ip_address' => $request->server('REMOTE_ADDR'), 
                        'user_agent' => substr((string)$request->header('User-Agent'), 0, 500)
                    ]);
                }
            }
            $after = microtime(true);
            return redirect()->route(config('app.admurl').'.game.list')->withSuccess(trans('app.games_updated'));
        }
        public function view(\VanguardLTE\Game $game, \Illuminate\Http\Request $request)
        {
            $shops = auth()->user()->availableShops();
            if ($request->comaster == '' || $request->comaster == '0')
            {
            }
            else
            {
                $user = \VanguardLTE\User::where('id', $request->comaster)->first();
                if (!$user)
                {   
                    return redirect()->back()->withErrors(['총본사를 찾을수 없습니다.']);
                }
                $shops = $user->shops_array(true);
            }
            $games = \VanguardLTE\Game::where('original_id', $game->original_id)->whereIn('shop_id', $shops);
            $games->update(['view' => $request->view]);
            return redirect()->back()->withSuccess('게임상태를 변경하였습니다.');
        }
        public function create()
        {
            $game = new \VanguardLTE\Game();
            $categories = \VanguardLTE\Category::where([
                'parent' => 0, 
                'shop_id' => auth()->user()->shop_id
            ])->get();
            return view('backend.Default.games.add', compact('categories', 'game'));
        }
        public function store(\Illuminate\Http\Request $request)
        {
            $data = $request->all();
            if( !in_array($request->shop_id, auth()->user()->availableShops()) ) 
            {
                return redirect()->back()->withErrors([trans('app.wrong_shop')]);
            }
            return redirect()->route(config('app.admurl').'.game.list')->withSuccess(trans('app.game_created'));
        }
        public function edit($game)
        {
            $edit = true;
            $game = \VanguardLTE\Game::where('id', $game)->firstOrFail();
            if( !in_array($game->shop_id, auth()->user()->availableShops()) ) 
            {
                return redirect()->back('backend.Default.game.list')->withErrors([trans('app.wrong_shop')]);
            }
            $game_stat = $game->statistics()->orderBy('date_time', 'DESC')->limit(5)->get();
            $categories = \VanguardLTE\Category::where([
                'parent' => 0, 
                'shop_id' => auth()->user()->shop_id
            ])->get();
            $cats = \VanguardLTE\GameCategory::where('game_id', $game->id)->pluck('category_id')->toArray();
            $jpgs = \VanguardLTE\JPG::where('shop_id', auth()->user()->shop_id)->pluck('name', 'id')->toArray();
            return view('backend.Default.games.edit', compact('edit', 'game', 'game_stat', 'categories', 'cats', 'jpgs'));
        }
        public function mass(\Illuminate\Http\Request $request)
        {
            $ids = [];
            if( $ids = $request->only('checkbox') ) 
            {
                $ids = array_keys($ids['checkbox']);
            }
            $categories = \VanguardLTE\Category::where([
                'parent' => 0, 
                'shop_id' => auth()->user()->shop_id
            ])->get();
            $emptyGame = new \VanguardLTE\Game();
            return view('backend.Default.games.mass', compact('emptyGame', 'categories', 'ids'));
        }
        public function go(\Illuminate\Http\Request $request, $game)
        {
            $userId = \Illuminate\Support\Facades\Auth::id();
            $object = '\VanguardLTE\Games\\' . $game . '\SlotSettings';
            $slot = new $object($game, $userId);
            $game = \VanguardLTE\Game::where('name', $game)->first();
            return view('backend.Default.games.list.' . $game->name, compact('slot', 'game'));
        }
        public function server(\Illuminate\Http\Request $request, $game)
        {
            $object = '\VanguardLTE\Games\\' . $game . '\Server';
            $server = new $object();
            echo $server->get($request, $game);
        }
        public function update($game, \Illuminate\Http\Request $request)
        {
            $request->validate([
                'gamebank' => 'sometimes|required|in:' . implode(',', \VanguardLTE\Game::$values['gamebank']), 
                'rezerv' => 'sometimes|required|in:' . implode(',', \VanguardLTE\Game::$values['rezerv']), 
                'cask' => 'sometimes|required|in:' . implode(',', \VanguardLTE\Game::$values['cask'])
            ]);
            $data = $request->only([
                'name', 
                'title', 
                'shop_id', 
                'percent', 
                'gamebank', 
                'jpg_id', 
                'label', 
                'winline1', 
                'winline3', 
                'winline5', 
                'winline7', 
                'winline9', 
                'winline10', 
                'winbonus1', 
                'winbonus3', 
                'winbonus5', 
                'winbonus7', 
                'winbonus9', 
                'winbonus10', 
                'winline_bonus1', 
                'winline_bonus3', 
                'winline_bonus5', 
                'winline_bonus7', 
                'winline_bonus9', 
                'winline_bonus10', 
                'garant_win1', 
                'garant_win3', 
                'garant_win5', 
                'garant_win7', 
                'garant_win9', 
                'garant_win10', 
                'garant_bonus1', 
                'garant_bonus3', 
                'garant_bonus5', 
                'garant_bonus7', 
                'garant_bonus9', 
                'garant_bonus10', 
                'garant_win_bonus1', 
                'garant_win_bonus3', 
                'garant_win_bonus5', 
                'garant_win_bonus7', 
                'garant_win_bonus9', 
                'garant_win_bonus10', 
                'rezerv', 
                'device', 
                'cask', 
                'denomination', 
                'view', 
                'gameline', 
                'monitor', 
                'bet', 
                'scaleMode', 
                'numFloat', 
                'slotViewState', 
                'stat_in', 
                'stat_out', 
                'ReelsMath'
            ]);
            $gameData = \VanguardLTE\Game::find($game);
            $gameData->update($data);
            if( !in_array($gameData->shop_id, auth()->user()->availableShops()) ) 
            {
                return redirect()->back('backend.Default.game.list')->withErrors([trans('app.wrong_shop')]);
            }
            $matchEdited = false;
            $categoryEdited = false;
            $data = $request->only([
                'match_winline1', 
                'match_winline3', 
                'match_winline5', 
                'match_winline7', 
                'match_winline9', 
                'match_winline10', 
                'match_winbonus1', 
                'match_winbonus3', 
                'match_winbonus5', 
                'match_winbonus7', 
                'match_winbonus9', 
                'match_winbonus10', 
                'match_winline_bonus1', 
                'match_winline_bonus3', 
                'match_winline_bonus5', 
                'match_winline_bonus7', 
                'match_winline_bonus9', 
                'match_winline_bonus10'
            ]);
            if( count($data) > 0 ) 
            {
                $win = \VanguardLTE\GameWin::where('game_id', $gameData->id)->first();
                foreach( [
                    1, 
                    3, 
                    5, 
                    7, 
                    9, 
                    10
                ] as $index ) 
                {
                    if( isset($data['match_winline' . $index]) ) 
                    {
                        $win->{'winline' . $index} = $data['match_winline' . $index];
                    }
                }
                foreach( [
                    1, 
                    3, 
                    5, 
                    7, 
                    9, 
                    10
                ] as $index ) 
                {
                    if( isset($data['match_winline_bonus' . $index]) ) 
                    {
                        $win->{'winline_bonus' . $index} = $data['match_winline_bonus' . $index];
                    }
                }
                foreach( [
                    1, 
                    3, 
                    5, 
                    7, 
                    9, 
                    10
                ] as $index ) 
                {
                    if( isset($data['match_winbonus' . $index]) ) 
                    {
                        $win->{'winbonus' . $index} = $data['match_winbonus' . $index];
                    }
                }
                $win->save();
                $matchEdited = true;
            }
            if( isset($request->category) ) 
            {
                \VanguardLTE\GameCategory::where('game_id', $gameData->id)->delete();
                foreach( $request->category as $category ) 
                {
                    \VanguardLTE\GameCategory::create([
                        'game_id' => $gameData->id, 
                        'category_id' => $category
                    ]);
                }
                $categoryEdited = true;
            }
            event(new \VanguardLTE\Events\Game\GameEdited($gameData, $categoryEdited, $matchEdited));
            return redirect()->route(config('app.admurl').'.game.edit', $gameData->id)->withSuccess(trans('app.game_updated'));
        }
        public function delete(\VanguardLTE\Game $game)
        {
            if( !in_array($game->shop_id, auth()->user()->availableShops()) ) 
            {
                return redirect()->back('backend.Default.game.list')->withErrors([trans('app.wrong_shop')]);
            }
            event(new \VanguardLTE\Events\Game\DeleteGame($game));
            \VanguardLTE\GameWin::where('game_id', $game->id)->delete();
            \VanguardLTE\GameCategory::where('game_id', $game->id)->delete();
            \VanguardLTE\StatGame::where('game', $game->name)->delete();
            \VanguardLTE\GameLog::where('game_id', $game->id)->delete();
            \VanguardLTE\Game::where('id', $game->id)->delete();
            return redirect()->route(config('app.admurl').'.game.list')->withSuccess(trans('app.game_deleted'));
        }
        public function gamebanks_add(\Illuminate\Http\Request $request)
        {
            $request->summ = str_replace(',','', $request->summ);
            if( $request->summ && $request->summ < 0 ) 
            {
                return redirect()->back()->withErrors([trans('app.wrong_sum')]);
            }
            if( !$request->type ) 
            {
                return redirect()->back()->withErrors([trans('app.wrong_gamebank_type')]);
            }
            $act = 'add';
            if ($request->act)
            {
                $act = $request->act;
            }

            
            if( auth()->user()->hasRole('admin') ) 
            {
                /*if( $request->summ > 0 && $shop->balance < $request->summ ) 
                {
                    return redirect()->back()->withErrors([trans('app.not_enough_money_in_the_shop', [
                        'name' => $shop->name, 
                        'balance' => $shop->balance
                    ])]);
                } */
                if ($request->type=='bonus')
                {
                    if ($request->id==0)
                    {
                        $bonus_bank = \VanguardLTE\BonusBank::where('master_id', $request->master)->get();
                    }
                    else
                    {
                        $bonus_bank = \VanguardLTE\BonusBank::where('id', $request->id)->get();
                    }

                    foreach ($bonus_bank as $bb){
                        $master = \VanguardLTE\User::where('id', $bb->master_id)->first();
                        if ($master)
                        {
                            $name = $master->username;
                            $shop_id = 0;
                            $old = $bb->bank;
                            if ($act == 'add')
                            {
                                $bb->increment('bank', abs($request->summ));
                            }
                            else
                            {
                                $bb->decrement('bank', abs($request->summ));
                            }
                            $new = $bb->bank;
                            $type = 'bonus';
                            $game = ' General';
                            if ($bb->game_id!=0)
                            {
                                $game = $bb->game->title;
                            }
                            \VanguardLTE\BankStat::create([
                                'name' => ucfirst($type) . "[$name]" . "-$game", 
                                'user_id' => \Illuminate\Support\Facades\Auth::id(), 
                                'type' => $act, 
                                'sum' => $request->summ, 
                                'old' => $old, 
                                'new' => $new, 
                                'shop_id' => $shop_id
                            ]);
                        }
                        
                    }
                }
                elseif ($request->type=='bonusmax')
                {
                    if ($request->id==0)
                    {
                        $bonus_bank = \VanguardLTE\BonusBank::where('master_id', $request->master)->get();
                    }
                    else
                    {
                        $bonus_bank = \VanguardLTE\BonusBank::where('id', $request->id)->get();
                    }

                    foreach ($bonus_bank as $bb){
                        if ($act == 'add')
                        {
                            $bb->increment('max_bank', abs($request->summ));
                        }
                        else
                        {
                            $bb->decrement('max_bank', abs($request->summ));
                        }                        
                    }
                }
                else
                {
                    if ($request->shop==0)
                    {
                        $shops = auth()->user()->availableShops();
                        $gamebank = \VanguardLTE\GameBank::whereIn('shop_id', $shops)->get();
                    }
                    else
                    {
                        $gamebank = \VanguardLTE\GameBank::where('shop_id', $request->shop)->get();
                    }
                    foreach ($gamebank as $gb){
                        $shop = $gb->shop;
                        $name = $shop->name;
                        $old = $gb->{$request->type};
                        if ($act == 'add')
                        {
                            $gb->increment($request->type, abs($request->summ));
                        }
                        else
                        {
                            $gb->decrement($request->type, abs($request->summ));
                        }
                        $new = $gb->{$request->type};
                        $shop_id = $gb->shop_id;
                        $type = ($request->type == 'table_bank' ? 'table' : $request->type);
                        \VanguardLTE\BankStat::create([
                            'name' => ucfirst($type) . "[$name]", 
                            'user_id' => \Illuminate\Support\Facades\Auth::id(), 
                            'type' => $act, 
                            'sum' => $request->summ, 
                            'old' => $old, 
                            'new' => $new, 
                            'shop_id' => $shop_id
                        ]);
                    }
                }
                
                return redirect()->back()->withSuccess(trans('app.gamebank_added'));
            }
            else
            {
                return redirect()->back()->withErrors([trans('app.no_permission')]);
            }
        }
        public function gamebanks_clear(\Illuminate\Http\Request $request)
        {
            if( auth()->user()->hasRole('admin') ) 
            {
                if ($request->type=='bonus')
                {
                    if ($request->id==0)
                    {
                        $bonus_bank = \VanguardLTE\BonusBank::where('master_id', $request->master)->get();
                    }
                    else
                    {
                        $bonus_bank = \VanguardLTE\BonusBank::where('id', $request->id)->get();
                    }

                    foreach ($bonus_bank as $bb){
                        $master = \VanguardLTE\User::where('id', $bb->master_id)->first();
                        if ($master)
                        {
                            $name = $master->username;
                            $shop_id = 0;
                            $old = $bb->bank;
                            $bb->update(['bank' => 0]);
                            $type = 'bonus';
                            $game = ' General';
                            if ($bb->game_id!=0)
                            {
                                $game = $bb->game->title;
                            }
                            \VanguardLTE\BankStat::create([
                                'name' => ucfirst($type) . "[$name]-$game", 
                                'user_id' => \Illuminate\Support\Facades\Auth::id(), 
                                'type' =>  ($old<0)?'add':'out', 
                                'sum' => abs($old), 
                                'old' => $old, 
                                'new' => 0, 
                                'shop_id' => $shop_id
                            ]);
                        }
                        
                    }
                }
                elseif ($request->type=='bonusmax')
                {
                    if ($request->id==0)
                    {
                        $bonus_bank = \VanguardLTE\BonusBank::where('master_id', $request->master)->get();
                    }
                    else
                    {
                        $bonus_bank = \VanguardLTE\BonusBank::where('id', $request->id)->get();
                    }

                    foreach ($bonus_bank as $bb){
                        $bb->update(['max_bank' => 0]);
                    }
                }
                else
                {

                    if ($request->shop==0)
                    {
                        $shops = auth()->user()->availableShops();
                        $gamebank = \VanguardLTE\GameBank::whereIn('shop_id', $shops)->get();
                    }
                    else
                    {
                        $gamebank = \VanguardLTE\GameBank::where('shop_id', $request->shop)->get();
                    }
                    foreach ($gamebank as $gb){
                        $shop = $gb->shop;
                        $name = $shop->name;
                        $old = $gb->{$request->type};
                        $gb->update([$request->type => 0]);
                        $shop_id = $gb->shop_id;
                        $type = ($request->type == 'table_bank' ? 'table' : $request->type);
                        \VanguardLTE\BankStat::create([
                            'name' => ucfirst($type) . "[$name]", 
                            'user_id' => \Illuminate\Support\Facades\Auth::id(), 
                            'type' => ($old<0)?'add':'out', 
                            'sum' => abs($old), 
                            'old' => $old, 
                            'new' => 0, 
                            'shop_id' => $shop_id
                        ]);
                    }

                }
                return redirect()->back()->withSuccess(trans('app.gamebank_cleared'));
            }
            else
            {
                return redirect()->back()->withErrors([trans('app.no_permission')]);
            }
        }
/*        public function security()
        {
            if( config('LicenseDK.APL_INCLUDE_KEY_CONFIG') != 'wi9qydosuimsnls5zoe5q298evkhim0ughx1w16qybs2fhlcpn' ) 
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
            return true;
        }*/
    }

}
namespace 
{
    function onkXppk3PRSZPackRnkDOJaZ9()
    {
        return 'OkBM2iHjbd6FHZjtvLpNHOc3lslbxTJP6cqXsMdE4evvckFTgS';
    }

}

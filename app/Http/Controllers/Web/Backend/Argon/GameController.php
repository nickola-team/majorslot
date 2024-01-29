<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend\Argon
{
    class GameController extends \VanguardLTE\Http\Controllers\Controller
    {
        public function __construct()
        {
            $this->middleware('auth');
            $this->middleware('permission:access.admin.panel');
            $this->middleware( function($request,$next) {
                if (!auth()->user()->isInOutPartner())
                {
                    return response('허용되지 않은 접근입니다.', 401);
                }
                return $next($request);
            }
            );
        }

        public function category_update(\Illuminate\Http\Request $request)
        {
            $categoryid = $request->cat_id;
            $data = $request->only([
                'status',
                'view'
            ]);
            $userid = $request->user_id;
            $availablePartners = auth()->user()->availableUsers();
            $user = \VanguardLTE\User::where('id',$userid)->first();
            if (!$user || !in_array($userid, $availablePartners))
            {
                return redirect()->back()->withErrors(['에이전트를 찾을수 없습니다']);
            }
            $shops = $user->shops(true);
            if ($user->hasRole('admin'))
            {
                $shops[] = 0; // include all domain settings
            }
            if (count($shops) == 0)
            {
                return redirect()->back()->withErrors(['하부매장이 없습니다']);
            }
            \VanguardLTE\Category::where('original_id', $categoryid)->whereIn('shop_id', $shops)->update($data);
            return redirect()->back()->withSuccess(['게임상태를 업데이트했습니다']);
        }

        public function game_category(\Illuminate\Http\Request $request)
        {
            set_time_limit(0);
            $excat = ['hot', 'new', 'card','bingo','roulette', 'keno', 'novomatic','wazdan','skywind'];
            $categories = \VanguardLTE\Category::where(['shop_id' => 0, 'site_id' => 0])->whereNotIn('href', $excat)->orderBy('position', 'desc');
            $users = \VanguardLTE\User::where('id', auth()->user()->id);
            if ($request->user != '')
            {
                $users = \VanguardLTE\User::whereIn('status', [\VanguardLTE\Support\Enum\UserStatus::ACTIVE, \VanguardLTE\Support\Enum\UserStatus::BANNED]);
                $users = $users->where('username', 'like', '%' . $request->user . '%')->where('role_id', '>=', 3);
                if ($request->role != '')
                {
                    $users = $users->where('role_id', $request->role);
                }
            }
            if ($request->category != '')
            {
                $categories = $categories->where('title', 'like', '%' . $request->category . '%');
            }
            $categories = $categories->paginate(10);
            $users = $users->get();
            return view('backend.argon.game.category', compact('users','categories'));
        }

        public function domain_category(\Illuminate\Http\Request $request)
        {
            set_time_limit(0);
            $availablePartners = auth()->user()->hierarchyPartners();
            $availablePartners[] = [auth()->user()->id];
            $sites = \VanguardLTE\WebSite::orderby('id')->whereIn('adminid', $availablePartners);
            if ($request->domain != '')
            {
                $sites = $sites->where('title', 'like', '%'. $request->domain . '%');
            }
            if ($request->username != '')
            {
                $comaster = \VanguardLTE\User::where('username', $request->username)->first();
                if (!$comaster || !in_array( $comaster->id, $availablePartners))
                {
                    return redirect()->back()->withErrors(['총본사를 찾을수 없습니다']);
                }
                $sites = $sites->where('adminid', $comaster->id);
                
            }
            $sites = $sites->paginate(5);
            return view('backend.argon.game.domain', compact('sites'));
        }
        public function domain_provider_update(\Illuminate\Http\Request $request)
        {
            $availablePartners = auth()->user()->hierarchyPartners();
            $availablePartners[] = [auth()->user()->id];
            $siteIds = \VanguardLTE\WebSite::orderby('id')->whereIn('adminid', $availablePartners)->get()->pluck('id')->toArray();

            $categoryid = $request->cat_id;
            $provider = $request->provider;
            $category = \VanguardLTE\Category::where('id', $categoryid)->first();
            if (!$category || !in_array($category->site_id, $siteIds))
            {
                return redirect()->back()->withErrors(['게임을 찾을수 없습니다']);
            }

            $site_id = $category->site_id;
            $site = \VanguardLTE\WebSite::where('id', $site_id)->first();
            if (!$site)
            {
                return redirect()->back()->withErrors(['도메인을 찾을수 없습니다']);
            }

            $newcat = null;
            if ($provider == 1)
            {
                $newcat = \VanguardLTE\Category::where([
                    'title' => $category->title,
                    'site_id' => $site_id,
                    'shop_id' => 0
                    ]
                    )->whereNotNull('provider')->first();
            }
            else
            {
                $newcat = \VanguardLTE\Category::where([
                    'title' => $category->title,
                    'site_id' => $site_id,
                    'shop_id' => 0
                    ]
                    )->whereNull('provider')->first();
            }
            if (!$newcat)
            {
                return redirect()->back()->withErrors(['교체할 제공사가 없습니다']);
            }

            $admin = $site->admin;
            $availableShops = $admin->shops(true);
            // $availableShops = $admin->availableShops();

            \VanguardLTE\Category::where('original_id' , $category->original_id)->whereIn('shop_id', $availableShops)->update(['view' => 0]);
            \VanguardLTE\Category::where('original_id' , $category->original_id)->where('site_id', $site_id)->update(['view' => 0]);

            \VanguardLTE\Category::where('original_id' , $newcat->original_id)->whereIn('shop_id', $availableShops)->update(['view' => 1]);
            \VanguardLTE\Category::where('original_id' , $newcat->original_id)->where('site_id', $site_id)->update(['view' => 1]);

            return redirect()->back()->withSuccess(['게임제공사를 교체했습니다']);

        }
        public function domain_update(\Illuminate\Http\Request $request)
        {
            $categoryid = $request->cat_id;
            $data = $request->only([
                'status',
                'view'
            ]);
            // $status = $request->status;
            // $view = $request->view;
            if (!auth()->user()->hasRole('admin')) //if user is not admin, could not change category view
            {
                unset($data['view']);
            }
            $category = \VanguardLTE\Category::where('id', $categoryid)->first();

            $availablePartners = auth()->user()->hierarchyPartners();
            $availablePartners[] = [auth()->user()->id];
            $siteIds = \VanguardLTE\WebSite::orderby('id')->whereIn('adminid', $availablePartners)->get()->pluck('id')->toArray();

            if (!$category  || !in_array($category->site_id, $siteIds))
            {
                return redirect()->back()->withErrors(['게임을 찾을수 없습니다']);
            }
            // $category->update(['view' => $status]);

            $site_id = $category->site_id;
            $site = \VanguardLTE\WebSite::where('id', $site_id)->first();
            if (!$site)
            {
                return redirect()->back()->withErrors(['도메인을 찾을수 없습니다']);
            }
            //check if admin category is enabled
            $orgCategory = \VanguardLTE\Category::where('id' , $category->original_id)->first();
            if (!auth()->user()->hasRole('admin') && ($orgCategory && $orgCategory->status == 0))
            {
                return redirect()->back()->withErrors(['상위파트너에 의하여 조작이 불가능한 게임입니다.']);
            }
            $admin = $site->admin;
            $availableShops = $admin->availableShops();
            //remove 0 shop id
            if (($key = array_search(0, $availableShops)) !== false) {
                unset($availableShops[$key]);
            }
            \VanguardLTE\Category::where('original_id' , $category->original_id)->whereIn('shop_id', $availableShops)->update($data);
            \VanguardLTE\Category::where('original_id' , $category->original_id)->where('site_id', $site_id)->update($data);

            return redirect()->back()->withSuccess(['게임상태를 업데이트했습니다']);

        }

        public function game_update(\Illuminate\Http\Request $request)
        {
            $gameid = $request->game_id;
            $value = $request->value;
            $userid = $request->user_id;
            $field = $request->field;

            $user = \VanguardLTE\User::where('id',$userid)->first();
            if (!$user)
            {
                return redirect()->back()->withErrors(['에이전트를 찾을수 없습니다']);
            }
            $shops = $user->shops(true);
            if ($user->hasRole('admin'))
            {
                $shops[] = 0;
            }
            if (count($shops) == 0)
            {
                return redirect()->back()->withErrors(['하부매장이 없습니다']);
            }
            \VanguardLTE\Game::where('original_id', $gameid)->whereIn('shop_id', $shops)->update([$field => $value]);
            return redirect()->back()->withSuccess(['게임상태를 업데이트했습니다']);
        }



        public function game_game(\Illuminate\Http\Request $request)
        {
            set_time_limit(0);
            $user_id = $request->user_id;
            $cat_id = $request->cat_id;

            $category = \VanguardLTE\Category::where('id', $cat_id)->first();
            if (!$category)
            {
                return redirect()->back()->withErrors(['게임사를 찾을수 없습니다']);
            }
            $games = $category->games->paginate(10);
            $user = \VanguardLTE\User::where('id', $user_id)->first();
            if (!$user)
            {
                return redirect()->back()->withErrors(['에이전트를 찾을수 없습니다']);
            }
            return view('backend.argon.game.game', compact('user','games','category'));
        }
        public function game_transaction(\Illuminate\Http\Request $request)
        {
            $statistics = \VanguardLTE\BankStat::select('bank_stat.*')->orderBy('bank_stat.created_at', 'DESC');

            $start_date = date("Y-m-1 0:0:0");
            $end_date = date("Y-m-d H:i:s");

            if ($request->dates != '')
            {
                $start_date = preg_replace('/T/',' ', $request->dates[0]);
                $end_date = preg_replace('/T/',' ', $request->dates[1]);            
            }
            $statistics = $statistics->where('bank_stat.created_at', '>=', $start_date);
            $statistics = $statistics->where('bank_stat.created_at', '<=', $end_date );
            $total = [
                'add' => (clone $statistics)->where('type', 'add')->sum('sum'),
                'out' => (clone $statistics)->where('type', 'out')->sum('sum'),
            ];
            $statistics = $statistics->paginate(20);
            return view('backend.argon.game.transaction', compact('statistics','total'));
        }
        public function gamebanks_setting(\Illuminate\Http\Request $request)
        {
            \VanguardLTE\Settings::updateOrCreate(['key' => 'minslot1'], ['value' => $request->minslot1]);
            \VanguardLTE\Settings::updateOrCreate(['key' => 'maxslot1'], ['value' => $request->maxslot1]);
            \VanguardLTE\Settings::updateOrCreate(['key' => 'minslot2'], ['value' => $request->minslot2]);
            \VanguardLTE\Settings::updateOrCreate(['key' => 'maxslot2'], ['value' => $request->maxslot2]);
            \VanguardLTE\Settings::updateOrCreate(['key' => 'minslot3'], ['value' => $request->minslot3]);
            \VanguardLTE\Settings::updateOrCreate(['key' => 'maxslot3'], ['value' => $request->maxslot3]);
            \VanguardLTE\Settings::updateOrCreate(['key' => 'minslot4'], ['value' => $request->minslot4]);
            \VanguardLTE\Settings::updateOrCreate(['key' => 'maxslot4'], ['value' => $request->maxslot4]);
            \VanguardLTE\Settings::updateOrCreate(['key' => 'minslot5'], ['value' => $request->minslot5]);
            \VanguardLTE\Settings::updateOrCreate(['key' => 'maxslot5'], ['value' => $request->maxslot5]);
            
            
            \VanguardLTE\Settings::updateOrCreate(['key' => 'minbonus1'], ['value' => $request->minbonus1]);
            \VanguardLTE\Settings::updateOrCreate(['key' => 'maxbonus1'], ['value' => $request->maxbonus1]);
            \VanguardLTE\Settings::updateOrCreate(['key' => 'minbonus2'], ['value' => $request->minbonus2]);
            \VanguardLTE\Settings::updateOrCreate(['key' => 'maxbonus2'], ['value' => $request->maxbonus2]);
            \VanguardLTE\Settings::updateOrCreate(['key' => 'minbonus3'], ['value' => $request->minbonus3]);
            \VanguardLTE\Settings::updateOrCreate(['key' => 'maxbonus3'], ['value' => $request->maxbonus3]);
            \VanguardLTE\Settings::updateOrCreate(['key' => 'minbonus4'], ['value' => $request->minbonus4]);
            \VanguardLTE\Settings::updateOrCreate(['key' => 'maxbonus4'], ['value' => $request->maxbonus4]);
            \VanguardLTE\Settings::updateOrCreate(['key' => 'minbonus5'], ['value' => $request->minbonus5]);
            \VanguardLTE\Settings::updateOrCreate(['key' => 'maxbonus5'], ['value' => $request->maxbonus5]);
            
            \VanguardLTE\Settings::updateOrCreate(['key' => 'reset_bank'], ['value' => $request->reset_bank]);
            return redirect()->back()->withSuccess(['환수금 설정이 업데이트되었습니다.']);
        }
        public function game_bankstore(\Illuminate\Http\Request $request)
        {
            $bank_type = $request->type;
            $batch = $request->batch;
            $bank_id = $request->id;
            $act = $request->balancetype;
            $amount = $request->amount;
            $master = $request->master;

            if ($request->outAll == '1') //bank clear
            {
                if ($bank_type=='bonus')
                {
                    if ($batch=='1')
                    {
                        $bonus_bank = \VanguardLTE\BonusBank::where('master_id', $request->master)->get();
                    }
                    else
                    {
                        $bonus_bank = \VanguardLTE\BonusBank::where('id', $bank_id)->get();
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
                elseif ($bank_type=='bonusmax')
                {
                    if ($batch=='1')
                    {
                        $bonus_bank = \VanguardLTE\BonusBank::where('master_id', $request->master)->get();
                    }
                    else
                    {
                        $bonus_bank = \VanguardLTE\BonusBank::where('id', $bank_id)->get();
                    }

                    foreach ($bonus_bank as $bb){
                        $bb->update(['max_bank' => 0]);
                    }
                }
                else
                {

                    if ($batch=='1')
                    {
                        $shops = auth()->user()->availableShops();
                        $gamebank = \VanguardLTE\GameBank::whereIn('shop_id', $shops)->get();
                    }
                    else
                    {
                        $gamebank = \VanguardLTE\GameBank::where('id', $bank_id)->get();
                    }
                    foreach ($gamebank as $gb){
                        $shop = $gb->shop;
                        $name = $shop->name;
                        $old = $gb->slots;
                        $gb->update(['slots' => 0]);
                        $shop_id = $gb->shop_id;
                        $type = 'slots';
                        \VanguardLTE\BankStat::create([
                            'name' => ucfirst($type) . "[$name]", 
                            'user_id' => \Illuminate\Support\Facades\Auth::id(), 
                            'type' => (($old<0)?'add':'out'), 
                            'sum' => abs($old), 
                            'old' => $old, 
                            'new' => 0, 
                            'shop_id' => $shop_id
                        ]);
                    }
                }
            }
            else
            {
                if ($bank_type=='bonus')
                {
                    if ($batch=='1')
                    {
                        $bonus_bank = \VanguardLTE\BonusBank::where('master_id', $master)->get();
                    }
                    else
                    {
                        $bonus_bank = \VanguardLTE\BonusBank::where('id', $bank_id)->get();
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
                                $bb->increment('bank', abs($amount));
                            }
                            else
                            {
                                $bb->decrement('bank', abs($amount));
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
                                'sum' => abs($amount), 
                                'old' => $old, 
                                'new' => $new, 
                                'shop_id' => $shop_id
                            ]);
                        }
                        
                    }
                }
                elseif ($bank_type=='bonusmax')
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
                    if ($batch=='1')
                    {
                        $shops = auth()->user()->availableShops();
                        $gamebank = \VanguardLTE\GameBank::whereIn('shop_id', $shops)->get();
                    }
                    else
                    {
                        $gamebank = \VanguardLTE\GameBank::where('id', $bank_id)->get();
                    }
                    foreach ($gamebank as $gb){
                        $shop = $gb->shop;
                        $name = $shop->name;
                        $old = $gb->slots;
                        if ($act == 'add')
                        {
                            $gb->increment('slots', abs($amount));
                        }
                        else
                        {
                            $gb->decrement('slots', abs($amount));
                        }
                        $new = $gb->slots;
                        $shop_id = $gb->shop_id;
                        $type = 'slots';
                        \VanguardLTE\BankStat::create([
                            'name' => ucfirst($type) . "[$name]", 
                            'user_id' => \Illuminate\Support\Facades\Auth::id(), 
                            'type' => $act, 
                            'sum' => abs($amount), 
                            'old' => $old, 
                            'new' => $new, 
                            'shop_id' => $shop_id
                        ]);
                    }
                }
            }

            if ($bank_type == 'bonus')
            {
                return redirect()->to(argon_route('argon.game.bonusbank',['id'=>$request->master]));
            }
            else
            {
                return redirect()->to(argon_route('argon.game.bank'));
            }

        }
        public function game_bankbalance(\Illuminate\Http\Request $request)
        {
            $bank_type = $request->type;
            $batch = $request->batch;
            $bank_id = $request->id;
            $master = $request->master;
            $bankinfo = [
                'type' => $bank_type,
                'batch' => $batch,
                'id' => $bank_id,
                'name' => '일괄수정',
                'balance' => 0,
                'master' => $master,
            ];
            if ($batch == '1')
            {

            }
            else
            {
                if ($bank_type == 'bonus')
                {
                    $bank = \VanguardLTE\BonusBank::where('id', $bank_id)->first();
                    if (!$bank)
                    {
                        return redirect()->back()->withSuccess('에이전트를 찾을수 없습니다');
                    }
                    if ($bank->game_id == 0)
                    {
                        $bankinfo['name'] = $bank->master->username . ' - 일반';
                    }
                    else
                    {
                        $bankinfo['name'] = $bank->master->username . ' - ' . $bank->game->title;
                    }
                    $bankinfo['balance'] = $bank->bank;
                    $bankinfo['master'] = $bank->master_id;
                }
                else
                {
                    $bank = \VanguardLTE\GameBank::where('id', $bank_id)->first();
                    if (!$bank)
                    {
                        return redirect()->back()->withSuccess('에이전트를 찾을수 없습니다');
                    }
                    $bankinfo['name'] = $bank->shop->name;
                    $bankinfo['balance'] = $bank->slots;
                }
                
            }

            return view('backend.argon.game.bankbalance', compact('bankinfo'));
        }

        public function game_bonusbank(\Illuminate\Http\Request $request)
        {
            $master_id = $request->id;
            $master = \VanguardLTE\User::where('id', $master_id)->first();
            $bonusbank = \VanguardLTE\BonusBank::where('master_id', $master_id)->get();
            
            return view('backend.argon.game.bonusbank', compact('master','bonusbank'));
        }

        public function game_bank(\Illuminate\Http\Request $request)
        {
            $shops = auth()->user()->availableShops();
            $gamebank = \VanguardLTE\GameBank::whereIn('shop_id', $shops);
            $masters = \VanguardLTE\User::where('role_id', 6)->pluck('id')->toArray();
            $bonusbank = \VanguardLTE\BonusBank::whereIn('master_id', $masters)
                            ->selectRaw('id, master_id, SUM(bank) as totalBank,count(master_id) as games')
                            ->groupby('master_id')->get();

            $gamebank = $gamebank->paginate(20);

            $minslot1 = \VanguardLTE\Settings::where('key', 'minslot1')->first();
            $maxslot1 = \VanguardLTE\Settings::where('key', 'maxslot1')->first();
            $minslot2 = \VanguardLTE\Settings::where('key', 'minslot2')->first();
            $maxslot2 = \VanguardLTE\Settings::where('key', 'maxslot2')->first();
            $minslot3 = \VanguardLTE\Settings::where('key', 'minslot3')->first();
            $maxslot3 = \VanguardLTE\Settings::where('key', 'maxslot3')->first();
            $minslot4 = \VanguardLTE\Settings::where('key', 'minslot4')->first();
            $maxslot4 = \VanguardLTE\Settings::where('key', 'maxslot4')->first();
            $minslot5 = \VanguardLTE\Settings::where('key', 'minslot5')->first();
            $maxslot5 = \VanguardLTE\Settings::where('key', 'maxslot5')->first();

            $minbonus1 = \VanguardLTE\Settings::where('key', 'minbonus1')->first();
            $maxbonus1 = \VanguardLTE\Settings::where('key', 'maxbonus1')->first();
            $minbonus2 = \VanguardLTE\Settings::where('key', 'minbonus2')->first();
            $maxbonus2 = \VanguardLTE\Settings::where('key', 'maxbonus2')->first();
            $minbonus3 = \VanguardLTE\Settings::where('key', 'minbonus3')->first();
            $maxbonus3 = \VanguardLTE\Settings::where('key', 'maxbonus3')->first();
            $minbonus4 = \VanguardLTE\Settings::where('key', 'minbonus4')->first();
            $maxbonus4 = \VanguardLTE\Settings::where('key', 'maxbonus4')->first();
            $minbonus5 = \VanguardLTE\Settings::where('key', 'minbonus5')->first();
            $maxbonus5 = \VanguardLTE\Settings::where('key', 'maxbonus5')->first();

            $reset_bank = \VanguardLTE\Settings::where('key', 'reset_bank')->first();
            return view('backend.argon.game.bank', compact('gamebank','bonusbank', 'minslot1','maxslot1','minslot2','maxslot2','minslot3','maxslot3','minslot4','maxslot4','minslot5','maxslot5','minbonus1','maxbonus1','minbonus2','maxbonus2','minbonus3','maxbonus3','minbonus4','maxbonus4','minbonus5','maxbonus5','reset_bank'));
        }
        public function game_betlimit(\Illuminate\Http\Request $request)
        {
            $betConfig = \VanguardLTE\ProviderInfo::where('user_id', 0)->where('provider', 'gac')->first();
            if (!$betConfig)
            {
                return redirect()->back()->withErrors(['기본 배팅한도 설정을 찾을수 없습니다.']);
            }

            $betLimits = json_decode($betConfig->config, true);
            $betLimits1 = null;

            if (!auth()->user()->hasRole('admin'))
            {
                $betConfig1 = \VanguardLTE\ProviderInfo::where('user_id', auth()->user()->id)->where('provider', 'gac')->first();
                if ($betConfig1)
                {
                    $betLimits1 = json_decode($betConfig1->config, true);
                    if (!isset($betLimits1[0]['BetLimit']))
                    {
                        $betLimits1 = [
                            [
                                'tableIds' => ['default'],
                                'BetLimit' => $betLimits1
                            ]
                        ];
                    }
                }
            }
            
            foreach ($betLimits as $limit)
            {
                if ($betLimits1)
                {
                    foreach ($betLimits1 as $limit1)
                    {
                        if ($limit['tableIds'] == $limit1['tableIds'])
                        {
                            $limit = $limit1;
                        }
                    }
                }

                foreach ($limit['tableIds'] as $tblId)
                {
                    if ($tblId == 'default')
                    {
                        $limit['tableName'][] = '기본설정';
                    }
                    else
                    {
                        $gameObj = \VanguardLTE\Http\Controllers\Web\GameProviders\GACController::getGameObj($tblId);
                        if (!$gameObj)
                        {
                            $limit['tableName'][] = $tblId;
                        }
                        else
                        {
                            $limit['tableName'][] = $gameObj['title'];
                        }
                    }
                    
                }
                $betUpdateTableName[] = $limit;
            }
            $betLimits = $betUpdateTableName;

            
            return view('backend.argon.game.betlimit', compact('betLimits'));
        }


        public function game_betlimitupdate(\Illuminate\Http\Request $request)
        {
            $data = $request->except('tableid');
            $tableIds = explode(',', $request->tableid);
            if (auth()->user()->hasRole('admin'))
            {
                $betConfig = \VanguardLTE\ProviderInfo::where('user_id', 0)->where('provider', 'gac')->first();
                if (!$betConfig)
                {
                    abort(500);
                }
                else
                {
                    $betLimits = json_decode($betConfig->config, true);
                    $updateLimits = [];
                    foreach ($betLimits as $limit)
                    {
                        if ($limit['tableIds'] == $tableIds)
                        {
                            $limit['BetLimit'] = $data;
                        }
                        $updateLimits[] = $limit;
                    }

                    $betConfig->update(['config' => json_encode($updateLimits)]);
                }
            }
            else
            {
                $betConfig = \VanguardLTE\ProviderInfo::where('user_id', auth()->user()->id)->where('provider', 'gac')->first();
                if (!$betConfig)
                {
                    $updateLimits[] = [
                        'tableIds' => $tableIds,
                        'BetLimit' => $data
                    ];
                    \VanguardLTE\ProviderInfo::create([
                        'user_id' => auth()->user()->id,
                        'provider' => 'gac',
                        'config' => json_encode($updateLimits)
                    ]);
                }
                else
                {
                    $betLimits = json_decode($betConfig->config, true);
                    if (!isset($betLimits[0]['BetLimit']))
                    {
                        $betLimits = [
                            [
                                'tableIds' => ['default'],
                                'BetLimit' => $betLimits
                            ]
                        ];
                    }

                    $updateLimits = [];
                    $bfound = false;
                    foreach ($betLimits as $limit)
                    {
                        if ($limit['tableIds'] == $tableIds)
                        {
                            $limit['BetLimit'] = $data;
                            $bfound = true;
                        }
                        $updateLimits[] = $limit;
                    }

                    if (!$bfound)
                    {
                        $updateLimits[] = [
                            'tableIds' => $tableIds,
                            'BetLimit' => $data
                        ];
                    }

                    $betConfig->update(['config' => json_encode($updateLimits)]);
                }
            }
            
            return redirect()->back()->withSuccess(['배팅한도가 업데이트되었습니다']);
        }

        public function game_gactable(\Illuminate\Http\Request $request)
        {
            $gacstatTable = \VanguardLTE\Http\Controllers\Web\GameProviders\GACController::getgamelist('gvo');
            $providerinfo = \VanguardLTE\ProviderInfo::where('provider','gacclose')->where('user_id', auth()->user()->id)->first();
            $gacclosed = [];
            if ($providerinfo)
            {
                $gacclosed = json_decode($providerinfo->config, true);
            }
            $gactables = [];
            foreach ($gacstatTable as $table )
            {
                if (in_array($table['gamecode'], $gacclosed))
                {
                    $table['view'] = 0;
                }
                else
                {
                    $table['view'] = 1;
                }
                $gactables[] = $table;
            }
            return view('backend.argon.game.gactable', compact('gactables'));
        }

        public function game_gactableupdate(\Illuminate\Http\Request $request)
        {
            $table = $request->table;
            $view = $request->view;

            $providerinfo = \VanguardLTE\ProviderInfo::where('provider','gacclose')->where('user_id', auth()->user()->id)->first();
            $gacclosed = [];
            if ($providerinfo)
            {
                $gacclosed = json_decode($providerinfo->config, true);
            }
            if ($view == 1)
            {
                $gacclosed = array_diff($gacclosed, array($table));
            }
            else
            {
                $gacclosed[] = $table;
            }
            if ($providerinfo)
            {
                if (count($gacclosed) > 0)
                {
                    $providerinfo->update(['config' => json_encode($gacclosed)]);
                }
                else
                {
                    $providerinfo->delete();
                }
            }
            else
            {
                \VanguardLTE\ProviderInfo::create([
                    'user_id' => auth()->user()->id,
                    'provider' => 'gacclose',
                    'config' => json_encode($gacclosed)
                ]);
            }
            
            return redirect()->back()->withSuccess(['테이블상태를 업데이트 했습니다']);
        }

        public function game_missrole(\Illuminate\Http\Request $request)
        {
            $user = auth()->user();
            $info = $user->info;
            $data = [
                'slot_total_deal' => 0,
                'slot_total_miss' => 0,
                'table_total_deal' => 0,
                'table_total_miss' => 0,

            ];
            foreach ($info as $inf)
            {
                if ($inf->roles =='slot')
                {
                    $data['slot_total_deal'] = $inf->title;
                    $data['slot_total_miss'] = $inf->link;
                }
                if ($inf->roles =='table')
                {
                    $data['table_total_deal'] = $inf->title;
                    $data['table_total_miss'] = $inf->link;
                }
            }
            $shops = [];
            $shopIds = $user->availableShops();
            if (count($shopIds)>0)
            {
                $shops = \VanguardLTE\Shop::whereIn('id', $shopIds);
                if ($request->shopname != '')
                {
                    $shops = $shops->where('name', 'like', '%'.$request->shopname.'%');
                }
                $shops = $shops->paginate(10);

            }

            return view('backend.argon.game.missrole', compact('data', 'shops'));
        }
        public function game_missrolestatus(\Illuminate\Http\Request $request)
        {
            $shopIds = [$request->id];
            $status = $request->status;
            $type = $request->type;
            $availableShops = auth()->user()->shops_array(true);
            if ($request->id == 0)
            {
                $shopIds = $availableShops;
            }
            else
            {
                if (!in_array($request->id, $availableShops))
                {
                    return redirect()->back()->withErrors(['매장을 찾을수 없습니다']);
                }
            }
            $shops = \VanguardLTE\Shop::whereIn('id', $shopIds)->update(
                [
                    $type . '_miss_deal' => $status,
                    $type . '_garant_deal' => 0,
                ]
            );

            return redirect()->back()->withSuccess(['매장의 공배팅상태를 업데이트했습니다']);
        }

        public function game_missroleupdate(\Illuminate\Http\Request $request)
        {
            $user = auth()->user();
            $info = $user->info;
            $data = $request->all();
            if ($data['slot_total_deal']== 0 || $data['slot_total_miss']==0)
            {
                return redirect()->back()->withErrors(['공배팅값은 0이 될수 없습니다']);
            }
            $slotinf = null;
            $tableinf = null;

            foreach ($info as $inf)
            {
                if ($inf->roles =='slot')
                {
                    $inf->title = $data['slot_total_deal'];
                    $inf->link = $data['slot_total_miss'];
                    $slotinf = $inf;
                    $inf->save();
                }
                if ($inf->roles =='table')
                {
                    $inf->title = $data['table_total_deal'];
                    $inf->link = $data['table_total_miss'];
                    $tableinf = $inf;
                    $inf->save();
                }
            }
            if ($slotinf==null)//create
            {
                \VanguardLTE\Info::create(
                    [
                        'link' => $data['slot_total_miss'],
                        'title' => $data['slot_total_deal'],
                        'roles' => 'slot',
                        'user_id' => $user->id
                    ]
                    );
            }
            if ($tableinf==null)//create
            {
                \VanguardLTE\Info::create(
                    [
                        'link' => $data['table_total_miss'],
                        'title' => $data['slot_total_deal'],
                        'roles' => 'table',
                        'user_id' => $user->id
                    ]
                    );
            }

            //create all info about child shops
            $shopIds = $user->availableShops();
            if (count($shopIds)>0)
            {
                $shops = \VanguardLTE\Shop::whereIn('id', $shopIds)->get();
                foreach ($shops as $shop)
                {
                    $infoShop = \VanguardLTE\InfoShop::where('shop_id', $shop->id)->get();
                    $slotinf = null;
                    $tableinf = null;
                    foreach ($infoShop as $info)
                    {
                        $inf = $info->info;
                        if ($inf && $inf->roles =='slot')
                        {
                            $inf->title = $data['slot_total_deal'];
                            $inf->link = $data['slot_total_miss'];
                            $inf->text = implode(',', rand_region_numbers($data['slot_total_deal'],$data['slot_total_miss']));
                            $slotinf = $inf;
                            $inf->save();
                        }
                        if ($inf && $inf->roles =='table')
                        {
                            $inf->title = $data['table_total_deal'];
                            $inf->link = $data['table_total_miss'];
                            $inf->text = implode(',', rand_region_numbers($data['table_total_deal'],$data['table_total_miss']));
                            $tableinf = $inf;
                            $inf->save();
                        }
                    }
                    if ($slotinf==null)//create
                    {
                        $slotinf = \VanguardLTE\Info::create(
                            [
                                'link' => $data['slot_total_miss'],
                                'title' => $data['slot_total_deal'],
                                'roles' => 'slot',
                                'text' => implode(',', rand_region_numbers($data['slot_total_deal'],$data['slot_total_miss'])),
                                'user_id' => $user->id
                            ]
                            );
                        \VanguardLTE\InfoShop::create(
                            [
                                'shop_id' => $shop->id,
                                'info_id' => $slotinf->id
                            ]
                            );
                    }
                    if ($tableinf==null)//create
                    {
                        $tableinf = \VanguardLTE\Info::create(
                            [
                                'link' => $data['table_total_miss'],
                                'title' => $data['table_total_deal'],
                                'roles' => 'table',
                                'text' => implode(',', rand_region_numbers($data['table_total_deal'],$data['table_total_miss'])),
                                'user_id' => $user->id
                            ]
                            );
                        \VanguardLTE\InfoShop::create(
                            [
                                'shop_id' => $shop->id,
                                'info_id' => $tableinf->id
                            ]
                            );
                    }
                    $init_table_miss = ($data['table_total_miss']==0||$data['table_total_deal']==0)?0:1;
                    $init_slot_miss = ($data['slot_total_miss']==0||$data['slot_total_deal']==0)?0:1;

                    $shop->update([
                        'slot_miss_deal' => $init_slot_miss,
                        'slot_garant_deal' => 0,
                        'table_miss_deal' => $init_table_miss,
                        'table_garant_deal' => 0,
                    ]);

                }
            }
            return redirect()->back()->withSuccess(['공배팅설정이 업데이트되었습니다']);
        }
    }
    

}

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
            $status = $request->status;
            $userid = $request->user_id;
            $user = \VanguardLTE\User::where('id',$userid)->first();
            if (!$user)
            {
                return redirect()->back()->withErrors(['에이전트를 찾을수 없습니다']);
            }
            $shops = $user->shops(true);
            if (count($shops) == 0)
            {
                return redirect()->back()->withErrors(['하부매장이 없습니다']);
            }
            \VanguardLTE\Category::where('original_id', $categoryid)->whereIn('shop_id', $shops)->update(['view' => $status]);
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
            $sites = \VanguardLTE\WebSite::orderby('id');
            if ($request->domain != '')
            {
                $sites = $sites->where('title', 'like', '%'. $request->domain . '%');
            }
            $sites = $sites->paginate(5);
            return view('backend.argon.game.domain', compact('sites'));
        }
        public function domain_update(\Illuminate\Http\Request $request)
        {
            $categoryid = $request->cat_id;
            $status = $request->status;
            $category = \VanguardLTE\Category::where('id', $categoryid)->first();
            if (!$category)
            {
                return redirect()->back()->withErrors(['게임을 찾을수 없습니다']);
            }
            // $category->update(['view' => $status]);

            $site_id = $category->site_id;
            \VanguardLTE\Category::where(['site_id' => $site_id, 'original_id' => $category->original_id])->update(['view' => $status]);

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
            \VanguardLTE\Settings::updateOrCreate(['key' => 'minslot'], ['value' => $request->minslot]);
            \VanguardLTE\Settings::updateOrCreate(['key' => 'maxslot'], ['value' => $request->maxslot]);
            \VanguardLTE\Settings::updateOrCreate(['key' => 'minbonus'], ['value' => $request->minbonus]);
            \VanguardLTE\Settings::updateOrCreate(['key' => 'maxbonus'], ['value' => $request->maxbonus]);
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

            $minslot = \VanguardLTE\Settings::where('key', 'minslot')->first();
            $maxslot = \VanguardLTE\Settings::where('key', 'maxslot')->first();
            $minbonus = \VanguardLTE\Settings::where('key', 'minbonus')->first();
            $maxbonus = \VanguardLTE\Settings::where('key', 'maxbonus')->first();
            $reset_bank = \VanguardLTE\Settings::where('key', 'reset_bank')->first();
            return view('backend.argon.game.bank', compact('gamebank','bonusbank', 'minslot','maxslot','minbonus','maxbonus','reset_bank'));
        }
        public function game_betlimit(\Illuminate\Http\Request $request)
        {
            $betConfig = null;
            $default = false;
            if (!auth()->user()->hasRole('admin'))
            {
                $betConfig = \VanguardLTE\ProviderInfo::where('user_id', auth()->user()->id)->where('provider', 'gac')->first();
            }
            if (!$betConfig)
            {
                $default = true;
                $betConfig = \VanguardLTE\ProviderInfo::where('user_id', 0)->where('provider', 'gac')->first();
            }
            if (!$betConfig)
            {
                return redirect()->back()->withErrors(['배팅한도 설정을 찾을수 없습니다.']);
            }
            $betLimits = json_decode($betConfig->config, true);
            if ($default)
            {
                $betLimits = $betLimits[0]['BetLimit'];
            }
            
            return view('backend.argon.game.betlimit', compact('betLimits'));
        }

        public function game_betlimitupdate(\Illuminate\Http\Request $request)
        {
            $data = $request->all();
            if (auth()->user()->hasRole('admin'))
            {
                $betConfig = \VanguardLTE\ProviderInfo::where('user_id', 0)->where('provider', 'gac')->first();
                if (!$betConfig)
                {
                    abort(500);
                }
                else
                {
                    $betConfig->update(['config' => json_encode(
                        [
                            [
                                'tableIds' => ['default'],
                                'BetLimit' => $data
                            ]
                        ]
                    )]);
                }
            }
            else
            {

                $betConfig = \VanguardLTE\ProviderInfo::where('user_id', auth()->user()->id)->where('provider', 'gac')->first();
                if (!$betConfig)
                {
                    \VanguardLTE\ProviderInfo::create([
                        'user_id' => auth()->user()->id,
                        'provider' => 'gac',
                        'config' => json_encode($data)
                    ]);
                }
                else
                {
                    $betConfig->update(['config' => json_encode($data)]);
                }
            }
            
            return redirect()->back()->withSuccess(['배팅한도가 업데이트되었습니다']);
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
                $shops = \VanguardLTE\Shop::whereIn('id', $shopIds)->paginate(10);
            }

            return view('backend.argon.game.missrole', compact('data', 'shops'));
        }
        public function game_missrolestatus(\Illuminate\Http\Request $request)
        {
            $shopId = $request->id;
            $status = $request->status;
            $type = $request->type;
            $shop = \VanguardLTE\Shop::where('id', $shopId)->first();
            $availableShops = auth()->user()->availableShops();
            if (!in_array($shopId, $availableShops) || !$shop)
            {
                return redirect()->back()->withErrors(['매장을 찾을수 없습니다']);
            }
            $shop->update([
                $type . '_miss_deal' => $status,
                $type . '_garant_deal' => 0,
            ]);
            return redirect()->back()->withSuccess(['매장의 공배팅상태를 업데이트했습니다']);
        }

        public function game_missroleupdate(\Illuminate\Http\Request $request)
        {
            $user = auth()->user();
            $info = $user->info;
            $data = $request->all();
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
                        if ($inf->roles =='slot')
                        {
                            $inf->title = $data['slot_total_deal'];
                            $inf->link = $data['slot_total_miss'];
                            $inf->text = implode(',', rand_region_numbers($data['slot_total_deal'],$data['slot_total_miss']));
                            $slotinf = $inf;
                            $inf->save();
                        }
                        if ($inf->roles =='table')
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

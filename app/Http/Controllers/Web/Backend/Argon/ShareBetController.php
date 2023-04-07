<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend\Argon
{
    class ShareBetController extends \VanguardLTE\Http\Controllers\Controller
    {
        public function __construct(\VanguardLTE\Repositories\User\UserRepository $users)
        {
            $this->middleware('auth');
            $this->middleware('permission:access.admin.panel');
        }

        public function index(\Illuminate\Http\Request $request)
        {
            $comasterIds = auth()->user()->availableUsersByRole('comaster');
            $users = \VanguardLTE\User::whereIn('id', $comasterIds)->whereIn('status', [\VanguardLTE\Support\Enum\UserStatus::ACTIVE, \VanguardLTE\Support\Enum\UserStatus::BANNED]);
            if ($request->user != '')
            {
                $users = $users->where('username', 'like', '%' . $request->user . '%');
            }
            $usersum = (clone $users)->get();
            $sum = 0;
            $count = 0;
            foreach ($usersum as $u)
            {
                $sum = $sum + $u->deal_balance;
                
            }

            $total = [
                'count' => $users->count(),
                'deal' => $sum
            ];

            $users = $users->paginate(20);
            return view('backend.argon.share.index', compact('users','total'));
        }
        public function setting(\Illuminate\Http\Request $request)
        {
            $partner_id = $request->id;
            if ($partner_id == 0)
            {
                $partner = auth()->user();
            }
            else
            {
                $partner = \VanguardLTE\User::where('id', $partner_id)->first();

                $comasterIds = auth()->user()->availableUsersByRole('comaster');
                if (!$partner || !in_array($partner_id, $comasterIds))
                {
                    return redirect()->back()->withErrors(['파트너를 찾을수 없습니다']);
                }
            }
            $categories = \VanguardLTE\Category::where(['shop_id'=>0,'site_id'=>0,'type' => 'live','view' => 1])->get();
            $sharebetinfos = \VanguardLTE\ShareBetInfo::where(['partner_id' => $partner->id, 'share_id' => $partner->parent_id])->get();
            return view('backend.argon.share.setting', compact('partner', 'categories','sharebetinfos'));
        }
        public function setting_store(\Illuminate\Http\Request $request)
        {
            $partner_id = $request->user_id;
            $partner = \VanguardLTE\User::where('id', $partner_id)->first();
            $comasterIds = auth()->user()->availableUsersByRole('comaster');
            if (!$partner || !in_array($partner_id, $comasterIds))
            {
                return redirect()->back()->withErrors(['파트너를 찾을수 없습니다']);
            }
            $data = $request->except(['user_id']);
            foreach ($data as $key => $value)
            {
                if (str_contains($key, 'share_'))
                {
                    $catdata = explode('_',$key);
                    $catid = $catdata[1];
                    $alreadydata = \VanguardLTE\ShareBetInfo::where(['partner_id' => $partner_id, 'share_id' => $partner->parent_id, 'category_id' => $catid])->first();
                    if ($alreadydata)
                    {
                        if ($value == 0)
                        {
                            $alreadydata->delete();
                        }
                        else
                        {
                            $alreadydata->update(['minlimit' => $value]);
                        }
                    }
                    else
                    {
                        if ($value > 0)
                        {
                            \VanguardLTE\ShareBetInfo::create(['partner_id' => $partner_id, 'share_id' => $partner->parent_id, 'category_id' => $catid, 'minlimit' => $value]);
                        }
                    }
                }
            }
            return redirect()->to(argon_route('argon.share.index'))->withSuccess(['받치기 설정을 업데이트했습니다']);

        }
        public function convert_deal(\Illuminate\Http\Request $request)
        {
        }

        public function gamestat(\Illuminate\Http\Request $request)
        {
            $user = auth()->user();
            $sharebetlogs = \VanguardLTE\ShareBetLog::orderBy('date_time', 'desc');
            $start_date = date("Y-m-d H:i:s", strtotime("-1 days"));
            $end_date = date("Y-m-d H:i:s");
            if ($request->dates != '')
            {
                // $dates = explode(' - ', $request->dates);
                $start_date = preg_replace('/T/',' ', $request->dates[0]);
                $end_date = preg_replace('/T/',' ', $request->dates[1]);            
            }
            $sharebetlogs = $sharebetlogs->where('date_time', '>=', $start_date);
            $sharebetlogs = $sharebetlogs->where('date_time', '<=', $end_date );

            if ($request->player != '')
            {
                $statistics = $statistics->where('users.username', 'like', '%' . $request->player . '%');
            }
            if ($request->game != '')
            {
                $statistics = $statistics->where('stat_game.game', 'like', '%'. $request->game . '%');
            }

            if( $request->win_from != '' ) 
            {
                $statistics = $statistics->where('stat_game.win', '>=', $request->win_from);
            }
            if( $request->win_to != '' ) 
            {
                $statistics = $statistics->where('stat_game.win', '<=', $request->win_to);
            }
            
            if ($request->shop != '')
            {
                $shop_ids = \VanguardLTE\Shop::where('name', 'like', '%' . $request->shop . '%')->whereIn('id', $availableShops)->pluck('id')->toArray();
                if (count($shop_ids) > 0) 
                {
                    $availableShops = $shop_ids;
                }
                else
                {
                    $availableShops = [-1];
                }
            }


        }

        public function report_daily(\Illuminate\Http\Request $request)
        {

        }

        public function report_game(\Illuminate\Http\Request $request)
        {

        }


    }

}

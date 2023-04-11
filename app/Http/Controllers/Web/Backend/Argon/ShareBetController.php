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
            //currently support gac
            $categories = \VanguardLTE\Category::where(['shop_id'=>0,'site_id'=>0,'type' => 'live','view' => 1, 'href'=>'gvo'])->get();

            $sharebetinfos = \VanguardLTE\ShareBetInfo::where(['partner_id' => $partner->id, 'share_id' => $partner->parent_id])->get();

            return view('backend.argon.share.setting', compact('partner', 'categories','sharebetinfos'));
        }
        public function setting_store(\Illuminate\Http\Request $request)
        {
            $partner_id = $request->user_id;
            $cat_id = $request->cat_id;

            $partner = \VanguardLTE\User::where('id', $partner_id)->first();
            $comasterIds = auth()->user()->availableUsersByRole('comaster');
            if (!$partner || !in_array($partner_id, $comasterIds))
            {
                return redirect()->back()->withErrors(['파트너를 찾을수 없습니다']);
            }
            $sharekeys = [];
            foreach (\VanguardLTE\ShareBetInfo::BET_TYPES as $type => $values)
            {
                foreach ($values as $k => $v)
                {
                    $sharekeys[] = $k;
                }
            }
            $data = $request->all($sharekeys);
            //filter minimum value
            $minlimit = 0;
            foreach ($data as $key => $value)
            {
                if ($value > 0 && ($minlimit==0 || $minlimit > $value))
                {
                    $minlimit = $value;
                }
            }


            $alreadydata = \VanguardLTE\ShareBetInfo::where(['partner_id' => $partner_id, 'share_id' => $partner->parent_id, 'category_id' => $cat_id])->first();
            if ($alreadydata)
            {
                if ($minlimit == 0)
                {
                    $alreadydata->delete();
                }
                else
                {
                    $alreadydata->update(['minlimit' => $minlimit, 'limit_info' => json_encode($data)]);
                }
            }
            else
            {
                if ($minlimit > 0)
                {
                    \VanguardLTE\ShareBetInfo::create([
                        'partner_id' => $partner_id, 
                        'share_id' => $partner->parent_id, 
                        'category_id' => $cat_id, 
                        'minlimit' => $minlimit,
                        'limit_info' => json_encode($data)
                    ]);
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
            
            $sharebetlogs = \VanguardLTE\ShareBetLog::select('sharebet_log.*')->orderBy('sharebet_log.date_time', 'desc');
            if ($user->hasRole('comaster'))
            {
                $sharebetlogs = $sharebetlogs->where('partner_id', $user->id);
            }
            else if ($user->hasRole('group'))
            {
                $sharebetlogs = $sharebetlogs->where('share_id', $user->id);
            }

            $start_date = date("Y-m-d H:i:s", strtotime("-1 days"));
            $end_date = date("Y-m-d H:i:s");
            if ($request->dates != '')
            {
                // $dates = explode(' - ', $request->dates);
                $start_date = preg_replace('/T/',' ', $request->dates[0]);
                $end_date = preg_replace('/T/',' ', $request->dates[1]);            
            }
            $sharebetlogs = $sharebetlogs->where('sharebet_log.date_time', '>=', $start_date);
            $sharebetlogs = $sharebetlogs->where('sharebet_log.date_time', '<=', $end_date );

            $sharebetlogs = $sharebetlogs->join('users', 'users.id', '=', 'sharebet_log.user_id');

            if ($request->player != '')
            {
                $sharebetlogs = $sharebetlogs->where('users.username', 'like', '%' . $request->player . '%');
            }
            if ($request->game != '')
            {
                $sharebetlogs = $sharebetlogs->where('sharebet_log.game', 'like', '%'. $request->game . '%');
            }

            if( $request->win_from != '' ) 
            {
                $sharebetlogs = $sharebetlogs->where('sharebet_log.win', '>=', $request->win_from);
            }
            if( $request->win_to != '' ) 
            {
                $sharebetlogs = $sharebetlogs->where('sharebet_log.win', '<=', $request->win_to);
            }

            if ($request->partner != '')
            {
                $availableUsers = auth()->user()->availableUsers();
                $user_ids = \VanguardLTE\User::where('username', $request->partner)->whereIn('id', $availableUsers)->pluck('id')->toArray();
                if (count($user_ids) > 0) 
                {
                    $sharebetlogs = $sharebetlogs->whereIn('sharebet_log.partner_id', $user_ids);
                }
                else
                {
                    $sharebetlogs = $sharebetlogs->whereIn('sharebet_log.partner_id', [-1]);
                }
            }
            $totallogs = (clone $sharebetlogs)->get();
            $total = [
                'bet' => $totallogs->sum('bet'),
                'win' => $totallogs->sum('win'),
                'sharebet' => $totallogs->sum('bet') - $totallogs->sum('betlimit'),
                'sharewin' => $totallogs->sum('win') - $totallogs->sum('winlimit'),
                'sharedeal' => $totallogs->sum('deal_share'),
            ];

            $sharebetlogs = $sharebetlogs->paginate(20);
            
            return view('backend.argon.share.game', compact('sharebetlogs','total'));


        }

        public function report_daily(\Illuminate\Http\Request $request)
        {

        }

        public function report_game(\Illuminate\Http\Request $request)
        {

        }


    }

}

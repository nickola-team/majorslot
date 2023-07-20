<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend\Argon
{
    class HappyHourController extends \VanguardLTE\Http\Controllers\Controller
    {
        public function __construct()
        {
            $this->middleware('auth');
            $this->middleware('permission:access.admin.panel');
            // $this->middleware('permission:happyhours.manage');
            $this->middleware('shopzero');
        }
        public function index(\Illuminate\Http\Request $request)
        {
            $availableUsers = auth()->user()->availableUsers();
            $availableUsers[] = auth()->user()->id;
            $happyhours = \VanguardLTE\HappyHourUser::select('happyhour_users.*')->whereIn('admin_id', $availableUsers)->orderBy('happyhour_users.created_at', 'DESC');
            $happyhours = $happyhours->paginate(10);
            return view('backend.argon.happyhour.list', compact('happyhours'));
        }
        public function create()
        {
            return view('backend.argon.happyhour.add');
        }
        public function store(\Illuminate\Http\Request $request)
        {
            $username = $request->username;
            if (!$username)
            {
                return redirect()->back()->withErrors('유저이름을 입력하세요');
            }
            $availableUsers = auth()->user()->availableUsers();
            $user = \VanguardLTE\User::where('username', $username)->first();
            if (!$user || !in_array($user->id, $availableUsers))
            {
                return redirect()->back()->withErrors('유저가 없습니다.');
            }
            $uniq = \VanguardLTE\HappyHourUser::where([
                // 'time' => $request->time, 
                'user_id' => $user->id,
                'status' => 1
            ])->count();
            if( $uniq ) 
            {
                return redirect()->back()->withErrors('유저 콜이 존재합니다');
            }
            $data = $request->only([
                'total_bank', 
                'jackpot',
                'time', 
                'status'
            ]);
            $bank = abs($data['total_bank']);
            $data['current_bank'] = $bank;
            $data['user_id'] = $user->id;
            $data['over_bank'] = 0;
            if (isset($data['jackpot']) && $data['jackpot'] > 0)
            {
                $data['progressive'] = mt_rand(2,5);
            }
            $data['admin_id'] = auth()->user()->id;
            if (!auth()->user()->hasRole('admin'))
            {
                if (auth()->user()->balance < $data['total_bank'])
                {
                    return redirect()->back()->withErrors('보유금이 충분하지 않습니다.');
                }

                $admin = \VanguardLTE\User::where('role_id', 9)->first();
                if (!$admin)
                {
                    return redirect()->back()->withErrors('내부오류');
                }

                auth()->user()->addBalance('out', $bank, $admin, 0, null, '콜 생성');

            }
            $happyhour = \VanguardLTE\HappyHourUser::create($data);
            event(new \VanguardLTE\Events\Jackpot\NewJackpot($happyhour));
            return redirect()->to(argon_route('argon.happyhour.list'))->withSuccess(['콜이 생성되었습니다']);
        }
        public function edit(\Illuminate\Http\Request $request)
        {
            $happyhour = $request->id;
            $happyhour = \VanguardLTE\HappyHourUser::where('id', $happyhour)->first();
            return view('backend.argon.happyhour.edit', compact('happyhour'));
        }
        public function update(\Illuminate\Http\Request $request)
        {
            $happyhour = $request->id;
            $happyhour = \VanguardLTE\HappyHourUser::where('id', $happyhour)->first();
            $username = $request->username;
            if (!$username)
            {
                return redirect()->back()->withErrors('유저이름을 입력하세요');
            }
            $user = \VanguardLTE\User::where('username', $username)->first();
            if (!$user)
            {
                return redirect()->back()->withErrors('유저가 없습니다.');
            }
            $data = $request->only([
                'total_bank', 
                'jackpot',
                'time', 
                'status'
            ]);
            $uniq = \VanguardLTE\HappyHourUser::where([
                // 'time' => $request->time, 
                'user_id' => $user->id
            ])->where('id', '<>', $happyhour->id)->count();
            if( $uniq ) 
            {
                return redirect()->back()->withErrors(['유저 콜이 존재합니다']);
            }
            $data['current_bank'] = $data['total_bank'];
            $data['user_id'] = $user->id;
            $data['over_bank'] = 0;
            if ($data['jackpot'] > 0)
            {
                $data['progressive'] = mt_rand(2,5);
            }
            $data['admin_id'] = auth()->user()->id;
            $happyhour->update($data);
            return redirect()->to(argon_route('argon.happyhour.list'))->withSuccess(['유저콜이 업데이트되었습니다']);
        }
        public function delete(\Illuminate\Http\Request $request)
        {
            /*if( !in_array($happyhour->shop_id, auth()->user()->availableShops()) ) 
            {
                return redirect()->back()->withErrors([trans('app.wrong_shop')]);
            } */
            $happyhour = $request->id;
            $jp = \VanguardLTE\HappyHourUser::where('id', $happyhour)->first();
            $availableUsers = auth()->user()->hierarchyUsers();
            $availableUsers[] = auth()->user()->id;
            if (!in_array($jp->user_id, $availableUsers) || !in_array($jp->admin_id, $availableUsers))
            {
                return redirect()->back()->withErrors('비정상적인 접근입니다');
            }

            $admin = \VanguardLTE\User::where('role_id', 9)->first();
            if (!$admin)
            {
                return redirect()->back()->withErrors('내부오류');
            }

            $remainbank = $jp->current_bank;

            auth()->user()->addBalance('add', $remainbank, $admin, 0, null, '콜 완료');
            

            event(new \VanguardLTE\Events\Jackpot\DeleteJackpot($jp));
            // $jp->delete();
            $jp->update(['status' => 2, 'updated_at' => \Carbon\Carbon::now()]);
            return redirect()->to(argon_route('argon.happyhour.list'))->withSuccess(['유저콜이 종료되었습니다']);
        }

    }

}

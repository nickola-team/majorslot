<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend\Argon
{
    class HappyHourController extends \VanguardLTE\Http\Controllers\Controller
    {
        public function __construct()
        {
            $this->middleware('auth');
            $this->middleware('permission:access.admin.panel');
            $this->middleware('permission:happyhours.manage');
            $this->middleware('shopzero');
        }
        public function index(\Illuminate\Http\Request $request)
        {
            $happyhours = \VanguardLTE\HappyHourUser::select('happyhour_users.*')->where('admin_id', auth()->user()->id)->orderBy('happyhour_users.created_at', 'DESC');
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
                return redirect()->to(argon_route('argon.happyhour.create'))->withErrors('유저이름을 입력하세요');
            }
            $user = \VanguardLTE\User::where('username', $username)->first();
            if (!$user)
            {
                return redirect()->to(argon_route('argon.happyhour.create'))->withErrors('유저가 없습니다.');
            }
            $uniq = \VanguardLTE\HappyHourUser::where([
                // 'time' => $request->time, 
                'user_id' => $user->id
            ])->count();
            if( $uniq ) 
            {
                return redirect()->back()->withErrors(['유저 콜이 존재합니다']);
            }
            $data = $request->only([
                'total_bank', 
                'jackpot',
                'time', 
                'status'
            ]);
            $data['current_bank'] = $data['total_bank'];
            $data['user_id'] = $user->id;
            $data['over_bank'] = 0;
            if ($data['jackpot'] > 0)
            {
                $data['progressive'] = mt_rand(2,5);
            }
            $data['admin_id'] = auth()->user()->id;
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
            event(new \VanguardLTE\Events\Jackpot\DeleteJackpot($jp));
            // $jp->delete();
            $jp->update(['status' => 2]);
            return redirect()->to(argon_route('argon.happyhour.list'))->withSuccess(['유저콜이 삭제되었습니다']);
        }

    }

}

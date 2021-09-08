<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend
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
            $happyhours = \VanguardLTE\HappyHourUser::select('happyhour_users.*')->where('admin_id', auth()->user()->id)->orderBy('happyhour_users.created_at', 'DESC')->get();
            return view('backend.Default.happyhours.list', compact('happyhours'));
        }
        public function create()
        {
            return view('backend.Default.happyhours.add');
        }
        public function store(\Illuminate\Http\Request $request)
        {
            $username = $request->username;
            if (!$username)
            {
                return redirect()->route(config('app.admurl').'.happyhour.create')->withErrors('회원이름을 입력하세요');
            }
            $user = \VanguardLTE\User::where('username', $username)->first();
            if (!$user)
            {
                return redirect()->route(config('app.admurl').'.happyhour.create')->withErrors('회원이 없습니다.');
            }
            $uniq = \VanguardLTE\HappyHourUser::where([
                'time' => $request->time, 
                'user_id' => $user->id
            ])->count();
            if( $uniq ) 
            {
                return redirect()->route(config('app.admurl').'.happyhour.list')->withErrors(trans('validation.unique', ['attribute' => 'time']));
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
            return redirect()->route(config('app.admurl').'.happyhour.list')->withSuccess(trans('app.happyhour_created'));
        }
        public function edit($happyhour)
        {
            $happyhour = \VanguardLTE\HappyHourUser::where('id', $happyhour)->first();
            return view('backend.Default.happyhours.edit', compact('happyhour'));
        }
        public function update(\Illuminate\Http\Request $request, \VanguardLTE\HappyHourUser $happyhour)
        {
            $username = $request->username;
            if (!$username)
            {
                return redirect()->route(config('app.admurl').'.happyhour.list')->withErrors('회원이름을 입력하세요');
            }
            $user = \VanguardLTE\User::where('username', $username)->first();
            if (!$user)
            {
                return redirect()->route(config('app.admurl').'.happyhour.list')->withErrors('회원이 없습니다.');
            }
            $data = $request->only([
                'total_bank', 
                'jackpot',
                'time', 
                'status'
            ]);
            $uniq = \VanguardLTE\HappyHourUser::where([
                'time' => $request->time, 
                'user_id' => $user->id
            ])->where('id', '!=', $happyhour->id)->count();
            if( $uniq ) 
            {
                return redirect()->route(config('app.admurl').'.happyhour.list')->withErrors(trans('validation.unique', ['attribute' => 'time']));
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
            return redirect()->route(config('app.admurl').'.happyhour.list')->withSuccess(trans('app.happyhour_updated'));
        }
        public function delete(\VanguardLTE\HappyHourUser $happyhour)
        {
            /*if( !in_array($happyhour->shop_id, auth()->user()->availableShops()) ) 
            {
                return redirect()->back()->withErrors([trans('app.wrong_shop')]);
            } */
            \VanguardLTE\HappyHourUser::where('id', $happyhour->id)->delete();
            return redirect()->route(config('app.admurl').'.happyhour.list')->withSuccess(trans('app.happyhour_deleted'));
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

<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend
{
    class NoticesController extends \VanguardLTE\Http\Controllers\Controller
    {
        public function __construct()
        {
            $this->middleware('auth');
            $this->middleware( function($request,$next) {
                    if (!auth()->user()->isInoutPartner())
                    {
                        return response('허용되지 않은 접근입니다.', 401);
                    }
                    return $next($request);
                }
            );
        }
        public function index(\Illuminate\Http\Request $request)
        {
            if (auth()->user()->hasRole('admin'))
            {
                $notices = \VanguardLTE\Notice::all();
            }
            else 
            {
                $notices = \VanguardLTE\Notice::where('user_id', auth()->user()->id)->get();
            }
            return view('backend.Default.notices.list', compact('notices'));
        }
        public function create()
        {
            return view('backend.Default.notices.add');
        }
        public function store(\Illuminate\Http\Request $request)
        {
            $data = $request->only([
                'title',
                'content',
                'type',
                'active',
            ]);
            if ($request->user != '')
            {
                $writer = \VanguardLTE\User::where('username', $request->user)->first();
                if (!$writer)
                {
                    return redirect()->back()->withErrors('작성자를 찾을수 없습니다.');
                }
                $data['user_id'] = $writer->id;
            }
            else
            {
                $data['user_id'] = auth()->user()->id;
            }
            \VanguardLTE\Notice::create($data);
            return redirect()->route(config('app.admurl').'.notice.list')->withSuccess('공지가 추가되었습니다');
        }
        public function massadd(\Illuminate\Http\Request $request)
        {
            $shop = \VanguardLTE\Shop::find(\Auth::user()->shop_id);
            if( !$shop ) 
            {
                return redirect()->back()->withErrors([trans('app.wrong_shop')]);
            }
            if( !$request->nominal || $request->nominal <= 0 ) 
            {
                return redirect()->back()->withErrors([trans('app.wrong_sum')]);
            }
            if( !auth()->user()->hasRole('cashier') ) 
            {
                return redirect()->back()->withErrors([trans('app.only_for_cashiers')]);
            }
            $open_shift = \VanguardLTE\OpenShift::where([
                'shop_id' => \Auth::user()->shop_id, 
                'end_date' => null
            ])->first();
            if( !$open_shift ) 
            {
                return redirect()->back()->withErrors([trans('app.shift_not_opened')]);
            }
            if( $shop->balance < ($request->nominal * $request->count) ) 
            {
                return redirect()->back()->withErrors([trans('app.not_enough_money_in_the_shop', [
                    'name' => $shop->name, 
                    'balance' => $shop->balance
                ])]);
            }
            if( isset($request->count) && is_numeric($request->count) && isset($request->nominal) && is_numeric($request->nominal) ) 
            {
                $shop->update(['balance' => $shop->balance - ($request->nominal * $request->count)]);
                $open_shift->increment('balance_out', $request->nominal * $request->count);
                $open_shift->increment('money_in', $request->nominal * $request->count);
                for( $i = 0; $i < $request->count; $i++ ) 
                {
                    $pincode = '';
                    $nonUniq = true;
                    while( $nonUniq ) 
                    {
                        $str = md5(rand(100000, 999999));
                        $pincode = mb_strtoupper(implode('-', [
                            substr($str, 0, 4), 
                            substr($str, 4, 4), 
                            substr($str, 8, 4), 
                            substr($str, 12, 4), 
                            substr($str, 16, 4)
                        ]));
                        $nonUniq = \VanguardLTE\Pincode::where('code', $pincode)->count();
                    }
                    $data = [
                        'code' => $pincode, 
                        'nominal' => $request->nominal, 
                        'status' => $request->status, 
                        'shop_id' => auth()->user()->shop_id
                    ];
                    \VanguardLTE\Pincode::create($data);
                }
            }
            return redirect()->route(config('app.admurl').'.pincode.list')->withSuccess(trans('app.pincode_created'));
        }
        public function edit($notice)
        {
            $notice = \VanguardLTE\Notice::where([
                'id' => $notice])->firstOrFail();
            return view('backend.Default.notices.edit', compact('notice'));
        }
        public function update(\Illuminate\Http\Request $request, \VanguardLTE\Notice $notice)
        {
            $data = $request->only([
                'title',
                'content',
                'type',
                'active',
            ]);
            if ($request->user != '')
            {
                $writer = \VanguardLTE\User::where('username', $request->user)->first();
                if (!$writer)
                {
                    return redirect()->back()->withErrors('작성자를 찾을수 없습니다.');
                }
                $data['user_id'] = $writer->id;
            }
            else
            {
                $data['user_id'] = auth()->user()->id;
            }

            \VanguardLTE\Notice::where('id', $notice->id)->update($data);
            return redirect()->route(config('app.admurl').'.notice.list')->withSuccess('공지가 업데이트되었습니다.');
        }
        public function delete(\VanguardLTE\Notice $notice)
        {
            \VanguardLTE\Notice::where('id', $notice->id)->delete();
            return redirect()->route(config('app.admurl').'.notice.list')->withSuccess('공지가 삭제되었습니다.');
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

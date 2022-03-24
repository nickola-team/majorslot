<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend\Argon
{
    class NoticesController extends \VanguardLTE\Http\Controllers\Controller
    {
        public function __construct()
        {
            $this->middleware('auth');
            $this->middleware('permission:access.admin.panel');
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
            return view('backend.argon.notices.list', compact('notices'));
        }
        public function create()
        {
            return view('backend.argon.notices.add');
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
            return redirect()->to(argon_route('argon.notice.list'))->withSuccess(['공지가 추가되었습니다']);
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
            return redirect()->to(argon_route('argon.pincode.list'))->withSuccess([trans('app.pincode_created')]);
        }
        public function edit(\Illuminate\Http\Request $request, $argon, $notice)
        {
            $notice = \VanguardLTE\Notice::where([
                'id' => $notice])->firstOrFail();
            return view('backend.argon.notices.edit', compact('notice'));
        }
        public function update(\Illuminate\Http\Request $request, $argon, $notice)
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

            \VanguardLTE\Notice::where('id', $notice)->update($data);
            return redirect()->to(argon_route('argon.notice.list'))->withSuccess(['공지가 업데이트되었습니다.']);
        }
        public function delete(\Illuminate\Http\Request $request, $argon, $notice)
        {
            \VanguardLTE\Notice::where('id', $notice)->delete();
            return redirect()->to(argon_route('argon.notice.list'))->withSuccess(['공지가 삭제되었습니다.']);
        }
    }

}
namespace 
{
    function onkXppk3PRSZPackRnkDOJaZ9()
    {
        return 'OkBM2iHjbd6FHZjtvLpNHOc3lslbxTJP6cqXsMdE4evvckFTgS';
    }

}

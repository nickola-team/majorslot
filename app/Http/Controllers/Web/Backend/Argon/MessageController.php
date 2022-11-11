<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend\Argon
{
    class MessageController extends \VanguardLTE\Http\Controllers\Controller
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
                $msgs = \VanguardLTE\Message::all();
            }
            else if (auth()->user()->isInoutPartner())
            {
                $msgs = \VanguardLTE\Message::where('writer_id', auth()->user()->id)->orWhere('user_id', auth()->user()->id)->get();
            }
            else
            {
                $msgs = \VanguardLTE\Message::where('user_id', auth()->user()->id)->get();
            }
            $odd = \VanguardLTE\Settings::where('key', 'MaxOdd')->first();
            $win = \VanguardLTE\Settings::where('key', 'MaxWin')->first();
            $data = [
                'MaxOdd' => $odd?$odd->value:300,
                'MaxWin' => $win?$win->value:5000000
            ];
            return view('backend.argon.messages.list', compact('msgs','data'));
        }
        public function create()
        {
            return view('backend.argon.messages.add');
        }
        public function store(\Illuminate\Http\Request $request)
        {
            $data = $request->only([
                'title',
                'content',
            ]);
            if ($request->user != '')
            {
                $user = \VanguardLTE\User::where('username', $request->user)->first();
                if (!$user)
                {
                    return redirect()->back()->withErrors('발송할 회원을 찾을수 없습니다.');
                }
                $data['user_id'] = $user->id;
            }
            else
            {
                $data['user_id'] = 0;
            }
            $data['writer_id'] = auth()->user()->id;
            \VanguardLTE\Message::create($data);
            return redirect()->to(argon_route('argon.msg.list'))->withSuccess(['쪽지가 발송되었습니다']);
        }
        public function delete(\Illuminate\Http\Request $request, $argon, $message)
        {
            $msg = \VanguardLTE\Message::where('id', $message)->first();
            if ($msg && $msg->user_id != 0){
                $msg->delete();
            }
            return redirect()->to(argon_route('argon.msg.list'))->withSuccess(['쪽지가 삭제되었습니다']);
        }

        public function deleteall(\Illuminate\Http\Request $request)
        {
            if (auth()->user()->hasRole('admin'))
            {
                $msgs = \VanguardLTE\Message::query()->delete();
            }
            else if (auth()->user()->isInoutPartner())
            {
                $msgs = \VanguardLTE\Message::where('writer_id', auth()->user()->id)->orwhere('user_id', auth()->user()->id)->delete();
            }
            else
            {
                $msgs = \VanguardLTE\Message::where('user_id', auth()->user()->id)->delete();
            }
            return redirect()->to(argon_route('argon.msg.list'))->withSuccess(['모든 쪽지가 삭제되었습니다']);
        }

        public function updatemonitor(\Illuminate\Http\Request $request)
        {
            $max_odd = $request->max_odd;
            $max_win = $request->max_win;
            $odd = \VanguardLTE\Settings::where('key', 'MaxOdd')->first();
            if ($odd)
            {
                $odd->update(['value' => $max_odd]);
            }
            else
            {
                \VanguardLTE\Settings::create(
                    [
                        'key' => 'MaxOdd',
                        'value' => $max_odd
                    ]
                );
            }
            $win = \VanguardLTE\Settings::where('key', 'MaxWin')->first();
            if ($win)
            {
                $win->update(['value' => $max_win]);
            }
            else
            {
                \VanguardLTE\Settings::create(
                    [
                        'key' => 'MaxWin',
                        'value' => $max_win
                    ]
                );
            }
            
            return redirect()->to(argon_route('argon.msg.list'))->withSuccess(['알림설정이 업데이트되었습니다']);
        }
        
    }

}

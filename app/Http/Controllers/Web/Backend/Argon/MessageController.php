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
            else 
            {
                $msgs = \VanguardLTE\Message::where('writer_id', auth()->user()->id)->get();
            }
            return view('backend.argon.messages.list', compact('msgs'));
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
                if (!$user || !$user->hasRole('user'))
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
    }

}

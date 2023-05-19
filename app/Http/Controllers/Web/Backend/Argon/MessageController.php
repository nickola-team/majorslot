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
            $type = $request->type;
            if (auth()->user()->hasRole('admin'))
            {
                $msgs = \VanguardLTE\Message::orderBy('created_at','desc')->where('ref_id', 0);
            }
            // else if (auth()->user()->hasRole('group'))
            // {
            //     $comasters = auth()->user()->childPartners();
            //     $msgs = \VanguardLTE\Message::whereIn('writer_id', $comasters)->orWhereIn('user_id', $comasters)->orderBy('created_at','desc');
            // }
            else //if (auth()->user()->hasRole('comaster'))
            {
                $msgs = \VanguardLTE\Message::where(function ($query) {
                    $query->where('writer_id','=', auth()->user()->id)->orWhere('user_id','=', auth()->user()->id);
                })->where(function ($query) {
                    $query->where('ref_id','=', 0);
                })->orderBy('created_at','desc');
            }
            // else
            // {
            //     $msgs = \VanguardLTE\Message::where('user_id', auth()->user()->id)->get();
            // }
            $msgs = $msgs->where('type', $type);
            $odd = \VanguardLTE\Settings::where('key', 'MaxOdd')->first();
            $win = \VanguardLTE\Settings::where('key', 'MaxWin')->first();
            $data = [
                'MaxOdd' => $odd?$odd->value:300,
                'MaxWin' => $win?$win->value:5000000
            ];
            $msgs = $msgs->paginate(20);
            return view('backend.argon.messages.list', compact('msgs','data'));
        }
        public function create(\Illuminate\Http\Request $request)
        {
            $ref = $request->ref;
            $refmsg = null;
            if ($ref != '')
            {
                $refmsg = \VanguardLTE\Message::where('id', $ref)->first();
                $availableUsers = auth()->user()->availableUsers();
                if ($refmsg==null || !in_array($refmsg->writer_id, $availableUsers))
                {
                    return redirect()->back()->withErrors(['수신자를 찾을수 없습니다']);
                }
            }
            $msgtemps = \VanguardLTE\MsgTemp::where('writer_id', auth()->user()->id)->orderBy('order','desc')->get();
            return view('backend.argon.messages.add', compact('msgtemps','refmsg'));
        }
        public function store(\Illuminate\Http\Request $request)
        {
            $data = $request->only([
                'title',
                'content',
                'ref_id',
                'type'
            ]);
            if ($request->user != '')
            {
                $availableUsers = auth()->user()->availableUsers();
                $user = \VanguardLTE\User::where('username', $request->user)->first();
                if (!$user || !in_array($user->id, $availableUsers))
                {
                    return redirect()->back()->withErrors('발송할 회원을 찾을수 없습니다.');
                }
                $data['user_id'] = $user->id;
            }
            else if (auth()->user()->isInoutPartner())
            {
                $data['user_id'] = \VanguardLTE\Message::GROUP_MSG_ID;
            }
            else // 일반 파트너들이 본사에게 발송
            {
                $master = auth()->user();
                while ($master !=null && !$master->isInoutPartner())
                {
                    $master = $master->referral;
                }

                $data['user_id'] = $master->id;
            }
            $data['writer_id'] = auth()->user()->id;
            \VanguardLTE\Message::create($data);
            return redirect()->to(argon_route('argon.msg.list', ['type' => $data['type']]))->withSuccess(['쪽지가 발송되었습니다']);
        }
        public function delete(\Illuminate\Http\Request $request, $argon, $message)
        {
            $msg = \VanguardLTE\Message::where('id', $message)->first();
            if ($msg && $msg->user_id != 0){
                $msg->delete();
            }
            \VanguardLTE\Message::where('ref_id', $message)->delete();
            return redirect()->back()->withSuccess(['쪽지가 삭제되었습니다']);
        }

        public function deleteall(\Illuminate\Http\Request $request)
        {
            $type = 0;
            if ($request->type != null)
            {
                $type = $request->type;
            }
            if (auth()->user()->hasRole('admin'))
            {
                $msgs = \VanguardLTE\Message::where('type',$type)->delete();
            }
            else
            {
                $availableUsers = auth()->user()->availableUsers();
                $msgs = \VanguardLTE\Message::where('type',$type)->whereIn('writer_id', $availableUsers)->delete();
                // return redirect()->back()->withSuccess(['현재 지원되지 않는 기능입니다']);
            }
            
            return redirect()->back()->withSuccess(['모든 쪽지가 삭제되었습니다']);
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
            
            return redirect()->back()->withSuccess(['알림설정이 업데이트되었습니다']);
        }
        
    }

}

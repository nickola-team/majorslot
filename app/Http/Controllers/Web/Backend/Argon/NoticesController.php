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
            else if (auth()->user()->hasRole('group'))
            {
                $comasters = auth()->user()->childPartners();
                $notices = \VanguardLTE\Notice::whereIn('user_id', $comasters)->get();
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
                'popup',
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
                'popup',
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

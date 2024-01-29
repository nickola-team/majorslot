<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend\Argon
{
    class MsgTempController extends \VanguardLTE\Http\Controllers\Controller
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
                $msgtemps = \VanguardLTE\MsgTemp::orderBy('order','desc');
            }
            else 
            {
                $msgtemps = \VanguardLTE\MsgTemp::where('writer_id', auth()->user()->id)->orderBy('order','desc');
            }
            $msgtemps = $msgtemps->paginate(20);
            return view('backend.argon.msgtemp.list', compact('msgtemps'));
        }
        public function create()
        {
            return view('backend.argon.msgtemp.add');
        }
        public function store(\Illuminate\Http\Request $request)
        {
            $data = $request->only([
                'title',
                'content',
                'order',
            ]);
            $data['writer_id'] = auth()->user()->id;
            \VanguardLTE\MsgTemp::create($data);
            return redirect()->to(argon_route('argon.msgtemp.list'))->withSuccess(['템플릿이 추가되었습니다']);
        }
        public function edit(\Illuminate\Http\Request $request, $argon, $msgtemp)
        {
            $msgtemp = \VanguardLTE\MsgTemp::where([
                'id' => $msgtemp])->firstOrFail();
            return view('backend.argon.msgtemp.edit', compact('msgtemp'));
        }
        public function update(\Illuminate\Http\Request $request, $argon, $msgtemp)
        {
            $data = $request->only([
                'title',
                'content',
                'order',
            ]);
            $data['writer_id'] = auth()->user()->id;

            \VanguardLTE\MsgTemp::where('id', $msgtemp)->update($data);
            return redirect()->to(argon_route('argon.msgtemp.list'))->withSuccess(['템플릿이 업데이트되었습니다.']);
        }
        public function delete(\Illuminate\Http\Request $request, $argon, $msgtemp)
        {
            \VanguardLTE\MsgTemp::where('id', $msgtemp)->delete();
            return redirect()->to(argon_route('argon.msgtemp.list'))->withSuccess(['템플릿이 삭제되었습니다.']);
        }
    }

}

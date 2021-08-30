<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend
{
    class WebsitesController extends \VanguardLTE\Http\Controllers\Controller
    {
        public function __construct()
        {
            $this->middleware('auth');
        }
        public function index(\Illuminate\Http\Request $request)
        {
            $websites = \VanguardLTE\WebSite::all();
            return view('backend.Default.websites.list', compact('websites'));
        }
        public function create()
        {
            foreach( glob(public_path() . '/frontend/*', GLOB_ONLYDIR) as $fileinfo ) 
            {
                $dirname = basename($fileinfo);
                $frontends[$dirname] = $dirname;
            }

            foreach( glob(resource_path() . '/views/backend/Default/layouts/*', GLOB_ONLYDIR) as $fileinfo ) 
            {
                $dirname = basename($fileinfo);
                $backends[$dirname] = $dirname;
            }
            return view('backend.Default.websites.add',compact('frontends','backends'));
        }
        public function store(\Illuminate\Http\Request $request)
        {
            $data = $request->only([
                'domain',
                'title',
                'frontend',
                'backend',
            ]);
            if ($request->admin != '')
            {
                $admin = \VanguardLTE\User::where('username', $request->admin)->first();
                if (!$admin)
                {
                    return redirect()->back()->withErrors('총본사를 찾을수 없습니다.');
                }
                $data['adminid'] = $admin->id;
            }
            else
            {
                return redirect()->back()->withErrors('총본사를 입력하세요.');
            }
            \VanguardLTE\WebSite::create($data);
            return redirect()->route('backend.website.list')->withSuccess('도메인이 추가되었습니다');
        }
        public function edit($website)
        {
            foreach( glob(public_path() . '/frontend/*', GLOB_ONLYDIR) as $fileinfo ) 
            {
                $dirname = basename($fileinfo);
                $frontends[$dirname] = $dirname;
            }

            foreach( glob(resource_path() . '/views/backend/Default/layouts/*', GLOB_ONLYDIR) as $fileinfo ) 
            {
                $dirname = basename($fileinfo);
                $backends[$dirname] = $dirname;
            }


            $website = \VanguardLTE\WebSite::where([
                'id' => $website])->firstOrFail();
            return view('backend.Default.websites.edit', compact('website','frontends','backends'));
        }
        public function update(\Illuminate\Http\Request $request, \VanguardLTE\WebSite $website)
        {
            $data = $request->only([
                'domain',
                'title',
                'frontend',
                'backend',
            ]);
            if ($request->admin != '')
            {
                $admin = \VanguardLTE\User::where('username', $request->admin)->first();
                if (!$admin)
                {
                    return redirect()->back()->withErrors('총본사를 찾을수 없습니다.');
                }
                $data['adminid'] = $admin->id;
            }
            else
            {
                return redirect()->back()->withErrors('총본사를 입력하세요.');
            }

            \VanguardLTE\WebSite::where('id', $website->id)->update($data);
            return redirect()->route('backend.website.list')->withSuccess('도메인이 업데이트되었습니다.');
        }
        public function delete(\VanguardLTE\Notice $notice)
        {
            \VanguardLTE\Notice::where('id', $notice->id)->delete();
            return redirect()->route('backend.notice.list')->withSuccess('공지가 삭제되었습니다.');
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

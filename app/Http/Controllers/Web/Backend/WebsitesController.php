<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend
{
    class WebsitesController extends \VanguardLTE\Http\Controllers\Controller
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
            $site = \VanguardLTE\WebSite::create($data);
            //create categories
            $categories = \VanguardLTE\Category::where([
                'shop_id' => 0, 
                'parent' => 0,
                'site_id' => 0,
            ])->get();
            if( count($categories) ) 
            {
                foreach( $categories as $category ) 
                {
                    $newCategory = $category->replicate();
                    $newCategory->site_id = $site->id;
                    $newCategory->save();
                }
            }

            return redirect()->route(config('app.admurl').'.website.list')->withSuccess('도메인이 추가되었습니다');
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
            return redirect()->route(config('app.admurl').'.website.list')->withSuccess('도메인이 업데이트되었습니다.');
        }
        public function delete(\VanguardLTE\WebSite $website)
        {
            \VanguardLTE\WebSite::where('id', $website->id)->delete();
            //create categories
            \VanguardLTE\Category::where([
                'shop_id' => 0,
                'parent' => 0,
                'site_id' => $website->id,
            ])->delete();
            return redirect()->route(config('app.admurl').'.website.list')->withSuccess('도메인이 삭제되었습니다.');
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

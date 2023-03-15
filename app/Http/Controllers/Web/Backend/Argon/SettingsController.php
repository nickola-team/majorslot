<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend\Argon
{
    class SettingsController extends \VanguardLTE\Http\Controllers\Controller
    {
        private $activities = null;
        public function __construct(\VanguardLTE\Repositories\Activity\ActivityRepository $activities)
        {
            $this->middleware('auth');
            $this->middleware('permission:access.admin.panel');
            $this->middleware('permission:users.activity');
            $this->activities = $activities;
        }
        public function index(\Illuminate\Http\Request $request)
        {
            $websites = \VanguardLTE\WebSite::all();
            return view('backend.argon.setting.list', compact('websites'));
        }
        public function create()
        {
            foreach( glob(resource_path() . '/views/frontend/*', GLOB_ONLYDIR) as $fileinfo ) 
            {
                $dirname = basename($fileinfo);
                $frontends[$dirname] = $dirname;
            }

            // foreach( glob(resource_path() . '/views/backend/argon/layouts/*', GLOB_ONLYDIR) as $fileinfo ) 
            // {
            //     $dirname = basename($fileinfo);
            //     $backends[$dirname] = $dirname;
            // }
            return view('backend.argon.setting.add',compact('frontends'));
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
            // create categories
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
                    $categories_2 = \VanguardLTE\Category::where([
                        'shop_id' => 0, 
                        'parent' => $category->id
                    ])->get();
                    if( count($categories_2) ) 
                    {
                        foreach( $categories_2 as $category_2 ) 
                        {
                            $newCategory_2 = $category_2->replicate();
                            $newCategory_2->site_id = $site->id;
                            $newCategory_2->parent = $newCategory->id;
                            $newCategory_2->save();
                        }
                    }
                }
            }
            return redirect()->to(argon_route('argon.website.list'))->withSuccess(['도메인이 추가되었습니다.']);
        }
        public function edit(\Illuminate\Http\Request $request, $argon, $website)
        {
            foreach( glob(resource_path() . '/views/frontend/*', GLOB_ONLYDIR) as $fileinfo ) 
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
            return view('backend.argon.setting.edit', compact('website','frontends','backends'));
        }
        public function update(\Illuminate\Http\Request $request, $argon, $website)
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

            \VanguardLTE\WebSite::where('id', $website)->update($data);
            return redirect()->to(argon_route('argon.website.list'))->withSuccess(['도메인이 업데이트되었습니다.']);
        }
        public function delete(\Illuminate\Http\Request $request, $argon, $website)
        {
            \VanguardLTE\WebSite::where('id', $website)->delete();
            //create categories
            \VanguardLTE\Category::where([
                'shop_id' => 0,
                'site_id' => $website,
            ])->delete();
            return redirect()->to(argon_route('argon.website.list'))->withSuccess(['도메인이 삭제되었습니다.']);
        }
        public function activity_index(\Illuminate\Http\Request $request)
        {
            $perPage = 20;
            $adminView = true;
            $shops = auth()->user()->availableShops();
            $ids = auth()->user()->availableUsers();
            $activities = \VanguardLTE\UserActivity::select('user_activity.*')->orderBy('created_at', 'DESC');
            if( $request->search != '' ) 
            {
                $activities = $activities->where('description', 'like', '%' . $request->search . '%');
            }
            if( $request->ip != '' ) 
            {
                $activities = $activities->where('ip_address', 'like', '%' . $request->ip . '%');
            }
            
            $activities = $activities->whereIn('shop_id', $shops);
            if( $request->username != '' ) 
            {
                $user = \VanguardLTE\User::where('username', 'like', '%'.$request->username.'%')->first();
                if ($user){
                    $ids = [$user->id];
                }
            }
            if( count($ids) ) 
            {
                $activities = $activities->whereIn('user_id', $ids);
            }
            $activities = $activities->paginate($perPage);
            return view('backend.argon.setting.activity', compact('activities', 'adminView'));
        }
        public function userActivity(\Illuminate\Http\Request $request, $argon, $user)
        {
            $perPage = 20;
            $adminView = true;
            if( !auth()->user()->isAvailable($user) ) 
            {
                return redirect()->back()->withErrors([trans('app.wrong_user')]);
            }
            $activities = $this->activities->paginateActivities($perPage, $request->get('search'), [$user->id]);
            return view('backend.argon.setting.activity', compact('activities', 'user', 'adminView'));
        }
        public function activity_clear(\Illuminate\Http\Request $request)
        {
            if( auth()->user()->hasRole('admin') ) 
            {
                \VanguardLTE\UserActivity::where('id', '>', '0')->delete();
                return redirect()->back()->withSuccess(trans('app.logs_removed'));
            }
            else
            {
                return redirect()->back()->withErrors([trans('app.no_permission')]);
            }
        }
        public function system_values(\Illuminate\Http\Request $request)
        {
            if( !auth()->user()->hasRole('admin') ) 
            {
                return redirect()->back()->withErrors([trans('app.no_permission')]);
            }
            $serverstat = server_stat();
            $bnnmoney = -1;
            $kuzanmoney = -1;
            $ktennmoney = -1;
            try
            {
                $bnnmoney = \VanguardLTE\Http\Controllers\Web\GameProviders\BNNController::getAgentBalance();
                $kuzanmoney = \VanguardLTE\Http\Controllers\Web\GameProviders\KUZAController::getAgentBalance();
                $ktennmoney = \VanguardLTE\Http\Controllers\Web\GameProviders\KTENController::getAgentBalance();

            }
            catch (\Exception $e)
            {

            }
            $agents = [
                'bnn' => $bnnmoney,
                'kuza' => $kuzanmoney,
                'kten' => $ktennmoney
            ];
            $strinternallog = '';
            $filesize = 0;
            $laravel = storage_path('logs/laravel.log');
            if ( file_exists($laravel) )
            {
                $filesize = filesize($laravel);
                $operating_system = PHP_OS_FAMILY;
                if ($operating_system === 'Windows') {
                    $strinternallog = file_get_contents($laravel);
                }
                else
                {
                    $strinternallog = `tail -100 $laravel`;
                }

            }
            else
            {
                $strinternallog = 'Could not find log file';
            }
            
            return view('backend.argon.setting.system', compact('serverstat','agents','strinternallog', 'filesize'));
        }
        public function logreset(\Illuminate\Http\Request $request)
        {
            $laravel = storage_path('logs/laravel.log');
            if (file_exists($laravel)) {     // Make sure we don't create the file
                $fp = fopen($laravel, 'w');  // Sets the file size to zero bytes
                fclose($fp);
            }
            return redirect()->back()->withSuccess(['로그파일을 리셋하였습니다']);
        }

        public function xmx_withdrawall(\Illuminate\Http\Request $request)
        {
            set_time_limit(0);
            exec('nohup php '. base_path() .'/artisan xmx:withdrawAll > /dev/null &');
            exec('nohup php '. base_path() .'/artisan game:withdrawAll > /dev/null &');
            return redirect()->back()->withSuccess(['게임사 머니회수 스케줄을 작동하였습니다']);
        }

    }

}

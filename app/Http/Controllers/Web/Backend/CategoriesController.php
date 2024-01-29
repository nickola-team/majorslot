<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend
{
    class CategoriesController extends \VanguardLTE\Http\Controllers\Controller
    {
        public function __construct()
        {
            $this->middleware('auth');
        }
        public function index(\Illuminate\Http\Request $request)
        {
            $excat = ['hot', 'new', 'card','bingo','roulette', 'keno', 'novomatic','wazdan'];
            if (auth()->user()->hasRole('admin'))
            {
                $categories = \VanguardLTE\Category::where([
                    'parent' => 0, 
                    'shop_id' => 0,
                ])->whereNotIn('href',$excat)->orderBy('site_id')->orderBy('position')->get();
            }
            else
            {
                $excat[] = 'virtualtech';
                $excat[] = 'skywind';
                $siteIds = \VanguardLTE\WebSite::where('adminid', auth()->user()->id)->get()->pluck('id')->toArray();
           
                $categories = \VanguardLTE\Category::where([
                    'parent' => 0, 
                    'shop_id' => 0,
                ])->whereIn('site_id', $siteIds)->whereNotIn('href',$excat)->orderBy('site_id')->orderBy('position')->get();
                if (count($categories) == 0) // use default category
                {
                    $categories = \VanguardLTE\Category::where([
                        'parent' => 0, 
                        'shop_id' => 0,
                        'site_id' => 0
                    ])->whereNotIn('href',$excat)->orderBy('position')->get();
                }
            }
            return view('backend.Default.categories.list', compact('categories'));
        }

        public function view(\VanguardLTE\Category $category, \Illuminate\Http\Request $request)
        {
            $categories = \VanguardLTE\Category::where([
                'original_id' => $category->original_id,
                'site_id' => $category->site_id
                ]);
            $categories->update(['view' => $request->view]);
            
            return redirect()->back()->withSuccess('게임제공사상태를 변경하였습니다.');
        }


        public function create()
        {
            $categories = \VanguardLTE\Category::where([
                'parent' => 0, 
                'shop_id' => auth()->user()->shop_id
            ])->pluck('id', 'title')->toArray();
            $categories = array_merge(['Root' => 0], $categories);
            $categories = array_flip($categories);
            return view('backend.Default.categories.add', compact('categories'));
        }
        public function store(\Illuminate\Http\Request $request)
        {
            $data = $request->all();
            $data['shop_id'] = auth()->user()->shop_id;
            $category = \VanguardLTE\Category::create($data);
            return redirect()->route(config('app.admurl').'.category.list')->withSuccess(trans('app.category_created'));
        }
        public function edit($category)
        {
            $category = \VanguardLTE\Category::where('id', $category)->first();
            if( !in_array($category->shop_id, auth()->user()->availableShops()) ) 
            {
                return redirect()->route(config('app.admurl').'.category.list')->withErrors([trans('app.wrong_shop')]);
            }
            $categories = \VanguardLTE\Category::where([
                'parent' => 0, 
                'shop_id' => auth()->user()->shop_id
            ])->pluck('id', 'title')->toArray();
            $categories = array_merge(['Root' => 0], $categories);
            $categories = array_flip($categories);
            return view('backend.Default.categories.edit', compact('category', 'categories'));
        }
        public function update(\VanguardLTE\Category $category, \Illuminate\Http\Request $request)
        {
            if( !in_array($category->shop_id, auth()->user()->availableShops()) ) 
            {
                return redirect()->route(config('app.admurl').'.category.list')->withErrors([trans('app.wrong_shop')]);
            }
            $data = $request->only([
                'title', 
                'parent', 
                'position', 
                'href'
            ]);
            \VanguardLTE\Category::where('id', $category->id)->update($data);
            return redirect()->route(config('app.admurl').'.category.list')->withSuccess(trans('app.category_updated'));
        }
        public function delete(\VanguardLTE\Category $category)
        {
            if( !in_array($category->shop_id, auth()->user()->availableShops()) ) 
            {
                return redirect()->back()->withErrors([trans('app.wrong_shop')]);
            }
            \VanguardLTE\GameCategory::where('category_id', $category->id)->delete();
            $category = \VanguardLTE\Category::where('id', $category->id)->delete();
            return redirect()->route(config('app.admurl').'.category.list')->withSuccess(trans('app.category_deleted'));
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

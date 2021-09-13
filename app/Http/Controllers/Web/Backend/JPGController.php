<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend
{
    class JPGController extends \VanguardLTE\Http\Controllers\Controller
    {
        public function __construct()
        {
            $this->middleware('auth');
            $this->middleware('permission:access.admin.panel');
            $this->middleware('shopzero');
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
/*            $checked = new \VanguardLTE\Lib\LicenseDK();
            $license_notifications_array = $checked->aplVerifyLicenseDK(null, 0);
            if( $license_notifications_array['notification_case'] != 'notification_license_ok' ) 
            {
                return redirect()->route('frontend.page.error_license');
            }
            if( !$this->security() ) 
            {
                return redirect()->route('frontend.page.error_license');
            }*/
            $jackpots = \VanguardLTE\JPG::where('shop_id', auth()->user()->shop_id)->get();
            return view('backend.Default.jpg.list', compact('jackpots'));
        }
        public function create()
        {
            return view('backend.Default.jpg.add');
        }
        public function store(\Illuminate\Http\Request $request)
        {
            $request->validate(['percent' => 'required|in:' . implode(',', \VanguardLTE\JPG::$values['percent'])]);
            $data = $request->all();
            foreach( $data as &$item ) 
            {
                $item = str_replace(',', '.', $item);
            }
            $data['shop_id'] = auth()->user()->shop_id;
            $jackpot = \VanguardLTE\JPG::create($data);
            return redirect()->route(config('app.admurl').'.jpgame.list')->withSuccess(trans('app.jackpot_created'));
        }
        public function edit($jackpot)
        {
            $jackpot = \VanguardLTE\JPG::where('id', $jackpot)->first();
            if( !in_array($jackpot->shop_id, auth()->user()->availableShops()) ) 
            {
                return redirect()->back()->withErrors([trans('app.wrong_shop')]);
            }
            return view('backend.Default.jpg.edit', compact('jackpot'));
        }
        public function update(\Illuminate\Http\Request $request, \VanguardLTE\JPG $jackpot)
        {
            if( !in_array($jackpot->shop_id, auth()->user()->availableShops()) ) 
            {
                return redirect()->back()->withErrors([trans('app.wrong_shop')]);
            }
            if($request->pay_sum < $jackpot->balance && $request->pay_sum > 0) {
                return redirect()->back()->withErrors(['당첨금액이 현재금액보다 작을수 없습니다']);
            }
            if($request->pay_sum_new < $jackpot->balance && $request->pay_sum > 0) {
                return redirect()->back()->withErrors(['당첨금액이 현재금액보다 작을수 없습니다']);
            }
            if($request->start_balance > $jackpot->balance) {
                return redirect()->back()->withErrors(['시작금액이 현재금액보다 클수 없습니다']);
            }
            if($request->pay_sum > 0 && $request->start_balance > $jackpot->pay_sum) {
                return redirect()->back()->withErrors(['시작금액이 당첨금액보다 클수 없습니다']);
            }
            $request->validate(['percent' => 'required|in:' . implode(',', \VanguardLTE\JPG::$values['percent'])]);
            $data = $request->only([
                'name', 
                'balance', 
                'pay_sum', 
                'pay_sum_new', 
                'percent', 
                'start_balance', 
                'view'
            ]);
            foreach( $data as &$item ) 
            {
                $item = str_replace(',', '.', $item);
            }
            $jackpot->update($data);
            
            return redirect()->route(config('app.admurl').'.jpgame.list')->withSuccess(trans('app.jackpot_updated'));
        }
        public function balance(\Illuminate\Http\Request $request)
        {
            $data = $request->all();
            if( !array_get($data, 'type') ) 
            {
                $data['type'] = 'add';
            }
            $jackpot = \VanguardLTE\JPG::find($request->jackpot_id);
            if( !in_array($jackpot->shop_id, auth()->user()->availableShops()) ) 
            {
                return redirect()->back()->withErrors([trans('app.wrong_shop')]);
            }
            if( $request->all && $request->all == '1' ) 
            {
                $request->summ = $jackpot->balance;
            }
            $request->summ = str_replace(',','', $request->summ);
            $add_jsp = $jackpot->add_jps(auth()->user(), abs($request->summ), $request->type);
            if( !$add_jsp['success'] ) 
            {
                return redirect()->back()->withErrors([$add_jsp['text']]);
            }
            else
            {
                return redirect()->back()->withSuccess(trans('app.balance_updated'));
            }
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

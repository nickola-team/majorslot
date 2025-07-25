<?php 
namespace VanguardLTE\Http\Controllers\Web\Frontend
{
    class PagesController extends \VanguardLTE\Http\Controllers\Controller
    {
        public function new_license()
        {
            $licensed = false;
            $checked = new \VanguardLTE\Lib\LicenseDK();
            $license_notifications_array = $checked->aplVerifyLicenseDK(null, 0);
            if( $license_notifications_array['notification_case'] == 'notification_license_ok' ) 
            {
                $licensed = true;
            }
            return view('system.pages.new_license', compact('licensed'));
        }
        public function new_license_post(\Illuminate\Http\Request $request)
        {
            $email = trim($request->email);
            $code = trim($request->code);
            file_put_contents(base_path() . '/' . config('LicenseDK.APL_LICENSE_FILE_LOCATION'), '');
            $checked = new \VanguardLTE\Lib\LicenseDK();
            $license_notifications_array = $checked->aplInstallLicenseDK($request->getSchemeAndHttpHost(), $email, $code);
            if( $license_notifications_array['notification_case'] == 'notification_license_ok' ) 
            {
                return redirect()->back()->withSuccess(trans('app.license_is_already_installed'));
            }
            if( $license_notifications_array['notification_case'] == 'notification_already_installed' ) 
            {
                return redirect()->back()->withSuccess(trans('app.license_is_already_installed'));
            }
            return redirect()->back()->withErrors([$license_notifications_array['notification_text']]);
        }
        public function jpstv($id = 0)
        {
            return view('system.pages.jpstv', compact('id'));
        }
        public function jpstv_json(\Illuminate\Http\Request $request)
        {
            $jNames = [
                'diamond', 
                'platinum', 
                'gold', 
                'silver', 
                'bronze', 
                'iron'
            ];
            $jCnt = 0;
            $res = [
                'status' => 'error', 
                'content' => [], 
                'i' => 1
            ];
            $diffs = [rand(40, 50), rand(30, 40), rand(10, 20), rand(0, 10)];
            $jackpots = [0, 0, 0, 0];
            $data = \VanguardLTE\JPG::where('shop_id', $request->id)->get();
            foreach( $data as $i => $jackpot ) 
            {
                $fake_new_jp = $jackpot->fake_balance + $diffs[$i];
                if($jackpot->pay_sum_new > 0 && $fake_new_jp > $jackpot->pay_sum_new){
                    //$jackpots[$i] = $jpgs[$i]->start_balance;
                    $fake_new_jp = $jackpot->balance;
                }
                $ret = $jackpot->update(['fake_balance' => $fake_new_jp]);
                
                $res['content'][] = [
                    'name' => $jNames[$jCnt], 
                    'jackpot' => $fake_new_jp, 
                    'user' => ''
                ];
                $jCnt++;
                if( $jCnt > 3 ) 
                {
                    break;
                }
            }
            return json_encode($res);
        }
        public function error_license()
        {
            return view('system.pages.error_license');
        }
    }

}

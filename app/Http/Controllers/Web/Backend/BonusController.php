<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend
{
    class BonusController extends \VanguardLTE\Http\Controllers\Controller
    {
        public function __construct()
        {
            $this->middleware('auth');
        }
        public function pp_index(\Illuminate\Http\Request $request)
        {
            $bonuses = \VanguardLTE\PPBonus::all();
            $frbs = [];
            foreach ($bonuses as $bonus)
            {
                $res = \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::getplayersfrb($bonus->playerId);
                $bdata = $bonus->toArray();
                $bdata['username'] = $bonus->user->username;
                $bdata['roundsPlayed'] = 0;
                $bdata['status'] = 0;
                if ($res['error'] == '0')
                {
                    foreach ($res['bonuses'] as $frb)
                    {
                        if ($frb['bonusCode'] == $bonus->bonusCode)
                        {
                            $bdata['roundsPlayed'] = $frb['roundsPlayed'];
                            $bdata['rounds'] = $frb['rounds'];
                            $bdata['status'] = 1;
                        }
                    }
                }
                $frbs[] = $bdata;
            }
            return view('backend.bonus.pplist', compact('frbs'));
        }
        public function pp_add(\Illuminate\Http\Request $request)
        {
            $gamelist = \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::getgamelist('');
            return view('backend.bonus.ppadd', compact('gamelist'));
        }
        public function pp_cancel($bonusCode, \Illuminate\Http\Request $request)
        {
            $res = \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::cancelfrb($bonusCode);
            if ($res['error'] != '0')
            {
                return redirect()->route('backend.bonus.pp')->withErrors($res['description']);
            }

            \VanguardLTE\PPBonus::where('bonusCode', $bonusCode)->delete();
            return redirect()->route('backend.bonus.pp')->withSuccess('보너스를 취소하였습니다');
        }
        public function pp_store(\Illuminate\Http\Request $request)
        {
            $data = $request->only(
                [
                    'playerId',
                    'rounds',
                    'gameIDList',
                    'expirationDate'
                ]
                );
            $data['bonusCode'] = auth()->user()->generateCode(12);
            $data['gameIDList'] = implode(',', $data['gameIDList']);
            $current_timestamp = \Carbon\Carbon::now()->timestamp;
            $data['expirationDate'] = $current_timestamp + 86400 * ($data['expirationDate'] + 1);
            $response = \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::createfrb($data);
            if ($response['error'] != '0')
            {
                return redirect()->route('backend.bonus.pp')->withErrors($response['description']);
            }

            \VanguardLTE\PPBonus::create($data);
            return redirect()->route('backend.bonus.pp')->withSuccess('보너스를 추가하였습니다');
        }
        public function bng_index(\Illuminate\Http\Request $request)
        {
            return view('backend.jackpots.add');
        }

    }

}

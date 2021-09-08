<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend
{
    class BonusController extends \VanguardLTE\Http\Controllers\Controller
    {
        public function __construct()
        {
            $this->middleware('auth');
        }

        /**
         * Pragmatic bonus
         */
        public function pp_index(\Illuminate\Http\Request $request)
        {
            $bonuses = \VanguardLTE\PPBonus::all();
            $frbs = [];
            $gamelist = \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::getgamelist('');
            foreach ($bonuses as $bonus)
            {
                $res = \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::getplayersfrb($bonus->playerId);
                $bdata = $bonus->toArray();
                $bdata['username'] = $bonus->user?$bonus->user->username:'unknown';
                $bdata['roundsPlayed'] = 0;
                $bdata['gamename'] = '';
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
                $gameIDs = explode(',', $bdata['gameIDList']);
                $gamenames = [];
                foreach ($gameIDs as $gameID) {
                    foreach ($gamelist as $game)
                    {
                        if ($gameID == $game['gamecode'])
                        {
                            $gamenames[] = $game['title'];
                            break;
                        }
                    }
                }
                if (count($gamenames) > 0)
                {
                    $bdata['gamename'] = implode(',', $gamenames);
                }
                else
                {
                    $bdata['gamename'] = $bdata['gameIDList'];
                }
                $frbs[] = $bdata;
            }
            return view('backend.Default.bonus.pplist', compact('frbs'));
        }
        public function pp_add(\Illuminate\Http\Request $request)
        {
            $gamelist = \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::getgamelist('');
            return view('backend.Default.bonus.ppadd', compact('gamelist'));
        }
        public function pp_cancel($bonusCode, \Illuminate\Http\Request $request)
        {
            $res = \VanguardLTE\Http\Controllers\Web\GameProviders\PPController::cancelfrb($bonusCode);
            if ($res['error'] != '0')
            {
                return redirect()->route(config('app.admurl').'.bonus.pp')->withErrors($res['description']);
            }

            \VanguardLTE\PPBonus::where('bonusCode', $bonusCode)->delete();
            return redirect()->route(config('app.admurl').'.bonus.pp')->withSuccess('보너스를 취소하였습니다');
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
                return redirect()->route(config('app.admurl').'.bonus.pp')->withErrors($response['description']);
            }
            \VanguardLTE\PPBonus::create($data);
            return redirect()->route(config('app.admurl').'.bonus.pp')->withSuccess('보너스를 추가하였습니다');
        }

        /**
         * Booongo Bonus
         */

        public function bng_index(\Illuminate\Http\Request $request)
        {
            $bonuses = \VanguardLTE\BNGBonus::all();
            $frbs = [];
            $listbonus = \VanguardLTE\Http\Controllers\Web\GameProviders\BNGController::bonuslist();
            $gamelist1 = \VanguardLTE\Http\Controllers\Web\GameProviders\BNGController::getgamelist('booongo');
            $gamelist2 = \VanguardLTE\Http\Controllers\Web\GameProviders\BNGController::getgamelist('playson');
            $gamelist = array_merge($gamelist1 , $gamelist2);
            foreach ($bonuses as $bonus)
            {
                $bdata = $bonus->toArray();
                $bdata['username'] = $bonus->user?$bonus->user->username:'unknown';
                $bdata['played_win'] = 0;
                $bdata['played_bet'] = 0;
                $bdata['status'] = '';
                $bdata['gamename'] = $bdata['game_id'];
                foreach ($listbonus as $bngbonus)
                {
                    if ($bngbonus['bonus_id'] == $bdata['bonus_id'])
                    {
                        $bdata['played_win'] = $bngbonus['played_win'];
                        $bdata['played_bet'] = $bngbonus['played_bet'];
                        $bdata['status'] = $bngbonus['status'];
                        break;
                    }
                }
                foreach ($gamelist as $game)
                {
                    if ($bdata['game_id'] == $game['gamecode'])
                    {
                        $bdata['gamename'] = $game['title'];
                        break;
                    }
                }
                $frbs[] = $bdata;
            }
            return view('backend.Default.bonus.bnglist', compact('frbs'));
        }
        public function bng_add(\Illuminate\Http\Request $request)
        {
            $gamelist1 = \VanguardLTE\Http\Controllers\Web\GameProviders\BNGController::getgamelist('booongo');
            $gamelist2 = \VanguardLTE\Http\Controllers\Web\GameProviders\BNGController::getgamelist('playson');
            $gamelist = array_merge($gamelist1 , $gamelist2);
            return view('backend.Default.bonus.bngadd', compact('gamelist'));
        }
        public function bng_cancel($bonus_id, \Illuminate\Http\Request $request)
        {
            $res = \VanguardLTE\Http\Controllers\Web\GameProviders\BNGController::bonuscancel($bonus_id);
            if ($res === null)
            {
                return redirect()->route(config('app.admurl').'.bonus.bng')->withErrors('보너스 취소오류');
            }

            \VanguardLTE\BNGBonus::where('bonus_id', $bonus_id)->delete();
            return redirect()->route(config('app.admurl').'.bonus.bng')->withSuccess('보너스를 취소하였습니다');
        }
        public function bng_store(\Illuminate\Http\Request $request)
        {
            $data = $request->only(
                [
                    'player_id',
                    'total_bet',
                    'game_id',
                    'end_date'
                ]
                );
            $data['campaign'] = auth()->user()->generateCode(12);
            $end_date = \Carbon\Carbon::now()->addDays($data['end_date'] + 1);
            $data['end_date'] = $end_date->toIso8601String();
            $response = \VanguardLTE\Http\Controllers\Web\GameProviders\BNGController::bonuscreate($data);
            if ($response == null || $response['status'] == 'FAILED')
            {
                return redirect()->route(config('app.admurl').'.bonus.bng')->withErrors('보너스추가시 오류');
            }
            $data['bonus_id'] = $response['bonus_id'];
            \VanguardLTE\BNGBonus::create($data);
            return redirect()->route(config('app.admurl').'.bonus.bng')->withSuccess('보너스를 추가하였습니다');
        }
        

    }

}

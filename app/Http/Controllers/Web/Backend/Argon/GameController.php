<?php 
namespace VanguardLTE\Http\Controllers\Web\Backend\Argon
{
    class GameController extends \VanguardLTE\Http\Controllers\Controller
    {
        public function __construct()
        {
            $this->middleware('auth');
            $this->middleware('permission:access.admin.panel');
        }
        public function game_transaction(\Illuminate\Http\Request $request)
        {
            $statistics = \VanguardLTE\BankStat::select('bank_stat.*')->orderBy('bank_stat.created_at', 'DESC');

            $start_date = date("Y-m-1 0:0:0");
            $end_date = date("Y-m-d H:i:s");

            if ($request->dates != '')
            {
                $start_date = preg_replace('/T/',' ', $request->dates[0]);
                $end_date = preg_replace('/T/',' ', $request->dates[1]);            
            }
            $statistics = $statistics->where('bank_stat.created_at', '>=', $start_date);
            $statistics = $statistics->where('bank_stat.created_at', '<=', $end_date );
            $total = [
                'add' => (clone $statistics)->where('type', 'add')->sum('sum'),
                'out' => (clone $statistics)->where('type', 'out')->sum('sum'),
            ];
            $statistics = $statistics->paginate(20);
            return view('backend.argon.game.transaction', compact('statistics','total'));
        }
        public function gamebanks_setting(\Illuminate\Http\Request $request)
        {
            \VanguardLTE\Settings::updateOrCreate(['key' => 'minslot'], ['value' => $request->minslot]);
            \VanguardLTE\Settings::updateOrCreate(['key' => 'maxslot'], ['value' => $request->maxslot]);
            \VanguardLTE\Settings::updateOrCreate(['key' => 'minbonus'], ['value' => $request->minbonus]);
            \VanguardLTE\Settings::updateOrCreate(['key' => 'maxbonus'], ['value' => $request->maxbonus]);
            \VanguardLTE\Settings::updateOrCreate(['key' => 'reset_bank'], ['value' => $request->reset_bank]);
            return redirect()->back()->withSuccess('환수금 설정이 업데이트되었습니다.');
        }

        public function game_bonusbank(\Illuminate\Http\Request $request)
        {
            $master_id = $request->id;
            $master = \VanguardLTE\User::where('id', $master_id)->first();
            $bonusbank = \VanguardLTE\BonusBank::where('master_id', $master_id)->get();
            
            return view('backend.argon.game.bonusbank', compact('master','bonusbank'));
        }

        public function game_bank(\Illuminate\Http\Request $request)
        {
            $shops = auth()->user()->availableShops();
            $gamebank = \VanguardLTE\GameBank::whereIn('shop_id', $shops);
            $masters = \VanguardLTE\User::where('role_id', 6)->pluck('id')->toArray();
            $bonusbank = \VanguardLTE\BonusBank::whereIn('master_id', $masters)
                            ->selectRaw('id, master_id, SUM(bank) as totalBank,count(master_id) as games')
                            ->groupby('master_id')->get();

            $gamebank = $gamebank->paginate(20);

            $minslot = \VanguardLTE\Settings::where('key', 'minslot')->first();
            $maxslot = \VanguardLTE\Settings::where('key', 'maxslot')->first();
            $minbonus = \VanguardLTE\Settings::where('key', 'minbonus')->first();
            $maxbonus = \VanguardLTE\Settings::where('key', 'maxbonus')->first();
            $reset_bank = \VanguardLTE\Settings::where('key', 'reset_bank')->first();
            return view('backend.argon.game.bank', compact('gamebank','bonusbank', 'minslot','maxslot','minbonus','maxbonus','reset_bank'));
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

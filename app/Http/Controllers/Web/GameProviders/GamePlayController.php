<?php 
namespace VanguardLTE\Http\Controllers\Web\GameProviders
{
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Log;
    class GamePlayController extends \VanguardLTE\Http\Controllers\Controller
    {
        //utility function
        public static function gamePlayTimeFormat($t=0)
        {
            if ($t==0)
            {
                $t = time();
            }
            $servertime = $t -  1356969600;
            return (int)$servertime;
        }

        public static function generateGameTrend($date, $gameId)
        {
            if ($gameId == 'taixiu')
            {
                //remove all old date
                $from_sl = $date . 'T00:00:00';
                $to_sl = $date . 'T23:59:59';
                \VanguardLTE\GPGameTrend::where('sDate','>=', $from_sl)->where('sDate','<', $to_sl)->delete();

                //create
                $from_ts = strtotime($from_sl);
                $to_ts = strtotime($to_sl);
                $i = 0;
                for ($ts = $from_ts; $ts < $to_ts; $ts += 30, $i=$i+1)
                { 
                    $r1 = mt_rand(1,6);
                    $r2 = mt_rand(1,6);
                    $r3 = mt_rand(1,6);
                    $result = "$r1|$r2|$r3";
                    $dno = substr(str_replace('-', '', $date),2) . sprintf('%06d', $i);
                    $ets = $ts + 20;
                    \VanguardLTE\GPGameTrend::create(
                        [
                            'game_id' => 'taixiu',
                            'p' => 12,
                            'a' => 1,
                            'dno' => $dno,
                            'e' => GamePlayController::gamePlayTimeFormat($ets),
                            'sl' => GamePlayController::gamePlayTimeFormat($ts),
                            'rno' => $result,
                            'rt' => ($r1+$r2+$r3)>=11?1:2,
                            's' => 0,
                            'sDate' => explode('.',date(DATE_RFC3339_EXTENDED, $ts))[0],
                            'eDate' => explode('.',date(DATE_RFC3339_EXTENDED, $ets))[0],
                            'PartialResultTime' => 0
                        ]
                    );
                }
            }
        }

        //web api
        public function Livebetpool(\Illuminate\Http\Request $request)
        {

        }
        public function GetMemberDrawResult(\Illuminate\Http\Request $request)
        {

        }
        public function HistoryBet(\Illuminate\Http\Request $request)
        {

        }
        public function MultiLimit(\Illuminate\Http\Request $request)
        {

        }
        public function GameSetting(\Illuminate\Http\Request $request)
        {

        }
        public function DrawResult(\Illuminate\Http\Request $request)
        {

        }
        public function OpenBet3(\Illuminate\Http\Request $request)
        {

        }
        public function ServerTime(\Illuminate\Http\Request $request)
        {

        }
        public function UserInfo(\Illuminate\Http\Request $request)
        {

        }
        public function SpreadBet(\Illuminate\Http\Request $request)
        {

        }
        public function Trend(\Illuminate\Http\Request $request)
        {
            $trend = [];
	        $curTime = time();
            $lastTrendTime =  GamePlayController::gamePlayTimeFormat($curTime - 96 * 30);
            $firstTrendTime =  GamePlayController::gamePlayTimeFormat($curTime + 4 * 30);
            \VanguardLTE\GPGameTrend::where('sl', '>=', $lastTrendTime)->where('sl', '<=', $firstTrendTime)->get();
        }
        public function GetActiveProductsByVendor(\Illuminate\Http\Request $request)
        {

        }
        public function GetTrialPromotionInfo(\Illuminate\Http\Request $request)
        {

        }
    }
}

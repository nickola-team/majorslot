<?php 
namespace VanguardLTE\Http\Controllers\Web\GameParsers\PowerBall
{
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Log;
    class PowerBallController
    {
        //web routes

        public function placeBet(\Illuminate\Http\Request $request)
        {
            $token = $request->token;
            $user = \VanguardLTE\User::where('api_token', $token)->where('role_id',1)->first();
            if (!$user)
            {
                return response()->json([
                    'error' => true,
                    'bet_id' => '',
                    'msg' => '유저를 찾을수 없습니다'
                ], 200);
            }
            $game_id = $request->game_id;
            $ground_no = $request->ground_no;
            $result = $request->result;
            $amount = $request->amount;
            $res_bets = [];
            $totalBet = 0;
            
            $currentRound = \VanguardLTE\PowerBallModel\PBGameRound::where('status',0)->where('game_id', $game_id)->where('ground_no', $ground_no)->first();
            if (!$currentRound)
            {
                return response()->json([
                    'error' => true,
                    'bet_id' => '',
                    'msg' => '알수없는 게임회차'
                ], 200);
            }

            $currTime = time();
            $endTime = strtotime($currentRound->e_time);
            
            if ($this->LAST_LIMIT_TIME>0 && $endTime - $currTime  < $this->LAST_LIMIT_TIME)
            {
                return response()->json([
                    'error' => true,
                    'bet_id' => '',
                    'msg' => '베팅시간이 아닙니다'
                ], 200);
            }
            if ($amount <= 0)
            {
                return response()->json([
                    'error' => true,
                    'bet_id' => '',
                    'msg' => '베팅금액을 입력하세요'
                ], 200);
            }

            if ($user->balance < $amount)
            {
                return response()->json([
                    'error' => true,
                    'bet_id' => -1,
                    'msg' => '잔고가 부족합니다'
                ], 200);
            }
            $user->balance = $user->balance - abs($amount);
            $bet_id = substr(date('YmdHis', time()),2);
            $rate = 0;
            $pbresult = \VanguardLTE\PowerBallModel\PBGameResult::where([
                'game_id' => $game_id,
                'rt_no' => $result
            ])->first();
            if (!$pbresult)
            {
                $pbresult = \VanguardLTE\PowerBallModel\PBGameResult::where([
                    'game_id' => 0,
                    'rt_no' => $result
                ])->first();
                if (!$pbresult)
                {
                    return response()->json([
                        'error' => true,
                        'bet_id' => -1,
                        'msg' => '알수없는 베팅'
                    ], 200);
                }
            }

            $rate = $pbresult->rt_rate;

            $brecord = \VanguardLTE\PowerBallModel\PBGameBet::create(
                [
                    'user_id' => $user->id,
                    'game_id' => $game_id,
                    'ground_no' => $ground_no,
                    'bet_id' => $bet_id,
                    'result' => $result,
                    'rate' => $rate,
                    'amount' => $amount,
                    'win' => 0,
                    'status' => 0
                ]
            );
            $bet_id = $bet_id . sprintf('%06d', $brecord->id);
            $brecord->update([
                'bet_id' => $bet_id
            ]);

            $user->save();
            
            return response()->json([
                'error' => false,
                'bet_id'  => $bet_id,
                'msg' => 'OK'
            ], 200);

        }
        public function historyBet(\Illuminate\Http\Request $request)
        {
            $token = $request->token;
            $game_id = $request->game_id;
            $user = \VanguardLTE\User::where('api_token', $token)->where('role_id',1)->first();
            if (!$user)
            {
                return response()->json([
                    'error' => true,
                    'bets'  => []
                ], 200);
            }
            if ($game_id == 0)
            {
                $userHistory = \VanguardLTE\PowerBallModel\PBGameBet::where([
                    'user_id' => $user->id,
                ])->orderby('created_at', 'desc')->limit(20)->get();
            }
            else
            {
                $userHistory = \VanguardLTE\PowerBallModel\PBGameBet::where([
                    'user_id' => $user->id,
                    'game_id' => $game_id,
                ])->orderby('created_at', 'desc')->limit(20)->get();
            }
            return response()->json([
                'error' => false,
                'bets'  => $userHistory
            ], 200);


        }
        public function userInfo(\Illuminate\Http\Request $request)
        {
            $token = $request->token;
            if ($token == '')
            {
                return response()->json([
                    'error' => true,
                    'servertime' => time(),
                ], 200);
            }
            $user = \VanguardLTE\User::where('api_token', $token)->where('role_id',1)->first();
            if (!$user)
            {
                return response()->json([
                    'error' => true,
                    'servertime' => time(),
                ], 200);
            }
            return response()->json([
                'error' => false,
                'servertime'  => time(),
                'username' => $user->username,
                'balance' => $user->balance
            ], 200);


        }
        public function currentRound(\Illuminate\Http\Request $request)
        {
            $game_id = $request->game_id;
            $from_sl = date('Y-m-d H:i:s');
            $round = \VanguardLTE\PowerBallModel\PBGameRound::where('e_time','>=', $from_sl)->where(['game_id' => $game_id, 'status' => 0])->orderby('id', 'asc')->first();
            if (!$round)
            {
                return response()->json([
                    'error' => true,
                    'round'  => null
                ], 200);
            }
            $round = $round->toArray();
            $round['remindtime'] = strtotime($round['e_time']) - time();
            return response()->json([
                'error' => false,
                'round'  => $round
            ], 200);
        }

        public function recentRounds(\Illuminate\Http\Request $request)
        {
            $game_id = $request->game_id;
            $to_sl = date('Y-m-d H:i:s');
            $from_sl = date('Y-m-d H:i:s', strtotime("-1 days"));
            $rounds = \VanguardLTE\PowerBallModel\PBGameRound::where('e_time','>=', $from_sl)->where('e_time','<', $to_sl)->where('game_id',$game_id)->orderby('id', 'desc')->get();
            return response()->json([
                'error' => false,
                'rounds'  => $rounds
            ], 200);
            

        }
    }
}

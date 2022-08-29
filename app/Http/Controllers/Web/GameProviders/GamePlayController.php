<?php 
namespace VanguardLTE\Http\Controllers\Web\GameProviders
{
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Log;
    class GamePlayController extends \VanguardLTE\Http\Controllers\Controller
    {
        const GPGameList = [
            12 => 'TaiXiu'
        ];
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

        public static function dateTimeFromGPTime($t)
        {
            $servertime = $t +  1356969600;
            return date('Y-m-d H:i:s', $servertime);
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
                    $currnt = time();
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
                            'rno' => ($currnt>$ts)?$result:null,
                            'rt' => ($currnt>$ts)?(($r1+$r2+$r3)>=11?1:2):null,
                            's' => ($currnt>$ts)?1:0,
                            'sDate' => explode('.',date(DATE_RFC3339_EXTENDED, $ts))[0],
                            'eDate' => explode('.',date(DATE_RFC3339_EXTENDED, $ets))[0],
                            'PartialResultTime' => 0
                        ]
                    );
                }
            }
        }

        public static function processOldTrends($gameId)
        {
            $currTime = time();
            $oldTrends = \VanguardLTE\GPGameTrend::where('s',0)->where('e', '<', GamePlayController::gamePlayTimeFormat($currTime))->get();
            if (count($oldTrends) == 0)
            {
                return;
            }
            foreach ($oldTrends as $trend){
                $r1 = mt_rand(1,6);
                $r2 = mt_rand(1,6);
                $r3 = mt_rand(1,6);
                $result = "$r1|$r2|$r3";
                $trend->update([
                    'rno' => $result,
                    'rt' => ($r1+$r2+$r3)>=11?1:2,
                    's' => 6,
                ]);
            }
        }

        public function calcBetWinForUser($trendID)
        {
            $trend = \VanguardLTE\GPGameTrend::where('id',$trendID)->first();
            $totalBets = \VanguardLTE\GPGameBet::where([
                'p' => $trend->p,
                'dno' => $trend->dno,
                'status' => 0,
                'rt' => $trend->rt
            ])->get();
            $users = [];
            foreach ($totalBets as $bet)
            {
                $bet->update([
                    'win' => $bet->amount * 1.98
                ]);
            }
            $trend->update(['s' => 1]);

            //update user balance and add game_stat
            $totalBets = \VanguardLTE\GPGameBet::where([
                'game_id' => $trend->game_id,
                'dno' => $trend->dno,
                'status' => 0,
            ])->groupby('user_id')->selectRaw('user_id, sum(amount) as bet, sum(win) as win')->get();
            $category = \VanguardLTE\Category::where(['provider' => null, 'shop_id' => 0, 'href' => 'gameplay'])->first();
            $game = \VanguardLTE\Game::where('name', self::GPGameList[$trend->p] . 'GP')->where('shop_id', 0)->first();
            foreach ($totalBets as $bet)
            {
                $user = \VanguardLTE\User::where('id', $bet->user_id)->first();
                if ($user)
                {
                    $user->update([
                        'balance' => $user->balance + $bet->win
                    ]);
                    $user = $user->fresh();
                    \VanguardLTE\StatGame::create([
                        'user_id' => $user->id, 
                        'balance' => intval($user->balance), 
                        'bet' => $bet->bet, 
                        'win' => $bet->win, 
                        'game' =>  $trend->game_id, 
                        'type' => 'table',
                        'percent' => 0, 
                        'percent_jps' => 0, 
                        'percent_jpg' => 0, 
                        'profit' => 0, 
                        'denomination' => 0, 
                        'shop_id' => $user->shop_id,
                        'category_id' => isset($category)?$category->id:0,
                        'game_id' => $game->original_id,
                        'roundid' => $trend->p . '_' . $trend->dno,
                    ]);
                }
            }


            $totalBets = \VanguardLTE\GPGameBet::where([
                'game_id' => $trend->game_id,
                'dno' => $trend->dno,
                'status' => 0,
            ])->update(['status' => 1]);
        }

        

        //web api
        public function processCurrentTrend(\Illuminate\Http\Request $request)
        {
            $p = $request->p;
            // if ($gameId == 'taixiu')
            {
                $currTime = time();
                $currentTrend = \VanguardLTE\GPGameTrend::where('s',0)->where('p',$p)->orderby('sl')->first();
                if ($currentTrend && GamePlayController::gamePlayTimeFormat($currTime) > $currentTrend->e)
                {
                    $r1 = mt_rand(1,6);
                    $r2 = mt_rand(1,6);
                    $r3 = mt_rand(1,6);
                    $result = "$r1|$r2|$r3";
                    $currentTrend->update([
                        'rno' => $result,
                        'rt' => ($r1+$r2+$r3)>=11?1:2,
                        's' => 6,
                    ]);
                    $currentTrend = $currentTrend->fresh();
                    $topTrend = \VanguardLTE\GPGameTrend::where('s',0)->where('p',$p)->orderby('sl')->limit(4)->get()->last();
                    $this->calcBetWinForUser($currentTrend->id);
                    return response()->json([
                        's' => 1,
                        'old' => $currentTrend,
                        'new' => $topTrend
                    ]);
                }
                else
                {
                    $result = $this->livebet($p, true);
                    return response()->json([
                        's' => 0,
                        'live' => $result
                    ]);
                }
            }
        }
        public function livebet($game_p, $fake=false)
        {
            $trend = \VanguardLTE\GPGameTrend::where('s',0)->where('p', $game_p)->orderby('sl')->first();
            $totalBets = \VanguardLTE\GPGameBet::where([
                'p' => $game_p,
                'dno' => $trend->dno,
                'status' => 0,
            ])->groupby('rt')->selectRaw('rt, sum(amount) as bet, count(rt) as players')->get();
            $data = [];
            if ($fake)
            {
                for ($id = 1; $id <=2; $id++){
                    $t = [
                        'playerCount' => 0,
                        'id' => $id,
                        'cont' => null,
                        'a' => 0,
                        'o' => 0,
                        'm' => null
                    ];
                    $cur = GamePlayController::gamePlayTimeFormat();
                    $deltaTime = $cur - $trend->sl;
                    $multiplier = 1;
                    for ($i = 0;$i < $deltaTime;$i++)
                    {
                        $multiplier += mt_rand(1,5);
                    }
                    $t['playerCount'] = $t['playerCount'] + $multiplier;
                    $t['a'] = $t['a'] + $multiplier * mt_rand(20,1000);
                    $data[] = $t;
                }
            }
            foreach ($totalBets as $bet)
            {
                $bfound = 0;
                for ($i=0;$i<count($data);$i++)
                {
                    if ($data[$i]['id'] == $bet->rt)
                    {
                        $bfound = 1;
                        $data[$i]['playerCount'] = $data[$i]['playerCount'] + $bet->players;
                        $data[$i]['a'] = $data[$i]['a'] + $bet->bet;
                        break;
                    }
                }
                if ($bfound == 0){
                    $t = [
                        'playerCount' => $bet->players,
                        'id' => $bet->rt,
                        'cont' => null,
                        'a' => $bet->bet,
                        'o' => 0,
                        'm' => null
                    ];
                    
                    $data[] = $t;
                }
            }
            $result = [
                'p' => 12,
                'a' => 1,
                'data' => $data,
                'r' => 1
            ];
            return $result;
        }
        public function Livebetpool(\Illuminate\Http\Request $request)
        {  
            $data = json_decode($request->getContent());
            $p = $data->p;
            $result = $this->livebet($p, false);
            return response()->json(json_encode($result));
        }
        public function GetMemberDrawResult(\Illuminate\Http\Request $request)
        {
            $token = $request->token;
            $dno = $request->dno;
            $p = $request->P;
            $user = \VanguardLTE\User::where('api_token', $token)->where('role_id',1)->first();
            if (!$user)
            {
                return response()->json([
                    'Code' => 0,
                    'Result'  => [
                        'TotalPayout' => 0,
                        'TotalBet' =>0,
                    ]
                ], 200);
            }
            $stat = \VanguardLTE\StatGame::where([
                'user_id' => $user->id, 
                'roundid' => $p. '_' .$dno,
            ])->first();
            $totalPayout = 0;
            $totalBet = 0;
            if ($stat)
            {
                $totalPayout = $stat->win;
                $totalBet = $stat->bet;
            }
            return response()->json([
                'Code' => 0,
                'Result'  => [
                    'TotalPayout' => round($totalPayout,2),
                    'TotalBet' => round($totalBet,2),
                ]
            ], 200);
        }
        public function HistoryBet(\Illuminate\Http\Request $request)
        {
            $data = json_decode($request->getContent());
            $token = $data->token;
            $start = $data->s;
            $end = $data->e;
            $p = $data->p;
            $pg = $data->pg;
            $user = \VanguardLTE\User::where('api_token', $token)->where('role_id',1)->first();
            if (!$user)
            {
                return response()->json([
                    'cmd' => 'HistoryBet',
                    'data'  => [
                        'p' => $p,
                        'bet' => [],
                        'totalpg' => 0,
                    ]
                ], 200);
            }
            $start_date = GamePlayController::dateTimeFromGPTime($start);
            $end_date = GamePlayController::dateTimeFromGPTime($end);
            $userHistory = \VanguardLTE\GPGameBet::where([
                'user_id' => $user->id,
                'p' => $p,
                'status' => 1,
            ])->where('created_at','>=', $start_date)->where('created_at','<=', $end_date)->orderby('created_at', 'desc')->get()->toArray();
            $totalCount = count($userHistory);
            $bets = [];
            if ($pg == 0) $pg = 1;
            for ($i = ($pg-1) * 10; $i < 10 * $pg; $i++)
            {
                if ($i >= $totalCount)
                {
                    break;
                }
                $trend = \VanguardLTE\GPGameTrend::where(
                    [
                        'p' => $p,
                        'dno' => $userHistory[$i]['dno']
                    ]
                )->first();
                $bets[] = [
                    'date' => GamePlayController::gamePlayTimeFormat(strtotime($userHistory[$i]['created_at'])),
                    'bno' => $userHistory[$i]['bet_id'],
                    'bs' => 4,
                    'amt' => round($userHistory[$i]['amount'],2),
                    'stk' => round($userHistory[$i]['amount'],2),
                    'cont' => "1@".$userHistory[$i]['dno']."@".$userHistory[$i]['rt']."@1.98@1",
                    'o' => '1.98',
                    "win" => $userHistory[$i]['win'] - $userHistory[$i]['amount'],
                    "result" => ($trend==null)?'1|1|1':$trend->rno,
                    "dno" => $userHistory[$i]['dno'],
                    "bundleId"=> (string)($userHistory[$i]['id']),
                    "returnAmount"=> round($userHistory[$i]['win'],2),
                    "rt"=> (string)($userHistory[$i]['rt'])

                ];

            }
            return response()->json([
                'cmd' => 'HistoryBet',
                'data'  => [
                    'p' => $p,
                    'bet' => $bets,
                    'totalpg' => (int)(($totalCount / 10) + 1),
                ]
            ], 200);


        }
        public function MultiLimit(\Illuminate\Http\Request $request)
        {
            $lmt = $request->lmt;
            $p = $request->p;
            return response()->json([
                'cmd' => 'MultiLimit',
                'data'  => [
                    'p' => $p,
                    's' => 'SUCCESS',
                    'lmt' => $lmt
                ]
            ], 200);
        }
        public function GameSetting(\Illuminate\Http\Request $request)
        {
            $lmt = $request->lmt;
            $lo = '150';
            $hi = '150000';
            if ($lmt == 2)
            {
                $lo = '7500';
                $hi = '1500000';
            }
            else if ($lmt == 3)
            {
                $lo = '15000';
                $hi = '3000000';
            }
            return response()->json([
                'data'  => [
                    'normal' => [
                        [
                            'set' => [
                                [
                                    'id' => 1,
                                    'o' => "1.98",
                                    'bet' => "1",
                                    'lo' => $lo,
                                    'hi' => $hi,
                                ],
                                [
                                    'id' => 2,
                                    'o' => "1.98",
                                    'bet' => "1",
                                    'lo' => $lo,
                                    'hi' => $hi,
                                ]
                            ],
                            'p' => 12,
                            'a' => 1,
                            'aname' => 'TaiXiu',
                            'offsite' => '',
                            's' => 1,
                            'stop' => 3,
                            'afreq' => 20,
                            'timezone' => 0,
                            'afmt' => '3x1',
                            'prt' => 0
                        ]
                    ]
                ]
            ], 200);
        }
        public function DrawResult(\Illuminate\Http\Request $request)
        {
            $data = json_decode($request->getContent());
            $p = $data->p;
            $pg = $data->pg;
            $date = $data->date;
            $dno = $data->dno;
            $enddate = $date - 86400; //for 1 day
            $trends = \VanguardLTE\GPGameTrend::where('sl', '<', $date)->where('sl', '>=', $enddate)->where('p', $p)->orderby('sl','desc');
            if ($dno != '')
            {
                $trends = $trends->where('dno', $dno);
            }
            if ($pg==0) $pg = 1;
            $totalCount = (clone $trends)->get()->count();
            $drawsData = $trends->skip(($pg-1) * 10)->limit(10)->get();
            $draws = [];
            foreach ($drawsData as $d)
            {
                $draws[] = [
                    'p' => $p,
                    'a' => $d->a,
                    'dno' => $d->dno,
                    'rno' => $d->rno,
                    's' => $d->sl,
                    'rt' => $d->rt
                ];
            }
            return response()->json([
                'cmd' => 'DrawResult',
                'data'  => [
                    'p' => 12,
                    'draw' =>$draws,
                    'totalpg' => $totalCount
                ]
            ], 200);
        }
        public function WinLose(\Illuminate\Http\Request $request)
        {
            $data = json_decode($request->getContent());
            $p = $data->p;
            $s = $data->s;
            $start_date = GamePlayController::dateTimeFromGPTime($s);
            $e = $data->e;
            $end_date = GamePlayController::dateTimeFromGPTime($s);
            $token = $data->token;
            $user = \VanguardLTE\User::where('api_token', $token)->where('role_id',1)->first();
            if (!$user)
            {
                return response()->json([
                    'cmd' => 'WinLose',
                    'data'  => [
                        [
                        'date' => date('m/d/y'),
                        'stk' => 0,
                        'totalw' => 0
                        ]
                    ]
                ], 200);
            }
            $game = \VanguardLTE\Game::where('name', self::GPGameList[$p] . 'GP')->where('shop_id', 0)->first();
            $stat = \VanguardLTE\StatGame::where([
                'user_id' => $user->id, 
                'game_id' => $game->original_id,
            ])->where('date_time','>=', $start_date)->where('date_time','>=', $end_date)->get();
            $stk = $stat->sum('bet');
            $totalw = $stat->sum('win');

            return response()->json([
                'cmd' => 'WinLose',
                'data'  => [
                    [
                    'date' => date('m/d/y', strtotime($start_date)),
                    'stk' => $stk,
                    'totalw' => $totalw - $stk,
                    ]
                ]
            ], 200);

        }
        public function OpenBet3(\Illuminate\Http\Request $request)
        {
            $token = $request->token;
            $p = $request->p;
            $user = \VanguardLTE\User::where('api_token', $token)->where('role_id',1)->first();
            if (!$user)
            {
                return response()->json([
                    'cmd' => 'OpenBet',
                    'data'  => [
                        'p' => 12,
                        'bet' =>[],
                        'totalpg' => 0
                    ]
                ], 200);
            }
            $openBets = \VanguardLTE\GPGameBet::where([
                'user_id' => $user->id,
                'p' => $p,
                'status' => 0,
            ])->get();
            $openBetData = [];
            foreach ($openBets as $bet)
            {
                $openBetData[] = [
                    'date' => GamePlayController::gamePlayTimeFormat(strtotime($bet->created_at)),
                    'bno' => $bet->bet_id,
                    'bs' => 1,
                    'amt' => round($bet->amount,2),
                    'stk' => round($bet->amount,2),
                    'cont' => "1@$bet->dno@$bet->rt@1.98@1",
                    'o' => 1.98,
                    'win' => 0.00,
                    'returnAmount' => 0.00
                ];
            }

            return response()->json([
                'cmd' => 'OpenBet',
                'data'  => [
                    'p' => 12,
                    'bet' =>$openBetData,
                    'totalpg' => 0
                ]
            ], 200);

        }
        public function ServerTime(\Illuminate\Http\Request $request)
        {
            $currTime = time();
            return response()->json([
                'cmd' => 'ServerTime',
                'data'  => GamePlayController::gamePlayTimeFormat($currTime)
            ], 200);

        }
        public function UserInfo(\Illuminate\Http\Request $request)
        {
            $token = $request->token;
            $init = $request->init;
            $user = \VanguardLTE\User::where('api_token', $token)->where('role_id',1)->first();
            if (!$user)
            {
                return response()->json([
                    'cmd' => 'UserInfo',
                    'data'  => null
                ], 200);
            }

            if ($init == 'true')
            {
                return response()->json([
                    'cmd' => 'UserInfo',
                    'data'  => [
                        'userid' => $user->username,
                        'curr' => 'KRW',
                        'wallet' => round($user->balance,1),
                        'lmt' => [
                            [
                            'p' => 12,
                            'prof' => 0,
                            'mul' => '150,7500,15000|150000,1500000,3000000',
                            ]
                        ],
                        'areas' => [
                            [
                                'p' => 12,
                                'a' => 1,
                                'aname' => 'TaiXiu',
                                'offsite' => '',
                                's' => 1,
                                'stop' => 3,
                                'afreq' => 20,
                                'timezone' => 0,
                                'afmt' => '3x1',
                                'prt' => 0
                            ]
                        ],
                        'type' => 3,
                    ]
                ], 200);
            }
            else
            {
                return response()->json([
                    'cmd' => 'UserInfo',
                    'data'  => [
                        'userid' => $user->username,
                        'curr' => 'KRW',
                        'wallet' => round($user->balance,1),
                        'type' => 3,
                    ]
                ], 200);
            }

        }
        public function SpreadBet(\Illuminate\Http\Request $request)
        {
            $data = json_decode($request->getContent());
            $token = $data->token;
            $user = \VanguardLTE\User::where('api_token', $token)->where('role_id',1)->first();
            if (!$user)
            {
                return response()->json([
                    'code' => 1,
                    'bets'  => []
                ], 200);
            }
            $gameid = $request->game;
            $res_bets = [];
            $totalBet = 0;
            $currentTrend = \VanguardLTE\GPGameTrend::where('s',0)->orderby('sl')->first();
            $currTime = GamePlayController::gamePlayTimeFormat();
            if ($currentTrend->e - $currTime  < 3)
            {
                return response()->json([
                    'code' => -107,
                ], 200);
            }
            foreach ($data->data as $bet )
            {
                //check if dno is correct??
                //currently no
                //create bet record
                $bet_id = substr(date('YmdHis', time()),2);
                $brecord = \VanguardLTE\GPGameBet::create(
                    [
                        'user_id' => $user->id,
                        'game_id' => $currentTrend->game_id,
                        'p' => $bet->p,
                        'dno' => $bet->dno,
                        'bet_id' => $bet_id,
                        'rt' => $bet->id,
                        'amount' => $bet->amt,
                        'win' => 0,
                        'status' => 0
                    ]
                );
                $bet_id = $bet_id . sprintf('%06d', $brecord->id);
                $brecord->update([
                    'bet_id' => $bet_id
                ]);
                $totalBet = $totalBet + $bet->amt;
                $res_bets[] = "$bet_id|$bet->p|1|$bet->dno|$bet->amt|$bet->amt|1|$bet->id";
            }
            $user->balance = $user->balance - abs($totalBet);
            $user->save();

            return response()->json([
                'code' => 0,
                'bets'  => $res_bets
            ], 200);

        }
        public function Trend(\Illuminate\Http\Request $request)
        {
            // $data = json_decode($request->getContent());
            $p = $request->p;
            $topTrend = \VanguardLTE\GPGameTrend::where('s',0)->where('p',$p)->orderby('sl')->limit(4)->get()->last();
            if (!$topTrend)
            {
                return response()->json([
                    'cmd' => 'Trend',
                    'data'  => [
                        'draw' => null
                    ]
                ], 200);
            }

            $trends = \VanguardLTE\GPGameTrend::where('sl', '<=', $topTrend->sl)->where('p',$p)->orderby('sl','desc')->limit(100)->get();
            return response()->json([
                'cmd' => 'Trend',
                'data'  => [
                    'draw' => $trends
                ]
            ], 200);
        }
        public function GetActiveProductsByVendor(\Illuminate\Http\Request $request)
        {
            return response()->json([
                'code' => 0,
                'productIds'  => [
                    1,3,4,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,33,32,34,35,36
                ]
            ], 200);

        }
        public function GetTrialPromotionInfo(\Illuminate\Http\Request $request)
        {
            return response()->json([
                'err' => [
                    'code' => -201,
                    'cont' => 'DATA NOT FOUND!'
                    ]
            ], 200);
        }
    }
}

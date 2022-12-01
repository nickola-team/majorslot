<?php 
namespace VanguardLTE\Games\EOSPowerBall3GP
{
    use VanguardLTE\Http\Controllers\Web\GameProviders\GamePlayController;
    use Illuminate\Support\Facades\Http;
    class Server
    {
        public  $GAMEID = 92;
        public  $GAMENAME = 'eospgb3';
        public  $SELF_GEN_TREND = false;
        public  $GAME_TIME = 170;
        public  $GAME_PERIOD = 180;
        public  $LAST_LIMIT_TIME = 10;
        public  $VIDEO_URL = 'https://ntry.com/scores/eos_powerball/3min/main.php';
        public  $VIDEO_WIDTH = 900;
        public  $TREND_URL = '/data/json/games/eos_powerball/3min/recent_result.json';
        public  $RATE = [
            1 => 1.95, //파홀
            2 => 1.95,  //파짝

            3 => 1.95, //파언
            4 => 1.95,  //파오

            5 => 4.0, //파홀언
            6 => 3.0,  //파홀오
            7 => 3.0, //파짝언
            8 => 4.0,  //파짝오

            9 => 1.95, //일홀
            10 => 1.95,  //일짝

            11 => 1.95, //일언
            12 => 1.95,  //일오

            13 => 3.70, //일홀언
            14 => 3.70,  //일홀오
            15 => 3.70, //일짝언
            16 => 3.70,  //일짝오

            17 => 7.0, //일홀언,파홀
            18 => 7.0,  //일홀언,파짝
            19 => 7.0, //일홀오,파홀
            20 => 7.0,  //일홀오,파짝
            21 => 7.0, //일짝언,파홀
            22 => 7.0,  //일짝언,파짝
            23 => 7.0, //일짝오,파홀
            24 => 7.0,  //일짝오,파짝
            
        ];
        public  $MULTILIMIT = [
            [
                'lo' => 150,
                'hi' => 300000,
            ],
            [
                'lo' => 750,
                'hi' => 750000,
            ],
            [
                'lo' => 1500,
                'hi' => 1500000,
            ]
        ];

        public  $AFMT = '4x1';

        public function generateResult()
        {
            return [
                'rno' => null,
                'rt' => null,
            ];
        }


        public function getRT($trendResult)
        {
            $balls = explode('|', $trendResult);
            $nball = $balls[0] + $balls[1] + $balls[2] + $balls[3] + $balls[4];
            $tball = $balls[5];
            $rt = '';
            if ($tball % 2 == 1) //파홀짝
            {
                $rt = $rt . '1|';
            }
            else
            {
                $rt = $rt . '2|';
            }

            if ($tball  < 5) //파언오
            {
                $rt = $rt . '3|';
            }
            else
            {
                $rt = $rt . '4|';
            }

            //파홀짝&파언오
            if ($tball % 2 == 1) //파홀짝
            {
                if ($tball  < 5) //파언오
                {
                    $rt = $rt . '5|';
                }
                else
                {
                    $rt = $rt . '6|';
                }
                
            }
            else
            {
                if ($tball  < 5) //파언오
                {
                    $rt = $rt . '7|';
                }
                else
                {
                    $rt = $rt . '8|';
                }
            }


            if ($nball % 2 == 1) //일홀짝
            {
                $rt = $rt . '9|';
            }
            else
            {
                $rt = $rt . '10|';
            }

            if ($nball  < 73) //일언오
            {
                $rt = $rt . '11|';
            }
            else
            {
                $rt = $rt . '12|';
            }

            //일홀짝&일언오
            if ($nball % 2 == 1) //일홀짝
            {
                if ($nball  < 73) //일언오
                {
                    $rt = $rt . '13|';
                }
                else
                {
                    $rt = $rt . '14|';
                }
                
            }
            else
            {
                if ($nball  < 73) //일언오
                {
                    $rt = $rt . '15|';
                }
                else
                {
                    $rt = $rt . '16|';
                }
            }

            //조합 파&홀
            if ($nball % 2 == 1) //일홀짝
            {
                if ($nball  < 73) //일언오
                {
                    if ($tball % 2 == 1)
                    {
                        $rt = $rt . '17';
                    }
                    else
                    {
                        $rt = $rt . '18';
                    }
                }
                else 
                {
                    if ($tball % 2 == 1)
                    {
                        $rt = $rt . '19';
                    }
                    else
                    {
                        $rt = $rt . '20';
                    }
                }
            }
            else
            {
                if ($nball  < 73) //일언오
                {
                    if ($tball % 2 == 1)
                    {
                        $rt = $rt . '21';
                    }
                    else
                    {
                        $rt = $rt . '22';
                    }
                }
                else 
                {
                    if ($tball % 2 == 1)
                    {
                        $rt = $rt . '23';
                    }
                    else
                    {
                        $rt = $rt . '24';
                    }
                }
            }


            return $rt;
        }

        public function getRecentTrend()
        {
            $server = env('NTRY_PROXY', 'http://ntry.com');
            $response = Http::get($server . $this->TREND_URL);
            if ($response->ok()) {
                $data = $response->json();
                return $data;
            }
            return null;
        }
        public function processCurrentTrend()
        {
            $currTime = time();
            $game = \VanguardLTE\Game::where('name', 'EOSPowerBall3GP')->where('shop_id', 0)->first();

            $pendingTrends = \VanguardLTE\GPGameTrend::where('s',0)->where('p',$this->GAMEID)->where('e', '<', GamePlayController::gamePlayTimeFormat($currTime))->orderby('sl')->get();
            if (count($pendingTrends) > 0) {
                $data = $this->getRecentTrend();
                if ($data!=null)
                {
                    foreach ($pendingTrends as $trend)
                    {
                        foreach ($data as $ga)
                        {
                            // $dno = substr(date('Ymd'), 2) . sprintf('%06d', $ga['date_round']);
                            $d = explode('-', $ga['reg_date']);
                            $dt = sprintf('%04d%02d%02d', $d[0], $d[1], $d[2]);
                            $dno = substr($dt, 2)  . sprintf('%06d', $ga['date_round']);
                            if ($trend->dno == $dno)
                            {
                                //process this round
                                $trendResult = $ga['ball_1'] .'|'.$ga['ball_2'] .'|' .$ga['ball_3'] .'|' .$ga['ball_4'] .'|'.$ga['ball_5'] .'|'.$ga['powerball'];
                                $rt = $this->getRT($trendResult);
                                $trend->update([
                                    'rno' => $trendResult,
                                    'rt' => $rt,
                                    's' => 1,
                                    'PartialResultTime' => 0,
                                ]);

                                $rts = explode('|', $rt);

                                $totalBets = \VanguardLTE\GPGameBet::where([
                                    'p' => $trend->p,
                                    'dno' => $trend->dno,
                                    'status' => 0,
                                ])->whereIn('rt', $rts)->get();
                                $users = [];
                                foreach ($totalBets as $bet)
                                {
                                    $bet->update([
                                        'win' => $bet->amount * $bet->o
                                    ]);
                                }

                                //update user balance and add game_stat
                                $totalBets = \VanguardLTE\GPGameBet::where([
                                    'p' => $trend->p,
                                    'dno' => $trend->dno,
                                    'status' => 0,
                                ])->groupby('user_id')->selectRaw('user_id, sum(amount) as bet, sum(win) as win')->get();
                                $category = \VanguardLTE\Category::where(['provider' => null, 'shop_id' => 0, 'href' => 'gameplay'])->first();
                                
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
                                            'type' => 'pball',
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
                                break;
                            }
                        }
                    }
                    
                }
            }
            else
            {
                //update PartialResultTime
                $currentTrend = \VanguardLTE\GPGameTrend::where('s',0)->where('p',$this->GAMEID)->where('e','>', GamePlayController::gamePlayTimeFormat($currTime))->orderby('sl')->first();
                if ($currentTrend)
                {
                    $currentTrend->update([
                        'PartialResultTime' => ($currentTrend->e - GamePlayController::gamePlayTimeFormat($currTime)) * 2
                    ]);
                }
            }
            return null;

        }
        public function gameDetail(\VanguardLTE\StatGame $stat)
    {
            $rounds = explode('_',$stat->roundid);
            $dno = $rounds[1];
            $userbets = \VanguardLTE\GPGameBet::where([
                'p' => $this->GAMEID,
                'dno' => $dno,
                'user_id' => $stat->user_id
            ])->get();
            $trend = \VanguardLTE\GPGameTrend::where(
                [
                    'p' => $this->GAMEID,
                    'dno' => $dno
                ]
            )->first();
            return [
                'type' => 'powerball',
                'result' => $trend,
                'bets' => $userbets,
                'stat' => $stat
            ];

        }
    }
}

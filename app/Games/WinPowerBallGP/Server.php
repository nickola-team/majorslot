<?php 
namespace VanguardLTE\Games\WinPowerBallGP
{
    use VanguardLTE\Http\Controllers\Web\GameProviders\GamePlayController;
    use Illuminate\Support\Facades\Http;
    class Server
    {
        public  $GAMEID = 90;
        public  $GAMENAME = 'winpgb';
        public  $SELF_GEN_TREND = true;
        public  $GAME_TIME = 270;
        public  $GAME_PERIOD = 270;
        public  $LAST_LIMIT_TIME = 1;
        public  $VIDEO_URL = 'http://video.pp-99.com/';
        public  $VIDEO_WIDTH = 1200;
        public  $TREND_URL = 'http://video.pp-99.com/ajax.process_powerball.php';
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

        public function getCurrentTrend()
        {
            return null;
            $response = Http::get($this->TREND_URL);
            if ($response->ok()) {
                $data = $response->json();
                if ($data['error'] == 'OK') {
                    return $data;
                }
            }
            return null;
        }
        public function processCurrentTrend()
        {
            $data = $this->getCurrentTrend();
            if ($data!=null)
            {
                $dno = substr(date('Ymd'), 2) . $data['round'];
                $currentTrend = \VanguardLTE\GPGameTrend::where('p',$this->GAMEID)->where('dno',$dno)->first();
                if ($currentTrend) {
                    $bResult = false;
                    if ($currentTrend->s == 0)
                    {
                        foreach ($data['list'] as $ga)
                        {
                            if (substr($currentTrend->dno, 6) == $ga['ga_no'])
                            {
                                $game = \VanguardLTE\Game::where('name', 'WinPowerBallGP')->where('shop_id', 0)->first();
                                //process this round
                                $trendResult = $ga['ga_result_1'] .'|'.$ga['ga_result_2'] .'|' .$ga['ga_result_3'] .'|' .$ga['ga_result_4'] .'|'.$ga['ga_result_5'] .'|'.$ga['ga_result_6'];
                                $rt = $this->getRT($trendResult);
                                $currentTrend->update([
                                    'rno' => $trendResult,
                                    'rt' => $rt,
                                    's' => 1,
                                    'PartialResultTime' => $data['bar'],
                                ]);

                                $currentTrend = $currentTrend->fresh();

                                $rts = explode('|', $rt);

                                $totalBets = \VanguardLTE\GPGameBet::where([
                                    'p' => $currentTrend->p,
                                    'dno' => $currentTrend->dno,
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
                                    'p' => $currentTrend->p,
                                    'dno' => $currentTrend->dno,
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
                                            'game' =>  $currentTrend->game_id, 
                                            'type' => 'pball',
                                            'percent' => 0, 
                                            'percent_jps' => 0, 
                                            'percent_jpg' => 0, 
                                            'profit' => 0, 
                                            'denomination' => 0, 
                                            'shop_id' => $user->shop_id,
                                            'category_id' => isset($category)?$category->id:0,
                                            'game_id' => $game->original_id,
                                            'roundid' => $currentTrend->p . '_' . $currentTrend->dno,
                                        ]);
                                    }
                                }


                                $totalBets = \VanguardLTE\GPGameBet::where([
                                    'game_id' => $currentTrend->game_id,
                                    'dno' => $currentTrend->dno,
                                    'status' => 0,
                                ])->update(['status' => 1]);
                                $currentTrend->s = 6; // ???
                                $bResult = true;
                                break;
                            }
                        }
                    }

                    if ($bResult == false)
                    {
                        $currTime = time();
                        $ets = $currTime + $data['bar']/2;
                        $currentTrend->update([
                            'e' => GamePlayController::gamePlayTimeFormat($ets),
                            'PartialResultTime' => $data['bar']
                        ]);
                    }
                }
                else
                {
                    //create new trend
                    $currTime = time();
                    $ets = $currTime + $this->GAME_TIME;
                    \VanguardLTE\GPGameTrend::create(
                        [
                            'game_id' => strtolower($this->GAMENAME),
                            'p' => $this->GAMEID,
                            'a' => 1,
                            'dno' => $dno,
                            'e' => GamePlayController::gamePlayTimeFormat($ets),
                            'sl' => GamePlayController::gamePlayTimeFormat($currTime),
                            'rno' => null,
                            'rt' => null,
                            's' => 0,
                            'sDate' => explode('.',date(DATE_RFC3339_EXTENDED, $currTime))[0],
                            'eDate' => explode('.',date(DATE_RFC3339_EXTENDED, $ets))[0],
                            'PartialResultTime' => $data['bar']
                        ]
                    );
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

<?php 
namespace VanguardLTE\Games\XocDiaGP
{
    use VanguardLTE\Http\Controllers\Web\GameProviders\GamePlayController;
    class Server
    {
        public  $GAMEID = 13;
        public  $GAMENAME = 'xocdia';
        public  $RATE = [
            1 => 1.95, //odd
            2 => 1.95,  //even
            3 => 1.98,  //big
            4 => 1.98,  //small
            5 => 15,  //white4
            6 => 15,  //red4
            7 => 3.8, //white3
            8 => 3.8, //red3
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
            $r1 = mt_rand(1,2);
            $r2 = mt_rand(1,2);
            $r3 = mt_rand(1,2);
            $r4 = mt_rand(1,2);

            $result = "$r1|$r2|$r3|$r4";
            $rt = '';
            $sum = $r1 + $r2 + $r3 + $r4;
            //check odd / even
            if ($sum % 2 == 0 )
            {
                $rt = '2';
            }
            else
            {
                $rt = '1';
            }

            //check big / small
            if ($sum  < 6 )
            {
                $rt = $rt . '|4';
            }
            else if ($sum  > 6)
            {
                $rt = $rt . '|3';
            }
            else
            {
                $rt = $rt . '|0';
            }

            //check red4 / white4
            if ($sum  == 4 )
            {
                $rt = $rt . '|5';
            }
            else if ($sum  == 8)
            {
                $rt = $rt . '|6';
            }
            else
            {
                $rt = $rt . '|0';
            }

            //check red3 / white3
            if ($sum  == 5 )
            {
                $rt = $rt . '|7';
            }
            else if ($sum  == 7)
            {
                $rt = $rt . '|8';
            }
            else
            {
                $rt = $rt . '|0';
            }

            return [
                'rno' => $result,
                'rt' => $rt
            ];
        }

        public function processCurrentTrend()
        {
            $currTime = time();
            $currentTrend = \VanguardLTE\GPGameTrend::where('s',0)->where('p',$this->GAMEID)->orderby('sl')->first();
            if ($currentTrend && GamePlayController::gamePlayTimeFormat($currTime) > $currentTrend->e) {
                $trendResult = $this->generateResult();
                $currentTrend->update([
                    'rno' => $trendResult['rno'],
                    'rt' => $trendResult['rt'],
                    's' => 1,
                ]);
                $currentTrend = $currentTrend->fresh();

                $rts = explode('|', $trendResult['rt']);

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

                //process special case
                if ($rts[0] == 2 && $rts[2] == 0) //red2,white2
                {
                    $specialBets = \VanguardLTE\GPGameBet::where([
                        'p' => $currentTrend->p,
                        'dno' => $currentTrend->dno,
                        'status' => 0,
                    ])->groupby('user_id')->selectRaw('user_id, GROUP_CONCAT(rt SEPARATOR  ",") as grt')->get();
                    foreach ($specialBets as $bet)
                    {
                        $user_bets = explode(',', $bet->grt);
                        
                    }
                }

                //update user balance and add game_stat
                $totalBets = \VanguardLTE\GPGameBet::where([
                    'p' => $currentTrend->p,
                    'dno' => $currentTrend->dno,
                    'status' => 0,
                ])->groupby('user_id')->selectRaw('user_id, sum(amount) as bet, sum(win) as win')->get();
                $category = \VanguardLTE\Category::where(['provider' => null, 'shop_id' => 0, 'href' => 'gameplay'])->first();
                $game = \VanguardLTE\Game::where('name', 'XocDiaGP')->where('shop_id', 0)->first();
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
                            'type' => 'table',
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
                return $currentTrend;
            }

            return null;

        }
    }
}

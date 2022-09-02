<?php 
namespace VanguardLTE\Games\TaiXiuGP
{
    use VanguardLTE\Http\Controllers\Web\GameProviders\GamePlayController;
    class Server
    {
        public  $GAMEID = 12;
        public  $GAMENAME = 'taixiu';
        public  $RATE = [
            1 => 1.98,
            2 => 1.98
        ];
        public  $MULTILIMIT = [
            [
                'lo' => 150,
                'hi' => 150000,
            ],
            [
                'lo' => 7500,
                'hi' => 1500000,
            ],
            [
                'lo' => 15000,
                'hi' => 3000000,
            ]
        ];

        public  $AFMT = '3x1';

        public function generateResult()
        {
            $r1 = mt_rand(1,6);
            $r2 = mt_rand(1,6);
            $r3 = mt_rand(1,6);
            $result = "$r1|$r2|$r3";
            $rt = 0;
            if ($r1 == $r2 && $r2 == $r3)
            {
                $rt = 0;
            }
            else if (($r1+$r2+$r3)>=11)
            {
                $rt = 1;
            }
            else
            {
                $rt = 2;
            }
            return [
                'rno' => $result,
                'rt' => $rt];
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

                $totalBets = \VanguardLTE\GPGameBet::where([
                    'p' => $currentTrend->p,
                    'dno' => $currentTrend->dno,
                    'status' => 0,
                    'rt' => $currentTrend->rt
                ])->get();
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
                $game = \VanguardLTE\Game::where('name', 'TaiXiuGP')->where('shop_id', 0)->first();
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

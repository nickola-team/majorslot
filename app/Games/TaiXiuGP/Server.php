<?php 
namespace VanguardLTE\Games\TaiXiuGP
{
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

        public function processBetRecords($trendID)
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
                    'win' => $bet->amount * $bet->o
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
    }
}

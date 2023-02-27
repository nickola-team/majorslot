<?php

namespace VanguardLTE\Console\Commands;

use Illuminate\Console\Command;
use VanguardLTE\User;
use VanguardLTE\Game;
use Exception;
use Log;

class MonitorBetWin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:betwin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    const MAX_ODD = 300; // odd
    const MAX_WIN = 5000000; // odd
    const TIME_PERIOD = 1; // 1hour

    const MSG_TITLE_1 = '고배당 당첨알림';
    const MSG_CONTENT_1 = '<a href="/replace_with_backend/player/gamehistory?player=%s">%s</a> [%s 매장]님이 %s에 %s게임에서 %.2f배 당첨되었습니다';
    const MSG_TITLE_2 = '고액 당첨알림';
    const MSG_CONTENT_2 = '<a href="/replace_with_backend/player/gamehistory?player=%s">%s</a> [%s 매장]님이 %s~%s사이에 %s원의 수익이 났습니다';
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $endTime = date('Y-m-d H:i:s');
        $startTime = date('Y-m-d H:i:s', strtotime("-".self::TIME_PERIOD. " hours"));

        $max_odd = self::MAX_ODD;
        $odd = \VanguardLTE\Settings::where('key', 'MaxOdd')->first();
        if ($odd)
        {
            $max_odd = $odd->value;
        }

        $max_win = self::MAX_WIN;
        $win = \VanguardLTE\Settings::where('key', 'MaxWin')->first();
        if ($win)
        {
            $max_win = $win->value;
        }

        if ($max_odd > 0) {

            //max odd check
            $query = "SELECT user_id, sum(bet), sum(win), sum(win)/sum(bet) as odd, roundid FROM w_stat_game WHERE bet>0 and date_time >= '" . $startTime . "' AND date_time<='" . $endTime . "' GROUP BY user_id, roundid ORDER BY odd DESC";
            $stat_games = \DB::select($query);
            foreach($stat_games as $stat_game) {
                if ($stat_game->odd >= $max_odd)
                {
                    $user = \VanguardLTE\User::where('id', $stat_game->user_id)->first();
                    $game_round = \VanguardLTE\StatGame::where('roundid', $stat_game->roundid)->first();
                    
                    if ($user)
                    {
                        //send message toadmin
                        $partner = \VanguardLTE\User::where('role_id', 9)->first();
                        \VanguardLTE\Message::create([
                            'user_id' => $partner->id,
                            'writer_id' => 0,
                            'title' => self::MSG_TITLE_1 . ' - [' . $user->username .'] 유저 - '.  number_format($stat_game->odd,2) .'배 당첨' ,
                            'content' => sprintf(self::MSG_CONTENT_1, $user->username,$user->username, $user->shop->name, $game_round->date_time, $game_round->category->title ,$stat_game->odd)
                        ]);
                    }
                }
                else
                {
                    break;
                }
            }
        }

        if ($max_win > 0)
        {
            //max win check
            $query = "SELECT user_id, sum(bet), sum(win), sum(win-bet) as betwin FROM w_stat_game WHERE date_time >= '" . $startTime . "' AND date_time<='" . $endTime . "' GROUP BY user_id ORDER BY betwin DESC";
            $stat_games = \DB::select($query);

            foreach($stat_games as $stat_game) {
                if ($stat_game->betwin >= $max_win)
                {
                    $user = \VanguardLTE\User::where('id', $stat_game->user_id)->first();
                    if ($user)
                    {
                        //send message to this user's shops, comaster and admin
                        $partner = \VanguardLTE\User::where('role_id', 9)->first();
                        \VanguardLTE\Message::create([
                            'user_id' => $partner->id,
                            'writer_id' => 0,
                            'title' => self::MSG_TITLE_2 . ' - [' . $user->username .'] 유저 - '.  number_format($stat_game->betwin) .'원 수익' ,
                            'content' => sprintf(self::MSG_CONTENT_2, $user->username,$user->username, $user->shop->name, $startTime, $endTime ,number_format($stat_game->betwin))
                        ]);
                    }
                }
                else
                {
                    break;
                }
            }

        }

    }
}

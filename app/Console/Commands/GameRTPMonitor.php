<?php

namespace VanguardLTE\Console\Commands;

use Illuminate\Console\Command;
use VanguardLTE\User;
use VanguardLTE\Game;
use Exception;
use Log;

class GameRTPMonitor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:rtp';

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

    const MAX_BET = 3000000;
    const MIN_RTP = 0.9;
    const MAX_RTP = 1.00;
    const LINE10_STEP = 1;
    const BONUS3_STEP = 10;
    const BONUS10_STEP = 5;
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
        $today = date('Y-m-d', strtotime('-1 days'));
        //if rpt is low
        $todayGames = \VanguardLTE\GameSummary::where('totalbet', '>=', self::MAX_BET)->where('date',  $today)->where('type','daily')->get();
        foreach ($todayGames as $gs)
        {
            $rtp = $gs->totalwin/$gs->totalbet;
            if ($gs->game)
            {
                $gamewin = $gs->game->game_win;
                if ($gamewin){
                    if ($rtp<self::MIN_RTP)
                    {
                        $bonus3 = $gamewin->winbonus3;
                        $numbers = explode(',', $bonus3);
                        $newnumbers = [];
                        foreach ($numbers as $n)
                        {
                            if ($n > self::BONUS3_STEP)
                            {
                                $n = $n - self::BONUS3_STEP;
                            }
                            $newnumbers[] = $n;
                        }
                        $gamewin->winbonus3 = implode(',', $newnumbers);

                        $line10 = $gamewin->winline10;
                        $numbers = explode(',', $line10);
                        $newnumbers = [];
                        foreach ($numbers as $n)
                        {
                            if ($n > self::LINE10_STEP)
                            {
                                $n = $n - self::LINE10_STEP;
                            }
                            $newnumbers[] = $n;
                        }
                        $gamewin->winline10 = implode(',', $newnumbers);

                        $bonus10 = $gamewin->winbonus10;
                        $numbers = explode(',', $bonus10);
                        $newnumbers = [];
                        foreach ($numbers as $n)
                        {
                            if ($n > self::BONUS10_STEP)
                            {
                                $n = $n - self::BONUS10_STEP;
                            }
                            $newnumbers[] = $n;
                        }
                        $gamewin->winbonus10 = implode(',', $newnumbers);

                        $gamewin->save();
                        Log::info($today .' : ' . $gs->game->name . ' RTP=' . number_format($gs->totalwin/$gs->totalbet, 2). '. Decreased bonus3 numbers.');
                        $this->info($today .' : ' . $gs->game->name . ' RTP=' . number_format($gs->totalwin/$gs->totalbet, 2). '. Decreased bonus3 numbers.');
                    }
                    else if ($rtp>self::MAX_RTP)
                    {
                        $bonus3 = $gamewin->winbonus3;
                        $numbers = explode(',', $bonus3);
                        $newnumbers = [];
                        foreach ($numbers as $n)
                        {
                            $n = $n + self::BONUS3_STEP;
                            $newnumbers[] = $n;
                        }
                        $gamewin->winbonus3 = implode(',', $newnumbers);

                        $line10 = $gamewin->winline10;
                        $numbers = explode(',', $line10);
                        $newnumbers = [];
                        foreach ($numbers as $n)
                        {
                            $n = $n + self::LINE10_STEP;
                            $newnumbers[] = $n;
                        }
                        $gamewin->winline10 = implode(',', $newnumbers);

                        $bonus10 = $gamewin->winbonus10;
                        $numbers = explode(',', $bonus10);
                        $newnumbers = [];
                        foreach ($numbers as $n)
                        {
                            $n = $n + self::BONUS10_STEP;
                            $newnumbers[] = $n;
                        }
                        $gamewin->winbonus10 = implode(',', $newnumbers);

                        $gamewin->save();
                        Log::info($today .' : ' . $gs->game->name . ' RTP=' . number_format($gs->totalwin/$gs->totalbet, 2). '. Increased bonus3 numbers.');
                        $this->info($today .' : ' . $gs->game->name . ' RTP=' . number_format($gs->totalwin/$gs->totalbet, 2). '. Increased bonus3 numbers.');
                    }
                    
                }
            }
        }
    }

}

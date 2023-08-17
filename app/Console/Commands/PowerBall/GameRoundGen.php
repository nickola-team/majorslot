<?php

namespace VanguardLTE\Console\Commands\PowerBall;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Exception;
use Log;

class GameRoundGen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pbgame:genround {date=next}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Powerball Game Round Generator';


    /**
     * Create a new command instance.
     *
     * @return void
     */
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
        
        set_time_limit(0);
        $date = $this->argument('date');
        if ($date == 'next')
        {
            $date = date('Y-m-d', strtotime("+1 days"));
        }

        


        $categories = \VanguardLTE\Category::where(['shop_id' => 0,'site_id'=>0,'type' => 'pball'])->get();
        foreach ($categories as $cat)
        {
            $games = $cat->games;
            if ($games){
                foreach ($games as $g)
                {
                    $minigame = $g->game;
                    if ($minigame)
                    {
                        $object = '\VanguardLTE\Http\Controllers\Web\GameParsers\PowerBall\\' . $minigame->name;
                        if (!class_exists($object))
                        {
                            continue;
                        }
                        $this->info("Begin genRound of " . $date . " for " . $minigame->name);
                        $parser = new $object($minigame->original_id);
                        $res = $parser->generateGameRounds($date);
                        $this->info("End genRound");
                    }
                }
            }
        }
        
    }
}

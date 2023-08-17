<?php

namespace VanguardLTE\Console\Commands\PowerBall;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Exception;
use Log;

class GameList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pbgame:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Powerball Game List';


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
        $miniGames = [];
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
                        $miniGames[] = [
                            'id' => $minigame->original_id,
                            'name' => $minigame->name,
                        ];
                    }
                }
            }
        }
        $this->info(json_encode($miniGames));

        
    }
}

<?php

namespace VanguardLTE\Console\Commands\PowerBall;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Exception;
use Log;

class GameRoundProcess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pbgame:processround {gameid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Powerball Game Round Processor';


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
        $gameid = $this->argument('gameid');
        $game = \VanguardLTE\Game::where('id', $gameid)->first();
        if ($game)
        {
            $this->info("Begin processRound " . $game->title);
            $object = '\VanguardLTE\Http\Controllers\Web\GameParsers\PowerBall\\' . $game->name;
            if (!class_exists($object))
            {
                return;
            }
            $parser = new $object($gameid);
            $res = $parser->processRound();
        }
        
        $this->info("End processRound");
    }
}

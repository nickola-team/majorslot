<?php 
namespace VanguardLTE\Http\Controllers\Web\GameParsers\PowerBall
{
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Log;
    class EOS5minPB extends EOS3minPB
    {
        public $ROUND_URL = 'https://ntry.com/data/json/games/eos_powerball/5min/recent_result.json';
        public  $GAME_TIME = 294;
        public  $GAME_PERIOD = 300;
        public  $LAST_LIMIT_TIME = 10;
        public  $VIDEO_URL = 'https://ntry.com/scores/eos_powerball/5min/main.php';
    }
}

<?php 
namespace VanguardLTE\Games\FortuneDragonPM
{
    class GameReel
    {
        public $reelsStrip = [
            'reelStrip0_0' => [],
            'reelStrip0_1' => [],
            'reelStrip0_2' => [],
            'reelStrip0_3' => [],
            'reelStrip0_4' => [],

            'reelStrip1_0' => [],
            'reelStrip1_1' => [],
            'reelStrip1_2' => [],
            'reelStrip1_3' => [],
            'reelStrip1_4' => [],
        ];
        
        public function __construct()
        {
            $temp = file(base_path() . '/app/Games/FortuneDragonPM/reels.txt');
            foreach( $temp as $str ) 
            {
                $str = explode('=', $str);
                if( isset($this->reelsStrip[$str[0]]) ) 
                {
                    $data = explode(',', $str[1]);
                    foreach( $data as $elem ) 
                    {
                        $elem = trim($elem);
                        if( $elem != '' ) 
                        {
                            $this->reelsStrip[$str[0]][] = $elem;
                        }
                    }
                }
            }
        }
    }

}

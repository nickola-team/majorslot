<?php 
namespace VanguardLTE\Games\UltraHoldandSpinPM
{
    class GameReel
    {
        public $reelsStrip = [
            'reelStrip0_0' => [],
            'reelStrip0_1' => [],
            'reelStrip0_2' => [],

            'reelStrip1_0' => [],
            'reelStrip1_1' => [],
            'reelStrip1_2' => [],
        ];
        
        public function __construct()
        {
            $temp = file(base_path() . '/app/Games/UltraHoldandSpinPM/reels.txt');
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

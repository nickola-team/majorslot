<?php 
namespace VanguardLTE\Games\PowerOfThorMegawaysPM
{
    class GameReel
    {
        public $reelsStrip = [
            'reelStrip0_0' => [],

            'reelStrip1_0' => [],
            'reelStrip1_1' => [],
            'reelStrip1_2' => [],
            'reelStrip1_3' => [],
            'reelStrip1_4' => [],
            'reelStrip1_5' => [],

            'reelStrip2_0' => [],

            'reelStrip3_0' => [],
            'reelStrip3_1' => [],
            'reelStrip3_2' => [],
            'reelStrip3_3' => [],
            'reelStrip3_4' => [],
            'reelStrip3_5' => [],

            'reelStrip4_0' => [],

            'reelStrip5_0' => [],
            'reelStrip5_1' => [],
            'reelStrip5_2' => [],
            'reelStrip5_3' => [],
            'reelStrip5_4' => [],
            'reelStrip5_5' => [],

            'reelStrip6_0' => [],

            'reelStrip7_0' => [],
            'reelStrip7_1' => [],
            'reelStrip7_2' => [],
            'reelStrip7_3' => [],
            'reelStrip7_4' => [],
            'reelStrip7_5' => [],

            'reelStrip8_0' => [],

            'reelStrip9_0' => [],
            'reelStrip9_1' => [],
            'reelStrip9_2' => [],
            'reelStrip9_3' => [],
            'reelStrip9_4' => [],
            'reelStrip9_5' => [],
        ];
        
        public function __construct()
        {
            $temp = file(base_path() . '/app/Games/PowerOfThorMegawaysPM/reels.txt');
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

<?php 
namespace VanguardLTE\Games\AztecGoldMegawaysISB
{
    class GameReel
    {
        public $reelsStrip = [
            'reelStrip0' => [], 
            'reelStrip1' => [], 
            'reelStrip2' => [], 
            'reelStrip3' => [], 
            'reelStrip4' => [], 
            'reelStrip5' => [], 
            'reelStrip6' => []
        ];
        public $reelsStripBonus = [
            'reelStripBonus0' => [], 
            'reelStripBonus1' => [], 
            'reelStripBonus2' => [], 
            'reelStripBonus3' => [], 
            'reelStripBonus4' => [], 
            'reelStripBonus5' => [], 
            'reelStripBonus6' => []
        ];
        public $reel_set = 8;
        public $reel_set_bonus = 12;
        public function __construct()
        {
            $this->reel_set = 8;//mt_rand(0, 6) * 2;
            $this->$reel_set_bonus = 12; //mt_rand(0, 6) * 2;

            $temp = file(base_path() . '/app/Games/BuffaloKingMegawaysPM/reels.txt');
            foreach( $temp as $str ) 
            {
                $str = explode('=', $str);
                $r_set = explode('_', $str[0]);
                if ($r_set[1] == $this->reel_set || $r_set[1] == $this->reel_set+1) {
                    if( isset($this->reelsStrip[$r_set[0]]) ) 
                    {
                        $data = explode(',', $str[1]);
                        foreach( $data as $elem ) 
                        {
                            $elem = trim($elem);
                            if( $elem != '' ) 
                            {
                                $this->reelsStrip[$r_set[0]][] = $elem;
                            }
                        }
                    }
                }

                if ($r_set[1] == $this->reel_set_bonus || $r_set[1] == $this->reel_set_bonus+1) {
                    $rpl_bonus = str_replace("reelStrip","reelStripBonus", $r_set[0]);
                    if( isset($this->reelsStripBonus[$rpl_bonus]) ) 
                    {
                        $data = explode(',', $str[1]);
                        foreach( $data as $elem ) 
                        {
                            $elem = trim($elem);
                            if( $elem != '' ) 
                            {
                                $this->reelsStripBonus[$rpl_bonus][] = $elem;
                            }
                        }
                    }
                }

            }
        }
    }

}

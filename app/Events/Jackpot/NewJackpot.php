<?php

namespace VanguardLTE\Events\Jackpot;

use VanguardLTE\HappyHourUser;

class NewJackpot
{
    /**
     * @var Returns
     */
    protected $NewJackpot;

    public function __construct(HappyHourUser $NewJackpot)
    {
        $this->NewJackpot = $NewJackpot;
    }

    /**
     * @Jackpot Jackpots
     */
    public function getNewJackpot()
    {
        return $this->NewJackpot;
    }
}

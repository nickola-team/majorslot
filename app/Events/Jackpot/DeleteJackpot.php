<?php

namespace VanguardLTE\Events\Jackpot;

use VanguardLTE\HappyHourUser;

class DeleteJackpot
{
    /**
     * @var Returns
     */
    protected $DeleteJackpot;

    public function __construct(HappyHourUser $DeleteJackpot)
    {
        $this->DeleteJackpot = $DeleteJackpot;
    }

    /**
     * @Jackpot Jackpots
     */
    public function getDeleteJackpot()
    {
        return $this->DeleteJackpot;
    }
}

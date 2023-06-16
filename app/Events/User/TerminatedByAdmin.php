<?php

namespace VanguardLTE\Events\User;

use VanguardLTE\User;

class TerminatedByAdmin
{
    /**
     * @var User
     */
    protected $terminatedUser;

    public function __construct(User $terminatedUser)
    {
        $this->terminatedUser = $terminatedUser;
    }

    /**
     * @return User
     */
    public function getterminatedUser()
    {
        return $this->terminatedUser;
    }
}

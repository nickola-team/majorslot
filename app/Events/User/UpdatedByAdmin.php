<?php

namespace VanguardLTE\Events\User;

use VanguardLTE\User;

class UpdatedByAdmin
{
    /**
     * @var User
     */
    protected $updatedUser;
    protected $mention;

    public function __construct(User $updatedUser, $mention='')
    {
        $this->updatedUser = $updatedUser;
        $this->mention = $mention;
    }

    /**
     * @return User
     */
    public function getUpdatedUser()
    {
        return $this->updatedUser;
    }
    public function getMention()
    {
        return $this->mention;
    }
}

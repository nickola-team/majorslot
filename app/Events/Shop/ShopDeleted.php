<?php

namespace VanguardLTE\Events\Shop;

use VanguardLTE\Shop;

class ShopDeleted
{
    /**
     * @var User
     */
    protected $deletedShop;

    public function __construct(Shop $deletedShop)
    {
        $this->deletedShop = $deletedShop;
    }

    /**
     * @return User
     */
    public function getDeletedShop()
    {
        return $this->deletedShop;
    }
}

<?php

namespace VanguardLTE\Listeners;

use VanguardLTE\Activity;
use VanguardLTE\Events\Shop\ShopEdited;
use VanguardLTE\Events\Shop\ShopDeleted;
use VanguardLTE\Events\Shop\ShopCreated;
use VanguardLTE\Services\Logging\UserActivity\Logger;

class ShopEventsSubscriber
{

    private $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function onShopCreate(ShopCreated $event)
    {
        $shop = $event->getCreatedshop();

        $text = '매장생성 ' . $shop->name . ' ';

        $this->logger->log($text);
    }

    public function onShopEdit(ShopEdited $event)
    {
        $shop = $event->getEditedshop();
        $changes = $shop->getChanges();

        $text = '매장업데이트 / ' . $shop->name . ' / ';

        if( count($changes)){
            foreach($changes AS $key=>$change){
                $text .= $key . '=' . $change . ', ';
            }
        }

        $text = str_replace('  ', ' ', $text);
        $text = trim($text, ' ');
        $text = trim($text, '/');
        $text = trim($text, ',');

        $this->logger->log($text);
    }

    public function onShopDelete(ShopDeleted $event)
    {
        $shop = $event->getDeletedshop();

        $text = '매장 삭제  ' . $shop->name . ' ';

        $this->logger->log($text);
    }


    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $class = 'VanguardLTE\Listeners\ShopEventsSubscriber';

        $events->listen(ShopEdited::class, "{$class}@onShopEdit");
        $events->listen(ShopDeleted::class, "{$class}@onShopDelete");
        $events->listen(ShopCreated::class, "{$class}@onShopCreate");
        
    }
}

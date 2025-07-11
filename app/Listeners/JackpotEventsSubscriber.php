<?php

namespace VanguardLTE\Listeners;

use VanguardLTE\Activity;
use VanguardLTE\Events\Jackpot\NewJackpot;
use VanguardLTE\Events\Jackpot\JackpotEdited;
use VanguardLTE\Events\Jackpot\DeleteJackpot;
use VanguardLTE\Events\User\UserEventContract;
use VanguardLTE\Services\Logging\UserActivity\Logger;

class JackpotEventsSubscriber
{
    /**
     * @var UserActivityLogger
     */
    private $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function onNewJackPot(NewJackPot $event)
    {
        $jp = $event->getNewJackpot();
        $text = 'New HappyUser / ' . $jp->user->username . ' / ' . $jp->total_bank;
        $this->logger->log($text);
    }

    public function onDeleteJackPot(DeleteJackpot $event)
    {
        $jp = $event->getDeleteJackpot();
        $text = 'Delete HappyUser / ' .( $jp->user?$jp->user->username:'Unknown') . ' / ' . $jp->total_bank;
        $this->logger->log($text);
    }

    public function onJackpotEdited(JackpotEdited $event)
    {
        $jackpot = $event->getEditedJackpot();
        $changes = $jackpot->getChanges();

        $text = 'Update Jackpot / ' . $jackpot->name . ' / ';

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

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $class = 'VanguardLTE\Listeners\JackpotEventsSubscriber';

        $events->listen(NewJackpot::class, "{$class}@onNewJackpot");
        $events->listen(JackpotEdited::class, "{$class}@onJackpotEdited");
        $events->listen(DeleteJackpot::class, "{$class}@onDeleteJackpot");
    }
}

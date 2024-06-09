<?php

namespace TeleBot\System\Telegram\Events;

use Exception;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Types\IncomingBusinessConnection;

class BusinessConnection implements IEvent
{

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function apply(array $event): IncomingBusinessConnection|bool
    {
        if (!array_key_exists('business_connection', $event)) return false;
        return new IncomingBusinessConnection($event['business_connection']);
    }
}
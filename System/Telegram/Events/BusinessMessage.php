<?php

namespace TeleBot\System\Telegram\Events;

use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Types\IncomingMessage;

class BusinessMessage implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingMessage|bool
    {
        if (!array_key_exists('business_message', $event)) return false;
        return new IncomingMessage($event['business_message']);
    }
}
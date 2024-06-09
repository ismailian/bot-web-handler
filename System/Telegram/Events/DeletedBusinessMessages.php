<?php

namespace TeleBot\System\Telegram\Events;

use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Types\IncomingBusinessMessagesDeleted;

class DeletedBusinessMessages implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingBusinessMessagesDeleted|bool
    {
        if (!array_key_exists('deleted_business_messages', $event)) return false;
        return new IncomingBusinessMessagesDeleted($event['deleted_business_messages']);
    }
}
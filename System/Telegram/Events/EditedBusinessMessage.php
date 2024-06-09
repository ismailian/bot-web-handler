<?php

namespace TeleBot\System\Telegram\Events;

use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Types\IncomingMessage;

class EditedBusinessMessage implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingMessage|bool
    {
        if (!array_key_exists('edited_business_message', $event)) return false;
        return new IncomingMessage($event['edited_business_message']);
    }
}
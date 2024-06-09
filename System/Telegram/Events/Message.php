<?php

namespace TeleBot\System\Telegram\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Types\IncomingMessage;

#[Attribute(Attribute::TARGET_METHOD)]
class Message implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingMessage|bool
    {
        if (!array_key_exists('message', $event)) return false;
        return new IncomingMessage($event['message']);
    }
}
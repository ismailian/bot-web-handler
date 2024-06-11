<?php

namespace TeleBot\System\Telegram\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Traits\Messageable;
use TeleBot\System\Telegram\Types\IncomingMessage;

#[Attribute(Attribute::TARGET_METHOD)]
class Message implements IEvent
{

    use Messageable;

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingMessage|bool
    {
        if (!$this->isMessage(array_keys($event))) return false;
        return new IncomingMessage($event[$this->first(array_keys($event))]);
    }
}
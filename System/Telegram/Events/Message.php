<?php

namespace Telebot\System\Telegram\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;

#[Attribute(Attribute::TARGET_METHOD)]
class Message implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): bool
    {
        return isset($event['data']['message']);
    }
}
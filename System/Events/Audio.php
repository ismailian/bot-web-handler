<?php

namespace TeleBot\System\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;

#[Attribute(Attribute::TARGET_METHOD)]
class Audio implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): bool
    {
        return isset($event['data']['message']) && isset($event['data']['message']['audio']);
    }
}
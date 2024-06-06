<?php

namespace TeleBot\System\Telegram\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;

#[Attribute(Attribute::TARGET_METHOD)]
class Poll implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): bool
    {
        $key = isset($event['edited_message']) ? 'edited_message' : 'message';
        return isset($event[$key]['poll']);
    }
}
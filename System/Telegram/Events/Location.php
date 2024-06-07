<?php

namespace TeleBot\System\Telegram\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Types\IncomingLocation;

#[Attribute(Attribute::TARGET_METHOD)]
class Location implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingLocation|bool
    {
        $key = isset($event['edited_message']) ? 'edited_message' : 'message';
        if (!array_key_exists('location', $event[$key])) return false;

        return new IncomingLocation($event[$key]['location']);
    }
}
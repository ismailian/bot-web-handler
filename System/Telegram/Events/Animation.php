<?php

namespace TeleBot\System\Telegram\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Types\IncomingAnimation;

#[Attribute(Attribute::TARGET_METHOD)]
class Animation implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingAnimation|bool
    {
        $key = isset($event['edited_message']) ? 'edited_message' : 'message';
        return new IncomingAnimation($event[$key]['animation']);
    }
}
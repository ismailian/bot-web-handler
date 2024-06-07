<?php

namespace TeleBot\System\Telegram\Events;

use Attribute;
use Exception;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Types\IncomingPoll;

#[Attribute(Attribute::TARGET_METHOD)]
class Poll implements IEvent
{

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function apply(array $event): IncomingPoll|bool
    {
        $key = isset($event['edited_message']) ? 'edited_message' : 'message';
        if (!array_key_exists('poll', $event[$key])) return false;

        return new IncomingPoll($event[$key]['poll']);
    }
}
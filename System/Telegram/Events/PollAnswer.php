<?php

namespace TeleBot\System\Telegram\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Types\IncomingPollAnswer;

#[Attribute(Attribute::TARGET_METHOD)]
class PollAnswer implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingPollAnswer|bool
    {
        if (!array_key_exists('poll_answer', $event)) return false;
        return new IncomingPollAnswer($event['poll_answer']);
    }
}
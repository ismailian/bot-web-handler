<?php

namespace TeleBot\System\Telegram\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Types\IncomingChosenInlineResult;

#[Attribute(Attribute::TARGET_METHOD)]
class ChosenInlineResult implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingChosenInlineResult|bool
    {
        if (!array_key_exists('chosen_inline_result', $event)) return false;
        return new IncomingChosenInlineResult($event['chosen_inline_result']);
    }
}
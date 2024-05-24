<?php

namespace Telebot\System\Telegram\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;

#[Attribute(Attribute::TARGET_METHOD)]
class ChosenInlineResult implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): bool
    {
        return false;
    }
}
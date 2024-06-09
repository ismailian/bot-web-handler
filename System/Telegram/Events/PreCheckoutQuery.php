<?php

namespace TeleBot\System\Telegram\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Types\IncomingPreCheckoutQuery;

#[Attribute(Attribute::TARGET_METHOD)]
class PreCheckoutQuery implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingPreCheckoutQuery|bool
    {
        if (!array_key_exists('pre_checkout_query', $event)) return false;
        return new IncomingPreCheckoutQuery($event['pre_checkout_query']);
    }
}
<?php

namespace TeleBot\System\Telegram\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Types\IncomingShippingQuery;

#[Attribute(Attribute::TARGET_METHOD)]
class ShippingQuery implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingShippingQuery|bool
    {
        if (!array_key_exists('shipping_query', $event)) return false;
        return new IncomingShippingQuery($event['shipping_query']);
    }
}
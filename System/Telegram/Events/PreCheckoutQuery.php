<?php

namespace TeleBot\System\Telegram\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;

#[Attribute(Attribute::TARGET_METHOD)]
class PreCheckoutQuery implements IEvent
{

    /**
     * default constructor
     */
    public function __construct() {}

    /**
     * @inheritDoc
     */
    public function apply(array $event): bool
    {
        return isset($event['data']['pre_checkout_query']);
    }
}
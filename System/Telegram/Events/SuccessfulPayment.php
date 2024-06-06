<?php

namespace TeleBot\System\Telegram\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Types\IncomingSuccessfulPayment;

#[Attribute(Attribute::TARGET_METHOD)]
class SuccessfulPayment implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingSuccessfulPayment|bool
    {
        if (!array_key_exists('message', $event)) return false;
        if (!array_key_exists('successful_payment', $event['message'])) return false;

        return new IncomingSuccessfulPayment(
            $event['message']['successful_payment']
        );
    }
}
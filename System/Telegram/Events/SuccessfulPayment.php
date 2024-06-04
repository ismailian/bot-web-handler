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
        if (!array_key_exists('message', $event['data'])) return false;
        if (!array_key_exists('successful_payment', $event['data']['message'])) return false;

        return new IncomingSuccessfulPayment(
            $event['data']['message']['successful_payment']
        );
    }
}
<?php

namespace TeleBot\System\Telegram\Events\Messages;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Traits\Messageable;
use TeleBot\System\Telegram\Types\IncomingSuccessfulPayment;

#[Attribute(Attribute::TARGET_METHOD)]
class SuccessfulPayment implements IEvent
{

    use Messageable;

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingSuccessfulPayment|bool
    {
        if (!$this->isMessage(array_keys($event))) return false;

        $key = $this->first(array_keys($event));
        if (!array_key_exists('successful_payment', $event[$key])) return false;

        return new IncomingSuccessfulPayment($event[$key]['successful_payment']);
    }
}
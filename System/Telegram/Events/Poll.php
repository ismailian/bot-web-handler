<?php

namespace TeleBot\System\Telegram\Events;

use Attribute;
use Exception;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Traits\Messageable;
use TeleBot\System\Telegram\Types\IncomingPoll;

#[Attribute(Attribute::TARGET_METHOD)]
class Poll implements IEvent
{

    use Messageable;

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function apply(array $event): IncomingPoll|bool
    {
        if (array_key_exists('poll', $event)) return new IncomingPoll($event['poll']);
        if (!$this->isMessage(array_keys($event))) return false;

        $key = $this->first(array_keys($event));
        if (!array_key_exists('poll', $event[$key])) return false;

        return new IncomingPoll($event[$key]['poll']);
    }
}
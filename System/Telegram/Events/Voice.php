<?php

namespace TeleBot\System\Telegram\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Types\IncomingVoice;

#[Attribute(Attribute::TARGET_METHOD)]
class Voice implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingVoice|bool
    {
        $key = isset($event['edited_message']) ? 'edited_message' : 'message';
        if (!array_key_exists('voice', $event[$key])) return false;

        return new IncomingVoice($event[$key]['voice']);
    }
}
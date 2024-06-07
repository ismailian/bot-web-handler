<?php

namespace TeleBot\System\Telegram\Events;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Types\IncomingAudio;

#[Attribute(Attribute::TARGET_METHOD)]
class Audio implements IEvent
{

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingAudio|bool
    {
        $key = isset($event['edited_message']) ? 'edited_message' : 'message';
        if (!array_key_exists('audio', $event[$key])) return false;

        return new IncomingAudio($event[$key]['audio']);
    }
}
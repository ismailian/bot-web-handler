<?php

namespace TeleBot\System\Telegram\Events\Messages;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Traits\Messageable;
use TeleBot\System\Telegram\Types\IncomingVideoChatEnded;

#[Attribute(Attribute::TARGET_METHOD)]
class VideoChatEnded implements IEvent
{

    use Messageable;

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingVideoChatEnded|bool
    {
        if (!$this->isMessage(array_keys($event))) return false;
        if (array_key_exists('video_chat_ended', $event[$this->first(array_keys($event))])) {
            return new IncomingVideoChatEnded($event[$this->first(array_keys($event))]);
        }

        return false;
    }
}
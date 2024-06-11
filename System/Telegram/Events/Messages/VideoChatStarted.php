<?php

namespace TeleBot\System\Telegram\Events\Messages;

use Attribute;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Traits\Messageable;
use TeleBot\System\Telegram\Types\IncomingVideoChatStarted;

#[Attribute(Attribute::TARGET_METHOD)]
class VideoChatStarted implements IEvent
{

    use Messageable;

    /**
     * @inheritDoc
     */
    public function apply(array $event): IncomingVideoChatStarted|bool
    {
        if (!$this->isMessage(array_keys($event))) return false;
        if (array_key_exists('video_chat_started', $event[$this->first(array_keys($event))])) {
            return new IncomingVideoChatStarted($event[$this->first(array_keys($event))]);
        }

        return false;
    }
}
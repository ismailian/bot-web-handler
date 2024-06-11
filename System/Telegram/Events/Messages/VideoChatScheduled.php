<?php

namespace TeleBot\System\Telegram\Events\Messages;

use Attribute;
use Exception;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Traits\Messageable;
use TeleBot\System\Telegram\Types\IncomingVideoChatScheduled;

#[Attribute(Attribute::TARGET_METHOD)]
class VideoChatScheduled implements IEvent
{

    use Messageable;

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function apply(array $event): IncomingVideoChatScheduled|bool
    {
        if (!$this->isMessage(array_keys($event))) return false;
        if (array_key_exists('video_chat_scheduled', $event[$this->first(array_keys($event))])) {
            return new IncomingVideoChatScheduled($event[$this->first(array_keys($event))]);
        }

        return false;
    }
}
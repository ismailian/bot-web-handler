<?php

namespace TeleBot\System\Telegram\Events;

use Exception;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Types\IncomingMessageReactionUpdated;

class MessageReaction implements IEvent
{

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function apply(array $event): IncomingMessageReactionUpdated|bool
    {
        if (!array_key_exists('message_reaction', $event)) return false;
        return new IncomingMessageReactionUpdated($event['message_reaction']);
    }
}
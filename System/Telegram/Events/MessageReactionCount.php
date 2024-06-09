<?php

namespace TeleBot\System\Telegram\Events;

use Exception;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Types\IncomingMessageReactionCountUpdated;

class MessageReactionCount implements IEvent
{

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function apply(array $event): IncomingMessageReactionCountUpdated|bool
    {
        if (!array_key_exists('message_reaction_count', $event)) return false;
        return new IncomingMessageReactionCountUpdated($event['message_reaction_count']);
    }
}
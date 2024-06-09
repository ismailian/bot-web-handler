<?php

namespace TeleBot\System\Telegram\Events;

use Exception;
use TeleBot\System\Interfaces\IEvent;
use TeleBot\System\Telegram\Types\IncomingChatBoostUpdated;

class ChatBoost implements IEvent
{

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function apply(array $event): IncomingChatBoostUpdated|bool
    {
        if (!array_key_exists('chat_boot', $event)) return false;
        return new IncomingChatBoostUpdated($event['chat_boot']);
    }
}
<?php

namespace TeleBot\System\Telegram\Traits;

use TeleBot\System\Telegram\Types\IncomingMessage;

/**
 * This trait allows direct replies to an incoming message
 */
trait CanReply
{

    /**
     * Reply with a text message
     *
     * @param string $message
     * @return bool|IncomingMessage
     */
    public function replyWithText(string $message): bool|IncomingMessage
    {
        return bot()->replyTo($this->id, $this->chat?->id)->sendMessage($message);
    }

}
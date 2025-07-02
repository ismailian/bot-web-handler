<?php

namespace TeleBot\System\Telegram\Traits;

/**
 * This trait allows direct replies to an incoming message
 */
trait CanReply
{

    /**
     * Reply with a text
     *
     * @param string $message
     * @return void
     */
    public function replyWithText(string $message): void
    {
        bot()->replyTo($this->id, $this->chat?->id);
        if (!bot()->sendMessage($message)) {
            logger()->error('failed to reply to message: ' . $this->id);
        }
    }

}
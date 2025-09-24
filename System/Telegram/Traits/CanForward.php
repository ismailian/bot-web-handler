<?php

namespace TeleBot\System\Telegram\Traits;

use Exception;

/**
 * This trait allows an incoming message to forward itself
 */
trait CanForward
{

    /**
     * Forward context message
     *
     * @param string $chatId chat id to forward this message to
     * @return void
     */
    public function forwardTo(string $chatId): void
    {
        try {
            if (!bot()->forwardMessage($chatId, $this->id, $this->chat?->id)) {
                logger()->error("failed to forward message [$this->id] to chat $chatId");
            }
        } catch (Exception $ex) {
            logger()->onException($ex);
        }
    }

}
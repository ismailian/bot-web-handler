<?php

namespace TeleBot\System\Telegram\Traits;

/**
 * This trait allows an incoming message to delete itself
 */
trait CanDelete
{

    /**
     * Delete context message
     *
     * @return void
     */
    public function delete(): void
    {
        if (!bot()->deleteMessage($this->id)) {
            logger()->error('failed to delete message: ' . $this->id);
        }
    }

}
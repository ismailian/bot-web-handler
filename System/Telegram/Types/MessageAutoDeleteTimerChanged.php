<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Telegram\Types;

class MessageAutoDeleteTimerChanged
{

    /** @var int $messageAutoDeleteTime New auto-delete time for messages in the chat; in seconds */
    public int $messageAutoDeleteTime;

    /**
     * default constructor
     *
     * @param array $messageAutoDeleteTimerChanged
     */
    public function __construct(protected readonly array $messageAutoDeleteTimerChanged)
    {
        $this->messageAutoDeleteTime = $this->messageAutoDeleteTimerChanged["message_auto_delete_time"];
    }

}
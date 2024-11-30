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

class MessageId
{

    /** @var string $message_id message id */
    public string $message_id;

    /**
     * default constructor
     *
     * @param array $messageId
     */
    public function __construct(array $messageId)
    {
        $this->message_id = $messageId['message_id'];
    }
}
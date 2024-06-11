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

class IncomingBusinessMessagesDeleted
{

    /** @var string $businessConnectionId business connection id */
    public string $businessConnectionId;

    /** @var Chat $chat chat in the business account */
    public Chat $chat;

    /** @var array $messageIds list of deleted messages ids */
    public array $messageIds;

    /**
     * default constructor
     *
     * @param array $incomingBusinessMessageDeleted
     */
    public function __construct(protected readonly array $incomingBusinessMessageDeleted)
    {
        $this->chat = new Chat($incomingBusinessMessageDeleted['chat']);
        $this->messageIds = $incomingBusinessMessageDeleted['message_ids'];
        $this->businessConnectionId = $incomingBusinessMessageDeleted['business_connection_id'];
    }

}
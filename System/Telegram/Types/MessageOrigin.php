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

use DateTime;
use Exception;

class MessageOrigin
{

    /** @var string $type
     * Type of the message origin.
     * MessageOriginUser: user
     * MessageOriginHiddenUser: hidden_user
     * MessageOriginChat: chat
     * MessageOriginChannel: channel
     */
    public string $type;

    /** @var DateTime $date Date the message was sent originally */
    public DateTime $date;

    /** @var string|null $senderUserName Name of the user that sent the message originally */
    public ?string $senderUserName = null;

    /** @var string|null $messageId Unique message identifier inside the chat */
    public ?string $messageId = null;

    /** @var Chat|null $senderChat Chat that sent the message originally */
    public ?Chat $senderChat = null;

    /** @var string|null $authorSignature
     * For messages originally sent by an anonymous chat administrator,
     * original message author signature
     */
    public ?string $authorSignature = null;

    /**
     * default constructor
     *
     * @param array $messageOrigin
     * @throws Exception
     */
    public function __construct(protected array $messageOrigin)
    {
        $this->type = $this->messageOrigin['type'];
        $this->date = new DateTime(date('Y-m-d H:m:s', $this->messageOrigin['date']));

        if (array_key_exists('message_id', $this->messageOrigin)) {
            $this->messageId = $this->messageOrigin['message_id'];
        }

        if (array_key_exists('sender_user_name', $this->messageOrigin)) {
            $this->senderUserName = $this->messageOrigin['sender_user_name'];
        }

        if (array_key_exists('sender_chat', $this->messageOrigin)) {
            $this->senderChat = new Chat($this->messageOrigin['sender_chat']);
        }

        if (array_key_exists('author_signature', $this->messageOrigin)) {
            $this->authorSignature = $this->messageOrigin['author_signature'];
        }
    }
}
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

class IncomingMessageReactionCountUpdated
{

    /** @var string $messageId message id */
    public string $messageId;

    /** @var Chat $chat the chat containing the message */
    public Chat $chat;

    /** @var DateTime $date date of the change */
    public DateTime $date;

    /** @var ReactionCount[] $reactions list of message reactions */
    public array $reactions;

    /**
     * default constructor
     *
     * @param array $incomingMessageReactionCountUpdated
     * @throws Exception
     */
    public function __construct(protected readonly array $incomingMessageReactionCountUpdated)
    {
        $this->messageId = $this->incomingMessageReactionCountUpdated['message_id'];
        $this->chat = new Chat($this->incomingMessageReactionCountUpdated['chat']);
        $this->date = new DateTime(date('Y-m-d H:i:d', $this->incomingMessageReactionCountUpdated['date']));
        $this->reactions = array_map(
            fn($r) => new ReactionCount($r),
            $this->incomingMessageReactionCountUpdated['reactions']
        );
    }

}
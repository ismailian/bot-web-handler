<?php

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
    public function __construct(protected array $incomingMessageReactionCountUpdated)
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
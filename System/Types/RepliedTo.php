<?php

namespace TeleBot\System\Types;

use DateTime;

class RepliedTo
{

    /** @var int $id message id */
    public int $id;

    /** @var DateTime $date message date */
    public DateTime $date;

    /** @var From $from sender */
    public From $from;

    /** @var Chat $chat message chat */
    public Chat $chat;

    /** @var string|null $text message content */
    public ?string $text = null;

    /**
     * default constructor
     *
     * @param array $repliedTo
     */
    public function __construct(protected array $repliedTo)
    {
        try {
            $this->id = (int) $this->repliedTo['message_id'];
            $this->date = new DateTime(date('Y-m-d H:i:s T', $this->repliedTo['date']));
            $this->from = new From($this->repliedTo['from']);
            $this->chat = new Chat($this->repliedTo['chat']);
            $this->text = $this->repliedTo['text'] ?? null;
        } catch (\Exception) {}
    }

}
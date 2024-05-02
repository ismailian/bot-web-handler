<?php

namespace TeleBot\System\Types;

use DateTime;

class ReplyTo
{

    /** @var int $id message id */
    public int $id;

    /** @var DateTime $date message date */
    public DateTime $date;

    /** @var Chat $chat message chat */
    public Chat $chat;

    /** @var string|null $text message content */
    public ?string $text = null;

    /**
     * default constructor
     *
     * @param array $replyTo
     */
    public function __construct(protected array $replyTo)
    {
        try {
            $this->id = (int) $this->replyTo['id'];
            $this->date = new DateTime(date('Y-m-d H:i:s T', $this->replyTo['date']));
            $this->text = $this->replyTo['text'] ?? null;
            $this->chat = new Chat($this->replyTo['chat']);
        } catch (\Exception) {}
    }

}
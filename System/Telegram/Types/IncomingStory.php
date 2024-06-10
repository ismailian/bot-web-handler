<?php

namespace TeleBot\System\Telegram\Types;

class IncomingStory
{

    /** @var int $id story id */
    public int $id;

    /** @var Chat $chat story chat */
    public Chat $chat;

    /**
     * default constructor
     *
     * @param array $incomingStory
     */
    public function __construct(protected readonly array $incomingStory)
    {
        $this->id = $this->incomingStory['id'];
        $this->chat = $this->incomingStory['chat'];
    }

}
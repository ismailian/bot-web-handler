<?php

namespace TeleBot\System\Telegram\Types;

class IncomingVideoChatEnded
{

    /** @var int|null $duration Video chat duration in seconds */
    public ?int $duration;

    /**
     * default constructor
     *
     * @param array $incomingVideoChatEnded
     */
    public function __construct(protected readonly array $incomingVideoChatEnded)
    {
        $this->duration = $this->incomingVideoChatEnded['duration'];
    }

}
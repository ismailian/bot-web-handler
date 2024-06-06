<?php

namespace TeleBot\System\Telegram\Types;

class IncomingVideoNote extends File
{

    /** @var int $length video width and height */
    public int $length;

    /** @var int $duration video duration */
    public int $duration;

    /** @var PhotoSize|null $thumbnail video thumbnail */
    public ?PhotoSize $thumbnail = null;

}
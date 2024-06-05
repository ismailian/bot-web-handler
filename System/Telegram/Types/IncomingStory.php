<?php

namespace TeleBot\System\Telegram\Types;

class IncomingStory
{

    /** @var int $id story id */
    public int $id;

    /** @var Chat $chat story chat */
    public Chat $chat;

}
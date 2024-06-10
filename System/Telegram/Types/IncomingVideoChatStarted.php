<?php

namespace TeleBot\System\Telegram\Types;

readonly class IncomingVideoChatStarted
{

    /**
     * default constructor
     *
     * @param array $incomingVideoChatStarted
     */
    public function __construct(protected array $incomingVideoChatStarted) {}

}
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
        $this->chat = new Chat($this->incomingStory['chat']);
    }

}
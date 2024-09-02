<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System;

use Exception;
use TeleBot\System\Http\Request;
use TeleBot\System\Telegram\BotApi;
use TeleBot\System\Telegram\Types\Event;
use TeleBot\System\Exceptions\InvalidUpdate;
use TeleBot\System\Exceptions\InvalidMessage;

class IncomingEvent
{

    /** @var array $config */
    public array $config;

    /** @var Event|null $event incoming message event */
    public ?Event $event = null;

    /** @var BotApi $telegram telegram client */
    protected BotApi $telegram;

    /**
     * default constructor
     * @throws InvalidMessage
     * @throws InvalidUpdate
     * @throws Exception
     */
    public function __construct()
    {
        $this->event = new Event(Request::json());
        $this->telegram = (new BotApi())
            ->setToken(getenv('TG_BOT_TOKEN'))
            ->setChatId($this->event->chat?->id ?? $this->event->from->id);
    }

}
<?php

namespace TeleBot\System;

use Exception;
use TeleBot\System\Http\HttpRequest;
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
        $this->event = new Event(HttpRequest::json());
        $this->telegram = (new BotApi())
            ->setToken(getenv('TG_BOT_TOKEN'))
            ->setChatId($this->event->chat?->id ?? $this->event->from->id);
    }

}
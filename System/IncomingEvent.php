<?php

namespace TeleBot\System;

use TeleBot\System\Http\HttpRequest;
use TeleBot\System\Telegram\BotClient;
use TeleBot\System\Telegram\Types\Event;
use TeleBot\System\Exceptions\InvalidUpdate;
use TeleBot\System\Exceptions\InvalidMessage;

class IncomingEvent
{

    /** @var array $config */
    public array $config;

    /** @var Event|null $event incoming message event */
    public ?Event $event = null;

    /** @var BotClient $telegram telegram client */
    protected BotClient $telegram;

    /**
     * default constructor
     *
     * @throws InvalidUpdate|InvalidMessage
     */
    public function __construct()
    {
        $chatId = null;
        $event = HttpRequest::event()['data'];
        foreach (array_keys($event) as $key) {
            if ($key !== 'update_id') {
                $chatId = $event[$key]['chat']['id'];
                break;
            }
        }

        $this->event = new Event($event);
        $this->telegram = (new BotClient())
            ->setToken(getenv('TG_BOT_TOKEN'))
            ->setChatId($chatId);
    }

}
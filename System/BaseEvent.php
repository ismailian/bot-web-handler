<?php

namespace TeleBot\System;

use TeleBot\System\Types\Event;
use TeleBot\System\Messages\Inbound;
use TeleBot\System\Exceptions\InvalidUpdate;
use TeleBot\System\Exceptions\InvalidMessage;

class BaseEvent
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
        $userId = null;
        $event = Inbound::event()['data'];
        foreach (array_keys($event) as $key) {
            if ($key !== 'update_id') {
                $userId = $event[$key]['from']['id'];
                break;
            }
        }

        $this->event = new Event($event);
        $this->telegram = (new BotClient())
            ->setToken(getenv('TG_BOT_TOKEN'))
            ->setChatId($userId);
    }

}
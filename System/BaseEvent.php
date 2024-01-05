<?php

namespace TeleBot\System;

use GuzzleHttp\Client;
use TeleBot\System\Messages\Inbound;
use TeleBot\System\Exceptions\InvalidUpdate;
use TeleBot\System\Exceptions\InvalidMessage;

class BaseEvent
{

    /** @var array $event */
    protected array $event;

    /** @var array $config */
    public array $config;

    protected object $telegram;

    protected Client $client;

    /**
     * default constructor
     *
     * @throws InvalidUpdate|InvalidMessage
     */
    public function __construct()
    {
        $this->event = Inbound::event()['data'];
        $this->telegram = (new BotClient())
            ->setToken(getenv('TG_BOT_TOKEN'))
            ->setChatId($this->event['message']['from']['id']);
    }

}
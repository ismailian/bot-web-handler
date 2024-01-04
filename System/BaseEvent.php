<?php

namespace TeleBot\System;

use GuzzleHttp\Client;
use TeleBot\System\Exceptions\InvalidMessage;
use TeleBot\System\Exceptions\InvalidUpdate;
use TeleBot\System\Messages\Inbound;

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
        $this->telegram = (object)[];
        $this->client = new Client([
            'verify' => false,
            'base_uri' => 'https://api.telegram.org'
        ]);
    }

}
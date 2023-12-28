<?php

namespace TeleBot\System;

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

    /**
     * default constructor
     *
     * @throws InvalidUpdate|InvalidMessage
     */
    public function __construct()
    {
        $this->event = Inbound::event();
        $this->telegram = (object)[];
    }

}
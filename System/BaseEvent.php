<?php

namespace TeleBot\System;

use TeleBot\System\Messages\Inbound;

class BaseEvent
{

    /** @var array $event */
    protected array $event;

    /** @var array $config */
    public array $config;

    protected object $telegram;

    public function __construct()
    {
        $this->event = Inbound::event();
        $this->telegram = (object)[];
    }

}
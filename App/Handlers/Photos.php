<?php

namespace TeleBot\App\Handlers;

use Exception;
use TeleBot\System\BaseEvent;
use TeleBot\System\Events\Photo;
use TeleBot\System\Messages\Outbound;

class Photos extends BaseEvent
{

    /**
     * handle photo event
     *
     * every photo sent is handled in this method
     *
     * @return void
     * @throws Exception
     */
    #[Photo]
    public function index(): void
    {
        Outbound::send($this->event, true);
    }

}
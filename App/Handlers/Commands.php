<?php

namespace TeleBot\App\Handlers;

use Exception;
use TeleBot\System\BaseEvent;
use TeleBot\System\Events\Command;
use TeleBot\System\Messages\Outbound;

class Commands extends BaseEvent
{

    /**
     * handle command: <b>/start</b>
     *
     * @return void
     * @throws Exception
     */
    #[Command('start')]
    public function start(): void
    {
        Outbound::send([
            'trigger' => 'Commands::start',
            'payload' => $this->event
        ], true);
    }

    /**
     * handle command: <b>/help</b>
     *
     * @return void
     * @throws Exception
     */
    #[Command('help')]
    public function help(): void
    {
        Outbound::send([
            'trigger' => 'Commands::help',
            'payload' => $this->event
        ], true);
    }

}
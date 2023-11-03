<?php

namespace TeleBot\System;

class BotHandler extends BaseHandler
{

    /**
     * start event monitoring
     *
     * @return void
     */
    public function start(): void
    {
        $this->handler->run();
    }

}
<?php

namespace TeleBot\System;

use Exception;

class AppHandler extends EventMapper
{

    /**
     * start event monitoring
     *
     * @return void
     */
    public function start(): void
    {
        try {
            if ($this->init()) {
                $this->handler->run();
                return;
            }

            $this->handler->fallback();
        } catch (Exception $ex) {}
    }

}
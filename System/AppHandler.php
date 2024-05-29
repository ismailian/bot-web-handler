<?php

namespace TeleBot\System;

use Exception;

class AppHandler extends BaseHandler
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
            }

            $this->handler->fallback();
        } catch (Exception $ex) {}
    }

}
<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System;

use Exception;

class App extends EventMapper
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
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
use TeleBot\System\Core\Handler;

class App extends EventMapper
{

    /**
     * handle incoming request
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            if ($this->init()) {
                Handler::run();
                return;
            }

            Handler::fallback();
        } catch (Exception $ex) {
            logger()->error($ex->getMessage());
        }
    }

}
<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\App\Handlers;

use TeleBot\System\IncomingEvent;

class Maintenance extends IncomingEvent
{

    /**
     * invokable method
     *
     * @return void
     */
    public function __invoke(): void
    {
        $this->telegram->sendMessage(
            "The bot is under maintenance. It will be operational soon!"
        );
    }

}
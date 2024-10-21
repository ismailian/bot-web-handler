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

use TeleBot\System\Telegram\BotApi;

class IncomingRequest
{

    /** @var array $config */
    public array $config = [];

    /** @var BotApi $telegram telegram client */
    protected BotApi $telegram;

    /**
     * default constructor
     */
    public function __construct()
    {
        $this->telegram = new BotApi();
    }

}
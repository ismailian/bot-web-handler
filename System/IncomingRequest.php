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

use TeleBot\System\Http\HttpRequest;
use TeleBot\System\Http\HttpResponse;
use TeleBot\System\Telegram\BotApi;

class IncomingRequest
{

    /** @var HttpRequest $request http request */
    protected HttpRequest $request;

    /** @var HttpResponse $response */
    protected HttpResponse $response;

    /** @var BotApi $telegram telegram client */
    protected BotApi $telegram;

    /** @var array $config */
    public array $config = [];

    /**
     * default constructor
     */
    public function __construct()
    {
        $this->request = new HttpRequest();
        $this->response = new HttpResponse();
        $this->telegram = (new BotApi())->setToken(getenv('TG_BOT_TOKEN'));

        $this->response::close();
    }

}
<?php

namespace TeleBot\System;

use TeleBot\System\Http\HttpRequest;
use TeleBot\System\Http\HttpResponse;
use TeleBot\System\Telegram\BotClient;

class IncomingRequest
{

    /** @var HttpRequest $request http request */
    protected HttpRequest $request;

    /** @var HttpResponse $response */
    protected HttpResponse $response;

    /** @var BotClient $telegram telegram client */
    protected BotClient $telegram;

    /**
     * default constructor
     */
    public function __construct()
    {
        $this->request = new HttpRequest();
        $this->response = new HttpResponse();

        $this->telegram = (new BotClient())->setToken(getenv('TG_BOT_TOKEN'));
    }

}
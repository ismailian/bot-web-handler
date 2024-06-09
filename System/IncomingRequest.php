<?php

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
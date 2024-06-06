<?php

namespace TeleBot\App\Handlers;

use Exception;
use TeleBot\System\IncomingEvent;
use GuzzleHttp\Exception\GuzzleException;
use TeleBot\System\Telegram\Events\Message;

class GetMyId extends IncomingEvent
{

    /**
     * handle all incoming messages
     *
     * @return void
     * @throws Exception|GuzzleException
     */
    #[Message]
    public function handle(): void
    {
        $reply = "Your user ID: <strong>{$this->event->from->id}</strong>\n";
        $reply .= "Current chat ID: <strong>{$this->event->chat->id}</strong>";

        $this->telegram->sendMessage($reply);
    }

}
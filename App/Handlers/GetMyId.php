<?php

namespace TeleBot\App\Handlers;

use Exception;
use TeleBot\System\BaseEvent;
use TeleBot\System\Events\Message;
use GuzzleHttp\Exception\GuzzleException;

class GetMyId extends BaseEvent
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
        $userId = $this->event->message->from->id;
        $reply = "Your user ID: <strong>{$userId}</strong>\n";
        $reply .= "Current chat ID: <strong>{$userId}</strong>";

        $this->telegram->sendMessage($reply);
    }

}
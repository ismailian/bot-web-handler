<?php

namespace TeleBot\App\Handlers;

use TeleBot\System\BaseEvent;
use TeleBot\System\Events\Message;
use GuzzleHttp\Exception\GuzzleException;

class GetMyId extends BaseEvent
{

    /**
     * handle all incoming messages
     *
     * @return void
     * @throws GuzzleException
     */
    #[Message]
    public function index(): void
    {
        try {
            $botToken = getenv('TG_BOT_TOKEN', true);
            $chatId = $this->event['message']['chat']['id'];
            $userId = $this->event['message']['chat']['id'];
            $message = "Your user ID: <strong>{$userId}</strong>\nCurrent chat ID: <strong>{$chatId}</strong>";

            $this->client->post("/bot{$botToken}/sendMessage", [
                'json' => [
                    'chat_id' => $this->event['message']['chat']['id'],
                    'text' => $message,
                    'parse_mode' => 'html'
                ]
            ]);
        } catch (\Exception $ex) {}
    }

}
<?php

namespace TeleBot\System\Messages;

use TeleBot\System\Events\Command;

class Inbound
{

    /** @var array $event */
    protected array $event;

    /**
     * default constructor
     */
    public function __construct() {}

    public static function context(): array
    {
        $payload = [
            'update_id' => 1234,
            'message' => [
                'chat' => [
                    'id' => 123,
                    'type' => 'private'
                ],
                'from' => [
                    'id' => 123,
                    'username' => 'user@123'
                ],
                'text' => '/start',
                'entities' => [
                    [
                        'offset' => 0,
                        'length' => 6,
                        'bot_command' => true,
                    ]
                ]
            ],
        ];

        return [
            'update_id' => 123,
            'type' => Command::class,
            'data' => $payload
        ];
    }

}
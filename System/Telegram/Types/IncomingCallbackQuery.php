<?php
/*
 * This file is part of the Bot Web Handler project.
 * Copyright 2024-2024 Ismail Aatif
 * https://github.com/ismailian/bot-web-handler
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeleBot\System\Telegram\Types;

class IncomingCallbackQuery
{

    /** @var string $id callback id */
    public string $id;

    /** @var string $chatInstance chat instance */
    public string $chatInstance;

    /** @var User $from sender */
    public User $from;

    /** @var IncomingMessage $message callback message */
    public IncomingMessage $message;

    /** @var mixed query data */
    public mixed $data;

    /**
     * default constructor
     *
     * @param array $callback
     */
    public function __construct(protected readonly array $callback)
    {
        $this->id = $this->callback['id'];
        $this->chatInstance = $this->callback['chat_instance'];

        $this->from = new User($this->callback['from']);
        $this->message = new IncomingMessage($this->callback['message']);
        $this->data = $this->callback['data'];

        if (($json = json_decode($this->data, true))) {
            $this->data = $json;
        }
    }

}
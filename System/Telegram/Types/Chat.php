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

class Chat
{

    /** @var string $id chat id */
    public string $id;

    /** @var string $chat type */
    public string $type = 'private';

    /** @var string|null $username unique username */
    public ?string $username = null;

    /** @var string|null first name */
    public ?string $firstName = null;

    /** @var string|null $lastName last name */
    public ?string $lastName = null;

    /** @var string|null $title chat title */
    public ?string $title = null;

    /**
     * default constructor
     *
     * @param array $chat
     */
    public function __construct(protected readonly array $chat)
    {
        $this->id = $this->chat['id'];
        $this->type = $this->chat['type'];
        $this->username = $this->chat['username'] ?? null;
        $this->firstName = $this->chat['first_name'] ?? null;
        $this->lastName = $this->chat['last_name'] ?? null;
        $this->title = $this->chat['title'] ?? null;
    }

}
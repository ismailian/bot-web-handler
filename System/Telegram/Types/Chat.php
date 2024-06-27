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

use TeleBot\System\Telegram\Enums\InlineChatType;

class Chat
{

    /** @var string $id chat id */
    public string $id;

    /** @var InlineChatType $type chat type */
    public InlineChatType $type = InlineChatType::PRIVATE;

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
        $this->username = $this->chat['username'] ?? null;
        $this->firstName = $this->chat['first_name'] ?? null;
        $this->lastName = $this->chat['last_name'] ?? null;
        $this->title = $this->chat['title'] ?? null;
        $this->type = match($this->chat['type']) {
            'group' => InlineChatType::GROUP,
            'supergroup' => InlineChatType::SUPERGROUP,
            'channel' => InlineChatType::CHANNEL,
            'sender' => InlineChatType::SENDER,
            default => InlineChatType::PRIVATE,
        };
    }

}
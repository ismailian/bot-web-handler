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

class ChatShared
{

    /** @var int $requestId Identifier of the request */
    public int $requestId;

    /** @var string $chatId Identifier of the shared chat */
    public string $chatId;

    /** @var string|null $title Title of the chat, if the title was requested by the bot. */
    public ?string $title = null;

    /** @var string|null $username Username of the chat, if the username was requested by the bot and available. */
    public ?string $username = null;

    /** @var PhotoSize[]|null $photo Available sizes of the chat photo, if the photo was requested by the bot */
    public ?array $photo = null;

    /**
     * default constructor
     *
     * @param array $chatShared
     */
    public function __construct(protected array $chatShared)
    {
        $this->requestId = $this->chatShared['request_id'];
        $this->chatId = $this->chatShared['chat_id'];
        $this->title = $this->chatShared['title'] ?? null;
        $this->username = $this->chatShared['username'] ?? null;

        if (array_key_exists('photo', $this->chatShared)) {
            $this->photo = array_map(
                fn($photo) => new PhotoSize($photo),
                $chatShared['photo']
            );
        }
    }

}
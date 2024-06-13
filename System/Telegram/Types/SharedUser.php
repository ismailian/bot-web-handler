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

class SharedUser
{

    /** @var string $userId Identifier of the shared user */
    public string $userId;

    /** @var string|null $firstName First name of the user, if the name was requested by the bot */
    public ?string $firstName;

    /** @var string|null $lastName Last name of the user, if the name was requested by the bot */
    public ?string $lastName = null;

    /** @var string|null $username Username of the user, if the username was requested by the bot */
    public ?string $username = null;

    /** @var PhotoSize[]|null $photo Available sizes of the chat photo, if the photo was requested by the bot */
    public ?array $photo = null;

    /**
     * default constructor
     *
     * @param array $sharedUser
     */
    public function __construct(protected array $sharedUser)
    {
        $this->userId = $sharedUser['id'];
        $this->firstName = $sharedUser['first_name'] ?? null;
        $this->lastName = $sharedUser['last_name'] ?? null;
        $this->username = $sharedUser['username'] ?? null;

        if (array_key_exists('photo', $this->sharedUser)) {
            $this->photo = array_map(
                fn($photo) => new PhotoSize($photo),
                $sharedUser['photo']
            );
        }
    }

}
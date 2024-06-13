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

class UsersShared
{

    /** @var int $requestId Identifier of the request */
    public int $requestId;

    /** @var SharedUser[] $users Information about users shared with the bot */
    public array $users;

    /**
     * default constructor
     *
     * @param array $usersShared
     */
    public function __construct(protected array $usersShared)
    {
        $this->requestId = $this->usersShared['request_id'];
        $this->users = array_map(
            fn($user) => new SharedUser($user),
            $this->usersShared
        );
    }

}
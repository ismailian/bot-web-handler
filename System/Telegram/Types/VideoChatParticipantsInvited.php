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

class VideoChatParticipantsInvited
{

    /** @var User[] $users New members that were invited to the video chat */
    public array $users;

    /**
     * default constructor
     *
     * @param array $videoChatParticipantsInvited
     */
    public function __construct(protected readonly array $videoChatParticipantsInvited)
    {
        $this->users = array_map(
            fn($user) => new User($user),
            $this->videoChatParticipantsInvited['users']
        );
    }

}